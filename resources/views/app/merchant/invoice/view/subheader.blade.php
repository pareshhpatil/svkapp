@if($has_watermark)
    <div id="watermark_parent">
        <div class="watermark__inner" id="watermark_div">
            <div class="watermark__body">{{$watermark_text}}</div>
        </div>
    </div>        
@endif
<div class="flex flex-row  gap-4">
    @if($has_aia_license)
        <div>
            <img src="{{ asset('images/logo-703.PNG') }}" />
        </div>
        <div>
            <h1 class="text-3xl text-left mt-8 font-bold">Document {{$gtype}}® – 1992</h1>
        </div>
    @else
        <div>
            <h1 class="text-3xl text-left font-bold text-black">Document {{$gtype}} – 1992</h1>
        </div>
    @endif
</div>
<h1 class="text-2xl text-left mt-4 font-bold">{{$title}}</h1>
<div class="w-full h-0.5 bg-gray-900 mt-1 mb-1"></div>



