<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">--}}
    {{-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>--}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">--}}
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings xmlns:o="urn:schemas-microsoft-com:office:office">
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style>
        td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
    </style>
    <![endif]-->
    <style>
    {{--@font-face {--}}
    {{-- font-family: 'Roboto';--}}
    {{-- font-style: normal;--}}


    {{-- src: url({{ storage_path('fonts\Roboto-Bold.ttf') }}) format("truetype");--}}
    {{-- font-weight: 600;--}}
    {{--}--}}

    @if($viewtype=='print')
    @page {
    margin: 0 10px 0 20px;
    }
    @else
    @page {
    margin: 15px;
    }
    @endif

    body{
    font-family: 'Roboto', sans-serif;
    letter-spacing: 0;
    line-height: 93%;
    margin: 10px 20px 5px;
    }

    .page-break {
    page-break-after: always;
    }

    .toc-list-item-link {

    text-decoration: none;
    color: #3E4AA3;
    }
    .toc-lists {
    list-style-type: none;
    font-weight: 600;
    }

    .toc-wrapper {
    padding: 50px;
    font-family: 'Roboto', sans-serif;
    color: #5C6B6B;
    }

    .toc-wrapper .title {
    font-size: 28px;
    text-align: center;
    }

    .toc-wrapper .title-toc {
    margin: 20px 0;
    }

    .toc-list > li {
    line-height: 1.2;
    margin: 10px 0;
    font-size: 18px;
    }

    #link_to_702, #link_to_703 {
    font-family: 'Roboto', sans-serif;
    }

    .attachment-title {
    font-family: 'Roboto', sans-serif;
    margin: 20px 0;
    font-size: 24px;
    }

    .attachment-item {
    font-family: 'Roboto', sans-serif;
    margin: 20px 20px 30px;
    text-align: center;
    }

    .attachment-item-wrapper {
    width: 70%;
    height: 70%;
    }

    .attachment-item-image {
    max-width: 80%;
    max-height: 80%;
    }
    .toc-item{
    font-weight: 400;
    font-size: 16px;
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

    </style>

</head>

<body style="margin: 0; width: 100%; padding: 0;">
<script type="text/php">
    if (isset($pdf)) {
        if ($PAGE_COUNT > 0) {
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
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
        $font = $fontMetrics->getFont('times');
        $txtHeight = $fontMetrics->getFontHeight($font, 150);
        $textWidth = $fontMetrics->getTextWidth($text, $font, 40);
            
        $x = (($w-$textWidth)/2);
        $y = (($h-$txtHeight)/1.5);
            
        $pdf->page_script('$pdf->set_opacity(.1, "Multiply");');
        $pdf->page_text($x, $y, $text, $font, 80,$color =array(0,0,0), $word_space = 0.0, $char_space = 0.0, $angle = -30.0);
        @endif
    }
</script>
    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <!doctype html>


        {{-- 702 Part  --}}
        <div id="link_to_702">
            <div style="display: flex; background-color: #f3f4f6;">
                <div id="tab" style="width: 100%; background-color: #fff; padding: 0 10px 5px;">
                    <table>
                        @if($has_aia_license)
                            <tr>
                                <td>
                                    <img style="height: 38px" src="data:image/png;base64, {{$info['logo']}}" alt="">
                                </td>
                                <td>
                                    <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 700; color: #000;">Document G702® – 1992</div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 700; color: #000;">Document G702® – 1992</div>
                                </td>
                            </tr>
                        @endif

                    </table>
                    <div style="font-size:21px;margin-top: 10px; text-align: left; font-weight: 700; color: #000">Application and Certificate for Payment </div>
                    <div style="margin-top: 3px; height: 2px; width: 100%; background-color: #111827"></div>
                    <div>
                        <table width="100%">
                            <tr>
                                <td style="font-size: 12px; font-weight: 700" width="25%">
                                    TO OWNER:
                                </td>
                                <td style="font-size: 12px;font-weight: 700 " width="25%">
                                    PROJECT:
                                </td>
                                <td width="25%" style="font-size: 12px;font-weight: 700 ">
                                    APPLICATION NO: {{ $info['invoice_number'] ?? 'NA' }}
                                </td>
                                <td width="25%" style="text-align: right;font-size: 12px;font-weight: 700 ">
                                    Distribution to:</td>
                            </tr>
                            <tr>
                                <td width="25%" rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                                    {{$metadata['customer'][1]['value'] }}<br />
                                    {{$info['project_details']->owner_address}}
                                </td>
                                <td width="25%" rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                                    {{$info['project_details']->project_name}}<br />
                                    {{$info['project_details']->project_address}}
                                </td>
                                <td width="25%" style="text-align: left;font-size: 12px;font-weight: 700;">
                                    PERIOD TO: {{ $info['cycle_name'] }}
                                </td>
                                <td width="25%" style="text-align: right;">
                                    <span style="margin-right: 8px; font-size: 12px">OWNER </span> <span style="border:1px solid gray;font-size: 10px;color:white">bb </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" style="text-align: left;font-size: 12px;font-weight: 700 ">
                                    CONTRACT FOR:
                                </td>
                                <td width="25%" style="text-align: right">
                                    <span style="margin-right: 8px; font-size: 12px">ARCHITECT </span> <span style="border:1px solid gray;font-size: 10px;color:white">bb </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" style="font-size: 12px; font-weight: 700;">
                                    FROM CONTRACTOR:
                                </td>
                                <td width="25%" style="font-size: 12px;font-weight: 700;">
                                    VIA ARCHITECT:
                                </td>
                                <td width="25%" style="text-align: left;font-size: 12px; font-weight: 700;">
                                    CONTRACT DATE: <x-localize :date="$info['project_details']->contract_date" type="date" />
                                </td>
                                <td width="25%" style="text-align: right;">
                                    <span style="margin-right: 8px; font-size: 12px;">CONTRACTOR </span> <span style="border:1px solid gray;font-size: 10px;color:white;">bb </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                                    {{$metadata['header'][0]['value'] }} <br />
                                    {{$info['project_details']->contractor_address}}
                                </td>
                                <td width="25%" rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                                    {{$info['project_details']->architect_address}}
                                </td>
                                <td width="25%" style="text-align: left;font-size: 12px;font-weight: 700 ">
                                    PROJECT NOS: {{$info['project_details']->project_code}}
                                </td>
                                <td width="25%" style="text-align: right;">
                                    <span style="margin-right: 8px; font-size: 12px">FIELD </span> <span style="border:1px solid gray;font-size: 10px;color:white">bb </span>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" style="text-align: left">

                                </td>
                                <td width="25%" style="text-align: right;">
                                    <span style="margin-right: 8px; font-size: 12px">OTHER </span> <span style="border:1px solid gray;font-size: 10px;color:white">bb </span>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div style="margin-top: 1px; height: 2px; width: 100%; background-color: #111827"></div>
                    <table style="width:100%">
                        <tr>
                            <td style="width:50%; padding-right: 10px;">
                                <h4 style="font-size: 13px;font-weight: 700;margin-top: 3px;margin-bottom: 3px;">CONTRACTOR’S APPLICATION FOR PAYMENT</h4>
                                @if($has_aia_license)
                                    <div style="font-size: 12px">Application is made for payment, as shown below, in connection with the Contract.
                                        AIA Document G703®, Continuation Sheet, is attached.</div>
                                @else
                                    <div style="font-size: 12px">Application is made for payment, as shown below, in connection with the Contract.
                                        Document G703, Continuation Sheet, is attached.</div>
                                @endif
                                @php $contract_sum_to_date = $info['total_original_contract']+$info['last_month_co_amount']+$info['this_month_co_amount'] @endphp
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            <div style="margin-top: 0; font-size: 10px; font-weight: 700">1. ORIGINAL CONTRACT SUM </div>
                                            <div style="margin-top: 0; font-size: 10px; font-weight: 700">2. NET CHANGE BY CHANGE ORDERS </div>
                                            <div style="margin-top: 0; font-size: 10px; font-weight: 700">3. CONTRACT SUM TO DATE <span style="font-weight: 300;font-style: italic;">(Line 1 ± 2)</span> </div>
                                            <div style="margin-top: 0; font-size: 10px; font-weight: 700">4. TOTAL COMPLETED & STORED TO DATE<span style="font-weight: 300;font-style: italic;"> (Column G on G703)</span>
                                            </div>
                                        </td>

                                        <td style="width: 30%;text-align: right;">
                                            <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_original_contract'] < 0)({{str_replace('-','',number_format($info['total_original_contract'],2))}})@else{{number_format($info['total_original_contract'],2)}}@endif</div>
                                                    <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif </div>
                                                            <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if( $contract_sum_to_date < 0)({{str_replace('-','',number_format($contract_sum_to_date,2))}}) @else{{number_format(($contract_sum_to_date),2)}}@endif</div>
                                                                    <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_g'] < 0)({{str_replace('-','',number_format($info['total_g'],2))}}) @else{{number_format($info['total_g'],2)}}@endif</div>
                                        </td>
                                    </tr>
                                </table>
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            @php
                                            $cper=0;
                                            if(($info['total_d']+$info['total_e']) <= 0){ $cper=0;} else{ $cper=round((($info['total_i']/($info['total_d']+$info['total_e']))*100));} $a5=0; $single_per=($info['total_d']+$info['total_e'])/100; $a5=$single_per*$cper; @endphp <div style="margin-top: 0; font-size: 12px; font-weight: 700">5. RETAINAGE:
                </div>
                <div style="margin-top: 0; font-size: 12px; font-weight: 700">a. <span style="border-bottom-width: 1px; border-color: #4b5563; font-weight: 300">{{number_format($info['percent_rcw'],2)}}</span><span style="font-weight: 300;"> % of Completed Work</span> <span style="font-weight: 300;font-style: italic;"> (Columns D + E on G703)</span> </div>
                <div style="margin-top: 0; font-size: 12px; font-weight: 700">b. <span style="border-bottom-width: 1px; border-color: #4b5563; font-weight: 300"> {{number_format($info['percent_rasm'],2)}} </span><span style="font-weight: 300"> % of Stored Material </span><span style="font-weight: 300;font-style: italic;">(Column F on G703)</span> </div>
                <div style="margin-top: 8px; font-size: 12px; font-weight: 300">Total Retainage <span style="font-weight: 300;font-style: italic;">(Lines 5a + 5b, or Total in Column I of G703)</span>
                </div>
                </td>
                <td style="width: 30%;text-align: right;">
                    <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($info['total_retainage_amount'],2) < 0)({{str_replace('-','',number_format($info['total_retainage_amount'],2))}}) @else{{number_format($info['total_retainage_amount'],2)}}@endif</div>
                            <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($info['total_rasm'],2) < 0)({{str_replace('-','',number_format($info['total_rasm'],2))}})@else{{number_format($info['total_rasm'],2)}}@endif</div>
                                    @php $total_retainage = $info['total_retainage']; if($total_retainage == 0) $total_retainage = $info['total_i']; @endphp
                                    <div style="margin-top: 8px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($total_retainage,2) < 0)({{str_replace('-','',number_format($total_retainage,2))}}) @else{{number_format(($total_retainage),2)}}@endif</div>
                </td>
                </tr>
                </table>
                <table style="width:100%">
                    <tr>
                        <td>
                            <div style="margin-top: 0; font-size: 12px; font-weight: 700">6. TOTAL EARNED LESS RETAINAGE </div>
                            <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 4 minus Line 5 Total)</div>
                        </td>
                        <td style="width: 30%;text-align: right;">
                            @php $total_earned_less_retain = $info['total_g']-($total_retainage) @endphp
                            <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if( $total_earned_less_retain < 0)({{str_replace('-','',number_format( $total_earned_less_retain , 2))}}) @else{{number_format( $total_earned_less_retain ,2)}}@endif</div>
                        </td>
                    </tr>
                </table>
                <table style="width:100%">
                    <tr>
                        <td>
                            <div style="margin-top: 0; font-size: 12px; font-weight: 700">7. LESS PREVIOUS CERTIFICATES FOR PAYMENT </div>
                            <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 6 from prior Certificate)</div>
                        </td>

                        <td style="width: 30%;text-align: right;">
                            <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['less_previous_certificates_for_payment'] < 0)({{str_replace('-','',number_format($info['less_previous_certificates_for_payment'],2))}}) @else{{number_format($info['less_previous_certificates_for_payment'],2)}}@endif</div>
                        </td>

                    </tr>
                </table>
                <table style="width:100%">
                    <tr>
                        <td>
                            <div style="margin-top: 0; font-size: 12px; font-weight: 700">8. CURRENT PAYMENT DUE </div>
                        </td>
                        <td style="width: 30%;text-align: right;">
                            <div style="margin-top: 0; border: 1px solid; padding-top: 4px; padding-bottom: 4px; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['grand_total'] < 0)0.00 @else{{number_format($info['grand_total'],2)}}@endif</div>
                        </td>
                    </tr>
                </table>
                <table style="width:100%">
                    <tr>
                        <td>
                            <div style="margin-top: 0; font-size: 12px; font-weight: 700">9. BALANCE TO FINISH, INCLUDING RETAINAGE </div>
                            <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 3 minus Line 6)</div>
                        </td>
                        <td style="width: 30%;text-align: right;">
                            @php $balance_to_finish = $contract_sum_to_date - $total_earned_less_retain; @endphp
                            <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700"> <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($balance_to_finish < 0) ({{str_replace('-','',number_format($balance_to_finish,2))}}) @else{{number_format($balance_to_finish,2)}}@endif</div>
                        </td>
                    </tr>
                </table>
                <table border="1" style="margin-top: 8px; width: 100%; overflow: hidden;
                        border: 1px solid gray;" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td style="padding: 2px;font-size: 12px">CHANGE ORDER SUMMARY </td>
                        <td style="font-size: 12px;padding: 2px;">ADDITIONS </td>
                        <td style="font-size: 12px;padding: 2px;">DEDUCTIONS </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;padding: 2px;">Total changes approved in previous months by Owner</td>
                        <td style="padding: 2px;font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['last_month_co_amount_positive']>=0){{number_format($info['last_month_co_amount_positive'],2)}}@else{{0}} @endif </td>
                        <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['last_month_co_amount_negative']<0)({{str_replace('-','',number_format($info['last_month_co_amount_negative'],2))}})@else{{0}} @endif</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;padding: 2px;">Total approved this month </td>
                        <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['this_month_co_amount_positive']>=0){{number_format($info['this_month_co_amount_positive'],2)}}@else{{0}} @endif</td>
                        <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['this_month_co_amount_negative']<0)({{str_replace('-','',number_format($info['this_month_co_amount_negative'],2))}})@else{{0}} @endif</td>
                    </tr>
                    <tr>
                        <td style=" text-align: right;font-size: 12px;padding: 2px;">TOTAL </td>
                        @php
                            $tt= $info['total_co_amount_positive'];
                            $tt1= $info['total_co_amount_negative'];
                        @endphp
                        <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($tt,2)}} </td>
                        <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($tt1,2)<0)({{str_replace('-','',number_format($tt1,2))}}) @else{{number_format($tt1,2)}} @endif</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;padding: 2px;">NET CHANGES by Change Order </td>
                        <td colspan="2" style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif </td>
                    </tr>
                </table>
                </td>
                <td style="width:50%; padding-left: 10px;">
                    <div style="font-size: 12px">The undersigned Contractor certifies that to the best of the Contractor’s knowledge, information
                        and belief the Work covered by this Application for Payment has been completed in accordance
                        with the Contract Documents, that all amounts have been paid by the Contractor for Work for
                        which previous Certificates for Payment were issued and payments received from the Owner, and
                        that current payment shown herein is now due.</div>
                    <div style="margin-top: 5px; font-size: 13px; font-weight: 700">CONTRACTOR:</div>
                    
                    <div style="margin-top: 0; font-size: 10px">State of:</div>
                    <div style="margin-top: 3px; font-size: 10px">County of:</div>
                    <div style="margin-top: 3px; font-size: 10px">Subscribed and sworn to before me this <span style="margin-left: 32px"> day of</span></div>
                    <div style="margin-top: 3px; font-size: 10px">Notary Public:</div>
                    <div style="margin-top: 0; font-size: 10px">My commission expires:</div>
                    <div style="margin-top: 2px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
                    <div style="font-size: 13px;font-weight: 700;margin-top: 5px;">ARCHITECT’S CERTIFICATE FOR PAYMENT</div>
                    <div style="margin-top:3px;font-size: 10px">In accordance with the Contract Documents, based on on-site observations and the data comprising this application, the Architect certifies to the Owner that to the best of the Architect’s knowledge,
                        information and belief the Work has progressed as indicated, the quality of the Work is in
                        accordance with the Contract Documents, and the Contractor is entitled to payment of the
                        AMOUNT CERTIFIED. </div>
                    <table style="width:100%">
                        <tr>
                            <td style="width: 70%">
                                <div style="margin-top: 0; font-size: 12px; font-weight: 700">AMOUNT CERTIFIED</div>
                            </td>
                            <td style="width: 30%">
                                <div style="margin-top: 0;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['grand_total'] < 0)0.00 @else{{number_format($info['grand_total'],2)}} @endif</div>
                            </td>
                        </tr>
                    </table>
                    <div style="margin-top: 0; font-size: 12px; font-weight: 300;font-style: italic;">(Attach explanation if amount certified differs from the amount applied. Initial all figures on this
                        Application and on the Continuation Sheet that are changed to conform with the amount certified.)</div>
                    
                    <div style="margin-top: 0; font-size: 12px">This Certificate is not negotiable. The AMOUNT CERTIFIED is payable only to the Contractor
                        named herein. Issuance, payment and acceptance of payment are without prejudice to any rights of
                        the Owner or Contractor under this Contract.</div>
                </td>
                </tr>
                </table>

                <div style="margin-top: 0; height: 2px; width: 100%; background-color: #111827"></div>
                <div style="margin-top: 2px">
                    <div style="line-height: 10px">
                        @if($has_aia_license)
                            <span style="font-size: 10px"><b>AIA Document G702® – 1992. Copyright</b> © 1953, 1963, 1965, 1971, 1978, 1983 and 1992 by The American Institute of Architects. All rights reserved.</span>
                            <span style="font-size: 10px; color: #ef4444"> The “American Institute of Architects,” “AIA,” the AIA Logo, “G702,” and
                                “AIA Contract Documents” are registered trademarks and may not be used without permission.</span>
                            <span style="font-size: 10px"> To report copyright violations of AIA Contract Documents, e-mail copyright@aia.org.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    {{-- 703 Part  --}}
    <div id="link_to_703">
        <div style="display: flex; background-color: #f3f4f6;">
            <div id="tab-703" style="width: 100%; background-color: #fff;padding: 0 10px 5px;">
                <table>
                    @if($has_aia_license)
                        <tr>
                            <td>
                                <img style="height: 40px" src="data:image/png;base64, {{$info['logo']}}" alt="">
                            </td>
                            <td>
                                <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 700; color: #000;">Document G703® – 1992</div>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 700; color: #000;">Document G703 – 1992</div>
                            </td>
                        </tr>
                    @endif

                </table>
                <div style="font-size:21px;margin-top: 10px; text-align: left; font-weight: 700; color: #000">Continuation Sheet</div>
                <div style="margin-top: 5px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
                <table style="width:100%">
                    <td>
                        @if($has_aia_license)
                            <div style="font-size: 12px">AIA Document G702®, Application and Certificate for Payment, or G732™,
                                Application and Certificate for
                                Payment, Construction Manager as Adviser Edition, containing Contractor’s signed certification
                                is attached.
                                Use Column I on Contracts where variable retainage for line items may apply. </div>
                        @else
                            <div style="font-size: 12px">Document G702, Application and Certificate for Payment, or G732™,
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
                                    <div style="font-size: 12px; font-weight: 700">APPLICATION NO: </div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 700">{{$info['invoice_number']?$info['invoice_number']:'NA'}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 700">APPLICATION DATE:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 700"><x-localize :date="$info['bill_date']" type="date" /></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 700">PERIOD TO:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 700">{{$info['cycle_name']}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 700">ARCHITECT’S PROJECT NO:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 700">{{$info['project_details']->project_code}}</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                <div style="margin-top: 8px; margin-bottom: 8px; width: 100%;">
                    <table style="margin-left: auto; margin-right: auto; width: 100%; overflow: hidden;border:1px solid #313131" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>
                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> A </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> B </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> C </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px">D </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> E </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> F </td>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> G </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> H</td>
                                <td style="border-bottom:1px solid #313131; padding: 8px 2px; text-align: center; font-size: 12px"> I </td>
                            </tr>
                            <tr style="text-align: center; color: #000">
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px">WORK COMPLETED </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"></td>
                                <td style="padding: 8px 2px;text-align: center; font-size: 12px"></td>

                            </tr>

                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> ITEM
                                    NO. </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> DESCRIPTION
                                    OF WORK </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> SCHEDULED
                                    VALUE </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> FROM
                                    PREVIOUS APPLICATION<br />
                                    (D + E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> THIS PERIOD
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> MATERIALS
                                    PRESENTLY
                                    STORED<br />
                                    (Not in D or E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px">TOTAL
                                    COMPLETED AND
                                    STORED TO DATE<br />
                                    (D+E+F) </td>
                                <td style="min-width: 70px; border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px"> %(G ÷ C)
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px">BALANCE TO
                                    FINISH<br />
                                    (C – G) </td>
                                <td style="border-bottom:1px solid #313131; padding: 8px 2px;text-align: center; font-size: 12px">RETAINAGE
                                    <br />(If variable rate)
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($info['constriuction_details'] as $key => $item)
                            @if($item['type']=='heading')
                            <tr>
                                <td colspan="10" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px; color: #6F8181;">{{ $item['b'] }} </div>
                                </td>
                            </tr>
                            
                            @elseif ($item['type']=='sub-heading')
                            <tr>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px;color: #6F8181;">{{ $item['b'] }}</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px"></div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='sub-footer')
                            <tr>
                            
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size: 13px"></div>
                                </td>
                                <td  style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px;color: #6F8181;"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
                                </td>
                                @php
                                $sub_total_g = filter_var($item['g'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $sub_total_c = filter_var($item['c'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                if($sub_total_g>0 && $sub_total_c>0)
                                {
                                $sub_total_g_by_c = $sub_total_g / $sub_total_c;
                                }else{
                                $sub_total_g_by_c=0;
                                }
                                @endphp
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">
                                        @if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c * 100, 2))}}) @else{{number_format($sub_total_g_by_c * 100,2)}}@endif% </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style=" border-top:1px solid #313131;border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='footer')
                            <tr>
                            <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 13px;color: #6F8181;"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
                                </td>
                                @php
                                $sub_total_g = filter_var($item['g'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                $sub_total_c = filter_var($item['c'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                                if($sub_total_g>0 && $sub_total_c>0)
                                {
                                    $sub_total_g_by_c = $sub_total_g / $sub_total_c;
                                }else{
                                    $sub_total_g_by_c=0;
                                }
                                @endphp
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">
                                        @if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c * 100, 2))}}) @else{{ number_format($sub_total_g_by_c * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='combine')
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: left">
                                    <div style="font-size: 13px"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">
                                        @if($item['g_per'] < 0)({{str_replace('-','',number_format($item['g_per']  * 100, 2) )}}) @else{{ number_format($item['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 13px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding: 8px 2px;text-align: right">
                                    <div style="font-size: 13px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: left">
                                    <div style="font-size: 13px">@if($item['a'] < 0)({{str_replace('-','',$item['a'])}}) @else{{ $item['a'] }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: left">
                                    <div style="font-size: 13px">@if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"> @if($item['c'] < 0)({{str_replace('-','',number_format($item['c'],2))}}) @else{{ number_format($item['c'],2) }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ number_format($item['d'],2) }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['e'] < 0)({{str_replace('-','',number_format($item['e'],2))}}) @else{{ number_format($item['e'],2) }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['f'] < 0)({{str_replace('-','',number_format($item['f'],2))}}) @else{{ number_format($item['f'],2) }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['g'] < 0)({{str_replace('-','',number_format($item['g'],2))}}) @else{{number_format($item['g'],2) }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">
                                        @if($item['g_per'] < 0)({{str_replace('-','',number_format($item['g_per']  * 100, 2) )}}) @else{{ number_format($item['g_per'] * 100,2) }}@endif% </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i']}} @endif</div>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            <tr>
                                <td style="min-width: 50px;border-right:1px solid #313131; border-top:1px solid #313131; padding: 8px 2px; text-align: left">
                                    <div style="font-size: 13px"> </div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131; border-top:1px solid #313131; padding: 8px 2px; text-align: center">
                                    <div style="font-size: 12px;font-weight: 600"><b>GRAND TOTAL</b> </div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_c'] < 0)({{str_replace('-','',number_format($info['total_c'],2))}})@else{{number_format($info['total_c'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_d'] < 0)({{str_replace('-','',number_format($info['total_d'],2))}})@else{{number_format($info['total_d'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131; border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_e'] < 0)({{str_replace('-','',number_format($info['total_e'],2))}})@else{{number_format($info['total_e'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_f'] < 0)({{str_replace('-','',number_format($info['total_f'],2))}})@else{{number_format($info['total_f'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_g'] < 0)({{str_replace('-','',number_format($info['total_g'],2))}})@else{{number_format($info['total_g'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px">@if($info['total_c']!=0)@if($info['total_g']/$info['total_c'] < 0)({{str_replace('-','',number_format($info['total_g']/$info['total_c'] * 100,2))}})@else{{number_format($info['total_g']/$info['total_c'] * 100, 2) }}@endif @else 0 @endif%</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_h'] < 0)({{str_replace('-','',number_format($info['total_h'],2))}})@else{{number_format($info['total_h'], 2)}}@endif</div>
                                </td>
                                <td style="min-width: 90px;border-top:1px solid #313131; padding: 8px 2px; text-align: right">
                                    <div style="font-size: 13px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_i'] < 0)({{str_replace('-','',number_format($info['total_i'],2))}})@else{{number_format($info['total_i'], 2)}}@endif</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 0; height: 2px; width: 100%; background-color: #111827"></div>
                <div style="margin-top: 4px">
                    <div style="line-height: 12px">
                        @if($has_aia_license)
                        <span style="font-size: 10px; font-weight: 700">AIA Document G703® – 1992. Copyright</span><span style="font-size: 10px"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                            of Architects. All rights reserved.</span><span style="font-size: 10px; color: #ef4444"> The “American
                            Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                            and “AIA Contract Documents” are registered trademarks and may not be used without
                            permission.</span><span style="font-size: 10px"> To report copyright violations of AIA Contract
                            Documents, e-mail copyright@aia.org.</span>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    

    @if(count($info['invoice_attachments']) > 0)
    {{-- Attachment Pages --}}
    @foreach($info['invoice_attachments'] as $k => $attachment)
    <div class="page-break"></div>
    <div id="{{ $k .'-'.$attachment['fileNameSlug'] }}">
        <div class="attachment-item">
            <h3 class="attachment-title">{{$attachment['fileName']}}</h3>
            <br />
            @if($attachment['fileType'] == 'jpeg' || $attachment['fileType'] == 'jpg' || $attachment['fileType'] == 'png')
            <br />
            <br />
            <br />
            <br />
            <div class="attachment-item-wrapper">
                <img class="attachment-item-image" src="data:image/{{$attachment['fileType']}};base64, {{$attachment['fileContent']}}" alt="">
            </div>

            <br />
            @endif
            <p>Download File:  {{$attachment['url']}}</p> 
        </div>
    </div>
    @endforeach
    @endif
    @if(count($info['mandatory_document_attachments']) > 0)
    {{-- Attachment Pages --}}
    @foreach($info['mandatory_document_attachments'] as $k => $attachment)
    @if($attachment['fileType'] != 'pdf')
    <div class="page-break"></div>
    <div id="{{ $k .'-'.$attachment['fileNameSlug'] }}">
        <div class="attachment-item">
            <h3 class="attachment-title">{{$attachment['fileName']}}</h3>
            <br />
            @if($attachment['fileType'] == 'jpeg' || $attachment['fileType'] == 'jpg' || $attachment['fileType'] == 'png')
            <br />
            <br />
            <br />
            <br />
            <div class="attachment-item-wrapper">
                <img class="attachment-item-image" src="data:image/{{$attachment['fileType']}};base64, {{$attachment['fileContent']}}" alt="">
            </div>

            <br />
            @endif
            <p>Download File:  {{$attachment['url']}}</p> 
        </div>
    </div>
    @endif
    @endforeach
    @endif
    <!-- Bill Code Attachments -->
    @if(count($info['bill_code_attachments']) > 0)
    {{-- Attachment Pages --}}
    @foreach($info['bill_code_attachments'] as $k => $bill_code)
    @foreach($bill_code["attachments"] as $j => $attachment)
    <div class="page-break"></div>
    <div id="{{ $j .'-'.$attachment['fileNameSlug'] }}">
        <div class="attachment-item">
            <h3 class="attachment-title">{{$bill_code['billCode']}} - {{$bill_code['billName']}} | {{$attachment['fileName']}}</h3>
            <br />
            @if($attachment['fileType'] == 'jpeg' || $attachment['fileType'] == 'jpg' || $attachment['fileType'] == 'png')
            <br />
            <br />
            <br />
            <br />
            <div class="attachment-item-wrapper">
                <img class="attachment-item-image" src="data:image/{{$attachment['fileType']}};base64, {{$attachment['fileContent']}}" alt="">
            </div>
            <br />
            @endif
            <p>Download File:  {{$attachment['url']}}</p> 
        </div>
    </div>
    @endforeach
    @endforeach

    @endif
    </div>
    
</body>

</html>