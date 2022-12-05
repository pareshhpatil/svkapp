var invoice_list = null;

function setUpdateMaster(link, name) {
    document.getElementById('name_').value = name;
    document.getElementById('id').value = link;
}

function setCreateMaster(type) {
    $("#frm_master").trigger("reset");
    $("#po-category-error-msg").css('display', 'none');
    document.getElementById("master_type").value = type;
    document.getElementById("master_title").innerHTML = 'Create ' + type;
}

function saveExpenseMaster() {
    var master_type = document.getElementById("master_type").value;
    var data = $("#frm_master").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/saveExpenseMaster/' + master_type,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                var x = document.getElementById(master_type);
                var option = document.createElement("option");
                option.text = obj.name;
                option.value = obj.id;
                option.selected = 1;
                x.add(option);
                $("#" + master_type).select2();
                $("#mstclg").click();
                $("#po-category-error-msg").css('display', 'none');
                $("#frm_master").trigger("reset");
            } else {
                $("#po-category-error-msg").find("ul").html('');
                $("#po-category-error-msg").css('display', 'block');
                $("#po-category-error-msg").find("ul").append('<li>' + obj.error + '</li>');
            }
        }
    });

    return false;
}

function setTransferData(id) {
    var data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getTransferDetail/' + id,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            document.getElementById('fbank_name').value = obj.bank_name;
            document.getElementById('amount').value = obj.amount;
            document.getElementById('date').value = obj.transfer_date;
            document.getElementById('fcash_paid_to').value = obj.cash_paid_to;
            document.getElementById('fcheque_no').value = obj.cheque_no;
            document.getElementById('fbank_transaction_no').value = obj.bank_transaction_no;
            document.getElementById('payment_mode').value = obj.offline_response_type;
        }
    });
}

function saveExpenseSequence(type) {
    var data = $("#submit_form").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/saveExpensesequence/' + type,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                document.getElementById('seq_id').value = obj.id;
                if (obj.auto_generate == 1) {
                    document.getElementById("expense_auto_generate").readOnly = true;
                    document.getElementById("expense_auto_generate").value = 'Auto generate';
                } else {
                    document.getElementById("expense_auto_generate").readOnly = false;
                    document.getElementById("expense_auto_generate").value = '';
                }
            }
            $("#closebutton").click();
        }
    });

    return false;
}

function AddExpenseParticular() {
    Numrow = 1;
    while (document.getElementById('cost' + Numrow)) {
        Numrow = Numrow + 1;
    }
    var tax = '<select class="form-control"  onchange="calculateexpensecost();" name="tax[]" ><option value="0">Select tax</option><option value="1">Non Taxable</option><option value="2">GST @0%</option><option value="3">GST @5%</option><option value="4">GST @12%</option><option value="5">GST @18%</option><option value="6">GST @28%</option></select>';
    var mainDiv = document.getElementById('new_particular');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><input type="text" required name="particular[]" class="form-control " placeholder="Particular"></td><td><input type="text" name="sac[]" class="form-control " placeholder="SAC/HSN Code"></td><td><input type="number" step="1" name="unit[]" onblur="calculateexpensecost();" class="form-control " placeholder="Unit"></td><td><input type="number" step="0.01" name="rate[]" onblur="calculateexpensecost();" class="form-control " placeholder="Rate"></td><td>' + tax + '</td><td><input type="text"  name="total[]" class="form-control " readonly ><input type="hidden" name="total_amt[]" value="0.00"><input type="hidden" name="particular_id[]" value="Na"></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();calculateexpensecost();" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);

}

function setGstType(code) {
    merchant_state_code = document.getElementById('merchant_state_code').value;
    if (merchant_state_code != code) {
        document.getElementById('gst_type').value = 'inter';
    } else {
        document.getElementById('gst_type').value = 'intra';
    }
    calculateexpensecost();
}
function setCustomerType(type) {
    if (type == 'B2B') {
        $('#cname').prop('required', true);
        $('#ctin').prop('required', true);
    } else {
        $('#cname').prop('required', false);
        $('#ctin').prop('required', false);
    }

}

function validatefilesize(maxsize, id) {
    var x = document.getElementById(id);
    var txt = "";
    max = '3 MB';
    if ('files' in x) {

        if (x.files.length == 0) {
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if (file.size > maxsize) {
                    alert('File size should be less than ' + max);
                    try {
                        document.getElementById(id).value = "";
                    } catch (c) {
                        alert(c.message);
                    }
                    document.getElementById('imgdismiss').click();
                    return false;
                }
            }
        }
    }
}


