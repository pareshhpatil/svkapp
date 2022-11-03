function setBenifeciaryType(type)
{
    if (type == 1)
    {
        type = 'Customer';
    }
    var data = '';
    $('#customer_id').empty();
    $('#customer_id').append('<option selected value="">Select ' + type + '</option>');
    $('#lbltype').html('Select ' + type);
    if (type == 'Customer' || type == 'Franchise' || type == 'Vendor')
    {
        $('#divtype').show();
        $("#addnewlink").attr("href", "#MD" + type);
        $.ajax({
            type: 'GET',
            url: '/ajax/getbeneficiaryList/' + type,
            data: data,
            success: function (data)
            {
                if (data != '')
                {
                    obj = JSON.parse(data);
                    $.each(obj, function (index, value) {
                        $('#customer_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                }

            }
        });
    } else
    {
        $('#divtype').hide();
    }
    $("#customer_id").select2();
}

function setBenifeciaryDetail(id)
{
    var data = '';
    type = $('#beneficiarytype').val();
    if (type == 'Customer' || type == 'Franchise' || type == 'Vendor')
    {
        $.ajax({
            type: 'GET',
            url: '/ajax/getbeneficiaryDetail/' + type + '/' + id,
            data: data,
            success: function (data)
            {
                obj = JSON.parse(data);
                $.each(obj[0], function (index, value) {
                    $('#' + index).val(value);
                });
            }
        });
    } else
    {
    }
}

function saveBeneCustomer()
{
    display_warning();
    $("#customer_id").select2();
    return false;
}

function saveBeneVendor()
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
function saveBeneFranchise()
{
    var data = $("#franchiseForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/franchise/franchisesave/ajax',
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
                $('#franchiseForm').trigger("reset");
                $("#closebuttonf").click();
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