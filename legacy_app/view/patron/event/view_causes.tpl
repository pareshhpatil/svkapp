
<div class="page-content">
    <div class="row no-margin">
        {if {$isGuest} =='1'}
            <div class="col-md-2"></div>
            <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;">
            {else}
                <div class="col-md-1"></div>
                <div class="col-md-10" style="text-align: -webkit-center;text-align: -moz-center;">
                {/if}
                <br>
                {if $is_valid=='NO' && !isset($is_invoice)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Invalid event!</strong>
                        <div class="media">
                            <p class="media-heading">{$invalid_message}</p>
                        </div>
                    </div>
                {/if}
                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div class="max900" style="text-align: left;">
                    {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable" >
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                    <div class="row">
                        <div class="col-md-12 fileinput fileinput-new banner-main max900" data-provides="fileinput">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail banner-container" style="min-width: 600px;width: 100%;" data-trigger="fileinput"> 
                                    <img class="img-responsive" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}"></div>
                            </div>

                        </div>
                        <div class="row no-margin" style="position: absolute;">
                            {if $info.image_path!=''}<div class="col-md-12 no-padding fileinput fileinput-new logo-main" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" >
                                        <img class="img-responsive templatelogo" src="/uploads/images/logos/{$info.image_path}" alt=""/>
                                    </div>
                                </div>{/if}

                            </div>
                            <div class="col-md-12 no-padding fileinput fileinput-new eventtitle max900" data-provides="fileinput">
                                <div class="fileinput-new thumbnail col-md-11 title">
                                    <div class="caption font-blue">
                                        <a href="{$merchant_page}" target="BLANK"><span class="caption-subject bold"> <h2>{$info.event_name}</h2></span></a>
                                        <p>Presented by : {$info.company_name}</p>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row" style="margin-top: 330px;">
                            <h3></h3><br><br>
                            <div class="col-md-1"></div>
                            <div class="col-md-10 invoice-payment">
                                <form action="/patron/event/pay/{$url}" method="post">


                                    {if {$info.event_from_date}!={$info.event_to_date}}
                                        <div class="row ">
                                            <div class="col-md-3"><strong>Date</strong></div>
                                            <div class="col-md-9 "><i class="fa fa-calendar"></i>&nbsp;&nbsp;  {{$info.event_from_date}|date_format:"%A, %B %e, %Y"} to {{$info.event_to_date}|date_format:"%A, %B %e, %Y"}</div>
                                        </div>
                                    {else}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Date</strong></div>
                                            <div class="col-md-9"><i class="fa fa-calendar"></i>&nbsp;&nbsp; {{$info.event_from_date}|date_format:"%A, %B %e, %Y"}</div>
                                        </div>
                                    {/if}
                                    {if $info.is_flexible==1}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Min amount</strong></div>
                                            <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.min_price}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3"><strong>Max amount</strong></div>
                                            <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.max_price}</div>
                                        </div>
                                    {else}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Seat price</strong></div>
                                            <div class="col-md-9"><i class="fa fa-inr fa-large"></i> &nbsp;&nbsp;{$info.price}</div>
                                            <input type="hidden" id="price" value="{$info.price}"/>
                                        </div>
                                    {/if}


                                    {if $info.description!=''}
                                        <div class="row">
                                            <div class="col-md-3"><strong>Description</strong></div>
                                            <div class="col-xs-9">{$info.description}</div>
                                        </div>
                                    {/if}



                                    {foreach from=$header item=v}
                                        {if $v.value!='' && $v.column_position!=1 &&  $v.column_position!=2}
                                            <div class="row">
                                                <div class="col-md-3"><strong>{$v.column_name}:</strong></div>
                                                <div class="col-md-9">{$v.value}</div>
                                            </div>
                                        {/if}
                                    {/foreach}

                                    {if !isset($is_invoice)} <div class="row">
                                            <br>
                                            <div class="col-xs-3"></div>
                                            <div class="col-xs-9">{if {$merchant_type} != '1' &&  {$is_valid}=='YES'}
                                                {if $isGuest=='1'} 
                                                    <a data-toggle="modal" href="#guestpay" class="btn blue hidden-print margin-bottom-5">
                                                        Pay now 
                                                    </a>{else}
                                                    <input type="hidden" name="seat"  value="1">
                                                    <input type="submit" class="btn blue hidden-print margin-bottom-5" value="Book now">
                                                {/if}
                                            {else}
                                                <!-- <p class="btn btn-xs red">Event unavailable</p>-->
                                            {/if}
                                            <br>
                                            <br>
                                        </div>

                                    </div>
                                {/if}
                            </form>
                        </div>


                    </div>

                </div>
                <!-- END PAGE CONTENT-->
            </div>
            <div class="col-md-1"></div>
        </div>
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
                        <div class="tools">
                            <a href="" class="remove">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Login with swipez credential</h4>
                                <form class="form-horizontal" id="guestlogin" action="/login/failed/{$url}|eonl" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-4 control-label">Email</label>
                                        <div class="col-md-7">
                                            <input type="email" name="username" required class="form-control input-sm" id="inputEmail1" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword12" class="col-md-4 control-label">Password</label>
                                        <div class="col-md-7">
                                            <input type="password" name="password" required AUTOCOMPLETE='OFF' class="form-control input-sm" id="inputPassword12" placeholder="Password">
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
                            <div class="col-md-6">
                                <h4>Book as guest</h4>
                                <form class="form-horizontal" id="guestlogin" action="/patron/event/pay/{$url}" method="post" role="form">
                                    {if $info.is_flexible!=1}
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-3 control-label">Grand total:</label>
                                            <div class="col-md-7">
                                                <b><h4 id="booking_amount"><i class="fa fa-inr fa-large"></i> {$info.price} /-</h4></b>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-3 control-label"></label>
                                        <div class="col-md-7">
                                            <button type="submit" class="btn blue" id="onlinepay" ><i class="fa fa-check"></i> Pay as guest</button>
                                            <input type="hidden" name="seat" id="guest_seat" value="1">
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
                                    <a href="/patron/register/index/{$url}" class="btn blue">Join now</a>
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

<script>
    function seatcalculate(val)
                                                {
                                                        price = document.getElementById('price').value;
                                                        document.getElementById('guest_seat').value = val;
                                                        booking_amount = price * parseInt(val);
                                                        document.getElementById('booking_amount').innerHTML = booking_amount + ' /-';
                                                    }
</script>