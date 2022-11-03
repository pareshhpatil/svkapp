
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Transaction Receipt</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <strong>Success!</strong> Your offline payment has been successfully saved.
            </div>

        </div>

        <div class="col-md-2"></div>
        <div class="col-md-8">

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="#" class="form-horizontal form-row-sepe">
                        <div >
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="/assets/admin/layout/img/logo.png" class="img-responsive  templatelogo" alt=""/>
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
                                            Patron Name
                                        </td>
                                        <td>
                                            {$response.patron_name}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Patron Email ID
                                        </td>
                                        <td>
                                            {if $response.patron_email!=''}
                                                {$response.patron_email}
                                            {else}
                                                -
                                            {/if}
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
                                            Transaction Ref Number
                                        </td>
                                        <td>
                                            {$response.transaction_id}
                                        </td>
                                    </tr>
                                    {if $response.type==1}
                                        <tr>
                                            <td>
                                                Bank Ref Number
                                            </td>
                                            <td>
                                                {$response.transaction_no}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Bank Name
                                            </td>
                                            <td>
                                                {$response.bank_name}
                                            </td>
                                        </tr>
                                    {elseif $response.type==2}
                                        <tr>
                                            <td>
                                                Cheque Number
                                            </td>
                                            <td>
                                                {$response.cheque_no}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Bank Name
                                            </td>
                                            <td>
                                                {$response.bank_name}
                                            </td>
                                        </tr>
                                    {else}
                                        {if $response.cash_paid_to!=''}
                                            <tr>
                                                <td>
                                                    Cash Paid To
                                                </td>
                                                <td>
                                                    {$response.cash_paid_to}
                                                </td>
                                            </tr>
                                        {/if}
                                    {/if}

                                    <tr>
                                        <td>
                                            Payment Date & Time
                                        </td>
                                        <td>
                                            {$response.create_date}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Payment Amount
                                        </td>
                                        <td>
                                            {$response.amount}/-
                                        </td>
                                    </tr>
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
                        <hr/>
                        <p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
                        <hr/>
                </div>

            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>