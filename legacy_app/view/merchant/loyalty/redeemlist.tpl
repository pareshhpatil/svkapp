
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}

            <div class="portlet">
                
                <div class="portlet-body">

                    <form class="form-inline" action="" method="post" role="form">
                        <span  id="plan_invoice_create">
                            <div class="form-group">
                                <input class="form-control form-control-inline input-sm date-picker" type="text" value="{$from_date}" name="from_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="From date"/>
                            </div>

                            <div class="form-group">
                                <input class="form-control form-control-inline input-sm date-picker" type="text" value="{$to_date}" name="to_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="To date"/>
                            </div>

                        </span>

                        <input type="submit" class="btn btn-sm blue" value="Search" />
                    </form>

                </div>
            </div>
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                <th class="td-c">
                                    Created Date
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Points
                                </th>

                                <th class="td-c">
                                    Narrative
                                </th>
                                <th class="td-c">
                                    Status
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.id}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.customer_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.customer_code}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>

                                    <td class="td-c">
                                        {$v.points}
                                    </td>
                                    <td class="td-c">
                                        {$v.narrative}
                                    </td>
                                    <td class="td-c">
                                        Redeemed
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



