@extends('home.master')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-6 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <!-- end -->
            <div class="d-none d-lg-block col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0">
                <img alt="Online payment collections made easy" width="640" height="360" class="img-fluid"
                    src="{!! asset('images/home/swipez-home-hero.svg') !!}" />
            </div>
            <div class="d-none d-lg-block col-12 col-md-12 col-lg-6 col-xl-6">
                <h1 class="pb-3">Digitize your <span class="txt-rotate" data-period="2000"
                        data-rotate='[" invoicing.", " collections.", " expenses.", " payouts.", " GST filing.", " operations.", " business!", " billing."]'>
                        billing.</span>
                    <!-- span
                        style="color:white;background-color:#18aebf;display:inline-block;clip-path:polygon(6% 12%,95% 8%,98% 95%,0 100%);padding:10px;">now</span -->
                </h1>
                <h5 class="pb-3 gray-700">The easiest way for businesses to collect payments faster, organize expenses
                    and automate GST filing.
                </h5>
                <!--p class="lead mb-2">Get your business the access to technology large companies use.</p-->
                @include('home.product.web_register',['d_type' => "web"])


            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-lg-0"><img
                    alt="Online payment collections made easy" class="img-fluid"
                    src="{!! asset('images/home/swipez-home-hero.svg') !!}" width="640" height="360" />
            </div>
            <div class="d-lg-none col-12 col-md-12 col-lg-6 col-xl-6 text-center">
                <h1 class="pb-3">Digitize your <br /><span class="txt-rotate" data-period="2000"
                        data-rotate='[" invoicing.", " collections.", " expenses.", " payouts.", " GST filing.", " operations.", " business!", " billing."]'>
                        billing.</span>
                    <!-- span
                        style="color:white;background-color:#18aebf;display:inline-block;clip-path:polygon(6% 12%,95% 8%,98% 95%,0 100%);padding:10px;">now</span -->
                </h1>
                <h5 class="pb-3">The easiest way for businesses to collect payments faster, organize expenses and
                    automate GST filing.
                </h5>
                @include('home.product.web_register',['d_type' => "mob"])

            </div>


        </div>
    </div>
    <script>
        var TxtRotate = function(el, toRotate, period) {
      this.toRotate = toRotate;
      this.el = el;
      this.loopNum = 0;
      this.period = parseInt(period, 10) || 2000;
      this.txt = '';
      this.tick();
      this.isDeleting = false;
    };

    TxtRotate.prototype.tick = function() {
      var i = this.loopNum % this.toRotate.length;
      var fullTxt = this.toRotate[i];

      if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
      } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
      }

      this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

      var that = this;
      var delta = 300 - Math.random() * 100;

      if (this.isDeleting) { delta /= 2; }

      if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
      } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
      }

      setTimeout(function() {
        that.tick();
      }, delta);
    };

    window.onload = function() {
      var elements = document.getElementsByClassName('txt-rotate');
      for (var i=0; i<elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-rotate');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
          new TxtRotate(elements[i], JSON.parse(toRotate), period);
        }
      }
      // INJECT CSS
      var css = document.createElement("style");
      css.type = "text/css";
      css.innerHTML = ".txt-rotate > .wrap { border-right: 0.04em solid #f8f5f2 }";
      document.body.appendChild(css);
    };
    </script>
</section>

<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center">More than just a <span class="highlighter">free</span> Billing Software</h1>
    <h2 class="pb-3 lead gray-700 text-center">A billing product that automates key areas of your business - in no time!
        </h2>
        @include('home.data_flow');
</section>

