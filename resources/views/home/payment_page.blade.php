@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')
<script src="/static/js/carousel-jquery-1.9.1.min.js"  crossorigin="anonymous"></script>
<script src="/static/js/carousel-bootstrap.js" async=""></script>
<link rel="stylesheet" href="/static/css/carousel.css?version=1647678767">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <style>
        .plugin-p{
            margin-top:12px;padding:2px;font-size: 1.5rem;
        }
        .pstyle{
    text-align: start;
    margin-top: 2px;
        }
        .plus-background {
     background-image: url() !important;
}
        </style>
        
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Collect payments faster with customizable payment pages</h1>
                <p class="lead mb-2">No-code payment pages custom-made for your business. Start collecting payments with just a few clicks!  </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Custom payment pages" class="img-fluid" src="{!! asset('images/product/payment-page/payment-page.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Custom payment pages" class="img-fluid mb-5" src="{!! asset('images/product/payment-page/payment-page.svg') !!}" />
                <h1>Collect payments faster with customizable payment pages</h1>
                <p class="lead mb-2">No-code payment pages custom-made for your business. Start collecting payments with just a few clicks.  </p>
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
                <h3>Receive payments in just a few clicks!<br/><br/>No code. Prompt payments. </h3>
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
       
        <div class="row text-center mt-4">
            <div class="container">
              
                <div class="row ">
                  <div class="col-md-12 d-none d-sm-block"> 
                    <div class="carousel slide  " id="theCarousel" data-ride="carousel">
                       
                  
      <!--Slides-->
      <div class="carousel-inner" role="listbox">
                          <!--First slide-->
        <div class="item active">
            <div class="row">

                <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                    <div class="card d-flex flex-column">
                        <h2 class="gray-600 plugin-p" ><strong>No coding required!</strong></h2>
                        <img class="card-img-top p-2 mt-4" src="{!! asset('images/product/payment-page/features/payment-page-feature-no-code.svg') !!}" alt="Payment page feature no code" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Set up your custom payment page without the hassle of server setups, integrations, or technical know-how. Start accepting prompt payments online with customized no code payment pages.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Automated payment reminders</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-automated-reminders.svg') !!}" alt="Payment page feature automated reminders" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Ensure prompt payments with automated payment reminders created & sent at predefined intervals as per your needs. Send payment reminders with online payment options via email & SMS.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Personalized payment receipts</strong></h2>
                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-payment-receipt.svg"') !!}" alt="Payment page feature payment receipt" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Generate personalized payment receipts automatically. Auto-fill and update error-free payment details for payment receipts. Add/edit the information captured in the payment receipts.
                                    </p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Customizable payment pages</strong></h2>
                        <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-customizable-pages.svg') !!}" alt="Payment page feature customizable pages" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Customize your website, event page, booking calendar & more to reflect your brand image. Configure the different billing & payment fields to curate relevant details to suit your needs.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Multiple payment options at the least possible rates!</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-multiple-payment-modes.svg') !!}" alt="Payment page feature multiple payment modes" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Accept payments via UPI, Debit card, Credit card, eWallets & more for domestic and international transactions. Ensure seamless payment collections with easy payment gateway integrations. </p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Customizable payment links</strong></h2>
                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-payment-link.svg') !!}" alt="Payment page feature payment link" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Create & share payment links to collect domestic and international payments across multiple payment modes. Create payment links with fixed or flexible amounts as per your requirement. </p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Easy refunds</strong></h2>
                        <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-easy-refunds.svg') !!}" alt="Payment page feature easy refunds" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Hassle-free refunds & cancellations for your customers and benefactors. Disburse refunds and track payments in real-time from a single dashboard. Receive auto-updated reports on refunds.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Comprehensive data management</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-feature-customer-data-management.svg') !!}" alt="Payment page feature customer data management" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Manage data for your customers, events, payments, and more, all from a single dashboard. Track online payment collections, refund disbursement and more for your custom payment pages. </p> 
                            </div>
                          </div>
    
                        </div>
                        
              
             
             
            </div>
          </div>
            <!--End Third slide-->
          <!--Four slide-->
          
            <!--End Four slide-->
        
         
        
        
                        <!--  Example item end -->
                      </div>
                      <div>
