
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        </div>
        <!-- END PAGE HEADER-->

        <!-- BEGIN SEARCH CONTENT-->
        {if $is_filter=='True'}
        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN PORTLET-->

                <div class="portlet">
                    
                    <div class="portlet-body" >

                        <form class="form-inline" role="form" action="" method="post">
                            <div class="form-group">
                                    <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                                </div>

                            <div class="form-group">
                                <select class="form-control input-sm select2me" name="cycle_name" data-placeholder="Merchant name">
                                    <option value=""></option>

                                    {foreach from=$cycle_list key=k item=v}
                                        {if {{$cycle_selected}=={$v.id}}}
                                            <option selected value="{$v.id}" selected>{$v.name}</option>
                                        {else}
                                             {if $v.name!=''}
                                            <option value="{$v.id}">{$v.name}</option>
                                            {/if}
                                        {/if}

                                    {/foreach}

                                </select>
                            </div>
                            <input type="submit" class="btn blue" value="Search">
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
                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Received Date
                                    </th>
                                    <th class="td-c">
                                        Due date
                                    </th>
                                    <th class="td-c">
                                        Merchant name
                                    </th>
                                    <th class="td-c">
                                        Amount
                                    </th>
                                    <th class="td-c">
                                        Status
                                    </th>
                                    <th class="td-c">
                                        View
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {foreach from=$requestlist item=v}
                                    <tr>
                                       
                                        <td class="td-c">
                                            {{$v.received_date}|date_format:"%Y-%m-%d"}
                                        </td>
                                        <td class="td-c">
                                            {{$v.due_date}|date_format:"%Y-%m-%d"}
                                        </td>
                                        <td class="td-c">
                                            {$v.name}
                                        </td>

                                        <td class="td-c">
                                            {$v.absolute_cost}
                                        </td>
                                        <td class="td-c">
                                            {if {$v.status}=='Submitted'} <span class="label label-sm label-success">Submitted
                                                                        </span> {else} <span class="label label-sm label-danger">Failed
                                                                        </span> {/if}
								</td>
                                        <td class="td-c">
                                            <a href="{$v.paylink}" target="_BLANK" class="btn btn-xs blue"><i class="fa fa-table"></i> Pay </a>
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
<!-- /.modal -->

<!-- /.modal-dialog -->
</div>
<!-- /.modal -->