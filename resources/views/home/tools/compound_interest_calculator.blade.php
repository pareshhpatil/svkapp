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
    .input-group-text{
    display: flex;
    -ms-flex-align: center;
    align-items: center;
    padding: 0.47rem 0.75rem;
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
    .calc2 tr {
    line-height: 50px;}
    .card1{
        position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
    }
    </style>
<section class="jumbotron bg-transparent py-5" id="testimonials">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            
            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
              
              <center>  <h1>Compound interest calculator             </h1>
                 <p class="lead">{{$description}}</p> </center>
               
            </div></div>
        <div class="row align-items-center justify-content-center">
            
            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
                <div class="card1 p-5">
                    <div class="row">
                      
                        <div class="col-lg-10 col-xl-12 mt-4 mt-xl-0">
                            <form id="calcform" name="calcform" autocomplete="off">
                              
                                <div class="form-group mb-2">
                                <label for="Text1">Present value</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                {{-- <span class="input-group-text" id="cur1">Rs</span> --}}
                                </div>
                                <input type="number" min="0" step="any" id="Text1"  class="form-control" >
                                <div >
                                <button type="button" onclick="OnClear(0)" class="btn btn-outline-secondary">×</button>
                                </div>
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label for="Text2">Annual interest rate</label>
                                <div class="input-group">
                                <input type="number" step="any" id="Text2" class="form-control" placeholder="">
                                <div >
                                <span class="input-group-text">%</span>
                                </div>
                                <div class=" ml-1">
                                <select id="Select2" class="form-control">
                                <option>compounded monthly</option>
                                <option>compounded quarterly</option>
                                <option selected="">compounded yearly</option>
                                </select>
                                </div>
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label for="Text4">Period</label>
                                <div class="input-group">
                                <input type="number" min="1" step="any" id="Text4" class="form-control" placeholder="0">
                                <div class=" ml-1">
                                <select id="Select4" name="Select4" class="form-control">
                                <option>Days</option>
                                <option>Weeks</option>
                                <option>Months</option>
                                <option>Quarters</option>
                                <option selected="selected">Years</option>
                                </select>
                                </div>
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label for="Text3">Additional payments (optional)</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                {{-- <span class="input-group-text " id="cur2">Rs</span> --}}
                                </div>
                                <input type="number" step="any" id="Text3" placeholder="0" class="form-control">
                                <div >
                                <button type="button" onclick="OnClear(1)" class="btn btn-outline-secondary">×</button>
                                </div>
                                <div class=" ml-1">
                                <select id="Select3" class="form-control">
                                
                                <option selected="">monthly</option>
                                <option>quarterly</option>
                                <option>yearly</option>
                                </select>
                                </div>
                                </div>
                                </div>
                                <div id="addpaydiv" class="form-group mb-3">
                                <label for="addpay-start" class="row ml-0">Add payment at compounding period</label>
                                <div class="btn-group btn-group-toggle row ml-0" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                <input type="radio" name="addpay" id="addpay-start" checked=""> Start
                                </label>
                                <label class="btn btn-secondary" style="margin-left: 5px;">
                                <input type="radio" name="addpay" id="addpay-end"> End
                                </label>
                                </div>
                                </div>
                                <div class="form-group mt-4 mb-2">
                                <button type="button" onclick="OnCalc()" class="btn btn-secondary"><span>=</span> Calculate</button>
                                <button type="button" onclick="OnClear(2)" class="btn btn-secondary"><span>×</span> Reset</button>
                                </div>
                                <div class="form-group mb-2">
                                <label>Future value</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                {{-- <span class="input-group-text" id="cur5">Rs</span> --}}
                                </div>
                                <input type="text" id="Text7" class="form-control" readonly="">
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label>Total deposits amount</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                {{-- <span class="input-group-text" id="cur3">Rs</span> --}}
                                </div>
                                <input type="text" id="Text5" class="form-control" readonly="">
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label>Total interest amount</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                {{-- <span class="input-group-text" id="cur4">Rs</span> --}}
                                </div>
                                <input type="text" id="Text6" class="form-control" readonly="">
                                </div>
                                </div>
                                <div class="form-group mb-2">
                                <label>Total yield</label>
                                <div class="input-group">
                                <input type="text" id="Text8" class="form-control" readonly="">
                                <div >
                                <span class="input-group-text">%</span>
                                </div>
                                </div>
                                </div>
                                {{-- <div class="form-group" style="display: none;">
                                <label>Final future value</label>
                                <div id="piechart" style="width:100%; height: 200px;"></div>
                                </div> --}}
                                {{-- <div class="form-group" style="display: none;">
                                <label>Future values by time</label>
                                <div id="columnchart" style="width:100%; height: 200px;"></div>
                                </div> --}}
                                <br/>
                                <div id="tbldiv" class="form-group" style="display: none;">
                                <label>Compound interest table</label>
                                <table class="table" id="tbl">
                                <tbody><tr>
                                <th>Period<br>No.</th>
                                <th>Payment</th>
                                <th>Balance</th>
                                </tr>
                                </tbody></table>
                                </div>
                                </form>
                        </div>
                   
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--<hr>
 <section class="jumbotron bg-transparent py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-2">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2>{{$title}}</h2>
                <p class="lead">{{$description}}</p>
            </div>
        </div>
       
    </div>
</section> --}}
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h2 class="text-white">Error-free compound interest calculator</h2>
                <p class="text-white"> Estimate the interest on your investments in seconds to ensure an informed financial decision.</p>
            </div>
        </div>
    </div>
