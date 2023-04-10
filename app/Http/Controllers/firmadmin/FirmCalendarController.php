<?php

namespace App\Http\Controllers\firmadmin;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Models\CalendarSetting;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Firm;
use App\Models\Esubscription;
use App\Models\Event;
use App\Models\Newclient;
use App\Models\FirmCase;
use Carbon\Carbon;
use App\Notifications\DatabaseNotification;
use Notification;
use App;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Grant\RefreshToken;

class FirmCalendarController extends Controller
{
	private $access_token;
	public function __construct()
	{
		$this->access_token = '';
		require_once(base_path('public/calenderApi/settings.php'));
	}


	public function index(Request $request)
	{	
		$data = Auth::User();
		$firm = Firm::select('*')
        ->where('id',$data->firm_id)
        ->first();
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

            update_user_meta($data->id, 'access_token', $token->getToken());
            update_user_meta($data->id, 'refresh_token', $token->getRefreshToken());
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

		$users = User::select('users.id', 'users.name', 'calendar_setting.value')
				->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'users.id')
                ->where('users.firm_id', $data->firm_id)
                ->where('users.role_id', 5)
                ->orWhere('users.id', $data->id)
                ->get();
		$lead_color = '#91476a';
		$arr = Event::select('event.*', 'lead.*', 'event.id as e_id')
				->join('lead','lead.id', 'event.related_id')
				->where('lead.firm_id',$data->firm_id)
				->where('event.title',"LEAD")
				->get();
		$events = array();
		$googleids = array();
		if($arr) {
			foreach ($arr as $key => $e) {
				$s_time  = date("H:i:s", strtotime($e->s_time));
				$e_time  = date("H:i:s", strtotime($e->e_time));
				$e->s_date = date("Y-m-d", strtotime($e->s_date));
				$e->e_date = date("Y-m-d", strtotime($e->e_date));
				$who_consult_with = json_decode($e->who_consult_with);
				$etitle = $e->s_time.'-'.$e->e_time.', '.$e->event_title.', '.$e->name.' '.$e->last_name;
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
		$arrc = Event::select('event.*', 'case.*', 'calendar_setting.value', 'event.id as e_id', 'users.name as uname')
				->join('case','case.id', 'event.related_id')
				->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'case.user_id')
				->leftJoin('users', 'users.id', '=', 'case.client_id')
				->where('case.firm_id',$data->firm_id)
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
				->where('new_client.firm_id',$data->firm_id)
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
				->where('related_id',$data->firm_id)
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
			// pre($elist);
			// die();
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
		$firmlead1 = Lead::where('firm_id', $data->firm_id)->get();
		$firmlead = array();
		foreach ($firmlead1 as $key => $value) {
			$arre = Event::select('*')
				->where('related_id',$value->id)
				->where('title',"LEAD")
				->first();
				if(empty($arre)) {
					$firmlead[] = $value;
				}
		}
		$firmcase = FirmCase::where('firm_id', $data->firm_id)->get();
		$firmclient = Newclient::where('firm_id', $data->firm_id)->get();
		CalenderRedirectSessionSave();

		require_once(base_path('vendor/stripe/stripe-php/init.php'));
        \Stripe\Stripe::setApiKey(env('SRTIPE_SECRET_KEY'));
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
        $users1 = User::select('users.*','roles.name as role_name')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('firm_id',$data->firm_id)
        ->whereIn('role_id', ['4', '5'])
        ->get();
		return view('firmadmin.calendar.index',compact('events', 'access_token', 'settings', 'users', 'firmcase', 'firmclient', 'firmlead', 'authUrl', 'card', 'firm', 'data', 'users1'));
	}
	public function calendar_setting(Request $request)
	{
		$data = Auth::User();
		$users = User::select('users.id', 'users.name', 'calendar_setting.value')
				->leftJoin('calendar_setting', 'calendar_setting.key', '=', 'users.id')
                ->where('users.firm_id', $data->firm_id)
                ->where('users.role_id', 5)
                ->orWhere('users.id', $data->id)
                ->get();
		$settings = CalendarSetting::where('user_id', Auth::User()->id)->get();
		return view('firmadmin.calendar.calendar_setting',compact( 'settings', 'users'));
	}

	public function create_firm_event(Request $request) {

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
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_description' => $request->event_description,
                'related_id' => $request->lead_id,
                's_date' => date('Y-m-d', strtotime($s_date)),
                's_time' => date('h:i A', strtotime($s_date)),
                'e_date' => date('Y-m-d', strtotime($s_date)),
                'e_time' => date('h:i A', strtotime($s_date)),
                'who_consult_with' => json_encode($request->who_consult_with),
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
                'who_consult_with' => json_encode($request->who_consult_with),
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
        /* --------------------Notifications--------------- */
        // $firm_id = Auth::User()->firm_id;
        // $firm_name = Firm::select('*')->where('id', Auth::User()->firm_id)->first();
        // $n_link = url('firm/clientcase/show').'/'.$request->lead_id;
        // $msg = 'Firm ' . $firm_name->firm_name . ' case event created successfully!';
        // $firmcase = FirmCase::select('client_id')->where('id', $request->lead_id)->first();
        // $touser = User::where('id', $firmcase->client_id)->first();
        // $message = collect(['title' => 'Firm Admin case event created', 'body' => $msg, 'type' => '5', 'from' => Auth::User()->id, 'fromName' => Auth::User()->name, 'link'=>$n_link]);
        // if($firm_name->account_type == 'CMS') {
        //     Notification::send($touser, new DatabaseNotification($message));
        // }
            /* --------------------Notifications--------------- */


        $res['status'] = true;
        $res['msg'] = 'Case Event created successfully!';
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
        if ($event) {
            //return redirect('firm/lead')->withInfo('Lead Event created successfully!');
        } else {
            //return redirect('firm/create_event')->withInfo(' not created, please try again');
        }
    }
}