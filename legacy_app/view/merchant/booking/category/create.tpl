
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if $success!=''}
                <div class="alert alert-block alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p>{$success}</p>
                </div>
            {/if}
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
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->


            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/bookings/savecategory/web" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('booking_category_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Category name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="category_name" maxlength="45" {$validate.name} class="form-control" value="{$post.category_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Require membership? <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="checkbox"  name="membership" value="1" class="make-switch" data-on-text="&nbsp;Yes&nbsp;&nbsp;" 
                                                   data-off-text="&nbsp;No&nbsp;">
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
                                        <input type="submit" value="Save" class="btn blue"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->


<!-- /.modal-content -->
</div>