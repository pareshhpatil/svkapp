
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
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
            <div class="">
                <div class="portlet-body">
                    <form action="/merchant/template/saveupdate" method="post" enctype="multipart/form-data">
                        {CSRF::create('template_update')}
                        <div class="form-body">


                            <div class="row mb-2">     
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <div class="col-md-8 no-padding">
                                            <label class="control-label">Template name <span class="required">* </span> </label> 
                                            <div class="input-icon right">
                                                <input type="text" name="template_name"  {$validate.temp_name} value="{$template_name}" class="form-control"  placeholder="Enter a name for your template"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {if count($billing_profile)>0}
                                    {if count($billing_profile)>1}
                                        <div class="col-md-6">
                                            <div class="form-group " >
                                                <div class="col-md-8  no-padding">
                                                    <label class="control-label">Billing profile<span class="required"> </span> </label> 
                                                    <div class="input-icon right">
                                                        <select onchange="setProfileDetails();" class="form-control" id="profile_id" name="billing_profile_id">
                                                            <option value="">Select..</option>
                                                            {foreach from=$billing_profile item=v}
                                                                <option {if $info.profile_id==$v.id} selected {/if} value="{$v.id}">{$v.profile_name} {$v.gst_number}</option>
                                                            {/foreach}
                                                        </select>    	
                                                    </div>	
                                                </div>	
                                            </div>
                                        </div>
                                    {else}
                                        <input type="hidden" name="billing_profile_id" value="{$billing_profile.{0}.id}">
                                    {/if}
                                {/if}

                            </div>

                            <div class="portlet  col-md-12">

                                <div class="portlet-body">
                                    <div class="row">     
                                        <div class="col-md-6 fileinput fileinput-new" data-provides="fileinput">
                                            <h4 class="form-section">Upload company logo</h4>
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 130px;">
                                                <img class="img-responsive templatelogo" id="img_logo" src="{if {$image_path}!=''}/uploads/images/logos/{$image_path}{else}/images/logo.gif{/if}" alt=""/>
                                            </div>
                                            <div  class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                            </div>
                                            <div>
                                                <span class="btn btn-sm default btn-file">
                                                    <span class="fileinput-new">
                                                        Select logo </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input onchange="return validatefilesize(500000, 'imgupload');" id="imgupload" type="file" accept="image/*"  name="uploaded_file">
                                                </span>
                                                <a href="javascript:;" id="imgdismiss" class="btn-sm btn default fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6" >
                                            <h4 class="form-section">Header Details <a  onclick="" data-toggle="modal"  href="#main_header" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Manage field </a></h4>
                                            <ul id="new_main_header_column">
                                                {foreach from=$main_header item=v}
                                                    {if $v.column_id>0}
                                                        {if $v.datatype=='money'} 
                                                            {$icon = 'fa-inr'}
                                                        {elseif $v.datatype== 'text'} 
                                                            {$icon = 'fa-font'}
                                                        {elseif $v.datatype== 'number'} 
                                                            {$icon = 'fa-sort-numeric-asc'}
                                                        {elseif $v.datatype== 'primary'} 
                                                            {$icon = 'fa-anchor'}
                                                        {elseif $v.datatype== 'textarea'} 
                                                            {$icon = 'fa-file-text-o'}
                                                        {elseif $v.datatype== 'date'} 
                                                            {$icon = 'fa-calendar'}
                                                        {elseif $v.datatype== 'email'} 
                                                            {$icon = 'fa-envelope'}
                                                        {elseif $v.datatype== 'link'} 
                                                            {$icon = 'fa-link'}
                                                        {elseif $v.datatype== 'pan'} 
                                                            {$icon = 'fa-credit-card'}
                                                        {/if}
                                                        <li class="ui-state-default" id="main_h{$v.id}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                            <div class="form-group" style="display: initial;" >
                                                                <div class="col-md-6">
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn">
                                                                            <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                        </span>
                                                                        <input type="text" name="main_header_name[]" readonly value="{$v.column_name}" readonly class="form-control input-sm" maxlength="40" placeholder="{$v.name}   ">
                                                                        <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                        <input name="exist_function_id[]" type="hidden" value="-1" />
                                                                        <input name="existheaderdatatype[]" type="hidden" value="{$v.datatype}" />
                                                                        <input name="exist_function_param[]" type="hidden"  value="" />
                                                                        <input name="exist_function_val[]" type="hidden"  value="" />
                                                                        <input name="exist_header_default[]" id="main_header_default{$v.id}" type="hidden" value="{$v.default_column_value}">

                                                                        <span class="input-group-btn">
                                                                            <div class="btn default btn-sm"> <i id="icon76" class="fa {$icon}"></i></div>
                                                                        </span>
                                                                    </div>
                                                                </div>	
                                                                <div class="col-md-6">


                                                                    {if $v.is_mandatory=='0'}
                                                                        <div class="input-group">
                                                                            {if $v.datatype=="textarea"}
                                                                                <textarea class="form-control input-sm" readonly="" id="profile{$v.id}" name="rightcount"></textarea><input name="rightcount" type="hidden">
                                                                            {else}
                                                                                <input type="text" class="form-control input-sm" id="profile{$v.id}" readonly name="rightcount">
                                                                            {/if}
                                                                            <span class="input-group-addon " onclick="uncheckbox('main_header_id{$v.id}');"><i class="fa fa-minus-circle"></i>
                                                                            </span> 
                                                                        </div>
                                                                    {else}
                                                                        {if $v.datatype=="textarea"}
                                                                            <textarea class="form-control input-sm" readonly="" id="profile{$v.id}" name="rightcount"></textarea><input name="rightcount" type="hidden">
                                                                        {else}
                                                                            <input type="text" class="form-control input-sm" id="profile{$v.id}" readonly name="rightcount">
                                                                        {/if}
                                                                    {/if}
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>				
                                                            </div>
                                                        </li>
                                                    {/if}
                                                {/foreach}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="portlet  col-md-12">

                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-6" id="newHeaderleft">
                                            <h4 class="form-section">Customer Details <a  onclick="" data-toggle="modal"  href="#customer" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Manage field </a></h4>
                                            <ul id="new_customer_column">
                                                {foreach from=$customer_column_list item=v}

                                                    {if $v.datatype=="textarea"}
                                                        {$text ='<textarea class="form-control input-sm" readonly="" name="rightcount"></textarea><input name="rightcount" type="hidden">'}
                                                    {else}
                                                        {$text ='<input type="text" class="form-control input-sm" readonly name="rightcount">'}
                                                    {/if}
                                                    {$icon = ''}
                                                    {if $v.datatype=='money'} 
                                                        {$icon = 'fa-inr'}
                                                    {elseif $v.datatype== 'text'} 
                                                        {$icon = 'fa-font'}
                                                    {elseif $v.datatype== 'number'} 
                                                        {$icon = 'fa-sort-numeric-asc'}
                                                    {elseif $v.datatype== 'mobile'} 
                                                        {$icon = 'fa-sort-numeric-asc'}
                                                    {elseif $v.datatype== 'email'} 
                                                        {$icon = 'fa-envelope'}
                                                    {elseif $v.datatype== 'primary'} 
                                                        {$icon = 'fa-anchor'}
                                                    {elseif $v.datatype== 'textarea'} 
                                                        {$icon = 'fa-file-text-o'}
                                                    {elseif $v.datatype== 'date'} 
                                                        {$icon = 'fa-calendar'}
                                                    {elseif $v.datatype== 'time'} 
                                                        {$icon = 'fa-clock-o'}
                                                    {/if}
                                                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                        <div class="form-group" style="display: initial;" id="{if $v.save_table_name=='customer'}customer{else}custom{/if}{$v.customer_column_id}">
                                                            <input name="exist_customer_column_id[]"  type="hidden" value="{$v.column_id}" />
                                                            <div class="col-md-6">
                                                                <div class="input-group">
                                                                    <span class="input-group-btn">
                                                                        <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                    </span>
                                                                    <input type="text" readonly value="{$v.column_name}" name="customer_column_name[]" class="form-control input-sm" maxlength="40">
                                                                    <span class="input-group-btn">
                                                                        <div class="btn default btn-sm"> <i id="icon76" class="fa {$icon}"></i></div>
                                                                    </span>
                                                                </div>
                                                            </div>	
                                                            <div class="col-md-6">
                                                                {if $v.delete_allow==1}
                                                                    <div class="input-group">
                                                                        {$text}
                                                                        <span class="input-group-addon " id="{$v.column_id}" onclick="uncheckbox('{if $v.save_table_name=='customer'}cust_id{else}custom_id{/if}{$v.customer_column_id}');">
                                                                            <i class="fa fa-minus-circle"></i>
                                                                        </span>
                                                                    </div>
                                                                {else}
                                                                    {$text}
                                                                {/if}
                                                                <span class="help-block">
                                                                </span>
                                                            </div>				
                                                        </div>
                                                    </li>
                                                {/foreach}
                                            </ul>

                                        </div>
                                        <div class="col-md-1"></div>

                                        <div class="col-md-6">
                                            <h4 class="form-section">Billing Details <a  onclick="reset();" data-toggle="modal"  href="#custom" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a></h4>
                                            <ul id="newHeaderright">
                                                {foreach from=$header item=v}
                                                    {if $v.position=='R'}

                                                        {if $v.datatype=="textarea"}
                                                            {$text ='<textarea class="form-control input-sm" readonly="" name="rightcount"></textarea>'}
                                                        {else}
                                                            {$text ='<input type="text" class="form-control input-sm" readonly name="rightcount">'}
                                                        {/if}

                                                        {if $v.datatype=='money'} 
                                                            {$icon = 'fa-inr'}
                                                        {elseif $v.datatype== 'text'} 
                                                            {$icon = 'fa-font'}
                                                        {elseif $v.datatype== 'number'} 
                                                            {$icon = 'fa-sort-numeric-asc'}
                                                        {elseif $v.datatype== 'primary'} 
                                                            {$icon = 'fa-anchor'}
                                                        {elseif $v.datatype== 'percent'} 
                                                            {$icon = 'fa-rub'}
                                                        {elseif $v.datatype== 'textarea'} 
                                                            {$icon = 'fa-file-text-o'}
                                                        {elseif $v.datatype== 'date'} 
                                                            {$icon = 'fa-calendar'}
                                                        {elseif $v.datatype== 'time'} 
                                                            {$icon = 'fa-clock-o'}
                                                        {elseif $v.datatype== 'link'} 
                                                            {$icon = 'fa-link'}
                                                        {/if}

                                                        {if $v.table_name=="request"}
                                                            {$readonly ='readonly'}
                                                        {else}
                                                            {$readonly =''}
                                                        {/if}

                                                        <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                            <div class="form-group" style="display: initial;" id="exist{$v.column_id}">

                                                                {if $v.delete_allow==1 || $v.datatype=='date'}
                                                                    <div class="col-md-6">
                                                                        <div class="input-group ">
                                                                            <span class="input-group-btn">
                                                                                <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                            </span>

                                                                            <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                            <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                            <input name="exist_function_id[]" id="function_id{$v.column_id}" type="hidden" value="{$v.function_id}" />
                                                                            <input name="exist_function_param[]" type="hidden" id="function_param{$v.column_id}" value="{if $v.param!=''}{$v.param}{else}manually_entered{/if}" />
                                                                            <input name="exist_function_val[]" type="hidden" id="function_val{$v.column_id}" value="{$v.value}" />
                                                                            <input name="existheaderdatatype[]" type="hidden" id="datatype{$v.column_id}" value="{$v.datatype}" />
                                                                            <span class="input-group-btn">
                                                                                <span class="btn default btn-sm">
                                                                                    <i id="icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                </span>

                                                                                <a class="btn default btn-sm" data-toggle="modal" id="edit{$v.column_id}" onclick="editclick({$v.column_id}, '{$v.datatype}',{$v.function_id}, '{$readonly}');" href="#custom">
                                                                                    <i class="fa fa-edit"></i></a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                {else}
                                                                    <div class="col-md-6">
                                                                        <div class="input-group ">
                                                                            <span class="input-group-btn">
                                                                                <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                            </span>

                                                                            <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                            <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                            <input name="exist_function_param[]" type="hidden" id="function_param{$v.column_id}" value="{$v.param}" />
                                                                            <input name="exist_function_val[]" type="hidden" id="function_val{$v.column_id}" value="{$v.value}" />  
                                                                            <input name="exist_function_id[]" id="function_id{$v.column_id}" type="hidden" value="{$v.function_id}" />
                                                                            <input name="existheaderdatatype[]" type="hidden" id="datatype{$v.column_id}" value="{$v.datatype}" />

                                                                            <span class="input-group-btn">
                                                                                <span class="btn default btn-sm">
                                                                                    <i id="icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                </span>

                                                                            </span>
                                                                        </div>	
                                                                    </div>	
                                                                {/if}
                                                                <div class="col-md-6">
                                                                    {if $v.delete_allow==1}
                                                                        <div class="input-group">
                                                                            <div id="datatypediv{$v.column_id}">
                                                                                {$text}
                                                                            </div>
                                                                            <span class="input-group-addon " id="{$v.column_id}" onclick="removedivexist(this.id)">
                                                                                <i class="fa fa-minus-circle"></i>
                                                                            </span>
                                                                        </div>
                                                                    {else}
                                                                        {$text}
                                                                    {/if}
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>			
                                                            </div>
                                                        </li>
                                                    {/if}
                                                {/foreach}
                                            </ul>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            {if $template_type=='travel_car_booking'}
                                <div class="portlet  col-md-12">

                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-6" id="newHeaderleft">
                                                <h4 class="form-section">&nbsp; <a  onclick="bds_reset('L');" data-toggle="modal"  href="#bds" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a></h4>
                                                <ul id="bdsleft">
                                                    {foreach from=$bds_column item=v}
                                                        {if $v.position=='L'}

                                                            {if $v.datatype=="textarea"}
                                                                {$text ='<textarea class="form-control input-sm" readonly="" name="rightcount"></textarea>'}
                                                            {else}
                                                                {$text ='<input type="text" class="form-control input-sm" readonly name="rightcount">'}
                                                            {/if}

                                                            {if $v.datatype=='money'} 
                                                                {$icon = 'fa-inr'}
                                                            {elseif $v.datatype== 'text'} 
                                                                {$icon = 'fa-font'}
                                                            {elseif $v.datatype== 'number'} 
                                                                {$icon = 'fa-sort-numeric-asc'}
                                                            {elseif $v.datatype== 'primary'} 
                                                                {$icon = 'fa-anchor'}
                                                            {elseif $v.datatype== 'percent'} 
                                                                {$icon = 'fa-rub'}
                                                            {elseif $v.datatype== 'textarea'} 
                                                                {$icon = 'fa-file-text-o'}
                                                            {elseif $v.datatype== 'date'} 
                                                                {$icon = 'fa-calendar'}
                                                            {elseif $v.datatype== 'time'} 
                                                                {$icon = 'fa-clock-o'}
                                                            {elseif $v.datatype== 'link'} 
                                                                {$icon = 'fa-link'}
                                                            {/if}

                                                            {if $v.table_name=="request"}
                                                                {$readonly ='readonly'}
                                                            {else}
                                                                {$readonly =''}
                                                            {/if}

                                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                <div class="form-group" style="display: initial;" id="existbds_{$v.column_id}">

                                                                    {if $v.delete_allow==1}
                                                                        <div class="col-md-6">
                                                                            <div class="input-group ">
                                                                                <span class="input-group-btn">
                                                                                    <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                                </span>
                                                                                <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="bds_columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                                <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                                <input name="exist_function_id[]"  type="hidden" value="{$v.function_id}" />
                                                                                <input name="exist_function_param[]" type="hidden"  value="{if $v.param!=''}{$v.param}{else}manually_entered{/if}" />
                                                                                <input name="exist_function_val[]" type="hidden"  value="{$v.value}" />
                                                                                <input name="existheaderdatatype[]" type="hidden" id="bds_datatype{$v.column_id}" value="{$v.datatype}" />
                                                                                <span class="input-group-btn">
                                                                                    <span class="btn default btn-sm">
                                                                                        <i id="bds_icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                    </span>

                                                                                    <a class="btn default btn-sm" data-toggle="modal" id="bds_edit{$v.column_id}" onclick="bds_editclick({$v.column_id}, '{$v.datatype}',{$v.function_id}, '{$readonly}');" href="#bds">
                                                                                        <i class="fa fa-edit"></i></a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    {else}
                                                                        <div class="col-md-6">
                                                                            <div class="input-group ">
                                                                                <span class="input-group-btn">
                                                                                    <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                                </span>
                                                                                <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="bds_columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                                <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                                <input name="exist_function_param[]" type="hidden"  value="{$v.param}" />
                                                                                <input name="exist_function_val[]" type="hidden"  value="{$v.value}" />  
                                                                                <input name="exist_function_id[]" type="hidden" value="{$v.function_id}" />
                                                                                <input name="existheaderdatatype[]" type="hidden" id="bds_datatype{$v.column_id}" value="{$v.datatype}" />
                                                                                <span class="input-group-btn">
                                                                                    <span class="btn default btn-sm">
                                                                                        <i id="bds_icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                    </span>

                                                                                </span>
                                                                            </div>	
                                                                        </div>	
                                                                    {/if}
                                                                    <div class="col-md-6">
                                                                        {if $v.delete_allow==1}
                                                                            <div class="input-group">
                                                                                <div id="bds_datatypediv{$v.column_id}">
                                                                                    {$text}
                                                                                </div>
                                                                                <span class="input-group-addon " id="bds_{$v.column_id}" onclick="removedivexist(this.id)">
                                                                                    <i class="fa fa-minus-circle"></i>
                                                                                </span>
                                                                            </div>
                                                                        {else}
                                                                            {$text}
                                                                        {/if}
                                                                        <span class="help-block">
                                                                        </span>
                                                                    </div>			
                                                                </div>
                                                            </li>
                                                        {/if}
                                                    {/foreach}
                                                </ul>

                                            </div>
                                            <div class="col-md-1"></div>

                                            <div class="col-md-6">
                                                <h4 class="form-section">Billing Details <a  onclick="bds_reset('R');" data-toggle="modal"  href="#bds" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a></h4>
                                                <ul id="bdsright">
                                                    {foreach from=$bds_column item=v}
                                                        {if $v.position=='R'}

                                                            {if $v.datatype=="textarea"}
                                                                {$text ='<textarea class="form-control input-sm" readonly="" name="rightcount"></textarea>'}
                                                            {else}
                                                                {$text ='<input type="text" class="form-control input-sm" readonly name="rightcount">'}
                                                            {/if}

                                                            {if $v.datatype=='money'} 
                                                                {$icon = 'fa-inr'}
                                                            {elseif $v.datatype== 'text'} 
                                                                {$icon = 'fa-font'}
                                                            {elseif $v.datatype== 'number'} 
                                                                {$icon = 'fa-sort-numeric-asc'}
                                                            {elseif $v.datatype== 'primary'} 
                                                                {$icon = 'fa-anchor'}
                                                            {elseif $v.datatype== 'percent'} 
                                                                {$icon = 'fa-rub'}
                                                            {elseif $v.datatype== 'textarea'} 
                                                                {$icon = 'fa-file-text-o'}
                                                            {elseif $v.datatype== 'date'} 
                                                                {$icon = 'fa-calendar'}
                                                            {elseif $v.datatype== 'time'} 
                                                                {$icon = 'fa-clock-o'}
                                                            {elseif $v.datatype== 'link'} 
                                                                {$icon = 'fa-link'}
                                                            {/if}

                                                            {if $v.table_name=="request"}
                                                                {$readonly ='readonly'}
                                                            {else}
                                                                {$readonly =''}
                                                            {/if}

                                                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                <div class="form-group" style="display: initial;" id="bds_exist{$v.column_id}">
                                                                    {if $v.delete_allow==1}
                                                                        <div class="col-md-6">
                                                                            <div class="input-group ">
                                                                                <span class="input-group-btn">
                                                                                    <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                                </span>

                                                                                <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="bds_columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                                <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                                <input name="exist_function_id[]" type="hidden" value="{$v.function_id}" />
                                                                                <input name="exist_function_param[]" type="hidden" value="{if $v.param!=''}{$v.param}{else}manually_entered{/if}" />
                                                                                <input name="exist_function_val[]" type="hidden"  value="{$v.value}" />
                                                                                <input name="existheaderdatatype[]" type="hidden" id="bds_datatype{$v.column_id}" value="{$v.datatype}" />
                                                                                <span class="input-group-btn">
                                                                                    <span class="btn default btn-sm">
                                                                                        <i id="exist_icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                    </span>

                                                                                    <a class="btn default btn-sm" data-toggle="modal" id="bds_edit{$v.column_id}" onclick="bds_editclick({$v.column_id}, '{$v.datatype}',{$v.function_id}, '{$readonly}');" href="#bds">
                                                                                        <i class="fa fa-edit"></i></a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    {else}
                                                                        <div class="col-md-6">
                                                                            <div class="input-group ">
                                                                                <span class="input-group-btn">
                                                                                    <div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div>
                                                                                </span>
                                                                                <input type="text" name="headercolumn[]" required  Value="{$v.column_name}" {$readonly} id="bds_columnname{$v.column_id}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                                                                <input name="headerid[]" type="hidden" value="{$v.column_id}" />
                                                                                <input name="exist_function_param[]" type="hidden"  value="{$v.param}" />
                                                                                <input name="exist_function_val[]" type="hidden"  value="{$v.value}" />  
                                                                                <input name="exist_function_id[]"  type="hidden" value="{$v.function_id}" />
                                                                                <input name="existheaderdatatype[]" type="hidden" id="bds_datatype{$v.column_id}" value="{$v.datatype}" />

                                                                                <span class="input-group-btn">
                                                                                    <span class="btn default btn-sm">
                                                                                        <i id="exist_icon{$v.column_id}" class="fa {$icon}"></i>
                                                                                    </span>

                                                                                </span>
                                                                            </div>	
                                                                        </div>	
                                                                    {/if}
                                                                    <div class="col-md-6">
                                                                        {if $v.delete_allow==1}
                                                                            <div class="input-group">
                                                                                <div id="exist_datatypediv{$v.column_id}">
                                                                                    {$text}
                                                                                </div>
                                                                                <span class="input-group-addon " id="bds_{$v.column_id}" onclick="removedivexist(this.id)">
                                                                                    <i class="fa fa-minus-circle"></i>
                                                                                </span>
                                                                            </div>
                                                                        {else}
                                                                            {$text}
                                                                        {/if}
                                                                        <span class="help-block">
                                                                        </span>
                                                                    </div>			
                                                                </div>
                                                            </li>
                                                        {/if}
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        </div>	


                        <div class="modal fade" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Field property</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button>
                                                        You have some form errors. Please check below.
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Datatype</label>
                                                        <div class="col-md-5">
                                                            <select class="form-control input-sm" id="datatype" onchange="datatypeChange(this.value);" data-placeholder="Select type">
                                                                <option value="text">Text</option>
                                                                <option value="textarea">Text area</option>
                                                                <option value="number">Number</option>
                                                                <option value="money">Money</option>
                                                                <option value="percent">Percentage</option>
                                                                <option value="date">Date</option>
                                                                <option value="primary">Primary</option>
                                                            </select>
                                                            <span class="help-block">
                                                            </span>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" id="bank_transaction_no" >
                                                        <label for="inputPassword12" class="col-md-5 control-label">Column name</label>
                                                        <div class="col-md-5">
                                                            <input type="hidden" value="new" id="custom_column_id">
                                                            <input class="form-control form-control-inline input-sm" id="custom_column_name" type="text" value="" placeholder="Column name"/>
                                                            <input id="readonly" type="hidden" value=""/>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Functions</label>
                                                        <div class="col-md-5">
                                                            <select class="form-control input-sm"  onchange="getMapping(this.value);" id="column_function"   data-placeholder="Select function">

                                                            </select>
                                                            <span class="help-block">
                                                            </span>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div id="mappinddiv" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="inputPassword12" id="colname" class="col-md-5 control-label">Type</label>
                                                            <div class="col-md-5">
                                                                <select class="form-control input-sm" name="mapping_param" id="mapping_param"   data-placeholder="Select">

                                                                </select>
                                                                <span class="help-block">
                                                                </span>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="mappvalue">
                                                            <label for="inputPassword12" class="col-md-5 control-label" id="valname">Days</label>
                                                            <div class="col-md-5" id="mappval">
                                                                <input type="text" name="mapping_value" id="mapping_value" class="form-control">
                                                                <span class="help-block">
                                                                </span>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                            <div class="col-md-1 no-margin no-padding" id="new_seq_number_btn" style="display: none;">
                                                                <a data-toggle="modal" title="New invoice number" onclick="newInvSeq();" class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                                            </div>
                                                        </div>
                                                        <div id="auto_inv_div" style="display: none;">
                                                            <div class="form-group" >
                                                                <label for="inputPassword12" class="col-md-5 control-label">Add new prefix</label>
                                                                <div class="col-md-4">
                                                                    <input type="text"  placeholder="Add prefix" id="prefix" class="form-control input-sm">
                                                                    <span class="help-block">
                                                                    </span>

                                                                </div>
                                                                <div class="col-md-3 no-padding">
                                                                    <input type="number" value="0" placeholder="Last no." id="current_number" class="form-control input-sm">
                                                                    <span class="help-block">
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="form-group" >
                                                                <label for="inputPassword12" class="col-md-5 control-label" >&nbsp;</label>
                                                                <div class="col-md-6">
                                                                    <button type="button" onclick="saveSequence();" class="btn btn-sm blue">Save sequence</button>
                                                                    <button type="button" class="btn default btn-sm" onclick="document.getElementById('auto_inv_div').style.display = 'none';">Cancel</button>
                                                                    <span class="help-block">
                                                                    </span>
                                                                    <p id="seq_error" style="color: red;"></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="addHeader();" class="btn blue">Save</button>
                                        <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <div class="modal fade" id="bds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Field property</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-body">
                                                    <div class="alert alert-danger display-hide">
                                                        <button class="close" data-close="alert"></button>
                                                        You have some form errors. Please check below.
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputPassword12" class="col-md-5 control-label">Datatype</label>
                                                        <div class="col-md-5">
                                                            <select class="form-control input-sm" id="bds_datatype" data-placeholder="Select type">
                                                                <option value="text">Text</option>
                                                                <option value="textarea">Text area</option>
                                                                <option value="number">Number</option>
                                                                <option value="money">Money</option>
                                                                <option value="percent">Percentage</option>
                                                                <option value="date">Date</option>
                                                                <option value="time">Time</option>
                                                            </select>
                                                            <span class="help-block">
                                                            </span>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" >
                                                        <label for="inputPassword12" class="col-md-5 control-label">Column name</label>
                                                        <div class="col-md-5">
                                                            <input type="hidden" value="new" id="bds_custom_column_id">
                                                            <input class="form-control form-control-inline input-sm" id="bds_custom_column_name" type="text" value="" placeholder="Column name"/>
                                                            <input id="bds_readonly" type="hidden" value=""/>
                                                            <input id="bds_position" type="hidden" value=""/>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="addBDS();" class="btn blue">Save</button>
                                        <button type="button" class="btn default" id="bds_closebutton" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <script>
                            var column_function_json = '{$functionsJSON}';
                            var column_mapping = '{$functionsMapping}';
                            var invoice_numbers = '{$invoice_numbers}';
                            //get_column_function('text');
                        </script>


                        <div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Select display column</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="table-scrollable">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="td-c">
                                                                    Column name
                                                                </th>
                                                                <th class="td-c">
                                                                    Action?
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {foreach from=$customer_column item=v}
                                                                <tr>
                                                                    <td class="td-c">
                                                                        <div>{$v.column_name}</div>
                                                                    </td>
                                                                    <td class="td-c">

                                                                        {if $v.is_default}
                                                                            <i class="fa fa-check"></i>
                                                                        {else}
                                                                            <input type="checkbox" {if $v.is_checked}checked{/if}  id="cust_id{$v.id}" value="{$v.id}"  onchange="AddCustomerColumn('customer',{$v.id}, '{$v.column_name}', '{$v.datatype}');"/>
                                                                        {/if}

                                                                    </td>
                                                                </tr>
                                                            {/foreach}
                                                            {foreach from=$custom_column item=v}
                                                                <tr>
                                                                    <td class="td-c">
                                                                        <div>{$v.column_name}</div>
                                                                    </td>
                                                                    <td class="td-c">

                                                                        {if $v.is_default}
                                                                            <i class="fa fa-check"></i>
                                                                        {else}
                                                                            <input type="checkbox" {if $v.is_checked}checked{/if}  id="custom_id{$v.column_id}" value="{$v.column_id}"  onchange="AddCustomerColumn('custom',{$v.column_id}, '{$v.column_name}', '{$v.column_datatype}');"/>
                                                                        {/if}

                                                                    </td>
                                                                </tr>
                                                            {/foreach}
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <div class="modal fade" id="main_header" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Select display column</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="table-scrollable">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="td-c">
                                                                    Column name
                                                                </th>
                                                                <th class="td-c">
                                                                    Display 
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {foreach from=$main_header item=v}
                                                                <tr>
                                                                    <td class="td-c">
                                                                        <div>{$v.column_name}</div>
                                                                    </td>
                                                                    <td class="td-c">

                                                                        {if $v.is_mandatory}
                                                                            <i class="fa fa-check"></i>
                                                                        {else}
                                                                            <input type="checkbox" {if $v.is_default}checked{/if}  id="main_header_id{$v.id}" value="{$v.id}"  onchange="AddMainHeaderColumn({$v.id}, '{$v.column_name}', '{$v.datatype}');"/>
                                                                        {/if}
                                                                    </td>
                                                                </tr>
                                                            {/foreach}
                                                        </tbody>
                                                    </table>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>