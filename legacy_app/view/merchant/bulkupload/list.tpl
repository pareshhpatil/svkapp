
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div> 
        {/if}
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">

                <div class="portlet-body">

                    <form class="form-inline" action="" method="post" role="form">
                        <div class="form-group">
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"   name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <input type="submit" class="btn blue" value="Search" />
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN BULK UPLOAD REQUEST TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-small">
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
                                    Total rows
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
                                        {$link=''}
                                        {if $v.status=='1'}
                                            {$link='/merchant/bulkupload/error/'}
                                            {else if {$v.status}=='3'}
                                                {$link='/merchant/bulkupload/bulkinvoice/'}
                                                {else if {$v.status}=='4'}
                                                    {$link='/merchant/bulkupload/bulkinvoice/'}
                                                    {else if {$v.status}=='5'}
                                                        {$link='/merchant/bulkupload/bulkrequest/'}
                                                    {/if}
                                                    {if $link!=''}
                                                        <a {if $v.status=='1'} class="iframe" {/if} href="{$link}{$v.bulk_id}">{$v.merchant_filename}</a>
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
                                                                        {$v.config_value}
                                                                    </span>
                                                                    {else if {$v.status}=='5'}
                                                                        <span class="label label-sm label-success">
                                                                            {$v.config_value}
                                                                        </span>
                                                                    {else if {$v.status}=='8'}
                                                                        <span class="label label-sm label-default">
                                                                            {$v.config_value}
                                                                        </span>
                                                                    {/if}

                                                                    </td>

                                                                    <td >
                                                                    {if $v.status!='8'}
                                                                        <!-- <div class="visible-xs btn-group-vertical">

                                                                            <span class="input-group-btn">
                                                                            </span>

                                                                            {if {$v.status}=='1'}
                                                                                <span class="input-group-btn">
                                                                                    <a class="btn  btn-xs blue" title="Download sheet" href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i></a></span>

                                                                                <span class="input-group-btn">
                                                                                    <a class="btn  btn-xs yellow" title="Re-upload sheet" href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i></a>
                                                                                </span>
                                                                                <span class="input-group-btn">
                                                                                    <a class="btn  btn-xs red" title="Delete sheet" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                                                </span>
                                                                                <span class="input-group-btn">
                                                                                    <a class="btn  btn-xs yellow" title="View errors" href="/merchant/bulkupload/error/{$v.bulk_id}" class="iframe" ><i class="fa fa-exclamation-triangle"></i></a>
                                                                                </span>

                                                                                {else if {$v.status}=='2'}
                                                                                    <span class="input-group-btn">
                                                                                        <a class="btn  btn-xs blue" title="Download sheet" href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i></a></span>
                                                                                    </span>
                                                                                    <span class="input-group-btn">
                                                                                        <a class="btn  btn-xs yellow" title="Re-upload sheet" href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i></a>
                                                                                    </span>
                                                                                    <span class="input-group-btn">
                                                                                        <a class="btn  btn-xs red" title="Delete sheet" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                                                    </span>
                                                                                    {else if {$v.status}=='3'}
                                                                                        <span class="input-group-btn">
                                                                                            <a class="btn  btn-xs blue" title="Download sheet" href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i></a>
                                                                                        </span>
                                                                                        <span class="input-group-btn">
                                                                                            <a class="btn  btn-xs green" target="_BLANK" title="View requests" href="/merchant/bulkupload/bulkinvoice/{$v.bulk_id}" ><i class="fa fa-table"></i></a>
                                                                                        </span>
                                                                                        <span class="input-group-btn">
                                                                                            <a class="btn  btn-xs yellow" title="Re-upload sheet" href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i></a>
                                                                                        </span>
                                                                                        <span class="input-group-btn">
                                                                                            <a class="btn  btn-xs red" title="Delete sheet" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                                                        </span>
                                                                                        <span class="input-group-btn">
                                                                                            <a class="btn  btn-xs green" title="Send requests" href="#sendinvoice" onclick="document.getElementById('sendanchor').href = '/merchant/bulkupload/send/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-send"></i></a>
                                                                                        </span>
                                                                                        {else if {$v.status}=='4'}
                                                                                            <span class="input-group-btn">
                                                                                                <a class="btn  btn-xs blue" title="Download sheet" href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i></a>
                                                                                            </span>
                                                                                            {else if {$v.status}=='5'}
                                                                                                <span class="input-group-btn">
                                                                                                    <a class="btn  btn-xs green" target="_BLANK" title="View requests" href="/merchant/bulkupload/bulkrequest/{$v.bulk_id}" ><i class="fa fa-table"></i></a>
                                                                                                </span>
                                                                                                <span class="input-group-btn">
                                                                                                    <a class="btn  btn-xs blue" title="Download sheet" href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i></a>
                                                                                                </span>
                                                                                                <span class="input-group-btn">
                                                                                                    <a class="btn  btn-xs red" title="Delete sheet" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                                                                </span>
                                                                                            {/if}
                                                                                        </div> -->
                                                                                        <div class="btn-group dropup">
                                                                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                                                            </button>
                                                                                            <ul class="dropdown-menu" role="menu">


                                                                                                {if {$v.status}=='1'}
                                                                                                    <li>
                                                                                                        <a href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i> Download sheet</a></span>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <a href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <a href="/merchant/bulkupload/error/{$v.bulk_id}" class="iframe" ><i class="fa fa-exclamation-triangle"></i> View errors</a>
                                                                                                    </li>

                                                                                                    {else if {$v.status}=='2'}
                                                                                                        <li>
                                                                                                            <a href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i> Download sheet</a></span>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <a href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                                                                        </li>
                                                                                                        {else if {$v.status}=='3'}
                                                                                                            <li>
                                                                                                                <a href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i> Download sheet</a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <a target="_BLANK"  href="/merchant/bulkupload/bulkinvoice/{$v.bulk_id}" ><i class="fa fa-table"></i> View requests</a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <a href="/merchant/bulkupload/reupload/{$v.bulk_id}"><i class="fa fa-undo"></i> Re-upload sheet</a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <a href="#sendinvoice" onclick="document.getElementById('sendanchor').href = '/merchant/bulkupload/send/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-send"></i> Send requests</a>
                                                                                                            </li>
                                                                                                            {else if {$v.status}=='4'}
                                                                                                                <li>
                                                                                                                    <a href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i> Download sheet</a>
                                                                                                                </li>
                                                                                                                <li> 
                                                                                                                    <a target="_BLANK"  href="/merchant/bulkupload/bulkinvoice/{$v.bulk_id}" ><i class="fa fa-table"></i> View requests</a>
                                                                                                                </li>
                                                                                                                {else if {$v.status}=='5'}
                                                                                                                    <li> 
                                                                                                                        <a target="_BLANK" href="/merchant/bulkupload/bulkrequest/{$v.bulk_id}" ><i class="fa fa-table"></i> View requests</a>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <a href="/merchant/bulkupload/download/{$v.bulk_id}" ><i class="fa fa-download"></i> Download sheet</a>
                                                                                                                    </li>
                                                                                                                    <li>
                                                                                                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/bulkupload/delete/{$v.bulk_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete sheet</a>
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
                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
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
                                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                            <h4 class="modal-title">Send Excel Invoices</h4>
                                                                                                        </div>
                                                                                                        <div class="modal-body">
                                                                                                            Are you sure you want to send these invoices right away?
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
                                                                                    <!-- END BULK UPLOAD REQUEST TABLE -->
                                                                                </div>
                                                                            </div>
                                                                            <!-- END PAGE CONTENT-->
                                                                        </div>
                                                                                           
                                                                    </div>

                                                                    <script>
                                                                        setTimeout(function () {
                                                                            location.reload();
                                                                        }, 180000);
                                                                    </script>