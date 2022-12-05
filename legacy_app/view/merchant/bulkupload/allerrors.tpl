</a>
    <div class="page-content noleftmargin">
        <!-- BEGIN PAGE HEADER-->
       
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row no-margin">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT REQUEST TABLE -->

                     <h3> Bulk upload errors</h3>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            Row no
                                        </th>
                                        <th>
                                            Error
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$errors item=v}
                                        {$int=0}
                                        {foreach from=$v item=k}
                                            {if $k.1 !=''}
                                                <tr>
                                                    {if $int==0}
                                                        <td rowspan="{count($v)-1}">
                                                            Row {$v.row}
                                                        </td>
                                                    {/if}
                                                    <td>
                                                        {$k.0} - {$k.1}
                                                    </td>

                                                </tr>
                                                {$int = $int +1}
                                            {/if}
                                        {/foreach}
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