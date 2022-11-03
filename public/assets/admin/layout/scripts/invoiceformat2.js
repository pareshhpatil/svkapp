var tax_array = null;
var customer_state = '';
var product_gst = 0;
var product_index = null;
var new_bill_index = null;
var datecolcount = 1;
var tax_index = null;
var update = false;
var tb_col = null;
var tc_col = null;
var hb_col = null;
var fs_col = null;
var invoice_construction = false;
var calcRowInt;
var GST_dropdown = '<select name="gst[]" onchange="calculateamt();" style="min-width:80px;" class="form-control "><option value="">Select</option><option value="0">0%</option><option value="5">5%</option><option value="12">12%</option><option value="18">18%</option><option value="28">28%</option></select>';
var GST_dropdown_SEC = '<select name="sec_gst[]" onchange="calculateamt(' + "'sec_'" + ');" style="min-width:80px;" class="form-control "><option value="">Select</option><option value="0">0%</option><option value="5">5%</option><option value="12">12%</option><option value="18">18%</option><option value="28">28%</option></select>';
function _(el) {
    return document.getElementById(el);
}
function drawInvoiceParticularFormat(particular_col, total_label) {
    try {
        particular_col_array = JSON.parse(particular_col);
        var head = '<thead><tr>';
        var footer = '<tbody><tr class="warning">';
        $.each(particular_col_array, function (index, value) {
            if (index != 'sr_no') {
                if (products != null && index == 'item') {
                    // value = value + '<a data-toggle="modal" href="#new_product" class="btn btn-xs blue-madison pull-right"> <i class="fa fa-plus"> </i> </a>';
                }
                head = head + '<th class="td-c">' + value + '</th>';
                if (index == 'item') {
                    footer = footer + '<td><input type="text" readonly value="' + total_label + '" id="particular_totallabel" class="form-control " placeholder="Enter total label"></td>';
                } else if (index == 'total_amount') {
                    footer = footer + '<td><input type="text" id="particulartotal" class="form-control " readonly=""></td>';
                } else {
                    footer = footer + '<td></td>';
                }
            }
        });
        head = head + '<th class="td-c">Action</th></tr></thead><tbody id="new_particular"></tbody>';
        footer = footer + '<td></td></tr></tbody>';
        _('particular_table').innerHTML = head + footer;
    }
    catch (o) { }
}

function getProducttext(defaultval, type, numrow = 1) {
    if (typeof type === 'undefined') {
        type = '';
    }
    var exist = 0;
    var produ_text = '<td><select style="width:100%;  " required onchange="product_rate(this.value,this,' + "'" + type + "'" + ');"  name="' + type + 'item[]" data-cy="particular_product' + numrow + '" data-placeholder="Type or Select" class="form-control  productselect" ><option value="">Select Product</option>';
    if (products != null) {
        $.each(products, function (value, arr) {
            var selected = '';
            try {
                if (value != '') {
                    if (defaultval == value) {
                        selected = 'selected';
                        exist = 1;
                    }

                    produ_text = produ_text + '<option ' + selected + ' value="' + value + '">' + value + '</option>';
                }
            } catch (o) {
            }
        });
    }
    if (exist == 0) {
        produ_text = produ_text + '<option selected value="' + defaultval + '">' + defaultval + '</option>';
    }
    produ_text = produ_text + '</select></td>';

    return produ_text;
}

function getCGItext(defaultval, type, numrow = 1) {
    if (typeof type === 'undefined') {
        type = '';
    }
    var exist = 0;
    var produ_text = '<td class="col-id-no"><select style="width:100%;  " required onchange="billCode(this.value,' + numrow + ');"  name="' + type + 'bill_code[]" id="bill_code' + numrow + '" data-cy="particular_product' + numrow + '" data-placeholder="Type or Select" class="form-control  productselect" ><option value="">Select Product</option>';
    if (csi_codes != null) {
        $.each(csi_codes, function (value, arr) {
            var selected = '';
            try {
                if (arr.code != '') {
                    if (defaultval == arr.code) {
                        selected = 'selected';
                        exist = 1;
                    }

                    produ_text = produ_text + '<option ' + selected + ' value="' + arr.code + '">' + arr.code + ' ' + arr.title + '</option>';
                }
            } catch (o) {
            }
        });
    }
    if (exist == 0) {
        produ_text = produ_text + '<option selected value="' + defaultval + '">' + defaultval + '</option>';
    }
    produ_text = produ_text + '</select>    <div class="text-center">    <p id="description' + numrow + '" class="lable-heading">    </p>    </div></td>';

    return produ_text;
}

function getCGItextReturns(defaultval, type, numrow = 1) {
    if (typeof type === 'undefined') {
        type = '';
    }
    var exist = 0;
    var produ_text = '<td class="col-id-no" scope="row"><select style="width:100%;" required id="bill_code' + numrow + '" onchange="billCode(this.value,' + numrow + ');"  name="' + type + 'bill_code[]" data-cy="particular_product' + numrow + '" data-placeholder="Type or Select" class="form-control  productselect" >';
    if (csi_codes != null) {
        $.each(csi_codes, function (value, arr) {
            var selected = '';
            try {
                if (arr.code != '') {
                    if (defaultval == arr.code) {
                        selected = 'selected';
                        exist = 1;
                    }

                    produ_text = produ_text + '<option ' + selected + ' value="' + arr.code + '">' + arr.title + ' | ' + arr.code + '</option>';
                }
            } catch (o) {
            }
        });
    }
    if (exist == 0) {
        produ_text = produ_text + '<option selected value="' + defaultval + '">' + defaultval + '</option>';
    }
    produ_text = produ_text + '</select><div class="text-center"><p id="description' + numrow + '" class="lable-heading"></p></div></td>';

    return produ_text;
}

function AddInvoiceParticularRow(defaultval) {
    //var x = document.getElementById("particular_table").rows.length;
    if (typeof defaultval === 'undefined') {
        defaultval = '';
    }
    var rate_exist = false;
    var tax_amt_readonly = '';
    var discount_amt_readonly = '';
    var mainDiv = document.getElementById('new_particular');
    var newDiv = document.createElement('tr');
    var i;
    var row = '';
    var numrow = Number($('input[name="particular_id[]"]').length + 1);
    while (_('pint' + numrow)) {
        numrow = Number(numrow + 1);
    }
    $.each(particular_col_array, function (index, value) {
        if (index != 'sr_no') {
            if (index == 'rate') {
                rate_exist = true;
            }

            if (index == 'item') {
                product_text = getProducttext(defaultval, '', numrow);
                row = row + product_text;
            }
            else if (index == 'total_amount' && rate_exist == true) {
                row = row + '<td><input onblur="calculateamt();" type="text" readonly name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else if (index == 'gst') {
                GST_Drop_down_text = GST_dropdown.replace('row_id', numrow);
                row = row + '<td>' + GST_Drop_down_text + '</td>';
                tax_amt_readonly = 'readonly';
            }
            else if (index == 'tax_amount') {
                row = row + '<td><input onblur="calculateamt();" type="text" ' + tax_amt_readonly + ' name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else if (index == 'discount_perc') {
                row = row + '<td><input onblur="calculateamt();" type="text" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
                discount_amt_readonly = 'readonly';
            }
            else if (index == 'discount') {
                row = row + '<td><input onblur="calculateamt();" type="text" ' + discount_amt_readonly + ' name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else if (index == 'product_expiry_date') {
                row = row + '<td> <input pattern="^(0[1-9]|[1-2][0-9]|3[0-1]) [A-Za-z]{3} [0-9]{4}$" class="form-control form-control-inline date-picker" type="text" name="' + index + '[]" data-cy="particular_' + index + numrow + '"  autocomplete="off" data-date-format="dd M yyyy" placeholder="Expiry Date" /></td>';
                discount_amt_readonly = 'readonly';
            }
            else {
                row = row + getParticularValue(index, numrow);
            }
        }

    });
    row = row + '<input type="hidden" id="pint' + numrow + '" name="pint[]" value="' + numrow + '"><input type="hidden" name="product_gst[]" value="" data-cy="product_gst' + numrow + '"> <input type="hidden" name="particular_id[]" value="0"><td class="td-c"><a data-cy="particular-remove' + numrow + '" href="javascript:;" onclick="setTaxApplicableAmt(' + numrow + ');$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateamt();calculatetax(undefined,undefined,1);" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    newDiv.innerHTML = row;
    mainDiv.appendChild(newDiv);
    setAdvanceDropdown();
    setdatepicker();
}


function AddInvoiceParticularRowConstruction(defaultval) {
    //var x = document.getElementById("particular_table").rows.length;
    if (typeof defaultval === 'undefined') {
        defaultval = '';
    }
    var rate_exist = false;
    var tax_amt_readonly = '';
    var discount_amt_readonly = '';
    var mainDiv = document.getElementById('new_particular');
    var newDiv = document.createElement('tr');
    var i;
    var row = '';
    read_cols = ["current_contract_amount", "previously_billed_percent", "previously_billed_amount", "retainage_amount_previously_withheld", "current_billed_amount", "net_billed_amount", "total_billed", "retainage_amount_for_this_draw", "total_outstanding_retainage"];
    number_cols = ["original_contract_amount", "approved_change_order_amount", "previously_billed_percent", "previously_billed_amount", "current_billed_percent", "retainage_percent", "retainage_amount_previously_withheld", "retainage_release_amount"];
    var numrow = Number($('input[name="particular_id[]"]').length + 1);
    while (_('pint' + numrow)) {
        numrow = Number(numrow + 1);
    }
    $.each(particular_col_array, function (index, value) {
        if (index != 'description') {
            if (index == 'rate') {
                rate_exist = true;
            }
            readonly = '';
            if (read_cols.includes(index)) {
                readonly = 'readonly';
            }



            if (index == 'bill_code') {
                product_text = getCGItext(defaultval, '', numrow);
                row = row + product_text;
            }
            else if (index == 'bill_type') {
                row = row + '<td><select style="width:100%;" onchange="addCaculatedRow(this.value,' + numrow + ');" id="bill_type' + numrow + '" required name="' + index + '[]" data-cy="particular_' + index + numrow + '" data-placeholder="Select Bill Type" class="form-control" ><option value="0">Select..</option><option value="% Complete">% Complete</option><option value="Unit">Unit</option><option value="Calculated">Calculated</option>';
            }
            else {
                row = row + getParticularValue(index, numrow, readonly);
            }
        }

    });
    row = row + '<input type="hidden" id="calculated_perc' + numrow + '" name="calculated_perc[]"><input type="hidden" id="calculated_row' + numrow + '" name="calculated_row[]"><input type="hidden" id="description-hidden' + numrow + '" name="description[]" value="' + numrow + '"><input type="hidden" id="pint' + numrow + '" name="pint[]" value="' + numrow + '"><input type="hidden" name="product_gst[]" value="" data-cy="product_gst' + numrow + '"> <input type="hidden" name="particular_id[]" value="0"><td class="td-c"><a data-cy="particular-remove' + numrow + '" href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateConstruction();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    newDiv.innerHTML = row;
    mainDiv.appendChild(newDiv);
    setAdvanceDropdownContract(numrow);
    setdatepicker();
}

function AddInvoiceParticularRowContract(defaultval) {
    var x = document.getElementById("project_id").value;
    if (x == '') {
        alert("Please select project first");
    } else {
        if (typeof defaultval === 'undefined') {
            defaultval = '';
        }
        var rate_exist = false;
        var tax_amt_readonly = '';
        var discount_amt_readonly = '';
        var mainDiv = document.getElementById('new_particular');
        var newDiv = document.createElement('tr');
        var i;
        var row = '';
        read_cols = ["retainage_amount"];
        var numrow = Number($('input[name="particular_id[]"]').length + 1);
        while (_('pint' + numrow)) {
            numrow = Number(numrow + 1);
        }
        particular_col_array = {
            "bill_code": "Bill Code",
            "bill_type": "Bill Type (% Complete or Unit)",
            "original_contract_amount": "Original Contract Amount",
            "retainage_percent": "Retainage %",
            "retainage_amount": "Retainage Amount",
            "project": "Project",
            "cost_code": "Cost Code",
            "cost_type": "Cost Type",
            "group_code1": "Group Code 1",
            "group_code2": "Group Code 2",
            "group_code3": "Group Code 3",
            "group_code4": "Group Code 4",
            "group_code5": "Group Code 5"
        };
        $.each(particular_col_array, function (index, value) {
            if (index != 'sr_no') {
                readonly = '';
                if (read_cols.includes(index)) {
                    readonly = 'readonly';
                }
                if (index == 'bill_code') {
                    product_text = getCGItextReturns(defaultval, '', numrow);
                    row = row + product_text;
                } else if (index == 'bill_type') {
                    row = row + '<td><select onclick="addCaculatedRow(this.value,' + numrow + ')" style="width:100%;" id="bill_type' + numrow + '" required name="' + index + '[]" data-cy="particular_' + index + numrow + '" data-placeholder="Select Bill Type" class="form-control" ><option value="0">Select..</option><option value="% Complete">% Complete</option><option value="Unit">Unit</option><option value="Calculated">Calculated</option>';
                }
                else if (index == 'original_contract_amount') {
                    row = row + '<td class="td-r"><input id="original_contract_amount' + numrow + '" onblur="calculateRetainage();" numbercom="yes" onkeyup="updateTextView($(this));" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"><a id="add-calc' + numrow + '" style="display:none; padding-top:5px;" href="javascript:;" onclick="OpenAddCaculatedRow(' + numrow + ')">Add calculation</a><a id="remove-calc' + numrow + '" style="display:none;padding-top:5px;" href="javascript:;" onclick="RemoveCaculatedRow(' + numrow + ')">Remove</a><span id="pipe-calc' + numrow + '" style="display:none;margin-left: 4px; color:#859494;"> | </span><a id="edit-calc' + numrow + '" style="display:none;padding-top:5px;padding-left:5px;" href="javascript:;" onclick="editCaculatedRow(' + numrow + ')">Edit</a></td>';
                } else if (index == 'retainage_percent') {
                    row = row + '<td><input id="retainage_percent' + numrow + '" onblur="calculateRetainage();" type="text" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
                } else if (index == 'retainage_amount') {
                    row = row + '<td><input id="retainage_amount' + numrow + '" readonly type="text" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
                }
                else {
                    row = row + getParticularValue(index, numrow, readonly);
                }
            }
        });
        row = row + '<input type="hidden" value=0 id="calculated_perc' + numrow + '" name="calculated_perc[]"><input type="hidden" value=0 id="calculated_row' + numrow + '" name="calculated_row[]"><input type="hidden" id="description-hidden' + numrow + '" name="description[]" value="' + numrow + '"><input type="hidden" id="pint' + numrow + '" name="pint[]" value="' + numrow + '"><input type="hidden" name="product_gst[]" value="" data-cy="product_gst' + numrow + '"> <input type="hidden" name="particular_id[]" value="0"><td class="td-c"><a data-cy="particular-remove' + numrow + '" href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateRetainage();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
        newDiv.innerHTML = row;
        mainDiv.appendChild(newDiv);

        setAdvanceDropdownContract(numrow);
    }
}

