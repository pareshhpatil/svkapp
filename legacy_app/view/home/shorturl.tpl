{strip}
    <!-- Header END -->
    <section id="intro">
        <!-- Slider BEGIN -->
        <div class="page-slider">
            <div class="fullwidthbanner-container revolution-slider">
                <div class="banner">
                    <ul id="revolutionul">
                        <!-- THE NEW SLIDE -->
                        <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="">
                            <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
                            <img src="/assets/frontend/onepage2/img/bg/shorturl.jpg" alt="" width="451" height="300" >

                            <div class="caption lft tp-resizeme"
                                 data-x="center"
                                 data-y="center"
                                 data-voffset="20"
                                 data-speed="900"
                                 data-start="500"
                                 data-easing="easeOutExpo">
                                <h1 class="subtitle-v2" style="font-weight: 400;">UNLEASH THE POWER OF YOUR LINKS!</h1>
                                <p class="hidden-xs" style="transition: all 0s ease 0s; min-height: 0px; min-width: 0px; line-height: 25px; border-width: 0px; margin: 0px 0px 10px; padding: 0px; letter-spacing: 0px; text-transform: none; color: #ffffff; font-weight: 300;font-size: 17px;text-align: center;">
                                    Businesses need URL shorteners and there are a few free ones available for small <br> volumes. However, at scale they are expensive. Swipez is a software as a service <br>offering built for bill presentments, notifications, payments, collections and <br>reconciliations across different payment modes. . One of the key features in Swipez is <br>to send and track SMS / Emails with shortened links within it.


                                </p>
                            </div>

                        </li>
                        <!-- THE NEW SLIDE -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- Slider END -->
    </section>
    <!-- BEGIN INTRO SECTION -->

    <!-- END INTRO SECTION -->

    <!-- BEGIN MAIN LAYOUT -->
    <div class="page-content">
        <!-- SUBSCRIBE BEGIN -->
        <section id="shorturl">
            <!-- Products BEGIN -->
            <div class="features-bg">
                <div class="container" id="container1">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">

                                <form action="" method="post" onsubmit="return shorturl();" id="submit_form">
                                    <div class="" id="longurl" >
                                        <div class="col-md-9">
                                            <input type="text" id="_url" onkeydown="changeBtn();" name="long_url" class="form-control shu_text" placeholder="Paste a url to shorten it">
                                            <span id="errorspan" class="errormsg-box" ></span>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="input-group-btn">
                                                <button type="submit" id="btn_short"  class="btn default shu_btn">
                                                    <span id="shortbtntext"> Shorten </span>
                                                </button>
                                                <span id="short_copy"></span>
                                                <a id="btn_copy" class="btn default bs_growl_show"  data-clipboard-action="copy" data-clipboard-target="shorturl" style="display: none;">
                                                    <span> Copy </span>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>



                <div id="shortdiv" class="container" {if $short_div==''}style="display: none;"{/if}>
                        <div class="form-group shu_list" id="short_url_list">
                            {$short_div}
                        </div>
                </div>

            </div>
            <!-- Products END -->
        </section>
        <!-- SUBSCRIBE END -->
        <!-- BEGIN FEATURES SECTION -->
        <section id="invoices" >
            <!-- Products BEGIN -->
            <div class="features-bg" style="background-color: #ffffff;">

                <div class="container">
                    <div class="heading">
                        <h2 style="font-size: 30px;">WHY SWIPEZ URL SHORTNER</h2>
                    </div><!-- //end heading -->
                    <div class="row">
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/chart-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>URL SHORTENING AT SCALE</strong></h3>
                            <p><br></p>
                            <p style="color: #747e84;font-size: 16px;">
                                If your business requires shortening URLs in large volumes, Swipez is a cost effective solution to solve that problem. Swipez uses a custom algorithm to create and redirect URLs. It handles mass creation and redirection requests as per growing business demands. 
                            </p>
                            </p>
                        </div>
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/link-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>CUSTOM DOMAIN</strong></h3>
                            <p><br></p>
                            <p style="color: #747e84;font-size: 16px;">
                                The short URL system can be exposed via a custom short domain allowing your brand recall to start right from the Short URL itself.
                            </p>
                            </p>
                        </div>
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/infra-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>MANAGED OR HOSTED INFRASTRUCTURE</strong></h3>
                            <p style="color: #747e84;font-size: 16px;">
                                Swipez  URL shortener is available for businesses to be deployed on their company infrastructure for a one time deployment fee. Contact us to know more about this offering.
                            </p>
                            </p>
                        </div>
                    </div>
                    <center>
                        <br>
                        <a href="{$server_name}/merchant/register" class="btn-danger btnlearn" style="background-color: #1bb9d1;">Try Now</a>
                    </center>
                </div>
            </div>
            <!-- Products END -->
        </section>



        <section id="tax">
            <div class="features-bg" style="background-color: #f5fafa;">

                <div class="container">

                    <div class="row">
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/api-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3" ><strong>API ACCESS</strong></h3>
                            <p><br></p>
                            <p class="shu_p" >
                                Users can create Short URLs in the system in 2 ways. Upload a file with the URLs to be shortened OR invoke our APIs. This makes it easy for either business users or existing systems to use the Short URL system.
                            </p>
                            </p>
                        </div>
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/track-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>CLICK TRACKING</strong></h3>
                            <p><br></p>
                            <p class="shu_p">
                                Click tracking reports are available in a CSV format. They allow you to analyse who clicked on your communication and customize subsequent marketing communication accordingly.
                            </p>
                            </p>
                        </div>
                        <div class="col-md-4 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/tested-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>TRIED AND TESTED</strong></h3>
                            <p><br></p>
                            <p class="shu_p">
                                Swipez is used by large financial institutions like Reliance Mutual Fund, Aon Global Insurance Brokers among others. It has been audited for security and infrastructure.
                            </p>
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </section>

        <!-- BEGIN FEATURES SECTION -->
        <section id="invoices" >
            <!-- Products BEGIN -->
            <div class="features-bg" style="background-color: #ffffff;">

                <div class="container">

                    <div class="row">
                        <div class="col-md-3 md-margin-bottom-70" style="text-align: center;">
                        </div>
                        <div class="col-md-6 md-margin-bottom-70" style="text-align: center;">
                            <img src="/assets/frontend/onepage2/img/feature/pricing-icon.png"  alt="Bulk invoicing with auto-generated invoice numbers">
                            <p>
                            <h3 class="shu_h3"><strong>FLEXIBLE PRICING TIERS</strong></h3>
                            <p style="color: #747e84;font-size: 16px;">
                                Pay as you go pricing for businesses of all sizes. See pricing table
                            </p>
                            </p>
                            <br><br>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <table class="table table-hover" >
                                <thead>
                                    <tr>
                                        <th class="featureheader">
                                            Features
                                        </th>
                                        <th class="featureheader  td-c" style="width: 20%;">
                                            Standard
                                        </th>
                                        <th class="featureheader  td-c" style="width: 20%;">
                                            Professional
                                        </th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="featuretd1">
                                            Upto 1 MN URLs per month (per URL)
                                        </td>
                                        <td class="featuretd1 td-c">
                                            ₹ 0.015
                                        </td>
                                        <td class="featuretd1 td-c">
                                            ₹ 0.02
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd2">
                                            > 1 MN URLs per month (per URL)
                                        </td>
                                        <td class="featuretd2 td-c">
                                            ₹ 0.012
                                        </td>
                                        <td class="featuretd2 td-c">
                                            ₹ 0.015
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd1">
                                            > 5 MN URLs per month (per URL)
                                        </td>
                                        <td colspan="2" class="featuretd1 fcontactus td-c">
                                            Contact us
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd2">
                                            Bulk URL Shortening via file upload
                                        </td>
                                        <td class="featuretd2 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                        <td class="featuretd2 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd1">
                                            Click tracking
                                        </td>
                                        <td class="featuretd1 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                        <td class="featuretd1 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd2">
                                            Custom Domain
                                        </td>
                                        <td class="featuretd2 td-c">
                                            <img src="/assets/admin/layout/img/no.png">
                                        </td>
                                        <td class="featuretd2 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="featuretd1">
                                            API Access
                                        </td>
                                        <td class="featuretd1 td-c">
                                            <img src="/assets/admin/layout/img/no.png">
                                        </td>
                                        <td class="featuretd1 td-c">
                                            <img src="/assets/frontend/onepage2/img/feature/check-circle-anticon.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="featurefooter">
                                            *For pricing / queries for on premise deployment of URL shortener deployment contact us
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                            <center><a href="{$server_name}/merchant/register" style="background-color: #f89030;" class="btn-danger btnlearn" >Try Now</a></center>

                        </div>
                    </div>

                </div>
            </div>
            <!-- Products END -->
        </section>









    </div>
{/strip}

