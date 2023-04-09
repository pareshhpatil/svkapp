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
                                <a href="/admin/vehicle/view/{{$link}}" class="btn btn-primary">View vehicle</a>
                                <a href="/admin/vehicle/update/{{$link}}" class="btn btn-success">Update vehicle</a>
                                <a href="/admin/vehicle/create" class="btn btn-info">Add vehicle</a>
                                <a href="/admin/vehicle/list" class="btn btn-primary">Vehicle Listing</a>
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
