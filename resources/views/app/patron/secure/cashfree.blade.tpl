<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        {if isset($logo)}
            <div class="page-logo">
                <img src="{{$logo}}" style="max-height: 50px;" alt="{{$company_name}}" class="logo-default" />
            </div>
        {else}
            <div class="page-logo" style="width: auto;">
                <h2>{{$company_name}}</h2>
            </div>
        {/if}


    </div>
    <!-- END HEADER INNER -->
</div>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <br>
    <h3 class="page-title">&nbsp;</h3>
    <br>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            <div class="" id="error"
                style="text-align: left;display: none;padding: 15px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;">
            </div>

            <div class="portlet light bordered" style="max-width: 900px;text-align: left;">
                <div class="portlet-title">
                    <div class="col-md-9">
                        <div class="caption font-blue" style="">
                            <span class="caption-subject bold uppercase">
                                <h2>{{$company_name}}</h2>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="caption font-blue">
                            <h2 id="grandtotal"><i class="fa fa-inr fa-large"></i> {{$amount|string_format:"%.2f"}}/-
                            </h2>
                            <h5 id="conv" style="font-size: 14px;"></h5>
                        </div>
                    </div>

                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <!-- Start profile details -->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="payment-div" style="max-width: 900px;text-align: left;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End profile details -->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<!-- Paste below code base before the closing tag(</body>) of body element -->
<script src="https://www.cashfree.com/assets/cashfree.sdk.v1.2.js" type="text/javascript"></script>
<script type="text/javascript">
    (function () {ldelim}
    var data = {ldelim}{rdelim};
    data.orderId = "{{$data.orderId}}";
    data.orderAmount = "{{$data.orderAmount}}";
    data.orderCurrency = "{{$data.orderCurrency}}";
    data.customerName = "{{$data.customerName}}";
    data.customerPhone = "{{$data.customerPhone}}";
    data.customerEmail = "{{$data.customerEmail}}";
    {if $data.vendor!=''}
        data.vendorSplit = "{{$data.vendor}}";
    {/if}
    data.returnUrl = "{{$data.returnUrl}}";
    data.appId = "{{$data.appId}}";
    {if isset($data.pc)}
        data.pc = "{{$data.pc}}";
    {/if}
    data.paymentToken = "{{$data.signature}}";

    var callback = function (event) {ldelim}
    var eventName = event.name;
    switch (eventName) {ldelim}
    case "PAYMENT_REQUEST":
    console.log(event.message);
    break;
    default:
    console.log(event.message);
    {rdelim}
    ;
    {rdelim}

    var config = {ldelim}{rdelim};
    config.layout = {ldelim}view: "inline", container: "payment-div"{rdelim};
    config.mode = "{{$cashfreeMode}}"; //use PROD when you go live
    var response = CashFree.init(config);
    if (response.status == "OK") {ldelim}
    CashFree.makePayment(data, callback);
    {rdelim} else {ldelim}
    //handle error
    console.log(response.message);
    {rdelim}

    {rdelim})();
</script>