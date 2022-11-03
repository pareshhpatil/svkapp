var count = 0;
var pcount = 0;
function hidenotification(str) {
    var divv = 'div' + str;

    var elem = document.getElementById(divv);
    elem.parentElement.removeChild(elem);
    //document.getElementById(divv).remove();

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {

    }
    xmlhttp.open("GET", "/merchant/dashboard/update/" + str, true);
    xmlhttp.send();

}

function remindmelater()
{
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.open("GET", "/merchant/dashboard/remindmelater", true);
    xmlhttp.send();
}

function validatefilesize(maxsize, id) {
    var x = document.getElementById(id);
    var txt = "";
    if (maxsize == 500000)
    {
        max = '500 KB';
    } else
    {
        max = '1 MB';
    }
    if ('files' in x) {

        if (x.files.length == 0) {
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if (file.size > maxsize)
                {
                    alert('File size should be less than ' + max);
                    try {
                        document.getElementById(id).value = "";
                    } catch (o)
                    {
                    }
                    return false;
                }
            }
        }
    }

}

function getStarted()
{
    $('#getstarted').show();
    $('#dashboard').hide();
}

function remove(id)
{
    var ab = 'exist' + id;
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
}

function addMorePanCard()
{
    var id = Date.now();
    var mainDiv = document.getElementById('addpan');
    var newSpan = document.createElement('div');
    newSpan.setAttribute('id', 'exist' + id);
    newSpan.setAttribute('class', 'form-group col-md-12');
    newSpan.innerHTML = '<label class="control-label col-md-5">Partners pan card</label><div class="col-md-5"><input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000,' + "'gst_cer" + id + "'" + ');" id="gst_cer' + id + '" name="partner_pan_card[]" ><span class="help-block red">* Max file size 1 MB</span></div><div class="col-md-1"><a onclick="remove(' + id + ');" class="btn btn-sm red">Remove</a></div>';
    mainDiv.appendChild(newSpan);
    count = count + 1;
}

function gstavailable()
{
    if (document.getElementById("gst").checked == true) {
        try {
            document.getElementById('gstdiv').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('reqspan').style.display = 'none';
        } catch (o) {
        }


    } else
    {
        try {
            document.getElementById('gstdiv').style.display = 'none';
        } catch (o) {
        }

        try {
            document.getElementById('reqspan').style.display = 'inline';
        } catch (o) {
        }

    }

}

function validateConfirm(val, type)
{
    $('#reqf1').prop('required', val);
    $('#reqf2').prop('required', val);
    $('#reqf3').prop('required', val);
    $('#reqf4').prop('required', val);
    $('#confirm').prop('required', val);
    if (type == 1)
    {
        var data = $("#submit_form1").serialize();
        $.ajax({
            type: 'POST',
            url: '/merchant/profile/validateuploadform',
            data: data,
            success: function (data)
            {
                obj = JSON.parse(data);
                if (obj.status == 1)
                {
                    document.getElementById('loader').style.display = 'block';
                    document.getElementById('submit_type').value = 'submit_document';
                    document.getElementById("submit_form1").submit();
                } else
                {
                   if(obj.error!='')
                   {
                       document.getElementById('error').innerHTML=obj.error;
                   }
                    document.getElementById('error').style.display = 'block';
                    window.scrollTo(0, 0);
                    return false;
                }

            }
        });
        return false;
    } else
    {
        document.getElementById('loader').style.display = 'block';
    }
}

function updateDoc(id, type)
{
    if (type == 1)
    {
        document.getElementById('update' + id).style.display = 'block';
    } else
    {
        document.getElementById('update' + id).style.display = 'none';
    }

}

function submitDoc()
{
    try {
        var cheque = '';
        var gst = '';
        var address_proof = '';
        var comp_cer = '';
        var adhar_card = '';
        var pan_card = '';
        var partner_pancard = '';
        var partner_deed = '';
        var biz_reg = '';
        try {
            var cheque = document.getElementById('a1').value;
        } catch (o) {
        }

        try {
            var gst = document.getElementById('a2').value;
        } catch (o) {
        }
        try {
            var address_proof = document.getElementById('a3').value;
        } catch (o) {
        }
        try {
            var comp_cer = document.getElementById('a4').value;
        } catch (o) {
        }
        try {
            var adhar_card = document.getElementById('a6').value;
        } catch (o) {
        }
        try {
            var pan_card = document.getElementById('a7').value;
        } catch (o) {
        }
        try {
            if (address_proof == '')
            {
                var address_proof = document.getElementById('a9').value;
            }
        } catch (o) {
        }
        try {
            var partner_pancard = document.getElementById('a10').value;
        } catch (o) {
        }
        try {
            var partner_deed = document.getElementById('a11').value;
        } catch (o) {
        }
        try {
            var biz_reg = document.getElementById('a5').value;
        } catch (o) {
        }

        var json = '{"cheque":"' + filtr(cheque) + '","gst":"' + filtr(gst) + '","address_proof":"' + filtr(address_proof) + '","comp_cer":"' + filtr(comp_cer) + '","adhar_card":"' + filtr(adhar_card) + '","pan_card":"' + filtr(pan_card) + '","partner_pancard":"' + filtr(partner_pancard) + '","partner_deed":"' + filtr(partner_deed) + '","biz_reg":"' + filtr(biz_reg) + '"}';

        document.getElementById('detail').value = json;

        var data = $("#documentForm").serialize();
        if (data == '')
        {
            var data = $("#submit_form1").serialize();
        }
        $.ajax({
            type: 'POST',
            url: '/merchant/profile/validateuploadform/1/1',
            data: data,
            success: function (data)
            {
                if (data == 1)
                {
                    document.getElementById('submit_doc').disabled = false;
                    document.getElementById('submit_doc').className = "btn blue pull-right";
                    document.getElementById('confirmbox').style.display = 'block';
                } else
                {
                    document.getElementById('submit_doc').disabled = true;
                    document.getElementById('submit_doc').className = "btn default pull-right";
                    document.getElementById('confirmbox').style.display = 'none';
                }

            }
        });
    } catch (o) {

    }
}

