<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">Sale date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange" autocomplete="off"
                            data-date-format="{$session_date_format}" name="date_range" type="text"
                                value="{$from_date} - {$to_date}" placeholder="From date" />
                        </div>
                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn blue" name="submit">Search</button>
                            <button type="submit" name="exportExcel" class="btn green" title="Download report in excel format">Excel export</button>
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
                                <th></th>
                                <th>
                                    Product name
                                </th>

                                <th>
                                    Sale date
                                </th>

                                <th>
                                    Price
                                </th>
                                <th>
                                    Quantity
                                </th>
                           </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                        {$v.product_name}
                                    </td>
                                    <td>
                                        {date('d/M/y', strtotime($v.sale_date))}
                                    </td>
                                    <td>
                                        {$v.price}
                                    </td>
                                    <td>
                                        {$v.quantity}
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