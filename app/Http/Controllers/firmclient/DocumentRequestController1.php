<?php
namespace App\Http\Controllers\firmclient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Firm;
use App\Models\Transaction;
use App\Models\AdminTask;
use App\Models\FirmCase;
use App\Models\ClientInformation;
use App\Models\DocumentRequest;
use App\Models\Newclient;
use App\Models\ClientDocument;
use App\User;
use Session;

class DocumentRequestController1 extends Controller
{
    public function document_request($id) {
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();

        $client_doc = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                $client_doc = ClientDocument::select('*')->where('client_id', $client->id)->get();
            }
        }
        return view('firmadmin.firmclient.document_request.index', compact('case', 'firm', 'client_doc'));
    }
    public function getDataDocument1($id)
    { 
        $data = Auth::User();
        $users = DocumentRequest::select('document_request.*', 'document_request.status as dstatus', 'document_request.id as did')
        // ->join('new_client', 'new_client.user_id', 'document_request.client_id')
        ->where('document_request.client_id', $id)
        ->get();   
        foreach ($users as $key => $user) {
            $uu = getUserName($user->family_id);
            $users[$key]->name = $uu->name;
            // $users[$key]->name = $user->first_name.' '.$user->last_name;
            if($users[$key]->dstatus == 4) {
                $users[$key]->dstatus = 'Rejected';
            }
            else if($users[$key]->dstatus == 3) {
                $users[$key]->dstatus = 'Requires Translation';
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

            $users[$key]->doclink = '';
            $users[$key]->doc_name = '';
            if(!empty($user->document)) {
                $doc = json_decode($user->document);
                $users[$key]->doc_name = $doc[0];
                $users[$key]->doclink = asset('storage/app').'/'.$doc[0];
            }
        }
        return datatables()->of($users)->toJson();        
    }

    public function setDataDocument(Request $request) {
        // $storeFolder = '/var/www/tila/assets/uploads/';
        // if (!empty($_FILES)) {
        //     $tempFile = $_FILES['file']['tmp_name'];
        // //     $targetPath = $storeFolder;
        // //     $targetFile =  $targetPath. $_FILES['file']['name'];
        // //     move_uploaded_file($tempFile,$targetFile);
        // }
        // pre($request->all());
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
        return redirect('firm/clientcase/case_documents')->withInfo('Document upload successfully!');
    }

    public function setCaseDocument4(Request $request) {
        $data = Auth::User();
        $case = FirmCase::select('*')->where('id', $request->case_id)->first();
        $client_doc = array();
        if($case->client_id) {
            $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
            if(!empty($client)) {
                foreach ($request->file as $key => $file) {
                    $f = Storage::put('client_doc', $file);
                    $data = [
                    'client_id' => $client->id,
                    'uploaded_by' => $data->id,
                    'document' => $f,
                    'title' => $request->title,
                    'description' => $request->description
                    ];
                    ClientDocument::create($data);
                }
            }
        }
        

        return redirect('firm/firmclient/document_requests/'.$request->case_id)->with('success', 'Case document upload successfully!');
    }

    public function family_document_requests($id, $fid) {
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        return view('firmadmin.firmclient.document_request.family_document', compact('case', 'firm', 'fid'));
    }

    public function getFamilyDataDocument1($id, $fid)
    { 
        $data = Auth::User();
        $users = DocumentRequest::select('document_request.*', 'document_request.status as dstatus', 'document_request.id as did')
        // ->join('new_client', 'new_client.user_id', 'document_request.family_id')
        ->where('document_request.case_id', $id)
        ->where('document_request.family_id', $fid)
        ->get();   
        foreach ($users as $key => $user) {
            $uu = getUserName($user->family_id);
            $users[$key]->name = $uu->name;
            if($users[$key]->dstatus == 4) {
                $users[$key]->dstatus = 'Rejected';
            }
            else if($users[$key]->dstatus == 3) {
                $users[$key]->dstatus = 'Requires Translation';
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

    public function setFamilyDataDocument(Request $request) {
        // $storeFolder = '/var/www/tila/assets/uploads/';
        // if (!empty($_FILES)) {
        //     $tempFile = $_FILES['file']['tmp_name'];
        // //     $targetPath = $storeFolder;
        // //     $targetFile =  $targetPath. $_FILES['file']['name'];
        // //     move_uploaded_file($tempFile,$targetFile);
        // }
        // pre($request->all());
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
        return redirect('firm/firmclient/family_document_requests/'.$docs->case_id.'/'.$request->fid)->withInfo('Document upload successfully!');
    }
}

