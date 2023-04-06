<section id="steps" class="jumbotron bg-transparent py-4">
    <div class="container">


        <div class="row justify-content-center">
            <div class="col-8">
                <h1 class="text-center">Contact Us</h1>
                <br>
                <h4>Email ID</h4>
                <p><i class="fa fa-envelope text-secondary pr-2"></i>support [@] swipez.in</p>
                <h4>Merchant helpdesk</h4>
                <p><i class="fa fa-phone text-secondary pr-2"></i>+91 741 497 3338 - Monday to Friday (9 am to 6 pm IST)</p>
                <h4>Office locations</h4>
                <p class="lead"><i class="fa fa-building text-secondary pr-2"></i>Pune</p>
                <p>91 Springboard, Sky Loft, Creaticity Mall Shastri Nagar, Pune - 411006</p>
                <div class="container-fluid">
                    <div class="row" style="height: 300px;padding: 0px 0px; max-width:100%;">
                        {literal}
                            <script type="text/javascript"
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp9CLmD70KwNZyfxs9ytxsJRQ_mrVN4Mo&sensor=false&callback=initMap">
                            </script>
                            <div style='overflow:hidden;height:300px;width:100%;'>
                                <div id='gmap_canvas' style='height:300px;width:100%;color:black;'></div>
                                <style>
                                    #gmap_canvas img {
                                        max-width: none !important;
                                        background: none !important
                                    }
                                </style>
                            </div>
                            <script type='text/javascript'>
                                function init_map() {
                                    var myOptions = {zoom: 15, center: new google.maps.LatLng(18.5563514, 73.8943515), mapTypeId: google.maps.MapTypeId.ROADMAP};
                                    map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                                    marker = new google.maps.Marker({map: map, position: new google.maps.LatLng(18.5563514, 73.8943515),
                                        icon: '{$server_name}/images/briq.ico'

                                    });
                                    infowindow = new google.maps.InfoWindow({content: '<strong>OPUS Net Pvt. Ltd.</strong><br>91 Springboard, Sky Loft, Creaticity Mall Shastri Nagar, Pune - 411006<br>'});
                                    google.maps.event.addListener(marker, 'click', function () {
                                        infowindow.open(map, marker);
                                    });
                                    infowindow.open(map, marker);
                                }
                                google.maps.event.addDomListener(window, 'load', init_map);
                            </script>
                        {/literal}
                    </div>
                </div>

                <p class="lead pt-3"><i class="fa fa-building text-secondary pr-2"></i>Mumbai</p>
                <p>C - 20, G Block, Bandra Kurla Complex, Mumbai, Maharashtra 400051</p>
                <div class="container-fluid">
                    <div class="row" style="height: 300px;padding: 0px 0px; max-width:100%;">
                        {literal}
                            <script type="text/javascript"
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp9CLmD70KwNZyfxs9ytxsJRQ_mrVN4Mo&sensor=false&callback=initMap">
                            </script>
                            <div style='overflow:hidden;height:300px;width:100%;'>
                                <div id='gmap_canvas2' style='height:300px;width:100%;color:black;'></div>
                                <style>
                                    #gmap_canvas img {
                                        max-width: none !important;
                                        background: none !important
                                    }
                                </style>
                            </div>
                            <script type='text/javascript'>
                                function init_map() {
                                    var myOptions2 = {zoom: 15, center: new google.maps.LatLng(19.058121, 72.865038), mapTypeId: google.maps.MapTypeId.ROADMAP};
                                    map = new google.maps.Map(document.getElementById('gmap_canvas2'), myOptions2);
                                    marker = new google.maps.Marker({map: map, position: new google.maps.LatLng(19.058121, 72.865038),
                                        icon: '{$server_name}/images/briq.ico'

                                    });
                                    infowindow = new google.maps.InfoWindow({content: '<strong>OPUS Net Pvt. Ltd.</strong><br>C - 20, G Block, Bandra Kurla Complex, Mumbai, Maharashtra 400051<br>'});
                                    google.maps.event.addListener(marker, 'click', function () {
                                        infowindow.open(map, marker);
                                    });
                                    infowindow.open(map, marker);
                                }
                                google.maps.event.addDomListener(window, 'load', init_map);
                            </script>
                        {/literal}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<section class="jumbotron bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over 4000+ businesses trust Swipez.<br /><br />Try it free. No credit
                    card required.</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white bg-tertiary" href="{$server_name}/merchant/register">Start Now</a>
            </div>
        </div>
    </div>
</section>