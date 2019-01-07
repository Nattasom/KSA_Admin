@extends('layouts.default')
@section('title')
Drop Lead
@stop
@section('content')
    <div id="page-identity" data-menu="#droplead-menu" data-parent=""  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Drop Lead <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Drop Lead</a>
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
                        @if(in_array('EXPORT',Session::get('userinfo')->permission['page_6']['actions']))
                            <div class="form-inline">
                                <select name="" id="sl_type_export" class="form-control">
                                    <option value="">All</option>
                                    <option value="DEFAULT">Default</option>
                                    <option value="AYCAL">Aycal</option>
                                    <option value="ASB">ASB</option>
                                </select>
                                <a href="{{url('/droplead/export')}}" id="btn-export-excel" target="_blank" id="" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Export Excel
                                </a>
                            </div>
                        @endif
                        
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
            <div class="well">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Status</label>
                            <select name="" id="sl_drop_status" class="form-control">
                                <option value="">All</option>
                                <option value="N">Default</option>
                                <option value="S">Synced</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">Date</label>
                            <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
                                <input type="text" class="form-control" id="txt-search-start" name="from">
                                <span class="input-group-addon">
                                to </span>
                                <input type="text" class="form-control" id="txt-search-end" name="to">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">&nbsp;</label>
                            <div>
                                <button type="button" id="btn-search" class="btn btn-primary" >Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tb-droplead">
                    <thead>
                        <tr>
                            <th>Droplead Date</th>
                            <th>Name</th>
                            <th>ID Card</th>
                            <th>Gender</th>
                            <th>Make Value</th>
                            <th>Model Value</th>
                            <th>Mobile</th>
                            <th>Status</th>
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

    <div class="modal fade" id="dropdetail" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-wide">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Droplead Detail</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-info col-xs-3">ชื่อ</td>
                                <td><span id="lbl-pop-name"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เบอร์โทร</td>
                                <td><span id="lbl-pop-tel"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">อีเมล์</td>
                                <td><span id="lbl-pop-email"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เวลาติดต่อกลับ</td>
                                <td><span id="lbl-pop-callback"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">เวลาที่ส่ง droplead</td>
                                <td><span id="lbl-pop-drop"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">ยี่ห้อรถ</td>
                                <td><span id="lbl-pop-make"></span></td>
                            </tr>
                            <tr>
                                <td class="bg-info col-xs-3">รุ่นรถ</td>
                                <td><span id="lbl-pop-model"></span></td>
                            </tr>
                             <tr>
                                <td class="bg-info col-xs-3">CC</td>
                                <td><span id="lbl-pop-cc"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" id="hd-idx-droplead" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
@stop
@section('script')
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
  <!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
    jQuery(document).ready(function() {   
        var ajTable = jQuery("#tb-droplead").dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{url('/datatable/droplead')}}",
                "type": "POST",
                "data": function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
                    d.start_date  = $("#txt-search-start").val();
                    d.drop_status = $("#sl_drop_status").val();
                    d.end_date = $("#txt-search-end").val();
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
                }, 
                {
                    "orderable": false
                }, {
                    "orderable": false
                }, {
                    "orderable": false
                }, 
                {
                    "orderable": false
                }],
            "order": [] 
        });
        $("#btn-search").click(function(){
            $('#tb-droplead').DataTable().ajax.reload();
            if($('#btn-export-excel').length>0){
                $("#btn-export-excel").attr("href","{{url('/droplead/export')}}?export_type="+$("#sl_type_export").val()+"&drop_status="+$("#sl_drop_status").val()+"&start_date="+$("#txt-search-start").val()+"&end_date="+$("#txt-search-end").val());
            }
        });
        $(document).on("change","#sl_type_export",function(){
            if($('#btn-export-excel').length>0){
                $("#btn-export-excel").attr("href","{{url('/droplead/export')}}?export_type="+$("#sl_type_export").val()+"&drop_status="+$("#sl_drop_status").val()+"&start_date="+$("#txt-search-start").val()+"&end_date="+$("#txt-search-end").val());
            }
        });
        $('.date-picker').datepicker({
                rtl: App.isRTL(),
                autoclose: true
            });
        $("#dropdetail").on("shown.bs.modal",function(){
            var params = {};
            params.idx = $("#hd-idx-droplead").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('/data/getdroplead')}}",
                type: "POST",
                data: params,
                success: function (result) {
                    console.log(result);
                    $("#lbl-pop-name").text(result.TFirstName+' '+result.TLastName);
                    $("#lbl-pop-tel").text(result.Mobile);
                    $("#lbl-pop-email").text(result.Email);
                    $("#lbl-pop-callback").text(result.CallbackDateTime);
                    $("#lbl-pop-drop").text(result.DropDate);
                    $("#lbl-pop-make").text(result.Make);
                    $("#lbl-pop-model").text(result.Model);
                    $("#lbl-pop-cc").text(result.CC);

                    $(".numbers").digits();
                }
            }).always(function () {
            });
        });
        $("#dropdetail").on("hide.bs.modal",function(){
            $("#lbl-pop-name").text('');
            $("#lbl-pop-tel").text('');
            $("#lbl-pop-email").text('');
            $("#lbl-pop-callback").text('');
            $("#lbl-pop-drop").text('');
            $("#lbl-pop-make").text('');
            $("#lbl-pop-model").text('');
            $("#lbl-pop-cc").text('');
        });
        $(document).on("click",".btn-view",function(){
            $("#hd-idx-droplead").val($(this).attr("data-idx"));
        });
        
    });
    function reloadTable(){
        $('#tb-droplead').DataTable().ajax.reload();
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
    //             url: "{{url('/action/insurer-status')}}",
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