function setVendorState(vendor_id) {
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getVendorGSTSTate/' + vendor_id,
        data: data,
        success: function (data) {
            document.getElementById('gst_type').value = data;
        }
    });
}
function setCustomerState(customer_id) {
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getVendorGSTSTate/' + customer_id + '/customer',
        data: data,
        success: function (data) {
            document.getElementById('gst_type').value = data;
        }
    });
}

function setCustomerDetails(customer_id) {
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getCustomerInvoice/' + customer_id,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            document.getElementById('gst_type').value = obj.type;
            invoice_list = obj['invoice'];
            $('#invoice_select').empty();
            $('#invoice_select').append('<option value="">Select Invoice</option>')
            $.each(invoice_list, function (index, value) {
                $('#invoice_select').append('<option value="' + index + '">' + value.invoice_number + ' | ' + value.total + ' | ' + value.bill_date + '</option>');
            });

        }
    });
}

function setInvoiceDetails(number) {
    $('#bill_date').val(invoice_list[number].bill_date);
    $('#due_date').val(invoice_list[number].due_date);
}

function toggleInvoice(type) {
    if (type == 1) {
        _('inv_text').style.display = 'none';
        _('inv_dropdown').style.display = 'table';
        $('#invoice_text').prop('required', false);
        $('#invoice_select').prop('required', true);
    } else {
        _('inv_text').style.display = 'table';
        _('inv_dropdown').style.display = 'none';
        $('#invoice_text').prop('required', true);
        $('#invoice_select').prop('required', false);
    }
}

function calculateexpensecost() {
    var form_name = 'frm_expense';
    var unit = 'unit[]';
    var rate = 'rate[]';
    var tax = 'tax[]';
    var total = 'total[]';
    var formObj = document.forms[form_name];
    var num = $('input[name="unit[]"]').length;
    tds_amt = 0;
    var amount = 0;
    var total_tax = 0;
    for (var i = 0; i < num; i++) {
        price = Number($('input[name="rate[]"]')[i].value);
        b_qty = Number($('input[name="unit[]"]')[i].value);
        percent = 0;
        tax_amount = 0;
        tax = Number($('select[name="tax[]"]')[i].value);
        if (tax == 3) {
            percent = 5;
        } else if (tax == 4) {
            percent = 12;
        }
        else if (tax == 5) {
            percent = 18;
        }
        else if (tax == 6) {
            percent = 28;
        }

        total_price = b_qty * price;

        if (percent > 0) {
            tax_amount = total_price * percent / 100;
            total_tax = total_tax + tax_amount;
        }
        $('input[name="total[]"]')[i].value = total_price;
        try {
            $('input[name="total_amt[]"]')[i].value = roundamt(total_price + tax_amount);
        } catch (o) {
        }
        amount = Number(amount + total_price);

    }
    try {
        discount = Number(document.getElementById('discount').value);
    } catch (o) {
        discount = 0;
    }

    try {
        adjustment = Number(document.getElementById('adjustment').value);
    } catch (o) {
        adjustment = 0;
    }
    try {
        tds = Number(document.getElementById('tds').value);
    } catch (o) {
        tds = 0;
    }
    document.getElementById('sub_total').value = roundamt(amount);
    if (total_tax > 0) {
        gst_type = document.getElementById('gst_type').value;

        if (gst_type == 'intra') {
            document.getElementById('sgst').style.display = '';
            document.getElementById('cgst').style.display = '';
            document.getElementById('igst').style.display = 'none';
            document.getElementById('cgst_amt').value = roundamt(Number(total_tax / 2));
            document.getElementById('sgst_amt').value = roundamt(Number(total_tax / 2));
        } else {
            document.getElementById('sgst').style.display = 'none';
            document.getElementById('cgst').style.display = 'none';
            document.getElementById('igst').style.display = '';
            document.getElementById('igst_amt').value = roundamt(total_tax);
        }
    }

    if (tds > 0) {
        tds_amt = amount * tds / 100;
    }

    grand_total = amount + total_tax - discount + adjustment - tds_amt;
    document.getElementById('total').value = roundamt(grand_total);

}

function paymentStatus(status) {
    if (status == 1) {
        document.getElementById('paymentmode').style.display = 'block';
        document.getElementById('payment_mode').required = true;
        document.getElementById('amount').required = true;
        document.getElementById('date').required = true;
    } else {
        document.getElementById('paymentmode').style.display = 'none';
        document.getElementById('payment_mode').required = false;
        document.getElementById('date').required = false;
        document.getElementById('amount').required = false;
    }
}

