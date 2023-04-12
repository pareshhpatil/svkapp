function _(el) {
    return document.getElementById(el);
}

function ev(el) {
    try {
        return document.getElementById(el).value;
    }
    catch (o) {
        return '';
    }

}

function setdata(name, fullurl) {

    document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
    document.getElementById('docfullurl').value = fullurl;
}

function deletedocfile(x) {
    var html = '';
    if (x == 'delete') {
        var fullurl = document.getElementById('docfullurl').value;
        var index = newdocfileslist.indexOf(fullurl);
        if (index !== -1) {
            newdocfileslist.splice(index, 1);
        }
    }

    for (var i = 0; i < newdocfileslist.length; i++) {
        var filenm = newdocfileslist[i].substring(newdocfileslist[i].lastIndexOf('/') + 1);
        filenm = filenm.split('.').slice(0, -1).join('.')
        filenm = filenm.substring(0, filenm.length - 4);
        html = html + '<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
            '<a class=" btn btn-xs " target="_BLANK" href="' + newdocfileslist[i] + '" title="Click to view full size">' + filenm.substring(0, 10) + '..</a>' +
            '<a href="#delete_doc" onclick="setdata(\'' + filenm.substring(0, 10) + '\',\'' + newdocfileslist[i] + '\');"   data-toggle="modal"> ' +
            ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';


    }
    clearnewuploads('no');
    document.getElementById('docviewbox').innerHTML = html;
    document.getElementById('closeconformdoc').click();
}

function clearnewuploads(x) {
    document.getElementById("file_upload").value = '';

    var filesnm = '';

    for (var i = 0; i < newdocfileslist.length; i++) {
        if (filesnm != '')
            filesnm = filesnm + ',' + newdocfileslist[i];
        else
            filesnm = filesnm + newdocfileslist[i];
    }
    document.getElementById("file_upload").value = filesnm;
}

function calculateRow(id, type = 0) {


    original_contract_amount = ev('original_contract_amount' + id);
    retainage_percent = ev('retainage_percent' + id);
    //_('retainage_amount' + id).value = updateTextView1(getamt(original_contract_amount) * getamt(retainage_percent) / 100);

    approved_change_order_amount = ev('approved_change_order_amount' + id);
    _('current_contract_amount' + id).value = updateTextView1(getamt(original_contract_amount) + getamt(approved_change_order_amount));


    previously_billed_amount = ev('previously_billed_amount' + id);
    current_billed_amount = ev('current_billed_amount' + id);
    stored_materials = ev('stored_materials' + id);
    current_stored_materials = ev('current_stored_materials' + id);
    previously_stored_materials = ev('previously_stored_materials' + id);

    _('stored_materials' + id).value = updateTextView1(getamt(previously_stored_materials) + getamt(current_stored_materials));
    _('total_billed' + id).value = updateTextView1(getamt(current_billed_amount) + getamt(previously_billed_amount) + getamt(previously_stored_materials) + getamt(current_stored_materials));


    //todo retainage_amount_change

    retainage_amount_change = ev('retainage_amount_change' + id);
    retainage_amount_for_this_draw = ev('retainage_amount_for_this_draw' + id);
    retainage_percent = ev('retainage_percent' + id);
    retainage_percent_cal = 0;
    if (retainage_amount_change == 'true' || particular_type == 2) {
        retainage_percent_cal = 1;
    }
    if (retainage_amount_change == 'false') {
        particular_type = 1;
        retainage_percent_cal = 0;
    }

    if (retainage_percent_cal == 1) {
        if (getamt(current_billed_amount) > 0) {
            _('retainage_percent' + id).value = updateTextView1(getamt(retainage_amount_for_this_draw) * 100 / getamt(current_billed_amount));
        } else {
            _('retainage_amount_for_this_draw' + id).value == '';
            _('retainage_percent' + id).value == '';
        }
    } else {
        _('retainage_amount_for_this_draw' + id).value = updateTextView1(getamt(current_billed_amount) * 100 / getamt(retainage_percent));
    }





    retainage_amount_stored_materials = ev('retainage_amount_stored_materials' + id);


    if (getamt(current_stored_materials) > 0) {
        _('retainage_percent_stored_materials' + id).value = updateTextView1(getamt(retainage_amount_stored_materials) * 100 / getamt(current_stored_materials));
    } else {
        _('retainage_amount_stored_materials' + id).value == '';
        _('retainage_percent_stored_materials' + id).value == '';
    }


    retainage_amount_previously_withheld = ev('retainage_amount_previously_withheld' + id);
    retainage_release_amount = ev('retainage_release_amount' + id);
    retainage_amount_previously_stored_materials = ev('retainage_amount_previously_stored_materials' + id);
    retainage_amount_for_this_draw = ev('retainage_amount_for_this_draw' + id);
    retainage_amount_stored_materials = ev('retainage_amount_stored_materials' + id);
    retainage_stored_materials_release_amount = ev('retainage_stored_materials_release_amount' + id);



    _('retainage_percent_stored_materials' + id).value = updateTextView1(getamt(retainage_amount_stored_materials) * 100 / getamt(current_stored_materials));




    total_outstanding_retainage = updateTextView1(getamt(retainage_amount_previously_withheld) + getamt(retainage_amount_previously_stored_materials) +
        getamt(retainage_amount_for_this_draw) + getamt(retainage_amount_stored_materials) - getamt(retainage_release_amount) - getamt(retainage_stored_materials_release_amount));
    _('total_outstanding_retainage' + id).value = total_outstanding_retainage;

    total_billed = ev('total_billed' + id);
    _('net_billed_amount' + id).value = updateTextView1(getamt(total_billed) - getamt(total_outstanding_retainage));


    if (type != 1) {
        calculateTotal();
    }

}


