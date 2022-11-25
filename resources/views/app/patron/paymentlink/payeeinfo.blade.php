@extends('getting-started.layout')
@section('content')
<div class="min-h-screen bg-gray-200 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        @if($show_payeeinfo_form==1)
            <form action="/patron/paymentrequest/payment/{{$payment_request_id}}" method="post" id="submit_form">
                <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
                    <div class="flex flex-col">
                        <div class="flex flex-row p-5">
                            <div class="">
                                <h2 class="text-3xl font-bold text-white">
                                    Pay now
                                </h2>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-3xl font-bold text-white float-right">
                                    ₹ {{number_format($requestDetails->absolute_cost, 2, '.', ',')}}
                                </h2>
                            </div>
                        </div>
                        <div class="pt-7 shadow-inner bg-white px-5">

                            <div class="space-y-6 sm:space-y-5">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Enter contact information
                                    </h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                        Provide your contact details to complete your online payment to {{$requestDetails->company_name}}.
                                    </p>
                                </div>
                                <div class="space-y-6 sm:space-y-5">
                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                        <label for="name"
                                            class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                            Full name
                                        </label>
                                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                                            <input type="text" name="name" required id="name" autocomplete="given-name"
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
                                            <input type="tel" id="mobile" required name="mobile" pattern="[0-9]{10}"
                                                autocomplete="mobile"
                                                class="block max-w-lg w-full shadow-sm focus:ring-aqua focus:border-aqua sm:text-sm border-gray-300 rounded-md" value="{{$requestDetails->mobile}}">
                                        </div>
                                    </div>
                                    <input type="hidden" name="address" value="{{$requestDetails->address}}"/>
                                    <input type="hidden" name="city" value="{{$requestDetails->city}}"/>
                                    <input type="hidden" name="zipcode" value="{{$requestDetails->zipcode}}"/>
                                    <input type="hidden" name="state" value="{{$requestDetails->state}}"/>
                                    <input type="hidden" name="payment_mode" value=""/>
                                    <input type="hidden" name="payment_req" value="{{$payment_request_id}}"/>
                                    <input type="hidden" name="customer_id" value="{{$requestDetails->customer_id}}"/>
                                    <input type="hidden" name="deduct_amount" value="0"/>
                                    <input type="hidden" name="deduct_text" value=""/>
                                    <input type="hidden" name="coupon" value=""/>
                                    <input type="hidden" name="coupon_id" value=""/>
                                    <input type="hidden" name="autocollect_plan_id" value=""/>
                                    <input type="hidden" name="discount" value="0" />
                                    <input type="hidden" name="return_url" value="{{env('SWIPEZ_BASE_URL')}}patron/paymentlink/payeeinfo/{{$payment_request_id}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row-reverse pt-7 p-5 bg-white rounded-b-2xl justify-between">
                        <div class="flex">
                            <button type="button"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua" onclick="location.href='/patron/paymentlink/view/{{$payment_request_id}}'">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-aqua hover:bg-aqua-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-300 ml-4">
                                Make payment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @elseif ($show_payeeinfo_form==0)
            <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
                <div class="flex flex-col">
                    <div class="flex flex-row p-5">
                        <div class="">
                            <h2 class="text-3xl font-bold text-white">
                                Account activation required
                            </h2>
                        </div>
                    </div>
                    <div class="pt-7 shadow-inner bg-white px-5">
                        <div class="space-y-6 sm:space-y-5">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Payment gateway account not active
                                </h3>
                                <p class="mt-3 max-w-2xl text-sm text-gray-500">
                                    {{$requestDetails->company_name}} has not yet activated their payment gateway account. We have informed them of your interest to pay them online.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse pt-7 p-5 bg-white rounded-b-2xl justify-between">
                </div>
            </div>
        @endif
    </div>
</div>
@endsection