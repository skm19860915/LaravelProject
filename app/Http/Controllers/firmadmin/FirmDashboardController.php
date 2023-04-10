<?php
namespace App\Http\Controllers\firmadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\Caselog;
use App\User;
use Twilio\Rest\Client;
use App\Models\FirmCase;
use App\Models\QBInvoice;
use App\Models\SchedulePayment;
use App\Models\Lead;
use App\Models\Client_profile;
use App\Models\Newclient;
use App\Models\TilaEmailTemplate;
use Session;
use App\Models\Notifications;
use App\Notifications\DatabaseNotification;
use Notification;
/* --------------QuickBook--------------- */
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Account;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

const INCOME_ACCOUNT_TYPE = "Income";
const INCOME_ACCOUNT_SUBTYPE = "SalesOfProductIncome";
const EXPENSE_ACCOUNT_TYPE = "Cost of Goods Sold";
const EXPENSE_ACCOUNT_SUBTYPE = "SuppliesMaterialsCogs";
const ASSET_ACCOUNT_TYPE = "Other Current Asset";
const ASSET_ACCOUNT_SUBTYPE = "Inventory";

/* --------------QuickBook--------------- */

class FirmDashboardController extends Controller
{
	public function __construct() {
        require_once(base_path('public/QuickBook/v2/vendor/autoload.php'));
        require_once(base_path('public/QuickBook/gettoken.php'));

    }

	public function index(Request $request)
	{
		$currunt_user = Auth::User();
        $responce = User::select('status')->where('id',$currunt_user->id)->first();
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
		if ($responce->status == 0) {
			$amount = $firm->usercost;
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
			return view('firmadmin.dashboard.payment_method1', ["firm"=>$firm, "user"=>$currunt_user, 'amount' => $amount, 'card'=> $card]);
		}
		if($currunt_user->first_login == '1' && $currunt_user->role_id == '4') {
			$request->session()->put('firstlogin', true);
			return redirect('firm/FirmAccountSetUp')->with('info','Please Update Firm Account');
		}

        $count = [];
        if($firm->account_type == 'CMS') {
    		$admintask = AdminTask::select('admintask.*','firms.firm_name','cluser.id as clientid','cluser.name as clientname','cluser.email as clientemail','case.case_file_path','vauser.id as vauserid','vauser.name as vausername','vauser.email as vauseremail','nc.id as cid')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users as cluser', 'case.client_id', '=', 'cluser.id')
            ->join('users as vauser', 'admintask.firm_admin_id', '=', 'vauser.id')
            ->leftJoin('new_client as nc', 'nc.user_id', '=', 'case.client_id')
            ->where('admintask.firm_admin_id',$currunt_user->id)
            ->where('admintask.status',0)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Required_Document_Request', 'Upload_Required_Document'])
            ->orderByDesc('admintask.id')
            ->limit(10)
            ->get(); 
            foreach ($admintask as $key => $value) {
                $ccid = $value->case_id;
                if($value->task_type == 'provide_a_quote') {
                    $doc = DocumentRequest::select('quote', 'case_id')->where('id', $ccid)->first();
                    $ccid = $doc['case_id'];
                }
                $case = FirmCase::select('case_type')->where('id', $ccid)->first();
                $admintask[$key]->case_type = $case['case_type'];
                if($value->task_type == 'ADMIN_TASK') {
                    $firmcase = Newclient::select('*')
                                ->where('user_id', $task->client_task)
                                ->first();
                    if($firmcase) {
                        $cname1 = $firmcase->first_name;
                        if(!empty($firmcase->middle_name)) {
                            $cname1 .= ' '.$firmcase->middle_name;
                        }
                        if(!empty($firmcase->last_name)) {
                            $cname1 .= ' '.$firmcase->last_name;
                        }
                        $admintask[$key]->clientname = $cname1; 
                        $admintask[$key]->cid = url('firm/client/client_task'.$firmcase->id);
                    }
                }
            }
            
            
            $total_lead = Lead::select('*')
                ->where('firm_id',$currunt_user->firm_id)
                ->count();

            $count['total_lead'] = $total_lead;

            $case_closed = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
                ->join('users', 'case.user_id', '=', 'users.id')
                ->join('users as ur', 'case.client_id', '=', 'ur.id')
                ->where('case.firm_id',$currunt_user->firm_id)
                ->where('case.status',9)
                ->count(); 

            $count['case_closed'] = $case_closed;

            $cases = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
                ->join('users', 'case.user_id', '=', 'users.id')
                ->join('users as ur', 'case.client_id', '=', 'ur.id')
                ->where('case.firm_id',$currunt_user->firm_id)
                ->count(); 

            $count['cases'] = $cases;

            $clients = User::select('users.*')
            ->where('firm_id',$currunt_user->firm_id)
            ->where('role_id' ,'=', '6')
            ->count();  

            $count['clients'] = $clients;
        }
        else if($firm->account_type != 'CMS') {
            $admintask = AdminTask::select('admintask.*','firms.firm_name','cluser.id as clientid','cluser.name as clientname','cluser.email as clientemail','nc.id as cid')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->join('firms', 'firms.id', '=', 'users.firm_id')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('users as cluser', 'case.client_id', '=', 'cluser.id')
            ->leftJoin('new_client as nc', 'nc.user_id', '=', 'case.client_id')
            ->where('admintask.firm_admin_id', $currunt_user->id)
            ->where('admintask.status', 0)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Required_Document_Request', 'Upload_Required_Document'])
            ->orderByDesc('admintask.id')
            ->limit(10)
            ->get(); 

            foreach ($admintask as $key => $value) {
                
                if($value->task_type == 'ADMIN_TASK') {
                    $firmcase = Newclient::select('*')
                                ->where('user_id', $value->client_task)
                                ->first();
                    if($firmcase) {
                        $cname1 = $firmcase->first_name;
                        if(!empty($firmcase->middle_name)) {
                            $cname1 .= ' '.$firmcase->middle_name;
                        }
                        if(!empty($firmcase->last_name)) {
                            $cname1 .= ' '.$firmcase->last_name;
                        }
                        $admintask[$key]->clientname = $cname1; 
                        $admintask[$key]->cid = $firmcase->id;
                    }
                }
            }
            $open_task = AdminTask::select('admintask.*','firms.firm_name')
                ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
                ->join('firms', 'firms.id', '=', 'users.firm_id')
                ->where('admintask.firm_admin_id', $currunt_user->id)
                ->where('admintask.status', 0)
                ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
                ->count(); 
            $count['open_task'] = $open_task;

            $case_closed = FirmCase::select('case.*')
                ->where('case.firm_id',$currunt_user->firm_id)
                ->where('case.status',9)
                ->count(); 

            $count['case_closed'] = $case_closed;

            $cases = FirmCase::select('case.*')
                ->where('case.firm_id',$currunt_user->firm_id)
                ->count(); 

            $count['cases'] = $cases;

            $open_case = FirmCase::select('case.*')
                ->where('case.firm_id',$currunt_user->firm_id)
                ->whereIn('case.status',array(2))
                ->count(); 

            $count['open_case'] = $open_case;

            $clients = User::select('users.*')
            ->where('firm_id',$currunt_user->firm_id)
            ->where('role_id' ,'=', '6')
            ->count();  

            $count['clients'] = $clients;
        }

        $count['messages'] = array();
      
        $q="select * from notifications where notifiable_id='".$currunt_user->id."' and isread=0 order by created_at DESC LIMIT 5";

        $Notif = DB::select($q);
		return view('firmadmin.dashboard.index', compact('admintask', 'count', 'firm', 'Notif'));
	}

