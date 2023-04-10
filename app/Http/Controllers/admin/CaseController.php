<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use App\Models\AdminTask;
use App\Models\Caselog;
use App\Models\FirmCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\TilaEmailTemplate;
use App\Models\Newclient;
use App\Models\Firm;
use App\Models\Event;
use App\Models\ClientTask;
use App\Models\ClientNotes;
use App\Models\ClientDocument;
use App\Models\CaseType;
use App\Models\ClientInformation;
use App\Models\AffidavitDocumentRequest;
use App\Models\Questionnaire;
use App\Notifications\DatabaseNotification;
use Notification;
use App;
use DB;

class CaseController extends Controller {

    public function __construct() {
        
    }

    public function allcases() {
        $vpuser = User::where('role_id', 2)->get();
        $firms = Firm::select('*')->get();
        $case_type = CaseType::select('Case_Type')->get();
        return view('admin.case.index', compact('vpuser', 'firms', 'case_type'));
    }

    public function getCaseData(Request $request) {
        
        if($request->tab == 0) {
            $q = "SELECT c.*, u1.firm_name as firmname, u2.name as clientname, u3.name as ownername, u3.email as owneremail, r.name as role, at.status as astatus, at.id as aid FROM `case` as c 
                LEFT JOIN `firms` as u1 ON c.firm_id = u1.id
                LEFT JOIN `users` as u2 ON c.client_id = u2.id  
                LEFT JOIN `users` as u3 ON c.created_by = u3.id
                LEFT JOIN `roles` as r ON r.id = u3.role_id
                LEFT JOIN `admintask` as at ON at.case_id = c.id  
                WHERE at.task_type = 'Assign_Case' AND at.allot_user_id = 0";
        }
        else if($request->tab == 1) {
            $q = "SELECT c.*, u1.name as firmname, u2.name as clientname, u3.name as ownername, u3.email as owneremail, r.name as role, u4.name as vpuser, u4.id as vpid, at.status as astatus, at.id as aid FROM `admintask` as at 
                LEFT JOIN `case` as c ON at.case_id = c.id  
                LEFT JOIN `users` as u1 ON at.firm_admin_id = u1.id
                LEFT JOIN `users` as u2 ON c.client_id = u2.id  
                LEFT JOIN `users` as u3 ON c.created_by = u3.id  
                LEFT JOIN `users` as u4 ON at.allot_user_id = u4.id  
                LEFT JOIN `roles` as r ON r.id = u3.role_id 
                WHERE at.task_type = 'Assign_Case' AND c.VP_Assistance = 1 AND at.allot_user_id != 0";
        }
        else if($request->tab == 2) {
            $data = Auth::User();
            $q = "SELECT c.*, u1.name as firmname, u2.name as clientname, u3.name as ownername, u3.email as owneremail, r.name as role, u4.name as vpuser, u4.id as vpid, at.status as astatus, at.id as aid FROM `admintask` as at 
                LEFT JOIN `case` as c ON at.case_id = c.id  
                LEFT JOIN `users` as u1 ON at.firm_admin_id = u1.id
                LEFT JOIN `users` as u2 ON c.client_id = u2.id  
                LEFT JOIN `users` as u3 ON c.created_by = u3.id  
                LEFT JOIN `users` as u4 ON at.allot_user_id = u4.id  
                LEFT JOIN `roles` as r ON r.id = u3.role_id 
                WHERE at.task_type = 'Assign_Case' AND c.VP_Assistance = 1
                AND at.allot_user_id = '".$data->id."'";
        }
        else if($request->tab == 3) {
            $q = "SELECT c.*, u1.name as firmname, u2.name as clientname, u3.name as ownername, u3.email as owneremail, r.name as role, at.updated_at as assigned_date, c.updated_at as compled_date, at.status as astatus, at.id as aid FROM `admintask` as at 
                LEFT JOIN `case` as c ON at.case_id = c.id  
                LEFT JOIN `users` as u1 ON at.firm_admin_id = u1.id
                LEFT JOIN `users` as u2 ON c.client_id = u2.id  
                LEFT JOIN `users` as u3 ON c.created_by = u3.id  
                LEFT JOIN `roles` as r ON r.id = u3.role_id 
                WHERE at.task_type = 'Assign_Case' AND c.status = 9";
        }
        if(!empty($request->firm)) {
            $q .= " and c.firm_id = ".$request->firm;
        }
        if(!empty($request->case_type)) {
            $q .= " and c.case_type = '".$request->case_type."'";
        }
        if(!empty($request->vpuser) && $request->tab) {
            $q .= " and at.task_type = 'Assign_Case'";
            $q .= " and at.allot_user_id = ".$request->vpuser;
        }
        $admintask = DB::select(DB::raw($q));

        
        foreach ($admintask as $key => $value) {
            $admintask[$key]->is_edit = True; 
            
            if(!empty($value->task_type) && $value->task_type == 'Assign_Case') {
                $admintask[$key]->is_edit = True; 
            }
            if(empty($value->vpuser)) {
                $admintask[$key]->vpuser = 'N/A';
                $admintask[$key]->vpid = false;
            }
            if(empty($value->clientname)) {
                $admintask[$key]->clientname = 'N/A';
            }
            if(empty($value->assigned_date)) {
                $admintask[$key]->assigned_date = 'N/A';
            }
            if(empty($value->compled_date)) {
                $admintask[$key]->compled_date = 'N/A';
            }
            $admintask[$key]->is_edit = false;
            $admintask[$key]->case_status = '';
            if($value->astatus == 0) {
                $admintask[$key]->is_edit = true;
                $admintask[$key]->case_status = 'Pending';
            }
            else if($value->astatus == 1) {
                $admintask[$key]->is_edit = false;
                $admintask[$key]->case_status = 'Accepted';
            }
            else if($value->astatus == -1) {
                $admintask[$key]->is_edit = true;
                $admintask[$key]->case_status = 'Denied';
            }
            if($request->tab == 3) {
                $admintask[$key]->case_status = GetCaseStatus($value->status);
            }
            $admintask[$key]->casestatus = GetCaseStatus($value->status);
        }
        return datatables()->of($admintask)->toJson();
    }

