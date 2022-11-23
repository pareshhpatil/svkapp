@extends('mpages.partial.layout')

@section('content')

<section class="text-gray-600 body-font overflow-hidden gray-bg-1">
  <div class="container px-4 lg:px-40 py-24 mx-auto">
    <div class="-my-8 divide-y-2">
      <div class="py-8 flex flex-wrap md:flex-nowrap bg-white px-4 rounded-2xl">
        <div class="md:flex-grow">
          <h2 class="text-2xl font-medium text-gray-500 title-font mb-2">Cancellation and Refund</h2>
          <!-- <p class="leading-relaxed">Glossier echo park pug, church-key sartorial biodiesel vexillologist pop-up snackwave ramps cornhole. Marfa 3 wolf moon party messenger bag selfies, poke vaporware kombucha lumbersexual pork belly polaroid hoodie portland craft beer.</p> -->
          <p class="leading-relaxed">{!!$merchant_detail->cancellation_policy!!}</p>
        </div>
      </div>
      <div class="py-8 flex flex-wrap md:flex-nowrap bg-white rounded-2xl px-4 mt-10">
        <div class="md:flex-grow">
          <h2 class="text-2xl font-medium text-gray-500 title-font mb-2">Terms and Conditions</h2>
          <!-- <p class="leading-relaxed">Glossier echo park pug, church-key sartorial biodiesel vexillologist pop-up snackwave ramps cornhole. Marfa 3 wolf moon party messenger bag selfies, poke vaporware kombucha lumbersexual pork belly polaroid hoodie portland craft beer.</p> -->
          <p class="leading-relaxed">{!!$merchant_detail->terms_condition!!}</p>
        </div>
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
            <input required minlength="2" maxlength="30" type="email" id="email" placeholder="Email" name="email" class="w-full h-12 my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
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
@endsection
@section('script')
      
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