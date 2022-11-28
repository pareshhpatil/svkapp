@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')


<link rel="stylesheet" href="{!! asset('assets/frontend/slick/slick.css') !!}">
<link rel="stylesheet" href="{!! asset('assets/frontend/slick/slick-theme.css') !!}">
<link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css" />

<style>
    .plugin-p {
        margin-top: 12px;
        padding: 2px;
        font-size: 1.5rem;
    }

    .pstyle {
        text-align: start;
        margin-top: 2px;
    }

    .compare-yes {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        margin-left: 40%;
        background: url(/images/yes.svg) center center/22px 16px no-repeat, #18aebf;
    }

    .compare-no {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        margin-left: 40%;
        background: url(/images/no.svg) center center/22px 16px no-repeat, #b82020;
    }

    .tr-grey {
        border-radius: 10px;
        background: rgba(233, 235, 240, .5);
    }

    .table th,
    .table td {
        vertical-align: middle;
        border-top: 0px solid #dee2e6;
    }



    .tabs_over-x-integrations .tabs__content .tabs__panel .slick-arrow {
        transform: translateY(-50%) !important;
        top: 50% !important;
        height: 100px;
    }

    .tabs_over-x-integrations .tabs__content .tabs__panel .slick-prev {
        left: 0;
    }

    .tabs_over-x-integrations .tabs__content .tabs__panel_active .slick-arrow {
        display: block !important;
    }

    .tabs_over-x-integrations .tabs__content .tabs__panel .slick-prev {
        right: 0;
        left: auto;
        bottom: 0;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .tabs_over-x-integrations .tabs__content .tabs__panel .slick-arrow {
        width: 50px;
        height: 50px;
        box-shadow: 4px 0 35px rgb(16 30 54 / 15%);
        -webkit-transform: none !important;
        -ms-transform: none !important;
        transform: none !important;
        display: none !important;
    }

    .slick-next:focus,
    .slick-next:hover,
    .slick-prev:focus,
    .slick-prev:hover {
        outline: 0;
        color: transparent;
    }

    .slick-prev {
        left: -5px;
        background: url(/images/arrow-left-big.svg) center/contain no-repeat;
        background: #ffffff !important;
        width: 50px !important;
        color: #ffffff !important;
    }

    .slick-next {
        right: 0px;
        background: url(/images/arrow-right-big.svg) center/contain no-repeat;
        background: #ffffff !important;
        width: 50px !important;
        color: #ffffff !important;
    }

    .slick-next,
    .slick-prev {
        position: absolute;
        display: block;
        height: 100px;
        width: 12px;
        line-height: 0;
        font-size: 0;
        cursor: pointer;
        background: 0 0;
        color: transparent;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        padding: 0;
        border: none;
        outline: 0;
        z-index: 100;
    }

    .slick-slide {
        background: #fff;
        height: 100px;
        padding: 10px;
        padding-top: 20px;
        font-size: 15px;
        border: 1px solid lightgrey;
        margin-right: 5px;
        line-height: 2.2;
        border-radius: 5%;
    }

    .plus-background {
     background-image: url() !important;
}
</style>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Hassle-free QuickBooks alternative</h1>
                <p class="lead mb-2">Automate invoice generation, GST filing & more from a single dashboard! Create & track GST invoices, expenses, sales, payment reminders, and more with the best cloud-based QuickBooks alternatives in India. </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Best quickbooks alternative" class="img-fluid" src="{!! asset('images/product/best-quickbooks-alternative.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Best quickbooks alternative" class="img-fluid mb-5" src="{!! asset('images/product/best-quickbooks-alternative.svg') !!}" />
                <h1>Hassle-free QuickBooks alternative</h1>
                <p class="lead mb-2">Automate invoice generation, GST filing & more from a single dashboard! Create & track GST invoices, expenses, sales, payment reminders, and more with the best cloud-based QuickBooks alternatives in India. </p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Used and trusted by 25,000+ businesses</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get it
                    now</a>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Get it
                        now</a>

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
                <h2 class="display-4">Compare</h2>
                <h3>What makes Swipez better than Quickbooks?</h3>
            </div>
        </div>
        <div class="row text-center mt-4">
            <div class="container">

                <div class="row ">
                    <div class="col-md-12 d-none d-sm-block">

                        <table class="table text-left mt-4 d-none d-lg-table">
                            <tbody>
                                <tr>
                                    <th scope="row" class="border-0">
                                        <h2 style="text-align: left;"></h2>
                                    </th>
                                    <td class="text-center border-0">
                                        <img alt="Quickbook alternative" class="img-fluid " src="{!! asset('images/product/quickbooks.png') !!}" />

                                    </td>
                                    <td class="text-center border-0">
                                        <img alt="Swipez" style="margin-top: 20px;" class="img-fluid" src="{!! asset('assets/admin/layout/img/logo.png') !!}" />

                                    </td>

                                </tr>

                                <tr class="tr-grey">
                                    <th scope="row">GST Invoicing</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Customizable GST-invoice Templates</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">API Integration</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">Bulk Upload of GST-invoices</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">Payment Reminders</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">Automated Estimate Conversion to Invoice</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">Direct GST Filing</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">GST Reconciliation</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">E-invoicing</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">HSN/SAC Code
                                    </th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">Recurring GST-invoicing</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">Inventory Management</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">Customer Data Management</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr>
                                    <th scope="row">Vendor Payouts</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>
                                <tr class="tr-grey">
                                    <th scope="row">Franchise Payouts</th>
                                    <td>
                                        <div aria-label="Not included." class="compare-no"></div>
                                    </td>
                                    <td>
                                        <div aria-label="Not included." class="compare-yes"></div>
                                    </td>

                                </tr>

                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>


        <!--End First slide-->
        <!--Second slide-->

        <!--End second slide-->
        <!--Third slide-->

        <!--End Third slide-->
        <!--Four slide-->

        <!--End Four slide-->




        <!--  Example item end -->
    </div>

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
                <h3>Get started to know more </h3>
            </div>
        </div>
    </div>
</section>



<section class="jumbotron jumbotron-features  py-5" id="header" style="">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1> Switch from Quickbooks to Swipez seamlessly</h1>
                <img alt="Switch to Quickbooks Alternative" class="img-fluid" src="{!! asset('images/product/quickbook/Switch_to_Quickbooks_Alternative.svg') !!}" />
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Upload directly</h3>
                            <p>Add your data directly on to the Swipez dashboard as per your requirements. Slice n dice the details you want to record according to the features you want to avail</p>
                        </div>
                    </div>

                    <div class="col-md-12  mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Excel import</h3>
                            <p>Import your existing Quickbooks data with a simple excel upload. Bulk upload your data in just a few clicks</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h1>Switch from Quickbooks to Swipez seamlessly</h1>
                <img alt="Simplified GST filing software for businesses" class="img-fluid mb-5" src="{!! asset('images/product/gst-filing.svg') !!}" />
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Upload directly</h3>
                            <p>Add your data directly on to the Swipez dashboard as per your requirements. Slice n dice the details you want to record according to the features you want to avail</p>
                        </div>
                    </div>

                    <div class="col-md-12  mb-2">
                        <div class="card shadow-sm p-3  bg-white rounded h-100">

                            <h3 class="text-secondary pb-2"> Excel import</h3>
                            <p>Import your existing Quickbooks data with a simple excel upload. Bulk upload your data in just a few clicks</p>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get your free account today!
</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="{{ config('app.APP_URL') }}merchant/register">Get your free account today!
</a>
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
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="featureTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">GST Invoicing
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-onlinepayment-tab" data-toggle="pill" href="#pills-onlinepayment" role="tab" aria-controls="pills-onlinepayment" aria-selected="false">GST filing </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-reminder-tab" data-toggle="pill" href="#pills-reminder" role="tab" aria-controls="pills-reminder" aria-selected="false">GST reconciliation
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-bulk-tab" data-toggle="pill" href="#pills-bulk" role="tab" aria-controls="pills-bulk" aria-selected="false">E-invoicing &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-inventory-tab" data-toggle="pill" href="#pills-inventory" role="tab" aria-controls="pills-inventory" aria-selected="false">Payouts</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="pills-recurring-tab" data-toggle="pill" href="#pills-recurring" role="tab" aria-controls="pills-recurring" aria-selected="false">Inventory management
                        </a>
                    </li>

                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/online-invoicing">GST Invoicing</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/Swipez_feature_GST_invoicing.svg') !!}" alt="GST Invoicing" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Ensure error-free GST invoicing. Calculate the applicable GST rates for each item on your invoice in accordance with the GSTN of your client and the nature of the good or service. Easy invoice creation for numerous GST profiles from a single dashboard.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-onlinepayment" role="tabpanel" aria-labelledby="pills-onlinepayment-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/gst-filing-software">GST filing</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/Swipez_feature_GST_filing.svg') !!}" alt="GST filing" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Directly upload your GSTR1 and 3B to the GST portal from the Swipez dashboard. Import your invoices for GST filing by simple bulk uploads via excel sheets, view, and edit your invoices before submitting them to the GST portal. Integrate your GST invoices from e-commerce platforms like Amazon, Flipkart, WooCommerce & more for hassle-free GST filing.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-reminder" role="tabpanel" aria-labelledby="pills-reminder-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/gst-reconciliation-software">GST reconciliation</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/GST_reconciliation.svg') !!}" alt="GST reconciliation" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Run comprehensive GST reconciliation to ensure error-free Input Tax Credit (ITC). Compare and reconcile GST records for a month, a quarter, or any custom period, advanced filters to slice-n-dice your GST reconciliation reports as per your needs. 70% faster GST reconciliation for your Input Tax Credit with reconciliation on 60K invoices in less than 6 seconds. </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-bulk" role="tabpanel" aria-labelledby="pills-bulk-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/e-invoicing">E-invoicing</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/einvoicing.svg') !!}" alt="E-invoicing" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Ensure GST compliance with an e-invoicing software that generates accurate e-invoices in just a few clicks. Create e-invoices with a unique Invoice Reference Number (IRN) and QR code for B2B, SEZs, WPAY, and more. Automate calculation of applicable GST rates to ensure accurate GST for all your e-invoices.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-inventory" role="tabpanel" aria-labelledby="pills-inventory-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/payouts">Payouts</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/Swipez_feature_payouts.svg') !!}" alt="Payouts" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Ensure prompt payouts to your employees, vendors, franchisees, and more. Bulk upload beneficiary data with a simple excel import, pre-define the split of payouts either in percentage or fixed values, and initiate payouts directly into any bank account or UPI ID. All from a single dashboard!

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-recurring" role="tabpanel" aria-labelledby="pills-recurring-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="/inventory-management-software">Inventory management</a>
                                    </center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/quickbook/Swipez_feature_inventory_management.svg') !!}" alt="Inventory management" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Automated inventory management for all your GST invoicing needs. Create and manage items that include a variety of parameters, including cost, sale, maximum retail price (MRP), expiration date, specifications, and more. Real-time updates as you raise GST invoices and increase stock as you create expenses. Lookup HSN/SAC code for products/services and the applicable GST rates with ease.

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
                <h3 class="text-white">A powerful, easy-to-use GST billing & invoicing software</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="http://swipez.prod/merchant/register">Start now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg bg-primary text-white" href="http://swipez.prod/merchant/register">Start now</a>
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
                            Is the Swipez GST invoicing software free?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, You can use all our features for free. We only charge the lowest online payment transaction fee for payments that you collect.

                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Is there a limit to the number of customers I can add to Swipez’s GST invoicing software?

                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there is no limit on the number of customers you can add to any of our plans.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Is my data safe with Swipez?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We at Swipez value our customer’s privacy above all else. Merchants, e-commerce sellers, and businesses rely on us for their invoicing & payment collections. And, we take that responsibility and our duty of care towards them very seriously. The security of our software, systems, and customer data are our number one priority.
                                Every piece of information that is transmitted between your browser and Swipez is protected with 256-bit SSL encryption. This ensures that your data is secure in transit. All data you have entered into Swipez sits securely behind web firewalls. This system is used by some of the biggest companies in the world and is widely acknowledged as safe and secure.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Who can I reach out to for help in my transition from Quickbooks to Swipez?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a dedicated team of experts who will be happy to assist you. You can reach out to us via email, chat, and our call center.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNinteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNinteen" aria-expanded="false" aria-controls="collapseNinteen">
                            Is my data posted (filed) directly to the GST portal?
                        </div>
                        <div id="collapseNinteen" class="collapse" aria-labelledby="headingNinteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez GST filing software submits your invoices to your profile on the GST portal. This is done via APIs which are used to send your information to the GST portal for filing.<br><br>
                                GSTR 3B is a summary of all your invoices for a particular. GSTR 3B needs to be filed every month by the 20th of the following month. For example - Invoices raised in Jan 2020 need to be summarized and filed on or before 20th Feb 2020. GSTR 3B contains input tax credit claimed, GST liability to be paid, reversal charges if any, etc.<br><br>
                                GSTR 1 is filed either monthly or quarterly. GSTR 1 contains invoice wise details of every sale along with the tax liability. This helps your customer to take the eligible input tax credit.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwenty">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                            How are my invoices prepared for GST filing?
                        </div>
                        <div id="collapseTwenty" class="collapse" aria-labelledby="headingTwenty" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Invoices created in Swipez are automatically prepared for GST filing. You can also import your invoices from external sources like E-Commerce seller portals, accounting softwares or any ERP systems you might be using.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            What is GST reconciliation and how does it work?

                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                GST reconciliation is the comparison of two sets of data, your expenses/purchase register and your vendor’s GST filings with the GST portal. The data sets are compared to identify any difference, omissions, or errors to ensure that your Input Tax Credit (ITC) amount is accurate. To run a GST reconciliation you have to-<br><br>
                                a) Get the data from the GST portal<br><br>
                                b) Run a comparative analysis on the two sets of data<br><br>
                                c) Identify the differences<br><br>
                                d) Reconcile the differences to ensure error-free Input Tax Credit.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Do I need to download anything to start using Swipez GST reconciliation software?

                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third-party software to start using the Swipez GST reconciliation software.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            I work for a tax consultancy/accountancy firm. Can I use Swipez’s GST reconciliation software?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can use Swipez GST reconciliation software to manage your clients and ensure error-free Input Tax Credit for their business.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            I work for a tax consultancy/accountancy firm. Can I manage multiple client filings?

                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, certainly. You can manage and run GST reconciliation for multiple GST numbers. With online GST reconciliation reports on more than 600K invoices in less than 6 seconds, Swipez’s GST reconciliation software is just what your firm needs.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Which modes of payouts does Swipez offer?

                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can initiate payouts via transfers to bank accounts, UPI IDs, or eWallets for your vendors, franchise, employees, and more as per your requirements. Your beneficiaries will receive payments directly into their bank accounts, UPI IDs, and eWallets.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            What are the charges applicable on payouts?
                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                A transaction fee is applicable on payouts as per the mode of payment and the payment gateway used. To know more about the different transaction charges, drop us a line or chat with us, and we’ll be happy to assist you.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThirteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            Can I split payments to multiple parties?

                        </div>
                        <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can enable the split payments plugin for your invoices to make direct payments to your vendors. The funds will be automatically transferred to your vendors.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFourteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            Is inventory management integrated with Swipez GST billing?

                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez Billing software is integrated with inventory management software. So when an invoice is raised within Swipez billing software the inventory is reduced automatically. Similarly when an expense / purchase entry is made the inventory of your product is increased automatically.
                            </div>
                        </div>
                    </div>



                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingfifteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapsefifteen" aria-expanded="false" aria-controls="collapsefifteen">
                            How do I get the HSN/SAC code for a product/service?

                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingfifteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Swipez inventory management software has an HSN/SAC code search facility. You can search using either product or service to get the HSN or SAC code and the related GST tax value against each of the products/services.
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

