
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
                <div class="alert alert-danger nolr-margin">
                    <div class="media">
                        <p class="media-heading"><strong></strong>{$haserrors}.</p>
                    </div>

                </div>
            {/if}

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Paid on</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>


                        <div class="form-group">
                            <label class="help-block">Column name (Max 5)</label>
                            <select multiple  id="column_name" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select)} 
                                            <option selected value="{$v.column_name}" >{$v.column_name}</option>
                                        {else}
                                            <option value="{$v.column_name}">{$v.column_name}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
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
                    <table class="table table-striped table-bordered table-hover" id="example">
                        <thead>
                            <tr>
                                <th>
                                    Transaction id
                                </th>
                                <th>
                                    Paid on
                                </th>
                                <th>
                                {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th>
                                    Payment method
                                </th>
                                <th>
                                    Bank reference
                                </th>
                                
                                <th>
                                    Captured
                                </th>
                                <th>
                                    Refunded
                                </th>
                                <th>
                                    Chargeback
                                </th>
                                <th>
                                    TDR
                                </th>
                                <th>
                                    Ser.Tax
                                </th>
                                <th>
                                    Surcharge
                                </th>
                                <th>
                                    Surcharge Ser.Tax
                                </th>
                                <th>
                                    Net Amount
                                </th>

                                {foreach from=$column_select item=v}
                                    <th>
                                        {$v}
                                    </th>
                                {/foreach}


                            </tr>
                        </thead>
                        
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

