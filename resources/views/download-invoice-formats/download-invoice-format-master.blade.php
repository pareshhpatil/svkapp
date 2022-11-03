@extends('home.master')

@section('content')
<link rel="stylesheet" href="{!! asset('static/css/fontawesome/all.min.css') !!}">
<link rel="stylesheet" type="text/css"
    href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" />
<link rel="stylesheet" type="text/css" href="/static/css/spectrum/spectrum.min.css">
<link rel="stylesheet" type="text/css"
    href="/static/css/downloadinvoiceformat.css{{ Helpers::fileTime('new','static/css/downloadinvoiceformat.css') }}">
<meta name="viewport" content="width=device-width,initial-scale=1">

<section class="jumbotron jumbotron-features bg-transparent" id="header">
    <div class="container">
        @if ($errors->any())
        <div class="alert alert-warning color-white" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <div class="row">

            <div class="col text-center pb-3">
                <h1>{{$title}}</h1>
                <center>
                    <p class="lead col-md-8">{{$description}}</p>
                </center>
                <h2 class="pt-2">
                    <center>{{ $downloadTitle ?? "Download GST bill format" }}</center>
                </h2>
                <center>
                    <p class="col-md-8">{{ $downloadText ?? "Download GST bill format. Edit the downloaded bill format and send to your
                        customers." }}</p>
                    <button data-target="#basic" id="downloadGSTBill" data-toggle="modal" onclick="downloadFile(this.id); setRegText(7);"
                        class="btn btn-outline-primary mb-sm-2" title="{{ $downloadButtonTitle ?? "Download GST bill format" }}">{{ $downloadButtonTitle ?? "Download GST bill format" }}</button>
                    <a href="/downloads/{{ $downloadFileName ?? "GSTBillFormat.xlsx" }}" id="downloadGSTBillExcel" class="hidden"></a>
                </center>
                <hr class="hr-text" data-content="OR">
                <h2 class="pt-2">
                    <center>{{ $creatorTitle ?? "Create GST bill online" }}</center>
                </h2>
            </div>
        </div>

        <form action="/export-invoice-formats" id="frm_format" method="post" enctype="multipart/form-data">

            <div class="row justify-content-center page-content">
                <div class="d-inline-flex img-shadow px-1">
                    <input type="hidden" name="format_name" value="{{$format_name}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="recaptcha_response" id="captcha1">
                    <div class="invoice">
                        <div class="row mx-0 mt-1 mb-4">
                            <!-- display for large screen sizes -->
                            <div class="col-md-12 bg-primary p-2 d-none d-lg-block color-white">
                                <p class="lead mb-0 text-white">Start creating your invoice now!
                                    <span class="pull-right">
                                        <button type="submit" onclick="gcaptchaReSet();" class="btn btn-sm btn-secondary" data-toggle="tooltip"
                                            data-placement="top" title="Download invoice PDF">
                                            <i class="fa fa-download"></i>
                                            Download
                                        </button>
                                        <button type="submit" id="print" name="print"
                                            class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                            data-placement="top" title="Print your invoice">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        <a href="javascript:" onclick="setRegText(3);"
                                            class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                            data-placement="top" title="Save invoice to your account">
                                            <i class="fa fa-save"></i>
                                        </a>
                                        <a href="javascript:" onclick="setRegText(4);"
                                            class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                            data-placement="top" title="Send invoice in Email">
                                            <i class="fa fa-envelope"></i>
                                        </a>
                                        <a href="javascript:" onclick="setRegText(5);"
                                            class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                            data-placement="top" title="Send invoice in SMS">
                                            <i class="fa fa-comment"></i>
                                        </a>
                                    </span>
                                </p>
                            </div>
                            <!-- display for large screen sizes end -->

                            <!-- display for small screen sizes -->
                            <div class="col-md-12 bg-primary p-2 d-lg-none">
                                <p class="lead mb-0 text-center text-white">Start creating your invoice now!</p>
                            </div>
                            <!-- display for small screen sizes end -->
                        </div>
                        <!-- invoice header section -->
                        <div class="row invoice-logo  no-margin mb-0">
                            <div class="col-md-4 py-1 pb-2" style="min-width: 150px;">
                                <div class="ml-1 col-sm-8 imgdiv d-print-none mb-2 text-center" id="imgdiv">
                                    <input type="file" name="img" id="imgg" class="file" accept="image/*">
                                    <div style="display: none;" id="btndiv">
                                        <button type="button"
                                            class="browse btn btn-sm btn-primary imgbtn">Change</button>
                                        <button type="button" class="remove btn btn-sm btn-primary imgbtn"
                                            style="margin-left: 5px;">&nbsp;&nbsp;<i style="margin-top: 3px;"
                                                class="fa fa-times"></i>&nbsp;&nbsp; </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <p style="text-align: left;">
                                    <input
                                        class="form-control form-control-plaintext form-control-sm company-name-text-box"
                                        required="" id="company_name" value="Type your company name here!"
                                        name="company_name" placeholder="Company name" onfocus="let value = this.value;
                                            this.value = null;
                                            this.value = value">
                                    <span class="h-contact" style=" margin-top:5px;"><textarea
                                            class="form-control form-control-plaintext form-control-sm" rows="2"
                                            name="merchant_address" placeholder="Your business address"
                                            style="height: 33px;resize: none;"></textarea> </span>

                                    <span class="h-contact">
                                        <div class="input-group"><b style="line-height: 2;">Contact:</b> <input
                                                class="form-control form-control-plaintext form-control-sm ml-2"
                                                name="merchant_mobile" placeholder="Your business contact number"></div>
                                    </span>
                                    <span class="h-contact">
                                        <div class="input-group"><b style="line-height: 2;">E-mail:</b> <input
                                                type="email"
                                                class="form-control form-control-plaintext form-control-sm ml-2"
                                                name="merchant_email" placeholder="Your email id"></div>
                                    </span>
                                    @if($format_name=='ticket booking' || $format_name=='car booking')
                                    <span class="h-contact">
                                        <div class="input-group"><b style="line-height: 2;">GST number:</b> <input
                                                class="form-control form-control-plaintext form-control-sm ml-2"
                                                maxlength="15"
                                                pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}"
                                                title="Enter Valid GST number" name="gstnumber"
                                                placeholder="Your GST number"></div>
                                    </span>
                                    @endif

                                </p>
                            </div>
                        </div>
                        <!-- invoice header section ends -->

                        <!-- color bar -->
                        <div class="row no-margin ">
                            <div class="col-md-12 bg-grey text-center p-1" id="bg1" style="font-size: 18px;">

                                <span><b>@isset($invoice_title) {{$invoice_title}} <input type="hidden"
                                            name="invoice_title" value="{{$invoice_title}}"> @else Tax Invoice <input
                                            type="hidden" name="invoice_title" value="Tax Invoice"> @endisset</b></span>
                                <i style="font-size: 18px;"
                                    class="fas fa-paint-brush gear pull-right d-print-none text-white"
                                    onclick="showcolor(1);" data-toggle="tooltip" data-placement="right"
                                    title="Change background color and text color"></i>

                                <div id="colorpckdiv1" style="display: none;">
                                    <div class="pull-right cpd">
                                        <p>Background color : <input name="bg_color" class="pull-right"
                                                onchange="changebg(this.value, 1);" id="color-picker" value='#5B5B5B' />
                                        </p>
                                        <p> Text color : <input name="text_color" class="pull-right"
                                                onchange="changecolor(this.value, 1);" id="color-picker2"
                                                value='#ffffff' />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- color bar ends -->

                        <!-- billing details section -->
                        <div class="row no-margin">

                            <div class="col-md-6 px-0">
                                <div class="" style="">
                                    <table class="table table-condensed mb-0">
                                        <tbody>
                                            <tr>
                                                <td td class="td-invoice_detail"><b>Customer code</b></td>
                                                <td class="no-border"><input
                                                        class="form-control form-control-plaintext form-control-sm"
                                                        name="customer_code" placeholder="Enter your customer's id">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail"><b>Customer name</b></td>
                                                <td class="no-border"><input
                                                        class="form-control form-control-plaintext form-control-sm"
                                                        name="name" required="" placeholder="Enter customer's name">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail"><b>Email ID</b></td>
                                                <td class="no-border"><input
                                                        class="form-control form-control-plaintext form-control-sm"
                                                        type="email" name="email"
                                                        placeholder="Enter customer's email id"></td>
                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail"><b>Mobile no</b></td>
                                                <td class="no-border"><input
                                                        class="form-control form-control-plaintext form-control-sm"
                                                        name="mobile" title="Enter your valid mobile number"
                                                        maxlength="12" pattern="^(\+[\d]{1,5}|0)?[1-9]\d{9}$"
                                                        placeholder="Enter customer's mobile no"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <div class="col-md-6 invoice-payment px-0">
                                <div class="">
                                    <table class="table table-condensed mb-0">
                                        <tbody>
                                            <tr>
                                                <td td class="td-invoice_detail">
                                                    <b>Bill date</b>
                                                </td>
                                                <td td class="td-invoice_detail">
                                                    <input
                                                        class="form-control  form-control-sm form-control-plaintext datepicker dp"
                                                        data-date-format="dd M yyyy" required=""
                                                        value="{{$current_date}}" autocomplete="off" name="bill_date"
                                                        id="bill_date" placeholder="Billing date">
                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail">
                                                    <b>Due date</b>
                                                </td>
                                                <td td class="td-invoice_detail">
                                                    <input
                                                        class="form-control form-control-sm form-control-plaintext datepicker dp"
                                                        data-date-format="dd M yyyy" required=""
                                                        value="{{$current_date}}" autocomplete="off" name="due_date"
                                                        id="due_date" placeholder="Enter due date">
                                                </td>

                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail">
                                                    <b>Invoice Number</b>
                                                </td>
                                                <td td class="td-invoice_detail">
                                                    <input class="form-control form-control-plaintext form-control-sm"
                                                        name="invoice_number" placeholder="Enter your invoice no">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td td class="td-invoice_detail">
                                                    <b>Customer GST</b>
                                                </td>
                                                <td td class="td-invoice_detail">
                                                    <input class="form-control form-control-plaintext form-control-sm"
                                                        maxlength="15"
                                                        pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}"
                                                        title="Enter Valid GST number" name="customer_gst"
                                                        placeholder="Enter customer's GST no">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- billing details section ends -->
                        @yield('content-format')
                        <div class="col-md-12 bg-primary p-2 d-none d-lg-block  color-white mb-1">
                            <p class="lead mb-0 text-white">Download PDF file of your invoice
                                <span class="pull-right">
                                    <button type="submit" onclick="gcaptchaReSet();" class="btn btn-sm btn-secondary" data-toggle="tooltip"
                                        data-placement="top" title="Download invoice PDF">
                                        <i class="fa fa-download"></i>
                                        Download
                                    </button>
                                    <button type="submit" id="print" name="print"
                                        class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                        data-placement="top" title="Print your invoice">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <a href="javascript:" onclick="setRegText(3);"
                                        class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                        data-placement="top" title="Save invoice to your account">
                                        <i class="fa fa-save"></i>
                                    </a>
                                    <a href="javascript:" onclick="setRegText(4);"
                                        class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                        data-placement="top" title="Send invoice in Email">
                                        <i class="fa fa-envelope"></i>
                                    </a>
                                    <a href="javascript:" onclick="setRegText(5);"
                                        class="btn btn-sm btn-outline-secondary" data-toggle="tooltip"
                                        data-placement="top" title="Send invoice in SMS">
                                        <i class="fa fa-comment"></i>
                                    </a>
                                </span>
                            </p>
                        </div>
                        <!-- particulars section for mobile -->
                        <div id="mobile" class="row">
                            <div class="col-md-12 ">
                                <div class="table-scrollable mr-2">

                                    <table class="table table-bordered table-condensed mb-1">
                                        <thead>
                                            <tr>
                                                <th class="tdb3" style="">
                                                    #
                                                </th>
                                                <th class="tdb3 tx-c" style="min-width: 140px;">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c bold"
                                                        name="description_label" value="Description">

                                                </th>
                                                <th class="tdb3 add-col" style="min-width: 100px;">
                                                    <div class="input-group"><input
                                                            class="form-control form-control-plaintext form-control-sm tx-c bold"
                                                            id="sac_code_label" name="sac_code_label"
                                                            placeholder="Add column name">
                                                        <a class="ml-2" onclick="removecol();" href="javascript:"><i
                                                                class="fa fa-times" data-toggle="tooltip"
                                                                data-placement="right" title="Remove column"></i></a>
                                                    </div>
                                                </th>
                                                <th class="tdb3 add-col2" style="min-width: 100px;">
                                                    <div class="input-group"><input
                                                            class="form-control form-control-plaintext form-control-sm tx-c bold"
                                                            id="time_period_label" name="time_period_label"
                                                            placeholder="Add column name">
                                                        <a class="ml-2" onclick="removecol('2');" href="javascript:"><i
                                                                class="fa fa-times" data-toggle="tooltip"
                                                                data-placement="right" title="Remove column"></i></a>
                                                    </div>
                                                </th>

                                                <th colspan="3" class="tdb3 tx-c" style="min-width: 100px;">
                                                    <div class="input-group"><input
                                                            class="form-control form-control-plaintext form-control-sm tx-c bold "
                                                            name="total_label" value="Absolute cost">
                                                        <a style="font-size: 18px;" onclick="addcol();"
                                                            title="Add new column" href="javascript:"><i
                                                                class="fa fa-columns"></i></a>
                                                    </div>
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="tdpr">
                                                    1
                                                </td>
                                                <td class="tdpr">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_description" required="" placeholder="Line item name">
                                                </td>
                                                <td class="tdpr add-col">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_sac_code" placeholder="Enter value">
                                                </td>

                                                <td class="tdpr add-col2">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_time_period" placeholder="Enter value">
                                                </td>

                                                <td colspan="3" class="tdpr">
                                                    <div class="input-group"><input
                                                            class="form-control form-control-plaintext form-control-sm tx-r"
                                                            name="p_cost" required="" type="number" step="0.01"
                                                            onblur="calculate();" id="p_cost"
                                                            placeholder="Enter amount">
                                                        <a style="font-size: 18px;" class="ml-2" onclick="addnewrow();"
                                                            href="javascript:"><i class="fa fa-plus-circle"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Add another line item"></i></a>
                                                    </div>

                                                </td>

                                            </tr>

                                            <tr id="newrow" style="display: none;">
                                                <td class="tdpr">
                                                    2
                                                </td>
                                                <td class="tdpr">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_description2" placeholder="Line item name">
                                                </td>
                                                <td class="tdpr add-col">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_sac_code2" placeholder="Enter value">
                                                </td>
                                                <td class="tdpr add-col2">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-c"
                                                        name="p_time_period2" placeholder="Enter value">
                                                </td>
                                                <td colspan="2" class="tdpr">
                                                    <div class="input-group"><input
                                                            class="form-control form-control-plaintext form-control-sm tx-r"
                                                            name="p_cost2" type="number" step="0.01"
                                                            onblur="calculate();" id="p_cost2"
                                                            placeholder="Enter amount">
                                                        <a style="font-size: 18px;" class="ml-2" onclick="removerow();"
                                                            title="Delete row" href="javascript:"><i
                                                                class="fa fa-minus-circle"></i></a>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>

                                                <td colspan="2" class="col-span td-firstcol tx-l border-right">
                                                    <b class="tx-l">Sub Total</b>
                                                </td>
                                                <td colspan="3" class="col-span">
                                                    <b> <input
                                                            class="form-control form-control-plaintext form-control-sm tx-r"
                                                            id="sub_total" name="sub_total" readonly=""
                                                            value="00.00"></b>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td colspan="2" class="col-span">
                                                    <select style="min-width: 90px;" placeholder="GST" name="tax_name1"
                                                        onchange="calculate();" id="tax_name1"
                                                        class="form-control form-control-plaintext form-control-sm">
                                                        <option value="">Not applicable</option>
                                                        <option value="CGST@2.5%">CGST@2.5%</option>
                                                        <option value="SGST@2.5%">SGST@2.5%</option>
                                                        <option value="IGST@5%">IGST@5%</option>
                                                        <option value="CGST@6%">CGST@6%</option>
                                                        <option value="SGST@6%">SGST@6%</option>
                                                        <option value="IGST@12%">IGST@12%</option>
                                                        <option selected="" value="CGST@9%">CGST@9%</option>
                                                        <option value="SGST@9%">SGST@9%</option>
                                                        <option value="IGST@18%">IGST@18%</option>
                                                        <option value="CGST@14%">CGST@14%</option>
                                                        <option value="SGST@14%">SGST@14%</option>
                                                        <option value="IGST@28%">IGST@28%</option>
                                                    </select>
                                                </td>
                                                <td colspan="3" class="col-span">
                                                    <input style="min-width: 90px;"
                                                        class="form-control form-control-plaintext form-control-sm tx-r"
                                                        id="tax1" name="tax1" readonly="" value="00.00">
                                                </td>
                                            </tr>
                                            <tr>

                                                <td colspan="2" class="col-span" colspan="">
                                                    <select placeholder="GST" name="tax_name2" onchange="calculate();"
                                                        id="tax_name2"
                                                        class="form-control form-control-plaintext form-control-sm">
                                                        <option value="">Not applicable</option>
                                                        <option value="CGST@2.5%">CGST@2.5%</option>
                                                        <option value="SGST@2.5%">SGST@2.5%</option>
                                                        <option value="IGST@5%">IGST@5%</option>
                                                        <option value="CGST@6%">CGST@6%</option>
                                                        <option value="SGST@6%">SGST@6%</option>
                                                        <option value="IGST@12%">IGST@12%</option>
                                                        <option value="CGST@9%">CGST@9%</option>
                                                        <option selected="" value="SGST@9%">SGST@9%</option>
                                                        <option value="IGST@18%">IGST@18%</option>
                                                        <option value="CGST@14%">CGST@14%</option>
                                                        <option value="SGST@14%">SGST@14%</option>
                                                        <option value="IGST@28%">IGST@28%</option>
                                                    </select>
                                                </td>
                                                <td colspan="3" class="col-span">
                                                    <input
                                                        class="form-control form-control-plaintext form-control-sm tx-r"
                                                        id="tax2" readonly="" name="tax2" value="00.00">
                                                </td>
                                            </tr>

                                            <tr>

                                                <td colspan="2" class="col-span td-firstcol tx-l border-right"><b>Total
                                                        Rs.</b></td>
                                                <td colspan="3" class="col-span"><b> <input
                                                            class="form-control form-control-plaintext form-control-sm tx-r"
                                                            id="total_amount" name="total_amount" readonly=""
                                                            value="00.00"> </b></td>
                                            </tr>
                                            <tr>

                                                <td colspan="2" class="col-span td-firstcol tx-l border-right"><b>Past
                                                        due</b></td>
                                                <td colspan="3" class="col-span"><b> <input
                                                            class="form-control form-control-plaintext form-control-sm tx-r"
                                                            id="past_due" onblur="calculate();" name="past_due"
                                                            placeholder="Past dues, if applicable"> </b></td>
                                            </tr>


                                            <tr>
                                                <td colspan="2" class="col-span text-left">GST Number</td>
                                                <td colspan="3" class="col-span text-right"><input
                                                        class="form-control form-control-plaintext form-control-sm tx-r"
                                                        maxlength="15"
                                                        pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}"
                                                        title="Your GST number" name="gst" placeholder="Enter GSTIN">
                                                </td>
                                            </tr>
                                            <tr>

                                                <td colspan="4" class="bg-grey2 text-right pr-1"
                                                    style="font-size:15px;">
                                                    <input type="hidden" name="total_due" id="total_due">
                                                    <i style="font-size: 18px; margin-top: 3px;"
                                                        class="fas fa-paint-brush pull-right gear d-print-none ml-2 text-white"
                                                        onclick="showcolor(2);"></i>

                                                    <span class="pull-right bold">
                                                        <b>Grand Total : Rs. <span id="grand_total">00.00</span></b>
                                                    </span>
                                                    <div id="colorpckdiv2" style="display: none;">
                                                        <div class="pull-right cpd">
                                                            <p style="margin-bottom: 5px;">Background color : <input
                                                                    name="bg_color2" class="pull-right"
                                                                    onchange="changebg(this.value, 2);"
                                                                    id="color-picker3" value='#5B5B5B' />
                                                            </p>
                                                            <p style="margin-bottom: 5px;"> Text color : <input
                                                                    name="text_color2" class="pull-right"
                                                                    onchange="changecolor(this.value, 2);"
                                                                    id="color-picker4" value='#ffffff' />
                                                            </p>
                                                        </div>
                                                    </div>

                                                </td>


                                            </tr>

                                            <tr>
                                                <td colspan="5">
                                                    <textarea
                                                        class="form-control form-control-plaintext form-control-sm tx-l mt-1"
                                                        rows="3" style="resize: none;text-align: left;" name="tnc"
                                                        placeholder="">Terms & Conditions&#13;&#10;1. Type your terms and conditions here
                                                    </textarea>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td colspan="5" style="font-size:12px;">
                                                    <b> * Note: </b>This is a system generated invoice. No signature
                                                    required.
                                                </td>

                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <div class="col-md-12 no-margin no-padding float-right"
                                                        style="text-align: right;">
                                                        <span style="color: #5B5B5B;
                                                              font-size: 16px;
                                                              font-weight: 600;vertical-align: text-top;
                                                              margin-right: 10px;" class="powerbytxt">Powered by</span>
                                                        <img src="https://www.swipez.in/assets/admin/layout/img/logo.png"
                                                            style="width: 130px;"
                                                            class="img-responsive pull-right powerbyimg" alt="">
                                                    </div>
                                                </td>
                                                <input type="hidden" name="type" value="isp">
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- display for download and more options for small screen sizes -->
                                <div class="col-12 d-lg-none">
                                    <div class="row pt-2 justify-content-center">
                                        <p class="lead text-center mb-0">Download invoice PDF</p>
                                    </div>
                                    <div class="row pb-2 justify-content-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-download"></i>
                                            Download PDF
                                        </button>
                                    </div>
                                    <div class="row pt-2 justify-content-center">
                                        <p class="lead text-center mb-0">Get paid faster</p>
                                    </div>
                                    <div class="row pb-2 justify-content-center">
                                        <button class="btn btn-outline-secondary" onclick="setRegText(6);"><i
                                                class="fa fa-credit-card"></i> Add online payment option</button>
                                    </div>
                                    <div class="row pt-2 justify-content-center mb-0">
                                        <p class="lead text-center mb-0">More options</p>
                                    </div>
                                    <div class="row px-4 pb-4 justify-content-center">
                                        <select onchange="setRegText(this.value);"
                                            class="form-control form-control-lg btn btn-outline-secondary"
                                            name="options">
                                            <option value="">Pick one</option>
                                            <option value="print">Print invoice</option>
                                            <option value="3">Save invoice</option>
                                            <option value="4">Send invoice in Email</option>
                                            <option value="5">Send invoice in SMS</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- display download and more options for small screen sizes end -->
                            </div>
                        </div>
                        <!-- particulars section for mobile ends -->
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>
@yield('invoice-format-features')


