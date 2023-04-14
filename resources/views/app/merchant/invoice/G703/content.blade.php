<div class="w-full   bg-white  shadow-2xl font-rubik m-2 p-10 watermark" style="max-width: 1400px;  color:#394242;" id="main_div">
    <!--include subheader file-->
    @include('app.merchant.invoice.view.subheader',array('title'=>'CONTINUATION SHEET','gtype'=>'G703'))

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            @if($has_aia_license)
            <p class="text-xs">AIA Document G702®, Application and Certificate for Payment, or G732™,
                Application and Certificate for
                Payment, Construction Manager as Adviser Edition, containing Contractor’s signed certification
                is attached.
                Use Column I on Contracts where variable retainage for line items may apply. </p>
            @endif
        </div>
        <div>
            <table>
                <tr>
                    <td>
                        <p class="text-xs font-bold">APPLICATION NO: </p>
                    </td>
                    <td>
                        <p class="ml-2 text-xs font-bold">
                            {{ $invoice_number ? $invoice_number : 'NA' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-xs font-bold">APPLICATION DATE: </p>
                    </td>
                    <td>
                        <p class="ml-2 text-xs font-bold">
                            @if($user_type!='merchant')
                                <x-localize :date="$created_date" type="onlydate" :userid="$user_id" />
                            @else
                                <x-localize :date="$created_date" type="onlydate" />
                            @endif
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-xs font-bold">PERIOD TO: </p>
                    </td>
                    <td>
                        <p class="ml-2 text-xs font-bold">
                            @if($user_type!='merchant')
                                <x-localize :date="$bill_date" type="onlydate" :userid="$user_id" />
                            @else
                                <x-localize :date="$bill_date" type="onlydate" />
                            @endif
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-xs font-bold">ARCHITECT’S PROJECT NO:</p>
                    </td>
                    <td>
                        <p class=" ml-2 text-xs font-bold">{{$project_details->project_code}}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class='overflow-x-auto w-full mt-4 mb-4'>
        <table class='mx-auto w-full border-collapse border border-[#A0ACAC] overflow-hidden'>
            <thead>
                <tr class="text-center">
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
                </tr>
                <tr class="text-center">
                    <td class="font-regular text-xs border-r border-l td-703 text-center">
                    </td>
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
                </tr>
                <tr class="text-center">
                    <td style="min-width:70px" class="border-b border-r border-l td-703 font-regular text-xs  text-center">
                        ITEM
                        NO. </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        DESCRIPTION
                        OF WORK </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        SCHEDULED
                        VALUE </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        FROM
                        PREVIOUS APPLICATION
                        <br />(D + E)
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        THIS PERIOD
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        MATERIALS
                        PRESENTLY
                        STORED<br />
                        (Not in D or E) </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        TOTAL
                        COMPLETED AND
                        STORED TO DATE<br />
                        (D+E+F) </td>
                    <td style="min-width:80px" class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        %(G ÷ C)
                    </td>
                    <td class="border-b border-r border-l td-703 font-regular text-xs text-center">
                        BALANCE TO
                        FINISH<br />
                        (C – G) </td>
                    <td class="border-b border-r border-l font-regular text-xs text-center">
                        RETAINAGE
                        <br />(If variable rate)
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
                                $sub_total_schedule_value = 0;
                                $sub_total_previously_billed_amt = 0;
                                $sub_total_current_billed_amount = 0;
                                $sub_total_material_stored = 0;
                                $sub_total_completed = 0;
                                $sub_total_retainage = 0;
                            @endphp
                            @foreach ($subgroup as $ik => $item)
                                @include('app.merchant.invoice.G703.particular_row',array('rowArray'=>$item))
                                @php
                                    $sub_total_schedule_value = $sub_total_schedule_value + filter_var($item['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_previously_billed_amt =  $sub_total_previously_billed_amt + filter_var($item['previously_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_current_billed_amount = $sub_total_current_billed_amount + filter_var($item['current_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_material_stored = $sub_total_material_stored + filter_var($item['stored_materials'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_completed =  $sub_total_completed + filter_var($item['total_completed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                    $sub_total_retainage = $sub_total_retainage + filter_var($item['total_outstanding_retainage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                @endphp
                            @endforeach
                            @php 
                                $group_total_schedule_value = $group_total_schedule_value + filter_var($sub_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
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
                            @include('app.merchant.invoice.G703.particular_row',array('rowArray'=>$group,'group_name'=>$key))
                            @php 
                                $group_total_schedule_value = $group_total_schedule_value + filter_var($group['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
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
                            @include('app.merchant.invoice.G703.particular_row',array('rowArray'=>$val))
                        @endforeach
                    @endif
                @endforeach
                    <tr>
                        <td style="min-width: 40px" class="border-r border-t border-l td-703 text-left">
                            <p class="text-sm"> </p>
                        </td>
                        <td style="min-width: 40px" class="border-r border-t border-l td-703 text-left">
                            <p class="text-xs"><b>GRAND TOTAL</b> </p>
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
    <div class="mt-2">
        @if($has_aia_license)
        <p class="leading-3"><span class="text-xs font-bold">AIA Document G703® – 1992. Copyright</span><span class="text-xs"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                of Architects. All rights reserved.</span><span class="text-xs text-red-500"> The “American
                Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                and “AIA Contract Documents” are registered trademarks and may not be used without
                permission.</span><span class="text-xs"> To report copyright violations of AIA Contract
                Documents, e-mail copyright@aia.org.</span></p>
        @endif
    </div>
</div>