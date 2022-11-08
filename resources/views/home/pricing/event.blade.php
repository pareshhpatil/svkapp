@extends('home.master')
@section('title', 'Helping businesses collect payments faster by improving their business operations')

@section('content')
<section class="jumbotron bg-transparent py-5" id="pricing">
    <div class="container">
        <header class="text-center w-md-50 mx-auto">
            <h1 class="h1">Event registration pricing</h1>
        </header>
        <p class="text-center h5">Affordable annual plans for businesses of all shapes and sizes.</p>
        <table class="table text-center mt-4 d-none d-lg-table">
            <tbody>
                <tr>
                    <th scope="row" class="border-0"></th>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Free</h2>
                        <h2 class="d-block my-3">₹<span data-monthly="0" data-annual="0">0</span>
                        </h2>
                        <p>
                            <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-outline-primary">Start Now</a>
                        </p>
                    </td>
                    <td class="text-center border-0">
                        <h2 class="font-weight-light">Professional</h2>
                        <h2 class="d-block my-3">₹<span data-monthly="15" data-annual="12">5,999</span>
                        </h2>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA" class="btn btn-primary">Buy Now</a>
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
                    <th scope="row">Events</th>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Ticket bookings</th>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6>4%</h6>
                    </td>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Ticket confirmations</th>
                    <td><div class="ctooltip">Only Email <span class="ctooltiptext">Unlimited email</span></div></td>
                    <td><div class="ctooltip">Email <span class="ctooltiptext">Unlimited email</span></div> & <div class="ctooltip">SMS <span class="ctooltiptext">&nbsp; 2500 free SMS &nbsp;</span></div></td>
                    <td><div class="ctooltip">Email <span class="ctooltiptext">Unlimited email</span></div> & <div class="ctooltip">SMS <span class="ctooltiptext">&nbsp; SMS as per usage &nbsp;</span></div></td>
                </tr>
                <tr></tr>
                <tr>
                    <th scope="row">Promotional SMS</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">QR reader</th>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">GST filing</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Manage franchises</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">API based access</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Private white labelled</th>
                    <td></td>
                    <td></td>
                    <td>
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Custom integrations</th>
                    <td></td>
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
                    <td class="text-center border-0" colspan="2">
                        <h2 class="font-weight-light">Free</h2>
                        <p class="h2">₹0</p>
                        <p>
                            <a href="{{ config('app.APP_URL') }}merchant/register" class="btn btn-outline-primary">Start Now</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Events</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Ticket bookings</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6>4%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Ticket confirmations</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Promotional SMS</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="h-6 gray-400" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">QR reader</th>
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
                        <h2 class="font-weight-light">Professional</h2>
                        <p class="h2">₹5,999</p>
                        <p>
                            <a href="https://www.swipez.in/merchant/package/confirm/O7MGk6h3Jgazj1Hy796ELA" class="btn btn-primary">Buy Now</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Events</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Ticket bookings</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Ticket confirmations</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Promotional SMS</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">QR reader</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
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
                    <th scope="row">Events</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Ticket bookings</th>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row">Online payments</th>
                    <td>
                        <h6><span class="font-weight-normal">Starts at</span> 0.50%</h6>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Ticket confirmations</th>
                    <td>Email & SMS</td>
                </tr>
                <tr>
                    <th scope="row">Promotional SMS</th>
                    <td><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check"class="h-6 text-primary" role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg></td>
                </tr>
                <tr>
                    <th scope="row">QR reader</th>
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
                    <h3>What is Swipez Events?</h3>
                    <p class="font-weight-normal">Swipez Events is an online registration and ticketing software that helps event planners reduce the administrative burden faced during a live event.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Can I use Swipez for free events?</h3>
                    <p class="font-weight-normal">Yes! For free events you can use the Swipez free plan where there will be 0 charges incurred for taking registrations and bookings.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>How much costs do I incur per ticket sale?</h3>
                    <p class="font-weight-normal">.</p>
                </div>
            </div>
            <div class="col-md-8 offset-md-2 col-sm-12">
                <div class="mb-5">
                    <h3>Is there an upper limit on transaction amounts or bookings for the free plan?</h3>
                    <p class="font-weight-normal">No, there are no limits to the number of bookings you can take and no cap on the ticket value on any of our plans.</p>
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
                <a class="btn btn-primary btn-lg text-white bg-secondary" href="/getintouch/event-registration-pricing">Send email</a>
            </div>
        </div>
    </div>
</section>
@endsection