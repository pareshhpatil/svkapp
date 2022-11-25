@extends('mpages.partial.layout')
@section('content')
<section class="text-gray-600 body-font pt-10 relative gray-bg-1">
    <div class="container lg:px-40 md:px-20 px-4 md:py-0 md:pb-12 py-12 mx-auto gray-bg-1">
        <div class="bg-white rounded-2xl pt-4">
            @if($show_msg == 1)
            <div class="bg-blue-100 border-t-4 border-blue-500 rounded-b text-blue-900 px-4 py-3 shadow-md mx-4 my-4" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                        </svg></div>
                    <div>
                        <p class="font-bold">Online payment collections not enabled</p>
                        <p class="text-sm">Merchant has not added any online payment collection method yet!</p>
                    </div>
                </div>
            </div>
            @endif
            @if(!empty($validerrors))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">
                    @foreach ($validerrors as $v)
                    <p class="media-heading">{{$v}}</p>
                    @endforeach
                </span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">

                </span>
            </div>
            @endif


            <div class="flex items-start md:pt-4 md:pb-4">
                <div class="flex-grow">
                    <h1 class="title-font text-center text-2xl font-bold text-color-1 mb-3">{{ucwords($merchant->company_name)}}</h1>
                </div>
            </div>
            <div class="mx-auto px-4">
                <form action="{{$post_url}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$merchant->display_url}}" name="url">
                    <input type="hidden" value="{{$request_post_url}}" name="request_post_url">
                    <div class="flex flex-wrap -m-2 justify-start items-center">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Name<span class="text-red-500 pl-1"> * </span></label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <input required minlength="2" maxlength="25" value="{{$detail->name??''}}" type="text" id="name" placeholder="Name" name="name" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Email ID <span class="text-red-500 pl-1"> * </span></label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <input required minlength="5" maxlength="250" value="{{$detail->email??''}}" type="email" id="email" placeholder="Email" title="Enter valid email id" name="email" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Mobile<span class="text-red-500 pl-1"> * </span></label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="w-14 bg-gray-200 absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none mr-4 rounded-md rounded-r-none border border-gray-300">
                                  <span class="text-gray-500 sm:text-sm" id="country_code_txt"> {{$detail->country_mobile_code??'+91'}} </span>
                                </div>
                                <div class="ml-14">
                                    <input required value="{{$detail->mobile??''}}" title="Enter valid mobile number" pattern="{{(empty($detail) || $detail->country=='India') ? '([0-9]{10})' : '([0-9]{7,10})'}}" maxlength="10" type="tel" id="mobile_number" placeholder="Mobile Number" name="mobile" class="w-full h-12 rounded-md rounded-l-none border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                              </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Country</label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <select id="country_drpdwn" name="country" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-1 sm:text-sm rounded-md" onchange="showStateDiv(this.value);">
                                    <option value="">Select Country</option>
                                    @foreach($country_code as $v)
                                        @if(isset($detail->country) && $detail->country!='')
                                            <option @if($detail->country==$v->config_value) selected @endif value="{{$v->config_value}}">{{$v->config_value}}</option>
                                        @else
                                            <option @if($v->config_value=="India") selected @endif value="{{$v->config_value}}">{{$v->config_value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @isset($page_setting['hidecustomercode'])
                    <input value="" type="hidden" name="customer_code">
                    @else
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Customer Code</label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <input minlength="3" maxlength="15" value="{{$detail->customer_code??''}}" type="text" id="customer_code" placeholder="Customer Code" name="customer_code" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                    </div>
                    @endisset
                    @isset($page_setting['hidepurpose'])
                    <input value="{{$detail->narrative??''}}" type="hidden" name="purpose">
                    @else
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Purpose of payment </label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <input maxlength="50" type="text" value="{{$detail->narrative??''}}" id="purpose_of_payment" placeholder="Purpose of payment" name="purpose" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                    </div>
                    @endisset
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Amount ({{$currency_icon}})<span class="text-red-500 pl-1"> * </span></label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <input required minlength="1" step="0.01" @isset($detail->amount) @if($detail->amount>0) readonly @endif @endisset value="{{$detail->amount??''}}" maxlength="20" type="number" id="amount" placeholder="Amount" name="amount" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-default text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                    </div>
                    @if($is_new_pg == false)
                    @if(isset($radio))
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Select Payment Mode<span class="text-red-500 pl-1"> * </span></label>
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative">
                                <div class="flex flex-wrap justify-start">
                                    @php $int=6; @endphp
                                    @foreach($radio as $v)
                                    <div @if($int>6) class="ml-4" @endif><label for="radio{{$int}}"><input type="radio" required id="radio{{$int}}" name="payment_mode" onchange="getgrandtotal(document.getElementById('amt').value, '{{$v['fee_id']}}');" value="{{$v['fee_id']}}"> {{$v['name']}} </label></div>
                                    @php $int++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <input type="hidden" id="captchamob1" name="recaptcha_response">
                    <div class="flex flex-wrap -m-2 justify-start items-center mt-2">
                        <div class="p-2 md:w-1/4 w-full">
                            <div class="relative">
                                <!-- <label for="name" class="leading-7 text-sm text-gray-600 justify-end flex">Amount<span class="text-red-500 pl-1"> * </span></label> -->
                                <!-- <input type="text" id="name" placeholder="Name" name="name" class="w-full h-12 my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"> -->
                            </div>
                        </div>
                        <div class="px-2 md:py-2 md:w-1/2 w-full">
                            <div class="relative ">
                                <input type="hidden" value="{{$detail->currency??'INR'}}" name="currency">

                                <!-- <input type="text" id="name" placeholder="Name" name="name" class="w-full h-12 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"> -->
                                <button type="submit" class="flex h-12 my-2 mr-auto ml-auto text-white blue-bg-1 border-0 py-2 px-8 focus:outline-none font-light rounded text-lg @if($show_msg == 1) opacity-50 cursor-not-allowed @else hover:bg-blue-600 @endif" @if($show_msg==1) disabled @else onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'" @endif>Pay Now</button>

                                <!-- <textarea id="message" placeholder="Message" name="message" class="w-full my-2 rounded-md border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 h-28 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea> -->
                            </div>
                            <!-- <div class="relative">
                    </div> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@isset($page_setting['hidecontactus'])
@else
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
                            <button data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit' class="flex ml-auto mr-auto md:mr-0 h-12 my-2 text-white blue-bg-1 border-0 py-2 px-8 focus:outline-none font-light hover:bg-blue-600 rounded text-lg" onmouseover="this.style.backgroundColor = '#18AEBF'" onmouseout="this.style.backgroundColor = '#1C729F'">Connect with us</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endisset
@endsection
@section('script')

<script>
    $(document).ready(function() {
        $('#country_drpdwn').select2();
        $('.select2-container .select2-selection--single').css('height', '40px');
        $('.select2-container--default .select2-selection--single .select2-selection__arrow').css('height', '36px');
        $('.select2-container--default .select2-selection--single .select2-selection__rendered').css('line-height', '36px');
    });
    function showStateDiv(country_name) {
        if(country_name!='') {
            if(country_name=='India') {
                $("#country_code_txt").text('+91');
                $("#mobile_number").attr('pattern',"([0-9]{10})");  //^(\+[\d]{1,5}|0)?[1-9]\d{9}$
                $("#mobile_number").attr('maxlength', "10");
            } else {
                $("#mobile_number").attr('pattern',"([0-9]{7,10})");
                $("#mobile_number").attr('maxlength',"10");

                $.ajax({
                    type: 'POST',
                    url: '/ajax/getCountryCode',
                    data: {
                        "country_name": country_name,
                    },
                    success: function (data)
                    {
                        obj = JSON.parse(data);
                        if (obj.status == 1) {
                            $("#country_code_txt").text('+' + obj.country_code);
                        } else {
                            $("#country_code_txt").text('');
                        }
                    }
                });
            }
        }
    }
</script>

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