
@extends('layouts.default')
@section('title')
แก้ไข {{$resp["detail"]["th"]["banner_name"]}}
@stop
@section('content')
    <div id="page-identity" data-menu="#slider-banner-menu" data-parent=""  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    แก้ไข {{$resp["detail"]["th"]["banner_name"]}} <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/banner-slider')}}">Banner Slider</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">เพิ่ม {{$resp["detail"]["th"]["banner_name"]}}</a>
            </li>
        </ul>
    </div>
    <div class="portlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-reorder"></i>Input Data
            </div>
        </div>
        <div class="portlet-body form">
            <form action="" id="form-banner-slider" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="alert alert-warning hide" id="alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Warning!</strong> <span></span>
                    </div>
                    <div class="alert alert-success hide" id="alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Successful!</strong> <span></span>
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
                                <label class="col-md-3 control-label">Title ({{$value->LanguageName}})<sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="banner_title_{{$value->LanguageCode}}" value="{{$resp['detail'][$value->LanguageCode]['banner_name']}}"  placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Image Desktop ({{$value->LanguageName}}) <sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 350px; height: 150px;">
                                        @if(!empty($resp['detail'][$value->LanguageCode]['banner_img']))
                                            <img src="{{Config::get('app.root_path')}}/uploads/banner/{{$resp['detail'][$value->LanguageCode]['banner_img']}}" />
                                        @endif
                                        </div>
                                        <div>
                                            <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                            Select image </span>
                                            <span class="fileinput-exists">
                                            Change </span>
                                            <input type="file" name="banner_{{$value->LanguageCode}}">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                                            Remove </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Image Mobile ({{$value->LanguageName}}) <sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 350px; height: 150px;">
                                        @if(!empty($resp['detail'][$value->LanguageCode]['banner_img_mb']))
                                            <img src="{{Config::get('app.root_path')}}/uploads/banner/{{$resp['detail'][$value->LanguageCode]['banner_img_mb']}}" />
                                        @endif
                                        </div>
                                        <div>
                                            <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                            Select image </span>
                                            <span class="fileinput-exists">
                                            Change </span>
                                            <input type="file" name="banner_mb_{{$value->LanguageCode}}">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                                            Remove </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="banner_id" value="{{$resp['id']}}" />
                    <div class="form-group">
                        <label class="col-md-3 control-label">Link Url </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="banner_link" value="{{$resp['banner_link']}}"  placeholder="Link Url">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <select name="banner_status" id="" class="form-control">
                                <option value="">Please Select</option>
                                <option value="A" {{($resp['banner_status']=='A') ? 'selected':''}}>Active</option>
                                <option value="I" {{($resp['banner_status']=='I') ? 'selected':''}}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="action" value="edit" id="hd-action" />
                            <input type="hidden" name="action_url" value="{{url('/action/banner-slider-edit')}}" id="hd-action-url" />
                            <button type="submit" class="btn btn-info" id="btn-submit"><i class="fa fa-circle-o-notch fa-spin loader hide"></i> Submit</button>
                            <a href="{{url('/banner-slider')}}" id="btn-back" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section('script')
   <script src="{{Config::get('app.root_path')}}/resources/assets/scripts/banner.js" type="text/javascript"></script>
@stop
