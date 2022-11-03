<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    {if !empty($customer_detail)}
        <h3 class="page-title"><b>Current Balance</b></h3>
    {/if}
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>  {$haserrors}
            </div> 
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
            </div> 
        {/if}
        <div class="col-md-12">


            <div class="portlet light bordered">
                <div class="portlet-body form">
                    {if empty($customer_detail)}
                        <div class="alert alert-danger">
                            <strong>Invalid QR code</strong>
                        </div> 
                    {else}
                        <h3 class="form-section">{$customer_detail.first_name} {$customer_detail.last_name}</h3>
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>


                            <div class="row no-margin">
                                <div class="col-md-12">
                                    <label class="control-label col-md-12" style="font-size: 20px;font-weight: 300;">Points : {$points}
                                    </label>
                                </div>
                            </div>
                            <div class="row no-margin">
                                <div class="col-md-12">
                                    <label class="control-label col-md-12"  style="font-size: 20px;font-weight: 300;">Rupees : {$points_rs} <i class="fa fa-inr" style="font-size: 20px;"></i> </label>
                                </div>
                            </div>

                            <div class="row">
                                <br>
                                <div class="col-md-12" style="text-align: center;">
                                    <a style="width: 100%;max-width: 300px;" data-toggle="modal" title="Earn Points" href="#custom" class="btn green">Add Points</a>
                                </div>
                            </div>
                            {if $points>=$loyalty_setting.redemption_threshold}
                                <div class="row ">
                                    <br>
                                    <div class="col-md-12" style="text-align: center;">
                                        <a style="width: 100%;max-width: 300px;" data-toggle="modal"  title="Redeem Points" href="#redeem" class="btn btn-link">Redeem Points</a>
                                    </div>
                                </div>
                            {/if}
                        {/if}
                        <div class="row">
                            <br>
                            <div class="col-md-12" style="text-align: center;">
                                <a style="width: 100%;max-width: 300px;" href="/merchant/loyalty/scanqrcode" class="btn blue">New Scan</a>
                            </div>
                        </div>
                        <!-- End profile details -->
                    </div>
                </div>
            </div>
        </div>	
        <!-- END PAGE CONTENT-->
    </div>

</div>
<!-- END CONTENT -->
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="/merchant/loyalty/saveearnpoints" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content" style="padding-left: 30px;padding-right: 30px;">
                <div class="modal-header">
                    <button type="button" class="close"  id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add Points</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Purchase Amount<span class="required">* </span></label>
                                            <div class="col-md-6">
                                                <input type="number" onblur="calculateLoyaltyPoints(this.value,{$loyalty_setting.earning_logic_rs/$loyalty_setting.earning_logic_points});" step="0.01" id="template_name" maxlength="45" name="amount" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Loyalty Points<span class="required"> </span></label>
                                            <div class="col-md-6">
                                                <input type="number" step="0.01" readonly="" id="purchase_point" maxlength="45" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Note<span class="required"> </span></label>
                                            <div class="col-md-6">
                                                <textarea type="text" rows="2" id="sms" maxlength="100" name="narrative" value="" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><span class="required"> </span></label>
                                            <div class="col-md-6 center">
                                                <input type="hidden" name="link" value="{$link}">
                                                <button type="submit" name="earn" class="btn blue">Save</button>
                                                <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>

    </div>
</div>
<div class="modal fade bs-modal-lg in" id="redeem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="/merchant/loyalty/saveredeempoints" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content" style="padding-left: 30px;padding-right: 30px;">
                <div class="modal-header">
                    <button type="button" class="close"  id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Redeem Points</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Redeem Amount (Max Amount {$points_rs})<span class="required">* </span></label>
                                            <div class="col-md-6">
                                                <input type="number" max="{$points_rs}" step="0.01" id="template_name" maxlength="45" name="amount" value="{$points_rs}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Note<span class="required"> </span></label>
                                            <div class="col-md-6">
                                                <textarea type="text" rows="2" id="sms" maxlength="100" name="narrative" value="" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4"><span class="required"> </span></label>
                                            <div class="col-md-6 center">
                                                <input type="hidden" name="link" value="{$link}">
                                                <button type="submit" name="earn" class="btn blue">Save</button>
                                                <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>

    </div>
</div>