<section class="loginPage">
    <div class="container">
        <div class="colTable vh100">
            <div class="colCell">
                <div class="login_div">
                    <form method="post" action="/cable/reset">
                        <div class="form-group text-center">
                            <h3 class="form-headline">Reset Password</h3>
                        </div>
                        {if isset($error)}
                            <div class="alert alert-danger">
                                {$error}
                            </div> 
                        {/if}
                        
                        <div class="form-group">
                            <input type="password" name="exist_password"  required placeholder="Current Password" class="inputFild">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password"  required placeholder="Password" class="inputFild">
                        </div>
                        <div class="form-group">
                            <input type="password" name="re_password"  required placeholder="Confirm Password" class="inputFild">
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