@extends('layouts.default')
@section('title')
Promotion Edit
@stop
@section('content')
    <div id="page-identity" data-menu="#promotion-menu" data-parent=""  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Edit <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/promotion')}}">Promotion List</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Edit</a>
            </li>
        </ul>
    </div>
    <div class="portlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-reorder"></i>Edit Data
            </div>
        </div>
        <div class="portlet-body form">
            <form action="" id="form-promotion" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="alert alert-warning hide" id="alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Warning!</strong> <span></span>
                    </div>
                    <div class="alert alert-success hide" id="alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Successful!</strong> <span></span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Promotion Code <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="promotion_code" value="{{$resp['promotion_code']}}" placeholder="Promotion Code">
                            <input type="hidden" name="old_code" value="{{$resp['promotion_code']}}"/>
                            <input type="hidden" name="promotion_id" value="{{$resp['promotion_id']}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Promotion Name <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="promotion_name" value="{{$resp['promotion_name']}}" placeholder="Promotion Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-3 control-label">Active date <sup class="required" aria-required="true">* </sup></label>
                        <div class="col-md-4">
                            <div class="input-group  date-picker input-daterange" data-date="" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control" name="from" value="{{$resp['startdate']}}" autocomplete = "off">
                                <span class="input-group-addon">
                                to </span>
                                <input type="text" class="form-control" name="to" value="{{$resp['enddate']}}" autocomplete = "off">
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tag <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                    @if(!empty($resp['promotion_tag']))
                                        <img src="{{Config::get('app.root_path')}}/uploads/promotion/{{$resp['promotion_tag']}}" />
                                    @endif
                                </div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">
                                    Select image </span>
                                    <span class="fileinput-exists">
                                    Change </span>
                                    <input type="file" name="promotion_tag">
                                    </span>
                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                                    Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <select name="promotion_status" id="" class="form-control">
                                <option value="">Please Select</option>
                                <option value="A" {{($resp['promotion_status']=='A') ? 'selected':''}}>Active</option>
                                <option value="I" {{($resp['promotion_status']=='I') ? 'selected':''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="action" value="edit" id="hd-action" />
                            <input type="hidden" name="action_url" value="{{url('/action/promotion-edit')}}" id="hd-action-url" />
                            <button type="submit" class="btn btn-info" id="btn-submit"><i class="fa fa-circle-o-notch fa-spin loader hide"></i> Submit</button>
                            <a href="{{url('/promotion')}}" id="btn-back" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
@stop
@section('script')
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script src="{{Config::get('app.root_path')}}/resources/assets/scripts/promotion.js" type="text/javascript"></script>
@stop