function responseType(type) {
    if (type == 1) {
        document.getElementById('bank_transaction_no').style.display = 'block';
        document.getElementById('cheque_no').style.display = 'none';
        try {
            document.getElementById('bank_name').style.display = 'block';
        } catch (o) {

        }
        document.getElementById('cash_paid_to').style.display = 'none';
        $('#bank_transaction_no_').prop('required', true);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', false);

    } else if (type == 2) {
        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'block';
        try {
            document.getElementById('bank_name').style.display = 'block';
        } catch (o) {

        }
        document.getElementById('cash_paid_to').style.display = 'none';
        $('#bank_transaction_no_').prop('required', false);
        $('#cheque_no_').prop('required', true);
        $('#cash_paid_to_').prop('required', false);

    } else if (type == 3) {
        document.getElementById('bank_transaction_no').style.display = 'none';
        try {
            document.getElementById('bank_name').style.display = 'none';
        } catch (o) {

        }
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'block';
        $('#bank_transaction_no_').prop('required', false);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', true);

    }
    else if (type == 4) {
        document.getElementById('bank_transaction_no').style.display = 'block';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('bank_name').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'none';
        $('#bank_transaction_no_').prop('required', true);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', false);

    }

    else if (type == 5) {
        document.getElementById('bank_transaction_no').style.display = 'block';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('bank_name').style.display = 'block';
        document.getElementById('cash_paid_to').style.display = 'none';
        $('#bank_transaction_no_').prop('required', true);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', false);

    }
}

function roundamt(amt) {
    return Math.round(100 * amt) / 100;
}

function setPaymentData(status, mode, id, vendor_id, transfer_id) {
    document.getElementById('vendor_id').value = vendor_id;
    document.getElementById('payment_status').value = status;
    document.getElementById('payment_mode').value = mode;
    document.getElementById('expense_id').value = id;
    document.getElementById('transfer_id').value = transfer_id;
    paymentStatus(status);
    if (transfer_id > 0) {
        setTransferData(transfer_id);
    } else {
        document.getElementById('fbank_name').value = '';
        document.getElementById('amount').value = '';
        document.getElementById('date').value = '';
        document.getElementById('fcash_paid_to').value = '';
        document.getElementById('fcheque_no').value = '';
        document.getElementById('fbank_transaction_no').value = '';
    }
}

function setFranchiseInvoiceDetails(number) {
    $('#bill_date').val(invoice_list[number].bill_date);
    $('#due_date').val(invoice_list[number].due_date);
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getFranchiseInvoiceDetail/' + invoice_list[number].id,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            sales = obj['sales'];
            summary = obj['summary'];
            $('#tb_gross_sale').empty();

            var mainDiv = document.getElementById('tb_gross_sale');
            $.each(sales, function (index, value) {
                var newDiv = document.createElement('tr');
                newDiv.innerHTML = '<td class="td-c"><input type="text" required="" value="' + value.sale_date + '" name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" value="' + value.gross_sale + '" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" value="' + value.gross_sale + '"  onblur="calculateFranchiseSaleNew();" name="new_gross_sale[]" class="form-control"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
                mainDiv.appendChild(newDiv);
            });
            _('gcp').value = summary.commision_fee_percent;
            _('new_gcp').value = summary.commision_fee_percent;
            _('wcp').value = summary.commision_waiver_percent;
            _('new_wcp').value = summary.commision_waiver_percent;
            _('ncp').value = summary.commision_net_percent;
            _('new_ncp').value = summary.commision_net_percent;
            _('penalty').value = summary.penalty;
            _('new_penalty').value = summary.penalty;
            _('previous_due').value = summary.previous_due;
            _('new_previous_due').value = summary.previous_due;
            setdatepicker();
            calculateFranchiseSale();

        }
    });
}

