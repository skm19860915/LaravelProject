<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\FirmCase;
use App\Models\AdminTask;
use App\Models\Newclient;
use App\Models\DocumentRequest;
use App\Models\ClientInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Notifications\DatabaseNotification;
use Notification;
use App\Models\TilaEmailTemplate;
use App\Models\Firm;
use App;

class DocumentRequestController extends Controller
{
    public function __construct()
    {

    }

    public function document_request($id)
    {
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        $data = Auth::User();
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
    	$clients = User::select('users.*', 'new_client.*')
        ->join('new_client', 'users.id', '=', 'new_client.user_id')
        // ->where('users.firm_id' ,'=', $data->firm_id)
        ->get();
    	return view('firmadmin.documentrequest.index',compact('id', 'clients', 'card'));
    }
    public function upload_document($id)
    {
        $docs = DocumentRequest::select('document_type')->where('case_id', $id)->get();
        $case = FirmCase::select('*')->where('id', $id)->first();
        $client = Newclient::select('*')->where('user_id', $case->client_id)->first();
        return view('firmadmin.documentrequest.upload_document',compact('id', 'docs', 'client'));
    }
    public function getDataDocument($id)
    { 
        $client = Newclient::select('*')->where('id', $id)->first();

        $users = DocumentRequest::select('document_request.*','new_client.*', 'document_request.status as dstatus', 'document_request.id as did')
        ->join('new_client', 'new_client.user_id', 'document_request.family_id')
        ->where('document_request.family_id', $client->user_id)
        ->get();   
        foreach ($users as $key => $user) {
        	$users[$key]->name = $user->first_name.' '.$user->last_name;
            $users[$key]->dstatus1 = $users[$key]->dstatus;
        	if($users[$key]->dstatus == 4) {
                $users[$key]->dstatus = 'Rejected';
            }
            else if($users[$key]->dstatus == 3) {
                $users[$key]->dstatus = 'Requires Translation';
                if($users[$key]->quote == 1) {
                    $users[$key]->dstatus = 'Quote Requested';
                }
                if($users[$key]->quote == 2) {
                    $users[$key]->dstatus = 'Quote Provided';
                }
                if($users[$key]->quote == 3) {
                    $users[$key]->dstatus = 'Paid for translation';
                }
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
    public function client_Cases($id)
    {
    	$states = FirmCase::select("*")
                    ->where("client_id",$id)
                    ->pluck("id","id");
        return response()->json($states);
    }

    public function setDataDocument1(Request $request) {
        $data1 = Auth::User();
        // foreach ($request->file_type as $kk => $vv) {
            if(!empty($request->family_id)) {
                foreach ($request->family_id as $ky => $va) {
                    if($va == $request->client_id) {
                        $data =  [
                            'client_id' => $request->client_id,
                            'family_id' => $va,
                            'case_id' => $request->case_id,
                            'requested_by' => $data1->id,
                            'document_type' => $request->file_type,
                            'expiration_date' => $request->expiration_date,
                            'status' => 0
                        ];
                        DocumentRequest::create($data);
                    }
                    else {
                        $data =  [
                            'client_id' => $request->client_id,
                            'family_id' => $va,
                            'case_id' => $request->case_id,
                            'requested_by' => $data1->id,
                            'document_type' => $request->file_type,
                            'expiration_date' => $request->expiration_date,
                            'status' => 0
                        ];
                        DocumentRequest::create($data);
                    }
                }
            }
        // }

        AdminTask::where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->update(['status' => 1]);

        $record = AdminTask::select('allot_user_id')->where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->first();

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document upload';
        
        $touser = User::where('id', $record->allot_user_id)->first();
        $n_link = url('admin/usertask/documents').'/'.$record->id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        if(!empty($touser)) {
            Notification::send($touser, new DatabaseNotification($message));
        }
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        if(!empty($usercase)) {
            Notification::send($usercase, new DatabaseNotification($message));
        }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 
        $cl = DocumentRequest::select('client_id')->where('case_id', $request->case_id)->first();
        return redirect('firm/client/document/'.$cl->client_id)->withInfo('Document upload successfully!');
    }

    public function setClientDataDocument1(Request $request) {
        if(!empty($request->file))
        {
            foreach ($request->file as $k1 => $file) {
                $client_file = array();
                $client_file[] = Storage::put('client_doc', $file);
                foreach ($request->filetype as $k2 => $fname) {
                    if($client_file){
                        DocumentRequest::where('case_id', $request->case_id)->where('family_id', $request->family_id)->where('document_type', $fname)->update(['document' => json_encode($client_file), 'status' => 1]);
                        
                    }
                }
            }
        }
        // AdminTask::where('case_id', $request->case_id)->where('task_type', 'Upload_Required_Document')->update(['status' => 1]);

        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();

        /*--------------------Notifications---------------*/ 
        
        $record = AdminTask::where('case_id', $request->case_id)->where('task_type', 'Assign_Case')->first();

        $msg = 'Document uploaded successfully!';
        if(!empty($record) && !empty($record->allot_user_id)) {
            $touser = User::where('id', $record->allot_user_id)->first();
            $n_link = url('admin/usertask/documents').'/'.$record->id;
            $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
            Notification::send($touser, new DatabaseNotification($message));
        }
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        
        // Notification::send($usercase, new DatabaseNotification($message));
        
        Notification::send($touser, new DatabaseNotification($message));
        /*--------------------Notifications---------------*/ 
        // $cl = DocumentRequest::select('client_id')->where('case_id', $request->case_id)->first();
        return redirect('firm/client/document/'.$request->client_id)->withInfo('Document uploaded successfully!');
    }
    public function completeDocument(Request $request, $id) {
        $status = $request->status;
        DocumentRequest::where('id', $id)->update(['status' => $status]);
        $res['status'] = true;
        $res['msg'] = 'Document complete successfully!';
        echo json_encode($res);
    }
    public function Request_Quote(Request $request, $id) {
        //echo $id;
        $doc = DocumentRequest::where('id', $id)->first();
        $client = Newclient::select('*')->where('user_id', $doc->family_id)->first();

        
        
        DocumentRequest::where('id', $id)->update(['quote' => 1]);
        $data1['case_id'] = $id;  
        $data1['firm_admin_id'] = Auth::User()->id;
        $data1['allot_user_id'] = $doc->requested_by;
        $data1['task_type'] = 'provide_a_quote';
        $data1['task'] = 'Provide a quote';
        $data1['status'] = 0;
        $atask = AdminTask::create($data1);

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('*')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Document quote requested';
        
        $touser = User::where('id', 1)->first();
        $n_link = url('admin/task/edit').'/'.$atask->id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$request->case_id;
        $message = collect(['title' => 'Firm Admin Document quote requested', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $request->case_id)
                        ->first();
        
        if(!empty($usercase)) {
            $usercase = User::where('id', $FirmCase->id)->first();
            if(!empty($FirmCase)) {
                Notification::send($usercase, new DatabaseNotification($message));
            }
        }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        /* ----- Email notificcation for assigned user  ---- */

        $remove = array(
            'Assigned_Firm_User' => Auth::User()->name,
            'ClientName'=>$client->first_name.' '.$client->middle_name.' '.$client->last_name,
            'DocumentType'=>$doc->document_type,
            'TID' => $doc->id,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(24, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Auth::User()->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);

        /* ----- Email notificcation for assigned user  ---- */

        /* ----- Email notificcation for TILA Admin  ---- */
        $remove = array(
            'FirmNmae' => $firm_name->firm_name,
            'FirmEmail'=> $firm_name->email,
            'ClientFile' => asset('storage/app/'.json_decode($doc->document)[0])
        );
        $email = EmailTemplate(19, $remove);
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => Eadmin(),
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        /* ----- Email notificcation for TILA Admin  ---- */

        return redirect('firm/client/document/'.$client->id)->withInfo('Document quote requested successfully!');
    }
    public function pay_for_translation(Request $request, $id) {
        $card_source = '';
        require_once(base_path('vendor/stripe/stripe-php/init.php'));
        $currunt_user = Auth::User();
        $did = $request->paydocid;
        $doc = DocumentRequest::where('id', $did)->first();
        $cl = Newclient::where('id', $doc->client_id)->first();
        
        $stripeToken = $request->stripeToken;
        $casecost1 = intval(str_replace('$','',$doc->quote_cost))*100;
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
        // $charge = \Stripe\Charge::create(['amount' => $casecost1, 'currency' => 'usd', 'source' => $stripeToken]);
        $searchResults = \Stripe\Customer::all([
            "email" => $currunt_user->email,
            "limit" => 1,
            "starting_after" => null
        ]);
        $cus_id = '';
        if($searchResults->data) {
            $cus_id =  $searchResults->data[0]->id;
            if(!empty($stripeToken)) {
                $source = \Stripe\Customer::createSource(
                  $cus_id,
                  [
                      'source' => $stripeToken,
                  ]
                );
                $card_source = $source->id;
            }
        }
        else {
            $cus = \Stripe\Customer::create([
              'description' => $currunt_user->name,
              'email' => $currunt_user->email,
              'name' => $currunt_user->name
            ]);
            $cus_id =  $cus->id;
            $source = \Stripe\Customer::createSource(
              $cus_id,
              [
                  'source' => $stripeToken,
              ]
            );
            $card_source = $source->id;
        }
        if(!empty($card_source)) {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd',
              'source' => $card_source
            ]);
        }
        else {
            $charge = \Stripe\Charge::create([
              'customer' => $cus_id,
              'amount' => $casecost1,
              'currency' => 'usd'
            ]);
        }
        $data['tx_id'] = $charge->id;
        $data['amount'] = $charge->amount;
        $data['user_id'] = Auth::User()->id;
        $data['responce'] = json_encode($charge);
        $data['paymenttype'] = 3;
        Transaction::create($data);

        DocumentRequest::where('id', $did)->update(['quote' => 3]);

        $data1['case_id'] = $did;  
        $data1['firm_admin_id'] = Auth::User()->id;
        $data1['allot_user_id'] = $doc->requested_by;
        $data1['task_type'] = 'upload_translated_document';
        $data1['task'] = 'Upload translated document';
        $data1['status'] = 0;
        $atask = AdminTask::create($data1);

        /*--------------------Notifications---------------*/ 
        
        $firm_id = Auth::User()->firm_id;
        $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg='Firm ' . $firm_name->firm_name . ' Firm admin Paid for translation';
        
        $touser = User::where('id', 1)->first();
        $n_link = url('admin/task/edit').'/'.$atask->id;
        $message = collect(['title' => 'Firm Admin Paid for translation', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        
        $touser = User::where('id',Auth::User()->id)->first();
        $n_link = url('firm/case/case_documents').'/'.$doc->case_id;
        $message = collect(['title' => 'Firm Admin Paid for translation', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        $FirmCase = FirmCase::select('users.*')
                        ->join('users', 'users.id', '=', 'case.user_id')
                        ->where('case.id', $doc->case_id)
                        ->first();
        $usercase = User::where('id', $FirmCase->id)->first();
        if($usercase->id != $task->firm_admin_id) {
            Notification::send($usercase, new DatabaseNotification($message));
        }
        Notification::send($touser, new DatabaseNotification($message)); 
        /*--------------------Notifications---------------*/ 

        /* -------------- email firm user quote notification ------- */
        $remove = array(
            'ClientName' => $cl->first_name.' '.$cl->middle_name.' '.$cl->last_name,
            'Cost' => $doc->quote_cost,
        );
        $email = EmailTemplate(25, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => 'tester.snv@gmail.com',
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        /* -------------- email firm user quote notification ------- */

        // FirmCase::where('id', $doc->case_id)->update(['status' => 6]);
        return redirect('firm/client/document/'.$doc->client_id)->withInfo('Paid for translation successfully!');
    }
}