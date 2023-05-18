<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>{{$title}}</title>
    <link rel="icon" type="image/png" href="/assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icon/192x192.png">
    <link rel="stylesheet" href="/assets/css/style.css?v=2">
    <link rel="stylesheet" href="/assets/css/custom.css?v=3">
    <link rel="manifest" href="__manifest.json">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body class="{{Session::get('mode')}}">

    <!-- loader -->
    <div id="loader">
        <img src="/assets/img/loading-icon.png" alt="icon" class="loading-icon">
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
                <img src="{{Session::get('icon')}}" alt="image" class="imaged w32">
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
        </div>
    </div>
    @endif
    <!-- * App Header -->


    <!-- App Capsule -->
    @yield('content')
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a @if($menu==1) href="javascript:location.reload();" @else href="/dashboard" @endif class="item @if($menu==1) active @endif">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a @if($menu==2) href="javascript:location.reload();" @else href="/my-rides" @endif class="item @if($menu==2) active @endif">
            <div class="col">
                <ion-icon name="car-sport-outline"></ion-icon>
                <strong>My Rides</strong>
            </div>
        </a>
        <a @if($menu==3) href="javascript:location.reload();" @else href="/book-ride" @endif class="item @if($menu==3) active @endif">
            <div class="col">
                <ion-icon name="add-circle-outline"></ion-icon>
                <strong>Book Ride</strong>
            </div>
        </a>
        <a @if($menu==4) href="javascript:location.reload();" @else href="/notifications" @endif class="item @if($menu==4) active @endif">
            <div class="col">
                <ion-icon name="notifications-outline"></ion-icon>
                <span class="badge badge-danger">0</span>
                <strong>Notifications</strong>
            </div>
        </a>

        <a @if($menu==5) href="javascript:location.reload();" @else href="/settings" @endif class="item @if($menu==5) active @endif">
            <div class="col">
                <ion-icon name="settings-outline"></ion-icon>
                <strong>Settings</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->

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
                            <a href="/contact-us" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline" role="img" class="md hydrated" aria-label="chatbubble outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Support
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


                    <div class="listview-title mt-1">Developed by Paresh</div>


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
    <script src="/assets/js/base.js"></script>

    @yield('footer')

</body>

</html>