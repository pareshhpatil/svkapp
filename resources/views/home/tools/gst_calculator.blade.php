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
    .input-group-text {
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

    .card1 {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 0.25rem;
    }
</style>
<section class="jumbotron bg-transparent py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
                <center>
                    <h1>GST tax calculator</h1>
                    <p> Online GST tax calculator. Accurate GST tax calculations to ensure seamless billing & invoicing
                    </p>
                </center>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">

            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
                <div class="card1 p-5">
                    <div class="row">

                        <div class="col-lg-10 col-xl-12 mt-4 mt-xl-0">
                            <form id="calcform" name="calcform" autocomplete="off">

                                <div class="form-group row  mb-2">
                                    <label for="int_amt" class="col-sm-3 col-form-label">Initial amount</label>
                                    <div class="input-group col-sm-9">
                                        {{-- <span class="input-group-text" id="cur1">Rs</span> --}}
                                        <input id="int_amt" name="int_amt" type="text" class="form-control">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="OnClear()"><span>×</span></button>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="gst_rate" class="col-sm-3 col-form-label">Select GST Rate</label>
                                    <div class="input-group col-sm-9">
                                        {{-- <input id="gst_rate" name="gst_rate" type="text" class="form-control"
                                            value=""> --}}
                                        <select id="gst_rate" class="form-control" name="gst_rate"
                                            style="font-size:medium">
                                            <option value='5'>5%</option>
                                            <option value='12'>12%</option>
                                            <option value='18'>18%</option>
                                            <option value='28'>28%</option>

                                        </select>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="" class="col-sm-3 col-form-label">&nbsp;</label>
                                    <div class="input-group col-sm-9">
                                        <button type="button" title="Add GST" class="btn text-white bg-primary  mr-2"
                                            onclick="OnCalcGST(1)"><span>+</span> Add GST</button>
                                        <button type="button" title="Subtract GST" class="btn btn-secondary"
                                            onclick="OnCalcGST(0)"><span>-</span> Remove GST</button>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="net_amt" class="col-sm-3 col-form-label">Net amount</label>
                                    <div class="input-group col-sm-9">
                                        {{-- <span class="input-group-text" id="cur2">Rs</span> --}}
                                        <input id="net_amt" name="net_amt" type="text" class="form-control" readonly="">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="gst_amt" class="col-sm-3 col-form-label">GST amount</label>
                                    <div class="input-group col-sm-9">
                                        {{-- <span class="input-group-text" id="cur3">Rs</span> --}}
                                        <input id="gst_amt" name="gst_amt" type="text" class="form-control" readonly="">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="gross_amt" class="col-sm-3 col-form-label">Gross amount</label>
                                    <div class="input-group col-sm-9">
                                        {{-- <span class="input-group-text" id="cur4">Rs</span> --}}
                                        <input id="gross_amt" name="gross_amt" type="text" class="form-control"
                                            readonly="">
                                    </div>
                                </div>
                            </form>




                        </div>

                    </div>


                </div>
            </div>
        </div>

        <div class="row align-items-center justify-content-center mt-4">

            <div class="col-lg-8 col-xl-10 mt-4 mt-xl-0">
                <h4 class="display-2">GST tax calculation</h4>
                <p class="lead">GST amount = GST Rate(%) × Net amount</p>

                <p class="lead"> Gross amount = (100% + GST Rate(%)) × Net amount</p>
                <h4 class="display-2"> Example</h4>
                <p class="lead"> Net amount = 100</p>
                <p class="lead"> GST Rate = 5%</p>

                <p class="lead"> GST amount = 5%×100 = 5</p>

                <p class="lead"> Gross amount = (100%+5%)×100% = 105</p>
            </div>
        </div>
    </div>
</section>

{{-- <section class="jumbotron bg-transparent py-5">
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
                <h2 class="text-white">Error-free GST calculations</h2>
                <p class="text-white">A simple, ready-to-use online GST calculator used and trusted by 25000+ businesses
                    across India</p>
            </div>
        </div>
    </div>
</section>






<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5">GST calculations in <b class="text-primary">3</b> simple steps</h2>
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
                            <h2 class="card-title"> Enter initial amount</h2>
                            <p class="card-text">Add the base amount on which to calculate the GST on. </p>
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
                            <h2 class="card-title"> Select GST rate</h2>
                            <p class="card-text">Choose the GST rate from the drop-down list. </p>
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
                            <h2 class="card-title">Add/Remove GST</h2>
                            <p class="card-text">Add or remove GST amount from your base amount as per your
                                requirements. </p>
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
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>GST calculations that are on the mark. Every time!</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Add multiple items of sale</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Calculate multiple GST rates
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Add or remove GST rates</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Automated calculations of subtotals, totals, and taxes</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Add or remove GST amount from base amount
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    GST calculation on gross total amount

                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    GST calculation on net total amount


                                </li>
                            </ul>

                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Easy to use GST filing"
                                src="{!! asset('images/product/gst-filing/features/easy-gst-filing.svg') !!}" width="640" height="360"
                                loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Easy to use GST filing"
                                src="{!! asset('images/product/gst-filing/features/easy-gst-filing.svg') !!}" width="640" height="360"
                                loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>GST calculations that are on the mark. Every time!
                                </b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Add multiple items of sale </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>Calculate multiple GST rates
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Add or remove GST rates</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Automated calculations of subtotals, totals, and taxes
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Add or remove GST amount from base amount

                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    GST calculation on gross total amount

                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                        data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path fill="currentColor"
                                            d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    GST calculation on net total amount
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
                            <h4> What are the different GST collected? </h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The different GST rates applicable of different goods and services are-
                                <ul>
                                    <li><strong>SGST,</strong> State GST rate levied by the state government.</li>
                                    <li><strong>CGST,</strong> Central GST which is collected by the central government.
                                    </li>
                                    <li><strong>IGST,</strong> Integrated GST which is levied by the central government
                                        on interstate trade and import of goods/items of sale.</li>
                                    <li><strong>UTGST,</strong> Union Territory GST is levied by the government of an
                                        Union Territory.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> How is GST calculated? </h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The applicable GST rates are 5%, 12%, 18% and 28%, which differ for different goods &
                                services in compliance with GST regulations.<br />
                                <br />To calculate the GST on a base amount,<br />
                                &nbsp;&nbsp;&nbsp;&nbsp;1. Add all the GST rates applicable on the item of sale.<br />
                                &nbsp;&nbsp;&nbsp;&nbsp;2. GST Amount = ( Original Cost * GST% ) / 100<br />
                                &nbsp;&nbsp;&nbsp;&nbsp;3. Net Price = Original Cost + GST Amount<br />

                                <br />To remove GSt from base amount<br />

                                &nbsp;&nbsp;&nbsp;&nbsp;1. GST Amount = Original Cost – [Original Cost * {100 / (100 +
                                GST% ) }]<br />
                                &nbsp;&nbsp;&nbsp;&nbsp;2. Net Price = Original Cost – GST Amount

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4> What is an GST inclusive calculation? </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                GST inclusive amount refers to the total cost of a product after including the GST
                                amount to the original value. The tax is not added to the customer's bill separately.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4> Who can use the GST calculator? </h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                A buyer, manufacturer, or wholesaler can all utilize the GST calculator.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFive">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h4>What are some of the advantages of using the GST calculator? </h4>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                When calculating the overall cost of goods and services as well as the tax amount, the
                                GST calculator saves time and eliminates the chances of human error.
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
                <a data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal"
                    onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary"
                    href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
<script>
    function OnClear()
    {

        document.getElementById('int_amt').value='';
        document.getElementById('net_amt').value='';
            document.getElementById('gst_amt').value='';
            document.getElementById('gross_amt').value='';
    }
    function OnCalcGST(val){
var int_amt= document.getElementById('int_amt').value;
if(int_amt=='')
int_amt=0;
var gst_per= document.getElementById('gst_rate').value;
if(gst_per=='')
   gst_per=0;

        if(val==1)
        {
            var gst_amt=(int_amt/100)*gst_per;
            var  gross_amt=eval(gst_amt)+eval(int_amt);
            document.getElementById('net_amt').value=int_amt;
            document.getElementById('gst_amt').value=gst_amt.toFixed(2);
            document.getElementById('gross_amt').value=gross_amt.toFixed(2);

        }else{

            var gst_amt1= eval(int_amt)*gst_per/(100+eval(gst_per));
            var  gross_amt1=eval(int_amt)-eval(gst_amt1);
            document.getElementById('net_amt').value=gross_amt1.toFixed(2);
            document.getElementById('gst_amt').value=gst_amt1.toFixed(2);
            document.getElementById('gross_amt').value=int_amt;
        }
    }
</script>
@endsection
