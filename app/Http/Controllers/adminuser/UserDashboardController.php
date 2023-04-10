<?php

namespace App\Http\Controllers\adminuser;

use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\AdminTask;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\DocumentRequest;
use App\Models\CalendarSetting;
use App\User;
use Mail;
use App\Notifications\DatabaseNotification;
use Notification;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        require_once(base_path('public/calenderApi/settings.php'));
    }

	public function index(Request $request)
	{
		$currunt_user = Auth::User()->id;
        $count['open_case'] = $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        ->where('admintask.task_type', 'Assign_Case')
        ->whereIn('case.status',array(1,2,3,4,5,7))
        ->where('admintask.status', 1)
        ->count();
        $count['working_case'] = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
        ->where('admintask.task_type', 'Assign_Case')
        ->whereIn('case.status',array(1,2,3,4,5,7))
        ->where('admintask.status', 1)
        ->count();
        $count['in_review'] = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
        ->where('admintask.task_type', 'Assign_Case')
        ->whereIn('case.status',array(6))
        ->where('admintask.status', 1)
        ->count();
        $count['total_case'] = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
        ->where('admintask.task_type', 'Assign_Case')
        ->where('admintask.status', 1)
        ->count();
        $count['completed'] = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at')
        ->join('case', 'admintask.case_id', '=', 'case.id')
        ->join('firms', 'case.firm_id', '=', 'firms.id')
        ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
        ->where('admintask.allot_user_id',Auth::User()->id)
        // ->whereNotIn('admintask.task_type', ['provide_a_quote', 'Assign_Case', 'upload_translated_document', 'schedule_training'])
        ->where('admintask.task_type', 'Assign_Case')
        ->whereIn('case.status',array(9))
        ->where('admintask.status', 1)
        ->count();
        $due_date = date('m/d/Y');
        $today_task = AdminTask::select('admintask.*', 'users.name as clientname')
                    ->where('admintask.allot_user_id',Auth::User()->id)
                    ->where('admintask.due_date', $due_date)
                    ->leftJoin('users', 'users.id', '=', 'admintask.client_task')
                    ->orderBy('admintask.id','DESC')
                    ->limit(10)
                    ->get();
        foreach ($today_task as $key => $value) {
            $today_task[$key]->status = ($value->status == 0) ? "Open" : "Completed";
            switch ($value->priority) {
                case 1:
                    $result = "Urgent";
                    break;
                case 2:
                    $result = "High";
                    break;
                case 3:
                    $result = "Medium";
                    break;
                case 4:
                    $result = "Low";
                    break;
                default:
                    $result = "Normal";
            }
            $today_task[$key]->priority = $result;

            $today_task[$key]->clink = '#';
            if(empty($value->clientname)) {
                $today_task[$key]->clientname = 'N/A';
            }
            else {
                $today_task[$key]->clink = url('admin/userclient/clientcases/'.$value->client_task);
            }
        }
		return view('admin.adminuser.dashboard.index',compact('count', 'today_task'));
	}

        public function leave_application() {
                $currunt_user = Auth::User();
                return view('admin.adminuser.dashboard.leave_application');
        }

        public function send_leave_application(Request $request) {
                $currunt_user = Auth::User();
                $task_data = [
                        'firm_admin_id' => $currunt_user->id,
                        'task_type' => 'Leave_Application',
                        'task' => $request->description,
                        'case_id' => $currunt_user->id,
                        'allot_user_id' => $currunt_user->id,
                        // 'priority' => 'Normal',
                        'status' => 0
                ];
                $task = AdminTask::create($task_data);
                $dates = explode(' - ', $request->date);
                $lead_event_data = [
                        'title' => "Leave_Application",
                        'event_type' => '',
                        'event_title' => 'Vacation Request',
                        'related_id' => $task->id,
                        's_date' => date('Y-m-d', strtotime($dates[0])),
                        's_time' => date('h:i A', strtotime($dates[0])),
                        'e_date' => date('Y-m-d', strtotime($dates[1])),
                        'e_time' => date('h:i A', strtotime($dates[1])),
                        'attorney' => Auth::User()->id
                ];

                $event = Event::create($lead_event_data);
                $remove = array(
                    'time' => date('h:i A', strtotime($dates[0])),
                    'date' => date('Y-m-d', strtotime($dates[0])),
                    'titleofevent' => $request->event_title
                );
                $email = EmailTemplate(12, $remove);
                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => Eadmin(),
                    'subject' => 'Vacation Request',
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                send_mail($args);
                return redirect('admin/leave_application')->withInfo('Vacation Request send successfully!');
        }
        public function usercalendar() {
            $currunt_user = Auth::User();
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $provider = new Google([
                'clientId'     => CLIENT_ID,
                'clientSecret' => CLIENT_SECRET,
                'redirectUri'  => url('admin/usercalendar'),
                'accessType'   => 'offline',
                'hostedDomain' => "",
            ]);

            if (!empty($_GET['error'])) {

            } elseif (empty($_GET['code'])) {


            } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                    // State is invalid, possible CSRF attack in progress
                unset($_SESSION['oauth2state']);

            } else {

                    // Try to get an access token (using the authorization code grant)
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                update_user_meta($currunt_user->id, 'access_token', $token->getToken());
                update_user_meta($currunt_user->id, 'refresh_token', $token->getRefreshToken());
                return redirect(CalenderRedirect())->with('success', 'Synced with Google Calendar');
                    //exit();
            }

            $authUrl = $provider->getAuthorizationUrl([
                'scope' => [
                    'https://www.googleapis.com/auth/calendar'
                ],
                'prompt' => 'consent'
            ]);
            
            $_SESSION['oauth2state'] = $provider->getState();


            $admintask = AdminTask::select('admintask.firm_admin_id','users.name as fa_name','admintask.allot_user_id','case.case_type','admintask.case_id', 'u1.name as clientname', 'nc.id as clientid', 'case.firm_id')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->leftJoin('new_client as nc', 'nc.user_id', '=', 'case.client_id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->where('admintask.task_type', 'Required_Document_Request')
            ->get(); 

            $firmadmins_ids = array();
            $firmcases_ids = array();
            $firmclients_ids = array();
            $firmadmins = array();
            $firmcases = array();
            $firmclients = array();
            if(!empty($admintask)) {
                foreach ($admintask as $k => $v) {
                    if(!in_array($v->firm_admin_id, $firmadmins_ids)) {
                        $firmadmins_ids[] = $v->firm_admin_id;
                        $firmadmins[] = array(
                                        'id' => $v->firm_admin_id,
                                        'name' => $v->fa_name,
                                        'firm_id' => $v->firm_id
                                        );
                    }
                    if(!in_array($v->case_id, $firmcases_ids)) {
                        $firmcases_ids[] = $v->case_id;
                        $firmcases[] = array(
                                        'id' => $v->case_id,
                                        'case_type' => $v->case_type,
                                        'firm_id' => $v->firm_id
                                        );
                    }
                    if(!in_array($v->clientid, $firmclients_ids) && !empty($v->clientname)) {
                        $firmclients_ids[] = $v->clientid;
                        $firmclients[] = array(
                                        'id' => $v->clientid,
                                        'name' => $v->clientname,
                                        'firm_id' => $v->firm_id
                                        );
                    }
                }
            }

            $events = array();
            $arr = Event::select('event.*', 'admintask.allot_user_id as allot_user_id', 'calendar_setting.value')
            ->leftJoin('admintask','admintask.id', '=', 'event.related_id')
            ->leftJoin('calendar_setting','calendar_setting.key', '=', 'admintask.allot_user_id')
            ->where('event.who_consult_with', 'REGEXP', $currunt_user->id)
            ->where('event.title',"ADMIN")
            ->get();
            $lead_color = '#91476a';
            if($arr) {
                foreach ($arr as $key => $e) {
                    $s_time  = date("H:i:s", strtotime($e->s_time));
                    $e_time  = date("H:i:s", strtotime($e->e_time));
                    if($e->event_title == 'Vacation Request') {
                        $e->event_title = $e->event_title.' - '.$e->username;
                    }
                    if(empty($e->value)) {
                        $e->value = $lead_color;
                    }
                    if(!empty($e->event_description)) {
                                // $e->event_title .= "".$e->event_description; 
                    }
                    $events[] = array(
                        'event_id' => $e->id,
                        'title' => $e->event_title, 
                        'start' => $e->s_date.'T'.$s_time,
                        'end' => $e->e_date.'T'.$e_time, 
                        'color' => $e->value,
                        'description' => $e->event_description,
                        'who_consult_with' => json_decode($e->who_consult_with),
                    );
                }
            }

            $googleids = array();

            $arrc = Event::select('event.*', 'case.*', 'calendar_setting.value', 'event.id as e_id', 'users.name as uname')
                ->join('case','case.id', 'event.related_id')
                ->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'case.user_id')
                ->leftJoin('users', 'users.id', '=', 'case.client_id')
                ->where('event.attorney',$currunt_user->id)
                ->where('event.title',"CASE")
                ->get();
            if($arrc) {
                foreach ($arrc as $key => $e) {
                    $s_time  = date("H:i:s", strtotime($e->s_time));
                    $e_time  = date("H:i:s", strtotime($e->e_time));
                    $e->s_date = date("Y-m-d", strtotime($e->s_date));
                    $e->e_date = date("Y-m-d", strtotime($e->e_date));
                    $ln = $e->name.' '.$e->last_name;
                    $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->uname;
                    if(empty($e->value)) {
                        $e->value = $lead_color;
                    }
                    $eedate = '';
                    if(!empty($e->e_date)) {
                        $eedate = date('m/d/Y', strtotime($e->e_date));
                    }
                    $events[] = array(
                                    'title' => $etitle, 
                                    'act_title' => $e->event_title,
                                    'start' => $e->s_date.'T'.$s_time,
                                    'end' => $e->e_date.'T'.$e_time, 
                                    'event_end' => $eedate.' '.$e->e_time,
                                    'color' => $e->value,
                                    'event_id' => $e->e_id,
                                    'related_id' => $e->related_id,
                                    'event_type' => $e->event_type,
                                    'description' => $e->event_description,
                                    'who_consult_with' => json_decode($e->who_consult_with),
                                );
                    if(!empty($e->google_id)) {
                        $googleids[] = $e->google_id;
                    }
                }
            }

            $arrc1 = Event::select('event.*', 'new_client.*', 'event.id as e_id')
                    ->join('new_client','new_client.id', 'event.related_id')
                    ->where('event.attorney',$currunt_user->id)
                    ->where('event.title',"CLIENT")
                    ->get();

            if($arrc1) {
                foreach ($arrc1 as $key => $e) {
                    $s_time  = date("H:i:s", strtotime($e->s_time));
                    $e_time  = date("H:i:s", strtotime($e->e_time));
                    $e->s_date = date("Y-m-d", strtotime($e->s_date));
                    $e->e_date = date("Y-m-d", strtotime($e->e_date));
                    $who_consult_with = json_decode($e->who_consult_with);
                    $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->first_name.' '.$e->middle_name.' '.$e->last_name;
                    $eedate = '';
                    if(!empty($e->e_date)) {
                        $eedate = date('m/d/Y', strtotime($e->e_date));
                    }
                    if($who_consult_with) {
                        foreach ($who_consult_with as $key => $u) {
                            $se = CalendarSetting::where('user_id', Auth::User()->id)->where('key', $u)->first();
                            if(!empty($se)) {
                                $events[] = array(
                                    'title' => $etitle, 
                                    'act_title' => $e->event_title,
                                    'start' => $e->s_date.'T'.$s_time,
                                    'end' => $e->e_date.'T'.$e_time, 
                                    'event_end' => $eedate.' '.$e->e_time,
                                    'color' => $se->value,
                                    'event_id' => $e->e_id,
                                    'related_id' => $e->related_id,
                                    'event_type' => $e->event_type,
                                    'description' => $e->event_description,
                                    'who_consult_with' => json_decode($e->who_consult_with),
                                );
                            }
                            else {
                                $events[] = array(
                                    'title' => $etitle, 
                                    'act_title' => $e->event_title,
                                    'start' => $e->s_date.'T'.$s_time,
                                    'end' => $e->e_date.'T'.$e_time,
                                    'event_end' => $eedate.' '.$e->e_time, 
                                    'color' => $lead_color,
                                    'event_id' => $e->e_id,
                                    'related_id' => $e->related_id,
                                    'event_type' => $e->event_type,
                                    'description' => $e->event_description,
                                    'who_consult_with' => json_decode($e->who_consult_with),
                                );
                            }
                        }
                    }
                    else {
                        $events[] = array(
                                        'title' => $etitle, 
                                        'act_title' => $e->event_title,
                                        'start' => $e->s_date.'T'.$s_time,
                                        'end' => $e->e_date.'T'.$e_time, 
                                        'event_end' => $eedate.' '.$e->e_time,
                                        'color' => $lead_color,
                                        'event_id' => $e->e_id,
                                        'related_id' => $e->related_id,
                                        'event_type' => $e->event_type,
                                        'description' => $e->event_description,
                                        'who_consult_with' => json_decode($e->who_consult_with),
                                    );
                    }
                    if(!empty($e->google_id)) {
                        $googleids[] = $e->google_id;
                    }
                }
            }

            $arrn = Event::select('*')
                    ->where('event.attorney',$currunt_user->id)
                    ->where('title',"NONE")
                    ->get();
            
            if($arrn) {
                foreach ($arrn as $key => $e) {
                    $s_time  = date("H:i:s", strtotime($e->s_time));
                    $e_time  = date("H:i:s", strtotime($e->e_time));
                    $e->s_date = date("Y-m-d", strtotime($e->s_date));
                    $e->e_date = date("Y-m-d", strtotime($e->e_date));
                    $who_consult_with = json_decode($e->who_consult_with);
                    $etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title;
                    $eedate = '';
                    if(!empty($e->e_date)) {
                        $eedate = date('m/d/Y', strtotime($e->e_date));
                    }
                    if($who_consult_with) {
                        foreach ($who_consult_with as $key => $u) {
                            $se = CalendarSetting::where('user_id', Auth::User()->id)->where('key', $u)->first();
                            
                            if(!empty($se)) {
                                $events[] = array(
                                    'title' => $etitle, 
                                    'act_title' => $e->event_title,
                                    'start' => $e->s_date.'T'.$s_time,
                                    'end' => $e->e_date.'T'.$e_time, 
                                    'event_end' => $eedate.' '.$e->e_time,
                                    'color' => $se->value,
                                    'event_id' => $e->id,
                                    'related_id' => $e->related_id,
                                    'event_type' => $e->event_type,
                                    'description' => $e->event_description,
                                    'who_consult_with' => json_decode($e->who_consult_with),
                                );
                            }
                            else {
                                $events[] = array(
                                    'title' => $etitle, 
                                    'act_title' => $e->event_title,
                                    'start' => $e->s_date.'T'.$s_time,
                                    'end' => $e->e_date.'T'.$e_time, 
                                    'event_end' => $eedate.' '.$e->e_time,
                                    'color' => $lead_color,
                                    'event_id' => $e->id,
                                    'related_id' => $e->related_id,
                                    'event_type' => $e->event_type,
                                    'description' => $e->event_description,
                                    'who_consult_with' => json_decode($e->who_consult_with),
                                );
                            }
                            
                        }
                    }
                    else {
                        $events[] = array(
                                        'title' => $etitle, 
                                        'act_title' => $e->event_title,
                                        'start' => $e->s_date.'T'.$s_time,
                                        'end' => $e->e_date.'T'.$e_time, 
                                        'event_end' => $eedate.' '.$e->e_time,
                                        'color' => $lead_color,
                                        'event_id' => $e->id,
                                        'related_id' => $e->related_id,
                                        'event_type' => $e->event_type,
                                        'description' => $e->event_description,
                                        'who_consult_with' => json_decode($e->who_consult_with),
                                    );
                    }
                }
                if(!empty($e->google_id)) {
                    $googleids[] = $e->google_id;
                }
            }

            $access_token = get_user_meta(Auth::User()->id, 'access_token');
            if(!empty($access_token)) {
                $elist = GetCalendarsList($access_token);
                if($elist == 0) {
                    $refreshToken = get_user_meta(Auth::User()->id, 'refresh_token');
                    $grant = new RefreshToken();
                    $access_token = $provider->getAccessToken($grant, ['refresh_token' => $refreshToken]);
                    update_user_meta(Auth::User()->id, 'access_token', $access_token);
                }
                
                if(!empty($elist)) {
                    foreach ($elist as $k => $e) {
                        if(!in_array($e['id'], $googleids)) {
                            $start1 = '';
                            $end1 = '';

                            $startt = '';
                            $endt = '';
                            $etitle = '';
                            if(array_key_exists('date', $e['start'])) {
                                $start1 = $e['start']['date'];
                                $end1 = $e['end']['date'];

                                $startt = date("h:i A", strtotime($start1));
                                $endt = date("h:i A", strtotime($end1));
                                $etitle = $startt.'-'.$endt.', '.$e['summary'];
                            }
                            else if(array_key_exists('dateTime', $e['start'])) {
                                $start1 = $e['start']['dateTime'];
                                $end1 = $e['end']['dateTime'];
                                $startt = date("h:i A", strtotime($start1));
                                $endt = date("h:i A", strtotime($end1));
                                $etitle = $startt.'-'.$endt.', '.$e['summary'];
                            }
                            $events[] = array(
                                        'title' => $etitle, 
                                        'start' => $start1,
                                        'end' => $end1, 
                                        'color' => $lead_color,
                                    );
                        }
                    }
                }
            }

            $events = json_encode($events);
            CalenderRedirectSessionSave();
            return view('admin.adminuser.dashboard.usercalendar', compact('events', 'authUrl', 'access_token', 'firmadmins', 'firmcases', 'firmclients'));
        }

        public function createuserevent(Request $request) {
            $res = array();
            $reminder_arr = array();
            $reminders = array(
                    'useDefault' => FALSE,
                    'overrides' => array()
                  );
            foreach ($request->event_reminder as $key => $value) {
                $rname = $value['name'];
                $rval  = $value['value'];
                if($rname == 'event_reminder[count][]') {
                    $reminder_arr['count'][] = $rval;
                }
                if($rname == 'event_reminder[type][]') {
                    $reminder_arr['type'][] = $rval;
                }
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
            if ($validator->fails()) {
                $res['status'] = false;
                $res['msg'] = $validator->errors()->first();
                echo json_encode($res);
                die();
            }

            $s_date = $request->s_date;
            $e_date = $request->e_date;
            if ($request->event_type == 'Reminder') {
                $lead_event_data = [
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
            if($request->event_id) {
                $event = Event::where('id', $request->event_id)->update($lead_event_data);
                $event_id1 = $request->event_id;
            }
            else {
                $data = Auth::User();
                $lead_event_data['title'] = $request->related_to;
                if($request->related_to == 'NONE') {
                    $lead_event_data['related_id'] = $data->firm_id;
                }
                $event = Event::create($lead_event_data);
                $event_id1 = $event->id;
                $remove = array(
                    'time' => date('h:i A', strtotime($s_date)),
                    'date' => date('Y-m-d', strtotime($s_date)),
                    'titleofevent' => $request->event_title
                );
                $email = EmailTemplate(12, $remove);
                if(!empty($request->who_consult_with)) {
                    $u = User::select('*')->where('id', $request->who_consult_with)->first();
                    $args = array(
                        'bodyMessage' => $email['MSG'],
                        'to' => $u->email,
                        'subject' => $email['Subject'],
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                }
            }

            $res['status'] = true;
            $res['msg'] = 'Event created successfully!';
            echo json_encode($res);
            $access_token = get_user_meta(Auth::User()->id, 'access_token');
            if (!empty($access_token)) {
                $user_timezone = GetUserCalendarTimezone($access_token);
                $time_in_24_hour_format = date("H:i:s", strtotime($request->time));
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
            die();
        }

        public function new_assignments() {
            return view('admin.adminuser.dashboard.new_assignments');
        }

        public function getNewAssignments(Request $request) { 
        if(!empty($request->status)) {
            $st = $request->status;
            if($request->status == 2) {
                $st = 0;
            }
            
            $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at', 'u1.name as clientname', 'admintask.created_at as created_date', 'admintask.updated_at as assigned_date', 'admintask.status as astatus')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->where('admintask.task_type', 'Assign_Case')
            ->where('admintask.status',$st)
            ->get(); 
        }
        else {
            $admintask = AdminTask::select('admintask.*', 'admintask.id as tid','firms.firm_name','case.*','case.status as case_status', 'case.created_at as ccreated_at', 'u1.name as clientname', 'admintask.created_at as created_date', 'admintask.updated_at as assigned_date', 'admintask.status as astatus')
            ->join('case', 'admintask.case_id', '=', 'case.id')
            ->join('firms', 'case.firm_id', '=', 'firms.id')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->leftJoin('users as u1', 'u1.id', '=', 'case.client_id')
            ->where('admintask.allot_user_id',Auth::User()->id)
            ->where('admintask.task_type', 'Assign_Case')
            ->get(); 
        }
        foreach ($admintask as $key => $value) {
            $admintask[$key]->stat = GetCaseStatus($value->case_status);
            $admintask[$key]->is_edit = false;
            $admintask[$key]->stat = '';
            if($value->astatus == 0) {
                $admintask[$key]->is_edit = true;
                $admintask[$key]->stat = 'Pending';
            }
            else if($value->astatus == 1) {
                $admintask[$key]->stat = 'Accepted';
            }
            else if($value->astatus == -1) {
                $admintask[$key]->stat = 'Denied';
            }
        }

        return datatables()->of($admintask)->toJson();        
    }

    public function accept_assignment($id) {
        $data = [
            'status' => 1
        ];
        AdminTask::where('id', $id)->update($data);
        $record = AdminTask::select('*')->where('id', $id)->first();
        $data3 = [
            'firm_admin_id' => $record->firm_admin_id,
            'task_type' => 'Required_Document_Request',
            'task' => 'Required Document Request',
            'case_id' => $record->case_id,
            'allot_user_id' => $record->allot_user_id,
            'priority' => $record->priority,
            'status' => 0
        ];
        $admintask = AdminTask::create($data3);

        $msg = 'Required Document Request for case #'.$record->case_id;
        // $msg = 'You have been assigned a case #'.$record->case_id;
        $touser = User::where('id', $record->allot_user_id)->first();
        $n_link = url('admin/usertask/documents/'.$admintask->id);
        $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>1,'fromName'=>'TILA Admin', 'link'=>$n_link]);
        
        Notification::send($touser, new DatabaseNotification($message));

        $msg = 'Case accepted successfully. Happy work day!';
        $touser = User::where('id', $record->firm_admin_id)->first();
        $n_link = url('firm/case/show').'/'.$record->case_id;
        $message = collect(['title' => 'Assign a case', 'body' => $msg,'type'=>'5','from'=>Auth::User()->id,'fromName'=>Auth::User()->name, 'link'=>$n_link]);
        Notification::send($touser, new DatabaseNotification($message));
        return redirect('admin/new_assignments')->with('success', 'Case accepted successfully. Happy work day!');
    }
    public function denied_assignment($id) {
        $data = [
            'status' => -1
        ];
        AdminTask::where('id', $id)->update($data);

        $admintask = AdminTask::select('admintask.*','firms.firm_name','firms.account_type', 'firms.email')
            ->join('users', 'admintask.firm_admin_id', '=', 'users.id')
            ->join('firms', 'users.firm_id', '=', 'firms.id')
            ->where('admintask.id',$id)
            ->first();
        $case_id = $admintask->case_id;
        if($admintask->task_type == 'upload_translated_document' || $admintask->task_type == 'provide_a_quote') {
            $docs = DocumentRequest::select('*')->where('id', $case_id)->first();
            $case_id = $docs->case_id;
        }
        

        $firm_admin = User::select('*')->where('id', $admintask->firm_admin_id)->first();

        $firm = Firm::select('*')->where('id', $firm_admin->firm_id)->first();

        $firmcase = FirmCase::select('*')->where('id', $case_id)->first();
        $ClientName = '';
        if(!empty($firmcase->client_id)) {
            $cll = User::select('*')->where('id', $firmcase->client_id)->first();
            $ClientName = $cll->name;
        }

        $TILAAdmin = User::select('*')->where('id', 1)->first();

        $remove = array(
            'TILAAdmin' => $TILAAdmin->name,
            'TILAVP' => Auth::User()->name,
            'FirmName' => $firm->firm_name,
            'ClientName' => $ClientName,
            'CaseType' => $firmcase->case_category,
        );
        $email = EmailTemplate(42, $remove);

        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => $TILAAdmin->email,
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        return redirect('admin/new_assignments')->with('success', 'Denied successfully');
    }
}