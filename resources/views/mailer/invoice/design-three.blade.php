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

  @if ($viewtype=='print')
  @isset($info['signature']['font_file'])
    <link href="{{$info['signature']['font_file']}}" rel="stylesheet">
@endisset
@endif


  @php
 if(isset($info['signature']['font_name']))
 {
     $name=$info['signature']['font_name'];
     $fontname='fonts\\'.str_replace(' ', '-', $name).'.ttf';
 }

 @endphp

   <style>
  @isset($info['signature']['font_name'])
    @font-face { 
font-family: '{{$info['signature']['font_name']}}';
src: url({{ storage_path($fontname) }}) format("truetype"); 
}
@endisset  

.m-2 {
    margin: 8px !important;
}
.min-h-screen {
    min-height: 100vw !important;
}
.items-center {
    align-items: center !important;
}
.drop-shadow {
    --tw-drop-shadow: drop-shadow(0 1px 2px rgb(0 0 0 / 0.1)) drop-shadow(0 1px 1px rgb(0 0 0 / 0.06)) !important;
    filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow) !important;
}
@media (max-width: 600px) {
    .sm-w-full {
        width: 100% !important;
    }
}
</style></head>
<body style="min-width:380px;word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">
  <div role="article" aria-roledescription="email" aria-label="" lang="en"> <table style="width: 100%"  role="presentation">
                <tr>
                  <td align="center" style="@if($viewtype=='mailer')padding-top: 30px; padding-bottom: 30px;@endif background-color: #F7F8F8">
                     <table class="sm-w-full" style="width: 700px"  role="presentation">
                <tr>
                  <td style="border: 1px solid gray; background-color: #fff; padding: 0">
                 <table width="100%">
                    <td style="padding: 16px">

                  
                    @php
                    $last_payment=NULL;
                  $adjustment=NULL;
                  $adjustment_col="Adjustment";
                  $previous_due_col="Previous due";
              @endphp

                  @php
                    $inv="";
                    $invnm="INVOICE";
                     @endphp 

                      @foreach ($metadata['invoice'] as $item)
                      @if($item['function_id']=='9')
                      @php
                      $inv=$item['value'];
                      $invnm=$item['column_name'];
                  @endphp
                  @endif
                  @endforeach
                    <table style="width: 100%;border-spacing: 0;"  role="presentation">
            <tr>
                <td style="vertical-align: top;width:36%">
                    @if(isset($info['image_path']) && !empty($info['image_path']))
                    <img @if($viewtype=='mailer') width="190" @endif @if($viewtype!='mailer')src="data:image/png;base64,{{$info['logo']}}" @else src="{{env('APP_URL')}}/uploads/images/logos/{{$info['image_path']}}" @endif style=" object-fit: scale-down;max-width:140px" alt="">
                      @endif    
                </td> 
                
                <td style="width:29%;background-color: #e5e7eb; vertical-align: bottom; text-align: center; padding-right: 5px">
                    <p style="font-size: 14px; color: #374151">{{$invnm}}  <span style="margin-left: 4px; font-size: 14px; font-weight: 700; color: #000">{{$inv}}</span></p>      
                          </td>             
                             <td style="width:35%;background-color: {{$info['design_color']}}; padding: 10px">
                    <table style="width: 100%"  role="presentation">
                 
                        @foreach ($metadata['invoice'] as $key=>$item)
                        @if($item['value']!="" && $item['function_id']!=9)
                       
                       
                         @if($item['function_id']==12)
                           @php
                               $adjustment=$item['value'];
                             $adjustment_col=$item['column_name']; @endphp
                         @elseif ($item['function_id']==4)
                         @php $previous_due_col=$item['column_name']; @endphp
                            @else
                            <tr >
                                <td style="border-bottom: 1px solid gray; text-align: right; padding: 2px">
                                    <div style="font-size: 14px; text-transform: uppercase; color: #fff">{{$item['column_name']}}</div>

                    {{-- start --}}
                         @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                        <a target="_BlANK" style="color:white"  href="{{$item['value']}}">{{$item['column_name']}}</a>
                        @else
                        @if ($item['datatype']=='money')

                        <span style="font-size: 14px; font-weight: 700; text-transform: uppercase; color: #fff"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($item['value'],2)}} </span>
                     
                        @elseif($item['datatype']=='date')

                        <span style="font-size: 14px; font-weight: 700; text-transform: uppercase; color: #fff"><x-localize :date="$item['value']" type="date" /></span>
                     
                        @else
                          <span style="font-size: 14px; font-weight: 700; text-transform: uppercase; color: #fff">{{$item['value']}} @if($item['datatype']=='percent') %@endif</span>
                        @endif
                       @endif
                   {{-- end --}}
                </td>
                            </tr>
                    @endif
                
                        @endif
                        @endforeach
                       
                       
                 
                        
                        
            
                </table>            </td>
        </tr>
        </table>
            <table style="margin-top: 10px; width: 100%"  role="presentation">
                <tr>
                <td>
                     <div style="font-size: 14px; text-transform: uppercase; color: #4b5563">Supplier</div>
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">{{$metadata['header'][0]['value'] }}</div>
                    @if ($info['main_company_name']!='')
                    <span style="font-size: 12px;"> (An official franchisee of {{$info['main_company_name']}})</span><br>
              @endif
              @foreach ($metadata['header'] as $item)
              @if($item['column_name']=='Merchant address' && $item['value']!='')
             {{$item['value'] }}<br>
              @endif
                @endforeach
                @foreach ($metadata['header'] as $item)
                @if($item['column_name']!='Company name' && $item['value']!='' && $item['column_name']!='Merchant address')
               {{$item['value'] }} <br>
                @endif
                @endforeach
                </td>
                <td style="vertical-align: top;border-left: 1px solid gray; padding-left: 20px;width: 50%;">
                    <div style="font-size: 14px; text-transform: uppercase; color: #4b5563">Client</div>
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">{{$metadata['customer'][1]['value'] }}</div>
                    @foreach ($metadata['customer'] as $key=>$item)
                    @if($key!=1 && $item['value']!="")
                    {{$item['value']}}<br>
                    @endif
                    @endforeach

                                </td>
            </tr>
        </table>  
        @if ($info['footer_note']!='' || isset($info['payment_mode'])) 
                 <div style="border-top: 1px dotted gray; border-bottom: 1px dotted gray; margin-top: 16px" >
           @else
           <div style="border-top: 1px dotted gray; margin-top: 16px" >
        @endif
           
                    <table style="width: 100%"  role="presentation">
                <tr>
                    @isset($info['payment_mode'])
                <td width="50%">
                    @isset($info['payment_mode'])
                   <div style="font-size: 14px; text-transform: uppercase">Payment Method: <span style="margin-left: 16px; font-weight: 700">{{$info['payment_mode']}}</span></div> 
                  @endisset
                  @isset($info['transaction_id'])
                   <div style="margin-top: 8px; font-size: 14px; text-transform: uppercase">Transaction Number:<span style="margin-left: 16px; font-weight: 700">{{$info['transaction_id']}}</span></div>
                   @endisset
                </td>
                @endisset
               

                <td>                     <p style="font-size: 16px; text-transform: uppercase">  {{$info['footer_note']}}</p>
                </td>
                </tr>
            </table>
            @if ($info['footer_note']!='' || isset($info['payment_mode'])) 
        </div>  
      @else
    </div>  
   @endif
             

                      <div style="margin-top: 16px; width: 100%; overflow-x: auto">
            @php
            $sr_no=1;
            $total=0;
                    $taxtotal=0;
        @endphp  
        @if (!empty($table_heders)) 
            <table  style="border-spacing: 0; margin-left: auto; margin-right: auto; width: 100%; overflow: hidden;  border-radius: 8px; background-color: #fff" cellpadding="0"  role="presentation">
                    <thead>
                        <tr >
                            @foreach ($table_heders as $item )
                            <th style="min-width:60px;border-bottom: 2px solid {{$info['design_color']}}; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align:center;font-size: 14px; font-weight: 700; color: {{$info['design_color']}}"> {{$item}} </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($metadata['particular'] as $key=>$item)
                        <tr>
                            @foreach ($table_heders as $k=>$h )
                            <td style="min-width:60px;border-bottom: 1px solid gray; padding-top: 8px; padding-bottom: 8px; text-align:center;">
                                @if ($k=='sr_no')
                                 {{$sr_no}} 
                              @else
                             {{$item[$k]}} 
                             @endif
                             </td>
                           @endforeach
                        </tr>
                        @php
                        $sr_no=$sr_no+1;
                    @endphp
                        @endforeach

                        @php
                                        $counts= count($table_heders);
                                        $addval=0;
                                        if($counts%2!=0)
                                             $addval=0.5;
                                            
                                     @endphp

                      <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                        <td colspan="{{(count($table_heders)/2)+$addval}}"  style="width:50%;vertical-align: top;" > 
                            <div style="width: 90%;padding-top:5px;">
 @isset($info['narrative'])
                                        @if($info['narrative']!='')
                                       
                                        <div style="font-size: 14px;padding-top:5px;"><b>Narrative</b></div>
                                      <span style="font-size: 14px;padding-top:5px;"> {{$info['narrative']}}</span> <br>
                                        @endif
                                         @endisset 
                                        @isset($info['tnc'])
                                        @if($info['tnc']!='')
                                       
                                        <div style="font-size: 14px;padding-top:7px;"><b>Terms & conditions</b></div>
                                        <span style="font-size: 14px;padding-top:5px;"> {!! str_replace("<p class='text-sm mt-1'>", ' ', $info['tnc']) !!} </span> <br>
                                      
                                        @endif
                                         @endisset 
                                        </div> 
                                    </td>
                                    <td colspan="{{(count($table_heders)/2)-$addval}}"  style="vertical-align: top;">
                                      <table style="border-spacing: 0;width:100%;">
                                        <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
             
                                            <td colspan="2" style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                                <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Sub Total</div>
                                             </td>
                                            <td style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                                <div style="font-weight: 700;font-size: 14px; color: #374151"><strong style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</strong>{{$info['basic_amount']}}</div>
                                                @isset($info['invoice_product_taxation'])
                      @if($info['invoice_product_taxation']==2)
                      
                       <div style="
                        font-size: 12px;
                        color: gray;
                        text-transform: lowercase;
                    "> (Inclusive of tax)</div>
                      @endif
                      @endisset
                                                </td> </tr>
                                                 @foreach ($metadata['tax'] as $key=>$item)
                                          <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                                                                                 <td colspan="2" style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                                <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">{{$item['tax_name']}}</div>
                                             </td>
                                            <td style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                                <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{$item['tax_amount']}}</div>
                                                 </td>
                                                 </tr>
                                                 @php
                                                 $taxtotal=$taxtotal+$item['tax_amount'];
                                              @endphp
                                                 @endforeach
                                                 @php
                                                 $total=$info['basic_amount']+$info['tax_amount'];
                                                
                                            @endphp
                                          <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                                                                               <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                                <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Total</div>
                                             </td>
                                            <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                                <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($total,2)}}</div>
                                                 </td>                                      
                                                </tr>
                                                @isset($info['previous_due'])
                    @if($info['previous_due']>0)
                                                <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                                                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                     <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">{{$previous_due_col}}</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                     <div style="font-size: 14px; color: #374151;font-weight: 700;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['previous_due'],2)}}</div>
                      </td>                                      
                     </tr>
                     @endif
                     @endisset
                     @if($adjustment>0)
                     <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                        <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">{{$adjustment_col}}</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($adjustment,2)}}</div>
                    </td>                                      
                    </tr>
                    @endif
                    @isset($info['advance'])
                    @if($info['advance']>0)
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Advance Received</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['advance'],2)}}</div>
                    </td>                                      
                    </tr>
                    @endif
                    @endisset
                    @isset($info['paid_amount'])
                    @if($info['paid_amount']>0)
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Paid Amount</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['paid_amount'],2)}}</div>
                    </td>                                      
                    </tr>
                    @endif
                    @endisset
                    
                    @isset($info['late_fee'])
                    @if($info['late_fee']>0)
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Late Fee</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['late_fee'],2)}}</div>
                    </td>                                      
                    </tr>
                    @endif
                    @endisset
                    
                    @isset($metadata['plugin']['has_coupon'])
                    @if($metadata['plugin']['has_coupon'])
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B"> Coupon Discount</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151">0.00</div>
                    </td>                                      
                    </tr>
                    @endif
                    @endisset
                    @if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
                    
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color: #5C6B6B"> GRAND TOTAL</div>
                    </td>
                    <td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                    <div style="font-weight: 700;font-size: 14px; color: #374151"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['absolute_cost'],2)}}</div>
                    </td>                                      
                    </tr>
                    @endif
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                        <td colspan="2" style="background: {{$info['design_color']}};border-bottom: 1px solid {{$info['design_color']}};padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                        <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color: white;font-weight: 700;"> Amount Due</div>
                        </td>
                        <td style="background: {{$info['design_color']}};border-bottom: 1px solid {{$info['design_color']}};padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                        <div style="font-size: 14px; color: white;font-weight: 700;"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{number_format($info['absolute_cost'],2)}}</div>
                        </td>                                      
                        </tr>
                    <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                    <td colspan="3" style="padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                    <div style="flex: 1 1 0%; font-size: 14px; color: rgb(39, 38, 38)"><b>Amount(in words) :</b> {{$info['absolute_cost_words']}}</div>
                    </td>
                                         
                    </tr>
                                      </table>
                                    </td>
                      </tr>
                                    


                          
                          </tbody>
                </table>
               @endif 
                 </div>    
            
                <table style="width: 100%"  role="presentation">
                    @isset($metadata['plugin']['has_signature'])
                    @if($metadata['plugin']['has_signature']!=1)
                    <tr>
                        <td >
                            <div style="font-size: 13px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                                <br>
                                <b style="color:black"> Note: </b> This is a system generated invoice. No signature required.
                            </div>
                        </td>
                    </tr>
                    @endif
        @endisset
        @if($viewtype!='mailer')
        @if (isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file']==1)
       <tr> 
            <td >
                
                    <div style="float:right;margin-right: 2px;">
                        <img src="/images/default_digital_signature.png" style="max-height: 100px;">
                    </div>
               
            </td>
        </tr>
        @else
        @if (isset($info['signature']))
        <tr> 
            <td  style="text-align:{{$info['signature']['align']}};">
                    <div >
                        @if($info['signature']['type']=='font')
                            <label
                                style="font-family: '{{$info['signature']['font_name']}}',cursive;font-size: {{$info['signature']['font_size']}}px;">{{$info['signature']['name']}}</label>
                        @else
                            <img src="data:image/png;base64,{{$info['signimg']}}" style="max-height: 100px;">
                        @endif
                    </div>
            </td>
        </tr>
            @endif
            @endif 
            @endif
                </table>
            </td></table>          
                
            @if ($viewtype=='mailer')
                                <div style="margin-bottom: 16px">
            <table style="width: 100%" cellpadding="0" cellspacing="0" role="presentation">
                
                <tr>
                    <td colspan="6" style="padding-left: 12px; padding-right: 8px">
                        <div style="font-size: 13px; color: #6e605d; ">
                            <br>
                            <b> Please note: No extra charges are applicable for paying online. </b>                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="float: right;padding-top: 10px; font-size: 15px; color: #fb735d; line-height: 20px; text-align: right; padding-left: 8px; padding-right: 12px"> 
                      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                        <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #18aebf; border-radius: 0px; text-align: center;" valign="top" bgcolor="#18aebf" align="center"> <a href="{{$info['paylink']}}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #18aebf; border: solid 1px #18aebf; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px;  margin: 0; padding: 6px 15px; text-transform: capitalize; border-color: #18aebf;" >Pay now</a> </td>
                            <td style="padding:2px"></td>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #f99636; border-radius: 0px; text-align: center;" valign="top" bgcolor="#f99636" align="center"> <a href="{{$info['savepdfurl']}}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #f99636; border: solid 1px #f99636; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px;  margin: 0; padding: 6px 15px; text-transform: capitalize; border-color: #f99636;" >  Save as PDF</a> </td>
                       
                        </tr>
                    </table>
                    
                    
                    </td>
                    </tr>
                    <tr>
                    <td style="padding: 7px"></td>
                    </tr>
                    @if($info['paid_user']==0)
                    <tr>
                    <td colspan="6" style="vertical-align: middle;padding: 2px; color: #5b4d4b; color: #ffffff; line-height: 30px; text-align: left; font-size: 12px; background-color: #18aebf">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin-left:15px;border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                        <tr>
                           
                            <td style="color: #ffff;font-family: sans-serif; font-size: 12px; vertical-align: top; background-color: #18aebf; border-radius: 0px; text-align: center;" valign="top" bgcolor="#18aebf" align="center">If you would like to collect online payments for your business,<a href="https://www.swipez.in/merchant/register" target="_blank" style="display: inline-block; color: #4444ef; background-color: #18aebf; border: solid 1px #18aebf; border-radius: 0px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 12px;  margin: 0; padding: 4px 4px; text-transform: capitalize; border-color: #18aebf;" >register now</a>on Swipez.</td>
                       
                        </tr>
                    </table>
                    </td>
                    </tr>
                    @endif
                <tr>
                    <td colspan="6" style="font-size: 15px; color: #fb735d; line-height: 30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" role="presentation">
                            <tbody><tr>
                                <td valign="top">
                                    <div style="font-size: 13px; color: #6e605d; padding-left: 15px; padding-right: 15px">
                                        If you are having trouble viewing this invoice in your email, you can use this link to view
                                        the same invoice
                                        <a style="color: #4444ef; text-decoration: underline" href="{{$info["patron_url"]}}" target="_blank" >{{$info['patron_url']}}</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <div style="font-size: 13px; color: #6e605d; padding-left: 15px; padding-right: 15px">
                                        If you do not recognize the merchant - MY COMPANY Pvt. Ltd OR have a query regarding this
                                        request, please <a style="color: #4444ef; text-decoration: underline" href="mailto:support@swipez.in?Subject=Query+regarding+the+Payment+Request" target="_blank"> contact us.</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="font-size: 13px; line-height: 30px">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5" role="presentation">
                            <tbody><tr>
                                <td valign="top">
                                    <div style="font-size: 13px; padding-left: 15px; padding-right: 15px">
                                    If you would prefer not receiving our emails, please <a style="color: #4444ef; text-decoration: underline" href="{{$info['unsuburl']}}" target="_blank" data-saferedirecturl="">click here</a> to unsubscribe.
                            </div>
                            </td>
                            </tr>                        </tbody></table>
                    </td>
                </tr>
               </table>
           </div>
           @endif
       </td>
       </tr>       </table>         </td>
</tr>
</table>            </div>

@if ($viewtype=='print')
<script>
    window.print();
    </script>
@endif

</body>
</html>