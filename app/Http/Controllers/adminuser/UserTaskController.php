<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Http\Request;
use App\User;
use App\Models\AdminTask;
use App\Models\Newclient;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\DocumentRequest;
use App\Models\ClientFamily;
use App\Models\ClientTask;
use App\Models\ClientNotes;
use App\Models\CaseType;
use App\Models\ClientInformation;
use App\Models\FamilyInformation;
use App\Models\ClientDocument;
use App\Models\AffidavitDocumentRequest;
use App\Models\TextMessage;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Notifications\DatabaseNotification;
use Notification;
use App;
use DB;
use App\Dropbox;
class UserTaskController extends Controller
{
    private $api_client;
    private $content_client;
    public function __construct(Dropbox $dropbox)
    {
        $this->api_client = $dropbox->api();
        $this->content_client = $dropbox->content();


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.adminuser.usertask.index');
    }


    public function getData(Request $request)
    { 
        $st = array();
        if(!empty($request->case_status)) {
            if($request->case_status == 'Open') {
                $st = array(1,2,3);
            }
            else if($request->case_status == 'Working') {
                $st = array(4,5,7);
            }
            else if($request->case_status == 'InReview') {
                $st = array(6);
            }
            else if($request->case_status == 'Complete') {
                $st = array(9);
            }
            else if($request->case_status == 'InComplete') {
                $st = array(8);
            }
        }
        if(!empty($request->case_status) && empty($request->due_date)) {
            $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status', 'u1.name as clientname', 'u1.id as cid')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->leftjoin('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Leave_Application', 'Required_Document_Request', 'Upload_Required_Document'])
            ->where('admintask.status', $request->status)
            ->whereIn('case.status', $st)
            ->leftjoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->get(); 
        }
        else if(empty($request->case_status) && !empty($request->due_date)) {
            $form = date('m/d/Y');
            if($request->due_date == 'today') {
                $to = $form;
            }
            else if($request->due_date == '15days') {
                $to = date('m/d/Y', strtotime("+15 day", strtotime($form)));
            }
            else if($request->due_date == '30days') {
                $to = date('m/d/Y', strtotime("+30 day", strtotime($form)));
            }
            $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status', 'u1.name as clientname', 'u1.id as cid')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->leftjoin('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Leave_Application', 'Required_Document_Request', 'Upload_Required_Document'])
            ->where('admintask.status', $request->status)
            ->whereBetween('admintask.due_date', [$form, $to])
            ->leftjoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->get(); 
        }

        else if(!empty($request->case_status) && !empty($request->due_date)) {
            $form = date('m/d/Y');
            if($request->due_date == 'today') {
                $to = $form;
            }
            else if($request->due_date == '15days') {
                $to = date('m/d/Y', strtotime("+15 day", strtotime($form)));
            }
            else if($request->due_date == '30days') {
                $to = date('m/d/Y', strtotime("+30 day", strtotime($form)));
            }
            $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status', 'u1.name as clientname', 'u1.id as cid')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->leftjoin('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Leave_Application', 'Required_Document_Request', 'Upload_Required_Document'])
            ->where('admintask.status', $request->status)
            ->whereBetween('admintask.due_date', [$form, $to])
            ->whereIn('case.status', $st)
            ->leftjoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->get(); 
        }
        else {
            $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status', 'u1.name as clientname', 'u1.id as cid')
            ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
            ->leftjoin('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'Leave_Application', 'Required_Document_Request', 'Upload_Required_Document'])
            ->where('admintask.status', $request->status)
            ->leftjoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->get(); 
        }

        foreach ($admintask as $key => $value) {
            $admintask[$key]->allotuserid = $admintask[$key]->allot_user_id;
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            $admintask[$key]->stat = ($value->status == 0) ? "Open" : "Completed";
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
            $admintask[$key]->priority =  $result;

            $admintask[$key]->clink = '#';
            if(empty($value->clientname) || $value->task_type == 'ADMIN_TASK') {       
                $admintask[$key]->clientname = 'N/A';
                if($value->task_type == 'ADMIN_TASK' && !empty($value->client_task))
                {
                    $lu1 = getUserName($value->client_task);
                    if($lu1) {
                        $admintask[$key]->clientname = $lu1->name;
                        $admintask[$key]->clink = url('admin/userclient/clientcases/'.$value->client_task);
                    }
                }
            }
            else {
                $admintask[$key]->clink = url('admin/userclient/clientcases/'.$value->cid);
            }
            if(empty($value->mytask)) {       
                $admintask[$key]->mytask = 'N/A';
            }
            if(empty($value->due_date)) {       
                $admintask[$key]->due_date = 'N/A';
            }
        }
        return datatables()->of($admintask)->toJson();        
    }

    public function overview($id)
    {   
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            if($admintask->account_type == 'CMS') {
                $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
                $case_id = $docs->case_id;
            }
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $data['totla_tasks'] = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();;
        $data['totla_documents'] = DocumentRequest::select('*')->where('case_id', $case->id)->count();
        $data['totla_notes'] = ClientNotes::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->count();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $task = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->get();
        return view('admin.adminuser.usertask.overview', compact('client', 'case', 'admintask', 'data', 'task'));
    }

    public function profile($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
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
        $ques = Questionnaire::select('*')
                ->where('client_id', $case->client_id)
                ->get();
        return view('admin.adminuser.usertask.profile', compact('client', 'case', 'admintask', 'ques', 'family_alllist', 'beneficiary_list'));
    }

    public function family($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $family_list = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_list = ClientFamily::where('client_id', $client->id)->orderBy('id', 'desc')->get();
        }
        return view('admin.adminuser.usertask.family', compact('client', 'case', 'admintask', 'family_list'));
    }

