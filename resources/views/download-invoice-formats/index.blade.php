@extends('home.master')
@section('title', 'Swipez Payouts allows a business disburse bulk payments instantly to any bank account, UPI ID, debit
cards and different digital wallets.')

@section('content')
<script src="/static/js/carousel-jquery-1.9.1.min.js" crossorigin="anonymous"></script>
<script src="/static/js/carousel-bootstrap.js" async=""></script>
<link rel="stylesheet" href="/static/css/carousel.css?version=1647678767">
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
</style>
<section class="jumbotron jumbotron-features bg-secondary py-5" id="header">
    <div class="container">
        <div class="row align-items-center text-white">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Download free invoice formats</h1>
                <p class="lead mb-2 text-white">Use Swipez's free online invoice maker to create and download
                    professional invoices. Send professional and customized invoices to your clients or customers.
                    Choose from our wide range of invoice format downloads to fit your business needs.
                </p>
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <picture>
                    <source srcset="{!! asset('images/product/billing-software/download-invoice-formats-webp.webp') !!}"
                        type="image/webp" alt="Download free invoice formats" class="img-fluid">
                    <source srcset="{!! asset('images/product/billing-software/download-invoice-formats.png') !!}"
                        type="image/png" alt="Download free invoice formats" class="img-fluid">
                    <img src="{!! asset('images/product/billing-software/download-invoice-formats.png') !!}"
                        alt="Set of invoices available for download" class="img-fluid">
                </picture>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <picture>
                    <source srcset="{!! asset('images/product/billing-software/download-invoice-formats-webp.webp') !!}"
                        type="image/webp" alt="Download free invoice formats" class="img-fluid">
                    <source srcset="{!! asset('images/product/billing-software/download-invoice-formats.png') !!}"
                        type="image/png" alt="Download free invoice formats" class="img-fluid">
                    <img src="{!! asset('images/product/billing-software/download-invoice-formats.png') !!}"
                        alt="Set of invoices available for download" class="img-fluid">
                </picture>
                <h1 class="mt-4">Download free invoice formats</h1>
                <p class="lead mb-2 text-white">Use Swipez's free online invoice maker to create and download
                    professional
                    invoices. Send professional and customized invoices to your clients or customers. Choose from our
                    wide range of invoice format downloads to fit your business needs.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5">Download invoice, estimate & proforma invoice formats</h2>
            </div>
            <div class="card-deck">
                <!-- 1st row -->
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <center>GST bill format</center>
                        </h2>
                        <p class="card-text">Create GST bill for your business. Add your GST number, clients
                            GST number, sale items in this easy to use GST invoice format. Download our GST bill
                            format and edit using excel and send it your clients. Send a professional and detailed GST
                            bill to your clients. Alternatively create your GST bill online using the GST bill generator
                            tool using the Create now option.
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home.gstbillformat') }}" class="btn btn-primary mb-sm-2"
                            title="Download GST bill format">Create now</a>
                        <button data-target="#basic" id="downloadGSTBill" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download GST bill format">Download excel</button>
                        <a href="/downloads/GSTBillFormat.xlsx" id="downloadGSTBillExcel" class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <center>Proforma invoice</center>
                        </h2>
                        <p class="card-text">Proforma invoices are sent to a customer before delivery of good or
                            service. Create proforma invoices using the free to download and use excel based proforma
                            format. Add your GST number, clients GST number, sale items and taxes in this proforma
                            invoice format. Send a professional and detailed proforma invoice to your clients.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home.proformainvoice') }}" class="btn btn-primary mb-sm-2"
                            title="Create proforma invoice online">Create now</a>
                        <button data-target="#basic" id="downloadProforma" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download proforma invoice format">Download excel</button>
                        <a href="/downloads/ProformaInvoiceFormat.xlsx" id="downloadProformaExcel" class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">
                            <center>Estimate format</center>
                        </h2>
                        <p class="card-text">Estimates are sent to a client to give them a sense of cost before delivery
                            of goods or service. Create estimates using the free to download and use excel based
                            estimate format. Add your company details, sale items and taxes in this estimate format.
                            Send a professional and detailed estimate to your clients.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home.estimate') }}" class="btn btn-primary mb-sm-2"
                            title="Create estimate format online">Create now</a>
                        <button data-target="#basic" id="downloadEstimate" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download estimate format">Download excel</button>
                        <a href="/downloads/EstimateFormat.xlsx" id="downloadEstimateExcel" class="hidden"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5">Download industry specific invoice format</h2>
            </div>
            <div class="card-deck">
                <!-- 1st row -->
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/sales-invoice-format.svg') !!}"
                        alt="Create or download sales invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Sales invoice format</center>
                        </h3>
                        <p class="card-text">If you are a manufacturer, supplier, stockist, or reseller; you can create
                            and download invoices easily with this sales invoice format. Easily add SAC codes, rates,
                            and GST against the items on your sales invoice.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-sales-invoice-format"
                            class="btn btn-primary mb-sm-2" title="Create sales invoice format online">Create now</a>
                        <button data-target="#basic" id="downloadSales" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download sales invoice format">Download excel</button>
                        <a href="/downloads/SalesInvoiceTemplate.xlsx" id="downloadSalesExcel" class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/isp-invoice-format.svg') !!}"
                        alt="Create or download ISP invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>ISP invoice format</center>
                        </h3>
                        <p class="card-text">This invoice is perfectly suited for ISPs or Internet Service Providers.
                            Using this invoice, you can easily show customers internet plan, data usage, duration and
                            cost in a professional invoice format.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-isp-invoice-format"
                            class="btn btn-primary mb-sm-2" title="Create ISP invoice format online">Create now</a>
                        <button data-target="#basic" id="downloadISP" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download ISP invoice format">Download excel</button>
                        <a href="/downloads/ISPInvoiceTemplate.xlsx" id="downloadISPExcel" class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/cable-invoice-format.svg') !!}"
                        alt="Create or download cable tv billing invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Cable TV invoice format</center>
                            </h2>
                            <p class="card-text">This invoice format is specially customized for Cable TV Operators.
                                Create and send digital invoices to your customers mentioning the cable packages, time
                                period and cost per package or channel.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-cable-invoice-format"
                            class="btn btn-primary mb-sm-2" title="Create cable tv billing invoice format online">Create
                            now</a>
                        <button data-target="#basic" id="downloadCable" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download cable invoice format">Download excel</button>
                        <a href="/downloads/CableInvoiceTemplate.xlsx" id="downloadCableExcel" class="hidden"></a>
                    </div>
                </div>
            </div>
            <div class="card-deck mt-4">
                <!-- 2nd row -->
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/travel-ticket-invoice-format.svg') !!}"
                        alt="Create or download travel ticket booking invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Travel ticket booking invoice format</center>
                        </h3>
                        <p class="card-text">Travel ticket booking invoice format is created to fit the needs of tour
                            and travel operators. With separate sections for booking and cancellation of tickets, you
                            can send detailed digital invoices that look professional.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-travel-ticket-invoice-format"
                            class="btn btn-primary mb-sm-2"
                            title="Create travel ticket booking invoice format online">Create now</a>
                        <button data-target="#basic" id="downloadTicket" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download travel ticket invoice format">Download excel</button>
                        <a href="/downloads/TravelTicketInvoiceTemplate.xlsx" id="downloadTicketExcel"
                            class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/housing-society-invoice-format.svg') !!}"
                        alt="Create or download housing society invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Free invoice generator</center>
                        </h3>
                        <br />
                        <p class="card-text">Free invoice generator has been created to help you create an
                            invoice online within minutes. Invoices created with this tool are generated in a PDF
                            format. This makes it easy for you to share your invoice with your client or if needed take
                            a print out. Get started by making your invoice right here in your browser.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home.freeinvoicegenerator') }}" class="btn btn-primary mb-sm-2"
                            title="Create housing society invoice format online">Free invoice generator</a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/travel-car-invoice-format.svg') !!}"
                        alt="Create or download travel car and transport billing invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Travel car and transport booking invoice format</center>
                        </h3>
                        <p class="card-text">This ready to fill invoice format is specially designed for travel car and
                            transport
                            booking services. Enter the type of transport availed, its duration, charges and your
                            invoice is good to go!</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-travel-car-invoice-format"
                            class="btn btn-primary mb-sm-2"
                            title="Create car and transport billing invoice format online">Create
                            now</a>
                        <button data-target="#basic" id="downloadCar" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download consultant invoice format">Download excel</button>
                        <a href="/downloads/TravelCarInvoiceTemplate.xlsx" id="downloadCarExcel" class="hidden"></a>
                    </div>
                </div>
            </div>
            <div class="card-deck mt-4">
                <!-- 3rd row -->
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/housing-society-invoice-format.svg') !!}"
                        alt="Create or download housing society invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Housing society invoice format</center>
                        </h3>
                        <p class="card-text">This easy to use and detailed invoice format for housing societies is
                            perfect if you need to add one or many line items to your society bill. Add taxes where
                            applicable and create your housing society bill as per your requirement. Use this invoice
                            format to create and send digital invoices to the members of your housing society.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-housing-society-invoice-format"
                            class="btn btn-primary mb-sm-2" title="Create housing society invoice format online">Create
                            now</a>
                        <button data-target="#basic" id="downloadSociety" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download housing society invoice format">Download excel</button>
                        <a href="/downloads/HousingSocietyInvoiceTemplate.xlsx" id="downloadSocietyExcel"
                            class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2"
                        src="{!! asset('images/download-invoice-format/consultant-invoice-format.svg') !!}"
                        alt="Create or download consultant and freelancer invoice format" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Consultant & freelancer invoice format</center>
                        </h3>
                        <p class="card-text">Easy to create invoice format for consultants, freelancers, and service
                            companies so you
                            can create invoices as good as your services. You can easily mention the details like the
                            service
                            provided, rate, hours, SAC code, and the final amount.</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}download-consultant-freelancer-invoice-format"
                            class="btn btn-primary mb-sm-2"
                            title="Create invoice format for consultant or freelancers">Create now</a>
                        <button data-target="#basic" id="downloadConsultant" data-toggle="modal"
                            onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2"
                            title="Download consultant invoice format">Download excel</button>
                        <a href="/downloads/ConsultantInvoiceTemplate.xlsx" id="downloadConsultantExcel"
                            class="hidden"></a>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top p-2" src="{!! asset('images/download-invoice-format/free-account.svg') !!}"
                        alt="Get a free account to create invoices" style="height: 20rem;">
                    <div class="card-body">
                        <h3 class="card-title">
                            <center>Free invoicing software</center>
                        </h3>
                        <br />
                        <ul class="list-unstyled">
                            <li>
                                <svg class="h-6 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Create and send invoices to customers on
                                their
                                email and SMS

                            </li>
                            <li>
                                <svg class="h-6 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Automatically remind customers to make
                                payments on-time
                            </li>
                            <li>
                                <svg class="h-6 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Customize your invoices as per your
                                requirement
                            </li>
                            <li>
                                <svg class="h-6 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Enable online payments on your invoice
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ env('SWIPEZ_BASE_URL') }}register" class="btn btn-primary mb-sm-2"
                            title="Create housing society invoice format online">Register now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-1">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez Invoices!<br /><br />Try it
                    for free. No payment required</h3>
                <a class="btn btn-lg text-white bg-tertiary mt-2"
                    href="{{ env('SWIPEZ_BASE_URL') }}merchant/register">Start
                    Now</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-5">Create professional invoices using free invoice formats</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img loading="lazy" height="354"
                        src="{!! asset('images/product/billing-software/invoice-formats/GST_Invoice_templates.png') !!}"
                        alt="Add logo and create an invoice online" class="img-fluid img-shadow">
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 text-center">
                <img loading="lazy" height="354"
                        src="{!! asset('images/product/billing-software/invoice-formats/GST_Invoice_templates.png') !!}"
                        alt="Add logo and create an invoice online" class="img-fluid img-shadow">
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>GST compliant invoice templates</strong></h2>
                <p class="lead">Choose from a range of customizable invoice templates. Create GST-compliant invoices from the industry-approved invoice templates with your unique brand image & voice. Ensure accurate GST calculations, customer & billing details to ease invoicing
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2 class="text-center"><strong>GST compliant invoice templates</strong></h2>
                <p class="lead text-left">Choose from a range of customizable invoice templates. Create GST-compliant invoices from the industry-approved invoice templates with your unique brand image & voice. Ensure accurate GST calculations, customer & billing details to ease invoicing</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img loading="lazy" height="354"
                        src="{!! asset('images/product/billing-software/invoice-formats/Invoice_template_customization.png') !!}"
                        alt="Invoice template customization" class="img-fluid img-shadow">
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 text-center">
                <img loading="lazy" height="354"
                        src="{!! asset('images/product/billing-software/invoice-formats/Invoice_template_customization.png') !!}"
                        alt="Invoice template customization" class="img-fluid img-shadow">
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Invoice customization</strong></h2>
                <p class="lead">Personalize your invoices with customizable colours, brand logo, and more. Customize industry-approved invoice templates to represent your company's identity and cater to your needs as a business
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2 class="text-center"><strong>Invoice customization</strong></h2>
                <p class="lead text-left">Personalize your invoices with customizable colours, brand logo, and more. Customize industry-approved invoice templates to represent your company's identity and cater to your needs as a business
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Tax breakup for invoices" class="img-fluid"
                    src="{!! asset('images/product/billing-software/invoice-formats/invoice-templates.png') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4 text-center">
                <img alt="Tax breakup for invoices" class="img-fluid img-shadow"
                    src="{!! asset('images/product/billing-software/invoice-formats/invoice-templates.png') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Hassle-free billing & invoicing</strong></h2>
                <p class="lead">Simple, easy-to-use invoice formats for effortless billing and invoicing. Create invoices for the payable amount based on your requirements. Add outstanding dues and online payment options to ensure prompt payments</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2 class="text-center"><strong>Hassle-free billing & invoicing</strong></h2>
                <p class="lead text-left">Simple, easy-to-use invoice formats for effortless billing and invoicing. Create invoices for the payable amount based on your requirements. Add outstanding dues and online payment options to ensure prompt payments</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Fast-track your business with the best in online invoicing software</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">
                    Download to know more!</a>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Download
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
                <h2 class="display-4">Invoicing automation plugins</h2>
                <h5>Enable plugins to customize your invoices with various features and automations.</h5>
            </div>
        </div>
        <div class="row text-center mt-4">
            <div class="container">

                <div class="row ">
                    <div class="col-md-12 d-none d-sm-block">
                        <div class="carousel slide  " id="theCarousel_web_plugin" data-ride="carousel">
                            <!--Slides-->
                            <div class="carousel-inner" role="listbox">
                                <!--First slide-->
                                <div class="item active">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Auto generate invoice numbers</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/invoice-numbers.png') !!}"
                                                    alt="Auto generate invoice numbers" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add sequential invoice numbers to your
                                                        invoices to ease billing. Enable system-generated invoice
                                                        sequence numbers or manually add them to each invoice.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Show TDS deductions in
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tds-deductions.png') !!}"
                                                    alt="TDS deduction on invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Allow your customers to deduct TDS from their
                                                        invoices/estimates before making payments. Automate TDS
                                                        deductions from the invoice total.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Automatically notify your suppliers</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/notify-suppliers.png') !!}"
                                                    alt="Notify supplier for invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automate supplier notifications for
                                                        invoice/estimate payments. Your suppliers will be immediately
                                                        notified via email & SMS upon payment.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End First slide-->
                                <!--Second slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add discount coupons to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/discount-coupon.png') !!}"
                                                    alt="Add discount coupons with invoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add discount coupon codes to your invoices
                                                        that can be used to apply discounts, which will be auto-deducted
                                                        from the invoice amounts.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Email invoices to multiple recipients</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/email-cc-invoices.png') !!}"
                                                    alt="Email invoices with ability to cc" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Email your team, suppliers, and/or franchise
                                                        a copy of your invoices. They will automatically receive emails
                                                        for the invoices created.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Automatically round off invoice value</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/round-off.png') !!}"
                                                    alt="Round off invoice amount" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Eliminate decimal points and round off the
                                                        invoice total to the nearest value. Round off invoice total
                                                        inclusive of applicable taxes.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End second slide-->
                                <!--Third slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add acknowledgment for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/acknowledgement-section.png') !!}"
                                                    alt="Receipt acknowledgement section" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Include an acknowledgment section to your
                                                        invoices. Incorporate the acknowledgment section in your online,
                                                        PDF, and printed invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Franchise invoicing and reporting</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tag-franchise.png') !!}"
                                                    alt="Tag franchise to your invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create invoices for your franchise and split
                                                        the invoice amount once paid. Automate email & SMS notifications
                                                        for franchise invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Tag vendors and split payments</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tag-vendor.png') !!}"
                                                    alt="Split payments while invoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add vendors to your invoices to automatically
                                                        split the payments. Automate the split among different vendors
                                                        or pay one vendor in full.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Third slide-->
                                <!--Four slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Invoices for advance payments</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/prepaid-invoice.png') !!}"
                                                    alt="Prepaid invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create invoices for previously collected
                                                        payments. Pre-paid invoices won't include payment options if the
                                                        amount has been paid in full.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add covering note to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/covering-note.png') !!}"
                                                    alt="Covering notes for invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Send your customers a personalized covering
                                                        note with invoices. Covering notes will be received as a PDF
                                                        attachment with payment options.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customized reminder text for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/custom-sms.png') !!}"
                                                    alt="Customizable reminders for unpaid invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Customize the notification text created &
                                                        sent via email and SMS with your invoices. Personalize the copy
                                                        to reflect your brand and needs.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Four slide-->
                                <!--Five slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customized reminder schedule for
                                                        unpaid invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/custom-reminder-schedule.png') !!}"
                                                    alt="Customized reminder schedule" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Personalize the frequency of payment
                                                        reminders sent via SMS and email. Tailor the schedule of payment
                                                        reminders to suit your requirements.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Support part payment of
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/invoice-part-payment.png') !!}"
                                                    alt="Part payment of invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Allow your customers to pay their invoices in
                                                        instalments. Customize the minimum amount for partial payments
                                                        on invoices to meet your needs.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Auto debit customers via invoices</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/auto-debit-via-invoices.png') !!}"
                                                    alt="Auto debit customer via invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automate the collection of recurring payments
                                                        for your products or services. Set up an auto collections
                                                        schedule that suits your requirements.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Five slide-->
                                <!--Six slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add supporting attachments to invoices</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/add-attachments-to-invoice.png') !!}"
                                                    alt="Support invoice with attachments" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add documents, images & more to invoices with
                                                        a simple file upload. Your customers will receive the invoices
                                                        with attachments via email & SMS.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add digital signature to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/digital-signature-invoice.png') !!}"
                                                    alt="Digitally sign invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automatically add digital signatures to your
                                                        invoices. Create, upload, and personalize your digital signature
                                                        before adding it to invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add expiry date to
                                                        your invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/expiry-date.png') !!}"
                                                    alt="Expire your invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add an expiry date to your invoices, after
                                                        which it will become invalid. Payment reminders will not be
                                                        created or sent post the expiry date.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Six slide-->
                                <!--Seven slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Calculate previous due for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/previous-dues-in-invoices.png') !!}"
                                                    alt="Previous dues in invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Auto-calculate outstanding dues when creating
                                                        a new invoice. The dues will be automatically added to the
                                                        invoice total.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Enable/Disable payments for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/enable-disable-online-payments.png') !!}"
                                                    alt="Enable disable online payments" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Enable or disable online payments for
                                                        invoices and/or estimates. If disabled, invoices won’t include
                                                        online payment options or links.</p>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customize payment receipt information</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/configure-receipt.png') !!}"
                                                    alt="Customize payment receipt" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Personalize the payment receipt received by
                                                        your customers. Add/edit the information in the payment receipts
                                                        as per your requirements.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Six slide-->
                                <!--Seven slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong><a
                                                            href="{{ config('app.APP_URL') }}e-invoicing">E-invoicing</a></strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/einvoicing.png') !!}"
                                                    alt="Create invoices for einvoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create GST compliant e-invoices and submit
                                                        them directly to the Invoice Registration Portal (IRP) with a
                                                        unique Invoice Reference Number and QR code.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>





                                    </div>
                                </div>
                                <!--Seven End-->

                                <!--  Example item end -->
                            </div>
                            <div>
                                <br /><br />
                                <ul class="carousel-indicators">
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="0" class="active"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="1"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="2"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="3"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="4"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="5"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="6"></li>
                                    <li data-target="#theCarousel_web_plugin" data-slide-to="7"></li>
                                </ul>


                            </div>
                        </div>
                    </div>
                    {{-- Ipad & Mobile view --}}

                    <div class="col-md-12 d-sm-none">
                        <div class="carousel slide" id="theCarousel_mobile_plugin" data-ride="carousel">
                            <!--Slides-->
                            <div class="carousel-inner d-sm-none" role="listbox">
                                <!--First slide-->
                                <div class="item active">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Auto generate invoice numbers</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/invoice-numbers.png') !!}"
                                                    alt="Auto generate invoice numbers" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add sequential invoice numbers to your
                                                        invoices to ease billing. Enable system-generated invoice
                                                        sequence numbers or manually add them to each invoice.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Show TDS deductions in
                                                    invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tds-deductions.png') !!}"
                                                    alt="TDS deduction on invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Allow your customers to deduct TDS from their
                                                        invoices/estimates before making payments. Automate TDS
                                                        deductions from the invoice total.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong> Automatically notify your suppliers</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/notify-suppliers.png') !!}"
                                                    alt="Notify supplier for invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automate supplier notifications for
                                                        invoice/estimate payments. Your suppliers will be immediately
                                                        notified via email & SMS upon payment.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End First slide-->
                                <!--Second slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add discount coupons to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/discount-coupon.png') !!}"
                                                    alt="Add discount coupons with invoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add discount coupon codes to your invoices
                                                        that can be used to apply discounts, which will be auto-deducted
                                                        from the invoice amounts.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Email invoices to multiple recipients</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/email-cc-invoices.png') !!}"
                                                    alt="Email invoices with ability to cc" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Email your team, suppliers, and/or franchise
                                                        a copy of your invoices. They will automatically receive emails
                                                        for the invoices created.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Automatically round off invoice value</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/round-off.png') !!}"
                                                    alt="Round off invoice amount" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Eliminate decimal points and round off the
                                                        invoice total to the nearest value. Round off invoice total
                                                        inclusive of applicable taxes.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End second slide-->
                                <!--Third slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add acknowledgment for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/acknowledgement-section.png') !!}"
                                                    alt="Receipt acknowledgement section" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Include an acknowledgment section to your
                                                        invoices. Incorporate the acknowledgment section in your online,
                                                        PDF, and printed invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Franchise invoicing and reporting</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tag-franchise.png') !!}"
                                                    alt="Tag franchise to your invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create invoices for your franchise and split
                                                        the invoice amount once paid. Automate email & SMS notifications
                                                        for franchise invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Tag vendors and split payments</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/tag-vendor.png') !!}"
                                                    alt="Split payments while invoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add vendors to your invoices to automatically
                                                        split the payments. Automate the split among different vendors
                                                        or pay one vendor in full.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Third slide-->
                                <!--Four slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Invoices for advance payments</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/prepaid-invoice.png') !!}"
                                                    alt="Prepaid invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create and send invoices for previously
                                                        collected payments. Pre-paid invoices won't include payment
                                                        options if the amount has been paid in full.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add covering note to
                                                    invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/covering-note.png') !!}"
                                                    alt="Covering notes for invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Send your customers a personalized covering
                                                        note with invoices. Covering notes will be received as a PDF
                                                        attachment with payment options.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customized reminder text for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/custom-sms.png') !!}"
                                                    alt="Customizable reminders for unpaid invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Customize the notification text created &
                                                        sent via email and SMS with your invoices. Personalize the copy
                                                        to reflect your brand and needs.</p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Four slide-->
                                <!--Five slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customized reminder schedule for
                                                        unpaid invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/custom-reminder-schedule.png') !!}"
                                                    alt="Customized reminder schedule" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Personalize the frequency of payment
                                                        reminders sent via SMS and email. Tailor the schedule of payment
                                                        reminders to suit your requirements.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Partial payment of
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/invoice-part-payment.png') !!}"
                                                    alt="Part payment of invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Allow your customers to pay their invoices in
                                                        instalments. Customize the minimum amount for partial payments
                                                        on invoices to meet your needs.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Auto collect via invoices</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/auto-debit-via-invoices.png') !!}"
                                                    alt="Auto debit customer via invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automate the collection of recurring payments
                                                        for your products or services. Set up an auto collections
                                                        schedule that suits your requirements.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Five slide-->
                                <!--Six slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Attach files to invoices</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/add-attachments-to-invoice.png') !!}"
                                                    alt="Support invoice with attachments" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add documents, images & more to invoices with
                                                        a simple file upload. Your customers will receive the invoices
                                                        with attachments via email & SMS.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add digital signature to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/digital-signature-invoice.png') !!}"
                                                    alt="Digitally sign invoice" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Automatically add digital signatures to your
                                                        invoices. Create, upload, and personalize your digital signature
                                                        before adding it to invoices.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Add expiry date to
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/expiry-date.png') !!}"
                                                    alt="Expire your invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Add an expiry date to your invoices, after
                                                        which it will become invalid. Payment reminders will not be
                                                        created or sent post the expiry date.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Six slide-->
                                <!--Seven slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Calculate previous due for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/previous-dues-in-invoices.png') !!}"
                                                    alt="Previous dues in invoices" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Auto-calculate outstanding dues when creating
                                                        a new invoice. The dues will be automatically added to the
                                                        invoice total.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Enable/Disable payments for
                                                        invoices</strong></h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/enable-disable-online-payments.png') !!}"
                                                    alt="Enable disable online payments" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Enable or disable online payments for
                                                        invoices and/or estimates. If disabled, invoices won’t include
                                                        online payment options or links.</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong>Customize payment receipt</strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/configure-receipt.png') !!}"
                                                    alt="Customize payment receipt" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Personalize the payment receipt received by
                                                        your customers. Add/edit the information in the payment receipts
                                                        as per your requirements.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>



                                    </div>
                                </div>
                                <!--End Six slide-->
                                <!--Seven slide-->
                                <div class="item">
                                    <div class="row">

                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch">
                                            <div class="card d-flex flex-column">
                                                <h2 class="gray-600 plugin-p"><strong><a
                                                            href="{{ config('app.APP_URL') }}e-invoicing">E-invoicing</a></strong>
                                                </h2>
                                                <img class="card-img-top p-2 mt-4"
                                                    src="{!! asset('/images/product/online-invoicing/features/einvoicing.png') !!}"
                                                    alt="Create invoices for einvoicing" width="100%" height="200px"
                                                    loading="lazy" class="lazyload" />
                                                <div class="container">

                                                    <p class="lead pstyle">Create GST compliant e-invoices and submit
                                                        them directly to the Invoice Registration Portal (IRP) with a
                                                        unique Invoice Reference Number and QR code.
                                                    </p>
                                                </div>
                                            </div>

                                        </div>





                                    </div>
                                </div>
                                <!--Seven End-->

                                <!--  Example item end -->
                            </div>

                            <div class="mt-2">


                                <a class="left carousel-control" style="background: transparent;"
                                    href="#theCarousel_mobile_plugin" data-slide="prev"><button id="nexttab" class="btn btn-primary" > <  </button></a>
                                <a class="right carousel-control" style="background: transparent;" href="#theCarousel_mobile_plugin"
                                    data-slide="next"><button class="ml-4 btn btn-primary" >>
                                    </button></a>

                            </div>
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
                <h3>Create your invoices online. Get your free account now!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-tertiary" href="{{ env('SWIPEZ_BASE_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1"
                        href="{{ env('SWIPEZ_BASE_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<div class="modal fade " id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-body p-0">
                <div class="container px-0">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="d-none d-lg-block">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 pl-md-0">
                                        <div class="bg-primary p-2 text-white">
                                            <h3 class="card-title text-center my-3">Get a free account for your company
                                            </h3>
                                            <ul class="list-unstyled mx-auto pl-3">
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Unlimited invoices
                                                        and estimates
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Send invoices on
                                                        email and SMS
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Collect online
                                                        payments using invoices
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Auto reminders to
                                                        customers for unpaid invoices
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Bulk upload
                                                        invoices using excel sheets
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <svg class="h-6 text-tertiary"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>


                                                        Create recurring
                                                        invoices with ease
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5 row pt-2 d-block">
                                        <div class="p-1 ">
                                            <p class="pb-2 regtext">Over {{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez
                                                daily. Access your invoices anywhere, get a free account to start
                                                creating and saving your invoices online.</p>
                                            @include('home.product.web_register',['d_type' => "web"])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-lg-none">
                                <div class="col-12">
                                    <div class="p-2">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <p class="regtext">Over {{env('SWIPEZ_BIZ_NUM')}} businesses use Swipez daily.
                                            Access your invoices anywhere, get a free account to start creating and
                                            saving your invoices online.</p>
                                        @include('home.product.web_register',['d_type' => "mob"])
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bg-primary p-2 text-white">
                                        <h3 class="card-title text-center mb-3">Get a free account for your company</h3>
                                        <ul class="list-unstyled mx-auto pl-2">
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Unlimited invoices and
                                                    estimates
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Send invoices on email
                                                    and SMS
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Collect online
                                                    payments using invoices
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Auto reminders to
                                                    customers for unpaid invoices
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Bulk upload invoices
                                                    using excel sheets
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <svg class="h-6 text-tertiary" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>


                                                    Create recurring
                                                    invoices with ease
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Popupsmart plugin-->
<script type="text/javascript" src="https://apiv2.popupsmart.com/api/Bundle/364244" async></script>
<script>
    // Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
  interval: 3000
});

// for every slide in carousel, copy the next slide's item in the slide.
// Do the same for the next, next item.
$('.multi-item-carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));

  if (next.next().length>0) {
    next.next().children(':first-child').clone().appendTo($(this));
  } else {
  	$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
  }
});
</script>
@endsection
