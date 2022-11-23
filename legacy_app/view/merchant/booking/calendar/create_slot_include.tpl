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
                        <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body"
                            data-placement="top" data-trigger="hover"
                            data-content="Your venue will be available from this date for bookings" type="button">
                            <i class="fa fa-info-circle"></i>
                        </button>
                        <span class="required">*
                        </span>
                        <input class="form-control form-control-inline date-picker" readonly type="text" required
                            name="slot_date_from1" id="slot_date_from1"
                            style="background-color: #ffffff;cursor: pointer;" autocomplete="off"
                            data-date-format="dd M yyyy" placeholder="From date" />
                    </div>
                    <div class="col-md-6">
                        To
                        <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body"
                            data-placement="top" data-trigger="hover"
                            data-content="Your venue will be available till this date for bookings. This To date field can be kept empty if you are creating the calendar for only 1 day"
                            type="button">
                            <i class="fa fa-info-circle"></i>
                        </button>
                        <div class="input-group">
                            <input readonly onchange="showweekdays(this.value);"
                                class="form-control form-control-inline  date-picker" type="text"
                                style="background-color: #ffffff;cursor: pointer;" name="slot_date_to1"
                                id="slot_date_to1" autocomplete="off" data-date-format="dd M yyyy"
                                placeholder="To date" />
                            <span class="input-group-btn">
                                <div class="btn default" onclick="clearSeconddate(1);">
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
                            <input type="checkbox" id="weekday-mon1" class="weekday" onchange="dateCheckboxclicked()"
                                name="slot_weekday1[]" value="1" checked />
                            <label id="weekday-mon1-div" for="weekday-mon1">Mon</label>

                            <input type="checkbox" id="weekday-tue1" onchange="dateCheckboxclicked()" class="weekday"
                                name="slot_weekday1[]" value="2" checked />

                            <label id="weekday-tue1-div" for="weekday-tue1">Tue</label>

                            <input type="checkbox" id="weekday-wed1" onchange="dateCheckboxclicked()"
                                name="slot_weekday1[]" class="weekday" value="3" checked />

                            <label id="weekday-wed1-div" for="weekday-wed1">Wed</label>

                            <input type="checkbox" id="weekday-thu1" onchange="dateCheckboxclicked()"
                                name="slot_weekday1[]" class="weekday" value="4" checked />

                            <label id="weekday-thu1-div" for="weekday-thu1">Thu</label>

                            <input type="checkbox" id="weekday-fri1" onchange="dateCheckboxclicked()"
                                name="slot_weekday1[]" class="weekday" value="5" checked />

                            <label id="weekday-fri1-div" for="weekday-fri1">Fri</label>

                            <input type="checkbox" id="weekday-sat1" onchange="dateCheckboxclicked()"
                                name="slot_weekday1[]" class="weekday" value="6" checked />

                            <label id="weekday-sat1-div" for="weekday-sat1">Sat</label>

                            <input type="checkbox" id="weekday-sun1" onchange="dateCheckboxclicked()" class="weekday"
                                name="slot_weekday1[]" value="7" checked />

                            <label id="weekday-sun1-div" for="weekday-sun1">Sun</label>
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
                    <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body"
                        data-placement="top" data-trigger="hover"
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
                        <select name="slot_interval1" class="form-control" required id="slot_interval1"
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
                            <input type="text" name="slot_interval_minutes1" id="slot_interval_minutes1"
                                style="width: 300px;" class="form-control form-control-inline">
                        </div>

                    </div>
                </div>
            </div>
            <br />

            <div class="row">
                <div class="col-md-5">
                    <h3>Pause between time slots <button class="popovers btn btn-link dropdown-toggle button-on-hover"
                            data-container="body" data-placement="top" data-trigger="hover"
                            data-content="Add an interval between two time slots as per your requirements. Specify the duration of your interval"
                            type="button">
                            <i class="fa fa-info-circle"></i>
                        </button></h3>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <div class="col-md-6">
                            Pause time in minute
                            <input type="number" min="0" name="slot_pause1" id="slot_pause1" style="width: 300px;"
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
                            <table class="table table-bordered table-hover" id="particular_table" style="width: 300px;">
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
                                            <div class="input-group">
                                                <select class="form-control input-sm" name="slot_time_from_hour1[]"
                                                    id="slot_time_from_hour1" style="width:85px">
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
                                                <select class="form-control input-sm" name="slot_time_from_minute1[]"
                                                    id="slot_time_from_minute1" style="width:90px">

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
                                                <select class="form-control input-sm" name="slot_time_from_ampm[]1"
                                                    id="slot_time_from_ampm1" style="width:80px">
                                                    <option value="am">
                                                        am
                                                    </option>
                                                    <option value="pm">
                                                        pm
                                                    </option>
                                                </select>
                                            </div>
                                        </td>
                                        <td style="width: 300px;">
                                            <div class="input-group">
                                                <select class="form-control input-sm" name="slot_time_to_hour1[]"
                                                    id="slot_time_to_hour1" style="width:85px">
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
                                                <select class="form-control input-sm" name="slot_time_to_minute1[]"
                                                    id="slot_time_to_minute1" style="width:90px">

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
                                                <select class="form-control input-sm" name="slot_time_to_ampm1[]"
                                                    id="slot_time_to_ampm1" style="width:80px">
                                                    <option value="am">
                                                        am
                                                    </option>
                                                    <option value="pm">
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
                        <table class="table table-bordered table-hover" id="particular_table" style="width: 290px;">
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
                                            <select class="form-control input-sm"
                                                name="slot_interval_custom_from_hour1[]"
                                                id="slot_interval_custom_from_hour1" style="width:85px">
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
                                            <select class="form-control input-sm"
                                                name="slot_interval_custom_from_minute1[]"
                                                id="slot_interval_custom_from_minute1" style="width:90px">

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
                                            <select class="form-control input-sm"
                                                name="slot_interval_custom_from_ampm1[]"
                                                id="slot_interval_custom_from_ampm1" style="width:85px">
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
                                            <select class="form-control input-sm" name="slot_interval_custom_to_hour1[]"
                                                id="slot_interval_custom_to_hour1" style="width:85px">
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
                                            <select class="form-control input-sm"
                                                name="slot_interval_custom_to_minute1[]"
                                                id="slot_interval_custom_to_minute1" style="width:90px">

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
                                            <select class="form-control input-sm" name="slot_interval_custom_to_ampm1[]"
                                                id="slot_interval_custom_to_ampm1" style="width:85px">
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
                                        <a onclick="AddDurationrow(1);" class="btn btn-sm green"><i class="fa fa-plus">
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
                <h3>Multiple slots <button class="popovers btn btn-link dropdown-toggle button-on-hover"
                        data-container="body" data-placement="top" data-trigger="hover"
                        data-content="Click the toggle to enable multiple bookings for each time slot at your venue. Keep it disabled to allow just one booking for each time slot."
                        type="button">
                        <i class="fa fa-info-circle"></i>
                    </button></h3>
            </div>

            <div class="col-md-7">
                <div id="multipleslot_remove1">
                    <input type="checkbox" value="1" onchange="is_multipleslots(this.checked, 'capture_detail', 1);"
                        class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;"
                        data-off-text="&nbsp;Disabled&nbsp;">
                    <input type="hidden" name="is_multiple1" id="capture_detail_name1">
                </div>
            </div>
        </div>

        <div class="row" style="display: none;" id="capture_detail1">
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
                        <input class="form-control form-control-inline" required="" min="0" type="number"
                            name="unitavailable1" value="0" id="unitavailable1">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <h4>Min Seat <span class="required">*
                            </span>
                        </h4>
                        <input type="number" min="1" onchange="validatemax('min_seat1', 'max_seat1');" name="min_seat1"
                            id="min_seat1" value="1" required class="form-control form-control-inline">
                        <span class="help-block">
                        </span>
                    </div>
                    <div class="col-md-3">
                        <h4>Max Seat <span class="required">*
                            </span>
                        </h4>
                        <input type="number" min="1" name="max_seat1" id="max_seat1" value="1" required=""
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
                    <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body"
                        data-placement="top" data-trigger="hover"
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
                    <input type="hidden" id="slot_number" name="slot_number[]" value="1">
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
                                <input type="text" name="slot_title1[]" id="slot_title11"
                                    class="form-control form-control-inline">
                                <h4>Description
                                </h4>
                                <textarea class="form-control" id="slot_description11" name="slot_description1[]"
                                    maxlength="500"></textarea>
                                <h4>Cost <span class="required">*
                                    </span>
                                </h4>
                                <input type="number" step="0.01" min="0" required name="slot_price1[]" id="slot_price11"
                                    class="form-control form-control-inline">
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:;" onclick="addSlots(1,1)" class="btn btn-xs green pull-right">
                                    <i class="fa fa-plus"></i> Add
                                </a>
                                <a style="display:none;" href="javascript:;" onclick="removeSlots(1,1)"
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