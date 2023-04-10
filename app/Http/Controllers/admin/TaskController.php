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
use App\Notifications\DatabaseNotification;
use Notification;
use App;
use DB;

class TaskController extends Controller {

    public function __construct() {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $vpuser = User::where('role_id', 2)->get();
        return view('admin.task.index', compact('vpuser'));
    }

    public function getData(Request $request) {
        //$data = Auth::User();
        
        if(!empty($request->vpuser) && empty($request->due_date)) {
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status')
                ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
                ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
                ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
                ->whereNotIn('admintask.task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request', 'Leave_Application_ACT', 'Additional_Service', 'FIRM_TASK'])
                ->where('admintask.status', $request->status)
                ->where('admintask.allot_user_id', $request->vpuser)
                ->get();
        }
        else if(empty($request->vpuser) && !empty($request->due_date)) {
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
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status')
                ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
                ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
                ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
                ->whereNotIn('admintask.task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request', 'Leave_Application_ACT', 'Additional_Service', 'FIRM_TASK'])
                ->where('admintask.status', $request->status)
                ->whereBetween('admintask.due_date', [$form, $to])
                ->get();
        }
        else if(!empty($request->vpuser) && !empty($request->due_date)) {
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
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status')
                ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
                ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
                ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
                ->whereNotIn('admintask.task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request', 'Leave_Application_ACT', 'Additional_Service', 'FIRM_TASK'])
                ->where('admintask.status', $request->status)
                ->where('admintask.allot_user_id', $request->vpuser)
                ->whereBetween('admintask.due_date', [$form, $to])
                ->get();
        }
        else {
            $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'case.status as case_status')
                ->leftjoin('case', 'admintask.case_id', '=', 'case.id')
                ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
                ->leftjoin('firms', 'users.firm_id', '=', 'firms.id')
                ->whereNotIn('admintask.task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request', 'Leave_Application_ACT', 'Additional_Service', 'FIRM_TASK'])
                ->where('admintask.status', $request->status)
                ->get();
        }
        foreach ($admintask as $key => $value) {
            // $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES";
            if($admintask[$key]->allot_user_id) {
                $lu1 = getUserName($value->allot_user_id);
                if($lu1) {
                    $admintask[$key]->allot_user_id = $lu1->name;
                }
                else {
                    $admintask[$key]->allot_user_id = 'Not Assign';
                }
            }
            else {
                $admintask[$key]->allot_user_id = 'Not Assign';
            }
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

            $admintask[$key]->priority = $result;

            $admintask[$key]->is_edit = True; 
            if($admintask[$key]->stat == 'Completed') {
                $admintask[$key]->is_edit = false; 
            }
            if($value->task_type == 'provide_a_quote') {
                $did = $value->case_id;
                $doc = DocumentRequest::select('quote')->where('id', $did)->first();
                $admintask[$key]->is_edit = $doc['quote']; 
                $quote = $doc['quote'];
                if($quote == 3) {
                    $admintask[$key]->is_edit = false;
                }
            }

            if($value->task_type == 'Leave_Application') {
                // $lu = getUserName($value->case_id);
                $admintask[$key]->task = 'Vacation Request';
                $admintask[$key]->mytask = 'Send by '.$admintask[$key]->allot_user_id;  
            }
            if(empty($value->mytask)) {
                $admintask[$key]->mytask = 'N/A';
            }
            if(empty($value->firm_name)) {
                $admintask[$key]->firm_name = 'N/A';
            }
            if(empty($value->due_date)) {
                $admintask[$key]->due_date = 'N/A';
            }

            $admintask[$key]->clink = '#';
            $admintask[$key]->cname = 'N/A';
            if($value->task_type == 'Assign_Case') {
                //$admintask[$key]->is_edit = True; 
                $firmcase = FirmCase::select('case.client_id', 'users.name as cname')
                            ->where('case.id', $value->case_id)
                            ->join('users', 'users.id', '=', 'case.client_id')
                            ->first();
                if($firmcase) {
                    $admintask[$key]->cname =  $firmcase->cname;  
                    $admintask[$key]->clink = url('admin/users/viewclient/'.$firmcase->client_id);
                }         
            }
            if($value->task_type == 'provide_a_quote' || $value->task_type == 'upload_translated_document') {
                $firmcase = DocumentRequest::select('document_request.family_id', 'users.name as cname')
                            ->where('document_request.id', $value->case_id)
                            ->join('users', 'users.id', '=', 'document_request.family_id')
                            ->first();
                if($firmcase) {
                    $admintask[$key]->cname =  $firmcase->cname;  
                    $admintask[$key]->clink = url('admin/users/viewclient/'.$firmcase->family_id);
                }
            }
            if($value->task_type == 'ADMIN_TASK') {
                $firmcase = User::select('users.name as cname')
                            ->where('id', $value->client_task)
                            ->first();
                if($firmcase) {
                    $admintask[$key]->cname =  $firmcase->cname; 
                    $admintask[$key]->clink = url('admin/users/viewclient/'.$value->client_task);
                }
            }
        }
        /* pre($admintask);
          die(); */

        return datatables()->of($admintask)->toJson();
    }

    public function show($id) {

        $admintask = AdminTask::select('admintask.*', 'firms.firm_name', 'cluser.id as clientid', 'cluser.name as clientname', 'cluser.email as clientemail', 'case.case_file_path', 'vauser.id as vauserid', 'vauser.name as vausername', 'vauser.email as vauseremail')
                ->join('case', 'admintask.case_id', '=', 'case.id')
                ->join('firms', 'case.firm_id', '=', 'firms.id')
                ->join('users as cluser', 'case.client_id', '=', 'cluser.id')
                ->join('users as vauser', 'admintask.allot_user_id', '=', 'vauser.id')
                ->where('admintask.id', $id)
                ->first();

        return view('admin.task.task_details', compact('admintask'));
    }

    public function timeline($case_id) {

        $caselog = Caselog::where('case_id', $case_id)->get();

        return view('admin.task.timeline', compact('caselog'));

        /* echo $id;
          die();

          $client = Client_profile::where('user_id', $id)->first();
          return view('firmadmin.client.show',compact('client')); */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        return view('admin.task.task_edit', compact('vauser', 'admintask', 'quote_cost', 'leave_event'));
    }

    public function update(Request $request) {
        // pre($request->all());
        // $record = AdminTask::select('*')->where('id', $request->task_id)->first();
        // pre($record);
        // die();
        $record = AdminTask::select('*')->where('id', $request->task_id)->first();
        if($record->task_type == 'Leave_Application') {
            $data = [
                'status' => 1
            ];
            AdminTask::where('id', $request->task_id)->update($data);
            $leave_title = 'Vacation Request '.$request->leave_act.' '.$request->leave_comment;
            $task_data = [
                    'firm_admin_id' => $record->allot_user_id,
                    'task_type' => 'Leave_Application_ACT',
                    'task' => $leave_title,
                    'case_id' => $record->allot_user_id,
                    'allot_user_id' => $record->allot_user_id,
                    // 'priority' => 'Normal',
                    'status' => 1
            ];
            AdminTask::create($task_data);
            //pre($task_data);
            if($request->leave_act == 'Approved') {
                $leave_event = Event::select('*')->where('related_id', $request->task_id)->first();
                $lead_event_data = [
                        'title' => "ADMIN"
                    ];
                Event::where('id', $leave_event->id)->update($lead_event_data);
            }
            $lmsg = 'Vacation Request '.$request->leave_act.' successfully!';
            return redirect('admin/task')->withInfo($lmsg);
        }
        else if($record->task_type == 'Assign_Case') {
            $assigned_user = User::select('*')->where('id', $request->vauser)->first();
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
                return redirect('admin/task')->withInfo($lmsg);
            }
        }
        $firm_admin = User::select('*')->where('id', $record->firm_admin_id)->first();

        $firm = Firm::select('*')->where('id', $firm_admin->firm_id)->first();

        if($record->task_type == 'upload_translated_document') {
            $client_file = array();
            if(!empty($request->file))
            {
                foreach ($request->file as $key => $file) {
                    $client_file[] = Storage::put('client_doc', $file);
                }
                if($client_file){
                    DocumentRequest::where('id', $record->case_id)->update(['document' => json_encode($client_file), 'status' => 2]);
                }
            }
            /* -------------- email firm user quote notification ------- */
            $doc = DocumentRequest::select('*')->where('id', $record->case_id)->first();
            $cl = Newclient::where('user_id', $doc->family_id)->first();
            if(!empty($cl)) {
                $remove = array(
                    'ClientName' => $cl->first_name.' '.$cl->middle_name.' '.$cl->last_name,
                    'FirmUser' => $firm->firm_name,
                    'Attachment' => asset('storage/app/'.$client_file[0])
                );
                $email = EmailTemplate(26, $remove);

                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => $firm->email,
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'translations@tilacaseprep.com'
                );
                send_mail($args);
            }
            /* -------------- email firm user quote notification ------- */
            // FirmCase::where('id', $request->case_id)->update(['status' => 7]);
            $data = [
                'status' => 1
            ];

            AdminTask::where('id', $request->task_id)->update($data);

            $task_data = [
                'firm_admin_id' => $record->firm_admin_id,
                'task_type' => 'Document_Action',
                'task' => ' Translated document upload successfully',
                'case_id' => $record->case_id,
                'allot_user_id' => $record->allot_user_id,
                'priority' => $record->priority,
                'status' => 1
            ];
            AdminTask::create($task_data);

            $msg = 'Translated document upload successfully';
            $touser = User::where('id', $record->firm_admin_id)->first();
            $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
            $message = collect(['title' => 'Translated document upload successfully', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);

            $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
            $usercase = User::where('id', $FirmCase->id)->first();
            if($usercase->id != $record->firm_admin_id) {
                Notification::send($usercase, new DatabaseNotification($message));
            }
            Notification::send($touser, new DatabaseNotification($message));
            return redirect('admin/task')->withInfo('Document upload successfully!');
        }
        extract($_REQUEST);
        if($firm->account_type == 'CMS') {
            $q = "select cc.name as ClientName,fu.name as FirmUserName,f.firm_admin_name as FirmName, f.email as FirmEmail,c.* from admintask as t,`case` as c,firms as f,users as fu,users as cc where cc.id=c.client_id and fu.id=c.user_id and f.id=c.firm_id and t.id='" . $request->task_id . "' and c.id=t.case_id";
        }
        else {
            // $q = "select fu.name as FirmUserName,f.firm_admin_name as FirmName, f.email as FirmEmail,c.* from admintask as t,`case` as c,firms as f,users as fu where t.id='" . $request->task_id . "' and c.id=t.case_id and f.id=c.firm_id and fu.firm_id=f.id";
            $q = "select cc.name as ClientName,fu.name as FirmUserName,f.firm_admin_name as FirmName, f.email as FirmEmail,c.* from admintask as t,`case` as c,firms as f,users as fu,users as cc where cc.id=c.client_id and fu.id=c.user_id and f.id=c.firm_id and t.id='" . $request->task_id . "' and c.id=t.case_id";
        }
        $Cases = DB::select(DB::raw($q));
        
        // pre($Cases);
        // pre($record);
        // die();

        if ($request->vauser !== 0) {
            $msg1 = 'Assign VA User successfully!';
            $data = [
                'allot_user_id' => $request->vauser,
                'priority' => $request->priority,
                'status' => 0
            ];
            AdminTask::where('id', $request->task_id)->update($data);
            

            if ($record->task_type == 'provide_a_quote') {
                $data2 = [
                    'case_cost' => $request->case_cost
                ];
                // FirmCase::where('id', $record->case_id)->update($data2);
                DocumentRequest::where('id', $record->case_id)->update(['quote' => 2, 'quote_cost' => $request->case_cost]);

                /* -------------- email firm user quote notification ------- */
                $doc = DocumentRequest::select('*')->where('id', $record->case_id)->first();
                $Paylink = url('firm/case/case_documents/'.$doc->case_id);
                $remove = array(
                    'FirmUser' => $firm_admin->firm_admin_name,
                    'Cost' => $request->case_cost,
                    'PaymentButton' => $Paylink
                );
                $email = EmailTemplate(21, $remove);

                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => $firm_admin->email,
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'translations@tilacaseprep.com'
                );
                send_mail($args);
                /* -------------- email firm user quote notification ------- */
                https://tila.in2.app.stoute.co/firm/case/show/214
                $msg = 'Tila Admin quote provided successfully for case #'.$doc->case_id;
                $touser = User::where('id', $record->firm_admin_id)->first();
                $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
                $message = collect(['title' => 'Assign VA User', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
                $usercase = User::where('id', $FirmCase->id)->first();
                if($usercase->id != $record->firm_admin_id) {
                    Notification::send($usercase, new DatabaseNotification($message));
                }
                Notification::send($touser, new DatabaseNotification($message));

                $msg1 = 'Quote provided successfully!';
            } 
            else if ($record->task_type != 'provide_a_quote') {
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
                // $msg1 = 'Quote provided successfully!';
                FirmCase::where('id', $record->case_id)->update(['status' => 2]);
                // if($record->task_type == 'Assign_Case') {
                //     AdminTask::where('case_id', $record->case_id)->where('allot_user_id', $record->allot_user_id)->update(['allot_user_id' => $request->vauser]);
                // }

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
                'message' => "Super Admin assign VA user " . $user_name->name . " to this task"
            ];
            if ($record->task_type != 'provide_a_quote') {
                Caselog::create($data1);
            }
            if($msg1 == 'Assign VA User successfully!') {
                // $q1 = 'select * from users where id="' . $vauser . '"';
                // $VA = DB::select(DB::raw($q1));
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
                if($touser->role_id == 1) {
                    $msg = 'Case accepted successfully. Happy work day!';
                    $touser = User::where('id', $record->firm_admin_id)->first();
                    $n_link = url('firm/case/show').'/'.$record->case_id;
                    $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                    Notification::send($touser, new DatabaseNotification($message));
                }
            }
            if($msg1 == 'Assign VA User successfully!') {
                $msg1 = 'Assignment of case to TILA VP, successful!';
            }
            return redirect('admin/task')->with('success', $msg1);
        } 
        else {
            return redirect('admin/task')->with('error', 'Task not assigned!');
        }
    }

    public function create() {
        $vauser = User::select('id', 'name')->where('firm_id', 0)->where('role_id', 2)->get();
        $firmclient = User::select('id', 'name')->where('role_id', 6)->get();
        return view('admin.task.create', compact('vauser', 'firmclient'));
    }

    public function create_task(Request $request) {
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
        $data3 = [
                    'client_task' => $client_task,
                    'firm_admin_id' => $firmid,
                    'task_type' => 'ADMIN_TASK',
                    'task' => $request->task,
                    'mytask' => $request->description,
                    'case_id' => $request->vauser,
                    'allot_user_id' => $request->vauser,
                    'priority' => $request->priority,
                    'due_date' => $request->due_date,
                    'status' => 0
                ];
        // pre($data3);
        // pre($request->all());
        // die();
        AdminTask::create($data3);
        $vauser = User::select('*')->where('id', $request->vauser)->first();
        $useremail = $vauser->email;
        $remove = array(
            'FirstName' => $vauser->name
        );
        $email = EmailTemplate(38, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $useremail,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );

        send_mail($args);
        return redirect($request->redirect_url)->with('success', 'Task created successfully!');
    }

    public function edit1($id) {

        $vauser = User::select('id', 'name')->where('firm_id', 0)->where('role_id', 2)->get();
        $firmadmin = User::select('id', 'name')->where('role_id', 4)->get();
        $admintask = AdminTask::select('*')->where('id', $id)->first();
        $vauser = User::select('id', 'name')->where('firm_id', 0)->where('role_id', 2)->get();
        $firmclient = User::select('id', 'name')->where('role_id', 6)->get();
        return view('admin.task.edit1', compact('vauser', 'admintask', 'firmadmin', 'firmclient'));
    }

    public function update_task1(Request $request) {
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
        $data3 = [
                    'client_task' => $client_task,
                    'firm_admin_id' => $firmid,
                    'task_type' => 'ADMIN_TASK',
                    'task' => $request->task,
                    'mytask' => $request->description,
                    'case_id' => $request->vauser,
                    'allot_user_id' => $request->vauser,
                    'priority' => $request->priority,
                    'due_date' => $request->due_date,
                    'status' => $request->status
                ];
        AdminTask::where('id', $request->id)->update($data3);
        return redirect('admin/task')->with('success', 'Task update successfully!');
    }

    public function delete_firm_account($id) {
        $admintask = AdminTask::select('*')->where('id', $id)->first();
        $data = [
            'status' => 1
        ];
        AdminTask::where('id', $id)->update($data);
        Firm::where('id', $admintask->case_id)->update(['status' => 0]);
        User::where('firm_id', $admintask->case_id)->update(['status' => 0]);
        FirmCase::where('firm_id', $admintask->case_id)->update(['status' => 2]);

        $firmadmin = User::where('firm_id', $admintask->case_id)->where('role_id', 4)->first();

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
        return redirect('admin/task')->with('success', 'Firm Account deactivate successfully!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /* public function delete($id)
      {
      User::where('id', $id)->delete();
      return redirect('firm/client')->withInfo('Firm client deleted successfully!');
      } */

    /* public function roles()
      {
      return response()->json(Role::get());
      } */
}
