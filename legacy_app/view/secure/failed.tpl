
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                Payment attempt failed
            </div>
            <div class="">
                <div class="portlet-body">
                    <div class="portlet ">
                        <div class="portlet-title">
                            <div class="caption">
                                Payment details
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-condensed table-bordered p-lg-font">
                                <tr>
                                    <td >
                                        Transaction id
                                    </td>
                                    <td>
                                        {$transaction_id}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Status received from payment gateway
                                    </td>
                                    <td>
                                    {if $msg==''}No status received at the moment{else}{$msg}{/if}
                                </td>
                            </tr>
                        </table>
                        {if $repath!=''}
                            <div class="center">
                                <a href="{$repath}" class="btn btn-sm blue">Retry your payment</a>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Funds debited?
                        </div>
                    </div>
                    <div class="portlet-body">
                        <p class="p-lg-font">
                            In case funds have been debited from your bank account. Please allow 15-20 minutes for the payment system to automatically update your payment status.
                            You will automatically receive an email and SMS if your payment has been updated successfully.
                            If you are still facing an issue after 15-20 minutes, please reach to us on support@swipez.in with the transaction id : {$transaction_id}
                        </p>
                    </div>
                </div>
            </div>
            <hr/>


        </div>

    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>