


        <div id="svgContainer"></div>
        <div class="container">
            <div class="row d-flex justify-content-center" style="align-items: center;margin-top:15px">
                <div class='col-md-2'>
                    <div class="row" id="div_cust" style="float: right;">
                        <div>
                            <div class="card data_card shadow  bg-white"
                                style="border: 2px solid #2B5778;padding-top: 16px;">
                                <div id="cust_connecter">
                                    <h6 class="fonts" style="color: #2B5778;">Customer</h6>
                                    <span id="changeText" class="fonts" style="font-weight:400;"><img
                                            src="{!! asset('images/data-flow/customers.svg?id=v1')!!}"
                                            class="color_1">Customer data</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="div_vendor" style="float: left;">
                        <div>
                            <div class="card data_card shadow  bg-white"
                                style="border: 2px solid #3781AD;padding-top: 16px;">
                                <div id="vendor_connecter">
                                    <h6 class="fonts" style="color: #3781AD;">Vendors</h6>
                                    <span id="changeText1" class="fonts" style="font-weight:400;"><img
                                            src="{!! asset('images/data-flow/vendor.svg?id=v1')!!}"
                                            class="color_1">Vendor data </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="div_fra" style="float: left;">
                        <div>
                            <div class="card data_card shadow bg-white"
                                style="border: 2px solid #14ACBC;padding-top: 16px;">
                                <div id="fra_connector" >
                                    <h6 class="fonts" style="color: #14ACBC;margin-bottom:5px">Franchise</h6>
                                  <div style="margin-top:13px;line-height: 5px !important;" >
                                    <span id="changeText2"  style="font-weight:400;" class="fonts"><img
                                            alt="Simple bulk invoice software"
                                            src="{!! asset('images/data-flow/franchise.svg?id=v1')!!}"
                                            class="color_3">Manage franchise
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="div_seller" style="float: right;">
                        <div>
                            <div class="card data_card shadow  bg-white"
                                style="border: 2px solid #66c1e6;padding-top: 16px;">
                                <h6 class="card-title fonts" style="color: #66c1e6;margin-top:-4px;">Online sellers</h6>
                                <div class="row" style="margin-top:0px;">
                                    <div class="col-md-12"> </div>
                                    <div style="margin-left:-10px;" id="div3"> </div>
                                </div>
                                <span id="changeText3"><img alt="Simple bulk invoice software" class="img"
                                        src="{!! asset('images/data-flow/woocommerce-logo.png?id=v1')!!}"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='col-md-4 ' style="margin-left:65px;margin-right:28px;">
                    <div class='row' id="main_div">
                        <div id="main_div1">
                        </div>
                        <div class="card shadow  p-3 mb-5 bg-white rounded"
                            style="border-radius: 8px !important;text-align: center;width: 90%;">
                            <div style="margin-top:-10px;" id="_m1"></div>
                            <div style="margin-top:28px;" id="_m2"></div>
                            <div style="margin-top:8px;" id="_m3"></div>
                            <div>
                                <div id="main_div2"> </div>
                                <img alt="Simple bulk invoice software" id="main_img"
                                    src="{!! asset('images/data-flow/swipez_logo-small.png?id=v1')!!}"
                                    style="width: 160px;margin-bottom: 20px; margin-top: 0px;">
                                <div id="main_div3"> </div>
                                <div id="main_div31" style="margin-top: 8px;"> </div>
                                <div id="main_div32" style="margin-top: 16px;"> </div>
                                <div id="main_div33" style="margin-top: 24px;"> </div>
                                <button type="button" id="mainbt" class="btn btn-info fonts btclass shadow"
                                    style="margin-top: -5px; pointer-events: none;"><span id="changeTextbt"><b>Billing
                                            software</b></span></button>
                                <div id="main_for_left_last" style="text-align-last: left;">
                                    <p id="_m71"></p>
                                </div>
                            </div>


                            <div class='row d-flex justify-content-center' id="buttonsdiv" style="text-align: center;margin-top: 26px;
                            margin-bottom: -15px;">
                                <div class='col-md-12'>
                                    <button type="button" style="margin-left: 15px;" onclick="prev();" id="bt_prev" class="btn bt fonts btpre"><img
                                            src="{!! asset('images/data-flow/previous.svg?id=v2') !!}" class="color_white"></button>
                                    <button type="button" onclick="pause();" id="bt_pause" class="btn bt fonts btnext"><img
                                            src="{!! asset('images/data-flow/pause.svg?id=v2') !!}" class="color_white"></button>
                                    <button type="button" onclick="play();" id="bt_play" style="display:none;"
                                        class="btn bt fonts btnext"><img src="{!! asset('images/data-flow/play.svg?id=v2') !!}"
                                            class="color_white"></button>
                                    <button type="button" onclick="next();" id="bt_next" class="btn bt fonts btnext"><img
                                            src="{!! asset('images/data-flow/next.svg?id=v2') !!}" class="color_white"></button>


                                        </div>

                            </div>
                            <div id="main_div4" style="text-align-last: end;margin-top: -20px;">
                                <p id="_m7"></p>
                                <p id="_m8" ></p>
                            </div>
                            <div id="_m9" style=" margin-top: 7px;"></div>

                        </div>
                    </div>
                    {{-- <div class='row' style="padding-left: 7px;padding-right: 7px;width: 100%;margin-top: -35px;">
                        <div class='col-md-6'>
                            <div class="card shadow bg-white rounded"
                                style="text-align: center;padding: 5px;height: 30px;font-size: 12px;">
                                <p>Finance team</p>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="card shadow bg-white rounded"
                                style="text-align: center;padding: 5px;height: 30px;font-size: 12px;">
                                <p>Operation team</p>
                            </div>
                        </div>
                    </div> --}}
                    <!--preview/next button -->
                </div>
                <div class='col-md-2'>
                    <div class="row" id='div_right_1' style="float: left;">
                        <div id='div_right_1_sub' style="margin-top:-33px;"> </div>
                        <div id='div_right_1_sub1' style="margin-top:-18px;"> </div>
                        <div class="card data_card shadow bg-white"
                            style="border: 1px solid #f0f0f1;padding-top: 14px;">
                            <div>
                                <h6 class=" fonts">Online payments</h6>
                                <span id="right_img1"><img alt="Simple bulk invoice software"
                                        src="{!! asset('images/data-flow/razorpay-logo.svg?id=v1')!!}"
                                        class="img"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row" id='div_right_2' style="float: right;">
                        <div id='div_right_2_sub' style="margin-top:-33px;"> </div>
                        <div id='div_right_2_sub1' style="margin-top:-17px;"> </div>
                        <div class="card data_card shadow  bg-white"
                            style="border: 1px solid #f0f0f1;padding-top: 14px;">
                            <h6 class=" fonts">Payout</h6>
                            <span id="right_img2"><img alt="Simple bulk invoice software"
                                    src="{!! asset('images/data-flow/icicibank-logo.png?id=v1')!!}" class="img"></span>
                        </div>
                    </div>
                    <div class="row" id='div_right_3' style="float: right;">
                        <div>
                            <div class="card data_card shadow bg-white"
                                style="border: 1px solid #f0f0f1;padding-top: 14px;">
                                <div id='div_right_3_sub1' style="margin-top:17px;"></div>
                                <div id='div_right_3_sub2' style="margin-top:8px;"></div>
                                <div id='div_right_3_sub3' style="margin-top:8px;"></div>
                                <div id='div_right_3_sub4' style="margin-top:8px;"></div>
                                <div style="margin-top:-40px;">
                                    <h6 class="fonts"> Accounting </h6>
                                    <span id="right_img3" style="margin-top:11px;"><img
                                            alt="Simple bulk invoice software"
                                            src="{!! asset('images/data-flow/tally-logo.svg?id=v1')!!}"
                                            class="img"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id='div_right_4' style="float: left;">
                        <div>
                            <div class="card data_card shadow bg-white"
                                style="border: 1px solid #f0f0f1;padding-top: 8px;">
                                <div id='div_right_4_sub' style="margin-top:25px;"></div>
                                <div id='div_right_4_sub3' class="gstf"></div>
                                <div id='div_right_4_sub1' class="gstf"></div>
                                <div style="margin-top:-34px;">
                                    <h6 class=" fonts">GST filing</h6>
                                    <span style="margin-top:4px;" id="right_img4"><img
                                            alt="Simple bulk invoice software"
                                            src="{!! asset('images/data-flow/gst_portal.jpg?id=v1')!!}"
                                            class="img1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
