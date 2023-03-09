<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preload" href="/static/css/purged.css" as="style">
    <link rel="preload" href="/static/css/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/font/rubik-v11-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/font/rubik-v11-latin-600.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/static/css/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>

    @if(env('APP_ENV')=='PROD')

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KJ2KHBQ');
    </script>
    <!-- End Google Tag Manager -->
        @isset($header_code)
            {!!$header_code!!}
        @endisset
    

    @endif
    <!-- Site wide SEO tags -->
    @if(request()->path() == '/')
    <link rel="canonical" href="https://www.swipez.in/" />
    @else
    <link rel="canonical" href="https://www.swipez.in/{{ request()->path() }}" />
    @endif
    <meta name="author" content="OPUS Net Pvt. Ltd.">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="image" content="{!! asset('static/images/swipez-banner-share.png') !!}">
    <meta itemprop="image" content="{!! asset('static/images/swipez-banner-share.png') !!}">
    <meta property="og:image" content="{!! asset('static/images/swipez-banner-share.png') !!}">
    <meta property="og:image:secure_url" content="{!! asset('static/images/swipez-banner-share.png') !!}">
    <meta property="og:site_name" content="Swipez">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_US">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{!! asset('static/images/swipez.ico') !!}">
    <link rel="apple-touch-icon" sizes="180x180" href="{!! asset('static/images/apple-touch-icon.png') !!}">
    <link rel="mask-icon" href="{!! asset('static/images/apple-touch-icon.png') !!}" color="#18AEBF">
    <meta name="msapplication-TileColor" content="#18AEBF">
    <meta name="msapplication-TileImage" content="{!! asset('static/images/apple-touch-icon.png') !!}">
    @if(env('APP_ENV')=='PROD' || env('APP_ENV')=='local')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    @isset($jsonld)
    {!! JsonLd::generate() !!}
    @endisset
    @endif
    <!-- End of SEO tools tags -->
    <link rel="stylesheet" href="{!! asset('static/css/font/googlefont.css') !!}">
    <link rel="stylesheet" href="/static/css/purged.css{{ Helpers::fileTime('new','static/css/purged.css') }}">
    <link rel="stylesheet" href="/static/css/custom.css{{ Helpers::fileTime('new','static/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @laravelPWA
    @if(env('IS_HOTJAR_ACTIVE')==1)

    <!--Start of hotjar script -->
    <!-- Hotjar Tracking Code for www.swipez.in -->
    <script>
        (function (h, o, t, j, a, r) {
                h.hj = h.hj || function () {
                    (h.hj.q = h.hj.q || []).push(arguments)
                };
                h._hjSettings = {hjid: 1534063, hjsv: 6};
                a = o.getElementsByTagName('head')[0];
                r = o.createElement('script');
                r.async = 1;
                r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
                a.appendChild(r);
            })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
    <!--End of hotjar script -->
    @endif

    @php if(isset($javascript)){ @endphp
    @foreach($javascript as $js)
    <script src="/assets/admin/layout/scripts/{{$js}}.js{{ Helpers::fileTime('js',$js) }}" type="text/javascript">
    </script>
    @endforeach
    @php } @endphp

    @livewireStyles
</head>

