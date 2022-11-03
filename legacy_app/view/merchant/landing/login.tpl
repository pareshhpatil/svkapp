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
                            <form method="post" action="">
                                <div class="form-group text-center">
                                    <h3 class="form-headline">Login</h3>
                                    <p>To manage your account, login through your <br><b>Registered Mobile number</b></p>
                                </div>
                                <div class="form-group text-center">
                                    <div class="loginImg">
                                        <img src="/assets/cable/images/mobileVector.svg">
                                    </div>
                                </div>
                                {if isset($error)}
                                    <div class="alert alert-danger">
                                        {$error}
                                    </div> 
                                {/if}
                                <div class="form-group">
                                    <input type="text" name="mobile" required minlength="10" maxlength="10" {$validate.mobile} placeholder="Enter 10 Digit Registered Mobile Number" class="inputFild">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password"  required placeholder="Password" class="inputFild">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="formBtn hvr-bounce-to-right">Continue</button>
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
</form>
</div>

</div>	
<!-- END PAGE CONTENT-->
</div>
</div>