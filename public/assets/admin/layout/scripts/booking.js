var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var enableDays = '';
var default_date = '';


function showweekdays(val) {
    if (val != '') {
        document.getElementById('weekdays').style.display = 'block';
    } else {
        document.getElementById('weekdays').style.display = 'none';
    }
}

function clearSeconddate(package_number) {
    document.getElementById('slot_date_to' + package_number).value = '';
    showweekdays('');
}

function selectCheckbox(form_name, check_name) {
    var formObj = document.forms[form_name];
    var boxes = formObj[check_name].length;
    var num = 0;
    if (formObj.selectAll.checked) {
        for (var i = 0; i < boxes; i++) {
            if (formObj[check_name][i].checked == false) {
                formObj[check_name][i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < boxes; i++) {
            if (formObj[check_name][i].checked == true) {
                formObj[check_name][i].checked = false;
            }
        }
    }

}

function disableSelectAll(form_name, checked) {
    var formObj = document.forms[form_name];
    if (checked == false) {
        formObj.selectAll.checked = false;
    }
}


function showCustom(ind, package_id) {
    if (ind == '1') {
        document.getElementById('tyfromto' + package_id).style.display = 'none';
        document.getElementById('tymin' + package_id).style.display = 'block';

    } else if (ind == '0') {
        document.getElementById('tyfromto' + package_id).style.display = 'block';
        document.getElementById('tymin' + package_id).style.display = 'none';
    } else {
        document.getElementById('tyfromto' + package_id).style.display = 'none';
        document.getElementById('tymin' + package_id).style.display = 'none';
    }
}

function AddDurationrow(package_number, slot_number) {

    var new_slot_number = Number(slot_number) + 1;
    while (document.getElementById("slot_interval_custom_from_hour" + package_number + new_slot_number)) {
        new_slot_number = new_slot_number + 1;
    }
    var mainDiv = document.getElementById('new_duration' + package_number);
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td style="width: 400px;"><div class="input-group"><select class="form-control input-sm" id="slot_interval_custom_from_hour' + package_number + new_slot_number + '" name="slot_interval_custom_from_hour' + package_number + '[]" style="width:85px"><option value="">Hour</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select> &nbsp;&nbsp;&nbsp;<select class="form-control input-sm" id="slot_interval_custom_from_minute' + package_number + new_slot_number + '" name="slot_interval_custom_from_minute' + package_number + '[]" style="width:90px"><option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><select class="form-control input-sm" id="slot_interval_custom_from_ampm' + package_number + new_slot_number + '" name="slot_interval_custom_from_ampm' + package_number + '[]" style="width:80px"><option value="am">am</option><option value="pm">pm</option></select></div></td><td style="width: 400px;"><div class="input-group"><select class="form-control input-sm" id="slot_interval_custom_to_hour' + package_number + new_slot_number + '"  name="slot_interval_custom_to_hour' + package_number + '[]" style="width:85px"><option value="">Hour</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select> &nbsp;&nbsp;&nbsp;<select class="form-control input-sm" id="slot_interval_custom_to_minute' + package_number + new_slot_number + '" name="slot_interval_custom_to_minute' + package_number + '[]" style="width:90px"><option value="00">00</option><option value="01">01</option><option value="02">02</option><optionvalue="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><optionvalue="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select><select class="form-control input-sm" id="slot_interval_custom_to_ampm' + package_number + new_slot_number + '"  name="slot_interval_custom_to_ampm' + package_number + '[]" style="width:80px"><option value="am">am</option><option value="pm">pm</option></select></div></td><td style="width: 50px;"><a href="javascript:;"  onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}



function checkData(frm) {

    with (frm) {
        if (slot_date_from.value == '') {
            alert("Choose slot interval");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '') {
            alert("Choose slot interval");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '1' && (!isNumeric(slot_interval_minutes) || slot_interval_minutes.value > 1435)) {
            alert("Insert a valid value for slot interval");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '0' && (document.getElementsByName("slot_interval_custom_from_hour[]").item(0).value == '' || document.getElementsByName("slot_interval_custom_to_hour[]").item(0).value == '')) {
            alert("Insert at least a custom slot");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '0' && checkSlotIntervalCustomTimes() == 1) {
            alert("Slot duration values are not correct");
            return false;
        } else if (slot_pause.value != '0' && slot_pause.value != '' && (!isNumeric(slot_pause) || slot_pause.value < 5 || slot_pause.value > 1435)) {
            alert("Insert a valid value for slot pause");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '1' && (document.getElementsByName("slot_time_from_hour[]").item(0).value == '' || document.getElementsByName("slot_time_to_hour[]").item(0).value == '')) {
            alert("Select at least time from and a time to");
            return false;
        } else if (slot_interval.options[slot_interval.selectedIndex].value == '1' && checkSlotTimes() == 1) {
            alert("Time periods for slots are not correct");
            return false;
        } else {
            return true;
        }
    }
}

function changeCategory(cur_date) {
    var merchant_id = document.getElementById('merchant_id').value;
    var cat_id = document.getElementById('category_id').value;
    var cal_id = '0';
    var fullstr = merchant_id + ',' + cat_id + ',' + cal_id;
    getCalendar(cat_id, merchant_id);
    setcalendar(fullstr, cur_date);

}
function getCalendar(cat_id, merchant_id) {
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/ajax/getCalendar/' + cat_id + '/' + merchant_id,
        data: data,
        success: function (data) {
            var d = JSON.parse(data);
            $('#calendar_id').empty();
            $.each(d, function (index, value) {
                $('#calendar_id').append('<option value="' + value.calendar_id + '">' + value.calendar_title + '</option>');
            });
        }
    });
}
function changeCalendar(cur_date) {
    var merchant_id = document.getElementById('merchant_id').value;
    var cat_id = document.getElementById('category_id').value;
    var cal_id = document.getElementById('calendar_id').value;
    var fullstr = merchant_id + ',' + cat_id + ',' + cal_id;
    setcalendar(fullstr, cur_date);
}

function setcalendar(val, cur_date) {
    try {
        $('#loading').toggle(true);
        document.getElementById('calenderdiv').innerHTML = '<br><div id="calendar" style="max-width: 900px;margin: 0 auto;"></div><br>';
    } catch (o) {
    }

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            theme: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            defaultDate: cur_date,
            navLinks: false, // can click day/week names to navigate views
            editable: false,
            eventLimit: false, // allow "more" link when too many events
            events: {
                url: '/ajax/getCalendarJson/' + val,
            },
            loading: function (bool) {
                $('#loading').toggle(bool);
            }
        });
    });
}

