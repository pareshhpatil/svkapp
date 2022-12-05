




<div class=" w-full flex flex-col items-center justify-center min-h-screen  " style="max-width: 910px">
    <div class="w-full lg:w-5/6 md:w-4/5">
        @if($info['its_from']!='preview')
        @include('app.merchant.invoiceformat.invoice_header')  
        @endif
    </div>
    @if($info['its_from']=='preview')
<div class="w-full lg:w-4/6 md:w-4/5 bg-white shadow-2xl    font-rubik m-2" id="tab">
 @else
 <div class="w-full lg:w-5/6 md:w-4/5 bg-white shadow-2xl   font-rubik m-2" id="tab">
@endif
    <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3  gap-2 ">
        <div class="mt-2 lg:mt-4 md:mt-4 lg:p-2 md:p-2">
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap justify-self lg:justify-center md:justify-center ">
                <img class=" h-8 lg:h-12 md:h-10 " src="/uploads/images/logos/{{$info['image_path']}}">    
                    </section> 
                    @endif
    </div>

        <div class="col-span-2">
            <h1 class="text-2xl  font-normal text-cyan-600">{{$metadata['header'][0]['value'] }}</h1>
            @if ($info['main_company_name']!='')
            <span class="muted" style="font-size: 12px;"> (An official franchisee of
                {{$info['main_company_name']}})</span>
      @endif

@php
 $gstontop=0;
 $panontop=0;
