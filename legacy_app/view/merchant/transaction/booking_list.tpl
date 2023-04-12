<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}<div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>
                        {$success}
                    </p>
                </div>
            {/if}
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">
                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <select class="form-control input-sm" required name="date_range_type"
                                data-placeholder="Type">
                                <option {if {$date_range_type == 'paid_on'}} selected {/if} value="paid_on">Paid On
                                </option>
                                <option {if {$date_range_type == 'booking_for'}} selected {/if} value="booking_for">
                                    Booking For</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control input-sm form-control-inline  rpt-date" id="daterange"
                                autocomplete="off" data-date-format="{$session_date_format}" name="date_range" type="text"
                                value="{$from_date} - {$to_date}" placeholder="From date" />
                        </div>
                        <div class="form-group">
                            <select class="form-control input-sm" name="calender_id" data-placeholder="Type">
                                <option value="">Select Calendar</option>
                                {foreach from=$calenders key=k item=v}
                                    {if {{$calender_selected}=={$v.calendar_id}}}
                                        <option selected value="{$v.calendar_id}">{$v.calendar_title}</option>
                                    {else}
                                        <option value="{$v.calendar_id}">{$v.calendar_title}</option>
                                    {/if}

                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control input-sm" name="status" data-placeholder="Payment status">
                                <option value="">Payment status</option>
                                {foreach from=$status_list key=k item=v}
                                    {$v.config_key}
                                    {if {{$status_selected}=={$v.config_key}}}
                                        <option selected value="{$v.config_key}">{$v.config_value}</option>
                                    {else}
                                        <option value="{$v.config_key}">{$v.config_value}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <input type="submit" class="btn btn-sm blue" value="Search">
                        <input type="submit" name="export" class="btn btn-sm green" value="Excel export">
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <form id="myForm" action="" method="post">
                        <table class="table table-striped table-bordered table-hover" id="table-small">
                            <thead>
                                <tr>
                                    <th>
                                        Transaction ID
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Type
                                    </th>
                                    <th>
                                        Booking detail
                                    </th>
                                    <th>
                                        Booking date
                                    </th>
                                    <th>
                                        Units
                                    </th>

                                    <th>
                                        Amount
                                    </th>
                                    {foreach from=$calender_slot item=v}
                                        <th>
                                            {$v.slot_title}
                                        </th>
                                    {/foreach}
                                    <th>
                                        Paid on
                                    </th>

                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Mode
                                    </th>
                                    <th class="td-c" style="width: 50px;">

                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                {foreach from=$transactionlist key=k item=v}
                                    {if $v.absolute_amount>0}
                                        <tr>
                                            <td class="td-c">
                                                {$v.pay_transaction_id}
                                            </td>

                                            <td class="td-c">
                                                {if $v.status_name=="Paid online" || $v.status_name=="Paid offline"}
                                                    <a href="/merchant/transaction/receipt/{$v.transaction_id}" title="View Receipt"
                                                        class="iframe"> {$v.name}</a>
                                                {else}
                                                    {$v.name}
                                                {/if}
                                            </td>
                                            <td class="td-c">
                                                {if $v.payment_request_type==5}
                                                    Slots
                                                {else}
                                                    Membership
                                                {/if}
                                            </td>
                                            <td class="td-c">
                                                {$v.calendar_title} - {$v.slot_time}
                                            </td>
                                            <td class="td-c">
                                                {{$v.calendar_date}|date_format:"%d/%b/%y"}
                                            </td>
                                            <td class="td-c">
                                                {$v.quantity}
                                            </td>
                                            <td class="td-c">
                                                {$v.absolute_amount}
                                            </td>
                                            {foreach from=$calender_slot item=vi}
                                                <th>
                                                    {if  {$v.{$vi.slot_title}} > 0}
                                                        {$v.{$vi.slot_title}}
                                                    {else}
                                                        0
                                                    {/if}
                                                </th>
                                            {/foreach}
                                            <td class="td-c">
                                                {$v.created_at}
                                            </td>
                                            <td class="td-c">
                                                {if {$v.status_name}=="Paid online"}
                                                    <span title="Paid online"
                                                        class="glyphicon glyphicon-record glyphicon-green"></span>
                                                    <span title="Success"
                                                        class="glyphicon glyphicon-ok-sign glyphicon-green"></span>
                                                {else if {$v.status_name}=="Paid offline"}
                                                    <span title="Paid offline"
                                                        class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                    <a id="{$v.pay_transaction_id}" title="Update transaction"
                                                        onclick="updateRespond(this.id);"><span
                                                            class="glyphicon glyphicon-edit glyphicon-blue"></span></a>
                                                {else if {$v.status_name}=="Offline failed"}
                                                    <span title="Paid offline"
                                                        class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                    <a id="{$v.pay_transaction_id}" title="Update transaction"
                                                        onclick="updateRespond(this.id);">
                                                        <span data-container="body" data-trigger="hover"
                                                            data-content="{$v.narrative}"
                                                            class="popovers glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                    </a>
                                                {else if {$v.status_name}=="Failed"}
                                                    <span title="Failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                    <span data-container="body" data-trigger="hover" data-content="{$v.narrative}"
                                                        class="popovers glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                {else if {$v.status_name}=="Initiated"}
                                                    <span title="Failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                    <span title="Failed"
                                                        class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                {else if {$v.status_name}=="Rejected"}
                                                    <span class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                    <span data-container="body" data-trigger="hover" data-content="{$v.narrative}"
                                                        class="popovers glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                {/if}
                                            </td>
                                            <td class="td-c">
                                                {$v.payment_mode}
                                            </td>
                                            <td>
                                                <!-- <div class="visible-xs btn-group-vertical" >

                                                <span class="input-group-btn">
                                                </span>
                                                <span class="input-group-btn">
                                                <a href="/patron/event/invoice/{$v.paymentrequest_id}" title="View Event" class="iframe btn btn-xs blue"><i class="fa fa-table"></i></a>
                                                </span>

                                                {if $v.status=="Paid online" || $v.status=="Paid offline"} 
                                                <span class="input-group-btn">
                                                <a href="/merchant/transaction/receipt/{$v.transaction_id}" title="View Receipt" class="iframe btn btn-xs green"><i class="fa fa-file-o"></i></a> 
                                                </span>
                                                {if $v.status=="Paid offline"} 
                                                <span class="input-group-btn">
                                                <a href="#basic" title="Delete transaction" onclick="document.getElementById('deleteanchor').href = '/merchant/transaction/delete/{$v.transaction_id}'" class="btn btn-xs red"data-toggle="modal" ><i class="fa fa-times"></i></a>
                                                </span>
                                                {/if}


                                                {if $v.is_availed==0}
                                                <span class="input-group-btn">
                                                <a href="/merchant/transaction/availed/{$v.transaction_id}" title="Avail" class="btn btn-xs green"><i class="fa  fa-unlock"></i></a>
                                                </span>
                                                {else}
                                                <span class="input-group-btn">
                                                <a href="/merchant/transaction/used/{$v.transaction_id}" title="Undo" class="btn btn-xs yellow"><i class="fa  fa-lock"></i></a> </span>
                                                {/if}
                                                {/if}
                                                <span class="input-group-btn">
                                                <a href="/merchant/comments/view/{$v.transaction_id}" title="Comment" class="iframe btn btn-xs blue" ><i class="fa fa-comment"></i></a>
                                                </span>

                                                </div>
                                                -->

                                                <div class="btn-group dropup">
                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                        data-toggle="dropdown">
                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        {if $v.status_name=="Paid online" || $v.status_name=="Paid offline"}
                                                            <li>
                                                                <a href="/merchant/transaction/receipt/{$v.transaction_id}"
                                                                    class="iframe"><i class="fa fa-file-o"></i> View Receipt</a>
                                                            </li>
                                                            {if $v.status_name=="Paid offline"}
                                                                <li><a href="#basic"
                                                                        onclick="document.getElementById('deleteanchor').href = '/merchant/transaction/delete/{$v.transaction_id}'"
                                                                        data-toggle="modal"><i class="fa fa-times"></i> Delete
                                                                        Transaction</a>
                                                                </li>
                                                            {/if}

                                                            {if $v.status_name=="Paid online" && $pg_vendor_id==0}
                                                                <li>
                                                                    <a href="#refund" title="Refund"
                                                                        onclick="document.getElementById('refund_transaction_id').value = '{$v.transaction_id}';
                                                                                                document.getElementById('refund_amount').value = '{$v.absolute_amount}'"
                                                                        data-toggle="modal"><i class="fa fa-undo"></i> Refund</a>
                                                                </li>
                                                            {/if}


                                                            {if $v.is_availed==0}
                                                                <li><a href="/merchant/transaction/availed/{$v.transaction_id}/booking"><i
                                                                            class="fa  fa-unlock"></i> Avail</a> </li>
                                                            {else}
                                                                <li><a href="/merchant/transaction/used/{$v.transaction_id}/booking"><i
                                                                            class="fa  fa-lock"></i> Undo</a> </li>
                                                            {/if}

                                                        {/if}
                                                        {if $v.status_name=="Failed"}
                                                            <li>
                                                                <a href="/merchant/transaction/reconcile/{$v.transaction_id}"
                                                                    title="Reconcile"><i class="fa fa-refresh"></i> Reconcile</a>
                                                            </li>
                                                        {/if}
                                                        <li><a href="/merchant/comments/view/{$v.transaction_id}"
                                                                class="iframe"><i class="fa fa-comment"></i> Comments</a></li>
                                                    </ul>
                                                </div>


                                            </td>
                                        </tr>
                                    {/if}
                                {/foreach}

                            </tbody>
                        </table>
                        <input type="hidden" name="paymentresponse_id" id="paymentresponse_id" />
                    </form>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
{if $is_update==1}
    <!-- /.modal -->
    <div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                    <div class="portlet ">
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <form class="form-horizontal" action="/merchant/transaction/updaterespond/{$type}"
                                        method="POST" id="profile_update">
                                        <div class="form-body">
                                            <div class="alert alert-danger display-hide">
                                                <button class="close" data-close="alert"></button>
                                                You have some form errors. Please check below.
                                            </div>


                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Transaction type
                                                    <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <select class="form-control input-sm select2me" name="response_type"
                                                        onchange="responseType(this.value);" data-placeholder="Select type">
                                                        <option value="1" {if $res_type==1} selected {/if}>Wire transfer
                                                        </option>
                                                        <option value="2" {if $res_type==2} selected {/if}>Cheque</option>
                                                        <option value="3" {if $res_type==3} selected {/if}>Cash</option>
                                                        <option value="5" {if $res_type==5} selected {/if}>Online Payment
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group" {if $res_type==2 || $res_type==3} style="display: none;"
                                                {/if} id="bank_transaction_no">
                                                <label for="inputPassword12" class="col-md-5 control-label">Bank ref
                                                    no</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm"
                                                        id="bank_transaction_no_" name="bank_transaction_no" type="text"
                                                        value="{$res_details.bank_transaction_no}"
                                                        placeholder="Bank ref number" />
                                                </div>
                                            </div>
                                            <div id="cheque_no" {if $res_type!=2} style="display: none;" {/if}>
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                        no</label>
                                                    <div class="col-md-5">
                                                        <input class="form-control form-control-inline input-sm"
                                                            id="cheque_no_" name="cheque_no" {$validate.number} type="text"
                                                            value="{$res_details.cheque_no}" placeholder="Cheque no" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                                        status</label>
                                                    <div class="col-md-5">
                                                        <select class="form-control input-sm" name="cheque_status"
                                                            data-placeholder="Select status">
                                                            <option {if $res_details.cheque_status=='Deposited'} selected
                                                                {/if} value="Deposited">Deposited</option>
                                                            <option {if $res_details.cheque_status=='Realised'} selected
                                                                {/if} value="Realised">Realised</option>
                                                            <option {if $res_details.cheque_status=='Bounced'} selected
                                                                {/if} value="Bounced">Bounced</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" {if $res_type!=3} style="display: none;" {/if}
                                                id="cash_paid_to">
                                                <label for="inputPassword12" class="col-md-5 control-label">Cash paid
                                                    to</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm"
                                                        id="cash_paid_to_" name="cash_paid_to" {$validate.name} type="text"
                                                        value="{$res_details.cash_paid_to}" placeholder="Cash paid to" />
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
                                                        value="{{$res_details.settlement_date}|date_format:" %d %b %Y"}"
                                                        placeholder="Date" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Bank
                                                    name</label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm"
                                                        name="bank_name" type="text" value="{$res_details.bank_name}"
                                                        placeholder="Bank name" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-5 control-label">Amount <span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" name="amount"
                                                        required {$validate.amount} type="text"
                                                        value="{$res_details.amount}" placeholder="Amount" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-8"></div>
                                                <div class="col-md-3">
                                                    <input type="hidden" name="offline_response_id"
                                                        value="{$res_details.offline_response_id}" />
                                                    <input type="hidden" name="payment_request_id"
                                                        value="{$res_details.payment_request_id}" />
                                                    <input type="submit" class="btn blue" value="Update">
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
    <a data-toggle="modal" id="respondupdate" href="#respond"></a>
    <script>
        document.getElementById('respondupdate').click();
        responseType({$res_type});
    </script>
    <!-- /.modal -->
{/if}

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete transaction</h4>
            </div>
            <div class="modal-body">
                Are you sure would you like to delete transaction?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade bs-modal-lg" id="refund" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Refund transaction</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" action="/merchant/transaction/refundtransaction" method="POST"
                            id="profile_update">
                            <div class="form-body">

                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-3 control-label">Reason <span
                                            class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" name="reason"
                                            required="" type="text" value="" placeholder="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-3 control-label">Amount <span
                                            class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" id="refund_amount"
                                            name="amount" required {$validate.amount} type="text"
                                            placeholder="Amount" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-5">
                                        <input type="hidden" id="refund_transaction_id" name="transaction_id"
                                            value="" />
                                        <input type="hidden" name="type"
                                            value="booking_calendar" />
                                        <input type="submit" class="btn blue pull-right" value="Submit">
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>


                </div>




            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>