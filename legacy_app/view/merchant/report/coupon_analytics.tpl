
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        <p class="media-heading">{$haserrors}.</p>
                    </div>
                </div>
            {/if}

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <!-- BEGIN PORTLET-->

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">From date</label>
                            <input class="form-control form-control-inline  date-picker rpt-date"   autocomplete="off" data-date-format="{$session_date_format}"  name="from_date" type="text" value="{$from_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">To date</label>
                            <input class="form-control form-control-inline  date-picker rpt-date"  autocomplete="off" data-date-format="{$session_date_format}"  name="to_date" type="text" value="{$to_date}" placeholder="To date"/>

                        </div>
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>

                        </div>
                    </form>
                </div>
            </div>
            <!-- END PORTLET-->
        </div> 
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-small">
                        <thead>
                            <tr>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Transaction id
                                </th>
                                <th>
                                    Date
                                </th>
                               
                                <th>
                                    Type
                                </th>
                                <th>
                                {$customer_default_column.customer_code|default:'Customer code'}
                                </th>
                                <th>
                                {$customer_default_column.customer_name|default:'Contact person name'}
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <td>
                                        {$v.created_date}
                                    </td>
                                    <td>
                                        {$v.transaction_id}
                                    </td>
                                    <td>
                                        {$v.created_date}
                                    </td>
                                    
                                    <td>
                                        {$v.request_type}
                                    </td>
                                    <td>
                                        {$v.customer_code}
                                    </td>
                                    <td>
                                        {$v.customer_name}
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

