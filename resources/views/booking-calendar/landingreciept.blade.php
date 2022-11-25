@include('booking-calendar.landing-header')
<section class="md:container md:w-1/2 lg:container lg:w-1/2 sm:w-80 text-gray-600 body-font mx-auto">
    <div class="mx-auto my-10 w-100 text-center">
        <h1 class="title-font font-medium text-3xl text-gray-900">Refund Reciept</h1>
    </div>
    <div class="px-4 pb-24 pt-4 mx-auto flex flex-wrap items-center">
        <div class="mt-8 w-full">
            <h2 class="text-gray-900 text-lg font-bold title-font mb-5">Thank you</h2>
            <h2 class="text-gray-900 text-lg font-medium title-font mb-5">
            @if($coupon_code != '')
                We have issued a coupon for the same value and it has been sent to you registered mail ID. Please quote your receipt number for any queries relating to this transaction in future. If Refunded, will be processed in 3-4 days.</h2>
            @else
                Refund has been initiated. It would take 7-10 working days for credit into the bank account.</h2>
            @endif
            <div id="calctable">
                <h2 class="text-gray-900 text-lg font-bold title-font mb-5">Booking Details</h2>
                <div class="h-full">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-600 bg-white">
                                        <thead>
                                            <tr>
                                                <th class="bg-white-100 border text-center px-8 py-4">Date - Time</th>
                                                <th class="bg-white-100 border text-center px-8 py-4">Package</th>
                                                <th class="bg-white-100 border text-center px-8 py-4">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach($slot_details as $key=>$v)
                                            <tr class="bg-white">
                                                <td class="border text-center px-4 py-4">
                                                    <div>
                                                        <p class="lable-heading">
                                                            {{$v->calendar_date}}
                                                        </p>
                                                        <span class="lable-sub-heading">TIME :</span>
                                                        <span class="lable-sub-heading2">{{$v->slot}}</span>
                                                    </div>
                                                </td>
                                                <td class="border text-center px-4 py-4">
                                                    <div>
                                                        <p class="lable-heading">
                                                            {{$v->slot_title}}
                                                        </p>
                                                        <span class="lable-sub-heading">PRICE :</span>
                                                        <span class="lable-sub-heading2"> ₹{{$v->amount}}</span>
                                                        <br>
                                                        <span class="lable-sub-heading">QUANTITY :</span>
                                                        <span class="lable-sub-heading2">
                                                            {{$v->new_qty}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="border text-center px-4 py-4">
                                                    @if($v->total_amount != '0' )
                                                    {{$v->total_amount}}
                                                    @else
                                                    Full Cancelled
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="mt-8 w-full">
            <div class="h-full">
                <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-600 bg-white">
                                    <tbody class="text-center">
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Member Code</td>
                                            <td class="border text-center px-8 py-4">{{$receipt_details->customer_code}}</td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Member Name</td>
                                            <td class="border text-center px-8 py-4">{{$receipt_details->patron_name}}</td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Email ID</td>
                                            <td class="border text-center px-8 py-4">{{$receipt_details->patron_email}}</td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Mobile Number</td>
                                            <td class="border text-center px-8 py-4">{{$receipt_details->patron_mobile}}</td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Refund From</td>
                                            <td class="border text-center px-8 py-4">{{$receipt_details->company_name}}</td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Refund Date</td>
                                            <td class="border text-center px-8 py-4" id="Transdate"></td>
                                        </tr>
                                        <tr class="bg-white">
                                            <td class="border text-center px-8 py-4">Refund Amount</td>
                                            <td class="border text-center px-8 py-4" id="refundAmount">{{$refundAmount}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="https://www.swipez.in/js/jquery-1.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        const timeElapsed = Date.now();
        const today = new Date(timeElapsed);

        document.getElementById('Transdate').innerHTML = today.toLocaleDateString();
    });
</script>


@include('booking-calendar.landing-footer')