@extends('layouts.default')
@section('title')
Premium
@stop
@section('content')
    <div id="page-identity" data-menu="#premium-menu" data-parent=""  data-parent2=""></div>
     <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Fix Premium <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Fix Premium</a>
            </li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <!-- FILTER ZONE -->
    <div class="portlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-search"></i>Filter
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">บริษัทประกัน</label>
                        <select name="filter_insurer" id="filter_insurer" class="form-control">
                            <option value="">ทั้งหมด</option>
                            @foreach($insurers as $key=>$value)
                                <option value="{{$value->InsurerCode}}">{{$value->InsurerName}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ยี่ห้อรถ</label>
                        <select name="filter_makevalue" id="filter_makevalue" onchange="loadModelValue(this)" class="form-control">
                            <option value="">ทั้งหมด</option>
                            @foreach($cars as $key=>$value)
                            <option value="{{$value->MakeValue}}">{{$value->MakeValueName}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">รุ่นรถ</label>
                        <select name="filter_modelvalue" id="filter_modelvalue" class="form-control">
                            <option value="" first-data="1">ทั้งหมด</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ประเภทประกัน</label>
                        <select name="filter_producttype" id="filter_producttype" class="form-control">
                            <option value="">ทั้งหมด</option>
                            @foreach($product_type as $key => $value)
                                <option value="{{$value->ProductType}}">{{$value->ProductTypeDescription}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ประเภทซ่อม</label>
                        <select name="filter_claimtype" id="filter_claimtype" class="form-control">
                            <option value="">ทั้งหมด</option>
                            @foreach($claim_type as $key=>$value)
                            <option value="{{$value->ClaimTypeValue}}">{{$value->Description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">กลุ่มรถ</label>
                        <select name="filter_cargroup" id="filter_cargroup" class="form-control">
                            <option value="">ทั้งหมด</option>
                            @foreach($car_group as $key=>$value)
                                <option value="{{$value->CarGroup}}">{{$value->CarGroup}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">เบี้ยเริ่มต้น</label>
                        <input type="number" class="form-control" id="filter_premium_start" name="filter_premium_start">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">เบี้ยสิ้นสุด</label>
                        <input type="number" class="form-control" id="filter_premium_end" name="filter_premium_end">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ทุนประกันเริ่มต้น</label>
                        <input type="number" class="form-control" id="filter_suminsured_start" name="filter_suminsured_start">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ทุนประกันสิ้นสุด</label>
                        <input type="number" class="form-control" id="filter_suminsured_end" name="filter_suminsured_end">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label">ค่าเสียหายส่วนแรก</label>
                        <input type="number" class="form-control" id="filter_deduct" name="filter_deduct">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-12">&nbsp;</label>
                        <button type="button" class="btn btn-primary" onclick="reloadTable()"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END FILTER ZONE -->

    <!-- TABLE ZONE  -->
    <div class="portlet">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-reorder"></i>Data
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <a href="{{url('/premium-import')}}" id="" class="btn btn-success">
                             <i class="fa fa-upload"></i> จัดการไฟล์
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- <div class="btn-group pull-right">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="#">
                                    Print </a>
                                </li>
                                <li>
                                    <a href="#">
                                    Save as PDF </a>
                                </li>
                                <li>
                                    <a href="#">
                                    Export to Excel </a>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tb-fix-premium">
                    <thead>
                        <tr>
                            <th>บริษัท</th>
                            <th>ประเภทประกัน</th>
                            <th>ยี่ห้อรถ</th>
                            <th>รุ่นรถ</th>
                            <th>ทุนประกัน</th>
                            <th>เบี้ยประกัน</th>
                            <th>ประเภทซ่อม</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END TABLE ZONE -->
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
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
  <!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="resources/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="resources/assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
    jQuery(document).ready(function() {   
        jQuery("#tb-fix-premium").dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{url('/datatable/premium')}}",
                "type": "POST",
                "data": function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.insurer = $("#filter_insurer").val();
                    d.makevalue = $("#filter_makevalue").val();
                    d.modelvalue = $("#filter_modelvalue").val();
                    d.producttype=$("#filter_producttype").val();
                    d.claimtype=$("#filter_claimtype").val();
                    d.cargroup=$("#filter_cargroup").val();
                    d.premium_start = $("#filter_premium_start").val();
                    d.premium_end = $("#filter_premium_end").val();
                    d.suminsured_start = $("#filter_suminsured_start").val();
                    d.suminsured_end = $("#filter_suminsured_end").val();
                    d.deduct = $("#filter_deduct").val();
                }
            },
             "columns": [{
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                },{
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }],
            "order": [],
            "searching": false
        });
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
    function reloadTable(){
        $('#tb-fix-premium').DataTable().ajax.reload();
    }
    function loadModelValue(element){
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
                $.each(result,function(k,v){
                    $("#filter_modelvalue").append('<option value="'+v.ModelValue+'" data-cc="'+v.CC+'">'+v.ModelValue+' ('+v.CC+' CC)</option>');
                });
            }
        }).always(function () {
        });
    }
</script>
@stop