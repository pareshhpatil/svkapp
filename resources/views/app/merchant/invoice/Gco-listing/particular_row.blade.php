<tr class="border-row">
    <td class="border-r border-l td-703 text-left">
        <p class="text-sm">{{ $rowArray['code'] }} </p>
    </td>
    <td class="border-r border-l td-703 text-left">
        @if (isset($group_name))
            @php $groupName=str_replace(' ', '_', strlen($group_name) > 7 ? substr($group_name, 0, 7) : $group_name) @endphp
            <p class="text-sm">{{ $rowArray['description'] }}  @if (!empty($rowArray['attachment']))
                <a href="/invoice/document/{{$user_type}}/{{$url}}/{{$groupName}}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code'])}}/{{ $rowArray['attachment'] }}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{$rowArray['files']}}" aria-hidden="true"></i></a>
                @endif
            </p>
        @else
            <p class="text-sm">{{ $rowArray['description'] }}  @if (!empty($rowArray['attachment']))
                <a href="/invoice/document/{{$user_type}}/{{$url}}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code']) }}/{{ $rowArray['attachment'] }}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{$rowArray['files']}}" aria-hidden="true"></i></a>
                @endif
            </p>
        @endif
        
    </td>
    <td class="border-r border-l td-703 text-right original-con">
        <p class="text-sm"><x-amount-format :amount="$rowArray['original_contract_amount']" /></p>
    </td>
    
    @if (isset($rowArray['change_order_col_values']))
        @foreach ($rowArray['change_order_col_values'] as $key => $change_order_col_value)
            <td class="border-r border-l td-703 text-right co-vals">
                <p class="text-sm"><x-amount-format :amount="$change_order_col_value" /></p>
            </td>
        @endforeach        
    @endif

    <td class="border-r border-l td-703 text-right approved-co">
        <p class="text-sm"><x-amount-format :amount="$rowArray['approved_change_order_amount']" /></p>
    </td>
    <td class="border-r border-l td-703 text-right current-amount">
        <p class="text-sm"><x-amount-format :amount="$rowArray['current_contract_amount']" /></p>
    </td>

</tr>