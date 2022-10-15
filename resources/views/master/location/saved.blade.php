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
                                <a href="/admin/vendor/view/{{$link}}" class="btn btn-primary">View vendor</a>
                                <a href="/admin/vendor/update/{{$link}}" class="btn btn-success">Update vendor</a>
                                <a href="/admin/vendor/create" class="btn btn-info">Add vendor</a>
                                <a href="/admin/vendor/list" class="btn btn-primary">Vendor Listing</a>
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