function selectSlot(date, calendar_id) {
    document.getElementById('bookslotclick').click();
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/ajax/getSlots/' + date + '/' + calendar_id,
        data: data,
        success: function (data) {
            var d = JSON.parse(data);
            document.getElementById('absolute_costt').innerHTML = '0.00';
            document.getElementById('amount').value = '0.00';
            document.getElementById('slotlist').innerHTML = d.slots;
            document.getElementById('frm_date').value = d.date;
            document.getElementById('frm_calendar_id').value = calendar_id;
        }
    });
}

function membershipCost(id) {
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/ajax/getmembershipcost/' + id,
        data: data,
        success: function (data) {
            document.getElementById('absolute_cost').value = data;
        }
    });
}

/*function selectSlot(date, calendar_id)
 {
 document.getElementById('bookslotclick').click();
 var data = '';
 $.ajax({
 type: 'POST',
 url: '/ajax/getSlots/' + date + '/' + calendar_id,
 data: data,
 success: function(data)
 {
 var d = JSON.parse(data);
 document.getElementById('absolute_costt').innerHTML = '0.00';
 document.getElementById('amount').value = '0.00';
 document.getElementById('slotlist').innerHTML = d.slots;
 document.getElementById('frm_date').value = d.date;
 document.getElementById('frm_calendar_id').value = calendar_id;
 }
 });
 }*/

function setMerchantDays(cat_id, date = '') {
    var merchant_id = document.getElementById('merchant_id').value;
    var data = '';
    default_date = date;
    $.ajax({
        type: 'POST',
        url: '/ajax/getmCategoryDates/' + cat_id + '/' + merchant_id,
        data: data,
        success: function (data) {
            try {
                enableDays = JSON.parse(data);
                $('#date').datepicker({ dateFormat: 'dd M yy', beforeShowDay: enableAllTheseDays });
                if (default_date != '') {
                    var setdate = default_date;
                } else {
                    var setdate = enableDays[0];
                }

                if (setdate != "") {
                    if (setdate.includes(",")) {
                        setdate = setdate.replaceAll(",", "-");
                        setdate = setdate + " 00:00:00";
                        var date2 = new Date(setdate.replace(" ", "T"));
                    } else {
                        var date2 = new Date(setdate);
                    }
                    $('#date').datepicker('setDate', date2);
                    $('#date').datepicker({ dateFormat: 'dd M yy' });
                } else {
                    $("#date").datepicker({
                        beforeShowDay: function (date) {
                            return false;
                        }
                    });
                    document.getElementById('date').value = '';
                }

            } catch (o) {
                //alert(o.Message + 'hii');
            }

        }
    });

}

function setDays(cat_id, date = '') {
    var merchant_id = document.getElementById('merchant_id').value;
    var data = '';
    default_date = date;
    if (cat_id != '') {
        $.ajax({
            type: 'POST',
            url: '/ajax/getCategoryDates/' + cat_id + '/' + merchant_id,
            data: data,
            success: function (data) {
                if (data != '[]') {
                    try {
                        if (date == '') {
                            //var d = JSON.parse(data);
                            var year = data.substring(10, 14);
                            var month_str = Number(data.substring(15, 16));
                            var month = Number(month_str + 1);
                            var day = data.substring(17, 19);
                            var default_date = year + ',' + month + ',' + day;
                            selected_date = new Date(default_date);
                        } else {
                            var year = date.substring(0, 4);
                            var month = Number(date.substring(5, 7));
                            var day = date.substring(8, 10);
                            selected_date = new Date(date);
                        }

                        document.getElementById('app').innerHTML = "<v-date-picker :input-props='{ name:" + '"booking_date"' + ",id:" + '"vcalendar"' + ",placeholder:" + '"Select date"' + " }' :mode='mode' v-model='selectedDate' :masks=" + '"' + "{ input: ['DD MMM YYYY']}" + '"' + " :disabled-dates='{  }' :available-dates='" + data + "' />";

                        new Vue({
                            el: '#app',
                            data: {
                                // Data used by the date picker
                                mode: 'single',
                                selectedDate: selected_date
                            }
                        })
                        document.getElementById('vcalendar').value = day + ' ' + monthNames[month] + ' ' + year;

                    } catch (o) {
                        //alert(o.message + 'hii');
                    }

                } else {
                    document.getElementById('app').innerHTML = "<v-date-picker :input-props='{ name:" + '"booking_date"' + ",id:" + '"vcalendar"' + ",placeholder:" + '"Select date"' + " }' :mode='mode' v-model='selectedDate' :masks=" + '"' + "{ input: ['DD MMM YYYY']}" + '"' + " :disabled-dates='{  }' :available-dates='" + data + "' />";

                    new Vue({
                        el: '#app',
                        data: {
                            // Data used by the date picker
                            mode: 'single',
                            selectedDate: null
                        }
                    })
                }
            }
        });
    }

}


function enableAllTheseDays(date) {
    var sdate = $.datepicker.formatDate('dd M yy', date);

    if ($.inArray(sdate, enableDays) != -1) {
        return true;
    } else {
        return false;
    }
}

function getCategorySlots() {
    category_id = document.getElementById('category_id').value;
    date = document.getElementById('date').value;
    date = date.replaceAll(" ", "Space");
    if (date != '') {
        var data = '';
        $.ajax({
            type: 'POST',
            url: '/ajax/getCategorySlots/' + date + '/' + category_id,
            data: data,
            success: function (data) {
                try {
                    var d = JSON.parse(data);
                    $('#slot_time').empty();
                    $('#slot_time').append('<option value="">Select Slots...</option>');
                    $.each(d, function (index, value) {
                        $('#slot_time').append('<option value="' + value + '">' + value + '</option>');
                    });
                } catch (o) {
                }
            }
        });
    }
}