</section>




<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Calculate your compound interest in <b class="text-primary">4</b> simple steps</h2>
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
                           <h2 class="card-title">Initial investment value</h2> 
                            <p class="card-text">Add the initial amount of money you intend to invest as the principal amount for the calculation. </p>
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
                             <h2 class="card-title">Compound interest rate</h2> 
                            <p class="card-text">Enter the estimated annual rate of interest. Select the range of interest rate variance, i.e, compounded monthly, quarterly, or yearly.
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
                            <h2 class="card-title"> Time interval</h2>
                            <p class="card-text">Define the time frame for which you intend to invest, the time duration for the interest to accrue on your principal amount. Add the number of days, weeks, months, quarters, or years from the drop-down list. 
                            </p>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="row no-gutters pb-5 justify-content-center">
                <div class="col-sm-1 text-center flex-column d-sm-flex">
                    <h5 class="m-2">
                        <button type="button" class="btn btn-primary btn-circle">4</button>
                    </h5>
                    <div class="row h-100">
                        <div class="col border-right">&nbsp;</div>
                        <div class="col">&nbsp;</div>
                    </div>
                </div>
                <div class="col-sm col-md-6 py-2">
                    <div class="card card-border-none shadow">
                        <div class="card-body">
                             <h2 class="card-title">Estimate compound values                            </h2> 
                            <p class="card-text">Calculate the estimated future value of your investments with a comprehensive breakdown of  the total investment amount, the total interest amount accrued, and the final yield with the interest amount.


                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>





