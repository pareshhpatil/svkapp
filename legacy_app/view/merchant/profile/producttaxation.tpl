<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in"
                    style="margin-left: 0px !important; margin-right: 0px !important;">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
        </div>
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <form class="form-horizontal" action="/merchant/profile/updatetaxation" method="post"
                        id="submit_form">
                        {CSRF::create('profile_setting')}
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 ">
                                    <label for="product_taxation_type" class="col-sm-4 control-label">Product taxation
                                        type</label>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="col-sm-10">
                                        <select id="product_taxation_type" required class="form-control"
                                            name="taxation_type">
                                            <option {if $product_taxation == 1}selected{/if} value="1">
                                                <div>
                                                    <b>Product cost exclusive of tax</b>
                                                </div>
                                            </option>
                                            <option {if $product_taxation == 2}selected{/if} value="2">
                                                <div>
                                                    <b> Product cost inclusive of tax</b>
                                                </div>
                                            </option>
                                            <option {if $product_taxation == 3}selected{/if} value="3">Set
                                                inclusive/exclusive during invoice creation</option>
                                        </select>
                                        <br /> <br />
                                        <div id="submit-div" class="form-actions">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="pull-right">
                                                        <input type="submit" value="Save" class="btn blue" />
                                                    </div>
                                                </div>
                                            </div>
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
</div>
<style>
    .select2-container--default .select2-selection--single {
        border: none;
    }
</style>

<!-- END PAYMENT TRANSACTION TABLE -->
</div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->