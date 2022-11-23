@extends('getting-started.layout')
@section('content')
<style>
    .stamp {
        transform: rotate(12deg);
        color: #555;
        font-size: 3rem;
        font-weight: 700;
        border: 0.25rem solid #555;
        display: inline-block;
        padding: 0.25rem 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        font-family: 'Courier';
        -webkit-mask-image: url('/images/grunge.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: multiply;
    }
    .is-approved {
        color: #565ca9;
        border: 0.5rem double #565ca9;
        transform: rotate(-30deg);
        -webkit-mask-position: 2rem 3rem;
        font-size: 1.25rem;
    }
</style>
<div class="min-h-screen bg-gray-200 py-0 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl sm:mx-auto">
        <div class="relative bg-aqua shadow-lg rounded-2xl m-3 sm:m-0">
            <div class="flex flex-col">
                <div class="flex flex-row p-5">
                    {{-- @if ( $logo > 0)
                    <div class="">
                        <img class="h-16 w-16 rounded-xl ring-4 ring-white object-contain"
                            src="/images/demo-logo.png" alt="">
                    </div>
                    @endif --}}
                    <div class="pl-3">
                        <h2 class="text-3xl font-bold text-white pt-1">
                            {{$paymentReceiptDetails->company_name}}
                        </h2>
                        @if ($paymentReceiptDetails->payment_transaction_status == 1)
                            <p class="flex text-md text-white items-end">
                                Transaction receipt
                            </p>
                        @else
                            <p class="flex text-md text-white items-end">
                                Transaction status
                            </p>
                        @endif

                    </div>
                </div>
                <div class="pt-1 shadow-inner bg-white px-5">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Name
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{$paymentReceiptDetails->full_name}}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Mobile
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{$paymentReceiptDetails->mobile}}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Transaction amount
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{$paymentReceiptDetails->amount}}/-
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Transaction status
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($paymentReceiptDetails->payment_transaction_status == 1)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                            Success
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                            Failed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if ($paymentReceiptDetails->payment_transaction_status <> 1)
                                <tr>
                                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                        Failure reason
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                        @if(!empty($paymentReceiptDetails->narrative))
                                           {{$paymentReceiptDetails->narrative}}
                                        @else
                                            Reason not received from bank
                                        @endif
                                    </td>
                                </tr>
                            @endif 
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Transaction Id
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($paymentReceiptDetails->paymentType=='H')
                                        {{$paymentReceiptDetails->offline_response_id}}
                                    @else
                                        {{$paymentReceiptDetails->pay_transaction_id}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    Date & Time
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ Carbon\Carbon::parse($paymentReceiptDetails->created_date)->format('jS F Y h:i:s A') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex bg-white px-5 rounded-b-2xl pb-5">
                    @if ($paymentReceiptDetails->payment_transaction_status == 1)
                        <div class="mr-4 flex-shrink-0 self-center">
                            <span class="stamp is-approved">PAID</span>
                        </div>
                        <div>
                            <p class="mt-1 text-gray-500 text-sm">
                                Your merchant {{$paymentReceiptDetails->company_name}} has been informed of this payment. For any queries you
                                can contact the merchant on
                                <a href="tel:{{$paymentReceiptDetails->merchant_mobile}}" class="underline">{{$paymentReceiptDetails->merchant_mobile}}</a> or <a
                                    href="mailto:{{$paymentReceiptDetails->merchant_email}}"
                                    class="underline">{{$paymentReceiptDetails->merchant_email}}</a>
                            </p>
                        </div>
                    @else
                        @if($paymentReceiptDetails->paymentType=='T')
                            <div class="pt-2">
                                <center>
                                    <button type="button"
                                        class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-aqua hover:bg-aqua-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aqua-300" onclick="location.href='/patron/paymentlink/payeeinfo/{{$paymentReceiptDetails->payment_request_id}}'">
                                        Retry payment
                                    </button>
                                </center>
                                <p class="pt-4 text-gray-500 text-sm">
                                    Your transaction has failed. In case money has been debited from your account, it will
                                    be automatically reverted back in 4 to 5 working days. For any queries you can contact
                                    us on <a href="mailto:support@swipez.in" class="underline">support@swipez.in</a>
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection