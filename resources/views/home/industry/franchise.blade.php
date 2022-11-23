@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5">
                <h1>Billing software for franchise business</h1>
                <p class="lead mb-5">Strengthening franchisee networks by virtue of streamlining business
                    operations.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="Billing software for franchise business" class="img-fluid"
                     src="{!! asset('images/product/billing-software/industry/franchise-business.svg') !!}" />
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Billing solution for franchise owners and franchisees.</h2>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Benefits of using Swipez <a href="{{ route('home.billing') }}">billing software</a> for franchise business</h2>
        <p class="lead">Swipez billing software for franchise business drives efficiency while ensuring brand consistency</p>
        <div class="container py-2">
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/ala-cart-selection-of-channels.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">1</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Centralised real time franchise data using Swipez’s franchise management software </h2>
                            <p class="card-text">Using Swipez, franchisors can oversee all their franchisees data sets like billing, revenue collected, customer information and taxation. Real time updates help you track growth and also identify underperforming franchises. The Swipez software gives you a detailed top level view of the financial performance of all your franchises and helps manage your network.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Reduced human intervention and operational bandwidth with the Swipez franchise billing software  </h2>
                            <p class="card-text">The Swipez franchise management system reduces the number of visits to the franchise by franchise managers, by giving them a birds eye view on the performance of their franchise.This enables you to identify which franchise needs more support. When systems and processes are in place you can focus on increasing franchise revenue and also multiply your franchise network.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/subscriber-data-management.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- end -->
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">2</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card card-border-none d-none d-sm-flex">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/subscriber-data-management.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Reduced human intervention and operational bandwidth with the Swipez franchise billing software  </h2>
                            <p class="card-text">The Swipez franchise management system reduces the number of visits to the franchise by franchise managers, by giving them a birds eye view on the performance of their franchise.This enables you to identify which franchise needs more support. When systems and processes are in place you can focus on increasing franchise revenue and also multiply your franchise network.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-billing.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">3</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Automated split of payments for franchisors </h2>
                            <p class="card-text">Collecting payments on behalf of your franchise or vice versa is one major pain point of a franchise business. However once payments have been collected disbursing them to the franchise requires human intervention and is a time consuming task. The Swipez franchise business software automates this entire flow, by splitting payments on an invoice level based on percentages fixed by the franchisor, so once an invoice is paid Swipez automatically splits the payment and credits the respective parties. If your model does not work on fixed split percentages, Swipez also allows you to make disbursements to franchises in bulk through a simple excel upload.</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row no-gutters pb-5">
                <div class="col-sm py-2">
                    <div class="card d-none d-sm-flex card-border-none">
                        <div class="card-body">
                            <h2 class="card-title">Reduces initial IT investments and cost incurred on disjointed softwares</h2>
                            <p class="card-text">Cloud based software deployments incur 63% lower initial consulting and implementation costs than on-premise ones. Building on-premise systems require a significant budget for equipment,software as well as an investment in time. Using the Swipez franchise business management solutions your data is stored and processed on your vendor’s virtual servers, so you’re not investing in additional machines and server stacks that need to be kept in an in-house data center.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- end -->
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">4</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card card-border-none d-none d-sm-flex">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-reporting.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="card card-border-none d-sm-none">
                        <div class="card-body">
                            <h2 class="card-title">Reduces initial IT investments and cost incurred on disjointed softwares</h2>
                            <p class="card-text">Cloud based software deployments incur 63% lower initial consulting and implementation costs than on-premise ones. Building on-premise systems require a significant budget for equipment,software as well as an investment in time. Using the Swipez franchise business management solutions your data is stored and processed on your vendor’s virtual servers, so you’re not investing in additional machines and server stacks that need to be kept in an in-house data center.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
            <div class="row no-gutters pb-5">
                <div class="col-sm">
                    <div class="card card-border-none">
                        <div class="card-media">
                            <img src="{!! asset('images/product/billing-software/industry/cable/accurate-billing.svg') !!}"
                                 class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">5</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>

                <div class="col-sm py-2">
                    <div class="card card-border-none">
                        <div class="card-body">
                            <h2 class="card-title"> Standardised billing procedure for franchise businesses </h2>
                            <p class="card-text">Most franchises use their own software or methods for payment collection. The franchisor has no control over how franchisees are invoicing their clients and there is no uniformity in the invoice data collected leading to inaccurate revenue reporting. Many franchisees don't send compliant invoices, causing delays in collections. The Swipez franchise business billing software tackles these issues by giving franchisees a login within the Swipez system.Which requires minimum training for franchisees to use the system. Invoice templates for each franchise are created beforehand with their own logos by the franchisor. All they needed to do is input invoice data into Swipez individually or allow  the franchisor to bill on their behalf. If the franchise has existing invoices they can upload their existing worksheet into Swipez and the system generated invoices are sent out automatically. The entire invoicing process across franchisees is automated and standardised. </p>
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
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez with their franchise management and billing everyday!<br /><br />Register to get your free account</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-4 bg-white" id="features">
    <div class="container">
        <div class="row justify-content-center py-4">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sitemap" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M128 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32zm-24-80h192v48h48v-48h192v48h48v-57.59c0-21.17-17.23-38.41-38.41-38.41H344v-64h40c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32H256c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h40v64H94.41C73.23 224 56 241.23 56 262.41V320h48v-48zm264 80h-96c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32zm240 0h-96c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32z"></path></svg>
                    <h5 class="pb-2">Manage multiple franchisees</h5>
                    <p>Manage all your franchisees from one dashboard. Oversee all of your franchisees data sets like
                        billing, revenue collected, customer information and taxation.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pizza-slice" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M158.87.15c-16.16-1.52-31.2 8.42-35.33 24.12l-14.81 56.27c187.62 5.49 314.54 130.61 322.48 317l56.94-15.78c15.72-4.36 25.49-19.68 23.62-35.9C490.89 165.08 340.78 17.32 158.87.15zm-58.47 112L.55 491.64a16.21 16.21 0 0 0 20 19.75l379-105.1c-4.27-174.89-123.08-292.14-299.15-294.1zM128 416a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm48-152a32 32 0 1 1 32-32 32 32 0 0 1-32 32zm104 104a32 32 0 1 1 32-32 32 32 0 0 1-32 32z"></path></svg>
                    <h5 class="pb-2">Split collected revenue</h5>
                    <p>Configure revenue split between your brand and your franchise. Automate revenue settlements
                        directly to your franchisee bank account.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice-dollar" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M377 105L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 80v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8zm144 263.88V440c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-24.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V232c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v24.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.05 44.44-42.67 45.07z"></path></svg>
                    <h5 class="pb-2">Centralized or franchise run billing</h5>
                    <p>Allow your franchise to manage billing for their customer set OR run it centrally via your
                        central dashboard and provide read-only access to your franchisees.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z"></path></svg>
                    <h5 class="pb-2">On time billing</h5>
                    <p>Fast and error-free invoicing with online payment collections. GST compliant invoices with
                        customized invoice templates as per your company needs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bell" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z"></path></svg>
                    <h5 class="pb-2">Automated reminders</h5>
                    <p>Payment reminders sent to customers automatically on Email and SMS with payment links.
                        Customizable reminder schedule.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mail-bulk" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M160 448c-25.6 0-51.2-22.4-64-32-64-44.8-83.2-60.8-96-70.4V480c0 17.67 14.33 32 32 32h256c17.67 0 32-14.33 32-32V345.6c-12.8 9.6-32 25.6-96 70.4-12.8 9.6-38.4 32-64 32zm128-192H32c-17.67 0-32 14.33-32 32v16c25.6 19.2 22.4 19.2 115.2 86.4 9.6 6.4 28.8 25.6 44.8 25.6s35.2-19.2 44.8-22.4c92.8-67.2 89.6-67.2 115.2-86.4V288c0-17.67-14.33-32-32-32zm256-96H224c-17.67 0-32 14.33-32 32v32h96c33.21 0 60.59 25.42 63.71 57.82l.29-.22V416h192c17.67 0 32-14.33 32-32V192c0-17.67-14.33-32-32-32zm-32 128h-64v-64h64v64zm-352-96c0-35.29 28.71-64 64-64h224V32c0-17.67-14.33-32-32-32H96C78.33 0 64 14.33 64 32v192h96v-32z"></path></svg>
                    <h5 class="pb-2">Bulk invoicing</h5>
                    <p>Raise invoices in bulk to a large customer base via excel upload or APIs. Easy upload formats
                        provided out of the box.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="retweet" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M629.657 343.598L528.971 444.284c-9.373 9.372-24.568 9.372-33.941 0L394.343 343.598c-9.373-9.373-9.373-24.569 0-33.941l10.823-10.823c9.562-9.562 25.133-9.34 34.419.492L480 342.118V160H292.451a24.005 24.005 0 0 1-16.971-7.029l-16-16C244.361 121.851 255.069 96 276.451 96H520c13.255 0 24 10.745 24 24v222.118l40.416-42.792c9.285-9.831 24.856-10.054 34.419-.492l10.823 10.823c9.372 9.372 9.372 24.569-.001 33.941zm-265.138 15.431A23.999 23.999 0 0 0 347.548 352H160V169.881l40.416 42.792c9.286 9.831 24.856 10.054 34.419.491l10.822-10.822c9.373-9.373 9.373-24.569 0-33.941L144.971 67.716c-9.373-9.373-24.569-9.373-33.941 0L10.343 168.402c-9.373 9.373-9.373 24.569 0 33.941l10.822 10.822c9.562 9.562 25.133 9.34 34.419-.491L96 169.881V392c0 13.255 10.745 24 24 24h243.549c21.382 0 32.09-25.851 16.971-40.971l-16.001-16z"></path></svg>
                    <h5 class="pb-2">Recurring billing</h5>
                    <p>Set up subscription billing and your customers will receive your bills automatically at a set
                        frequency every month.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="piggy-bank" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M560 224h-29.5c-8.8-20-21.6-37.7-37.4-52.5L512 96h-32c-29.4 0-55.4 13.5-73 34.3-7.6-1.1-15.1-2.3-23-2.3H256c-77.4 0-141.9 55-156.8 128H56c-14.8 0-26.5-13.5-23.5-28.8C34.7 215.8 45.4 208 57 208h1c3.3 0 6-2.7 6-6v-20c0-3.3-2.7-6-6-6-28.5 0-53.9 20.4-57.5 48.6C-3.9 258.8 22.7 288 56 288h40c0 52.2 25.4 98.1 64 127.3V496c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-48h128v48c0 8.8 7.2 16 16 16h64c8.8 0 16-7.2 16-16v-80.7c11.8-8.9 22.3-19.4 31.3-31.3H560c8.8 0 16-7.2 16-16V240c0-8.8-7.2-16-16-16zm-128 64c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16zM256 96h128c5.4 0 10.7.4 15.9.8 0-.3.1-.5.1-.8 0-53-43-96-96-96s-96 43-96 96c0 2.1.5 4.1.6 6.2 15.2-3.9 31-6.2 47.4-6.2z"></path></svg>
                    <h5 class="pb-2">Auto debit payments</h5>
                    <p>Set up recurring deductions via wallets. Eliminate OTP input for subscription payment collections
                        resulting in faster collections.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="credit-card" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z"></path></svg>
                    <h5 class="pb-2">Collect payments online</h5>
                    <p>Provide multiple payment modes to your customers like UPI, Wallets, Credit, Debit Card, Net
                        Banking. Send and present online receipts to customers upon payments.</p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="smile-beam" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM112 223.4c3.3-42.1 32.2-71.4 56-71.4s52.7 29.3 56 71.4c.7 8.6-10.8 11.9-14.9 4.5l-9.5-17c-7.7-13.7-19.2-21.6-31.5-21.6s-23.8 7.9-31.5 21.6l-9.5 17c-4.3 7.4-15.8 4-15.1-4.5zm250.8 122.8C334.3 380.4 292.5 400 248 400s-86.3-19.6-114.8-53.8c-13.5-16.3 11-36.7 24.6-20.5 22.4 26.9 55.2 42.2 90.2 42.2s67.8-15.4 90.2-42.2c13.6-16.2 38.1 4.3 24.6 20.5zm6.2-118.3l-9.5-17c-7.7-13.7-19.2-21.6-31.5-21.6s-23.8 7.9-31.5 21.6l-9.5 17c-4.1 7.3-15.6 4-14.9-4.5 3.3-42.1 32.2-71.4 56-71.4s52.7 29.3 56 71.4c.6 8.6-11 11.9-15.1 4.5z"></path></svg>
                    <h5 class="pb-2">Customer retention</h5>
                    <p>Send custom coupons to customers. Notify customers of new services via SMS. Provide your
                        customers coupons & offers from the largest online brands.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="percent" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M112 224c61.9 0 112-50.1 112-112S173.9 0 112 0 0 50.1 0 112s50.1 112 112 112zm0-160c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zm224 224c-61.9 0-112 50.1-112 112s50.1 112 112 112 112-50.1 112-112-50.1-112-112-112zm0 160c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zM392.3.2l31.6-.1c19.4-.1 30.9 21.8 19.7 37.8L77.4 501.6a23.95 23.95 0 0 1-19.6 10.2l-33.4.1c-19.5 0-30.9-21.9-19.7-37.8l368-463.7C377.2 4 384.5.2 392.3.2z"></path></svg>
                    <h5 class="pb-2">GST filing for billing data</h5>
                    <p>File your monthly GST directly from your console. Automated reconciliation and preparation of GST
                        R1 & 3B from your collections data.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path></svg>
                    <h5 class="pb-2">Customer database</h5>
                    <p>Built-in customer database to manage all your customer data accurately. Ability to group
                        customers and notify them of your services.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">{{env('SWIPEZ_BIZ_NUM')}} small and medium business owners use Swipez billing<br /><br />Get your account, its free!</h3>
            </div>
            <div class="col-md-12"><a class="btn btn-primary btn-lg text-white"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>
<section id="steps" class="jumbotron py-5 bg-transparent">
    <div class="container">
        <div class="zig">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-7 col-xl-7">
                    <h2 class="section_title">Advantages of cloud based billing software for franchise businesses</h2>
                    <p class="intro-description">Key advantages franchise brands gained using our <a href="{{ route('home.billing') }}">online billing software</a></p>
                    <div class="step mt-5">
                        <div>
                            <div class="circle bg-primary">1</div>
                        </div>
                        <div>
                            <h3>Saved time</h3>
                            <p>Saved over 100+ man hours every month due to automated split payments</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">2</div>
                        </div>
                        <div>
                            <h3>Customized GST invoices</h3>
                            <p>Custom invoices sent for every franchisee with their respective GST number</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">3</div>
                        </div>
                        <div>
                            <h3>Automated settlements</h3>
                            <p>Automated thousands of bi-party settlements every month across multiple franchisees</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">4</div>
                        </div>
                        <div>
                            <h3>Late payments reduced</h3>
                            <p>Late payments reduced by 18%</p>
                        </div>
                    </div>
                    <div class="step">
                        <div>
                            <div class="circle bg-primary">5</div>
                        </div>
                        <div>
                            <h3>Cash collection reduced</h3>
                            <p>Cash collection reduced by 45%</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 pt-5 mt-5 d-none d-sm-flex">
                    <img class="zig-image img-fluid mx-auto" src="{!! asset('images/product/billing-software/industry/cable/advantages-of-cable-billing-software.svg') !!}" title="Companies with Swipez cable billing software" alt="Companies with swipez cable tv operator software have organized their billing" />
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
