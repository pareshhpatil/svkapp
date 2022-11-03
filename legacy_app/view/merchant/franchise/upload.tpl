<style>
    .pagination{
        margin-top: 10px !important;
    }
</style>
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

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
        <div class="col-md-12">
            <form action="/merchant/franchise/upload" enctype="multipart/form-data" method="post">
                {CSRF::create('franchise_upload')}
                <div class="portlet ">
                    <div class="portlet-body">
                        <div class="panel-group accordion" id="accordion1">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion1"
                                            href="#collapse_1">
                                            <b>Step #1 - Download & Fill excel sheet</b> </a>
                                    </h4>
                                </div>
                                <div id="collapse_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <p>Download the excel format for upload vendor <a
                                                href="/merchant/franchise/download" class="btn btn-xs blue">Download
                                                format</a></p>
                                        <p>In the downloaded excel sheet enter {$type} details. Use column names
                                            mentioned in the excel sheet as reference.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion1"
                                            href="#collapse_2">
                                            <b>Step #2 - Upload filled sheet</b> </a>
                                    </h4>
                                </div>
                                <div id="collapse_2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p>Upload filled excel sheet</p>
                                        <p>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new">
                                                    Select file </span>
                                                <span class="fileinput-exists">
                                                    Change </span>
                                                <input type="file" accept=".xlsx" name="fileupload" required>
                                            </span>
                                            <span class="fileinput-filename">
                                            </span>
                                            &nbsp; <a href="javascript:;" class="close fileinput-exists"
                                                data-dismiss="fileinput">
                                            </a>
                                        </div>
                                        </p>
                                        <p><input type="submit" class="btn blue" value="Upload" />
                                            <input type="hidden" name="type" value="{$type}">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END UPLOAD EXCEL BOX -->
            <!-- BEGIN CREATED TEMPLATES LISTING BOX -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-bordered table-striped table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Uploaded on
                                </th>
                                <th class="td-c">
                                    Uploaded on
                                </th>
                                <th class="td-c">
                                    Excel file name
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
                                <tr>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%Y-%m-%d %I:%M %p"}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$link=''}
                                        {if $v.status=='1'}
                                            {$link='/merchant/franchise/error/'}
                                        {else if {$v.status}=='3'}
                                            {$link='/merchant/franchise/bulklist/'}
                                        {else if {$v.status}=='4'}
                                            {$link='/merchant/franchise/bulklist/'}
                                        {else if {$v.status}=='5'}
                                            {$link='/merchant/franchise/viewlist/'}
                                        {/if}
                                        {if $link!=''}
                                            <a {if $v.status=='1'} class="iframe" {/if}
                                                href="{$link}{$v.bulk_id}">{$v.merchant_filename}</a>
                                        {else}
                                            {$v.merchant_filename}
                                        {/if}
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
                                        {else if {$v.status}=='5'}
                                            <span class="label label-sm label-success">
                                                Saved
                                            </span>
                                        {else if {$v.status}=='8'}
                                            <span class="label label-sm label-default">
                                                {$v.config_value}
                                            </span>
                                        {/if}

                                    </td>
                                    <td>
                                        {if $v.status!='8'}
                                            <!-- <div class="visible-xs btn-group-vertical">

                                                <span class="input-group-btn">
                                                </span>

                                                {if {$v.status}=='1'}
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs blue" title="Download sheet"
                                                            href="/merchant/bulkupload/download/{$v.bulk_id}"><i
                                                                class="fa fa-download"></i></a></span>

                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs yellow" title="Re-upload sheet"
                                                            href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                class="fa fa-undo"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs red" title="Delete sheet" href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}'"
                                                            data-toggle="modal"><i class="fa fa-times"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs yellow" title="View errors"
                                                            href="/merchant/franchise/error/{$v.bulk_id}" class="iframe"><i
                                                                class="fa fa-exclamation-triangle"></i></a>
                                                    </span>

                                                {else if {$v.status}=='2'}
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs blue" title="Download sheet"
                                                            href="/merchant/bulkupload/download/{$v.bulk_id}"><i
                                                                class="fa fa-download"></i></a></span>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs yellow" title="Re-upload sheet"
                                                            href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                class="fa fa-undo"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs red" title="Delete sheet" href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}'"
                                                            data-toggle="modal"><i class="fa fa-times"></i></a>
                                                    </span>
                                                {else if {$v.status}=='3'}
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs blue" title="Download sheet"
                                                            href="/merchant/bulkupload/download/{$v.bulk_id}"><i
                                                                class="fa fa-download"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs green" title="View vendors"
                                                            href="/merchant/franchise/bulklist/{$v.bulk_id}"><i
                                                                class="fa fa-users"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs yellow" title="Re-upload sheet"
                                                            href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                class="fa fa-undo"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs red" title="Delete sheet" href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}'"
                                                            data-toggle="modal"><i class="fa fa-times"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs green" title="Approve {$type}" href="#sendinvoice"
                                                            onclick="document.getElementById('sendanchor').href = '/merchant/bulkupload/send/{$v.bulk_id}/{$type_}'"
                                                            data-toggle="modal"><i class="fa fa-send"></i></a>
                                                    </span>
                                                {else if {$v.status}=='4'}
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs blue" title="Download sheet"
                                                            href="/merchant/bulkupload/download/{$v.bulk_id}"><i
                                                                class="fa fa-download"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs green" title="View {$type}"
                                                            href="/merchant/franchise/bulklist/{$v.bulk_id}"><i
                                                                class="fa fa-users"></i></a>
                                                    </span>
                                                {else if {$v.status}=='5'}
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs green" title="View {$type}"
                                                            href="/merchant/franchise/viewlist/{$v.bulk_id}"><i
                                                                class="fa fa-users"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs blue" title="Download sheet"
                                                            href="/merchant/bulkupload/download/{$v.bulk_id}"><i
                                                                class="fa fa-download"></i></a>
                                                    </span>
                                                    <span class="input-group-btn">
                                                        <a class="btn  btn-xs red" title="Delete sheet" href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}'"
                                                            data-toggle="modal"><i class="fa fa-times"></i></a>
                                                    </span>
                                                {/if}
                                            </div> -->
                                            <div class="btn-group dropup">
                                                <button class="btn btn-xs btn-link dropdown-toggle" type="button"
                                                    data-toggle="dropdown">
                                                    &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    {if {$v.status}=='1'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                    class="fa fa-undo"></i> Re-upload sheet</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/franchise/bulkerror/{$v.bulk_id}" class="iframe"><i
                                                                    class="fa fa-exclamation-triangle"></i> View errors</a>
                                                        </li>

                                                    {else if {$v.status}=='2'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                    class="fa fa-undo"></i> Re-upload sheet</a>
                                                        </li>
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
                                                        <li><a href="/merchant/franchise/bulklist/{$v.bulk_id}"><i
                                                                    class="fa fa-table"></i> View {$type}s</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/franchise/reupload/{$v.bulk_id}"><i
                                                                    class="fa fa-undo"></i> Re-upload sheet</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic"
                                                                onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}/{$type_}';"
                                                                data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                        </li>
                                                        <li> <a href="#sendinvoice"
                                                                onclick="document.getElementById('sendanchor').href = '/merchant/bulkupload/send/{$v.bulk_id}/{$type_}'"
                                                                data-toggle="modal"><i class="fa fa-check"></i> Approve {$type}s</a>
                                                        </li>
                                                    {else if {$v.status}=='4'}
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
                                                        </li>
                                                    {else if {$v.status}=='5'}
                                                        <li>
                                                            <a href="/merchant/franchise/viewlist/{$v.bulk_id}"><i
                                                                    class="fa fa-table"></i> View {$type}s</a>
                                                        </li>
                                                        <li>
                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}/{$type_}"><i
                                                                    class="fa fa-download"></i> Download sheet</a></span>
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
                                    <h4 class="modal-title">Save Excel {$type}</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to save these {$type}s?
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