<?php
namespace App\Http\Controllers\firmuser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\Caselog;
use App\User;
use Twilio\Rest\Client;

class FirmUserTaskController extends Controller
{
	public function index(Request $request)
	{
		return view('firmadmin.firmuser.task.index');
	}
	public function getData()
	{ 
		$data = Auth::User();
		$admintask = AdminTask::select('admintask.*','firms.firm_name')
        // ->join('case', 'admintask.case_id', '=', 'case.id')
		->join('users', 'admintask.firm_admin_id', '=', 'users.id')
		->join('firms', 'firms.id', '=', 'users.firm_id')
		->where('admintask.firm_admin_id', $data->id)
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
				$result = "NOT SET";
			}
			switch ($value->status) {
				case 0:
				$result1 = "Open";
				break;
				case 1:
				$result1 = "Close";
				break;
				default:
				$result1 = "NOT SET";
			}
			$admintask[$key]->priority =  $result;
			$admintask[$key]->status =  $result1;
		}
        /*pre($admintask);
        die();*/
        
        return datatables()->of($admintask)->toJson();        
    }
    public function show($id)
    {

    	$admintask = AdminTask::select('admintask.*','firms.firm_name')
	        // ->join('case', 'admintask.case_id', '=', 'case.id')
    	->join('users', 'admintask.firm_admin_id', '=', 'users.id')
    	->join('firms', 'firms.id', '=', 'users.firm_id')
    	->where('admintask.id', $id)
    	->first(); 
    	return view('firmadmin.firmuser.task.task_details',compact('admintask'));
    }
}