<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Http\Request;
use App\User;
use App\Models\FirmCase;
use App\Models\AdminTask;
use App\Models\Newclient;
use App\Models\DocumentRequest;
use App\Models\ClientInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\CaseType;
use App\Models\Firm;
use DB;
use App;
use App\Notifications\DatabaseNotification;
use Notification;

class DocumentRequestController extends Controller
{
    public function __construct()
    {

    }

    public function document_request($id)
    {
    	$data = Auth::User();
        $clients = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        // ->where('users.firm_id' ,'=', $data->firm_id)
        ->get();

        $firmcase = FirmCase::select('*')->where('id', $id)->first();
        $client_id = '';
        $already_requested = array();
        $CaseTypes = array();
        if(!empty($firmcase)) {
            $client = Newclient::select('id')->where('user_id', $firmcase->client_id)->first();
            $client_id = $client->id;
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $firmcase->case_category)
            ->where('Case_Type', $firmcase->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);

            $docs = DocumentRequest::select('document_request.*','new_client.*', 'document_request.status as dstatus', 'document_request.id as did')
            ->join('new_client', 'new_client.id', 'document_request.client_id')
            ->where('document_request.case_id', $id)
            ->get(); 

            
            if(!empty($docs)) {
                foreach ($docs as $key => $value) {
                    $already_requested[] = $value->document_type;
                }
            }
        }
        // pre($CaseTypes);
        // pre($already_requested);
        // die();
        return view('admin.adminuser.documentrequest.index',compact('id', 'client_id', 'CaseTypes', 'already_requested'));
    }
    public function getDataDocument($id)
    { 
        
        $firmcase = FirmCase::select('*')->where('id', $id)->first();
        $firm = Firm::select('*')->where('id', $firmcase->firm_id)->first();
        if($firm->account_type == 'CMS') {
            $users = DocumentRequest::select('document_request.*', 'document_request.status as dstatus', 'document_request.id as did')
            // ->join('new_client', 'new_client.user_id', 'document_request.family_id')
            ->where('document_request.case_id', $id)
            ->get();
        }   
        else {
            $users = DocumentRequest::select('document_request.*', 'document_request.status as dstatus', 'document_request.id as did')
            // ->join('new_client', 'new_client.id', 'document_request.client_id')
            ->where('document_request.case_id', $id)
            ->get();
        }
        foreach ($users as $key => $user) {
            if($firm->account_type == 'CMS') {
        	   $users[$key]->name = $user->first_name.' '.$user->last_name. ' - Client';
            }
            else {
                $users[$key]->name = '';
            }
            if($user->family_id) {
                $uu = getUserName($user->family_id);
                if(!empty($uu)) {
                    $users[$key]->name = $uu->name.' - Family';
                }
                else {
                    $users[$key]->name = '';
                }
            }
            $users[$key]->dstatus1 = $users[$key]->dstatus;
            if($users[$key]->dstatus == 4) {
                $users[$key]->dstatus = 'Rejected';
            }
            else if($users[$key]->dstatus == 3) {
                $users[$key]->dstatus = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->dstatus = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->dstatus = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->dstatus = 'Paid for translation';
                }
            }
        	else if($users[$key]->dstatus == 2) {
                $users[$key]->dstatus = 'Accepted';
            }
            else if($users[$key]->dstatus == 1) {
                $users[$key]->dstatus = 'Submitted';
            }
            else {
                $users[$key]->dstatus = 'Requested';
            }
        	$users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
        }
        return datatables()->of($users)->toJson();        
    }

    public function getFamilyDocument($id, $fid)
    { 
        
        // $users = DocumentRequest::select('document_request.*','new_client.*', 'document_request.status as dstatus', 'document_request.id as did')
        // ->join('new_client', 'new_client.id', 'document_request.client_id')
        // ->where('document_request.case_id', $id)
        // ->where('document_request.family_id', $fid)
        // ->get();   
        $users = DocumentRequest::select('*','status as dstatus', 'id as did')
        ->where('case_id', $id)
        ->where('family_id', $fid)
        ->get(); 
        foreach ($users as $key => $user) {
            
            if($user->family_id) {
                $uu = getUserName($user->family_id);
                $users[$key]->name = $uu->name.' - Family';
            }
            else {
                $uu = getUserName($user->family_id);
                $users[$key]->name = $uu->name. ' - Client';
            }
            $users[$key]->dstatus1 = $users[$key]->dstatus;
            if($users[$key]->dstatus == 4) {
                $users[$key]->dstatus = 'Rejected';
            }
            else if($users[$key]->dstatus == 3) {
                $users[$key]->dstatus = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->dstatus = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->dstatus = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->dstatus = 'Paid for translation';
                }
            }
            else if($users[$key]->dstatus == 2) {
                $users[$key]->dstatus = 'Accepted';
            }
            else if($users[$key]->dstatus == 1) {
                $users[$key]->dstatus = 'Submitted';
            }
            else {
                $users[$key]->dstatus = 'Requested';
            }
            $users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
        }
        return datatables()->of($users)->toJson();        
    }
    public function client_Cases($id)
    {
    	$states = FirmCase::select("*")
                    ->where("client_id",$id)
                    ->pluck("id","id");
        return response()->json($states);
    }

    public function setDataDocument(Request $request) {
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

    public function setDataDocument2(Request $request) {
        $client_file = array();

        if(!empty($request->file))
        {
            foreach ($request->file as $key => $file) {
                $client_file[] = Storage::put('client_doc', $file);
            }
            if($client_file){
                DocumentRequest::where('id', $request->id)->update(['document' => json_encode($client_file), 'status' => 2]);
            }
        }
        /* -------------- email firm user quote notification ------- */
        $doc = DocumentRequest::select('*')->where('id', $request->id)->first();
        $cl = Newclient::where('id', $doc->client_id)->first();
        $firm = Firm::select('*')->where('id', $cl->firm_id)->first();
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
        /* -------------- email firm user quote notification ------- */
        FirmCase::where('id', $request->case_id)->update(['status' => 7]);
        return redirect('admin/document_request/'.$request->case_id)->withInfo('Document upload successfully!');
    }

    public function completeDocument(Request $request, $id) {
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
        if(!empty($task)) { 
            AdminTask::where('id', $task->id)->update(['status' => 1]);
        }
        $task = AdminTask::select('*')
                ->where('case_id', $request->case_id)
                ->where('task_type', 'Assign_Case')
                ->where('allot_user_id', $data1->id)->first();
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
}