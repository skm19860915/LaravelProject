<?php
namespace App\Http\Controllers\firmadmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\Caselog;
use App\Models\DocumentRequest;
use App\Models\ClientTask;
use App\Models\Newclient;
use App\User;
use Twilio\Rest\Client;
use App\Notifications\DatabaseNotification;
use Notification;

class FirmTaskController extends Controller
{
	public function index(Request $request)
	{
		$data = Auth::User();
		$firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
		$users = User::select('users.*', 'firms.*', 'users.id as uid')
		->join('firms', 'firms.email', '=', 'users.email')
        ->where('users.firm_id',$data->firm_id)
        ->first(); 
		return view('firmadmin.task.index', compact('users', 'firm'));
	}
	public function getData(Request $request)
	{ 
		$data = Auth::User();
		$users = User::select('users.*', 'firms.*', 'users.id as uid')
		->join('firms', 'firms.email', '=', 'users.email')
        ->where('users.firm_id',$data->firm_id)
        ->first(); 
		$admintask = AdminTask::select('admintask.*','firms.firm_name')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
		->join('firms', 'firms.id', '=', 'users.firm_id')
		->where('admintask.firm_admin_id', $users->uid)
		->where('admintask.status', $request->status)
		->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training', 'DELETE_ACCOUNT', 'Required_Document_Request', 'Upload_Required_Document'])
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
				$result1 = "Opened";
				break;
				case 1:
				$result1 = "Completed";
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
	        $client = FirmCase::select('users.name', 'case.case_type as case_type', 'new_client.id as clientid')
	        		->join('users', 'users.id', '=', 'case.client_id')
	        		->leftJoin('new_client', 'new_client.user_id', '=', 'case.client_id')
	        		->where('case.id',$admintask[$key]->case_id)
	        		// ->where('case.id',$admintask[$key]->case_id)
	        		->first();
	        $admintask[$key]->clink = '#';
	        if(!empty($client)) {		
		        $admintask[$key]->client = $client->name;
		        $admintask[$key]->case_type = $client->case_type;
		        $admintask[$key]->clink = url('firm/client/show/'.$client->clientid);
		    }
		    else {
		    	$client = FirmCase::select('case.case_type as case_type')
	        		// ->join('users', 'users.id', '=', 'case.client_id')
	        		->where('case.id',$admintask[$key]->case_id)
	        		// ->where('case.id',$admintask[$key]->case_id)
	        		->first();
		    	$admintask[$key]->client = 'N/A';
		    	if(!empty($client)) {
		    		$admintask[$key]->case_type = $client->case_type;
		    	}
		    	else {
		    		$admintask[$key]->case_type = '';	
		    	}
		    }
		    if($value->task_type == 'ADMIN_TASK' && !empty($value->client_task))
            {
            	$admintask[$key]->case_type = 'N/A';
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
                    $admintask[$key]->client = $cname1; 
                    $admintask[$key]->clink = url('firm/client/client_task/'.$firmcase->id);
                }
            }
			$admintask[$key]->priority =  $result;
			$admintask[$key]->status =  $result1;
		}
        // pre($admintask);
        // die();
        
        return datatables()->of($admintask)->toJson();        
    }
    public function getCasetask(Request $request)
	{

		$data = Auth::User();
		$users = User::select('users.*', 'firms.*', 'users.id as uid')
		->join('firms', 'firms.email', '=', 'users.email')
        ->where('users.firm_id',$data->firm_id)
        ->first(); 
		$admintask = AdminTask::select('admintask.*','firms.firm_name', 'case.id as cid')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
		->join('firms', 'firms.id', '=', 'users.firm_id')
		->join('case', 'admintask.case_id', '=', 'case.id')
		->where('admintask.firm_admin_id', $users->uid)
		->where('case.user_id', $data->id)
		->where('admintask.status', $request->status)
		->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
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
	        		->where('case.id',$admintask[$key]->case_id)
	        		->first();
	        if(!empty($client)) {		
		        $admintask[$key]->client = $client->name;
		        $admintask[$key]->case_type = $client->case_type;
		    }
		    else {
		    	$admintask[$key]->client = 'Not Found';
		    	$admintask[$key]->case_type = 'Not Found';
		    }
			$admintask[$key]->priority =  $result;
			$admintask[$key]->status =  $result1;
		}
        
        return datatables()->of($admintask)->toJson();        
     
		// $data = Auth::User();
		// $admintask = ClientTask::select('client_task.*', 'case.*')
		// 			->join('case', 'client_task.related_id', 'case.id')
		// 			->where('case.firm_id', $data->firm_id)
		// 			->where('client_task.task_for', 'CASE')
		// 			->get();
  //       return datatables()->of($admintask)->toJson();        
    }
	public function show($id)
	{
		     
	    $admintask = AdminTask::select('admintask.*','firms.firm_name')
	        // ->join('case', 'admintask.case_id', '=', 'case.id')
			->join('users', 'admintask.firm_admin_id', '=', 'users.id')
			->join('firms', 'firms.id', '=', 'users.firm_id')
			->where('admintask.id', $id)
			->first(); 
		return view('firmadmin.task.task_details',compact('admintask'));
	}
	public function delete($id)
	{
		AdminTask::where('id', $id)->delete();
		return redirect('firm/task')->with('success','Firm task deleted successfully!');
	}

	public function create() {
		$data = Auth::User();
		
		$firm = User::select('users.*', 'firms.*', 'users.id as uid')
		->join('firms', 'firms.email', '=', 'users.email')
        ->where('users.firm_id',$data->firm_id)
        ->first(); 
        if($firm->account_type == 'VP Services') {
        	$vauser = User::select('id', 'name')->where('firm_id', $data->firm_id)->where('role_id', 6)->get();
        }
        else {
        	$vauser = User::select('id', 'name')->where('firm_id', $data->firm_id)->where('role_id', 5)->get();
        }
        return view('firmadmin.task.create', compact('vauser', 'firm'));
    }

    public function create_task(Request $request) {
        $data3 = [
                    'firm_admin_id' => Auth::User()->id,
                    'task_type' => 'FIRM_TASK',
                    'task' => $request->task,
                    'mytask' => $request->description,
                    'case_id' => $request->vauser,
                    'allot_user_id' => $request->vauser,
                    'priority' => $request->priority,
                    'status' => 0
                ];
        AdminTask::create($data3);
        return redirect('firm/task')->with('success', 'Task created successfully!');
    }
}