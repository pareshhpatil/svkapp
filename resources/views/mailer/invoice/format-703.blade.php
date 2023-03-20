<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
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
        body {
            font-family: Roboto;
            letter-spacing: 0;
            line-height: 75%;
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
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 3;
        $x = ($pdf->get_width() - $width - 12);
        $y = $pdf->get_height() - 25;
        $pdf->page_text($x, $y, $text, $font, $size);
        }
        @if($has_watermark)
        $w = $pdf->get_width();
        $h = $pdf->get_height();
        $pdf->set_opacity(.1,'Multiply');

        $text = "{{$watermark_text}}";
        $text = chunk_split($text, 10);
        $font = $fontMetrics->getFont('times');
        $txtHeight = $fontMetrics->getFontHeight($font, 150);
        $textWidth = $fontMetrics->getTextWidth($text, $font, 40);
            
        $x = ($w-$textWidth-400);
        $y = ($h-$txtHeight);
            
        $pdf->page_text($x, $y, $text, $font, 80,$color = array(0, 0, 0, .2), $word_space = 0.0, $char_space = 0.0, $angle = -30.0);
        @endif
    }
</script>

    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <!doctype html>
        <div style="display: flex;  align-items: center; justify-content: center; background-color: #f3f4f6; padding: 8px;">
           
            <div id="tab" style="width: 100%; background-color: #fff; padding: 16px">
                <table>
                    @if($has_aia_license)
                    <tr>
                        <td>
                            <img style="height: 40px" src="data:image/png;base64,{{$info['logo']}}" alt="">
                        </td>
                        <td>
                            <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 600; color: #000;">Document G703® – 1992</div>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td>
                            <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 600; color: #000;">Document G703 – 1992</div>
                        </td>
                    </tr>
                    @endif
                </table>
                <div style="font-size:22px;margin-top: 10px; text-align: left; font-weight: 600; color: #000">Continuation Sheet</div>
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
                                    <div style="font-size: 12px; font-weight: 600">APPLICATION NO: </div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 600">{{$info['invoice_number']?$info['invoice_number']:'NA'}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 600">APPLICATION DATE:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 600"><x-localize :date="$info['bill_date']" type="date" /></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 600">PERIOD TO:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 600">{{$info['cycle_name']}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-size: 12px; font-weight: 600">ARCHITECT’S PROJECT NO:</div>
                                </td>
                                <td style="padding-left:5px;font-size: 12px; font-weight: 600">{{$info['project_details']->project_code}}</td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                <div style="margin-top: 16px; margin-bottom: 16px; width: 100%; overflow-x: auto">
                    <table style="margin-left: auto; margin-right: auto; width: 100%;  overflow: hidden;border:1px solid #313131" cellpadding="0" cellspacing="0" role="presentation">
                        <thead>
                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> A </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> B </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> C </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">D </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> E </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> F </td>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> G </td>
                                <td style="border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> H</td>
                                <td style="border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> I </td>
                            </tr>
                            <tr style="text-align: center; color: #000">
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">WORK COMPLETED </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"></td>
                                <td style=" padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"></td>

                            </tr>

                            <tr style="text-align: center; color: #000">
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> ITEM
                                    NO. </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> DESCRIPTION
                                    OF WORK </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> SCHEDULED
                                    VALUE </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> FROM
                                    PREVIOUS APPLICATION<br />
                                    (D + E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> THIS PERIOD
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> MATERIALS
                                    PRESENTLY
                                    STORED<br />
                                    (Not in D or E) </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">TOTAL
                                    COMPLETED AND
                                    STORED TO DATE<br />
                                    (D+E+F) </td>
                                <td style="width: 60px; border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px; "> %(G ÷ C)
                                </td>
                                <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">BALANCE TO
                                    FINISH<br />
                                    (C – G) </td>
                                <td style="border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">RETAINAGE
                                    <br />(If variable rate)
                                </td>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($info['constriuction_details'] as $key=>$item)

                            @php
                            if(is_numeric($item['c']))
                            {
                            $item['c']=number_format($item['c'],2);
                            }
                            if(is_numeric($item['e']))
                            {
                            $item['e']=number_format($item['e'],2);
                            }

                            if(is_numeric($item['f']))
                            {
                            $item['f']=number_format($item['f'],2);
                            }
                            if(is_numeric($item['d']))
                            {
                            $item['d']=number_format($item['d'],2);
                            }
                            if(is_numeric($item['g']))
                            {
                            $item['g']=number_format($item['g'],2);
                            }

                            @endphp

                            @if($item['type']=='heading')
                            <tr>
                                <td colspan="10" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px; color: #6F8181;">{{ $item['b'] }} </div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='sub-heading')
                            <tr>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;">{{ $item['b'] }}</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td style="border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"></div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='sub-footer')
                            <tr>
                            
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size: 14px"></div>
                                </td>
                                <td  style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
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
                                    <div style="font-size: 14px">
                                        @if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c * 100, 2))}}) @else{{number_format($sub_total_g_by_c * 100,2)}}@endif% </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style=" border-top:1px solid #313131;border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='footer')
                            <tr>
                                <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 4px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px;color: #6F8181;"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
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
                                    <div style="font-size: 14px">
                                        @if($sub_total_g_by_c < 0)({{str_replace('-','',number_format($sub_total_g_by_c * 100, 2))}}) @else{{number_format($sub_total_g_by_c * 100,2)}}@endif% </div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style=" border-top:1px solid #313131;border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @elseif ($item['type']=='combine')
                            <tr>
                                <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> @if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">
                                        @if($item['g_per'] < 0)({{str_replace('-','',number_format($item['g_per']  * 100, 2) )}}) @else{{ number_format($item['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">@if($item['a'] < 0)({{str_replace('-','',$item['a'])}}) @else{{ $item['a'] }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px">@if($item['b'] < 0)({{str_replace('-','',$item['b'])}}) @else{{ $item['b'] }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"> @if($item['c'] < 0)({{str_replace('-','',$item['c'])}}) @else{{ $item['c'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['d'] < 0)({{str_replace('-','',$item['d'])}}) @else{{ $item['d'] }} @endif </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['e'] < 0)({{str_replace('-','',$item['e'])}}) @else{{ $item['e'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['f'] < 0)({{str_replace('-','',$item['f'])}}) @else{{ $item['f'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['g'] < 0)({{str_replace('-','',$item['g'])}}) @else{{ $item['g'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">
                                        @if($item['g_per'] < 0)({{str_replace('-','',number_format($item['g_per']  * 100, 2) )}}) @else{{ number_format($item['g_per'] * 100,2) }} @endif% </div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['h'] < 0)({{str_replace('-','',$item['h'])}}) @else{{ $item['h'] }} @endif</div>
                                </td>
                                <td style="border-bottom: solid 1px #A0ACAC; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px">@if($item['i'] < 0)({{str_replace('-','',$item['i'])}}) @else{{ $item['i'] }} @endif</div>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                            <tr>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                    <div style="font-size: 14px"> </div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                    <div style="font-size: 12px;font-weight: 600"><b>GRAND TOTAL</b> </div>
                                </td>
                                <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_c'] < 0) ({{str_replace('-','',number_format($info['total_c'],2))}}) @else{{ number_format($info['total_c'], 2) }} @endif </div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_d'] < 0) ({{str_replace('-','',number_format($info['total_d'],2))}}) @else{{ number_format($info['total_d'], 2) }} @endif </div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_e'] < 0) ({{str_replace('-','',number_format($info['total_e'],2))}}) @else{{ number_format($info['total_e'], 2) }} @endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span> @if($info['total_f'] < 0) ({{str_replace('-','',number_format($info['total_f'],2))}}) @else{{ number_format($info['total_f'], 2) }} @endif</div>
                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_g'] < 0) ({{str_replace('-','',number_format($info['total_g'],2))}}) @else{{ number_format($info['total_g'], 2) }} @endif</div>
                                </td>
                                <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">

                                    <div style="font-size: 14px">@if($info['total_c']!=0)@if($info['total_g']/$info['total_c'] < 0) ({{str_replace('-','',number_format($info['total_g']/$info['total_c'] * 100,2))}}) @else{{ number_format($info['total_g']/$info['total_c'] * 100, 2) }} @endif @else 0 @endif%</div>

                                </td>
                                <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span> @if($info['total_h'] < 0) ({{str_replace('-','',number_format($info['total_h'],2))}}) @else{{ number_format($info['total_h'], 2) }} @endif</div>
                                </td>
                                <td style=" min-width: 90px;border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                    <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_i'] < 0) ({{str_replace('-','',number_format($info['total_i'],2))}}) @else{{ number_format($info['total_i'], 2) }} @endif</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div style="margin-top: 8px">
                    <div style="line-height: 12px">
                        @if($has_aia_license)
                        <span style="font-size: 12px; font-weight: 600">AIA Document G703® – 1992. Copyright</span><span style="font-size: 12px"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                            of Architects. All rights reserved.</span><span style="font-size: 12px; color: #ef4444"> The “American
                            Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                            and “AIA Contract Documents” are registered trademarks and may not be used without
                            permission.</span><span style="font-size: 12px"> To report copyright violations of AIA Contract
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