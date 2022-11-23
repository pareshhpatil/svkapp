
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$k} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <form action="/merchant/cable/updatesetting" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!-- End Bank details -->
                        <h4 class="form-section">NCF fee Settings</h4>
                        <!-- Start Bulk upload details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF channel quantity</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.ncf_qty)}{$se.ncf_qty}{else}25{/if}" name="ncf_qty">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF fee</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.ncf_fee)}{$se.ncf_fee}{else}20{/if}" name="ncf_fee">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF tax</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.ncf_tax)}{$se.ncf_tax}{else}18.00{/if}" name="ncf_tax">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF tax name</label>
                                    <div class="col-md-3">
                                        <input type="text"  class="form-control"  required value="{if isset($se.ncf_tax_name)}{$se.ncf_tax_name}{else}GST @18%{/if}" name="ncf_tax_name">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF apply on base packages</label>
                                    <div class="col-md-3">
                                        <input type="checkbox" value="1"  class="form-control" {if $se.ncf_base_package==1}checked{/if} name="ncf_base_package">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF apply on addon packages</label>
                                    <div class="col-md-3">
                                        <input type="checkbox" value="1"  class="form-control" {if $se.ncf_addon_package==1}checked{/if} name="ncf_addon_package">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">NCF apply on alacarte packages</label>
                                    <div class="col-md-3">
                                        <input type="checkbox" value="1" class="form-control" {if $se.ncf_alacarte_package==1}checked{/if} name="ncf_alacarte_package">
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <input type="hidden" name="id" value="{if isset($se.merchant_id)}{$se.merchant_id}{else}{/if}" name="id">
                                        <input type="submit" class="btn blue" value="Submit"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>						
                </div>						
                <!-- End Bulk upload details -->



            </div>
        </form>	
    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
