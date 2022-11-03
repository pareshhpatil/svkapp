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
    <link rel="stylesheet" type="text/css" href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/> 

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
                        <a itemprop="url" title="Swipez" href="{{$server_name}}">
                            <img style="max-height: 50px;" class="logo-default hidden-xs" src="/assets/admin/layout/img/logo.png?v=5" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                        </a>
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
                        <li class="dropdown hidden-xs">
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
                        </li>
                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-th"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-title">
                                    Active Apps
                                </li>
                                <li class="divider">
                                </li>
                                @if(!empty(Session::get('active_service_list')))
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
                                @endif

                                <li class="dropdown-button">
                                    <a class="btn green dd-btn" href="/merchant/dashboard/home"> Available Apps</a>
                                </li>

                            </ul>
                        </li>
                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-user-circle"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="https://helpdesk.swipez.in/help/">
                                        <i class="fa fa-question-circle"></i> Help </a>
                                </li>
                                <li>
                                    <a href="/logout">
                                        <i class="fa fa-sign-out"></i> {{$menu['logout']}} </a>
                                </li>
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
            <div class="page-footer">
                <div class="page-footer-inner">
                    &copy; {{$current_year}} OPUS Net Pvt. Handmade in Pune.
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>

            <script>
                @isset($hide_first_col)
                hide_first_col=true;
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