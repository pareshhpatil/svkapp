function _(el) {
    return document.getElementById(el);
}

function confirmlogsheet()
{
    try {
        var data = $("#logsheetform").serialize();
        $.ajax({
            type: 'POST',
            url: '/admin/logsheet/confirmlogsheet',
            data: data,
            success: function (data)
            {
                _("detail").innerHTML = data;
                _("conf").click();
            }
        });
    } catch (o)
    {
        alert(o.message);
    }
    return false;
}

function saveLogsheet()
{
    try {
        var data = $("#logsheetform").serialize();
        $.ajax({
            type: 'POST',
            url: '/admin/logsheet/savelogsheet',
            data: data,
            success: function (data)
            {
                _("loaded_n_total").innerHTML = data;
                _("startkm").value = '';
                _("endkm").value = '';
                _("toll").value = '';
                _("remark").value = '';
                _("closebtn").click();
            }
        });
    } catch (o)
    {
        alert(o.message);
    }
    return false;
}

function logsheetDiv()
{
    _("list").style.display = 'none';
    _("insert").style.display = 'block';
}

function  typechange(val)
{
    if (val == 2)
    {
        _('location').style.display = 'block';
        _('normal').style.display = 'none';
    } else
    {

        _('location').style.display = 'none';
        _('normal').style.display = 'block';
    }
}

function  pickupdrop(val)
{
    if (val == 'DROP')
    {
        document.getElementById('from_loc').value = 'Company';
        document.getElementById('to_loc').value = '';
    } else
    {

        document.getElementById('to_loc').value = 'Company';
        document.getElementById('from_loc').value = '';
    }
}

function calculateLogsheet()
{
    total_amount = 0;
    $('input[name="int[]"]').each(function () {
        var id = $(this).val();
        var deduct = _("is_deduct" + id).value;
        qty = _("qty" + id).value;
        rate = _("rate" + id).value;
        amt = '';
        if (qty > 0 && rate > 0)
        {
            amt = Number(qty * rate);
            if (deduct == 0)
            {
                total_amount = Number(total_amount + amt);
            } else
            {
                total_amount = Number(total_amount - amt);
            }
            _("amt" + id).value = amt.toFixed(2);
        }

    });
    toll = getamt('amt6');
    total_amount = Number(total_amount + toll);
    _("base_total").value = total_amount.toFixed(2);

    cgst = getamt('cgst');
    sgst = getamt('sgst');
    igst = getamt('igst');
    cgst_amt = roundA(total_amount * cgst / 100);
    sgst_amt = roundA(total_amount * sgst / 100);
    igst_amt = roundA(total_amount * igst / 100);
    _("cgst_amt").value = cgst_amt;
    _("sgst_amt").value = sgst_amt;
    _("igst_amt").value = igst_amt;

    total_gst = Number(cgst_amt) + Number(sgst_amt) + Number(igst_amt);
    _("total_gst").value = roundA(total_gst);
    _("grand_total").value = roundA(Number(total_amount) + Number(total_gst));


}

function getamt(amt)
{
    amt = _(amt).value;
    if (amt > 0)
    {
        return Number(amt);
    } else
    {
        return 0;
    }
}

function roundA(amt)
{
    return amt.toFixed(2);
}