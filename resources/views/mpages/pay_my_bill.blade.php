@extends('mpages.partial.layout')
@section('content')
<section class="text-gray-600 body-font">
  <div class="container px-4 lg:px-40 pb-24 pt-4 mx-auto flex flex-wrap items-center">
    <div class="lg:w-3/6 md:w-1/2 md:pr-16 lg:pr-0 pr-0">
      @if($online_payment==0)
      <div id="online_payment_error" style="display: none;margin-top: -50px;" class="bg-blue-100 border-t-4 border-blue-500 rounded-b text-blue-900 px-4 py-3 shadow-md my-4" role="alert">
        <div class="flex">
          <div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"></path>
            </svg></div>
          <div>
            <p class="font-bold">Online payment collections not enabled</p>
            <p class="text-sm">Merchant has not added any online payment collection method yet!</p>
          </div>
        </div>
      </div>
      @endif
      @if($merchant_detail->pay_my_bill_text != null)
      <h1 class="title-font font-medium text-3xl text-gray-900">{!! $merchant_detail->pay_my_bill_text !!}</h1>
      @else
      <h1 class="title-font font-medium text-3xl text-gray-900">{!! $default_data->pay_my_bill_text !!}</h1>
      @endif
      @if($merchant_detail->pay_my_bill_paragraph != null)
      <p class="leading-relaxed mt-4">{!!str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $merchant_detail->pay_my_bill_paragraph)!!}</p>
      @else
      <p class="leading-relaxed mt-4">{!!str_replace('%COMPANY_NAME%', ucwords($merchant->company_name), $default_data->pay_my_bill_paragraph)!!}</p>
      @endif
    </div>
    <div class="lg:w-2/5 md:w-1/2 bg-gray-100 rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
      <h2 class="text-gray-900 text-lg font-medium title-font mb-5">Search for bills</h2>
      <form method="POST" action="{{url('/m/'.$merchant->display_url.'/paymybill')}}">
        @csrf
        <input type="hidden" name="url" value="{{$merchant->display_url}}">
        <input type="hidden" name="user_id" value="{{$merchant->user_id}}">
        <div class="relative mb-4">
          <label for="email" class="leading-7 text-sm text-gray-600">@if($merchant_detail->search_bill_text!='') {{$merchant_detail->search_bill_text}} @else Enter your Email ID / Mobile No. / Customer code @endif <span class="text-red-600">*</span></label>
          <input type="text" id="user_input" name="user_input" value="{{$user_input??''}}" required class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
        </div>
        <input type="hidden" id="captchamob1" name="recaptcha_response">
        @if($online_payment==1)
        <button data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit' type="submit" class="text-white blue-bg-1 w-full border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Start</button>
        @else
        <button type="button" onclick="showMessage();" class="text-white blue-bg-1 w-full border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Start</button>
        @endif
      </form>
    </div>

    @if(count($mybills) > 0)
    <div class="mt-8 w-full">
      <div class="h-full bg-gray-100">
        <table class="table-auto border border-gray-600 w-full bg-white">
          <thead>
            <tr>
              <th class="bg-white-100 border text-left px-8 py-4">Sent on</th>
              <th class="bg-white-100 border text-left px-8 py-4">Due date</th>
              <th class="bg-white-100 border text-left px-8 py-4">Company name</th>
              <th class="bg-white-100 border text-left px-8 py-4">Customer code</th>
              <th class="bg-white-100 border text-left px-8 py-4">Customer name</th>
              <th class="bg-white-100 border text-left px-8 py-4">Amount</th>
              <th class="bg-white-100 border text-left px-8 py-4">Status</th>
              <th class="bg-white-100 border text-left px-8 py-4">View</th>
            </tr>
          </thead>
          <tbody class="text-center">
            @foreach($mybills as $bill)
            <tr class="bg-white">
              <td class="border">{{ Carbon\Carbon::parse($bill->received_date)->format('Y-m-d') }}</td>
              <td class="border">{{$bill->due_date}}</td>
              <td class="border">{{$merchant->company_name}}</td>
              <td class="border">{{$bill->customer_code}}</td>
              <td class="border">{{$bill->patron_name}}</td>
              <td class="border">{{$bill->absolute_cost}}</td>
              <td class="border">
                @if($bill->status=='Submitted')
                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 uppercase last:mr-0 mr-1">{{$bill->status}}</span>
                @else
                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200 uppercase last:mr-0 mr-1">{{$bill->status}}</span>
                @endif
              </td>
              <td class="border">
                <a href="{{$bill->paylink}}" target="_blank" class="bg-blue-500 text-white active:bg-blue-600 bg-aqua font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 mt-1 ease-linear transition-all duration-150" type="button">
                  <i class="fas fa-table"></i> Pay
                </a>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <div class="mt-8 w-full">
      <div class="h-full bg-gray-100">
        <h1 class="py-3 px-8 bg-blue-200">Help information to find your bills</h1>
        <div class="p-8">
          <p class="leading-relaxed mb-6">You could search for your pending bills by entering either your email ID OR mobile number OR landline number OR merchant provided account identifier.</p>
          <ul class="ml-12">
            <li class="list-disc">Customer code : Customer code is assigned by a merchant to each consumer. This maybe either a number OR alpha-numeric ID which shoold be part of the bill presented to you by your merchant.</li>
            <li class="list-disc">Email ID : Email ID you have provided your merchant for billing</li>
            <li class="list-disc">Mobile number : Mobile number you have provided your merchant. Enter without country code (eg.9820123456)</li>
            {{-- <li class="list-disc">Landline number : Landline number you have provided your merchant. Enter without area code.</li> --}}
          </ul>
          <p class="leading-relaxed my-6">If in case you are unable to locate your bill, please get in touch with your merchant/service provider and update your details. We will make sure it reflects correctly on Swipez.</p>
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
<script>
  function showMessage()
  {
    document.getElementById('online_payment_error').style.display='block';
  }
  </script>
@endsection
@section('script')
@if(Session('info'))
<script>
  let message = "{{ Session::get('info') }}";
  toastr.info('', message, {
    postionClass: 'toast-top-right',
    closeDuration: 200,
    closeButton: true
  });
</script>
@endif
@if(Session('success'))
<script>
  let message = "{{ Session::get('success') }}";
  toastr.success('', message, {
    postionClass: 'toast-top-right',
    closeDuration: 200,
    closeButton: true
  });

  
</script>
@endif
@endsection