function getCategoryCalendar() {
    category_id = document.getElementById('category_id').value;
    date = document.getElementById('date').value;
    date = date.replace(" ", "Space");
    date = date.replace(" ", "Space");
    var data = {
        'date': date, //for get email 
        'category_id': category_id, //for get email 
    };
    $.ajax({
        type: 'POST',
        url: '/ajax/getCategoryCalendar',
        data: data,
        success: function (data) {
            var d = JSON.parse(data);
            $('#court').empty();
            $('#court').append('<option value="">Select Court...</option>');
            $.each(d, function (index, value) {
                $('#court').append('<option value="' + value.calendar_id + '">' + value.calendar_title + '</option>');
            });
        }
    });
}

function getPackagesCalendar() {
    category_id = document.getElementById('category_id').value;
    calendar_id = document.getElementById('court').value;
    date = document.getElementById('date').value;
    date = date.replace(" ", "Space");
    date = date.replace(" ", "Space");
    var data = {
        'date': date,
        'category_id': category_id,
        'calendar_id': calendar_id,
    };
    $.ajax({
        type: 'POST',
        url: '/ajax/getPackagesCalendar',
        data: data,
        success: function (data) {
            var d = JSON.parse(data);
            $('#packages').empty();
            $('#packages').append('<option value="">Select packages...</option>');
            $.each(d, function (index, value) {
                $('#packages').append('<option value="' + value.package_id + '">' + value.package_name + '</option>');
            });
        }
    });
}

function validatemaxbooking() {
    var max_booking = document.getElementById('max_booking').value;
    if (max_booking > 0) {
        var form_name = 'frm_slot';
        var check_name = 'booking_slots[]';
        var number_name = 'booking_qty[]';
        var formObj = document.forms[form_name];
        var boxes = Number(formObj[check_name].length);
        var qty = Number(formObj[number_name].length);
        var num = 0;
        var amount = 0;
        for (var i = 0; i < boxes; i++) {
            try {
                if (formObj[check_name][i].checked == true) {
                    num = num + 1;
                }
            } catch (o) {
                //alert(o.message);
            }
        }
        if (num > max_booking) {
            document.getElementById('max_booking_div').style.display = 'block';
            document.getElementById('max_booking_error').innerHTML = 'You can book maximum ' + max_booking + ' slot at a time';
            return false;
        } else {
            document.getElementById('max_booking_div').style.display = 'none';
            return true;
        }

    } else {
        document.getElementById('max_booking_div').style.display = 'none';
        return true;
    }
}

function calculateSlot(id) {
    try {
        valid = validatemaxbooking();
        if (valid == false) {
            document.getElementById("slotchk" + id).checked = false;
            return false;
        }
        var form_name = 'frm_slot';
        var check_name = 'booking_slots[]';
        var number_name = 'booking_qty[]';
        var formObj = document.forms[form_name];
        var boxes = Number(formObj[check_name].length);
        var qty = Number(formObj[number_name].length);
        var num = 0;
        var amount = 0;
        for (var i = 0; i < boxes; i++) {
            try {
                if (formObj[check_name][i].checked == true) {
                    price = Number(formObj[check_name][i].title);
                    b_qty = Number(formObj[number_name][i].value);
                    total_price = b_qty * price;
                    amount = Number(amount + total_price);
                    num = num + b_qty;
                    if (b_qty == 0) {
                        formObj[check_name][i].checked = false;
                        formObj[number_name][i].value = formObj[number_name][i].min;
                    }
                }
            } catch (o) {
                // alert(o.message);
            }
        }
        //amount = Math.round(100 * amount) / 100;
        if (amount > 0) {
            document.getElementById('amount').value = amount.toFixed(2);
            document.getElementById('absolute_costt').innerHTML = 'Rs. ' + amount.toFixed(2) + '/-';
        } else {
            document.getElementById('amount').value = '';
            document.getElementById('absolute_costt').innerHTML = '';
        }
        document.getElementById('totalslot').innerHTML = num;

    } catch (p) {
        //alert(p.message);
    }

    try {
        if (document.getElementById('slotchk' + id).checked) {
            document.getElementById('slotbtntext' + id).innerHTML = 'Cancel';
            document.getElementById('slotbtn' + id).className = "btn btn-sm red pull-right";
            document.getElementById('slotid' + id).value = id;
        } else {
            document.getElementById('slotbtntext' + id).innerHTML = 'Book';
            document.getElementById('slotbtn' + id).className = "btn btn-sm blue pull-right";
            document.getElementById('slotid' + id).value = '0';
        }
    } catch (c) {
        //alert(c.message);
    }

    return true;
}



function login() {
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/login/failed/ajax',
        data: data,
        success: function (data) {
            if (data == 'success') {
                document.getElementById('errorshow').style.display = 'none';
                document.getElementById('error_display').innerHTML = '';
                $('#form_slot').submit();

            } else {
                obj = JSON.parse(data);
                var error = '';
                try {
                    $.each(obj['error'], function (index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('errorshow').style.display = 'block';
                    document.getElementById('error_display').innerHTML = error;
                } catch (o) {
                    //alert(o.message);
                }
            }

        }
    });

    return false;
}

function validateform() {
    amount = Number(document.getElementById('amount').value);
    if (amount > 0) {
        return true;
    } else {
        alert('Please select slot');
        return false;
    }
}

function setSlotdetails(id) {
    data = '';
    $.ajax({
        type: 'POST',
        url: '/ajax/getSlotdetails/' + id,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            document.getElementById('slot_date').value = obj.slot_date;
            var is_multiple = obj.is_multiple;
            var min_seat = obj.min_seat;
            var max_seat = obj.max_seat;
            var total_seat = obj.total_seat;

            if (is_multiple == 1) {
                document.getElementById('ismulcheck').checked = true;
                is_multipleslots(1, 'capture_detail');
            } else {
                document.getElementById('ismulcheck').checked = false;
                is_multipleslots(0, 'capture_detail');
            }
            document.getElementById('capture_detail_name').value = is_multiple;
            document.getElementById('min_seat1').value = min_seat;
            document.getElementById('max_seat1').value = max_seat;
            document.getElementById('unitavailable').value = total_seat;

            var from_time = obj.slot_time_from;
            document.getElementById("from_hr").selectedIndex = from_time.substr(0, 2);
            document.getElementById("from_min").selectedIndex = from_time.substr(3, 2);
            f_ampm = from_time.substr(6, 2);
            if (f_ampm == 'AM') {
                f_ampm = 'am';
            } else {
                f_ampm = 'pm';
            }
            document.getElementById("from_ampm").value = f_ampm;

            var to_time = obj.slot_time_to;
            document.getElementById("to_hr").selectedIndex = to_time.substr(0, 2);
            document.getElementById("to_min").selectedIndex = to_time.substr(3, 2);
            t_ampm = to_time.substr(6, 2);
            if (t_ampm == 'AM') {
                t_ampm = 'am';
            } else {
                t_ampm = 'pm';
            }
            document.getElementById("to_ampm").value = t_ampm;
            document.getElementById('package_id').value = obj.package_id;
            document.getElementById('package_name').value = obj.package_name;

            document.getElementById('slot_title').value = obj.slot_title;
            document.getElementById('slot_description').value = obj.slot_description;
            document.getElementById('slot_price').value = obj.slot_price;
            document.getElementById('slot_id').value = id;

        }
    });


}

