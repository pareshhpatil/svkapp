<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="theme-color" content="#000000">
  <title>Siddhivinayak Travels House</title>
  <meta name="description" content="Siddhivinayak Travels House">
  <link rel="icon" type="image/png" href="/assets/img/favicon.png" sizes="32x32">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icon/192x192.png">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="manifest" href="__manifest.json">
</head>

<body style="background: #ffffff;">

  <!-- loader -->
  <div id="loader">
  <img src="/assets/img/animation1.gif" alt="icon" class="loading-icon">
  </div>
  <!-- * loader -->

  <!-- App Header -->
  <div class="appHeader no-border transparent position-absolute">
    <div class="left">
      @if($title!='Login')
      <a href="#" class="headerButton goBack">
        <ion-icon name="chevron-back-outline"></ion-icon>
      </a>
      @endif
    </div>
    <div class="pageTitle">{{$title}}</div>
    <div class="right">
    </div>
  </div>
  <!-- * App Header -->
  @yield('content')


  <!-- ========= JS Files =========  -->
  <!-- Bootstrap -->
  <script src="/assets/js/lib/bootstrap.bundle.min.js"></script>
  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <!-- Splide -->
  <script src="/assets/js/plugins/splide/splide.min.js"></script>
  <!-- Base Js File -->
  <script src="/assets/js/base.js?v=3"></script>


</body>

</html>