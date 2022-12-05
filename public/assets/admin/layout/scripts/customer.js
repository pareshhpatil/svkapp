var numherder = 1;
var login_user_id = '';

function removedivexist(id)
{
    var ab = 'exist' + id;

    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
}

function addCustomerField() {
    custom_column_id = document.getElementById("custom_column_id").value;
    if (custom_column_id == 'new')
    {
        newCustomerField();
    } else
    {
        column_datatype = document.getElementById("datatype").value;
        custom_column_name = document.getElementById("custom_column_name").value;

        if (column_datatype == 'textarea')
        {
            disable_textbox = '<textarea class="form-control" readonly="" ></textarea>';
        } else
        {
            disable_textbox = '<input type="text" class="form-control" readonly="" >';
        }


        document.getElementById("datatype" + custom_column_id + "").value = column_datatype;
        document.getElementById("columnname" + custom_column_id + "").value = custom_column_name;
        switch (column_datatype)
        {
            case 'text':
                icon = 'fa-font';
                break;
            case 'number':
                icon = 'fa-sort-numeric-asc';
                break;
            case 'textarea':
                icon = 'fa-file-text-o';
                break;
            case 'date':
                icon = 'fa-calendar';
                break;
            case 'password':
                icon = 'fa-lock';
                break;
            default :
                icon = 'fa-font';
                break;
        }
        document.getElementById("icon" + custom_column_id + "").className = 'fa ' + icon;
        document.getElementById("datatypediv" + custom_column_id + "").innerHTML = disable_textbox;
        $("#edit" + custom_column_id + "").attr("onclick", "editclick(" + custom_column_id + ",'" + column_datatype + "');");
    }
    $('#closebutton').click();


}
function editclick(id, value)
{
    $('#custom_column_id').val("" + id + "");
    $('#datatype').val("" + value + "").attr('selected', 'selected');
    document.getElementById("custom_column_name").value = document.getElementById("columnname" + id + "").value;
}
function newCustomerField()
{
    numherder++;
    while (document.getElementById('exist' + numherder)) {
        numherder = numherder + 1;
    }
    column_datatype = document.getElementById("datatype").value;
    custom_column_name = document.getElementById("custom_column_name").value;


    var node_listleft = document.getElementsByName("leftcount");
    var node_listright = document.getElementsByName("rightcount");
    var leftcount = Number(node_listleft.length);
    var rightcount = Number(node_listright.length);
    if (Number(rightcount) < Number(leftcount)) {
        var mainDiv = document.getElementById('newHeaderright');
        counttext = 'rightcount';
        side_name = 'R';
    } else
    {
        var mainDiv = document.getElementById('newHeaderleft');
        counttext = 'leftcount';
        side_name = 'L';
    }

    var newDiv = document.createElement('div');

    var newSpan = document.createElement('div');
    newSpan.setAttribute('id', 'exist' + numherder);
    newSpan.setAttribute('class', 'form-group');




    switch (column_datatype)
    {
        case 'money':
            icon = 'fa-inr';
            break;
        case 'text':
            icon = 'fa-font';
            break;
        case 'number':
            icon = 'fa-sort-numeric-asc';
            break;
        case 'primary':
            icon = 'fa-anchor';
            break;
        case 'textarea':
            icon = 'fa-file-text-o';
            break;
        case 'date':
            icon = 'fa-calendar';
            break;
        case 'password':
            icon = 'fa-lock';
            break;
            default :
                icon = 'fa-font';
                break;
    }

    if (column_datatype == 'textarea')
    {
        disable_textbox = '<div id="datatypediv' + numherder + '"><textarea class="form-control" readonly="" ></textarea></div>';
    } else
    {
        disable_textbox = '<div id="datatypediv' + numherder + '"><input type="text" class="form-control" readonly="" ></div>';
    }

    onclick_event = "editclick(" + numherder + ",'" + column_datatype + "');";
    newSpan.innerHTML = '<div class="col-md-6"><div class="input-group"><input name="' + counttext + '" type="hidden"><input name="position[]" type="hidden" value="' + side_name + '"><input type="text" name="column_name[]" value="' + custom_column_name + '" id="columnname' + numherder + '" class="form-control"><span class="input-group-btn"><div class="btn default"> <i id="icon' + numherder + '" class="fa ' + icon + '"></i></div><a class="btn default" data-toggle="modal" id="edit' + numherder + '" onclick="' + onclick_event + '" href="#custom"><i class="fa fa-edit"></i></a></span></div></div><input type="hidden" id="datatype' + numherder + '" name="datatype[]" value="' + column_datatype + '"> <div class="col-md-5"><div class="input-group">' + disable_textbox + ' <span class="input-group-addon " id="' + numherder + '" onclick="removedivexist(this.id)"><i class="fa fa-minus-circle"></i></span> </div> </div>';

    mainDiv.appendChild(newSpan);
}

