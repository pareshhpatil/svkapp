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
                            <div class="col-md-3">
                                <select name="vehicle_id" required class="form-control" data-placeholder="Select...">
                                    <option value="0">Select vehicle</option>
                                    @foreach ($vehicle_list as $item)
                                    @if($vehicle_id==$item->vehicle_id)
                                    <option selected value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                    @else
                                    <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                    @endif

                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-1">
                                <button  type="submit" class="btn btn-primary">Submit </button>
                            </div>
                            <div class="col-md-1">
                                <button name="export" type="submit" class="btn btn-success">Export </button>
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
                            <th>Date</th>
                            <th>Vehicle </th>
                            <th>Logsheet No </th>
                            <th>Employee </th>
                            <th>Location </th>
                            <th>Details </th>
                            <th>Start KM </th>
                            <th>End KM </th>
                            <th>Total KM </th>
                            <th>Shift Time</th>
                            <th>Toll</th>
                            <th style="width: 40px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->date}}</td>
                            <td>{{$item->vehicle_name}}</td>
                            <td>{{$item->logsheet_no}}</td>
                            <td>{{$item->employee}}</td>
                            <td>{{$item->location}}</td>
                            <td>{{$item->pickdrop}}</td>
                            <td>{{$item->start_km}}</td>
                            <td>{{$item->end_km}}</td>
                            <td>{{$item->total_km}}</td>
                            <td>{{$item->shift_time}}</td>
                            <td>{{$item->toll}}</td>
                            <td>
                                <a href="#" onclick="document.getElementById('deleteanchor').href = '/admin/mis/deletemis/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i></a>
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
                <h4 class="modal-title">Delete MIS</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this MIS in the future?</p>
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
