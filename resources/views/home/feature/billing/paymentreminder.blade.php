@extends('home.master')
@section('title', 'Free invoice software with payment collections and payment reminders')
@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-10 col-xl-8 mx-auto text-center">
                <h1 class="display-4 letter-spacing-lg mb-5">Payment Reminder
                    <br>
                </h1>
                <h2 class="mb-5">Reduce payment delays from clients. Get paid on time!</h2>
                <a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3"
                    href="{{ config('app.APP_URL') }}merchant/register">Get a free account</a>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12 col-lg-11 mx-auto">
                <img class="d-block w-100 shadow-lg
                 rounded-lg" src="{!! asset('images/product/payment-reminder.png') !!}" style="margin-bottom: -20rem;">
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-secondary text-white" id="cta">
    <div class="container">
        <div class="row text-center" style="margin-top: 20rem;">
            <div class="col-md-12">
                <h2>Automate your payment reminders and improve your companies cashflow</h2>
                <br /><a class="btn btn-primary btn-lg d-inline-block mr-3 mt-xl-0 mt-3"
                    href="{{ config('app.APP_URL') }}merchant/register">Start using for free</a>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="features">
    <!-- reusable svg icon -->
    <svg style="display: none" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class=""
        role="img" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <symbol id="tickmark">
                <path fill="currentColor"
                    d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                </path>
            </symbol>
        </defs>
    </svg>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">How to create a friendly payment reminder?</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="How to create a payment reminder" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/create-payment-reminder.svg') !!}" />
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="How to create a payment reminder" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/create-payment-reminder.svg') !!}" />
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 m-md-auto">
                <h2><strong>Customize your payment reminder schedule</strong></h2>
                <p class="lead">Customize the frequency, schedule, and mode of communication for your payment reminders.
                    You can also add payment links to your reminders to ensure prompt payments.</p>
                <h2><strong>Enable custom notifications for your invoices</strong></h2>
                <p class="lead">Enable the customize notification text plugin on your invoices and customers
                    receiving the invoices will be automatically notified as per your requirements.</p>
                <h2><strong>Create a covering note</strong></h2>
                <p class="lead">Courteous but firm. Create a covering note template that conveys the message you want to
                    convey. No need to create a new covering note for each customer. The details of the invoice and the
                    customer will be auto-picked and added to your covering notes.</p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Over {{env('SWIPEZ_BIZ_NUM')}} businesses trust Swipez.<br /><br />Get your free
                    account today</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-secondary"
                    href="{{ config('app.APP_URL') }}merchant/register">Register now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-secondary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Register now</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="Why should you use Swipez’ payment reminder?">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Why should you use Swipez’ payment reminder?</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-3">
            <!-- end -->
            <div class="col-12 col-md-12 d-none d-lg-block">
                <p class="lead">The most effective way of sending a payment reminder to your client is via an email or a
                    SMS.</p>
                <p class="lead">Now, it’s not as if, you send them 1 email and 1 sms and the amount gets magically
                    credited into your account. Several reminders need to be sent to your clients in frequent intervals,
                    especially when the due date is round the corner.
                </p>
                <p class="lead">Setting these reminders, crafting them and sending them across is time consuming,
                    expensive and more hassle than it's worth.</p>
                <p class="lead">There’s a simpler way of doing things though…</p>
                <p class="lead">With Swipez, you can schedule, automate, <a
                        href="https://helpdesk.swipez.in/help/customize-your-email-subject-and-sms-text-message-content-for-your-invoices"
                        target="_blank">personalize</a> and send a payment reminder to your clients and ensure timely
                    payments.
                </p>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <p class="lead">The most effective way of sending a payment reminder to your client is via an email or a
                    SMS.</p>
                <p class="lead">Now, it’s not as if, you send them 1 email and 1 sms and the amount gets magically
                    credited into your account. Several reminders need to be sent to your clients in frequent intervals,
                    especially when the due date is round the corner.
                </p>
                <p class="lead">Setting these reminders, crafting them and sending them across is time consuming,
                    expensive and more hassle than it's worth.</p>
                <p class="lead">There’s a simpler way of doing things though…</p>
                <p class="lead">With Swipez, you can schedule, automate, <a
                        href="https://helpdesk.swipez.in/help/customize-your-email-subject-and-sms-text-message-content-for-your-invoices"
                        target="_blank">personalize</a> and send a payment reminder to your clients and and ensure
                    timely payments.
                </p>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-transparent" id="Types of Payment Reminders">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">Types of Payment Reminders</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <!-- end -->
            <div class="col-12 col-md-12 d-lg-block">
                <p class="lead">Irrespective of the size of your firm, all businesses at some point of time find
                    themselves in situations where they have to send a payment reminder to their clients.</p>
                <p class="lead">Incorporating automated payment reminder software like Swipez makes your life
                    hassle-free and ensures you get paid on time.</p>
                <p class="lead">There are 2 types of payment reminders: <strong>SMS and Email</strong>.</p>
                <p class="lead">While there isn’t much difference where the format is concerned, timing is everything!
                </p>
                <p class="lead">How frequently you remind your client determines how quickly the amount is credited.</p>
                <p class="lead">With Swipez you can customize, set the frequency and due date, personalize the messages
                    followed by a notification and include invoice details to ensure your client fully understands the
                    deadline and the amount payable.</p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="How to schedule your payment reminder with Swipez">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">How to schedule your payment reminder with Swipez?</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5">
            <!-- end -->
            <div class="col-12 col-md-12 d-none d-lg-block">
                <p class="lead">Sending across payment reminders aid in timely payments. For the bills you raise, an
                    automated reminder is delivered to your customers via Email and SMS.</p>
                <p class="lead">As soon as the invoice is created, the customer receives an email and a SMS. The due
                    date entered while creating an invoice is used to generate further reminders.</p>
                <h2>Types of payment reminder schedule</h2>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <p class="lead">Sending across payment reminders aid in timely payments. For the bills you raise, an
                    automated reminder is delivered to your customers via Email and SMS.</p>
                <p class="lead">As soon as the invoice is created, the customer receives an email and a SMS. The due
                    date entered while creating an invoice is used to generate further reminders.</p>
                <h2>Types of payment reminder schedule</h2>
            </div>
            <!-- end -->
        </div>

        <div class="row text-left align-items-center pt-0 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Setup payment reminder schedule" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/setup-payment-reminder.svg') !!}">
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Setup payment reminder schedule" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/setup-payment-reminder.svg') !!}">
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Default payment reminder schedule</strong></h2>
                <p class="lead">After sending the invoice to your client, they will receive notifications on the
                    following days:</p>
                <ul class="lead">
                    <li>Day the invoice is created</li>
                    <li>3 days before due date</li>
                    <li>1 day before due date</li>
                    <li>On the due date</li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Default payment reminder schedule</strong></h2>
                <p class="lead">After sending the invoice to your client, they will receive notifications on the
                    following days:</p>
                <ul class="lead">
                    <li>Day the invoice is created</li>
                    <li>3 days before due date</li>
                    <li>1 day before due date</li>
                    <li>On the due date</li>
                </ul>
            </div>
            <!-- end -->
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 m-md-auto order-md-5 d-none d-lg-block">
                <img alt="Customize your reminder schedule" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/custom-payment-reminder.svg') !!}">
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none pb-4">
                <img alt="Customize your reminder schedule" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/custom-payment-reminder.svg') !!}">
            </div>
            <!-- end -->
            <div class="col-12 col-md-5 d-none d-lg-block">
                <h2><strong>Customized payment reminder schedule</strong></h2>
                <p class="lead">Set the frequency as per your taste and preferences by following these steps:</p>
                <ul class="list-unstyled pt-2">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Personalize the payment reminder schedule as per your taste, and dare we say, your customer’s
                        requirements
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Enable the customize reminder schedule plugin for your invoices
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Choose the frequency at which you would prefer your customers to be notified. The number of days
                        preceding the due date you would like your reminders to be sent
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Payment reminders will be auto-generated and sent to your customers as per your customized
                        schedule with the invoice and payment details attached
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Personalize the notification text that will be sent to your customers via email & SMS
                    </li>
                </ul>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-lg-none">
                <h2><strong>Customized payment reminder schedule</strong></h2>
                <p class="lead">Set the frequency as per your taste and preferences by following these steps:</p>
                <ul class="list-unstyled pt-2">
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Personalize the payment reminder schedule as per your taste, and dare we say, your customer’s
                        requirements
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Enable the customize reminder schedule plugin for your invoices
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Choose the frequency at which you would prefer your customers to be notified. The number of days
                        preceding the due date you would like your reminders to be sent
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Payment reminders will be auto-generated and sent to your customers as per your customized
                        schedule with the invoice and payment details attached
                    </li>
                    <li class="lead">
                        <svg class="h-4 pr-1 text-primary" viewBox="0 0 512 512">
                            <use href="#tickmark"></use>
                        </svg>
                        Personalize the notification text that will be sent to your customers via email & SMS
                    </li>
                </ul>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">Join {{env('SWIPEZ_BIZ_NUM')}} businesses using Swipez for their daily billing
                    requirements</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary mr-4" href="{{ config('app.APP_URL') }}merchant/register">Get
                    a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Get free billing software now!</a>
                    <a class="btn btn-outline-primary btn-lg text-white mt-2"
                        href="{{ route('home.pricing.billing') }}">See pricing plans</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-transparent py-5" id="When should you send a payment reminder to your clients">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4">When should you send a payment reminder to your clients?</h2>
            </div>
        </div>
        <div class="row text-left align-items-center pt-5 pb-md-5">
            <div class="col-4 col-md-5 d-none d-lg-block">
                <img alt="Schedule your payment reminders" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/schedule-payment-reminders.svg') !!}">
            </div>

            <div class="col-12 d-lg-none pb-4">
                <img alt="Schedule your payment reminders" class="img-fluid"
                    src="{!! asset('images/product/payment-reminder/features/schedule-payment-reminders.svg') !!}">
            </div>

            <div class="col-12 col-md-5 m-md-auto d-none d-lg-block">
                <p class="lead">Your first payment reminder should be sent a week before the due date. You'll keep the
                    payment fresh in your client's memory and give them plenty of time to review the invoice this way.
                </p>
                <p class="lead">You can customize the number of reminders you send and will be determined by your
                    requirements and your client's payment history. If you haven't received the payment, issuing another
                    reminder on the due date and the following week is highly recommended</p>
            </div>
            <div class="col-12 d-lg-none">
                <p class="lead">Your first payment reminder should be sent a week before the due date. You'll keep the
                    payment fresh in your client's memory and give them plenty of time to review the invoice this way.
                </p>
                <p class="lead">You can customize the number of reminders you send and will be determined by your
                    requirements and your client's payment history. If you haven't received the payment, issuing another
                    reminder on the due date and the following week is highly recommended</p>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="How does timely payment reminders help?">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h2 class="display-4 text-white">How does timely payment reminders help?</h2>
            </div>
        </div><br />
        <div class="row row-eq-height text-center">
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <h3 class="text-secondary pb-2">Turn the odds in your favour</h3>
                    <p>The main advantage of payment reminder software is that it consistently reminds your customers to
                        pay on schedule. Subsequently, it turns the odds in your favour of getting paid in a timely
                        manner</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <h3 class="text-secondary pb-2">Be polite yet professional</h3>
                    <p>A message for payment reminder is a considerate way of reminding your clients about outstanding
                        dues. Automate your payment reminder schedule with Swipez and send them to your clients as
                        opposed to calling or messaging them ceaselessly. This is a gentle yet straightforward way of
                        reminding them</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 h-100">
                    <h3 class="text-secondary pb-2">Get paid on time, every time!</h3>
                    <p>Automated payment reminders save you time and money that may be better spent on more important
                        tasks like expanding your business. Manually sending payment reminders is time-consuming,
                        expensive and a bummer! Swipez helps you save time and reminds your customer about the due date
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">A one-stop solution for all your billing & invoicing needs. Get your free account
                    today!</h3>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary mr-4" href="{{ config('app.APP_URL') }}merchant/register">Get
                    a
                    free account</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Get free billing software now!</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>
