<div class="page-content">
    <div class="row ">            
        <div class="col-md-2" ></div>
        <div class="col-md-8" >
            <div class="alert alert-success">
                <strong></strong> Your payment has been successfully processed by our banking partner. Receipt has been sent to your email id.

                <div class="row">
                    <div class="col-md-12">
                        {if {$transaction_link} !=null}   
                            <a target="_BLANK" class="btn green hidden-print margin-top-10" href="/patron/transaction/receipt/{$transaction_link}">
                                View Receipt
                            </a>
                        {/if}
                        {if {$invoice_link} !=null}       
                            <a class="btn blue hidden-print margin-top-10"  href="/patron/paymentrequest/download/{$invoice_link}">
                                Download Invoice
                            </a>
                        {/if}
                        {if {$court_link} !=null}       
                            <a class="btn blue hidden-print margin-top-10"  href="{$court_link}">
                                Book slots
                            </a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">            
        <div class="col-md-12" >
            <hr>
            <h4 class="form-title font-blue center"><b>Offers and Coupons brought to you by {$details.company_name}</b></h4>
            <hr>
            <div class="center" swipez-trans-key="{$transaction_key}" coupon-menu="false" offer-type="store" swipez-merchant-key="{$merchant_key}" id="swipez-offer">
            </div>
            <script type="text/javascript" src="https://www.swipez.in/coupons/api.js?v=5"></script>
        </div>
    </div>	
</div>
</div>
</form>
</div>

</div>	
<!-- END PAGE CONTENT-->