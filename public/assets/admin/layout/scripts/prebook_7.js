var added = 0;
function addButton(val)
{
    document.getElementById(val + '_row').className = '';
    document.getElementById(val + '_btn').className = 'hidden';
    added = 1;
}

function closeButton(val, qty)
{
    document.getElementById(val + '_row').className = 'hidden';
    document.getElementById(val + '_btn').className = 'btn blue btn-xs';
    document.getElementById('iphone_model2').selectedIndex = 0;
    added = 0;
    calculateprebook(qty);
}

function calculateprebook(qty)
{
    error = "";
    totalnumber = 0;

    model1 = document.getElementById('iphone_model1').value;
    if (model1 == 'iPhone 7')
    {
        amount1 = 5000;
    } else
    {
        amount1 = 5000;
    }

    model2 = document.getElementById('iphone_model2').value;
    if (model2 == 'iPhone 7')
    {
        amount2 = 5000;
    } else
    {
        amount2 = 5000;
    }

    if (model1 == "")
    {
        qty1 = 0;
    } else
    {
        qty1 = Number(document.getElementById('iphone_qty1').value);
    }

    if (model2 == "")
    {
        qty2 = 0;
    } else
    {
        qty2 = Number(document.getElementById('iphone_qty2').value);
    }


    var iphone = qty1 + qty2;

    if (iphone > 10) {
        error = "2";
    }
    totalnumber = totalnumber + iphone;
    total1 = qty1 * amount1;
    total2 = qty2 * amount2;
    total = total1 + total2;

    if (error == "2")
    {
        alert("You can select a maximum of 10 products under this category.");
        document.getElementById(qty).selectedIndex = 0;
        calculateprebook(qty);
        return;
    } else {
        if (totalnumber > 10)
        {
            alert("You can select a maximum of 10 products under this offer.");
            document.getElementById(qty).selectedIndex = 0;
            calculateprebook(qty);
            return;
        } else {
            document.getElementById('grand_total').value = Number(total);
            if (total > 0)
            {
                total = numberWithCommas(total);
                document.getElementById('display_grand_total').innerHTML = '<i class="fa  fa-inr"></i> ' + total + '/-';
            } else
            {
                document.getElementById('display_grand_total').innerHTML = '';
            }
        }
    }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function seatcalculate()
{
    booking_amount_total = document.getElementById('grand_total').value;

    if (booking_amount_total > 0)
    {
        return true;
    }
    else
    {
        alert('Select Quantity');
        return false;
    }
}

function clearModel(id)
{
    document.getElementById('iphone_colour' + id).selectedIndex = 0;
    document.getElementById('iphone_storage' + id).selectedIndex = 0;
    document.getElementById('iphone_qty' + id).selectedIndex = 0;
    calculateprebook('NULL');
}

function validate()
{
    var res = isProduct('iphone_qty1');
    if (res == false)
    {
        return false;
    } else {
        if (added == 1)
        {
            var res = isProduct('iphone_qty2');
            if (res == false)
            {
                return false;
            }
        }
    }
}

function isProduct(qty)
{
    switch (qty)
    {
        case 'iphone_qty1':
            var i_model = document.getElementById('iphone_model1').value;
            var i_storage = document.getElementById('iphone_storage1').value;
            var i_color = document.getElementById('iphone_colour1').value;
            var i_othercolor = document.getElementById('iphone_colour_other1').value;
            type = "iphone";
            if (i_model == "" || i_storage == "" || i_color == "")
            {
                alert('Purchase details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            if (i_color == "Jet Black" && i_othercolor == "")
            {
                alert('Purchase details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            break;
        case 'iphone_qty2':
            var i_model = document.getElementById('iphone_model2').value;
            var i_storage = document.getElementById('iphone_storage2').value;
            var i_color = document.getElementById('iphone_colour2').value;
            var i_othercolor = document.getElementById('iphone_colour_other2').value;
            type = "iphone";
            if (i_model == "" || i_storage == "" || i_color == "")
            {
                alert('Product Details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            if (i_color == "Jet Black" && i_othercolor == "")
            {
                alert('Product Details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            break;
    }

    return true;
}

function manageSubcategory(val, number)
{
    var full_storage = '<select class="form-control" name="iphonestorage[]" id="iphone_storage' + number + '" data-placeholder="Select..."><option value="">Select Storage</option><option value="32GB">32GB</option><option value="128GB">128GB</option><option value="256GB">256GB</option></select>';
    var storage = '<select class="form-control" name="iphonestorage[]" id="iphone_storage' + number + '" data-placeholder="Select..."><option value="">Select Storage</option><option value="128GB">128GB</option><option value="256GB">256GB</option></select>';

    if (val == 'Jet Black')
    {
        document.getElementById('istorage_' + number).innerHTML = storage;
        document.getElementById('other_' + number).style.display = 'block';
    } else
    {
        document.getElementById('istorage_' + number).innerHTML = full_storage;
        document.getElementById('other_' + number).style.display = 'none';
    }
    document.getElementById('iphone_qty' + number).selectedIndex = 0;
    calculateprebook('NULL');
    //selectother();
}


function selectother()
{
    var narrative = document.getElementById('company_name').value;

    othercolor1 = document.getElementById('iphone_colour_other1').value;
    othercolor2 = document.getElementById('iphone_colour_other2').value;

    color1 = document.getElementById('iphone_colour1').value;
    color2 = document.getElementById('iphone_colour2').value;

    if (color1 == 'Jet Black')
    {
        narrative = narrative + ' Model 1 Alt Color: ' + othercolor1 + '. ';
    }

    if (color2 == 'Jet Black')
    {
        narrative = narrative + ' Model 2 Alt Color: ' + othercolor2 + '.';
    }

    document.getElementById('narrative').value = narrative;
}