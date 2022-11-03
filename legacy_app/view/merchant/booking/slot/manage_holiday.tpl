
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
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
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="/merchant/bookings/managecalendar/slot/{$link}" >Manage Time Slots</a>
                    </li>
                    <li class="active">
                        <a href="/merchant/bookings/managecalendar/holiday/{$link}" >Manage Holidays </a>
                    </li>
                    <li >
                        <a href="/merchant/bookings/managecalendar/notification/{$link}" >Manage Notifications & Data </a>
                    </li>
                </ul>

            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">



            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        Add new holiday
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title="">
                        </a>
                    </div>
                </div>
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" onsubmit="return confirm('Existing slots will be deleted for this date. Are you sure you want to continue?');" action="/merchant/bookings/addholidays" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Holiday Date</label>
        <input class="form-control form-control-inline  date-picker" required type="text" name="holiday"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Holiday date"/>
                        </div>
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <input type="hidden"  name="calendar_id" value="{$link}"/>
                            <button type="submit" class="btn blue">Add Holiday</button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    ID
                                </th>
                                
                                <th class="td-c">
                                    Holiday date
                                </th>
                               
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.holiday_id}
                                    </td>
                                  
                                    <td class="td-c">
                                        {{$v.holiday_date}|date_format:"%Y-%m-%d"}
                                    </td>
                                    
                                    <td class="td-c">
                                        <a href="#basic" class="btn btn-sm red" onclick="document.getElementById('deleteanchor').href = '/merchant/bookings/delete/3/{$v.encrypted_id}'" data-toggle="modal"><i class="fa fa-times"></i> Delete </a>
                                    </td>
                                </tr>

                            {/foreach}
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>
            
            

            <!-- END PORTLET-->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete holiday</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this holiday in the future?
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