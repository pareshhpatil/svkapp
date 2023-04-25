<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        #header,
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
        }

        #header {
            top: 0;
            border-bottom: 0.1pt solid #aaa;
        }

        #footer {
            bottom: 0;
        }

        .page-number:before {
            content: "Page " counter(page);
        }

        @if($viewtype=='print') @page {
            margin-top: 0px;
            margin-bottom: 0px;
            margin-left: 20px;
            margin-right: 10px
        }

        @else @page {
            margin-top: 15px;
            margin-bottom: 15px;
            margin-left: 15px;
            margin-right: 15px
        }

        @endif body {
            margin-top: 10px;
            margin-bottom: 5px;
            margin-left: 20px;
            margin-right: 20px
        }
    </style>
</head>

<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">

    <script type="text/php">
        if (isset($pdf)) {
        if ($PAGE_COUNT > 0) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Roboto");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 3;
        $x = ($pdf->get_width() - $width - 12);
        $y = $pdf->get_height() - 25;
        $pdf->page_text($x, $y, $text, $font, $size);
        }

    }
</script>

    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <!doctype html>
        <div style="display: flex;  align-items: center; justify-content: center; background-color: #f3f4f6; padding: 8px;">

            <div id="tab" style="width: 100%; background-color: #fff; padding: 16px">

                <div style="font-size:22px;margin-top: 10px; text-align: left; font-weight: 600; color: #000">Change order Listing</div>
                <div style="margin-top: 5px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>

                <div style="margin-top: 16px; margin-bottom: 16px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%;  overflow: hidden;border:1px solid #313131" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>

                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> ITEM
                                    NO. </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> DESCRIPTION
                                    OF WORK </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> Orig. Scheduled
                                    VALUE </td>
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">
                                    BUDGET REALLOCATION </td>
                                @endif
                                @foreach ($change_order_columns as $coKeyIndex => $change_order_column)
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px;text-transform: capitalize;">
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
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">
                                    Total Change Order
                                </td>
                                <td style="border-bottom:1px solid #313131;border-left:1px solid #313131; border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">
                                    Scheduled Value
                                </td>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $changeOrderColumnCount = count($change_order_columns);
                            $noGroupColSpan = $changeOrderColumnCount + 12;
                            @endphp
                            @foreach ($particularRows as $key=>$row)
                            @if($key!='no-group~')
                            <tr>
                                <td colspan="{{$noGroupColSpan}}" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px; color: #6F8181;">{{ $key }} </div>
                                </td>
                            </tr>
                            @endif
                            @php
                            $group_total_original_schedule_value = 0;
                            $group_total_budget_reallocation = 0;
                            $group_total_schedule_value = 0;
                            @endphp
                            @if(isset($row['subgroup']) && $row['subgroup']!='')
                            @foreach ($row['subgroup'] as $sk => $subgroup)
                            <tr>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;">{{ $sk }}</div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;"></div>
                                </td>
                                @endif
                                @foreach ($change_order_columns as $change_order_column)
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                @endforeach
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                            </tr>
                            @php
                            $sub_original_total_schedule_value = 0;
                            $sub_total_schedule_value = 0;
                            @endphp
                            @foreach ($subgroup as $ik => $item)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{$item['code']}}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{$item['description']}}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$item['original_contract_amount']" /></div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$item['budget_reallocation']" /></div>
                                </td>
                                @endif
                                @if (isset($item['change_order_col_values']))
                                @foreach ($item['change_order_col_values'] as $key => $change_order_col_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$change_order_col_value" /></div>
                                </td>
                                @endforeach
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;color: #6F8181;"><x-amount-format :amount="$item['approved_change_order_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;color: #6F8181;"><x-amount-format :amount="$item['current_contract_amount']" /></div>
                                </td>

                            </tr>
                            @php
                            $sub_total_schedule_value = $sub_total_schedule_value + filter_var($item['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_budget_reallocation = $sub_total_budget_reallocation + filter_var($item['budget_reallocation'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_original_total_schedule_value = $sub_original_total_schedule_value + filter_var($item['original_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                            @endphp
                            @endforeach
                            @php
                            $group_total_schedule_value = $group_total_schedule_value + filter_var($sub_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_budget_reallocation = $group_total_budget_reallocation + filter_var($sub_total_budget_reallocation, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_original_schedule_value = $group_total_original_schedule_value + filter_var($sub_original_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                            @endphp

                            <tr>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;">{{$sk . ' sub total'}} </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group_total_original_schedule_value" /> </div>
                                </td>
                                @if($has_budget)
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group_total_budget_reallocation" /> </div>
                                </td>
                                @endif
                                @php
                                $approvedCOAmount = 0;

                                foreach ($row['only-group~'] as $group) {
                                $approvedCOAmount += $group['approved_change_order_amount'];
                                }

                                $changeOrdersGroupTotalAmount = [];
                                foreach ($change_order_columns as $change_order_column) {
                                $coGroupTotal = 0;
                                foreach($change_orders_group_data[$change_order_column] as $change_order_group_data) {
                                if($change_order_group_data['group'] == $sk) {
                                $coGroupTotal += $change_order_group_data['change_order_amount'];
                                }
                                }
                                $changeOrdersGroupTotalAmount[$change_order_column] = $coGroupTotal;
                                }

                                @endphp
                                @foreach ($changeOrdersGroupTotalAmount as $changeOrderGroupTotalAmount)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$changeOrderGroupTotalAmount" /></div>
                                </td>
                                @endforeach
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$approvedCOAmount" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> <x-amount-format :amount="$group_total_schedule_value" /></div>
                                </td>

                            </tr>
                            @endforeach
                            @endif
                            @if(isset($row['only-group~']) && $row['only-group~']!='')
                            @foreach ($row['only-group~'] as $ok => $group)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{ $group['code'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{ $group['description'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group['original_contract_amount']" /></div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group['budget_reallocation']" /></div>
                                </td>
                                @endif
                                @if (isset($group['change_order_col_values']))
                                @foreach ($group['change_order_col_values'] as $cokey => $change_order_col_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$change_order_col_value" /></div>
                                </td>
                                @endforeach
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group['approved_change_order_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$group['current_contract_amount']" /></div>
                                </td>

                            </tr>
                            @php
                            $group_total_schedule_value = $group_total_schedule_value + filter_var($group['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_original_schedule_value = $group_total_original_schedule_value + filter_var($group['original_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_budget_reallocation = $group_total_budget_reallocation + filter_var($group['budget_reallocation'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                            @endphp
                            @endforeach
                            @endif
                            @if(isset($row['no-bill-code-detail~']) && !empty($row['no-bill-code-detail~']))
                            @foreach ($row['no-bill-code-detail~'] as $rk => $val)
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{$key}}</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['original_contract_amount']" /></div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['budget_reallocation']" /></div>
                                </td>
                                @endif
                                @if (isset($val['change_order_col_values']))
                                @foreach ($val['change_order_col_values'] as $key => $change_order_col_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$change_order_col_value" /></div>
                                </td>
                                @endforeach
                                @endif
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['approved_change_order_amount']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @if($key!='no-group~')
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;">{{$key. ' sub total'}}</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$group_total_original_schedule_value" /></div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$group_total_budget_reallocation" /></div>
                                </td>
                                @endif
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
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$changeOrderGroupTotalAmount" /></div>
                                </td>
                                @endforeach
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><x-amount-format :amount="$approvedCOAmount" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> <x-amount-format :amount="$group_total_schedule_value" /></div>
                                </td>
                            </tr>
                            @else
                            @foreach ($row as $rk => $val)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{ $val['code'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">{{ $val['description'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['original_contract_amount']" /></div>
                                </td>
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['budget_reallocation']" /></div>
                                </td>
                                @endif
                                @if (isset($val['change_order_col_values']))
                                @foreach ($val['change_order_col_values'] as $key => $change_order_col_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$change_order_col_value" /></div>
                                </td>
                                @endforeach
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['approved_change_order_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                            </tr>
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
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> </div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size: 12px;font-weight: 600"><b>GRAND TOTAL</b> </div>
                                </td>
                                <td style="min-width: 70px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span> <x-amount-format :amount="$grand_total_original_schedule_value" /> </div>
                                </td>
                                @if($has_budget)
                                <td style="min-width: 70px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span> <x-amount-format :amount="$grand_total_budget_reallocation" /> </div>
                                </td>
                                @endif


                                @foreach($changeOrdersTotalAmountArray as $changeOrderTotalAmount)
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span> <x-amount-format :amount="$changeOrderTotalAmount" /> </div>
                                </td>
                                @endforeach
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span> <x-amount-format :amount="$grand_total_approved_change_order_value" /> </div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span> <x-amount-format :amount="$grand_total_schedule_value" /></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>

            </div>
        </div>
    </div>
</body>
@if ($viewtype=='print')
<script>
    window.print();
</script>
@endif

</html>