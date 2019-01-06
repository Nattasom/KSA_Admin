@extends('layouts.default')
@section('title')
Mockup
@stop
@section('content')
    <div id="page-identity" data-menu="" data-parent=""  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    xxxx <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">xxxx</a>
            </li>
        </ul>
    </div>
@stop
@section('script')
   
@stop