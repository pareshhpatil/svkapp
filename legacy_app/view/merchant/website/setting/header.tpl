<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Swipez website builder</title>
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
        <script type="text/javascript" src="/assets/site-builder/setup/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/assets/site-builder/setup/js/site.inc.js?v=1"></script>
        <script type="text/javascript" src="/assets/site-builder/setup/js/jquery.scrollTo.min.js"></script>
        <link href="/assets/site-builder/setup/css/animate.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/assets/site-builder/setup/css/style.css"/>
        <link rel="stylesheet" href="/assets/site-builder/setup/css/edit-theme.css"/>
        <link rel="stylesheet" href="/assets/site-builder/setup/css/device.css"/>
        <script type="text/javascript" src="/assets/site-builder/setup/js/my_jquery.js"></script>
        <script type="text/javascript" src="/assets/site-builder/setup/js/tinymce/tinymce.min.js"></script>
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body> 


        <div class="main-sidebar">
            <a onclick="window.history.back();" href="" class="btn-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>

            <ul class="sidebar-nav">
                <li><a href="/merchant/website/build" {if $type=='started'} class="active" {/if}>Website Editor</a></li>
                <li><a href="/merchant/website/domain" {if $type=='setup'} class="active" {/if}>Set up</a></li>
            </ul>
        </div>
        <div class="main-topbar-wrapp">
            <div class="main-topbar">
                <h1>Set Up Your Website</h1>
                <!--<div class="btns"><a href="#" class="button preview">Preview</a> <a href="#" class="button publish">Publish</a></div>-->
            </div>
        </div>
        <div class="main-wrapper">
