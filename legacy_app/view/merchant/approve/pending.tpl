
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    {if $is_filter=='True'}
        <div class="row">
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
                <!-- BEGIN PORTLET-->
                <div class="portlet">

                    <div class="portlet-body" >

                        <form class="form-inline" role="form" action="" method="post">
                            <div class="form-group">
                                <label class="help-block">Date</label>
                                <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                            </div>

                            <div class="form-group">
                                <select class="form-control input-sm select2me" name="cycle_name" data-placeholder="Billing cycle name">
                                    <option value=""></option>

                                    {foreach from=$cycle_list key=k item=v}
                                        {if {{$cycle_selected}=={$v.id}}}
                                            <option selected value="{$v.id}" selected>{$v.name}</option>
                                        {else}
                                            <option value="{$v.id}">{$v.name}</option>
                                        {/if}

                                    {/foreach}

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

            <div class="portlet">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="table-accordian">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <i class="fa fa-check"></i>
                                </th>
                                <th>
                                    Date
                                </th>
                                <th>
                                    User code
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Mobile
                                </th>
                                <th style="display: none;">
                                    Json
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$requestlist key=k item=v}
                                <tr class="odd gradeX">
                                    <td>
                                        <input type="checkbox" class="checkboxes" value="1"/>
                                    </td>
                                    <td>
                                        {{$v.0.date}|date_format:"%d/%b/%y"}
                                    </td>
                                    <td >
                                        {$v.0.customer_code}
                                    </td>
                                    <td>
                                        {$v.0.name}
                                    </td>
                                    <td >
                                        {$v.0.email}
                                    </td>

                                    <td>
                                        {$v.0.mobile}
                                    </td>
                                    <td style="display: none;">
                                        {$k}
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

<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    var json_req = '{$json_req}';
</script>