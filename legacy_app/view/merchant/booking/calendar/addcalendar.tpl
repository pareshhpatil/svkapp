<style>
    .weekDays-selector input[type=checkbox] {
        display: inline-block !important;
        border-radius: 6px !important;
        background: #dddddd !important;
        min-width: 35px !important;
        padding: 0.4rem !important;
        margin-right: 3px !important;
        line-height: 40px !important;
        text-align: center !important;
        cursor: pointer !important;
    }

    .weekDays-selector label {
        display: inline-block !important;
        border-radius: 6px !important;
        background: #dddddd !important;
        min-width: 35px !important;
        padding: 0.4rem !important;
        margin-right: 3px !important;
        line-height: 40px !important;
        text-align: center !important;
        cursor: pointer !important;
    }

    .weekDays-selector input[type=checkbox]:checked {
        background: #18aebf !important;
        color: #ffffff !important;
    }

    .weekDays-selector label {
        background: #18aebf !important;
        color: #ffffff !important;
    }


    .weekDays-selector input[type=checkbox] {
        display: none !important;
    }


    .btn-link {
        color: #6F8181;
        padding: 0px;
    }

    .form-actions {
        background-color: #ffffff !important;
        border-top: none !important;
    }

    .weekDays-selector input {
        display: none !important;
    }

    .weekDays-selector input[type=checkbox]+label {
        display: inline-block;
        border-radius: 6px;
        background: #dddddd;
        min-width: 35px;
        padding: 0.4rem;
        margin-right: 3px;
        line-height: 40px;
        text-align: center;
        cursor: pointer;
    }

    .weekDays-selector input[type=checkbox]:checked+label {
        background: #18aebf;
        color: #ffffff;
    }

    .multiselect-container>li>a>label.checkbox {
        width: 220px;
        padding-left: 20px;
    }

    .btn-group>.btn:first-child {
        width: 220px;
    }

    .has-success .form-control {
        border-color: #e5e5e5;
    }

    .has-success .control-label {
        color: #000;
    }
