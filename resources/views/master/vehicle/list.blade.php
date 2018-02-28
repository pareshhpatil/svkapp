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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Vehicle #</th>
                            <th>Name </th>
                            <th>Number </th>
                            <th>Car type </th>
                            <th>Brand </th>
                            <th>Purchase date </th>
                            <th style="width: 70px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->vehicle_id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->number}}</td>
                            <td>{{$item->car_type}}</td>
                            <td>{{$item->brand}}</td>
                            <td>{{$item->purchase_date}}</td>
                            <td>
                                <a href="/admin/vehicle/view/{{$item->link}}" class="btn btn-xs btn-primary"><i class="fa fa-table"></i></a>
                                <a href="/admin/vehicle/update/{{$item->link}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/vehicle/delete/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i></a>
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
                <h4 class="modal-title">Delete Vehicle</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this vehicle in the future?</p>
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
