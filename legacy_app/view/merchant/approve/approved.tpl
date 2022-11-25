<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
        </div> {/if}
        <div class="col-md-12">


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


                <!-- BEGIN PORTLET-->

                <div class="portlet">
                    <div class="portlet-body">

                        <form class="form-inline" role="form" action="" method="post">
                            <div class="form-group">
                                <label class="help-block">Approved date</label>
                                <input class="form-control form-control-inline  rpt-date" id="daterange" autocomplete="off"
                                    data-date-format="dd M yyyy" name="date_range" type="text"
                                    value="{$from_date} - {$to_date}" placeholder="From date" />
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
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>

                                <th class="td-c">
                                    Approved date
                                </th>
                                <th class="td-c">
                                    {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th class="td-c">
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Status
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$requestlist key=k item=v}
                                <tr>
                                    <td class="td-c">
                                        {{$v.update_date}|date_format:"%Y-%m-%d"}
                                    </td>
                                    <td>
                                        {$v.customer_code}
                                    </td>
                                    <td>
                                        {$v.name}
                                    </td>
                                    <td>
                                        {$v.email}
                                    </td>

                                    <td>
                                        {$v.mobile}
                                    </td>
                                    <td class="td-c">
                                        {if $v.status==1}
                                            <span class="label label-sm label-success">Approved</span>
                                        {else}
                                            <span class="label label-sm label-danger">Rejected</span>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        <tbody>

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