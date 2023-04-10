<?php

namespace App\Http\Controllers\firmclientfamily;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\FirmCase;
use App\Models\ClientInformation;
use App\Models\QBInvoice;
use App\Models\ClientTask;
use App\Models\DocumentRequest;
use App\Models\ClientNotes;
use App\Models\Newclient;
use App\Models\ClientDocument;
use Illuminate\Support\Facades\Storage;
use App\User;
use Session;

class FirmclientfamilyDashboardController extends Controller {

    public function index(Request $request) {
        $count['clients'] = 0;

        $count['case_complete'] = 0;
        $count['admintask'] = '';
        $currunt_user = Auth::User();

        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));

               
        $cases=get_user_meta($currunt_user->id, 'CaseID',1);
        
        $CID=array();
        if(!empty($cases)) {
            foreach($cases as $v)
            {
                $CID[]=$v->meta_value;
            }
        }

        $cases = FirmCase::select('case.*', 'case.id as case_id', 'case.status as case_status', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                // ->where('case.client_id', $ID)
                ->whereIn('case.id', $CID)
                ->orderBy('case_id', 'DESC')
                ->get();
        foreach ($cases as $key => $value) {
            if ($value->CourtDates == "0") {
                $cases[$key]->CourtDates = 'Not set';
            }
            $cases[$key]->case_status = GetCaseStatus($value->case_status);
            if (empty($value->VP_Assistance)) {
                $cases[$key]->case_cost = 'Self Managed';
            }
        }
        $count['total_case'] = count($cases);
        $count['cases'] = count($cases);
        return view('firmclientfamily.dashboard.index', compact('cases', 'count'));
    }

    /* --------------------Cases Dashboard------------------------- */

    public function Cases(Request $request) {
        $currunt_user = Auth::User();
        
        $cases=get_user_meta($currunt_user->id, 'CaseID',1);
        
        $CID=array();
        if(!empty($cases)) {
            foreach($cases as $v)
            {
                $CID[]=$v->meta_value;
            }
        }
        #pre($CID);
        
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $cases = FirmCase::select('case.*', 'case.id as case_id', 'case.status as case_status', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                // ->where('case.client_id', $ID)
                ->whereIn('case.id', $CID)
                ->orderBy('case_id', 'DESC')
                ->get();
        foreach ($cases as $key => $value) {
            if ($value->CourtDates == "0") {
                $cases[$key]->CourtDates = 'Not set';
            }
            $cases[$key]->case_status = GetCaseStatus($value->case_status);
            if (empty($value->VP_Assistance)) {
                $cases[$key]->case_cost = 'Self Managed';
            }
        }
        #pre($cases);
        #die;
        return view('firmclientfamily.cases.list', compact('cases'));
    }

    public function show($id) {
        $currunt_user = Auth::User();
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $ID)
                ->first();
        $task = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'FAMILY')->get();
        $firm = Firm::select('*')->where('id', $currunt_user->firm_id)->first();
        $data['totla_tasks'] = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'FAMILY')->count();
        ;
        $data['totla_documents'] = DocumentRequest::select('*')->where('case_id', $case->case_id)->where('family_id', $currunt_user->id)->count();
        $data['totla_notes'] = ClientNotes::select('*')->where('related_id', $case->case_id)->where('task_for', 'FAMILY')->count();

        return view('firmclientfamily.cases.show', compact('case', 'firm', 'task', 'data'));
    }

    public function familytask($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $ID)
                ->first();
        $task = ClientTask::select('*')->where('related_id', $case->case_id)->where('task_for', 'FAMILY')->get();
        return view('firmclientfamily.cases.task', compact('case', 'task', 'firm'));
    }

    public function insert_family_task(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
            ]);
        if ($validator->fails()) {
            return redirect('firm/clientfamilydashboard/addfamilytask/'.$request->case_id)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'task_for' => 'FAMILY',
            'related_id' => $request->case_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            // 's_date' => date('Y-m-d', strtotime($dates[0])),
            // 's_time' => date('h:i A', strtotime($dates[0])),
            'e_date' => date('Y-m-d', strtotime($request->date)),
            'e_time' => date('h:i A', strtotime($request->date)),
            'status' => 0
        ];
        ClientTask::create($data);
        return redirect('firm/clientfamilydashboard/familytask/'.$request->case_id)->withInfo('Task created successfully');
    }
    public function addfamilytask($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $currunt_user = Auth::User();
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $ID)
                ->first();
        return view('firmclientfamily.cases.addfamilytask', compact('case', 'firm'));
    }

    public function familydocuments($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $currunt_user = Auth::User();
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $ID)
                ->first();
        $task = ClientTask::select('*')->where('related_id', $currunt_user->id)->where('task_for', 'FAMILY')->get();
        $client_doc = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                $client_doc = ClientDocument::select('*')->where('client_id', $client->id)->get();
            }
        }
        return view('firmclientfamily.cases.familydocuments', compact('case', 'task', 'firm', 'client_doc'));
    }

    public function familynotes($id) {
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $currunt_user = Auth::User();
        $currunt_user = Auth::User();
        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $ID)
                ->first();
        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $case->case_id)
                ->where('client_notes.task_for', 'FAMILY')
                ->get();
        return view('firmclientfamily.cases.familynotes', compact('case', 'notes_list', 'firm'));
    }

    public function add_family_notes(Request $request) {
        $res = array();
        $validator = Validator::make($request->all(), [
                    'note' => 'required|string',
        ]);
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = 'Mendatory fields are required!';
            echo json_encode($res);
            die();
        }
        $data = [
            'task_for' => 'FAMILY',
            'related_id' => $request->case_id,
            'notes' => $request->note,
            'created_by' => Auth::User()->id
        ];
        
        $note = ClientNotes::create($data);
        $res['status'] = true;
        $res['msg'] = 'Client note created successfully!';
        echo json_encode($res);
    }

    public function casenotes($id) {
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*', 'case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
                ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
                ->where('case.id', $id)
                // ->where('case.client_id', $currunt_user->id)
                ->first();
        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $case->case_id)
                ->where('client_notes.task_for', 'CASE')
                ->get();
        return view('firmadmin.firmclient.dashboard.casenotes', compact('case', 'firm', 'notes_list'));
    }

    public function caseuser() {
        $currunt_user = Auth::User();
        $cases = FirmCase::select('case.*', 'case.id as case_id', 'users.*')
                ->join('users', 'users.id', '=', 'case.user_id')
                ->where('case.client_id', $currunt_user->id)
                ->get();
        // $tasks = FirmCase::select('admintask.*','admintask.case_id as case_id', 'users.*')
        //     ->join('users', 'users.id', '=', 'case.user_id')
        //     ->where('case.client_id',$currunt_user->id)
        //     ->get(); 
        // pre($cases);
        // die();
        return view('firmadmin.firmclient.dashboard.caseuser', compact('cases'));
    }

    /* ------------------Case Dashboard---------------------- */

    public function Invoice(Request $request) {
        $currunt_user = Auth::User();

        $ID = getUserIDByClientID(get_user_meta($currunt_user->id, 'ClientID'));
        $invoice = QBInvoice::select('qb_invoice.*', 'users.name as name')
                ->join('users', 'qb_invoice.user_id', '=', 'users.id')
                ->where('qb_invoice.user_id', $ID)
                ->get();

        foreach ($invoice as $key => $v) {
            $invoice[$key]->st = 'Un-Paid';
            if ($v->status == 1) {
                $invoice[$key]->st = 'Paid';
            } else if ($v->status == 3) {
                $invoice[$key]->st = 'Cancel';
            }
            if ($v->payment_method == 'Card') {
                $invoice[$key]->payment_method = 'Card via LawPay';
            }
        }

        return view('firmclientfamily.Invoice.list', compact('count'));
    }

    public function getCaseFamilyDataDocument($id) {
        $currunt_user = Auth::User();
        $users = DocumentRequest::select('*','status as dstatus1')
        ->where('case_id', $id)
        ->where('family_id', $currunt_user->id)
        ->get();   
        foreach ($users as $key => $user) {
            
            if($user->family_id) {
                $uu = getUserName($user->family_id);
                $users[$key]->name = $uu->name.' - Family';
            }
            else {
                $client = Newclient::select('*')->where('id', $user->client_id)->first();
                $users[$key]->name = $client->first_name.' '.$client->last_name. ' - Client';
            }
            if($users[$key]->status == 4) {
                $users[$key]->status = 'Rejected';
            }
            else if($users[$key]->status == 3) {
                $users[$key]->status = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->status = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->status = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->status = 'Paid for translation';
                }
            }
            else if($users[$key]->status == 2) {
                $users[$key]->status = 'Accepted';
            }
            else if($users[$key]->status == 1) {
                $users[$key]->status = 'Submitted';
            }
            else {
                $users[$key]->status = 'Requested';
            }
            $users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
        }
        return datatables()->of($users)->toJson();  
    }

    public function setCaseFamilyDocument(Request $request) {
        $data = Auth::User();
        // pre($request->all());
        // die();
        $docs = DocumentRequest::select('*')->where('id', $request->id)->first();
        // pre($docs->case_id);
        // die();
        $client_file = array();
        if(!empty($request->file))
        {
            foreach ($request->file as $key => $file) {
                $client_file[] = Storage::put('client_doc', $file);
            }
            if($client_file){
                DocumentRequest::where('id', $request->id)->update(['document' => json_encode($client_file), 'status' => 1]);
            }
        }
        // return redirect('firm/firmclient/document_requests/'.$docs->case_id)->withInfo('Document upload successfully!');

        // $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        // $client_doc = array();
        // if($case->client_id) {
        //     $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        //     if(!empty($client)) {
        //         foreach ($request->file as $key => $file) {
        //             $f = Storage::put('client_doc', $file);
        //             $data = [
        //             'client_id' => $client->id,
        //             'uploaded_by' => $data->id,
        //             'document' => $f,
        //             'title' => $request->title,
        //             'description' => $request->description
        //             ];
        //             ClientDocument::create($data);
        //         }
        //     }
        // }
        
        return redirect('firm/clientfamilydashboard/familydocuments/'.$docs->case_id)->with('success', 'Document upload successfully!');
    }

}
