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
                        <form action="/merchant/profile/profilecompanysaved" class="form-horizontal" id="submit_form"
                            method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li class="active">
                                            <a href="#tab1" data-toggle="tab" class="step active">
                                                <span class="number circle-c">
                                                    <i class="fa fa-briefcase fa18"></i> </span><br>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company 
                                                    <br> information
                                                    </span>
                                            </a>
                                        </li>
                                        <li>
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
                                            <li class="">
                                                <a href="#tab5" data-toggle="tab" class="step">
                                                    <span class="number circle-c">
                                                        <i class="fa fa-university fa18"></i> </span><br>
                                                    <span class="desc">
                                                    <i class="fa fa-check"></i>Activate payments <br> ({$int_currency})</span>
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

                                        <div id="error" class="alert alert-warning display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            <span id="gst_error">Invalid GST number. Please enter valid GST
                                                number</span>
                                        </div>

                                        <div class="tab-pane active" id="tab1">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">GST registered company?
                                                            <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="checkbox" name="gst_available"
                                                                onchange="GSTAvailable(this.checked);" value="1"
                                                                class="make-switch" data-on-text="&nbsp;Yes&nbsp;&nbsp;"
                                                                {if $info.entity_type>0 && $account.gst_available==0}
                                                                {else} checked 
                                                                {/if} data-off-text="&nbsp;No&nbsp;">

                                                        </div>
                                                    </div>
                                                    <div id="gst_div" class="form-group display-none"
                                                        
                                                        style="display: block;" >
                                                        <label class="control-label col-md-5">GST Number <span
                                                                class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-5">
                                                            <input type="text" value="{$info.gst_number}"
                                                                name="gst_number" onchange="gstCheck(this.value);"
                                                                id="gst_number" class="form-control"
                                                                {$validate.gst_number} />
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a onclick="gstValidate();" class="btn blue">Fetch company
                                                                details</a>
                                                        </div>
                                                    </div>

                                                    <div class="display-none" id="cdiv" {if $info.entity_type>0}
                                                        style="display: block;" {/if}>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Company name <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" {if $info.company_name==''}
                                                                    required="" {else} readonly
                                                                    value="{$info.company_name}" {/if} id="company_name"
                                                                    name="company_name" class="form-control" />
                                                                <input type="hidden" value="{$info.city}" id="city"
                                                                    name="city" />
                                                                <input type="hidden" value="{$info.state}" id="state"
                                                                    name="state" />
                                                                <input type="hidden" value="{$info.zipcode}"
                                                                    id="zipcode" name="zipcode" />
                                                                <input type="hidden" value="{$info.address}"
                                                                    id="address" name="address1" />
                                                                <input type="hidden" value="{$info.first_name}"
                                                                    id="first_name" name="first_name" />
                                                                <input type="hidden" value="{$info.last_name}"
                                                                    id="last_name" name="last_name" />
                                                                {if $info.email_id!=''}
                                                                    <input type="hidden" name="business_email" id="email"
                                                                        value="{$info.email_id}">
                                                                {/if}
                                                                <input type="hidden" id="mobile" name="business_contact"
                                                                    value="{$info.mobile_no}">
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Entity type <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <select name="entity_type" required id="entity_type"
                                                                    class="form-control" data-placeholder="Select...">
                                                                    {foreach from=$entitytype key=k item=v}
                                                                        {if {{$info.entity_type}=={$v.config_key}}}
                                                                            <option selected value="{$v.config_key}" selected>
                                                                                {$v.config_value}
                                                                            </option>
                                                                        {else}
                                                                            <option value="{$v.config_key}">{$v.config_value}
                                                                            </option>
                                                                        {/if}
                                                                    {/foreach}
                                                                </select>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Industry type <span
                                                                    class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <select name="industry_type" required
                                                                    class="form-control select2me"
                                                                    data-placeholder="Select...">
                                                                    <option value=""></option>
                                                                    {foreach from=$industry_list item=v}
                                                                        {if $info.industry_type==$v.config_key}
                                                                            <option selected="" value="{$v.config_key}">
                                                                                {$v.config_value}</option>
                                                                        {else}
                                                                            <option value="{$v.config_key}">{$v.config_value}
                                                                            </option>
                                                                        {/if}
                                                                    {/foreach}
                                                                </select>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Company PAN <span
                                                                    class="required">
                                                                </span>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" value="{$info.pan}" id="pan"
                                                                    name="pan" class="form-control" {$validate.pan} />
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions ">
                                    <div class="row">
                                        <input type="hidden" id="exist_company" value="{$info.company_name}">
                                        <input type="hidden" id="validated_gst_number" value="{$info.gst_number}">
                                        <input type="hidden" name="form_type" value="company">
                                        <div class="col-md-12">
                                            <a href="/merchant/dashboard" class="btn btn-link pull-left">
                                                Back to Dashboard
                                            </a>
                                            <div id="submit-div" class="display-none" {if $info.entity_type>0}
                                                style="display: block;" {/if}>
                                                <button type="submit" class="btn blue pull-right">
                                                    Next
                                                </button>
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