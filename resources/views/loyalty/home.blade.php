
@extends('loyalty.layout')
@section('content')
<script>
    getDetails();
</script>
        <section class="about-us login" id="s2" style="background-color: #d0d4d8;">
            <center>

                <section class="">
                    <div class="container">
                        <div class="colTable" style="margin-top: 20px;">
                            <div class="colCell">
                                <div class="login_div" style="min-height: 500px;">
                                        <div class="form-group text-center">
                                            <h4 class="form-headline" id="name"></h4>

                                            <p><br></p>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <h5>Balance Points &nbsp;&nbsp;: <span id="points"></span>&nbsp;&nbsp;&nbsp;</h5>
                                            <h5>Balance Rupees :  <span id="point_rs"></span> <i class="fa fa-inr"></i></h5>
                                        </div>
                                        <div class="form-group text-center"  style="margin-bottom: 20px;text-align: center;">
                                            <div class="loginImg center" style="text-align: center;">
                                                <img style="float: none;" id="qr_image"  width="200" src="">
                                            </div>
                                        </div>


                                        <div class="form-group text-center">
                                            <!-- Sharingbutton WhatsApp -->
                                            <!-- Sharingbutton WhatsApp -->
                                            <!-- Sharingbutton WhatsApp -->
                                            <a id="whatsapp_btn" target="_self" style="background-color:#29a628;" href="whatsapp://send?text=https://h7sak8am43.swipez.in/tmp/loyalty/test.html" 
                                               class="formBtn "><i class="fa fa-whatsapp"></i><span class="jssocials-share-label"> WhatsApp</span>
                                            </a>

                                            <a target="_BLANK" id="download_btn" href="https://h7sak8am43.swipez.in/assets/admin/layout/img/logo.png" download
                                               class="formBtn "><i class="fa fa-download"></i><span class="jssocials-share-label"> Download</span>
                                            </a>


                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </center>
        </section>
        @endsection