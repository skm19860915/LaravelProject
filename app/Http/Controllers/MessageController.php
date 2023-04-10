<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Firm;
use App;
use DB;
use App\Notifications\DatabaseNotification;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Notification;

class MessageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $users = User::where('id', '<>', $request->user()->id)->get();

        //$twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        // $usert = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))->users->create("rktestuser@gmail.com");

        return view('messages.index', compact('users'));
    }

    public function NotificationCenter() {
        
        $ID = Auth::user()->id;
        $type = Auth::user()->role_id;
        
        // $Notif = Notifications::where('notifiable_id', $ID)->where('isread', 0)->orderBy('created_at', 'DESC')->get();
        $q="select * from notifications where notifiable_id='".$ID."' and isread=0 order by created_at DESC";
        $Notif = DB::select($q);
        $data = array();
        $data['message']['task'] = $data['message']['chat'] = $data['message']['textmsg'] = array();
        switch ($type) {
            case '1':
                $data['type'] = array('task' => 1, 'textmsg' => 0, 'chat' => 1);
                break;
            case '2':
                $data['type'] = array('task' => 1, 'textmsg' => 0, 'chat' => 1);
                break;
            case '3':
                $data['type'] = array('task' => 1, 'textmsg' => 1, 'chat' => 0);
                break;
            case '4':
                $firm = Firm::select('*')->where('id', Auth::user()->firm_id)->first();
                if($firm->account_type == 'CMS') {
                    $data['type'] = array('task' => 1, 'textmsg' => 1, 'chat' => 1);
                }
                else {
                    $data['type'] = array('task' => 1, 'textmsg' => 1, 'chat' => 1);
                }
                break;
            case '5':
                $data['type'] = array('task' => 1, 'textmsg' => 1, 'chat' => 1);
                break;
            case '6':
                $data['type'] = array('task' => 1, 'textmsg' => 1, 'chat' => 0);
                break;
            case '7':
                $data['type'] = array('task' => 0, 'textmsg' => 0, 'chat' => 0);
                break;
        }

        
        foreach ($Notif as $v) {
            $msg = json_decode($v->data);
            $msg->message->id = $v->id;
            $msg->message->newlink = url('messages/readnotification').'/'.$v->id;
            $msg->message->read = $v->isread;
            if (isset($msg->message->fromName)) {
                $msg->message->title = $msg->message->fromName . ' ';
            } else {
                $msg->message->title = '' . ' ';
            }
            if (!isset($msg->message->type)) {
                $msg->message->type = 4;
            }
            if ($msg->message->type == 1 || $msg->message->type == 2) {
                $data['message']['textmsg'][] = $msg->message;
            }
            if ($msg->message->type == 3) {
                
                $data['message']['chat'][] = $msg->message;
            }
            if ($msg->message->type == 4 || $msg->message->type == 5 || $msg->message->type == 6) {
                $data['message']['task'][] = $msg->message;
            }
        }
        $data['chatusers']=$this->chatUsers(0);    
        echo json_encode($data);
    }

    public function allread($id, $ids) {

        $Notif = Notifications::where('notifiable_id', $ids)->where('isread', 0)->get();
        foreach ($Notif as $v) {
            $type = json_decode($v->data)->message->type;
            switch ($id) {
                case 1:
                    if ($type == 4 || $type == 5) {
                        Notifications::where('notifiable_id', $ids)->update(['isread' => 1]);
                    }
                    break;
                case 2:
                    if ($type == 1 || $type == 2) {
                        Notifications::where('notifiable_id', $ids)->update(['isread' => 1]);
                    }

                    break;
                case 3:
                    if ($type == 3) {
                        Notifications::where('notifiable_id', $ids)->update(['isread' => 1]);
                    }
                    break;
            }
        }
    }

    public function chat(Request $request, $ids) {
        echo $ids;
        echo '<hr>'.CheatRoomID($ids);
        
        
        die;
        $authUser = $request->user();
        die;
        $otherUser = User::find(explode('-', $ids)[1]);
        $users = User::where('id', '<>', $authUser->id)->get();

        $twilio = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));

        $temp_ids = explode('-', $ids);
        if ($temp_ids[0] > $temp_ids[1]) {
            $ids = $temp_ids[1] . '-' . $temp_ids[0];
        }
        // Fetch channel or create a new one if it doesn't exist
        try {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels($ids)
                    ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels
                    ->create([
                'uniqueName' => $ids,
                'type' => 'private',
            ]);
        }

        // Add first user to the channel
        try {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels($ids)
                    ->members($authUser->email)
                    ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $member = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels($ids)
                    ->members
                    ->create($authUser->email);
        }

        // Add second user to the channel
        try {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels($ids)
                    ->members($otherUser->email)
                    ->fetch();
        } catch (\Twilio\Exceptions\RestException $e) {
            $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                    ->channels($ids)
                    ->members
                    ->create($otherUser->email);
        }

        return view('messages.chat', compact('users', 'otherUser'));
    }

    public function ChatInPopup(Request $request, $ids)
    {
        return view('chatwindow.chatinpopup', compact('ids'));
    }
    
    
    public function chatUsers($i=0) {
        $data = Auth::User();
        $users = array();
        $data->role_id;
        $data->id;
        $q = '';
        switch ($data->role_id) {
            //Admin
            case '1':
                $q = "SELECT id,name,role_id,avatar,status,(select count(*) from tila_chat_room as r where inbrowsernotify=1 and r.from=id) as numberofmsg FROM `users` as u WHERE (u.role_id = '2' or u.role_id = '1') and u.id!=$data->id  order by u.role_id";
                $users = (object) DB::select(DB::raw($q));
                break;
            //Firm
            case '4':
                $q = "select va.* from users as firm,admintask as t,users as va where firm.id=t.firm_admin_id and t.task='Assign Case to VP' and t.allot_user_id=va.id and firm.id='" . $data->id . "' group by t.allot_user_id";
                $q1 = "SELECT id,name,role_id,avatar,status,(select count(*) from tila_chat_room as r where inbrowsernotify=1 and r.from=id) as numberofmsg FROM `users` WHERE `firm_id` = '" . $data->firm_id . "' AND (role_id = '5' or role_id = '4') and id!=$data->id  group by id order by role_id";
                //$q2 = "SELECT * FROM `users` WHERE `role_id` = '1'  group by id";
                //$Super = DB::select(DB::raw($q2));
                $USERANDCLIENT = DB::select(DB::raw($q1));
                $VAUSER = DB::select(DB::raw($q));
                $users = (object) array_merge($USERANDCLIENT, $VAUSER);

                break;
            //VA User
            case '2':
                $q = "select firm.* from users as firm,admintask as t,users as va where firm.id=t.firm_admin_id and t.task='Assign Case to VP' and t.allot_user_id=va.id and va.id='" . $data->id . "' group by firm.id";
                $q1 = "SELECT * FROM `users` WHERE `role_id` = '1'  group by id";
                $q2 = "select u.id,u.name,u.role_id,u.avatar,u.status from admintask as t,`case` as c,users as u where t.allot_user_id='" . $data->id . "' and t.case_id>0 and c.id=t.case_id and u.id=c.client_id group by u.id";
                $q3 = "select uf.* from admintask as t,`case` as c,users as u,usermeta as um,users as uf where t.allot_user_id='" . $data->id . "' and t.case_id>0 and c.id=t.case_id and u.id=c.client_id and um.meta_key='CaseID' and t.case_id=um.meta_value and uf.id=um.user_id group by uf.id";
                $q4 = "select u.id,u.name,u.role_id,u.avatar,u.status from admintask as t,`case` as c,users as u where t.allot_user_id='" . $data->id . "' and t.case_id>0 and c.id=t.case_id and u.firm_id=c.firm_id and (u.role_id = '5') group by u.id";
                $Firm = DB::select(DB::raw($q));
                $SuperUser = DB::select(DB::raw($q1));
                $Client = DB::select(DB::raw($q2));
                $usersFamily = DB::select(DB::raw($q3));
                $FirmUsers = DB::select(DB::raw($q4));
                // $users = array_merge($SuperUser, $Firm, $Client, $usersFamily);
                $users = array_merge($SuperUser, $Firm, $FirmUsers);
                break;
            //VA User
            case '5':
                $firmadmin = User::select('users.*')
                            ->leftjoin('firms', 'firms.email', '=', 'users.email')
                            ->where('firms.id', Auth::user()->firm_id)
                            ->first();
                $q = "select va.* from users as firm,admintask as t,users as va where firm.id=t.firm_admin_id and t.task='Assign Case to VP' and t.allot_user_id=va.id and firm.id='" . $firmadmin->id . "' group by t.allot_user_id";
                $VAUSER = DB::select(DB::raw($q));

                

                $q1 = "SELECT id,name,role_id,avatar,status,(select count(*) from tila_chat_room as r where inbrowsernotify=1 and r.from=id) as numberofmsg FROM `users` WHERE (role_id = 4 or role_id = 5) and firm_id='" . $data->firm_id . "' and id!=$data->id  group by id order by role_id";
                $USERANDCLIENT = DB::select(DB::raw($q1));
                $users = (object) array_merge($USERANDCLIENT, $VAUSER);
                // pre($firmadmin);
                // pre($VAUSER);
                break;
            //VA User
            case '6':
                $q1 = "SELECT id,name,role_id,avatar,status,(select count(*) from tila_chat_room as r where inbrowsernotify=1 and r.from=id) as numberofmsg FROM `users` WHERE `role_id` = '1'  group by id";
                $users = DB::select(DB::raw($q1));
                break;
            case '7':
                $q1 = "SELECT id,name,role_id,avatar,status,(select count(*) from tila_chat_room as r where inbrowsernotify=1 and r.from=id) as numberofmsg FROM `users` WHERE `role_id` = '1'  group by id";
                $users = DB::select(DB::raw($q1));
                break;
        }



        $dd = array();
        $i = 0;

        $IDC = $data->id;
        foreach ($users as $k => $u) {

            $qn = 'select  cast(JSON_EXTRACT(JSON_EXTRACT(data, "$.message"),"$.type")  AS UNSIGNED) as  notify from notifications where isread=0 and notifiable_id="' . $u->id . '"  having notify =3';
            // $qnR = DB::select(DB::raw($qn));
            // if (count($qnR) > 0) {
            //     $u->notify = 1;
            // }
            // $u->password = '';
            if (!CurlDataStatue($u->avatar)) {
                $u->avatar = '/avatar.png';
            } else {
                $u->avatar = '/storage/app/' . $u->avatar;
            }
            $u->chatWith = $IDC;

            switch ($u->role_id) {
                case '1':
                    $u->type = 'Super Admin';
                    if($data->role_id==1)
                        $u->type = 'Tila Admin';
                    
                    break;
                case '2':
                    $u->type = 'VP User';
                    break;
                case '3':
                    $u->type = 'TILA Support';
                    break;
                case '4':
                    $u->type = 'Firm Admin';
                    break;
                case '5':
                    $u->type = 'Firm User';
                    break;
                case '6':
                    $u->type = 'Client';
                    break;
                case '7':
                    $u->type = 'Client Family';
                    break;
            }
            //$dd[$i]['type']=$data->role_id;
            $i++;
        }
        $users=json_decode(json_encode($users), true);
        #pre($users);die;
        foreach($users as $k=>$u)
        {
            
            $users[$k]['ShowNotifications']=ShowChatNotifications($users[$k]['id'].'-'.$users[$k]['chatWith']);
        }
        return  json_decode(json_encode($users), true);
        //echo json_encode($d);
    }

    public function chatNote(Request $request, $ID) {
        $data = Auth::User();
        if (isset($_REQUEST['msg'])) {
            $MSG = base64_encode($_REQUEST['msg']);
            CreateCheatRoom($ID, $MSG);
        }
        $IDS = explode('-', $ID);
        $key = array_search($data->id, $IDS);
        $seander = $IDS[$key];
        unset($IDS[$key]);
        $reciver = implode(',', $IDS);
        /* --------------------Notifications--------------- */
        
        $msg = Auth::User()->name . ' Chat Message Hello Msg';
        $touser = User::where('id', $reciver)->first();
        $message = collect(['title' => 'Chat Massage', 'body' => $msg, 'type' => '3', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */
    }

    public function ReadNotify(Request $request, $ID) {
        $data = Auth::User();
        //$IDS = explode('-', $ID);

        $qq = "select CAST(JSON_EXTRACT(JSON_EXTRACT(data, '$.message'),'$.type') as UNSIGNED) as types,n.id from notifications as n where notifiable_id='" . $ID . "'  and isread=0 having types=3";
        $Chats = DB::select(DB::raw($qq));
        foreach ($Chats as $v) {
            $qq1 = "DELETE FROM `notifications` WHERE id = '" . $v->id . "'";
            $Chats = DB::select(DB::raw($qq1));
        }
    }

    public function readnotification($id) {
        $Notif = Notifications::where('id', $id)->first();
        $arr = $Notif->data;
        $arr = json_decode($arr);
        Notifications::where('id', $id)->update(['isread' => 1]);
        if(!empty($arr->message->link)) {
            $link = $arr->message->link;
            return redirect($link);
        }
        else {
            return redirect()->back();
        }
    }

    public function view_all_notification() {
        $data = Auth::user();
        $ID = Auth::user()->id;
        $Notif = Notifications::where('notifiable_id', $ID)->orderBy('created_at', 'DESC')->get();
        // Notifications::where('notifiable_id', $ID)->update(['isread' => 1]);
        if ($data->role_id == 1 || $data->role_id == 2 || $data->role_id == 3) {
            return view('messages.view_all_notification1', compact('Notif'));
        } 
        else if ($data->role_id == 4 || $data->role_id == 5 || $data->role_id == 6) {
           return view('messages.view_all_notification', compact('Notif'));
        } 
        else if ($data->role_id == 7) {
            return view('messages.view_all_notification', compact('Notif'));
        }
        
    }

    public function view_unread_notification(Request $request) {
        $ID = Auth::user()->id;
        $isread = $request->isread;
        if($isread) {
           $Notif = Notifications::where('notifiable_id', $ID)->orderBy('created_at', 'DESC')->get(); 
        }
        else {
            $Notif = Notifications::where('notifiable_id', $ID)->where('isread', 0)->orderBy('created_at', 'DESC')->get();
            Notifications::where('notifiable_id', $ID)->update(['isread' => 1]);
        }
        
        if(!empty($Notif)) {
          foreach ($Notif as $k => $v) { 
            $v1 = json_decode($v->data)->message;
            $Notif[$k]->title = $v1->title;
            $Notif[$k]->body = $v1->body;
            $nlink = '#';
            if(!empty($v1->link)) {
                $nlink = $v1->link;
            }
            $Notif[$k]->link = $nlink;
          }
        }
        return datatables()->of($Notif)->toJson();
        // Notifications::where('notifiable_id', $ID)->update(['isread' => 1]);
        // return view('messages.view_unread_notification', compact('Notif'));
    }

}
