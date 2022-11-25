
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- END PAGE HEADER-->
        <div class="row">
            <!-- END SEARCH CONTENT-->
            <div class="col-md-12">

                <!-- BEGIN PORTLET-->
                <div class="portlet">
                    
                    <div class="portlet-body ">
                        <form class="form-inline" method="post" role="form">
                            <div class="form-group">
                                    <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                                </div>

                            <div class="form-group">
                                <select class="form-control  select2me" data-placeholder="{$customer_default_column.customer_name|default:'Customer name'}" name="customer_name">
                                    <option value=""></option>
                                    {foreach from=$customer_list item=v}
                                        {if {{$customer_selected}=={$v.name}}}
                                            <option selected value="{$v.name}" selected>{$v.name}</option>
                                        {else}
                                            <option value="{$v.name}">{$v.name}</option>
                                        {/if}

                                    {/foreach}
                                </select>
                            </div>

                            <button type="submit" class="btn  blue">Search</button>
                            
                        </form>

                    </div>
                </div>
                <br>
                <!-- END PORTLET-->
            </div>

            <!-- END SEARCH CONTENT-->
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">
                            <thead>
                                <tr>
                                    <th>
                                    {$customer_default_column.customer_name|default:'Customer name'}
                                    </th>
                                    <th>
                                        Invoice Balance
                                    </th>
                                    <th>
                                        Available credits
                                    </th>
                                    <th>
                                        Balance
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$reportlist item=v}
                                    <tr>
                                        <td>
                                            {$v.customer_name}
                                        </td>
                                        <td>
                                            {$v.invoice_balance} 
                                        </td>
                                        <td>
                                            00.00
                                        </td>
                                        <td>
                                            {$v.invoice_balance} 
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

