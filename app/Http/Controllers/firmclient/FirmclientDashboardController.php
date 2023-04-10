<?php
namespace App\Http\Controllers\firmclient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\FirmCase;
use App\Models\ClientInformation;
use App\Models\QBInvoice;
use App\Models\ClientTask;
use App\Models\DocumentRequest;
use App\Models\ClientNotes;
use App\Models\Newclient;
use App\Models\Event;
use App\Models\Questionnaire;
use App\User;
use Session;

class FirmclientDashboardController extends Controller
{
	public function index(Request $request)
	{
		$currunt_user = Auth::User();

        $client = Newclient::where('user_id', $currunt_user->id)->first();
		$count = [];

        $count['total_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $client->id)->where('status', '!=', 3)->sum('amount');

        $count['paid_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $client->id)->where('status', '=', 1)->sum('paid_amount');
        
        $count['outstanding_amount'] = $count['total_amount'] - $count['paid_amount'];

        $count['paid_amount'] = '$'.number_format($count['paid_amount'], 2);
        $count['outstanding_amount'] = '$'.number_format($count['outstanding_amount'], 2);
    
		$count['case_opned'] = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
            ->join('users', 'case.user_id', '=', 'users.id')
            ->join('users as ur', 'case.client_id', '=', 'ur.id')
            ->where('case.client_id',$currunt_user->id)
            ->whereNotIN('case.status',[8, 9])
            ->count();   
 
        $query = DB::getQueryLog();    

        $count['events'] = Event::select('*')
                ->where('related_id', $client->id)
                ->where('title', "CLIENT")
                ->orderBy('id', 'DESC')
                ->get();

        if(!empty($count['events'])) {
            foreach ($count['events'] as $k => $e) {
                $wcw = json_decode($e->who_consult_with);
                $meetingwith = '';
                if(!empty($wcw)) {
                    foreach ($wcw as $k1 => $u) {
                        if(!empty($meetingwith)) {
                            $meetingwith .= ', ';
                        }
                        $meetingwith .= getUserName($u)->name;
                    }
                }
                $count['events'][$k]->meetingwith = $meetingwith;
            }
        }

        $count['task'] = ClientTask::select('*')
                ->where('related_id', $client->id)
                ->where('task_for', 'CLIENT')
                ->get();

        $ID = Auth::user()->id;
        $type = Auth::user()->role_id;
        
        $q="select * from notifications where notifiable_id='".$ID."' and isread=0 order by created_at DESC";
        $Notif = DB::select($q);
        $count['message']['textmsg'] = array();

        foreach ($Notif as $v) {
            $msg = json_decode($v->data);
            if ($msg->message->type == 1 || $msg->message->type == 2) {
                $count['message']['textmsg'][] = $msg->message;
            }
        }

        $ids = AdminTask::select('admintask.allot_user_id as id', 'users.name', 'users.contact_number', 'users.email')
               ->where('admintask.task_type', 'Assign_Case')
               ->join('users', 'users.id', '=', 'admintask.allot_user_id')
               ->join('case', 'case.id', '=', 'admintask.case_id')
               ->where('case.client_id', $currunt_user->id)
               ->groupBy('users.id')
               ->get();
               
        // $ids = User::select('*')
        //     // ->join('firms', 'firms.email', '=', 'users.email')
        //     ->whereIn('role_id',[4, 5])
        //     ->where('firm_id',$currunt_user->firm_id)
        //     ->get(); 
        // pre($ids);
        //        die();
        return view('firmadmin.firmclient.dashboard.index', compact('count', 'ids'));
	}

	public function clientcase(Request $request) {
        return view('firmadmin.firmclient.dashboard.case');
    }

    public function questionnaire(Request $request) {
        $data = Auth::User();
        $case = FirmCase::select('*')
                ->where('client_id', $data->id)
                ->orderBy('id', 'DESC')
                ->first();
        $ques = Questionnaire::select('*')
                ->where('client_id', $data->id)
                ->get();
        return view('firmadmin.firmclient.dashboard.questionnaire', compact('data', 'case', 'ques'));
    }

    public function case_documents(Request $request) {
        $data = Auth::User();
        $case = FirmCase::select('*')
                ->where('client_id', $data->id)
                ->orderBy('id', 'DESC')
                ->first();
        $client = Newclient::select('*')->where('user_id', $data->id)->first();
        return view('firmadmin.firmclient.dashboard.case_documents', compact('data', 'case', 'client'));
    }

    public function getClientCaseData()
    { 
        $currunt_user = Auth::User();
        $cases = FirmCase::select('case.*','case.id as case_id','case.status as case_status', 'case.created_at as case_created_at', 'cp.*', 'f.firm_name as firm_name')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->join('firms as f', 'f.id', '=', 'case.firm_id')
            ->where('case.client_id',$currunt_user->id)
            ->orderBy('case_id', 'DESC')
            ->get(); 
        foreach ($cases as $key => $value) {
            if($value->CourtDates == "0") {
                $cases[$key]->CourtDates = 'Not set';
            }
            $cases[$key]->case_status = GetCaseStatus($value->case_status);
            if(empty($value->VP_Assistance)) {
                $cases[$key]->case_cost = 'Self Managed';
            }
        }
        return datatables()->of($cases)->toJson();
        
    }

    public function show($id) {
        $currunt_user = Auth::User();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first(); 
        $task = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'CASE')->get();  
        $firm = Firm::select('*')->where('id', $currunt_user->firm_id)->first(); 
        $data['totla_tasks'] = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'CASE')->count();;
        $data['totla_documents'] = DocumentRequest::select('*')->where('case_id', $case->case_id)->count();
        $data['totla_notes'] = ClientNotes::select('*')->where('related_id', $case->case_id)->where('task_for', 'CASE')->count();
        return view('firmadmin.firmclient.dashboard.show', compact('case', 'firm', 'task', 'data'));
    }

