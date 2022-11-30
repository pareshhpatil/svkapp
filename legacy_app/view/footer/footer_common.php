<!-- BEGIN FOOTER -->
<!--
<?php// if ($this->source != 'APP') { ?>
    <div class="floating-back visible-xs">
        <i onclick="history.back(-1);" class="icon-arrow-left" style="font-size: 28px;"></i>
    </div>
<?php // } 
?>
-->
<?php if ($this->hide_footer != 1) { ?>
    <!-- <div class="page-footer">
        <div class="page-footer-inner">
            Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune.
        </div>
        <div class="scroll-to-top hidden-xs">
            <i class="icon-arrow-up"></i>
        </div>
    </div> -->
<?php } ?>

<?php if ($this->help_hero == 1) { ?>
    <script src="//app.helphero.co/embed/cjcHsHLBZdr"></script>
    <script>
        HelpHero.identify("<?php echo $this->merchant_id; ?>", {
            role: "Merchant",
            created_at: "<?php echo $this->created_date; ?>"
        });
    </script>
<?php } ?>
<?php if ($this->software_suggest == 1 && $this->env == 'PROD') { ?>
    <!--ClickMeter.com code for conversion: Swipez -->
    <script type='text/javascript'>
        var ClickMeter_conversion_id = '8FFC3DD07E574F5DA9EB936AEAEEE1D8';
        var ClickMeter_conversion_value = '0.00';
        var ClickMeter_conversion_commission = '0.00';
        var ClickMeter_conversion_commission_percentage = '0.00';
        var ClickMeter_conversion_parameter = 'empty';
    </script>
    <script type='text/javascript' id='cmconvscript' src='//s3.amazonaws.com/scripts-clickmeter-com/js/conversion.js'></script>
    <noscript>
        <img height='0' width='0' alt='' src='//www.clickmeter.com/conversion.aspx?id=8FFC3DD07E574F5DA9EB936AEAEEE1D8&val=0.00&param=empty&com=0.00&comperc=0.00&pixel=true' />
    </noscript>
<?php } ?>
<?php
if (isset($this->landing_tag)) {
    echo $this->landing_tag;
}
?>
<?php
if (isset($this->success_tag)) {
    echo $this->success_tag;
}
?>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/subscription-datatable.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<?php echo $this->footer_code; ?>


<script>
    //$('.page-sidebar-menu').height(window.innerHeight-100);
</script>
<?php if ($this->v3captcha) { ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $this->v3_captcha_id; ?>"></script>

    <script>
        function captchaset() {
            captcha_id = '<?php echo $this->v3_captcha_id; ?>';
            grecaptcha.execute(captcha_id, {
                action: 'homepage'
            }).then(function(token) {
                try {
                    document.getElementById('captcha1').value = token;
                } catch (o) {}
            });
        }
        grecaptcha.ready(function() {
            captchaset();
        });
        setInterval(function() {
            captchaset();
        }, 2 * 60 * 1000);
    </script>
<?php } ?>

<?php if($this->show_smart_look_script && $this->logged_in) { ?>
    <script type='text/javascript'>
        window.smartlook||(function(d) {
        var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
        var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
        c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
        })(document);
        smartlook('init', '936cedeb2f54186e4d1b006dc4bcc0395e854ecf');
  </script>
<?php } ?>