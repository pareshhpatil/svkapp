var status = 1;
var URL = '';
var captcha_id = '';
var login_new = 0;
function sameaddress() {
    if (status == 2) {
        document.getElementById("current_address").value = '';
        document.getElementById("current_city").value = '';
        document.getElementById("current_zip").value = '';
        document.getElementById("current_state").value = '';
        document.getElementById("current_country").value = '';
        status = 1;
    } else {
        document.getElementById("current_city").value = document.getElementById("city").value;
        document.getElementById("current_zip").value = document.getElementById("zip").value;
        document.getElementById("current_state").value = document.getElementById("state").value;
        document.getElementById("current_country").value = document.getElementById("country").value;
        document.getElementById("current_address").value = document.getElementById("address").value;
        status = 2;
    }
}

function GSTAvailable(val) {
    if (val == 1) {
        document.getElementById('gst_div').style.display = 'block';
        document.getElementById('cdiv').style.display = 'none';
        document.getElementById('submit-div').style.display = 'none';
        document.getElementById('gst_number').setAttribute("required", "true");
    } else {
        document.getElementById('gst_div').style.display = 'none';
        document.getElementById('cdiv').style.display = 'block';
        document.getElementById('submit-div').style.display = 'block';
        document.getElementById('gst_number').required = false;
        document.getElementById('company_name').value = document.getElementById('exist_company').value;
        document.getElementById("pan").value = '';
    }

}

// if user wantss to verify bank details then this function will put required = true to all mandatory fields.
function paymentOnline(val) {
    if (val == 1) {
        document.getElementById('bdet').style.display = 'block';
        document.getElementById('non_op_doc').style.display = 'none';
        document.getElementById('op_doc').style.display = 'block';
       // document.getElementById('kycdiv').style.display = 'table-cell';
        document.getElementById('iop_li').style.display = 'table-cell';
        document.getElementById('op_li').style.display = 'table-cell';
        //document.getElementById('s-btn').value = 'Verify your bank account';
    } else {
        document.getElementById('bdet').style.display = 'none';
        //document.getElementById('kycdiv').style.display = 'none';
        document.getElementById('non_op_doc').style.display = 'block';
        document.getElementById('op_doc').style.display = 'none';
        document.getElementById('iop_li').style.display = 'none';
        document.getElementById('op_li').style.display = 'none';
       // document.getElementById('s-btn').value = 'Save';

    }
    bankDetailRequired(val);
    removeBankNotVerified();
}

function bankDetailRequired(val) {
    document.getElementById('reqf1').setAttribute("required", val);
    document.getElementById('reqf2').setAttribute("required", val);
    document.getElementById('reqf3').setAttribute("required", val);
    document.getElementById('reqf4').setAttribute("required", val);
    document.getElementById('reqf5').setAttribute("required", val);
}

// if user wants to save partial bank details then this function will remove required attribute from the fields.
function bankDetailNotRequired() {
    document.getElementById('reqf1').removeAttribute("required");
    document.getElementById('reqf2').removeAttribute("required");
    document.getElementById('reqf3').removeAttribute("required");
    document.getElementById('reqf4').removeAttribute("required");
    document.getElementById('reqf5').removeAttribute("required");
    bankNotVerified();
}

function bankNotVerified() {
    sessionStorage.setItem('verified', 'false');
}

window.onload = function () {
    if (sessionStorage.verified) {
        document.getElementById('bankVerification').style.display = "block";
    }
};

function removeBankNotVerified() {
    sessionStorage.removeItem('verified');
}

