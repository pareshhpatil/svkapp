
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    {if $is_filter=='True'}
        <div class="row">

            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>  {$success}
                </div> 
            {/if}
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->

                <div class="portlet">
                    
                    <div class="portlet-body" >

                        <form class="form-inline" role="form" action="" method="post">
                            <div class="form-group">
                                <select name="status"  class="form-control" data-placeholder="Select...">
                                    <option {if $status==1} selected {/if} value="1" >Active</option>
                                    <option {if $status==2} selected {/if} value="2" >Expired</option>
                                    <option {if $status==3} selected {/if} value="3" >Deactivated</option>
                                </select>
                            </div>

                            <input type="submit" class="btn btn-sm blue" value="Search">
                        </form>

                    </div>
                </div>

                <!-- END PORTLET-->
            </div>
        </div>
    {/if}
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Created date
                                </th>
                                
                                {if $has_franchise==1}
                                    <th class="td-c">
                                        Franchise name
                                    </th>
                                {/if}
                                <th class="td-c">
                                    Event name
                                </th>
                                <th class="td-c">
                                    Created date
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {$int=0}
                            {foreach from=$requestlist item=v}
                                {$int=$int+1}
                                <tr>
                                    <td class="td-c">
                                        {{$v.created_date}|date_format:"%Y-%m-%d %I:%M %p"}
                                    </td>
                                    
                                    {if $has_franchise==1}
                                        <td class="td-c">
                                            {$v.franchise_name}
                                        </td>
                                    {/if}
                                    <td class="td-c">
                                        <a href="/merchant/event/view/{$v.encrypted_id}" target="_BLANK" title="View event"> {$v.event_name}</a>
                                    </td>
                                    <td class="td-c">
                                        {$v.created_at}
                                    </td>
                                    <td class="td-c">
                                        {if $status==1}
                                            <span class="label label-sm label-success">Active</span>
                                        {elseif $status==3}
                                            <span class="label label-sm label-danger">Deactivated</span>
                                        {else}
                                            <span class="label label-sm label-warning">Expired</span>
                                        {/if}
                                    </td>
                                    <td class="td-c">
                                        <!-- <div class="visible-xs btn-group-vertical" >
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                </span>
                                                <span class="input-group-btn">
                                                    <a href="/merchant/event/view/{$v.encrypted_id}" target="_BLANK" title="View event" class="btn btn-xs blue" ><i class="fa fa-table"></i></a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a href="/merchant/event/update/{$v.encrypted_id}" target="_BLANK" title="Update event" class="btn btn-xs green"><i class="fa fa-edit"></i></a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a href="/merchant/event/attendees/{$v.encrypted_id}" target="_BLANK" title=" Attendees list" class="btn btn-xs yellow"><i class="fa fa-users"></i></a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a href="/merchant/event/packagesummary/{$v.encrypted_id}" target="_BLANK" title=" Package summary" class="btn btn-xs yellow"><i class="fa fa-file"></i></a>
                                                </span>
                                                {if $status!=3}
                                                    <span class="input-group-btn">
                                                        <a href="#basic"  title="Deactivate event" class="btn btn-xs red" onclick="document.getElementById('deleteanchor').href = '/merchant/event/delete/{$v.encrypted_id}'" data-toggle="modal" ><i class="fa fa-times"></i></a>
                                                    </span>
                                                {/if}

                                                <span class="input-group-btn">
                                                    <div style="font-size: 0px;"><abc{$int}>{$v.short_url}</abc{$int}></div>
                                                    <a class="btn btn-xs green bs_growl_show" title="Copy Event Link" data-clipboard-action="copy"  data-clipboard-target="abc{$int}"><i class="fa fa-clipboard"></i></a>
                                                </span>
                                                <span class="input-group-btn">
                                                    <a href="https://api.whatsapp.com/send?text=Check out the latest event {$v.event_name} by {$company_name}. Click here to view event details and book your seats - {$v.short_url}" target="_BLAK" class="btn green btn-xs"><i class="fa fa-whatsapp"></i></a>
                                                </span>
                                            </div>
                                        </div> -->
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">

                                                <li>
                                                    <a href="/merchant/event/view/{$v.encrypted_id}" target="_BLANK" ><i class="fa fa-table"></i> View event</a>
                                                </li>
                                                {if $status!=3}
                                                    <li>
                                                        <a href="/merchant/event/update/{$v.encrypted_id}" target="_BLANK" ><i class="fa fa-edit"></i> Update event</a>
                                                    </li>
                                                {/if}
                                                <li>
                                                    <a href="/merchant/event/attendees/{$v.encrypted_id}" target="_BLANK"><i class="fa fa-users"></i> Attendees list</a>
                                                </li>
                                                <li>
                                                    <a href="/merchant/event/packagesummary/{$v.encrypted_id}" target="_BLANK"><i class="fa fa-file"></i> Package summary</a>
                                                </li>
                                                {if $status!=3}
                                                    <li>
                                                        <a href="#basic"  onclick="document.getElementById('deleteanchor').href = '/merchant/event/delete/{$v.encrypted_id}'" data-toggle="modal" ><i class="fa fa-times"></i> Deactivate</a>
                                                    </li>
                                                {/if}
                                                <li class="divider"></li>
                                                <li>
                                                    <div style="font-size: 0px;"><abcd{$int}>{$v.short_url}</abcd{$int}></div>
                                                    <a class="btn bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="abcd{$int}"><i class="fa fa-clipboard"></i> Copy Event Link</a>
                                                </li>
                                                <li>
                                                    <a href="https://api.whatsapp.com/send?text=Check out the latest event {$v.event_name} by {$company_name}. Click here to view event details and book your seats - {$v.short_url}" target="_BLAK" class="btn"><i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                            {/foreach}
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
<!-- /.modal -->
<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Deactivate event</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this event?
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
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->