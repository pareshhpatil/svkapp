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
                <strong></strong>Your E-Mandate registration is successfully completed.<br>
                We will confirm the e-mandate registration with the bank within 2 business days.
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="#" class="form-horizontal form-row-sepe">
                        <h3 class="font-blue-madison">Thank you</h3>
                        <p>
                            Your E-Mandate registration is successfully completed. Please quote your reference
                            number for any queries relating to this transaction in future. Please note that this
                            receipt is valid subject to the realisation of your payment.
                        </p>
                        <div class="portlet ">

                            <div class="portlet-body">
                                <table class="table table-hover">
                                    <tr>
                                        <td>
                                            Customer Name
                                        </td>
                                        <td>
                                            {$subscription.customer_name}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Email ID
                                        </td>
                                        <td>
                                            {$subscription.email_id}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            Mobile No.
                                        </td>
                                        <td>
                                            {$subscription.mobile}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Towards
                                        </td>
                                        <td>
                                            {$response.company_name}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            Payment Ref Number
                                        </td>
                                        <td>
                                            {$response.cf_referenceId}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Narrative
                                        </td>
                                        <td>
                                            {$response.cf_message}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Date & Time
                                        </td>
                                        <td>
                                            {$response.date}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Subscription amount
                                        </td>
                                        <td>
                                            {$subscription.amount}/-
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Start date
                                        </td>
                                        <td>
                                            {$subscription.start_date|date_format:"%d-%m-%Y"}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            End date
                                        </td>
                                        <td>
                                            {$subscription.expiry_date|date_format:"%d-%m-%Y"}
                                        </td>
                                    </tr>


                                </table>
                            </div>
                        </div>




                        {if {$isGuest} =='1'}
                            <hr />
                            <p>Track your payments by registering on <a href="/patron/register">Swipez - Register.</a>
                                <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg"
                                    alt="" /><span class="powerbytxt">powered by</span>
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