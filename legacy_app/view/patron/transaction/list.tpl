
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->

            <div class="portlet">
                
                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                                    <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                                </div>

                        <div class="form-group">
                            <select class="form-control select2me" name="merchant_name" data-placeholder="Merchant name">
                                <option value=""></option>

                                {foreach from=$merchant_list key=k item=v}
                                    {if {{$merchantselected}=={$v.id}}}
                                        <option selected value="{$v.id}" selected>{$v.name}</option>
                                    {else}
                                        <option value="{$v.id}">{$v.name}</option>
                                    {/if}

                                {/foreach}

                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control select2me" name="status" data-placeholder="Invoice status">
                                <option value=""></option>
                                {foreach from=$status_list key=k item=v}
                                    {$v.config_key}
                                    {if {{$status_selected}=={$v.config_key}}}
                                        <option selected value="{$v.config_key}" selected>{$v.config_value}</option>
                                    {else}
                                        <option value="{$v.config_key}" >{$v.config_value}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <input type="submit" class="btn blue" value="Search">
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
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                        <th>
                            Paid on
                        </th>
                        <th>
                            Due date
                        </th>

                        <th>
                            Merchant name
                        </th>

                        <th>
                            Amount
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            View
                        </th>
                        </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$transactionlist key=k item=v}
                                <tr>

                                    <td>
                                        {{$v.last_update_on}|date_format:"%Y-%m-%d %I:%M %p"}
                                    </td>
                                    <td>
                                        {{$v.due_date}|date_format:"%Y-%m-%d"}
                                    </td>
                                    <td>
                                        {$v.name}
                                    </td>
                                    <td>
                                        {$v.absolute_cost}
                                    </td>
                                    <td class="td-c">
                                        {if {$v.status}=="Paid online"} 
                                            <span title="Paid online" class="glyphicon glyphicon-record glyphicon-green"></span>
                                            <span title="Success" class="glyphicon glyphicon-ok-sign glyphicon-green"></span>
                                            {else if {$v.status}=="Paid offline"}
                                                <span title="Paid offline" class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                <span class="glyphicon glyphicon-ok-sign glyphicon-green"></span>
                                                {else if {$v.status}=="Failed"}
                                                    <span title="Payment failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                    <span title="Failed" class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                    {else if {$v.status}=="Initiated"}
                                                        <span title="Payment failed" class="glyphicon glyphicon-record glyphicon-green"></span>
                                                        <span title="Failed" class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                        {else if {$v.status}=="Rejected"}
                                                            <span class="glyphicon glyphicon-record glyphicon-blue"></span>
                                                            <span class="glyphicon glyphicon-remove-sign glyphicon-red"></span>
                                                        {/if}   
                                                    </td>
                                                    <td>
                                                        {if $v.payment_request_type==2}
                                                            <a href="/patron/event/invoice/{$v.paymentrequest_id}" class="btn btn-xs blue iframe"><i class="fa fa-table"></i> Event </a>

                                                        {elseif $v.payment_request_type==5}

                                                        {else}
                                                            <a href="/patron/paymentrequest/view/{$v.paymentrequest_id}" target="_BLANK" class="btn btn-xs blue iframe"><i class="fa fa-table"></i> Invoice </a>
                                                        {/if}
                                                        {if $v.status=="Paid online" || $v.status=="Paid offline"} 
                                                            <a href="/patron/transaction/receipt/{$v.transaction_id}" class="btn btn-xs green iframe"><i class="fa fa-file-o"></i> Receipt </a>

                                                            {if $v.payment_request_type==2}
                                                                <a href="/patron/transaction/downloadreceipt/{$v.transaction_id}" class="btn btn-xs red"><i class="fa fa-download"></i> QR Code </a>
                                                            {/if}

                                                        {/if}
                                                    </td>
                                                </tr>

                                            {/foreach}
                                        </form>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- END PAYMENT TRANSACTION TABLE -->
                        </div>
                    </div>
                    <!-- END PAGE CONTENT-->
                </div>
            </div>
            <!-- END CONTENT -->
            <!-- /.modal -->

            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->