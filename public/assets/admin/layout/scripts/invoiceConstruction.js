var postJson = '';

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
    //_('current_contract_amount' + id).value = updateTextView1(getamt(original_contract_amount) + getamt(approved_change_order_amount));


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

    current_contract_amount = ev('current_contract_amount' + id);
    if (getamt(current_contract_amount) > 0) {
        _('current_billed_percent' + id).value = updateTextView1(getamt(current_billed_amount) * 100 / getamt(current_contract_amount));
    }

    if (retainage_percent_cal == 1) {
        if (getamt(current_billed_amount) > 0) {
            _('retainage_percent' + id).value = updateTextView1(getamt(retainage_amount_for_this_draw) * 100 / getamt(current_billed_amount));
        } else {
            _('retainage_amount_for_this_draw' + id).value == '';
            _('retainage_percent' + id).value == '';
        }
    } else {
        _('retainage_amount_for_this_draw' + id).value = updateTextView1(getamt(current_billed_amount) * getamt(retainage_percent) / 100);
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

    saveParticularRow(id);


}
function setBilltype(value, id) {
    var Complete = '';
    var Unit = '';
    var Calculated = '';
    var Cost = '';
    if (value == '% Complete') {
        Complete = 'selected';
    } else if (value == 'Unit') {
        Unit = 'selected';
    } else if (value == 'Calculated') {
        Calculated = 'selected';
    } else if (value == 'Cost') {
        Cost = 'selected';
    } else { }
    _('cell_bill_type_' + id).innerHTML = '<select required="" style="width: 100%; min-width: 150px;font-size: 12px;" id="bill_type' + id + '" name="bill_type[]" data-placeholder="Select.." class="form-control billTypeSelect input-sm" onchange="changeBillType(' + id + ')"> <option value="">Select..</option><option ' + Complete + '  value="% Complete">% Complete</option><option  ' + Unit + ' value="Unit">Unit</option><option  ' + Calculated + ' value="Calculated">Calculated</option><option ' + Cost + ' value="Cost">Cost</option></select>';
}

function setInput(id, okey) {
    var array = ['current_billed_percent', 'current_billed_amount', 'previously_stored_materials', 'current_stored_materials', 'retainage_percent', 'retainage_amount_for_this_draw', 'retainage_percent_stored_materials', 'retainage_amount_stored_materials', 'retainage_release_amount', 'retainage_stored_materials_release_amount', 'project', 'cost_code'];
    $.each(array, function (ind, key) {
        value = ev(key + id);
        div = '<input  value="' + value + '"';
        if (key == 'retainage_amount_for_this_draw') {
            div = div + 'onchange="' + "_('retainage_amount_change'+id).value='true'" + '"';
        } else if (key == 'retainage_amount_for_this_draw') {
            div = div + 'onchange="' + "_('retainage_amount_change'+id).value='false'" + '"';
        }
        div = div + ' type="text" onblur="';
        if (key == 'current_billed_percent') {
            div = div + 'calculateCurrentBillAmount(' + id + ');';
        }
        else if (key == 'retainage_percent_stored_materials') {
            div = div + 'calculateRetainageStoreMaterialAmount(' + id + ');';
        } else {
            div = div + 'calculateRow(' + id + ');';
        }
        div = div + '" name="' + key + '[]" class="form-control input-sm amtbox" id="' + key + id + '"/> ';
        _('span_' + key + id).innerHTML = div;
    });
    if (okey != '') {
        value = ev(okey + id);
        const end = value.length;
        _(okey + id).setSelectionRange(end, end);
        _(okey + id).focus();
    }

}


