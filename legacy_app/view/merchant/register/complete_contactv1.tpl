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
                        <form action="/merchant/profile/profilecontactsaved" class="form-horizontal" id="submit_form" method="POST" enctype="multipart/form-data">
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step ">
                                                <span class="number circle-c">
                                                    <i class="fa fa-briefcase fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Company </span>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number circle-c">
                                                    <i class="fa fa-address-book fa18"></i> </span>
                                                <span class="desc">
                                                    <i class="fa fa-check"></i> Contact </span>
                                            </a>
                                        </li>
                                        <li >
                                            <a href="#tab3" data-toggle="tab" class="step ">
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
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>
                                        <div class="tab-pane active" id="tab2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Contact person first name <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="{$info.first_name}" name="first_name" id="f_name" required {$validate.name} />
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Contact person last name <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="{$info.last_name}" name="last_name"id="l_name" required {$validate.name} />
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Registered Company Address <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <textarea class="form-control"  name="address1" id="address" required {$validate.address} >{$info.address}</textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">City <span class="required" >*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="{$info.city}" name="city" id="city" required {$validate.city}/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="col-md-6">
                                                    {if $info.email_id==''}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Email ID<span class="required">*
                                                                </span>
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input type="email" class="form-control" value="" name="business_email"  required />
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Zip code<span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="{$info.zipcode}" name="zipcode" id="zip" required {$validate.zipcode}/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">State <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <select name="state" required id="state" class="form-control select2me" data-placeholder="Select...">
                                                                <option value="">Select State</option>
                                                                {foreach from=$state_code item=v}
                                                                    <option {if $info.state==$v.config_value} selected="" {/if}  value="{$v.config_value}" >{$v.config_value}</option>
                                                                {/foreach}
                                                                <option value="Non Indian territory" >Non Indian territory</option>
                                                            </select>

                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Country <span class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-7">
                                                            <input type="text" class="form-control" value="India" name="country" id="country" required {$validate.name} />
                                                            {if $info.email_id!=''}
                                                                <input type="hidden" name="business_email" id="email" value="{$info.email_id}">
                                                            {/if}
                                                            <input type="hidden" id="mobile" name="business_contact" value="{$info.mobile_no}">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-4">
                                        <a href="/merchant/dashboard"  class="btn btn-link pull-left">
                                        Back to Dashboard </a>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="col-md-12 no-padding">
                                                <input type="hidden" name="form_type" value="contact">
                                                <button type="submit"  class="btn blue pull-right">
                                                    Next
                                                </button>
                                                <a href="/merchant/profile/complete/company" class="btn btn-link pull-right mr-1">
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

<script>
    function sameas(type)
    {
        if (type == 'email') {
            if ($('#sameas_' + type).is(':checked')) {
                document.getElementById('business_email').value = document.getElementById('email').value;
            } else
            {
                document.getElementById('business_email').value = '';
            }
        } else
        {
            if ($('#sameas_' + type).is(':checked')) {
                document.getElementById('business_contact').value = document.getElementById('mobile').value;
            } else
            {
                document.getElementById('business_contact').value = '';
            }

        }
    }
</script>