
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

            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div> 
            {/if}
            {if isset($error)}
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    {$error}
                </div> 
            {/if}

                <!-- BEGIN PORTLET-->

                <div class="portlet">

                    <div class="portlet-body">

                        <form class="form-inline" action="/merchant/gst/viewlist{if $type!=''}/{$type}{/if}" method="post" role="form">
                            <div class="form-group">
                                <select required="" class="form-control" name="gstin">
                                    <option value="">Select GSTIN</option>
                                    {foreach from=$data key=k item=v}
                                        <option {if $post.gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <select required="" name="month" class="form-control" >
                                    <option>Select Month</option>
                                    {foreach from=$months key=k item=v}
                                        <option {if $k==$post.month} selected {$monthsel=$v}{/if} value="{$k}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <select required="" name="year" class="form-control" >
                                    <option>Select Year</option>
                                    {foreach from=$years item=v}
                                        <option {if $v==$post.year} selected {/if} value="{$v}">{$v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <input type="submit" class="btn blue" value="Search" />
                        </form>

                    </div>
                </div>

                <!-- END PORTLET-->
            </div>
        </div>

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
                                        Invoice No
                                    </th>
                                    <th class="td-c">
                                        Bill date
                                    </th>
                                    <th class="td-c">
                                        Month
                                    </th>
                                    <th class="td-c">
                                        Taxable amount
                                    </th>
                                    <th class="td-c">
                                        CGST Amount
                                    </th>
                                    <th class="td-c">
                                        SGST Amount
                                    </th>
                                    <th class="td-c">
                                        IGST Amount
                                    </th>

                                    <th class="td-c">
                                        Amount
                                    </th>
                                    <th class="td-c">
                                        GSTIN
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {foreach from=$list key=kk item=v}
                                    {foreach from=$v.inv key=kk item=vk}
                                        <tr>
                                            <td class="td-c">
                                                {$vk.inum}
                                            </td>
                                            <td class="td-c">
                                                {$vk.idt}
                                            </td>
                                            <td class="td-c">
                                                {$monthsel}-{$post.year}
                                            </td>
                                            <td class="td-c">
                                                {$vk.txamount}
                                            </td>
                                            <td class="td-c">
                                                {$vk.camount}
                                            </td>
                                            <td class="td-c">
                                                {$vk.camount}
                                            </td>
                                            <td class="td-c">
                                                {$vk.iamount}
                                            </td>
                                            <td class="td-c">
                                                {$vk.val}
                                            </td>
                                            <td class="td-c">
                                                {$v.ctin}
                                            </td>
                                        </tr>
                                    {/foreach}
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
                Are you sure you would not like to use this invoice in the future?
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
