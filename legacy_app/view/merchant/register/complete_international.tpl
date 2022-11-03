<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Failure!</strong>
                <div class="media">
                    {foreach from=$haserrors item=v}

                        <p class="media-heading">{$v.0} - {$v.1}.</p>
                    {/foreach}
                </div>

            </div>
        {/if}


        <div class="row">
            <div class="col-md-12">
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <div class="portlet " id="form_wizard_1">
                    <div class="portlet-body form">
                        <form action="/merchant/profile/currencysaved" class="form-horizontal" id="submit_form"
                            method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step ">
                                                <span class="number circle-c">
                                                    <i class="fa fa-briefcase fa18"></i> </span><br>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company <br> information</span>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number circle-c">
                                                    <i class="fa fa-address-book fa18"></i> </span><br>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Contact
                                                    <br> details
                                                </span>
                                            </a>
                                        </li>
                                        {if $has_inr_currency==1}
                                            <li id="kycdiv" class="">
                                                <a href="#tab4" data-toggle="tab" class="step">
                                                    <span class="number circle-c">
                                                        <i class="fa fa-id-card fa18"></i> </span><br>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Online payments <br> KYC </span>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab3" data-toggle="tab" class="step ">
                                                    <span class="number circle-c">
                                                        <i class="fa fa-university fa18"></i> </span><br>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Activate payments <br> (INR)</span>
                                                </a>
                                            </li>

                                        {/if}
                                        {if $has_other_currency==1}
                                            <li class="active">
                                                <a href="#tab5" data-toggle="tab" class="step active">
                                                    <span class="number circle-c">
                                                        <i class="fa fa-university fa18"></i> </span><br>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i>Activate payments <br>
                                                        ({$int_currency})</span>
                                                </a>
                                            </li>
                                        {/if}

                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success">
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="tab-pane active" id="tab5">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10 mb-2">

                                                    <h3>Integration Stripe payment gateway
                                                    </h3>

                                                    {$stripe_status=0}
                                                    {if isset($account)}
                                                        {$stripe_status=$account.stripe_status}
                                                    {/if}
                                                    {if $stripe_status==1}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-6 text-left"
                                                                style="text-align: left;">Your Stripe account activation
                                                                currently in progress.
                                                            </label>

                                                        </div>
                                                    {elseif $stripe_status==2}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-6 text-left"
                                                                style="text-align: left;">Your Stripe account activated
                                                                successfully.
                                                            </label>

                                                        </div>
                                                    {else}
                                                        <p> To integrate Stripe into your Swipez account we will redirect
                                                            you to the Stripe website to complete your onboarding.
                                                        </p>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-2 text-left"
                                                                style="text-align: left;">Currency <span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <select class="form-control select2me" multiple id="reqf5"
                                                                    name="currency[]">
                                                                    {foreach from=$currency_list item=v}
                                                                        <option {if in_array($v.code, $currency)} selected {/if}
                                                                            value="{$v.code}">{$v.code} {$v.icon}</option>
                                                                    {/foreach}
                                                                </select>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="/merchant/dashboard" class="btn btn-link pull-left">
                                                Back to Dashboard </a>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="col-md-12 no-padding">
                                                <input type="hidden" name="form_type" value="international">
                                                {if $stripe_status==0}
                                                    <button type="submit" class="btn blue pull-right">
                                                        Integrate
                                                    </button>
                                                    <a href="/merchant/dashboard" class="btn green pull-right mr-1">
                                                        Skip for now </a>
                                                {/if}
                                                <a href="/merchant/profile/complete/bank"
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