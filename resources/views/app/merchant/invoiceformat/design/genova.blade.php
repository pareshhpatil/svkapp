
<div class=" w-full flex flex-col items-center justify-center min-h-screen  " >
    <div class="w-full lg:w-5/6 md:w-4/5">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="font-rubik w-full lg:w-5/6 md:w-4/5 shadow-2xl  bg-white mb-4  p-4  lg:p-10 md:p-10" style="max-width: 910px">
  @else
  <div class="font-rubik w-full   bg-white shadow-2xl p-4  lg:p-10 md:p-10" style="max-width: 910px">
@endif
    <div class="grid lg:grid-cols-4 md:grid-cols-4 sm:grid-cols-1 justify-left ">
        <div class="mt-2  p-2 mr-12 col-span-2">
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap justify-left ">
                <img class=" h-8 lg:h-14 md:h-14 " src="/uploads/images/logos/{{$info['image_path']}}">  
                 </section>  
                 @endif
    </div>
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
         <div class=" bg-gray-200 p-4 relative justify-center" style="display: flex;"> 
          @if (!empty($inv))
          <p class=" text-gray-700 text-sm font-regular " style="display: inline-block;
          align-self: flex-end;" >{{$invnm}}  <span class="text-sm text-black font-semibold ml-1">{{$inv}}</span></p>
           @else
           <p class=" text-gray-700 text-sm font-regular " style="display: inline-block;
           align-self: flex-end;" >INVOICE</p>
         
          @endif
            
        </div>
        @php
        $last_payment=NULL;
      $adjustment=NULL;
      $adjustment_col="Adjustment";
      $previous_due_col="Previous due";
  @endphp

        <div class="  p-4 bg-[{{$colors}}]" > 
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
                <div class="border-b border-gray-400 pt-1 pl-4">
             <p class="text-sm uppercase text-white text-left lg:text-right md:text-right " >{{$item['column_name']}}</p>
        {{-- start --}}
             @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
            <a target="_BlANK" class="text-sm  text-white font-semibold text-left lg:text-right md:text-right" href="{{$item['value']}}">{{$item['column_name']}}</a>
            @else
            @if ($item['datatype']=='money')
            <p class="text-sm uppercase text-white font-semibold text-left lg:text-right md:text-right">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
            @elseif($item['datatype']=='date')
            <p class="text-sm uppercase text-white font-semibold text-left lg:text-right md:text-right"><x-localize :date="$item['value']" type="date" /> </p>
            @else
            <p class="text-sm uppercase text-white font-semibold text-left lg:text-right md:text-right"> {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
           @endif
           @endif
       {{-- end --}}
    </div>
        @endif
    
            @endif
            @endforeach
           
        
        </div>

    </div>
   
    <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-4 justify-center p-0 lg:p-4 md:p-4 mt-4  lg:mt-0 md:mt-0">
        <div> 
             <h1 class=" text-sm  font-regular  text-gray-600 uppercase">Supplier</h1>
            <p class="text-sm font-bold uppercase" >{{$metadata['header'][0]['value'] }}</p>
            @if ($info['main_company_name']!='')
            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                {{$info['main_company_name']}})</span>
      @endif
            @foreach ($metadata['header'] as $item)
      @if($item['column_name']=='Merchant address' && $item['value']!='')
      <p class="text-sm">{{$item['value'] }}</p>
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
        <div class="border-t lg:border-t-0 md:border-t-0 lg:border-l md:border-l  border-gray-400 px-0 lg:px-4 md:px-4"> 
            <h1 class="mt-0 sm:mt-4 text-sm  font-regular text-gray-600 uppercase">Client</h1>
            <p class="text-sm font-bold uppercase" >{{$metadata['customer'][1]['value'] }}</p>
            @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
                <p class="text-sm"> {{$item['value']}}</p>
                @endif
                @endforeach
        
        </div>

    </div>
    
    <div class=" border-t-2 lg:border-t md:border-t border-dotted border-gray-700 mt-4  lg:mt-0 md:mt-0"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-4 justify-center p-0 lg:p-4 md:p-4 mt-4  lg:mt-0 md:mt-0">
        @isset($info['payment_mode'])
        <div> 
            @isset($info['payment_mode'])
            <p class="text-sm uppercase" >Payment Method: <span class="ml-4 font-semibold">{{$info['payment_mode']}}</span></p>
           @endisset
           @isset($info['transaction_id'])
            <p class="text-sm uppercase mt-2" >Transaction Number:<span class="ml-4 font-semibold">{{$info['transaction_id']}}</span></p>
        @endisset
        </div>  
        @endisset
        <div> 
          
            <p class=" text-2xl uppercase">  Thank you for your purchase.</p>
            </div>
    </div>
   
    <div class=" border-t-2 lg:border-t md:border-t border-dotted border-gray-700 mt-4  lg:mt-0 md:mt-0"></div>
    
    
   
        <div class='overflow-x-auto w-full mt-4'>
            @php
            $sr_no=1;
            $total=0;
                        $taxtotal=0;
        @endphp
            <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                <thead >
                    <tr class="text-left " >
                        @foreach ($table_heders as $item )
                        <th class="font-semibold text-sm  text-[{{$colors}}] uppercase px-2 py-2 @if($item!='item') text-center @endif"> {{$item}} </th>
                       @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($metadata['particular'] as $key=>$item)
                    <tr>
                        @foreach ($table_heders as $k=>$h )
                    
                        <td class="px-2 py-2 @if($k!='item') text-center @endif">
                           
                            @if ($k=='sr_no')
                            <p class="text-sm font-normal"> {{$sr_no}} </p>
                          @else
                         <p class="text-sm font-normal"> {{$item[$k]}} </p>
                         @endif
                                  
                               
                        </td>
                        @endforeach
                    </tr>
                    @php
                    $sr_no=$sr_no+1;
                @endphp
                    @endforeach
                 
                  
                
                </tbody>
            </table>
       
    </div>
    <hr>
   
    <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-2 gap-4 p-0 lg:p-4 md:p-4">
        <div> @isset($info['tnc'])
            @if($info['tnc']!='')
            <p class="text-sm font-semibold mt-2">Terms & conditions</p>
           @php
           
               echo $info['tnc'];
           @endphp
            @endif
             @endisset  </div>
        <div>
        <div class="flex justify-between mb-0 px-2 py-2">
            <div class="text-gray-700  flex-1 uppercase">Sub Total</div>
            <div class="text-right w-20">
                <div class="text-gray-700 font-semibold" >{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
            </div>
        </div>
        @foreach ($metadata['tax'] as $key=>$item)
        <div class="flex justify-between mb-0 px-2  py-2 border-t border-[{{$colors}}] ">
            <div class="text-sm text-gray-700  flex-1 uppercase">{{$item['tax_name']}}</div>
            <div class="text-right w-20">
                <div class="text-sm text-gray-700 font-semibold" >{{$info['currency_icon']}}{{$item['tax_amount']}}</div>
            </div>
        </div>
        @php
                $taxtotal=$taxtotal+$item['tax_amount'];
             @endphp
                @endforeach
                @php
                $total=$info['basic_amount']+$info['tax_amount'];
           @endphp
    
       
            <div class="flex justify-between px-2 py-2 border-t border-[{{$colors}}] ">
                <div class="text-sm text-gray-800 font-medium  flex-1 uppercase">Total</div>
                <div class="text-right w-20">
                    <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{ number_format($total,2)}}</div>
                </div>
            </div>



           
            @isset($info['previous_due'])
            @if($info['previous_due']>0)
           
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">{{$previous_due_col}}</div>
                            <div class="text-right w-20">
                                <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
         
          
            @if($adjustment>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">{{$adjustment_col}}</div>
                            <div class="text-right w-20">
                                <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($adjustment,2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
                  
          
            @isset($info['advance'])
            @if($info['advance']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Advance Received</div>
                            <div class="text-right w-20">
                                <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($info['advance'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['paid_amount'])
            @if($info['paid_amount']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1">  Paid Amount</div>
                            <div class="text-right w-20">
                                <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['late_fee'])
            @if($info['late_fee']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Late Fee</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($metadata['plugin']['has_coupon'])
            @if($metadata['plugin']['has_coupon'])
            
                    <div class="py-1 border-t border-[{{$colors}}]">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-800 uppercase  flex-1"> Coupon Discount</div>
                            <div class="text-right w-20">
                                <div class="text-sm text-gray-800 font-semibold" > 0.00</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
          
            @if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
           <div class="py-1 border-t border-[{{$colors}}] ">
                <div class="flex justify-between px-2 py-1">
                    <div class="text-sm text-gray-800 uppercase  flex-1">GRAND TOTAL</div>
                    <div class="text-right">
                        <div class="text-sm text-gray-800 font-semibold" >{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
                    </div>
                </div>

            </div>
            @endif
          

               {{-- @isset($info['pan'])
               @if($info['pan']!='' && $panontop==0)
               
               
                <div class="py-1 border-t border-[{{$colors}}]">
                    <div class="flex justify-between px-2 py-1">
                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1">PAN NO</div>
                        <div class="text-right ">
                            <div class="text-sm text-gray-800 font-semibold" >{{$info['pan']}}</div>
                        </div>
                    </div>

                </div>
                @endif
                @endisset --}}
                {{-- @if($info['tan']!='' && $tanontop==0)
               

                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-[{{$colors}}] uppercase  flex-1">TAN No</div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-800 font-semibold" >{{$info['tan']}}</div>
                                </div>
                            </div>
        
                        </div>

                   
                    @endif
                 
                           @isset($info['gst_number'])
                           @if($info['gst_number']!='' && $gstontop==0)
                          
                                <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-[{{$colors}}] uppercase  flex-1">GST Number</div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-800 font-semibold" >{{$info['gst_number']}}</div>
                                </div>
                            </div>
        
                        </div>
                               @endif
                               @endisset
                          
                               @isset($info['registration_number'])
                               @if($info['registration_number']!='')
                              
                                   <div class="py-1 border-t border-[{{$colors}}] ">
                                    <div class="flex justify-between px-2 py-1">
                                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1">S. Tax Regn</div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-800 font-semibold" >{{$info['registration_number']}}</div>
                                        </div>
                                    </div>
                
                                </div>
                                   @endif
                                   @endisset --}}
                                 
                                <div class="flex justify-between   px-2 py-2 bg-[{{$colors}}]" >
                                    <div class="text-sm text-white font-medium  flex-1 uppercase">Amount Due</div>
                                    <div class="text-right w-20">
                                        <div class="text-sm text-white font-semibold" >{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
                                    </div>
                                </div>
                                <div class="py-1 border-t border-[{{$colors}}] ">
                                    <div class="flex justify-between px-2 py-1">
                                        <p class="text-sm font-bold text-black-900">Amount(in words) : <span class="font-normal">{{$info['absolute_cost_words']}}</span></p>  
                             
                                    </div>
                
                                </div>
           {{-- end total menu --}}
        
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

<div class="w-full  mt-1" style="max-width: 910px">
@include($footers)

</div>
@endif
@endif
</div>