$(document).ready(function() {
    tinymce.init({
        selector: 'textarea',
        height: 151,
        menubar: false,
    });


    var _URL = window.URL || window.webkitURL;
    $("#fileupload").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {
                // alert(this.width + " " + this.height);
            };
            img.onerror = function() {
                alert("not a valid file: " + file.type);
                $('#image_form').trigger("reset");
            };
            img.src = _URL.createObjectURL(file);
        }

    });
});


req_type = '';
function _(el) {
    return document.getElementById(el);
}
function display_text(path)
{
    val = document.getElementById(path).innerHTML;
    $('#textpopup').css('display', 'block');
    document.getElementById('textinput').value = val;
    document.getElementById('textinputpath').value = path;
}

function display_status(path, status)
{
    if (status == '1')
    {
        document.getElementById('status_text').innerHTML = 'Are you sure you want to Enable this section?';
    } else
    {
        document.getElementById('status_text').innerHTML = 'Are you sure you want to Disable this section?';
    }
    $('#statuspopup').css('display', 'block');
    document.getElementById('statusinput').value = status;
    document.getElementById('statusinputpath').value = path;
}

function display_textarea(path)
{
    val = document.getElementById(path).innerHTML;
    $('#description_textarea_ifr').contents().find('#tinymce').html(val);
    document.getElementById('textareainputpath').value = path;
    $('#textareapopup').css('display', 'block');
}

function display_image(path, info, max)
{
    _("status").innerHTML = '';
    document.getElementById('imageinputpath').value = path;
    document.getElementById('maxupload').value = max;
    document.getElementById('image_info').innerHTML = info;
    $('#imagepopup').css('display', 'block');
}

function closepopup(type)
{
    $('#' + type + 'popup').css('display', 'none');
    return false;
}



function saveform(type)
{
    if (type == 'textarea')
    {
        document.getElementById('textareainput').value = $('#description_textarea_ifr').contents().find('#tinymce').html();
    }
    req_type = type;
    var data = $("#" + type + '_form').serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/website/save',
        data: data,
        success: function(data)
        {
            obj = JSON.parse(data);
            if (req_type == 'status')
            {
                if (obj.value == 1)
                {
                    $('#' + obj.path + '_enable').css('display', 'none');
                    $('#' + obj.path + '_disable').css('display', 'inline-block');
                } else
                {
                    $('#' + obj.path + '_enable').css('display', 'inline-block');
                    $('#' + obj.path + '_disable').css('display', 'none');
                }
            }
            else {
                document.getElementById(obj.path).innerHTML = obj.value;
            }
            closepopup(type);
        }
    });

    return false;
}



function uploadfile() {
    $('#savebutton').attr('disabled', true);
    try {
        var file = _("fileupload").files[0];
    } catch (o) {
    }
    if (file)
    {
        max = document.getElementById('maxupload').value;
        filesize = file.size / 1024;
        if (filesize > max)
        {
            _("status").innerHTML = "Upload failed, file should not be greater than " + max + " KB.";
            $('#image_form').trigger("reset");
            return false;
        } else {
            try {
                path = document.getElementById('imageinputpath').value;

                var formdata = new FormData();
                formdata.append("fileupload", file);
                var ajax = new XMLHttpRequest();
                ajax.upload.addEventListener("progress", progressHandler, false);
                ajax.addEventListener("load", completeHandler, false);
                ajax.addEventListener("error", errorHandler, false);
                ajax.addEventListener("abort", abortHandler, false);
                ajax.open("POST", "/merchant/website/save/" + path);
                ajax.send(formdata);
            } catch (o)
            {
                alert(o.message);
            }
        }
    } else
    {
        alert('Please select image file');
    }
    return false;

}

function progressHandler(event) {
    // _("loaded_n_total").innerHTML = "Uploaded " + event.loaded / 1000 + " KB of " + event.total / 1000;
    var percent = (event.loaded / event.total) * 100;
    _("status").innerHTML = Math.round(percent) + "% uploaded... please wait";

}
function completeHandler(event) {
    obj = JSON.parse(event.target.responseText);
    if (obj.path == 'content_home_banner_value')
    {
        document.getElementById(obj.path).style.background = "url('" + obj.value + "')";
    } else
    {
        document.getElementById(obj.path).src = obj.value;
    }
    _("status").innerHTML = '';
    $('#image_form').trigger("reset");
    closepopup('image');
}
function errorHandler(event) {

}
function abortHandler(event) {
}
