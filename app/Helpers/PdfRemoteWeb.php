<?php

function margePDF() { 
    //OriginChk();
   # echo getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php";
    include(getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php");


    //$dirPath = (storage_path('app/forms/all/'));
    $input_path = storage_path('app/forms/all/tilacasep');
    $output_path = storage_path('app/forms/output/');
    $dir = scandir($input_path);
    $nf = array();
    foreach ($dir as $k => $v) {
        $nf[md5(trim($v))] = $v;
    }
    
    

    $i = 1;
    $arrays = explode(',', $_REQUEST['files']);
    #pre($arrays);
    //PDFNet.Initialize("Tila Case Prep, Inc.(tilacaseprep.com):OEM:TilaCasePrep::L:AMS(20210406):2F67BEB01F879AD0631352786F610FAB1C084ACA95952B84BD429BF65A8A31F5C7");
    PDFNet::Initialize("Tila Case Prep, Inc.(tilacaseprep.com):OEM:TilaCasePrep::L:AMS(20210406):2F67BEB01F879AD0631352786F610FAB1C084ACA95952B84BD429BF65A8A31F5C7");
    #PDFNet::Initialize(); 
    PDFNet::GetSystemFontList();
    #die;
    $new_doc = new PDFDoc();
    $new_doc->InitSecurityHandler();
    foreach ($arrays as $key => $v) {
        //$uc = DB::table("tila_pdfform_list")->where("FileNameEncripted", $v)->first();
        $file = explode('__', $v);

        $in_doc = new PDFDoc($input_path . '/' . $nf[$file[0]]);

        $in_doc->InitSecurityHandler();
        if ($_REQUEST['fill_fld'] == "true" && $_POST['isna'][$key] != 2) {
            for ($itr = $in_doc->GetFieldIterator(); $itr->HasNext(); $itr->Next()) {
                $type = $itr->Current()->GetType();
                $txt = 'signature';
                $mystring = strtolower($itr->Current()->GetName());
                // $results[] = $mystring;
                $findme = 'signature';
                $pos = strpos($mystring, $findme);
                // $fldr = $in_doc->GetField($itr->Current()->GetName());
                // $fldr->SetFlag(Field::e_read_only, false);
                // $fldr->RefreshAppearance();
                // print_r($itr);
                // echo nl2br("Field name: ".$itr->Current()->GetName()."\n");
                // echo nl2br("Field partial name: ".$itr->Current()->GetPartialName()."\n");
                if ($pos === false && $file[1] != 2) {
                    switch ($type) {
                        // case Field::e_button: echo nl2br("Button"."\n"); break;
                        case Field::e_text: {
                                $fld = $in_doc->GetField($itr->Current()->GetName());
                                $fld->SetFlag(Field::e_read_only, false);
                                /* ----------Change By Ankit------------- */
                                if ($fld->getValueAsString() != 'NONA') {
                                    $fld->SetValue("N/A");
                                } else {
                                    $fld->SetValue("");
                                }
                                /* ----------Change By Ankit------------- */
                                //$fld->SetValue("N/A");     Old Code
                                $fld->RefreshAppearance();
                            }
                            break;
                        // case Field::e_choice: echo nl2br("Choice"."\n"); break;
                        case Field::e_signature: {
                                $fld = $in_doc->GetField($itr->Current()->GetName());
                                $fld->SetFlag(Field::e_read_only, false);
                                $fld->SetValue("");
                                $fld->RefreshAppearance();
                            }
                            break;
                    }
                } else {
                    $fld = $in_doc->GetField($itr->Current()->GetName());
                    $fld->SetFlag(Field::e_read_only, false);
                    $fld->SetValue("");
                    $fld->RefreshAppearance();
                }
            }
        } else {
            for ($itr = $in_doc->GetFieldIterator(); $itr->HasNext(); $itr->Next()) {
                $type = $itr->Current()->GetType();
                $txt = 'signature';
                $mystring = strtolower($itr->Current()->GetName());
                $findme = 'signature';
                $pos = strpos($mystring, $findme);
                // $fldr = $in_doc->GetField($itr->Current()->GetName());
                // $fldr->SetFlag(Field::e_read_only, false);
                // $fldr->RefreshAppearance();
                // print_r($itr);
                // echo nl2br("Field name: ".$itr->Current()->GetName()."\n");
                // echo nl2br("Field partial name: ".$itr->Current()->GetPartialName()."\n");
                if ($pos === false) {
                    switch ($type) {
                        // case Field::e_button: echo nl2br("Button"."\n"); break;
                        case Field::e_text: {
                                $fld = $in_doc->GetField($itr->Current()->GetName());
                                $fld->SetFlag(Field::e_read_only, false);
                                /* ----------Change By Ankit------------- */
                                if ($fld->getValueAsString() != 'NONA' || $fld->getValueAsString() != 'N') {
                                    
                                } else {
                                    $fld->SetValue("");
                                }
                                /* ----------Change By Ankit------------- */
                                //$fld->SetValue("N/A");     Old Code
                                $fld->RefreshAppearance();
                            }
                            break;
                        // case Field::e_choice: echo nl2br("Choice"."\n"); break;
                        case Field::e_signature: {
                                $fld = $in_doc->GetField($itr->Current()->GetName());
                                $fld->SetFlag(Field::e_read_only, false);
                                if ($fld->getValueAsString() == 'NONA' || $fld->getValueAsString() == 'N') {
                                    $fld->SetValue("");
                                }
                                $fld->RefreshAppearance();
                            }
                            break;
                    }
                } else {
                    $fld = $in_doc->GetField($itr->Current()->GetName());
                    $fld->SetFlag(Field::e_read_only, false);
                    if ($fld->getValueAsString() == 'NONA' || $fld->getValueAsString() == 'N') {
                        $fld->SetValue("");
                    }
                    $fld->RefreshAppearance();
                }
            }
        }
        $i++;
        $new_doc->InsertPages($i, $in_doc, 1, $in_doc->GetPageCount(), PDFDoc::e_none);
        $i = $i + $in_doc->GetPageCount();
        $in_doc->Close();
    }
    $file = "DownLoad_" . md5(rand() . strtotime('now')) . ".pdf";
    $pf = $output_path . $file;
    $new_doc->Save($pf, SDFDoc::e_remove_unused);
    chmod($pf, 777);
    echo $URL = "https://app.tilacaseprep.com/storage/app/forms/output/" . $file;
    $log = $output_path . 'log.txt';
    $file = array();
    $file = json_decode(file_get_contents($log, $pf), true);
    $file[] = array('name' => $file, 'time' => strtotime('now'));
    file_put_contents($log, json_encode($file));
    chmod($log, 777);
    $in_doc->Close();
}

function OriginChk() {
    if (isset($_SERVER["HTTP_ORIGIN"]) === true) {
        $origin = $_SERVER["HTTP_ORIGIN"];
        $allowed_origins = array(
            "*",
            "https://test.tilacaseprep.com",
            "http://test.tilacaseprep.com",
            "https://tilacaseprep.com",
            "http://tilacaseprep.com"
        );
        if (in_array($origin, $allowed_origins, true) === true) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Content-Type');
        } else {
            echo '404 Not Found'; 
        }
    }
}
