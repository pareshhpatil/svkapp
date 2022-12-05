
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->

    <div class="row no-margin">
        <div class="col-md-12">
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
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Credit note No
                                </th>
                                <th class="td-c">
                                    Invoice Number
                                </th>
                                <th class="td-c">
                                    Invoice date
                                </th>
                                {if $type=='creditnote'}
                                    <th class="td-c">
                                        Invoice Number
                                    </th>
                                    <th class="td-c">
                                        Invoice date
                                    </th>
                                {/if}
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Supply Type
                                </th>
                                <th class="td-c">
                                    Supply State
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Customer GST
                                </th>
                                <th class="td-c">
                                    
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$data item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.inum}
                                    </td>
                                    <td class="td-c">
                                        {$v.pdt|date_format:"%d-%m-%Y"}
                                    </td>
                                    <td class="td-c">
                                        {$v.invTyp}
                                    </td>
                                    <td class="td-c">
                                        {$v.splyTy}
                                    </td>
                                    <td class="td-c">
                                        {$v.state}
                                    </td>
                                    <td class="td-c">
                                        {$v.val}
                                    </td>
                                    <td class="td-c">
                                        {$v.ctin}
                                    </td>
                                    <td class="td-c">
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deletestaginginvoice/{$v.id}/{$link}'" data-toggle="modal" class="btn btn-xs red"> Delete </a>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to consider this invoice for submission now?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
