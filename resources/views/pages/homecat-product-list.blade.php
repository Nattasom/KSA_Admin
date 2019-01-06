@extends('layouts.default')
@section('title')
Category Product List
@stop
@section('content')
    <div id="page-identity" data-menu="#homecat-menu" data-parent="#content-group"  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    กรุงศรีเลือกให้ <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/homecat')}}">กรุงศรีเลือกให้</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Product 's {{$resp['detail']['th']['cat_name']}}</a>
            </li>
        </ul>
    </div>
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
                        <!-- <div class="btn-group">
                            <a href="{{url('/homecat-add')}}" id="" class="btn btn-success">
                             <i class="fa fa-plus"></i> Add Category
                            </a>
                        </div> -->
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
                <table class="table table-bordered" id="tb-product-list">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รูป</th>
                            <th>ชื่อประกัน</th>
                            <th>ยี่ห้อรถ</th>
                            <th>รุ่นรถ</th>
                            <th>ทุนประกัน</th>
                            <th>เบี้ยประกัน</th>
                            <th>ประเภทประกัน</th>
                            <th>เรียง</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <input type="hidden" name="cat_id" id="hd-cat-id" value="{{$resp['id']}}" />
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
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
  <!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{Config::get('app.root_path')}}/resources/assets/scripts/insurer.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {   
        var ajTable = jQuery("#tb-product-list").dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{url('/datatable/homecat-product-list')}}",
                "type": "POST",
                "data": function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.cat_id = $("#hd-cat-id").val();
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
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }],
            "order": [] 
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
        $('#tb-product-list').DataTable().ajax.reload();
    }
    // function setActive(action,code){
    //     if(confirm("Are you sure to change status this item ?")){
    //         var params = {};
    //         params.status = action;
    //         params.code = code;
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //         $.ajax({
    //             url: "{{url('/action/homecat-status')}}",
    //             type: "POST",
    //             data: params,
    //             success: function (result) {
    //                 reloadTable();
    //             }
    //         }).always(function () {
    //         });
    //     }
    // }
</script>
@stop