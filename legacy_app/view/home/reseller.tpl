<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8"/>
        <title>Swipez | Partner | Earn a recurring income </title>
        <link rel="canonical" href=""/>


        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css?version=4.7" rel="stylesheet" type="text/css"/>
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
        <link href="/assets/global/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
        <link href="/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
        <link href="/assets/global/plugins/cubeportfolio/cubeportfolio/css/cubeportfolio.min.css" rel="stylesheet">
        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="/assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/pricingtable.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/frontend/onepage2/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/admin/layout/css/colorbox.css" rel="stylesheet" type="text/css"/>
        <script src='https://www.google.com/recaptcha/api.js'></script>

        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="/images/briq.ico"/>
    </head>
</head>
{strip}
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-on-scroll" class to the body element to set fixed header layout -->
<body class="page-header-fixed">
    <!-- BEGIN MAIN LAYOUT -->
    <!-- Header BEGIN -->
    <header class="page-header">
        <nav class="navbar navbar-fixed-top" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="toggle-icon">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </span>
                    </button>
                    <div class="logo" itemscope="" itemtype="http://schema.org/Organization">
                    <a itemprop="url" class="navbar-brand" title="Swipez" href="{$server_name}">
                        <img class="logo-default" src="/assets/frontend/onepage2/img/logo_default.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    <img class="logo-scroll" src="/assets/frontend/onepage2/img/logo_scroll.png" alt="Swipez Online Payment" title="Swipez Online Payment Solutions">
                    </a>
                    </div>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown page-scroll">
                            <a class="dropdown-toggle" style="border-color: transparent;" data-toggle="dropdown" data-target="#" href="javascript:;">
                                Products
                            </a>
                            <ul class="dropdown-menu" style="background-color: #275770;margin-top: -20px;">
                                <li style="padding: 0 0;"><a href="{$server_name}/invoicing-software" style="font-size: 14px;">Invoicing Software</a></li>
                                <li style="padding: 0 0;"><a href="{$server_name}/event-management-software" style="font-size: 14px;">Event Management Software</a></li>
                                <li style="padding: 0 0;"><a href="{$server_name}/booking-management-software" style="font-size: 14px;">Booking Management Software</a></li>
                                <li style="padding: 0 0;"><a href="{$server_name}/url-shortener" style="font-size: 14px;">URL Shortener</a></li>
                                <li style="padding: 0 0;"><a href="{$server_name}/website-builder" style="font-size: 14px;">Website Builder</a></li>
                                <li style="padding: 0 0;"><a href="{$server_name}/coupon-management-software" style="font-size: 14px;">Coupon Management Software</a></li>
                            </ul>
                        </li>
                        <li class="page-scroll">
                            <a href="/pricing" >
                                Pricing
                            </a>
                        </li>
                        <li class="page-scroll">
                            <a href="https://helpdesk.swipez.in/help">FAQs</a>
                        </li>
                        <li class="page-scroll">
                            <a href="/partner" >
                                Partner
                            </a>
                        </li>
                        {if $logged_in==true}
                            <li class="page-scroll">
                                <a href="/{$user_type}/dashboard">Dashboard</a>
                            </li>
                            <li class="page-scroll">
                                <a href="/logout">Logout</a>
                            </li>
                        {else}
                            <li class="page-scroll">
                                <a href="/merchant/register">Register</a>
                            </li>
                            <li class="page-scroll">
                                <a href="/login">Login</a>
                            </li>
                        {/if}


                        <!--<li class="page-scroll">
                            <a href="#portfolio">Portfolio</a>
                        </li>

                        <li class="page-scroll">
                            <a href="#contact">Contact</a>
                        </li>-->
                    </ul>
                </div>
                <!-- End Navbar Collapse -->
            </div>
            <!--/container-->
        </nav>
    </header>
    <!-- Header END -->

    <!-- BEGIN INTRO SECTION -->
    <section id="intro">
        <!-- Slider BEGIN -->
        <div class="page-slider">
            <div class="fullwidthbanner-container revolution-slider">
                <div class="banner" >
                    <ul id="revolutionul">
                        <!-- THE NEW SLIDE -->
                        <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="">
                            <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
                            <img src="/assets/admin/layout/img/pricing_banner.jpg" alt="Swipez Pricing Banner" title="Swipez Pricing Details Banner"  >

                            <div class="caption lft tp-resizeme"
                                 data-x="center"
                                 data-y="center"

                                 data-voffset="20"
                                 data-speed="900"
                                 data-start="1000"
                                 data-easing="easeOutExpo">
                                <p class="subtitle-v2" style="font-size: 32px;font-weight: initial;">Join Swipez Partner Program and earn recurring revenue<br></p>
                                <p class="subtitle-v2" style="font-size: 16px;text-align: center;line-height: 0px;font-weight: initial;">No Investment Required | Competitive Pricing | Dedicated Technical & Customer Support | Quick Onboarding</p><br>
                                <p style="text-align: center;"><a onclick="document.getElementById('tab_5').click();" class="btn"  style="background-color: #f3bb76;color: #ffffff;">Join us as a Partner</a></p>
                            </div>
                        </li>
                        <!-- THE NEW SLIDE -->
                    </ul>
                </div>
            </div>
        </div>
        <!-- Slider END -->
    </section>
    <!-- END INTRO SECTION -->
    <br/>
    <!-- BEGIN MAIN LAYOUT -->
    <div class="clearfix"></div>
    <div class="row no-margin">
        <div class="col-md-1"></div>
        <div class="col-md-3">
            <ul class="ver-inline-menu tabbable margin-bottom-10">
                <li class="active">
                    <a data-toggle="tab" href="#tab_1">
                        <i class="fa fa-thumbs-up"></i> Partner Program Overview </a>
                    <span class="after">
                    </span>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_2">
                        <i class="fa fa-link"></i> Why partner with us? </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#earn">
                        <i class="fa fa-money"></i> What can I earn? </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab_4" style="min-height: 37px;padding-top: 8px;">
                        <span style="float: left;margin-top: -8px;"> <i class="fa fa-exchange"></i></span> What do we expect from our Partners? </a>
                </li>
                <li>
                    <a id="tab_5" data-toggle="tab" href="#join">
                        <i class="fa fa-users"></i> Join us </a>
                </li>
            </ul>
        </div>
        <div class="col-md-7">
            <div class="tab-content">
                <div id="tab_1" class="tab-pane {if !isset($haserrors)}active{/if}">
                    <h2>Partner Program Overview</h2>
                    <p style="font-size: 14px">With India going digital, service providers need to automate their systems by going digital as well. They need a complete solution that takes care of payment collections, accounting and reconciliation or customer retention. They must be able to do this without having to learn some complex software. Swipez is an easy to use platform that automates the business end to end. Swipez provides, Bill presentment, accounting integration, payment collections, Customer Retention tools and centralized accounting under one roof. On boarding existing users and getting started is all a matter of a few minutes.</p>

                    <p style="font-size: 14px;">Swipez is relevant to Internet service provider (ISP), telecom network provider, auto-dealer / service centre, educational institutions, hospitality providers or any other business looking to automate bill presentment, payment collections and CRM under one roof.</p>

                    <p style="font-size: 14px;">The Swipez Partner Program is designed to reward Partners that are looking to provide back office & operations management software solutions to their customers.</p>
                    <div class="alert alert-info" style="font-size: 16px;text-align: center;">
                        <strong>Together we provide the tools necessary to make businesses successful.</strong>
                    </div>
                    <p style="text-align: center;"><a onclick="document.getElementById('tab_5').click();" class="btn" style="background-color: #f3bb76;color: #ffffff;">Join us as a Partner</a></p>
                </div>
                <div id="tab_2" class="tab-pane">
                    <div class="col-md-12">
                        <h2>Why partner with us?</h2>
                        <p style="font-size: 14px">Swipez Partners play a key role in delivering our products and services worldwide. We are committed to working with our Partners to help them increase customer satisfaction and build profitable, long-term businesses.</p><br/>
                        <div class="col-md-3">
                            <center><i class="fa fa-line-chart" style="font-size: 36px;"></i></cemter>
                                <h4 class="card-title">Business Growth</h4>
                                <p class="card-text">Collaborate with Swipez team to scale your business with the right set of customers and industry connects.</p>
                        </div>
                        <div class="col-md-3">
                            <center><i class="fa fa-wrench" style="font-size: 36px;"></i></cemter>
                                <h4 class="card-title">Technical Enablement</h4>
                                <p class="card-text">Build expertise on Swipez technology with our training and support programs.</p>
                        </div>
                        <div class="col-md-3">
                            <center><i class="fa fa-bullhorn" style="font-size: 36px;"></i></cemter>
                                <h4 class="card-title">Marketing Support</h4>
                                <p class="card-text">Access marketing best practices to plan and roll out joint marketing campaigns that will help expand your customer reach.</p>
                        </div>
                        <div class="col-md-3">
                            <center><i class="fa fa-users" style="font-size: 36px;"></i></cemter>
                                <h4 class="card-title">Community</h4>
                                <p class="card-text">Engage, share and learn from our strong, and growing partner community.</p>
                        </div>
                    </div>
                    <br/>
                    <p style="text-align: center;"><a onclick="document.getElementById('tab_5').click();" class="btn" style="background-color: #f3bb76;color: #ffffff;">Join us as a Partner</a></p>

                </div>
                <div id="earn" class="tab-pane ">
                    <h2>What can I earn?</h2>
                    <p style="font-size: 14px">Swipez Partners play a key role in delivering our products and services worldwide. We are committed to working with our Partners to help them build profitable, long-term businesses.</p>

                    <p style="font-size: 14px;">As an approved Swipez Partner, you earn a percentage on every sale you make from the Swipez product suite. As the Swipez product is licensed annually, we incentivize our partners not just on the first year sale but also on subsequent renewals year on year.</p>

                    <center><table class="table table-bordered table-hover" style="width:50%;">
                            <thead>
                                <tr>
                                    <th>
                                        Year
                                    </th>
                                    <th>
                                        Revenue Share
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        1st Year
                                    </td>
                                    <td>
                                        40 %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        2nd Year
                                    </td>
                                    <td>
                                        20 %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        3rd Year
                                    </td>
                                    <td>
                                        10 %
                                    </td>
                                </tr>
                            </tbody>
                        </table></center>
                    <h2>See how our partners are generating recurring revenue</h2>
                    <p style="font-size: 14px">Our Partners have the option of selling different Swipez packages and all our packages offer meaningful incentives.</p>
                    <p style="font-size: 14px">Here is an actual illustration of a Partner, who is growing rapidly by selling Swipez packages.</p>

                    <p style="font-size: 14px"><i class="fa fa-plus"></i> &nbsp; One of our Partners has sold Swipez solutions units worth &#8377; 3,96,000 in the past 5 months has made a whopping  &#8377; 1,58,000. At the current rate this Partner stands to make &#8377; 4,00,000.</p>
                    <p style="font-size: 14px"><i class="fa fa-plus"></i> &nbsp; On renewal by signed-up merchants additional revenues automatically adds to subsequent year’s earnings</p>
                    <p style="font-size: 14px"><i class="fa fa-plus"></i> &nbsp; And all this from just one city</p>
                    <center><table class="table table-bordered table-hover" style="width:80%;">
                            <thead>
                                <tr>
                                    <th>
                                        Month
                                    </th>
                                    <th>
                                        Sale value
                                    </th>
                                    <th>
                                        Commission
                                    </th>
                                    <th>
                                        Year 2 renewal revenue
                                    </th>
                                    <th>
                                        Year 3 renewal revenue
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Jan-17
                                    </td>
                                    <td>
                                        &#8377; 65,000
                                    </td>
                                    <td>
                                        &#8377; 26,000
                                    </td>
                                    <td>
                                        &#8377; 13,000
                                    </td>
                                    <td>
                                        &#8377; 4,289
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Feb-17
                                    </td>
                                    <td>
                                        &#8377; 71,500
                                    </td>
                                    <td>
                                        &#8377; 28,600
                                    </td>
                                    <td>
                                        &#8377; 14,300
                                    </td>
                                    <td>
                                        &#8377; 4,292
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Mar-17
                                    </td>
                                    <td>
                                        &#8377; 78,650
                                    </td>
                                    <td>
                                        &#8377; 31,460
                                    </td>
                                    <td>
                                        &#8377; 15,730
                                    </td>
                                    <td>
                                        &#8377; 4,295
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Apr-17
                                    </td>
                                    <td>
                                        &#8377; 86,515
                                    </td>
                                    <td>
                                        &#8377; 34,606
                                    </td>
                                    <td>
                                        &#8377; 17,303
                                    </td>
                                    <td>
                                        &#8377; 4,298
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        May-17
                                    </td>
                                    <td>
                                        &#8377; 95,167
                                    </td>
                                    <td>
                                        &#8377; 38,067
                                    </td>
                                    <td>
                                        &#8377; 19,033
                                    </td>
                                    <td>
                                        &#8377; 4,301
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Total Revenue Earned
                                    </td>
                                    <td>
                                        &#8377; 1,58,733
                                    </td>
                                    <td>
                                        &#8377; 79,366
                                    </td>
                                    <td>
                                        &#8377; 21,475
                                    </td>
                                </tr>
                            </tbody>
                        </table></center>

                    <p style="text-align: center;"><a onclick="document.getElementById('tab_5').click();" class="btn" style="background-color: #f3bb76;color: #ffffff;">Join us as a Partner</a></p>
                </div>
                <div id="tab_4" class="tab-pane">
                    <h2>What do we expect from our Partners?</h2>
                    <p style="font-size: 14px">At Swipez, we believe in putting the customer first. This is probably why none of our merchants have left us since we started this service. We pride ourselves on customer satisfaction and would want our partners to do the same. We expect our partners to forge long term relationships with the clients and that doesn’t end with just making the sale.</p>

                    <p style="text-align: center;"><a onclick="document.getElementById('tab_5').click();" class="btn" style="background-color: #f3bb76;color: #ffffff;">Join us as a Partner</a></p>
                </div>
                <div id="join" class="tab-pane {if isset($haserrors)}active{/if}">
                    <form action="" method="post">
                        <h2>Join our Partner program</h2>
                        {if isset($haserrors)}
                            {if isset($success)}
                                <div class="alert alert-success" id="error" style="text-align:left;">
                                    <button type="button" class="close" data-dismiss="alert"></button>
                                    <p>Your request has been submitted successfully. Our representative will get back to you on the contact details provided in your request.</p>
                                </div>
                            {else}
                                <div class="alert alert-danger" id="error" style="text-align:left;">
                                    <button type="button" class="close" data-dismiss="alert"></button>
                                    <p>Invalid captcha please click on captcha box.</p>
                                </div>
                            {/if}
                        {/if}

                        <!-- Partner form goes here-->
                        <!--
                            Company name
                            Company type    (Indi, Prop, LLP, Pvt Ltd)
                            First name
                            Last name
                            Email id
                            Mobile number
                            Your web site URL (if available)
                            Postal Address
                            Brief Description about your Company / Line of Business (optional)
                            Size of sales team
                            Representing other products? Name them if any
                            Google Captcha
                        -->
                        <div class="row">
                            <div class="col-md-12">

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Company name
                                                    <p style="font-size: 12px;">(If applicable)</p>
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="text" value="{$post.company_name}" name="company_name" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Company type <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <select name="type" required class="form-control select2me" data-placeholder="Select...">
                                                        <option value="Individual">Individual</option>
                                                        <option value="Proprietor">Proprietor</option>
                                                        <option value="LLP">LLP (Partnership)</option>
                                                        <option value="Pvt Ltd">Pvt. Ltd.</option>
                                                    </select>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">First name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" required name="f_name" value="{$post.f_name}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Last name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" required="" name="l_name"  value="{$post.l_name}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Email Id <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="email" name="email" value="{$post.email}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Mobile no. <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="mobile" required="" value="{$post.mobile}" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Postal address <span class="required">
                                                        * </span> </label>
                                                <div class="col-md-8">
                                                    <textarea required name="address"  class="form-control" >{$post.address}</textarea>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Website URL (If available?) <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <input type="text" name="website" value="{$post.website}"  class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Brief Description about your Company / Line of Business (optional) <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <textarea name="description"  class="form-control" >{$post.description}</textarea>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Size of team <span class="required">
                                                    *</span> </label>
                                                <div class="col-md-8">
                                                    <input type="number" name="team_size" required="" value="{$post.team_size}" step="1" min="0" class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Representing other products? Name them if any <span class="required">
                                                    </span> </label>
                                                <div class="col-md-8">
                                                    <textarea name="other_product"  class="form-control" >{$post.other_product}</textarea>
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Captcha <span class="required">
                                                        * </span> </label>
                                                <div class="col-md-8">
                                                    <form id="comment_form" action="form.php" method="post">
                                                        <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>

                                                        <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <p style="text-align: center;">
                            <input name="Join us" type=submit class="btn" style="background-color: #f3bb76;color: #ffffff;" /></p>
                        <div class="alert alert-info" style="font-size: 16px;text-align: center;">
                            <strong>Sign up for a demo session. We promise to make it worth your while.</strong>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->


{/strip}







<!-- BEGIN CLIENTS SECTION -->

<!-- END CLIENTS SECTION -->


<!-- BEGIN CONTACT SECTION -->
