@extends('download-invoice-formats.download-invoice-format-master')
@section('content-format')
<!-- particulars section for web -->
<div id="web" class="row">
    <div class="col-md-12 ">
        <div class="table-scrollable">
            <table class="table table-bordered  table-condensed mb-1">
                <thead>
                    <tr>
                        <th class="border-right" >
                            #
                        </th>
                        <th class="border-right text-center" style="min-width: 100px;">
                            <input class="form-control form-control-plaintext form-control-sm text-left bold"  name="description_label"  value="Description">
                        </th>
                        <th class="border-right add-col" style="min-width: 50px;">
                <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-center bold"id="time_period_label"  name="time_period_label" placeholder="Add column name" >
                    <a class="ml-2" onclick="removecol('1');" href="javascript:" ><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="Remove column"></i></a></div>
                </th>
                <th class="border-right text-center" style="min-width: 50px;">
                    SAC code
                </th>
                <th class="border-right text-center " style="min-width: 50px;">
                    Quantity
                </th>
                <th class="border-right text-center " style="min-width: 50px;">
                    Unit type
                </th>
                <th class="border-right text-center " style="min-width: 50px;">
                    Rate
                </th>
                <th class="border-right text-center " style="min-width: 50px;">
                    GST
                </th>
                <th class="border-right  text-center" style="min-width: 5px;">
                    Discount
                </th>


                <th  class="tdb3 text-center" style="min-width: 150px;">
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
                        <td class="tdpr add-col">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period" placeholder="Enter value">
                        </td>

                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_sac_code" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" required="" type="number" step="0.01" id="p_qty" onblur="calculateSale();" name="p_qty" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_unit_type" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="p_rate" required="" type="number" step="0.01" name="p_rate" onblur="calculateSale();" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <select style="min-width: 50px;" placeholder="GST" name="p_gst" onchange="calculateSale();"  id="p_gst" class="form-control form-control-plaintext form-control-sm">
                                <option value="">NA</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18">18%</option>
                                <option value="28">28%</option>
                            </select>

                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="p_discount" onblur="calculateSale();" name="p_discount" placeholder="Enter value">
                        </td>

                        <td class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" name="p_cost" readonly="" required="" type="number" step="0.01"   onblur="calculateSale();" id="p_cost" value="0.00" placeholder="Enter amount">
                                <a style="font-size: 18px;" class="ml-2" onclick="addnewrow();" href="javascript:" ><i class="fa fa-plus-circle" data-toggle="tooltip" data-placement="top" title="Add another line item"></i></a></div>

                        </td>

                    </tr>
                    <tr id="newrow" style="display: none;">
                        <td class="tdpr">
                            2
                        </td>
                        <td class="tdpr">
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_description2"  placeholder="Line item name">
                        </td>
                        <td class="tdpr add-col">
                            <input class="form-control form-control-plaintext form-control-sm text-center" onblur="setMandatory('time_period', this.value);" name="p_time_period2" placeholder="Enter value">
                        </td>

                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_sac_code2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="p_qty2" type="number" step="0.01" name="p_qty2" onblur="calculateSale();" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" name="p_unit_type2" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="p_rate2" type="number" step="0.01" name="p_rate2" onblur="calculateSale();" placeholder="Enter value">
                        </td>
                        <td class="tdpr" >
                            <select style="min-width: 50px;" placeholder="GST" name="p_gst2" onchange="calculateSale();"  id="p_gst2" class="form-control form-control-plaintext form-control-sm">
                                <option value="">NA</option>
                                <option value="5">5%</option>
                                <option value="12">12%</option>
                                <option value="18">18%</option>
                                <option value="28">28%</option>
                            </select>
                        </td>
                        <td class="tdpr" >
                            <input class="form-control form-control-plaintext form-control-sm text-center" id="p_discount2" onblur="calculateSale();" name="p_discount2" placeholder="Enter value">
                        </td>

                        <td class="tdpr">
                            <div class="input-group"><input class="form-control form-control-plaintext form-control-sm text-right" id="p_cost2" readonly="" name="p_cost2" required="" type="number" step="0.01"   onblur="calculateSale();" value="0.00"  placeholder="0.00">
                                <a style="font-size: 18px;" class="ml-2" onclick="removerow();" href="javascript:" ><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Add another line item"></i></a></div>

                        </td>

                    </tr>
                    <tr style="text-align: center;">
                        <td colspan="6" rowspan="5" id="tnc_col" class="pb-2 border-bottom-0">
                            <textarea  class="form-control form-control-plaintext form-control-sm text-left summernote mt-2"  id="summernote" rows="10" style="resize: none;text-align: left;" name="tnc" placeholder=""  >
