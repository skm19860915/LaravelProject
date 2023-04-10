@extends('layouts.admin-master')

@section('title')
PDF Forms 
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush  

@section('content') 
<section class="section">
    <div class="section-header">
        <h1>PDF Form "<strong><?php echo base64_decode($RequestedData); ?></strong>" <a target="_new" href="<?php echo '/storage/app/forms/all/' . base64_decode($RequestedData); ?>"><i class="fa fa-file-pdf-o"></i></a></h1>
        <div class="section-header-breadcrumb">

        </div>
    </div>
    <div class="section-body">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">

                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $file = base64_decode($RequestedData);
                        $dirPath = (storage_path('app/forms/all/' . $file));
                        include(getcwd() . "/PDFNetWrappers/PDFNetC/Lib/PDFNetPHP.php");


                        PDFNet::Initialize();
                        PDFNet::GetSystemFontList();    // Wait for fonts to be loaded if they haven't already. This is done because PHP can run into errors when shutting down if font loading is still in progress.
// Relative path to the folder containing the test files.
                        echo $input_path = storage_path('app/forms/all/');
                        echo '<br>';
                        echo $output_path = getcwd();

                        /* ---------------New Code------------- */
                        $input_filename = $file;
                        $output_filename = 'update_' . $file;

                        function ProcessElements($reader) {
                            for ($element = $reader->Next(); $element != null; $element = $reader->Next()) {  // Read page contents
                                // pre(Element::e_path);
                                // echo "----------";
                                pre($element->GetXObject());
                                pre($element);
                                switch ($element->GetType()) {
                                    case Element::e_path:      //Process path data...
                                        {
                                            echo '<hr>';
                                            $data = $element->GetPathData();
                                            $points = $data->GetPoints();
                                        }
                                        break;
                                    case Element::e_text:     //Process text strings...
                                        {
                                            echo '<hr> ';
                                            $data = $element->GetTextString();
                                            //echo nl2br("Field name: ".$element->GetName()."\n");
                                            echo nl2br($data . "\n");
                                        }
                                        break;
                                    case Element::e_form:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello Ankit';
                                            pre($reader); 
                                        }
                                        break;
                                    case Element::e_text_begin:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_text_begin';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_text_new_line:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_text_new_line';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_text_end:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_text_end';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_image:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_image';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_inline_image:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_inline_image';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_shading:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_shading';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_group_begin:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_group_begin';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_group_end:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_group_end';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_marked_content_begin:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_marked_content_begin';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_marked_content_end:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_marked_content_end';
                                            pre($reader);
                                        }
                                        break;
                                    case Element::e_marked_content_point:    //Process form XObjects
                                        {
                                            echo '<hr>  Hello e_marked_content_point';
                                            pre($reader);
                                        }
                                        break;
                                }
                            }
                        }

                        PDFNet::Initialize();
                        PDFNet::GetSystemFontList();    // Wait for fonts to be loaded if they haven't already. This is done because PHP can run into errors when shutting down if font loading is still in progress.
                        // Extract text data from all pages in the document

                        echo nl2br("-------------------------------------------------\n");
                        echo nl2br("Sample 1 - Extract text data from all pages in the document.\n");
                        echo nl2br("Opening the input pdf...\n");

                        $doc = new PDFDoc($input_path . $input_filename);
                        $doc->InitSecurityHandler();
                        
                        $pgnum = $doc->GetPageCount();

                        $page_reader = new ElementReader();
                        
                                    
                        for($itr = $doc->GetFieldIterator(); $itr->HasNext(); $itr->Next())
                        {
                            echo '<br>---ITR---<br>'; 
//                           var_dump($itr);
                            echo '<br>---ITRCurrent---<br>';
                            pre($itr->Current()->GetDefaultAppearance());
//                            echo '<br>---ITRCurrent Field---<br>';
//                            var_dump($itr->Current()->GetJustification());
//                            echo nl2br("Field name: ".$itr->Current()->GetDefaultAppearance()."\n");
////                           echo nl2br("<br><br>Field partial name: ".$itr->Current()->PData()."\n");
//                           echo nl2br("------------------------------\n");
                           echo '<hr>';
                        }
                        
                        
//                        for($itr = $doc->GetFieldIterator(); $itr->HasNext(); $itr->Next())
//                        {
//                            echo '<br>---ITR---<br>'; 
////                           var_dump($itr);
//                            echo '<br>---ITRCurrent---<br>';
//                            var_dump($itr->Current()->GetDefaultAppearance());
////                            echo '<br>---ITRCurrent Field---<br>';
////                            var_dump($itr->Current()->GetJustification());
////                            echo nl2br("Field name: ".$itr->Current()->GetDefaultAppearance()."\n");
//////                           echo nl2br("<br><br>Field partial name: ".$itr->Current()->PData()."\n");
////                           echo nl2br("------------------------------\n");
//                           echo '<hr>';
//                        }
//                         for ($itr = $doc->GetPageIterator(); $itr->HasNext(); $itr->Next()) {  //  Read every page
//                             echo '<br>---ITRCurrent---<br>';
//                            var_dump($itr->Current());
//                             $page_reader->Begin($itr->Current());
//                             ProcessElements($page_reader);
//                             $page_reader->End(); 
//                         }

                        echo nl2br("Done.\n");
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- <users-component></users-component> -->
    </div>
</section>
@endsection

@push('footer_script')


@endpush 
