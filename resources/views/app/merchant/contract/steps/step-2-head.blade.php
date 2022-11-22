@if(!empty($particular_column))
<thead id="headerRow" class="headFootZIndex">
<tr>
    @foreach($particular_column as $key => $particular)
    @if($key!='description')
    <th class="biggerHead @if($key == 'bill_code') col-id-no @endif" @if($key =='description' || $key =='bill_code' ) style="min-width: 100px;" @endif id="{{$key}}_head">
        {!! (strlen($particular['title']) > 10) ? str_replace( ' ', '<br>', $particular['title']) : $particular['title'] !!}
{{--        <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$particular['title']}}" data-original-title=""> {{Helpers::stringShort($particular['title'])}}</span>--}}
    </th>
    @endif
    @endforeach
    <th class="td-c" style="width: 60px;">
        ?
    </th>
</tr>
</thead>

@endif