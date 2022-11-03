@extends('home.master')
@section('title', 'Page not found')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-3" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>{{$title}}</h1>
                <p class="lead mb-5">{!! $message !!}</p>
                <div class="col-md-12 pt-4">
                    @if($return_url!='')
                    <a class="btn btn-primary btn-lg text-white" href="{{$return_url}}">@isset($button_text) {{$button_text}} @else Retry now @endisset</a>
                    <a class="btn btn-outline-primary btn-lg" href="{{ config('app.APP_URL') }}merchant/dashboard">Back to Dashboard</a>
                    @else
                    <a class="btn btn-tertiary btn-lg text-white" href="{{ config('app.APP_URL') }}">Homepage</a>
                    <a class="btn btn-secondary btn-lg text-white" href="{{ config('app.APP_URL') }}faq">FAQ</a>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                     src="/static/images/{{$image}}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Partner program to earn recurring revenue" class="img-fluid"
                     src="/static/images/{{$image}}" />
                <h1>Oops</h1>
                <p class="lead mb-5">{!! $message !!}</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h4>{!! $message !!}</h4>
            </div>
            <div class="col-md-12 pt-4">
                @if($return_url!='')
                <a class="btn btn-tertiary btn-lg text-white" href="{{$return_url}}">@isset($button_text) {{$button_text}} @else Retry now @endisset</a>
                <a class="btn btn-secondary btn-lg text-white" href="{{ config('app.APP_URL') }}merchant/dashboard">Back to Dashboard</a>
                @else
                <a class="btn btn-tertiary btn-lg text-white" href="{{ config('app.APP_URL') }}">Homepage</a>
                <a class="btn btn-secondary btn-lg text-white" href="{{ config('app.APP_URL') }}faq">FAQ</a>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