	public function FirmAccountSetUp()
	{

		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		return view('firmadmin.dashboard.accountsetup', ["firm"=>$firm, "user"=>$currunt_user]);
	}

	public function FirmAccountUpdate()
	{
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();

		Firm::where('id', $_POST['firm_id'])->update(['firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type'], 'firm_admin_name' => $_POST['firm_admin_name']]);
        if($firm->email == $currunt_user->email) {
    		User::where('id', $_POST['user_id'])->update(['name' => $_POST['firm_admin_name'], 'first_login' => 0]);
        }
		if($firm->account_type == 'CMS') {
			return redirect('firm/createnewuser')->with('success','Firm Account update successfully');
		}
		else {
			return redirect(route('firm.admindashboard'))->with('success','Firm Account update successfully');
		}
		
	}

	public function createnewuser() {
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		return view('firmadmin.dashboard.createnewuser', ["firm"=>$firm, "user"=>$currunt_user]);
	}

	public function payment_method(Request $request) {
		$currunt_user = Auth::User();
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
        $viewamount = 0;
		$amount = 0;
		$firm_user = $request->session()->get('user_count');
        if(!empty($firm_user)) {
            $amount = $amount+count($firm_user)*$firm->usercost;
        }
		

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

		return view('firmadmin.dashboard.payment_method_n', ["firm"=>$firm, "user"=>$currunt_user, 'card'=> $card, 'amount' => $amount, 'firm_user' => $firm_user, 'viewamount' => $viewamount]);
	}

	public function payment_method2(Request $request) {
		$currunt_user = Auth::User();
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
        $viewamount = $firm->usercost;
		$amount = $firm->usercost;
		$firm_user = $request->session()->get('user_count');
		if(!empty($firm_user)) {
			$amount = $amount+count($firm_user)*$firm->usercost;
		}
		

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

		return view('firmadmin.dashboard.payment_method', ["firm"=>$firm, "user"=>$currunt_user, 'card'=> $card, 'amount' => $amount, 'firm_user' => $firm_user, 'viewamount' => $viewamount]);
	}

	public function first_reset_password() {
		
		return view('firmadmin.dashboard.firstresetpassword');
	}


	public function update_first_password(Request $request) {

		$validator = Validator::make($request->all(), [
            'password'         => 'required',
        	'conform_password' => 'required|same:password'
        ]);

        if ($validator->fails()) 
        {
           	return redirect()->back()->withErrors('The confirm password and password must match.');
        }

        $data = [
            'password' => Hash::make($request->password),
            'is_reset_pass' => 1
        ];
        $current_userid = Auth::User()->id;
        $user = User::where('id',$current_userid)->update($data);

        /*Auth::logout();
        return redirect(route('login'))->withInfo('Your Password is successfully update, Please login With your new password');*/

        $request->session()->put('is_reset_pass', '1');

        $msg = 'Welcome to your firm portal! You can start creating clients and cases.';
                $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('firm/admindashboard');
        $message = collect(['title' => 'Assign VA User', 'body' => $msg,'type'=>'5','from'=>1,'fromName'=>'TILA Admin', 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        //Auth::logout();

        $remove = array(
            'FirmUsername' => Auth::User()->name
        );
        $email = EmailTemplate(43, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Auth::User()->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );

        send_mail($args);
        return redirect(route('firm.admindashboard'))->with('success','Welcome to your firm portal! You can start creating clients and cases.');

	}

	public function create_charge(Request $request) {
		$card_source = '';
		require_once(base_path('vendor/stripe/stripe-php/init.php'));
		extract($_REQUEST);
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		\Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
		// $charge = \Stripe\Charge::create(['amount' => 2000, 'currency' => 'usd', 'source' => $stripeToken]);
		$casecost1 = $request->amount*100;
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
            if(!empty($card_source)) {
                $charge = \Stripe\Charge::create([
                  'customer' => $cus_id,
                  'amount' => $casecost1,
                  'currency' => 'usd',
                  'source' => $card_source
                ]);
            }
            else {
                $charge = \Stripe\Charge::create([
                  'customer' => $cus_id,
                  'amount' => $casecost1,
                  'currency' => 'usd'
                ]);
            }
        } catch(\Stripe\Exception\CardException $e) {
            return redirect()->back()->withErrors($e->getError()->message);
        }

        // $plan = \Stripe\Plan::create([
        //   'amount' => $casecost1,
        //   'currency' => 'usd',
        //   'interval' => 'month',
        //   'product' => 'prod_HaJKyyGjXoSLVN',
        // ]);

        // $charge = \Stripe\Subscription::create([
        //   'customer' => 'cus_HJ8NH2EGZvEAUe',
        //   'items' => [['plan' => $plan->id]],
        // ]);


		User::where('id',$currunt_user->id)->update(['status' => 1]);
		Firm::where('id',$currunt_user->firm_id)->update(['status' => 1]);
		
        $firm_user = $request->session()->get('user_count');

        $ids = array();
        if(!empty($firm_user)) {
            foreach ($firm_user as $key => $u) {
                $ids[] = $u->id;
                User::where('id',$u->id)->update(['status' => 1]);

                $username = $u->name;
                $useremail =  $u->email;
                $pass = $u->temp_pass;

                $remove = array(
                    'FirmName' => $username,
                    'UserN'=>$useremail,
                    'UserP'=>$pass,
                );
                $email = EmailTemplate(32, $remove);
                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => $useremail,
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );

                send_mail($args);
            }
        }
        else {
            $ids[] = $currunt_user->id;
        }
		$data['tx_id'] = $charge->id;
		$data['amount'] = $casecost1;
        $data['type'] = 'User';
		$data['user_id'] = $currunt_user->id;
		$data['responce'] = json_encode($charge);
        $data['related_id'] = json_encode($ids);
		$data['paymenttype'] = 2;
		Transaction::create($data);
		$request->session()->forget('user_count');
		return redirect('firm/users')->with('success','Payment successfull');
	}


	public function create_charge1(Request $request) {
		require_once(base_path('vendor/stripe/stripe-php/init.php'));
		extract($_REQUEST);
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		\Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
		$casecost1 = 6500;
        try {
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
            if(!empty($card_source)) {
                $charge = \Stripe\Charge::create([
                  'customer' => $cus_id,
                  'amount' => $casecost1,
                  'currency' => 'usd',
                  'source' => $card_source
                ]);
            }
            else {
                $charge = \Stripe\Charge::create([
                  'customer' => $cus_id,
                  'amount' => $casecost1,
                  'currency' => 'usd'
                ]);
            }
        } catch(\Stripe\Exception\CardException $e) {
            return redirect()->back()->withErrors($e->getError()->message);
        }
        // $plan = \Stripe\Plan::create([
        //   'amount' => $casecost1,
        //   'currency' => 'usd',
        //   'interval' => 'month',
        //   'product' => 'prod_HaJKyyGjXoSLVN',
        // ]);

        // $charge = \Stripe\Subscription::create([
        //   'customer' => 'cus_HJ8NH2EGZvEAUe',
        //   'items' => [['plan' => $plan->id]],
        // ]);
		$data['tx_id'] = $charge->id;
		$data['amount'] = $casecost1;
        $data['type'] = 'User';
		$data['user_id'] = $currunt_user->id;
		$data['responce'] = json_encode($charge);
		$data['paymenttype'] = 2;
		Transaction::create($data);

		User::where('id',$currunt_user->id)->update(['status' => 1]);
		Firm::where('id',$currunt_user->firm_id)->update(['status' => 1]);

		return view('firmadmin.dashboard.conform_case', ["user"=>$currunt_user, "firm_id"=>$currunt_user->firm_id]);


		return redirect('firm/users')->with('success','Payment successfull');

	}

    public function payForUser(Request $request) {
        $card_source = '';
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        extract($_REQUEST);
        $currunt_user = Auth::User();
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        // $charge = \Stripe\Charge::create(['amount' => 2000, 'currency' => 'usd', 'source' => $stripeToken]);
        $casecost1 = $firm->usercost*100;
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
        if(!empty($card_source)) {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd',
              'source' => $card_source
            ]);
        }
        else {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd'
            ]);
        }
        
        User::where('id',$request->id)->update(['status' => 1]);

        $ids = array();
        $ids[] = $request->id;

        $data['tx_id'] = $charge->id;
        $data['amount'] = $casecost1;
        $data['type'] = 'User';
        $data['user_id'] = $currunt_user->id;
        $data['responce'] = json_encode($charge);
        $data['related_id'] = json_encode($ids);
        $data['paymenttype'] = 2;
        Transaction::create($data);
        $request->session()->forget('user_count');
        return redirect('firm/users')->with('success','Payment successfull');
    }

	public function caseIsConform($id) {

        FirmCase::where('firm_id',$id)->update(['status' => 5]);

        $response = Firm::where('id', $id)->first();

        $username = $response->firm_admin_name;
        $useremail =  $response->email;

        $msg = "Hi, $username.<br>";
        $msg .= "Welcome to TILA Case Prep, your account hase been Re-active successfully<br>";
        $msg .= "Please login account with your previous login credentials <br>";
        $msg = EmptyEmailTemplate($msg);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Welcome to TILA Case Prep',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        return redirect('firm/admindashboard')->with('success','Case Reactive Successfully');
    }



	public function payment_succcess() {
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		
		$clients = User::select('users.*')
		->where('firm_id',$currunt_user->firm_id)
		->where('role_id' ,'=', '6')
		->get(); 
		if($clients) {
			return redirect('firm/admindashboard')->with('success','Payment successfull');
		}
		else {
			return view('firmadmin.dashboard.payment_succcess', ["firm"=>$firm, "user"=>$currunt_user]);
		}
	}

	public function createclient() {
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		return view('firmadmin.dashboard.createclient', ["firm"=>$firm, "user"=>$currunt_user]);
	}

	public function schedule_training() {
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		
		return view('firmadmin.dashboard.schedule_training', ["firm"=>$firm, "user"=>$currunt_user]);
	}

	/*public function training_scheduled(Request $request) {
		
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		$case_id = $request->session()->get('case_id');
		$data['case_id'] = $case_id;
		$data['firm_admin_id'] = $currunt_user->id;
		$data['task_type'] = 'schedule_training';
		$data['task'] = 'Schedule Training';
		$data['status'] = 0;
		AdminTask::create($data);
		// $data1['case_id'] = $case_id;
		// $data1['message'] = "Case create By ". $firm->firm_name." Firm";
		// Caselog::create($data1);
		// $request->session()->forget('case_id');
		return redirect('firm/admindashboard')->withInfo('Training Schedule successfully');
	}*/


	public function training_scheduled(Request $request) {
		$currunt_user = Auth::User();
		$firm = Firm::where('id', $currunt_user->firm_id)->first();
		$case_id = $request->session()->get('case_id');
		$data['task_type'] = 'schedule_training';
		$data['task'] = 'Schedule Training';
		if($case_id) {
			$data['case_id'] = $case_id;	
			if($case_id->VP_Assistance) {
				$data['task_type'] = 'Assign_Case';
				$data['task'] = 'Assign Case to VP';
			}
		}
		$data['firm_admin_id'] = $currunt_user->id;
		
		$data['status'] = 0;
		AdminTask::create($data);

		$user = User::where('id',1)->first();
        
		$message = TilaEmailTemplate::select('subtitle','massage')->where('title','Case Notifications')->where('subtitle',"Case Assigned Notifications")->first();

		$username = $user->name;
		$useremail = $user->email;
		$msg = "Hello, $username.<br>";
		$msg = EmptyEmailTemplate($msg);

		$args = array(
		    'bodyMessage' => $msg,
		    'to' => $useremail,
		    'subject' => 'Tila', //$message->subtitle,
		    'from_name' => 'TILA',
		    'from_email' => 'no-reply@tilacaseprep.com'
		);
		send_mail($args);

		// $data1['case_id'] = $case_id;
		// $data1['message'] = "Case create By ". $firm->firm_name." Firm";
		// Caselog::create($data1);
		$request->session()->forget('case_id');
		return redirect('firm/admindashboard')->with('success','Training Schedule successfully');
	}



	

	public function searchshow(Request $request) {

		$value = explode(" ",$request->search);

		$query =  Newclient::where('first_name','LIKE',"%{$value[0]}%");
					if(!empty($value[1])) {
						$query->where('middle_name','LIKE',"%{$value[1]}%");
					}
					if(!empty($value[2])) {
						$query->where('last_name','LIKE',"%{$value[2]}%");
					}
		$record = $query->first();

		if (!empty($record)) {
			return redirect('firm/client/show/'.$record->id);
		}else{

			$value = explode(" ",$request->search);
			$lead_data =  Lead::where('name','LIKE',"%{$value[0]}%")
						->where('last_name','LIKE',"%{$value[1]}%")
						->first();

			if (!empty($lead_data)) {
				return redirect('firm/lead/show/'.$lead_data->id);
			}else{
				return redirect('firm/lead');
			}
			
		}
	}

	public function billing() {
        $data = Auth::User();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        $count = array();
        $count['total_amount'] = QBInvoice::select('*')->where('firm_id', $data->firm_id)->where('status', '!=', 3)->sum('amount');
        $count['paid_amount'] = QBInvoice::select('*')->where('firm_id', $data->firm_id)->where('status', '=', 1)->sum('paid_amount');
        $count['outstanding_amount'] = $count['total_amount'] - $count['paid_amount'];

        if($count['total_amount']) {
            $count['paid_percent'] = intval(($count['paid_amount']/$count['total_amount'])*100);
        }
        else {
            $count['paid_percent'] = 0;
        }
        return view('firmadmin.dashboard.billing', compact('count', 'firm'));
	}

	public function getBillingData(Request $request)
    { 
        $data = Auth::User();
        if(!empty($request->type)) {
            $Transaction = Transaction::select('*')
            ->where('user_id', $data->id)
            ->where('type', $request->type)
            ->get();
        }
        else {
            $Transaction = Transaction::select('*')->where('user_id', $data->id)->get();
        }
        foreach ($Transaction as $key => $value) {
            $Transaction[$key]->name = '';
            if(!empty($value->type) && $value->type != 'User') {
                $case = FirmCase::select('case_type')->where('id', $value->related_id)->first();
                $Transaction[$key]->name = $case->case_type;
            }
            else if(!empty($value->type) && $value->type == 'User' && $value->related_id != '0') {
                $users = User::select('name', 'email')->whereIn('id', json_decode($value->related_id))->get();
                $names = '';
                foreach ($users as $k1 => $v1) {
                    if($k1) {
                        $names .= ', ';
                    }
                    $names .= '('.$v1->name.', '.$v1->email.')';
                }
                $Transaction[$key]->name = $names;
            }
        	$Transaction[$key]->amount = '$'.number_format(($value->amount/100), 2);
        }
        return datatables()->of($Transaction)->toJson();
    }
	public function create_invoice(Request $request) {
		$data = Auth::User();
		$client = Newclient::where('id', $request->firmclient)->first();
		if($data->QBConnect && $request->payment_method == 'Card') {
			
			$config = require_once(base_path('public/QuickBook/v2/config.php'));
			$dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' =>  $config['client_secret'],
                'RedirectURI' => $config['oauth_redirect_uri'],
                'scope' => $config['oauth_scope'],
                'baseUrl' => "https://quickbooks.api.intuit.com"
            ));
            $accessToken = json_decode($data->QBToken);
            $oauth2LoginHelper = new OAuth2LoginHelper($accessToken->getclientID,$accessToken->getClientSecret);
            $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($accessToken->getRefreshToken);
            $newAccessTokenObj->setRealmID($accessToken->getRealmID);
            $newAccessTokenObj->setBaseURL($accessToken->getBaseURL);
            $accessToken = $newAccessTokenObj;
            $dataService->throwExceptionOnError(true);
            $dataService->updateOAuth2Token($accessToken);
            $dataService->setLogLocation("/Users/ksubramanian3/Desktop/HackathonLogs");

            // $customerRef = getCustomerObj($dataService);
            // $itemRef = getItemObj($dataService);

		    $invoiceObj = Invoice::create([
		    	"DocNumber" => "101",
		    	"Line" => [
		    		[
		    			"Description" => "Software Service for Ravikant in India",
		    			"Amount" => $request->casecost,
		    			"DetailType" => "SalesItemLineDetail",
		    			"SalesItemLineDetail" => [
		    				"ItemRef" => [
		    					"value" => 1,
		    					"name" => "New Services Indore"
		    				]
		    			]
		    		]
		    	],
		    	"CustomerRef" => [
		    		"value" => $client->QBCustomerID,
		    		"name" => "Ravikant"
		    	]
		    ]);

            $resultingInvoiceObj = $dataService->Add($invoiceObj);
            $error = $dataService->getLastError();
		    if ($error) {

		    } 
		    else {
		    	$idata = [
			        'user_id' => $client->user_id,
			        'firm_id' => $data->firm_id,
			        'client_id' => $client->id,
			        'Customer_ID' => $client->QBCustomerID,
			        'amount' => $request->casecost,
			        'invoice_id' => $resultingInvoiceObj->Id,
			        'invoice_items' => json_encode($request->invoice_items),
			        'payment_method' => $request->payment_method,
			        'status' => 0,
			    ];
			    QBInvoice::create($idata);
		    }
	        return redirect('firm/billing/invoice')->with('success','Invoice created successfully');
	    }
	    else if($request->payment_method == 'Manual') {
            $idata = [
                    'user_id' => $client->user_id,
                    'firm_id' => $data->firm_id,
                    'client_id' => $client->id,
                    'Customer_ID' => $client->QBCustomerID,
                    'amount' => $request->casecost,
                    'invoice_id' => 0,
                    'invoice_items' => json_encode($request->invoice_items),
                    'payment_method' => $request->payment_method,
                    'status' => 0,
                ];
                QBInvoice::create($idata);
                return redirect('firm/client/client_invoice/'.$request->firmclient)->with('success','Invoice created successfully');
        }
	    else {
	    	return redirect('firm/billing/invoice')->with('error','Please setup your Quickbook account first!');
	    }
	}

	public function invoice() {
		$data = Auth::User();
		$client = User::select('id','name')
        ->where('firm_id',$data->firm_id)
        ->where('role_id',6)
        ->get();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
		return view('firmadmin.dashboard.invoice', compact('client', 'firm'));
	}

	public function getInvoiceData()
    { 
        $data = Auth::User();
        $invoice = QBInvoice::select('qb_invoice.*','users.name as name')
            ->join('new_client', 'qb_invoice.client_id', '=', 'new_client.id')
            ->join('users', 'users.id', '=', 'new_client.user_id')
            ->where('qb_invoice.firm_id',$data->firm_id)
            ->get();
        
        foreach ($invoice as $key => $v) {
            if($v->invoice_for == 'LEAD') {
                $link = url('firm/lead/billing').'/'.$v->lead_id;
            }
            else {
                $link = url('firm/client/client_invoice').'/'.$v->client_id;
            }
            $invoice[$key]->link = $link;
        	$invoice[$key]->st = 'Un-Paid';
            if($v->status == 1) {
            	$invoice[$key]->st = 'Partially Paid';
                if($v->amount == $v->paid_amount) {
                    $invoice[$key]->st = 'Paid';
                }
            }
            else if($v->status == 3) {
            	$invoice[$key]->st = 'Cancel';
            }
            
            if(!empty($v->paid_amount)) {
               $invoice[$key]->outstanding_amount = $v->amount - $v->paid_amount;
               $invoice[$key]->paid_amount = '$'.number_format($v->paid_amount, 2); 
            }
            else {
                $invoice[$key]->paid_amount = '$0.00';
                $invoice[$key]->outstanding_amount = $v->amount;
            }
            $invoice[$key]->amount = '$'.number_format($v->amount, 2);
            $invoice[$key]->outstanding_amount = '$'.number_format($invoice[$key]->outstanding_amount, 2);
            $scheduled = SchedulePayment::select('id')->where('schedule_for', 'CLIENT')->where('related_id', $v->id)->first();
            $invoice[$key]->scheduled = 'No';
            if(!empty($scheduled)) {
                $invoice[$key]->scheduled = 'Yes';
            }

        }
        return datatables()->of($invoice)->toJson();
        
    }

    public function getScheduledInvoiceData()
    { 
        $data = Auth::User();
        $invoice = QBInvoice::select('qb_invoice.*', 'schedule_payment.*')
            //->join('users', 'qb_invoice.user_id', '=', 'users.id')
            ->where('qb_invoice.firm_id',$data->firm_id)
            ->where('qb_invoice.invoice_for','CLIENT')
            ->join('schedule_payment', 'schedule_payment.invoice_id', '=', 'qb_invoice.id')
            // ->where('qb_invoice.firm_id',$data->firm_id)
            ->get();
        
        foreach ($invoice as $key => $v) {
            if($v->invoice_for == 'LEAD') {
                $link = url('firm/lead/billing').'/'.$v->lead_id;
            }
            else {
                $link = url('firm/client/client_scheduled').'/'.$v->client_id;
            }
            $invoice[$key]->link = $link;
            $invoice[$key]->st = 'Un-Paid';
            if($v->status == 1) {
                $invoice[$key]->st = 'Paid';
            }
            else if($v->status == 3) {
                $invoice[$key]->st = 'Cancel';
            }
            $invoice[$key]->amount = '$'.number_format($v->amount, 2);
            $invoice[$key]->recurring_amount = '$'.number_format($v->recurring_amount, 2);
        }
        return datatables()->of($invoice)->toJson();
        
    }

    public function create($id) {
    	$data = Auth::User();
		$client = User::select('users.*', 'new_client.*')
		->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('users.id',$id)
        ->where('users.role_id',6)
        ->first();
        // pre($client);
        // die();
        // foreach ($client as $key => $value) {
        // 	$client[$key]->birth_address = json_decode($value->birth_address);
        // }
        $firm = Firm::where('id', $data->firm_id)->first();
        // pre($firm);
        $account_id = get_user_meta(Auth::User()->id, 'account_id');
        $SECRET_KEY = get_user_meta(Auth::User()->id, 'SECRET_KEY');
        $is_card = false;
        if(!empty($account_id) && !empty($SECRET_KEY)) {
        	$is_card = true;
        }
        return view('firmadmin.dashboard.createinvoice', compact('client', 'firm', 'is_card'));
    }

    public function edit_invoice($id) {
    	$invoice = QBInvoice::select('*')->where('id', $id)->first();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('new_client.id',$invoice->client_id)
        ->first();
        $firm = Firm::where('id', $invoice->firm_id)->first(); 
        return view('firmadmin.dashboard.edit_invoice', compact('invoice', 'client', 'firm'));
    }

    public function update_invoice(Request $request) {
    	//pre($request->all());
    	$idata = [
	        'amount' => $request->casecost,
	        'invoice_items' => json_encode($request->invoice_items),
            'client_name' => $request->client_name,
            'client_address' => $request->client_address,
            'tax_id' => $request->tax_id,
	        'payment_method' => $request->payment_method,
	        'status' => 0,
	    ];
	    QBInvoice::where('id', $request->id)->update($idata);
	    return redirect('firm/billing/invoice')->with('success','Invoice updated successfully');
    }

    public function paid_invoice($id) {
    	QBInvoice::where('id', $id)->update(['status' => 1]);
    	return redirect('firm/billing/invoice')->with('success','Invoice mark ad paid successfully');
    }

    public function unpaid_invoice($id) {
    	QBInvoice::where('id', $id)->update(['status' => 0]);
    	return redirect('firm/billing/invoice')->with('success','Invoice mark ad un-paid successfully');
    }

    public function cancel_invoice($id) {
    	QBInvoice::where('id', $id)->update(['status' => 3]);
    	return redirect('firm/billing/invoice')->with('success','Invoice cancel successfully');
    }

    public function scheduled() {
        $data = Auth::User();
        $client = User::select('id','name')
        ->where('firm_id',$data->firm_id)
        ->where('role_id',6)
        ->get();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        return view('firmadmin.dashboard.scheduled', compact('client', 'firm'));
    }

    public function acceptpayment() {
        $data = Auth::User();
        $firm_id = $data->firm_id;
        $client = User::select('id','name')
        ->where('firm_id',$data->firm_id)
        ->where('role_id',6)
        ->get();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        $invoice = QBInvoice::select('*')
                ->where('invoice_for', 'CLIENT')
                ->where('firm_id', $firm_id)
                ->where('status', '!=', 3)->get();
        return view('firmadmin.dashboard.acceptpayment', compact('client', 'invoice', 'firm'));
    }

    public function exit_payment_page(Request $request)
    {
        $firm_user = $request->session()->get('user_count');
        $newarr = array();
        if(!empty($firm_user)) {
            foreach ($firm_user as $k => $v) {
                User::where('id', $v->id)->delete();
            }
        }
        $request->session()->forget('user_count');

        return redirect('firm/users');
        // ->with('success','User deleted successfully!')

    }

    public function transactions() {
        $data = Auth::User();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        $t = Transaction::select('amount')
            ->where('user_id', $data->id)
            ->sum('amount');
        $t = '$'.number_format(($t/100), 2);
        return view('firmadmin.dashboard.transactions', compact('data', 'firm', 't'));
    }

    public function getFirmTransactions(Request $request)
    { 
        $data = Auth::User();

        $q = "SELECT t.*, u1.name as username, f.firm_name, f.account_type, c.client_id, c.case_type, u2.name as clientname, qb.description FROM `transactions` as t
                LEFT JOIN `users` as u1 ON t.user_id = u1.id
                LEFT JOIN `firms` as f ON f.id = u1.firm_id
                LEFT JOIN `case` as c ON c.id = t.related_id 
                LEFT JOIN `users` as u2 ON c.client_id = u2.id
                LEFT JOIN `qb_invoice` as qb ON t.related_id = qb.id AND t.type = 'Invoice'
                WHERE 1 = 1 AND t.user_id = '$data->id'";

        if(!empty($request->type)) {
            $q .= " AND t.type = '$request->type'";
        }
        if(!empty($request->pastdate)) {
            $d = $request->pastdate;
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime("-$d day", strtotime($to)));
            $to = $to.' 23:59:59';
            $q .= " AND t.created_at BETWEEN '$from' AND '$to'";
        }
        if(!empty($request->from) && !empty($request->to)) {
            $f = $request->from;
            $t = $request->to;
            $f = explode('/', $request->from);
            $t = explode('/', $request->to);

            $from = $f[2] . '-' . $f[0] . '-' . $f[1];
            $to = $t[2] . '-' . $t[0] . '-' . $t[1].' 23:59:59';
            $q .= " AND t.created_at BETWEEN '$from' AND '$to'";
        }
        //echo $q;
        $Transaction = DB::select(DB::raw($q));

        foreach ($Transaction as $key => $value) {
            $Transaction[$key]->name = '';
            if(!empty($value->type) && $value->type == 'User') {
                $Transaction[$key]->name = 'Monthly user cost';
                $Transaction[$key]->description = 'Monthly user cost';
            }
            else if(!empty($value->type) && $value->type == 'Case') {
                $Transaction[$key]->description = $value->case_type;
            }
            else if(!empty($value->type) && $value->type == 'Translation') {
                $Transaction[$key]->description = 'Translation Service';
            }
            else if(!empty($value->type) && $value->type == 'Additional Service') {
                $Transaction[$key]->description = 'Additional Service';
            }

            if(empty($value->clientname)) {
                $Transaction[$key]->clientname = 'N/A';
            }
            if(empty($value->case_type)) {
                $Transaction[$key]->case_type = 'N/A';
            }
            if(empty($value->description)) {
                $Transaction[$key]->description = 'N/A';
            }
            $Transaction[$key]->p_method = 'N/A';
            $responce = json_decode($value->responce);
            // pre($responce);
            if(!empty($responce)) {
                if(!empty($responce->source->last4)) {
                   $Transaction[$key]->p_method = '************'.$responce->source->last4; 
                }
                else if(!empty($responce->method->number)) {
                   $Transaction[$key]->p_method = $responce->method->number; 
                }
            }
            $Transaction[$key]->amount = '$'.number_format(($value->amount/100), 2);
        }
        return datatables()->of($Transaction)->toJson();
    }

    public function pay_for_cms(Request $request) {
        $card_source = '';
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        extract($_REQUEST);
        $currunt_user = Auth::User();
        $firm = Firm::where('id', $currunt_user->firm_id)->first();
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $casecost1 = $request->amount*100;
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
        if(!empty($card_source)) {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd',
              'source' => $card_source
            ]);
        }
        else {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd'
            ]);
        }

        $ids = array();
        $ids[] = $currunt_user->id;

        $data['tx_id'] = $charge->id;
        $data['amount'] = $casecost1;
        $data['type'] = 'User';
        $data['user_id'] = $currunt_user->id;
        $data['responce'] = json_encode($charge);
        $data['related_id'] = json_encode($ids);
        $data['paymenttype'] = 2;
        Transaction::create($data);

        Firm::where('id', $currunt_user->firm_id)->update(['account_type' => 'CMS']);
        foreach ($request->user_id as $k => $v) {
            $kk = 'user_permmition'.$v;
            $role_id = $request->$kk;
            User::where('id',$v)->update(['role_id' => $role_id]);
        }
        return redirect($request->redirect_url)->with('success', 'Paid for CMS successfully');
    }

    public function upgradetocms() {
        $data = Auth::User();
        $firm = Firm::where('id', $data->firm_id)->first();
        $users = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
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
        return view('firmadmin.dashboard.upgradetocms', compact('data', 'users', 'firm', 'card'));
    }

    public function request_to_delete() {
        $currunt_user = Auth::User();
        $data['case_id'] = $currunt_user->firm_id;
        $data['firm_admin_id'] = $currunt_user->id;
        $data['task_type'] = 'DELETE_ACCOUNT';
        $data['task'] = 'Delete Account Request';
        $data['mytask'] = $currunt_user->name.' has requted to deactivate their account';
        $data['status'] = 0;
        AdminTask::create($data);
        return redirect('profile')->with('success', 'Request successfully');
    }
}

