<!doctype html>
<html>
    <head>
        <title>Payment</title>
        <meta name="viewport" content="width=device-width" />
        <script src="<?php echo $this->js_link1; ?>" type="text/javascript"></script>
        <script type="text/javascript" src="<?php echo $this->js_link2; ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var configJson = JSON.parse('<?php echo $this->json_string; ?>');
                $.pnCheckout(configJson);
                if (configJson.features.enableNewWindowFlow) {
                    pnCheckoutShared.openNewWindow();
                }
            });
        </script>
        <style>
            .popup-close{
                display: none !important;
            }
        </style>
    </head>
    <body style="text-align:-webkit-center;">
        <div  style="width:900px;" id="checkoutElement"></div>
    </body>



</html>