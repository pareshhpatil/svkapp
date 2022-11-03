var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function repeatChange(value)
{

    if (value == '1')
    {
        document.getElementById('repeat_text').innerHTML = 'days';
        document.getElementById('repeat_on').style.display = "none";

    }
    if (value == '2')
    {
        document.getElementById('repeat_text').innerHTML = 'weeks';
        document.getElementById('repeat_on').style.display = "block";
        setStartdate();
    }
    if (value == '3')
    {

        document.getElementById('repeat_text').innerHTML = 'months';
        document.getElementById('repeat_on').style.display = "none";
    }
    if (value == '4')
    {
        document.getElementById('repeat_text').innerHTML = 'years';
        document.getElementById('repeat_on').style.display = "none";
    }
    diplayText();
}

function endModeChange(value)
{
    if (value == '1')
    {
        document.getElementById('end_date_text').value = '';
        document.getElementById('end_date_text').disabled = true;
        document.getElementById('occurence_text').disabled = true;
        document.getElementById('occurence_text').value = '';


    }
    if (value == '2')
    {
        document.getElementById('end_date_text').value = '';
        document.getElementById('end_date_text').disabled = true;
        document.getElementById('occurence_text').disabled = false;
    }
    if (value == '3')
    {
        document.getElementById('end_date_text').disabled = false;
        document.getElementById('occurence_text').disabled = true;
        document.getElementById('occurence_text').value = '';

        var currentdate = new Date();
        var day = currentdate.getDate();
        var month = currentdate.getMonth() + 1;
        var year = currentdate.getFullYear();
        var fulldate = ("0" + day).slice(-2) + ' ' + monthNames[month] + ' ' + year;
        document.getElementById('end_date_text').value = fulldate;
    }
    diplayText();
}

function setStartdate()
{
    var mode = document.getElementById('mode').value;
    var billdate = Date();
    if (mode == 2) {
        var weekday = document.getElementById('repeat_on_').value;
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
        var month = datenew.getMonth() + 1;
        var year = datenew.getFullYear();
        var fulldate = ("0" + day).slice(-2) + ' ' + monthNames[month] + ' ' + year;
    } else
    {
        var datenew = new Date();
        var day = datenew.getDate();
        var month = datenew.getMonth() + 1;
        var year = datenew.getFullYear();
        var fulldate = ("0" + day).slice(-2) + ' ' + monthNames[month] + ' ' + year;
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

    } else
    {
        var text = 'Due same day';
    }
    document.getElementById('due_datetime_text').innerHTML = text;
    document.getElementById('due_datetime_diff').value = diffDays;

}
function diplayText()
{

    var text = '';
    var mode = document.getElementById('mode').value;
    var start_date = document.getElementById('start_date').value;
    var occurrence = document.getElementById('occurence_text').value;
    var end_date = document.getElementById('end_date_text').value;
    var repeat = document.getElementById('repeat_every').value;
    var endmode = document.querySelector('input[name="end_mode"]:checked').value;
    var skillsSelect = document.getElementById("repeat_on_");
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
        if (start_date != '')
        {
            var d = new Date(start_date);
            var n = d.getDate();
            text = text + ' on day ' + n;
        }

        

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
        if (start_date != '')
        {
        var d = new Date(start_date);
        var n = d.getDate();
        var m = monthNames[d.getMonth()+1];

        text = text + ' on ' + m + ' ' + n;
        }
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


function callSidePanel(subscription_id) {
    if(subscription_id !='') {
        document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)"; 
        document.getElementById("panelWrapId").style.transform = "translateX(0%)"; 
        document.getElementById("close_tab").style.display="block";
        $('.page-header-fixed').css('pointer-events','none');
        $("#close_tab").css('pointer-events','all');
        $("#panelWrapId").css('pointer-events','all');
        $('.delete_modal').css('pointer-events','all');
        $.ajax({
            type: "POST",
            url: '/merchant/subscription/getSubscriptionDetails',
            data: {
                'subscription_id':subscription_id
            },
            datatype: 'html',
            success: function(response) {
                $("#subscription_view_ajax").html(response);
                SubscriptionTableAdvanced.init();
            },
            error: function() {
            },
        }); 
    }
    return false;
}
function closeSidePanel() {
    document.getElementById("panelWrapId").style.boxShadow = "none"; 
    document.getElementById("panelWrapId").style.transform = "translateX(100%)";
    document.getElementById("close_tab").style.display="none";
    $('.page-header-fixed').css('pointer-events','all');
    return false;
}