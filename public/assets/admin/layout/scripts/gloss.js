var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


function deleteChapter(amount, id)
{
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getEncryptedAmount/' + amount + '/' + id,
        data: data,
        success: function (data)
        {
            document.getElementById('incrypt').value = data;
            document.getElementById('amtgloss').value = amount;
            amount = amount.toFixed(2);
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + amount + ' /-';
        }
    });
}

function calculateTotal(abc, priceex, id)
{
    try {
        var form_name = 'gloss_confirm';
        var check_name = 'price[]';
        var formObj = document.forms[form_name];
        var boxes = Number(formObj[check_name].length);
        var num = 0;
        var amount = 0;

        for (var i = 0; i < boxes; i++) {
            try {
                price = Number(formObj[check_name][i].value);
                amount = Number(amount + price);

            }
            catch (o)
            {
                alert(o.message);
            }
        }
        amount = amount - priceex;
    }
    catch (o)
    {
        alert(o.message);
    }
    if (amount > 0)
    {
        $(abc).closest('tr').remove();
        deleteChapter(amount, id);
        return true;
    } else
    {
        document.getElementById('deleteid').setAttribute('onclick', 'deleteChapter(' + amount + ',' + id + ');');
        document.getElementById('guest').click();
        return false;
    }

}

function deleteChapter(amount, id)
{
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getEncryptedAmount/' + amount + '/' + id,
        data: data,
        success: function (data)
        {
            document.getElementById('incrypt').value = data;
            document.getElementById('amtgloss').value = amount;
            amount = amount.toFixed(2);
            document.getElementById('grandtotal').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + amount + ' /-';
        }
    });
}
function checkstatus(link)
{
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/checkstatus',
        data: data,
        success: function (data)
        {
            if (data == 0)
            {
                window.location = "/patron/event/view/" + link;
            }
        }
    });
}
function HandleBackFunctionality()
{
    alert();
}
function checkpost()
{
    var form_name = 'event_form';
    var check_name = 'booking_slots[]';
    var formObj = document.forms[form_name];
    var boxes = Number(formObj[check_name].length);
    var num = 0;
    var amount = 0;
    for (var i = 0; i < boxes; i++) {
        try {
            if (formObj[check_name][i].checked == true) {
                price = Number(formObj[check_name][i].title);
                b_qty = 1;
                total_price = b_qty * price;

                amount = Number(amount + total_price);


                num = num + b_qty;
            }
        }
        catch (o)
        {
            // alert(o.message);
        }
    }
    if (amount > 0)
    {
        window.location = "/patron/event/redirect";
    }
}
function calculateSlot(id)
{
    try {
        var form_name = 'event_form';
        var check_name = 'booking_slots[]';
        var formObj = document.forms[form_name];
        var boxes = Number(formObj[check_name].length);
        var num = 0;
        var amount = 0;
        for (var i = 0; i < boxes; i++) {
            try {
                if (formObj[check_name][i].checked == true) {
                    price = Number(formObj[check_name][i].title);
                    b_qty = 1;
                    total_price = b_qty * price;

                    amount = Number(amount + total_price);


                    num = num + b_qty;
                }
            }
            catch (o)
            {
                // alert(o.message);
            }
        }
        //amount = Math.round(100 * amount) / 100;
        if (amount > 0)
        {
            document.getElementById('amount').value = amount.toFixed(2);
            document.getElementById('amount2').value = amount.toFixed(2);
            document.getElementById('total_div').style.display = 'block';
        } else
        {
            document.getElementById('amount').value = '';
            document.getElementById('amount2').value = '';
            document.getElementById('total_div').style.display = 'none';
        }
        document.getElementById('totalslot').innerHTML = num;
        document.getElementById('absolute_costt').innerHTML = '<i class="fa fa-inr fa-large"></i> ' + amount.toFixed(2) + '/-';
    }
    catch (p)
    {
        //alert(p.message);
    }


    try {
        if (document.getElementById('slotchk' + id).checked) {
            document.getElementById('slotid' + id).value = '1';
            document.getElementById('slotbtntext' + id).innerHTML = '&nbsp;<i class="fa fa-remove"></i>&nbsp;&nbsp;';
            document.getElementById('slotbtn' + id).className = "btn btn-sm  red";
            // document.getElementById('slotbtntextp' + id).innerHTML = '&nbsp;<i class="fa fa-remove">&nbsp;';
            //document.getElementById('slotbtnp' + id).className = "btn btn-sm  red";



        } else
        {
            document.getElementById('slotid' + id).value = '0';
            document.getElementById('slotbtntext' + id).innerHTML = 'Buy';
            document.getElementById('slotbtn' + id).className = "btn btn-sm  blue";
            // document.getElementById('slotbtntextp' + id).innerHTML = 'Buy';
            // document.getElementById('slotbtnp' + id).className = "btn btn-sm  blue";


        }
    }
    catch (c)
    {
        // alert(c.message+'hi1');
    }
    displaydesc(id);
}

function displaydesc(id)
{
    try {
        document.getElementById('chapterdesc').innerHTML = document.getElementById('desc' + id).innerHTML;
        document.getElementById('pkgname').innerHTML = document.getElementById('name' + id).value;
        document.getElementById('pkgprice').innerHTML = document.getElementById('pric' + id).value;
        if (document.getElementById('slotchk' + id).checked) {
            document.getElementById('btnpc').innerHTML = '<label class="btn btn-sm  red" id="slotbtnp' + id + '"><span id="slotbtntextp' + id + '">&nbsp;<i class="fa fa-remove"></i>&nbsp;</span><span style="display: none;"><input id="checkboxbutton" checked onchange="selectCheckbox(' + "'" + id + "'" + ');"class="checker" value="1" type="checkbox"></span></label>';
        } else
        {
            document.getElementById('btnpc').innerHTML = '<label class="btn btn-sm  blue" id="slotbtnp' + id + '"><span id="slotbtntextp' + id + '">Buy</span><span style="display: none;"><input id="checkboxbutton" onchange="selectCheckbox(' + "'" + id + "'" + ');"class="checker" value="1" type="checkbox"></span></label>';
        }
    }
    catch (c)
    {
        //alert(c.message+'hi2');
    }

}

function selectCheckbox(id)
{
    try {
        if (document.getElementById('checkboxbutton').checked) {
            document.getElementById('slotchk' + id).checked = true;
        } else
        {
            document.getElementById('slotchk' + id).checked = false;
        }

    }
    catch (c)
    {
        // alert(c.message);
    }
    calculateSlot(id);
}


function validateform()
{
    amount = Number(document.getElementById('amount').value);
    if (amount > 0)
    {
        return true;
    } else
    {
        alert('Please select chapters');
        return false;
    }
}