    public function tasks($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $task = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            
        }
        //$task = ClientTask::select('*')->where('related_id', $case->id)->where('task_for', 'CASE')->get();
        $task = ClientTask::select('client_task.*', 'users.name')
                ->where('client_task.related_id', $case->id)
                ->where('client_task.task_for', 'CASE')
                ->leftJoin('users', 'users.id', '=', 'client_task.created_by')
                ->get();
        return view('admin.adminuser.usertask.tasks', compact('client', 'case', 'admintask', 'task'));
    }

    public function add_new_task($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        // pre($case);
        return view('admin.adminuser.usertask.add_new_task', compact('client', 'case', 'admintask'));
    }

    public function insert_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('admin/usertask/add_new_task/'.$request->id)->withInfo('Mendatory fields are required!');
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
        return redirect('admin/usertask/tasks/'.$request->id)->withInfo('Task created successfully');
    }

    public function edit_case_task($id, $tid)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $task = ClientTask::where('id', $tid)->first();
        return view('admin.adminuser.usertask.edit_case_task', compact('client', 'case', 'admintask', 'task'));
    }

    public function update_case_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('admin/usertask/edit_case_task/' . $request->case_id.'/'.$request->tid)->withInfo('Mendatory fields are required!');
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
        return redirect('admin/usertask/tasks/' . $request->case_id)->withInfo('Task update successfully');
    }

    public function documents($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $client_doc = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                $client_doc = ClientDocument::select('*')->where('client_id', $client->id)->get();
            }
        }
        $client_doc = ClientDocument::select('*')->where('case_id', $case->id)->get();
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)->get();
        $already_requested = array();
        $CaseTypes = array();
        $client_id = '';
        if(!empty($case)) {
            $client1 = Newclient::select('id')->where('user_id', $case->client_id)->first();
            if(!empty($client1)) {
                $client_id = $client1->id;
            }
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
            
            if(!empty($requested_doc)) {
                foreach ($requested_doc as $key => $value) {
                    $already_requested[] = $value->document_type;
                }
            }
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case->id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        return view('admin.adminuser.usertask.documents', compact('client', 'case', 'admintask', 'requested_doc', 'CaseTypes', 'already_requested', 'client_id', 'client_doc', 'family_alllist'));
    }

    public function editrequestdocuments($id, $did)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $requested_doc = DocumentRequest::select('*')->where('id', $did)->first();
        $already_requested = array();
        $CaseTypes = array();
        $client_id = '';
        if(!empty($case)) {
            $client1 = Newclient::select('id')->where('user_id', $case->client_id)->first();
            if(!empty($client1)) {
                $client_id = $client1->id;
            }
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case->id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        return view('admin.adminuser.usertask.editrequestdocuments', compact('client', 'case', 'admintask', 'requested_doc', 'CaseTypes', 'already_requested', 'client_id', 'client_doc', 'family_alllist'));
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
        return redirect('admin/usertask/documents/'.$request->tid)->with('success', 'Document request update successfully!');
    }

    public function setCaseDocument5(Request $request) {
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

        return redirect('admin/usertask/documents/'.$request->id)->with('success', 'Case document upload successfully!');
    }

    public function familydocuments($id, $fid)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')
            ->where('id', $case_id)
            ->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            
        }
        $requested_doc = DocumentRequest::select('*')->where('case_id', $case->id)->where('family_id', $fid)->get();
        $already_requested = array();
        $CaseTypes = array();
        $client_id = '';
        if(!empty($case)) {
            $client1 = Newclient::select('id')->where('user_id', $case->client_id)->first();
            if(!empty($client1)) {
                $client_id = $client1->id;
            }
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
            
            if(!empty($requested_doc)) {
                foreach ($requested_doc as $key => $value) {
                    $already_requested[] = $value->document_type;
                }
            }
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case->id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        return view('admin.adminuser.usertask.familydocuments', compact('client', 'case', 'admintask', 'requested_doc', 'CaseTypes', 'already_requested', 'client_id', 'family_alllist', 'fid'));
    }

    public function notes($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
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
        return view('admin.adminuser.usertask.notes', compact('client', 'case', 'admintask', 'msg'));
    }

    public function caseinbox($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        
        $msg = array();
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        // ->where('text_message.msgfrom', Auth::User()->id)
        ->where('text_message.msgto', $case->client_id)
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
        ->where('text_message.msgfrom', $case->client_id)
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
        return view('admin.adminuser.usertask.caseinbox', compact('client', 'case', 'admintask', 'msg'));
    }

    public function getMessageData($id)
    { 
        $data = Auth::User();
        $msg = array();
        $messages = TextMessage::select('text_message.*')
        ->where('text_message.msgfrom', $data->id)
        ->where('text_message.msgto', $id)
        ->get(); 
        foreach ($messages as $k => $v) {
            $messages[$k]->msgfrom = getUserName($v->msgfrom)->name;
            $messages[$k]->msgto = getUserName($v->msgto)->name;
            $msg[] = $messages[$k];
        }
        $messages1 = TextMessage::select('text_message.*')
        ->where('text_message.msgfrom', $id)
        ->where('text_message.msgto', $data->id)
        ->get(); 
        foreach ($messages1 as $k => $v) {
            $messages1[$k]->msgfrom = getUserName($v->msgfrom)->name;
            $messages1[$k]->msgto = getUserName($v->msgto)->name;
            $msg[] = $messages1[$k];
        }
        return datatables()->of($msg)->toJson();        
    }

    public function sendtextmsg(Request $request) {
        $data2 = [
            'msgfrom' => Auth::User()->id,
            'msgto' => $request->to,
            'msg' => $request->msg,
        ];

        $touser = User::where('id',$request->to)->first();
        
        $n_link = url('firm/case/case_inbox').'/'.$request->case_id;
        
        $message = collect(['title' => 'Send you Text message', 'body' => $request->msg,'type'=>'1','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        Notification::send($touser, new DatabaseNotification($message));
        $note = TextMessage::create($data2);
    }

    public function caseforms($id, $uid=0)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        if($uid) {
            $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$case->id)
            ->where('client_information_forms.client_id',$uid)
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->where('case.VP_Assistance', 1)
            ->get();
        }
        else {
            $client_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$case->id)
            ->join('case', 'client_information_forms.case_id', '=', 'case.id')
            ->where('case.VP_Assistance', 1)
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
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
            ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
            ->where('usermeta.meta_value', $case_id)
            ->where('users.role_id' ,'=', '7')
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->get();

        return view('admin.adminuser.usertask.caseforms', compact('client', 'case', 'admintask', 'client_information_forms', 'family_alllist', 'uid'));
    }

    public function addcaseforms($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        return view('admin.adminuser.usertask.addcaseforms', compact('client', 'case', 'admintask'));
    }

    public function add_new_notes(Request $request) {

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

    public function deletenote($id, $tid) {
        $note = ClientNotes::select('*')
                ->where('id', $id)
                ->first();
        ClientNotes::where('id', $id)->delete();
        return redirect('admin/usertask/notes/'.$tid)->with('success','Note delete successfully!');
    }

    public function show($id)
    {
        $client = Client_profile::where('user_id', $id)->first();
        return view('firmadmin.client.show',compact('client'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edittask($id)
    {
        $current_user = Auth::User();
        $firmadmins = User::select('id', 'name', 'firm_id')->where('role_id', 4)->get();
        $firmclient = User::select('id', 'name', 'firm_id')->where('role_id', 6)->get();
        $admintask = AdminTask::select('*')->where('id', $id)->first();
        return view('admin.adminuser.usertask.edittask', compact('current_user', 'firmadmins', 'admintask', 'firmclient'));
    }

    public function update_task2(Request $request) {
        $data3 = array();
        $client_task = '';
        if(!empty($request->firmclient)) {
            $client = Newclient::select('new_client.firm_id', 'firms.*', 'users.id as firm_admin_id')
                    ->where('new_client.user_id', $request->firmclient)
                    ->join('firms', 'firms.id', '=', 'new_client.firm_id')
                    ->join('users', 'users.email', '=', 'firms.email')
                    ->first();
            $firmid = '';
            if(!empty($client)) {
                $firmid = $client->firm_admin_id;
            }

            $client_task = $request->firmclient;
        }
        else {
            $firmid = $request->vauser;
        }
        $firmid = $request->vauser;
        $data3 = [
                    'client_task' => $client_task,
                    'firm_admin_id' => $firmid,
                    'task_type' => 'ADMIN_TASK',
                    'task' => $request->task,
                    'mytask' => $request->description,
                    // 'case_id' => $request->vauser,
                    // 'allot_user_id' => $request->vauser,
                    'priority' => $request->priority,
                    'due_date' => $request->due_date,
                    'status' => $request->status
                ];
        AdminTask::where('id', $request->id)->update($data3);
        return redirect('admin/usertask')->with('success', 'Task update successfully!');
    }

    public function update(Request $request)
    {

        Firm::where('id', $_POST['id'])->update(['firm_name' => $_POST['firm_name'], 'account_type' => $_POST['account_type']]);
        return redirect('admin/firm')->with('success','Firm Account update successfully!');
    }

    public function readytoreview($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        $case_id = $admintask->case_id;
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        

        $firm_admin = User::select('*')->where('id', $admintask->firm_admin_id)->first();

        $firm = Firm::select('*')->where('id', $firm_admin->firm_id)->first();

        $firmcase = FirmCase::select('*')->where('id', $case_id)->first();
        $ClientName = '';
        if(!empty($firmcase->client_id)) {
            $cll = User::select('*')->where('id', $firmcase->client_id)->first();
            $ClientName = $cll->name;
        }

        $TILAAdmin = User::select('*')->where('id', 1)->first();

        $remove = array(
            'TILAAdmin' => $TILAAdmin->name,
            'TILAVP' => Auth::User()->name,
            'FirmName' => $firm->firm_name,
            'ClientName' => $ClientName,
            'CaseType' => $firmcase->case_category,
        );
        $email = EmailTemplate(28, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $TILAAdmin->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        FirmCase::select('*')->where('id', $case_id)->update(['status' => 6]);

        $msg = 'Case sent to review successfully!';
        $touser = User::where('id', $admintask->firm_admin_id)->first();
        $n_link = url('firm/case/show').'/'.$case_id;
        $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        return redirect('admin/usertask/overview/'.$id)->with('success','Case sent to review successfully!');
    }

    public function casefamily($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        
        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['Beneficiary','Principal Beneficiary','Derivative Beneficiary','Qualifying Family Member','Applicant/Beneficiary'])
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $case->firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
            $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->where('usermeta.meta_key', 'beneficiary')
        ->where('usermeta.meta_value', $case_id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        }
        else {
            $family_alllist = array();
            $beneficiary_list = array();
        }

        // $derivative_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'derivative')
        // ->where('usermeta.meta_value', $case_id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $interpreter_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'interpreter')
        // ->where('usermeta.meta_value', $case_id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $petitioner_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'petitioner')
        // ->where('usermeta.meta_value', $case_id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->get();

        // $Co_Sponsor = array();
        // $Co_Sponsor_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'Co_Sponsor')
        // ->where('usermeta.meta_value', $case_id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->first();
        // $Household_Member = array();
        // $Household_Member_arr = User::select('users.*', 'client_family.*', 'users.id as uid')
        // ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        // ->where('usermeta.meta_key', 'Household_Member')
        // ->where('usermeta.meta_value', $case_id)
        // ->where('users.role_id' ,'=', '7')
        // ->join('client_family', 'client_family.email', '=', 'users.email')
        // ->first();

        // if (!empty($Co_Sponsor_arr)) {
        //     $Co_Sponsor = $Co_Sponsor_arr;
        // }
        
        // if (!empty($Household_Member_arr)) {
        //     $Household_Member = $Household_Member_arr;
        // }
        
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
        return view('admin.adminuser.usertask.casefamily', compact('family_list','family_alllist', 'firm', 'case', 'admintask', 'client', 'countries', 'em', 'QuestionsArr'));
    }

    public function addcasefamily($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $case->firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }

        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $countries = DB::table("countries")->get();
        return view('admin.adminuser.usertask.addcasefamily', compact('id', 'firm', 'case', 'client', 'admintask', 'family_alllist', 'countries'));
    }

    public function addcasefamilymember($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $case->firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }

        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $countries = DB::table("countries")->get();
        return view('admin.adminuser.usertask.addcasefamilymember', compact('id', 'firm', 'case', 'client', 'admintask', 'family_alllist', 'countries'));
    }

    public function createcasefamily(Request $request) {
        $record = $request->all();
        $record['name'] = $request->first_name;
        if(!empty($request->middle_name)) {
            $record['name'] .= ' '.$request->middle_name;
        }
        if(!empty($request->last_name)) {
            $record['name'] .= ' '.$request->last_name;
        }
        $newdata = array();
        $current_firm_id = $request->firm_id;
        
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
            return redirect('admin/usertask/casefamily/' . $request->task_id)->with('success', 'Family created successfully!');
        } else {
            return redirect('admin/usertask/casefamily/' . $request->task_id)->with('error', 'Family not created, please try again');
        }
    }

    public function familyforms($tid, $id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$tid)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $family = User::select('*')->where('id', $id)->first();
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $family_information_forms = ClientInformation::select('client_information_forms.*', 'client_information_forms.id as info_id', 'client_information_forms.status as status1')
            ->where('client_information_forms.case_id',$case_id)
            ->whereIn('client_information_forms.client_id',[0, $id])
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
        return view('admin.adminuser.usertask.familyforms', compact('client', 'case', 'admintask', 'family_information_forms', 'family'));
    }

    public function addfamilyforms($tid, $id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$tid)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $family = ClientFamily::select('*')->where('id', $id)->first();
        $case = FirmCase::select('*')->where('id', $family->case_id)->first();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();    
        return view('admin.adminuser.usertask.addfamilyforms', compact('family', 'tid', 'id', 'firm', 'case', 'client', 'admintask'));
    }

    public function getFamilyForms(Request $request) {
        $query = $request->q;

        $data = json_encode(
            [
                'query' => $query
            ]
        );

        $response = $this->api_client->request(
            'POST', '/2/files/search_v2',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('DROPBOX_TOKEN'),
                    'Content-Type' => 'application/json'
                ],
                'body' => $data
        ]);

        $search_results = json_decode($response->getBody(), true);
        $matches = $search_results['matches'];
        $res = array();
        if($matches) {
            foreach ($matches as $k => $v) {
                $metadata = $v['metadata']['metadata'];
                extract($metadata);
                $res[] = array('Name' => $name, 'ID' => $metadata);
            }
        }
        echo json_encode($res);
    }

    public function createfamilyforms(Request $request) {
        $file_data = json_decode($request->file_data);
        $path = $file_data->id;
        $client = new \Spatie\Dropbox\Client(env('DROPBOX_TOKEN'));
        $a = $client->download($path);
        $file = 'forms/'.$file_data->name;
        Storage::put($file, stream_get_contents($a));
        $data = [
             'family_id' => $request->family_id,
             'case_id' => $request->case_id,
             'firm_id' => $request->firm_id,
             'file' => $file,
             'file_type' => $file_data->name   
        ];
        FamilyInformation::create($data);

        return redirect('admin/usertask/familyforms/'.$request->task_id.'/'.$request->family_id)->with('success','Family Form submited successfully!');
    }

    public function additional_service($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $client = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();

        $I_864_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor')->first()['VP_Pricing'];
        $I_864A_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'I-864A, Contract Between Sponsor and Household Member')->first()['VP_Pricing'];
        $I_DS260_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'DS-260 for Additional Derivative Beneficiary (online only)')->first()['VP_Pricing'];
        $I_Affidavit_Cost = CaseType::select('VP_Pricing')->where('Case_Type', 'Draft a Letter/Affidavit')->first()['VP_Pricing'];
        return view('admin.adminuser.usertask.additional_service', compact('admintask', 'case','firm', 'client', 'I_864_Cost', 'I_864A_Cost', 'I_DS260_Cost', 'I_Affidavit_Cost'));
    }

    public function case_affidavit($id)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $Affidavitdoc = AffidavitDocumentRequest::select('*')
                    ->where('case_id', $case_id)
                    ->get();
        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        return view('admin.adminuser.usertask.case_affidavit', compact('admintask', 'case','firm', 'Affidavitdoc'));
    }

    public function uploadaffidavitdocuments(Request $request)
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
        return redirect('admin/usertask/case_affidavit/'.$request->task_id)->with('success','Document upload successfully!');
    }

    function request_additional_service(Request $request) {
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
        return redirect('admin/usertask/additional_service/'.$request->task_id)->with('success','Additional Service Requested successfully!');
    }
    public function addfamilyincase(Request $request) {
        if($request->family_id) {
            // foreach ($request->family_id as $k => $v) {
                update_user_meta($request->family_id, 'CaseID', $request->case_id, 1);
                update_user_meta($request->family_id, 'beneficiary', $request->case_id, 1);
            // }
        }
    }
    public function addfamilymemberincase(Request $request) {
        update_user_meta($request->family_id, 'CaseID', $request->case_id, 1);
        $kk = 'memberof_'.$request->case_id;
        $vv = array(
                'memberof' => $request->member_of,
                'relationship' => $request->member_relationship
                );
        update_user_meta($request->family_id, $kk, json_encode($vv));
    }
    public function addderivativeincase(Request $request) {
        // pre($request->all());
        if($request->checked) {
            update_user_meta($request->family_id, $request->type, $request->case_id, 1);
        }
        else {
            $uc = DB::table("usermeta")->where("user_id", $request->family_id)->where("meta_key", $request->type)->where("meta_value", $request->case_id)->delete();
        }
    }
    public function createderivativeincase(Request $request) {
        $record = $request->all();
        $record['name'] = $request->first_name;
        if(!empty($request->middle_name)) {
            $record['name'] .= ' '.$request->middle_name;
        }
        if(!empty($request->last_name)) {
            $record['name'] .= ' '.$request->last_name;
        }
        $newdata = array();
        //$current_firm_id = Auth::User()->firm_id;
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'email' => 'string|email|unique:users|unique:new_client',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $current_firm_id = $case->firm_id;
        app('App\Http\Controllers\HomeController')->CreateClientFamily($record,$current_firm_id,$request->case_id);
        $record['dob'] = date('Y-m-d', strtotime($record['dob']));
        $check = ClientFamily::create($record);

        if ($check) {
            return redirect('admin/usertask/casefamily/' . $request->task_id)->with('success', 'Family created successfully!');
        } else {
            return redirect('admin/usertask/casefamily/' . $request->task_id)->with('error', 'Family not created, please try again');
        }
    }
    public function addcaseinterpreter($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('users.firm_id' ,'=', $case->firm_id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();

        $countries = DB::table("countries")->get();
        return view('admin.adminuser.usertask.addcaseinterpreter', compact('id', 'firm', 'case', 'client', 'admintask', 'family_alllist', 'countries'));
    }

    public function addcasepetitioner($id) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('users.firm_id' ,'=', $case->firm_id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        }

        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();

        $countries = DB::table("countries")->get();
        return view('admin.adminuser.usertask.addcasepetitioner', compact('id', 'firm', 'case', 'client', 'admintask', 'family_alllist', 'countries'));
    }

    public function updateformstatus(Request $request) {
        $data1 = array('status' => $request->status);
        ClientInformation::where('id', $request->doc_id)->update($data1);
    }

    public function editfamily($id, $fid) {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            ->where('users.firm_id' ,'=', $case->firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        }
        else {
            $family_alllist = array();
        }

        $firm_id = $case->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $countries = DB::table("countries")->get();

        $FamilyMember = get_user_meta($fid, 'FamilyMember');
        $FamilyMember = json_decode($FamilyMember);
        $r_states = array();
        if (!empty($FamilyMember->residence_address->country)) {
            $r_states = DB::table("regions")
                    ->where("country_id", $FamilyMember->residence_address->country)
                    ->pluck("name", "id");
        }
        return view('admin.adminuser.usertask.editfamily', compact('id', 'firm', 'case', 'client', 'admintask', 'family_alllist', 'countries', 'r_states', 'FamilyMember', 'fid'));
    }

    public function createtask() {
        $current_user = Auth::User();
        $firmadmins = User::select('id', 'name', 'firm_id')->where('role_id', 4)->get();
        $firmclient = User::select('id', 'name', 'firm_id')->where('role_id', 6)->get();
        return view('admin.adminuser.usertask.createtask', compact('firmadmins', 'firmclient', 'current_user'));
    }

    public function insertusertask(Request $request) {
        // pre($request->all());

        $data = Auth::User();

        if(!empty($request->firmclient)) {
            $client = Newclient::select('new_client.firm_id', 'firms.*', 'users.id as firm_admin_id')
                    ->where('new_client.user_id', $request->firmclient)
                    ->join('firms', 'firms.id', '=', 'new_client.firm_id')
                    ->join('users', 'users.email', '=', 'firms.email')
                    ->first();
            $firmid = '';
            if(!empty($client)) {
                $firmid = $client->firm_admin_id;
            }
            $client_task = $request->firmclient;
        }
        else {
            $firmid = $request->firmadmins;
        }
        $data3 = [
                    'client_task' => $request->firmclient,
                    'firm_admin_id' => $firmid,
                    'task_type' => 'ADMIN_TASK',
                    'task' => $request->task,
                    'mytask' => $request->description,
                    'case_id' => $data->id,
                    'allot_user_id' => $data->id,
                    'priority' => $request->priority,
                    'due_date' => $request->due_date,
                    'status' => 0
                ];
        // pre($data3);
        // die();
        AdminTask::create($data3);

        $touser = User::where('id',$request->firmadmins)->first();
        
        $n_link = url('firm/task');
        // $n_link = '#';
        $msg = $data->name.' has assigned a task for you. Please review';
        $message = collect(['title' => $msg, 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        Notification::send($touser, new DatabaseNotification($message));

        return redirect($request->redirect_url)->with('success', 'Task created successfully!');
    }

    public function rquestblueprintdocuments(Request $request) {
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

    public function uploaddocuments($id, $did)
    {
        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->join('firms', 'users.firm_id', '=', 'firms.id')
        ->where('admintask.id',$id)
        ->first();
        if($admintask->allot_user_id != Auth::User()->id) {
            return redirect('admin/usertask')->withInfo('You can not access this url!');
        }
        $case_id = $admintask->case_id;
        $client = array();
        $case = array();
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs1 = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs1->case_id;
        }
        $case = FirmCase::select('*')->where('id', $case_id)->first();
        $client_id = '';
        if(!empty($case)) {
            $client = Newclient::select('id')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                $client_id = $client->id;
            }
        }
        $docs2 = DocumentRequest::select('*')->where('id', $did)->first();
        $family_id = $docs2->family_id;
        $docs = DocumentRequest::select('*')
                ->where('case_id', $case_id)
                ->where('family_id', $family_id)
                ->get();
        return view('admin.adminuser.usertask.uploaddocuments', compact('client', 'case', 'admintask', 'client_id', 'docs', 'family_id'));
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
                $n_link = url('admin/usertask/documents').'/'.$record->id;
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
        return redirect('admin/usertask/documents/'.$request->tid)->withInfo('Document uploaded successfully!');
    }
}