    public function show($id) {
        $case_id = $id;
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        if($firm->account_type == 'CMS') {
            $casedata = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name')
                    ->join('users', 'case.user_id', '=', 'users.id')
                    ->join('users as ur', 'case.client_id', '=', 'ur.id')
                    ->where('case.firm_id', $firm_id)
                    ->first();
        }
        else {
            $casedata = FirmCase::select('*')
                ->where('id', $case_id)
                ->first();
        }
        
        $data['totla_tasks'] = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();;
        $data['totla_documents'] = DocumentRequest::select('*')->where('case_id', $case->id)->count();
        $data['totla_notes'] = ClientNotes::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();
        $task = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->get();
        return view('admin.case.show', compact('casedata', 'id', 'case',  'data','firm', 'task'));
    }

    public function profile($id) {
        $case_id = $id;
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        if($firm->account_type == 'CMS') {
            $casedata = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name')
                    ->join('users', 'case.user_id', '=', 'users.id')
                    ->join('users as ur', 'case.client_id', '=', 'ur.id')
                    ->where('case.firm_id', $firm_id)
                    ->first();
        }
        else {
            $casedata = FirmCase::select('*')
                ->where('id', $case_id)
                ->first();
        }
        $ques = Questionnaire::select('*')
                ->where('client_id', $case->client_id)
                ->get();
        $client = Newclient::where('user_id', $case->client_id)->first();
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
        return view('admin.case.profile', compact('casedata', 'id', 'case', 'admintask','firm', 'ques', 'family_alllist', 'client', 'beneficiary_list'));
    }

    public function edit($id) {

        // $vauser = User::select('id', 'name')->where('firm_id', 0)->where('role_id', 2)->get();
        $vauser = User::select('users.*', 'roles.name as role_name')
                ->whereIn('users.role_id', [1,2])
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->get();
        $admintask = AdminTask::select('*')->where('id', $id)->first();
        $quote_cost = 0;
        if($admintask->task_type == 'provide_a_quote') {
            $doc = DocumentRequest::select('quote_cost')->where('id', $admintask->case_id)->first();
            $quote_cost = $doc->quote_cost;
        }
        $leave_event = array();
        if($admintask->task_type == 'Leave_Application') {
            $leave_event = Event::select('*')->where('related_id', $id)->first();
        }
        return view('admin.case.edit', compact('vauser', 'admintask'));
    }

