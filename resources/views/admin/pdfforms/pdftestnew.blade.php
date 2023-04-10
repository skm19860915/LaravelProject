@extends('layouts.admin-master')

@section('title')
PDF Forms 
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush  

@section('content')
<div class="row">
            <div class="col-md-12">
                <div class="card">
<?php  CallPDFDataBYGroup('b8f57ca746'); ?>
                </div></div></div>  
@endsection

@push('footer_script')


@endpush 