function AddInvoiceParticularRowOrder(defaultval) {
    //var x = document.getElementById("particular_table").rows.length;
    if (typeof defaultval === 'undefined') {
        defaultval = '';
    }
    var rate_exist = false;
    var tax_amt_readonly = '';
    var discount_amt_readonly = '';
    var mainDiv = document.getElementById('new_particular');
    var newDiv = document.createElement('tr');
    var i;
    var row = '';
    read_cols = ["retainage_amount"];
    var numrow = Number($('input[name="particular_id[]"]').length + 1);
    while (_('pint' + numrow)) {
        numrow = Number(numrow + 1);
    }
    particular_col_array = {
        "bill_code": "Bill Code",
        "original_contract_amount": "Original Contract Amount",
        "unit": "Unit",
        "rate": "Rate",
        "change_order_amount": "Chnage Order Amount",
        "order_description": "Description",
    };
    $.each(particular_col_array, function (index, value) {
        if (index != 'sr_no') {
            readonly = '';
            if (read_cols.includes(index)) {
                readonly = 'readonly';
            }
            if (index == 'bill_code') {
                product_text = getCGItextReturns(defaultval, '', numrow);
                row = row + product_text;
            }
            else if (index == 'original_contract_amount') {
                row = row + '<td class="td-r"><input readonly id="original_contract_amount' + numrow + '" numbercom="yes" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input" value="0"></td>';
            }
            else if (index == 'unit') {
                row = row + '<td><input id="unit' + numrow + '" onblur="calculateChangeOrder();" type="number" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else if (index == 'rate') {
                row = row + '<td><input id="rate' + numrow + '" onblur="calculateChangeOrder();" type="number" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else if (index == 'change_order_amount') {
                row = row + '<td><input id="change_order_amount' + numrow + '" readonly type="text" name="' + index + '[]" data-cy="particular_' + index + numrow + '" class="form-control  pc-input"></td>';
            }
            else {
                row = row + getParticularValue(index, numrow, readonly);
            }
        }
    });
    row = row + '<input type="hidden" value=0 id="calculated_perc' + numrow + '" name="calculated_perc[]"><input type="hidden" value=0 id="calculated_row' + numrow + '" name="calculated_row[]"><input type="hidden" id="description-hidden' + numrow + '" name="description[]" value="' + numrow + '"><input type="hidden" id="pint' + numrow + '" name="pint[]" value="' + numrow + '"><input type="hidden" name="product_gst[]" value="" data-cy="product_gst' + numrow + '"> <input type="hidden" name="particular_id[]" value="0"><td class="td-c"><a data-cy="particular-remove' + numrow + '" href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateRetainage();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    newDiv.innerHTML = row;
    mainDiv.appendChild(newDiv);

    setAdvanceDropdownContract(numrow);
}

function addCaculatedRow(value, row) {
    if (value == 'Calculated') {
        document.getElementById("add-calc" + row).style.display = 'block';
        document.getElementById("remove-calc" + row).style.display = 'none';
        document.getElementById("pipe-calc" + row).style.display = 'none';
        document.getElementById("edit-calc" + row).style.display = 'none';
        document.getElementById("original_contract_amount" + row).readOnly = true;

        try {
            document.getElementById("approved_change_order_amount" + row).readOnly = true;
            document.getElementById("current_billed_percent" + row).readOnly = true;
            document.getElementById("retainage_percent" + row).readOnly = true;
            document.getElementById("retainage_release_amount" + row).readOnly = true;
            document.getElementById("project" + row).readOnly = true;
            document.getElementById("cost_code" + row).readOnly = true;
            document.getElementById("cost_type" + row).readOnly = true;
            document.getElementById("group_code1" + row).readOnly = true;
            document.getElementById("group_code2" + row).readOnly = true;
            document.getElementById("group_code3" + row).readOnly = true;
            document.getElementById("group_code4" + row).readOnly = true;
            document.getElementById("group_code5" + row).readOnly = true;
        } catch (o) {

        }
    } else {
        document.getElementById("add-calc" + row).style.display = 'none';
        document.getElementById("remove-calc" + row).style.display = 'none';
        document.getElementById("pipe-calc" + row).style.display = 'none';
        document.getElementById("edit-calc" + row).style.display = 'none';
        document.getElementById("original_contract_amount" + row).readOnly = false;
    }
}

function OpenAddCaculatedRow(row) {
    proindexContract(row, row)

}

function proindexContract(ind, select_id) {
    product_index = ind;
    currect_select_dropdwn_id = select_id;
    document.getElementById("panelWrapIdcalc").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapIdcalc").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');
    addRowinCalcTable(ind)
}

function billIndex(ind, select_id, project_id) {
    new_bill_index = ind;

    document.getElementById("panelWrapIdBillCode").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapIdBillCode").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');
    if (project_id != '0' && project_id != '') {
        document.getElementById("_project_id").value = project_id;

    }
    document.getElementById("comefrom").value = select_id;


}
function showupdatebillcode(ind, project_id, code, desc) {

    document.getElementById("updatepanelWrapIdBillCode").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("updatepanelWrapIdBillCode").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');
    document.getElementById("project_id").value = project_id;
    document.getElementById("bill_id").value = ind;
    document.getElementById("bill_code").value = code
    document.getElementById("bill_description").value = desc;;


}

function addRowinCalcTable(ind) {
    console.log(ind);
    clearCalcTable();
    calcRowInt = ind
    var mainDiv = document.getElementById('new_particular1');
    $('input[name="pint[]"]').each(function (indx, arr) {
        var newDiv = document.createElement('tr');
        row = '';
        int = $(this).val();
        bint = Number(int) + 2;
        if (ind != int) {
            oca = document.getElementById('original_contract_amount' + int).value;
            amt = getamt(oca);
            var bill_code = document.getElementById('select2-billcode' + int + '-container').innerHTML;
            var discription = document.getElementById('description' + int).value;
            //bill_code = document.getElementById('bill_code' + bint).value;
            if (amt > 0) {
                row = row + '<td class="td-c"><input type="hidden" name="calc-pint[]" value="' + int + '" id="calc-pint' + int + '"><input type="checkbox" name="calc-checkbox[]" value="' + int + '" id="calc' + int + '" onclick="inputCalcClicked(' + int + ',' + getamt(document.getElementById('original_contract_amount' + int).value) + ')"></td><td class="td-c">' + bill_code + '</td><td class="td-c">' + discription + '</td><td class="td-c">$' + document.getElementById('original_contract_amount' + int).value + '</td>'
            }
        }
        newDiv.innerHTML = row;
        mainDiv.appendChild(newDiv);

    });
}

function RemoveCaculatedRow(row) {
    document.getElementById("original_contract_amount" + row).value = '0';
    document.getElementById("lbl_original_contract_amount" + row).value = '';
    document.getElementById("add-calc" + row).style.display = 'block';
    document.getElementById("remove-calc" + row).style.display = 'none';
    document.getElementById("pipe-calc" + row).style.display = 'none';
    document.getElementById("edit-calc" + row).style.display = 'none';

    calculateRetainage();
    clearCalcTable();
    try {
        _('calculated_perc' + row).value = '';
        _('calculated_row' + row).value = '[]';
        if (invoice_construction == true) {
            calculatedRowSummary();
        } else {
            calculatedRowSummaryContract();
        }
    } catch (o) { }
}

function clearCalcTable() {
    var myNode = document.getElementById("new_particular1");
    myNode.innerHTML = '';
    document.getElementById("calc_amount").value = 0;
    document.getElementById("calc_total").value = 0;
    document.getElementById("calc_perc").value = '';
    document.getElementById("allCheckbox").checked = false;
}

function closeSidePanel() {
    document.getElementById("panelWrapId").style.boxShadow = "none";
    document.getElementById("panelWrapId").style.transform = "translateX(100%)";
    $("#product_create").trigger("reset");
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    return false;
}

function closeSidePanelBillCode() {
    document.getElementById("panelWrapIdBillCode").style.boxShadow = "none";
    document.getElementById("panelWrapIdBillCode").style.transform = "translateX(100%)";
    $("#billcodeform").trigger("reset");
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    return false;
}
function closeSideUpdatePanelBillCode() {
    document.getElementById("updatepanelWrapIdBillCode").style.boxShadow = "none";
    document.getElementById("updatepanelWrapIdBillCode").style.transform = "translateX(100%)";
    $("#billcodeform").trigger("reset");
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    return false;
}

function closeSidePanelcalc() {
    document.getElementById("panelWrapIdcalc").style.boxShadow = "none";
    document.getElementById("panelWrapIdcalc").style.transform = "translateX(100%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    clearCalcTable();
    return false;
}
function addbillcode() {

    var comefrom = document.getElementById("comefrom").value;
    var pid = document.getElementById("_project_id").value;
    var data = $("#billcodeform").serialize();

    var actionUrl = '/merchant/billcode/create';
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: data,
        success: function (data) {


            if (data[2] > 0) {
                if (comefrom == 'create') {



                    html = '<tr><td class="td-c">' + data[2] + '</td><td class="td-c">' + data[0] + '</td><td class="td-c">' + data[1] + '</td>'
                        + '<td class="td-c"><div class="btn-group dropup">' +
                        '<button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">' +
                        ' &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;</button>' +
                        '<ul class="dropdown-menu" role="menu">' +
                        ' <li><a onclick="showupdatebillcode(\'' + data[2] + '\',\'' + pid + '\',\'' + data[0] + '\',\'' + data[1] + '\')"><i class="fa fa-edit"></i> Update</a> </li>' +
                        '<li><a href="#basic" onclick="document.getElementById(' + '\'deleteanchor\'' + ').href =' + '\'/merchant/code/delete/' + pid + '/' + data[2] + '\'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a> </li>' +
                        '</ul></div></td></tr>';

                    document.getElementById('table-bill-code').innerHTML += html;





                } else {
                    try {
                        document.getElementById("description-hidden" + new_bill_index).value = data[1]; // bill desc
                        document.getElementById("description" + new_bill_index).innerHTML = data[1]; // bill desc

                    } catch (o) { }
                    try {
                        document.getElementById("description" + new_bill_index).value = data[1]; // bill desc

                    } catch (o) { }
                    $('select[name="bill_code[]"]').map(function () {
                        this.append(new Option(data[0] + ' |' + data[1], data[0])); // bill code
                    }).get();

                    $('select[name="bill_code[]"]').each(function (indx, arr) {
                        if (indx == new_bill_index - 2) {
                            $(this).val(data[0]);
                            // setAdvanceDropdownContract(new_bill_index);
                        }

                    });

                    if ($('#bill_code' + new_bill_index).find("option[value='" + data[0] + "']").length) {
                        $('#bill_code' + new_bill_index).val(data[0]).trigger('change');
                    }
                }
            }
            closeSidePanelBillCode()
        }
    });

    return false;
}
function addbillcode2() {

    var comefrom = document.getElementById("comefrom").value;
    var pid = document.getElementById("_project_id").value;
    var bill_code_name = document.getElementById("bill_code_name").value;
    var bill_code_description = document.getElementById("bill_code_description").value;
    var data = 'bill_code=' + bill_code_name + '&bill_description=' + bill_code_description + '&project_id=' + pid;
    var actionUrl = '/merchant/billcode/create';
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: data,
        success: function (data) {



        }
    });

    return false;
}
function updatebillcode() {

    var pid = document.getElementById("project_id").value;
    $("#billcodeform").submit(function (e) {
        e.preventDefault();

        var form = $(this);
        var actionUrl = form.attr('action');
        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function (data) {
                alert(data);

                if (data[2] > 0) {








                }
                closeSideUpdatePanelBillCode()
            }
        });

    });
}


