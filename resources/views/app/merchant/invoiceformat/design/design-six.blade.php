<div class=" w-full flex flex-col items-center justify-center min-h-screen  " >
    <div class="w-full " style="max-width: 910px">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="font-rubik  w-full lg:w-5/6 md:w-4/5 mb-4 bg-white shadow-2xl   p-6 " style="max-width: 910px">
@else
<div class="font-rubik  w-full   bg-white  shadow-2xl p-6 " style="max-width: 910px">
    @endif

    @php
        $inv='';
    @endphp

    @foreach ($metadata['invoice'] as $item)
        @if($item['function_id']=='9')
        @php
        $inv=$item['value'];
    @endphp
        @endif
    @endforeach
    <h1 class="text-1xl text-right mt-4  font-bold tracking-widest text-black">INVOICE {{$inv}}</h1>
  
    @php
   
   $last_payment=NULL;
  $adjustment=NULL;
  $adjustment_col="Adjustment";
  $previous_due_col="Previous due";
   @endphp
    

    <div class="w-full h-0.5 bg-gray-200 mt-2 lg:mt-0 md:mt-0"></div>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 gap-2 justify-center p-0 lg:px-4 md:px-4 mb-2 lg:mb-0 md:mb-0 mt-4 lg:mt-0 md:mt-0">
        <div class="mt-0  p-2 ">
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap justify-left ">
                <img class="max-h-20 " src="{{$info['image_path']}}" >  
                 </section>  
                 @endif
    </div>
        <div> 
             <h1 class="text-1xl  font-bold tracking-widest text-gray-400">Supplier</h1>
            <p class="text-sm font-bold" >{{$metadata['header'][0]['value'] }}</p>
            @if ($info['main_company_name']!='')
            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                {{$info['main_company_name']}})</span>
      @endif
            @foreach ($metadata['header'] as $item)
            @if($item['column_name']=='Merchant address' && $item['value']!='')
            <p class="text-sm ">{{$item['value'] }}</p>
            @endif
              @endforeach
            @foreach ($metadata['header'] as $item)
            @if($item['column_name']!='Company name' && $item['value']!='' && $item['column_name']!='Merchant address')
         
            <p class="text-sm">{{$item['value']}}</p>
                     @endif
                     {{-- @if($item['column_name']=='GSTIN Number')
            {{$gstontop=1}}
            @endif
        @if($item['column_name']=='Company pan')
            {{$panontop=1}}
        @endif
        @if($item['column_name']=='Company TAN')
            {{$tanontop=1}}
            @endif
            @if($item['column_name']=='CIN Number')
            {{$cinontop=1}}
            @endif --}}
            @endforeach   
        </div>
        <div> 
            <h1 class="text-1xl  font-bold tracking-widest text-gray-400">Client</h1>
            <p class="text-sm font-bold" >{{$metadata['customer'][1]['value'] }}</p>
            @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
                <p class="text-sm"> {{$item['value']}}</p>
                @endif
                @endforeach
           
           
        
        </div>

    </div>
    <hr>
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 gap-4  justify-center p-0 lg:px-4 md:px-4 mt-4 lg:mt-0 md:mt-0">
       @if(isset($info['payment_mode']) || isset($info['transaction_id']))
        <div> 
            <table>
            @isset($info['payment_mode'])
            <tr>
         
            <td >
                <p class="text-sm" >Payment Method:</p>
                    </td> 
                    <td class=" px-2">
                        <p class="text-sm" >{{$info['payment_mode']}}</p>
                            </td>    
        </tr>
            @endisset
            @isset($info['transaction_id'])
            <tr>
                <td >
            <p class="text-sm" >Transaction Number:</p>
                </td> 
                <td class=" px-2">
                    <p class="text-sm" >{{$info['transaction_id']}}</p>
                        </td> 
        </tr>
            @endisset
            </table>
        </div>
        @endif   
        
        <div> 
           
            <table>
            @foreach ($metadata['invoice'] as $key=>$item)
                @if( $item['value']!="" && $item['function_id']!=4 && $item['datatype']=='date' && $item['function_id']!='9')
                @if($item['function_id']==12)
               @php
                   $adjustment=$item['value'];
                 $adjustment_col=$item['column_name']; @endphp
             @elseif ($item['function_id']==4)
             @php $previous_due_col=$item['column_name']; @endphp
                    @else
                  <tr>
                    <td>
                        <p class="text-sm ">{{ $item['column_name']}} : </p>
                    </td>
                    <td class=" px-2">

                      
                        @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                        <a target="_BlANK" class="text-sm " href="{{$item['value']}}">{{$item['column_name']}}</a>
                        @else
                        @if ($item['datatype']=='money')
                        <p class="text-sm">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
                        @elseif($item['datatype']=='date')
                        <p class="text-sm "><x-localize :date="$item['value']" type="date" /></p>
                        @else
                        <p class="text-sm"> {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
                       @endif
                       @endif
                    </td>
                  </tr>
              
                  @endif   
           @endif
           @endforeach
        </table>
        </div>
        <div> 
            <table>
            @foreach ($metadata['invoice'] as $key=>$item)
                @if( $item['value']!="" && $item['function_id']!=4 && $item['datatype']!='date' && $item['function_id']!='9')
             @if($item['function_id']==12)
               @php
                   $adjustment=$item['value'];
                 $adjustment_col=$item['column_name']; @endphp
             @elseif ($item['function_id']==4)
             @php $previous_due_col=$item['column_name']; @endphp
                    @else
                  <tr>
                    <td>
                        <p class="text-sm ">{{ $item['column_name']}} : </p>
                    </td>
                    <td class=" px-2">

                      
                        @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                        <a target="_BlANK" class="text-sm " href="{{$item['value']}}">{{$item['column_name']}}</a>
                        @else
                        @if ($item['datatype']=='money')
                        <p class="text-sm">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
                        @elseif($item['datatype']=='date')
                        <p class="text-sm "><x-localize :date="$item['value']" type="date" /> </p>
                        @else
                        <p class="text-sm"> {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
                       @endif
                       @endif
                    </td>
                  </tr>
              
                  @endif   
           @endif
           @endforeach
        </table>
        </div>
    </div>
   
    
    
   
        <div class='overflow-x-auto w-full mt-6'>
            @php
            $sr_no=1;
            $total=0;
                        $taxtotal=0;
        @endphp
        @if (!empty($table_heders))
            <table class=' mx-auto  w-full  rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                <thead >
                    <tr class="text-black text-left">
                        @foreach ($table_heders as $item )
                        <th class="font-semibold text-sm @if($item!='item') text-center @endif px-2 py-2 "> {{$item}} </th>
                       @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($metadata['particular'] as $key=>$item)
                    <tr>
                        @foreach ($table_heders as $k=>$h )
                    
                        <td class="px-2 py-2 @if($k!='item') text-center @endif">
                           
                            @if ($k=='sr_no')
                            <p class="text-sm"> {{$sr_no}} </p>
                          @else
                         <p class="text-sm"> {{$item[$k]}} </p>
                         @endif
                                  
                               
                        </td>
                    </td>
                    @endforeach
                </tr>
                @php
                $sr_no=$sr_no+1;
            @endphp
                @endforeach
               
                  <tr>
                    
                  </tr>
                
                </tbody>
            </table>
            @endif
       
    </div>
    <hr>
   
    <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-2 gap-4 py-2 lg:p-4 md:p-4">
        <div >
            @isset($info['narrative'])
            @if($info['narrative']!='')
            <p class="text-sm font-semibold mt-1">Narrative</p>
            <p class='text-sm mt-1'>{{$info['narrative']}}</p>
            @endif
             @endisset
            @isset($info['tnc'])
                                                @if($info['tnc']!='')
                                                <p class="text-sm font-semibold mt-1">Terms & conditions</p>
                                               @php
                                               
                                                   echo $info['tnc'];
                                               @endphp
                                                @endif
                                                 @endisset 
        </div>
        <div>
        <div class="flex justify-between mb-0 px-2 py-1">
            <div class="text-gray-700  flex-1">Sub Total</div>
            <div class="text-right">
                <div class="text-gray-700  " >{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
                @isset($info['invoice_product_taxation'])
                @if($info['invoice_product_taxation']==2)
                
                 <div style="
                  font-size: 12px;
                  color: gray;
                  text-transform: lowercase;
              "> (Inclusive of tax)</div>
                @endif
                @endisset
            </div>
        </div>
        @foreach ($metadata['tax'] as $key=>$item)
        <div class="flex justify-between mb-0 px-2 py-2 border-t border-gray-400 ">
            <div class="text-sm text-gray-700  flex-1">{{$item['tax_name']}}</div>
            <div class="text-right">
                <div class="text-sm text-gray-700" >{{$info['currency_icon']}}{{$item['tax_amount']}}</div>
            </div>
        </div>
        @php
        $taxtotal=$taxtotal+$item['tax_amount'];
     @endphp
        @endforeach
        @php
        $total=$info['basic_amount']+$info['tax_amount'];
   @endphp
    
        
            <div class="flex justify-between px-2 py-2 border-t border-gray-400 ">
                <div class="text-sm text-gray-800 font-medium  flex-1">Total</div>
                <div class="text-right">
                    <div class="text-sm text-gray-800 font-medium" >{{$info['currency_icon']}}{{ number_format($total,2)}}</div>
                </div>
            </div>
  
            @isset($info['previous_due'])
            @if($info['previous_due']>0)
           
                    <div class="py-1 border-t border-gray-400 ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">{{$previous_due_col}}</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
          
          
            @if($adjustment>0)
            
                    <div class="py-1 border-t border-gray-400 ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">{{$adjustment_col}}</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($adjustment,2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
                  
           
            @isset($info['advance'])
            @if($info['advance']>0)
            
                    <div class="py-1 border-t border-gray-400 ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Advance Received</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['advance'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['paid_amount'])
            @if($info['paid_amount']>0)
            
                    <div class="py-1 border-t border-gray-400 ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">  Paid Amount</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['late_fee'])
            @if($info['late_fee']>0)
            
                    <div class="py-1 border-t border-gray-400 ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Late Fee</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($metadata['plugin']['has_coupon'])
            @if($metadata['plugin']['has_coupon'])
            
                    <div class="py-1 border-t border-gray-400">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Coupon Discount</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " > 0.00</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
          
            @if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
           <div class="py-1 border-t border-gray-400 ">
                <div class="flex justify-between px-2 py-1">
                    <div class="text-sm text-gray-800 uppercase  flex-1">GRAND TOTAL</div>
                    <div class="text-right">
                        <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
                    </div>
                </div>

            </div>
            @endif
          
{{-- 
               @isset($info['pan'])
               @if($info['pan']!='' && $panontop==0)
               
               
                <div class="py-1 border-t border-gray-400">
                    <div class="flex justify-between px-2 py-1">
                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1">PAN NO</div>
                        <div class="text-right ">
                            <div class="text-sm text-gray-800 " >{{$info['pan']}}</div>
                        </div>
                    </div>

                </div>
                @endif
                @endisset
                @if($info['tan']!='' && $tanontop==0)
               

                        <div class="py-1 border-t border-gray-400 ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-[{{$colors}}] uppercase  flex-1">TAN No</div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-800 " >{{$info['tan']}}</div>
                                </div>
                            </div>
        
                        </div>

                   
                    @endif
                 
                           @isset($info['gst_number'])
                           @if($info['gst_number']!='' && $gstontop==0)
                          
                                <div class="py-1 border-t border-gray-400 ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-[{{$colors}}] uppercase  flex-1">GST Number</div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-800 " >{{$info['gst_number']}}</div>
                                </div>
                            </div>
        
                        </div>
                               @endif
                               @endisset
                          
                               @isset($info['registration_number'])
                               @if($info['registration_number']!='')
                              
                                   <div class="py-1 border-t border-gray-400 ">
                                    <div class="flex justify-between px-2 py-1">
                                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1">S. Tax Regn</div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-800 " >{{$info['registration_number']}}</div>
                                        </div>
                                    </div>
                
                                </div>
                                   @endif
                                   @endisset --}}
                                   
            <div class="flex justify-between   px-2 bg-gray-100 py-2 border-t border-gray-400">
                <div class="text-sm text-gray-800 font-medium  flex-1">Amount Due</div>
                <div class="text-right">
                    <div class="text-sm text-gray-800 font-medium">{{$info['currency_icon']}}{{$info['absolute_cost']}}</div>
                </div>
            
        </div>
        <div class="py-1 border-t border-gray-400 ">
            <div class="flex justify-between px-2 py-1">
                <p class="text-sm font-bold text-black-900">Amount(in words) : <span class="font-normal">{{$info['absolute_cost_words']}}</span></p>  
     
            </div>

        </div>
        </div>
      
    </div>
    @isset($metadata['plugin']['has_signature'])
    @if($metadata['plugin']['has_signature']!=1)
   <div>
       <div  class="mt-1 px-1.5 py-1.5 text-left border-t border-gray-200">
           <p class="text-sm  py-1 px-1 mt-1 ">Note: This is a system generated invoice. No signature required.</p> 
       </div>
   </div>
@endif
@endisset
@if (isset($metadata['plugin']['has_digital_certificate_file']) && $metadata['plugin']['has_digital_certificate_file']==1)
<div class="row">
    <div class="col-md-12">
        <div class="pull-right" style="margin-right: 20px;">
            <img src="/images/default_digital_signature.png" style="max-height: 100px;">
        </div>
    </div>
</div>
@else
@if (isset($info['signature']))
    <div class="row">
        <div class="col-md-12">
            <div class="pull-{{$info['signature']['align']}}" style="margin-{{$info['signature']['align']}}: 20px;">
                @if($info['signature']['type']=='font')
                    <label
                        style="font-family: '{{$info['signature']['font_name']}}',cursive;font-size: {{$info['signature']['font_size']}}px;">{{$info['signature']['name']}}</label>
                @else
                    <img src="{{$info['signature']['signature_file']}}" style="max-height: 100px;">
                @endif
            </div>
        </div>
    </div>
    @endif
    @endif  
 
    <div class="p-0 lg:p-4 md:p-4  mt-5">
        <div class="flex items-center justify-center lg:justify-self md:justify-self font-bold">
            {{$info['footer_note']}}
        </div>
       
    </div>
   
   
</div>
@if($info['its_from']!='preview')
@php
    $footers='app.merchant.invoiceformat.invoice_footer';
    if($info['user_type']=='merchant')
    {
    $footers='app.merchant.invoiceformat.invoice_footer';}
else{
$footers='app.patron.invoice.invoice-footer';}

@endphp

@if($info['its_from']!='preview')

@if($info['staging']==0)
<div class="w-full mt-1" style="max-width: 910px">

    @include($footers)
    
    </div> 
@endif
@endif
@endif
</div>