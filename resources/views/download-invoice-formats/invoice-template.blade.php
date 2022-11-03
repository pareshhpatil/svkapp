@extends('home.master')

@section('title', 'Swipez Payouts allows a business disburse bulk payments instantly to any bank account, UPI ID, debit
cards and different digital wallets.')

@section('content')
<script src="/static/js/carousel-jquery-1.9.1.min.js"  crossorigin="anonymous"></script>
<script src="/static/js/carousel-bootstrap.js" async=""></script>
<link rel="stylesheet" href="/static/css/carousel.css?version=1647678767">
<section class="jumbotron bg-transparent py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 class="mb-4">Invoice Templates</h1>
                <p class="lead text-left">Whether you’re into utilities, fashion, shipping, retail, IT, or even finance, Swipez understands the importance of providing you with a professional yet easy to use invoice template for clients.
                </p>
                <p class="lead text-left">One of the most unique things about Swipez invoice templates is that you can add your own company’s logo and brand colours, so that your invoice reflects your business branding.</p>
                 <p class="lead text-left">This will help you gain a competitive edge amongst your peers and recall value with your clients. Reduce manual invoice template creation effort with Swipez free invoice templates and enable your business to deliver a broader range of payment and billing arrangements. </p>
           
                 <div class="row justify-content-center">
                    <h2 class="display-5 mt-4 mb-4">Download Free Invoice Templates</h2>
                    <div class="card-deck">
                        <!-- 1st row -->
                        <div class="card">
                            <div class="pt-3">
                                <h2 class="card-title">
                                    <center>Excel format</center>
                                </h2>
                                <img class="card-img-top" src="{!! asset('downloads/excel-invoice-format.png') !!}" alt="Create or download sales invoice format">
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('home.gstbillformat') }}" class="btn btn-primary mb-sm-2" title="Download GST bill format">Create now</a>
                                <button data-target="#basic" id="downloadGSTBill" data-toggle="modal" onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2" title="Download excel invoice template">Download format</button>
                                <a href="/downloads/Invoice-Template.xlsx" id="downloadGSTBillExcel" class="hidden"></a>
                                <a href="{{ route('home.excelinvoicetemplates') }}">Excel invoice template</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="pt-3">
                                <h2 class="card-title">
                                    <center>Word Format</center>
                                </h2>
                                <img class="card-img-top" src="{!! asset('downloads/word-invoice-format.png') !!}" alt="Create or download sales invoice format">
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('home.housinginvoiceformat') }}" class="btn btn-primary mb-sm-2" title="Create invoice online">Create now</a>
                                <button data-target="#basic" id="downloadProforma" data-toggle="modal" onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2" title="Download word invoice template">Download format</button>
                                <a href="/downloads/Invoice-Template.docx" id="downloadProformaExcel" class="hidden"></a>
                                <a href="{{ route('home.wordinvoicetemplates') }}">Word invoice template</a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="pt-3">
                                <h2 class="card-title">
                                    <center>PDF format</center>
                                </h2>
                                <img class="card-img-top" src="{!! asset('downloads/pdf-invoice-format.png') !!}" alt="Create or download sales invoice format">
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('home.ispinvoiceformat') }}" class="btn btn-primary mb-sm-2" title="Create estimate format online">Create now</a>
                                <button data-target="#basic" id="downloadEstimate" data-toggle="modal" onclick="downloadFile(this.id);" class="btn btn-outline-primary mb-sm-2" title="Download pdf invoice template">Download format</button>
                                <a href="/downloads/Invoice-Template.pdf" download id="downloadEstimateExcel" class="hidden"></a>
                                <a href="{{ route('home.pdfinvoicetemplates') }}">PDF invoice template</a>
                            </div>
                        </div>
                    </div>
                </div>
           
                </div>
        </section>

        
        <section class="jumbotron bg-transparent py-3">
            <div class="container">
                <div class="text-center">
                    <h2 class="display-5  mb-4">Customize invoice templates</h2>
            </div>
                <div class="row text-center mt-0 py-0">
                  
                    <div class="container">
                   
                        <div class="row ">
                          <div class="col-md-12 d-none d-none d-lg-block"> 
                            <div class="carousel slide  " id="theCarousel" data-ride="carousel">
                               
                          
              <!--Slides-->
              <div class="carousel-inner " role="listbox">
                                  <!--First slide-->
                                  @php 
                                  $count=0;
                                  @endphp
                                  @foreach ($templateList as $key=>$item)
                                  @if($item['design_name']!='isp')
                                  <div class="item @if($count==0) active @endif ">
                                    <div class="row">
                                  <div class="col-md-12 col-xs-4 d-flex align-items-stretch"> 
                                    <div class="card2 d-flex flex-column">
                                        <div class="row text-left align-items-center p-2">
                                            <div class="col-12 col-md-7 d-none d-lg-block justify-center items-center">
                                            <center>
                                            <img loading="lazy" style=" max-width: 436px; border: 0.5px solid lightgrey"  alt="Billing app dashboard example" class="img-fluid" src="{!! asset('/images/invoice-formats/'.$item['image']) !!}">
                                            </center>
                                            </div>
                                            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block ">
                                            <h2><strong>{{$item['title']}}</strong></h2>
                                            <p class="lead">{{$item['description']}}</p>
                                            </div>
                                            
                                           
                                            
                                            </div>
                                       
                                      </div>
                
                                    </div>
                 
                
                
                                    </div>
                                  </div>
        
                             @php
                                 $count++;
                             @endphp
        
                             @endif
                                @endforeach   
                            
                            
                            </div>
                              <div>
        <br/><br/>
                                <ul class="carousel-indicators">
        
                                    @for ($i=0; $i<$count;$i++)
                                    <li data-target="#theCarousel" data-slide-to="{{$i}}" @if($i==0) class="active" @endif></li>
                                    @endfor
                                      
                                </ul>
         </div>
                          </div>
                        </div>
                        {{-- Ipad & Mobile view --}}
        
                        <div class="col-md-12  d-lg-none">
                            <div class="carousel slide" id="theCarousel1" data-ride="carousel">
                                                   
                                              
                                  <!--Slides-->
                                  <div class="carousel-inner " role="listbox">
                                                      <!--First slide-->
                                    
          @php 
                                  $count1=0;
                                  @endphp
                                                      @foreach ($templateList as $key=>$item)
                                                      @if($item['design_name']!='isp')
                                    <div class="item @if($count1==0) active @endif">
                                        <div class="row">
                            
                                            <div class="col-md-12 col-xs-12 d-flex align-items-stretch"> 
                                                <div class="card d-flex flex-column ">
                                                    <h2 class="gray-600 plugin-p mt-2" ><strong>{{$item['title']}}</strong></h2>
                                                    <div class="container">
                                                      
                                                        <p class="lead pstyle">{{$item['description']}}Set up your custom payment page without the hassle of server setups, integrations, or technical know-how. Start accepting prompt payments online with customized no code payment pages.</p> 
                                                    </div>
                                                    <img class="card-img-top p-1" src="{!! asset('/images/invoice-formats/'.$item['image']) !!}" alt="Create invoices or estimates"  loading="lazy" class="lazyload" />
                                                   
                                                  </div>
                            
                                                </div>
                                        </div>
                                    </div>
                                    @php
                                    $count1++;
                                @endphp
                                @endif
                                    @endforeach
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
               
            {{-- </div>
        </section> --}}         
