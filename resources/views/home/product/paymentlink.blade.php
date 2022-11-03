@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Generate payment links. Get paid instantly</h1>
                <p class="lead">
                    Send payment links and collect money directly into your bank account within minutes. Provide your
                    customers the convenience to make payments using credit cards, debit cards, net banking, wallets or
                    UPI apps. Starting online payment collections was never this easy!
                </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Create payment link and collect payments online" class="img-fluid" src="{!! asset('images/product/payment-link.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Create payment link and collect payments online" class="img-fluid" src="{!! asset('images/product/payment-link.svg') !!}" />
                <h1>Generate payment links. Get paid instantly</h1>
                <p class="lead mb-2">Send payment links and collect money directly into your bank account within
                    minutes. Provide your
                    customers the convenience to make payments using credit cards, debit cards, net banking, wallets or
                    UPI apps. Starting online payment collections was never this easy!</p>
                @include('home.product.web_register',['d_type' => "mob"])

            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2 class="text-white">Receive payments in just a few clicks</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Accept payments in <b class="text-primary">3</b> simple steps</h2>
        <div class="container py-2">
            <div class="row no-gutters pb-5 justify-content-center">
                <div class="col-sm-1 text-center flex-column d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">1</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm col-md-6 py-2">
                    <div class="card card-border-none shadow">
                        <div class="card-body">
                            <h2 class="card-title">Create a payment link</h2>
                            <p class="card-text">Create a payment link to help your customers make payments using
                                multiple payment modes like credit card, debit card, UPI, net banking and wallets</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters pb-5 justify-content-center">
                <div class="col-sm-1 text-center flex-column d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">2</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm col-md-6 py-2">
                    <div class="card card-border-none shadow">
                        <div class="card-body">
                            <h2 class="card-title">Share link</h2>
                            <p class="card-text">Copy and share the payment link URL with your customer via WhatsApp,
                                Email, SMS or even social channels like Facebook or Instagram</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row no-gutters pb-5 justify-content-center">
                <div class="col-sm-1 text-center flex-column d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">3</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm col-md-6 py-2">
                    <div class="card card-border-none shadow">
                        <div class="card-body">
                            <h2 class="card-title">Collect payments</h2>
                            <p class="card-text">Receive funds directly into your bank account. Both you and your
                                customer are sent a receipt and are notified of successful payments via Email & SMS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Create an account to start creating payment links. It's <b class="text-primary">FREE</b>!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Collect payments using payment links" class="img-fluid" src="{!! asset('images/product/payment-link/features/payment-link.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <img alt="Collect payments using payment links" class="img-fluid" src="{!! asset('images/product/payment-link/features/payment-link.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Payment links : The fastest way to collect payments</strong></h2>
                <p class="lead">Simple, flexible and effective. Use Swipez’s payment links to get paid on time. Every
                    time 💸👍</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Payment links : The fastest way to collect payments</strong></h2>
                <p class="lead">Simple, flexible and effective. Use Swipez’s payment links to get paid on time. Every
                    time 💸👍</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Accept all payment modes" class="img-fluid" src="{!! asset('images/product/payment-link/features/accept-all-payment-modes.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <img alt="Accept all payment modes" class="img-fluid" src="{!! asset('images/product/payment-link/features/accept-all-payment-modes.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Accept all payment modes</strong></h2>
                <p class="lead">Get your customer to pay via their preferred payment mode. Accept payments via UPI,
                    Debit card, Credit card, Net banking and Wallets. Or even offline payments like Cash, Cheque or
                    NEFTs — reconcile all our customer payments with ease</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Accept all payment modes</strong></h2>
                <p class="lead">Get your customer to pay via their preferred payment mode. Accept payments via UPI,
                    Debit card, Credit card, Net banking and Wallets. Or even offline payments like Cash, Cheque or
                    NEFTs — reconcile all our customer payments with ease</p>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Accept payments in any currency" class="img-fluid" src="{!! asset('images/product/payment-link/features/multi-currency-payment-link.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Accept payments in any currency" class="img-fluid" src="{!! asset('images/product/payment-link/features/multi-currency-payment-link.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Accept international payments</strong></h2>
                <p class="lead"> Receive international payments in 50+ foreign currencies from across the world.
                    Accept international payments in different currencies with a simple payment link integrated seamlessly with an international
                    payment gateway. Automate real-time currency conversion and international payment collections directly to your preferred account.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Accept international payments</strong></h2>
                <p class="lead"> Receive international payments in 50+ foreign currencies from across the world.
                    Accept international payments in different currencies with a simple payment link integrated seamlessly with an international
                    payment gateway. Automate real-time currency conversion and international payment collections directly to your preferred account.</p>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Payment link with flexible amounts" class="img-fluid" src="{!! asset('images/product/payment-link/features/flexible-amount.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Payment link with flexible amounts" class="img-fluid" src="{!! asset('images/product/payment-link/features/flexible-amount.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Flexible payment amounts</strong></h2>
                <p class="lead">Create payment links with a fixed amount or allow your customers to enter the amount.
                    Create payment links as per your business requirement</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Flexible payment amounts</strong></h2>
                <p class="lead">Create payment links with a fixed amount or allow your customers to enter the amount.
                    Create payment links as per your business requirement</p>
            </div>
            <!-- end -->
        </div>





        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="URL shortened payment link" class="img-fluid" src="{!! asset('images/product/payment-link/features/shortened-payment-link.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="URL shortened payment link" class="img-fluid" src="{!! asset('images/product/payment-link/features/shortened-payment-link.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Short link for payment</strong></h2>
                <p class="lead">Payment links are shortened to help you include them in your communications like SMS or
                    text messages. Our built in URL shortener shortens your payment links automatically</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Short link for payment</strong></h2>
                <p class="lead">Payment links are shortened to help you include them in your communications like SMS or
                    text messages. Our built in URL shortener shortens your payment links automatically</p>
            </div>
            <!-- end -->
        </div>





        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Payment link for WhatsApp" class="img-fluid" src="{!! asset('images/product/payment-link/features/payment-link-for-whatsapp.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Payment link for WhatsApp" class="img-fluid" src="{!! asset('images/product/payment-link/features/payment-link-for-whatsapp.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>WhatsApp payment link generator</strong></h2>
                <p class="lead">Create simple, easy-to-use payment links and share via WhatsApp for one-time services
                    like alterations, customized packaging, warranty extensions, and more</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>WhatsApp payment link generator</strong></h2>
                <p class="lead">Create simple, easy-to-use payment links and share via WhatsApp for one-time services
                    like alterations, customized packaging, warranty extensions, and more</p>
            </div>
            <!-- end -->
        </div>






        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Facebook payment link generator" class="img-fluid" src="{!! asset('images/product/payment-link/features/facebook-payment-link.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Facebook payment link generator" class="img-fluid" src="{!! asset('images/product/payment-link/features/facebook-payment-link.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Facebook payment link generator</strong></h2>
                <p class="lead">Running a business on a social network or hosting e-commerce sellers on your handle and
                    need to collect payments? Generate payment links that can be shared easily over multiple social
                    networks like Facebook, Twitter, Instagram, etc. Offer your customers a quick checkout option</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Facebook payment link generator</strong></h2>
                <p class="lead">Running a business on a social network or hosting e-commerce sellers on your handle and
                    need to collect payments? Generate payment links that can be shared easily over multiple social
                    networks like Facebook, Twitter, Instagram, etc. Offer your customers a quick checkout option</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Get your free account today</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Register now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Register now</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Free payment link generator for every kind of business</h2>
                <p class="lead text-white">Generate free payment links for your home-based or small businesses. You
                    don’t need an
                    app or a website to create payment links and start accepting online payments.</p>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="link" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Send payment links</h3>
                    <p>Simple to generate and share payment links. Your customers can pay using any device. Get notified
                        on payment.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                        <path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">All payment options</h3>
                    <p>Provide your customers all payment options like credit card, debit card, net banking, UPI or
                        wallets. Make it easy for your customer to pay.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bell" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Reduce due payments</h3>
                    <p>Your customers receive automated reminders of due payments on a timely basis. Ensuring your
                        payments are paid before every due every time!</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Payments mapped to customers</h3>
                    <p>Stay on top of your paid and unpaid customer list. Easily figure out who has paid you and
                        who is yet to pay you within minutes.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h2 class="display-4 text-white">Collect payments without a website</h2>
                <p class="text-white lead"> Hassle-free payment collection for utilities like milk, cable, and internet services with payment links according to your customized plans</p>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who are already
                    using Swipez
                    payment links</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now my team is able to collect payments from my customers with so much ease! We just
                                share a simple link and collect payments directly in to our bank account"</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Solutions</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Makes our lives so much easier. Now we collect payments faster without any delays.
                                Payment link product is completely customizable to our business needs"</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Vikas Sankhla</strong>
                            </p>
                            <p>
                                <em>Co-founder, Car Nanny</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron text-white bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3>Power your business with a comprehensive collections solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg bg-secondary text-white" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section id="faq" class="jumbotron py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row text-center justify-content-center pb-3">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h2 class="display-4">FAQ'S</h2>
                        <p class="lead">Looking for more info? Here are some things we're commonly asked</p>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Is the payment links software free plan really free?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, You can use all our features for free. We only charge the <a href="{{ route('home.pricing.onlinetransactions') }}">lowest online payment
                                    transaction fee</a> for
                                payments that you collect.
                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What is the difference between the payment links software free and paid plans?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Free plans offers you unlimited invoices and estimates similar to paid plans but there
                                are a few key differentiator such as, you get reduced transaction charges in the paid
                                plans, SMS Notifications, GST filing, API access and the ability to manage franchises.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Is there any limit on the amount of customers I can add for payment links in all three
                            plans?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there is no limit on the amount of customers you can add in any of our plans.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree1">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree1">
                            Are the different international currencies going to be converted using the most recent exchange rates?
                        </div>
                        <div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, all foreign currencies will be automatically converted to INR at the current exchange rate.
                                Payments received from all over the world will be transferred immediately to your bank account in INR.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Are my invoices and data safe on Swipez payment link software system?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                At Swipez we really value our customer's privacy. You rely on us for a big part of your
                                business and billing, so we really take your needs seriously. That is why the security
                                of our software, systems and data are our number one priority. All information that is
                                transmitted between your browser and Swipez is protected with 256-bit SSL encryption.
                                This ensures that your data is secure in transit. All data you have entered into Swipez
                                sits securely behind web firewalls. This system is used by some of the biggest companies
                                in the world and is widely acknowledged as safe and secure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Who’s there if I need any help related to payment link system?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a team of experts who are available for assistance through email, chat and our
                                call centre.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Can I use Swipez’s payment links software on multiple devices and platforms?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can flawlessly use it on multiple devices and platforms such as mobiles,
                                tablets, desktops, laptops etc.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Do I need to download anything to start using Swipez collections software?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third party software to start using the Swipez
                                payment links. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez payment links effortlessly. 
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Can multiple people from my team use the collection application at one time?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez collections software can be used by you and your team members together.
                                Multiple team members can access the platform at one time. You can also assign roles and
                                give access as per your organization structure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Can I enable part payments for my customers?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can setup part payments for an invoice. A invoice setup with part payments will
                                remain open till the complete amount has been paid. You can also setup the minimum
                                amount a customer is allowed to pay to make a valid part payment.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            Can I restrict access of data as per my organization structure?
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create multiple roles and decide if you would like to give full control or
                                restrict access to certain features and data sets for your employees.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThirteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            Can I track offline invoice payments?
                        </div>
                        <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can track invoice payments that are made offline via cash / cheque / NEFT.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFourteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            Will my customers get reminders to make payments?
                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Your customers will be reminded to make payments via Email and SMS notifications.
                                You can set the frequency of reminders and the reminder text as per your preference too.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeventeen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                            Can I send a payment link and collect payments online?
                        </div>
                        <div id="collapseSeventeen" class="collapse" aria-labelledby="headingSeventeen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can simply send a payment link over WhatsApp, SMS and Email to your customers.
                                You can fix the amount the customer has to pay or let the customer pay a variable amount
                                as per your requirement.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEighteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                            Can add a covering note to my invoices?
                        </div>
                        <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, we understand that at times you need to enter a covering note while sending an
                                invoice to your customer. You can create covering notes as per your needs and send the
                                email to your customer with a covering note.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Accordion wrapper -->
        </div>
    </div>
    </div>
</section>
@endsection
