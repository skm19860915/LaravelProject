<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Http\Request;
use App\User;
use App\Models\AdminTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App;

class UserCaseController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function index()
    {
        return view('admin.adminuser.usertask.index');
    }*/


    public function readycase()
    {
        return view('admin.adminuser.userreadycase.index');
    }


    public function getDataReady()
    { 
        
        $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        ->where('case.status',4)
        ->get(); 
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            $admintask[$key]->stat = ($value->case_status == 2) ? "Cancled" : "Active";
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
            $admintask[$key]->priority =  $result;
        }

        return datatables()->of($admintask)->toJson();        
    }


    public function pendingcase()
    {
        return view('admin.adminuser.userpendingcase.index');
    }


    public function getDataPending()
    { 
        
        $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        ->where('case.status',5)
        ->get(); 
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            $admintask[$key]->stat = ($value->case_status == 2) ? "Cancled" : "Active";
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
        }

        return datatables()->of($admintask)->toJson();        
    }


    public function complitcase()
    {
        return view('admin.adminuser.usercomplitcase.index');
    }


    public function getDataComplit()
    { 
        
        $admintask = AdminTask::select('admintask.*','firms.firm_name','case.status as case_status')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        ->where('case.status',3)
        ->get(); 
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            $admintask[$key]->stat = ($value->case_status == 2) ? "Cancled" : "Active";
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
            $admintask[$key]->priority =  $result;
        }

        return datatables()->of($admintask)->toJson();        
    }

    public function all_case()
    {
        return view('admin.adminuser.all_case.index');
    }


    public function getDataAll(Request $request)
    { 
        if(!empty($request->status)) {
            $st = array();
            if($request->status == 'Open') {
                $st = array(1,2);
            }
            else if($request->status == 'Working') {
                $st = array(3,4,5,7);
            }
            else if($request->status == 'InReview') {
                $st = array(6);
            }
            else if($request->status == 'Complete') {
                $st = array(9);
            }
            else if($request->status == 'InComplete') {
                $st = array(8);
            }
            $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at', 'u1.name as clientname', 'admintask.created_at as assigned_date')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
            ->where('admintask.task_type', 'Assign_Case')
            ->whereIn('case.status',$st)
            ->where('admintask.status', 1)
            ->get(); 
        }
        else {
            $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at', 'u1.name as clientname', 'admintask.created_at as assigned_date')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.status', 1)
            ->get(); 
        }
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES" ;
            $admintask[$key]->stat = GetCaseStatus($value->case_status);
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
            if(empty($value->clientname)) {
                $admintask[$key]->clientname = 'N/A';
            }
            else {
                $admintask[$key]->clink = url('admin/userclient/clientcases/'.$value->client_id);
            }
        }

        return datatables()->of($admintask)->toJson();        
    }


}
