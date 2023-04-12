@if ($payment_request_status!=3)
<div class="invoice mt-1" style="max-width: 1400px;">
    <div class="row no-margin">
        <form action="/patron/paymentrequest/pay/{{$url}}" method="post">
            <div class="row no-margin">
                <div class="col-md-12 invoice-block">
                    <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style="" href="/invoice/download-v2/{{$url}}/2 @if(isset($gtype))/{{$gtype}}@endif">
                        Print
                    </a>
                    <div class="btn-group margin-bottom-5">
                        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right:  15px " data-toggle="dropdown" aria-expanded="true" fdprocessedid="0s2a8">
                            Download <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/invoice/download-v2/{{$url}}@if(isset($gtype))/0/{{$gtype}}@endif">
                                    Download {{$gtype}}
                                </a>
                            </li>
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/invoice/download-v2/{{$url}}/0/full/{{$user_type}}">
                                    Download Full PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    @if(isset($is_online_payment) && $is_online_payment==true && $absolute_cost>0)
                        <!--<a data-toggle="modal" href="#guestpay" class="btn green hidden-print margin-bottom-5">
                            Pay now 
                        </a>-->
                        @if($payment_request_status==6)
                            {{-- <a id="onlinepay" href="/patron/paymentrequest/view/{{$info['invoice_link']}}" class="btn blue hidden-print margin-bottom-5">
                                View Invoice
                            </a> --}}
                        @else
                            {{-- @if(isset($metadata['plugin']['has_partial']) && $metadata['plugin']['has_partial']==1)
                                <button type="submit" name="partial" class="btn green hidden-print margin-bottom-5">
                                    Pay Partial Amount
                                </button>
                            @endif --}}
                        
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
    {{-- @if( !empty($info['partial_payments']))
    <div class="portlet-body">
        <h4><b>Payment details</b></h4>
        <div class="table-scrollable">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="td-c">
                            Transaction ID
                        </th>
                        <th class="td-c">
                            Payment date
                        </th>
                        <th class="td-c">
                            Payment mode
                        </th>
                        <th class="td-c">
                            Amount
                        </th>
                    </tr>
                    @foreach ($info['partial_payments'] as $item)
                    <tr>
                        <td class="td-c">
                            {{$item->transaction_id ?? ''}}
                        </td>
                        <td class="td-c">
                            {{$item->payment_date ?? ''}}
                        </td>
                        <td class="td-c">
                            {{$item->payment_mode ?? ''}}
                        </td>
                        <td class="td-c">
                            {{$item->amount ?? ''}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif --}}

</div>
@endif
@if($has_watermark)
<script>
    var offsetHeight = document.getElementById('main_div').offsetHeight;
    intervalHeight = Math.round(offsetHeight / 500);
    for (let i = 1; i <= intervalHeight; i++) {
        divID = Math.random();
        let clone = document.querySelector('#watermark_div').cloneNode(true);
        clone.setAttribute('id', 'watermark_div' + divID);
        document.querySelector('#watermark_parent').appendChild(clone);
        newElement = document.getElementById('watermark_div' + divID)
        pixels = 1000 * i;
        newElement.style.paddingTop = pixels + "px";
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