    public function casetasks($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        $task = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'CASE')->get();
        return view('firmadmin.firmclient.dashboard.casetasks', compact('case' , 'task', 'firm'));
    }

    public function add_casetasks($id)
    {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        return view('firmadmin.firmclient.dashboard.add_casetasks', compact('case' , 'firm'));
    }

    public function insert_newtask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('firm/clientcase/add_casetasks/'.$request->id)->withInfo('Mendatory fields are required!');
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
            'status' => 0
        ];
        ClientTask::create($data);
        return redirect('firm/clientcase/casetasks/'.$request->id)->withInfo('Task created successfully');
    }
    
    public function casenotes($id)
    {
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $case->case_id)
                ->where('client_notes.task_for', 'CASE')
                ->get();
        return view('firmadmin.firmclient.dashboard.casenotes', compact('case', 'firm', 'notes_list'));
    }

    public function casefamily($id)
    {
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        
        $case_id = $id;

        $client = array();
        
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'CaseID')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

        $derivative_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'derivative')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        $interpreter_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'interpreter')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        $petitioner_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'petitioner')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        $Co_Sponsor = array();
        $Co_Sponsor_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'Co_Sponsor')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->first();
        $Household_Member = array();
        $Household_Member_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'Household_Member')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->first();

        if (!empty($Co_Sponsor_arr)) {
            $Co_Sponsor = $Co_Sponsor_arr;
        }
        
        if (!empty($Household_Member_arr)) {
            $Household_Member = $Household_Member_arr;
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('users.firm_id' ,'=', $case->firm_id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        return view('firmadmin.firmclient.dashboard.casefamily', compact('case', 'firm', 'family_list', 'derivative_list', 'interpreter_list', 'Co_Sponsor', 'Household_Member','family_alllist', 'admintask', 'client', 'petitioner_list'));
    }

    public function caseuser() {
        $currunt_user = Auth::User();
        $cases = FirmCase::select('case.*','case.id as case_id', 'users.*')
            ->join('users', 'users.id', '=', 'case.user_id')
            ->where('case.client_id',$currunt_user->id)
            ->get();
        // $tasks = FirmCase::select('admintask.*','admintask.case_id as case_id', 'users.*')
        //     ->join('users', 'users.id', '=', 'case.user_id')
        //     ->where('case.client_id',$currunt_user->id)
        //     ->get(); 
        // pre($cases);
        // die();
        return view('firmadmin.firmclient.dashboard.caseuser', compact('cases'));
    }

    

    public function first_reset_password() {
        
        return view('firmadmin.firmclient.dashboard.firstresetpassword');
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


        $request->session()->put('is_reset_pass', '1');

        //Auth::logout();
        return redirect(route('firm.clientdashboard'))->with('success','Your password is reset successfully');
    }

    public function invoice() {
        $data = Auth::User();
        return view('firmadmin.firmclient.dashboard.invoice');
    }

    public function getInvoiceData()
    { 
        $data = Auth::User();
        $client = Newclient::select('*')->where('user_id', $data->id)->first();
        $invoice = QBInvoice::select('qb_invoice.*','qb_invoice.client_name as name', 'schedule_payment.id as sid')
            ->leftJoin('schedule_payment', 'qb_invoice.id', '=', 'schedule_payment.invoice_id')
            ->where('qb_invoice.client_id',$client->id)
            ->get();
        
        foreach ($invoice as $key => $v) {
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

            // if($v->payment_method == 'Card') {
            //     $invoice[$key]->payment_method = 'Card via LawPay';
            // }
            $invoice[$key]->scheduled = 'No';
            if(!empty($v->sid)) {
                $invoice[$key]->scheduled = 'Yes';
            }
            $invoice[$key]->amount = '$'.number_format($v->amount, 2);
            $invoice[$key]->paid_amount = '$'.number_format($v->paid_amount, 2);
        }
        return datatables()->of($invoice)->toJson();
        
    }

    public function payForInvoice(Request $request) {
        $tx_id = 0;
        $t_amount = 0;
        $array = array();
        $ch = curl_init();
        $currunt_user = Auth::User();
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $currunt_user->firm_id)
                ->first();
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
                    "reference" => $request->ctype.$invoice->client_id 
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
                        "reference" => $request->ctype.$invoice->client_id
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
        else if(!empty($request->payment_method) && $request->payment_method == 'E-Check') {

            $account_id = get_user_meta($firm->uid, 'echeck');

            $t_amount = $request->check_amount*100;
            $amt1 = $request->check_amount;
            $headers = array();
            $headers[] = 'Content-Type: application/json';
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

            if(!empty($invoice->paid_amount)) {
                $amt1 = $request->check_amount+$invoice->paid_amount;
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
        return redirect(route('firm.firmclient.billing.invoice'))->with('success','Paid successfully');
    }

    public function payForInvoice1(Request $request) {
        $tx_id = 0;
        $t_amount = 0;
        $array = array();
        $ch = curl_init();
        $currunt_user = Auth::User();
        $firm = Firm::select('firms.*', 'users.id as uid')
                ->join('users', 'firms.email', '=', 'users.email')
                ->where('firms.id', $currunt_user->firm_id)
                ->first();
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
                    "reference" => $request->ctype.$invoice->client_id 
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
                        "reference" => $request->ctype.$invoice->client_id
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
        else if(!empty($request->payment_method) && $request->payment_method == 'E-Check') {

            $account_id = get_user_meta($firm->uid, 'echeck');

            $t_amount = $request->amount*100;
            $amt1 = $request->amount;
            $headers = array();
            $headers[] = 'Content-Type: application/json';
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

            if(!empty($invoice->paid_amount)) {
                $amt1 = $request->amount+$invoice->paid_amount;
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
        return redirect(route('firm.mybalance'))->with('success','Paid successfully');
    }

    public function viewinvoice($id) {
        // echo "========= $id ";
        $invoice = QBInvoice::select('*')->where('id', $id)->first();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('new_client.id',$invoice->client_id)
        // ->where('users.role_id',6)
        ->first();
        $firm = Firm::where('id', $invoice->firm_id)->first();
        $transaction = Transaction::select('*')->where('type', 'Invoice')->where('related_id', $id)->get();
        return view('firmadmin.firmclient.dashboard.viewinvoice', compact('invoice', 'client', 'firm', 'transaction'));
    }

    public function mybalance() {
        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('new_client.user_id',$data->id)
        // ->where('users.role_id',6)
        ->first();
        $count = [];

        $count['payment_info'] = QBInvoice::select('qb_invoice.*', 'transactions.created_at as payment_date')
                    ->where('qb_invoice.client_id', $client->id)
                    ->join('transactions', 'transactions.related_id', '=', 'qb_invoice.id')
                    ->where('transactions.type', 'Invoice')
                    ->whereNotNull('qb_invoice.paid_amount')
                    ->orderBy('qb_invoice.id', 'DESC')
                    ->first();
        $count['schedule_invoice'] = QBInvoice::select('qb_invoice.*','schedule_payment.*', 'schedule_payment.id as sid')
            ->join('schedule_payment', 'qb_invoice.id', '=', 'schedule_payment.invoice_id')
            ->where('qb_invoice.client_id',$client->id)
            ->first();

        

        $count['total_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $client->id)->where('status', '!=', 3)->sum('amount');

        $count['paid_amount'] = QBInvoice::select('*')->where('invoice_for', 'CLIENT')->where('client_id', $client->id)->where('status', '=', 1)->sum('paid_amount');
        
        $count['outstanding_amount'] = $count['total_amount'] - $count['paid_amount'];

        $count['paid_amount'] = '$'.number_format($count['paid_amount'], 2);
        $count['outstanding_amount'] = '$'.number_format($count['outstanding_amount'], 2);

        $count['invoice'] = QBInvoice::select('*')->where('client_id', $client->id)->where('status', '!=', 3)->get();

        return view('firmadmin.firmclient.dashboard.mybalance', compact('count'));
    }

    public function complete_task($tid) {
        ClientTask::where('id', $tid)->update(['status' => 1]);
        return redirect(url('firm/clientdashboard'))->with('success','Task completed', 'schedule_invoice');
    }
}

