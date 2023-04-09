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
            p {
    margin: 0 0 0px;
}
.page-break {
    page-break-after: always;
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
     <body class="hold-transition sidebar-mini fixed">
        <div class="wrapper">
            
            
            <div class="">
                <section class="content-header">
                    @isset($title)
                    <h1>
                        @if($title=='Logsheet')
                        @if($user_type==1)
                        <button  onclick="logsheetDiv();" style="margin-left: 10px;" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Logsheet Entry</button>
                        <a href="/admin/logsheet/generatebill" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Generate Bill</a>
                        <form id="frm" style="display: contents;" action="" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <select onchange="document.getElementById('frm').submit();" name="type" required class="form-control pull-right" style="width: 150px; margin-right: 10px;" data-placeholder="Select...">
                                <option @if($type==1) selected @endif value="1">Unpaid Bill</option>
                                <option @if($type==2) selected @endif value="2">Unpaid GST</option>
                                <option @if($type==3) selected @endif value="3">All Bills</option>
                            </select>
                        </form>
                        @else
                        <a href="/admin/logsheet/getlogsheet" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Get Logsheet</a>
                        @endif

                        @endif
                        @isset($addnew_button)
                        <button  onclick="logsheetDiv();" style="margin-left: 10px;" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp; Add new</button>
                        @endisset
                        @if($title=='Bill Print')
                        <a  href="/admin/logsheet/downloadbill/{{$link}}" style="margin-left: 10px;" class="btn btn-success btn-sm pull-right"><i class="fa fa-download"></i> &nbsp; Download</a>
                        @endif
                        @if($title=='Logsheet Print')
                        <a  href="/admin/logsheet/downloadlogsheet/{{$link}}" style="margin-left: 10px;" class="btn btn-success btn-sm pull-right"><i class="fa fa-download"></i> &nbsp; Download</a>
                        @endif
                    </h1>
                    @endisset

                </section>
                <section class="content">
                    @yield('content')
                </section>
            </div>
            
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
        <script src="{{ asset('dist/js/custom.js?version=29') }}"></script>
        
    </body>
</html>