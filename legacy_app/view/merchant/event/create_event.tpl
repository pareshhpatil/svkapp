<script>
    var t = '{$pg.swipez_fee_type}';
    var e = '{$pg.swipez_fee_val}';
    var a = '{$pg.pg_fee_type}';
    var n = '{$pg.pg_fee_val}';
    var u = '{$pg.pg_tax_val}';
</script>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>

        <div class="col-md-10">
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=kk}
                            {foreach from=$kk item=v}
                                {if $v.1!=''}
                                    <p class="media-heading">{$v.0} - {$v.1}.</p>
                                {/if}
                            {/foreach}
                        {/foreach}
                    </div>

                </div>
            {/if}
            <form enctype="multipart/form-data" action="/merchant/event/saved" method="post"
                onsubmit="document.getElementById('loader').style.display='block';" id="submit_form"
                class="form-horizontal form-row-sepe">
                {CSRF::create('event_create')}
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
                                                <img src="holder.js/1140x330">
                                            </div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Upload banner </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input onchange="return validatefilesize(2000000, 'imgupload');"
                                                        id="imgupload" type="file" accept="image/*" name="banner">

                                                </span>
                                                <a href="javascript:;" id="imgdismiss" class="btn red fileinput-exists"
                                                    data-dismiss="fileinput">
                                                    Remove </a>
                                                <span style="color: #A0ACAC;">* Max size 2MB</span>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="portlet-body form">
                                        <div class="form-body">
                                            <div class="row no-margin">

                                                <div class="col-md-12">

                                                    <h4 class="form-section">Event details
                                                        <a hhref="javascript:;" onclick="addevent();"
                                                            class="btn btn-xs green pull-right">
                                                            <i class="fa fa-plus"></i> Add Fields </a>
                                                    </h4>
                                                    {if !empty($franchise_list)}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Select franchise <span
                                                                    class="required"> </span></label>
                                                            <div class="col-md-6">
                                                                <select name="franchise_id" class="form-control"
                                                                    data-placeholder="Select...">
                                                                    <option value="">Select franchise</option>
                                                                    {foreach from=$franchise_list item=v}
                                                                        <option value="{$v.franchise_id}">{$v.franchise_name}
                                                                        </option>
                                                                    {/foreach}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                    {if !empty($vendor_list)}
                                                        <div class="form-group">
                                                            <label class="control-label col-md-4">Select vendor <span
                                                                    class="required"> </span></label>
                                                            <div class="col-md-6">
                                                                <select name="vendor_id" class="form-control"
                                                                    data-placeholder="Select...">
                                                                    <option value="">Select vendor</option>
                                                                    {foreach from=$vendor_list item=v}
                                                                        <option value="{$v.vendor_id}">{$v.vendor_name}</option>
                                                                    {/foreach}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    {/if}
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Event Title <span
                                                                class="required">* </span></label>
                                                        <div class="col-md-6">
                                                            <input type="text" name="event_name" id="title"
                                                                onchange="setcopy('title');" maxlength="250"
                                                                value="{$post.event_name}" required
                                                                class="form-control form-control-inline">
                                                            <input type="hidden" value="1" name="duration">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Booking unit <span
                                                                class="required">* </span></label>
                                                        <div class="col-md-3">
                                                            <select id="booking_unit" onchange="unitChange(this.value);"
                                                                class="form-control" name="booking_unit">
                                                                <option value="Seats">Seat</option>
                                                                <option value="Quantity">Quantity</option>
                                                                <option value="Tickets">Ticket</option>
                                                            </select>
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
                                                                    class="form-control select2me" name="currency[]">
                                                                    {foreach from=$currency item=v}
                                                                        <option value="{$v}">{$v}</option>
                                                                    {/foreach}
                                                                </select>
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                    {else}
                                                        <input type="hidden" name="currency[]" value="{$currency.0}">
                                                    {/if}

                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Occurrence of event <span
                                                                class="popovers" data-container="body"
                                                                data-trigger="hover"
                                                                data-content="
                                                                                                                        i.	Occurrence of event specifies the number of times this event is held i.e. You could host an event which occurs twice in one day by setting this as 2 OR an event which occurs 3 times across 3 days by setting this as 3
                                                                                                                        &nbsp;&nbsp;&nbsp;&nbsp; ii.	If your event is occurring only once then set this as 1
                                                                                                                        iii.	If your event does not need dates to be shown against it then set it as 0
                                                                                                                        "
                                                                data-original-title="" title=""><i
                                                                    class="fa fa-info-circle"
                                                                    aria-hidden="true"></i></span><span
                                                                class="required">* </span></label>
                                                        <div class="col-md-3">
                                                            <input type="number" value="1" min="0" max="60"
                                                                onchange="showdate(this.value);" step="1" id="occurence"
                                                                name="occurence" required
                                                                class="form-control form-control-inline">
                                                            <span class="help-block">
                                                            </span>
                                                            <!-- /input-group -->
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="occurence_div">
                                                        <label class="control-label col-md-4">Choose Date(s) <span
                                                                class="required">* </span></label>
                                                        <div class="col-md-8" id="startdate">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <th class="td-c" colspan="2">Start Date Time</th>
                                                                    <th class="td-c" colspan="2">End Date Time</th>
                                                                </tr>
                                                                <tbody id="occurence_date">
                                                                    <tr>
                                                                        <td><input
                                                                                class="form-control form-control-inline input-sm date-picker"
                                                                                onchange="setOccurenceDate(this);"
                                                                                type="text" required
                                                                                value="{$from_date}" name="from_date[]"
                                                                                autocomplete="off"
                                                                                data-date-format="dd M yyyy"
                                                                                placeholder="Start date" /></td>
                                                                        <td style="padding-left: 5px;"><input
                                                                                type="text" name="from_time[]"
                                                                                onchange="setOccurenceDate(this);"
                                                                                class="form-control form-control-inline input-sm timepicker timepicker-no-seconds">
                                                                        </td>
                                                                        <td style="padding-left: 5px;"><input
                                                                                class="form-control form-control-inline input-sm date-picker"
                                                                                onchange="setOccurenceDate(this);"
                                                                                type="text" required
                                                                                value="{$from_date}" name="to_date[]"
                                                                                autocomplete="off"
                                                                                data-date-format="dd M yyyy"
                                                                                placeholder="End date" /></td>
                                                                        <td style="padding-left: 5px;"><input
                                                                                type="text" name="to_time[]"
                                                                                onchange="setOccurenceDate(this);"
                                                                                class="form-control form-control-inline input-sm timepicker timepicker-no-seconds">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Stop bookings for event
                                                            before <span class="required"> </span></label>
                                                        <div class="col-md-2">
                                                            <select name="stop_booking_day" class="form-control">
                                                                <option value="">Days</option>
                                                                {for $foo=0 to 30}
                                                                    <option value="{$foo}">{$foo}</option>
                                                                {/for}
                                                            </select> <span class="help-block">
                                                            </span>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="stop_booking_hour" class="form-control">
                                                                <option value="">Hour</option>
                                                                {for $foo=0 to 23}
                                                                    <option value="{$foo}">{$foo}</option>
                                                                {/for}
                                                            </select>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="stop_booking_min" class="form-control">
                                                                <option value="">Min</option>
                                                                {for $foo=0 to 59}
                                                                    <option value="{$foo}">{$foo}</option>
                                                                {/for}
                                                            </select>
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Venue <span
                                                                class="required"> </span></label>
                                                        <div class="col-md-6">
                                                            <input type="text" name="venue" maxlength="250"
                                                                value="{$post.venue}"
                                                                class="form-control form-control-inline">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4 ">
                                                            <input type="text" name="artist_label" maxlength="40"
                                                                style="width: 100px;" value="Artist"
                                                                class="pull-right form-control form-control-inline">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="artist" maxlength="250"
                                                                value="{$post.artist}"
                                                                class="form-control form-control-inline">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Description</label>
                                                        <div class="col-md-8">
                                                            <textarea name="description" id="summernote"
                                                                class="form-control description"></textarea>

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
                                                            <input type="text" name="title" id="altitle"
                                                                onchange="disablecopy('title');" value="{$post.title}"
                                                                maxlength="200"
                                                                class="form-control form-control-inline">
                                                            <span class="help-block">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Short description <span
                                                                class="popovers" data-container="body"
                                                                data-trigger="hover"
                                                                data-content="Text entered in this will appear as the short description, under your title when you share this link on Facebook and other Social networks"
                                                                data-original-title="" title=""><i
                                                                    class="fa fa-info-circle"
                                                                    aria-hidden="true"></i></span></label>
                                                        <div class="col-md-6">
                                                            <textarea name="short_description"
                                                                onchange="disablecopy('desc');" id="shortdesc"
                                                                maxlength="300" class="form-control"></textarea>

                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div id="newevent">
                                                    </div>
                                                    <hr>
                                                    <h4 class="form-section">Capture payee details</h4>
                                                    <div class="form-group">
                                                        <div class="col-md-6">
                                                            <a data-toggle="modal" onclick="setStructure('cpd', 'l');"
                                                                href="#customer" class="btn green btn-xs pull-right"><i
                                                                    class="fa fa-plus"></i> Add </a>
                                                            <div id="div_cpdl">
                                                                {foreach from=$structure_column item=v}
                                                                    {if $v.position=='L' && $v.selected==1}
                                                                        <div id="existcpd{$v.name}" class="form-group">
                                                                            <label
                                                                                class="col-md-4 control-label">{$v.column_name}
                                                                                <span id="cpdreq_{$v.name}" class="required">
                                                                                    {if $v.mandatory==1}* {/if}</span></label>
                                                                            <div class="col-md-6">
                                                                                {if $v.mandatory==0}
                                                                                    <div class="input-group">
                                                                                    {/if}
                                                                                    {if $v.datatype=='textarea'}
                                                                                        <textarea readonly id="cpd_{$v.name}"
                                                                                            class="form-control input-sm form-control-inline"></textarea>
                                                                                    {else}
                                                                                        <input type="text" id="cpd_{$v.name}"
                                                                                            readonly
                                                                                            class="form-control form-control-inline">
                                                                                    {/if}
                                                                                    <textarea style="display:none;"
                                                                                        id="cpdlf_{$v.name}"
                                                                                        name="cpdl[]">{$v|@json_encode}</textarea>
                                                                                    <input type="hidden" id="cpdlm_{$v.name}"
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
                                                                                            <i class="fa fa-minus-circle"></i>
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
                                                            <a data-toggle="modal" onclick="setStructure('cpd', 'r');"
                                                                href="#customer" class="btn green btn-xs pull-right"><i
                                                                    class="fa fa-plus"></i> Add </a>
                                                            <div id="div_cpdr">
                                                                {foreach from=$structure_column item=v}
                                                                    {if $v.position=='R' && $v.selected==1}
                                                                        <div id="existcpd{$v.name}" class="form-group">
                                                                            <label
                                                                                class="col-md-4 control-label">{$v.column_name}
                                                                                <span id="cpdreq_{$v.name}" class="required">
                                                                                    {if $v.mandatory==1}* {/if}</span></label>
                                                                            <div class="col-md-6">
                                                                                {if $v.mandatory==0}
                                                                                    <div class="input-group">
                                                                                    {/if}
                                                                                    {if $v.datatype=='textarea'}
                                                                                        <textarea readonly id="cpd_{$v.name}"
                                                                                            class="form-control input-sm form-control-inline"></textarea>
                                                                                    {else}
                                                                                        <input type="text" id="cpd_{$v.name}"
                                                                                            readonly
                                                                                            class="form-control form-control-inline">
                                                                                    {/if}
                                                                                    <textarea style="display:none;"
                                                                                        id="cpdrf_{$v.name}"
                                                                                        name="cpdr[]">{$v|@json_encode}</textarea>
                                                                                    <input type="hidden" id="cpdrm_{$v.name}"
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
                                                                                            <i class="fa fa-minus-circle"></i>
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
                                                    <h4 class="form-section">Capture attendees details</h4>
                                                    <div class="form-group">

                                                        <div class="col-md-6">
                                                            <a data-toggle="modal" onclick="setStructure('cad', 'l');"
                                                                href="#customer" class="btn green btn-xs pull-right"><i
                                                                    class="fa fa-plus"></i> Add </a>
                                                            <div id="div_cadl">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a data-toggle="modal" onclick="setStructure('cad', 'r');"
                                                                href="#customer" class="btn green btn-xs pull-right"><i
                                                                    class="fa fa-plus"></i> Add </a>
                                                            <div id="div_cadr">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="package1">
                                                        <hr>

                                                        <h4 class="form-section">Package

                                                            <a hhref="javascript:;" onclick="clonepackage('1');"
                                                                class="btn btn-xs green pull-right">
                                                                <i class="fa fa-copy"></i> Clone </a>
                                                            <a hhref="javascript:;" onclick="addeventpackage();"
                                                                class="btn btn-xs green pull-right">
                                                                <i class="fa fa-plus"></i> Add </a>

                                                        </h4>

                                                        <div id="exist1">
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Category name
                                                                    <span class="popovers" data-container="body"
                                                                        data-trigger="hover"
                                                                        data-content="i.	Category can be used to add your packages into logical customer friendly buckets. Example of categories – Season Pass, U-21 Pass, Senior Citizen Pass ii.	Categories are useful as it enables your customer to navigate directly into the package category of their choice while viewing your event.
                                                                                                                      iii.	The least & highest cost from all the packages added into a category will be automatically shown in the event view page"
                                                                        data-original-title="" title=""><i
                                                                            class="fa fa-info-circle"
                                                                            aria-hidden="true"></i></span><span
                                                                        class="required"> </span></label>
                                                                <div class="col-md-6" id="firstcatdiv">
                                                                    <select class="form-control" name="category_name[]"
                                                                        id="cat1d" data-placeholder="Category name">
                                                                        <option value="">Select Category</option>
                                                                    </select>

                                                                </div>
                                                                <div class="col-md-1 no-margin no-padding">

                                                                    <a title="Add new" href="#basic" data-toggle="modal"
                                                                        class="btn btn-xs green"><i
                                                                            class="fa fa-plus"></i>
                                                                        Add new </a>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Package name <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-6">
                                                                    <input type="text" name="package_name[]"
                                                                        maxlength="250" id="pkg1d" required
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Package
                                                                    description
                                                                    <span class="required">* </span></label>
                                                                <div class="col-md-6">
                                                                    <textarea name="package_description[]" id="desc1d"
                                                                        required
                                                                        class="form-control form-control-inline"></textarea>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Available <span
                                                                        class="bookunit">Seats</span> <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-3">
                                                                    <input class="form-control form-control-inline"
                                                                        id="avqty1d" required min="0" type="number"
                                                                        name="unitavailable[]" value="0" />
                                                                    <span class="help-block"></span>
                                                                </div>
                                                                (Keep 0 for unlimited Bookings)
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Min <span
                                                                        class="bookunit">Seats</span> <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-2">
                                                                    <input type="number" min="1" id="minqty1d" max="100"
                                                                        maxlength="5"
                                                                        onchange="validatemax('minqty1d', 'maxqty1d');"
                                                                        name="min_seat[]" value="1" required
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>

                                                                <label class="col-md-2 control-label">Max <span
                                                                        class="bookunit">Seats</span> <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-2">
                                                                    <input type="number" min="1" id="maxqty1d"
                                                                        name="max_seat[]" max="100" maxlength="5"
                                                                        value="1" required
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Pricing Type <span
                                                                        class="popovers" data-container="body"
                                                                        data-trigger="hover"
                                                                        data-content="Change this to Flexible, if you would like your audience to decide how much they want to pay to attend the event. Mostly, applicable if you are creating an event for a NGO or an event to collect donations for a cause. If you change this to 'Flexible' you will be provided two boxes to enter the Min amount & Max amount. Your users can pay any amount between the minimum and maximum amounts entered by you."
                                                                        data-original-title="" title=""><i
                                                                            class="fa fa-info-circle"
                                                                            aria-hidden="true"></i></span></label>
                                                                <div class="col-md-2">
                                                                    <select class="form-control" onchange="flexible(1);"
                                                                        name="is_flexible[]" id="is_flexible1"
                                                                        data-placeholder="Pricing type">
                                                                        <option value="0">Standard</option>
                                                                        <option value="1">Flexible</option>
                                                                        <option value="2">Free</option>
                                                                    </select>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group" id="flixible_div1"
                                                                style="display: none;">
                                                                <label class="col-md-4 control-label">Min amount <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-2">
                                                                    <input step="0.01" value="0" type="number" id="minpr1d"
                                                                        name="min_price[]" maxlength="12"
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>

                                                                <label class="col-md-2 control-label">Max amount <span
                                                                        class="required">* </span></label>
                                                                <div class="col-md-2">
                                                                    <input step="0.01" value="0" type="number" id="maxpr1d"
                                                                        name="max_price[]" maxlength="12"
                                                                        class="form-control form-control-inline">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div id="nonflixible_div1">
                                                                <div id="price_div1">
                                                                    <div class="form-group">
                                                                        <label class="col-md-4 control-label">Price
                                                                            <span class="required">* </span></label>
                                                                        <div class="col-md-3">
                                                                            <input name="unitcost[]" required
                                                                                id="price1d"
                                                                                class="form-control form-control-inline"
                                                                                max="100000" maxlength="11" step="0.01"
                                                                                type="number" />
                                                                            <span class="help-block"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">Coupon</label>
                                                                    <div class="col-md-2" id="coupon">
                                                                        <select class="form-control" id="coup1d"
                                                                            name="package_coupon[]"
                                                                            data-placeholder="Coupon code">
                                                                            {if empty($coupons)}
                                                                                <option value="0">No active coupons</option>
                                                                            {else}
                                                                                <option value="0">Select coupon</option>
                                                                            {/if}
                                                                            {foreach from=$coupons key=k item=v}
                                                                                <option value="{$v.coupon_id}">
                                                                                    {$v.coupon_code}
                                                                                </option>
                                                                            {/foreach}
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">Tax (if
                                                                        applicable)</label>
                                                                    <div class="col-md-3">
                                                                        <input name="tax_text[]" id="taxtext1d"
                                                                            placeholder="Tax label" maxlength="45"
                                                                            class="form-control form-control-inline"
                                                                            type="text" value="GST" />
                                                                        <span class="help-block"></span>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input name="tax[]" id="tax1d" step="0.01"
                                                                            min="0" max="100" maxlength="7"
                                                                            class="form-control form-control-inline"
                                                                            type="number" value="" />
                                                                        <span class="help-block"></span>
                                                                    </div>%
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-4 control-label">Package
                                                                    type</label>
                                                                <div class="col-md-8">
                                                                    <label class="control-label">
                                                                        <input type="radio" checked class="md-radiobtn"
                                                                            onchange="changePackageType(1, this.value);"
                                                                            name="package_type1[]" value="1">
                                                                        Normal Pass</label>
                                                                    <br><label class="control-label">
                                                                        <input type="radio" class="md-radiobtn"
                                                                            onchange="changePackageType(1, this.value);"
                                                                            name="package_type1[]" value="2">
                                                                        Season Pass </label>
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" name="package_int[]" value="1">
                                                            </div>
                                                            <div class="form-group" id="multidatediv1d">
                                                                <label class="control-label col-md-4">Choose available
                                                                    date
                                                                    <span class="required"> </span></label>
                                                                <div class="col-md-8" id="multidate1d"
                                                                    style="height: 80px; overflow: auto; width: 300px;">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div id="newpackage" class="col-md-12">
                                                    </div>
                                                    <hr>


                                                    <h4 class="form-section">Coupon details</h4>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Coupon (for all
                                                            packages)&nbsp;</label>
                                                        <div class="col-md-6">
                                                            <select class="form-control" name="coupon_code"
                                                                data-placeholder="Coupon code">
                                                                {if empty($coupons)}
                                                                    <option value="0">No active coupons</option>
                                                                {else}
                                                                    <option value="0">Select coupon</option>
                                                                {/if}

                                                                {foreach from=$coupons key=k item=v}
                                                                    <option value="{$v.coupon_id}">{$v.coupon_code}</option>
                                                                {/foreach}

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" style="display: none;"
                                                        id="custom_capture_detail">
                                                        <label class="col-md-4 control-label"></label>
                                                        <div class="col-md-6">

                                                            <h4>Add custom fields <a onclick="reset();"
                                                                    data-toggle="modal" href="#custom"
                                                                    style="margin-right: 15px;"
                                                                    class="btn btn-sm green pull-right"> <i
                                                                        class="fa fa-plus"> </i> Add custom field </a>
                                                            </h4>

                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <h4 class="form-section">Terms & Conditions</h4>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Terms & Conditions</label>
                                                        <div class="col-md-8">
                                                            <textarea name="event_tnc"
                                                                class="form-control description"></textarea>

                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Cancellation
                                                            policy</label>
                                                        <div class="col-md-8">
                                                            <textarea name="cancellation_policy"
                                                                class="form-control description"></textarea>

                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="pull-right">
                                                            {CSRF::create('event_create')}
                                                            <button type="reset" class="btn btn-default">Reset</button>
                                                            <button type="submit" class="btn blue"><i
                                                                    class="fa fa-check"></i> Save</button>
                                                            <input type="hidden" name="event_type" value="{$type}">

                                                        </div>
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
                                <div class="col-md-7">
                                    <select class="form-control input-sm" id="custom_column_datatype"
                                        onchange="changeeventcustom_datatype(this.value);"
                                        data-placeholder="Select type">
                                        <option value="text">Text</option>
                                        <option value="textarea">Text area</option>
                                        <option value="number">Number</option>
                                        <option value="money">Money</option>
                                        <option value="percent">Percentage</option>
                                        <option value="date">Date</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="multicheckbox">Multi Checkbox</option>
                                    </select>
                                    <span class="help-block">
                                    </span>
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword12" class="col-md-5 control-label">Section name</label>
                                <div class="col-md-7">
                                    <input class="form-control form-control-inline input-sm" id="custom_column_section"
                                        type="text" value="" placeholder="Section name" />
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword12" class="col-md-5 control-label">Column name</label>
                                <div class="col-md-7">
                                    <input class="form-control form-control-inline input-sm" id="custom_column_name"
                                        type="text" value="" placeholder="Column name" />
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword12" class="col-md-5 control-label">Is mandatory?</label>
                                <div class="col-md-7">
                                    <input class="form-control form-control-inline input-sm"
                                        id="custom_column_mandatory" type="checkbox" value="1" />
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group" style="display: none;" id="custom_colum_value_div">
                                <label for="inputPassword12" class="col-md-5 control-label">Column values</label>
                                <div class="col-md-7">
                                    <input class="form-control form-control-inline input-sm" id="custom_column_name"
                                        type="text" value="" placeholder="Column name" />
                                    <span class="help-block">
                                    </span>
                                </div>
                            </div>
                            <div class="form-group" id="bank_transaction_no">
                                <label for="inputPassword12" class="col-md-5 control-label">Column description</label>
                                <div class="col-md-7">
                                    <textarea class="form-control form-control-inline" /></textarea>
                                    <span class="help-block">
                                    </span>
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
                <button onclick="saveCategory(0);" class="btn blue">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
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