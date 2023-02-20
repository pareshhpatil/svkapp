@php
$validate=(array)$validate;

@endphp


@if($info['payment_request_status']!=3)
<div class="invoice mt-1 invoice-footer-btns" style="@if($info['template_type'] == 'construction')max-width: 1400px;@endif">

    @if(isset($info['transaction']))
    @php
    $transaction=(array)$info['transaction'];

    @endphp
    <h5 class="invoice-sub-title"><b>Payment details</b></h5>
    <table class="table table-bordered table-condensed">
        <tr>
            <th>Receipt no.</th>
            <th>Receipt date</th>
            <th>Customer</th>
            <th>Payment method</th>
            <th>Amount</th>
        </tr>
        <tr>
            <td>{{$transaction['transaction_id']}}</td>
            <td>{{$transaction['date']}}</td>
            <td>{{$transaction['patron_name']}}</td>
            <td>{{$transaction['payment_mode']}}</td>
            <td>{{$transaction['amount']}}</td>
        </tr>
    </table>
    @endif

    @isset($info['supplierlist'])
    @if(!empty($info['supplierlist']))
    <div class="row no-margin">
        <h5 class="invoice-sub-title">Suppliers</h5>
        <div class="col-xs-12">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>
                            Company name
                        </th>
                        <th>
                            Contact name
                        </th>
                        <th>
                            Mobile
                        </th>
                        <th>
                            Email
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($info['supplierlist'] as $key=>$item)

                    <tr>
                        <td>@if($item['supplier_company_name'] !=
                            ''){{$item['supplier_company_name']}} @else &nbsp;
                            @endif</td>
                        <td>@if($item['contact_person_name'] !=
                            ''){{$item['contact_person_name']}} @else &nbsp;
                            @endif</td>
                        <td>@if($item['mobile1'] != ''){{$item['mobile1']}}@else &nbsp; @endif</td>
                        <td>@if($item['email_id1']!= ''){{$item['email_id1']}}@else &nbsp; @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endisset

    @if(isset($info['document_url']) && $info['document_url']!='')
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


    @if($info['payment_request_status']!=1 && $info['payment_request_status']!=2 && $info['payment_request_status']!=3)
    @if(!empty($info['coupon_details']))
    {{-- <div class="row">
             <div class="col-md-12">
                 <table class="table" style="background-color: #fcf8e3;">
                     <tbody>
                         <tr>
                             <td>
                                 Coupon discount : <b>@if( $info['coupon_details']['type']==1) Rs.{{$info['coupon_details']['fixed_amount']}}/-
    @else {{$info['coupon_details']['percent']}} %
    @endif</b>
    <p class="small">{{$info['coupon_details']['descreption']}}</p>
    </td>
    <td>
        <button class="btn blue pull-right" id="btn_apply_coupun" style="display: block;" onclick="handleCoupon(1,{{$info['coupon_details']['type']}});">Apply Coupon <i class="fa fa-check"></i></button>
        <button class="btn btn-link pull-right" id="btn_remove_coupon" style="display: none;" onclick="handleCoupon(2,{{$info['coupon_details']['type']}});">Remove Coupon <i class="fa fa-remove"></i></button>
    </td>
    </tr>
    </tbody>
    </table>

</div>
</div> --}}
@endif
<div class="row no-margin">
    <div class="col-md-12 mt-1">
        @if($info['payment_request_status']==11 || $info['payment_request_status']==14)

        <form class="form-horizontal invoice-preview-form" action="/merchant/invoice/saveInvoicePreview/{{$info['payment_request_id']}}" method="post" onsubmit="document.getElementById('loader').style.display = 'block';">
            @csrf
            <div class="col-md-4 pull-left btn-pl-0">
                @if(!empty($info['invoice_access']) && $info['invoice_access'] == 'all-full')
                    <div class="input-icon">
                        <label class="control-label pr-1">Notify customer </label> <input type="checkbox" data-cy="notify" id="notify_" onchange="notifyPatron('notify_');" value="1" @if($info['notify_patron']==1) checked @endif class="make-switch" data-size="small">
                        <input type="hidden" id="is_notify_" name="notify_patron" value="{{($info['notify_patron']==1) ? 1 : 0}}" />
                    </div>
                @endif

            </div>
            <input type="hidden" name="payment_request_id" value="{{$info['payment_request_id']}}" />
            <input type="hidden" name="payment_request_type" value="{{$info['payment_request_type']}}" />
            <input type="hidden" name="payment_request_status" value="{{$info['payment_request_status']}}" />

            <div class="view-footer-btn-rht-align">
                @if(!empty($info['invoice_access']))
                    @if($info['notify_patron']==1 && $info['invoice_access'] == 'all-full')
                        <input type="button" value="Save & Send" id="saveandsendbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />
{{--                        <input type="button" value="Save & Send" id="saveandsendbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />--}}
                    @else
                        <input type="button" value="Save" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />
                        
                    @endif
                @endif
            </div>
        </form>
        @endif
        @if($info['payment_request_status']!=11)
        @if($info['grand_total']>1)
        @if($info['invoice_type']==2)
        @if($info['payment_request_status']!=6)
        <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" data-toggle="modal" href="#convert">
            Convert to Invoice
        </a>
        <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: 20px;" data-toggle="modal" href="#settleestimate">
            Settle
        </a>
        @endif
        @else
        <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" data-toggle="modal" href="#respond">
            Settle
        </a>
        @endif
        @endif
        @endif
        @if($info['payment_request_status']!=6 && $info['payment_request_status']!=7)
        <div class=" view-footer-btn-rht-align btn-pl-0" style="margin-top: @if($info['payment_request_status']==11)-13px @else 0px;@endif">

            <a class="btn green hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px @endif" href="/merchant/invoice/update/{{$info['Url']}}">
                Update @if($info['invoice_type']==1) invoice @else estimate @endif
            </a>
        </div>
        @endif
        @if($info['payment_request_status']!=11)
        @if(isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file'] ==1)
{{--        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">--}}
{{--            Save as PDF--}}
{{--        </a>--}}
        <div class="btn-group pull-right">
            <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" data-toggle="dropdown" aria-expanded="true">
                Download <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                @if($info['gtype'] != 'attachment')
                    <li>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                            Download {{$info['gtype']}}
                        </a>
                    </li>
                @endif
                <li>
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/full/{{$info['Url']}}">
                        Download Full PDF
                    </a>
                </li>

            </ul>
        </div>
        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" href="/merchant/invoice/download/{{$info['Url']}}/2 @if(isset($info['gtype']))/{{$info['gtype']}} @endif">
            Print
        </a>
        @else

{{--        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">--}}
{{--            Save as PDF--}}
{{--        </a>--}}
        <div class="btn-group pull-right">
            <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" data-toggle="dropdown" aria-expanded="true">
                Download <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                @if($info['gtype'] != 'attachment')
                    <li>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                            Download {{$info['gtype']}}
                        </a>
                    </li>
                @endif
                <li>
                    <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/full/{{$info['Url']}}">
                        Download Full PDF
                    </a>
                </li>

            </ul>
        </div>
        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" href="/merchant/invoice/download/{{$info['Url']}}/2 @if(isset($info['gtype']))/{{$info['gtype']}}@endif">
            Print
        </a>
        @endif
        @else
        <div class=" view-footer-btn-rht-align btn-pl-0" style="margin-top: @if($info['payment_request_status']==11)-13px @else 0px;@endif">

{{--            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">--}}
{{--                Save as PDF--}}
{{--            </a>--}}
            <div class="btn-group pull-right">
                <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" data-toggle="dropdown" aria-expanded="true">
                    Download <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                    @if($info['gtype'] != 'attachment')
                        <li>
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                                Download {{$info['gtype']}}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/full/{{$info['Url']}}">
                            Download Full PDF
                        </a>
                    </li>

                </ul>
            </div>

        </div>
        @endif

        @if(isset($info['document_url']) && $info['document_url']!='')
        <button onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: 20px;">

            {{$metadata['plugin']['upload_file_label']}}
        </button>
        @endif
    </div>
</div>
@else
<div class="row no-margin">
    <div class="col-md-12">
        @if(isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file']==1)
            <div class="btn-group pull-right">
                <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" data-toggle="dropdown" aria-expanded="true">
                    Download <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                    @if($info['gtype'] != 'attachment')
                        <li>
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                                Download {{$info['gtype']}}
                            </a>
                        </li>
                    @endif
                    <li>
                        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/full/{{$info['Url']}}">
                            Download Full PDF
                        </a>
                    </li>

                </ul>
            </div>

{{--            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">--}}
{{--            Save as PDF--}}
{{--        </a>--}}
        <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/2/{{$info['gtype']}}@endif">
            Print
        </a>
        @else
            @if($info['payment_request_status'] == '2')
                <a class="btn blue hidden-print margin-bottom-5 view-footer-btn-rht-align" style="margin-right: 20px;" data-toggle="modal" href="#respond">
                    Update Transaction
                </a>
            @endif
                <div class="btn-group pull-right">
                    <button id="btnGroupVerticalDrop7" type="button" class="btn btn-link hidden-print view-footer-btn-rht-align dropdown-toggle" style="margin-right: @if($info['invoice_type']==1) 15px @else 20px  @endif" data-toggle="dropdown" aria-expanded="true">
                        Download <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                        @if($info['gtype'] != 'attachment')
                            <li>
                                <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                                    Download {{$info['gtype']}}
                                </a>
                            </li>
                        @endif
                        <li>
                            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5" href="/merchant/invoice/download/full/{{$info['Url']}}">
                                Download Full PDF
                            </a>
                        </li>

                    </ul>
                </div>
            <a target="_BLANK" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;" href="/merchant/invoice/download/{{$info['Url']}}@if(isset($info['gtype']))/0/{{$info['gtype']}}@endif">
                Print
            </a>
        @endif
    </div>
</div>
@if($info['document_url']!='')
<button onclick="showDocument(1);" class="btn btn-link hidden-print margin-bottom-5 pull-right" style="margin-right: 20px;">
{{$metadata['plugin']['upload_file_label']}}
</button>
@endif
@endif

{{-- <div class="row no-margin">
    <div class="col-md-12">

            <a target="_BLANK" class="btn blue hidden-print  pull-right" style="margin-right: 10px;"
                href="/merchant/invoice/download/{{$info['Url']}}/2">
Print<i class="fa fa-print"></i>
</a>

</div>
</div> --}}


@isset($info['partial_payments'])

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

                @foreach ($info['partial_payments'] as $key=>$item)


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
@endisset

</div>
@isset($info['commentlist'])
@if(!empty($info['commentlist']))
<div class="row no-margin" style="">
<br>
<div class="col-md-12 portlet light bordered" style="text-align: left;">
<a href="/merchant/comments/view/{{$info['Url']}}" title="Comments" class="btn btn-xs blue iframe pull-right"><i class="fa fa-comment"></i> </a>
@foreach ($info['commentlist'] as $key=>$item)
<div class="media">
    <div class="media-body">
        <h5 class="media-heading">{{$item['name']}} <span>
                {{$item['last_update_date']}} - <a class="iframe" href="/merchant/comments/update/{{$item['link']}}">
                    Edit </a>/
                <a class="iframe" href="/merchant/comments/delete/{{$item['link']}}">
                    Delete </a>
            </span>
        </h5>
        <p>
            {{$item['comment']}}
        </p>
        <hr>
    </div>
</div>
@endforeach
</div>
</div>
@endif
@endif

@endisset
</div>

<!-- END PAGE CONTENT-->
</div>
</div>





</div>
<div class="col-md-1"></div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
<div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
<div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body">

        <div class=" ">
            <div>
                <div class="form-section">
                    Offline payment
                    <hr>
                </div>

            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="respond-form" action="/merchant/paymentrequest/respond" method="post" onsubmit="document.getElementById('respond').style.display = 'none';
                                      document.getElementById('loader').style.display = 'block';">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                <h4 class="alert-heading">Log partial payment?</h4>
                                    <button class="close" id="md_close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                @isset($metadata['plugin']['deductible'])
                                @if(!empty($metadata['plugin']['deductible']))
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Select
                                        deductible <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <select name="selecttemplate" onchange="calculatedeductMerchant('{{$info['tax_amount']}}', '{{$info['basic_amount']-$info['advance']}}', '{{$info['Previous_dues']}}');" id="deduct" required class="form-control" data-placeholder="Select...">
                                            <option value="0">Select deductible</option>
                                            @foreach($metadata['plugin']['deductible'] as $key=>$item)
                                            <option value="{{$item['total']}}">{{$item['tax_name']}} ({{$item['percent']}} %)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @endisset
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Transaction
                                        type<span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <select class="form-control input-sm" name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                            <option value="1">NEFT/RTGS</option>
                                            <option value="2">Cheque</option>
                                            <option value="3">Cash</option>
                                            <option value="5">Online Payment</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" id="cod_status" style="display: none;">
                                    <label for="inputPassword12" class="col-md-4 control-label">COD Status</label>
                                    <div class="col-md-5">
                                        <select class="form-control input-sm" name="cod_status" data-placeholder="Select status">
                                            <option value="cod">COD</option>
                                            <option value="cash_collected">Cash Collected</option>
                                            <option value="cash_received">Cash Received</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" id="bank_transaction_no">
                                    <label for="inputPassword12" class="col-md-4 control-label">Bank ref
                                        no</label>
                                    <div class="col-md-5">
                                        @if($info['payment_request_status'] == '2' && isset($info['offline_success_transaction']))
                                            <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="{{$info['offline_success_transaction']->bank_transaction_no}}" placeholder="Bank ref number" />
                                        @else
                                            <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number" />
                                        @endif
                                    </div>
                                </div>
                                <div id="cheque_no" style="display: none;">
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Cheque
                                            no</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" name="cheque_no" {!!$validate['number']!!} type="text" value="" placeholder="Cheque no" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Cheque
                                            status</label>
                                        <div class="col-md-5">
                                            <select class="form-control input-sm" name="cheque_status" data-placeholder="Select status">
                                                <option value="Deposited">Deposited</option>
                                                <option value="Realised">Realised</option>
                                                <option value="Bounced">Bounced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="cash_paid_to" style="display: none;">
                                    <label for="inputPassword12" class="col-md-4 control-label">Cash paid
                                        to</label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" name="cash_paid_to" {!!$validate['name']!!} type="text" value="{{$info['user_name']}}" placeholder="Cash paid to" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Date <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        @if($info['payment_request_status'] == '2'  && isset($info['offline_success_transaction']))
                                            <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" required name="date" type="text" value="{{ date('M d Y', strtotime($info['offline_success_transaction']->settlement_date)) }}" placeholder="Date" />
                                        @else
                                            <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" required name="date" type="text" value="" placeholder="Date" />
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group" id="bank_name">
                                    <label for="inputPassword12" class="col-md-4 control-label">Bank
                                        name</label>
                                    <div class="col-md-5">
                                        @if($info['payment_request_status'] == '2' && isset($info['offline_success_transaction']))
                                            <input class="form-control form-control-inline input-sm" name="bank_name" type="text" value="{{$info['offline_success_transaction']->bank_name}}" placeholder="Bank name" />
                                        @else
                                            <input class="form-control form-control-inline input-sm" name="bank_name" type="text" value="" placeholder="Bank name" />
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Amount <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        @if($info['payment_request_status'] == '2' && isset($info['offline_success_transaction']))
                                            <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {!!$validate['amount']!!} value="{{$info['offline_success_transaction']->amount}}" placeholder="Amount" />
                                            <input type="hidden" id="original_amount" value="{{$info['absolute_cost']}}" />
                                        @else
                                            <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {!!$validate['amount']!!} value="{{$info['absolute_cost']}}" placeholder="Amount" />
                                            <input type="hidden" id="original_amount" value="{{$info['absolute_cost']}}" />
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-4 control-label">Send the receipt
                                        to customer <span class="required">
                                        </span></label>
                                    <div class="col-md-5">
                                        <input type="checkbox" name="send_receipt" onchange="showDocument(this.checked)" data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;" value="1" checked class="make-switch" data-size="small">
                                    </div>
                                </div>
                                <input type="hidden" name="notification_type" value="1">
                                <!--<div class="form-group" id="document_div">
                                    <label for="inputPassword12" class="col-md-4 control-label">Notify customer
                                        on <span class="required">
                                        </span></label>
                                    <div class="col-md-5">
                                        <select class="form-control input-sm" name="notification_type">
                                            <option selected value="1">Email</option>
                                            <option value="2">SMS</option>
                                            <option value="3">Email & SMS</option>
                                        </select>
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning" id="partial-warning" style="display: none;">
                                            <div class="media">
                                                <p class="media-heading">
                                                <b>Log partial payment?</b>
                                                </p>
                                                <p>
                                                You have chosen to settle (close) the invoice with an amount lesser than the full amount due. Would you like to mark this invoice as partially paid?
                                                </p>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-4"></div>
                                    <div class="col-md-8 center">

                                        <input type="hidden" name="payment_req_id" value="{{$info['payment_request_id']}}" />
                                        <input type="hidden" class="displayonly" id="bill_total" value="{{$info['invoice_total']}}" />
                                        <input type="hidden" class="displayonly" id="coupon_used" readonly name="coupon_used" value="0" />
                                        <input type="hidden" class="displayonly" id="coupon_id" name="coupon_id" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['coupon_id']}}@endif" />
                                        <input type="hidden" class="displayonly" id="surcharge_amount" value="{{$info['surcharge_amount']}}" />
                                        <input type="hidden" class="displayonly" id="fee_id" value="@if(isset($info['fee_id'])){{$info['fee_id']}}@endif" />
                                        <input type="hidden" class="displayonly" name="discount" id="discount_amt" value="" />
                                        <input type="hidden" class="displayonly" id="c_percent" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['percent']}}@endif" />
                                        <input type="hidden" class="displayonly" id="grand_total" value="{{$info['grand_total']}}" />
                                        <input type="hidden" class="displayonly" id="c_fixed_amount" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['fixed_amount']}}@endif" />
                                        <input type="hidden" class="displayonly" id="paymenturl" value="/patron/paymentrequest/pay/{{$info['Url']}}" />
                                        <input type="hidden" class="displayonly" id="is_partial_field" name="is_partial" value="0" />
                                        @if(!empty($info['offline_response_id']))
                                            <input type="hidden" class="displayonly" id="offline_response_id" name="offline_response_id" value="{{$info['offline_response_id']}}" />
                                        @endif
                                        <input name="deduct_amount" id="deduct_amount" type="hidden" class="displayonly" value="0" />
                                        <input name="deduct_text" id="deduct_text" type="hidden" class="displayonly" value="" />
                                        @if($info['payment_request_status'] == '2' && !empty($info['offline_response_id']))
                                            <a href="#delete-confirm-modal" id="deletebtn"  data-toggle="modal" onclick="document.getElementById('deleteanchor').href = '/merchant/transaction/delete/{{$info['offline_response_id']}}'" class="btn swipez-draft-btn pull-right mr-1">Delete Transaction</a>
                                        @endif
                                        @if($info['payment_request_status'] != '2')
                                            <button id="settlebutton" type="submit" onclick="return validatePartial();" class="btn blue center pull-right mr-1">Settle
                                                Invoice</button>
                                        @endif

                                        <button id="settlebuttonconfirm" style="display: none;" type="submit" onclick="return validatePartial();" class="btn green center pull-right mr-1">Settle
                                            Invoice</button>
                                        @if($info['payment_request_status'] == '2' || $info['payment_request_status'] == '7')
                                            <button id="partialbtn" type="button" onclick="savePartialPayment();" class="btn green pull-right mr-1">Save Partial Payment</button>
                                        @else
                                            @isset($metadata['plugin']['has_partial'])
                                                @if($metadata['plugin']['has_partial']==1)
                                                    <button id="partialbtn" type="button" onclick="savePartialPayment();" class="btn green pull-right mr-1">Save Partial Payment</button>
                                                @endif
                                            @endisset
                                        @endif


                                        <button data-dismiss="modal" onclick="resetSettle();" aria-hidden="true" class="btn green pull-right mr-1">Cancel</button>

                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>


                </div>


            </div>
        </div>

    </div>

</div>

</div>
<!-- /.modal-content -->
</div>

<div class="modal fade" id="convert" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Convert to Invoice</h4>
    </div>
    <form class="form-horizontal" action="/merchant/paymentrequest/estimateinvoice" method="post" onsubmit="document.getElementById('convert').style.display = 'none';
                      document.getElementById('loader').style.display = 'block';">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="form-body">
                        <div class="alert alert-danger display-hide">
                            <button class="close" id="md_close" data-close="alert"></button>
                            You have some form errors. Please check below.
                        </div>
                        @isset($metadata['plugin']['has_online_payments'])
                        @if($metadata['plugin']['has_online_payments']==1)
                        <div class="form-group">
                            <label for="inputPassword12" class="col-md-10">
                                Your estimate has online payments disabled. Would you like to enable online
                                payments on the converted invoice?

                            </label>
                            <div class="col-md-2">
                                <div class="pull-right">
                                    <input type="checkbox" data-cy="plugin_online_payments""
                                    data-on-text=" &nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;" class="make-switch pull-right" data-size="small" onchange="checkValue('is_online_payments_', this.checked);">
                                    <input type="hidden" id="is_online_payments_" @if((isset($metadata['plugin']['has_online_payments']) && $metadata['plugin']['has_online_payments']=='1' ) ) value="1" @else value="0" @endif name="has_online_payments" />
                                    <input type="hidden" id="is_enable_payments_" name="enable_payments" @if( (isset($metadata['plugin']['enable_payments']) && $metadata['plugin']['enable_payments']=='1' ) ) value="1" @else value="0" @endif />
                                </div>
                            </div>


                        </div>
                        <hr>
                        @endif
                        @endisset
                        <div class="form-group">
                            <label for="inputPassword12" class="col-md-10">Send this
                                invoice to customer <span class="required">
                                </span></label>
                            <div class="col-md-2">
                                <div class="pull-right">
                                    <input type="checkbox" name="send_invoice" data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;" value="1" checked class="make-switch" data-size="small">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
            <input type="hidden" name="estimate_req_id" value="{{$info['payment_request_id']}}" />
            <button type="submit" class="btn blue">Submit</button>
        </div>
    </form>
</div>
</div>
<!-- /.modal-content -->
</div>
<div class="modal fade bs-modal-lg" id="settleestimate" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body">
        <div class="portlet ">
            <div class="portlet-title">
                <div class="caption">
                    Settle Estimate
                </div>

            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-10">
                        <form class="form-horizontal" action="/merchant/paymentrequest/estimatesettle" method="post" onsubmit="document.getElementById('settleestimate').style.display = 'none';
                                              document.getElementById('loader').style.display = 'block';">
                            <div class="form-body">
                                <div class="alert alert-danger display-hide">
                                    <button class="close" id="md_close" data-close="alert"></button>
                                    You have some form errors. Please check below.
                                </div>
                                @isset($metadata['plugin']['deductible'])

                                @if( !empty($metadata['plugin']['deductible']))
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-5 control-label">Select
                                        deductible <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <select name="selecttemplate" onchange="calculatedeductMerchant('{{$info['tax_amount']}}', '{{$info['basic_amount']-$info['advance']}}', '{{$info['Previous_dues']}}');" id="deduct" required class="form-control" data-placeholder="Select...">
                                            <option value="0">Select deductible</option>





                                            @foreach ($metadata['plugin']['deductible'] as $item=>$v)
                                            <option value="{{$v['total']}}">{{$v['tax_name']}} ({{$v['percent']}} %)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>





                                @endif
                                @endisset
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-5 control-label">Transaction
                                        type<span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <select class="form-control input-sm select2me" name="response_type" onchange="responseType(this.value);" data-placeholder="Select type">
                                            <option value="1">NEFT/RTGS</option>
                                            <option value="2">Cheque</option>
                                            <option value="3">Cash</option>
                                            <option value="5">Online Payment</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" id="bank_transaction_no">
                                    <label for="inputPassword12" class="col-md-5 control-label">Bank ref
                                        no</label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" name="bank_transaction_no" type="text" value="" placeholder="Bank ref number" />
                                    </div>
                                </div>
                                <div id="cheque_no" style="display: none;">
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                            no</label>
                                        <div class="col-md-5">
                                            <input class="form-control form-control-inline input-sm" name="cheque_no" {!!$validate['number']!!} type="text" value="" placeholder="Cheque no" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-5 control-label">Cheque
                                            status</label>
                                        <div class="col-md-5">
                                            <select class="form-control input-sm" name="cheque_status" data-placeholder="Select status">
                                                <option value="Deposited">Deposited</option>
                                                <option value="Realised">Realised</option>
                                                <option value="Bounced">Bounced</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="cash_paid_to" style="display: none;">
                                    <label for="inputPassword12" class="col-md-5 control-label">Cash paid
                                        to</label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" name="cash_paid_to" {!!$validate['name']!!} type="text" value="{{$info['user_name']}}" placeholder="Cash paid to" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-5 control-label">Date <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm date-picker" onkeypress="return false;" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" required name="date" type="text" value="" placeholder="Date" />
                                    </div>
                                </div>
                                <div class="form-group" id="bank_name">
                                    <label for="inputPassword12" class="col-md-5 control-label">Bank
                                        name</label>
                                    <div class="col-md-5">
                                        <select class="form-control input-sm select2me" name="bank_name" data-placeholder="Select bank name">
                                            <option value=""></option>
                                            @foreach ($info['bank_value'] as $key=>$item)
                                            <option value="{{$item}}">{{$item}}</option>

                                            @endforeach




                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-5 control-label">Amount <span class="required">*
                                        </span></label>
                                    <div class="col-md-5">
                                        <input class="form-control form-control-inline input-sm" id="total" name="amount" required type="text" {!!$validate['amount']!!} value="{{$info['absolute_cost']}}" placeholder="Amount" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword12" class="col-md-5 control-label">Send the receipt
                                        & invoice to customer <span class="required">
                                        </span></label>
                                    <div class="col-md-5">
                                        <input type="checkbox" name="send_receipt" data-on-text="&nbsp;Yes&nbsp;&nbsp;" data-off-text="&nbsp;No&nbsp;" value="1" checked class="make-switch" data-size="small">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-3">
                                        <input type="hidden" name="estimate_req_id" value="{{$info['payment_request_id']}}" />
                                        <input type="hidden" class="displayonly" id="bill_total" value="{{$info['invoice_total']}}" />
                                        <input type="hidden" class="displayonly" id="coupon_used" readonly name="coupon_used" value="0" />
                                        <input type="hidden" class="displayonly" name="coupon_id" value="@if(!empty($info['coupon_details'])){{ $info['coupon_details']['coupon_id'] }}@endif" />
                                        <input type="hidden" class="displayonly" id="surcharge_amount" value="{{$info['surcharge_amount']}}" />
                                        <input type="hidden" class="displayonly" id="fee_id" value="@if(isset($info['fee_id'])){{$info['fee_id']}}@endif" />
                                        <input type="hidden" class="displayonly" name="discount" id="discount_amt" value="" />
                                        <input type="hidden" class="displayonly" id="c_percent" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['percent']}}@endif" />
                                        <input type="hidden" class="displayonly" id="grand_total" value="{{$info['grand_total']}}" />
                                        <input type="hidden" class="displayonly" id="c_fixed_amount" value="@if(!empty($info['coupon_details'])){{$info['coupon_details']['fixed_amount']}}@endif" />
                                        <input type="hidden" class="displayonly" id="paymenturl" value="/patron/paymentrequest/pay/{{$info['Url']}}" />
                                        <input name="deduct_amount" id="deduct_amount" type="hidden" class="displayonly" value="0" />
                                        <input name="deduct_text" id="deduct_text" type="hidden" class="displayonly" value="" />
                                        <button type="submit" class="btn blue">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /.modal-content -->
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-confirm-modal" tabindex="-1" role="delete-confirm-modal" aria-hidden="true" aria-labelledby="delete-confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Transaction</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete transaction?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
function checkValue(id, check) {
if (check == true) {
    val = 1;
    style = 'block';
} else {
    val = 0;
    style = 'none';
}
document.getElementById(id).value = val;
document.getElementById('is_enable_payments_').value = val;
}

function savePartialPayment() {
    $("#is_partial_field").val('1');
    $("#respond-form").submit();
}

$(function(){
    let invoicePreviewForm = $('.invoice-preview-form');
    $("#subbtn").on("click", function () {
        let paymentRequestStatus = invoicePreviewForm.find('input[name=payment_request_status]');
        paymentRequestStatus.val(14);
        invoicePreviewForm.submit();
    })

    $("#saveandsendbtn").on("click", function () {
        let paymentRequestStatus = invoicePreviewForm.find('input[name=payment_request_status]');
        // let notifyPatronStatus = invoicePreviewForm.find('input[name=notify_patron]');
        paymentRequestStatus.val(0);
        // notifyPatronStatus.val(1);
        invoicePreviewForm.submit();
    })
})
</script>
    <style>
        .swipez-draft-btn {
            color: #611818;
            background: #FFFFFF;
            border: 1px solid #FCE8E8;
        }

        .invoice-footer-btns {
            margin-bottom: 100px;
        }
    </style>