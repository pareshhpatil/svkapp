@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron bg-transparent py-5" id="pricing">
    <div class="container">
        <header class="text-center w-md-100 mx-auto">
            <h1 class="h1">Billing software pricing</h1>
            <h2 class="h4 gray-600">Free billing software for small businesses</h2>
            <h2 class="h4 text-primary">Register today and get online transactions worth ₹5 lakhs at no charge 🚀</h2>
        </header>
        <table class="table text-center mt-4 d-none d-lg-table">
            <tbody>
                <tr>
                    <th scope="row" class="border-0"></th>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Free</h2>
                        <h2 class="d-block my-3">₹<span data-monthly="0" data-annual="0">0</span>
                        </h2>
                        <p>
                            <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-outline-primary">Start
                                Now</a>
                        </p>
                    </td>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Startup</h2>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                        </h2>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA" class="btn btn-outline-primary">Buy Now</a>
                        </p>
                    </td>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Growth</h2>
                        <h2 class="d-block my-3">₹<span data-monthly="120" data-annual="83">11,999</span>
                        </h2>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/ee-VQfC4a-YrOCWHw-VP6g" class="btn btn-primary">Buy Now</a>
                        </p>
                    </td>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Enterprise</h2>
                        <h2 class="d-block my-3">Call Us</h2>
                        <p>
                            <a href="{{ route('home.footer.contactus') }}" class="btn btn-outline-primary">Contact</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Invoices</th>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Estimates</th>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Customers</th>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6>2.1%</h6>
                    </td>
                    <td>
                        <h6><span class="font-weight-normal">Starting from</span> 0.50%</h6>
                    </td>
                    <td>
                        <h6><span class="font-weight-normal">Starting from</span> 0.50%</h6>
                    </td>
                    <td>
                        <h6><span class="font-weight-normal">Starting from</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Payment reminders</th>
                    <td>
                        <div class="ctooltip">Only Email <span class="ctooltiptext">Unlimited email</span></div>
                    </td>
                    <td>
                        <div class="ctooltip">Email <span class="ctooltiptext">Unlimited email</span></div> & <div
                            class="ctooltip">SMS <span class="ctooltiptext">2500 free SMS</span></div>
                    </td>
                    <td>
                        <div class="ctooltip">Email <span class="ctooltiptext">Unlimited email</span></div> & <div
                            class="ctooltip">SMS <span class="ctooltiptext">5000 free SMS</span></div>
                    </td>
                    <td>
                        <div class="ctooltip">Email <span class="ctooltiptext">Unlimited email</span></div> & <div
                            class="ctooltip">SMS <span class="ctooltiptext">&nbsp; SMS as per usage &nbsp;</span></div>
                    </td>
                </tr>
                <tr></tr>
                <tr>
                    <th scope="row">Recurring billing</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Vendor payouts</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Offers and Coupons</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Vendor logins</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"
                            class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path fill="currentColor"
                                d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z">
                            </path>
                        </svg>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center border-0" colspan="2">
                        <h2 class="font-weight-light">Free</h2>
                        <p class="h2">₹0</p>
                        <p>
                            <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-outline-primary">Start
                                Now</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Invoices</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Estimates</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Customers</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6>2.1%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Payment reminders</th>
                    <td>Only email</td>
                </tr>
                <tr>
                    <th scope="row">Recurring billing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor payouts</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Offers and coupons</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor logins</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">
                        <h2 class="font-weight-light">Startup</h2>
                        <p class="h2">₹5,999</p>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA" class="btn btn-outline-primary">Buy Now</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Invoices</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Estimates</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Customers</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Payment reminders</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Recurring billing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor payouts</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Offers and coupons</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor logins</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">
                        <h2 class="font-weight-light">Growth</h2>
                        <p class="h2">₹11,999</p>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/ee-VQfC4a-YrOCWHw-VP6g" class="btn btn-primary">Buy Now</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Invoices</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Estimates</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Customers</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Payment reminders</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Recurring billing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor payouts</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Offers and coupons</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor logins</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
            </tbody>
        </table>
        <table class="table text-center mt-5 d-table d-lg-none">
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">
                        <h2 class="font-weight-light">Enterprise</h2>
                        <p class="h2">Call us</p>
                        <p>
                            <a href="/contactus" class="btn btn-outline-primary">Contact</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Invoices</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Estimates</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Customers</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Payment reminders</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Recurring billing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor payouts</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Offers and coupons</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Vendor logins</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
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
                    <h3>What is Swipez Billing?</h3>
                    <p class="font-weight-normal">Swipez Billing is a cloud based software that helps you craft GST
                        friendly invoices, automatically send payment notifications and reminders and collect payments
                        online and file your GST.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3> How does the free plan work?</h3>
                    <p class="font-weight-normal">In the free plan you can experience the billing and invoicing features
                        of Swipez including online payments to see if it suits your needs. And it is free for forever
                        for you to use.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Are there charges for online payments?</h3>
                    <p class="font-weight-normal">We provide the most competitive online transaction charges in
                        the market for our paid users. For more on that click on <a
                            href="{{ route('home.pricing.onlinetransactions') }}" target="_blank">online transaction
                            charges</a></p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Are there any costs for SMS?</h3>
                    <p class="font-weight-normal">Depending on the plan you opt for we give you a SMS pack however after
                        that is exhausted you can top it up from Swipez or integrate with us your own third party SMS
                        provider.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Can I switch plans?</h3>
                    <p class="font-weight-normal">Yes, you can switch to any plan you need at any time. We'd be more
                        than happy to help you find the right plan for your business.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Can I cancel my subscription?</h3>
                    <p class="font-weight-normal">If at any time you wish to cancel your subscription, you can get a pro
                        rata based refund as mentioned on our company refund policy.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>My account lapsed. How do I reactivate it?</h3>
                    <p class="font-weight-normal">Just login to your Swipez account and navigate to package details and
                        click on upgrade my package to select the plan you desire.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-0">
                    <h3>Can I use my own payment gateway?</h3>
                    <p class="font-weight-normal">Yes, you can integrate your own payment gateway with Swipez. We offer
                        several payment gateway integrations out of the box as well.</p>
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
                    billing software</p>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-9 col-xl-6">
                <div class="card p-5">
                    <div class="row">
                        <div class="col-8 col-sm-6 col-md-4 col-xl-3 ml-auto mr-auto">
                            <img alt="Billing software clients picture" class="img-fluid rounded"
                                src="{!! asset('images/product/billing-software/swipez-client1.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"Now we send the monthly internet bills to our customers at the click of a
                                button. Customers receive bills on e-mail and SMS with multiple online payment options.
                                Payments collected online and offline are all reconciled with Swipez."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Jayesh Shah</strong>
                            </p>
                            <p>
                                <em>Founder, Shah Solutions</em>
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
                                src="{!! asset('images/product/billing-software/swipez-client2.jpg') !!}">
                        </div>
                        <div class="col-md-8 mt-4 mt-md-0">
                            <p>"We are now managing payments across our complete customer base along with
                                timely pay outs for all franchisee's across the country from one dashboard. My team has
                                saved over hundred hours after adopting Swipez Billing."</p>
                            <p class="h3 mt-4 mt-xl-5">
                                <strong>Vikas Sankhla</strong>
                            </p>
                            <p>
                                <em>Co-founder, Car Nanny</em>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="jumbotron py-4" id="features">
    <div class="container">
        <div class="row justify-content-center pb-0">
            <div class="col-12 text-center">
                <h2 class="display-4">Features</h2>
            </div>
        </div>
        <div class="row row-eq-height text-center">
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-file-invoice text-primary pb-0"></i></div>
                <h5>On time billing</h5>
                <p>Fast and error-free invoicing with online payment collections. GST compliant invoices with
                    customized invoice templates as per your company needs.</p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-bell text-primary pb-0"></i></div>
                <h5>Automated reminders</h5>
                <p>Payment reminders sent to customers automatically on Email and SMS with payment links.
                    Customizable reminder schedule.</p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-mail-bulk text-primary pb-0 mb-0"></i></div>
                <h5>Bulk invoicing</h5>
                <p>Raise invoices in bulk to a large customer base via excel upload or APIs. Easy upload formats
                    provided out of the box.</p>
            </div>
        </div>
        <div class="row row-eq-height text-center">
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-retweet text-primary"></i></div>
                <h5>Recurring billing</h5>
                <p>Set up subscription billing and your customers will receive your bills automatically at a set
                    frequency every month.  </p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-piggy-bank text-primary"></i></div>
                <h5>Auto debit payments</h5>
                <p>Set up recurring deductions via wallets. Eliminate OTP input for subscription payment collections
                    resulting in faster collections.</p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-credit-card text-primary"></i></div>
                <h5>Collect payments online</h5>
                <p>Provide multiple payment modes to your customers like UPI, Wallets, Credit, Debit Card, Net
                    Banking. Send and present online receipts to customers upon payments.</p>
            </div>
        </div>
        <div class="row row-eq-height text-center">
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-smile-beam text-primary"></i></div>
                <h5>Customer retention</h5>
                <p>Send custom coupons to customers. Notify customers of new services via SMS. Provide your
                    customers coupons & offers from the largest online brands.</p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-percent text-primary"></i></div>
                <h5>GST filing for billing data</h5>
                <p>File your monthly GST directly from your console. Automated reconciliation and preparation of GST
                    R1 & 3B from your collections data.</p>
            </div>
            <div class="col-md-4">
                <div><i class="icon-rounded icon-rounded-lg fa fa-users text-primary"></i></div>
                <h5>Customer database</h5>
                <p>Built-in customer database to manage all your customer data accurately. Ability to group
                    customers and notify them of your services.</p>
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
                <a class="btn btn-primary btn-lg text-white bg-tertiary"
                    href="javascript:void(groove.widget.toggle())">Chat now</a>
                <a class="btn btn-primary btn-lg text-white bg-secondary"
                    href="/getintouch/billing-software-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection
