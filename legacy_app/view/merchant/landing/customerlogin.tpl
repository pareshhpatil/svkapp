                                

<div class="row">            
    <div class="col-md-12">
        <div class="row">            
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="portlet ">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                {if isset($haserrors)}
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                        <strong>Error!</strong>
                                        <div class="media">
                                            <p class="media-heading">{$haserrors}</p>
                                        </div>

                                    </div>
                                {/if}
                                <form class="form-horizontal" action="/m/{$url}/logincheck" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-4 control-label">Membership number</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" required name="customer_code" id="inputEmail1" placeholder="Membership number">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-8">
                                            <div class="g-recaptcha" required data-sitekey="{$capcha_key}"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-4 col-md-5">
                                            <button type="submit" class="btn blue">Sign in</button>
                                        </div>
                                    </div>
                                </form>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAYMENT TRANSACTION TABLE -->
        <hr/>
        <p>&copy; {$current_year} OPUS Net Pvt. Handmade in Pune. <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>
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
<div class="modal fade bs-modal-lg" id="guestpay" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Guest payment
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">

                            <div class="col-md-6">
                                <h4>Pay as guest</h4>
                                <form class="form-horizontal"  action="/m/{$url}/pay/" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-3 control-label">Grand total:</label>
                                        <div class="col-md-7">
                                            <b><h4 id="booking_amount"><i class="fa fa-inr fa-large"></i> {$info.price} /-</h4></b>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-3 control-label"></label>
                                        <div class="col-md-7">
                                            <a class="btn blue" id="gpay" href="/m/{$url}/pay/" >Pay as guest</a>

                                        </div>
                                    </div>
                                </form>		
                            </div>
                            <div class="col-md-6">
                                <h4>Login with Swipez</h4>
                                <form class="form-horizontal" id="guestlogin" action="/login/failed/{$url}|ponl" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-4 control-label">Email</label>
                                        <div class="col-md-7">
                                            <input type="email" name="username" class="form-control input-sm" id="inputEmail1" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Password</label>
                                        <div class="col-md-7">
                                            <input type="password" name="password" AUTOCOMPLETE='OFF' class="form-control input-sm" id="inputPassword12" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <label for="inputPassword12" class="col-md-4 control-label"></label>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn blue">Sign in</button>
                                        </div>
                                    </div>
                                </form>		
                            </div>
                        </div>
                        <hr>
                        <h4>Benefits of swipez login</h4>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="col-md-2"> <img src="/assets/admin/layout/img/single.jpg"></div><div class="col-md-10"><label>
                                        Single window for paying 
                                        multiple merchants
                                    </label></div>

                                <div class="col-md-2"> <img src="/assets/admin/layout/img/instant.jpg"></div><div class="col-md-10"><label>
                                        Instant alerts, due date 
                                        reminders across merchants
                                    </label></div>
                            </div>
                            <div class="col-md-5">
                                <div class="col-md-2"> <img src="/assets/admin/layout/img/access.jpg"></div><div class="col-md-10"><label>
                                        Access your payments across 
                                        devices from the cloud
                                    </label></div>

                                <div class="col-md-2"> <img src="/assets/admin/layout/img/reward.jpg"></div><div class="col-md-10"><label>
                                        Reward programs and much
                                        more coming your way
                                    </label></div>
                            </div>
                            <div class="col-md-2">
                                <br><br>
                                <div class="form-group">
                                    <a href="/patron/register/index/{$url}!plan" id="guestjoin" class="btn blue">Join now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>

<div class="modal fade bs-modal-lg" id="booking_request" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            Request to book facility
                        </div>

                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <form class="form-horizontal" id="guestlogin" action="/m/{$url}/bookingrequest" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-4 control-label">Booked for date</label>
                                        <div class="col-md-7">
                                            <input type="text" name="booking_date" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Slot (Morn/Eve)</label>
                                        <div class="col-md-7">
                                            <select name="slot" required class="form-control" data-placeholder="Select...">
                                                <option value="Morning">Morning</option>
                                                <option value="Evening">Evening</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <label for="inputPassword12" class="col-md-4 control-label"></label>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn blue">Book</button>
                                            <input type="hidden" name="plan_id" id="bookingplan_id">
                                        </div>
                                    </div>
                                </form>		
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.modal-content -->
</div>

<a  href="#guestpay"  data-toggle="modal" id="guest"></a>   



<script>

    function planselect(id)
    {

            var link = document.getElementById('planid_' + id).value;
            var postlink = '/login/failed/' + link + '|ponl';
            $('#guestlogin').attr('action', postlink);
            $('#gpay').attr('href', '/m/{$url}/confirmplan/' + link);
            $('#guestjoin').attr('href', '/patron/register/index/' + link + '!plan');

            var price = document.getElementById('price_' + id).innerHTML;
            document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i>' + price;

        }
</script>