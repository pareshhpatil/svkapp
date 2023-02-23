@extends('app.master')

@section('header')
@endsection

@section('content')

<div class="page-content">
    <section class="jumbotron bg-transparent py-3" id="header" style="background:#fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6 col-md-6 col-lg-6 col-xl-5 d-none d-lg-block">
                    <h1>You don't have permission to access this page</h1>
                    <p class="lead mb-5">Contact your admin for access.</p>
                </div>
                <div class="col-6 col-md-6 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                    <img width="800" alt="Partner program to earn recurring revenue" class="img-fluid" src="{!! asset('static/images/404.svg') !!}" />
                </div>
            </div>
        </div>
    </section>
</div>
@endsection