<style>
    .multiselect-container>li>a>label.checkbox {
        width: 220px;
        padding-left: 20px;
    }

    .btn-group>.btn:first-child {
        width: 220px;
    }
</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
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
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="/merchant/bookings/managecalendar/slot/{$link}">Manage Time Slots</a>
                    </li>
                    <li>
                        <a href="/merchant/bookings/managecalendar/holiday/{$link}">Manage Holidays </a>
                    </li>
                    <li class="active">
                        <a href="/merchant/bookings/managecalendar/notification/{$link}">Manage Notifications & Data
                        </a>
                    </li>
                </ul>

            </div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <form class="form-horizontal" action="/merchant/bookings/updatenotification" method="post" role="form">
                <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-4">Capture details :</label>
                        <div class="col-md-7">
                            <select id="column_name" name="capture_detail[]" class="form-control" multiple>
                                <option selected disabled="" value="name">Name</option>
                                <option selected disabled="" value="email">Email</option>
                                <option selected disabled="" value="mobile">Mobile</option>
                                <option {if in_array('customer_code',$detail.capture)}selected{/if}
                                    value="customer_code">Customer code</option>
                                <option {if in_array('address',$detail.capture)}selected{/if} value="address">Address
                                </option>
                                <option {if in_array('city',$detail.capture)}selected{/if} value="city">City</option>
                                <option {if in_array('state',$detail.capture)}selected{/if} value="state">State</option>
                                <option {if in_array('zipcode',$detail.capture)}selected{/if} value="zipcode">Zipcode
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Cancellation Type :</label>
                        <div class="col-md-7">
                            <select id="cancellation_type" name="cancellation_type"
                                class="form-control" onclick="cancellationTypeClicked(this.value)">
                                <option {if $detail.cancellation_type == 1}selected{/if} value="1">Not Allowed</option>
                                <option {if $detail.cancellation_type == 2}selected{/if} value="2">Refund</option>
                                <option {if $detail.cancellation_type == 3}selected{/if} value="3">Coupon</option>
                            </select>
                        </div>
                    </div>
                    <div id="cancellation_div" style="display:none;">
                        <div class="form-group">
                            <label class="control-label col-md-4">Cancellation days :</label>
                            <div class="col-md-7">
                                <input type="text" maxlength="500" value="{$detail.cancellation_days}" name="can_days"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Cancellation hours :</label>
                            <div class="col-md-7">
                                <input type="text" maxlength="500" value="{$detail.cancellation_hours}" name="can_hours"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Emails with comma seperated :</label>
                        <div class="col-md-7">
                            <input type="text" maxlength="500" value="{$detail.notification_email}"
                                name="notification_email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Mobiles with comma seperated :</label>
                        <div class="col-md-7">
                            <input type="text" maxlength="100" value="{$detail.notification_mobile}"
                                name="notification_mobile" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="help-block">&nbsp;</label>
                        <div class="col-md-4"></div>
                        <div class="col-md-7">
                            <input type="hidden" name="calendar_id" value="{$link}" />
                            <button type="submit" class=" btn blue">Save</button>
                        </div>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete holiday</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this holiday in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    var cancellation_value  = {$detail.cancellation_type};
    if (cancellation_value > 1) {
        document.getElementById("cancellation_div").style.display = "block";
    } else {
        document.getElementById("cancellation_div").style.display = "none";
    }
</script>