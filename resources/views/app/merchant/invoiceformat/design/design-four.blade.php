
<div class=" w-full flex flex-col items-center justify-center min-h-screen  " >
    <div class="w-full " style="max-width: 910px">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="font-rubik w-full lg:w-5/6 md:w-4/5  bg-white  shadow-2xl p-4 mb-4  lg:p-10 md:p-10" id="tab" style="max-width: 910px">
  @else
  <div class="font-rubik w-full   bg-white  shadow-2xl p-4  lg:p-10 md:p-10" id="tab" style="max-width: 910px">
@endif

    <div class="p-4  lg:p-2 md:p-2 mt-0">


        <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 gap-2">
            <div class="mt-1   p-1">
                @if(!empty($info['image_path']))
                <section class="flex flex-wrap justify-center  ">
                    <img class="max-h-20 lg:max-h-32 md:max-h-28 " src="{{$info['image_path']}}" >     
        </section>
        @endif  
        </div>
        {{-- @php
        $gstontop=0;
        $panontop=0;
       $tanontop=0;
       $cinontop=0;
       @endphp --}}
         @php
         $last_payment=NULL;
       $adjustment=NULL;
       $adjustment_col="Adjustment";
       $previous_due_col="Previous due";
   @endphp
               <div class="col-span-2">
                <div class="d-none d-lg-block  h-0.5  w-full bg-[{{$colors}}] "></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-2 justify-left">
                   
                    <div >
                        <h1 class="text-3xl  font-medium tracking-widest text-[{{$colors}}]">
                            @if ($info['invoice_type']==2)
                              {{$metadata['plugin']['invoice_title']}}
                              @else
                             
                                @if(!empty($metadata['tax']))
                                Tax Invoice
                                @else
                                Invoice
                                @endif
                              
                              @endif
                        </h1>
                        <br/> 
                         @foreach ($metadata['invoice'] as $item)
                        @if($item['value']!='' && $item['function_id']=='9')
                       
                        <p class="mt-1 border-b border-gray-400"><span class="mt-1 text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black">{{$item['value']}} </span></p>
         
                        @endif
                        @endforeach
                    
                        @foreach ($metadata['invoice'] as $item)
                        @if($item['value']!="" && $item['datatype']=='date')
                        @if($item['function_id']==12)
                          @php
                              $adjustment=$item['value'];
                            $adjustment_col=$item['column_name']; @endphp
                        @elseif ($item['function_id']==4)
                        @php $previous_due_col=$item['column_name']; @endphp
                @else
           
             @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
             <p class="mt-1 border-b border-gray-400"><span class="mt-1 text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black"> <a target="_BlANK" class="text-sm  text-white font-semibold text-left lg:text-right md:text-right" href="{{$item['value']}}">{{$item['column_name']}}</a></span></p>
         
            @else
            @if ($item['datatype']=='money')
            <p class="text-sm uppercase text-white font-semibold text-left lg:text-right md:text-right">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
            @elseif($item['datatype']=='date')
              <p class="mt-1 border-b border-gray-400"><span class="mt-1 text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black"><x-localize :date="$item['value']" type="date" /></span></p>
         @else
             <p class="mt-1 border-b border-gray-400"><span class="mt-1 text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black">{{$item['value']}} @if($item['datatype']=='percent') %@endif</span></p>
                     
            @endif
           @endif
                                  
           @endif
           @endif


                      @endforeach
                      
                       
     
               </div>
                <div class="ml-0 lg:ml-4 md:ml-4  ">
                    
                    <h1 class="text-sm   font-bold text-black sm:mt-2 mt-1">Supplier</h1>
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

                </div>
              

           </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3 gap-2 mt-4">
    <div class="border-t-2 border-dotted border-gray-400 pt-4">
      
        <h1 class="text-sm font-bold text-black ">Client</h1>
        <p class="text-sm py-0.2  font-bold" >{{$metadata['customer'][1]['value'] }}</p>
        @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
                <p class="text-sm py-0.2"> {{$item['value']}}</p>
                @endif
                @endforeach
   </div>
   <div class="border-t-2 border-dotted border-gray-400 pt-4 ">
    @foreach ($metadata['invoice'] as $item)
    @if($item['value']!="" && $item['function_id']!="9" && $item['datatype']!='date')
    @if($item['function_id']==12)
      @php
          $adjustment=$item['value'];
        $adjustment_col=$item['column_name']; @endphp
    @elseif ($item['function_id']==4)
    @php $previous_due_col=$item['column_name']; @endphp
@else

@if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
<p class="py-0.2" ><span class="text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black"> <a target="_BlANK" class="text-sm  text-white font-semibold text-left lg:text-right md:text-right" href="{{$item['value']}}">{{$item['column_name']}}</a></span></p>

@else
@if ($item['datatype']=='money')
<p class="py-0.2"><span class="  text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black">{{number_format($item['value'],2)}} </span></p>
@else
<p class="py-0.2"><span class="  text-black text-sm font-semibold " >{{$item['column_name']}}: </span><span class="text-sm text-black">{{$item['value']}} @if($item['datatype']=='percent') %@endif</span></p>
 
@endif
@endif
              
@endif
@endif


  @endforeach
