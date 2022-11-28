@extends('home.master')

@section('content')
<section id="steps" class="jumbotron bg-transparent py-4">
    <div class="container">


        <div class="row justify-content-center">

            <div class="col-md-8" style="padding: 10px 10px 10px 10px;">
                <h1 class="text-center">Get in touch</h1>
                <br>
            <iframe style="width: 100%;height: 520px;border:none;" src="/helpdesk/contactus/swipez/{{$subject}}"></iframe>
            </div>
        </div>
    </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it free. No credit
                    card required.</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
            </div>
        </div>
    </div>
</section>
@endsection
