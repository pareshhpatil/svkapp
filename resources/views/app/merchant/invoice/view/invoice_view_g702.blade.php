@php
    $header='app.master';
    if($info['user_type']=='merchant')
    {
    $header='app.master';}
else{
$header='app.patron.invoice.invoice-master';}

@endphp

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

.tabbable-line>.nav-tabs>li.open, .tabbable-line>.nav-tabs>li:hover {
    border-bottom: 4px solid #3E4AA3 !important;
}

.watermark {
    position: relative;
    overflow: hidden;
    }

    .watermark__inner {
        align-items: center;
        display: flex;
        justify-content: center;
        left: 0px;
        position: absolute;
        top: 22px;
        height: 70%;
        width: 100%;
    }

    .watermark__body {
        color: rgba(0, 0, 0, 0.2);
        font-size: 14vh;
        font-weight: bold;
        text-transform: uppercase;
        transform: rotate(-45deg);
        user-select: none;
    }
</style>

@extends($header)




<script src="/js/tailwind.js"></script>
<link href="/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script src="/assets/admin/layout/scripts/transaction.js?version=16456140396" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/invoice.js?version=1649936891" type="text/javascript"></script>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@section('content')
@if($info['user_type']=='merchant')
<div class="page-content" style="text-align: -webkit-center !important;" >
  @else
 <div class="w-full flex flex-col  justify-center"  style="background-color: #F7F8F8;min-height: 344px;    padding: 20px 10px 20px 10px;">
    @endif   

    @if($info['user_type']=='merchant')
    <div class="page-bar">
      
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.invoice.view','Invoice') }}

        @if ($info['payment_request_status']==11)
            <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;width: auto;background: transparent;">Step 3 of 3</span>
            @endif
      
    </div>
   @endif


    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    
 
