
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-3"></div>
        <div class="col-md-6">

            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong> Thank you for choosing Swipez. We have received your payment and our executive will get in touch with you to help you with next steps. In the meanwhile, if you have any questions please reach to us on support@swipez.in.
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="#" class="form-horizontal form-row-sepe">
                        <div >
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="https://www.swipez.in/images/logo.png" class="img-responsive templatelogo"  alt=""/>
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
                                    <tr>
                                        <td>
                                            Merchant Name
                                        </td>
                                        <td>
                                            {$detail.name}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            Email ID
                                        </td>
                                        <td>
                                            {$detail.email} 
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Towards
                                        </td>
                                        <td>
                                            Swipez
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Transaction Ref Number
                                        </td>
                                        <td>
                                            {$info.package_transaction_id}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Ref Number
                                        </td>
                                        <td>
                                            {$info.pg_ref_1}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Date & Time
                                        </td>
                                        <td>
                                            {$info.created_date}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Amount
                                        </td>
                                        <td>
                                            {$info.amount}/-
                                        </td>
                                    </tr>
                                    {if $info.discount>0}
                                        <tr>
                                            <td>
                                                Coupon Discount
                                            </td>
                                            <td>
                                                {$info.discount}/-
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td>
                                            Mode of Payment
                                        </td>
                                        <td>
                                            {$info.payment_mode}
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <hr/>
                        <hr/>
                </div>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>