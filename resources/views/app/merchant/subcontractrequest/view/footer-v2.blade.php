@php
$validate=(array)$validate;
@endphp

@if($payment_request_status!=3)
<div class="invoice mt-1 invoice-footer-btns" style="max-width: 1400px;">
    <div class="row no-margin">
        <div class="col-md-12 mt-1">
            @if($payment_request_status==11)
                <form class="form-horizontal invoice-preview-form" action="/merchant/requestpayment/saveinvoice" method="post" onsubmit="document.getElementById('loader').style.display = 'block';" 
                style="display:inline">
                @csrf
                    <div class="col-md-4 pull-left btn-pl-0">
                        @if($invoice_access == 'full' || $invoice_access == 'approve')
                            <div class="input-icon">
                                <label class="control-label pr-1">Notify customer </label> <input type="checkbox" data-cy="notify" id="notify_" onchange="notifyPatron('notify_');" value="1" @if($notify_patron==1) checked @endif class="make-switch" data-size="small">
                                <input type="hidden" id="is_notify_" name="notify_patron" value="{{($notify_patron==1) ? 1 : 0}}" />
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="payment_request_id" value="{{$payment_request_id}}" />
                    <input type="hidden" name="payment_request_type" value="{{$payment_request_type}}" />
                    <input type="hidden" id="preview_invoice_status" name="payment_request_status" value="{{$payment_request_status??0}}" />

                    <div class="view-footer-btn-rht-align">
                        @if(!empty($invoice_access))
                            @if ($invoice_access != 'view-only')
                                @if($payment_request_status == 14 && ($invoice_access == 'full' || $invoice_access == 'approve'))
                                    <input type="button" value="Approve" id="approvebtn" class="btn blue margin-bottom-5 margin-top-15 view-footer-btn-rht-align" />
                                @elseif($invoice_access == 'full')
                                    @if($notify_patron == 1)
                                        <input type="button" value="Save & Send" id="saveandsendbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />
                                    @else
                                        <input type="button" value="Save" id="saveandsendbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />
                                    @endif
                                    {{-- <input type="button" value="Save & Send" id="saveandsendbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />--}}
                                @elseif($invoice_access == 'edit')
                                    <input type="button" value="Save" id="subbtn" class="btn blue margin-bottom-5 margin-top-5 view-footer-btn-rht-align" />
                                @endif
                            @endif

                        @endif
                        {{-- @if($notify_patron==1)
                            <input type="submit" value="Save & Send" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" onclick="saveInvoicePreview('{{$payment_request_id}}',document.getElementById('is_notify_').value,'{{$invoice_number}}','{{$payment_request_type}}');" />
                        @else
                            <input type="submit" value="Save" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" onclick="saveInvoicePreview('{{$payment_request_id}}',document.getElementById('is_notify_').value,'{{$invoice_number}}','{{$payment_request_type}}');" />
                        @endif --}}
                    </div>
                </form>
            @endif
            @if($payment_request_status!=11)
                @if($absolute_cost>1)
                    <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" data-toggle="modal" href="#respond">
                        Settle
                    </a>
                @endif
            @endif
            @if($payment_request_status!=6 && $payment_request_status!=7)
                <div class=" view-footer-btn-rht-align btn-pl-0" style="margin-top:0px;">
                    <a class="btn green hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($invoice_type==1) 15px @else 20px @endif" href="/merchant/subcontract/requestpayment/create/{{$url}}">
                        Update invoice
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif
    
</div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>





</div>
<div class="col-md-1"></div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
<div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
<div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <div class=" ">
                    <div>
                        <div class="form-section">
                            Offline payment
                            <hr>
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" id="respond-form" action="/merchant/paymentrequest/respond" method="post" onsubmit="document.getElementById('respond').style.display = 'none';
                                      document.getElementById('loader').style.display = 'block';">
                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <h4 class="alert-heading">Log partial payment?</h4>
                                            <button class="close" id="md_close" data-close="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        @isset($metadata['plugin']['deductible'])
                                        @if(!empty($metadata['plugin']['deductible']))
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Select
                                                deductible <span class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <select name="selecttemplate" onchange="calculatedeductMerchant('{{$info['tax_amount']}}', '{{$info['basic_amount']-$info['advance']}}', '{{$info['Previous_dues']}}');" id="deduct" required class="form-control" data-placeholder="Select...">
                                                    <option value="0">Select deductible</option>
                                                    @foreach($metadata['plugin']['deductible'] as $key=>$item)
                                                    <option value="{{$item['total']}}">{{$item['tax_name']}} ({{$item['percent']}} %)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        @endisset
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Transaction
                                                type<span class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm" name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                                    <option value="1">NEFT/RTGS</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Cash</option>
                                                    <option value="5">Online Payment</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="cod_status" style="display: none;">
                                            <label for="inputPassword12" class="col-md-4 control-label">COD Status</label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm" name="cod_status" data-placeholder="Select status">
                                                    <option value="cod">COD</option>
                                                    <option value="cash_collected">Cash Collected</option>
                                                    <option value="cash_received">Cash Received</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="bank_transaction_no">
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank ref
                                                no</label>
                                            <div class="col-md-5">
                                                @if($payment_request_status == '2' && isset($info['offline_success_transaction']))
                                                <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="{{$info['offline_success_transaction']->bank_transaction_no}}" placeholder="Bank ref number" />
                                                @else
                                                <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number" />
                                                @endif
                                            </div>
                                        </div>
                                        <div id="cheque_no" style="display: none;">
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Cheque
                                                    no</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" name="cheque_no" {!!$validate['number']!!} type="text" value="" placeholder="Cheque no" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-4 control-label">Cheque
                                                    status</label>
                                                <div class="col-md-5">
                                                    <select class="form-control input-sm" name="cheque_status" data-placeholder="Select status">
                                                        <option value="Deposited">Deposited</option>
                                                        <option value="Realised">Realised</option>
                                                        <option value="Bounced">Bounced</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="cash_paid_to" style="display: none;">
                                            <label for="inputPassword12" class="col-md-4 control-label">Cash paid
                                                to</label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm" name="cash_paid_to" {!!$validate['name']!!} type="text" value="{{$user_name}}" placeholder="Cash paid to" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                @if($payment_request_status == '2' && isset($info['offline_success_transaction']))
                                                <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" required name="date" type="text" value="{{ date('M d Y', strtotime($info['offline_success_transaction']->settlement_date)) }}" placeholder="Date" />
                                                @else
                                                <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" required name="date" type="text" value="" placeholder="Date" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group" id="bank_name">
                                            <label for="inputPassword12" class="col-md-4 control-label">Bank
                                                name</label>
                                            <div class="col-md-5">
                                                @if($payment_request_status == '2' && isset($info['offline_success_transaction']))
                                                <input class="form-control form-control-inline input-sm" name="bank_name" type="text" value="{{$info['offline_success_transaction']->bank_name}}" placeholder="Bank name" />
                                                @else
                                                <input class="form-control form-control-inline input-sm" name="bank_name" type="text" value="" placeholder="Bank name" />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                @if($payment_request_status == '2' && isset($info['offline_success_transaction']))
                                                <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {!!$validate['amount']!!} value="{{$info['offline_success_transaction']->amount}}" placeholder="Amount" />
                                                <input type="hidden" id="original_amount" value="{{$absolute_cost}}" />
                                                @else
                                                <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {!!$validate['amount']!!} value="{{$grand_total_offline}}" placeholder="Amount" />
                                                <input type="hidden" id="original_amount" value="{{$absolute_cost}}" />
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-4 control-label">Send the receipt
                                                to customer <span class="required">
                                                </span></label>
                                            <div class="col-md-5">
                                                <input type="checkbox" name="send_receipt" onchange="showDocument(this.checked)" data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;" value="1" checked class="make-switch" data-size="small">
                                            </div>
                                        </div>
                                        <input type="hidden" name="notification_type" value="1">
                                        <!--<div class="form-group" id="document_div">
                                                <label for="inputPassword12" class="col-md-4 control-label">Notify customer
                                                    on <span class="required">
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control input-sm" name="notification_type">
                                                        <option selected value="1">Email</option>
                                                        <option value="2">SMS</option>
                                                        <option value="3">Email & SMS</option>
                                                    </select>
                                                </div>
                                        </div>-->
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="alert alert-warning" id="partial-warning" style="display: none;">
                                                    <div class="media">
                                                        <p class="media-heading">
                                                            <b>Log partial payment?</b>
                                                        </p>
                                                        <p>
                                                            You have chosen to settle (close) the invoice with an amount lesser than the full amount due. Would you like to mark this invoice as partially paid?
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-4"></div>
                                            <div class="col-md-8 center">

                                                <input type="hidden" name="payment_req_id" value="{{$payment_request_id}}" />
                                                <input type="hidden" class="displayonly" id="bill_total" value="{{$invoice_total}}" />
                                                <input type="hidden" class="displayonly" id="coupon_used" readonly name="coupon_used" value="0" />
                                                <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['coupon_id']}}@endif" />
                                                <input type="hidden" class="displayonly" id="surcharge_amount" value="{{$surcharge_amount}}" />
                                                <input type="hidden" class="displayonly" id="fee_id" value="@if(isset($info['fee_id'])){{$info['fee_id']}}@endif" />
                                                <input type="hidden" class="displayonly" name="discount" id="discount_amt" value="" />
                                                <input type="hidden" class="displayonly" id="c_percent" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['percent']}}@endif" />
                                                <input type="hidden" class="displayonly" id="grand_total" value="{{$grand_total}}" />
                                                <input type="hidden" class="displayonly" id="c_fixed_amount" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['fixed_amount']}}@endif" />
                                                <input type="hidden" class="displayonly" id="paymenturl" value="/patron/paymentrequest/pay/{{$url}}" />
                                                <input type="hidden" class="displayonly" id="is_partial_field" name="is_partial" value="0" />
                                                @if(!empty($info['offline_response_id']))
                                                <input type="hidden" class="displayonly" id="offline_response_id" name="offline_response_id" value="{{$info['offline_response_id']}}" />
                                                @endif
                                                <input name="deduct_amount" id="deduct_amount" type="hidden" class="displayonly" value="0" />
                                                <input name="deduct_text" id="deduct_text" type="hidden" class="displayonly" value="" />
                                                @if($payment_request_status == '2' && !empty($info['offline_response_id']))
                                                <a href="#delete-confirm-modal" id="deletebtn" data-toggle="modal" onclick="document.getElementById('deleteanchor').href = '/merchant/transaction/delete/{{$info['offline_response_id']}}'" class="btn swipez-draft-btn pull-right mr-1">Delete Transaction</a>
                                                @endif
                                                @if($payment_request_status != '2')
                                                <button id="settlebutton" type="submit" onclick="return validatePartial();" class="btn blue center pull-right mr-1">Settle
                                                    Invoice</button>
                                                @endif

                                                <button id="settlebuttonconfirm" style="display: none;" type="submit" onclick="return validatePartial();" class="btn green center pull-right mr-1">Settle
                                                    Invoice</button>
                                                @if($payment_request_status == '2' || $payment_request_status == '7')
                                                <button id="partialbtn" type="button" onclick="savePartialPayment();" class="btn green pull-right mr-1">Save Partial Payment</button>
                                                @else
                                                @isset($metadata['plugin']['has_partial'])
                                                @if($metadata['plugin']['has_partial']==1)
                                                <button id="partialbtn" type="button" onclick="savePartialPayment();" class="btn green pull-right mr-1">Save Partial Payment</button>
                                                @endif
                                                @endisset
                                                @endif


                                                <button data-dismiss="modal" onclick="resetSettle();" aria-hidden="true" class="btn green pull-right mr-1">Cancel</button>

                                            </div>
                                            <div class="col-md-2">
                                            </div>
                                        </div>
                                    </div>


                                </form>
                            </div>


                        </div>


                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>