function saveAutogenerate()
{
    var x = document.getElementById("autogen").checked;
    if (x == true)
    {
        a = 1;
    } else {
        a = 0;
    }
    document.getElementById("prefix_hide").value = document.getElementById("prefix").value;
    document.getElementById("is_autogenerate").value = a;
    $('#closeauto').click();
    document.getElementById('loader').style.display = 'block';
    $("#submit_form").submit();
    
}


function approve_all(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/approve_all/' + id,
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            try {
                $.each(obj['ids'], function (index, value) {
                    document.getElementById('approve_' + value).style.backgroundColor = 'lightgray';
                    document.getElementById('approve_' + value).onclick = '';
                    document.getElementById('approve_' + value).style.cursor = 'auto';

                    document.getElementById('reject_' + value).style.backgroundColor = 'lightgray';
                    document.getElementById('reject_' + value).onclick = '';
                    document.getElementById('reject_' + value).style.cursor = 'auto';

                    document.getElementById('undo_' + value).style.backgroundColor = '#2386ca';
                    document.getElementById('undo_' + value).setAttribute("onClick", "return undo(" + value + ");");
                    document.getElementById('undo_' + value).style.color = '#FFFFFF';
                    document.getElementById('undo_' + value).style.cursor = 'pointer';

                    document.getElementById('status_' + value).innerHTML = 'Approved';
                    document.getElementById('status_' + value).className = 'label label-sm label-success';
                });
            } catch (o)
            {
            }
            document.getElementById('approve_all_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('approve_all_' + id).onclick = '';
            document.getElementById('approve_all_' + id).style.cursor = 'auto';

            document.getElementById('reject_all_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('reject_all_' + id).onclick = '';
            document.getElementById('reject_all_' + id).style.cursor = 'auto';

            document.getElementById('undo_all_' + id).style.backgroundColor = '#2386ca';
            document.getElementById('undo_all_' + id).setAttribute("onClick", "return undo_all(" + id + ");");
            document.getElementById('undo_all_' + id).style.color = '#FFFFFF';
            document.getElementById('undo_all_' + id).style.cursor = 'pointer';
        }
    });

    return false;
}

function reject_all(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/reject_all/' + id,
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            try {
                $.each(obj['ids'], function (index, value) {
                    document.getElementById('approve_' + value).style.backgroundColor = 'lightgray';
                    document.getElementById('approve_' + value).onclick = '';
                    document.getElementById('approve_' + value).style.cursor = 'auto';

                    document.getElementById('reject_' + value).style.backgroundColor = 'lightgray';
                    document.getElementById('reject_' + value).onclick = '';
                    document.getElementById('reject_' + value).style.cursor = 'auto';

                    document.getElementById('undo_' + value).style.backgroundColor = '#2386ca';
                    document.getElementById('undo_' + value).style.color = '#FFFFFF';
                    document.getElementById('undo_' + value).style.cursor = 'pointer';
                    document.getElementById('undo_' + value).setAttribute("onClick", "return undo(" + value + ");");

                    document.getElementById('status_' + value).innerHTML = 'Rejected';
                    document.getElementById('status_' + value).className = 'label label-sm label-danger';
                });
            } catch (o)
            {

            }
            document.getElementById('approve_all_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('approve_all_' + id).onclick = '';
            document.getElementById('approve_all_' + id).style.cursor = 'auto';

            document.getElementById('reject_all_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('reject_all_' + id).onclick = '';
            document.getElementById('reject_all_' + id).style.cursor = 'auto';

            document.getElementById('undo_all_' + id).style.backgroundColor = '#2386ca';
            document.getElementById('undo_all_' + id).setAttribute("onClick", "return undo_all(" + id + ");");
            document.getElementById('undo_all_' + id).style.color = '#FFFFFF';
            document.getElementById('undo_all_' + id).style.cursor = 'pointer';
        }
    });

    return false;
}

