<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title> @if(Session::has('company_name'))
    {{Session::get('company_name')}}
    |
    @endif
    Choose a invoice design
</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="{{ asset('sass/app.scss') }}" rel="stylesheet">
  {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
  <link href="/assets/admin/layout/css/movingintotailwind.css{{ Helpers::fileTime('css','movingintotailwind') }}" rel="stylesheet" type="text/css" />
  
  <script src="/js/tailwind.js" ></script>
</head>
<body class="font-rubik">

    <div class="flex items-center justify-center min-h-screen bg-white ">
        <div class="w-full lg:w-5/6 md:w-4/5  bg-white  rounded-md   p-4" id="tab">
            <div class="flex flex-col lg:flex-row md:flex-row mt-4">
            <div class="ml-12">
                <a onclick="history.back()"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/> </svg></a>
                </div>
                <div class="w-5/6 justify-center">
            <h1 class=" ml-6 text-2xl text-center font-medium  text-black">Choose a invoice design</h1>
            </div></div>
            <div class="grid grid-cols-1 lg:grid-cols-3 md:grid-cols-3  mt-4">
                @foreach ($templateList as $key=>$item)
                
                <div class="items-center justify-center mt-2"> 
                <a href="@if($item['design_name']=='isp'){{ url('/merchant/invoiceformat/create/'.$links) }} @else {{ url('/merchant/invoiceformat/choose-color/'.$from.'/'.$item['design_name'].'/'.str_replace("#", "", $item['color']).'/'.$links) }} @endif">
                <section class="flex flex-wrap justify-self lg:justify-center md:justify-center">
                <img style="border: 0.5px solid #e3e3e3;" class="h-1/3 w-80 transform motion-safe:hover:-translate-y-1 motion-safe:hover:scale-110 transition ease-in-out duration-300" src="{{ asset('images/invoice-formats/'.$item['image'].'?id=765') }}">  
                </section> 
            </a> 
                <h1 class="text-1xl text-center mt-4  font-semibold text-gray-700">{{$item['title']}}</h1>
        </div>
@endforeach
     

                 </div>

           </div>
</div>


   

</body>

</html>