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
                                <a href="/admin/company/view/{{$link}}" class="btn btn-primary">View company</a>
                                <a href="/admin/company/update/{{$link}}" class="btn btn-success">Update company</a>
                                <a href="/admin/company/create" class="btn btn-info">Add company</a>
                                <a href="/admin/company/list" class="btn btn-primary">Company Listing</a>
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