Fund transfer information&#13;
Bank name : HDFC Bank&#13;
Account holder name : My Company Pvt. Ltd.&#13;
Account number : 1234567890123&#13;
IFSC code : HDFC000001&#13;&#10;
Terms & Conditions&#13;
1. Type your terms and conditions here</textarea>
                        </td>
                        <td colspan="2" class="td-firstcol">
                            <b>Sub Total</b>
                        </td>
                        <td class="col-span"  >
                            <b>  <input class="form-control form-control-plaintext form-control-sm text-right" id="sub_total" name="sub_total" readonly="" value="00.00"></b>
                        </td>
                    </tr>
                    <tr class="d-none" id="tax1-tr">
                        <td colspan="2" >
                            <select style="min-width: 90px;" placeholder="GST" name="tax_name1" onchange="GSTType(1);"  id="tax_name1" class="form-control form-control-plaintext form-control-sm">
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
                    <tr class="d-none" id="tax2-tr">

                        <td colspan="2" colspan="">
                            <select placeholder="GST" name="tax_name2" onchange="calculateSale();"  id="tax_name2" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="col-span">
                            <input class="form-control form-control-plaintext form-control-sm text-right" id="tax2" readonly="" name="tax2" value="00.00">
                        </td>
                    </tr>
                    <tr class="d-none" id="tax3-tr">

                        <td colspan="2">
                            <select style="min-width: 90px;" placeholder="GST"  name="tax_name3" onchange="GSTType(3);"  id="tax_name3" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="col-span">
                            <input style="min-width: 90px;" class="form-control form-control-plaintext form-control-sm text-right" id="tax3" name="tax3" readonly="" value="00.00">
                        </td>
                    </tr>
                    <tr class="d-none" id="tax4-tr">

                        <td colspan="2"  colspan="">
                            <select placeholder="GST" name="tax_name4" onchange="calculateSale();"  id="tax_name4" class="form-control form-control-plaintext form-control-sm">
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
                        <td class="col-span">
                            <input class="form-control form-control-plaintext form-control-sm text-right" id="tax4" readonly="" name="tax4" value="00.00">
                        </td>
                    </tr>

                    <tr>

                        <td colspan="2" class="td-firstcol"><b>Total Rs.</b></td>
                        <td class="col-span" ><b> <input class="form-control form-control-plaintext form-control-sm text-right" id="total_amount" name="total_amount" readonly="" value="00.00"> </b></td>
                    </tr>
                    <tr >

                        <td colspan="2" class="td-firstcol"><b>Past due</b></td>
                        <td class="col-span" ><b> <input class="form-control form-control-plaintext form-control-sm text-right" id="past_due" onblur="calculateSale();" type="number" step="0.01" name="past_due" placeholder="Past dues, if applicable"> </b></td>
                    </tr>
                    <tr >
                        <td colspan="2" >GST Number</td>
                        <td class="col-span" ><input class="form-control form-control-plaintext form-control-sm text-right" maxlength="15" pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}" title="Enter Valid GST number" name="gst" placeholder="Your GST number"></td>
                    </tr>
                    <tr>

                        <td colspan="3" class="bg-grey2 col-span2 pr-1 text-right" style="font-size:15px;">
                            <input type="hidden" name="total_due" id="total_due">
                            <i style="font-size: 18px; margin-top: 3px;" class="fas fa-paint-brush pull-right gear d-print-none ml-2 text-white" onclick="showcolor(2);" data-toggle="tooltip" data-placement="top" title="Change background color and text color"></i>

                            <span class="pull-right">
                                <b>Grand Total : Rs. <span id="grand_total">00.00</span></b>
                            </span>
                            <div id="colorpckdiv2" style="display: none;">
                                <div class="pull-right cpd" >
                                    <p class="mb-1">Background color : <input name="bg_color2" class="pull-right" onchange="changebg(this.value, 2);"  id="color-picker3"  value='#5B5B5B' />
                                    </p>
                                    <p class="mb-1"> Text color : <input name="text_color2" class="pull-right" onchange="changecolor(this.value, 2);"  id="color-picker4"  value='#ffffff' />
                                    </p>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td colspan="5" class="bg-light-blue lead border-right-0">
                            Get paid faster by collecting payments online
                        </td>
                        <td colspan="4" class="bg-light-blue border-left-0 col-span5" >
                            <a href="javascript:" class="btn btn-sm btn-primary pull-right" onclick="setRegText(6);" >Add online payment option</a>
                        </td>
                    </tr>

                    <tr style="text-align: center;">
                        <td colspan="5" class="text-left border-right-0" style="font-size:12px;">
                            <b> * Note: </b>This  is a system generated invoice. No signature required.
                        </td>
                        <td colspan="4" class=" border-left-0 col-span5" style="font-size:12px;">
                            <div class="col-md-12 no-margin no-padding float-right text-right">
                                <span style="color: #5B5B5B;
                                      font-size: 16px;
                                      font-weight: 600;vertical-align: text-top;
                                      margin-right: 10px;" class="powerbytxt">Powered by</span> <img  src="https://www.swipez.in/assets/admin/layout/img/logo.png" style="width: 130px;" class="img-responsive pull-right powerbyimg" alt="">
                            </div>
                            <input type="hidden" name="type" value="sales">
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- particulars section for web ends -->
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
                            <i class="fa fa-check mr-3 text-primary"></i>Create your proforma invoice right here in your browser and download the PDF
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Add multiple columns and rows as required in your proforma
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Add your logo and change your proforma colors as per your brand
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Automatic calculation of taxes, subtotals and grand total in your proforma invoice
                        </p>
                    </li>
                    <li>
                        <p>
                            <i class="fa fa-check mr-3 text-primary"></i>Proforma invoice Ready to be printed or emailed to your clients
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