<div class="w-full flex flex-col items-center justify-center  " >
    <div class="w-full mb-2" style="max-width: 1400px;">
    @if ($info['payment_request_status']==11)
            <div class="alert alert-block alert-success fade in">
                <p>@if($info['invoice_type']==1) Invoice @else estimate @endif preview</p>
            </div>
     @endif
    <div class="tabbable-line" @if($info['user_type']!='merchant' ) style="padding-left: 0px;" @endif>
        <ul class="nav nav-tabs">
            @if($info['user_type']!='merchant')
            <li class="active">
                <a href="/patron/invoice/view/{{$info['Url']}}/702">702</a>
            </li>
            <li >
                <a href="/patron/invoice/view/{{$info['Url']}}/703">703</a>
            </li>
           
            <li >
                <a href="/patron/invoice/document/{{$info['Url']}}">Attached files</a>
            </li>
          
            @else
            <li class="active">
                <a href="/merchant/invoice/viewg702/{{$info['Url']}}">702</a>
            </li>
            <li >
                <a href="/merchant/invoice/viewg703/{{$info['Url']}}">703</a>
            </li>
          
            <li >
                <a href="/merchant/invoice/document/{{$info['Url']}}">Attached files</a>
            </li>
          
            @endif
        </ul>
    </div>
    </div>
    <div class="w-full bg-white  shadow-2xl font-rubik m-2 p-10 watermark"
       style="max-width: 1400px; color:#394242;">
            @if($has_watermark)
                <div class="watermark__inner">
                    <div class="watermark__body">{{$watermark_text}}</div>
                </div>
            @endif
            <div class="flex flex-row  gap-4">
                @if($has_aia_license)
                    <div>
                        <img src="{{ asset('images/logo-703.PNG') }}" />
                    </div>
                    <div>
                        <h1 class="text-3xl text-left mt-8 font-bold">Document G702® – 1992</h1>
                    </div>
                @else
                    <div>
                        <h1 class="text-3xl text-left font-bold">Document G702 – 1992</h1>
                    </div>
                @endif

            </div>
            <h1 class="text-2xl text-left mt-4 font-bold">Application and Certificate for Payment </h1>
            <div class="w-full h-0.5 bg-gray-900 mb-1 mt-1"></div>

            <div>
                <table width="100%" >
                    <tr>
                        <td width="25%">
                            <p class="text-xs font-bold">TO OWNER: </p>
                           
                        </td>
                        <td width="25%">
                            <p class="text-xs font-bold">PROJECT: </p>
                        </td>
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold">APPLICATION NO: {{$info['invoice_number']?$info['invoice_number']:'NA'}}</p>
                        </td>
                        <td width="25%" class="text-right"> <p class="text-xs font-bold">Distribution to:</p></td>
                    </tr>
                    <tr>
                        <td width="25%"   rowspan="2" style="vertical-align: baseline;">
                            <p class="text-xs  mt-1">{{$metadata['customer'][1]['value'] }}</p>
                            <span class="text-xs">{{$info['project_details']->owner_address}}</span>
                        </td>
                        <td width="25%" rowspan="2" style="vertical-align: baseline;">
                            <p class="text-xs">{{$info['project_details']->project_name}} </p>
                            <span class="text-xs">{{$info['project_details']->project_address}}</span>
                        </td>
                      
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold">PERIOD TO: {{ $info['cycle_name'] }}</p>
                        </td>
                        <td width="25%" class="text-right">
                        <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">OWNER</label> <input class="" type="checkbox" value=""></p>
   
                        </td>
                    </tr>
                    <tr>
                        
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold">CONTRACT FOR:</p>
                        </td>
                        <td width="25%" class="text-right"> 
                            <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">ARCHITECT</label> <input class="" type="checkbox" value=""></p>
   
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <p class="text-xs font-bold ">FROM CONTRACTOR: </p>
                        </td>
                        <td width="25%">
                            <p class="text-xs font-bold">VIA ARCHITECT:</p>
                        </td>
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold">CONTRACT DATE: <x-localize :date="$info['project_details']->contract_date" type="date" /></p>
                        </td>
                        <td width="25%" class="text-right"> 
                            <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">CONTRACTOR</label> <input class="" type="checkbox" value=""></p>
   
                        </td>    </tr>
                    <tr>
                        <td width="25%" rowspan="2" style="vertical-align: baseline;">
                            <p class="text-xs">{{$metadata['header'][0]['value'] }}</p>
                            <span class="text-xs">{{$info['project_details']->contractor_address}}</span>
                         
                        </td>
                        <td width="25%" rowspan="2" style="vertical-align: baseline;">
                            <span class="text-xs">{{$info['project_details']->architect_address}}</span>
                        </td>
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold">PROJECT NOS: {{$info['project_details']->project_code}}</p>
                        </td>
                        <td width="25%" class="text-right"> 
                            <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">FIELD</label> <input class="" type="checkbox" value=""></p>
   
                        </td>    </tr>
                    <tr>
                       
                        <td width="25%" class="text-left">
                            <p class="text-xs font-bold"></p>
                        </td>
                        <td width="25%" class="text-right"> 
                            <p style="display:inline-flex"><label class="text-xs mr-2 mt-1">OTHER</label> <input class="" type="checkbox" value=""></p>
   
                        </td>  </tr>
                  
                </table>
            </div>
           
            <div class="w-full h-0.5 bg-gray-900 mt-2 lg:mt-0 md:mt-0"></div>

            <div class="grid grid-cols-2 gap-2 mt-1">
                <div>
                    <h4 class="font-bold">CONTRACTOR’S APPLICATION FOR PAYMENT</h4>
                    @if($has_aia_license)
                        <p class="text-xs">Application is made for payment, as shown below, in connection with the Contract.
                            AIA Document G703®, Continuation Sheet, is attached.</p>
                    @else
                        <p class="text-xs">Application is made for payment, as shown below, in connection with the Contract.
                            Document G703, Continuation Sheet, is attached.</p>
                    @endif

                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">1. ORIGINAL CONTRACT SUM  </p>
                        <p class="font-bold text-xs mt-1">2. NET CHANGE BY CHANGE ORDERS  </p>
                        <p class="font-bold text-xs mt-1">3. CONTRACT SUM TO DATE <span class="font-light italic">(Line 1 ± 2)</span>  </p>
                        <p class="font-bold text-xs mt-1">4. TOTAL COMPLETED & STORED TO DATE<span class="font-light italic "> (Column G on G703)</span>  </p>
                         @php $contract_sum_to_date = $info['total_original_contract']+$info['last_month_co_amount']+$info['this_month_co_amount'] @endphp
                    </div>
                            <div class="text-right">
                               <p class="font-bold text-xs border-b   border-gray-600 mt-1">  {{$info['currency_icon']}}@if($info['total_original_contract'] < 0)({{str_replace('-','',number_format($info['total_original_contract'],2))}})@else{{number_format($info['total_original_contract'],2)}}@endif</p>
                               <p class="font-bold text-xs border-b  border-gray-600 mt-1">  {{$info['currency_icon']}}@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif</p>
                               <p class="font-bold text-xs border-b   border-gray-600 mt-1">  {{$info['currency_icon']}}@if( $contract_sum_to_date < 0)({{str_replace('-','',number_format($contract_sum_to_date,2))}}) @else{{number_format(($contract_sum_to_date),2)}}@endif</p>
                               <p class="font-bold text-xs border-b   border-gray-600 mt-0.5">  {{$info['currency_icon']}}@if($info['total_g'] < 0)({{str_replace('-','',number_format($info['total_g'],2))}}) @else{{number_format($info['total_g'],2)}}@endif</p> 
                              
                             
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">5. RETAINAGE: </p>
                        @php
                            $cper=0;
                            if(($info['total_d']+$info['total_e']) <= 0){
                            $cper=0;}
                            else{
                            $cper=number_format((($info['total_i']/($info['total_d']+$info['total_e']))*100),2);}

                            $a5=0;
                            $single_per=($info['total_d']+$info['total_e'])/100;
                            $a5=$single_per*$cper;

                           


                        @endphp
                        <p class="font-bold text-xs mt-1">a. <span class="font-light border-b border-gray-600">{{number_format($info['percent_rcw'],2)}}</span><span class="font-light"> % of Completed Work <span class="italic ">(Columns D + E on G703)</span></span>  </p>
                        <p class="font-bold text-xs mt-1">b. <span class="font-light border-b border-gray-600"> {{number_format($info['percent_rasm'],2)}} </span><span class="font-light"> % of Stored Material <span class="italic ">(Column F on G703)</span></span>  </p>
                        <p class="font-light text-xs mt-2">Total Retainage <span class="italic ">(Lines 5a + 5b, or Total in Column I of G703)</span> </p>
                    
                    </div>
                            <div class="text-right">
                                <p class="font-bold text-xs mt-5"></p>
                                <p class="font-bold text-xs border-b   border-gray-600 mt-2">  {{$info['currency_icon']}}@if(number_format($info['total_retainage_amount'],2) < 0)({{str_replace('-','',number_format($info['total_retainage_amount'],2))}}) @else{{number_format($info['total_retainage_amount'],2)}}@endif</p>
                               <p class="font-bold text-xs border-b   border-gray-600 mt-1">  {{$info['currency_icon']}}@if(number_format($info['total_rasm'],2) < 0)({{str_replace('-','',number_format($info['total_rasm'],2))}})@else{{number_format($info['total_rasm'],2)}}@endif</p>

                                @php $total_retainage = $info['total_retainage']; if($total_retainage == 0) $total_retainage = $info['total_i']; @endphp
                               <p class="font-bold text-xs border-b   border-gray-600 mt-2">  {{$info['currency_icon']}}@if(number_format($total_retainage,2) < 0)({{str_replace('-','',number_format($total_retainage,2))}}) @else{{number_format(($total_retainage),2)}}@endif</p>
                              
                             
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">6. TOTAL EARNED LESS RETAINAGE </p>
                        <p class="text-light ml-4 text-xs italic">(Line 4 minus Line 5 Total)</p>
                       
                    </div>
                            <div class="text-right">
                              @php $total_earned_less_retain = $info['total_g']-($total_retainage)  @endphp
                                <p class="font-bold text-xs border-b   border-gray-600 mt-1">  {{$info['currency_icon']}}@if( $total_earned_less_retain < 0)({{str_replace('-','',number_format( $total_earned_less_retain , 2))}}) @else{{number_format( $total_earned_less_retain ,2)}}@endif</p>
                              
                             
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">7. LESS PREVIOUS CERTIFICATES FOR PAYMENT </p>
                        <p class="text-light ml-4 text-xs italic">(Line 6 from prior Certificate)</p>
                       
                    </div>
                            <div class="text-right">
                              
