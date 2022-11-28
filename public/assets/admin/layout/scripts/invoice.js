var tax_state = 'in';

function savepatron(url)
{
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/patron/register/saved/' + url,
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                $('#submit_form').trigger("reset");
                document.getElementById('register').style.display = 'none';
                document.getElementById('errorshow').style.display = 'none';
                document.getElementById('info').style.display = 'block';

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


function calculatededuct(tax, basic_amount, previous_due)
{
    var sel = document.getElementById("deduct");
    var deductval = sel.options[sel.selectedIndex].value;
    var deducttext = sel.options[sel.selectedIndex].text;
    total_percent = (tax * 100) / basic_amount;

    total_deduct = basic_amount - deductval;
    document.getElementById("deduct_val").innerHTML = Number(deductval).toFixed(2);
    document.getElementById("deduct_label").innerHTML = deducttext;

    document.getElementById("deduct_amount").value = Number(deductval).toFixed(2);
    document.getElementById("deduct_text").value = deducttext;

    //document.getElementById("deducttotal").innerHTML = Number(basic_amount).toFixed(2);

    total_tax = (total_percent * basic_amount) / 100;
    document.getElementById("total_tax").innerHTML = total_tax.toFixed(2);

    if (previous_due > 0)
    {
    } else
    {
        previous_due = 0;
    }
    discount = document.getElementById("coupon_discount").value;
    grand_total = total_deduct + total_tax + previous_due - discount;
    document.getElementById("deductgrandtotal").innerHTML = grand_total.toFixed(2);
    document.getElementById("grandtotal").innerHTML = '<i class="fa fa-inr fa-large"></i> ' + grand_total.toFixed(2) + ' /-';
}

function calculatedeductMerchant(tax, basic_amount, previous_due)
{
    var sel = document.getElementById("deduct");
    var deductval = sel.options[sel.selectedIndex].value;
    var deducttext = sel.options[sel.selectedIndex].text;
    total_percent = (tax * 100) / basic_amount;

    total_deduct = basic_amount - deductval;
    document.getElementById("deduct_amount").value = Number(deductval).toFixed(2);
    document.getElementById("deduct_text").value = deducttext;


    total_tax = (total_percent * basic_amount) / 100;

    if (previous_due > 0)
    {
    } else
    {
        previous_due = 0;
    }
    grand_total = total_deduct + total_tax + previous_due;
    document.getElementById("total").value = grand_total.toFixed(2);
}


function getgrandtotal(grand_total, fee_id)
{
    if (grand_total != '')
    {

    } else
    {
        document.getElementById('conv').innerHTML = '';
        return;
    }
    var data = '';

    $.ajax({
        type: 'POST',
        url: '/patron/paymentrequest/getgrandtotal/' + grand_total + '/' + fee_id,
        data: data,
        success: function (data)
        {
            try {
                document.getElementById('form_gender_error').innerHTML = '';
            } catch (o)
            {

            }

            if (data == 'pgsurcharge')
            {
                document.getElementById('conv').innerHTML = '(Convenience fee of applicable for online payments based on payment mode)';
                grand_total = document.getElementById('amt').value;
                document.getElementById('grandtotal').innerHTML =  grand_total + '/-';
                return;
            }

            try {

                document.getElementById('grandtotal').innerHTML =  data + '/-';
            } catch (o)
            {

            }

            try {
                grand_total = document.getElementById('amt').value;
                conv_fee = Number(data) - Number(grand_total);
                if (conv_fee > 0)
                {
                    document.getElementById('conv').innerHTML = '(Convenience fee <i class="fa  fa-inr"></i> ' + conv_fee.toFixed(2) + '/- applicable for this payment mode)';
                } else
                {
                    document.getElementById('conv').innerHTML = '';
                }
            } catch (o)
            {

            }
        }
    });

}

function getxwaygrandtotal(grand_total, fee_id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/patron/paymentrequest/getxwaygrandtotal/' + grand_total + '/' + fee_id,
        data: data,
        success: function (data)
        {
            document.getElementById('form_gender_error').innerHTML = '';
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + data + '/-';
            grand_total = document.getElementById('amt').value;
            conv_fee = Number(data) - Number(grand_total);
            if (conv_fee > 0)
            {
                document.getElementById('conv').innerHTML = '(Convenience fee <i class="fa  fa-inr"></i>' + conv_fee + '/-)';
            } else
            {
                document.getElementById('conv').innerHTML = '';
            }
        }
    });

}

