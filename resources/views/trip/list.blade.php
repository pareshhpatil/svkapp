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
                            <th>Trip #</th>
                            <th>Vehicle Type </th>
                            <th>Date Time </th>
                            <th>Pickup location </th>
                            <th>Drop location </th>
                            <th>Passengers </th>
                            <th>Status </th>
                            <th style="width: 70px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->req_id}}</td>
                            <td>{{$item->vehicle_type}}</td>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y')}} {{ \Carbon\Carbon::parse($item->time)->format('h:i A')}}</td>
                            <td>{{$item->pickup_location}}</td>
                            <td>{{$item->drop_location}}</td>
                            <td>{{$item->passengers}}</td>
                            <td>
                                @if($item->status=='Requested')
                                <span style="padding: 0px 5px 0px 5px;" class="lable label-sm label-primary">{{$item->status}} </span>
                                @elseif($item->status=='Assigned')
                                <span style="padding: 0px 5px 0px 5px;" class="lable label-sm label-warning">{{$item->status}} </span>
                                @elseif($item->status=='Completed')
                                <span style="padding: 0px 5px 0px 5px;" class="lable label-sm label-success">{{$item->status}} </span>
                                @elseif($item->status=='Rejected')
                               <span style="padding: 0px 5px 0px 5px;" class="lable label-sm label-danger">{{$item->status}} </span>
                               @endif
                                
                            </td>
                            <td>
                                @if($item->status=='Assigned')
                                <a href="/trip/complete/{{$item->link}}" target="_BLANK" class="btn btn-xs btn-primary">Complete</a>
                                @endif
                                
                                @if($login_type!='client' && $item->status=='Requested')
                                <a href="/trip/schedule/{{$item->req_link}}" target="_BLANK" class="btn btn-xs btn-warning">Schedule</a>
                                @endif
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
                <h4 class="modal-title">Delete Company</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this company in the future?</p>
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
