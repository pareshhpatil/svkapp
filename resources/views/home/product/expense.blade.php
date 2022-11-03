@extends('home.master')
@section('title', 'Track expenses, create purchase orders and make payouts. Simplify purchases for your business,
improve expense reporting and save costs.')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Expense management software</h1>
                <p class="lead mb-2">What if you can digitize and track your expenses, reduce paperwork, and improve
                    your company’s cashflow? Yes, it is possible with Swipez Expense management software. Report,
                    approve and manage business expenses to eliminate cash shortfalls. Simplify managing expenses,
                    automating purchase orders, tracking spends and much more.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Payouts made simple for businesses" class="img-fluid" src="{!! asset('images/product/expense-management-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Payouts made simple for businesses" class="img-fluid" src="{!! asset('images/product/expense-management-software.svg') !!}" />
                <h1>Expense management software</h1>
                <p class="lead mb-2">What if you can digitize and track your expenses, reduce paperwork, and improve
                    your company’s cashflow? Yes, it is possible with Swipez Expense management software. Report,
                    approve and manage business expenses to eliminate cash shortfalls. Simplify managing expenses,
                    automating purchase orders, tracking spends and much more.</p>
                @include('home.product.web_register',['d_type' => "mob"])

            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center"><span class="highlighter">Free</span> Expense management software</h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Simplify managing expenses,
            automating purchase orders, making payments, tracking spends and much more.</p>
    </center>
    @include('home.data_flow');
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Manage expenses to understand your business spends in seconds</h3>
            </div>
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
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Making payouts to any beneficiary" class="img-fluid" src="{!! asset('images/product/payouts/features/payouts-to-bank-or-cards.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Making payouts anywhere" class="img-fluid" src="{!! asset('images/product/payouts/features/payouts-to-bank-or-cards.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Create purchase orders</strong></h2>
                <p class="lead">Swipez’s expense management software lets you create detailed purchase orders in seconds. Capture information like expected delivery date, product number, maximum retail price (MRP), applicable GST rates & more. Share them electronically with your vendors for quicker approvals, automating your purchasing workflow.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Create purchase orders</strong></h2>
                <p class="lead">Swipez’s expense management software lets you create detailed purchase orders in seconds. Capture information like expected delivery date, product number, maximum retail price (MRP), applicable GST rates & more. Share them electronically with your vendors for quicker approvals, automating your purchasing workflow.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Managing vendors and payouts centrally" class="img-fluid" src="{!! asset('images/product/payouts/features/managing-vendor-payouts.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Managing vendors and payouts centrally" class="img-fluid" src="{!! asset('images/product/payouts/features/managing-vendor-payouts.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Automatically create expense records</strong></h2>
                <p class="lead">Swipez’s expense management system allows you to convert purchase orders to expenses,
                    helping you track all orders and business expenses in a single dashboard!</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Automatically create expense records</strong></h2>
                <p class="lead">Swipez’s expense management system allows you to convert purchase orders to expenses,
                    helping you track all orders and business expenses in a single dashboard!</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Split payments between parties" class="img-fluid" src="{!! asset('images/product/payouts/features/split-payments.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Split payments between parties" class="img-fluid" src="{!! asset('images/product/payouts/features/split-payments.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Create expenses in bulk </strong></h2>
                <p class="lead">Update your expenses in bulk using our convenient upload formats. Swipez’s business
                    expense software helps improve the expenses workflow using batch uploads of all your business spends.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Create expenses in bulk </strong></h2>
                <p class="lead">Update your expenses in bulk using our convenient upload formats. Swipez’s business
                    expense software helps improve the expenses workflow using batch uploads of all your business spends.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Manage franchise payments" class="img-fluid" src="{!! asset('images/product/payouts/features/franchise-payments.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Manage franchise payments" class="img-fluid" src="{!! asset('images/product/payouts/features/franchise-payments.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Upload expense invoices</strong></h2>
                <p class="lead">Swipez expense management software tracks your expenses electronically and lets you
                    upload original invoices for all purchases to improve spend tracking and reducing paperwork.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Upload expense invoices</strong></h2>
                <p class="lead">Swipez expense management software tracks your expenses electronically and lets you
                    upload original invoices for all purchases to improve spend tracking and reducing paperwork.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Make payouts in bulk" class="img-fluid" src="{!! asset('images/product/payouts/features/bulk-payouts.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Make payouts in bulk" class="img-fluid" src="{!! asset('images/product/payouts/features/bulk-payouts.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Make payouts for your expenses</strong></h2>
                <p class="lead">Use one centralized dashboard to disburse payments for orders. Using Swipez’s payout
                    feature you can transfer money directly to your vendor’s bank account for all business purchases.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Make payouts for your expenses</strong></h2>
                <p class="lead">Use one centralized dashboard to disburse payments for orders. Using Swipez’s payout
                    feature you can transfer money directly to your vendor’s bank account for all business purchases.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Reduce follow-ups for payments" class="img-fluid" src="{!! asset('images/product/payouts/features/reduce-manual-follow-ups-for-payments.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Reduce follow-ups for payments" class="img-fluid" src="{!! asset('images/product/payouts/features/reduce-manual-follow-ups-for-payments.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Expenses reporting</strong></h2>
                <p class="lead">Gain complete control and visibility of your business spends using Swipez’s company
                    expenses software’s in-depth real time reporting. Get detailed reports for all your expenses. Cost price, sale price, maximum retail price (MRP), applicable GST rates, payment status, and more, across vendors and multiple departments.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Expenses reporting</strong></h2>
                <p class="lead">Gain complete control and visibility of your business spends using Swipez’s company
                    expenses software’s in-depth real time reporting. Get detailed reports for all your expenses. Cost price, sale price, maximum retail price (MRP), applicable GST rates, payment status, and more, across vendors and multiple departments.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Provide vendors logins for invoicing" class="img-fluid" src="{!! asset('images/product/gst-filing/features/verify-gst-compliance.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Provide vendors logins for invoicing" class="img-fluid" src="{!! asset('images/product/gst-filing/features/verify-gst-compliance.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>A single software to manage all your business spends</strong></h2>
                <p class="lead">Swipez’s expense management software provides complete control over all of your
                    companies spends. Tracking purchase order backed spends, approvals and expenses under a single
                    software helps reduce spend leakage and generate significant savings for your business.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>A single software to manage all your business spends</strong></h2>
                <p class="lead">Swipez’s expense management software provides complete control over all of your
                    companies spends. Tracking purchase order backed spends, approvals and expenses under a single
                    software helps reduce spend leakage and generate significant savings for your business.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Reconcile expenses against GST" class="img-fluid" src="{!! asset('images/product/gst-reconciliation/features/gst-reconciliation.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Reconcile expenses against GST" class="img-fluid" src="{!! asset('images/product/gst-reconciliation/features/gst-reconciliation.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Hassle-free <a href="{{ route('home.gstrecon') }}">GST reconciliation</a> of
                        expenses</strong></h2>
                <p class="lead">Identify & reconcile differences between your expense data and your vendor’s GST filings
                    with just a few clicks. With auto-updated, real-time reports on all your expense data, ensure
                    accurate & faster GST 2a reconciliations.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Hassle-free <a href="{{ route('home.gstrecon') }}">GST
                            reconciliation</a> of expenses</strong></h2>
                <p class="lead">Identify & reconcile differences between your expense data and your vendor’s GST filings
                    with just a few clicks. With auto-updated, real-time reports on all your expense data, ensure
                    accurate & faster GST 2a reconciliations.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Provide vendors logins for invoicing" class="img-fluid" src="{!! asset('images/product/expense-management-software/features/vendor-invoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Provide vendors logins for invoicing" class="img-fluid" src="{!! asset('images/product/expense-management-software/features/vendor-invoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Simplify vendor invoicing</strong></h2>
                <p class="lead">Provide your vendors access to a vendor portal with vendor wise login credentials to
                    create and send invoices to you. Receive and view all your vendor invoices in a single dashboard
                    with their details and schedule payouts.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Simplify vendor invoicing</strong></h2>
                <p class="lead">Provide your vendors access to a vendor portal with vendor wise login credentials to
                    create and send invoices to you. Receive and view all your vendor invoices in a single dashboard
                    with their details and schedule payouts.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-outline-primary btn-lg text-white bg-primary" href="{{ route('home.pricing.billing') }}">See pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-outline-primary btn-lg text-white bg-primary" href="{{ route('home.pricing.billing') }}">Pricing plans</a>
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
                <h2 class="display-4 text-white">Manage all your company expenses</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Accurate expense management</h3>
                    <p>Spend confidently and save money by using Swipez’s company expense management software. Swipez
                        gives your business complete control over their financial operations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cogs" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M512.1 191l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0L552 6.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zm-10.5-58.8c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.7-82.4 14.3-52.8 52.8zM386.3 286.1l33.7 16.8c10.1 5.8 14.5 18.1 10.5 29.1-8.9 24.2-26.4 46.4-42.6 65.8-7.4 8.9-20.2 11.1-30.3 5.3l-29.1-16.8c-16 13.7-34.6 24.6-54.9 31.7v33.6c0 11.6-8.3 21.6-19.7 23.6-24.6 4.2-50.4 4.4-75.9 0-11.5-2-20-11.9-20-23.6V418c-20.3-7.2-38.9-18-54.9-31.7L74 403c-10 5.8-22.9 3.6-30.3-5.3-16.2-19.4-33.3-41.6-42.2-65.7-4-10.9.4-23.2 10.5-29.1l33.3-16.8c-3.9-20.9-3.9-42.4 0-63.4L12 205.8c-10.1-5.8-14.6-18.1-10.5-29 8.9-24.2 26-46.4 42.2-65.8 7.4-8.9 20.2-11.1 30.3-5.3l29.1 16.8c16-13.7 34.6-24.6 54.9-31.7V57.1c0-11.5 8.2-21.5 19.6-23.5 24.6-4.2 50.5-4.4 76-.1 11.5 2 20 11.9 20 23.6v33.6c20.3 7.2 38.9 18 54.9 31.7l29.1-16.8c10-5.8 22.9-3.6 30.3 5.3 16.2 19.4 33.2 41.6 42.1 65.8 4 10.9.1 23.2-10 29.1l-33.7 16.8c3.9 21 3.9 42.5 0 63.5zm-117.6 21.1c59.2-77-28.7-164.9-105.7-105.7-59.2 77 28.7 164.9 105.7 105.7zm243.4 182.7l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0l8.2-14.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zM501.6 431c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.6-82.4 14.3-52.8 52.8z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Improve your expense workflow</h3>
                    <p>Improve speed and efficiency, remove opportunities for error or fraud, and reduce time wasting
                        manual tasks. Simple and easy to use across your entire organization</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Manage all your expenses</h3>
                    <p>Full control of all your business spend available anywhere, anytime, from any device. See where
                        every Rupee goes and monitor the bottom line impact with real-time budgeting.</p>
                </div>
            </div>

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
                    collections software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/payouts/litcabs.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Payouts has saved over 100 man hours for our team every month. A simple excel upload
                                takes care of payments to our vendor partners and suppliers. Reconciliation of payments
                                is present
                                in the same dashboard."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Siddhartha S</strong>
                            </p>
                            <p>
                                <em>Co-founder, Litcabs</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/payouts/shah-infinite.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Making timely salary payments was a time consuming activity for us. Now using payouts we
                                make our salary payments within minutes. The extensive reporting provide us a clear view
                                of all payouts."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Infinite Solutions</em>
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
                <h3>Power your business with a comprehensive GST filing solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.billing') }}">See pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a>
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
                            What is the Swipez expense management system?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez expense management system is a complete solution for businesses to create
                                purchase orders, record payment details and track business expenditure.
                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How can I use the Swipez expense management software?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez expense management software is a completely cloud based solution. It can be
                                accessed through a browser on any device with an active internet connection.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How can I use Swipez to keep track of original purchase invoices?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez expense management system allows for of upload soft copies of all original
                                invoices and stores them for easy accessibility.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Does Swipez have expense reports that can be used for taxation?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez expense management system has detailed reports that can be exported via Excel
                                sheets for all taxation purposes.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Can I create purchase orders using Swipez ?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes. You can create purchase orders using Swipez’s business expense software and share
                                them with your vendors and suppliers electronically.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            How can I make payments to my vendors for my business purchases?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Using Swipez’s payout feature you can transfer payments directly to your vendors bank
                                accounts.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            How can I record an expense for purchase orders created?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Once you create a purchase order and make a payment for it, you can easily convert it to
                                an expense and record your payment details.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Can I bulk upload all my expenses?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes. The Swipez expense management software has an easy to use bulk upload option via an
                                Excel input.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            How can I view a report of vendor wise expenses?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Using Swipez expense reporting you filter expenses reports by vendor, departments and
                                categories.
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
<script>
    var intcounter = 0;
    var istimer = false;
    var titles1 = ["Expenses management"];
</script>

@endsection