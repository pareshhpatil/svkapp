</a>
    <div class="page-content noleftmargin">
        <!-- BEGIN PAGE HEADER-->
       
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row no-margin">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT REQUEST TABLE -->

                     <h3> GST upload {$type}</h3>
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table  table-hover">
                                <thead>
                                    <tr>
                                        
                                        <th>
                                            Error
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$errors item=v}
                                        {$int=0}
                                        {foreach from=$v item=k}
                                                <tr>
                                                    <td>
                                                       {$k.id}: {$k.description}
                                                    </td>
                                                </tr>
                                                {$int = $int +1}
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