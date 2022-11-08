@php
$header='app.master';
if($info['user_type']=='merchant')
{
$header='app.master';}
else{
$header='app.patron.invoice.invoice-master';}

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
@if($info['user_type']=='merchant')
<div class="page-content" style="text-align: -webkit-center !important;">
    @else
    <div class="w-full flex flex-col  justify-center" style="background-color: #F7F8F8;min-height: 344px;    padding: 20px 10px 20px 10px;">
        @endif

        @if($info['user_type']=='merchant')
        <div class="page-bar">

            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render('home.invoice.view','Invoice') }}
        </div>
        @endif


        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

        <div class="  w-full flex flex-col items-center justify-center ">
            <div class="w-full" style="max-width: 1400px;">
                @include('app.merchant.invoiceformat.invoice_header')
            </div>

            <div class="w-full mb-2 " style="max-width: 1400px;">
            @if ($info['payment_request_status']==11)
            <div class="alert alert-block alert-success fade in">
                <p>@if($info['invoice_type']==1) Invoice @else estimate @endif preview</p>
            </div>
     @endif
                <div class="tabbable-line" @if($info['user_type']!='merchant' ) style="padding-left: 0px;" @endif>
                        <ul class="nav nav-tabs">
                            @if($info['user_type']!='merchant')
                            <li >
                                <a href="/patron/invoice/view/{{$info['Url']}}/702">702</a>
                            </li>
                            <li >
                                <a href="/patron/invoice/view/{{$info['Url']}}/703">703</a>
                            </li>
                            <li class="active">
                                <a href="/patron/invoice/document/{{$info['Url']}}">Attached files</a>
                            </li>
                            @else
                            <li >
                                <a href="/merchant/invoice/viewg702/{{$info['Url']}}">702</a>
                            </li>
                            <li >
                                <a href="/merchant/invoice/viewg703/{{$info['Url']}}">703</a>
                            </li>
                            <li class="active">
                                <a href="/merchant/invoice/document/{{$info['Url']}}">Attached files</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div> 

@if(empty($files[0]))
<div class="row w-full   bg-white  shadow-2xl font-rubik m-2 p-10" style="max-width: 1400px;">
<h1 class="text-2xl text-center  font-normal  text-gray-400">No attachment available</h1>
</div>
@else
            <div class="row w-full   bg-white  shadow-2xl font-rubik m-2 py-10" style="max-width: 1400px;">
                <div class="tabbable-line col-md-2 col-sm-3 col-xs-3">
                 
                    @include('app.merchant.invoice.view.attachment-menu')
                    
                   
                   
                </div>
            
                <div class="col-md-10 col-sm-9 col-xs-9" >
                    <div class="tab-content"  >
                        @foreach ($files as $key=>$item)
@php
    $nm=substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10);
    $nm=strlen(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)) < 10 ?substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4):$nm.'...';
     
@endphp
                        <div class="tab-pane @if(in_array($nm, $selectedDoc)) active @else fade @endif" id="tab_{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,7)}}" >
                            <div class="grid grid-cols-3  gap-4 mb-2">
                                <div class="col-span-2">
                             <h2 class="text-lg text-left  font-normal  text-black">{{substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)}} </h2>
                                </div>
                           
                                <div >
                                  <h2 class="text-sm text-right  font-normal  text-blue-800"> <a href="download/{{basename($item)}}" target="_blank" ><i class="ml-2 popovers fa fa-download support blue" data-placement="left" data-container="body" data-trigger="hover"  data-content="Download file" data-original-title="" title=""></i></a>  <a href="download/all/{{$info['Url']}}" target="_blank"><i class="ml-2 popovers fa fa-archive support blue" style="font-size:18px" data-container="body" data-trigger="hover"   data-placement="left" data-content="Download all files" data-original-title="" title=""></i></a>
                                    </h2>
                                   
                                       </div>
                            </div>
                            <hr>
                            <p class="mt-2">
                                <iframe src="{{$item}}" class="w-full" height="800px">
                                </iframe>
                            </p>
                        </div>
                        @endforeach



                    </div>
                </div>
            </div>

@endif





        



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
    </div>



    <!-- END GROOVE WIDGET CODE -->


    @endsection
