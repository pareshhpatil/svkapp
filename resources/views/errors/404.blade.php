@extends('home.master')
@section('title', 'Page not found')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-3" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Cause I still haven't found what <em>you're</em> looking for...</h1>
                <p class="lead mb-5">Page not found</p>
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                    src="{!! asset('static/images/404.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                    src="{!! asset('static/images/404.svg') !!}" />
                <h1>Cause I still haven't found what <em>you're</em> looking for...</h1>
                <p class="lead mb-5">Page not found</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h4>Page your are looking for does not exist, but the links below might help.</h4>
            </div>
            <div class="col-md-12 pt-4">
                <a class="btn btn-tertiary btn-lg text-white" href="{{ config('app.APP_URL') }}">Homepage</a>
                <a class="btn btn-secondary btn-lg text-white" href="{{ config('app.APP_URL') }}faq">FAQ</a>
            </div>
        </div>
    </div>
</section>

@endsection
