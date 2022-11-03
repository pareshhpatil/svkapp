<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>
                <div class="media">
                    <p class="media-heading">
                        {$success}
                    </p>
                </div>

            </div>
        {/if}

        {if isset($error)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Failure!</strong>
                <div class="media">
                    <p class="media-heading">
                        Penny drop could not be completed. We have received the following message from the bank {$error}.
                        Please get in touch with our support team via chat or drop us an email on support@swipez.in. Our
                        support team will help you complete your bank account verification
                    </p>
                </div>

            </div>
        {/if}



        <div class="row">
            <div class="col-md-12">
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <div class="portlet " id="form_wizard_1">
                    <div class="portlet-body form">
                        <form action="/merchant/profile/banksaved" class="form-horizontal" id="submit_form"
                            method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number circle-c">
                                                    <i class="fa fa-briefcase fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company 
                                                    <br> Information
                                                    </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number circle-c">
                                                    <i class="fa fa-address-book fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Contact </span>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="#tab3" data-toggle="tab" class="step active">
                                                <span class="number circle-c">
                                                    <i class="fa fa-university fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Online payments </span>
                                            </a>
                                        </li>
                                        <li id="kycdiv" class="">
                                            <a href="#tab4" data-toggle="tab" class="step">
                                                <span class="number circle-c">
                                                    <i class="fa fa-id-card fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> KYC information </span>
                                            </a>
                                        </li>

                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="error" class="alert alert-danger display-none">
                                            Please fill all required fields
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>

                                        <div class="tab-pane active" id="tab3">
                                            {if $bank_warning==true}
                                                <div class="alert alert-block alert-warning fade in" >
                                                    <p>Your bank account verification is not complete. Please complete bank
                                                        verification to submit your documents.</p>
                                                </div>
                                            {/if}
                                            <div class="row">
                                                <div class="col-md-8">

                                                    {if $info.verification_status!=2}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Would you like to collect
                                                                payments online? <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="checkbox" name="online_payment"
                                                                    onchange="paymentOnline(this.checked);" value="1"
                                                                    class="make-switch" data-on-text="&nbsp;Yes&nbsp;&nbsp;"
                                                                    checked data-off-text="&nbsp;No&nbsp;">


                                                            </div>
                                                        </div>
                                                    {/if}

                                                    <div id="bdet">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Account number <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" value="{$info.account_number}"
                                                                    {if $info.verification_status==2} readonly {/if}
                                                                    id="reqf1" pattern="[0-9]"
                                                                    title="Please enter numeric characters"
                                                                    maxlength="20" class="form-control"
                                                                    name="account_number">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Bank account holder
                                                                name <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" value="{$info.account_holder_name}"
                                                                    id="reqf2" {if $info.verification_status==2}
                                                                    readonly {/if}
                                                                    placeholder="ABC Pvt. Ltd. OR Rahul Sharma" title=""
                                                                    maxlength="100" class="form-control"
                                                                    name="account_holder_name">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">IFSC code<span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" {$validate.name_text} id="reqf3"
                                                                    {if $info.verification_status==2} readonly {/if}
                                                                    value="{$info.ifsc_code}" maxlength="20" pattern=""
                                                                    class="form-control" name="ifsc_code">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Account type <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                {if $info.verification_status==2}
                                                                    <input type="text" readonly id="reqf5"
                                                                        value="{if $info.account_type==1}Current{else}Saving{/if}"
                                                                        maxlength="20" pattern="" class="form-control">
                                                                    <input type="hidden" value="{$info.account_type}"
                                                                        name="account_type">
                                                                {else}
                                                                    <select class="form-control" id="reqf5"
                                                                        name="account_type">
                                                                        <option value="">Select..</option>
                                                                        <option {if $info.account_type==1} selected {/if}
                                                                            value="1">Current</option>
                                                                        <option {if $info.account_type==2} selected {/if}
                                                                            value="2">Saving</option>
                                                                    </select>
                                                                {/if}
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Bank name <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" value="{$info.bank_name}" id="reqf4"
                                                                    {if $info.verification_status==2} readonly {/if}
                                                                    maxlength="100" class="form-control"
                                                                    name="bank_name">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="/merchant/dashboard" class="btn btn-link pull-left">
                                                Back to Dashboard </a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12 no-padding">
                                                <input type="hidden" value="" id="detail" name="detail">
                                                <input type="hidden" value="{$merchant.entity_type}" name="biz_type">
                                                <input type="hidden" name="form_type" value="document">
                                                <input type="hidden" id="submit_type" name="submit_type" value="">

                                                {if $info.verification_status==2}
                                                    <a href="/merchant/profile/complete/document"
                                                        class="btn blue pull-right">Next</a>
                                                {else}
                                                    <input type="submit" onclick="bankDetailRequired(true)" id="s-btn"
                                                        class="btn blue pull-right" name="submit"
                                                        value="Verify your bank account" />
                                                    <input type="submit" onclick="bankDetailNotRequired()"
                                                        class="btn green pull-right ml-4" style="margin-right:10px"
                                                        name="btnskip" value="Skip for now" />
                                                {/if}
                                                <a href="/merchant/profile/complete/contact"
                                                    onclick="removeBankNotVerified()"
                                                    class="btn btn-link pull-right mr-1">
                                                    Back </a>




                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix">
    </div>
    <!-- END PAGE CONTENT-->
</div>
<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->