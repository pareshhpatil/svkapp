
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
                    <strong></strong> Your payment has been successfully processed by our banking partner. An email receipt has been sent by Swipez with these details on your registered email ID.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" class="form-horizontal form-row-sepe">
                            <div >
                                <div class="row invoice-logo">
                                    <div class="col-xs-6 invoice-logo-space">
                                        {if $response.logo}<img src="{$response.logo}" class="img-responsive templatelogo"  alt=""/>{/if}
                                    </div>
                                    <div class="col-xs-6">
                                        <p class="font-blue-madison">
                                            
                                        <h3 class="pull-right no-margin">Transaction Receipt</h3> 
                                        </p>
                                    </div>
                                </div>
                                <hr/>
                            </div>

                            <h3 class="font-blue-madison">Thank you</h3>

                            <p>
                                Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.
                            </p>

                            <div class="portlet ">
                                <div class="portlet-body">
                                    <table class="table table-hover">

                                        {if $response.customer_code!=''}
                                            <tr>
                                                <td>
                                                    Customer code
                                                </td>
                                                <td>
                                                    {$response.customer_code}
                                                </td>
                                            </tr>
                                        {/if}
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
                                                {if $response.BillingEmail=='unknown@swipez.in'}
                                                    -
                                                {else} 
                                                    {$response.BillingEmail} 
                                                {/if}
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
                                        <tr>
                                            <td>
                                                Payment Ref Number
                                            </td>
                                            <td>
                                                {$response.MerchantRefNo}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Transaction Ref Number
                                            </td>
                                            <td>
                                                {$response.TransactionID}
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
                                            {$response.currency_icon} {$response.Amount|string_format:"%.2f"}/-
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
                                        {if $response.discount>0}
                                            <tr>
                                                <td>
                                                    Coupon Discount
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
                            <div class="row">
                                <div class="col-md-12">
                                    {if {$coupon_link} !=null}   
                                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="{$coupon_link}">
                                            Get Coupons
                                        </a>
                                    {/if}
                                    {if {$invoice_link} !=null}       
                                        <a class="btn blue hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download/{$invoice_link}">
                                            Download Invoice as PDF 
                                        </a>
                                    {/if}
                                </div>
                            </div>
                            {if $hide_footer!=true}
                                {if {$isGuest} =='1'}              
                                    <hr/>
                                    <p>Track your payments by registering on  <a href="/patron/register">Swipez - Register.</a> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
                                    <hr/>
                                {/if}
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
</div>
<!-- END CONTENT -->
</div>

