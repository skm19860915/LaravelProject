<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\AdminTask;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\Transaction;
use App\Models\DocumentRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use Mail;
use DB;

class DashboardController extends Controller
{
	public function __invoke(Request $request)
	{

		$currunt_user = Auth::User();
        $admintask = AdminTask::select('admintask.*','firms.firm_name')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->whereNotIn('admintask.task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request', 'Additional_Service'])
        ->orderBy('created_at', 'DESC')
        ->limit(10)
        ->get();
        foreach ($admintask as $key => $value) {
            $admintask[$key]->allot_user_id = ($value->allot_user_id == 0) ? "NO" : "YES";
            $admintask[$key]->stat = ($value->status == 0) ? "Open" : "Complete";
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
            $ccid = $value->case_id;
            $admintask[$key]->is_edit = True; 
            if($admintask[$key]->stat == 'Complete') {
                $admintask[$key]->is_edit = false; 
            }
            if($value->task_type == 'provide_a_quote') {
                $doc = DocumentRequest::select('quote', 'case_id')->where('id', $ccid)->first();
                $admintask[$key]->is_edit = $doc['quote']; 
                $ccid = $doc['case_id'];
                $quote = $doc['quote'];
                if($quote == 3) {
                    $admintask[$key]->is_edit = false;
                }
            }
            $admintask[$key]->priority = $result;
            $case = FirmCase::select('case_type')->where('id', $ccid)->first();
            $admintask[$key]->case_type = $case['case_type'];
        }
        $count = [];
        $count['firm'] = Firm::where('status',1)->count();
        $count['cmsfirm'] = Firm::where('status',1)->where('account_type', 'CMS')->count();
        $count['vpfirm'] = Firm::where('status',1)->where('account_type', 'VP Services')->count();
        $count['vauser'] = User::where('role_id',2)->where('firm_id',0)->count();
        $count['revenue'] = Transaction::select('transactions.*')
                                ->where('transactions.paymenttype', '!=', 4)
                                ->join('users', 'users.id', '=', 'transactions.user_id')
                                ->join('firms', 'firms.id', '=', 'users.firm_id')
                                // ->where('firms.account_type', '=', 'VP Services')
                                ->sum('transactions.amount');
        $count['revenue'] = ($count['revenue'])/100;

        $count['cmsrevenue'] = Transaction::select('transactions.*')
                                ->where('transactions.paymenttype', '!=', 4)
                                ->join('users', 'users.id', '=', 'transactions.user_id')
                                ->join('firms', 'firms.id', '=', 'users.firm_id')
                                ->where('firms.account_type', '=', 'CMS')
                                ->sum('transactions.amount');
        $count['cmsrevenue'] = ($count['cmsrevenue'])/100;

        $count['vprevenue'] = Transaction::select('transactions.*')
                                ->where('transactions.paymenttype', '!=', 4)
                                ->join('users', 'users.id', '=', 'transactions.user_id')
                                ->join('firms', 'firms.id', '=', 'users.firm_id')
                                ->where('firms.account_type', '=', 'VP Services')
                                ->sum('transactions.amount');
        $count['vprevenue'] = ($count['vprevenue'])/100;

        $count['task'] = AdminTask::select('*')
                    ->whereNotIn('task_type', ['Upload_Required_Document', 'Document_Action', 'Required_Document_Request'])
                    ->where('status', 0)
                    ->count();
		return view('admin.dashboard.index', compact('admintask','count'));
	}

    public function adminbilling() {
        $firms = Firm::select('firms.*', 'users.id as user_id')
                ->join('users', 'users.email', '=', 'firms.email')
                ->get();
        $t = Transaction::select('amount')
            ->whereNotIn('type', ['Invoice'])
            ->sum('amount');
        $t = '$'.number_format(($t/100), 2);
        return view('admin.dashboard.billing', compact('firms', 't'));
    }

    public function getAdminBillingData(Request $request)
    { 
        $data = Auth::User();

        $q = "SELECT t.*, u1.name as username, f.firm_name, f.account_type FROM `transactions` as t
                LEFT JOIN `users` as u1 ON t.user_id = u1.id
                LEFT JOIN `firms` as f ON f.id = u1.firm_id WHERE 1 = 1";

        if(!empty($request->type)) {
            $q .= " AND t.type = '$request->type'";
        }
        if(!empty($request->pastdate)) {
            $d = $request->pastdate;
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime("-$d day", strtotime($to)));
            $q .= " AND t.created_at BETWEEN '$from' AND '$to'";
        }
        if(!empty($request->from) && !empty($request->to)) {
            $f = $request->from;
            $t = $request->to;
            $f = explode('/', $request->from);
            $t = explode('/', $request->to);

            $from = $f[2] . '-' . $f[0] . '-' . $f[1];
            $to = $t[2] . '-' . $t[0] . '-' . $t[1];
            $q .= " AND t.created_at BETWEEN '$from' AND '$to'";
        }

        $Transaction = DB::select(DB::raw($q));

        foreach ($Transaction as $key => $value) {
            $Transaction[$key]->name = '';
            if(!empty($value->type) && $value->type != 'User') {
                // $case = FirmCase::select('case_type')->where('id', $value->related_id)->first();
                // $Transaction[$key]->name = $case->case_type;
            }
            else if(!empty($value->type) && $value->type == 'User') {
                $Transaction[$key]->name = 'Monthly user cost';
            }
            $Transaction[$key]->amount = '$'.number_format(($value->amount/100), 2);
        }
        return datatables()->of($Transaction)->toJson();
    }

    public function markasread()
    {
        Auth::User()->notifications->markAsRead();
        return redirect('admin/dashboard')->with('success','Notifications clear successfully ');
    }
}