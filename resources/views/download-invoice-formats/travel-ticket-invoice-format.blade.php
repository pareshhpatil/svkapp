@extends('download-invoice-formats.download-invoice-format-master')
@section('content-format')
<div id="web" class="row">
    <div class="col-md-12">
        <p><h5>Booking details</h5></p>
        <div class="table-scrollable">
            <table class="table  table-bordered table-condensed mb-1" >
                <thead>
                    <tr >
                        <th class="tdb3" style="">
                            Booking date
                        </th>
                        <th class="tdb3" style="">
                            journey date
                        </th>
                        <th class="tdb3 add-col" >
                <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-center bold"id="time_period_label"  name="time_period_label" placeholder="Add column name" >
                    <a class="ml-2" onclick="removecol('1');" href="javascript:" ><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="Remove column"></i></a></div>
                </th>
                <th class="tdb3 text-center" style="">
                    Name
                </th>
                <th class="tdb3 text-center" style="">
                    Type
                </th>
                <th class="tdb3 text-center" style="">
                    From
                </th>
                <th class="tdb3 text-center" style="">
                    To
                </th>
                <th class="tdb3 text-center" style="">
                    Amt
                </th>
                <th class="tdb3 text-center" style="">
                    Ser. Ch
                </th>



                <th  class="tdb3 text-center" >
                <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-center bold "  name="total_label"  value="Total">
                    <a  style="font-size: 18px;" onclick="addcol();"  href="javascript:" ><i class="fa fa-columns" data-toggle="tooltip" data-placement="top" title="Add a new column"></i></a></div>
                </th>

                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="p_booking_date1"  placeholder="Booking date">

                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="p_journey_date1"  placeholder="Journey date">
                        </td>
                        <td class="tdpr add-col">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period" placeholder="Enter value">
                        </td>

                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_name1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_type1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_from1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_to1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" required="" type="number" step="0.01" id="amount" onblur="calculateTicket();" name="p_amount1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" required="" type="number" step="0.01" id="scharge" onblur="calculateTicket();" name="p_scharge1" placeholder="Enter value">
                        </td>
                        <td class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" name="p_cost" readonly="" required="" type="number" step="0.01"   onblur="calculateTicket();" id="p_cost" value="0.00" placeholder="Enter amount">
                                <a style="font-size: 18px;" class="ml-2" onclick="addnewrow();" href="javascript:" ><i class="fa fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Add another line item"></i></a></div>

                        </td>

                    </tr>
                    <tr id="newrow" style="display: none;">
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="p_booking_date2"  placeholder="Booking date">

                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="p_journey_date2"  placeholder="Journey date">
                        </td>
                        <td class="tdpr add-col">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period2" placeholder="Enter value">
                        </td>

                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_name2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_type2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_from2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_to2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center"  type="number" step="0.01" id="amount2" onblur="calculateTicket();" name="p_amount2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center"  type="number" step="0.01" id="scharge2" onblur="calculateTicket();" name="p_scharge2" placeholder="Enter value">
                        </td>
                        <td class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" name="p_cost2" readonly="" required="" type="number" step="0.01"   onblur="calculateTicket();" id="p_cost2" value="0.00" placeholder="Enter amount">
                                <a style="font-size: 18px;" class="ml-2" onclick="removerow();" href="javascript:" ><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Add another line item"></i></a></div>

                        </td>

                    </tr>

                    <tr >
                        <td class="add-col border-0">
                        </td>
                        <td class="border-left-0" colspan="6">
                            <b class="pull-right">Total Rs.</b>

                        </td>

                        <td class="tdpr" >
                            <b id="tp_amt">0.00</b>
                        </td>
                        <td class="tdpr" >
                            <b id="tp_scharge">0.00</b>
                        </td>
                        <td  class="tdpr">
                            <b id="tp_cost">0.00</b>

                        </td>

                    </tr>




                </tbody>
            </table>

        </div>
        <p><h5>Cancellation details</h5></p>
        <div class="table-scrollable">
            <table class="table  table-bordered table-condensed mb-1" >
                <thead>
                    <tr >
                        <th class="tdb3" style="">
                            Booking date
                        </th>
                        <th class="tdb3" style="">
                            journey date
                        </th>

                        <th class="tdb3 text-center" style="">
                            Name
                        </th>
                        <th class="tdb3 text-center" style="">
                            Type
                        </th>
                        <th class="tdb3 text-center" style="">
                            From
                        </th>
                        <th class="tdb3 text-center" style="">
                            To
                        </th>
                        <th class="tdb3 text-center" style="">
                            Amt
                        </th>
                        <th class="tdb3 text-center" style="">
                            Ser. Ch
                        </th>
                        <th  class="tdb3 text-center" >
                            <input class="form-control form-control-plaintext form-control-sm text-center bold "  name="total_label2"  value="Total">
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="c_booking_date1"  placeholder="Booking date">

                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-sm form-control-plaintext datepicker dp" data-date-format="dd M yyyy"   required="" value="{{$current_date}}" autocomplete="off"  name="c_journey_date1"  placeholder="Journey date">
                        </td>


                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="c_name1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="c_type1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="c_from1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="c_to1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center"  type="number" step="0.01" id="camount" onblur="calculateTicket();" name="c_amount1" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center"  type="number" step="0.01" id="cscharge" onblur="calculateTicket();" name="c_scharge1" placeholder="Enter value">
                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="c_cost" readonly="" required="" type="number" step="0.01"   onblur="calculateTicket();" id="c_cost" value="0.00" placeholder="Enter amount">


                        </td>

                    </tr>
                    <tr >

                        <td class="border-left-0" colspan="6">
                            <b class="pull-right">Total Rs.</b>

                        </td>

                        <td class="tdpr" >
                            <b id="tc_amt">0.00</b>
                        </td>
                        <td class="tdpr" >
                            <b id="tc_scharge">0.00</b>
                        </td>
                        <td  class="tdpr text-center">
                            <b id="tc_cost" >0.00</b>
                        </td>

                    </tr>





                </tbody>
            </table>

        </div>
        <p><h5>Tax details</h5></p>
        <div class="table-scrollable">
            <table class="table  table-bordered table-condensed mb-1" >
                <thead>
                    <tr >
                        <th class="tdb3 text-center" style="">
                            Tax name
                        </th>
                        <th class="tdb3 text-center" style="">
                            Percentage
                        </th>

                        <th class="tdb3 text-center" style="">
                            Applicable
                        </th>
                        <th class="tdb3 text-center" style="">
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tdpr" >
                            <select style="min-width: 90px;" placeholder="GST" name="tax_name1" onchange="changeTax(1);"  id="tax_name1" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="tx_per1" readonly="" name="tx_percentage1" value="9.00" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" readonly id="tx_applicable1" name="tx_applicable1" value="0.00" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" readonly="" id="tax1" name="tax1" value="0.00" placeholder="Enter value">
                        </td>


                    </tr>
                    <tr>
                        <td class="tdpr" >
                            <select placeholder="GST" name="tax_name2" onchange="changeTax(2);" id="tax_name2" class="form-control form-control-plaintext form-control-sm">
                                <option value="">Not applicable</option>
                                <option value="CGST@2.5%">CGST@2.5%</option>
                                <option value="SGST@2.5%">SGST@2.5%</option>
                                <option value="IGST@5%">IGST@5%</option>
                                <option value="CGST@6%">CGST@6%</option>
                                <option value="SGST@6%">SGST@6%</option>
                                <option value="IGST@12%">IGST@12%</option>
                                <option  value="CGST@9%">CGST@9%</option>
                                <option selected="" value="SGST@9%">SGST@9%</option>
                                <option value="IGST@18%">IGST@18%</option>
                                <option value="CGST@14%">CGST@14%</option>
                                <option value="SGST@14%">SGST@14%</option>
                                <option value="IGST@28%">IGST@28%</option>
                            </select>
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center"id="tx_per2"  readonly="" name="tx_percentage2" value="9.00" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" readonly id="tx_applicable2" name="tx_applicable2" value="0.00" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" readonly="" id="tax2" name="tax2" value="0.00" placeholder="Enter value">
                        </td>


                    </tr>
                    <tr >

                        <td class="border-left-0" colspan="3">
                            <b >Tax Total</b>

                        </td>


                        <td  class="tdpr text-center">
                            <b id="tx_total" >0.00</b>
                        </td>

                    </tr>





                </tbody>
            </table>

        </div>

    <table class="table  table-bordered table-condensed mb-1">
        <tr>

                        <td colspan="3"></td>
                        <td colspan="2" class="bg-grey2 col-span2 text-right pr-1" style="font-size:15px;min-width: 260px;">
                            <input type="hidden" name="total_amount" id="total_amount">
                            <input type="hidden" name="sub_total" id="sub_total">
                            <input type="hidden" name="past_due" id="past_due">
                            <input type="hidden" name="total_due" id="total_due">
                            <input type="hidden" name="p_description" value="NA">
                            <input type="hidden" name="gst">
                            <i style="font-size: 18px; margin-top: 3px;" class="fas fa-paint-brush pull-right gear d-print-none ml-2 text-white" onclick="showcolor(2);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Change background color and text color"></i>

                            <span class="pull-right bold">
                                <b>Grand Total : Rs. <span id="grand_total">00.00</span></b>
                            </span>
                            <div id="colorpckdiv2" style="display: none;">
                                <div class="pull-right cpd">
                                    <p style="margin-bottom: 5px;">Background color : <span class="sp-original-input-container sp-colorize-container" style="margin: 0px; display: flex;"><input name="bg_color2" class="pull-right spectrum sp-colorize" onchange="changebg(this.value, 2);" id="color-picker3" value="#5B5B5B" style="background-color: rgb(39, 87, 112); color: white;"></span>
                                    </p>
                                    <p style="margin-bottom: 5px;"> Text color : <span class="sp-original-input-container sp-colorize-container" style="margin: 0px; display: flex;"><input name="text_color2" class="pull-right spectrum sp-colorize" onchange="changecolor(this.value, 2);" id="color-picker4" value="#ffffff" style="background-color: rgb(255, 255, 255); color: black;"></span>
                                    </p>
                                </div>
                            </div>

                        </td>


                    </tr>
                    <tr>
                        <td colspan="3" rowspan="1" class="pb-2 border-bottom-0 border-right-0">
                            <textarea class="form-control form-control-plaintext form-control-sm text-left summernote mt-2" id="summernote" rows="10" style="resize: none;text-align: left;" name="tnc" placeholder="">Fund transfer information
