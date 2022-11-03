<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

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
            <form enctype="multipart/form-data" action="/merchant/event/updatesaved" method="post"
                onsubmit="document.getElementById('loader').style.display='block';" id="submit_form"
                class="form-horizontal form-row-sepe">
                {CSRF::create('event_update')}
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <form action="#" id="template_create">
                            <div class="form-body">
                                <!-- image upload -->
                                <div class="row">
                                    <div class="col-md-12 fileinput fileinput-new center" data-provides="fileinput">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail banner-container"
                                                data-trigger="fileinput">
                                                <img
                                                    src="{if $info.banner_path!=''}/uploads/images/logos/{$info.banner_path}{else}holder.js/1000x330{/if}">
                                            </div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Upload banner </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input type="file" id="imgupload"
                                                        onchange="return validatefilesize(2000000, 'imgupload');"
                                                        accept="image/*" name="banner">
                                                </span>
                                                <a href="javascript:;" id="imgdismiss"
                                                    class="btn red {if $info.banner_path==''}fileinput-exists{/if}"
                                                    data-dismiss="fileinput">
                                                    Remove </a><span style="color: #A0ACAC;">* Max size 2MB</span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <div class="portlet  col-md-12">
                                                        <div class="portlet-body">
                                                            <h4 class="form-section">{$title} details
                                                                <a hhref="javascript:;" onclick="addevent();"
                                                                    class="btn btn-xs green pull-right">
                                                                    <i class="fa fa-plus"></i> Add Fields </a>
                                                            </h4>
                                                            {if !empty($franchise_list)}
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Select franchise
                                                                        <span class="required"> </span></label>
                                                                    <div class="col-md-6">
                                                                        <div class="">
                                                                            <select name="franchise_id" class="form-control"
                                                                                data-placeholder="Select...">
                                                                                <option value="">Select franchise</option>
                                                                                {foreach from=$franchise_list item=v}
                                                                                    {if $v.franchise_id==$info.franchise_id}
                                                                                        <option selected value="{$v.franchise_id}">
                                                                                            {$v.franchise_name}</option>
                                                                                    {else}
                                                                                        <option value="{$v.franchise_id}">
                                                                                            {$v.franchise_name}</option>
                                                                                    {/if}

                                                                                {/foreach}
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                            {if !empty($vendor_list)}
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-4">Select vendor
                                                                        <span class="required"> </span></label>
                                                                    <div class="col-md-6">
                                                                        <div class="">
                                                                            <select name="vendor_id" class="form-control"
                                                                                data-placeholder="Select...">
                                                                                <option value="">Select vendor</option>
                                                                                {foreach from=$vendor_list item=v}
                                                                                    {if $v.vendor_id==$info.vendor_id}
                                                                                        <option selected value="{$v.vendor_id}">
                                                                                            {$v.vendor_name}</option>
                                                                                    {else}
                                                                                        <option value="{$v.vendor_id}">
                                                                                            {$v.vendor_name}</option>
                                                                                    {/if}

                                                                                {/foreach}
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Event Title <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="event_name" maxlength="250"
                                                                        value="{$info.event_name}" required
                                                                        class="form-control form-control-inline">
                                                                    <input type="hidden" value="1" name="duration">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            {if count($currency)>1}
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">Currency <span
                                                                            class="required">* </span></label>
                                                                    <div class="col-md-6">
                                                                        <select id="currency" multiple
                                                                            onchange="currencyChange(this.value);"
                                                                            class="form-control select2me"
                                                                            name="currency[]">
                                                                            {foreach from=$currency item=v}
                                                                                <option {if $v|in_array:$event_currency}
                                                                                    selected {/if} value="{$v}">{$v}</option>
                                                                            {/foreach}
                                                                        </select>
                                                                        <span class="help-block">
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            {else}
                                                                <input type="hidden" name="currency[]"
                                                                    value="{$currency.0}">
                                                            {/if}

                                                            {if $info.occurence>0}
                                                                <div class="form-group" id="occurence_div">
                                                                    <label class="control-label col-md-4">Choose Date(s)
                                                                        <span class="required">* </span></label>
                                                                    <div class="col-md-8" id="startdate">
                                                                        <input type="hidden" value="{$info.occurence}"
                                                                            id="occurence" name="occurence">
                                                                        <table class="table table-borderless"
                                                                            id="occurence_tbl">
                                                                            <tr>
                                                                                <th class="td-c" colspan="2">Start Date Time
                                                                                </th>
                                                                                <th class="td-c" colspan="2">End Date Time
                                                                                </th>
                                                                                <th class="td-c" colspan="2"><a
                                                                                        href="javascript:;"
                                                                                        onclick="addOccurence();"
                                                                                        class="btn btn-xs green"><i
                                                                                            class="fa fa-plus"></i></a></th>
                                                                            </tr>
                                                                            <tbody id="occurence_date">
                                                                                {foreach from=$occurence item=v}
                                                                                    <tr>
                                                                                        <td><input
                                                                                                class="form-control form-control-inline input-sm date-picker"
                                                                                                onblur="setOccurenceDate(this);"
                                                                                                type="text" required
                                                                                                value="{$v.start_date|date_format:"%d %b %Y"}"
                                                                                                name="from_date[]"
                                                                                                autocomplete="off"
                                                                                                data-date-format="dd M yyyy"
                                                                                                placeholder="Start date" /></td>
                                                                                        <td style="padding-left: 5px;"><input
                                                                                                type="text" name="from_time[]"
                                                                                                value="{$v.start_time|date_format:"%I:%M %p"}"
                                                                                                onblur="setOccurenceDate(this);"
                                                                                                class="form-control form-control-inline input-sm timepicker timepicker-no-seconds">
                                                                                        </td>
                                                                                        <td style="padding-left: 5px;"><input
                                                                                                class="form-control form-control-inline input-sm date-picker"
                                                                                                onblur="setOccurenceDate(this);"
                                                                                                type="text" required
                                                                                                value="{$v.end_date|date_format:"%d %b %Y"}"
                                                                                                name="to_date[]"
                                                                                                autocomplete="off"
                                                                                                data-date-format="dd M yyyy"
                                                                                                placeholder="End date" /></td>
                                                                                        <td style="padding-left: 5px;"><input
                                                                                                type="text" name="to_time[]"
                                                                                                value="{$v.end_time|date_format:"%I:%M %p"}"
                                                                                                onblur="setOccurenceDate(this);"
                                                                                                class="form-control form-control-inline input-sm timepicker timepicker-no-seconds">
                                                                                        </td>
                                                                                        <td style="padding-left: 5px;"><a
                                                                                                href="javascript:;"
                                                                                                onclick="$(this).closest('tr').remove();setOccurenceDate();setOccurenceCount();"
                                                                                                class="btn btn-xs red"><i
                                                                                                    class="fa fa-times"></i></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                {/foreach}
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            {/if}
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Stop bookings for
                                                                    event before <span class="required"> </span></label>
                                                                <div class="col-md-2">
                                                                    {if $stop_booking_time.{0}==""}
                                                                        {$stop_booking_time.{0}='100'}
                                                                    {/if}
                                                                    <select name="stop_booking_day"
                                                                        class="form-control">
                                                                        <option value="">Days</option>

                                                                        {for $foo=0 to 30}
                                                                            {if $stop_booking_time.{0}==$foo}
                                                                                <option selected value="{$foo}">{$foo}</option>
                                                                            {else}
                                                                                <option value="{$foo}">{$foo}</option>
                                                                            {/if}
                                                                        {/for}
                                                                    </select> <span class="help-block">
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    {if $stop_booking_time.{1}==""}
                                                                        {$stop_booking_time.{1}='100'}
                                                                    {/if}
                                                                    <select name="stop_booking_hour"
                                                                        class="form-control">
                                                                        <option value="">Hour</option>
                                                                        {for $foo=0 to 24}
                                                                            {if $stop_booking_time.{1}==$foo}
                                                                                <option selected value="{$foo}">{$foo}</option>
                                                                            {else}
                                                                                <option value="{$foo}">{$foo}</option>
                                                                            {/if}
                                                                        {/for}
                                                                    </select>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    {if $stop_booking_time.{2}==""}
                                                                        {$stop_booking_time.{2}='100'}
                                                                    {/if}
                                                                    <select name="stop_booking_min"
                                                                        class="form-control">
                                                                        <option value="">Min</option>
                                                                        {for $foo=0 to 60}
                                                                            {if $stop_booking_time.{2}==$foo}
                                                                                <option selected value="{$foo}">{$foo}</option>
                                                                            {else}
                                                                                <option value="{$foo}">{$foo}</option>
                                                                            {/if}
                                                                        {/for}
                                                                    </select>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Booking unit <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-3">
                                                                    <select id="booking_unit"
                                                                        onchange="unitChange(this.value);"
                                                                        class="form-control" name="booking_unit">
                                                                        <option {if $info.unit_type=='Seats'}
                                                                            selected{/if} value="Seats">Seat</option>
                                                                        <option {if $info.unit_type=='Quantity'}
                                                                            selected{/if} value="Quantity">Quantity
                                                                        </option>
                                                                        <option {if $info.unit_type=='Tickets'}
                                                                            selected{/if} value="Tickets">Ticket
                                                                        </option>
                                                                    </select>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Venue <span
                                                                        class="required"> </span></label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="venue" maxlength="249"
                                                                        value="{$info.venue}"
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-md-4 ">
                                                                    <input type="text" name="artist_label"
                                                                        maxlength="40" style="width: 100px;"
                                                                        value="{$info.artist_label}"
                                                                        class="pull-right form-control form-control-inline">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="artist" maxlength="249"
                                                                        value="{$info.artist}"
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label
                                                                    class="col-md-4 control-label">Description</label>
                                                                <div class="col-md-8">
                                                                    <textarea name="description"
                                                                        class="form-control description">{$info.description}</textarea>
                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Alt title <span
                                                                        class="popovers" data-container="body"
                                                                        data-trigger="hover"
                                                                        data-content="Text entered in this will appear as the Title when you share this link on Facebook and other Social networks"
                                                                        data-original-title="" title=""><i
                                                                            class="fa fa-info-circle"
                                                                            aria-hidden="true"></i></span><span
                                                                        class="required"> </span></label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="title"
                                                                        value="{$info.title}" maxlength="200"
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Short description
                                                                    <span class="popovers" data-container="body"
                                                                        data-trigger="hover"
                                                                        data-content="Text entered in this will appear as the short description, under your title when you share this link on Facebook and other Social networks"
                                                                        data-original-title="" title=""><i
                                                                            class="fa fa-info-circle"
                                                                            aria-hidden="true"></i></span></label>
                                                                <div class="col-md-6">
                                                                    <textarea name="short_description" maxlength="300"
                                                                        class="form-control">{$info.short_description}</textarea>

                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>
                                                            {foreach from=$eventlist item=v}
                                                                {if $v.column_position>2}
                                                                    {if $v.is_mandatory==0}
                                                                        {$v.is_mandatory=2}
                                                                    {/if}
                                                                    <div class="form-group" id="exist{$v.column_id}">
                                                                        <input type="hidden" name="invoice_id[]"
                                                                            value="{$v.invoice_id}" />
                                                                        <label
                                                                            class="col-md-4 control-label">{$v.column_name}</label>
                                                                        <div class="col-md-6">
                                                                            <div class="input-group">
                                                                                {if $v.column_datatype=='textarea'}
                                                                                    <textarea style="z-index: 0;"
                                                                                        name="existvalue[]"
                                                                                        class="form-control input-sm">{$v.value}</textarea></span>
                                                                                {else}
                                                                                    <input style="z-index: 0;" name="existvalue[]"
                                                                                        class="form-control form-control-inline input-sm"
                                                                                        type="text" value="{$v.value}" />
                                                                                {/if}
                                                                                <span class="input-group-addon"
                                                                                    id="{$v.column_id}"
                                                                                    onclick="removedivexist(this.id)"><i
                                                                                        class="fa fa-minus-circle"></i></span>
                                                                            </div> <span class="help-block"></span>
                                                                        </div>
                                                                    </div>
                                                                {/if}
                                                            {/foreach}
                                                            <div id="newevent">
                                                            </div>
                                                            <hr>
                                                            <h4 class="form-section">Capture Payee details</h4>
                                                            <div class="form-group">
                                                                <div class="col-md-6">
                                                                    <a data-toggle="modal"
                                                                        onclick="setStructure('cpd', 'l');"
                                                                        href="#customer"
                                                                        class="btn green btn-xs pull-right"><i
                                                                            class="fa fa-plus"></i> Add </a>
                                                                    <div id="div_cpdl">
                                                                        {foreach from=$payee_capture item=v}
                                                                            {if $v.position=='L'}
                                                                                <div id="existcpd{$v.name}" class="form-group">
                                                                                    <label
                                                                                        class="col-md-4 control-label">{$v.column_name}
                                                                                        <span id="cpdreq_{$v.name}"
                                                                                            class="required">
                                                                                            {if $v.mandatory==1}*
                                                                                            {/if}</span></label>
                                                                                    <div class="col-md-6">
                                                                                        {if $v.mandatory==0}
                                                                                            <div class="input-group">
                                                                                            {/if}
                                                                                            {if $v.datatype=='textarea'}
                                                                                                <textarea readonly
                                                                                                    id="cpd_{$v.name}"
                                                                                                    class="form-control input-sm form-control-inline"></textarea>
                                                                                            {else}
                                                                                                <input type="text"
                                                                                                    id="cpd_{$v.name}" readonly
                                                                                                    class="form-control form-control-inline">
                                                                                            {/if}
                                                                                            <textarea style="display:none;"
                                                                                                id="cpdlf_{$v.name}"
                                                                                                name="cpdl[]">{$v|@json_encode}</textarea>
                                                                                            <input type="hidden"
                                                                                                id="cpdlm_{$v.name}"
                                                                                                value="{$v.mandatory}">
                                                                                            {if $v.mandatory==0}
                                                                                                <span class="input-group-addon "
                                                                                                    onclick="editMandatory('cpd', '{$v.name}', 'l')">
                                                                                                    <a data-toggle="modal"
                                                                                                        href="#mandatory"> <i
                                                                                                            class="fa fa-edit"></i></a>
                                                                                                </span>
                                                                                                <span class="input-group-addon "
                                                                                                    onclick="removedivexist('cpd{$v.name}')">
                                                                                                    <i
                                                                                                        class="fa fa-minus-circle"></i>
                                                                                                </span>
                                                                                            </div>
                                                                                        {/if}

                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <a data-toggle="modal"
                                                                        onclick="setStructure('cpd', 'r');"
                                                                        href="#customer"
                                                                        class="btn green btn-xs pull-right"><i
                                                                            class="fa fa-plus"></i> Add </a>
                                                                    <div id="div_cpdr">
                                                                        {foreach from=$payee_capture item=v}
                                                                            {if $v.position=='R'}
                                                                                <div id="existcpd{$v.name}" class="form-group">
                                                                                    <label
                                                                                        class="col-md-4 control-label">{$v.column_name}
                                                                                        <span id="cpdreq_{$v.name}"
                                                                                            class="required">
                                                                                            {if $v.mandatory==1}*
                                                                                            {/if}</span></label>
                                                                                    <div class="col-md-6">
                                                                                        {if $v.mandatory==0}
                                                                                            <div class="input-group">
                                                                                            {/if}
                                                                                            {if $v.datatype=='textarea'}
                                                                                                <textarea readonly
                                                                                                    id="cpd_{$v.name}"
                                                                                                    class="form-control input-sm form-control-inline"></textarea>
                                                                                            {else}
                                                                                                <input type="text"
                                                                                                    id="cpd_{$v.name}" readonly
                                                                                                    class="form-control form-control-inline">
                                                                                            {/if}
                                                                                            <textarea style="display:none;"
                                                                                                id="cpdrf_{$v.name}"
                                                                                                name="cpdr[]">{$v|@json_encode}</textarea>
                                                                                            <input type="hidden"
                                                                                                id="cpdrm_{$v.name}"
                                                                                                value="{$v.mandatory}">
                                                                                            {if $v.mandatory==0}
                                                                                                <span class="input-group-addon "
                                                                                                    onclick="editMandatory('cpd', '{$v.name}', 'r')">
                                                                                                    <a data-toggle="modal"
                                                                                                        href="#mandatory"> <i
                                                                                                            class="fa fa-edit"></i></a>
                                                                                                </span>
                                                                                                <span class="input-group-addon "
                                                                                                    onclick="removedivexist('cpd{$v.name}')">
                                                                                                    <i
                                                                                                        class="fa fa-minus-circle"></i>
                                                                                                </span>
                                                                                            </div>
                                                                                        {/if}

                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <h4 class="form-section">Capture Attendees details</h4>
                                                            <div class="form-group">
                                                                <div class="col-md-6">
                                                                    <a data-toggle="modal"
                                                                        onclick="setStructure('cad', 'l');"
                                                                        href="#customer"
                                                                        class="btn green btn-xs pull-right"><i
                                                                            class="fa fa-plus"></i> Add </a>
                                                                    <div id="div_cadl">
                                                                        {foreach from=$attendees_capture item=v}
                                                                            {if $v.position=='L'}
                                                                                <div id="existcad{$v.name}" class="form-group">
                                                                                    <label
                                                                                        class="col-md-4 control-label">{$v.column_name}
                                                                                        <span id="cadreq_{$v.name}"
                                                                                            class="required">{if $v.mandatory==1}*
                                                                                            {/if}</span></label>
                                                                                    <div class="col-md-6">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                id="cad_{$v.name}" readonly=""
                                                                                                class="form-control form-control-inline">
                                                                                            <textarea style="display:none;"
                                                                                                id="cadlf_{$v.name}"
                                                                                                name="cadl[]">{$v|@json_encode}</textarea>
                                                                                            <input type="hidden"
                                                                                                id="cadlm_{$v.name}"
                                                                                                value="{$v.mandatory}">
                                                                                            <span class="input-group-addon "
                                                                                                onclick="editMandatory('cad', '{$v.name}', 'l')"><a
                                                                                                    data-toggle="modal"
                                                                                                    href="#mandatory">
                                                                                                    <i
                                                                                                        class="fa fa-edit"></i></a>
                                                                                            </span>
                                                                                            <span class="input-group-addon "
                                                                                                onclick="removedivexist('cad{$v.name}')"><i
                                                                                                    class="fa fa-minus-circle"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <a data-toggle="modal"
                                                                        onclick="setStructure('cad', 'r');"
                                                                        href="#customer"
                                                                        class="btn green btn-xs pull-right"><i
                                                                            class="fa fa-plus"></i> Add </a>
                                                                    <div id="div_cadr">
                                                                        {foreach from=$attendees_capture item=v}
                                                                            {if $v.position=='R'}
                                                                                <div id="existcad{$v.name}" class="form-group">
                                                                                    <label
                                                                                        class="col-md-4 control-label">{$v.column_name}
                                                                                        <span id="cadreq_{$v.name}"
                                                                                            class="required">{if $v.mandatory==1}*
                                                                                            {/if}</span></label>
                                                                                    <div class="col-md-6">
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                id="cad_{$v.name}" readonly=""
                                                                                                class="form-control form-control-inline">
                                                                                            <textarea style="display:none;"
                                                                                                id="cadrf_{$v.name}"
                                                                                                name="cadr[]">{$v|@json_encode}</textarea>
                                                                                            <input type="hidden"
                                                                                                id="cadrm_{$v.name}"
                                                                                                value="{$v.mandatory}">
                                                                                            <span class="input-group-addon "
                                                                                                onclick="editMandatory('cad', '{$v.name}', 'r')"><a
                                                                                                    data-toggle="modal"
                                                                                                    href="#mandatory">
                                                                                                    <i
                                                                                                        class="fa fa-edit"></i></a>
                                                                                            </span>
                                                                                            <span class="input-group-addon "
                                                                                                onclick="removedivexist('cad{$v.name}')"><i
                                                                                                    class="fa fa-minus-circle"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>





                                                            <hr>

                                                            {$int =1}
                                                            {foreach from=$package item=v}
                                                                <div id="package{$int}">
                                                                    <h4 class="form-section">Package

                                                                        <a class="btn btn-xs red pull-right"
                                                                            onclick="setdeletepackage({$int});"
                                                                            title="Delete package"><i
                                                                                class="fa fa-remove"></i></a>
                                                                        <a hhref="javascript:;" onclick="addeventpackage();"
                                                                            class="btn btn-xs green pull-right">
                                                                            <i class="fa fa-plus"></i> Add </a>
                                                                    </h4>
                                                                    <div id="exist{$int}">

                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Category
                                                                                name <span class="popovers"
                                                                                    data-container="body"
                                                                                    data-trigger="hover"
                                                                                    data-content="i.	Category can be used to add your packages into logical customer friendly buckets. Example of categories – Season Pass, U-21 Pass, Senior Citizen Pass ii.	Categories are useful as it enables your customer to navigate directly into the package category of their choice while viewing your event.
                                                                                                                                      iii.	The least & highest cost from all the packages added into a category will be automatically shown in the event view page"
                                                                                    data-original-title="" title=""><i
                                                                                        class="fa fa-info-circle"
                                                                                        aria-hidden="true"></i></span><span
                                                                                    class="required"> </span></label>
                                                                            <div class="col-md-6" id="firstcatdiv">
                                                                                <select class="form-control"
                                                                                    name="ecategory_name[]" id="cat{$int}d"
                                                                                    data-placeholder="Category name">
                                                                                    <option value="">Select Category
                                                                                    </option>
                                                                                    {foreach from=$total_cat key=k item=c}
                                                                                        {if $v.category_name==$c}
                                                                                            <option selected value="{$c}">{$c}
                                                                                            </option>
                                                                                        {else}
                                                                                            <option value="{$c}">{$c}</option>
                                                                                        {/if}
                                                                                    {/foreach}
                                                                                </select>

                                                                            </div>
                                                                            <div class="col-md-1 no-margin no-padding">

                                                                                <a title="Add new" href="#basic"
                                                                                    data-toggle="modal"
                                                                                    class="btn btn-sm green"><i
                                                                                        class="fa fa-plus"></i> Add new </a>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Package
                                                                                name <span class="required">*
                                                                                </span></label>
                                                                            <div class="col-md-6">
                                                                                <input type="text" name="epackage_name[]"
                                                                                    maxlength="250"
                                                                                    value="{$v.package_name}" required
                                                                                    class="form-control form-control-inline">
                                                                                <input type="hidden" name="epackage_id[]"
                                                                                    value="{$v.package_id}">
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Package
                                                                                description <span class="required">*
                                                                                </span></label>
                                                                            <div class="col-md-6">
                                                                                <textarea type="text"
                                                                                    name="epackage_description[]" required
                                                                                    class="form-control form-control-inline">{$v.package_description}</textarea>
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Available
                                                                                {$display_booking} <span class="required">*
                                                                                </span></label>
                                                                            <div class="col-md-2">
                                                                                <input
                                                                                    class="form-control form-control-inline"
                                                                                    required min="0" maxlength="7"
                                                                                    type="number" name="eunitavailable[]"
                                                                                    value="{$v.seats_available}" />
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                            (Keep 0 for unlimited bookings)
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Sold out
                                                                                package <span class="required">
                                                                                </span></label>
                                                                            <div class="col-md-2">
                                                                                <input type="checkbox"
                                                                                    onchange="is_details_capture(this.checked, 'soldout{$v.package_id}');"
                                                                                    value="1"
                                                                                    {if $v.sold_out==1}checked{/if}
                                                                                    class="make-switch"
                                                                                    data-on-text="&nbsp;Yes&nbsp;&nbsp;"
                                                                                    data-off-text="&nbsp;No&nbsp;">
                                                                                <input type="hidden" name="esold_out[]"
                                                                                    {if $v.sold_out==1}value="1"
                                                                                    {else}value="0" 
                                                                                    {/if}
                                                                                    id="soldout{$v.package_id}_name">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Min
                                                                                {$display_booking} <span class="required">*
                                                                                </span></label>
                                                                            <div class="col-md-2">
                                                                                <input type="number" min="1"
                                                                                    name="emin_seat[]" maxlength="7"
                                                                                    max="100"
                                                                                    onchange="validatemax('min_seat{$int}', 'max_seat{$int}');"
                                                                                    id="min_seat{$int}"
                                                                                    value="{$v.min_seat}" required
                                                                                    class="form-control form-control-inline">
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>

                                                                            <label class="col-md-2 control-label">Max
                                                                                {$display_booking} <span class="required">*
                                                                                </span></label>
                                                                            <div class="col-md-2">
                                                                                <input type="number" min="1"
                                                                                    name="emax_seat[]" id="max_seat{$int}"
                                                                                    maxlength="7" max="100"
                                                                                    value="{$v.max_seat}" required
                                                                                    class="form-control form-control-inline">
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Pricing
                                                                                Type <span class="popovers"
                                                                                    data-container="body"
                                                                                    data-trigger="hover"
                                                                                    data-content="Change this to Flexible, if you would like your audience to decide how much they want to pay to attend the event. Mostly, applicable if you are creating an event for a NGO or an event to collect donations for a cause. If you change this to 'Flexible' you will be provided two boxes to enter the Min amount & Max amount. Your users can pay any amount between the minimum and maximum amounts entered by you."
                                                                                    data-original-title="" title=""><i
                                                                                        class="fa fa-info-circle"
                                                                                        aria-hidden="true"></i></span></label>
                                                                            <div class="col-md-2">
                                                                                <select class="form-control"
                                                                                    onchange="flexible({$int});"
                                                                                    name="eis_flexible[]"
                                                                                    id="is_flexible{$int}"
                                                                                    data-placeholder="Pricing type">
                                                                                    <option value="0">Standard</option>
                                                                                    <option
                                                                                        {if $v.is_flexible==1}selected{/if}
                                                                                        value="1">Flexible</option>
                                                                                    <option
                                                                                        {if $v.is_flexible==2}selected{/if}
                                                                                        value="2">Free</option>
                                                                                </select>
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group" id="flixible_div{$int}"
                                                                            {if $v.is_flexible==1}
                                                                            {else}style="display: none;" 
                                                                            {/if}>
                                                                            <label class="col-md-4 control-label">Min amount
                                                                                <span class="required">* </span></label>
                                                                            <div class="col-md-2">
                                                                                <input step="0.01" type="number"
                                                                                    id="min_amount{$int}"
                                                                                    name="emin_price[]" maxlength="8"
                                                                                    value="{$v.min_price}"
                                                                                    class="form-control form-control-inline">
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>

                                                                            <label class="col-md-2 control-label">Max amount
                                                                                <span class="required">* </span></label>
                                                                            <div class="col-md-2">
                                                                                <input step="0.01" type="number"
                                                                                    id="max_amount{$int}"
                                                                                    name="emax_price[]" maxlength="8"
                                                                                    value="{$v.max_price}"
                                                                                    class="form-control form-control-inline">
                                                                                <span class="help-block">
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div id="nonflixible_div{$int}"
                                                                            {if $v.is_flexible!=0}style="display: none;"
                                                                            {/if}>
                                                                            <div id="price_div{$int}">
                                                                                {if !empty($v.currency_price)}
                                                                                    {foreach from=$v.currency_price key=ck item=cc}
                                                                                        <div class="form-group">
                                                                                            <label
                                                                                                class="col-md-4 control-label">Price
                                                                                                in ({$ck})
                                                                                                <span class="required">*
                                                                                                </span></label>
                                                                                            <div class="col-md-3">
                                                                                                <input name="eunitcost[]"
                                                                                                    id="unitcost{$int}"
                                                                                                    value="{$cc}" maxlength="8"
                                                                                                    max="100000"
                                                                                                    class="form-control form-control-inline"
                                                                                                    step="0.01" type="number"
                                                                                                    value="" />
                                                                                                <input type="hidden" value="{$ck}"
                                                                                                    name="epackage_currency[]">
                                                                                                <span class="help-block"></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    {/foreach}
                                                                                {else}
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            class="col-md-4 control-label">Price
                                                                                            <span class="required">*
                                                                                            </span></label>
                                                                                        <div class="col-md-3">
                                                                                            <input name="eunitcost[]"
                                                                                                id="unitcost{$int}"
                                                                                                value="{$v.price}" maxlength="8"
                                                                                                max="100000"
                                                                                                class="form-control form-control-inline"
                                                                                                step="0.01" type="number"
                                                                                                value="" />
                                                                                            <span class="help-block"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                {/if}
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <div class="col-md-4"><label
                                                                                        class="control-label">Coupon</label>
                                                                                </div>
                                                                                <div class="col-md-3" id="coupon">
                                                                                    <select class="form-control"
                                                                                        name="epackage_coupon[]"
                                                                                        data-placeholder="Coupon code">
                                                                                        {if empty($coupons)}
                                                                                            <option value="0">No active
                                                                                                coupons
                                                                                            </option>
                                                                                        {else}
                                                                                            <option value="0">Select coupon
                                                                                            </option>
                                                                                        {/if}
                                                                                        {foreach from=$coupons key=k item=c}
                                                                                            {if $c.coupon_id==$v.coupon_code}
                                                                                                <option selected
                                                                                                    value="{$c.coupon_id}">
                                                                                                    {$c.coupon_code}</option>
                                                                                            {else}
                                                                                                <option value="{$c.coupon_id}">
                                                                                                    {$c.coupon_code}</option>
                                                                                            {/if}
                                                                                        {/foreach}
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-4 control-label">Tax
                                                                                (if applicable)</label>
                                                                            <div class="col-md-3">
                                                                                <input name="etax_text[]"
                                                                                    placeholder="Tax label" maxlength="45"
                                                                                    class="form-control form-control-inline"
                                                                                    type="text" value="{$v.tax_text}" />
                                                                                <span class="help-block"></span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input name="etax[]" step="0.01" min="0"
                                                                                    max="100" maxlength="6"
                                                                                    class="form-control form-control-inline"
                                                                                    type="number" value="{$v.tax}" />
                                                                                <span class="help-block"></span>
                                                                            </div>%
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Package
                                                                            type</label>
                                                                        <div class="col-md-8">
                                                                            <label class="control-label">
                                                                                <input type="radio" {if $v.package_type==1}
                                                                                    checked{/if} class="md-radiobtn"
                                                                                    onchange="changePackageType({$int}, this.value);"
                                                                                    name="epackage_type{$int}[]" value="1">
                                                                                Normal Pass</label>
                                                                            <br><label class="control-label">
                                                                                <input type="radio" class="md-radiobtn"
                                                                                    {if $v.package_type==2} checked{/if}
                                                                                    onchange="changePackageType({$int}, this.value);"
                                                                                    name="epackage_type{$int}[]" value="2">
                                                                                Season Pass </label>
                                                                            <span class="help-block">
                                                                            </span>
                                                                        </div>
                                                                        <input type="hidden" name="epackage_int[]"
                                                                            value="{$int}">
                                                                    </div>
                                                                    <div class="form-group" id="multidatediv{$int}d"
                                                                        {if $v.package_type==2} style="display: none;"
                                                                        {/if}>
                                                                        <label class="control-label col-md-4">Choose
                                                                            available date <span class="required">
                                                                            </span></label>
                                                                        <div class="col-md-8" id="emultidate{$int}d"
                                                                            style="height: 80px; overflow: auto; width: 240px;">
                                                                            {foreach from=$total_occurence key=k item=oc}
                                                                                {if {$oc|count_characters}>10}
                                                                                    {$dated={$oc|date_format:"%d %b %Y %I:%M %p"}}
                                                                                {else}
                                                                                    {$dated={$oc|date_format:"%d %b %Y"}}
                                                                                {/if}
                                                                                <label class="control-label"
                                                                                    style="margin-right:10px;">
                                                                                    {if {$oc|in_array:$v.occurence_array} ||
                                                                                        $v.package_type==2}
                                                                                        <input type="checkbox" checked=""
                                                                                            class="md-checkbox icheck"
                                                                                            name="emulti_occurence{$int}[]"
                                                                                            value="{$dated}">{$dated}
                                                                                    {else}
                                                                                        <input type="checkbox"
                                                                                            class="md-checkbox icheck"
                                                                                            name="emulti_occurence{$int}[]"
                                                                                            value="{$dated}">{$dated}
                                                                                    {/if}
                                                                                </label>
                                                                            {/foreach}

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {$int = $int + 1}
                                                        {/foreach}

                                                        <div id="newpackage" class="col-md-12">
                                                        </div>
                                                        <hr>
                                                        <h4 class="form-section">Coupon details</h4>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Coupon (if
                                                                applicable)</label>
                                                            <div class="col-md-6">
                                                                <select class="form-control" name="coupon_code"
                                                                    data-placeholder="Coupon code">
                                                                    <option value="0">Select coupon</option>

                                                                    {foreach from=$coupons key=k item=c}
                                                                        {if $c.coupon_id==$info.coupon_id}
                                                                            <option selected value="{$info.coupon_id}">
                                                                                {$c.coupon_code}</option>
                                                                        {else}
                                                                            <option value="{$c.coupon_id}">{$c.coupon_code}
                                                                            </option>
                                                                        {/if}
                                                                    {/foreach}

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <h4 class="form-section">Terms & Conditions</h4>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Terms &
                                                                Conditions</label>
                                                            <div class="col-md-8">
                                                                <textarea name="event_tnc"
                                                                    class="form-control description">{$info.terms}</textarea>

                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-4 control-label">Cancellation
                                                                policy</label>
                                                            <div class="col-md-8">
                                                                <textarea name="cancellation_policy"
                                                                    class="form-control description">{$info.privacy}</textarea>

                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    {CSRF::create('event_update')}
                                                    <a href="/merchant/event/viewlist"
                                                        class="btn btn-default">Cancel</a>
                                                    <input type="hidden" name="event_type" value="{$info.event_type}">
                                                    <input type="hidden" name="event_request_id" value="{$link}">
                                                    <button type="submit" onsubmit="return validateEvent();"
                                                        class="btn blue"><i class="fa fa-check"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </form>
                    </div>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Category name</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Category name <span class="required"> </span></label>
                        <div class="col-md-6">
                            <input type="text" id="category_name" required class="form-control form-control-inline">
                            <span class="help-block">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn default" data-dismiss="modal">Close</button>
                <button onclick="saveCategory(1);" class="btn blue">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    numherder ={$int};
</script>
<a href="#deletepkg" id="dpkg" data-toggle="modal"></a>
<div class="modal fade" id="deletepkg" tabindex="-1" role="deletepkg" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete package</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this package?
            </div>
            <div class="modal-footer">
                <button type="button" id="clgpkg" class="btn default" data-dismiss="modal">Close</button>
                <button id="deletepackage" class="btn delete">Confirm</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>