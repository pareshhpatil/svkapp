<tr class="border-row">
    <td class="border-r border-l td-703 text-left">
        <p class="text-sm">{{$rowArray['code']}} </p>
    </td>
    <td class="border-r border-l td-703 text-left">
        @if (isset($group_name))
            @php $groupName=str_replace(' ', '_', strlen($group_name) > 7 ? substr($group_name, 0, 7) : $group_name); @endphp
            <p class="text-sm">{{$rowArray['description']}}  
                @if($rowArray['attachment']!='')
                <a href="/invoice/document/{{$user_type}}/{{$url}}/{{$groupName}}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code'])}}/{{$rowArray['attachment']}}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{$rowArray['files']}}" aria-hidden="true"></i></a>
                @endif
            </p>
        @else
            <p class="text-sm">{{ $rowArray['description'] }}  @if (!empty($rowArray['attachment']))
                <a href="/invoice/document/{{$user_type}}/{{$url}}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code']) }}/{{$rowArray['attachment']}}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{$rowArray['files']}}" aria-hidden="true"></i></a>
                @endif
            </p>
        @endif
    </td>
   
    @if($has_schedule_value)
        <td class="border-r border-l td-703 text-right">
            <p class="text-sm"><x-amount-format :amount="$rowArray['current_contract_amount']" /></p>
        </td>
        <td class="border-r border-l td-703 text-right">
            <p class="text-sm"><x-amount-format :amount="$rowArray['change_from_previous_application']" /></p>
        </td>
        <td class="border-r border-l td-703 text-right">
            <p class="text-sm"><x-amount-format :amount="$rowArray['change_this_period']" /></p>
        </td> 
        <td class="border-r border-l td-703 text-right">
            <p class="text-sm"><x-amount-format :amount="$rowArray['current_total']" /></p>
        </td>
    @else
        <td class="border-r border-l td-703 text-right">
            <p class="text-sm"><x-amount-format :amount="$rowArray['current_contract_amount']" /></p>
        </td>
    @endif
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['previously_billed_amount']" /></p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['current_billed_amount']" /></p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['stored_materials']" /></p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['total_completed']" /></p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm">@if($rowArray['g_per'] < 0)({{str_replace('-','',number_format($rowArray['g_per']  * 100, 2) )}}) @else{{ number_format($rowArray['g_per'] * 100,2)}}@endif%</p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['balance_to_finish']" /> </p>
    </td>
    <td class="border-r border-l td-703 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['total_outstanding_retainage']" /></p>
    </td>
</tr>