@extends('layouts.default')
@section('title')
Homecat Product Edit
@stop
@section('content')
    <div id="page-identity" data-menu="#homecat-menu" data-parent="#content-group"  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Edit  {{$resp['detail']['th']['product_name']}}<small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/homecat')}}">Homecat List</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Edit {{$resp['detail']['th']['product_name']}}</a>
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
            <form action="" id="form-homecat-product" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="alert alert-warning hide" id="alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Warning!</strong> <span></span>
                    </div>
                    <div class="alert alert-success hide" id="alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Successful!</strong> <span></span>
                    </div>
                    <input type="hidden" name="old_id" value="{{$resp['id']}}" />
                    <div class="text-right">
                        <a data-toggle="modal" href="#wide" class="btn btn-info btn-xs btn-view" data-idx="{{$resp['premium_idx']}}">Premium Detail</a>
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
                                    <input type="text" class="form-control" name="product_name_{{$value->LanguageCode}}" value="{{$resp['detail'][$value->LanguageCode]['product_name']}}"  placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 1 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                <textarea class="form-control ckeditor" name="editor1_{{$value->LanguageCode}}">{{$resp['detail'][$value->LanguageCode]['product_desc_1']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 2 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                    <textarea class="form-control ckeditor" name="editor2_{{$value->LanguageCode}}">{{$resp['detail'][$value->LanguageCode]['product_desc_2']}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Product Detail 3 ({{$value->LanguageName}})</label>
                                <div class="col-md-7">
                                    <textarea class="form-control ckeditor" name="editor3_{{$value->LanguageCode}}">{{$resp['detail'][$value->LanguageCode]['product_desc_3']}}</textarea>
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
                                <option value="{{$value->PromotionID}}" {{($resp['promotion']==$value->PromotionID) ? 'selected':''}}>{{$value->PromotionName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Image </label>
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                    @if(!empty($resp['image']))
                                        <img src="{{Config::get('app.root_path')}}/uploads/home/{{$resp['image']}}"  />
                                    @endif
                                </div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">
                                    Select image </span>
                                    <span class="fileinput-exists">
                                    Change </span>
                                    <input type="file" name="product_image">
                                    </span>
                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                                    Remove </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="action" value="product-edit" id="hd-action" />
                            <input type="hidden" name="action_url" value="{{url('/action/homecat-product-edit')}}" id="hd-action-url" />
                            <button type="submit" class="btn btn-info" id="btn-submit"><i class="fa fa-circle-o-notch fa-spin loader hide"></i> Submit</button>
                            <a href="{{url()->previous()}}" id="btn-back" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="wide" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-wide">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">รายละเอียดประกัน</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-info col-xs-3">บริษัทประกัน</td>
                                <td><span id="lbl-pop-insurer-code"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ยี่ห้อรถ</td>
                                <td><span id="lbl-pop-makevalue"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">รุ่นรถ</td>
                                <td><span id="lbl-pop-modelvalue"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">รหัสรถ</td>
                                <td><span id="lbl-pop-motor"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ทุนประกัน</td>
                                <td><span id="lbl-pop-suminsured"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ประเภทซ่อม</td>
                                <td><span id="lbl-pop-claimtype"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">CC</td>
                                <td><span id="lbl-pop-cc"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ที่นั่ง</td>
                                <td><span id="lbl-pop-seat"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">น้ำหนัก</td>
                                <td><span id="lbl-pop-weight"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">อายุผู้ขับขี่</td>
                                <td><span id="lbl-pop-agedriver"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">อายุรถรับประกันเริ่มต้นสำหรับเบี้ย</td>
                                <td><span id="lbl-pop-agecar"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">อายุรถรับประกันสิ้นสุดสำหรับเบี้ย</td>
                                <td><span id="lbl-pop-agecarmax"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เบี้ยพื้นฐาน</td>
                                <td><span id="lbl-pop-base-premium"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เบี้ยหลัก</td>
                                <td><span id="lbl-pop-main-premium"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เบี้ยไม่รวมอากรและภาษีมูลค่าเพิ่ม</td>
                                <td><span id="lbl-pop-net-premium"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ภาษีมูลค่าเพิ่ม</td>
                                <td><span id="lbl-pop-vat"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เบี้ยรวม (Gross premium)</td>
                                <td><span id="lbl-pop-total-premium"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ค่าความเสียหายส่วนแรก</td>
                                <td><span id="lbl-pop-deduct"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">สูญหายไฟไหม้</td>
                                <td><span id="lbl-pop-fire"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ความรับผิดต่อบุคคลภายนอก/คน</td>
                                <td><span id="lbl-pop-tppi-p"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ความรับผิดต่อบุคคลภายนอก/ครั้ง</td>
                                <td><span id="lbl-pop-tppi-c"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ความคุ้มครองต่อทรัพย์สินบุคคลภายนอก</td>
                                <td><span id="lbl-pop-tppd"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">กลุ่มรถ</td>
                                <td><span id="lbl-pop-cargroup"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ประเภทประกัน</td>
                                <td><span id="lbl-pop-producttype"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">วันที่เริ่มต้น</td>
                                <td><span id="lbl-pop-startdate"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">วันที่สิ้นสุด</td>
                                <td><span id="lbl-pop-enddate"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" id="hd-idx-premium" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@stop
@section('script')
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/ckeditor/ckeditor.js"></script>
   <script src="{{Config::get('app.root_path')}}/resources/assets/scripts/homecat.js" type="text/javascript"></script>
   <script>
       $(document).ready(function(){
           $("#wide").on("shown.bs.modal",function(){
                console.log($("#hd-idx-premium").val());
                var params = {};
                params.idx = $("#hd-idx-premium").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('/data/getpremium')}}",
                    type: "POST",
                    data: params,
                    success: function (result) {
                        console.log(result);
                        $("#lbl-pop-insurer-code").text(result.InsurerCode);
                        $("#lbl-pop-makevalue").text(result.MakeValue);
                        $("#lbl-pop-modelvalue").text(result.ModelValue);
                        $("#lbl-pop-motor").text(result.MotorType);
                        $("#lbl-pop-suminsured").text(result.SumInsured);
                        $("#lbl-pop-claimtype").text(result.ClaimTypeValue);
                        $("#lbl-pop-cc").text(result.CC);
                        $("#lbl-pop-seat").text(result.Seat);
                        $("#lbl-pop-weight").text(result.Weight);
                        $("#lbl-pop-agedriver").text(result.AgeDriver);
                        $("#lbl-pop-agecar").text(result.AgeCar);
                        $("#lbl-pop-agecarmax").text(result.AgeCarMax);
                        $("#lbl-pop-base-premium").text(result.BasePremium);
                        $("#lbl-pop-main-premium").text(result.MainPremium);
                        $("#lbl-pop-net-premium").text(result.NetPremium);
                        $("#lbl-pop-vat").text(result.VAT);
                        $("#lbl-pop-total-premium").text(result.TotalPremium);
                        $("#lbl-pop-deduct").text(result.DeductAmt);
                        $("#lbl-pop-fire").text(result.FIRE_THEFT);
                        $("#lbl-pop-tppi-p").text(result.TPPI_P);
                        $("#lbl-pop-tppi-c").text(result.TPPI_C);
                        $("#lbl-pop-tppd").text(result.TPPD);
                        $("#lbl-pop-cargroup").text(result.CarGroup);
                        $("#lbl-pop-producttype").text(result.ProductType);
                        $("#lbl-pop-startdate").text(result.StartDate);
                        $("#lbl-pop-enddate").text(result.EndDate);


                        $(".numbers").digits();
                    }
                }).always(function () {
                });
            });
            $("#wide").on("hide.bs.modal",function(){
                $("#lbl-pop-insurer-code").text('');
                $("#lbl-pop-makevalue").text('');
                $("#lbl-pop-modelvalue").text('');
                $("#lbl-pop-motor").text('');
                $("#lbl-pop-suminsured").text('');
                $("#lbl-pop-claimtype").text('');
                $("#lbl-pop-cc").text('');
                $("#lbl-pop-seat").text('');
                $("#lbl-pop-weight").text('');
                $("#lbl-pop-agedriver").text('');
                $("#lbl-pop-agecar").text('');
                $("#lbl-pop-agecarmax").text('');
                $("#lbl-pop-base-premium").text('');
                $("#lbl-pop-main-premium").text('');
                $("#lbl-pop-net-premium").text('');
                $("#lbl-pop-vat").text('');
                $("#lbl-pop-total-premium").text('');
                $("#lbl-pop-deduct").text('');
                $("#lbl-pop-fire").text('');
                $("#lbl-pop-tppi-p").text('');
                $("#lbl-pop-tppi-c").text('');
                $("#lbl-pop-tppd").text('');
                $("#lbl-pop-cargroup").text('');
                $("#lbl-pop-producttyoe").text('');
                $("#lbl-pop-startdate").text('');
                $("#lbl-pop-enddate").text('');
            });
            $(document).on("click",".btn-view",function(){
                $("#hd-idx-premium").val($(this).attr("data-idx"));
            });

       });
   </script>
@stop