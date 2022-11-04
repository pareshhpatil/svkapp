<html lang="en">

<head>
    <meta charset="utf-8" />
    <title> @if(Session::has('company_name'))
        {{Session::get('company_name')}}
        |
        @endif
        {{$title}}
    </title>
    <meta name="author" content="Swipez">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/layout/css/swipezapp.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />

    @foreach ($jsfile as $js)
    <script src="/assets/admin/layout/scripts/{{$js}}.js{{ Helpers::fileTime('js',$js) }}" type="text/javascript"></script>
    @endforeach
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href='/assets/admin/layout/css/plugin/select2.min.css' rel='stylesheet' type='text/css'>
    @if($datatablejs!='')
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    @endif
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />

    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0
        }

        .ui-icon-arrowthick-2-n-s {
            background-position: -128px -48px
        }

        .ui-icon {
            display: inline-block;
            vertical-align: middle;
            margin-top: -.25em;
            position: relative;
            text-indent: -99999px;
            overflow: hidden;
            background-repeat: no-repeat
        }

        .ui-widget-icon-block {
            left: 50%;
            margin-left: -8px;
            display: block
        }

        .select2-container--bootstrap .select2-selection {
            border-radius: 1px
        }

        .select2-container .select2-selection--single {
            display: contents
        }
    </style>
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="/assets/global/css/plugins.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    @yield('header')
    @if(isset($richtext))
    <link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">
    @endif
    <link href="/assets/admin/layout/css/custom.css{{ Helpers::fileTime('css','custom') }}" rel="stylesheet" type="text/css" />
    <link href="/assets/admin/layout/css/movingintotailwind.css{{ Helpers::fileTime('css','movingintotailwind') }}" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <!-- END THEME STYLES -->

    <!-- Uppy file upload css -->
    <link href="/assets/admin/layout/css/uppy.min.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="/favicon.ico" />
    <style>
        .bootstrap-switch .bootstrap-switch-handle-on,
        .bootstrap-switch .bootstrap-switch-handle-off,
        .bootstrap-switch .bootstrap-switch-label {
            height: auto !important;
        }

        td,
        th {
            font-size: 1rem;
        }

        [class^="fa-"]:not(.fa-stack),
        [class^="glyphicon-"],
        [class^="icon-"],
        [class*=" fa-"]:not(.fa-stack),
        [class*=" glyphicon-"] .fa-angle-left {
            padding: 3px;
        }
    </style>
    @livewireStyles
</head>

