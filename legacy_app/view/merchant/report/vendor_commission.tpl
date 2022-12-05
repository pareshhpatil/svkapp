<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
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
                            <label class="help-block">Date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange" autocomplete="off"
                            data-date-format="{$session_date_format}" name="date_range" type="text"
                                value="{$from_date} - {$to_date}" placeholder="From date" />
                        </div>
                        <div class="form-group">
                            <label class="help-block">Payment status</label>
                            <select class="form-control " data-placeholder="Status" name="status">
                                <option value="">Select status</option>
                                <option {if $status==1} selected {/if} value="1">Paid</option>
                                <option {if $status==2} selected {/if} value="2">Un-Paid</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green">Excel export</button>

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
                    <table class="table table-striped table-bordered table-hover" id="table-sum-ellipsis-small">
                        <thead>
                            <tr>

                                <th>
                                    Id
                                </th>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Vendor code
                                </th>
                                <th>
                                    Name
                                </th>
                                
                                <th>
                                    Invoice No.
                                </th>
                                <th>
                                    Bill date
                                </th>
                                <th>
                                    Invoice amount
                                </th>
                                <th>
                                    Commission amount
                                </th>
                                <th>
                                    Merchant margin
                                </th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="9" style=""></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>

                                    <td>
                                        {$v.id}
                                    </td>
                                    <td>
                                        {$v.date}
                                    </td>
                                   
                                    <td>
                                        {$v.vendor_code}
                                    </td>
                                    <td>
                                        {$v.vendor_name}
                                    </td>
                                    <td>
                                        {$v.invoice_number}
                                    </td>
                                    <td>
                                        {$v.bill_date}
                                    </td>
                                    <td>
                                        {$v.grand_total}
                                    </td>
                                    <td>
                                        {$v.amount}
                                    </td>
                                    <td>
                                        {{$v.grand_total-$v.amount}|string_format:"%.2f"}
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