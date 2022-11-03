@isset($info['invoice_success'])
@isset($info['signature']['font_file'])
<link href="{{$info['signature']['font_file']}}" rel="stylesheet">
@endisset
<br>
@if ($info['payment_request_status']!=11)
@if ($info['notify_patron']==1)
@if($info['invoice_type']==1)
<div class="alert alert-block alert-success fade in" style="max-width: @if($info['template_type'] == 'construction')1400px @else 900px @endif;text-align: left;">
    <h4 class="alert-heading">Payment request sent!</h4>
    <p>
        Your invoice has been sent to your customer. You will receive a notification as soon as your customer
        makes the payment.
    </p>
    <p>
        <a class="btn blue" data-toggle="modal" href="#respond">
            Settle </a>
        <a class="btn green" href="/merchant/invoice/update/{{$info['link']}}">
            Update @if($info['invoice_type']==1)invoice @else estimate @endif </a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/1/703">
            Save as PDF</a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/2/703">
            Print</a>
    </p>
    <div style="font-size: 0px;">
        <linkcopy>{{$info['patron_url']}}</linkcopy>
    </div>
</div>
@else
<div class="alert alert-block alert-success fade in" style="max-width: @if($info['template_type'] == 'construction')1400px @else 900px @endif;text-align: left;">
    <h4 class="alert-heading">Estimate sent!</h4>
    <p>
        Your estimate has been sent to your customer. You will receive a notification as soon as your customer
        makes the payment, along with the final invoice copy.
    </p>
    <p>
        <a class="btn blue" data-toggle="modal" href="#respond">
            Settle </a>
        <a class="btn green" href="/merchant/invoice/update/{{$info['link']}}">
            Update @if($info['invoice_type']==1)invoice @else estimate @endif </a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/1/703">
            Save as PDF</a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/2/703">
            Print</a>
    </p>
    <div style="font-size: 0px;">
        <linkcopy>{{$info['patron_url']}}</linkcopy>
    </div>
</div>
@endif
@else
@if($info['invoice_type']==1)
<div class="alert alert-block alert-success fade in" style="max-width: @if($info['template_type'] == 'construction')1400px @else 900px @endif;text-align: left;">
    <h4 class="alert-heading">Invoice saved!</h4>
    <p>
        Your invoice has been saved and will appear in the Requests and Reports tabs.
    </p>
    <p>
        <a class="btn blue" data-toggle="modal" href="#respond">
            Settle </a>
        <a class="btn green" href="/merchant/invoice/update/{{$info['link']}}">
            Update @if($info['invoice_type']==1)invoice @else estimate @endif </a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/1/703">
            Save as PDF</a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/2/703">
            Print</a>
    </p>
    <div style="font-size: 0px;">
        <linkcopy>{{$info['patron_url']}}</linkcopy>
    </div>
</div>
@else
<div class="alert alert-block alert-success fade in" style="max-width: @if($info['template_type'] == 'construction')1400px @else 900px @endif;text-align: left;">
    <h4 class="alert-heading">Estimate saved!</h4>
    <p>
        Your estimate has been saved and will appear in the Requests and Reports tabs.
    </p>
    <p>
        <a class="btn blue" data-toggle="modal" href="#respond">
            Settle </a>
        <a class="btn green" href="/merchant/invoice/update/{{$info['link']}}">
            Update @if($info['invoice_type']==1)invoice @else estimate @endif </a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/1/703">
            Save as PDF</a>
        <a class="btn green" href="/merchant/invoice/download/{{$info['link']}}/2/703">
            Print</a>
    </p>
    <div style="font-size: 0px;">
        <linkcopy>{{$info['patron_url']}}</linkcopy>
    </div>
</div>
@endif
@endif
@endif
@endisset
@isset(($info['payment_gateway_info']))

@if ($info['payment_gateway_info']==true)
<!-- Added info box on invoice creation -->
<div class="alert alert-info" style="max-width: 900px;text-align: left;">
    <strong>Free online transactions for you!</strong>
    <div class="media">
        <p class="media-heading">Collect payments for your invoices online. No charges for all your
            online transactions for the first 5 lakhs. <a href="/merchant/profile/complete/bank">Getting
                started.</a></p>
    </div>

</div>
@endif

@endisset
@isset($info['error'])
<div class="alert alert-danger" style="max-width: 900px;text-align: left;">
    <div class="media">
        <p class="media-heading">{{$info['error']}}</p>
    </div>

</div>
@endisset