@extends('app.master')

@section('header')
@endsection
<style>
    .no-permission-heading {
        font-size: 3em;
        margin-top: 80px;
    }

    .no-permission-img {
        width: 100%;
        height: 83%;
    }
</style>
@section('content')

<div class="page-content">
    <section class="bg-transparent py-3" id="header" style="background:#fff;">
        <div style="padding: 20px;">
            <div class="row align-items-center">
                <div class="col-6 col-md-6 col-lg-6 col-xl-5 d-none d-lg-block">
                    <h1 class="no-permission-heading">You don't have permission to access this page</h1>
                    <p class="lead mb-5">Contact your admin for access.</p>
                </div>
                <div class="col-6 col-md-5 m-auto ml-lg-auto mr-lg-0 col-lg-5 pt-5 pt-lg-0 d-none d-lg-block">
                    <img alt="Partner program to earn recurring revenue" class="img-fluid no-permission-img" src="{!! asset('static/images/404.svg') !!}" />
                </div>
            </div>
        </div>
    </section>
</div>
@endsection