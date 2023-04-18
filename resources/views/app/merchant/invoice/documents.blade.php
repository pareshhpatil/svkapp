@php
$header='app.master';
if($user_type=='merchant')
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
{{-- <script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script> --}}
<script src="/assets/admin/layout/scripts/transaction.js?version=16456140396" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/invoice.js?version=1649936891" type="text/javascript"></script>

<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@section('content')
@if($user_type=='merchant')
<div class="page-content" style="text-align: -webkit-center !important;">
    @else
    <div class="w-full flex flex-col  justify-center" style="background-color: #F7F8F8;min-height: 344px;    padding: 20px 10px 20px 10px;">
        @endif

        @if($user_type=='merchant')
        <div class="page-bar">

            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render('home.invoice.view','Invoice') }}
            @if ($payment_request_status==11)
            <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;width: auto;background: transparent;">Step 3 of 3</span>
            @endif
        </div>
        @endif


        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

        <div class="  w-full flex flex-col items-center justify-center ">
            <div class="w-full" style="max-width: 1400px;">
                @include('app.merchant.invoiceformat.invoice_header')
            </div>

            <div class="w-full mb-2 " style="max-width: 1400px;">
                @if ($payment_request_status==11)
                <div class="alert alert-block alert-success fade in">
                    <p>@if($invoice_type==1) Invoice @else estimate @endif preview</p>
                </div>
                @endif
                <div class="tabbable-line" @if($user_type!='merchant' ) style="padding-left: 0px;" @endif>
                    <ul class="nav nav-tabs">
                        @if($user_type!='merchant')
                        <li>
                            <a href="/patron/invoice/view/702/{{$url}}/patron">702</a>
                        </li>
                        <li>
                            <a href="/patron/invoice/view/703/{{$url}}/patron">703</a>
                        </li>
                            @if($list_all_change_orders)
                                <li>
                                    <a href="/patron/invoice/view/co-listing/{{$url}}">CO Listing</a>
                                </li>
                            @endif
                        <li class="active">
                            <a href="/invoice/document/patron/{{$url}}">Attached files</a>
                        </li>
                        @else
                        <li>
                            <a href="/merchant/invoice/view/702/{{$url}}">702</a>
                        </li>
                        <li>
                            <a href="/merchant/invoice/view/703/{{$url}}">703</a>
                        </li>
                            @if($list_all_change_orders)
                                <li>
                                    <a href="/merchant/invoice/view/co-listing/{{$url}}">CO Listing</a>
                                </li>
                            @endif
                        <li class="active">
                            <a href="/invoice/document/merchant/{{$url}}">Attached files</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            @if(empty($files[0]))
            <div class="row w-full bg-white shadow-2xl font-rubik m-2 p-10" style="max-width: 1400px;">
                <h1 class="text-2xl text-center font-normal text-gray-400">No attachment available</h1>
            </div>
            @else
            <div class="row w-full   bg-white  shadow-2xl font-rubik m-2 py-10" style="max-width: 1400px;">
                <div class="tabbable-line col-md-2 col-sm-3 col-xs-3" style="padding: 0px !important;">
                    @include('app.merchant.invoice.view.attachment-menu')
                </div>

                <div class="col-md-10 col-sm-9 col-xs-9">
                    <div class="tab-content">
                        @foreach ($files as $key=>$item)

                        <div class="tab-pane @if(in_array(str_replace(' ', '_', substr(substr(basename($item), 0, strrpos(basename($item), '.')),-10)), $selectedDoc)) active @else fade @endif" id="tab_{{str_replace(' ', '_', substr(substr(basename($item), 0, strrpos(basename($item), '.')),-10))}}">
                            <div class="grid grid-cols-3  gap-4 mb-2">
                                <div class="col-span-2">
                                    <h2 class="text-lg text-left  font-normal  text-black">{{substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4)}} </h2>
                                </div>
                                @php
                                $lastWord = explode("/", $item);
                                $folder= $lastWord[count($lastWord)-2];
                                $folder=$folder.'_'.basename($item);
                                @endphp
                                <div>
                                    <h2 class="text-sm text-right  font-normal  text-blue-800"> <a href="/invoice/document/download/{{$folder}}" target="_blank"><i class="ml-2 popovers fa fa-download support blue" data-placement="left" data-container="body" data-trigger="hover" data-content="Download file" data-original-title="" title=""></i></a> <a href="/invoice/document/download/all/{{$url}}" target="_blank"><i class="ml-2 popovers fa fa-archive support blue" style="font-size:18px" data-container="body" data-trigger="hover" data-placement="left" data-content="Download all files" data-original-title="" title=""></i></a>
                                    </h2>
                                </div>
                            </div>
                            <hr>

                            <p class="mt-2">
                                @if(strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'doc' ||
                                strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'docx' ||
                                strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'xls' ||
                                strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'xlsx' ||
                                strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'txt' ||
                                strtolower(pathinfo($item, PATHINFO_EXTENSION))=='csv')
                                <a href="/invoice/document/download/{{$folder}}" target="_blank">Download file</a>
                                @elseif(strtolower(pathinfo($item, PATHINFO_EXTENSION)) == 'pdf')
                                <iframe src="{{$item}}" class="w-full" height="800px">
                                </iframe>
                                @else
                                <img src="{{$item}}" class="img-fluid" height="800px" />
                                @endif
                            </p>
                        </div>
                        @endforeach



                    </div>
                </div>
            </div>
            @endif

            {{-- @php
                $footers='app.merchant.invoiceformat.invoice_footer';
                if($info['user_type']=='merchant')
                {
                $footers='app.merchant.invoiceformat.invoice_footer';}
                else{
                $footers='app.patron.invoice.invoice-footer';}
            @endphp --}}
            @php
            $footers='app.merchant.invoice.view.footer-v2';
            if($user_type=='merchant')
            {
            $footers='app.merchant.invoice.view.footer-v2';}
            else{
            $footers='app.patron.invoice.footer-v2';}
            @endphp

            @if($its_from!='preview')
                @if($staging==0)
                <div class="w-full mt-1" style="max-width: 1400px">
                    @include($footers)
                </div>
                @endif
            @endif
        </div>
    </div>



    <!-- END GROOVE WIDGET CODE -->


    @endsection