function slotUpdate() {
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/bookings/updateslot',
        data: data,
        success: function (data) {
            if (data == 'success') {
                $('#loading').toggle(true);
                location.reload();
            } else {

            }

        }
    });

    return false;
}

function save_category() {
    var data = $("#categoryForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/bookings/savecategory',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                $('#cat_drop').append('<option selected value="' + obj.id + '">' + obj.name + '</option>');
                $("#categoryForm").trigger("reset");
                document.getElementById("closebutton").click();
                $("#categoryList").load(location.href + " #categoryList");
            } else {
                document.getElementById('errors').innerHTML = obj.error;
                document.getElementById('errors').style.display = 'block';
            }

        }
    });

    return false;
}

function validateAddCalendar() {

    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/bookings/validatecalendar',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                document.getElementById('total_holidays').innerHTML = obj.total_holiday;
                document.getElementById('total_days').innerHTML = obj.total_days;
                document.getElementById('slots_per_day').innerHTML = obj.slots_per_day;
                document.getElementById('total_slots').innerHTML = obj.total_slots;
                return true;
            } else {
                document.getElementById('validation_error' + obj.page).innerHTML = obj.error;
                document.getElementById('validation_error_div' + obj.page).style.display = 'block';
                document.getElementById('backbtn').click();
                return false;
            }
        }
    });

    return false;
}

function displayslot(id) {
    document.getElementById('slot_div' + id).style.display = 'block';
}

function hideslot(id) {
    document.getElementById('slot_div' + id).style.display = 'none';
}

function validateBooking() {
    amount = document.getElementById('totalslot').innerHTML;
    date = new Date(document.getElementById('current_booking_date').value);
    todaysDate = new Date();
    date_diff = date - todaysDate
    day_diff = Math.ceil(date_diff / (1000 * 60 * 60 * 24))

    if (day_diff >= 0) {
        if (amount > 0) {
            return true;
        } else {
            alert('Select slot');
            return false;
        }
    } else {
        alert('Please check your date');
        return false;
    }
}

function dateChange(date) {
    document.getElementById('book_date').value = date;
    document.getElementById("slotfrm").submit();
}

function selectCourt(id, name) {
    document.getElementById('calendar_id').value = id;
    document.getElementById('court_name').value = name;
}

function is_multipleslots(val, id, package_number = '') {
    if (val == 1) {
        document.getElementById(id + package_number).style.display = 'block';
        document.getElementById(id + '_name' + package_number).value = 1;
    } else {
        document.getElementById(id + package_number).style.display = 'none';
        document.getElementById(id + '_name' + package_number).value = 0;
    }

}

function is_primarySlot(val, id) {
    if (val == 1) {
        document.getElementById(id).value = 1;
    } else {
        document.getElementById(id).value = 0;
    }
}

function validatemax(min_id, max_id) {
    minval = document.getElementById(min_id).value;
    $('#' + max_id).attr("min", minval);
}

function validatefilesize(maxsize, id) {
    var x = document.getElementById(id);
    var txt = "";
    if (maxsize == 500000) {
        max = '500 KB';
    } else {
        max = '2 MB';
    }
    if ('files' in x) {

        if (x.files.length == 0) {
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if (file.size > maxsize) {
                    alert('File size should be less than ' + max);
                    try {
                        document.getElementById(id).value = "";
                    } catch (c) {
                        // alert(c.message);
                    }
                    document.getElementById('imgdismiss').click();
                    return false;
                }
            }
        }
    }
}

function validatefilesizev2(maxsize, id, dismiss_id) {
    var x = document.getElementById(id);
    var txt = "";
    if (maxsize == 500000) {
        max = '500 KB';
    } else {
        max = '2 MB';
    }
    if ('files' in x) {

        if (x.files.length == 0) {
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if (file.size > maxsize) {
                    alert('File size should be less than ' + max);
                    try {
                        document.getElementById(id).value = "";
                    } catch (c) {
                        // alert(c.message);
                    }
                    document.getElementById(dismiss_id).click();
                    return false;
                }
            }
        }
    }
}

