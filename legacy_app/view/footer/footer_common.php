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
<?php if ($this->disable_talk != 1 && $this->env == 'PROD') { ?>
    <!-- BEGIN GROOVE WIDGET CODE -->
<script>

!function(e,t){if(!e.groove){var i=function(e,t){return Array.prototype.slice.call(e,t)},a={widget:null,loadedWidgets:{},classes:{Shim:null,Embeddable:function(){this._beforeLoadCallQueue=[],this.shim=null,this.finalized=!1;var e=function(e){var t=i(arguments,1);if(this.finalized){if(!this[e])throw new TypeError(e+"() is not a valid widget method");this[e].apply(this,t)}else this._beforeLoadCallQueue.push([e,t])};this.initializeShim=function(){a.classes.Shim&&(this.shim=new a.classes.Shim(this))},this.exec=e,this.init=function(){e.apply(this,["init"].concat(i(arguments,0))),this.initializeShim()},this.onShimScriptLoad=this.initializeShim.bind(this),this.onload=void 0}},scriptLoader:{callbacks:{},states:{},load:function(e,i){if("pending"!==this.states[e]){this.states[e]="pending";var a=t.createElement("script");a.id=e,a.type="text/javascript",a.async=!0,a.src=i;var s=this;a.addEventListener("load",(function(){s.states[e]="completed",(s.callbacks[e]||[]).forEach((function(e){e()}))}),!1);var n=t.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},addListener:function(e,t){"completed"!==this.states[e]?(this.callbacks[e]||(this.callbacks[e]=[]),this.callbacks[e].push(t)):t()}},createEmbeddable:function(){var t=new a.classes.Embeddable;return e.Proxy?new Proxy(t,{get:function(e,t){return e instanceof a.classes.Embeddable?Object.prototype.hasOwnProperty.call(e,t)||"onload"===t?e[t]:function(){e.exec.apply(e,[t].concat(i(arguments,0)))}:e[t]}}):t},createWidget:function(){var e=a.createEmbeddable();return a.scriptLoader.load("groove-script","https://d9c27c88-adc0-4923-a7e8-2ef0b33493f5.widget.cluster.groovehq.com/api/loader"),a.scriptLoader.addListener("groove-iframe-shim-loader",e.onShimScriptLoad),e}};e.groove=a}}(window,document);

window.groove.widget = window.groove.createWidget();

/* Contact Email */
window.groove.widget.identifyContact('contact_email', '<?php echo $this->talk_email_id; ?>');
window.groove.widget.identifyCompany('company_name', '<?php echo $this->company_name; ?>');

window.groove.widget.setContact({
  contact_first_name: '<?php echo $this->talk_first_name; ?>',
  contact_last_name: '<?php echo $this->talk_last_name; ?>',
  contact_phone_number:'<?php echo $this->talk_mobile; ?>'  
});

window.groove.widget.init('d9c27c88-adc0-4923-a7e8-2ef0b33493f5', {});

</script>

<!-- END GROOVE WIDGET CODE -->
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