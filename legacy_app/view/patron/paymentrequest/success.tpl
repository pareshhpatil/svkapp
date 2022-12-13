<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        {if {$isGuest} =='1'}
            <div class="col-md-3"></div>
            <div class="col-md-6">
            {else}
                <div class="col-md-2"></div>
                <div class="col-md-8">
                {/if}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong></strong>{if $response.amount>0} Your payment has been successfully processed by our banking
                    partner. <br>{/if}An email receipt has been sent by Swipez with these details on your registered
                    email ID.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" class="form-horizontal form-row-sepe">
                            <div>
                                <div class="row invoice-logo">
                                    <div class="col-xs-6 invoice-logo-space">
                                        <img src="{if $logo!=''}{$logo}{else}/assets/admin/layout/img/logo.png{/if}"
                                            class="img-responsive templatelogo" style="max-height: 80px !important;"
                                            alt="" />
                                    </div>
                                    <div class="col-xs-6">
                                        <p class="font-blue-madison">
                                            {if $response.main_company_name!=''}
                                                <span style="font-size:11px; display:block;  margin-bottom:1px;"> (An
                                                    official franchisee of {$response.main_company_name})</span>
                                            {/if}
                                        <h3 class="pull-right no-margin">Transaction Receipt</h3>
                                        </p>
                                    </div>
                                </div>
                                <hr />
                            </div>

                            <h3 class="font-blue-madison">Thank you</h3>

                            <p>
                                Your Payment is successful. Please quote your receipt number for any queries relating to
                                this transaction in future. Please note that this receipt is valid subject to the
                                realisation of your payment.
                            </p>

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
                                                <td style="width: 75%;">{$attendee_details.0.package_name} <span
                                                        class="font-blue">{if $attendee_details.0.package_type==2}- Season
                                                        Ticket {else} -
                                                        {$attendee_details.0.start_date|date_format:"%d-%b-%Y"}{/if}</span>
                                                </td>
                                                <td style="width: 15%;">{$attendee_details.0.total_qty}</td>
                                            </tr>
                                        </table>
                                        <table class="table table-condensed table-hover"
                                            style="margin-bottom: 0px;width: 40%;">

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
                                                        <td style="width: 75%;">{$v.package_name} <span
                                                                class="font-blue">{if $v.package_type==2}- Season Ticket
                                                                {else} -
                                                                {$v.start_date|date_format:"%d-%b-%Y"}{/if}</span></td>
                                                        <td style="width: 15%;">{$v.total_qty}</td>
                                                    </tr>
                                                </table>
                                                <table class="table table-condensed table-bordered"
                                                    style="margin-bottom: 0px;width: 40%;">
                                                {/if}
                                                {if !empty($attendees_capture)}
                                                    {if $v.package_id!=$package_id}
                                                        {$aint=1}
                                                        <tr>
                                                            {foreach from=$attendees_capture item=vac}
                                                                <th><b>{$vac.column_name}</b></th>
                                                            {/foreach}
                                                        </tr>
                                                    {/if}
                                                    {if $v.package_id!=$package_id}
                                                        {$package_id=$v.package_id}
                                                    {/if}
                                                    <tr>
                                                        {foreach from=$attendees_capture item=vac}
                                                            {if $vac.type=='system'}
                                                                {if $vac.name=='name'}
                                                                    <td>{$v.cust_det.first_name} {$v.cust_det.last_name}</td>
                                                                {else}
                                                                    <td>{$v.cust_det.{$vac.name}}</td>
                                                                {/if}

                                                            {else}
                                                                <td>{$v.cust_value_det.{$vac.name}}</td>
                                                            {/if}
                                                        {/foreach}
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
                                                <th style="width: 30%;">Details</th>
                                                <th style="width: 30%;">Package</th>
                                                <th style="width: 10%;">Qty</th>
                                                {if $response.Amount>0}
                                                    <th style="width: 10%;">Price</th>
                                                    <th style="width: 10%;">Total price</th>
                                                {/if}
                                            </tr>
                                            {$int=0}
                                            {foreach from=$booking_details item=v}
                                                {$int=$int+1}
                                                <tr>
                                                    <td style="width: 10%;">{$int}</td>
                                                    <td style="width: 60%;">{$v.slot}</td>
                                                    <td style="width: 60%;">{$v.package_name} -
                                                        {$v.slot_title}</td>
                                                    <td style="width: 10%;">{$v.qty}</td>
                                                    {if $response.Amount>0}
                                                        <td style="width: 10%;">{$v.amount}</td>
                                                        <td style="width: 10%;">{$v.amount*$v.qty}</td>
                                                    {/if}
                                                </tr>
                                            {/foreach}
                                        </table>

                                    </div>
                                </div>
                            {/if}

                            <div class="portlet ">

                                <div class="portlet-body">
                                    <table class="table table-hover">
                                        {if $response.has_customized_payment_receipt==1}
                                            {if $response.custom_fields}
                                                {foreach from=$response.custom_fields item=val}
                                                    <tr>
                                                        <td>
                                                            {$val@key}
                                                        </td>

                                                        <td>{$val}</td>
                                                    </tr>
                                                {/foreach}
                                            {/if}
                                        {else}
                                            {if $response.customer_code!=''}
                                                <tr>
                                                    <td>
                                                        {$customer_default_column.customer_code|default:'Customer code'}
                                                    </td>
                                                    <td>
                                                        {$response.customer_code}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $response.payment_request_type==2}
                                                {foreach from=$payee_capture item=vpc}
                                                    <tr>
                                                        <td>
                                                            {$vpc.column_name}
                                                        </td>
                                                        {if $vpc.type=='system'}
                                                            {if $vpc.name=='name'}
                                                                <td>{$customer_details.cust_det.first_name}
                                                                    {$customer_details.cust_det.last_name}</td>
                                                            {else}
                                                                <td>{$customer_details.cust_det.{$vpc.name}}</td>
                                                            {/if}
                                                        {else}
                                                            <td>{$customer_details.cust_value_det.{$vpc.name}}</td>
                                                        {/if}
                                                    </tr>
                                                {/foreach}
                                            {else}
                                                <tr>
                                                    <td>
                                                        {$customer_default_column.customer_name|default:'Patron name'}
                                                    </td>
                                                    <td>
                                                        {$response.patron_name}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {$customer_default_column.email|default:'Patron Email ID'}
                                                    </td>
                                                    <td>
                                                        {$response.patron_email}
                                                    </td>

                                                </tr>
                                            {/if}
                                            <tr>
                                                <td>
                                                    Payment Towards
                                                </td>
                                                <td>
                                                    {$response.company_name}
                                                    {if $response.main_company_name!=''}
                                                        (An official franchisee of {$response.main_company_name})
                                                    {/if}
                                                </td>
                                            </tr>
                                            {if $response.invoice_number!=''}
                                                <tr>
                                                    <td>
                                                        Invoice number
                                                    </td>
                                                    <td>
                                                        {$response.invoice_number}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $response.ref_no!=''}
                                                <tr>
                                                    <td>
                                                        Payment Ref Number
                                                    </td>
                                                    <td>
                                                        {$response.ref_no}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $response.transaction_id}
                                                <tr>
                                                    <td>
                                                        Transaction Ref Number
                                                    </td>
                                                    <td>
                                                        {$response.transaction_id}
                                                    </td>
                                                </tr>
                                            {/if}
                                            <tr>
                                                <td>
                                                    Payment Date & Time
                                                </td>
                                                <td>
                                                    {$response.date}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Payment Amount
                                                </td>
                                                <td>
                                                    {$response.currency_icon} {$response.amount|number_format:2:".":","}/-
                                                </td>
                                            </tr>
                                            {if $response.deduct_amount>0}
                                                <tr>
                                                    <td>
                                                        Deduct {$response.deduct_text}
                                                    </td>
                                                    <td>
                                                        {$response.deduct_amount}/-
                                                    </td>
                                                </tr>
                                            {/if}
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
                                            {if $response.narrative!=''}
                                                <tr>
                                                    <td>
                                                        Description
                                                    </td>
                                                    <td>
                                                        {$response.narrative}
                                                    </td>
                                                </tr>
                                            {/if}
                                            {if $response.payment_mode!=''}
                                                <tr>
                                                    <td>
                                                        Mode of Payment
                                                    </td>
                                                    <td>
                                                        {$response.payment_mode}
                                                    </td>
                                                </tr>
                                            {/if}
                                        {/if}
                                    </table>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    {if {$invoice_link} !=null}
                                        <a class="btn blue hidden-print margin-bottom-5 pull-right"
                                            style="margin-right: 20px;"
                                            href="/patron/paymentrequest/download/{$invoice_link}">
                                            Download Invoice as PDF
                                        </a>
                                    {/if}
                                </div>
                            </div>

                            {if {$isGuest} =='1'}
                                <hr />
                                <p>Track your payments by registering on <a href="/patron/register">Swipez - Register.</a>
                                    <img src="/assets/admin/layout/img/logo.png"
                                        class="img-responsive pull-right powerbyimg" alt="" /><span
                                        class="powerbytxt">powered by</span>
                                </p>
                                <hr />
                            {/if}
                    </div>
                </div>
            </div>
            <!-- 
            <div class="col-md-2">
                <script type="text/javascript" language="javascript">
                    var aax_size = '160x600';
                    var aax_pubname = 'swipez-21';
                    var aax_src = '302';
                </script>
                <script type="text/javascript" language="javascript" src="https://c.amazon-adsystem.com/aax2/assoc.js"></script>
            </div>-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>