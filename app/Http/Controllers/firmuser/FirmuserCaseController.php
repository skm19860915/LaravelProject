<?php
namespace App\Http\Controllers\firmuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\User;
use Twilio\Rest\Client;
use App\Models\FirmCase;
class FirmuserCaseController extends Controller
{
	public function usercase(Request $request)
	{
		return view('firmadmin.firmuser.case.index');	
	}
	public function mycase(Request $request)
	{
		return view('firmadmin.firmuser.case.index');
	}
	public function allcase(Request $request)
	{
		return view('firmadmin.firmuser.case.index');
	}
	public function getData()
    { 
        $data = Auth::User();
        if(isset($_GET['case']) && $_GET['case'] == 'firm.usercase.allcase') {
            $case = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
            ->join('users', 'case.user_id', '=', 'users.id')
            ->join('users as ur', 'case.client_id', '=', 'ur.id')
            ->where('case.firm_id',$data->firm_id)
            ->get();
        }
        else {
            $case = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
            ->join('users', 'case.user_id', '=', 'users.id')
            ->join('users as ur', 'case.client_id', '=', 'ur.id')
            ->where('case.firm_id',$data->firm_id)
            ->where('case.user_id',$data->id)
            ->get();   
        }
        
        return datatables()->of($case)->toJson();
        
    }
    public function show($id)
    {
        $firm_id = Auth::User()->firm_id;
        $casedata = FirmCase::select('case.*','users.name as user_name','ur.name as client_name')
        ->join('users', 'case.user_id', '=', 'users.id')
        ->join('users as ur', 'case.client_id', '=', 'ur.id')
        ->where('case.id',$id)
        ->first();


        return view('firmadmin.firmuser.case.show',compact('casedata'));
    }
}