var showcolorpic = 0;
var autoGST = 0;
function calculate()
{
    var past_due = inval(_('past_due').value);
    var p_cost = inval(_('p_cost').value);
    var p_cost2 = inval(_('p_cost2').value);
    var c_cost = 0;
    p_cost = Number(p_cost) + Number(p_cost2);
    _('sub_total').value = round(p_cost);
    if (autoGST == 0)
    {
        tax_1 = getTaxAmount('tax_name1');
        tax_1 = p_cost * tax_1 / 100;
        tax_2 = getTaxAmount('tax_name2');
        tax_2 = p_cost * tax_2 / 100;
        _('tax1').value = round(tax_1);
        _('tax2').value = round(tax_2);
        total_amount = Number(p_cost) + Number(tax_1) + Number(tax_2);
    } else
    {
        total_amount = Number(p_cost) + inval(_('tax1').value) + inval(_('tax2').value) + inval(_('tax3').value) + inval(_('tax4').value);
    }

    try {
        _('tx_total').innerHTML = round(Number(tax_1) + Number(tax_2));
    } catch (o)
    {

    }
    try {
        c_cost = inval(_('c_cost').value);
    } catch (o)
    {

    }

    _('total_amount').value = round(total_amount - c_cost);
    grand_total = total_amount + Number(past_due) - c_cost;
    // _('current_charges').value = round(total_amount);
    _('total_due').value = round(grand_total);
    _('grand_total').innerHTML = currencyFormat(round(grand_total));

}

function currencyFormat(x) {
    return x.toString().split('.')[0].length > 3 ? x.toString().substring(0, x.toString().split('.')[0].length - 3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length - 3) : x.toString();
}

function calculateSale()
{
    var p_qty = inval(_('p_qty').value);
    var p_rate = inval(_('p_rate').value);
    var p_discount = inval(_('p_discount').value);
    var cost = Number(p_qty * p_rate);
    _('p_cost').value = round(cost - p_discount);
    var p_qty = inval(_('p_qty2').value);
    var p_rate = inval(_('p_rate2').value);
    var p_discount = inval(_('p_discount2').value);
    var cost = Number(p_qty * p_rate);
    _('p_cost2').value = round(cost - p_discount);
    calculateGST();
    calculate();
}

function calculateTicket()
{
    var p_amt = inval(_('amount').value);
    var p_scharge = inval(_('scharge').value);
    var cost = Number(p_amt + p_scharge);
    _('p_cost').value = round(cost);
    var p_amt2 = inval(_('amount2').value);
    var p_scharge2 = inval(_('scharge2').value);
    var cost2 = Number(p_amt2 + p_scharge2);
    _('p_cost2').value = round(cost2);
    _('tp_amt').innerHTML = round(Number(p_amt + p_amt2));
    _('tp_scharge').innerHTML = round(Number(p_scharge + p_scharge2));
    _('tp_cost').innerHTML = round(Number(cost + cost2));
    _('tx_applicable1').value = Number(cost + cost2);
    _('tx_applicable2').value = Number(cost + cost2);

    var p_amt = inval(_('camount').value);
    var p_scharge = inval(_('cscharge').value);
    var cost = Number(p_amt - p_scharge);
    _('c_cost').value = round(cost);
    _('tc_amt').innerHTML = round(p_amt);
    _('tc_scharge').innerHTML = round(p_scharge);
    _('tc_cost').innerHTML = round(cost);

    calculate();
}

function calculateCar()
{
    var p_amt = inval(_('p_qty').value);
    var p_scharge = inval(_('p_rate').value);
    var cost = Number(p_amt * p_scharge);
    _('p_cost').value = round(cost);
    var p_amt2 = inval(_('p_qty2').value);
    var p_scharge2 = inval(_('p_rate2').value);
    var cost2 = Number(p_amt2 * p_scharge2);
    _('p_cost2').value = round(cost2);
    _('tp_cost').innerHTML = round(Number(cost + cost2));
    _('tx_applicable1').value = Number(cost + cost2);
    _('tx_applicable2').value = Number(cost + cost2);
    calculate();
}

function calculateConsultant()
{
    var p_amt = inval(_('p_qty').value);
    var p_scharge = inval(_('p_rate').value);
    var cost = Number(p_amt * p_scharge);
    _('p_cost').value = round(cost);
    var p_amt2 = inval(_('p_qty2').value);
    var p_scharge2 = inval(_('p_rate2').value);
    var cost2 = Number(p_amt2 * p_scharge2);
    _('p_cost2').value = round(cost2);
    calculate();
}

function changeTax(id)
{
    tax = getTaxAmount('tax_name' + id);
    _('tx_per' + id).value = round(tax);
    calculate();
}

