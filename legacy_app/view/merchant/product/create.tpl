
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Create product</h3>
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
                    <form action="/merchant/product/productsave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('product_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Type <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">

                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio1" checked="" value="Goods" name="type" class="md-radiobtn">
                                                    <label for="radio1">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Goods </label>
                                                </div>
                                                <div class="md-radio">
                                                    <input type="radio" id="radio2" value="Service" name="type" class="md-radiobtn">
                                                    <label for="radio2">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        Service </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Product name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="product_name" {$validate.name} class="form-control" value="{$post.product_name}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Price <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" name="price"  class="form-control" value="{$post.price}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">HSN/SAC Code <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="20" name="sac_code"  class="form-control" value="{$post.sac_code}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Unit type <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" maxlength="45" name="unit_type"  class="form-control" value="{$post.unit_type}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Description <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea name="description" class="form-control" >{$post.description}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">GST applicable <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <select id="tax"  data-placeholder="Select GST" name="gst_percent" class="form-control">
                                                {foreach from=$gst_tax item=v}
                                                    <option value="{$v}">{$v}%</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>					
                        <!-- End profile details -->

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-6 col-md-5">
                                    <input type="submit" value="Save" class="btn blue"/>
                                    <a href="/merchant/product/viewlist" class="btn btn-link">Cancel</a>
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
