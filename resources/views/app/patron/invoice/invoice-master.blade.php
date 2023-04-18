<html lang="en">

<head>
    <meta charset="utf-8" />
   
    <meta name="author" content="Swipez">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   
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
    
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <script src="/assets/admin/layout/scripts/invoice.js?version=1649936891" type="text/javascript"></script>
    <link href="/assets/admin/pages/css/invoice.css" rel="stylesheet" type="text/css" />
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

    <link rel="shortcut icon" href="/images/briq.ico" />
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
   
    
    
   
    <div class="clearfix">
    </div>
    <div class="page-container" >
     
        <div >
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



            <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <script src="/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>

            <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
            <script src="/assets/admin/pages/scripts/subscription-datatable.js" type="text/javascript"></script>

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
          

            <script src="/assets/admin/pages/scripts/clipboard/dist/clipboard.min.js"></script>
            <script src="/assets/admin/layout/scripts/colorbox.js?version=1603971994"></script>


            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

            <script type="text/javascript" src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

            <script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
            <script type="text/javascript" src="/assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
            <script type="text/javascript" src="/assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

            <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/transaction.js?version=1646398683" type="text/javascript"></script>

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

                $('#daterange').daterangepicker({
                    "dateLimit": {
                        "day": 365
                    },
                    locale: {
                        format: 'DD MMM YYYY'
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
           
            @yield('footer')
            <!-- END JAVASCRIPTS -->
            @livewireScripts
            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js"></script>
            <script src="{{ asset('assets/admin/layout/scripts/datatable-dropdown-actions-responsive.js') }}" type="text/javascript"></script>

            <!-- DataTables -->
            <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
</body>
<!-- END BODY -->

</html>