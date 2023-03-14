<div class="w-full mb-2" style="max-width: 1400px;">
    @if ($info['payment_request_status']==11)
    <div class="alert alert-block alert-success fade in">
        <p>@if($info['invoice_type']==1) Invoice @else estimate @endif preview</p>
    </div>
    @endif
    <div class="tabbable-line" @if($info['user_type']!='merchant' ) style="padding-left: 0px;" @endif>
        <ul class="nav nav-tabs">
            @if($info['user_type']!='merchant')
            <li class="active">
                <a href="/patron/invoice/view/{{$info['Url']}}/702">702</a>
            </li>
            <li>
                <a href="/patron/invoice/view/{{$info['Url']}}/703">703</a>
            </li>

            <li>
                <a href="/patron/invoice/document/{{$info['Url']}}">Attached files</a>
            </li>

            @else
            <li class="active">
                <a href="/merchant/invoice/viewg702/{{$info['Url']}}">702</a>
            </li>
            <li>
                <a href="/merchant/invoice/viewg703/{{$info['Url']}}">703</a>
            </li>

            <li>
                <a href="/merchant/invoice/document/{{$info['Url']}}">Attached files</a>
            </li>

            @endif
        </ul>
    </div>
</div>