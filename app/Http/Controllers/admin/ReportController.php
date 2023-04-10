<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpfullTips;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\AdminTask;
use App\Models\CaseType;

use App;
use DB;

class ReportController extends Controller {

    public function __construct() {
        //$this->authorizeResource(User::class);
    }

    public function firmDetails_report() {
        return view('admin.report.firmDetails');
    }

    public function firmDetails_getData() {
        $firms = Firm::all();
        foreach ($firms as $key => $value) {

            $firms[$key]->user_count = User::where('firm_id', $value->id)->count();
            $firms[$key]->CaseCount = FirmCase::where('firm_id', $value->id)->count();
            $firms[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
        }
        //$this->authorize(User::class, 'index');
        return datatables()->of($firms)->toJson();
    }

    public function firmUse_report() {

        return view('admin.report.firmUse');
    }

    public function firmUse_getData() {
        $recordFirmUses = [];
        switch ($_POST['reporttype']) {
            case 1:
                $recordFirmUses = Firm::select('firms.*')
                        ->where('firms.account_type', "CMS")
                        ->join('case', 'case.firm_id', '=', 'firms.id')
                        ->where('case.VP_Assistance', '=', 1)
                        ->groupBy('firms.id')
                        ->get();
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
            case 2:
                $recordFirmUses = Firm::select('*')->where('account_type', "VP Services")->get();
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
            case 3:
                    $recordFirmUses = Firm::select('firms.*')
                        ->where('firms.account_type', "CMS")
                        ->join('case', 'case.firm_id', '=', 'firms.id')
                        ->join('document_request', 'document_request.case_id', '=', 'case.id')
                        ->where('document_request.quote', '=', 3)
                        ->groupBy('firms.id')
                        ->get();
                
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
            case 4:
                $recordFirmUses = Firm::select('firms.*')
                        ->where('firms.account_type', "CMS")
                        ->join('case', 'case.firm_id', '=', 'firms.id')
                        ->join('document_request', 'document_request.case_id', '=', 'case.id')
                        ->where('document_request.quote', '=', 3)
                        ->where('case.VP_Assistance', '=', 1)
                        ->groupBy('firms.id')
                        ->get();
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
            case 5:
                $recordFirmUses = Firm::select('firms.*')
                        // ->where('firms.account_type', "CMS")
                        ->join('case', 'case.firm_id', '=', 'firms.id')
                        ->join('document_request', 'document_request.case_id', '=', 'case.id')
                        ->where('document_request.quote', '=', 3)
                        // ->where('case.VP_Assistance', '=', 1)
                        ->groupBy('firms.id')
                        ->get();
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
            case 6:
                $recordFirmUses = Firm::select('*')->where('account_type', "CMS")->get();
                foreach ($recordFirmUses as $key => $value) {
                    $recordFirmUses[$key]->stat = ($value->status == 1) ? "Active" : "Unactive";
                }
                break;
        }
        return datatables()->of($recordFirmUses)->toJson();
    }

    public function financialFirm_report() {
        return view('admin.report.financialFirm');
    }

    public function financialFirm_getData() {
        /* $tips = HelpfullTips::select()->get();
          foreach ($tips as $key => $value) {
          $tips[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
          }
          return datatables()->of($tips)->toJson(); */
    }

    /* ------------------New Reports----------- */

    public function FirmCase_report() {
        $firms = Firm::select('id', 'firm_admin_name')->get();
        $case_type = CaseType::select('Case_Category', 'Case_Type')->get();
        return view('admin.report.firmcase', compact('firms', 'case_type'));
    }

    public function VaCase_report() {
        $users = User::where('role_id', 2)->get();
        $case_type = CaseType::select('Case_Category', 'Case_Type')->get();
        return view('admin.report.vacase', compact('users', 'case_type'));
    }

    public function Financial() {
        return view('admin.report.Financial');
    }

    public function financialgetdate() {
        $rtype = '';
        switch ($_POST['reporttype']) {
            case 1:
                $rtype = ' and f.account_type = "CMS" and f.is_vp_services = 1';
                break;
            case 2:
                $rtype = ' and f.account_type = "VP Services"';
                break;
            case 3:
                $rtype = ' and f.account_type = "CMS" and f.translation = 1';
                break;
            case 4:
                $rtype = ' and f.account_type = "CMS" and f.translation = 1 and f.is_vp_services = 1';
                break;
            case 5:
                $rtype = ' and f.translation = 1';
                break;
        }

        $q = "SELECT * FROM transactions as t,users as u, firms as f where  t.user_id=u.id and f.id = u.firm_id and u.role_id=4 and t.paymenttype<4 $rtype";
        $Financial = DB::select(DB::raw($q));
        foreach ($Financial as $k => $v) {
            $Financial[$k]->paymenttype = PaymentType($Financial[$k]->paymenttype);
            $Financial[$k]->amount = '$' . number_format($Financial[$k]->amount, 2);
        }
        return datatables()->of($Financial)->toJson();
    }

    public function FirmCase_getDate(Request $request) {

        if(!empty($request->firm) && empty($request->Case_Type)) {
            $q = "SELECT f.firm_admin_name as FirmName,c.CourtDates,c.status as casestatus,c.id as cid,c.created_at as crd,c.updated_at as upd,c.case_type,u.*,f.* FROM users as u,firms as f,`case` as c where u.role_id=4 and f.id=u.firm_id and c.firm_id=f.id and c.status = 9 and c.firm_id = '$request->firm'";
        }
        else if(empty($request->firm) && !empty($request->Case_Type)) {
            $q = "SELECT f.firm_admin_name as FirmName,c.CourtDates,c.status as casestatus,c.id as cid,c.created_at as crd,c.updated_at as upd,c.case_type,u.*,f.* FROM users as u,firms as f,`case` as c where u.role_id=4 and f.id=u.firm_id and c.firm_id=f.id and c.status = 9 and c.case_type = '$request->Case_Type'";
        }
        else if(!empty($request->firm) && !empty($request->Case_Type)) {
            $q = "SELECT f.firm_admin_name as FirmName,c.CourtDates,c.status as casestatus,c.id as cid,c.created_at as crd,c.updated_at as upd,c.case_type,u.*,f.* FROM users as u,firms as f,`case` as c where u.role_id=4 and f.id=u.firm_id and c.firm_id=f.id and c.status = 9 and c.firm_id = '$request->firm' and c.case_type = '$request->Case_Type'";
        }
        else {
            $q = "SELECT f.firm_admin_name as FirmName,c.CourtDates,c.status as casestatus,c.id as cid,c.created_at as crd,c.updated_at as upd,c.case_type,u.*,f.* FROM users as u,firms as f,`case` as c where u.role_id=4 and f.id=u.firm_id and c.firm_id=f.id and c.status = 9";
        }
        
        $firms = DB::select(DB::raw($q));

        //$firms = Firm::all();
        // $casetype_arr = array();
        foreach ($firms as $key => $value) {

            $firms[$key]->casestatus = GetCaseStatus($value->casestatus, 'admin');
            if ($firms[$key]->CourtDates == 0) {
                $firms[$key]->CourtDates = 'Not set';
            }
            if ($firms[$key]->is_vp_services == 1) {
                $firms[$key]->is_vp_services = 'VP Service';
            }
            if ($firms[$key]->is_vp_services == 0) {
                $firms[$key]->is_vp_services = 'Self';
            }

            $date1 = $value->crd;
            $date2 = $value->upd;

            $diff = abs(strtotime($date2) - strtotime($date1));

            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            $firms[$key]->complete_time = "$days days, $months months, $years years";
        }
        #pre($firms);die;
        //$this->authorize(User::class, 'index');
        return datatables()->of($firms)->toJson();
    }

    public function VaCase_getDate(Request $request) {
        // old one
        // $q = "select fi.name as fname,va.name as vname,c.case_type,c.status as case_status,c.*,at.* from `case` as  c,admintask as at,users as fi,users as va where at.task='Assign Case to VP' and allot_user_id>0 and at.firm_admin_id=fi.id and at.allot_user_id=va.id and (c.case_type=0)";

        $q = "select fi.name as fname,va.name as vname,c.case_type,c.id as cid,c.created_at as crd,c.updated_at as upd,c.status as case_status,c.*,at.* from `case` as  c,admintask as at,users as fi,users as va where at.task='Assign Case to VP' and allot_user_id>0 and at.firm_admin_id=fi.id and at.allot_user_id=va.id and c.status=9 and at.task_type = 'Assign_Case' ";

        if(!empty($request->firm) && empty($request->Case_Type)) {
            $q .= " and at.allot_user_id = '$request->firm'";
        }
        else if(empty($request->firm) && !empty($request->Case_Type)) {
            $q .= " and c.case_type = '$request->Case_Type'";
        }
        else if(!empty($request->firm) && !empty($request->Case_Type)) {
            $q .= " and at.allot_user_id = '$request->firm'";
            $q .= " and c.case_type = '$request->Case_Type'";
        }

        // $q .= " Group By c.id";
        

        $firms = DB::select(DB::raw($q));
        foreach ($firms as $key => $value) {
            if ($firms[$key]->CourtDates == 0) {
                $firms[$key]->CourtDates = 'Not set';
            }
            $firms[$key]->casestatus = GetCaseStatus($value->case_status, 'admin');
        }

        return datatables()->of($firms)->toJson();
    }

}
