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
                <a href="/admin/dashboard" class="logo" style="background-color: #ffffff;">
                    <span class="logo-mini"><img src="{{ asset('dist/img/'.$company_logo) }}"></span>
                    <span class="logo-lg"><img src="{{ asset('dist/img/'.$company_logo) }}"></span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-custom-menu pull-left">
                        <h4 style="color: #ffffff;vertical-align: middle;margin-top: 15px;">{{$company_name}}</h4>
                    </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <?php $name = Session::get('user_name'); ?>
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('dist/img/avatar.png') }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{$name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <img src="{{ asset('dist/img/avatar.png') }}" class="img-circle" alt="User Image">
                                        <p>
                                            {{$name}}
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/admin/profile" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu" data-widget="tree">
                        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                        <li><a href="/admin/logsheet"><i class="fa fa-file-text"></i> <span>Log sheet</span></a></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-cogs"></i> <span>Masters</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="/admin/employee/list">
                                        <i class="fa fa-circle-o"></i> Employee</a>
                                </li>
                                <li>
                                    <a href="/admin/vehicle/list">
                                        <i class="fa fa-circle-o"></i> Vehicle</a>
                                </li>
                                <li>
                                    <a href="/admin/master/vendor">
                                        <i class="fa fa-circle-o"></i> Vendor</a>
                                </li>
                                <li>
                                    <a href="/admin/company/list">
                                        <i class="fa fa-circle-o"></i> Company</a>
                                </li>
                                <li>
                                    <a href="/admin/master/card">
                                        <i class="fa fa-circle-o"></i> Card</a>
                                </li>
                                <li>
                                    <a href="/admin/master/location">
                                        <i class="fa fa-circle-o"></i> Location</a>
                                </li>

                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-users"></i> <span>Employee</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="/admin/employee/absent">
                                        <i class="fa fa-circle-o"></i> Absent</a>
                                </li>
                                <li>
                                    <a href="/admin/employee/advance">
                                        <i class="fa fa-circle-o"></i> Advance</a>
                                </li>
                                <li>
                                    <a href="/admin/employee/overtime">
                                        <i class="fa fa-circle-o"></i> Over Time</a>
                                </li>
                                <li>
                                    <a href="/admin/employee/payment">
                                        <i class="fa fa-circle-o"></i> Payment</a>
                                </li>

                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-car"></i> <span>Vehicle</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="/admin/transaction/fuel">
                                        <i class="fa fa-circle-o"></i> Fuel</a>
                                </li>
                                <li>
                                    <a href="/admin/vehicle/replacecab">
                                        <i class="fa fa-circle-o"></i> Replace Cab</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    @isset($title)
                    <h1>
                        {{$title}}
                        @isset($titledeccription)
                        <small>{{$titledeccription}}</small>
                        @endisset
                        @isset($addnewlink)
                        <a href="{{$addnewlink}}" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Add new</a>
                        @endisset
                        @if($title=='Logsheet')
                        <button  onclick="logsheetDiv();" style="margin-left: 10px;" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Logsheet Entry</button>
                        <a href="/admin/logsheet/generatebill" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Generate Bill</a>
                        @endif
                        @isset($addnew_button)
                        <button  onclick="logsheetDiv();" style="margin-left: 10px;" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Add new</button>
                        @endisset
                        @if($title=='Logsheet Print')
                        <button  onclick="window.print();" style="margin-left: 10px;" class="btn btn-success btn-sm pull-right"><i class="fa fa-print"></i> &nbsp; Print</button>
                        @endif
                    </h1>
                    @endisset

                </section>
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
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
        <script src="{{ asset('dist/js/demo.js') }}"></script>
        <script src="{{ asset('dist/js/custom.js') }}"></script>
        <script>
                            $(function () {
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

                            @isset($logsheet_detail)
                                    calculateLogsheet();
                                    @endisset
        </script>
    </body>
</html>