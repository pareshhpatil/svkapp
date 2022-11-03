
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
                    <form action="/merchant/bookings/updatemembershipsave" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                       {CSRF::create('booking_membership_update')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Title <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="title" maxlength="45" {$validate.name} class="form-control" value="{$detail.title}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Category <span class="required"> *
                                            </span></label>
                                        <div class="col-md-4">
                                            <select name="category" id="cat_drop" required class="form-control" data-placeholder="Select...">
                                                <option value="">Select Category</option>
                                                {foreach from=$category_list item=v}
                                                    {if $v.membership==1}

                                                        <option {if $detail.category_id==$v.category_id} selected {/if} value="{$v.category_id}"> {$v.category_name}</option>
                                                    {/if}
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Days <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="number" min="1" max="1000" required name="days" class="form-control" value="{$detail.days}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Amount <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="number" min="1" step="0.01" required name="amount" class="form-control" value="{$detail.amount}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Description <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea  name="description" class="form-control" >{$detail.description}</textarea>
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
                                        <button type="reset" class="btn default">Reset</button>
                                        <input type="hidden" value="{$detail.membership_id}" name="membership_id"/>
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