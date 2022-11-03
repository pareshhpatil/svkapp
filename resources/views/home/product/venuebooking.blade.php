@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5 d-lg-none" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-5 d-none d-lg-block">
                <h1>Venue booking software</h1>
                <p class="lead mb-2">Manage time slots across one or many venues, schedule appointments and publish
                    your booking calendar to your customers. Accept online payments using UPI,  Wallets, Credit,
                    Debit Card or Net Banking - Get more freedom, spend less time on managing bookings.</p>
                @include('home.product.web_register',['d_type' => "web"])
            </div>
            <div class="col-12 col-md-8 m-auto ml-lg-auto mr-lg-0 col-lg-6 pt-5 pt-lg-0 d-none d-lg-block">
                <img alt="Easy to use venue booking software with custmoziable calendars and time slots" class="img-fluid" src="{!! asset('images/product/venue-booking-software.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none text-center">
                <img alt="Easy to use venue booking software with custmoziable calendars and time slots" class="img-fluid" src="{!! asset('images/product/venue-booking-software.svg') !!}" />
                <h1>Venue booking software</h1>
                <p class="lead mb-2">Manage time slots across one or many venues, schedule appointments and publish
                    your booking calendar to your customers. Accept online payments using UPI,  Wallets, Credit,
                    Debit Card or Net Banking - Get more freedom, spend less time on managing bookings.</p>
                @include('home.product.web_register',['d_type' => "mob"])
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="jumbotron jumbotron-features bg-transparent py-6 d-none d-lg-block" id="data-flow">
    <script src="/assets/global/plugins/jquery.min.js?id={{time()}}" type="text/javascript"></script>
    <script src="/js/data-flow/jquery.html-svg-connect.js?id={{time()}}"></script>

    <h1 class="pb-3 gray-700 text-center"><span class="highlighter">Free</span> Venue and calendar management software
    </h1>
    <center>
        <p class="pb-3 lead gray-700 text-center" style="width: 620px;">Manage time slots across one or many venues,
            schedule appointments and publish your booking calendar to your customers. Accept online payments for your
            bookings - Spend less time on managing bookings.
        </p>
    </center>
    @include('home.data_flow');
</section>

