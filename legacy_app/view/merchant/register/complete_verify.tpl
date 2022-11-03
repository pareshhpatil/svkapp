<!-- BEGIN CONTAINER -->

<!-- BEGIN CONTENT -->
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="col-md-1"></div>
    <div class="col-md-10">

        {if isset($error)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Failure!</strong>
                <div class="media">
                    <p class="media-heading">{$error}</p>
                </div>

            </div>
        {/if}



        <div class="row">
            <div class="col-md-12">
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <div class="portlet " id="form_wizard_1">

                    <div class="portlet-body form">
                        <form action="/merchant/profile/validateamount" class="form-horizontal" id="submit_form"
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
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step ">
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
                                            <li class="active">
                                                <a href="#tab3" data-toggle="tab" class="step active">
                                                    <span class="number circle-c">
                                                        <i class="fa fa-university fa18"></i> </span><br>
                                                    <span class="desc">
                                                    <i class="fa fa-check"></i> Activate payments <br> (INR)</span>
                                                </a>
                                            </li>

                                        {/if}
                                        {if $has_other_currency==1}
                                            <li >
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
                                        <div id="error" class="alert alert-danger display-none">
                                            Please fill all required fields
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            Your form validation is successful!
                                        </div>

                                        <div class="tab-pane active" id="tab3">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10 mb-2">
                                                    <p>We have deposited a small amount into your bank account. The
                                                        amount received would be between ₹1 - ₹2 for ex. ₹1.34.
                                                        <br>Please check your {$account.bank_name} account statement and
                                                        enter the received amount below.
                                                    </p>
                                                </div>
                                                <div class="col-md-8">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">Penny amount <span
                                                                class="required">*
                                                            </span>
                                                        </label>
                                                        <div class="col-md-6">
                                                            <input type="number" step="0.01" required=""
                                                                title="Enter penny drop amount" max="10"
                                                                class="form-control" name="penny_amount">
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
                                        <div class="col-md-4 col-xs-6"><a href="/merchant/dashboard"
                                                class="btn btn-link pull-left">
                                                Back to Dashboard </a></div>
                                        <div class="col-md-8 hidden-xs">
                                            <button type="submit" class="btn blue pull-right">
                                                Validate
                                            </button>
                                            <a href="/merchant/profile/skippennydrop" class="btn green pull-right mr-1">
                                                Skip for now </a>
                                            <a href="/merchant/profile/complete/contact"
                                                class="btn btn-link pull-right mr-1">
                                                Back </a>
                                        </div>
                                        <div class="visible-xs-block col-md-6">
                                            <a href="/merchant/profile/complete/contact"
                                                class="btn btn-link pull-right">
                                                Back </a>
                                        </div>
                                        <div class="visible-xs-block col-md-4 pull-left">
                                            <button type="submit" class="btn blue">
                                                Validate
                                            </button>
                                        </div>
                                        <div class="visible-xs-block col-md-6">
                                            <a href="/merchant/profile/skippennydrop" class="btn green">
                                                Skip for now </a>
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
    BizType({$merchant.entity_type});
    gstavailable();
</script>