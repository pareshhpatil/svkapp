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
                            ₹ {{ Helpers::moneyFormatIndia($receipt_details->amount,2)}}
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
                <span class="lable-sub-heading"> All your bookings have been cancelled. </span>
            </div>
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
@include('booking-calendar.landing-footer')