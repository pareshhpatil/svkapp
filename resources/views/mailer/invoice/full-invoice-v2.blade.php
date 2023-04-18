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
    <style>
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

        .toc-list>li {
            line-height: 1.2;
            margin: 10px 0;
            font-size: 18px;
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

        .toc-item {
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

<body>
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
    {{-- 702 Part  --}}
    <div id="link_to_702" style="line-height: 82%">
        @include('mailer.invoice.format-702-v2')
    </div>
    <div class="page-break"></div>
    {{-- 703 Part  --}}
    <div id="link_to_703">
        @include('mailer.invoice.format-703-v2')
    </div>
    {{-- Co Listing  --}}
{{--    @if($list_all_change_orders)--}}
{{--        @if(!isset($has_change_order_data))--}}
{{--            <div class="page-break"></div>--}}
{{--            <div id="link_to_703">--}}
{{--                @include('mailer.invoice.format-co-listing-v2')--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    @endif--}}

    @if(count($invoice_attachments) > 0)
    {{-- Attachment Pages --}}
    @foreach($invoice_attachments as $k => $attachment)
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
    @if(count($mandatory_document_attachments) > 0)
    {{-- Attachment Pages --}}
    @foreach($mandatory_document_attachments as $k => $attachment)
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
    @if(count($bill_code_attachments) > 0)
    {{-- Attachment Pages --}}
    @foreach($bill_code_attachments as $k => $bill_code)
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