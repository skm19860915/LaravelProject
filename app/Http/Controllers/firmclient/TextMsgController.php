<?php

namespace App\Http\Controllers\firmclient;

use Illuminate\Http\Request;
use App\User;
use App\Models\TextMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\AdminTask;
use App\Models\Event;
use App\Models\FirmCase;
use App;
use App\Notifications\DatabaseNotification;
use Notification;
use DB;
use Twilio\Rest\Client; 

class TextMsgController extends Controller
{
    public function __construct()
    {
        
    }

    public function textmessage($id) {
        $data = Auth::User();
        $u=User::select()->where('role_id',4)->where('firm_id',$data->firm_id)->first(); 
        $ids=$u;
        $currunt_user = Auth::User();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();
        $case = FirmCase::select('case.*','case.id as case_id', 'case.created_at as case_created_at', 'cp.*')
            ->join('new_client as cp', 'cp.user_id', '=', 'case.client_id')
            ->where('case.id',$id)
            ->where('case.client_id',$currunt_user->id)
            ->first();
        return view('firmadmin.firmclient.text_message.index',compact('ids', 'case', 'firm'));
    }

    public function getData()
	{ 
		$data = Auth::User();
		$msg = array();
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        // ->where('text_message.msgfrom', $data->id)
        ->where('text_message.msgto', $data->id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 

        foreach ($messages as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }

        $messages1 = TextMessage::select('text_message.*', 'u1.name as username')
        ->where('text_message.msgfrom', $data->id)
        // ->where('text_message.msgto', $data->id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 
        foreach ($messages1 as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }
		return datatables()->of($msg)->toJson();        
    }

    public function sendtextmsg(Request $request) {
    	$msg = $request->msg;
        $subject = $request->subject;
        $mtype = array();
        if(!empty($request->is_text_send) && $request->is_text_send == 'true') {
            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            try {
                $message = $twilio->messages 
                  ->create($phone_no,
                           array( 
                             "from" => env('TWILIO_FROM_NO'),       
                             "body" => $msg 
                         ) 
                       ); 
            }
            catch (\Exception $e) {
                //return redirect()->back()->withErrors($e->getMessage());
            }
            $mtype[] = 'SMS';
        }

        if(!empty($request->is_email_send) && $request->is_email_send == 'true') {
            $useremail = $request->email;
            $msg1 = EmptyEmailTemplate($msg);
            $args = array (
                'bodyMessage' => $msg1,
                'to' => $useremail,
                'subject' => $subject,
                'from_name' => 'TILA',
                'from_email' => 'info@stoute.com'
            );
            send_mail($args);
            $mtype[] = 'Email';
        }

        $data2 = [
            'msgfrom' => Auth::User()->id,
            'msgto' => $request->to,
            'msg' => $msg,
            'subject' => $subject,
            'type' => json_encode($mtype)
        ];
        $touser = User::where('id',$request->to)->first();
        $n_link = url('firm/client/text_message').'/'.Auth::User()->id;
        $message = collect(['title' => 'Send you Text message', 'body' => $request->msg,'type'=>'1','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        Notification::send($touser, new DatabaseNotification($message));
        
        
    	$note = TextMessage::create($data2);
    }

    public function mymessages() {
        $data = Auth::User();
        $ids = AdminTask::select('admintask.allot_user_id as id', 'users.name', 'users.contact_number', 'users.email')
               ->where('admintask.task_type', 'Assign_Case')
               ->join('users', 'users.id', '=', 'admintask.allot_user_id')
               ->join('case', 'case.id', '=', 'admintask.case_id')
               ->where('case.client_id', $data->id)
               ->groupBy('users.id')
               ->get();
        $firm_id = Auth::User()->firm_id;
        $firm = DB::table('firms')->where('id', $firm_id)->first();

        $msg = array();
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        // ->where('text_message.msgfrom', $data->id)
        ->where('text_message.msgto', $data->id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 

        foreach ($messages as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }

        $messages1 = TextMessage::select('text_message.*', 'u1.name as username')
        ->where('text_message.msgfrom', $data->id)
        // ->where('text_message.msgto', $data->id)
        ->leftJoin('users as u1', 'u1.id', 'text_message.msgfrom')
        ->get(); 
        foreach ($messages1 as $k => $v) {
            $type = json_decode($v->type);
            if(!empty($type)) {
                $type = implode(',', $type);
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->msg,
                        'created_by' => $v->username,
                        'type' => $type,
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                    );
        }

        return view('firmadmin.firmclient.text_message.mymessages',compact('ids', 'firm', 'msg'));
    }
}