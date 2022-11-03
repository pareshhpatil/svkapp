@extends('download-invoice-formats.download-invoice-format-master')
@section('content-format')
<div id="web" class="row">
    <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table  table-bordered table-condensed mb-1" >
                <thead>
                    <tr >
                        <th class="tdb3" style="">
                            #
                        </th>
                        <th class="tdb3 text-center" style="min-width: 200px;">
                            <input class="form-control form-control-plaintext form-control-sm text-center bold"  name="description_label"  value="Description">

                        </th>
                        <th class="tdb3 add-col" style="min-width: 120px;display: table-cell;">
                            <input class="form-control form-control-plaintext form-control-sm text-center bold"  name="sac_code_label" id="sac_code_label" placeholder="Add column name" >
                        </th>
                        <th class="tdb3 add-col2" style="min-width: 120px;">
                <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-center bold"id="time_period_label"  name="time_period_label" placeholder="Add column name" >
                    <a class="ml-2" onclick="removecol('2');" href="javascript:" ><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="Remove column"></i></a></div>
                </th>

                <th colspan="2" class="tdb3 text-center" style="min-width: 150px;">
                <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-center bold "  name="total_label"  value="Absolute cost">
                    <a  style="font-size: 18px;" onclick="addcol();"  href="javascript:" ><i class="fa fa-columns" data-toggle="tooltip" data-placement="top" title="Add a new column"></i></a></div>
                </th>

                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tdpr">
                            1
                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_description" required=""  placeholder="Line item name">
                        </td>
                        <td class="tdpr add-col" style="display: table-cell;">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('sac_code', this.value);" name="p_sac_code" placeholder="Enter value">
                        </td>

                        <td class="tdpr add-col2" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period" placeholder="Enter value">
                        </td>

                        <td colspan="2" class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" name="p_cost" required="" type="number" step="0.01"   onblur="calculate();" id="p_cost" placeholder="Enter amount">
                                <a style="font-size: 18px;" class="ml-2" onclick="addnewrow();" href="javascript:" ><i class="fa fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Add another line item"></i></a></div>

                        </td>

                    </tr>
                    <tr id="newrow" style="display: none;">
                        <td class="tdpr">
                            2
                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_description2"   placeholder="Line item name">
                        </td>
                        <td class="tdpr add-col" style="display: table-cell;">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('sac_code', this.value);" name="p_sac_code2" placeholder="Enter value">
                        </td>
                        <td class="tdpr add-col2">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period2" placeholder="Enter value">
                        </td>
                        <td colspan="2" class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" name="p_cost2" type="number" step="0.01"  onblur="calculate();" id="p_cost2" placeholder="Enter amount">
                                <a style="font-size: 18px;" class="ml-2"  onclick="removerow();" href="javascript:" ><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Delete row"></i></a>
                            </div>
                        </td>

                    </tr>
                    <tr >
                        <td colspan="3" rowspan="6" class="pb-2 border-bottom-0" >
                            <textarea  class="form-control form-control-plaintext form-control-sm text-left summernote mt-2"  id="summernote" rows="10" style="resize: none;text-align: left;" name="tnc" placeholder=""  >Fund transfer information&#13;
