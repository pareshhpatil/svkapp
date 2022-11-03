<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';
</script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Update event</h3>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">

        <div class="col-md-1"></div>
        <div class="col-md-10">
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
            <form enctype="multipart/form-data" id="templateform"  action="/merchant/event/updatesaved" method="post" id="profile_update" class="form-horizontal form-row-sepe">
                {CSRF::create('event_update')}
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" id="template_create" >
                            <div class="form-body">
                                <!-- image upload -->
                                <div class="row">
                                    <div class="col-md-12 fileinput fileinput-new banner-main" data-provides="fileinput">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail banner-container" data-trigger="fileinput">
                                                <img src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/100%x100%{/if}"></div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Upload banner </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input type="file" accept="image/*" name="banner">
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 fileinput fileinput-new logo-main" style="position: absolute;" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            <img src="{if $info.image_path!=''}/uploads/images/logos/{$info.image_path}{else}/assets/admin/layout/img/nologo.gif{/if}" alt=""/>
                                        </div>

                                        <div  class="fileinput-preview fileinput-exists thumbnail logo-select">
                                        </div>
                                        <div>
                                            <span class="btn btn-sm default btn-file">
                                                <span class="fileinput-new">
                                                    Select logo </span>
                                                <span class="fileinput-exists">
                                                    Change </span>
                                                <input type="file" accept="image/*" name="logo">
                                            </span>
                                            <a href="javascript:;" class="btn-sm btn default fileinput-exists" data-dismiss="fileinput">
                                                Remove </a>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row" style="margin-top: 370px;">
                                    <div class="portlet-body form">
                                        <h3 class="form-section">Event details
                                            <a href="javascript:;" onclick="addevent();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add field </a>
                                        </h3>

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-8">

                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Event name</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="event_name"  value="{$info.template_name}" class="form-control form-control-inline input-sm">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>

                                                    </div>
                                                    {foreach from=$eventlist item=v}
                                                        {if $v.is_mandatory==0}
                                                            {$v.is_mandatory=2}
                                                        {/if}

                                                        <div class="form-group" id="exist{$v.column_id}">
                                                            <input type="hidden" name="invoice_id[]" value="{$v.invoice_id}" />
                                                            {if $v.column_datatype=='date'}
                                                                <label class="col-md-4 control-label">{$v.column_name} <span onclick="addrange();" id="range">Add range?</span> </label>
                                                            {else}
                                                                <label class="col-md-4 control-label">{$v.column_name}</label>
                                                            {/if}
                                                            <div class="col-md-8">
                                                                {if $v.is_delete_allow==1}
                                                                    <div class="input-group">
                                                                        {if $v.column_datatype=='textarea'}
                                                                            <textarea name="existvalue[]" class="form-control input-sm">{$v.value}</textarea>
                                                                        {else if $v.column_datatype=='date'}
                                                                            <div class="col-md-5 no-padding"><input type="hidden" name="existvalue[]" value="{$v.value}" id="daterange"/> <input name="fromdate" class="form-control form-control-inline input-sm date-picker"  data-date-format="dd M yyyy"  type="text" id="from_date" onchange="setrange();" value="{$info.bill_date}"/></div><div class="col-md-7 collapse" id="range_todate"><div class="input-group"><input name="todate" class="form-control form-control-inline input-sm date-picker"  autocomplete="off" data-date-format="dd M yyyy"  id="to_date" onchange="setrange();" type="text" value="{$info.due_date}"/> <span class="input-group-addon " onclick="removerange();"><i class="fa fa-minus-circle"></i></span></div></div>
                                                                            {else}
                                                                            <input name="existvalue[]" class="form-control form-control-inline input-sm" type="text" value="{$v.value}"/>
                                                                        {/if}
                                                                        <span class="input-group-addon " id="{$v.column_id}" onclick="removedivexist(this.id)">
                                                                            <i class="fa fa-minus-circle"></i>
                                                                        </span>
                                                                    </div> <span class="help-block"></span>
                                                                {else}
                                                                    {if $v.column_datatype=='textarea'}
                                                                        <textarea name="existvalue[]" class="form-control input-sm">{$v.value}</textarea>
                                                                    {else if $v.column_datatype=='date'}
                                                                        <div class="col-md-5 no-padding"><input type="hidden" name="existvalue[]" value="{$v.value}" id="daterange"/> <input name="fromdate" class="form-control form-control-inline input-sm date-picker" type="text" id="from_date"  data-date-format="dd M yyyy"  onchange="setrange();" value="{$info.bill_date}"/></div><div class="col-md-7 collapse" id="range_todate"><div class="input-group"><input name="todate" class="form-control form-control-inline input-sm date-picker" id="to_date" onchange="setrange();"  autocomplete="off" data-date-format="dd M yyyy"  type="text" value="{$info.due_date}"/> <span class="input-group-addon " onclick="removerange();"><i class="fa fa-minus-circle"></i></span></div></div>
                                                                        {else}
                                                                        <input name="existvalue[]" class="form-control form-control-inline input-sm" type="text" value="{$v.value}"/>
                                                                    {/if}
                                                                    <span class="help-block">
                                                                    </span>
                                                                {/if}
                                                            </div>
                                                        </div>
                                                    {/foreach}
                                                    <div id="newevent">

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Units available</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control form-control-inline input-sm" type="text" name="unitavailable" value="0"/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Unit cost</label>
                                                        <div class="col-md-8">
                                                            <input name="unitcost" id="unitcost" onblur="calculateEventgrandtotal();" class="form-control form-control-inline input-sm" type="text" value="{$info.invoice_total}"/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Grand total</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control form-control-inline input-sm" name="grandtotal" id="grandtotal" readonly="" type="text" value="{$info.absolute_cost}"/>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>



                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-6">
                                                    <input type="hidden" value="{$link}" name="payment_request_id">
                                                    <button type="submit" class="btn blue"><i class="fa fa-check"></i> Update</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form></div>
                                </div>	
                            </div>	


                    </div>
                </div>
            </form>
        </div>

    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>