<?php

if (!function_exists('PagesAuthentications')) {

    function PagesAuthentications() {
        $data = Auth::User();
        TestURLPattern($data);
        // if (isset($_REQUEST['test']) && ($_REQUEST['test'] == 1 || $_REQUEST['test'] == 2)) {

        //     TestURLPattern($data);
        //     if ($_REQUEST['test'] == 2) {
        //         die;
        //     }
        // }
    }

}
if (!function_exists('TestURLPattern')) {

    function TestURLPattern($data) {
        $dataUrl = strtok($_SERVER['REQUEST_URI'], '?');
        $dataex = explode('/', $dataUrl);
        //pre($dataex);
        /* ------ Lead Detail Page-------- */
        if(
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/acceptpayment') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/edit_invoice') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/cancel_invoice') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/view_invoice') !== false)
        ) 
        {
            if(count($dataex) == 5) {
                $LeadID = $dataex[count($dataex) - 1];
                $q = "SELECT * FROM lead WHERE id = '" . $LeadID . "' AND firm_id = '" . $data->firm_id . "'";
                $Lead = DB::select($q);
                if (count($Lead) == 0) {
                    echo '<img style="width:100%;" src="/404.jpg">';
                    echo stylecss();
                    die;
                }
            }
            else if(count($dataex) == 6) {
                $LeadID = $dataex[count($dataex) - 2];
                $q = "SELECT * FROM lead WHERE id = '" . $LeadID . "' AND firm_id = '" . $data->firm_id . "'";
                $Lead = DB::select($q);
                if (count($Lead) == 0) {
                    echo '<img style="width:100%;" src="/404.jpg">';
                    echo stylecss();
                    die;
                }
            }
        }
        else if (
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/show') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/billing') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/invoice') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/edit') !== false) || 
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/create_event') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/lead/add_invoice') !== false)
        ) 
        {
            $LeadID = $dataex[count($dataex) - 1];
            $q = "SELECT * FROM lead WHERE id = '" . $LeadID . "' AND firm_id = '" . $data->firm_id . "'";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }


        /* ------ Lead Detail Page-------- */

        /* ------ Client Detail Page-------- */
        if(
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/edit_family') !== false)
            )
        {
            $ClintID = $dataex[count($dataex) - 2];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u WHERE c.id = '" . $ClintID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        else if(
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/edit_client_task') !== false)
            )
        {
            $ClintID = $dataex[count($dataex) - 2];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u WHERE c.id = '" . $ClintID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        else if(
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/view_client_invoice') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/edit_client_invoice') !== false)
            )
        {
            $ClintID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u, qb_invoice as qi WHERE qi.id = '" . $ClintID . "' and qi.client_id = c.id and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        else if (
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/show') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/profile') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'client/view_family') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'client/family') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_event') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_case') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/document') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/view_notes') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_invoice') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/add_new_case') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/edit') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/create_event') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_task') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/add_client_task') !== false) ||
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_billing') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_schedule_history') !== false)
            ) 
        {
            $ClintID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u WHERE c.id = '" . $ClintID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        else if (
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/add_new_invoice') !== false) || 
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/text_message') !== false)
            )
        {
            $ClintID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u WHERE c.user_id = '" . $ClintID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        else if (
                (strpos($_SERVER['REQUEST_URI'], 'firm/client/client_edit_event') !== false)
            )
        {
            $ClintID = $dataex[count($dataex) - 2];
            $EventID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM new_client as c,users as u, event as e WHERE c.id = '" . $ClintID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (u.role_id = '4' OR u.role_id = '5') and e.related_id = c.id and e.id = '" . $EventID . "'";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        
        /* ------ Client Detail Page-------- */


        /* ------ Case Detail Page-------- */
        if (
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/show') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/profile') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/case_family') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/case_forms') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/add_forms') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/case_documents') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/case_tasks') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/additional_service') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/affidavit') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/add_case_tasks') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/add_case_interpreter') !== false)
        )
        {
            $CaseID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM `case` as c,users as u WHERE c.id = '" . $CaseID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }

        if (
            (strpos($_SERVER['REQUEST_URI'], 'firm/case/view_family_documents') !== false)
        )
        {
            $CaseID = $dataex[count($dataex) - 2];
            $FirmID = $data->firm_id;
            $q = "SELECT c.id FROM `case` as c,users as u WHERE c.id = '" . $CaseID . "' and c.firm_id='" . $FirmID . "' and c.firm_id=u.firm_id and (role_id = '4' OR role_id = '5') ";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        
        /* ------ Case Detail Page-------- */

        /* ------ User Detail Page-------- */

        if (
            (strpos($_SERVER['REQUEST_URI'], 'firm/users/edit') !== false) ||
            (strpos($_SERVER['REQUEST_URI'], 'firm/users/delete') !== false)
        )
        {
            $UserID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT u.id FROM users as u WHERE u.id = '" . $UserID . "' and u.firm_id='" . $FirmID . "'";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }

        /* ------ User Detail Page-------- */

        /* ------ Other Detail Page-------- */
        if (
            (strpos($_SERVER['REQUEST_URI'], 'firm/setting/update') !== false)
        )
        {
            $UserID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q = "SELECT u.id FROM firm_setting as u WHERE u.id = '" . $UserID . "' and u.firm_id='" . $FirmID . "'";
            $Lead = DB::select($q);
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }

        if (
            (strpos($_SERVER['REQUEST_URI'], 'editpdf') !== false)
        )
        {
            $UserID = $dataex[count($dataex) - 1];
            $FirmID = $data->firm_id;
            $q1 = "SELECT * FROM client_information_forms WHERE id ='" . $UserID . "'";
            $Form = DB::select($q1);
            if($data->role_id == 4 || $data->role_id == 5) {
                $case_id = $Form[0]->case_id;
                $q = "SELECT * FROM client_information_forms WHERE id ='" . $UserID . "' AND firm_id = '".$FirmID."'";
                $Lead = DB::select($q);
                
            }
            else if($data->role_id == 2) {
                $case_id = $Form[0]->case_id;
                $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $case_id . "' AND allot_user_id = '".$data->id."'";
                $Lead = DB::select($q);
            }
            else if($data->role_id == 1) {
                $case_id = $Form[0]->case_id;
                $q = "SELECT * FROM admintask WHERE task_type = 'Assign_Case' AND case_id = '" . $case_id . "'";
                $Lead = DB::select($q);
            }
            if (count($Lead) == 0) {
                echo '<img style="width:100%;" src="/404.jpg">';
                echo stylecss();
                die;
            }
        }
        /* ------ Other Detail Page-------- */
    }

}

if (!function_exists('stylecss')) {

    function stylecss() {
        ?>
        <style>body,html{margin:0px; margin:0px;}</style>
    <?php

    }

}