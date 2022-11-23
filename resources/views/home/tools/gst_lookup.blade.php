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
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="">
                    <div class="card1">
                        <div class="card-body mb-2 pb-2">
                            <h1 style="margin-top: 10px;;">GST filing information lookup</h1>
                            <p>View GST filing information of any GST number at a click of a button. A one-click
                                verification of any GSTIN, the GSTIN/UIN holder's name, address, status, and more.
                                Ensure error-free input tax credit with accurate GST filing lookup.
                            </p>
                            <form class="form-inline" action="" method="post" role="form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group" style="width: 100%;">
                                            @php
                                            $vls='';
                                            if(isset($gstno))
                                            $vls=$gstno;

                                            @endphp
                                            <input placeholder="Enter GSTIN" style="width: 100%;font-size: 17px;"
                                                type="text" required="" name="gst_number" maxlength="15"
                                                pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}"
                                                title="Enter Valid GST number" class="form-control " value="{{ $vls}}">
                                        </div>
                                    </div>
                                    <input type="hidden" id="captcha1" name="recaptcha_response"
                                        value="03AGdBq27mFAEQ5bAS-5AgLcd05MTOA0KsLDWXu8E7rUGJCcPaYnWEnX5gNY5A9fYg6NWdRPb7XnCkX5I4UZ3hbrOK_qoyDWIueRIBTbZ96vOXjrrRakGEVqYhoshWEC25t5Dvk2rIkcsLx5678C4aldoV-lGAU0EGC7x6afmVSYRlbHAshOuQx9LMQNagpaFYB4qOuQsQ49RzeZFgI-Z4RKShLrMrEFOg8eV4BqSr10p_g6X7jD5G8EXJ0y2qcDaU-JPxKX9jEt-sUCDU3DNSpyVWMFpUQdfmAZOxsJZ4QhbsY6ZkZ6YAt1y757tir9ufM1fZnBeWWumkRzawwhL_UzhKUcHEycL0Xi2RwQTpou7ansi5nTIHtPTkMe8i2ZOkEW3NzHtXX21FpjKAHjoqGzEg5osMmu6jAV4m0an4ahpF53cpqBEM1G6KnQvJWdkuYVcwY1hh4bRCvhn-owdU-eA7IPRnA3BX1_ZT-l6wWqXTV7Aze0iMbGO-FaR7HIot3KBEkwt5TcYXUIpN9KoI-lIpBnB0rFJvdA">
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-primary pull-right" value="Get details">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</section>
@if (isset($info))


