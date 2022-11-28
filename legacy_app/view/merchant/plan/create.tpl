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
                    <form action="/merchant/plan/plansave" method="post" class="form-horizontal form-row-sepe">
                        {CSRF::create('plan_create')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
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
                                                            <option value="{$v.source}">{$v.source}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            {/if}
                                            {if !empty($source)}
                                                <div id="sonew" style="display: none;">
                                                {else}
                                                    <div id="sonew">
                                                    {/if}
                                                    <input type="text" placeholder="New source name" id="sotext"
                                                        name="source_name" {$validate.name} class="form-control"
                                                        value="{$post.source_name}">
                                                </div>

                                            </div>
                                            <div class="col-md-1 no-margin no-padding">
                                                {if !empty($source)}
                                                    <a id="solinkadd" maxlength="100" title="Add new source"
                                                        onclick="newsource(1);" class="btn green">Add new <i
                                                            class="fa fa-plus"></i></a>
                                                    <a id="solinkremove" style="display: none;" title="Cancel"
                                                        onclick="newsource(0);" class="btn red">Cancel <i
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
                                                                <option value="{$v.category}">{$v.category}</option>
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
                                                            value="{$post.category_name}">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 no-margin no-padding">
                                                    {if !empty($category)}
                                                        <a id="catlinkadd" title="Add new source" onclick="newcategory(1);"
                                                            class="btn green">Add new <i class="fa fa-plus"></i></a>
                                                        <a id="catlinkremove" style="display: none;" title="Cancel"
                                                            onclick="newcategory(0);" class="btn red">Cancel <i
                                                                class="fa fa-remove"></i></a>
                                                    {/if}
                                                </div>
                                            </div>


                                            <h3>Add plans <span style="font-size: 12px;color:red;">* All fields are
                                                    mandatory</span><a href="javascript:;" onclick="addNewPlan();"
                                                    class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add
                                                    new row </a></h3>

                                            <div class="table-scrollable">
                                                <table class="table table-bordered table-hover" id="particular_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="td-c">
                                                                Plan name
                                                            </th>
                                                            <th class="td-c" colspan="2">
                                                                Speed
                                                            </th>
                                                            <th class="td-c" colspan="2">
                                                                Data limit <span style="font-size: 10px;">(Keep 0 For
                                                                    Unlimited)</span>
                                                            </th>
                                                            <th class="td-c">
                                                                Duration <span style="font-size: 10px;">(Month)</span>
                                                            </th>
                                                            <th class="td-c">
                                                                Price (Inclusive tax)
                                                            </th>
                                                            <th class="td-c">
                                                                Tax1 
                                                            </th>
                                                            <th class="td-c">
                                                                Tax2 
                                                            </th>
                                                            <th class="td-c">

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    {$int=1}
                                                    <tbody id="new_plan">
                                                        <tr>
                                                            <td>
                                                                <div class="input-icon right">
                                                                    <input type="text" maxlength="100" required value=""
                                                                        name="plan_name[]" class="form-control input-sm"
                                                                        placeholder="Add plan name">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" max="10000" required
                                                                    placeholder="" style="width: 70px;" name="speed[]"
                                                                    class="form-control input-sm">
                                                            </td>
                                                            <td>
                                                                <select name="speed_type[]" style="width: 100px;"
                                                                    required class="form-control input-sm"
                                                                    data-placeholder="Select...">
                                                                    <option value=" MBPS">MBPS</option>
                                                                    <option value=" KBPS">KBPS</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" max="100000" aria-required="true"
                                                                    title="Keep 0 for Unlimited" min="0" required
                                                                    placeholder="" style="width: 70px;" value="0"
                                                                    name="data_limit[]" class="form-control input-sm">
                                                            </td>
                                                            <td>
                                                                <select name="data_limit_type[]" style="width: 100px;"
                                                                    required class="form-control input-sm"
                                                                    data-placeholder="Select...">
                                                                    <option value=" GB">GB</option>
                                                                    <option value=" MB">MB</option>
                                                                    <option value=" Hrs">Hrs</option>
                                                                    <option value="Unlimited">Unlimited</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" required min="1" max="1000"
                                                                    placeholder="Duration Month" name="duration[]"
                                                                    class="form-control input-sm">
                                                            </td>
                                                            <td>
                                                                <input type="number" placeholder="Price in Rs."
                                                                    max="100000" required name="price[]"
                                                                    class="form-control input-sm">
                                                            </td>
                                                            <td>
                                                            <select name="tax1_id[]" class="form-control input-sm" data-placeholder="Select...">
                                                            <option value="">Select Tax</option>
                                                            {foreach from=$tax item=v}
                                                                <option value="{$v.tax_id}">{$v.tax_name}</option>
                                                            {/foreach}
                                                        </select>
                                                            </td>
                                                            <td>
                                                            <select name="tax2_id[]" class="form-control input-sm" data-placeholder="Select...">
                                                            <option value="">Select Tax</option>
                                                            {foreach from=$tax item=v}
                                                                <option value="{$v.tax_id}">{$v.tax_name}</option>
                                                            {/foreach}
                                                        </select>
                                                            </td>
                                                            <td>
                                                                <a onclick="$(this).closest('tr').remove();"
                                                                    class="btn btn-sm red"> <i class="fa fa-times"> </i>
                                                                    Delete</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>

                                        </div>
                                        <div style="display: none;" id="taxlist">
                                            <select name="tax_id" class="form-control input-sm" data-placeholder="Select...">
                                                <option value="">Select Tax</option>
                                                {foreach from=$tax item=v}
                                                    <option value="{$v.tax_id}">{$v.tax_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>


                                    </div>
                                </div>
                                <!-- End profile details -->

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <button type="reset" onclick="return confirm('Are you sure?');" class="btn btn-default">Reset</button>
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