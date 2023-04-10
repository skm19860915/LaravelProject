<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Http\Request;
use App\User;
use App\Models\AdminTask;
use App\Models\Newclient;
use App\Models\ClientInformation;
use App\Models\FirmCase;
use App\Models\ClientTask;
use App\Models\ClientNotes;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\DocumentRequest;
use App\Models\ClientDocument;
use App\Models\TextMessage;
use App\Models\ClientFamily;
use App\Models\UserMeta;
use App\Models\Firm;
use App\Models\CaseType;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Notifications\DatabaseNotification;
use Notification;
use App;
use DB;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class UserClientController extends Controller
{
    public function __construct()
    {
        require_once(base_path('public/calenderApi/settings.php'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function index()
    {
        return view('admin.adminuser.usertask.index');
    }*/

    public function viewclient($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $data = array();
        $data['total_case'] = FirmCase::select('*')->where('client_id', $client->user_id)->count();
        $data['total_task'] = ClientTask::select('*')->where('related_id', $client->id)->where('task_for', 'CLIENT')->count();
        $data['total_note'] = ClientNotes::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->count();
        $data['total_event'] = Event::select('*')->where('related_id', $id)->count();
        $data['total_billing'] = Transaction::select('*')->where('user_id', $client->user_id)->sum('amount');
        $data['totla_forms'] = ClientInformation::select('*')->where('client_id', $client->user_id)->count();
        $requested_doc = DocumentRequest::select('*')->where('client_id', $id)->count();
        $client_doc = ClientDocument::select('*')->where('client_id', $id)->count();
        $data['total_document'] = intval($requested_doc) + intval($client_doc);
        $task = ClientTask::select('*')->where('related_id', $id)->where('task_for', 'CLIENT')->get();
            return view('admin.adminuser.userclient.viewclient', compact('client', 'data', 'task'));
    }

    public function profile($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $ques = Questionnaire::select('*')
                ->where('client_id', $client->user_id)
                ->get();
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
            ->where('users.role_id' ,'=', '7')
            // ->where('users.firm_id' ,'=', $firm_id)
            ->join('client_family', 'client_family.email', '=', 'users.email')
            ->where('client_family.client_id', '=', $client->id)
            ->get();
        $beneficiary_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->where('client_family.type', 'Beneficiary')
        ->where('client_family.client_id', $client->id)
        ->where('users.role_id' ,'=', '7')
        ->first();
        return view('admin.adminuser.userclient.profile', compact('client', 'ques', 'family_alllist', 'beneficiary_list'));
    }

    public function clientcases($id) {
        $client = User::select('users.*', 'new_client.*')
            ->where('users.id', $id)
            ->join('new_client', 'new_client.user_id', '=', 'users.id')
            ->first();
        $cases = FirmCase::select('case.*', 'users.name as user_name', 'ur.name as client_name', 'urp.name as paralegal_name','new_client.id as clientid', 'admintask.allot_user_id as vp_id', 'admintask.id as aid')
                    ->join('users', 'case.user_id', '=', 'users.id')
                    ->join('users as ur', 'case.client_id', '=', 'ur.id')
                    ->join('new_client', 'new_client.user_id', '=', 'case.client_id')
                    ->leftJoin('users as urp', 'case.assign_paralegal', '=', 'urp.id')
                    ->where('case.client_id', $id)
                    ->where('case.VP_Assistance', 1)
                    ->leftJoin('admintask', 'admintask.case_id', '=', 'case.id')
                    ->where('admintask.task_type', 'Assign_Case')
                    ->orderBy('case.id', 'DESC')
                    // ->skip($s)->take($l)
                    ->get();
        return view('admin.adminuser.userclient.clientcases', compact('client', 'cases'));
    }

    public function viewfamily($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();

        $family_list = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->where('users.role_id' ,'=', '7')
        ->where('client_id', $client->id)
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        return view('admin.adminuser.userclient.viewfamily', compact('family_list', 'client'));
    }

    public function viewnotes($id) {

        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $msg = array();

        $notes_list = ClientNotes::select('client_notes.*', 'users.name as username')
                ->join('users', 'client_notes.created_by', '=', 'users.id')
                ->where('client_notes.related_id', $client->id)
                ->where('client_notes.task_for', 'CLIENT')
                ->orderBy('id', 'DESC')
                ->get();

        foreach ($notes_list as $k => $v) {
            $is_edit = false; 
            if(Auth::User()->id == $v->created_by) {
                $is_edit = true;
            }
            $msg[] = (object) array(
                        'id' => $v->id,
                        'subject' => $v->subject,
                        'message' => $v->notes,
                        'created_by' => $v->username,
                        'type' => 'Note',
                        'create_date' => date('m/d/Y', strtotime($v->created_at)),
                        'create_time' => date('h:i A', strtotime($v->created_at)),
                        'is_edit' => $is_edit
                    );
        }

        return view('admin.adminuser.userclient.viewnotes', compact('msg', 'client'));
    }

    public function deletenotes($id, $nid) {
        
        $note = ClientNotes::select('*')
                ->where('id', $nid)
                ->first();
        ClientNotes::where('id', $nid)->delete();
        return redirect('admin/userclient/viewnotes/'.$id)->with('success','Note delete successfully!');
    }

    public function viewinbox($id) {

        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $msg = array();
        
        $messages = TextMessage::select('text_message.*', 'u1.name as username')
        // ->where('text_message.msgfrom', $data->id)
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

        return view('admin.adminuser.userclient.viewinbox', compact('msg', 'client'));
    }

    public function addnotes(Request $request) {
        $res = array();
        $validator = Validator::make($request->all(), [
                    'subject' => 'required|string',
                    'note' => 'required|string',
        ]);
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
        }
        $data = [
            'task_for' => 'CLIENT',
            'related_id' => $request->client_id,
            'subject' => $request->subject,
            'notes' => $request->note,
            'created_by' => Auth::User()->id
        ];

        /* --------------------Notifications--------------- */

        $nuser = User::where('id', Auth::User()->id)->first();
        //$firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        $msg = $nuser->name . ' TILA VP Created Some Note For Client';

        $touser = User::where('id', 1)->first();
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        //Notification::send($touser, new DatabaseNotification($message));

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('admin/client/view_notes') . '/' . $request->client_id;
        $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        if($request->note_id) {
            $res['msg'] = 'Note update successfully!';
            ClientNotes::where('id', $request->note_id)->update($data);
        }
        else {
            $res['msg'] = 'Note created successfully!';
            $note = ClientNotes::create($data);
        }

        $res['status'] = true;
        echo json_encode($res);
    }

    public function clienttasks($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        // $task = ClientTask::select('*')->where('related_id', $client->id)->where('task_for', 'CLIENT')->get();
        $task = ClientTask::select('client_task.*', 'users.name')
                ->where('client_task.related_id', $client->id)
                ->where('client_task.task_for', 'CLIENT')
                ->leftJoin('users', 'users.id', '=', 'client_task.created_by')
                ->get();
        $atask = AdminTask::select('*')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->where('task_type', 'ADMIN_TASK')
            ->where('client_task', $client->user_id)
            ->get(); 
        return view('admin.adminuser.userclient.clienttasks', compact('client', 'task', 'atask'));
    }

    public function addclienttask($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        return view('admin.adminuser.userclient.addclienttask', compact('id', 'client'));
    }

    public function insertclienttask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('admin/userclient/addclienttask/' . $request->uid)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'task_for' => 'CLIENT',
            'related_id' => $request->client_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            // 's_date' => date('Y-m-d', strtotime($dates[0])),
            // 's_time' => date('h:i A', strtotime($dates[0])),
            'e_date' => date('Y-m-d', strtotime($request->date)),
            'e_time' => date('h:i A', strtotime($request->date)),
            'created_by' => Auth::User()->id,
            'status' => 0
        ];
        ClientTask::create($data);
        return redirect('admin/userclient/clienttasks/' . $request->uid)->withInfo('Task created successfully');
    }

    public function editclienttask($id, $tid) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $task = ClientTask::where('id', $tid)->first();
        return view('admin.adminuser.userclient.editclienttask', compact('id', 'client', 'task'));
    }

    public function updateclienttask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'date' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('admin/userclient/editclienttask/' . $request->client_id.'/'.$request->tid)->withInfo('Mendatory fields are required!');
        }
        $data = [
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'e_date' => date('Y-m-d', strtotime($request->date)),
            'e_time' => date('h:i A', strtotime($request->date)),
            'status' => $request->status
        ];
        ClientTask::where('id', $request->tid)->update($data);
        return redirect('admin/userclient/clienttasks/' . $request->client_id)->withInfo('Task update successfully');
    }

    public function clientsevents($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $event = Event::select('*')->where('related_id', $client->id)->get();
        return view('admin.adminuser.userclient.clientsevents', compact('client', 'event'));
    }

    public function createevent(Request $request, $id) {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $provider = new Google([
            'clientId'     => CLIENT_ID,
            'clientSecret' => CLIENT_SECRET,
            'redirectUri'  => CLIENT_REDIRECT_URL,
            'accessType'   => 'offline',
            'hostedDomain' => "",
        ]);

        $authUrl = $provider->getAuthorizationUrl([
                        'scope' => [
                            'https://www.googleapis.com/auth/calendar'
                        ],
                        'prompt' => 'consent'
                    ]);
        $_SESSION['oauth2state'] = $provider->getState();

        $data = Auth::User();
        $user_id = $data->id;
        $users = User::select('users.*', 'roles.name as role_name')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('firm_id', $data->firm_id)
                // ->where('users.id', '!=', $data->id)
                ->whereIn('role_id', ['4', '5'])
                ->get();



        $arr1 = Event::select('who_consult_with')
                ->where('related_id', $id)
                ->where('title', "CLIENT")
                ->orderBy('id', 'DESC')
                ->first();


        if ($users) {
            foreach ($users as $k => $v) {
                if (isset($_REQUEST['reschedule'])) {
                    $wcw = json_decode($arr1->who_consult_with);
                    if (in_array($v->id, $wcw)) {
                        $users[$k]->userset = 1;
                    } else {
                        $users[$k]->userset = 0;
                    }
                } else {
                    $users[$k]->userset = 0;
                }
            }
        }


        $arr = Event::select('*')
                ->where('related_id', $id)
                ->where('title', "CLIENT")
                ->orderBy('id', 'DESC')
                ->get();

        $arr = Event::select('event.*', 'new_client.*', 'event.id as e_id')
                ->join('new_client','new_client.id', 'event.related_id')
                ->where('event.related_id',$id)
                ->where('event.title',"CLIENT")
                ->get();

        $events = array();
        $dateandtime = array('dates' => '', 'event_title' => '', 'coutner' => 0, 'event_type' => '');
        if ($arr) {
            foreach ($arr as $key => $e) {
                $select = 0;
                $wcw = json_decode($arr1->who_consult_with);
                if (isset($_REQUEST['reschedule']) && $e->related_id == $id) {
                    $edates = $e->s_date . ' ' . $e->s_time . ' - ' . $e->e_date . ' ' . $e->e_time;
                    $dateandtime = array('dates' => $edates, 'event_title' => $e->event_title, 'coutner' => $e->coutner, 'event_type' => $e->event_type);
                }
                $s_time  = date("H:i:s", strtotime($e->s_time));
                $e_time  = date("H:i:s", strtotime($e->e_time));
                $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->first_name.' '.$e->middle_name.' '.$e->last_name;
                $eedate = '';
                if(!empty($e->e_date)) {
                    $eedate = date('m/d/Y', strtotime($e->e_date));
                }
                $events[] = array(
                    'event_id' => $e->e_id,
                    'title' => $etitle, 
                    'act_title' => $e->event_title, 
                    'start' => $e->s_date . 'T' . $s_time,
                    'end' => $e->e_date.'T'.$e_time,
                    'event_end' => $eedate.' '.$e->e_time,
                    'event_type' => $e->event_type,
                    'description' => $e->event_description,
                    'who_consult_with' => json_decode($e->who_consult_with),
                );
            }
        }
        $events = json_encode($events);
        $access_token = get_user_meta($data->id, 'access_token');
        if(!empty($access_token)) {
            $elist = GetCalendarsList($access_token);
            if($elist == 0) {
                $refreshToken = get_user_meta($data->id, 'refresh_token');
                $grant = new RefreshToken();
                $access_token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
                update_user_meta($data->id, 'access_token', $access_token);
            }
        }
        CalenderRedirectSessionSave();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        return view('admin.adminuser.userclient.createevent', compact('id', 'users', 'events', 'access_token', 'dateandtime', 'user_id', 'client', 'authUrl'));
    }

    public function createclientevent(Request $request) {

        $res = array();
        $reminder_arr = array();
        $reminders = array(
                'useDefault' => FALSE,
                'overrides' => array()
              );
        foreach ($request->event_reminder as $key => $value) {
            $rname = $value['name'];
            $rval = $value['value'];
            if ($rname == 'event_reminder[count][]') {
                $reminder_arr['count'][] = $rval;
            }
            if ($rname == 'event_reminder[type][]') {
                $reminder_arr['type'][] = $rval;
            }
            // pre($value);
        }
        foreach ($reminder_arr['type'] as $k => $v) {
            $m = $reminder_arr['count'][$k];
            if($v == 'minutes') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m
                                        );
            }
            else if($v == 'hours') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60
                                        );
            }
            else if($v == 'days') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24
                                        );
            }
            else if($v == 'weeks') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24*7
                                        );
            }
            else if($v == 'months') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*24*30
                                        );
            }
            else if($v == 'years') {
                $reminders['overrides'][] = array(
                                            'method' => 'email',
                                            'minutes' => $m*60*60*24*30*12
                                        );
            }

        }
        
        $validator = Validator::make($request->all(), [
                    's_date' => 'required',
                    'event_title' => 'required',
                    'event_type' => 'required',
                    'who_consult_with' => 'required'
        ]);
        // print_r($validator->fails());
        // die();
        if ($validator->fails()) {
            $res['status'] = false;
            $res['msg'] = $validator->errors()->first();
            echo json_encode($res);
            die();
            // return redirect('firm/create_event')->withInfo('Mendatory fields are required!');
        }

        $s_date = $request->s_date;
        $e_date = $request->e_date;
        if ($request->event_type == 'Reminder') {
            $lead_event_data = [
                'title' => "CLIENT",
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_description' => $request->event_description,
                'related_id' => $request->lead_id,
                's_date' => date('Y-m-d', strtotime($s_date)),
                's_time' => date('h:i A', strtotime($s_date)),
                'e_date' => date('Y-m-d', strtotime($s_date)),
                'e_time' => date('h:i A', strtotime($s_date)),
                'who_consult_with' => json_encode(array($request->who_consult_with)),
                'attorney' => Auth::User()->id
            ];
        } else {
            $lead_event_data = [
                'title' => "CLIENT",
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_description' => $request->event_description,
                'related_id' => $request->lead_id,
                's_date' => date('Y-m-d', strtotime($s_date)),
                's_time' => date('h:i A', strtotime($s_date)),
                'e_date' => date('Y-m-d', strtotime($e_date)),
                'e_time' => date('h:i A', strtotime($e_date)),
                'who_consult_with' => json_encode(array($request->who_consult_with)),
                'attorney' => Auth::User()->id
            ];
        }

        
        $lead_event_data['event_reminder'] = json_encode($reminder_arr);
        $event_id1 = 0;
        if ($request->reschedule == "true") {
            $lead_event_data['coutner'] = $request->coutner;
            $event = Event::where('related_id', $request->lead_id)->update($lead_event_data);
        } else {
            //$event = Event::create($lead_event_data);
            if($request->event_id) {
                $event = Event::where('id', $request->event_id)->update($lead_event_data);
                $event_id1 = $request->event_id;
            }
            else {
                $event = Event::create($lead_event_data);
                $event_id1 = $event->id;
                $remove = array(
                    'time' => date('h:i A', strtotime($s_date)),
                    'date' => date('Y-m-d', strtotime($s_date)),
                    'titleofevent' => $request->event_title
                );
                $email = EmailTemplate(12, $remove);
                // if(!empty($request->who_consult_with)) {
                //     foreach ($request->who_consult_with as $k => $v) {
                //         $u = User::select('*')->where('id', $v)->first();
                //         $args = array(
                //             'bodyMessage' => $email['MSG'],
                //             'to' => $u->email,
                //             'subject' => $email['Subject'],
                //             'from_name' => 'TILA',
                //             'from_email' => 'no-reply@tilacaseprep.com'
                //         );
                //         send_mail($args);
                //     }
                // }
            }
        }
        

        if (isset($request->create_lead_with_event)) {

            //return redirect()->route('firm.client')->with('success', 'Firm Lead Create and Schedule Consult successfully!');
        }


        $res['status'] = true;
        $res['msg'] = 'Client Event created successfully!';
        echo json_encode($res);
        $access_token = get_user_meta(Auth::User()->id, 'access_token');
        if (!empty($access_token)) {
            $user_timezone = GetUserCalendarTimezone($access_token);
            $etime = array();
            $etime['start_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            if($request->event_type == 'Reminder') {
                $etime['end_time'] = date('Y-m-d', strtotime($s_date)) . 'T' . date('H:i:s', strtotime($s_date));
            }
            else {
                $etime['end_time'] = date('Y-m-d', strtotime($e_date)) . 'T' . date('H:i:s', strtotime($e_date));
            }

            if(empty($request->event_id)) {
                $gid = CreateCalendarEvent('primary', $request->event_title, 0, $etime, $user_timezone, $access_token, $reminders);
                Event::where('id', $event_id1)->update(['google_id' => $gid]);
            }
        }

        /* --------------------Notifications--------------- */

        // $firm_id = Auth::User()->firm_id;
        // $firm_name = Firm::select('firm_name')->where('id', $firm_id)->first();
        // $msg = $firm_name->firm_name . ' Firm admin created a Event';

        // $touser = User::where('id', 1)->first();
        // $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name]);
        // // Notification::send($touser, new DatabaseNotification($message));

        // $n_link = url('firm/client/client_event') . '/' . $request->lead_id;
        // $touser = User::where('id', Auth::User()->id)->first();
        // $message = collect(['title' => 'Firm Admin Create  Client', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link' => $n_link]);
        // Notification::send($touser, new DatabaseNotification($message));
        // if($request->who_consult_with) {
        //     foreach ($request->who_consult_with as $k => $v) {
        //         $touser = User::where('id', $v)->first();
        //         Notification::send($touser, new DatabaseNotification($message));
        //     }
        // }
        
        /* --------------------Notifications--------------- */




        die();
        if ($event) {
            //return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        } else {
            //return redirect('firm/create_event')->withInfo(' not created, please try again');
        }
    }

    public function clienteditevent($id, $eid) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $event = Event::select('*')->where('id', $eid)->first();
        $data = Auth::User();
        $users = User::select('users.*', 'roles.name as role_name')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('firm_id', $data->firm_id)
                // ->where('users.id', '!=', $data->id)
                ->whereIn('role_id', ['4', '5'])
                ->get();

        return view('admin.adminuser.userclient.editclientevent', compact('client', 'event', 'users'));
    }

    public function clientdocument($id) {
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
        if ($searchResults->data) {
            $cust = $searchResults->data[0];
            $card = $cust->sources->data;
        }
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $requested_doc = DocumentRequest::select('*')->where('family_id', $client->user_id)->get();
        $client_doc = ClientDocument::select('*')->where('client_id', $client->id)->get();
        return view('admin.adminuser.userclient.clientdocument', compact('client', 'requested_doc', 'client_doc', 'card'));
    }

    public function Set_Client_Document(Request $request) {
        $data = Auth::User();
        // pre($request->all());
        // die();
        foreach ($request->file as $key => $file) {
            $f = Storage::put('client_doc', $file);
            $data = [
                'client_id' => $request->client_id,
                'uploaded_by' => $data->id,
                'document' => $f,
                'title' => $request->title,
                'description' => $request->description
            ];
            ClientDocument::create($data);
        }

        /* --------------------Notifications--------------- */

        $firm_id = Auth::User()->firm_id;
        $msg = 'TILA VP ' . Auth::User()->name . ' upload Document Successfully!';

        $client_name = Newclient::select('*')->where('id', $request->client_id)->first();
        $case = FirmCase::select('*')->where('client_id', $client_name->user_id)->orderBy('id', 'DESC')->first();
        $touser = User::where('id', $client_name->user_id)->first();
        if(!empty($case)) {
            $n_link = url('firm/firmclient/document_requests').'/'.$case->id;
        }
        else {
            $n_link = url('firm/clientdashboard');
        }
        $message = collect(['title' => 'Firm Admin upload Document', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        if(!empty($touser)) {
            Notification::send($touser, new DatabaseNotification($message));    
        }
        

        $touser = User::where('id', Auth::User()->id)->first();
        $n_link = url('admin/userclient/clientdocument').'/'.$request->uid;
        $message = collect(['title' => 'Firm Admin upload Document', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        /* --------------------Notifications--------------- */

        return redirect('admin/userclient/clientdocument/' . $request->uid)->with('success', 'Client document upload successfully!');
    }

    public function getDataClientDocument($id)
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

    public function deletedoc($id, $did) {
        ClientDocument::where('id', $did)->delete();
        return redirect('admin/userclient/clientdocument/'.$id)->with('success','Document delete successfully');
    }

    public function addfamily($id) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $q="SELECT * FROM countries ORDER BY id = 230 DESC, name ASC";
        $countries = DB::select($q);
        return view('admin.adminuser.userclient.addfamily', compact('id', 'client', 'countries'));
    }

    public function createfamily(Request $request) {
        $record = $request->all();
        $record['name'] = $request->first_name;
        if(!empty($request->middle_name)) {
            $record['name'] .= ' '.$request->middle_name;
        }
        if(!empty($request->last_name)) {
            $record['name'] .= ' '.$request->last_name;
        }

        $newdata = array();
        $current_firm_id = $request->firm_id;

        $cl_email = $request->email;
        if ($request->is_portal_access || !empty($cl_email)) {
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }
        else {
            if(empty($request->email)) {
                $cl_email = 'dummy'.time().'@tilacaseprep.com';
            }
            $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    // 'email' => 'required|string|email|unique:users|unique:new_client',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $record['email'] = $cl_email;
        
        app('App\Http\Controllers\HomeController')->CreateClientFamily($record, $current_firm_id);
        ;

        // $record['dob'] = date('Y-m-d', strtotime($record['dob']));
        $check = ClientFamily::create($record);

        if ($check) {
            return redirect('admin/userclient/viewfamily/' . $request->uid)->with('success', 'Family added successfully!');
        } else {
            return redirect('admin/userclient/viewfamily/' . $request->uid)->with('error', 'client family not created, please try again');
        }
    }

    public function editfamily($id, $fid) {
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $countries = DB::table("countries")->get();
        $FamilyMember = get_user_meta($fid, 'FamilyMember');
        $FamilyMember = json_decode($FamilyMember);
        $r_states = array();
        if (!empty($FamilyMember->residence_address->country)) {
            $r_states = DB::table("regions")
                    ->where("country_id", $FamilyMember->residence_address->country)
                    ->pluck("name", "id");
        }
        return view('admin.adminuser.userclient.editfamily', compact('id', 'fid', 'client', 'countries', 'FamilyMember', 'r_states'));                                                                          
    }

    public function deletefamily($id, $fid) {
        $f_user = User::where('id', $fid)->first();
        UserMeta::where('user_id', $fid)->delete();
        User::where('id', $fid)->delete();
        ClientFamily::where('email', $f_user->email)->delete();
        return redirect('admin/userclient/viewfamily/'.$id)->with('success','Member delete successfully');
    }

    public function updateforms(Request $request) {
        extract($_POST);
        $client_arr = array();
        $client = ClientInformation::select('*')->where('id', $id)->first();
        $addr = json_decode($client->residence_address);
        $pdf_data = json_decode($_POST['data']);
        foreach ($pdf_data as $k => $v) {
            if(!empty($v->value)) {
                if (strpos($v->name, 'FirstName')) {
                    $client_arr['first_name'] = $v->value;
                }
                if (strpos($v->name, 'LastName')) {
                    $client_arr['last_name'] = $v->value;
                }
                if (strpos($v->name, 'MiddleName')) {
                    $client_arr['middle_name'] = $v->value;
                }
                if (strpos($v->name, 'Mobile')) {
                    $client_arr['cell_phone'] = $v->value;
                }

                if (strpos($v->name, 'Country')) {
                    $countries = DB::table("countries")->where('name', $v->value)->first();
                    if(!empty($countries)) {
                        $addr['country'] = $countries->id;
                    }
                }

                if (strpos($v->name, 'State')) {
                    $regions = DB::table("regions")->where('name', $v->value)->first();
                    if(!empty($regions)) {
                        $addr['state'] = $regions->id;
                    }
                }

                if (strpos($v->name, 'City')) {
                    $cities = DB::table("cities")->where('name', $v->value)->first();
                    if(!empty($cities)) {
                        $addr['city'] = $cities->id;
                        $addr['state'] = $cities->region_id;
                    }
                }

                if (strpos($v->name, 'Street')) {
                    $addr['address'] = $v->value;
                }
            }
        }
        if(!empty($addr)) {
            $client_arr['residence_address'] = json_encode($addr);
        }
        if(!empty($client_arr)) {
            
            //Newclient::where('id', $client->client_id)->update($client_arr);
        }
        
        
        
        
        updatePdfFormAllField($id,$data);
        
        
        $data1 = array('information' => $data, 'status' => 0);
        ClientInformation::where('id', $id)->update($data1);
        // AdminTask::where('id', $request->task_id)->update($data);
    }

    public function editclientdocument($id, $did) {
        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $requested_doc = DocumentRequest::select('*')->where('id', $did)->first();
        $case = FirmCase::select('*')->where('id', $requested_doc->case_id)->first();
        $CaseTypes = array();
        if(!empty($case)) {
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['CaseID', 'beneficiary', 'derivative', 'Co_Sponsor', 'Household_Member', 'petitioner'])
        ->where('usermeta.meta_value', $case->id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();
        // pre($requested_doc);
        // die();
        return view('admin.adminuser.userclient.editclientdocument', compact('client', 'requested_doc', 'CaseTypes', 'family_alllist', 'case'));
    }

    public function updaterequestdocuments(Request $request) {
        // pre($request->all());
        // die();
        $data =  [
            'family_id' => $request->client_id,
            'document_type' => $request->file_type,
            'expiration_date' => $request->expiration_date,
        ];
        DocumentRequest::where('id', $request->did)->update($data);
        return redirect('admin/userclient/clientdocument/'.$request->client_id)->with('success', 'Document request update successfully!');
    }

    public function uploadclientdocument($id, $did) {
        $data = Auth::User();
        $client = User::select('users.*', 'new_client.*')
                ->where('users.id', $id)
                ->join('new_client', 'new_client.user_id', '=', 'users.id')
                ->first();
        $requested_doc = DocumentRequest::select('*')->where('id', $did)->first();
        $case = FirmCase::select('*')->where('id', $requested_doc->case_id)->first();
        $CaseTypes = array();
        if(!empty($case)) {
            $CaseTypes = CaseType::select('*')
            ->where('Case_Category', $case->case_category)
            ->where('Case_Type', $case->case_type)
            ->get();
            $CaseTypes[0]->Required_Documentation_en = json_decode($CaseTypes[0]->Required_Documentation_en);
        }
        $family_alllist = User::select('users.*', 'client_family.*', 'users.id as uid')
        ->join('usermeta', 'usermeta.user_id', '=', 'users.id')
        ->whereIn('usermeta.meta_key', ['CaseID', 'beneficiary', 'derivative', 'Co_Sponsor', 'Household_Member', 'petitioner'])
        ->where('usermeta.meta_value', $case->id)
        ->where('users.role_id' ,'=', '7')
        ->join('client_family', 'client_family.email', '=', 'users.email')
        ->get();

        $docs = DocumentRequest::select('*')
                ->where('case_id', $requested_doc->case_id)
                ->where('family_id', $requested_doc->family_id)
                ->get();
        // pre($requested_doc);
        // die();
        return view('admin.adminuser.userclient.uploadclientdocument', compact('client', 'requested_doc', 'CaseTypes', 'family_alllist', 'case', 'docs'));
    }

    public function upload_req_doc(Request $request) {
        // pre($request->all());
        // die();
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
                // DocumentRequest::where('id', $request->did)->update(['document' => json_encode($client_file), 'status' => 1]);
            }

            $record = AdminTask::where('case_id', $request->case_id)->where('task_type', 'Assign_Case')->first();

            $msg = 'Document uploaded successfully!';
            if(!empty($record)) {
                $touser = User::where('id', Auth::User()->id)->first();
                $n_link = url('admin/usertask/documents').'/'.$record->id;
                $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
                Notification::send($touser, new DatabaseNotification($message));
            }
            
            $touser = User::where('id',$record->firm_admin_id)->first();
            $n_link = url('firm/case/case_documents').'/'.$request->case_id;
            $message = collect(['title' => 'Firm Admin Document upload', 'body' => $msg ,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link ]);
            $FirmCase = FirmCase::select('users.*')
                            ->join('users', 'users.id', '=', 'case.user_id')
                            ->where('case.id', $request->case_id)
                            ->first();
            $usercase = User::where('id', $FirmCase->id)->first();
            
            // Notification::send($usercase, new DatabaseNotification($message));
            
            Notification::send($touser, new DatabaseNotification($message));
        }
        return redirect('admin/userclient/clientdocument/'.$request->family_id)->withInfo('Document uploaded successfully!');
    }

}
