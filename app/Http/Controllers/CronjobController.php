<?php

namespace App\Http\Controllers;

use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Firm;
use App\Models\Lead;
use App\Models\Event;
use App\Models\Newclient;
use App\Models\FirmSetting;
use App\Models\TilaEmailTemplate;
use App\Models\Country;
use App\Models\LeadNotes;
use App\Models\Transaction;
use App\Models\CaseType;
use App\Models\ClientInformation;
use App\Notifications\DatabaseNotification;
use Notification;
use Carbon\Carbon;
use App;
use DB;

class CronjobController extends Controller {

    public function index() {
        // $val = array(
        //     "i-602.pdf",
        //     "i-612.pdf",
        //     "i-687.pdf",
        //     "i-690.pdf",
        //     "i-690sup1.pdf",
        //     "i-693.pdf",
        //     "i-694.pdf",
        //     "i-698.pdf",
        //     "i-730.pdf",
        //     "i-751.pdf",
        //     "i-765.pdf",
        //     "i-765v.pdf",
        //     "i-765ws.pdf",
        //     "i-800.pdf",
        //     "i-800a.pdf",
        //     "i-800asup1.pdf",
        //     "i-800asup2.pdf",
        //     "i-800asup3.pdf",
        //     "i-800sup1.pdf",
        //     "i-817.pdf",
        //     "i-821.pdf",
        //     "i-821d.pdf",
        //     "i-824.pdf",
        //     "i-829.pdf",
        //     "i-854a.pdf",
        //     "i-854b.pdf"
        // );
        // foreach ($val as $key => $v) {
        //     $file1 = 'forms/all/'.$v;
        //     $data = [
        //          'client_id' => 8,
        //          'case_id' => 3,
        //          'firm_id' => 2,
        //          'file' => $file1,
        //          'file_type' => $v   
        //     ];
        //     ClientInformation::create($data);
        // }

        // $filename = asset('storage/app/DEV Final - Pricing Master Sheet - Sheet1.csv');
        // $filename = asset('storage/app/DEV Final - Document Default Master Sheet - Sheet1.csv');
        $filename = asset('storage/app/DEV Final - Form Defaults Master Sheet - Sheet1.csv');
        $file = fopen($filename, 'r');
        $default_Doc = array();
        $default_Forms = array();
        while (($line = fgetcsv($file)) !== FALSE) {
            // Insert case category and case type 
            /* 
            if(!empty($line[0]) && !empty($line[1]) && $line[2] != 'Price') {
                $data = [
                    'Case_Category' => trim($line[0]),
                    'Case_Type' => trim($line[1]),
                    'VP_Pricing' => trim($line[2]),
                    'actual_cost' => trim($line[2])
                ];
                CaseType::create($data);
            }
            */

            // Insert case default document 
            /*
            if(!empty($line[0]) && !empty($line[1]) && !empty($line[2]) && $line[2] != 'Document ') {
                $k = trim($line[0]).'_'.trim($line[1]);
                $default_Doc[$k][] = $line[2];
            }
            */

            // Insert case default forms 
            /*
            if(!empty($line[0]) && !empty($line[1]) && !empty($line[2]) && $line[2] != 'Forms') {
                $k = trim($line[0]).'_'.trim($line[1]);
                $default_Forms[$k][] = $line[2];
            } */
            
        }

        // Insert case default document
        /*
        if(!empty($default_Doc)) {
            foreach ($default_Doc as $k => $v) {
                $k1 = explode('_', $k);
                $ct = CaseType::where('Case_Category', $k1[0])
                        ->where('Case_Type', $k1[1])->first();
                if(!empty($ct)) {
                    $rd = [
                        'Required_Documentation_en' => json_encode($v),
                        'Required_Documentation_es' => json_encode($v)
                        ];
                    CaseType::where('id', $ct->id)->update($rd);
                }
            }
        }
        */
        
        // Insert case default forms
        /*
        if(!empty($default_Forms)) {
            foreach ($default_Forms as $k => $v) {
                $k1 = explode('_', $k);
                $ct = CaseType::where('Case_Category', $k1[0])
                        ->where('Case_Type', $k1[1])->first();
                if(!empty($ct)) {
                    $rd = [
                        'Required_Forms' => json_encode($v)
                        ];
                    CaseType::where('id', $ct->id)->update($rd);
                }
            }
        }
        */


        fclose($file);
        // $this->CheckTodayEvent();
        // $this->check_VA_first_login();
        // $this->check_client_first_login();
        die;
    }

