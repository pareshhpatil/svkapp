@extends('getting-started.layout')
@section('content')
<div class="min-h-screen bg-gray-200 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative bg-white shadow-lg rounded-2xl m-3 sm:m-0 sm:mb-3 px-5 pt-5 pb-3">
            <div class="flex flex-col">
                <p class="max-w-2xl text-xl text-gray-500 pb-2">
                    What happens when you report a merchant?
                </p>
                <ul class="list-inside text-gray-500 text-md list-decimal">
                    <li class="pb-2">You immediately stop receiving email and SMS payment links from this merchant via Swipez</li>
                    <li class="pb-2">Swipez support team will contact the merchant and understand the reason for sending payment link</li>
                    <li class="pb-2">Merchants who do not adhere to Swipez policies will be banned from the platform</li>
                </ul>
            </div>
        </div>
        <form action="/patron/paymentlink/reportthankyou" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
                <div class="flex flex-col">
                    <div class="flex flex-row p-5">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                            </svg>
                            <h2 class="text-white text-3xl pl-2">Report link</h2>

                        </div>
                    </div>
                    <div class="pt-7 shadow-inner bg-white px-5">
                        <div class="space-y-6 sm:space-y-5">
                            <div>
                                <h2 class="text-2xl leading-6 font-medium text-gray-900">
                                    Report : {{$requestDetails->company_name}}
                                </h2>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                                <label for="reason" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                    Reason for reporting this merchant
                                </label>
                                <div class="mt-1 sm:mt-0 sm:col-span-2">
                                    <select id="reason" name="reason" autocomplete="reason"
                                        class="block max-w-lg w-full shadow-sm focus:ring-aqua focus:border-aqua sm:text-sm border-gray-300 rounded-md" required>
                                        <option value="">Pick a reason</option>
                                        @foreach ($reason as $rk=>$rval)
                                            <option value="{{$rk}}">{{$rval}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Enter your contact information
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Provide your contact details so that we can stop these links from coming to you in
                                    the future from {{$requestDetails->company_name}}
                                </p>
                            </div>
                            <div class="space-y-6 sm:space-y-5">
                                <div
                                    class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="first_name"
                                        class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                        Full name
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <input type="text" name="full_name" id="full_name" required autocomplete="given-name"
                                            class="block max-w-lg w-full shadow-sm focus:ring-aqua focus:border-aqua sm:text-sm border-gray-300 rounded-md" value="{{$requestDetails->full_name}}">
                                    </div>
                                </div>

                                <div
                                    class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                        Email address
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <input id="email" name="email" type="email" required autocomplete="email"
                                            class="block max-w-lg w-full shadow-sm focus:ring-aqua focus:border-aqua sm:text-sm border-gray-300 rounded-md" value="{{$requestDetails->email}}">
                                    </div>
                                </div>

                                <div
                                    class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-b sm:border-gray-200 sm:pt-5 sm:pb-5">
                                    <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                        Mobile number
                                    </label>
                                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                                        <input type="tel" id="mobile" name="mobile" required pattern="[0-9]{10}"
                                            autocomplete="mobile"
                                            class="block max-w-lg w-full shadow-sm focus:ring-aqua focus:border-aqua sm:text-sm border-gray-300 rounded-md" value="{{$requestDetails->mobile}}">
                                    </div>
                                </div>
                                <input type="hidden" value="{{$payment_request_id}}" name="payment_request_id">
                                <input type="hidden" value="{{$requestDetails->merchant_id}}" name="merchant_id">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row-reverse pt-7 p-5 bg-white rounded-b-2xl justify-between">
                    <div class="flex">
                        <button type="button"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua"
                            onclick="location.href='/patron/paymentlink/view/{{$payment_request_id}}'">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-aqua hover:bg-aqua-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-300 ml-4">
                            Report merchant
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

@endsection