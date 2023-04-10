<?php


if (!function_exists('AllTypeOfAjax')) {

    function AllTypeOfAjax($action) {
        $action();
        die;
    }

}


if (!function_exists('send_mail')) {

    function send_mail($args) {
        global $args1;
        $args1 = $args;
        try {
            Mail::send('email', $args, function ($message) {
                global $args1;
                if (!isset($args1['from_email'])) {
                    $args1['from_email'] = 'no-reply@tilacaseprep.com';
                }
                if (!isset($args1['from_name'])) {
                    $args1['from_name'] = 'Welcome to TILA';
                }
                $args1['from_email'] = 'no-reply@tilacaseprep.com';
                $args1['from_name'] = 'TILA Case Prep';
                $message->from($args1['from_email'], $args1['from_name']);
                $message->to($args1['to'])->subject($args1['subject']);
                $message->getHeaders()->addTextHeader('X-PM-Message-Stream', 'outbound');
                if(!empty($args1['pdfdata'])) {
                    $pdfdata = base64_decode($args1['pdfdata']);
                    $message->attachData($pdfdata, "invoice.pdf");
                }
            });

            // Mail::send('email', $args, function ($message) {
            //     global $args1;
            //     if (!isset($args1['from_email'])) {
            //         $args1['from_email'] = 'no-reply@tilacaseprep.com';
            //     }
            //     $args1['from_email'] = 'no-reply@tilacaseprep.com';
            //     if (!isset($args1['from_name'])) {
            //         $args1['from_name'] = 'TILA';
            //     }
            //     $message->from($args1['from_email'], $args1['from_name']);
            //     $message->to(COPYMAIL())->subject($args1['subject']);
            // });
        }

        catch (Exception $e) {
            
        }
    }

}


/* -----------THis Function For TimeStamp TO Date Formate-------- */
if (!function_exists('timetodate')) {

    function timetodate($t) {
        $t = trim($t);
        if (date('Y', strtotime($t)) > 1970) {
            return date('d F, Y h:i A', strtotime($t));
        } else {
            return '';
        }
    }

}

if (!function_exists('PaymentType')) {

    function PaymentType($Status) {
        switch ($Status) {
            case 1:
                $Status = 'VP Service';
                break;
            case 2:
                $Status = 'CMS';
                break;
            case 3:
                $Status = 'translation';
                break;
            case 4:
                $Status = 'client payment';
                break;
            default:
                $Status = "Don't know";
                break;
        }
        return $Status;
    }

}
if (!function_exists('EmailTemplate')) {

    function EmailTemplate($id, $rplc = array()) {

        $MailTemp = DB::table("tila_email_template")->where("id", $id)->get();
        $MsgT = array();
        $doamin = url('');
        $msg = '';
        $msg .= '<div>';
        $msg .= '<div style="margin:0 10%; background:#fff;     padding: 1em;">';
        $msg .= '<img src="' . $doamin . '/assets/img/tila-logo.png" alt="logo" width="100" class="mb-5 mt-2">';
        $msg .= '<hr style="background: #91476A;height: 2px;">';
        $msg .= '<div>';
        $msg .= $MailTemp[0]->massage;
        //$msg .= '<br><br>Have a great day!<br>The TILA Team';
        $msg .= '</div>';
        $msg .= '<div><hr style="background: #91476A;height: 2px;">';

        $msg .= '<br>TILA Case Prep, Inc<br>1500 SW First Avenue, Portland, OR 97201<br>';
        $msg .= '</div>';
        $msg .= '</div></div>';
        foreach ($rplc as $k => $v) {
            $msg = str_replace('%%' . $k . '%%', $v, $msg);
        }
        $MsgT['MSG'] = $msg;
        $MsgT['Subject'] = $MailTemp[0]->subtitle;

        return $MsgT;
    }

}

if (!function_exists('EmptyEmailTemplate')) {
    function EmptyEmailTemplate($msg4) {
        $doamin = url('');
        $msg = '';
        $msg .= '<div>';
        $msg .= '<div style="margin:0 10%; background:#fff;     padding: 1em;">';
        $msg .= '<img src="' . $doamin . '/assets/img/tila-logo.png" alt="logo" width="100" class="mb-5 mt-2">';
        $msg .= '<hr style="background: #91476A;height: 2px;">';
        $msg .= '<div>';
        $msg .= $msg4;
        //$msg .= '<br><br>Have a great day!<br>The TILA Team';
        $msg .= '</div>';
        $msg .= '<div><hr style="background: #91476A;height: 2px;">';

        $msg .= '<br>TILA Case Prep, Inc<br>1500 SW First Avenue, Portland, OR 97201<br>';
        $msg .= '</div>';
        $msg .= '</div></div>';
        return $msg;
    }

}

