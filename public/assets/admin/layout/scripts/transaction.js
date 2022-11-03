var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

function responseType(type)
{
    if (type == 1)
    {
        document.getElementById('bank_transaction_no').style.display = 'block';
        document.getElementById('cheque_no').style.display = 'none';
        try {
            document.getElementById('bank_name').style.display = 'block';
        } catch (o) {

        }
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('cod_status').style.display = 'none';
        $('#bank_transaction_no_').prop('required', true);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', false);

    } else if (type == 2)
    {
        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'block';
        try {
            document.getElementById('bank_name').style.display = 'block';
        } catch (o) {

        }
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('cod_status').style.display = 'none';
        $('#bank_transaction_no_').prop('required', false);
        $('#cheque_no_').prop('required', true);
        $('#cash_paid_to_').prop('required', false);

    } else if (type == 3)
    {
        document.getElementById('bank_transaction_no').style.display = 'none';
        try {
            document.getElementById('bank_name').style.display = 'none';
        } catch (o) {

        }
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'block';
        document.getElementById('cod_status').style.display = 'block';
        $('#bank_transaction_no_').prop('required', false);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', true);

    } else if (type == 5)
    {
        document.getElementById('bank_transaction_no').style.display = 'block';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('bank_name').style.display = 'block';
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('cod_status').style.display = 'none';
        $('#bank_transaction_no_').prop('required', true);
        $('#cheque_no_').prop('required', false);
        $('#cash_paid_to_').prop('required', false);

    }
}



function updateRespond(respondId)
{
    document.getElementById('paymentresponse_id').value = respondId;
    document.getElementById("myForm").submit();
}

function validateEvent()
{

    return true;
    count = datee.length;
    if (count < occu)
    {
        alert('Please choose all start dates of occurrences.');
        return false;
    } else
    {
        return true;
    }

}

function handleCoupon(status, coupon_type)
{
    try {
        grand_total = document.getElementById('bill_total').value;
        absolute_cost = document.getElementById('grand_total').value;
        surcharge_amount = document.getElementById('surcharge_amount').value;
        fee_id = document.getElementById('fee_id').value;
        url = document.getElementById('paymenturl').value;

        if (status == 1)
        {
            if (coupon_type == 1) {
                discount = document.getElementById('c_fixed_amount').value;
            } else
            {
                percent = document.getElementById('c_percent').value;
                discount = grand_total * percent / 100;
            }

            absolute_cost = grand_total - discount;
            document.getElementById('btn_apply_coupun').style.display = 'none';
            document.getElementById('btn_remove_coupon').style.display = 'block';
            purl = url + '/coupon';
            document.getElementById('coupon_used').value = '1';
        } else
        {
            discount = '00.00';
            absolute_cost = grand_total;
            document.getElementById('btn_remove_coupon').style.display = 'none';
            document.getElementById('btn_apply_coupun').style.display = 'block';
            purl = url;
            document.getElementById('coupon_used').value = '0';
        }

        try {
            try {
                document.getElementById('absolute_cost').innerHTML = Number(absolute_cost).toFixed(2);
            } catch (o) {
                //alert(o.message);
            }
            try {
                document.getElementById('absolute_costt').innerHTML = Number(absolute_cost).toFixed(2);
            } catch (o) {
                // alert(o.message);
            }
            try
            {
                document.getElementById('absolute_costtt').innerHTML = Number(absolute_cost).toFixed(2);
            } catch (o) {
                //alert(o.message);
            }
            try {
                document.getElementById('onlinepay').href = purl;
            } catch (o) {
                // alert(o.message);
            }
            try {
                document.getElementById('onlinepay2').href = purl;
            } catch (o) {
            }
            try {
                document.getElementById('onlinepay3').href = purl;
            } catch (o) {
            }
        } catch (o) {
            // alert(o.message);
        }
        document.getElementById('discount').innerHTML = Number(discount).toFixed(2);
        document.getElementById('discount_amt').value = Number(discount).toFixed(2);
        try {

            if (surcharge_amount > 0)
            {
                getsurchargegrandtotal(absolute_cost, fee_id);
            } else {
                document.getElementById('total').value = Number(absolute_cost).toFixed(2);
            }
        } catch (o) {
        }
    } catch (o) {
        // alert(o.message);
    }
}

