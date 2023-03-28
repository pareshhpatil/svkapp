@if ($payment_request_status!=3)
<div class="invoice mt-1" style="max-width: 1400px;">
    <div class="row no-margin">
        @if($document_url!='')
        <div class="row" id="document_div" style="display: none;">
            <button class="btn btn-default pull-right" onclick="showDocument(2);" style="margin-right: 15px"><i class="fa fa-close"></i></button>
            <div class="col-md-12">
                <div style="max-height: 700px;overflow: auto;">
                    @if(substr(strtolower($document_url), -3)=='pdf')
                    <iframe src="{{$document_url}}" frameborder="no" width="100%" height="500px">
                    </iframe>
                    @else
                    <img src="{{$document_url}}" style="width: 100%;max-width: 100%;">
                    @endif
                </div>
                <br>

            </div>
        </div>
        @endif

        <form action="/patron/paymentrequest/pay/{{$url}}" method="post">
            <div class="row no-margin">
                <div class="col-md-12 invoice-block">
                    @if(isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file']==1)
                    <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style="" href="/patron/invoice/download/{{$url}}/2 @if(isset($gtype))/{{$gtype}}@endif">
                        Print
                    </a>
                    <div class="btn-group margin-bottom-5">
                        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right:  15px " data-toggle="dropdown" aria-expanded="true" fdprocessedid="0s2a8">
                            Download <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/{{$url}} @if(isset($gtype))/1/{{$gtype}}@endif">
                                    Download {{$gtype}}
                                </a>
                            </li>
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/full/{{$url}}">
                                    Download Full PDF
                                </a>
                            </li>

                        </ul>
                    </div>

                    @else
                    <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style="" href="/patron/invoice/download/{{$url}}/2 @if(isset($gtype))/{{$gtype}}@endif">
                        Print
                    </a>
                    
                    <div class="btn-group margin-bottom-5">
                        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right:  15px " data-toggle="dropdown" aria-expanded="true" fdprocessedid="0s2a8">
                            Download <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/{{$url}} @if(isset($gtype)) /1/{{$gtype}}@endif">
                                    Download {{$gtype}}
                                </a>
                            </li>
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/full/{{$url}}">
                                    Download Full PDF
                                </a>
                            </li>

                        </ul>
                    </div>
                    @endif
                    @if( $document_url!='')
                    <a onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5">
                        {{$metadata['plugin']['upload_file_label']}}
                    </a>
                    @endif
                    @if(isset($is_online_payment) && $is_online_payment==TRUE && $grand_total>0)

                    <!--<a data-toggle="modal" href="#guestpay" class="btn green hidden-print margin-bottom-5">
                        Pay now 
                    </a>-->

                    @if( $payment_request_status==6)
                    <a id="onlinepay" href="/patron/paymentrequest/view/{{$info['invoice_link']}}" class="btn blue hidden-print margin-bottom-5">
                        View Invoice
                    </a>
                    @else
                    @if(isset($metadata['plugin']['has_online_payments']) && $metadata['plugin']['has_online_payments']==1 && isset($metadata['plugin']['enable_payments']) && $metadata['plugin']['enable_payments']==0)
                    @else
                    <button type="submit" name="paynow" class="btn blue hidden-print margin-bottom-5">
                        Pay Now
                    </button>
                    @endif
                    @endif
                    @endif
                </div>
            </div>
        </form>

    </div>

</div>
@endif
@if($has_watermark)
<script>
    var offsetHeight = document.getElementById('main_div').offsetHeight;
    intervalHeight = Math.round(offsetHeight/500);
    for(let i=1;i<=intervalHeight;i++){
        divID= Math.random();
        let clone = document.querySelector('#watermark_div').cloneNode(true);
        clone.setAttribute('id', 'watermark_div' + divID);
        document.querySelector('#watermark_parent').appendChild(clone);
        newElement  = document.getElementById('watermark_div'+divID)
        pixels  = 1000*i;
        newElement.style.paddingTop = pixels+"px";
    }    
</script>
@endif
<!-- END PAGE CONTENT-->
<div class="row" style="max-width: 909px;margin-top: 5px;padding-left: 25px;">


    <div class="col-md-12 no-margin no-padding" style="text-align: left;max-width: 909px;margin-top:10px;">
        
       <!-- <p><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt="" /><span class="powerbytxt">Powered by</span> </p>
        <br>
        <br>-->

    </div>
</div>
</div>
<div class="col-md-1"></div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->