$tanontop=0;
$cinontop=0;
@endphp

            @foreach ($metadata['header'] as $item)
      @if($item['column_name']=='Merchant address' && $item['value']!='')
      <p class="text-sm py-0.5">{{$item['value'] }}</p>
      @endif
        @endforeach
            @foreach ($metadata['header'] as $item)
            @if($item['column_name']!='Company name' && $item['value']!='' && $item['column_name']!='Merchant address')
            <p class="text-sm py-0.5">{{ucfirst(str_replace('Merchant ', '',$item['column_name']))}}:{{$item['value']}}</p>
            @endif
            @if($item['column_name']=='GSTIN Number')
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
            @endif

            @endforeach   
            
        </div>
     

  

        </div>

        <div class="border-t border-gray-700 mt-2" ></div>
        <div class="bg-[{{$colors}}] p-2">
            <h1 class="text-lg text-center font-bold text-black-900">
          @if ($info['invoice_type']==2)
            {{$metadata['plugin']['invoice_title']}}
            @else
           
              @if(!empty($metadata['tax']))
              Tax Invoice
              @else
              Invoice
              @endif
            </h1>
            @endif
        </div>
        <div class="border-t border-gray-700" ></div>
        <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 ">
            <div>
                <table class= 'border-collapse border border-gray-200  mx-auto  w-full  bg-white divide-y-2 divide-cyan-600 overflow-hidden'>
                   
                    <tbody class="divide-y divide-cyan-600 ">

                        @foreach ($metadata['customer'] as $key=>$item)
                            <tr>
                              
                            
                            <td class="px-0.5 py-0.5 text-left border border-gray-200">
                               
                                        <p class="text-black-900 text-sm font-bold">{{$item['column_name']}}</p>
                                      
                                   
                            </td>
                            <td class="px-0.5 py-0.5 text-left border border-gray-200">
                                <p class="text-sm">{{$item['value']}} </p>
                               
                            </td>
                            
                               </tr>
                               @endforeach
                              
                      
                                      
                                          
                    </tbody>
                </table>
            </div>
            <div>
                
                <table class= 'border-collapse border border-gray-200  mx-auto  w-full  bg-white divide-y-2 divide-cyan-600 overflow-hidden'>
                   
                    <tbody class="divide-y divide-cyan-600 ">
                        @php
                              $last_payment=NULL;
                            $adjustment=NULL;
                            $adjustment_col="Adjustment";
                            $previous_due_col="Previous due";
                        @endphp
                        @foreach ($metadata['invoice'] as $key=>$item)
                        @if($item['value']!="" && $item['function_id']!=4)
                       @if($item['function_id']==11)
                    {{$last_payment=$item['value']}}
                    @elseif($item['function_id']==12)
                        {{$adjustment=$item['value']}}
                        {{$adjustment_col=$item['column_name']}}
                    @elseif ($item['function_id']==4)
                        {{$previous_due_col=$item['column_name']}}
                        @else
                        <tr>
                            <td class="px-0.5 py-0.5 text-left border border-gray-200">
                               
                                        <p class="text-black-900 text-sm font-bold">{{$item['column_name']}}</p>
                                      
                                   
                            </td>
                            @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                            <td class="px-0.5 py-0.5 text-left border border-gray-200">
                                <a target="_BlANK" href="{{$item['value']}}">{{$item['column_name']}}</a>
                           
                           </td>
                            @else
                            <td class="px-0.5 py-0.5 text-left border border-gray-200">
                           
                           @if ($item['datatype']=='money')
                           <p class="text-sm">{{number_format($item['value'],2)}} </p>
                        @elseif($item['datatype']=='date')
                        <p class="text-sm">{{date("d-M-Y", strtotime($item['value']))}} </p>
                        @else
                        <p class="text-sm"> {{$item['value']}} @if($item['datatype']=='percent') %@endif</p>
                       @endif
                            </td>
                            @endif
                               </tr>
                               @endif
                             @endif
                             @endforeach
                             
                                   
                                          
                    </tbody>
                </table>
 
            </div>
       

            </div>
            <div class="border-t  border-gray-700" ></div>
            @if($info['hide_invoice_summary']!=1)
            @if ($last_payment==NULL && $adjustment==NULL)

            <div class="flex flex-row border-b bg-[{{$colors}}]  border-gray-700">
                <div class="p-2 bg-[{{$colors}}] border-r  border-gray-500">
                    <p class="text-black-900 text-sm font-bold ml-0">Billing summery</p> 
                </div>
                <div class="p-2  border-r bg-white  border-gray-700">
                    <p class="text-sm font-bold text-black-900 mr-4">Past due : <span class="font-normal">
                        @isset($info['previous_due'])
                        {{$info['previous_due']==''?'0.00':number_format($info['previous_due'],2)}}
                        @endisset
                       
                    </span></p>  
                                                              
                </div>

                <div class="p-2  border-r bg-white  border-gray-700">
                    <p class="text-sm font-bold text-black-900 mr-4">Current Charges : <span class="font-normal">{{number_format($info['basic_amount']+$info['tax_amount'],2)}}</span></p>  
                                                              
                </div>
                <div class="p-2  ">
                    <p class="text-sm font-bold text-black-900 ">Total Due : <span class="font-normal">{{number_format($info['absolute_cost'],2)}}</span></p>  
                                                              
                </div>
        
            </div>
            @else
            @if ($last_payment!=NULL && $adjustment!=NULL)
            <div class="flex flex-row border-b bg-[{{$colors}}]  border-gray-700">
                <div class="p-2 bg-[{{$colors}}]">
                    <p class="text-black-900 text-sm font-bold ml-0">Billing summery:</p> 
                </div>
            </div>
            <table class= 'border-collapse border border-gray-200  mx-auto  w-full  bg-white   overflow-hidden'>
                <thead >
                    <tr class="text-black text-center ">
                      
                       
                        <td class="py-2 px-2" style="border-right:1px solid black;border-bottom:1px solid black;">Previous Balance</td>
                        <td rowspan="2" style="border-right:1px solid black;border-bottom:1px solid black;">&nbsp; - &nbsp;</td>
                        <td style="border-right:1px solid black;border-bottom:1px solid black;">Last Payments</td>
                        <td rowspan="2" style="border-right:1px solid black;border-bottom:1px solid black;">&nbsp; - &nbsp;</td>
                        <td style="border-right:1px solid black;border-bottom:1px solid black;">{{$adjustment_col}}</td>
                        <td rowspan="2" style="border-right:1px solid black;border-bottom:1px solid black;">&nbsp; + &nbsp;</td>
                        <td style="border-right:1px solid black; border-bottom:1px solid black;">Current Bill</td>
                        <td rowspan="2" style="border-right:1px solid black;border-bottom:1px solid black;">&nbsp; = &nbsp;</td>
                        <td style="border-bottom:1px solid black;">Total Due</td>
                    </tr>
                    <tr class=" text-center ">
                        <td class="py-2 px-2" style="border-right:1px solid black; border-bottom:1px solid black;">{{number_format($info['previous_due'],2)}} </td>

                        <td style="border-right:1px solid black; border-bottom:1px solid black;">{{number_format($last_payment,2)}}</td>
                        <td style="border-right:1px solid black; border-bottom:1px solid black;">{{number_format($adjustment,2)}}</td>
                        <td style="border-right:1px solid black; border-bottom:1px solid black;">
                            {{number_format($info['basic_amount']+$info['tax_amount'],2)}}</td>
                        <td style='border-bottom:1px solid black;'>{{number_format($info['absolute_cost'],2)}}</td>
                    </tr>
                </thead>
            </table>
            @endif
            @endif
            @endif
            
            
            <div class='overflow-x-auto w-full mt-2'>
                @php
                $sr_no=1;
            $total=0;
                        $taxtotal=0;
            @endphp
                <table class= 'border-collapse border border-gray-200  mx-auto  w-full  bg-white   overflow-hidden'>
                    <thead >
                        <tr class="text-black text-left ">
                            @foreach ($table_heders as $item )
                            <th class="font-bold text-sm text-gray-500  px-0.5 py-0.5 text-center  border border-gray-200">{{$item}}</th>
                          @endforeach
                        </tr>
                    </thead>
                    <tbody >

                           @foreach ($metadata['particular'] as $key=>$item)
                            <tr>
                                @foreach ($table_heders as $k=>$h )
                         
                              <td  class="px-2 py-2 text-center border-r border-l border-gray-200 ">
                         
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

                    {{-- end particulars --}}
                            @php
                                
                          
                                             if(count($table_heders)>5)
                                             {
                                           $subtotal_colspan=2;
                                             $colspan=count($table_heders)-2;
                                             }
                                         else
                                         {
                                             $subtotal_colspan=1;
                                             $colspan=count($table_heders)-2;
                                             }
                                         @endphp