    function CheckTodayEvent() {

        $today = date('Y-m-d');
        echo $q = "select * from event where  s_date='" . $today . "'";
        //$q = "SELECT u.name FROM admintask as t,users as u where t.case_id='" . $value->id . "' and u.id=t.allot_user_id";
        $Event = DB::select(DB::raw($q));
        foreach ($Event as $k => $e) {
            $Event[$k]->who_consult_with = json_decode($e->who_consult_with);
            $whom = array();
            foreach ($Event[$k]->who_consult_with as $e1) {
                $q1 = "select name,email from users where  id='" . $e1 . "'";
                $User = DB::select(DB::raw($q1));
                $whom[] = ($User[0]->name);
            }
            $Event[$k]->who_consult_with = implode(',', $whom);
            echo $rid = $e->related_id;
            switch ($e->title) {
                case 'LEAD':
                    $q2 = "select email from lead where id='" . $rid . "'";
                    break;
                case 'CLIENT':
                    $q2 = "select email from new_client where id='" . $rid . "'";
                    break;
                case 'CASE':
                    $q2 = "select cl.email as email from new_client as cl,`case` as c where c.id='" . $rid . "' and cl.id=c.client_id";
                    break;
            }
            $emails = DB::select(DB::raw($q2));
            if (count($emails) > 0) {
                $Event[$k]->toemail = ($emails[0]->email);
            } else {
                $Event[$k]->toemail = 'No';
            }
        }
        foreach ($Event as $k => $e) {
            /* --------------------------Email--------------------------- */
            $remove = array(
                'date' => $e->s_date,
                'time' => $e->s_time,
                'title_of_event' => $e->event_title,
                'who_consult_with' => $e->who_consult_with,
            );

            $email = EmailTemplate(10, $remove);
            if ($e->toemail != 'No') {
                $args = array(
                    'bodyMessage' => $email['MSG'],
                    'to' => $e->toemail,
                    'subject' => $email['Subject'],
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                pre($args);
                send_mail($args);
            }
            /* --------------------------Email--------------------------- */
        }

        die;
    }

    public function check_mail_lead_convert() {

        $lead_record = Lead::where('status', 1)->Where('email', '!=', '')->get();

        foreach ($lead_record as $key => $value) {

            $date = Carbon::parse($value->created_at);

            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            $username = $value->name . ' ' . $value->last_name;
            $useremail = $value->email;
            $lead_firm_id = $value->firm_id;

            $firm_record = Firm::where('id', $lead_firm_id)->first();

            switch ($diff) {
                case '6':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Not Hired 6 days after previous email")->where('firm_id', $lead_firm_id)->first();

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;

                case '20':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Not Hired 14 days after previous")->where('firm_id', $lead_firm_id)->first();
                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;

                case '40':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Not Hired 20 days after previous")->where('firm_id', $lead_firm_id)->first();

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;
                case '70':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Lead Not Hired 30 days after previous")->where('firm_id', $lead_firm_id)->first();

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;

                case '85':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Lead Not Hired 45 days after previous")->where('firm_id', $lead_firm_id)->first();

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;

                case '145':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "   Lead Not Hired 60 days after previous")->where('firm_id', $lead_firm_id)->first();
                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;

                case '235':
                    $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Lead Not Hired 90 days after previous")->where('firm_id', $lead_firm_id)->first();

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";
                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    send_mail($args);
                    break;
            }
        }
    }

    public function check_client_first_login() {

        $client_record = User::where('role_id', 6)->where('is_reset_pass', 0)->where('status', 1)->get();

        foreach ($client_record as $key => $value) {

            $date = Carbon::parse($value->created_at);

            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            $username = $value->name;
            $useremail = $value->email;
            $client_firm_id = $value->firm_id;


            if ($diff = 1) {

                $firm_record = Firm::where('id', $client_firm_id)->first();

                $mail_record = FirmSetting::select('title', 'message')->where('category', 'EMAIL')->where('title', "Client Access Invite Reminder")->where('firm_id', $client_firm_id)->first();

                if ($mail_record) {

                    $msg = "Hi, $username.<br>";
                    $msg .= "Email : " . $useremail . " <br>";
                    $msg .= $mail_record->message . "<br>";

                    $args = array(
                        'bodyMessage' => $msg,
                        'to' => $useremail,
                        'subject' => $mail_record->title,
                        'from_name' => 'TILA',
                        'from_email' => 'no-reply@tilacaseprep.com'
                    );
                    echo 'Run' . send_mail($args);
                }
            }
        }
    }

    public function check_VA_first_login() {

        $va_record = User::where('role_id', 2)->where('first_login', 0)->where('status', 1)->get();
        pre($va_record);
        foreach ($va_record as $key => $value) {

            $date = Carbon::parse($value->created_at);
            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            $username = $value->name;
            $useremail = $value->email;


            if ($diff = 1) {

                $mail_record = TilaEmailTemplate::select('subtitle', 'massage')->where('title', 'VA Notifications')->where('subtitle', "VA Access Invite Reminder")->first();

                $msg = "Hi, $username.<br>";
                $msg .= "Email : " . $useremail . " <br>";
                $msg .= $mail_record->massage . "<br>";

                $args = array(
                    'bodyMessage' => $msg,
                    'to' => $useremail,
                    'subject' => $mail_record->subtitle,
                    'from_name' => 'TILA',
                    'from_email' => 'no-reply@tilacaseprep.com'
                );
                echo 'Run VA' . send_mail($args);
            }
        }
    }

    public function PdfMetaEnter() {
        return view('admin.pdfforms.pdfmeta', ["RequestedData" => 1, 'total' => '2']);
        die();
    }

    public function UserMonthlyPayment() {
        $date = date('Y-m-d');
        $date = '%2020-08-22%';
        $t = Transaction::select('*')
                // ->where('created_at', 'LIKE', $date)
                ->where('type', 'User')
                ->get();
        pre($t);
    }

    public function DirectUseHelper(Request $request, $action = '') {
            AllTypeOfAjax($action);
    }

}
