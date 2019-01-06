@extends('layouts.default')
@section('title')
Insurer Edit
@stop
@section('content')
    <div id="page-identity" data-menu="#insurer-menu" data-parent="#master-group"  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Edit  {{$resp['insurer_code']}}<small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/master/insurer')}}">Insurer List</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Edit {{$resp['insurer_code']}}</a>
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
            <form action="" id="form-insurer" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                        <label class="col-md-3 control-label">Code <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="insurer_code" value="{{$resp['insurer_code']}}"  placeholder="Code">
                            <input type="hidden" name="old_code" value="{{$resp['insurer_code']}}"/>
                        </div>
                    </div>
                    <ul class="nav nav-tabs">
                        @foreach($lang as $key => $value)
                            <li class="{{($key==0) ? 'active':''}}">
								<a href="#tab_{{$key}}" data-toggle="tab">{{$value->LanguageName}}</a>
							</li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($lang as $key => $value)
                        <div class="tab-pane {{($key==0) ? 'active':''}}" id="tab_{{$key}}">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Name ({{$value->LanguageName}})<sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="insurer_name_{{$value->LanguageCode}}" value="{{$resp['detail'][$value->LanguageCode]['insurer_name']}}"  placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Short Name ({{$value->LanguageName}})<sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="insurer_shortname_{{$value->LanguageCode}}" value="{{$resp['detail'][$value->LanguageCode]['insurer_shortname']}}" placeholder="Short Name">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Logo <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                    @if(!empty($resp['insurer_image']))
                                        <img src="{{Config::get('app.root_path')}}/uploads/insurer/{{$resp['insurer_image']}}" />
                                    @endif
                                </div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">
                                    Select image </span>
                                    <span class="fileinput-exists">
                                    Change </span>
                                    <input type="file" name="insurer_logo">
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
                            <select name="insurer_status" id="" class="form-control">
                                <option value="A" {{($resp['insurer_status']=='A') ? 'selected':''}}>Active</option>
                                <option value="I" {{($resp['insurer_status']=='I') ? 'selected':''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="action" value="edit" id="hd-action" />
                            <input type="hidden" name="action_url" value="{{url('/action/insurer-edit')}}" id="hd-action-url" />
                            <input type="hidden" name="current_url" value="{{url('/master/insurer-edit')}}" id="hd-current-url" />
                            <button type="submit" class="btn btn-info" id="btn-submit"><i class="fa fa-circle-o-notch fa-spin loader hide"></i> Submit</button>
                            <a href="{{url('/master/insurer')}}" id="btn-back" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section('script')
   <script src="{{Config::get('app.root_path')}}/resources/assets/scripts/insurer.js" type="text/javascript"></script>
@stop