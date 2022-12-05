@extends('getting-started.layout')
@section('content')
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative sm:max-w-4xl sm:mx-auto">
            <div class="bg-white px-4 py-5 md:p-3 mx-2 lg:mx-0 shadow-xl rounded-xl">
                <div class="flex flex-row justify-center">
                    <p class="text-xs text-gray-400 font-bold">2 of {{$total_step}}</p>
                </div>
                <div class="flex flex-col md:flex-row">
                    <div class="overflow-hidden">
                        <img class="transform object-cover translate-y-6" src="{{ asset('images/welcome.svg') }}"
                            alt="Welcome to Swipez message">
                    </div>
                    <div class="pl-0 md:pl-5 pt-3 lg:pt-0 flex justify-center items-center">
                        <div class="items-start">
                            <h2 class="text-3xl font-bold text-aqua sm:text-4xl">
                                Welcome {{$name}}
                            </h2>

                            <h4 class="mt-4 text-1xl text-aqua md:text-4xl">
                                We're super excited to have you on board
                            </h4>
                            <br/><br/><br/>
                            <!--p class="mt-4 text-lg leading-6 text-white">Let's setup for your account</p-->
                            <div class="absolute bottom-0 right-0">
                                <a href="@if($total_step==4){{ route('gettingstarted.features') }}@else{{ route('gettingstarted.industry') }}@endif"
                                    class="mr-5 lg:mr-3 mb-3 bg-aqua border border-transparent rounded-md shadow px-6 py-3 inline-flex items-center text-base font-medium text-white hover:bg-aqua-700">Let's
                                    get started</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
