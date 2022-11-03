                                

<div class="row">   
    {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
    <div class="col-md-12">
        
        <h3 class="page-title" style="margin-left: 0px !important;">Book your package<a href="/m/{$url}/memberlogout"  class="pull-right">Logout</a></h3>
        
        <!-- BEGIN PAYMENT TRANSACTION TABLE -->
        {$count=1}
        {$id=0}
        {foreach from=$requestlist item=p key=s}

            <div class="panel-group accordion" id="accordion{$count}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion{$count}" href="#collapse_{$count}">
                                <b>{$s}</b> </a>
                        </h4>
                    </div>
                    <div id="collapse_{$count}" class="panel-collapse {if $count==1}in{else}collapse{/if}">
                        <div class="panel-body">
                            <div class="portlet-body">

                                {if $s=='Hall / conference rooms'}
                                    {$hall=1}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4><b>TIMINGS :</b> </h4>
                                            <p><b>Morning :</b>  (6 hours)    9.30 hrs. to 3:30 hrs. </p>
                                            <p>F&B Service will stop at  :     3.00 hours   </p>
                                            <p>Music must stop at  :     2.45 hours Music must stop at : 23.45 hours Lights Off </p>
                                            <p>All guests must leave by   :     3.45 hours </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>&nbsp; </h4>
                                            <p><b>Evening :</b>   18.30 hrs. to 00:30 hrs.  </p>
                                            <p>F&B Service will stop at   : 24.00 hours   </p>
                                            <p>Music must stop at         : 23.45 Lights Off hours : 00.30 hour	 </p>
                                            <p>All guests must leave by  : 00.45 hours</p>
                                        </div>
                                    </div>
                                {/if}


                                <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                                        <thead>
                                            <tr>

                                                <th class="td-c">
                                                    Package
                                                </th>
                                                <th class="td-c">
                                                    Type
                                                </th>
                                                {if $hall!=1}
                                                    <th class="td-c">
                                                        Duration
                                                    </th>
                                                {/if}

                                                <th class="td-c">
                                                    Price
                                                </th>
                                                <th class="td-c">
                                                    View
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <form action="" method="">
                                            {$int=0}
                                            {foreach from=$requestlist.$s item=v key=k}
                                                {$cat='abcd'}
                                                {foreach from=$requestlist.$s.$k item=vp}
                                                    <tr>
                                                        {if {$cat!=$k}}
                                                            {if $int==1}
                                                                <td colspan="6"></td>
                                                            </tr><tr>
                                                            {/if}
                                                            {$cat=$k}
                                                            <td class="td-c " style="vertical-align: middle;background-color: #0da3e2;color: #ffffff;" rowspan="{$requestlist.$s.$k|@count}">
                                                                <h4><b>{$k}</b></h4>
                                                            </td>
                                                        {/if}
                                                        <td class="td-c">
                                                            {$vp.speed}
                                                        </td>
                                                        {if $hall!=1}
                                                            <td class="td-c" style="text-align: -webkit-center;">
                                                                {if $vp.duration|@count >1}
                                                                    <select name="selecttemplate" style="width: 100px;" onchange="getplanDetails('{$merchant_id}{$vp.plan_id}', this.value,{$id}, '{$url}');" required class="form-control input-sm" data-placeholder="Select...">
                                                                        {foreach from=$vp.duration item=vd}
                                                                            <option value="{$vd.duration}">{$vd.duration} Month</option>
                                                                        {/foreach}
                                                                    </select>
                                                                {else}
                                                                    {$vp.duration.0.duration} Month
                                                                {/if}
                                                            </td>
                                                        {/if}
                                                        <td class="td-c">
                                                            <span id="price_{$id}"> {$vp.price}</span>
                                                        </td>
                                                        <td class="td-c"> 
                                                            {if $hall!=1}
                                                                <input type="hidden" id="planid_{$id}" value="{$vp.plan_link}">
                                                                <a  href="/m/{$url}/confirmpackage/{$vp.plan_link}"  id="link_{$id}" class="btn btn-xs blue"><i class="fa fa-check"></i> Select package </a>
                                                            {else}
                                                                <a data-toggle="modal" href="#booking_request" onclick="document.getElementById('bookingplan_id').value = '{$vp.plan_link}';" class="btn btn-xs blue"><i class="fa fa-check"></i> Request to book facility </a>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                    {$id=$id+1}
                                                    {$int=1}
                                                {/foreach}
                                            {/foreach}
                                        </form>
                                        </tbody>
                                    </table>
                                </div>
                                {if $hall==1}
                                    An additional  amount of Rs. 1500/- will be charged for extra  power utilized for Video     Shooting, Series Light power can series light etc.
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {$count=$count+1}
        {/foreach}
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
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <form class="form-horizontal" id="guestlogin" action="/m/{$url}/bookingrequest" method="post" role="form">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-md-4 control-label">Booked for date</label>
                                        <div class="col-md-7">
                                            <input type="text" required="" name="booking_date" class="form-control date-picker"  autocomplete="off" data-date-format="dd M yyyy" >
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
            $('#gpay').attr('href', '/m/{$url}/confirmpackage/' + link);
            $('#guestjoin').attr('href', '/patron/register/index/' + link + '!plan');

            var price = document.getElementById('price_' + id).innerHTML;
            document.getElementById('booking_amount').innerHTML = '<i class="fa fa-inr fa-large"></i>' + price;

        }
</script>