<tr class="border-row">
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-left">
        <p class="text-sm">{{ $rowArray['code'] }} </p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-left">
        @if (isset($group_name))
            <p class="text-sm">{{ $rowArray['description'] }}  @if (!empty($rowArray['attachment']))
                <a href="/{{ $user_type }}/invoice/document/{{ $url }}/{{ $group_name }}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code'])}}/{{ $rowArray['attachment'] }}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{ $rowArray['files'] }}" aria-hidden="true"></i></a>
                @endif
            </p>
        @else
            <p class="text-sm">{{ $rowArray['description'] }}  @if (!empty($rowArray['attachment']))
                <a href="/{{ $user_type }}/invoice/document/{{ $url }}/{{str_replace(' ', '_', strlen($rowArray['code']) > 7 ? substr($rowArray['code'], 0, 7) : $rowArray['code']) }}/{{ $rowArray['attachment'] }}">
                    <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover" data-content="{{ $rowArray['files'] }}" aria-hidden="true"></i></a>
                @endif
            </p>
        @endif
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['current_contract_amount']" /></p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['previously_billed_amount']" /></p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['current_billed_amount']" /></p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"><x-amount-format :amount="$rowArray['stored_materials']" /></p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['total_completed']" /></p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm">@if($rowArray['g_per'] < 0)({{str_replace('-','',number_format($rowArray['g_per']  * 100, 2) )}}) @else{{ number_format($rowArray['g_per'] * 100,2)}}@endif%</p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['balance_to_finish']" /> </p>
    </td>
    <td class="border-r border-l border-[#A0ACAC] px-2 py-2 text-right">
        <p class="text-sm"> <x-amount-format :amount="$rowArray['total_outstanding_retainage']" /></p>
    </td>
</tr>