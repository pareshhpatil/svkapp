@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')

<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>​Free WooCommerce Invoicing plugin for ecommerce sellers</h1>
                <p class="lead mb-2">A one-stop solution for all your billing and invoicing needs for e-commerce sellers
                    hosted on WooCommerce. Create and send invoices, manage your inventory, and get real-time reports on
                    payments. All from a single dashboard! </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Simplified GST filing software for businesses" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing-plugin.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Simplified GST filing software for businesses" class="img-fluid mb-5"
                    src="{!! asset('images/product/woocommerce-invoicing-plugin.svg') !!}" />
                <h1>Free WooCommerce Invoicing plugin for ecommerce sellers</h1>
                <p class="lead mb-2">A one-stop solution for all your billing and invoicing needs for e-commerce sellers
                    hosted on WooCommerce. Create and send invoices, manage your inventory, and get real-time reports on
                    payments. All from a single dashboard!</p>
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
                <h3>End-to-end Billing & Invoicing plugin for WooCommerce sellers</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Automate invoicing for WooCommerce with <b class="text-primary">3</b> simple steps
        </h2>
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
                            <h2 class="card-title"> Add WooCommerce Invoicing Plugin</h2>
                            <p class="card-text">Simply navigate to your WooCommerce dashboard on WordPress and select
                                the “Add New” option from the Plugins menu. There are no file transfers, new account
                                creation, etc, just one simple click.
                            </p>
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
                            <h2 class="card-title">Install WooCommerce Invoicing Plugin</h2>
                            <p class="card-text">Type “WooCommerce invoice template & billing by Swipez” in the search
                                field and click “Search Plugins”. Click on “Install Now” to activate it and start
                                creating invoices for your WooCommerce store.
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
                            <h2 class="card-title">Enable WooCommerce Invoicing Plugin</h2>
                            <p class="card-text">Confirm that you want to install the plugin, and WordPress will take
                                care of the rest for you. Once the installation has been completed, click on “Activate
                                Plugin” to start using the plugin to create and send GST compliant invoices for your
                                WooCommerce products.
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
                <h3 class="text-white"> Manage your invoicing & inventory effortlessly!</h3>
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
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="GST compliant invoicing for woocommerce" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/woocommerce-gst-invoice.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="GST compliant invoicing for woocommerce" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/woocommerce-gst-invoice.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong> GST compliant invoicing</strong></h2>
                <p class="lead">Create & send GST invoices for your WooCommerce products. Choose from a range of
                    industry-approved invoice templates to start creating invoices with the applicable taxes
                    auto-calculated.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>GST compliant invoicing</strong></h2>
                <p class="lead">Create & send GST invoices for your WooCommerce products. Choose from a range of
                    industry-approved invoice templates to start creating invoices with the applicable taxes
                    auto-calculated.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Payment collections made simple" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/payment-collection.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Payment collections made simple" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/payment-collection.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Hassle-free payment collections</strong></h2>
                <p class="lead">Collect payments directly from your invoices with easy-to-use payment links. Generate
                    invoices with a simple access and secret key from your Swipez dashboard to automatically add payment
                    links to your WooCommerce invoices.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Hassle-free payment collections</strong></h2>
                <p class="lead">Collect payments directly from your invoices with easy-to-use payment links. Generate
                    invoices with a simple access and secret key from your Swipez dashboard to automatically add payment
                    links to your WooCommerce invoices.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Accept multiple payment modes" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/accept-all-payment-modes.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Accept multiple payment modes" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/accept-all-payment-modes.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Multiple payment modes
                    </strong></h2>
                <p class="lead">Help your customers pay for your WooCommerce invoices with UPI, Debit/Credit cards, Net
                    banking, e-Wallets & more. Collect payments both online and offline.

                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Multiple payment modes</strong></h2>
                <p class="lead">Help your customers pay for your WooCommerce invoices with UPI, Debit/Credit cards, Net
                    banking, e-Wallets & more. Collect payments both online and offline.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Automated invoicing for woocommerce" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/automated-invoicing-woocommerce.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Automated invoicing for woocommerce" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/automated-invoicing-woocommerce.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Automated invoicing for woocommerce</strong></h2>
                <p class="lead">Automate the creation & dispatch of invoices every time a customer places an order on
                    your WooCommerce store. Create invoices with online payment options via UPI, eWallets, Credit/Debit
                    cards, Netbanking, or payment gateway. Moreover, auto-generated unpaid invoices for Cash on Delivery
                    orders placed on your WooCommerce store. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Automated invoicing for woocommerce</strong></h2>
                <p class="lead">Automate the creation & dispatch of invoices every time a customer places an order on
                    your WooCommerce store. Create invoices with online payment options via UPI, eWallets, Credit/Debit
                    cards, Netbanking, or payment gateway. Moreover, auto-generated unpaid invoices for Cash on Delivery
                    orders placed on your WooCommerce store. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Woocommerce reports" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/woocommerce-sales-reports.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Woocommerce reports" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/woocommerce-sales-reports.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Comprehensive real-time reports</strong></h2>
                <p class="lead">Manage & monitor all your invoices from a single dashboard. Get auto-updated real-time
                    reports on all invoices. Track payments for invoices both with online & offline payment options.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Comprehensive real-time reports </strong></h2>
                <p class="lead">Manage & monitor all your invoices from a single dashboard. Get auto-updated real-time
                    reports on all invoices. Track payments for invoices both with online & offline payment options.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Woocommerce inventory management" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/inventory-management.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Woocommerce inventory management" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/inventory-management.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Effortless inventory management</strong></h2>
                <p class="lead">Simplify and automate your inventory management for your WooCommerce store. Auto-update
                    your items of sales, everytime you generate & send an invoice. Your stock/product quantity will be
                    automatically updated in real-time when an invoice is created for an order placed. Any discounts
                    attached to the different items of the inventory will be auto-detected when creating an invoice.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong> Effortless inventory management</strong></h2>
                <p class="lead">Simplify and automate your inventory management for your WooCommerce store. Auto-update
                    your items of sales, everytime you generate & send an invoice. Your stock/product quantity will be
                    automatically updated in real-time when an invoice is created for an order placed. Any discounts
                    attached to the different items of the inventory will be auto-detected when creating an invoice.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Woocommerce inventory reporting" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/inventory-analytics.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Woocommerce inventory reporting" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/inventory-analytics.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Real-time updates on inventory</strong></h2>
                <p class="lead">Automatically create & update a single ledger for all your products and sales. View sale
                    and purchase history for each of your products along with the invoice and expense entries. Refunds &
                    cancellations are also automatically updated in your inventory to reflect the items available for
                    sale.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Real-time updates on inventory</strong></h2>
                <p class="lead">Automatically create & update a single ledger for all your products and sales. View sale
                    and purchase history for each of your products along with the invoice and expense entries. Refunds &
                    cancellations are also automatically updated in your inventory to reflect the items available for
                    sale.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Sync woocommerce inventory" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sync-woocommerce-inventory.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Sync woocommerce inventory" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sync-woocommerce-inventory.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Sync woocommerce inventory</strong></h2>
                <p class="lead">Auto sync your WooCommerce inventory with Swipez. Manage and track all your
                    services/items of sale with multiple variables like cost price, sale price, specifications & more.
                    Auto-update your dynamic and evolving inventory in real-time.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Sync woocommerce inventory</strong></h2>
                <p class="lead">Auto sync your WooCommerce inventory with Swipez. Manage and track all your
                    services/items of sale with multiple variables like cost price, sale price, specifications & more.
                    Auto-update your dynamic and evolving inventory in real-time.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Variable pricing of products" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/variable-pricing.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Variable pricing of products" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/variable-pricing.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Variable items of sale</strong></h2>
                <p class="lead">Add and manage items of sale with different attributes & variations like color, size,
                    dimensions, and more. Monitor and track all variations of your items of sale with an all-inclusive
                    inventory of all your stock. Create as many variations of the different products as per requirements
                    and have all the information at your fingertips when creating invoices.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Variable items of sale</strong></h2>
                <p class="lead">Add and manage items of sale with different attributes & variations like color, size,
                    dimensions, and more. Monitor and track all variations of your items of sale with an all-inclusive
                    inventory of all your stock. Create as many variations of the different products as per requirements
                    and have all the information at your fingertips when creating invoices.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Map SKU" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sku-mapping.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Map SKU" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sku-mapping.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>SKU mapping of products</strong></h2>
                <p class="lead">Add SKUs to any of your WooCommerce products with just a few clicks. Track and identify
                    your inventory or stock with ease by assigning distinct stock-keeping units.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>SKU mapping of products</strong></h2>
                <p class="lead">Add SKUs to any of your WooCommerce products with just a few clicks. Track and identify
                    your inventory or stock with ease by assigning distinct stock-keeping units.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="HSN lookup" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/hsn-sac-lookup.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="HSN lookup" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/hsn-sac-lookup.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Easy HSN or SAC code searches</strong></h2>
                <p class="lead">Unsure about the HSN/SAC code for your items of sale? Search for the HSN/SAC code and
                    the applicable GST% with ease. Ensure GST compliance & accuracy for all your invoices in just a few
                    clicks. </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Easy HSN or SAC code searches</strong></h2>
                <p class="lead">Unsure about the HSN/SAC code for your items of sale? Search for the HSN/SAC code and
                    the applicable GST% with ease. Ensure GST compliance & accuracy for all your invoices in just a few
                    clicks. </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Woocommerce sales reports" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sales-reports.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Woocommerce sales reports" class="img-fluid"
                    src="{!! asset('images/product/woocommerce-invoicing/features/sales-reports.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Real-time sales reports</strong></h2>
                <p class="lead">Get a snapshot report of your WooCommerce sales for your pre-defined time frame. Track
                    all sales & invoices created in a week, a month, six months, or more with a simple and comprehensive
                    bar chart. Review your sales reports in real-time.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Real-time sales reports</strong></h2>
                <p class="lead">Get a snapshot report of your WooCommerce sales for your pre-defined time frame. Track
                    all sales & invoices created in a week, a month, six months, or more with a simple and comprehensive
                    bar chart. Review your sales reports in real-time.</p>
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
                    free. No payment required.</h3>
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
                    {{-- <a class="btn btn-outline-primary btn-lg" href="{{ route('home.pricing.billing') }}">Pricing
                        plans</a> --}}
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
                <h2 class="display-4 text-white">Simplify your WooCommerce invoicing & inventory management seamlessly!
                </h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center mb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mobile-alt"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 320 512">
                        <path fill="currentColor"
                            d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z">
                        </path>
                    </svg>

                    <h5 class="pb-2">Multiple payment options</h5>
                    <p>Help your customers to pay via multiple modes with invoices with both online & offline payment
                        options. Collect payments online via Credit/Debit card, UPI, e-Wallets, Netbanking & more. Along
                        with offline payments like CoD, Cheque & more.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sitemap"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M128 352H32c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32zm-24-80h192v48h48v-48h192v48h48v-57.59c0-21.17-17.23-38.41-38.41-38.41H344v-64h40c17.67 0 32-14.33 32-32V32c0-17.67-14.33-32-32-32H256c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h40v64H94.41C73.23 224 56 241.23 56 262.41V320h48v-48zm264 80h-96c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32zm240 0h-96c-17.67 0-32 14.33-32 32v96c0 17.67 14.33 32 32 32h96c17.67 0 32-14.33 32-32v-96c0-17.67-14.33-32-32-32z">
                        </path>
                    </svg>

                    <h5 class="pb-2">Error-free invoicing
                    </h5>
                    <p>Create & send GST compliant invoices for your WooCommerce orders with online & offline payment
                        options. Auto-generate unpaid invoices for Cash on Delivery orders with real-time status
                        updates.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="robot"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 512">
                        <path fill="currentColor"
                            d="M32,224H64V416H32A31.96166,31.96166,0,0,1,0,384V256A31.96166,31.96166,0,0,1,32,224Zm512-48V448a64.06328,64.06328,0,0,1-64,64H160a64.06328,64.06328,0,0,1-64-64V176a79.974,79.974,0,0,1,80-80H288V32a32,32,0,0,1,64,0V96H464A79.974,79.974,0,0,1,544,176ZM264,256a40,40,0,1,0-40,40A39.997,39.997,0,0,0,264,256Zm-8,128H192v32h64Zm96,0H288v32h64ZM456,256a40,40,0,1,0-40,40A39.997,39.997,0,0,0,456,256Zm-8,128H384v32h64ZM640,256V384a31.96166,31.96166,0,0,1-32,32H576V224h32A31.96166,31.96166,0,0,1,640,256Z">
                        </path>
                    </svg>
                    <h5 class="pb-2">All-inclusive inventory management</h5>
                    <p>Automate the tracking & management of your evolving inventory. Include & monitor items of sale
                        with different variations and attributes in just a few clicks.
                    </p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height text-center mb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file-invoice"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 384 512">
                        <path fill="currentColor"
                            d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8V72zm0 64c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16c0 4.42-3.58 8-8 8H72c-4.42 0-8-3.58-8-8v-16zm256 304c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-200v96c0 8.84-7.16 16-16 16H80c-8.84 0-16-7.16-16-16v-96c0-8.84 7.16-16 16-16h224c8.84 0 16 7.16 16 16z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Quick access to HSN/SAC codes</h5>
                    <p>Look up the HSN/SAC codes for your different products in just a few clicks. Add products to your
                        growing inventory with applicable GST rates.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bell"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M224 512c35.32 0 63.97-28.65 63.97-64H160.03c0 35.35 28.65 64 63.97 64zm215.39-149.71c-19.32-20.76-55.47-51.99-55.47-154.29 0-77.7-54.48-139.9-127.94-155.16V32c0-17.67-14.32-32-31.98-32s-31.98 14.33-31.98 32v20.84C118.56 68.1 64.08 130.3 64.08 208c0 102.3-36.15 133.53-55.47 154.29-6 6.45-8.66 14.16-8.61 21.71.11 16.4 12.98 32 32.1 32h383.8c19.12 0 32-15.6 32.1-32 .05-7.55-2.61-15.27-8.61-21.71z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Comprehensive reports</h5>
                    <p>Get detailed reports on invoices created & sent, payment collections, and inventory. Automate the
                        inventory updates in real-time with auto-synced invoice creation, refunds & cancellations, and
                        much more.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mail-bulk"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 576 512">
                        <path fill="currentColor"
                            d="M160 448c-25.6 0-51.2-22.4-64-32-64-44.8-83.2-60.8-96-70.4V480c0 17.67 14.33 32 32 32h256c17.67 0 32-14.33 32-32V345.6c-12.8 9.6-32 25.6-96 70.4-12.8 9.6-38.4 32-64 32zm128-192H32c-17.67 0-32 14.33-32 32v16c25.6 19.2 22.4 19.2 115.2 86.4 9.6 6.4 28.8 25.6 44.8 25.6s35.2-19.2 44.8-22.4c92.8-67.2 89.6-67.2 115.2-86.4V288c0-17.67-14.33-32-32-32zm256-96H224c-17.67 0-32 14.33-32 32v32h96c33.21 0 60.59 25.42 63.71 57.82l.29-.22V416h192c17.67 0 32-14.33 32-32V192c0-17.67-14.33-32-32-32zm-32 128h-64v-64h64v64zm-352-96c0-35.29 28.71-64 64-64h224V32c0-17.67-14.33-32-32-32H96C78.33 0 64 14.33 64 32v192h96v-32z">
                        </path>
                    </svg>
                    <h5 class="pb-2">Sales overview</h5>
                    <p>Get a snapshot view of your sales in a week, month, six months, and more with a simple bar chart.
                        Manage & monitor your store’s cash flow in real-time..</p>
                </div>
            </div>
        </div>
        {{-- <div class="row row-eq-height text-center pb-5">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512">
                        <path fill="currentColor"
                            d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Multiple payment options</h3>
                    <p>Help your customers to pay via multiple modes with invoices with both online & offline payment
                        options. Collect payments online via Credit/Debit card, UPI, e-Wallets, Netbanking & more. Along
                        with offline payments like CoD, Cheque & more.</p>
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
                    <h3 class="text-secondary pb-2">Error-free invoicing</h3>
                    <p>Create & send GST compliant invoices for your WooCommerce orders with online & offline payment
                        options. Auto-generate unpaid invoices for Cash on Delivery orders with real-time status
                        updates.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">All-inclusive inventory management</h3>
                    <p>Automate the tracking & management of your evolving inventory. Include & monitor items of sale
                        with different variations and attributes in just a few clicks.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Comprehensive reports</h3>
                    <p>Get detailed reports on invoices created & sent, payment collections, and inventory. Automate the
                        inventory updates in real-time with auto-synced invoice creation, refunds & cancellations, and
                        much more.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol"
                        class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512">
                        <path fill="currentColor"
                            d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Sales overview</h3>
                    <p>Get a snapshot view of your sales in a week, month, six months, and more with a simple bar chart.
                        Manage & monitor your store’s cash flow in real-time.</p>
                </div>
            </div>
        </div> --}}
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
                                src="{!! asset('images/product/gst-filing/svklogo2.jpeg') !!}">
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
                                src="{!! asset('images/product/gst-filing/chordia-sarda-associates.png') !!}">
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
                <h2 class="display-4 text-white">Automate invoicing & inventory management with ease</h2>
                <p class="text-white lead">Fast track your WooCommerce business with a comprehensive invoicing &
                    inventory management solution</p>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Get a
                    free account</a>
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
                            Is the WooCommerce Invoicing plugin for e-commerce sellers by swipez free?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, it is free. The only charges applied are <a
                                    href="https://www.swipez.in/payment-gateway-charges"> online payment collection
                                    fees</a>. These are regulatory fees applied by banks to provide online payment
                                collections via a payment gateway.


                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            When do I receive the money paid by my customers?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You will receive the money paid by your customers in 2 working days. If your customer
                                makes a payment on Monday, you will receive the funds on Wednesday of the same week.
                                Funds are settled in bulk i.e., multiple customer payments are clubbed together and you
                                receive it directly in the bank account registered from your WooCommerce store.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Why is inventory management important for invoicing?

                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The key reasons why inventory management is important for invoicing are-<br />
                                <li>A centralized database of all your products/items of sales, ready at your disposal
                                    for invoice creation. </li>
                                <li> Products are simple to manage and edit to add cost price, sale price, and images to
                                    distinguish. </li>
                                <li>An inventory management solution helps you get an overview of the stock available,
                                    sales made, and purchase history. </li>
                                <li>Tax compliance for invoices with easy-to-find and add HSN/SAC codes for different
                                    products. </li>


                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Can I review the stock ledger of all my products?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Inventory management includes within it the capacity to track all your products in a
                                single ledger. Every product's stock will be tracked, so when an invoice is created,
                                inventory is reduced. If an order is canceled and/or refunded, the inventory will
                                reflect the same.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            How to look up HSN/SAC code and applicable tax rates for products?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                To look up the HSN/SAC code for a product, you can simply search by product name to find
                                the HSN/SAC code and the applicable taxes on it.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Can I add images for my products?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can add images for your different products by simply uploading an image file
                                when adding the product to your inventory list.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            How do I create an inventory list?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                In order to create an inventory list, there are a few attributes/values for each product
                                you need to include. They include:<br />

                                <li>Product name</li>
                                <li>Product description</li>
                                <li>Product image</li>
                                <li>HSN code</li>
                                <li>SAC code</li>
                                <li>Applicable tax (GST)</li>
                                <li>Category of product</li>
                                <li>Product vendor</li>
                                <li>SKU</li>
                                <li>Cost price</li>
                                <li>Sale price</li>
                                <li>Available stock</li>
                                <li>Minimum stock</li>
                                You can also add attributes/values for variables of a product like color, size, and more
                                to ensure a comprehensive product stock inventory at all times.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            How do I update my WooCommerce inventory on Swipez?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Good news you don’t have to. We’ll take care of it for you. Once you add an item to your
                                WooCommerce inventory it is automatically updated in your Swipez inventory list. So, any
                                invoices created for your WooCommerce store will have an up to date inventory data with
                                applicable taxes and more at its disposal. </div>
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