function gstValidate() {
    var gst = document.getElementById('gst_number').value;
    var data = '';
    if (gst.length == 15) {
        $.ajax({
            type: 'POST',
            url: '/ajax/gstinfo/' + gst,
            data: data,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.status == 0) {
                    document.getElementById('error').style.display = 'block';
                    document.getElementById('submit-div').style.display = 'none';
                    document.getElementById('cdiv').style.display = 'none';
                    document.getElementById('company_name').value = document.getElementById('exist_company').value;
                    document.getElementById("pan").value = '';
                } else {
                    if (obj.status == 2) {
                        document.getElementById('error').innerHTML = 'Unable to fetch your GST information. Please proceed with your registration by filling the below form.';
                        document.getElementById('error').style.display = 'block';
                        document.getElementById('cdiv').style.display = 'block';
                        document.getElementById('submit-div').style.display = 'block';
                    } else {
                        document.getElementById('error').style.display = 'none';
                        document.getElementById("company_name").value = obj.company_name;
                        document.getElementById("city").value = obj.city;
                        document.getElementById("state").value = obj.state;
                        document.getElementById("pan").value = obj.pan;
                        document.getElementById("first_name").value = obj.first_name;
                        document.getElementById("last_name").value = obj.last_name;
                        $('#entity_type').val(obj.entity_type);
                        document.getElementById("zipcode").value = obj.zipcode;

                        document.getElementById('cdiv').style.display = 'block';
                        document.getElementById('submit-div').style.display = 'block';
                        document.getElementById("validated_gst_number").value = gst;
                        try {
                            document.getElementById("address").value = obj.address;
                        } catch (o) {

                        }
                        try {
                            document.getElementById("addresstext").innerHTML = obj.address;
                        } catch (o) {

                        }
                    }
                }
                return false;
            }

        });
    }
    return false;
}

function gstCheck(gst_number) {
    validated_gst_number = document.getElementById("validated_gst_number").value;
    if (validated_gst_number != '' && gst_number != validated_gst_number) {
        document.getElementById('submit-div').style.display = 'none';
        document.getElementById('cdiv').style.display = 'none';
        document.getElementById('company_name').value = document.getElementById('exist_company').value;
        document.getElementById("pan").value = '';
    }
}


function validateotp() {
    var otp = document.getElementById('otp').value;
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/login/validateotp/' + otp,
        data: data,
        success: function (data) {
            if (data == 1) {
                document.getElementById('link').click();
            } else {
                document.getElementById('error').style.display = 'block';
            }
            return false;
        }

    });
    return false;
}

function submitOTP() {
    var data = new FormData(document.getElementById("frmotp"));
    let xhr = new XMLHttpRequest;
    xhr.open('POST', URL + '/api/v1/loyalty/otpvalidate', true)
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.status == 1) {
                document.getElementById("error").innerHTML = '';
                window.location.href = "/m/" + short_url + "/login";
            } else {
                document.getElementById("error").innerHTML = data.error;
            }
        }
    }
    xhr.send(data);
    return false;
}

function sendDemorequest() {
    document.getElementById('loader').style.display = 'block';
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/home/demorequest',
        data: data,
        success: function (data) {
            if (data == 'success') {
                document.getElementById('demosent').style.display = 'block';
                document.getElementById('form_demo').style.display = 'none';
            } else {
                document.getElementById('errordiv').innerHTML = '* ' + data;
            }
            document.getElementById('loader').style.display = 'none';
        }
    });
    return false;
}

