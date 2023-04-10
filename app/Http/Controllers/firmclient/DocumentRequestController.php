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
use App\User;
use Session;

class DocumentRequestController extends Controller
{
    public function document_request() {
        return view('firmadmin.firmclient.document_request.index');
    }
    public function getDataDocument()
    { 
        $data = Auth::User();
        $clients = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        ->where('users.id' ,'=', $data->id)
        ->get();
        $id = $clients[0]->id;
        $users = DocumentRequest::select('document_request.*','new_client.*', 'document_request.status as dstatus', 'document_request.id as did')
        ->join('new_client', 'new_client.id', 'document_request.client_id')
        ->where('document_request.client_id', $id)
        ->get();   
        foreach ($users as $key => $user) {
            $users[$key]->name = $user->first_name.' '.$user->last_name;
            if($users[$key]->dstatus == 2) {
                $users[$key]->dstatus = 'Upload Complete';
            }
            else if($users[$key]->dstatus == 1) {
                $users[$key]->dstatus = 'Upload In Review';
            }
            else {
                $users[$key]->dstatus = 'Upload Needed';
            }
            $users[$key]->document_type = ucwords(str_replace('_', ' ', $users[$key]->document_type));
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
        return redirect('firm/document_request')->withInfo('Document upload successfully!');
    }
}

