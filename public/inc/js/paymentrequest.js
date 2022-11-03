function respond_changes(idd)
{

    if (idd == 4)
    {

        document.getElementById('bank_name').style.display = 'none';
        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('date').style.display = 'none';
        document.getElementById('amount').style.display = 'none';


    }
    else
    {

        document.getElementById('bank_transaction_no').style.display = 'none';
        document.getElementById('cheque_no').style.display = 'none';
        document.getElementById('cash_paid_to').style.display = 'none';
        document.getElementById('bank_name').style.display = 'block';
        document.getElementById('date').style.display = 'block';
        document.getElementById('amount').style.display = 'block';

        if (idd == 2)
        {
            document.getElementById('cheque_no').style.display = 'block';
            document.getElementById('bank_name').value = 'My Bank Name';
        }
        else if (idd == 1)
        {
            document.getElementById('bank_transaction_no').style.display = 'block';
            document.getElementById('bank_name').value = 'My Bank Name';
        }
        else if (idd == 3)
        {
            document.getElementById('cash_paid_to').style.display = 'block';
            document.getElementById('bank_name').style.display = 'none';
        }
    }

}

function respond(abc)
{
    document.getElementById('pay_req').value = abc;

}


function updateRespond(respondId)
{
    document.getElementById('paymentresponse_id').value = respondId;
    document.getElementById("myForm").submit();
}