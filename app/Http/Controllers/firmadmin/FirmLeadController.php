<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Firm;
use App\Models\Esubscription;
use App\Models\Event;
use App\Models\Newclient;
use App\Models\FirmSetting;
use App\Models\Country;
use App\Models\LeadNotes;
use App\Models\ClientDocument;
use App\Models\QBInvoice;
use App\Models\SchedulePayment;
use App\Models\Transaction;
use App\Models\UserMeta;
use Carbon\Carbon;
use App\Notifications\DatabaseNotification;
use Notification;
use App;
use DB;
use QuickBooksOnline\API\Exception\ServiceException;
/* --------------QuickBook--------------- */
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
/* --------------QuickBook--------------- */

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class FirmLeadController extends Controller {

    public function __construct() {
        require_once(base_path('public/QuickBook/gettoken.php'));
        require_once(base_path('public/calenderApi/settings.php'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $data = Auth::User();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
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

        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
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
        $users = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
        return view('firmadmin.lead.index', compact('firm', 'card', 'data', 'users'));
    }
    
    
    public function emailtemplatesshow(){
        $remove['user_first_name']='Ankit';
        $remove['attorney_first_name']='Ankit Saxena';
        
        $email=EmailTemplate(1,$remove);
        
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => 'snvankit@gmail.com',
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        
        
        
        echo $email['Subject']; 
        echo $email['MSG']; 
        die;
        return view('firmadmin.case.index');
    }

    /* Show Lead Table Data Ajax */
    public function getData() {
        $data = Auth::User();
        $lead = Lead::select('lead.*')->where('firm_id', $data->firm_id)->where('status', '!=', 2)->get();

        foreach ($lead as $key => $value) {
            switch ($value->status) {
                case 0:
                    $lead[$key]->status = "Closed - Lost";
                    break;
                case 1:
                    if (strtotime($value->updated_at) < strtotime('-60 days')) {
                        $lead[$key]->status = "Aging";
                    } else {
                        $lead[$key]->status = "Active";
                    }
                    break;
                case 2:
                    $lead[$key]->status = "Converted to Client";
                    break;
            }
            $ccode = json_decode($value->birth_address);
            if ($ccode->country) {
                $iisd = Country::select('ISD')->where('id', $ccode->country)->first();
                $lead[$key]->cell_phone = '+' . $iisd->ISD . ' ' . $value->cell_phone;
            }
            $event = Event::select('event.*')->where('related_id', $value->id)->where('title', "LEAD")->orderBy('id', 'DESC')->first();
            if (!empty($event)) {
                $lead[$key]->event = Carbon::parse($event->e_date)->format('m-d-Y') . " " . Carbon::parse($event->e_time)->format('g:i a');
                $lead[$key]->oldevent = strtotime($event->e_date);
            } else {
                $lead[$key]->event = "";
            }
            $lead[$key]->name = $value->name.' '.$value->last_name;
            $lead[$key]->todaytime = strtotime('now');
            $lead[$key]->created = Carbon::parse($value->created_at)->format('m-d-Y g:i a');
        }

        return datatables()->of($lead)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        // $countries = DB::table("countries")->get();
        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);
        return view('firmadmin.lead.create', compact('countries'));
    }

    public function create_lead(Request $request) {

        $file_arr1 = array();
        $file_arr1 = $request->document_path;

        $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'last_name' => 'required|string'
                        //'email' => 'string|email|unique:users|unique:lead'       
        ]);

        /* if($validator->fails()){

          return redirect('firm/lead')->withInfo('Mendatory fields are required!');
          } */

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $record = $request->all();

        $requestData = $request->all();
        $requestData['dob'] = $request->dob;
        $requestData['document_path'] = '';
        $requestData['birth_address'] = json_encode($request->birth_address1);
        $firm_id = Auth::User()->firm_id;
        $lead = Lead::create($requestData);
        Lead::where('id', $lead->id)->update(['firm_id' => $firm_id]);

        /* Document image upload start */
        if (!empty($file_arr1)) {
            $file_arr = array();
            // foreach ($file_arr1 as $k => $v) {
            //     $file_arr[] = Storage::put('lead_doc', $v);
            // }
            foreach ($file_arr1 as $k => $v) {
                $file = $v->getClientOriginalName();
                $fname = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename  = $fname.time().'.'. $extension;
                $file_arr[] = $v->storeAs('lead_doc', $filename);
            }
            if ($file_arr) {
                $file_arr2 = json_encode($file_arr);
                Lead::where('id', $lead->id)->update(['document_path' => $file_arr2]);
            }
        }
        /* Document image upload close */

        if ($request->lead_note) {
            $data2 = [
                'lead_id' => $lead->id,
                'notes' => $request->lead_note,
                'created_by' => Auth::User()->id
            ];

            $note = LeadNotes::create($data2);
        }
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();

        $log_data = [
            'title' => "LEAD_CREATE",
            'related_id' => $lead->id,
            'message' => $firm_name->firm_name . " created a new lead " . $lead->name
        ];

        Log::create($log_data);

        if (!empty($request->email)) {

            $validator = Validator::make($request->all(), [
                        'email' => 'string|email|unique:esubscription'
            ]);

            if (!$validator->fails()) {

                $esubscription_data = [
                    'name' => $request->name,
                    'email' => $request->email
                ];
                Esubscription::create($esubscription_data);
            }
        }


        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a Lead " . $lead->name
        ];
        Log::create($logdata);

        /*--------------------Notifications---------------*/        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ' admin create a Lead ' . $lead->name,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $lead->id;
        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ' admin create a Lead' ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
         
        /*--------------------Notifications---------------*/  
        if (isset($request->create_firm_lead_event)) {

            return redirect('firm/lead/create_event/' . $lead->id);
        }

        if ($lead) {
            return redirect('firm/lead')->with('success', 'Successful');
        } else {
            return redirect('firm/lead')->with('error', 'Lead not created, please try again');
        }

        /* if ($lead_profile_id->id) {
          return redirect('firm/lead')->withInfo('Firm lead created successfully!');
          }else{
          return redirect('firm/lead')->withInfo('lead not created, please try again');
          } */
    }

    public function view_event($id) {

        /* echo $id;
          die();
         */
        $lead_id = $id;
        $data = Auth::User();
        $user_id = $data->id;
        $user = User::select('id', 'name')
                ->where('firm_id', $data->firm_id)
                ->where('role_id', '!=', 6)
                ->get();
        $lead = Lead::where('id', $id)->first();
        return view('firmadmin.lead.event', compact('lead_id', 'user', 'user_id', 'lead'));
    }

    public function edit($id) {
        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);
        $lead = Lead::where('id', $id)->first();
        $states = array();
        $lead->birth_address = json_decode($lead->birth_address);
        if($lead->birth_address->country) {
            $states = DB::table("regions")
                    ->where("country_id",$lead->birth_address->country)
                    ->pluck("name","id");
        }
        return view('firmadmin.lead.edit', compact('lead', 'countries', 'states'));
    }

    public function update_lead(Request $request) {


        $validator = Validator::make($request->all(), [
                    'name' => 'required|string',
                    'last_name' => 'required|string'
                        // 'email' => 'string|email|unique:users|unique:lead'       
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $file_arr1 = array();
        $file_arr1 = $request->document_path1;
        $file_arr2 = $request->document_path;
        
        /* Document image upload start */
        if (!empty($file_arr2)) {
            // foreach ($file_arr2 as $k => $v) {
            //     $file_arr1[] = Storage::put('lead_doc', $v);
            // }
            foreach ($file_arr2 as $k => $v) {
                $file = $v->getClientOriginalName();
                $fname = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $filename  = $fname.time().'.'. $extension;
                $file_arr1[] = $v->storeAs('lead_doc', $filename);
            }
            if ($file_arr1) {
                $file_arr = json_encode($file_arr1);
                Lead::where('id', $request->lead_id)->update(['document_path' => $file_arr]);
            }
        }

        /* Document image upload close */

        $data = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'cell_phone' => $request->cell_phone,
            'home_phone' => $request->home_phone,
            'is_deported' => $request->is_deported,
            'is_detained' => $request->is_detained,
            'dob' => $request->dob,
            'language' => $request->language,
            'Current_address' => $request->Current_address,
            'lead_note' => $request->lead_note,
            'birth_address' => json_encode($request->birth_address1)
        ];

        Lead::where('id', $request->lead_id)->update($data);


//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin update a Lead ' . $request->name . ' ' . $request->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));

        /*--------------------Notifications---------------*/    
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$request->name.' '.$request->last_name.' Updated ';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Lead Updated', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $request->lead_id;
        $message = collect(['title' => 'Firm Lead Updated', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
         
        /*--------------------Notifications---------------*/  
        
        
        
        
        
        
        return redirect('firm/lead/show/'.$request->lead_id)->with('success', 'Firm lead update successfully!');
    }

    public function show($id) {

        $lead = Lead::where('id', $id)->first();
        $event = Event::select('event.*')
                ->where('related_id', $lead->id)
                ->where('title', "LEAD")
                ->first();
        $who_consult_with = array();
        if(!empty($event)) {
            $who_consult_with = json_decode($event->who_consult_with);
        }
        
        $attorney_users = '';
        if(!empty($who_consult_with)) {
            foreach ($who_consult_with as $k => $v) {
                $user = User::select('name')->where('id', $v)->first();
                if($k) {
                    $attorney_users .= ', ';
                }
                $attorney_users .= $user->name;
            }
        }

        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);
        $states = array();
        $lead->birth_address = json_decode($lead->birth_address);
        if($lead->birth_address->country) {
            $states = DB::table("regions")
                    ->where("country_id",$lead->birth_address->country)
                    ->pluck("name","id");
        }
        return view('firmadmin.lead.show', compact('lead', 'event', 'attorney_users', 'countries', 'states'));
    }

    public function billing($id) {
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('invoice_for', 'LEAD')->where('lead_id', $id)->where('status', '!=', 3)->orderBy('id', 'DESC')->get();
        $scheduled = SchedulePayment::select('*')->where('schedule_for', 'LEAD')->where('related_id', $id)->where('status', '!=', 3)->orderBy('id', 'DESC')->get();

        $count = array();
        $count['total_amount'] = QBInvoice::select('*')->where('invoice_for', 'LEAD')->where('lead_id', $id)->where('status', '!=', 3)->sum('amount');
        $count['paid_amount'] = QBInvoice::select('*')->where('invoice_for', 'LEAD')->where('lead_id', $id)->where('status', '=', 1)->sum('paid_amount');
        $count['outstanding_amount'] = $count['total_amount'] - $count['paid_amount'];

        if($count['total_amount']) {
            $count['paid_percent'] = intval(($count['paid_amount']/$count['total_amount'])*100);
        }
        else {
            $count['paid_percent'] = 0;
        }
        
        return view('firmadmin.lead.billing', compact('lead', 'invoice', 'scheduled', 'count'));
    }

    public function invoice($id) {
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid')
                    ->where('qb_invoice.invoice_for', 'LEAD')
                    ->where('qb_invoice.lead_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->leftJoin('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        return view('firmadmin.lead.invoice', compact('lead', 'invoice'));
    }

    public function add_invoice($id) {
        $lead = Lead::where('id', $id)->first();
        return view('firmadmin.lead.add_invoice', compact('lead'));
    }

    public function create_lead_invoice(Request $request) {
        $data = Auth::User();
        $idata = [
            'user_id' => 0,
            'firm_id' => $data->firm_id,
            'lead_id' => $request->lead_id,
            'client_name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'destination_account' => $request->destination_account,
            'comment' => $request->comments,
            'amount' => $request->total_amount,
            'invoice_for' => 'LEAD',
            'Customer_ID' => 0,
            'invoice_id' => 0,
            'status' => 0,
        ];
        $qbinvoice = QBInvoice::create($idata);
        return redirect('firm/lead/acceptpayment/'.$request->lead_id.'/'.$qbinvoice->id)->with('success', 'Invoice created!');
    }

    public function acceptpayment($id, $id1 = 0) {
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('invoice_for', 'LEAD')->where('lead_id', $id)->where('status', '!=', 3)->get();
        $qbinvoice = array();
        if($id1) {
            $qbinvoice = QBInvoice::select('*')
            ->where('id',$id1)
            ->first();
        }
        return view('firmadmin.lead.acceptpayment', compact('lead', 'qbinvoice', 'invoice', 'id1'));
    }

    public function scheduled($id, $id1 = 0) {
        $data = Auth::User();
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('invoice_for', 'LEAD')->where('lead_id', $id)->where('status', '!=', 3)->get();
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
        curl_setopt($ch, CURLOPT_URL, 'https://api.chargeio.com/v1/cards?reference=Lead'.$id);
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
        return view('firmadmin.lead.scheduled', compact('lead', 'qbinvoice', 'id1', 'invoice', 'cards'));
    }

    public function SchedulePayment(Request $request) {
        $data = Auth::User();
        if(empty($request->is_schedule)) {
            if(!empty($request->save_view)) {
                return redirect('firm/lead/view_invoice/'.$request->lead_id.'/'.$request->id);
            }
            else {
                return redirect('firm/lead/invoice/'.$request->lead_id);
            }
            // return redirect('firm/client/client_invoice/'.$request->client_id);
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
                $array1= array(
                        "account_id"=>"$account_id",
                        "number"=>$_REQUEST['card_number'],
                        "exp_month"=>$_REQUEST['exp_month'] , 
                        "exp_year"=> $_REQUEST['exp_year'] ,
                        // "cvv"=>$_REQUEST['cvc'],
                        // "zipcode"=>$_REQUEST['address_zip'],
                        "name"=>$request->name_of_credit_card, 
                        "description" => "Corporate VISA",
                        "reference" => "Lead".$request->lead_id 
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
                $credit_card = $card['id'];
            }
            else {
                $credit_card = $request->credit_card;
            }
            $idata = [
                'recurring_amount' => $request->recurring_amount,
                'first_payment' => $request->first_payment,
                'payment_cycle' => $request->payment_cycle,
                'credit_card' => $credit_card,
                'invoice_id' => $request->id,
                'schedule_for' => 'LEAD',
                'related_id' => $request->lead_id,
            ];
            $qbinvoice = SchedulePayment::create($idata);
            if(!empty($request->save_view)) {
                return redirect('firm/lead/view_invoice/'.$request->lead_id.'/'.$request->id)->with('success', 'Schedule payment successfully!');
            }
            else {
                return redirect('firm/lead/invoice/'.$request->lead_id)->with('success', 'Schedule payment successfully!');
            }
            // return redirect('firm/lead/invoice/'.$request->lead_id)->with('success', 'Schedule payment successfully!');
        }
        else {
            return redirect('firm/lead/invoice/'.$request->lead_id);
        }
        
    }

    public function schedule_history($id) {
        $data = Auth::User();
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.id as sid',  'schedule_payment.*')
                    ->where('qb_invoice.invoice_for', 'LEAD')
                    ->where('qb_invoice.lead_id', $id)
                    ->where('qb_invoice.status', '!=', 3)
                    ->join('schedule_payment', 'schedule_payment.invoice_id', 'qb_invoice.id')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->get();
        return view('firmadmin.lead.schedule_history', compact('lead', 'invoice'));
    }

    public function view_invoice($id, $id1) {
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('id', $id1)->first();
        $transaction = Transaction::select('*')->where('type', 'Invoice')->where('related_id', $id1)->get();
        $firm = Firm::select('firms.*', 'users.id as uid', 'users.*')
                ->where('firms.id', $invoice->firm_id)
                ->join('users', 'users.email', '=', 'firms.email')
                ->first(); 
        return view('firmadmin.lead.view_invoice', compact('lead', 'invoice', 'transaction', 'firm'));
    }

    public function edit_invoice($id, $id1) {
        $lead = Lead::where('id', $id)->first();
        $invoice = QBInvoice::select('*')->where('id', $id1)->first();
        return view('firmadmin.lead.edit_invoice', compact('lead', 'invoice'));
    }

    public function update_lead_invoice(Request $request) {
        $data = Auth::User();
        $idata = [
            'client_name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'destination_account' => $request->destination_account,
            'comment' => $request->comments,
            'amount' => $request->total_amount,
        ];
        $qbinvoice = QBInvoice::where('id', $request->invoice_id)->update($idata);
        return redirect('firm/lead/invoice/'.$request->lead_id)->with('success', 'Invoice update successfully!');
    }

    public function cancel_invoice($id, $cid) {
        QBInvoice::where('id', $cid)->update(['status' => 3]);
        return redirect('firm/lead/invoice/'.$id)->with('success','Invoice cancel successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) {
        $lead_record = Lead::select('name', 'last_name')->where('id', $id)->first();
        Lead::where('id', $id)->delete();

//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin delete a Lead ' . $lead_record->name . ' ' . $lead_record->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
        /*--------------------Notifications---------------*/    
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead Deleted';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Lead Deleted', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead');
        $message = collect(['title' => 'Firm Lead Deleted', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
         
        /*--------------------Notifications---------------*/  

        return redirect('firm/lead')->with('success', 'Firm lead deleted successfully!');
    }

    public function lost($id) {

        $lead_record = Lead::select('name', 'last_name')->where('id', $id)->first();
        
        
        
//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Lost a Lead ' . $lead_record->name . ' ' . $lead_record->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
        
        /*--------------------Notifications---------------*/    
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead_record->name.' '.$lead_record->last_name.' Lost';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Lead Deleted', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead');
        $message = collect(['title' => 'Firm Lead Deleted', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
         
        /*--------------------Notifications---------------*/
        
        Lead::where('id', $id)->update(['status' => 0]);
        return redirect('firm/lead')->with('info', 'Firm Lead Lost');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                ->where('title', "LEAD")
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
                ->where('title', "LEAD")
                ->orderBy('id', 'DESC')
                ->get();
        $events = array();
        //$dateandtime=array('dates'=>'','event_title'=>'');
        $dateandtime = array('s_date' => '', 'e_date' => '','event_title' => '', 'coutner' => 0, 'event_type' => '', 'event_description' => '', 'who_consult_with' => array() );
        if ($arr) {
            foreach ($arr as $key => $e) {
                $select = 0;
                $wcw = json_decode($arr1->who_consult_with);
                if (isset($_REQUEST['reschedule']) && $e->related_id == $id) {
                    $edates = $e->s_date . ' ' . $e->s_time . ' - ' . $e->e_date . ' ' . $e->e_time;
                    //$dateandtime=array('dates'=>$edates,'event_title'=>$e->event_title); 
                    $dateandtime = array(
                        's_date' => date('m/d/Y', strtotime($e->s_date)) . ' ' . $e->s_time, 
                        'e_date' => date('m/d/Y', strtotime($e->e_date)) . ' ' . $e->e_time,
                        'event_title' => $e->event_title, 
                        'coutner' => $e->coutner, 
                        'event_type' => $e->event_type,
                        'who_consult_with' => json_decode($e->who_consult_with),
                        'event_description' => $e->event_description,
                    );
                }
                $s_time  = date("H:i:s", strtotime($e->s_time));
                $e_time  = date("H:i:s", strtotime($e->e_time));

                $eedate = '';
                if(!empty($e->e_date)) {
                    $eedate = date('m/d/Y', strtotime($e->e_date));
                }
                $events[] = array(
                    'event_id' => $e->id,
                    'title' => $e->event_title, 
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

        $lead = Lead::select('name', 'last_name')->where('id', $id)->first();

//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin update a Lead ' . $lead->name . ' ' . $lead->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
        //pre($dateandtime);die;
        /*--------------------Notifications---------------*/    
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead->name.' '.$lead->last_name.' Created Event'.$dateandtime['s_date'];
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Created Event', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $id;
        $message = collect(['title' => 'Firm Admin Created Event', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
         
        /*--------------------Notifications---------------*/
        
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
        return view('firmadmin.lead.create_event', compact('id', 'users', 'events', 'access_token', 'dateandtime', 'user_id', 'lead', 'authUrl'));
    }

    public function create_lead_event(Request $request) {
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
        $res = array();
        $reminder_arr = array();
        $reminders = array(
                'useDefault' => FALSE,
                'overrides' => array()
              );
        if($request->event_reminder) {
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
        $s_date = $request->s_date;
        $e_date = $request->e_date;
        if($request->event_type == 'Reminder') {
            $lead_event_data = [
                'title' => "LEAD",
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
        }
        else {
            $lead_event_data = [
                'title' => "LEAD",
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
            $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
            $firm_id = Auth::User()->firm_id;
            $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
            
            $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Reschedule a Lead ' . $lead->name . ' ' . $lead->last_name]);
            if($request->who_consult_with) {
                foreach ($request->who_consult_with as $k => $v) {
                    $touser = User::where('id', $v)->first();
                    Notification::send($touser, new DatabaseNotification($message));
                }
            }
            
        } else {
            $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
            $firm_id = Auth::User()->firm_id;
            $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
            $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Reschedule a Lead ' . $lead->name . ' ' . $lead->last_name]);
            if($request->who_consult_with) {
                foreach ($request->who_consult_with as $k => $v) {
                    $touser = User::where('id', $v)->first();
                    Notification::send($touser, new DatabaseNotification($message));
                }
            }
            // $event = Event::create($lead_event_data);
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

            return redirect('firm/lead/show/'.$request->lead_id)->with('success', 'Firm Lead Create and Schedule Consult successfully!');
        }


        $res['status'] = true;
        $res['msg'] = 'Lead Event created successfully!';
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
            $gid = CreateCalendarEvent('primary', $request->event_title, 0, $etime, $user_timezone, $access_token, $reminders);
            Event::where('id', $event_id1)->update(['google_id' => $gid]);
        }
        
        
        /*--------------------Notifications---------------*/ 
        $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead->name.' '.$lead->last_name.' Created Event';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Created Event', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $request->lead_id;
        $message = collect(['title' => 'Firm Admin Created Event', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/
       die();
        if ($event) {
            //return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        } else {
            //return redirect('firm/create_event')->withInfo(' not created, please try again');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_client($id) {

        $lead = Lead::where('id', $id)->first();
        $pass = str_random(8);
        $data = [
            'name' => $lead->name . " " . $lead->last_name,
            'role' => 6,
            'email' => $lead->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];

        $user = User::create($data);

        $data1 = [
            'first_name' => $lead->name,
            'last_name' => $lead->last_name,
            'user_id' => $user->id,
            'email' => $lead->email,
            'cell_phone' => $lead->cell_phone,
            'dob' => $lead->dob,
            'language' => $lead->language
        ];
        $newclient = Newclient::create($data1);

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Convert to client a Lead ' . $lead->name . ' ' . $lead->last_name]);
        // Notification::send($touser, new DatabaseNotification($message));
        // Newclient::where('id', $newclient->id)->update(['user_id' => $user->id, 'image_path' => $request->image_path]);


        if (true) {

            $current_firm_id = Auth::User()->firm_id;

            $message = FirmSetting::where('title', 'Welcome Client')->where('category', 'EMAIL')->where('firm_id', $current_firm_id)->first();

            $username = $lead->name . ' ' . $lead->last_name;
            $useremail = $lead->email;
            $pass = $pass;

            $msg = "Hi, $username.<br>";
            $msg .= "Email : " . $useremail . " <br>";
            $msg .= "Password : " . $pass . " <br>";
            $msg .= $message->message . "<br>";
            $msg = EmptyEmailTemplate($msg);
            $args = array(
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => 'Welcome to TILA Case Prep',
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
        }

        Lead::where('id', $id)->update(['status' => 2]);

        if ($data) {
            return redirect('firm/client')->with('success', 'Firm client created successfully!');
        } else {
            return redirect('firm/client')->with('success', 'client not created, please try again');
        }
        // return view('firmadmin.lead.create_client',compact('lead','id'));
    }

    function QBCreateClient($saveddata) {
                
            $data = Auth::User();
            if ($data->QBConnect == 1) {
                $config = require_once(base_path('public/QuickBook/conf.php'));
                // $config = require_once('/var/www/tila/public/QuickBook/config.php');

                $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $config['client_id'],
                    'ClientSecret' => $config['client_secret'],
                    'RedirectURI' => $config['oauth_redirect_uri'],
                    'scope' => $config['oauth_scope'],
                    'baseUrl' => "https://quickbooks.api.intuit.com"
                ));

                $accessToken = json_decode($data->QBToken);


                $oauth2LoginHelper = new OAuth2LoginHelper($accessToken->getclientID, $accessToken->getClientSecret);
                try{
                $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($accessToken->getRefreshToken);
                }   catch (ServiceException  $e) {
                        return 0;
                    }
                $newAccessTokenObj->setRealmID($accessToken->getRealmID);
                $newAccessTokenObj->setBaseURL($accessToken->getBaseURL);
                $accessToken = $newAccessTokenObj;

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $dataService->throwExceptionOnError(true);
                /*
                 * Update the OAuth2Token of the dataService object
                 */

                $dataService->updateOAuth2Token($newAccessTokenObj);
                $theResourceObj = Customer::create([
                            "BillAddr" => [
                                "Line1" => $saveddata['address'],
                                "City" => "",
                                "Country" => "",
                                "CountrySubDivisionCode" => "",
                                "PostalCode" => ""
                            ],
                            "Notes" => $saveddata['notes'],
                            "Title" => "Mr",
                            "GivenName" => $saveddata['name'],
                            "MiddleName" => "",
                            "FamilyName" => $saveddata['name'],
                            "Suffix" => "",
                            "FullyQualifiedName" => $saveddata['name'],
                            "CompanyName" => $saveddata['name'],
                            "DisplayName" => $saveddata['name'],
                            "PrimaryPhone" => [
                                "FreeFormNumber" => $saveddata['phone']
                            ],
                            "PrimaryEmailAddr" => [
                                "Address" => $saveddata['email']
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
                    return $resultingObj->Id;
                }
            }
       
    }

    public function convert_client(Request $request) {
        $cl_email = $request->email;
        if(empty($request->email)) {
            $cl_email = 'dummy'.time().'@tilacaseprep.com';
        }
        // pre($request->all());
        // die();
        if ($request->is_portal_access) {
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    // 'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }
        

        if ($validator->fails()) {
          return redirect('firm/lead/edit/'.$request->lead_id)->withInfo($validator->errors()->first());
        } 

        $user_id = 0;
        $QBCustomer = 0;
        $portal_access = 0;
        if ($request->is_portal_access) {
            $portal_access = 1;
            
        }
        $pass = str_random(8);
        $data = [
            'name' => $request->first_name . " " . $request->last_name,
            'role' => 6,
            'email' => $cl_email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 6,
            'firm_id' => Auth::User()->firm_id
        ];
        $newd = $data;
        $newd['LeadID'] = $request->lead_id;
        $newd['notes'] = $request->Note;
        $newd['phone'] = $request->phone;
        $newd['address'] = $request->add;
        $QBCustomer = $this->QBCreateClient($newd);
        $user = User::create($data);
        $user_id = $user->id;
        Lead::where('id', $request->lead_id)->update(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'status' => 2]);

        $lead = Lead::where('id', $request->lead_id)->first();
        $lead_addr = json_decode($lead->birth_address);
        $lead_addr->address = $request->add;
        $data1 = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_id' => $user_id,
            'email' => $cl_email,
            'cell_phone' => $request->cell_phone,
            'dob' => $request->dob,
            'language' => $request->language,
            'firm_id' => Auth::User()->firm_id,
            'is_detained' => $lead->is_detained,
            'is_deported' => $lead->is_deported,
            'lead_id' => $request->lead_id,
            'birth_address' => $request->birth_address,
            'QBCustomerID' => $QBCustomer,
            'is_portal_access' => $portal_access,
            'residence_address' => json_encode($request->residence_address)
        ];

        $newclient = Newclient::create($data1);

        Newclient::where('id', $newclient->id)->update(['user_id' => $user_id]);
        QBInvoice::where('invoice_for', 'LEAD')->where('lead_id', $request->lead_id)->update(['invoice_for' => 'CLIENT', 'client_id' => $newclient->id]);
        if($lead->document_path) {
          $document_path = json_decode($lead->document_path);
          foreach ($document_path as $k => $v) {
            $cdoc = [
                'client_id' => $newclient->id,
                'uploaded_by' => Auth::User()->id,
                'document' => $v,
                'title' => 'Lead Document',
                'description' => 'Lead Document'
            ];
            ClientDocument::create($cdoc);
          }
        }
//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Convert to client a Lead ' . $lead->name . ' ' . $lead->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
        
        /*--------------------Notifications---------------*/ 
        $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead->name.' '.$lead->last_name.' Convert to Client successfully!';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Lead Convert To Client', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/client/show') . '/' . $newclient->id;
        $message = collect(['title' => 'Firm Admin Lead Convert To Client', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/
        
        
        

        if ($request->is_portal_access) {
            $current_firm_id = Auth::User()->firm_id;
            $message = FirmSetting::where('title', 'Welcome Client')->where('category', 'EMAIL')->where('firm_id', $current_firm_id)->first();
            $username = $request->first_name . " " . $request->last_name;
            $useremail = $request->email;
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
        }


        return redirect('firm/client/show/'.$newclient->id)->with('success', 'Firm client created successfully!');
    }

    public function undo_lead($id) {
        $lead = Lead::where('id', $id)->first();
        
        $client = Newclient::select('*')->where('lead_id', $id)->first();
        $email = $client->email;

        if ($email) {
            UserMeta::where('user_id', $client->user_id)->delete();
            QBInvoice::where('client_id', $client->id)->delete();
            $user = User::where('id', $client->user_id)->delete();
        }
        
        Newclient::where('lead_id', $id)->delete();

        Lead::where('id', $id)->update(['status' => 1]);

//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin Undo lead to client' . $lead->name . ' ' . $lead->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
//        
        /*--------------------Notifications---------------*/ 
        $lead = Lead::select('name', 'last_name')->where('id', $id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead->name.' '.$lead->last_name.' Undo Client to Lead successfully!';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Lead Convert To Client', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $id;
        $message = collect(['title' => 'Firm Admin Lead Convert To Client', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/
        
        
        
        
        
        return redirect('firm/lead')->with('success', 'Undo Client to Lead successfully!');
    }

    public function notes($id) {
        $lead_notes = LeadNotes::select('lead_notes.*', 'users.name as username')
                ->join('users', 'lead_notes.created_by', 'users.id')
                ->where('lead_notes.lead_id', $id)
                ->orderBy('lead_notes.id', 'DESC')
                ->get();

        $lead = Lead::select('name', 'last_name')->where('id', $id)->first();

        return view('firmadmin.lead.notes', compact('lead_notes', 'id', 'lead'));
    }

    public function create_lead_note(Request $request) {
        $res = array();
        $validator = Validator::make($request->all(), [
                    'note' => 'required|string',
        ]);
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = 'Mendatory fields are required!';
            echo json_encode($res);
            die();
        }
        $data = [
            'lead_id' => $request->lead_id,
            'notes' => $request->note,
            'created_by' => Auth::User()->id
        ];

        $note = LeadNotes::create($data);
        Lead::where('id', $request->lead_id)->update(array('lead_note' => $request->note));
        $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
//        $firm_id = Auth::User()->firm_id;
//        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
//        $touser = User::where('id', 1)->first();
//        $message = collect(['title' => 'Firm Lead Create', 'body' => 'Firm ' . $firm_name->firm_name . ',firm admin create a note for lead ' . $lead->name . ' ' . $lead->last_name]);
//        Notification::send($touser, new DatabaseNotification($message));
        
        /*--------------------Notifications---------------*/ 
        $lead = Lead::select('name', 'last_name')->where('id', $request->lead_id)->first();
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' admin Lead '.$lead->name.' '.$lead->last_name.' Note Added successfully!';
        
        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Lead Convert To Client', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name]);
        // Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/lead/show') . '/' . $request->lead_id;
        $message = collect(['title' => 'Firm Admin create lead note', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link' => $n_link ]);
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/
        
        $res['status'] = true;
        $res['msg'] = 'Lead note created successfully!';
        echo json_encode($res);
    }

}