function selectDeselectAll(value) {

    $('input[name="calc-checkbox[]"]').each(function (indx, arr) {
        if (value.checked) {
            document.getElementById('calc' + arr.value).checked = true;
        } else {
            document.getElementById('calc' + arr.value).checked = false;
        }
        inputCalcClicked(arr.value, getamt(document.getElementById('original_contract_amount' + arr.value).value))
    });
}

function inputCalcClicked(row, value) {
    var calc_total = 0;

    $('input[name="calc-checkbox[]"]').each(function (indx, arr) {
        if (arr.checked) {
            calc_total = calc_total + getamt(document.getElementById('original_contract_amount' + arr.value).value);
        }
    });

    document.getElementById("calc_total").value = updateTextView1(calc_total)
    if (parseInt(document.getElementById("calc_perc").value) > 0) {
        document.getElementById("calculated_perc" + row).value = updateTextView1(getamt(document.getElementById("calc_perc").value))
        document.getElementById("calc_amount").value = updateTextView1(getamt(document.getElementById("calc_perc").value) * getamt(calc_total) / 100)
    } else {
        document.getElementById("calc_amount").value = updateTextView1(calc_total);
    }

}

function calculatePercContract(value) {
    calc_total = document.getElementById("calc_total").value
    document.getElementById("calculated_perc" + calcRowInt).value = value
    document.getElementById("calc_amount").value = updateTextView1(getamt(value) * getamt(calc_total) / 100)
}

function setOriginalContractAmount() {

    try {
        document.getElementById("original_contract_amount" + calcRowInt).value = updateTextView1(getamt(document.getElementById("calc_amount").value));
    } catch (o) {

    }
    document.getElementById("lbl_original_contract_amount" + calcRowInt).innerHTML = updateTextView1(getamt(document.getElementById("calc_amount").value));
    // document.getElementById("original_contract_amount" + calcRowInt).readOnly = true;
    try {
        document.getElementById("approved_change_order_amount" + calcRowInt).readOnly = true;
        document.getElementById("current_billed_percent" + calcRowInt).readOnly = true;
        document.getElementById("retainage_percent" + calcRowInt).readOnly = true;
        document.getElementById("retainage_release_amount" + calcRowInt).readOnly = true;
        document.getElementById("project" + calcRowInt).readOnly = true;
        document.getElementById("cost_code" + calcRowInt).readOnly = true;
        document.getElementById("cost_type" + calcRowInt).readOnly = true;
        document.getElementById("group_code1" + calcRowInt).readOnly = true;
        document.getElementById("group_code2" + calcRowInt).readOnly = true;
        document.getElementById("group_code3" + calcRowInt).readOnly = true;
        document.getElementById("group_code4" + calcRowInt).readOnly = true;
        document.getElementById("group_code5" + calcRowInt).readOnly = true;
    } catch (o) {

    }
    document.getElementById("add-calc" + calcRowInt).style.display = 'none';
    document.getElementById("remove-calc" + calcRowInt).style.display = 'inline-block';
    document.getElementById("edit-calc" + calcRowInt).style.display = 'inline-block';
    document.getElementById("pipe-calc" + calcRowInt).style.display = 'inline-block';
    document.getElementById("edit-calc" + calcRowInt).innerHTML = 'Edit';
    calcRowArray = [];
    $('input[name="calc-pint[]"]').each(function (indx, arr) {
        int = $(this).val();
        var checkBox = document.getElementById("calc" + int);
        if (checkBox.checked == true) {
            calcRowArray.push(parseInt(int))
        }
    });
    calcRowArray = JSON.stringify(calcRowArray);
    console.log(calcRowInt);
    document.getElementById("calculated_row" + calcRowInt).value = calcRowArray;
    document.getElementById("calculated_perc" + calcRowInt).value = parseInt(document.getElementById("calc_perc").value);
    closeSidePanelcalc()
    clearCalcTable();
    calculateRetainage();
    try {
        document.getElementById("exicon" + calcRowInt).style.display = 'inline-block';
    } catch (o) { }
    try {
        if (invoice_construction == true) {
            calculateConstruction();
        } else {
            calculatedRowSummaryContract()
        }
    } catch (o) { }
    return false;
}

function editCaculatedRow(row) {
    OpenAddCaculatedRow(row)
    document.getElementById("calc_perc").value = document.getElementById("calculated_perc" + row).value
    calc_json = document.getElementById("calculated_row" + row).value;
    calc_json = JSON.parse(calc_json)

    for (const element of calc_json) {
        amount_value = getamt(document.getElementById("original_contract_amount" + element).value)
        document.getElementById("calc" + element).checked = true
        inputCalcClicked(element, amount_value)
    }

}

function setProductTaxation(value) {
    document.getElementById("product_taxation_type").value = value;
}

function setdatepicker() {
    try {
        $('.date-picker').datepicker({
            rtl: Swipez.isRTL(),
            orientation: "left",
            autoclose: true,
            todayHighlight: true
        });
    } catch (o) {
        //console.log(o.Message);
    }
}
function setdatetimepiker() {

    $(".form_datetime1").datetimepicker({
        format: "dd M yyyy h:i a",
        autoclose: true,
        todayBtn: true,

        minuteStep: 10
    });

}


function removeArrayVal(ar, vl) {
    var index = ar.indexOf(vl);
    if (index !== -1) {
        ar.splice(index, 1);
    }
    return ar;
}

function AddSecRow(column_array, type, hasdatetime) {

    try {
        columns = JSON.parse(column_array);
        var rate_exist = false;
        var tax_amt_readonly = '';
        var discount_amt_readonly = '';
        var mainDiv = document.getElementById('new_sec_' + type);
        var newDiv = document.createElement('tr');
        var i;
        var row = '';
        input_type = 'text';
        var number_cols = ["qty", "rate", "mrp", "product_expiry_date", "product_number", "gst", "tax_amount", "discount_perc", "discount", "total_amount", "amount", "charge"];
        var date_cols = ["booking_date", "journey_date", "from_date", "to_date"];
        var all_cols = ["booking_date", "journey_date", "from_date", "to_date", "qty", "rate", "mrp", "product_expiry_date", "product_number", "gst", "tax_amount", "discount_perc", "discount", "total_amount", "amount", "charge", "name", "type", "from", "to", "item", "description", "information"];
        var numrow = 1;
        var hastime = 'date-picker';
        var minwidth = "110";
        try {

            if (hasdatetime.toString() == '1') {
                minwidth = '160';
                hastime = 'date form_datetime1 date-set';
            }

        } catch (e) { }
        try {
            while (_('sec' + type + numrow)) {
                numrow = Number(numrow + 1);
            }
        } catch (o) { }
        $.each(columns, function (index, value) {
            input_type = 'text';

            if (index != 'sr_no') {
                if (index == 'rate' || index == 'amount') {
                    rate_exist = true;
                }

                if (number_cols.includes(index)) {

                    input_type = 'number" step="0.01';
                }

                if (index == 'item') {
                    product_text = getProducttext('', 'sec_');
                    row = row + product_text;
                }
                else if (index == 'total_amount' && rate_exist == true) {
                    row = row + '<td><input onblur="calculateSecamt(' + "'" + type + "'" + ');"  data-cy="travel-' + type + '-' + index + numrow + '" type="text" readonly name="sec_' + index + '[]" class="form-control  pc-input"></td>';
                }
                else if (index == 'gst') {
                    row = row + '<td>' + GST_dropdown_SEC + '</td>';
                    tax_amt_readonly = 'readonly';
                }
                else if (index == 'discount_perc') {
                    var id = "id=" + type + "_sec_" + index + "[]";
                    row = row + '<td><input onblur="calculateSecamt(' + "'" + type + "'" + ');" ' + id + 'type="' + input_type + ' data-cy="travel-' + type + '-' + index + numrow + '" name="sec_' + index + '[]" class="form-control  pc-input"></td>';
                    discount_amt_readonly = 'readonly';
                }
                else if (index == 'product_expiry_date') {
                    var id = "id=" + type + "_sec_" + index + "[]";
                    row = row + '<td> <input pattern="^(0[1-9]|[1-2][0-9]|3[0-1]) [A-Za-z]{3} [0-9]{4}$" ' + id + ' class="form-control form-control-inline date-picker" type="text" data-cy="travel-' + type + '-' + index + numrow + '" name="sec_' + index + '[]"  autocomplete="off" data-date-format="dd M yyyy" placeholder="Expiry Date" /></td>';
                }
                else if (index == 'discount') {
                    var id = "id=" + type + "_sec_" + index + "[]";
                    row = row + '<td><input ' + discount_amt_readonly + ' onblur="calculateSecamt(' + "'" + type + "'" + ');" ' + id + ' type="text" data-cy="travel-' + type + '-' + index + numrow + '" name="sec_' + index + '[]" class="form-control  pc-input"></td>';
                }
                else if (index == 'charge' || index == 'amount' || index == 'rate' || index == 'qty') {
                    var id = '';
                    if (type == 'hb' && index == 'qty') {
                        id = 'id="hbqty' + datecolcount + '"';
                    }
                    row = row + '<td><input onblur="calculateSecamt(' + "'" + type + "'" + ');" ' + id + ' type="' + input_type + ' data-cy="travel-' + type + '-' + index + numrow + '" name="sec_' + index + '[]" class="form-control  pc-input"></td>';
                }
                else if (date_cols.includes(index)) {
                    var event = '';
                    if (type == 'hb' && index == 'from_date') {
                        event = 'id="hbfromdate' + datecolcount + '" onchange="calculateDateUnit(' + datecolcount + ');"';
                    }
                    if (type == 'hb' && index == 'to_date') {
                        event = 'id="hbtodate' + datecolcount + '" onchange="calculateDateUnit(' + datecolcount + ');"';
                    }
                    row = row + '<td><input  type="text" name="sec_' + index + '[]" ' + event + ' class="form-control ' + hastime + '" data-cy="travel-' + type + '-' + index + numrow + '" style="min-width:' + minwidth + 'px !important;" autocomplete="off" data-date-format="dd M yyyy"></td>';
                }
                else {
                    row = row + '<td><input type="' + input_type + '" name="sec_' + index + '[]" data-cy="travel-' + type + '-' + index + numrow + '" class="form-control  pc-input" maxlength="500"></td>';
                }
                all_cols = removeArrayVal(all_cols, index);
            }

        });
        $.each(all_cols, function (ai, av) {
            row = row + '<input type="hidden" name="sec_' + av + '[]" data-cy="travel-' + type + '-' + av + numrow + '" value="">';
        });
        row = row + '<input type="hidden" id="sec' + type + numrow + '" name="sec_exist_id[]" value="0"><input type="hidden" name="sec_product_gst[]"> <input type="hidden" name="sec_type_value[]" value="' + type + '"><td class="td-c"><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateSecamt(' + "'" + type + "'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
        newDiv.innerHTML = row;
        mainDiv.appendChild(newDiv);
        if (type == 'hb') {
            datecolcount++;
        }
        if (hasdatetime.toString() == '1') {

            setdatetimepiker();
        } else {
            setdatepicker();
        }



    } catch (o) {
        //console.log(o.Message);
    }
    setAdvanceDropdown();
}

function calculateDateUnit(id) {
    try {
        from_date = '';
        to_date = '';

        if (_('hbfromdate' + id).value) {
            from_date = _('hbfromdate' + id).value;
        }
        if (_('hbtodate' + id).value) {
            to_date = _('hbtodate' + id).value;
        }

        if (from_date != '' && to_date != '') {
            var date1 = new Date(from_date);
            var date2 = new Date(to_date);
            var Difference_In_Time = date2.getTime() - date1.getTime();
            _('hbqty' + id).value = Difference_In_Time / (1000 * 3600 * 24);
            calculateSecamt('hb');
        }

    }
    catch (o) {
    }

}

function product_rate(productname, val, type) {
    if (typeof type === 'undefined') {
        type = '';
    }
    if (productname == 'Add product') {
        _('add_prod').click();
        return;
    }

    var int = $('[name="' + type + 'item[]"]').index(val);
    if (products == null) {
        return false;
    }
    if (products[productname]) {
        try {
            $('input[name="' + type + 'rate[]"]')[int].value = products[productname].price;
        } catch (o) {
        }
        try {
            if ($('input[name="' + type + 'amount[]"]')[int].is(":hidden")) { } else {
                $('input[name="' + type + 'amount[]"]')[int].value = products[productname].price;
            }
        } catch (o) {
        }
        try {
            $('input[name="' + type + 'sac_code[]"]')[int].value = products[productname].sac_code;
        } catch (o) {
        }
        try {
            $('input[name="' + type + 'qty[]"]')[int].value = '1';
            try {
                var qint = $('input[name="pint[]"]')[int].value;
                if (products[productname].enable_stock == 1) {
                    $('#stkqty' + qint).html('Stock <span id="qtyavailable' + qint + '"></span><br>Avb. Stock <span id="qtycurrent' + qint + '"></span>');
                    $('#qtyavailable' + qint).html(products[productname].available_stock);
                    $('#qtycurrent' + qint).html(products[productname].available_stock - 1);
                } else {
                    $('#stkqty' + qint).html('');
                }
            } catch (o) {
            }


        } catch (o) {
        }
        try {
            $('input[name="' + type + 'unit_type[]"]')[int].value = products[productname].unit_type;
        } catch (o) {
        }
        try {
            $('input[name="' + type + 'total_amount[]"]')[int].value = products[productname].price;
        } catch (o) {
        }
        try {
            $('input[name="' + type + 'mrp[]"]')[int].value = products[productname].mrp;
        } catch (o) {
        }
        try {
            var expiry_product_date = products[productname].product_expiry_date;

            var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var currentdate = new Date(expiry_product_date);
            var day = currentdate.getDate();
            var month = currentdate.getMonth() + 1;
            var year = currentdate.getFullYear();
            var fulldate = ("0" + day).slice(-2) + ' ' + monthNames[month] + ' ' + year;
            if (expiry_product_date != '') {
                $('input[name="' + type + 'product_expiry_date[]"]')[int].value = fulldate;
            } else {
                $('input[name="' + type + 'product_expiry_date[]"]')[int].value = '';
            }

        } catch (o) {
        }
        try {
            $('input[name="' + type + 'product_number[]"]')[int].value = products[productname].product_number;
        } catch (o) {
        }

        try {
            product_gst = products[productname].gst_percent;
            $('input[name="' + type + 'product_gst[]"]')[int].value = product_gst;
            try {
                $('[name="' + type + 'gst[]"]')[int].value = product_gst;
            } catch (o) {
                console.log(o);
            }
            setGstTax();
        } catch (o) {
        }

        //setproducttax(productname);

        calculateamt(type);
        if (type != '') {
            calculateSecamt();
        }
        calculatetax();
    }

}