    public function update(Request $request) {
        $record = AdminTask::select('*')->where('id', $request->task_id)->first();
        $firm_admin = User::select('*')->where('id', $record->firm_admin_id)->first();

        $firm = Firm::select('*')->where('id', $firm_admin->firm_id)->first();
        extract($_REQUEST);
        if($firm->account_type == 'CMS') {
            $q = "select cc.name as ClientName,fu.name as FirmUserName,f.firm_admin_name as FirmName, f.email as FirmEmail,c.* from admintask as t,`case` as c,firms as f,users as fu,users as cc where cc.id=c.client_id and fu.id=c.user_id and f.id=c.firm_id and t.id='" . $request->task_id . "' and c.id=t.case_id";
        }
        else {
            $q = "select cc.name as ClientName,fu.name as FirmUserName,f.firm_admin_name as FirmName, f.email as FirmEmail,c.* from admintask as t,`case` as c,firms as f,users as fu,users as cc where cc.id=c.client_id and fu.id=c.user_id and f.id=c.firm_id and t.id='" . $request->task_id . "' and c.id=t.case_id";
        }
        $Cases = DB::select(DB::raw($q));

        if($record->task_type == 'Assign_Case') {
            $msg = 'Assignment of case to TILA VP, successful!';
            $touser = User::where('id', $record->firm_admin_id)->first();
            $n_link = url('firm/case/show').'/'.$record->case_id;
            $message = collect(['title' => 'Assign VA User', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            $FirmCase = FirmCase::select('users.*')
                    ->join('users', 'users.id', '=', 'case.user_id')
                    ->where('case.id', $record->case_id)
                    ->first();
            $usercase = User::where('id', $FirmCase->id)->first();
            if($usercase->id != $record->firm_admin_id) {
                // Notification::send($usercase, new DatabaseNotification($message));
            }
            Notification::send($touser, new DatabaseNotification($message));
            $assigned_user = User::select('*')->where('id', $request->vauser)->first();
            if($assigned_user->role_id == '1') {
                $data3 = [
                        'firm_admin_id' => $record->firm_admin_id,
                        'task_type' => 'Required_Document_Request',
                        'task' => 'Required Document Request',
                        'case_id' => $record->case_id,
                        'allot_user_id' => $request->vauser,
                        'priority' => $request->priority,
                        'status' => 0
                    ];
                AdminTask::create($data3);
                FirmCase::where('id', $record->case_id)->update(['status' => 2]);
                $data = [
                    'status' => 1,
                    'allot_user_id' => $request->vauser
                ];
                AdminTask::where('id', $record->id)->update($data);
                $lmsg = 'Assignment of case to TILA VP, successful!';

                $msg = 'Case accepted successfully. Happy work day!';
                $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                Notification::send($touser, new DatabaseNotification($message));
                return redirect('admin/allcases')->withInfo($lmsg);
            }
        }

        if ($request->vauser !== 0) {
            $msg1 = 'Assign VA User successfully!';
            $data = [
                'allot_user_id' => $request->vauser,
                'priority' => $request->priority,
                'status' => 0
            ];
            AdminTask::where('id', $request->task_id)->update($data);
            

            if ($record->task_type != 'provide_a_quote') {
                $data3 = [
                    'firm_admin_id' => $record->firm_admin_id,
                    'task_type' => 'Required_Document_Request',
                    'task' => 'Required Document Request',
                    'case_id' => $record->case_id,
                    'allot_user_id' => $request->vauser,
                    'priority' => $request->priority,
                    'status' => 0
                ];
                // AdminTask::create($data3);
                
                FirmCase::where('id', $record->case_id)->update(['status' => 2]);

                // $msg = 'Required Document Request for case #'.$record->case_id;
                $msg = 'You have been assigned a case #'.$record->case_id;
                $touser = User::where('id', $request->vauser)->first();
                $n_link = url('admin/new_assignments');
                $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                
                Notification::send($touser, new DatabaseNotification($message));
            }
            $user_name = User::select('*')->where('id', $request->vauser)->first();
            $data1 = [
                'case_id' => $record->case_id,
                'message' => "Super Admin  VA user " . $user_name->name . " to this task"
            ];
            if ($record->task_type != 'provide_a_quote') {
                Caselog::create($data1);
            }
            if($msg1 == 'Assign VA User successfully!') {
                $clientname = '';
                if(!empty($Cases)) {
                    $clientname = $Cases[0]->ClientName;
                }
                $remove = array(
                    'FirmName' => $Cases[0]->FirmName,
                    'CaseType' => $Cases[0]->case_type,
                    'AssignedFirmUsers' => $Cases[0]->FirmUserName,
                    'ClientName' => $clientname,
                    'TILAReferenceNumber' => $task_id,
                    'VPUserName' => $user_name->name
                );
                $email = EmailTemplate(30, $remove);

                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => $user_name->email,
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                send_mail($args);

                $msg = 'Assignment of case to TILA VP, successful!';
                $touser = User::where('id', $record->firm_admin_id)->first();
                $n_link = url('firm/case/show').'/'.$record->case_id;
                $message = collect(['title' => 'Assign VA User', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $record->case_id)
                        ->first();
                $usercase = User::where('id', $FirmCase->id)->first();
                if($usercase->id != $record->firm_admin_id) {
                    // Notification::send($usercase, new DatabaseNotification($message));
                }
                Notification::send($touser, new DatabaseNotification($message));


                // $msg = 'You have been assigned a case #'.$record->case_id;
                $touser = User::where('id', $request->vauser)->first();
                $n_link = url('admin/new_assignments');
                $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                Notification::send($touser, new DatabaseNotification($message));
            }
            return redirect('admin/allcases')->with('success', 'Assignment of case to TILA VP, successful!');
        } 
        else {
            return redirect('admin/allcases')->with('error', 'Task not assigned!');
        }
    }

    public function casefamily($id)
    {
        $currunt_user = Auth::User();
        $case = FirmCase::select('*')
                ->where('id',$id)
                ->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        
        $case_id = $id;

        $client = array();
        
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $case->firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
            // $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
            // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
            // ->where('usermeta.meta_key', 'beneficiary')
            // ->where('usermeta.meta_value', $case_id)
            // ->where('users.role_id' ,'=', '7')
            // ->join('client_family', 'client_family.email', '=', 'users.email')
            // ->get();
        }
        else {
            $family_alllist = array();
            $beneficiary_list = array();
        }

       // $derivative_list = User::select('users.*', 'client_family.*', 'users.id as uid')
       //  ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
       //  ->where('usermeta.meta_key', 'derivative')
       //  ->where('usermeta.meta_value', $case_id)
       //  ->where('users.role_id' ,'=', '7')
       //  ->join('client_family', 'client_family.email', '=', 'users.email')
       //  ->get();

       //  $interpreter_list = User::select('users.*', 'client_family.*', 'users.id as uid')
       //  ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
       //  ->where('usermeta.meta_key', 'interpreter')
       //  ->where('usermeta.meta_value', $case_id)
       //  ->where('users.role_id' ,'=', '7')
       //  ->join('client_family', 'client_family.email', '=', 'users.email')
       //  ->get();

       //  $petitioner_list = User::select('users.*', 'client_family.*', 'users.id as uid')
       //  ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
       //  ->where('usermeta.meta_key', 'petitioner')
       //  ->where('usermeta.meta_value', $case_id)
       //  ->where('users.role_id' ,'=', '7')
       //  ->join('client_family', 'client_family.email', '=', 'users.email')
       //  ->get();

       //  $Co_Sponsor = array();
       //  $Co_Sponsor_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
       //  ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
       //  ->where('usermeta.meta_key', 'Co_Sponsor')
       //  ->where('usermeta.meta_value', $case_id)
       //  ->where('users.role_id' ,'=', '7')
       //  ->join('client_family', 'client_family.email', '=', 'users.email')
       //  ->first();
       //  $Household_Member = array();
       //  $Household_Member_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
       //  ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
       //  ->where('usermeta.meta_key', 'Household_Member')
       //  ->where('usermeta.meta_value', $case_id)
       //  ->where('users.role_id' ,'=', '7')
       //  ->join('client_family', 'client_family.email', '=', 'users.email')
       //  ->first();

       //  if (!empty($Co_Sponsor_arr)) {
       //      $Co_Sponsor = $Co_Sponsor_arr;
       //  }
        
       //  if (!empty($Household_Member_arr)) {
       //      $Household_Member = $Household_Member_arr;
       //  }
        
        $firm = DB::table('firms')->where('id', $case->firm_id)->first();
        $countries = DB::table("countries")->get();

        $existing_members = User::select('users.*')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->pluck("users.id");
        $em = array();
        if(!empty($existing_members)) {
            foreach ($existing_members as $k => $v) {
                $em[] = $v;
            }
        }

        $QuestionsArr=array(
            'Petitioner'=>'58bd6f6e02',
            'Principal Beneficiary'=>'c190d60db9',
            'Firm'=>'282505ebbb',
            'Derivative Beneficiary'=>'3cc1ec0e1f',
            'Household Member'=>'3dcc61d98e',
            'Co Sponsor'=>'a013381c7e',
        );
        
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$currunt_user->id."'";
        $admintask = DB::select($q);

        return view('admin.case.casefamily', compact('case', 'firm', 'family_list','family_alllist', 'admintask', 'client', 'em', 'QuestionsArr'));
    }

    public function casetask($id)
    {
        $case = FirmCase::select('*')
                ->where('id',$id)
                ->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        //$task = ClientTask::select('*')->where('related_id', $id)->where('task_for', 'CASE')->get();
        $task = ClientTask::select('client_task.*', 'users.name')
                ->where('client_task.related_id', $id)
                ->where('client_task.task_for', 'CASE')
                ->leftJoin('users', 'users.id', '=', 'client_task.created_by')
                ->get();
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$currunt_user->id."'";
        $admintask = DB::select($q);
        return view('admin.case.casetask', compact('case' , 'task', 'firm', 'admintask'));
    }

    public function addnewtask($id)
    {
        $case = FirmCase::select('*')
                ->where('id',$id)
                ->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        return view('admin.case.addnewtask', compact('case', 'firm'));
    }

    public function inserttask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('admin/allcases/addnewtask/'.$request->case_id)->withInfo('Mendatory fields are required!');
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
        return redirect('admin/allcases/casetask/'.$request->case_id)->withInfo('Task created successfully');
    }

    public function editcasetask($id, $tid)
    {
        $case = FirmCase::select('*')
                ->where('id',$id)
                ->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $task = ClientTask::select('*')
                ->where('id', $tid)
                ->first();
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$currunt_user->id."'";
        $admintask = DB::select($q);
        return view('admin.case.editcasetask', compact('case' , 'task', 'firm', 'admintask'));
    }

    public function updatecasetask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('admin/allcases/casetask/'.$request->case_id)->withInfo('Mendatory fields are required!');
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
        return redirect('admin/allcases/casetask/'.$request->case_id)->withInfo('Task created successfully');
    }

