@extends('loyalty.layout')
@section('content')
<section class="about-us login" id="s2" style="background-color: #d0d4d8;">
    <center>

        <section class="">
            <div class="container">
                <div class="colTable" style="margin-top: 20px;">
                    <div class="colCell">
                        <div class="login_div">
                            <form method="post" id="frmotp" action="#" onsubmit="return submitOTP();">
                                <div class="form-group text-center">
                                    <h4 class="form-headline">Enter OTP Sent to your mobile number</h4>
                                </div>

                                <p style="color: red;margin-bottom: -10px;margin-top: 10px;" id="error"></p>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <input type="number" autocomplete="false" maxlength="4" max="9999" id="otp" style="margin-top: 20px;" name="otp" required title="Enter OTP" placeholder="Enter OTP" class="inputFild">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="mobile" id="mobile" value="">
                                    <input type="hidden" name="token" id="token" value="">
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
    </center>
</section>
<script>
    setOTP();
</script>
@endsection