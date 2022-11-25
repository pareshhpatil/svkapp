				 
												 
												 @php
                                                 $scn=0;
                                             @endphp
                                             <div class="w-full flex flex-col items-center justify-center" style="max-width: 910px">
                                                 @if($info['its_from']!='preview')
                                                 <div class="w-full lg:w-5/6 md:w-4/5">
                                                     @include('app.merchant.invoiceformat.invoice_header')  
                                                     </div>
                                                     @endif
                                                     @if($info['its_from']=='preview')
                                             <div class="w-full lg:w-5/6 md:w-4/5  bg-white drop-shadow  border-1 rounded  border-gray font-rubik p-2 mb-4" id="tab">
                                               @else
                                               <div class="w-full lg:w-5/6 md:w-4/5  bg-white drop-shadow  border-1 rounded  border-gray font-rubik p-2" id="tab">
                                             
                                               @endif
                                                 <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3  gap-2 ">
                                                     <div class="mt-2 lg:mt-8 md:mt-8 lg:p-2 md:p-2">
                                                         @if(!empty($info['image_path']))
                                                         <section class="flex flex-wrap justify-self lg:justify-center md:justify-center ">
                                                             <img class=" h-8 lg:h-12 md:h-10 " src="{{$info['image_path']}}">    
                                                                 </section>   
                                                         @endif
                                                        
                                                 </div>
                                             
                                                     <div class="col-span-2">
                                                         <h1 class="text-2xl  font-medium text-cyan-600">{{$metadata['header'][0]['value'] }}</h1>
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
                                                      
                                                         <p class="text-sm py-0.5">{{ucfirst(str_replace('Merchant ', '',$item['column_name']))}}:{{$item['value']}}</p>
                                                         @endif
                                                         @endforeach
                                                     </div>
                                                  
                                             
                                               
                                             
                                                     </div>
                                             
                                                    
                                                     <div class="bg-[{{$colors}}] p-1 mt-2">
                                                         @if ($info['invoice_type']==2)
                                                         <h1 class="text-md text-center font-semibold text-white">{{$metadata['plugin']['invoice_title']}}</h1>
                                                         @else
                                                         <h1 class="text-md text-center font-semibold text-white">
                                                           @if(!empty($metadata['tax']))
                                                           Tax Invoice
                                                           @else
                                                           Invoice
                                                           @endif
                                                         </h1>
                                                         @endif
                                                       
                                                     </div>
                                                  
                                                     <div class="grid grid-cols-2 lg:grid-cols-4 md:grid-cols-4 mt-2  gap-2">
                                                         <div>
                                                             @foreach ($metadata['customer'] as $key=>$item)
                                                             <p class="text-sm py-1.5 text-[#859494] " >{{$item['column_name']}}</p>
                                                            @endforeach
                                                         </div>
                                                         <div>
                                                            
                                                             @foreach ($metadata['customer'] as $key=>$item)
                                                             <p class="text-sm py-1.5  text-black-900" >{{$item['value']}}</p>
                                                             @endforeach
                                                            
                                              
                                                         </div>
                                                         <div>
                                                            
                                                             @php
                                                             $last_payment=NULL;
                                                           $adjustment=0;
                                                           $discount=0;
                                                        
                                                       @endphp
                                                             @foreach ($metadata['invoice'] as $key=>$item)
                                                             @if($item['position']=="R" && $item['value']!="" && $item['function_id']!=4)
                                                             @if($item['function_id']==11)
                                                                 {{$last_payment=$item['value']}}
                                                                 @elseif($item['function_id']==12)
                                                                     {{$adjustment=$item['value']}}
                                                                    
                                                                 @elseif ($item['function_id']==14)
                                                                     {{$discount=$item['value']}}
                                                                     @else
                                                             <p class="text-sm py-py-1.5  text-[#859494]" >{{$item['column_name']}}</p>
                                                             @endif
                                                             @endif
                                                           @endforeach
                                                            
                                                         </div>
                                                     
                                                         <div>
                                                           
                                                             @foreach ($metadata['invoice'] as $key=>$item)
                                                             @if($item['position']=="R" && $item['value']!="" && $item['function_id']!=4)
                                                             @if($item['function_id']==11)
                                                             {{$last_payment=$item['value']}}
                                                             @elseif($item['function_id']==12)
                                                                 {{$adjustment=$item['value']}}
                                                                
                                                             @elseif ($item['function_id']==14)
                                                                 {{$discount=$item['value']}}
                                                                 @else
                                                                 @if(substr($item['value'],0,7)=='http://' || substr($item['value'],0,8)=='https://')
                                                                 <a target="_BlANK" href="{{$item['value']}}">{{$item['column_name']}}</a>
                                                                  @else
                                                                  @if ($item['datatype']=='money')
                                                                  <p class="text-sm py-py-1.5  text-black-900">{{$info['currency_icon']}}{{number_format($item['value'],2)}} </p>
                                                               @elseif($item['datatype']=='date')
                                                               <p class="text-sm py-py-1.5  text-black-900">{{date("d-M-Y", strtotime($item['value']))}} </p>
                                                               @else
                                                               <p class="text-sm py-py-1.5  text-black-900" >{{$item['value']}}  @if($item['datatype']=='percent') %@endif</p>
                                                              @endif     
                                                                
                                                             @endif
                                                             @endif
                                                             @endif
                                                            @endforeach
                                                            
                                              
                                                         </div>
                                                    
                                             
                                                         </div>
                                                       
                                                         @isset($metadata['vehicle_details'])  
                                                         @if(!empty($metadata['vehicle_details']))
                                                         <hr>
                                                         <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >@if(isset($metadata['travel_particular']['vehicle_det_section']['title'])){{$metadata['travel_particular']['vehicle_det_section']['title']}} @else Vehicle
                                                             details @endif</p>
                                                         <div class="grid grid-cols-2 lg:grid-cols-4 md:grid-cols-4  gap-2">
                                                             <div>
                                                                 @foreach ($metadata['vehicle_details'] as $item)
                                                                 @if($item['type']=='BDS')
                                                                 @if($item['position']=='L' && $item['value']!='')
                                                                 <p class="text-sm py-1.5 text-[#859494] " >{{$item['column_name']}}</p>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                             </div>
                                                             <div>
                                                                 @foreach ($metadata['vehicle_details'] as $item)
                                                                 @if($item['type']=='BDS')
                                                                 @if($item['position']=='L' && $item['value']!='')
                                             
                                                                 @if($item['column_datatype']=='date')
                                                                 <p class="text-sm py-1.5  text-black-900">{{date("d-M-Y", strtotime($item['value']))}} </p>
                                                                 @else
                                                                 <p class="text-sm py-1.5  text-black-900" >{{$item['value']}}  @if($item['column_datatype']=='percent') %@endif</p>
                                                           @endif
                                             
                                                                
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                                
                                                  
                                                             </div>
                                                             <div>
                                                                 @foreach ($metadata['vehicle_details'] as $item)
                                                                 @if($item['type']=='BDS')
                                                                 @if($item['position']=='R' && $item['value']!='')
                                                                 <p class="text-sm py-1.5 text-[#859494] " >{{$item['column_name']}}</p>
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                             </div>
                                                             <div>
                                                                 @foreach ($metadata['vehicle_details'] as $item)
                                                                 @if($item['type']=='BDS')
                                                                 @if($item['position']=='R' && $item['value']!='')
                                                                
                                                                 @if($item['column_datatype']=='date')
                                                                 <p class="text-smpy-1.5  text-black-900">{{date("d-M-Y", strtotime($item['value']))}} </p>
                                                                 @else
                                                                 <p class="text-sm py-1.5  text-black-900" >{{$item['value']}}  @if($item['column_datatype']=='percent') %@endif</p>
                                                           @endif
                                                                @endif
                                                                @endif
                                                                @endforeach
                                                                
                                                  
                                                             </div>
                                             
                                                             </div>
                                                             @endif
                                                             @endisset
                                                       
                                                <hr>
                                                @isset($metadata['travel_particular']['vehicle_section']['column']) 
                                                @if(!empty($metadata['particular']))     
                                                <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >{{$metadata['travel_particular']['vehicle_section']['title']}}</p>    
                                                         
                                                
                                                     <div class='overflow-x-auto w-full mt-2'>
                                                         @php
                                                             $vehicle_total=0;
                                                             $vsr_no=1;;
                                                         @endphp
                                                 <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                                     <thead >
                                                        
                                                         <tr class="text-black text-left">
                                                             @foreach ($metadata['travel_particular']['vehicle_section']['column'] as  $item=>$col)
                                                            
                                                             <th class="font-semibold text-sm text-[#859494]  px-2 py-2 text-center"> {{$col}} </th> 
                                                             @endforeach
                                                           
                                                            
                                                                </tr>
                                                             
                                                     </thead>
                                                     <tbody class="divide-y divide-gray-300">
                                                         @foreach ($metadata['particular'] as $key=>$item)
                                                       
                                                         <tr>
                                                             @foreach ($metadata['travel_particular']['vehicle_section']['column'] as $k=>$h )
                                                            
                                                            
                                             <td class="px-2 py-2 text-center">
                                                 @if ($k=='sr_no')
                                                 <p class="text-sm"> {{$vsr_no}} </p>
                                               @else
                                                     <p class="text-sm">{{$item[$k]}} @if ($k=='gst' && $item[$k]>0) % @endif</p>
                                                 @endif
                                                 @php
                                                       if($k=='total_amount')
                                                        $vehicle_total=$vehicle_total+$item[$k];
                                                   @endphp
                                                
                                             </td>
                                             
                                             @endforeach
                                             
                                             </tr>
                                             @php
                                                 $vsr_no++;
                                             @endphp
                                             @endforeach
                                                         <tr>
                                                          
                                                             <td class="px-2 py-2 text-center" colspan="{{count($metadata['travel_particular']['vehicle_section']['column'])-2}}">
                                                                 <p class="text-sm"></p>
                                                                
                                                             </td>
                                                          
                                                             <td class="px-2 py-2 text-center">  <p class="text-sm">Total ({{$info['sec_col'][$scn]}})</p>@php
                                                                 $scn=$scn+1;
                                                             @endphp</td>
                                                             <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$vehicle_total, 2, '.', '') }}</p></td>
                                                         </tr>
                                                      
                                                       
                                                     
                                                     </tbody>
                                                 </table>
                                             
                                             </div>
                                             @endif
                                             @endisset
                                             
                                             @if(in_array(1,$info['secarray']))
                                             @isset($metadata['travel_particular']['travel_section']['column'])   
                                             <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >{{$metadata['travel_particular']['travel_section']['title']}}</p>    
                                               
                                             <div class='overflow-x-auto w-full mt-2'>
                                                 @php
                                                     $tra_amt=0;
                                                     $tra_charge=0;
                                                     $tra_total=0;
                                                     $sr_no=1;
                                                 @endphp
                                             <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                                 <thead >
                                                     <tr class="text-black text-left">
                                                        
                                                         @foreach ($metadata['travel_particular']['travel_section']['column'] as  $item=>$col)
                                                         <th class="font-semibold text-sm text-[#859494]  px-2 py-2 text-center"> {{$col}} </th>
                                                        @endforeach
                                                     </tr>
                                                 </thead>
                                                 <tbody class="divide-y divide-gray-300">
                                                
                                                     @foreach ($info['ticket_detail'] as $key=>$item)
                                                     @if($item['type']=='1')
                                                                         <tr>
                                                                             @foreach ($metadata['travel_particular']['travel_section']['column'] as $k=>$h )
                                                                            
                                                                           
                                                         <td class="px-2 py-2 text-center">
                                                             @if ($k=='sr_no')
                                                                             <p class="text-sm"> {{$sr_no}} </p>
                                                                             @elseif ($k=='booking_date' || $k=='journey_date')
                                                                             <p class="text-sm">{{date('d M Y', strtotime($item[$k]))}}</p>
                                                                           @elseif ($k=='from')
                                                                           <p class="text-sm">{{$item['from_station']}}</p>
                                                                           @elseif ($k=='to')
                                                                           <p class="text-sm">{{$item['to_station']}}</p>
                                                                           @elseif ($k=='total_amount')
                                                                           <p class="text-sm">{{$item['total']}}</p>
                                                                           @elseif ($k=='type')
                                                                           <p class="text-sm">{{$item['vehicle_type']}}</p>
                                                                           @else
                                                                           <p class="text-sm">{{$item[$k]}}</p>
                                                                           @endif
                                             
                                                                    
                                                                  
                                                                     @php
                                                                     if($k=='total_amount')
                                                                      $tra_total=$tra_total+$item['total'];
                                             
                                                                      if($k=='charge')
                                                                      $tra_charge=$tra_charge+$item[$k];
                                             
                                                                      if($k=='amount')
                                                                      $tra_amt=$tra_amt+$item[$k];
                                                                 @endphp   
                                                         </td>
                                                       
                                                         @endforeach
                                                         
                                                     </tr>
                                                     @php
                                                 $sr_no++;
                                             @endphp
                                             @endif
                                                 @endforeach
                                                  
                                                     <tr>
                                                      
                                                         <td class="px-2 py-2 text-center" colspan="{{count($metadata['travel_particular']['travel_section']['column'])-4}}">
                                                             <p class="text-sm"></p>
                                                            
                                                         </td>
                                                      
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm">Total ({{$info['sec_col'][$scn]}})</p>@php
                                                             $scn=$scn+1;
                                                         @endphp</td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$tra_amt, 2, '.', '') }}</p></td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$tra_charge, 2, '.', '') }}</p></td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$tra_total, 2, '.', '') }}</p></td>
                                                     </tr>
                                                  
                                                   
                                                 
                                                 </tbody>
                                             </table>
                                             
                                             </div>
                                             @endisset
                                             @endif
                                             
                                             @if(in_array(2,$info['secarray']))
                                             @isset($metadata['travel_particular']['travel_cancel_section']['column'])   
                                             <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >{{$metadata['travel_particular']['travel_cancel_section']['title']}}</p>    
                                             
                                             <div class='overflow-x-auto w-full mt-2'>
                                                 @php
                                                 $cancel_amt=0;
                                                 $cancel_charge=0;
                                                 $cancel_total=0;
                                                 $sr_no=1;
                                             @endphp
                                             <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                                 <thead >
                                                     <tr class="text-black text-left">
                                                         @foreach ($metadata['travel_particular']['travel_cancel_section']['column'] as  $item=>$col)
                                                         <th class="font-semibold text-sm text-[#859494]  px-2 py-2 text-center"> {{$col}} </th>
                                                     @endforeach
                                                         
                                                     </tr>
                                                 </thead>
                                                 <tbody class="divide-y divide-gray-300">
                                                     @foreach ($info['ticket_detail'] as $key=>$item)
                                                     @if($item['type']=='2')
                                                     <tr>
                                                         @foreach ($metadata['travel_particular']['travel_cancel_section']['column'] as $k=>$h )
                                                        
                                                      
                                             <td class="px-2 py-2 text-center">
                                                 @if ($k=='sr_no')
                                                 <p class="text-sm"> {{$sr_no}} </p>
                                                 @elseif ($k=='booking_date' || $k=='journey_date')
                                                 <p class="text-sm">{{date('d M Y', strtotime($item[$k]))}}</p>
                                                 @elseif ($k=='from')
                                                 <p class="text-sm">{{$item['from_station']}}</p>
                                                 @elseif ($k=='to')
                                                 <p class="text-sm">{{$item['to_station']}}</p>
                                                 @elseif ($k=='type')
                                                 <p class="text-sm">{{$item['vehicle_type']}}</p>
                                                 @elseif ($k=='total_amount')
                                                 <p class="text-sm">{{$item['total']}}</p>
                                                  @else
                                                 <p class="text-sm">{{$item[$k]}}</p>
                                                 @endif
                                             
                                             
                                                 @php
                                                 if($k=='total_amount')
                                                  $cancel_total=$cancel_total+$item['total'];
                                             
                                                  if($k=='charge')
                                                  $cancel_charge=$cancel_charge+$item[$k];
                                             
                                                  if($k=='amount')
                                                  $cancel_amt=$cancel_amt+$item[$k];
                                             @endphp   
                                             
                                             </td>
                                             
                                             @endforeach
                                             
                                             </tr>
                                             @php
                                                 $sr_no++;
                                             @endphp
                                             @endif
                                             @endforeach
                                                   
                                                     <tr>
                                                      
                                                         <td class="px-2 py-2 text-center" colspan="{{count($metadata['travel_particular']['travel_cancel_section']['column'])-4}}">
                                                             <p class="text-sm"></p>
                                                            
                                                         </td>
                                                      
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm">Total ({{$info['sec_col'][$scn]}})</p>@php
                                                             $scn=$scn+1;
                                                         @endphp</td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$cancel_amt, 2, '.', '') }}</p></td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$cancel_charge, 2, '.', '') }}</p></td>
                                                         <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$cancel_total, 2, '.', '') }}</p></td>
                                                  
                                                     </tr>
                                                  
                                                   
                                                 
                                                 </tbody>
                                             </table>
                                             
                                             </div>
                                                @endisset
                                                @endif
                                                @if(in_array(3,$info['secarray']))
                                                @isset($metadata['travel_particular']['hotel_section']['column'])                                                         
                                             <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >{{$metadata['travel_particular']['hotel_section']['title']}}</p>    
                                                         
                                                 
                                             <div class='overflow-x-auto w-full mt-2'>
                                                 @php
                                                     $hotel_total=0;
                                                     $sr_no3=1;
                                                 @endphp
                                             <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                              <thead >
                                                  <tr class="text-black text-left">
                                                     @foreach ($metadata['travel_particular']['hotel_section']['column'] as  $item=>$col)
                                                      <th class="font-semibold text-sm text-[#859494]  px-2 py-2 text-center"> {{$col}} </th>
                                                    @endforeach
                                                         </tr>
                                              </thead>
                                              <tbody class="divide-y divide-gray-300">
                                                 @foreach ($info['ticket_detail'] as $key=>$item)
                                                 @if($item['type']=='3')
                                                 <tr>
                                                     @foreach ($metadata['travel_particular']['hotel_section']['column'] as $k=>$h )
                                                    
                                                   
                                             <td class="px-2 py-2 text-center">
                                              @if ($k=='sr_no')
                                                                             <p class="text-sm"> {{$sr_no3}} </p>
                                                                             @elseif ($k=='item')
                                                                             <p class="text-sm">{{$item['name']}}</p>
                                                                             @elseif ($k=='from_date')
                                                                             <p class="text-sm">{{date('d M Y', strtotime($item['booking_date']))}}</p>
                                                                             @elseif ($k=='to_date')
                                                                             <p class="text-sm">{{date('d M Y', strtotime($item['journey_date']))}}</p>
                                                                             @elseif ($k=='qty')
                                                                             <p class="text-sm">{{$item['units']}}</p>
                                                                             @elseif ($k=='total_amount')
                                                                             <p class="text-sm">{{$item['total']}}</p>
                                                                             @else
                                                                             <p class="text-sm">{{$item[$k]}}</p>
                                                                             @endif
                                             @php
                                             if($k=='total_amount')
                                              $hotel_total=$hotel_total+$item['total'];
                                             @endphp
                                             </td>
                                             
                                             
                                             @endforeach
                                             
                                             </tr>
                                             @php
                                                 $sr_no3++;
                                             @endphp
                                             @endif
                                             @endforeach
                                                  <tr>
                                                   
                                                      <td class="px-2 py-2 text-center" colspan="{{count($metadata['travel_particular']['hotel_section']['column'])-2}}">
                                                          <p class="text-sm"></p>
                                                         
                                                      </td>
                                                   
                                                      <td class="px-2 py-2 text-center">  <p class="text-sm">Total ({{$info['sec_col'][$scn]}})</p>@php
                                                         $scn=$scn+1;
                                                     @endphp</td>
                                                      <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$hotel_total, 2, '.', '') }}</p></td>
                                                  </tr>
                                               
                                                
                                              
                                              </tbody>
                                             </table>
                                             
                                             </div>
                                             @endisset
                                             @endif
                                             @if(in_array(4,$info['secarray']))
                                             @isset($metadata['travel_particular']['facility_section']['column'])    
                                             <p class="text-lg py-1.5 text-[{{$colors}}] uppercase mt-2 font-semibold" >{{$metadata['travel_particular']['facility_section']['title']}}</p>    
                                                         
                                                 
                                             <div class='overflow-x-auto w-full mt-2'>
                                                 @php
                                                     $facility_total=0;
                                                     $sr_no4=1;
                                                 @endphp
                                             <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                              <thead >
                                                  <tr class="text-black text-left">
                                                     @foreach ($metadata['travel_particular']['facility_section']['column'] as  $item=>$col)
                                                      <th class="font-semibold text-sm text-[#859494]  px-2 py-2 text-center"> {{$col}} </th>
                                                      @endforeach
                                                         </tr>
                                              </thead>
                                              <tbody class="divide-y divide-gray-300">
                                                 @foreach ($info['ticket_detail'] as $key=>$item)
                                                 @if($item['type']=='4')
                                                 <tr>
                                                     @foreach ($metadata['travel_particular']['facility_section']['column'] as $k=>$h )
                                                    
                                                    
                                             <td class="px-2 py-2 text-center">
                                                 @if ($k=='sr_no')
                                                 <p class="text-sm"> {{$sr_no4}} </p>
                                                 @elseif ($k=='item')
                                                 <p class="text-sm">{{$item['name']}}</p>
                                                 @elseif ($k=='from_date')
                                                 <p class="text-sm">{{date('d M Y', strtotime($item['booking_date']))}}</p>
                                                 @elseif ($k=='to_date')
                                                 <p class="text-sm">{{date('d M Y', strtotime($item['journey_date']))}}</p>
                                                 @elseif ($k=='qty')
                                                 <p class="text-sm">{{$item['units']}}</p>
                                                 @elseif ($k=='total_amount')
                                                 <p class="text-sm">{{$item['total']}}</p>
                                                 @else
                                                 <p class="text-sm">{{$item[$k]}}</p>
                                                 @endif
                                             
                                             @php
                                             if($k=='total_amount')
                                              $facility_total=$facility_total+$item['total'];
                                             @endphp
                                             
                                             </td>
                                             
                                             @endforeach
                                             
                                             </tr>
                                             @php
                                                 $sr_no4++;
                                             @endphp
                                             @endif
                                             @endforeach
                                             
                                                  <tr>
                                                   
                                                      <td class="px-2 py-2 text-center" colspan="{{count($metadata['travel_particular']['facility_section']['column'])-2}}">
                                                          <p class="text-sm"></p>
                                                         
                                                      </td>
                                                   
                                                      <td class="px-2 py-2 text-center">  <p class="text-sm">Total ({{$info['sec_col'][$scn]}})</p>@php
                                                         $scn=$scn+1;
                                                     @endphp</td>
                                                      <td class="px-2 py-2 text-center">  <p class="text-sm font-semibold">{{$info['currency_icon']}}{{number_format((float)$facility_total, 2, '.', '') }}</p></td>
                                                  </tr>
                                               
                                                
                                              
                                              </tbody>
                                             </table>
                                             
                                             </div>
                                             @endisset  
                                             @endif   
                                             <div class="grid grid-cols-1  md:grid-cols-2 lg:grid-cols-2 gap-4 py-2 lg:p-4 md:p-4">
                                             <div ></div>
                                             <div>
                                                 <p class="text-lg py-2 text-center text-[{{$colors}}] uppercase mt-2 font-semibold" >Final Summary </p>
                                                 <hr>
                                             <div class="flex justify-between mb-0 px-2 py-1">
                                                 <div class="text-gray-900  flex-1">SUB TOTAL <p class="text-left text-sm text-gray-900">@if($scn>1)
                                                    (
                                                    @php
                                                        $scn=0;
                                                    @endphp
                                                     @if(!empty($metadata['particular']))
                                                         {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                     @endif
                                                     @if(in_array(1,$info['secarray']))
                                                         @if ($scn>0)+ @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif
                                                         @if(in_array(2,$info['secarray']))
                                                         @if($scn>0)- @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif
                                                         @if(in_array(3,$info['secarray']))
                                                         @if($scn>0)+ @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif
                                                         @if(in_array(4,$info['secarray']))
                                                         @if( $scn>0)+ @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif
                                                         @if( $discount>0)
                                                         @if($scn>0)- @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif
                                                         @if($adjustment>0)
                                                         @if($scn>0)- @endif  {{$info['sec_col'][$scn]}} @php $scn=$scn+1; @endphp
                                                         @endif)
                                                         @endif</p></div>
                                                 <div class="text-right w-20">
                                                     <div class="text-gray-700 " x-html="netTotal">{{$info['currency_icon']}}{{$info['basic_amount']}}</div>
                                                 </div>
                                             </div>
                                             @foreach ($metadata['tax'] as $key=>$item)
                                             <div class="flex justify-between mb-0 px-2 py-2  border-b ">
                                                 <div class="text-sm text-gray-900  flex-1">{{$item['tax_name']}}</div>
                                                 <div class="text-right w-20">
                                                     <div class="text-sm text-gray-900" x-html="totalGST">{{$item['tax_amount']}}</div>
                                                 </div>
                                             </div>
                                             @endforeach
                                             @isset($info['previous_due'])
                                             @if ($info['previous_due']>0)
                                             <div class="flex justify-between mb-0 px-2 py-2  border-b ">
                                                 <div class="text-sm text-gray-900  flex-1">Previous due</div>
                                                 <div class="text-right w-20">
                                                     <div class="text-sm text-gray-900" x-html="totalGST">{{number_format($info['previous_due'],2)}}</div>
                                                 </div>
                                             </div>
                                             @endif
                                             @endisset
                                             @isset($info['coupon_details'])
                                             @if(!empty($info['coupon_details']) && $info['payment_request_status']!=1 && $info['payment_request_status']!=2)
                                                                                          
                                             <div class="flex justify-between mb-0 px-2 py-2  border-b ">
                                                 <div class="text-sm text-gray-900 font-semibold  flex-1">Coupon discount</div>
                                                 <div class="text-right w-20">
                                                     <div class="text-sm text-gray-900" x-html="totalGST">0.00</div>
                                                 </div>
                                             </div>
                                             @endif
                                             @endisset
                                             @isset($info['advance'])
                                             @if ($info['advance']>0)
                                             <div class="flex justify-between mb-0 px-2 py-2  border-b ">
                                                 <div class="text-sm text-gray-900  flex-1">Advance received </div>
                                                 <div class="text-right w-20">
                                                     <div class="text-sm text-gray-900" x-html="totalGST">{{number_format($info['advance'],2)}}</div>
                                                 </div>
                                             </div>
                                             @endif
                                             @endisset
                                             @isset($info['paid_amount'])
                                             @if ($info['paid_amount']>0)
                                             <div class="flex justify-between mb-0 px-2 py-2  border-b ">
                                                 <div class="text-sm text-gray-900  flex-1">Paid amount </div>
                                                 <div class="text-right w-20">
                                                     <div class="text-sm text-gray-900" x-html="totalGST">{{number_format($info['paid_amount'],2)}}</div>
                                                 </div>
                                             </div>
                                             @endif
                                             @endisset
                                             
                                                 <div class="flex justify-between mt-0 border px-2 py-2 bg-[{{$colors}}]">
                                                     <div class="text-sm text-white font-semibold  flex-1">Grand total Rs.</div>
                                                     <div class="text-right w-20">
                                                         <div class="text-sm text-white font-semibold" x-html="netTotal">{{$info['currency_icon']}}{{$info['absolute_cost']}}</div>
                                                   
                                                     </div>
                                                 </div>
                                                 <p class="text-sm text-gray-900">{{$info['absolute_cost_words']}}</p>
                                             </div>
                                             
                                             </div>
                                             
                                             @if(!isset($info['signature']))
                                             <hr>
                                             <p class="text-sm  py-1 px-1 mt-1 text-gray-900">Note: This is a system generated invoice. No signature required.</p> 
                                                                                        
                                             @endif
                                             @if (isset($info['signature']))
                                             <div class="row mt-2">
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