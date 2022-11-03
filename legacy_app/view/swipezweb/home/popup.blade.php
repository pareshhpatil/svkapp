<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Site wide SEO tags -->
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

    <!-- SEO tools tags -->
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    <!-- End of SEO tools tags -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,600,800">
    <link rel="stylesheet" href="{!! asset('static/css/main.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('static/css/fontawesome/all.min.css') !!}">


    <meta name="description" content="">

    <style>
        body {
            font-family: Montserrat, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        .megamenu {}

        .megamenu .dropdown-menu {
            background: none;
            border: none;
            width: 550px;
        }

        .dropdown-menu:before {
            border-top: none;
        }

        .megamenu ul.list-unstyled li {
            margin-bottom: 0.3rem;
        }
    </style>
</head>

<body>
    @yield('content')
