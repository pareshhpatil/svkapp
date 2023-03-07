
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Refund date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}" name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div> 

        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-small" >
                        <thead>
                            <tr>

                                <th>
                                   Date
                                </th>
                                <th>
                                    Refund date
                                </th>
                                <th>
                                    Transaction date
                                </th>
                                <th>
                                    Transaction ID
                                </th>
                                <th>
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th>
                                {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th>
                                    Transaction Amount
                                </th>
                                <th>
                                    Refund Amount
                                </th>
                                <th>
                                    Status
                                </th>
                                
                                <th>
                                    Reason
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                {if $v.refund_status > -1}
                                    <tr>
                                        <td>
                                            {$v.transaction_date} 
                                        </td>
                                        <td>
                                            {$v.refund_at} 
                                        </td>
                                        <td>
                                            {$v.transaction_at}
                                        </td>
                                        <td>
                                            {$v.transaction_id} 
                                        </td>
                                        <td>
                                            {$v.customer_code} 
                                        </td>
                                        <td>
                                            {$v.customer_name} 
                                        </td>
                                        <td>
                                            {$v.company_name} 
                                        </td>
                                        <td>
                                            {$v.transaction_amount|number_format:2:".":","} 
                                        </td>
                                        <td>
                                            {$v.refund_amount|number_format:2:".":","} 
                                        </td>
                                        <td>
                                            {if $v.refund_status==1}
                                                Proccessed
                                            {else}
                                                Pending
                                            {/if}
                                        </td>
                                        <td>
                                            {$v.reason} 
                                        </td>
                                    </tr>
                                {/if}
                            {/foreach}
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

