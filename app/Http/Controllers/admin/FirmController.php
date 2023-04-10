<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\{UserUpdateRequest,UserAddRequest};
use Spatie\Permission\Models\Role;
use App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\MasterFirmEmailTemplate;
use App\Models\TilaEmailTemplate;
use App\Models\FirmSetting;
use App\Models\AdminTask;
use App\Models\Newclient;
use App\Models\Transaction;
use App\Models\DocumentRequest;
use App\Models\Log;
use App\Models\Lead;
use App\Models\CaseType;
use App\Models\ClientNotes;
use Carbon\Carbon;
/* --------------QuickBook--------------- */
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Exception\ServiceException;

/* --------------QuickBook--------------- */

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class FirmController extends Controller
{
    public function __construct()
    {
         require_once(base_path('public/QuickBook/v2/vendor/autoload.php'));
          require_once(base_path('public/QuickBook/gettoken.php'));
        //$this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $firms = Firm::select()->get();
        //$this->authorize(User::class, 'index');
        return view('admin.firm.index', ["firms"=>$firms, 'total' => count($firms)]);
    }


    public function getData(Request $request)
    { 
        if(!empty($request->firmtype) && empty($request->firmstatus)) {
            $firms = Firm::select('firms.*', 'users.first_login', 'users.is_reset_pass')
                    ->where('firms.account_type', $request->firmtype)
                    ->join('users', 'users.email', 'firms.email')
                    ->get();
        }
        else if(empty($request->firmtype) && !empty($request->firmstatus)) {
            if($request->firmstatus == 2) {
                $request->firmstatus = 0;
            }
            if($request->firmstatus == 0) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass')
                    ->join('users', 'users.email', 'firms.email')
                    ->where('firms.status', 0)
                    ->get();
            }
            else if($request->firmstatus == 1) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass')
                    ->join('users', 'users.email', 'firms.email')
                    ->where('firms.status', 1)
                    ->where('users.first_login', 0)
                    ->where('users.is_reset_pass', 1)
                    ->get();
            }
            else if($request->firmstatus == 3) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass', 't.id as txid')
                    ->join('users', 'users.email', 'firms.email')
                    ->leftJoin('transactions as t', 't.user_id', 'users.id')
                    ->where('users.first_login', 1)
                    ->where('users.is_reset_pass', 0)
                    ->get();
            }
        }
        else if(!empty($request->firmtype) && !empty($request->firmstatus)) {
            if($request->firmstatus == 2) {
                $request->firmstatus = 0;
            }
            if($request->firmstatus == 0) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass')
                    ->join('users', 'users.email', 'firms.email')
                    ->where('firms.status', 0)
                    ->where('firms.account_type', $request->firmtype)
                    ->get();
            }
            else if($request->firmstatus == 1) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass')
                    ->join('users', 'users.email', 'firms.email')
                    ->where('firms.account_type', $request->firmtype)
                    ->where('firms.status', 1)
                    ->where('users.first_login', 0)
                    ->where('users.is_reset_pass', 1)
                    ->get();
            }
            else if($request->firmstatus == 3) {
                $firms = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass')
                    ->join('users', 'users.email', 'firms.email')
                    ->where('firms.account_type', $request->firmtype)
                    ->where('users.first_login', 1)
                    ->where('users.is_reset_pass', 0)
                    ->get();
            }
        }
        else {
            $firms = Firm::select('firms.*', 'users.first_login', 'users.is_reset_pass')
                    // ->where('firms.account_type', $request->firmtype)
                    ->join('users', 'users.email', 'firms.email')
                    ->get();
        }
        $firms_ids = array();
        $all_firms = array();
        $firms1 = Firm::select('firms.*', 'users.first_login as first_login', 'users.is_reset_pass as is_reset_pass', 't.id as txid')
            ->join('users', 'users.email', 'firms.email')
            ->where('firms.account_type', 'CMS')
            ->leftJoin('transactions as t', 't.user_id', 'users.id')
            ->where('users.first_login', 0)
            ->where('users.is_reset_pass', 1)
            ->where('firms.status', 1)
            ->get();
        if(!empty($firms1) ) {
            foreach ($firms1 as $key => $value) {
                $value->stat = ($value->status == 1) ? "Active" : "Inactive";
                if($value->account_type == 'CMS' && empty($value->txid) && $request->firmstatus != '0' && $request->firmtype != 'VP Services') {
                    $value->stat = 'Pending';
                    if($request->firmstatus == 3 || empty($request->firmstatus)) {
                        $all_firms[] = $value;
                    }
                    $firms_ids[] = $value->id;
                }
            }
        }
        foreach ($firms as $key => $value) {
            $value->stat = ($value->status == 1) ? "Active" : "Inactive";
            if($value->first_login == '1' && $value->is_reset_pass == 0 && $value->status == 1) {
                $value->stat = 'Pending';
            }
            
            if(!in_array($value->id, $firms_ids)) {
                $all_firms[] = $value;
            }
            
        }
        
        return datatables()->of($all_firms)->toJson();        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.firm.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_firm(Request $request) {
        $validator = Validator::make($request->all(), [
            'firm_name' => 'required|string',
            'account_type' => 'required|string',
            'email' => 'required|string|email|unique:firms|unique:users',
            'firm_admin_name' => 'required|string'
        ]);

        $data = [
            'firm_name' => $request->firm_name,
            'account_type' => $request->account_type,
            'email' => $request->email,
            'firm_admin_name' => $request->firm_admin_name,
            'cost_type' => $request->cost_type,
            'usercost' => $request->usercost,
            'status' => 1
        ];

        if ($validator->fails()) {
            return redirect()->back()->with(['data' => $data])->withErrors($validator);
        }

        
        if($request->cost_type == 'Specific Cost') {
            $data['usercost'] = $request->usercost;
        }
        else {
            $data['usercost'] = 65;
        }
        $CID = $this->QBCreateClient($data);

        //Newclient::where('id', $newclient->id)->update(['QBCustomerID' => $CID]);
        
        $Firm = Firm::create($data);
        $pass = str_random(8);
        $data1 = [
            'name' => $request->firm_admin_name,
            'role' => 4,
            'email' => $request->email,
            'password' => Hash::make($pass),
            'password_confirmation' => Hash::make($pass),
            'role_id' => 4,
            'firm_id' => $Firm->id
        ];
        $user1 = User::create($data1);
        update_user_meta($user1->id, 'QBCustomerID', $CID);
        $logdata = [
            'title' => "FIRM",
            'related_id' => $Firm->id,
            'message' => "TILA Admin create a firm ".$request->firm_name
        ];
        Log::create($logdata);


        /* firm logo upload start */
        if(!empty($request->firm_logo_path))
        {
            $firm_logo = Storage::put('firm_logo', $request->firm_logo_path);
            if($firm_logo){
                Firm::where('id', $Firm->id)->update(['firm_logo_path' => $firm_logo]);
            }
        }
        /* firm logo upload close */

        $master_template = MasterFirmEmailTemplate::get();
        $master_record = [];

        foreach ($master_template as $key => $value) {
            $master_record = [
                'firm_id' => $Firm->id,
                'category' => "EMAIL",
                'title' => $value->title,
                'message' => $value->message
            ];
            FirmSetting::create($master_record);    
        }
        
        $role = Role::find(4);
        if($role)
        {
            $user1->assignRole($role);
        }

        $username = $request->firm_admin_name;
        $useremail =  $request->email;

        $content = TilaEmailTemplate::select('massage')->where('subtitle',"New Firm Account Welcome Email")->first();

        $msg = "Email : $useremail <br>";
        $msg .= "Password : $pass <br>";
        $msg .= $request->firm_name ." ".$content->massage; 
        
        $msg .= "Thank you <br>";
        $msg .= "TILA case prep";

        /* --------------------------Firm Create Account Welcome Email--------------------------- */
        $remove = array(
            'FirmName' => $request->firm_name,
        );
        $email = EmailTemplate(31, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $useremail,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        // send_mail($args);
        /* --------------------------Firm Create Account Welcome Email--------------------------- */
        /* --------------------------Firm Create Account Details Email--------------------------- */
        $LoginPage = url('login');
        $remove = array(
            'FirmName' => $request->firm_name,
            'UserN'=>$useremail,
            'UserP'=>$pass,
            'LoginPage' => $LoginPage
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
        /* --------------------------Firm Create Account Details Email--------------------------- */
              
        if ($Firm) {
            return redirect('admin/firm')->with('success','The creation was successful and an email was sent to firm admin.');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAddRequest $request)
    {
        $user = User::create($request->all());
        $role = Role::find($request->role);
        if($role)
        {
            $user->assignRole($role);
        }
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "<pre>";
        print_r($id);
        echo "</pre>";
    }

    public function firm_details($id)
    {
        $firm = Firm::where('id', $id)->first();
        $firmadmin = User::where('firm_id', $id)->where('role_id', 4)->first();
        $data = array();
        $data['total_case'] = FirmCase::select('*')->where('firm_id', $id)->count();
        $data['vp_case'] = FirmCase::select('*')->where('VP_Assistance', 1)->where('firm_id', $id)->count();
        $data['self_case'] = $data['total_case']-$data['vp_case'];
        $data['total_client'] = Newclient::select('*')->where('firm_id', $id)->count();
        $data['total_user'] = User::where('firm_id', $id)->where('role_id', 5)->count();
        $data['total_lead'] = Lead::select('*')->where('firm_id', $id)->count();
        $data['total_billing'] = Transaction::select('*')->where('user_id', $firmadmin->id)->sum('amount');
        $data['total_billing'] = $data['total_billing']/100;
        // $vp_total = Transaction::select('*')
        //             ->where('transactions.user_id', $firmadmin->id)
        //             ->join('case', 'case.id', '=', 'transactions.related_id')
        //             ->where('transactions.type', 'Case')
        //             ->where('case.firm_id', $id)
        //             ->where('case.')
        //             ->get();
        //             pre($vp_total);
        //             die();
        $task = AdminTask::select('*')
                ->where('firm_admin_id', $firmadmin->id)
                ->whereIn('task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
                ->orderBy('id','DESC')
                ->skip(0)
                ->take(10)->get();
        foreach ($task as $key => $value) {
            $ccid = $value->case_id;
            if($value->task_type == 'provide_a_quote') {
                $doc = DocumentRequest::select('quote', 'case_id')->where('id', $ccid)->first();
                $ccid = $doc['case_id'];
            }
            $case = FirmCase::select('case_type')->where('id', $ccid)->first();
            $task[$key]->case_type = $case['case_type'];
        }
        return view('admin.firm.firm_details', ["firm"=>$firm, 'data' => $data, 'task' => $task]);
    }

    public function firm_users($id)
    {
        $firm = Firm::where('id', $id)->first();
        return view('admin.firm.firm_users', ["firm"=>$firm]);
    }

    public function firm_cases($id)
    {
        $firm = Firm::where('id', $id)->first();
        $firmadmin = User::where('firm_id', $id)->where('role_id', 4)->first();
        $data = array();
        $data['total_client'] = Newclient::select('*')->where('firm_id', $id)->count();
        $data['total_billing'] = Transaction::select('*')->where('user_id', $firmadmin->id)->sum('amount');
        $data['total_billing'] = $data['total_billing']/100;
        return view('admin.firm.firm_cases', compact('firm', 'data'));
    }

    public function getFirmCaseData(Request $request) {

        if($request->status == 1) {
            $admintask = FirmCase::select('case.*', 'users.name as client_name')
            ->where('case.firm_id', $request->firm_id)
            ->whereNull('case.VP_Assistance')
            ->leftJoin('users', 'users.id', 'case.client_id')
            ->get();
        }
        else if($request->status == 2) {
            $admintask = FirmCase::select('case.*', 'users.name as client_name')
            ->where('case.firm_id', $request->firm_id)
            ->where('case.VP_Assistance', 1)
            ->leftJoin('users', 'users.id', 'case.client_id')
            ->get();
        }
        else {
            $admintask = FirmCase::select('case.*', 'users.name as client_name')
            ->where('case.firm_id', $request->firm_id)
            ->leftJoin('users', 'users.id', 'case.client_id')
            ->get();
        }
        foreach ($admintask as $key => $value) {
            if($value->VP_Assistance) {
                $admintask[$key]->case_status = 'VP Assigned';
            }
            else {
                $admintask[$key]->case_status = 'Self managed';
            }
            $admintask[$key]->clink = '#';
            if(empty($value->client_name)) {
               $admintask[$key]->client_name = 'N/A'; 
            }
            else {
                $admintask[$key]->clink = url('admin/firm/viewclient/'.$request->firm_id.'/'.$value->client_id);
            }
        }
        return datatables()->of($admintask)->toJson();
    }

    public function firm_vpcases($id)
    {
        $firm = Firm::where('id', $id)->first();
        $firms = Firm::select('*')->get();
        $vpuser = User::where('role_id', 2)->get();
        $case_type = CaseType::select('Case_Type')->get();

        $firmadmin = User::where('firm_id', $id)->where('role_id', 4)->first();
        $data = array();
        $data['total_case'] = FirmCase::select('*')->where('firm_id', $id)->count();
        $data['total_billing'] = Transaction::select('*')->where('user_id', $firmadmin->id)->sum('amount');
        $data['total_billing'] = $data['total_billing']/100;
        return view('admin.firm.firm_vpcases', compact('firm', 'firms', 'vpuser', 'case_type', 'data'));
    }

    public function getFirmVPCaseData(Request $request) {

        if(!empty($request->vpuser) && !empty($request->case_type)) {
            $admintask = FirmCase::select('case.*', 'u1.name as client_name', 'u2.name as owner_name', 'admintask.allot_user_id', 'u3.name as vp_name')
            ->where('case.firm_id', $request->firm_id)
            ->where('case.VP_Assistance', 1)
            ->where('case.case_type', $request->case_type)
            ->leftJoin('users as u1', 'u1.id', 'case.client_id')
            ->leftJoin('users as u2', 'u2.id', 'case.created_by')
            ->join('admintask', 'admintask.case_id', 'case.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.allot_user_id', $request->vpuser)
            ->leftJoin('users as u3', 'u3.id', 'admintask.allot_user_id')
            ->get();
        }
        else if(empty($request->vpuser) && !empty($request->case_type)) {
            $admintask = FirmCase::select('case.*', 'u1.name as client_name', 'u2.name as owner_name', 'admintask.allot_user_id', 'u3.name as vp_name')
            ->where('case.firm_id', $request->firm_id)
            ->where('case.VP_Assistance', 1)
            ->where('case.case_type', $request->case_type)
            ->leftJoin('users as u1', 'u1.id', 'case.client_id')
            ->leftJoin('users as u2', 'u2.id', 'case.created_by')
            ->join('admintask', 'admintask.case_id', 'case.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->leftJoin('users as u3', 'u3.id', 'admintask.allot_user_id')
            ->get();
        }
        else if(!empty($request->vpuser) && empty($request->case_type)) {
            $admintask = FirmCase::select('case.*', 'u1.name as client_name', 'u2.name as owner_name', 'admintask.allot_user_id', 'u3.name as vp_name')
            ->where('case.firm_id', $request->firm_id)
            ->where('case.VP_Assistance', 1)
            ->leftJoin('users as u1', 'u1.id', 'case.client_id')
            ->leftJoin('users as u2', 'u2.id', 'case.created_by')
            ->join('admintask', 'admintask.case_id', 'case.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.allot_user_id', $request->vpuser)
            ->leftJoin('users as u3', 'u3.id', 'admintask.allot_user_id')
            ->get();
        }
        else {
            $admintask = FirmCase::select('case.*', 'u1.name as client_name', 'u2.name as owner_name', 'admintask.allot_user_id', 'u3.name as vp_name')
            ->where('case.firm_id', $request->firm_id)
            ->where('case.VP_Assistance', 1)
            ->leftJoin('users as u1', 'u1.id', 'case.client_id')
            ->leftJoin('users as u2', 'u2.id', 'case.created_by')
            ->join('admintask', 'admintask.case_id', 'case.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->leftJoin('users as u3', 'u3.id', 'admintask.allot_user_id')
            ->get();
        }
        foreach ($admintask as $key => $value) {
            if($value->VP_Assistance) {
                $admintask[$key]->case_status = 'VP Assigned';
            }
            else {
                $admintask[$key]->case_status = 'Self managed';
            }
            $admintask[$key]->clink = '#';
            if(empty($value->client_name)) {
               $admintask[$key]->client_name = 'N/A'; 
            }
            else {
                $admintask[$key]->clink = url('admin/firm/viewclient/'.$request->firm_id.'/'.$value->client_id);
            }

            $admintask[$key]->vlink = '#';
            if(empty($value->vp_name)) {
               $admintask[$key]->vp_name = 'N/A'; 
            }
            else {
                $admintask[$key]->vlink = url('admin/users/show/'.$value->allot_user_id);
            }
        }
        return datatables()->of($admintask)->toJson();
    }

    public function firm_notes($id)
    {
        $firm = Firm::where('id', $id)->first();
        $users = User::whereIn('role_id', [1])->get();
        
        return view('admin.firm.firm_notes', compact('firm', 'users'));
    }

    public function add_firm_notes(Request $request) {

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
            'task_for' => 'ADMIN',
            'related_id' => $request->firm_id,
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

    public function delete_firm_note($id) {
        $note = ClientNotes::select('*')
                ->where('id', $id)
                ->first();
        ClientNotes::where('id', $id)->delete();
        return redirect('admin/firm/firm_notes/'.$note->related_id)->with('success','Note delete successfully!');
    }

    public function firm_billing($id)
    {
        $data = array();
        $firm = Firm::where('id', $id)->first();
        $firmadmin = User::where('firm_id', $id)->where('role_id', 4)->first();
        $data['total_user'] = User::where('firm_id', $id)->where('role_id', 5)->count();
        $data['billing_info'] = Transaction::select('*')->where('user_id', $firmadmin->id)->first();
        $data['payment_info'] = '';
        if(!empty($data['billing_info'])) {
            $data['payment_info'] = json_decode($data['billing_info']->responce);
        }
        //pre($data['payment_info']);
        return view('admin.firm.firm_billing', compact('firm', 'data', 'firmadmin'));
    }
    public function viewclient($id, $cid) {
        $firm = Firm::where('id', $id)->first();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $cid)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        return view('admin.firm.viewclient', compact('firm', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function firm_edit($id)
    {
        $firm = Firm::where('id', $id)->first();
        return view('admin.firm.firm_edit', ["firm"=>$firm]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function timeline($id)
    {
        $firmlog = Log::where('title', 'FIRM')->where('related_id', $id)->get();
        return view('admin.firm.firm_timeline',compact('firmlog'));
    }


    public function delete($id)
    {
        Firm::where('id', $id)->update(['status' => 0]);
        User::where('firm_id', $id)->update(['status' => 0]);
        FirmCase::where('firm_id', $id)->update(['status' => 2]);

        $firmadmin = User::where('firm_id', $id)->where('role_id', 4)->first();

        $username = $firmadmin->name;
        $useremail =  $firmadmin->email;

        $msg = "Hi $username,<br><br>";
        $msg .= "Your Firm account temporary deactivat for 90 days<br>";
        $msg .= "Please contact to our support <br>";
        $msg = EmptyEmailTemplate($msg);
        $args = array (
            'bodyMessage' => $msg,
            'to' => $useremail,
            'subject' => 'Information from TILA',
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);


        return redirect('admin/firm')->with('success','Firm Account deactivate successfully!');
    }


    public function reactive($id)
    {
    
        $check = Firm::where('id', $id)->update(['status' => 1]);
        User::where('firm_id', $id)->update(['status' => 1]);
        
        $response = Firm::where('id', $id)->first();

        $username = $response->firm_admin_name;
        $useremail =  $response->email;

        //$user1 = User::where('email', $useremail)->first();
        //$token = app('auth.password.broker')->createToken($user1);
        //$resetlink = url('password/reset/'.$token.'?email='.$useremail);

        $LoginLink = url('login');
        $remove = array(
            'FirmName' => $response->firm_name,
            'LoginLink' => $LoginLink
        );
        $email = EmailTemplate(41, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $response->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        if ($check) {
            return redirect('admin/case_conformation/'.$id)->with('success','Firm and his user activate successfully!');
        }else{
            return redirect('admin/firm')->with('error','Firm account not activate, please try again');
        }

    }

    public function case_conformation($id) {
        $currunt_user = Auth::User();
        return view('admin.dashboard.case_conformation', ["user"=>$currunt_user, "firm_id"=>$id]);
    }

    public function caseIsConform($id) {

        FirmCase::where('firm_id',$id)->update(['status' => 5]);

        return redirect('admin/firm')->with('success','Case Reactive Successfully');
    }

    public function update_firm(Request $request)
    {
        if($request->oldemail == $request->email) {
            $validator = Validator::make($request->all(), [
                'firm_name' => 'required|string',
                'account_type' => 'required|string',
                'firm_admin_name' => 'required|string',
                'usercost' => 'required|string'
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'firm_name' => 'required|string',
                'account_type' => 'required|string',
                'email' => 'required|string|email|unique:firms|unique:users',
                'firm_admin_name' => 'required|string',
                'usercost' => 'required|string'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->with(['data' => $_POST])->withErrors($validator);
        }

        Firm::where('id', $_POST['id'])->update(['email' => $_POST['email'], 'firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type'], 'firm_admin_name' => $request->firm_admin_name, 'usercost' => $request->usercost]);

        User::where('email',$request->oldemail)->update(['name' => $request->firm_admin_name, 'email' => $request->email ]);
        /* firm logo upload start */
        if(!empty($request->firm_logo_path))
        {
            $firm_logo = Storage::put('firm_logo', $request->firm_logo_path);

            if($firm_logo){
                Firm::where('id', $_POST['id'])->update(['firm_logo_path' => $firm_logo]);
            }
        }
        /* firm logo upload close */


        return redirect('admin/firm')->with('success','Firm Account update successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if(!App::environment('demo'))
        {
            $user->update($request->only([
                'name', 'email'
            ]));

            if($request->password)
            {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if($request->role && $request->user()->can('edit-users') && !$user->isme)
            {
                $role = Role::find($request->role);
                if($role)
                {
                    $user->syncRoles([$role]);
                }
            }
        }

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function roles()
    {
        return response()->json(Role::get());
    }

    public function get_firmuser_data(Request $request)
    { 
        if(!empty($request->role)) {
            $record = User::select('users.*','roles.name as role_name')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('role_id' ,'=', $request->role)
            ->where('users.firm_id' ,'=', $request->firm_id)
            ->get();
        }
        else {
            $record = User::select('users.*','roles.name as role_name')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('role_id' ,[4, 5])
            ->where('users.firm_id' ,'=', $request->firm_id)
            ->get();
        }
        
        foreach ($record as $key => $value) {
            $record[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }

        return datatables()->of($record)->toJson();
    }

    public function get_firmnote_data(Request $request)
    { 
        if(!empty($request->role)) {
            $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $request->id)
                ->where('client_notes.task_for', 'ADMIN')
                ->where('client_notes.created_by', $request->role)
                ->orderBy('id', 'DESC')
                ->get();
        }
        else {
            $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $request->id)
                ->where('client_notes.task_for', 'ADMIN')
                ->orderBy('id', 'DESC')
                ->get();
        }

        return datatables()->of($notes_list)->toJson();
    }
    
    
    public function QBCreateClient($saveddata) {
        $data = Auth::User();
        // pre($data);
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
                        'RedirectURI' => $conf['oauth_redirect_uri_a'],
                        'scope' => $conf['oauth_scope'],
                        'baseUrl' => "https://quickbooks.api.intuit.com"
            )); 
            $oauth2LoginHelper = new OAuth2LoginHelper($ClientID, $client_secret);
            try{
            $newAccessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshToken);
            }   catch (ServiceException  $e) {
                echo $e->getMessage();
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

            $QBCustomerEmail = $saveddata['email'];
            // $customer = $dataService->FindbyId('customer', $QBCustomerID);
            $customer = $dataService->Query("SELECT * FROM Customer WHERE PrimaryEmailAddr = '$QBCustomerEmail'");            
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
                if ($error) {
     
                } else {
                    "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
                    $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
                    $xmlBody . "\n";
                }
                return $resultingObj->Id;
            }
            else {
                return $customer[0]->Id;
            }
        }
    }

}
