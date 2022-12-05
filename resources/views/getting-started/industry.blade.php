@extends('getting-started.layout')
@section('content')
<div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
    <div class="relative sm:max-w-4xl sm:mx-auto">
        <div class="bg-white px-4 py-10 md:p-3 mx-2 lg:mx-0 shadow-xl rounded-xl">
            <div class="flex flex-row justify-center">
                <p class="text-xs text-gray-400 font-bold">{{$total_step}} of {{$total_step}}</p>
            </div>
            <div class="flex flex-col md:flex-row">
                <div class="overflow-hidden">
                    <img class="transform object-cover translate-y-6" src="{{ asset('images/pick-industry.svg') }}" alt="App screenshot">
                </div>
                <div class="pl-4 flex flex-wrap content-start bg-white rounded-xl">
                    <form action="/merchant/industrysave" novalidate x-data="data();" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="w-full max-w-xl mx-auto">
                            <h2 class="text-2xl font-bold text-aqua sm:text-4xl text-center">
                                Customize your account
                            </h2>
                            <p class="text-gray-400 pb-4">Your account will be setup as per your industry and organization size</p>
                            <label for="industy_type" class="mb-1 block text-sm font-medium text-gray-500">Industry</label>
                            <select id="industy_type" onchange="removeValidation('industy_type', 'industry_validation')" x-show="false" required name="industry_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-1 sm:text-sm rounded-md" :class="showvalidation==true && industry=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' ">
                                <option value="">Select..</option>
                                @foreach($industry_list as $v)
                                <option value="{{$v->config_key}}">{{$v->config_value}}</option>
                                @endforeach
                            </select>
                            <span class="text-red-600 font-sm text-sm" id="industry_validation" x-text="showvalidation==true && industry=='' ? 'Select industry type' : '' "></span>


                            <label for="employees" class="block text-sm font-medium text-gray-500 pt-4">Number of employees</label>
                            <select id="employees" onchange="removeValidation('employees', 'employee_validation')" name="employee_count" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-1 sm:text-sm rounded-md" :class="showvalidation==true && employee=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' ">
                                <option value="">Select..</option>
                                <option value="1 - 5">1 - 5</option>
                                <option value="6 - 10">6 - 10</option>
                                <option value="10 - 20">10 - 20</option>
                                <option value="20 - 50">20 - 50</option>
                                <option value="50 - 100">50 - 100</option>
                                <option value="Above 100">Above 100</option>
                            </select>
                            <span class="text-red-600 font-sm text-sm" id="employee_validation" x-text="showvalidation==true && employee=='' ? 'Select number of employees' : '' "></span>


                            <label for="customers" class="block text-sm font-medium text-gray-500 pt-4">Number of customers</label>
                            <select id="customers" onchange="removeValidation('customers', 'customer_validation')" name="customer_count" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-1 sm:text-sm rounded-md" :class="showvalidation==true && customer=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' ">
                                <option value="">Select..</option>
                                <option value="1 - 5">1 - 5</option>
                                <option value="6 - 10">6 - 10</option>
                                <option value="10 - 20">10 - 20</option>
                                <option value="20 - 50">20 - 50</option>
                                <option value="50 - 100">50 - 100</option>
                                <option value="100 - 500">100 - 500</option>
                                <option value="500 - 2500">500 - 2500</option>
                                <option value="2500 - 5000">2500 - 5000</option>
                                <option value="5000 and above">5000 and above</option>
                            </select>
                            <span class="text-red-600 font-sm text-sm" id="customer_validation" x-text="showvalidation==true && customer=='' ? 'Select number of customers' : '' "></span>

                            @if(env('ENABLE_MULTI_CURRENCY'))
                            <label for="currency" class="block text-sm font-medium text-gray-500 pt-4">Currency</label>
                            <select id="currency" multiple x-show="false" required name="currency[]" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-1 sm:text-sm rounded-md" :class="showvalidation==true && industry=='' ? 'border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-aqua focus:border-aqua' ">
                                <option value="">Select..</option>
                                @foreach($currency_list as $v)
                                <option @if($v->code=='INR') selected @endif value="{{$v->code}}">{{$v->code}} {{$v->icon}} </option>
                                @endforeach
                            </select>
                            <span class="text-red-600 font-sm text-sm" id="currency_validation" x-text="showvalidation==true && currency=='' ? 'Select atleast one currency' : '' "></span>
                            @else
                            <input type="hidden" name="currency[]" value="INR" id="">
                            @endif
                            <br /><br /><br /><br />
                            <div class="absolute bottom-0 right-0">
                                <button type="submit" onclick="checkValidation();" class="mr-5 lg:mr-3 mb-3 bg-aqua border border-transparent rounded-md shadow px-6 py-3 inline-flex items-center text-base font-medium text-white hover:bg-aqua-700">Complete setup</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    data = function() {
        return {
            showvalidation: false,
            industry: '',
            employee: '',
            customer: '',
            validate() {
                this.showvalidation = true;
                var validform = true;
                if (this.industry == '') {
                    validform = false;
                }
                if (this.customer == '') {
                    validform = false;
                }
                if (this.employee == '') {
                    validform = false;
                }
                return validform;

            }
        }
    };
</script>
<script>
    $(document).ready(function() {
        $('#industy_type').select2();
        $('#currency').select2();
        let arrow = `
            <svg xmlns="http://www.w3.org/2000/svg" width="19px" class="text-gray-500" style="cursor:pointer" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            `;
        $('.select2-selection__arrow').empty();
        $('.select2-selection__arrow').append(arrow);
        $('.select2-container').addClass('w-full');
        $('.select2-container').removeAttr('style');
        $('.select2-selection').css('height', 'auto');
        $('.select2-selection').css('border-radius', '6px');
        $('.select2-selection').css('border-color', 'rgba(209, 213, 219)');

    });

    function checkValidation() {
        let industry = $('#industy_type').val();
        let employee = $('#employees').val();
        let customer = $('#customers').val();
        let currency = $('#currency').val();
        if ($('#currency_validation').length==0) {
            currency = 'INR';
        } 
        if (industry == '' || employee == '' || customer == '' || currency == null) {

            event.preventDefault()
            if (industry == '') {
                $('#industry_validation').text('Select your industry type');
            } else {
                $('#industry_validation').text('');
            }

            if (employee == '') {
                $('#employee_validation').text('Select the number of employees in your company');
            } else {
                $('#employee_validation').text('');
            }
            if ($('#currency_validation')) {
                if (currency == null) {
                    $('#currency_validation').text('Select atleast one currency');
                } else {
                    $('#currency_validation').text('');
                }
            }

            if (customer == '') {
                $('#customer_validation').text('Select the number of customers your company serves');
            } else {
                $('#customer_validation').text('');
            }
        }
    }

    function removeValidation(input, validation) {
        if ($('#' + input).val() != '') {
            $('#' + validation).text('');
        }
    }
</script>
@endsection