{{--                                <p class="font-bold text-xs border-b   border-gray-600 mt-1"> {{$info['currency_icon']}}@if($info['total_d'] < 0)({{str_replace('-','',number_format($info['total_d'],2))}}) @else{{number_format($info['total_d'],2)}}@endif</p>--}}
                              <p class="font-bold text-xs border-b border-gray-600 mt-1">{{$info['currency_icon']}}@if($info['less_previous_certificates_for_payment'] < 0)({{str_replace('-','',number_format($info['less_previous_certificates_for_payment'],2))}}) @else{{ number_format($info['less_previous_certificates_for_payment'],2) }}@endif</p>
                             
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">8. CURRENT PAYMENT DUE </p>
                     
                       
                    </div>
                            <div class="text-right">
                              
                                <p class="font-bold text-xs border   border-gray-600 mt-0 py-1">  {{$info['currency_icon']}}@if($info['grand_total'] < 0) 0.00 @else{{number_format($info['grand_total'],2)}}@endif</p>
                              
                             
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-1">
                            <div class="col-span-2">
                        <p class="font-bold text-xs mt-1">9. BALANCE TO FINISH, INCLUDING RETAINAGE </p>
                        <p class="text-light ml-4 text-xs italic">(Line 3 minus Line 6)</p>
                       
                    </div>
                            <div class="text-right">
                              @php $balance_to_finish = $contract_sum_to_date - $total_earned_less_retain; @endphp
                                <p class="font-bold text-xs border-b   border-gray-600 mt-4">  {{$info['currency_icon']}}@if($balance_to_finish < 0) ({{str_replace('-','',number_format($balance_to_finish,2))}}) @else{{number_format($balance_to_finish,2)}}@endif</p>
                              
                             
                            </div>
                        </div>
                        <table class="mt-2 w-full border-collapse border border-gray-500 overflow-hidden">
                            <tr>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs">CHANGE ORDER SUMMARY </p></td>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs">ADDITIONS </p></td>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs">DEDUCTIONS </p></td>
                            </tr>
                            <tr>
                                <td class="border-collapse border-r border-gray-500 py-1 px-1"><p class="text-xs">Total changes approved in previous months by Owner </p></td>
                                <td class="border-collapse border-r border-gray-500 py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}@if($info['last_month_co_amount_positive']>=0){{number_format($info['last_month_co_amount_positive'],2)}}@else{{0}} @endif </p></td>
                                <td class="py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}@if($info['last_month_co_amount_negative']<0)({{str_replace('-','',number_format($info['last_month_co_amount_negative'],2))}})@else{{0}} @endif </p> </td>
                            </tr>
                            <tr>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs">Total approved this month </p></td>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}@if($info['this_month_co_amount_positive']>=0){{number_format($info['this_month_co_amount_positive'],2)}}@else{{0}} @endif </p></td>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}@if($info['this_month_co_amount_negative']<0)({{str_replace('-','',number_format($info['this_month_co_amount_negative'],2))}})@else{{0}} @endif </p></td>
                            </tr>
                            <tr>
                                <td class="text-right border-collapse border-r border-gray-500 py-1 px-1"><p class="text-xs">TOTAL</p> </td>
                                @php
                                    $tt= $info['total_co_amount_positive'];
                                    $tt1= $info['total_co_amount_negative'];
                              
                                @endphp
                                <td class="border-collapse border-r border-gray-500 py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}{{number_format($tt,2)}} </p></td>
                                <td class="py-1 px-1"><p class="text-xs text-right">{{$info['currency_icon']}}@if(number_format($tt1,2)<0)({{str_replace('-','',number_format($tt1,2))}}) @else{{number_format($tt1,2)}} @endif </p></td>
                            </tr>
                            <tr>
                                <td class="border-collapse border border-gray-500 py-1 px-1"><p class="text-xs">NET CHANGES by Change Order</p> </td>
                                <td colspan="2" class=" border-collapse border border-gray-500 py-1 px-1" ><p class="text-xs text-right">{{$info['currency_icon']}}@if(($info['last_month_co_amount']+$info['this_month_co_amount'])<0)({{str_replace('-','',number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2))}})@else{{number_format($info['last_month_co_amount']+$info['this_month_co_amount'],2)}}@endif</p> </td>
                                
                            </tr>
                        </table>

                </div>
                <div> 
                    <p class="text-xs">The undersigned Contractor certifies that to the best of the Contractor’s knowledge, information
