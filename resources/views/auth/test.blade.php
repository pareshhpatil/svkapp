@extends('layouts.nonloggedin')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-2">
        <div class="login-panel panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title">Global Login</h3>
            </div>
            <div class="panel-body">
                <fieldset>
                    <!-- Change this to a button or input when using this as a form -->
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/SAL">Super Admin Login</a>
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/GUL">Global Admin Login</a>
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/GEL">Global Executive Login</a>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-md-offset-1">
        <div class="login-panel panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title">Corporate Login</h3>
            </div>
            <div class="panel-body">
                <fieldset>
                    <!-- Change this to a button or input when using this as a form -->
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/CAL">Corporate Admin Login</a>
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/LAL">Location Admin Login</a>
                    <a class="btn btn-lg btn-success btn-block" href="/auth/login/EMPL">Employee Login</a>
                </fieldset>
            </div>
        </div>
    </div>
</div>
@endsection
