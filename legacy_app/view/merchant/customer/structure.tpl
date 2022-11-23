<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
            </div> 
        {/if}
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <form action="/merchant/customer/structuresave" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
                        {CSRF::create('customer_structure')}
                        <h3 class="form-section">System Fields
                            <div class="btn-group pull-right">
                                <button id="btnGroupVerticalDrop7" type="button" class="btn btn-xs green dropdown-toggle " data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-file-code-o"></i>  Download JSON <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                                    <li>
                                        <a href="/merchant/customer/saveCustomerJson/v1">
                                            Save Customer v1</a>
                                    </li>
                                    <li>
                                        <a href="/merchant/customer/updateCustomerJson/v1">
                                            Update Customer v1</a>
                                    </li>

                                </ul>
                            </div>
                        </h3>

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5"> {$customer_default_column.customer_code|default:$lang_title.customer_code}<span class="required">* </span></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="text" readonly value="" class="form-control" >
                                                <span class="input-group-btn">
                                                    <a data-toggle="modal" href="#customer" class="btn btn-icon-only green"><i class="icon-settings"> </i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-5"> {$customer_default_column.customer_name|default:$lang_title.customer_name}<span class="required">* </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-5"> {$customer_default_column.email|default:$lang_title.email}</label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5"> {$customer_default_column.mobile|default:$lang_title.mobile}</label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-5"> {$customer_default_column.address|default:$lang_title.address}<span class="required"> </span></label>
                                        <div class="col-md-5">
                                            <textarea readonly value="" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">{$customer_default_column.address|default:$lang_title.country}<span class="required"> </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">{$customer_default_column.state|default:$lang_title.state}<span class="required"> </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">{$customer_default_column.city|default:$lang_title.city}<span class="required"> </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5">{$customer_default_column.zipcode|default:$lang_title.zipcode}<span class="required"> </span></label>
                                        <div class="col-md-5">
                                            <input type="text" readonly value="" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End profile details -->
                        </div>
                        <h3 class="form-section">Custom Fields <a onclick="$('#custom_column_id').val('new');
                                $('#custom_column_name').val('');" data-toggle="modal" href="#custom" class="btn btn-sm green"> <i class="fa fa-plus"> </i> Add field </a></h3> 

                        <div  class="row">
                            <div class="col-md-6">
                                {foreach from=$column item=v}
                                    {if $v.position=='L'}
                                        {if $v.column_datatype=='money'} 
                                            {$icon = 'fa-inr'}
                                        {elseif $v.column_datatype== 'number'} 
                                            {$icon = 'fa-sort-numeric-asc'}
                                        {elseif $v.column_datatype== 'primary'} 
                                            {$icon = 'fa-anchor'}
                                        {elseif $v.column_datatype== 'textarea'} 
                                            {$icon = 'fa-file-text-o'}
                                        {elseif $v.column_datatype== 'date'} 
                                            {$icon = 'fa-calendar'}
                                        {else} 
                                            {$icon = 'fa-font'}
                                        {/if}
                                        <div id="exist{$v.column_id}" class="form-group">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input name="leftcount" type="hidden">
                                                    <input name="exist_col_id[]" type="hidden" value="{$v.column_id}">
                                                    <input type="text" name="exist_col_name[]" value="{$v.column_name}" id="columnname{$v.column_id}" class="form-control" aria-invalid="false">
                                                    <span class="input-group-btn">
                                                        <div class="btn default"> <i id="icon{$v.column_id}" class="fa {$icon}"></i>
                                                        </div>
                                                        <a class="btn default" data-toggle="modal" id="edit{$v.column_id}" onclick="editclick({$v.column_id}, '{$v.column_datatype}');" href="#custom"><i class="fa fa-edit"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            <input type="hidden" id="datatype{$v.column_id}" name="exist_datatype[]" value="{$v.column_datatype}"> 
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div id="datatypediv{$v.column_id}">
                                                        {if $v.column_datatype=='textarea'}
                                                            <textarea class="form-control" readonly=""></textarea>
                                                        {else}
                                                            <input type="text" class="form-control" readonly="">
                                                        {/if}
                                                    </div> 
                                                    <span class="input-group-addon " id="{$v.column_id}" onclick="removedivexist(this.id)">
                                                        <i class="fa fa-minus-circle"></i>
                                                    </span> 
                                                </div> 
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                                <div id="newHeaderleft"></div>
                            </div>


                            <div class="col-md-6">
                                {foreach from=$column item=v}
                                    {if $v.position=='R'}
                                        {if $v.column_datatype=='money'} 
                                            {$icon = 'fa-inr'}
                                        {elseif $v.column_datatype== 'number'} 
                                            {$icon = 'fa-sort-numeric-asc'}
                                        {elseif $v.column_datatype== 'primary'} 
                                            {$icon = 'fa-anchor'}
                                        {elseif $v.column_datatype== 'textarea'} 
                                            {$icon = 'fa-file-text-o'}
                                        {elseif $v.column_datatype== 'date'} 
                                            {$icon = 'fa-calendar'}
                                        {else} 
                                            {$icon = 'fa-font'}
                                        {/if}
                                        <div id="exist{$v.column_id}" class="form-group">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input name="rightcount" type="hidden">
                                                    <input name="exist_col_id[]" type="hidden" value="{$v.column_id}">
                                                    <input type="text" name="exist_col_name[]" value="{$v.column_name}" id="columnname{$v.column_id}" class="form-control" aria-invalid="false">
                                                    <span class="input-group-btn">
                                                        <div class="btn default"> <i id="icon{$v.column_id}" class="fa {$icon}"></i>
                                                        </div>
                                                        <a class="btn default" data-toggle="modal" id="edit{$v.column_id}" onclick="editclick({$v.column_id}, '{$v.column_datatype}');" href="#custom"><i class="fa fa-edit"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            <input type="hidden" id="datatype{$v.column_id}" name="exist_datatype[]" value="{$v.column_datatype}"> 
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div id="datatypediv{$v.column_id}">
                                                        {if $v.column_datatype=='textarea'}
                                                            <textarea class="form-control" readonly=""></textarea>
                                                        {else}
                                                            <input type="text" class="form-control" readonly="">
                                                        {/if}
                                                    </div> 
                                                    <span class="input-group-addon " id="{$v.column_id}" onclick="removedivexist(this.id)">
                                                        <i class="fa fa-minus-circle"></i>
                                                    </span> 
                                                </div> 
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}

                                <div id="newHeaderright"></div>
                            </div>
                        </div>




                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                         <a href="{$cancel_url}" class="btn btn-default">Cancel</a>
                                        <input type="hidden" name="prefix" value="{$prefix.prefix}" id="prefix_hide">
                                        <input type="hidden" name="is_autogenerate" value="{$merchant_setting.customer_auto_generate}" id="is_autogenerate">
                                        <input type="submit" class="btn blue" value="Save"/>
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
                                <label for="inputPassword12" class="col-md-5 control-label">Type</label>
                                <div class="col-md-5">
                                    <select class="form-control input-sm" id="datatype" data-placeholder="Select type">
                                        <option value="text">Text</option>
                                        <option value="textarea">Text area</option>
                                        <option value="number">Number</option>
                                        <option value="date">Date</option>
                                        <option value="password">Password</option>
                                        <option value="gst">GST</option>
                                        <option value="company_name">Company name</option>
                                        {if $cable_enable==1}
                                            <option value="stb">Set Top Box</option>
                                        {/if}
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
                                    <input class="form-control form-control-inline input-sm" id="custom_column_name" type="text" value="" placeholder="Column name" maxlength="250"/>
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="addCustomerField();" class="btn blue">Save</button>
                <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="customer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="closeauto" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{$customer_default_column.customer_code|default:'Customer code'}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <div class="form-group">
                                <h5>Your {$customer_default_column.customer_code|default:'Customer code'}s are set on auto-generate mode to save your time. Are you sure about changing this setting?</h5>

                            </div>

                            <div class="form-group" >
                                <label for="autogen" class="col-md-12 control-label">
                                    <input type="radio" id="autogen" name="auto_generate" value="1" {if $merchant_setting.customer_auto_generate==1} checked=""{/if}> Continue auto-generating numbers
                                </label>
                                <div class="col-md-8">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <p>Prefix</p>
                                            <input type="text" name="prefix" id="prefix" maxlength="10" value="{$prefix}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <p>Number</p>
                                            <input type="text" readonly value="000001" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="auto2" class="col-md-8 control-label">
                                    <input type="radio" class="form-control" id="auto2"  name="auto_generate" {if $merchant_setting.customer_auto_generate!=1} checked=""{/if} value="0"/> I will add them manually each time
                                </label>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="saveAutogenerate();" class="btn blue">Save</button>
                <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- BEGIN FOOTER -->