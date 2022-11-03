
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}&nbsp;
    </h3>
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
                                    Date
                                </th>
                                <th class="td-c">
                                    Transfer ID
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Narrative
                                </th>
                                <th class="td-c">
                                    Mode
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.transfer_id}
                                    </td>
                                    <td class="td-c">
                                        {if $v.beneficiary_type==1}
                                            Vendor
                                        {else}
                                            Franchise
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {if $v.beneficiary_type==1}
                                            {$v.vendor_name}
                                        {else}
                                            {$v.franchise_name}
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        {$v.narrative}
                                    </td>
                                    <td class="td-c">
                                        {if $v.type==1}
                                            Payment gateway
                                        {else}
                                            {if $v.offline_response_type==1}
                                                NEFT/RTGS
                                            {else if $v.offline_response_type==2}
                                                Cheque
                                            {else if $v.offline_response_type==3}
                                                Cash
                                            {else if $v.offline_response_type==5}
                                                Online Payment
                                            {/if}
                                        {/if}
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
                <h4 class="modal-title">Delete Transfer</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this transfer in the future?
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
