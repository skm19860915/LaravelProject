<?php

namespace App\Http\Controllers;

use App\Models\Client_profile;
use Illuminate\Support\Facades\Validator;
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
use App\Models\CaseType;
use App\Models\AdminTask;
use App\Models\ClientTask;
use App\Models\ClientInformation;
use App\Models\TextMessage;
use App\Models\Questionnaire;
use QuickBooksOnline\API\Facades\Invoice;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\UserMeta;
use App\Notifications\DatabaseNotification;
use Notification;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App;
use App\User;
use Twilio\Rest\Client; 

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

/* --------------QuickBook--------------- */
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Facades\Payment;
/* --------------QuickBook--------------- */

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $access_token;
    public function __construct() {
        require_once(base_path('public/QuickBook/v2/vendor/autoload.php'));
        require_once(base_path('public/QuickBook/gettoken.php'));
        $this->access_token = '';
        require_once(base_path('public/calenderApi/settings.php'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('home');
    }

    //For fetching all countries
    public function getCounties() {
        $countries = DB::table("countries")->get();
        return $countries;
        // return view('index')->with('countries',$countries);
    }

    //For fetching states
    public function getStates($id) {
        $states = DB::table("regions")
                ->where("country_id", $id)
                ->pluck("name", "id");
        return response()->json($states);
    }

    //For fetching cities
    public function getCities($id) {
        $cities = DB::table("cities")
                ->where("region_id", $id)
                ->pluck("name", "id");
        return response()->json($cities);
    }

    public function profile() {
        
        $data = Auth::User();
        if(empty($data)) {
            return redirect('/login')->with('info', 'Please login');
        }
        CalenderRedirectSessionSave();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $client_redirect = url('profile');
        $provider = new Google([
            'clientId'     => CLIENT_ID,
            'clientSecret' => CLIENT_SECRET,
            'redirectUri'  => $client_redirect,
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
            return redirect(CalenderRedirect());
            exit();
        }

        $authUrl = $provider->getAuthorizationUrl([
                        'scope' => [
                            'https://www.googleapis.com/auth/calendar'
                        ],
                        'prompt' => 'consent'
                    ]);
        $_SESSION['oauth2state'] = $provider->getState();

        $access_token = get_user_meta(Auth::User()->id, 'access_token');
        if(!empty($access_token)) {
            $elist = GetCalendarsList($access_token);
            if($elist == 0) {
                $refreshToken = get_user_meta(Auth::User()->id, 'refresh_token');
                $grant = new RefreshToken();
                $access_token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
                update_user_meta(Auth::User()->id, 'access_token', $access_token);
            }
        }
        $roles = DB::table("roles")->where('id', $data->role_id)->first();
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->where('firms.id', $data->firm_id)
                ->join('users', 'users.email', '=', 'firms.email')
                ->first();
        $QuickBookUrl = app('App\Http\Controllers\firmadmin\FirmSettingController')->QuickbookToken($data->id, $data->QBcompanyID, $data->QBToken, $data->QBTokenDate, $data->QBConnect);

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
        // pre($card);
        // die();
        if ($data->role_id == 1 || $data->role_id == 2 || $data->role_id == 3) { // Tila Admin / Tila VP / Tila Support
            return view('auth/profile1', compact('data', 'roles', 'access_token', 'authUrl', 'QuickBookUrl'));
        } else if ($data->role_id == 4 || $data->role_id == 5 || $data->role_id == 6) { // Firm Admin / Firm User / Firm Client
            if (isset($_REQUEST['v']) && $_REQUEST['v'] == 1) {
                return view('auth/profile_new', compact('data', 'roles', 'firm', 'access_token', 'authUrl', 'card'));
            } else {
                return view('auth/profile', compact('data', 'roles', 'firm', 'QuickBookUrl', 'access_token', 'authUrl', 'card'));
            }
        } else if ($data->role_id == 7) { // Firm Client Family 
            return view('auth/profile2', compact('data', 'roles', 'firm', 'access_token', 'authUrl', 'card'));
        }
    }

    public function delete_card($id) {
        $data = Auth::User();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $searchResults = \Stripe\Customer::all([
            "email" => $data->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cust = '';
        $card = base64_decode($id);
        try {
            if($searchResults->data) {
                $cust =  $searchResults->data[0];
                $cus_id =  $searchResults->data[0]->id;
                $res = \Stripe\Customer::deleteSource(
                  $cus_id,
                  $card
                );
            }
        } catch(\Stripe\Exception\CardException $e) {
            return redirect()->back()->withErrors($e->getError()->message);
        }
        return redirect()->back()->with('success', 'Card deleted successfully!');
    }

    public function update_stripe_card(Request $request) {

        $res = array();
        $data = Auth::User();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        
        $searchResults = \Stripe\Customer::all([
            "email" => $data->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cust = '';
        $cardid = $request->cardid;
        $carddata = [
                        'address_zip' => $request->address_zip,
                        'exp_month' => $request->exp_month,
                        'exp_year' => $request->exp_year
                    ];
        try {
            if($searchResults->data) {
                if($request->new_card == '1'){
                    $stripeToken = $request->stripe_token;
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
                                        
                    $res['status'] = true;
                    $res['msg'] = 'Card Added successfully!';

                } else {
                    $cust =  $searchResults->data[0];
                    $cus_id =  $searchResults->data[0]->id;
                    $res1 = \Stripe\Customer::updateSource(
                        $cus_id,
                        $cardid,
                        $carddata
                    );
                    $res['status'] = true;
                    $res['msg'] = 'Card Update successfully!';
                }
            }
        } catch(\Stripe\Exception\CardException $e) {
            $res['status'] = false;
            $res['msg'] = $e->getError()->message;
        }
        echo json_encode($res);
    }

    public function logout_google() {
        $data = Auth::User();
        UserMeta::where('user_id', $data->id)->where('meta_key', 'access_token')->delete();
        UserMeta::where('user_id', $data->id)->where('meta_key', 'refresh_token')->delete();
        return redirect()->back()->with('success', 'Logout form Google Calendar successfully');
    }

    public function updateprofile(Request $request) {
        
        $data = Auth::User();
        update_user_meta($data->id, 'usertimezone', $request->usertimezone);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if($data->role_id == 1){
            update_user_meta(1, 'annual_amount', $request->annual_amount);
        }
        if($data->role_id == 2){
            update_user_meta($data->id, 'dob', $request->dob);
            update_user_meta($data->id, 'mailing_address', $request->mailing_address);
        }
        $_SESSION['UserTimeZone']=$request->usertimezone;
        if (isset($request->update_user)) {
            $arr = [
                'name' => $request->name,
                'contact_number' => $request->contact_number
            ];
            if (!empty($request->file)) {
                $avatar = Storage::put('client_doc', $request->file);
                $arr['avatar'] = $avatar;
            }
            User::where('id', $data->id)->update($arr);
            return redirect('/profile')->with('success', 'Profile Update successfully!');
        } 
        else if (isset($request->rest_password)) {
            // User::where('id', $data->id)->update(['password' => Hash::make($request->password)]);
            $validator = $request->validate([
                'email' => 'required',
                'password' => 'required|min:6'
            ]);

            if (Auth::attempt($validator)) {
                User::where('id', $data->id)->update(['password' => Hash::make($request->new_password)]);
                return redirect('/logout')->with('info', 'Password reset successfully, plese login with new password!');
                //return redirect()->route('dashboard'); Jaimatadi@786
            } else {
                return redirect('/profile')->with('error', 'Current password is not valid!');
            }
        } 
        else if (isset($request->update_setting)) {
            $firm = Firm::select('firms.*', 'users.id as uid')
                    ->where('firms.id', $data->firm_id)
                    ->join('users', 'users.email', '=', 'firms.email')
                    ->first();
            if (!empty($request->theme_logo)) {
                $theme_logo = Storage::put('client_doc', $request->theme_logo);
                update_user_meta($firm->uid, 'theme_logo', $theme_logo);
            }
            update_user_meta($data->id, 'theme_color', $request->theme_color);
            pre($request->all());
            $info_viewable_me = 0;
            $info_viewable_firm = 0;
            $info_viewable_hr = 0;
            if (isset($request->info_viewable_me)) {
                $info_viewable_me = 1;
            }
            if (isset($request->info_viewable_firm)) {
                $info_viewable_firm = 1;
            }
            if (isset($request->info_viewable_hr)) {
                $info_viewable_hr = 1;
            }
            update_user_meta($data->id, 'info_viewable_me', $info_viewable_me);
            update_user_meta($data->id, 'info_viewable_firm', $info_viewable_firm);
            update_user_meta($data->id, 'info_viewable_hr', $info_viewable_hr);

            update_user_meta($data->id, 'account_id', $request->account_id);
            update_user_meta($data->id, 'trust_account_id', $request->trust_account_id);
            update_user_meta($data->id, 'echeck', $request->echeck);
            update_user_meta($data->id, 'SECRET_KEY', $request->SECRET_KEY);
            return redirect('/profile')->with('success', 'Settings Update successfully!');
        }
        else if (isset($request->add_attorney)) {
            $pass = str_random(8);
            $user_name = $request->attorney_fname;
            $user_name .= ' '.$request->attorney_mname;
            $user_name .= ' '.$request->attorney_lname;
            $user_email = 'dummy'.time().'@tilacaseprep.com';
            $data = [
                'name' => $user_name,
                'role' => 5,
                'email' => $user_email,
                'password' => Hash::make($pass),
                'password_confirmation' => Hash::make($pass),
                'role_id' => 5,
                'firm_id' => Auth::User()->firm_id,
                'status' => 0
            ];

            $user = User::create($data);
            User::where('id', $user->id)->update(['first_login' => 0]);
            return redirect('/profile')->with('success', 'Attorney added successfully!');
        }
        else if (isset($request->vp_update_setting)) {
            if (!empty($request->theme_logo)) {
                $theme_logo = Storage::put('client_doc', $request->theme_logo);
                update_user_meta($data->id, 'theme_logo', $theme_logo);
            }
            update_user_meta($data->id, 'theme_color', $request->theme_color);
            return redirect('/profile')->with('success', 'Settings Update successfully!');
        }
    }

    public function editpdf($id) {
        PagesAuthentications();
        $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
                ->where('client_information_forms.id', $id)
                ->first();
        if ($client_information_forms->client_id) {
            $uu = getUserName($client_information_forms->client_id);
            $client_information_forms->name = $uu->name;
        } else {
            $client_information_forms->name = 'Case Form';
        }
        if(empty($client_information_forms->information)) {
            $client_information_forms->information = json_encode(array());
        }
        // $client_information_forms->information = GetFieldValueForForm($client_information_forms->client_id, $client_information_forms->file_type);

        return view('auth/editpdf', compact('client_information_forms'));
    }

    public function edit_questionnaire($formtype, $lang, $id) {
        if(strpos($formtype, 'Petitioner') !== false && $lang == 'en') {
            $furl = asset('storage/app').'/Questionnaire for Petitioner - English.pdf';
        }
        else if(strpos($formtype, 'Petitioner') !== false && $lang == 'es') {
            $furl = asset('storage/app').'/Cuestionario del Peticionario - Spanish.pdf';
        }
        else if(strpos($formtype, 'Beneficiary') !== false && $lang == 'en') {
            $furl = asset('storage/app').'/Questionnaire for Beneficiary - English.pdf';
        }
        else if(strpos($formtype, 'Beneficiary') !== false && $lang == 'es') {
            $furl = asset('storage/app').'/Cuestionario para Beneficiario - Spanish.pdf';
        }
        else {
            $ques1 = Questionnaire::select('*')
                ->where('id', $lang)
                ->first();
            $furl = asset('storage/app').'/'.$ques1->file;
        }

        // $furl = asset('storage/app').'/forms/g-28.pdf';
        $key = $formtype.'_'.$lang;
        $formdata = get_user_meta($id, $key);
        if(empty($formdata)) {
            $formdata = array();
            $formdata = json_encode($formdata);
        }
        
        return view('auth/edit_questionnaire', compact('formdata', 'formtype', 'lang', 'id', 'furl'));
    }

    public function update_questionnaire(Request $request) {
        extract($_POST);
        $key = $formtype.'_'.$lang;
        update_user_meta($id, $key, $data);
    }

    public function add_questionnaire_fn(Request $request) {
        extract($_POST);
        $furl = '';
        if($Questionnaire == 'Petitioner' && $que_lang == 'en') {
            $furl = 'Questionnaire for Petitioner - English.pdf';
        }
        else if($Questionnaire == 'Petitioner' && $que_lang == 'es') {
            $furl = 'Cuestionario del Peticionario - Spanish.pdf';
        }
        else if($Questionnaire == 'Beneficiary' && $que_lang == 'en') {
            $furl = 'Questionnaire for Beneficiary - English.pdf';
        }
        else if($Questionnaire == 'Beneficiary' && $que_lang == 'es') {
            $furl = 'Cuestionario para Beneficiario - Spanish.pdf';
        }

        $data = [
                'client_id' => $client_id,
                'language' => $que_lang,
                'type' => $Questionnaire,
                'name' => $membername,
                'file' => $furl,
                'status' => 0
                ];
        Questionnaire::create($data);
        // $key1 = $Questionnaire.'_'.$index_id.'_en';
        // update_user_meta($client_id, $key1, '');
        // $key2 = $Questionnaire.'_'.$index_id.'_es';
        // update_user_meta($client_id, $key2, '');
        return redirect()->back()->with('success', 'Questionnaire added successfully');
    }

    public function send_invoice(Request $request) {
        $invoice = QBInvoice::select('*')->where('id', $request->id)->first();
        $arr = array('invoice' => $invoice, 'id' => $request->id);
        $link = url('viewinvoice/'.$request->id);
        $invoice_pass = str_random(8);
        if($request->n_type == 'email') {
            $remove = array(
                'Client_Name' => $invoice->client_name,
                'Link' => $link,
                'InvoicePassword' => $invoice_pass
            );
            $email = EmailTemplate(35, $remove);
            
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => $request->contact_info,
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com'
            );
            send_mail($args);
        }
        else {
            $email = "Hello $invoice->client_name,\n\nAn invoice has been generated by your firm admin.Click here to view and complete the payment process.\n\n$link\nInvoice Password : $invoice_pass";

            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            try {
                $message = $twilio->messages 
                  ->create($request->contact_info,
                           array( 
                             "from" => env('TWILIO_FROM_NO'),       
                             "body" => $email 
                         ) 
                       ); 
            }
            catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
        
        QBInvoice::where('id', $request->id)->update(['invoice_pass' => $invoice_pass]);
    }

    public function viewinvoice(Request $request, $id) {
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        $firm = Firm::select('firms.*', 'users.id as uid', 'users.*')
                ->where('firms.id', $invoice->firm_id)
                ->join('users', 'users.email', '=', 'firms.email')
                ->first();
        // pre($firm);
        // die();
        $invoice_password = '';
        if(!empty($request->invoice_password)) {
            $invoice_password = $request->invoice_password;
        }
        return view('auth/viewinvoice', compact('invoice', 'invoice_password', 'firm'));
    }

    public function pay_for_invoice(Request $request) {

        $invoice = QBInvoice::select('*')->where('id', $request->id)->first();
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $invoice->firm_id)
                ->first();

        $tx_id = 0;
        $t_amount = 0;
        $array = array();
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
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        if(!empty($request->payment_method) && $request->payment_method == 'Credit Card') {
            
            $exp_date = explode('/', $_REQUEST['exp_date']);
            
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
                        "postal_code" => "04401"
                        // "reference" => $request->ctype.$request->lead_id
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
            else {
                $tx_id = $array["id"];
                $amt1 = $request->amount;
                if(!empty($invoice->paid_amount)) {
                    $amt1 = $request->amount+$invoice->paid_amount;
                }
                QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
            }
        }
        else if(!empty($request->payment_method) && $request->payment_method == 'E-Check') {

            $account_id = get_user_meta($firm->uid, 'echeck');

            $t_amount = $request->amount*100;
            $amt1 = $request->amount;
            
            $ch = curl_init();

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
                        "account_type"=> $request->account_type,
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
                $amt1 = $request->amount+$invoice->paid_amount;
            }
            QBInvoice::where('id',$request->id)->update(['paid_amount' => $amt1]);
        }

        QBInvoice::where('id',$request->id)->update(['status'=> 1, 'payment_method' => $request->payment_method, 'paid_date' => date('m/d/Y')]);

        $data['tx_id'] = $tx_id;
        $data['amount'] = $t_amount;
        $data['user_id'] = $firm->uid;
        $data['responce'] = json_encode($array);
        $data['paymenttype'] = 4;
        $data['type'] = 'Invoice';
        $data['related_id'] = $request->id;
        Transaction::create($data);

        return redirect('viewinvoice/'.$request->id.'?invoice_password='.$request->invoice_password)->with('success','Paid successfully');
    }
    public function sendinvoice(Request $request, $id) {
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        $link = url('firm/firmclient/billing/invoice/viewinvoice/'.$id);
        

        if($request->n_type == 'email') {
            $remove = array(
                'Client_Name' => $invoice->client_name,
                'Link' => $link,
            );
            $email = EmailTemplate(34, $remove);
            $args = array(
                'bodyMessage' => $email['MSG'],
                'to' => 'snvservices.ravikant@gmail.com',
                'subject' => $email['Subject'],
                'from_name' => 'TILA',
                'from_email' => 'no-reply@tilacaseprep.com',
                'pdfdata' => $request->pdfdata
            );
            send_mail($args);
        }
        else {
            $email = "Hello $invoice->client_name,\n\nAn invoice has been generated by your firm admin.Click here to view and complete the payment process.\n\n$link";

            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            try {
                $message = $twilio->messages 
                  ->create($request->contact_info,
                           array( 
                             "from" => env('TWILIO_FROM_NO'),       
                             "body" => $email 
                         ) 
                       ); 
            }
            catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function printinvoice($id) {
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        // pre($invoice);
        $arr = array('invoice' => $invoice, 'id' => $id);
        PrintInvoice($arr);
        // return redirect('firm/client/client_invoice/' . $invoice->client_id)->with('success', 'Send invoice successfully!');
    }

    public function delete_event($id) {
        Event::where('id', $id)->delete();
    }

    public function CreateClientFamily($record, $current_firm_id, $caseID = 0) {


        $newdata = array();
        // $current_firm_id = Auth::User()->firm_id;

        /* if ($validator->fails()) {
          return redirect('firm/client')->withInfo('Mendatory fields are required!');
          } */
        $record = (object) $record;
        // pre($record);
        // pre($current_firm_id);

        $user_id = 0;

        $pass = str_random(8);
        $data = [
            'name' => $record->name,
            'role' => 7,
            'email' => $record->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 7,
            'firm_id' => $current_firm_id
        ];
        $newdata['userdata'] = $data;
        $user = User::create($data);
        $user_id = $user->id;

        /* --------------------Notifications--------------- */
        $firm_id = $current_firm_id;
        $firm_name = Firm::select('*')->where('id', $current_firm_id)->first();
        $msg = 'Firm ' . $firm_name->firm_name . ' created Family Member account successfully!';
        $message = collect(['title' => 'Firm Admin Created your account', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);

        Notification::send($user, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        $sendcmail = true;
        if ($caseID > 0) {

            if (!empty($record->beneficiary)) {
                $kk = 'memberof_' . $caseID;
                $vv = array(
                    'memberof' => $record->beneficiary,
                    'relationship' => $record->relationship
                );
                update_user_meta($user_id, 'CaseID', $caseID);
                update_user_meta($user_id, $kk, json_encode($vv));
            } else if (!empty($record->derivative)) {
                update_user_meta($user_id, $record->type, $caseID);
                if ($record->type == 'interpreter') {
                    $sendcmail = false;
                }
            } else {
                update_user_meta($user_id, 'CaseID', $caseID);
                update_user_meta($user_id, 'beneficiary', $caseID);
            }
        }
        update_user_meta($user_id, 'ClientID', $record->client_id);
        update_user_meta($user_id, 'firm_id', $current_firm_id);
        update_user_meta($user_id, 'FamilyMember', json_encode($record));

        $message = FirmSetting::where('title', 'Welcome Client')->where('category', 'EMAIL')->where('firm_id', $current_firm_id)->first();

        //pre($message->message);
        /* echo $message->message;
          die(); */

        $username = $record->name;
        $useremail = $record->email;
        $pass = $pass;

        $msg1 = "Hi, $username.<br>";
        $msg1 .= "Email : " . $useremail . " <br>";
        $msg1 .= "Password : " . $pass . " <br><br>";
        $msg1 .= $message->message . "<br>";
        $v = url('firm/clientfamilydashboard');
        $msg1 = str_replace('{client_portal_link}', ' ' . $v . ' ', $msg1);

        $msg = EmptyEmailTemplate($msg1);

        $args = array(
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Welcome to TILA Case Prep',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );

        if ($firm_name->account_type == 'CMS' && $sendcmail && !empty($record->is_portal_access)) {
            send_mail($args);
        }

        $logdata = [
            'title' => "FIRM",
            'related_id' => Auth::User()->firm_id,
            'message' => "Firm admin create a Client Family Member" . $record->name
        ];
        Log::create($logdata);
    }

    public function FindShortCodeData(Request $request, $ID, $ShortCode, $rUrl, $CID) {
        $rUrl = base64_decode($rUrl);

        return view('firmadmin/FindShortCode', compact('ID', 'ShortCode', 'rUrl', 'CID'));
    }

    public function FindShortCodeDataSave(Request $request) {
        $data = ($request->all());
        $UID = $data['userID'];
        $ReDirect = $data['returnpage'];
        unset($data['userID']);
        unset($data['_token']);
        unset($data['returnpage']);
        foreach ($data as $k => $v) {

            if (is_array($v)) {
                $h = 0;
                foreach ($v as $vv) {
                    $h++;
                    $this->SaveAndUpDatePDFData($k . '_' . $h, $vv, $UID);
                }
            } else {
                $this->SaveAndUpDatePDFData($k, $v, $UID);
            }
        }

        return redirect($ReDirect)->with('success', 'Profile Update successfully!');
    }

    function SaveAndUpDatePDFData($key, $Value, $UID) {

        SavePDFDataHelper($key, $Value, $UID);
    }

    function AjaxData(Request $request, $action = '') {
        AllTypeOfAjax($action);
    }

    function FileUpload(Request $request) {
        $data=array();
        $i=0;
        foreach ($request->file as $key => $file) { 
            //$file = $request->file('file');
            $f = Storage::put('chat', $file);
            $data[$i]['file']=asset('storage/app') . '/' . ($f);
            $data[$i]['ext'] = '/icon/'.pathinfo($f, PATHINFO_EXTENSION).'.png';
            $i++;
        }
        
        echo json_encode($data);
    }

    function UpdateFamily(Request $request) {
        $record = $request->all();
        $femail = '';
        if($request->oldemail == $request->email || empty($request->email)) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
            ]);
            $femail = $request->oldemail;
        }
        else {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'email' => 'required|string|email|unique:users',
            ]);
            $femail = $request->email;
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $record['name'] = $request->first_name;
        if (!empty($request->middle_name)) {
            $record['name'] .= ' ' . $request->middle_name;
        }
        if (!empty($request->last_name)) {
            $record['name'] .= ' ' . $request->last_name;
        }
        $d1 = [
            'email' => $femail,
            'type' => $record['type'],
            'name' => $record['name'],
            'gender' => $record['gender'],
            'phon_number' => $record['phon_number'],
            'dob' => $record['dob'],
            'relationship' => $record['relationship']
        ];
        $c = ClientFamily::where('email', $request->oldemail)->update($d1);
        if(empty($femail)) {
            $d2 = [
                'name' => $record['name']
            ];
        }
        else {
            $d2 = [
                'name' => $record['name'],
                'email' => $femail,
            ];
        }
        $record['email'] = $femail;
        //pre($record);
        User::where('id', $request->fid)->update($d2);
        update_user_meta($request->fid, 'FamilyMember', json_encode($record));
        return redirect($request->redirecturl)->with('success', 'Family Update successfully!');
    }

    public function NewQbookConnect() { 
        $data = Auth::User();
        $config = require_once(base_path('public/QuickBook/v2/config.php'));
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' =>  $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => "https://quickbooks.api.intuit.com"
        ));

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

        /*
         * Update the OAuth2Token
         */
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
        $dataService->updateOAuth2Token($accessToken);
        $token = $accessToken->getRefreshToken();
        $tokenD = strtotime($accessToken->getRefreshTokenExpiresAt());

        /*
         * Setting the accessToken for session variable
         */
        // $_SESSION['sessionAccessToken'] = $accessToken;
        $j = array();
        $j['accessTokenValue'] = $accessToken->getAccessToken(); 
        $j['getclientID'] = $accessToken->getclientID();
        $j['getClientSecret'] = $accessToken->getClientSecret();
        $j['getRefreshToken'] = $accessToken->getRefreshToken();
        $j['getRealmID'] = $accessToken->getRealmID();
        $j['getBaseURL'] = $accessToken->getBaseURL();
        $token1 = json_encode($j);
        DB::update('update users set QBToken=?,QBTokenDate=?,QBConnect = ? where id = ?', [$token1, $tokenD, 1, $data->id]);
        return redirect('profile')->with('success', 'QuickBook Connected Successfully!');
    }

    public function parseAuthRedirectUrl($url)
    {
        parse_str($url,$qsArray);
        return array(
            'code' => $qsArray['code'],
            'realmId' => $qsArray['realmId']
        );
    }

    public function setAdditionalDocument(Request $request) {
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $as = json_decode($case->additional_service);

        if(!empty($request->doc_type) && $request->doc_type == 'nvc_packet_doc') {
            $f = Storage::put('client_doc', $request->file);
            $as->nvc_packet_doc = $f;
        }
        else if(!empty($request->doc_type) && $request->doc_type == 'additional_service_doc') {
            $f = Storage::put('client_doc', $request->file);
            $kk = 'additional_service_doc'.'_'.$request->doc_index;
            $as->$kk = $f;
        }
        else if(!empty($request->doc_type) && $request->doc_type == 'declaration_doc') {
            $f = Storage::put('client_doc', $request->file);
            $kk1 = 'declaration_doc'.'_'.$request->doc_index;
            $as->$kk1= $f;
        }

        FirmCase::where('id', $request->case_id)->update(['additional_service' => json_encode($as)]);
        return redirect()->back()->with('success', 'Document upload successfully');
    }

    public function addcasenotes(Request $request) {

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
            'task_for' => $request->ntype,
            'related_id' => $request->related_id,
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

    public function sendtextmsg(Request $request) {

        $msg = $request->msg;
        $subject = $request->subject;
        $mtype = array();
        //pre($request->all());
        if(!empty($request->is_text_send)) {
            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            $phone_no = preg_replace('/(.*) \((.*)\) (.*)-(.*)/', '$1$2$3$4', $phone_no);
            try {
                $message = $twilio->messages 
                  ->create($phone_no,
                           array( 
                             "from" => env('TWILIO_FROM_NO'),       
                             "body" => $msg 
                         ) 
                       ); 
            }
            catch (\Exception $e) {
                //echo $e->getMessage();
                //return redirect()->back()->withErrors($e->getMessage());
            }
            $mtype[] = 'SMS';
        }

        if(!empty($request->is_email_send)) {
            $useremail = $request->email;
            $msg = EmptyEmailTemplate($msg);
            $args = array (
                'bodyMessage' => $msg,
                'to' => $useremail,
                'subject' => $subject,
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
            $mtype[] = 'Email';
        }

        $data2 = [
            'msgfrom' => Auth::User()->id,
            'msgto' => $request->to,
            'msg' => $request->msg,
            'subject' => $request->subject,
            'type' => json_encode($mtype)
        ];
        
        $case = FirmCase::select('*')->where('client_id', $request->to)->orderBy('id', 'DESC')->first();

        $touser = User::where('id',$request->to)->first();
        if(!empty($case)) {
            $n_link = url('firm/mymessages').'/'.$request->to;
        }
        else {
            $n_link = url('/');
        }
        $n_link = url('firm/mymessages');
        $message = collect(['title' => 'Send you Text message', 'body' => $request->msg,'type'=>'1','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        Notification::send($touser, new DatabaseNotification($message));
        $note = TextMessage::create($data2);
    }

    public function DEVQBInvoiceCreation($case) {
        $tilaadmin = User::where('id', 1)->first();
        $current_user = Auth::User();
        $frim = Firm::select('*')->where('id', $current_user->firm_id)->first();
        $saveddata = [
            'firm_name' => $frim->firm_name,
            'account_type' => $frim->account_type,
            'email' => $frim->email,
            'firm_admin_name' => $frim->firm_admin_name
        ];
        $CID = 0;
        // echo "tilaadmin===============";
        // pre($tilaadmin);
        if ($tilaadmin->QBConnect == 1) {
            $invoiceData = json_decode($tilaadmin->QBToken);
            $accessTokenKey = $invoiceData->accessTokenValue;
            $refreshToken = $invoiceData->getRefreshToken;
            $QBcompanyID = $invoiceData->getRealmID;
            $ClientID = $invoiceData->getclientID;
            $client_secret = $invoiceData->getClientSecret;
            $BaseURL = $invoiceData->getBaseURL;
            // echo "invoiceData===============";
            // pre($invoiceData);
            $conf = require_once(base_path('public/QuickBook/conf.php'));
            // echo "conf===============";
            // pre($conf); 
            $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $conf['client_id'],
                        'ClientSecret' => $conf['client_secret'],
                        'RedirectURI' => $conf['oauth_redirect_uri_a'],
                        'scope' => $conf['oauth_scope'],
                        'baseUrl' => "https://quickbooks.api.intuit.com",
                        'accessTokenKey' => $accessTokenKey,
                        'refreshTokenKey' => $refreshToken
            )); 
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $client_secret);
           // $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            // echo "newAccessTokenObj===============";
            // pre($newAccessTokenObj);
            // die();
            try {
                $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
                // echo "newAccessTokenObj===============";
                // pre($newAccessTokenObj);
                // die();
            }   
            catch (ServiceException  $e) {
                echo $e->getMessage();
                //pre($e);
                return 0;
            }
            $newAccessTokenObj->setRealmID($QBcompanyID);
            $newAccessTokenObj->setBaseURL($BaseURL);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

            $dataService->updateOAuth2Token($newAccessTokenObj);

            $j = array();
            $j['accessTokenValue'] = $newAccessTokenObj->getAccessToken(); 
            $j['getclientID'] = $newAccessTokenObj->getclientID();
            $j['getClientSecret'] = $newAccessTokenObj->getClientSecret();
            $j['getRefreshToken'] = $newAccessTokenObj->getRefreshToken();
            $j['getRealmID'] = $newAccessTokenObj->getRealmID();
            $j['getBaseURL'] = $newAccessTokenObj->getBaseURL();
            $tokenD = strtotime($newAccessTokenObj->getRefreshTokenExpiresAt());
            $token1 = json_encode($j);

            DB::update('update users set QBToken=?,QBTokenDate=?,QBConnect = ? where id = ?', [$token1, $tokenD, 1, 1]);

            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

            $dataService->throwExceptionOnError(true);
            /*
             * Update the OAuth2Token of the dataService object
             */

            $dataService->updateOAuth2Token($newAccessTokenObj);

            $QBCustomerEmail = $saveddata['email'];
            $customer = $dataService->Query("SELECT * FROM Customer WHERE PrimaryEmailAddr = '$QBCustomerEmail'");   
            // pre($customer);
            // die();         
            if(empty($customer)) { 
                $Line1 = '';
                $City = 'Mountain View';
                $CountrySubDivisionCode = 'CA';
                $Country = 'USA';
                $PostalCode = '94043';
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
                            "GivenName" => $saveddata['firm_admin_name'],
                            "MiddleName" => '',
                            "FamilyName" => '',
                            "Suffix" => "Jr",
                            "FullyQualifiedName" => $saveddata['firm_admin_name'],
                            "CompanyName" => $saveddata['firm_name'],
                            "DisplayName" => $saveddata['firm_admin_name'],
                            "PrimaryPhone" => [
                                "FreeFormNumber" => ''
                            ],
                            "PrimaryEmailAddr" => [
                                "Address" => $saveddata['email']
                            ]
                ]);



                $resultingObj = $dataService->Add($theResourceObj);

                $error = $dataService->getLastError();
                // pre($error);
                // die();
                if ($error) {
     
                } else {
                    "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
                    $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
                    $xmlBody . "\n";
                }
                $CID = $resultingObj->Id;
            }
            else {
                $CID = $customer[0]->Id;
            }
            update_user_meta($current_user->id, 'QBCustomerID', $CID);
            // echo "case1===============";
            // pre($case);
            $case_cost = $case->case_cost;
            $case_cost = str_replace('$', '', $case_cost);
            // echo "case_cost2===============";
            // pre($case_cost);
            $arr1 = [
                "Description" => $case->case_category,
                "Amount" => intval($case_cost),
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "Qty" => 1,
                    "UnitPrice" => $case->case_category,
                    "ItemRef" => [
                        "value" => 1,
                        "name" => $case->case_type
                    ]
                ]
            ];
            $itemArr[] = $arr1;

            $invoiceToCreate = Invoice::create([
                "DocNumber" => time(),
                "Line" => $itemArr,
                "CustomerRef" => [
                    "value" => $CID,
                    "name" => $saveddata['firm_admin_name']
                ]
            ]);
            $resultObj = $dataService->Add($invoiceToCreate);
            $invoice_id = $resultObj->Id;
            // echo "invoice_id===============";
            // pre($invoice_id);
            $theResourceObj = Payment::create([
                  "CustomerRef" =>
                  [
                      "value" => $CID
                  ],

                  "TotalAmt" => intval($case_cost),

                  "Line" => [

                  [

                      "Amount" => intval($case_cost),

                      "LinkedTxn" => [

                      [

                          "TxnId" => $invoice_id,

                          "TxnType" => "Invoice"

                      ]]

                  ]]

                ]);

                $resultingObj = $dataService->Add($theResourceObj);
                // echo "resultingObj===============";
                // pre($resultingObj);
        }
        // die('----------------');
        
    }

    public function search() {
        $data = Auth::User();
        $firm_id = $data->firm_id;
        $current_id = $data->id;
        $query = !empty($_GET['search']) ? $_GET['search'] : '';
        $result = array();
        if(!empty($query))
        {
            if($data->role_id == 1) {
                $q = "SELECT * FROM `new_client` WHERE ";
                $q .= "(id LIKE '%$query%' or firm_id LIKE '%$query%' or first_name LIKE '%$query%' or middle_name LIKE '%$query%' or last_name LIKE '%$query%' or cell_phone LIKE '%$query%' or email LIKE '%$query%' or language LIKE '%$query%' or type LIKE '%$query%' or gender LIKE '%$query%')";
                $clients = DB::select(DB::raw($q));
                $result['clients'] = $clients;
                
                $q1 = "SELECT * FROM `case` WHERE ";
                $q1 .= "(id LIKE '%$query%' or firm_id LIKE '%$query%' or case_category LIKE '%$query%' or case_type LIKE '%$query%' or case_cost LIKE '%$query%' or status LIKE '%$query%')";
                $cases = DB::select(DB::raw($q1));
                $result['cases'] = $cases;

                $q2 = "SELECT * FROM `users` WHERE role_id = 2 and ";
                $q2 .= "(id LIKE '%$query%' or firm_id LIKE '%$query%' or name LIKE '%$query%' or email LIKE '%$query%' or contact_number LIKE '%$query%')";
                $users = DB::select(DB::raw($q2));
                $result['users'] = $users;

                $q3 = "SELECT f.*, u.id as fid FROM `firms` as f ";
                $q3 .= "LEFT JOIN `users` as u ON f.email = u.email";
                $q3 .= " WHERE (f.id LIKE '%$query%' or f.account_type LIKE '%$query%' or f.firm_name LIKE '%$query%' or f.email LIKE '%$query%' or f.firm_admin_name LIKE '%$query%' or u.name LIKE '%$query%' or u.contact_number LIKE '%$query%')";
                $firms = DB::select(DB::raw($q3));
                $result['firms'] = $firms;
            }
            else if($data->role_id == 2) {
                $q3 = "SELECT DISTINCT f.id,f.*, u.id as fid FROM `firms` as f ";
                $q3 .= "LEFT JOIN `users` as u ON f.email = u.email ";
                $q3 .= "LEFT JOIN `admintask` as at ON at.firm_admin_id = u.id";
                $q3 .= " WHERE at.allot_user_id = '$current_id' and at.task_type = 'Assign_Case' and (f.id LIKE '%$query%' or f.account_type LIKE '%$query%' or f.firm_name LIKE '%$query%' or f.email LIKE '%$query%' or f.firm_admin_name LIKE '%$query%' or u.name LIKE '%$query%' or u.contact_number LIKE '%$query%')";
                $firms = DB::select(DB::raw($q3));
                $result['firms'] = $firms;

                $q2 = "SELECT c.*, at.*, at.id as tid FROM `admintask` as at ";
                $q2 .= "JOIN `case` as c ON at.case_id = c.id ";
                $q2 .= "JOIN `firms` as f ON c.firm_id = f.id ";
                $q2 .= "JOIN `users` as u ON at.firm_admin_id = u.id ";
                
                $q2 .= "WHERE at.allot_user_id = '$current_id' and at.task_type = 'Assign_Case' and at.status = 1 ";

                $q2 .= "and (c.id LIKE '%$query%' or c.case_category LIKE '%$query%' or c.case_type LIKE '%$query%' or c.case_cost LIKE '%$query%')";
                $cases = DB::select(DB::raw($q2));
                $result['cases'] = $cases;

                $q = "SELECT DISTINCT n.user_id, n.* FROM `new_client` as n ";
                $q .= "JOIN `users` as u ON n.user_id = u.id ";
                $q .= "JOIN `case` as c ON u.id = c.client_id ";
                $q .= "JOIN `admintask` as at ON at.case_id = c.id ";
                $q .= "JOIN `firms` as f ON c.firm_id = f.id ";
                $q .= "WHERE at.allot_user_id = '$current_id' and at.task_type = 'Assign_Case' and at.status = 1 ";

                $q .= "and (n.id LIKE '%$query%' or n.firm_id LIKE '%$query%' or n.first_name LIKE '%$query%' or n.middle_name LIKE '%$query%' or n.last_name LIKE '%$query%' or n.cell_phone LIKE '%$query%' or n.email LIKE '%$query%' or n.language LIKE '%$query%' or n.type LIKE '%$query%' or n.gender LIKE '%$query%')";
                $clients = DB::select(DB::raw($q));
                $result['clients'] = $clients;
            }
            else if($data->role_id == 4 || $data->role_id == 5) {
                $q = "SELECT * FROM `new_client` WHERE firm_id = '$firm_id'";
                $q .= " and (id LIKE '%$query%' or firm_id LIKE '%$query%' or first_name LIKE '%$query%' or middle_name LIKE '%$query%' or last_name LIKE '%$query%' or cell_phone LIKE '%$query%' or email LIKE '%$query%' or language LIKE '%$query%' or type LIKE '%$query%' or gender LIKE '%$query%')";
                $clients = DB::select(DB::raw($q));
                $result['clients'] = $clients;
                
                $q1 = "SELECT * FROM `case` WHERE firm_id = '$firm_id'";
                $q1 .= " and (id LIKE '%$query%' or firm_id LIKE '%$query%' or case_category LIKE '%$query%' or case_type LIKE '%$query%' or case_cost LIKE '%$query%' or status LIKE '%$query%')";
                $cases = DB::select(DB::raw($q1));
                $result['cases'] = $cases;

                $q2 = "SELECT * FROM `users` WHERE firm_id = '$firm_id' and role_id in (4, 5)";
                $q2 .= " and (id LIKE '%$query%' or firm_id LIKE '%$query%' or name LIKE '%$query%' or email LIKE '%$query%' or contact_number LIKE '%$query%')";
                $users = DB::select(DB::raw($q2));
                $result['users'] = $users;

                $q3 = "SELECT cf.*, u.id as fid FROM `client_family` as cf ";
                $q3 .= "LEFT JOIN `users` as u ON cf.email = u.email";
                $q3 .= " WHERE u.firm_id = '$firm_id' and (cf.id LIKE '%$query%' or cf.type LIKE '%$query%' or cf.name LIKE '%$query%' or cf.email LIKE '%$query%' or cf.phon_number LIKE '%$query%' or cf.gender LIKE '%$query%' or cf.dob LIKE '%$query%' or cf.relationship LIKE '%$query%')";
                $family = DB::select(DB::raw($q3));
                $result['family'] = $family;
            }
        }

        if ($data->role_id == 1) {
            return view('auth/admin_search', compact('data', 'result', 
                'query'));
        } 
        else if ($data->role_id == 2) {
            return view('auth/vp_search', compact('data', 'result', 
                'query'));
        }
        else if ($data->role_id == 4 || $data->role_id == 5) {
            return view('auth/firm_search', compact('data', 'result', 'query'));
        } else if ($data->role_id == 6 || $data->role_id == 7) {
            return view('auth/family_search', compact('data', 'result', 'query'));
        }
    }

}
