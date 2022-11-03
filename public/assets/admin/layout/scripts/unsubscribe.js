var t = 1;
var otp = 1;
function timer(limit) {
    var timeinterval = setInterval(function() {
        limit = limit - 1;
        document.getElementById('timer').innerHTML = 'Resend OTP enable in ' + limit;
        if (limit <= 0) {
            clearInterval(timeinterval);
            $('#vbtn').css('display', 'inline-block');
            document.getElementById('timer').innerHTML = '';
        }
    }, 1000);
}

function sendotp(id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/unsubscribe/sendotp/' + id,
        data: data,
        success: function(data)
        {
            if (data == 1)
            {
                $('#vbtn').css('display', 'none');
                timer(120);
            } else
            {
                $('#vbtn').css('display', 'none');
            }
        }
    });

    return false;
}

function verifyotp(id)
{
    otp = document.getElementById("otp").value;
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/unsubscribe/verifyotp/' + id + '/' + otp,
        data: data,
        success: function(data)
        {
            if (data == '1')
            {
                document.getElementById("verifymobile").value = 1;
                $('#success_otp').css('display', 'block');
                $('#failed_otp').css('display', 'none');
                $('#mobilevverification').css('display', 'none');
            } else
            {
                document.getElementById("verifymobile").value = 0;
                $('#success_otp').css('display', 'none');
                $('#failed_otp').css('display', 'block');
            }
        }
    });

    return false;
}

function ismobileverification(id)
{
    var x = document.getElementById("mbvfn").checked;
    if (x == true)
    {

        if (otp == 1)
        {
            otp = 0;
            sendotp(id);

        }
        $('#mobilevverification').css('display', 'block');

    } else {
        $('#mobilevverification').css('display', 'none');
    }
}

function validateUn()
{
    var x = document.getElementById("mbvfn").checked;
    if (x == true)
    {
        val = document.getElementById("verifymobile").value;
        if (val == 1)
        {
            return true;
        } else
        {
            $('#success_otp').css('display', 'none');
            $('#failed_otp').css('display', 'block');
            return false;
        }
    }
}