function setFranchiseInvoiceDetailsNonBrand(number) {
    $('#bill_date').val(invoice_list[number].bill_date);
    $('#due_date').val(invoice_list[number].due_date);
    data = '';
    $.ajax({
        type: 'GET',
        url: '/ajax/getFranchiseInvoiceDetail/' + invoice_list[number].id,
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            sales = obj['sales'];
            summary = obj['summary'];
            $('#tb_gross_sale_non_brand').empty();

            var mainDiv = document.getElementById('tb_gross_sale_non_brand');
            $.each(sales, function (index, value) {
                var newDiv = document.createElement('tr');
                newDiv.innerHTML = '<td class="td-c"><input type="text" required="" value="' + value.sale_date + '" name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" value="' + value.gross_sale + '" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td>				<td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" value="' + value.non_brand_gross_sale + '" required="" onblur="calculateFranchiseSale();" name="non_brand_gross_sale[]" class="form-control"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" value="' + value.gross_sale + '"  onblur="calculateFranchiseSaleNew();" name="new_gross_sale[]" class="form-control"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" value="' + value.non_brand_gross_sale + '"  onblur="calculateFranchiseSaleNew();" name="non_brand_new_gross_sale[]" class="form-control"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
                mainDiv.appendChild(newDiv);
            });
            _('gcp').value = summary.commision_fee_percent;
            _('new_gcp').value = summary.commision_fee_percent;
            _('non_brand_gcp').value = summary.non_brand_commision_fee_percent;
            _('non_brand_new_gcp').value = summary.non_brand_commision_fee_percent;
            _('wcp').value = summary.commision_waiver_percent;
            _('new_wcp').value = summary.commision_waiver_percent;
            _('non_brand_wcp').value = summary.non_brand_commision_waiver_percent;
            _('non_brand_new_wcp').value = summary.non_brand_commision_waiver_percent;
            _('ncp').value = summary.commision_net_percent;
            _('new_ncp').value = summary.commision_net_percent;
            _('non_brand_ncp').value = summary.non_brand_commision_net_percent;
            _('non_brand_new_ncp').value = summary.non_brand_commision_net_percent;
            _('penalty').value = summary.penalty;
            _('new_penalty').value = summary.penalty;
            _('previous_due').value = summary.previous_due;
            _('new_previous_due').value = summary.previous_due;
            setdatepicker();
            calculateFranchiseSale();

        }
    });
}


function addGrossSaleRow() {
    var mainDiv = document.getElementById('tb_gross_sale');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td class="td-c"><input type="text" required=""  name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSaleNew();" name="new_gross_sale[]" class="form-control"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);
    setdatepicker();
}

function addGrossSaleRowNonBrand() {
    var mainDiv = document.getElementById('tb_gross_sale_non_brand');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td class="td-c"><input type="text" required=""  name="sale_date[]" class="form-control date-picker" placeholder="Date"  autocomplete="off" data-date-format="dd M yyyy" ></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="gross_sale[]" class="form-control"><input type="hidden" name="gs_id[]" value="0"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSale();" name="non_brand_gross_sale[]" class="form-control"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSaleNew();" name="new_gross_sale[]" class="form-control"></td><td colspan="2" class="td-c"><input type="number" step="0.01" placeholder="Gross sale" required="" onblur="calculateFranchiseSaleNew();" name="non_brand_new_gross_sale[]" class="form-control"></td><td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();calculateFranchiseSale();" class="btn btn-xs red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);
    setdatepicker();
}

function roundAmount(amt) {
    return (Math.round(100 * amt) / 100).toFixed(2);
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
    _('particulartotal').value = net_val;
    franchiseSummary();
    calculateFranchiseSaleNonBrand();
    calculateFranchiseSaleNew();

}

function calculateFranchiseSaleNonBrand() {
    try {
        var total_gross = 0;
        $('input[name="non_brand_gross_sale[]"]').each(function (indx, arr) {
            total_gross = Number(total_gross) + Number($(this).val());
        });
        _('non_brand_gbs').value = roundAmount(total_gross);
        net_val = roundAmount(total_gross * 100 / 105);
        _('non_brand_sale_tax').value = roundAmount(total_gross - net_val);
        _('non_brand_nbs').value = net_val;

        _('particulartotal').value = net_val + Number(_('nbs').value);
    }
    catch (o) { }
    franchiseSummaryNonBrand();
    calculateFranchiseSaleNewNonBrand();

}

function franchiseSummaryNonBrand() {
    try {
        var gross = Number(_('non_brand_gbs').value);
        var sale_tax = Number(_('non_brand_sale_tax').value);
        net_sale = roundAmount(gross - sale_tax);
        _('non_brand_nbs').value = net_sale;
        _('non_brand_gca').value = roundAmount(Number(_('non_brand_gcp').value) * net_sale / 100);
        _('non_brand_wca').value = roundAmount(Number(_('non_brand_wcp').value) * net_sale / 100);
        net_fee = roundAmount(Number(_('non_brand_ncp').value) * net_sale / 100);
        //console.log(net_fee);
        _('non_brand_nca').value = net_fee;

        _('particulartotal').value = roundAmount(Number(_('penalty').value) + Number(net_fee) + Number(_('nca').value));
        _('invoice_total').value = roundAmount(Number(_('particulartotal').value) + Number(_('totaltaxcost').value));
        total = Number(_('invoice_total').value);
        gst = total * 0.18;
        _('gstamt').value = roundAmount(gst);
        total = roundAmount(Number(total + gst));
        _('grand_total').value = roundAmount(total + Number(_('previous_due').value));
    }
    catch (o) { alert(o.Message()) }

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
    _('invoice_total').value = roundAmount(Number(_('particulartotal').value) + Number(_('totaltaxcost').value));
    total = Number(_('invoice_total').value);
    gst = total * 0.18;
    _('gstamt').value = roundAmount(gst);
    total = roundAmount(Number(total + gst));
    _('grand_total').value = roundAmount(total + Number(_('previous_due').value));

    franchiseSummaryNonBrand()
}

