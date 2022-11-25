


<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/global/plugins/jquery.easing.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.parallax.js" type="text/javascript"></script>
<script src="/assets/global/plugins/smooth-scroll/smooth-scroll.js" type="text/javascript"></script>
<script src="/assets/global/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script>
<!-- BEGIN Cubeportfolio -->
<script src="/assets/global/plugins/cubeportfolio/cubeportfolio/js/jquery.cubeportfolio.min.js" type="text/javascript"></script>
<script src="/assets/frontend/onepage2/scripts/portfolio.js" type="text/javascript"></script>
<!-- END Cubeportfolio -->
<!-- BEGIN RevolutionSlider -->  
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript"></script> 
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js" type="text/javascript"></script>
<!-- END RevolutionSlider -->
<!-- END PAGE LEVEL PLUGINS -->


<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/frontend/onepage2/scripts/layout.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/global/plugins/nouislider/nouislider.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var RevosliderInit = function () {
        $(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
        return {
            initRevoSlider: function () {
                var height = 395; // minimal height for medium resolution

                // smart height detection for all major screens

                jQuery('.banner').revolution({
                    delay: 1000,
                    startwidth: 1170,
                    startheight: height,
                    navigationArrows: "none",
                    fullWidth: "on",
                    fullScreen: "off",
                    touchenabled: "on", // Enable Swipe Function : on/off
                    onHoverStop: "on", // Stop Banner Timet at Hover on Slide on/off
                    fullScreenOffsetContainer: ""
                });
            }
        };

    }();
    jQuery(document).ready(function () {
        Layout.init();
        RevosliderInit.initRevoSlider();
        // initiate layout and plugins
        Swipez.init(); // init swipez core components
        Demo.init(); // init demo features
        ComponentsPickers.init();
    });

    //no ui slider javascript nounislider
    var vInvoiceSlider = document.getElementById('invoice_slider');
    var vEventSlider = document.getElementById('event_slider');



    noUiSlider.create(vInvoiceSlider, {
        start: <?php echo $this->inv_count; ?>,
        connect: [true, false],
        step: 100,
        range: {
            min: 0,
            max: 12000
        }
    });

    noUiSlider.create(vEventSlider, {
        start: <?php echo $this->booking_count; ?>,
        connect: [true, false],
        step: 100,
        range: {
            min: 0,
            max: 12000
        }
    });
</script>
<script>
    var vInvoiceSliderValue1 = document.getElementById('invoice-slider-value1');
    var vInvoiceSliderValue2 = document.getElementById('invoice-slider-value2');
    var vEventsSliderValue1 = document.getElementById('events-slider-value1');
    var vEventsSliderValue2 = document.getElementById('events-slider-value2');
    var frmEventsVal = document.getElementsByName('eventsVal');
    var frmInvoicesVal = document.getElementsByName('invoicesVal');
    var vInvoiceCost = 0;
    var vEventCost = 0;
    var vTotalCost = 0;

    vInvoiceSlider.noUiSlider.on('update', function (values, handle) {
        var vHandleValue = Number(values[handle]);
        vInvoiceSliderValue1.innerHTML = vHandleValue;
        document.getElementById('inv_count').value = vHandleValue;
        vInvoiceSliderValue2.innerHTML = vHandleValue;
        frmInvoicesVal.value = vHandleValue;
        vTotalCharges = calculateCharges(1, vHandleValue);
    });

    vEventSlider.noUiSlider.on('update', function (values, handle) {
        var vHandleValue = Number(values[handle]);
        document.getElementById('booking_count').value = vHandleValue;
        vEventsSliderValue1.innerHTML = vHandleValue;
        vEventsSliderValue2.innerHTML = vHandleValue;
        frmEventsVal.value = vHandleValue;
        vTotalCharges = calculateCharges(2, vHandleValue);
    });

    function calculateCharges(type, units)
    {
        var vTotalCostValue = document.getElementById('total-cost');
        //If invoice slider is changed
        if (type == 1) {
            if (units == 0) {
                vInvoiceCost = 0;
            } else if (units < 1001) {
                vInvoiceCost = 2360;
            } else if (units > 1000 && units < 2501) {
                vInvoiceCost = 3540;
            } else if (units > 2500 && units < 5001) {
                vInvoiceCost = 4130;
            } else if (units > 5000 && units < 7001) {
                vInvoiceCost = 4720;
            } else if (units > 7000 && units < 8501) {
                vInvoiceCost = 5310;
            } else if (units > 8500 && units < 10001) {
                vInvoiceCost = 5310;
            } else {
                vInvoiceCost = 5999;
            }
        } else if (type == 2) {
            if (units == 0) {
                vEventCost = 0;
            } else if (units < 1001) {
                vEventCost = 2360;
            } else if (units > 1000 && units < 2501) {
                vEventCost = 3540;
            } else if (units > 2500 && units < 5001) {
                vEventCost = 4130;
            } else if (units > 5000 && units < 7001) {
                vEventCost = 4720;
            } else if (units > 7000 && units < 8501) {
                vEventCost = 5310;
            } else if (units > 8500 && units < 10001) {
                vEventCost = 5310;
            } else {
                vEventCost = 5999;
            }
        }

        var vInvoiceCostValue = document.getElementById('invoice-cost');
        vInvoiceCostValue.innerHTML = vInvoiceCost;
        var vEventsCostValue = document.getElementById('events-cost');
        vEventsCostValue.innerHTML = vEventCost;

        vTotalCost = vInvoiceCost + vEventCost;
        if (vTotalCost > 0) {
            document.getElementById('submitbtn').disabled = false;
        } else
        {
            document.getElementById('submitbtn').disabled = true;
        }
        vTotalCostValue.innerHTML = vTotalCost;

        var vSMSNo = document.getElementById('sms-no');
        vSMSNo.innerHTML = Math.round(vTotalCost * 0.04167);
    }

</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>