function validateRegisterOTP(form) {
    try {
        document.getElementById(form + '-btn').disabled = true;
        document.getElementById(form + '-btn').innerHTML = 'Loading';
        var requestType = document.getElementById('exist_type');
        if (typeof (requestType) == 'undefined' || requestType == null) {
            requestType = '1'
        } else {
            requestType = requestType.value
        }
        var data = $("#" + form + "-form").serialize();
        $.ajax({
            type: 'POST',
            url: '/merchant/register/validateotp',
            data: data,
            success: function (data) {
                document.getElementById(form + '-btn').disabled = false;
                obj = JSON.parse(data);
                if (obj.status == 1) {
                    window.location.href = "/merchant/getting-started";
                }
                else if (obj.status == 2) {
                    window.location.href = obj.redirect_link;
                }
                else if (obj.status == 3) {
                    document.getElementById('otpSentSuccessMessage').style.display = 'block';
                    document.getElementById('otpSentSuccessMessage').innerHTML = 'OTP Verified';
                    document.getElementById('submitbutton').disabled = true;
                    document.getElementById("submitbutton").classList.add("opacity-50");
                    document.getElementById('backbutton').disabled = true;
                    document.getElementById("backbutton").classList.add("opacity-50");
                    document.getElementById('web-otp').disabled = true;
                    document.getElementById("web-otp").classList.add("opacity-50");
                    document.getElementById("setupAccount").disabled = false;
                    document.getElementById("setupAccount").classList.remove("opacity-50");

                    document.getElementById(form + '-error').style.display = 'none';
                }
                else {
                    if (obj.type == 3) {
                        document.getElementById(form + '-btn').innerHTML = 'Send OTP';
                        document.getElementById(form + '-error').style.display = 'block';
                        document.getElementById(form + '-error').innerHTML = '* ' + obj.error;
                        captchaSet();
                    }else{
                        document.getElementById(form + '-btn').innerHTML = 'Validate';
                        document.getElementById(form + '-error').style.display = 'block';
                        document.getElementById(form + '-error').innerHTML = '* ' + obj.error;
                        captchaSet();
                    }
                }
            },
            error: function (request, status, error) {
                if (requestType == 3) {
                    document.getElementById(form + '-btn').innerHTML = 'Send OTP';
                    document.getElementById(form + '-btn').disabled = false;
                    document.getElementById(form + '-error').style.display = 'block';
                    document.getElementById(form + '-error').innerHTML = ' ';
                    document.getElementById('submitbutton').disabled = true;
                    document.getElementById("submitbutton").classList.add("opacity-50");
                    document.getElementById('backbutton').disabled = true;
                    document.getElementById("backbutton").classList.add("opacity-50");
                    var timeleft = 60;
                    var downloadTimer = setInterval(function () {
                        if (timeleft <= 0) {
                            clearInterval(downloadTimer);
                            document.getElementById('submitbutton').innerHTML = 'Verify OTP';
                            document.getElementById('submitbutton').disabled = false;
                            document.getElementById("submitbutton").classList.remove("opacity-50");
                            document.getElementById('backbutton').disabled = false;
                            document.getElementById("backbutton").classList.remove("opacity-50");
                        } else {
                            document.getElementById('submitbutton').innerHTML = 'Wait (' + (timeleft) + ')';
                        }
                        timeleft--;
                    }, 1000);
                    captchaSet();
                } else {
                    document.getElementById(form + '-btn').disabled = false;
                    error = error + '. Please try after some time';
                    document.getElementById(form + '-btn').innerHTML = 'Validate';
                    document.getElementById(form + '-error').style.display = 'block';
                    document.getElementById(form + '-error').innerHTML = '* ' + error;
                    captchaSet();
                }

            }
        });

    } catch (o) {
        alert(o.message);
    }
    return false;
}
function productRegister(form) {
    try {
        document.getElementById(form + '-btn').innerHTML = 'Loading';
        document.getElementById(form + '-btn').disabled = true;
        var requestType = document.getElementById('exist_type');
        if (typeof (requestType) == 'undefined' || requestType == null) {
            requestType = '1'
        } else {
            requestType = requestType.value
        }
        var data = $("#" + form + "-form").serialize();
        $.ajax({
            type: 'POST',
            url: '/merchant/register/validate',
            data: data,
            success: function (data) {
                obj = JSON.parse(data);
                document.getElementById(form + '-btn').disabled = false;
                if (obj.message == 'success') {
                    document.getElementById(form + '-error').style.display = 'none';
                    if (obj.account_type == 2) {
                        document.getElementById(form + '-form').setAttribute("action", "/login");
                        document.getElementById(form + '-form').setAttribute("onsubmit", "");
                        document.getElementById(form + '-username').setAttribute("name", "email_id");
                        document.getElementById(form + '-password').style.display = 'block';
                        document.getElementById('forgot_p').style.display = 'block';
                        document.getElementById(form + '-btn').innerHTML = 'Login';
                    } else {
                        if (obj.type == '3') {
                            document.getElementById(form + '-otp-container').style.display = 'block';
                            document.getElementById(form + '-form').action = "/merchant/registersave";
                            document.getElementById(form + '-otp').style.display = 'block';
                            document.getElementById(form + '-btn').innerHTML = 'Send OTP';
                            document.getElementById(form + '-username').style.display = 'none';
                            try {
                                document.getElementById('mobile-enter').style.display = 'none';
                            } catch (o) {

                            }
                            try {
                                document.getElementById('email-enter').style.display = 'none';
                            } catch (o) {

                            }
                        } else {
                            document.getElementById(form + '-btn').innerHTML = 'Validate';
                            document.getElementById(form + '-username').style.display = 'none';
                            document.getElementById(form + '-form').setAttribute("onsubmit", "return validateRegisterOTP('" + form + "')");
                            document.getElementById(form + '-otp').style.display = 'block';
                        }
                    }
                    captchaSet();
                } else {
                    if (obj.type == '3') {
                        document.getElementById(form + '-error').style.display = 'block';
                        document.getElementById(form + '-error').innerHTML = '* ' + obj.message;
                        document.getElementById(form + '-btn').innerHTML = 'Send OTP';
                    } else {
                        document.getElementById(form + '-btn').innerHTML = 'Get started for free';
                        document.getElementById(form + '-error').style.display = 'block';
                        document.getElementById(form + '-error').innerHTML = '* ' + obj.message;
                    }
                    captchaSet();
                }
            },
            error: function (request, status, error) {
                document.getElementById(form + '-btn').disabled = false;
                if (requestType == 3) {
                    document.getElementById(form + '-btn').classList.add("opacity-50");
                    document.getElementById(form + '-btn').disabled = true;
                    var timeleft = 90;
                    var downloadTimer = setInterval(function () {
                        if (timeleft <= 0) {
                            clearInterval(downloadTimer);
                            document.getElementById(form + '-btn').innerHTML = 'Send OTP';
                            document.getElementById(form + '-btn').classList.remove("opacity-50");
                            document.getElementById(form + '-btn').disabled = false;
                        } else {
                            document.getElementById(form + '-btn').innerHTML = 'Wait (' + (timeleft) + ')';
                        }
                        timeleft--;
                    }, 1000);
                    document.getElementById(form + '-error').style.display = 'block';
                    document.getElementById(form + '-error').innerHTML = '';
                    captchaSet();
                } else {
                    data = 'Please try after some time';
                    document.getElementById(form + '-btn').innerHTML = 'Get started for free';
                    document.getElementById(form + '-error').style.display = 'block';
                    document.getElementById(form + '-error').innerHTML = '*' + data;
                    captchaSet();
                }

            }
        });
    } catch (o) {
        // alert(o.message);
    }
    return false;
}

