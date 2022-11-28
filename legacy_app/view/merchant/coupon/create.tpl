
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}

                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}

            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/coupon/save" method="post" onsubmit="return validateCoupon();" class="form-horizontal form-row-sepe">
                        {CSRF::create('coupon_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="error_div" class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Coupon code <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="coupon_code" class="form-control" value="{$post.coupon_code}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Coupon description <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea  required name="descreption" class="form-control" >{$post.descreption}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Start date <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline date-picker" id="start_date" type="text" required  value="{$post.start_date}" name="start_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Start date"/>
                                            <span id="start_date-er" style="display: none;color: #a94442;"  class="help-block help-block-error">Start date should be greater than End date.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">End date <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-inline date-picker" id="end_date" type="text" required  value="{$post.end_date}" name="end_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="End date"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Number of coupons<span class="required">*
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="number" min="0" value="0" required name="limit" class="form-control" value="{$post.limit}">
                                        </div>Keep 0 for unlimited
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Coupon offer type</label>
                                        <div class="col-md-2">
                                            <select name="is_fixed" class="form-control" onchange="isFixed(this.value);" data-placeholder="Select...">
                                                <option value="1">Fixed amount</option>
                                                <option value="2">Percentage</option>

                                            </select>
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="per_div" style="display: none;">
                                        <label class="control-label col-md-4">Percentage <span class="required">*
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="number"  id="per" name="percent" class="form-control" value="{$post.percent}">
                                        </div>%
                                    </div>
                                    <div class="form-group" id="fixed_div" >
                                        <label class="control-label col-md-4">Fixed amount <span class="required">*
                                            </span></label>
                                        <div class="col-md-2">
                                            <input type="number" required id="fix" name="fixed_amount" class="form-control" value="{$post.fixed_amount}">
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>					
                        <!-- End profile details -->
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <button type="reset" class="btn btn-default">Reset</button>
                                        <input  type="submit" value="Save" class="btn blue"/>
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
                        function isFixed(id) {
                            if (id == 1) {
                                $("#fixed_div").slideDown(200).fadeIn();
                                $('#fix').prop('required', true);
                                $('#per').prop('required', false);
                                $("#per_div").slideDown(200).fadeOut();
                            } else {
                                $("#per_div").slideDown(200).fadeIn();
                                $('#fix').prop('required', false);
                                $('#per').prop('required', true);
                                $("#fixed_div").slideDown(200).fadeOut();
                            }
                        }
</script>