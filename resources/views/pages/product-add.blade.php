@extends('layouts.default')
@section('title')
Product Add
@stop
@section('content')
    <div id="page-identity" data-menu="#product-menu" data-parent="#content-group"  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Product Add <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/product')}}">Product List</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Product Add</a>
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
            <form action="" id="form-product" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="alert alert-warning hide" id="alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Warning!</strong> <span></span>
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
                                <label class="col-md-3 control-label">Product Name ({{$value->LanguageName}})<sup class="required">*</sup></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="product_name_{{$value->LanguageCode}}"  placeholder="Product Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 1 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                    <textarea class="ckeditor form-control" name="editor1_{{$value->LanguageCode}}" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 2 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                    <textarea class="ckeditor form-control" name="editor2_{{$value->LanguageCode}}" rows="6"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 3 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                    <textarea class="ckeditor form-control" name="editor3_{{$value->LanguageCode}}" rows="6"></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Promotion</label>
                        <div class="col-md-4">
                            <select name="promotion" id="" class="form-control">
                                <option value="">Select Promotion</option>
                                @foreach($promotion_list as $key=>$value)
                                <option value="{{$value->PromotionID}}">{{$value->PromotionName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">บริษัทประกัน </label>
                        <div class="col-md-4">
                             <select name="filter_insurer" id="filter_insurer" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($insurers as $key=>$value)
                                    <option value="{{$value->InsurerCode}}">{{$value->InsurerName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ยี่ห้อรถ </label>
                        <div class="col-md-4">
                             <select name="filter_makevalue" onchange="loadModelValue(this)" id="filter_makevalue" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($cars as $key=>$value)
                                <option value="{{$value->MakeValue}}">{{$value->MakeValueName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">รุ่นรถ </label>
                        <div class="col-md-4">
                             <select name="filter_modelvalue" id="filter_modelvalue" class="form-control">
                            <option value="" first-data="1">ทั้งหมด</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ประเภทประกัน </label>
                        <div class="col-md-4">
                             <select name="filter_producttype" id="filter_producttype" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($product_type as $key => $value)
                                    <option value="{{$value->ProductType}}">{{$value->ProductTypeDescription}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ประเภทซ่อม </label>
                        <div class="col-md-4">
                             <select name="filter_claimtype" id="filter_claimtype" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($claim_type as $key=>$value)
                                <option value="{{$value->ClaimTypeValue}}">{{$value->Description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">กลุ่มรถ </label>
                        <div class="col-md-4">
                             <select name="filter_cargroup" id="filter_cargroup" class="form-control">
                                <option value="">ทั้งหมด</option>
                                @foreach($car_group as $key=>$value)
                                    <option value="{{$value->CarGroup}}">{{$value->CarGroup}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ทุนประกันเริ่มต้น </label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="insured_min"  placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">ทุนประกันสิ้นสุด </label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="insured_max"  placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">เบี้ยประกันเริ่มต้น </label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="premium_min"  placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">เบี้ยประกันสิ้นสุด </label>
                        <div class="col-md-4">
                            <input type="number" class="form-control" name="premium_max"  placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Status <sup class="required">*</sup></label>
                        <div class="col-md-4">
                            <select name="status" id="" class="form-control">
                                <option value="">Please Select</option>
                                <option value="A">Active</option>
                                <option value="I">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="action" value="add" id="hd-action" />
                            <input type="hidden" name="action_url" value="{{url('/action/product-add')}}" id="hd-action-url" />
                            <button type="submit" class="btn btn-info" id="btn-submit"><i class="fa fa-circle-o-notch fa-spin loader hide"></i> Submit</button>
                            <a href="{{url('/product')}}" id="btn-back" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
@stop
@section('script')
    <script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/ckeditor/ckeditor.js"></script>
   <script src="{{Config::get('app.root_path')}}/resources/assets/scripts/product.js" type="text/javascript"></script>
   <script>
       function loadModelValue(element) {
            var params = {};
            params.makevalue = $(element).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('/data/loadmodelvalue')}}",
                type: "POST",
                data: params,
                success: function (result) {
                    console.log(result);
                    $("#filter_modelvalue > option:not([first-data])").remove();
                    $.each(result, function (k, v) {
                        $("#filter_modelvalue").append('<option value="' + v.ModelValue + '" data-cc="' + v.CC + '">' + v.ModelValue + ' (' + v.CC + ' CC)</option>');
                    });
                }
            }).always(function () {
            });
        }
    </script>
@stop