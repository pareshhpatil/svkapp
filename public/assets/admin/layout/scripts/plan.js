channel_array = new Array();
package_array = new Array();

function getplanDetails(merchant_id, value, id, link) {
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/mybills/getplandetails/' + merchant_id + '/' + value,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            try {
                if (obj.price > 0) {
                    document.getElementById('price_' + id).innerHTML = obj.price;
                    document.getElementById('planid_' + id).value = obj.plan_link;

                    if (obj.tax_amount > 0) {
                        document.getElementById('base_' + id).innerHTML = obj.base_amount;
                        document.getElementById('tax_' + id).innerHTML = obj.tax_amount;
                    }
                    $('#' + 'link_' + id).attr('href', '/m/' + link + '/confirmpackage/' + obj.plan_link);
                } else {
                    return false;
                }
            } catch (o) {
            }


        }
    });


}
function _(el) {
    return document.getElementById(el);
}
function newcategory(type) {
    if (type == 1) {
        $('#catdrop').prop('required', false);
        $('#cattext').prop('required', true);
        _('catex').style.display = 'none';
        _('catnew').style.display = 'block';
        _('catlinkadd').style.display = 'none';
        _('catlinkremove').style.display = 'inline-block';
    } else {
        $('#catdrop').prop('required', true);
        $('#cattext').prop('required', false);
        _('catex').style.display = 'block';
        _('catnew').style.display = 'none';
        _('catnew').value = '';
        _('catlinkadd').style.display = 'inline-block';
        _('catlinkremove').style.display = 'none';
    }
}

function newsource(type) {
    if (type == 1) {
        $('#sodrop').prop('required', false);
        $('#sotext').prop('required', true);
        _('soex').style.display = 'none';
        _('sonew').style.display = 'block';
        _('solinkadd').style.display = 'none';
        _('solinkremove').style.display = 'inline-block';
    } else {
        $('#sodrop').prop('required', true);
        $('#sotext').prop('required', false);
        _('soex').style.display = 'block';
        _('sonew').style.display = 'none';
        _('sonew').value = '';
        _('solinkadd').style.display = 'inline-block';
        _('solinkremove').style.display = 'none';
    }
}

function addNewPlan() {
    //var x = document.getElementById("particular_table").rows.length;
    var tax = document.getElementById('taxlist').innerHTML;
    tax1 = tax.replace("tax_id", "tax1_id[]");
    tax2 = tax.replace("tax_id", "tax2_id[]");
    var mainDiv = document.getElementById('new_plan');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="text" required value="" name="plan_name[]" maxlength="100" class="form-control input-sm" placeholder="Add plan name"></div></td><td><input type="number" max="10000" min="1" required placeholder="" style="width: 70px;"  name="speed[]" class="form-control input-sm"></td><td><select name="speed_type[]" style="width: 100px;" required class="form-control input-sm" data-placeholder="Select..."><option value=" MBPS">MBPS</option><option value=" KBPS">KBPS</option></select></td><td><input type="number" placeholder="" style="width: 70px;" max="100000" title="Keep 0 for Unlimited" min="0" required value="0" name="data_limit[]" class="form-control input-sm"></td><td><select name="data_limit_type[]"style="width: 100px;" required class="form-control input-sm" data-placeholder="Select..."><option value=" GB">GB</option><option value=" MB">MB</option><option value="Unlimited">Unlimited</option></select></td><td><input type="number" required min="1" max="1000" placeholder="Duration Month" name="duration[]"  class="form-control input-sm"></td><td><input type="number" placeholder="Price in Rs." max="100000" required name="price[]"  class="form-control input-sm"></td><td>' + tax1 + '</td><td>' + tax2 + '</td><td><a  onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> Delete</a></td>';
    mainDiv.appendChild(newDiv);
}


function filterChannel() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("searchval");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("span")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

function removePackage(id) {
    document.getElementById("accordion" + id).remove();

}


