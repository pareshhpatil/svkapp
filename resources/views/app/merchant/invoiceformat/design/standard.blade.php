
<div class="w-full flex items-center justify-center min-h-screen  " style="max-width: 910px">
<div class="font-rubik  w-full lg:w-3/5 md:w-4/5  bg-white  border-2 rounded-md  border-gray p-6 mb-4" >
    <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2">
        <div>
            @if(!empty($info['image_path']))
            <section class="flex flex-wrap justify-self lg:justify-left md:justify-left ">
                <img class=" h-8 lg:h-12 md:h-10 " src="/uploads/images/logos/{{$info['image_path']}}">    
                    </section> 
                    @endif
        </div>
        <div>
           
                    <h1 class="text-xl lg:text-right md:text-right mt-0    text-gray-500">Invoice</h1>
                    @foreach ($metadata['invoice'] as $item)
                    @if($item['function_id']=='9')
                    <p class="text-sm text-gray-500  lg:text-right md:text-right mr-2" >{{$item['value']}}</p>
           @endif
           @endforeach
        </div>
                </div>
                <hr class="mt-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-4 justify-center p-0 lg:p-2 md:p-2 mb-4 lg:mb-0 md:mb-0 mt-4 lg:mt-0 md:mt-0">
                        <div> 
                            
                            <p class="text-sm font-bold" >{{$metadata['customer'][1]['column_name']}}:<span class="text-sm font-medium ml-2">{{$metadata['customer'][1]['value'] }}</span></p>
                            @foreach ($metadata['customer'] as $key=>$item)
                @if($key!=1)
                            <p class="text-sm font-bold" >{{$item['column_name']}}<span class="text-sm font-medium ml-2">{{$item['value']}}</span></p>
                            @endif
                            @endforeach

                          
                        </div>
                        <div> 
                           
                            
                            @foreach ($metadata['invoice'] as $item)
                            @if($item['function_id']=='9')
                            <p class="text-sm font-bold" >{{$item['column_name']}}:<span class="text-sm font-medium ml-2">{{$item['value']}}</span></p>
                           @endif
                           @endforeach
                           @foreach ($metadata['invoice'] as $key=>$item)
                           @if($item['value']!="" && $item['function_id']!=4)
                                       <p class="text-sm font-bold" >{{$item['column_name']}}<span class="text-sm font-medium ml-2">{{$item['value']}}</span></p>
                                       @endif
                                       @endforeach
                           
                        </div>
        
                    </div>
                   
                    
                    
                   
                        <div class='overflow-x-auto w-full mt-4'>
                            @php
                            $sr_no=1;
                        @endphp
                            <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                                <thead >
                                    <tr class="text-black text-left">
                                       
                                        @foreach ($table_heders as $item )
                                        <th class="font-semibold text-sm  px-2 py-2 text-left">{{$item}}</th>
                                      @endforeach
                                       
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">

                                     @foreach ($metadata['particular'] as $key=>$item)
                            <tr>
                                @foreach ($table_heders as $k=>$h )
                            
                                        <td class="px-2 py-2 text-left">
                                           
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
                                        <td class="px-2 py-2 text-left " colspan="{{count($table_heders)-1}}" >
                                           
                                                    <p class="text-sm font-semibold">Particular total</p>
                                                  
                                              
                                        </td>
                                       
                                        <td class="px-2 py-2 text-left "> <p class="text-sm"> {{$info['basic_amount']}}</p> </td>
                                       
                                       
                                    </tr>
                                  
                                
                                </tbody>
                            </table>
                       
                    </div>
                    <div class='overflow-x-auto w-full mt-8'>
                        <table class=' mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                            <thead >
                                
                                <tr class="text-black text-left">
                                  
                                    @foreach ($tax_heders as $item )
                                    <th class="font-semibold text-sm  px-2 py-2 text-left">{{$item}}</th>
                                   @endforeach
                                   
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">

                               @foreach ($metadata['tax'] as $key=>$item)
                            <tr>
                                @foreach ($tax_heders as $k=>$h )
                                    <td class="px-2 py-2 text-left">
                                       
                                                <p class="text-sm"> {{$item[$k]}} </p>
                                              
                                           
                                    </td>
                                   
                              @endforeach
                                </tr>
                              @endforeach
                                <tr >
                                    <td class="px-2 py-2 text-left" colspan="{{count($tax_heders)-1}}">
                                       
                                                <p class="text-sm font-semibold">Tax total</p>
                                              
                                          
                                    </td>
                                   
                                    <td class="px-2 py-2 text-left "> <p class="text-sm"> {{$info['tax_amount']}}</p> </td>
                                   
                                   
                                </tr>
                              
                            
                            </tbody>
                        </table>
                   
                </div>
                   
                   
                   
                 
                   
                <div class="grid grid-cols-1 lg:grid-cols-2 md:grid-cols-2 gap-4 mt-8  p-0 lg:p-2 md:p-2">
                    <div>
                        @isset($info['narrative'])
                        <p class="text-sm font-bold text-left" >Narrative</p>
                        <p class="text-sm font-medium text-left">{{$info['narrative']}}</p>
                        @endisset
                    </div>
                    <div>
                        <p class="text-sm font-bold lg:text-right md:text-right" >Bill value with taxes: <span class="text-sm font-medium">Rs.{{$info['absolute_cost']}}</span></p>
                        <p class="text-sm font-bold lg:text-right md:text-right" >Grand total: <span class="text-sm font-medium">{{$info['absolute_cost']}}</span></p>
                      
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

<div class="w-full lg:w-5/6 md:w-4/5 mt-1">
@include($footers)

</div>
@endif
@endif
</div>