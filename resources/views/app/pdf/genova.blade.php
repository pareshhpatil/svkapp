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
<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">
  <div role="article" aria-roledescription="email" aria-label="" lang="en"> <table style="width: 100%"  role="presentation">
                <tr>
                  <td align="center" style="@if($viewtype=='mailer')padding-top: 30px; padding-bottom: 30px;@endif background-color: #F7F8F8">
                     <table class="sm-w-full" style="width: 700px"  role="presentation">
                <tr>
                  <td style="border: 1px solid gray; background-color: #fff; padding: 0"><div style="padding: 16px">
                  
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
                    <table style="width: 100%"  role="presentation">
            <tr>
                <td style="vertical-align: top;">
                    @if(!empty($info['image_path']))
                    <img src="/uploads/images/logos/{{$info['image_path']}}" style=" object-fit: scale-down;max-width:120px" alt="">
                      @endif    
                </td> 
                
                <td style="background-color: #e5e7eb; vertical-align: bottom; text-align: center; padding-right: 5px">
                    <p style="font-size: 14px; color: #374151">{{$invnm}}  <span style="margin-left: 4px; font-size: 14px; font-weight: 600; color: #000">{{$inv}}</span></p>                </td>                <td style="background-color: {{$info['design_color']}}; padding: 10px">
                    <table style="width: 100%"  role="presentation">
                   
                        @foreach ($metadata['invoice'] as $key=>$item)
                        @if($item['value']!="" && $item['function_id']!=9)
                       
                        @if($item['function_id']==11)
                        @php $last_payment=$item['value']; @endphp
                         @elseif($item['function_id']==12)
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

                        <span style="font-size: 14px; font-weight: 600; text-transform: uppercase; color: #fff">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </span>
                     
                        @elseif($item['datatype']=='date')

                        <span style="font-size: 14px; font-weight: 600; text-transform: uppercase; color: #fff">{{date("d-M-Y", strtotime($item['value']))}}</span>
                     
                        @else
                          <span style="font-size: 14px; font-weight: 600; text-transform: uppercase; color: #fff">{{$item['value']}} @if($item['datatype']=='percent') %@endif</span>
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
                     <h1 style="font-size: 14px; text-transform: uppercase; color: #4b5563">Supplier</h1>
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
                <td style="border-left: 1px solid gray; padding-left: 20px;width: 50%;">
                    <h1 style="margin-top: 16px; font-size: 14px; text-transform: uppercase; color: #4b5563">Client</h1>
                    <div style="font-size: 14px; font-weight: 700; text-transform: uppercase">{{$metadata['customer'][1]['value'] }}</div>
                    @foreach ($metadata['customer'] as $key=>$item)
                    @if($key!=1)
                    {{$item['value']}}<br>
                    @endif
                    @endforeach

                                </td>
            </tr>
        </table>           <div style="border-top: 1px dotted gray; border-bottom: 1px dotted gray; margin-top: 16px" >
            <table style="width: 100%"  role="presentation">
                <tr>
                    @isset($info['payment_mode'])
                <td width="50%">
                    @isset($info['payment_mode'])
                   <div style="font-size: 14px; text-transform: uppercase">Payment Method: <span style="margin-left: 16px; font-weight: 600">{{$info['payment_mode']}}</span></div> 
                  @endisset
                  @isset($info['transaction_id'])
                   <div style="margin-top: 8px; font-size: 14px; text-transform: uppercase">Transaction Number:<span style="margin-left: 16px; font-weight: 600">{{$info['transaction_id']}}</span></div>
                   @endisset
                </td>
                @endisset
               

                <td>                     <p style="font-size: 24px; text-transform: uppercase">  Thank you for your purchase.</p>
                </td>
                </tr>
            </table>
           </div>                <div style="margin-top: 16px; width: 100%; overflow-x: auto">
            @php
            $sr_no=1;
            $total=0;
                    $taxtotal=0;
        @endphp   
            <table  style="border-spacing: 0; margin-left: auto; margin-right: auto; width: 100%; overflow: hidden;  border-radius: 8px; background-color: #fff" cellpadding="0"  role="presentation">
                    <thead>
                        <tr >
                            @foreach ($table_heders as $item )
                            <th style="border-bottom: 2px solid {{$info['design_color']}}; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px;  @if($item!='item') text-align:center; @endif  font-size: 14px; font-weight: 600; color: {{$info['design_color']}}"> {{$item}} </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($metadata['particular'] as $key=>$item)
                        <tr>
                            @foreach ($table_heders as $k=>$h )
                            <td style="border-bottom: 1px solid gray; padding-top: 8px; padding-bottom: 8px; @if($k!='item') text-align:center; @endif">
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



                      <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                        <td width="50%" style="vertical-align: top;" colspan="3" rowspan="11"> 
                             <div style="width: 90%;">

                                @isset($info['tnc'])
                                @if($info['tnc']!='')
                                <p class="text-sm font-semibold mt-2">Terms & conditions</p>
                               @php

                                   echo $info['tnc'];
                               @endphp
                                @endif
                                 @endisset 
                        </div> </td>
                        <td colspan="2" style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                            <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">Sub Total</div>
                         </td>
                        <td style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                            <div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
                             </td> </tr>
                             @foreach ($metadata['tax'] as $key=>$item)
                      <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                                                             <td colspan="2" style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                            <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">{{$item['tax_name']}}</div>
                         </td>
                        <td style="border-bottom: 1px solid #6F8181; padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                            <div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{$item['tax_amount']}}</div>
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
                            <div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{ number_format($total,2)}}</div>
                             </td>                                      
                            </tr>
                            @isset($info['previous_due'])
