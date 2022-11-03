{if $payment_request_status!=3}
    <div class="invoice mt-1">
        {if isset($transaction)}
            <h5 class="invoice-sub-title"><b>Payment details</b></h5>
            <table class="table table-bordered table-condensed">
                <tr>
                    <th>Receipt no.</th>
                    <th>Receipt date</th>
                    <th>Customer</th>
                    <th>Payment method</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>{$transaction.transaction_id}</td>
                    <td>{$transaction.date}</td>
                    <td>{$transaction.patron_name}</td>
                    <td>{$transaction.payment_mode}</td>
                    <td>{$transaction.amount}</td>
                </tr>
            </table>
        {/if}
        {if !empty($supplierlist)}
            <div class="row no-margin">
                <h5 class="invoice-sub-title">Suppliers</h5>
                <div class="col-xs-12">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Company name
                                </th>
                                <th>
                                    Contact name
                                </th>
                                <th>
                                    Mobile
                                </th>
                                <th>
                                    Email
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {section name=sec1 loop=$supplierlist}
                                <tr>
                                    <td>{if {$supplierlist[sec1].supplier_company_name} !=
                                        ''}{$supplierlist[sec1].supplier_company_name}{else}&nbsp;
                                        {/if}</td>
                                    <td>{if {$supplierlist[sec1].contact_person_name} !=
                                        ''}{$supplierlist[sec1].contact_person_name}{else}&nbsp;
                                        {/if}</td>
                                    <td>{if {$supplierlist[sec1].mobile1} != ''}{$supplierlist[sec1].mobile1}{else}&nbsp;{/if}</td>
                                    <td>{if {$supplierlist[sec1].email_id1} != ''}{$supplierlist[sec1].email_id1}{else}&nbsp;{/if}
                                    </td>
                                </tr>
                            {/section}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}
        {if $info.document_url!=''}
            <div class="row" id="document_div" style="display: none;">
                <button class="btn btn-default pull-right" onclick="showDocument(2);" style="margin-right: 15px"><i
                        class="fa fa-close"></i></button>
                <div class="col-md-12">
                    <div style="max-height: 700px;overflow: auto;">
                        {if {$info.document_url|substr:-3|lower}=='pdf'}
                            <iframe src="{$info.document_url}" frameborder="no" width="100%" height="500px">
                            </iframe>
                        {else}
                            <img src="{$info.document_url}" style="width: 100%;max-width: 100%;">
                        {/if}
                    </div>
                    <br>

                </div>
            </div>
        {/if}

        {if $payment_request_status!=1 && $payment_request_status!=2 && $payment_request_status!=3}
            {if !empty($coupon_details)}
                {* <div class="row">
                <div class="col-md-12">
                    <table class="table" style="background-color: #fcf8e3;">
                        <tbody>
                            <tr>
                                <td>
                                    Coupon discount : <b>{if $coupon_details.type==1} Rs.{$coupon_details.fixed_amount}/-
                                        {else}{$coupon_details.percent}%
                                        {/if}</b>
                                    <p class="small">{$coupon_details.descreption}</p>
                                </td>
                                <td>
                                    <button class="btn blue pull-right" id="btn_apply_coupun" style="display: block;"
                                        onclick="handleCoupon(1,{$coupon_details.type});">Apply Coupon <i
                                            class="fa fa-check"></i></button>
                                    <button class="btn btn-link pull-right" id="btn_remove_coupon" style="display: none;"
                                        onclick="handleCoupon(2,{$coupon_details.type});">Remove Coupon <i
                                            class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div> *}
            {/if}
            <div class="row no-margin">
                <div class="col-md-12 mt-1">
                    {if $info.payment_request_status==11}
                        <form class="form-horizontal" action="/merchant/invoice/saveInvoicePreview/{$info.payment_request_id}" method="post" onsubmit="document.getElementById('loader').style.display = 'block';">
                            <div class="col-md-4 pull-left btn-pl-0">
                                <div class="input-icon">
                                    <label class="control-label pr-1">Notify customer </label> <input type="checkbox" data-cy="notify" id="notify_" onchange="notifyPatron('notify_');" value="1" {if $info.notify_patron==1} checked {/if} class="make-switch" data-size="small">
                                    <input type="hidden" id="is_notify_" name="notify_patron" value="{($info.notify_patron==1) ? 1 : 0}" />
                                </div>
                            </div> 
                            <input type="hidden" name="payment_request_id" value="{$info.payment_request_id}" />
                            <input type="hidden" name="payment_request_type" value="{$info.payment_request_type}" />
                            <div class="view-footer-btn-rht-align">
                                {if $info.notify_patron==1}
                                    <input type="submit" value="Save & Send" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" onclick="saveInvoicePreview('{$info.payment_request_id}',document.getElementById('is_notify_').value,'{$info.invoice_number}','{$info.payment_request_type}');" />
                                {else}
                                    <input type="submit" value="Save" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" onclick="saveInvoicePreview('{$info.payment_request_id}',document.getElementById('is_notify_').value,'{$info.invoice_number}','{$info.payment_request_type}');" />
                                {/if}
                            </div>
                        </form>
                    {/if}
                    {if $info.payment_request_status!=11}
                        {if $grand_total>1}
                            {if $info.invoice_type==2}
                                {if $payment_request_status!=6}
                                    <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" data-toggle="modal" href="#convert">
                                        Convert to Invoice
                                    </a>
                                    <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: 20px;" data-toggle="modal"
                                        href="#settleestimate">
                                        Settle
                                    </a>
                                {/if}
                            {else}
                                <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" data-toggle="modal" href="#respond">
                                    Settle
                                </a>
                            {/if}
                        {/if}
                    {/if}
                    {if $payment_request_status!=6 && $payment_request_status!=7}
                        <div class="col-md-2 view-footer-btn-rht-align btn-pl-0">
                            <a class="btn green hidden-print margin-bottom-5 view-footer-btn-rht-align" 
                                href="/merchant/invoice/update/{$Url}">
                                Update {if $info.invoice_type==1} invoice {else} estimate {/if}
                            </a>
                        </div>
                    {/if}
                    {if $info.payment_request_status!=11}
                        {if $plugin.has_digital_certificate_file==1}
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: {if $info.invoice_type==1} 15px {else} 20px {/if}"
                                href="/merchant/paymentrequest/download_tcpdf/{$Url}">
                                Save as PDF
                            </a>
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: {if $info.invoice_type==1} 15px {else} 20px {/if}"
                                href="/merchant/paymentrequest/download_tcpdf/{$Url}/2">
                                Print
                            </a>
                        {else}
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: {if $info.invoice_type==1} 15px {else} 20px {/if}"
                                href="/merchant/paymentrequest/download/{$Url}">
                                Save as PDF
                            </a>
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: {if $info.invoice_type==1} 15px {else} 20px {/if}"
                                href="/merchant/paymentrequest/download/{$Url}/2">
                                Print
                            </a>
                        {/if}
                    {/if}
                    
                    {if $info.document_url!=''}
                        <button onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align"
                            style="margin-right: 20px;">
                            {$plugin.upload_file_label}
                        </button>
                    {/if}
                </div>
            </div>
        {else}
            <div class="row no-margin">
                <div class="col-md-12">
                    {if $plugin.has_digital_certificate_file==1}
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"
                            href="/merchant/paymentrequest/download_tcpdf/{$Url}">
                            Save as PDF
                        </a>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"
                            href="/merchant/paymentrequest/download_tcpdf/{$Url}/2">
                            Print
                        </a>
                    {else}
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"
                            href="/merchant/paymentrequest/download/{$Url}">
                            Save as PDF
                        </a>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;"
                            href="/merchant/paymentrequest/download/{$Url}/2">
                            Print
                        </a>
                    {/if}
                </div>
            </div>
            {if $info.document_url!=''}
                <button onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5 pull-right"
                    style="margin-right: 20px;">
                    {$plugin.upload_file_label}
                </button>
            {/if}
        {/if}
        {if !empty($partial_payments)}

            <div class="portlet-body">
                <h4><b>Payment details</b></h4>
                <div class="table-scrollable">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="td-c">
                                    Transaction ID
                                </th>
                                <th class="td-c">
                                    Payment date
                                </th>
                                <th class="td-c">
                                    Payment mode
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                            </tr>
                            {foreach from=$partial_payments item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.transaction_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.payment_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.payment_mode}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        {/if}

    </div>
    {if !empty($commentlist)}
        <div class="row no-margin" style="">
            <br>
            <div class="col-md-12 portlet light bordered" style="text-align: left;">
                <a href="/merchant/comments/view/{$Url}" title="Comments" class="btn btn-xs blue iframe pull-right"><i
                        class="fa fa-comment"></i> </a>
                {foreach from=$commentlist item=v}
                    <div class="media">
                        <div class="media-body">
                            <h5 class="media-heading">{$v.name} <span>
                                    {$v.last_update_date} - <a class="iframe" href="/merchant/comments/update/{$v.link}">
                                        Edit </a>/
                                    <a class="iframe" href="/merchant/comments/delete/{$v.link}">
                                        Delete </a>
                                </span>
                            </h5>
                            <p>
                                {$v.comment}
                            </p>
                            <hr>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    {/if}
{/if}
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

                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="form-section">
                            Offline payment
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-10">
                                <form class="form-horizontal" action="/merchant/paymentrequest/respond" method="post"
                                    onsubmit="document.getElementById('respond').style.display = 'none';
                                              document.getElementById('loader').style.display = 'block';">
                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" id="md_close" data-close="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        {if !empty($plugin.deductible)}
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Select
                                                    deductible <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <select name="selecttemplate"
                                                        onchange="calculatedeductMerchant('{$info.tax_amount}', '{$info.basic_amount-$advance}', '{$info.Previous_dues}');"
                                                        id="deduct" required class="form-control"
                                                        data-placeholder="Select...">
                                                        <option value="0">Select deductible</option>
                                                        {foreach from=$plugin.deductible item=v}
                                                            <option value="{$v.total}">{$v.tax_name} ({$v.percent} %)</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Transaction
                                                type<span class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm select2me" name="response_type"
                                                    onchange="responseType(this.value);" data-placeholder="Select type">
                                                    <option value="1">NEFT/RTGS</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Cash</option>
                                                    <option value="5">Online Payment</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group" id="cod_status" style="display: none;">
                                            <label for="inputPassword12" class="col-md-5 control-label">COD Status</label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm" name="cod_status"
                                                    data-placeholder="Select status">
                                                    <option value="cod">COD</option>
                                                    <option value="cash_collected">Cash Collected</option>
                                                    <option value="cash_received">Cash Received</option>
                                                </select>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group" id="bank_transaction_no">
                                            <label for="inputPassword12" class="col-md-5 control-label">Bank ref
                                                no</label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm"
                                                    name="bank_transaction_no" type="text" value=""
                                                    placeholder="Bank ref number" />
                                            </div>
                                        </div>
                                        <div id="cheque_no" style="display: none;">
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                    no</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm"
                                                        name="cheque_no" {$validate.number} type="text" value=""
                                                        placeholder="Cheque no" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                    status</label>
                                                <div class="col-md-5">
                                                    <select class="form-control input-sm" name="cheque_status"
                                                        data-placeholder="Select status">
                                                        <option value="Deposited">Deposited</option>
                                                        <option value="Realised">Realised</option>
                                                        <option value="Bounced">Bounced</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="cash_paid_to" style="display: none;">
                                            <label for="inputPassword12" class="col-md-5 control-label">Cash paid
                                                to</label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm"
                                                    name="cash_paid_to" {$validate.name} type="text"
                                                    value="{$user_name}" placeholder="Cash paid to" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Date <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm date-picker"
                                                    onkeypress="return false;" autocomplete="off"
                                                    data-date-format="dd M yyyy" required name="date" type="text"
                                                    value="" placeholder="Date" />
                                            </div>
                                        </div>
                                        <div class="form-group" id="bank_name">
                                            <label for="inputPassword12" class="col-md-5 control-label">Bank
                                                name</label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm select2me" name="bank_name"
                                                    data-placeholder="Select bank name">
                                                    <option value=""></option>
                                                    {html_options values=$bank_value selected={$bank_selected}
                                                    output=$bank_value}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Amount <span
                                                    class="required">*
                                                </span></label>
                                            <div class="col-md-5">
                                                <input class="form-control form-control-inline input-sm" id="total"
                                                    name="amount" required type="text" {$validate.amount}
                                                    value="{$info.absolute_cost}" placeholder="Amount" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputPassword12" class="col-md-5 control-label">Send the receipt
                                                to customer <span class="required">
                                                </span></label>
                                            <div class="col-md-5">
                                                <input type="checkbox" name="send_receipt"
                                                    onchange="showDocument(this.checked)"
                                                    data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;"
                                                    value="1" checked class="make-switch" data-size="small">
                                            </div>
                                        </div>
                                        <div class="form-group" id="document_div">
                                            <label for="inputPassword12" class="col-md-5 control-label">Notify customer
                                                on <span class="required">
                                                </span></label>
                                            <div class="col-md-5">
                                                <select class="form-control input-sm" name="notification_type">
                                                    <option selected value="1">Email</option>
                                                    <option value="2">SMS</option>
                                                    <option value="3">Email & SMS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7 center">
                                                <input type="hidden" name="payment_req_id"
                                                    value="{$payment_request_id}" />
                                                <input type="hidden" class="displayonly" id="bill_total"
                                                    value="{$invoice_total}" />
                                                <input type="hidden" class="displayonly" id="coupon_used" readonly
                                                    name="coupon_used" value="0" />
                                                <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id"
                                                    value="{$coupon_details.coupon_id}" />
                                                <input type="hidden" class="displayonly" id="surcharge_amount"
                                                    value="{$surcharge_amount}" />
                                                <input type="hidden" class="displayonly" id="fee_id"
                                                    value="{$fee_id}" />
                                                <input type="hidden" class="displayonly" name="discount"
                                                    id="discount_amt" value="" />
                                                <input type="hidden" class="displayonly" id="c_percent"
                                                    value="{$coupon_details.percent}" />
                                                <input type="hidden" class="displayonly" id="grand_total"
                                                    value="{$grand_total}" />
                                                <input type="hidden" class="displayonly" id="c_fixed_amount"
                                                    value="{$coupon_details.fixed_amount}" />
                                                <input type="hidden" class="displayonly" id="paymenturl"
                                                    value="/patron/paymentrequest/pay/{$Url}" />
                                                <input name="deduct_amount" id="deduct_amount" type="hidden"
                                                    class="displayonly" value="0" />
                                                <input name="deduct_text" id="deduct_text" type="hidden"
                                                    class="displayonly" value="" />
                                                <button data-dismiss="modal" aria-hidden="true"
                                                    class="btn green">Cancel</button>
                                                {if $plugin.has_partial==1}
                                                    <button type="submit" name="is_partial"
                                                        class="btn green pull-right mr-1">Save Partial Payment</button>
                                                {/if}

                                                <button type="submit" class="btn blue center middle">Settle
                                                    Invoice</button>

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

