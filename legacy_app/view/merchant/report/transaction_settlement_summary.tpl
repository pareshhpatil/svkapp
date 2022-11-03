
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
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Settlement date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>
                        {if !empty($franchise_list)}
                            <div class="form-group">
                                <label class="help-block">Franchise name</label>
                                <select class="form-control  select2me" data-placeholder="Franchise name" name="franchise_id">
                                    <option value="0">Select franchise</option>
                                    {foreach from=$franchise_list item=v}
                                        {if {{$franchise_id}=={$v.franchise_id}}}
                                            <option selected value="{$v.franchise_id}" selected>{$v.franchise_name}</option>
                                        {else}
                                            <option value="{$v.franchise_id}">{$v.franchise_name}</option>
                                        {/if}
                                    {/foreach}
                                </select>
                            </div>
                        {/if}

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
                    <table class="table table-striped table-bordered table-hover" id="table-sum-ellipsis-small">
                        <thead>
                            <tr>
                               
                                <th>
                                    Id
                                </th>
                                <th>
                                    Settlement date
                                </th>
                                {if $has_franchise==1}
                                    <th>
                                        Franchise
                                    </th>
                                {/if}
                                <th>
                                    Bank reference number
                                </th>
                                <th>
                                    Total Captured
                                </th>
                                <th>
                                    Total TDR
                                </th>
                                <th>
                                    Total GST
                                </th>
                                <th>
                                    Calculated settlement value
                                </th>
                                <th>
                                    Total settlement value
                                </th>
                                {if $show_narrative==1}
                                    <th>
                                        Narrative
                                    </th>
                                {/if}
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="{$has_franchise+8}" style=""></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    
                                    <td>
                                        <a href="/merchant/report/payment_settlement_details/{$v.link}">{$v.id}</a>
                                    </td>
                                    <td>
                                        {$v.settlement_at}
                                    </td>
                                    {if $has_franchise==1}
                                        <th>
                                            {$v.franchise_name}
                                        </th>
                                    {/if}
                                    <td>
                                        {$v.bank_reference}
                                    </td>
                                    <td>
                                        {$v.total_capture}
                                    </td>
                                    <td>
                                        {$v.total_tdr}
                                    </td>
                                    <td>
                                        {$v.total_service_tax}
                                    </td>
                                    <td>
                                        {$v.settlement_amount}
                                    </td>
                                    <td>
                                        {$v.requested_settlement_amount}
                                    </td>
                                    {if $show_narrative==1}
                                        <td>
                                            {$v.narrative}
                                        </td>
                                    {/if}
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

