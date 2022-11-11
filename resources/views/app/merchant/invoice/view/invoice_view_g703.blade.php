@php
$header = 'app.master';
if ($info['user_type'] == 'merchant') {
    $header = 'app.master';
} else {
    $header = 'app.patron.invoice.invoice-master';
}

@endphp


@extends($header)

<style>
    .tabbable-line>.nav-tabs>li.active {
        border-bottom: 4px solid #3E4AA3 !important;
        position: relative;
        color: #3E4AA3 !important;
    }

    .tabbable-line>.nav-tabs>li.active>a {
        border: 0;
        color: #3E4AA3 !important;
    }

    .tabbable-line>.nav-tabs>li.open,
    .tabbable-line>.nav-tabs>li:hover {
        border-bottom: 4px solid #3E4AA3 !important;
    }
</style>


<script src="/js/tailwind.js"></script>
<link href="/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script src="/assets/admin/layout/scripts/transaction.js?version=1645614039" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/invoice.js?version=1649936891" type="text/javascript"></script>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@section('content')
    @if ($info['user_type'] == 'merchant')
        <div class="page-content" style="text-align: -webkit-center !important;">
        @else
            <div class="w-full flex flex-col  justify-center"
                style="background-color: #F7F8F8;min-height: 344px;    padding: 20px 10px 20px 10px;">
    @endif

    @if ($info['user_type'] == 'merchant')
        <div class="page-bar">

            <span class="page-title" style="float: left;">{{ $title }}</span>
            {{ Breadcrumbs::render('home.invoice.view', 'Invoice') }}

        </div>
    @endif


    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

    <div class=" w-full flex flex-col items-center justify-center  ">
        <div class="w-full" style="max-width: 1400px;">
            @include('app.merchant.invoiceformat.invoice_header')
        </div> 
        <div class="w-full mb-2 " style="max-width: 1400px;">
        @if ($info['payment_request_status']==11)
            <div class="alert alert-block alert-success fade in">
                <p>@if($info['invoice_type']==1) Invoice @else estimate @endif preview</p>
            </div>
     @endif
            <div class="tabbable-line" @if ($info['user_type'] != 'merchant') style="padding-left: 0px;" @endif>
                <ul class="nav nav-tabs">
                    @if ($info['user_type'] != 'merchant')
                        <li>
                            <a href="/patron/invoice/view/{{ $info['Url'] }}/702">702</a>
                        </li>
                        <li class="active">
                            <a href="/patron/invoice/view/{{ $info['Url'] }}/703">703</a>
                        </li>
                        @isset($metadata['plugin']['has_upload'])
                            <li>
                                <a href="/patron/invoice/document/{{ $info['Url'] }}">Attached files</a>
                            </li>
                        @endisset
                    @else
                        <li>
                            <a href="/merchant/invoice/viewg702/{{ $info['Url'] }}">702</a>
                        </li>
                        <li class="active">
                            <a href="/merchant/invoice/viewg703/{{ $info['Url'] }}">703</a>
                        </li>
                        @isset($metadata['plugin']['has_upload'])
                            <li>
                                <a href="/merchant/invoice/document/{{ $info['Url'] }}">Attached files</a>
                            </li>
                        @endisset
                    @endif
                </ul>
            </div>
        </div>
        <div class="w-full   bg-white  shadow-2xl font-rubik m-2 p-10" style="max-width: 1400px;">


            <div class="flex flex-row  gap-4">
                <div>
                    <img src="{{ asset('images/logo-703.PNG') }}" />
                    {{-- @if (isset($info['image_path']) && !empty($info['image_path']))
                    <img style="max-width: 120px" src="data:image/png;base64,{{$info['logo']}}" />
                    @else
                    <img style="max-width: 120px" src="{{ asset('images/logo-703.PNG') }}" />
                    @endif --}}
                </div>
                <div>
                    <h1 class="text-3xl text-left mt-8 font-bold  text-black">Document G703® – 1992</h1>
                </div>

            </div>
            <h1 class="text-2xl text-left mt-4 font-bold  text-black">Continuation Sheet</h1>
            <div class="w-full h-0.5 bg-gray-900 mt-1 mb-1"></div>

            <div class="grid grid-cols-3  gap-4">
                <div class="col-span-2">
                    <p class="text-xs">AIA Document G702®, Application and Certificate for Payment, or G732™,
                        Application and Certificate for
                        Payment, Construction Manager as Adviser Edition, containing Contractor’s signed certification
                        is attached.
                        Use Column I on Contracts where variable retainage for line items may apply. </p>
                </div>
                <div>
                    <table>
                        <tr>
                            <td>
                                <p class="text-xs font-bold">APPLICATION NO: </p>
                            </td>
                            <td>
                                <p class="ml-2 text-xs font-bold">
                                    {{ $info['invoice_number'] ? $info['invoice_number'] : 'NA' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-xs font-bold">APPLICATION DATE: </p>
                            </td>
                            <td>
                                <p class="ml-2 text-xs font-bold"><x-localize :date="$info['bill_date']" type="date" /></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-xs font-bold">PERIOD TO: </p>
                            </td>
                            <td>
                                <p class="ml-2 text-xs font-bold">{{ $info['cycle_name'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-xs font-bold">ARCHITECT’S PROJECT NO:</p>
                            </td>
                            <td>
                                <p class=" ml-2 text-xs font-bold">{{ $info['project_details']->project_id }}</p>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class='overflow-x-auto w-full mt-4 mb-4'>
                <table class=' mx-auto  w-full border-collapse border border-gray-500 overflow-hidden'>
                    <thead>
                        <tr class="text-black text-center">
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> A </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> B </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> C </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center">D </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> E </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> F </td>
                            <td colspan="2" class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> G
                            </td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> H</td>
                            <td class="border border-gray-500 font-regular text-xs  px-2 py-2 text-center"> I </td>
                        </tr>
                        <tr class="text-black text-center ">
                            <td class=" font-regular text-xs  border-r border-l border-gray-500  px-2 py-2 text-center">
                            </td>
                            <td class=" font-regular text-xs   border-r border-l border-gray-500  px-2 py-2 text-center">
                            </td>
                            <td class=" font-regular text-xs   border-r border-l border-gray-500  px-2 py-2 text-center">
                            </td>
                            <td colspan="2"
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                WORK COMPLETED </td>
                            <td class="font-regular text-xs   border-r border-l border-gray-500 px-2 py-2 text-center">
                            </td>
                            <td class=" font-regular text-xs   border-r border-l border-gray-500  px-2 py-2 text-center">
                            </td>
                            <td class=" font-regular text-xs   border-r border-l border-gray-500  px-2 py-2 text-center">
                            </td>
                            <td class=" font-regular text-xs   border-r border-l border-gray-500 px-2 py-2 text-center">
                            </td>

                        </tr>
                        <tr class="text-black text-center">
                            <td style="min-width:70px"
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                ITEM
                                NO. </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                DESCRIPTION
                                OF WORK </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                SCHEDULED
                                VALUE </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                FROM
                                PREVIOUS APPLICATION
                                <br />(D + E)
                            </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                THIS PERIOD
                            </td>
                            <td
                                class="border-b border-r border-l border-gray-500  font-regular text-xs  px-2 py-2 text-center">
                                MATERIALS
                                PRESENTLY
                                STORED<br />
                                (Not in D or E) </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                TOTAL
                                COMPLETED AND
                                STORED TO DATE<br />
                                (D+E+F) </td>
                            <td style="min-width:70px"
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                %(G ÷ C)
                            </td>

                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                BALANCE TO
                                FINISH<br />
                                (C – G) </td>
                            <td
                                class="border-b border-r border-l border-gray-500 font-regular text-xs  px-2 py-2 text-center">
                                RETAINAGE
                                <br />(If variable rate)
                            </td>


                        </tr>
                    </thead>
                    <tbody>
                      
                        @foreach ($info['constriuction_details'] as $key => $item)
                       @if($item['a']=='heading')
                        <tr>
                            <td colspan="10" class="border border-gray-500 px-2 py-2 text-left">

                                <p class="text-sm ">{{ $item['b'] }} </p>


                            </td>
                        </tr>
                        @elseif ($item['a']=='footer')
                        <tr>
                            <td colspan="2" class="border border-gray-500 px-2 py-2   text-center text-black">

                              <p class=" font-regular text-xs">  {{ $item['b'] }} </p>


                            </td>
                          
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $item['c'] }} </p>

                            </td>
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm"> {{ $item['d'] }}</p>

                            </td>
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $item['e'] }}</p>
                            </td>
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $item['f'] }}</p>
                            </td>
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $item['g'] }}</p>
                            </td>
                            <td class="border border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm"> {{ $item['g_per'] }}</p>
                 
                    </td>
                    <td class="border border-gray-500 px-2 py-2 text-right">
                        <p class="text-sm"> {{ $item['h'] }} </p>
                    </td>
                    <td class="border border-gray-500 px-2 py-2 text-right">
                        <p class="text-sm">{{ $item['i'] }}</p>
                    </td>

                    </tr>
                    @elseif ($item['a']=='combine')
                    <tr>
                        <td colspan="2" class=" border border-gray-500 px-2 py-2 text-left">

                            <p class="text-sm">{{ $item['b'] }} @if(!empty($item['attachment']))<a href="/{{$info['user_type']}}/invoice/document/{{ $info['Url'] }}/{{ strlen($item['b']) > 10 ? substr($item['b'],0,10)."..." : $item['b']}}/{{$item['group_name']}}/{{$item['attachment']}}"> <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover"  data-content="{{$item['files']}}" aria-hidden="true"></i></a>@endif </p>


                        </td>
                      
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm">{{ $item['c'] }} </p>

                        </td>
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm"> {{ $item['d'] }}</p>

                        </td>
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm">{{ $item['e'] }}</p>
                        </td>
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm">{{ $item['f'] }}</p>
                        </td>
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm">{{ $item['g'] }}</p>
                        </td>
                        <td class="border border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm"> {{ $item['g_per'] }}</p>
             
                </td>
                <td class="border border-gray-500 px-2 py-2 text-right">
                    <p class="text-sm"> {{ $item['h'] }} </p>
                </td>
                <td class="border border-gray-500 px-2 py-2 text-right">
                    <p class="text-sm">{{ $item['i'] }}</p>
                </td>

                </tr>
                        @else
                            <tr>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-left">

                                    <p class="text-sm">{{ $item['a'] }} </p>


                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-left">
                                    @if(isset( $item['group_name']))
                                    <p class="text-sm">{{ $item['b'] }} @if(!empty($item['attachment']))<a href="/{{$info['user_type']}}/invoice/document/{{ $info['Url'] }}/{{$item['group_name']}}/{{ strlen($item['b']) > 10 ? substr($item['b'],0,10)."..." : $item['b'] }}/{{$item['attachment']}}"> <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover"  data-content="{{$item['files']}}"  aria-hidden="true"></i></a>@endif</p>
                             @else
                             <p class="text-sm">{{ $item['b'] }} @if(!empty($item['attachment']))<a href="/{{$info['user_type']}}/invoice/document/{{ $info['Url'] }}/{{ strlen($item['b']) > 10 ? substr($item['b'],0,10)."..." : $item['b'] }}/{{$item['attachment']}}"> <i class="fa fa-paperclip popovers" data-placement="right" data-container="body" data-trigger="hover"  data-content="{{$item['files']}}" aria-hidden="true"></i></a>@endif</p>
                             
                                    @endif
                                  </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm">{{ $item['c'] }} </p>

                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm"> {{ $item['d'] }}</p>

                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm">{{ $item['e'] }}</p>
                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm">{{ $item['f'] }}</p>
                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm">{{ $item['g'] }}</p>
                                </td>
                                <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                                    <p class="text-sm"> {{ $item['g_per'] }}</p>
                     
                        </td>
                        <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm"> {{ $item['h'] }} </p>
                        </td>
                        <td class="border-r border-l border-gray-500 px-2 py-2 text-right">
                            <p class="text-sm">{{ $item['i'] }}</p>
                        </td>

                        </tr>
                        @endif
                      



                        @endforeach
                      

                      
                       

                        <tr>
                            <td style="min-width: 40px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-left">

                                <p class="text-sm"> </p>


                            </td>
                            <td style="min-width: 40px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-left">
                                <p class="text-xs"><b>GRAND TOTAL</b> </p>

                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_c'],2) }} </p>

                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_d'],2)  }}</p>
                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_e'],2)  }}
                                </p>
                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }} {{ number_format($info['total_f'],2)  }}</p>
                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_g'],2)  }}</p>
                            </td>
                            <td style="min-width: 40px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm"></p>
                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_h'],2)  }}</p>
                            </td>
                            <td style="min-width: 70px"
                                class="border-r border-t border-l border-gray-500 px-2 py-2 text-right">
                                <p class="text-sm">{{ $info['currency_icon'] }}{{ number_format($info['total_i'],2)  }}</p>
                            </td>
                        </tr>


                    </tbody>
                </table>

            </div>
            <hr>
            <div class="mt-2">

                <p class="leading-3"><span class="text-xs font-bold">AIA Document G703® – 1992. Copyright</span><span
                        class="text-xs"> © 1963, 1965, 1966, 1967, 1970, 1978, 1983 and 1992 by The American Institute
                        of Architects. All rights reserved.</span><span class="text-xs text-red-500"> The “American
                        Institute of Architects,” “AIA,” the AIA Logo, “G703,”
                        and “AIA Contract Documents” are registered trademarks and may not be used without
                        permission.</span><span class="text-xs"> To report copyright violations of AIA Contract
                        Documents, e-mail copyright@aia.org.</span></p>


            </div>









        </div>



        @php
            $footers = 'app.merchant.invoiceformat.invoice_footer';
            if ($info['user_type'] == 'merchant') {
                $footers = 'app.merchant.invoiceformat.invoice_footer';
            } else {
                $footers = 'app.patron.invoice.invoice-footer';
            }
            
        @endphp

        @if ($info['its_from'] != 'preview')
            @if ($info['staging'] == 0)
                <div class="w-full mt-1" style="max-width: 1400px">

                    @include($footers)

                </div>
            @endif
        @endif



    </div>



    <!-- END GROOVE WIDGET CODE -->


@endsection