{{-- end rows --}}
                                             
                                          {{-- end particulars --}}
                                         
                                          <tr>
                                            <td colspan="{{$colspan}}"   class="border-t border-gray-200 px-0.5 py-0.5  text-left ">
                                                @isset($info['tnc'])
                                                @if($info['tnc']!='')
                                                <p class="text-sm font-semibold mt-1">Terms & conditions:</p>
                                               @php
                                               
                                                   echo $info['tnc'];
                                               @endphp
                                                @endif
                                                 @endisset 
                                            </td>
                                           
                                            <td  colspan="{{$subtotal_colspan+1}}"  >
                                           <table  class="w-full">
                                            <tr class="border-t border-gray-200">
                                               
                                               
                                                <td class="px-1.5 py-1.5 text-left border-r-0 border border-gray-200">
                                                    <p class="text-sm font-bold text-black-900">SUB TOTAL</p>
                                                   
                                                </td>
                                                <td class="px-1.5 py-1.5 border-r-0  text-center border border-gray-200 text-black-900"> <p class="text-sm font-bold">{{number_format($info['basic_amount'],2)}} </p></td>
                                                 </tr>
                                                 @foreach ($metadata['tax'] as $key=>$item)
                                                 <tr>
                                                   
                                                   
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                        <p class="text-sm ">{{$item['tax_name']}}</p>
                                                       
                                                    </td>
                                                    <td class="px-1.5 py-1.5 border-r-0 text-center border border-gray-200"> <p class="text-sm"> {{$item['tax_amount']}}</p></td>
                                                     </tr>
                                                     @php
                                                        $taxtotal=$taxtotal+$item['tax_amount'];
                                                     @endphp
                                                @endforeach
                                               
                                            <tr>
                                            <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                    <p class="text-sm font-semibold ">TOTAL</p>
                                                   
                                                </td>
                                                <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                    @php
                                                         $total=$info['basic_amount']+$info['tax_amount'];
                                                    @endphp
                                                     <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format($total,2)}} </p>
                                                    </td>
                                                 </tr>
                                                 @isset($info['previous_due'])
                                                 @if($info['previous_due']>0)
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">{{$previous_due_col}}</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">{{number_format($info['previous_due'],2)}} </p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                 @endisset
                                                 @isset($info['adjustment'])
                                               
                                                 @if($info['adjustment']>0)
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">{{$adjustment_col}}</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">{{number_format($info['adjustment'],2)}} </p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                       
                                                 @endisset
                                                 @isset($info['advance'])
                                                 @if($info['advance']>0)
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">  Advance Received</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">{{number_format($info['advance'],2)}} </p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                 @endisset
                                                 @isset($info['paid_amount'])
                                                 @if($info['paid_amount']>0)
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">  Paid Amount</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">{{number_format($info['paid_amount'],2)}} </p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                 @endisset
                                                 @isset($info['late_fee'])
                                                 @if($info['late_fee']>0)
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">  Late Fee</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">{{number_format($info['late_fee'],2)}} </p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                 @endisset
                                                 @isset($metadata['plugin']['has_coupon'])
                                                 @if($metadata['plugin']['has_coupon'])
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm "> Coupon Discount</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200">
                                                           
                                                             <p class="text-sm">  0.00</p>
                                                            </td>
                                                         </tr>
                                                 @endif
                                                 @endisset

                                                 @if($total != $info['absolute_cost'] || $metadata['plugin']['has_coupon'])
                                                 <tr>
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                        <p class="text-sm font-semibold ">GRAND TOTAL</p>
                                                       
                                                    </td>
                                                     <td class="px-1.5 py-1.5 text-center border-r-0 border border-gray-200"> <p class="text-sm font-semibold">{{number_format($info['absolute_cost'],2)}} </p></td>
                                                    
                                                 </tr>
                                                 @endif
                                                 <tr>
                                
                                                    @isset($info['pan'])
                                                    @if($info['pan']!='' && $panontop==0)
                                                    <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                        <p class="text-sm  ">PAN NO.</p>
                                                       
                                                    </td>
                                                    <td class="px-1.5 py-1.5 border-r-0 text-left border border-gray-200">
                                                         <p class="text-sm">{{$info['pan']==''?'NA':$info['pan']}}</p></td>
                                                       
                                                     </tr>
                                                     @endif
                                                     @endisset
                                                     @if($info['tan']!='' && $tanontop==0)
                                                     <tr>
                                                       
                                                       
                                                        <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                            <p class="text-sm ">TAN No.</p>
                                                           
                                                        </td>
                                                        <td class="px-1.5 py-1.5 border-r-0 text-left border border-gray-200">
                                                             <p class="text-sm">{{$info['tan']==''?'NA':$info['tan']}}</p></td>
                                                         </tr>
                                                         @endif
                                                         {{-- @if($info['cin_no']!='')
                                                         <tr>
                                                          
                                                               <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                                   <p class="text-sm ">CIN NO.</p>
                                                                  
                                                               </td>
                                                               <td class="px-1.5 py-1.5 border-r-0 text-left border border-gray-200">
                                                                    <p class="text-sm">{{$info['cin_no']==''?'NA':$info['cin_no']}}</p></td>
                                                                </tr>
                                                                
                                                                @endif --}}
                                                                @isset($info['gst_number'])
                                                                @if($info['gst_number']!='' && $gstontop==0)
                                                                <tr>
                                                                  
                                                                   <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                                       <p class="text-sm ">GST Number  </p>
                                                                      
                                                                   </td>
                                                                   <td class="px-1.5 py-1.5 border-r-0 text-left border border-gray-200">
                                                                        <p class="text-sm ">{{$info['gst_number']==''?'NA':$info['gst_number']}} </p></td>
                                                                    </tr>
                                                                    @endif
                                                                    @endisset
                                                               
                                                                    @isset($info['registration_number'])
                                                                    @if($info['registration_number']!='')
                                                                    <tr>
                                                                      
                                                                       <td class="px-1.5 py-1.5 text-left border border-gray-200">
                                                                           <p class="text-sm ">S. Tax Regn.</p>
                                                                          
                                                                       </td>
                                                                       <td class="px-1.5 py-1.5 border-r-0 text-left border border-gray-200">
                                                                            <p class="text-sm ">{{$info['registration_number']==''?'NA':$info['registration_number']}} </p></td>
                                                                        </tr>
                                                                        @endif
                                                                        @endisset
                                                                   
                                                                <tr>
                                                                    <td colspan="2" class="border-r-0 px-1.5 py-1.5 text-left border border-gray-200 ">
                                                                       
                                                                        <p class="text-sm font-bold text-black-900">Amount(in words):<span class="font-normal">{{$info['absolute_cost_words']}}</span></p>  
                                                                              
                                                                           
                                                                    </td>
                                                                    
                                                                   
                                                                   
                                                            </tr>
                                                         
                                                         
                    {{-- end rows --}}
                                                       
                                           </table>
                                            </td>
                                          </tr>
                                          @isset($metadata['plugin']['has_signature'])
                                              
                                        
                                          @if($metadata['plugin']['has_signature']!=1)
                                          <tr>
                                              <td colspan="{{$colspan+$subtotal_colspan+1}}" class="border-r-0 px-1.5 py-1.5 text-left border border-gray-200">
                                                  <p class="text-sm  py-1 px-1 mt-1 ">Note: This is a system generated invoice. No signature required.</p> 
                                              </td>
                                          </tr>
                                    @endif
                                    @endisset
                                
                                    
                                   
                                       
                                        
                                             
                    
                    </tbody>
                </table>
           
        </div>
   
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

       
        

<div class="m-4"></div>
  
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

<div class="w-full lg:w-5/6 md:w-4/5 mt-1">
@include($footers)

</div>
@endif
@endif
</div>




   