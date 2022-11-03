@extends('home.master')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <!-- for iPad and mobile -->
            <div class="card card-border-none d-sm-none pb-5 px-4">
                <div class="card-media">
                    <img alt="Access your billing software from your home" src="{!! asset('images/stay-home-stay-safe.svg') !!}"
                         class="img-fluid" />
                </div>
            </div>
            <!-- end -->
            <div class="col-md-6">
                <p>Easybiz just got better on Swipez. Moneycontrol and Swipez have partnered to bring you an improved version of Easybiz that will help you automate your business operations. 
                    You can now manage your receivables payables and GST filing of a single dashboard.
                </p>
                <h4>Key Benefits</h4>
                <ul>
                    <li>Manage your customers, vendors and franchisees from a single console</li>
                    <li>Collect payments upto 35% faster with the Easybiz online invoicing and collections system</li>
                    <li>Reconcile payments received via all payment modes centrally</li>
                    <li>Organize your expenses and make payouts to vendors centrally</li>
                    <li>Integrate seamlessly with Tally. Transfer data straight into Tally via a simple excel export</li>
                    <li>Send notifications and reminders to your customers from your dasboard</li>
                    <li>Automate GST tax filing</li>
                </ul>
                <p>We care about your business. Receive dedicated support for your account to help digitize your business operations faster.
                </p>
            </div>
            <div class="col-md-6 center-text">
                <img style="max-width: 50%;" class="mb-3" src="https://images.moneycontrol.com/images/common/headfoot/logo.png"/>
                <h4 class="center-text">Set your password
                </h4>
                <p class="center-text">Swipez products help you make time for growth!</p>
                @isset($errors)
                @if($errors->any())
                <span class="color-red small text-left">{!! implode('', $errors->all('<div>:message</div>')) !!}</span>
                @endif
                @endisset
                @guest
                <form method="post" action="/login/passwordsave" >
                    <div class="row" >
                        <div class="col-12">
                            @if($company_name=='')
                            <input type="text" required  name="company_name" class="form-control big-text mb-2"  placeholder="Company name">
                            @else 
                            <input type="hidden"  name="company_name" value="{{$company_name}}" class="form-control big-text mb-2"  >
                            @endif
                            <input type="password" required  name="password" class="form-control big-text mb-2"  placeholder="Set password">
                            <input type="password" required  name="password_confirmation" class="form-control big-text"  placeholder="Confirm password">
                            <input type="hidden" name="recaptcha_response" >
                            <input type="hidden" name="user_id" value="{{$user_id}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                        <div class="col-12 mt-2">
                            <button id="web-btn" class="btn btn-primary pull-left big-text" type="submit">Set password</button>
                        </div>
                    </div>
                </form>
                @else
                <a class="btn btn-lg btn-tertiary text-white" href="/merchant/dashboard">Dashboard</a>
                @endguest
            </div>
        </div>

    </div>
</section>

<section class="jumbotron py-5" id="clients">
    <div class="container">
        <h2 class="text-center pb-5">Trusted by Great Companies</h2>
        <div class="row">
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Reliance Mutual Fund" class="img-grayscale"
                     src="{!! asset('images/home/reliance-mutual-fund.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                     src="{!! asset('images/home/global-insurance.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                     src="{!! asset('images/home/malaka-spice.png') !!}" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client IRIS GST" class="img-grayscale"
                     src="{!! asset('images/home/iris-gst.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Reliance mutual fund" class="img-grayscale"
                     src="{!! asset('images/home/reliance-mutual-fund.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                     src="{!! asset('images/home/global-insurance.png') !!}" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                     src="{!! asset('images/home/malaka-spice.png') !!}" />
            </div>
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez client IRIS GST" class="img-grayscale"
                     src="{!! asset('images/home/iris-gst.png') !!}" />
            </div>
        </div>
    </div>
</section>
@endsection