<body>
    @if(env('APP_ENV')=='PROD')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KJ2KHBQ" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif
    <div id="navbar-briq">
        <p>Swipez is now part of Briq! Read the press release announcing the acquisition. 
            <a target="_blank" href="https://www.briq.com/press-release/briq-announces-acquisition-of-swipez">Learn more</a>
        </p>
    </div>
    <nav class="topnav navbar navbar-expand-lg navbar-dark bg-primary" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="/"><img class="logo-default"
                    src="@isset($logo){{$logo}}@else {!! asset('static/images/logo_default.png') !!} @endisset"
                    alt="Swipez Online Payment" title="Swipez Online Payment Solutions" width="210" height="68" /></a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarColor02"
                aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" class="text-white h-6"
                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="currentColor"
                        d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z">
                    </path>
                </svg>
            </button>
            <div class="navbar-collapse collapse" id="navbarColor02">
                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    @if(!isset($has_partner))
                    {{-- <li class="nav-item dropdown megamenu">
                        <a id="megamenu" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="nav-link dropdown-toggle">Products</a>
                        <div aria-labelledby="megamenu" class="dropdown-menu border-0 p-0 m-0 dropdown-menu-right">
                            <div class="bg-white col-lg-12 col-xl-12 pl-0 border-bottom border-tertiary rounded">
                                <div class="pt-3 pl-3 pr-0 pb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold text-uppercase gray-400">Product list</h6>
                                            <hr class="mt-0 mb-2 bg-tertiary" />
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('home.billing') }}"
                                                        class="lead pb-0 text-secondary">Billing
                                                        software</a>
                                                </li>
                                                <li><a href="{{ route('home.collectit') }}"
                                                        class="lead pb-0 text-secondary">Collect it - Billing app
                                                    </a>
                                                </li>
                                            </ul>
                                            <h6 class="font-weight-bold text-uppercase gray-400 pt-2">More products</h6>
                                            <hr class="mt-0 mb-2 bg-tertiary" />
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('home.websitebuilder') }}"
                                                        class="pb-0 text-secondary">Website
                                                        builder</a></li>
                                                <li><a href="{{ route('home.event') }}"
                                                        class="pb-0 text-secondary">Event
                                                        registrations</a></li>
                                                <li><a href="{{ route('home.booking') }}"
                                                        class="pb-0 text-secondary">Venue booking
                                                        software</a></li>
                                            </ul>
                                            <br class="d-lg-none" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    {{-- <li class="nav-item dropdown megamenu">
                        <a id="megamenu" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="nav-link dropdown-toggle">Features</a>
                        <div aria-labelledby="megamenu" class="dropdown-menu border-0 p-0 m-0 dropdown-menu-right">
                            <div class="bg-white col-lg-12 col-xl-12 pl-0 border-bottom border-tertiary rounded">
                                <div class="pt-3 pl-3 pr-0 pb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold text-uppercase gray-400">What you need and more
                                            </h6>
                                            <hr class="mt-0 mb-2 bg-tertiary" />
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('home.billing.feature.onlineinvoicing') }}"
                                                        class="text-small pb-0 text-secondary">Online invoicing</a></li>
                                                <li><a href="{{ route('home.paymentcollections') }}"
                                                        class="text-small pb-0 text-secondary">Payment gateway</a></li>
                                                <li><a href="{{ route('home.billing.feature.paymentreminder') }}"
                                                        class="text-small pb-0 text-secondary">Payment reminders</a>
                                                </li>
                                                <li><a href="{{ route('home.billing.feature.bulkinvoicing') }}"
                                                        class="text-small pb-0 text-secondary">Bulk invoicing</a>
                                                </li>
                                                <li><a href="{{ route('home.paymentlink') }}"
                                                        class="text-small pb-0 text-secondary">Payment links</a></li>
                                                <li><a href="{{ route('home.inventorymanagement') }}"
                                                        class="text-small pb-0 text-secondary">Inventory
                                                        management</a></li>
                                                <li><a href="{{ route('home.expenses') }}"
                                                        class="text-small pb-0 text-secondary">Expense
                                                        management</a></li>
                                                <li><a href="{{ route('home.payouts') }}"
                                                        class="text-small pb-0 text-secondary">Payouts</a></li>
                                                        <li><a href="{{ route('home.feature.custom.payment.page') }}"
                                                        class="text-small pb-0 text-secondary">Payment pages</a></li>
                                                <li><a href="{{ route('home.gstfiling') }}"
                                                        class="text-small pb-0 text-secondary">GST filing</a></li>
                                                <li><a href="{{ route('home.gstrecon') }}"
                                                        class="text-small pb-0 text-secondary">GST reconciliation</a>
                                                </li>
                                                <li><a href="{{ route('home.einvoicing') }}"
                                                        class="text-small pb-0 text-secondary">E-invoicing</a></li>
                                                <li><a href="{{ route('home.woocommerce.invoice') }}"
                                                        class="text-small pb-0 text-secondary">WooCommerce invoicing</a>
                                                </li>
                                                <li><a href="{{ route('home.woocommerce.paymentgateway') }}"
                                                        class="text-small pb-0 text-secondary">WooCommerce payment
                                                        gateway</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    {{-- <li class="nav-item dropdown megamenu">
                        <a id="megamenu" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="nav-link dropdown-toggle">Industry</a>
                        <div aria-labelledby="megamenu" class="dropdown-menu border-0 p-0 m-0 dropdown-menu-right">
                            <div class="bg-white col-lg-12 col-xl-12 pl-0 border-bottom border-tertiary rounded">
                                <div class="pt-3 pl-3 pr-0 pb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold text-uppercase gray-400">Built for your industry
                                            </h6>
                                            <hr class="mt-0 mb-2 bg-tertiary" />
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('home.industry.cable') }}"
                                                        class="text-small pb-0 text-secondary">Cable operator</a></li>
                                                <li><a href="{{ route('home.industry.isp') }}"
                                                        class="text-small pb-0 text-secondary">Internet service
                                                        provider</a></li>
                                                <li><a href="{{ route('home.industry.travelntour') }}"
                                                        class="text-small pb-0 text-secondary">Travel and tour
                                                        operator</a></li>
                                                <li><a href="{{ route('home.industry.milkdairy') }}"
                                                        class="text-small pb-0 text-secondary">Milk Dairy business</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.franchise') }}"
                                                        class="text-small pb-0 text-secondary">Franchise business</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.education') }}"
                                                        class="text-small pb-0 text-secondary">Schools & education</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.entertainmentevent') }}"
                                                        class="text-small pb-0 text-secondary">Entertainment events</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.hospitalityevent') }}"
                                                        class="text-small pb-0 text-secondary">Hospitality events</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.bookingfitness') }}"
                                                        class="text-small pb-0 text-secondary">Health and fitness</a>
                                                </li>
                                                <li><a href="{{ route('home.industry.societybooking') }}"
                                                        class="text-small pb-0 text-secondary">Housing society</a></li>
                                                <li><a href="{{ route('home.industry.freelancers') }}"
                                                        class="text-small pb-0 text-secondary">Freelancers</a></li>
                                                <li><a href="{{ route('home.industry.enterprises') }}"
                                                        class="text-small pb-0 text-secondary">Enterprise</a></li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}
                    {{-- <li class="nav-item dropdown megamenu">
                        <a id="megamenu" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="nav-link dropdown-toggle">Resources</a>
                        <div aria-labelledby="megamenu" class="dropdown-menu border-0 p-0 m-0 dropdown-menu-right">
                            <div class="bg-white col-lg-12 col-xl-12 pl-0 border-bottom border-tertiary rounded">
                                <div class="pt-3 pl-3 pr-0 pb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold text-uppercase gray-400">Free business resources</h6>
                                            <hr class="mt-0 mb-2 bg-tertiary" />
                                            <ul class="list-unstyled">
                                                <li><a href="{{ route('home.freebiztools') }}"
                                                        class="lead pb-0 text-secondary">Free business tools</a>
                                                </li>
                                                <li><a href="{{ route('home.invoicetemplates') }}"
                                                        class="lead pb-0 text-secondary">Invoice templates</a>
                                                    <ul class="list-styled">
                                                        <li><a href="{{ route('home.excelinvoicetemplates') }}"
                                                                class="text-small pb-0 text-secondary">Excel</a>
                                                        </li>
                                                        <li><a href="{{ route('home.wordinvoicetemplates') }}"
                                                                class="text-small pb-0 text-secondary">Word</a>
                                                        </li>
                                                        <li><a href="{{ route('home.pdfinvoicetemplates') }}"
                                                                class="text-small pb-0 text-secondary">PDF</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li><a href="{{ route('home.invoiceformats') }}"
                                                        class="text-small pb-0 text-secondary">Free invoice
                                                        templates</a>
                                                </li>
                                                <li><a href="{{ route('home.footer.customerstories') }}"
                                                        class="text-small pb-0 text-secondary">Customer stories</a>
                                                </li>
                                                <li><a href="https://helpdesk.swipez.in/help"
                                                        class="text-small pb-0 text-secondary" target="_blank">Knowledge
                                                        base</a>
                                                </li>
                                                <li><a href="{{ route('home.partnerbenefits') }}"
                                                    class="text-small pb-0 text-secondary" target="_blank">Partner benefits</a>
                                                </li>
                                                <li><a href="{{ route('home.integrations') }}"
                                                    class="text-small pb-0 text-secondary" target="_blank">Integrations</a>
                                                </li>
                                                <li><a href="{{ route('home.footer.partner') }}"
                                                        class="text-small pb-0 text-secondary">Partner with us</a></li>
                                                <li><a href="https://www.swipez.in/blog"
                                                        class="text-small pb-0 text-secondary" target="_blank">Blog</a>
                                                </li>
                                                <li>
                                                    <a href="https://headwayapp.co/swipez-updates" target="_blank"
                                                        class="text-small pb-0 text-secondary badgeCont">Changelog</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> --}}

                    <!-- li class="nav-item"><a class="nav-link" href="">Customer
                            stories</a></li -->
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('home.pricing') }}">Pricing</a></li> --}}
                    @endif
                    @guest
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ config('app.APP_URL') }}login">Login</a></li>
                    <li class="nav-item"><span class="nav-link" href="#"><a class="btn btn-secondary"
                                href="{{ config('app.APP_URL') }}merchant/register">Sign Up</a></span></li> --}}
                    @else
                    <li class="nav-item"><a class="btn btn-secondary"
                            href="{{ config('app.APP_URL') }}merchant/dashboard">Dashboard</a></li>
                    {{-- <li class="nav-item"><span class="nav-link" href="#"><a class="nav-link"
                                href="/logout">Logout</a></span></li> --}}
                    @endguest


                </ul>
            </div>
        </div>
    </nav>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.2.js"></script>
    <script type='text/javascript'>
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            let user = getCookie("briq-popup");
            if (user != "") {
                return true;
            } else {
                var name = '{{ env('BRIQ_POPUP_COOKIE') }}';
                if(name == '' || name ==  null){
                    name = false
                }
                if(name){
                    setCookie('briq-popup', 'true', '1');
                }
                return false;
            }
        }
        var popupCheck = '{{ env('BRIQ_DISABLE_POPUP') }}';
        if(!popupCheck){
            $(function () {
            var overlay = $('<div id="overlay"></div>');
            overlay.show();
            if(checkCookie() == false){
                overlay.appendTo(document.body);
                $('.popup').show();
                $('body').css('overflow', 'hidden');
                $('.close').click(function () {
                    $('.popup').hide();
                    $('body').css('overflow', 'auto');
                    overlay.appendTo(document.body).remove();
                    return false;
                });
            }
        });
        }
        
    </script>
    <div class='popup'>
        <div class='cnt223'>
            <img class="img-briq-logo" src="/static/images/logo-brik-default.png">
            <a href='#' class='close'><i class="fa fa-close whiteincolor"></i></a>
            <br>
            <br>
            <br>
            <br>
            <h2 id="popup-heading">Swipez is now a Briq company</h2>
            <br>
            <p id="popup-text">Swipez is now part of Briq! Read the press release announcing the acquisition.
            </p>
            <br>
            <a target="_blank" class="btn btn-lg btn-briq-custom"
                href="https://www.briq.com/press-release/briq-announces-acquisition-of-swipez">
                Learn more
            </a>
        </div>
    </div>
    @yield('content')
    @include('home.footer')
