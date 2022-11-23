<footer style="padding: 5px;background: grey;color: #c9dae9;">
    <div class="page-footer" style="height: auto;">
        <div class="page-footer-inner">
            © 2019 OPUS Net Pvt. Handmade in Pune.
        </div>
        <div class="scroll-to-top hidden-xs style="display: block;">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
</footer>

</body>
<script type="text/javascript" src="/assets/cable/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/cable/js/fontawesome.js"></script>
<script type="text/javascript" src="/assets/cable/js/jquery.fancybox.js"></script>
<script type="text/javascript" src="/assets/cable/js/owl.carousel.js"></script>
<script type="text/javascript" src="/assets/cable/js/wow.min.js"></script>
<script type="text/javascript" src="/assets/cable/js/bootstrap.min.js"></script>
<script>
    new WOW().init();
</script>
<script>
    $(document).ready(function () {
        $("#myBtn").click(function () {
            $("#myModal").modal();
        });
    });
</script>
<script>
    $('.fancybox').fancybox();
    $(document).ready(function () {

    });
</script>
<script>
    $(document).ready(function () {
        $("input[name$='cars']").click(function () {
            var test = $(this).val();

            $("div.desc").hide();
            $("#Cars" + test).show();
        });
    });
</script>
</html>