<br/><br/>
                        <ul class="carousel-indicators">
                            <li data-target="#theCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#theCarousel" data-slide-to="1"></li>
                            <li data-target="#theCarousel" data-slide-to="2"></li>
                          
                           
                        </ul>

                    
                    </div>
                  </div>
                </div>
                {{-- Ipad & Mobile view --}}

                <div class="col-md-12 d-sm-none">
                    <div class="carousel slide" id="theCarousel1" data-ride="carousel">
                                           
                                      
                          <!--Slides-->
                          <div class="carousel-inner d-sm-none" role="listbox">
                                              <!--First slide-->
                            


                            <div class="item active">
                                <div class="row">
                    
                                    <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                        <div class="card d-flex flex-column">
                                            <h2 class="gray-600 plugin-p" ><strong>No coding required!</strong></h2>
                                            <img class="card-img-top p-2 mt-4" src="{!! asset('images/product/payment-page/features/automated-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Set up your custom payment page without the hassle of server setups, integrations, or technical know-how. Start accepting prompt payments online with customized no code payment pages.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Automated payment reminders</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/plug-n-play-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Ensure prompt payments with automated payment reminders created & sent at predefined intervals as per your needs. Send payment reminders with online payment options via email & SMS.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Personalized payment receipts</strong></h2>
                                                    <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/smart-validation.svg"') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Generate personalized payment receipts automatically. Auto-fill and update error-free payment details for payment receipts. Add/edit the information captured in the payment receipts.
                                                        </p> 
                                                    </div>
                                                  </div>
                            
                                                </div>
                                  
                                 
                                 
                                </div>
                              </div>
                             
                              <div class="item">
                                <div class="row">
                    
                                    <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                        <div class="card d-flex flex-column">
                                           <h2 class="gray-600 plugin-p" ><strong>Customizable payment pages</strong></h2>
                                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/industry-wise-invoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Customize your website, event page, booking calendar & more to reflect your brand image. Configure the different billing & payment fields to curate relevant details to suit your needs.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Multiple payment options at the least possible rates!</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/bulk-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Accept payments via UPI, Debit card, Credit card, eWallets & more for domestic and international transactions. Ensure seamless payment collections with easy payment gateway integrations. </p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Customizable payment links</strong></h2>
                                                    <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/recurring-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Create & share payment links to collect domestic and international payments across multiple payment modes. Create payment links with fixed or flexible amounts as per your requirement. </p> 
                                                    </div>
                                                  </div>
                            
                                                </div>
                                  
                                 
                                 
                                </div>
                              </div>
                              
                              <div class="item">
                                <div class="row">
                    
                                    <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                        <div class="card d-flex flex-column">
                                           <h2 class="gray-600 plugin-p" ><strong>Easy refunds</strong></h2>
                                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/accurate-gst-data.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Hassle-free refunds & cancellations for your customers and benefactors. Disburse refunds and track payments in real-time from a single dashboard. Receive auto-updated reports on refunds.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Comprehensive data management</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/multiple-gst-profiles.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Manage data for your customers, events, payments, and more, all from a single dashboard. Track online payment collections, refund disbursement and more for your custom payment pages. </p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    
                              
                              
                            
                          
                                
                                   
                               
                                    
                               
                            
                              
                               <!--Seven End-->
                            
                                            <!--  Example item end -->
                                          </div>
                                          <div class="mt-2">
                   
                    
                                            <a class="left carousel-control" style="background: transparent;" href="#theCarousel1" data-slide="prev"><button class="btn btn-primary" ><</button></a>
                                            <a class="right carousel-control" style="background: transparent;" href="#theCarousel1" data-slide="next"><button class="ml-4 btn btn-primary" >></button></a>
                                         
                                        </div>
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
                <h3 class="text-white">Over 25,000+ businesses trust Swipez.<br /><br />Get your free account today</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Register
                    now</a>
               
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Register
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
                <h2 class="display-4">Custom payment pages for your industry</h2>
                <h5 >Hassle-free payment pages for your business</h5> 
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-3 d-none d-sm-block">
                <ul class="nav nav-pills" id="industryTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active text-uppercase gray-400" id="event-management-tab" data-toggle="pill" href="#event-management" role="tab" aria-controls="event-management" aria-selected="true">Event Management</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="educational-institutions-tab" data-toggle="pill" href="#educational-institutions" role="tab" aria-controls="educational-institutions" aria-selected="false">Educational Institutions</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="payment-link-generator-tab" data-toggle="pill" href="#payment-link-generator" role="tab" aria-controls="payment-link-generator" aria-selected="false">Payment Link Generator</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="booking-calendar-tab" data-toggle="pill" href="#booking-calendar" role="tab" aria-controls="booking-calendar" aria-selected="false">Booking Calendar</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link text-uppercase gray-400" id="website-builder-tab" data-toggle="pill" href="#website-builder" role="tab" aria-controls="website-builder" aria-selected="false">Website Builder</a>
                    </li>
                  
                </ul>
            </div>
            <div class="col-12 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="event-management" role="tabpanel" aria-labelledby="event-management-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}event-registration-for-entertainment-event">Event Management</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-for-events.svg') !!}" alt="payment page for events" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">
                                    Create & manage events from designing an <a href="{{ config('app.APP_URL') }}event-registration-for-entertainment-event">event page</a> to collecting payments for ticket sales all from a single dashboard. Curate different event packages, generate QR codes, share & promote events on social media and a lot more.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="educational-institutions" role="tabpanel" aria-labelledby="educational-institutions-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}billing-software-for-school">Educational Institutions</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-for-educational-institutions.svg') !!}" alt="payment page for educational institutions" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Stay on top of fee collections, online invoicing, and student data management. <a href="{{ config('app.APP_URL') }}billing-software-for-school">Error-free invoicing</a> with automated payment reminders and payment links via SMS & email, payment receipts with pertinent details to ensure a seamless collection process.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="payment-link-generator" role="tabpanel" aria-labelledby="payment-link-generator-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}payment-link">Payment Link Generator</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-for-payment-links.svg') !!}" alt="payment page for payment links" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Generate <a href="{{ config('app.APP_URL') }}payment-link">payment links</a> to accept domestic and international payments via multiple modes of payment like UPI, Debit card, Credit card, Net banking, eWallets & more. Share payment links on social media platforms such as Facebook, Twitter, and Instagram and start collecting prompt payments.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="booking-calendar" role="tabpanel" aria-labelledby="booking-calendar-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}venue-booking-software"> Booking Calendar</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-for-calendar-bookings.svg') !!}" alt="payment page for calendar bookings" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Create customizable membership plans for facilities, community building events with easy to design and manage <a href="{{ config('app.APP_URL') }}venue-booking-software">booking calendars</a>. Setup booking details, slot reservations, automate invoicing, payment collections & more.

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="website-builder" role="tabpanel" aria-labelledby="website-builder-tab">
                        <div class="border-0">
                            <div class="card-title">
                                <h2 class="card-title gray-600">
                                    <center><a href="{{ config('app.APP_URL') }}website-builder">Website Builder</a></center>
                                </h2>
                            </div>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/payment-page/features/payment-page-for-websites.svg') !!}" alt="payment page for websites" width="640" height="360" loading="lazy" class="lazyload" />
                            <div class="card-body">
                                <p class="lead">Create & manage a <a href="{{ config('app.APP_URL') }}website-builder">website for your business</a> with zero-coding required. Accept online payments, streamline payment collections with simple payment links, integrated payment gateway & more.  
                                </p>
                            </div>
                        </div>
                    </div>
                   
                    
                </div>
                <div class="text-center d-sm-none">
                    <button id="prevtab" class="btn btn-primary" onclick="prevIndustryClick();">
                         </button> 
                         <button id="nexttab" class="ml-5 btn btn-primary" onclick="nextIndustryClick();"> 
                              
                            </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Swipez custom payment pages </h2>
                <h5 class="text-white">Start collecting payments with ease</h5>
            </div>
        </div><br/>
         <div class="row row-eq-height text-center pb-5">
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Automated payment receipts </h3>
                    <p>Ensure prompt payments with automated payment reminders via SMS & email.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Multiple payment modes</h3>
                    <p>Receive domestic & international payments via UPI, Debit card, Credit card, Net banking and eWallets.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Hassle-free refunds</h3>
                    <p>Offer easy cancellations and refunds directly from your payment page.</p>
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
                    using Swipez billing software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}" width="166" height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now we send the monthly internet bills to our customers at the click of a
                                button. Customers receive bills on e-mail and SMS with multiple online payment options.
                                Payments collected online and offline are all reconciled with Swipez billing."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Solutions</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded" src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}" width="166" height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We are now managing payments across our complete customer base along with
                                timely pay outs for all franchisee's across the country from one dashboard. My team has
                                saved over hundred hours after adopting Swipez Billing."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Vikas Sankhla</strong>
                            </p>
                            <p>
                                <em>Co-founder, Car Nanny</em>
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
                <h2 class="text-white">Supercharge your business with Swipez custom payment pages </h2>
              
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start now</a>
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
                            <h4>How to integrate Swipez payment pages?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Swipez payment pages do not require any integration or coding. You can pick and choose what kind of payment page you want. For example, a website, an event page, or a simple payment link to share with your customers via social media. And, we’ll take care of the rest for you.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> Are Swipez payment pages secure?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We at Swipez value our customer’s privacy above all else. Merchants, e-commerce sellers, businesses rely on us for their invoicing & payment collections. And, we take that responsibility and our duty of care towards them very seriously. The security of our software, systems, and customer data are our number one priority.
                            <br/>
                            <br/>
                            Every piece of information that is transmitted between your browser and Swipez is protected with 256-bit SSL encryption. This ensures that your data is secure in transit. All data you have entered into Swipez sits securely behind web firewalls. This system is used by some of the biggest companies in the world and is widely acknowledged as safe and secure.

                        </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Who can I reach out to for help related to custom payment pages?
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a dedicated team of experts who will be happy to assist you. You can <a href="{{ config('app.APP_URL') }}getintouch/pricing">reach out</a> to us via email, chat, and our call center.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Do I need to download anything to start using Swipez’s custom payment pages?
                            </h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third-party software to start using the Swipez custom payment pages. Our seamless <a href="{{ route('home.integrations') }}" target="_blank">integrations</a> ensure that you can start using the Swipez custom payment pages effortlessly.
                             </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>Can I use Swipez’s custom payment pages on different devices?</h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez’s payment pages are compatible and work perfectly on a variety of devices and platforms, including smartphones, tablets, desktops, and laptops.

                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4> What are the charges applicable for the payment pages?</h4>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                There are no extra charges on transactions on the payment pages. The only charges applied are <a href="https://www.swipez.in/payment-gateway-charges">online payment collection fees</a>. These are regulatory fees applied by banks to provide online payment collections via a payment gateway.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h4>Can I create a unified payment page for different booking calendars?</h4>

                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create and customize a payment page for different calendars with multiple time slots.
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
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
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
