
<div class="page-content">
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
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Set Top Box Number
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Cost
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.name}
                                    </td>
                                    <td class="td-c">
                                        {$v.customer_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.customer_code}
                                    </td>

                                    <td class="td-c">
                                        {if $v.package_type==1}
                                            Package
                                        {else}
                                            Channel
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {if $v.package_type==1}
                                            {$v.package_name}
                                        {else}
                                            {$v.channel_name}
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.cost}
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

