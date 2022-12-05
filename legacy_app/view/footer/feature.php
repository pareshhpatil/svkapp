<section id="contact"><div class="footer-copyright"><div class="container"><div class="row"><div class="col-md-12 col-sm-6 pre-footer-col" style="color: white;"><a href="<?php echo $this->server_name; ?>/privacy" class="color-white">Privacy Policy</a> | <a href="<?php echo $this->server_name; ?>/terms" class="color-white">Terms & Conditions</a> |  <a href="<?php echo $this->server_name; ?>/aboutus" class="color-white">About Us</a> | <a href="<?php echo $this->server_name; ?>/contactus" class="color-white">Contact Us</a> | <a href="<?php echo $this->server_name; ?>/work-from-home" class="color-white">Work From Home</a>| <a href="<?php echo $this->server_name; ?>/sitemap" class="color-white">Sitemap</a><ul class="copyright-socials" style="margin-bottom: 20px;margin-top: 20px;"><li><span itemscope itemtype="http://schema.org/Organization"><link itemprop="url" href="https://www.swipez.in"><a itemprop="sameAs" target="_BLANK" href="https://www.facebook.com/Swipez-347240818812009/" data-original-title="Facebook" title="Swipez Facebook Page"><i class="fa fa-facebook"></i></a></span></li><li><span itemscope itemtype="http://schema.org/Organization"><link itemprop="url" href="https://www.swipez.in"><a itemprop="sameAs" target="_BLANK" href="https://www.youtube.com/channel/UC62hpFWFt-S8aReQGDsdoSg/featured" data-original-title="Youtube" title="Youtube Channel"><i class="fa fa-youtube"></i></a></span></li>
                    <span> Associated with</span>
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <a itemprop="sameAs" target="_BLANK" href="https://vilcap.com/" data-original-title="Home | Village Capital" title="Home | Village Capital">
                                    <img class="img-responsive" style="height: 34px;margin-bottom: -12px;" src="/assets/admin/layout/img/vilcap.png" alt="Home | Village Capital" title="Home | Village Capital">
                                </a>
                            </span>
                        </li>
                        <li>
                            <span itemscope itemtype="http://schema.org/Organization">
                                <a itemprop="sameAs" data-original-title="Home | Mumbai Fintech Hub" title="Home | Mumbai Fintech Hub">
                                    <img class="img-responsive" style="height: 34px;margin-bottom: -12px;" src="/assets/admin/layout/img/fintech.png" alt="Home | Mumbai Fintech Hub" title="Home | Mumbai Fintech Hub">
                                </a>
                            </span>
                        </li>
                    </ul><p>Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune.</p></div></div></div></div></section></div>
<a href="#intro" class="go2top"><i class="fa fa-arrow-up"></i></a>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.easing.js" type="text/javascript"></script>
<script src="/assets/global/plugins/jquery.parallax.js" type="text/javascript"></script>
<script src="/assets/global/plugins/smooth-scroll/smooth-scroll.js" type="text/javascript"></script>
<script src="/assets/global/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/cubeportfolio/cubeportfolio/js/jquery.cubeportfolio.min.js" type="text/javascript"></script>
<script src="/assets/frontend/onepage2/scripts/portfolio.js" type="text/javascript"></script>
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript"></script> 
<script src="/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>
<script src="/assets/frontend/onepage2/scripts/layout.js" type="text/javascript"></script>
<script>
var RevosliderInit = function () {
$(".iframe").colorbox({iframe: true, width: "80%", height: "90%"});
return {
initRevoSlider: function () {
var height = 580; 
if (Layout.getViewPort().width > 1600) {
height = $(window).height() - $('.subscribe').outerHeight();  // full height for high resolution
} else if (Layout.getViewPort().height > height) {
height = Layout.getViewPort( ).height;
}
height = height - 109;
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
});
</script>
</body>
</html>