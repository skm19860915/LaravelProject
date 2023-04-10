<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\Dropbox;
use App\User;
use App\Models\Client_profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\FirmCase;
use App\Models\Firm;
use App\Models\Newclient;
use App\Models\FirmSetting;
use App\Models\ClientFiles;
use App\Models\Event;
use App\Models\ClientFamily;
use App\Models\ClientNotes;
use App\Models\Country;
use App\Models\Lead;
use App\Models\DocumentRequest;
use App\Models\ClientDocument;
use App\Models\Transaction;
use App\Models\QBInvoice;
use App\Models\SchedulePayment;
use App\Models\CaseType;
use App\Models\AdminTask;
use App\Models\ClientTask;
use App\Models\ClientInformation;
use App\Models\TextMessage;
use App\Models\UserMeta;
use App\Models\Questionnaire;
use QuickBooksOnline\API\Facades\Invoice;
use Carbon\Carbon;
use App\Models\Log;
use App;
use DB;
use App\Notifications\DatabaseNotification;
use Notification;
/* --------------QuickBook--------------- */
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Exception\ServiceException;

/* --------------QuickBook--------------- */

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class FirmNewClientController extends Controller {

    private $api_client;
    private $content_client;
    private $access_token;

    public function __construct(Dropbox $dropbox) {

        require_once(base_path('public/QuickBook/gettoken.php'));
        require_once(base_path('public/calenderApi/settings.php'));
        $this->api_client = $dropbox->api();
        $this->content_client = $dropbox->content();
        $this->access_token = '';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $data = Auth::User();
        $firm_id = $data->firm_id;
        $firm = Firm::select('*')->where('id', $firm_id)->first();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $provider = new Google([
            'clientId'     => CLIENT_ID,
            'clientSecret' => CLIENT_SECRET,
            'redirectUri'  => CLIENT_REDIRECT_URL,
            'accessType'   => 'offline',
            'hostedDomain' => "",
        ]);

        if (!empty($_GET['error'])) {

        } elseif (empty($_GET['code'])) {


        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            // State is invalid, possible CSRF attack in progress
            unset($_SESSION['oauth2state']);

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            update_user_meta($data->id, 'access_token', $token->getToken());
            update_user_meta($data->id, 'refresh_token', $token->getRefreshToken());
            return redirect(CalenderRedirect())->with('success', 'Synced with Google Calendar');
            //exit();
        }
        CalenderRedirectSessionSave();
        return view('firmadmin.client.index', compact('firm'));
        
    }

    public function getData() {
        $data = Auth::User();
        $users = Newclient::select('new_client.*')
                ->where('firm_id', $data->firm_id)
                ->get();

        foreach ($users as $key => $value) {

            $event = Event::select('event.*')
                    ->where('related_id', $value->id)
                    ->where('title', "CLIENT")
                    ->orderBy('id', 'DESC')
                    ->first();
            $cases = FirmCase::select('*')
                    ->where('client_id', $value->user_id)
                    ->count();
            if (!empty($event)) {
                $users[$key]->event = Carbon::parse($event->e_date)->format('m-d-Y') . " " . Carbon::parse($event->e_time)->format('g:i a');
                $users[$key]->oldevent = strtotime($event->e_date);
            } else {
                $users[$key]->event = "";
            }
            $cname = $value->first_name;
            if(!empty($value->middle_name)) {
                $cname .= ' '.$value->middle_name;
            }
            if(!empty($value->last_name)) {
                $cname .= ' '.$value->last_name;
            }
            $users[$key]->name = $cname;
            $users[$key]->todaytime = strtotime('now');
            $users[$key]->created = Carbon::parse($value->created_at)->format('m-d-Y g:i a');
            $users[$key]->cases = $cases;
        }
        return datatables()->of($users)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $data = Auth::User();

        //$this->QuickbookToken($data->id,$data->QBcompanyID,$data->QBToken,$data->QBTokenDate);
        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);        
        return view('firmadmin.client.create', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function family($id) {
        $client = Newclient::select('*')->where('id', $id)->first();
        // $countries = DB::table("countries")->get();
        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);
        return view('firmadmin.client.create_family', compact('id', 'client', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notes($id) {
        $client_id = $id;
        $client_name = Newclient::select('first_name', 'middle_name', 'last_name')->where('id', $id)->first();

        return view('firmadmin.client.create_notes', compact('client_id', 'client_name'));
    }

    public function create_client(Request $request) {
        $newdata = array();
        $current_firm_id = Auth::User()->firm_id;

        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'email' => 'string|email|unique:users|unique:new_client',
        ]);

        /* if ($validator->fails()) {
          return redirect('firm/client')->withInfo('Mendatory fields are required!');
          } */
        $data1 = $request->all();
        if ($validator->fails()) {
            return redirect()->back()->with(['data' => $data1])->withErrors($validator);
        }
        $user_id = 0;
        $pass = str_random(8);
        $data = [
            'name' => $request->first_name . " " . $request->middle_name . " " . $request->last_name,
            'role' => 6,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];
        $newdata['userdata'] = $data;
        $user = User::create($data);
        $user_id = $user->id;
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', Auth::User()->firm_id)->first();
        if ($request->is_portal_access == 1) {
            

            /* --------------------Notifications--------------- */
            

            $msg = 'Firm ' . $firm_name->firm_name . ' created your account successfully!';
            $n_link = url('profile');
            $message = collect(['title' => 'Firm Admin Created your account', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);

            Notification::send($user, new DatabaseNotification($message));
            /* --------------------Notifications--------------- */
        }

        $record = $request->all();
        $record['client_aliases'] = json_encode($request->client_aliases1);
        $record['birth_address'] = json_encode($request->birth_address1);
        $record['residence_address'] = json_encode($request->residence_address);
        $record['firm_id'] = Auth::User()->firm_id;
        $newdata['ClientDetails'] = $record;
        $newclient = Newclient::create($record);
        Newclient::where('id', $newclient->id)->update(['user_id' => $user_id]);



        /* Document image upload start */
        if (!empty($request->image_path)) {
            $client_file = Storage::put('client_doc', $request->image_path);
            if ($client_file) {
                Newclient::where('id', $newclient->id)->update(['image_path' => $client_file]);
            }
        }
        /* Document image upload close */


        if ($request->is_portal_access == 1) {
            $username = $request->first_name . " " . $request->middle_name . " " . $request->last_name;
            $useremail = $request->email;
            $pass = $pass;
            
            $LoginPage = url('login');
            
            $remove = array(
                'FirmName' => $firm_name->firm_name,
                'ClientName' => $username,
                'Email' => $useremail,
                'Password' => $pass,
                'LoginPage' => $LoginPage,
                'AttorneyRecord' => $firm_name->firm_admin_name
            );

            $email = EmailTemplate(44, $remove);

            $sub = 'Welcome to '.$firm_name->firm_name.'!';
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $useremail,
                'subject' => $sub,
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
        }

        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a Client " . $request->first_name . " " . $request->middle_name . " " . $request->last_name
        ];
        Log::create($logdata);
        $CID = $this->QBCreateClient($newdata);
        Newclient::where('id', $newclient->id)->update(['QBCustomerID' => $CID]);


        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Client created successfully! Next, add a case to your client.';

        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('firm/client/add_new_case').'/'.$newclient->id;
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        if ($newclient) {
            return redirect('firm/client')->with('success', 'Client created successfully! Next, add a case to your client.');
        } else {
            return redirect('firm/client')->with('error', 'client not created, please try again');
        }
    }

    public function QBCreateClient($saveddata) {
        $data = Auth::User();
        if ($data->QBConnect == 1) {
            
            $invoiceData = json_decode($data->QBToken);

            $refreshToken = $invoiceData->getRefreshToken;
            $QBcompanyID = $invoiceData->getRealmID;
            $ClientID = $invoiceData->getclientID;
            $client_secret = $invoiceData->getClientSecret;
            $BaseURL = $invoiceData->getBaseURL;

            $conf = require_once(base_path('public/QuickBook/conf.php'));
            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $conf['oauth_redirect_uri'],
                        'scope' => $conf['oauth_scope'],
                        'baseUrl' => "https://quickbooks.api.intuit.com"
            ));
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $client_secret);
            try{
            $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            }   catch (ServiceException  $e) {
                        return 0;
                    }
            $newAccessTokenObj->setRealmID($QBcompanyID);
            $newAccessTokenObj->setBaseURL($BaseURL);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

            $dataService->updateOAuth2Token($newAccessTokenObj);

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $dataService->throwExceptionOnError(true);
            /*
             * Update the OAuth2Token of the dataService object
             */

            $dataService->updateOAuth2Token($newAccessTokenObj);





            // $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            // $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
            // $dataService->updateOAuth2Token($refreshedAccessTokenObj);
            // $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
            // $dataService->throwExceptionOnError(true);
            $residence_address = json_decode($saveddata['ClientDetails']['residence_address']);
            $Line1 = '';
            $City = 'Mountain View';
            $CountrySubDivisionCode = 'CA';
            $Country = 'USA';
            $PostalCode = '94043';
            if(!empty($residence_address->address)) {
              $Line1 = $residence_address->address;
            }
            if(!empty($residence_address->city)) {
              $City =  getCityName($residence_address->city);
            }
            if(!empty($residence_address->state)) {
              $CountrySubDivisionCode =  getStateName($residence_address->state);
            }
            if(!empty($residence_address->country)) {
              $Country = getCountryName($residence_address->country);
            }
            $theResourceObj = Customer::create([
                        "BillAddr" => [
                            "Line1" => $Line1,
                            "City" => $City,
                            "Country" => $Country,
                            "CountrySubDivisionCode" => $CountrySubDivisionCode,
                            "PostalCode" => $PostalCode
                        ],
                        "Notes" => ".",
                        "Title" => "Mr",
                        "GivenName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "MiddleName" => $saveddata['ClientDetails']['middle_name'],
                        "FamilyName" => $saveddata['ClientDetails']['last_name'],
                        "Suffix" => "Jr",
                        "FullyQualifiedName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "CompanyName" => $saveddata['ClientDetails']['first_name'],
                        "DisplayName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "PrimaryPhone" => [
                            "FreeFormNumber" => $saveddata['ClientDetails']['cell_phone']
                        ],
                        "PrimaryEmailAddr" => [
                            "Address" => $saveddata['ClientDetails']['email']
                        ]
            ]);



            $resultingObj = $dataService->Add($theResourceObj);

            $error = $dataService->getLastError();
            if ($error) {
                # echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                # echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                # echo "The Response message is: " . $error->getResponseBody() . "\n";
            } else {
                "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
                $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
                $xmlBody . "\n";
            }
            return $resultingObj->Id;
        }
    }

    public function create_client_event(Request $request) {

        $res = array();
        $reminder_arr = array();
        $reminders = array(
                'useDefault' => FALSE,
                'overrides' => array()
              );
        foreach ($request->event_reminder as $key => $value) {
            $rname = $value['name'];
            $rval = $value['value'];
            if ($rname == 'event_reminder[count][]') {
                $reminder_arr['count'][] = $rval;
            }
            if ($rname == 'event_reminder[type][]') {
                $reminder_arr['type'][] = $rval;
            }
            // pre($value);
        }
        foreach ($reminder_arr['type'] as $k => $v) {
            $m = $reminder_arr['count'][$k];
            if($v == 'minutes') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m
                                        );
            }
            else if($v == 'hours') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60
                                        );
            }
            else if($v == 'days') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24
                                        );
            }
            else if($v == 'weeks') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24*7
                                        );
            }
            else if($v == 'months') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24*30
                                        );
            }
            else if($v == 'years') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*60*24*30*12
                                        );
            }

        }
        
        $validator = Validator::make($request->all(), [
                    's_date' => 'required',
                    'event_title' => 'required',
                    'event_type' => 'required',
                    'who_consult_with' => 'required'
        ]);
        // print_r($validator->fails());
        // die();
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
            // return redirect('firm/create_event')->withInfo('Mendatory fields are required!');
        }

        $s_date = $request->s_date;
        $e_date = $request->e_date;
        if ($request->event_type == 'Reminder') {
            $lead_event_data = [
                'title' => "CLIENT",
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_description' => $request->event_description,
                'related_id' => $request->lead_id,
                's_date' => date('Y-m-d', strtotime($s_date)),
                's_time' => date('h:i A', strtotime($s_date)),
                'e_date' => date('Y-m-d', strtotime($s_date)),
                'e_time' => date('h:i A', strtotime($s_date)),
                'who_consult_with' => json_encode($request->who_consult_with),
                'attorney' => Auth::User()->id
            ];
        } else {
            $lead_event_data = [
                'title' => "CLIENT",
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_description' => $request->event_description,
                'related_id' => $request->lead_id,
                's_date' => date('Y-m-d', strtotime($s_date)),
                's_time' => date('h:i A', strtotime($s_date)),
                'e_date' => date('Y-m-d', strtotime($e_date)),
                'e_time' => date('h:i A', strtotime($e_date)),
                'who_consult_with' => json_encode($request->who_consult_with),
                'attorney' => Auth::User()->id
            ];
        }

        
        $lead_event_data['event_reminder'] = json_encode($reminder_arr);
        $event_id1 = 0;
        if ($request->reschedule == "true") {
            $lead_event_data['coutner'] = $request->coutner;
            $event = Event::where('related_id', $request->lead_id)->update($lead_event_data);
        } else {
            //$event = Event::create($lead_event_data);
            if($request->event_id) {
                $event = Event::where('id', $request->event_id)->update($lead_event_data);
                $event_id1 = $request->event_id;
            }
            else {
                $event = Event::create($lead_event_data);
                $event_id1 = $event->id;
                $remove = array(
                    'time' => date('h:i A', strtotime($s_date)),
                    'date' => date('Y-m-d', strtotime($s_date)),
                    'titleofevent' => $request->event_title
                );
                $email = EmailTemplate(12, $remove);
                if(!empty($request->who_consult_with)) {
                    foreach ($request->who_consult_with as $k => $v) {
                        $u = User::select('*')->where('id', $v)->first();
                        $args = array(
                            'bodyMessage' => $email['MSG'],
                            'to' => $u->email,
                            'subject' => $email['Subject'],
                            'from_name' => 'TILA',
                            'from_email' => 'no-reply@tilacaseprep.com'
                        );
                        send_mail($args);
                    }
                }
            }
        }
        

        if (isset($request->create_lead_with_event)) {

            return redirect()->route('firm.client')->with('success', 'Firm Lead Create and Schedule Consult successfully!');
        }


        $res['status'] = true;
        $res['msg'] = 'Client Event created successfully!';
        echo json_encode($res);
        $access_token = get_user_meta(Auth::User()->id, 'access_token');
        if (!empty($access_token)) {
            $user_timezone = GetUserCalendarTimezone($access_token);
            $etime = array();
            $etime['start_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            if($request->event_type == 'Reminder') {
                $etime['end_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            }
            else {
                $etime['end_time'] = date('Y-m-d', strtotime($e_date)) . 'T' . date('H:i:s', strtotime($e_date));
            }

            if(empty($request->event_id)) {
                $gid = CreateCalendarEvent('primary', $request->event_title, 0, $etime, $user_timezone, $access_token, $reminders);
                Event::where('id', $event_id1)->update(['google_id' => $gid]);
            }
        }

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = $firm_name->firm_name . ' Firm admin created a Event';

        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));

        $n_link = url('firm/client/client_event') . '/' . $request->lead_id;
        $touser = User::where('id', Auth::User()->id)->first();
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link' => $n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        if($request->who_consult_with) {
            foreach ($request->who_consult_with as $k => $v) {
                $touser = User::where('id', $v)->first();
                Notification::send($touser, new DatabaseNotification($message));
            }
        }
        
        /* --------------------Notifications--------------- */




        die();
        if ($event) {
            //return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        } else {
            //return redirect('firm/create_event')->withInfo(' not created, please try again');
        }
    }

    public function created() {
        return view('firmadmin.client.created');
    }

    public function show($id) {
        $data = array();
        $client = Newclient::where('id', $id)->first();
        $data['total_case'] = FirmCase::select('*')->where('client_id', $client->user_id)->count();
        $data['total_task'] = ClientTask::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->count();
        $data['total_note'] = ClientNotes::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->count();
        $data['total_event'] = Event::select('*')->where('related_id', $id)->count();
        $data['total_billing'] = Transaction::select('*')->where('user_id', $client->user_id)->sum('amount');
        $data['totla_forms'] = ClientInformation::select('*')->where('client_id', $client->user_id)->count();
        $requested_doc = DocumentRequest::select('*')->where('client_id', $id)->count();
        $client_doc = ClientDocument::select('*')->where('client_id', $id)->count();
        $data['total_document'] = intval($requested_doc) + intval($client_doc);
        $task = ClientTask::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->get();
        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        return view('firmadmin.client.show', compact('client', 'data', 'task', 'firm'));
    }

    public function profile($id) {
        $client = Newclient::where('id', $id)->first();
        $ques = Questionnaire::select('*')
                ->where('client_id', $client->user_id)
                ->get();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            // ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->where('client_family.type', 'Beneficiary')
        ->where('client_family.client_id', $client->id)
        ->where('users.role_id' ,'=', '7')
        ->first();
        return view('firmadmin.client.profile1', compact('client', 'ques', 'family_alllist', 'beneficiary_list'));
    }

    public function client_task($id) {
        $client = Newclient::where('id', $id)->first();
        //$task = ClientTask::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->get();
        $task = ClientTask::select('client_task.*', 'users.name')
                ->where('client_task.related_id', $id)
                ->where('client_task.task_for', 'CLIENT')
                ->leftJoin('users', 'users.id', '=', 'client_task.created_by')
                ->get();
        $atask = AdminTask::select('*')
            //->where('admintask.allot_user_id',Auth::User()->id)
            ->where('task_type', 'ADMIN_TASK')
            ->where('client_task', $client->user_id)
            ->get(); 
        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        return view('firmadmin.client.client_task', compact('client', 'task', 'firm', 'atask'));
    }

    public function add_client_task($id) {
        $client = Newclient::where('id', $id)->first();
        $firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
        return view('firmadmin.client.add_client_task', compact('id', 'client', 'firm'));
    }

    public function edit_client_task($id, $tid) {
        $client = Newclient::where('id', $id)->first();
        $task = ClientTask::where('id', $tid)->first();
        return view('firmadmin.client.edit_client_task', compact('id', 'client', 'task'));
    }

    public function insert_client_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('firm/client/add_client_task/' . $request->client_id)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'task_for' => 'CLIENT',
            'related_id' => $request->client_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            // 's_date' => date('Y-m-d', strtotime($dates[0])),
            // 's_time' => date('h:i A', strtotime($dates[0])),
            'e_date' => date('Y-m-d', strtotime($request->date)),
            'e_time' => date('h:i A', strtotime($request->date)),
            'created_by' => Auth::User()->id,
            'status' => 0
        ];
        ClientTask::create($data);
        return redirect('firm/client/client_task/' . $request->client_id)->withInfo('Task created successfully');
    }

    public function update_client_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('firm/client/edit_client_task/' . $request->client_id.'/'.$request->tid)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'e_date' => date('Y-m-d', strtotime($request->date)),
            'e_time' => date('h:i A', strtotime($request->date)),
            'status' => $request->status
        ];
        ClientTask::where('id', $request->tid)->update($data);
        return redirect('firm/client/client_task/' . $request->client_id)->withInfo('Task update successfully');
    }

    public function client_event($id) {
        $client = Newclient::where('id', $id)->first();
        $event = Event::select('*')->where('related_id', $id)->get();
        return view('firmadmin.client.client_event', compact('client', 'event'));
    }

    public function client_edit_event($id, $eid) {
        $client = Newclient::where('id', $id)->first();
        $event = Event::select('*')->where('id', $eid)->first();
        $data = Auth::User();
        $users = User::select('users.*', 'roles.name as role_name')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('firm_id', $data->firm_id)
                // ->where('users.id', '!=', $data->id)
                ->whereIn('role_id', ['4', '5'])
                ->get();

        return view('firmadmin.client.edit_client_event', compact('client', 'event', 'users'));
    }

    public function client_case($id) {
        $client = Newclient::where('id', $id)->first();
        $case = FirmCase::select('case.*', 'u1.name')
                ->where('case.client_id', $client->user_id)
                ->leftJoin('users as u1', 'u1.id', 'case.user_id')
                ->orderBy('created_at', 'DESC')
                ->get();
        $firm = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
        return view('firmadmin.client.client_case', compact('client', 'case', 'firm'));
    }

    public function add_new_case($id) {
        $currunt_user = Auth::User();
        $client = Newclient::where('id', $id)->first();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $data = Auth::User();
        $searchResults = \Stripe\Customer::all([
                    "email" => $data->email,
                    "limit" => 1,
                    "starting_after" => null
        ]);
        $cust = '';
        $card = '';
        if ($searchResults->data) {
            $cust = $searchResults->data[0];
            $card = $cust->sources->data;
        }
        if(!empty($card)) {
            foreach ($card as $k => $c) {
                $exp_month = $c->exp_month;
                $exp_year = $c->exp_year;

                $c_month = date('m');
                $c_year = date('Y');
                if($exp_year < $c_year) {
                    unset($card[$k]);
                }
                else if($exp_year == $c_year && $exp_month < $c_month) {
                    unset($card[$k]);
                }
            }  
        }
        $user = User::select('id', 'name')
                ->where('firm_id', $data->firm_id)
                ->whereIn('role_id', [4,5])
                ->get();
        $firm = Firm::select('*')->where('id', $data->firm_id)->first();
        $case_type = CaseType::select('id', 'Case_Category', 'Case_Type', 'is_additional_service', 'VP_Pricing', 'additional_services')->where('status', 1)->get();
        foreach ($case_type as $key => $value) {
            $case_type[$key]->additional_services = json_decode($value->additional_services, true);
        }
        $I_864_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor')->first()['VP_Pricing'];
        $I_864A_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864A, Contract Between Sponsor and Household Member')->first()['VP_Pricing'];
        $I_DS260_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'DS-260 for Additional Derivative Beneficiary (online only)')->first()['VP_Pricing'];
        $I_Affidavit_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'Draft a Letter/Affidavit')->first()['VP_Pricing'];
        
        return view('firmadmin.client.add_new_case', compact('user', 'client', 'case_type', 'firm', 'card', 'currunt_user', 'I_864_Cost', 'I_864A_Cost', 'I_DS260_Cost', 'I_Affidavit_Cost'));
    }


    public function client_billing($id) {
        $client = Newclient::where('id', $id)->first();
        
        $invoice = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $id)->where('status', '!=', 3)->orderBy('id', 'DESC')->get();

        $scheduled = SchedulePayment::select('*')->where('schedule_for', 'CLIENT')->where('related_id', $id)->where('status', '!=', 3)->orderBy('id', 'DESC')->get();

        $count = array();
        $count['total_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $id)->where('status', '!=', 3)->sum('amount');
        $count['paid_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $id)->where('status', '=', 1)->sum('paid_amount');
        $count['outstanding_amount'] = $count['total_amount'] - $count['paid_amount'];

        if($count['total_amount']) {
            $count['paid_percent'] = intval(($count['paid_amount']/$count['total_amount'])*100);
        }
        else {
            $count['paid_percent'] = 0;
        }

        return view('firmadmin.client.client_billing', compact('client', 'invoice', 'scheduled', 'count'));
    }

    public function client_invoice($id) {
        $client = Newclient::where('id', $id)->first();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->leftJoin('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        return view('firmadmin.client.client_invoice', compact('client', 'invoice'));
    }

    public function client_scheduled($id, $id1 = 0) {
        $data = Auth::User();
        $client = Newclient::where('id', $id)->first();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid',  'schedule_payment.*')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->join('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        // pre($invoice);
        // die();
        $invoice1 = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->LeftJoin('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        $qbinvoice = array();
        if($id1) {
            $qbinvoice = QBInvoice::select('*')
            ->where('id',$id1)
            ->first();
        }

        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $data->firm_id)
                ->first();

        $account_id = get_user_meta($firm->uid, 'account_id');
        $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
        if(empty($account_id) || empty($SECRET_KEY)) {
            $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
            $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards?reference=Client'.$id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch); 
        $cards1 = json_decode($result,true);
        // pre($cards1);
        // die();
        $cards = array();
        if(!empty($cards1['results'])) {
            $cards = $cards1['results'];
        }

        return view('firmadmin.client.client_scheduled', compact('client', 'invoice', 'qbinvoice', 'invoice1', 'id1', 'cards'));
    }

    public function client_schedule_history($id, $id1 = 0) {
        $data = Auth::User();
        $client = Newclient::where('id', $id)->first();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid',  'schedule_payment.*')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->join('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        $invoice1 = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->LeftJoin('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        $qbinvoice = array();
        if($id1) {
            $qbinvoice = QBInvoice::select('*')
            ->where('id',$id1)
            ->first();
        }
        return view('firmadmin.client.client_schedule_history', compact('client', 'invoice', 'qbinvoice', 'invoice1', 'id1'));
    }

    public function client_acceptpayment($id, $id1 = 0) {
        $data = Auth::User();
        $client = Newclient::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('client_id', $id)->where('status', '!=', 3)->get();
        $qbinvoice = array();
        if($id1) {
            $qbinvoice = QBInvoice::select('*')
            ->where('id',$id1)
            ->first();
        }
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $data->firm_id)
                ->first();

        $account_id = get_user_meta($firm->uid, 'account_id');
        $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
        if(empty($account_id) || empty($SECRET_KEY)) {
            $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
            $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards?reference=Client'.$id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch); 
        $cards1 = json_decode($result,true);
        $cards = array();
        if(!empty($cards1['results'])) {
            $cards = $cards1['results'];
        }
        return view('firmadmin.client.client_acceptpayment', compact('client', 'invoice', 'qbinvoice', 'id1'));
    }

    public function view_client_invoice($id) {
        // $client = Newclient::where('id', $id)->first();
        // $invoice = QBInvoice::select('*')->where('client_id', $id)->get();
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('new_client.id',$invoice->client_id)
        ->first();
        $firm = Firm::select('firms.*', 'users.id as uid', 'users.*')
                ->where('firms.id', $invoice->firm_id)
                ->join('users', 'users.email', '=', 'firms.email')
                ->first(); 
        $transaction = Transaction::select('*')->where('type', 'Invoice')->where('related_id', $id)->get();
        return view('firmadmin.client.view_client_invoice', compact('client', 'invoice', 'firm', 'transaction'));
    }

    public function edit_client_invoice($id) {
        // $client = Newclient::where('id', $id)->first();
        // $invoice = QBInvoice::select('*')->where('client_id', $id)->get();
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('new_client.id',$invoice->client_id)
        ->first();
        $firm = Firm::where('id', $invoice->firm_id)->first(); 
        return view('firmadmin.client.edit_client_invoice', compact('client', 'invoice', 'firm'));
    }

    public function update_client_invoice(Request $request) {
        //pre($request->all());
        // $idata = [
        //     'amount' => $request->casecost,
        //     'invoice_items' => json_encode($request->invoice_items),
        //     'client_name' => $request->client_name,
        //     'client_address' => $request->client_address,
        //     'tax_id' => $request->tax_id,
        //     'payment_method' => $request->payment_method,
        //     'status' => 0,
        // ];
        $idata = [
                'client_name' => $request->name,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'destination_account' => $request->destination_account,
                'comment' => $request->comments,
                'amount' => $request->total_amount,
            ];
        QBInvoice::where('id', $request->invoice_id)->update($idata);
        return redirect('firm/client/client_invoice/'.$request->firmclient)->with('success','Invoice updated successfully');
    }

    public function add_new_invoice($id) {
        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
                ->join('new_client', 'users.id', '=', 'new_client.user_id')
                ->where('users.id', $id)
                ->where('users.role_id', 6)
                ->first();
        // pre($client);
        // die();
        // foreach ($client as $key => $value) {
        //  $client[$key]->birth_address = json_decode($value->birth_address);
        // }
        $firm = Firm::where('id', $data->firm_id)->first();
        // pre($firm);
        $account_id = get_user_meta(Auth::User()->id, 'account_id');
        $SECRET_KEY = get_user_meta(Auth::User()->id, 'SECRET_KEY');
        $is_card = false;
        if (!empty($account_id) && !empty($SECRET_KEY)) {
            $is_card = true;
        }
        return view('firmadmin.client.add_new_invoice', compact('client', 'firm', 'is_card'));
    }

    public function create_client_invoice(Request $request) {
        $data = Auth::User();
        $client = Newclient::where('id', $request->client_id)->first();

        $QBCustomerID1 = $client->QBCustomerID;
        $itemArr = array();

        // foreach ($request->invoice_items['item_name'] as $k1 => $v1) {
        //     $qty = $request->invoice_items['item_qty'][$k1];
        //     $cost = $request->invoice_items['item_cost'][$k1];
        //     $arr1 = [
        //                 "Description" => $v1,
        //                 "Amount" => ($qty*$cost),
        //                 "DetailType" => "SalesItemLineDetail",
        //                 "SalesItemLineDetail" => [
        //                     "Qty" => $qty,
        //                     "UnitPrice" => $cost,
        //                     "ItemRef" => [
        //                         "value" => 1,
        //                         "name" => $v1
        //                     ]
        //                 ]
        //             ];
        //     $itemArr[] = $arr1;
        // }
        // if ($data->QBConnect && $request->payment_method == 'Card') {
        // pre($request->all());
        // pre($data->QBConnect);
        // die();
        if ($data->QBConnect) {

            $invoiceData = json_decode($data->QBToken);

            $refreshToken = $invoiceData->getRefreshToken;
            $QBcompanyID = $invoiceData->getRealmID;
            $ClientID = $invoiceData->getclientID;
            $client_secret = $invoiceData->getClientSecret;
            $BaseURL = $invoiceData->getBaseURL;

            $conf = require_once(base_path('public/QuickBook/conf.php'));

            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $conf['oauth_redirect_uri'],
                        'scope' => $conf['oauth_scope'],
                        'baseUrl' => "https://quickbooks.api.intuit.com"
            ));
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $client_secret);

            try{
            $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            
            $newAccessTokenObj->setRealmID($QBcompanyID);
            $newAccessTokenObj->setBaseURL($BaseURL);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

            $dataService->updateOAuth2Token($newAccessTokenObj);

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
            $dataService->throwExceptionOnError(true); 

            $arr1 = [
                    "Description" =>$request->description,
                    "Amount" => $request->total_amount,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => 1,
                        "UnitPrice" => $request->total_amount,
                        "ItemRef" => [
                            "value" => 1,
                            "name" => $request->description
                        ]
                    ]
                ];
            $itemArr[] = $arr1;
            $customer = $dataService->FindbyId('customer', $client->QBCustomerID);

            

            if(empty($customer)) {
                $saveddata = array();
                $saveddata['ClientDetails']['first_name'] = $client->first_name;
                $saveddata['ClientDetails']['middle_name'] = $client->middle_name;
                $saveddata['ClientDetails']['last_name'] = $client->last_name;
                $saveddata['ClientDetails']['email'] = $client->email;
                $saveddata['ClientDetails']['cell_phone'] = $client->cell_phone;
                $saveddata['ClientDetails']['residence_address'] = $client->residence_address;

                $residence_address = json_decode($saveddata['ClientDetails']['residence_address']);
                $Line1 = '';
                $City = 'Mountain View';
                $CountrySubDivisionCode = 'CA';
                $Country = 'USA';
                $PostalCode = '94043';
                if(!empty($residence_address->address)) {
                  $Line1 = $residence_address->address;
                }
                if(!empty($residence_address->city)) {
                  $City =  getCityName($residence_address->city);
                }
                if(!empty($residence_address->state)) {
                  $CountrySubDivisionCode =  getStateName($residence_address->state);
                }
                if(!empty($residence_address->country)) {
                  $Country = getCountryName($residence_address->country);
                }
                $theResourceObj = Customer::create([
                        "BillAddr" => [
                            "Line1" => $Line1,
                            "City" => $City,
                            "Country" => $Country,
                            "CountrySubDivisionCode" => $CountrySubDivisionCode,
                            "PostalCode" => $PostalCode
                        ],
                        "Notes" => ".",
                        "Title" => "Mr",
                        "GivenName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "MiddleName" => $saveddata['ClientDetails']['middle_name'],
                        "FamilyName" => $saveddata['ClientDetails']['last_name'],
                        "Suffix" => "Jr",
                        "FullyQualifiedName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "CompanyName" => $saveddata['ClientDetails']['first_name'],
                        "DisplayName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                        "PrimaryPhone" => [
                            "FreeFormNumber" => $saveddata['ClientDetails']['cell_phone']
                        ],
                        "PrimaryEmailAddr" => [
                            "Address" => $saveddata['ClientDetails']['email']
                        ]
                    ]);

                $resultingObj = $dataService->Add($theResourceObj);

                $CID = $resultingObj->Id;
                Newclient::where('id', $client->id)->update(['QBCustomerID' => $CID]);
                $QBCustomerID1 = $CID;
            }

            $invoiceToCreate = Invoice::create([
                "DocNumber" => "101",
                "Line" => $itemArr,
                "CustomerRef" => [
                    "value" => $QBCustomerID1,
                    "name" => $client->first_name.' '.$client->middle_name.' '.$client->last_name
                ]
            ]);
            $resultObj = $dataService->Add($invoiceToCreate);
            $error = $dataService->getLastError();
            $invoice_id = $resultObj->Id;
            }   
            catch (ServiceException  $e) {
                $invoice_id = 0;
            }
            $idata = [
                'user_id' => 0,
                'firm_id' => $data->firm_id,
                'client_id' => $request->client_id,
                'client_name' => $request->name,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'destination_account' => $request->destination_account,
                'comment' => $request->comments,
                'amount' => $request->total_amount,
                'invoice_for' => 'CLIENT',
                'Customer_ID' => $QBCustomerID1,
                'invoice_id' => 0,
                'status' => 0,
            ];

            $qbinvoice = QBInvoice::create($idata);

            $link = url('firm/firmclient/billing/invoice/viewinvoice/'.$qbinvoice->id);
            $remove = array(
                'Client_Name' => $client->first_name.' '.$client->middle_name.' '.$client->last_name,
                'Link' => $link,
            );
            $email = EmailTemplate(34, $remove);
            // $client->email
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $client->email,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);

            $msg = 'An invoice has been generated by your firm admin. Click here to view and complete the payment process';
            $touser = User::where('id', $client->user_id)->first();
            $message = collect(['title' => 'Invoice Notification', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$link]);
            Notification::send($touser, new DatabaseNotification($message));

            return redirect('firm/client/client_acceptpayment/' . $request->client_id.'/'.$qbinvoice->id)->with('success', 'Successful');
        } else {
            $idata = [
                'user_id' => 0,
                'firm_id' => $data->firm_id,
                'client_id' => $request->client_id,
                'client_name' => $request->name,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'destination_account' => $request->destination_account,
                'comment' => $request->comments,
                'amount' => $request->total_amount,
                'invoice_for' => 'CLIENT',
                'Customer_ID' => $client->QBCustomerID,
                'invoice_id' => 0,
                'status' => 0,
            ];
            
            $qbinvoice = QBInvoice::create($idata);

            $link = url('firm/firmclient/billing/invoice/viewinvoice/'.$qbinvoice->id);
            $remove = array(
                'Client_Name' => $client->first_name.' '.$client->middle_name.' '.$client->last_name,
                'Link' => $link,
            );
            $email = EmailTemplate(34, $remove);
            // $client->email
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $client->email,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);

            $msg = 'An invoice has been generated by your firm admin. Click here to view and complete the payment process';
            $touser = User::where('id', $client->user_id)->first();
            $message = collect(['title' => 'Invoice Notification', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$link]);
            Notification::send($touser, new DatabaseNotification($message));
            
            return redirect('firm/client/client_acceptpayment/' . $request->client_id.'/'.$qbinvoice->id)->with('success', 'Successful');
        } 
    }

    public function document($id) {
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $data = Auth::User();
        $searchResults = \Stripe\Customer::all([
                    "email" => $data->email,
                    "limit" => 1,
                    "starting_after" => null
        ]);
        $cust = '';
        $card = '';
        if ($searchResults->data) {
            $cust = $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $client = Newclient::where('id', $id)->first();
        $requested_doc = DocumentRequest::select('*')->where('client_id', $id)->get();
        $client_doc = ClientDocument::select('*')->where('client_id', $id)->get();
        return view('firmadmin.client.document', compact('client', 'requested_doc', 'client_doc', 'card'));
    }

    public function create_client_case(Request $request) {
        // pre($request->all());
        // die();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        $card_source = '';
        extract($_REQUEST);
        $currunt_user = Auth::User();  
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
        if($firm_name->account_type == 'CMS') {
            $validator = Validator::make($request->all(), [
                    'case_category' => 'required',
                    'casetype' => 'required',
                    'firmuser' => 'required',
                    'firmclient' => 'required'
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                    'case_category' => 'required',
                    'casetype' => 'required'
                    // 'firmuser' => 'required'
            ]);
        }      
        if ($validator->fails()) {
            $user = User::select('id', 'name')
                ->where('firm_id', $currunt_user->firm_id)
                ->where('role_id', 5)
                ->count();
            if(!$user) {
                return redirect('firm/client/add_new_case/'.$request->client_id)->withInfo('Please add user/client first');
            }
            else {
                return redirect('firm/client/add_new_case/'.$request->client_id)->withInfo('Mendatory fields are required!');
            }
        }

        $firm_id = Auth::User()->firm_id;
        
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
        
        $firm_id = Auth::User()->firm_id;
        $adds_arr = array();
        $adds_arr1 = array();
        $adds_arr2 = array();
        $adds_arr3 = array();
        if(isset($request->additional_service)) {
            $adds_arr['additional_service'] = $request->additional_service;
            $adds_arr['additional_service_cost'] = $request->additional_service_cost;
            $adds_arr['status'] = 1;
            $adds_arr2['additional_service'] = $adds_arr;
        }
        if(isset($request->additional_service1)) {
            $adds_arr1['additional_service1'] = $request->additional_service1;
            $adds_arr1['additional_service1_cost'] = $request->additional_service1_cost;
            $adds_arr1['status'] = 1;
            $adds_arr2['additional_service1'] = $adds_arr1;
        }
        if(isset($request->declaration)) {
            $adds_arr3['declaration'] = $request->declaration;
            $adds_arr3['declaration_other'] = $request->declaration_other;
            $adds_arr3['status'] = 1;
            $adds_arr2['declaration'] = $adds_arr3;
        }
        $adds_arr2['nvc_packet'] = $request->nvc_packet;
        $adds_arr2['nvc_packet_quantity'] = $request->nvc_packet_quantity;
        $data = [
            'client_id' => $request->firmclient,
            'case_category' => $request->case_category,
            'case_type' => $request->casetype,
            'user_id' => $request->firmuser,
            'assign_paralegal' => $request->assign_paralegal,
            'case_cost' => $request->casecost,
            'VP_Assistance' => $request->VP_Assistance,
            'firm_id' => $firm_id,
            'additional_service' => json_encode($adds_arr2),
            'created_by' => Auth::User()->id
        ];
        $case_id = FirmCase::create($data);
        
        $client = DB::table('new_client')->where('user_id', $request->firmclient)->first();

        $client_name = '';
        $caseType = $request->casetype;
        if(!empty($client)) {
            $client_name = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
        }
        if ($case_id) {
            if(!empty($request->case_comment)) {
                $ndata = [
                    'task_for' => 'CASE',
                    'related_id' => $case_id->id,
                    'notes' => $request->case_comment,
                    'subject' => '',
                    'created_by' => Auth::User()->id
                ];
                ClientNotes::create($ndata);
            }
            $data['case_id'] = $case_id->id;
            if($currunt_user->role_id == 4) {
                if ($request->VP_Assistance) {
                    $data['task_type'] = 'Assign_Case';
                    $data['task'] = 'Assign Case to VP';
                    $data['firm_admin_id'] = $currunt_user->id;
                    $data['status'] = 0;
                    $atask = AdminTask::create($data);
                    /* --------------------Notifications--------------- */
                    $msg = 'Firm ' . $firm_name->firm_name . ' paid for VP Assistance successfully!';
                    $touser = User::where('id', 1)->first();
                    $n_link = url('admin/task/edit').'/'.$atask->id;
                    $message = collect(['title' => 'Firm Admin Payment', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                    Notification::send($touser, new DatabaseNotification($message));

                    $msg = 'Case created successfully! A TILA VP will be assigned to this case shortly.';
                    $touser = User::where('email', $firm_name->email)->first();
                    $n_link = url('firm/case/show').'/'.$case_id->id;
                    $message = collect(['title' => 'New Case Approvel', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                    Notification::send($touser, new DatabaseNotification($message));
                    /* --------------------Notifications--------------- */


                    $casecost1 = intval(str_replace('$', '', $request->casecost)) * 100;
                    \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
                    $searchResults = \Stripe\Customer::all([
                        "email" => $currunt_user->email,
                        "limit" => 1,
                        "starting_after" => null
                    ]);
                    $cus_id = '';
                    if($searchResults->data) {
                        $cus_id =  $searchResults->data[0]->id;
                        if(!empty($stripeToken)) {
                            if(!empty($request->savecard)) {
                                $source = \Stripe\Customer::createSource(
                                  $cus_id,
                                  [
                                      'source' => $stripeToken,
                                  ]
                                );
                                $card_source = $source->id;
                            }
                        }
                    }
                    else {
                        $cus = \Stripe\Customer::create([
                          'description' => $currunt_user->name,
                          'email' => $currunt_user->email,
                          'name' => $currunt_user->name
                        ]);
                        $cus_id =  $cus->id;
                        if(!empty($request->savecard)) {
                            $source = \Stripe\Customer::createSource(
                              $cus_id,
                              [
                                  'source' => $stripeToken,
                              ]
                            );
                            $card_source = $source->id;
                        }
                    }
                    if(!empty($request->card_source)) {
                        $card_source = $request->card_source;
                    }
                    if(!empty($card_source)) {
                        $charge = \Stripe\Charge::create([
                          'customer' => $cus_id,
                          'amount' => $casecost1,
                          'currency' => 'usd',
                          'source' => $card_source
                        ]);
                    }
                    else {
                        if(!empty($request->savecard)) {
                            $charge = \Stripe\Charge::create([
                              'customer' => $cus_id,
                              'amount' => $casecost1,
                              'currency' => 'usd',
                              'source' => $stripeToken
                            ]);
                        }
                        else {
                            $charge = \Stripe\Charge::create([
                              // 'customer' => $cus_id,
                              'amount' => $casecost1,
                              'currency' => 'usd',
                              'source' => $stripeToken
                            ]);
                        }
                    }
                    
                    $data['tx_id'] = $charge->id;
                    $data['amount'] = $charge->amount;
                    $data['type'] = 'Case';
                    $data['user_id'] = $currunt_user->id;
                    $data['related_id'] = $case_id->id;
                    $data['responce'] = json_encode($charge);
                    $data['paymenttype'] = 1;
                    Transaction::create($data);

                    $LoginPage = url('login');
                    $TILAAdmin = User::select('*')->where('id', 1)->first();
                    $remove = array(
                        'TILAAdmin' => $TILAAdmin->name,
                        'FirmName' => $firm->firm_name,
                        'ClientName' => $client_name,
                        'CaseType' => $request->case_category,
                        'LoginPage' => $LoginPage
                    );
                    $email = EmailTemplate(29, $remove);
                    $args = array(
                        'bodyMessage' => $email['MSG'],
                        'to' => $TILAAdmin->email,
                        'subject' => $email['Subject'],
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                }

                if($firm->account_type == 'CMS') {
                    $client_record = Newclient::select('first_name', 'middle_name', 'last_name', 'email')->where('user_id', $request->firmclient)->first();
                    $firm_user = User::where('id', $request->firmuser)->first();
                    
                    $message = FirmSetting::where('title', 'New Case Notification')->where('category', 'EMAIL')->where('firm_id', $firm_id)->first();
                    $message->message = str_replace("{client, name}", $client_name, $message->message);
                    $message->message = str_replace("{case type}", $caseType, $message->message);

                    $username = $firm_user->name;
                    $useremail = $firm_user->email;

                    $msg = "Hello, $username.<br>";
                    $msg .= $message->message . "<br>";
                    /* --------------------------Assign User Email--------------------------- */
                    $remove = array(
                        'AssignedFirmUser' => $username,
                        'ClientName' => $client_name,
                        'CaseType' => $caseType,
                        'FirmCaseReferenceNumber' => $firm_id,
                    );
                    $email = EmailTemplate(27, $remove);
                    $args = array(
                        'bodyMessage' => $email['MSG'],
                        'to' => $client_record->email,
                        'subject' => $email['Subject'],
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    /* --------------------------Assign User Email--------------------------- */
                    /* --------------------------Client Email--------------------------- */
                    $remove = array(
                        'FirmName' => $firm_name->firm_name,
                        'ClientNumber' => $client_name,
                        'CaseType' => $caseType,
                    );
                    $email = EmailTemplate(22, $remove);
                    $args = array(
                        'bodyMessage' => $email['MSG'],
                        'to' => $useremail,
                        'subject' => $email['Subject'],
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    /* --------------------------Client Email--------------------------- */
                }
            }
            else {

                if ($request->VP_Assistance) {
                    /* --------------------Notifications--------------- */
                    $msg = Auth::User()->name.' has created new case, please review';
                    $touser = User::where('email', $firm_name->email)->first();
                    $n_link = url('firm/case/edit').'/'.$case_id->id;
                    $message = collect(['title' => 'New Case Approvel', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                    Notification::send($touser, new DatabaseNotification($message));

                    $msg = 'Case created successfully! A TILA VP will be assigned to this case shortly.';
                    $touser = User::where('email', $firm_name->email)->first();
                    $n_link = url('firm/case/show').'/'.$case_id->id;
                    $message = collect(['title' => 'New Case Approvel', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                    Notification::send($touser, new DatabaseNotification($message));
                    /* --------------------Notifications--------------- */

                    FirmCase::where('id', $case_id->id)->update(['status' => -1]);

                    $username = Auth::User()->name;
                    $firm_admin_name =  $firm_name->firm_admin_name;
                    $msg1 = "Hi, $firm_admin_name.<br>";
                    $msg1 .= "$username has created new case, please review<br>";
                    $msg1 .= $n_link;
                    
                    $msg = EmptyEmailTemplate($msg1);
                    $args = array (
                        'bodyMessage' => $msg,
                        'to' => $firm_name->email,
                        'subject' => 'New Case Approvel',
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                }
                else {
                    $client = DB::table('new_client')->where('user_id', $request->firmclient)->first();
                    $client_name = '';
                    $caseType = $request->casetype;
                    if($firm->account_type == 'CMS') {
                        $client_record = Newclient::select('first_name', 'middle_name', 'last_name', 'email')->where('user_id', $request->firmclient)->first();
                        $client_name = $client_record->first_name . ' ' . $client_record->middle_name . ' ' . $client_record->last_name;
                        $firm_user = User::where('id', $request->firmuser)->first();
                        
                        $message = FirmSetting::where('title', 'New Case Notification')->where('category', 'EMAIL')->where('firm_id', $firm_id)->first();
                        $message->message = str_replace("{client, name}", $client_name, $message->message);
                        $message->message = str_replace("{case type}", $caseType, $message->message);

                        $username = $firm_user->name;
                        $useremail = $firm_user->email;

                        $msg = "Hello, $username.<br>";
                        $msg .= $message->message . "<br>";
                        /* --------------------------Assign User Email--------------------------- */
                        $remove = array(
                            'AssignedFirmUser' => $username,
                            'ClientName' => $client_name,
                            'CaseType' => $caseType,
                            'FirmCaseReferenceNumber' => $firm_id,
                        );
                        $email = EmailTemplate(27, $remove);
                        $args = array(
                            'bodyMessage' => $email['MSG'],
                            'to' => $client_record->email,
                            'subject' => $email['Subject'],
                            'from_name' => 'TILA',
                            'from_email' => 'no-reply@tilacaseprep.com'
                        );
                        send_mail($args);
                        /* --------------------------Assign User Email--------------------------- */
                        /* --------------------------Client Email--------------------------- */
                        $remove = array(
                            'FirmName' => $firm_name->firm_name,
                            'ClientNumber' => $client_name,
                            'CaseType' => $caseType,
                        );
                        $email = EmailTemplate(22, $remove);
                        $args = array(
                            'bodyMessage' => $email['MSG'],
                            'to' => $useremail,
                            'subject' => $email['Subject'],
                            'from_name' => 'TILA',
                            'from_email' => 'no-reply@tilacaseprep.com'
                        );
                        send_mail($args);
                        /* --------------------------Client Email--------------------------- */
                    }
                    
                    /* --------------------Notifications--------------- */
                    $msg = 'Firm ' . $firm_name->firm_name . ' create case successfully!';
                    $touser = User::where('id', $request->firmclient)->first();
                    $n_link = url('firm/clientcase/show').'/'.$case_id->id;
                    $message = collect(['title' => 'Firm Admin Create Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                    if($firm_name->account_type == 'CMS') {
                        Notification::send($touser, new DatabaseNotification($message));

                        $msg = 'You have been assigned a case #'.$case_id->id;
                        $n_link = url('firm/case/show').'/'.$case_id->id;
                        $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                        $caseuser = User::where('id', $request->firmuser)->first();
                        Notification::send($caseuser, new DatabaseNotification($message));
                    }
                }
            }

            $case_type = CaseType::select('Required_Forms')
                    ->where('Case_Category', $request->case_category)
                    ->where('Case_Type', $request->casetype)
                    ->first();
            $Required_Forms = json_decode($case_type->Required_Forms);
            // if($Required_Forms) {
            //     foreach ($Required_Forms as $k => $val) {
            //         $data = json_encode(
            //             [
            //                 'query' => $val,
            //                 "include_highlights" => true
            //             ]
            //         );

            //         $response = $this->api_client->request(
            //             'POST', '/2/files/search_v2',
            //             [
            //                 'headers' => [
            //                     'Authorization' => 'Bearer ' . env('DROPBOX_TOKEN'),
            //                     'Content-Type' => 'application/json'
            //                 ],
            //                 'body' => $data
            //             ]);

            //         $search_results = json_decode($response->getBody(), true);
            //         $matches = $search_results['matches'];
                    
            //         if($matches) {
            //             foreach ($matches as $k => $v) {
            //                 $metadata = $v['metadata']['metadata'];
            //                 extract($metadata);
            //                 $name1 = strtoupper($name);
            //                 $val1 = str_replace('.PDF', '', $name1);
            //                 if($val1 == $val) {
            //                     $path = $id;
            //                     $clientr = new \Spatie\Dropbox\Client(env('DROPBOX_TOKEN'));
            //                     $a = $clientr->download($path);
            //                     $file = 'forms/'.$name;
            //                     Storage::put($file, stream_get_contents($a));
                                
            //                     $datac = [
            //                         'client_id' => $request->firmclient,
            //                         'case_id' => $case_id->id,
            //                         'firm_id' => $firm_id,
            //                         'file' => $file,
            //                         'file_type' => $name
            //                     ];
            //                     ClientInformation::create($datac);
            //                 }

            //             }
            //         }
            //     }
            // } 
            if($Required_Forms) {
                foreach ($Required_Forms as $k => $val) {
                    $v = explode(',', $val);
                    $file = 'forms/all/'.strtolower($v[0]).'.pdf';
                    $datac = [
                         'client_id' => $request->firmclient,
                         'case_id' => $case_id->id,
                         'firm_id' => $firm_id,
                         'file' => $file,
                         'file_type' => $val   
                    ];
                    ClientInformation::create($datac);
                }
            }

            if(!empty($request->additional_service)) {
                $file1 = '';
                $file_dataname = '';
                if(in_array('I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor', $request->additional_service)) {
                    $file1 = 'forms/all/i-864.pdf';
                    $file_dataname = 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor';
                    $data = [
                         'client_id' => $request->firmclient,
                         'case_id' => $case_id->id,
                         'firm_id' => $firm_id,
                         'file' => $file1,
                         'file_type' => $file_dataname   
                    ];
                    ClientInformation::create($data);
                }
                if(in_array('I-864A, Contract Between Sponsor and Household Member', $request->additional_service)) {
                    $file1 = 'forms/all/i-864a.pdf';
                    $file_dataname = 'I-864A, Contract Between Sponsor and Household Member';
                    $data = [
                         'client_id' => $request->firmclient,
                         'case_id' => $case_id->id,
                         'firm_id' => $firm_id,
                         'file' => $file1,
                         'file_type' => $file_dataname   
                    ];
                    ClientInformation::create($data);
                }
            }
        }

        if (!empty($request->case_file)) {
            foreach ($request->case_file as $key => $v) {
                $file = $v->getClientOriginalName();
                $fname = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename  = $fname.time().'.'. $extension;
                $file_arr = $v->storeAs('case_doc', $filename);
                $data = [
                    'client_id' => $client->id,
                    'case_id' => $case_id->id,
                    'uploaded_by' => Auth::User()->id,
                    'document' => $file_arr,
                    'title' => $filename,
                    'description' => $filename
                ];
                ClientDocument::create($data);
            }
            
            // if ($case_file) {
            //     FirmCase::where('id', $case_id->id)->update(['case_file_path' => $case_file]);
            // }
        }
        /* Document image upload close */

        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a case id  " . $case_id->id
        ];
        Log::create($logdata);
        /* --------------------Notifications--------------- */
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' create case successfully!';
        $touser = User::where('id', $request->firmclient)->first();
        $n_link = url('firm/clientcase/show').'/'.$case_id->id;
        $message = collect(['title' => 'Firm Admin Create Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));

            $msg = 'You have been assigned a case #'.$case_id->id;
            $n_link = url('firm/case/show').'/'.$case_id->id;
            $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            $caseuser = User::where('id', $request->firmuser)->first();
            Notification::send($caseuser, new DatabaseNotification($message));
        }
        /* --------------------Notifications--------------- */
        if ($case_id->id) {
            if ($request->VP_Assistance) {
                /* --------------------------SuperAdmin--------------------------- */
                // $remove = array(
                //     'FirmName' => $firm_name->firm_name,
                //     'ClientNumber' => $client_name,
                //     'CaseType' => $caseType,
                // );
                $LoginPage = url('login');
                $TILAAdmin = User::select('*')->where('id', 1)->first();
                $remove = array(
                    'TILAAdmin' => $TILAAdmin->name,
                    'FirmName' => $firm->firm_name,
                    'ClientName' => $client_name,
                    'CaseType' => $caseType,
                    'LoginPage' => $LoginPage
                );
                $email = EmailTemplate(29, $remove);
                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => Eadmin(),
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                send_mail($args);
                /* --------------------------SuperAdmin--------------------------- */
                app('App\Http\Controllers\HomeController')->DEVQBInvoiceCreation($case_id);
                return redirect('firm/client/client_case/' . $request->client_id)->with('success', 'Case created successfully! A TILA VP will be assigned to this case shortly.');
            } 
            else {
                return redirect('firm/client/client_case/' . $request->client_id)->with('success', 'Case created successfully! A TILA VP will be assigned to this case shortly.');
            }
        } else {
            return redirect('firm/client/client_case/' . $request->client_id)->with('error', 'Case not created, please try again');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_family(Request $request) {
        $record = $request->all();
        $record['name'] = $request->first_name;
        if(!empty($request->middle_name)) {
            $record['name'] .= ' '.$request->middle_name;
        }
        if(!empty($request->last_name)) {
            $record['name'] .= ' '.$request->last_name;
        }

        $newdata = array();
        $current_firm_id = Auth::User()->firm_id;

        $cl_email = $request->email;
        
        if ($request->is_portal_access || !empty($cl_email)) {
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }
        else {
            if(empty($request->email)) {
                $cl_email = 'dummy'.time().'@tilacaseprep.com';
            }
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    // 'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $record['email'] = $cl_email;
        
        app('App\Http\Controllers\HomeController')->CreateClientFamily($record, $current_firm_id);
        ;

        // $record['dob'] = date('Y-m-d', strtotime($record['dob']));
        $check = ClientFamily::create($record);

        if ($check) {
            return redirect('firm/client/view_family/' . $request->client_id)->with('success', 'Family added successfully!');
        } else {
            return redirect('firm/client/view_family/' . $request->client_id)->with('error', 'client family not created, please try again');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_notes(Request $request) {

        /* $record['created_by'] = Auth::User()->id;

          $check = ClientNotes::create($record);

          if ($check) {
          return redirect('firm/client/show/'.$request->client_id)->with('success','Firm client family created successfully!');
          }else{
          return redirect('firm/client/show/'.$request->client_id)->with('error','client family not created, please try again');
          } */


        $res = array();
        $validator = Validator::make($request->all(), [
                    'subject' => 'required|string',
                    'note' => 'required|string',
        ]);
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
        }
        $data = [
            'task_for' => 'CLIENT',
            'related_id' => $request->client_id,
            'subject' => $request->subject,
            'notes' => $request->note,
            'created_by' => Auth::User()->id
        ];

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' Firm admin Created Some Note For Client';

        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        //Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('firm/client/view_notes') . '/' . $request->client_id;
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        if($request->note_id) {
            $res['msg'] = 'Note update successfully!';
            ClientNotes::where('id', $request->note_id)->update($data);
        }
        else {
            $res['msg'] = 'Note created successfully!';
            $note = ClientNotes::create($data);
        }

        $res['status'] = true;
        echo json_encode($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_family($id) {
        // $family_list = ClientFamily::where('client_id', $id)->orderBy('id', 'desc')->get();

        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('client_id', $id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        // pre($family_list);
        // die;
        $client = Newclient::select('*')->where('id', $id)->first();

        return view('firmadmin.client.family_show', compact('family_list', 'client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view_notes($id) {

        $data = Auth::User();
        $client = Newclient::select('*')->where('id', $id)->first();
        $msg = array();

        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $id)
                ->where('client_notes.task_for', 'CLIENT')
                ->orderBy('id', 'DESC')
                ->get();

        foreach ($notes_list as $k => $v) {
            $is_edit = false; 
            if(Auth::User()->id == $v->created_by) {
                $is_edit = true;
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->notes,
                        'created_by' => $v->username,
                        'type' => 'Note',
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                        'is_edit' => $is_edit
                    );
        }
        

        return view('firmadmin.client.notes_show', compact('msg', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $client = Newclient::where('id', $id)->first();
        $client_name = Newclient::select('first_name', 'middle_name', 'last_name')->where('id', $id)->first();
        $countries = DB::table("countries")->get();
        $client->client_aliases = json_decode($client->client_aliases);

        $r_states = array();
        $r_cities = array();
        $client->residence_address = json_decode($client->residence_address);
        if (!empty($client->residence_address->country)) {
            $r_states = DB::table("regions")
                    ->where("country_id", $client->residence_address->country)
                    ->pluck("name", "id");
        }
        if (!empty($client->residence_address->state)) {
            $r_cities = DB::table("cities")
                    ->where("region_id", $client->residence_address->state)
                    ->pluck("name", "id");
        }

        $states = array();
        $cities = array();
        $client->birth_address = json_decode($client->birth_address);
        if (!empty($client->birth_address->country)) {
            $states = DB::table("regions")
                    ->where("country_id", $client->birth_address->country)
                    ->pluck("name", "id");
        }
        if (!empty($client->birth_address->state)) {
            $cities = DB::table("cities")
                    ->where("region_id", $client->birth_address->state)
                    ->pluck("name", "id");
        }

        return view('firmadmin.client.edit', compact('client', 'countries', 'states', 'cities', 'client_name', 'r_states', 'r_cities'));
    }

    public function update(Request $request) {

        $user_id = $request->user_id;
        $client = Newclient::where('user_id', $user_id)->first();
        if (Auth::User()->QBConnect) {
            $invoiceData = json_decode(Auth::User()->QBToken);

            $refreshToken = $invoiceData->getRefreshToken;
            $QBcompanyID = $invoiceData->getRealmID;
            $ClientID = $invoiceData->getclientID;
            $client_secret = $invoiceData->getClientSecret;
            $BaseURL = $invoiceData->getBaseURL;

            $conf = require_once(base_path('public/QuickBook/conf.php'));

            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $conf['oauth_redirect_uri'],
                        'scope' => $conf['oauth_scope'],
                        'baseUrl' => "https://quickbooks.api.intuit.com"
            ));
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $client_secret);

            try {
                $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                
                $newAccessTokenObj->setRealmID($QBcompanyID);
                $newAccessTokenObj->setBaseURL($BaseURL);
                $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

                $dataService->updateOAuth2Token($newAccessTokenObj);

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
                $dataService->throwExceptionOnError(true); 

                $arr1 = [
                    "Description" =>$request->description,
                    "Amount" => $request->total_amount,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "Qty" => 1,
                        "UnitPrice" => $request->total_amount,
                        "ItemRef" => [
                            "value" => 1,
                            "name" => $request->description
                        ]
                    ]
                ];
                $itemArr = array();
                $itemArr[] = $arr1;
                $customer = $dataService->FindbyId('customer', $client->QBCustomerID);

                $QBCustomerID1 = $client->QBCustomerID;

                if(empty($customer)) {
                    $saveddata = array();
                    $saveddata['ClientDetails']['first_name'] = $client->first_name;
                    $saveddata['ClientDetails']['middle_name'] = $client->middle_name;
                    $saveddata['ClientDetails']['last_name'] = $client->last_name;
                    $saveddata['ClientDetails']['email'] = $client->email;
                    $saveddata['ClientDetails']['cell_phone'] = $client->cell_phone;
                    $saveddata['ClientDetails']['residence_address'] = $client->residence_address;

                    $residence_address = json_decode($saveddata['ClientDetails']['residence_address']);
                    $Line1 = '';
                    $City = 'Mountain View';
                    $CountrySubDivisionCode = 'CA';
                    $Country = 'USA';
                    $PostalCode = '94043';
                    if(!empty($residence_address->address)) {
                      $Line1 = $residence_address->address;
                    }
                    if(!empty($residence_address->city)) {
                      $City =  getCityName($residence_address->city);
                    }
                    if(!empty($residence_address->state)) {
                      $CountrySubDivisionCode =  getStateName($residence_address->state);
                    }
                    if(!empty($residence_address->country)) {
                      $Country = getCountryName($residence_address->country);
                    }
                    $theResourceObj = Customer::create([
                            "BillAddr" => [
                                "Line1" => $Line1,
                                "City" => $City,
                                "Country" => $Country,
                                "CountrySubDivisionCode" => $CountrySubDivisionCode,
                                "PostalCode" => $PostalCode
                            ],
                            "Notes" => ".",
                            "Title" => "Mr",
                            "GivenName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                            "MiddleName" => $saveddata['ClientDetails']['middle_name'],
                            "FamilyName" => $saveddata['ClientDetails']['last_name'],
                            "Suffix" => "Jr",
                            "FullyQualifiedName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                            "CompanyName" => $saveddata['ClientDetails']['first_name'],
                            "DisplayName" => $saveddata['ClientDetails']['first_name'].' '.$saveddata['ClientDetails']['middle_name'].' '.$saveddata['ClientDetails']['last_name'],
                            "PrimaryPhone" => [
                                "FreeFormNumber" => $saveddata['ClientDetails']['cell_phone']
                            ],
                            "PrimaryEmailAddr" => [
                                "Address" => $saveddata['ClientDetails']['email']
                            ]
                        ]);

                    $resultingObj = $dataService->Add($theResourceObj);

                    $CID = $resultingObj->Id;
                    Newclient::where('id', $client->id)->update(['QBCustomerID' => $CID]);
                    $QBCustomerID1 = $CID;
                }

                // update customer
                // $saveddata1 = array();
                
                $customer = $dataService->FindbyId('customer', $QBCustomerID1);
                $residence_address = $request->residence_address;
                $Line1 = '';
                $City = 'Mountain View';
                $CountrySubDivisionCode = 'CA';
                $Country = 'USA';
                $PostalCode = '94043';
                if(!empty($residence_address->address)) {
                  $Line1 = $residence_address->address;
                }
                if(!empty($residence_address->city)) {
                  $City =  getCityName($residence_address->city);
                }
                if(!empty($residence_address->state)) {
                  $CountrySubDivisionCode =  getStateName($residence_address->state);
                }
                if(!empty($residence_address->country)) {
                  $Country = getCountryName($residence_address->country);
                }
                /*
                $saveddata1 = [
                    "BillAddr" => [
                        "Line1" => $Line1,
                        "City" => $City,
                        "Country" => $Country,
                        "CountrySubDivisionCode" => $CountrySubDivisionCode,
                        "PostalCode" => $PostalCode
                    ],
                    "Notes" => ".",
                    "Title" => "Mr",
                    "GivenName" => $request->first_name.' '.$request->middle_name.' '.$request->last_name,
                    "MiddleName" => $request->middle_name,
                    "FamilyName" => $request->last_name,
                    "Suffix" => "Jr",
                    "FullyQualifiedName" => $request->first_name.' '.$request->middle_name.' '.$request->last_name,
                    "CompanyName" => $request->first_name,
                    "DisplayName" => $request->first_name.' '.$request->middle_name.' '.$request->last_name,
                    "PrimaryPhone" => [
                        "FreeFormNumber" => $request->cell_phone
                    ],
                    "PrimaryEmailAddr" => [
                        "Address" => $request->email
                    ]
                ];
                $theResourceObj = Customer::update($customer, $saveddata1);
                $resultingObj = $dataService->Update($theResourceObj);
                */
            }   
            catch (ServiceException  $e) {
                $invoice_id = 0;
            }
        }

        $update = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'cell_phone' => $request->cell_phone,
            'language' => $request->language,
            'type' => $request->type,
            'is_portal_access' => $request->is_portal_access,
            'is_detained' => $request->is_detained,
            'is_deported' => $request->is_deported,
            'is_outside_us' => $request->is_outside_us,
            'residence_address' => json_encode($request->residence_address),
            'mailing_address' => $request->mailing_address,
            'full_legal_name' => $request->full_legal_name,
            'dob' => $request->dob,
            'previous_name' => $request->previous_name,
            'alien_number' => $request->alien_number,
            'Social_security_number' => $request->Social_security_number,
            'birth_address' => json_encode($request->birth_address),
            'gender' => $request->gender,
            'eye_color' => $request->eye_color,
            'hair_color' => $request->hair_color,
            'height' => $request->height,
            'weight' => $request->weight,
            'race' => $request->race,
            'ethnicity' => $request->ethnicity,
            'religion' => $request->religion,
            'client_aliases' => json_encode($request->client_aliases)
        ];
        Newclient::where('user_id', $user_id)->update($update);
        $data = [
            'name' => $request->first_name . " " . $request->middle_name . " " . $request->last_name,
        ];

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' Firm admin update a Client ' . $request->first_name . ' ' . $request->last_name . ' Successfully!';

        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin update  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('firm/client/show') . '/' . $client->id;
        $message = collect(['title' => 'Firm Admin update  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */



        $user = User::where('id', $user_id)->update($data);

        $rurl = CalenderRedirect();

        if(!empty($rurl)) {
            return redirect($rurl)->with('success', 'Client update successfully!');
        }
        else {
            return redirect('firm/client')->with('success', 'Client update successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) {
        User::where('id', $id)->delete();
        return redirect('firm/client')->with('success', 'Firm client deleted successfully!');
    }

    public function client_files($id) {


        $data = Auth::User();
        $cases = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name')
                ->join('users', 'case.user_id', '=', 'users.id')
                ->join('users as ur', 'case.client_id', '=', 'ur.id')
                ->where('case.firm_id', $data->firm_id)
                ->get();

        $users = User::select('*')
                ->where('firm_id', $data->firm_id)
                ->where('role_id', '5')
                ->get();


        $client_name = Newclient::select('first_name', 'middle_name', 'last_name')->where('id', $id)->first();
        $client_files = ClientFiles::where('client_id', $id)->orderBy('id', 'desc')->get();

        return view('firmadmin.client.client_files', compact('id', 'client_files', 'cases', 'users', 'client_name'));
    }

    public function create_client_file(Request $request) {
        $validator = Validator::make($request->all(), [
                    'client_id' => 'required|string',
                    'petitioner' => 'required|string',
                    'beneficiary' => 'required|string',
                    'case_number' => 'required|string',
                    'case_type' => 'required|string',
                    'case_venue' => 'required|string',
                    'sponsor_type' => 'required|string',
                    'open_date' => 'required|string',
                    'staff_assigned' => 'required|string',
                    'attorney_of_record' => 'required|string',
                    'VA_Assigned' => 'required|string'
        ]);
        $res = array();
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = 'Mendatory fields are required!';
            echo json_encode($res);
            die();
        }
        ClientFiles::create($request->all());
        $res['status'] = true;
        $res['msg'] = 'Client file update successfully';
        echo json_encode($res);

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' Firm admin create a client file Successfully!';

        $touser = User::where('id', $request->client_id)->first();
        $message = collect(['title' => 'Firm Admin create a client file', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $message = collect(['title' => 'Firm Admin create a client file', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */
    }

    public function update_client_file(Request $request) {
        $validator = Validator::make($request->all(), [
                    'client_id' => 'required|string',
                    'petitioner' => 'required|string',
                    'beneficiary' => 'required|string',
                    'case_number' => 'required|string',
                    'case_type' => 'required|string',
                    'case_venue' => 'required|string',
                    'sponsor_type' => 'required|string',
                    'open_date' => 'required|string',
                    'staff_assigned' => 'required|string',
                    'attorney_of_record' => 'required|string',
                    'VA_Assigned' => 'required|string'
        ]);
        $res = array();
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = 'Mendatory fields are required!';
            echo json_encode($res);
            die();
        }
        $data = [
            'client_id' => $request->client_id,
            'petitioner' => $request->petitioner,
            'beneficiary' => $request->beneficiary,
            'case_number' => $request->case_number,
            'case_type' => $request->case_type,
            'case_venue' => $request->case_venue,
            'sponsor_type' => $request->sponsor_type,
            'open_date' => $request->open_date,
            'staff_assigned' => $request->staff_assigned,
            'attorney_of_record' => $request->attorney_of_record,
            'VA_Assigned' => $request->VA_Assigned
        ];
        ClientFiles::where('id', $request->fileid)->update($data);
        $res['status'] = true;
        $res['msg'] = 'Client file update successfully';
        echo json_encode($res);

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' Firm admin update a client file Successfully!';

        $touser = User::where('id', $request->client_id)->first();
        $message = collect(['title' => 'Firm Admin update a client file', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $message = collect(['title' => 'Firm Admin update a client file', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */
    }

    public function create_event(Request $request, $id) {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $provider = new Google([
            'clientId'     => CLIENT_ID,
            'clientSecret' => CLIENT_SECRET,
            'redirectUri'  => CLIENT_REDIRECT_URL,
            'accessType'   => 'offline',
            'hostedDomain' => "",
        ]);

        $authUrl = $provider->getAuthorizationUrl([
                        'scope' => [
                            'https://www.googleapis.com/auth/calendar'
                        ],
                        'prompt' => 'consent'
                    ]);
        $_SESSION['oauth2state'] = $provider->getState();

        $data = Auth::User();
        $user_id = $data->id;
        $users = User::select('users.*', 'roles.name as role_name')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('firm_id', $data->firm_id)
                // ->where('users.id', '!=', $data->id)
                ->whereIn('role_id', ['4', '5'])
                ->get();



        $arr1 = Event::select('who_consult_with')
                ->where('related_id', $id)
                ->where('title', "CLIENT")
                ->orderBy('id', 'DESC')
                ->first();


        if ($users) {
            foreach ($users as $k => $v) {
                if (isset($_REQUEST['reschedule'])) {
                    $wcw = json_decode($arr1->who_consult_with);
                    if (in_array($v->id, $wcw)) {
                        $users[$k]->userset = 1;
                    } else {
                        $users[$k]->userset = 0;
                    }
                } else {
                    $users[$k]->userset = 0;
                }
            }
        }


        $arr = Event::select('*')
                ->where('related_id', $id)
                ->where('title', "CLIENT")
                ->orderBy('id', 'DESC')
                ->get();

        $arr = Event::select('event.*', 'new_client.*', 'event.id as e_id')
                ->join('new_client','new_client.id', 'event.related_id')
                ->where('event.related_id',$id)
                ->where('event.title',"CLIENT")
                ->get();

        $events = array();
        $dateandtime = array('dates' => '', 'event_title' => '', 'coutner' => 0, 'event_type' => '');
        if ($arr) {
            foreach ($arr as $key => $e) {
                $select = 0;
                $wcw = json_decode($arr1->who_consult_with);
                if (isset($_REQUEST['reschedule']) && $e->related_id == $id) {
                    $edates = $e->s_date . ' ' . $e->s_time . ' - ' . $e->e_date . ' ' . $e->e_time;
                    $dateandtime = array('dates' => $edates, 'event_title' => $e->event_title, 'coutner' => $e->coutner, 'event_type' => $e->event_type);
                }
                $s_time  = date("H:i:s", strtotime($e->s_time));
                $e_time  = date("H:i:s", strtotime($e->e_time));
                $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->first_name.' '.$e->middle_name.' '.$e->last_name;
                $eedate = '';
                if(!empty($e->e_date)) {
                    $eedate = date('m/d/Y', strtotime($e->e_date));
                }
                $events[] = array(
                    'event_id' => $e->e_id,
                    'title' => $etitle, 
                    'act_title' => $e->event_title, 
                    'start' => $e->s_date . 'T' . $s_time,
                    'end' => $e->e_date.'T'.$e_time,
                    'event_end' => $eedate.' '.$e->e_time,
                    'event_type' => $e->event_type,
                    'description' => $e->event_description,
                    'who_consult_with' => json_decode($e->who_consult_with),
                );
            }
        }
        $events = json_encode($events);
        $access_token = get_user_meta($data->id, 'access_token');
        if(!empty($access_token)) {
            $elist = GetCalendarsList($access_token);
            if($elist == 0) {
                $refreshToken = get_user_meta($data->id, 'refresh_token');
                $grant = new RefreshToken();
                $access_token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
                update_user_meta($data->id, 'access_token', $access_token);
            }
        }
        CalenderRedirectSessionSave();
        $client = Newclient::where('id', $id)->first();
        return view('firmadmin.client.create_event', compact('id', 'users', 'events', 'access_token', 'dateandtime', 'user_id', 'client', 'authUrl'));
    }

    public function client_document($id) {
        $data = Auth::User();
        $clients = User::select('users.*', 'new_client.*')
                ->join('new_client', 'users.id', '=', 'new_client.user_id')
                ->get();
        $client_name = Newclient::select('first_name', 'middle_name', 'last_name')->where('id', $id)->first();
        return view('firmadmin.client.client_document', compact('id', 'clients', 'client_name'));
    }

    public function getClienDocument($id) {

        $users = ClientDocument::select('client_document.*', 'new_client.*', 'client_document.id as did')
                ->join('new_client', 'new_client.id', 'client_document.client_id')
                ->where('client_document.client_id', $id)
                ->get();
        foreach ($users as $key => $user) {
            $users[$key]->name = $user->first_name . ' ' . $user->last_name;
            $users[$key]->uploaded_name = getUserName($user->uploaded_by)->name;
        }
        return datatables()->of($users)->toJson();
    }

    public function setClientDocument(Request $request) {
        $data = Auth::User();
        // pre($request->all());
        // die();
        foreach ($request->file as $key => $file) {
            $f = Storage::put('client_doc', $file);
            $data = [
                'client_id' => $request->client_id,
                'uploaded_by' => $data->id,
                'document' => $f,
                'title' => $request->title,
                'description' => $request->description
            ];
            ClientDocument::create($data);
        }

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' Firm admin upload Document Successfully!';

        $client_name = Newclient::select('*')->where('id', $request->client_id)->first();
        $case = FirmCase::select('*')->where('client_id', $client_name->user_id)->orderBy('id', 'DESC')->first();
        $touser = User::where('id', $client_name->user_id)->first();
        if(!empty($case)) {
            $n_link = url('firm/firmclient/document_requests').'/'.$case->id;
        }
        else {
            $n_link = url('firm/clientdashboard');
        }
        $message = collect(['title' => 'Firm Admin upload Document', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if(!empty($touser)) {
            Notification::send($touser, new DatabaseNotification($message));    
        }
        

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('firm/client/document').'/'.$request->client_id;
        $message = collect(['title' => 'Firm Admin upload Document', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        return redirect('firm/client/document/' . $request->client_id)->with('success', 'Client document upload successfully!');
    }

    
    public function cancel_client_invoice($id, $cid) {
        QBInvoice::where('id', $id)->update(['status' => 3]);
        return redirect('firm/client/client_invoice/'.$cid)->with('success','Invoice cancel successfully');
    }
    
    public function edit_family($id, $fid) {
        $client = Newclient::select('*')->where('id', $id)->first();
        $countries = DB::table("countries")->get();
        $FamilyMember = get_user_meta($fid, 'FamilyMember');
        $FamilyMember = json_decode($FamilyMember);
        $r_states = array();
        if (!empty($FamilyMember->residence_address->country)) {
            $r_states = DB::table("regions")
                    ->where("country_id", $FamilyMember->residence_address->country)
                    ->pluck("name", "id");
        }
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        return view('firmadmin.client.edit_family', compact('id', 'fid', 'client', 'countries', 'FamilyMember', 'r_states', 'firm'));                                                                          
    }

    public function delete_family($id, $fid) {
        $f_user = User::where('id', $fid)->first();
        UserMeta::where('user_id', $fid)->delete();
        User::where('id', $fid)->delete();
        ClientFamily::where('email', $f_user->email)->delete();
        return redirect('firm/client/view_family/'.$id)->with('success','Member delete successfully');
    }

    public function payForClientInvoice(Request $request) {
        
        // pre($request->all());
        // die();
        $currunt_user = Auth::User();
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $currunt_user->firm_id)
                ->first();
        $tx_id = 0;
        $t_amount = 0;
        $array = array();
        $invoice = QBInvoice::select('*')
                ->where('id',$request->id)
                ->first();
        if($invoice->destination_account == 'Trust Account') {
            $account_id = get_user_meta($firm->uid, 'account_id');
        }
        else {
            $account_id = get_user_meta($firm->uid, 'trust_account_id');
        }
        
        $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
        if(empty($account_id) || empty($SECRET_KEY)) {
            $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
            $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
        }
        if(!empty($request->payment_method) && $request->payment_method == 'Credit Card') {
            
            $exp_date = explode('/', $_REQUEST['exp_date']);

            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, 'https://api.chargeio.com/v1/cards');
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch1, CURLOPT_POST, 1);
            $array2= array(
                    "account_id"=>"$account_id",
                    "number"=>$_REQUEST['card_number'],
                    "exp_month"=>$exp_date[0], 
                    "exp_year"=> $exp_date[1],
                    // "cvv"=>$_REQUEST['cvc'],
                    // "zipcode"=>$_REQUEST['address_zip'],
                    "name"=>$request->name_of_credit_card, 
                    "description" => "Corporate VISA",
                    "reference" => $request->ctype.$request->lead_id 
                );
            curl_setopt($ch1, CURLOPT_POSTFIELDS, (json_encode($array2))) ;
            curl_setopt($ch1, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch1); 
            
            $ch = curl_init();
            curl_setopt($ch,  CURLOPT_URL, 'https://api.chargeio.com/v1/charges');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $amt = $request->amount*100;
            $t_amount = $request->amount*100;
            
            $array1= array(
                    "amount"=>$amt,
                    "account_id"=>"$account_id",
                    "method" => array(
                        "type"=>"card",
                        "number"=>$_REQUEST['card_number'],
                        "exp_month"=>$exp_date[0], 
                        "exp_year"=> $exp_date[1],
                        "cvv"=>$_REQUEST['cvc'],
                        "name"=>$invoice->client_name, 
                        "address1" => "97 Hammond St",
                        "city" => "Bangor",
                        "state" => "ME",
                        "postal_code" => "04401",
                        "reference" => $request->ctype.$request->lead_id
                  ) 
                );
            curl_setopt($ch, CURLOPT_POSTFIELDS, (json_encode($array1))) ;
            curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

            // $headers = array();
            // $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch); 
            $array = json_decode($result,true);
            curl_close($ch);
            // pre($array);
            // die();
            $arr = $request->all();
            if(array_key_exists('messages', $array)) {
                return redirect()->back()->with(['data' => $arr])->withErrors($array['messages'][0]['message']);
            }
            else {
                $tx_id = $array["id"];
                $amt1 = $request->amount;
                if(!empty($invoice->paid_amount)) {
                    $amt1 = $request->amount+$invoice->paid_amount;
                }
                QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
            }
        }
        else if(!empty($request->payment_method) && $request->payment_method == 'Cash') {
            $t_amount = $request->cash_amount*100;
            $amt1 = $request->cash_amount;
            if(!empty($invoice->paid_amount)) {
                $amt1 = $request->cash_amount+$invoice->paid_amount;
            }
            QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
        }
        else if(!empty($request->payment_method) && $request->payment_method == 'E-Check') {

            $account_id = get_user_meta($firm->uid, 'echeck');

            $t_amount = $request->check_amount*100;
            $amt1 = $request->check_amount;
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $ch = curl_init();

            // curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/merchant');
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_HTTPGET, 1);
            // curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            // $result = curl_exec($ch); 
            // $account = json_decode($result,true);

            // pre($account);
            // die();

            curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/charges');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            
            $array1= array(
                    "amount"=>$t_amount,
                    "account_id"=>"x831iQGnR76c6bvGRYSIIg",
                    "method" => array(
                        "type"=>"bank",
                        "routing_number"=> $request->routing_number,
                        "account_number"=> $request->account_number, 
                        "account_type"=> "CHECKING",
                        "name"=>$invoice->client_name, 
                        "account_holder_type" => "business"
                    ) 
                );
            curl_setopt($ch, CURLOPT_POSTFIELDS, (json_encode($array1))) ;
            curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch); 
            $array = json_decode($result,true);
            curl_close($ch);

            $arr = $request->all();
            if(array_key_exists('messages', $array)) {
                return redirect()->back()->with(['data' => $arr])->withErrors($array['messages'][0]['message']);
            }
            // pre($array);
            // die();

            if(!empty($invoice->paid_amount)) {
                $amt1 = $request->check_amount+$invoice->paid_amount;
            }
            QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
        }
        else {
            $t_amount = $request->moneyorder_amount*100;
            $amt1 = $request->moneyorder_amount;
            if(!empty($invoice->paid_amount)) {
                $amt1 = $request->moneyorder_amount+$invoice->paid_amount;
            }
            QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
        }
        
        QBInvoice::where('id',$request->id)->update(['status'=> 1, 'payment_method' => $request->payment_method, 'paid_date' => date('m/d/Y')]);

        $currunt_user = Auth::User();    
        $data['tx_id'] = $tx_id;
        $data['amount'] = $t_amount;
        $data['user_id'] = $currunt_user->id;
        $data['responce'] = json_encode($array);
        $data['paymenttype'] = 4;
        $data['type'] = 'Invoice';
        $data['related_id'] = $request->id;
        Transaction::create($data);
        
        $isschedule = SchedulePayment::select('*')->where('invoice_id', $request->id)->first();
        if(($invoice->amount*100) > $t_amount) {
            if($request->ctype == 'Lead') {
                return redirect($request->redirect_url)->with('success','Paid successfully');
            }
            else {
                if(!empty($request->isscheduleb) && !empty($isschedule)) {
                    return redirect($request->redirect_url)->with('success','Paid successfully');
                }
                else {
                    return redirect($request->redirect_url1.'/'.$request->id)->with('success','Paid successfully');
                }
            }
        }
        else {
            return redirect($request->redirect_url)->with('success','Paid successfully');
        }
    }    

    public function ClientSchedulePayment(Request $request) {
        $data = Auth::User();
        if(empty($request->is_schedule)) {
            if(!empty($request->save_view)) {
                return redirect('firm/client/view_client_invoice/'.$request->id);
            }
            else {
                return redirect('firm/client/client_invoice/'.$request->client_id);
            }
            
        }
        else if(!empty($request->save_close) || !empty($request->save_view)) {
            if($request->credit_card == 'New Credit Card') {
                $firm = Firm::select('firms.*', 'users.id as uid')
                        ->join('users', 'firms.email', '=', 'users.email')
                        ->where('firms.id', $data->firm_id)
                        ->first();

                $account_id = get_user_meta($firm->uid, 'account_id');
                $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
                if(empty($account_id) || empty($SECRET_KEY)) {
                    $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
                    $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
                }
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                $exp_date = explode('/', $_REQUEST['exp_date']);
                $array1= array(
                        "account_id"=>"$account_id",
                        "number"=>$_REQUEST['card_number'],
                        "exp_month"=>$exp_date[0], 
                        "exp_year"=> $exp_date[1],
                        // "cvv"=>$_REQUEST['cvc'],
                        // "zipcode"=>$_REQUEST['address_zip'],
                        "name"=>$request->name_of_credit_card, 
                        "description" => "Corporate VISA",
                        "reference" => "Client".$request->client_id 
                    );
                curl_setopt($ch, CURLOPT_POSTFIELDS, (json_encode($array1))) ;
                curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch); 
                $card = json_decode($result,true);
                // pre($card);
                // die();
                $arr = $request->all();
                if(array_key_exists('messages', $card)) {
                    return redirect()->back()->with(['data' => $arr])->withErrors($card['messages'][0]['message']);
                }
                $credit_card = $card['id'];
            }
            else {
                $credit_card = $request->credit_card;
            }

            $payment_cycle = '';
            if($request->payment_cycle == 'Weekly') {
                $payment_cycle = '+1 week';
            }
            else if($request->payment_cycle == 'Monthly') {
                $payment_cycle = '+1 month';
            }
            else if($request->payment_cycle == 'Quarterly') {
                $payment_cycle = '+3 month';
            }
            $sdate = date('m/d/Y', strtotime($payment_cycle, strtotime($request->first_payment)));
            $idata = [
                'recurring_amount' => $request->recurring_amount,
                'first_payment' => $request->first_payment,
                'next_payment' => $sdate,
                'payment_cycle' => $request->payment_cycle,
                'credit_card' => $credit_card,
                'invoice_id' => $request->id,
                'schedule_for' => 'CLIENT',
                'related_id' => $request->client_id,
            ];
            $qbinvoice = SchedulePayment::create($idata);

            if(!empty($request->save_view)) {
                return redirect('firm/client/view_client_invoice/'.$request->id)->with('success', 'Schedule payment successfully!');
            }
            else {
                return redirect('firm/client/client_invoice/'.$request->client_id)->with('success', 'Schedule payment successfully!');
            }
            // return redirect('firm/client/client_invoice/'.$request->client_id)->with('success', 'Schedule payment successfully!');
        }
        else {
            return redirect('firm/client/client_invoice/'.$request->client_id);
        }
        
    }

    public function GetClientSchedulePayment($id, $id1) {
        $data = Auth::User();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.*')
                    ->where('qb_invoice.invoice_for', 'CLIENT')
                    ->where('qb_invoice.client_id', $id)
                    ->where('qb_invoice.id', $id1)
                    ->where('qb_invoice.status', '!=', 3)
                    ->join('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->first();
        $transaction = Transaction::select('*')->where('type', 'Invoice')->where('related_id', $id1)->get();
        if(!empty($invoice)) { 
            $credit_card = $invoice->credit_card;
            $cards = array();
            if(!empty($credit_card)) {
                $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $data->firm_id)
                ->first();

                $account_id = get_user_meta($firm->uid, 'account_id');
                $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
                if(empty($account_id) || empty($SECRET_KEY)) {
                    $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
                    $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
                }
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards/'.$credit_card);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch); 
                $cards = json_decode($result,true);
            }
            // if(!empty($cards)) {
                    
            //     }
            // pre($cards);
            ?>
            <br>
            <h4>Schedule Payment Information</h4>
            <?php 
            if(!empty($cards)) { ?>
                <div class="card_detail_w">
                    <div class="row form-group">
                      <div class="col-sm-6 col-md-6">
                        <div class="row">
                          <label class="col-form-label col-md-5 col-sm-5">Name of Credit Card <span style="color: red"> *</span></label>
                          <div class="col-sm-7 col-md-7">
                            <input placeholder="Name of Credit Card" name="name_of_card" class="form-control" value="<?php echo $cards['name']; ?>" readonly/>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 col-md-6">
                          <a href="#" class="action_btn card_edit_btn customedit_btn" title="Edit Lead" data-toggle="tooltip" style="position: static; padding-top: 1px;" data-id="{{$lead->id}}"><img src="<?php echo url('assets/images/icon'); ?>/pencil(1)@2x.png" style="width: 13px;" /></a>
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-sm-6 col-md-6">
                        <div class="row">
                          <label class="col-form-label col-md-5 col-sm-5">Credit Card Number <span style="color: red"> *</span></label>
                          <div class="col-sm-7 col-md-7">
                            <input type="text" placeholder="Credit Card Number" size="20" name="new_card_number" class="form-control" value="<?php echo $cards['number']; ?>" data-stripe="number" maxlength="16" readonly/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-sm-6 col-md-6">
                        <div class="row">
                          <label class="col-form-label col-md-5 col-sm-5">Expiration Date <span style="color: red"> *</span></label>
                          <div class="col-sm-7 col-md-7">
                            <input type="text" placeholder="mm/yyyy" name="new_exp_date" data-stripe="exp_month" class="form-control new_exp_date" value="<?php if($cards['exp_month'] < 10) { echo '0'; } echo $cards['exp_month']; ?>/<?php echo $cards['exp_year']; ?>" readonly/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row form-group">
                      <div class="col-sm-6 col-md-6">
                        <div class="row">
                          <label class="col-form-label col-md-5 col-sm-5"></label>
                          <div class="col-sm-7 col-md-7">
                            <input type="hidden" name="invoice_id1" value="<?php echo $invoice->invoice_id; ?>">
                            <input type="hidden" name="related_id1" value="<?php echo $invoice->related_id; ?>">
                            <button type="button" name="update_card" class="update_card btn btn-primary" style="display: none;">
                                Update and Save
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            <?php } ?>
            <br>
            <br>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Invoice Number</label>
                  <div class="col-sm-7 col-md-7">
                    <?php echo $invoice->invoice_id; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Initial Invoice Amount</label>
                  <div class="col-sm-7 col-md-7">
                    $<?php echo number_format($invoice->amount, 2); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Recurring Payment Amount</label>
                  <div class="col-sm-7 col-md-7">
                    $<?php echo number_format($invoice->recurring_amount, 2); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Frequency of Payment</label>
                  <div class="col-sm-7 col-md-7">
                    <?php echo $invoice->payment_cycle; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">First Payment Date</label>
                  <div class="col-sm-7 col-md-7">
                    <?php echo $invoice->first_payment; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Next Payment Date</label>
                  <div class="col-sm-7 col-md-7">
                    <span class="nextpayment_s"><?php 
                        echo $invoice->next_payment; 
                    ?> </span>
                    <a href="#" class="skip_payment" data-id="<?php echo $invoice->invoice_id; ?>" data-sdate="<?php echo $invoice->next_payment; ?>">Skip Payment</a>
                  </div>
                </div>
              </div>
            </div>
        <?php }
        if(!empty($transaction)) { ?>
            <br>
            <h4>Payment History</h4>
            <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                        <tr>
                            <th>Payment Number</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                            <th>Balance</th>
                        </tr>
                     </thead>
                     <tbody>
                      <?php 
                      if(!empty($transaction)) {
                        foreach ($transaction as $k => $v) { ?>
                            <tr>
                                <td>#<?php echo $k+1; ?></td>
                                <td>$<?php echo number_format($v->amount/100, 2); ?></td>
                                <td>
                                    <?php 
                                    if($v->amount) {
                                        echo 'Paid';
                                    }
                                    else {
                                        echo 'Skipped';
                                    }
                                    ?>
                                </td> 
                                <td><?php echo date('m/d/Y', strtotime($v->created_at)); ?></td> 
                                <td>
                                  $<?php echo number_format($invoice->amount, 2); ?>
                                </td> 
                            </tr>
                        <?php
                        }
                      }
                      ?>                      
                     </tbody>
                   </table>
                </div>
              </div>
        <?php }
    }

    public function SkipPayment(Request $request,$id) {
        $currunt_user = Auth::User();   
        $array = array(); 
        $data['tx_id'] = 0;
        $data['amount'] = 0;
        $data['user_id'] = $currunt_user->id;
        $data['responce'] = json_encode($array);
        $data['paymenttype'] = 4;
        $data['type'] = 'Invoice';
        $data['related_id'] = $id;
        $data['created_at'] = date('Y-m-d h:i:s', strtotime($request->sdate));
        $t = Transaction::create($data);
        

        $schedule_invoice = SchedulePayment::select('*')->where('invoice_id', $id)->first();
        $payment_cycle = '';
        if($schedule_invoice->payment_cycle == 'Weekly') {
            $payment_cycle = '+1 week';
        }
        else if($schedule_invoice->payment_cycle == 'Monthly') {
            $payment_cycle = '+1 month';
        }
        else if($schedule_invoice->payment_cycle == 'Quarterly') {
            $payment_cycle = '+3 month';
        }
        $sdate = date('m/d/Y', strtotime($payment_cycle, strtotime($schedule_invoice->next_payment)));

        Transaction::where('id', $t->id)->update(['created_at' => date('Y-m-d h:i:s', strtotime($schedule_invoice->next_payment))]);

        $idata = [
            'next_payment' => $sdate
        ];
        $qbinvoice = SchedulePayment::where('invoice_id', $id)->update($idata);
        $arr = array('next_payment' => $sdate);
        echo json_encode($arr);
    }

    public function UpdateScheduleCard(Request $request) {
        // pre($request->all());
        // die();
        $data = Auth::User(); 
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $data->firm_id)
                ->first();

        $account_id = get_user_meta($firm->uid, 'account_id');
        $SECRET_KEY = get_user_meta($firm->uid, 'SECRET_KEY');
        if(empty($account_id) || empty($SECRET_KEY)) {
            $account_id = 'dtxR7ZVPTjqs9-Ah_YvYZQ';
            $SECRET_KEY = 'AP9bkZggRLK1FJH6YbGufA3jm9gdkX8gtVqytxnryv9XIwKsWdCLv0ZhRhke4v5w';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $exp_date = explode('/', $_REQUEST['new_exp_date']);
        $array1= array(
                "account_id"=>"$account_id",
                "number"=>$_REQUEST['new_card_number'],
                "exp_month"=>$exp_date[0], 
                "exp_year"=> $exp_date[1],
                "name"=>$request->name_of_card, 
                "description" => "Corporate VISA",
                "reference" => "Client".$request->client_id 
            );
        curl_setopt($ch, CURLOPT_POSTFIELDS, (json_encode($array1))) ;
        curl_setopt($ch, CURLOPT_USERPWD, "$SECRET_KEY" . ':' . '');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch); 
        $card = json_decode($result,true);
        $credit_card = $card['id'];
        $idata = [
            'credit_card' => $credit_card
        ];
        $qbinvoice = SchedulePayment::where('invoice_id', $request->invoice_id)->update($idata);
    }

    public function delete_doc($id, $cid) {
        ClientDocument::where('id', $id)->delete();
        return redirect('firm/client/document/'.$cid)->with('success','Document delete successfully');
    }

    public function portal_access(Request $request) {
        $res = array();
        $res['status'] = false;
        $res['msg'] = 'Client Portal Access disable successfully';
        $client = User::where('id', $request->cid)->first();
        if($request->access) {
            $pass = str_random(8);
            $data = array(
                    'first_login' => 0,
                    'status' => 1,
                    'password' => Hash::make($pass)
                 );
            User::where('id', $client->id)->update($data);
            /* --------------------Notifications--------------- */
            $firm_id = Auth::User()->firm_id;
            $firm_name = Firm::select('firm_name')->where('id', Auth::User()->firm_id)->first();

            $msg = 'Firm ' . $firm_name->firm_name . ' created your account successfully!';
            $n_link = url('profile');
            $message = collect(['title' => 'Firm Admin Created your account', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);

            Notification::send($client, new DatabaseNotification($message));
            /* --------------------Notifications--------------- */

            $message = FirmSetting::where('title', 'Welcome Client')->where('category', 'EMAIL')->where('firm_id', Auth::User()->firm_id)->first();
            $username = $client->name;
            $useremail = $client->email;
            $pass = $pass;
            $msg1 = "Hi, $username.<br>";
            $msg1 .= "Email : " . $useremail . " <br>";
            $msg1 .= "Password : " . $pass . " <br><br>";
            $msg1 .= $message->message . "<br>";
            $v = url('firm/clientdashboard');
            $msg1 = str_replace('{client_portal_link}', ' '.$v.' ', $msg1);
            $msg = EmptyEmailTemplate($msg1);
            $args = array(
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Welcome to TILA Case Prep',
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);

            $res['status'] = true;
            $res['msg'] = 'Client Portal invitation sent!';
        }
        else {
            $pass = str_random(8);
            $data = array(
                    'first_login' => 0,
                    'status' => 0,
                    'password' => Hash::make($pass)
                );
            User::where('id', $client->id)->update($data);
        }
        Newclient::where('user_id', $request->cid)->update(['is_portal_access' => $request->access]);
        echo json_encode($res);
    }
}
