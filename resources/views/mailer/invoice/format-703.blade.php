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
  <![endif]-->
<style>
    @font-face {
    font-family: 'Rubik';
    font-style: normal;
    
  
    src: url({{ storage_path('fonts\Roboto-Bold.ttf') }}) format("truetype");
    font-weight: 600;
}
body{
    font-family: Roboto;
    letter-spacing: 0px;
    line-height: 75%;
}


@if($viewtype=='print')
@page { margin-top: 0px;margin-bottom:0px;margin-left: 20px;margin-right: 10px  }
@else
@page { margin-top: 15px;margin-bottom:15px;margin-left: 15px;margin-right: 15px  }
@endif
body { margin-top: 10px;margin-bottom:5px;margin-left: 20px;margin-right: 20px }
    </style>

</head>
<body style="word-break: break-word; -webkit-font-smoothing: antialiased; margin: 0; width: 100%; padding: 0">
  <div role="article" aria-roledescription="email" aria-label="" lang="en"> <!doctype html>
                         <div style="display: flex;  align-items: center; justify-content: center; background-color: #f3f4f6; padding: 8px">
                    <div id="tab" style="width: 100%; background-color: #fff; padding: 16px">
                        <table >
                            <tr>
                            <td>
                                <img style="height: 40px" src="{{ asset('images/logo-703.PNG') }}" alt="">
                            </td>
                            <td>
                                <div style="margin-top: 20px; text-align: left; font-size: 24px; font-weight: 600; color: #000;font-size:24px;">Document G703® – 1992</div>
                            </td> 
                            </tr>
                        </table>
                            <div style="font-size:22px;margin-top: 10px; text-align: left; font-weight: 600; color: #000">Continuation Sheet</div>
                        <div style="margin-top: 5px;margin-bottom: 2px; height: 2px; width: 100%; background-color: #111827"></div>
                          <table style="width:100%">
                            <td>
                                <div style="font-size: 12px">AIA Document G702®, Application and Certificate for Payment, or G732™,
                                    Application and Certificate for
                                    Payment, Construction Manager as Adviser Edition, containing Contractor’s signed certification
                                    is attached.
                                    Use Column I on Contracts where variable retainage for line items may apply. </div>
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
                            <table   style="margin-left: auto; margin-right: auto; width: 100%;  overflow: hidden;border:1px solid #313131" cellpadding="0" cellspacing="0" role="presentation">
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
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">  </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">  </td>
                                        <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">WORK COMPLETED </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">  </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">  </td>
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
                                            PREVIOUS APPLICATION<br/>
                                            (D + E) </td>
                                        <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> THIS PERIOD
                                        </td>
                                        <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> MATERIALS
                                            PRESENTLY
                                            STORED<br/>
                                            (Not in D or E) </td>
                                        <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">TOTAL
                                            COMPLETED AND
                                            STORED TO DATE<br/>
                                            (D+E+F) </td>
                                        <td style="min-width: 70px; border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px"> %(G ÷ C)
                                        </td>                                        <td style="border-bottom:1px solid #313131; border-right:1px solid #313131;; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">BALANCE TO
                                            FINISH<br/>
                                            (C – G) </td>
                                        <td style="border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center; font-size: 12px">RETAINAGE
                                            <br/>(If variable rate) </td>                                    </tr>
                                </thead>
                                <tbody>
                                  
                                                        @foreach ($info['constriuction_details'] as $key=>$item)  
                                 
                                                        @if($item['type']=='heading')
                                    <tr>
                                        <td colspan="10" style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">   
                                                                                     <div style="font-size: 14px">{{ $item['b'] }} </div>      
                                                                                                                       </td>
                                        
                                        
                                       
                                    </tr>
                                    @elseif ($item['type']=='footer')
                                    <tr>
                                          <td colspan="2" style="border-right:1px solid #313131;border-bottom:1px solid #313131;border-top:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 12px"> {{ $item['b'] }}</div>                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"> {{ $item['c'] }}</div>                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['d'] }} </div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 14px">{{ $item['e'] }}</div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['f'] }}</div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['g'] }}</div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">
                                                {{ $item['g_per'] }}
                                                </div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['h'] }}</div>
                                        </td>
                                        <td style=" border-top:1px solid #313131;border-bottom:1px solid #313131;padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['i'] }}</div>
                                        </td>
                                    </tr>
                                   @elseif ($item['type']=='combine')
                                   <tr>
                                         <td colspan="2" style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                            <div style="font-size: 14px"> {{ $item['b'] }}</div>                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"> {{ $item['c'] }}</div>                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['d'] }} </div>
                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 14px">{{ $item['e'] }}</div>
                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['f'] }}</div>
                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['g'] }}</div>
                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">
                                                {{ $item['g_per'] }}
                                                </div>
                                        </td>
                                        <td style="border-bottom:1px solid #313131;border-top:1px solid #313131;border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['h'] }}</div>
                                        </td>
                                        <td style="border-top:1px solid #313131;border-bottom:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['i'] }}</div>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">   
                                                                                     <div style="font-size: 14px">{{ $item['a'] }} </div>                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">
                                            <div style="font-size: 14px"> {{ $item['b'] }}</div>                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"> {{ $item['c'] }}</div>                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['d'] }} </div>
                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 14px">{{ $item['e'] }}</div>
                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['f'] }}</div>
                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['g'] }}</div>
                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">
                                                {{ $item['g_per'] }}
                                                </div>
                                        </td>
                                        <td style="border-right:1px solid #313131; padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['h'] }}</div>
                                        </td>
                                        <td style=" padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ $item['i'] }}</div>
                                        </td>
                                    </tr>
                                   @endif
                                    @endforeach
                                   
                                    <tr>
                                        <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: left">                                            <div style="font-size: 14px"> </div>                                        </td>
                                        <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 12px;font-weight: 600"><b>GRAND TOTAL</b> </div>                                        </td>
                                        <td style="min-width: 100px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_c'],2) }}  </div>                                        </td>
                                        <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_d'],2) }} </div>
                                        </td>
                                        <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: center">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_e'],2) }}</div>
                                        </td>
                                        <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_f'],2) }}</div>
                                        </td>
                                        <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_g'],2) }}</div>
                                        </td>
                                        <td style="min-width: 50px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px">{{ number_format($info['total_g']/$info['total_c'],2)}}</div>
                                        </td>
                                        <td style="min-width: 90px;border-right:1px solid #313131;  border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_h'],2) }}</div>
                                        </td>
                                        <td style=" min-width: 90px;border-top:1px solid #313131;  padding-left: 2px; padding-right: 2px; padding-top: 8px; padding-bottom: 8px; text-align: right">
                                            <div style="font-size: 14px"><span style="font-family:@if($info['currency_icon']=='₹')DejaVu Sans;@endif sans-serif;">{{$info['currency_icon']}}</span>{{ number_format($info['total_i'],2) }}</div>
                                        </td>
                                    </tr>                                </tbody>
                            </table>                        </div>
                        <hr>
                        <div style="margin-top: 8px">                            <div style="line-height: 12px"><span style="font-size: 12px; font-weight: 600">AIA Document G703® – 1992. Copyright</span><span style="font-size: 12px"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                                    of Architects. All rights reserved.</span><span style="font-size: 12px; color: #ef4444"> The “American
                                    Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                                    and “AIA Contract Documents” are registered trademarks and may not be used without
                                    permission.</span><span style="font-size: 12px"> To report copyright violations of AIA Contract
                                    Documents, e-mail copyright@aia.org.</span></div>                        </div>                    </div>
                </div>             </div>
</body>
@if ($viewtype=='print')
<script>
    window.print();
    </script>
@endif
</html>
