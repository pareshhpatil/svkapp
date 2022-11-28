var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];







function repeatChange(value)
{
    if (value == '1')
    {
        document.getElementById('repeat_text').innerHTML = 'days';
        document.getElementById('repeat_on').style.display = 'none';

    }
    if (value == '2')
    {
        document.getElementById('repeat_text').innerHTML = 'weeks';
        document.getElementById('repeat_on').style.display = 'block';
    }
    if (value == '3')
    {
        document.getElementById('repeat_text').innerHTML = 'months';
        document.getElementById('repeat_on').style.display = 'none';
    }
    if (value == '4')
    {
        document.getElementById('repeat_text').innerHTML = 'years';
        document.getElementById('repeat_on').style.display = 'none';
    }
    diplayText();
}

function endModeChange(value)
{
    if (value == '1')
    {
        document.getElementById('end_date').value = '';
        document.getElementById('end_date').disabled = true;
        document.getElementById('occurrence').disabled = true;
        document.getElementById('occurrence').value = '';


    }
    if (value == '2')
    {
        document.getElementById('end_date').disabled = true;
        document.getElementById('end_date').value = '';
        document.getElementById('occurrence').disabled = false;
    }
    if (value == '3')
    {
        document.getElementById('end_date').disabled = false;
        document.getElementById('occurrence').disabled = true;
        document.getElementById('occurrence').value = '';
        var date1 = document.getElementById('start_date').value;
        document.getElementById('end_date').value = date1;
        showDatePicker('end_date', date1);
    }
    diplayText();
}

function setStartdate()
{
    var mode = document.getElementById('mode').value;
    var billdate = Date();
    if (mode == 2) {
        var weekday = document.getElementById('weekdays').value;
        var d = new Date(billdate);
        var k = d.getDay() + 1;
        if (parseInt(k) > parseInt(weekday))
        {
            var addday = 7 - parseInt(k) + parseInt(weekday);
        }
        if (parseInt(k) < parseInt(weekday))
        {
            var addday = parseInt(weekday) - parseInt(k);
        }
        if (parseInt(k) == parseInt(weekday))
        {
            var addday = 0;
        }



        var datenew = new Date(d.getTime() + addday * 24 * 60 * 60 * 1000);
        var day = datenew.getDate();
        var month = monthNames[datenew.getMonth()];
        var year = datenew.getFullYear();
        var fulldate = day + ' ' + month + ' ' + year;
    } else
    {
        var datenew = new Date();
        var day = datenew.getDate();
        var month = monthNames[datenew.getMonth()];
        var year = datenew.getFullYear();
        var fulldate = day + ' ' + month + ' ' + year;
    }
    document.getElementById('start_date').value = fulldate;

}

function duedateSummery()
{
    var billdate = document.getElementById('start_date').value;
    var billdate2 = document.getElementById('due_datetime').value;
    var date1 = new Date(billdate);
    var date2 = new Date(billdate2);
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    if (diffDays > 0)
    {
        var text = 'Due within ' + diffDays + ' days';
        document.getElementById('due_datetime_diff').value = diffDays;

    } else
    {
        var text = 'Due same day';
        document.getElementById('due_datetime_diff').value = '0';
    }
    document.getElementById('due_datetime_text').innerHTML = text;

}
function diplayText()
{
    var start_date = document.getElementById('start_date').value;
    if (start_date == '')
    {
        setStartdate();
    }
    var text = '';
    var mode = document.getElementById('mode').value;
    var start_date = document.getElementById('start_date').value;
    var occurrence = document.getElementById('occurrence').value;
    var end_date = document.getElementById('end_date').value;
    var repeat = document.getElementById('repeat_every').value;
    var endmode = document.querySelector('input[name="end_mode"]:checked').value;
    var skillsSelect = document.getElementById("weekdays");
    var weekday = skillsSelect.options[skillsSelect.selectedIndex].text;

    if (mode == 1)
    {
        if (repeat == 1)
        {
            text = text + 'Daily';
        } else
        {
            text = text + 'Every ' + repeat + ' days';
        }



    }
    if (mode == 2)
    {
        if (repeat == 1)
        {
            text = text + 'Weekly';
        } else
        {
            text = text + 'Every ' + repeat + ' weeks';
        }

        text = text + ' on ' + weekday;
    }
    if (mode == 3)
    {
        if (repeat == 1)
        {
            text = text + 'Monthly';
        } else
        {
            text = text + 'Every ' + repeat + ' months';
        }

        var d = new Date(start_date);
        var n = d.getDate();

        text = text + ' on day ' + n;

    }
    if (mode == 4)
    {
        if (repeat == 1)
        {
            text = text + 'Annually';
        } else
        {
            text = text + 'Every ' + repeat + ' years';
        }

        var d = new Date(start_date);
        var n = d.getDate();
        var m = monthNames[d.getMonth()];

        text = text + ' on ' + m + ' ' + n;
    }

    if (endmode == 2)
    {
        if (occurrence > 0) {
            text = text + ', ' + occurrence + ' times';
        }
    }
    if (endmode == 3)
    {
        text = text + ', until ' + end_date;
    }

    document.getElementById('display_text').innerHTML = text;
    document.getElementById('_display_text').value = text;
    duedateSummery();
}


