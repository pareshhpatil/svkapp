<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <!-- <meta name="apple-mobile-web-app-capable" content="yes" /> -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>{{$title}}</title>
    <link rel="icon" type="image/png" href="/assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icon/192x192.png">
    <link rel="stylesheet" href="/assets/css/style.css?v=11">
    <link rel="stylesheet" href="/assets/css/custom.css?v=9">
    <!-- <link rel="manifest" href="__manifest.json"> -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        .profile-img {
            border-radius: 100%;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.09);
        }
    </style>
    <!-- <style>
        body {
            background-image: url("/assets/img/bg.jpg");
            background-repeat: no-repeat, repeat;
            background-size: cover;
        }
    </style>-->

    @if(Session::get('user_type')==4)
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3546413,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
    @endif
</head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PZW6G05662"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-PZW6G05662');
</script>

<body @isset($onload) onload="{{$onload}}" @endisset class="{{Session::get('mode')}}">

    <!-- loader -->
    <div id="loader">
        <img src="/assets/img/animation1.gif" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- App Header -->
    @if($title=='dashboard')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="/assets/img/logo.png" alt="logo" class="logo" style="max-height: 50px;">
        </div>
        <div class="right">

            <a href="/profile" class="headerButton">
                <img src="{{Session::get('icon')}}?v=3" alt="image" class="imaged w32 profile-img">
            </a>
        </div>
    </div>
    @else
    <div class="appHeader">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">{{$title}}</div>
        <div class="right">
            <a href="/dashboard" class="headerButton" style="color: #27173E;">
                <ion-icon name='home-outline'></ion-icon>
            </a>
        </div>
    </div>
    @endif
    <!-- * App Header -->


    <!-- App Capsule -->
    @yield('content')
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    @if(Session::has('name'))
    @if(!isset($hide_menu))
    <div class="appBottomMenu">
        <a @if($menu==1) href="javascript:location.reload();" @else href="/staff/dashboard" @endif onclick="lod(true);" class="item @if($menu==1) active @endif">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        @if(Session::get('user_type')==1)
        <a @if($menu==2) href="javascript:location.reload();" @else href="/staff/payment/pending" @endif onclick="lod(true);" class="item @if($menu==2) active @endif">
            <div class="col">
                <ion-icon name="card-outline"></ion-icon>
                <strong>Pending</strong>
            </div>
        </a>
        @else
        <a @if($menu==4) href="javascript:location.reload();" @else href="/staff/payment/send" @endif onclick="lod(true);" class="item @if($menu==2) active @endif">
            <div class="col">
                <ion-icon name="card-outline"></ion-icon>
                <strong>Payment</strong>
            </div>
        </a>
        @endif
        <a @if($menu==3) href="javascript:location.reload();" @else href="/staff/payment/transactions" @endif onclick="lod(true);" class="item @if($menu==3) active @endif">
            <div class="col">
                <ion-icon name="cash-outline"></ion-icon>
                <strong>Transactions</strong>
            </div>
        </a>

        <a @if($menu==5) href="javascript:location.reload();" @else href="/settings" @endif onclick="lod(true);" class="item @if($menu==5) active @endif">
            <div class="col">
                <ion-icon name="settings-outline"></ion-icon>
                <strong>Settings</strong>
            </div>
        </a>
    </div>
    @endif
    @endif


    <!-- App Sidebar -->
    @if($title=='dashboard')
    <div class="modal fade panelbox panelbox-left show" id="sidebarPanel" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">

                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">
                            <img src="{{Session::get('icon')}}" alt="image" class="imaged  w36">
                        </div>
                        <div class="in">
                            <strong>{{Session::get('name')}}</strong>
                            <div class="text-muted">{{Session::get('mobile')}}</div>
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                            <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="close outline"></ion-icon>
                        </a>
                    </div>

                    <div class="listview-title mt-1">Menu</div>
                    <ul class="listview flush transparent no-line image-listview">
                        @if(Session::get('user_type')==3)
                        <li>
                            <a href="/master/add/driver" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="person-circle-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Add Driver
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/master/add/vehicle" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="car-sport-outline" role="img" class="md hydrated" aria-label="settings outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Add Vehicle
                                </div>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="/settings" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="settings-outline" role="img" class="md hydrated" aria-label="settings outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Settings
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/chats" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Chats
                                </div>
                            </a>
                        </li>
                        @if(Session::get('mobile')=='9730946150')
                        <li>
                            <a href="https://app.svktrv.in/user/switch/o2IZM970oyDLn95JrFlW9yw" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Payment Login
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="https://app.svktrv.in/user/switch/KlcMttFurp_p4WP4q3_jXixe4A" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Supervisor Login
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/whatsapp" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="logo-whatsapp" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Whatsapp
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/users/list" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Users
                                </div>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="/chat/create/{{Session::get('user_type')}}/0/1/{{Session::get('parent_id')}}/0" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Contact us
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/blogs" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="apps-outline" role="img" class="md hydrated" aria-label="apps outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Blogs
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/faq" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="help-circle-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    FAQ
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/calendar" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="calendar-number-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Calendar
                                </div>
                            </a>
                        </li>
                        <li>
                            <a onclick="document.getElementById('logout').submit();" href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="log-out-outline" role="img" class="md hydrated" aria-label="log out outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Log out
                                </div>
                            </a>
                            <form id="logout" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </li>
                    </ul>
                    <div class="listview-title mt-1">Developed by <a href="https://www.ridetrack.in"> Ride Track</a></div>
                </div>
            </div>
        </div>
    </div>

    @endif
    <!-- * App Sidebar -->









    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="/assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="/assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="/assets/js/base.js?v=9"></script>

    @yield('footer')
    <script>
        function closeT(num = 11) {
            document.getElementById('toast-' + num).classList.remove("show");
        }
    </script>

</body>

</html>
