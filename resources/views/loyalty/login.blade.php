@extends('loyalty.layout')
@section('content')
<section class="">
    <div class="container">
        <div class="colTable" style="margin-top: 20px;">
            <div class="colCell">
                <div class="login_div">
                    <form method="post" id="frmlogin" action="#" onsubmit="return logincheck();">
                        <div class="form-group text-center">
                            <h3 class="form-headline">Login</h3>
                            <p>To manage your loyalty account, login through your <br><b>Registered Mobile number</b></p>
                        </div>
                        <div class="form-group text-center" style="width:100%;">
                            <div class="loginImg">
                                <img src="https://www.swipez.in/assets/cable/images/mobileVector.svg">
                                <br>
                            <div class="form-group" style="width:100%;margin-bottom: -20px;margin-top: 20px;">
                                <p style="color: red;" id="error"></p>
                            </div>
                            </div>
                            
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <input type="text" id="username" style="margin-top: 20px;" name="mobile" required minlength="10" maxlength="10" title="Enter your valid mobile number" maxlength="12" pattern="^(\+[\d]{1,5}|0)?[1-9]\d{9}$" placeholder="Enter 10 Digit Registered Mobile Number" class="inputFild">
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" name="password" required placeholder="Password" class="inputFild">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="merchant_id" value="{{$merchant_id}}">
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
</center>
</section>
@endsection