function captchaSet() {
    try {
        grecaptcha.execute(captcha_id, { action: 'homepage' }).then(function (token) {
            try {
                document.getElementById('captchaweb').value = token;
                document.getElementById('captchamob').value = token;
            } catch (o) {
            }
        });
    } catch (o) {

    }
}

function keyChange(evt, int) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode > 95 && charCode < 106) || (charCode > 48 && charCode < 59)) {
        id = Number(int) + 1;
        var val = document.getElementById('key' + int).value;
        if (val.length == 4) {
            document.getElementById("key" + id).focus();
        }
    }

}

function validatemax(evt, int) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    //alert(charCode);
    if ((charCode > 95 && charCode < 106) || (charCode > 48 && charCode < 59)) {
        var val = document.getElementById('key' + int).value;
        if (val.length > 3) {
            return false;
        }
    }

}
function getcouponcode(id, url, desc, title, img) {
    document.getElementById('code').innerHTML = 'Loading..';
    document.getElementById('couponimg').src = img;
    document.getElementById('title').innerHTML = title;
    document.getElementById('desc').innerHTML = desc;
    document.getElementById('linkk').href = url;
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/ajax/getcouponcode/' + id,
        data: data,
        success: function (data) {
            document.getElementById('code').innerHTML = data;
            return false;
        }

    });
    return false;
}



function languageChange(lg) {
    if (lg == 'English') {
        document.getElementById('lbname').innerHTML = 'Name <span class="required">* </span>';
        document.getElementById('lbnamexs').innerHTML = 'Name <span class="required">* </span>';
        document.getElementById('lbemail').innerHTML = 'Email <span class="required">* </span>';
        document.getElementById('lbemailxs').innerHTML = 'Email <span class="required">* </span>';
        document.getElementById('lbmobile').innerHTML = 'Mobile <span class="required">* </span>';
        document.getElementById('lbmobilexs').innerHTML = 'Mobile <span class="required">* </span>';
        document.getElementById('lbamount').innerHTML = 'Amount <span class="required">* </span>';
        document.getElementById('lbamountxs').innerHTML = 'Amount <span class="required">* </span>';
    } else {
        document.getElementById('lbname').innerHTML = 'नाम  <span class="required">* </span>';
        document.getElementById('lbnamexs').innerHTML = 'नाम  <span class="required">* </span>';
        document.getElementById('lbemail').innerHTML = 'ईमेल  <span class="required">* </span>';
        document.getElementById('lbemailxs').innerHTML = 'ईमेल  <span class="required">* </span>';
        document.getElementById('lbmobile').innerHTML = 'मोबाइल  <span class="required">* </span>';
        document.getElementById('lbmobilexs').innerHTML = 'मोबाइल  <span class="required">* </span>';
        document.getElementById('lbamount').innerHTML = 'राशि <span class="required">* </span>';
        document.getElementById('lbamountxs').innerHTML = 'राशि <span class="required">* </span>';
    }
}