function GetSelected(type, sel_id) {
    var pkgcount = 0;
    var chcount = 0;
    var amount = 0;
    var total_ncf_channel = 0;
    //cable_set = document.getElementById('cable_setting').value;

    if (cable_set == '') {
        var ncf_enable = 0;
    } else {
        var ncf_enable = 1;
        cable_setting = JSON.parse(cable_set);
    }


    if (type == 'channel') {
        scheck = document.getElementById('single' + sel_id).checked;
        document.getElementById('genre' + sel_id).checked = scheck;
    }
    if (type == 'genre') {
        scheck = document.getElementById('genre' + sel_id).checked;
        document.getElementById('single' + sel_id).checked = scheck;
    }

    try {
        chcheck = document.getElementById('single' + sel_id).checked;
        if (chcheck == true) {
            channel_array[sel_id]['checked'] = 1;
        } else {
            channel_array[sel_id]['checked'] = 0;
        }
    } catch (o) {
    }

    try {
        pkgcheck = document.getElementById('pkg_id' + sel_id).checked;
        if (pkgcheck == true) {
            package_array[sel_id]['checked'] = 1;
        } else {
            package_array[sel_id]['checked'] = 0;
        }
    } catch (o) {
    }

    $('input[name="package_id[]"]').each(function () {
        sel_id = $(this).val();
        pkgcheck = document.getElementById('pkg_id' + sel_id).checked;
        if (pkgcheck == true) {
            package_array[sel_id]['checked'] = 1;
        } else {
            package_array[sel_id]['checked'] = 0;
        }

    });


    var pkgsel = '';
    $.each(package_array, function (index, value) {
        if (value.checked == 1) {
            pkgcount = pkgcount + 1;
            pkgsel = pkgsel + index + ',';
            pkgamt = Number(value.total_cost);
            channel_count = Number(value.channel_count);
            group_type = value.group_type
            amount = Number(amount + pkgamt);
            if (ncf_enable == 1) {
                try {
                    if (cable_setting.ncf_base_package == 1 && group_type == 1) {
                        total_ncf_channel = total_ncf_channel + channel_count;
                    }

                    if (cable_setting.ncf_addon_package == 1 && group_type == 2) {
                        total_ncf_channel = total_ncf_channel + channel_count;
                    }
                } catch (o) {
                }
            }

        }
    });
    document.getElementById('pkg_sel').value = pkgsel;




    var fLen, i, chid;
    var chsel = '';
    $.each(channel_array, function (index, value) {
        if (value.checked == 1) {
            chsel = chsel + index + ',';
            chcount = chcount + 1;
            chamt = Number(value.total_cost);
            if (chamt > 0) {
                amount = Number(amount + chamt);
            }
            if (ncf_enable == 1) {
                if (cable_setting.ncf_alacarte_package == 1) {
                    total_ncf_channel = total_ncf_channel + 1;
                }
            }

        }
    });

    document.getElementById('ch_sel').value = chsel;
    if (total_ncf_channel > 0) {
        nfc_amt = Number(cable_setting.ncf_fee) + Number(cable_setting.ncf_fee * cable_setting.ncf_tax / 100);
        while (total_ncf_channel > 0) {
            amount = Number(amount + nfc_amt);
            total_ncf_channel = total_ncf_channel - cable_setting.ncf_qty;
        }
    }

    document.getElementById('pkg_selected').innerHTML = pkgcount + ' Package Selected';
    document.getElementById('channl_selected').innerHTML = chcount + ' Channels Selected';
    document.getElementById('total_cost').innerHTML = 'Total Cost (Including GST) : <span class="total">' + amount.toFixed(2) + '</span>';

}


function packageTotal() {
    var amount = 0;
    var pkg_amt = 0;
    var total_ncf_channel = 0;
    if (cable_set == '') {
        var ncf_enable = 0;
    } else {
        var ncf_enable = 1;
        cable_setting = JSON.parse(cable_set);
    }

    $('input[name="exist_id[]"]:checked').each(function () {
        id = $(this).val();
        pkg_amt = Number(document.getElementById('cost' + id).value);
        group_type = Number(document.getElementById('group' + id).value);
        channel_count = Number(document.getElementById('channelcount' + id).value);
        if (pkg_amt > 0) {
            amount = Number(amount + pkg_amt);
        }

        if (ncf_enable == 1) {
            if (cable_setting.ncf_base_package == 1 && group_type == 1) {
                total_ncf_channel = total_ncf_channel + channel_count;
            }

            if (cable_setting.ncf_addon_package == 1 && group_type == 2) {
                total_ncf_channel = total_ncf_channel + channel_count;
            }
            if (cable_setting.ncf_alacarte_package == 1 && group_type == 3) {
                total_ncf_channel = total_ncf_channel + 1;
            }
        }

    });

    if (total_ncf_channel > 0) {
        nfc_amt = Number(cable_setting.ncf_fee) + Number(cable_setting.ncf_fee * cable_setting.ncf_tax / 100);
        while (total_ncf_channel > 0) {
            amount = Number(amount + nfc_amt);
            total_ncf_channel = total_ncf_channel - cable_setting.ncf_qty;
        }
    }

    document.getElementById('total_cost').innerHTML = 'Package Total: <span class="total">' + amount.toFixed(2) + '</span>';
    document.getElementById('confirm_amt').innerHTML = amount.toFixed(2);
}