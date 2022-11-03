<section class="loginPage">
    <div class="container">
        <div class="colTable vh100">
            <div class="colCell">
                <div class="login_div">
                    <form method="post" action="/cable/login">
                        <div class="form-group text-center">
                            <h3 class="form-headline">Login</h3>
                            <p>To manage your cable account, login through your <br><b>Registered Mobile number</b></p>
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