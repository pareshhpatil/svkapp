
<script>
    var eventpage = 1;
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';

</script>
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
                    <div class="alert alert-danger max900">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <strong>Invalid event!</strong>
                        <div class="media">
                            <p class="media-heading">{$invalid_message}</p>
                        </div>
                    </div>
                {/if}
                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable" style="max-width: 900px;text-align: left;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}

                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- BEGIN PAGE CONTENT-->
                <div  style="text-align: left;box-shadow: 1px 10px 10px #888888;">
                    <form id="event_form" action="/patron/event/pay/{$url}" method="post" onsubmit="return validateform();">
                        <div class="row">
                           {if $info.banner_path!=''}
                                <div class="col-md-12 fileinput fileinput-new" style="text-align: center;" data-provides="fileinput">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail banner-container"  data-trigger="fileinput"> 
                                            <img class="img-responsive" style="width: 100%;" src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}">
                                        </div>
                                    </div>
                                </div>

                            {/if}
                        </div>
                        <div class="row">
                            <div class="col-md-12 no-padding fileinput fileinput-new " style="width: 100%;" data-provides="fileinput">
                                <div class="fileinput-new thumbnail col-md-12 title">
                                    <div class="caption font-blue" style="background-color: #2a94a5; margin-left: 10px;margin-right: 10px;">
                                        <span class="caption-subject bold" style="color: #FFFFFF;" > <h4>{$info.event_name}</h4></span>
                                        <p style="color: #FFFFFF;">Presented by <a href="{$merchant_page}" style="color: #FFFFFF;" target="BLANK">{$info.company_name}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row no-padding no-margin" style="
                             background-color: black;
                             font-size: 19px;
                             letter-spacing: 1.4px;">
                            <div class="col-md-5 ileinput-new" style="
                                 background-color: black;
                                 display: inline-block;
                                 padding: 10px 10px 10px 20px;
                                 color: #16b9c6;">
                                <span id="totalslot">{$chapter_count}</span> Chapter Selected
                            </div>
                            <div class="col-md-5 ileinput-new" style="
                                 background-color: black;
                                 display: inline-block;
                                 padding: 10px 10px 10px 20px;
                                 color: #ffffff;">
                                Total Price: <span id="absolute_costt"><i class="fa fa-inr fa-large"></i> {$chapter_amount|string_format:"%.2f"}/-</span>
                            </div>
                            <div class="col-md-2" style="padding: 10px 10px 10px 20px;">
                                <input type="submit" onclick="$('#table-no-export-gloss').dataTable().fnFilter('');" class="btn blue" value="Buy Now">
                            </div>
                        </div>

                        <div class="row no-margin no-padding " >


                            <div class="col-md-5 invoice-payment" >
                                <table class="table table-striped table-hover" id="table-no-export-gloss">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Buy
                                            </th>
                                            <th class="td-c">
                                                Chapter
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        {$int =1}
                                        {$total =0}
                                        {foreach from=$package item=v}
                                            {if in_array($v.package_id,$chapter_history)}
                                                {$check='1'}
                                            {else}
                                                {$check='0'}
                                            {/if}
                                            <tr style="cursor: pointer;">
                                                <td class="td-c">
                                                    <label class="btn btn-sm  {if $check=='1'}red{else}blue{/if}" id="slotbtn{$v.package_id}">

                                                        <span id="slotbtntext{$v.package_id}">{if $check=='1'}<i class="fa fa-remove"></i>{else}Buy{/if}
                                                        </span> 
                                                        <span style="display: none;">
                                                            <input name="booking_slots[]" id="slotchk{$v.package_id}" onchange="calculateSlot('{$v.package_id}');" {if $check=='1'}checked{/if} title="{$v.price}" value="{$v.package_id}" class="checker"  type="checkbox"></span>

                                                    </label>

                                                </td>
                                                <td class="td-c" style="cursor: pointer;" onclick="displaydesc('{$v.package_id}');">
                                                    {$v.package_name}
                                                    <br>
                                                    <span style="color: #9b9b9b; font-size: 12px;letter-spacing: 0.9px;">Price : <i class="fa fa-inr"></i> {$v.price}</span>
                                                    <span style="display: none;" id="desc{$v.package_id}" >{$v.package_description}</span>
                                                    <input type="hidden" id="pric{$v.package_id}" value="{$v.price}">
                                                    <input type="hidden" id="name{$v.package_id}" value="{$v.package_name}">
                                                    <input type="hidden" name="package_id[]" value="{$v.package_id}">
                                                    <input type="hidden" name="occurence_id[]" value="{$v.occurence_id}">
                                                    <input type="hidden" name="package_qty[]" id="slotid{$v.package_id}" value="{if $check=='1'}1{else}0{/if}">
                                                </td>
                                            </tr>
                                            {$total = $total + $v.price}
                                            {$int = $int + 1}
                                        {/foreach}


                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-7">
                                <div class="row no-margin" style="margin-top: 40px;font-size: 19px;
                                     letter-spacing: 1.4px;border-bottom: 1px solid #bbbdbf;
                                     color: #727272;">
                                    <div class="col-md-8 no-margin no-padding" style="text-align: left;font-size: 15px;    font-weight: bold;" >
                                        <span id="pkgname"></span>
                                    </div>
                                    <div class="col-md-4 no-margin no-padding pull-right" style="text-align: right; color: #16b9c6;" >
                                        <i class="fa fa-inr fa-large"></i> <span id="pkgprice">0.00</span>
                                        <br>
                                        <span id="btnpc"></span>

                                    </div>
                                    <div></div>
                                </div>
                                <div class="row no-margin">
                                    <div class="col-md-12" id="chapterdesc"></div>
                                </div>
                            </div>
                            <input type="hidden" title="0"  name="booking_slots[]"  value="0">
                            <input type="hidden" required="" id="amount" name="grand_total" value="{$chapter_amount}" >
                            <input type="hidden" required="" id="amount2" name="total" value="{$chapter_amount}" >
                            <input value="0.00" name="service_tax" type="hidden">
                            <input value="0.00" name="coupon_discount" type="hidden">
                            <input value="0.00" name="tax" type="hidden">
                            <input value="{$occurence.0.occurence_id}" name="start_date" type="hidden">
                        </div>
                    </form>
                </div>
                {if isset($promotion)}
                    <div class="row no-margin" style="margin-top: 5px;">
                        <div class="col-md-12 no-margin no-padding" style="text-align: left;" >
                            <div class="alert alert-success" style="margin-bottom: 0px;">
                                <p>
                                    {$promotion} 
                                </p>
                            </div>
                        </div>
                    </div>
                {/if}
                <div class="row no-margin" style="margin-top: 5px;">
                    <div class="col-md-12 no-margin no-padding" style="text-align: left;">
                        <div class="alert alert-success">
                            <p>
                                <b>If you would like to collect online payments for your business, <a target="_BLANK" href="/merchant/register">register now</a> on Swipez.</b>
                            </p>
                        </div>
                    </div>
                </div>
                <p> <a target="_BLANK" href="https://www.swipez.in"><img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></a></p>

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

                    </div>
                    <div class="portlet-body">
                        <div class="row">

                            <div class="col-md-6">
                                <h4>Book as guest</h4>
                                <form class="form-horizontal" id="guestlogin" action="/patron/event/pay/{$url}" method="post" role="form">
                                    {if $is_flexible==0}
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-md-3 control-label">Grand total:</label>
                                            <div class="col-md-7">
                                                <b><h3 id="booking_amount"><i class="fa fa-inr fa-large"></i> {$info.price} /-</h3></b>
                                            </div>
                                        </div>
                                    {/if}
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-3 control-label"></label>
                                        <div class="col-md-7">
                                            <button type="button" class="btn blue" onclick="document.getElementById('event_form').submit();" id="onlinepay" >Book as guest</button>

                                        </div>
                                    </div>
                                </form>		
                            </div>
                            <div class="col-md-6">
                                <h4>Login with Swipez</h4>
                                <form class="form-horizontal" id="guestlogin" action="/login/failed/{$url}|eonl" method="post" role="form">
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
<a  href="#guestpay"  data-toggle="modal" id="guest"></a>                         

<script>

    function seatcalculate()
    {

    {if $package.0.is_flexible==0}
            booking_amount_total = document.getElementById('grand_total').value;
            totalcostamt = document.getElementById('totalcostamt').value;
            if (totalcostamt > 0)
                {
                            document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i>  ' + booking_amount_total + ' /-';
                            document.getElementById('guest').click();
                        }
                        else
                            {
                                        alert('Select Quantity');
                                        return false;
                                    }
    {else}
                                    document.getElementById('guest').click();
                                    document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i> {$package.0.min_price} - {$package.0.max_price} ';
    {/if}

                                }
                                
                                {$p_id=0}
        {foreach from=$chapter_history item=v}
            {if $v>0}
                {$p_id=$v}
                                selectCheckbox('{$v}');
            {/if}
        {/foreach}
                    {if $pg_id==0}           
                                displaydesc('{$package.0.package_id}');
    {/if}
</script>