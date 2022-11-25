@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-10 col-xl-8 mx-auto text-center">
                <h1 class="display-4 letter-spacing-lg mb-5">Bulk invoicing
                    <br>
                </h1>
                <h2 class="mb-5">Generate invoices in bulk using excel sheet or APIs</h2>
                <a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12 col-lg-11 mx-auto">
                <img class="d-block w-100 shadow-lg
                 rounded-lg" src="{!! asset('images/product/billing-software/features/bulk-invoice.png') !!}"
                    style="margin-bottom: -20rem;">
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5 text-white" style="margin-top: 20rem;">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead text-white">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who
                    are already
                    using Swipez billing software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier we had to create invoices manually and it was a time consuming activity. With
                                Swipez bulk invoicing solution we are now able to generate invoices for complete
                                customer base with a click of a button using the API integration. "</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Infinite</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We tried many softwares and tools to generate invoices in bulk. Swipez bulk invoicing
                                has been the best so far. It generates invoices in bulk with a simple excel upload,
                                moreover the invoices are in the exact design and format that need it."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Chandrabhanu P</strong>
                            </p>
                            <p>
                                <em>Founder, Go Seeko</em>
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
                <h2>Billing software with bulk invoicing feature <br />Generate invoices as per your company invoice
                    format!</h2>
                <br /><a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-5" id="features">
    <!-- reusable svg icon -->
    <svg style="display: none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class=""
        role="img" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <symbol id="tickmark">
                <path fill="currentColor"
                    d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                </path>
            </symbol>
        </defs>
    </svg>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Bulk invoicing features your company’s missing out on</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Simple bulk invoice software" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/simple-bulk-invoicing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Simple bulk invoice software" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/simple-bulk-invoicing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Bulk invoicing made simple</strong></h2>
                <ul class="list-unstyled pt-2">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Raise invoices to a large customer base from your accounting system via excel bulk uploads or
                        APIs
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send bulk invoice notifications to your all your customers via Email and SMS
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto-generate PDFs for invoices in your company format
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto-calculate the date of your invoice including the outstanding dues during bulk upload
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Bulk print the invoice using PDF exports
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send automated invoice due reminders
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Track offline payments of the invoice
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Real-time reports and get updated with collection status of your invoices raised
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Bulk invoicing made simple</strong></h2>
                <ul class="list-unstyled pt-2">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Raise invoices to a large customer base from your accounting system via excel bulk uploads or
                        APIs
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send bulk invoice notifications to your all your customers via Email and SMS
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto-generate PDFs for invoices in your company format
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Auto-calculate the date of your invoice including the outstanding dues during bulk upload
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Bulk print the invoice using PDF exports
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Send automated invoice due reminders
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Track offline payments of the invoice
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark" /></svg>
                        Real-time reports and get updated with collection status of your invoices raised
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Generate invoice using excel" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/bulk-invoice-with-excel-upload.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Generate invoice using excel" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/bulk-invoice-with-excel-upload.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Generate invoices from excel sheets with ease</strong></h2>
                <p class="lead">Swipez invoicing software makes the set up, designing, creating and printing of your
                    invoice seamless, hassle free and quick for your business. With Swipez bulk invoice generator, you
                    can generate any kind of batch invoices including GST invoices, service invoices and export invoices
                    for accounting purposes for your brand automatically. Just upload the details of the invoice from an
                    excel sheet and the invoice will be created automatically and sent to your customers via email and
                    SMS. You can also download the bulk invoices in a PDF format. Swipez gives you the power to
                    individually customize each invoice according to your brand’s logo, name and more, helping you
                    create a strong brand identity. To add the cherry on the cake, you can save time with our mass
                    invoice printing and conveniently complete your billing before you know it.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Generate invoices from excel sheets with ease</strong></h2>
                <p class="lead">Swipez invoicing software makes the set up, designing, creating and printing of your
                    invoice seamless, hassle free and quick for your business. With Swipez bulk invoice generator, you
                    can generate any kind of batch invoices including GST invoices, service invoices and export invoices
                    for accounting purposes for your brand automatically. Just upload the details of the invoice from an
                    excel sheet and the invoice will be created automatically and sent to your customers via email and
                    SMS. You can also download the bulk invoices in a PDF format. Swipez gives you the power to
                    individually customize each invoice according to your brand’s logo, name and more, helping you
                    create a strong brand identity. To add the cherry on the cake, you can save time with our mass
                    invoice printing and conveniently complete your billing before you know it.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Generate customized invoices in bulk" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/generate-customized-invoices-in-bulk.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Generate customized invoices in bulk" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/generate-customized-invoices-in-bulk.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Customize invoices as you want, generate in bulk</strong></h2>
                <p class="lead">Personalized invoices generated by Swipez bulk invoicing software will help you gain a
                    competitive edge with its automated process. Reduce manual invoicing effort with Swipez free bulk
                    invoice generator and enable your business to deliver a broader range of payment and billing
                    arrangements. Data-driven invoices will also help customers understand what they're paying for. With
                    Swipez intuitive invoice generator you can set up auto generated invoice numbers as per your
                    business requirement, date calculations for bill date, due date and expiry date, create pre-paid
                    invoices, allow part payments of invoice, add a cover note and customize email messages and allow
                    TDS deductions, in simple, easy-to-understand formats that draw attention and inspire consumers to
                    take desired actions. With customization and automation, your accounting department can produce
                    bills at a much faster pace and can speed up the invoicing process, so you’re updated on the go.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Customize invoices as you want, generate in bulk</strong></h2>
                <p class="lead">Personalized invoices generated by Swipez bulk invoicing software will help you gain a
                    competitive edge with its automated process. Reduce manual invoicing effort with Swipez free bulk
                    invoice generator and enable your business to deliver a broader range of payment and billing
                    arrangements. Data-driven invoices will also help customers understand what they're paying for. With
                    Swipez intuitive invoice generator you can set up auto generated invoice numbers as per your
                    business requirement, date calculations for bill date, due date and expiry date, create pre-paid
                    invoices, allow part payments of invoice, add a cover note and customize email messages and allow
                    TDS deductions, in simple, easy-to-understand formats that draw attention and inspire consumers to
                    take desired actions. With customization and automation, your accounting department can produce
                    bills at a much faster pace and can speed up the invoicing process, so you’re updated on the go.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Manage franchise payments" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/bulk-invoice-generator.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Manage franchise payments" class="img-fluid"
                    src="{!! asset('images/product/billing-software/features/bulk-invoice-generator.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Bulk invoice generation was never this simple!</strong></h2>
                <p class="lead">We know that you don’t like spending unnecessary time generating invoices on a
                    day-to-day basis. That is why you can save time and store all of your essential client details in
                    one dashboard with Swipez. We have made bulk invoicing simple, fast and easy, allowing you to
                    produce bulk invoices, track outstanding dues, submit important documents and much more in no time.
                    Our central dashboard keeps track of all your invoices and handles them in one place.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Bulk invoice generation was never this simple!</strong></h2>
                <p class="lead">We know that you don’t like spending unnecessary time generating invoices on a
                    day-to-day basis. That is why you can save time and store all of your essential client details in
                    one dashboard with Swipez. We have made bulk invoicing simple, fast and easy, allowing you to
                    produce bulk invoices, track outstanding dues, submit important documents and much more in no time.
                    Our central dashboard keeps track of all your invoices and handles them in one place.</p>
            </div>
            <!-- end -->
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
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Get free billing software now!</a>
                    <a class="btn btn-outline-primary btn-lg text-white mt-2"
                        href="{{ route('home.pricing.billing') }}">See pricing plans</a>
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
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
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
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Access anywhere</h3>
                    <p>Cloud-based simple billing software. Access and manage invoices on-the-go. Works securely across
                        operating systems or any device</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
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
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 384 512">
                        <path fill="currentColor"
                            d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z">
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
                <h2 class="display-4">Bulk invoicing for your industry</h2>
                <p class="lead">Over {{env('SWIPEZ_BIZ_NUM')}} small businesses use features like bulk invoicing in
                    Swipez <a href="{{ route('home.billing') }}">online billing software</a> to manage their business on
                    a day to day basis</p>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="industryTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="industry-cable-tab" data-toggle="pill"
                            href="#industry-cable" role="tab" aria-controls="industry-cable" aria-selected="true">Cable
                            operator</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-billing-tab" data-toggle="pill"
                            href="#industry-billing" role="tab" aria-controls="industry-billing"
                            aria-selected="false">Education industry</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-isp-tab" data-toggle="pill"
                            href="#industry-isp" role="tab" aria-controls="industry-isp" aria-selected="false">ISP</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-travel-tab" data-toggle="pill"
                            href="#industry-travel" role="tab" aria-controls="industry-travel"
                            aria-selected="false">Travel and tour operators</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-franchise-tab" data-toggle="pill"
                            href="#industry-franchise" role="tab" aria-controls="industry-franchise"
                            aria-selected="false">Franchise business</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-housing-tab" data-toggle="pill"
                            href="#industry-housing" role="tab" aria-controls="industry-housing"
                            aria-selected="false">Housing societies</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="industry-consultancy-tab" data-toggle="pill"
                            href="#industry-consultancy" role="tab" aria-controls="industry-consultancy"
                            aria-selected="false">Consultancy firms and freelancers</a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="industry-cable" role="tabpanel"
                        aria-labelledby="industry-cable-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a
                                            href="{{ config('app.APP_URL') }}billing-software-for-cable-operator">Cable
                                            operator billing software</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}"
                                alt="Create invoices or estimates" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Cable operators use Swipez bulk invoicing to upload latest bills for
                                    their customers. The <a
                                        href="{{ config('app.APP_URL') }}billing-software-for-cable-operator">Billing
                                        software for cable operators</a> comes built in with bulk invoicing generation
                                    capability ensuring all your customers get their bills on time. Resulting in faster
                                    payments every month!</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-billing" role="tabpanel"
                        aria-labelledby="industry-billing-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-school">Billing
                                            software for education industry</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/billing-software-for-education-institute.svg') !!}"
                                alt="Billing software for education industry" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Education institutes like coaching classes, schools & colleges use
                                    Swipez bulk invoicing to upload latest fees for their student base. The <a
                                        href="{{ config('app.APP_URL') }}billing-software-for-school">school fees
                                        collection and billing software</a> comes built in with bulk invoicing ability
                                    ensuring all your students get your fee collection requests on time. Resulting in on
                                    time billing and payments every month!
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
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/billing-software-for-isp.svg') !!}"
                                alt="ISP billing software" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Internet service providers use Swipez bulk invoicing to upload latest
                                    bandwidth bills for their internet customers. The <a
                                        href="{{ config('app.APP_URL') }}billing-software-for-isp-telcos">billing
                                        software for ISP’s</a> has the ability to create and send invoices in bulk to
                                    all your customers. ISP's using Swipez bulk invoicing get paid on time every
                                    month!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-travel" role="tabpanel"
                        aria-labelledby="industry-travel-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a
                                            href="{{ config('app.APP_URL') }}billing-software-for-travel-and-tour-operator">Billing
                                            software for travel and tour operators</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/travel-and-tour-billing-software.svg') !!}"
                                alt="Billing software for travel and tour operators" width="640" height="360"
                                loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Travel and tour operators use Swipez bulk invoicing to create invoice for their B2B
                                    and B2C customers. The <a
                                        href="{{ config('app.APP_URL') }}billing-software-for-travel-and-tour-operator">billing
                                        software for travel and tour companies</a> allows tour operators to create
                                    customized invoices in bulk, saving time and effort in every billing cycle.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-franchise" role="tabpanel"
                        aria-labelledby="industry-franchise-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a
                                            href="{{ config('app.APP_URL') }}billing-software-for-franchise-business">Billing
                                            software for franchise business</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/billing-software-for-franchise-business.svg') !!}"
                                alt="Billing software for franchise business" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Franchise based businesses use Swipez bulk invoicing to generate invoices for all
                                    their franchises and their customers. The <a
                                        href="{{ config('app.APP_URL') }}billing-software-for-franchise-business">billing
                                        software for franchise businesses</a> allows a franchise brand to generate
                                    customized invoices in bulk for all the franchise they manage using one dashboard!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-housing" role="tabpanel"
                        aria-labelledby="industry-housing-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a
                                            href="{{ config('app.APP_URL') }}billing-and-venue-booking-software-for-housing-societies">Billing
                                            software for housing societies</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/billing-software-for-housing-society.svg') !!}"
                                alt="Billing software for housing societies" width="640" height="360" loading="lazy"
                                class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Housing society use Swipez bulk invoicing to create and send maintenance bills in
                                    bulk for all their flat owners. The <a
                                        href="{{ config('app.APP_URL') }}billing-and-venue-booking-software-for-housing-societies">free
                                        billing software for housing
                                        societies</a> comes with the feature to enable a housing society to generate
                                    individual maintenance bills with a simple excel upload!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="industry-consultancy" role="tabpanel"
                        aria-labelledby="industry-consultancy-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a
                                            href="{{ config('app.APP_URL') }}invoicing-software-for-freelancers-and-consultants">Billing
                                            software for consultancy firms and freelancers</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4"
                                src="{!! asset('/images/product/billing-software/features/billing-software-for-freelancer.svg') !!}"
                                alt="Billing software for consultancy firms and freelancers" width="640" height="360"
                                loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Freelancers use Swipez bulk invoicing to generate professional invoices in bulk for
                                    their clients. The <a
                                        href="{{ config('app.APP_URL') }}invoicing-software-for-freelancers-and-consultants">billing
                                        software for freelancers and consulting firms</a> has a key feature to generate
                                    professional invoices in bulk to save your time and effort.
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
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get
                        free billing software now!</a>
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
                        <h2 class="display-4">Frequently asked questions</h2>
                        <h3 class="lead">Looking for more information? Here are some questions small and medium
                            businesses
                            owners ask</h3>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h3>How can you generate and send a bulk invoice?</h3>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                An invoice format is as good as an empty bill book (albeit digital). Businesses that
                                need to generate an invoice for a large customer base would typically devote a
                                significant amount of resources solely for this purpose. Swipez smart billing software
                                has a batch invoice generator feature which allows you to enter information of multiple
                                customer bills in one Excel sheet format and upload it to the application to make your
                                life
                                easier. Swipez will then create and send all of your invoices to your customers via
                                email and SMS. It’s that simple! Now that you’ve understood how Swipez’ smart bulk
                                invoicing billing software makes your life stress free, check out these 3 easy-peasy
                                steps you need to know to start generating and sending bulk invoices in a jiffy:
                                <ul>
                                    <li><b>Download excel upload format:</b> To create an invoice for bulk purposes, you
                                        need to start by downloading an Excel version of your invoice format or simply
                                        download our list of professional, ready-to-use templates from the templates
                                        section. If you don't have an invoice template and want to make one, go to the
                                        bulk upload request section and click the build a new template and customize as
                                        per your preference.</li>
                                    <li><b>Fill It Up:</b> The next step in this process is to fill the downloaded
                                        invoices from the Excel sheet with the information. Information like the
                                        customer code, bill date, due date, summary of the products or services given,
                                        absolute cost, taxable amounts is required. Once filled, send the invoice to
                                        your customers via email and SMS, enter “Yes” on the Notify patron column. Save
                                        the Excel sheet once you've filled in all of the relevant information to your
                                        satisfaction.</li>
                                    <li><b>Upload It:</b> Upload the completed Excel sheet to Swipez dashboard from the
                                        drop-down menu. Swipez then performs a few simple checks and alerts you if the
                                        Excel sheet you've uploaded contains any errors. If there are any, make the
                                        required changes in the original Excel sheet and re-upload it as instructed.
                                        Your sheet will appear on the Bulk uploaded payment requests page once it has
                                        been successfully uploaded. The status of the same will be marked as Processing
                                        – that’s where the magic happens. The status will then be changed to Review once
                                        it has been processed. Double-check all the invoices you've generated at this
                                        point before sending them to your clients and Voila! You’re set.</li>
                                </ul>
                                To learn in depth how to start bulk invoicing, check out our helpdesk article - <a
                                    href="https://helpdesk.swipez.in/help/how-to-create-multiple-invoices-using-bulk-invoicing"
                                    target="_blank">Bulk invoices - How to create multiple invoices using bulk
                                    invoicing</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h3>What is batch invoicing?</h3>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                In the simplest terms, Batch invoicing is creating multiple invoices for multiple
                                customers at the same time. It's a neat time management technique that eliminates
                                distractions and improves productivity. Using batch invoicing you can generate more
                                invoices quickly and with less stress and hassle, leaving you more time to spend on
                                paying projects.

                                Batching invoicing allows you to focus on growing your business or brand. By
                                streamlining the invoicing process you can work more quickly. This allows you to avoid
                                distractions that disrupt your mental rhythm when working on assignments for which you
                                get compensated.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h3>Why is batch invoicing helpful for you?</h3>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                For many people, batch invoicing is either daunting or uninteresting. You enjoy getting
                                paid, but generating invoices for your brand may be a nuisance at times, especially if
                                you produce more extensive invoices. With Swipez batch invoicing feature,you can
                                schedule your invoices accordingly and complete all of your invoicing responsibilities
                                at once. This means that after it's done, you're done for another week or month.

                                Batch invoicing also involves repetitive tasks, one that involves calculating figures,
                                entering data, and dealing with invoice software. This can be taxing for professionals.
                                Swipez bulk invoicing feature makes your job much more simpler and economical. You can
                                get into that zone once a week or month rather than every day.

                                For freelancers, work schedules can be erratic even at the best of times. Things can
                                become unruly or delayed, and new things can appear out of nowhere. Emergencies come,
                                and distractions are unavoidable. You can improve general consistency and stability in
                                your work routine with Swipez bulk invoicing feature to create invoices for all your
                                clients at once.

                                Clients who you engage with on a regular basis will rapidly acclimate to your billing
                                schedules. They'll know when to expect your invoices and on what day. This adds a
                                professional touch to your companies services and ensures that they aren't left in the
                                dark about when and if you submitted an invoice.

                                Billing in batches also saves time and work. With Swipez, when you create invoices for
                                similar types of projects, you can merely copy and paste information and modify details
                                accordingly. You can also create templates to speed up the process further. It’s that
                                easy!
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h3>Are my invoices and data safe on Swipez billing software system?</h3>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
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


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h3>Who can generate bulk invoices?</h3>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Any company who needs to generate more than 5 invoices at a given time is the perfect
                                candidate to try out bulk invoicing. Without bulk invoicing you would have to create
                                each of these invoice manually which is time consuming, error prone and a pain to do
                                every month or week. Bulk invoices is perfect for your company if you manage and bill
                                more than 5 customers at any given point in time.
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- Accordion wrapper -->
        </div>
    </div>
</section>
@endsection
