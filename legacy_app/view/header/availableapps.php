<?php
if ($this->showcampaign_script == 1 && $this->env == 'PROD') {
    ?>
    <!-- Google Code for Swipez home Conversion Page -->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 817807754;
        var google_conversion_label = "wsjyCIuOjnwQioP7hQM";
        var google_remarketing_only = false;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
    </script>
    <!-- Event snippet for Swipez conversion page -->
    <script>
        gtag('event', 'conversion', {'send_to': 'AW-817807754/MM9LCNq7rIcBEIqD-4UD'});
    </script>
    <noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/817807754/?label=wsjyCIuOjnwQioP7hQM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>

    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s)
        {
            if (f.fbq)
                return;
            n = f.fbq = function () {
                n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)
                f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '489330198131207');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=489330198131207&ev=PageView&noscript=1"
                   /></noscript>
    <!-- End Facebook Pixel Code -->
<?php } ?>