function cloneBookingPackage(package_number) {

    var new_package_number = package_number + 1;
    while (document.getElementById("package_name" + new_package_number)) {
        new_package_number = new_package_number + 1;
    }
    var html = document.getElementById('ddl_' + package_number).innerHTML;
    var clone = document.createElement('span');
    clone.setAttribute("id", "package" + new_package_number)
    html = html.replaceAll('name="package_number[]" value="' + package_number + '"', 'name="package_number[]" value="' + new_package_number + '"')
    html = html.replaceAll('package_name' + package_number, 'package_name' + new_package_number)
    html = html.replaceAll('package_desc' + package_number, 'package_desc' + new_package_number)
    html = html.replaceAll('package_image' + package_number, 'package_image' + new_package_number)
    html = html.replaceAll('package_image_dismiss' + package_number, 'package_image_dismiss' + new_package_number)
    html = html.replaceAll('slot_date_from' + package_number, 'slot_date_from' + new_package_number)
    html = html.replaceAll('slot_date_to' + package_number, 'slot_date_to' + new_package_number)
    html = html.replaceAll('new_duration' + package_number, 'new_duration' + new_package_number)
    html = html.replaceAll('clearSeconddate(' + package_number + ')', 'clearSeconddate(' + new_package_number + ')')
    html = html.replaceAll('slot_weekday' + package_number, 'slot_weekday' + new_package_number)
    html = html.replaceAll('weekday-mon' + package_number, 'weekday-mon' + new_package_number)
    html = html.replaceAll('weekday-tue' + package_number, 'weekday-tue' + new_package_number)
    html = html.replaceAll('weekday-wed' + package_number, 'weekday-wed' + new_package_number)
    html = html.replaceAll('weekday-thu' + package_number, 'weekday-thu' + new_package_number)
    html = html.replaceAll('weekday-fri' + package_number, 'weekday-fri' + new_package_number)
    html = html.replaceAll('weekday-sat' + package_number, 'weekday-sat' + new_package_number)
    html = html.replaceAll('weekday-sun' + package_number, 'weekday-sun' + new_package_number)
    html = html.replaceAll('slot_interval' + package_number, 'slot_interval' + new_package_number)
    html = html.replaceAll('slot_interval_minutes' + package_number, 'slot_interval_minutes' + new_package_number)
    html = html.replaceAll('slot_pause' + package_number, 'slot_pause' + new_package_number)
    html = html.replaceAll('slot_time_from_hour' + package_number, 'slot_time_from_hour' + new_package_number)
    html = html.replaceAll('slot_time_from_minute' + package_number, 'slot_time_from_minute' + new_package_number)
    html = html.replaceAll('slot_time_from_ampm' + package_number, 'slot_time_from_ampm' + new_package_number)
    html = html.replaceAll('slot_time_to_hour' + package_number, 'slot_time_to_hour' + new_package_number)
    html = html.replaceAll('slot_time_to_minute' + package_number, 'slot_time_to_minute' + new_package_number)
    html = html.replaceAll('slot_time_to_ampm' + package_number, 'slot_time_to_ampm' + new_package_number)
    html = html.replaceAll('tyfromto' + package_number, 'tyfromto' + new_package_number)
    html = html.replaceAll('tymin' + package_number, 'tymin' + new_package_number)
    html = html.replaceAll('showCustom(this.value,' + package_number + ')', 'showCustom(this.value,' + new_package_number + ')')
    html = html.replaceAll('slot_interval_custom_from_hour' + package_number + '[]', 'slot_interval_custom_from_hour' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_from_minute' + package_number + '[]', 'slot_interval_custom_from_minute' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_from_ampm' + package_number + '[]', 'slot_interval_custom_from_ampm' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_hour' + package_number + '[]', 'slot_interval_custom_to_hour' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_minute' + package_number + '[]', 'slot_interval_custom_to_minute' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_ampm' + package_number + '[]', 'slot_interval_custom_to_ampm' + new_package_number + '[]')
    html = html.replaceAll('unitavailable' + package_number, 'unitavailable' + new_package_number)
    html = html.replaceAll('min_seat' + package_number, 'min_seat' + new_package_number)
    html = html.replaceAll('max_seat' + package_number, 'max_seat' + new_package_number)

    html = html.replaceAll('multipleslot_remove' + package_number, 'multipleslot_remove' + new_package_number)
    html = html.replaceAll('is_multiple' + package_number, 'is_multiple' + new_package_number)
    html = html.replaceAll('capture_detail_name' + package_number, 'capture_detail_name' + new_package_number)
    html = html.replaceAll('capture_detail' + package_number, 'capture_detail' + new_package_number)
    html = html.replaceAll('slot_title' + package_number + '[]', 'slot_title' + new_package_number + '[]')
    html = html.replaceAll('slot_description' + package_number + '[]', 'slot_description' + new_package_number + '[]')
    html = html.replaceAll('slot_price' + package_number + '[]', 'slot_price' + new_package_number + '[]')
    html = html.replaceAll('slot_isprimary' + package_number + '[]', 'slot_isprimary' + new_package_number + '[]')

    var i = 1;
    while (document.getElementById("slot_title" + package_number + i)) {
        html = html.replaceAll('slot' + package_number + i, 'slot' + new_package_number + i)
        html = html.replaceAll('slot_title' + package_number + i, 'slot_title' + new_package_number + i)
        html = html.replaceAll('slot_description' + package_number + i, 'slot_description' + new_package_number + i)
        html = html.replaceAll('slot_price' + package_number + i, 'slot_price' + new_package_number + i)
        html = html.replaceAll('slot_isprimary' + package_number + i, 'slot_isprimary' + new_package_number + i)
        html = html.replaceAll('isprimary_checkbox' + package_number + i, 'isprimary_checkbox' + new_package_number + i)
        html = html.replace('removeSlots(' + package_number + ',' + i + ')', 'removeSlots(' + new_package_number + ',' + i + ')" id="remove_package_slot' + package_number + i)
        html = html.replace('addSlots(' + package_number + ',' + i + ')', 'addSlots(' + new_package_number + ',' + i + ')" id="add_package_slot' + package_number + i)
        i++;
    }

    var a = 1;
    while (document.getElementById("slot_interval_custom_from_hour" + package_number + a)) {
        html = html.replaceAll('slot_interval_custom_from_hour' + package_number + a, 'slot_interval_custom_from_hour' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_from_minute' + package_number + a, 'slot_interval_custom_from_minute' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_from_ampm' + package_number + a, 'slot_interval_custom_from_ampm' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_hour' + package_number + a, 'slot_interval_custom_to_hour' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_minute' + package_number + a, 'slot_interval_custom_to_minute' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_ampm' + package_number + a, 'slot_interval_custom_to_ampm' + new_package_number + a);
        html = html.replaceAll('AddDurationrow(' + package_number + ',' + a + ')', 'AddDurationrow(' + new_package_number + ',' + a + ')');

        a++;
    }

    html = html.replaceAll('slot_clone' + package_number, 'slot_clone' + new_package_number)
    html = html.replaceAll('slot_clones' + package_number, 'slot_clones' + new_package_number)
    html = html.replaceAll('cloneBookingPackage(' + package_number + ')', 'cloneBookingPackage(' + new_package_number + ')')
    html = html.replaceAll('addBookingPackage(' + package_number + ')', 'addBookingPackage(' + new_package_number + ')')
    html = html.replaceAll('removeBookingPackage(' + package_number + ')', 'removeBookingPackage(' + new_package_number + ')" id="remove_package' + new_package_number)

    clone.innerHTML = '<div id="ddl_' + new_package_number + '">' + html + '</div>';
    document.getElementById('clones').appendChild(clone);
    document.getElementById("remove_package" + new_package_number).style.display = "block";
    var is_checked = document.getElementById('capture_detail_name' + package_number).value;
    var val_checked = '';
    if (is_checked == 1) {
        val_checked = 'checked';
    }
    document.querySelector('#multipleslot_remove' + new_package_number).innerHTML = '<input ' + val_checked + ' type="checkbox" id="multiplt_switch' + new_package_number + '" value="0" onchange = "is_multipleslots(this.checked, ' + "'capture_detail'" + ', ' + new_package_number + ')" class="make-switch"  data-on-text="&nbsp;Enabled&nbsp;&nbsp" data-off-text="&nbsp;Disabled&nbsp" />  <input type="hidden" value="' + is_checked + '" name="is_multiple' + new_package_number + '" id="capture_detail_name' + new_package_number + '">';
    $('#multiplt_switch' + new_package_number).bootstrapSwitch();

    setdatepicker();
    _('package_name' + new_package_number).value = _("package_name" + package_number).value;
    _('package_desc' + new_package_number).value = _("package_desc" + package_number).value;
    _('slot_date_from' + new_package_number).value = _("slot_date_from" + package_number).value;
    _('slot_date_to' + new_package_number).value = _("slot_date_to" + package_number).value;
    _('weekday-mon' + new_package_number).value = _("weekday-mon" + package_number).value;
    _('weekday-tue' + new_package_number).value = _("weekday-tue" + package_number).value;
    _('weekday-wed' + new_package_number).value = _("weekday-wed" + package_number).value;
    _('weekday-thu' + new_package_number).value = _("weekday-thu" + package_number).value;
    _('weekday-fri' + new_package_number).value = _("weekday-fri" + package_number).value;
    _('weekday-sat' + new_package_number).value = _("weekday-sat" + package_number).value;
    _('weekday-sun' + new_package_number).value = _("weekday-sun" + package_number).value;

    _('weekday-mon' + new_package_number).checked = _("weekday-mon" + package_number).checked;
    _('weekday-tue' + new_package_number).checked = _("weekday-tue" + package_number).checked;
    _('weekday-wed' + new_package_number).checked = _("weekday-wed" + package_number).checked;
    _('weekday-thu' + new_package_number).checked = _("weekday-thu" + package_number).checked;
    _('weekday-fri' + new_package_number).checked = _("weekday-fri" + package_number).checked;
    _('weekday-sat' + new_package_number).checked = _("weekday-sat" + package_number).checked;
    _('weekday-sun' + new_package_number).checked = _("weekday-sun" + package_number).checked;

    _('slot_interval' + new_package_number).value = _("slot_interval" + package_number).value;
    _('slot_interval_minutes' + new_package_number).value = _("slot_interval_minutes" + package_number).value;
    _('slot_pause' + new_package_number).value = _("slot_pause" + package_number).value;
    _('slot_time_from_hour' + new_package_number).value = _("slot_time_from_hour" + package_number).value;
    _('slot_time_from_minute' + new_package_number).value = _("slot_time_from_minute" + package_number).value;
    _('slot_time_from_ampm' + new_package_number).value = _("slot_time_from_ampm" + package_number).value;
    _('slot_time_to_hour' + new_package_number).value = _("slot_time_to_hour" + package_number).value;
    _('slot_time_to_minute' + new_package_number).value = _("slot_time_to_minute" + package_number).value;
    _('slot_time_to_ampm' + new_package_number).value = _("slot_time_to_ampm" + package_number).value;

    _('unitavailable' + new_package_number).value = _("unitavailable" + package_number).value;
    _('min_seat' + new_package_number).value = _("min_seat" + package_number).value;
    _('max_seat' + new_package_number).value = _("max_seat" + package_number).value;

    var j = 1;
    while (document.getElementById("slot_title" + new_package_number + j)) {
        _('slot_title' + new_package_number + j).value = _("slot_title" + package_number + j).value;
        _('slot_description' + new_package_number + j).value = _("slot_description" + package_number + j).value;
        _('slot_price' + new_package_number + j).value = _("slot_price" + package_number + j).value;
        _('slot_isprimary' + new_package_number + j).value = _("slot_isprimary" + package_number + j).value;
        document.getElementById("uniform-isprimary_checkbox" + new_package_number + j).classList.remove("checker");
        j++;
    }

    var b = 1;
    while (document.getElementById("slot_interval_custom_from_hour" + new_package_number + b)) {
        _('slot_interval_custom_from_hour' + new_package_number + b).value = _("slot_interval_custom_from_hour" + package_number + b).value;
        _('slot_interval_custom_from_minute' + new_package_number + b).value = _("slot_interval_custom_from_minute" + package_number + b).value;
        _('slot_interval_custom_from_ampm' + new_package_number + b).value = _("slot_interval_custom_from_ampm" + package_number + b).value;
        _('slot_interval_custom_to_hour' + new_package_number + b).value = _("slot_interval_custom_to_hour" + package_number + b).value;
        _('slot_interval_custom_to_minute' + new_package_number + b).value = _("slot_interval_custom_to_minute" + package_number + b).value;
        _('slot_interval_custom_to_ampm' + new_package_number + b).value = _("slot_interval_custom_to_ampm" + package_number + b).value;
        b++;
    }

    $("#package_image" + new_package_number).prop("files", $("#package_image" + package_number).prop("files"));

}

function addBookingPackage(package_number) {
    var new_package_number = Number(package_number) + 1;
    while (document.getElementById("package_name" + new_package_number)) {
        new_package_number = new_package_number + 1;
    }
    var html = document.getElementById('ddl_' + package_number).innerHTML;
    var clone = document.createElement('span');
    clone.setAttribute("id", "package" + new_package_number)
    html = html.replaceAll('name="package_number[]" value="' + package_number + '"', 'name="package_number[]" value="' + new_package_number + '"')
    html = html.replaceAll('package_name' + package_number, 'package_name' + new_package_number)
    html = html.replaceAll('package_desc' + package_number, 'package_desc' + new_package_number)
    html = html.replaceAll('package_image' + package_number, 'package_image' + new_package_number)
    html = html.replaceAll('package_image_dismiss' + package_number, 'package_image_dismiss' + new_package_number)
    html = html.replaceAll('slot_date_from' + package_number, 'slot_date_from' + new_package_number)
    html = html.replaceAll('slot_date_to' + package_number, 'slot_date_to' + new_package_number)
    html = html.replaceAll('new_duration' + package_number, 'new_duration' + new_package_number)
    html = html.replaceAll('clearSeconddate(' + package_number + ')', 'clearSeconddate(' + new_package_number + ')')
    html = html.replaceAll('weekday-mon' + package_number, 'weekday-mon' + new_package_number)
    html = html.replaceAll('weekday-tue' + package_number, 'weekday-tue' + new_package_number)
    html = html.replaceAll('weekday-wed' + package_number, 'weekday-wed' + new_package_number)
    html = html.replaceAll('weekday-thu' + package_number, 'weekday-thu' + new_package_number)
    html = html.replaceAll('weekday-fri' + package_number, 'weekday-fri' + new_package_number)
    html = html.replaceAll('weekday-sat' + package_number, 'weekday-sat' + new_package_number)
    html = html.replaceAll('weekday-sun' + package_number, 'weekday-sun' + new_package_number)
    html = html.replaceAll('slot_weekday' + package_number, 'slot_weekday' + new_package_number)
    html = html.replaceAll('slot_interval' + package_number, 'slot_interval' + new_package_number)
    html = html.replaceAll('slot_interval_minutes' + package_number, 'slot_interval_minutes' + new_package_number)
    html = html.replaceAll('slot_pause' + package_number, 'slot_pause' + new_package_number)
    html = html.replaceAll('slot_time_from_hour' + package_number, 'slot_time_from_hour' + new_package_number)
    html = html.replaceAll('slot_time_from_minute' + package_number, 'slot_time_from_minute' + new_package_number)
    html = html.replaceAll('slot_time_from_ampm' + package_number, 'slot_time_from_ampm' + new_package_number)
    html = html.replaceAll('slot_time_to_hour' + package_number, 'slot_time_to_hour' + new_package_number)
    html = html.replaceAll('slot_time_to_minute' + package_number, 'slot_time_to_minute' + new_package_number)
    html = html.replaceAll('slot_time_to_ampm' + package_number, 'slot_time_to_ampm' + new_package_number)
    html = html.replaceAll('tyfromto' + package_number, 'tyfromto' + new_package_number)
    html = html.replaceAll('tymin' + package_number, 'tymin' + new_package_number)
    html = html.replaceAll('showCustom(this.value,' + package_number + ')', 'showCustom(this.value,' + new_package_number + ')')
    html = html.replaceAll('slot_interval_custom_from_hour' + package_number + '[]', 'slot_interval_custom_from_hour' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_from_minute' + package_number + '[]', 'slot_interval_custom_from_minute' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_from_ampm' + package_number + '[]', 'slot_interval_custom_from_ampm' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_hour' + package_number + '[]', 'slot_interval_custom_to_hour' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_minute' + package_number + '[]', 'slot_interval_custom_to_minute' + new_package_number + '[]')
    html = html.replaceAll('slot_interval_custom_to_ampm' + package_number + '[]', 'slot_interval_custom_to_ampm' + new_package_number + '[]')
    html = html.replaceAll('unitavailable' + package_number, 'unitavailable' + new_package_number)
    html = html.replaceAll('min_seat' + package_number, 'min_seat' + new_package_number)
    html = html.replaceAll('max_seat' + package_number, 'max_seat' + new_package_number)

    html = html.replaceAll('cloneBookingPackage(' + package_number + ')', 'cloneBookingPackage(' + new_package_number + ')')
    html = html.replaceAll('addBookingPackage(' + package_number + ')', 'addBookingPackage(' + new_package_number + ')')

    html = html.replaceAll('multipleslot_remove' + package_number, 'multipleslot_remove' + new_package_number)
    html = html.replaceAll('is_multiple' + package_number, 'is_multiple' + new_package_number)
    html = html.replaceAll('capture_detail' + package_number, 'capture_detail' + new_package_number)
    html = html.replaceAll('slot_title' + package_number + '[]', 'slot_title' + new_package_number + '[]')
    html = html.replaceAll('slot_description' + package_number + '[]', 'slot_description' + new_package_number + '[]')
    html = html.replaceAll('slot_price' + package_number + '[]', 'slot_price' + new_package_number + '[]')
    html = html.replaceAll('slot_isprimary' + package_number + '[]', 'slot_isprimary' + new_package_number + '[]')

    html = html.replaceAll('capture_detail_name' + package_number, 'capture_detail_name' + new_package_number)

    var i = 1;
    while (document.getElementById("slot_title" + package_number + i)) {
        html = html.replaceAll('slot' + package_number + i, 'slot' + new_package_number + i)
        html = html.replaceAll('slot_title' + package_number + i, 'slot_title' + new_package_number + i)
        html = html.replaceAll('slot_description' + package_number + i, 'slot_description' + new_package_number + i)
        html = html.replaceAll('slot_price' + package_number + i, 'slot_price' + new_package_number + i)
        html = html.replaceAll('slot_isprimary' + package_number + i, 'slot_isprimary' + new_package_number + i)
        html = html.replaceAll('isprimary_checkbox' + package_number + i, 'isprimary_checkbox' + new_package_number + i)
        html = html.replace('removeSlots(' + package_number + ',' + i + ')', 'removeSlots(' + new_package_number + ',' + i + ')" id="remove_package_slot' + package_number + i)
        html = html.replace('addSlots(' + package_number + ',' + i + ')', 'addSlots(' + new_package_number + ',' + i + ')" id="add_package_slot' + package_number + i)
        i++;
    }

    var a = 1;
    while (document.getElementById("slot_interval_custom_from_hour" + package_number + a)) {
        html = html.replaceAll('slot_interval_custom_from_hour' + package_number + a, 'slot_interval_custom_from_hour' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_from_minute' + package_number + a, 'slot_interval_custom_from_minute' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_from_ampm' + package_number + a, 'slot_interval_custom_from_ampm' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_hour' + package_number + a, 'slot_interval_custom_to_hour' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_minute' + package_number + a, 'slot_interval_custom_to_minute' + new_package_number + a);
        html = html.replaceAll('slot_interval_custom_to_ampm' + package_number + a, 'slot_interval_custom_to_ampm' + new_package_number + a);
        html = html.replaceAll('AddDurationrow(' + package_number + ',' + a + ')', 'AddDurationrow(' + new_package_number + ',' + a + ')');
        a++;
    }

    html = html.replaceAll('slot_clone' + package_number, 'slot_clone' + new_package_number)
    html = html.replaceAll('slot_clones' + package_number, 'slot_clones' + new_package_number)

    html = html.replaceAll('removeBookingPackage(' + package_number + ')', 'removeBookingPackage(' + new_package_number + ')" id="remove_package' + new_package_number)
    clone.innerHTML = '<div id="ddl_' + new_package_number + '">' + html + '</div>';
    document.getElementById('clones').appendChild(clone);
    document.getElementById("remove_package" + new_package_number).style.display = "block";
    var is_checked = document.getElementById('capture_detail_name' + package_number).value;
    var val_checked = '';
    if (is_checked == 1) {
        val_checked = 'checked';
    }

    document.querySelector('#multipleslot_remove' + new_package_number).innerHTML = '<input ' + val_checked + ' type="checkbox" id="multiplt_switch' + new_package_number + '" value="0" onchange = "is_multipleslots(this.checked, ' + "'capture_detail'" + ', ' + new_package_number + ')" class="make-switch"  data-on-text="&nbsp;Enabled&nbsp;&nbsp" data-off-text="&nbsp;Disabled&nbsp" />  <input type="hidden" value="' + is_checked + '" name="is_multiple' + new_package_number + '" id="capture_detail_name' + new_package_number + '">';
    $('#multiplt_switch' + new_package_number).bootstrapSwitch();

    setdatepicker();

    document.getElementById('package_image_dismiss' + new_package_number).click();

    var j = 1;
    while (document.getElementById("slot_title" + new_package_number + j)) {
        document.getElementById("uniform-isprimary_checkbox" + new_package_number + j).classList.remove("checker");
        j++;
    }

}

function setdatepicker() {
    $('.date-picker').datepicker({
        rtl: Swipez.isRTL(),
        orientation: "left",
        autoclose: true,
        todayHighlight: true
    });
}

function addSlots(package_number, slot_number) {
    var new_slot_number = Number(slot_number) + 1;
    while (document.getElementById("slot_title" + package_number + new_slot_number)) {
        new_slot_number = new_slot_number + 1;
    }
    var html = document.getElementById('slot_clone' + package_number).innerHTML;
    var clone = document.createElement('span');
    clone.setAttribute("id", "slot" + package_number + new_slot_number)
    html = html.replace('slot_title' + package_number + slot_number, 'slot_title' + package_number + new_slot_number)
    html = html.replace('slot_description' + package_number + slot_number, 'slot_description' + package_number + new_slot_number)
    html = html.replace('slot_price' + package_number + slot_number, 'slot_price' + package_number + new_slot_number)
    html = html.replaceAll('slot_isprimary' + package_number + slot_number, 'slot_isprimary' + package_number + new_slot_number)
    html = html.replaceAll('isprimary_checkbox' + package_number + slot_number, 'isprimary_checkbox' + package_number + new_slot_number)

    html = html.replace('removeSlots(' + package_number + ',' + slot_number + ')', 'removeSlots(' + package_number + ',' + new_slot_number + ')" id="remove_package_slot' + package_number + new_slot_number)
    html = html.replace('addSlots(' + package_number + ',' + slot_number + ')', 'addSlots(' + package_number + ',' + new_slot_number + ')" id="add_package_slot' + package_number + new_slot_number)

    clone.innerHTML = html;
    document.getElementById('slot_clones' + package_number).appendChild(clone);
    document.getElementById("remove_package_slot" + package_number + new_slot_number).style.display = "block";
    document.getElementById("add_package_slot" + package_number + new_slot_number).style.display = "none";
    document.getElementById("uniform-isprimary_checkbox" + package_number + new_slot_number).classList.remove("checker");
    document.getElementById("slot_isprimary" + package_number + new_slot_number).value = "0";
}

function _(el) {
    return document.getElementById(el);
}

function packageClicked(id) {
    _("package_id").value = id

    document.getElementById("package_form").submit();

}

function packageSelected() {
    var packageselection = document.getElementById("packageselection");
    var packagebackbutton = document.getElementById("packagebackbutton");
    var slotsDiv = document.getElementById("slotsDiv");

    packageselection.style.display = "none";
    packagebackbutton.style.display = "block";
    slotsDiv.style.display = "block";
}

function backpackageSelected() {
    var packageselection = document.getElementById("packageselection");
    var packagebackbutton = document.getElementById("packagebackbutton");
    var slotsDiv = document.getElementById("slotsDiv");

    packageselection.style.display = "block";
    packagebackbutton.style.display = "none";
    slotsDiv.style.display = "none";
}

function removeBookingPackage(id) {
    var removeDivID = 'package' + id;
    _(removeDivID).remove()
}

function removeSlots(package_number, slot_number) {
    var removeDivID = 'slot' + package_number + slot_number;
    _(removeDivID).remove()
}

function trackChange(value, id) {
    document.getElementById(id).setAttribute('value', value);

}

function radiobuttonclicked() {
    $('input:radio').each(function () {

        var $this = $(this),
            package_id = $this.attr('value')

        if ($(this).prop('checked')) {
            document.getElementById("package_div2" + package_id).style.border = "2px solid #18AEBF";
            document.getElementById("package_div2" + package_id).style.boxSizing = "border-box";
        } else {
            document.getElementById("package_div2" + package_id).style.border = "0";
            document.getElementById("package_div2" + package_id).style.boxSizing = null;
        }

    });

}

function dateCheckboxclicked() {
    $('.weekDays-selector input:checkbox').each(function () {

        var $this = $(this),
            package_id = $this.attr('id')
        if ($(this).prop('checked')) {
            document.getElementById(package_id + '-div').style.setProperty('background-color', '#18aebf', 'important');
            document.getElementById(package_id + '-div').style.setProperty('color', '#fff', 'important');
        } else {
            document.getElementById(package_id + '-div').style.setProperty('background-color', '#fff', 'important');
            document.getElementById(package_id + '-div').style.setProperty('color', '#18aebf', 'important');
        }

    });

}

function bigImg(x) {
    x.style.setProperty('box-shadow', '0px 4px 4px rgba(0, 0, 0, 0.25)', 'important');
}

function normalImg(x) {
    x.style.boxShadow = null;
}

function setpackageonslotupdate(value) {
    document.getElementById("showslotbookings").style.display = "block";
}

function cancellationTypeClicked(value) {
    if(value > 1){
        document.getElementById("cancellation_div").style.display = "block";
    }else{
        document.getElementById("cancellation_div").style.display = "none";
    }
}