function addButton(val)
{
    document.getElementById(val + '_row').className = '';
    document.getElementById(val + '_btn').className = 'hidden';
}

function closeButton(val, qty)
{
    document.getElementById(val + '_row').className = 'hidden';
    document.getElementById(val + '_btn').className = 'btn blue btn-xs';
    document.getElementById(qty).selectedIndex = 0;
}


function getSubCategory(type, category, td, qty)
{
    var data = $("#add").serialize();
    $.ajax({
        type: 'POST',
        url: '/patron/event/get_sub_category/' + type + '/' + category,
        data: data,
        success: function(data)
        {
            document.getElementById(td).innerHTML = data;
            document.getElementById(qty).selectedIndex = 0;
        }
    });
    return false;
}

function calculateprebook(qty)
{
    if (qty != 'NULL')
    {
        var res = isProduct(qty);
        if (res == false)
        {
            return false;
        }
    }
    error = "";
    totalnumber = 0;
    var imac = (Number(document.getElementById('imac_qty1').value) + Number(document.getElementById('imac_qty2').value));
    if (imac > 2) {
        error = "2";
    }
    totalnumber = totalnumber + imac;
    imac = imac * 10000;
    var macbook = (Number(document.getElementById('macbook_qty1').value) + Number(document.getElementById('macbook_qty2').value));
    if (macbook > 2) {
        error = "2";
    }
    totalnumber = totalnumber + macbook;
    macbook = macbook * 10000;
    var macbookpro = (Number(document.getElementById('macbookpro_qty1').value) + Number(document.getElementById('macbookpro_qty2').value));
    if (macbookpro > 2) {
        error = "2";
    }
    totalnumber = totalnumber + macbookpro;
    macbookpro = macbookpro * 10000;
    var macpro = (Number(document.getElementById('macpro_qty1').value) + Number(document.getElementById('macpro_qty2').value));
    if (macpro > 2) {
        error = "2";
    }
    totalnumber = totalnumber + macpro;
    macpro = macpro * 10000;
    var macbookair = (Number(document.getElementById('macbookair_qty1').value) + Number(document.getElementById('macbookair_qty2').value));
    if (macbookair > 2) {
        error = "2";
    }
    totalnumber = totalnumber + macbookair;
    macbookair = macbookair * 10000;

    var iphone = (Number(document.getElementById('iphone_qty1').value) + Number(document.getElementById('iphone_qty2').value));

    if (iphone > 2) {
        error = "2";
    }
    totalnumber = totalnumber + iphone;
    iphone = iphone * 5000;

    if (error == "2")
    {
        alert("You can select a maximum of 2 products under this category.");
        document.getElementById(qty).selectedIndex = 0;
        calculateprebook(qty);
        return;
    } else {
        if (totalnumber > 4)
        {
            alert("You can select a maximum of 4 products under this offer.");
            document.getElementById(qty).selectedIndex = 0;
            calculateprebook(qty);
            return;
        } else {
            var total = imac + macbook + macbookpro + macpro + macbookair + iphone;
            document.getElementById('grand_total').value = Number(total);
            total = numberWithCommas(total);
            document.getElementById('display_grand_total').innerHTML = '<i class="fa  fa-inr"></i> ' + total + '/-';
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

function isProduct(qty)
{
    var type = "gadget";
    switch (qty)
    {
        case 'imac_qty1':
            var qty_id = 'pack01';
            break;
        case 'imac_qty2':
            var qty_id = 'pack02';
            break;
        case 'macbook_qty1':
            var qty_id = 'pack03';
            break;
        case 'macbook_qty2':
            var qty_id = 'pack04';
            break;
        case 'macbookpro_qty1':
            var qty_id = 'pack05';
            break;
        case 'macbookpro_qty2':
            var qty_id = 'pack06';
            break;
        case 'macpro_qty1':
            var qty_id = 'pack07';
            break;
        case 'macpro_qty2':
            var qty_id = 'pack08';
            break;
        case 'macbookair_qty1':
            var qty_id = 'pack09';
            break;
        case 'macbookair_qty2':
            var qty_id = 'pack10';
            break;
        case 'iphone_qty1':
            var i_model = document.getElementById('iphone_model1').value;
            var i_storage = document.getElementById('iphone_storage1').value;
            var i_color = document.getElementById('iphone_colour1').value;
            type = "iphone";
            if (i_model == "" || i_storage == "" || i_color == "")
            {
                alert('Product Details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            break;
        case 'iphone_qty2':
            var i_model = document.getElementById('iphone_model2').value;
            var i_storage = document.getElementById('iphone_storage2').value;
            var i_color = document.getElementById('iphone_colour2').value;
            type = "iphone";
            if (i_model == "" || i_storage == "" || i_color == "")
            {
                alert('Product Details incomplete. Please complete all fields and try again.');
                document.getElementById(qty).selectedIndex = 0;
                return false;
            }
            break;
    }

    if (type == "gadget") {
        if (document.getElementById(qty_id).value > 0)
        {

        } else
        {
            alert('Product Details incomplete. Please complete all fields and try again.');
            document.getElementById(qty).selectedIndex = 0;
            return false;
        }
    }

    return true;
}

function manageSubcategory(val, number)
{
    var full_storage = '<select class="form-control" name="iphonestorage[]" id="iphone_storage' + number + '" data-placeholder="Select..."><option value="">Select Storage</option><option value="16GB">16GB</option><option value="64GB">64GB</option><option value="128GB">128GB</option></select>';
    var full_colour = '<select class="form-control" name="iphonecolour[]" id="iphone_colour' + number + '" data-placeholder="Select..."><option value="">Select Colour</option><option value="Space Gray">Space Gray</option><option value="Gold">Gold</option><option value="Rose Gold">Rose Gold</option><option value="Silver">Silver</option></select>';

    var storage = '<select class="form-control" name="iphonestorage[]" id="iphone_storage' + number + '" data-placeholder="Select..."><option value="">Select Storage</option><option value="16GB">16GB</option><option value="64GB">64GB</option></select>';
    var colour = '<select class="form-control" name="iphonecolour[]" id="iphone_colour' + number + '" data-placeholder="Select..."><option value="">Select Colour</option><option value="Space Gray">Space Gray</option><option value="Silver">Silver</option></select>';



    if (val == 'iPhone 6' || val == 'iPhone 6 plus')
    {
        document.getElementById('istorage_' + number).innerHTML = storage;
        document.getElementById('icolour_' + number).innerHTML = colour;
    } else
    {
        document.getElementById('istorage_' + number).innerHTML = full_storage;
        document.getElementById('icolour_' + number).innerHTML = full_colour;
    }
    document.getElementById('iphone_qty' + number).selectedIndex = 0;
    calculateprebook('NULL');
}