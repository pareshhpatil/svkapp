
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/promotions/create" class="btn blue pull-right mb-1"> Create</a>

    </div>

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div> 
        {/if}

    </div>

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN BULK UPLOAD REQUEST TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    {$lang_title.create_date}
                                </th>

                                <th class="td-c">
                                    {$lang_title.promotion_name}
                                </th>
                                <th class="td-c">
                                    {$lang_title.message}
                                </th>
                                <th class="td-c">
                                    {$lang_title.records}
                                </th>
                                <th class="td-c">
                                    Created date
                                </th>
                                <th class="td-c">
                                    {$lang_title.status}
                                </th>

                                <th class="td-c" style="width: 50px;">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%Y-%m-%d %I:%M %p"}
                                    </td>

                                    <td  class="td-c">
                                        {$v.promotion_name}
                                    </td>
                                    <td  class="td-c">
                                        {$v.template_name}
                                        <span class="popovers" data-container="body" data-trigger="hover" data-content="{$v.sms_text}"><i class="fa fa-info-circle" style="color: #275770;"></i></span>
                                    </td>
                                    <td  class="td-c">
                                        {$v.total_records}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>

                                    <td class="td-c">
                                        {if {$v.swipez_status}=='0'}
                                            <span class="label label-sm label-default">
                                                Submited
                                            </span>
                                            {else if {$v.swipez_status}=='1'}
                                                <span class="label label-sm label-warning">
                                                    Processing
                                                </span>
                                                {else if {$v.swipez_status}=='2'}
                                                    <span class="label label-sm label-danger">
                                                        Failed
                                                    </span>
                                                {else}
                                                    <span class="label label-sm label-success">
                                                        Sent
                                                    </span>
                                                {/if}
                                            </td>

                                            <td >
                                                <!-- <div class="visible-xs btn-group-vertical">

                                                    <span class="input-group-btn">
                                                    </span>

                                                    {if {$v.swipez_status}=='0'}
                                                        <span class="input-group-btn">
                                                            <a class="btn  btn-xs red" title="Delete promotion" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/promotions/delete/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                        </span>
                                                        {else if {$v.swipez_status}=='2'}
                                                            <span class="input-group-btn">
                                                                <a class="btn  btn-xs red" title="Delete promotion" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/promotions/delete/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                            </span>
                                                            {else if {$v.swipez_status}=='3'}
                                                                <span class="input-group-btn">
                                                                    <a class="btn  btn-xs yellow"  href="#fetchreport" onclick="document.getElementById('sendanchor').href = '/merchant/promotions/fetchreport/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i></a>
                                                                </span>
                                                                {else if {$v.swipez_status}=='5'}
                                                                    <span class="input-group-btn">
                                                                        <a class="btn  btn-xs green" title="Delete promotion" href="/merchant/promotions/downloadreport/{$v.encrypted_id}'" ><i class="fa fa-download"></i></a>
                                                                    </span>
                                                                {/if}
                                                            </div> -->
                                                            <div class="btn-group dropup">
                                                                {if $v.swipez_status!='1' && $v.swipez_status!='4'}
                                                                    <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                                        &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">

                                                                        {if {$v.swipez_status}=='0'}
                                                                            <li>
                                                                                <a  title="Delete promotion" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/promotions/delete/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete promotion </a>
                                                                            </li>
                                                                            {else if {$v.swipez_status}=='2'}
                                                                                <li>
                                                                                    <a  title="Delete promotion" href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/promotions/delete/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete promotion </a>
                                                                                </li>
                                                                                {else if {$v.swipez_status}=='3'}
                                                                                    <li>
                                                                                        <a  href="#fetchreport" onclick="document.getElementById('sendanchor').href = '/merchant/promotions/fetchreport/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i> Fetch Report</a>
                                                                                    </li>
                                                                                    {else if {$v.swipez_status}=='5'}
                                                                                        <li>
                                                                                            <a  href="/merchant/promotions/downloadreport/{$v.encrypted_id}'" ><i class="fa fa-download"></i> Download Report</a>
                                                                                        </li>

                                                                                    {/if}

                                                                                </ul>
                                                                            {/if}
                                                                        </div>
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
                                                                    <h4 class="modal-title">Delete Promotion</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you would not like to send this promotion?
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
                                                    <div class="modal fade" id="fetchreport" tabindex="-1" role="basic" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                    <h4 class="modal-title">Fetch SMS Report</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    The requested data is too large to be downloaded instantaneously. We will started your download and the requested file will soon appear in "Download" section. 
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