<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h3>Ideal for professionals managing calendars for sports & fitness, healthcare, beauty, services &
                    trades.</h3>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <div class="container">
        <div class="row justify-content-center pb-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Online calendar to book time slots in a venue" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/online-calendar-for-venues.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Online calendar to book time slots in a venue" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/online-calendar-for-venues.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Publish your venue calendar online</strong></h2>
                <p class="lead">Create and publish your venue booking calendar and available time slots in minutes.
                    Create
                    a closed user group or an open calendar link allowing your customers to book, or share your calendar
                    with
                    friends or family.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Publish your venue calendar online</strong></h2>
                <p class="lead">Create and publish your venue booking calendar and available time slots in minutes.
                    Create
                    a closed user group or an open calendar link allowing your customers to book, or share your calendar
                    with
                    friends or family.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Time slot calendar for venue management" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/time-slot-management-for-venues.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Time slot calendar for venue management" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/time-slot-management-for-venues.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Manage bookings for your venue time slots</strong></h2>
                <p class="lead">Create multiple calendars and time slots for your bookings. Allow your customers to
                    choose a date and time that works for them, without calling you up.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Manage bookings for your venue time slots</strong></h2>
                <p class="lead">Create multiple calendars and time slots for your bookings. Allow your customers to
                    choose a date and time that works for them, without calling you up.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Membership management for venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/membership-management-for-venues.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Membership management for venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/membership-management-for-venues.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Manage memberships and renewals</strong></h2>
                <p class="lead">Set up memberships with different packages. Set up payments for non-members and make
                    your time slots free for members. Send reminders and collect renewal fees online.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Manage memberships and renewals</strong></h2>
                <p class="lead">Set up memberships with different packages. Set up payments for non-members and make
                    your time slots free for members. Send reminders and collect renewal fees online.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Customize calendar for bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/customize-booking-calendar.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Customize calendar for bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/customize-booking-calendar.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Customize booking calendars</strong></h2>
                <p class="lead">Design and create calendars to suit your requirements with ease. Affix a calendar title, define the booking unit and the maximum number of slots per calendar, and more. Add a description and image for your calendar that reflects your brand image to ease your customer’s selection.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Customize booking calendars</strong></h2>
                <p class="lead">Design and create calendars to suit your requirements with ease. Affix a calendar title, define the booking unit and the maximum number of slots per calendar, and more. Add a description and image for your calendar that reflects your brand image to ease your customer’s selection.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Time slots as per your business requirement" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/time-slots-customizable.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Time slots as per your business requirement" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/time-slots-customizable.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Customizable calendar time slots</strong></h2>
                <p class="lead">Customize slots for different calendars with custom configurations. Tailor the date, time-frame, slot price, maximum number of slots available for each calendar, and more. Add an interval of customizable duration between two time slots, include multiple holidays within the calendar, create multiple calendars with the same specifications.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Customizable calendar time slots</strong></h2>
                <p class="lead">Customize slots for different calendars with custom configurations. Tailor the date, time-frame, slot price, maximum number of slots available for each calendar, and more. Add an interval of customizable duration between two time slots, include multiple holidays within the calendar, create multiple calendars with the same specifications.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Notify customers of bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/notify-customers.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Notify customers of bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/notify-customers.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Personalize customer notifications</strong></h2>
                <p class="lead">Create customized messages to present your customers upon payment confirmation and include in the email/PDF with QR codes your customer will receive. Add any details or information you want to convey regarding the booking including terms & conditions, cancellation & refund policies pertaining to the package or slots.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Personalize customer notifications</strong></h2>
                <p class="lead">Create customized messages to present your customers upon payment confirmation and include in the email/PDF with QR codes your customer will receive. Add any details or information you want to convey regarding the booking including terms & conditions, cancellation & refund policies pertaining to the package or slots.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Reports for bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/booking-calendar-reports.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Reports for bookings" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/booking-calendar-reports.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Comprehensive reports</strong></h2>
                <p class="lead">Get detailed reports on the different booking calendars, the transactions related to each, reservations booked, and more. Filter and track the different booking transactions by date, calendar, or payment status. Initiate refunds with ease and download reports with simple excel exports from a single dashboard.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Comprehensive reports</strong></h2>
                <p class="lead">Get detailed reports on the different booking calendars, the transactions related to each, reservations booked, and more. Filter and track the different booking transactions by date, calendar, or payment status. Initiate refunds with ease and download reports with simple excel exports from a single dashboard.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Full utlization of venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/calendar-max-utilization.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Full utlization of venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/calendar-max-utilization.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Ensure maximum utilization</strong></h2>
                <p class="lead">Publish your booking calendar online for customers to pick a time for
                    themselves. Update offline bookings on the system to manage inventory. Free up slots against
                    cancellations.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Ensure maximum utilization</strong></h2>
                <p class="lead">Publish your booking calendar online for customers to pick a time for
                    themselves. Update offline bookings on the system to manage inventory. Free up slots against
                    cancellations.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Online payment collection for venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/calendar-with-online-payments.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Online payment collection for venues" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/calendar-with-online-payments.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <h2><strong>Collect payments online</strong></h2>
                <p class="lead">Provide multiple payment options while booking like UPI, Wallets, Credit, Debit Card
                    or Net Banking. Customers receive receipts for their bookings on email and SMS.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Collect payments online</strong></h2>
                <p class="lead">Provide multiple payment options while booking like UPI, Wallets, Credit, Debit Card
                    or Net Banking. Customers receive receipts for their bookings on email and SMS.</p>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Entry management of venues with QR codes" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/organize-venues.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Entry management of venues with QR codes" class="img-fluid" src="{!! asset('images/product/venue-booking-software/features/organize-venues.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Ease your on-ground activities</strong></h2>
                <p class="lead">QR code is sent to patrons upon booking which can be scanned to gain entry. Set up
                    multiple notifications for your team members for every booking.</p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2 class="text-center"><strong>Ease your on-ground activities</strong></h2>
                <p class="lead">QR code is sent to patrons upon booking which can be scanned to gain entry. Set up
                    multiple notifications for your team members for every booking.</p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">See why over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Try it
                    free. No credit
                    card required.</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                <a class="btn btn-outline-primary btn-lg text-white bg-primary" href="{{ route('home.pricing.bookingcalendar') }}">See pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-outline-primary btn-lg text-white bg-primary" href="{{ route('home.pricing.bookingcalendar') }}">Pricing plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">Time slot based bookings made simple!</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center pb-5">
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="calendar-alt" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Manage time slots efficiently</h3>
                    <p>Track booking of time slots made either online or offline. Hide slots from being booked as per
                        changes in availability. Members can book a date and time as per the calendar you setup.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                        <path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Set up membership programs</h3>
                    <p>Set up memberships for your facilities with flexible tenures. Allow time slot bookings as per
                        membership. Renew and collect membership fees online.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="smile-beam" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                        <path fill="currentColor" d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM112 223.4c3.3-42.1 32.2-71.4 56-71.4s52.7 29.3 56 71.4c.7 8.6-10.8 11.9-14.9 4.5l-9.5-17c-7.7-13.7-19.2-21.6-31.5-21.6s-23.8 7.9-31.5 21.6l-9.5 17c-4.3 7.4-15.8 4-15.1-4.5zm250.8 122.8C334.3 380.4 292.5 400 248 400s-86.3-19.6-114.8-53.8c-13.5-16.3 11-36.7 24.6-20.5 22.4 26.9 55.2 42.2 90.2 42.2s67.8-15.4 90.2-42.2c13.6-16.2 38.1 4.3 24.6 20.5zm6.2-118.3l-9.5-17c-7.7-13.7-19.2-21.6-31.5-21.6s-23.8 7.9-31.5 21.6l-9.5 17c-4.1 7.3-15.6 4-14.9-4.5 3.3-42.1 32.2-71.4 56-71.4s52.7 29.3 56 71.4c.6 8.6-11 11.9-15.1 4.5z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">Flexible space management</h3>
                    <p>Manage free or paid bookings as per your business needs. Manage multiple venues from one
                        dashboard with different pricing and time slots. Publish a calendar for each of your venues.
                    </p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="bg-white p-4 h-100">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="qrcode" class="h-16 text-secondary pb-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path fill="currentColor" d="M0 224h192V32H0v192zM64 96h64v64H64V96zm192-64v192h192V32H256zm128 128h-64V96h64v64zM0 480h192V288H0v192zm64-128h64v64H64v-64zm352-64h32v128h-96v-32h-32v96h-64V288h96v32h64v-32zm0 160h32v32h-32v-32zm-64 0h32v32h-32v-32z">
                        </path>
                    </svg>
                    <h3 class="text-secondary pb-2">QR code ready</h3>
                    <p>Customers receive QR code post booking. Use our QR code reader to manage entries to each of your
                        venues smoothly. Ensure security of your venues, only paid or approved members can visit your
                        venue.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who are already
                    using Swipez
                    time slot booking software.</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="image" class="img-fluid rounded" src="{!! asset('images/product/venue-booking-software/swipez-client1.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier time slot bookings for all our facilities & venues was via phone calls
                                and tracked manually on a register. Now with the booking calendar our
                                residents book a facility online within minutes."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Mahesh Shelote</strong>
                            </p>
                            <p>
                                <em>Facility manager, The Crown Greens</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="image" class="img-fluid rounded" src="{!! asset('images/product/venue-booking-software/swipez-client2.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Time slot bookings for our badminton courts is now completely online. Our players are
                                now able to view availability, book a time slot, schedule a coaching session and pay us
                                online via their mobile phones."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Lokesh Sonawane</strong>
                            </p>
                            <p>
                                <em>Founder, LSBA</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron text-white bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3>Start making your life easier & focus on doing what you love.</h3>
            </div>
            <div class="col-md-12 d-none d-sm-block">
                <a class="btn btn-primary btn-lg bg-tertiary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    Now</a>
                <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.bookingcalendar') }}">See
                    pricing plans</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-tertiary mr-1" href="{{ config('app.APP_URL') }}merchant/register">Start Now</a>
                    <a class="btn btn-lg text-white bg-secondary" href="{{ route('home.pricing.bookingcalendar') }}">Pricing
                        plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section id="faq" class="jumbotron py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="row text-center justify-content-center pb-3">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h2 class="display-4">FAQ'S</h2>
                        <p class="lead">Looking for more info? Here are some things we're commonly asked</p>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What kind of venues can I use the Swipez venue booking software for?
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez venue booking software can be used for organising bookings of spaces such as
                                conference rooms, sports venues, gyms, studios, auditoriums, society facilities.
                                Basically any venue that needs to organize its time slot bookings for its members.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTwo">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Can I create a calendar with multiple venues and timeslots?
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, with our venue booking software you can create different calendars with multiple
                                time slots.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingThree">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Can I notify a particular person when there is a relevant booking for them?
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the venue booking software allows you to notify a client and venue manager
                                regarding a booking through email and SMS.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFour">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Can I manage customer memberships using the Swipez Venue Booking software?
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can create and manage venue memberships with different packages and pricing.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingFive">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Is the data entered into the Swipez Venue booking software safe?
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                At Swipez we really value our customer's privacy. You rely on us for a big part of your
                                business and billing, so we really take your needs seriously. That is why the security
                                of our software, systems and data are our number one priority. All information that is
                                transmitted between your browser and Swipez is protected with 256-bit SSl encryption.
                                This verifies that you are not using a phishing site that is impersonating Swipez. This
                                is proof that your data is secure in transit. All the data you have entered into Swipez
                                sits securely behind web firewalls. This system is used by some of the biggest companies
                                in the world and is widely acknowledged as safe and secure.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSix">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Can I take bookings for free activities using the Swipez Venue Booking software?
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can offer zero cost bookings using the Swipez venue booking software ‘Free
                                Plan’.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingSeven">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Can I mark a slot as unavailable on account for holidays in the Swipez Venue Booking
                            software?
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, the Swipez venue booking software allows you to mark unavailable slots, dates and
                                holidays. So that your patrons cannot book those slots.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingEight">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Can I make offline time slot bookings via venue booking system?
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can block time slots for customers directly from your Swipez dashboard using
                                our offline booking feature.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingNine">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Can my customer cancel slots that have been booked and do they get reutilized?
                        </div>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Yes, you can cancel anytime, anywhere. We reutilize and publish the slots after
                                cancellation in order to optimise your venue bookings.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity" itemtype="http://schema.org/Question" id="headingTen">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            How do I manage entry at the venue for customer bookings?

                        </div>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion" itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                The Swipez venue booking software QR reader helps you manage customers entry at the
                                venue.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Accordion wrapper -->
            </div>
        </div>
    </div>
</section>
<script>
    var intcounter = 0;
    var istimer = false;
    var titles1 = ["Event / Booking calender"];
</script>

@endsection
