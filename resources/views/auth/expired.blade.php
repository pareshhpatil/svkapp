@extends('layouts.nonloggedin')

@section('content')
<div class="row">
    <br>
    <br>
    <div class="col-md-8 col-md-offset-2">
        <div class="alert alert-danger">
            <div class="media">
                <p class="media-heading"><strong>Session expired</strong></p>
                <p class="media-heading">Session expired, please login GBS Portal and redirect again to this system.</p>
            </div>

        </div>
    </div>
</div>
@endsection