</style>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
            <div class="" id="form_wizard_1">
                <div class="form">
                    <form action="/merchant/bookings/savecalendar"
                        onsubmit="document.getElementById('loader').style.display = 'block';" name="addslot"
                        class="form-horizontal" id="submit_form" method="POST" enctype="multipart/form-data">
                        {CSRF::create('calender_save')}
                        <div class="form-wizard">
                            <div class="form-body">
                                <div class="portlet ">
                                    <div class="portlet-body">
                                        <ul class="nav nav-pills nav-justified steps">
                                            <li>
                                                <a href="#tab1" data-toggle="tab" class="step">
                                                    <span class="number">
                                                        1 </span>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Calendar Info </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab2" data-toggle="tab" class="step">
                                                    <span class="number">
                                                        2 </span>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Calendar Slots </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab3" data-toggle="tab" class="step active">
                                                    <span class="number">
                                                        3 </span>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Holidays </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab4" data-toggle="tab" class="step active">
                                                    <span class="number">
                                                        4 </span>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Notification & Data</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#tab5" data-toggle="tab" class="step">
                                                    <span class="number">
                                                        5 </span>
                                                    <span class="desc">
                                                        <i class="fa fa-check"></i> Confirm </span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div id="bar" class="progress progress-striped" role="progressbar">
                                            <div class="progress-bar progress-bar-success">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>

                                    <div class="alert alert-success display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        Your form validation is successful!
                                    </div>


                                    <div class="tab-pane active" id="tab1">
                                        <div class="portlet">
                                            <div class="portlet-body">
                                                <h3 class="block">Calendar Info</h3>
                                                <div class="row">

                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-6">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="validation_error_div">
                                                            <span id="validation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Category name
                                                                <span class="required">*
                                                                </span>
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Pick a category in which you would like to place this new calendar"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-6">
                                                                <select name="category" id="cat_drop" required
                                                                    class="form-control" data-placeholder="Select...">
                                                                    <option value="">Select Category</option>
                                                                    {foreach from=$category_list item=v}
                                                                        <option value="{$v.category_id}"> {$v.category_name}
                                                                        </option>
                                                                    {/foreach}
                                                                </select>
                                                                <span class="help-block"></span>
                                                            </div>
                                                            <div class="col-md-1 no-margin no-padding">
                                                                <a data-toggle="modal" title="Add new customer"
                                                                    href="#custom" class="btn green"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Calendar title
                                                                <span class="required">*
                                                                </span>
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Title of the calendar you are creating. Eg. If you are creating a calendar under the category tennis court then calendar title can be set as 'Court 1'"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input type="text" required maxlength="45"
                                                                    {$validate.name} value="" name="calendar_name"
                                                                    class="form-control" />
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Booking unit
                                                                <span class="required">*
                                                                </span>
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Name of the unit that your customer will booking Eg. seat, court, ground, venue, etc."
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-7">
                                                                <input type="text" required maxlength="20"
                                                                    {$validate.name} value="Court" name="booking_unit"
                                                                    class="form-control" />
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Max booking allowed
                                                                <span class="required">*
                                                                </span>
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content=" Number of slot a customer can book in one booking on this calendar. Keep unlimited if there are no restrictions on the number of slots that can be booked"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-7">
                                                                <select name="max_booking" required class="form-control"
                                                                    data-placeholder="Select...">
                                                                    <option value="0">Unlimited</option>
                                                                    {for $foo=1 to 10}
                                                                        <option value="{$foo}"> {$foo}</option>
                                                                    {/for}
                                                                </select>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Description
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Description of your venue"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-7">
                                                                <textarea class="form-control" name="description"
                                                                    maxlength="500"></textarea>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Select image
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Image depicting your venue"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                            </label>
                                                            <div class="col-md-6 fileinput fileinput-new"
                                                                data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail"
                                                                    style="width: 200px; height: 130px;">
                                                                    <img src="/assets/admin/layout/img/nologo.gif"
                                                                        class="img-responsive templatelogo" alt="" />
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                    style="max-width: 200px; max-height: 150px;">
                                                                </div>
                                                                <div>
                                                                    <span class="btn btn-sm default btn-file">
                                                                        <span class="fileinput-new">
                                                                            Select image </span>
                                                                        <span class="fileinput-exists">
                                                                            Change </span>
                                                                        <input
                                                                            onchange="return validatefilesize(500000);"
                                                                            id="imgupload" type="file" accept="image/*"
                                                                            name="uploaded_file">
                                                                    </span>
                                                                    <a href="javascript:;" id="imgdismiss"
                                                                        class="btn-sm btn default fileinput-exists"
                                                                        data-dismiss="fileinput">
                                                                        Remove </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane " id="tab2">
                                        <div id="ddl_1">
                                            <div class="portlet">
                                                <div class="portlet-body">
                                                    <div class="row">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-9">
                                                            <div class="alert"
                                                                style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                                id="validation_error_div2">
                                                                <span id="validation_error2"></span>
                                                            </div>
                                                            <h4>Insert your preferences to create the time slots</h4>
                                                            <h5>Maximum 2000 slots can be created at one go i.e. you can
                                                                create
                                                                slots for a maximum of 3 months in the future.</h5>
                                                            <div class="">
                                                                <!-- Start profile details -->
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="hidden" id="package_number"
                                                                            name="package_number[]" value="1">
                                                                        <div class="row">
                                                                            <div class="col-md-5">
                                                                                <h3>Package</h3>
                                                                            </div>
                                                                            <div class="col-md-7">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12">
                                                                                        Package Name
                                                                                        <input type="text" required
                                                                                            name="package_name1"
                                                                                            id="package_name1"
                                                                                            class="form-control form-control-inline">
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-5">
                                                                            </div>
                                                                            <div class="col-md-7">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12">
                                                                                        Package Description
                                                                                        <textarea name="package_desc1"
                                                                                            id="package_desc1" rows="4"
                                                                                            maxlength="350"
                                                                                            class="form-control form-control-inline"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-5">
                                                                            </div>
                                                                            <div class="col-md-7">
                                                                                <div class="form-group">
                                                                                    <div class="col-md-12">
                                                                                        Package Image
                                                                                        <div class="form-group">
                                                                                            <div class="col-md-6 fileinput fileinput-new"
                                                                                                data-provides="fileinput">
                                                                                                <div class="fileinput-new thumbnail"
                                                                                                    style="width: 200px; height: 130px;">
                                                                                                    <img src="/assets/admin/layout/img/nologo.gif"
                                                                                                        class="img-responsive templatelogo"
                                                                                                        alt="" />
                                                                                                </div>
                                                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                                                    style="max-width: 200px; max-height: 150px;">
                                                                                                </div>
                                                                                                <div>
                                                                                                    <span
                                                                                                        class="btn btn-sm default btn-file">
                                                                                                        <span
                                                                                                            class="fileinput-new">
                                                                                                            Select image
                                                                                                        </span>
                                                                                                        <span
                                                                                                            class="fileinput-exists">
                                                                                                            Change
                                                                                                        </span>
                                                                                                        <input
                                                                                                            onchange="return validatefilesizev2(500000,'package_image1', 'package_image_dismiss1');"
                                                                                                            id="package_image1"
                                                                                                            type="file"
                                                                                                            accept="image/*"
                                                                                                            name="package_image1">
                                                                                                    </span>
                                                                                                    <a href="javascript:;"
                                                                                                        id="package_image_dismiss1"
                                                                                                        class="btn-sm btn default fileinput-exists"
                                                                                                        data-dismiss="fileinput">
                                                                                                        Remove </a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="javascript:;" onclick="cloneBookingPackage(1);"
                                                                class="btn btn-xs green pull-right">
                                                                <i class="fa fa-copy"></i> Clone </a>
                                                            <a href="javascript:;" onclick="addBookingPackage(1);"
                                                                class="btn btn-xs green pull-right">
                                                                <i class="fa fa-plus"></i> Add </a>
                                                            <a style="display:none;" href="javascript:;"
                                                                onclick="removeBookingPackage(1)"
                                                                class="btn btn-xs red pull-right">
                                                                <i class="fa fa-close"></i> remove </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <h3>Date</h3>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <div class="col-md-6">
                                                                            From
                                                                            <button
                                                                                class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                                data-container="body"
                                                                                data-placement="top"
                                                                                data-trigger="hover"
                                                                                data-content="Your venue will be available from this date for bookings"
                                                                                type="button">
                                                                                <i class="fa fa-info-circle"></i>
                                                                            </button>
                                                                            <span class="required">*
                                                                            </span>
                                                                            <input
                                                                                class="form-control form-control-inline date-picker"
                                                                                readonly type="text" required
                                                                                name="slot_date_from1"
                                                                                id="slot_date_from1"
                                                                                style="background-color: #ffffff;cursor: pointer;"
                                                                                autocomplete="off"
                                                                                data-date-format="dd M yyyy"
                                                                                placeholder="From date" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            To
                                                                            <button
                                                                                class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                                data-container="body"
                                                                                data-placement="top"
                                                                                data-trigger="hover"
                                                                                data-content="Your venue will be available till this date for bookings. This To date field can be kept empty if you are creating the calendar for only 1 day"
                                                                                type="button">
                                                                                <i class="fa fa-info-circle"></i>
                                                                            </button>
                                                                            <div class="input-group">
                                                                                <input readonly
                                                                                    onchange="showweekdays(this.value);"
                                                                                    class="form-control form-control-inline  date-picker"
                                                                                    type="text"
                                                                                    style="background-color: #ffffff;cursor: pointer;"
                                                                                    name="slot_date_to1"
                                                                                    id="slot_date_to1"
                                                                                    autocomplete="off"
                                                                                    data-date-format="dd M yyyy"
                                                                                    placeholder="To date" />
                                                                                <span class="input-group-btn">
                                                                                    <div class="btn default"
                                                                                        onclick="clearSeconddate(1);">
                                                                                        <i class="fa fa-remove"></i>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="" id="weekdays" style="display: none;">
                                                                    <hr />
                                                                    <div class="col-md-5">
                                                                        <h3>Days</h3>
                                                                    </div>
                                                                    <div class="col-md-7 form-group">
                                                                        <div class="col-md-12">
                                                                            <div class="weekDays-selector">
                                                                                <input type="checkbox" id="weekday-mon1"
                                                                                    class="weekday"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    name="slot_weekday1[]" value="1"
                                                                                    checked />
                                                                                <label id="weekday-mon1-div"
                                                                                    for="weekday-mon1">Mon</label>

                                                                                <input type="checkbox" id="weekday-tue1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    class="weekday"
                                                                                    name="slot_weekday1[]" value="2"
                                                                                    checked />

                                                                                <label id="weekday-tue1-div"
                                                                                    for="weekday-tue1">Tue</label>

                                                                                <input type="checkbox" id="weekday-wed1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    name="slot_weekday1[]"
                                                                                    class="weekday" value="3" checked />

                                                                                <label id="weekday-wed1-div"
                                                                                    for="weekday-wed1">Wed</label>

                                                                                <input type="checkbox" id="weekday-thu1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    name="slot_weekday1[]"
                                                                                    class="weekday" value="4" checked />

                                                                                <label id="weekday-thu1-div"
                                                                                    for="weekday-thu1">Thu</label>

                                                                                <input type="checkbox" id="weekday-fri1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    name="slot_weekday1[]"
                                                                                    class="weekday" value="5" checked />

                                                                                <label id="weekday-fri1-div"
                                                                                    for="weekday-fri1">Fri</label>

                                                                                <input type="checkbox" id="weekday-sat1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    name="slot_weekday1[]"
                                                                                    class="weekday" value="6" checked />

                                                                                <label id="weekday-sat1-div"
                                                                                    for="weekday-sat1">Sat</label>

                                                                                <input type="checkbox" id="weekday-sun1"
                                                                                    onchange="dateCheckboxclicked()"
                                                                                    class="weekday"
                                                                                    name="slot_weekday1[]" value="7"
                                                                                    checked />

                                                                                <label id="weekday-sun1-div"
                                                                                    for="weekday-sun1">Sun</label>
                                                                            </div>
                                                                            {* <label
                                                                        class="control-label"><input
                                                                            class="icheck"
                                                                            type="checkbox"
                                                                            name="selectAll"
                                                                            id="slot_weekday"
                                                                            value="0"
                                                                            onclick="javascript:selectCheckbox('addslot', 'slot_weekday[]');"
                                                                            checked="checked">&nbsp;All</label><br> *}
                                                                            {* <label class="control-label">
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="1"
                                                                                checked="checked">&nbsp;Mondays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="2"
                                                                                checked="checked">&nbsp;Tuesdays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="3"
                                                                                checked="checked">&nbsp;Wednesdays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="4"
                                                                                checked="checked">&nbsp;Thursdays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="5"
                                                                                checked="checked">&nbsp;Fridays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="6"
                                                                                checked="checked">&nbsp;Saturdays</label><br>
                                                                        <label
                                                                            class="control-label pull-left"><input
                                                                                class="icheck"
                                                                                onclick="javascript:disableSelectAll('addslot', this.checked);"
                                                                                type="checkbox"
                                                                                name="slot_weekday1[]"
                                                                                id="slot_weekday1"
                                                                                value="7"
                                                                                checked="checked">&nbsp;Sundays</label> *}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <h3>Slot Duration
                                                                        <button
                                                                            class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                            data-container="body" data-placement="top"
                                                                            data-trigger="hover"
                                                                            data-content="Define the duration of your time slots. Your venue will be available for the allocated time slot"
                                                                            type="button">
                                                                            <i class="fa fa-info-circle"></i>
                                                                        </button>
                                                                        <span class="required">*
                                                                        </span>
                                                                    </h3>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <div class="col-md-6">
                                                                            Select the time duration for your
                                                                            time slots
                                                                            <select name="slot_interval1"
                                                                                class="form-control" required
                                                                                id="slot_interval1"
                                                                                onchange="showCustom(this.value,1)">
                                                                                <option value="">choose duration
                                                                                </option>
                                                                                <option value="1">in minutes
                                                                                </option>
                                                                                <option value="0">from, to
                                                                                </option>
                                                                            </select>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br />

                                                            <div style="display: none;" id="tymin1">
                                                                <div class="row">
                                                                    <div class="col-md-5">

                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <div class="col-md-6">
                                                                                Type here the minutes
                                                                                <input type="text"
                                                                                    name="slot_interval_minutes1"
                                                                                    id="slot_interval_minutes1"
                                                                                    style="width: 300px;"
                                                                                    class="form-control form-control-inline">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br />

                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <h3>Pause between time slots <button
                                                                                class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                                data-container="body"
                                                                                data-placement="top"
                                                                                data-trigger="hover"
                                                                                data-content="Add an interval between two time slots as per your requirements. Specify the duration of your interval"
                                                                                type="button">
                                                                                <i class="fa fa-info-circle"></i>
                                                                            </button></h3>
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="form-group">
                                                                            <div class="col-md-6">
                                                                                Pause time in minute
                                                                                <input type="number" min="0"
                                                                                    name="slot_pause1" id="slot_pause1"
                                                                                    style="width: 300px;"
                                                                                    class="form-control form-control-inline">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr />

                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <h3>Time Period</h3>
                                                                        Create the time frame for when your time
                                                                        slots
                                                                        are available.
                                                                        You can set the time perios as per your
                                                                        requirements.
                                                                        If you assign a time slot for a fixed
                                                                        hour (e.g.
                                                                        6:00),
                                                                        ensure to select minutes (e.g. 00) from
                                                                        the
                                                                        drop-down list. Without which,
                                                                        a "Duplicated Slots" error will appear
                                                                    </div>
                                                                    <div class="col-md-7">
                                                                        <div class="portlet-body">
                                                                            <div class="table-scrollable">
                                                                                <table
                                                                                    class="table table-bordered table-hover"
                                                                                    id="particular_table"
                                                                                    style="width: 300px;">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="td-c">
                                                                                                From
                                                                                            </th>
                                                                                            <th class="td-c">
                                                                                                To
                                                                                            </th>

                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="width: 300px;">
                                                                                                <div
                                                                                                    class="input-group">
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_from_hour1[]"
                                                                                                        id="slot_time_from_hour1"
                                                                                                        style="width:85px">
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Hour
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="01">
                                                                                                            1
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="02">
                                                                                                            2
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="03">
                                                                                                            3
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="04">
                                                                                                            4
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="05">
                                                                                                            5
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="06">
                                                                                                            6
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="07">
                                                                                                            7
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="08">
                                                                                                            8
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="09">
                                                                                                            9
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="10">
                                                                                                            10
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="11">
                                                                                                            11
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="12">
                                                                                                            12
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    &nbsp;&nbsp;&nbsp;
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_from_minute1[]"
                                                                                                        id="slot_time_from_minute1"
                                                                                                        style="width:90px">

                                                                                                        <option
                                                                                                            value="00">
                                                                                                            00
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="01">
                                                                                                            01
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="02">
                                                                                                            02
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="03">
                                                                                                            03
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="04">
                                                                                                            04
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="05">
                                                                                                            05
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="06">
                                                                                                            06
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="07">
                                                                                                            07
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="08">
                                                                                                            08
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="09">
                                                                                                            09
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="10">
                                                                                                            10
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="11">
                                                                                                            11
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="12">
                                                                                                            12
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="13">
                                                                                                            13
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="14">
                                                                                                            14
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="15">
                                                                                                            15
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="16">
                                                                                                            16
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="17">
                                                                                                            17
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="18">
                                                                                                            18
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="19">
                                                                                                            19
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="20">
                                                                                                            20
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="21">
                                                                                                            21
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="22">
                                                                                                            22
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="23">
                                                                                                            23
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="24">
                                                                                                            24
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="25">
                                                                                                            25
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="26">
                                                                                                            26
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="27">
                                                                                                            27
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="28">
                                                                                                            28
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="29">
                                                                                                            29
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="30">
                                                                                                            30
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="31">
                                                                                                            31
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="32">
                                                                                                            32
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="33">
                                                                                                            33
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="34">
                                                                                                            34
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="35">
                                                                                                            35
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="36">
                                                                                                            36
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="37">
                                                                                                            37
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="38">
                                                                                                            38
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="39">
                                                                                                            39
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="40">
                                                                                                            40
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="41">
                                                                                                            41
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="42">
                                                                                                            42
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="43">
                                                                                                            43
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="44">
                                                                                                            44
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="45">
                                                                                                            45
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="46">
                                                                                                            46
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="47">
                                                                                                            47
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="48">
                                                                                                            48
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="49">
                                                                                                            49
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="50">
                                                                                                            50
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="51">
                                                                                                            51
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="52">
                                                                                                            52
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="53">
                                                                                                            53
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="54">
                                                                                                            54
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="55">
                                                                                                            55
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="56">
                                                                                                            56
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="57">
                                                                                                            57
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="58">
                                                                                                            58
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="59">
                                                                                                            59
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_from_ampm[]1"
                                                                                                        id="slot_time_from_ampm1"
                                                                                                        style="width:80px">
                                                                                                        <option
                                                                                                            value="am">
                                                                                                            am
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="pm">
                                                                                                            pm
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </td>
                                                                                            <td style="width: 300px;">
                                                                                                <div
                                                                                                    class="input-group">
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_to_hour1[]"
                                                                                                        id="slot_time_to_hour1"
                                                                                                        style="width:85px">
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Hour
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="01">
                                                                                                            1
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="02">
                                                                                                            2
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="03">
                                                                                                            3
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="04">
                                                                                                            4
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="05">
                                                                                                            5
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="06">
                                                                                                            6
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="07">
                                                                                                            7
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="08">
                                                                                                            8
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="09">
                                                                                                            9
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="10">
                                                                                                            10
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="11">
                                                                                                            11
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="12">
                                                                                                            12
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    &nbsp;&nbsp;&nbsp;
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_to_minute1[]"
                                                                                                        id="slot_time_to_minute1"
                                                                                                        style="width:90px">

                                                                                                        <option
                                                                                                            value="00">
                                                                                                            00
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="01">
                                                                                                            01
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="02">
                                                                                                            02
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="03">
                                                                                                            03
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="04">
                                                                                                            04
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="05">
                                                                                                            05
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="06">
                                                                                                            06
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="07">
                                                                                                            07
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="08">
                                                                                                            08
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="09">
                                                                                                            09
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="10">
                                                                                                            10
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="11">
                                                                                                            11
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="12">
                                                                                                            12
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="13">
                                                                                                            13
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="14">
                                                                                                            14
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="15">
                                                                                                            15
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="16">
                                                                                                            16
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="17">
                                                                                                            17
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="18">
                                                                                                            18
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="19">
                                                                                                            19
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="20">
                                                                                                            20
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="21">
                                                                                                            21
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="22">
                                                                                                            22
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="23">
                                                                                                            23
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="24">
                                                                                                            24
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="25">
                                                                                                            25
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="26">
                                                                                                            26
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="27">
                                                                                                            27
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="28">
                                                                                                            28
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="29">
                                                                                                            29
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="30">
                                                                                                            30
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="31">
                                                                                                            31
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="32">
                                                                                                            32
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="33">
                                                                                                            33
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="34">
                                                                                                            34
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="35">
                                                                                                            35
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="36">
                                                                                                            36
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="37">
                                                                                                            37
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="38">
                                                                                                            38
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="39">
                                                                                                            39
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="40">
                                                                                                            40
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="41">
                                                                                                            41
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="42">
                                                                                                            42
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="43">
                                                                                                            43
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="44">
                                                                                                            44
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="45">
                                                                                                            45
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="46">
                                                                                                            46
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="47">
                                                                                                            47
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="48">
                                                                                                            48
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="49">
                                                                                                            49
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="50">
                                                                                                            50
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="51">
                                                                                                            51
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="52">
                                                                                                            52
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="53">
                                                                                                            53
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="54">
                                                                                                            54
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="55">
                                                                                                            55
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="56">
                                                                                                            56
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="57">
                                                                                                            57
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="58">
                                                                                                            58
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="59">
                                                                                                            59
                                                                                                        </option>
                                                                                                    </select>
                                                                                                    <select
                                                                                                        class="form-control input-sm"
                                                                                                        name="slot_time_to_ampm1[]"
                                                                                                        id="slot_time_to_ampm1"
                                                                                                        style="width:80px">
                                                                                                        <option
                                                                                                            value="am">
                                                                                                            am
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="pm">
                                                                                                            pm
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </td>

                                                                                        </tr>
                                                                                    </tbody>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr />
                                                            </div>

                                                            <div class="row" style="display: none;" id="tyfromto1">
                                                                <div class="col-md-5"></div>
                                                                <div class="col-md-7">
                                                                    <div class="portlet-body">
                                                                        <div class="table-scrollable">
                                                                            <table
                                                                                class="table table-bordered table-hover"
                                                                                id="particular_table"
                                                                                style="width: 290px;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="td-c">
                                                                                            From
                                                                                        </th>
                                                                                        <th class="td-c">
                                                                                            To
                                                                                        </th>
                                                                                        <th class="td-c">

                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="new_duration1">
                                                                                    <tr>
                                                                                        <td style="width: 405px;">
                                                                                            <div class="input-group">
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_from_hour1[]"
                                                                                                    id="slot_interval_custom_from_hour11"
                                                                                                    style="width:85px">
                                                                                                    <option value="">
                                                                                                        Hour
                                                                                                    </option>
                                                                                                    <option value="01">
                                                                                                        1
                                                                                                    </option>
                                                                                                    <option value="02">
                                                                                                        2
                                                                                                    </option>
                                                                                                    <option value="03">
                                                                                                        3
                                                                                                    </option>
                                                                                                    <option value="04">
                                                                                                        4
                                                                                                    </option>
                                                                                                    <option value="05">
                                                                                                        5
                                                                                                    </option>
                                                                                                    <option value="06">
                                                                                                        6
                                                                                                    </option>
                                                                                                    <option value="07">
                                                                                                        7
                                                                                                    </option>
                                                                                                    <option value="08">
                                                                                                        8
                                                                                                    </option>
                                                                                                    <option value="09">
                                                                                                        9
                                                                                                    </option>
                                                                                                    <option value="10">
                                                                                                        10
                                                                                                    </option>
                                                                                                    <option value="11">
                                                                                                        11
                                                                                                    </option>
                                                                                                    <option value="12">
                                                                                                        12
                                                                                                    </option>
                                                                                                </select>
                                                                                                &nbsp;&nbsp;&nbsp;
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_from_minute1[]"
                                                                                                    id="slot_interval_custom_from_minute11"
                                                                                                    style="width:90px">

                                                                                                    <option value="00">
                                                                                                        00
                                                                                                    </option>
                                                                                                    <option value="01">
                                                                                                        01
                                                                                                    </option>
                                                                                                    <option value="02">
                                                                                                        02
                                                                                                    </option>
                                                                                                    <option value="03">
                                                                                                        03
                                                                                                    </option>
                                                                                                    <option value="04">
                                                                                                        04
                                                                                                    </option>
                                                                                                    <option value="05">
                                                                                                        05
                                                                                                    </option>
                                                                                                    <option value="06">
                                                                                                        06
                                                                                                    </option>
                                                                                                    <option value="07">
                                                                                                        07
                                                                                                    </option>
                                                                                                    <option value="08">
                                                                                                        08
                                                                                                    </option>
                                                                                                    <option value="09">
                                                                                                        09
                                                                                                    </option>
                                                                                                    <option value="10">
                                                                                                        10
                                                                                                    </option>
                                                                                                    <option value="11">
                                                                                                        11
                                                                                                    </option>
                                                                                                    <option value="12">
                                                                                                        12
                                                                                                    </option>
                                                                                                    <option value="13">
                                                                                                        13
                                                                                                    </option>
                                                                                                    <option value="14">
                                                                                                        14
                                                                                                    </option>
                                                                                                    <option value="15">
                                                                                                        15
                                                                                                    </option>
                                                                                                    <option value="16">
                                                                                                        16
                                                                                                    </option>
                                                                                                    <option value="17">
                                                                                                        17
                                                                                                    </option>
                                                                                                    <option value="18">
                                                                                                        18
                                                                                                    </option>
                                                                                                    <option value="19">
                                                                                                        19
                                                                                                    </option>
                                                                                                    <option value="20">
                                                                                                        20
                                                                                                    </option>
                                                                                                    <option value="21">
                                                                                                        21
                                                                                                    </option>
                                                                                                    <option value="22">
                                                                                                        22
                                                                                                    </option>
                                                                                                    <option value="23">
                                                                                                        23
                                                                                                    </option>
                                                                                                    <option value="24">
                                                                                                        24
                                                                                                    </option>
                                                                                                    <option value="25">
                                                                                                        25
                                                                                                    </option>
                                                                                                    <option value="26">
                                                                                                        26
                                                                                                    </option>
                                                                                                    <option value="27">
                                                                                                        27
                                                                                                    </option>
                                                                                                    <option value="28">
                                                                                                        28
                                                                                                    </option>
                                                                                                    <option value="29">
                                                                                                        29
                                                                                                    </option>
                                                                                                    <option value="30">
                                                                                                        30
                                                                                                    </option>
                                                                                                    <option value="31">
                                                                                                        31
                                                                                                    </option>
                                                                                                    <option value="32">
                                                                                                        32
                                                                                                    </option>
                                                                                                    <option value="33">
                                                                                                        33
                                                                                                    </option>
                                                                                                    <option value="34">
                                                                                                        34
                                                                                                    </option>
                                                                                                    <option value="35">
                                                                                                        35
                                                                                                    </option>
                                                                                                    <option value="36">
                                                                                                        36
                                                                                                    </option>
                                                                                                    <option value="37">
                                                                                                        37
                                                                                                    </option>
                                                                                                    <option value="38">
                                                                                                        38
                                                                                                    </option>
                                                                                                    <option value="39">
                                                                                                        39
                                                                                                    </option>
                                                                                                    <option value="40">
                                                                                                        40
                                                                                                    </option>
                                                                                                    <option value="41">
                                                                                                        41
                                                                                                    </option>
                                                                                                    <option value="42">
                                                                                                        42
                                                                                                    </option>
                                                                                                    <option value="43">
                                                                                                        43
                                                                                                    </option>
                                                                                                    <option value="44">
                                                                                                        44
                                                                                                    </option>
                                                                                                    <option value="45">
                                                                                                        45
                                                                                                    </option>
                                                                                                    <option value="46">
                                                                                                        46
                                                                                                    </option>
                                                                                                    <option value="47">
                                                                                                        47
                                                                                                    </option>
                                                                                                    <option value="48">
                                                                                                        48
                                                                                                    </option>
                                                                                                    <option value="49">
                                                                                                        49
                                                                                                    </option>
                                                                                                    <option value="50">
                                                                                                        50
                                                                                                    </option>
                                                                                                    <option value="51">
                                                                                                        51
                                                                                                    </option>
                                                                                                    <option value="52">
                                                                                                        52
                                                                                                    </option>
                                                                                                    <option value="53">
                                                                                                        53
                                                                                                    </option>
                                                                                                    <option value="54">
                                                                                                        54
                                                                                                    </option>
                                                                                                    <option value="55">
                                                                                                        55
                                                                                                    </option>
                                                                                                    <option value="56">
                                                                                                        56
                                                                                                    </option>
                                                                                                    <option value="57">
                                                                                                        57
                                                                                                    </option>
                                                                                                    <option value="58">
                                                                                                        58
                                                                                                    </option>
                                                                                                    <option value="59">
                                                                                                        59
                                                                                                    </option>
                                                                                                </select>
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_from_ampm1[]"
                                                                                                    id="slot_interval_custom_from_ampm11"
                                                                                                    style="width:85px">
                                                                                                    <option value="am">
                                                                                                        am
                                                                                                    </option>
                                                                                                    <option value="pm">
                                                                                                        pm
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td style="width: 405px;">
                                                                                            <div class="input-group">
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_to_hour1[]"
                                                                                                    id="slot_interval_custom_to_hour11"
                                                                                                    style="width:85px">
                                                                                                    <option value="">
                                                                                                        Hour
                                                                                                    </option>
                                                                                                    <option value="01">
                                                                                                        1
                                                                                                    </option>
                                                                                                    <option value="02">
                                                                                                        2
                                                                                                    </option>
                                                                                                    <option value="03">
                                                                                                        3
                                                                                                    </option>
                                                                                                    <option value="04">
                                                                                                        4
                                                                                                    </option>
                                                                                                    <option value="05">
                                                                                                        5
                                                                                                    </option>
                                                                                                    <option value="06">
                                                                                                        6
                                                                                                    </option>
                                                                                                    <option value="07">
                                                                                                        7
                                                                                                    </option>
                                                                                                    <option value="08">
                                                                                                        8
                                                                                                    </option>
                                                                                                    <option value="09">
                                                                                                        9
                                                                                                    </option>
                                                                                                    <option value="10">
                                                                                                        10
                                                                                                    </option>
                                                                                                    <option value="11">
                                                                                                        11
                                                                                                    </option>
                                                                                                    <option value="12">
                                                                                                        12
                                                                                                    </option>
                                                                                                </select>
                                                                                                &nbsp;&nbsp;&nbsp;
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_to_minute1[]"
                                                                                                    id="slot_interval_custom_to_minute11"
                                                                                                    style="width:90px">

                                                                                                    <option value="00">
                                                                                                        00
                                                                                                    </option>
                                                                                                    <option value="01">
                                                                                                        01
                                                                                                    </option>
                                                                                                    <option value="02">
                                                                                                        02
                                                                                                    </option>
                                                                                                    <option value="03">
                                                                                                        03
                                                                                                    </option>
                                                                                                    <option value="04">
                                                                                                        04
                                                                                                    </option>
                                                                                                    <option value="05">
                                                                                                        05
                                                                                                    </option>
                                                                                                    <option value="06">
                                                                                                        06
                                                                                                    </option>
                                                                                                    <option value="07">
                                                                                                        07
                                                                                                    </option>
                                                                                                    <option value="08">
                                                                                                        08
                                                                                                    </option>
                                                                                                    <option value="09">
                                                                                                        09
                                                                                                    </option>
                                                                                                    <option value="10">
                                                                                                        10
                                                                                                    </option>
                                                                                                    <option value="11">
                                                                                                        11
                                                                                                    </option>
                                                                                                    <option value="12">
                                                                                                        12
                                                                                                    </option>
                                                                                                    <option value="13">
                                                                                                        13
                                                                                                    </option>
                                                                                                    <option value="14">
                                                                                                        14
                                                                                                    </option>
                                                                                                    <option value="15">
                                                                                                        15
                                                                                                    </option>
                                                                                                    <option value="16">
                                                                                                        16
                                                                                                    </option>
                                                                                                    <option value="17">
                                                                                                        17
                                                                                                    </option>
                                                                                                    <option value="18">
                                                                                                        18
                                                                                                    </option>
                                                                                                    <option value="19">
                                                                                                        19
                                                                                                    </option>
                                                                                                    <option value="20">
                                                                                                        20
                                                                                                    </option>
                                                                                                    <option value="21">
                                                                                                        21
                                                                                                    </option>
                                                                                                    <option value="22">
                                                                                                        22
                                                                                                    </option>
                                                                                                    <option value="23">
                                                                                                        23
                                                                                                    </option>
                                                                                                    <option value="24">
                                                                                                        24
                                                                                                    </option>
                                                                                                    <option value="25">
                                                                                                        25
                                                                                                    </option>
                                                                                                    <option value="26">
                                                                                                        26
                                                                                                    </option>
                                                                                                    <option value="27">
                                                                                                        27
                                                                                                    </option>
                                                                                                    <option value="28">
                                                                                                        28
                                                                                                    </option>
                                                                                                    <option value="29">
                                                                                                        29
                                                                                                    </option>
                                                                                                    <option value="30">
                                                                                                        30
                                                                                                    </option>
                                                                                                    <option value="31">
                                                                                                        31
                                                                                                    </option>
                                                                                                    <option value="32">
                                                                                                        32
                                                                                                    </option>
                                                                                                    <option value="33">
                                                                                                        33
                                                                                                    </option>
                                                                                                    <option value="34">
                                                                                                        34
                                                                                                    </option>
                                                                                                    <option value="35">
                                                                                                        35
                                                                                                    </option>
                                                                                                    <option value="36">
                                                                                                        36
                                                                                                    </option>
                                                                                                    <option value="37">
                                                                                                        37
                                                                                                    </option>
                                                                                                    <option value="38">
                                                                                                        38
                                                                                                    </option>
                                                                                                    <option value="39">
                                                                                                        39
                                                                                                    </option>
                                                                                                    <option value="40">
                                                                                                        40
                                                                                                    </option>
                                                                                                    <option value="41">
                                                                                                        41
                                                                                                    </option>
                                                                                                    <option value="42">
                                                                                                        42
                                                                                                    </option>
                                                                                                    <option value="43">
                                                                                                        43
                                                                                                    </option>
                                                                                                    <option value="44">
                                                                                                        44
                                                                                                    </option>
                                                                                                    <option value="45">
                                                                                                        45
                                                                                                    </option>
                                                                                                    <option value="46">
                                                                                                        46
                                                                                                    </option>
                                                                                                    <option value="47">
                                                                                                        47
                                                                                                    </option>
                                                                                                    <option value="48">
                                                                                                        48
                                                                                                    </option>
                                                                                                    <option value="49">
                                                                                                        49
                                                                                                    </option>
                                                                                                    <option value="50">
                                                                                                        50
                                                                                                    </option>
                                                                                                    <option value="51">
                                                                                                        51
                                                                                                    </option>
                                                                                                    <option value="52">
                                                                                                        52
                                                                                                    </option>
                                                                                                    <option value="53">
                                                                                                        53
                                                                                                    </option>
                                                                                                    <option value="54">
                                                                                                        54
                                                                                                    </option>
                                                                                                    <option value="55">
                                                                                                        55
                                                                                                    </option>
                                                                                                    <option value="56">
                                                                                                        56
                                                                                                    </option>
                                                                                                    <option value="57">
                                                                                                        57
                                                                                                    </option>
                                                                                                    <option value="58">
                                                                                                        58
                                                                                                    </option>
                                                                                                    <option value="59">
                                                                                                        59
                                                                                                    </option>
                                                                                                </select>
                                                                                                <select
                                                                                                    class="form-control input-sm"
                                                                                                    name="slot_interval_custom_to_ampm1[]"
                                                                                                    id="slot_interval_custom_to_ampm11"
                                                                                                    style="width:85px">
                                                                                                    <option value="am">
                                                                                                        am
                                                                                                    </option>
                                                                                                    <option value="pm">
                                                                                                        pm
                                                                                                    </option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td style="width: 30px;">
                                                                                            <a onclick="AddDurationrow(1,1);"
                                                                                                class="btn btn-sm green"><i
                                                                                                    class="fa fa-plus">
                                                                                                </i></a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <h3>Multiple slots <button
                                                                            class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                            data-container="body" data-placement="top"
                                                                            data-trigger="hover"
                                                                            data-content="Click the toggle to enable multiple bookings for each time slot at your venue. Keep it disabled to allow just one booking for each time slot."
                                                                            type="button">
                                                                            <i class="fa fa-info-circle"></i>
                                                                        </button></h3>
                                                                </div>

                                                                <div class="col-md-7">
                                                                    <div id="multipleslot_remove1">
                                                                        <input type="checkbox" value="1"
                                                                            onchange="is_multipleslots(this.checked, 'capture_detail', 1);"
                                                                            class="make-switch"
                                                                            data-on-text="&nbsp;Enabled&nbsp;&nbsp;"
                                                                            data-off-text="&nbsp;Disabled&nbsp;">
                                                                        <input type="hidden" name="is_multiple1"
                                                                            id="capture_detail_name1">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" style="display: none;"
                                                                id="capture_detail1">
                                                                <div class="col-md-5">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    Number of bookings supported for each time
                                                                    slot.
                                                                    Keep it at 0 if unlimited bookings can be
                                                                    supported
                                                                    for each time slot.

                                                                    <div class="form-group">
                                                                        <div class="col-md-3">
                                                                            <h4>Available Seat <span class="required">*
                                                                                </span></h4>
                                                                            <input
                                                                                class="form-control form-control-inline"
                                                                                required="" min="0" type="number"
                                                                                name="unitavailable1" value="0"
                                                                                id="unitavailable1">
                                                                            <span class="help-block"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-3">
                                                                            <h4>Min Seat <span class="required">*
                                                                                </span>
                                                                            </h4>
                                                                            <input type="number" min="1"
                                                                                onchange="validatemax('min_seat1', 'max_seat1');"
                                                                                name="min_seat1" id="min_seat1"
                                                                                value="1" required
                                                                                class="form-control form-control-inline">
                                                                            <span class="help-block">
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <h4>Max Seat <span class="required">*
                                                                                </span>
                                                                            </h4>
                                                                            <input type="number" min="1"
                                                                                name="max_seat1" id="max_seat1"
                                                                                value="1" required=""
                                                                                class="form-control form-control-inline">
                                                                            <span class="help-block">
                                                                            </span>
                                                                        </div>
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-8">
                                                                            Minimum or maximum number of seats
                                                                            that can
                                                                            be
                                                                            booked for each time slot.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr />
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <h3>Slot Price
                                                                        <button
                                                                            class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                            data-container="body" data-placement="top"
                                                                            data-trigger="hover"
                                                                            data-content="Cost of each slot that is being sold. In case of multiple seats then this the cost that is applied per seat."
                                                                            type="button">
                                                                            <i class="fa fa-info-circle"></i>
                                                                        </button>
                                                                        <span class="required">*
                                                                        </span>
                                                                    </h3>
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="form-group">
                                                                        <input type="hidden" id="slot_number"
                                                                            name="slot_number[]" value="1">
                                                                        <div id="slot_clone1">
                                                                            <div class="row" style="
                                                                        margin: 5px;
                                                                        margin-bottom: 20px;
                                                                        padding: 20px;
                                                                        border: 1px solid #BEC6C6;
                                                                        border-radius: 15px;">
                                                                                <div class="col-md-10">
                                                                                    <h4>Title <span class="required">*
                                                                                        </span>
                                                                                    </h4>
                                                                                    <input type="text"
                                                                                        name="slot_title1[]"
                                                                                        id="slot_title11"
                                                                                        class="form-control form-control-inline">
                                                                                    <h4>Description
                                                                                    </h4>
                                                                                    <textarea class="form-control"
                                                                                        id="slot_description11"
                                                                                        name="slot_description1[]"
                                                                                        maxlength="200"></textarea>
                                                                                    <h4>Cost <span class="required">*
                                                                                        </span>
                                                                                    </h4>
                                                                                    <input type="number" step="0.01"
                                                                                        min="0" required
                                                                                        name="slot_price1[]"
                                                                                        id="slot_price11"
                                                                                        class="form-control form-control-inline">
                                                                                    <h4>Primary Slot ?</h4>
                                                                                    <input type="checkbox" value="1"
                                                                                        onchange="is_primarySlot(this.checked,'slot_isprimary11')"
                                                                                        id="isprimary_checkbox11">
                                                                                    <input type="hidden" value="0"
                                                                                        name="slot_isprimary1[]"
                                                                                        id="slot_isprimary11">
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <a href="javascript:;"
                                                                                        onclick="addSlots(1,1)"
                                                                                        class="btn btn-xs green pull-right">
                                                                                        <i class="fa fa-plus"></i> Add
                                                                                    </a>
                                                                                    <a style="display:none;"
                                                                                        href="javascript:;"
                                                                                        onclick="removeSlots(1,1)"
                                                                                        class="btn btn-xs red pull-right">
                                                                                        <i class="fa fa-close"></i>
                                                                                        remove </a>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <span id="slot_clones1"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <span id="clones"></span>
                                    </div>


                                    <div class="tab-pane " id="tab3">
                                        <div class="portlet ">
                                            <div class="portlet-body">
                                                <h3 class="block">Add Holidays</h3>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Choose Holidays
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Pick one or multiple days where your venue will not be available"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                <span class="required">
                                                                </span>
                                                            </label>
                                                            <div class="col-md-7" id="startdate">
                                                                <div class="demo">
                                                                    <div class="box" id="from--input">
                                                                        <input type="text" readonly
                                                                            style="background-color: #ffffff;"
                                                                            autocomplete="off" name="holiday"
                                                                            class="form-control" id="from-input">
                                                                    </div>
                                                                    <div class="code-box">
                                                                        <pre class="code" style="display: none;">
                                                                                                                           $('#from-input').datepicker({
                                                                                                                            multidate: true
                                                                                                                        });
                                                                                                                        </pre>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>




                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab4">
                                        <div class="portlet ">
                                            <div class="portlet-body">
                                                <h3 class="block">Captures details</h3>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Attendee details
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Select the information you would like your customer to enter while booking a slot"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button> :</label>
                                                            <div class="col-md-7">
                                                                <select id="column_name" name="capture_detail[]"
                                                                    class="form-control dropdown" multiple>
                                                                    <option selected disabled="" value="name">Name
                                                                    </option>
                                                                    <option selected disabled="" value="email">Email
                                                                    </option>
                                                                    <option selected disabled="" value="mobile">Mobile
                                                                    </option>
                                                                    <option selected value="customer_code">Customer code
                                                                    </option>
                                                                    <option selected value="address">Address</option>
                                                                    <option selected value="city">City</option>
                                                                    <option selected value="state">State</option>
                                                                    <option selected value="zipcode">Zipcode</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <h3 class="block">Payment notification details</h3>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Emails (comma
                                                                separated)
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Enter the email ids of your team members that should be notified once a booking has been made"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <input type="text" maxlength="500"
                                                                    name="notification_email" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Mobile number (comma
                                                                separated)
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Enter the mobile numbers of your team members that should be notified once a booking has been made"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <input type="text" maxlength="100"
                                                                    name="notification_mobile" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="block">Order confirmation message</h3>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Order page message
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="The message entered here will shown to the user on the order confirmation page and will also be included in the order confirmation email. Use this to convey any order / transaction related detail to the user"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <textarea type="text" rows="4"
                                                                    name="confirmation_message" maxlength="1000"
                                                                    placeholder="Kindly show up 5-10 minutes before your slot booking"
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="block">Company Policies</h3>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Terms & Conditions
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Enter your company T&C here. While booking the user will be asked to accept the T&C by checking a check box"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <textarea type="text" rows="4" name="tandc"
                                                                    maxlength="500" placeholder=""
                                                                    class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Cancellation policy
                                                                <button
                                                                    class="popovers btn btn-link dropdown-toggle button-on-hover"
                                                                    data-container="body" data-placement="top"
                                                                    data-trigger="hover"
                                                                    data-content="Enter your company cancellation policies here. While booking the user will be asked to accept the policy by checking a check box"
                                                                    type="button">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </button>
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <textarea type="text" rows="4"
                                                                    name="cancellation_policy" maxlength="500"
                                                                    placeholder="" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2"></div>
                                                    <div class="col-md-8">
                                                        <div class="alert"
                                                            style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;display: none;"
                                                            id="nvalidation_error_div">
                                                            <span id="nvalidation_error"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Cancellation Type
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <select id="cancellation_type"
                                                                    name="cancellation_type" class="form-control"
                                                                    onclick="cancellationTypeClicked(this.value)">
                                                                    <option
                                                                        value="1">Not Allowed</option>
                                                                    <option
                                                                        value="2">Refund</option>
                                                                    <option
                                                                        value="3">Coupon</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="cancellation_div" style="display:none;">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Cancellation
                                                                    days</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" maxlength="500"
                                                                        name="can_days" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Cancellation
                                                                    hours</label>
                                                                <div class="col-md-7">
                                                                    <input type="text" maxlength="500"
                                                                        name="can_hours" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="tab-pane" id="tab5">
                                        <div class="portlet ">
                                            <div class="portlet-body">
                                                <h3 class="block">Please confirm the details entered before creating new
                                                    calendar</h3>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <br>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Total Days :
                                                            </label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static" id="total_days"></p>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Total Holidays
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static" id="total_holidays"></p>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Slots Per Day
                                                                :</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static" id="slots_per_day"></p>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Total Slots :</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static" id="total_slots"></p>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet ">
                                    <div class="portlet-body">
                                        <div class="form-actions">
                                            <div class="row pull-right">
                                                <div class="col-md-12">
                                                    <a href="javascript:;" id="backbtn"
                                                        class="btn default button-previous">
                                                        <i class="m-icon-swapleft"></i> Back </a>
                                                    <a href="javascript:;" onclick="return validateAddCalendar();"
                                                        class="btn blue button-next">
                                                        Continue <i class="m-icon-swapright m-icon-white"></i>
                                                    </a>
                                                    <a href="javascript:;" class="btn blue button-submit">
                                                        Save <i class="m-icon-swapright m-icon-white"></i>
                                                    </a>
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
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->




