



<?php
include_once '../legacy_app/view/footer/footer_common.php';
?>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript" src="/assets/global/plugins/jquery-multi-select/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-multi-select/js/bootstrap-multiselect-collapsible-groups.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/admin/pages/scripts/table-advanced.js"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>

<script>


    jQuery(document).ready(function () {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        Swipez.init(); // init swipez core components
        //Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        ComponentsPickers.init();

    });
</script>
<!-- 1. Link Vue Javascript -->
<script src='https://unpkg.com/vue/dist/vue.js'></script>

<!-- 2. Link VCalendar Javascript (Plugin automatically installed) -->
<script src='https://unpkg.com/v-calendar'></script>
<!-- END JAVASCRIPTS -->
<script>
    new Vue({
        el: '#app',
        data: {
            // Data used by the date picker
            mode: 'single',
            selectedDate: null,
        }
    })
</script>
<?php if (isset($this->category_id)) {
    ?>
    <script>
        setMerchantDays(<?php echo $this->category_id; ?>, '<?php echo $this->select_booking_date; ?>');
    </script>
<?php } ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
