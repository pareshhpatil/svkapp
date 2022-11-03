
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        {if $has_partner==false}<a href="/pricing" class="btn blue pull-right mb-1"> Upgrade you package</a>{/if}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    {if $success!=''}
                        <div class="alert alert-block alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            <p>{$success}</p>
                        </div>
                    {/if}
                    <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                    <table class="table  " >
                        <thead>
                            <tr>
                                <th class="td-c" style="width: 30%;">
                                    Feature
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-c" style="height: 20px;vertical-align: middle;">
                                    Package info
                                </td>
                                <td>
                                    <div class="center"><b>{$package.package_name}</b> <br>{$package.package_description}</div>

                                </td>
                            </tr>
                            <tr>
                                <td class="td-c" style="height: 20px;vertical-align: middle;">
                                    Package duration
                                </td>
                                <td>
                                    <div class="center">
                                        {if $account.total_invoices==0}
                                            Forever Free plan
                                        {else}
                                            {{$account.start_date}|date_format:"%d/%b/%y"} To {{$account.end_date}|date_format:"%d/%b/%y"}
                                        {/if}
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td class="td-c vcenter">
                                    Invoices
                                </td>
                                <td class="td-c vcenter">
                                    {if $account.total_invoices==0}
                                        <img src="/images/infinite.png" style="width: 30px;">
                                    {else}
                                        <div class="center">Used {$account_sum.total_invoices} out of {$account.total_invoices}</div>
                                        <div class="progress" >
                                            {$per=$account_sum.total_invoices*100/$account.total_invoices}
                                            <div class="progress-bar progress-bar-striped" role="progressbar"
                                                 aria-valuenow="{$account_sum.total_invoices}" aria-valuemin="0" aria-valuemax="{$account.total_invoices}" style="width:{$per|number_format:0}%">
                                                {if $per>9}
                                                    ({$per|number_format:0} %)
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}
                                </td>
                            </tr>
                            <tr>
                                <td class="td-c vcenter">
                                    SMS Service
                                </td>
                                <td class="td-c vcenter">
                                    {if $account_sum.sms_bought==0}
                                        0 SMS allocated  {if $has_partner==false}<a href="/merchant/package/confirm/{$sms_plan_link}" target="_BLANK" class="btn btn-xs green">Buy Now</a>{/if}
                                    {else}
                                        <div class="center">Sent {$account_sum.sms_consume} out of {$account_sum.sms_bought}</div>
                                        <div class="progress" >
                                            {$per=$account_sum.sms_consume*100/$account_sum.sms_bought}
                                            <div class="progress-bar progress-bar-striped" role="progressbar"
                                                 aria-valuenow="{$account_sum.sms_consume}" aria-valuemin="0" aria-valuemax="{$account_sum.sms_bought}" style="width:{$per|number_format:0}%">
                                                {if $per>9}
                                                    ({$per|number_format:0} %)
                                                {/if}
                                            </div>
                                        </div>
                                        {if $has_partner==false}
                                            <a href="/merchant/package/confirm/{$sms_plan_link}" target="_BLANK" class="btn btn-xs green">Buy Now</a>
                                        {/if}
                                    {/if}
                                </td>
                            </tr>

                        </tbody>
                    </table>


                    <!-- END PAYMENT TRANSACTION TABLE -->
                </div>
            </div>
        </div>
    </div>



    <h3 class="page-title">Packages bought</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Transaction ID
                                </th>
                                <th class="td-c">
                                    Package name
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Payment date
                                </th>
                                <th class="td-c">
                                    ?
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {$int=0}
                            {foreach from=$requestlist item=v}
                                {$int=$int+1}
                                <tr>
                                    <td class="td-c">
                                        {$v.package_transaction_id}
                                    </td>
                                    <td>
                                        {$v.narrative}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%d/%b/%y"}
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/profile/packagereceipt/{$v.link}"  class="btn btn-xs green iframe"><i class="fa fa-file"></i> Receipt </a>
                                        {if isset($v.payment_request_id)}
                                            <a href="/merchant/transaction/invoice/{$v.invoice_link}"  class="iframe btn btn-xs blue"><i class=" fa    fa-table"></i> Invoice </a>
                                            <a href="/patron/paymentrequest/download/{$v.invoice_link}"  class="btn btn-xs red"><i class="fa    fa-download"></i> PDF </a>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Invoice in the future?
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