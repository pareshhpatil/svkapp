@extends('getting-started.layout')
@section('content')
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative sm:max-w-4xl sm:mx-auto">
            <div class="bg-white px-4 py-2 md:p-3 mx-2 lg:mx-0 shadow-2xl rounded-xl">
                <!--div class="flex flex-col md:flex-row justify-center items-center"-->
                <div class="flex flex-row justify-center">
                    <p class="text-xs text-gray-400 font-bold">3 of {{$total_step}}</p>
                </div>
                <div class="items-start">
                    <form action="/merchant/preferencesave" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <h2 class="text-2xl font-bold text-aqua sm:text-4xl text-center">
                            How can Swipez help you?
                        </h2>
                        <p class="text-gray-400 pb-4 text-center">Get started by picking one or more of the tools that will make your life easier</p>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature1">
                                            <span class="text-sm font-medium text-gray-600">Online payment
                                                collections</span>
                                            <span class="text-sm leading-normal text-gray-500">Collect payments using
                                                UPI, Wallets, Net Banking, Credit and Debit cards</span>
                                        </span>
                                        <button type="button" for="feature1" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature1" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                            <input id="feature1" x-bind:checked="on" type="checkbox" name="online_payment" value="1" class="hidden" />
                                        </button>

                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature2">
                                            <span class="text-sm font-medium text-gray-600">Create bills or
                                                invoices</span>
                                            <span class="text-sm leading-normal text-gray-500">Create GST bills or
                                                invoices and send it to customers via email & SMS</span>
                                        </span>
                                        <button type="button" for="feature2" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature2" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                            <input id="feature2" x-bind:checked="on" type="checkbox" name="create_invoice" value="1" class="hidden" />
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature3">
                                            <span class="text-sm font-medium text-gray-600">Organize your customer
                                                data</span>
                                            <span class="text-sm leading-normal text-gray-500">Keep you all of your
                                                customer contact information organized and up to date</span>
                                        </span>
                                        <button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature3" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <input id="feature3" x-bind:checked="on" type="checkbox" name="create_customer" value="1" class="hidden" />
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature4">
                                            <span class="text-sm font-medium text-gray-600">GST filing and reconciliation</span>
                                            <span class="text-sm leading-normal text-gray-500">File your GST returns & reconcile your GSTR 2A and ensure your vendor have paid their GST dues</span>
                                        </span>
                                        <button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature4" x-data="{ on: true }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <input id="feature4" x-bind:checked="on" type="checkbox" name="send_paymentlink" value="1" class="hidden" />
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature5">
                                            <span class="text-sm font-medium text-gray-600">Send bulk invoices</span>
                                            <span class="text-sm leading-normal text-gray-500">Send bills or invoices to your customer base via email and SMS</span>
                                        </span>
                                        <button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature5" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <input id="feature5" x-bind:checked="on" type="checkbox" name="bulk_invoice" value="1" class="hidden" />
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center hover:border-gray-400">
                                <div class="max-w-xl w-full -mx-auto">
                                    <div class="flex items-center justify-between">
                                        <span class="flex-grow flex flex-col mr-2" id="feature6">
                                            <span class="text-sm font-medium text-gray-600">Recurring billing or subscriptions</span>
                                            <span class="text-sm leading-normal text-gray-500">Automatically send bills to customers monthly, quarterly, yearly or any other frequency</span>
                                        </span>
                                        <button type="button" @click="on = !on" :aria-pressed="on.toString()" aria-pressed="false" aria-labelledby="feature6" x-data="{ on: false }" :class="{ 'bg-gray-200': !on, 'bg-aqua-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-500 bg-gray-200">
                                            <input id="feature6" x-bind:checked="on" type="checkbox" name="subscription" value="1" class="hidden" />
                                            <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" href="{{ route('gettingstarted.industry') }}" class="mt-4 bg-aqua border border-transparent rounded-md shadow px-6 py-3 inline-flex items-center text-base font-medium text-white hover:bg-aqua-700">Save preferences</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!--/div-->
            </div>
        </div>
    </div>

@endsection