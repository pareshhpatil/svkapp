
<div class=" w-full flex flex-col items-center justify-center min-h-screen  ">
    <div class="w-full lg:w-5/6 md:w-4/5">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="font-rubik w-full lg:w-5/6 md:w-4/5  bg-white shadow-2xl mb-4  p-4  lg:p-12 md:p-10" style="max-width: 910px">
    @else
    <div class="font-rubik w-full  bg-white  shadow-2xl  p-4  lg:p-12 md:p-10" style="max-width: 910px">
@endif
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3  gap-2 mt-4">
        <div class="block lg:hidden md:hidden mt-2">
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap  ">
                <img class=" h-8 " src="/uploads/images/logos/{{$info['image_path']}}">     
            </section>  
            @endif
    </div>
    {{-- @php
    $gstontop=0;
    $panontop=0;
   $tanontop=0;
   $cinontop=0;
   @endphp --}}
    <div>
        <h1 class="text-sm uppercase text-[{{$colors}}]">Supplier</h1>
        <p class="text-sm mt-1 font-bold" >{{$metadata['header'][0]['value'] }}</p>
        @if ($info['main_company_name']!='')
            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                {{$info['main_company_name']}})</span>
      @endif
        @foreach ($metadata['header'] as $item)
        @if($item['column_name']=='Merchant address' && $item['value']!='')
        <p class="text-sm py-0.5">{{$item['value'] }}</p>
        @endif

          @endforeach
        @foreach ($metadata['header'] as $item)
        @if($item['column_name']!='Company name' && $item['value']!='' && $item['column_name']!='Merchant address')
         <p class="text-sm py-0.5">{{$item['value']}}</p>
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
    <div class="d-none d-lg-block mt-0 px-4">
        @if(!empty($info['image_path']))
        <section class="flex flex-wrap justify-center ">
            <img class=" h-20 " src="/uploads/images/logos/{{$info['image_path']}}">    
        </section>  
        @endif
</div>
    <div>
        <h1 class="text-sm uppercase text-[{{$colors}}] text-left lg:text-right md:text-right">Client</h1>
            <p class="text-sm mt-1 font-bold text-left lg:text-right md:text-right" >{{$metadata['customer'][1]['value'] }}</p>
            @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
            <p class="text-sm py-0.5 text-left lg:text-right md:text-right" >{{$item['value']}}</p>
            @endif
            @endforeach
            
</div>
    </div>
    @php
    $last_payment=NULL;
  $adjustment=NULL;
  $adjustment_col="Adjustment";
  $previous_due_col="Previous due";