function addnewRow() {
    id = 0;
    $('input[name="pint[]"]').each(function (index, value) {
        pint = Number(this.value);
        if (pint > id) {
            id = pint + 1;
        }
    });
    id = id + 1;
    var mainDiv = document.getElementById('particular_body');
    var newDiv = document.createElement('tr');
    newDiv.setAttribute("id", id);
    newDiv.setAttribute("class", "sorted_table_tr");
    newDiv.innerHTML = '<td style="vertical-align: middle;" id="cell_bill_code_' + id + '" onclick="virtualSelectInit(' + id + ', ' + "'" + 'bill_code' + "'" + ',' + id + ')" class="td-c onhover-border  col-id-no"><div style="display:flex;"><span style="margin-right: 3px;" class="handle ui-sortable-handle"><svg xmlns="http://www.w3.org/2000/svg" class="svg-icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1"><path d="M384 64H256C220.66 64 192 92.66 192 128v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320H256c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64zM768 64h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64V128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z m0 320h-128c-35.34 0-64 28.66-64 64v128c0 35.34 28.66 64 64 64h128c35.34 0 64-28.66 64-64v-128c0-35.34-28.66-64-64-64z" fill="" /></svg></span><input type="hidden" id="bill_code' + id + '" value="" name="bill_code[]"><span id="span_bill_code' + id + '" style="width:80%"></span><span id="vspan_bill_code' + id + '" style="width:80%; display: none;"><div id="v_bill_code' + id + '">' +
        '</div></span><a onclick="showupdatebillcodeattachment(' + id + ');" id="attacha-' + id + '" style="align-self: center; margin-left: 3px;" class="pull-right popovers" data-original-title="" title=""><i id="icon-' + id + '" class="fa fa-paperclip" data-placement="top" data-container="body" data-trigger="hover" data-content="0 file " aria-hidden="true" data-original-title="" title="0 file"></i></a><input type="hidden" name="attachments[]" value="" id="attach-' + id + '"><input type="hidden" name="calculated_perc[]" value="" id="calculated_perc' + id + '"><input type="hidden" name="calculated_row[]" value="" id="calculated_row' + id + '"><input type="hidden" name="description[]" value="Concrete" id="description' + id + '">' +
        '<input type="hidden" name="billed_transaction_ids[]" value="" id="billed_transaction_ids' + id + '"><input id="id' + id + '" value="" type="hidden" name="id[]"><input id="pint' + id + '" value="' + id + '" type="hidden" name="pint[]"><input id="sort_order' + id + '" value="' + id + '" type="hidden" name="sort_order[]"><input type="hidden" name="sub_group[]" value=""><input type="hidden" id="retainage_amount_change' + id + '" value=""></div></td><td style="vertical-align: middle; min-width: 124px;" class="td-c onhover-border " id="cell_bill_type_' + id + '"><select required="" style="width: 100%; min-width: 150px;font-size: 12px;" id="bill_type' + id + '" name="bill_type[]" data-placeholder="Select.." class="form-control billTypeSelect input-sm" onchange="changeBillType(' + id + ')" fdprocessedid="mlc0v8"><option value="">Select..</option><option selected="" value="% Complete">% Complete</option><option value="Unit">Unit</option><option value="Calculated">Calculated</option><option value="Cost">Cost</option></select></td><td style="vertical-align: middle; " id="cell_cost_type_' + id + '" onclick="virtualSelectInit(' + id + ', ' + "'" + 'cost_type' + "'" + ',' + id + ')" class="td-c onhover-border "><input type="hidden" id="cost_type' + id + '" name="cost_type[]" value=""><span id="span_cost_type' + id + '"></span><span id="vspan_cost_type' + id + '" style="width: 100%; display: none;"><div id="v_cost_type' + id + '"></div></span></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="original_contract_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="original_contract_amount' + id + '" fdprocessedid="a2zfop">' +
        '<span id="add-calc-span' + id + '"></span></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="approved_change_order_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="approved_change_order_amount' + id + '" fdprocessedid="7twjb8"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="current_contract_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="current_contract_amount' + id + '" fdprocessedid="xez4' + id + 'f"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="previously_billed_percent[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="previously_billed_percent' + id + '" fdprocessedid="fmsnx"></td>' +
        '<td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="previously_billed_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="previously_billed_amount' + id + '" fdprocessedid="ml10dh"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateCurrentBillAmount(' + "'" + '' + id + '' + "'" + '); " name="current_billed_percent[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="current_billed_percent' + id + '" fdprocessedid="c5b71"></td>' +
        '<td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="current_billed_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="current_billed_amount' + id + '" fdprocessedid="0n' + id + 'b2h"><span id="add-cost-span' + id + '"></span></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="previously_stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="previously_stored_materials' + id + '" fdprocessedid="8p' + id + '2le"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="current_stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="current_stored_materials' + id + '" fdprocessedid="yu3lpe"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '">' +
        '<input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="stored_materials' + id + '" fdprocessedid="dp04z8"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="total_billed[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="total_billed' + id + '" fdprocessedid="0nf' + id + 'of"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" onchange="_(' + "'" + 'retainage_amount_change' + id + '' + "'" + ').value=' + "'" + 'false' + "'" + '" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_percent[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_percent' + id + '" fdprocessedid="8j1pbt"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_amount_previously_withheld[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_amount_previously_withheld' + id + '" fdprocessedid="oz01qt"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" onchange="_(' + "'" + 'retainage_amount_change' + id + '' + "'" + ').value=' + "'" + 'true' + "'" + '" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_amount_for_this_draw[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_amount_for_this_draw' + id + '"></td>' +
        '<td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRetainageStoreMaterialAmount(' + "'" + '' + id + '' + "'" + '); " name="retainage_percent_stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_percent_stored_materials' + id + '"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_amount_previously_stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_amount_previously_stored_materials' + id + '"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_amount_stored_materials[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_amount_stored_materials' + id + '"></td><td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="net_billed_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="net_billed_amount' + id + '"></td>' +
        '<td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_release_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_release_amount' + id + '"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="retainage_stored_materials_release_amount[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="retainage_stored_materials_release_amount' + id + '"></td>' +
        '<td style="vertical-align: middle;  background-color:#f5f5f5; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input readonly="" value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="total_outstanding_retainage[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="total_outstanding_retainage' + id + '"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="project[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="project' + id + '"></td><td style="vertical-align: middle; " class="td-c onhover-border " id="cell_current_billed_percent_' + id + '"><input value="" type="text" onblur=" calculateRow(' + "'" + '' + id + '' + "'" + '); " name="cost_code[]" style="width: 100%;border:none;text-align: center;background-color: transparent;" class="form-control input-sm " id="cost_code' + id + '"></td><td style="vertical-align: middle; " id="cell_group_' + id + '" onclick="virtualSelectInit(' + id + ', ' + "'" + 'group' + "'" + ',' + id + ')" class="td-c onhover-border "><input type="hidden" id="group' + id + '" name="group[]" value=""><span id="span_group' + id + '"></span><span id="vspan_group' + id + '" style="width: 100%; display: none;"><div id="v_group' + id + '"></div></span></td><td style="vertical-align: middle; " id="cell_bill_code_detail_' + id + '" onclick="virtualSelectInit(' + id + ', ' + "'" + 'bill_code_detail' + "'" + ',' + id + ')" class="td-c onhover-border "><input type="hidden" id="bill_code_detail' + id + '" name="bill_code_detail[]" value="Yes"><span id="span_bill_code_detail' + id + '">Yes</span><span id="vspan_bill_code_detail' + id + '" style="width: 100%; display: none;"><div id="v_bill_code_detail' + id + '"></div></span></td><td class="td-c " style="vertical-align: middle;width: 59px;"><button onclick="$(this).closest(' + "'tr'" + ').remove();calculateTotal();" type="button" class="btn btn-xs red">×</button></td>';
    mainDiv.appendChild(newDiv);
    virtualSelectInit(id, 'bill_code', id);
    virtualSelectInit(id, 'cost_type', id);
}

