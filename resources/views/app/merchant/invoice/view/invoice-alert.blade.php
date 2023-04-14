@isset($invoice_success)
<br>
    @if ($payment_request_status!=11)
        @if ($notify_patron==1)
            <div class="alert alert-block alert-success fade in" style="max-width: 1400px;text-align: left;">
                <h4 class="alert-heading">Payment request sent!</h4>
                <p>
                    Your invoice has been sent to your customer. You will receive a notification as soon as your customer
                    makes the payment.
                </p>
                <p>
                    <a class="btn blue" data-toggle="modal" href="#respond">
                        Settle </a>
                    <a class="btn green" href="/merchant/invoice/update/{{$link}}">
                        Update invoice </a>
                    <a class="btn green" href="/invoice/download/{{$link}}@if(isset($gtype))/0/{{$gtype}}@endif">
                        Save as PDF</a>
                    <a class="btn green" href="/invoice/download/{{$link}}/2 @if(isset($gtype))/{{$gtype}}@endif">
                        Print</a>
                </p>
                <div style="font-size: 0px;">
                    {{-- <linkcopy>{{$patron_url}}</linkcopy> --}}
                </div>
            </div>
        @else
            <div class="alert alert-block alert-success fade in" style="max-width:1400px;text-align: left;">
                <h4 class="alert-heading">Invoice saved!</h4>
                <p>
                    Your invoice has been saved and will appear in the Requests and Reports tabs.
                </p>
                <p>
                    @if($invoice_access == 'full')
                        <a class="btn blue" data-toggle="modal" href="#respond">
                            Settle </a>
                    @endif
                    <a class="btn green" href="/merchant/invoice/update/{{$link}}">
                        Update invoice </a>
                    <a class="btn green" href="/invoice/download/{{$link}}@if(isset($gtype)) /0/{{$gtype}}@endif">
                        Save as PDF</a>
                    <a class="btn green" href="/invoice/download/{{$link}}/2 @if(isset($gtype))/{{$gtype}}@endif">
                        Print</a>
                </p>
                <div style="font-size: 0px;">
                    {{-- <linkcopy>{{$patron_url}}</linkcopy> --}}
                </div>
            </div>
        @endif
    @endif
@endisset


@isset($error)
<div class="alert alert-danger" style="max-width: 900px;text-align: left;">
    <div class="media">
        <p class="media-heading">{{$error}}</p>
    </div>
</div>
@endisset