<div class="modal fade" id="convert" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Convert to Invoice</h4>
            </div>
            <form class="form-horizontal" action="/merchant/paymentrequest/estimateinvoice" method="post" onsubmit="document.getElementById('convert').style.display = 'none';
                              document.getElementById('loader').style.display = 'block';">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" id="md_close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                {if ($plugin.has_online_payments==1)}
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-10">
                                            Your estimate has online payments disabled. Would you like to enable online
                                            payments on the converted invoice?
                                            
                                    </label>
                                    <div class="col-md-2">
                                        <div class="pull-right">
                                        <input type="checkbox" data-cy="plugin_online_payments""
                                            data-on-text=" &nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;"
                                            class="make-switch pull-right" data-size="small"
                                            onchange="checkValue('is_online_payments_', this.checked);">
                                            <input type="hidden" id="is_online_payments_"
                                                {if (isset($plugin['has_online_payments']) && $plugin['has_online_payments']=='1') }
                                                value="1" {else} value="0" {/if} name="has_online_payments" />
                                            <input type="hidden" id="is_enable_payments_" name="enable_payments"
                                                {if (isset($plugin['enable_payments']) && $plugin['enable_payments']=='1') }
                                                value="1" {else} value="0" {/if} />
                                        </div>
                                    </div>


                                </div>
                                <hr>
                                {/if}
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-10">Send this
                                        invoice to customer <span class="required">
                                        </span></label>
                                    <div class="col-md-2">
                                        <div class="pull-right">
                                            <input type="checkbox" name="send_invoice"
                                                data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;"
                                                value="1" checked class="make-switch" data-size="small">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="hidden" name="estimate_req_id" value="{$payment_request_id}" />
                    <button type="submit" class="btn blue">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<div class="modal fade bs-modal-lg" id="settleestimate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Settle Estimate
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-10">
                                <form class="form-horizontal" action="/merchant/paymentrequest/estimatesettle"
                                    method="post" onsubmit="document.getElementById('settleestimate').style.display = 'none';
                                                      document.getElementById('loader').style.display = 'block';">
                                            <div class="form-body">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" id="md_close" data-close="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>
                                                {if !empty($plugin.deductible)}
                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Select
                                                            deductible <span class="required">*
                                                            </span></label>
                                                        <div class="col-md-5">
                                                            <select name="selecttemplate"
                                                                onchange="calculatedeductMerchant('{$info.tax_amount}', '{$info.basic_amount-$advance}', '{$info.Previous_dues}');"
                                                                id="deduct" required class="form-control"
                                                                data-placeholder="Select...">
                                                                <option value="0">Select deductible</option>





                                                              {foreach from=$plugin.deductible item=v}
                                                                    <option value="{$v.total}">{$v.tax_name} ({$v.percent} %)</option>





                                                              {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>





                                                          {/if}
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Transaction
                                                        type<span class="required">*
                                                        </span></label>
                                                    <div class="col-md-5">
                                                        <select class="form-control input-sm select2me" name="response_type"
                                                            onchange="responseType(this.value);" data-placeholder="Select type">
                                                            <option value="1">NEFT/RTGS</option>
                                                            <option value="2">Cheque</option>
                                                            <option value="3">Cash</option>
                                                            <option value="5">Online Payment</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group" id="bank_transaction_no">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Bank ref
                                                        no</label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm"
                                                            name="bank_transaction_no" type="text" value=""
                                                            placeholder="Bank ref number" />
                                                    </div>
                                                </div>
                                                <div id="cheque_no" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                            no</label>
                                                        <div class="col-md-5">
                                                            <input class="form-control form-control-inline input-sm"
                                                                name="cheque_no" {$validate.number} type="text" value=""
                                                                placeholder="Cheque no" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                            status</label>
                                                        <div class="col-md-5">
                                                            <select class="form-control input-sm" name="cheque_status"
                                                                data-placeholder="Select status">
                                                                <option value="Deposited">Deposited</option>
                                                                <option value="Realised">Realised</option>
                                                                <option value="Bounced">Bounced</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="cash_paid_to" style="display: none;">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Cash paid
                                                        to</label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm"
                                                            name="cash_paid_to" {$validate.name} type="text"
                                                            value="{$user_name}" placeholder="Cash paid to" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Date <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm date-picker"
                                                            onkeypress="return false;" autocomplete="off"
                                                            data-date-format="dd M yyyy" required name="date" type="text"
                                                            value="" placeholder="Date" />
                                                    </div>
                                                </div>
                                                <div class="form-group" id="bank_name">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Bank
                                                        name</label>
                                                    <div class="col-md-5">
                                                        <select class="form-control input-sm select2me" name="bank_name"
                                                            data-placeholder="Select bank name">
                                                            <option value=""></option>





                                                          {html_options values=$bank_value selected={$bank_selected}
                                                            output=$bank_value}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Amount <span
                                                            class="required">*
                                                        </span></label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm" id="total"
                                                            name="amount" required type="text" {$validate.amount}
                                                            value="{$info.absolute_cost}" placeholder="Amount" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Send the receipt
                                                        & invoice to customer <span class="required">
                                                        </span></label>
                                                    <div class="col-md-5">
                                                        <input type="checkbox" name="send_receipt"
                                                            data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;"
                                                            value="1" checked class="make-switch" data-size="small">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-8"></div>
                                                    <div class="col-md-3">
                                                        <input type="hidden" name="estimate_req_id"
                                                            value="{$payment_request_id}" />
                                                        <input type="hidden" class="displayonly" id="bill_total"
                                                            value="{$invoice_total}" />
                                                        <input type="hidden" class="displayonly" id="coupon_used" readonly
                                                            name="coupon_used" value="0" />
                                                        <input type="hidden" class="displayonly" name="coupon_id"
                                                            value="{$coupon_details.coupon_id}" />
                                                        <input type="hidden" class="displayonly" id="surcharge_amount"
                                                            value="{$surcharge_amount}" />
                                                        <input type="hidden" class="displayonly" id="fee_id"
                                                            value="{$fee_id}" />
                                                        <input type="hidden" class="displayonly" name="discount"
                                                            id="discount_amt" value="" />
                                                        <input type="hidden" class="displayonly" id="c_percent"
                                                            value="{$coupon_details.percent}" />
                                                        <input type="hidden" class="displayonly" id="grand_total"
                                                            value="{$grand_total}" />
                                                        <input type="hidden" class="displayonly" id="c_fixed_amount"
                                                            value="{$coupon_details.fixed_amount}" />
                                                        <input type="hidden" class="displayonly" id="paymenturl"
                                                            value="/patron/paymentrequest/pay/{$Url}" />
                                                        <input name="deduct_amount" id="deduct_amount" type="hidden"
                                                            class="displayonly" value="0" />
                                                        <input name="deduct_text" id="deduct_text" type="hidden"
                                                            class="displayonly" value="" />
                                                        <button type="submit" class="btn blue">Save</button>
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
        </script>