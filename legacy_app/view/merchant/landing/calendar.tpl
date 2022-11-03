
<div class="row no-margin">
    <div class="col-md-1"></div>
    <div class=" col-md-10" style="text-align: -webkit-center;text-align: -moz-center;text-align: center;">
        <div class="portlet">
            <!-- BEGIN PORTLET-->
            <div class="portlet-body ">
                <form class="form-inline"  method="post" role="form">
                    <div class="form-group">
                        <label class="help-block">Category name</label>
                        <select name="category_id" id="category_id" onchange="changeCategory({$current_date});" class="form-control" required  onchange="">
                            {foreach from=$category_list item=v}
                                <option value="{$v.category_id}"> {$v.category_name}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="help-block">Calendar</label>
                        <select name="calendar_id" id="calendar_id" class="form-control" onchange="changeCalendar({$current_date});" required  >

                        </select>
                    </div>
                    <input type="hidden" value="{$merchant_id}" id="merchant_id" >
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>
<div class="row">
    <div class=" col-md-12" id="calenderdiv" style="text-align: -webkit-center;text-align: -moz-center;text-align: center;">
        <br>
        <div id='calendar' style="max-width: 900px;margin: 0 auto;"></div>
        <br>
    </div>
    <div id='loading'>loading...</div>
    <div class="col-md-1"></div>
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
<div class="modal fade bs-modal-lg" id="bookslot" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="portlet ">
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-10">
                            <br>

                            <div class="form-body">
                                <form class="form-horizontal" onsubmit="return validateform();" name="frm_slot" action="/m/{$details.display_url}/confirmslot" method="post"  id="form_slot" >
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group" >
                                        <div class="col-md-4"></div>
                                        <div class="col-md-6">
                                            <div id="slotlist" class="checkbox-list">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" >
                                        <label for="inputPassword12" class="col-md-4 control-label"></label>
                                        <div class="col-md-5">
                                            <h3 >Grand total: <i class="fa fa-inr fa-large"></i> <span id="absolute_costt">00.00</span>/-</h3>
                                            <input  id="amount" required="" type="hidden" placeholder="0.00"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-6">
                                            <input type="hidden" id="frm_date" name="date"  >
                                            <input type="hidden" id="frm_calendar_id" name="calendar_id" >
                                            <button type="submit" class="btn blue">Confirm amount & pay as guest</button>
                                        </div>
                                    </div>
                                </form>
                                    <form class="form-horizontal" name="frm_slot" action="/m/{$details.display_url}/confirmslot" method="post"  id="submit_form" >
                                <div class="form-group">
                                    <label for="inputEmail1" class="col-md-4 control-label"></label>
                                    <div class="col-md-6">
                                        <h4>Login with swipez credential</h4>
                                    </div>
                                </div>
                                <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        Please correct the below errors to complete registration.
                                    </div>

                                    <div class="alert alert-danger" style="display: none;" id="errorshow">
                                        <p id="error_display">Please correct the below errors to complete registration.</p>
                                    </div>
                                <div class="form-group">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        Email
                                        <input type="email" name="username" required class="form-control input-sm" id="inputEmail1" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        Password
                                        <input type="password" {$validate.password} name="password" required AUTOCOMPLETE='OFF' class="form-control input-sm" id="inputPassword12" placeholder="Password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-6">
                                        <button type="submit" onclick="return login();" class="btn blue">Login & Pay</button>
                                        <br>
                                        <a  href="/patron/register">Register</a>
                                    </div>
                                </div>
                                    </form>
                            </div>

                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>

</div>
<a href="#bookslot" id="bookslotclick" data-toggle="modal"></a>
<script>
                            changeCategory({$current_date});
</script>    