function setproducttax(productname) {
    d = products[productname].product_taxes;
    $.each(d, function (index, value) {
        exist = checkTaxExist(value);
        if (exist == false) {
            AddInvoiceTax();
            var taxindex = Number($('input[name="tax_percent[]"]').length - 1);
            $('select[name="tax_id[]"]')[taxindex].value = value;
        }
    });
}

function getParticularValue(name, numrow = 1, readonly = '') {
    tdcol = '';
    totalfunction = '';
    input_type = 'text';
    maxlength = 45;
    qtytext = '';
    if (name == 'description') {
        maxlength = 250;
    }
    var value = '';
    if (name == 'project') {
        value = $("#project_id option:selected").text();
        if (value == 'Select project') {
            value = '';
        }
        else {
            try {
                value = value.split("|")[1].replace(' ', '');

            } catch (o) {

            }
        }

    }
    if (name == 'rate' || name == 'qty' || name == 'taxin' || name == 'tax_amount' || name == 'discount_perc' || name == 'discount' || name == 'total_amount') {
        totalfunction = 'onblur="calculateamt();calculatetax();"';
        input_type = 'number" max="100000000" step="0.01';
    }

    number_cols = ["original_contract_amount", "approved_change_order_amount", "previously_billed_percent", "previously_billed_amount", "current_billed_percent", "retainage_percent", "retainage_amount_previously_withheld", "retainage_release_amount"];
    if (number_cols.includes(name)) {
        input_type = 'text" numbercom="yes" onkeyup="updateTextView($(this));" onblur="calculateConstruction();" step="0.01';
    }

    if (name == 'original_contract_amount') {
        qtytext = '<div class="pull-right"><a id="add-calc' + numrow + '" style="display:none; padding-top:5px;" href="javascript:;" onclick="OpenAddCaculatedRow(' + numrow + ')">Add calculation</a><a id="remove-calc' + numrow + '" style="display:none;padding-top:5px;float: left; margin-right: 5px;" href="javascript:;" onclick="RemoveCaculatedRow(' + numrow + ')">Remove</a><span style="display: none; margin-right: 4px; color: rgb(133, 148, 148);" id="pipe-calc' + numrow + '"> | </span><a id="edit-calc' + numrow + '" style="display:none;padding-top:5px;" href="javascript:;" onclick="editCaculatedRow(' + numrow + ')">Edit</a></div>';
    }
    if (name == 'qty') {
        qtytext = '<span id="stkqty' + numrow + '" class="help-block "></span>';
    }
    if (particular_values != '') {
        var pv = JSON.parse(particular_values);
        if (pv[name]) {
            if (Array.isArray(pv[name])) {
                tdcol = '<td><select name="' + name + '[]" ' + totalfunction + ' data-cy="particular_' + name + numrow + '" class="form-control  pc-input" style="width:auto">';
                $.each(pv[name], function (index, value) {
                    tdcol = tdcol + '<option value="' + value + '">' + value + '</option>';
                });
                tdcol = tdcol + '</select></td>';
            } else {

                tdcol = '<td><input id="' + name + numrow + '" type="text" ' + readonly + ' name="' + name + '[]"  ' + totalfunction + ' value="' + pv[name] + '" data-cy="particular_' + name + numrow + '" class="form-control  pc-input">' + qtytext + '</td>';
            }
        } else {
            tdcol = '<td><input id="' + name + numrow + '"  type="text" ' + readonly + ' maxlength="' + maxlength + '" name="' + name + '[]"   ' + totalfunction + ' data-cy="particular_' + name + numrow + '" class="form-control  pc-input">' + qtytext + '</td>';
        }
    } else {

        tdcol = '<td><input  id="' + name + numrow + '" type="' + input_type + '" ' + readonly + ' maxlength="' + maxlength + '" value="' + value + '" name="' + name + '[]"   ' + totalfunction + ' data-cy="particular_' + name + numrow + '" class="form-control  pc-input">' + qtytext + '</td>';
    }
    return tdcol;
}
function getInputArrayValue(name, int, input) {
    if ($('[name="' + name + '[]"]')[int]) {
        value = Number($('[name="' + name + '[]"]')[int].value);
        if (value > 0) {
            return value;
        } else {
            return '0';
        }
    } else {
        return false;
    }
}

function getamt(val) {
    Str = val.toString();
    return Number(Str.replaceAll(',', ''));
}

function calculateConstruction() {
    var grand_total = 0;
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();

        document.getElementById('current_contract_amount' + int).value = updateTextView1(getamt(document.getElementById('original_contract_amount' + int).value) + getamt(document.getElementById('approved_change_order_amount' + int).value));
        document.getElementById('current_billed_amount' + int).value = updateTextView1(getamt(document.getElementById('current_contract_amount' + int).value) * getamt(document.getElementById('current_billed_percent' + int).value) / 100);
        document.getElementById('total_billed' + int).value = updateTextView1(getamt(document.getElementById('current_billed_amount' + int).value) + getamt(document.getElementById('previously_billed_amount' + int).value) + getamt(document.getElementById('stored_materials' + int).value));
        document.getElementById('retainage_amount_for_this_draw' + int).value = updateTextView1(getamt(document.getElementById('total_billed' + int).value) * getamt(document.getElementById('retainage_percent' + int).value) / 100);
        document.getElementById('total_outstanding_retainage' + int).value = document.getElementById('retainage_amount_for_this_draw' + int).value;
        document.getElementById('net_billed_amount' + int).value = updateTextView1(getamt(document.getElementById('current_billed_amount' + int).value) + getamt(document.getElementById('stored_materials' + int).value) - getamt(document.getElementById('retainage_amount_for_this_draw' + int).value));

        grand_total = grand_total + getamt(document.getElementById('net_billed_amount' + int).value);
    });

    calculatedRowSummary();
    var grand_total = 0;
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();

        grand_total = grand_total + getamt(document.getElementById('net_billed_amount' + int).value);
    });
    document.getElementById('totalamount').value = updateTextView1(grand_total);
    document.getElementById('grandtotal').value = updateTextView1(grand_total);
}

function calculatedRowSummary() {
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();
        bill_type = _('bill_type' + int).value;
        if (bill_type == 'Calculated') {
            rows = _('calculated_row' + int).value;
            per = _('calculated_perc' + int).value;
            let arr1 = JSON.parse(rows);
            ocamount = 0;
            kbamount = 0;
            acoamount = 0;
            ccamount = 0;
            pbamount = 0;
            tb = 0;
            rpwamount = 0;
            rdamount = 0;
            rramount = 0;
            rdamount = 0;
            tormount = 0;
            netamount = 0;
            $(arr1).each(function (ri, rv) {
                try {
                    ocamount = ocamount + getamt(_('original_contract_amount' + rv).value);
                    kbamount = kbamount + getamt(_('current_billed_amount' + rv).value);
                    acoamount = acoamount + getamt(_('approved_change_order_amount' + rv).value);
                    ccamount = ccamount + getamt(_('current_contract_amount' + rv).value);
                    pbamount = pbamount + getamt(_('previously_billed_amount' + rv).value);
                    tb = tb + getamt(_('total_billed' + rv).value);
                    rpwamount = rpwamount + getamt(_('retainage_amount_previously_withheld' + rv).value);
                    rdamount = rdamount + getamt(_('retainage_amount_for_this_draw' + rv).value);
                    rramount = rramount + getamt(_('retainage_release_amount' + rv).value);
                    tormount = tormount + getamt(_('total_outstanding_retainage' + rv).value);
                    netamount = netamount + getamt(_('net_billed_amount' + rv).value);
                } catch (o) {
                    calculated_row = _('calculated_row' + int).value;
                    _('calculated_row' + int).value = calculated_row.replace(',"' + rv + '"', '');
                    calculated_row = _('calculated_row' + int).value;
                    _('calculated_row' + int).value = calculated_row.replace('"' + rv + '"', '');
                }
            });
            _('original_contract_amount' + int).value = updateTextView1(ocamount * per / 100);
            _('current_billed_amount' + int).value = updateTextView1(kbamount * per / 100);
            _('net_billed_amount' + int).value = updateTextView1(netamount * per / 100);



            _('approved_change_order_amount' + int).value = updateTextView1(acoamount * per / 100);
            _('current_contract_amount' + int).value = updateTextView1(ccamount * per / 100);
            _('previously_billed_amount' + int).value = updateTextView1(pbamount * per / 100);
            _('total_billed' + int).value = updateTextView1(tb * per / 100);
            _('retainage_amount_previously_withheld' + int).value = updateTextView1(rpwamount * per / 100);
            _('retainage_amount_for_this_draw' + int).value = updateTextView1(rdamount * per / 100);
            _('retainage_release_amount' + int).value = updateTextView1(rramount * per / 100);
            _('total_outstanding_retainage' + int).value = updateTextView1(tormount * per / 100);

            try {
                _('original_contract_amount_lb' + int).innerHTML = updateTextView1(ocamount * per / 100);
                _('current_billed_amount_lb' + int).innerHTML = updateTextView1(kbamount * per / 100);
                _('net_billed_amount_lb' + int).innerHTML = updateTextView1(netamount * per / 100);
                _('previously_billed_percent_lb' + int).innerHTML = '';
                _('current_billed_percent_lb' + int).innerHTML = '';
                _('retainage_percent_lb' + int).innerHTML = '';
                _('project_lb' + int).innerHTML = '';
                _('total_outstanding_retainage_lb' + int).innerHTML = updateTextView1(tormount * per / 100);
                _('retainage_release_amount_lb' + int).innerHTML = updateTextView1(rramount * per / 100);
                _('retainage_amount_for_this_draw_lb' + int).innerHTML = updateTextView1(rdamount * per / 100);
                _('retainage_amount_previously_withheld_lb' + int).innerHTML = updateTextView1(rpwamount * per / 100);
                _('total_billed_lb' + int).innerHTML = updateTextView1(tb * per / 100);
                _('previously_billed_amount_lb' + int).innerHTML = updateTextView1(pbamount * per / 100);
                _('current_contract_amount_lb' + int).innerHTML = updateTextView1(ccamount * per / 100);
                _('approved_change_order_amount_lb' + int).innerHTML = updateTextView1(acoamount * per / 100);
            } catch (o) {

            }
        }
    });

}

function calculatedRowSummaryContract() {
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();
        bill_type = _('bill_type' + int).value;
        if (bill_type == 'Calculated') {
            rows = _('calculated_row' + int).value;
            per = _('calculated_perc' + int).value;
            let arr1 = JSON.parse(rows);
            ocamount = 0;
            kbamount = 0;
            $(arr1).each(function (ri, rv) {
                try {
                    ocamount = ocamount + getamt(_('original_contract_amount' + rv).value);
                } catch (o) {
                    calculated_row = _('calculated_row' + int).value;
                    _('calculated_row' + int).value = calculated_row.replace(',"' + rv + '"', '');
                    calculated_row = _('calculated_row' + int).value;
                    _('calculated_row' + int).value = calculated_row.replace('"' + rv + '"', '');
                }

            });
            _('original_contract_amount' + int).value = updateTextView1(ocamount * per / 100);
        }
    });

}

function calculateRetainage() {
    try {
        $('input[name="pint[]"]').each(function (indx, arr) {
            int = $(this).val();
            document.getElementById('retainage_amount' + int).value = updateTextView1(getamt(document.getElementById('retainage_percent' + int).value) * getamt(document.getElementById('original_contract_amount' + int).value) / 100)
                ;
        });
    }
    catch (o) {

    }

    var total = 0;
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();
        total = total + getamt(document.getElementById('original_contract_amount' + int).value)
    });
    calculatedRowSummaryContract()
    try {
        document.getElementById('particulartotal1').value = updateTextView1(total);
    }
    catch (o) {

    }
    document.getElementById('contract_amount').value = updateTextView1(total);
}

function calculateChangeOrder() {
    try {
        $('input[name="pint[]"]').each(function (indx, arr) {
            int = $(this).val();
            document.getElementById('change_order_amount' + int).value = updateTextView1(getamt(document.getElementById('unit' + int).value) * getamt(document.getElementById('rate' + int).value))
                ;
        });
    }
    catch (o) {

    }

    var total = 0;
    $('input[name="pint[]"]').each(function (indx, arr) {
        int = $(this).val();
        total = total + getamt(document.getElementById('change_order_amount' + int).value)
    });
    try {
        document.getElementById('particulartotal1').value = updateTextView1(total);
    }
    catch (o) {

    }
    document.getElementById('total_change_order_amount').value = updateTextView1(total);
}


