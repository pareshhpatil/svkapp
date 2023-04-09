@extends('layouts.nonloggedin')

@section('content')
<div class="row">
    <br>
    <br>
    <div class="col-md-8 col-md-offset-2">
        <div class="alert alert-danger">
            <div class="media">
                <p class="media-heading"><strong>Login failed</strong></p>
                <p class="media-heading">Invalid cookies please try again later.</p>
            </div>

        </div>
    </div>
</div>
@endsection
