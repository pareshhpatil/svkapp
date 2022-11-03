<link rel="stylesheet" type="text/css" href="/assets/cable/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/owl.carousel.css" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/owl.theme.css" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/animate.css?version=1551089434" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/style.css?version=1551275752" />
<link rel="stylesheet" type="text/css" href="/assets/cable/css/responsive.css?version=1551275339" />

<style>
    h4, h5, h6 {
        font-weight: 200;
    }
    .loginPage {
        background-image: url('/assets/admin/layout/img/booking_bg.jpg');height: 858px;background-repeat: round;
    }
    
</style>

<div class="row ">            
    <div class="col-md-12" >

        <section class="loginPage">
            <div class="container">
                <div class="colTable vh100">
                    <div class="colCell">

                        <form action="/m/{$url}/memberregister" method="post" >
                            <div class="login_div">
                                {if isset($haserrors)}
                                    <div class="alert alert-danger" style="text-align:left;">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                        <p>{$haserrors}</p>
                                    </div>
                                {/if}
                                <div class="form-group text-center">
                                    <h3 class="form-headline">Register</h3>
                                </div>

                                <p style="color: red;margin-bottom: -10px;margin-top: 10px;" id="error"></p>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <input type="text" id="username" style="margin-top: 20px;" name="name" required="" title="Enter your Name" placeholder="Enter your Name" class="inputFild">
                                </div>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <input type="email" id="email" required="" name="email" title="Enter your valid email" placeholder="Enter your valid email" class="inputFild">
                                </div>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <input type="text" id="mobile" name="mobile" required="" minlength="10" maxlength="10" title="Enter your valid mobile number" placeholder="Enter 10 Digit Registered Mobile Number" class="inputFild">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="password" name="password" required="" placeholder="Password" class="inputFild">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2" style="text-align: right;"></div>
                                            <div class="col-md-4" style="text-align: left;">
                                                <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="formBtn hvr-bounce-to-right">Continue</button>
                                    <br>
                                    <br>
                                    <a href="/m/{$url}/login" style="cursor:pointer;color:blue;">Login ?</a>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>



    </div>



</div>	


</div>
</div>
</div>

</div>	
<!-- END PAGE CONTENT-->
</div>
</div>