<section class="jumbotron py-5" id="clients">
    <div class="container">
        <h2 class="text-center pb-5">Trusted by Great Companies</h2>
        <div class="row">
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}"
                    width="210" height="90" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Reliance Mutual Fund" class="img-grayscale"
                    src="{!! asset('images/home/reliance-mutual-fund.png') !!}" width="210" height="90" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                    src="{!! asset('images/home/global-insurance.png') !!}" width="210" height="90" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                    src="{!! asset('images/home/malaka-spice.png') !!}" width="210" height="90" />
            </div>
            <div class="col d-none d-lg-block">
                <img alt="Swipez client IRIS GST" class="img-grayscale" src="{!! asset('images/home/iris-gst.png') !!}"
                    width="144" height="108" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Amazon" class="img-grayscale" src="{!! asset('images/home/amazon.png') !!}"
                    width="210" height="90" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Reliance mutual fund" class="img-grayscale"
                    src="{!! asset('images/home/reliance-mutual-fund.png') !!}" width="210" height="90" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Global Insurance" class="img-grayscale"
                    src="{!! asset('images/home/global-insurance.png') !!}" width="210" height="90" />
            </div>
            <div class="col-6 d-lg-none">
                <img alt="Swipez client Malaka Spice" class="img-grayscale"
                    src="{!! asset('images/home/malaka-spice.png') !!}" width="210" height="90" />
            </div>
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez client IRIS GST" class="img-grayscale" src="{!! asset('images/home/iris-gst.png') !!}"
                    width="144" height="108" />
            </div>
        </div>
    </div>
</section>