<!-- /.modal-content -->
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton1"
                        data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">List category</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="portlet-body form">

                                <table class="table table-striped table-bordered table-hover" id="categoryList">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                ID
                                            </th>
                                            <th class="td-c">
                                                Category name
                                            </th>
                                            <th class="td-c">
                                                Created date
                                            </th>
                                            <th class="td-c">
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form action="" method="">
                                            {foreach from=$category_list item=v}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.category_id}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.category_name}
                                                    </td>
                                                    <td class="td-c">
                                                        {{$v.created_date}|date_format:"%Y-%m-%d"}
                                                    </td>
                                                    <td class="td-c">














                                                        {if $v.category_active==0}
                                                            <span class="label label-sm label-warning">Draft</span>














                                                        {else}
                                                            <span class="label label-sm label-success">Published</span>














                                                        {/if}
                                                    </td>

                                                </tr>















                                            {/foreach}
                                        </form>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-header">
                            <h4 class="modal-title">Add category</h4>
                        </div>

                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Category name<span class="required">*
                                                </span></label>
                                            <div class="col-md-4">
                                                <input type="text" id="category_name" maxlength="45"
                                                    name="category_name" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn default"
                        onclick="document.getElementById('errors').style.display = 'none';" id="closebutton"
                        data-dismiss="modal">Close</button>
                    <button type="submit" onclick="return save_category();" class="btn blue">Save</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
</div>

<script type="text/javascript" src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js">
</script>