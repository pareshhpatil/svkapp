
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        <p class="media-heading">{$haserrors}.</p>
                    </div>
                </div>
            {/if}

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" action="/merchant/report/payment_settlement_details" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Settlement date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green">Excel export</button>
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
                                    Settlement ID
                                </th>
                                <th>
                                    Settlement date
                                </th>
                                {if $has_franchise==1}
                                    <th>
                                        Franchise name
                                    </th>
                                {/if}
                                <th>
                                    {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th>
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th>
                                    Transaction id
                                </th>
                                <th>
                                    Transaction date
                                </th>
                                <th>
                                    Payment ID
                                </th>
                                <th>
                                    Bank reference
                                </th>
                                <th>
                                    Captured
                                </th>
                                <th>
                                    TDR
                                </th>
                                <th>
                                    GST
                                </th>
                                <th>
                                    Settled
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {$settlement_id=''}
                            {$settlement_date=''}
                            {$total=0}
                            {$totaltdr=0}
                            {$totalst=0}
                            {foreach from=$reportlist item=v}
                                {if $settlement_id!=$v.settlement_id && $settlement_id!=''}
                                    {$last_total=$total}
                                    {$last_tdr=$totaltdr}
                                    {$last_st=$totalst}
                                    {$total=0}
                                    {$totaltdr=0}
                                    {$totalst=0}
                                {/if}
                                {$settled=$v.captured + $v.tdr + $v.service_tax}
                                {$tdr=$v.captured - $settled + $v.service_tax}
                                {$st=$v.captured - $settled + $v.tdr}
                                <tr>
                                    <td>
                                        {$v.settlement_id}
                                    </td>
                                    <td>
                                        {$v.settlement_at}
                                    </td>
                                    {if $has_franchise==1}
                                        <td>
                                            {$v.franchise_name}
                                        </td>
                                    {/if}
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
                                        {$v.transaction_id}
                                    </td>
                                    <td>
                                        {$v.transaction_date}
                                    </td>
                                    <td>
                                        {$v.payment_id}
                                    </td>
                                    <td>
                                        {$v.bank_reff}
                                    </td>
                                    <td>
                                        {$v.captured}
                                    </td>
                                    <td>
                                        {$v.tdr}
                                        {$totaltdr=$totaltdr+$tdr}
                                    </td>
                                    <td>
                                        {$v.service_tax}
                                        {$totalst=$totalst+$st}
                                    </td>
                                    <td>
                                        {$settled}
                                        {$total=$total+$settled}
                                    </td>

                                </tr>
                                {if $settlement_id!=$v.settlement_id && $settlement_id!=''}
                                    <tr class="bg-blue-soft">
                                        <td>{$settlement_id}
                                        </td>
                                        <td colspan="{$has_franchise+9}" style="text-align: right;">
                                            Settlement total for {$settlement_date}
                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        {if $has_franchise==1}
                                            <td style="display: none;">
                                            </td>
                                        {/if}
                                        <td style="display: none;">
                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        <td style="display: none;">

                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        <td style="display: none;">
                                        </td>
                                        <td>
                                            <b>-{$last_tdr|number_format:2}</b>
                                        </td>
                                        <td>
                                            <b> -{$last_st|number_format:2} </b>
                                        </td>
                                        <td>
                                            <b>{$last_total|number_format:2}</b>
                                        </td>
                                    </tr>
                                {/if}
                                {$settlement_id=$v.settlement_id}
                                {$settlement_date=$v.settlement_date|date_format:"%d %b %Y %I:%M %p"}
                            {/foreach}
                            {if !empty($reportlist)}
                                <tr class="bg-blue-soft">
                                    <td>{$settlement_id}
                                    </td>
                                    <td colspan="{$has_franchise+9}" style="text-align: right;">
                                        Settlement total for {$settlement_date}
                                    </td>
                                    {if $has_franchise==1}
                                        <td style="display: none;">
                                        </td>
                                    {/if}
                                    <td style="display: none;">

                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td style="display: none;">
                                    </td>
                                    <td>
                                        <b>-{$totaltdr|number_format:2}</b>
                                    </td>
                                    <td>
                                        <b> -{$totalst|number_format:2} </b>
                                    </td>
                                    <td>
                                        <b>{$total|number_format:2}</b>
                                    </td>
                                </tr>
                            {/if}
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