<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center pb-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Online payment collections made easy</h2>
                <p class="lead">Swipez products help your business collect payments faster.<br />
                    Our products automate your business operations and are built to scale as per the needs of your
                    business.</p>
            </div>
            <p class="lead text-left"><span class="badge badge-primary">Product List</span></p>
        </div>
        <div class="row text-left align-items-center pt-3 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5">
                <a href="{{ route('home.billing') }}">
                    <img alt="Easy to use billing software for small or large teams" class="img-fluid"
                        src="{!! asset('images/home/billing-software.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.billing') }}">
                    <img alt="Easy to use billing software for small or large teams" class="img-fluid"
                        src="{!! asset('images/home/billing-software.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong><a href="{{ route('home.billing') }}">Billing software</a></strong></h2>
                <p class="lead">Streamline business operations by automating your invoicing, payment collections,
                    bulk pay outs, GST filing & customer data management.<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.billing') }}">Billing software</a></strong></h2>
                <p class="lead">Streamline business operations by automating your invoicing, payment collections,
                    bulk pay outs, GST filing & customer data management.<br /><a
                        href="{{ route('home.billing') }}">Online billing software</a></p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5 m-md-auto order-md-5">
                <a href="{{ route('home.expenses') }}">
                    <img alt="Manage your company expenses" class="img-fluid"
                        src="{!! asset('/images/product/expense-management-software.svg') !!}" width="640"
                        height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.expenses') }}">
                    <img alt="Manage your company expenses" class="img-fluid"
                        src="{!! asset('images/product/expense-management-software.svg') !!}" width="640"
                        height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong><a href="{{ route('home.expenses') }}">Expense management software</a></strong></h2>
                <p class="lead">Manage all your company expenses in one dashboard. Organize your all you company
                    payments by managing your vendors, franchises and all other beneficiaries. Stay on top of expenses
                    at all times!<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.expenses') }}">Expense management software</a></strong></h2>
                <p class="lead">Manage all your company expenses in one dashboard. Organize your all you company
                    payments by managing your vendors, franchises and all other beneficiaries. Stay on top of expenses
                    at all times!<br />
                    <a href="{{ route('home.expenses') }}">Expense management software</a>
                </p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-3 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5">
                <a href="{{ route('home.inventorymanagement') }}">
                    <img alt="Manage all your product inventory" class="img-fluid"
                        src="{!! asset('images/product/inventory-management-software.svg') !!}" width="640"
                        height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.inventorymanagement') }}">
                    <img alt="Manage all your product inventory" class="img-fluid"
                        src="{!! asset('images/product/inventory-management-software.svg') !!}" width="640"
                        height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong><a href="{{ route('home.inventorymanagement') }}">Inventory management software</a></strong>
                </h2>
                <p class="lead">Simple and easy to use inventory management software to manage stocks of all your
                    products. Stay on top available stock quantity and new stock automatically thanks to our billing
                    software that is completely integrated with inventory management.<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.inventorymanagement') }}">Inventory management software</a></strong>
                </h2>
                <p class="lead">Simple and easy to use inventory management software to manage stocks of all your
                    products. Stay on top available stock quantity and new stock automatically thanks to our billing
                    software that is completely integrated with inventory management.<br /><a
                        href="{{ route('home.inventorymanagement') }}">Inventory management software</a></p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5 m-md-auto order-md-5">
                <a href="{{ route('home.payouts') }}">
                    <img alt="Manage expenses and payouts for your business" class="img-fluid"
                        src="{!! asset('images/home/payouts.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.payouts') }}">
                    <img alt="Manage expenses and payouts for your business" class="img-fluid"
                        src="{!! asset('images/home/payouts.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong><a href="{{ route('home.payouts') }}">Payouts software</a></strong></h2>
                <p class="lead">Organize payments from your bank account. Get a clear view of all payments made to your
                    business contacts. Split payments between multiple parties. Single, bulk (excel) or APIs based
                    payouts supported.<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.payouts') }}">Payouts software</a></strong></h2>
                <p class="lead">Organize payments from your bank account. Get a clear view of all payments made to your
                    business contacts. Split payments between multiple parties. Single, bulk (excel) or APIs based
                    payouts supported.<br /><a href="{{ route('home.payouts') }}">Expense and payouts software</a></p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5">
                <a href="{{ route('home.gstfiling') }}">
                    <img alt="GST filing made simple and easy to understand" class="img-fluid"
                        src="{!! asset('images/home/gst-filing.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.gstfiling') }}">
                    <img alt="GST filing made simple and easy to understand" class="img-fluid"
                        src="{!! asset('images/home/gst-filing.svg') !!}" width="640" height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong><a href="{{ route('home.gstfiling') }}">GST filing software</a></strong></h2>
                <p class="lead">Easy to use and automated GST filing for your business. Create or upload GST invoices,
                    know your tax liability and file your GST returns from one dashboard.<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.gstfiling') }}">GST filing software</a></strong></h2>
                <p class="lead">Easy to use and automated GST filing for your business. Create or upload GST invoices,
                    know your tax liability and file your GST returns from one dashboard.<br /><a
                        href="{{ route('home.gstfiling') }}">GST filing software</a></p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="d-none d-lg-block col-4 col-md-5 m-md-auto order-md-5">
                <a href="{{ route('home.event') }}">
                    <img alt="GST reconciliaton sotware"
                        class="img-fluid" src="{!! asset('images/product/gst-reconciliation/GST-reconciliation-software.png') !!}"
                        width="640" height="360" />
                </a>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <a href="{{ route('home.event') }}">
                    <img alt="GST reconciliaton sotware"
                        class="img-fluid" src="{!! asset('images/product/gst-reconciliation/GST-reconciliation-software.png') !!}"
                        width="640" height="360" />
                </a>
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong><a href="{{ route('home.gstrecon') }}">GST Reconciliation Software</a></strong></h2>
                <p class="lead">Error-free Input Tax Credit with quick & easy GST 2A & 3B reconciliation. Identify and reconcile differences between your expense data and your vendors’ GST portal data in a matter of seconds.<br /></p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h2><strong><a href="{{ route('home.gstrecon') }}">GST Reconciliation Software</a></strong></h2>
                <p class="lead">Error-free Input Tax Credit with quick & easy GST 2A & 3B reconciliation. Identify and reconcile differences between your expense data and your vendors’ GST portal data in a matter of seconds.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary text-white py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Forever free plans available with all Swipez products</h3>
                <br /><a class="btn btn-lg btn-tertiary text-white"
                    href="{{ config('app.APP_URL') }}merchant/register">Sign
                    Up Free</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5 pt-0" id="features">
    <div class="container">
        <div class="row justify-content-center pb-3 py-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Payment collection software for your industry</h2>
            </div>
        </div>
        <div class="row row-eq-height mb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="tv"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="M592 0H48A48 48 0 0 0 0 48v320a48 48 0 0 0 48 48h240v32H112a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16H352v-32h240a48 48 0 0 0 48-48V48a48 48 0 0 0-48-48zm-16 352H64V64h512z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.cable') }}">
                            <h3 class="font-weight-bold">Cable operators</h3>
                        </a>
                    </center>
                    <p>Cable companies using our billing software are able to automate their monthly bill presentments
                        and provide their customers options to
                        choose their channels / packages.<br />
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bullhorn"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 576 512">
                                <path fill="currentColor"
                                    d="M576 240c0-23.63-12.95-44.04-32-55.12V32.01C544 23.26 537.02 0 512 0c-7.12 0-14.19 2.38-19.98 7.02l-85.03 68.03C364.28 109.19 310.66 128 256 128H64c-35.35 0-64 28.65-64 64v96c0 35.35 28.65 64 64 64h33.7c-1.39 10.48-2.18 21.14-2.18 32 0 39.77 9.26 77.35 25.56 110.94 5.19 10.69 16.52 17.06 28.4 17.06h74.28c26.05 0 41.69-29.84 25.9-50.56-16.4-21.52-26.15-48.36-26.15-77.44 0-11.11 1.62-21.79 4.41-32H256c54.66 0 108.28 18.81 150.98 52.95l85.03 68.03a32.023 32.023 0 0 0 19.98 7.02c24.92 0 32-22.78 32-32V295.13C563.05 284.04 576 263.63 576 240zm-96 141.42l-33.05-26.44C392.95 311.78 325.12 288 256 288v-96c69.12 0 136.95-23.78 190.95-66.98L480 98.58v282.84z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.entertainmentevent') }}">
                            <h3 class="font-weight-bold">Event Management</h3>
                        </a>
                    </center>
                    <p>Event organizers implemented online event registrations and ticketing on Swipez to host their
                        biggest events via our event registration & online ticketing product.<br />
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wifi"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="M634.91 154.88C457.74-8.99 182.19-8.93 5.09 154.88c-6.66 6.16-6.79 16.59-.35 22.98l34.24 33.97c6.14 6.1 16.02 6.23 22.4.38 145.92-133.68 371.3-133.71 517.25 0 6.38 5.85 16.26 5.71 22.4-.38l34.24-33.97c6.43-6.39 6.3-16.82-.36-22.98zM320 352c-35.35 0-64 28.65-64 64s28.65 64 64 64 64-28.65 64-64-28.65-64-64-64zm202.67-83.59c-115.26-101.93-290.21-101.82-405.34 0-6.9 6.1-7.12 16.69-.57 23.15l34.44 33.99c6 5.92 15.66 6.32 22.05.8 83.95-72.57 209.74-72.41 293.49 0 6.39 5.52 16.05 5.13 22.05-.8l34.44-33.99c6.56-6.46 6.33-17.06-.56-23.15z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.isp') }}">
                            <h3 class="font-weight-bold">ISP</h3>
                        </a>
                    </center>
                    <p>Internet service providers large and small, are getting paid faster by adopting our billing
                        software. They have transformed their payment collection into a hassle-free process.<br /></p>
                    <p></p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height mb-5">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="shipping-fast"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H112C85.5 0 64 21.5 64 48v48H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h272c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h208c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H64v128c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.travelntour') }}">
                            <h3 class="font-weight-bold">Travel and tour operators</h3>
                        </a>
                    </center>
                    <p>Travel and tour operator save 100+ man hours by automating their billing process via our billing
                        software.<br /></p>
                    <p></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="city"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="M616 192H480V24c0-13.26-10.74-24-24-24H312c-13.26 0-24 10.74-24 24v72h-64V16c0-8.84-7.16-16-16-16h-16c-8.84 0-16 7.16-16 16v80h-64V16c0-8.84-7.16-16-16-16H80c-8.84 0-16 7.16-16 16v80H24c-13.26 0-24 10.74-24 24v360c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V216c0-13.26-10.75-24-24-24zM128 404c0 6.63-5.37 12-12 12H76c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12H76c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12H76c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm128 192c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm160 96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12V76c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm160 288c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40zm0-96c0 6.63-5.37 12-12 12h-40c-6.63 0-12-5.37-12-12v-40c0-6.63 5.37-12 12-12h40c6.63 0 12 5.37 12 12v40z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.societybooking') }}">
                            <h3 class="font-weight-bold">Housing society</h3>
                        </a>
                    </center>
                    <p>Housing societies organise facility bookings and setup membership profiles
                        across tenants and owners using our Booking Calendar.<br /></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="running"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 416 512">
                                <path fill="currentColor"
                                    d="M272 96c26.51 0 48-21.49 48-48S298.51 0 272 0s-48 21.49-48 48 21.49 48 48 48zM113.69 317.47l-14.8 34.52H32c-17.67 0-32 14.33-32 32s14.33 32 32 32h77.45c19.25 0 36.58-11.44 44.11-29.09l8.79-20.52-10.67-6.3c-17.32-10.23-30.06-25.37-37.99-42.61zM384 223.99h-44.03l-26.06-53.25c-12.5-25.55-35.45-44.23-61.78-50.94l-71.08-21.14c-28.3-6.8-57.77-.55-80.84 17.14l-39.67 30.41c-14.03 10.75-16.69 30.83-5.92 44.86s30.84 16.66 44.86 5.92l39.69-30.41c7.67-5.89 17.44-8 25.27-6.14l14.7 4.37-37.46 87.39c-12.62 29.48-1.31 64.01 26.3 80.31l84.98 50.17-27.47 87.73c-5.28 16.86 4.11 34.81 20.97 40.09 3.19 1 6.41 1.48 9.58 1.48 13.61 0 26.23-8.77 30.52-22.45l31.64-101.06c5.91-20.77-2.89-43.08-21.64-54.39l-61.24-36.14 31.31-78.28 20.27 41.43c8 16.34 24.92 26.89 43.11 26.89H384c17.67 0 32-14.33 32-32s-14.33-31.99-32-31.99z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.bookingfitness') }}">
                            <h3 class="font-weight-bold">Health & Fitness</h3>
                        </a>
                    </center>
                    <p>Fitness pros organise time slot bookings for their instructors and venues, while collect
                        recurring
                        membership fees with ease.<br /></p>
                </div>
            </div>
        </div>
        <div class="row row-eq-height">
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="graduation-cap"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="M622.34 153.2L343.4 67.5c-15.2-4.67-31.6-4.67-46.79 0L17.66 153.2c-23.54 7.23-23.54 38.36 0 45.59l48.63 14.94c-10.67 13.19-17.23 29.28-17.88 46.9C38.78 266.15 32 276.11 32 288c0 10.78 5.68 19.85 13.86 25.65L20.33 428.53C18.11 438.52 25.71 448 35.94 448h56.11c10.24 0 17.84-9.48 15.62-19.47L82.14 313.65C90.32 307.85 96 298.78 96 288c0-11.57-6.47-21.25-15.66-26.87.76-15.02 8.44-28.3 20.69-36.72L296.6 284.5c9.06 2.78 26.44 6.25 46.79 0l278.95-85.7c23.55-7.24 23.55-38.36 0-45.6zM352.79 315.09c-28.53 8.76-52.84 3.92-65.59 0l-145.02-44.55L128 384c0 35.35 85.96 64 192 64s192-28.65 192-64l-14.18-113.47-145.03 44.56z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.education') }}">
                            <h3 class="font-weight-bold">Education</h3>
                        </a>
                    </center>
                    <p>Education institutions have organized their fee collections and generated revenue by offering
                        their
                        venues for events.<br />
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="store"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 616 512">
                                <path fill="currentColor"
                                    d="M602 118.6L537.1 15C531.3 5.7 521 0 510 0H106C95 0 84.7 5.7 78.9 15L14 118.6c-33.5 53.5-3.8 127.9 58.8 136.4 4.5.6 9.1.9 13.7.9 29.6 0 55.8-13 73.8-33.1 18 20.1 44.3 33.1 73.8 33.1 29.6 0 55.8-13 73.8-33.1 18 20.1 44.3 33.1 73.8 33.1 29.6 0 55.8-13 73.8-33.1 18.1 20.1 44.3 33.1 73.8 33.1 4.7 0 9.2-.3 13.7-.9 62.8-8.4 92.6-82.8 59-136.4zM529.5 288c-10 0-19.9-1.5-29.5-3.8V384H116v-99.8c-9.6 2.2-19.5 3.8-29.5 3.8-6 0-12.1-.4-18-1.2-5.6-.8-11.1-2.1-16.4-3.6V480c0 17.7 14.3 32 32 32h448c17.7 0 32-14.3 32-32V283.2c-5.4 1.6-10.8 2.9-16.4 3.6-6.1.8-12.1 1.2-18.2 1.2z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.franchise') }}">
                            <h3 class="font-weight-bold">Franchise industry</h3>
                        </a>
                    </center>
                    <p>Franchise based brands have organized billing across all their franchises, with automated
                        billing, GST filing and bulk pay outs.<br />
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light-blue p-4 h-100">
                    <div class="mb-4">
                        <center>
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="utensils"
                                class="h-16 text-secondary" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 416 512">
                                <path fill="currentColor"
                                    d="M207.9 15.2c.8 4.7 16.1 94.5 16.1 128.8 0 52.3-27.8 89.6-68.9 104.6L168 486.7c.7 13.7-10.2 25.3-24 25.3H80c-13.7 0-24.7-11.5-24-25.3l12.9-238.1C27.7 233.6 0 196.2 0 144 0 109.6 15.3 19.9 16.1 15.2 19.3-5.1 61.4-5.4 64 16.3v141.2c1.3 3.4 15.1 3.2 16 0 1.4-25.3 7.9-139.2 8-141.8 3.3-20.8 44.7-20.8 47.9 0 .2 2.7 6.6 116.5 8 141.8.9 3.2 14.8 3.4 16 0V16.3c2.6-21.6 44.8-21.4 48-1.1zm119.2 285.7l-15 185.1c-1.2 14 9.9 26 23.9 26h56c13.3 0 24-10.7 24-24V24c0-13.2-10.7-24-24-24-82.5 0-221.4 178.5-64.9 300.9z">
                                </path>
                            </svg>
                        </center>
                    </div>
                    <center>
                        <a href="{{ route('home.industry.hospitalityevent') }}">
                            <h3 class="font-weight-bold">Hospitality industry</h3>
                        </a>
                    </center>
                    <p>Host food events, attract new customers and walk-ins. Hold special menu tasting events or regular
                        events packaged with food.<br />
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5" id="features">
    <div class="container">
        <div class="row justify-content-center pb-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Business operations made easy</h2>
                <p class="lead">Swipez products help you make more time for the work that matters.</p>
            </div>
        </div>
        <div class="row text-left pt-5 pb-md-5">
            <div class="d-none d-lg-block col-4 m-md-auto order-md-5">
                <img alt="Payment collections products built for scale" class="img-fluid"
                    src="{!! asset('static/images/built-for-scale.svg') !!}" width="640" height="360" />
            </div>
            <div class="col-4 d-none d-lg-block">
                <h3>Easy to implement, use, and scale</h3>
                <p>Swipez products work right out of the box, with powerful features and functions to help any business
                    improve their business operations and online payment collections. Our platform is designed to grow
                    and scale, so it works for
                    companies of any size—from start-up to enterprise.</p>
            </div>
            <div class="col-4 d-none d-lg-block">
                <h3>Be on top of your operations at all times</h3>
                <p>Swipez products offer everything you need for frictionless business operations. Our products work
                    together to improve your business efficiency and help revenue collections flow seamlessly across
                    channels.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <img alt="Payment collections products built for scale" class="img-fluid"
                    src="{!! asset('static/images/built-for-scale.svg') !!}" width="640" height="360" />
            </div>
            <!-- end -->
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h3>Easy to implement, use, and scale</h3>
                <p>Swipez products work right out of the box, with powerful features and functions to help any business
                    improve their business operations and online payment collections. Our platform is designed to grow
                    and scale, so it works for
                    companies of any size—from start-up to enterprise.</p>
            </div>
            <div class="col-12 d-lg-none text-center">
                <h3>Be on top of your operations at all times</h3>
                <p>Swipez products offer everything you need for frictionless business operations. Our products work
                    together to improve your business efficiency and help revenue collections flow seamlessly across
                    channels.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left pt-3 pb-md-5">
            <div class="d-none d-lg-block col-4">
                <img alt="Smart CRM for customer data cleanup" class="img-fluid"
                    src="{!! asset('static/images/work-smarter.svg') !!}" width="640" height="360" />
            </div>
            <div class="col-4 m-md-auto d-none d-lg-block">
                <h3>Work smarter, not harder</h3>
                <p>Reporting and analytics are an integral part of any organization. Swipez products automate your
                    online payment collections and provide real-time view of your payment collections. Get insights into
                    customer payments and collection status at your finger tips.
                </p>
            </div>
            <div class="col-4 m-md-auto d-none d-lg-block">
                <h3>Perfect customer data at all times</h3>
                <p>Swipez is built on our own flexible CRM platform. As your start collecting payments online, our smart
                    CRM platform cleans up your customer records automatically, giving you perfect customer contact
                    information at all times.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="d-lg-none col-12 pb-4">
                <img alt="Smart CRM for customer data cleanup" class="img-fluid"
                    src="{!! asset('static/images/work-smarter.svg') !!}" width="640" height="360" />
            </div>
            <!-- end -->
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <h3>Work smarter, not harder</h3>
                <p>Reporting and analytics are an integral part of any organization. Swipez products automate your
                    online payment collections and provide real-time view of your payment collections. Get insights into
                    customer payments and collection status at your finger tips.
                </p>
            </div>
            <div class="col-12 d-lg-none text-center">
                <h3>Perfect customer data at all times</h3>
                <p>Swipez is built on our own flexible CRM platform. As your start collecting payments online, our smart
                    CRM platform cleans up your customer records automatically, giving you perfect customer contact
                    information at all times.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary text-white" id="cta">
    <div class="container">
        <div class="row gap-y align-items-center">
            <div class="col-md-12 text-center mx-auto">
                <h3 class="text-center">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.</h3><br /><a
                    class="btn btn-lg btn-tertiary text-white" href="{{ config('app.APP_URL') }}merchant/register">Sign
                    Up Free</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="clients">
    <div class="container">
        <h2 class="text-center pb-5">Associated with</h2>
        <div class="text-center row">
            <div class="col-md-3">
                <a href="https://fintech.maharashtra.gov.in/" target="_blank">
                    <img alt="Mumbai Fintech logo" class="img-grayscale"
                        src="{!! asset('images/home/mumbai-fintech.png') !!}" width="200" height="100" />
                </a>
            </div>
            <div class="col-md-3">
                <a href="https://www.jiogennext.com/" target="_blank">
                    <img alt="Jio GenNext logo" class="img-grayscale" src="{!! asset('images/home/jio-gennext.png') !!}"
                        width="200" height="100" />
                </a>
            </div>
            <div class="col-md-3">
                <img alt="Nasscom emerge 50" class="img-grayscale"
                    src="{!! asset('images/home/nasscom-emerge-50-awards.png') !!}" width="72" height="101" />
            </div>
            <div class="col-md-3">
                <a href="https://www.irisgst.com" target="_blank">
                    <img alt="IRIS GST logo" class="img-grayscale" src="{!! asset('images/home/iris-gst.png') !!}"
                        width="136" height="101" />
                </a>
            </div>
        </div>
    </div>
