<!doctype html>
<html>

<head>
    <title> @if(Session::has('company_name'))
        {{Session::get('company_name')}}
        |
        @endif
        Choose a design color
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="/js/tailwind.js"></script>
     <link href="/assets/admin/layout/css/movingintotailwind.css{{ Helpers::fileTime('css','movingintotailwind') }}" rel="stylesheet" type="text/css" />
  
    @livewireStyles
</head>

<body class="font-rubik">
    @php
    $colors='#'.$colors;
  
    
   

    @endphp

    <div class="flex items-center justify-center min-h-screen bg-white ">
        <div class="w-full  bg-[#F7F8F8]  rounded-md" id="tab">
            {{-- <h1 class="text-2xl text-center font-regular  text-black">Choose a invoice design</h1> --}}

            <div class="flex flex-col lg:flex-row md:flex-row justify-left">
                <form action="/merchant/invoiceformat/{{$from}}/{{ $links }}" method="POST">
                    @csrf
                    <div class="w-80 h-full bg-white shadow-xl  p-4">
                        <div class="flex flex-col lg:flex-row md:flex-row ">
                            <div class="justify-left">
                            <a onclick="history.back()"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/> </svg></a>
                            </div>
                            <div class="w-40 justify-center">
                            <h1 class="text-2xl text-center font-regular  text-black uppercase">Colors</h1>
                            </div>
                        </div>
                        <p class="font-regular mt-2">Pick a color to match your document to your brand </p>
                        <a href="#" onclick="color_choose('#000000');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6 rounded-full shadow-inner bg-black hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Black </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" onclick="color_choose('#4c1d95');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6 rounded-full shadow-inner bg-violet-900  hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Violet </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" onclick="color_choose('#831843');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6 rounded-full shadow-inner bg-pink-900 hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Pink </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" onclick="color_choose('#134e4a');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6 rounded-full shadow-inner bg-teal-900 hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Teal </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" onclick="color_choose('#78350f');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6  rounded-full shadow-inner bg-amber-900 hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Amber </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" onclick="color_choose('#ea580c');">
                            <div class="flex flex-row justify-left">

                                <div class="mt-4 h-6 w-6 rounded-full shadow-inner bg-orange-600 hover:border-blue-400 border-transparent border-2"></div>
                                <div class="mt-5 px-2">
                                    <p class="font-regular text-sm  text-center">Orange </p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="hover:underline">
                            <div class="flex flex-row justify-left">

                                <div id="selected_color" class="mt-4 h-6 w-6 rounded-full shadow-inner  bg-[{{$colors}}] border-transparent border-2"></div>
                           <div class="mt-5 px-2">
                                    <p id="xxx"  class="font-regular text-sm  text-center">Choose a custom color</p>
                                    <input id="c" name="design_color" onchange="setcolor();" class=" hidden mt-4 h-6 w-6 rounded-full bg-red-900" type="color" value="{{$colors}}">
                                    <input name="design_name" type="hidden" value="{{$formatename}}">
                              
                                </div>
                            </div>
                        </a>

                        {{-- <div class="mb-2 mt-5">
                            <a href="#" class="hover:underline">
                                <p id="xxx" class="font-regular text-sm text-gray-500  text-left">Custom color </p>
                            </a>
                            <input id="c" name="design_color" onchange="setcolor();" class=" hidden mt-4 h-6 w-6 rounded-full bg-red-900" type="color" value="{{$colors}}">
                            <input name="design_name" type="hidden" value="{{$formatename}}">
                        </div>
                        <div class="flex flex-row justify-left border-t border-gray-300">

                             <div class="mt-4 pr-2">
                                <p class="font-regular text-sm  text-center">Selected Color </p>
                            </div>
                            
                            <div id="selected_color" class="mt-4 h-6 w-6 rounded-full shadow-inner  bg-[{{$colors}}] border-transparent border-2"></div>
                         
                        </div> --}}
                        {{-- <div class="border-t border-gray-300">
                            <p class="font-regular text-sm  text-left mt-2">Selected Color </p>
                        </div>
                        <div id="selected_color" class="mt-0 h-6 w-6  rounded-full shadow-inner bg-[{{$colors}}]"></div> --}}

                        <input type="submit" value="Next" class="w-24 text-white bg-[#18AEBF] hover:bg-[#047886] focus:ring-4 focus:ring-[#047886] font-regular rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-6 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
  <input type="button" onclick="history.back()" value="Back" class="w-24 text-black bg-white border border-[#A5F4FD] hover:border-[#2DD8EB]  font-regular rounded-md text-sm px-5 py-2.5 mr-2 mb-2 mt-6 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">



                    </div>
                </form>
                <div class="w-4/5">
                    <section class="flex flex-wrap justify-self lg:justify-center md:justify-center mt-4">
                        {{-- <img class="h-2/5 w-2/5 shadow-lg" src="{{ asset('images/invoice-formats/'.$formatename.'.png') }}">
                        --}}
                        {{-- @livewire('aprilformat') --}}
                        @livewire('formatdesign',['colors'=>$colors,'name'=>$formatename,'metadata'=>$metadata,'info'=>$info,'table_heders'=>$table_heders,'tax_heders'=>$tax_heders])

                    </section>
                </div>
            </div>


            <script>
                function color_choose(color) {
                    document.getElementById("selected_color").style = "background-color:" + color;
                    document.getElementById("c").value = color;
                    window.livewire.emit('changeColor', color);

                }

                document.getElementById("xxx").addEventListener("click", function() {
                    document.getElementById("c").focus();
                    //document.getElementById("c").value = "#FFCC00";
                    document.getElementById("c").click();

                });

                function setcolor() {
                    var k = document.getElementById("c").value;

                    // document.getElementById("custom_color").style="background-color:"+k;
                    document.getElementById("selected_color").style = "background-color:" + k;
                    window.livewire.emit('changeColor', k);
                }
            </script>

        </div>
    </div>




</body>
@livewireScripts

</html>