function calculateCurrentBillAmount(id) {
    current_billed_percent = ev('current_billed_percent' + id);
    current_contract_amount = ev('current_contract_amount' + id);
    _('current_billed_amount' + id).value = updateTextView1(getamt(current_contract_amount) * getamt(current_billed_percent) / 100);
    calculateRow(id);

}
function calculateRetainageStoreMaterialAmount(id) {
    retainage_percent_stored_materials = ev('retainage_percent_stored_materials' + id);
    current_stored_materials = ev('current_stored_materials' + id);
    _('retainage_amount_stored_materials' + id).value = updateTextView1(getamt(current_stored_materials) * getamt(retainage_percent_stored_materials) / 100);
    calculateRow(id);

}

function calculateTotal() {
    total = 0;
    total_oca = 0;
    total_acoa = 0;
    total_cca = 0;

    total_pba = 0;
    total_cba = 0;
    total_psm = 0;
    total_csm = 0;
    total_sm = 0;
    total_tb = 0;
    total_rapw = 0;
    total_rad = 0;
    total_rra = 0;
    total_tor = 0;
    total_rasm = 0;
    total_rapsm = 0;
    total_rrasm = 0;

    $('input[name="pint[]"]').each(function (index, value) {
        pint = this.value;
        total = Number(total) + getamt(ev('net_billed_amount' + pint));
        total_oca = Number(total_oca) + getamt(ev('original_contract_amount' + pint));
        total_acoa = Number(total_acoa) + getamt(ev('approved_change_order_amount' + pint));
        total_cca = Number(total_cca) + getamt(ev('current_contract_amount' + pint));
        total_pba = Number(total_pba) + getamt(ev('previously_billed_amount' + pint));
        total_cba = Number(total_cba) + getamt(ev('current_billed_amount' + pint));
        total_psm = Number(total_psm) + getamt(ev('previously_stored_materials' + pint));
        total_csm = Number(total_csm) + getamt(ev('current_stored_materials' + pint));
        total_sm = Number(total_sm) + getamt(ev('stored_materials' + pint));
        total_tb = Number(total_tb) + getamt(ev('total_billed' + pint));
        total_rapw = Number(total_rapw) + getamt(ev('retainage_amount_previously_withheld' + pint));
        total_rad = Number(total_rad) + getamt(ev('retainage_amount_for_this_draw' + pint));
        total_rra = Number(total_rra) + getamt(ev('retainage_release_amount' + pint));
        total_tor = Number(total_tor) + getamt(ev('total_outstanding_retainage' + pint));
        total_rasm = Number(total_rasm) + getamt(ev('retainage_amount_stored_materials' + pint));
        total_rapsm = Number(total_rapsm) + getamt(ev('retainage_amount_previously_stored_materials' + pint));
        total_rrasm = Number(total_rrasm) + getamt(ev('retainage_stored_materials_release_amount' + pint));
    });


    document.getElementById('particulartotal').value = updateTextView1(total);
    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);
    document.getElementById('total_oca').innerHTML = updateTextView1(total_oca);
    document.getElementById('total_acoa').innerHTML = updateTextView1(total_acoa);
    document.getElementById('total_cca').innerHTML = updateTextView1(total_cca);

    document.getElementById('total_pba').innerHTML = updateTextView1(total_pba);
    document.getElementById('total_cba').innerHTML = updateTextView1(total_cba);
    document.getElementById('total_psm').innerHTML = updateTextView1(total_psm);
    document.getElementById('total_csm').innerHTML = updateTextView1(total_csm);
    document.getElementById('total_sm').innerHTML = updateTextView1(total_sm);
    document.getElementById('total_tb').innerHTML = updateTextView1(total_tb);
    document.getElementById('total_rapw').innerHTML = updateTextView1(total_rapw);
    document.getElementById('total_rad').innerHTML = updateTextView1(total_rad);
    document.getElementById('total_rra').innerHTML = updateTextView1(total_rra);
    total_tor = updateTextView1(total_tor);
    if (total_tor == '') {
        total_tor = 0;
    }
    document.getElementById('total_tor').innerHTML = total_tor;
    document.getElementById('total_rasm').innerHTML = updateTextView1(total_rasm);

    document.getElementById('total_rapsm').innerHTML = updateTextView1(total_rapsm);
    document.getElementById('total_rrasm').innerHTML = updateTextView1(total_rrasm);
}



