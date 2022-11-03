@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pb-5 pb-sm-0">
                <img alt="Online collections made simple with online billing software" class="img-fluid" src="{!! asset('images/product/billing-software.svg') !!}" width="640" height="360" />
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <!--<h1>Billing Software</h1>-->
                <h1>Billing software to manage your day to day business operations</h1>
                <p class="lead mb-2">Free billing software​​ made for your business. Track your customer data,
                    billing, payment collections and more. Send automated invoice due reminders, offline tracking of
                    payments and a simple interface to manage your business operations. An affordable billing and
                    invoicing software that helps you collect payments faster via online payment modes like UPI,
                    Wallets, Credit or Debit Card, or Net Banking.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
        </div>
    </div>
</section>

<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center"><span class="highlighter">Free</span> Billing software</h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Free billing software that helps manage your customer data, billing, vendors and their expenses & more from a single dashboard. Collect payments online instantly using UPI, Wallets, Credit Card, Debit Card & Net Banking with free billing software</p>
    </center>
    @include('home.data_flow');
</section>
<section class="jumbotron py-3" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-md-9 mx-auto my-3">
                <h2 class="pb-2 display-4">Free billing software</h2>
                <p class="mb-0">Watch our video overview to learn more</p>
                <div style="cursor:pointer;">
                    <div id="video-promo-container" style="max-width:100%;position:relative;padding-top:62.25%">
                        <div id="video-play-button" style="position:absolute; top:0px; left:0px; right:0px; bottom:0px; z-index:400">
                            <div style="position:absolute; top:0%; left:0%; width:100%">
                                <picture>
                                    <source title="Click to watch overview video" srcset="{!! asset('images/product/billing-software/dashboard.webp') !!}" type="image/webp" alt="Billing software explained" class="img-fluid" width="825" height="464" />
                                    <source title="Click to watch overview video" srcset="{!! asset('images/product/billing-software/dashboard.png') !!}" type="image/jpeg" alt="Billing software explained" class="img-fluid" width="825" height="464" />
                                    <img title="Click to watch overview video" src="{!! asset('images/product/billing-software/dashboard.png') !!}" alt="Billing software explained" class="img-fluid" width="825" height="464" loading="lazy" class="lazyload" />
                                </picture>
                            </div>
                            <span style="position:absolute; margin-top:-40px; margin-left:-30px; top:50%; left:48%">
                                <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube" class="h-16 pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="color: #ff0000;t">
                                    <path fill="currentColor" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z">
                                    </path>
                                </svg>
                            </span>
                        </div>
                        <div id="video-text" class="w-100" style="position:absolute; bottom:0px; left:0px; right:0px; z-index:400">
                            <p class="lead text-center font-weight-bold font-italic text-primary d-none">See Swipez
                                Billing in
                                action</p>
                        </div>
                        <div id="youtube-video" class="pb-3"></div>
                    </div>
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body p-5">
                                <div class="row">
                                    <div class="col-xl-7 mb-4 mb-sm-0 text-center">
                                        <h5>{{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez Billing Free trial
                                            available!</h5>
                                    </div>
                                    <div class="col-xl-5 text-center">
                                        <a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3" href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    using Swipez billing software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}" width="166" height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now we send the monthly internet bills to our customers at the click of a
                                button. Customers receive bills on e-mail and SMS with multiple online payment options.
                                Payments collected online and offline are all reconciled with Swipez billing."</p>
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
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}" width="166" height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We are now managing payments across our complete customer base along with
                                timely pay outs for all franchisee's across the country from one dashboard. My team has
                                saved over hundred hours after adopting Swipez Billing."</p>
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
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Ideal billing software for small businesses, B2B or B2C companies, freelancers and
                    finance / accounts
                    teams.</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
                <p class="lead">Features built from feedback from over {{env('SWIPEZ_BIZ_NUM')}} happy businesses who
                    are already
                    using Free Online Billing Software</p>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="featureTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Invoicing &
                            Estimates</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-onlinepayment-tab" data-toggle="pill" href="#pills-onlinepayment" role="tab" aria-controls="pills-onlinepayment" aria-selected="false">Online payments</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-reminder-tab" data-toggle="pill" href="#pills-reminder" role="tab" aria-controls="pills-reminder" aria-selected="false">Payment reminders</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-bulk-tab" data-toggle="pill" href="#pills-bulk" role="tab" aria-controls="pills-bulk" aria-selected="false">Bulk
                            invoicing</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-inventory-tab" data-toggle="pill" href="#pills-inventory" role="tab" aria-controls="pills-inventory" aria-selected="false">Inventory
                            management</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-recurring-tab" data-toggle="pill" href="#pills-recurring" role="tab" aria-controls="pills-recurring" aria-selected="false">Recurring billing</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-international-tab" data-toggle="pill" href="#pills-international" role="tab" aria-controls="pills-international" aria-selected="false">International payment collections</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-expense-tab" data-toggle="pill" href="#pills-expense" role="tab" aria-controls="pills-payout" aria-selected="false">Expense
                            management</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-payout-tab" data-toggle="pill" href="#pills-payout" role="tab" aria-controls="pills-payout" aria-selected="false">Split
                            payments</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-multibilling-tab" data-toggle="pill" href="#pills-multibilling" role="tab" aria-controls="pills-multibilling" aria-selected="false">Multiple GST profiles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-gst-tab" data-toggle="pill" href="#pills-gst" role="tab" aria-controls="pills-gst" aria-selected="false">GST
                            submission</a>
                    </li>

                    <li class="nav-item" role="presentation">

                        <a class="nav-link text-uppercase gray-400" id="pills-einvoice-tab" data-toggle="pill" href="#pills-einvoice" role="tab" aria-controls="pills-einvoice" aria-selected="false">e-Invoicing</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-gstrecon-tab" data-toggle="pill" href="#pills-gstrecon" role="tab" aria-controls="pills-gstrecon" aria-selected="false">GST
                            reconciliation</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-tally-tab" data-toggle="pill" href="#pills-tally" role="tab" aria-controls="pills-tally" aria-selected="false">Tally
                            integration</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.billing.feature.onlineinvoicing') }}">Invoicing & Estimates</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/invoices.svg') !!}" alt="Create invoices or estimates" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Send <a href="{{ route('home.billing.feature.onlineinvoicing') }}">GST tax invoices</a> to your customers with our ready made industry friendly
                                    templates. Our wide range of GST-compliant invoice templates makes it easier for you to start creating industry-approved invoices with your unique brand in just a few clicks.  Add personalized covering notes, attached files,
                                    and more to your invoices. Offer discount coupons, multiple payment options,
                                    collect international payments and more. Notify customers via email and SMS.
                                    Save time creating & sending estimates and automatically converting them to invoices
                                    with a single click!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-onlinepayment" role="tabpanel" aria-labelledby="pills-onlinepayment-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.paymentcollections') }}">Online payments</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/online-payments.svg') !!}" alt="Invoicing with online payments" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    <a href="{{ route('home.paymentcollections') }}">Collect payments online</a>
                                    directly from your invoices. Customers can view your
                                    invoices and make payments using UPI, Debit card, Credit card payments, Net banking
                                    or Wallets. Add EMI payment options to your invoices. Integrate into your website or
                                    simply <a href="{{ route('home.paymentlink') }}">send payment links</a>.
                                    Start collecting prompt payments in a currency of your choice!

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-reminder" role="tabpanel" aria-labelledby="pills-reminder-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.billing.feature.paymentreminder') }}">Payment
                                            reminders</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/reminders.svg') !!}" alt="Automated payment reminders" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    <a href="{{ route('home.billing.feature.paymentreminder') }}">Automated payment
                                        reminders</a> against unpaid invoices at configurable frequencies.
                                    Customers who haven’t paid the invoice will receive a reminder via E-mail and SMS
                                    with online payment links. Reduce your outstanding payments. Get paid on time every
                                    time!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-bulk" role="tabpanel" aria-labelledby="pills-bulk-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.billing.feature.bulkinvoicing') }}">Bulk
                                            invoicing</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/bulk-invoicing.svg') !!}" alt="Create invoices in bulk" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Create invoices and bill using <a href="{{ route('home.billing.feature.bulkinvoicing') }}">bulk invoicing</a> via
                                    excel sheets or APIs. Personalized invoices will be
                                    auto-generated and sent to your customer base in bulk via email and SMS. Save
                                    valuable time for your team by using bulk invoicing!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-inventory" role="tabpanel" aria-labelledby="pills-inventory-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.inventorymanagement') }}">Inventory management</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/inventory-management-software.svg') !!}" alt="Billing software with inventory management" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Easy to use <a href="{{ route('home.inventorymanagement') }}">inventory management
                                        software</a> perfect for small and medium sized businesses. Integrated to reduce
                                    stock as you create or update bills and linked with expenses to add new stock to
                                    your inventory. Create & manage all your services/items of sale with multiple variables like cost price, sale price, maximum retail price (MRP), expiry date, specifications & more.
                                    Add different variations like size, color & more for different products. Track stock inventory of all your products with ease.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-recurring" role="tabpanel" aria-labelledby="pills-recurring-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Recurring billing</center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/recurring-billing.svg') !!}" alt="Create recurring invoices or subscriptions" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Automate bill presentment to customers who have subscribed to a package or a
                                    service. Send out invoices automatically on the assigned date without any manual
                                    intervention. Bulk upload subscriptions with details for the different recurring invoices like frequency, due date, end date, and more via a simple excel import. View the different invoices/estimates created and sent from a
                                    subscription with real-time status updates. Settle invoices paid offline and get a
                                    comprehensive overview of all your payments on a single dashboard. Set up your
                                    subscription billing once and your customer receives bills
                                    automatically!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-international" role="tabpanel" aria-labelledby="pills-international-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>International payment collections</center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cross-border-payment-collections.svg') !!}" alt="International payment collections" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Enable payment collections in 50+ currencies from across the globe. Receive
                                    international payments directly into your bank account. Create & send invoices/estimates with payment options in multiple currencies to ease
                                    your billing.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-expense" role="tabpanel" aria-labelledby="pills-expense-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.expenses') }}">Expense management</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/expense-management.svg') !!}" alt="Expense management software" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Manage all your company expenses in a central place. You can create purchase orders,
                                    convert them to expenses and <a href="{{ route('home.payouts') }}">make payouts</a>
                                    for your expenses. Comprehensive expenses with detailed information that includes MRP, expiry date, applicable GST rates & more for faster approvals and seamless workflow.
                                    Simple and easy-to-use <a href="{{ route('home.expenses') }}">expense management
                                        software</a> tailor made for your business,
                                    available in multiple currencies. Manage & monitor all your expenses from a single
                                    dashboard!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-payout" role="tabpanel" aria-labelledby="pills-payout-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Split payments</center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/split-payments.svg') !!}" alt="Split payments and automate payouts" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Collect payments online and split payments between multiple parties automatically.
                                    Settle funds to vendors or <a href="{{ route('home.industry.franchise') }}">franchises</a> as per a
                                    pre-defined split. Ensure prompt payment for your vendors, franchises, and partners.
                                    Save time and
                                    effort and <a href="{{ route('home.payouts') }}">automate your payouts</a> with
                                    ease!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-multibilling" role="tabpanel" aria-labelledby="pills-multibilling-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Multiple GST profiles</center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/multiple-billing-profiles.svg') !!}" alt="Manage multiple GST profiles" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Setup multiple billing profiles for your company. Configure multiple addresses,
                                    separate contact information and state wise GST numbers. Choose the required
                                    billing profile while creating invoices for your company. The GST values will be
                                    auto-calculated and incorporated into the invoices.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-gst" role="tabpanel" aria-labelledby="pills-gst-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.gstfiling') }}">GST filing</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/gst-filing-software.svg') !!}" alt="GST filing" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    File accurate GSTR1 and 3B records directly to the GST portal. Monthly and quarterly
                                    results prepared automatically and presented for filing from your GST invoicing. Cut
                                    down errors and time taken for your <a href="{{ route('home.gstfiling') }}">GST
                                        filing</a> by using Swipez for GST invoicing.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-einvoice" role="tabpanel" aria-labelledby="pills-einvoice-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.einvoicing') }}">e-Invoicing</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/einvoicing.svg') !!}" alt="Billing software with e-Invoicing" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Create GST-compliant e-invoices and submit them directly to the Invoice Registration
                                    Portal (IRP). Generate e-invoices with a unique Invoice Reference Number (IRN),
                                    digital signature, and QR code in just a few clicks. Automatically send PDF invoices with IRN number and QR code to your customers via email.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-gstrecon" role="tabpanel" aria-labelledby="pills-gstrecon-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ route('home.gstrecon') }}">GST reconciliation</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('images/product/gst-reconciliation/features/gst-reconciliation.svg') !!}" alt="GST reconciliation software" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Fast & simple GST 2A and 3B reconciliation to ensure error-free Input Tax Credit for
                                    your business. Identify and reconcile differences between your expense data and the
                                    data from your vendors' GST filings. Slice-n-dice GST reconciliation reports, notify
                                    vendors, <a href="{{ route('home.gstrecon') }}">faster GST reconciliation</a> & much
                                    more.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-tally" role="tabpanel" aria-labelledby="pills-tally-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center>Tally integration</center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/tally-integration.svg') !!}" alt="Integrated with tally and other accounting softwares" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Continue bookkeeping in your existing accounting software like Tally and import
                                    / export business data. Automated conversion of Tally accounting software data into
                                    online bills and send it out to customers with a payment request link and reminders
                                    on both email and SMS.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center d-sm-none">
                    <button id="prevtab" class="btn btn-primary" onclick="prevFeatureClick();">
                        < </button> <button id="nexttab" class="ml-5 btn btn-primary" onclick="nextFeatureClick();"> >
                            </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez<br /><br />Free
                    trial available. No payment required!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary mr-4" href="{{ config('app.APP_URL') }}merchant/register">Get
                    a
                    free account</a>
                <a class="btn btn-outline-primary btn-lg text-white" href="{{ route('home.pricing.billing') }}">See
                    pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Get free billing software now!</a>
                    <a class="btn btn-outline-primary btn-lg text-white mt-2" href="{{ route('home.pricing.billing') }}">See pricing plans</a>
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
                <h2 class="display-4 text-white">Simplify your business & get paid faster!</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Error-free invoices</h3>
                    <p>Accurate GST calculations on every invoice. No manual intervention or errors.  GST compliant and
                        easy to
                        reconcile your monthly taxation</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Access anywhere</h3>
                    <p>Cloud-based simple billing software. Access and manage invoices on-the-go. Works securely across
                        operating systems or any device</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Grow your business</h3>
                    <p>Manage cash flow with our hassle-free invoicing and sales reports. Streamline your collections
                        and accounting
                        processes. Make way for growth!</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path fill="currentColor" d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Automated billing</h3>
                    <p>Your monthly billing is automated at the push of the button. Everything you need to get paid
                        faster in one
                        user friendly invoicing tool</p>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Billing software for your industry</h2>
                <h4 class="lead">Over {{env('SWIPEZ_BIZ_NUM')}} small businesses use Swipez online GST billing software
                    to manage
                    their business on a day to day basis</h4>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="industryTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="industry-cable-tab" data-toggle="pill" href="#industry-cable" role="tab" aria-controls="industry-cable" aria-selected="true">Cable
                            operator</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-billing-tab" data-toggle="pill" href="#industry-billing" role="tab" aria-controls="industry-billing" aria-selected="false">Education industry</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-isp-tab" data-toggle="pill" href="#industry-isp" role="tab" aria-controls="industry-isp" aria-selected="false">ISP</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-travel-tab" data-toggle="pill" href="#industry-travel" role="tab" aria-controls="industry-travel" aria-selected="false">Travel and tour operators</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-franchise-tab" data-toggle="pill" href="#industry-franchise" role="tab" aria-controls="industry-franchise" aria-selected="false">Franchise business</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-housing-tab" data-toggle="pill" href="#industry-housing" role="tab" aria-controls="industry-housing" aria-selected="false">Housing societies</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-consultancy-tab" data-toggle="pill" href="#industry-consultancy" role="tab" aria-controls="industry-consultancy" aria-selected="false">Consultancy firms and freelancers</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="industry-cable" role="tabpanel" aria-labelledby="industry-cable-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-cable-operator">Cable
                                            operator billing software</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Automate billing & invoicing, payment reminders, payment collections and more.
                                    Manage customer data, online payment collections through different payment modes
                                    with comprehensive real-time reports. Swipez software for billing helps businesses
                                    like cable operators in subscriber management, channel package selection, faster
                                    payment collections making your team more productive and customers satisfied. An
                                    easy to use and efficient system to manage set top box <a href="{{ config('app.APP_URL') }}billing-software-for-cable-operator">billing
                                        software for cable
                                        operators</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-billing" role="tabpanel" aria-labelledby="industry-billing-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-school">Billing
                                            software for education industry</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/billing-software-for-education-institute.svg') !!}" alt="Billing software for education industry" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Swipez’s billing and invoicing software for educational institutes is an
                                    advanced cloud-based fee management, online invoicing and venue management platform.
                                    It transforms the most resource heavy aspects of managing an educational institute's
                                    finances into an effortless operation. Our comprehensive <a href="{{ config('app.APP_URL') }}billing-software-for-school">school fees
                                        collection and billing software</a> not only fast tracks student fee collection
                                    but also provides an enhanced experience to parents and students.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-isp" role="tabpanel" aria-labelledby="industry-isp-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-isp-telcos">ISP
                                            billing software</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/billing-software-for-isp.svg') !!}" alt="ISP billing software" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Be it post-paid or prepaid billing or integration with your existing
                                    internet CRM
                                    systems through API, the Swipez <a href="{{ config('app.APP_URL') }}billing-software-for-isp-telcos">billing
                                        software for
                                        ISP’s</a> is a complete billing management system. Our GST ready billing
                                    software is a 100%
                                    cloud based solution which requires low investment but has rich functionality and is
                                    scalable no
                                    matter the size of your customer base. Internet Service Providers using Swipez have
                                    reduced feet on
                                    street operations by invoicing online resulting in faster payment collections from
                                    customers. While increasing the number of internet connection they manage.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-travel" role="tabpanel" aria-labelledby="industry-travel-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-travel-and-tour-operator">Billing
                                            software for travel and tour operators</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/travel-and-tour-billing-software.svg') !!}" alt="Billing software for travel and tour operators" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Get access to complete finances of your business anywhere and anytime
                                    using Swipez’s <a href="{{ config('app.APP_URL') }}billing-software-for-travel-and-tour-operator">billing
                                        software for travel and tour
                                        companies</a>. Our feature rich invoicing and billing software for travel and
                                    tour operators
                                    provides all the
                                    functionalities that you need to manage your retail business billing, invoicing and
                                    disbursements. Be it
                                    receiving payments from your customers using our travel friendly invoice template,
                                    franchisees and
                                    agents. Even paying your vendors, staff and other day-to-day payments all from one
                                    centralised
                                    dashboard.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-franchise" role="tabpanel" aria-labelledby="industry-franchise-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-franchise-business">Billing
                                            software for franchise business</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/billing-software-for-franchise-business.svg') !!}" alt="Billing software for franchise business" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Swipez’s billing software for franchise business is a modern and easy to
                                    use system that
                                    completely manages invoicing, collections and disbursements for all your
                                    franchisee’s. Swipez <a href="{{ config('app.APP_URL') }}billing-software-for-franchise-business">billing
                                        software for franchise businesses</a>
                                    allows franchisors to control all aspects of billing &amp; invoicing. From the look
                                    and feel of the invoice to the automated split of received payments between the
                                    franchisor and franchisee. Our software’s accurate reporting on a franchise level
                                    allows franchisors to monitor the health of all their franchises. Standardised
                                    billing and live real time reporting are key factors in running a successful
                                    franchise business and expanding the network.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-housing" role="tabpanel" aria-labelledby="industry-housing-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-and-venue-booking-software-for-housing-societies">Billing
                                            software for housing societies</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/billing-software-for-housing-society.svg') !!}" alt="Billing software for housing societies" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Timely collection of maintenance dues and identifying outstanding dues
                                    are an important
                                    function to keep a housing society functioning smoothly. Keeping housing society
                                    finances, cash
                                    positive is a time consuming and resource heavy task for housing society
                                    administration. Swipez’s <a href="{{ config('app.APP_URL') }}billing-and-venue-booking-software-for-housing-societies">free
                                        billing software for housing
                                        societies</a> automates multiple operations for administrators. From invoicing,
                                    automatic
                                    <a href="{{ route('home.billing.feature.paymentreminder') }}">payment reminders</a>,
                                    online collections and accurate tracking of all payments received
                                    from its
                                    members be it for maintenance, society events or amenity management.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-consultancy" role="tabpanel" aria-labelledby="industry-consultancy-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}invoicing-software-for-freelancers-and-consultants">Billing
                                            software for consultancy firms and freelancers</a></center>
                                </h2>
                            </div>
                            <img class="plus-background-without-star card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/billing-software-for-freelancer.svg') !!}" alt="Billing software for consultancy firms and freelancers" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">As a freelancer or a consultancy firm a key factor to running a smooth
                                    business is
                                    collection of payments. Collecting payments is important but time consuming, from
                                    creating
                                    professional invoices, to follow ups and providing multiple payment modes all of
                                    which make sure you
                                    get paid on time. Swipez’s <a href="{{ config('app.APP_URL') }}invoicing-software-for-freelancers-and-consultants">billing
                                        software for
                                        freelancers and consulting firms</a> allows you to create and send invoices in
                                    the look
                                    and feel of your
                                    company. Setup automated reminders to make sure you get paid on time and reduce
                                    those time intensive
                                    follow up calls for overdue payments. Multiple payment options offered to your
                                    customers reduces
                                    payment barriers and delays. Swipez’s professional invoice and billing software for
                                    small
                                    businesses
                                    saves you the most precious resource ‘time’.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center d-sm-none">
                    <button id="prevtab" class="btn btn-primary" onclick="prevIndustryClick();">
                        < </button> <button id="nexttab" class="ml-5 btn btn-primary" onclick="nextIndustryClick();"> >
                                </i>
                            </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">The perfect billing software for small businesses<br /><br />Free trial
                    available. No payment required!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary mr-4" href="{{ config('app.APP_URL') }}merchant/register">Get
                    free billing software</a>
                <a class="btn btn-outline-primary btn-lg text-white" href="{{ route('home.pricing.billing') }}">See
                    pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get
                        free billing software now!</a>
                    <a class="btn btn-outline-primary btn-lg text-white mt-3" href="{{ route('home.pricing.billing') }}">See pricing plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-8" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-primary rounded-1 text-white">
                    <div class="row">
                        <div class="col-md-6 p-5">
                            <h2 class="mb-4">
                                Billing and invoicing software with payments collections!
                            </h2>
                            <h5 class="mb-5">Simple to use billing and invoicing software for faster payment
                                collections. Collect
                                payments from your customers using payment links, online invoices with automated
                                payment reminders
                            </h5>
                            <a class="btn btn-outline-secondary btn-lg text-white bg-secondary" href="{{ route('home.paymentcollections') }}">Know more</a>
                        </div>
                        <div class="col-md-6">
                            <img src="{!! asset('images/product/billing-software/features/payment-collections-dashboard.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
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
                <h3>⚡ Super charge your business with a complete billing solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg bg-secondary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get
                    free billing software</a>
                <a class="btn btn-outline-secondary btn-lg text-white" href="{{ route('home.pricing.billing') }}">See
                    pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-secondary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get free billing software now!</a>
                    <a class="btn btn-outline-secondary btn-lg text-white mt-3" href="{{ route('home.pricing.billing') }}">See pricing plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>


