function sendtestemail()
{
    document.getElementById('emailidd').value = document.getElementById('merchant_email').value;
    document.getElementById('closebutton').click();
    document.getElementById('loader').style.display = 'block';
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/coveringnote/sendtestmail/store',
        data: data,
        success: function (data)
        {
            $("#categoryForm").submit();
        }
    });

    return false;
}

function confirmCovering(payment_request_id)
{
    var data = $("#confirm_covering_frm").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/getPostJson',
        data: data,
        success: function (data)
        {
            document.getElementById('ccclosebutton').click();
            document.getElementById('custom_covering').value = data;
            validateInvoice(payment_request_id);
        }
    });
}

function saveCovering(value)
{

    document.getElementById('loader').style.display = 'block';
    var data = $("#"+value+"covering_frm").serialize();
  
    $.ajax({
        type: 'POST',
        url: '/merchant/coveringnote/save/1',
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);

            if (obj.status == 1)
            {
                var x = document.getElementById("covering_select");
                var option = document.createElement("option");
                option.text = obj.name;
                option.value = obj.id;
                option.selected = 1;
                x.add(option);
                document.getElementById('edit_note_div').style='display:block';
                
                document.getElementById(value+'cclosebutton').click();
                document.getElementById(value+'covering_error').style.display = 'none';
                document.getElementById(value+'covering_error').innerHTML = '';
                closeSidePanelcover();
            } else
            {
                document.getElementById(value+'covering_error').style.display = 'block';
                document.getElementById(value+'covering_error').innerHTML = obj.error;
            }
         
            document.getElementById('loader').style.display = 'none';
        }
    });

    return false;
}

function saveAutocollectPlan()
{
    var data = $("#autocollect_frm").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/saveautoplan',
        data: data,
        success: function (data)
        {
            document.getElementById("acsubbutton").disable = true;
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                var x = document.getElementById("autocollect_plan_id");
                var option = document.createElement("option");
                option.text = obj.plan_name;
                option.value = obj.plan_id;
                option.selected = 1;
                x.add(option);
                document.getElementById('acclosebutton').click();
                document.getElementById('autocollect_error').style.display = 'none';
                document.getElementById('autocollect_error').innerHTML = '';
                document.getElementById("acsubbutton").disable = false;
            } else
            {
                document.getElementById('autocollect_error').style.display = 'block';
                document.getElementById('autocollect_error').innerHTML = obj.errors;
                document.getElementById("acsubbutton").disable = false;
            }

        }
    });
}