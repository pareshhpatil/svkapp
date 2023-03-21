@if ($info['payment_request_status']!=3)
<div class="invoice mt-1" style="@if($info['template_type'] == 'construction')max-width: 1400px;@endif">
    <div class="row no-margin">
        @isset($metadata['plugin']['has_deductible'])

        @if( $metadata['plugin']['has_deductible']==1)
        <div class="row no-margin">
            <div class="col-md-12 no-margin font-blue">
                <label>Deductions applicable to this invoice can be adjusted after clicking Pay Now</label>
            </div>
        </div>
        @endif

        @endisset
        @if($info['document_url']!='')
        <div class="row" id="document_div" style="display: none;">
            <button class="btn btn-default pull-right" onclick="showDocument(2);" style="margin-right: 15px"><i class="fa fa-close"></i></button>
            <div class="col-md-12">
                <div style="max-height: 700px;overflow: auto;">
                    @if(substr(strtolower($info['document_url']), -3)=='pdf')
                    <iframe src="{{$info['document_url']}}" frameborder="no" width="100%" height="500px">
                    </iframe>
                    @else
                    <img src="{{$info['document_url']}}" style="width: 100%;max-width: 100%;">
                    @endif
                </div>
                <br>

            </div>
        </div>
        @endif

        <form action="/patron/paymentrequest/pay/{{$info['Url']}}" method="post">
            @isset($info['is_online_payment'])

            @if( $info['is_online_payment']==TRUE && $info['grand_total']>0)
            @if(isset($info['coupon_details']) && !empty($info['coupon_details']))
            <div class="row">
                <div class="col-md-12">
                    <table class="table" style="background-color: #fcf8e3;">
                        <tbody>
                            <tr>
                                <td>
                                    <input type="hidden" class="displayonly" id="coupon_used" readonly name="coupon_used" value="0" />
                                    <input type="hidden" class="displayonly" name="coupon_id" value="{{$info['coupon_details']['coupon_id']}}" />
                                    <input type="hidden" class="displayonly" name="discount" id="discount_amt" value="" />
                                    <input type="hidden" class="displayonly" id="c_percent" value="{{$info['coupon_details']['percent']}}" />
                                    <input type="hidden" class="displayonly" id="bill_total" value="{{$info['invoice_total']}}" />
                                    <input type="hidden" class="displayonly" id="surcharge_amount" value="{{$info['surcharge_amount']}}" />
                                    <input type="hidden" class="displayonly" id="fee_id" value="{{$info['fee_id']}}" />
                                    <input type="hidden" class="displayonly" id="grand_total" value="{{$info['grand_total']}}" />
                                    <input type="hidden" class="displayonly" id="c_fixed_amount" value="{{$info['coupon_details']['fixed_amount']}}" />
                                    <input type="hidden" class="displayonly" id="paymenturl" value="/patron/paymentrequest/pay/{{$info['Url']}}" />
                                    Coupon discount : <b>@if($info['coupon_details']['type']==1)
                                        Rs.{{$info['coupon_details']['fixed_amount']}}/- @else{{$info['coupon_details']['percent']}}%
                                        @endif</b>
                                    <p class="small">{{$info['coupon_details']['descreption']}}</p>
                                </td>
                                <td>
                                    <a class="btn blue pull-right" href="javascript:" id="btn_apply_coupun" style="display: block;" onclick="handleCoupon(1,{{$info['coupon_details']['type']}});">Apply
                                        Coupon <i class="fa fa-check"></i></a>
                                    <a class="btn red pull-right" href="javascript:" id="btn_remove_coupon" style="display: none;" onclick="handleCoupon(2,{{$info['coupon_details']['type']}});">Remove
                                        Coupon <i class="fa fa-remove"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            @else
            @if(isset( $metadata['plugin']['has_coupon']) && isset($info['coupon_details']['coupon_id']))
            <div class="row no-margin">
                <br>

                <div class="col-md-3">

                    <input type="text" id="coupon_code" placeholder="Enter coupon code" class="form-control" value="" />
                    <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="{{$info['coupon_details']['coupon_id']}}" />
                    <br>
                </div>
                <div class="col-md-2">
                    <label class="font-red">&nbsp;</label>
                    <button onclick="return validateCoupon('{{$info['merchant_user_id']}}', '{{$info['grand_total']}}');" class="btn green">Apply</button>
                </div>
                <div class="col-md-5">
                    <label class="font-blue" id="coupon_status">&nbsp;</label>

                </div>

            </div>
            @endif
            @endif
            @endif

            @endisset
            <div class="row no-margin">
                <div class="col-md-12 invoice-block">
                    @if(isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file']==1)
                    <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style="" href="/patron/invoice/download/{{$info['Url']}}/2 @if(isset($info['gtype']))/{{$info['gtype']}}@endif">
                        Print
                    </a>
                    <div class="btn-group margin-bottom-5">
                        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right:  15px " data-toggle="dropdown" aria-expanded="true" fdprocessedid="0s2a8">
                            Download <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/{{$info['Url']}} @if(isset($info['gtype']))/1/{{$info['gtype']}}@endif">
                                    Download {{$info['gtype']}}
                                </a>
                            </li>
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/full/{{$info['Url']}}">
                                    Download Full PDF
                                </a>
                            </li>

                        </ul>
                    </div>

                    @else
                    <a class="btn btn-link hidden-print margin-bottom-5" target="_BLANK" style="" href="/patron/invoice/download/{{$info['Url']}}/2 @if(isset($info['gtype']))/{{$info['gtype']}}@endif">
                        Print
                    </a>
                    
                    <div class="btn-group margin-bottom-5">
                        <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right:  15px " data-toggle="dropdown" aria-expanded="true" fdprocessedid="0s2a8">
                            Download <i class="fa fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/{{$info['Url']}} @if(isset($info['gtype'])) /1/{{$info['gtype']}}@endif">
                                    Download {{$info['gtype']}}
                                </a>
                            </li>
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/patron/invoice/download/full/{{$info['Url']}}">
                                    Download Full PDF
                                </a>
                            </li>

                        </ul>
                    </div>
                    @endif
                    @if( $info['document_url']!='')
                    <a onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5">
                        {{$metadata['plugin']['upload_file_label']}}
                    </a>
                    @endif
                    @if(isset($info['is_online_payment']) && $info['is_online_payment']==TRUE && $info['grand_total']>0)

                    <!--<a data-toggle="modal" href="#guestpay" class="btn green hidden-print margin-bottom-5">
                        Pay now 
                    </a>-->

                    @if( $info['payment_request_status']==6)
                    <a id="onlinepay" href="/patron/paymentrequest/view/{{$info['invoice_link']}}" class="btn blue hidden-print margin-bottom-5">
                        View Invoice
                    </a>
                    @else
                    @if(isset($metadata['plugin']['has_partial']) && $metadata['plugin']['has_partial']==1)
                    <button type="submit" name="partial" class="btn green hidden-print margin-bottom-5">
                        Pay Partial Amount
                    </button>
                    @endif
                    @if(isset($metadata['plugin']['autocollect_plan_id']) && $metadata['plugin']['autocollect_plan_id']>0)
                    <button type="submit" name="autopay" class="btn green hidden-print margin-bottom-5">
                        Enable auto payment
                    </button>
                    @endif

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

    @if( !empty($info['partial_payments']))

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
    @endif


</div>
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