<!-- BEGIN FOOTER -->

<?php if ($this->source != 'APP') { ?>
    <div class="floating-back visible-xs">
        <i onclick="history.back(-1);" class="icon-arrow-left" style="font-size: 28px;"></i>
    </div>
<?php } ?>
<div class="page-footer">
    <div class="page-footer-inner">
        Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune.
    </div>
    <div class="scroll-to-top hidden-xs>
         <i class=" icon-arrow-up"></i>
    </div>
</div>

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/////assets/global/plugins/respond.min.js"></script>
<script src="/////assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->



<script src="/assets/admin/layout/scripts/layout.js<?php echo $this->fileTime('js', 'layout'); ?>" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/quick-sidebar.js<?php echo $this->fileTime('js', 'quick-sidebar'); ?>" type="text/javascript"></script>

<script src="/assets/admin/layout/scripts/demo.js<?php echo $this->fileTime('js', 'demo'); ?>" type="text/javascript"></script>
<script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- Script -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>
<script src="/assets/global/plugins/holder.js" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/admin/pages/scripts/invoice-validation.js?version=2"></script>

<link href="/assets/admin/pages/css/portfolio.css" rel="stylesheet" type="text/css" />
<script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<!-- Form validation end -->


<script type="text/javascript" src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="/assets/admin/layout/scripts/colorbox.js<?php echo $this->fileTime('js', 'colorbox'); ?>"></script>


<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>


<!-- END CORE PLUGINS -->



<!-- END PAGE LEVEL PLUGINS -->
<script>
    try {
        $('.description').summernote({
            height: 200,
            callbacks: {
                onBlur: function() {
                    setcopy('desc');
                }
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['undo', 'redo', 'codeview']]
            ]
        });
    } catch (o) {

    }

    try {
        $('.tncrich').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['undo', 'redo', 'codeview']]
            ],
            callbacks: {
                onKeydown: function(e) {
                    var t = e.currentTarget.innerText;
                    if (t.trim().length >= 5000) {
                        //delete keys, arrow keys, copy, cut
                        if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                            e.preventDefault();
                    }
                },
                onKeyup: function(e) {
                    var t = e.currentTarget.innerText;
                    $('#maxContentPost').text(5000 - t.trim().length);
                },
                onPaste: function(e) {
                    var t = e.currentTarget.innerText;
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    var maxPaste = bufferText.length;
                    if (t.length + bufferText.length > 5000) {
                        maxPaste = 5000 - t.length;
                    }
                    if (maxPaste > 0) {
                        document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                    }
                    $('#maxContentPost').text(5000 - t.length);
                }
            }
        });
    } catch (o) {

    }
    var flg = 0;

    jQuery(document).ready(function() {
        <?php echo $this->function_script; ?>
        $(".iframe").colorbox({
            iframe: true,
            width: "80%",
            height: "90%"
        });
        Swipez.init(); // init swipez core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        ComponentsPickers.init();
        FormValidation.init();
    });

    try {
        $('.select2mepop').select2({
            dropdownParent: $("#custom")
        });
    } catch (o) {}
    try {
    setAdvanceDropdown();
    setTaxDropdown();
} catch (o) {}
</script>
<?php if ($this->help_hero == 1) { ?>
    <script src="//app.helphero.co/embed/cjcHsHLBZdr"></script>
    <script>
        HelpHero.identify("<?php echo $this->merchant_id; ?>", {
            role: "Merchant",
            created_at: "<?php echo $this->created_date; ?>"
        });
    </script>
<?php } ?>
<?php echo $this->footer_code; ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>