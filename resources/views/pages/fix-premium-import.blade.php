@extends('layouts.default')
@section('title')
Premium
@stop
@section('content')
    <div id="page-identity" data-url="dashboard" data-parent=""  data-parent2=""></div>
     <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
    Fix Premium Import<small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{url('/dashboard')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{url('/premium')}}">Fix Premium</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">จัดการไฟล์</a>
            </li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">Upload</a>
        </li>
        <li>
            <a href="#tab_2" data-toggle="tab">Processing</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active in" id="tab_1">
            <!-- FILTER ZONE -->
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-upload"></i>Upload File
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="alert-upload" class="alert alert-danger display-hide">
                        <strong>Error!</strong> <span></span>
                    </div>
                    @if($status == "01")
                        <div class="alert alert-success">
                            <strong>Successful!</strong> Import successful
                        </div>
                    @endif
                    <form action="" id="form-upload" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label">Insurer <sup class="required" aria-required="true">* </sup></label>
                                    <select name="insurer" id="upload_insurer" class="form-control">
                                        <option value="">Select Insurer</option>
                                        @foreach($insurers as $key=>$value)
                                            <option value="{{$value->InsurerCode}}">{{$value->InsurerName}}</option>
                                        @endforeach
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label">Upload File (*.csv) <sup class="required" aria-required="true">* </sup></label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="input-group">
                                            <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename">
                                                </span>
                                            </div>
                                            <span class="input-group-addon btn default btn-file">
                                            <span class="fileinput-new">
                                            Select file </span>
                                            <span class="fileinput-exists">
                                            Change </span>
                                            <input type="file" name="file_upload" accept="text/csv">
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-danger fileinput-exists" data-dismiss="fileinput">
                                            Remove </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 upload-group"></div>
                            <div class="col-md-3 upload-group text-center">
                                <div class="form-group">
                                    <label for="" class="control-label col-sm-12">&nbsp;</label>
                                    <button class="btn btn-primary" id="btn-upload"><i class="fa fa-upload"></i> Upload</button>
                                    <span id="upload-loader" class="display-hide"><i class="fa fa-circle-o-notch fa-spin"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <form id="form-import" action="" class="hide" method="post" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label">Insurer </label>
                                    <div id="lbl-insurer-name" >

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label">Filename </label>
                                    <div id="lbl-file-name" >

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="control-label">Fill active date <sup class="required" aria-required="true">* </sup></label>
                                    <div class="input-group  date-picker input-daterange" data-date="" data-date-format="dd/mm/yyyy">
                                        <input type="text" class="form-control" name="from">
                                        <span class="input-group-addon">
                                        to </span>
                                        <input type="text" class="form-control" name="to">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-1 upload-group"></div>
                            <div class="col-md-3 upload-group text-center">
                                <div class="form-group">
                                    <label for="" class="control-label col-sm-12"><span id="lbl-record"></span></label>
                                    <button type="submit" class="btn btn-success" id="btn-import"><i class="fa fa-check"></i> Import</button>
                                    <span id="import-loader" class="display-hide"><i class="fa fa-circle-o-notch fa-spin"></i></span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="insurer" value="" id="hd_import_insurer" />
                        <input type="hidden" name="file_name" value="" id="hd_import_filename" />
                    </form>

                </div>
            </div>
            <!-- END FILTER ZONE -->
        </div>
        <div class="tab-pane" id="tab_2">
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list"></i>Processing list
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tb-fix-premium-process">
                            <thead>
                                <tr>
                                    <th>วันที่อัพโหลด</th>
                                    <th>ชื่อไฟล์</th>
                                    <th>บริษัทประกัน</th>
                                    <th>จำนวนทั้งหมด</th>
                                    <th>จำนวนที่ผ่าน</th>
                                    <th>จำนวนที่ต้องแก้ไข</th>
                                    <th>สถานะ</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


             
        </div>
    </div>


    

    <!-- TABLE ZONE  -->
    <!-- <div id="table-zone" class="portlet display-hide">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-reorder"></i>Data
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive table-overflow-x">
                <table class="table table-bordered table-advance table-hover" id="tb-import">
                    <thead>
                        <tr>
                            <th>MakeValue</th>
                            <th>ModelValue</th>
                            <th>MotorType</th>
                            <th>SumInsured</th>
                            <th>ClaimTypeValue</th>
                            <th>CC</th>
                            <th>Seat</th>
                            <th>Weight</th>
                            <th>AgeDriver</th>
                            <th>AgeCar</th>
                            <th>AgeCarMax</th>
                            <th>BasePremium</th>
                            <th>MainPremium</th>
                            <th>EndorsePremium01</th>
                            <th>EndorsePremium02</th>
                            <th>EndorsePremium03</th>
                            <th>DriverDiscountRate</th>
                            <th>DriverDiscount</th>
                            <th>GroupDiscountRate</th>
                            <th>GroupDiscountAmt</th>
                            <th>HistoryDiscountRate</th>
                            <th>HistoryDiscountAmt</th>
                            <th>OtherDiscountRate</th>
                            <th>OtherDiscountAmt</th>
                            <th>PlusPremium1</th>
                            <th>PlusPremium2</th>
                            <th>NetPremium</th>
                            <th>Stamp</th>
                            <th>VAT</th>
                            <th>TotalPremium</th>
                            <th>DeductAmt</th>
                            <th>FIRE&THEFT</th>
                            <th>TPPI_P</th>
                            <th>TPPI_C</th>
                            <th>TPPD</th>
                            <th>BailBond</th>
                            <th>PA_Driver</th>
                            <th>PA_Passengers</th>
                            <th>MED</th>
                            <th>CommissionRate</th>
                            <th>PromotionDiscountRate</th>
                            <th>PromotionDiscountAmt</th>
                            <th>PromotionEffectiveDate</th>
                            <th>PromotionExpiryDate</th>
                            <th>CarGroup</th>
                            <th>ProductType</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
    <!-- END TABLE ZONE -->
    <div id="import-load-panel" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Importing...</h4>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            <span class="sr-only">
                             </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link rel="stylesheet" type="text/css" href="resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
  <!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="resources/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="resources/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script type="text/javascript" src="resources/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="resources/assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="resources/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>
    jQuery(document).ready(function() {
        jQuery("#tb-fix-premium-process").dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{url('/datatable/premium-processing')}}",
                "type": "POST",
                "data": function ( d ) {
                    d._token = $('meta[name="csrf-token"]').attr('content');
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
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
        $("#form-upload").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                insurer: {
                    required: true
                },
                file_upload: {
                    required: true,
                    filesize_max: 30000  //30 mb
                }
            },

            messages: {
                insurer: {
                    required: "Please choose Insurer"
                },
                file_upload: {
                    required: "Please upload file"
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                if($(element).attr("type")=="file"){
                        error.insertAfter(element.closest('.input-group'));
                }else{
                    error.insertAfter(element.closest('.form-control')); 
                }
                
            },
            submitHandler: function (form,event) {
                event.preventDefault();
                var formData = new FormData(form);
                var $btnUpload = $("#btn-upload");
                var $btnImport = $("#btn-import");
                var $loader = $("#upload-loader");
                $btnUpload.prop("disabled",true);
                $loader.removeClass("display-hide");
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            console.log(percentComplete);

                            if (percentComplete === 100) {

                            }

                        }
                        }, false);

                        return xhr;
                    },
                    url: "{{url('/ajax/premium-upload')}}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType:false,
                    success: function(result) {
                        console.log(result);
                        
                        if(result.status=="01"){ //success
                            window.location.href += "#tab_2";
                            window.location.reload();
                            // $btnUpload.addClass("display-hide");
                            // $(form).addClass("hide");
                            // $("#form-import").removeClass("hide");
                            // // $(form).find("select").prop("disabled",true);
                            // // $(form).find(".fileinput").hide();
                            // $("#lbl-file-name").removeClass("display-hide");
                            // $("#lbl-file-name").text(result.file_name);
                            // $("#lbl-record").text(result.records+" records");
                            // $("#lbl-insurer-name").text($("#upload_insurer").val());
                            // $("#hd_import_insurer").val($("#upload_insurer").val());
                            // $("#hd_import_filename").val(result.file_name);
                            //uploadSuccess(result);
                        }else{
                            $("#alert-upload").removeClass("display-hide");
                            $("#alert-upload").find("span").text(result.message);
                        }
                        
                    }
                    }).always(function(){
                        $btnUpload.prop("disabled",false);
                        $loader.addClass("display-hide");
                    });
                //form.submit();
            }
        });

        $("#form-import").validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                from: {
                    required: true
                },
                to: {
                    required: true
                }
            },

            messages: {
                insurer: {
                    required: "Please choose Insurer"
                },
                file_upload: {
                    required: "Please upload file"
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                // if($(element).attr("name")=="from" || $(element).attr("name")=="to"){
                //         error.insertAfter(element.closest('.input-group'));
                // }else{
                    
                // }
                error.insertAfter(element.closest('.form-control')); 
            },
            submitHandler: function (form,event) {
                event.preventDefault();
                var $btnImport = $("#btn-import");
                var $loader = $("#import-loader");

                if(confirm("Are you sure to import file '"+$("#hd_import_filename").val()+"' ?")){
                    
                    $btnImport.prop("disabled",true);
                    $loader.removeClass("display-hide");
                    $("#import-load-panel").modal("show");
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        xhr: function()
                        {
                            var xhr = new window.XMLHttpRequest();
                            //Upload progress
                            xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                //Do something with upload progress
                                console.log("up = "+percentComplete);
                                // $("#import-load-panel").find(".progress-bar").css("width",percentComplete+"%");
                            }
                            }, false);
                            //Download progress
                            xhr.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                //Do something with download progress
                                console.log("down = "+percentComplete);
                            }
                            }, false);
                            return xhr;
                        },
                        url: "{{url('/ajax/premium-import')}}",
                        type: "POST",
                        data: $(form).serialize(),
                        success: function(result) {
                            // console.log(result);
                            
                            if(result.status=="01"){ //success
                                location.reload();
                                //window.location = "{{url('/premium')}}?ins="+$("#hd_import_insurer").val();
                            }else{
                                $("#alert-upload").removeClass("display-hide");
                                $("#alert-upload").find("span").text(result.message);
                            }
                        }
                        }).always(function(){
                            $("#import-load-panel").modal("hide");
                            $btnImport.prop("disabled",false);
                            $loader.addClass("display-hide");
                        });
                }
            }
        });
    });
    function uploadSuccess(result){
        var $tb = $("#tb-import").find("tbody");
        
        $tb.html("");
        // $.each(result.read_list,function(k,v){
        //     var html = "";
        //     html += "<tr>";
        //     html += "   <td>"+v.MakeValue+"</td>";
        //     html += "   <td>"+v.ModelValue+"</td>";
        //     html += "   <td>"+v.MotorType+"</td>";
        //     html += "   <td>"+v.SumInsured+"</td>";
        //     html += "   <td>"+v.ClaimTypeValue+"</td>";
        //     html += "   <td>"+v.CC+"</td>";
        //     html += "   <td>"+v.Seat+"</td>";
        //     html += "   <td>"+v.Weight+"</td>";
        //     html += "   <td>"+v.AgeDriver+"</td>";
        //     html += "   <td>"+v.AgeCar+"</td>";
        //     html += "   <td>"+v.AgeCarMax+"</td>";
        //     html += "   <td>"+v.BasePremium+"</td>";
        //     html += "   <td>"+v.MainPremium+"</td>";
        //     html += "   <td>"+v.EndorsePremium01+"</td>";
        //     html += "   <td>"+v.EndorsePremium02+"</td>";
        //     html += "   <td>"+v.EndorsePremium03+"</td>";
        //     html += "   <td>"+v.DriverDiscountRate+"</td>";
        //     html += "   <td>"+v.DriverDiscount+"</td>";
        //     html += "   <td>"+v.GroupDiscountRate+"</td>";
        //     html += "   <td>"+v.GroupDiscountAmt+"</td>";
        //     html += "   <td>"+v.HistoryDiscountRate+"</td>";
        //     html += "   <td>"+v.HistoryDiscountAmt+"</td>";
        //     html += "   <td>"+v.OtherDiscountRate+"</td>";
        //     html += "   <td>"+v.OtherDiscountAmt+"</td>";
        //     html += "   <td>"+v.PlusPremium1+"</td>";
        //     html += "   <td>"+v.PlusPremium2+"</td>";
        //     html += "   <td>"+v.NetPremium+"</td>";
        //     html += "   <td>"+v.Stamp+"</td>";
        //     html += "   <td>"+v.VAT+"</td>";
        //     html += "   <td>"+v.TotalPremium+"</td>";
        //     html += "   <td>"+v.DeductAmt+"</td>";
        //     html += "   <td>"+v["FIRE&THEFT"]+"</td>";
        //     html += "   <td>"+v.TPPI_P+"</td>";
        //     html += "   <td>"+v.TPPI_C+"</td>";
        //     html += "   <td>"+v.TPPD+"</td>";
        //     html += "   <td>"+v.Bail_Bond+"</td>";
        //     html += "   <td>"+v.PA_Driver+"</td>";
        //     html += "   <td>"+v.PA_Passengers+"</td>";
        //     html += "   <td>"+v.MED+"</td>";
        //     html += "   <td>"+v.CommissionRate+"</td>";
        //     html += "   <td>"+v.PromotionDiscountRate+"</td>";
        //     html += "   <td>"+v.PromotionDiscountAmt+"</td>";
        //     html += "   <td>"+v.PromotionEffectiveDate+"</td>";
        //     html += "   <td>"+v.PromotionExpiryDate+"</td>";
        //     html += "   <td>"+v.CarGroup+"</td>";
        //     html += "   <td>"+v.ProductType+"</td>";
        //     html += "</tr>";
        //     $tb.append(html);
        // });
    }
</script>
@stop