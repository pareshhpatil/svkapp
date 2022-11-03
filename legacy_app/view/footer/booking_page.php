<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.js"></script>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>

<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>

<script src="/assets/global/plugins/holder.js" type="text/javascript"></script>


<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<link href="/assets/admin/pages/css/portfolio.css" rel="stylesheet" type="text/css"/>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- Form validation end -->




<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>


<!-- BEGIN PAGE LEVEL PLUGINS -->

<script type="text/javascript" src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script type="text/javascript" src="/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>



<script src="/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script src="/assets/admin/pages/scripts/form-validation.js?version=2"></script>


<!-- Form validation -->
<script src="/assets/admin/pages/scripts/form-wizard.js?version=2"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/admin/pages/scripts/ui-bootstrap-growl.js"></script>

<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/<?php echo $this->datatablejs; ?>.js"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>

<script src="/assets/admin/layout/scripts/booking.js<?php echo $this->fileTime('js', 'booking'); ?>" type="text/javascript"></script>

<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
   
</script>
<script>


    var sum_col = '<?php echo $this->sum_column; ?>';
    jQuery(document).ready(function() {

        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features

        FormValidation.init();
        FormWizard.init();
        ComponentsPickers.init();
        UIBootstrapGrowl.init();
        TableAdvanced.init();
        $('#column_name').multiselect({
            numberDisplayed: 2,
        });
        $('[data-toggle="popover"]').popover();
        
    });
</script>




<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>