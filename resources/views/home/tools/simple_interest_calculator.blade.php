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
    .cnt_label{
        display: flex;
    text-align: center;
    align-items: center;
    margin-top: 8px;
    }
    </style>
<section class="jumbotron bg-transparent py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            
            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
              
              <center>  <h1> Simple interest calculator</h1>
                <p class="lead">{{$description}}</p></center>
            
            </div></div>
        <div class="row align-items-center justify-content-center">
            
            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
                <div class="card1 p-5">
                    <div class="row">
                      
                        <div class="col-lg-10 col-xl-12 mt-4 mt-xl-0">
                            <form name="calcform" autocomplete="off">

                                <div class="form-group row  mb-2"    >
                                    <label for="int_amt" class="col-sm-3 col-form-label cnt_label">Principal amount</label>
                                    <div class="input-group col-sm-8">
                                        <input id="prin_amt" name="prin_amt" type="number" class="form-control" >
                                        <input type="button" value="×" class="btn btn-outline-secondary" style="font-size:medium" onclick="OnClear(0)">
                                    </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label for="gst_rate" class="col-sm-3 col-form-label cnt_label">Annual interest %</label>
                                        <div class="input-group col-sm-8">
                                            <input id="anu_rate" name="anu_rate" type="number" min="0" max="100" class="form-control" >
                                        <span style="display: flex;align-items: center;padding-left:2px">% per year</span>
                                        </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <label for="gst_rate" class="col-sm-3 col-form-label cnt_label">Period</label>
                                            <div class="input-group col-sm-8">
                                                <input id="period" name="period" type="number" min="1" max="100" class="form-control" >
                                                &nbsp;&nbsp;
                                                <select id="Select2" class="form-control" name="Select2" style="font-size:medium">
                                                    <option value='0.0027'>Days</option>
                                                    <option value='0.0192'>Weeks</option>
                                                    <option value='0.083'>Months</option>
                                                    <option value='0.25'>Quarters</option>
                                                    <option value='1' selected="selected">Years</option>
                                                    </select> 
                                            </div>
                                            </div>
                                            <div class="form-group row mb-2">
                                                <label for="gst_rate" class="col-sm-3 col-form-label">&nbsp;</label>
                                                <div class="input-group col-sm-8">
                                                    <input onclick="OnCalc()" type="button" class="btn text-white bg-primary" value="Calculate" style="font-size:medium; width:120px; padding:5px">
                                                    &nbsp;
                                                    <input onclick="OnClear(1)" type="button" class="btn text-white bg-secondary" value="Clear" style="font-size:medium; width:120px; padding:5px">
                                                </div>
                                                </div>
                                                <div class="form-group row mb-2">
                                                    <label for="gst_rate" class="col-sm-3 col-form-label cnt_label">Interest amount</label>
                                                    <div class="input-group col-sm-8">
                                                        <input id="int_amt" name="int_amt" type="text" class="form-control" style="width:140px" readonly="readonly">   </div>
                                                    </div>
                                                    <div class="form-group row mb-2">
                                                        <label for="gst_rate" class="col-sm-3 col-form-label cnt_label">Total amount</label>
                                                        <div class="input-group col-sm-8">
                                                            <input id="total_amt" name="total_amt" type="text" class="form-control" style="width:140px" readonly="readonly">  </div>
                                                        </div>
                              
                                </form>
                        </div>
                   
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <hr>
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
                <h2 class="text-white">Accurate simple interest calculator</h2>
                <p class="text-white"> Estimate the interest on your savings & loans in seconds</p>
            </div>
        </div>
    </div>
</section>



<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">Calculate your simple interest in <b class="text-primary">3</b> simple steps</h2>
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
                           <h2 class="card-title"> Add calculation details
                        </h2> 
                            <p class="card-text"> Enter the principal amount you want to calculate the interest on along with the applicable rate of interest. </p>
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
                            <h2 class="card-title">Select Duration</h2>
                            <p class="card-text">Choose the time duration for which you want to calculate the simple interest on the principal amount. You can choose days, weeks, months, or years as per your requirements.</p>
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
                             <h2 class="card-title">Calculate interest amount</h2> 
                            <p class="card-text">Determine both the interest amount on your principal along with the total amount with interest.</p>
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
                                <b>Accurate calculations for your financial health</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Error-free simple interest calculations for longer time periods like long consecutive years.
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Easy insight into interest value as well as the estimated increase in your investments.

                                </li>
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
                                <b>Accurate calculations for your financial health
                                </b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Error-free simple interest calculations for longer time periods like long consecutive years.
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Easy insight into interest value as well as the estimated increase in your investments.
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Customizable time periods to accurately calculate your total investment, estimated total return, maturity time, and more to ensure informed financial decisions.</li>
                               
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
                            <h4>  How can a simple interest calculator inform my financial decisions? </h4>

                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You can accurately determine the interest rate on your principal amount along with the total return upon completion of tenure of a loan/savings plan.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> Can a simple interest calculator help me choose an investment? </h4>

                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                With a clear and error-free calculation of the interest amount of investments, a simple interest calculator can help you choose an investment based on your investment goals and risk tolerance.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4> Do interest rates change over time for simple interest? </h4>

                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                No. Unlike compound interest, interest rates on principal amounts do not change over time for simple interest.
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
    function OnClear(val)
    {
       if(0)
       {
        document.getElementById('prin_amt').value='';
       }else{
        document.getElementById('prin_amt').value='';
        document.getElementById('anu_rate').value='';
            document.getElementById('period').value='';
            document.getElementById('int_amt').value='';
            document.getElementById('total_amt').value='';
        }
    }
    function OnCalc(){
var pri_amt= document.getElementById('prin_amt').value;
if(pri_amt=='')
pri_amt=0;
var anu_per= document.getElementById('anu_rate').value;
if(anu_per=='')
anu_per=0;
var period= document.getElementById('period').value;
if(period=='')
period=0;
var yers= document.getElementById('Select2').value;

var p_y=eval(period)*eval(yers);   
       
var int_amt=(eval(pri_amt) * eval(p_y) * eval(anu_per)) / 100;

 var tamt=eval(pri_amt)+eval(int_amt);  
      
            document.getElementById('int_amt').value=int_amt.toFixed(2);
            document.getElementById('total_amt').value=tamt.toFixed(2);
          

       
    }
    </script>
@endsection