function calculateSMS(rate, pack)
{
    total = rate * pack;
    tax = total * 18 / 100;
    grand_total = total + tax;
    document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + grand_total.toFixed(2) + '/-';
}

function validateCoupon(merchant_id, total)
{
    var coupon_code = document.getElementById('coupon_code').value;
    coupon_code = coupon_code.split(' ').join('SPACE');
    var coupon_id = '';

    try
    {
        total = Number(total);
        if (coupon_code == '')
        {
            document.getElementById('coupon_status').innerHTML = 'Enter coupon code';
            document.getElementById('coupon_id').value = coupon_id;
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + total.toFixed(2) + '/-';
            return false;
        }
        try {
            var url = document.getElementById('onlinepay').href;
        } catch (o)
        {
        }
        var data = '';
        $.ajax({
            type: 'POST',
            url: '/patron/paymentrequest/couponvalidate/' + merchant_id + '/' + coupon_code,
            data: data,
            success: function (data)
            {
                if (data == 'false')
                {
                    document.getElementById('coupon_status').innerHTML = 'Invalid coupon code';
                    try {
                        document.getElementById('absolute_cost').innerHTML = total.toFixed(2);
                        document.getElementById('discount').innerHTML = '0';
                        document.getElementById('disli').style.display = 'none';
                        document.getElementById('coupon_id').value = '0';
                    } catch (o)
                    {
                    }
                } else
                {
                    obj = JSON.parse(data);
                    if (obj.type == 1)
                    {
                        cost_coupon = Number(obj.fixed_amount);
                    } else
                    {
                        cost_coupon = Number(total * obj.percent / 100);
                    }
                    total = Number(total - cost_coupon);
                    coupon_id = obj.coupon_id;
                    document.getElementById('coupon_status').innerHTML = 'Your coupon discount has been applied<br>' + obj.descreption;
                    document.getElementById('coupon_id').value = coupon_id;
                    try {
                        document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + total.toFixed(2) + '/-';
                    } catch (o)
                    {
                    }
                    try {
                        document.getElementById('onlinepay').href = url + '/coupon';
                    } catch (o)
                    {
                    }
                    try {
                        document.getElementById('absolute_costtt').innerHTML = total.toFixed(2);
                    } catch (o)
                    {
                    }
                    try {
                        document.getElementById('absolute_costt').innerHTML = total.toFixed(2);
                    } catch (o)
                    {
                    }
                    try {
                        document.getElementById('absolute_cost').innerHTML = total.toFixed(2);
                        document.getElementById('discount').innerHTML = cost_coupon.toFixed(2);
                        document.getElementById('disli').style.display = 'block';
                    } catch (o)
                    {
                    }
                    return false;
                }
            }
        });
        try {
            document.getElementById('coupon_id').value = coupon_id;
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + total.toFixed(2) + '/-';
        } catch (o)
        {
        }
        try {
            document.getElementById('absolute_cost').innerHTML = total.toFixed(2);
            document.getElementById('absolute_costt').innerHTML = total.toFixed(2);
        } catch (o)
        {
        }
    } catch (o)
    {
    }
    return false;
}


