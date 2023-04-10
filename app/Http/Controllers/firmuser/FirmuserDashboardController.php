<?php
namespace App\Http\Controllers\firmuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\User;
use Twilio\Rest\Client;
use App\Models\FirmCase;
use App\Models\Lead;
use Session;

class FirmuserDashboardController extends Controller
{
	public function index_bk(Request $request)
	{
		$currunt_user = Auth::User();
		$responce = User::select('status')->where('id',$currunt_user->id)->first();
		if ($responce->status == 0) {
		
			$firm = Firm::where('id', $currunt_user->firm_id)->first();
			return view('firmadmin.dashboard.payment_method1', ["firm"=>$firm, "user"=>$currunt_user]);
		}
		if($currunt_user->first_login == '1' && $currunt_user->role_id == '4') {
			return redirect('firm/FirmAccountSetUp')->with('info','Please Update Firm Account');
		}

		$admintask = AdminTask::select('admintask.*','firms.firm_name','cluser.id as clientid','cluser.name as clientname','cluser.email as clientemail','case.case_file_path','vauser.id as vauserid','vauser.name as vausername','vauser.email as vauseremail')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users as cluser', 'case.client_id', '=', 'cluser.id')
        ->join('users as vauser', 'admintask.firm_admin_id', '=', 'vauser.id')
        ->where('admintask.firm_admin_id',$currunt_user->id)
        ->get(); 

        $count = [];

        $case_opned = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
            ->join('users', 'case.user_id', '=', 'users.id')
            ->join('users as ur', 'case.client_id', '=', 'ur.id')
            ->where('case.firm_id',$currunt_user->firm_id)
            ->where('case.status',0)
            ->count();

        $count['case_opned'] = $case_opned;

        $case_closed = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
            ->join('users', 'case.user_id', '=', 'users.id')
            ->join('users as ur', 'case.client_id', '=', 'ur.id')
            ->where('case.firm_id',$currunt_user->firm_id)
            ->where('case.status',1)
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
        $count['messages'] = array();
        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        $ids = strtolower(base64_encode($currunt_user->firm_id));
        try {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                ->channels($ids)
                ->fetch();
            $chanel_id = $channel->sid;
            $messages = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                                    ->channels($chanel_id)
                                    ->messages
                                    ->read(array('order' => 'desc'), 5);
            
	        foreach ($messages as $key => $message) {
	        	$u = User::select('users.name')
			        ->where('email',$message->from)
			        ->first(); 
			    $count['messages'][] = array(
	            							'msg' => $message->body,
	            							'from' => $u->name,
	            							'dateCreated' => timeago($message->dateCreated->format('Y-m-d H:i:sP'))
	            						);
	        }
        } 
        catch (\Twilio\Exceptions\RestException $e) {
            $count['messages'] = array();
        }
        
		return view('firmadmin.dashboard.index', compact('admintask', 'count'));
	}

    public function index(Request $request)
    {
        return redirect(route('firm.admindashboard'));
        $currunt_user = Auth::User();
        $responce = User::select('status')->where('id',$currunt_user->id)->first();
        if ($responce->status == 0) {
        
            $firm = Firm::where('id', $currunt_user->firm_id)->first();
            $amount = 65;
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

        $admintask = AdminTask::select('admintask.*','firms.firm_name','cluser.id as clientid','cluser.name as clientname','cluser.email as clientemail','case.case_file_path','vauser.id as vauserid','vauser.name as vausername','vauser.email as vauseremail')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users as cluser', 'case.client_id', '=', 'cluser.id')
        ->join('users as vauser', 'admintask.firm_admin_id', '=', 'vauser.id')
        ->where('admintask.firm_admin_id',$currunt_user->id)
        ->where('admintask.status',0)
        ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
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
        }
        $count = [];

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
        $count['messages'] = array();
      
        
        return view('firmadmin.dashboard.index', compact('admintask', 'count'));
    }

    public function first_reset_password() {

        return view('firmadmin.firmuser.dashboard.firstresetpassword');
    }


    public function update_first_password(Request $request) {

        $validator = Validator::make($request->all(), [
            'password'         => 'required',
            'conform_password' => 'required|same:password'
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator);
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

        //Auth::logout();
        return redirect(route('firm.admindashboard'))->with('success','Your password is reset successfully');
    }
}