function calculateFranchiseSaleNew() {
    var total_gross = 0;
    $('input[name="new_gross_sale[]"]').each(function (indx, arr) {
        total_gross = Number(total_gross) + Number($(this).val());
    });
    _('new_gbs').value = roundAmount(total_gross);
    net_val = roundAmount(total_gross * 100 / 105);
    _('new_sale_tax').value = roundAmount(total_gross - net_val);
    _('new_nbs').value = net_val;
    _('new_particulartotal').value = net_val;
    franchiseSummaryNew();
    calculateFranchiseSaleNewNonBrand();

}

function calculateFranchiseSaleNewNonBrand() {
    try {
        var total_gross = 0;
        $('input[name="non_brand_new_gross_sale[]"]').each(function (indx, arr) {
            total_gross = Number(total_gross) + Number($(this).val());
        });
        _('non_brand_new_gbs').value = roundAmount(total_gross);
        net_val = roundAmount(total_gross * 100 / 105);
        _('non_brand_new_sale_tax').value = roundAmount(total_gross - net_val);
        _('non_brand_new_nbs').value = net_val;
        _('non_brand_new_particulartotal').value = net_val + Number(_('new_particulartotal').value);
    } catch (o) { }
    franchiseSummaryNewNonBrand();

}

function franchiseSummaryNewNonBrand() {
    try {
        var gross = Number(_('non_brand_new_gbs').value);
        var sale_tax = Number(_('non_brand_new_sale_tax').value);
        net_sale = roundAmount(gross - sale_tax);
        _('non_brand_new_nbs').value = net_sale;
        _('non_brand_new_gca').value = roundAmount(Number(_('non_brand_new_gcp').value) * net_sale / 100);
        _('non_brand_new_wca').value = roundAmount(Number(_('non_brand_new_wcp').value) * net_sale / 100);
        net_fee = roundAmount(Number(_('non_brand_new_ncp').value) * net_sale / 100);
        _('non_brand_new_nca').value = net_fee;

        _('new_particulartotal').value = roundAmount(Number(_('new_penalty').value) + Number(net_fee) + Number(_('new_nca').value));
        _('new_invoice_total').value = roundAmount(Number(_('new_particulartotal').value) + Number(_('new_totaltaxcost').value));


        total = Number(_('new_invoice_total').value);
        gst = total * 0.18;
        _('new_gstamt').value = roundAmount(gst);
        total = roundAmount(Number(total + gst));

        _('new_grand_total').value = roundAmount(total + Number(_('new_previous_due').value));
        _('sub_total').value = roundAmount(Number(_('grand_total').value) - Number(_('new_grand_total').value));
        _('rate').value = roundAmount(Number(_('invoice_total').value) - Number(_('new_invoice_total').value));
    } catch (o) { }
    calculateexpensecost();
}

function franchiseSummaryNew() {
    var gross = Number(_('new_gbs').value);
    var sale_tax = Number(_('new_sale_tax').value);
    net_sale = roundAmount(gross - sale_tax);
    _('new_nbs').value = net_sale;
    _('new_gca').value = roundAmount(Number(_('new_gcp').value) * net_sale / 100);
    _('new_wca').value = roundAmount(Number(_('new_wcp').value) * net_sale / 100);
    net_fee = roundAmount(Number(_('new_ncp').value) * net_sale / 100);
    _('new_nca').value = net_fee;

    _('new_particulartotal').value = roundAmount(Number(_('new_penalty').value) + Number(net_fee));
    _('new_invoice_total').value = roundAmount(Number(_('new_particulartotal').value) + Number(_('new_totaltaxcost').value));


    total = Number(_('new_invoice_total').value);
    gst = total * 0.18;
    _('new_gstamt').value = roundAmount(gst);
    total = roundAmount(Number(total + gst));

    _('new_grand_total').value = roundAmount(total + Number(_('new_previous_due').value));
    _('sub_total').value = roundAmount(Number(_('grand_total').value) - Number(_('new_grand_total').value));
    _('rate').value = roundAmount(Number(_('invoice_total').value) - Number(_('new_invoice_total').value));
    franchiseSummaryNewNonBrand();
    calculateexpensecost();
}