<div class="modal fade " id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-body p-0">
                <div class="container px-0">
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="d-none d-lg-block">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 pl-md-0">
                                        <div class="bg-primary p-2 text-white">
                                            <h3 class="card-title text-center my-3">Get a free account for your company
                                            </h3>
                                            <ul class="list-unstyled mx-auto pl-3">
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Unlimited invoices
                                                        and estimates
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Send invoices on
                                                        email and SMS
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Collect online
                                                        payments using invoices
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Auto reminders to
                                                        customers for unpaid invoices
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Bulk upload invoices
                                                        using excel sheets
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="text-white">
                                                        <i class="fa fa-check mr-3 text-white"></i>Create recurring
                                                        invoices with ease
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5 row pt-2 d-block">
                                        <button class="btn btn-sm btn-default pull-right" data-dismiss="modal"
                                            aria-label="Close">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <div class="p-1 ">
                                            <p class="pb-2 regtext"></p>
                                            @include('home.product.web_register',['d_type' => "web"])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-lg-none">
                                <div class="col-12">
                                    <div class="p-2">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <p class="regtext"></p>
                                        @include('home.product.web_register',['d_type' => "mob"])
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bg-primary p-2 text-white">
                                        <h3 class="card-title text-center mb-3">Get a free account for your company</h3>
                                        <!-- <p>Organize your invoicing and payment collections in a few clicks</p>-->
                                        <ul class="list-unstyled mx-auto pl-2">
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Unlimited invoices and
                                                    estimates
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Send invoices on email
                                                    and SMS
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Collect online
                                                    payments using invoices
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Auto reminders to
                                                    customers for unpaid invoices
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Bulk upload invoices
                                                    using excel sheets
                                                </p>
                                            </li>
                                            <li>
                                                <p class="text-white">
                                                    <i class="fa fa-check mr-3 text-tertiary"></i>Create recurring
                                                    invoices with ease
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<a data-target="#basic" id="modal" data-toggle="modal"></a>
@endsection