<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    <!-- BEGIN HEADER -->
    @if(Session::has('package_expire'))
    <div class="center packageexpire">
        Your current plan has expired. Please renew your plan to start using your account - <a href="/merchant/package/confirm/{{ Session::get('package_link')}}">Renew plan</a> or <a href="{{ Session::get('choose_package_link')}}">Pick another plan</a>
    </div>
    @endif
    @if(Session::has('package_reminder_days'))
    <div class="center packageexpire">
        Your current plan will expire in @if(Session::get('package_reminder_days')==1) Tommorrow @else {{Session::get('package_reminder_days')}} days @endif. Please renew your package to keep your account active - <a href="/merchant/package/confirm/{{ Session::get('package_link')}}">Renew plan</a> or <a href="{{ Session::get('choose_package_link')}}">Pick another plan</a>
    </div>
    @endif
    <div id="stickyActionBar" class="page-header navbar navbar-fixed-top" style="padding-top: 4px;display:none; position: fixed; height: 50px; min-height: 50px; background-color: #D9DEDE">
        <div class="page-header-inner text-center ">
            @include('gst.actionbar')
        </div>
    </div>
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo" style="width:100%;padding-right: 0px;" itemscope="" itemtype="http://schema.org/Organization">
                <div class="top-menu" style="width: 100%;">
                    <a href="javascript:;" style="margin-top: 20px;" class="menu-toggler responsive-toggler pull-left" data-toggle="collapse" data-target=".navbar-collapse">
                    </a>
                    <ul class="nav navbar-nav pull-left">
                        <!-- <a itemprop="url" title="Swipez" href="{{$server_name}}">
                            <img style="max-height: 50px;" class="logo-default hidden-xs" src="/assets/admin/layout/img/logo.png?v=5" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        </a> -->
                        <li class="dropdown dropdown-user pull-left hidden-lg hidden-sm">
                            <a href="javascript:;" class="dropdown-toggle blank white " data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                {{$display_name}}
                                @if(Session::has('master_login_group'))
                                <i class="fa fa-angle-down"></i>
                                @endif
                            </a>
                            @if(Session::has('master_login_group'))
                            <ul class="dropdown-menu">
                                @foreach (Session::get('master_login_group') as $ml)
                                <li class="{{$ml['active']}}">
                                    @if($ml['active']=='active')
                                    <a href="#">
                                        <b>{{$ml['display_name']}}</b>
                                        <p>{{$ml['email_id']}}</p>
                                    </a>
                                    @else
                                    <a href="/merchant/profile/switchlogin/{{$ml['key']}}">
                                        <b>{{$ml['display_name']}}</b>
                                        <p>{{$ml['email_id']}}</p>
                                    </a>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <!-- <li class="dropdown hidden-xs">
                            <a href="javascript:;" class="dropdown-toggle blank white dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                {{$display_name}}
                                @if(Session::has('master_login_group'))
                                <i class="fa fa-angle-down"></i>
                                @endif
                            </a>
                            @if(Session::has('master_login_group'))
                            <ul class="dropdown-menu">
                                @foreach (Session::get('master_login_group') as $ml)
                                <li class="{{$ml['active']}}">
                                    @if($ml['active']=='active')
                                    <a href="#">
                                        <b>{{$ml['display_name']}}</b>
                                        <p>{{$ml['email_id']}}</p>
                                    </a>
                                    @else
                                    <a href="/merchant/profile/switchlogin/{{$ml['key']}}">
                                        <b>{{$ml['display_name']}}</b>
                                        <p>{{$ml['email_id']}}</p>
                                    </a>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li> -->
                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-user-circle"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                <a href="http://swipez.prod/merchant/profile/settings">
                                        <i class="fa fa-cog"></i> Setting </a>
                                </li>
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-sign-out"></i> {{$menu['logout']}} </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-th"></i>
                            </a>
                            <ul class="dropdown-menu" style="min-width: 230px;">
                            <li>
                                    <a href="#">
                                        <div style="position: relative;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="v-icon__component theme--light" style="color: rgb(33, 33, 64); caret-color: rgb(33, 33, 64);">
                                                <path d="M0 2C0 0.89543 0.895431 0 2 0H22C23.1046 0 24 0.895431 24 2V22C24 23.1046 23.1046 24 22 24H2C0.89543 24 0 23.1046 0 22V2Z" fill="#212140"></path>
                                                <path d="M6.14467 10.153C5.58857 10.153 5.08239 10.3759 4.7031 10.7407V7.63672H3.92725V9.64452V11.487V13.2269V13.4948V14.4834H4.7031V14.015C5.08239 14.3798 5.58857 14.6027 6.14467 14.6027C7.32041 14.6027 8.2736 13.6065 8.2736 12.3777C8.2736 11.1489 7.32041 10.153 6.14467 10.153ZM6.08637 13.8486C5.29324 13.8486 4.65021 13.1871 4.65021 12.3713C4.65021 11.5553 5.29324 10.8937 6.08637 10.8937C6.87951 10.8937 7.52253 11.5553 7.52253 12.3713C7.52253 13.1871 6.87951 13.8486 6.08637 13.8486Z" fill="white"></path>
                                                <path d="M20.1299 15.5633L19.7623 14.9265L19.0953 15.3116V13.7344V13.4073V11.5625V11.4911V10.2984H18.3195V10.7116C17.9437 10.3635 17.4494 10.1523 16.9077 10.1523C15.732 10.1523 14.7788 11.1483 14.7788 12.3771C14.7788 13.6059 15.732 14.602 16.9077 14.602C17.4494 14.602 17.9437 14.3907 18.3195 14.0424V16.1611H19.0953V16.1588L19.096 16.16L20.1299 15.5633ZM16.9662 13.8482C16.1731 13.8482 15.5301 13.1866 15.5301 12.3708C15.5301 11.5548 16.1731 10.8933 16.9662 10.8933C17.7593 10.8933 18.4024 11.5548 18.4024 12.3708C18.4024 13.1866 17.7593 13.8482 16.9662 13.8482Z" fill="white"></path>
                                                <path d="M13.792 10.2988H13.0161V14.4876H13.792V10.2988Z" fill="white"></path>
                                                <path d="M13.5237 9.17351C13.8056 9.10696 13.9801 8.82456 13.9135 8.54274C13.847 8.26091 13.5646 8.0864 13.2827 8.15295C13.0009 8.21949 12.8264 8.5019 12.893 8.78372C12.9595 9.06554 13.2419 9.24006 13.5237 9.17351Z" fill="white"></path>
                                                <path d="M10.8718 10.2252C10.4202 10.3406 10.1441 10.5972 9.98142 10.8223V10.2992H9.20557V14.488H9.98142V12.3915C10.0029 11.7787 10.1764 11.5371 10.3254 11.3591C10.4438 11.2175 10.6232 11.1011 10.7077 11.0505C10.7492 11.0304 10.7929 11.0108 10.839 10.9923C11.4651 10.7939 11.9431 11.0793 11.9431 11.0793L12.3601 10.3683C12.36 10.3683 11.7628 9.99777 10.8718 10.2252Z" fill="white"></path>
                                            </svg>
                                            <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Planning & Forecasting</span>
                                        </div>

                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                        <img src="<?php echo '/assets/admin/layout/img/spend.png'; ?>">
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Spend Management</span>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                    <img src="<?php echo '/assets/admin/layout/img/cash.png'; ?>">
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Briq Cash</span>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                    <div style="position: relative;">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="v-icon__component theme--light" style="color: rgb(33, 33, 64); caret-color: rgb(33, 33, 64);">
                                            <path d="M18.7219 10.7219C18.7219 11.9259 18.4053 13.0559 17.8509 14.0333L15.4224 10.3087C15.097 9.80952 14.3031 9.80952 13.9777 10.3087L13.1193 11.6253L12.5097 12.5434L10.5239 9.44277C10.1752 8.89826 9.32465 8.89826 8.9759 9.44277L6.09657 13.9392C5.5747 12.9836 5.27808 11.8874 5.27808 10.7219C5.27808 7.00949 8.28757 4 12 4C15.7124 4 18.7219 7.00949 18.7219 10.7219Z" fill="#212140"></path>
                                            <path d="M20.8799 19.9969L15.4225 11.6269C15.2597 11.3774 14.9798 11.2525 14.7 11.2525C14.4201 11.2525 14.1403 11.3774 13.9774 11.6269L12.5149 13.8701L10.524 10.7609C10.3496 10.4887 10.0498 10.3525 9.74996 10.3525C9.45014 10.3525 9.15033 10.4887 8.97595 10.7609L3.12875 19.892C2.77522 20.4441 3.19147 21.1526 3.86957 21.1526H20.1883C20.8211 21.1526 21.2098 20.5032 20.8799 19.9969ZM4.78897 19.8026L9.74996 12.0555L14.7109 19.8026H4.78897ZM16.3141 19.8026L13.3137 15.1169L14.7 12.9907L19.1415 19.8026H16.3141Z" fill="#212140"></path>
                                        </svg>
                                        <span style="padding:10px; margin: -3px; position: absolute; top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);">Discover</span>
                                    </div>
                                    </a>
                                </li>
                                <!-- @if(!empty(Session::get('active_service_list')))
                                @foreach (Session::get('active_service_list') as $ml)
                                @if(Session::get('service_id')==$ml['service_id'])
                                <li class="active">
                                    <a href="#">
                                        {{$ml['title']}}
                                    </a>
                                </li>
                                @else
                                <li>
                                    <a href="/merchant/dashboard/index/{{$ml['key']}}">
                                        {{$ml['title']}}
                                    </a>
                                </li>
                                @endif
                                @endforeach
                                @endif -->

                                <!-- <li class="dropdown-button">
                                    <a class="btn green dd-btn" href="/merchant/dashboard/home"> Available Apps</a>
                                </li> -->

                            </ul>
                        </li>

                    </ul>
                </div>
                <div class="menu-toggler sidebar-toggler hide">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix">
    </div>
    <div class="page-container">
        @include('app.merchant.menu')
        <div class="page-content-wrapper">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

            @yield('content')

            <!-- BEGIN FOOTER -->
            <!-- <div class="page-footer">
                <div class="page-footer-inner">
                    &copy; {{$current_year}} OPUS Net Pvt. Handmade in Pune.
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div> -->

            <script>
                @isset($hide_first_col)
                hide_first_col = true;
                @endisset
            </script>


            <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->

            <script src="/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
            <script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>
            <script src="/assets/admin/layout/scripts/layout.js{{ Helpers::fileTime('js', 'layout') }}" type="text/javascript"></script>
            <script src="/assets/admin/layout/scripts/quick-sidebar.js{{ Helpers::fileTime('js', 'quick-sidebar') }}" type="text/javascript"></script>
            @if($datatablejs!='')
            <script type="text/javascript" src="/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
            <script src="/assets/admin/pages/scripts/{{$datatablejs}}.js"></script>
            @endif
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js'></script>


            <script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
            <script src="/assets/admin/pages/scripts/components-pickers.js?version=2"></script>
            <script src="/assets/global/scripts/swipez.js" type="text/javascript"></script>


            <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
            <script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>
            <script src="/assets/admin/layout/scripts/colorbox.js?version=1603971994"></script>


            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

            <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
            <script type="text/javascript" src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

            @if(isset($show_smart_look_script) && Session::get('logged_in'))
            <script type='text/javascript'>
                window.smartlook || (function(d) {
                    var o = smartlook = function() {
                            o.api.push(arguments)
                        },
                        h = d.getElementsByTagName('head')[0];
                    var c = d.createElement('script');
                    o.api = new Array();
                    c.async = true;
                    c.type = 'text/javascript';
                    c.charset = 'utf-8';
                    c.src = 'https://rec.smartlook.com/recorder.js';
                    h.appendChild(c);
                })(document);
                smartlook('init', '936cedeb2f54186e4d1b006dc4bcc0395e854ecf');
            </script>
            @endif

            <!--Helphero-->
            @isset($help_hero)
            <script src="//app.helphero.co/embed/cjcHsHLBZdr"></script>
            <script>
                HelpHero.identify("{{$merchant_id}}", {
                    role: "Merchant",
                    created_at: "{{$created_date}}"
                });
            </script>
            @endisset



            <!-- END PAGE LEVEL PLUGINS -->
            <script>
                var clipboard = new Clipboard('.btn');
                clipboard.on('success', function(e) {
                    console.log(e);
                });
                clipboard.on('error', function(e) {
                    console.log(e);
                });

                function loader() {
                    document.getElementById('loader').style.display = 'block';
                }

                jQuery(document).ready(function() {
                    Swipez.init(); // init swipez core components
                    Layout.init(); // init current layout
                    ComponentsPickers.init();
                    QuickSidebar.init(); // init quick sidebar
                    @if($datatablejs != '')
                    TableAdvanced.init();
                    @endif



                    $(".iframe").colorbox({
                        iframe: true,
                        width: "80%",
                        height: "90%"
                    });
                });

                @if($script != '') 
                {!!$script!!}
                @endif

                @if(Session::get('default_date_format') == 'd M yyyy')
                @php
                $rpt_format = 'DD MMM YYYY';
                @endphp
                @else
                @php
                $rpt_format = 'MMM DD YYYY';
                @endphp
                @endif

                $('#daterange').daterangepicker({
                    "dateLimit": {
                        "day": 365
                    },
                    locale: {
                        format: "{{ $rpt_format }}"
                    },
                    "autoApply": true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                });
            </script>
            @if(isset($richtext))
            <script src="/assets/global/plugins/summernote/summernote.min.js"></script>

            <script>
                $('.tncrich').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ol', 'ul', 'paragraph', 'height']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['undo', 'redo', 'codeview']]
                    ],
                    callbacks: {
                        onKeydown: function(e) {
                            var t = e.currentTarget.innerText;
                            if (t.trim().length >= 5000) {
                                //delete keys, arrow keys, copy, cut
                                if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                                    e.preventDefault();
                            }
                        },
                        onKeyup: function(e) {
                            var t = e.currentTarget.innerText;
                            $('#maxContentPost').text(5000 - t.trim().length);
                        },
                        onPaste: function(e) {
                            var t = e.currentTarget.innerText;
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            var maxPaste = bufferText.length;
                            if (t.length + bufferText.length > 5000) {
                                maxPaste = 5000 - t.length;
                            }
                            if (maxPaste > 0) {
                                document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                            }
                            $('#maxContentPost').text(5000 - t.length);
                        }
                    }
                });
            </script>
            @endif
            @yield('footer')
            <!-- END JAVASCRIPTS -->
            @livewireScripts
            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
            <script src="{{ asset('assets/admin/layout/scripts/datatable-dropdown-actions-responsive.js') }}" type="text/javascript"></script>

            <!-- timepiker -->

            <!-- DataTables -->
            <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>



</body>
<!-- END BODY -->

</html>