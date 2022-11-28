
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Package name
                                </th>
                                {if $occurence>1}
                                    <th class="td-c">
                                        Booking date
                                    </th>
                                {/if}
                                <th class="td-c">
                                    Price
                                </th>
                                <th class="td-c">
                                    Total Quantity
                                </th>
                                <th class="td-c">
                                    Booked Quantity
                                </th>
                                <th class="td-c">
                                    Available Quantity
                                </th>
                                <th class="td-c">
                                    Total amount
                                </th>
                                <th class="td-c">
                                    Sold Amount
                                </th>
                                <th class="td-c">
                                    Unpaid Amount
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$requestlist item=v}
                                {if $v.package_name != ''}
                                    <tr>
                                        <td class="td-c">
                                            {$v.package_name}{$v.package_id}
                                        </td>
                                        {if $occurence>1}
                                            <td class="td-c">
                                                {$v.date|date_format:"%d-%m-%Y"} {if $v.start_time!=$v.end_time}{$v.start_time|date_format:"%I:%M %p"}{/if}
                                            </td>
                                        {/if}
                                        <td class="td-c">
                                            {$v.price}
                                        </td>
                                        <td class="td-c">
                                        {if $v.total_qty==0}Unlimited{else}{$v.total_qty}{/if}
                                    </td>
                                    <td class="td-c">
                                        {$v.reserv_qty}
                                    </td>
                                    <td class="td-c">
                                    {if $v.total_qty==0}Unlimited{else}{$v.available_qty}{/if}
                                </td>

                                <td class="td-c">
                                    {$v.available_amount}
                                </td>
                                <td class="td-c">
                                    {$v.sold_amount}
                                </td>
                                <td class="td-c">
                                {if $v.total_qty==0}Unlimited{else}{$v.unpaid_amount}{/if}
                            </td>
                        </tr>
                    {/if}
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