function saveParticularRow(id, active = 1) {
    json = '{';
    $.each(particular_column_array, function (index, av) {
        json = json + '"' + index + '":"' + ev(index + id) + '",';
    });
    json = json + '"request_id":"' + ev('request_id') + '",';
    json = json + '"is_active":"' + active + '",';
    json = json + '"dpid":"' + ev('dpid' + id) + '",';
    json = json + '"id":"' + ev('id' + id) + '",';
    json = json + '"sort_order":"' + ev('sort_order' + id) + '",';
    json = json + '"sub_group":"' + ev('sub_group' + id) + '",';
    json = json + '"calculated_perc":"' + ev('calculated_perc' + id) + '",';
    json = json + '"calculated_row":"' + ev('calculated_row' + id) + '",';
    json = json + '"billed_transaction_ids":"' + ev('billed_transaction_ids' + id) + '",';
    json = json + '"attachments":"' + ev('attachments' + id) + '",';
    json = json + '"pint":"' + ev('pint' + id) + '"}';
    if (postJson != json) {
        postJson = json;
        $.ajax({
            type: 'POST',
            url: '/merchant/invoice/particulars/row',
            data: { 'data': json },
            success: function (data) {
                _('dpid' + id).value = data;
            }
        });
    }
}

function loadDraft() {
    _('loader2').style.display = 'block';
    $("#draft").modal('hide');


    $.each(draft_particulars, function (index, av) {
        pint = av.pint;
        av.id = av.particular_id;
        if (av.is_active == 0) {
            _(pint).remove();
        } else {
            $.each(av, function (column, value) {
                try {
                    _(column + pint).value = value;
                } catch (o) { }
            });
            try {
                _('span_bill_code' + pint).innerHTML = csi_codes_array[av.bill_code].label;
            } catch (o) { }

            try {
                _('span_cost_type' + pint).innerHTML = cost_types_array[av.cost_type].label;
            } catch (o) { }

            try {
                _('span_bill_type' + pint).innerHTML = av.bill_type;
            } catch (o) { }
            setInput(pint, '');
        }
    });


    calculateTotal();
    _('loader2').style.display = 'none';
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
        if (_(pint).style.display != 'none') {
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
        }
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
    try {
        _('span_' + type + id).style.display = 'none';
        _('vspan_' + type + id).style.display = 'block';

    } catch (o) { }

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
                // $('#new_bill_code').val(this.value)
                //   $('#selectedBillCodeId').val(type + id)
                try {
                    billIndex(0, 0, 0)
                } catch (o) { }

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
    }
    else if (_('bill_type' + id).value === 'Cost') {
        _('add-cost-span' + id).innerHTML = '<a  style=" padding-top: 5px;"  href="javascript:;" onclick="OpenAddCost(' + id + ')" id="add-cost' + id + '">Add</a><a   style="padding-top: 5px; display: none;" href="javascript:;" onclick="RemoveCost(' + id + ')" id="remove-cost' + id + '">Remove</a>        <span   style="margin-left: 4px; color: rgb(133, 148, 148); display: none;" id="pipe-cost' + id + '"> | </span><a   style="padding-top: 5px; padding-left: 5px; display: none;" href="javascript:;" onclick="OpenAddCost(' + id + ',' + "'edit'" + ')" id="edit-cost' + id + '">Edit</a>';
    }
    else {
        _('add-calc-span' + id).innerHTML = '';
        _('add-cost-span' + id).innerHTML = '';
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

function OpenAddCost(id, type = 'new') {
    billed_transactions = [];
    billed_transactions_filter = [];
    OpenAdCostRow();
    document.getElementById('cost_selected_id').value = id;

    cost_code_selected = _('bill_code' + id).value;
    cost_type_selected = _('cost_type' + id).value;
    VirtualSelect.init({ ele: '#v_bill_codecost' });
    VirtualSelect.init({ ele: '#v_cost_typecost' });



    document.querySelector('#v_cost_typecost').setValue(cost_type_selected);
    document.querySelector('#v_bill_codecost').setValue(cost_code_selected);

    billed_transactions_array.forEach(function (currentValue, index, arr) {
        billed_transactions_filter.push(currentValue);
    });
    filterCost(type, id);

    if (type == 'edit') {
        _('billed_transaction_ids').value = _('billed_transaction_ids' + id).value;
        _('cost_amount').value = updateTextView1(ev('current_billed_amount' + id));

    }


}



function filterCost(type, id) {

    _('allCheckboxcost').checked = false;
    allcostCheck();
    var billed_transactions_id_array = [];
    var array = [];
    var ignore_array = [];
    var int = _('cost_selected_id').value;

    $('input[name="pint[]"]').each(function (ind, value) {
        index = this.value;
        if (index != int) {
            billed_transaction_ids = _('billed_transaction_ids' + index).value;
            if (billed_transaction_ids != '') {
                var array = JSON.parse(billed_transaction_ids);
                array.forEach(function (arrv, ii, aaa) {
                    ignore_array.push(arrv);
                });
            }
        }
    });
    cost_code_selected = document.querySelector('#v_bill_codecost').value;
    cost_type_selected = document.querySelector('#v_cost_typecost').value;
    if (type == 'edit') {
        var exist_array = _('billed_transaction_ids' + id).value;
    }
    billed_transactions_filter.forEach(function (currentValue, index, arr) {
        var filter = true;
        currentValue.checked = '';
        if (cost_code_selected != '') {
            if (cost_code_selected != currentValue.cost_code) {
                filter = false;
            }
        }

        if (cost_type_selected != '') {
            if (cost_type_selected != currentValue.cost_type) {
                filter = false;
            }
        }

        if (ignore_array.includes(currentValue.id)) {
            filter = false;
        }

        if (type == 'edit') {
            costid = '"' + currentValue.id + '"';
            if (exist_array.indexOf(costid) > -1) {
                currentValue.checked = 'checked';
                filter = true;
            }
        }


        if (filter == true) {
            array.push(currentValue);
            billed_transactions_id_array.splice(index, 0, currentValue.id);
        }

    });
    billed_transactions = array;
    billed_transactions_id_array = billed_transactions_id_array;
    console.log(csi_codes_array);

    var mainDiv = document.getElementById('new_particular_cost');
    _('new_particular_cost').innerHTML = '';
    billed_transactions.forEach(function (field, index, arr) {
        var newDiv = document.createElement('tr');
        csi_code = csi_codes_array[field.cost_code].label;
        newDiv.innerHTML = '<td class="td-c"><input type="checkbox" ' + field.checked + ' name="cost-checkbox[]" value="' + field.id + '" onchange="costCalc();"></td><td class="td-c" >' + csi_code + '</td><td class="td-c" >' + field.cost_type_label + '</td><td class="td-c" >' + field.rate + '</td><td class="td-c" >' + field.unit + '</td><td class="td-c" id="costamt' + field.id + '">' + field.amount + '</td><td class="td-c" >' + field.description + '</td>';
        mainDiv.appendChild(newDiv);
    });

}
function allcostCheck() {
    var check = _('allCheckboxcost').checked;
    $('input[name="cost-checkbox[]"]').each(function (ind, value) {
        this.checked = check;
    });
    this.costCalc();
}
function costCalc() {
    total = 0;
    ids = [];

    $('input[name="cost-checkbox[]"]').each(function (ind, value) {
        checked = this.checked;
        id = this.value;
        amount = getamt(_('costamt' + id).innerHTML);
        if (checked == true) {
            ids.push(id);
            total = Number(total) + amount;
        }
    });


    _('billed_transaction_ids').value = JSON.stringify(ids);
    _('cost_amount').value = updateTextView1(total);
}
function setCostAmount() {
    if ($('input[name^=cost-checkbox]:checked').length <= 0) {
        $('#cost_checkbox_error').html('Please select atleast one transaction');
    } else {
        $('#cost_checkbox_error').html('');
        let cost_selected_id = document.getElementById('cost_selected_id').value;
        let calc_amount = updateTextView1(getamt(document.getElementById("cost_amount").value));
        _('current_billed_amount' + cost_selected_id).value = calc_amount;
        _('billed_transaction_ids' + cost_selected_id).value = ev('billed_transaction_ids');
        // document.getElementById('billed_transaction_ids' + cost_selected_id).value = document.getElementById('billed_transaction_ids').value;
        setInput(cost_selected_id, 'current_billed_amount');
        calculateRow(cost_selected_id);
        closeSidePanelcost();
        _('add-cost' + cost_selected_id).style.display = 'none';
        _('remove-cost' + cost_selected_id).style.display = 'inline-block';
        _('edit-cost' + cost_selected_id).style.display = 'inline-block';
    }
}

function RemoveCost(id) {

    _('current_billed_amount' + id).value = '';
    _('billed_transaction_ids' + id).value = '';
    calculateRow(id);
}




function closeSidePanelcost() {
    document.getElementById("panelWrapIdcost").style.boxShadow = "none";
    document.getElementById("panelWrapIdcost").style.transform = "translateX(100%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    $('.page-content-wrapper').css('pointer-events', 'auto');
    _('allCheckboxcost').checked = false;
    allcostCheck();
}


function closeAttachmentPanel() {
    // let attachment_pos = $('#attachment_pos_id').val();
    //let attach_index = $('#index' + attachment_pos).val();
    //let vals = document.getElementById('attach-' + attachment_pos).value;
    // _('attachments' + attach_index) = vals;
    // this.fields[attach_index].attachments = particularray[attach_index].attachments;

    //reset attachment pos id in attachment modal
    document.getElementById('attachment_pos_id').value = '';
    return closeSidePanelBillCodeAttachment();
}

function closeSidePanelBillCodeAttachment() {
    try {
        var closebutton = document.getElementsByClassName("uppy-u-reset uppy-c-btn uppy-StatusBar-actionBtn uppy-StatusBar-actionBtn--done")[0].click();
    } catch (o) { }
    document.getElementById("listtab2").classList.remove('active');
    document.getElementById("tab2").classList.remove('active')
    document.getElementById("panelWrapIdBillCodeAttachment").style.boxShadow = "none";
    document.getElementById("panelWrapIdBillCodeAttachment").style.transform = "translateX(100%)";
    $("#billcodeform").trigger("reset");
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    $('.page-content-wrapper').css('pointer-events', 'auto');
    return false;
}

function filterRows() {
    var input, filter, table, tr, td, i, txtValue;
    table = document.getElementById("particular_body");
    tr = table.getElementsByTagName("tr");
    dropdown_search = document.getElementById("dropdown_search").value;
    search = document.getElementById("search").value.toUpperCase();
    for (i = 0; i <= tr.length - 1; i++) {
        display = "";
        if (search != '') {
            search_td = tr[i].getElementsByTagName("td")[0];
            txtValue = search_td.textContent || search_td.innerText;
            txtValue = txtValue.toUpperCase();
            if (txtValue.toUpperCase().indexOf(search) > -1) {} else {
                display = 'none';
            }
        }

        if (dropdown_search > 0) {
            if (dropdown_search == 1) {
                input_name = 'previously_billed_amount';
            } else if(dropdown_search == 3){
                input_name = 'previously_billed_amount';
            } else {
                input_name = 'current_billed_amount';
            }

            amt_val = parseFloat(document.getElementsByName(input_name + '[]')[i].value.replace(/,/g, ''));
            if(dropdown_search == 3){
                if (amt_val > 0 || isNaN(amt_val)) {
                    display = 'none';
                } 
            }else{
                if (amt_val > 0) {} else {
                    display = 'none';
                }
            }
        }
        tr[i].style.display = display;
    }
    calculateTotal();

    return false;
}
