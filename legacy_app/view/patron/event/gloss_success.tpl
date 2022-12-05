<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong> Your payment has been successfully processed by our banking partner. An email
                receipt has been sent by Swipez with these details on your registered email ID.
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="#" class="form-horizontal form-row-sepe">
                        <div>
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="{if $response.logo}{$response.logo}{else}/assets/admin/layout/img/logo.png{/if}"
                                        class="img-responsive" style="max-height: 80px !important;max-width: 200px;"
                                        alt="" />
                                </div>
                                <div class="col-xs-6">
                                    <p class="font-blue-madison">
                                        {$response.merchant_name}
                                    <h3 class="pull-right no-margin">Transaction Receipt</h3>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="font-blue-madison">Thank you</h3>

                        <p>
                            Your payment is successful towards your purchase of the Universal Bare Acts (by LexisNexis)
                            .
                        </p>
                        <p>
                            We will send you the PDF attachment you ordered for into your registered mail ID within the
                            current business hours ( 9 am - 7 pm).
                            Incase you have order for this post 7pm, you will receive this by the start of the next
                            business hour.
                        </p>

                        <div class="portlet ">
                            <div class="portlet-body">
                                <table class="table table-condensed">
                                    <tr>
                                        <td>
                                            Customer code
                                        </td>
                                        <td>
                                            {$response.customer_code}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            Patron Name
                                        </td>
                                        <td>
                                            {$response.BillingName}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            Patron Email ID
                                        </td>
                                        <td>
                                            {$response.BillingEmail}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Towards
                                        </td>
                                        <td>
                                            {$response.merchant_name}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Ref Number
                                        </td>
                                        <td>
                                            {$response.TransactionID}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Transaction Ref Number
                                        </td>
                                        <td>
                                            {$response.MerchantRefNo}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Date & Time
                                        </td>
                                        <td>
                                            {$response.DateCreated}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Amount
                                        </td>
                                        <td>
                                            {$response.Amount|string_format:"%.2f"}/-
                                        </td>
                                    </tr>
                                    {if $coupon_code!=''}
                                        <tr>
                                            <td>
                                                Coupon code applied
                                            </td>
                                            <td>
                                                {$coupon_code}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $response.discount>0}
                                        <tr>
                                            <td>
                                                Coupon discount
                                            </td>
                                            <td>
                                                {$response.discount}/-
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td>
                                            Mode of Payment
                                        </td>
                                        <td>
                                            {$response.payment_mode}
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        </div>
                        {if !empty($attendee_details)}
                            <div class="portlet ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Booking Details
                                    </div>
                                </div>
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
                                                    <b> Date: </b>{$attendee_details.0.start_date}
                                                </td>
                                            </tr>
                                            {if $attendee_details.0.venue!=''}
                                                <tr>
                                                    <td>
                                                        <b> Venue: </b>{$attendee_details.0.venue}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $response.narrative!=''}
                                                <tr>
                                                    <td>
                                                        <b> Narrative: </b>{$response.narrative}
                                                    </td>
                                                </tr>
                                            {/if}
                                        </tbody>
                                    </table>
                                    <br>
                                    <table class="table table-condensed table-bordered "
                                        style="width: 70%;margin-bottom: 0px;">
                                        <tr style="font-weight: bold;">
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 75%;">Details</th>
                                            <th style="width: 15%;">Quantity</th>
                                        </tr>
                                    </table>
                                    <table class="table table-condensed table-bordered "
                                        style="width: 70%;margin-bottom: 0px;">
                                        <tr>
                                            <td style="width: 10%;">1</td>
                                            <td style="width: 75%;">{$attendee_details.0.package_name}</td>
                                            <td style="width: 15%;">{$attendee_details.0.total_qty}</td>
                                        </tr>
                                    </table>
                                    <table class="table table-condensed table-hover" style="margin-bottom: 0px;width: 40%;">

                                        {assign var=id value=1}
                                        {foreach from=$attendee_details item=v}


                                            {if $v.package_id!=$package_name}
                                                {$package_name=$v.package_id}
                                                {$id=$id+1}
                                            </table>
                                            <table class="table table-condensed table-bordered "
                                                style="width: 70%;margin-bottom: 0px;">
                                                <tr>
                                                    <td style="width: 10%;">{$id}</td>
                                                    <td style="width: 75%;">{$v.package_name}</td>
                                                    <td style="width: 15%;">{$v.total_qty}</td>
                                                </tr>
                                            </table>
                                            <table class="table table-condensed table-bordered"
                                                style="margin-bottom: 0px;width: 40%;">
                                            {/if}
                                            {if $attendee_details.0.capture_details==1}
                                                {if $v.package_id!=$package_id}
                                                    <tr>
                                                        <th><b>Attendee name</b></th>
                                                        {if $attendee_details.0.mobile_capture==1}
                                                            <th><b>Mobile</b></th>
                                                        {/if}
                                                        {if $attendee_details.0.age_capture==1}
                                                            <th><b>Age</b></th>
                                                        {/if}
                                                    </tr>
                                                {/if}
                                                {if $v.package_id!=$package_id}
                                                    {$package_id=$v.package_id}
                                                {/if}
                                                <tr>
                                                    <td>
                                                        {$v.name}
                                                    </td>
                                                    {if $attendee_details.0.mobile_capture==1}
                                                        <td>
                                                            {$v.mobile}
                                                        </td>
                                                    {/if}
                                                    {if $attendee_details.0.age_capture==1}
                                                        <td>
                                                            {if $v.age>0} {$v.age}{/if}
                                                        </td>
                                                    {/if}

                                                </tr>
                                            {/if}
                                        {/foreach}
                                    </table>
                                </div>
                            </div>
                        {/if}
                        {if !empty($booking_details)}
                            <div class="portlet ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Booking Details
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h4>{$booking_details.0.category_name}
                                                        {$booking_details.0.calendar_title}</h4>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b> Date: </b>{$booking_details.0.calendar_date}
                                                </td>
                                            </tr>

                                            {if $response.narrative!=''}
                                                <tr>
                                                    <td>
                                                        <b> Narrative: </b>{$response.narrative}
                                                    </td>
                                                </tr>
                                            {/if}
                                        </tbody>
                                    </table>
                                    <br>
                                    <table class="table table-condensed table-bordered "
                                        style="width: 70%;margin-bottom: 0px;">
                                        <tr style="font-weight: bold;">
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 60%;">Chapter Details</th>
                                            <th style="width: 10%;">Price</th>
                                        </tr>
                                        {$int=0}
                                        {foreach from=$booking_details item=v}
                                            {$int=$int+1}
                                            <tr>
                                                <td style="width: 10%;">{$int}</td>
                                                <td style="width: 60%;">{$v.slot}</td>
                                                <td style="width: 10%;">{$v.amount}</td>
                                            </tr>
                                        {/foreach}
                                    </table>

                                </div>
                            </div>
                        {/if}
                        {if !empty($c_c_detail)}
                            <div class="portlet ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        Booking Details
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-condensed table-bordered "
                                        style="width: 70%;margin-bottom: 0px;">
                                        {foreach from=$c_c_detail item=v}
                                            <tr>
                                                <td style="width: 40%;"><b>{$v.column_name}</b></td>
                                                <td style="width: 60%;">{$v.value}</td>
                                            </tr>
                                        {/foreach}
                                    </table>
                                    <br>
                                    <h5>For any queries related to cards please email <a
                                            href="#">aniruddha@puneeatouts.in</a></h5>
                                </div>


                            </div>

                        {/if}
                </div>
                <hr />
                <p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png"
                        class="img-responsive pull-right powerbyimg" alt="" /><span class="powerbytxt">powered by</span>
                </p>
                <hr />
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>