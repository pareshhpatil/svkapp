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
  <![endif]-->    <style>
.checked-border-blue-600:checked {
    border-color: #2563eb !important;
}
.checked-bg-blue-600:checked {
    background-color: #2563eb !important;
}
.focus-outline-none:focus {
    outline: 2px solid transparent !important;
    outline-offset: 2px !important;
}
@if($viewtype=='print')
@page { margin-top: 0px;margin-bottom:0px;margin-left: 20px;margin-right: 10px  }
@else
@page { margin-top: 10px;margin-bottom:10px;margin-left: 15px;margin-right: 15px  }
@endif
body { margin-top: 10px;margin-bottom:5px;margin-left: 20px;margin-right: 20px }

</style>

<style>
    @font-face {
    font-family: 'Rubik';
   
  
    src: url({{ storage_path('fonts\Roboto-Bold.ttf') }}) format("truetype");
     font-weight:700;
}
body{
    font-family: 'Roboto', sans-serif;
    letter-spacing: 0px;
    @if($has_aia_license)
    line-height: 92%;
    @else
    line-height: 105%;
    @endif
}
    </style>

</head>


<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; ">
<script type="text/php">
    if (isset($pdf)) {
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
        $pdf->page_text($x, $y, $text, $font, 80,$color = array(0, 0, 0), $word_space = 0.0, $char_space = 0.0, $angle = -30.0);
        @endif
    }
