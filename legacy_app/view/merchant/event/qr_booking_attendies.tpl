</a>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">

            <form action="/m/{$url}/attendeeavailed/booking" method="post">
                {if !empty($booking_details)}
                    <div class="portlet ">
                        <div class="portlet-body">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h4>Name: {$receipt_info.patron_name}</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Booking date: </b>{$receipt_info.date|date_format:"%d-%b-%Y"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Payment mode : </b>{$receipt_info.payment_mode}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <div style="overflow: auto;">
                                <table class="table table-condensed  table-hover" style="margin-bottom: 0px;">
                                    {assign var=id value=1}
                                    <tr>
                                        <th><b>Category name</b></th>
                                        <th><b>Name</b></th>
                                        <th><b>Slot</b></th>

                                        <th style="min-width: 70px;"><b>Availed?</b></th>
                                    </tr>
                                    {$show_edit=0}
                                    {foreach from=$booking_details item=v}
                                        {if $v.is_availed==1}
                                            {$show_edit=1}
                                        {/if}
                                        <tr>
                                            <td>
                                                {$v.category_name}
                                            </td>
                                            <td>
                                                {$v.calendar_title}
                                            </td>

                                            <td>
                                                {$v.calendar_date|date_format:"%d-%b-%Y"} {$v.slot}
                                            </td>

                                            <td>
                                                {if $is_edit=='update' || $v.is_availed==0}
                                                    <input type="hidden" name="detail_id[]"
                                                        value="{$v.booking_transaction_detail_id}">
                                                    <label><input class="icheck" type="checkbox"
                                                            {if $is_edit=='update' && $v.is_availed==0} 
                                                            {else} checked
                                                            {/if}name="is_availed[]" value="{$v.booking_transaction_detail_id}">
                                                        Avail
                                                    </label>
                                                {else}
                                                    Availed
                                                {/if}
                                            </td>
                                        </tr>
                                        {$id=$id+1}
                                    {/foreach}
                                </table>
                            </div>
                        </div>
                    </div>
                {/if}
                <div class="row no-margin">
                    {if $is_available==0 && $is_edit!='update'}
                        <div class="alert alert-block alert-danger fade in">
                            <p>
                                All booking have been availed with this transaction.
                            </p>
                        </div>
                    {/if}
                    <div class="col-xs-12 invoice-block text-center">

                        <a href="/m/{$url}/qrcode" class="btn btn-sm yellow  margin-bottom-5 text-center">Re-Scan QR <i
                                class="fa fa-undo"></i></a>
                        {if $is_edit!='update' && $show_edit==1}
                            <a href="/m/{$url}/qrcodereceipt/{$link}/update"
                                class="btn btn-sm green  margin-bottom-5 text-center">Update info <i
                                    class="fa fa-edit"></i></a>
                        {/if}
                        {if $is_available==1 || $is_edit=='update'}
                            <input type="hidden" value="{$link}" name="transaction_id">
                            <button type="submit" class="btn btn-sm blue margin-bottom-5 text-center">Submit <i
                                    class="fa fa-check"></i></button>
                        {/if}
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>