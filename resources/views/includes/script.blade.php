<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{Config::get('app.root_path')}}/resources/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/scripts/app.js" type="text/javascript"></script>
<script src="{{Config::get('app.root_path')}}/resources/assets/scripts/custom.js" type="text/javascript"></script>
<script>
    jQuery(document).ready(function() {    
            App.init(); // initlayout and core plugins
    });
    
</script>