</div>
</section>

        <section class="jumbotron py-5 bg-secondary text-white" >
                <div class="container">

                    <div class="row text-center">


                      
              

                        <div class="row text-center align-items-center">
                            <div class="col-md-6">
                        <div class="col-md-12 ">
                            <h3 class="text-white">Get your customizable invoice templates today!<br/><br/> Register to know more</h3>
                        </div>
                            </div>
                            <div class="col-md-6 ">
                        <div class="col-12 d-none d-sm-block ">
                            
                            
                            @include('home.product.web_register',['d_type' => "web"])
                           
                        </div>
                        <!-- for iPad and mobile -->
                        <div class="col-12 d-sm-none" style="margin-top: 20px;">
                            <div class="row justify-content-center">
                                @include('home.product.web_register',['d_type' => "mob"])
                               
                            </div>
                        </div>
                            </div>
                        </div>
                        <!-- end -->
                    </div>
                </div>
        </section>

<section class="jumbotron bg-transparent mt-4 py-2 ">
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">What is an invoice?</h2>
                <p class="lead text-left">In the simplest terms, an invoice is an itemized list of items or services you give to your customer after the product or service has been shipped, but before it is charged indicating how much the customer has to pay, and other payment related terms.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">Why Is An Invoice So Important For Your Business?</h2>
                <p class="lead text-left"> Well, if you’ve provided a service or delivered a product, you need to get paid on time!
                </p>
                <p class="lead text-left">Any invoice depicts a legal document agreed between your company and your customer as you record the service / product offered and the payment required to be made.
                </p>
                <p class="lead text-left">This also allows you to keep track of revenue and control finances. Invoices offer useful information on how your revenues are changing over time and can help you make precise revenue projections.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">What Is An Invoice Template?</h2>
                <p class="lead text-left">Every time you create an invoice for clients, you should include these necessary details in your invoice template.
                </p>
                <h4 class="text-left">Invoice date and Invoice due date</h4>
                <p class="lead text-left">This gives your customer a deadline for how long they have to pay you. Other details include the date it was sent, due date for payment etc.
                </p>
                <h4 class="text-left">Name and Address</h4>
                <p class="lead text-left">Include your name, address, other contact details and details of your business in your invoice template. This makes it easy for your customer to communicate with you if they have a query.
                </p>
                <h4 class="text-left">Terms</h4>
                <p class="lead text-left">Whether you have agreed on monthly or a bi weekly payment schedule, your invoice template has to include your terms and conditions to avoid any communication issues which can lead text-left to outstanding payments, or missed due dates.
                </p>
                <p class="lead text-left">The shorter the payment terms and conditions, the easier it will be for your customer to understand and thus quicker the payment.
                </p>
                <h4 class="text-left">What Are You Charging Them For?</h4>
                <p class="lead text-left">Details like the price of each product or service offered, applicable tax and other details justifying the individual amount for each item should be included in your invoice template. This helps the customer to get a clear picture of what they are paying for.
                </p>
                <h4 class="text-left">Invoice Number</h4>
                <p class="lead text-left">This is important for you to understand how many invoices have been issued, which payments have been cleared, which are due etc. Include a unique invoice number in your invoice template for each invoice to avoid any confusion.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">Special purpose templates</h2>

                <p class="lead text-left ">
                <ul class="text-left ">
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.gstbillformat') }}"> GST bill format</a></h4> Create GST bill for your business. Add your GST number, clients GST number, sale items in this easy to use GST invoice format. Download our GST bill format and edit using excel and send it your clients. Send a professional and detailed GST bill to your clients. Alternatively create your GST bill online using the GST bill generator tool using the Create now option.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.proformainvoice') }}"> Proforma invoice</a></h4> Proforma invoices are sent to a customer before delivery of good or service. Create proforma invoices using the free to download and use excel based proforma format. Add your GST number, clients GST number, sale items and taxes in this proforma invoice format. Send a professional and detailed proforma invoice to your clients.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.estimate') }}"> Estimate format</a></h4> Estimates are sent to a client to give them a sense of cost before delivery of goods or service. Create estimates using the free to download and use excel based estimate format. Add your company details, sale items and taxes in this estimate format. Send a professional and detailed estimate to your clients.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.salesinvoiceformat') }}"> Sales invoice format</a></h4> If you are a manufacturer, supplier, stockist, or reseller; you can create and download invoices easily with this sales invoice format. Easily add SAC codes, rates, and GST against the items on your sales invoice.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.ispinvoiceformat') }}"> ISP invoice format</a></h4> This invoice is perfectly suited for ISPs or Internet Service Providers. Using this invoice, you can easily show customers internet plan, data usage, duration and cost in a professional invoice format.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.cableinvoiceformat') }}"> Cable TV invoice format</a></h4> This invoice format is specially customized for Cable TV Operators. Create and send digital invoices to your customers mentioning the cable packages, time period and cost per package or channel.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.travelticketinvoiceformat') }}"> Travel ticket booking invoice format</a></h4> Travel ticket booking invoice format is created to fit the needs of tour and travel operators. With separate sections for booking and cancellation of tickets, you can send detailed digital invoices that look professional.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.travelcarinvoiceformat') }}"> Travel car and transport booking invoice format</a></h4> This ready to fill invoice format is specially designed for travel car and transport booking services. Enter the type of transport availed, its duration, charges and your invoice is good to go!
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.housinginvoiceformat') }}"> Housing society invoice format</a></h4> This easy to use and detailed invoice format for housing societies is perfect if you need to add one or many line items to your society bill. Add taxes where applicable and create your housing society bill as per your requirement. Use this invoice format to create and send digital invoices to the members of your housing society.
                    </li>
                    <li>
                        <h4 class="mt-2"><a href="{{ route('home.consultantinvoiceformat') }}"> Consultant & freelancer invoice format</a></h4> Easy to create invoice format for consultants, freelancers, and service companies so you can create invoices as good as your services. You can easily mention the details like the service provided, rate, hours, SAC code, and the final amount.
                    </li>

                </ul>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">How to Make an Invoice?</h2>
                <p class="lead text-left "> There are 2 ways:
                </p>
                <p class="lead text-left ">1) You can manually make an invoice on your own, each time a client transacts with you.
                </p>
                <p class="lead text-left"> OR</p>
                <p class="lead text-left ">2) Check out the plethora of free professional templates on Swipez. Get started with your <a href="{{ route('home.billing.feature.onlineinvoicing') }}">online invoicing</a> in minutes!
                </p>
                <p class="lead text-left "> With Swipez you can set up, customize and auto-generate invoices as per your business requirement, date calculations for bill date, due date, and expiry date, create pre-paid invoices, allow part payments of an invoice, add a cover note, digital signatures, customize messages and allow TDS deductions, in quick and, easy steps that grab peoples’ attention and initiate your consumers to swiftly take the desired action.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">What's the Difference Between a Bill and an Invoice?</h2>
                <p class="lead text-left">Although they may seem similar, in general, invoices and bills are both commercial records provided by a business detailing the products and services purchased by the consumer.
                </p>
                <p class="lead text-left">Bills are used for upfront payments, and are common in retail stores, restaurants etc. They are intended mainly as legal evidence for any sale.
                </p>
                <p class="lead text-left">On the other hand, an invoice is issued for services rendered or products delivered on a credit basis.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">Why Use An Invoice Template In Invoicing Software?</h2>
                <p class="lead text-left">The answer to that is simple, with Swipez free invoice templates, you can save time, money, manual effort, and more.
                </p>
                <p class="lead text-left">With Swipez invoice templates, you have the power to use ready-made, professional invoices for various industries. You can also customize the invoice as per your taste. It’s that easy, flexible, and economical.
                </p>
                <p class="lead text-left">Using invoice templates reduces the repetitive work, so your team saves time and can focus on other things. This also means that your customer receives their bill quickly and can pay you on time.
                </p>
                <p class="lead text-left">Still not convinced? Check out some of these benefits:
                </p>
                <h4 class="text-left">Quick Payment</h4>
                <p class="lead text-left">Starting to use a free invoice template is a great way to keep track of the data, time and date, and other transaction-related details enabling your customer to make payments faster. Use Swipez’ professional and structured free invoice templates and minimize human error, create more invoices and make better use of your accounting department.
                </p>
                <h4 class="text-left">They Serve as Reminders</h4>
                <p class="lead text-left">Invoices contain all of the necessary details, such as the date and time of the purchase, or the date of the transaction, serial number and customer codes which serve as a reminder of previous records. Swipez' free invoice template serves as reminders to your clients for a variety of purposes in various scenarios.
                </p>
                <h4 class="text-left">They Keep Track of Things</h4>
                <p class="lead text-left">Make life easy with Swipez’s wide range of professional invoice templates, save time, avoid mistakes and make better use of your accounts and operation teams other than preparing invoices. A win-win situation indeed.
                </p>
                <h4 class="text-left">Detail Oriented</h4>
                <p class="lead text-left">Use digital invoice templates to generate slips containing all of the information about the product or service you provide. Customize with Swipez’ easy to use invoice templates as per your industry and personal preference along with multiple format options like Word invoice template, Excel invoice template, and even PDF invoice formats.
                </p>

            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">Why Is It Important To Create And Send Professional Invoices?</h2>
                <p class="lead text-left">You've finished working on your client's project or shipped your goods and now twiddling your thumbs waiting for the payment from your client. Perhaps you and the client neglected to negotiate payment terms before the start of the project, and now it’s up to you to present them with a payment invoice.
                </p>
                <p class="lead text-left">Sounds familiar?
                </p>
                <p class="lead text-left">Every business owner must have a proper, professional method of billing their customers, reminding them of the due amount, the purpose of payments, and the date the services are provided.
                </p>
                <p class="lead text-left">Professional invoice template is a must for the growth of your company because they ensure that you are paid on time and for what your product/services are worth.
                </p>
                <p class="lead text-left">Use Swipez’ free invoice template and build a strong professional relationship with your clients.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">So What Benefits Do Small Businesses Miss Out On If They Choose Not To Use an Invoice Template?</h2>
                <h4 class="text-left">Saves Time</h4>
                <p class="lead text-left">Save your time and energy and concentrate on scaling your company by using the simple and ready-to-fill invoice templates offered by Swipez.
                </p>
                <h4 class="text-left">Unique Designs</h4>
                <p class="lead text-left">Customize any of the free invoice templates from Swipez to match your company's requirement and save time and money.
                </p>
                <h4 class="text-left">Timely Payments</h4>
                <p class="lead text-left">The faster you send out your invoices, the faster your client pays you. Personalize Swipez’s professional invoice template according to your preference, send them to your clients and manage your payments better.
                </p>
                <h4 class="text-left">Consistency</h4>
                <p class="lead text-left">Use Swipez to design and create your invoices to produce a professional and eye-catching invoice every time. Your company will appear professional, competent, and authentic if your invoicing methods are consistent. Additionally, as your clients become more acquainted with your company’s invoice template, in turn helping your business get paid quicker.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">What are the Most Popular Types of Invoice templates?</h2>
                <p class="lead text-left">We all have the same 24 hours, it’s how you use them that makes the difference. When you use our free invoice templates to create an invoice, you’re assured that your accounting departments’ manual labour and time-consuming work is taken care of in a jiffy.
                </p>
                <p class="lead text-left">Check out our most popular invoice templates we have in store for you:
                <ul class="text-left">
                    <li><a href="{{ route('home.wordinvoicetemplates') }}">Word invoice template</a></li>
                    <li><a href="{{ route('home.excelinvoicetemplates') }}">Excel invoice template</a></li>
                    <li><a href="{{ route('home.pdfinvoicetemplates') }}">PDF invoice template</a></li>
                </ul>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 mb-4 text-left">Which Invoice Template Format Should I Use?</h2>
                <p class="lead text-left">As a thumb rule, use the invoice template that is easy for customers to understand.
                    <br>
                    As easy as this sounds, in reality, it isn’t…
                    <br>
                    We’ve laid down the pros and cons for you to get a better understanding (choose wisely).

                </p>
                <h4 class="text-left"><a href="{{ route('home.wordinvoicetemplates') }}">Microsoft Word or Google Docs</a></h4>
                <p class="lead text-left">Pros
                <ul class="text-left">
                    <li>Widely used.</li>
                    <li>Makes the invoice look neat and attractive.</li>
                </ul>
                <p class="lead text-left">Cons
                <ul class="text-left">
                    <li>Adding minute details and making changes is a painful task.</li>
                    <li>Difficult to personalize an invoice template</li>
                    <li>Time consuming when creating an invoice </li>
                    <li>Not a user-friendly tool to make multiple invoices.</li>
                </ul>

                <h4 class="text-left"><a href="{{ route('home.excelinvoicetemplates') }}">Microsoft Excel</a></h4>
                <p class="lead text-left">Pros
                <ul class="text-left">
                    <li>Better than Microsoft word.</li>
                    <li>Can make calculations easy with formulas and macros.</li>
                    <li>Numbers are structured perfectly.</li>
                </ul>
                <p class="lead text-left">Cons
                <ul class="text-left">
                    <li>No room to add design or customize your invoice.</li>
                    <li>Very difficult to make the invoice look professional and eye-catching.</li>
                    <li>Time Consuming to create multiple invoices.</li>
                </ul>

                <h4 class="text-left"><a href="{{ route('home.pdfinvoicetemplates') }}">PDF template</a></h4>
                <p class="lead text-left">Pros
                <ul class="text-left">
                    <li> Print friendly and can be viewed by anyone in soft copy.</li>
                    <li> Invoice is neat and perfectly formatted.</li>
                    <li>Easy to share.</li>
                </ul>
                <p class="lead text-left">Cons
                <ul class="text-left">
                    <li>Calculations are not possible like in excel.</li>
                    <li>Not possible to make any changes.</li>
                    <li>Time Consuming to create multiple invoices.</li>
                </ul>



                <h4 class="text-left"><a href="{{ route('home.invoiceformats') }}"> Invoice Templates from Swipez</a></h4>
                <p class="lead text-left">Pros
                <ul class="text-left">
                    <li>Better than Microsoft Word and Excel.</li>
                    <li>Numbers are structured perfectly.</li>
                    <li>Invoice Templates customized according to various industry standards</li>
                    <li>Invoice details fits in 1 page for customer to act swiftly.</li>
                    <li>Templates are free to use.</li>
                    <li>Saves time, money and effort.</li>
                </ul>
                <p class="lead text-left">Cons
                <ul class="text-left">
                    <li>Let us know if you find any.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <p class="lead text-left">You can use Swipez invoice templates for your daily business needs. Simplify your and your customers’ lives and utilize Swipez free invoice templates for your business. Get started and kiss your invoice woes goodbye.
                </p>
                <p class="lead text-left">
                    CTA: Are you ready to create and send invoices to your customers? Click here to get started.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Popupsmart plugin-->
<script type="text/javascript" src="https://apiv2.popupsmart.com/api/Bundle/364244" async></script>
@endsection
