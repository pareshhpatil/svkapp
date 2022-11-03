</a>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">

            <form action="/merchant/event/attendeeavailed" method="post">
                {if !empty($attendee_details)}
                    <div class="portlet ">
                        {$package_name=$attendee_details.0.package_id}
                        <div class="portlet-body">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h4>{$attendee_details.0.event_name}</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>  Date: </b>{$attendee_details.0.start_date}
                                        </td>
                                    </tr>
                                    {if $attendee_details.0.venue!=''}
                                        <tr>
                                            <td>
                                                <b>  Venue: </b>{$attendee_details.0.venue}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $response.narrative!=''}
                                        <tr>
                                            <td>
                                                <b>  Narrative: </b>{$response.narrative}
                                            </td>
                                        </tr>
                                    {/if}
                                </tbody>
                            </table>
                            <br>
                            <div style="overflow: auto;">
                                <table class="table table-condensed  table-hover" style="margin-bottom: 0px;">
                                    {assign var=id value=1}
                                    <tr>
                                        <th><b>Package name</b></th>
                                        <th><b>Attendee name</b></th>
                                            {if $attendee_details.0.mobile_capture==1}
                                            <th><b>Mobile</b></th>
                                            {/if}
                                            {if $attendee_details.0.age_capture==1}
                                            <th><b>Age</b></th>
                                            {/if}
                                        <th style="min-width: 70px;"><b>Availed?</b></th>
                                    </tr>
                                    {$show_edit=0}
                                    {foreach from=$attendee_details item=v}
                                        {if $v.is_availed==1}
                                            {$show_edit=1}
                                        {/if}
                                        <tr>
                                            <td>
                                                {$v.package_name} <span class="font-blue">{if $v.package_type==2}- Season Ticket {else} - {$v.start_date|date_format:"%d-%b-%Y"}{/if}</span>
                                            </td>
                                            <td >
                                                {if $v.name!=''} {$v.name} {else} Attendee {$id}{/if}
                                            </td>
                                            {if $attendee_details.0.mobile_capture==1}
                                                <td >
                                                    {$v.mobile} 
                                                </td>
                                            {/if}
                                            {if $attendee_details.0.age_capture==1}
                                                <td >
                                                    {if $v.age>0} {$v.age}{/if} 
                                                </td>
                                            {/if}
                                            <td>
                                                {if $is_edit=='update' || $v.is_availed==0}
                                                    <input type="hidden" name="detail_id[]" value="{$v.detail_id}">
                                                    <label><input class="icheck" type="checkbox" {if $is_edit=='update' && $v.is_availed==0} {else} checked {/if}name="is_availed[]" value="{$v.detail_id}">
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

                        <a href="/merchant/event/qrcode" class="btn btn-sm yellow  margin-bottom-5 text-center">Re-Scan QR <i class="fa fa-undo"></i></a>
                            {if $is_edit!='update' && $show_edit==1}
                            <a href="/merchant/event/qrcodereceipt/{$link}/update" class="btn btn-sm green  margin-bottom-5 text-center">Update info <i class="fa fa-edit"></i></a>
                            {/if}
                            {if $is_available==1 || $is_edit=='update'}
                            <input type="hidden" value="{$link}" name="transaction_id">
                            <button type="submit" class="btn btn-sm blue margin-bottom-5 text-center">Submit <i class="fa fa-check"></i></button>
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