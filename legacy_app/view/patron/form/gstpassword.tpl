
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">&nbsp;</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row no-margin">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="text-align: -webkit-center;text-align: -moz-center;">
            <h3 class="page-title">GSTIN Password</h3>
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


            <div class="portlet light bordered" style="max-width: 900px;text-align: left;">

                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">To <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" name="merchant_email" value="{$merchant_email}" readonly class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Name <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input type="text"  readonly="" value="{$detail.customer_name}" name="name" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Email <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input type="email" readonly="" value="{$detail.email}" name="email"  class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Transaction ref number <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" readonly="" name="transaction_id" value="{$transaction_id}" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">GST number <span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input type="text" readonly="" name="gst_number" value="{$detail.gst_number}" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">GSTIN password<span class="required">
                                                            * </span></label>
                                                    <div class="col-md-6">
                                                        <input required type="text" name="gst_password" class="form-control" >
                                                        <span class="help-block"> </span>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="col-md-12">
                                                    <button type="submit" name="submitbtn" class="btn blue pull-right">Send</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>					
                <!-- End profile details -->
            </div>
        </div>
    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>