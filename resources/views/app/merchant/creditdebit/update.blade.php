@extends('app.master')

@section('content')
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('merchant.debitnote.update',$detail->type,$detail->credit_debit_no) }}
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
                    <form action="/merchant/creditnote/updatesave" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        @if($detail->type==1)
                                        Credit note information
                                        @else
                                        Debit note information
                                        @endif
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">@if($detail->type==1)Credit note number @else Debit note number @endif <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input id="expense_auto_generate" name="credit_note_no" type="text" @if($auto_generate==1) readonly="" @endif value="{{$detail->credit_debit_no}}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required name="credit_note_date" value='<x-localize :date="$detail->date" type="date" />' autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Date" />
                                        </div>
                                    </div>
                                    @if($detail->type==1)
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select customer <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" onchange="setCustomerState(this.value);" data-placeholder="Select customer" required="" name="customer_id">
                                                <option value="">Select customer</option>
                                                @foreach($customers as $v)
                                                @if($detail->customer_id==$v->customer_id)
                                                <option selected="" value="{{$v->customer_id}}">{{$v->first_name}} {{$v->last_name}} - {{$v->customer_code}}</option>
                                                @else
                                                <option value="{{$v->customer_id}}">{{$v->first_name}} {{$v->last_name}} - {{$v->customer_code}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="vendor_id" value="0">
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Select Vendor <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select class="form-control select2me" onchange="setVendorState(this.value);" data-placeholder="Select vendor" required="" name="vendor_id">
                                                <option value="">Select vendor</option>
                                                @foreach($vendors as $v)
                                                @if($detail->vendor_id==$v->vendor_id)
                                                <option selected="" value="{{$v->vendor_id}}">{{$v->vendor_name}} - {{$v->state}}</option>
                                                @else
                                                <option value="{{$v->vendor_id}}">{{$v->vendor_name}} - {{$v->state}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="customer_id" value="0">
                                    </div>
                                    @endif

                                </div>
                                <div class="col-md-6">
                                    <h3 class="form-section">
                                        Invoice information
                                    </h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice number <span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" maxlength="45" value="{{$detail->invoice_no}}" name="invoice_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Bill date <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required name="bill_date" value='<x-localize :date="$detail->bill_date" type="date" />' autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Due date<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input class="form-control form-control-inline date-picker" type="text" required name="due_date" value='<x-localize :date="$detail->due_date" type="date" />' autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="" />
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
                        @if($detail->credit_note_type==2)
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
                                        @foreach($sales as $key=>$row)
                                        <tr>
                                            <td class="td-c">
                                                <input type="text" required="" value='<x-localize :date="$row->date" type="date" />'' name="sale_date[]" class="form-control date-picker" placeholder="Date" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" value="{{$row->inv_gross_sale}}" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="gross_sale[]" class="form-control">
                                                <input type="hidden" name="gs_id[]" value="{{$row->id}}">
                                            </td>
                                            <td colspan="2" class="td-c">
                                                <input type="number" step="0.01" value="{{$row->credit_gross_sale}}" placeholder="Gross sale" onblur="calculateFranchiseSale();" required="" name="new_gross_sale[]" class="form-control">
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="td-c">
                                                Gross Billable Sales
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="gbs" name="gross_bilable_sale" value="{{$summary->gross_bilable_sale}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_gbs" name="new_gross_bilable_sale" value="{{$summary->new_gross_bilable_sale}}" class="form-control td-r" value="0" readonly="">
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
                                                <input type="text" id="nbs" name="net_bilable_sale" value="{{$summary->net_bilable_sale}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_nbs" name="new_net_bilable_sale" value="{{$summary->new_net_bilable_sale}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Gross Franchisee Fee on Net Billable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gcp" name="gross_comm_percent" value="{{$summary->gross_comm_percent}}" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="gca" name="gross_comm_amt" value="{{$summary->gross_comm_amt}}" class="form-control td-r" value="0&quot;" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gcp" name="new_gross_comm_percent" value="{{$summary->new_gross_comm_percent}}" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_gca" name="new_gross_comm_amt" value="{{$summary->new_gross_comm_amt}}" class="form-control td-r" value="0&quot;" readonly="">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Less: Waiver
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wcp" name="waiver_comm_percent" value="{{$summary->waiver_comm_percent}}" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="wca" name="waiver_comm_amt" value="{{$summary->waiver_comm_amt}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wcp" name="new_waiver_comm_percent" value="{{$summary->new_waiver_comm_percent}}" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_wca" name="new_waiver_comm_amt" value="{{$summary->new_waiver_comm_amt}}" class="form-control td-r" value="0" readonly="">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Net Franchise Fee receivable
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="ncp" name="net_comm_percent" value="{{$summary->net_comm_percent}}" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="nca" name="net_comm_amt" value="{{$summary->net_comm_amt}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_ncp" name="new_net_comm_percent" value="{{$summary->new_net_comm_percent}}" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                            <td class="td-r">
                                                <input type="text" id="new_nca" name="new_net_comm_amt" value="{{$summary->new_net_comm_amt}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Penalty on outstanding amt
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="penalty" name="penalty" value="{{$summary->penalty}}" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_penalty" name="new_penalty" value="{{$summary->new_penalty}}" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-c">
                                                Franchisee fees Payable
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="particulartotal" name="totalcost" value="{{$summary->totalcost}}" class="form-control td-r" value="0" readonly="">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_particulartotal" name="new_totalcost" value="{{$summary->new_totalcost}}" class="form-control td-r" value="0" readonly="">
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
                                                <input type="text" id="previous_due" name="previous_dues" value="{{$summary->previous_dues}}" onblur="franchiseSummary();" class="form-control td-r" value="0">
                                            </td>
                                            <td colspan="2" class="td-r">
                                                <input type="text" id="new_previous_due" name="new_previous_dues" value="{{$summary->new_previous_dues}}" onblur="franchiseSummaryNew();" class="form-control td-r" value="0">
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
                                                @foreach($particulars as $key=>$row)
                                                <input type="hidden" required="" name="particular[]" value="{{$row->particular_name}}">
                                                <input type="hidden" name="sac[]" value="{{$row->sac_code}}" class="form-control input-sm">
                                                <input type="hidden" step="1" name="unit[]" value="{{$row->qty}}" onblur="calculateexpensecost();">
                                                <input type="hidden" step="0.01" name="rate[]" id="rate" value="{{$row->rate}}" onblur="calculateexpensecost();">
                                                <input type="hidden" name="total[]" value="{{$row->amount}}">
                                                <input type="hidden" name="particular_id[]" value="{{$row->id}}">
                                                @endforeach
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
                                    @foreach($particulars as $key=>$row)
                                    <tr>
                                        <td>
                                            <input type="text" required="" name="particular[]" value="{{$row->particular_name}}" class="form-control input-sm" placeholder="Particular">
                                        </td>
                                        <td><input type="text" name="sac[]" value="{{$row->sac_code}}" class="form-control input-sm" placeholder="SAC/HSN Code"></td>
                                        <td><input type="number" step="1" name="unit[]" value="{{$row->qty}}" onblur="calculateexpensecost();" class="form-control input-sm" placeholder="Unit"></td>
                                        <td>
                                            <input type="number" step="0.01" name="rate[]" value="{{$row->rate}}" onblur="calculateexpensecost();" class="form-control input-sm" placeholder="Rate">
                                        </td>
                                        <td>
                                            <select class="form-control input-sm" onchange="calculateexpensecost();" name="tax[]">
                                                <option value="0">Select tax</option>
                                                <option @if($row->tax==1)selected @endif value="1">Non Taxable</option>
                                                <option @if($row->tax==2)selected @endif value="2">GST @0%</option>
                                                <option @if($row->tax==3)selected @endif value="3">GST @5%</option>
                                                <option @if($row->tax==4)selected @endif value="4">GST @12%</option>
                                                <option @if($row->tax==5)selected @endif value="5">GST @18%</option>
                                                <option @if($row->tax==6)selected @endif value="6">GST @28%</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="total[]" value="{{$row->amount}}" class="form-control input-sm" readonly="">
                                            <input type="hidden" name="particular_id[]" value="{{$row->id}}">
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border-bottom: none;"></td>
                                        <td colspan="2"><label>Sub Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sub_total" name="sub_total" value="{{$detail->base_amount}}"></td>
                                    </tr>

                                    <tr id="cgst" @if($detail->cgst_amount==0)style="display: none;" @endif>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>CGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="cgst_amt" name="cgst_amt" value="{{$detail->cgst_amount}}"></td>
                                    </tr>
                                    <tr id="sgst" @if($detail->sgst_amount==0)style="display: none;" @endif>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>SGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="sgst_amt" name="sgst_amt" value="{{$detail->sgst_amount}}"></td>
                                    </tr>
                                    <tr id="igst" @if($detail->igst_amount==0)style="display: none;" @endif>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>IGST</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly="" id="igst_amt" name="igst_amt" value="{{$detail->igst_amount}}"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border: none;"></td>
                                        <td colspan="2"><label>Total</label></td>
                                        <td colspan="2"><input type="text" class="form-control" readonly id="total" value="{{$detail->total_amount}}" name="total"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endif

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Narrative<span class="required">
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="narrative" class="form-control">{{$detail->narrative}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="">
                                        <div class="row">
                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-6">Notify <span class="required">
                                                        </span></label>
                                                    <div class="col-md-4">
                                                        <input type="checkbox" id="notify_" onchange="notifyPatron('notify_');" checked="" value="1"  class="make-switch" data-size="small">  
                                                    </div>
                                                </div>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        @if($detail->type==1)
                                            <a href="/merchant/creditnote/viewlist" class="btn default">Cancel</a>
                                        @else
                                            <a href="/merchant/debitnote/viewlist" class="btn default">Cancel</a>
                                        @endif
                                        <input type="hidden" id="gst_type" name="gst_type">
                                        <input type="hidden" id="is_notify_" name="notify" value="{{$detail->notify}}">
                                        <input id="subbtn" type="submit" @if($detail->notify==5) value="Save & Send" @else value="Save" @endif class="btn blue"/>
                                        <input type="hidden" name="id" value="{{$link}}">
                                        <input type="hidden" name="type" value="{{$detail->type}}">
                                        <input type="hidden" value="{{$detail->credit_note_type}}" name="credit_note_type">
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-- End profile details -->
            </form>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>




@endsection
@section('footer')
@if($detail->credit_note_type == 2)
<script>
    calculateFranchiseSale();
    franchiseSummary();
</script>
@endif
@endsection