@extends('home.master')
@section('title', 'Track expenses, create purchase orders and make payouts. Simplify purchases for your business,
improve expense reporting and save costs.')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Inventory Management Software</h1>
                <p class="lead mb-2">Free inventory management software designed to help your business effortlessly
                    manage inventory across all your products. Stay on top your product stock keeping at all times!</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Easy to use inventory management software" class="img-fluid" src="{!! asset('images/product/inventory-management-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Easy to use inventory management software" class="img-fluid" src="{!! asset('images/product/inventory-management-software.svg') !!}" />
                <h1 class="mt-2">Inventory Management Software</h1>
                <p class="lead mb-2">Free inventory management software designed to help your business effortlessly
                    manage inventory across all your products. Stay on top your product stock keeping at all times!</p>
                @include('home.product.web_register',['d_type' => "mob"])

            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center">Inventory management made <span class="highlighter">easy</span></h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Free inventory management software designed to
            help your business effortlessly
            manage inventory across all your products. Stay on top your product stock keeping at all times!</p>
    </center>
    @include('home.data_flow');
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2>Inventory tracking software to keep track of your sales activity</h2>
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
                <img alt="Simple inventory management software" class="img-fluid" src="{!! asset('images/product/inventory-management/features/easy-to-use-inventory-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Simple inventory management software" class="img-fluid" src="{!! asset('images/product/inventory-management/features/easy-to-use-inventory-software.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Easy to use</strong></h2>
                <p class="lead">Simple and integrated inventory management with your billing software. Reduce stocks or
                    product quantity as you raise invoices and increase stock as you create expenses.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Easy to use</strong></h2>
                <p class="lead">Simple and integrated inventory management with your billing software. Deplete stocks or
                    product quantity as you raise invoices and increase stock as you create expenses.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Managing vendors and payouts centrally" class="img-fluid" src="{!! asset('images/product/inventory-management/features/product-sku-mapping.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Managing vendors and payouts centrally" class="img-fluid" src="{!! asset('images/product/inventory-management/features/product-sku-mapping.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Product SKU mapping</strong></h2>
                <p class="lead">Add SKU against all your products with ease. Add unique stock keeping units to identify
                    and track your inventory, or stock.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Product SKU mapping</strong></h2>
                <p class="lead">Add SKU against all your products with ease. Add unique stock keeping units to identify
                    and track your inventory, or stock.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Split payments between parties" class="img-fluid" src="{!! asset('images/product/inventory-management/features/sale-and-purchase-history.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Split payments between parties" class="img-fluid" src="{!! asset('images/product/inventory-management/features/sale-and-purchase-history.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Real-time updates on sales and purchase history</strong></h2>
                <p class="lead">Automatically created ledger for all your products. View sale and purchase history for
                    each of your products along with the invoice and expense entries.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Real-time updates on sales and purchase history</strong></h2>
                <p class="lead">Automatically created ledger for all your products. View sale and purchase history for
                    each of your products along with the invoice and expense entries.</p>
            </div>
            <!-- end -->
        </div>


        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Dynamic inventory management" class="img-fluid" src="{!! asset('images/product/inventory-management/features/dynamic-inventory-management.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Dynamic inventory management" class="img-fluid" src="{!! asset('images/product/inventory-management/features/dynamic-inventory-management.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Dynamic Inventory</strong></h2>
                <p class="lead">Create & manage products with different variables like cost price, sale price, maximum retail price (MRP), expiry date, specifications & more. Real-time auto-updates for your dynamic and growing inventory.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Dynamic Inventory</strong></h2>
                <p class="lead">Create & manage products with different variables like cost price, sale price, maximum retail price (MRP), expiry date, specifications & more. Real-time auto-updates for your dynamic and growing inventory.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Inventory with product variations" class="img-fluid" src="{!! asset('images/product/inventory-management/features/product-variations.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Inventory with product variations" class="img-fluid" src="{!! asset('images/product/inventory-management/features/product-variations.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Product Variations</strong></h2>
                <p class="lead">Add and manage products with a range of attributes & variations, like color, size, and more. Create as many variations of different products to suit your needs.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Product Variations</strong></h2>
                <p class="lead">Add and manage products with a range of attributes & variations, like color, size, and more. Create as many variations of different products to suit your needs.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Manage bills and purchases" class="img-fluid" src="{!! asset('images/product/inventory-management/features/bill-and-purchases.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Manage bills and purchases" class="img-fluid" src="{!! asset('images/product/inventory-management/features/bill-and-purchases.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Manage invoices and purchases in a single place</strong></h2>
                <p class="lead">All your sale invoices and purchase entries on a single dashboard. Automate inventory
                    addition or depletion as you raise expenses & create sales invoices.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Manage invoices and purchases in a single place</strong></h2>
                <p class="lead">All your sale invoices and purchase entries on a single dashboard. Automate inventory
                    addition or depletion as you raise expenses & create sales invoices.
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Make payouts in bulk" class="img-fluid" src="{!! asset('images/product/inventory-management/features/hsn-sac-lookup.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Make payouts in bulk" class="img-fluid" src="{!! asset('images/product/inventory-management/features/hsn-sac-lookup.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Lookup HSN code or SAC code</strong></h2>
                <p class="lead">Search for products HSN code and the associated GST percentage with ease. Search using
                    HSN code or SAC code using product or service name or code.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Lookup HSN code or SAC code</strong></h2>
                <p class="lead">Search for products HSN code and the associated GST percentage with ease. Search using
                    HSN code or SAC code using product or service name or code.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Inventory reporting" class="img-fluid" src="{!! asset('images/product/inventory-management/features/inventoy-reporting.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Inventory reporting" class="img-fluid" src="{!! asset('images/product/inventory-management/features/inventoy-reporting.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Track inventory reports</strong></h2>
                <p class="lead">Get a snapshot view of your items in stock, total stock available, stock status, total stock value & more on a single dashboard! Review your inventory for a week, a month, 6 months, or more as per your requirements. Track your sales for your chosen time period with real-time updates.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Track inventory reports</strong></h2>
                <p class="lead">Get a snapshot view of your items in stock, total stock available, stock status, total stock value & more on a single dashboard! Review your inventory for a week, a month, 6 months, or more as per your requirements. Track your sales for your chosen time period with real-time updates.
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
                    free. No payment required.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
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
                <h2 class="display-4 text-white">Manage your product inventory</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Accurate stock keeping</h3>
                    <p>Order confidently and save money by using Swipez’s inventory management software. Swipez
                        gives your business complete control over your product stocks.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cogs" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M512.1 191l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0L552 6.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zm-10.5-58.8c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.7-82.4 14.3-52.8 52.8zM386.3 286.1l33.7 16.8c10.1 5.8 14.5 18.1 10.5 29.1-8.9 24.2-26.4 46.4-42.6 65.8-7.4 8.9-20.2 11.1-30.3 5.3l-29.1-16.8c-16 13.7-34.6 24.6-54.9 31.7v33.6c0 11.6-8.3 21.6-19.7 23.6-24.6 4.2-50.4 4.4-75.9 0-11.5-2-20-11.9-20-23.6V418c-20.3-7.2-38.9-18-54.9-31.7L74 403c-10 5.8-22.9 3.6-30.3-5.3-16.2-19.4-33.3-41.6-42.2-65.7-4-10.9.4-23.2 10.5-29.1l33.3-16.8c-3.9-20.9-3.9-42.4 0-63.4L12 205.8c-10.1-5.8-14.6-18.1-10.5-29 8.9-24.2 26-46.4 42.2-65.8 7.4-8.9 20.2-11.1 30.3-5.3l29.1 16.8c16-13.7 34.6-24.6 54.9-31.7V57.1c0-11.5 8.2-21.5 19.6-23.5 24.6-4.2 50.5-4.4 76-.1 11.5 2 20 11.9 20 23.6v33.6c20.3 7.2 38.9 18 54.9 31.7l29.1-16.8c10-5.8 22.9-3.6 30.3 5.3 16.2 19.4 33.2 41.6 42.1 65.8 4 10.9.1 23.2-10 29.1l-33.7 16.8c3.9 21 3.9 42.5 0 63.5zm-117.6 21.1c59.2-77-28.7-164.9-105.7-105.7-59.2 77 28.7 164.9 105.7 105.7zm243.4 182.7l-8.2 14.3c-3 5.3-9.4 7.5-15.1 5.4-11.8-4.4-22.6-10.7-32.1-18.6-4.6-3.8-5.8-10.5-2.8-15.7l8.2-14.3c-6.9-8-12.3-17.3-15.9-27.4h-16.5c-6 0-11.2-4.3-12.2-10.3-2-12-2.1-24.6 0-37.1 1-6 6.2-10.4 12.2-10.4h16.5c3.6-10.1 9-19.4 15.9-27.4l-8.2-14.3c-3-5.2-1.9-11.9 2.8-15.7 9.5-7.9 20.4-14.2 32.1-18.6 5.7-2.1 12.1.1 15.1 5.4l8.2 14.3c10.5-1.9 21.2-1.9 31.7 0l8.2-14.3c3-5.3 9.4-7.5 15.1-5.4 11.8 4.4 22.6 10.7 32.1 18.6 4.6 3.8 5.8 10.5 2.8 15.7l-8.2 14.3c6.9 8 12.3 17.3 15.9 27.4h16.5c6 0 11.2 4.3 12.2 10.3 2 12 2.1 24.6 0 37.1-1 6-6.2 10.4-12.2 10.4h-16.5c-3.6 10.1-9 19.4-15.9 27.4l8.2 14.3c3 5.2 1.9 11.9-2.8 15.7-9.5 7.9-20.4 14.2-32.1 18.6-5.7 2.1-12.1-.1-15.1-5.4l-8.2-14.3c-10.4 1.9-21.2 1.9-31.7 0zM501.6 431c38.5 29.6 82.4-14.3 52.8-52.8-38.5-29.6-82.4 14.3-52.8 52.8z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Improve your stock keeping</h3>
                    <p>Improve speed and efficiency, remove error or fraud, and reduce time consuming
                        manual tasks. Simple and easy to use across your entire organization.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Manage all your products</h3>
                    <p>Full control of all your products anywhere, anytime, from any device. See where
                        every Rupee goes and monitor the bottom line impact with real-time budgeting.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="thumbs-up" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Comprehensive product variations</h3>
                    <p>Add and manage products with distinct variations like color, size, price & more. Curate and manage your evolving inventory from a single dashboard.</p>
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
                            <img alt="Inventory management software clients picture" class="img-fluid rounded" src="{!! asset('images/home/goseeko.png') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Keeping a track of our inventory is now so much simpler. Every sale invoice we raise
                                reduces our inventory and every expense entry we make adds inventory automatically."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Chandrabhanu</strong>
                            </p>
                            <p>
                                <em>Founder, GoSeeko</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Inventory management software clients picture" class="img-fluid rounded" src="{!! asset('images/product/payouts/shah-infinite.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now we manage stocks of all our products with ease. We know when to place a new order
                                and
                                how much stock we are holding with us at any given point of time."</p>
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
                <h3>Power your business with a comprehensive inventory management solution</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg bg-secondary text-white" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-secondary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
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
                        <p class="lead">Looking for more information? Here are some things we're commonly asked</p>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h3 class="h4">What are the features that make an Inventory Management Software perfect?
                            </h3>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Key features of Inventory Management Software
                                <ul>
                                    <li>Easy to create and update products</li>
                                    <li>Attach HSN code and SAC code and get associated taxation</li>
                                    <li>Automated reduction of product stock quantity via an <a href="{{ route('home.billing') }}">integrated billing software</a></li>
                                    <li>Automated increase of product stock quantity via an <a href="{{ route('home.expenses') }}">integrated expense management
                                            software</a></li>
                                    <li>Ledger of all your stock purchases and sales</li>
                                    <li>Ability to store cost price, sale price and images of for your products</li>
                                    <li>Access anywhere, anytime</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h3 class="h4">Is the inventory management software integrated with billing software?</h3>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez Billing software is integrated with inventory management software. So when
                                an invoice is raised within Swipez billing software the inventory is reduced
                                automatically. Similarly when an expense / purchase entry is made the inventory of your
                                product is increased automatically.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h3 class="h4">Can I see a stock ledger of all my products?</h3>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Inventory management software comes built in with the ability to keep a ledger of all
                                your products. Every products stock will be tracked i.e. you will see the related
                                invoice entry when inventory is reduced and an expense or purchase entry when the
                                product stock is added.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h3 class="h4">How to get HSN code and GST tax rate for my product?</h3>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Swipez inventory management software has an HSN search facility. You can search using
                                either product name or HSN code to get the HSN code and related GST tax value against
                                each of products.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h3 class="h4">We also deal with services along with products, can I still use the inventory
                                management software for tracking?</h3>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes. You can create either a product or a service in your inventory. In case of services
                                there is a SAC lookup which will help you to get the related SAC code and GST tax
                                percentage for your service.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h3 class="h4">Is there a way to store product images?</h3>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can upload images for each of your products in Swipez inventory management
                                software. In the product creation screen you will see a user friendly drag and drop
                                facility to upload your product images.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h3 class="h4">How can I record an expense and increase my stock inventory?</h3>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Once you create a purchase order and make a payment for it, you can easily convert it to
                                an expense and record your payment details. Or you simply create an expense / purchase
                                entry for your products. You expense entry will result in the stock inventory getting
                                added automatically as per the quantity data in your purchase.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h3 class="h4">How do you create an inventory list?</h3>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                To create an inventory of your products you need to store a set of values for each
                                product. Typical values you need to store to have a complete inventory list are:
                                <ul>
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
                                    <li>Minimum stock</li>
                                </ul>
                                All of these and more are supported in Swipez Inventory management software. This
                                enables you to have a complete view of your products stock inventory at all times.
                                Create & manage your evolving inventory with a range of attributes & variations for your Amazon, Flipkart, and/or WooCommerce stores.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h3 class="h4">How do you manage inventory?</h3>
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Depending on the number of products and stock you manage the answer to this question can
                                change. But the best and the most easiest way is to use an existing software like Swipez
                                Inventory Management. This will help you to manage inventory irrespective of how many
                                products you manage. It will also enable you to keep a track of all your product stocks
                                at any point in time.<br /><br />

                                To manage inventory you need to reduce your stock after every sale and increase stocks
                                after every stock purchase. Keeping a tab of this manually can be error prone and very
                                time consuming. Thus using an inventory management software to manage your stock
                                inventory is the best way forward.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h3 class="h4">Can I use Excel for inventory management?</h3>
                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                MS Excel is a great starting point to manage all forms of data. Its perfect to manage
                                small amounts of data which does not change very frequently. As you can imagine,
                                changing stock values as you buy or sell your products can be difficult to manage. Excel
                                can be used to manage a small stock of inventory which does not change (i.e. gets bought
                                or sold) very frequently. The moment you want to have a real time view of your inventory
                                as you sell or buy products you need to consider an inventory management software like
                                Swipez for your needs.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEleven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h3 class="h4">What kind of software is inventory management?</h3>
                        </div>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Inventory management is a stock keeping software which enables you to stay on top of
                                your companies stock at all times. A good inventory management software has the
                                following features:
                                <ul>
                                    <li>Simple creation and maintenance products</li>
                                    <li>Search HSN code and SAC code with taxation for all your products</li>
                                    <li>Automatic reduction of product stock via an integrated billing software</li>
                                    <li>Automatic increase of product stock via an integrated expense management
                                        software</li>
                                    <li>Ledger of all your purchases and sales</li>
                                    <li>Store cost price and sale price of all your products</li>
                                </ul>
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
    var titles1 = ["Inventory management"];
</script>

@endsection
