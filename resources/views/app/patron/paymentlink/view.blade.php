@extends('getting-started.layout')
@section('content')
<div class="min-h-screen bg-gray-200 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
            <div class="flex flex-col">
                    <div class="flex flex-col p-5">
                        <div class="flex-grow">
                            <h2 class="text-2xl font-bold text-white">
                                {{$requestDetails->company_name}}
                            </h2>
                        </div>
                        <div class="flex">
                            <p class="flex text-sm text-white items-end">
                                <svg class="text-white group-hover:text-gray-500 flex-shrink-0 h-6 w-6"
                                    x-state-description="undefined: &quot;text-gray-500&quot;, undefined: &quot;text-gray-400 group-hover:text-gray-500&quot;"
                                    x-description="Heroicon name: outline/calendar"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="pl-1">Due {{ Carbon\Carbon::parse($requestDetails->due_date)->format('jS F, Y') }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="pt-7 shadow-inner bg-white px-5">
                        <h4 class="uppercase text-gray-400">
                            @if(!empty($requestDetails->narrative))
                                Payment for
                            @else
                                MESSAGE
                            @endif
                        </h4>
                        <p class="text-md text-gray-700 font-mono">
                            @if(!empty($requestDetails->narrative)) 
                                {{$requestDetails->narrative}}
                            @else
                                Thank you and we look forward to serving you again!
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-row-reverse pt-10 bg-white px-5">
                        <div>
                            <h4 class="uppercase text-gray-400">
                                Amount due
                            </h4>
                            <h2 class="text-2xl font-bold text-gray-700 float-right">
                                ₹ {{number_format($requestDetails->absolute_cost, 2, '.', ',')}}
                            </h2>
                        </div>
                    </div>
                    <div class="flex flex-row pt-5 p-5 bg-white rounded-b-2xl justify-between">
                        @if($show_report_link_btn==1)
                            <div class="flex pr-5 sm:pr-10">
                                <a href="/patron/paymentlink/reportlink/{{$payment_request_id}}" class="flex text-sm text-gray-400 items-end">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-aqua-200 h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <span class="pl-1">Report this link</span>
                                </a>
                            </div>
                        @endif
                        @if($show_pay_now_btn==1)
                            <div class="flex justify-end pl-5 sm:pl-10">
                                <button type="button"
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-aqua hover:bg-aqua-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-300"
                                    onclick="location.href='/patron/paymentlink/payeeinfo/{{$payment_request_id}}'">
                                    Pay now
                                </button>
                            </div>
                        @endif
                        @if($show_view_receipt_button==1)
                            <div class="flex pr-5 sm:pr-10">
                            </div>
                            <div class="flex justify-end pl-5 sm:pl-10">
                                <button type="button"
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-aqua hover:bg-aqua-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-300"
                                    onclick="location.href='/patron/paymentlink/receipt/{{$pay_transaction_id}}'">
                                    View receipt
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection