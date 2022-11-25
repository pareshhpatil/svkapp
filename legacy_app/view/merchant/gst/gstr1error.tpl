</a>
<div class="page-content noleftmargin">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT REQUEST TABLE -->

            <h3> GST upload R1</h3>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Error</th>
                                <th>Field errors</th>

                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$errors key=k item=v}
                                {if !empty($v.response.fieldErrors)}
                                    {foreach from=$v.response.fieldErrors item=fe}
                                        <tr>
                                            <td>
                                                {$v.response.status}
                                            </td>
                                            <td>
                                                {$v.response.message}
                                            </td>
                                            <td>
                                                {$fe.defaultMessage}
                                            </td>
                                        </tr>
                                    {/foreach}
                                {else}
                                    <tr>
                                        <td>
                                            {$v.response.status}
                                        </td>
                                        <td>
                                            {$v.response.message}
                                        </td>
                                        <td>
                                        </td>
                                    </tr>

                                {/if}

                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- END PAYMENT REQUEST TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>