<section class="py-7 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row text-center justify-content-center pb-3">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h2 class="display-4">Features</h2>
                        
                    </div>
                </div>  </div>
            </div>
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>Accurate compound interest calculations for smart financial decisions</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Consistent & regular savings with significant growth as the compound interest snowballs with time.</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                   Error-free compound interest calculations for long term investment planning.
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Easy insight into interest value as well as the estimated increase in your investments.</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Customizable time periods to accurately calculate your total investment, estimated total return, maturity time, and more to ensure informed financial decisions.
</li>
                                
             
                            </ul>
                          
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Download an invoice as per your business requirement"
                                src="{!! asset('images/home/download-invoice-format.svg') !!}" width="640" height="360"
                                loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Download an invoice as per your business requirement"
                                src="{!! asset('images/home/download-invoice-format.svg') !!}" width="640" height="360"
                                loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Accurate compound interest calculations for smart financial decisions
                                </b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Consistent & regular savings with significant growth as the compound interest snowballs with time. </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Error-free compound interest calculations for long term investment planning.
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Easy insight into interest value as well as the estimated increase in your investments.</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Customizable time periods to accurately calculate your total investment, estimated total return, maturity time, and more to ensure informed financial decisions.

                                </li>
                                
                           
                       
                            </ul>
                           
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
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
                          <h4>  What is the difference between daily, monthly, yearly compounding? </h4>

                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                When looking for an investment opportunity that offers compound interest, consider how often the interest is compounded. You can choose from investment plans that accumulate interest daily, monthly, six-monthly, or annually.<br/><br/>
                                Compounding works best when the compounding interval is short. You can opt for an investment plan that offers daily interest, meaning your interest will be compounded every single day. So, an amount will be added to your initial investment.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> What does an effective annual interest rate mean? </h4>

                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The effective annual rate is the rate of interest you receive after compounding on your savings/investment. When interest is compounded, the effective annual rate rises above the nominal yearly rate. The greater the effective annual interest rate, the more interest is compounded within the year.

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
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
<script>
    "use strict"
    var depamount,intamount;
    var val,pay;
    window.onload=function() {
       $("#calcform>div:nth-child(n+12)").hide();
       document.getElementById("calcform").onkeypress = function(e) { if( e.keyCode==13 ) OnCalc(); };
       //OnCalc();
    };
    function OnClear(n)
    {
       if( n==0 )
       {
          document.getElementById("Text1").value = '';
          document.getElementById("Text1").focus();
       }
       else if( n==1 )
       {
          document.getElementById("Text3").value = '';
          document.getElementById("Text3").focus();
       }
       else
       {
          document.getElementById("Text1").value = '';
          document.getElementById("Text2").value = '';
          document.getElementById("Text5").value = '';
          document.getElementById("Text6").value = '';
          document.getElementById("Text7").value = '';
          document.getElementById("Text8").value = '';
          document.getElementById("Text1").focus();
       }
    }
    function OnCurrencyChange()
    {
       var c_look=['$','&pound;','&euro;','&#3647;','kr','L','t','&#8369;','R','R$','RM','R','Rs','&#8362;','z&#322;',''];
       var i = document.getElementById("Select1").selectedIndex;
       var c = c_look[i];
       document.getElementById("cur1").innerHTML = c;
       document.getElementById("cur2").innerHTML = c;
       document.getElementById("cur3").innerHTML = c;
       document.getElementById("cur4").innerHTML = c;
       document.getElementById("cur5").innerHTML = c;
    }
    function DrawTable(v,pay,ratio)
    {
      // var c_look=['$','&pound;','&euro;','&#3647;','kr','L','t','&#8369;','R','R$','RM','R','Rs','&#8362;','z&#322;',''];
      // var i = document.getElementById("Select1").selectedIndex;
       var c = 'Rs';//c_look[i];
       var value,p,n,f;
       $("#tbl").find("tr:gt(0)").remove();
       n = v.length;
       for(var i=0; i<n; i++) {
          p=0;
          f=(i+1)/ratio;
          //if( (i>0) && (f==Math.round(f)) )
          if( f==Math.round(f) )
             p=pay;
          p = numberWithCommas(p);
          value = numberWithCommas(v[i].toFixed(2));
          $('#tbl tr').eq(i).after("<tr>\
             <td>"+i+"</td>\
             <td>"+c+" "+p+"</td>\
             <td>"+c+" "+value+"</td>\
          </tr>");
       }
       $(".calc tr:nth-child(n+12)").show();
       $("#tbldiv").show();
    }
    function numberWithCommas(x)
    {
       return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function CompoundCalc(PV,i,dV,n1,n2,n,iaddpay)
    {
       //FV = PV*Math.pow((1+i/100),n);
       var f;
       //var v=[];
       var prevv=PV;
       var v=[prevv];
       if( iaddpay==0 ) v[0]=prevv+dV;
       //for(var k=0; k<n; k++)
       for(var k=1; k<=n; k++)
       {
          v[k]=prevv;
          f=(k+1)/n2;
          if( f==Math.round(f) && iaddpay==0 )
             v[k]+=dV;
          f=(k+1)/n1;
          if( f==Math.round(f) )
             v[k]=v[k]*(1+i/100.0);
          f=(k+1)/n2;
          if( f==Math.round(f) && iaddpay==1 )
             v[k]+=dV;
          prevv=v[k];
       }
       return v;
    }
    function OnCalc()
    {
       var x0 = document.getElementById("Text1").value;
       var rate = document.getElementById("Text2").value;
       var i1 = document.getElementById("Select2").selectedIndex;
       var x1 = document.getElementById("Text3").value;
       var i2 = document.getElementById("Select3").selectedIndex;
       var period = document.getElementById("Text4").value;
       var i3 = document.getElementById("Select4").selectedIndex;
       var addpayButtons = $("#addpaydiv input:radio[name='addpay']");
       var iaddpay = addpayButtons.index(addpayButtons.filter(':checked'));
       
       if( x0=='' ) x0='1000';
       if( x1=='' ) x1='0';
       if( rate=='' ) rate='4';
       if( period=='' ) period='10';
 
       var x0 = parseFloat(x0);
       var x1 = parseFloat(x1);
       if(x0==0) x0=x1;
       rate = parseFloat(rate);
       period = parseFloat(period);
       i1 = parseInt(i1);
       i2 = parseInt(i2);
       i3 = parseInt(i3);
       if( x1==0 ) i2=2;
 
       //var rlook=[52,12,4,1];
       var rlook=[12,4,1];
       var rlook4=[365,52,12,4,1];
       var n1 = rlook[i1];
       var n2 = rlook[i2];
       var n3 = rlook4[i3];
       period /= n3; // convert to years
       var period1 = period*n1;
       var period2 = period*n2;
       rate /= n1;
       var ratio=n2/n1;
       var maxperiod = period1>period2?period1:period2;
       var f1=12/n1;
       var f2=12/n2;
       if( (f1>=f2) && ((f1/f2)==Math.round(f1/f2)) ) { f1/=f2; f2=1; }
       else if( (f2>f1) && ((f2/f1)==Math.round(f2/f1)) ) { f2/=f1; f1=1; }
       var v = CompoundCalc(x0,rate,x1,f1,f2,maxperiod,iaddpay);
       var y2=v[v.length-1];
       var y0 = x0+x1*period2;
       var p = y2-y0;
       var total;
       if( y0>0 )
          total = y2/y0*100.0-100.0;
       else
          total = 0;
 
       y0 = y0.toFixed(2);
       p  = (Math.round(100*p)/100).toFixed(2);
       y2 = y2.toFixed(2);
       total = (Math.round(100*total)/100).toFixed(2);
 
       depamount=parseFloat(y0);
       intamount=parseFloat(p);
 
       y0 = numberWithCommas(y0);
       p  = numberWithCommas(p);
       y2 = numberWithCommas(y2);
       total = numberWithCommas(total);
 
       document.getElementById("Text5").value = y0;
       document.getElementById("Text6").value = p;
       document.getElementById("Text7").value = y2;
       document.getElementById("Text8").value = total;
 
       //google.charts.load('current', {'packages':['corechart']});
       val=v; pay=x1;
      // google.charts.setOnLoadCallback(drawChart);
 
       DrawTable(v,x1,f2);
       $("#calcform>div:nth-child(n+12)").show();
    }
    function drawChart()
    {
    //    var data = google.visualization.arrayToDataTable([
    //       ['Name', 'Amount'],
    //       ['Total deposites amount', depamount],
    //       ['Total interest amount',  intamount]
    //    ]);
    //    var options = {};
    //    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    //    chart.draw(data, options);
 
      var arr=[
         ['Time', 'Principal', 'Cumulative Interest', { role: 'annotation' } ]
       ];
       var p=val[0],interest=0;
       for(var i=0; i<val.length; i++) {
          if( i>0 ) {
             p+=pay;
             interest=val[i]-p;
             arr.push([i,p,interest,'']);
          } else {
             arr.push([i,p,0,'']);
          }
       }
       var data2 = google.visualization.arrayToDataTable(arr);
       var options2 = {
         //width: 600,
         //height: 400,
         legend: { position: 'top', maxLines: 3 },
         bar: { groupWidth: '75%' },
         isStacked: true,
       };
       var chart2 = new google.visualization.ColumnChart(document.getElementById("columnchart"));
       chart2.draw(data2, options2);
    }
 </script>
@endsection
