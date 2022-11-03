@extends('mpages.partial.layout')
    
@section('content')
      <section class="text-gray-600 body-font gray-bg-1">
        <div class="container mx-auto md:pt-10 md:px-4 lg:px-0 flex flex-col gray-bg-1">
          <div class="relative lg:mx-20">
            <div class="block h-full w-full bg-indigo-500 text-white text-5xl text-center">
              @if($default_banner == null)
              @if(strpos($merchant_detail->banner, '/'))
              <img alt="content" class="object-cover object-center h-full w-full" src="{{asset($merchant_detail->banner)}}" style="height:70vh">
              @else
              <img alt="content" class="object-cover object-center h-full w-full" src="{{asset('uploads/images/landing/'.$merchant_detail->banner)}}" style="height:70vh">
              @endif
              @else
              <img alt="content" class="object-cover object-center h-full w-full" src="{{asset($default_banner)}}" style="height:70vh">
              @endif
            </div> 
            <div class="hero-image" style="background-image:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));">
              <div data-tour="hero-text" class="hero-text absolute 2xl:top-16 2xl:left-16 2xl:pl-8 2xl:pt-8 md:top-16 md:left-16 md:pl-8 md:pt-8 top-0 left-0 justify-center 2xl:w-2/5 xl:w-1/2 opacity-75 md:3/5 sm:w-3/5 lg:w-3/5 w-4/ pb-8 pt-10 px-4 text-white bg-black">
                  @if($merchant_detail->banner_text == null)
                  <h1 class="md:text-4xl text-2xl font-semibold">{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $default_data->banner_text))))}}</h1>
                  @else
                  <h1 class="md:text-4xl text-2xl font-semibold">{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $merchant_detail->banner_text))))}}</h1>
                  @endif
                  @if($merchant_detail->banner_paragraph == null)
                  <p class="leading-relaxed text-base mt-4 mb-4">{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $default_data->banner_paragraph))))}}</p>
                  @else
                  <p class="leading-relaxed text-base mt-4 mb-4">{{str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $merchant_detail->banner_paragraph))))}}</p>
                  @endif
              </div>
            </div>
        </div>
    </div>
</section> 
<section class="text-gray-600 body-font gray-bg-1 md:pb-0 pb-8">
    <div class="container mx-auto md:-mt-20 -mt-8 flex flex-col gray-bg-1">
        <div class="text-center rounded-2xl md:mx-14 xl:mx-52 lg:mx-40 px-2 sm:px-6 bg-white relative z-10 mx-2" data-tour="get-in-touch">
          <h1 class="text-2xl tracking-tight pt-4 px-1 font-bold text-color-1">Get in touch with us</h1>
          <form action="{{route('connect.mail')}}" method="POST">
          @csrf
          <input type="hidden" value="{{$merchant->display_url}}" name="url">
          <div class="flex w-full sm:flex-row flex-col mx-auto md:px-6 px-4 pt-6 pb-4 sm:space-x-4 sm:space-y-0 space-y-4 sm:px-0 md:items-end">
            <div class="relative flex-grow w-full">
              <input required minlength="2" maxlength="30" type="text" id="full-name" name="name" placeholder="Name" class="w-full bg-opacity-50 rounded border border-gray-900 h-12 focus:border-blue-500 focus:bg-transparent focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
            <div class="relative flex-grow w-full">
              <input required minlength="2" maxlength="30" type="email" id="email" name="email" placeholder="Email" class="w-full bg-opacity-50 rounded border border-gray-900 h-12 focus:border-blue-500 focus:bg-transparent focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
          </div>
          <div class="flex w-full sm:flex-row flex-col mx-auto md:px-6 px-4 pb-6 sm:space-x-4 sm:space-y-0 space-y-4 sm:px-0 md:items-end">
            <div class="relative flex-grow w-full">
              <input required minlength="2" id="query" name="query" type="text" placeholder="Write your query here" class="w-full bg-opacity-50 rounded border border-gray-900 h-12 focus:border-blue-500 focus:bg-transparent focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
            </div>
            <button class="2xl:w-1/4 xl:w-1/3 sm:w-1/3 md:w-1/3 w-3/5 mx-auto text-white h-12 blue-bg-1 border-0 px-2 font-light focus:outline-none hover:bg-blue-2 rounded text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Connect with us</button>
          </div>
          <input type="hidden" id="captchamob1" name="recaptcha_response">
          </form>
        </div>
    </div>
</section>
<div class="h-16 gray-bg-1"></div>
<section class="text-gray-600 body-font overflow-hidden">
  <div class="container pt-8 mx-auto text-center">
    <a href="{{url('/m/'.$merchant->display_url.'/payment-link')}}"><button data-tour="pay-now" class="text-white h-12 blue-bg-1 border-0 px-10 font-light focus:outline-none hover:bg-blue-2 rounded-l-full rounded-r-full text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Pay Now</button></a>
                
  </div>
