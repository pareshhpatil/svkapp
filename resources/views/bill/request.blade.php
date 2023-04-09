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
                            <th>Trans #</th>
                            <th>Date</th>
                            <th>Employee Name </th>
                            <th>Amount </th>
                            <th>Note </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->request_id}}</td>
                            <td>{{$item->date}}</td>
                            <td><a target="_BLANK" href="/admin/employee/view/{{$item->emplink}}">{{$item->employee_name}}</a></td>
                            <td>{{$item->amount}}</td>
                            <td style="max-width: 250px;">{{$item->note}}</td>
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
