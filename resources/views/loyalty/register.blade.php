@extends('loyalty.layout')
@section('content')
<section class="about-us login" id="s2" style="background-color: #d0d4d8;">
            <center>
                
                <section class="">
                    <div class="container">
                        <div class="colTable" style="margin-top: 20px;">
                            <div class="colCell">
                                <div class="login_div">
                                    <form method="post" id="frmlogin" action="#" onsubmit="return register();">
                                        <div class="form-group text-center">
                                            <h3 class="form-headline">Register</h3>
                                        </div>
                                        
                                        <p style="color: red;margin-bottom: -10px;margin-top: 10px;" id="error"></p>
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <input type="text" id="username" style="margin-top: 20px;" name="name" required  title="Enter your Name" placeholder="Enter your Name" class="inputFild">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <input type="email" id="email"  name="email" title="Enter your valid email (Optional)"  placeholder="Enter your valid email (Optional)" class="inputFild">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <input type="text" id="mobile"  name="mobile" required minlength="10" maxlength="10" title="Enter your valid mobile number" maxlength="12" pattern="^(\+[\d]{1,5}|0)?[1-9]\d{9}$" placeholder="Enter 10 Digit Registered Mobile Number" class="inputFild">
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