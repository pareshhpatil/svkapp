@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

<style>
    .mb16 {
        border-radius: 6px;
        object-fit: cover;
        margin-bottom: 16px
    }

    . product-item {}
</style>
@section('content')

<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">

            <div class="col-md-4 col-xs-12 ">
                <a href="/online-gst-calculator">
                    <img alt="Free GST calculator"
                        src="{!! asset('images/product/free-business-tools/gst-calculator.png') !!}" class="mb16">
                    <h4 class="mb4">GST calculator</h4>
                </a>
                <p>Ensure accuracy with our GST calculator to determine GST amount to be included in gross prices & net
                    prices.</p>
            </div>

            <div class="col-md-4 col-xs-12 product-item"><a href="/free-online-gst-lookup">
                    <img alt="Free GST number lookup"
                        src="{!! asset('images/product/free-business-tools/gst-lookup.png') !!}" class="mb16">
                    <h4 class="mb4">GST number lookup</h4>
                </a>
                <p>Verify any GSTIN with a single click. Check the name, address, status, and more of the GSTIN/UIN
                    holder across India with a simple search.</p>
            </div>

            <div class="col-md-4 col-xs-12 product-item">
                <a href="/free-online-simple-interest-calculator">
                    <img alt="Simple interest calculator"
                        src="{!! asset('images/product/free-business-tools/simple-interest-calculator.png') !!}"
                        class="mb16">
                    <h4 class="mb4">Simple interest calculator</h4>
                </a>
                <p>Calculate the interest on your savings and/or loan with a simple interest calculator in a matter of
                    seconds.</p>
            </div>


            <div class="col-md-4 col-xs-12 product-item"><a href="/online-compound-interest-calculator">
                    <img alt="Compound interest calculator"
                        src="{!! asset('images/product/free-business-tools/compound-interest-calculator.png') !!}"
                        class="mb16">
                    <h4 class="mb4">Compound interest calculator</h4>
                </a>
                <p>Estimate the interest accrued on your savings, investments, and pension with a compound interest
                    calculator.</p>
            </div>

            <div class="col-md-4 col-xs-12 product-item"><a
                    href="https://www.lendingkart.com/business-loan/sme/swipez?lksrc=c3JjPXN3aXBleiZ0eXBlPWVjb20mcmVmaWQ9c3dpcGV6MDAx">
                    <img alt="Business loan for small and medium business"
                    src="{!! asset('images/product/free-business-tools/business-loan.png') !!}"
                    class="mb16">
                    <h4 class="mb4">Get a business loan</h4>
                </a>
                <p>Secure customizable working capital, operational, and other business loans at affordable interest
                    rates.</p>
            </div>

            <div class="col-md-4 col-xs-12 product-item">
                <a href="/gst-bill-format">
                    <img alt="Free invoice generator"
                    src="{!! asset('images/product/free-business-tools/free-invoice-generator.png') !!}"
                    class="mb16">
                    <h4 class="mb4">Invoice creator</h4>
                </a>
                <p>Create, send, download, print GST compliant invoices online with just a few clicks.</p>
            </div>

            <div class="col-md-4 col-xs-12 product-item"><a href="/online-hsn-code-lookup">
                    <img  alt="HSN / SAC lookup"
                    src="{!! asset('images/product/free-business-tools/hsn-lookup.png') !!}"
                    class="mb16">
                    <h4 class="mb4">HSN/SAC code lookup</h4>
                </a>
                <p>Look up a product's HSN/SAC code and applicable GST rates with ease.</p>
            </div>


</section>

@endsection
