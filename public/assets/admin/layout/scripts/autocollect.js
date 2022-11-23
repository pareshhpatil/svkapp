function saveAutocollectPlan()
{
    var data = $("#vendorForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/vendor/vendorsave/ajax',
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                try {
                    var x = document.getElementById("customer_id");
                    var option = document.createElement("option");
                    option.text = obj.name;
                    option.value = obj.id;
                    option.selected = 1;
                    x.add(option);
                } catch (o) {

                }
                $('#vendorForm').trigger("reset");
                $("#closebuttonv").click();
            } else
            {
                var error = '';
                try {
                    $.each(obj['error'], function (index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('errorshow').style.display = 'block';
                    document.getElementById('error_display').innerHTML = error;
                } catch (o)
                {
                    alert(o.message);
                }
            }

        }
    });
    return false;
}
