


@extends('home.master')
@section('title', 'Send payment links, invoices or forms and collect payment faster from customers')

@section('content')


<script src="/static/js/carousel-jquery-1.9.1.min.js"  crossorigin="anonymous"></script>
<script src="/static/js/carousel-bootstrap.js" async=""></script>
<link rel="stylesheet" href="/static/css/carousel.css?version=1647678767">

 <style>
    .plugin-p{
        margin-top:12px;padding:2px;font-size: 1.5rem;
    }
    .pstyle{
text-align: start;
margin-top: 2px;
    }
    </style>
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Online invoice generator software for all businesses</h1>
                <p class="lead mb-2">Automate error-free GST invoicing to suit your business needs. Ensure prompt payment collections for both domestic and international customers. Fast-track your business with the best in online invoicing solutions. </p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Simplified GST einvoicing for businesses" class="img-fluid" src="{!! asset('images/product/einvoicing-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Simplified GST einvoicing for businesses" class="img-fluid mb-5" src="{!! asset('images/product/einvoicing-software.svg') !!}" />
                <h1>Online Invoice Generator Software for all business</h1>
                <p class="lead mb-2">Automate error-free GST invoicing to suit your business needs. Ensure prompt payment collections for both domestic and international customers. Fast-track your business with the best in online invoicing solutions. </p>
                @include('home.product.web_register',['d_type' => "mob"])
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
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    now</a>
               
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start
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
                        <h2 class="gray-600 plugin-p" ><strong>Easy to customize invoice templates</strong></h2>
                        <img class="card-img-top p-2 mt-4" src="{!! asset('images/product/einvoicing/features/automated-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Personalize industry-approved invoice templates to reflect your brand image and suit your business needs. Choose from a wide range of GST compliant invoice templates designed for different industries like utility providers, tour & travel operators, freelancers & more. </p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Manage customer data for easy invoicing</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/plug-n-play-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Automate customer data management with invoicing. Add a unique customer code to your invoices either automatically or manually. Manage customer details like contact information, company information, GST details & more with configurable customer database fields.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Accurate GST calculations for invoices</strong></h2>
                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/smart-validation.svg"') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Ensure error-free GST calculations for your invoices. Accurately calculate the applicable GST rates for each item on your invoice as per your customer’s GSTN and the product/service. Create invoices for multiple GST profiles with ease from a single dashboard.
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
                       <h2 class="gray-600 plugin-p" ><strong>Multiple payment options</strong></h2>
                        <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/industry-wise-invoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Collect payments directly from your invoices via multiple modes like UPI, Debit card, Credit card, Net banking, eWallets & more. Receive prompt domestic and international payments directly in your company bank account with seamless payment gateway integration.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Inventory management</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/bulk-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Automatically reduce stocks or product quantity as you raise invoices. Manage different products/services with a variety of features. Add product number, cost price, sale price, maximum retail price (MRP), expiry date, discounts, applicable HSN/SAC code & more. </p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Automated payment notifications</strong></h2>
                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/recurring-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Automate payment reminders with online <a href="{{ config('app.APP_URL') }}payment-link">payment links</a> to ensure prompt payments. Customize the frequency of reminders, notification text, and more as per your requirements. Send automated payment reminders to customers with overdue invoices via email and/or SMS. </p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Recurring invoicing</strong></h2>
                        <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/einvoicing-email-pdf.svg"') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Create and send recurring invoices to your customers at a predefined frequency of your choice. Auto-generate recurring invoices with accurate GST calculations, payment notifications & online payment links with a simple subscription. Create subscriptions in bulk with easy excel imports.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong> Bulk invoicing</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/accurate-gst-data.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Create and send invoices in bulk with easy excel uploads and/or APIs. Auto-generate and deliver invoices in bulk to your customer base via email & SMS with online payment options. Automate bulk invoicing for recurring invoices with easy-to-create subscriptions. </p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>E-invoicing</strong></h2>
                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Create GST-compliant e-invoices and submit them directly to the Invoice Registration Portal (IRP). Add a unique Invoice Reference Number (IRN) and a QR code before sending them to your customers. Ensure error-free GST filing and reconciliation with e-invoicing.</p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Plugins to customize automations for invoices</strong></h2>
                        <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/accurate-gst-data.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Add discount coupons, attach files, enable auto collection of payments, and more with plugins to customize your invoice. Create invoices for franchise, notify suppliers, calculate previous dues & more. Enable/disable plugins to ease billing & payment collections.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Convert estimates to invoices</strong></h2>
                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle"> Estimates created & sent to your customers include an online payment option. Once an estimate is paid, it can easily be converted to an invoice. Either automatically or manually as per your needs. Ensure error-free invoicing with easy conversions for estimates.
                                </p> 
                            </div>
                          </div>
    
                        </div>
                      
              
             
             
            </div>
          </div>
            <!--End Four slide-->
        
         
        
        
                        <!--  Example item end -->
                      </div>
                      <div>
<br/><br/>
                        <ul class="carousel-indicators">
                            <li data-target="#theCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#theCarousel" data-slide-to="1"></li>
                            <li data-target="#theCarousel" data-slide-to="2"></li>
                            <li data-target="#theCarousel" data-slide-to="3"></li>
                           
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
                                            <h2 class="gray-600 plugin-p" ><strong>Easy to customize invoice templates</strong></h2>
                                            <img class="card-img-top p-2 mt-4" src="{!! asset('images/product/einvoicing/features/automated-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Personalize industry-approved invoice templates to reflect your brand image and suit your business needs. Choose from a wide range of GST compliant invoice templates designed for different industries like utility providers, tour & travel operators, freelancers & more. </p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Manage customer data for easy invoicing</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/plug-n-play-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Automate customer data management with invoicing. Add a unique customer code to your invoices either automatically or manually. Manage customer details like contact information, company information, GST details & more with configurable customer database fields.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Accurate GST calculations for invoices</strong></h2>
                                                    <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/smart-validation.svg"') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Ensure error-free GST calculations for your invoices. Accurately calculate the applicable GST rates for each item on your invoice as per your customer’s GSTN and the product/service. Create invoices for multiple GST profiles with ease from a single dashboard.
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
                                           <h2 class="gray-600 plugin-p" ><strong>Multiple payment options</strong></h2>
                                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/industry-wise-invoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Collect payments directly from your invoices via multiple modes like UPI, Debit card, Credit card, Net banking, eWallets & more. Receive prompt domestic and international payments directly in your company bank account with seamless payment gateway integration.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Inventory management</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/bulk-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Automatically reduce stocks or product quantity as you raise invoices. Manage different products/services with a variety of features. Add product number, cost price, sale price, maximum retail price (MRP), expiry date, discounts, applicable HSN/SAC code & more. </p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Automated payment notifications</strong></h2>
                                                    <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/recurring-einvoicing.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Automate payment reminders with online <a href="{{ config('app.APP_URL') }}payment-link">payment links</a> to ensure prompt payments. Customize the frequency of reminders, notification text, and more as per your requirements. Send automated payment reminders to customers with overdue invoices via email and/or SMS. </p> 
                                                    </div>
                                                  </div>
                            
                                                </div>
                                  
                                 
                                 
                                </div>
                              </div>
                              
                              <div class="item">
                                <div class="row">
                    
                                    <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                        <div class="card d-flex flex-column">
                                           <h2 class="gray-600 plugin-p" ><strong>Recurring invoicing</strong></h2>
                                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/einvoicing-email-pdf.svg"') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Create and send recurring invoices to your customers at a predefined frequency of your choice. Auto-generate recurring invoices with accurate GST calculations, payment notifications & online payment links with a simple subscription. Create subscriptions in bulk with easy excel imports.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong> Bulk invoicing</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/accurate-gst-data.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Create and send invoices in bulk with easy excel uploads and/or APIs. Auto-generate and deliver invoices in bulk to your customer base via email & SMS with online payment options. Automate bulk invoicing for recurring invoices with easy-to-create subscriptions. </p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>E-invoicing</strong></h2>
                                                    <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Create GST-compliant e-invoices and submit them directly to the Invoice Registration Portal (IRP). Add a unique Invoice Reference Number (IRN) and a QR code before sending them to your customers. Ensure error-free GST filing and reconciliation with e-invoicing.</p> 
                                                    </div>
                                                  </div>
                            
                                                </div>
                                  
                                 
                                 
                                </div>
                              </div>
                              
                              <div class="item">
                                <div class="row">
                    
                                    <div class="col-md-4 col-xs-412 d-flex align-items-stretch"> 
                                        <div class="card d-flex flex-column">
                                           <h2 class="gray-600 plugin-p" ><strong>Plugins to customize automations for invoices</strong></h2>
                                            <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/accurate-gst-data.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Add discount coupons, attach files, enable auto collection of payments, and more with plugins to customize your invoice. Create invoices for franchise, notify suppliers, calculate previous dues & more. Enable/disable plugins to ease billing & payment collections.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Convert estimates to invoices</strong></h2>
                                                <img class=" card-img-top p-2 mt-4" src="{!! asset('/images/product/einvoicing/features/multiple-gst-profiles.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle"> Estimates created & sent to your customers include an online payment option. Once an estimate is paid, it can easily be converted to an invoice. Either automatically or manually as per your needs. Ensure error-free invoicing with easy conversions for estimates.
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
                   
                    
                                            <a class="left carousel-control" style="background: transparent; margin-right:10px" href="#theCarousel1" data-slide="prev"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i></a>
                                            <a class="right carousel-control" style="background: transparent;" href="#theCarousel1" data-slide="next"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></i></a>
                                         
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
                <h3 class="text-white">Used and trusted by 25,000+ businesses</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Register today to know more!</a>
               
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
                <h2 class="display-4">Invoicing automation plugins</h2>
                <h5 >Enable plugins to customize your invoices with various features and automations.</h5> 
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
                        <h2 class="gray-600 plugin-p" ><strong>Invoice number</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Add sequential invoice numbers to your invoices to ease billing. Enable system-generated invoice sequence numbers or manually add them to each invoice.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Add deductibles to invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Allow your customers to deduct TDS from their invoices/estimates before making payments. Automate TDS deductions from the invoice total.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong> Supplier invoices</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Automate supplier notifications for invoice/estimate payments. Your suppliers will be immediately notified via email & SMS upon payment.</p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Add discount coupons to invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Add discount coupon codes to your invoices that can be used to apply discounts, which will be auto-deducted from the invoice amounts.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>CC email invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Email your team, suppliers, and/or franchise a copy of your invoices. They will automatically receive emails for the invoices created.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Round off invoice total</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Eliminate decimal points and round off the invoice total to the nearest value. Round off invoice total inclusive of applicable taxes.</p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Add acknowledgment for invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Include an acknowledgment section to your invoices. Incorporate the acknowledgment section in your online, PDF, and printed invoices.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Franchise invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Create invoices for your franchise and split the invoice amount once paid. Automate email & SMS notifications for franchise invoices.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Vendor invoices</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Add vendors to your invoices to automatically split the payments. Automate the split among different vendors or pay one vendor in full.</p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Pre-paid invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Create invoices for previously collected payments. Pre-paid invoices won't include payment options if the amount has been paid in full.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Covering note for invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Send your customers a personalized covering note with invoices. Covering notes will be received as a PDF attachment with payment options.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Customized reminder text for invoices</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Customize the notification text created & sent via email and SMS with your invoices. Personalize the copy to reflect your brand and needs.</p> 
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
                       <h2 class="gray-600 plugin-p" ><strong>Customized reminder schedule for unpaid invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Personalize the frequency of payment reminders sent via SMS and email. Tailor the schedule of payment reminders to suit your requirements.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Partial payment of invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Allow your customers to pay their invoices in installments. Customize the minimum amount for partial payments on invoices to meet your needs.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Auto collect via invoices</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Automate the collection of recurring payments for your products or services. Set up an auto collections schedule that suits your requirements.
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
                       <h2 class="gray-600 plugin-p" ><strong>Attach files to invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Add documents, images & more to invoices with a simple file upload. Your customers will receive the invoices with attachments via email & SMS.</p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong> Add digital signature to invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Automatically add digital signatures to your invoices. Create, upload, and personalize your digital signature before adding it to invoices.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Add expiry date to invoices</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Add an expiry date to your invoices, after which it will become invalid. Payment reminders will not be created or sent post the expiry date.
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
                       <h2 class="gray-600 plugin-p" ><strong>Calculate previous due for invoices</strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                            <p class="lead pstyle">Auto-calculate outstanding dues when creating a new invoice. The dues will be automatically added to the invoice total.
                        </p> 
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                        <div class="card d-flex flex-column">
                           <h2 class="gray-600 plugin-p" ><strong>Enable/Disable payments for invoices</strong></h2>
                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                            <div class="container">
                              
                                <p class="lead pstyle">Enable or disable online payments for invoices and/or estimates. If disabled, invoices won’t include online payment options or links.</p> 
                            </div>
                          </div>
    
                        </div>
                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                            <div class="card d-flex flex-column">
                               <h2 class="gray-600 plugin-p" ><strong>Customize payment receipt</strong></h2>
                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                <div class="container">
                                  
                                    <p class="lead pstyle">Personalize the payment receipt received by your customers. Add/edit the information in the payment receipts as per your requirements.
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
                        <h2 class="gray-600 plugin-p" ><strong><a href="{{ config('app.APP_URL') }}e-invoicing">E-invoicing</a></strong></h2>
                        <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                        <div class="container">
                          
                          <p class="lead pstyle">Create GST compliant e-invoices and submit them directly to the Invoice Registration Portal (IRP) with a unique Invoice Reference Number and QR code.
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
<br/><br/>
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
                                           <h2 class="gray-600 plugin-p" ><strong>Invoice Number</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Add sequential invoice numbers to your invoices to ease billing. Enable system-generated invoice sequence numbers or manually add them to each invoice.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Add deductibles to invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Allow your customers to deduct TDS from their invoices/estimates before making payments. Automate TDS deductions from the invoice total.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong> Supplier invoices</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Automate supplier notifications for invoice/estimate payments. Your suppliers will be immediately notified via email & SMS upon payment.</p> 
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
                                           <h2 class="gray-600 plugin-p" ><strong>Add discount coupons to invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Add discount coupon codes to your invoices that can be used to apply discounts, which will be auto-deducted from the invoice amounts.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>CC email invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Email your team, suppliers, and/or franchise a copy of your invoices. They will automatically receive emails for the invoices created.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Round off invoice total</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Eliminate decimal points and round off the invoice total to the nearest value. Round off invoice total inclusive of applicable taxes.</p> 
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
                                           <h2 class="gray-600 plugin-p" ><strong>Add acknowledgment for invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Include an acknowledgment section to your invoices. Incorporate the acknowledgment section in your online, PDF, and printed invoices.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Franchise invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Create invoices for your franchise and split the invoice amount once paid. Automate email & SMS notifications for franchise invoices.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Vendor invoices</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Add vendors to your invoices to automatically split the payments. Automate the split among different vendors or pay one vendor in full.</p> 
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
                                           <h2 class="gray-600 plugin-p" ><strong>Pre-paid invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Create and send invoices for previously collected payments. Pre-paid invoices won't include payment options if the amount has been paid in full.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Covering note for invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Send your customers a personalized covering note with invoices. Covering notes will be received as a PDF attachment with payment options.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Customized reminder text for invoices</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Customize the notification text created & sent via email and SMS with your invoices. Personalize the copy to reflect your brand and needs.</p> 
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
                                           <h2 class="gray-600 plugin-p" ><strong>Customized reminder schedule for unpaid invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Personalize the frequency of payment reminders sent via SMS and email. Tailor the schedule of payment reminders to suit your requirements.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Partial payment of invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Allow your customers to pay their invoices in installments. Customize the minimum amount for partial payments on invoices to meet your needs.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Auto collect via invoices</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Automate the collection of recurring payments for your products or services. Set up an auto collections schedule that suits your requirements.
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
                                           <h2 class="gray-600 plugin-p" ><strong>Attach files to invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Add documents, images & more to invoices with a simple file upload. Your customers will receive the invoices with attachments via email & SMS.</p> 
                                            </div>
                                          </div>
                    
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                            <div class="card d-flex flex-column">
                                               <h2 class="gray-600 plugin-p" ><strong>Add digital signature to invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Automatically add digital signatures to your invoices. Create, upload, and personalize your digital signature before adding it to invoices.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Add expiry date to invoices</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Add an expiry date to your invoices, after which it will become invalid. Payment reminders will not be created or sent post the expiry date.
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
                                           <h2 class="gray-600 plugin-p" ><strong>Calculate previous due for invoices</strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                                <p class="lead pstyle">Auto-calculate outstanding dues when creating a new invoice. The dues will be automatically added to the invoice total.
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
                                               <h2 class="gray-600 plugin-p" ><strong>Enable/Disable payments for invoices</strong></h2>
                                                <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                <div class="container">
                                                  
                                                    <p class="lead pstyle">Enable or disable online payments for invoices and/or estimates. If disabled, invoices won’t include online payment options or links.</p> 
                                                </div>
                                              </div>
                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column">
                                                   <h2 class="gray-600 plugin-p" ><strong>Customize payment receipt</strong></h2>
                                                    <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">Personalize the payment receipt received by your customers. Add/edit the information in the payment receipts as per your requirements.
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
                                            <h2 class="gray-600 plugin-p" ><strong><a href="{{ config('app.APP_URL') }}e-invoicing">E-invoicing</a></strong></h2>
                                            <img class="plus-background card-img-top p-2 mt-4" src="{!! asset('/images/product/billing-software/features/cable-operator-billing-software.svg') !!}" alt="Create invoices or estimates" width="100%" height="200px" loading="lazy" class="lazyload" />
                                            <div class="container">
                                              
                                              <p class="lead pstyle">Create GST compliant e-invoices and submit them directly to the Invoice Registration Portal (IRP) with a unique Invoice Reference Number and QR code.
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
                   
                    
                                            <a class="left carousel-control" style="background: transparent; margin-right:10px" href="#theCarousel_mobile_plugin" data-slide="prev"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i></a>
                                            <a class="right carousel-control" style="background: transparent;" href="#theCarousel_mobile_plugin" data-slide="next"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></i></a>
                                         
                                        </div>
                                      </div>
                    </div>

              </div>
        </div>
     
    </div>
    </div>
</section>

<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Effortless invoicing for prompt payments </h2>
                {{-- <h5 class="text-white">Start collecting payments with ease</h5> --}}
            </div>
        </div><br/>
         <div class="row row-eq-height text-center pb-5">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-square" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Error-free invoices </h3>
                    <p>Accurate GST calculations on every invoice without any manual intervention. Ensure GST compliance and seamless monthly tax reconciliation.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cloud" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M537.6 226.6c4.1-10.7 6.4-22.4 6.4-34.6 0-53-43-96-96-96-19.7 0-38.1 6-53.3 16.2C367 64.2 315.3 32 256 32c-88.4 0-160 71.6-160 160 0 2.7.1 5.4.2 8.1C40.2 219.8 0 273.2 0 336c0 79.5 64.5 144 144 144h368c70.7 0 128-57.3 128-128 0-61.9-44-113.6-102.4-125.4z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Access anywhere</h3>
                    <p>Cloud-based invoicing software, compatible with different operating systems & & devices. Easy access to create & manage invoices on-the-go.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Multiple payment modes</h3>
                    <p>Prompt payments with multiple payment options like Credit card, Debit card, Net banking, UPI, eWallets, and more.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ol" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M61.77 401l17.5-20.15a19.92 19.92 0 0 0 5.07-14.19v-3.31C84.34 356 80.5 352 73 352H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h22.83a157.41 157.41 0 0 0-11 12.31l-5.61 7c-4 5.07-5.25 10.13-2.8 14.88l1.05 1.93c3 5.76 6.29 7.88 12.25 7.88h4.73c10.33 0 15.94 2.44 15.94 9.09 0 4.72-4.2 8.22-14.36 8.22a41.54 41.54 0 0 1-15.47-3.12c-6.49-3.88-11.74-3.5-15.6 3.12l-5.59 9.31c-3.72 6.13-3.19 11.72 2.63 15.94 7.71 4.69 20.38 9.44 37 9.44 34.16 0 48.5-22.75 48.5-44.12-.03-14.38-9.12-29.76-28.73-34.88zM496 224H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM16 160h64a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H64V40a8 8 0 0 0-8-8H32a8 8 0 0 0-7.14 4.42l-8 16A8 8 0 0 0 24 64h8v64H16a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8zm-3.91 160H80a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8H41.32c3.29-10.29 48.34-18.68 48.34-56.44 0-29.06-25-39.56-44.47-39.56-21.36 0-33.8 10-40.46 18.75-4.37 5.59-3 10.84 2.8 15.37l8.58 6.88c5.61 4.56 11 2.47 16.12-2.44a13.44 13.44 0 0 1 9.46-3.84c3.33 0 9.28 1.56 9.28 8.75C51 248.19 0 257.31 0 304.59v4C0 316 5.08 320 12.09 320z"></path>
                    </svg>
                    <h3 class="text-secondary pb-2">Automated reminders</h3>
                    <p>Automated payment reminders created & sent as per a customizable schedule that suits your needs.</p>
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
                <h2 class="text-white">Power your business with a comprehensive invoicing solution
                </h2>
              
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
                            <h4>Is the Swipez invoicing software free?</h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, You can use all our features for free. We only charge the lowest online payment <a href="{{ config('app.APP_URL') }}payment-gateway-charges"> transaction fee</a> for payments that you collect.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4>Is there a limit to the number of customers I can add to Swipez’s invoicing software?</h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, there is no limit on the number of customers you can add to any of our plans.
                        </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Is my data and my invoices safe with Swipez’s invoicing solution?
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We at Swipez value our customer’s privacy above all else. Merchants, e-commerce sellers, and businesses rely on us for their invoicing & payment collections. And, we take that responsibility and our duty of care towards them very seriously. The security of our software, systems, and customer data are our number one priority.
<br/><br/>
                                Every piece of information that is transmitted between your browser and Swipez is protected with 256-bit SSL encryption. This ensures that your data is secure in transit. All data you have entered into Swipez sits securely behind web firewalls. This system is used by some of the biggest companies in the world and is widely acknowledged as safe and secure.
                                
                            </div>
                        </div>
                    </div>


                    


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>Who can I reach out to for help related to invoicing?</h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                We have a dedicated team of experts who will be happy to assist you. You can reach out to us via email, chat, and our call center.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h4>Do I need to download anything to start using Swipez’s invoicing solution?</h4>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No, you don’t need to download any third-party software to start using the Swipez invoicing software.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h4>What is an invoice number? How do I assign a number to my invoices with Swipez?
                            </h4>

                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                An invoice number is used to uniquely identify an estimate/invoice to ease billing your customers. An invoice number can be numeric or alphanumeric value as per your requirements. You can add an invoice number manually each time you create an invoice. Or, create an invoice sequence and the invoices created with that sequence will have invoice number auto generated for them.
                            </div>
                        </div>
                    </div>
                    {{-- //rem --}}
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h4>Can I include TDS when creating an estimate?
                            </h4>

                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly. You can enable the deductibles plugin for your estimates. When your customer makes a payment, the TDS amount will be automatically deducted from the estimate amount. Moreover, once paid the estimate will automatically convert into an invoice. 

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h4>How do I notify my suppliers about changes in an invoice? </h4>

                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can enable the supplier plugin to ensure that your suppliers will be automatically and immediately notified of any changes in an invoice via email and/or SMS.

                            </div>
                        </div>
                    </div>
                 
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h4>Can I add a discount coupon with a fixed amount to my invoices?
                            </h4>

                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can add a discount coupon with a fixed amount. You can also create and add discount coupons with percentage (%) discounts to your invoices, as per your requirements.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEleven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h4>Can I CC more than one email address on an invoice? 
                            </h4>

                        </div>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Once you have enabled the CC plugin, you can add multiple email addresses as per your discretion. Email notifications will be created and sent to each of the emails automatically. 

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwelve">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                            <h4>Does the invoice total round off to the lowest value? </h4>

                        </div>
                        <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The round off plugin when enabled removes decimal points to the <b>nearest</b> value. For example, if the invoice total is ₹ 99.4 it will round off to ₹ 99. If the invoice total is ₹ 99.6 it will round off to ₹ 100.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThirteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                            <h4>Can I generate invoices on behalf of my franchises?
                            </h4>

                        </div>
                        <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly. You can generate invoices for your franchises, feature their name, contact information & more on the invoice. You can also automatically split payments with your franchise once the invoice has been paid.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFourteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                            <h4>Can I enable automated vendor payments on my invoices? </h4>

                        </div>
                        <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can automatically split invoice payments with one or more vendors. You can split the invoice amount among several vendors or pay the entire amount to one vendor, as per your requirements.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFifteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                            <h4>What are pre-paid invoices? What if the customer has paid only a portion of the invoice total in advance?
                            </h4>

                        </div>
                        <div id="collapseFifteen" class="collapse" aria-labelledby="headingFifteenn" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Pre-paid invoices are created when the customer has made an advance payment for services or products billed. With the pre-paid invoice plugin, you can generate a zero or adjusted value invoice for your customer based on the amount received and the invoice total.
                               <br/><br/> For example, if the Advance Received is ₹ 10,000 and the total cost of the particulars with applicable taxes is also ₹ 10,000, you are creating a zero value invoice for your customers. If the Advance Received is ₹ 10,000 and the total cost of the particulars with applicable taxes is also ₹ 12,000, you are creating an invoice for ₹ 2,000 that your customers need to pay. 
                               <br/> <br/> In the case of the former scenario, your invoice will not include payment options as the invoice total has been paid in full. In the second scenario, the pre-paid invoice will include payment options for the outstanding  ₹ 2,000 due.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSixteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                            <h4>Why are covering notes important for invoices?
                            </h4>

                        </div>
                        <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Covering notes provide a chance for you to thank your customer while also providing context for your invoice. The invoices are also linked within the covering note, allowing your consumers to read the invoice quickly and pay promptly.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeventeen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                            <h4>What are customer notifications? Can I personalize my customer notifications?
                            </h4>

                        </div>
                        <div id="collapseSeventeen" class="collapse" aria-labelledby="headingSeventeen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Customer notifications are emails and SMSs sent to your customers when invoices are created to inform them of the same. You can also send payment reminders for outstanding dues with online payment options to ensure prompt payments. 
                                You can personalize the notifications that will be presented to your customers to suit your brand image, convey the message you want it to, and more as per your requirements. You can also personalize the frequency at which notifications are generated & sent to your customers to suit your needs.
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEighteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                            <h4>How do I record and follow up on partial payment invoices?
                            </h4>

                        </div>
                        <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Good news you don’t have to. We’ll take care of it for you. Once you have enabled the partial payment plugin and assigned the minimum amount you'd like your customer to pay for your invoice, it remains open, allowing your customer to make repeated payments until paid in full.
                                <br/>Your customer's partial payments will be automatically detected and reported against the invoice. Both you and your customer will be able to see how many transactions have been made against an invoice, how much has been paid, and how much is still owed, in real time.
                                
                            </div>
                        </div>
                    </div>
                    {{-- remain --}}
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNineteen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                            <h4>Can I customize the frequency of auto-collection of recurring invoices?
                            </h4>

                        </div>
                        <div id="collapseNineteen" class="collapse" aria-labelledby="headingNineteen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly. You and your customers can choose the frequency at which dues will be auto collected for recurring invoices. For more details on the same, <a href="/getintouch/billing-software-pricing">get in touch</a>, and we will be happy to walk you through the process.
                            </div>
                        </div>
                    </div>

                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyoneadd">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyoneadd" aria-expanded="false" aria-controls="collapseTwentyoneadd">
                            <h4>Can I create subscriptions in bulk?</h4>
                        </div>
                        <div id="collapseTwentyoneadd" class="collapse" aria-labelledby="headingTwentyoneadd" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly. A simple excel upload is all it takes. You can specify details for each subscription like the mode of the subscription, frequency, due date, end date & more as per your requirements. Upload the filled excel sheet and recurring invoices will be automatically created and sent to the different customers for the subscriptions as per your specifications.
                            </div>
                        </div>
                    </div>


                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwenty">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                            <h4>Is there a limit when attaching files to invoices?</h4>

                        </div>
                        <div id="collapseTwenty" class="collapse" aria-labelledby="headingTwenty" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can attach any file, in any format you want to your invoices. You can upload pdfs, sheets, documents, images & more. They just have to be below 3 MB in size. 
                            </div>
                        </div>
                    </div>
                   <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyone">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyone" aria-expanded="false" aria-controls="collapseTwentyone">
                            <h4>How to add a digital signature to invoices? Why are digital signatures essential for online invoicing?
                            </h4>

                        </div>
                        <div id="collapseTwentyone" class="collapse" aria-labelledby="headingTwentyone" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You choose to upload a  PDF or Image file of your signature to start adding them to your invoices. Or, choose one of the customizable, Swipez generated variations of your signature to start adding them to your invoices. You can personalize the font, size, alignment & more of the signature to suit your needs.
<br/><br/>
                                All invoices uploaded on to the GST portal for GST filings must be digitally signed, in accordance to GST regulations. Digital signatures are essential for online invoicing, not just as a good business practice, but as an official government mandate.
                                  
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentytwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentytwo" aria-expanded="false" aria-controls="collapseTwentytwo">
                            <h4>Will the previous dues be calculated automatically or do I need to add it manually? </h4>

                        </div>
                        <div id="collapseTwentytwo" class="collapse" aria-labelledby="headingTwentytwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                If the previous due has been enabled, any outstanding dues of the customer for whom the invoice is being created will be automatically added to the invoice total. You can rest assured that manual intervention is not required to ensure accurate calculation of dues.   
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentythree">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentythree" aria-expanded="false" aria-controls="collapseTwentythree">
                            <h4>Can I disable online payments for my invoices?
                            </h4>

                        </div>
                        <div id="collapseTwentythree" class="collapse" aria-labelledby="headingTwentythree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you simply have to turn the toggle for online payments off and we will take care of the rest. The invoices/estimates your customers receive will not include online payment options.   
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyfour">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyfour" aria-expanded="false" aria-controls="collapseTwentyfour">
                            <h4>Can I customize the payment receipt received by my customers? </h4>

                        </div>
                        <div id="collapseTwentyfour" class="collapse" aria-labelledby="headingTwentyfour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can personalize your customer's payment receipts to suit your needs. You can add or remove information fields from the payment receipts to capture the information that you want to reflect in the receipts.  
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentyfive">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentyfive" aria-expanded="false" aria-controls="collapseTwentyfive">
                            <h4>Does Swipez offer GST e-invoicing? Can I upload GST e-invoices directly from Swipez? </h4>

                        </div>
                        <div id="collapseTwentyfive" class="collapse" aria-labelledby="headingTwentyfive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, Swipez provides a <a href="{{ config('app.APP_URL') }}e-invoicing">GST e-invoicing</a> solution that allows you to upload your electronic invoices straight to the Invoice Registration Portal (IRP). A unique Invoice Reference Number (IRN), a digital signature, and a QR code will be used to validate the invoices.
                                You can also bulk upload invoices via excel sheets to initiate GST e-invoicing. If you want to integrate the API you have generated directly from the IRP with the Swipez dashboard, you can do that too.
                                 
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwentysix">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwentysix" aria-expanded="false" aria-controls="collapseTwentysix">
                            <h4>How do I update my inventory on Swipez?
                            </h4>

                        </div>
                        <div id="collapseTwentysix" class="collapse" aria-labelledby="headingTwentysix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Good news you don’t have to. We’ll take care of it for you. Once you generate an invoice, your inventory will be automatically updated in real-time.   
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
