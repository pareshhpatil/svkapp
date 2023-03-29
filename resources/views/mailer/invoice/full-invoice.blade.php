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
    line-height: 95%;
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
    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <!doctype html>


        {{-- 702 Part  --}}
        <div id="link_to_702">
            @include('mailer.invoice.format-702');
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

                    @endif

                </table>
                <div style="font-size:21px;margin-top: 10px; text-align: left; font-weight: 700; color: #000">CONTINUATION SHEET</div>
                <div style="margin-top: 5px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
                <table style="width:100%">
                    <td>
                        @if($has_aia_license)
                        <div style="font-size: 12px">AIA Document G702®, Application and Certificate for Payment, or G732™,
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
                                <td style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
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
            <p>Download File: {{$attachment['url']}}</p>
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
            <p>Download File: {{$attachment['url']}}</p>
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
            <p>Download File: {{$attachment['url']}}</p>
        </div>
    </div>
    @endforeach
    @endforeach

    @endif
    </div>

</body>

</html>