@extends('layouts.admin-master')

@section('title')
PDF Forms 
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
.bootstrap-select .dropdown-menu li a span.text {
    white-space: initial;
}
</style>
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
                <div class="alert alert-danger msg">ee</div>
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">
                            <a class="ShowAllFIeld hideShow btn btn-danger" data-show="0"><i class="fa fa-eye"></i> Show All Field</a>
                            <a class="ManageGroup btn btn-danger">Manage Group</a>
                        </div>
                    </div>
                    <div class="card-body getFilesFields" data-page="0" data-next="0" data-pre="0" style="position: relative;" data-filename="<?php echo $RequestedData; ?>" >
                        <?php
                        $file = base64_decode($RequestedData);
                        //$FieldsData = DB::select('SELECT  * from tila_pdfform_meta where pdffileEncripted="' . md5($file) . '"');
                        //pre($FieldsData);
                        ?><div  class="boxloaders"><i class="fa fa-spin fa-spinner"></i></div>
                        <table class="table groupselection">
                            <thead>
                                <tr>
                                    <th style=" width: 100px !important;">.<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th style=" width: 150px !important;">Part Number <i class="hideBoxes btn btn-danger  fa fa-eye btn-sm"></i></th>
                                    <th style=" width: 150px !important;">Question #<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th>Name Given In PDF<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th>Field<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th>Person Field<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i> </th>
                                    <th >Relation Name<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th>Select Relationship<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th>Relation<i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                                    <th><center>Is Master</center><i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                            <th><i class="hideBoxes btn btn-danger fa fa-eye btn-sm"></i></th>
                            </tr>
                            </thead> 
                            <tbody>

                            </tbody>
                        </table>
                        <a class="btn btn-danger DarkColor previous">Previous</a> <a class="Nextbut btn btn-danger DarkColor">Next</a>
                        <div class="manageGroupBox">
                            <div class="grouptitle">
                                <h4>Manage Groups <i class="ManageGroup fa fa-close"></i></h4>
                                <div class="clr"></div>
                            </div>
                            <div class="manageGroupBoxBody">
                                <div class="groupForm">
                                    <form id="GroupNameAdd">
                                        <div>
                                            <label>Group Name<br>
                                                <input type="text" class="form-control groupName" name="GroupName"></label>
                                            <label>Parent Group<br>
                                                <select name="ParentGroup" class="parentgroup form-control"><option></option></select></label>
                                            <label>Is Repeat<br>
                                                <input type="radio"  class="form-control groupRepeat"  name="GroupRepeat"></label> 
                                            <label>Is Radio<br>
                                                <input type="radio"  class="form-control groupradio"  name="GroupRepeat"></label> 
                                            <label>
                                                <input type="submit"  class="form-control btn btn-success SaveDatagroup"   name="SaveData"></label>
                                        </div>
                                    </form>

                                </div>
                                <div class="groupTable ">
                                    <div class="">
                                        <table class="table groupselection">
                                            <thead><tr><th>Group Name</th> <th>Parent Group Name</th> <th><center>Is Repeated</center></th> <th>Number Of Field Added</th><th>Action</th></tr></thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <users-component></users-component> -->
    </div>

</section>
<button onclick="topFunction()" id="backtotop" title="Go to top">
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</button>
@endsection

@push('footer_script')
<div class="manageGroupBoxFields ">
    <div class="grouptitle">
        <h4>Manage Fields <i class="ManageGroupFields fa fa-close"></i></h4>
        <div class="clr"></div>
    </div>
    <div class="manageGroupBoxBody">
        <select><option>First</option></select>
    </div>
</div> 
<style>
    .DarkColor{
        font-size: 12px;
        border-radius: 30px !important;
        padding-left: 13px !important;
        padding-right: 13px !important;
        background: #91476A !important;
        color: #fff !IMPORTANT;
        cursor: pointer;
    }
    td div.FieldRelations {
        padding: 0px !important;
        width: auto !important;
        border: 0px !important;
    }
    #backtotop {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: #013E41;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 4px;
    }

    #backtotop:hover {
        background-color: #013E41;
    }
</style>
<script>
//Get the button
    var mybutton = document.getElementById("backtotop");

// When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

// When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>




@endpush 
