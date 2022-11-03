var is_login = false;
var login_name = '';
var merchant_id = '';
var customer_id = '';
var user_id = '';
var qr_code = '';

function setCookie(cname, cvalue) {
    exdays = 30;
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(redirect) {
    setCookie('last_username', '');
    setCookie('last_password', '');
    var user = getCookie("username");
    if (user != "") {

    } else {
        if (redirect == true) {
            window.location.href = "/loyalty/" + MERCHANT_URL + '/login';
        }
    }
}

function getDetails() {
    checkCookie(true);
    var token = getCookie("token");
    let xhr = new XMLHttpRequest;
    xhr.open('GET', URL + 'v1/loyalty/getpoints/' + token, true)
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.status == 1) {
                document.getElementById("name").innerHTML = getCookie("username");
                document.getElementById("qr_image").src = data.qr_image;
                document.getElementById("points").innerHTML = data.points;
                document.getElementById("point_rs").innerHTML = data.points_rs;
                document.getElementById("whatsapp_btn").setAttribute("href", "whatsapp://send?text=" + data.qr_image);
                document.getElementById("download_btn").setAttribute("href", data.qr_image);
            }
        }
    }
    xhr.send();
    return false;
}

function logincheck() {
    var data = new FormData(document.getElementById("frmlogin"));
    let xhr = new XMLHttpRequest;
    //alert('https://intapi.swipez.inv1/coupon/store/'+swipezMerchantKey+'/'+swipezTKey);
    user_name = document.getElementById("username").value;
    password = document.getElementById("password").value;
    if (user_name != getCookie('last_username') || password != getCookie('last_password')) {
        setCookie('last_username', user_name);
        setCookie('last_password', password);
        xhr.open('POST', URL + 'v1/loyalty/login', true)
        xhr.onload = function () {
            if (this.status === 200) {
                var data = JSON.parse(this.responseText);
                if (data.status == 1) {
                    document.getElementById("error").innerHTML = '';
                    setCookie('username', data.name);
                    setCookie('token', data.token);
                    window.location.href = "/loyalty/" + MERCHANT_URL + '/home';
                } else {
                    document.getElementById("error").innerHTML = data.error;
                }
            }
        }
        xhr.send(data);
    }
    return false;
}

function logout() {
    setCookie('username', '');
    window.location.href = "/loyalty/" + MERCHANT_URL + '/login';
}

function reloadcaptcha() {
    var d = new Date();
    document.getElementById("captcha").src = 'https://h7sak-api.swipez.in/captcha/' + d.getTime();
}


function register() {
    var data = new FormData(document.getElementById("frmlogin"));
    let xhr = new XMLHttpRequest;
    xhr.open('POST', URL + 'v1/loyalty/register', true)
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.status == 1) {
                document.getElementById("error").innerHTML = '';
                setCookie('otp_token', data.token);
                setCookie('otp_mobile', data.mobile);
                window.location.href = "/loyalty/" + MERCHANT_URL + '/otp';
            } else if (data.status == 2) {
                setCookie('otp_token', data.token);
                setCookie('otp_mobile', data.mobile);
                window.location.href = "/loyalty/" + MERCHANT_URL + '/otp';
            } else {
                setCookie('otp_token', '');
                setCookie('otp_mobile', '');
                document.getElementById("error").innerHTML = 'Error while registration';
            }
        }
    }
    xhr.send(data);
    return false;
}



function resendOTP() {
    document.getElementById("resendotp").style.display = 'none';
    document.getElementById("otp-msg").innerHTML = 'OTP sending';
    var data = new FormData(document.getElementById("frmotp"));
    let xhr = new XMLHttpRequest;
    xhr.open('GET', URL + 'v1/loyalty/resendotp/' + getCookie('otp_mobile') + '/' + getCookie('otp_token'), true)
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.status == 1) {
                document.getElementById("otp-msg").innerHTML = 'OTP sent successfully';
                document.getElementById("resendotp").style.display = 'none';
            } else {

            }
        }
    }
    xhr.send(data);
    return false;
}

function submitOTP() {
    var data = new FormData(document.getElementById("frmotp"));
    let xhr = new XMLHttpRequest;
    xhr.open('POST', URL + 'v1/loyalty/otpvalidate', true)
    xhr.onload = function () {
        if (this.status === 200) {
            var data = JSON.parse(this.responseText);
            if (data.status == 1) {
                document.getElementById("error").innerHTML = '';
                setCookie('username', data.name);
                setCookie('token', data.token);
                window.location.href = "/loyalty/" + MERCHANT_URL + '/home';
            } else {
                document.getElementById("error").innerHTML = data.error;
            }
        }
    }
    xhr.send(data);
    return false;
}


function setOTP() {
    document.getElementById("token").value = getCookie('otp_token');
    document.getElementById("mobile").value = getCookie('otp_mobile');
}