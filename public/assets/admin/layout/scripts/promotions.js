function save_sms_template()
{
    var data = $("#categoryForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/promotions/savetemplate',
        data: data,
        success: function(data)
        {
            var error='';
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                $('#cat_drop').append('<option selected value="' + obj.sms + '">' + obj.name + '</option>');
                document.getElementById('message').value = obj.sms;
                document.getElementById('template_sms').value = obj.sms;
                document.getElementById('template_id').value = obj.template_id;
                document.getElementById('template_name').value = obj.name;
                document.getElementById("closebutton").click();
            } else
            {
                $.each(obj['error'], function(index, value) {
                        error = error + value.value + '<br>';
                    });
                document.getElementById('errors').innerHTML =error;
                document.getElementById('errors').style.display = 'block';
            }

        }
    });

    return false;
}


function save_promotion()
{
    document.getElementById('loader').style.display = 'block';
    var data = $("#promotion").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/promotions/validatepromotion',
        data: data,
        success: function(data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                document.getElementById('grp_errors').style.display = 'none';
                document.getElementById("promotion").submit();
            } else
            {
                document.getElementById('loader').style.display = 'none';
                var error = '';
                try {
                    $.each(obj['error'], function(index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('grp_errors').innerHTML = error;
                } catch (o)
                {
                    alert(o.message);
                }
                document.getElementById('grp_errors').style.display = 'block';
                document.getElementById('grp_success').style.display = 'none';
                return false;
            }

        }
    });

    return false;
}

function setSMS()
{
    document.getElementById('message').value = $("#cat_drop").find("option:selected").attr("title");
    document.getElementById('template_sms').value = $("#cat_drop").find("option:selected").attr("title");
    document.getElementById('template_id').value = $("#cat_drop option:selected").val();
    document.getElementById('template_name').value = $("#cat_drop option:selected").text();
}

function selectGroup(grp)
{
    document.getElementById('group_hidden').value = grp;
    document.getElementById('grp_frm').submit();
}