</section>
<section class="text-gray-600 body-font overflow-hidden">
  <div class="container py-16 mx-auto">
    <div class="md:relative lg:w-3/4 mx-auto flex flex-wrap" data-tour="why-work-with-us">
      <!-- <img alt="ecommerce" class="sm:w-1/2 hidden lg:block my-10 object-cover object-center rounded-l-2xl" style="height:50vh" src="{{asset('img/river.jpg')}}"> -->
      <img alt="ecommerce" class="sm:w-1/2 hidden lg:block my-10 object-cover object-center rounded-l-2xl" style="height:50vh" src="{{asset($default_random_work)}}">
      <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0 mx-4 lg:mx-0 bg-gray-100 pr-10 rounded-3xl py-10 pl-10 lg:py-0 lg:pl-0 lg:pt-10">
        <h1 class="text-gray-900 text-4xl title-font font-medium mb-1 text-center">Why work with us?</h1>
        @if($merchant_detail->overview != null)
        <p class="leading-relaxed text-lg mt-10">{!!str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $merchant_detail->overview))))!!}</p>
        @else
        <p class="leading-relaxed text-lg mt-10">{!!str_replace('%INDUSTRY_NAME%', $industry_name, str_replace('%NO_OF_CUSTOMER%', $merchant->customer_count, str_replace('%NO_OF_EMPLOYEE%', $merchant->employee_count,str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $default_data->overview))))!!}</p>
        @endif
      </div>
    </div>
  </div>
</section>

      <section class="text-gray-600 body-font relative gray-bg-2">
        <div class="container lg:px-40 md:px-20 px-4 md:py-0 md:pb-12 py-12 mx-auto gray-bg-2">
          <div class="flex items-start md:pt-16 md:pb-4">
            <div class="flex-grow">
              <h1 class="title-font text-3xl font-bold text-color-1 mb-3">Get in touch with us</h1>
              <p class="leading-relaxed mb-5 text-color-2">Send us your query and we will get back to you with a response at the earliest.</p>
            </div>
          </div>
          <form action="{{route('connect.mail')}}" method="POST">
          @csrf
          <input type="hidden" value="{{$merchant->display_url}}" name="url">
          <div class="mx-auto">
            <div class="flex flex-wrap -m-2">
              <div class="p-2 md:w-1/2 w-full">
                <div class="relative">
                  <input required minlength="2" maxlength="20" type="text" id="name" placeholder="Name" name="name" class="w-full h-12 my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                <div class="relative">
                  <input required minlength="5" maxlength="30" type="email" id="email" placeholder="Email" name="email" class="w-full h-12 my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
              </div>
              <div class="px-2 md:py-2 md:w-1/2 w-full">
                <div class="relative">
                  <textarea required minlength="2" id="message" placeholder="Write your query here" name="query" class="w-full my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default h-28 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>
                <input type="hidden" id="captchamob" name="recaptcha_response">
                <div class="relative">
                  <button class="flex ml-auto mr-auto md:mr-0 h-12 my-2 text-white blue-bg-1 border-0 py-2 px-8 focus:outline-none font-light hover:bg-blue-600 rounded text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Connect with us</button>
                </div>
              </div>
            </div>
          </div>
          </form>
        </div>
      </section>

      @if($isLogin==1 && $showTour==0)
        <div class="modal z-10 inset-0 opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
            <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
              <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Take a guided tour
                    </h3>
                  <div class="modal-close cursor-pointer z-50">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                      <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                  </div>
                </div>
                <div class="mt-2 mb-10">
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Lets take a guided tour to view your company page and learn how to edit your company page</p>
                </div>
                <div class="flex justify-end pt-2">
                    <button class="modal-close mt-3 w-full inline-flex justify-center rounded-md border-gray-300 px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Not now</button>
                    <button class="bg-aqua w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm ring-blue-default" onclick="toggleModal();HelpHero.startTour('Z09jPso15f', { skipIfAlreadySeen: false });">Start tour</button>
                </div>
              </div>
            </div>
        </div>
      @endif


     @endsection 

      @section('script')
      
      <!--Load Help hero flow & script only if merchant is logged in -->
      
        @if($isLogin==1 && $showTour==0)
            <script src="//app.helphero.co/embed/cjcHsHLBZdr"></script>
            <script>
                HelpHero.identify("{{$merchant->merchant_id}}", {
                    role: "Merchant"
                });
                HelpHero.cancelTour();
                toggleModal();
                const overlay = document.querySelector('.modal-overlay')
                overlay.addEventListener('click', toggleModal)
                
                var closemodal = document.querySelectorAll('.modal-close')
                for (var i = 0; i < closemodal.length; i++) {
                closemodal[i].addEventListener('click', toggleModal)
                }

                function toggleModal () {
                    const body = document.querySelector('body')
                    const modal = document.querySelector('.modal')
                    modal.classList.toggle('opacity-0')
                    modal.classList.toggle('pointer-events-none')
                    body.classList.toggle('modal-active')
                }
            </script>
        @endif
        @if(Session('success'))
        <script>
            let message = "{{ Session::get('success') }}";
            toastr.success('', message, {
              postionClass:'toast-top-right',
              closeDuration: 200,
              closeButton: true
            } );
        </script>
        @endif
      @endsection
      
</body>
</html>