@if($info['previous_due']>0)
                            <tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
                                <td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
 <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color:#5C6B6B">{{$previous_due_col}}</div>
</td>
<td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
 <div style="font-size: 14px; color: #374151;font-weight: 600;">{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}}</div>
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
<div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{number_format($adjustment,2)}}</div>
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
<div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{number_format($info['advance'],2)}}</div>
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
<div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}}</div>
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
<div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}}</div>
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
<div style="font-weight: 600;font-size: 14px; color: #374151">0.00</div>
</td>                                      
</tr>
@endif
@endisset
@if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))

<tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
<td colspan="2" style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
<div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color: {{$info['design_color']}}"> GRAND TOTAL</div>
</td>
<td style="border-bottom: 1px solid #6F8181;padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
<div style="font-weight: 600;font-size: 14px; color: #374151">{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
</td>                                      
</tr>
@endif
<tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
    <td colspan="2" style="background: {{$info['design_color']}};border-bottom: 1px solid {{$info['design_color']}};padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
    <div style="flex: 1 1 0%; font-size: 14px; text-transform: uppercase; color: white;font-weight: 600;"> Amount Due</div>
    </td>
    <td style="background: {{$info['design_color']}};border-bottom: 1px solid {{$info['design_color']}};padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: right">
    <div style="font-size: 14px; color: white;font-weight: 600;">{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
    </td>                                      
    </tr>
<tr style="--tw-divide-y-reverse: 0; border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse))); border-bottom-width: calc(1px * var(--tw-divide-y-reverse)); border-color: #e5e7eb">
<td colspan="3" style="padding-left: 8px; padding-right: 8px; padding-top: 8px; padding-bottom: 8px; text-align: left">
<div style="flex: 1 1 0%; font-size: 14px; color: rgb(39, 38, 38)"><b>Amount(in words) :</b> {{$info['absolute_cost_words']}}</div>
</td>
                     
</tr>


                          
                          </tbody>
                </table>    </div>    
            
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
                            <img src="{{$info['signature']['signature_file']}}" style="max-height: 100px;">
                        @endif
                    </div>
            </td>
        </tr>
            @endif
            @endif 
                </table>
            </div>          
                
            @if ($viewtype=='mailer')
    
                <div style="margin-bottom: 16px">
        <table style="width: 100%"  role="presentation">
            <tr>
                <td colspan="6">
                    <div style="font-size: 13px; color: #6e605d; padding-left: 8px; padding-right: 8px">
                        <br>
                        <b> Please note: No extra charges are applicable for paying online. </b>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-size: 15px; color: #fb735d; line-height: 20px; text-align: right; padding-left: 8px; padding-right: 8px">
                    <a href="#" style="font-family: Arial,Helvetica,sans-serif; font-family: Open Sans,sans-serif; font-size: 13px; background-color: #18aebf; color: #ffffff; padding: 7px 10px 7px 10px; text-decoration: none; float: right" target="_blank">
                             Pay now
                    </a>
                    <a href="#" style="font-family: Arial,Helvetica,sans-serif; font-family: Open Sans,sans-serif; font-size: 13px; background-color: #f99636; color: #ffffff; padding: 7px 10px 7px 10px; text-decoration: none; float: right; margin-right: 10px" target="_blank">
                                Save as PDF
                    </a>
                </td>
            </tr>
           <tr>
            <td><br></td>
           </tr>
            <tr>
                <td colspan="6" style="padding: 5px; color: #5b4d4b; color: #ffffff; line-height: 30px; text-align: right; font-size: 12px; background-color: #18aebf">
                    <span style="float: left">If you would like to collect online payments for your business, <a href="https://www.swipez.in/merchant/register" style="color: #4444ef; text-decoration: underline" target="_blank">register now</a> on Swipez.</span>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="font-size: 15px; color: #fb735d; line-height: 30px">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" role="presentation">
                        <tbody><tr>
                            <td valign="top">
                                <div style="font-size: 13px; color: #6e605d; padding-left: 15px; padding-right: 15px">
                                    If you are having trouble viewing this invoice in your email, you can use this link to view
                                    the same invoice
                                    <a style="color: #4444ef; text-decoration: underline" href="https://www.swipez.in/patron/paymentrequest/view/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.swipez.in/patron/paymentrequest/view/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0&amp;source=gmail&amp;ust=1656045138803000&amp;usg=AOvVaw3ehTZDuYWC4CQLrjHWvTCx">https://www.swipez.in/patron/<wbr>paymentrequest/view/<wbr>NE7FwKNOkpSAb4xSHABi_<wbr>xzIhGv69dU3kV0</a>
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
                                If you would prefer not receiving our emails, please <a style="color: #4444ef; text-decoration: underline" href="https://www.swipez.in/unsubscribe/select/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.swipez.in/unsubscribe/select/NE7FwKNOkpSAb4xSHABi_xzIhGv69dU3kV0&amp;source=gmail&amp;ust=1656045138803000&amp;usg=AOvVaw1Q7ifpd7k51xjj3pzNusiw">click here</a> to unsubscribe.
                        </div>
                        </td>
                        </tr>                    </tbody></table>
                </td>
            </tr>
           </table>
       </div>
       @endif
       </td>
       </tr>       </table>         </td>
</tr>
</table>            </div>
</body>
</html>