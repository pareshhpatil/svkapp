@extends('layouts.nonloggedin')

@section('content')
<br>
<div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <br>
        <div class="alert alert-danger">
            <div class="media">
                <p class="media-heading"><strong>{{$title}}</strong></p>
                <p>{{$message}} </p>
            </div>
        </div>

        <!-- /.panel -->
    </div>

    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
</div>


@endsection