function validatePackageCoupon(merchant_id)
{
    total = document.getElementById('amount').value;
    var coupon_code = document.getElementById('coupon_code').value;
    coupon_code = coupon_code.split(' ').join('SPACE');
    var coupon_id = '';

    try
    {
        total = Number(total);
        if (coupon_code == '')
        {
            tax = total * 18 / 100;
            grand_total = total + tax;

            document.getElementById('coupon_status').innerHTML = 'Enter coupon code';
            document.getElementById('coupon_id').value = coupon_id;
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + grand_total.toFixed(2) + '/-';
            return false;
        }

        var data = '';
        $.ajax({
            type: 'POST',
            url: '/patron/paymentrequest/couponvalidate/' + merchant_id + '/' + coupon_code,
            data: data,
            success: function (data)
            {
                if (data == 'false')
                {
                    document.getElementById('coupon_status').innerHTML = 'Invalid coupon code';
                    try {
                        tax = total * 18 / 100;
                        grand_total = total + tax;
                        document.getElementById('absolute_cost').innerHTML = grand_total.toFixed(2);
                        document.getElementById('discount').innerHTML = '0';
                        document.getElementById('disli').style.display = 'none';
                        document.getElementById('coupon_id').value = '0';
                    } catch (o)
                    {
                    }
                } else
                {
                    obj = JSON.parse(data);
                    if (obj.type == 1)
                    {
                        cost_coupon = Number(obj.fixed_amount);
                    } else
                    {
                        cost_coupon = Number(total * obj.percent / 100);
                    }
                    total = Number(total - cost_coupon);
                    coupon_id = obj.coupon_id;
                    document.getElementById('coupon_status').innerHTML = 'Your coupon discount has been applied<br>' + obj.descreption;
                    document.getElementById('coupon_id').value = coupon_id;
                    tax = total * 18 / 100;
                    grand_total = total + tax;
                    try {
                        document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + grand_total.toFixed(2) + '/-';
                    } catch (o)
                    {
                    }
                    try {
                        tax = total * 18 / 100;
                        grand_total = total + tax;
                        document.getElementById('absolute_cost').innerHTML = grand_total.toFixed(2);
                        document.getElementById('discount').innerHTML = cost_coupon.toFixed(2);
                        document.getElementById('disli').style.display = 'block';
                        document.getElementById('absolute_costt').innerHTML = grand_total.toFixed(2);
                    } catch (o)
                    {
                    }
                    return false;
                }
            }
        });
        try {
            tax = total * 18 / 100;
            grand_total = total + tax;
            document.getElementById('coupon_id').value = coupon_id;
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + grand_total.toFixed(2) + '/-';
        } catch (o)
        {
        }
        try {
            tax = total * 18 / 100;
            grand_total = total + tax;
            document.getElementById('absolute_cost').innerHTML = grand_total.toFixed(2);
            document.getElementById('absolute_costt').innerHTML = grand_total.toFixed(2);
        } catch (o)
        {
        }
    } catch (o)
    {
    }
    return false;
}

function checkmode() {
    try {
        if ($('input[type=radio]:checked').size() > 0)
        {

        } else
        {
            document.getElementById('form_gender_error').innerHTML = 'Please select payment mode';
        }
    } catch (o) {
    }
}


function changeGSTtext()
{
    try {
        var customergst = document.getElementById('customer_gst').value;
    } catch (o)
    {
        var customergst = '';
    }
    if (customergst.length > 12)
    {
        try {
            var merchantgst = document.getElementById('merchant_gst_number').value;
            if (merchantgst.substring(0, 2) == customergst.substring(0, 2)) {
                var taxtext = document.getElementById('instate_label').value;
                tax_state = 'in';
            } else
            {
                var taxtext = document.getElementById('outstate_label').value;
                tax_state = 'out';
            }
            document.getElementById('gsttext').setAttribute("data-content", taxtext);
        } catch (o)
        {

        }
        try {
            document.getElementById('pan_number').value = customergst.substring(2, 12);
        } catch (o)
        {

        }
    }
}

function setStateCode()
{
    try {
        var statecode = $("#state option:selected").text();
        document.getElementById('state_code').value = statecode.substring(0, 2);
    } catch (o)
    {

    }
}

function existCustomerCode(label)
{
    var data = $("#frm_customer_code").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/getCustomerDetail',
        data: data,
        success: function (data)
        {
            var extra = '';
            password_validation = document.getElementById('password_validation').value;
            if (password_validation == 1)
            {
                var extra = ' and Password';
            }
            if (data == 'false')
            {
                document.getElementById('cust_code_status').innerHTML = 'Please enter valid ' + label + extra;
            } else
            {
                document.getElementById('cust_code_status').innerHTML = '';
                obj = JSON.parse(data);
                document.getElementById('customer_code').value = obj.customer_code;
                document.getElementById("customer_code").readOnly = true;
                document.getElementById('name').value = obj.name;
                document.getElementById('email').value = obj.email;
                document.getElementById('mobile').value = obj.mobile;
                document.getElementById('city').value = obj.city;
                document.getElementById('state').value = obj.state;
                document.getElementById('zipcode').value = obj.zipcode;
                document.getElementById('address').innerHTML = obj.address;
                document.getElementById('customer_form').style.display = 'none';
                document.getElementById('payment_form').style.display = 'block';
            }

        }
    });

    return false;
}

