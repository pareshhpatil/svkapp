@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <div class="row"  >
                    <form action="" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <input type="text" name="from_date" readonly="" required="" value="{{$from_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy" >
                                <div class="help-block"></div>
                            </div>
                            <div class="col-md-1">
                                <button  type="submit" class="btn btn-primary">Submit </button>
                            </div>

                            <br>
                            <br>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>employee name </th>
                            <th>Mobile </th>
                            <th>Pick up Time </th>
                            <th>Status </th>
                            <th>SMS Status </th>
                            <th style="width: 180px;">SMS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->route_name}}</td>
                            <td>{{$item->employee_name}}</td>
                            <td>{{$item->mobile}}</td>
                            <td>{{$item->pickup_time}}</td>
                            <td>
                                @if($item->seen==0) 
                                Pending
                                @elseif($item->seen==1) 
                                Seen
                                @endif                            
                            </td>
                            <td>{{$item->sms_status}}</td>
                            <td>
                                <a href="#" onclick="document.getElementById('sms').innerHTML = '{{$item->sms}}'" class="btn btn-xs btn-success" data-toggle="modal" data-target="#modal-danger">SMS </a>
                                <a href="/admin/resendsms/{{$item->id}}"  class="btn btn-xs btn-success" >Resend </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


@endsection

<div class="modal modal-primary fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">SMS</h4>
            </div>
            <div class="modal-body">
                <p id="sms"></p>
            </div>
            <div class="modal-footer">
                <a href="#" onclick="CopyToClipboard('sms');"  class="btn btn-outline">Copy</a>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function CopyToClipboard(containerid) {
if (document.selection) { 
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("copy"); 

} else if (window.getSelection) {
    var range = document.createRange();
     range.selectNode(document.getElementById(containerid));
     window.getSelection().addRange(range);
     document.execCommand("copy");
     alert("text copied") 
}}
    </script>