</section>

<script src="//cdn.headwayapp.co/widget.js"></script>

<script>
    // @see https://docs.headwayapp.co/widget for more configuration options.
    var HW_UnseenCount = 0;
    var config = {
        selector: ".badgeCont",
        trigger: ".toggleWidget",
        account: "JrXKbx",
        translations: {
            title: "New Features",
            readMore: "Read more",
            labels: {
                "new": "Feature",
                "improvement": "Improvement",
                "fix": "Fixes"
            },
            footer: "Read more 👉"
        },
        callbacks: {
            onWidgetReady: function(widget) {
                console.log("Widget is here!");
                console.log("unseen entries count: " + widget.getUnseenCount());
                HW_UnseenCount = widget.getUnseenCount();
            },
            onShowWidget: function() {
                console.log("Someone opened the widget!");
            },
            onShowDetails: function(changelog) {
                console.log(changelog.position); // position in the widget
                console.log(changelog.id); // unique id
                console.log(changelog.title); // title
                console.log(changelog.category); // category, lowercased
            },
            onReadMore: function(changelog) {
                console.log(changelog); // same changelog object as in onShowDetails callback
            },
            onHideWidget: function() {
                console.log("Who turned off the light?");
            }
        }
    };
    Headway.init(config);
    console.log("unseen entries count: " + HW_UnseenCount);
</script>
<!-- Popupsmart plugin -->

<script type="text/javascript" src="https://apiv2.popupsmart.com/api/Bundle/364244" async></script>
<!-- Data flow code -->
<script>
    var intcounter=0;
    var istimer=true;
    var titles1 = ["Billing software", "GST invoicing", "Bulk invoicing","Subcriptions", "Payment Gateway","Expenses management","Inventory management","GST filling","Payment pages"];

</script>

@endsection
