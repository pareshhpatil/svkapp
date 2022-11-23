@extends('getting-started.layout')
@section('content')
<div class="min-h-screen bg-gray-200 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
            <div class="flex flex-col">
                <div class="flex flex-row p-5">
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-white text-3xl pl-2"> {{($status==1) ? 'Report received' : 'Oops'}}</h2>

                    </div>
                </div>
                <div class="py-5 shadow-inner bg-white px-5 rounded-b-2xl">
                    <div>
                        @if($status==1)
                            <h3 class="text-xl font-medium text-gray-900">
                                Thank you for reporting
                            </h3>
                            <p class="mt-1 max-w-2xl text-md text-gray-500">
                                You will immediately stop receiving email and SMS payment links from {{$requestDetails->company_name}}
                                via Swipez.
                            </p>
                            <p class="mt-1 max-w-2xl text-md text-gray-500">
                                Our support team will get in touch with the merchant and understand the
                                reason for sending this payment link.
                            </p>
                        @else
                            <h3 class="text-xl font-medium text-gray-900">
                                Something went wrong !!
                            </h3>
                            <p class="mt-1 max-w-2xl text-md text-gray-500">
                               Please try again
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection