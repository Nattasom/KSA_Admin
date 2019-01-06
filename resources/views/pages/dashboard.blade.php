@extends('layouts.default')
@section('title')
Dashboard
@stop
@section('content')
<div id="page-identity" data-menu="#dashboard-menu" data-parent=""  data-parent2=""></div>
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
     <small></small>
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="index.html">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#"></a>
            </li>
        </ul>
    </div>
    <!-- END PAGE HEADER-->
    <div class="clearfix">
    </div>
		
@stop
@section('script')
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="resources/assets/scripts/index.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Index.init();
//    Index.initJQVMAP(); // init index page's custom scripts
//    Index.initCalendar(); // init index page's custom scripts
//    Index.initCharts(); // init index page's custom scripts
//    Index.initChat();
//    Index.initMiniCharts();
//    Index.initPeityElements();
//    Index.initKnowElements();
//    Index.initDashboardDaterange();
});
</script>
<!-- END JAVASCRIPTS -->
@stop