<section class="jumbotron bg-primary py-5" id="testimonials">
    <div class="container">
        <div class="row text-center justify-content-center pb-5 text-white">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="display-4">Testimonials</h2>
                <p class="lead text-white">You are in good company. Join {{env('SWIPEZ_BIZ_NUM')}} happy businesses who
                    are already
                    using Swipez billing software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Earlier we had to create invoices manually and it was a time consuming activity. With
                                Swipez bulk invoicing solution we are now able to generate invoices for complete
                                customer base with a click of a button using the API integration. "</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Infinite</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-6 mt-4 mt-xl-0">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}" width="166"
                                height="185" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We tried many softwares and tools to generate invoices in bulk. Swipez bulk invoicing
                                has been the best so far. It generates invoices in bulk with a simple excel upload,
                                moreover the invoices are in the exact design and format that need it."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Chandrabhanu P</strong>
                            </p>
                            <p>
                                <em>Founder, Go Seeko</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
                        <h2 class="display-4">Frequently asked questions</h2>
                        <h3 class="lead">Looking for more information? Here are some questions small and medium
                            businesses
                            owners ask</h3>
                    </div>
                </div>
                <!--Accordion wrapper-->
                <div id="accordion" itemscope itemtype="http://schema.org/FAQPage">
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingOne">

                        <div itemprop="name" class="btn btn-link color-black" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h3>Searching for reliable payment reminder software?</h3>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer" itemscope itemprop="acceptedAnswer"
                            itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Today's market is flooded with a plethora of payment reminder software. However, not all
                                software's are created keeping your company's needs, taste and industry in mind.
                                To make things hassle free for you, follow these steps before choosing one
                                <ul>
                                    <li><b>Check Their Goodwill:</b> Choose a reputable software vendor with an
                                        excellent track record in the industry. Additionally, select software that is
                                        appropriate for your sector.</li>
                                    <li><b>Check The Reviews: </b> It's a good idea to perform some web research to get
                                        the best invoice reminder software on the market. Simultaneously, compare
                                        features and read extensive product reviews to get a better idea of what the
                                        market has to offer.</li>
                                    <li><b>Easy Set-up:</b> Make sure the payment reminder software you're purchasing is
                                        simple to set up and integrate with your existing systems. This will save both
                                        time and money.</li>
                                    <li><b>Free Trial:</b> Most invoice reminder software offer a free trial period.
                                        Scrutinize the features of the payment reminder software with a free trial
                                        period to test the waters before you take the plunge. Determine whether the
                                        programme is perfect for your firm. Check to see if the invoicing software can
                                        handle a variety of payment gateways as well as many items and clients.</li>
                                    <li><b>Robust Customer Care:</b> Make sure there is a responsive customer support
                                        team so you can get answers to your questions about payment reminder software
                                        whenever you need them. Support should be available by phone, chat, and email so
                                        that you may contact them in the event of a problem.</li>
                                    <li><b>Price Matters:</b> Select feature-rich payment reminder software that is also
                                        cost-effective. However, some of this software may include optional features for
                                        which you will be charged an additional fee. Make sure you compare prices and
                                        aren't overpaying for features you'll never use.</li>
                                </ul>
                                Now that you know what red flags to look out for, Swipez is an easy-to-use, intuitive
                                and pocket friendly billing software designed to ease the process of billing, payment
                                collections, payment reminders and much more making it the most hassle free and
                                lightning speed billing software for you while you focus all your attention at scaling
                                your company. More so, it’s absolutely free!
                                Should you have any queries, feel free to <a href="{{ config('app.APP_URL') }}contactus"
                                    target="_blank">contact us</a> and we’ll bid your billing woes goodbye
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="headingTwo">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h3>When should you use a payment reminder software?</h3>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                You've finished the job for a client, delivered the invoice, and are now waiting for
                                payment.

                                It makes no difference what kind of business you have. Even after you've ensured to
                                notify them when their payment is due, you're bound to have clients who still owe you
                                money.

                                You're not alone if you dread sending payment reminder emails to your clients. Many
                                business owners are hesitant to ask for payment immediately. But keep in mind that
                                sending a payment reminder email or message is a professional courtesy as long as
                                they're well-written and sent quickly.

                                Swipez’s intuitive and easy to use billing software helps you to send automated payment
                                reminders and courteously inform clients of forthcoming actions like late penalties or
                                collections, in addition to reminding clients that they have outstanding invoices.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="heading3">
                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                            <h3>How are payment reminders sent to my customers?</h3>
                        </div>
                        <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Payment reminders are sent using Email and SMS text message to your customers. The email
                                contains the invoice with an online payment option. Reminder emails can also send the
                                invoice as a PDF attachment with a <a
                                    href="https://helpdesk.swipez.in/help/add-a-covering-note-to-your-invoice"
                                    target="_blank">invoice covering note</a> of your choice. The SMS text message
                                payment reminder contains a short link which takes the customer directly to your invoice
                                or bill with online payment options.
                            </div>
                        </div>
                    </div>
                    <div class="card-header bg-white p-1" itemscope itemprop="mainEntity"
                        itemtype="http://schema.org/Question" id="heading4">

                        <div itemprop="name" class="btn btn-link color-black collapsed" data-toggle="collapse"
                            data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            <h3>How frequently are the payment reminders sent to my customers?</h3>
                        </div>
                        <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordion"
                            itemscope itemprop="acceptedAnswer" itemtype="http://schema.org/Answer">
                            <div class="card-body" itemprop="text">
                                Payment reminders are sent 3 days before, 1 day before and on the due date. Payment
                                reminders are sent only if the invoice is still unpaid i.e. your customer has not paid
                                the invoice so far.<br /><br />
                                You have the option to <a href="https://helpdesk.swipez.in/help/how-to-configure-payment-reminder-schedule" target="_blakn">configure your payment reminders</a> i.e. make your reminders more or
                                less frequent as per your business requirements.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Accordion wrapper -->
        </div>
    </div>
</section>

@endsection
