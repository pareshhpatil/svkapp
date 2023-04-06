@extends('home.master')

@section('content')
<section id="steps" class="jumbotron bg-transparent py-4">
    <div class="container">


        <div class="row justify-content-center">
            <div class="col-8">
                <h1 class="text-center">Contact Us</h1>
                <br>
                <h4>Email ID</h4>
                <p>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope"
                        class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z">
                        </path>
                    </svg> support [@] swipez.in</p>
                <h4>Merchant helpdesk</h4>
                <p>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="phone" class="h-6 gray-400"
                        role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z">
                        </path>
                    </svg>
                    {{env('SWIPEZ_HELPDESK_CONTACT')}} - Monday to Friday
                    (9 am to 6 pm IST)</p>
                <h4>Office locations</h4>
                <p class="lead">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="building"
                        class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M436 480h-20V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v456H12c-6.627 0-12 5.373-12 12v20h448v-20c0-6.627-5.373-12-12-12zM128 76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76zm0 96c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40zm52 148h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12zm76 160h-64v-84c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v84zm64-172c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40z">
                        </path>
                    </svg>
                    Pune</p>
                <p>91 Springboard, Sky Loft, Creaticity Mall Shastri Nagar, Pune - 411006</p>
                <div class="container-fluid">
                    <div class="row" style="height: 300px;padding: 0px 0px; max-width:100%;">
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
                                                    icon: '{{ config('app.APP_URL') }}images/briq.ico'

                                                });
                                                infowindow = new google.maps.InfoWindow({content: '<strong>OPUS Net Pvt. Ltd.</strong><br>91 Springboard, Sky Loft, Creaticity Mall Shastri Nagar, Pune - 411006<br>'});
                                                google.maps.event.addListener(marker, 'click', function() {
                                                    infowindow.open(map, marker);
                                                });
                                                infowindow.open(map, marker);
                                            }
                                            google.maps.event.addDomListener(window, 'load', init_map);
                        </script>
                    </div>
                </div>

                <p class="lead pt-3">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="building"
                        class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M436 480h-20V24c0-13.255-10.745-24-24-24H56C42.745 0 32 10.745 32 24v456H12c-6.627 0-12 5.373-12 12v20h448v-20c0-6.627-5.373-12-12-12zM128 76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76zm0 96c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40zm52 148h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40c0 6.627-5.373 12-12 12zm76 160h-64v-84c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v84zm64-172c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12v-40c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40zm0-96c0 6.627-5.373 12-12 12h-40c-6.627 0-12-5.373-12-12V76c0-6.627 5.373-12 12-12h40c6.627 0 12 5.373 12 12v40z">
                        </path>
                    </svg> Mumbai
                </p>
                <p>C - 20, G Block, Bandra Kurla Complex, Mumbai, Maharashtra 400051</p>
                <div class="container-fluid">
                    <div class="row" style="height: 300px;padding: 0px 0px; max-width:100%;">
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
                                                var myOptions2 = {zoom: 15, center: new google.maps.LatLng(19.058121,72.865038), mapTypeId: google.maps.MapTypeId.ROADMAP};
                                                map = new google.maps.Map(document.getElementById('gmap_canvas2'), myOptions2);
                                                marker = new google.maps.Marker({map: map, position: new google.maps.LatLng(19.058121,72.865038),
                                                    icon: '{{ config('app.APP_URL') }}images/briq.ico'

                                                });
                                                infowindow = new google.maps.InfoWindow({content: '<strong>OPUS Net Pvt. Ltd.</strong><br>C - 20, G Block, Bandra Kurla Complex, Mumbai, Maharashtra 400051<br>'});
                                                google.maps.event.addListener(marker, 'click', function() {
                                                    infowindow.open(map, marker);
                                                });
                                                infowindow.open(map, marker);
                                            }
                                            google.maps.event.addDomListener(window, 'load', init_map);
                        </script>
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
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white bg-tertiary"
                    href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
            </div>
        </div>
    </div>
</section>
@endsection
