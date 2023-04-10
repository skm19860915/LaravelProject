<?php

function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {
    $url = 'https://accounts.google.com/o/oauth2/token';

    // $url = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&access_type=offline&client_id='. $client_id;
    
    $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code=' . $code . '&grant_type=authorization_code';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code != 200)
        throw new Exception('Error : Failed to receieve access token');

    return $data;
}

function GetUserCalendarTimezone($access_token) {
    $url_settings = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_settings);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code != 200)
    //throw new Exception('Error : Failed to get timezone');
        return 'Asia/Kolkata';

    return $data['value'];
}

function GetCalendarsList($access_token) {
    $url_parameters = array();

    $url_parameters['fields'] = 'items(id,summary,timeZone,description)';
    $url_parameters['minAccessRole'] = 'owner';

    $url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?' . http_build_query($url_parameters);

    $url_calendars = 'https://www.googleapis.com/calendar/v3/calendars/primary/events';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_calendars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $data = json_decode(curl_exec($ch), true); 
    // echo '<pre>';print_r($data);echo '</pre>';
    // die();
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code != 200)
        return 0;
        //throw new Exception('Error : Failed to get calendars list');

    return $data['items'];
}

function CreateCalendarEvent($calendar_id, $summary, $all_day, $event_time, $event_timezone, $access_token, $reminders = false) {
    $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events';

    $curlPost = array('summary' => $summary);
    if ($all_day == 1) {
        $curlPost['start'] = array('date' => $event_time['event_date']);
        $curlPost['end'] = array('date' => $event_time['event_date']);
    } else {
        $curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
        $curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
    }
    if($reminders) {
        $curlPost['reminders'] = $reminders;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_events);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
    $data = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code != 200)
    //throw new Exception('Error : Failed to create event');
        return '';

    return $data['id'];
}

if (!function_exists('CalenderRedirect')) {

    function CalenderRedirect() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        $url=$_SESSION['SaveURLDATA'];
        return $url;
    }

}

if (!function_exists('CalenderRedirectSessionSave')) {
    function CalenderRedirectSessionSave() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['SaveURLDATA']=url()->current();       
    }
}