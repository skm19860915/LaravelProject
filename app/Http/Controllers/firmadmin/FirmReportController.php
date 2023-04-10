<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\FirmCase;
use App\Models\Lead;
use App\Models\Event;
use App\Models\Firm;
use DB;
use App;

class FirmReportController extends Controller {

    public function __construct() {
        
    }

    public function ReportingJson() {
        echo 'hi';
        die;
        return datatables()->of($deadline_case)->toJson();
    }

    public function deadline_report() {
        $data = Auth::User();
        $firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
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
        $users1 = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
        return view('firmadmin.report.deadline', compact('firm', 'card', 'data', 'users1'));
    }

    public function deadline_getData() {
        $current_firm_id = Auth::User()->firm_id;
        $deadline_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name', 'event.e_date')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->join('event', function ($join) {
                    $join->on('case.id', '=', 'event.related_id')
                    ->where('event.title', 'CASE');
                })
                ->where('case.firm_id', $current_firm_id)
                ->get();
        $courtdate = array();
        foreach ($deadline_case as $key => $value) {
            
            $deadline_case[$key]->stat = GetCaseStatus($value->status);
            $deadline_case[$key]->caseType = $value->case_type == 1;
            $deadline_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $deadline_case[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $deadline_case[$key]['VP_Assistance'] = $d;
            } else {
                $deadline_case[$key]['VP_Assistance'] = 'Pandding State';
            }
            $id=$deadline_case[$key]['id'];
            $deadline_case[$key]['button']='<a  href="firm/case/show/'.$id.'" ><i class="btn btn-primary fa fa-eye"></i></a>';
            if($value->CourtDates == "0") {
                $deadline_case[$key]->CourtDates = 'Not set';
            }
        }
        foreach ($deadline_case as $key => $value) {
            $deadline_case[$key]['start'] = $f = strtotime($_REQUEST['start_date']);
            $deadline_case[$key]['end'] = $t = strtotime('+24 hours', strtotime($_REQUEST['end_date']));
            $c = strtotime($value->e_date);
            $deadline_case[$key]['start'] = date('d F,y', $f);
            $deadline_case[$key]['end'] = date('d F,y', $t);
            $deadline_case[$key]['chk'] = date('d F,y', $c);
            if ($f < $c && $c < $t) {
                $courtdate[$key] = $deadline_case[$key];
            }
        }
        return datatables()->of($courtdate)->toJson();
    }

    public function expirationDates_report() {
        return view('firmadmin.report.expirationDates');
    }

    public function expirationDates_getData() {
        $current_firm_id = Auth::User()->firm_id;
        $expiration_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->join('event', function ($join) {
                    $join->on('case.id', '=', 'event.related_id')
                    ->where('event.title', 'CASE')
                    ->whereDate('e_date', '<', Carbon::now());
                })
                ->where('case.firm_id', $current_firm_id)
                ->get();
        foreach ($expiration_case as $key => $value) {
            $expiration_case[$key]->stat = GetCaseStatus($value->status);
            $expiration_case[$key]->caseType = ($value->case_type);
            $expiration_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $expiration_case[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $expiration_case[$key]['VP_Assistance'] = $d;
            } else {
                $expiration_case[$key]['VP_Assistance'] = 'Pandding State';
            }
        }
        return datatables()->of($expiration_case)->toJson();
    }

    public function openedCases_report() {
        return view('firmadmin.report.openedCases');
    }

    public function openedCases_getData() {
        $current_firm_id = Auth::User()->firm_id;
        $open_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->where('case.firm_id', $current_firm_id)
                ->whereIn('case.status', [1, 4])
                ->get();

        foreach ($open_case as $key => $value) {
            $open_case[$key]->stat = GetCaseStatus($value->status);
            $open_case[$key]->caseType = ($value->case_type);
            $open_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $open_case[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $open_case[$key]['VP_Assistance'] = $d;
            } else {
                $open_case[$key]['VP_Assistance'] = 'Pandding State';
            }
        }
        return datatables()->of($open_case)->toJson();
    }

    public function closedCases_report() {
        return view('firmadmin.report.closedCases');
    }

    public function closedCases_getData() {
        $current_firm_id = Auth::User()->firm_id;
        $closed_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->where('case.firm_id', $current_firm_id)
                ->whereIn('case.status', [9])
                ->get();

        foreach ($closed_case as $key => $value) {
            $closed_case[$key]->stat = GetCaseStatus($value->status);
            $closed_case[$key]->caseType = $value->case_type;
            $closed_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $closed_case[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $closed_case[$key]['VP_Assistance'] = $d;
            } else {
                $closed_case[$key]['VP_Assistance'] = 'Pandding State';
            }
        }
        return datatables()->of($closed_case)->toJson();
    }

    public function courtDates_report() {
        return view('firmadmin.report.courtDates');
    }

    public function courtDates_getData() {


        $current_firm_id = Auth::User()->firm_id;
        $courtDates_getData = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->where('case.firm_id', $current_firm_id)
                //->whereDate('CourtDates', '>', Carbon::now())
                ->get();
        $courtdate = array();
        foreach ($courtDates_getData as $key => $value) {
            $courtDates_getData[$key]->stat = GetCaseStatus($value->status);
            $courtDates_getData[$key]->caseType = $value->case_type;
            $courtDates_getData[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $courtDates_getData[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $courtDates_getData[$key]['VP_Assistance'] = $d;
            } else {
                $courtDates_getData[$key]['VP_Assistance'] = 'Pandding State';
            }
            $courtDates_getData[$key]['CoartDate'] = ($value->CourtDates);

            $f = strtotime($_REQUEST['start_date']);
            $t = strtotime('+24 hours', strtotime($_REQUEST['end_date']));
            $c = strtotime($value->CourtDates);

            if ($f < $c && $c < $t) {
                $courtdate[$key] = $courtDates_getData[$key];
            }
        }

        return datatables()->of($courtdate)->toJson();
    }

    public function nationality_report(Request $request) {
        $q = "SELECT cc.* FROM countries as cc,new_client as cl,`case` as ca where ca.client_id=cl.user_id and  CAST(cl.birth_address->'$.country' AS UNSIGNED)=cc.id group by cc.id ";
        $country = DB::select(DB::raw($q));

        //$country = DB::table('countries')->get();
        return view('firmadmin.report.nationality', compact('country'));
    }

    public function nationality_getData() {

        $current_firm_id = Auth::User()->firm_id;
        $All_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name', 'new_client.birth_address')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->where('case.firm_id', $current_firm_id)
                ->get();

        $display_record = [];
        foreach ($All_case as $key => $value) {

            $location_data = json_decode($value->birth_address);
            #pre($location_data);
            
            if(!empty($location_data) && $location_data->country > 0)
            {}else{
                //$location_data->country=0;
            }
            
            if (!empty($location_data) && (($location_data->country == $_POST['country_id']) || ($_POST['country_id']==-1))) {
                $display_record[$key]['country']=getCountryName($location_data->country);
                $display_record[$key]['stat'] = GetCaseStatus($value->status);
                $display_record[$key]['caseType'] = $value->case_type;
                $display_record[$key]['id'] = $value->id;
                $display_record[$key]['client_id'] = $value->client_id;
                $display_record[$key]['clientname'] = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
                if ($value->VP_Assistance == 0) {
                    $display_record[$key]['VP_Assistance'] = 'Self Managed';
                } else if ($value->VP_Assistance == 1) {

                    $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                    $Cases = DB::select(DB::raw($q));
                    $d = 'Va User Assign';
                    if (count($Cases) > 0) {
                        $d .= '(' . $Cases[0]->name . ')';
                    }
                    $display_record[$key]['VP_Assistance'] = $d;
                } else {
                    $display_record[$key]['VP_Assistance'] = 'Pandding State';
                }
            }
        }

        return datatables()->of($display_record)->toJson();
    }

    public function submittedCases_report() {
        return view('firmadmin.report.submittedCases');
    }

    public function submittedCases_getData() {
        $current_firm_id = Auth::User()->firm_id;
        

        $current_time = strtotime('now');
        // $q1 = "select c.status as cStatus,c.case_type as CaseT,c.id as CaseID,JSON_LENGTH(t.Required_Forms) as formcase,(select count(d.case_id) from document_request as d where cl.id=d.client_id and d.case_id=c.id and (d.status=1 or status=2) group by d.case_id) as submitcase,cl.id as clientID,cl.first_name as first_name,cl.middle_name as middle_name,cl.last_name as last_name,t.Required_Forms,c.*,t.* from `case` as c,case_types as t,new_client as cl where cl.user_id=c.client_id and c.case_category=t.Case_Category and c.case_type=t.Case_Type and c.status=9 and c.CourtDates_Time < ".$current_time." and c.firm_id='".$current_firm_id."' having  formcase=submitcase";

        $q1 = "select c.status as cStatus,c.case_type as CaseT,c.id as CaseID,JSON_LENGTH(t.Required_Forms) as formcase,(select count(d.case_id) from document_request as d where cl.id=d.client_id and d.case_id=c.id and (d.status=1 or status=2) group by d.case_id) as submitcase,cl.id as clientID,cl.first_name as first_name,cl.middle_name as middle_name,cl.last_name as last_name,t.Required_Forms,c.*,t.* from `case` as c,case_types as t,new_client as cl where cl.user_id=c.client_id and c.case_category=t.Case_Category and c.case_type=t.Case_Type and c.status=9 and c.CourtDates_Time < ".$current_time." and c.firm_id='".$current_firm_id."'";
        $incomplete_case = DB::select(DB::raw($q1));
        #pre($incomplete_case);
       # die;;
        foreach ($incomplete_case as $key => $value) {
            
            $incomplete_case[$key]->stat = GetCaseStatus($value->cStatus);
            $incomplete_case[$key]->caseType = ($value->CaseT);
            $incomplete_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $incomplete_case[$key]->VP_Assistance = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {
                $incomplete_case[$key]->VP_Assistance = 'Va User Assign';
                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->CaseID . "' and u.id=t.allot_user_id  group by u.name";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $incomplete_case[$key]->VP_Assistance = $d;
            } else {
                $incomplete_case[$key]->VP_Assistance = 'Pandding State';
            }
            if($value->CourtDates == "0") {
                $incomplete_case[$key]->CourtDates = 'Not set';
            }
        }
        return datatables()->of($incomplete_case)->toJson();
    }

    public function nextStageCase_report() {
        return view('firmadmin.report.nextStageCase');
    }

    public function nextStageCase_getData() {
        /* $data = Auth::User();
          $message = FirmSetting::where('category',"SMS")->where('firm_id',$data->firm_id)->get();
          foreach ($message as $key => $value) {
          $message[$key]->stat = ($value->status == 1) ? "Active" : "Inactive";
          }
          return datatables()->of($message)->toJson(); */
    }

    public function incompleteCases_report() {
        return view('firmadmin.report.incompleteCases');
    }

    public function incompleteCases_getData() {
        $current_firm_id = Auth::User()->firm_id;
        $incomplete_case = FirmCase::select('case.*', 'new_client.first_name', 'new_client.middle_name', 'new_client.last_name')
                ->join('new_client', 'case.client_id', 'new_client.user_id')
                ->where('case.firm_id', $current_firm_id)
                ->where('case.status', 8)
                ->get();

        foreach ($incomplete_case as $key => $value) {
            $incomplete_case[$key]->client_id = timetodate($value->CourtDates);
            $incomplete_case[$key]->stat = GetCaseStatus($value->status);
            $incomplete_case[$key]->caseType = ($value->case_type == 1) ? "Monthly" : "Self Managed";
            $incomplete_case[$key]->clientname = $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name;
            if ($value->VP_Assistance == 0) {
                $incomplete_case[$key]['VP_Assistance'] = 'Self Managed';
            } else if ($value->VP_Assistance == 1) {

                $q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
                $Cases = DB::select(DB::raw($q));
                $d = 'Va User Assign';
                if (count($Cases) > 0) {
                    $d .= '(' . $Cases[0]->name . ')';
                }
                $incomplete_case[$key]['VP_Assistance'] = $d;
            } else {
                $incomplete_case[$key]['VP_Assistance'] = 'Pandding State';
            }
            if($value->CourtDates == "0") {
                $incomplete_case[$key]->CourtDates = 'Not set';
            }
        }
        return datatables()->of($incomplete_case)->toJson();
    }

    public function leads_report() {
        return view('firmadmin.report.leadsreport');
    }

    public function leads_getData() {
        $from = $_POST['start_date'];
        $to = $_POST['end_date'];
        $from = explode('/', $from);
        $to = explode('/', $to);
        $f = $from[2] . '-' . $from[0] . '-' . $from[1];
        $t = $to[2] . '-' . $to[0] . '-' . $to[1];
        $a = ['l.name', 'l.last_name', 'e.id', 'e.title', 'e.event_title', 'e.related_id', 'e.s_date', 'e.e_date', 'e.s_time', 'e.e_time', 'e.who_consult_with', 'e.attorney', 'e.created_at', 'e.updated_at', 'l.status', 'e.coutner'];
        $c = implode(',', $a);
        $current_firm_id = Auth::User()->firm_id;
        $q = "SELECT $c FROM lead as l,event as e where e.title='LEAD' and e.related_id=l.id and l.firm_id='" . $current_firm_id . "' and e.s_date BETWEEN '" . $f . "' AND '" . $t . "'";
        $results = DB::select(DB::raw($q));
        foreach ($results as $key => $value) {
            $results[$key]->leadname = $value->name . ' ' . $value->last_name;
            $results[$key]->leadid = $value->id;
            $results[$key]->eventDate = $value->s_date . ' ' . $value->e_time;

            //0 = lost lead, 1 = processing, 2 = convert to client
            switch ($value->status) {
                case 0:
                    $results[$key]->Status = 'lost lead';
                    break;
                case 1:
                    $results[$key]->Status = 'processing';
                    break;
                case 2:
                    $results[$key]->Status = 'convert to client';
                    break;
            }
        }
        return datatables()->of($results)->toJson();
    }

}