function undo_all(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/undo_all/' + id,
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            try {
                $.each(obj['ids'], function (index, value) {
                    document.getElementById('approve_' + value).style.backgroundColor = '#1f897f';
                    document.getElementById('approve_' + value).setAttribute("onClick", "return approve(" + value + ");");
                    document.getElementById('approve_' + value).style.cursor = 'pointer';

                    document.getElementById('reject_' + value).style.backgroundColor = '#c23f44';
                    document.getElementById('reject_' + value).setAttribute("onClick", "return reject(" + value + ");");
                    document.getElementById('reject_' + value).style.cursor = 'pointer';

                    document.getElementById('undo_' + value).style.backgroundColor = 'lightgray';
                    document.getElementById('undo_' + value).style.color = 'black';
                    document.getElementById('undo_' + value).style.cursor = 'auto';
                    document.getElementById('undo_' + value).onclick = '';


                    document.getElementById('status_' + value).innerHTML = 'Pending';
                    document.getElementById('status_' + value).className = 'label label-sm label-primary';
                });
            } catch (o)
            {

            }
            document.getElementById('approve_all_' + id).style.backgroundColor = '#1f897f';
            document.getElementById('approve_all_' + id).setAttribute("onClick", "return approve_all(" + id + ");");
            document.getElementById('approve_all_' + id).style.cursor = 'pointer';

            document.getElementById('reject_all_' + id).style.backgroundColor = '#c23f44';
            document.getElementById('reject_all_' + id).setAttribute("onClick", "return reject_all(" + id + ");");
            document.getElementById('reject_all_' + id).style.cursor = 'pointer';

            document.getElementById('undo_all_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('undo_all_' + id).style.color = 'black';
            document.getElementById('undo_all_' + id).style.cursor = 'auto';
            document.getElementById('undo_all_' + id).onclick = '';
        }
    });

    return false;
}

function approve(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/approve_individual/' + id,
        data: data,
        success: function (data)
        {
            document.getElementById('approve_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('approve_' + id).onclick = '';
            document.getElementById('approve_' + id).style.cursor = 'auto';

            document.getElementById('reject_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('reject_' + id).onclick = '';
            document.getElementById('reject_' + id).style.cursor = 'auto';

            document.getElementById('undo_' + id).style.backgroundColor = '#2386ca';
            document.getElementById('undo_' + id).setAttribute("onClick", "return undo(" + id + ");");
            document.getElementById('undo_' + id).style.color = '#FFFFFF';
            document.getElementById('undo_' + id).style.cursor = 'pointer';

            document.getElementById('status_' + id).innerHTML = 'Approved';
            document.getElementById('status_' + id).className = 'label label-sm label-success';

        }
    });

    return false;
}

function reject(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/reject_individual/' + id,
        data: data,
        success: function (data)
        {
            document.getElementById('approve_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('approve_' + id).onclick = '';
            document.getElementById('approve_' + id).style.cursor = 'auto';

            document.getElementById('reject_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('reject_' + id).onclick = '';
            document.getElementById('reject_' + id).style.cursor = 'auto';

            document.getElementById('undo_' + id).style.backgroundColor = '#2386ca';
            document.getElementById('undo_' + id).style.color = '#FFFFFF';
            document.getElementById('undo_' + id).style.cursor = 'pointer';
            document.getElementById('undo_' + id).setAttribute("onClick", "return undo(" + id + ");");

            document.getElementById('status_' + id).innerHTML = 'Rejected';
            document.getElementById('status_' + id).className = 'label label-sm label-danger';
        }
    });

    return false;
}

function undo(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/merchant/approve/undo_individual/' + id,
        data: data,
        success: function (data)
        {
            document.getElementById('approve_' + id).style.backgroundColor = '#1f897f';
            document.getElementById('approve_' + id).setAttribute("onClick", "return approve(" + id + ");");
            document.getElementById('approve_' + id).style.cursor = 'pointer';

            document.getElementById('reject_' + id).style.backgroundColor = '#c23f44';
            document.getElementById('reject_' + id).setAttribute("onClick", "return reject(" + id + ");");
            document.getElementById('reject_' + id).style.cursor = 'pointer';

            document.getElementById('undo_' + id).style.backgroundColor = 'lightgray';
            document.getElementById('undo_' + id).style.color = 'black';
            document.getElementById('undo_' + id).style.cursor = 'auto';
            document.getElementById('undo_' + id).onclick = '';


            document.getElementById('status_' + id).innerHTML = 'Pending';
            document.getElementById('status_' + id).className = 'label label-sm label-primary';
        }
    });

    return false;
}