Bank name : HDFC Bank&#13;
Account holder name : My Company Pvt. Ltd.&#13;
Account number : 1234567890123&#13;
IFSC code : HDFC000001&#13;&#10;
Terms & Conditions&#13;
1. Type your terms and conditions here</textarea>
                        </td>
                        <td colspan="" class="td-firstcol">
                            <b>Sub Total</b>
                        </td>
                        <td class="col-span"   >
                            <b>  <input class="form-control form-control-plaintext form-control-sm text-right" id="sub_total" name="sub_total" readonly="" value="00.00"></b>
                        </td>
                    </tr>
                    <tr >

                        <td >
                            <select style="min-width: 90px;" placeholder="GST" name="tax_name1" onchange="calculate();"  id="tax_name1" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="col-span" >
                            <input style="min-width: 90px;" class="form-control form-control-plaintext form-control-sm text-right" id="tax1" name="tax1" readonly="" value="00.00">
                        </td>
                    </tr>
                    <tr >

                        <td  colspan="">
                            <select placeholder="GST" name="tax_name2" onchange="calculate();"  id="tax_name2" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="col-span" >
                            <input class="form-control form-control-plaintext form-control-sm text-right" id="tax2" readonly="" name="tax2" value="00.00">
                        </td>
                    </tr>

                    <tr >
                        <td class="td-firstcol"><b>Total Rs.</b></td>
                        <td class="col-span" ><b> <input class="form-control form-control-plaintext form-control-sm text-right" id="total_amount" name="total_amount" readonly="" value="00.00"> </b></td>
                    </tr>
                    <tr >

                        <td class="td-firstcol"><b>Past due</b></td>
                        <td class="col-span" ><b> <input class="form-control form-control-plaintext form-control-sm text-right" id="past_due" onblur="calculate();" type="number" step="0.01" name="past_due" placeholder="Past dues, if applicable"> </b></td>
                    </tr>
                    <tr >
                        <td class="text-left">GST Number</td>
                        <td class="col-span"><input class="form-control form-control-plaintext form-control-sm text-right" maxlength="15" pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}" title="Enter Valid GST number" name="gst" placeholder="Your GST number"></td>
                    </tr>
                    <tr>

                        <td colspan="3"  ></td>
                        <td colspan="2" class="bg-grey2 col-span2 text-right pr-1" style="font-size:15px;min-width: 260px;">
                            <input type="hidden" name="total_due" id="total_due">
                            <i style="font-size: 18px; margin-top: 3px;" class="fas fa-paint-brush pull-right gear d-print-none ml-2 text-white" onclick="showcolor(2);" data-toggle="tooltip" data-placement="top" title="Change background color and text color"></i>

                            <span class="pull-right bold">
                                <b>Grand Total : Rs. <span id="grand_total">00.00</span></b>
                            </span>
                            <div id="colorpckdiv2" style="display: none;">
                                <div class="pull-right cpd" >
                                    <p style="margin-bottom: 5px;">Background color : <input name="bg_color2" class="pull-right" onchange="changebg(this.value, 2);"  id="color-picker3"  value='#5B5B5B' />
                                    </p>
                                    <p style="margin-bottom: 5px;"> Text color : <input name="text_color2" class="pull-right" onchange="changecolor(this.value, 2);"  id="color-picker4"  value='#ffffff' />
                                    </p>
                                </div>
                            </div>

                        </td>


                    </tr>
                    <tr >
                        <td colspan="3" class="bg-light-blue lead border-right-0">
                            Get paid faster by collecting payments online
                        </td>
                        <td colspan="2" class="bg-light-blue border-left-0">
                            <a href="javascript:" class="btn btn-sm btn-primary pull-right" onclick="setRegText(6);" >Add online payment option</a>
                        </td>
                    </tr>

                    <tr >
                        <td colspan="3" class="text-left border-right-0" style="font-size:12px;">
                            <b> * Note: </b>This  is a system generated invoice. No signature required.
                        </td>
                        <td colspan="2" class="text-left border-left-0">
                            <div class="col-md-12 no-margin no-padding float-right text-right">
                                <span style="color: #5B5B5B;
                                      font-size: 16px;
                                      font-weight: 600;vertical-align: text-top;
                                      margin-right: 10px;" class="powerbytxt">Powered by</span> <img  src="https://www.swipez.in/assets/admin/layout/img/logo.png" style="width: 130px;" class="img-responsive pull-right powerbyimg" alt="">
                                <div>
                                    <input type="hidden" name="type" value="isp">
                                    </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div>
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
                                                        <i class="fa fa-check mr-3 text-primary"></i>Send <a href="{{ route('home.billing.feature.onlineinvoicing') }}">online invoices</a> via email to your customers mailbox
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        <i class="fa fa-check mr-3 text-primary"></i>Send <a href="{{ route('home.billing.feature.onlineinvoicing') }}">online invoices</a> via SMS to your customer
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





