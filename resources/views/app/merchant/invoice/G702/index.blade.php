@php
$header='app.master';
if($user_type=='merchant'){
$header='app.master';
}else{
$header='app.patron.invoice.invoice-master';
}

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

    .tabbable-line>.nav-tabs>li.open,
    .tabbable-line>.nav-tabs>li:hover {
        border-bottom: 4px solid #3E4AA3 !important;
    }
</style>

@extends($header)


<script src="/js/tailwind.js"></script>
<link href="/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
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

            @if ($info['payment_request_status']==11)
            <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;width: auto;background: transparent;">Step 3 of 3</span>
            @endif

        </div>
        @endif


        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

        <div class="w-full flex flex-col items-center justify-center  ">

            <!-- common header file for both formats  -->
            @include('app.merchant.invoice.view.header');
            @include('app.merchant.invoice.G702.content');

            @php
            $footers='app.merchant.invoiceformat.invoice_footer';
            if($user_type=='merchant')
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