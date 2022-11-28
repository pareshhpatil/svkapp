<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$merchant->company_name}}</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <script type="text/javascript" src="https://d32nilxotdql0q.cloudfront.net/live/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://d32nilxotdql0q.cloudfront.net/live/js/site.inc.js"></script>
    <script type="text/javascript" src="https://d32nilxotdql0q.cloudfront.net/live/js/jquery.scrollTo.min.js"></script>
    <link href="https://d32nilxotdql0q.cloudfront.net/live/css/animate.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://d32nilxotdql0q.cloudfront.net/live/css/style.css" />
    <link rel="stylesheet" href="https://d32nilxotdql0q.cloudfront.net/live/css/device.css" />
    <link rel="stylesheet" href="https://s3.amazonaws.com/www.pravinstores.in/css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://s3.amazonaws.com/www.pravinstores.in/css/responsive.css?version=1551283086" />
    <link rel="stylesheet" type="text/css" href="https://s3.amazonaws.com/www.pravinstores.in/css/fontawesome-all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/regular.css" integrity="sha384-zkhEzh7td0PG30vxQk1D9liRKeizzot4eqkJ8gB3/I+mZ1rjgQk+BSt2F6rT2c+I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css" integrity="sha384-HbmWTHay9psM8qyzEKPc8odH4DsOuzdejtnr+OFtDmOcIVnhgReQ4GZBH7uwcjf6" crossorigin="anonymous">
    <script src="/assets/admin/pages/scripts/loyalty.js?version=2"></script>
    <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
</head>
<script>
    var URL = '{{env('SWIPEZ_API_URL')}}';
    var MERCHANT_URL='{{$merchant_url}}';
    checkCookie(false);
</script>
<style>
    .login_div {
        padding-top: 50px;
    }
</style>

<body>
    <header style="padding: 10px;">
        <div class="header-inside"> 
        <a href="#" class="logo">
        @if($logo!='')
        <img id="logo_image" style="max-height: 50px;max-width: 200px;" src="/uploads/images/landing/{{$logo}}" />
        @else
        <h2>{{$merchant_id->company_name}}</h2>
        @endif
        </a>
        <a href="javascript:void(0)" class="menu-icon"></a>
            <nav>
                <ul style="padding-top: 10px;" class="main-nav">
                    @if($logged_in==true)
                    <li><a class="active" onclick="logout();">LOGOUT</a></li>
                    @else 
                    <li><a class="active" href="/loyalty/{{$merchant_url}}/login">LOGIN</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    @yield('content')
    <footer>

    </footer>
</body>


</html>