<?php

$meta = DB::select('SELECT  * from tila_pdfform_list where Syn=0 ');
if (count($meta) == 0) {
    $j = array('Syn' => 0);
    DB::table('tila_pdfform_list')->update($j);
    die;
}

$index = rand(0, count($meta) - 1);
$file = $meta[$index]->FileName;
$dirPath = (storage_path('app/forms/all/' . $file));
include(getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php");


PDFNet::Initialize("Tila Case Prep, Inc.(tilacaseprep.com):OEM:TilaCasePrep::L:AMS(20210406):2F67BEB01F879AD0631352786F610FAB1C084ACA95952B84BD429BF65A8A31F5C7");
    
//PDFNet::Initialize();
PDFNet::GetSystemFontList();    // Wait for fonts to be loaded if they haven't already. This is done because PHP can run into errors when shutting down if font loading is still in progress.
// Relative path to the folder containing the test files.
$input_path = getcwd() . "/PDFNetWrappers/test/";
$output_path = $input_path . "Output/";

// Example 1: 
// Iterate over all form fields in the document. Display all field names.
echo $dirPath;
$doc = new PDFDoc($dirPath);
$doc->InitSecurityHandler();

$ii=0;
for ($itr = $doc->GetFieldIterator(); $itr->HasNext(); $itr->Next()) {

    
    $type = $itr->Current()->GetType();
    $FieldType = '';
    switch ($type) {
        case Field::e_button:
            $FieldType = "Button";
            break;
        case Field::e_check:
            $FieldType = "CheckBox";
            break;
        case Field::e_radio:
            $FieldType = "RadioButton";
            break;
        case Field::e_text:
            $FieldType = "Textbox";
            break;
        case Field::e_choice:
            $FieldType = "MultiChoice";
            break;
        case Field::e_signature:
            $FieldType = "Signature";
            break;
        default:
            break;
    }
    $newF = $file;
    $uID = GetFieldKey(($file), $itr->Current()->GetName());
    $metas = DB::select('SELECT  * from tila_pdfform_meta where FieldUniqueID="' . $uID . '"');
    $i['fieldtype'] = FieldType($FieldType);
    $i['FieldUniqueID'] = ($uID);
    $i['FieldID'] = $itr->Current()->GetName();
    $i['FieldName'] = $itr->Current()->GetPartialName();
    $i['pdffile'] = $file;
    $i['pdffileEncripted'] = md5($file);

    if (count($metas) == 0) {
        
        DB::table('tila_pdfform_meta')->insert($i);
    } else {
       $i['pdfindex'] = $ii;
        DB::table('tila_pdfform_meta')->where('FieldUniqueID', $uID)->update($i);
    }
    $ii++;
}

$j = array('Syn' => 1);
DB::table('tila_pdfform_list')->where('FileNameEncripted', md5($file))->update($j);



/* ---------------------------Get Text--------------------------------- */
$dirPath;

$page = $doc->GetPage(1);

$txt = new TextExtractor();
$txt->Begin($page); // Read the page.
$Line = '';
for ($line = $txt->GetFirstLine(); $line->IsValid(); $line = $line->GetNextLine()) {
    $Line = '';
    for ($word = $line->GetFirstWord(); $word->IsValid(); $word = $word->GetNextWord()) {
        $Line .= ' ' . $word->GetString();
    }
    $a = array();
    $Line = trim($Line);
    if (strlen($Line) > 2) {
        $file=trim($file);
        $a['fileName'] = $file;
        $a['metatext'] = base64_encode($Line);
        $a['textUnique'] = md5($Line);
        $a['fileEncription'] = md5($file);

        $metas = DB::select('SELECT  * from tila_pdfform_meta_text where textUnique="' . md5($Line) . '"');
        if (count($metas) == 0) {
           '<br>Now New Textt ===> ' . $Line . '>>>>>' . $file . '<hr>';

            DB::table('tila_pdfform_meta_text')->insert($a);
        } else {
             '<br>Now Update Text ===> ' . $Line . '>>>>>' . $file . '<hr>';
            //pre($i);
            DB::table('tila_pdfform_meta_text')->where('textUnique', md5($Line))->update($a);
        }
    }
}






/* -------------------------Get Text----------------------------------- */



?>