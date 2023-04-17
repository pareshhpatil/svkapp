<div class="w-full   bg-white  shadow-2xl font-rubik m-2 p-10 watermark" style="max-width: 1400px;  color:#394242;" id="main_div">
    <!--include subheader file-->
    @include('app.merchant.invoice.view.subheader',array('title'=>'Change order listing','gtype'=>'co-listing'))

    
    <div class='overflow-x-auto w-full mt-4 mb-4'>
        <table class='mx-auto w-full border-collapse border border-[#A0ACAC] overflow-hidden'>
            <thead>
                {{-- <tr class="text-center">
                    <td class="border td-703 font-regular text-xs  text-center"> A </td>
                    <td class="border td-703 font-regular text-xs  text-center"> B </td>
                    <td class="border td-703 font-regular text-xs  text-center"> C </td>
                    <td class="border td-703 font-regular text-xs  text-center">D </td>
                    <td class="border td-703 font-regular text-xs  text-center"> E </td>
                    <td class="border td-703 font-regular text-xs  text-center"> F </td>
                    <td colspan="2" class="border td-703 font-regular text-xs  text-center"> G
                    </td>
                    <td class="border td-703 font-regular text-xs  text-center"> H</td>
                    <td class="border td-703 font-regular text-xs  text-center"> I </td>
                    <td class="border td-703 font-regular text-xs  text-center"> J</td>
                    <td class="border td-703 font-regular text-xs  text-center"> K </td>
                </tr> --}}
                <tr class="text-center">
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    @foreach ($change_order_columns as $change_order_column)
                        <td class="font-regular text-xs border-r border-l td-703 text-center">
                        </td>
                    @endforeach   
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td colspan="2" class="border-b border-r border-l td-703 font-regular text-xs  text-center">
                        WORK COMPLETED </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
                </tr>
                <tr class="text-center">
                    <td style="min-width:70px" class="border-b border-r border-l td-703 font-regular text-xs  text-center">
                        ITEM
                        NO. </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        DESCRIPTION</td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        Orig. SCHEDULED
                        VALUE</td>
                    @foreach ($change_order_columns as $coKeyIndex => $change_order_column)
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center text-capitalize">
                        @php
                            if(is_numeric($coKeyIndex)) {
                                $coNumber = $coKeyIndex + 1;
                            } else {
                                $coNumber = intval($coKeyIndex) + 1;
                            }
                        @endphp
                        {{'CO ' . $coNumber}}
                    </td>
                    @endforeach    
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        TOTAL CHANGE ORDER
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        SCHEDULED VALUE
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        FROM
                        PREVIOUS APPLICATION
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        THIS PERIOD
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        MATERIALS
                        PRESENTLY
                        STORED</td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        TOTAL
                        COMPLETED AND
                        STORED TO DATE</td>
                    <td style="min-width:80px" class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        %(G ÷ C)
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        BALANCE TO
                        FINISH</td>
                    <td class="border-b border-r border-l font-regular text-xs text-center">
                        RETAINAGE
                    </td>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($particularRows as $key => $row)
                    @if($key!='no-group~')
                        <tr>
                            <td colspan="10" class="border td-703 text-left">
                                <p class="text-sm" style="color: #6F8181;">{{$key}} </p>
                            </td>
                        </tr>
                    @endif
                    @php 
                        $group_total_original_schedule_value = 0;
                        $group_total_schedule_value = 0;
                        $group_total_previously_billed_amt = 0;
                        $group_total_current_billed_amt = 0;
                        $group_total_material_stored = 0;
                        $group_total_completed = 0;
                        $group_total_retainage = 0;
                    @endphp
                    @if(isset($row['subgroup']) && $row['subgroup']!='') 
                        @foreach ($row['subgroup'] as $sk => $subgroup)
                            <tr>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"><p class="text-sm" style="color: #6F8181;">{{$sk}}</p></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                                <td class="px-2 py-2 text-left"></td>
                            </tr>
                            @php 
                                $sub_original_total_schedule_value = 0;
                                $sub_total_schedule_value = 0;
                                $sub_total_previously_billed_amt = 0;
                                $sub_total_current_billed_amount = 0;
                                $sub_total_material_stored = 0;
                                $sub_total_completed = 0;
                                $sub_total_retainage = 0;
                                
                            @endphp
                            @foreach ($subgroup as $ik => $item)
                                @include('app.merchant.invoice.Gco-listing.particular_row',array('rowArray'=>$item))
                                @php
                                    $sub_total_schedule_value = $sub_total_schedule_value + filter_var($item['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_original_total_schedule_value =  $sub_original_total_schedule_value + filter_var($item['original_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_previously_billed_amt =  $sub_total_previously_billed_amt + filter_var($item['previously_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_current_billed_amount = $sub_total_current_billed_amount + filter_var($item['current_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_material_stored = $sub_total_material_stored + filter_var($item['stored_materials'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_completed =  $sub_total_completed + filter_var($item['total_completed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_retainage = $sub_total_retainage + filter_var($item['total_outstanding_retainage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                @endphp
                            @endforeach
                            @php 
                                $group_total_schedule_value = $group_total_schedule_value + filter_var($sub_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_original_schedule_value = $group_total_original_schedule_value + filter_var($sub_original_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_previously_billed_amt = $group_total_previously_billed_amt + filter_var($sub_total_previously_billed_amt, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_current_billed_amt = $group_total_current_billed_amt + filter_var($sub_total_current_billed_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_material_stored = $group_total_material_stored + filter_var($sub_total_material_stored, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_completed = $group_total_completed + filter_var($sub_total_completed, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_retainage = $group_total_retainage + filter_var($sub_total_retainage, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            @endphp
                            <tr>
                                <td class="border td-703" style="border-right: none;">
                                    <p class="text-sm" style="color: #6F8181;"></p>
                                </td>
                                <td class="border td-703 text-left" style="border-left: none;">
                                    <p class="text-sm" style="color: #6F8181;"> {{$sk . ' sub total'}}</p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_schedule_value" /></p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_previously_billed_amt" /></p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_current_billed_amount" /></p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_material_stored" /></p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_completed" /></p>
                                </td>
                                @php
                                    if($sub_total_completed>0 && $sub_total_schedule_value>0)
                                    {
                                        $sub_total_g_by_c = $sub_total_completed / $sub_total_schedule_value;
                                    }else{
                                        $sub_total_g_by_c=0;
                                    }
                                    $sub_total_balance_to_finish = $sub_total_schedule_value - $sub_total_completed;
                                @endphp
                                <td class="border td-703 text-right" style="min-width: 90px;">
                                    <p class="text-sm">@if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c  * 100, 2) )}}) @else{{ number_format($sub_total_g_by_c * 100,2)}}@endif%</p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_balance_to_finish" /></p>
                                </td>
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$sub_total_retainage" /></p>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if(isset($row['only-group~']) && $row['only-group~']!='')
                        @foreach ($row['only-group~'] as $ok => $group)
                            @include('app.merchant.invoice.Gco-listing.particular_row',array('rowArray'=>$group,'group_name'=>$key))
                            @php
                                
                                $group_total_schedule_value = $group_total_schedule_value + filter_var($group['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_original_schedule_value = $group_total_original_schedule_value + filter_var($group['original_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_previously_billed_amt = $group_total_previously_billed_amt + filter_var($group['previously_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_current_billed_amt = $group_total_current_billed_amt + filter_var($group['current_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_material_stored = $group_total_material_stored + filter_var($group['stored_materials'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_completed = $group_total_completed + filter_var($group['total_completed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $group_total_retainage = $group_total_retainage + filter_var($group['total_outstanding_retainage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            @endphp
                        @endforeach
                    @endif
                    @if(isset($row['no-bill-code-detail~']) && !empty($row['no-bill-code-detail~']))
                        @foreach ($row['no-bill-code-detail~'] as $rk => $val)
                            <tr class="border-row">
                                <td colspan="2" class="border-r border-l td-703 text-left">
                                    <p class="text-sm">{{$key}}</p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['original_contract_amount']" /></p>
                                </td>
                                @if (isset($val['change_order_col_values']))
                                    @foreach ($val['change_order_col_values'] as $key => $change_order_col_value)
                                        <td class="border-r border-l td-703 text-right">
                                            {{$change_order_col_value}}
                                        </td>
                                    @endforeach
                                @endif
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['approved_change_order_amount']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['current_contract_amount']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['previously_billed_amount']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['current_billed_amount']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['stored_materials']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['total_completed']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm">@if($val['g_per'] < 0)({{str_replace('-','',number_format($val['g_per']  * 100, 2) )}}) @else{{ number_format($val['g_per'] * 100,2)}}@endif%</p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['balance_to_finish']" /></p>
                                </td>
                                <td class="border-r border-l td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$val['total_outstanding_retainage']" /></p>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if($key!='no-group~')
                        <tr>
                            <td colspan="2" class="border td-703 text-left">
                                <p class="text-sm" style="color: #6F8181;"> {{$key. ' sub total'}}</p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_original_schedule_value" /></p>
                            </td>
                            @php
                                $approvedCOAmount = 0;

                                foreach ($row['only-group~'] as $group) {
                                    $approvedCOAmount += $group['approved_change_order_amount'];
                                }

                                $changeOrdersGroupTotalAmount = [];
                                foreach ($change_order_columns as $change_order_column) {
                                    $coGroupTotal = 0;
                                    foreach($change_orders_group_data[$change_order_column] as $change_order_group_data) {
                                        if($change_order_group_data['group'] == $key) {
                                            $coGroupTotal += $change_order_group_data['change_order_amount'];
                                        }
                                    }
                                    $changeOrdersGroupTotalAmount[$change_order_column] = $coGroupTotal;
                                }

                            @endphp


                            @foreach ($changeOrdersGroupTotalAmount as $changeOrderGroupTotalAmount)
                                <td class="border td-703 text-right">
                                    <p class="text-sm"><x-amount-format :amount="$changeOrderGroupTotalAmount" /></p>
                                </td>
                            @endforeach
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$approvedCOAmount" /></p>
                            </td>

                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_schedule_value" /></p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_previously_billed_amt" /></p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_current_billed_amt" /></p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_material_stored" /></p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_completed" /></p>
                            </td>
                            @php
                                if($group_total_completed>0 && $group_total_schedule_value>0)
                                {
                                    $group_total_g_by_c = $group_total_completed / $group_total_schedule_value;
                                }else{
                                    $group_total_g_by_c=0;
                                }
                                $group_total_balance_to_finish = $group_total_schedule_value - $group_total_completed;
                            @endphp
                            <td class="border td-703 text-right" style="min-width: 90px;">
                                <p class="text-sm">@if($group_total_g_by_c < 0)({{str_replace('-','',number_format($group_total_g_by_c  * 100, 2) )}}) @else{{ number_format($group_total_g_by_c * 100,2)}}@endif%</p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_balance_to_finish" /></p>
                            </td>
                            <td class="border td-703 text-right">
                                <p class="text-sm"><x-amount-format :amount="$group_total_retainage" /></p>
                            </td>
                        </tr>
                    @else
                        @foreach ($row as $rk => $val)
                            @include('app.merchant.invoice.Gco-listing.particular_row',array('rowArray'=>$val))
                        @endforeach
                    @endif
                @endforeach
                    @php
                        $changeOrdersTotalAmountArray = [];
                        foreach ($change_order_columns as $change_order_column) {
                            $changeOrderGroupTotal = 0;
                            foreach($change_orders_group_data[$change_order_column] as $change_order_group_data) {
                                $changeOrderGroupTotal += $change_order_group_data['change_order_amount'];
                            }
                            $changeOrdersTotalAmountArray[$change_order_column] = $changeOrderGroupTotal;
                        }
                    @endphp
                    <tr>
                        <td style="min-width: 40px" class="border-r border-t border-l td-703 text-left">
                            <p class="text-sm"> </p>
                        </td>
                        <td style="min-width: 40px" class="border-r border-t border-l td-703 text-left">
                            <p class="text-xs"><b>GRAND TOTAL</b> </p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_original_schedule_value" /> </p>
                        </td>
                        @foreach($changeOrdersTotalAmountArray as $changeOrderTotalAmount)
                            <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                                <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$changeOrderTotalAmount" /> </p>
                            </td>
                        @endforeach
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_approved_change_order_value" /> </p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right"> 
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_schedule_value" /> </p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_previouly_billed_amt" /></p>
                        </td>
                        <td style="min-width: 90px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_current_billed_amt" /></p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_stored_material" /> </p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_total_completed" /> </p>
                        </td>
                        <td style="min-width: 40px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">@if($grand_total_g_per < 0) ({{str_replace('-','',number_format($grand_total_g_per * 100,2))}}) @else{{ number_format($grand_total_g_per * 100, 2) }} @endif%</p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_balance_to_finish" /> </p>
                        </td>
                        <td style="min-width: 70px" class="border-r border-t border-l td-703 text-right">
                            <p class="text-sm">{{ $currency_icon }}<x-amount-format :amount="$grand_total_retainge" /> </p>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <hr>
</div>