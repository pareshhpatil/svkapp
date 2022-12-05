
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
                    <form action="/merchant/supplier/saveupdate" method="post" class="form-horizontal form-row-sepe">
                        {CSRF::create('supplier_update')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Supplier company name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="supplier_company_name" class="form-control" value="{$supplier.supplier_company_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Industry type <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control select2me" name="industry_type" >
                                                {foreach from=$list key=k item=v}
                                                    {if {{{$supplier.industry_type}}=={$v.config_key}}}
                                                        <option selected value="{$v.config_key}" selected>{$v.config_value}</option>
                                                    {else}
                                                        <option value="{$v.config_key}">{$v.config_value}</option>
                                                    {/if}

                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #1 name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="contact_person_name" {$validate.name} class="form-control" value="{$supplier.contact_person_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #1 email <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" name="email1" class="form-control" value="{$supplier.email_id1}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #1 mobile <span class="required">*
                                            </span></label>
                                        <div class="col-md-1">
                                            <input type="text" name="mob_country_code1" {$validate.mobilecode} class="form-control" value="{$supplier.mob_country_code1}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="mobile1" {$validate.mobile} class="form-control" value="{$supplier.mobile1}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 name <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="contact_person_name2" {$validate.name} class="form-control" value="{$supplier.contact_person_name2}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 email <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="email" name="email2" class="form-control" value="{$supplier.email_id2}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Contact #2 mobile <span class="required">
                                            </span></label>
                                        <div class="col-md-1">
                                            <input type="text" name="mob_country_code2" {$validate.mobilecode} class="form-control" value="{$supplier.mob_country_code2}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="mobile2" {$validate.mobile_number} class="form-control" value="{$supplier.mobile2}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Company Website (Optional) <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="company_website" class="form-control" value="{$supplier.company_website}">
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
                                        <a href="/merchant/supplier/viewlist" class="btn btn-default">Cancel</a>
                                        <input type="hidden" name="supplier_id" value="{$supplier_id}" />
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