</script>
  <div role="article" aria-roledescription="email" aria-label="" lang="en" > 
    <div style="display: flex;  align-items: center; justify-content: center; background-color: #f3f4f6; padding: 0px">
                
      <div style="display: flex; align-items: center; justify-content: center; background-color: #f3f4f6; padding: 0">
        <div id="tab" style="width: 100%; background-color: #fff; padding: @if($viewtype=='print')20px @else 10px        
        @endif;">
            <table >
                @if($has_aia_license)
                    <tr>
                        <td>
                            <img style="height: 40px" src="data:image/png;base64, {{$info['logo']}}" alt="">
                        </td>
                        <td>
                            <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 700; color: #000;font-size:24px;">Document G702® – 1992</div>
                        </td>
                    </tr>
                @endif

            </table>
            <div style="font-size:15px;margin-top: 10px; text-align: left; font-weight: 600; color: #000">APPLICATION AND CERTIFICATE FOR PAYMENT</div>
            <div style="margin-top: 3px; height: 2px; width: 100%; background-color: #111827;margin-bottom: 10px;"></div>     
                   <div>
                <table width="100%" >
                    <tr>
                        <td style="font-size: 12px; font-weight: 700" width="25%">
                           TO OWNER:  
                        </td>
                        <td style="font-size: 12px;font-weight: 700 " width="25%">
                            PROJECT:   
                        </td>
                        <td width="25%" style="font-size: 12px;font-weight: 700 ">
                            APPLICATION NO: {{$info['invoice_number']?$info['invoice_number']:'NA'}}
                        </td>
                        <td width="25%" style="text-align: right;font-size: 12px;font-weight: 700 ">
                           Distribution to:</td>
                    </tr>
                    <tr>
                        <td width="25%"  rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                            {{$metadata['customer'][1]['value'] }}<br/>
                            {{$info['project_details']->owner_address}}
                        </td>
                        <td width="25%"  rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                            {{$info['project_details']->project_name}}<br/>
                            {{$info['project_details']->project_address}}
                        </td>
                        <td width="25%" style="text-align: left;font-size: 12px;font-weight: 700 ">
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
                        <td width="25%" style="font-size: 12px; font-weight: 700">
                         FROM CONTRACTOR: 
                        </td>
                        <td width="25%" style="font-size: 12px;font-weight: 700 ">
                            VIA ARCHITECT:
                        </td>
                        <td width="25%" style="text-align: left;font-size: 12px; font-weight: 700">
                          CONTRACT DATE: <x-localize :date="$info['project_details']->contract_date" type="date" />
                        </td>
                        <td width="25%" style="text-align: right;">
                           
                            <span style="margin-right: 8px; font-size: 12px">CONTRACTOR </span> <span style="border:1px solid gray;font-size: 10px;color:white">bb </span>
                         
                            
                               
                                                   </td>
                                                    </tr>
                    <tr>
                        <td width="25%" rowspan="2" style="vertical-align: baseline;font-size: 12px;">
                            {{$metadata['header'][0]['value'] }} <br/>
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
                         
                            
                               
                                                   </td>   </tr>
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
                    <div style="font-size: 15px;font-weight: 600;margin-top: 3px;margin-bottom: 3px;">CONTRACTOR’S APPLICATION FOR PAYMENT</div>
                            @if($has_aia_license)
                                <div style="font-size: 12px">Application is made for payment, as shown below, in connection with the Contract.
                                    AIA Document G703®, Continuation Sheet, is attached.</div>
                            @endif

                        @php $contract_sum_to_date = $info['total_original_contract']+$info['last_month_co_amount']+$info['this_month_co_amount'] @endphp
                        <table style="width:100%">
                            <tr>
                            <td >
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">1. ORIGINAL CONTRACT SUM  </div>
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">2. NET CHANGE BY CHANGE ORDERS  </div>
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">3. CONTRACT SUM TO DATE <span style="font-weight: 300;font-style: italic;">(Line 1 ± 2)</span>  </div>
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">4. TOTAL COMPLETED & STORED TO DATE<span style="font-weight: 300;font-style: italic;"> (Column G on G703)</span> 
                         </div>                    </td>
                            <td style="width: 30%;text-align: right">

                               <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_original_contract'] < 0)({{str_replace('-','',number_format($info['total_original_contract'],2))}})@else{{number_format($info['total_original_contract'],2)}}@endif</div>
                               <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif</div>
                               <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if( $contract_sum_to_date < 0)({{str_replace('-','',number_format($contract_sum_to_date,2))}}) @else{{number_format(($contract_sum_to_date),2)}}@endif</div>
                               <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_g'] < 0)({{str_replace('-','',number_format($info['total_g'],2))}}) @else{{number_format($info['total_g'],2)}}@endif</div>  

                            </td>
                        </tr>
                            </table>
                            <table style="width:100%">
                                <tr>
                                <td >
                                    @php
                            $cper=0;
                            if(($info['total_d']+$info['total_e']) <= 0){
                            $cper=0;}
                            else{
                            $cper=round((($info['total_i']/($info['total_d']+$info['total_e']))*100));}

                            $a5=0;
                            $single_per=($info['total_d']+$info['total_e'])/100;
                            $a5=$single_per*$cper;


                        @endphp
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">5. RETAINAGE: </div>
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">a. <span style="border-bottom-width: 1px; border-color: #4b5563; font-weight: 300">{{number_format($info['percent_rcw'],2)}}</span><span style="font-weight: 300;"> % of Completed Work</span> <span style="font-weight: 300;font-style: italic;"> (Columns D + E on G703)</span>  </div>
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">b. <span style="border-bottom-width: 1px; border-color: #4b5563; font-weight: 300">  {{number_format($info['percent_rasm'],2)}} </span><span style="font-weight: 300"> % of Stored Material </span><span style="font-weight: 300;font-style: italic;">(Column F on G703)</span>  </div>
                        <div style="margin-top: 8px; font-size: 12px; font-weight: 300">Total Retainage <span style="font-weight: 300;font-style: italic;">(Lines 5a + 5b, or Total in Column I of G703)</span>
                         </div>             
                        </td>
                            <td style="width: 30%;text-align: right"> 
                               <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($info['total_retainage_amount'],2) < 0)({{str_replace('-','',number_format($info['total_retainage_amount'],2))}}) @else{{number_format($info['total_retainage_amount'],2)}}@endif</div>
                               <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['total_rasm'] < 0) ({{str_replace('-','',number_format($info['total_rasm'],2))}})@else{{number_format($info['total_rasm'],2)}}@endif</div>
                               @php $total_retainage = $info['total_retainage']; if($total_retainage == 0) $total_retainage = $info['total_i']; @endphp
                               <div style="margin-top: 8px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($total_retainage,2) < 0)({{str_replace('-','',number_format($total_retainage,2))}}) @else{{number_format(($total_retainage),2)}}@endif</div>
                            </td>
                            </tr>
                            </table>
                            <table style="width:100%">
                                <tr>
                                <td >
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">6. TOTAL EARNED LESS RETAINAGE </div>
                        <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 4 minus Line 5 Total)</div>
                                        </td>
                            <td style="width: 30%;text-align: right"> 
                            @php $total_earned_less_retain = $info['total_g']-($total_retainage)  @endphp
                           <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if( $total_earned_less_retain < 0)({{str_replace('-','',number_format( $total_earned_less_retain , 2))}}) @else{{number_format( $total_earned_less_retain ,2)}}@endif</div>  
                            </td>
                            </tr>    
                        </table>
                        <table style="width:100%">
                            <tr>
                            <td >
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">7. LESS PREVIOUS CERTIFICATES FOR PAYMENT </div>
                        <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 6 from prior Certificate)</div> 
                            </td>
                          
                        <td style="width: 30%;text-align: right">
                                                                <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['less_previous_certificates_for_payment'] < 0)({{str_replace('-','',number_format($info['less_previous_certificates_for_payment'],2))}}) @else{{number_format($info['less_previous_certificates_for_payment'],2)}}@endif</div>   
                            </td>
                          
                            </tr>               
                                           </table>
                                           <table style="width:100%">
                                            <tr>
                                            <td >
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">8. CURRENT PAYMENT DUE </div>  
                                            </td>
                            <td style="width: 30%;text-align: right"> 
                         <div style="margin-top: 0px; border: 1px solid; padding-top: 4px; padding-bottom: 4px; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['grand_total'] < 0)0.00 @else{{number_format($info['grand_total'],2)}}@endif</div> 
                            </td>
                        </tr>
                                                </table>
                                                <table style="width:100%">
                                                    <tr>
                                                    <td >
                        <div style="margin-top: 0px; font-size: 12px; font-weight: 700">9. BALANCE TO FINISH, INCLUDING RETAINAGE </div>
                        <div style="margin-left: 16px; font-size: 12px;font-style: italic;">(Line 3 minus Line 6)</div>
                                                    </td>
                            <td style="width: 30%;text-align: right">  
                            @php $balance_to_finish = $contract_sum_to_date - $total_earned_less_retain; @endphp
                                                              <div style="margin-top: 16px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700">  <span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($balance_to_finish < 0) ({{str_replace('-','',number_format($balance_to_finish,2))}}) @else{{number_format($balance_to_finish,2)}}@endif</div>     
                            </td>
                        </tr>
                            </table>
                        <table border="1" style="margin-top: 8px; width: 100%;  overflow: hidden; 
                        border: 1px solid gray; " cellpadding="0" cellspacing="0" role="presentation">
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
                                <td style="text-align: right;font-size: 12px;padding: 2px;">TOTAL </td>
                                @php
                                    $tt= $info['total_co_amount_positive'];
                                    $tt1= $info['total_co_amount_negative'];
                                    
                                @endphp
                                <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($tt,2)}} </td>
                                <td style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(number_format($tt1,2)<0)({{str_replace('-','',number_format($tt1,2))}}) @else{{number_format($tt1,2)}} @endif</td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px;padding: 2px;">NET CHANGES by Change Order </td>
                                <td colspan="2" style="font-size: 12px;padding: 2px;text-align: right;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif </td>                            </tr>
                        </table>   
                    </td>
                    <td style="width:50%; padding-left: 10px;">


                    <div style="font-size: 12px">The undersigned Contractor certifies that to the best of the Contractor’s knowledge, information
