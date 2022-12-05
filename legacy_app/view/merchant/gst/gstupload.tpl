<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
            <!-- BEGIN UPLOAD EXCEL BOX -->
            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div>
            {/if}

            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}

            <form action="/merchant/gst/upload" onsubmit="document.getElementById('loader').style.display = 'block'"
                enctype="multipart/form-data" method="post">
                {CSRF::create('gst_invoice_upload')}
                <div class="">
                    <div class="portlet-body">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>Upload {$sub_type} MTR sheet</p>
                                <div class="form-inline">
                                    {if $sub_type=='stock'}
                                        <input type="hidden" name="type" value="Stock">
                                    {else}
                                        <div class="form-group">
                                            <div class="md-radio-inline">
                                                <div class="form-group radio-box">
                                                    <input type="radio" required="" value="Amazon" id="amazon" name="type">
                                                    <label for="amazon">
                                                        <img src="/assets/admin/layout/img/amazon.png"
                                                            style="height: 30px;cursor: pointer;">
                                                    </label>
                                                </div>
                                                <div class="form-group radio-box">
                                                    <input type="radio" required="" value="Flipkart" id="flipkart"
                                                        name="type">
                                                    <label for="flipkart">
                                                        <img src="/assets/admin/layout/img/flipkart.png"
                                                            style="height: 30px;cursor: pointer;">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="fileinput fileinput-new ml-1" data-provides="fileinput">
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new">
                                                Select file </span>
                                            <span class="fileinput-exists">
                                                Change </span>
                                            <input type="file" accept=".csv,.xlsx" name="fileupload" required>
                                        </span>
                                        <span class="fileinput-filename">
                                        </span>
                                        &nbsp; <a href="javascript:;" class="close fileinput-exists"
                                            data-dismiss="fileinput">
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn blue" value="Upload" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>



                </div>
            </form>
            <!-- END UPLOAD EXCEL BOX -->
            <!-- BEGIN CREATED TEMPLATES LISTING BOX -->
            <h3 class="form-section">Uploaded sheets</h3>
            <div class="portlet ">
                <div class="portlet-body">

                    <table class="table table-bordered table-striped table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Uploaded on
                                </th>

                                <th class="td-c">
                                    Excel file name
                                </th>
                                <th class="td-c">
                                    Uploaded on
                                </th>
                                <th class="td-c">
                                    Rows
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c" style="width: 50px;">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$bulklist item=v}
                                {if $type=='transfer'}
                                    {$vlist='transferlist'}
                                {else}
                                    {$vlist='viewlist'}
                                {/if}
                                <tr>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%Y-%m-%d %I:%M %p"}
                                    </td>

                                    <td class="td-c">
                                        {$link=''}
                                        {if $v.status=='1'}
                                            {$link='/merchant/vendor/bulkerror/'}
                                        {else if {$v.status}=='3'}
                                            {$link='/merchant/gst/invoicelist/'}
                                        {else if {$v.status}=='5'}
                                            {$link='/merchant/gst/invoicelist/'}
                                        {/if}
                                        {if $link!=''}
                                            <a {if $v.status=='1'} class="iframe" {/if}
                                                href="{$link}{$v.bulk_id}/1">{$v.merchant_filename}</a>
                                        {else}
                                            {$v.merchant_filename}
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>
                                    <td class="td-c">
                                        {$v.total_rows}
                                    </td>

                                    <td class="td-c">
                                        {if {$v.status}=='1'}
                                            <span class="label label-sm label-danger">
                                                {$v.config_value}
                                            </span>
                                        {else if {$v.status}=='2'}
                                            <span class="label label-sm label-default">
                                                {$v.config_value}
                                            </span>
                                        {else if {$v.status}=='3'}
                                            <span class="label label-sm label-warning">
                                                {$v.config_value}
                                            </span>
                                        {else if {$v.status}=='4'}
                                            <span class="label label-sm label-default">
                                                Saving
                                            </span>
                                        {else if {$v.status}=='8'}
                                            <span class="label label-sm label-default">
                                                {$v.config_value}
                                            </span>
                                        {else if {$v.status}=='5'}
                                            <span class="label label-sm label-success">
                                                Approved
                                            </span>
                                        {/if}
                                    </td>
                                    <td>
                                        {if $v.status!='8'}
                                            <div class="btn-group dropup" style="position: absolute;">
                                                <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                    data-toggle="dropdown">
                                                    &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                </button>
                                                <ul class="dropdown-menu pull-right" role="menu">
                                                    {if {$v.status}=='1'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                        <!--<li>
                                                                                    <a href="/merchant/gst/reupload/{$v.sub_type}/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                </li>-->
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/vendor/bulkerror/{$v.bulk_id}" class="iframe"><i
                                                                    class="fa fa-exclamation-triangle"></i> View errors</a>
                                                        </li>

                                                    {else if {$v.status}=='2'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                        <!--<li>
                                                                                        <a href="/merchant/gst/reupload/{$v.sub_type}/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                    </li>-->
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>
                                                    {else if {$v.status}=='3'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>

                                                        <!--<li>
                                                                                            <a href="/merchant/gst/reupload/{$v.sub_type}/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                        </li>-->
                                                        <li>
                                                            <a href="/merchant/gst/invoicelist/{$v.bulk_id}/1" target="_BLANK"><i
                                                                    class="fa fa-table"></i> View invoices</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/gst/invoicelist/{$v.bulk_id}/creditnote"
                                                                target="_BLANK"><i class="fa fa-table"></i> View credit note</a>
                                                        </li>
                                                        <li>
                                                            <a href="#sendinvoice"
                                                                onclick="document.getElementById('sendanchor').href = '/merchant/gst/approveinvoice/{$v.bulk_id}/{if $sub_type=='stock'}stock{else}1{/if}'"
                                                                data-toggle="modal"><i class="fa fa-check"></i> Approve sheet</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>
                                                    {else if {$v.status}=='4'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                    {else if {$v.status}=='5'}
                                                        <li>
                                                            <a href="/merchant/vendor/{$vlist}/{$v.bulk_id}"><i
                                                                    class="fa fa-table"></i> View {$type}s</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/gst/invoicelist/{$v.bulk_id}/1" target="_BLANK"><i
                                                                    class="fa fa-table"></i> View invoices</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/gst/invoicelist/{$v.bulk_id}/creditnote"
                                                                target="_BLANK"><i class="fa fa-table"></i> View credit note</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>

                                                    {/if}
                                                </ul>
                                            </div>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                    <h4 class="modal-title">Delete Excel sheet</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you would not like to use this Excel in the future?
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
                    <div class="modal fade" id="sendinvoice" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true"></button>
                                    <h4 class="modal-title">Approve Invoice</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to approve these invoices?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    <a href="" id="sendanchor" class="btn blue">Confirm</a>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
            <!-- END CREATED TEMPLATES LISTING BOX -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>