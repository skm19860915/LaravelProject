<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\{UserUpdateRequest,UserAddRequest};
use Spatie\Permission\Models\Role;
use App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Firm;
use App\Models\FirmCase;
use App\Models\Log;
use App\Models\Event;
use App\Models\AdminTask;
use App\Models\CalendarSetting;

class CalendarController extends Controller
{
    private $access_token;
    public function __construct()
	{
		require_once(base_path('public/calenderApi/settings.php'));
        $this->access_token = '';
	}

	public function index(Request $request)
	{
        if (isset($_GET['code'])) {
            try {
                $data = GetAccessToken(CLIENT_ID, url('admin/calendar'), CLIENT_SECRET, $_GET['code']);
                $request->session()->put('access_token', $data['access_token']);
                return redirect('admin/calendar');
                exit();
            } catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }
		$data = Auth::User();
		$arr = Event::select('event.*', 'admintask.allot_user_id as allot_user_id', 'calendar_setting.value')
				->leftJoin('admintask','admintask.id', '=', 'event.related_id')
                ->leftJoin('calendar_setting','calendar_setting.key', '=', 'admintask.allot_user_id')
				// ->where('lead.firm_id',$data->firm_id)
				->where('event.title',"ADMIN")
				->get();       
		$events = array();
		$lead_color = '#91476a';
        $googleids = array();
		if($arr) {
			foreach ($arr as $key => $e) {
				$s_time  = date("H:i:s", strtotime($e->s_time));
				$e_time  = date("H:i:s", strtotime($e->e_time));
				$etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title;
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
								'title' => $etitle, 
                                'act_title' => $e->event_title,
								'start' => $e->s_date.'T'.$s_time,
								'end' => $e->e_date.'T'.$e_time, 
                                'event_end' => $e->e_date.' '.$e->e_time,
								'color' => $e->value,
                                'description' => $e->event_description,
                                'who_consult_with' => json_decode($e->who_consult_with),
							);
			    if(!empty($e->google_id)) {
                    $googleids[] = $e->google_id;
                }
            }
		}
		
		$access_token = $request->session()->get('access_token');

        $access_token = $request->session()->get('access_token');
        if(!empty($access_token)) {
            $elist = GetCalendarsList($access_token);
            if($elist == 0) {
                $request->session()->forget('access_token');
                $access_token = false;
            }
            // pre($elist);
            // die();
            if(!empty($elist)) {
                foreach ($elist as $k => $e) {
                    if(!in_array($e['id'], $googleids)) {
                        $start1 = '';
                        $end1 = '';

                        $startt = '';
                        $endt = '';

                        if(array_key_exists('date', $e['start'])) {
                            $start1 = $e['start']['date'];
                            $end1 = $e['end']['date'];
                        }
                        else if(array_key_exists('dateTime', $e['start'])) {
                            $start1 = $e['start']['dateTime'];
                            $end1 = $e['end']['dateTime'];
                            $startt = date("H:i A", strtotime($start1));
                            $endt = date("H:i A", strtotime($end1));
                            $e['summary'] = $startt.'-'.$endt.', '.$e['summary'];
                        }
                        $events[] = array(
                                    'title' => $e['summary'], 
                                    'start' => $start1,
                                    'end' => $end1, 
                                    'color' => $lead_color,
                                );
                    }
                }
            }
        }
        $events = json_encode($events);
        $users = User::select('*')
                ->whereIn('role_id', [1, 2])
                ->get();
		return view('admin.calendar.index',compact('events', 'access_token', 'users'));
	}
    public function calendarsetting(Request $request)
    {
        $data = Auth::User();
        $users = User::select('users.id', 'users.name', 'calendar_setting.value')
                ->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'users.id')
                ->where('users.role_id', 2)
                ->get();
        $settings = CalendarSetting::where('user_id', Auth::User()->id)->get();
        return view('admin.calendar.calendarsetting',compact( 'settings', 'users'));
    }
    public function update_calendarsetting(Request $request) {
        $id = Auth::User()->id;
        foreach ($request->setting as $k => $v) {
            $res = CalendarSetting::where('user_id', $id)->where('key', $k)->count();
            if ($res) {
                $data = [
                    'value' => $v
                ];
                CalendarSetting::where('user_id', $id)->where('key', $k)->update($data);
            } else {
                $data = [
                    'user_id' => $id,
                    'key' => $k,
                    'value' => $v
                ];
                CalendarSetting::create($data);
            }
        }
        $result = array();
        $result['status'] = true;
        echo json_encode($result);
    }
	public function create_admin_event(Request $request) {
        $res = array();
        $validator = Validator::make($request->all(), [
                    's_date' => 'required',
                    'e_date' => 'required',
                    'event_title' => 'required',
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
        $lead_event_data = [
        	'title' => "ADMIN",
        	'event_type' => '',
        	'event_title' => $request->event_title,
            'event_description' => $request->event_description,
        	'related_id' => 1,
            'who_consult_with' => json_encode($request->who_consult_with),
        	's_date' => date('Y-m-d', strtotime($s_date)),
            's_time' => date('h:i A', strtotime($s_date)),
            'e_date' => date('Y-m-d', strtotime($e_date)),
            'e_time' => date('h:i A', strtotime($e_date)),
        	'attorney' => Auth::User()->id
        ];
        $res['status'] = true;
        if($request->event_id) {
            $res['msg'] = 'Event updated successfully!';
            $event = Event::where('id', $request->event_id)->update($lead_event_data);
        }
        else {
            $res['msg'] = 'Event created successfully!';
            $event = Event::create($lead_event_data);

            $remove = array(
                'time' => date('h:i A', strtotime($request->s_date)),
                'date' => date('Y-m-d', strtotime($request->s_date)),
                'titleofevent' => $request->event_title
            );
            $email = EmailTemplate(12, $remove);
            if(!empty($request->who_consult_with)) {
                foreach ($request->who_consult_with as $k => $v) {
                    $u = User::select('*')->where('id', $v)->first();
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
            
        }
        //$event = Event::create($lead_event_data);

        
        
        echo json_encode($res);
        $access_token = $request->session()->get('access_token');
        if (!empty($access_token)) {
            $user_timezone = GetUserCalendarTimezone($access_token);
            $etime = array();
            $etime['start_time'] = date('Y-m-d', strtotime($request->s_date)) . 'T' . date('H:i:s', strtotime($request->s_date));
            $etime['end_time'] = date('Y-m-d', strtotime($request->e_date)) . 'T' . date('H:i:s', strtotime($request->e_date));
            $gid = CreateCalendarEvent('primary', $request->event_title, 0, $etime, $user_timezone, $access_token);
            Event::where('id', $event->id)->update(['google_id' => $gid]);
        }        
        die();
    }
}