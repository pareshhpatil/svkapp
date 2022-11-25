
<div class="page-content">
    <br>
    <!-- BEGIN PAGE HEADER-->
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
                {if isset($response.mailer_content)}
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <h3 class="font-blue-madison">Important Information</h3>
                            {$response.mailer_content=$response.mailer_content|replace:'<tr><td>':'<p>'}
                            {$response.mailer_content=$response.mailer_content|replace:'</td></tr>':'</p>'}
                            {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_NAME__':$response.merchant_name}
                            {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_EMAIL__':$response.merchant_email}
                            {$response.mailer_content=$response.mailer_content|replace:'__MERCHANT_MOBILE__':$response.merchant_mobile}
                            {$response.mailer_content}

                        </div>
                    </div>
                {/if}
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" class="form-horizontal form-row-sepe">
                            <h3 class="font-blue-madison">Thank you</h3>

                            <p>
                                Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.
                            </p>

                            <div class="portlet "
                                 {if isset($receipt_background_color)}
                                     {if $receipt_background_color!=''}
                                         style="border-color: {$receipt_background_color} !important;"
                                     {/if}
                                 {/if}
                                 >
                                <div class="portlet-title" {if isset($receipt_background_color)}
                                     {if $receipt_background_color!=''}
                                         style="background-color: {$receipt_background_color} !important;"
                                     {/if}
                                     {/if}>
                                         <div class="caption">
                                             Transaction Details
                                         </div>
                                     </div>
                                     <div class="portlet-body">
                                         <table class="table table-hover">
                                             <tr>
                                                 <td>
                                                     Payment Ref Number
                                                 </td>
                                                 <td>
                                                     <span style="background-color: turquoise;"><b><abc1>{$response.TransactionID}</abc1></b></span>

                                                     <a href="javascript:;" class="btn btn-xs blue bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc1"><i class="fa fa-clipboard"></i> Copy</a>
                                                 </td>
                                             </tr>
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
                                                     Patron Mobile no
                                                 </td>
                                                 <td>
                                                     {$response.BillingMobile} 
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
                                                     Bank Ref Number
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
                                                     {$response.Amount}/-
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
                                                         Purpose of Payment
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
                                {if !empty($response.form_details)}
                                    <div class="portlet "
                                         {if isset($receipt_background_color)}
                                             {if $receipt_background_color!=''}
                                                 style="border-color: {$receipt_background_color} !important;"
                                             {/if}
                                         {/if}
                                         >
                                        <div class="portlet-title" {if isset($receipt_background_color)}
                                             {if $receipt_background_color!=''}
                                                 style="background-color: {$receipt_background_color} !important;"
                                             {/if}
                                        {/if}>
                                        <div class="caption">
                                            Form Details
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-hover">
                                            {foreach from=$response.form_details item=v}
                                                {if $v.label!='' && $v.value!=''}
                                                    <tr>
                                                        <td>
                                                            {$v.label}
                                                        </td>
                                                        <td>
                                                            {$v.value}
                                                        </td>
                                                    </tr>
                                                {/if}
                                            {/foreach}
                                        </table>
                                    </div>
                                </div>
                                {/if}
                                    {if {$invoice_link} !=null}       
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a class="btn blue hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/patron/paymentrequest/download/{$invoice_link}">
                                                    Download Invoice as PDF 
                                                </a>
                                            </div>
                                        </div>
                                    {/if}
                                    {if {$isGuest} =='1'}              
                                        <hr/>
                                        <p>Track your payments by registering on  <a href="/patron/register">Swipez - Register.</a> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
                                        <hr/>
                                    {/if}
                            </div>
                        </div>
                    </div>	
                    <!-- END PAGE CONTENT-->

                </div>
            </div>
        </div>
        <!-- END CONTENT -->
    </div>

    <div class="modal modal-full fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Screen shot</h4>
                </div>
                <div class="modal-body">
                    <img style="max-width: 100%;" src="/assets/admin/pages/media/invoice/amazon-flow.png">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

