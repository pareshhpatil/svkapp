
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
        

            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div> 
            {/if}
            {if isset($error)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>{$error}
                </div> 
            {/if}
            <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" method="post" action="/merchant/gst/gst3bupload" id="3bfrm"  enctype="multipart/form-data"  role="form">
                        {CSRF::create('gst_3b_upload')}
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
                                    <option value="{$k}">{$v}</option>
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
                        <div class="form-group">
                            <input type="file" required="" accept=".csv"  name="file" />
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn blue" value="Upload" />
                        </div>

                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <h3 class="form-section">Uploaded sheets</h3>
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Date
                                </th>
                                
                                <th class="td-c">
                                    File name
                                </th>
                                <th class="td-c">
                                    GST
                                </th>
                                <th class="td-c">
                                    File period
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c">
                                    
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$bulk_list key=kk item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.merchant_filename}
                                    </td>
                                    <td class="td-c">
                                        {$v.gstin}
                                    </td>
                                    <td class="td-c">
                                        {$datearray.{$v.fp|substr:0:2}}-{$v.fp|substr:2}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>
                                    <td class="td-c">
                                        {if $v.status==0}
                                            Processing
                                        {else if $v.status==1}
                                            Validated
                                        {else if $v.status==2}
                                            Failed
                                        {else if $v.status==4}
                                            Ready for upload
                                        {else if $v.status==5}
                                            Uploaded
                                        {else if $v.status==6}
                                            Saved to GSTN
                                        {/if}
                                    </td>
                                    <td>

                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                {if $v.status=='0'}
                                                    <li>
                                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/gst/delete3b/{$v.link}'" data-toggle="modal" ><i class="fa fa-remove"></i> Delete </a>
                                                    </li>
                                                    {else if {$v.status}=='1'}
                                                        <li>
                                                            <a href="/merchant/gst/draft3b/{$v.link}/create" ><i class="fa fa-table"></i>Create Draft</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/gst/delete3b/{$v.link}'" data-toggle="modal" ><i class="fa fa-remove"></i> Delete </a>
                                                        </li>
                                                        {else if {$v.status}=='4'}
                                                            <li>
                                                                <a href="/merchant/gst/draft3b/{$v.link}" ><i class="fa fa-table"></i>Submit Draft</a>
                                                            </li>
                                                            <li>
                                                                <a href="#deleted" onclick="document.getElementById('deletedraftanchor').href = '/merchant/gst/draft3b/{$v.link}/delete" data-toggle="modal" ><i class="fa fa-remove"></i>Delete Draft</a>
                                                            </li>
                                                        {/if}
                                                        <li>
                                                            <a href="/uploads/Excel/{$merchant_id}/staging/{$v.system_filename}" ><i class="fa fa-download"></i>Download</a>
                                                        </li>
                                                    </ul>
                                                </div>


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
                    <h4 class="modal-title">Delete 3B file</h4>
                </div>
                <div class="modal-body">
                    Are you sure you would not like to use this file in the future?
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

    <div class="modal fade" id="deleted" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Draft</h4>
                </div>
                <div class="modal-body">
                    You are about to delete draft. Are you sure you would like to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <a href="" id="deletedraftanchor" class="btn blue">Confirm</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>