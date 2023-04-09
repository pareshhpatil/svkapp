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
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Transaction</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Credit</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="activity">
                    <div class="panel panel-primary">
                        <div class="panel-body" style="overflow: auto;">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 50px;">Date</th>
                                        <th>Name </th>
                                        <th>Narrative </th>
                                        <th>Amount </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                    <tr class="odd gradeX">
                                        <td>{{$item->transaction_id}}</td>
                                        <td>{{$item->paid_date}}</td>
                                        <td><a target="_BLANK" href="/admin/employee/view/{{$item->emplink}}">{{$item->employee_name}}</a></td>
                                        <td>{{$item->narrative}}</td>
                                        <td>{{$item->amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                <div class="panel panel-primary">
                        <div class="panel-body" style="overflow: auto;">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 60px;">Date</th>
                                        <th>Narrative </th>
                                        <th>Amount </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clist as $item)
                                    <tr class="odd gradeX">
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->paid_date}}</td>
                                        <td>{{$item->narrative}}</td>
                                        <td>{{$item->amount}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>








        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection