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
                            <label class="help-block">Paid on</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange" autocomplete="off"
                            data-date-format="{$session_date_format}" name="date_range" type="text"
                                value="{$from_date} - {$to_date}" placeholder="From date" />
                        </div>


                        <div class="form-group">
                            <label class="help-block">Mode</label>
                            <select multiple name="mode[]" class="multi_column">
                                {foreach from=$modes item=v}
                                    {if in_array($v, $mode)}
                                        <option selected value="{$v}">{$v}</option>
                                    {else}
                                        <option value="{$v}">{$v}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Source</label>
                            <select multiple name="source[]" class="multi_column">
                                {foreach from=$sources item=v}
                                    {if in_array($v, $source)}
                                        <option selected value="{$v}">{$v}</option>
                                    {else}
                                        <option value="{$v}">{$v}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn blue">Search</button>
                            <button type="submit" name="export" class="btn green">Excel export</button>

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
                                    Paid on
                                </th>
                                <th>
                                    Payment #
                                </th>
                                <th>
                                    {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                <th>
                                    {$company_column_name}
                                </th>
                                <th class="td-c">
                                    Mode
                                </th>
                                <th>
                                    Source
                                </th>

                                <th>
                                    Amount
                                </th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="7" style=""></th>
                            </tr>
                        </tfoot>

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