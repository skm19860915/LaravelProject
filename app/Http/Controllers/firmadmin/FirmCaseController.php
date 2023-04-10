<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\FirmCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\FirmSetting;
use App\Models\Newclient;
use App\Models\AdminTask;
use App\Models\Event;
use App\Models\CaseType;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\ClientTask;
use App\Models\ClientNotes;
use App\Notifications\DatabaseNotification;
use App\Models\DocumentRequest;
use App\Models\ClientDocument;
use App\Models\ClientInformation;
use App\Models\FamilyInformation;
use App\Models\ClientFamily;
use App\Models\TextMessage;
use App\Models\AffidavitDocumentRequest;
use App\Models\Questionnaire;
use Notification;
use App;
use DB;
use App\Dropbox;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class FirmCaseController extends Controller {
    private $api_client;
    private $content_client;
    private $access_token;

    public function __construct(Dropbox $dropbox) {
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
            exit();
        }

        $user = User::select('id', 'name')
                ->where('firm_id', $data->firm_id)
                ->where('role_id', 5)
                ->get();

        $firm = Firm::select('*')->where('id', $data->firm_id)->first();
        return view('firmadmin.case.index', compact('firm', 'data', 'user'));
    }

    public function mycase() {
        $data = Auth::User();
        $firm = Firm::select('*')->where('id', $data->firm_id)->first();
        return view('firmadmin.case.index', compact('firm', 'data'));
    }

    public function allcase(Request $request) {
        $data = Auth::User();

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
            exit();
        }
        $user = User::select('id', 'name')
                ->where('firm_id', $data->firm_id)
                ->where('role_id', 5)
                ->get();

        $firm = Firm::select('*')->where('id', $data->firm_id)->first();
        return view('firmadmin.case.index', compact('firm', 'data', 'user'));
    }

    public function getData() {
        $data = Auth::User();
        $firm = Firm::select('*')->where('id', $data->firm_id)->first();
        $s = $_GET['start'];
        $l = $_GET['length'];
        if($firm->account_type == 'CMS') {
            if (isset($_GET['case']) && $_GET['case'] == 'firm.case.allcase') {
                $filter1 = $_GET['filter1'];
                $filter2 = $_GET['filter2'];
                
                if($filter1 == 'VP') {
                    $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'urp.name as paralegal_name','new_client.id as clientid', 'admintask.allot_user_id as vp_id')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                        ->where('case.firm_id', $data->firm_id)
                        ->where('case.VP_Assistance', 1)
                        ->leftJoin('admintask', 'admintask.case_id', '=', 'case.id')
                        ->where('admintask.task_type', 'Assign_Case')
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
                }
                else if($filter1 == 'Attorney' && !empty($filter2)) {
                    $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'urp.name as paralegal_name','new_client.id as clientid')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                        ->where('case.firm_id', $data->firm_id)
                        ->where('case.user_id', $filter2)
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
                }
                else if($filter1 == 'Paralegal' && !empty($filter2)) {
                    $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'urp.name as paralegal_name','new_client.id as clientid')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                        ->where('case.firm_id', $data->firm_id)
                        ->where('case.assign_paralegal', $filter2)
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
                }
                else {
                    $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'urp.name as paralegal_name','new_client.id as clientid')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                        ->where('case.firm_id', $data->firm_id)
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
                }
            } else {
                $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'new_client.id as clientid', 'urp.name as paralegal_name')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                        ->where('case.firm_id', $data->firm_id)
                        ->where('case.user_id', $data->id)
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
            }
        }
        else {
            $st = array(1,2,3,4,5,6,7,8,9);
            if($_GET['status'] == 'Open') {
                $st = array(1,2);
            }
            else if($_GET['status'] == 'Working') {
                $st = array(3,4,5);
            }
            else if($_GET['status'] == 'InReview') {
                $st = array(6);
            }
            else if($_GET['status'] == 'Complete') {
                $st = array(9);
            }
            else if($_GET['status'] == 'InComplete') {
                $st = array(8);
            }
            $case = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name','new_client.id as clientid')
                        ->join('users', 'case.user_id', '=', 'users.id')
                        ->join('users as ur', 'case.client_id', '=', 'ur.id')
                        ->leftJoin('new_client', 'new_client.user_id', '=', 'case.client_id')
                        ->where('case.firm_id', $data->firm_id)
                        ->whereIn('case.status',$st)
                        ->orderBy('case.id', 'DESC')
                        // ->skip($s)->take($l)
                        ->get();
        }
        // $case = (array)$case;
        // $case = array_slice($case, $s, $l);
        // $case = (object)$case;
        foreach ($case as $key => $value) {

            $event = Event::select('event.*')
                    ->where('related_id', $value->id)
                    ->where('title', "CASE")
                    ->orderBy('id', 'DESC')
                    ->first();

            if (!empty($event)) {
                $case[$key]->event = Carbon::parse($event->e_date)->format('m-d-Y') . " " . Carbon::parse($event->e_time)->format('g:i a');
                $case[$key]->oldevent = strtotime($event->e_date);
            } else {
                $case[$key]->event = "";
            }
            if($value->CourtDates == "0") {
                $case[$key]->CourtDates = 'Not set';
            }
            if(empty($value->VP_Assistance)) {
                $case[$key]->case_cost = 'Self managed';
            }
            $case[$key]->case_status = GetCaseStatus($value->status);
            $case[$key]->todaytime = strtotime('now');
            $case[$key]->created = Carbon::parse($value->created_at)->format('m-d-Y g:i a');

            $case[$key]->vpuser = 'N/A';
            $case[$key]->vpid = 0;
            if(!empty($value->VP_Assistance)) {
                $record = AdminTask::select('admintask.allot_user_id', 'users.name as vpname')
                        ->where('admintask.case_id', $value->id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->leftJoin('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
                if(!empty($record) && !empty($record->allot_user_id)){
                    $case[$key]->vpuser = $record->vpname;
                    $case[$key]->vpid = $record->allot_user_id;
                }
            }
            $case[$key]->clink = '#';
            if(!empty($value->client_name)) {
                $case[$key]->clink = url('firm/client/show/'.$value->clientid);
            }
        }
        return datatables()->of($case)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currunt_user = Auth::User();
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
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
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
        $client = User::select('id', 'name')
                ->where('firm_id', $data->firm_id)
                ->where('role_id', 6)
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

        return view('firmadmin.case.create', compact('user', 'client', 'case_type', 'firm', 'card', 'currunt_user', 'I_864_Cost', 'I_864A_Cost', 'I_DS260_Cost', 'I_Affidavit_Cost'));
    }

    public function create_case(Request $request) {

        // $case_type = CaseType::select('Required_Forms')
        //             ->where('Case_Category', $request->case_category)
        //             ->where('Case_Type', $request->casetype)
        //             ->first();
        // $Required_Forms = json_decode($case_type->Required_Forms); 
        $firm_id = Auth::User()->firm_id;
        $client = DB::table('new_client')->where('user_id', $request->firmclient)->first();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        $card_source = '';
        extract($_REQUEST);
        $currunt_user = Auth::User();  
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
        $validator = Validator::make($request->all(), [
                'case_category' => 'required',
                'casetype' => 'required',
                'firmuser' => 'required',
                'firmclient' => 'required'
        ]);
        if ($validator->fails()) {
            $user = User::select('id', 'name')
                ->where('firm_id', $currunt_user->firm_id)
                ->where('role_id', 5)
                ->count();
            if(!$user) {
                return redirect('firm/case/create')->withInfo('Please add user/client first');
            }
            else {
                return redirect('firm/case/create')->withInfo('Mendatory fields are required!');
            }
        }

        
        
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
                         'firm_id' => $case_id->firm_id,
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
                         'firm_id' => $case_id->firm_id,
                         'file' => $file1,
                         'file_type' => $file_dataname   
                    ];
                    ClientInformation::create($data);
                }
            }
        }

        if (!empty($request->case_file)) {
            foreach ($request->case_file as $key => $v) {
                // $case_file = Storage::put('case_doc', $file);
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

        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a case id  " . $case_id->id
        ];
        Log::create($logdata);

        $caseType = $request->casetype;
        
        if($currunt_user->role_id == 4) {
            if ($request->VP_Assistance) {

                $data2['case_id'] = $case_id->id;
                $data2['task_type'] = 'Assign_Case';
                $data2['task'] = 'Assign Case to VP';
                $data2['firm_admin_id'] = $currunt_user->id;
                $data2['status'] = 0;
                $atask = AdminTask::create($data2);
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
                        else {
                            //$card_source = $stripeToken;
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
                    else {
                        //$card_source = $stripeToken;
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
                $data = array();
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
            /* --------------------Notifications--------------- */
        }
        else {

            if ($request->VP_Assistance) {
                /* --------------------Notifications--------------- */
                $msg = 'Case created successfully! A TILA VP will be assigned to this case shortly.';
                $touser = User::where('email', $firm_name->email)->first();
                $n_link = url('firm/case/show').'/'.$case_id->id;
                $message = collect(['title' => 'New Case Approvel', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                Notification::send($touser, new DatabaseNotification($message));
                /* --------------------Notifications--------------- */

                FirmCase::where('id', $case_id->id)->update(['status' => -1]);

                $username = Auth::User()->name;
                $firm_admin_name =  $firm_name->firm_admin_name;
                $msg1 = "Hi $firm_admin_name,<br>";
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
                //if($firm_name->account_type == 'CMS') {
                //Notification::send($touser, new DatabaseNotification($message));

                $msg = 'You have been assigned a case #'.$case_id->id;
                $n_link = url('firm/case/show').'/'.$case_id->id;
                $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                $caseuser = User::where('id', $request->firmuser)->first();
                Notification::send($caseuser, new DatabaseNotification($message));
                //}
            }
        }

        if($request->VP_Assistance) {
            app('App\Http\Controllers\HomeController')->DEVQBInvoiceCreation($case_id);
        }

        if ($case_id->id) { 
            return redirect('firm/case')->with('success', 'Case created successfully! A TILA VP will be assigned to this case shortly.');
        } else {
            return redirect('firm/case')->with('error', 'Case not created, please try again');
        }
    }

    public function show($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();

        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        $data['totla_tasks'] = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();;
        $data['totla_documents'] = DocumentRequest::select('*')->where('case_id', $case->id)->count();
        $data['totla_notes'] = ClientNotes::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();
        $data['totla_forms'] = ClientInformation::select('*')->where('case_id', $case->id)->count();
        $task = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->get();
        // return view('firmadmin.case.show', compact('casedata', 'id', 'case', 'admintask', 'data','firm', 'task', 'client'));
        return view('firmadmin.case.show', compact( 'id', 'case', 'admintask', 'data','firm', 'task', 'client'));
    }

    public function profile($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        
        $client = array();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();

        $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->where('client_family.type', 'Beneficiary')
        ->where('client_family.client_id', $client->id)
        ->where('users.role_id' ,'=', '7')
        ->first();
        
        $ques = Questionnaire::select('*')
                ->where('client_id', $case->client_id)
                ->get();
        // pre($ques);
        return view('firmadmin.case.profile', compact('id', 'case', 'admintask','firm', 'client', 'ques', 'family_alllist', 'beneficiary_list'));
    }

    public function case_tasks($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $task = ClientTask::select('client_task.*', 'users.name')
                ->where('client_task.related_id', $case->id)
                ->where('client_task.task_for', 'CASE')
                ->leftJoin('users', 'users.id', '=', 'client_task.created_by')
                ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.case_tasks', compact('case' , 'task', 'firm', 'admintask', 'client'));
    }

    public function add_case_tasks($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.add_case_tasks', compact('case', 'firm', 'admintask', 'client'));
    }

    public function insert_new_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('firm/case/add_case_tasks/'.$request->id)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'task_for' => 'CASE',
            'related_id' => $request->case_id,
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
        return redirect('firm/case/case_tasks/'.$request->id)->withInfo('Task created successfully');
    }

    public function edit_case_tasks($id, $tid)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $task = ClientTask::select('*')
                ->where('id', $tid)
                ->first();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.edit_case_tasks', compact('case' , 'task', 'firm', 'admintask', 'client'));
    }

    public function update_case_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('firm/case/add_case_tasks/'.$request->id)->withInfo('Mendatory fields are required!');
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
        return redirect('firm/case/case_tasks/'.$request->case_id)->withInfo('Task created successfully');
    }

    public function case_documents($id)
    {
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        // \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        \Stripe\Stripe::setApiKey('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');

        $data = Auth::User();
        $searchResults = \Stripe\Customer::all([
            "email" => $data->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cust = '';
        $card = '';
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)->get();
        $clientrr = array();
        if($case->client_id) {
            $clientrr = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($clientrr)) {
                $client_doc = ClientDocument::select('*')->where('client_id', $clientrr->id)->get();
            }
        }
        $client_doc = ClientDocument::select('*')->where('case_id', $id)->get();

        $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $existing_members = User::select('users.*')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.case_documents', compact('case', 'requested_doc','firm', 'card', 'client_doc', 'family_alllist', 'clientrr', 'CaseTypes', 'admintask', 'existing_members', 'client'));
    }

    public function upload_documents($id, $fid)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)->get();
        $docs = DocumentRequest::select('document_type')
                ->where('case_id', $id)
                ->where('family_id', $fid)
                ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.upload_documents', compact('id', 'case', 'requested_doc','firm', 'docs', 'fid', 'admintask', 'client'));
    }

    public function upload_family_documents($id, $fid)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $requested_doc = DocumentRequest::select('*')
                        ->where('case_id', $case->id)
                        ->where('family_id', $fid)
                        ->get();
        $docs = DocumentRequest::select('document_type')->where('case_id', $id)->where('family_id', $fid)->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.upload_family_documents', compact('id', 'fid', 'case', 'requested_doc','firm', 'docs', 'admintask', 'client'));
    }

    public function setDataDocument4(Request $request) {
        if(!empty($request->file))
        {
            foreach ($request->file as $k1 => $file) {
                $client_file = array();
                $client_file[] = Storage::put('client_doc', $file);
                foreach ($request->filetype as $k2 => $fname) {
                    if($client_file){
                        DocumentRequest::where('case_id', $request->case_id)->where('family_id', $request->family_id)->where('document_type', $fname)->update(['document' => json_encode($client_file), 'status' => 1]);
                        
                    }
                }
            }
        }
        // AdminTask::where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->update(['status' => 1]);

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();

        /*--------------------Notifications---------------*/ 
        
        $record = AdminTask::where('case_id', $request->case_id)->where('task_type', 'Assign_Case')->first();

        $msg = 'Document uploaded successfully!';
        if(!empty($record) && !empty($record->allot_user_id)) {
            $touser = User::where('id', $record->allot_user_id)->first();
            $n_link = url('admin/usertask/documents').'/'.$record->id;
            $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
        }
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        
        //Notification::send($usercase, new DatabaseNotification($message));
        
        Notification::send($touser, new DatabaseNotification($message));
        /*--------------------Notifications---------------*/ 
        // $cl = DocumentRequest::select('client_id')->where('case_id', $request->case_id)->first();
        return redirect('firm/case/case_documents/'.$request->case_id)->withInfo('Document uploaded successfully!');
    }

    public function setFamilyDocument4(Request $request) {
        
        if(!empty($request->file))
        {
            foreach ($request->file as $k1 => $file) {
                $client_file = array();
                $client_file[] = Storage::put('client_doc', $file);
                foreach ($request->filetype as $k2 => $fname) {
                    if($client_file){
                        DocumentRequest::where('case_id', $request->case_id)
                        ->where('document_type', $fname)
                        ->where('family_id', $request->fid)
                        ->update(['document' => json_encode($client_file), 'status' => 1]);
                        
                    }
                }
            }
        }
        // AdminTask::where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->update(['status' => 1]);

        $record = AdminTask::where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->first();
        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document upload';
        
        $touser = User::where('id', $record->allot_user_id)->first();
        $n_link = url('admin/usertask/documents').'/'.$record->id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        if($usercase->id != $record->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 
        $cl = DocumentRequest::select('client_id')->where('case_id', $request->case_id)->first();
        return redirect('firm/case/view_family_documents/'.$request->case_id.'/'.$request->fid)->withInfo('Document upload successfully!');
    }

    public function setCaseDocument(Request $request) {
        $data = Auth::User();
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $client_doc = array();
        $client_id = 0;
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                $client_id = $client->id;
            }
        }

        foreach ($request->file as $key => $file) {
            $f = Storage::put('client_doc', $file);
            $data = [
            'client_id' => $client_id,
            'case_id' => $request->case_id,
            'uploaded_by' => $data->id,
            'document' => $f,
            'title' => $request->title,
            'description' => $request->description
            ];
            ClientDocument::create($data);
        }
        

        return redirect('firm/case/case_documents/'.$request->case_id)->with('success', 'Client document upload successfully!');
    }

    public function case_notes($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();

        $msg = array();

        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $case->id)
                ->where('client_notes.task_for', 'CASE')
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
        
        return view('firmadmin.case.case_notes', compact('case', 'firm', 'msg', 'admintask', 'client'));
    }

    public function delete_note($id) {
        $note = ClientNotes::select('*')
                ->where('id', $id)
                ->first();
        ClientNotes::where('id', $id)->delete();
        if($note->task_for == 'CASE') {
            return redirect('firm/case/case_notes/'.$note->related_id)->with('success','Note delete successfully!');
        } 
        else {
           return redirect('firm/client/view_notes/'.$note->related_id)->with('success','Note delete successfully!'); 
        }
    }

    public function add_case_notes(Request $request) {

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
            'task_for' => 'CASE',
            'related_id' => $request->case_id,
            'notes' => $request->note,
            'subject' => $request->subject,
            'created_by' => Auth::User()->id
        ];
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

    public function case_forms($id, $uid=0)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        if($uid) {
            $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$id)
            ->where('client_information_forms.client_id',$uid)
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->get();
        }
        else {
            $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$id)
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->get();
        }
        
        foreach ($client_information_forms as $k => $v) {
            if($v->client_id) {
                $uu = getUserName($v->client_id);
                $client_information_forms[$k]->name = $uu->name;
            }
            else {
                $client_information_forms[$k]->name = 'Case Form';
            }
            // $client_information_forms[$k]->information=GetFieldValueForForm($client_information_forms[$k]->client_id,$client_information_forms[$k]->file_type);
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
            ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
            ->where('usermeta.meta_value', $id)
            ->where('users.role_id' ,'=', '7')
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }

        return view('firmadmin.case.case_forms', compact('case', 'firm', 'client_information_forms', 'family_alllist', 'uid', 'client', 'admintask'));
    }

    public function add_forms($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $client = array();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
            ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
            ->where('usermeta.meta_value', $id)
            ->where('users.role_id' ,'=', '7')
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.add_forms', compact('case', 'firm', 'client', 'family_alllist', 'admintask'));
    }

    public function create_case_form(Request $request) {
        $file_data = json_decode($request->file_data);
        $path = $file_data->id;
        $client = new \Spatie\Dropbox\Client(env('DROPBOX_TOKEN'));
        $a = $client->download($path);
        $file = 'forms/'.$file_data->name;
        Storage::put($file, stream_get_contents($a));
        $data = [
             'client_id' => $request->client_id,
             'case_id' => $request->case_id,
             'firm_id' => $request->firm_id,
             'file' => $file,
             'file_type' => $file_data->name   
        ];
        ClientInformation::create($data);

         /*--------------------Notifications---------------*/ 
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Client Form submited';
        
        if($firm_name->account_type == 'CMS') {
            $client1 = Newclient::select('*')->where('id', $request->client_id)->first();

            $touser = User::where('id', $request->client_id)->first();
            $n_link = url('firm/clientcase/show').'/'.$request->case_id;
            $message = collect(['title' => 'Firm Admin Client Form submited', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
            
            
            $n_link = url('firm/case/case_forms').'/'.$request->case_id;
            $message = collect(['title' => 'Firm Admin Client Form submited', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
            $FirmCase = FirmCase::select('users.*')
                            ->join('users', 'users.id', '=', 'case.user_id')
                            ->where('case.id', $request->case_id)
                            ->first();

            $usercase = User::where('id', $FirmCase->id)->first();
            Notification::send($usercase, new DatabaseNotification($message));
        }
        $n_link = url('firm/case/case_forms').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Client Form submited', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $touser = User::where('id',Auth::User()->id)->first();
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        return redirect('firm/case/case_forms/'.$request->case_id)->with('success','Client Form submited successfully!');
    }

    public function additional_service($id)
    {
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
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        // $additional_service = json_decode($case->additional_service);
        // pre($additional_service);
        // die();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        $I_864_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor')->first()['VP_Pricing'];
        $I_864A_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864A, Contract Between Sponsor and Household Member')->first()['VP_Pricing'];
        $I_DS260_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'DS-260 for Additional Derivative Beneficiary (online only)')->first()['VP_Pricing'];
        $I_Affidavit_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'Draft a Letter/Affidavit')->first()['VP_Pricing'];

        return view('firmadmin.case.additional_service', compact('case','firm', 'card', 'admintask', 'client', 'I_864_Cost', 'I_864A_Cost', 'I_DS260_Cost', 'I_Affidavit_Cost'));
    }

    public function affidavit($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $Affidavitdoc = AffidavitDocumentRequest::select('*')
                    ->where('case_id', $id)
                    ->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.affidavit', compact('case','firm', 'Affidavitdoc', 'admintask', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $currunt_user = Auth::User();
        $firm_id = $currunt_user->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*', 'ur.name as urn', 'up.name as upn')
                ->where('case.id', $id)
                ->leftJoin('users as ur', 'ur.id', '=', 'case.user_id')
                ->leftJoin('users as up', 'up.id', '=', 'case.assign_paralegal')
                ->first();
        $client = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $searchResults = \Stripe\Customer::all([
            "email" => $currunt_user->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cust = '';
        $card = '';
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
            $card = $cust->sources->data;
        }
        return view('firmadmin.case.edit', compact('case', 'id', 'firm', 'client', 'currunt_user', 'card'));
    }

    public function update_case(Request $request) {
        $currunt_user = Auth::User();    
        // pre($request->all()); 
        // die();
        $case = FirmCase::select('*')->where('id', $request->id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();
        if($currunt_user->role_id == 4 && $request->VP_Assistance && !empty($request->approve_pay)) {
            $data2['case_id'] = $request->id;
            $data2['task_type'] = 'Assign_Case';
            $data2['task'] = 'Assign Case to VP';
            $data2['firm_admin_id'] = $currunt_user->id;
            $data2['status'] = 0;
            $atask = AdminTask::create($data2);
            /* --------------------Notifications--------------- */
            $msg = 'Firm ' . $firm_name->firm_name . ' paid for VP Assistance successfully!';
            $touser = User::where('id', 1)->first();
            $n_link = url('admin/task/edit').'/'.$atask->id;
            $message = collect(['title' => 'Firm Admin Payment', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
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
                    $source = \Stripe\Customer::createSource(
                      $cus_id,
                      [
                          'source' => $stripeToken,
                      ]
                    );
                    $card_source = $source->id;
                }
            }
            else {
                $cus = \Stripe\Customer::create([
                  'description' => $currunt_user->name,
                  'email' => $currunt_user->email,
                  'name' => $currunt_user->name
                ]);
                $cus_id =  $cus->id;
                $source = \Stripe\Customer::createSource(
                  $cus_id,
                  [
                      'source' => $stripeToken,
                  ]
                );
                $card_source = $source->id;
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
            $data = array();
            $data['tx_id'] = $charge->id;
            $data['amount'] = $charge->amount;
            $data['type'] = 'Case';
            $data['user_id'] = $currunt_user->id;
            $data['related_id'] = $request->id;
            $data['responce'] = json_encode($charge);
            $data['paymenttype'] = 1;
            Transaction::create($data);

            $client = DB::table('new_client')->where('user_id', $request->firmclient)->first();
            $client_name = '';
            if(!empty($client)) {
                $client_name = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
            }
            $caseType = $request->case_type;
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
            if($firm_name->account_type == 'CMS') {
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
            
            /* --------------------Notifications--------------- */
            $msg = 'Firm ' . $firm_name->firm_name . ' create case successfully!';
            $touser = User::where('id', $request->firmclient)->first();
            $n_link = url('firm/clientcase/show').'/'.$request->id;
            $message = collect(['title' => 'Firm Admin Create Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            if($firm_name->account_type == 'CMS') {
                Notification::send($touser, new DatabaseNotification($message));

                $msg = 'You have been assigned a case #'.$request->id;
                $n_link = url('firm/case/show').'/'.$request->id;
                $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                $caseuser = User::where('id', $request->firmuser)->first();
                Notification::send($caseuser, new DatabaseNotification($message));

                $msg = 'Your case have been approved by Firm Admin';
                $n_link = url('firm/case/show').'/'.$request->id;
                $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
                $caseuser = User::where('id', $case->created_by)->first();
                Notification::send($caseuser, new DatabaseNotification($message));

                $case_user_name =  $caseuser->name;
                $msg1 = "Hi, $case_user_name.<br>";
                $msg1 .= "Your case have been approved by Firm Admin, please review<br>";
                $msg1 .= $n_link;
                
                $msg = EmptyEmailTemplate($msg1);
                $args = array (
                    'bodyMessage' => $msg,
                    'to' => $caseuser->email,
                    'subject' => 'New Case Approvel',
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                send_mail($args);
            }
            /* --------------------Notifications--------------- */
            FirmCase::where('id', $request->id)->update(['status' => 1, 'VP_Assistance' => 1]);
            return redirect('firm/case')->with('success', 'Case update successfully!');
        }
        else if($currunt_user->role_id == 4 && !empty($request->decline)) {
            $msg = 'Your case have been decline by Firm Admin';
            $n_link = url('firm/case/show').'/'.$request->id;
            $message = collect(['title' => $msg, 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            $caseuser = User::where('id', $case->created_by)->first();
            Notification::send($caseuser, new DatabaseNotification($message));

            $case_user_name =  $caseuser->name;
            $msg1 = "Hi, $case_user_name.<br>";
            $msg1 .= "Your case have been decline by Firm Admin, please review<br>";
            $msg1 .= $n_link;
            
            $msg = EmptyEmailTemplate($msg1);
            $args = array (
                'bodyMessage' => $msg,
                'to' => $caseuser->email,
                'subject' => 'New Case Approvel',
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
            FirmCase::where('id', $request->id)->update(['status' => -2]);
            return redirect('firm/case')->with('success', 'Case decline successfully!');
        }
        else if($currunt_user->role_id == 5 && !empty($request->VP_Assistance)) {
            echo "update VP_Assistance";
            /* --------------------Notifications--------------- */
            $msg = Auth::User()->name.' has request for assign TILA VP, please review';
            $touser = User::where('email', $firm_name->email)->first();
            $n_link = url('firm/case/edit').'/'.$request->id;
            $message = collect(['title' => 'Assign TILA VP', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
            /* --------------------Notifications--------------- */

            FirmCase::where('id', $request->id)->update(['status' => -1, 'VP_Assistance' => 1]);

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
            return redirect('firm/case')->with('success', 'Request for assign TILA VP successfully!');
        }
        else if($currunt_user->role_id == 5) {
            return redirect('firm/case')->with('success', 'Case update successfully!');
        }
        
    }

    public function update(Request $request) {


        Firm::where('id', $_POST['id'])->update(['firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type']]);
        return redirect('admin/firm')->with('success', 'Firm Account update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) {
        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();

        $msg = 'Firm ' . $firm_name->firm_name . ' deleted case successfully!';

        $firmcase = FirmCase::select('client_id')->where('id', $id)->first();

        $touser = User::where('id', $firmcase->client_id)->first();
        $message = collect(['title' => 'Firm Admin Deleted Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));
        }
        /* --------------------Notifications--------------- */
        FirmCase::where('id', $id)->delete();
        return redirect('firm/case')->with('success', 'Firm case deleted successfully!');
    }

    public function case_complete($id) {
        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();

        $msg = 'Firm ' . $firm_name->firm_name . ' marked as completed case successfully!';

        $firmcase = FirmCase::select('client_id')->where('id', $id)->first();

        $touser = User::where('id', $firmcase->client_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$id;
        $message = collect(['title' => 'Firm Admin marked as completed Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));
        }
        /* --------------------Notifications--------------- */

        /* --------------------------Email--------------------------- */
        $q = "select c.first_name as clientf,c.middle_name as clientm,c.last_name  as clientl,u.name as username,u.email as useremail from `case` as cs,users as u,new_client as c where cs.user_id >0 and u.id=cs.user_id and c.user_id=cs.client_id and cs.id='" . $id . "'";
        $emailsa = DB::select(DB::raw($q));
        pre($emailsa);

        $remove = array(
            'AssignedFirmUser' => $emailsa[0]->username,
            'ClientName' => $emailsa[0]->clientf . ' ' . $emailsa[0]->clientm . ' ' . $emailsa[0]->clientl,
            'CaseNumber' => $id,
        );
        $email = EmailTemplate(28, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $emailsa[0]->useremail,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        /* --------------------------Email--------------------------- */


        FirmCase::where('id', $id)->update(['status' => 9]);
        return redirect('firm/case')->with('success', 'Case marked as completed successfully!');
    }

    public function case_complete1($id) {
        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();

        $msg = 'Firm ' . $firm_name->firm_name . ' marked as completed case successfully!';

        $firmcase = FirmCase::select('client_id')->where('id', $id)->first();

        $touser = User::where('id', $firmcase->client_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$id;
        $message = collect(['title' => 'Firm Admin marked as completed Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            
            /* --------------------Notifications--------------- */
            Notification::send($touser, new DatabaseNotification($message));
            /* --------------------Notifications--------------- */

            /* --------------------------Email--------------------------- */
            $q = "select c.first_name as clientf,c.middle_name as clientm,c.last_name  as clientl,u.name as username,u.email as useremail from `case` as cs,users as u,new_client as c where cs.user_id >0 and u.id=cs.user_id and c.user_id=cs.client_id and cs.id='" . $id . "'";
            $emailsa = DB::select(DB::raw($q));
            //pre($emailsa);

            $remove = array(
                'AssignedFirmUser' => $emailsa[0]->username,
                'ClientName' => $emailsa[0]->clientf . ' ' . $emailsa[0]->clientm . ' ' . $emailsa[0]->clientl,
                'CaseNumber' => $id,
            );
            $email = EmailTemplate(28, $remove);
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $emailsa[0]->useremail,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
            /* --------------------------Email--------------------------- */
        }

        FirmCase::where('id', $id)->update(['status' => 9]);
        return redirect('firm/case/show/'.$id)->with('success', 'Case marked as completed successfully!');
    }

    public function case_incomplete($id) {
        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();

        $msg = 'Firm ' . $firm_name->firm_name . ' marked as incompleted case successfully!';

        $firmcase = FirmCase::select('client_id')->where('id', $id)->first();

        $touser = User::where('id', $firmcase->client_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$id;
        $message = collect(['title' => 'Firm Admin marked as incompleted Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));
        }
        /* --------------------Notifications--------------- */
        FirmCase::where('id', $id)->update(['status' => 8]);
        return redirect('firm/case/show/'.$id)->with('success', 'Case marked as incompleted successfully!');
    }

    public function update_court_date(Request $request) {

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', Auth::User()->firm_id)->first();

        $msg = 'Firm ' . $firm_name->firm_name . ' update case court date successfully!';

        $firmcase = FirmCase::select('client_id')->where('id', $request->case_id)->first();

        $touser = User::where('id', $firmcase->client_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin update case court date', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));
        }
        /* --------------------Notifications--------------- */
        FirmCase::where('id', $request->case_id)->update(['CourtDates' => $request->court_date, 'CourtDates_Time' => strtotime($request->court_date)]);
        return redirect('firm/case')->with('success', 'Court date update successfully!');
    }

    public function create_task($id) {
        return view('firmadmin.case.create_task', ["id" => $id]);
    }

    public function add_task(Request $request) {

        $firm_admin_id = Auth::User()->id;
        $data = [
            'firm_admin_id' => $firm_admin_id,
            'task_type' => 'schedule_training',
            'priority' => $request->priority,
            'task' => $request->task,
            'mytask' => $request->mytask,
            'client_task' => $request->client_task,
            'assigned_to' => $request->assigned_to,
            'case_id' => $request->case_id
        ];
        AdminTask::create($data);
        return redirect('firm/case')->with('success', 'Case task created successfully!');
        // pre($data);
    }

    public function case_event($id) {
        
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $client = Newclient::where('user_id', $case->client_id)->first();
        $event =   Event::select('*')->where('title', 'CASE')->where('related_id', $id)->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.case_event', compact('firm', 'case', 'event', 'admintask', 'client'));
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
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $users = User::select('users.*', 'roles.name as role_name')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('firm_id', $data->firm_id)
                // ->where('users.id', '!=', $data->id)
                ->whereIn('role_id', ['4', '5'])
                ->get();



        $arr1 = Event::select('who_consult_with')
                ->where('related_id', $id)
                ->where('title', "CASE")
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


        // $arr = Event::select('*')
        //         ->where('related_id', $id)
        //         ->where('title', "CASE")
        //         ->orderBy('id', 'DESC')
        //         ->get();
        $arr = Event::select('event.*', 'case.*', 'calendar_setting.value', 'event.id as e_id')
                ->join('case','case.id', 'event.related_id')
                ->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'case.user_id')
                ->where('event.related_id',$id)
                ->where('event.title',"CASE")
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
                $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->case_type;
                $eedate = '';
                if(!empty($e->e_date)) {
                    $eedate = date('m/d/Y', strtotime($e->e_date));
                }
                $events[] = array(
                    'event_id' => $e->id,
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

        // $lead = Lead::select('name','last_name')->where('id', $id)->first();
        // /* --------------------Notifications--------------- */

        // $firm_id = Auth::User()->firm_id;
        // $firm_name = Firm::select('firm_name')->where('id', Auth::User()->firm_id)->first();

        // $msg = 'Firm ' . $firm_name->firm_name . ' deleted case successfully!';

        // $firmcase = FirmCase::select('client_id')->where('id', $request->case_id)->first();

        // $touser = User::where('id', $firmcase->client_id)->first();
        // $message = collect(['title' => 'Firm Admin update case court date', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);

        // Notification::send($touser, new DatabaseNotification($message));
        // /* --------------------Notifications--------------- */
        /* --------------------------Email--------------------------- */
        $remove['user_first_name'] = 'Ankit';
        $remove['attorney_first_name'] = 'Ankit Saxena';

        $email = EmailTemplate(1, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => 'snvankit@gmail.com',
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        //send_mail($args);
        /* --------------------------Email--------------------------- */

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
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.case.create_event', compact('id', 'users', 'events', 'access_token', 'dateandtime', 'user_id', 'firm', 'case', 'admintask', 'authUrl', 'client'));
    }

    public function create_case_event(Request $request) {

        $res = array();
        $reminder_arr = array();
        $reminders = array(
                'useDefault' => FALSE,
                'overrides' => array()
              );
        foreach ($request->event_reminder as $key => $value) {
            $rname = $value['name'];
            $rval  = $value['value'];
            if($rname == 'event_reminder[count][]') {
                $reminder_arr['count'][] = $rval;
            }
            if($rname == 'event_reminder[type][]') {
                $reminder_arr['type'][] = $rval;
            }
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
                'title' => "CASE",
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
                'title' => "CASE",
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
        if ($request->reschedule == "true") {
            $lead_event_data['coutner'] = $request->coutner;
            $event = Event::where('related_id', $request->lead_id)->update($lead_event_data);

            /* --------------------Notifications--------------- */
            $firm_id = Auth::User()->firm_id;
            $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
            $n_link = url('firm/clientcase/show').'/'.$request->lead_id;
            $msg = 'Firm ' . $firm_name->firm_name . ' case event Re-Schedule successfully!';
            $firmcase = FirmCase::select('client_id')->where('id', $request->lead_id)->first();
            $touser = User::where('id', $firmcase->client_id)->first();
            $message = collect(['title' => 'Firm Admin case event Re-Schedule', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            if($firm_name->account_type == 'CMS') {
                Notification::send($touser, new DatabaseNotification($message));
            }
            /* --------------------------Email--------------------------- */
            $remove['user_first_name'] = 'Ankit';
            $remove['attorney_first_name'] = 'Ankit Saxena';

            $email = EmailTemplate(1, $remove);

            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => 'snvankit@gmail.com',
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            //send_mail($args);
            /* --------------------------Email--------------------------- */
            /* --------------------Notifications--------------- */
        } else {
            if($request->event_id) {
                $event = Event::where('id', $request->event_id)->update($lead_event_data);
            }
            else {
                $event = Event::create($lead_event_data);
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
            /* --------------------Notifications--------------- */
            $firm_id = Auth::User()->firm_id;
            $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
            $n_link = url('firm/clientcase/show').'/'.$request->lead_id;
            $msg = 'Firm ' . $firm_name->firm_name . ' case event created successfully!';
            $firmcase = FirmCase::select('client_id')->where('id', $request->lead_id)->first();
            $touser = User::where('id', $firmcase->client_id)->first();
            $message = collect(['title' => 'Firm Admin case event created', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            if($firm_name->account_type == 'CMS') {
                Notification::send($touser, new DatabaseNotification($message));
            }
            /* --------------------Notifications--------------- */
        }
        if (isset($request->create_lead_with_event)) {
            return redirect()->route('firm.case')->with('success', 'Firm Case Create and Schedule Consult successfully!');
        }


        $res['status'] = true;
        $res['msg'] = 'Case Event created successfully!';
        echo json_encode($res);
        $access_token = get_user_meta(Auth::User()->id, 'access_token');
        if (!empty($access_token)) {
            $user_timezone = GetUserCalendarTimezone($access_token);
            $time_in_24_hour_format = date("H:i:s", strtotime($request->time));
            $etime = array();
            $etime['start_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            if($request->event_type == 'Reminder') {
                $etime['end_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            }
            else {
                $etime['end_time'] = date('Y-m-d', strtotime($e_date)) . 'T' . date('H:i:s', strtotime($e_date));
            }
            $gid = CreateCalendarEvent('primary', $request->event_title, 0, $etime, $user_timezone, $access_token, $reminders);
            Event::where('id', $event->id)->update(['google_id' => $gid]);
        }
        die();
        if ($event) {
            //return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        } else {
            //return redirect('firm/create_event')->withInfo(' not created, please try again');
        }
    }

    public function case_document($id) {
        return view('firmadmin.case.casedocument',compact('id'));
    }

    public function getCaseDataDocument($id) {
        $users = DocumentRequest::select('*','status as dstatus1')
        ->where('case_id', $id)
        ->get();   
        foreach ($users as $key => $user) {
            
            if($user->family_id) {
                $uu = getUserName($user->family_id);
                $users[$key]->name = $uu->name;
            }
            else {
                $client = Newclient::select('*')->where('id', $user->client_id)->first();
                $users[$key]->name = $client->first_name.' '.$client->last_name;
            }
            if($users[$key]->status == 4) {
                $users[$key]->status = 'Rejected';
            }
            else if($users[$key]->status == 3) {
                $users[$key]->status = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->status = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->status = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->status = 'Paid for translation';
                }
            }
            else if($users[$key]->status == 2) {
                $users[$key]->status = 'Accepted';
            }
            else if($users[$key]->status == 1) {
                $users[$key]->status = 'Submitted';
            }
            else {
                $users[$key]->status = 'Requested';
            }
            $users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
        }
        return datatables()->of($users)->toJson();  
    }

    public function getFamilyDataDocument($id, $fid) {
        $users = DocumentRequest::select('*','status as dstatus1')
        ->where('case_id', $id)
        ->where('family_id', $fid)
        ->get();   
        foreach ($users as $key => $user) {
            
            if($user->family_id) {
                $uu = getUserName($user->family_id);
                $users[$key]->name = $uu->name.' - Family';
            }
            else {
                $client = Newclient::select('*')->where('id', $user->client_id)->first();
                $users[$key]->name = $client->first_name.' '.$client->last_name. ' - Client';
            }
            if($users[$key]->status == 4) {
                $users[$key]->status = 'Rejected';
            }
            else if($users[$key]->status == 3) {
                $users[$key]->status = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->status = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->status = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->status = 'Paid for translation';
                }
            }
            else if($users[$key]->status == 2) {
                $users[$key]->status = 'Accepted';
            }
            else if($users[$key]->status == 1) {
                $users[$key]->status = 'Submitted';
            }
            else {
                $users[$key]->status = 'Requested';
            }
            $users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
        }
        return datatables()->of($users)->toJson();  
    }

    public function setCaseDataDocument(Request $request) {
        $client_file = array();
        if(!empty($request->file))
        {
            foreach ($request->file as $key => $file) {
                $client_file[] = Storage::put('client_doc', $file);
            }
            if($client_file){
                DocumentRequest::where('id', $request->id)->update(['document' => json_encode($client_file), 'status' => 1]);
            }
        }

        /*--------------------Notifications---------------*/ 
        
        $record = AdminTask::select('allot_user_id')->where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->first();

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document upload';
        
        $touser = User::where('id', $record->allot_user_id)->first();
        $n_link = url('admin/usertask/documents').'/'.$record->id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        // if($usercase->id != $task->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        // }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 
        return redirect('firm/case/case_document/'.$request->case_id)->withInfo('Document upload successfully!');
    }

    public function Case_Request_Quote(Request $request, $id) {
        //echo $id;
        $doc = DocumentRequest::where('id', $id)->first();
        // $client = Newclient::select('*')->where('id', $doc->client_id)->first();
        
        DocumentRequest::where('id', $id)->update(['quote' => 1]);
        $task = AdminTask::select('*')
                ->where('case_id', $doc->case_id)
                ->where('task_type', 'Assign_Case')
                ->first();
        $data1['case_id'] = $id;  
        $data1['firm_admin_id'] = Auth::User()->id;
        $data1['allot_user_id'] = $task->allot_user_id;
        $data1['task_type'] = 'provide_a_quote';
        $data1['task'] = 'Provide a quote';
        $data1['status'] = 0;
        $atask = AdminTask::create($data1);

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document quote requested';
        
        $touser = User::where('id', 1)->first();
        $n_link = url('admin/task/edit').'/'.$atask->id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
            Notification::send($usercase, new DatabaseNotification($message));
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        /* ----- Email notificcation for assigned user  ---- */

        $remove = array(
            'Assigned_Firm_User' => Auth::User()->name,
            'ClientName'=> '',
            'DocumentType'=>$doc->document_type,
            'TID' => $doc->id,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(24, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Auth::User()->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        /* ----- Email notificcation for assigned user  ---- */

        /* ----- Email notificcation for TILA Admin  ---- */
        $remove = array(
            'FirmNmae' => $firm_name->firm_name,
            'FirmEmail'=> $firm_name->email,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(19, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Eadmin(),
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        /* ----- Email notificcation for TILA Admin  ---- */

        return redirect('firm/case/case_documents/'.$doc->case_id)->withInfo('Document quote requested successfully!');
    }

    public function Case_Family_Request_Quote(Request $request, $id, $fid) {
        //echo $id;
        $doc = DocumentRequest::where('id', $id)->first();
        // $client = Newclient::select('*')->where('id', $doc->client_id)->first();
        
        DocumentRequest::where('id', $id)->update(['quote' => 1]);
        $data1['case_id'] = $id;  
        $data1['firm_admin_id'] = Auth::User()->id;
        $data1['allot_user_id'] = $doc->requested_by;
        $data1['task_type'] = 'provide_a_quote';
        $data1['task'] = 'Provide a quote';
        $data1['status'] = 0;
        $atask = AdminTask::create($data1);

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document quote requested';
        
        $touser = User::where('id', 1)->first();
        $n_link = url('admin/task/edit').'/'.$atask->id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
            Notification::send($usercase, new DatabaseNotification($message));
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        /* ----- Email notificcation for assigned user  ---- */

        $remove = array(
            'Assigned_Firm_User' => Auth::User()->name,
            'ClientName'=> '',
            'DocumentType'=>$doc->document_type,
            'TID' => $doc->id,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(24, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Auth::User()->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        /* ----- Email notificcation for assigned user  ---- */

        /* ----- Email notificcation for TILA Admin  ---- */
        $remove = array(
            'FirmNmae' => $firm_name->firm_name,
            'FirmEmail'=> $firm_name->email,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(19, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Eadmin(),
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        /* ----- Email notificcation for TILA Admin  ---- */

        return redirect('firm/case/view_family_documents/'.$doc->case_id.'/'.$fid)->withInfo('Document quote requested successfully!');
    }

    public function pay_case_translation(Request $request, $id) {
        //$cl = Newclient::where('id', $id)->first();
        
        $did = $request->paydocid;
        $doc = DocumentRequest::where('id', $did)->first();
        $stripeToken = $request->stripeToken;
        $casecost1 = intval(str_replace('$','',$doc->quote_cost))*100;
        
        $currunt_user = Auth::User(); 
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $searchResults = \Stripe\Customer::all([
            "email" => $currunt_user->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cus_id = '';
        try {
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
        } catch(\Stripe\Exception\CardException $e) {
            return redirect()->back()->withErrors($e->getError()->message);
        }

        $data['tx_id'] = $charge->id;
        $data['amount'] = $charge->amount;
        $data['type'] = 'Translation';
        $data['related_id'] = $doc->case_id;
        $data['user_id'] = Auth::User()->id;
        $data['responce'] = json_encode($charge);
        $data['paymenttype'] = 3;
        Transaction::create($data);

        DocumentRequest::where('id', $did)->update(['quote' => 3]);

        $data1['case_id'] = $did;  
        $data1['firm_admin_id'] = Auth::User()->id;
        $data1['allot_user_id'] = $doc->requested_by;
        $data1['task_type'] = 'upload_translated_document';
        $data1['task'] = 'Upload translated document';
        $data1['status'] = 0;
        $atask = AdminTask::create($data1);

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Paid for translation';
        
        $touser = User::where('id', 1)->first();
        $n_link = url('admin/task/edit').'/'.$atask->id;
        $message = collect(['title' => 'Firm Admin Paid for translation', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
        $message = collect(['title' => 'Firm Admin Paid for translation', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        // if($usercase->id != $task->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        // }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        /* -------------- email firm user quote notification ------- */
        $remove = array(
            'ClientName' => '',
            'Cost' => $doc->quote_cost,
        );
        $email = EmailTemplate(25, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Eadmin(),
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        $case = FirmCase::select('*')->where('id', $doc->case_id)->first();
        $case->case_cost = $doc->quote_cost;
        app('App\Http\Controllers\HomeController')->DEVQBInvoiceCreation($case);
        /* -------------- email firm user quote notification ------- */

        if(isset($request->family_id)) {
            return redirect('firm/case/view_family_documents/'.$doc->case_id.'/'.$request->family_id)->withInfo('Paid for translation successfully!');
        }
        else {
            return redirect('firm/case/case_documents/'.$doc->case_id)->withInfo('Paid for translation successfully!');
        }
        
    }

    public function case_family($id) {
        $firm_id = Auth::User()->firm_id;
        
        // $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'beneficiary')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        // $derivative_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'derivative')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $interpreter_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'interpreter')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $petitioner_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'petitioner')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $Co_Sponsor = array();
        // $Co_Sponsor_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'Co_Sponsor')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->first();
        // $Household_Member = array();
        // $Household_Member_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'Household_Member')
        // ->where('usermeta.meta_value', $id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->first();

        // if (!empty($Co_Sponsor_arr)) {
        //     $Co_Sponsor = $Co_Sponsor_arr;
        // }
        
        // if (!empty($Household_Member_arr)) {
        //     $Household_Member = $Household_Member_arr;
        // }      

        $case = FirmCase::select('*')->where('id', $id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        if($case->client_id) {
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }
        $existing_members = User::select('users.*')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->pluck("users.id");
        $em = array();
        if(!empty($existing_members)) {
            foreach ($existing_members as $k => $v) {
                $em[] = $v;
            }
        }
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $QuestionsArr=array(
            'Petitioner'=>'58bd6f6e02',
            'Principal Beneficiary'=>'c190d60db9',
            'Firm'=>'282505ebbb',
            'Derivative Beneficiary'=>'3cc1ec0e1f',
            'Household Member'=>'3dcc61d98e',
            'Co Sponsor'=>'a013381c7e',
        );
        $countries = DB::table("countries")->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.case_family', compact('family_list','family_alllist', 'firm', 'case', 'client', 'QuestionsArr', 'countries', 'admintask', 'em'));
    }
    
    public function add_case_family($id) {
        $firm_id = Auth::User()->firm_id;
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $family_arr = array(); 
          foreach ($family_list as $key => $value) { 
            $family_arr[] = $value->uid;
          }


        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        if($case->client_id) {
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }
        $countries = DB::table("countries")->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.add_case_family', compact('id', 'firm', 'case', 'client', 'family_alllist', 'family_arr', 'countries', 'admintask'));
    }

    public function add_case_petitioner($id) {
        $firm_id = Auth::User()->firm_id;
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $family_arr = array(); 
          foreach ($family_list as $key => $value) { 
            $family_arr[] = $value->uid;
          }

        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('users.firm_id' ,'=', $firm_id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        
        $countries = DB::table("countries")->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.add_case_petitioner', compact('id', 'firm', 'case', 'client', 'family_alllist', 'family_arr', 'countries', 'admintask'));
    }

    public function add_case_family_member($id) {
        $firm_id = Auth::User()->firm_id;
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $family_arr = array(); 
          foreach ($family_list as $key => $value) { 
            $family_arr[] = $value->uid;
          }

        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        if($case->client_id) {
            
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
                ->where('users.role_id' ,'=', '7')
                ->where('users.firm_id' ,'=', $firm_id)
                ->join('client_family', 'client_family.email', '=', 'users.email')
                ->where('client_family.client_id', '=', $client->id)
                ->get();
             
        }
        else {
            
            if($firm->account_type == 'CMS') {
                  $family_alllist = array(); 
            }
            else {
                $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
                ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
                ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
                ->where('usermeta.meta_value', $id)
                ->where('users.role_id' ,'=', '7')
                ->join('client_family', 'client_family.email', '=', 'users.email')
                ->get();
            }
        }
        $countries = DB::table("countries")->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.add_case_family_member', compact('id', 'firm', 'case', 'client', 'family_alllist', 'family_arr', 'countries', 'admintask'));
    }

    public function add_case_interpreter($id) {
        $firm_id = Auth::User()->firm_id;
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $family_arr = array(); 
          foreach ($family_list as $key => $value) { 
            $family_arr[] = $value->uid;
          }

        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('users.firm_id' ,'=', $firm_id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        $countries = DB::table("countries")->get();
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.add_case_interpreter', compact('id', 'firm', 'case', 'client', 'family_alllist', 'family_arr', 'countries', 'admintask'));
    }

    public function create_case_family(Request $request) {
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
        // pre($record);
        // die();
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'email' => 'string|email|unique:users|unique:new_client',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        app('App\Http\Controllers\HomeController')->CreateClientFamily($record,$current_firm_id,$request->case_id);

        $record['dob'] = date('Y-m-d', strtotime($record['dob']));
        $check = ClientFamily::create($record);

        if ($check) {
            return redirect('firm/case/case_family/' . $request->case_id)->with('success', 'Family created successfully!');
        } else {
            return redirect('firm/case/case_family/' . $request->case_id)->with('error', 'Family not created, please try again');
        }
    }

    public function view_family_forms($id, $fid) {
        $family = User::select('*')->where('id', $fid)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();    
        $family_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$id)
            ->whereIn('client_information_forms.client_id',[0, $fid])
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->get();
        foreach ($family_information_forms as $k => $v) {
            if($v->client_id) {
                $uu = getUserName($v->client_id);
                $family_information_forms[$k]->name = $uu->name;
            }
            else {
                $family_information_forms[$k]->name = 'Case Form';
            }
        }
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.view_family_forms', compact('family_information_forms', 'family', 'id', 'firm', 'case', 'client', 'admintask'));
    }

    public function add_case_family_forms($id, $fid) {
        $family = User::select('*')->where('id', $fid)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();  
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }  
        return view('firmadmin.case.add_case_family_forms', compact('family', 'id', 'firm', 'case', 'client', 'admintask'));
    }

    public function view_family_documents($id, $fid)
    {
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
        if($searchResults->data) {
            $cust =  $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)
        ->where('family_id', $fid)->get();
        $admintask = array();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first(); 
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.view_family_documents', compact('case', 'requested_doc','firm', 'card', 'fid', 'admintask', 'client'));
    }

    public function create_family_forms(Request $request) {
        $file_data = json_decode($request->file_data);
        $path = $file_data->id;
        $client = new \Spatie\Dropbox\Client(env('DROPBOX_TOKEN'));
        $a = $client->download($path);
        $file = 'forms/'.$file_data->name;
        Storage::put($file, stream_get_contents($a));
        $data = [
             'client_id' => $request->family_id,
             'case_id' => $request->case_id,
             'firm_id' => $request->firm_id,
             'file' => $file,
             'file_type' => $file_data->name   
        ];
        ClientInformation::create($data);

        return redirect('firm/case/view_family_forms/'.$request->case_id.'/'.$request->family_id)->with('success','Family Form submited successfully!');
    }

    public function pay_additional_service(Request $request) {
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $as = json_decode($case->additional_service);
        //pre($request->all());
        $cost = 0;
        $I_864_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor')->first()['VP_Pricing'];
        $I_864A_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864A, Contract Between Sponsor and Household Member')->first()['VP_Pricing'];
        $I_DS260_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'DS-260 for Additional Derivative Beneficiary (online only)')->first()['VP_Pricing'];
        $I_Affidavit_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'Draft a Letter/Affidavit')->first()['VP_Pricing'];
        if($request->nvc_packet_quantity_new) {
            // $as->nvc_packet_quantity = intval($as->nvc_packet_quantity)+intval($as->nvc_packet_quantity_new);
            $as->nvc_packet_quantity = intval($as->nvc_packet_quantity_new);
            $cost = $I_DS260_Cost*intval($as->nvc_packet_quantity_new);
            unset($as->nvc_packet_quantity_new);
        }
        if($request->additional_service_new) {
            if($as->additional_service_new) {
                foreach ($as->additional_service_new as $k => $v) {
                    $as->additional_service->additional_service[] = $v;
                    if($v == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                        $cost = $cost+$I_864_Cost;
                        $file1 = 'forms/all/i-864.pdf';
                        $file_dataname = 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor';
                        $data = [
                             'client_id' => $case->client_id,
                             'case_id' => $case->id,
                             'firm_id' => $case->firm_id,
                             'file' => $file1,
                             'file_type' => $file_dataname   
                        ];
                        ClientInformation::create($data);
                    }
                    if($v == 'I-864A, Contract Between Sponsor and Household Member') {
                        $cost = $cost+$I_864A_Cost;
                        $file1 = 'forms/all/i-864a.pdf';
                        $file_dataname = 'I-864A, Contract Between Sponsor and Household Member';
                        $data = [
                             'client_id' => $case->client_id,
                             'case_id' => $case->id,
                             'firm_id' => $case->firm_id,
                             'file' => $file1,
                             'file_type' => $file_dataname   
                        ];
                        ClientInformation::create($data);
                    }
                    
                }
                
            }
            unset($as->additional_service_new);
        }
        if($request->declaration_new) {
            if($as->declaration->declaration_new) {
                foreach ($as->declaration->declaration_new as $k => $v) {
                    $as->declaration->declaration[] = $v;
                    $as->declaration->declaration_other[] = $as->declaration->declaration_other_new[$k];
                    $cost = $cost+$I_Affidavit_Cost;
                }
                
            }
            unset($as->declaration->declaration_new);
            unset($as->declaration->declaration_other_new);
        }
        // pre($cost);
        $casecost1 = intval(str_replace('$','',$cost))*100;
        // pre($casecost1);
        $stripeToken = $request->stripeToken;
        $currunt_user = Auth::User(); 
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $searchResults = \Stripe\Customer::all([
            "email" => $currunt_user->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cus_id = '';
        try {
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
        } catch(\Stripe\Exception\CardException $e) {
            return redirect()->back()->withErrors($e->getError()->message);
        }

        $data['tx_id'] = $charge->id;
        $data['amount'] = $charge->amount;
        $data['type'] = 'Additional Service';
        $data['related_id'] = $request->case_id;
        $data['user_id'] = Auth::User()->id;
        $data['responce'] = json_encode($charge);
        $data['paymenttype'] = 3;

        Transaction::create($data);
        
        FirmCase::where('id', $request->case_id)->update(['additional_service' => json_encode($as)]);

        $task = AdminTask::select('*')
                ->where('task_type', 'Additional_Service')
                ->where('case_id', $request->case_id)
                ->first();
        $task_data = [
                    'firm_admin_id' => Auth::User()->id,
                    'task_type' => 'Additional_Service',
                    'task' => 'Paid for Additional Service',
                    'case_id' => $request->case_id,
                    'allot_user_id' => $task->allot_user_id,
                    'status' => 0
                ];
        AdminTask::create($task_data);

        // $task = AdminTask::select('*')
        //         ->where('task_type', 'Assign_Case')
        //         ->where('case_id', $request->case_id)
        //         ->first();
        $task = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $request->case_id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        $firmadmin = User::select('users.id as uid')
                    ->join('firms', 'firms.email', '=', 'users.email')
                    ->where('users.firm_id', $case->firm_id)
                    ->first();
        $msg = 'Payment for Additional Services, successful!';
        $touser = User::where('id', $firmadmin->uid)->first();
        $n_link = url('firm/case/additional_service').'/'.$request->case_id;
        $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));

        if(!empty($task)) {
            $touser = User::where('id', $task->allot_user_id)->first();
            $n_link = url('admin/usertask/additional_service').'/'.$task->id;
            if($touser->role_id == '1') {
                $n_link = url('admin/allcases/additionalservice').'/'.$request->case_id;
            }
            
            $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
        }
        $casecost4 = $casecost1/100;
        $case->case_cost = $casecost4;
        //pre($case);
        app('App\Http\Controllers\HomeController')->DEVQBInvoiceCreation($case);
        return redirect('firm/case/additional_service/'.$request->case_id)->with('success','Payment for Additional Services, successful!');
    }

    public function add_family_incase(Request $request) {
        if($request->family_id) {
            // foreach ($request->family_id as $k => $v) {
                update_user_meta($request->family_id, 'CaseID', $request->case_id, 1);
                update_user_meta($request->family_id, 'beneficiary', $request->case_id, 1);
            // }
        }
    }

    public function add_family_member_incase(Request $request) {
        update_user_meta($request->family_id, 'CaseID', $request->case_id, 1);
        $kk = 'memberof_'.$request->case_id;
        $vv = array(
                'memberof' => $request->member_of,
                'relationship' => $request->member_relationship
                );
        update_user_meta($request->family_id, $kk, json_encode($vv));
    }

    public function add_derivative_incase(Request $request) {
        if($request->checked) {
            update_user_meta($request->family_id, $request->type, $request->case_id, 1);
        }
        else {
            $uc = DB::table("usermeta")->where("user_id", $request->family_id)->where("meta_key", $request->type)->where("meta_value", $request->case_id)->delete();
        }
    }
    public function create_derivative_incase(Request $request) {
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
        if(empty($request->email)) {
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required|string',
                        // 'email' => 'string|email|unique:users|unique:new_client',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required|string',
                        'email' => 'string|email|unique:users|unique:new_client',
            ]);
        }
        $cl_email = $request->email;
        if(empty($request->email)) {
            $cl_email = 'dummy'.time().'@tilacaseprep.com';
        }
        $record['email'] = $cl_email;
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        app('App\Http\Controllers\HomeController')->CreateClientFamily($record,$current_firm_id,$request->case_id);

        $record['dob'] = date('Y-m-d', strtotime($record['dob']));
        $check = ClientFamily::create($record);

        if ($check) {
            return redirect('firm/case/case_family/' . $request->case_id)->with('success', 'Family created successfully!');
        } else {
            return redirect('firm/case/case_family/' . $request->case_id)->with('error', 'Family not created, please try again');
        }
    }
    public function upload_affidavit_documents(Request $request)
    {
        $data = Auth::User();
        // pre($request->all());
        $firm_id = Auth::User()->firm_id;
        foreach ($request->file as $key => $file) {
            $f = Storage::put('client_doc', $file);
            $data = [
            'uploaded_by' => $data->id,
            'index' => $request->service_index,
            'case_id' => $request->case_id,
            'document' => $f,
            ];
            $doc = AffidavitDocumentRequest::select('*')
                    ->where('index', $request->index)
                    ->where('case_id', $request->case_id)
                    ->first();
            if(!empty($doc)) {
                AffidavitDocumentRequest::where('id', $doc->id)->update($data);
            }
            else {
                AffidavitDocumentRequest::create($data);
            }
        }
        return redirect('firm/case/affidavit/'.$request->case_id)->with('success','Document upload successfully!');
    }

    public function edit_family($id, $fid) {
        $firm_id = Auth::User()->firm_id;
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $family_arr = array(); 
          foreach ($family_list as $key => $value) { 
            $family_arr[] = $value->uid;
          }


        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        if($case->client_id) {
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }
        $countries = DB::table("countries")->get();

        $FamilyMember = get_user_meta($fid, 'FamilyMember');
        $FamilyMember = json_decode($FamilyMember);
        $r_states = array();
        if (!empty($FamilyMember->residence_address->country)) {
            $r_states = DB::table("regions")
                    ->where("country_id", $FamilyMember->residence_address->country)
                    ->pluck("name", "id");
        }
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        return view('firmadmin.case.edit_family', compact('id', 'firm', 'case', 'client', 'family_alllist', 'family_arr', 'countries', 'fid', 'FamilyMember', 'r_states', 'admintask'));
    }

    public function case_inbox($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $admintask = array();
        if($case->VP_Assistance == 1) {
            $admintask = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();
        }
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();

        $msg = array();
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        // ->where('text_message.msgfrom', Auth::User()->id)
        ->where('text_message.msgto', $client->user_id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 

        foreach ($messages as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }

        $messages1 = TextMessage::select('text_message.*', 'u1.name as username')
        ->where('text_message.msgfrom', $client->user_id)
        // ->where('text_message.msgto', Auth::User()->id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 
        foreach ($messages1 as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }
        return view('firmadmin.case.case_inbox', compact('case', 'firm', 'msg', 'admintask', 'client'));
    }

    public function rquest_blueprint_documents(Request $request) {
        $data1 = Auth::User();
        $case = FirmCase::select('new_client.id as cid')
                ->where('case.id', $request->case_id)
                ->join('new_client', 'new_client.user_id', 'case.client_id')
                ->first();
        foreach ($request->family_id as $k => $v) {
            $data =  [
                'client_id' => $case->cid,
                'family_id' => $v,
                'case_id' => $request->case_id,
                'requested_by' => $data1->id,
                'document_type' => $request->doc,
                'expiration_date' => $request->duedate,
                'status' => 0
            ];
            DocumentRequest::create($data);
        }
    }

    function requestadditionalservice(Request $request) {
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('users.id',$case->client_id)
        // ->where('users.role_id',6)
        ->first();
        $as = json_decode($case->additional_service);
        $servicelist = '';
        if($request->nvc_packet_quantity_new) {
            $as->nvc_packet_quantity_new = $request->nvc_packet_quantity_new;
            $servicelist .= 'DS-260 for Additional Derivative Beneficiary (online only)<br>';
        }

        if($request->additional_service_new && !in_array(99, $request->additional_service_new) && !in_array(100, $request->additional_service_new)) {
            $as->additional_service_new = $request->additional_service_new;
            foreach ($request->additional_service_new as $k => $v) {
                $servicelist .= $v.'<br>';
            }
        }

        if($request->declaration_new) {
            if(empty($as->declaration)) {
                $as->declaration = new \stdClass();
                $as->declaration->status = 1;
            }
            $as->declaration->declaration_new = $request->declaration_new;
            $as->declaration->declaration_other_new = $request->declaration_other_new;
            foreach ($request->declaration_new as $k => $v) {
                $servicelist .= $v.'<br>';
            }
        }

        $task_data = [
                    'firm_admin_id' => Auth::User()->id,
                    'task_type' => 'Additional_Service',
                    'task' => 'Additional Service Requested',
                    'case_id' => $request->case_id,
                    'allot_user_id' => Auth::User()->id,
                    'status' => 0
                ];
        AdminTask::create($task_data);

        FirmCase::where('id', $request->case_id)->update(['additional_service' => json_encode($as)]);
        $admintask = AdminTask::select('admintask.id as tid','admintask.allot_user_id', 'users.*')
                    ->where('admintask.task_type', 'Assign_Case')
                    ->where('admintask.case_id', $request->case_id)
                    ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                    ->first();
        if(!empty($admintask)) {
            $remove = array(
                        'TILAVPName' => $admintask->name,
                        'FirmName' => Auth::User()->name,
                        'ClientName' => $client->name,
                        'CaseType' => $case->case_category,
                        'CaseCategory' => $case->case_type,
                        'listService' => $servicelist
                    );
            $email = EmailTemplate(36, $remove);
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $admintask->email,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);

            $msg = 'Firm ' . Auth::User()->name . ' an additional service has been added!';
            $touser = User::where('id', $admintask->id)->first();
            if($admintask->role_id == 1) {
                $n_link = url('admin/allcases/additionalservice').'/'.$request->case_id;
            }
            else {
                $n_link = url('admin/usertask/additional_service').'/'.$admintask->tid;
            }
            
            $message = collect(['title' => 'Additional Service', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
        }
        return redirect('firm/case/additional_service/'.$request->case_id)->with('success','Additional Service Requested successfully!');
    }
}
