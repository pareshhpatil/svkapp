
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">{$title}</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            {if empty($templatelist)}
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Info!</strong>
                    <div class="media">
                        <p class="media-heading">You need to create a template before sending invoices. Please create a bill template using the Create template button below</p>
                        <p><a href="/merchant/template/newtemplate" class="btn blue">Create Template</a></p>
                    </div>

                </div>
            {else}
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet ">
                            <div class="portlet-body" data-tour="invoice-pick-format">
                                <form action="" method="post" id="template_create" class="form-horizontal form-row-sepe">
                                    <div class="form-body">
                                        <div class="form-group mb-0">
                                            <div class="col-md-12">
                                                <div class="col-md-2 pl-1 pr-0" style="width: auto;"><label class="control-label">Select an existing format<span class="required">* </span></label></div>
                                                <div class="col-md-3 pl-1"  style="padding-right: 0px;"><select name="selecttemplate"  required class="form-control select2me" data-placeholder="Select...">
                                                        <option value=""></option>
                                                        {foreach from=$templatelist item=v}
                                                            {if ($v.template_type!='travel_ticket_booking' && $v.template_type!='scan') || $subscription!=1}
                                                                {if {{$template_selected}=={$v.template_id}}}
                                                                    <option selected value="{$v.template_id}" selected>{$v.template_name}</option>
                                                                {else}
                                                                    <option value="{$v.template_id}">{$v.template_name}</option>
                                                                {/if}
                                                            {/if}
                                                        {/foreach}
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                                <div class="col-md-7 pl-1" style="width: auto;">
                                                    <button type="submit" class="btn blue"> Select format</button>
                                                    <a id="edit_templte" href="{if $template_selected!=''}/merchant/template/update/{$info.template_id}{else}/merchant/template/viewlist{/if}" class="btn green "> Edit format </a>
                                                    <a href="/merchant/template/newtemplate" class="btn btn-link "> Create a new format </a>
                                                    <input type="hidden" name="request_type" value="{$request_type}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                {/if}

            </div>
        </div>
    </div>
    {if $template_selected==''}
        <div class="row" id="preview_div" style="display: none;">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-body form">
                        <h4 class="form-section">Preview format</h4>
                        <div id="preview">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}


    <!-- BEGIN SEARCH CONTENT-->


