<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use App\Models\TextMessage;
use App\Models\Newclient;
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
use Twilio\Rest\Client; 

class FirmTextMsgController extends Controller
{
    public function __construct()
    {
        
    }

    // public function text_message($id) {
    //     $client = Newclient::where('user_id', $id)->first();
    // 	return view('firmadmin.text_message.index', compact('id', 'client'));
    // }

    public function text_message($id) {

        $data = Auth::User();
        $client = Newclient::select('*')->where('user_id', $id)->first();
        $msg = array();

        
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        ->where('text_message.msgfrom', $data->id)
        ->where('text_message.msgto', $client->user_id)
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
        ->where('text_message.msgfrom', $client->user_id)
        ->where('text_message.msgto', $data->id)
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

        return view('firmadmin.text_message.index', compact('msg', 'client'));
    }

    public function getData($id)
	{ 
		$data = Auth::User();
		$msg = array();
		$messages = TextMessage::select('text_message.*')
        ->where('text_message.msgfrom', $data->id)
        ->where('text_message.msgto', $id)
		->get(); 
		foreach ($messages as $k => $v) {
			$messages[$k]->msgfrom = getUserName($v->msgfrom)->name;
			$messages[$k]->msgto = getUserName($v->msgto)->name;
			$msg[] = $messages[$k];
		}
		$messages1 = TextMessage::select('text_message.*')
        ->where('text_message.msgfrom', $id)
        ->where('text_message.msgto', $data->id)
		->get(); 
		foreach ($messages1 as $k => $v) {
			$messages1[$k]->msgfrom = getUserName($v->msgfrom)->name;
			$messages1[$k]->msgto = getUserName($v->msgto)->name;
			$msg[] = $messages1[$k];
		}
		return datatables()->of($msg)->toJson();        
    }

    public function send_text_msg(Request $request) {

        $msg = $request->msg;
        $subject = $request->subject;
        $mtype = array();
        //pre($request->all());
        if(!empty($request->is_text_send)) {
            $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
            $phone_no = $request->phone_no;
            $phone_no = preg_replace('/(.*) \((.*)\) (.*)-(.*)/', '$1$2$3$4', $phone_no);
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
                return redirect()->back()->withErrors($e->getMessage());
            }
            $mtype[] = 'SMS';
        }

        if(!empty($request->is_email_send)) {
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
    		'msg' => $request->msg,
            'subject' => $request->subject,
            'type' => json_encode($mtype)
    	];
        
        $case = FirmCase::select('*')->where('client_id', $request->to)->orderBy('id', 'DESC')->first();

        $touser = User::where('id',$request->to)->first();
        if(!empty($case)) {
            $n_link = url('firm/mymessages').'/'.$request->to;
        }
        else {
            $n_link = url('/');
        }
        $n_link = url('firm/mymessages');
        $message = collect(['title' => 'Send you Text message', 'body' => $request->msg,'type'=>'1','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
        Notification::send($touser, new DatabaseNotification($message));
    	$note = TextMessage::create($data2);
    }
}