<section class="py-7 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>Invoice format free download</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create and send
                                    invoice from your browser</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Download free
                                    invoice formats</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Invoice formats
                                    as per your business</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Great design to
                                    impress your clients!</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Send PDF invoices to your clients</li>
                            </ul>
                            <a class="btn btn-primary big-text mt-3" href="{{ route('home.invoiceformats') }}">Invoice
                                format
                                free download</a>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Download an invoice as per your business requirement" src="{!! asset('images/home/download-invoice-format.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Download an invoice as per your business requirement" src="{!! asset('images/home/download-invoice-format.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Invoice format free download</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create and send
                                    invoice from your browser</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Download free
                                    invoice formats</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Invoice formats
                                    as per your business</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Clean design to
                                    impress your clients!</li>
                            </ul>
                            <a class="btn btn-primary big-text" href="{{ route('home.invoiceformats') }}">Invoice format
                                free download</a>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
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
                        <h2 class="display-4">Frequently asked questions</h2>
                        <h3 class="lead">Looking for more info? Here are some questions small and medium businesses
                            owners ask</h3>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h4>Is the Swipez GST billing software free plan really free?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, You can use all our features for free. We only charge the lowest online payment <a href="/payment-gateway-charges" target="_blank">transaction fee</a> for payments that you collect.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>What is the difference between the GST billing software free and paid plans?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                With our Free plan you can create unlimited GST invoices and estimates similar to paid
                                plans
                                but there are a few key differentiator such as, you get reduced transaction charges in
                                the paid plans, SMS Notifications, GST filing, API access and the ability to manage
                                franchises.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Is there any limit on the amount of customers I can add for billing in all three plans?
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there is no limit on the amount of customers you can add in any of our plans.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Are my invoices and data safe on Swipez billing software system?</h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                At Swipez we really value our customer's privacy. You rely on us for a big part of
                                your business and GST billing, so we really take your needs seriously. That is why the
                                security of our software, systems and business data are our number one priority. All
                                information that is transmitted between your browser and Swipez is protected with
                                256-bit SSl encryption. This ensures to keep data secure and unreadable during transit.
                                All the
                                business data you have entered into Swipez sits securely behind web firewalls. This
                                system is used by some of the biggest companies in the world and is widely acknowledged
                                as safe and secure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>Who’s there if I need any help related to billing system?</h4>
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
                            <h4>Can I use Swipez’s GST billing software on multiple devices and platforms?</h4>
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
                            <h4>Do I need to download anything to start using Swipez billing software?</h4>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download or install anything on your operating system to start
                                using the Swipez GST billing software. All you need is any regular web browser.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h4>Can multiple people use the billing application and platform at one time?</h4>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez billing software has no such restrictions, multiple people can access
                                the platform at one time.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h4>I give discounts on an invoice level, can I do that on Swipez system?</h4>
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can add multiple levels of discounts and offer various coupon codes for your
                                customers to avail the same in the Swipez billing system.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h4>Can I create GST invoices on Swipez software platform?</h4>
                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, with our GST ready bill formats you can create GST compliant invoices in just a few
                                clicks.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEleven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h4>How do I file my GST returns using Swipez billing software?</h4>
                        </div>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Our GST billing software integration is via IRIS an approved GST Suvidha Provider. You
                                can connect to your GST portal using our secured OTP process and upload your GST bills
                                and file GST 3B and R1 directly from the Swipez billing software.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            <h4>Can I restrict employee access on Swipez billing platform?</h4>
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create multiple roles and decide if you would like to give full control or
                                restrict access to certain features for your employees using Swipez free billing
                                software.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThirteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            <h4>Can I track offline invoice payments on Swipez?</h4>
                        </div>
                        <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez allows you to track invoice payments that are made offline via cash or
                                cheque. Including your bank transactions. This way you keep on top of all the invoices
                                you have sent to your customers.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFourteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            <h4>Can I create customized invoice templates for my business?</h4>
                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez offers customizable invoice templates suitable for multiple businesses. You
                                can customize the invoice as per your brands look and feel and add your company logo to
                                your invoice.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFifteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                            <h4>How do my invoices reach my customers?</h4>
                        </div>
                        <div id="collapseFifteen" class="collapse" aria-labelledby="headingFifteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Once you create an invoice your customer is intimated of the invoice via email and SMS.
                                Moreover, your customer also receives reminders to make payments on time. Your invoices
                                can start getting paid online by your customers. This results in higher customer
                                satisfaction for your service as you making their life easier to make these payments.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFifteenone">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFifteenone" aria-expanded="false" aria-controls="collapseFifteenone">
                            <h4>Can I generate e-invoices and upload it to the Invoice Registration Portal from Swipez?
                            </h4>
                        </div>
                        <div id="collapseFifteenone" class="collapse" aria-labelledby="headingFifteenone" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can <a href="{{ route('home.einvoicing') }}">create e-invoices</a> with Swipez. You can upload your e-invoices directly to
                                the Invoice Registration Portal (IRP). The invoices will be validated with a unique
                                Invoice Reference Number (IRN), digital signature, and QR code.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentysix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentysix" aria-expanded="false" aria-controls="collapseTwentysix">
                            <h4>Will the different currencies be converted in real-time?</h4>
                        </div>
                        <div id="collapseTwentysix" class="collapse" aria-labelledby="headingTwentysix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                All foreign currencies will be automatically converted to INR in accordance with latest
                                conversion rates.
                                Payment collections from around the world will be settled in INR directly to your bank
                                account.
                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSixteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                            <h4>Can I add an existing payment gateway of mine to Swipez?</h4>
                        </div>
                        <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, we allow you to add any existing payment gateway that you are already using in our
                                paid plans.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeventeen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                            <h4>Is this billing software customizable as per my company requirement?</h4>
                        </div>
                        <div id="collapseSeventeen" class="collapse" aria-labelledby="headingSeventeen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, for larger businesses or enterprises we do carry out bespoke customisations.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEighteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                            <h4>Is there a Bulk upload option in the Swipez billing software to send payment links,
                                reminders, sms, emails to customers?</h4>
                        </div>
                        <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez online invoice software has an easy to use excel upload which allow you
                                to send
                                out multiple invoices to your customer base.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEighteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                            <h4>Does the billing software support payouts?</h4>
                        </div>
                        <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez billing product comes with a facility to make payouts using NEFT/IMPS
                                and you can also make payouts to UPI ID. This make it easy to not only <a href="{{ route('home.paymentcollections') }}">collect payments online</a> but also
                                make <a href="{{ route('home.payouts') }}">payouts</a> in bulk
                                or using APIs
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyone1">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyone1" aria-expanded="false" aria-controls="collapseTwentyone1">
                            <h4>Can I collect part payments against my invoices?</h4>
                        </div>
                        <div id="collapseTwentyone1" class="collapse" aria-labelledby="headingTwentyone1" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can collect part payments against your invoices via online or offline modes of
                                payments. And let your customer pay your invoice via multiple transactions.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyone">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyone" aria-expanded="false" aria-controls="collapseTwentyone">
                            <h4>Can I setup recurring billing using Swipez online invoice software?</h4>
                        </div>
                        <div id="collapseTwentyone" class="collapse" aria-labelledby="headingTwentyone" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can recurring bills by simply selecting the date and frequency of recurrence.
                                This will send customized invoices to your customers at a frequency defined by you. Use
                                this as a simple subscription management software. You just need to setup it up once and
                                let Swipez simple billing software do the rest. Invoices raised via Swipez help you
                                get paid online faster.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyoneadd">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyoneadd" aria-expanded="false" aria-controls="collapseTwentyoneadd">
                            <h4>Can I create subscriptions in bulk?</h4>
                        </div>
                        <div id="collapseTwentyoneadd" class="collapse" aria-labelledby="headingTwentyoneadd" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly. A simple excel upload is all it takes. You can specify details for each subscription like the mode of the subscription, frequency, due date, end date & more as per your requirements. Upload the filled excel sheet and recurring invoices will be automatically created and sent to the different customers for the subscriptions as per your specifications.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyone2">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyone2" aria-expanded="false" aria-controls="collapseTwentyone2">
                            <h4>What to look for in a good billing software for a small business?</h4>
                        </div>
                        <div id="collapseTwentyone2" class="collapse" aria-labelledby="headingTwentyone2" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                <ul>
                                    <li>First thing check if the free trial allows you to use it for free for an
                                        unlimited amount of time. You do not want to start using a product and then
                                        loose all the data you put in. Swipez GST billing and invoicing software allows
                                        you to keep your data for an unlimited amount of time. For example, Zoho invoice
                                        limits the functionality they provide in their free version.
                                    </li>
                                    <li>Pick a invoice software with ability to create different types of invoices or
                                        bills. A product which is designed for handling your current bill format.
                                        Invoice software you choose needs to create invoices or bills which are easy for
                                        your customer to understand.
                                    </li>
                                    <li>It should be easy to create and send GST tax invoice to your customer using the
                                        billing solution you opt for. Time is of the essence as a small business owner
                                        creating and sending invoices should be as simple as it can get.
                                    </li>
                                    <li>
                                        Best invoicing software typically let you choose a invoice template and provides
                                        multiple choices of invoice templates which you can customize to suit your
                                        business. Customized invoices help you to make sure that your brand and
                                        companies business requirements are completely covered.
                                    </li>
                                </ul>
                                There are multiple billing solutions in the market for small business owners, choose the
                                one that provides you all
                                the features you need and is easy to use.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentytwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentytwo" aria-expanded="false" aria-controls="collapseTwentytwo">
                            <h4>What are the features of Swipez free billing software?</h4>
                        </div>
                        <div id="collapseTwentytwo" class="collapse" aria-labelledby="headingTwentytwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Here are some of the top features of Swipez billing software.
                                <ul>
                                    <li><b>Error-free invoices</b>
                                        <ul>
                                            <li>Accurate GST calculations.</li>
                                            <li>No manual intervention or errors. </li>
                                            <li>GST Compliant and easy to reconcile </li>
                                            <li>Generate multiple bills within minutes</li>
                                        </ul>
                                    </li>
                                    <li><b>Access anywhere</b>
                                        <ul>
                                            <li>Cloud-based online invoicing software.</li>
                                            <li>Generate invoices and access them on-the-go.</li>
                                            <li>Works securely across operating systems or devices</li>
                                            <li>Integrate anywhere using our APIs including any desktop software, apps
                                                or other applications</li>
                                        </ul>
                                    </li>

                                    <li><b>Grow your business</b>
                                        <ul>
                                            <li>Hassle-free and user friendly invoicing.</li>
                                            <li>Simple and secure software.</li>
                                            <li>Visually attractive, in line with your brand</li>
                                            <li>Get cash flow statements of your business with a click of a button</li>
                                        </ul>
                                    </li>
                                    <li><b> At the push of a button</b>
                                        <ul>
                                            <li>Comprehensive and automated features.</li>
                                            <li>Simple and friendly dashboard.</li>
                                            <li>All invoicing needs under one roof</li>
                                            <li>Integrated into your financial accounting software</li>
                                            <li>Send payment link to your customer for quick payments</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentythree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentythree" aria-expanded="false" aria-controls="collapseTwentythree">
                            <h4>How to attach digital signature to an invoice?</h4>
                        </div>
                        <div id="collapseTwentythree" class="collapse" aria-labelledby="headingTwentythree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                In your invoice format simply switch on the digital signature plugin. From the digital
                                signature plugin you can upload your digital signature or create one by typing you name
                                in the text provided and selecting the format you need. There is also a facility to
                                attach your invoices with USB stick digital signature facility as well
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyfour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyfour" aria-expanded="false" aria-controls="collapseTwentyfour">
                            <h4>Can I add attachments to my invoices?</h4>
                        </div>
                        <div id="collapseTwentyfour" class="collapse" aria-labelledby="headingTwentyfour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can attach documents or PDFs to your invoices as supporting. This is possible using
                                the attachment plugins within invoice formats. Edit your invoice format and attach the
                                document you want to be attached along with your invoice.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyfive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyfive" aria-expanded="false" aria-controls="collapseTwentyfive">
                            <h4>Can I provide my customers EMI based payment options?</h4>
                        </div>
                        <div id="collapseTwentyfive" class="collapse" aria-labelledby="headingTwentyfive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, your customers can pay using EMIs (Easy Monthly Installments) via various online
                                payment options like Debit cards, Credit cards, and Netbanking. The pre-determined EMI
                                amount will be debited from your customer's account monthly until paid in full. 
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
    if (document.getElementById('video-promo-container')) {
        document.getElementById('video-promo-container').addEventListener("click", function() {
            //   document.getElementById('video-promo').classList.remove("d-none")
            document.getElementById('video-play-button').classList.add("d-none")
            document.getElementById('video-text').classList.add("d-none")
            document.getElementById('youtube-video').innerHTML = `<iframe id="video-promo" class="" width="480" height="270" src="https://www.youtube.com/embed/T4b9BQHV2UY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="position:absolute; top:0px; left:0px; width:100%; height:100%"></iframe>`
            $("#video-promo")[0].src += "?rel=0&autoplay=1";
        });
    }
</script>

<script>
    var intcounter = 0;
    var istimer = false;
    var titles1 = ["Billing software"];
</script>


@endsection
