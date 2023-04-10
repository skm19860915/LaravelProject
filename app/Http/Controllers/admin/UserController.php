<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\{UserUpdateRequest,UserAddRequest};
use Spatie\Permission\Models\Role;
use App;
use Illuminate\Support\Facades\Validator;

use App\Models\TilaEmailTemplate;
use App\Models\AdminTask;
use App\Models\FirmCase;
use App\Models\DocumentRequest;
class UserController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize(User::class, 'index');
       /* if($request->ajax())
        {
            $users = new User;
            if($request->q)
            {
                $users = $users->where('name', 'like', '%'.$request->q.'%')->orWhere('email', $request->q);
            }
            $users = $users->paginate(config('stisla.perpage'))->appends(['q' => $request->q]);
            return response()->json($users);
        }*/
        //$c = User::get()->count();

        $c = User::select('users.*','roles.name as role_name')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('role_id' , [1, 2])
            ->count();
        return view('admin.users.index',['total' => $c]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        $data = array();
        $data['total_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->count();
        $data['complete_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(9))
            ->count();
        $data['open_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(1,2,3))
            ->count();
        $data['onhold_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(4,5,7))
            ->count();
        $data['inreview_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(6))
            ->count();

        $todaydate = date('Y-m-d');
        $admintask = AdminTask::select('admintask.*','firms.firm_name')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'firms.id', '=', 'users.firm_id')
        ->where('admintask.allot_user_id', $id)
        ->where('admintask.created_at', 'like', '%'.$todaydate.'%')
        ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
        ->orderByDesc('admintask.id')
        ->limit(10)
        ->get(); 
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            switch ($value->priority) {
                case 1:
                $result = "Urgent";
                break;
                case 2:
                $result = "High";
                break;
                case 3:
                $result = "Medium";
                break;
                case 4:
                $result = "Low";
                break;
                default:
                $result = "Normal";
            }
            switch ($value->status) {
                case 0:
                $result1 = "Open";
                break;
                case 1:
                $result1 = "Complete";
                break;
                default:
                $result1 = "Normal";
            }
            if($value->task_type == 'upload_translated_document' || $value->task_type == 'provide_a_quote') {
                $docs = DocumentRequest::select('*')
                        ->where('id', $value->case_id)
                        ->first();
              if(!empty($docs)) {
                $admintask[$key]->case_id = $docs->case_id;
              }
            }
            $client = FirmCase::select('users.name', 'case.case_type as case_type')
                    ->join('users', 'users.id', '=', 'case.client_id')
                    ->where('case.id',$admintask[$key]->case_id)
                    // ->where('case.id',$admintask[$key]->case_id)
                    ->first();
            if(!empty($client)) {       
                $admintask[$key]->client = $client->name;
                $admintask[$key]->case_type = $client->case_type;
            }
            else {
                $client = FirmCase::select('case.case_type as case_type')
                    // ->join('users', 'users.id', '=', 'case.client_id')
                    ->where('case.id',$admintask[$key]->case_id)
                    // ->where('case.id',$admintask[$key]->case_id)
                    ->first();
                $admintask[$key]->client = 'Not Found';
                if(!empty($client)) {
                    $admintask[$key]->case_type = $client->case_type;
                }
                else {
                    $admintask[$key]->case_type = '';   
                }
            }
            $admintask[$key]->priority =  $result;
            $admintask[$key]->status =  $result1;
        }
        return view('admin.users.show', compact('user', 'data', 'admintask'));
    }

    public function cases($id)
    {
        $user = User::where('id', $id)->first();
        $data = array();
        $data['total_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->count();
        $data['complete_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(9))
            ->count();
        $data['open_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(1,2,3))
            ->count();
        $data['onhold_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(4,5,7))
            ->count();
        $data['inreview_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',$user->id)
            ->whereIn('admintask.task_type', ['Assign_Case'])
            ->whereIn('case.status',array(6))
            ->count();
        return view('admin.users.cases', compact('user', 'data'));
    }

    public function assigned($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.users.assigned', compact('user'));
    }

    public function tasks($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.users.tasks', compact('user'));
    }

    public function newtasks($id)
    {
        $user = User::where('id', $id)->first();
        $vauser = User::select('id', 'name')->where('firm_id', 0)->where('role_id', 2)->get();
        $firmclient = User::select('id', 'name')->where('role_id', 6)->get();
        return view('admin.users.newtasks', compact('user', 'vauser', 'firmclient'));
    }

    public function getTaskData(Request $request) {
        if(!empty($request->due_date)) {
            $form = date('m/d/Y');
            if($request->due_date == 'week') {
                $to = date('m/d/Y', strtotime("+7 day", strtotime($form)));
            }
            else if($request->due_date == '15days') {
                $to = date('m/d/Y', strtotime("+15 day", strtotime($form)));
            }
            else if($request->due_date == '30days') {
                $to = date('m/d/Y', strtotime("+30 day", strtotime($form)));
            }
            
            $admintask = AdminTask::select('admintask.*', 'users.name as clientname')
                ->leftjoin('users', 'users.id', '=', 'admintask.client_task')
                ->where('admintask.task_type', 'ADMIN_TASK')
                ->where('admintask.status', $request->s)
                ->where('admintask.allot_user_id', $request->vpuser)
                ->whereBetween('admintask.due_date', [$form, $to])
                ->get();
        }
        else {
        $admintask = AdminTask::select('admintask.*', 'users.name as clientname')
                ->leftjoin('users', 'users.id', '=', 'admintask.client_task')
                ->where('admintask.task_type', 'ADMIN_TASK')
                ->where('admintask.status', $request->s)
                ->where('admintask.allot_user_id', $request->vpuser)
                ->get();
            }
        foreach ($admintask as $key => $value) {
            $admintask[$key]->stat = ($value->status == 0) ? "Opened" : "Completed";
            switch ($value->priority) {
                case 1:
                    $result = "Urgent";
                    break;
                case 2:
                    $result = "High";
                    break;
                case 3:
                    $result = "Medium";
                    break;
                case 4:
                    $result = "Low";
                    break;
                default:
                    $result = "Normal";
            }

            $admintask[$key]->clink = '#';
            if(empty($value->clientname)) {
               $admintask[$key]->clientname = 'N/A'; 
            }
            else {
                $admintask[$key]->clink = url('admin/users/viewclient/'.$value->client_task);
            }
            $admintask[$key]->priority = $result;           
        }
        return datatables()->of($admintask)->toJson();
    }

    public function viewclient($cid) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $cid)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        return view('admin.users.viewclient', compact('client'));
    }

    public function get_cases_data(Request $request) {
        // $st = $request->s1;
        // if($request->s1 == 2) {
        $st = array();
        if($request->s1 == 'Open') {
            $st = array(2);
        }
        else if($request->s1 == 'OnHold') {
            $st = array(3,4,5);
        }
        else if($request->s1 == 'InReview') {
            $st = array(6);
        }
        else if($request->s1 == 'Complete') {
            $st = array(9);
        }
        else if($request->s1 == 'InComplete') {
            $st = array(8);
        }
        // }
        if(!empty($request->s1)) {
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status', 'case.case_type', 'case.case_cost', 'case.VP_Assistance', 'case.id as case_id', 'case.client_id as client_id')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.allot_user_id', $request->vpuser)
            ->whereIn('case.status', $st)
            ->get();
        }
        else {
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status', 'case.case_type', 'case.case_cost', 'case.VP_Assistance', 'case.id as case_id', 'case.client_id as client_id')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.allot_user_id', $request->vpuser)
            ->get();
        }
        foreach ($admintask as $key => $value) {
            $admintask[$key]->is_edit = True; 
            if($value->VP_Assistance) {
                $admintask[$key]->stat = '';
                $lu = getUserName($value->allot_user_id);
                if(!empty($lu)) {
                    $admintask[$key]->stat = $lu->name;
                }
                if(!empty($value->allot_user_id)) {
                    $admintask[$key]->is_edit = false; 
                }
                else {
                    $admintask[$key]->stat = 'Not Assign';
                }
            }
            else {
                $admintask[$key]->is_edit = false; 
                $admintask[$key]->case_cost = 'Self managed';
                $admintask[$key]->stat = 'Self managed';
            }
            if(!empty($value->task_type) && $value->task_type == 'Assign_Case') {
                $admintask[$key]->is_edit = True; 
            }
            $cl = getUserName($value->client_id);
            $admintask[$key]->client_name = 'N/A';
            if(!empty($cl)) {
                $admintask[$key]->client_name = $cl->name;
            }
            
            if($value->status == 0) {
                $admintask[$key]->case_status = 'Pending';
            }
            else if($value->status == 1) {
                $admintask[$key]->case_status = 'Accepted';
            }
            else if($value->status == -1) {
                $admintask[$key]->case_status = 'Denied';
            }
            //$admintask[$key]->case_status = GetCaseStatus($value->case_status);
        }
        return datatables()->of($admintask)->toJson();
    }

    public function delete($id)
    {
        User::where('id', $id)->delete();
        return redirect('admin/users')->with('success','User Account deleted successfully!');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_user(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:firms|unique:users',
        ]);

        $pass = str_random(8);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($pass)
        ];

        if ($validator->fails()) {
            // $data['error'] = 'rk test';
            return redirect('admin/users/create')->with(['data' => $data])->withErrors($validator);
        }

        $user = User::create($data);
        
        $role = Role::find($request->role);
        if($role)
        {
            $user->assignRole($role);
        }


        $content = TilaEmailTemplate::select('massage')->where('subtitle',"VA Access Invite")->first();
        
        $username = $request->name;
        $useremail =  $request->email;
        $pass = $pass;
        $LoginPage = url('login');
        $remove = array(
            'VPName' => $username,
            'UserN'=>$useremail,
            'UserP'=>$pass,
            'LoginPage' => $LoginPage
        );
        $email = EmailTemplate(37, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $useremail,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );

        send_mail($args);

        return redirect('admin/users')->with('success','User Create successfully!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        if($request->password)
        {
            $user->update(['password' => Hash::make($request->password)]);
        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_user(Request $request)
    {


        if (empty($request->status)) {
            $request->status = 1;
        }
        User::where('id',$request->user_id)->update(['name' => $request->name, 'status' => $request->status, 'role_id' => $request->role_id]);

        if($request->password)
        {
            User::where('id',$request->user_id)->update(['password' => Hash::make($request->password)]);
        }   
       
        return redirect('admin/users')->with('success','User Update successfully!');
        //return response()->json($user);
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
    public function destroy(User $user)
    {   
        
        if(!App::environment('demo') && !$user->isme)
        {
            $user->delete();
        }else
        {
            return response()->json(['message' => 'User accounts cannot be deleted in demo mode.'], 400);
        }
    }

    public function roles()
    {
        return response()->json(Role::get());
    }

    public function get_user_data(Request $request)
    { 
        if(!empty($request->role)) {
            $record = User::select('users.*','roles.name as role_name')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('role_id' ,'=', $request->role)
            ->get();
        }
        else {
            $record = User::select('users.*','roles.name as role_name')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereIn('role_id' , [1, 2])
            ->get();
        }
        foreach ($record as $key => $value) {
            $record[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
        }

        return datatables()->of($record)->toJson();

        /*return datatables()->of($record)->addColumn('checkbox', '<input type="checkbox" name="student_checkbox[]" class="client_checkbox" value="{{ $id }}">')
        ->rawColumns(['checkbox'])
           ->make(true)*/;
    }
}