function saveCustomer()
{
    document.getElementById('loader').style.display = 'block';
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/customersave/popup',
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                window.location = "/merchant/customer/view/" + obj.link;
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
            document.getElementById('loader').style.display = 'none';
        }
    });

    return false;
}

function updateCustomer()
{
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/updatesave/popup',
        data: data,
        success: function (data)
        {
            return false;
        }
    });
    return false;
}

function updatemultiCustomer()
{
    var success = 0;
    document.getElementById('loader').style.display = 'block';
    try {
        $("input.excust[type=checkbox]:checked").each(function () {
            document.getElementById('loader').style.display = 'block';
            document.getElementById("customer_id_").value = $(this).val();
            document.getElementById("customer_code").value = $(this).attr('title');
            updateCustomer();
            success = 1;
        });

    } catch (o)
    {
    }
    if (success === 1)
    {
        window.location = "/merchant/customer/viewlist";
    }
    return false;
}

function deleteCustomer()
{
    var data = '';
    var customer_id = document.getElementById("customer_id_").value;
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/deletecustomer/' + customer_id,
        data: data, success: function (data)
        {
            saveCustomer();
        }
    });
    return false;

}
function display_warning()
{
    try {
        var form = $('#submit_form');
        var error = $('.alert-danger', form);
        if (form.valid() == false) {
            return false;
        } else
        {
            error.hide();
        }
    } catch (o)
    {
        //alert(o.message);
    }

    try {
        document.getElementById('loader').style.display = 'block';
    } catch (o)
    {

    }

    var customer_code = document.getElementById('customer_code').value;
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/isexist/' + customer_code,
        data: data,
        success: function (data)
        {
            try {
                document.getElementById('loader').style.display = 'none';
            } catch (o)
            {

            }
            if (data != 'false')
            {
                obj = JSON.parse(data);
                console.log(obj);
                if (obj.customer_code == 1)
                {
                    document.getElementById("customer_id_").value = obj.customer_id;
                    document.getElementById("warning").style.display = 'block';
                    document.getElementById("ex_message").innerHTML = 'This customer code already exists in your database.';
                    document.getElementById("ex_add").style.display = 'none';
                    document.getElementById("ex_delete").style.display = 'initial';
                    checkedcheckbox = 'checked onclick="return false;"';
                } else
                {
                    document.getElementById("customer_id_").value = obj.customer_id;
                    document.getElementById("warning").style.display = 'block';
                    comma = '';
                    ismob = '';
                    isemail = '';
                    if (obj.mobile == 0)
                    {
                        ismob = 'Mobile Number';
                        comma = ',';
                    }
                    if (obj.email == 0)
                    {
                        isemail = 'Email ID' + comma;
                    }
                    document.getElementById("ex_message").innerHTML = 'This ' + isemail + ' ' + ismob + ' already exists in your customer database. You could either replace this record or create a new entry with same values.<br> Alternatively you can change the data entered from the Customer create screen below.';
                    document.getElementById("ex_delete").style.display = 'none';
                    document.getElementById("ex_add").style.display = 'initial';
                    checkedcheckbox = '';
                }
                var alltr = '';
                $.each(obj.customer_detail, function (index, value) {
                    alltr = alltr + '<tr><td class="td-c">' + value.customer_code + '</td><td class="td-c">' + value.name + '</td><td class="td-c">' + value.email + '</td><td class="td-c">' + value.mobile + '</td><td class="td-c"><input type="checkbox" ' + checkedcheckbox + ' class="excust" name="exist_customer[]" title="' + value.customer_code + '" value="' + value.customer_id + '"></td></tr>';
                });
                document.getElementById("allcusta").innerHTML = alltr;
                return false;
            } else {
                document.getElementById("warning").style.display = 'none';
                saveCustomer();
            }

        }
    });
    return false;

}

