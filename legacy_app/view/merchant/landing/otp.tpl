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
        {if isset($haserrors)}
            <div class="alert alert-danger" style="text-align:left;">
                <button type="button" class="close" data-dismiss="alert"></button>
                <p>{$haserrors}</p>
            </div>
        {/if}
        <section class="loginPage">
            <div class="container">
                <div class="colTable vh100">
                    <div class="colCell">
                        <div class="login_div">
                            <form method="post" id="frmotp" action="#" onsubmit="return submitOTP();">
                                <div class="form-group text-center">
                                    <h3 class="form-headline">Enter OTP</h3>
                                </div>
                                <p style="color: red;margin-bottom: -10px;margin-top: 10px;" id="error"></p>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <input type="number" autocomplete="false" maxlength="4" max="9999" id="otp" style="margin-top: 20px;" name="otp" required="" title="Enter OTP" placeholder="Enter OTP" class="inputFild">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="mobile" id="mobile" value="{$otp_details.mobile}">
                                    <input type="hidden" name="token" id="token" value="{$otp_details.token}">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="formBtn hvr-bounce-to-right">Submit</button>
                                    <br><br>
                                    <p id="otp-msg"></p>
                                    <a id="resendotp" style="cursor:pointer;color:blue;" onclick="resendOTP();">Resend OTP?</a>
                                    <br>
                                </div>
                            </form>
                        </div>
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

<script>
    URL = '{$post_url}';
    short_url = '{$url}';
</script>