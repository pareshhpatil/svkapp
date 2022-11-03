@include('booking-calendar.landing-header')

<section class="md:container md:w-1/2 lg:container lg:w-1/2 sm:w-80 text-gray-600 body-font mx-auto">
    <div class="mx-auto my-10 w-100 text-center">
        <h1 class="title-font font-medium text-3xl text-gray-900">Cancel booking</h1>
        <p class="leading-relaxed mt-4">Cancel your booking completely or only part of your booking</p>
    </div>
    <h2 class="text-gray-900 text-lg font-medium title-font mb-5">Transaction Summary</h2>
    <div class="portlet">
        <div class="portlet-body">
            <div class="rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="lable-heading">
                            {{$receipt_details->transaction_id}}
                        </p>
                        <span class="lable-sub-heading">TRANSACTION ID</span>
                    </div>
                    <div>
                        <p class="lable-heading">
                            {{$receipt_details->patron_name}}
                        </p>
                        <span class="lable-sub-heading">NAME</span>
                    </div>

                </div>
                <br>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="lable-heading">
                            {{$receipt_details->patron_email}}
                        </p>
                        <span class="lable-sub-heading">EMAIL</span>
                    </div>
                    <div>
                        <p class="lable-heading">
                            ₹{{$receipt_details->amount}}
                        </p>
                        <span class="lable-sub-heading">AMOUNT PAID</span>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <br>
    <h2 class="text-gray-900 text-lg font-medium title-font mb-5">Booking Details</h2>

    <div class="portlet">
        <div class="portlet-body">
            <div class="w-full">
                <div class="">
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
                                                <th class="bg-white-100 border text-center px-8 py-4"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach($slot_details as $key=>$v)
                                            <script>
                                                localStorage.setItem('{{$v->slot_title}}', '0');
                                            </script>
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
                                                        <span class="lable-sub-heading2"> {{$v->qty}}</span>
                                                    </div>
                                                </td>
                                                <td class="border text-center px-4 py-4">₹{{$v->total_amount}} </td>
                                                <td class="border text-center px-2 py-4">
                                                    <div class="w-full" id="packagebutton{{$key+1}}">
                                                        <a href="javascript:void(0)" style="  
                                                        background-color: #ffffff;
                                                                            border: 1px solid #D9DEDE;
                                                                            color: #275770;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" onclick="cancelPackage('{{$key+1}}','{{$v->qty}}')" type="button">
                                                            Cancel package
                                                        </a>
                                                    </div>
                                                    <div style="display: none;" id="cancelButtons{{$key+1}}">
                                                        <div class="w-full">
                                                            <a href="javascript:void(0)" onclick="fullCancelledclicked('{{$key+1}}',0, '{{$v->slot_title}}')" style="  
                                                        background-color: #ffffff;
                                                                            border: 1px solid #D9DEDE;
                                                                            color: #275770;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" class="mb-1 mt-1" type="button">
                                                                Full cancellation
                                                            </a>
                                                        </div>
                                                        <div class="w-full">
                                                            <a href="javascript:void(0)" onclick="PartialCancelledclicked('{{$key+1}}','{{$v->qty}}')" style="  
                                                        background-color: #ffffff;
                                                                            border: 1px solid #D9DEDE;
                                                                            color: #275770;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" class="mb-1 mt-1" type="button">
                                                                Partial cancellation
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div style="display: none;" id="cancelText{{$key+1}}">
                                                        Cancelled
                                                    </div>
                                                    <div id="quantity_button{{$key+1}}" class="quantity_button" style="display:none;">
                                                        <div class="num-block skin-1">
                                                            <div class="num-in">
                                                                <span class="minus" onclick="setpackageID('{{$key+1}}')"></span>
                                                                <input id="couter_value{{$key+1}}" type="text" class="in-num" value="{{$v->qty}}" readonly="">
                                                                <span class="plus" onclick="setpackageID('{{$key+1}}')"></span>
                                                            </div>
                                                            <input id="slot_title{{$key+1}}" type="hidden" value="{{$v->slot_title}}">
                                                            <input id="max_qty{{$key+1}}" type="hidden" value="{{$v->qty}}">
                                                        </div>
                                                    </div>
                                                    <div style="display: none;" id="backButton{{$key+1}}" class="back-button mb-1 mt-1" onclick="backClicked('{{$key+1}}')">
                                                        <a href="javascript:void(0)">Back </a>
                                                    </div>
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
    </div>
    <div class="px-4 pb-24 pt-4 mx-auto flex flex-wrap items-center">
        <div class="w-full">
            <a href="javascript:void(0)" onclick="checkPackageExists()" style="  
                                                        background-color: #18aebf;
                                                                            border: 1px solid #E5FCFF;
                                                                            color: #fff;
                                                                            padding-top: 7px;
                                                                            padding-left: 14px;
                                                                            padding-bottom: 7px;
                                                                            padding-right: 14px;" class=" float-right mb-4 mt-4" type="button">
                Cancel booking
            </a>
        </div>
    </div>
    @if($cancellation_policy != '' )
    <div class="portlet">
        <div class="portlet-body">
            <div class="w-full">
                <h1 class="p-4 card-lable-heading">Cancellation Policy</h1>
                <div class="p-4">
                    <p class="card-lable-text"> {{$cancellation_policy}}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($tandc != '')
    <div class="portlet">
        <div class="portlet-body">
            <div class="w-full">
                <h1 class="p-4 card-lable-heading">Terms and Conditions</h1>
                <div class="p-4">
                    <p class="card-lable-text">{{$tandc}}</p>
                </div>
            </div>
        </div>
    </div>
    @endif


</section>
<script>
    var k = 1;
    var count = "{{$count_slot_details}}";
    while (count >= k) {
        localStorage.setItem('slot_title' + k, 0);
        k++;
    }

    var j = 1;
    while (localStorage.getItem('slot_title' + j)) {
        localStorage.removeItem('slot_title' + j);
        j++;
    }
</script>
@include('booking-calendar.landing-footer')