function calculateSecamt(type) {
    if (typeof type === 'undefined') {
        type = '';
    }
    var sec_grand_total = 0;
    var num = $('input[name="sec_type_value[]"]').length;
    $('input[name="sec_calculate[]"]').each(function (indx, arr) {
        $(this).val('0.00');
    });
    total_amount = 0;
    for (var i = 0; i < num; i++) {
        amount = 0;
        sec_type = $('input[name="sec_type_value[]"]')[i].value;
        price = getInputArrayValue('sec_rate', i, 'input');
        qty = getInputArrayValue('sec_qty', i, 'input');
        taxin = getInputArrayValue('sec_gst', i, '');
        tax_amount = getInputArrayValue('sec_tax_amount', i, 'input');
        discount_perc = getInputArrayValue('sec_discount_perc', i, 'input');
        discount = getInputArrayValue('sec_discount', i, 'input');
        amt = getInputArrayValue('sec_amount', i, 'input');
        charge = getInputArrayValue('sec_charge', i, 'input');

        if (price) {
            if (qty > 0) {
                amount = Number(price * qty);
            } else {
                amount = Number(price);
            }

            if ($('input[id="' + type + '_sec_discount[]"]').is('[readonly]')) {
                if (discount_perc == 0) {
                    discount = 0;
                    try {
                        $('input[name="sec_discount[]"]')[i].value = roundAmount(Number(discount));
                    } catch (o) {
                    }
                }
            }

            if (discount_perc > 0) {
                var discountAmount = (amount * (discount_perc / 100));
                amount = Number(amount - discountAmount);

                try {
                    $('input[name="sec_discount[]"]')[i].value = roundAmount(Number(discountAmount));
                } catch (o) {
                }
            } else {
                amount = Number(amount - discount);
            }
        }
        if (amt > 0) {
            amount = amt;

            if ($('input[id="' + type + '_sec_discount[]"]').is('[readonly]')) {
                if (discount_perc == 0) {
                    discount = 0;
                    try {
                        $('input[name="sec_discount[]"]')[i].value = roundAmount(Number(discount));
                    } catch (o) {
                    }
                }
            }

            if (discount_perc > 0) {
                var discountAmount = (amount * (discount_perc / 100));
                amount = Number(amount - discountAmount);

                try {
                    $('input[name="sec_discount[]"]')[i].value = roundAmount(Number(discountAmount));
                } catch (o) {

                }
            } else {
                amount = Number(amount - discount);
            }
        }

        if (charge != "") {
            if (sec_type == 'tc') {
                amount = Number(amount) - Number(charge);
            } else {
                amount = Number(amount) + Number(charge);
            }
        }
        try {
            $('input[name="sec_total_amount[]"]')[i].value = roundAmount(amount);
        } catch (o) {
        }

        sectotal = getAmount(sec_type + '_sectotalamt');
        sectotal = Number(sectotal) + Number(amount);
        if (sec_type == 'tc') {
            sec_grand_total = sec_grand_total - amount;
        } else {
            sec_grand_total = sec_grand_total + amount;
        }

        _(sec_type + '_sectotalamt').value = roundAmount(sectotal);
    }
    _('sec_total').value = roundAmount(sec_grand_total);
    calculateamt('sec_');
    calculategrandtotal();
    calculateVendorCommission();


}

function getAmount(id) {
    var gamt = Number(_(id).value);
    if (gamt > 0) {
        return roundAmount(gamt);
    } else {
        return 0;
    }
}

function calculateamt(type) {
    if (typeof type === 'undefined') {
        type = '';
    }

    var particular_total = 0;
    var num = $('input[name="total_amount[]"]').length;
    total_amount = 0;
    if (type == '') {
        for (var i = 0; i < num; i++) {
            var amount = 0;
            var tax_amount = 0;
            price = getInputArrayValue(type + 'rate', i, 'input');
            qty = getInputArrayValue(type + 'qty', i, 'input');

            try {
                if (qty > 0) {
                    var qint = $('input[name="pint[]"]')[i].value;
                    if (_('qtycurrent' + qint)) {
                        available_stock = Number(_('qtyavailable' + qint).innerHTML);
                        avqty = available_stock - qty;
                        $('#qtycurrent' + qint).html(roundAmount(avqty));
                        if (avqty > 0) {
                            document.getElementById('qtycurrent' + qint).style.color = "#737373";
                        } else {
                            document.getElementById('qtycurrent' + qint).style.color = "red";
                        }
                    }
                }
            } catch (o) { }

            taxin = getInputArrayValue(type + 'gst', i, '');
            tax_amount = getInputArrayValue(type + 'tax_amount', i, 'input');
            discount_perc = getInputArrayValue(type + 'discount_perc', i, 'input');
            discount = getInputArrayValue(type + 'discount', i, 'input');
            if (price) {
                if (qty) {
                    amount = Number(price * qty);
                } else {
                    amount = Number(price);
                }

                if ($('input[name="discount[]"]').is('[readonly]')) {
                    if (discount_perc == 0) {
                        discount = 0;
                        try {
                            $('input[name="discount[]"]')[i].value = roundAmount(Number(discount));
                        } catch (o) {
                        }
                    }
                }

                if (discount_perc > 0) {
                    var discountAmount = (amount * (discount_perc / 100));
                    amount = Number(amount - discountAmount);

                    try {
                        $('input[name="discount[]"]')[i].value = roundAmount(Number(discountAmount));
                    } catch (o) {
                    }
                } else {
                    amount = Number(amount - discount);
                }

                $('input[name="' + type + 'total_amount[]"]')[i].value = roundAmount(amount);
            }

            amount = Number($('input[name="' + type + 'total_amount[]"]')[i].value);

            if (taxin) {
                if (taxin > 0) {
                    tax_amount = calculateTax(amount, taxin);
                } else {
                    tax_amount = 0;
                }

                try {
                    $('input[name="tax_amount[]"]')[i].value = roundAmount(tax_amount);
                } catch (o) {
                }
            }


            particular_total = Number(particular_total + amount);
        }
    }

    particular_total = roundAmount(particular_total);

    if (getInputArrayValue('gst', 0, 'input')) { //old code gst 'select'
        if (type != 'undefined') {
            setGstTax();
        }
    } else if (getInputArrayValue('product_gst', 0, 'input')) {  // added by darshana
        if (type != 'undefined') {
            setGstTax();
        }
    }
    else {
        setGstTax();
        try {
            $('input[name="tax_applicable[]"]').map(function () {
                this.value = (particular_total == 0) ? this.value : particular_total;
            }).get();
            calculatetax();
        } catch (o) {
        }
        try {
            $('input[name="deduct_applicable[]"]').map(function () {
                this.value = particular_total;
            }).get();
        } catch (o) {
        }
    }
    try {
        var template_type = _('template_type').value;
        if (template_type != 'scan') {
            if (type == '') {
                _('particulartotal').value = particular_total;
            }
        }
    } catch (o) {
    }
    calculategrandtotal();
    calculateVendorCommission();

}

function roundAmount(amt) {
    return (Math.round(100 * amt) / 100).toFixed(2);
}
function calculateTax(amount, tax) {
    var tax_amount = Number(amount * tax / 100);
    return roundAmount(tax_amount);
}
function getIntVal(val) {
    if (val > 0) {
        return Number(val);
    } else {
        return 0;
    }
}
function AddInvoiceTax(tax) {
    if (typeof tax === 'undefined') {
        tax = '';
    }
    var taxtext = _('tax_div').innerHTML;
    //var numrow = Number($('input[name="tax_percent[]"]').length + 1);
    var numrow = Date.now();
    taxtext = taxtext.replace('taxselectdefault"', 'taxselect" data-cy="invoice_tax_id' + numrow + '"');
    if (tax != '') {
        taxtext = taxtext.replace('value="' + tax + '"', 'selected value="' + tax + '"');
        taxtext = taxtext.replace('value="' + tax + '"', 'selected value="' + tax + '"');
    }
    totalcostamt = _('totalcostamt').value;
    var mainDiv = document.getElementById('new_tax');
    var newDiv = document.createElement('tr');
    newDiv.setAttribute("id", 'tax_tr' + numrow);
    newDiv.classList.add('default_taxes');
    newDiv.innerHTML = '<td>' + taxtext + '<span class="help-block tax-note" data-cy="note-for-tax-calculation' + numrow + '"></span></td><td><input type="number" step="0.01" max="100" name="tax_percent[]" data-cy="invoice_tax_percent' + numrow + '" class="form-control " readonly ></td><td><input type="text" name="tax_applicable[]" data-cy="invoice_tax_applicable' + numrow + '" onblur="calculatetax();" value="' + totalcostamt + '" class="form-control " ></td><td><input type="text" name="tax_amt[]" data-cy="invoice_tax_amt' + numrow + '" value="0" class="form-control "  readonly><input type="hidden" name="tax_detail_id[]" value="0"/><input type="hidden" name="tax_type[]" value="0" data-cy="invoice_tax_type' + numrow + '"/><input type="hidden" name="tax_calculated_on[]" value="0" data-cy="invoice_tax_calculated_on' + numrow + '"/></td><td class="td-c"><a href="javascript:;" onClick="setProductGST(' + numrow + ');$(this).closest(' + "'tr'" + ').remove();calculatetax();" data-cy="invoice_tax_remove' + numrow + '" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);
    setTaxDropdown();
}

function addGrossSaleRow(type = '') {
    var mainDiv = document.getElementById('tb_gross_sale');
    var newDiv = document.createElement('tr');
    if (type == 'nonbrand') {
        newDiv.innerHTML = '<td class="td-c"><input type="text" required=""  name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td><td class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="non_brand_gross_sale[]" class="form-control"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
    } else {
        newDiv.innerHTML = '<td class="td-c"><input type="text" required=""  name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
    }
    mainDiv.appendChild(newDiv);
    setdatepicker();
}

function setTaxApplicableAmt(particular_row_id, gst_dropdown_value = '') {
    if (particular_row_id != '' && particular_row_id != 'undefined') {
        var percent = document.querySelector('[data-cy="product_gst' + particular_row_id + '"]').value;
        var cost = document.querySelector('[data-cy="particular_total_amount' + particular_row_id + '"]').value;
        gst_dropdown_percent = 0;
        $('input[name="tax_percent[]"]').map(function (i) {
            var tax_type = $('input[name="tax_type[]"]')[i].value;

            if (tax_type == 1 || tax_type == 2) {
                tax_percent = getIntVal(this.value) * 2;
                gst_dropdown_percent = gst_dropdown_value * 2;
            } else if (tax_type == 3) {
                tax_percent = getIntVal(this.value);
                gst_dropdown_percent = gst_dropdown_value;
            } else {
                tax_percent = 0;
            }

            if (tax_percent == getIntVal(percent)) {
                $('input[name="tax_applicable[]"]')[i].value = 0;
            } else {
                $('input[name="tax_applicable[]"]')[i].value = $('input[name="tax_applicable[]"]')[i].value - cost;
            }
            // if(gst_dropdown_value!='') {
            //     if(getIntVal(gst_dropdown_percent)==tax_percent) {
            //         $('input[name="tax_applicable[]"]')[i].value =  $('input[name="tax_applicable[]"]')[i].value - cost;
            //     }
            // }
        }).get();
    }

}

function setGstTax() {
    hasgst = getInputArrayValue('gst', 0, '');

    if (hasgst != false) {
        $('#taxes_tbl tbody tr.default_taxes').remove();
    }
    if (hasgst == 0) {
        $('#taxes_tbl tbody tr.default_taxes').remove();
    }

    if (getInputArrayValue('gst', 0, '')) {
        check_prdocuct_gst = 0;
    } else {
        check_prdocuct_gst = 1;
    }

    var amt5 = 0;
    var amt6 = 0;
    var amt12 = 0;
    var amt18 = 0;
    var amt28 = 0;
    gst_array = [];
    var num = $('input[name="total_amount[]"]').length;
    for (var i = 0; i < num; i++) {
        gst = getInputArrayValue('gst', i, '');

        if (gst == false) {
            if (check_prdocuct_gst == 1) {
                gst = getInputArrayValue('product_gst', i, '');
            } else {
                gst = getInputArrayValue('gst', i, '');
            }
        }
        //added by darshana

        if (gst > 0) {
            amt = getInputArrayValue('total_amount', i, 'input');
            if (gst == 5) {
                amt5 = Number(amt5 + amt);
            } else if (gst == 6) {
                amt6 = Number(amt6 + amt);
            }
            else if (gst == 12) {
                amt12 = Number(amt12 + amt);
            }
            else if (gst == 18) {
                amt18 = Number(amt18 + amt);
            }
            else if (gst == 28) {
                amt28 = Number(amt28 + amt);
            }
        } else {
            $('tr.add_default_taxes').each(function (index, elem) {
                if (tax_appli = $(this).find('input[name="tax_applicable[]"]').val()) {
                    if (tax_appli != '' && tax_appli != 'undefined' && tax_appli > 0) {
                        $('input[name="tax_applicable[]"]')[index].value = tax_appli;
                    } else {
                        $('input[name="tax_applicable[]"]')[index].value = 0;
                    }
                }
            });
        }
    }

    var num = $('input[name="sec_total_amount[]"]').length;
    for (var i = 0; i < num; i++) {
        gst = getInputArrayValue('sec_gst', i, 'select');

        if (gst == false) {
            gst = getInputArrayValue('sec_product_gst', i, 'input');
        } else {
            if (gst > 0) {
                amt = getInputArrayValue('sec_total_amount', i, 'input');
                if (gst == 5) {
                    amt5 = Number(amt5 + amt);
                } else if (gst == 6) {
                    amt6 = Number(amt6 + amt);
                }
                else if (gst == 12) {
                    amt12 = Number(amt12 + amt);
                }
                else if (gst == 18) {
                    amt18 = Number(amt18 + amt);
                }
                else if (gst == 28) {
                    amt28 = Number(amt28 + amt);
                }

            }
        }
    }


    if (amt5 > 0) {
        addGSTTax(5, amt5);
    }
    if (amt6 > 0) {
        addGSTTax(6, amt6);
    }
    if (amt12 > 0) {
        addGSTTax(12, amt12);
    }
    if (amt18 > 0) {
        addGSTTax(18, amt18);
    }
    if (amt28 > 0) {
        addGSTTax(28, amt28);
    }

    calculatetax();
    setTaxDropdown();
}