function getsurchargegrandtotal(grand_total, fee_id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/patron/paymentrequest/getcoupongrandtotal/' + grand_total + '/' + fee_id,
        data: data,
        success: function (data)
        {
            try {
                document.getElementById('absolute_cost').innerHTML = Number(data).toFixed(2);
                document.getElementById('absolute_costt').innerHTML = Number(data).toFixed(2);
                document.getElementById('absolute_costtt').innerHTML = Number(data).toFixed(2);
                surcharge = data - grand_total;
                document.getElementById('surfee').innerHTML = Number(surcharge).toFixed(2);
            } catch (o) {
                // alert(o.message);
            }
            document.getElementById('total').value = Number(data).toFixed(2);
        }
    });
}

function getsurchargegrandtotal(grand_total, fee_id)
{
    var data = '';
    $.ajax({
        type: 'POST',
        url: '/patron/paymentrequest/getcoupongrandtotal/' + grand_total + '/' + fee_id,
        data: data,
        success: function (data)
        {
            try {
                document.getElementById('absolute_cost').innerHTML = Number(data).toFixed(2);
                document.getElementById('absolute_costt').innerHTML = Number(data).toFixed(2);
                document.getElementById('absolute_costtt').innerHTML = Number(data).toFixed(2);
                surcharge = data - grand_total;
                document.getElementById('surfee').innerHTML = Number(surcharge).toFixed(2);
            } catch (o) {
                // alert(o.message);
            }
            document.getElementById('total').value = Number(data).toFixed(2);
        }
    });
}



function AddNewSettlement()
{
    var group = $('input[name="id[]"]');
    var max_id = 0;
    if (group.length > 0) {
        var count = group.length;
        var int = 0;
        group.each(function () {
            int = parseInt(int) + 1;
            var colid = this.value;
            if (colid > max_id)
            {
                max_id = colid;
            }

        });
    }
    var id = parseInt(max_id) + 1;
    var selecttext = document.getElementById('beneficiary').innerHTML;
    var select = selecttext.replace('__ID__"', id + '" required');
    var mainDiv = document.getElementById('new_settlement');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td class="td-c">0<input type="hidden" value="0" name="settlement_id[]" ><input type="hidden" value="' + id + '" name="id[]" ></td><td class="td-c">0.00</td><td class="td-c">0.00</td><td class="td-c">0.00</td><td class="td-c">0.00</td><td class="td-c"></td><td class="td-c">' + select + '</td><td class="td-c"><input type="number" onblur="getTransferCharge();" value="0.00" id="amt' + id + '" class="form-control input-sm" step="0.01" name="request_amount[]" ></td><td class="td-c"><input type="number" value="0.00" id="transfercharges' + id + '" readonly  class="form-control input-sm" step="0.01" name="transfer_charges[]" ></td>';
    mainDiv.appendChild(newDiv);

}

function getTransferCharge()
{
    var transfercharge = document.getElementById('pgtransfercharge').value;
    var group = $('input[name="id[]"]');
    if (group.length > 0) {
        var count = group.length;
        var int = 0;
        group.each(function () {
            int = parseInt(int) + 1;
            var colid = this.value;
            var ben_id = document.getElementById('benificiary' + colid).value;
            amount = document.getElementById('amt' + colid).value;
            if (ben_id.substring(1) > 0 && amount > 0)
            {
                document.getElementById('transfercharges' + colid).value = transfercharge;
            } else
            {
                document.getElementById('transfercharges' + colid).value = '0.00';
            }

            if (int > count)
            {
                return false;
            }

        });
    }
}

function validateLedgerAmount()
{
    total_amount = 0;
    var ledgerbalance = parseInt(document.getElementById('ledgerbalance').value);
    var group = $('input[name="id[]"]');
    if (group.length > 1) {
        var count = group.length;
        var int = 0;
        group.each(function () {
            int = parseInt(int) + 1;
            var colid = this.value;
            var amt = document.getElementById('amt' + colid).value;
            var charge = document.getElementById('transfercharges' + colid).value;
            total_amount = total_amount + parseInt(amt) + parseInt(charge);
            if (int > count)
            {
                return false;
            }
        });
    }
    if (total_amount > ledgerbalance)
    {
        alert('Amount exceeds');
        return false;
    } else
    {
        return true;
    }
}

function planInvoiceEnable(type)
{
    if (type == true)
    {
        document.getElementById('plan_invoice_create').style.display = 'block';
    } else
    {
        document.getElementById('plan_invoice_create').style.display = 'none';
    }
}

function checkAll(checkk)
{
    $('input:checkbox').not(this).prop('checked', checkk);

}

