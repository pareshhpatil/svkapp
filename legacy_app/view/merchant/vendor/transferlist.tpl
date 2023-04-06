
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

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

            <div class="portlet">

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <input class="form-control form-control-inline input-sm rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <select class="form-control input-sm select2me" name="bulk_id" data-placeholder="Bulk upload file name">
                                <option value=""></option>

                                {foreach from=$bulk_list key=k item=v}
                                    {if {{$bulk_id}=={$v.bulk_upload_id}}}
                                        <option selected value="{$v.bulk_upload_id}" selected>{$v.merchant_filename}</option>
                                    {else}
                                        <option value="{$v.bulk_upload_id}">{$v.merchant_filename}</option>
                                    {/if}

                                {/foreach}

                            </select>
                        </div>
                        <input type="submit" class="btn btn-sm blue" value="Search">
                    </form>

                </div>
            </div>



            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>

                                <th class="td-c">
                                    Transfer ID
                                </th>
                                <th class="td-c">
                                    Date
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
                                    UTR Number
                                </th>
                                <th class="td-c">
                                    Mode
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
                                        {$v.transfer_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {if $v.beneficiary_type==1}
                                            Vendor
                                        {elseif $v.beneficiary_type==3}
                                            Beneficiary
                                        {else}
                                            Franchise
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {if $v.beneficiary_type==1}
                                            {$v.vendor_name}
                                        {elseif $v.beneficiary_type==3}
                                            {$v.name}
                                        {else}
                                            {$v.franchise_name}
                                        {/if}

                                    </td>
                                    <td class="td-c">
                                        {$v.amount}
                                    </td>
                                    <td class="td-c">
                                        {$v.utr_number}
                                    </td>
                                    <td class="td-c">
                                        {if $v.type==1}
                                            Payment gateway
                                        {else}
                                            {if $v.offline_response_type==1}
                                                Wire transfer
                                            {else if $v.offline_response_type==2}
                                                Cheque
                                            {else if $v.offline_response_type==3}
                                                Cash
                                            {else if $v.offline_response_type==5}
                                                Online Payment
                                            {/if}
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {if $v.status==1}
                                            <label class="label label-sm label-success">Processed</label>
                                        {else}
                                            <label class="label label-sm label-default">Pending</label>
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
                <h4 class="modal-title">Delete Vendor</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this vendor in the future?
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