function virtualSelectInit(id, type, index) {
    allowNewOption = true;
    search = true;
    dropboxWrapper = 'body';
    vs_class = 'vs-option1';
    _('span_' + type + id).style.display = 'none';
    _('vspan_' + type + id).style.display = 'block';

    if (type == 'group') {
        try {
            selectedValue = ev('group' + id);
        }
        catch (o) {
            selectedValue = '';
        }

        options = groups;
    } else if (type == 'cost_type') {
        options = merchant_cost_types;
        try {
            selectedValue = ev('cost_type' + id);
        }
        catch (o) {
            selectedValue = '';
        }
    } else if (type == 'bill_code_detail') {
        options = bill_code_details;
        try {
            selectedValue = ev('bill_code_detail' + id);
        }
        catch (o) {
            selectedValue = '';
        }
        if (selectedValue == '') {
            selectedValue = 'Yes';
        }
        search = false;
    } else if (type == 'bill_code') {
        vs_class = 'vs-option';
        options = csi_codes;
        try {
            selectedValue = ev('bill_code' + id);
        }
        catch (o) {
            selectedValue = '';
        }
    }


    VirtualSelect.init({
        ele: '#v_' + type + id,
        options: options,
        //name: type + '[]',
        dropboxWrapper: dropboxWrapper,
        allowNewOption: allowNewOption,
        search: search,
        multiple: false,
        selectedValue: selectedValue,
        additionalClasses: vs_class
    });

    $('.vscomp-toggle-button').not('.form-control, .input-sm').each(function () {
        $(this).addClass('form-control input-sm mw-150');
    })

    $('#v_' + type + id).change(function () {
        if (type === 'bill_code') {
            _('bill_code' + id).value = this.value;

            let displayValue = this.getDisplayValue().split('|');
            if (displayValue[1] !== undefined) {
                $('#description' + id).val(displayValue[1].trim())
            }
            if (this.value !== null && this.value !== '' && !only_bill_codes.includes(parseInt(this.value))) {
                //  only_bill_codes.push(this.value)
                $('#new_bill_code').val(this.value)
                $('#selectedBillCodeId').val(type + id)
                billIndex(0, 0, 0)
            }
        }
        if (type === 'group') {
            if (!groups.includes(this.value) && this.value !== '') {
                groups.push(this.value)
                for (let g = 0; g < particularray.length; g++) {
                    try {
                        let groupSelector = document.querySelector('#group' + particularray[g].pint);

                        if ('group' + id === 'group' + particularray[g].pint)
                            groupSelector.setOptions(groups, this.value);
                        else
                            groupSelector.setOptions(groups, particularray[g].group);

                    } catch (o) { }
                }
            }
            _('group' + id).value = this.value;
        }

        if (type === 'cost_type') {
            _('cost_type' + id).value = this.value;
        }

        if (type === 'bill_code_detail') {
            _('bill_code_detail' + id).value = this.value;
        }

        if (type === 'cost_codes' || type === 'cost_types') {
            //_('filterbutton').click();
        }
    });



    $('#v_' + type + id).on('beforeOpen', function () {
        //console.log('#'+type+id)
        let dropboxContainer = $('#' + type + id).find('.vscomp-ele-wrapper').attr('aria-controls');
        $('#' + dropboxContainer).css('z-index', 4);
    });
    try {
        $("#table-scroll").scroll(function () {
            //  document.querySelector('#' + type + id).close();
        });
    } catch (o) {

    }


}


