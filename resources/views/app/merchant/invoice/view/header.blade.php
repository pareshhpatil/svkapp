<div class="w-full mb-2" style="max-width: 1400px;">
    @if ($payment_request_status==11)
    <div class="alert alert-block alert-success fade in">
        <p>@if($invoice_type==1) Invoice @else estimate @endif preview</p>
    </div>
    @endif
    <div class="tabbable-line" @if($user_type!='merchant' ) style="padding-left: 0px;" @endif>
        <ul class="nav nav-tabs">
            @if($user_type!='merchant')
                <li class="@if($gtype=='702') active @endif">
                    <a href="/patron/invoice/view/702/{{$url}}/patron">702</a>
                </li>
                <li class="@if($gtype=='703') active @endif">
                    <a href="/patron/invoice/view/703/{{$url}}/patron">703</a>
                </li>
                <li>
                    <a href="/invoice/document/patron/{{$url}}">Attached files</a>
                </li>
                @if($list_all_change_orders)
                    <li class="@if($gtype == 'co-listing') active @endif">
                        <a href="/patron/invoice/view/co-listing/{{$url}}/patron">CO Listing</a>
                    </li>
                @endif
            @else
                <li class="@if($gtype=='702') active @endif">
                    <a href="/merchant/invoice/view/702/{{$url}}">702</a>
                </li>
                <li class="@if($gtype=='703') active @endif">
                    <a href="/merchant/invoice/view/703/{{$url}}">703</a>
                </li>
                <li>
                    <a href="/invoice/document/merchant/{{$url}}">Attached files</a>
                </li>
                @if($list_all_change_orders)
                    <li class="@if($gtype == 'co-listing') active @endif">
                        <a href="/merchant/invoice/view/co-listing/{{$url}}">CO Listing</a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>