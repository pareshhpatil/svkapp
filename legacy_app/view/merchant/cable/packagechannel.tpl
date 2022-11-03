
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->

    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <br>
            <h3 class="page-title">{$title}&nbsp;
            </h3>
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-pagignation">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Price
                                </th>
                               

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$channels item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.channel_name}
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