function addGSTTax(gst, amount) {
    try {
        merchant_state = _('merchant_state').value;
    } catch (o) {
        merchant_state = '';
    }
    if (customer_state == '' || customer_state == merchant_state) {
        gst_type = 1;
    } else {
        gst_type = 2;
    }
    var int = 0;
    if (gst_type == 1) {
        gst_percent = Number(gst / 2);
        setGstTaxRow(gst_percent, 1, amount);
        setGstTaxRow(gst_percent, 2, amount);
    } else {
        setGstTaxRow(gst, 3, amount);
    }
}

function setGstTaxRow(percent, type, amount) {
    int = getGstInputIndex(type, percent);
    amount = roundAmount(amount);
    if (int == 'na') {
        tax_id = getGSTTypeID(type, percent);
        if (tax_id > 0 && amount > 0) {
            AddInvoiceTax();
            var taxindex = Number($('input[name="tax_percent[]"]').length - 1);
            $('select[name="tax_id[]"]')[taxindex].value = tax_id;
            $('input[name="tax_applicable[]"]')[taxindex].value = amount;
        }
    } else {
        $('input[name="tax_applicable[]"]')[int].value = amount;
    }
}

function getGSTTypeID(type, percent) {
    var tax_id = 0;
    $.each(tax_array, function (id, val) {
        if (val.tax_type == type && val.percentage == percent) {
            tax_id = id;
        }
    });
    return tax_id;
}

function checkTaxExist(taxid) {
    var exist = false;
    $('select[name="tax_id[]"]').map(function () {
        if (taxid == this.value) {
            exist = true;
        }
    }).get();
    return exist;
}

function getGstInputIndex(type, percent) {
    var num = $('input[name="tax_type[]"]').length;
    percent = roundAmount(percent);
    var indexid = 'na';
    for (var i = 0; i < num; i++) {
        tax_type = getInputArrayValue('tax_type', i, 'input');
        tax_percent = Number(getInputArrayValue('tax_percent', i, 'input'));
        tax_percent = roundAmount(tax_percent);
        if (tax_type == type && tax_percent == percent) {
            indexid = i;
        }
    }
    return indexid;
}

function checkNewUpdate(is_updated) {
    is_update = 0;
    //calculatetax(undefined, undefined, is_update);
    let particulars = [];

    if (is_updated == '1') {
        return is_update = 1;
    } else {
        try {
            $('input[name="particular_id[]"]').map(function (i) {
                particulars[i] = getIntVal(this.value);
            }).get();

        } catch (o) {
        }

        let arr1 = JSON.parse(exist_particular_ids);
        let arr2 = particulars;
        let difference = arr1.filter(x => arr2.indexOf(x) === -1);
        if (difference != '') {
            return is_update = 1;
        } else {
            return is_update = 0;
        }
    }
}

function setProductGST(tax_row_id) {
    var percent = document.querySelector('[data-cy="invoice_tax_percent' + tax_row_id + '"]').value;
    var tax_type = document.querySelector('[data-cy="invoice_tax_type' + tax_row_id + '"]').value;
    if (percent != '') {
        if (tax_type == 1 || tax_type == 2) {
            percent = percent * 2;
        } else if (tax_type == 3) {
            percent = percent;
        }
        $('input[name="product_gst[]"]').map(function (i) {
            if (getIntVal(this.value) == getIntVal(percent)) {
                $('input[name="product_gst[]"]')[i].value = 0;
            }
        }).get();
    }
}

function calculatetax(tax_applicable_val, tax_applicable_attr, is_update = '') {
    var num = $('input[name="tax_percent[]"]').length;

    var current_particulars_row = $('input[name="pint[]"]').length;
    if (mode == 'update') {
        if (is_update == '1') {
            var is_update_particulars = checkNewUpdate(is_update);
        } else {
            var is_update_particulars = checkNewUpdate();
        }
    }
    var total_amount = 0;
    for (var i = 0; i < num; i++) {
        var tax_amount = 0;
        tax_id = getInputArrayValue('tax_id', i, 'select');
        tax_id = getIntVal(tax_id);
        try {
            percent = tax_array[tax_id].percentage;
            tax_type = tax_array[tax_id].tax_type;
        } catch (o) {
            percent = 0;
            tax_type = 0;
        }

        try {
            fixed_amt = tax_array[tax_id].fix_amount;
        } catch (o) {
            fixed_amt = 0;
        }

        $('input[name="tax_type[]"]')[i].value = tax_type;
        $('input[name="tax_percent[]"]')[i].value = percent;
        applicable_tax = getInputArrayValue('tax_applicable', i, 'input');
        applicable_tax = getIntVal(applicable_tax);
        if (tax_type == 5) {
            tax_amount = tax_array[tax_id].fix_amount;
            $('input[name="tax_applicable[]"]')[i].value = '';
            $('input[name="tax_applicable[]"]')[i].readOnly = true;
        } else if (tax_type == 4) {
            //added darshana tax_type==4 condition
            tax_calculated_on = getInputArrayValue('tax_calculated_on', i, 'input');
            tax_calculated_on = getIntVal(tax_calculated_on);

            particular_total = document.getElementById('particulartotal').value;
            particular_total = getIntVal(particular_total);

            //0 is on base amount, 1 is on grand total
            if (tax_calculated_on == 1) {
                $('input[name="tax_applicable[]"]')[i].readOnly = false;
                try {
                    grand_total_tax = 0;

                    $('tr.default_taxes').each(function (index, elem) {
                        if (tax_amt = $(this).find('input[name="tax_amt[]"]').val()) {
                            if (tax_amt != '' && tax_amt != 'undefined') {
                                grand_total_tax = grand_total_tax + getIntVal(tax_amt);
                            }
                        }
                    });

                    $('tr.add_default_taxes').each(function (index, elem) {
                        if (tax_amt = $(this).find('input[name="tax_amt[]"]').val()) {
                            if (tax_amt != '' && tax_amt != 'undefined') {
                                grand_total_tax = grand_total_tax + getIntVal(tax_amt);
                            }
                        }
                    });
                    percent = $('input[name="tax_percent[]"]')[i].value;

                    if (tax_applicable_val === undefined && typeof tax_applicable_val == 'undefined') {
                        if (mode == 'create') {
                            applicable_on = Number(grand_total_tax + particular_total);
                        } else {
                            if (current_particulars_row != exist_paricular_cnt) {
                                applicable_on = Number(grand_total_tax + particular_total);
                            } else {
                                if (is_update_particulars == 1) {
                                    applicable_on = Number(grand_total_tax + particular_total);
                                } else {
                                    applicable_on = $('input[name="tax_applicable[]"]')[i].value;
                                }
                            }
                        }
                        $('input[name="tax_applicable[]"]')[i].value = applicable_on;
                    } else {
                        applicable_on = $('input[name="tax_applicable[]"]')[i].value;
                        $(tax_applicable_attr).value = applicable_on;
                    }

                    tax_amount = calculateTax(applicable_on, percent);
                } catch (o) {
                }

            } else if (tax_calculated_on == 0) {
                percent = $('input[name="tax_percent[]"]')[i].value;
                $('input[name="tax_applicable[]"]')[i].readOnly = false;
                tax_app = particular_total;
                if (tax_applicable_val === undefined && typeof tax_applicable_val == 'undefined') {
                    if (mode == 'create') {
                        $('input[name="tax_applicable[]"]')[i].value = particular_total;
                    } else {
                        if (current_particulars_row != exist_paricular_cnt) {
                            $('input[name="tax_applicable[]"]')[i].value = particular_total;
                        } else {
                            if (is_update_particulars == 1) {
                                $('input[name="tax_applicable[]"]')[i].value = particular_total;
                            } else {
                                tax_app = $('input[name="tax_applicable[]"]')[i].value;
                            }
                        }
                    }
                } else {
                    tax_app = $('input[name="tax_applicable[]"]')[i].value;
                    $(tax_applicable_attr).value = tax_app;
                }

                tax_amount = calculateTax(tax_app, percent);
            }
        }
        else {
            $('input[name="tax_applicable[]"]')[i].readOnly = false;
            $('input[name="tax_applicable[]"]')[i].value = applicable_tax;
            tax_amount = calculateTax(applicable_tax, percent);
        }
        tax_amount = getIntVal(tax_amount);
        $('input[name="tax_amt[]"]')[i].value = roundAmount(tax_amount);
        total_amount = Number(total_amount + tax_amount);
    }
    document.getElementById("totaltaxcost").value = roundAmount(total_amount);
    calculategrandtotal();
    calculateVendorCommission();

}

function billCode(value, int) {
    data = '';
    if (value != '' || value != null || value != undefined) {
        $.ajax({
            type: 'GET',
            url: '/ajax/getcgiCode/' + value,
            data: data,
            success: function (data) {
                document.getElementById('description' + int).innerHTML = data;
                document.getElementById('description-hidden' + int).value = data;
            }
        });
    }
    return false;
}

function billCode2() {

    $('select[name="bill_code[]"]').each(function (indx, arr) {
        int = this.value.indexOf(" | ");
        if (int > 0) {
            bill_code = this.value.substring(0, int);
        } else {
            bill_code = this.value;
        }
        $.each(csi_codes, function (value, arra) {
            if (bill_code == arra.code) {
                document.getElementById('description' + indx).value = arra.description;
                document.getElementById('description-hidden' + indx).innerHTML = arra.description;
            }
        });
    });

}

function selectCustomer(customer_id) {
    var data = '';
    var template_id = document.getElementById('template_id').value;
    if (customer_id > 0) {
        $.ajax({
            type: 'GET',
            url: '/merchant/customer/getcustomerdetails/' + customer_id + '/' + template_id,
            data: data,
            success: function (data) {
                obj = JSON.parse(data);
                customer_state = obj.state;
                try {
                    var template_type = document.getElementById('template_type').value;
                    if (template_type != 'franchise') {
                        calculateamt('undefined');
                    }
                } catch (o) {
                }
                try {
                    $.each(obj['column_value'], function (index, value) {

                        if (value.datatype == 'textarea') {
                            document.getElementById('customer' + value.id).innerHTML = value.value;
                        } else {
                            document.getElementById('customer' + value.id).value = value.value;
                        }
                    });
                } catch (o) {

                }

                try {
                    due_type = document.getElementById('previous_due_param').value;
                    if (due_type == 'auto_calculate') {
                        document.getElementById('previous_due').value = obj.balance;
                        calculateamt();
                    }
                } catch (o) {

                }


            }
        });
        try {
            document.getElementById("cust_empty_error").style.display = 'none';
        } catch (o) {
        }
    } else {
        var x = document.getElementsByClassName("form-control cust_det");
        var i;
        for (i = 0; i < x.length; i++) {
            try {
                x[i].value = '';
            } catch (o) {
            }

            try {
                x[i].innerHTML = '';
            } catch (o) {
            }
        }
    }

    return false;
}

function showLedger() {

    customer_id = document.getElementById('customer_id').value;
    data = '';
    if (customer_id > 0) {
        document.getElementById("panelLedger").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
        document.getElementById("panelLedger").style.transform = "translateX(0%)";
        $('.page-sidebar-wrapper').css('pointer-events', 'none');
        $.ajax({
            type: 'GET',
            url: '/merchant/customer/getCustomerLedger/' + customer_id,
            data: data,
            success: function (data) {
                obj = JSON.parse(data);
                customer_state = obj.customer_name;
                try {
                    document.getElementById('lname').innerHTML = obj.customer_name;
                    document.getElementById('lcustomer_code').innerHTML = obj.customer_code;
                    document.getElementById('lbalance').innerHTML = obj.balance;
                    document.getElementById('ledger').innerHTML = obj.ledger;

                } catch (o) {
                }



            }
        });
    } else {
        try {
            document.getElementById("cust_empty_error").style.display = 'block';
        } catch (o) {
        }
    }


    return false;
}

function calculateFranchiseSale() {
    var total_gross = 0;
    $('input[name="gross_sale[]"]').each(function (indx, arr) {
        total_gross = Number(total_gross) + Number($(this).val());
    });
    _('gbs').value = roundAmount(total_gross);
    net_val = roundAmount(total_gross * 100 / 105);
    _('sale_tax').value = roundAmount(total_gross - net_val);
    _('nbs').value = net_val;
    //_('particulartotal').value = net_val;
    franchiseSummary();
    calculateNonBrandFranchiseSale();
}

