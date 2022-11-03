@extends('mpages.partial.layout')

@section('content')
<section class="text-gray-600 body-font overflow-hidden gray-bg-1">
  <div class="container px-4 lg:px-40 py-20 mx-auto">
    <div class="-my-8 divide-y-2 divide-gray-100">
      <div class="py-8 flex flex-wrap md:flex-nowrap">
        <div class="md:flex-grow">
          <h2 class="text-2xl text-center font-medium text-gray-500 title-font">Contact Us</h2>
          <div class="px-10">
              @if($merchant_detail->email_id != null)
              <div class="mt-4 bg-white p-4 rounded-2xl">
                  <h1 class="text-2xl text-black title-font mb-2">Email ID</h1>
                  <p class="leading-relaxed mb-5 text-color-2"><i class="fas fa-envelope text-gray-600"></i> - {{$merchant_detail->email_id}}</p>
              </div>
              @endif
              @if($merchant_detail->contact_no !=null)
              <div class="mt-4 bg-white p-4 rounded-2xl">
                  <h1 class="text-2xl text-black title-font mb-2">Contact No</h1>
                  <p class="leading-relaxed mb-5 text-color-2"><i class="fas fa-phone text-gray-600"></i> - {{$merchant_detail->contact_no}}</p>
              </div>
              @endif
              @if($merchant_detail->office_location != null)
              <div class="mt-4 bg-white p-4 rounded-2xl">
                  <h1 class="text-2xl text-black title-font mb-2">Office Location</h1>
                  <p class="leading-relaxed mb-5 text-color-2" style="white-space: pre-wrap"><i class="fas fa-building text-gray-600"></i> - {{$merchant_detail->office_location}}</p>
              </div>
              <div class="container-fluid mt-4 hidden">
                    <div class="row" style="height: 300px;padding: 0px 0px; max-width:100%;">
                        <script type="text/javascript"
                            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp9CLmD70KwNZyfxs9ytxsJRQ_mrVN4Mo&sensor=false&callback=initMap">
                        </script>
                        <div style='overflow:hidden;height:300px;width:100%;'>
                            <div id='gmap_canvas' style='height:300px;width:100%;color:black;'></div>
                            <style>
                                #gmap_canvas img {
                                    max-width: none !important;
                                    background: none !important
                                }
                            </style>
                        </div>
                        <script type='text/javascript'>

                            function init_map() {
                                var myOptions = {zoom: 15, center: new google.maps.LatLng(18.5563514, 73.8943515), mapTypeId: google.maps.MapTypeId.ROADMAP};
                                map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                                marker = new google.maps.Marker({map: map, position: new google.maps.LatLng(18.5563514, 73.8943515),
                                  // icon: '{{ config('app.APP_URL') }}favicon.ico'
                                  // @if($logo == null)
                                  //   @if(strpos($merchant_detail->logo,'/'))
                                  //   icon: '{{ asset($merchant_detail->logo) }}'
                                  //   @else
                                  //   icon: '{{ asset("uploads/images/landing/".$merchant_detail->logo) }}'
                                  //   @endif
                                  // @else
                                  //   icon: '{{asset($logo)}}'
                                  // @endif

                                });
                                infowindow = new google.maps.InfoWindow({content: '<strong>{{ucwords($merchant->company_name)}}</strong><br>{{$merchant_detail->office_location}}<br>'});
                                google.maps.event.addListener(marker, 'click', function() {
                                    infowindow.open(map, marker);
                                });
                                infowindow.open(map, marker);
                            }
                            google.maps.event.addDomListener(window, 'load', init_map);
                        </script>
                    </div>
                </div>
                @endif

          </div>
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