and belief the Work covered by this Application for Payment has been completed in accordance
with the Contract Documents, that all amounts have been paid by the Contractor for Work for
which previous Certificates for Payment were issued and payments received from the Owner, and
that current payment shown herein is now due.</p>

<p class="font-bold text-sm mt-1">CONTRACTOR:</p>
<div class="grid grid-cols-3 gap-2 mt-1">
    
</div>
<p class="text-xs mt-1">State of: </p>
<p class="text-xs mt-2">County of:</p>
<p class="text-xs mt-2">Subscribed and sworn to before me this        <span class="ml-8"> day of</span></p>
<p class="text-xs mt-2">Notary Public:</p>
<p class="text-xs mt-1">My commission expires:</p>
<div class="w-full h-0.5 bg-gray-900 mt-2 "></div>
<h4 class="font-bold mt-1">ARCHITECT’S CERTIFICATE FOR PAYMENT</h4> 
<p class="text-xs">In accordance with the Contract Documents, based on on-site observations and the data comprising this application, the Architect certifies to the Owner that to the best of the Architect’s knowledge,
    information and belief the Work has progressed as indicated, the quality of the Work is in
    accordance with the Contract Documents, and the Contractor is entitled to payment of the
    AMOUNT CERTIFIED. </p>

    <div class="grid grid-cols-3 gap-2 mt-1">
        <div class="col-span-2">
    <p class="font-bold text-xs mt-1">AMOUNT CERTIFIED</p>
     </div>
        <div>
          
            <p class="font-bold text-xs border-b border-gray-600 mt-1 text-right">{{$info['currency_icon']}}@if($info['grand_total'] < 0)0.00  @else{{number_format($info['grand_total'],2)}} @endif</p>
          
         
        </div>
    </div>

    <p class="text-xs font-light mt-1 italic">(Attach explanation if amount certified differs from the amount applied. Initial all figures on this
        Application and on the Continuation Sheet that are changed to conform with the amount certified.)</p>
        <br>
        <p class="text-xs mt-1">This Certificate is not negotiable. The AMOUNT CERTIFIED is payable only to the Contractor
            named herein. Issuance, payment and acceptance of payment are without prejudice to any rights of
            the Owner or Contractor under this Contract.</p>
                </div>

            </div>


            <div class="w-full h-0.5 bg-gray-900 mt-2 "></div>
            <div class="mt-2">
                @if($has_aia_license)
                    <p class="leading-3"><span class="text-xs"><b>AIA Document G702® – 1992. Copyright</b> © 1953, 1963, 1965, 1971, 1978, 1983 and 1992 by The American Institute of Architects. All rights reserved.</span><span class="text-xs text-red-500"> The “American Institute of Architects,” “AIA,” the AIA Logo, “G702,” and
                    “AIA Contract Documents” are registered trademarks and may not be used without permission.</span><span class="text-xs"> To report copyright violations of AIA Contract Documents, e-mail copyright@aia.org.</span></p>
                @endif
                       


            </div>









        </div>
   
    
   


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
<div class="w-full mt-1" style="max-width: 1400px">

    @include($footers)
    
    </div> 
@endif

@endif

</div>



<!-- END GROOVE WIDGET CODE -->


@endsection