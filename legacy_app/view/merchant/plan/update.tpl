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
                    <form action="/merchant/plan/saveupdate" method="post" class="form-horizontal form-row-sepe">
                        {CSRF::create('plan_update')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Source name <span class="required">
                                            </span></label>
                                        <div class="col-md-4">
                                            {if !empty($source)}
                                                <div id="soex">
                                                    <select id="sodrop" required name="ex_source_name" class="form-control"
                                                        data-placeholder="Select...">
                                                        <option value="">Select Source</option>
                                                        {foreach from=$source item=v}
                                                            {if $v.source==$plan.source}
                                                                <option selected value="{$v.source}">{$v.source}</option>
                                                            {else}
                                                                <option value="{$v.source}">{$v.source}</option>
                                                            {/if}
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            {/if}
                                            {if !empty($source)}
                                                <div id="sonew" style="display: none;">
                                                {else}
                                                    <div id="sonew">
                                                    {/if}
                                                    <input type="text" maxlength="100" placeholder="New source name"
                                                        id="sotext" name="source_name" {$validate.name}
                                                        class="form-control" value="{$plan.source}">
                                                </div>

                                            </div>
                                            <div class="col-md-1 no-margin no-padding">
                                                {if !empty($source)}
                                                    <a id="solinkadd" title="Add new source" onclick="newsource(1);"
                                                        class="btn green">Add new <i class="fa fa-plus"></i></a>
                                                    <a id="solinkremove" style="display: none;" title="Cancel"
                                                        onclick="newsource(0);" class="btn btn-link">Cancel <i
                                                            class="fa fa-remove"></i></a>
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Category name <span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                {if !empty($category)}
                                                    <div id="catex">
                                                        <select id="catdrop" required name="ex_category_name"
                                                            class="form-control" data-placeholder="Select...">
                                                            <option value="">Select Category</option>
                                                            {foreach from=$category item=v}
                                                                {if $v.category==$plan.category}
                                                                    <option selected value="{$v.category}">{$v.category}</option>
                                                                {else}
                                                                    <option value="{$v.category}">{$v.category}</option>
                                                                {/if}
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                {/if}
                                                {if !empty($category)}
                                                    <div id="catnew" style="display: none;">
                                                    {else}
                                                        <div id="catnew">
                                                        {/if}
                                                        <input id="cattext" type="text" placeholder="New category name"
                                                            name="category_name" maxlength="100" class="form-control"
                                                            value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 no-margin no-padding">
                                                    {if !empty($category)}
                                                        <a id="catlinkadd" title="Add new source" onclick="newcategory(1);"
                                                            class="btn green">Add new <i class="fa fa-plus"></i></a>
                                                        <a id="catlinkremove" style="display: none;" title="Cancel"
                                                            onclick="newcategory(0);" class="btn btn-link">Cancel <i
                                                                class="fa fa-remove"></i></a>
                                                    {/if}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Plan name <span class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="text" required name="plan_name" maxlength="100"
                                                        class="form-control" value="{$plan.plan_name}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Speed <span class="required">*
                                                    </span></label>
                                                <div class="col-md-2">
                                                    <input type="number" max="10000" required required name="speed"
                                                        class="form-control" value="{$plan.speed}">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="speed_type" style="width: 100px;" required
                                                        class="form-control" data-placeholder="Select...">
                                                        {if $plan.speed_type=='MBPS'}
                                                            <option selected="" value=" MBPS">MBPS</option>
                                                            <option value=" KBPS">KBPS</option>
                                                        {else}
                                                            <option value=" MBPS">MBPS</option>
                                                            <option selected value=" KBPS">KBPS</option>
                                                        {/if}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Data <span
                                                        style="font-size: 10px;">(Keep 0 For Unlimited)</span><span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-2">
                                                    <input type="number" max="100000" title="Keep 0 for Unlimited"
                                                        min="0" required name="data" class="form-control"
                                                        value="{$plan.data}">
                                                </div>
                                                <div class="col-md-2">
                                                    <select name="data_limit_type" style="width: 100px;" required
                                                        class="form-control" data-placeholder="Select...">

                                                        <option {if $plan.data_limit_type==' GB'} selected {/if}
                                                            value=" GB">GB</option>
                                                        <option {if $plan.data_limit_type==' MB'} selected {/if}
                                                            value=" MB">MB</option>
                                                        <option {if $plan.data_limit_type=='Hrs'} selected {/if}
                                                            value=" Hrs">Hrs</option>
                                                        <option {if $plan.data_limit_type=='Unlimited'} selected {/if}
                                                            value="Unlimited">Unlimited</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Duration (Month) <span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="number" min="1" max="1000" required name="duration"
                                                        class="form-control" value="{$plan.duration}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Price (Inclusive tax) <span
                                                        class="required">*
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <input type="number" required name="price" max="100000.00"
                                                        class="form-control" value="{$plan.price}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Tax1 <span class="required">
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <select name="tax1_id" class="form-control "
                                                        data-placeholder="Select...">
                                                        <option value="">Select Tax</option>
                                                        {foreach from=$tax item=v}
                                                        <option {if $plan.tax1_id==$v.tax_id} selected  {/if} value="{$v.tax_id}">{$v.tax_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-4">Tax2 <span class="required">
                                                    </span></label>
                                                <div class="col-md-4">
                                                    <select name="tax2_id" class="form-control "
                                                        data-placeholder="Select...">
                                                        <option value="">Select Tax</option>
                                                        {foreach from=$tax item=v}
                                                            <option {if $plan.tax2_id==$v.tax_id} selected  {/if} value="{$v.tax_id}">{$v.tax_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    <a href="/merchant/plan/viewlist"  class="btn btn-default">Cancel</a>
                                                    <input type="hidden" name="plan_id" value="{$plan_id}" />
                                                    <input type="submit" value="Save" class="btn blue"/>
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
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->
</div>