@extends('layouts.test')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <chat :user="{{ Auth::user() }}"/>
        </div>
        <example-component :user="{{ Auth::user() }}"/>
    </div>
</div>
@endsection
