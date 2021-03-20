<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{$title}}</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style>
            #load{
                width:100%;
                height:100%;
                position:fixed;
                z-index:9999;
                background:url("{{ asset('dist/img/loader.gif') }}") no-repeat center center rgba(0,0,0,0.25)
            }
        </style>
        <script>
            document.onreadystatechange = function () {
                var state = document.readyState
                if (state == 'complete') {
                    document.getElementById('interactive');
                    document.getElementById('load').style.visibility = "hidden";
                }
            }
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini fixed">
        <div id="load"></div>
        <div class="wrapper">
            <header class="main-header">
                <a href="/{{$login_type}}/dashboard" class="logo" style="background-color: #ffffff;">
                    <span class="logo-mini"><img style="max-height:45px;" src="http://siddhivinayaktravel.in/dist/img/1539330364.png"></span>
                    <span class="logo-lg"><img style="max-height:45px;" src="http://siddhivinayaktravel.in/dist/img/1539330364.png"></span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu pull-left">
                        <h4 style="color: #ffffff;vertical-align: middle;margin-top: 15px;">{{$title}}</h4>
                    </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('dist/img/avatar.png') }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">Employee</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header" style="height: 140px;">
                                        <img src="{{ asset('dist/img/avatar.png') }}" class="img-circle" alt="User Image">
                                        <p>
                                            Employee
                                        </p>
                                    </li>
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    
                </section>
            </aside>
            <div class="content-wrapper">
                <section class="content">
                    @yield('content')
                </section>
            </div>
            <footer class="main-footer hidden-print">
                <small>Copyright &copy; 2018 Siddhivinayak travels All rights reserved.</small>
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>
        <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('dist/js/demo.js') }}"></script>
        <script src="{{ asset('dist/js/custom.js?version=28') }}"></script>
        <script>
                            $(function () {
                                $('.select2').select2()
                                //Date picker
                                $('.date-picker').datepicker({
                                    format: 'dd-mm-yyyy',
                                    todayHighlight: true,
                                    autoclose: true
                                })

                                $('#example1').DataTable(
                                        {
                                            "order": [[0, "desc"]]
                                        })
                                $('.timepicker').timepicker({
                                    showInputs: false
                                })

                                $('.month-picker').datepicker({
                                    autoclose: true,
                                    minViewMode: 1,
                                    format: 'M-yyyy'
                                });
                            })

        </script>
    </body>
</html>