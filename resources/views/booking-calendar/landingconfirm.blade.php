@include('booking-calendar.landing-header')
<section class="md:container md:w-1/2 lg:container lg:w-1/2 sm:w-80 text-gray-600 body-font mx-auto">
    <div class="mx-auto my-10 w-100 text-center">
        <h1 class="title-font font-medium text-3xl text-gray-900">Confirm Cancellation</h1>
        <p class="leading-relaxed mt-4">You can cancel your booking partially or the whole booking.</p>
    </div>
    <div class="px-4 pb-24 pt-4 mx-auto flex flex-wrap items-center">

        <div class="mt-8 w-full" id="calctable">
            <h2 class="text-gray-900 text-lg font-medium title-font mb-5">New booking details</h2>
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
                                                    <span class="lable-sub-heading2" id="newCount{{$key+1}}"></span>
                                                </div>
                                            </td>
                                            <td class="border text-center px-4 py-4" id="newAmount{{$key+1}}"></td>
                                        </tr>
                                        <script>
                                            var qty = '{{$v->qty}}';
                                            var amount = '{{$v->amount}}';
                                            var count = localStorage.getItem('slot_title{{$key+1}}');
                                            if (count == null) {
                                                count = qty
                                            }
                                            document.getElementById('newCount{{$key+1}}').innerHTML = count
                                            document.getElementById('newAmount{{$key+1}}').innerHTML = '₹' + (count * amount)
                                        </script>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-8 w-full">
            <h2 class="text-gray-900 text-lg font-medium title-font mb-5">Cancellation Details</h2>
            <div class="h-full">
                <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-600 bg-white">
                                    <thead>
                                        <tr>
                                            <th class="bg-white-100 border text-center px-8 py-4">Title</th>
                                            <th class="bg-white-100 border text-center px-8 py-4">Price</th>
                                            <th class="bg-white-100 border text-center px-8 py-4">Cancelled Quantity</th>
                                            <th class="bg-white-100 border text-center px-8 py-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <form action="/patron/booking/cancellation/{{$payment_request_id}}/reciept" method="POST">
                                        @csrf()
                                        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="refundAmountFinal" name="refundAmountFinal" value="">
                                        <tbody class="text-center">
                                            <script>
                                                var refundAmount = 0;
                                                var totalCount = 0;
                                            </script>
                                            @foreach($slot_details as $key=>$v)
                                            <tr class="bg-white">
                                                <td class="border text-center px-8 py-4"> {{$v->slot_title}}</td>
                                                <td class="border text-center px-8 py-4">₹{{$v->amount}}</td>
                                                <td class="border text-center px-8 py-4" id="countdiff{{$key+1}}"></td>
                                                <td class="border text-center px-8 py-4" id="amountdiff{{$key+1}}"></td>
                                            </tr>

                                            <script>
                                                var qty = '{{$v->qty}}';
                                                var amount = '{{$v->amount}}';
                                                var count = localStorage.getItem('slot_title{{$key+1}}');
                                                if (count == null) {
                                                    count = qty
                                                }
                                                document.getElementById('newCount{{$key+1}}').innerHTML = count
                                                document.getElementById('newAmount{{$key+1}}').innerHTML = '₹' + (count * amount)
                                                document.getElementById('countdiff{{$key+1}}').innerHTML = (qty - count) + " of " + qty;
                                                document.getElementById('amountdiff{{$key+1}}').innerHTML = (qty - count) * amount


                                                refundAmount = refundAmount + ((qty - count) * amount);
                                                totalCount = totalCount + count
                                            </script>



                                            <input type="hidden" id="newCount1{{$key+1}}" name="newCount[]" value="">
                                            <input type="hidden" name="slot_title[]" value="{{$v->slot_title}}">
                                            <script>
                                                document.getElementById('newCount1{{$key+1}}').value = count
                                            </script>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="border text-center px-8 py-4" colspan="2"></td>
                                                <td class="border text-center px-8 py-4 font-bold">Total Refund Amount</td>
                                                <td class="border text-center px-8 py-4 font-bold" id="refundAmount"></td>
                                            </tr>
                                        </tfoot>
                                        <script>
                                            document.getElementById('refundAmount').innerHTML = refundAmount;
                                            document.getElementById('refundAmountFinal').value = refundAmount;
                                            if (totalCount == 0) {
                                                document.getElementById('calctable').style.display = 'none';
                                            }
                                        </script>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="w-full mt-2">
            <input type="submit" style="background-color: #18aebf;
                                                                            border: 1px solid #E5FCFF;
                                                                            color: #fff;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" class="float-right mb-4 mt-4" value="Cancel booking">

            </form>
            <a href="{{env('APP_URL')}}/patron/booking/cancellation/{{$payment_request_id}}" style="  
                                                        background-color: #ffffff;
                                                                            border: 1px solid #D9DEDE;
                                                                            color: #275770;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" type="button" class="float-right mb-4 mt-4 mx-2">
                Back
            </a>
        </div>
    </div>
</section>
@include('booking-calendar.landing-footer')