<section class="jumbotron bg-transparent py-5" style="padding-top: 0px !important;">
    <div class="container">
        <div class="col-md-12">
            @if ($filereturn_success!='')
            <div class="alert alert-block alert-info ml-0 mr-0">
                <p style="margin-bottom: 0;">{{$filereturn_success}}</p>
            </div>
            @endif
            @if ($filereturn_error!='')
            <div class="alert alert-block alert-danger ml-0 mr-0" style="border: 1px solid red;">
                <p style="margin-bottom: 0;">{{$filereturn_error}}</p>
            </div>
            @endif
            @if (!isset($info['error']['message']))
            <div class="form-section">
                <h4>GSTN Info</h4>
            </div>
            <div class="card1">
                <div class="card-body">
                    <div class="row">

                        <p></p>
                        <div class="col-md-6">
                            <p><b>Name of business :</b>

                                @if($info['tradename']!='')
                                {{ $info['tradename']}}
                                @else
                                {{$info['name']}}
                                @endif </p>
                            <p><b>Location :</b> {{$info['pradr']['loc']}}</p>

                            <p><b>Tax payer type :</b> {{$info['type']}}</p>
                            <p><b>Status :</b> {{$info['status']}}</p>
                            <p><b>Tax return status :</b> {{$info['status']}}</p>
                            <p><b>Principle place of business
                                    :</b>{{$info['pradr']['bno']}},{{$info['pradr']['st']}},{{$info['pradr']['loc']}},{{$info['pradr']['stcd']}}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Constitution of business :</b> {{$info['constitution']}}</p>
                            <p><b>Nature of business :</b> {{$info['nature']}}</p>
                            <p><b>State jurisdiction :</b> {{$info['state']}}</p>
                            <p><b>Center jurisdiction :</b> {{$info['center']}}</p>
                            <p><b>Date of registration :</b>
                                {{$info['registrationDate']}}
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-section mt-4 mb-2">
                <h4>GSTR3B filing status</h4>
            </div>
            <div class="card1">
                <div class="card-body">
                    <div class="row no-margin">
                        <div class="col-md-12" style="overflow: auto;max-height: 400px;">
                            <div class="">
                                <table class="table table-bordered table-no-export_class">
                                    <thead>
                                        <tr>
                                            <th style="display:none;">#</th>
                                            <th>Date of filing</th>
                                            <th>Return period</th>
                                            <th>Return type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $v)

                                        @if($v['rtntype']=='GSTR3B')
                                        <tr>
                                            <td class="td-c" style="display:none;">{{date('Y-m-d',
                                                strtotime($v['dof']));}}
                                            </td>
                                            <td class="td-c">{{date('Y-M-d', strtotime($v['dof']));}}</td>
                                            <td class="td-c">
                                                {{ $datarray[substr($v['ret_prd'],0,2)];}}-{{substr($v['ret_prd'],2,5)}}
                                            </td>
                                            <td class="td-c">{{$v['rtntype']}}</td>
                                            <td class="td-c">{{$v['status']}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section mt-4 mb-2">
                <h4>GSTR1 filing status</h4>
            </div>
            <div class="card1">
                <div class="card-body">
                    <div class="row no-margin">
                        <div class="col-md-12" style="overflow: auto;max-height: 400px;">
                            <div class="">
                                <table class="table table-bordered table-no-export_class">
                                    <thead>
                                        <tr>
                                            <th style="display:none;">#</th>
                                            <th>Date of filing</th>
                                            <th>Return period</th>
                                            <th>Return type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($details as $v)

                                        @if($v['rtntype']=='GSTR1')
                                        <tr>
                                            <td class="td-c" style="display:none;">{{date('Y-m-d',
                                                strtotime($v['dof']));}}
                                            </td>
                                            <td class="td-c">{{date('Y-M-d', strtotime($v['dof']));}}</td>
                                            <td class="td-c">
                                                {{ $datarray[substr($v['ret_prd'],0,2)];}}-{{substr($v['ret_prd'],2,5)}}
                                            </td>
                                            <td class="td-c">{{$v['rtntype']}}</td>
                                            <td class="td-c">{{$v['status']}}</td>
                                        </tr>
                                        @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div>
</section>
@endif
{{--
<hr>
<section class="jumbotron bg-transparent py-5">
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
                <h2 class="text-white"> Hassle-free online GST lookup & verification </h2>
                <p class="text-white"> Used and trusted by 25000+ businesses across the nation</p>
            </div>
        </div>
    </div>
</section>






<section class="jumbotron py-5 bg-transparent" id="steps">
    <div class="container">
        <h2 class="text-center pb-5"> GST lookup & verification in <b class="text-primary">2</b> simple steps</h2>
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
                            <h2 class="card-title">Enter GSTIN</h2>
                            <p class="card-text">Add a valid GST number in the search box to run verification for the
                                same.
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
                            <h2 class="card-title">Get details
                            </h2>
                            <p class="card-text">Receive a comprehensive report on the GSTIN with details like address,
                                date of registration, GSTIN status / UIN status, GSTR3B filing status, GSTR1 filing
                                status, and more.
                            </p>
                        </div>
                    </div>
                </div>
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
                <img alt="Validate GST using lookup" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-lookup.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Validate GST using lookup" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-lookup.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Validate GSTIN</strong></h2>
                <p class="lead">Verify the authenticity and validity of the GSTIN provided by your suppliers/vendors.
                </p>
                <ul class="list-unstyled">
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg> GSTIN or GST number structure</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg> GSTIN authenticity & validity </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN payer type </li>
                    <li> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN return status </li>

                </ul>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Validate GSTIN</strong></h2>
                <p class="lead">Verify the authenticity and validity of the GSTIN provided by your suppliers/vendors.
                </p>
                <ul class="list-unstyled">
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg> GSTIN or GST number structure</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg> GSTIN authenticity & validity </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN payer type </li>
                    <li> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN return status </li>

                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Complete GST reports" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-reports.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Complete GST reports" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-reports.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Comprehensive GSTIN reports</strong></h2>
                <p class="lead">Get a snapshot view of all the pertinent details related to the GSTIN</p>
                <ul class="list-unstyled">
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>The legal name of the business</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Principal Place of Business </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Constitution of business – company, sole-proprietor, or partnership </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Nature of business</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>State Jurisdiction</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Center Jurisdiction</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Date of registration</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Type– regular taxpayer or composition dealer</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN status / UIN status</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Date of Cancellation (if applicable)</li>

                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Comprehensive GSTIN reports</strong></h2>
                <p class="lead">Get a snapshot view of all the pertinent details related to the GSTIN
                </p>
                <ul class="list-unstyled">
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>The legal name of the business</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Principal Place of Business </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Constitution of business – company, sole-proprietor, or partnership </li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Nature of business</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>State Jurisdiction</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Center Jurisdiction</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Date of registration</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Type– regular taxpayer or composition dealer</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTIN status / UIN status</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>Date of Cancellation (if applicable)</li>

                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="GST filing status" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-filing-status.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="GST filing status" class="img-fluid"
                    src="{!! asset('images/product/gst-filing/features/gst-filing-status.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>GSTIN filing report

                    </strong></h2>
                <p class="lead">Receive accurate reports on the GSTIN’s filing status with the GST portal.</p>
                <ul class="list-unstyled">
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg> GSTR3B filing status</li>
                    <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1"
                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>GSTR1 filing status</li>

                </ul>

            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>GSTIN filing report
                    </strong></h2>
                <p class="lead">Receive accurate reports on the GSTIN’s filing status with the GST portal. </p>
                <ul>
                    <ul class="list-unstyled">
                        <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                </path>
                            </svg> GSTR3B filing status</li>
                        <li><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                                class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                </path>
                            </svg>GSTR1 filing status</li>

                    </ul>


                </ul>
            </div>
            <!-- end -->
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
                            <h4>How is a GSTIN allocated? </h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The GST portal assigns a 15-digit GSTIN to the applicant/ business once the GST
                                registration application has been processed successfully.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4> Is it necessary to include the GSTIN on all invoices? </h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, a taxpayer must include his/her GSTIN on all invoices. Additionally, the GST
                                registered individual/business must present the GST registration certificate at all
                                company locations.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h4>Can the company address be verified from GSTIN?
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly, the GSTIN number can be used to locate the company's address. It is one of
                                the key details in the report you will receive once you run a search on a valid GSTIN.

                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h4>Can you check whether the GSTIN is active or not? </h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Certainly, the GSTIN number can be used to verify a GSTIN’s status. It is one of the key
                                details in the report you will receive once you run a search on a valid GSTIN.

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