function calculateNonBrandFranchiseSale() {
    try {
        var total_gross = 0;
        $('input[name="non_brand_gross_sale[]"]').each(function (indx, arr) {
            total_gross = Number(total_gross) + Number($(this).val());
        });
        _('nbgbs').value = roundAmount(total_gross);
        net_val = roundAmount(total_gross * 100 / 105);
        _('nbsale_tax').value = roundAmount(total_gross - net_val);
        _('nbnbs').value = net_val;
        // bnet_val = getIntVal(_('nbs').value);
        //_('particulartotal').value =Number(net_val) + Number(bnet_val);
    } catch (o) {
    }

}

function franchiseSummary() {
    var gross = Number(_('gbs').value);
    var sale_tax = Number(_('sale_tax').value);
    net_sale = roundAmount(gross - sale_tax);
    _('nbs').value = net_sale;
    _('gca').value = roundAmount(Number(_('gcp').value) * net_sale / 100);
    _('wca').value = roundAmount(Number(_('wcp').value) * net_sale / 100);
    net_fee = roundAmount(Number(_('ncp').value) * net_sale / 100);
    _('nca').value = net_fee;
    _('particulartotal').value = roundAmount(Number(_('penalty').value) + Number(net_fee));
    franchiseNonBrandSummary();
    $('input[name="tax_applicable[]"]').each(function (indx, arr) {
        $(this).val(_('particulartotal').value);
    });
    calculatetax();
    _('invoice_total').value = roundAmount(Number(_('particulartotal').value) + Number(_('totaltaxcost').value));
    try {
        adjustment = Number(_('adjustment').value);
    } catch (o) {
        adjustment = 0;
    }
    _('grand_total').value = roundAmount(Number(_('invoice_total').value) - adjustment + Number(_('previous_due').value));
}

function franchiseNonBrandSummary() {
    try {
        var gross = Number(_('nbgbs').value);
        var sale_tax = Number(_('nbsale_tax').value);
        net_sale = roundAmount(gross - sale_tax);
        _('nbnbs').value = net_sale;
        _('nbgca').value = roundAmount(Number(_('nbgcp').value) * net_sale / 100);
        _('nbwca').value = roundAmount(Number(_('nbwcp').value) * net_sale / 100);
        net_fee = roundAmount(Number(_('nbncp').value) * net_sale / 100);
        _('nbnca').value = net_fee;
        bnet_fee = _('nca').value;
        _('particulartotal').value = roundAmount(Number(_('penalty').value) + Number(net_fee) + Number(bnet_fee));
    } catch (o) { }
}


function saveProductDelete() {
    document.getElementById('loader').style.display = 'block';
    var data = $("#product_frm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/product/productsave/1',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                $('select[name="item[]"]').map(function () {
                    this.append(new Option(obj.name, obj.name));
                    // $(this).val()
                }).get();

                products = JSON.parse(obj.product_array);

                $('select[name="item[]"]').each(function (indx, arr) {
                    if (indx == product_index) {
                        $(this).val(obj.name);
                        $(this).change();
                    }
                });

                document.getElementById('pclosebutton').click();
                document.getElementById('product_error').style.display = 'none';
                document.getElementById('product_error').innerHTML = '';
                $("#product_frm").trigger("reset");

            } else {
                document.getElementById('product_error').style.display = 'block';
                document.getElementById('product_error').innerHTML = obj.error;
            }
            document.getElementById('loader').style.display = 'none';
        }
    });
    return false;
}
function saveTax() {
    document.getElementById('loader').style.display = 'block';
    var data = $("#tax_frm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/tax/taxsave/1',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                $('select[name="tax_id[]"]').map(function () {
                    this.append('<option value="' + obj.tax_id + '">' + obj.tax_name + '</option>');
                }).get();

                tax_master = obj.tax_array;
                tax_array = JSON.parse(tax_master);
                taxes_rate = obj.tax_rate_array;

                document.getElementById('tclosebutton').click();
                document.getElementById('tax_error').style.display = 'none';
                document.getElementById('tax_error').innerHTML = '';
                $('.taxselect').append($('<option>', {
                    value: obj.tax_id,
                    text: obj.tax_name
                })).select2();

                $('select[name="tax_id[]"]').each(function (indx, arr) {
                    if (indx == tax_index) {
                        $(this).val(obj.tax_id);
                        $(this).change();
                    }
                });
            } else {
                document.getElementById('tax_error').style.display = 'block';
                document.getElementById('tax_error').innerHTML = obj.error;
            }
            document.getElementById('loader').style.display = 'none';
        }
    });
    return false;
}

function setAdvanceDropdown(numrow) {
    try {


        $('.productselect').select2({
            tags: true,

            insertTag: function (data, tag) {
                var $found = false;
                $.each(data, function (index, value) {
                    if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                        $found = true;
                    }
                });

                if (!$found) data.unshift(tag);
            }
        }).on('select2:open', function (e) {
            pind = $(this).index();
            var index = $(".productselect").index(this);
            index += 1;
            var project_id = document.getElementById('project_id').value;
            if (document.getElementById('prolist' + pind)) {
            } else {
                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + index + ',' + index + ',project_id);">Add new bill code</a> </div>');
            }
        });


    } catch (o) {
    }
}

async function setAdvanceDropdownContract(numrow) {
    $('.productselect').select2({
        tags: true,

        insertTag: function (data, tag) {
            var $found = false;
            $.each(data, function (index, value) {
                if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                    $found = true;
                }
            });
            if (!$found) data.unshift(tag);
        }
    }).on('select2:open', function (e) {
        pind = $(this).index();
        var index = $(".productselect").index(this);
        if (document.getElementById('prolist' + pind)) {
        } else {
            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + numrow + ',' + numrow + ',0);">Add new bill code</a> </div>');
        }
    });

}

function setAdvanceDropdownContract3(numrow) {
    $('.productselect').select2({
        tags: true,

        insertTag: function (data, tag) {
            var $found = false;
            $.each(data, function (index, value) {
                if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                    $found = true;
                }
            });
            if (!$found) data.unshift(tag);
        }
    }).on('select2:open', function (e) {
        pind = $(this).index();
        var index = $(".productselect").index(this);
        if (document.getElementById('prolist' + pind)) {
        } else {
            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + numrow + ',' + numrow + ',0);">Add new bill code</a> </div>');
        }
    }).on('change', function (e) {
        iid = $(this).attr("id");
        alert('a' + iid);
        document.getElementById('a' + iid).style.display = 'none';
    });

}

function setAdvanceDropdownGroup(id) {
    try {
        console.log('#billcode' + id);
        $('#billcode' + id).select2({
            insertTag: function (data, tag) {
                var $found = false;
                $.each(data, function (index, value) {
                    if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                        $found = true;
                    }
                });
                if (!$found) data.unshift(tag);
            }
        }).on('select2:open', function (e) {
            pind = $(this).index();
            var index = $(".productselect").index(this);
            if (document.getElementById('prolist' + pind)) {
            } else {
                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + numrow + ',' + numrow + ',0);">Add new bill code</a> </div>');
            }
        });
    } catch (o) {
    }
}

function setTaxDropdown() {
    try {
        $('.taxselect').select2({
            tags: true
        }).on('select2:open', function () {
            pind = $(this).index();
            var index = $(".taxselect").index(this);
            if (document.getElementById('taxlist' + pind)) {
            } else {
                $('.select2-results').append('<div class="wrapper" id="taxlist' + pind + '" > <a data-toggle="modal" onclick="tax_index=' + index + ';" href="#new_addtax">Add new tax</a> </div>');
            }
        });
    } catch (o) {
    }
}

function AddInvoiceReminder() {
    var mainDiv = document.getElementById('new_reminder');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="number" name="reminders[]" class="form-control " placeholder="Add day"></div></td><td><div class="input-icon right"><input type="text" name="reminder_subject[]"  maxlength="250" class="form-control " placeholder="Reminder mail subject"></div></td><td><div class="input-icon right"><input type="text" name="reminder_sms[]"  maxlength="200" class="form-control " placeholder="Reminder SMS"></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}
function showEditNote() {
    var value = document.getElementById('covering_select').value;

    if (value != '0') {
        document.getElementById('edit_note_div').style = 'display:block';
    } else {
        document.getElementById('edit_note_div').style = 'display:none';
    }

    data = '';
    $.ajax({
        type: 'GET',
        url: '/merchant/getSingleCoverNote/' + value,
        data: data,
        success: function (data) {

            if (data.length > 0) {
                document.getElementById('edit_template_name').value = data[0].template_name;
                $('#edit_summernote').summernote('code', data[0].body);
                document.getElementById('edit_subject').value = data[0].subject;
                document.getElementById('edit_invoice_label').value = data[0].invoice_label;
                document.getElementById('edit_pdf_enable').value = data[0].pdf_enable;
                document.getElementById('ispdf').value = data[0].pdf_enable;

            }
        }
    });

    $('.tncrich1').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ol', 'ul', 'paragraph', 'height']],
            ['table', ['table']],
            ['insert', ['link', 'hr']],
            ['view', ['undo', 'redo', 'codeview']]
        ],
        callbacks: {
            onKeydown: function (e) {
                var t = e.currentTarget.innerText;
                if (t.trim().length >= 5000) {
                    //delete keys, arrow keys, copy, cut
                    if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                        e.preventDefault();
                }
            },
            onKeyup: function (e) {
                var t = e.currentTarget.innerText;
                $('#maxContentPost').text(5000 - t.trim().length);
            },
            onPaste: function (e) {
                var t = e.currentTarget.innerText;
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                var maxPaste = bufferText.length;
                if (t.length + bufferText.length > 5000) {
                    maxPaste = 5000 - t.length;
                }
                if (maxPaste > 0) {
                    document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                }
                $('#maxContentPost').text(5000 - t.length);
            }
        }
    });

}
function AddCoveringNote() {

    document.getElementById("panelWrapIdaddcovernote").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapIdaddcovernote").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');

}
function EditCoveringNote() {

    document.getElementById("panelWrapIdeditcovernote").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapIdeditcovernote").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');
    showEditNote();
}
function closeSidePanelcover() {
    document.getElementById("panelWrapIdaddcovernote").style.boxShadow = "none";
    document.getElementById("panelWrapIdaddcovernote").style.transform = "translateX(100%)";
    document.getElementById("panelWrapIdeditcovernote").style.boxShadow = "none";
    document.getElementById("panelWrapIdeditcovernote").style.transform = "translateX(100%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    CloseCoverNote();
    return false;
}
function EnablePdf() {

    if (document.getElementById('edit_pdf_enable').checked) {
        document.getElementById('ispdf').value = 1;
    } else {
        document.getElementById('ispdf').value = 0;
    }
}
function CloseCoverNote() {
    try {
        document.getElementById('editcovering_error').style.display = 'none';
        document.getElementById('editcovering_error').innerHTML = '';
        // document.getElementById('editcclosebutton').click();
    } catch (e) { }
    try {
        document.getElementById('covering_error').style.display = 'none';
        document.getElementById('covering_error').innerHTML = '';
        //  document.getElementById('cclosebutton').click();
    } catch (e) { }
    document.getElementById('cancel_confirm_box').click();
}




function AddInvoiceDebit() {

    try {
        var node_listright = document.getElementsByName("countrowdebit");
        var Numrow = Number(node_listright.length) + 1;
    } catch (o) {
        Numrow = 1;
    }

    while (document.getElementById('debitin' + Numrow)) {
        Numrow = Numrow + 1;
    }
    totalcostamt = _('totalcostamt').value;
    var mainDiv = document.getElementById('new_debit');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<input type="hidden" name="countrowdebit"/><td><div class="input-icon right"><input type="text" required name="deduct_tax[]" class="form-control " placeholder="Add label"></div></td><td><div class="input-icon right"><input type="number" step="0.01" max="100" name="deduct_percent[]" ' + ptax + ' id="debitin' + Numrow + '" onblur="calculatedebit(' + Numrow + ')"  class="form-control " placeholder="Add %"></div></td><td><div class="input-icon right"><input type="text" name="deduct_applicable[]" ' + aamt + ' id="applicabledebitamount' + Numrow + '" placeholder="Add applicable on" onblur="calculatedebit(' + Numrow + ')"  value="' + totalcostamt + '" class="form-control " ></div></td><td><input type="text" name="deduct_total[]" class="form-control " id="totaldebit' + Numrow + '" readonly></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}


function setGSTInvoiceSeq(id) {
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getgstinvoiceseq/' + id,
        data: data,
        success: function (data) {
            try {
                obj = JSON.parse(data);
                if (obj.id != '') {
                    try {
                        _('invoice_number').placeholder = obj.id + '-System generated';
                    } catch (o) {

                    }
                }
                _('merchant_state').value = obj.state;
                calculateamt();
            } catch (o) {

            }

        }
    });
}

function setCurrency(id) {
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getbillingprofilecurrency/' + id,
        data: data,
        success: function (data) {
            try {
                obj = JSON.parse(data);
                $("#currency").empty();
                var x = document.getElementById("currency");
                $.each(obj, function (index, value) {
                    var option = document.createElement("option");
                    option.text = value;
                    option.value = value;
                    x.add(option);
                });
            } catch (o) {

            }

        }
    });
}

