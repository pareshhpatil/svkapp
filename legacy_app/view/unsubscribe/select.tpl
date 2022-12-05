<script src="https://www.google.com/recaptcha/api.js"></script>
<div class="page-content" >

    <div class="row no-margin" style="text-align: center;">

        <div class="col-md-2"></div>
        <div class="col-md-8" style="text-align: -webkit-center;text-align: -moz-center;text-align: center; max-width: 960px;">
            <br>
            <h3 class="page-title" style="text-align: left;">Unsubscribe</h3>

            {if isset($success)}
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Success!</strong>{$success}
                </div> {/if}


                {if isset($errors)}
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <strong>Error!</strong>
                        <div class="media">
                            {foreach from=$errors key=k item=v}
                                <p class="media-heading">{$k} - {$v.1}</p>
                            {/foreach}
                        </div>

                    </div>
                {/if}
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="/unsubscribe/saveunsubscribe/{$link}" onsubmit="return validateUn();" method="post" id="template_create" class="form-horizontal form-row-sepe">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        {if isset($haserrors)}
                                            <div class="alert alert-danger" style="text-align:left;">
                                                <button type="button" class="close" data-dismiss="alert"></button>
                                                <p>{$haserrors}</p>
                                            </div>
                                        {/if}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Email <span class="required">
                                                </span></label>
                                            <div class="col-md-8">
                                                <input type="email" readonly name="email" class="form-control" value="{$email}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Reason <span class="required">
                                                    * </span></label>
                                            <div class="col-md-8">
                                                <select name="type" class="form-control" data-placeholder="Select...">
                                                    <option value="1">This Bill has been settled</option>
                                                    <option value="2">I do not recognize this service provider</option>
                                                    <option value="3">No longer my service provider</option>
                                                    <option value="4">Paid using Cash/Cheque/NEFT</option>
                                                    <option value="5">Don't remind me this month</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4"></label>
                                            <label class="control-label col-md-8" style="text-align:left;">
                                                <input id="mbvfn" onchange="ismobileverification('{$customerlink}');" type="checkbox">Would you also like to stop receiving alerts on your mobile phone? <span class="required">
                                                </span>
                                            </label>
                                        </div>
                                        <div id="mobilevverification" style="display: none;">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Mobile <span class="required">
                                                    </span></label>
                                                <div class="col-md-8">
                                                    <input type="text" id="mobile" readonly  class="form-control" value="{$mobile}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <label class="control-label col-md-4"></label>
                                                <label class="control-label col-md-8" style="text-align: left;">
                                                     <a onclick="sendotp('{$customerlink}');" id="vbtn" class="btn btn-sm blue">Resend OTP</a>  <span style="color: red;" id="timer"></span><span class="required">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4"><span class="required">
                                                    </span></label>
                                                <div class="col-md-8">
                                                    <div class="input-group">

                                                        <input type="text"  id="otp" class="form-control" autocomplete="off" maxlength="4" min="4" placeholder="Enter OTP">
                                                        <span class="input-group-btn">
                                                            <a onclick="verifyotp('{$mobilelink}');" class="btn green">Verify OTP</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p style="display: none;color: green;" id="success_otp" style="color:green;">Mobile verified successfully.</p>
                                        <p style="display: none;color: red;" id="failed_otp" style="color:red;">Mobile verification failed.</p>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label class="control-label col-md-4">Captcha <span class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <form id="comment_form" action="form.php" method="post">
                                                        <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-5">
                                            <input type="hidden" class="displayonly" name="verifymobile" value="0" id="verifymobile">
                                            <button type="submit" class="btn blue">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>	
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>