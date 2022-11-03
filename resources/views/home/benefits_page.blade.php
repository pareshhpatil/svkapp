@extends('home.master')
@section('content')
<style>
    .plugin-p{
        margin-top:12px;padding:2px;font-size: 1.5rem;
    }
    .pstyle{
        text-align: start;
        margin-top: 2px;
    }
    .btns{
    text-transform: uppercase;
    font-size: 9px;
    padding-left: 5px;
    padding-right: 5px;
    padding-top: 2px;
    padding-bottom: 2px;
    border-radius: 8px;
    margin-bottom: 5px;
   }
.p_title{
    font-size: 14px; color: white !important;
}
.imgs{
    /* width: 60px  !important; */
        height: 30px  !important;
        /* max-width: 30% !important; */
}
@media only screen and (max-width: 600px) {
    .imgs{

        height: 30px  !important;
          
}
.text_center{
    text-align: center;
   
}

}
.text_center{
    text-align: auto;
}
.para {
    line-height: 1.2em;

font-size:16px;
  /* overflow: hidden;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3; */
}

</style>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Swipez partner benefits</h1>
                <p class="lead mb-2">Fast-track your business growth! Ease billing, payment collections, payouts, international payments, and more. Trusted by 25000+ businesses across the nation!</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Swipez Benefits" class="img-fluid" src="{!! asset('images/benefits/Swipez_Benefits.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez Benefits" class="img-fluid mb-5" src="{!! asset('images/benefits/Swipez_Benefits.svg') !!}" />
                <h1>Swipez partner benefits</h1>
                <p class="lead mb-2">Fast-track your business growth! Ease billing, payment collections, payouts, international payments, and more. Trusted by 25000+ businesses across the nation!</p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container shadow-lg p-5 bg-white rounded-1">
        <div class="row ">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                 <img alt="Swipez Benefits" class="img-fluid" src="{!! asset('images/benefits/Swipez_partner_benefits.svg') !!}" />
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <h2>Get the latest benefits from Swipez</h2>
                <p class="lead mb-2">Whatever your business needs, we are here to help. From website development to billing and invoicing. From payment collections to payouts across different channels. We’ve got you covered!</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Swipez Benefits" class="img-fluid mb-5" src="{!! asset('images/benefits/Swipez_partner_benefits.svg') !!}" />
                <h1>Get the latest benefits from Swipez</h1>
                <p class="lead mb-2">Whatever your business needs, we are here to help. From website development to billing and invoicing. From payment collections to payouts across different channels. We’ve got you covered!</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Accelerate your business growth with Swipez & our partners.</h3>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-5" id="cta">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-4 mb-3 d-flex"> 
                <div class="text_center box-plugin  apps-shadow mr-1 card">
                    <a href="/partner-benefits/razorpay" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/razorpaypaymentgateway/razorpay.png?id=v1')!!}">
                            </div> --}}
                            <h4>Razorpay </h4> 
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Payment Gateway</button>
                            <div class="apps-box">
                                <p class="lead para">Avail over 100+ payment methods with Razorpay payment gateway and let us take care of the rest.  </p>
                            </div> 
                        </div>
                    </a>
                </div> 
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin  apps-shadow mr-1 card">
                    <a href="/partner-benefits/cashfree" style="text-decoration: none !important;" >
                        {{-- <div class="col-md-3 mt-2"> 
                            <img class="imgs" src="{!! asset('images/benefits/benefits-partners/cashfree2.png?id=v1')!!}">
                        </div> --}}
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-0">
                                <img class="imgs" style="padding-bottom: 7px;" src="{!! asset('images/benefits/cashfreepaymentgateway/cashfree2.png?id=v1')!!}">
                            </div> --}}
                            <h4>Cashfree</h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Payment Gateway</button>
                            <div class="apps-box">
                                <p class="lead para"> From payment collection to on-demand payouts and everything in between, Cashfree offers a powerful and scalable payment gateway tailored to your needs. </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin  apps-shadow mr-1 card">
                    <a href="/partner-benefits/stripe" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/stripe.png?id=v1')!!}">
                            </div> --}}
                            <h4>Stripe </h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Payment Gateway</button>
                            <div class="apps-box">
                                <p class="para">Power your payment collections with Stripe. Accept both domestic and international payments with a simple and streamlined payment gateway integration. </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-4 mb-3 d-flex">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                    <a href="/partner-benefits/payoneer" style="text-decoration: none !important;" >       
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-0">
                                <img class="imgs" style="padding-bottom: 5px" src="{!! asset('images/integrations/payoneer.png?id=v1')!!}">
                            </div> --}}
                            <h4>Payoneer  </h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Payment Gateway</button>
                            <div class="apps-box">
                                <p class="lead para">Expand your company's reach with a seamless payment gateway integration. Receive payments from anywhere in the world. </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex">
                <div class="text_center box-plugin apps-shadow  mr-1 card">
                    <a href="/partner-benefits/boot-360" style="text-decoration: none !important;" >       
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/boost-360-logo.svg?id=v1')!!}">
                            </div> --}}
                            <h4>Boost 360 </h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Website Builder</button>
                            <div class="apps-box">
                                <p class="lead para">Create an ecommerce website with online payment collections and optimized for multiple devices in minutes. No coding, server setup, or technical know-how required!  </p>
                            </div> 
                        </div>
                    </a>       
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                   <a href="/partner-benefits/lending-kart" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                        {{-- <div class="mt-2 mb-2">
                            <img class="imgs" src="{!! asset('images/benefits/lendingkart.png?id=v1')!!}">
                        </div> --}}
                            <h4>Lendingkart</h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Business Loan</button>
                            <div class="apps-box">
                                <p class="lead para">Get business loans upto ₹ 1 Crore in less than 24 hrs. Join the 90,000+ businesses across the nation supported by the RBI approved NBFC. 
                                </p>
                            </div>
                        </div>
                   </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-4 mb-3 d-flex">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                    <a href="/partner-benefits/sms" style="text-decoration: none !important;" > 
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/swipezwebsitebuilder/swipezlogo.png?id=v1')!!}">
                            </div> --}}
                            <h4>Swipez SMS notification packages</h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">SMS package</button>
                            <div class="apps-box">
                                <p class="lead para">Automate notifications for your invoices and payment collections. Ensure prompt payments with timely SMS reminders. Add SMS credits to your account as per needs.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                   <a href="/partner-benefits/dalal-street" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                        {{-- <div class="mt-2 mb-0">
                            <img class="imgs" src="{!! asset('images/benefits/dsij/dsijlogo.png?id=v1')!!}">
                        </div> --}}
                            <h4>Dalal Street Investment Journal </h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Finance</button>
                            <div class="apps-box">
                                <p class="lead para">Stay on top of the latest stock market trends, updates, and more. Get the best of stock market analysis by industry-experts at a 50% discount on the annual subscription with Swipez.
                                </p>
                            </div>
                        </div>
                   </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                   <a href="/partner-benefits/scatter" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/scattercontentcreationservices/scatterlogo.png?id=v1')!!}">
                            </div> --}}
                            <h4>Scatter content creation services</h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Content Writing</button>
                            <div class="apps-box">
                                <p class="lead para">Ensure fresh & SEO friendly content for your website, emailers, newsletters, social media campaigns, and more. Create a brand voice that boosts your bottom line. 
                                </p>
                            </div>
                        </div>
                   </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                    <a href="/partner-benefits/amazon-web-services" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/amazonwebservices/awsactivate.png?id=v1')!!}">
                            </div> --}}
                            <h4>Amazon Web Services </h4>
                      
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Cloud Computing</button>
                            <div class="apps-box">
                                <p class="lead para">Amazon Web Services provides cloud computing services that are affordable, dependable, and scalable. </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 mb-3 d-flex ">
                <div class="text_center box-plugin apps-shadow mr-1 card">
                   <a href="/partner-benefits/website-builder" style="text-decoration: none !important;" >
                        <div class="col-md-12 mt-2"> 
                            {{-- <div class="mt-2 mb-2">
                                <img class="imgs" src="{!! asset('images/benefits/swipezwebsitebuilder/swipezlogo.png?id=v1')!!}">
                            </div> --}}
                            <h4> Swipez website builder</h4>
                            <button type="button" class="btns btn btn-outline-primary btn-rounded waves-effect">Website Builder</button>
                            <div class="apps-box">
                                <p class="para">Create a website for your business and start collecting payments online in minutes. Customizable websites with your own  domain as per your brand. </p>
                            </div>
                        </div>
                   </a>
                </div>         
            </div>
        </div>
    </div>
</section>

<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5 ">
                <h3 class="text-white">Drop us a line and we’ll get in touch
                </h3>
            </div>
            <div class="col-md-12">
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>

@endsection