function addNewBillCode(token) {
    var data = $("#billcodeform").serialize();

    var actionUrl = '/merchant/billcode/new';
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: {
            _token: token,
            bill_code: $('#new_bill_code').val(),
            bill_description: $('#new_bill_description').val(),
            project_id: $('#_project_id').val()
        },
        success: function (data) {
            let label = data.billCode.code + ' | ' + data.billCode.description
            //console.log(label)
            bill_codes.push(
                { value: data.billCode.id, label: label, description: data.billCode.description }
            )
            csi_codes.push(
                { value: data.billCode.id, label: label, description: data.billCode.description }
            )
            only_bill_codes.push(data.billCode.id)
            csi_codes_array[data.billCode.id] = { value: data.billCode.id, label: label, description: data.billCode.description }

            updateBillCodeDropdowns(bill_codes, data.billCode)
        }
    });

    /*let new_bill_code = $('#new_bill_code').val();
    let new_bill_description = $('#new_bill_description').val();
 
    let label = new_bill_code + ' | ' + new_bill_description
 
    bill_codes.push(
        {label: label, value : new_bill_code, description : new_bill_description }
    )
 
    this.updateBillCodeDropdowns(bill_codes, new_bill_code, new_bill_description);*/

    // initializeBillCodes();
    return false;
}

function updateBillCodeDropdowns(optionArray, newBillCode) {

    let selectedId = $('#selectedBillCodeId').val();

    for (let v = 0; v < particularray.length; v++) {
        let currentField = particularray[v];
        let billCodeSelector = document.querySelector('#v_bill_code' + currentField.pint);

        if (selectedId === 'v_bill_code' + currentField.pint) {

            billCodeSelector.setOptions(optionArray);
            billCodeSelector.setValue(newBillCode.id);

            only_bill_codes.push(newBillCode.id)

            particularray[v].bill_code = newBillCode.code;
            particularray[v].description = newBillCode.description;
            $('#description' + v).val(newBillCode.description)

        }

        //else billCodeSelector.setOptions(optionArray, particularray[v].bill_code);

    }
    closeSidePanelBillCode();

    $('#new_bill_code').val(null);
    $('#new_bill_description').val(null);
    $('#selectedBillCodeId').val(null);
}


function changeBillType(id) {
    if (_('bill_type' + id).value === 'Calculated') {
        _('add-calc-span' + id).innerHTML = '<a  style=" padding-top: 5px;"  href="javascript:;" onclick="OpenAddCaculated(' + id + ')" id="add-calc' + id + '">Add calculation</a><a  style="padding-top: 5px; padding-left: 5px; display: none;" href="javascript:;" onclick="EditCaculated(' + id + ')" id="edit-calc' + id + '">Override</a><span  style="margin-left: 4px; color:#859494;display: none;" id="pipe-calc' + id + '"> | </span><a  style="padding-top:5px;display: none;" href="javascript:;" onclick="RemoveCaculated(' + id + ')" id="remove-calc' + id + '">Remove</a>';
        RemoveCaculated(id);
    } else {
        _('add-calc-span' + id).innerHTML = '';
    }
}

function EditCaculated(id) {
    document.getElementById('selected_field_int').value = id;
    editCaculatedRow(id);
}


function RemoveCaculated(id) {
    _('original_contract_amount' + id).value = 0;
    calculateCurrentBillAmount(id);
}


function OpenAddCaculated(id) {
    document.getElementById('selected_field_int').value = id;
    OpenAddCaculatedRow(id);
}

function setAOriginalContractAmount() {
    id = document.getElementById('selected_field_int').value;
    calc_amount = document.getElementById("calc_amount").value;
    let valid = true;
    if ($('input[name^=calc-checkbox]:checked').length <= 0) {
        $('#calc_checkbox_error').html('Please select atleast one particular');
        valid = false;
    } else
        $('#calc_checkbox_error').html('');

    if ($('#calc_perc').val() === '' || $('#calc_perc').val() === null || $('#calc_perc').val() === '0') {
        $('#calc_perc_error').html('Please enter percentage');
        valid = false
    } else
        $('#calc_perc_error').html('');

    if (valid) {

        try {
            _('original_contract_amount' + id).value = calc_amount;
        } catch (o) { }

        setOriginalContractAmount();

        calculateCurrentBillAmount(id);

        // this.reflectOriginalContractAmountChange(this.fields[fieldIndex], fieldIndex);
    }

}

function reflectOriginalContractAmountChange(id) {

    let total = 0;
    for (let f = 0; f < this.fields.length; f++) {
        let currentValue = this.fields[f];
        let amount = (currentValue.original_contract_amount) ? currentValue.original_contract_amount : 0
        total = Number(total) + Number(getamt(amount));
        let calculatedRowValue = $('#calculated_row' + currentValue.pint).val()
        if (calculatedRowValue !== '') {
            let rowsIncludedInCalculation = JSON.parse(calculatedRowValue);

            if (rowsIncludedInCalculation.includes(id)) {
                const index = rowsIncludedInCalculation.indexOf(id);
                if (index > -1) {
                    this.reCalculateCalculatedRowValue(currentValue);
                }
            }
        }
        this.calc(currentValue);
        // this.fields[index].introw = index;
    }
    document.getElementById('particulartotal').value = updateTextView1(total);
    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

}