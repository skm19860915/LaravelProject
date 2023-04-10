@extends('layouts.admin-master')

@section('title')
PDF Forms 
@endsection

@push('header_styles')
<style type="text/css">
    #usertable tbody tr td:nth-child(1) {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush  

@section('content') 
<section  class="section">
    <div class="section-header">
        <h1>PDF Form</h1>
        <div class="section-header-breadcrumb">
            <a class="btn btn-primary"><i class="fa fa-spinner wc"></i></a>
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
                        <input type="text" class="form-control searchingData" placeholder="Please Enter File Name"><br><br>
                        <div class="allpdfsformwithsearch masterField"></div>
                    </div>

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
