@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron bg-transparent py-5" id="pricing">
    <div class="container">
        <header class="text-center w-md-50 mx-auto">
            <h1 class="h1">URL Shortener pricing</h1>
        </header>
        <p class="text-center h5">Affordable plans for businesses of all shapes and sizes.</p>
        <table class="table text-center mt-4 d-none d-lg-table">
            <tbody>
                <tr>
                    <th scope="row" class="border-0"></th>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Growth</h2>
                        <p><a href="/getintouch/url-shortener-growth" class="btn btn-outline-primary">Get in touch</a></p>
                    </td>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Enterprise</h2>
                        <p><a href="/getintouch/url-shortener-enterprise" class="btn btn-secondary">Get in touch</a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Up to 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.015</td>
                    <td>₹ 0.02</td>
                </tr>
                <tr>
                    <th scope="row">> 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.012</td>
                    <td>₹ 0.015</td>
                </tr>
                <tr>
                    <th scope="row">> 5 MN URLs per month (per URL)</th>
                    <td colspan="2">
                        Customized pricing available
                    </td>
                </tr>
                <tr>
                    <th scope="row">Bulk URL Shortening via file upload</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr></tr>
                <tr>
                    <th scope="row">Click tracking</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom Domain</th>
                    <td></td>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API Access</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">On premise deployment</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">
                        <h2 class="font-weight-light">Growth</h2>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Events</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Up to 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.015</td>
                </tr>
                <tr>
                    <th scope="row">> 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.012</td>
                </tr>
                <tr>
                    <th scope="row">> 5 MN URLs per month (per URL)</th>
                    <td>Customized pricing available</td>
                </tr>
                <tr>
                    <th scope="row">Bulk URL shortening via file upload</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Click tracking</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom domain</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">On premise deployment</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <td class="text-center border-0" colspan="2">
                        <p><a href="/contactus" class="btn btn-primary">Get in touch</a></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">
                        <h2 class="font-weight-light">Enterprise</h2>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Up to 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.02</td>
                </tr>
                <tr>
                    <th scope="row">> 1 MN URLs per month (per URL)</th>
                    <td>₹ 0.015</td>
                </tr>
                <tr>
                    <th scope="row">Bulk URL Shortening via file upload</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Click tracking</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom Domain</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API Access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">On premise deployment</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <td class="text-center border-0" colspan="2">
                        <p><a href="/contactus" class="btn btn-secondary">Get in touch</a></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<section class="jumbotron py-5" id="faq">
    <div class="container text-center">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="h1">Frequently Asked Questions</h2>
                <p class="lead">Looking for more info? Here are some things we're commonly asked</p>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>What is Swipez URL shortener?</h3>
                    <p class="font-weight-normal">Swipez URL shortener is a simple-yet-powerful tool to shorten long
                        links. Use our tool to shorten links and then share them, in addition you can monitor traffic
                        statistics. Ideal for social sharing, hiding affiliate links and much more.
                    </p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Can i use my own domain name?</h3>
                    <p class="font-weight-normal">Our enterprise plan has Custom Short URL features where you use your
                        own domain name. As long as this domain name is not already in-use with your website it will
                        work fine.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Can i deploy the URL shortener on my infrastructure?</h3>
                    <p class="font-weight-normal">Yes, by opting for our enterprise plan. If your organisation's
                        compliance requires the system to stay on your infrastructure we can deploy it accordingly.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Are there any click limits?</h3>
                    <p class="font-weight-normal">No, none of our plans have any click limits.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-5 bg-primary" id="cta">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12 mb-5">
                <h3 class="text-white">More questions?<br /><br />Reach out to our support team. We're here to help.
                </h3>
            </div>
            <div class="col-md-12">
                <a class="btn btn-primary btn-lg text-white bg-tertiary" href="javascript:void(groove.widget.toggle())">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/url-shortener-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
