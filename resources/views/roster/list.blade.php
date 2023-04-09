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
                            <div class="col-md-3">
                                <input type="text" name="to_date" readonly="" required="" value="{{$to_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy" >
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
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date </th>
                            <th>Pickup/Drop </th>
                            <th>Route </th>
                            <th>Status </th>
                            <th style="width: 180px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->roster_id}}</td>
                            <td>{{$item->date}}</td>
                            <td>{{$item->pickupdrop}}</td>
                            <td>{{$item->route_name}}</td>
                            <td>
                                @if($item->status==0) 
                                Submitted
                                @elseif($item->status==1) 
                                Asigned
                                @elseif($item->status==2) 
                                Completed
                                @endif                            
                            </td>
                            <td>
                                @if($item->status==0) 
                                <a href="/admin/roster/updateroster/{{$item->link}}" class="btn btn-xs btn-success" >Update <i class="fa fa-edit"></i></a>
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/roster/deleteroster/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger">Delete <i class="fa fa-remove"></i></a>
                                @if($login_type!='admin')
                                <a target="_BLANK" href="/admin/roster/print/{{$item->link}}" class="btn btn-xs btn-success" >Print <i class="fa fa-print"></i></a>
                                @endif
                                @elseif($item->status==1) 
                                <a target="_BLANK" href="/roster/trip/admin/{{$item->link}}" class="btn btn-xs btn-primary" >Deatil <i class="fa fa-table"></i></a>
                                <a target="_BLANK" href="/admin/roster/print/{{$item->link}}" class="btn btn-xs btn-success" >Print <i class="fa fa-print"></i></a>
                                @if($login_type=='superadmin')
                                <a href="/admin/roster/sendnotification/{{$item->link}}" class="btn btn-xs btn-warning" >Notify <i class="fa fa-bell"></i></a>
                                @endif

                                @endif     
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/roster/closeroster/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger">Close <i class="fa fa-close"></i></a>
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

<div class="modal modal-danger fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete Roster</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this Roster in the future?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                <a id="deleteanchor" href="" class="btn btn-outline">Delete</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
