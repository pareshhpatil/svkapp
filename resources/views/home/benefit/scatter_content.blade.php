@extends('home.master')

@section('content')
<section class="jumbotron jumbotron-features bg-transparent py-5" id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Scatter content creation services benefits with Swipez</h1>
                {{-- <h2> </h2> --}}
                <h5>Original and engaging content that ensures customer interest and increase in sales. Attract and engage new customers with your content across different platforms to boost sales and brand recognition</h5>
            </div>
            <div class="col-md-6 center-text">
                <img class="imgs" style="max-width: 50%;" alt="swipez benefits_scatter_content_service" src="{!! asset('images/benefits/scattercontentcreationservices/scatterlogo.png?id=v1')!!}">
                <h5 class="center-text mt-2"> Creative content at your fingertips</h5>
                {{-- <h5 class="center-text">Register your Swipez account — it’s free!
                </h5>
                <p class="center-text">Swipez products help you digitize your business!</p> --}}

                @include('home.product.web_register',['d_type' => "web"])
            </div>
        </div>
    </div>
</section>
<section class="jumbotron bg-secondary py-5" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h2 class="text-white">Get a flat 50% discount on your first project with Swipez</h2>
            </div>
            <div class="col-12 d-none d-sm-block">
                <a class="btn btn-lg text-white bg-primary" href="{{ config('app.APP_URL') }}merchant/register">Start
                    now</a>
            </div>
            <!-- for iPad and mobile -->
            <div class="col-12 d-sm-none">
                <div class="row justify-content-center">
                    <a class="btn btn-lg text-white bg-primary mr-1"
                        href="{{ config('app.APP_URL') }}merchant/register">Start
                        now</a>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</section>

<section class="py-5 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-white rounded-1 p-5">
                    <div class="row">
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Swipez Benefits About Scatter_content" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>About Scatter content creation services</b>
                            </h2>
                            <p>Scatter works with some of the best freelance content writers to create unique and engaging content for your business. Attract and engage new customers with your content across different platforms to increase sales & boost brand image. Whether you're a brand marketer or a content developer, Scatter offers collaboration and workflow management tools, digital asset management, and content strategy.</p>
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits About Scatter_content" src="{!! asset('images/benefits/Swipez_Benefits_About.svg') !!}" width="640" height="360" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 ">
                                <b>About Scatter content creation services</b>
                            </h2>
                            <p>Scatter works with some of the best freelance content writers to create unique and engaging content for your business. Attract and engage new customers with your content across different platforms to increase sales & boost brand image. Whether you're a brand marketer or a content developer, Scatter offers collaboration and workflow management tools, digital asset management, and content strategy.</p>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-tranparent" id="cta">
    <div class="container">
        <div class="row">
            <div class="col mx-auto">
                <div class="bg-secondary rounded-1 p-5">
                    <div class="row text-white">
                        <div class="col-md-6 px-3 d-none d-lg-block">
                            <h2 class="pb-3">
                                <b>Benefits of the Scatter content creation services</b>
                            </h2>
                            <ul class="list-unstyled">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                </svg>
                                Get a flat 50% discount on your first project with Swipez</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                </svg>
                                    Attract & engage customers with your brand’s content</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create an engaging website and social media profiles
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Explain your product  with how-to articles, explainer videos, comprehensive blogs, infographics, & more
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create case studies, E-books, whitepapers, business letters,  and more </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Enhance your marketing strategies with content for  emailers, newsletters, SMS, and more</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Boost social media marketing with engaging social media posts, SEO content, press releases and more
                                </li>  
                            </ul>
                        </div>
                        <div class="col-md-6 d-none d-lg-block">
                            <img alt="Swipez Benefits Scatter-content offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="460" loading="lazy" class="lazyload" />
                        </div>
                    </div>
                    <!-- for iPad and mobile -->
                    <div class="row text-white">
                        <div class="col-md-6 d-lg-none mb-3">
                            <img alt="Swipez Benefits Scatter-content offer" src="{!! asset('images/benefits/Swipez_Benefits_offer.svg') !!}" width="640" height="460" loading="lazy" class="lazyload" />
                        </div>
                        <div class="col-md-6 d-lg-none">
                            <h2 class="py-3 text-center">
                                <b>Benefits of the Scatter content creation services</b>
                            </h2>
                            <ul class="list-unstyled pb-2">
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                </svg>
                                Get a flat 50% discount on your first project with Swipez</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                    </path>
                                </svg>
                                Attract & engage customers with your brand’s content</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create an engaging website and social media profiles</li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Explain your product  with how-to articles, explainer videos, comprehensive blogs, infographics, & more
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Create case studies, E-books, whitepapers, business letters,  and more
                                </li>
                                <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Enhance your marketing strategies with content for  emailers, newsletters, SMS, and more</li>
                                    <li class="lead text-white"> <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="h-6 pr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                                        </path>
                                    </svg>
                                    Boost social media marketing with engaging social media posts, SEO content, press releases and more
                                </li>   
                            </ul>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5 ">
                <h3 class="text-white">Drop us a line and we’ll get in touch
                </h3>
            </div>
            <div class="col-md-12">
                <a  data-toggle="modal" class="btn btn-primary btn-lg text-white bg-tertiary" href="#chatnowModal" onclick="showPanel()">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