@endphp
    
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 gap-4 mt-4">
        <div>
            @php
                $inv='NA';
                $nm='INVOICE';
            @endphp
            <div class="border-t border-gray"></div>
           
            @foreach ($metadata['invoice'] as $item)
            @if($item['value']!="" && $item['function_id']==9)
           @php
               $inv=$item['value'];
               $nm=$item['column_name'];
           @endphp
           @endif
            @endforeach
            <p class="text-sm uppercase text-[{{$colors}}] mt-2">{{$nm}}</p>
            <p class="text-lg uppercase mb-2  mt-1">{{$inv}}</p>
            <div class="border-b border-gray"></div>
        </div>
        <div>
            <div class="border-t border-gray"></div>
            @php
                $duename='Due Date';
                $duedt='';
            @endphp
            @foreach ($metadata['invoice'] as $item)
            @if($item['value']!="" && $item['column_position']=='6')
           @php
              $duedt=$item['value'];
               $duename=$item['column_name'];
           @endphp
           @endif
            @endforeach
            <p class="text-sm uppercase text-[{{$colors}}] mt-2">{{$duename}}</p>
            <p class="text-lg uppercase mb-2  mt-1"><x-localize :date="$info['due_date']" type="date" /></p>
          
            <div class="border-b border-gray"></div>
        </div>
        <div>
            <div class="border-t border-gray"></div>
            <p class="text-sm uppercase text-[{{$colors}}] mt-2 text-left lg:text-right md:text-right">Amount Due</p>
            <p class="text-lg uppercase mb-2  mt-1 text-left lg:text-right md:text-right">{{$info['currency_icon']}}{{$info['absolute_cost']}}</p>
            <div class="border-b border-gray"></div>
        </div>
    
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-3  gap-4 mt-2">
        <div>
        
            @foreach ($metadata['invoice'] as $item)
     
       @if($item['value']!="" && $item['function_id']!=9 && $item['column_position']!='6')
       @if($item['function_id']==11)
       @php $last_payment=$item['value']; @endphp
       @elseif($item['function_id']==12)
         @php
             $adjustment=$item['value'];
           $adjustment_col=$item['column_name']; @endphp
       @elseif ($item['function_id']==4)
       @php $previous_due_col=$item['column_name']; @endphp
        @else
       @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
       <a target="_BlANK" href="{{$item['value']}}">{{$item['column_name']}}</a>
       @else
       @if ($item['datatype']=='money')
       <p class="text-sm  text-gray-500 mt-1">{{$info['currency_icon']}}{{$item['column_name']}}: {{number_format($item['value'],2)}}</p>
      
       @elseif($item['datatype']=='date')
      
       @else
       <p class="text-sm  text-gray-500 mt-1">{{$item['column_name']}}: {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
    
      
       @endif
       @endif
      
       @endif
       @endif
      
       @endforeach
        </div>
        <div>
           
            @isset($info['payment_mode'])
            <p class="text-sm  text-gray-500 mt-1">Payment Method: {{$info['payment_mode']}}</p>
            @endisset
            @isset($info['transaction_id'])
            <p class="text-sm  text-gray-500 mt-1">Transaction Number: {{$info['transaction_id']}}</p>
            @endisset
           
        </div>
        {{-- <div>
            <div class="h-0.5 w-full bg-gray-200"></div>
            @foreach ($metadata['invoice'] as $item)
        @if( $item['value']!="")
            <p class="text-xs  text-gray-500 mt-2">{{$item['column_name']}}: {{$item['value']}}</p>
            @endif
            @endforeach
        </div> --}}
       
    
    </div>
   
     
   
        <div class='overflow-x-auto w-full mt-6'>
            @php
            $sr_no=1;
            $total=0;
                        $taxtotal=0;
        @endphp
            <table class=' mx-auto  w-full whitespace-nowrap bg-white divide-y-2 divide-[{{$colors}}] overflow-hidden'>
                <thead >
                    <tr class="text-black text-left bg-[{{$colors}}]">
                        @foreach ($table_heders as $item )
                        <th class="font-semibold text-sm text-white  px-2 py-2 @if($item!='item') text-center @endif"> {{$item}} </th>
                    @endforeach       
                    </tr>
                </thead>
                <tbody class="divide-y divide-[{{$colors}}]">
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
                                    @endforeach
                                </tr>
                                @php
                                $sr_no=$sr_no+1;
                            @endphp
                                @endforeach
                  
                
                </tbody>
            </table>
       
    </div>

    <div class="border-t-2 border-[{{$colors}}]  w-full "></div>
    <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-2 gap-0 p-0">
        <div>
            @isset($info['tnc'])
            @if($info['tnc']!='')
            <p class="text-sm font-semibold mt-2">Terms & conditions</p>
           @php
           
               echo $info['tnc'];
           @endphp
            @endif
             @endisset  </div>
      
        <div>
        <div class="flex justify-between mb-0 px-2 py-1 mt-2">
            <div class="text-gray-900 text-sm uppercase  flex-1 text-left ">Sub Total</div>
            <div class="text-right w-20">
                <div class="text-gray-900 text-sm" x-html="netTotal">{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
            </div>
        </div>
        @foreach ($metadata['tax'] as $key=>$item)
        <div class="flex justify-between mb-0 px-2 py-2 border-t border-[{{$colors}}] mt-1">
            <div class="text-sm text-gray-900 uppercase  flex-1 text-left">{{$item['tax_name']}}</div>
            <div class="text-right w-20">
                <div class="text-sm text-gray-900" x-html="totalGST">{{$info['currency_icon']}}{{$item['tax_amount']}}</div>
            </div>
        </div>
        @php
                $taxtotal=$taxtotal+$item['tax_amount'];
             @endphp
                @endforeach
                @php
                $total=$info['basic_amount']+$info['tax_amount'];
           @endphp
    
        <div class="py-1 border-t border-[{{$colors}}] ">
            <div class="flex justify-between px-2 py-1">
                <div class="text-sm text-gray-900 uppercase  flex-1 text-left ">Total</div>
                <div class="text-right w-20">
                    <div class="text-sm text-gray-900" x-html="netTotal">{{$info['currency_icon']}}{{ number_format($total,2)}}</div>
                </div>
            </div>
           
        </div>
       
        @isset($info['previous_due'])
                @if($info['previous_due']>0)
               
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1">{{$previous_due_col}}</div>
                                <div class="text-right w-20">
                                    <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}}</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                @endisset
               
              
                @if($adjustment>0)
                
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1">{{$adjustment_col}}</div>
                                <div class="text-right w-20">
                                    <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($adjustment,2)}}</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                      
              
                @isset($info['advance'])
                @if($info['advance']>0)
                
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1"> Advance Received</div>
                                <div class="text-right w-20">
                                    <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($info['advance'],2)}}</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                @endisset
                @isset($info['paid_amount'])
                @if($info['paid_amount']>0)
                
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1">  Paid Amount</div>
                                <div class="text-right w-20">
                                    <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}}</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                @endisset
                @isset($info['late_fee'])
                @if($info['late_fee']>0)
                
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1"> Late Fee</div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}}</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                @endisset
                @isset($metadata['plugin']['has_coupon'])
                @if($metadata['plugin']['has_coupon'])
                
                        <div class="py-1 border-t border-[{{$colors}}] ">
                            <div class="flex justify-between px-2 py-1">
                                <div class="text-sm text-gray-900 uppercase  flex-1"> Coupon Discount</div>
                                <div class="text-right w-20">
                                    <div class="text-sm text-gray-900 " > 0.00</div>
                                </div>
                            </div>
        
                        </div>
                @endif
                @endisset
              
                @if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
               <div class="py-1 border-t border-[{{$colors}}] ">
                    <div class="flex justify-between px-2 py-1">
                        <div class="text-sm text-gray-900 uppercase  flex-1">GRAND TOTAL</div>
                        <div class="text-right">
                            <div class="text-sm text-gray-900 " >{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
                        </div>
                    </div>

                </div>
                @endif
                <div class="py-1 border-t border-[{{$colors}}]">
                    <div class="flex justify-between px-2 py-1">
                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1 text-left ">Amount Due</div>
                        <div class="text-right ">
                            <div class="text-sm text-[{{$colors}}]" x-html="netTotal">{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
                        </div>
                    </div>
                   
                </div>

                   {{-- @isset($info['pan'])
                   @if($info['pan']!='' && $panontop==0)
                   
                   
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-[{{$colors}}] uppercase  flex-1">PAN NO.</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-900 " >{{$info['pan']}}</div>
                            </div>
                        </div>
    
                    </div>
                    @endif
                    @endisset --}}
                    {{-- @if($info['tan']!='' && $tanontop==0)
                   

                            <div class="py-1 border-t border-[{{$colors}}] ">
                                <div class="flex justify-between px-2 py-1">
                                    <div class="text-sm text-[{{$colors}}] uppercase  flex-1">TAN No.</div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-900 " >{{$info['tan']}}</div>
                                    </div>
                                </div>
            
                            </div>

                       
                        @endif --}}
                     
                               {{-- @isset($info['gst_number'])
                               @if($info['gst_number']!='' && $gstontop==0)
                              
                                    <div class="py-1 border-t border-[{{$colors}}] ">
                                <div class="flex justify-between px-2 py-1">
                                    <div class="text-sm text-[{{$colors}}] uppercase  flex-1">GST Number</div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-900 " >{{$info['gst_number']}}</div>
                                    </div>
                                </div>
            
                            </div>
                                   @endif
                                   @endisset --}}
                              
                                   {{-- @isset($info['registration_number'])
                                   @if($info['registration_number']!='')
                                  
                                       <div class="py-1 border-t border-[{{$colors}}] ">
                                        <div class="flex justify-between px-2 py-1">
                                            <div class="text-sm text-[{{$colors}}] uppercase  flex-1">S. Tax Regn.</div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-900 " >{{$info['registration_number']}}</div>
                                            </div>
                                        </div>
                    
                                    </div>
                                       @endif
                                       @endisset --}}
                         
                                         
                                          
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
    <div class="border-t-2 border-[{{$colors}}]  w-full "></div>


{{-- end paading div --}}

<div class="mt-10 lg:mt-20 md:mt-20">
<div class=" text-sm  flex items-center uppercase text-gray-600 justify-self font-regular mt-2">
Thank you for your purchase.
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

<div class="w-full  mt-1" style="max-width: 910px">
@include($footers)

</div>
@endif
@endif
</div>