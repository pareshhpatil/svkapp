
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->


    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-block alert-success fade in">
                {if $notify_patron==1}
                    {if $invoice_type==1}
                        <h4 class="alert-heading">Payment request sent!</h4>
                        <p>
                            Your request for payment has been sent to your customer. You will receive a notification as soon as your customer makes the payment.
                        </p>
                    {else}
                        <h4 class="alert-heading">Estimate sent!</h4>
                        <p>
                            Your estimate has been sent to your customer. You will receive a notification as soon as your customer makes the payment, along with the final invoice copy.
                        </p>
                    {/if}
                {else}
                    {if $invoice_type==1}
                        <h4 class="alert-heading">Invoice saved!</h4>
                        <p>
                            Your invoice has been saved and will appear in the Requests and Reports tabs.
                        </p>
                    {else}
                        <h4 class="alert-heading">Estimate saved!</h4>
                        <p>
                            Your estimate has been saved and will appear in the Requests and Reports tabs.
                        </p>
                    {/if}

                {/if} 
                <p>
                    <a class="btn blue input-sm" href="/merchant/invoice/update/{$link}">
                         Update request </a>
                    <a class="btn blue-madison input-sm" href="/merchant/paymentrequest/view/{$link}">
                         View request </a>
                    <a class="btn blue input-sm" href="/merchant/dashboard">
                         Dashboard </a>
                    <a class="btn green input-sm" target="_BLANk" href="https://api.whatsapp.com/send?text={$server_name}/patron/paymentrequest/view/{$link}{$mobile}">
                        <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                </p>
            </div>
        </div>
    </div>
</div>
</div>