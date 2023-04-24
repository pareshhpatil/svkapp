<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
@php
$font_size ='';
if($has_schedule_value)
{
$font_size='11px;';
}

if($has_budget)
{
$font_size='11px;';
}
@endphp

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
        @if($has_watermark)
        $w = $pdf->get_width();
        $h = $pdf->get_height();

        $text = "{{$watermark_text}}";
        $text = chunk_split($text, 10);
        $font = $fontMetrics->getFont('Roboto');
        $txtHeight = $fontMetrics->getFontHeight($font, 150);
        $textWidth = $fontMetrics->getTextWidth($text, $font, 40);
            
        $x = (($w-$textWidth)/2);
        $y = (($h-$txtHeight)/1.5);
        $pdf->page_script('$pdf->set_opacity(.1, "Multiply");');
        $pdf->page_text($x, $y, $text, $font, 80,$color =array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = -30.0);
        @endif
    }
</script>
    @php
    $sub_total_schedule_value = 0;
    $sub_total_budget_reallocation = 0;
    $sub_total_change_from_previous_application = 0;
    $sub_total_change_this_period = 0;
    $sub_total_current_total = 0;
    $sub_total_previously_billed_amt = 0;
    $sub_total_current_billed_amount = 0;
    $sub_total_material_stored = 0;
    $sub_total_completed = 0;
    $sub_total_retainage = 0;
    @endphp
    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <!doctype html>
        <div style="display: flex;  align-items: center; justify-content: center; background-color: #f3f4f6; padding: 8px;">

            <div id="tab" style="width: 100%; background-color: #fff; padding: 16px">
                <table>
                    @if($has_aia_license)
                    <tr>
                        <td>
                            <img style="height: 40px" src="data:image/png;base64,{{$logo}}" alt="">
                        </td>
                        <td>
                            <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 600; color: #000;">Document G703® – 1992</div>
                        </td>
                    </tr>
                    @endif
                </table>
                <div style="font-size:22px;margin-top: 10px; text-align: left; font-weight: 600; color: #000">CONTINUATION SHEET</div>
                <div style="margin-top: 5px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
                <table style="width:100%">
                    <td>
                        @if($has_aia_license)
                        <div style="font-size:{{$font_size}} 12px">AIA Document G702®, Application and Certificate for Payment, or G732™,
                            Application and Certificate for
                            Payment, Construction Manager as Adviser Edition, containing Contractor’s signed certification
                            is attached.
                            Use Column I on Contracts where variable retainage for line items may apply. </div>
                        @endif

                    </td>
                    <td style="width:30%">
                        <table cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    <div style="font-size:{{$font_size}} 12px; font-weight: 600">APPLICATION NO: </div>
                                </td>
                                <td style="padding-left:5px;font-size:{{$font_size}} 12px; font-weight: 600">{{$invoice_number ? $invoice_number:'NA'}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size:{{$font_size}} 12px; font-weight: 600">APPLICATION DATE:</div>
                                </td>
                                <td style="padding-left:5px;font-size:{{$font_size}} 12px; font-weight: 600">
                                    @if($user_type!='merchant')
                                    <x-localize :date="$created_date" type="onlydate" :userid="$user_id" />
                                    @else
                                    <x-localize :date="$created_date" type="onlydate" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size:{{$font_size}} 12px; font-weight: 600">PERIOD TO:</div>
                                </td>
                                <td style="padding-left:5px;font-size:{{$font_size}} 12px; font-weight: 600">
                                    @if($user_type!='merchant')
                                    <x-localize :date="$bill_date" type="onlydate" :userid="$user_id" />
                                    @else
                                    <x-localize :date="$bill_date" type="onlydate" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size:{{$font_size}} 12px; font-weight: 600">ARCHITECT’S PROJECT NO:</div>
                                </td>
                                <td style="padding-left:5px;font-size:{{$font_size}} 12px; font-weight: 600">{{$project_details->project_code}}</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                <div style="margin-top: 16px; margin-bottom: 16px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%;  overflow: hidden;border:1px solid #313131" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>
                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> A </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> B </td>
                                @if($has_schedule_value)
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C1 </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C2 </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C3 </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C4 </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C1 </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C2 </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C3 </td>
                                @else
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> C </td>
                                @endif
                                @endif

                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">D </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> E </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> F </td>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> G </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> H</td>
                                <td style="border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> I </td>
                            </tr>
                            <tr style="text-align: center; color: #000">
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                @if($has_schedule_value)
                                <td colspan="4" style="border-right:1px solid #313131; border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">SCHEDULED VALUE</td>
                                @else
                                @if($has_budget)
                                <td colspan="3" style="border-right:1px solid #313131; border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">SCHEDULED VALUE</td>
                                @else
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                @endif
                                @endif
                                <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">WORK COMPLETED </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"></td>
                                <td style=" padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"></td>
                            </tr>

                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> ITEM
                                    NO. </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> DESCRIPTION
                                    OF WORK </td>
                                @if($has_schedule_value)
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    SCHEDULE VALUE </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    CHANGE FROM PREVIOUS APPLICATION </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    CHANGE THIS PERIOD </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    CURRENT (C1 + C2 + C3)</td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    SCHEDULE VALUE </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    BUDGET REALLOCATION </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">
                                    CURRENT BUDGET </td>
                                @else
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> SCHEDULED
                                    VALUE </td>
                                @endif
                                @endif
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> FROM
                                    PREVIOUS APPLICATION<br />
                                    (D + E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> THIS PERIOD
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px"> MATERIALS
                                    PRESENTLY
                                    STORED<br />
                                    (Not in D or E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">TOTAL
                                    COMPLETED AND
                                    STORED TO DATE<br />
                                    (D+E+F) </td>
                                <td style="width: 60px; border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px; "> %(G ÷ C)
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">BALANCE TO
                                    FINISH<br />
                                    (C – G) </td>
                                <td style="border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size:{{$font_size}} 12px">RETAINAGE
                                    <br />(If variable rate)
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($particularRows as $key=>$row)
                            @if($key!='no-group~')
                            <tr>
                                @if($has_schedule_value)
                                <td colspan="13" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px; color: #6F8181;">{{ $key }} </div>
                                </td>
                                @else
                                @if($has_budget)
                                <td colspan="12" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px; color: #6F8181;">{{ $key }} </div>
                                </td>
                                @else
                                <td colspan="10" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px; color: #6F8181;">{{ $key }} </div>
                                </td>
                                @endif
                                @endif
                            </tr>
                            @endif
                            @php
                            $group_total_schedule_value = 0;
                            $group_total_budget_reallocation = 0;
                            $group_total_change_from_previous_application = 0;
                            $group_total_change_this_period = 0;
                            $group_total_current_total = 0;
                            $group_total_previously_billed_amt = 0;
                            $group_total_current_billed_amt = 0;
                            $group_total_material_stored = 0;
                            $group_total_completed = 0;
                            $group_total_retainage = 0;
                            @endphp
                            @if(isset($row['subgroup']) && $row['subgroup']!='')
                            @foreach ($row['subgroup'] as $sk => $subgroup)
                            <tr>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;color: #6F8181;">{{ $sk }}</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                @else
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                            </tr>
                            @foreach ($subgroup as $ik => $item)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{$item['code']}}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{$item['description']}}</div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_contract_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['change_from_previous_application']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['change_this_period']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_total']" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_contract_amount']-$rowArray['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_contract_amount']" /></div>
                                </td>

                                @else
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_contract_amount']" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['previously_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['current_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['stored_materials']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['total_completed']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        @if($item['g_per'] < 0)({{str_replace('-','',number_format($item['g_per']  * 100, 2) )}}) @else{{ number_format($item['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['balance_to_finish']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$item['total_outstanding_retainage']" /></div>
                                </td>
                            </tr>
                            @php
                            $sub_total_schedule_value = $sub_total_schedule_value + filter_var($item['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_budget_reallocation = $sub_total_budget_reallocation + filter_var($item['budget_reallocation'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_change_from_previous_application = $sub_total_change_from_previous_application + filter_var($item['change_from_previous_application'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_change_this_period = $sub_total_change_this_period + filter_var($item['change_this_period'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_current_total = $sub_total_current_total + filter_var($item['current_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_previously_billed_amt = $sub_total_previously_billed_amt + filter_var($item['previously_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_current_billed_amount = $sub_total_current_billed_amount + filter_var($item['current_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_material_stored = $sub_total_material_stored + filter_var($item['stored_materials'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_completed = $sub_total_completed + filter_var($item['total_completed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $sub_total_retainage = $sub_total_retainage + filter_var($item['total_outstanding_retainage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            @endphp
                            @endforeach
                            @php
                            $group_total_schedule_value = $group_total_schedule_value + filter_var($sub_total_schedule_value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_budget_reallocation = $group_total_budget_reallocation + filter_var($sub_total_budget_reallocation, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_change_from_previous_application = $group_total_change_from_previous_application + filter_var($sub_total_change_from_previous_application, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_change_this_period = $group_total_change_this_period + filter_var($sub_total_change_this_period, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_current_total = $group_total_current_total + filter_var($sub_total_current_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_previously_billed_amt = $group_total_previously_billed_amt + filter_var($sub_total_previously_billed_amt, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_current_billed_amt = $group_total_current_billed_amt + filter_var($sub_total_current_billed_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_material_stored = $group_total_material_stored + filter_var($sub_total_material_stored, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_completed = $group_total_completed + filter_var($sub_total_completed, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_retainage = $group_total_retainage + filter_var($sub_total_retainage, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            @endphp
                            <tr>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size:{{$font_size}} 14px;"></div>
                                </td>
                                <td style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;color: #6F8181;">{{$sk . ' sub total'}} </div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_schedule_value" /> </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_change_from_previous_application" /> </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_change_this_period" /> </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_current_total" /> </div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_schedule_value-$sub_total_budget_reallocation" /> </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_budget_reallocation" /> </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_schedule_value" /> </div>
                                </td>
                                @else
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_schedule_value" /> </div>
                                </td>
                                @endif
                                @endif
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_previously_billed_amt" /></div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_current_billed_amount" /></div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_material_stored" /></div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_completed" /></div>
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
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        >@if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c  * 100, 2) )}}) @else{{ number_format($sub_total_g_by_c * 100,2)}}@endif%</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_balance_to_finish" /></div>
                                </td>
                                <td style=" border-top:1px solid #313131;border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$sub_total_retainage" /></div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @if(isset($row['only-group~']) && $row['only-group~']!='')
                            @foreach ($row['only-group~'] as $ok => $group)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{ $group['code'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{ $group['description'] }}</div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_contract_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['change_from_previous_application']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['change_this_period']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_total']" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_contract_amount']-$group['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_contract_amount']" /></div>
                                </td>
                                @else
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_contract_amount']" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['previously_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['current_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['stored_materials']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['total_completed']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        @if($group['g_per'] < 0)({{str_replace('-','',number_format($group['g_per']  * 100, 2) )}}) @else{{ number_format($group['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['balance_to_finish']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group['total_outstanding_retainage']" /></div>
                                </td>
                            </tr>
                            @php
                            $group_total_schedule_value = $group_total_schedule_value + filter_var($group['current_contract_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_change_from_previous_application = $group_total_change_from_previous_application + filter_var($sub_total_change_from_previous_application, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_change_this_period = $group_total_change_this_period + filter_var($sub_total_change_this_period, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_current_total = $group_total_current_total + filter_var($sub_total_current_total, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_previously_billed_amt = $group_total_previously_billed_amt + filter_var($group['previously_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_current_billed_amt = $group_total_current_billed_amt + filter_var($group['current_billed_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_material_stored = $group_total_material_stored + filter_var($group['stored_materials'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_completed = $group_total_completed + filter_var($group['total_completed'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_retainage = $group_total_retainage + filter_var($group['total_outstanding_retainage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            $group_total_budget_reallocation = $group_total_budget_reallocation + filter_var($group['budget_reallocation'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                            @endphp
                            @endforeach
                            @endif
                            @if(isset($row['no-bill-code-detail~']) && !empty($row['no-bill-code-detail~']))
                            @foreach ($row['no-bill-code-detail~'] as $rk => $val)
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{$key}}</div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['change_from_previous_application']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['change_this_period']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_total']" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']-$val['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                @else
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['previously_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['stored_materials']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['total_completed']" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        @if($val['g_per'] < 0)({{str_replace('-','',number_format($val['g_per']  * 100, 2) )}}) @else{{ number_format($val['g_per'] * 100,2)}}@endif% </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['balance_to_finish']" /></div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['total_outstanding_retainage']" /></div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @if($key!='no-group~')
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;color: #6F8181;">{{$key. ' sub total'}}</div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_schedule_value" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$grand_total_change_from_previous_application" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$grand_total_change_this_period" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$grand_total_current_total" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_schedule_value-$group_total_budget_reallocation" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_budget_reallocation" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_schedule_value" /></div>
                                </td>
                                @else
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_schedule_value" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_previously_billed_amt" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_current_billed_amt" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_material_stored" /></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_completed" /></div>
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
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        @if($group_total_g_by_c < 0)({{str_replace('-','',number_format($group_total_g_by_c  * 100, 2) )}}) @else{{ number_format($group_total_g_by_c * 100,2)}}@endif% </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_balance_to_finish" /></div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$group_total_retainage" /></div>
                                </td>
                            </tr>
                            @else
                            @foreach ($row as $rk => $val)
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{ $val['code'] }}</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;">{{ $val['description'] }}</div>
                                </td>
                                @if($has_schedule_value)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['change_from_previous_application']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['change_this_period']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_total']" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']-$val['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['budget_reallocation']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                @else
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_contract_amount']" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['previously_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['current_billed_amount']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['stored_materials']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['total_completed']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">
                                        @if($val['g_per'] < 0)({{str_replace('-','',number_format($val['g_per']  * 100, 2) )}}) @else{{ number_format($val['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['balance_to_finish']" /></div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><x-amount-format :amount="$val['total_outstanding_retainage']" /></div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            @endforeach
                            <tr>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size:{{$font_size}} 14px;"> </div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size:{{$font_size}} 12px;font-weight: 600"><b>GRAND TOTAL</b> </div>
                                </td>
                                @if($has_schedule_value)
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_schedule_value" /></div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_change_from_previous_application" /></div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_change_this_period" /></div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_current_total" /></div>
                                </td>
                                @else
                                @if($has_budget)
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_schedule_value-$grand_total_budget_reallocation" /></div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_budget_reallocation" /></div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_schedule_value" /></div>
                                </td>
                               
                                @else
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_schedule_value" /></div>
                                </td>
                                @endif
                                @endif
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_previouly_billed_amt" /></div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_current_billed_amt" /></div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_stored_material" /></div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_total_completed" /></div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;">@if($grand_total_g_per < 0) ({{str_replace('-','',number_format($grand_total_g_per * 100,2))}}) @else{{ number_format($grand_total_g_per * 100, 2) }} @endif%</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_balance_to_finish" /></div>
                                </td>
                                <td style=" min-width: 90px;border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size:{{$font_size}} 14px;"><span style="font-family:@if($currency_icon=='₹')DejaVu Sans;@endif sans-serif;">{{$currency_icon}}</span><x-amount-format :amount="$grand_total_retainge" /></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div style="margin-top: 8px">
                    <div style="line-height: 12px">
                        @if($has_aia_license)
                        <span style="font-size:{{$font_size}} 12px; font-weight: 600">AIA Document G703® – 1992. Copyright</span><span style="font-size:{{$font_size}} 12px"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                            of Architects. All rights reserved.</span><span style="font-size:{{$font_size}} 12px; color: #ef4444"> The “American
                            Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                            and “AIA Contract Documents” are registered trademarks and may not be used without
                            permission.</span><span style="font-size:{{$font_size}} 12px"> To report copyright violations of AIA Contract
                            Documents, e-mail copyright@aia.org.</span>
                        @endif
                    </div>
                </div>
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