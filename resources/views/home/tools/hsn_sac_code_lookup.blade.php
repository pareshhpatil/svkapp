@extends('home.master')

@section('content')
<link rel="stylesheet" href="{!! asset('static/css/fontawesome/all.min.css') !!}">
<link rel="stylesheet" type="text/css"
    href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" type="text/css" href="/static/css/spectrum/spectrum.min.css">
<link rel="stylesheet" type="text/css"
    href="/static/css/downloadinvoiceformat.css{{ Helpers::fileTime('new','static/css/downloadinvoiceformat.css') }}">
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
    .input-group-text {
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .card1 {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 0.25rem;
    }

    .close {
        display: inline-block;
        margin-top: 0;
        margin-right: 0;
        width: 9px;
        height: 9px;
        background-repeat: no-repeat !important;
        text-indent: -10000px;
        outline: 0;
        background-image: url(https://www.swipez.in/assets/admin/layout/img/remove-icon-small.png) !important;
    }
</style>

<section class="jumbotron bg-transparent py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="">
                    <div class="card1">
                        <div class="card-body mb-2 pb-2">
                            <h1 style="margin-top: 10px;;">HSN/SAC code lookup</h1>
                            <p>Simplify HSN/SAC code lookup with just a click! Get HSN/SAC codes for products or
                                services with applicable GST
                            </p>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group" style="width: 100%;">

                                        <label class="control-label col-md-4">Select Type <span
                                                class="required"></span></label>

                                        <div class="col-md-10">

                                            <input type="radio" id="radio1" checked="true" value="Goods" name="type"
                                                class="md-radiobtn" onclick="setInputFields(this);"
                                                data-cy="product_type_goods">
                                            <label for="radio1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                Product </label>


                                            <input type="radio" id="radio2" style="margin-left: 20px;" value="Service"
                                                name="type" class="md-radiobtn" onclick="setInputFields(this);"
                                                data-cy="product_type_service">
                                            <label for="radio2">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                                Service </label>



                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 col-sm-8">
                                    <div class="form-group" style="width: 100%;">

                                        <input type="text" maxlength="50" id="sac_code" name="sac_code"
                                            class="form-control" value="{{ old('sac_code') }}" data-cy="sac_code"
                                            placeholder="Search HSN/SAC code">

                                    </div>
                                </div>

                                <input type="hidden" id="captcha1" name="recaptcha_response"
                                    value="03AGdBq27mFAEQ5bAS-5AgLcd05MTOA0KsLDWXu8E7rUGJCcPaYnWEnX5gNY5A9fYg6NWdRPb7XnCkX5I4UZ3hbrOK_qoyDWIueRIBTbZ96vOXjrrRakGEVqYhoshWEC25t5Dvk2rIkcsLx5678C4aldoV-lGAU0EGC7x6afmVSYRlbHAshOuQx9LMQNagpaFYB4qOuQsQ49RzeZFgI-Z4RKShLrMrEFOg8eV4BqSr10p_g6X7jD5G8EXJ0y2qcDaU-JPxKX9jEt-sUCDU3DNSpyVWMFpUQdfmAZOxsJZ4QhbsY6ZkZ6YAt1y757tir9ufM1fZnBeWWumkRzawwhL_UzhKUcHEycL0Xi2RwQTpou7ansi5nTIHtPTkMe8i2ZOkEW3NzHtXX21FpjKAHjoqGzEg5osMmu6jAV4m0an4ahpF53cpqBEM1G6KnQvJWdkuYVcwY1hh4bRCvhn-owdU-eA7IPRnA3BX1_ZT-l6wWqXTV7Aze0iMbGO-FaR7HIot3KBEkwt5TcYXUIpN9KoI-lIpBnB0rFJvdA">

                                <a data-toggle="modal" href="#search_hsn_sac_code" onclick="calllive();"
                                    data-cy="search_hsn_sac_code">
                                    <span class="btn btn-primary pull-right">
                                        <i class="fa fa-search" style="color:white"> </i>
                                    </span></a>


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
                <h2 class="text-white"> Seamless HSN/SAC code lookup </h2>
                <p class="text-white">Look up a product's HSN/SAC code and applicable GST to ensure accuracy with just a
                    click!</p>
            </div>
        </div>
    </div>
</section>



<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">HSN/SAC code lookup in <b class="text-primary">3</b> simple steps</h2>
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
                            <h2 class="card-title">Select product or service</h2>
                            <p class="card-text">Choose product to lookup HSN code or service to lookup SAC code. Select
                                product or service according to your requirements. </p>
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
                            <h2 class="card-title">Enter product/service name </h2>
                            <p class="card-text">Simply type in the name of the product or service you want to find the
                                HSN/SAC code for, and click on the search button.</p>
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
                            <h2 class="card-title">Obtain HSN/SAC code</h2>
                            <p class="card-text">Get the HSN/SAC code for the product/service, along with a description
                                of the product/service and the applicable GST rates to ensure accuracy.</p>
                        </div>
                    </div>
                </div>
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
                <img alt="Inventory management" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/bill-and-purchases.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Inventory management" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/bill-and-purchases.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Seamless inventory management</strong></h2>
                <p class="lead">Manage & monitor products for your evolving inventory with ease. Add HSN/SAC code to
                    different products with a simple lookup.

                </p>


            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Seamless inventory management</strong></h2>
                <p class="lead">Manage & monitor products for your evolving inventory with ease. Add HSN/SAC code to
                    different products with a simple lookup.

                </p>

            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Easy invoicing" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/easy-to-use-inventory-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Easy invoicing" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/easy-to-use-inventory-software.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong> Hassle-free invoicing</strong></h2>
                <p class="lead">Ensure accurate billing for all your products with applicable GST rates for all your
                    invoices.</p>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong> Hassle-free invoicing</strong></h2>
                <p class="lead">Ensure accurate billing for all your products with applicable GST rates for all your
                    invoices.
                </p>

            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="GST compliance checks" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/sale-and-purchase-history.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="GST compliance checks" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/sale-and-purchase-history.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>GST compliance

                    </strong></h2>
                <p class="lead">Add relevant GST rates for all your products to ensure error-free GST invoicing &
                    compliance.</p>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>GST compliance

                    </strong></h2>
                <p class="lead">Add relevant GST rates for all your products to ensure error-free GST invoicing &
                    compliance. </p>

            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Stock ledger" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/product-sku-mapping.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Stock ledger" class="img-fluid"
                    src="{!! asset('images/product/inventory-management/features/product-sku-mapping.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Dynamic stock ledger</strong></h2>
                <p class="lead">Add different variations of a product with their distinct HSN/SAC codes and applicable
                    GST rates.</p>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Dynamic stock ledger</strong></h2>
                <p class="lead">Add different variations of a product with their distinct HSN/SAC codes and applicable
                    GST rates.
                </p>

            </div>
            <!-- end -->
        </div>
    </div>
</section>


<section id="faq" class="jumbotron py-5 bg-transparent">
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
                            <h4>What are HSN codes? </h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                HSN or Harmonized System of Nomenclature is a system for naming, classifying, and
                                identifying products that is internationally recognized. HSN codes are used to
                                categorize items in order to calculate GST. </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>What are SAC codes?
                            </h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                SAC or Service Accounting Codes are a one-of-a-kind code to identify, measure, and tax
                                services.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Where are HSN/SAC codes used?
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                HSN/SAC codes are used when registering for GST, on invoices, and on the GST returns
                                that must be uploaded to the GST Portal.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Which businesses need to add HSN/SAC codes to their invoices?</h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The only businesses exempt from using HSN/SAC codes on their invoices are the ones with
                                annual turnover of less than Rs. 1.5 crore, or those registered under the composition
                                scheme.<br />
                                Every other business must include HSN/SAC codes in their invoices for items/services of
                                sale.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>How are products classified under HSN?
                            </h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                HSN Code format -
                                <br />&nbsp;&nbsp;&nbsp;&nbsp;[xx]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[xx]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[xx
                                xx]<br />
                                Chapte&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Heading&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Subheading
                                <br /><br />
                                A product is classified under sections, chapters, headings, and subheadings. For
                                example, if you are selling fresh bananas, you can find your HSN code under Section 02:
                                Vegetable Products, Chapter 08: Edible fruits and nuts; peel of citrus fruits or melons,
                                Heading 03: Bananas including Plantains, Subheading 9010: Bananas, fresh. Your HSN code
                                will be 0803. If you are exporting bananas, your HSN code will be 08 03 9010.


                            </div>
                        </div>
                    </div>

                </div>
                <!-- Accordion wrapper -->
            </div>
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
                <a data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal"
                    onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary"
                    href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="search_hsn_sac_code" tabindex="-1" role="basic" aria-hidden="true"
    style="overflow-y: auto;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="find_hsn_sac_code">
                <div class="modal-header" style="min-height: 16.43px;
                padding: 15px;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Find <span id="modal_sac_code_lbl">HSN</span> code</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @livewire('lookup.hsn-sac-code-search')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_hsn_sac_lookup_modal" class="btn default" data-dismiss="modal"
                        data-cy="close_hsn_sac_lookup_modal">Cancel</button>
                    {{-- <button id="saveCode" class="btn blue" data-cy="save_sac_modal"
                        onclick="return saveCode()">Save</button> --}}
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function calllive()
{
    var product_type='Goods';
    if(document.getElementById('radio1').checked) {
        product_type='Goods';
}else if(document.getElementById('radio2').checked) {
    product_type='Service';
}
    setInputFields(product_type);
    document.getElementById('hsn_sac_code').value = document.getElementById('sac_code').value;
     livewire.emit('setSearchTerm',document.getElementById('sac_code').value);
}
function setInputFields(product_type) {
    try {

        if (product_type) {
            if (product_type.value == 'Goods' || product_type == 'Goods') {

                document.getElementById("modal_sac_code_lbl").innerHTML = 'HSN';
                livewire.emit('setProductType', 'Goods');


            } else if (product_type.value == 'Service' || product_type == 'Service') {
                livewire.emit('setProductType', 'Service');
                document.getElementById("modal_sac_code_lbl").innerHTML = 'SAC';
            }
        } else {
            livewire.emit('setProductType', 'Service');
            document.getElementById("modal_sac_code_lbl").innerHTML = 'SAC';
        }
    }
    catch (o) {}
}



</script>
@endsection
