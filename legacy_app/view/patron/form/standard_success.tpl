<div class="page-content">
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin" style="min-height: 700px;">
        {if {$isGuest} =='1'}
            <div class="col-md-3"></div>
            <div class="col-md-6">
            {else}
                <div class="col-md-2"></div>
                <div class="col-md-8">
                {/if}
                <br>
                <br>
                <div class="portlet " id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            Complete the following steps
                        </div>
                        <div class="tools hidden-xs">

                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form action="#" class="form-horizontal" id="submit_form" method="POST">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number">
                                                    1 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Update Amazon Seller Central </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number">
                                                    2 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Merchant information </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab3" data-toggle="tab" class="step active">
                                                <span class="number">
                                                    3 </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Receipt </span>
                                            </a>
                                        </li>

                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="tab-pane active" id="tab1">
                                            {$step1_content|replace:'__TRANSACTION_ID__':$response.TransactionID}
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            {$step2_content=$step2_content|replace:'__MERCHANT_NAME__':$response.merchant_name}
                                            {$step2_content=$step2_content|replace:'__MERCHANT_EMAIL__':$response.merchant_email}
                                            {$step2_content=$step2_content|replace:'__MERCHANT_MOBILE__':$response.merchant_mobile}
                                            {$step2_content}
                                        </div>
                                        <div class="tab-pane" id="tab3">
                                            <div class="portlet">
                                                <div class="portlet-body form">
                                                    <form action="#" class="form-horizontal form-row-sepe">


                                                        <div class="portlet ">
                                                            <div class="portlet-body">
                                                                <table class="table table-hover">
                                                                    <tr>
                                                                        <td>
                                                                            Payment Ref Number
                                                                        </td>
                                                                        <td>
                                                                            {$response.TransactionID}
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
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="javascript:;" class="btn default button-previous">
                                                <i class="m-icon-swapleft"></i> Back </a>
                                            <a href="javascript:;"  class="btn blue button-next">
                                                Continue <i class="m-icon-swapright m-icon-white"></i>
                                                {if {$invoice_link} !=null}   
                                                    <a href="/patron/paymentrequest/download/{$invoice_link}"  class="btn green button-submit">
                                                        Download Invoice <i class="m-icon-swapdown m-icon-white"></i>
                                                    </a>
                                                {else}
                                                    <a class="button-submit"></a>
                                                {/if}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr/>
                <p> <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
            </div>
        </div>
        <!-- END PAGE CONTENT-->
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
    <div class="modal modal-full fade" id="screenshot" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Screen shot</h4>
                </div>
                <div class="modal-body">
                    <img style="max-width: 100%;" src="/assets/admin/pages/media/invoice/amazon-screenshot.png">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>