function changeHispanicCourse(val) {
    var data = document.getElementById('course_list').innerHTML;
    obj = JSON.parse(data);
    arr = obj[val];
    document.getElementById('base_amount').value = arr.base_amount;
    document.getElementById('base_amt').value = arr.base_amount;
    document.getElementById('gst').value = arr.gst;
    document.getElementById('g_total').innerHTML = arr.total;
    document.getElementById('grand_total').value = arr.total;
}

function validateGoogleToken(token) {
    if (token != '') {
        service_id = document.getElementById('service_id').value;
        //document.getElementById('web' + '-formdiv').style.display = 'none';
        //document.getElementById('web' + '-error').style.display = 'none';
        //document.getElementById('web-start_now').setAttribute("href", "/googlelogintoken/" + token + '/' + service_id);
        //document.getElementById('web' + '-start_now').style.display = 'inline-flex';
        //document.getElementById('mob' + '-formdiv').style.display = 'none';
        //document.getElementById('mob' + '-error').style.display = 'none';
        //document.getElementById('mob-start_now').setAttribute("href", "/googlelogintoken/" + token + '/' + service_id);
        //document.getElementById('mob' + '-start_now').style.display = 'inline-flex';
        window.location.href = "/googlelogintoken/" + token + '/' + service_id;

    }
}

function logincheck() {
    document.getElementById('login-btn').disabled = true;
    document.getElementById('login-btn').innerHTML = 'Loading';
    var data = $("#login-form").serialize();
    $.ajax({
        type: 'POST',
        url: '/login/logincheck',
        data: data,
        success: function (data) {
            if (data == 'success') {
                window.location.href = "/merchant/dashboard/home";
            } else {
                document.getElementById('login-btn').disabled = false;
                document.getElementById('login-btn').innerHTML = 'Login';
                document.getElementById('login-error').style.display = 'block';
                document.getElementById('login-error').innerHTML = '* ' + data;
                captchaSet();
            }
        }
    });


    return false;
}

function setValue(val) {
    var data = document.getElementById('custom_json').innerHTML;
    obj = JSON.parse(data);
    arr = obj[val];
    document.getElementById('data_value').value = arr;
}

function setBusinessProfile() {
    var gst = document.getElementById('customer_gst').value;
    var code = gst.substring(0, 2);
    setValue(code);
}


function limit(element) {
    var max_chars = 4;

    if (element.value.length > max_chars) {
        element.value = element.value.substr(0, max_chars);
    }
}

function hideMobileDiv(type) {
    document.getElementById('otpErrorMessage').style.display = 'none';
    var username = document.getElementById(type).value;

    if (type == 'phone_number') {
        if (username.length == 10 && username.match(/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/)) {
            document.getElementById("web-username").value = username;
            document.getElementById("web-otp").value = '';
            productRegister('web')
        } else {
            document.getElementById('web-error').style.display = 'block';
            document.getElementById('web-error').innerHTML = 'Please enter a valid mobile number';
        }
    } else {
        var regex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
        if (regex.test(username)) {
            document.getElementById("web-username").value = username;
            document.getElementById("web-otp").value = '';
            productRegister('web')
        } else {
            document.getElementById('web-error').style.display = 'block';
            document.getElementById('web-error').innerHTML = 'Please enter correct email';
        }
    }


}

function backButtonClicked() {
    try {
        document.getElementById("mobile-enter").style.display = "block";

    } catch (o) {
        try {
            document.getElementById("email-enter").style.display = "block";
        } catch (o2) {

        }
    }

    document.getElementById("web-otp-container").style.display = "none";
    document.getElementById("web-error").style.display = "none";
}

function enableSubmit() {
    var otp = document.getElementById("web-otp").value;
    document.getElementById("otpErrorMessage").innerText = '';
    if (otp.length == 4) {
        document.getElementById("exist_type").value = "3";
        validateRegisterOTP('web')
    } else {
        document.getElementById('otpErrorMessage').style.display = 'block';
        document.getElementById("otpSentSuccessMessage").innerText = '';
        document.getElementById("otpErrorMessage").appendChild(document.createTextNode("OTP should be 4 digits"));
    }

}