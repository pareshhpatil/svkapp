</a>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="#" class="form-horizontal form-row-sepe">
                        <div >
                            <div class="row invoice-logo">
                                <div class="col-xs-6 invoice-logo-space">
                                    <img src="https://www.swipez.in/assets/admin/layout/img/logo.png" class="img-responsive templatelogo" style="max-height: 80px !important;" alt=""/>
                                </div>
                                <div class="col-xs-6">
                                    <p class="font-blue-madison">
                                        
                                    <h3 class="pull-right no-margin">Transaction Receipt</h3> 
                                    </p>
                                </div>
                            </div>
                        </div>

                        <h3 class="font-blue-madison">Thank you</h3>

                        <p>
                            Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.
                        </p>

                        <div class="portlet ">
                            <div class="portlet-body">
                                <table class="table table-condensed">
                                    <tr>
                                        <td>
                                            Merchant Name
                                        </td>
                                        <td>
                                            {$info.company_name}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td>
                                            Merchant Email ID
                                        </td>
                                        <td>
                                                {$info.email_id}
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
                                    {if $info.invoice_number!=''}
                                        <tr>
                                            <td>
                                                Invoice number
                                            </td>
                                            <td>
                                                {$info.invoice_number}
                                            </td>
                                        </tr>
                                    {/if}
                                    {if $info.pg_ref_no}
                                        <tr>
                                            <td>
                                                Payment Ref Number
                                            </td>
                                            <td>
                                                {$info.pg_ref_no}
                                            </td>
                                        </tr>
                                    {/if}

                                    {if $info.package_transaction_id!=''}
                                        <tr>
                                            <td>
                                                Transaction Ref Number
                                            </td>
                                            <td>
                                                {$info.package_transaction_id}
                                            </td>
                                        </tr>
                                    {/if}

                                    {if $info.narrative!=''}
                                        <tr>
                                            <td>
                                                Description
                                            </td>
                                            <td>
                                                {$info.narrative}
                                            </td>
                                        </tr>
                                    {/if}
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
                                                Coupon discount
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


                </div>
                <hr/>
            </div>

        </div>
        <div class="row no-margin hidden-sm hidden-xs">
            <div class="col-xs-12 invoice-block text-center">
                <a class="btn btn-lg blue hidden-print margin-bottom-5 text-center" onclick="javascript:window.print();">Print <i class="fa fa-print"></i></a>
            </div>
        </div>
    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>