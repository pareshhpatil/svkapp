@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>​Online GST Reconciliation Software</h1>
                <p class="lead mb-2">Need to check your Input Tax Credit (ITC) accurately? Need to identify
                    irregularities in your expense data and your vendors' GST filings. Identify and reconcile
                    differences to save time & ensure accuracy.
                </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Simplified GST reconciliation software for CA firms and businesses" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/GST-reconciliation-software.png') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Simplified GST reconciliation software for CA firms and businesses" class="img-fluid mb-5"
                    src="{!! asset('images/product/gst-reconciliation/GST-reconciliation-software.png') !!}" />
                <h1>Online GST Reconciliation Software</h1>
                <p class="lead mb-2">Need to check your Input Tax Credit (ITC) accurately? Need to identify
                    irregularities in your expense data and your vendors' GST filings. Identify and reconcile
                    differences to save time & ensure accuracy.</p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Fast, easy & error-free GSTR 2A reconciliation for businesses</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Online GST reconciliation in <b class="text-primary">3</b> simple steps</h2>
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
                            <h2 class="card-title">Get the GST reconciliation data</h2>
                            <p class="card-text">Define the date and time limits for which you want to compare your
                                expenses and your vendors’ GST invoices. Swipez will collect & curate your vendor’s GST
                                fillings from the GST portal as per your defined timeframe. Your expense data will be
                                compared with the corresponding GST portal data to ensure consistency.</p>
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
                            <h2 class="card-title">Identify Differences</h2>
                            <p class="card-text">The different details of the GST reconciliation data will be
                                automatically compared to help identify any differences. Any differences between your
                                expense data and GST portal data will be highlighted.
                            </p>
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
                            <h2 class="card-title">Reconcile</h2>
                            <p class="card-text">Resolve differences in GST reconciliation data to ensure error-free
                                input tax credit. Reduce time and labor spent on curating data, coordinating with
                                vendors, and settling differences. Notify vendors about gaps in their GST filing via
                                email directly from your online GST reconciliation software.
                            </p>
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
                <h3 class="text-white">GST reconciliation made effortless!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
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
                <h2 class="display-4">Features of GST reconciliation </h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Compare company expense with GST R2A data from GST portal" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/compare-expense-gstr-2a.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Compare company expense with GST R2A data from GST portal" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/compare-expense-gstr-2a.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Compare expenses with GST reconciliation data</strong></h2>
                <p class="lead">Compare your expense data with the invoices uploaded to the GST Government Portal by
                    your vendors. Match expense data with those filed on the GST portal by the vendor.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Compare expenses with GST reconciliation data</strong></h2>
                <p class="lead">Compare your expense data with the invoices uploaded to the GST Government Portal by
                    your vendors. Match expense data with those filed on the GST portal by the vendor.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Compare GST R2 data across time periods" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/gst-reconciliation-across-time-periods.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Compare GST R2 data across time periods" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/gst-reconciliation-across-time-periods.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Find & compare invoices across time periods</strong></h2>
                <p class="lead">Define the time frame for your GST reconciliation as per your requirements. Compare and
                    reconcile GST records for a month, a quarter, or any custom period as suits your needs. Run advanced
                    filters to slice-n-dice your GST reconciliation reports to get all the GSTR information for your
                    chosen timeframe.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Find & compare invoices across time periods</strong></h2>
                <p class="lead">Define the time frame for your GST reconciliation as per your requirements. Compare and
                    reconcile GST records for a month, a quarter, or any custom period as suits your needs. Run advanced
                    filters to slice-n-dice your GST reconciliation reports to get all the GSTR information for your
                    chosen timeframe.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Spot differences in GST R2 filing data" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/identify-difference.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Spot differences in GST R2 filing data" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/identify-difference.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Identify differences in vendor GST filing</strong></h2>
                <p class="lead">Identify variations between your expense data and GST data as filed by your vendors.
                    Ascertain that all invoices for the time have been recorded and filed.

                <div id="showdescription" class="lead">Differences in GST reconciliation
                    reports may include-<br />

                    <div class="lead"><i class="fa-solid fa-square-check"></i> Mismatch in the number of invoices filed.
                        The number of invoices filed by you and your vendor for the defined time frame does not match.
                    </div>
                    <div class="lead"><i class="fa-solid fa-square-check"></i> Unavailable data in vendor’s GST filings.
                        Invoices that appear in your purchase register but haven't been filed by the vendor.</div>
                    <div class="lead"><i class="fa-solid fa-square-check"></i> Unavailable data in your expense records.
                        Invoices that have been filed by the vendor but do not appear in your purchase register.</div>
                    <div class="lead"><i class="fa-solid fa-square-check"></i> Inconsistency in taxable amount.
                        The GST amount for the invoices for the defined time frame does not match
                        between your expense data and your vendor’s GST filings. So, your Input Tax
                        Credit amount isn’t consistent with your vendor’s GST filings.</div>
                </div>
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Identify differences in vendor GST filing</strong></h2>
                <p class="lead">Identify variations between your expense data and GST data as filed by your vendors.
                    Ascertain that all invoices for the time have been recorded and filed. Differences in GST
                    reconciliation reports may include-</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="GST reconciliation reports" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/gstr-2a-recon-report.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="GST reconciliation reports" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/gstr-2a-recon-report.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Comprehensive reports of GST reconciliation</strong></h2>
                <p class="lead">Get all-inclusive GST reconciliation reports for your pre-defined time frame. The number
                    of invoices filed by your vendors, any differences in invoice numbers, differences in data both in
                    your expense data & your vendor’s filings, along with differences in taxable amounts, and more. With
                    an efficient & simple side-by-side tabular view of the detailed summary of the different reports,
                    you can easily prioritize your reconciliation as per your requirements.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Comprehensive reports of GST reconciliation</strong></h2>
                <p class="lead">Get all-inclusive GST reconciliation reports for your pre-defined time frame. The number
                    of invoices filed by your vendors, any differences in invoice numbers, differences in data both in
                    your expense data & your vendor’s filings, along with differences in taxable amounts, and more. With
                    an efficient & simple side-by-side tabular view of the detailed summary of the different reports,
                    you can easily prioritize your reconciliation as per your requirements.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Notify vendor of GST mis-match" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/notify-vendor.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Notify vendor of GST mis-match" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/notify-vendor.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Automated vendor notification of GST filing mis-match</strong></h2>
                <p class="lead">Notify your vendors when a GST reconciliation process shows mis-match for their GST
                    invoices. Request corrections and reconciliation directly via email with a copy of the report
                    attached directly from your GST reconciliation dashboard.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Automated vendor notification of GST filing mis-match</strong></h2>
                <p class="lead">Notify your vendors when a GST reconciliation process shows mis-match for their GST
                    invoices. Request corrections and reconciliation directly via email with a copy of the report
                    attached directly from your GST reconciliation dashboard.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="View differences between expense data and GST portal" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/reconcile-gstr2-difference.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="View differences between expense data and GST portal" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/reconcile-gstr2-difference.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Reconcile differences in expense data and GST portal</strong></h2>
                <p class="lead">Reconcile small differences like invoice number, or missing information in your expense
                    data in just a few clicks. Update your expense data directly from your GST reconciliation dashboard.
                    Make adjustments & auto-update your expense ledger.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Reconcile differences in expense data and GST portal</strong></h2>
                <p class="lead">Reconcile small differences like invoice number, or missing information in your expense
                    data in just a few clicks. Update your expense data directly from your GST reconciliation dashboard.
                    Make adjustments & auto-update your expense ledger.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Faster GST recon" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/faster-gst-recon.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Faster GST recon" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/faster-gst-recon.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Faster GSTR 2 reconciliation</strong></h2>
                <p class="lead">70% faster GST reconciliation for your Input Tax Credit. Run online GST reconciliation
                    on 60K invoices in less than 6 seconds.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Faster GSTR 2 reconciliation</strong></h2>
                <p class="lead">70% faster GST reconciliation for your Input Tax Credit. Run online GST reconciliation
                    on 60K invoices in less than 6 seconds.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Accurate GST ITC filing" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/accurate-gst-recon.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Accurate GST ITC filing" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/accurate-gst-recon.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Error-free ITC filing</strong></h2>
                <p class="lead">Ensure a 100 % accurate Input Tax Credit (ITC) filing for your business with an online
                    GST reconciliation software designed and trusted by 2000+ tax experts. Avoid errors when claiming
                    Input Tax Credit (ITC) returns, notices, audits, and loss of refunds.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Error-free ITC filing</strong></h2>
                <p class="lead">Ensure a 100 % accurate Input Tax Credit (ITC) filing for your business with an online
                    GST reconciliation software designed and trusted by 2000+ tax experts. Avoid errors when claiming
                    Input Tax Credit (ITC) returns, notices, audits, and loss of refunds.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Automated GST compliance of vendor" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/automated-gst-reconciliation.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Automated GST compliance of vendor" class="img-fluid"
                    src="{!! asset('images/product/gst-reconciliation/features/automated-gst-reconciliation.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Automated GST reconciliations</strong></h2>
                <p class="lead">No more pen-and-paper double checking of expense records. No more to-and-fro
                    emails/messages/phone calls with vendors for verification & GST filing reports. Run GST
                    reconciliation in just a few clicks and notify vendors about differences directly with a copy of the
                    report attached.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Automated GST reconciliations</strong></h2>
                <p class="lead">No more pen-and-paper double checking of expense records. No more to-and-fro
                    emails/messages/phone calls with vendors for verification & GST filing reports. Run GST
                    reconciliation in just a few clicks and notify vendors about differences directly with a copy of the
                    report attached.
                </p>
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
                    for
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-outline-primary btn-lg" href="{{ route('home.pricing.billing') }}">See pricing
                    plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-outline-primary btn-lg" href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a>
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
                <h2 class="display-4 text-white">Accurate ITC filing has never been easier!</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="h-16 text-secondary pb-2">
                        <path fill="currentColor"
                            d="M319.9 320c57.41 0 103.1-46.56 103.1-104c0-57.44-46.54-104-103.1-104c-57.41 0-103.1 46.56-103.1 104C215.9 273.4 262.5 320 319.9 320zM369.9 352H270.1C191.6 352 128 411.7 128 485.3C128 500.1 140.7 512 156.4 512h327.2C499.3 512 512 500.1 512 485.3C512 411.7 448.4 352 369.9 352zM512 160c44.18 0 80-35.82 80-80S556.2 0 512 0c-44.18 0-80 35.82-80 80S467.8 160 512 160zM183.9 216c0-5.449 .9824-10.63 1.609-15.91C174.6 194.1 162.6 192 149.9 192H88.08C39.44 192 0 233.8 0 285.3C0 295.6 7.887 304 17.62 304h199.5C196.7 280.2 183.9 249.7 183.9 216zM128 160c44.18 0 80-35.82 80-80S172.2 0 128 0C83.82 0 48 35.82 48 80S83.82 160 128 160zM551.9 192h-61.84c-12.8 0-24.88 3.037-35.86 8.24C454.8 205.5 455.8 210.6 455.8 216c0 33.71-12.78 64.21-33.16 88h199.7C632.1 304 640 295.6 640 285.3C640 233.8 600.6 192 551.9 192z" />
                    </svg>
                    <h3 class="text-secondary pb-2">Manage multiple GST numbers</h3>
                    <p>Run and manage GST reconciliations for multiple GSTNs. Manage expense data for your different
                        GSTNs, vendor data, and more from a single dashboard.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-16 text-secondary pb-2">
                        <path fill="currentColor"
                            d="M384 32C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96C0 60.65 28.65 32 64 32H384zM339.8 211.8C350.7 200.9 350.7 183.1 339.8 172.2C328.9 161.3 311.1 161.3 300.2 172.2L192 280.4L147.8 236.2C136.9 225.3 119.1 225.3 108.2 236.2C97.27 247.1 97.27 264.9 108.2 275.8L172.2 339.8C183.1 350.7 200.9 350.7 211.8 339.8L339.8 211.8z" />
                    </svg>
                    <h3 class="text-secondary pb-2">Error-free Input Tax Credit</h3>
                    <p>Stay on top of your Input Tax Credit with easy reconciliation of your expense data and vendor’s
                        GST filing data from the GST portal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="h-16 text-secondary pb-2">
                        <path fill="currentColor"
                            d="M160 24C160 10.75 170.7 0 184 0H296C309.3 0 320 10.75 320 24C320 37.25 309.3 48 296 48H280L384 192H500.4C508.1 192 515.7 193.4 522.9 196.1L625 234.4C634 237.8 640 246.4 640 256C640 265.6 634 274.2 625 277.6L522.9 315.9C515.7 318.6 508.1 320 500.4 320H384L280 464H296C309.3 464 320 474.7 320 488C320 501.3 309.3 512 296 512H184C170.7 512 160 501.3 160 488C160 474.7 170.7 464 184 464H192V320H160L105.4 374.6C99.37 380.6 91.23 384 82.75 384H64C46.33 384 32 369.7 32 352V288C14.33 288 0 273.7 0 256C0 238.3 14.33 224 32 224V160C32 142.3 46.33 128 64 128H82.75C91.23 128 99.37 131.4 105.4 137.4L160 192H192V48H184C170.7 48 160 37.25 160 24V24zM80 240C71.16 240 64 247.2 64 256C64 264.8 71.16 272 80 272H144C152.8 272 160 264.8 160 256C160 247.2 152.8 240 144 240H80z" />
                    </svg>
                    <h3 class="text-secondary pb-2">Faster reconciliations</h3>
                    <p>Run GST reconciliation in seconds and notify your vendors with just a few clicks. Comprehensive
                        reports that help you identify and slice-n-dice differences faster.</p>
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
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto p-0">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/gst-reconciliation/svklogo2.jpeg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier GST filing was time consuming and expensive. I am now able to file my own
                                monthly GST R1 and 3B within minutes. I am able to stay GST
                                compliant and not worry about monthly GST penalties anymore."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Mahesh Patil</strong>
                            </p>
                            <p>
                                <em>Founder, Siddhivinayak Travels House</em>
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
                                src="{!! asset('images/product/gst-reconciliation/chordia-sarda-associates.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Using Swipez GST filing we are now able to service more clients with the same resources.
                                We are now able to automate large aspects of the filing process."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Amit Chordia</strong>
                            </p>
                            <p>
                                <em>Co-founder, Chordia Sarda & Associates</em>
                            </p>
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
                <h3 class="text-white">Register today to learn more!
                </h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
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
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is GST reconciliation and how does it work?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                GST reconciliation is the comparison of two sets of data, your expenses/purchase
                                register and your vendor’s GST filings with the GST portal. The data sets are compared
                                to identify any difference, omissions, or errors to ensure that your Input Tax Credit
                                (ITC) amount is accurate.
                                To run a GST reconciliation you have to-<br />
                                a) Get the data from the GST portal<br />
                                b) Run a comparative analysis on the two sets of data<br />
                                c) Identify the differences<br />
                                d) Reconcile the differences to ensure error-free Input Tax Credit.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Is the GST portal data accurate?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the GST portal data is accurate. It is curated via APIs and reflects the data as
                                filed on the GST portal accurately. Swipez's robust <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensures accurate and hassle-free GST reconciliation.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="heading3">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            How do I add multiple GSTNs for my business?
                        </div>
                        <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Here’s an <a
                                    href="https://helpdesk.swipez.in/help/how-to-manage-multiple-billing-profiles-and-gst-numbers-05ea9899">article</a>
                                to walk you through the creation of multiple GST numbers on Swipez.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Do I need to download anything to start using Swipez GST reconciliation software?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third-party software to start using the Swipez GST
                                reconciliation software. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez GST reconciliation software effortlessly.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How do I run GST reconciliation in Tally?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Import your expense data from Tally on to the Swipez dashboard in bulk with simple excel
                                uploads. Run GST reconciliation on your Tally data with just a few clicks.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            I work for a tax consultancy/accountancy firm. Can I use Swipez’s GST reconciliation
                            software?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can use Swipez GST reconciliation software to manage your clients and ensure
                                error-free Input Tax Credit for their business.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            I work for a tax consultancy/accountancy firm. Can I manage multiple client filings?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, certainly. You can manage and run GST reconciliation for multiple GST numbers. With
                                online GST reconciliation reports on more than 600K invoices in less than 6 seconds,
                                Swipez’s GST reconciliation software is just what your firm needs.
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
                <h3 class="text-white">Did we miss your question?
                    <br /><br />Drop us a line and we’ll get in touch.

                </h3>
            </div>
            <div class="col-md-12">
                    <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary"
                    href="/getintouch/billing-software-pricing">Send email</a>
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
            document.getElementById('youtube-video').innerHTML = `<iframe id="video-promo" class="" width="480" height="270" src="https://www.youtube.com/embed/V17c56geXtg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="position:absolute; top:0px; left:0px; width:100%; height:100%"></iframe>`
            $("#video-promo")[0].src += "?rel=0&autoplay=1";
        });
    }

    function showdescription() {
        document.getElementById('showdescription').style.display = "block";
        document.getElementById('readmore').style.display = "none";

    }
</script>

@endsection