function setTaxCalculatedOn(tax_id, data_cy) {
    data = ''; tax_data_cy = ''; tax_row = '';

    tax_data_cy = data_cy.replace('invoice_tax_id', 'invoice_tax_calculated_on');
    tax_row = data_cy.replace('invoice_tax_id', 'tax_tr');
    help_note = data_cy.replace('invoice_tax_id', 'note-for-tax-calculation');
    $.ajax({
        type: 'GET',
        url: '/ajax/getTaxCalculatedOn/' + tax_id,
        data: data,
        success: function (data) {
            try {
                obj = JSON.parse(data);

                if (tax_data_cy != '') {
                    document.querySelector('[data-cy=' + tax_data_cy + ']').value = obj.tax_calculated_on;
                }
                if (obj.tax_type == 4) {
                    if (obj.tax_calculated_on == 1) {
                        $('[data-cy=' + help_note + ']').html('Tax calculated on <b>Grand total (Amount with GST)</b>');
                    } else {
                        $('[data-cy=' + help_note + ']').html('Tax calculated on <b>Base amount (Amount without GST)</b>');
                    }
                    var element = document.getElementById(tax_row);
                    element.classList.remove("default_taxes");
                    element.classList.add("other_taxes");
                    calculatetax(undefined, undefined, '1');
                } else {
                    $('[data-cy=' + help_note + ']').html('');
                    calculatetax();
                }
            } catch (o) {

            }
        }
    });
}

function invoicePreview(template_id) {
    if (template_id != '') {
        var data = '';
        $.ajax({
            type: 'GET',
            url: '/merchant/template/templatepreview/' + template_id,
            data: data,
            success: function (data) {
                obj = JSON.parse(data);
                document.getElementById('preview').innerHTML = obj.preview;
                document.getElementById('preview_div').style.display = 'block';
                document.getElementById('edit_templte').setAttribute("href", obj.link);
            }
        });
    } else {
        document.getElementById('preview').innerHTML = '';
        document.getElementById('preview_div').style.display = 'none';
        document.getElementById('edit_templte').setAttribute("href", '/merchant/template/viewlist');
    }
}

function changeTaxtype(val) {
    if (val == 5) {
        document.getElementById('tamount').style.display = 'block';
        document.getElementById('tpercent').style.display = 'none';
        document.getElementById('tax_calculated_on').style.display = 'none';
    } else if (val == 4) {
        document.getElementById('tax_calculated_on').style.display = 'block';
        document.getElementById('tpercent').style.display = 'block';
        document.getElementById('tamount').style.display = 'none';
    } else {
        document.getElementById('tpercent').style.display = 'block';
        document.getElementById('tamount').style.display = 'none';
        document.getElementById('tax_calculated_on').style.display = 'none';
    }
}

function setProductRates() {
    $('select[name="item[]"]').each(function (indx, arr) {
        $(this).change();
    });
}

function carryDueForward(val) {
    if (val == true) {
        document.getElementById("carry_due_forward").value = 1;
    } else {
        document.getElementById("carry_due_forward").value = 0;
    }
}

function vendorCommission(vendor_id) {
    if (vendor_id != '') {
        var data = '';
        $.ajax({
            type: 'GET',
            url: '/ajax/getVendordetails/' + vendor_id,
            data: data,
            success: function (data) {
                obj = JSON.parse(data);
                if (obj.commision_type == 0) {
                    obj.commision_type = 2;
                }
                document.getElementById('commision_type').value = obj.commision_type;
                document.getElementById('commision_value').value = obj.commision_value;
                calculateVendorCommission();
            }
        });
    } else {
        document.getElementById('commision_type').innerHTML = '';
        document.getElementById('commision_value').style.display = '';
        calculateVendorCommission();
    }

}

function calculateVendorCommission() {
    try {
        amt = Number(_('totalamount').value);
        commision_type = _('commision_type').value;
        commision_value = Number(_('commision_value').value);
        if (commision_type == 1) {
            commision = amt * commision_value / 100;
        } else {
            commision = Number(_('commision_value').value);
        }
        _('commision_amount').value = commision;

    } catch (o) {
        //alert(o.Message());
    }
}



function updateTextView(_obj) {
    var num = getNumber(_obj.val());
    try {
        num = num.toFixed(2);
    } catch (o) { }
    dotpo = num.indexOf(".");
    decimal_text = '';
    if (dotpo > 0) {
        number = Number(num.substring(0, dotpo));
        decimal_text = num.substring(dotpo);
    } else {
        number = Number(num);
    }
    if (num == 0) {
        _obj.val(num);
    } else {
        _obj.val(number.toLocaleString() + decimal_text);
    }
}

function updateTextView1(val) {
    if (val != '') {
        try {
            val = val.toFixed(2);
        } catch (o) {
            val = Number(val.replaceAll(',', ''));
        }
        if (val > 0) {
            try {
                str = val.toString();
            } catch (o) {
                return '';
            }

            var num = getNumber(str);
            dotpo = num.indexOf(".");
            decimal_text = '';
            if (dotpo > 0) {
                number = Number(num.substring(0, dotpo));
                decimal_text = num.substring(dotpo);

            } else {
                number = Number(num);
            }
            if (num == 0) {
                return 0.00;
            } else {
                if (decimal_text != '.00') {
                    return number.toLocaleString() + decimal_text;
                } else {
                    return number.toLocaleString();
                }
                //return number.toLocaleString() + decimal_text;
            }
        }
        return 0.00;
    } else {
        return '';
    }

}
function getNumber(_str) {
    var arr = _str.split('');
    var out = new Array();
    for (var cnt = 0; cnt < arr.length; cnt++) {
        if (arr[cnt] == '.') {
            out.push(arr[cnt]);
        }
        if (isNaN(arr[cnt]) == false) {
            out.push(arr[cnt]);
        }
    }
    return out.join('');
}


function templateChange(template_id) {
    if (template_id != '') {
        var data = '';
        $.ajax({
            type: 'GET',
            url: '/ajax/templatetype/' + template_id,
            data: data,
            success: function (data) {
                if (data == 'construction') {
                    document.getElementById('contract_div').style.display = 'block';
                    $('#contract_id').prop('required', true);

                    $('#contract_id').select2({

                    }).on('select2:open', function (e) {
                        pind = $(this).index();
                        if (document.getElementById('contractlist' + pind)) {
                        } else {
                            $('.select2-results').append('<div class="wrapper" > <a href="/merchant/contract/create" id="contractlist' + pind + '"   class="clicker" >Add new contract</a> </div>');
                        }
                    });

                } else {
                    document.getElementById('contract_div').style.display = 'none';
                    $('#contract_id').prop('required', false);

                }
            }
        });
    } else {
        document.getElementById('contract_div').style.display = 'none';
        $('#contract_id').prop('required', false);
    }
}

function assignProjectID() {
    project_id = document.getElementById('project_id').value;
    document.getElementById('project_prefix').value = project_id
}

function preview() {
    document.getElementById('pr_project_name').innerHTML = document.getElementById('project_name').value;
    document.getElementById('pr_company_name').innerHTML = document.getElementById('customer_code').value;
    document.getElementById('pr_contract_number').innerHTML = document.getElementById('contract_number').value;
    document.getElementById('pr_billing_frequency').innerHTML = $("#billing_frequency option:selected").text();
    document.getElementById('pr_contract_date').innerHTML = document.getElementById('contract_date').value;
    document.getElementById('preview_data').innerHTML = '';
    var mainDiv = document.getElementById('preview_data');
    $('input[name="bill_code[]"]').each(function (int, arr) {
        // try {
        var newDiv = document.createElement('tr');
        row = '';
        num = int;

        let billTypeArr = ['% Complete', 'Unit', 'Calculated'];
        let billcodeArr = document.getElementById('select2-billcode' + num + '-container').innerHTML.split(' | ');
        let billType = document.getElementById('billtype' + int).value;
        let original_contract_amountL = document.getElementsByName('original_contract_amount[]')[int].value;
        let retainage_percentL = document.getElementsByName('retainage_percent[]')[int].value;
        let groupL = document.getElementById('select2-group' + num + '-container').innerHTML;
        if (groupL === '<span class="select2-selection__placeholder">Select group</span>') groupL = '';

        if (billcodeArr.length > 0 && billTypeArr.includes(billType) && original_contract_amountL !== null
            && original_contract_amountL !== '') {
            bill_code = document.getElementById('select2-billcode' + num + '-container').innerHTML;
            bill_type = document.getElementById('billtype' + int).value;
            original_contract_amount = document.getElementsByName('original_contract_amount[]')[int].value;
            retainage_percent = document.getElementsByName('retainage_percent[]')[int].value;
            retainage_amount = document.getElementsByName('retainage_amount[]')[int].value;
            project = document.getElementsByName('project[]')[int].value;
            project_code = document.getElementsByName('project[]')[int].value;
            cost_code = document.getElementsByName('cost_code[]')[int].value;
            cost_type = document.getElementsByName('cost_type[]')[int].value;
            group = groupL;
            bill_code_detail = document.getElementById('select2-billcodedetail' + num + '-container').innerHTML;
            row = '<td class="td-c">' + bill_code + '</td><td class="td-c">' + bill_type + '</td><td class="td-c">' + original_contract_amount + '</td><td class="td-c">' + retainage_percent + '</td><td class="td-c">' + retainage_amount + '</td><td class="td-c">' + project_code + '</td><td class="td-c">' + cost_code + ' </td><td class="td-c">' + cost_type + '</td> <td class="td-c">' + group + '</td><td class="td-c"> ' + bill_code_detail + '</td>';
            newDiv.innerHTML = row;
            mainDiv.appendChild(newDiv);
        }
        //  } catch (o) {

        //  }
    });
}

function getContractParticularsData() {
    let particulars = [];
    let billTypeArr = ['% Complete', 'Unit', 'Calculated'];
    $('input[name="bill_code[]"]').each(function (int, arr) {
        let num = int;
        let particular = new Object();

        let billcodeArr = document.getElementById('select2-billcode' + num + '-container').innerHTML.split(' | ');
        let billType = document.getElementById('billtype' + int).value;
        let original_contract_amount = document.getElementsByName('original_contract_amount[]')[int].value;
        let retainage_percent = document.getElementsByName('retainage_percent[]')[int].value;
        let group = document.getElementById('select2-group' + num + '-container').innerHTML;
        if (group === '<span class="select2-selection__placeholder">Select group</span>') group = '';

        // if (billcodeArr.length === 0) $('#select2-billcode' + num + '-container').parent('.select2-container').css('border', '1px solid #red !important')

        if (billcodeArr.length > 0 && billTypeArr.includes(billType) && original_contract_amount !== null
            && original_contract_amount !== '') {

            particular.bill_code = billcodeArr[0].trim();
            particular.bill_type = billType.trim();
            particular.description = document.getElementById('description' + int).value.trim();
            particular.original_contract_amount = document.getElementsByName('original_contract_amount[]')[int].value;
            if (particular.original_contract_amount === null || particular.original_contract_amount === '')
                particular.original_contract_amount = 0;
            particular.retainage_percent = document.getElementsByName('retainage_percent[]')[int].value;
            if (particular.retainage_percent === null || particular.retainage_percent === '')
                particular.retainage_percent = 0;
            particular.retainage_amount = document.getElementsByName('retainage_amount[]')[int].value;
            if (particular.retainage_amount === null || particular.retainage_amount === '')
                particular.retainage_amount = 0;
            particular.project = document.getElementsByName('project[]')[int].value;
            particular.cost_code = document.getElementsByName('cost_code[]')[int].value;
            particular.cost_type = document.getElementsByName('cost_type[]')[int].value;
            particular.calculated_perc = document.getElementsByName('calculated_perc[]')[int].value;
            particular.calculated_row = document.getElementsByName('calculated_row[]')[int].value;
            particular.group = group;
            particular.original_contract_amount = getamt(particular.original_contract_amount);
            particular.retainage_amount = getamt(particular.retainage_amount);
            particular.retainage_percent = getamt(particular.retainage_percent);




            particular.pint = document.getElementsByName('pint[]')[int].value;
            // particular.group = document.getElementById('select2-group' + num + '-container').innerHTML;

            particular.bill_code_detail = (document.getElementById('select2-billcodedetail' + num + '-container').innerHTML === 'Yes') ? 'Yes' : 'No';

            particulars.push(particular);
        }

    });
    if ($('input[name="bill_code[]"]').length > particulars.length)
        return [];
    return particulars;
}


function select2Dropdowns(id) {
    try {
        $('#billcode' + id).select2({
            insertTag: function (data, tag) {

            }
        }).on('select2:open', function (e) {
            pind = $(this).index();
            if (document.getElementById('prolist' + pind)) { } else {
                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + id + ',' + id + ',0);">Add new bill code</a> </div>');
            }
        });
    } catch (o) { }

    try {
        // $('#billtype' + id).select2({
        //     minimumResultsForSearch: -1
        // });
    } catch (o) { }
    try {
        $('#billcodedetail' + id).select2({
            minimumResultsForSearch: -1
        });
    } catch (o) { }

    try {
        $('#group' + id).select2({
            insertTag: function (data, tag) {

            }
        }).on('select2:open', function (e) {
            pind = $(this).index();
            if (document.getElementById('grouplist' + pind)) { } else {
                $('.select2-results').append('<div class="wrapper" id="grouplist' + pind + '" > <a class="clicker" onclick="setgroup(' + id + ');">Add new group</a> </div>');
            }
        });
    } catch (o) { }
}