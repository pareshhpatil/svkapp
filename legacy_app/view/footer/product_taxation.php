<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.js"></script>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/admin/pages/scripts/form-validation.js<?php echo $this->fileTime('new', 'assets/admin/pages/scripts/form-validation.js'); ?>"></script>
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/form-wizard.js<?php echo $this->fileTime('new', 'assets/admin/pages/scripts/form-wizard.js'); ?>"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-mixitup/jquery.mixitup.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/template-validation.js<?php echo $this->fileTime('new', 'assets/admin/pages/scripts/template-validation.js'); ?>"></script>


<!-- END PAGE LEVEL PLUGINS -->

<script>
    jQuery(document).ready(function() {
        $(".iframe").colorbox({
            iframe: true,
            width: "80%",
            height: "90%"
        });
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        FormValidation.init();
        FormWizard.init();
        ComponentsPickers.init();
    });

    $('#product_taxation_type').select2({
        selectOnClose: true,
        templateResult: formatState
    });

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        if (state.id == '1') {
            var desc = 'Product cost entered as ₹1000 @ 18% GST tax calculated to 180 & total cost set to 1180';
        } else if (state.id == '2') {
            var desc = 'Product cost entered as ₹1000 @ 18% GST tax back calculated to 152.54 & product base cost set to 847.46';
        } else {
            var desc = '';
        }

        var $state = $(
            '<div><b>' + state.text + '</b><p style="margin-bottom: 0px;"> ' + desc + '</p>'
        );
        return $state;
    };
</script>
<?php if (isset($this->category_id)) { ?>
    <script>
        setMerchantDays(<?php echo $this->category_id; ?>, '<?php echo $this->booking_date; ?>');
    </script>
<?php } ?>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>