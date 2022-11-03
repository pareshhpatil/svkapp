
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Package bought</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    
        <!-- BEGIN SEARCH CONTENT-->
`
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12" >
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="table-ellipsis-small">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Transaction ID
                                    </th>
                                    <th class="td-c">
                                        Package name
                                    </th>
                                    <th class="td-c">
                                        License bought
                                    </th>
                                    <th class="td-c">
                                        License available
                                    </th>

                                    <th class="td-c">
                                        Start date
                                    </th>
                                    <th class="td-c">
                                        End date
                                    </th>
                                    <th class="td-c">
                                        Amount
                                    </th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {$int=0}
                                {foreach from=$requestlist item=v}
                                    {$int=$int+1}
                                    <tr>
                                        <td class="td-c">
                                            {$v.package_transaction_id}
                                        </td>
                                        <td>
                                            {$v.package_name}
                                        </td>
                                        <td class="td-c">
                                            {$v.license_bought}
                                        </td>
                                        <td class="td-c">
                                            {$v.license_available}
                                        </td>

                                        <td class="td-c">
                                            {{$v.start_date}|date_format:"%Y-%m-%d"}
                                        </td>
                                        <td class="td-c">
                                            {{$v.end_date}|date_format:"%Y-%m-%d"}
                                        </td>
                                        <td class="td-c">
                                            {$v.amount}
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