function setSalesGST(amount, int, gst)
{
    var int2 = Number(int + 1);
    half_gst = Number(gst / 2);
    $('#tax' + int + '-tr').removeClass("d-none");
    $('#tax' + int2 + '-tr').removeClass("d-none");
    $("#tax_name" + int + " option").remove();
    $("#tax_name" + int2 + " option").remove();
    $('#tax_name' + int).append('<option selected value="CGST@' + half_gst + '%">CGST@' + half_gst + '%</option>');
    $('#tax_name' + int).append('<option value="IGST@' + gst + '%">IGST@' + gst + '%</option>');
    $('#tax_name' + int2).append('<option selected value="SGST@' + half_gst + '%">SGST@' + half_gst + '%</option>');
    tax_amt = amount * half_gst / 100;
    _('tax' + int).value = round(tax_amt);
    _('tax' + int2).value = round(tax_amt);
}

function calculateGST()
{
    $('#tax1-tr').addClass("d-none");
    $('#tax2-tr').addClass("d-none");
    $('#tax3-tr').addClass("d-none");
    $('#tax4-tr').addClass("d-none");
    autoGST = 1;
    var gst1 = inval(_('p_gst').value);
    var gst2 = inval(_('p_gst2').value);


    if (gst1 == gst2)
    {
        if (gst1 > 0)
        {
            amt1 = inval(_('p_cost').value);
            amt2 = inval(_('p_cost2').value);
            amount = Number(amt1) + Number(amt2);
            setSalesGST(amount, 1, gst1);
            $("#tnc_col").attr('rowspan', 7);
        }
    } else
    {
        if (gst1 > 0)
        {
            amt = inval(_('p_cost').value);
            setSalesGST(amt, 1, gst1);
            $("#tnc_col").attr('rowspan', 7);

            if (gst2 > 0)
            {
                amt = inval(_('p_cost2').value);
                setSalesGST(amt, 3, gst2);
                $("#tnc_col").attr('rowspan', 9);
            }

        } else
        {
            if (gst2 > 0)
            {
                amt = inval(_('p_cost2').value);
                setSalesGST(amt, 1, gst2);
                $("#tnc_col").attr('rowspan', 7);
            }
        }
    }





}

function getTaxAmount(tax_name)
{
    var tax_val = _(tax_name).value;
    switch (tax_val) {
        case 'CGST@2.5%':
            var tax = 2.5;
            break;
        case 'SGST@2.5%':
            var tax = 2.5;
            break;
        case 'IGST@5%':
            var tax = 5;
            break;
        case 'CGST@6%':
            var tax = 6;
            break;
        case 'SGST@6%':
            var tax = 6;
            break;
        case 'IGST@12%':
            var tax = 12;
            break;
        case 'CGST@9%':
            var tax = 9;
            break;
        case 'SGST@9%':
            var tax = 9;
            break;
        case 'IGST@18%':
            var tax = 18;
            break;
        case 'CGST@14%':
            var tax = 14;
            break;
        case 'SGST@14%':
            var tax = 14;
            break;
        case 'IGST@28%':
            var tax = 28;
            break;
        default:
            tax = 0;
            break;

    }

    return inval(tax);
}

function _(el) {
    return document.getElementById(el);
}

function inval(val)
{
    if (val > 0)
    {
        return Number(val);
    } else
    {
        return 0;
    }
}

function round(num)
{
    return  (Math.round(num * 100) / 100).toFixed(2);

}

function addnewrow()
{
    val = document.getElementById('newrow').style.display;
    if (val == 'table-row')
    {
        setRegText(2);
    } else
    {
        document.getElementById('newrow').style.display = 'table-row';
        //document.getElementById('newrowlink').style.display = 'none';
    }
}
function addcol()
{
    val = $('.add-col').css('display');
    if (val == 'table-cell')
    {
        val = $('.add-col2').css('display');
        if (val == 'table-cell')
        {
            setRegText(1);

        } else
        {
            $(".col-span").attr('colspan', 2);
            $('.add-col2').css('display', 'table-cell');
        }
    } else
    {
        $(".col-span").attr('colspan', 2);
        $('.add-col').css('display', 'table-cell');
        $(".col-span2").attr('colspan', 4);
        $(".col-span3").attr('colspan', 10);
        $(".col-span4").attr('colspan', 3);
        $(".col-span5").attr('colspan', 5);
        $(".col-span6").attr('colspan', 6);
        $(".col-span7").attr('colspan', 7);
    }



}
function removecol(type)
{
    if (type == '2')
    {
        $('.add-col2').css('display', 'none');
        document.getElementById('time_period_label').value = '';
        $('#time_period_label').prop('required', false);
    } else
    {
        $('.add-col').css('display', 'none');
        document.getElementById('sac_code_label').value = '';
        $('#sac_code_label').prop('required', false);
    }
}