function confirmreplace()
{
    count = $("input.excust[type=checkbox]:checked").length;
    if (count > 0)
    {
        $('#confirmm').click();
        document.getElementById("totalchecked").innerHTML = count;
    } else {
        alert('Please select atlist 1 customer to replace');

    }
    return false;
}

function defaultcustomeremail()
{
    var x = document.getElementById("def_email").checked;
    if (x == true)
    {
        document.getElementById('defaultemail').value = 'emailunavailable@swipez.in';
        document.getElementById('defaultemail').readOnly = true;
    } else
    {
        document.getElementById('defaultemail').value = '';
        document.getElementById('defaultemail').readOnly = false;
    }
}

function defaultcustomermobile()
{
    var x = document.getElementById("def_mobile").checked;
    if (x == true)
    {
        document.getElementById('defaultmobile').value = '9999999999';
        document.getElementById('defaultmobile').readOnly = true;
    } else
    {
        document.getElementById('defaultmobile').value = '';
        document.getElementById('defaultmobile').readOnly = false;
    }
}

function save_group()
{
    var data = $("#categoryForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/savegroup',
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                $('#cat_drop').append('<option selected value="' + obj.id + '">' + obj.name + '</option>');
                $('#fil_grp').append('<option selected value="' + obj.id + '">' + obj.name + '</option>');
                document.getElementById("closebutton").click();
            } else
            {
                document.getElementById('errors').innerHTML = obj.error;
                document.getElementById('errors').style.display = 'block';
            }

        }
    });

    return false;
}

function save_customer_group()
{
    document.getElementById('loader').style.display = 'block';
    var data = $("#customergroup").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/savecustomergroup',
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('grp_success').innerHTML = obj.count + ' customers added to this group successfully.';
                document.getElementById('grp_errors').style.display = 'none';
                document.getElementById('grp_success').style.display = 'block';
                $('#customergroup').trigger("reset");
            } else
            {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('grp_errors').innerHTML = obj.error;
                document.getElementById('grp_errors').style.display = 'block';
                document.getElementById('grp_success').style.display = 'none';
            }

        }
    });

    return false;
}

function updatePassword()
{
    var pass = document.getElementById('password_').value;
    if (pass.length > 3)
    {
        var data = '';
        $.ajax({
            type: 'GET',
            url: '/ajax/updatepassword/' + login_user_id + '/' + pass,
            data: data,
            success: function (data)
            {
                try {
                    document.getElementById('password_text').style.display = 'none';
                    document.getElementById('cnfbtn').style.display = 'none';
                    document.getElementById('pass_success').style.display = 'block';
                } catch (o) {
                }
            }
        });
    } else
    {
        alert('Password must be minimum of 4 characters');
    }
}

function password(type, id)
{
    if (type == 1)
    {
        document.getElementById('eye' + id).style.display = 'none';
        document.getElementById('hide' + id).style.display = 'none';
        document.getElementById('show' + id).style.display = 'inline-block';
        document.getElementById('closeeye' + id).style.display = 'inline-block';
    } else
    {
        document.getElementById('eye' + id).style.display = 'inline-block';
        document.getElementById('hide' + id).style.display = 'inline-block';
        document.getElementById('show' + id).style.display = 'none';
        document.getElementById('closeeye' + id).style.display = 'none';
    }
}

function showStateDiv(country_name) {
    if(country_name!='') {
        //$("#defaultmobile-error").html("");
        if(country_name=='India') {
            $('#state_txt').hide();
            $('#state_drpdown').show();
            $('#s2id_state_drpdown').show();
            $("#country_code_txt").text('+91');
            $("#defaultmobile").attr('pattern',"([0-9]{10})");  //^(\+[\d]{1,5}|0)?[1-9]\d{9}$
            $("#defaultmobile").attr('maxlength', "10");
        } else {
            $('#state_drpdown').hide();
            $('#s2id_state_drpdown').hide();
            $('#state_txt').show();
            $("#defaultmobile").attr('pattern', "([0-9]{7,10})");
            $("#defaultmobile").attr('maxlength', "10");
            $.ajax({
                type: 'POST',
                url: '/ajax/getCountryCode',
                data: {
                    'country_name':country_name
                },
                success: function (data)
                {
                    obj = JSON.parse(data);
                    if (obj.status == 1) {
                        $("#country_code_txt").text('+' + obj.country_code);
                    } else {
                        $("#country_code_txt").text('');
                    }
                }
            });
        }
    }
}