function validateDriver()
{
    var token = document.getElementById('token').value;
    if (token == '')
    {
        document.getElementById('loader').style.display = 'block';

        var data = $("#frm_form_builder").serialize();
        $.ajax({
            type: 'POST',
            url: '/ajax/validatedriverdetails',
            data: data,
            success: function (data)
            {
                obj = JSON.parse(data);
                if (obj.isVerified == true)
                {
                    document.getElementById('error_msg').style.display = 'none';
                    document.getElementById('token').value = obj.token;
                    $('#frm_form_builder').submit();
                    return true;
                } else
                {
                    $("html, body").animate({scrollTop: 0}, "slow");
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('error_msg').style.display = 'block';
                    document.getElementById('error_msg').innerHTML = 'Driver details does not exist';
                    return false;
                }

            }
        });
        return false;
    }
}

function calculateGST(amount)
{
    total_tax = document.getElementById(tax_state + 'state_total_tax').value;
    tamt = Number(amount * total_tax / 100);
    try {
        document.getElementById('gst').value = tamt;
    } catch (o)
    {
    }
    return amount + tamt;
}

function validateFormCoupon(merchant_id)
{
    total = document.getElementById('base_amount').value;
    var coupon_code = document.getElementById('coupon_code').value;
    coupon_code = coupon_code.split(' ').join('SPACE');
    var coupon_id = '';
    try
    {
        total = Number(total);
        if (coupon_code == '')
        {
            try {
                document.getElementById('base_amt').value = total;
            } catch (o)
            {
            }
            total = calculateGST(total);
            document.getElementById('g_total').innerHTML = total.toFixed(2);
            document.getElementById('grand_total').value = total.toFixed(2);
            document.getElementById('coupon_status').innerHTML = 'Enter coupon code';
            document.getElementById('coupon_id').value = coupon_id;
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + total.toFixed(2) + '/-';
            return false;
        }

        var data = '';
        $.ajax({
            type: 'POST',
            url: '/patron/paymentrequest/couponvalidate/' + merchant_id + '/' + coupon_code,
            data: data,
            success: function (data)
            {
                if (data == 'false')
                {
                    document.getElementById('coupon_status').innerHTML = 'Invalid coupon code';
                    try {
                        try {
                            document.getElementById('base_amt').value = total;
                        } catch (o)
                        {
                        }
                        total = calculateGST(total);
                        document.getElementById('g_total').innerHTML = total.toFixed(2);
                        document.getElementById('grand_total').value = total.toFixed(2);
                        document.getElementById('discount_amount').value = '0';
                        document.getElementById('coupon_id').value = '0';
                    } catch (o)
                    {
                    }
                } else
                {
                    obj = JSON.parse(data);
                    if (obj.type == 1)
                    {
                        cost_coupon = Number(obj.fixed_amount);
                    } else
                    {
                        cost_coupon = Number(total * obj.percent / 100);
                    }
                    total = Number(total - cost_coupon);
                    coupon_id = obj.coupon_id;
                    document.getElementById('coupon_status').innerHTML = 'Your coupon discount has been applied ' + obj.descreption;
                    document.getElementById('coupon_id').value = coupon_id;
                    try {
                        document.getElementById('base_amt').value = total;
                    } catch (o)
                    {
                    }
                    total = calculateGST(total);
                    try {
                        document.getElementById('g_total').innerHTML = total.toFixed(2);
                        document.getElementById('grand_total').value = total.toFixed(2);
                        document.getElementById('discount_amount').value = cost_coupon.toFixed(2);
                        if (total == 0)
                        {
                            $('.md-radiobtn').removeAttr('required');
                            $('#tnc_check').removeAttr('required');
                            $("input:radio[name=payment_mode]:first").attr('checked', true);
                            document.getElementById('radio-div').style.display = 'none';
                            document.getElementById('tnc-div').style.display = 'none';

                        }
                    } catch (o)
                    {
                    }
                    return false;
                }
            }
        });

    } catch (o)
    {
    }
    return false;
}


function showDocument(type)
{
    if (type == 1)
    {
        document.getElementById('document_div').style.display = 'block';
    } else
    {
        document.getElementById('document_div').style.display = 'none';
    }
}

function notifyPatron(id) {

    if ($('#' + id).is(':checked')) {
        document.getElementById('is_' + id).value = '1';
        try {
            document.getElementById('subbtn').value = 'Save & Send';
        } catch (o) {
        }
    } else {
        document.getElementById('is_' + id).value = '0';
        try {
            document.getElementById('subbtn').value = 'Save';
        } catch (o) {
        }
    }
}