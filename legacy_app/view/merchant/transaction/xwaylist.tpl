<style>
    .pagination {
        margin-top:10px !important;
    }
</style>
<div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

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

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <select class="form-control input-sm select2me" name="status" data-placeholder="Invoice status">
                                <option value=""></option>
                                {foreach from=$status_list key=k item=v}
                                    {if $v.config_key!=2 && $v.config_key!=3}
                                        {if {{$status_selected}=={$v.config_key}}}
                                            <option selected value="{$v.config_key}" selected>{$v.config_value}</option>
                                        {else}
                                            <option value="{$v.config_key}" >{$v.config_value}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <input type="submit" class="btn btn-sm blue" value="Search">
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
                        <table class="table table-striped  table-hover" sort-offcol="1" id="table-small">
                            <thead>
                                <tr>
                                    <th>
                                        Transaction #
                                    </th>
                                    <th>

                                    </th>
                                    <th>
                                        Transaction #
                                    </th>

                                    {if $has_franchise==1}
                                        <th>
                                            Franchise name
                                        </th>
                                    {/if}
                                    <th>
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>
                                    <th>
                                        Email ID
                                    </th>

                                    <th>
                                        Mobile No
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Paid on
                                    </th>


                                    <th>
                                        Mode
                                    </th>
                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Udf 1
                                    </th>
                                    <th>
                                        Udf 2
                                    </th>
                                    <th>
                                        Udf 3
                                    </th>
                                    <th>
                                        Udf 4
                                    </th>
                                    <th>
                                        Udf 5
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                {foreach from=$transactionlist key=k item=v}
                                    <tr>
                                        <td>
                                            {$v.xway_transaction_id}
                                        </td>
                                        <td>

                                            {if $v.status=="Paid online" || $v.status=="Paid offline"} 
                                                <div class="visible-xs btn-group-vertical" >
                                                    <a href="/merchant/transaction/receipt/{$v.transaction_id}/website" title="Receipt" class="btn btn-xs green iframe"><i class="fa fa-file-o"></i>  </a>
                                                    <a onclick="document.getElementById('sendanchor').href = '/merchant/transaction/sendreceipt/xway/{$v.transaction_id}'" href="#basic" data-toggle="modal" title="Send Receipt" class="btn btn-xs blue"><i class="fa fa-send"></i></a> 
                                                </div>
                                            {/if}
                                            {if $v.status!='Refund'}
                                                <div class="hidden-xs btn-group dropup">
                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">

                                                        {if $v.status=="Paid online" || $v.status=="Paid offline"} 
                                                            <li>
                                                                <a href="/merchant/transaction/receipt/{$v.transaction_id}/website" class="iframe"><i class="fa fa-file"></i> Receipt</a>
                                                            </li>
                                                            <li>
                                                                <a href="#sendmodal" onclick="document.getElementById('sendanchor').href = '/merchant/transaction/sendreceipt/xway/{$v.transaction_id}'" data-toggle="modal" ><i class="fa fa-send"></i> Send Receipt</a> 
                                                            </li>

                                                            {if $v.status=="Paid online" && $pg_vendor_id==0} 
                                                                <li>
                                                                    <a href="#refund" title="Refund" onclick="document.getElementById('refund_transaction_id').value = '{$v.transaction_id}';
                                                                            document.getElementById('refund_amount').value = '{$v.absolute_cost}'" data-toggle="modal"><i class="fa fa-undo"></i> Refund</a>
                                                                </li>
                                                            {/if}

                                                        {/if}
                                                        {if $v.status=="Failed"}
                                                            <li>
                                                                <a href="/merchant/transaction/reconcile/{$v.transaction_id}" title="Reconcile" ><i class="fa fa-refresh"></i> Reconcile</a>
                                                            </li>
                                                        {/if}


                                                    </ul>
                                                </div>
                                            {/if}
                                        </td>

                                        <td>
                                            {$v.xway_transaction_id}
                                        </td>

                                        {if $has_franchise==1}
                                            <th>
                                                {$v.franchise_name}
                                            </th>
                                        {/if}
                                        <td>
                                            {if $v.status=="Paid online" || $v.status=="Paid offline"} 
                                                <a href="/merchant/transaction/receipt/{$v.transaction_id}/website" class="iframe">{$v.customer_name}</a>
                                            {else}
                                                {$v.customer_name}
                                            {/if}

                                        </td>
                                        <td>
                                            {$v.email}
                                        </td>

                                        <td>
                                            {$v.mobile}
                                        </td>
                                        <td>
                                            {$v.display_amount}
                                        </td>
                                        <td>
                                            {$v.created_at}
                                        </td>


                                        <td>
                                            {$v.payment_mode}
                                        </td>
                                        <td class="td-c">
                                        
                                            {if {$v.status}=="Paid online"} 
                                                <span title="Paid online" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                <span title="Success" class="glyphicon glyphicon-ok-sign glyphicon-green"></span>
                                                {else if {$v.status}=="Paid offline"}
                                                    <span title="Paid offline" class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                    <a  id="{$v.pay_transaction_id}" onclick="updateRespond(this.id);"><span class="glyphicon glyphicon-edit glyphicon-blue"></span></a>
                                                        {else if {$v.status}=="Failed"}
                                                        <span title="Failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                        <span data-container="body" data-trigger="hover" data-content="{$v.narrative}" class="popovers glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                        {else if {$v.status}=="Initiated"}
                                                            <span title="Failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                            <span title="Failed" class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                            {else if {$v.status}=="Rejected"}
                                                                <span class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                                <span class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                            {else if {$v.status}=='Refund'}
                                                                <span title="Refunded" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                                <span data-container="body" data-trigger="hover" class="glyphicon glyphicon glyphicon-repeat glyphicon-red" style="color: #DFBA49;"></span>
                                                            {/if}   
                                                        </td>


                                                        <td>
                                                            {$v.udf1}
                                                        </td>
                                                        <td>
                                                            {$v.udf2}
                                                        </td>
                                                        <td>
                                                            {$v.udf3}
                                                        </td>
                                                        <td>
                                                            {$v.udf4}
                                                        </td>
                                                        <td>
                                                            {$v.udf5}
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                            </tbody>
                                        </table>
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
            <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Send Transaction Receipt</h4>
                        </div>
                        <div class="modal-body">
                            Are you sure would you like to Re-send transaction receipt?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn default" id="close" data-dismiss="modal">Close</button>
                            <a href="" onclick="document.getElementById('close').click();
                                    document.getElementById('loader').style.display = 'block';"  id="sendanchor" class="btn blue">Confirm</a>
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
                                    <form class="form-horizontal" action="/merchant/transaction/refundtransaction" method="POST"  id="profile_update" >
                                        <div class="form-body">

                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-3 control-label">Reason <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" name="reason" required="" type="text" value="" placeholder=""/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputPassword12" class="col-md-3 control-label">Amount <span class="required">*
                                                    </span></label>
                                                <div class="col-md-5">
                                                    <input class="form-control form-control-inline input-sm" id="refund_amount" name="amount" required {$validate.amount} type="text"  placeholder="Amount"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <input type="hidden" id="refund_transaction_id" name="transaction_id" value="" />
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