@section('customfooter')
<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js">
</script>
<script src="/static/js/format.js{{ Helpers::fileTime('new','static/js/format.js') }}" type="text/javascript"></script>
<script src="/static/js/spectrum.min.js"></script>
<script>
    if (window.matchMedia("(max-width: 767px)").matches) {
        $("#web").remove();
        $('#frm_format').attr("target", "_blank");
        $(".dp").attr('type', 'date');
        //$(".dp").removeClass("datepicker");
        $('.datepicker').datepicker('remove');
        document.getElementById("bill_date").valueAsDate = new Date();
        document.getElementById("due_date").valueAsDate = new Date();

    } else {
        $("#mobile").remove();
        $(".dp").attr('type', 'text');
        $('.datepicker').datepicker().on('changeDate', function(ev) {
            $('.dropdown-menu').hide();
        });

    }

    $('#color-picker').spectrum({
        color: "#5B5B5B",
        type: "text",
        togglePaletteOnly: "true",
        hideAfterPaletteSelect: "true",
        showInput: "true",
        showInitial: "true"
    });
    $('#color-picker2').spectrum({
        color: "#ffffff",
        type: "text",
        togglePaletteOnly: "true",
        hideAfterPaletteSelect: "true",
        showInput: "true",
        showInitial: "true"
    });

    $('#color-picker3').spectrum({
        color: "#5B5B5B",
        type: "text",
        togglePaletteOnly: "true",
        hideAfterPaletteSelect: "true",
        showInput: "true",
        showInitial: "true"
    });
    $('#color-picker4').spectrum({
        color: "#ffffff",
        type: "text",
        togglePaletteOnly: "true",
        hideAfterPaletteSelect: "true",
        showInput: "true",
        showInitial: "true"
    });


    $('#company_name').focus();

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@if(isset($captcha))
<script src="https://www.google.com/recaptcha/api.js?render={{$CAPTCHA_CLIENT_ID}}"></script>

<script>
    var captcha_id = '{{$CAPTCHA_CLIENT_ID}}';
    var req_count = 0;

    function gcaptchaReSet() {
        if (req_count < 3) {
            gcaptchaSet();
            req_count = req_count + 1;
        } else {
            req_count = 0;
        }
    }

    function gcaptchaSet() {
        grecaptcha.execute(captcha_id, {
            action: 'homepage'
        }).then(function(token) {
            try {
                document.getElementById('captcha1').value = token;
            } catch (o) {}
        });
    }
    grecaptcha.ready(function() {
        gcaptchaSet();
    });

    setInterval(function() {
        gcaptchaSet();
    }, 2 * 60 * 1000);
</script>
@endif
@endsection
