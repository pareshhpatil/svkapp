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
                                <a href="/admin/subscription/view/{{$link}}" class="btn btn-primary">View subscription</a>
                                <a href="/admin/subscription/update/{{$link}}" class="btn btn-success">Update subscription</a>
                                <a href="/admin/bill/subscription/create" class="btn btn-info">Add subscription</a>
                                <a href="/admin/bill/subscription" class="btn btn-primary">Subscription Listing</a>
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