    public function caseevent($id) {
        $client = Newclient::where('id', $id)->first();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $event =   Event::select('*')->where('title', 'CASE')->where('related_id', $id)->get();
        return view('admin.case.case_event', compact('firm', 'case','client', 'event'));
    }

    public function casedocuments($id)
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
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)->get();
        $client_doc = array();
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
        
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$data->id."'";
        $admintask = DB::select($q);

        return view('admin.case.case_documents', compact('case', 'requested_doc','firm', 'card', 'client_doc', 'family_alllist', 'clientrr', 'CaseTypes', 'admintask'));
    }

    public function setAdminCaseDataDocument(Request $request) {
        $res = array();
        $data1 = Auth::User();
        $validator = Validator::make($request->all(), [
            'file_type' => 'required',
            'case_id' => 'required',
        ]);
        if ($validator->fails()) { 
            $res['status'] = false;
            $res['msg'] = 'Mendatory fields are required!';
            echo json_encode($res);
            die();
        }   
        $task = AdminTask::select('*')
                ->where('case_id', $request->case_id)
                ->where('task_type', 'Required_Document_Request')
                ->where('allot_user_id', $data1->id)->first();

        // foreach ($request->file_type as $kk => $vv) {
            if(!empty($request->family_id)) {
                foreach ($request->family_id as $ky => $va) {
                    if($va == $request->client_id) {
                        $data =  [
                            'client_id' => $request->client_id,
                            'family_id' => $va,
                            'case_id' => $request->case_id,
                            'requested_by' => $data1->id,
                            'document_type' => $request->file_type,
                            'expiration_date' => $request->expiration_date,
                            'status' => 0
                        ];
                        DocumentRequest::create($data);
                    }
                    else {
                        $data =  [
                            'client_id' => $request->client_id,
                            'family_id' => $va,
                            'case_id' => $request->case_id,
                            'requested_by' => $data1->id,
                            'document_type' => $request->file_type,
                            'expiration_date' => $request->expiration_date,
                            'status' => 0
                        ];
                        DocumentRequest::create($data);
                    }
                }
            }
        // }
        FirmCase::where('id', $request->case_id)->update(['status' => 4]);
        if(!empty($task)) {
           AdminTask::where('id', $task->id)->update(['status' => 1]);
           $task_data = [
                    'firm_admin_id' => $task->firm_admin_id,
                    'task_type' => 'Upload_Required_Document',
                    'task' => 'Upload Required Document',
                    'case_id' => $task->case_id,
                    'allot_user_id' => $task->allot_user_id,
                    'priority' => $task->priority,
                    'status' => 0
                ];
            AdminTask::create($task_data);
            /*--------------------Notifications---------------*/ 
        
            $msg = 'Upload Required Document';
            
            $touser = User::where('id', $task->firm_admin_id)->first();
            $n_link = url('firm/case/case_documents').'/'.$task->case_id;
            $message = collect(['title' => 'Upload Required Document', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $task->case_id)
                        ->first();
            $usercase = User::where('id', $FirmCase->id)->first();
            if($usercase->id != $task->firm_admin_id) {
                Notification::send($usercase, new DatabaseNotification($message));
            }
            Notification::send($touser, new DatabaseNotification($message));
        }
        $res['status'] = true;
        $res['msg'] = 'Document request successfully!';
        echo json_encode($res);
        die();
    }

    public function rquestblueprintdocuments1(Request $request) {
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

    public function completeDocument4(Request $request, $id) {
        $data1 = Auth::User();
        $status = $request->status;
        $act = '';
        if($status == 1) {
            $act = 'Submitted';
        }
        elseif ($status == 2) {
            $act = 'Accepted';
        }
        elseif ($status == 3) {
            $act = 'Required Translation';
        }
        elseif ($status == 4) {
            $act = 'Rejected';
        }
        DocumentRequest::where('id', $id)->update(['status' => $status]); 
        $task = AdminTask::select('*')
                ->where('case_id', $request->case_id)
                ->where('task_type', 'Upload_Required_Document')
                ->where('allot_user_id', $data1->id)->first();
        AdminTask::where('id', $task->id)->update(['status' => 1]);
        $task_data = [
            'firm_admin_id' => $task->firm_admin_id,
            'task_type' => 'Document_Action',
            'task' => 'Document '.$act,
            'case_id' => $task->case_id,
            'allot_user_id' => $task->allot_user_id,
            'priority' => $task->priority,
            'status' => 1
        ];
        AdminTask::create($task_data);
        /*--------------------Notifications---------------*/ 
        
        $msg = 'Document '.$act;
        
        $touser = User::where('id', $task->firm_admin_id)->first();
        $n_link = url('firm/case/case_documents').'/'.$task->case_id;
        $message = collect(['title' => $msg, 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $task->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        if($usercase->id != $task->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        }
        Notification::send($touser, new DatabaseNotification($message));
        if($status == 3) {
            FirmCase::where('id', $request->case_id)->update(['status' => 5]);      
        }

        $allot_user = User::where('id', $task->allot_user_id)->first();

        $remove = array(
            'Assigned_Firm_User' => $touser->name,
            'VP_USER' => $allot_user->name,
            'Link' => $n_link,
        );
        $email = EmailTemplate(33, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $touser->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        $res['status'] = true;
        $res['msg'] = 'Document complete successfully!';
        echo json_encode($res);
    }

    public function setAdminCaseDocument(Request $request) {
        $case_id = $request->case_id;
        $data = Auth::User();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
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

        return redirect('admin/allcases/casedocuments/'.$case_id)->with('success', 'Case document upload successfully!');
    }

    public function casenotes($id)
    {
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $case->id)
                ->where('client_notes.task_for', 'CASE')
                ->get();
    
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$currunt_user->id."'";
        $admintask = DB::select($q);

        return view('admin.case.case_notes', compact('case', 'firm', 'notes_list', 'admintask'));
    }

    public function addnewnotes(Request $request) {

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
            'subject' => $request->subject,
            'notes' => $request->note,
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

    public function deletecasenote($id, $tid) {
        $note = ClientNotes::select('*')
                ->where('id', $id)
                ->first();
        ClientNotes::where('id', $id)->delete();
        return redirect('admin/allcases/casenotes/'.$tid)->with('success','Note delete successfully!');
    }

    public function caseforms($id, $uid=0)
    {
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
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

        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$currunt_user->id."'";
        $admintask = DB::select($q);

        return view('admin.case.case_forms', compact('case', 'firm', 'client_information_forms', 'family_alllist', 'uid', 'client', 'admintask'));
    }

    public function additionalservice($id)
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
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$data->id."'";
        $admintask = DB::select($q);
        $I_864_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor')->first()['VP_Pricing'];
        $I_864A_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864A, Contract Between Sponsor and Household Member')->first()['VP_Pricing'];
        $I_DS260_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'DS-260 for Additional Derivative Beneficiary (online only)')->first()['VP_Pricing'];
        $I_Affidavit_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'Draft a Letter/Affidavit')->first()['VP_Pricing'];
        return view('admin.case.additional_service', compact('case','firm', 'card', 'admintask', 'I_864_Cost', 'I_864A_Cost', 'I_DS260_Cost', 'I_Affidavit_Cost'));
    }

    function requestadditionalservice(Request $request) {
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $as = json_decode($case->additional_service);
        if($request->nvc_packet_quantity_new) {
            $as->nvc_packet_quantity_new = $request->nvc_packet_quantity_new;
        }
        if($request->declaration_new) {
            $as->declaration->declaration_new = $request->declaration_new;
            $as->declaration->declaration_other_new = $request->declaration_other_new;
        }
        if($request->additional_service_new) {
            $as->additional_service_new = $request->additional_service_new;
        }
        FirmCase::where('id', $request->case_id)->update(['additional_service' => json_encode($as)]);
        $task_data = [
                    'firm_admin_id' => $request->firm_admin_id,
                    'task_type' => 'Additional_Service',
                    'task' => 'Additional Service Requested',
                    'case_id' => $request->case_id,
                    'allot_user_id' => Auth::User()->id,
                    'status' => 0
                ];
        AdminTask::create($task_data);
        return redirect('admin/allcases/additionalservice/'.$request->case_id)->with('success','Additional Service Requested successfully!');
    }

    public function affidavit($id)
    {
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $Affidavitdoc = AffidavitDocumentRequest::select('*')
                    ->where('case_id', $id)
                    ->get();

        $data = Auth::User();
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$data->id."'";
        $admintask = DB::select($q);

        return view('admin.case.affidavit', compact('case','firm', 'Affidavitdoc', 'admintask'));
    }

    public function uploadaffidavitdocuments4(Request $request)
    {
        $data = Auth::User();
        
        foreach ($request->file as $key => $file) {
            $f = Storage::put('client_doc', $file);
            $data1 = [
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
                AffidavitDocumentRequest::where('id', $doc->id)->update($data1);
            }
            else {
                AffidavitDocumentRequest::create($data1);
            }
        }
        return redirect('admin/allcases/affidavit/'.$request->case_id)->with('success','Document upload successfully!');
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

    public function addderivativeincase1(Request $request) {
        // pre($request->all());
        if($request->checked) {
            update_user_meta($request->family_id, $request->type, $request->case_id, 1);
        }
        else {
            $uc = DB::table("usermeta")->where("user_id", $request->family_id)->where("meta_key", $request->type)->where("meta_value", $request->case_id)->delete();
        }
    }

    public function editrequestdocuments($id, $did)
    {
        $data = Auth::User();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $requested_doc = DocumentRequest::select('*')->where('id', $did)->first();
        $client_doc = array();
        $clientrr = array();
        if($case->client_id) {
            $clientrr = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

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
        
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$data->id."'";
        $admintask = DB::select($q);

        return view('admin.case.editrequestdocuments', compact('case', 'requested_doc','firm', 'card', 'client_doc', 'family_alllist', 'clientrr', 'CaseTypes', 'admintask'));
    }

    public function updaterequestdocuments(Request $request) {
        // pre($request->all());
        // die();
        $data =  [
            'family_id' => $request->family_id,
            'document_type' => $request->file_type,
            'expiration_date' => $request->expiration_date,
        ];
        DocumentRequest::where('id', $request->did)->update($data);
        return redirect('admin/allcases/casedocuments/'.$request->case_id)->with('success', 'Document request update successfully!');
    }

    public function uploaddocuments($id, $did)
    {
        $data = Auth::User();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        
        $requested_doc = DocumentRequest::select('*')->where('id', $did)->first();
        $client_doc = array();
        $clientrr = array();
        if($case->client_id) {
            $clientrr = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

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
        
        $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $id . "' AND allot_user_id = '".$data->id."'";
        $admintask = DB::select($q);

        $family_id = $requested_doc->family_id;
        $docs = DocumentRequest::select('*')
                ->where('case_id', $id)
                ->where('family_id', $family_id)
                ->get();

        return view('admin.case.uploaddocuments', compact('case', 'requested_doc','firm', 'card', 'client_doc', 'family_alllist', 'clientrr', 'CaseTypes', 'admintask', 'docs'));
    }

    public function upload_req_doc(Request $request) {
        // pre($request->all());
        // die();
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
                // DocumentRequest::where('id', $request->did)->update(['document' => json_encode($client_file), 'status' => 1]);
            }

            $record = AdminTask::where('case_id', $request->case_id)->where('task_type', 'Assign_Case')->first();

            $msg = 'Document uploaded successfully!';
            if(!empty($record)) {
                $touser = User::where('id', Auth::User()->id)->first();
                $n_link = url('admin/allcases/casedocuments').'/'.$record->id;
                $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                Notification::send($touser, new DatabaseNotification($message));
            }
            
            $touser = User::where('id',$record->firm_admin_id)->first();
            $n_link = url('firm/case/case_documents').'/'.$request->case_id;
            $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
            $FirmCase = FirmCase::select('users.*')
                            ->join('users', 'users.id', '=', 'case.user_id')
                            ->where('case.id', $request->case_id)
                            ->first();
            $usercase = User::where('id', $FirmCase->id)->first();
            
            // Notification::send($usercase, new DatabaseNotification($message));
            
            Notification::send($touser, new DatabaseNotification($message));
        }
        return redirect('admin/allcases/casedocuments/'.$request->case_id)->with('success', 'Document uploaded successfully!');
    }

    public function casecomplete1($id) {
        /* --------------------Notifications--------------- */

        $firmcase = FirmCase::select('*')->where('id', $id)->first();
        $firm_id = $firmcase->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();

        $msg = Auth::User()->name . ' marked as completed case successfully!';

        

        $touser = User::where('id', $firmcase->client_id)->first();
        $n_link = url('firm/clientcase/show').'/'.$id;
        $message = collect(['title' => 'Firm Admin marked as completed Case', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if($firm_name->account_type == 'CMS') {
            Notification::send($touser, new DatabaseNotification($message));
        }

        $msg = 'This case is now marked as completed!';
        $touser = User::where('email', $firm_name->email)->first();
        $n_link = url('firm/case/show').'/'.$id;
        $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        /* --------------------------Email--------------------------- */

        $task = AdminTask::select('admintask.*', 'users.name as name', 'users.email as email')
                        ->where('admintask.case_id', $id)
                        ->where('admintask.task_type', 'Assign_Case')
                        ->join('users', 'users.id', '=', 'admintask.allot_user_id')
                        ->first();

        $q = "select c.first_name as clientf,c.middle_name as clientm,c.last_name  as clientl,u.name as username,u.email as useremail, cs.case_type, cs.case_category from `case` as cs,users as u,new_client as c where cs.user_id > 0 and u.id = cs.user_id and c.user_id = cs.client_id and cs.id='" . $id . "'";

        $emailsa = DB::select(DB::raw($q));
        $remove = array(
            'FirmName' => $firm_name->firm_name,
            'ClientName' => $emailsa[0]->clientf . ' ' . $emailsa[0]->clientm . ' ' . $emailsa[0]->clientl,
            'CaseType' => $emailsa[0]->case_category,
            'TILAVP' => $task->name
        );
        $email = EmailTemplate(39, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $task->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        $remove = array(
            'FirmName' => $firm_name->firm_name,
            'ClientName' => $emailsa[0]->clientf . ' ' . $emailsa[0]->clientm . ' ' . $emailsa[0]->clientl,
            'CaseType' => $emailsa[0]->case_category,
            'CaseCategory' => $emailsa[0]->case_type
        );
        $email = EmailTemplate(40, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $firm_name->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        /* --------------------------Email--------------------------- */
        FirmCase::where('id', $id)->update(['status' => 9]);
        return redirect('admin/allcases/show/'.$id)->with('success', 'This case is now marked as completed!');
    }

    public function updatecaseforms(Request $request) {
        extract($_POST);
        $client_arr = array();
        $client = ClientInformation::select('*')->where('id', $id)->first();
        $addr = json_decode($client->residence_address);
        $pdf_data = json_decode($_POST['data']);
        foreach ($pdf_data as $k => $v) {
            if(!empty($v->value)) {
                if (strpos($v->name, 'FirstName')) {
                    $client_arr['first_name'] = $v->value;
                }
                if (strpos($v->name, 'LastName')) {
                    $client_arr['last_name'] = $v->value;
                }
                if (strpos($v->name, 'MiddleName')) {
                    $client_arr['middle_name'] = $v->value;
                }
                if (strpos($v->name, 'Mobile')) {
                    $client_arr['cell_phone'] = $v->value;
                }

                if (strpos($v->name, 'Country')) {
                    $countries = DB::table("countries")->where('name', $v->value)->first();
                    if(!empty($countries)) {
                        $addr['country'] = $countries->id;
                    }
                }

                if (strpos($v->name, 'State')) {
                    $regions = DB::table("regions")->where('name', $v->value)->first();
                    if(!empty($regions)) {
                        $addr['state'] = $regions->id;
                    }
                }

                if (strpos($v->name, 'City')) {
                    $cities = DB::table("cities")->where('name', $v->value)->first();
                    if(!empty($cities)) {
                        $addr['city'] = $cities->id;
                        $addr['state'] = $cities->region_id;
                    }
                }

                if (strpos($v->name, 'Street')) {
                    $addr['address'] = $v->value;
                }
            }
        }
        if(!empty($addr)) {
            $client_arr['residence_address'] = json_encode($addr);
        }
        if(!empty($client_arr)) {
            
            //Newclient::where('id', $client->client_id)->update($client_arr);
        }
        
        
        
        
        updatePdfFormAllField($id,$data);
        
        
        $data1 = array('information' => $data, 'status' => 0);
        ClientInformation::where('id', $id)->update($data1);
        // AdminTask::where('id', $request->task_id)->update($data);
    }
}