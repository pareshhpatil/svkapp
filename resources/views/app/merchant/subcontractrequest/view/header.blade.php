<div class="w-full mb-2" style="max-width: 1400px;">
    @if ($payment_request_status==11)
    <div class="alert alert-block alert-success fade in">
        <p> Request of payment preview</p>
    </div>
    @endif
    <div class="tabbable-line" @if($user_type!='merchant' ) style="padding-left: 0px;" @endif>
        <ul class="nav nav-tabs">
            @if($user_type!='merchant')
                <li class="@if($gtype=='702') active @endif">
                    <a href="/patron/subcontract/requestpayment/view/702/{{$url}}/patron">702</a>
                </li>
                <li class="@if($gtype=='703') active @endif">
                    <a href="/patron/subcontract/requestpayment/view/703/{{$url}}/patron">703</a>
                </li>
                
            @else
                <li class="@if($gtype=='702') active @endif">
                    <a href="/merchant/subcontract/requestpayment/view/702/{{$url}}">702</a>
                </li>
                <li class="@if($gtype=='703') active @endif">
                    <a href="/merchant/subcontract/requestpayment/view/703/{{$url}}">703</a>
                </li>
               
            @endif
        </ul>
    </div>
</div>