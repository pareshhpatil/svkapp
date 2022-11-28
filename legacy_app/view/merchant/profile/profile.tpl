<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$k} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
                {if $GettingStarted==true}
                    <a class="btn blue btn-sm" href="/merchant/dashboard">
                        Back to Getting Started </a>
                {/if}
            </div>
        {/if}

        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <h3 class="form-section">Personal details</h3>
                    <form action="/merchant/profile/update" method="post" id="submit_form"
                        class="form-horizontal form-row-sepe" enctype="multipart/form-data">
                        {CSRF::create('profile_update')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-md-4">First name<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="first_name" {$validate.name} required
                                                value="{if !empty($post)}{$post.first_name}{else}{$personal.first_name}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Last name<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required name="last_name" {$validate.name} required
                                                value="{if !empty($post)}{$post.last_name}{else}{$personal.last_name}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="email" readonly
                                                value="{if !empty($post)}{$post.email}{else}{$personal.email_id}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mobile<span class="required">*
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="text" name="mob_country_code" {$validate.mobilecode}
                                                value="{if !empty($post)}{$post.mob_country_code}{else}{$personal.mob_country_code}{/if}"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="mobile" required {$validate.mobile}
                                                value="{if !empty($post)}{$post.mobile}{else}{$personal.mobile_no}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile details -->
                            <h3 class="form-section">Company details</h3>
                            <!-- Start company details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company type<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select onchange="removeValidation()" id="industry_type"
                                                name="industry_type" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select..</option>
                                                {foreach from=$industrytype key=k item=v}
                                                    {if {{$merchant.industry_type}=={$v.config_key}}}
                                                        <option selected value="{$v.config_key}" selected>{$v.config_value}
                                                        </option>
                                                    {else}
                                                        <option value="{$v.config_key}">{$v.config_value}</option>
                                                    {/if}

                                                {/foreach}
                                            </select>
                                            <span class="text-danger text-sm" id="industry_validation"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Entity type<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select name="type" class="form-control select2me"
                                                data-placeholder="Select...">
                                                {foreach from=$entitytype key=k item=v}
                                                    {if {{$merchant.entity_type}=={$v.config_key}}}
                                                        <option selected value="{$v.config_key}" selected>{$v.config_value}
                                                        </option>
                                                    {else}
                                                        <option value="{$v.config_key}">{$v.config_value}</option>
                                                    {/if}

                                                {/foreach}

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company Website<span>&nbsp;
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="website"
                                                value="{if !empty($post)}{$post.website}{else}{$merchant.merchant_website}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Registered Company Name<span>&nbsp;
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="company_name" required
                                                value="{if !empty($post)}{$post.company_name}{else}{$merchant.company_name}{/if}"
                                                class="form-control" readonly value="Opusnet">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">CIN Number<span>&nbsp; </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="cin_no" {$validate.narrative}
                                                value="{if !empty($post)}{$post.cin_no}{else}{$merchant.cin_no}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Registered company address<span
                                                class="required">* </span></label>
                                        <div class="col-md-8">
                                            <textarea name="address" required
                                                class="form-control">{if !empty($post)}{$post.address}{else}{$merchant.reg_address}{/if}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="city" required {$validate.name}
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)" onpaste="return false;"
                                                value="{if !empty($post)}{$post.city}{else}{$merchant.reg_city}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="zip" required {$validate.zipcode}
                                                value="{if !empty($post)}{$post.zip}{else}{$merchant.reg_zipcode}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select name="state" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option {if !empty($post)}
                                                            {if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($post.state))}
                                                                selected
                                                                {/if}{else}{if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($merchant.reg_state))}
                                                            selected {/if} 
                                                        {/if} value="{$v.config_value}">{$v.config_value}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Country<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="country" {$validate.name} required
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)"
                                                value="{if !empty($post)}{$post.country}{else}{$merchant.reg_country}{/if}"
                                                onpaste="return false;" class="form-control">
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company PAN<span>&nbsp; </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="pan" title="Enter Valid PAN Number"
                                                pattern="[A-Za-z]{ldelim}5{rdelim}\d{ldelim}4{rdelim}[A-Za-z]{ldelim}1{rdelim}"
                                                maxlength="10"
                                                value="{if !empty($post)}{$post.pan}{else}{$merchant.pan}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GST Number<span>&nbsp; </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="gst_number" {$validate.narrative}
                                                value="{if !empty($post)}{$post.gst_number}{else}{$merchant.gst_number}{/if}"
                                                {if $merchant.gst_number!=''} readonly {/if} class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company TAN<span>&nbsp; </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="tan" title="Enter Valid TAN Number"
                                                {$validate.narrative}
                                                value="{if !empty($post)}{$post.tan}{else}{$merchant.tan}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Business email<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="email" name="business_email" required
                                                value="{if !empty($post)}{$post.business_email}{else}{$merchant.business_email}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Business Contact<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="hidden" name="country_code" value="{$merchant.country_code}">
                                            <input type="text" name="business_contact" required
                                                value="{if !empty($post)}{$post.business_contact}{else}{$merchant.business_contact}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label class="control-label col-md-4">Display contact on invoice<span class="required">* </span></label>
                                        <div class="col-md-6">
                                            <input type="checkbox" {if $merchant.phone_on_invoice==1}checked{/if} name="phone_on_invoice" value="1" class="make-switch" data-on-text="&nbsp;Show&nbsp;&nbsp;" data-off-text="&nbsp;Hide&nbsp;">
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Business address<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea name="current_address" {$validate.address} required
                                                class="form-control">{if !empty($post)}{$post.current_address}{else}{$merchant.address}{/if}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">City<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="current_city" {$validate.name} required
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)"
                                                value="{if !empty($post)}{$post.current_city}{else}{$merchant.city}{/if}"
                                                onpaste="return false;" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Zipcode<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="current_zip" {$validate.zipcode} required
                                                value="{if !empty($post)}{$post.current_zip}{else}{$merchant.zipcode}{/if}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">State<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <select name="current_state" class="form-control select2me"
                                                data-placeholder="Select...">
                                                <option value="">Select State</option>
                                                {foreach from=$state_code item=v}
                                                    <option {if !empty($post)}
                                                            {if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($post.current_state))}
                                                                selected
                                                                {/if}{else}{if str_replace(' ','',strtolower($v.config_value))==str_replace(' ','',strtolower($merchant.state))}
                                                            selected {/if} 
                                                        {/if} value="{$v.config_value}">{$v.config_value}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Country<span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="current_country" {$validate.name} required
                                                onkeydown="return /[a-zA-Z\s]/i.test(event.key)"
                                                value="{if !empty($post)}{$post.current_country}{else}{$merchant.country}{/if}"
                                                onpaste="return false;" class="form-control">
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <!-- End transaction details -->
                            <h3 class="form-section">Documents & Bank details</h3>
                            <!-- Start bank details -->
                            <div class="row">
                                <div class="col-md-6">
                                    {if $account.verification_status==2}
                                        <!-- Checking whether the bank verification is completed or not -->
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Account number</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" readonly
                                                    value="{$account.account_no}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank account holder name</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" readonly
                                                    value="{$account.account_holder_name}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">IFSC code</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" readonly
                                                    value="{$account.ifsc_code}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Account type</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" readonly
                                                    value="{if $account.account_type=="1"}Current{else if $account.account_type=="2"}Saving{else}{/if}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Bank name</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" readonly
                                                    value="{$account.bank_name}">
                                            </div>
                                        </div>
                                    {else}
                                        <div class="alert alert-block alert-info fade in">
                                            <h4 class="alert-heading">Bank account is not linked!</h4>
                                            <p>Your bank account is currently not linked with your Swipez id.
                                                To start collecting payments online link your bank account with Swipez.
                                                Get in touch with <a href="https://support@swipez.in">support@swipez.in</a>
                                                or chat with us to link your bank account.
                                            </p>
                                        </div>
                                    {/if}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Address proof (Drivers license OR Election
                                            card OR Passport - both sides)</label>
                                        <div class="col-md-8">
                                            {if $account.adhar_card!=''}
                                                <span class="help-block">
                                                    <a class="btn btn-xs green iframe"
                                                        {if {$account.adhar_card|mb_substr:0:4}=='http' }
                                                            href="{$account.adhar_card}">
                                                        {else}
                                                            href="/uploads/documents/{$account.merchant_id}/{$account.adhar_card}">
                                                        {/if}
                                                        View
                                                        doc</a>
                                                    <a onclick="updateDoc(100, 1);" class="btn btn-xs blue">Update doc</a>
                                                </span>
                                                <span id="update100" style="display: none;">
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'a100');" id="a100"
                                                        name="doc_adhar_card">
                                                    <span class="help-block red">* Max file size 1 MB
                                                        <a onclick="updateDoc(100, 0);" class="btn btn-xs red"><i
                                                                class="fa fa-remove"></i></a>
                                                    </span>
                                                </span>
                                            {else}
                                                <input type="file" accept="image/*,application/pdf"
                                                    onchange="return validatefilesize(1000000, 'adhar_card');"
                                                    id="adhar_card" name="doc_adhar_card">
                                                <span class="help-block red">* Max file size 1 MB</span>
                                            {/if}

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{if $merchant.entity_type==6}Society{else}Business{/if}
                                            pan card</label>
                                        <div class="col-md-8">
                                            {if $account.pan_card!=''}
                                                <span class="help-block">
                                                    <a class="btn btn-xs green iframe"
                                                        href="/uploads/documents/{$account.merchant_id}/{$account.pan_card}">View
                                                        doc</a>
                                                    <a onclick="updateDoc(1, 1);" class="btn btn-xs blue">Update doc</a>
                                                </span>
                                                <span id="update1" style="display: none;">
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'a1');" id="a1"
                                                        name="doc_pan_card">
                                                    <span class="help-block red">* Max file size 1 MB
                                                        <a onclick="updateDoc(1, 0);" class="btn btn-xs red"><i
                                                                class="fa fa-remove"></i></a>
                                                    </span>
                                                </span>
                                            {else}
                                                <input type="file" accept="image/*,application/pdf"
                                                    onchange="return validatefilesize(1000000, 'pan_card');" id="pan_card"
                                                    name="doc_pan_card">
                                                <span class="help-block red">* Max file size 1 MB</span>
                                            {/if}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Cancelled cheque</label>
                                        <div class="col-md-8">
                                            {if $account.cancelled_cheque!=''}
                                                <span class="help-block">
                                                    <a class=" iframe btn btn-xs green"
                                                        href="/uploads/documents/{$account.merchant_id}/{$account.cancelled_cheque}">View
                                                        doc</a>
                                                    <a onclick="updateDoc(2, 1);" class="btn btn-xs blue">Update doc</a>
                                                </span>
                                                <span id="update2" style="display: none;">
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'a2');" id="a2"
                                                        name="doc_cancelled_cheque">
                                                    <span class="help-block red">* Max file size 1 MB
                                                        <a onclick="updateDoc(2, 0);" class="btn btn-xs red"><i
                                                                class="fa fa-remove"></i></a>
                                                    </span>
                                                </span>
                                            {else}
                                                <input type="file" accept="image/*,application/pdf"
                                                    onchange="return validatefilesize(1000000, 'cancelled_cheque');"
                                                    id="cancelled_cheque" name="doc_cancelled_cheque">
                                                <span class="help-block red">* Max file size 1 MB</span>
                                            {/if}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">GST certificate</label>
                                        <div class="col-md-8">
                                            {if $account.gst_certificate!=''}
                                                <span class="help-block">
                                                    <a class=" iframe btn btn-xs green"
                                                        href="/uploads/documents/{$account.merchant_id}/{$account.gst_certificate}">View
                                                        doc</a>
                                                    <a onclick="updateDoc(3, 1);" class="btn btn-xs blue">Update doc</a>
                                                </span>
                                                <span id="update3" style="display: none;">
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'a3');" id="a3"
                                                        name="doc_gst_cer">
                                                    <span class="help-block red">* Max file size 1 MB
                                                        <a onclick="updateDoc(3, 0);" class="btn btn-xs red"><i
                                                                class="fa fa-remove"></i></a>
                                                    </span>
                                                </span>
                                            {else}
                                                <input type="file" accept="image/*,application/pdf"
                                                    onchange="return validatefilesize(1000000, 'gst_cer');" id="gst_cer"
                                                    name="doc_gst_cer">
                                                <span class="help-block red">* Max file size 1 MB</span>
                                            {/if}
                                        </div>
                                    </div>
                                    {if $merchant.entity_type==2}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company incorporation certificate</label>
                                            <div class="col-md-8">
                                                {if $account.company_incorporation_certificate!=''}
                                                    <span class="help-block">
                                                        <a class=" iframe btn btn-xs green"
                                                            href="/uploads/documents/{$account.merchant_id}/{$account.company_incorporation_certificate}">View
                                                            doc</a>
                                                        <a onclick="updateDoc(4, 1);" class="btn btn-xs blue">Update doc</a>
                                                    </span>
                                                    <span id="update4" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a4');" id="a4"
                                                            name="company_certificate">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc(4, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                {else}
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'compcer');" id="compcer"
                                                        name="company_certificate">
                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                {/if}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company AOA</label>
                                            <div class="col-md-8">
                                                {if $account.company_aoa!=''}
                                                    <span class="help-block">
                                                        <a class="btn btn-xs green iframe"
                                                            {if {$account.company_aoa|mb_substr:0:4}=='http' }
                                                                href="{$account.company_aoa}">
                                                            {else}
                                                                href="/uploads/documents/{$account.merchant_id}/{$account.company_aoa}">
                                                            {/if}
                                                            View
                                                            doc</a>
                                                        <a onclick="updateDoc(100, 1);" class="btn btn-xs blue">Update doc</a>
                                                    </span>
                                                    <span id="update100" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a100');" id="a100"
                                                            name="company_aoa">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc(100, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                {else}
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'adhar_card');"
                                                        id="adhar_card" name="company_aoa">
                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                {/if}

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4">Company MOA</label>
                                            <div class="col-md-8">
                                                {if $account.company_moa!=''}
                                                    <span class="help-block">
                                                        <a class="btn btn-xs green iframe"
                                                            {if {$account.company_moa|mb_substr:0:4}=='http' }
                                                                href="{$account.company_moa}">
                                                            {else}
                                                                href="/uploads/documents/{$account.merchant_id}/{$account.company_moa}">
                                                            {/if}
                                                            View
                                                            doc</a>
                                                        <a onclick="updateDoc(100, 1);" class="btn btn-xs blue">Update doc</a>
                                                    </span>
                                                    <span id="update100" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a100');" id="a100"
                                                            name="company_moa">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc(100, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                {else}
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'adhar_card');"
                                                        id="adhar_card" name="company_moa">
                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                {/if}

                                            </div>
                                        </div>
                                    {/if}

                                    {if $merchant.entity_type==3}
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Partnership deed</label>
                                            <div class="col-md-8">
                                                {if $account.partnership_deed!=''}
                                                    <span class="help-block">
                                                        <a class=" iframe btn btn-xs green"
                                                            href="/uploads/documents/{$account.merchant_id}/{$account.partnership_deed}">View
                                                            doc</a>
                                                        <a onclick="updateDoc(5, 1);" class="btn btn-xs blue">Update doc</a>
                                                    </span>
                                                    <span id="update5" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a5');" id="a5"
                                                            name="partnership_deed">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc(5, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                {else}
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'partnership_deed');"
                                                        id="partnership_deed" name="partnership_deed">
                                                    <span class="help-block red">* Max file size 1 MB</span>
                                                {/if}
                                            </div>
                                        </div>
                                    {/if}

                                    {if !empty($account.address_proof)}
                                        {foreach from=$account.address_proof key=k item=v}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address proof (Drivers license OR Election
                                                    card OR Passport - both sides)</label>
                                                <div class="col-md-8">
                                                    <span class="help-block">
                                                        <a class=" iframe btn btn-xs green"
                                                            href="/uploads/documents/{$account.merchant_id}/{$v}">View doc</a>
                                                        <a onclick="updateDoc({$key+89}, 1);" class="btn btn-xs blue">Update
                                                            doc</a>
                                                    </span>
                                                    <span id="update{$key+89}" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a{$key+89}');"
                                                            id="a{$key+89}" name="address_prrof[]">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc({$key+89}, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}

                                    {if !empty($account.partner_pan_card)}
                                        {foreach from=$account.partner_pan_card item=v}
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Pan Cards</label>
                                                <div class="col-md-8">
                                                    <span class="help-block">
                                                        <a class=" iframe btn btn-xs green"
                                                            href="/uploads/documents/{$account.merchant_id}/{$v}">View doc</a>
                                                        <a onclick="updateDoc({$key+59}, 1);" class="btn btn-xs blue">Update
                                                            doc</a>
                                                    </span>
                                                    <span id="update{$key+59}" style="display: none;">
                                                        <input type="file" accept="image/*,application/pdf"
                                                            onchange="return validatefilesize(1000000, 'a{$key+59}');"
                                                            id="a{$key+59}" name="partner_pan_card[]">
                                                        <span class="help-block red">* Max file size 1 MB
                                                            <a onclick="updateDoc({$key+59}, 0);" class="btn btn-xs red"><i
                                                                    class="fa fa-remove"></i></a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}

                                    <div class="form-group">
                                        <label
                                            class="control-label col-md-4">{if $merchant.entity_type==6}Society{else}Business{/if}
                                            registration proof</label>
                                        <div class="col-md-8">
                                            {if $account.business_reg_proof!=''}
                                                <span class="help-block">
                                                    <a class=" iframe btn btn-xs green"
                                                        href="/uploads/documents/{$account.merchant_id}/{$account.business_reg_proof}">View
                                                        doc</a>
                                                    <a onclick="updateDoc(12, 1);" class="btn btn-xs blue">Update doc</a>
                                                </span>
                                                <span id="update12" style="display: none;">
                                                    <input type="file" accept="image/*,application/pdf"
                                                        onchange="return validatefilesize(1000000, 'a12');" id="a12"
                                                        name="biz_reg_proof">
                                                    <span class="help-block red">* Max file size 1 MB
                                                        <a onclick="updateDoc(12, 0);" class="btn btn-xs red"><i
                                                                class="fa fa-remove"></i></a>
                                                    </span>
                                                </span>
                                            {else}
                                                <input type="file" accept="image/*,application/pdf"
                                                    onchange="return validatefilesize(1000000, 'biz_cer');" id="biz_cer"
                                                    name="biz_reg_proof">
                                                <span class="help-block red">* Max file size 1 MB</span>
                                            {/if}
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                        <!-- End Bulk upload details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                        <input type="submit" class="btn blue" value="Update" />
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
<script>
    function checkValidation() {
        if (document.querySelector('#industry_type').value == "") {
            event.preventDefault();
            document.querySelector('#industry_validation').innerText = 'Select your industry type';
        }
    }

    function removeValidation() {
        if (document.querySelector('#industry_type').value != "") {
            document.querySelector('#industry_validation').innerText = '';
        }
    }
</script>