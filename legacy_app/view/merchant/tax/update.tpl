
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
                    <form action="/merchant/tax/saveupdate" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('tax_update')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Tax name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="tax_name" maxlength="100" class="form-control" value="{$detail.tax_name}">
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="control-label col-md-4">Tax Type <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <select name="tax_type"  onchange="changeTaxtype(this.value);" required="" class="form-control" data-placeholder="Select...">
                                                <option value="">Select Type</option>
                                                {foreach from=$tax_type item=v}
                                                    <option {if $v.config_key==$detail.tax_type} selected {/if} value="{$v.config_key}" >{$v.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group {if $detail.tax_type!=4}display-none{/if}" id="tax_calculated_on">
                                        <label class="control-label col-md-4 ">Tax calculated on </label>
                                        <div class="col-md-4">
                                            <select name="tax_calculated_on" class="form-control" data-placeholder="Select...">
                                                {foreach from=$tax_calculated_on item=t}
                                                    <option {if $t.config_key==$detail.tax_calculated_on} selected {/if} value="{$t.config_key}" >{$t.config_value}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group {if $detail.tax_type==5}display-none{/if}"  id="tpercent">
                                        <label class="control-label col-md-4">Percentage <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" name="percentage"  class="form-control" value="{$detail.percentage}">
                                        </div>
                                    </div>

                                    <div class="form-group {if $detail.tax_type!=5}display-none{/if}" id="tamount">
                                        <label class="control-label col-md-4">Amount <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="number" step="0.01" name="tax_amount" class="form-control" value="{$detail.fix_amount}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4">Description <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <textarea name="description" class="form-control" >{$detail.description}</textarea>
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
                                        <a href="/merchant/tax/viewlist" class="btn btn-link">Cancel</a>
                                        <input type="hidden" name="tax_id" value="{$detail.tax_id}">
                                        <input type="submit" value="Save" class="btn blue"/>
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