function GSTType(int)
{
    var int2 = Number(int + 1);
    gst = _('tax_name' + int).value;
    taxamt = inval(_('tax' + int).value);
    rowspan = Number($("#tnc_col").attr('rowspan'));
    if (gst == 'IGST@5%' || gst == 'IGST@12%' || gst == 'IGST@18%' || gst == 'IGST@28%')
    {
        _('tax' + int).value = round(taxamt * 2);
        $("#tnc_col").attr('rowspan', Number(rowspan - 1));
        $('#tax' + int2 + '-tr').addClass("d-none");
        _('tax' + int2).value = "";
    } else
    {
        _('tax' + int).value = round(taxamt / 2);
        $("#tnc_col").attr('rowspan', Number(rowspan + 1));
        $('#tax' + int2 + '-tr').removeClass("d-none");
        _('tax' + int2).value = round(taxamt / 2);
    }
}

function setMandatory(type, val)
{
    if (val != '')
    {
        $('#' + type + '_label').prop('required', true);
    } else
    {
        $('#' + type + '_label').prop('required', false);
    }
}
function removerow()
{
    document.getElementById('newrow').style.display = 'none';
    //document.getElementById('newrowlink').style.display = 'none';
    try {
        _('p_rate2').value = 0;
    } catch (o)
    {
    }
    try {
        _('p_qty2').value = 0;
    } catch (o)
    {
    }
    try {
        _('p_cost2').value = 0;
    } catch (o)
    {
    }
    try {
        _('amount2').value = 0;
    } catch (o)
    {
    }
    try {
        _('scharge2').value = 0;
    } catch (o)
    {
    }

     _('p_cost2').onblur();

}

function setRegText(type)
{
    if (type == '')
    {
        return;
    }
    if (type == 'print')
    {
        _('print').click();
        return;
    }
    if (type == 1)
    {
        text = 'Create an invoice with multiple columns, register now to unlock this feature';
    }
    else if (type == 2)
    {
        text = 'Create an invoice with multiple rows, register now to unlock this feature';
    }
    else if (type == 3)
    {
        text = 'Save your invoices in your own account, register now to unlock this feature';
    }
    else if (type == 4)
    {
        text = 'Email your invoice to your customer, register now to unlock this feature';
    }
    else if (type == 5)
    {
        text = 'Send your invoice on SMS to your customer, register now to unlock this feature';
    }
    else if (type == 6)
    {
        text = 'Collect money faster, provide online payment options to your customers. Register now, to unlock this feature';
    }
    else {
        text = 'Thousands of businesses use Swipez daily. Access your invoices anywhere, get a free account to start creating and saving your invoices online.';
    }
    $(".regtext").html(text);
    document.getElementById('modal').click();

}


$(document).on("click", ".browse", function () {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});
$(document).on("click", ".remove", function () {
    document.getElementById('imgg').value = '';
    document.getElementById('imgdiv').style.backgroundImage = "url('/static/images/demo-logo.pngv=3')";

});

jQuery(document).ready(function () {
    $('input[type="file"]').change(function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            document.getElementById('imgdiv').style.backgroundImage = 'url(' + e.target.result + ')';
            //document.getElementById("preview").src = e.target.result;
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
    $(".imgdiv").mouseover(function () {
        document.getElementById('btndiv').style.display = 'block';
    });
    $(".imgdiv").mouseleave(function () {
        document.getElementById('btndiv').style.display = 'none';
    });

    // $("#cgear").click(function () {
    // $(".spectrum").click();
    // });

});

$(document).mouseup(function (e) {
    var container = $("#colorpckdiv1");
    var container2 = $("#colorpckdiv2");

    // If the target of the click isn't the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        showcolorpic = 0;
        container.hide();
        container2.hide();
    }
});

$(document).click(function (e) {
    var container = $(".collapse");
    val = $('#collapseExample').hasClass("show");
    // If the target of the click isn't the container
    if (!container.is(e.target) && container.has(e.target).length === 0 && val == true) {
        document.getElementById('more').click();
    }
});


function showcolor(id)
{
    if (showcolorpic == 0)
    {
        showcolorpic = 1;
        document.getElementById('colorpckdiv' + id).style.display = 'block';
    } else
    {
        showcolorpic = 0;
        document.getElementById('colorpckdiv' + id).style.display = 'none';
    }
}


function changebg(color, type)
{
    if (type == 1)
    {
        $('.bg-grey').css('background-color', color);
        $('.bg-grey').css('border-color', color);
        document.getElementById('color-picker3').value = color;
        $('.bg-grey2').css('background-color', color);
        $('.bg-grey2').css('border-color', color);
    } else
    {
        $('.bg-grey2').css('background-color', color);
        $('.bg-grey2').css('border-color', color);
    }
}
function changecolor(color, type)
{
    if (type == 1)
    {
        $('.bg-grey').css('color', color);
        $('.bg-grey2').css('color', color);
        document.getElementById('color-picker4').value = color;
    } else
    {
        $('.bg-grey2').css('color', color);
    }
}






