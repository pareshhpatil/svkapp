var is_mobile = 0;
function shorturl()
{
    document.getElementById('shortbtntext').innerHTML = 'Loading...';
    $('#btn_short').prop('disabled', true);
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/getshorturl/' + is_mobile,
        data: data,
        success: function (data)
        {
            obj = JSON.parse(data);
            if (obj.status == 1)
            {
                document.getElementById('shortdiv').style.display = 'block';
                document.getElementById('short_url_list').innerHTML = obj.div;
                document.getElementById('short_copy').innerHTML = '<shorturl>' + obj.short_url + '</shorturl>';
                document.getElementById('_url').value = obj.short_url;
                document.getElementById('_url').style.color = '#2a5bd7';
                document.getElementById('btn_short').style.display = 'none';
                document.getElementById('btn_copy').style.display = 'block';
                document.getElementById('btn_copy').className = "btn default shu_btn";
                document.getElementById('shortbtntext').innerHTML = 'Shorten';
                $('#btn_short').prop('disabled', false);
                UIBootstrapGrowl.init();
                document.getElementById('btn_copy').className = "btn default shu_btn bs_growl_show";
            } else
            {
                document.getElementById('shortbtntext').innerHTML = 'Shorten';
                document.getElementById('errorspan').style.display = 'block';
                document.getElementById('errorspan').innerHTML = obj.error;
                $('#btn_short').prop('disabled', false);
                success = 0;
            }


        }
    });

    return false;
}


function changeBtn()
{
    document.getElementById('btn_short').style.display = 'block';
    document.getElementById('btn_copy').style.display = 'none';
    document.getElementById('_url').style.color = '#97a7b4';
    document.getElementById('errorspan').style.display = 'none';
}