<tr>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
        <div style="font-size:{{$font_size}} 14px;">{{$rowArray['code']}}</div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
        <div style="font-size:{{$font_size}} 14px;">{{$rowArray['description']}}</div>
    </td>
    @if($has_schedule_value)
        <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
            <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']" /></div>
        </td>
        @if($has_budget)
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['budget_reallocation']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_from_previous_application']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_this_period']" /></div>
            </td>
            @if($has_total_co_col)
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_change_order_col']" /></div>
                </td>
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_total']" /></div>
                </td>
            @else
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_total']" /></div>
                </td>
            @endif
        @elseif($has_total_co_col)
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_from_previous_application']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_this_period']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_change_order_col']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_total']" /></div>
            </td>
        @else
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_from_previous_application']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['change_this_period']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_total']" /></div>
            </td>       
        @endif
    @else
        @if($has_budget)
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']-$rowArray['budget_reallocation']-$rowArray['total_change_order_col']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['budget_reallocation']" /></div>
            </td>
            @if($has_total_co_col)
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_change_order_col']" /></div>
                </td>
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']" /></div>
                </td>
            @else
                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']" /></div>
                </td>
            @endif
        @elseif($has_total_co_col)
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_change_order_col']" /></div>
            </td>
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']+$rowArray['total_change_order_col']" /></div>
            </td>
        @else
            <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_contract_amount']" /></div>
            </td>
        @endif
    @endif
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['previously_billed_amount']" /></div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['current_billed_amount']" /></div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['stored_materials']" /></div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_completed']" /></div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;">
            @if($rowArray['g_per'] < 0)({{str_replace('-','',number_format($rowArray['g_per']  * 100, 2) )}}) @else{{ number_format($rowArray['g_per'] * 100,2) }} @endif% </div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['balance_to_finish']" /></div>
    </td>
    <td style="border-bottom: solid 1px #A0ACAC; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
        <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$rowArray['total_outstanding_retainage']" /></div>
    </td>
</tr>