and belief the Work covered by this Application for Payment has been completed in accordance
with the Contract Documents, that all amounts have been paid by the Contractor for Work for
which previous Certificates for Payment were issued and payments received from the Owner, and
that current payment shown herein is now due.</div>
<div style="margin-top: 5px; font-size: 14px; font-weight: 700">CONTRACTOR:</div>
<table style="width:100%">
    
    </table>
<div style="margin-top: 0px; font-size: 12px">State of:</div>
<div style="margin-top: 3px; font-size: 12px">County of:</div>
<div style="margin-top: 3px; font-size: 12px">Subscribed and sworn to before me this        <span style="margin-left: 32px"> day of</span></div>
<div style="margin-top: 3px; font-size: 12px">Notary Public:</div>
<div style="margin-top: 0px; font-size: 12px">My commission expires:</div>
<div style="margin-top: 2px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
<div style="font-size: 16px;font-weight: 600;margin-top: 5px;">ARCHITECT’S CERTIFICATE FOR PAYMENT</div>
<div style="margin-top:3px;font-size: 12px">In accordance with the Contract Documents, based on on-site observations and the data comprising this application, the Architect certifies to the Owner that to the best of the Architect’s knowledge,
    information and belief the Work has progressed as indicated, the quality of the Work is in
    accordance with the Contract Documents, and the Contractor is entitled to payment of the
    AMOUNT CERTIFIED. </div>  
    <table style="width:100%">
        <tr>
        <td style="width: 70%" >
    <div style="margin-top: 0px; font-size: 12px; font-weight: 700">AMOUNT CERTIFIED</div>
        </td>
        <td style="width: 30%"> 
                       <div style="margin-top: 0px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 700;text-align: right"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>@if($info['grand_total'] < 0)0.00  @else{{number_format($info['grand_total'],2)}} @endif</div>  
        </td>
        </tr>
    </table>  
      <div style="margin-top: 0px; font-size: 12px; font-weight: 300;font-style: italic;">(Attach explanation if amount certified differs from the amount applied. Initial all figures on this
        Application and on the Continuation Sheet that are changed to conform with the amount certified.)</div>
		 <div style="margin-top: 0; font-size: 12px; font-weight: 700">ARCHITECT:</div>
		<table style="width:100%">
			<tr>
				<td style="width: 70%">
					<div style="margin-top: 10px;margin-bottom: 5px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 400"> By: </div>
				</td>
				<td style="width: 30%">
					<div style="margin-top: 10px;margin-bottom: 5px;border-bottom: 1px solid gray; font-size: 12px; font-weight: 400">Date: </div>
				</td>
			</tr>
		</table>
        
        <div style="margin-top: 0px; font-size: 12px">This Certificate is not negotiable. The AMOUNT CERTIFIED is payable only to the Contractor
            named herein. Issuance, payment and acceptance of payment are without prejudice to any rights of
            the Owner or Contractor under this Contract.</div>
        </td>  
    </tr>  
            
    </table>    
            
            <div style="margin-top: 0px; height: 2px; width: 100%; background-color: #111827"></div>
            <div style="margin-top: 4px">
                @if($has_aia_license)
                <div style="line-height: 12px">
                    <span style="font-size: 12px"><b>AIA Document G702® – 1992. Copyright</b> © 1953, 1963, 1965, 1971, 1978, 1983 and 1992 by The American Institute of Architects. All rights reserved.</span><span style="font-size: 12px; color: #ef4444"> The “American Institute of Architects,” “AIA,” the AIA Logo, “G702,” and
                    “AIA Contract Documents” are registered trademarks and may not be used without permission.</span><span style="font-size: 12px"> To report copyright violations of AIA Contract Documents, e-mail copyright@aia.org.</span>
                </div>
                @endif
            </div>
        </div>
    </div>  </div></div>
</body>
@if ($viewtype=='print')
<script>
    window.print();

    </script>
@endif
</html>