function filtr(val)
{
    if (val != '')
    {
        return 'Exist';
    } else
    {
        return '';
    }
}

function addMoreAddressProof(val, text)
{
    var id = Date.now();
    var mainDiv = document.getElementById('address' + val);
    var newSpan = document.createElement('div');
    newSpan.setAttribute('id', 'exist' + id);
    newSpan.setAttribute('class', 'form-group col-md-12');
    newSpan.innerHTML = '<label class="control-label col-md-5">' + text + ' address proof (Election Card/DL/Passport)</label><div class="col-md-5"><input type="file" accept="image/*,application/pdf" onchange="return validatefilesize(1000000,' + "'gst_cerz" + id + "'" + ');" id="gst_cerz' + id + '" name="address_prrof[]" ><span class="help-block red">* Max file size 1 MB</span></div><div class="col-md-1"><a onclick="remove(' + id + ');" class="btn btn-sm red">Remove</a></div>';
    mainDiv.appendChild(newSpan);
    pcount = pcount + 1;
}

function BizType(val)
{
    if (val == 4)
    {
        try {
            document.getElementById('ccheque').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('gstav').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('biz_reg').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('pvt').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('llp').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('propriter').style.display = 'block';
        } catch (o) {
        }

    } else if (val == 3)
    {
        try {
            document.getElementById('gstav').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('ccheque').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('biz_reg').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('pvt').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('llp').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('propriter').style.display = 'none';
        } catch (o) {
        }

    } else if (val == 2)
    {
        try {
            document.getElementById('gstav').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('ccheque').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('biz_reg').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('pvt').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('llp').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('propriter').style.display = 'none';
        } catch (o) {
        }
    } else if (val == 6)
    {
        try {
            document.getElementById('gstav').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('ccheque').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('biz_reg').style.display = 'block';
        } catch (o) {
        }
        try {
            document.getElementById('pvt').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('llp').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('propriter').style.display = 'block';
        } catch (o) {
        }
    } else
    {
        try {
            document.getElementById('gstav').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('ccheque').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('biz_reg').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('pvt').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('llp').style.display = 'none';
        } catch (o) {
        }
        try {
            document.getElementById('propriter').style.display = 'none';
        } catch (o) {
        }

    }
    submitDoc();
}

function getshareButtons(id)
{
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getShareDetail/' + id,
        data: data,
        success: function (data)
        {
            try {
                document.getElementById('text-data').innerHTML = data;
            } catch (o)
            {
            }
        }
    });


}


function changeFontText(val, type)
{
    $(".font-text").each(function () {
        if (type == 1)
        {
            $(this).text(val);
        } else
        {
            $(this).css("font-size", val + "px");
        }
    });
}

function setFonSize(elm, fontsize) {
    elm.style.fontSize = fontsize;
}


function setupCanvas(canvas) {
    // Get the device pixel ratio, falling back to 1.
    var dpr = window.devicePixelRatio || 1;
    // Get the size of the canvas in CSS pixels.
    var rect = canvas.getBoundingClientRect();
    // Give the canvas pixel dimensions of their CSS
    // size * the device pixel ratio.
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    var ctx = canvas.getContext('2d');
    // Scale all drawing operations by the dpr, so you
    // don't have to worry about the difference.
    ctx.scale(dpr, dpr);
    return ctx;
}

function showImg(id)
{
    var element = $("#font_id" + id);
    html2canvas(element, {
        scale: 2,
        onrendered: function (canvas) {
            $("#font_img").val(canvas.toDataURL());
        }
    });
    $("#testimg").html('');
}

function requestdemo(){
   var date = document.getElementById('daterange').value;
   var time = document.getElementById('time').value;
   
   if(time == 'Pick one time slot'){
        document.getElementById("time-container").classList.add("has-error");
        document.getElementById("timeSlotError").textContent = "Please select a time slot";
        return true;
   }else{
        document.getElementById("requestDemoForm").style.display = "none";
        document.getElementById("requestDemobody").style.display = "none";
        document.getElementById("requestDemoFormSuccessMessage").classList.remove("hidden");
        document.getElementById("requestDemoFormimage").classList.remove("hidden");
        document.getElementById("requestDemoFormSuccessMessageSupport").classList.remove("hidden");
        document.getElementById("DemoTitle").textContent = "Session booked";
        document.getElementById("requestDemoFormSuccessMessage").appendChild(
        document.createTextNode(" "+date+" "+time));
        
        try {
            var data = $("#requestDemoForm").serialize();
            $.ajax({
                type: 'POST',
                url: '/merchant/dashboard/requestdemo',
                data: data,
                success: function (data) {
                    obj = JSON.parse(data);
             
                },
                error: function (request, status, error) {
                   
                }
            });
        } catch (o) {
        }
   }

}