if (!function_exists('IsCaseAdditionalService')) {
    function IsCaseAdditionalService($ccat, $ctype) {
        $v = DB::table("case_types")
                    ->where("Case_Category", $ccat)
                    ->where("Case_Type", $ctype)
                    ->first();
        return $v->is_additional_service;
    }
}

if (!function_exists('GetCaseStatus')) {

    function GetCaseStatus($Status, $account = 'firmadmin') {
        $status = "";
        switch ($Status) {
            case -2:
                $status = "Decline";
                break;
            case -1:
                $status = "Not Approve";
                break;
            case 0:
                $status = "";
                break;
            case 1:
                $status = "Open";
                if ($account == 'admin') {
                    $status = 'To be assigned';
                }
                break;
            case 2:
                $status = "Open";
                if ($account == 'admin') {
                    $status = 'Assigned';
                }
                break;
            case 3:
                $status = "Open";
                if ($account == 'admin') {
                    $status = 'In Progress';
                }
                break;
            case 4:
                $status = "On Hold";
                if ($account == 'admin') {
                    $status = 'Document Required';
                }
                break;
            case 5:
                $status = "On Hold";
                if ($account == 'admin') {
                    $status = 'Translation Required';
                }
                break;
            case 6:
                $status = "Ready for Review";
                if ($account == 'admin') {
                    $status = 'Ready for Review';
                }
                break;
            case 7:
                $status = "On Hold";
                if ($account == 'admin') {
                    $status = 'Firm Accepted (CLOSED)';
                }
                break;
            case 8:
                $status = "Incomplete ";
                if ($account == 'admin') {
                    $status = 'Incomplete  (CLOSED)';
                }
                break;
            case 9:
                $status = "Complete";
                if ($account == 'admin') {
                    $status = 'Complete  (CLOSED)';
                }
                break;
        }
        return$status;
    }

}

if (!function_exists('SendInvoice')) {

    function SendInvoice($data) {
        $link = url('firm/firmclient/billing/invoice/viewinvoice/'.$data['id']);
        $remove = array(
            'Client_Name' => $data['invoice']->client_name,
            'Link' => $link,
        );
        $email = EmailTemplate(34, $remove);
        // $client->email
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => 'snvservices.ravikant@gmail.com',
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        send_mail($args);
        // include(getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php");
        // PDFNet::Initialize();
        // PDFNet::GetSystemFontList();

        // echo $output_path = asset('storage/app');

        // $doc = new PDFDoc();

        // $converter = new HTML2PDF();

        // $html = "<html><body><h1>Heading</h1><p>Paragraph.</p></body></html>";
            
        // $converter->InsertFromHtmlString($html);
 
        
        // if ( $converter->Convert($doc) ) {
        //     $doc->Save($output_path."/invoice.pdf", SDFDoc::e_linearized);
        // }
        // else {
        //     echo printf("Conversion failed. HTTP Code: %d\n%s", $converter->GetHTTPErrorCode(), $converter->GetLog());
        // }
        // $doc->Close();
    }

}

if (!function_exists('PrintInvoice')) {

    function PrintInvoice($data) {
        $link = url('firm/firmclient/billing/invoice/viewinvoice/'.$data['id']);
        $remove = array(
            'Client_Name' => $data['invoice']->client_name,
            'Link' => $link,
        );
        $email = EmailTemplate(34, $remove);
        // $client->email
        $args = array(
            'bodyMessage' => $email['MSG'],
            'to' => 'snvservices.ravikant@gmail.com',
            'subject' => $email['Subject'],
            'from_name' => 'TILA',
            'from_email' => 'no-reply@tilacaseprep.com'
        );
        // send_mail($args);

        include(getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php");
        HTML2PDF::SetModulePath(getcwd() ."/PDFNetWrappers/PDFNetC/Lib");
        PDFNet::Initialize('Tila Case Prep, Inc.:OEM:TilaCasePrep::B+:AMS(20210406):1FB51A520467460AB360B13AC9A2737860610FAB1C084ACA95952B84BD429BF65A8A31F5C7');
        PDFNet::GetSystemFontList();

        $output_path = asset('storage/app');
        
        $doc = new PDFDoc();

        $converter = new HTML2PDF();

        $html = "<html><body><h1>Heading</h1><p>Paragraph.</p></body></html>";
            
        $converter->InsertFromHtmlString($html);
 
        
        if ( $converter->Convert($doc) ) {
            $doc->Save($output_path."/invoice.pdf", SDFDoc::e_linearized);
        }
        else {
            echo printf("Conversion failed. HTTP Code: %d\n%s", $converter->GetHTTPErrorCode(), $converter->GetLog());
        }
        $doc->Close();
    }

}


