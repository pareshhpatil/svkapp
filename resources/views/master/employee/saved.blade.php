@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel-body">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        Information saved
                    </div>
                    <div class="panel-body">
                        <p>{{$success}}</p>
                        <br/>
                        <div class="form-group">
                            <div>
                                <a href="/admin/employee/view/{{$link}}" class="btn btn-primary">View employee</a>
                                <a href="/admin/employee/update/{{$link}}" class="btn btn-success">Update employee</a>
                                <a href="/admin/employee/create" class="btn btn-info">Add Employee</a>
                                <a href="/admin/employee/list" class="btn btn-primary">Employee Listing</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@endsection
