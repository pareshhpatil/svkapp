@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @if(!empty($errors))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>
                <div class="media">
                    @foreach ($errors as $v)
                    <p class="media-heading">{{$v}}</p>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/creditnote/save" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Credit note information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Credit note number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input id="expense_auto_generate" name="credit_note_no" type="text" @if($credit_note_auto_generate==1) readonly="" value="Auto generate" @endif class="form-control">
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#customer" class="btn btn-icon-only green"><i class="icon-settings mt-0"> </i></a>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Credit note date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required name="credit_note_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Credit note date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select Customer <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" onchange="setCustomerDetails(this.value);" data-placeholder="Select customer" required="" name="customer_id">
                                                <option value="">Select customer</option>
                                                @foreach($customers as $v)
                                                <option value="{{$v->customer_id}}">{{$v->first_name}} {{$v->last_name}} - {{$v->customer_code}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Invoice information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">

                                            <div class="input-group display-none" id="inv_text">
                                                <input type="text" id="invoice_text" maxlength="45" name="invoice_no" class="form-control">
                                                <span class="input-group-btn">
                                                    <a onclick="toggleInvoice(1);" class="btn btn-icon-only red"><i class="fa fa-remove mt-0"> </i></a>
                                                </span>
                                            </div>

                                            <div class="input-group" id="inv_dropdown">
                                                <select class="form-control select2me" id="invoice_select" @if($type==2) onchange="setFranchiseInvoiceDetails(this.value);" @elseif($type==3) onchange="setFranchiseInvoiceDetailsNonBrand(this.value);" @else onchange="setInvoiceDetails(this.value);" @endif data-placeholder="Select invoice" required="" name="invoice_no">
                                                    <option value="">Select invoice</option>
                                                </select>
                                                <span class="input-group-btn">
                                                    <a onclick="toggleInvoice(2);" class="btn btn-icon-only green"><i class="fa fa-plus mt-0"> </i></a>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" id="bill_date" required name="bill_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Bill date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" id="due_date" required name="due_date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Due date" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Upload file<span class="required">
                                            </span>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(3000000, 'expense');" id="expense" name="file">
                                            <span class="help-block red">* Max file size 3 MB</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        @if($type==2)
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td class="td-c">
                                                <b>Date</b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>System Gross Sales</b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>Franchise Gross Sales</b>
                                            </td>
                                            <td class="td-c">
                                                <a onclick="addGrossSaleRow();" class="btn green btn-xs pull-right"><i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="tb_gross_sale">

                                        <tr>
                                            <td class="td-c">
                                                <input type="text" required="" name="sale_date[]" class="form-control date-picker" placeholder="Date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="gross_sale[]" class="form-control">
                                                <input type="hidden" name="gs_id[]" value="0">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="new_gross_sale[]" class="form-control">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="td-c">
                                                Gross Billable Sales
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="gbs" name="gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_gbs" name="new_gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Less: GST@5%
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="new_sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummaryNew();" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Net Billable Sales
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="nbs" name="net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_nbs" name="new_net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Gross Franchisee Fee on Net Billable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gcp" name="gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gca" name="gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gcp" name="new_gross_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gca" name="new_gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Less: Waiver
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wcp" name="waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wca" name="waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wcp" name="new_waiver_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wca" name="new_waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Net Franchise Fee receivable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="ncp" name="net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="nca" name="net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_ncp" name="new_net_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_nca" name="new_net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Penalty on outstanding amt
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="penalty" name="penalty" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_penalty" name="new_penalty" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Franchisee fees Payable
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="particulartotal" name="totalcost" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_particulartotal" name="new_totalcost" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Total Amount (FEE)
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="invoice_total" class="form-control td-r" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_invoice_total" class="form-control td-r" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Previous outstanding
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="previous_due" name="previous_dues" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_previous_due" name="new_previous_dues" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                <select class="form-control " onchange="calculateexpensecost();" name="tax[]">
                                                    <option value="0">Select tax</option>
                                                    <option value="1">Non Taxable</option>
                                                    <option value="2">GST @0%</option>
                                                    <option value="3">GST @5%</option>
                                                    <option value="4">GST @12%</option>
                                                    <option selected value="5">GST @18%</option>
                                                    <option value="6">GST @28%</option>
                                                </select>
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="gstamt" name="gstamt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_gstamt" name="new_gstamt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Total FF to be Paid with Previous outstanding
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="grand_total" class="form-control td-r" value="0" readonly="">
                                                <input type="hidden" id="totaltaxcost" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_grand_total" class="form-control td-r" value="0" readonly="">
                                                <input type="hidden" id="new_totaltaxcost" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label>Credit / Debit Note Amount/ Royalty</label></td>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <input type="hidden" name="particular_id[]" value="0">
                                                <input type="hidden" name="particular[]" value="Franchise fee">
                                                <input type="hidden" name="sac[]" value="">
                                                <input type="hidden" name="unit[]" value="1">
                                                <input type="hidden" name="total[]" value="0">
                                                <input type="hidden" name="rate[]" id="rate" value="0">
                                                <input type="hidden" id="cgst_amt" name="cgst_amt" value="0.00">
                                                <input type="hidden" id="sgst_amt" name="sgst_amt" value="0.00">
                                                <input type="hidden" id="igst_amt" name="igst_amt" value="0.00">
                                                <input type="hidden" id="sub_total" name="sub_total" value="0.00">
                                                <div id="cgst" style="display: none;"></div>
                                                <div id="sgst" style="display: none;"></div>
                                                <div id="igst" style="display: none;"></div>
                                                <input type="text" class="form-control" readonly="" id="total" name="total" value="0.00">
                                            </td>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>

                        </div>
                        @elseif($type==3)
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td class="td-c">
                                                <b>Date</b>
                                            </td>
                                            <td colspan="4" class="td-c">
                                                <b>System Gross Sales</b>
                                            </td>
                                            <td colspan="4" class="td-c">
                                                <b>Franchise Gross Sales</b>
                                            </td>
                                            <td class="td-c">
                                                <a onclick="addGrossSaleRowNonBrand();" class="btn green btn-xs pull-right"><i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                <b></b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>Chetty's	</b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>Non Chetty's</b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>Chetty's	</b>
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <b>Non Chetty's</b>
                                            </td>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="tb_gross_sale_non_brand">

                                        <tr>
                                            <td class="td-c">
                                                <input type="text" required="" name="sale_date[]" class="form-control date-picker" placeholder="Date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="gross_sale[]" class="form-control">
                                                <input type="hidden" name="gs_id[]" value="0">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="non_brand_gross_sale[]" class="form-control">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="new_gross_sale[]" class="form-control">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="non_brand_new_gross_sale[]" class="form-control">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="td-c">
                                                Gross Billable Sales
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="gbs" name="gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="non_brand_gbs" name="non_brand_gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_gbs" name="new_gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="non_brand_new_gbs" name="non_brand_new_gross_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Less: GST@5%
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="non_brand_sale_tax" name="non_brand_sale_tax" class="form-control td-r" onblur="franchiseSummary();" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="new_sale_tax" name="sale_tax" class="form-control td-r" onblur="franchiseSummaryNew();" value="0">
                                            </td>
                                            
                                            <td colspan="2" class="td-r">
                                                <input type="number" step="0.01" id="non_brand_new_sale_tax" name="non_brand_new_sale_tax" class="form-control td-r" onblur="franchiseSummaryNew();" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Net Billable Sales
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="nbs" name="net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="non_brand_nbs" name="non_brand_net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_nbs" name="new_net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="non_brand_new_nbs" name="non_brand_new_net_bilable_sale" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Gross Franchisee Fee on Net Billable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gcp" name="gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gca" name="gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_gcp" name="non_brand_gross_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_gca" name="non_brand_gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gcp" name="new_gross_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gca" name="new_gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_gcp" name="non_brand_new_gross_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_gca" name="non_brand_new_gross_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Less: Waiver
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wcp" name="waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wca" name="waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_wcp" name="non_brand_waiver_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_wca" name="non_brand_waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wcp" name="new_waiver_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wca" name="new_waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_wcp" name="non_brand_new_waiver_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_wca" name="non_brand_new_waiver_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Net Franchise Fee receivable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="ncp" name="net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="nca" name="net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_ncp" name="non_brand_net_comm_percent" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_nca" name="non_brand_net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_ncp" name="new_net_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_nca" name="new_net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_ncp" name="non_brand_new_net_comm_percent" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="non_brand_new_nca" name="non_brand_new_net_comm_amt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Penalty on outstanding amt
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="penalty" name="penalty" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_penalty" name="new_penalty" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Franchisee fees Payable
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="particulartotal" name="totalcost" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_particulartotal" name="new_totalcost" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Total Amount (FEE)
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="invoice_total" class="form-control td-r" readonly="">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_invoice_total" class="form-control td-r" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Previous outstanding
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="previous_due" name="previous_dues" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_previous_due" name="new_previous_dues" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                <select class="form-control " onchange="calculateexpensecost();" name="tax[]">
                                                    <option value="0">Select tax</option>
                                                    <option value="1">Non Taxable</option>
                                                    <option value="2">GST @0%</option>
                                                    <option value="3">GST @5%</option>
                                                    <option value="4">GST @12%</option>
                                                    <option selected value="5">GST @18%</option>
                                                    <option value="6">GST @28%</option>
                                                </select>
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="gstamt" name="gstamt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_gstamt" name="new_gstamt" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Total royalty to be Paid with Previous outstanding
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="grand_total" class="form-control td-r" value="0" readonly="">
                                                <input type="hidden" id="totaltaxcost" value="0">
                                            </td>
                                            <td colspan="4" class="td-r">
                                                <input type="text" id="new_grand_total" class="form-control td-r" value="0" readonly="">
                                                <input type="hidden" id="new_totaltaxcost" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label>Credit / Debit Note Amount/ Royalty</label></td>
                                            <td colspan="4"></td>
                                            <td colspan="4">
                                                <input type="hidden" name="particular_id[]" value="0">
                                                <input type="hidden" name="particular[]" value="Franchise fee">
                                                <input type="hidden" name="sac[]" value="">
                                                <input type="hidden" name="unit[]" value="1">
                                                <input type="hidden" name="total[]" value="0">
                                                <input type="hidden" name="rate[]" id="rate" value="0">
                                                <input type="hidden" id="cgst_amt" name="cgst_amt" value="0.00">
                                                <input type="hidden" id="sgst_amt" name="sgst_amt" value="0.00">
                                                <input type="hidden" id="igst_amt" name="igst_amt" value="0.00">
                                                <input type="hidden" id="sub_total" name="sub_total" value="0.00">
                                                <div id="cgst" style="display: none;"></div>
                                                <div id="sgst" style="display: none;"></div>
                                                <div id="igst" style="display: none;"></div>
                                                <input type="text" class="form-control" readonly="" id="total" name="total" value="0.00">
                                            </td>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>

                        </div>
                        @else
                        <h3 class="form-section">Add particulars
                            <a href="javascript:;" onclick="AddExpenseParticular();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
                        </h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover" id="particular_table">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            <label class="control-label">Particular <span class="required">*</span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">SAC/HSN Code <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Unit <span class="required">*</span></label>

                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Rate <span class="required">*</span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Tax <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Total <span class="required"></span></label>
                                        </th>
                                        <th class="td-c">
                                            <label class="control-label">Action <span class="required"></span></label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_particular">
                                    <tr>
                                        <td>
                                            <input type="text" required="" name="particular[]" class="form-control " placeholder="Particular">
                                        </td>
                                        <td><input type="text" name="sac[]" class="form-control " placeholder="SAC/HSN Code"></td>
                                        <td><input required type="number" step="1" name="unit[]" onblur="calculateexpensecost();" class="form-control " placeholder="Unit"></td>
                                        <td>
                                            <input type="number" required step="0.01" name="rate[]" onblur="calculateexpensecost();" class="form-control " placeholder="Rate">
                                        </td>
                                        <td>
                                            <select class="form-control " onchange="calculateexpensecost();" name="tax[]">
                                                <option value="0">Select tax</option>
                                                <option value="1">Non Taxable</option>
                                                <option value="2">GST @0%</option>
                                                <option value="3">GST @5%</option>
                                                <option value="4">GST @12%</option>
                                                <option value="5">GST @18%</option>
                                                <option value="6">GST @28%</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="total[]" class="form-control " readonly=""><input type="hidden" name="particular_id[]" value="Na"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border-bottom: none;"></td>
                                        <td colspan="2"><label>Sub Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sub_total" name="sub_total" value="0.00"></td>
                                    </tr>

                                    <tr id="cgst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>CGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="cgst_amt" name="cgst_amt" value="0.00"></td>
                                    </tr>
                                    <tr id="sgst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>SGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sgst_amt" name="sgst_amt" value="0.00"></td>
                                    </tr>
                                    <tr id="igst" style="display: none;">
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>IGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="igst_amt" name="igst_amt" value="0.00"></td>
                                    </tr>



                                    <tr>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly id="total" name="total" value="0.00">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif

                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control"></textarea>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/creditnote/viewlist" class="btn default">Cancel</a>
                                        <input type="hidden" id="gst_type" name="gst_type">
                                        <input type="hidden" value="1" name="type">
                                        <input type="hidden" value="{{$type}}" name="credit_note_type">
                                        <input type="submit" value="Save" class="btn blue" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="submit_form" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closeauto" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Credit note number</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>

                                <div class="form-group">
                                    <h5>Your credit note number is on auto-generate mode to save your time. Are you sure about changing this setting?</h5>

                                </div>

                                <div class="form-group">
                                    <label for="autogen" class="col-md-12 control-label">
                                        <input type="radio" id="autogen" name="auto_generate" value="1" @if($credit_note_auto_generate==1) checked="" @endif> Continue auto-generating numbers
                                    </label>
                                    <div class="col-md-8">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <p>Prefix</p>
                                                <input type="text" name="prefix" id="prefix" maxlength="10" value="{{$prefix}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <p>Number</p>
                                                <input type="text" name="prefix_val" value="{{$prefix_val}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="auto2" class="col-md-8 control-label">
                                        <input type="radio" class="icheck" id="auto2" name="auto_generate" @if($credit_note_auto_generate!=1) checked="" @endif value="0" /> I will add them manually each time
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="seq_id" name="seq_id" value="{{$prefix_id}}">
                    <button type="button" onclick="return saveExpenseSequence(5);" class="btn blue">Save</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- BEGIN FOOTER -->



@endsection