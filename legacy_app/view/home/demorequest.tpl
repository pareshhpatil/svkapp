    <!-- BEGIN INTRO SECTION -->
    <section id="intro">
        <!-- Slider BEGIN -->
        <div class="page-slider">
            <div class="fullwidthbanner-container revolution-slider" >
                <div class="banner">
                    <ul id="revolutionul">
                        <!-- THE NEW SLIDE -->
                        <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="">
                            <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
                            <img src="/assets/admin/layout/img/pricing_banner.jpg" alt=""  >

                            <div class="caption lft tp-resizeme"
                                 data-x="center"
                                 data-y="center"

                                 data-voffset="20"
                                 data-speed="900"
                                 data-start="1000"
                                 data-easing="easeOutExpo">
                                <p class="subtitle-v2" style="font-size: 32px;font-weight: initial;">SELL MORE. GET PAID FASTER. AUTOMATE REPORTING.<br></p>

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
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <div class="tab-content">

                <div id="tab_5" class="tab-pane active">
                    <form action="" method="post">
                        <h2>Request for Demo</h2>
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

                        <!-- reseller form goes here-->
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

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" value="{$post.name}" name="name" required class="form-control" >
                                                    <span class="help-block"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Company name <span class="required">
                                                        * </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" value="{$post.company_name}" name="company_name" required class="form-control" >
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
                                                <label class="control-label col-md-4">Preferred Date & Time <span class="required">
                                                        * </span></label>
                                                <div class="col-md-5 no-padding" >
                                                    <input class="form-control form-control-inline input-sm date-picker" type="text" required  value="{$current_date}" name="date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Preferred Date"/>
                                                    <span class="help-block"> </span>
                                                </div>
                                                <div class="col-md-3 no-padding">
                                                    <div class="input-group">
                                                        <input type="text" name="time" class="form-control timepicker timepicker-no-seconds">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
                                                        </span>
                                                    </div>
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
                            <strong>Request for a demo session. We promise to make it worth your while.</strong> 
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










<!-- BEGIN CLIENTS SECTION -->

<!-- END CLIENTS SECTION -->


<!-- BEGIN CONTACT SECTION -->