Bank name : HDFC Bank
Account holder name : My Company Pvt. Ltd.
Account number : 1234567890123
IFSC code : HDFC000001

Terms &amp; Conditions
1. Type your terms and conditions here</textarea>
                        </td>
                        <td colspan="" class="td-firstcol border-0">

                        </td>
                        <td class="col-span border-0">
                        </td>
                    </tr>


                    <tr>
                        <td colspan="3" class="bg-light-blue lead border-right-0">
                            Get paid faster by collecting payments online
                        </td>
                        <td colspan="2" class="bg-light-blue border-left-0">
                            <a href="javascript:" class="btn btn-sm btn-primary pull-right" onclick="setRegText(6);">Add online payment option</a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="3" class="text-left border-right-0" style="font-size:12px;">
                            <b> * Note: </b>This  is a system generated invoice. No signature required.
                        </td>
                        <td colspan="2" class="text-left border-left-0">
                            <div class="col-md-12 no-margin no-padding float-right text-right">
                                <span style="color: #5B5B5B;
                                      font-size: 16px;
                                      font-weight: 600;vertical-align: text-top;
                                      margin-right: 10px;" class="powerbytxt">Powered by</span> <img src="https://www.swipez.in/assets/admin/layout/img/logo.png" style="width: 130px;" class="img-responsive pull-right powerbyimg" alt="">
                                <div>
                                    <input type="hidden" name="type" value="travel_ticket">
                                    </div></div>
                        </td>
                                    </tr>
                                    </tbody>
                                    </table>
    </div>
</div>
@endsection
@section('invoice-format-features')
<section class="py-7 bg-light-blue" id="features">
    <div class="container">
        <h2 class="display-4 font-weight-bold text-center">Features</h2>
        <div class="row lead">
            <div class="col-lg-6 mt-5">
                <ul class="list-unstyled w-75 mx-auto float-lg-right">
                    <li>
                        <p class="font-weight-bold text-center">
                            Without registration
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Create your invoice right here in your browser and download the PDF
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Add multiple columns and rows as required
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Add your logo and change your invoice colors as per your brand
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Automatic calculation of subtotals, totals and tax
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Ready to be printed or emailed to your clients
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>GST compliant invoice format
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>A great design to impress clients!
                        </p>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 mt-5">
                <ul class="list-unstyled w-75 mx-auto float-lg-left">
                    <li>
                        <p class="font-weight-bold text-center">
                            With your free registration
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Send invoice via email to your customers mailbox
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Send invoice via SMS to your customer
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Add online payment options to your invoice
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Automated reminders sent to customer for pending invoices
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>All invoices saved in your secure account
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Customize all aspects of your invoice
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>More <a href="{{ route('home.billing') }}"
                            class="text-small">invoicing features</a> 🚀
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