<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Drop us a line and we’ll get in touch.
                </h3>
            </div>
            <div class="col-md-12">
                <a data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/quickbooks-billing-software">Send email</a>
            </div>
        </div>
    </div>
</section>
















<script>

</script>
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script src="{!! asset('assets/frontend/slick/slick.js') !!}" type="text/javascript" charset="utf-8"></script>
<script>
    if (document.getElementById('video-promo-container')) {
        document.getElementById('video-promo-container').addEventListener("click", function() {
            //   document.getElementById('video-promo').classList.remove("d-none")
            document.getElementById('video-play-button').classList.add("d-none")
            document.getElementById('video-text').classList.add("d-none")
            document.getElementById('youtube-video').innerHTML = `<iframe id="video-promo" class="" width="480" height="270" src="https://www.youtube.com/embed/V17c56geXtg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="position:absolute; top:0px; left:0px; width:100%; height:100%"></iframe>`
            $("#video-promo")[0].src += "?rel=0&autoplay=1";
        });
    }

    function showdescription() {
        document.getElementById('showdescription').style.display = "block";
        document.getElementById('readmore').style.display = "none";

    }
</script>

<script>
    var $jq = jQuery.noConflict();
    $jq(document).on('ready', function() {
        $jq(".centerslick").slick({
            dots: false,
            infinite: true,
            centerMode: true,
            slidesToShow: 5,
            slidesToScroll: 3,
            autoplay: true,
        });
    });
</script>

@endsection

@section('customfooter')

@endsection