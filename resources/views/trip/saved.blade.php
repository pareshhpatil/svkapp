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
                                <a href="/trip/add" class="btn btn-info">Add Trip</a>
                                <a href="/trip/list/all" class="btn btn-primary">Trip Listing</a>
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
