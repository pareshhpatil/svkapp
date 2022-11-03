function getshortlink() {
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/merchant/directpaylink/shortlink',
        data: data,
        success: function (data) {
            try {
                document.getElementById('shortlink').value = data;
                document.getElementById('shortlinkdiv').innerHTML = '<abc2>' + data + '</abc2>';
                document.getElementById('c_link').style.display = 'none';
                document.getElementById('cp_link').style.display = 'inline-block';
            } catch (o) {
            }


        }
    });


}



function validateCoupon() {
    var start_date = document.getElementById('start_date').value;
    var end_date = document.getElementById('end_date').value;
    if (new Date(end_date).getTime() < new Date(start_date).getTime()) {
        document.getElementById('error_div').style.display = 'block';
        document.getElementById('start_date-er').style.display = 'block';
        return false;
    } else {
        document.getElementById('error_div').style.display = 'none';
        document.getElementById('start_date-er').style.display = 'none';
        return true;
    }
    return false;
}

function showfranchiselogin(check) {
    if (check == true) {
        document.getElementById('logindiv').style.display = 'block';
        $('#login_email').prop('required', true);
        $('#submit_form_password').prop('required', true);
        document.getElementById('login_email').value = document.getElementById('f_email').value;
    } else {
        document.getElementById('logindiv').style.display = 'none';
        $('#login_email').prop('required', false);
        $('#submit_form_password').prop('required', false);
    }
}

function changeTransferMode(check) {
    if (check == true) {
        document.getElementById('offline').style.display = 'none';
        document.getElementById('online').style.display = 'block';
    } else {
        document.getElementById('offline').style.display = 'block';
        document.getElementById('online').style.display = 'none';
    }
}

function changeTransferType(check) {
    if (check == true) {
        document.getElementById('vendor1').style.display = 'none';
        document.getElementById('vendor2').style.display = 'none';
        document.getElementById('franchise1').style.display = 'block';
        document.getElementById('franchise2').style.display = 'block';
        document.getElementById('type1').value = '2';
        document.getElementById('type2').value = '2';
        $('#franchise_id1').prop('required', true);
        $('#franchise_id2').prop('required', true);
        $('#vendor_id1').prop('required', false);
        $('#vendor_id2').prop('required', false);
    } else {
        document.getElementById('vendor1').style.display = 'block';
        document.getElementById('vendor2').style.display = 'block';
        document.getElementById('franchise1').style.display = 'none';
        document.getElementById('franchise2').style.display = 'none';
        document.getElementById('type1').value = '1';
        document.getElementById('type2').value = '1';
        $('#franchise_id1').prop('required', false);
        $('#franchise_id2').prop('required', false);
        $('#vendor_id1').prop('required', true);
        $('#vendor_id2').prop('required', true);
    }
}

function vendorOnlineSettlement(check) {
    if (check == true) {
        document.getElementById('onlinediv').style.display = 'block';

    } else {
        document.getElementById('onlinediv').style.display = 'none';
    }
    $('#acc_name').prop('required', check);
    $('#acc_no').prop('required', check);
    $('#bank_name').prop('required', check);
    $('#acc_type').prop('required', check);
    $('#ifsc_code').prop('required', check);
    $('#f_email').prop('required', check);
}
function vendorLoginCheck(check) {
    if (check == true) {
        document.getElementById('logindiv').style.display = 'block';

    } else {
        document.getElementById('logindiv').style.display = 'none';
    }
}

function vendorSettlementType(check) {
    if (check == 1) {
        document.getElementById('commision_div').style.display = 'list-item';
        document.getElementById('commision_per_div').style.display = 'list-item';
        document.getElementById('commision_amt_div').style.display = 'none';
    } else if (check == 2) {
        document.getElementById('commision_div').style.display = 'list-item';
        document.getElementById('commision_per_div').style.display = 'none';
        document.getElementById('commision_amt_div').style.display = 'list-item';
    } else {
        document.getElementById('commision_div').style.display = 'none';
    }
}

function saveRole() {
    var data = $("#roleForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/subuser/saverole/popup',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                var x = document.getElementById("role");
                var option = document.createElement("option");
                option.text = obj.name;
                option.value = obj.id;
                option.selected = 1;
                x.add(option);
                $('#roleForm').trigger("reset");
                $("#closebutton").click();
            } else {
                var error = '';
                try {
                    $.each(obj['error'], function (index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('errorshow').style.display = 'block';
                    document.getElementById('error_display').innerHTML = error;
                } catch (o) {
                    alert(o.message);
                }
            }

        }
    });
    return false;
}

function saveVendorSequence() {
    var data = $("#seq_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/saveExpensesequence/7',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                document.getElementById('seq_id').value = obj.id;
                if (obj.auto_generate == 1) {
                    document.getElementById("vendor_code_auto_generate").readOnly = true;
                    document.getElementById("vendor_code_auto_generate").value = 'Auto generate';
                } else {
                    document.getElementById("vendor_code_auto_generate").readOnly = false;
                    document.getElementById("vendor_code_auto_generate").value = '';
                }
            }
            $("#closebutton").click();
        }
    });

    return false;
}

