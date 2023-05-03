@php
    $header='app.master';
    if($info['user_type']=='merchant')
    {
    $header='app.master';}
else{
$header='app.patron.invoice.invoice-master';}

@endphp


@extends($header)




<script src="/js/tailwind.js"></script>
<link href="/assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script src="/assets/admin/layout/scripts/transaction.js?version=16456140399" type="text/javascript"></script>
<script src="/assets/admin/layout/scripts/invoice.js?version=1649936891" type="text/javascript"></script>
@isset($info['signature']['font_file'])
    <link href="{{$info['signature']['font_file']}}" rel="stylesheet">
@endisset
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@section('content')
@if($info['user_type']=='merchant')
<div class="page-content" style="text-align: -webkit-center !important;" >
 @else
 <div class="w-full flex flex-col items-center justify-center"  style="background-color: #F7F8F8;min-height: 344px;    padding: 20px 0px 20px 0px;">
    @endif   

    @if($info['user_type']=='merchant')
    <div class="page-bar">
        <br>
        {{-- <span class="page-title" style="float: left;">{{$title}}</span> --}}
        {{-- {{ Breadcrumbs::render() }} --}}
    </div>
   @endif

    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>


    
    @livewire('formatdesign',['colors'=>$colors,'name'=>$formatename,'metadata'=>$metadata,'info'=>$info,'table_heders'=>$table_heders,'tax_heders'=>$tax_heders])

  




</div>



<!-- END GROOVE WIDGET CODE -->


@endsection