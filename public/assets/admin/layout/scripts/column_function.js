var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];


function get_column_function(datatype, column_name)
{
    try {
        var d = JSON.parse(column_function_json);
        $('#column_function').empty();
        $('#column_function').append('<option value="">Select function</option>')
        $.each(d[datatype], function (index, value) {
            if (column_name != 'Bill date' || (value.id != '7' && value.id != '1'))
            {
                $('#column_function').append('<option value="' + value.id + '">' + value.value + '</option>');
            }
        });
    } catch (o)
    {
        // alert(o.message);
    }
}

function getMapping(function_id)
{
    try {
        var column_name = document.getElementById("custom_column_name").value;
        document.getElementById('mappinddiv').style.display = 'none';
        var d = JSON.parse(column_mapping);
        $('#mapping_param').empty();
        $('#mapping_param').append('<option value="">Select function</option>');
        $.each(d[function_id], function (index, value) {
            if (value != '')
            {
                if (column_name != 'Due date' || value != 'due_date')
                {
                    display_value = value;
                    if (value == 'system_generated')
                    {
                        display_value = 'System generated';
                    }
                    if (value == 'manually_entered')
                    {
                        display_value = 'Manually entered';
                    }
                    $('#mapping_param').append('<option value="' + value + '">' + display_value + '</option>');
                }
                if (function_id == 9) {
                    $("#mapping_param").attr('onchange', 'handleparam(' + function_id + ',this.value);');
                    document.getElementById('mappvalue').style.display = 'none';
                    document.getElementById('valname').innerHTML = 'Auto generate sequence';
                } else
                {
                    $("#mapping_param").attr('onchange', '');
                    document.getElementById('mappvalue').style.display = 'block';
                    var dropdwon = '<input type="text" name="mapping_value" id="mapping_value" class="form-control"><span class="help-block"></span><span class="help-block"></span>';
                    document.getElementById('mappval').innerHTML = dropdwon;
                    document.getElementById('valname').innerHTML = 'Days';
                }
                document.getElementById('mappinddiv').style.display = 'block';
            }
        });
    } catch (o)
    {
        //alert(o.message);
    }
}

function handleparam(function_id, val)
{
    if (val == 'system_generated') {
        var dropdwon = '<select class="form-control input-sm" name="mapping_value" id="mapping_value"  data-placeholder="Select"></select><span class="help-block"></span><span class="help-block"></span>';
        document.getElementById('mappval').innerHTML = dropdwon;
        var d = JSON.parse(invoice_numbers);
        $('#mapping_value').empty();
        $('#mapping_value').append('<option value="">Select sequence</option>');
        $.each(d, function (index, value) {
            $('#mapping_value').append('<option value="' + value.auto_invoice_id + '">' + value.prefix + value.val + '</option>');
        });
        document.getElementById('mappvalue').style.display = 'block';
        document.getElementById('new_seq_number_btn').style.display = 'block';
    } else
    {
        var dropdwon = '<select class="form-control input-sm" name="mapping_value" id="mapping_value"  data-placeholder="Select"></select><span class="help-block"></span><span class="help-block"></span>';
        document.getElementById('mappval').innerHTML = dropdwon;
        var d = JSON.parse(invoice_numbers);
        $('#mapping_value').empty();
        $('#mapping_value').append('<option value="">Select sequence</option>')
        document.getElementById('mappvalue').style.display = 'none';
        document.getElementById('new_seq_number_btn').style.display = 'none';
    }
}
function autoGenerateInvoiceNumber()
{
    var mainDiv = document.getElementById('new_invoice');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="text" name="subscript[]"  maxlength="20" class="form-control input-sm" placeholder="Add prefix"></div></td><td><div class="input-icon right"><input type="number" step="1" maxlength="6" required value="0" name="lastnumber[]" class="form-control input-sm" placeholder="Last Number"></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-remove"> </i> </a></td>';
    mainDiv.appendChild(newDiv);

}

function datatypeChange(datatype)
{
    document.getElementById("datatype").disabled = false;
    document.getElementById("custom_column_name").disabled = false;
    $('#custom_column_name').val('');
    $('#column_function').empty();
    $('#column_function').append('<option value="">Select function</option>');
    $("#column_function").change();
    get_column_function(datatype);
}

function reset()
{
    try {
        document.getElementById("datatype").disabled = false;
        document.getElementById("custom_column_name").disabled = false;
        $("#datatype").val('text');
        $('#custom_column_id').val('new');
        $('#custom_column_name').val('');
        var datatype = document.getElementById('datatype').value;
        $('#column_function').empty();
        $('#column_function').append('<option value="">Select function</option>');
        $("#column_function").change();
        get_column_function(datatype);
    } catch (o)
    {
        // alert(o.message);
    }
}

function bds_reset(position)
{
    try {
        document.getElementById("bds_datatype").disabled = false;
        document.getElementById("bds_custom_column_name").disabled = false;
        $("#bds_datatype").val('text');
        $('#bds_custom_column_id').val('new');
        $('#bds_custom_column_name').val('');
        $('#bds_position').val(position);
    } catch (o)
    {
        // alert(o.message);
    }
}