function hideFilter(type)
{
    if (type == true)
    {
        document.getElementById('plan_invoice_create').style.display = 'initial';
    } else
    {
        document.getElementById('plan_invoice_create').style.display = 'none';
    }
}



function upload3b()
{
    var data = $("#3bfrm").serialize();
    var settings = {
        "async": true,
        "crossDomain": true,
        processData: false,
        contentType: false,
        "url": "https://stage.api.irisgst.com/capsule/gstr/upload?gstin=27BMKPP6491H1ZK&fp=112017&ft=GSTR3B",
        "method": "POST",
        "headers": {
            "X-Auth-Token": "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJpcmlzc3RhZ2VAbWFpbGluYXRvci5jb20iLCJhdWRpZW5jZSI6IndlYiIsImNyZWF0ZWQiOjE1NTEzNjk0Njc4MzIsImV4cCI6MTU1MTk3NDI2NywidGVuYW50Ijoic3RhZ2UifQ.r-Z5SUHYVuTXlmFMf3YGXPUzpiHDLnN_Fb5QM_oztxuX59QCyxOWRDctxc04rtNom5TPYaO-BeOb7zwa0W9_uw",
            "companyid": "4078",
            "Content-Type": "multipart/form-data"
        },
        "mimeType": "multipart/form-data",
        "data": data
    }

    $.ajax(settings).done(function (response) {
        alert(response)
    });

    return false;

}

function calculateLoyaltyPoints(amount, logic)
{
    points = 0;
    if (amount > 0)
    {
        points = amount / logic;
    }
    document.getElementById('purchase_point').value = Number(points).toFixed(2);
    ;
}


function updateExpiry()
{
    var data = $("#exp_frm").serialize();
    var exp_date = document.getElementById('exp_date').value;
    var stb_id = document.getElementById('stb_id').value;
    $.ajax({
        type: 'POST',
        url: '/ajax/updateexpiry',
        data: data,
        success: function (data)
        {
            if (data == 'success')
            {
                document.getElementById('exp' + stb_id).innerHTML = exp_date;
                document.getElementById('exp_success').style.display = 'block';
            } else
            {
                document.getElementById('exp_success').style.display = 'none';
            }
            document.getElementById('closebtn').click();
        }
    });
    return false;
}

function commentLink(link)
{
    $("#commentlink").attr("href", link);
    $("#commentlink").click();
}

function setFilePeriod(val)
{
    var currentdate = new Date(val);
    var month = currentdate.getMonth() + 1;
    var year = currentdate.getFullYear();
    m = ("0" + month).slice(-2);
    document.getElementById('fpmonth').value = m;
    document.getElementById('fpyear').value = year;
}


function AddInvoiceDocument() {
    var mainDiv = document.getElementById('new_doc');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td class="td-c"><select class="form-control" name="docNum[]"><option selected="" value="1">Invoices for outward supply</option><option value="5">Credit Note</option><option value="4">Debit Note</option></select></td><td class="td-c"><input type="text" class="form-control" value="" name="from[]"></td><td class="td-c"><input type="text" class="form-control" value="" name="to[]"></td><td class="td-c">    <input type="text" class="form-control" value="" name="total[]"></td><td class="td-c"><input type="text" class="form-control" value="" name="cancel[]"></td><td class="td-c"><input type="text" class="form-control" value="" name="netissued[]"></td><td class="td-c">    <a href="javascript:;" onclick="$(this).closest('+"'"+"tr"+"'"+').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}


function callRevisionSidePanel(id) {
    if(id !='') {
        document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)"; 
        document.getElementById("panelWrapId").style.transform = "translateX(0%)"; 
        $('.page-header-fixed').css('pointer-events','none');
        $("#panelWrapId").css('pointer-events','all');
        $('.delete_modal').css('pointer-events','all');
        $.ajax({
            type: "POST",
            url: '/merchant/paymentrequest/getRevisionDetails',
            data: {
                'payment_request_id':id
            },
            datatype: 'html',
            success: function(response) {
                $("#subscription_view_ajax").html(response);
                SubscriptionTableAdvanced.init();
            },
            error: function() {
            },
        }); 
    }
    return false;
}
function closeRevisionSidePanel() {
    document.getElementById("panelWrapId").style.boxShadow = "none"; 
    document.getElementById("panelWrapId").style.transform = "translateX(100%)";
    $('.page-header-fixed').css('pointer-events','all');
    return false;
}