<script>
    function checkValue(id, check) {
        if (check == true) {
            val = 1;
            style = 'block';
        } else {
            val = 0;
            style = 'none';
        }
        document.getElementById(id).value = val;
        document.getElementById('is_enable_payments_').value = val;
    }

    function savePartialPayment() {
        $("#is_partial_field").val('1');
        $("#respond-form").submit();
    }
    $(function() {
        let invoicePreviewForm = $('.invoice-preview-form');
        $("#subbtn").on("click", function() {

            let paymentRequestStatus = invoicePreviewForm.find('input[name=payment_request_status]');
            paymentRequestStatus.val(14);
            invoicePreviewForm.submit();
        })
        $("#saveandsendbtn").on("click", function() {
            let paymentRequestStatus = invoicePreviewForm.find('input[name=payment_request_status]');
            // let notifyPatronStatus = invoicePreviewForm.find('input[name=notify_patron]');
            paymentRequestStatus.val(0);
            // notifyPatronStatus.val(1);
            invoicePreviewForm.submit();
        })
        $("#approvebtn").on("click", function() {
            let paymentRequestStatus = invoicePreviewForm.find('input[name=payment_request_status]');
            paymentRequestStatus.val(0);
            invoicePreviewForm.submit();
        })
    })
</script>
<style>
    .swipez-draft-btn {
        color: #611818;
        background: #FFFFFF;
        border: 1px solid #FCE8E8;
    }

    .invoice-footer-btns {
        margin-bottom: 100px;
    }
</style>