<div class=" w-full flex flex-col items-center justify-center min-h-screen  " >
    <div class="w-full lg:w-5/6 md:w-4/5">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="font-rubik w-full lg:w-5/6 md:w-4/5  bg-white  shadow-2xl mb-4   p-4  lg:p-10 md:p-10" style="max-width: 910px">
@else
<div class="font-rubik w-full   bg-white  shadow-2xl      p-4  lg:p-10 md:p-10" style="max-width: 910px">
@endif
    <div class="grid grid-cols-1 lg:grid-cols-4 md:grid-cols-4   mt-0">
        <div class="mt-2 lg:mt-8 md:mt-8 lg:p-2 md:p-2">
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap justify-self lg:justify-center md:justify-center ">
       
        <img class=" h-8 lg:h-20 md:h-12 "src="/uploads/images/logos/{{$info['image_path']}}">          
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
        <div class="col-span-3">
            <h1 class="text-sm uppercase font-bold">Supplier</h1>
            <p class="text-sm mt-1 py-0.5 font-semibold" >{{$metadata['header'][0]['value'] }}</p>
            @if ($info['main_company_name']!='')
            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                {{$info['main_company_name']}})</span>
      @endif
            @foreach ($metadata['header'] as $item)
            @if($item['column_name']=='Merchant address' && $item['value']!='')
            <p class="text-sm py-0.5 font-semibold">{{$item['value'] }}</p>
            @endif
              @endforeach
            @foreach ($metadata['header'] as $item)
            @if($item['column_name']!='Company name' && $item['value']!='' && $item['column_name']!='Merchant address')
            <p class="text-sm py-0.5 font-semibold">{{$item['value']}}</p>
            @endif
            @endforeach
        </div>
       
           
        

    {{-- <div class="block lg:hidden md:hidden">  
        <div class="border-t-2  border-[{{$colors}}] pt-2 mt-2" ></div>
        @foreach ($metadata['invoice'] as $item)
        @if($item['function_id']=='9' && !empty($item['value']))
        <h1 class="text-sm uppercase font-bold">{{$item['column_name']}}</h1>
        <p class="text-sm   text-left lg:text-right md:text-right" >{{$item['value']}}</p>
        @endif
        @endforeach

        @foreach ($metadata['invoice'] as $item)
        @if($item['value']!="" && $item['function_id']!="9")
        @if($item['function_id']==11)
        @php $last_payment=$item['value']; @endphp
             @elseif($item['function_id']==12)
               @php
                   $adjustment=$item['value'];
                 $adjustment_col=$item['column_name']; @endphp
             @elseif ($item['function_id']==4)
             @php $previous_due_col=$item['column_name']; @endphp
            @else
        <p class="text-sm py-0.5 uppercase font-bold" >{{$item['column_name']}}</p>

        @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
        <a target="_BlANK" class="text-sm py-0.5 text-left lg:text-right md:text-right" href="{{$item['value']}}">{{$item['column_name']}}</a>
        @else
        @if ($item['datatype']=='money')
        <p class="text-sm py-0.5 text-left lg:text-right md:text-right">{{number_format($item['value'],2)}} </p>
        @elseif($item['datatype']=='date')
        <p class="text-sm py-0.5 text-left lg:text-right md:text-right">{{date("d-M-Y", strtotime($item['value']))}} </p>
        @else
        <p class="text-sm py-0.5 text-left lg:text-right md:text-right"> {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
       @endif
       @endif
       @endif
       @endif
       @endforeach
    </div> --}}

        </div>

        <div class="border-t-2  border-[{{$colors}}] pt-2 mt-2" ></div>
        <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2  gap-4">
          
            <div>
                <h1 class="text-sm uppercase font-bold ">Client</h1>
                <p class="text-sm   py-0.5" >{{$metadata['customer'][1]['value'] }}</p>
                @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
                <p class="text-sm py-0.5"> {{$item['value']}}</p>
                @endif
                @endforeach
 
            </div>
            <div >
                <table class="w-full">
                @foreach ($metadata['invoice'] as $item)
                @if($item['function_id']=='9' && !empty($item['value']))
                <tr>
                    <td> <p class="text-sm uppercase font-bold">{{$item['column_name']}}</p></td>
                   <td> <p class="text-sm   text-left " >{{$item['value']}}</p></td>
                </tr>
                    @endif
                    @endforeach
    
                    @foreach ($metadata['invoice'] as $item)
                            @if($item['value']!="" && $item['function_id']!="9")
                            @if($item['function_id']==11)
                            @php $last_payment=$item['value']; @endphp
                            @elseif($item['function_id']==12)
                              @php
                                  $adjustment=$item['value'];
                                $adjustment_col=$item['column_name']; @endphp
                            @elseif ($item['function_id']==4)
                            @php $previous_due_col=$item['column_name']; @endphp
                    @else
                 <tr>
                    <td> <p class="text-sm uppercase font-bold">{{$item['column_name']}}</p></td>
                    <td >
                    @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                    <a target="_BlANK" class="text-sm  text-left " href="{{$item['value']}}">{{$item['column_name']}}</a>
                    @else
                    @if ($item['datatype']=='money')
                    <p class="text-sm  text-left ">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
                    @elseif($item['datatype']=='date')
                    <p class="text-sm  text-left ">{{date("d-M-Y", strtotime($item['value']))}} </p>
                    @else
                    <p class="text-sm  text-left ">{{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
                   
                    @endif
                   @endif
                </td>
                 </tr>
                    @endif
                    @endif
                  @endforeach
                  @isset($info['payment_mode'])
                  <tr>
                    <td> <p class="text-sm uppercase font-bold">Payment Method</p></td>
                   <td> <p class="text-sm   text-left " >{{$info['payment_mode']}}</p></td>
                </tr>
                @endisset
                @isset($info['transaction_id'])
                <tr>
                  <td> <p class="text-sm uppercase font-bold">Transaction Number</p></td>
                 <td> <p class="text-sm   text-left " >{{$info['transaction_id']}}</p></td>
              </tr>
              @endisset
                </table>      
        </div>
            
          
           
            
       

        

            </div>
     
              <div class='overflow-x-auto w-full mt-6'>
                @php
                $sr_no=1;
                $total=0;
                        $taxtotal=0;
            @endphp
                <table class= ' mx-auto  w-full whitespace-nowrap bg-white  overflow-hidden'>
                    <thead >
                        <tr class="text-black text-left bg-[{{$colors}}]">
                            @foreach ($table_heders as $item )
                            <th class=" border border-[{{$colors}}] uppercase font-semibold text-sm text-white  px-2 py-2 @if($item!='item') text-center @endif"> {{$item}} </th>
                        @endforeach   
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($metadata['particular'] as $key=>$item)
                        <tr>
                            @foreach ($table_heders as $k=>$h )
                        
                            <td class="px-2 py-2 @if($k!='item') text-center @endif border border-[{{$colors}}]">
                               
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
                             
                                <tr>
                                    <td colspan="{{count($table_heders)-2}}"  rowspan="11" class="px-2 py-2 text-left ">
                                   
                                        @isset($info['tnc'])
                                        @if($info['tnc']!='')
                                        <div>
                                        <p class="text-sm font-semibold mt-2">Terms & conditions</p>
                                      
                                       @php
                                       
                                           echo $info['tnc'];
                                        
                                       @endphp
                                        </div>
                                        @endif
                                         @endisset 
                                     
                                    </td>
                                <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                                    <p class="text-sm">Sub Total</p></td>
                                    <td class="px-2 py-2 text-center border border-[{{$colors}}]">
                                        <p class="text-sm">{{$info['currency_icon']}}{{$info['basic_amount']}} </p></td>
                               
                            </tr>
                            @foreach ($metadata['tax'] as $key=>$item)
                            <tr>
                                
                            <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                                <p class="text-sm">{{$item['tax_name']}}</p></td>
                                <td class="px-2 py-2 text-center border border-[{{$colors}}]">
                                    <p class="text-sm">{{$info['currency_icon']}}{{$item['tax_amount']}}</p></td>
                           
                        </tr>
                        @php
                        $taxtotal=$taxtotal+$item['tax_amount'];
                     @endphp
                        @endforeach
                        @php
                        $total=$info['basic_amount']+$info['tax_amount'];
                   @endphp
<tr>
 
<td class="px-2 py-2 text-left border border-[{{$colors}}]">
    <p class="text-sm">Total </p></td>
    <td class="px-2 py-2 text-center border border-[{{$colors}}]">
        <p class="text-sm"> {{$info['currency_icon']}}{{ number_format($total,2)}} </p></td>

</tr>
@isset($info['previous_due'])
@if($info['previous_due']>0)
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">{{$previous_due_col}}</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">{{$info['currency_icon']}}{{number_format($info['previous_due'],2)}} </p>
           </td>
        </tr>
@endif
@endisset


@if($adjustment>0)
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">{{$adjustment_col}}</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">{{$info['currency_icon']}}{{number_format($adjustment,2)}} </p>
           </td>
        </tr>
@endif

@isset($info['advance'])
@if($info['advance']>0)
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">  Advance Received</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">{{$info['currency_icon']}}{{number_format($info['advance'],2)}} </p>
           </td>
        </tr>
@endif
@endisset
@isset($info['paid_amount'])
@if($info['paid_amount']>0)
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">  Paid Amount</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">{{$info['currency_icon']}}{{number_format($info['paid_amount'],2)}} </p>
           </td>
        </tr>
@endif
@endisset
@isset($info['late_fee'])
@if($info['late_fee']>0)
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">  Late Fee</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">{{$info['currency_icon']}}{{number_format($info['late_fee'],2)}} </p>
           </td>
        </tr>
@endif
@endisset
@isset($metadata['plugin']['has_coupon'])
@if($metadata['plugin']['has_coupon'])
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm "> Coupon Discount</p>
          
       </td>
       <td class="px-2 py-2 text-center border border-[{{$colors}}]">
          
            <p class="text-sm">  0.00</p>
           </td>
        </tr>
@endif
@endisset

@if($total != $info['absolute_cost'] || (isset($metadata['plugin']['has_coupon']) && $metadata['plugin']['has_coupon']))
        
<tr>
   
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
       <p class="text-sm font-semibold ">GRAND TOTAL</p>
      
   </td>
    <td class="px-2 py-2 text-center border border-[{{$colors}}]"> <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format($info['absolute_cost'],2)}} </p></td>
   
</tr>
@endif
{{-- <tr>
    <td colspan="{{count($table_heders)-2}}" class="px-2 py-2 text-left ">
    </td>
   @isset($info['pan'])
   @if($info['pan']!='' && $panontop==0)
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
       <p class="text-sm  ">PAN NO.</p>
      
   </td>
   <td class="px-2 py-2 text-left border border-[{{$colors}}]">
        <p class="text-sm">{{$info['pan']==''?'NA':$info['pan']}}</p></td>
      
    </tr>
    @endif
    @endisset
    @if($info['tan']!='' && $tanontop==0)
    <tr>
        <td colspan="{{count($table_heders)-2}}" class="px-2 py-2 text-left ">
        </td>
      
       <td class="px-2 py-2 text-left border border-[{{$colors}}]">
           <p class="text-sm ">TAN No.</p>
          
       </td>
       <td class="px-2 py-2 text-left border border-[{{$colors}}]">
            <p class="text-sm">{{$info['tan']==''?'NA':$info['tan']}}</p></td>
        </tr>
        @endif
       
               @isset($info['gst_number'])
               @if($info['gst_number']!='' && $gstontop==0)
               <tr>
                <td colspan="{{count($table_heders)-2}}" class="px-2 py-2 text-left ">
                </td>
                  <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                      <p class="text-sm ">GST Number  </p>
                     
                  </td>
                  <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                       <p class="text-sm ">{{$info['gst_number']==''?'NA':$info['gst_number']}} </p></td>
                   </tr>
                   @endif
                   @endisset
              
                   @isset($info['registration_number'])
                   @if($info['registration_number']!='')
                   <tr>
                    <td colspan="{{count($table_heders)-2}}" class="px-2 py-2 text-left ">
                    </td>
                      <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                          <p class="text-sm ">S. Tax Regn.</p>
                         
                      </td>
                      <td class="px-2 py-2 text-left border border-[{{$colors}}]">
                           <p class="text-sm ">{{$info['registration_number']==''?'NA':$info['registration_number']}} </p></td>
                       </tr>
                       @endif
                       @endisset
                   --}}
              
        
<tr >
   
<td class="px-2 py-2 text-left border border-[{{$colors}}] bg-[{{$colors}}]">
    <p class="text-sm text-white">AMOUNT DUE</p></td>
    <td class="px-2 py-2 text-center border border-[{{$colors}}] bg-[{{$colors}}]">
        <p class="text-sm text-white">{{$info['currency_icon']}}{{$info['absolute_cost']}} </p></td>

</tr>
<tr>
   
    <td colspan="2" class=" px-2 py-2 text-left border border-[{{$colors}}] ">
       
        <p class="text-sm font-bold text-black-900">Amount(in words):<span class="font-normal">{{$info['absolute_cost_words']}}</span></p>  
              
           
    </td>
    
   
   
</tr>
                      
                    
                    </tbody>
                </table>
               
        </div>


      
      
        
        
    {{-- end paading div --}}
  
<div class="mt-12">
<div class="border-t  border-[{{$colors}}] pt-2 mt-12" ></div>
@isset($metadata['plugin']['has_signature'])
@if($metadata['plugin']['has_signature']!=1)
<div>
   <div  class="mt-0  py-1.5 text-left ">
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
<div class=" text-sm  flex items-center  text-gray-600 justify-self font-bold mt-2">
   
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

<div class="w-full mt-1" style="max-width: 910px">
@include($footers)

</div>
@endif
@endif
</div>