</div>
      
      
         
        <div class="ml-0 lg:ml-4 md:ml-4 border-t-2 border-dotted border-gray-400 pt-4">
            
            @isset($info['payment_mode'])
               <p class="text-left py-0.2"><span class=" text-black text-sm font-semibold" >Payment Method </span> <span class="ml-2 text-sm text-black">{{$info['payment_mode']}}</p></p>
                @endisset
                @isset($info['transaction_id'])
                <p class="text-left py-0.2"><span class=" text-black text-sm font-semibold" >Transaction Number</span> <span class="ml-2 text-sm text-black">{{$info['transaction_id']}}</p></p>
                    @endisset
    </div>

       

   
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3  gap-4 mt-4">
<div class="d-none d-lg-block   border-t-2  border-[{{$colors}}] pt-2">

</div>
<div class="col-span-2 border-t-2 border-dotted border-gray-400 pt-2"></div>
</div>
           
            
            
           
                <div class='overflow-x-auto w-full mt-4'>
                    @php
                    $sr_no=1;
                    $total=0;
                        $taxtotal=0;
                @endphp
                @if (!empty($table_heders))
                    <table class=' mx-auto  w-full rounded-lg bg-white divide-y-2 divide-[{{$colors}}] overflow-hidden'>
                        <thead >
                            <tr class="text-black text-left">
                                @foreach ($table_heders as $item )
                                <th class="font-semibold text-sm text-gray-800  px-2 py-2 @if($item!='item') text-center @endif">{{$item}} </th>
                            @endforeach  
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dashed divide-gray-200 ">
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
               @endif
            </div>

            <div class="border-t border-dashed border-gray-200  w-full "></div>
            <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-2 gap-4 p-0">
                <div style="padding-top:10px;">
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
                     @endisset  </div>
                <div>
				<div class="flex justify-between mb-0 px-2 py-1 mt-2">
					<div class="text-gray-700 text-sm uppercase  flex-1">Sub Total</div>
					<div class="text-right">
						<div class="text-gray-700 text-sm" >{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
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
				<div class="flex justify-between mb-0 px-2 py-2 border-t border-[{{$colors}}]">
					<div class="text-sm text-gray-700 uppercase  flex-1">{{$item['tax_name']}}</div>
					<div class="text-right ">
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
			
				<div class="border-t border-[{{$colors}}]">
					<div class="flex justify-between px-2 py-2">
						<div class="text-sm text-gray-700 uppercase  flex-1">Total</div>
						<div class="text-right">
							<div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{ number_format($total,2)}}</div>
						</div>
					</div>
                   
				</div>
                 
            @isset($info['previous_due'])
            @if($info['previous_due']>0)
           
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1">{{$previous_due_col}}</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
          
          
            @if($adjustment>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1">{{$adjustment_col}}</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($adjustment,2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
                  
          
            @isset($info['advance'])
            @if($info['advance']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1"> Advance Received</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['advance'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['paid_amount'])
            @if($info['paid_amount']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1">  Paid Amount</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($info['late_fee'])
            @if($info['late_fee']>0)
            
                    <div class="py-1 border-t border-[{{$colors}}] ">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1"> Late Fee</div>
                            <div class="text-right">
                                <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}}</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
            @isset($metadata['plugin']['has_coupon'])
            @if($metadata['plugin']['has_coupon'])
            
                    <div class="py-1 border-t border-[{{$colors}}]">
                        <div class="flex justify-between px-2 py-1">
                            <div class="text-sm text-gray-700 uppercase  flex-1"> Coupon Discount</div>
                            <div class="text-right ">
                                <div class="text-sm text-gray-800" > 0.00</div>
                            </div>
                        </div>
    
                    </div>
            @endif
            @endisset
          
            @if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
           <div class="py-1 border-t border-[{{$colors}}] ">
                <div class="flex justify-between px-2 py-1">
                    <div class="text-sm text-gray-700 uppercase  flex-1">GRAND TOTAL</div>
                    <div class="text-right">
                        <div class="text-sm text-gray-800 " >{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}}</div>
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
                            <div class="text-sm text-gray-800 " >{{$info['pan']}}</div>
                        </div>
                    </div>

                </div>
                @endif
                @endisset
                @if($info['tan']!='' && $tanontop==0)
               

                        <div class="py-1 border-t border-[{{$colors}}] ">
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
                          
                                <div class="py-1 border-t border-[{{$colors}}] ">
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
                              
                                   <div class="py-1 border-t border-[{{$colors}}] ">
                                    <div class="flex justify-between px-2 py-1">
                                        <div class="text-sm text-[{{$colors}}] uppercase  flex-1">S. Tax Regn</div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-800 " >{{$info['registration_number']}}</div>
                                        </div>
                                    </div>
                
                                </div>
                                   @endif
                                   @endisset --}}
                                  
                <div class=" border-t border-[{{$colors}}]  border-b-2">
					<div class="flex justify-between">
						<div class="text-sm text-[{{$colors}}] uppercase px-2 py-2  flex-1">Amount Due</div>
						<div class="text-right bg-[{{$colors}}]">
							<div class="text-sm text-white px-2 py-2 " >{{$info['currency_icon']}}{{$info['absolute_cost']}}</div>
						</div>
					</div>
                   
				</div>
                <div class="py-1 border-t border-[{{$colors}}] ">
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


            <div class=" grid grid-cols-1 lg:grid-cols-2  md:grid-cols-2   gap-0 lg:gap-2 md:gap-2 mb-2 ">

                <div >
                    <div class="flex flex-col mt-2 ">



                        <p class="mt-0 font-semibold"> {{$info['footer_note']}}</p>

                        <div class=" grid grid-cols-1 mt-7 ">
                            
                           
                        </div>

                       
                </div>
                </div>
               
                
    
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