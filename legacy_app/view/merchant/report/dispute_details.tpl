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
                            <label class="help-block">Dispute date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange" autocomplete="off"
                            data-date-format="{$session_date_format}" name="date_range" type="text"
                                value="{$from_date} - {$to_date}" placeholder="From date" />
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
                    <table class="table table-striped table-bordered table-hover" id="table-small">
                        <thead>
                            <tr>

                                <th>
                                    Date
                                </th>
                                <th>
                                    Dispute date
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
                                    Dispute Amount
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
                                <tr>
                                    <td>
                                        {$v.created_date}
                                    </td>
                                    <td>
                                        {$v.dispute_date}
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
                                        {if $v.refund_status==0}
                                            In review
                                        {elseif $v.refund_status==1}
                                            Merchant won
                                        {else}
                                            Customer won
                                        {/if}
                                    </td>
                                    <td>
                                        {$v.reason}
                                    </td>
                                </tr>
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