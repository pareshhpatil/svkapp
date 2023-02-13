var numherder = 1;
var removecount = 0;
var removetaxcount = 0;
var numbers = /^-?[0-9]\d*(\.\d+)?$/;
var manual_tax = 0;
var icon = 'fa-font';
var monthNames = ["abc", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
//particular and tax labels , 
var label = 'required aria-required="true" maxlength="40" title="Does not accepts ` and ~ characters"  pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" ';
// , narrative  
var label1 = 'maxlength="40" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';
//percentage tax
var ptax = 'maxlength="5" pattern="[0-9]+([\\.][0-9]+)?" title="Accepts only numeric characters.Value less than 100"  step="0.01"';
//amount , absolute cost
var aamt = 'title="Accepts only numeric characters. Value not exceeding (&#x20B9;) 1,00,000.00" pattern="^-?[0-9]\\d*(\\.\\d+)?$" maxlength="9"';
//quantity , no of units
var units = 'title="Accepts only numeric characters" pattern="((?=.*[0-9])\\\d{1,5}(?:\\\.\\\d{1,2})?|100000.00|100000)" maxlength="9"';
//header
var header = 'required aria-required="true" maxlength="20" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';
//duration
var dur = 'required aria-required="true" maxlength="15" title="Does not accepts ` and ~ characters" pattern="[a-zA-Z0-9]+(([\\\!\\\'\\\,\\\.\\\s\\\-\\\@\\\#\\\$\\\%\\\^\\\&\\\*\\\(\\\)\\\_\\\+\\\|\\\\\\\/\\\=\\\{\\\}\\\[\\\]\\\][a-zA-Z0-9])?[a-zA-Z0-9]*)*"';
var products = null;
var products_rate = null;
var tax_master = null;
var taxes_rate = null;
var products_taxes = null;
var particular_col_array = null;
var GST_dropdown = '<select name="gst[]" onchange="setTaxApplicableAmt(row_id,this.value);calculateamt();calculatetax();" style="min-width:80px;" class="form-control"><option value="">Select</option><option value="0">0%</option><option value="5">5%</option><option value="12">12%</option><option value="18">18%</option><option value="28">28%</option></select>';
var particular_values = '';
var t = '';
var e = '';
var a = '';
var n = '';
var u = '';
var uitype = 'laravel';
function _(el) {
    return document.getElementById(el);
}

function removedivexist(id) {
    var ab = 'exist' + id;
    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
}





function addHeader() {

    custom_column_id = document.getElementById("custom_column_id").value;
    if (custom_column_id == 'new') {
        newHwader();
    } else {
        column_datatype = document.getElementById("datatype").value;
        custom_column_name = document.getElementById("custom_column_name").value;
        function_param = document.getElementById("mapping_param").value;
        function_val = document.getElementById("mapping_value").value;
        readonly = document.getElementById("readonly").value;
        function_id = document.getElementById("column_function").value;
        if (function_id > 0) {
            cont = $('#mapping_param').children('option').length;
            if (function_id == 9 && function_param == 'system_generated') {
                cont2 = $('#mapping_value').children('option').length;
                if (cont2 == 1) {
                    alert('Please add invoice number sequence.');
                    return false;
                }
            }

            if (cont > 1 && function_param == '') {
                alert('Please select all fields');
                return false;
            } else {
                cont2 = $('#mapping_value').children('option').length;
                if (cont2 > 1 && function_val == '') {
                    alert('Please select all fields');
                    return false;
                }
            }

        } else {
            function_id = -1;
        }

        if (column_datatype == 'textarea') {
            disable_textbox = '<textarea class="form-control input-sm" readonly="" ></textarea>';
        } else {
            disable_textbox = '<input type="text" class="form-control input-sm" readonly="" >';
        }
        if (column_datatype == 'primary' && document.getElementById('datatype' + custom_column_id).value != 'primary') {
            if ($("i").hasClass("fa fa-anchor")) {
                $('#closebutton').click();
                alert('You can select only one primary fields');
                exit;
            }
        }
        try {
            document.getElementById("datatype" + custom_column_id + "").value = column_datatype;
            document.getElementById("function_id" + custom_column_id + "").value = function_id;
            document.getElementById("columnname" + custom_column_id + "").value = custom_column_name;
            document.getElementById("function_param" + custom_column_id + "").value = function_param;
            document.getElementById("function_val" + custom_column_id + "").value = function_val;
        } catch (o) {
        }
        icon = getIcon(column_datatype);
        document.getElementById("icon" + custom_column_id + "").className = 'fa ' + icon;
        try {
            document.getElementById("datatypediv" + custom_column_id + "").innerHTML = disable_textbox;
        } catch (o) {
        }
        $("#edit" + custom_column_id + "").attr("onclick", "editclick(" + custom_column_id + ",'" + column_datatype + "'," + function_id + ",'" + readonly + "');");
        $('#closebutton').click();
    }
}

function addBDS() {

    custom_column_id = document.getElementById("bds_custom_column_id").value;
    if (custom_column_id == 'new') {
        newBDS();
    } else {
        column_datatype = document.getElementById("bds_datatype").value;
        custom_column_name = document.getElementById("bds_custom_column_name").value;
        readonly = document.getElementById("bds_readonly").value;
        position = document.getElementById("bds_position").value;
        function_id = -1;
        if (column_datatype == 'textarea') {
            disable_textbox = '<textarea class="form-control input-sm" readonly="" ></textarea>';
        } else {
            disable_textbox = '<input type="text" class="form-control input-sm" readonly="" >';
        }

        try {
            document.getElementById("bds_datatype" + custom_column_id + "").value = column_datatype;
            document.getElementById("bds_columnname" + custom_column_id + "").value = custom_column_name;
        } catch (o) {
        }
        icon = getIcon(column_datatype);
        document.getElementById("bds_icon" + custom_column_id + "").className = 'fa ' + icon;
        try {
            document.getElementById("bds_datatypediv" + custom_column_id + "").innerHTML = disable_textbox;
        } catch (o) {
        }
        $("#bds_edit" + custom_column_id + "").attr("onclick", "bds_editclick(" + custom_column_id + ",'" + column_datatype + "'," + function_id + ",'" + readonly + "');");
        $('#bds_closebutton').click();
    }
}

function newBDS() {
    numherder++;
    while (document.getElementById('exist_bds' + numherder)) {
        numherder = numherder + 1;
    }
    column_datatype = document.getElementById("bds_datatype").value;
    position = document.getElementById("bds_position").value;
    function_id = -1;
    mapping_param = '';
    mapping_value = '';
    custom_column_name = document.getElementById("bds_custom_column_name").value;
    if (position == 'R') {
        divname = 'bdsright';
    } else {
        divname = 'bdsleft';
    }

    var mainDiv = document.getElementById(divname);
    var newSpan = document.createElement('li');
    newSpan.setAttribute('id', 'exist_bds' + numherder);
    newSpan.setAttribute('class', 'form-group ui-state-default');
    if (column_datatype == 'textarea') {
        disable_textbox = '<textarea class="form-control input-sm" readonly="" ></textarea>';
    } else {
        disable_textbox = '<input type="text" class="form-control input-sm" readonly="" >';
    }


    icon = getIcon(column_datatype);
    onclick_event = "bds_editclick(" + numherder + ",'" + column_datatype + "'," + function_id + ");";
    newSpan.innerHTML = '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div class="form-group" style="display: initial;" id="exist_bds' + numherder + '"><input name="position[]" type="hidden" value="' + position + '"><input name="headertablesave[]" type="hidden" value="metadata"><input name="column_type[]" type="hidden" value="BDS"><input name="headermandatory[]" type="hidden" value="2"><input name="headercolumnposition[]" type="hidden" value="-1"><input name="function_id[]"  type="hidden" value="' + function_id + '" /><input name="headerid[]" type="hidden" value="-1"><input name="function_param[]" type="hidden"  value="' + mapping_param + '" /><input name="function_val[]" type="hidden"  value="' + mapping_value + '" /><input name="headerisdelete[]" type="hidden" value="1"><input name="headerdatatype[]" id="bds_datatype' + numherder + '" type="hidden" value="' + column_datatype + '"><div class="col-md-6"><div class="input-group"><span class="input-group-btn"><div class="btn default btn-sm"> <i id="bds_icon76" class="fa fa-arrows-v"></i></div></span><input type="text" name="headercolumn[]" onkeypress="return false;" value="' + custom_column_name + '" id="bds_columnname' + numherder + '" class="form-control input-sm" maxlength="40" placeholder="Enter label name"><span class="input-group-btn"><div class="btn default btn-sm"><i id="bds_icon' + numherder + '" class="fa ' + icon + '"></i></div><a class="btn default btn-sm" data-toggle="modal" id="bds_edit' + numherder + '" onclick="' + onclick_event + '" href="#bds"><i class="fa fa-edit"></i></a></span></div></div><div class="col-md-6"><div class="input-group"><div id="bds_datatypediv' + numherder + '">' + disable_textbox + '</div><span class="input-group-addon " id="bds_' + numherder + '" onclick="removedivexist(this.id)"><i class="fa fa-minus-circle"></i></span></div><span class="help-block"></span></div></div>';
    mainDiv.appendChild(newSpan);
    $('#bds_closebutton').click();
}


function addnewHeaderclick() {
    $('#custom_column_id').val('new');
    $('#custom_column_name').val('');
    $('#readonly').val('');
    document.getElementById("custom_column_name").disabled = false;
    document.getElementById("datatype").disabled = false;
}
function editclick(id, value, function_id, readonly) {
    $('#mapping_param').empty();
    $('#mapping_value').empty();
    if (readonly == 'readonly') {
        document.getElementById("custom_column_name").disabled = true;
        document.getElementById("datatype").disabled = true;
    } else {
        document.getElementById("custom_column_name").disabled = false;
        document.getElementById("datatype").disabled = false;
    }
    var column_name = document.getElementById("columnname" + id + "").value
    document.getElementById("readonly").value = readonly;
    get_column_function(value, column_name);
    if (function_id == -1) {
        function_id = "";
    }
    $('#column_function').val("" + function_id + "").attr('selected', 'selected');
    $('#custom_column_id').val("" + id + "");
    $('#datatype').val("" + value + "").attr('selected', 'selected');
    getMapping(function_id);
    try {

        mapping_value = document.getElementById("function_val" + id).value;
        mapping_param = document.getElementById("function_param" + id).value;
        if (function_id == 9) {
            handleparam(function_id, mapping_param);
            $('#mapping_value').val("" + mapping_value + "").attr('selected', 'selected');
        } else {
            $('#mapping_value').val("" + mapping_value + "");
        }

        $('#mapping_param').val("" + mapping_param + "").attr('selected', 'selected');
    } catch (o) {
        //alert(o.message);
    }


    document.getElementById("custom_column_name").value = column_name;
}

function bds_editclick(id, value, function_id, readonly) {
    if (readonly == 'readonly') {
        document.getElementById("bds_custom_column_name").disabled = true;
        document.getElementById("bds_datatype").disabled = true;
    } else {
        document.getElementById("bds_custom_column_name").disabled = false;
        document.getElementById("bds_datatype").disabled = false;
    }
    var column_name = document.getElementById("bds_columnname" + id + "").value
    document.getElementById("bds_readonly").value = readonly;
    get_column_function(value, column_name);
    if (function_id == -1) {
        function_id = "";
    }
    $('#bds_custom_column_id').val("" + id + "");
    $('#bds_datatype').val("" + value + "").attr('selected', 'selected');
    document.getElementById("bds_custom_column_name").value = column_name;
}
function newHwader() {
    numherder++;
    while (document.getElementById('exist' + numherder)) {
        numherder = numherder + 1;
    }
    column_datatype = document.getElementById("datatype").value;
    function_id = document.getElementById("column_function").value;
    mapping_param = '';
    mapping_value = '';
    try {
        mapping_param = document.getElementById("mapping_param").value;
        mapping_value = document.getElementById("mapping_value").value;
    } catch (o) {
    }
    custom_column_name = document.getElementById("custom_column_name").value;
    if (function_id > 0) {

        cont = $('#mapping_param').children('option').length;
        if (function_id == 9 && mapping_param == 'system_generated') {
            cont2 = $('#mapping_value').children('option').length;
            if (cont2 == 1) {
                alert('Please add invoice number sequence.');
                return false;
            }
        }

        cont = $('#mapping_param').children('option').length;
        if (cont > 1 && mapping_param == '') {
            alert('Please select all fields');
            return false;
        } else {
            cont2 = $('#mapping_value').children('option').length;
            if (cont > 1 && cont2 > 1 && mapping_value == '') {
                alert('Please select all fields');
                return false;
            }
        }

    } else {
        function_id = -1;
    }






    var mainDiv = document.getElementById('newHeaderright');
    var newSpan = document.createElement('li');
    newSpan.setAttribute('id', 'exist' + numherder);
    newSpan.setAttribute('class', 'form-group ui-state-default');
    /*if (rightcount < leftcount) {
     counttext = 'rightcount';
     side_name = 'R';
     }
     else
     {
     counttext = 'leftcount';
     side_name = 'L';
     }*/
    counttext = 'rightcount';
    side_name = 'R';
    if (column_datatype == 'textarea') {
        disable_textbox = '<textarea class="form-control input-sm" readonly="" ></textarea>';
    } else {
        disable_textbox = '<input type="text" class="form-control input-sm" readonly="" >';
    }


    if (column_datatype == 'primary') {
        if ($("i").hasClass("fa fa-anchor")) {
            $('#closebutton').click();
            alert('You can select only one primary fields');
            exit;
        }
    }

    icon = getIcon(column_datatype);
    onclick_event = "editclick(" + numherder + ",'" + column_datatype + "'," + function_id + ");";
    newSpan.innerHTML = '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div class="form-group" style="display: initial;" id="exist' + numherder + '"><input name="' + counttext + '" type="hidden"><input name="position[]" type="hidden" value="' + side_name + '"><input name="headertablesave[]" type="hidden" value="metadata"><input name="column_type[]" type="hidden" value="H"><input name="headermandatory[]" type="hidden" value="2"><input name="headercolumnposition[]" type="hidden" value="-1"><input name="function_id[]" id="function_id' + numherder + '" type="hidden" value="' + function_id + '" /><input name="headerid[]" type="hidden" value="-1"><input name="function_param[]" type="hidden" id="function_param' + numherder + '" value="' + mapping_param + '" /><input name="function_val[]" type="hidden" id="function_val' + numherder + '" value="' + mapping_value + '" /><input name="headerisdelete[]" type="hidden" value="1"><input name="headerdatatype[]" id="datatype' + numherder + '" type="hidden" value="' + column_datatype + '"><div class="col-md-6"><div class="input-group"><span class="input-group-btn"><div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div></span><input type="text" name="headercolumn[]" onkeypress="return false;" value="' + custom_column_name + '" id="columnname' + numherder + '" class="form-control input-sm" maxlength="40" placeholder="Enter label name"><span class="input-group-btn"><div class="btn default btn-sm"><i id="icon' + numherder + '" class="fa ' + icon + '"></i></div><a class="btn default btn-sm" data-toggle="modal" id="edit' + numherder + '" onclick="' + onclick_event + '" href="#custom"><i class="fa fa-edit"></i></a></span></div></div><div class="col-md-6"><div class="input-group"><div id="datatypediv' + numherder + '">' + disable_textbox + '</div><span class="input-group-addon " id="' + numherder + '" onclick="removedivexist(this.id)"><i class="fa fa-minus-circle"></i></span></div><span class="help-block"></span></div></div>';
    mainDiv.appendChild(newSpan);
    $('#closebutton').click();
}


function getIcon(column_datatype) {
    switch (column_datatype) {
        case 'money':
            icon = 'fa-inr';
            break;
        case 'text':
            icon = 'fa-font';
            break;
        case 'number':
            icon = 'fa-sort-numeric-asc';
            break;
        case 'primary':
            icon = 'fa-anchor';
            break;
        case 'percent':
            icon = 'fa-rub';
            break;
        case 'textarea':
            icon = 'fa-file-text-o';
            break;
        case 'date':
            icon = 'fa-calendar';
            break;
        case 'time':
            icon = 'fa-clock-o';
            break;
        default:
            icon = 'fa-font';
            break;
    }
    return icon;
}

function AddDebit() {
    var mainDiv = document.getElementById('new_debit');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="text" name="debit[]" class="form-control input-sm" placeholder="Add label"></div></td><td><div class="input-icon right"><input type="number" step="0.01" max="100" name="debitdefaultValue[]" class="form-control input-sm" placeholder="Add %"></div></td><td><input type="text" readonly class="form-control input-sm" ></td><td><input type="text" class="form-control input-sm" readonly></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();tableHead(' + "'new_debit'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);
}
function AddCC() {
    var mainDiv = document.getElementById('new_cc');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="email" name="cc[]" class="form-control input-sm" placeholder="Add email"></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();tableHead(' + "'new_cc'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}

function AddReminder() {
    var mainDiv = document.getElementById('new_reminder');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><input type="number" name="reminder[]" class="form-control input-sm" placeholder="Add day"></div></td><td><div class="input-icon right"><input type="text" name="reminder_subject[]"  maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject"></div></td><td><div class="input-icon right"><input type="text" name="reminder_sms[]"  maxlength="200" class="form-control input-sm" placeholder="Reminder SMS"></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();tableHead(' + "'new_reminder'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
}

function showDebit(id) {
    if ($('#is' + id).is(':checked')) {
        $("#" + id + "div").slideDown(500).fadeIn();
    } else {
        $("#" + id + "div").slideUp(500).fadeOut();
    }
}
function AddTnC() {
    var mainDiv = document.getElementById('new_tnc');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><div class="input-icon right"><textarea type="text" name="tnc[]" maxlength="250" class="form-control input-sm tncrich" placeholder="Add terms & conditions"></textarea></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>';
    mainDiv.appendChild(newDiv);
    $('.tncrich').summernote({
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
                if (t.trim().length >= 240) {
                    //delete keys, arrow keys, copy, cut
                    if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                        e.preventDefault();
                }
            },
            onKeyup: function (e) {
                var t = e.currentTarget.innerText;
                $('#maxContentPost').text(240 - t.trim().length);
            },
            onPaste: function (e) {
                var t = e.currentTarget.innerText;
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                var maxPaste = bufferText.length;
                if (t.length + bufferText.length > 240) {
                    maxPaste = 240 - t.length;
                }
                if (maxPaste > 0) {
                    document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                }
                $('#maxContentPost').text(240 - t.length);
            }
        }
    }
    );
}

/* Invoice particular */
function AddTravelTicketBooking(ch) {
    //var x = document.getElementById("particular_table").rows.length;
    try {
        var node_listright = document.getElementsByName("countrow");
        var Numrow = Number(node_listright.length) + 1;
    } catch (o) {
        Numrow = 1;
    }

    while (document.getElementById('bamt' + Numrow)) {
        Numrow = Numrow + 1;
    }
    while (document.getElementById('camt' + Numrow)) {
        Numrow = Numrow + 1;
    }
    var mainDiv = document.getElementById(ch + 'new_particular');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td> <div class="input-icon right autocomplete"><input type="hidden" onblur="product_rate(this.value,' + Numrow + ');" id="particular' + Numrow + '"  name="texistid[]" value="0"><input type="hidden" name="btype[]" value="' + ch + '" ><input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px" autocomplete="off" value=""name="booking_date[]" class="form-control date-picker input-sm" data-date-format="dd M yyyy"> </div></td><td> <div class="input-icon right"><input type="text" required="" style="padding-right: 0px; padding-left: 10px;width: 120px"  autocomplete="off" value=""name="journey_date[]" class="form-control date-picker input-sm" data-date-format="dd M yyyy"> </div></td><td> <div class="input-icon right"><input type="text" name="b_name[]"  maxlength="500" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input type="text" name="b_type[]"  maxlength="45" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input type="text"  maxlength="45" name="b_from[]" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input type="text"  maxlength="45" name="b_to[]" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input required="" type="text"  ' + aamt + '   name="b_amt[]" id="' + ch + 'amt' + Numrow + '" onblur="calculatetravelbooking(' + Numrow + ',' + "'" + ch + "'" + ')" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input required="" type="text"  ' + aamt + '   name="b_charge[]" id="' + ch + 'charge' + Numrow + '" onblur="calculatetravelbooking(' + Numrow + ',' + "'" + ch + "'" + ')" class="form-control input-sm" > </div></td><td> <div class="input-icon right"><input type="text" name="b_total[]" id="' + ch + 'total' + Numrow + '" readonly class="form-control input-sm" > </div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove(),calculatetravelbooking(' + Numrow + ', ' + "'" + ch + "'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
    setdatepicker();
    autocomplete(Numrow);
    //$('.date-picker').datepicker();

}

function setdatepicker() {
    $('.date-picker').datepicker({
        rtl: Swipez.isRTL(),
        orientation: "left",
        autoclose: true,
        todayHighlight: true
    });
}


function getTaxtext(name, Numrow, defaultval) {
    if (tax_master == null) {
        var tax_text = '<input type="text "  id="tax' + Numrow + '"   required name="' + name + '[]" class="form-control input-sm" placeholder="Add label">';
    } else {
        var tax_text = '<select style="max-width:200px; min-width:175px;   padding-right: 0px; padding-left: 10px;" onchange="tax_rate(this.value,' + Numrow + ');" id="tax' + Numrow + '"  name="' + name + '[]" data-placeholder="Select..." class="form-control input-sm productselect" ><option value="">Select Tax</option>';
        var d = tax_master;
        $.each(d, function (index, value) {
            try {
                if (defaultval == value) {
                    tax_text = tax_text + '<option selected value="' + value + '">' + value + '</option>';
                } else {
                    tax_text = tax_text + '<option value="' + value + '">' + value + '</option>';
                }
            } catch (o) {
            }
        });
        tax_text = tax_text + '</select>';
    }
    return tax_text;
}




function calculatetravelbooking(p, cha) {
    try {
        d = document.getElementById(cha + "amt" + p).value;
        if (d == '') {
            d = 0;
        } else {

        }
        var m = document.getElementById(cha + "charge" + p).value;
        if (m == '') {
            m = 0;
        } else {

        }
        if (cha == 'c') {
            var r = Number(d) - Number(m);
        } else {
            var r = Number(m) + Number(d);
        }

        document.getElementById(cha + "total" + p).value = Math.round(100 * r) / 100;
    } catch (o) {
    }
    particularcount = 1;
    exist = 1;
    while (exist == 1) {
        if (document.getElementById('btotal' + particularcount)) {
            exist = 1;
        } else {
            exist = 0;
        }

        if (exist == 0) {
            if (document.getElementById('ctotal' + particularcount)) {
                exist = 1;
            } else {
                exist = 0;
            }

        }

        particularcount = particularcount + 1;
    }


    for (var c = 0, cc = 0, ch = 0, g = 0, v = 1; particularcount > v; v++)
        try {
            c += Number(document.getElementById(cha + "total" + v).value);
            cc += Number(document.getElementById(cha + "amt" + v).value);
            ch += Number(document.getElementById(cha + "charge" + v).value);
        } catch (o) {
            //alert(o.message);
        }

    total_par = Math.round(100 * c) / 100;
    document.getElementById(cha + "totalcost").value = Math.round(100 * cc) / 100;
    document.getElementById(cha + "totalcharge").value = Math.round(100 * ch) / 100;
    document.getElementById(cha + "totalcostamt").value = total_par;
    //setapplicable(total_par);
    //document.getElementById("totalunit").innerHTML = Math.round(100 * g) / 100, document.getElementById("totalcost").value = Math.round(100 * c) / 100, document.getElementById("totalcostamt").value = Math.round(100 * c) / 100, calculategrandtotal(a, n, u, o, l)
    calculatetravelgrandtotal();
}

function getidamt(id) {
    try {
        amt = document.getElementById(id).value;
        if (amt > 0) {
            return amt;
        } else {

        }
    } catch (o) {
    }
    return 0;
}


function calculatedebit(p) {
    try {
        var d = document.getElementById("debitin" + p).value, r = document.getElementById("applicabledebitamount" + p).value;
        if ("" == r && (r = 0), d > 0) {
            totalcost = d * r / 100, document.getElementById("totaldebit" + p).value = totalcost > 0 ? Math.round(100 * totalcost) / 100 : 0;
        } else {
            document.getElementById("totaldebit" + p).value = '0';
        }
    } catch (o) {
    }
}

function calculateevent(p) {
    try {

        d = document.getElementById("unitnumber" + p).value;
        if (d == '') {
            document.getElementById("unitnumber" + p).value = '1';
            d = 1;
        } else {

        }
        var m = document.getElementById("unitprice" + p).value, d = document.getElementById("unitnumber" + p).value;
        if ("" == d && (document.getElementById("unitnumber" + p).value = "1", d = 1), m > 0) {
            var r = m * d;
            document.getElementById("cost" + p).value = r > 0 ? Math.round(100 * r) / 100 : 0;
        } else {
            document.getElementById("cost" + p).value = '0';
        }
    } catch (o) {
    }
    particularcount = 1;
    while (document.getElementById('cost' + particularcount)) {
        particularcount = particularcount + 1;
    }
    for (var c = 0, g = 0, v = 1; particularcount > v; v++)
        try {
            c += Number(document.getElementById("cost" + v).value);
        } catch (o) {
        }

    try {
        var e = document.getElementById("coupon_id");
        var coupon_id = e.value;
    } catch (o) {
        coupon_id = '';
    }
    total = Math.round(100 * c) / 100;
    document.getElementById("totalcostamt").value = total.toFixed(2);
    //document.getElementById("totalunit").innerHTML = Math.round(100 * g) / 100, document.getElementById("totalcost").value = Math.round(100 * c) / 100, document.getElementById("totalcostamt").value = Math.round(100 * c) / 100, calculategrandtotal(a, n, u, o, l)
    var cost_coupon = 0;
    if (coupon_id != '') {
        var package_id = document.getElementById("c_package_id" + coupon_id).value;
        var c_type = document.getElementById("c_type" + coupon_id).value;
        var c_percent = document.getElementById("c_percent" + coupon_id).value;
        var c_fixed_amount = document.getElementById("c_fixed_amount" + coupon_id).value;
        if (package_id == 0) {
            if (c_type == 1) {
                cost_coupon = Number(c_fixed_amount);
            } else {
                cost_coupon = Number(total * c_percent / 100);
            }
            total = Number(total - cost_coupon);
        } else {
            var cost_int = document.getElementById("package" + package_id).value;
            var unitPrice = document.getElementById("unitprice" + cost_int).value;
            var unitNum = document.getElementById("unitnumber" + cost_int).value;
            if (c_type == 1) {
                cost_coupon = Number(c_fixed_amount);
            } else {
                cost_coupon = Number(unitPrice * c_percent / 100);
            }
            cost_coupon = Number(cost_coupon * unitNum);
            total = Number(total - cost_coupon);
        }

    }

    try {
        var tax = document.getElementById("tax").value;
    } catch (o) {
        var tax = 0;
    }
    if (tax > 0) {
        tax_amount = Number(total * tax / 100);
    } else {
        tax_amount = 0;
    }
    try {
        document.getElementById("service_tax").value = tax_amount.toFixed(2);
    } catch (o) {

    }
    try {
        var total = Math.ceil(total + tax_amount);
        document.getElementById("grand_total").value = total.toFixed(2);
        try {
            document.getElementById("total_display").innerHTML = total.toFixed(2);
        } catch (o) {

        }
    } catch (o) {

    }
    try {
        document.getElementById("coupon_discount").value = cost_coupon.toFixed(2);
    } catch (o) {

    }



}

function calculategrandtotal() {

    try {
        type = document.getElementById("template_type").value;
    } catch (o) {
        type = 'particular';
    }

    try {
        product_taxation_type = document.getElementById("product_taxation_type").value;
    } catch (o) {
        product_taxation_type = 0;
    }

    if (type == 'simple') {
        calculatesimplegrandtotal();
    } else {

        try {
            advance = document.getElementById("advance_amt").value;
        } catch (o) {
            advance = 0;
        }
        if (advance > 0) {

        } else {
            advance = 0;
        }
        try {
            previous_dues = document.getElementById("previous_due").value;
        } catch (o) {
            previous_dues = 0;
        }
        try {
            last_payment = document.getElementById("last_payment").value;
        } catch (o) {
            last_payment = 0;
        }
        try {
            adjustment = document.getElementById("adjustment").value;
        } catch (o) {
            adjustment = 0;
        }
        try {
            discount = document.getElementById("discount").value;
        } catch (o) {
            discount = 0;
        }

        adjustment = Number(adjustment) + Number(discount);

        try {
            sec_total = document.getElementById("sec_total").value;
        } catch (o) {
            sec_total = 0;
        }

        try {
            particular_total = document.getElementById("particulartotal").value;
        } catch (o) {
            particular_total = 0;
        }

        //console.log(particular_total+'/'+sec_total);

        _('totalcostamt').value = Number(particular_total) + Number(sec_total);


        try {
            document.getElementById("previous_duesclone").value = previous_dues;
        } catch (o) {
        }

        previous_dues = previous_dues - Number(last_payment) - Number(adjustment);
        try {
            amount = document.getElementById("totalcostamt").value;
        } catch (o) {
            amount = 0
        }
        try {
            tax_amount = document.getElementById("totaltaxcost").value;
        } catch (o) {
            tax_amount = 0
        }

        if (previous_dues >= 0) {
            grandtotal = Number(amount) + Number(tax_amount) + Number(previous_dues);
        } else {
            grandtotal = Number(amount) + Number(tax_amount);
            try {
                if (previous_dues < 0) {
                    grandtotal = grandtotal + Number(previous_dues);
                }
            } catch (o) {
            }
        }

        var l = 0;
        var m = 0;
        if (t == "P") {
            var m = (Number(e) * Number(grandtotal)) / 100;
        } else {
            var m = Number(e);
        }     // m = "P" == t ? e * grandtotal / 100 : e;

        //console.log(grandtotal+'/'+sec_total);

        // grandtotal = Number(grandtotal) + Number(sec_total);

        var d = 0;
        "P" == a ? (
            l = Number(grandtotal) + Number(m),
            d = n * l / 100) :
            d = n,
            stapply = u * d / 100,
            document.getElementById("totalamount").value = Math.round(100 * grandtotal) / 100,
            grandtotal = Number(grandtotal) + Number(m) + Number(d) + Number(stapply),
            grandtotal = grandtotal - advance,
            document.getElementById("grandtotal").value = Math.round(100 * grandtotal) / 100

        if (product_taxation_type == '2') {
            totaltaxcost = document.getElementById("totaltaxcost").value
            totalamount = document.getElementById("totalamount").value
            grandtotal = document.getElementById("grandtotal").value
            document.getElementById("grandtotal").value = (grandtotal - totaltaxcost).toFixed(2)
            document.getElementById("totalamount").value = (totalamount - totaltaxcost).toFixed(2)
        }
    }
}

function calculatetravelgrandtotal() {
    try {
        advance = document.getElementById("advance_amt").value;
    } catch (o) {
        advance = 0;
    }
    if (advance > 0) {

    } else {
        advance = 0;
    }
    try {
        previous_dues = document.getElementById("previous_due").value;
    } catch (o) {
        previous_dues = 0;
    }
    try {
        last_payment = document.getElementById("last_payment").value;
    } catch (o) {
        last_payment = 0;
    }
    try {
        adjustment = document.getElementById("adjustment").value;
    } catch (o) {
        adjustment = 0;
    }

    try {
        discount = document.getElementById("discount").value;
    } catch (o) {
        discount = 0;
    }

    adjustment = Number(adjustment) + Number(discount);


    try {
        document.getElementById("previous_duesclone").value = previous_dues;
    } catch (o) {
    }

    previous_dues = previous_dues - Number(last_payment) - Number(adjustment);
    try {
        bamount = document.getElementById("btotalcostamt").value;
    } catch (o) {
        bamount = 0
    }
    try {
        camount = document.getElementById("ctotalcostamt").value;
    } catch (o) {
        camount = 0
    }
    amount = Number(bamount) - Number(camount);
    //document.getElementById("totalcostamt").value = amount;
    try {
        tax_amount = document.getElementById("totaltaxcost").value;
    } catch (o) {
        tax_amount = 0
    }

    if (previous_dues >= 0) {
        grandtotal = Number(amount) + Number(tax_amount) + Number(previous_dues);
    } else {
        grandtotal = Number(amount) + Number(tax_amount);
        try {
            if (previous_dues < 0) {
                grandtotal = grandtotal + Number(previous_dues);
            }
        } catch (o) {
        }
    }

    var l = 0;
    var m = 0;
    if (t == "P") {
        var m = (Number(e) * Number(grandtotal)) / 100;
    } else {
        var m = Number(e);
    }     // m = "P" == t ? e * grandtotal / 100 : e;

    var d = 0;
    "P" == a ? (l = Number(grandtotal) + Number(m), d = n * l / 100) : d = n, stapply = u * d / 100, document.getElementById("totalamount").value = Math.round(100 * grandtotal) / 100, grandtotal = Number(grandtotal) + Number(m) + Number(d) + Number(stapply), grandtotal = grandtotal - advance, document.getElementById("grandtotal").value = Math.round(100 * grandtotal) / 100
}

function calculateEventgrandtotal(id) {
    try {
        price = document.getElementById("unitcost" + id).value;
    } catch (o) {
        price = 0;
    }

    try {
        tax = document.getElementById("tax" + id).value;
    } catch (o) {
        tax = 0;
    }

    if (tax > 0) {

    } else {
        tax = 0;
    }

    var tax_amount = tax * price / 100;
    document.getElementById("taxcost" + id).value = tax_amount;
    grandtotal = Number(price) + Number(tax_amount);
    var l = 0;
    if (grandtotal > 0) {
        var m = 0;
        if (t == "P") {
            var m = (Number(e) * Number(grandtotal)) / 100;
        } else {
            var m = Number(e);
        }
        // m = "P" == t ? e * grandtotal / 100 : e;

        var d = 0;
        "P" == a ? (l = Number(grandtotal) + Number(m), d = n * l / 100) : d = n, stapply = u * d / 100, grandtotal = Number(grandtotal) + Number(m) + Number(d) + Number(stapply), document.getElementById("grandtotal" + id).value = Math.round(100 * grandtotal) / 100
    } else {
        document.getElementById("grandtotal" + id).value = 0;
    }
}

function addrange() {
    document.getElementById("range_todate").className = "col-md-7 open";
}

function removerange() {
    document.getElementById("range_todate").className = "col-md-7 collapse";
}

function setrange() {
    var status = document.getElementById("range_todate").className;
    var from_date = document.getElementById("from_date").value;
    var to_date = document.getElementById("to_date").value;
    if (status == 'col-md-7 open' && to_date != '') {
        document.getElementById("daterange").value = from_date + ' to ' + to_date;
    } else {
        document.getElementById("daterange").value = from_date;
    }

}



function removesupplier(id) {
    document.getElementById("spid" + id).checked = false;
    tableHead('new_supplier');
}

function AddsupplierRow(id) {
    if ($('#spid' + id).is(':checked')) {
        var name = document.getElementById('spname' + id).innerHTML;
        var contact = document.getElementById('spcontact' + id).innerHTML;
        var mobile = document.getElementById('spmobile' + id).innerHTML;
        var spemail = document.getElementById('spemail' + id).innerHTML;
        NumOfRow = id + 4544;
        var mainDiv = document.getElementById('new_supplier');
        var newDiv = document.createElement('tr');
        newDiv.setAttribute('id', 'row' + id);
        newDiv.innerHTML = '<td class="td-c"><input type="hidden" name="supplier[]" value="' + id + '">' + name + '</td><td class="td-c">' + contact + '</td><td class="td-c">' + mobile + '</td><td class="td-c">' + spemail + '</td><td class="td-c"><a href="javascript:;" id="' + id + '" onclick="removesupplier(this.id);$(this).closest(' + "'tr'" + ').remove();tableHead(' + "'new_supplier'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>';
        mainDiv.appendChild(newDiv);
    } else {
        removediv('row' + id);
    }
    tableHead('new_supplier');
}

function AddCustomerColumn(type, id, column_name, column_datatype) {
    if (type == 'custom') {
        checkbox_name = 'custom_id';
        text_name = 'custom_column_id';
    } else {
        checkbox_name = 'cust_id';
        text_name = 'customer_column_id';
    }
    if ($('#' + checkbox_name + id).is(':checked')) {

        if (column_datatype == 'textarea') {
            disable_textbox = '<textarea class="form-control input-sm" readonly="" ></textarea>';
        } else {
            disable_textbox = '<input type="text" class="form-control input-sm" readonly="" >';
        }


        icon = getIcon(column_datatype);
        var mainDiv = document.getElementById('new_customer_column');
        var newDiv = document.createElement('li');
        newDiv.setAttribute('class', 'ui-state-default');
        newDiv.setAttribute('id', type + id);
        newDiv.innerHTML = '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div class="form-group"><input name="customer_column_name[]" type="hidden" value="' + column_name + '" /><input name="' + text_name + '[]" type="hidden" value="' + id + '" />\n\
<div class="col-md-6"><div class="input-group"><span class="input-group-btn"><div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div></span><input type="text" readonly value="' + column_name + '"\n\
 readonly class="form-control input-sm" maxlength="40" "><span class="input-group-btn"><div class="btn default btn-sm">\n\
 <i id="icon76" class="fa ' + icon + '"></i></div></span></div></div><div class="col-md-6">\n\
<div class="input-group">' + disable_textbox + '\n\
<span class="input-group-addon "  onclick="uncheckbox(' + "'" + checkbox_name + id + "'" + ');"><i class="fa fa-minus-circle"></i></span></div>\n\
<span class="help-block"></span></div></div>';
        mainDiv.appendChild(newDiv);
    } else {
        removediv(type + id);
    }
}

function AddMainHeaderColumn(id, column_name, column_datatype) {
    type = 'main_h';
    checkbox_name = 'main_header_id';
    text_name = 'main_header_id';
    if ($('#' + checkbox_name + id).is(':checked')) {

        if (column_datatype == 'textarea') {
            disable_textbox = '<textarea class="form-control input-sm" id="profile' + id + '" readonly="" ></textarea>';
        } else {
            disable_textbox = '<input type="text" class="form-control input-sm" id="profile' + id + '" readonly="" >';
        }


        icon = getIcon(column_datatype);
        var mainDiv = document.getElementById('new_main_header_column');
        var newDiv = document.createElement('li');
        newDiv.setAttribute('class', 'ui-state-default');
        newDiv.setAttribute('id', type + id);
        newDiv.innerHTML = '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><div class="form-group"  id="exist76"><input name="' + text_name + '[]" type="hidden" value="' + id + '" /><input name="headerid[]" type="hidden" value="-1" /><input name="main_header_default[]" id="main_header_default' + id + '" type="hidden" value="Profile" />\n\
<div class="col-md-6"><div class="input-group"><span class="input-group-btn"><div class="btn default btn-sm"> <i id="icon76" class="fa fa-arrows-v"></i></div></span><input type="text" name="main_header_name[]" readonly value="' + column_name + '"\n\
 readonly class="form-control input-sm" maxlength="40" "><span class="input-group-btn"><div class="btn default btn-sm">\n\
 <i id="icon76" class="fa ' + icon + '"></i></div></span></div></div><div class="col-md-6">\n\
<div class="input-group">' + disable_textbox + '\n\
        <span class="input-group-addon "  onclick="uncheckbox(' + "'" + checkbox_name + id + "'" + ');"><i class="fa fa-minus-circle"></i></span></div>\n\
<span class="help-block"></span></div></div>';
        mainDiv.appendChild(newDiv);
        ChangeDefaultValue(id);
        setProfileDetails();
    } else {
        removediv(type + id);
    }
}

function uncheckbox(id) {
    document.getElementById('' + id + '').checked = false;
    $('#uniform-' + id).find('span').removeClass('checked');
    document.getElementById('' + id + '').onchange();
}
function ChangeDefaultValue(id) {
    if ($('#main_header_default_id' + id).is(':checked')) {
        document.getElementById('main_header_default' + id).value = 'Profile';
    } else {
        document.getElementById('main_header_default' + id).value = 'Profile';
    }
}

function removediv(id) {
    var elem = document.getElementById(id);
    elem.parentNode.removeChild(elem);
}







function due_dateconfirm() {
    var date = document.getElementById('date6').value;
    var billdatedate = document.getElementById('date5').value;
    var today = new Date();
    var current_date = today.getDate() + "/" + today.getMonth() + "/" + today.getFullYear();
    if (new Date(date).getTime() < new Date(billdatedate).getTime()) {
        alert('Bill date should not be greater than due date.');
        return false;
    } else {
        var date = document.getElementById('date6').value;
        var billdate = new Date(date);
        var date = billdate.getDate() + "/" + billdate.getMonth() + "/" + billdate.getFullYear();
        if (new Date(date).getTime() < new Date(current_date).getTime()) {
            if (!confirm('Due date is less than current date. Are you sure to continue?'))
                return false;
        } else {
            try {
                var expirydate = document.getElementById('function1').value;
            } catch (o) {
                return true;
            }

            if (expirydate.length > 5) {
                if (new Date(date).getTime() > new Date(expirydate).getTime()) {
                    alert('Expiry date should not be less than due date.');
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
    }

    // return false;
}

function coveringInvoice(payment_request_id) {
    var covering_id = $('#covering_select').val();
    if ($('#confirm_covering').is(':checked') && $('#notify_').is(':checked') && covering_id > 0) {
        try {
            var data = '';
            $.ajax({
                type: 'GET',
                url: '/ajax/getCoveringDetail/' + covering_id,
                data: data,
                success: function (data) {
                    obj = JSON.parse(data);
                    _('conf_template_name').value = obj.template_name;
                    _('conf_mail_sub').value = obj.subject;
                    _('conf_inv_label').value = obj.invoice_label;
                    $("#confsummernote").summernote('code', obj.body);
                }
            });
        } catch (o) {

        }
        _('conf_cov').click();
        return false;
    } else {
        validateInvoice(payment_request_id);
    }
    return false;
}

function checkCurrentContractAmount(payment_request_id) {
    grand_total = document.getElementById("grandtotal").value
    if (grand_total == 0 || grand_total == '') {
        $("#amount_check").modal();
        return false;
    } else {
        validateInvoice(payment_request_id);
    }
    return false;
}


function validateInvoice(payment_request_id) {
    document.getElementById('loader').style.display = 'block';
    var res = '';
    var data = $("#invoice").serialize();
    if (payment_request_id != '') {
        payment_request_id = '/' + payment_request_id;
    }
    $.ajax({
        type: 'POST',
        url: '/merchant/invoice/invoicesave/validate' + payment_request_id,
        data: data,
        success: function (data) {
            res = data;
            if (data == 'yes') {
                document.getElementById("invoice").submit();
                return true;
            } else {
                obj = JSON.parse(data);
                var error = '';
                $.each(obj['error'], function (index, value) {
                    error = error + value.value + '<br>';
                });
                document.getElementById('invoiceerrorshow').style.display = 'block';
                document.getElementById('invoiceerror_display').innerHTML = error;
                var $target = $('html,body');
                $target.animate({ scrollTop: -50 }, 500);
                document.getElementById('loader').style.display = 'none';
                return false;

            }

        }
    });
    // alert(data);
    if (res == 'yes') {
        return true;
    } else {
        return false;
    }

}

function notifyPatron(id) {

    if ($('#' + id).is(':checked')) {
        document.getElementById('is_' + id).value = '1';
        try {
            document.getElementById('subbtn').value = 'Save & Send';
        } catch (o) {
        }
    } else {
        document.getElementById('is_' + id).value = '0';
        try {
            document.getElementById('subbtn').value = 'Save';
        } catch (o) {
        }
    }
}



function saveCoupon() {
    var data = $("#couponForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/coupon/save/popup',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                var x = document.getElementById("coupon_select");
                var option = document.createElement("option");
                option.text = obj.code;
                option.value = obj.id;
                option.selected = 1;
                x.add(option);
                $('#couponForm').trigger("reset");
                $("#closebutton").click();
            } else {
                var error = '';
                try {
                    $.each(obj['error'], function (index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('errorshow').style.display = 'block';
                    document.getElementById('error_display').innerHTML = error;
                } catch (o) {
                    alert(o.message);
                }
            }

        }
    });
    return false;
}

function saveCustomer() {
    var data = $("#customerForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/customersave/popup',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                try {
                    var x = document.getElementById("customer_id");
                    var option = document.createElement("option");
                    option.text = obj.name;
                    option.value = obj.id;
                    option.selected = 1;
                    x.add(option);
                    selectCustomer(obj.id);
                } catch (o) {

                }

                $('#customerForm').trigger("reset");
                $("#closebutton1").click();
            } else {
                var error = '';
                try {
                    $.each(obj['error'], function (index, value) {
                        error = error + value.value + '<br>';
                    });
                    document.getElementById('errorshow').style.display = 'block';
                    document.getElementById('error_display').innerHTML = error;
                } catch (o) {
                    alert(o.message);
                }
            }

        }
    });
    return false;
}


function confirmreplace() {
    count = $("input.excust[type=checkbox]:checked").length;
    if (count > 0) {
        $('#confirmm').click();
        document.getElementById("totalchecked").innerHTML = count;
    } else {
        alert('Please select atlist 1 customer to replace');
    }
    return false;
}
function updateCustomer() {
    document.getElementById('loader').style.display = 'block';
    var data = $("#customerForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/updatesave/popup',
        data: data,
        success: function (data) {
            return false;
        }
    });
    document.getElementById('loader').style.display = 'none';
    return false;
}

function updatemultiCustomer() {
    count = $("input.excust[type=checkbox]:checked").length;
    if (count > 0) {
        var success = 0;
        try {
            $("input.excust[type=checkbox]:checked").each(function () {
                document.getElementById('loader').style.display = 'block';
                document.getElementById("customer_id_").value = $(this).val();
                document.getElementById("customer_code").value = $(this).attr('title');
                updateCustomer();
                success = 1;
            });
        } catch (o) {
        }
        if (success === 1) {
            $('#customerForm').trigger("reset");
            $("#closebutton1").click();
        }
    } else {
        alert('Please select atlist 1 customer to replace');
    }
    return false;
}



function deleteCustomer() {
    var data = '';
    var customer_id = document.getElementById("customer_id_").value;
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/deletecustomer/' + customer_id,
        data: data, success: function (data) {
            saveCustomer();
        }
    });
    return false;
}
function display_warning() {
    try {
        var form = $('#customerForm');
        var error = $('.alert-danger', form);
        if (form.valid() == false) {
            return false;
        } else {
            error.hide();
        }
    } catch (o) {
        //alert(o.message);
    }

    var customer_code = document.getElementById('customer_code').value;
    var data = $("#customerForm").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/customer/isexist/' + customer_code,
        data: data,
        success: function (data) {
            if (data != 'false') {
                obj = JSON.parse(data);
                if (obj.customer_code == 1) {
                    document.getElementById("customer_id_").value = obj.customer_id;
                    document.getElementById("warning").style.display = 'block';
                    document.getElementById("ex_message").innerHTML = 'This customer code already exists in your database.';
                    document.getElementById("ex_add").style.display = 'none';
                    document.getElementById("ex_delete").style.display = 'initial';
                    checkedcheckbox = 'checked onclick="return false;"';
                } else {
                    document.getElementById("customer_id_").value = obj.customer_id;
                    document.getElementById("warning").style.display = 'block';
                    comma = '';
                    ismob = '';
                    isemail = '';
                    if (obj.mobile == 0) {
                        ismob = 'Mobile Number';
                        comma = ',';
                    }
                    if (obj.email == 0) {
                        isemail = 'Email ID' + comma;
                    }
                    document.getElementById("ex_message").innerHTML = 'This ' + isemail + ' ' + ismob + ' already exists in your customer database. You could either replace this record or create a new entry with same values.<br> Alternatively you can change the data entered from the Customer create screen below.';
                    document.getElementById("ex_delete").style.display = 'none';
                    document.getElementById("ex_add").style.display = 'initial';
                    checkedcheckbox = '';
                }
                var alltr = '';
                $.each(obj.customer_detail, function (index, value) {
                    alltr = alltr + '<tr><td class="td-c">' + value.customer_code + '</td><td class="td-c">' + value.name + '</td><td class="td-c">' + value.email + '</td><td class="td-c">' + value.mobile + '</td><td class="td-c"><input type="checkbox" ' + checkedcheckbox + ' class="excust" name="exist_customer[]" title="' + value.customer_code + '" value="' + value.customer_id + '"></td></tr>';
                });
                document.getElementById("allcusta").innerHTML = alltr;
                return false;
            } else {
                document.getElementById("warning").style.display = 'none';
                saveCustomer();
            }

        }
    });
    return false;
}



function validatefilesize(maxsize, id) {
    var x = document.getElementById(id);
    var txt = "";
    if (maxsize == 500000) {
        max = '500 KB';
    } else {
        max = '2 MB';
    }
    if (maxsize == 1000000) {
        max = '1 MB';
    }
    if (maxsize == 3000000) {
        max = '3 MB';
    }
    if ('files' in x) {

        if (x.files.length == 0) {
        } else {
            for (var i = 0; i < x.files.length; i++) {
                var file = x.files[i];
                if (file.size > maxsize) {
                    alert('File size should be less than ' + max);
                    try {
                        document.getElementById('imgdismiss').click();
                    } catch (o) {
                    }
                    try {
                        document.getElementById(id).value = "";
                    } catch (o) {
                    }
                    return false;
                }
            }
        }
    }
}

function isFixed(id) {
    if (id == 1) {
        $("#fixed_div").slideDown(200).fadeIn();
        $("#per_div").slideDown(200).fadeOut();
    } else {
        $("#per_div").slideDown(200).fadeIn();
        $("#fixed_div").slideDown(200).fadeOut();
    }
}



function changeeventcustom_datatype(datatype) {
    try {
        if (datatype == 'dropdown' || datatype == 'multicheckbox') {
            document.getElementById('custom_colum_value_div').style.display = 'block';
        } else {
            document.getElementById('custom_colum_value_div').style.display = 'none';
        }

    } catch (o) {
        // alert(o.message);
    }
}

function validateCoupon(merchant_id) {
    var coupon_code = document.getElementById('coupon_code').value;
    coupon_code = coupon_code.split(' ').join('SPACE');
    var coupon_id = '';
    try {
        var data = '';
        $.ajax({
            type: 'POST',
            url: '/patron/paymentrequest/couponvalidate/' + merchant_id + '/' + coupon_code,
            data: data,
            success: function (data) {
                if (data == 'false') {
                    document.getElementById('coupon_status').innerHTML = 'Invalid coupon code';
                    try {
                        document.getElementById('coupon_id').value = '';
                    } catch (o) {
                    }
                } else {
                    obj = JSON.parse(data);
                    document.getElementById('coupon_id').value = obj.coupon_id;
                    try {
                        document.getElementById('coupon_status').innerHTML = 'Your coupon discount has been applied<br>' + obj.descreption;
                    } catch (o) {
                    }
                    calculateevent(null);
                    return false;
                }
            }
        });
    } catch (o) {
    }
    calculateevent(null);
    return false;
}

function saveSequence() {
    var data = '';
    var prefix = document.getElementById("prefix").value;
    var last_no = document.getElementById("current_number").value;
    data = {
        'prefix': prefix, //for get email 
        'last_no': last_no, //for get email 
    };
    $.ajax({
        type: 'POST',
        url: '/ajax/savesequence',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                var x = document.getElementById("mapping_value");
                var option = document.createElement("option");
                option.text = obj.name;
                option.value = obj.id;
                option.selected = 1;
                x.add(option);
                document.getElementById('seq_error').innerHTML = '';
                document.getElementById("prefix").value = '';
                document.getElementById("current_number").value = '0';
                document.getElementById("auto_inv_div").style.display = 'none';
                var objjs = JSON.parse(invoice_numbers);
                objjs.push({ "auto_invoice_id": "" + obj.id + "", "prefix": "" + obj.prefix + "", "val": "" + obj.number + "" });
                invoice_numbers = JSON.stringify(objjs);
            } else {
                document.getElementById('seq_error').innerHTML = obj.error;
            }

        }
    });
    return false;
}

function newInvSeq() {
    document.getElementById("auto_inv_div").style.display = 'inline-block';
    document.getElementById("prefix").value = '';
    document.getElementById("current_number").value = '0';
    document.getElementById('seq_error').innerHTML = '';
}

function customReminder(rmjson) {
    try {
        var due_date = _('due_date').value;
    } catch (o) {
        var due_date = _('due_datetime').value;
    }
    var d = JSON.parse(rmjson);
    $.each(d, function (index, value) {
        try {
            if (value != '') {
                var d = new Date(due_date);
                var datenew = new Date(d.getTime() - value * 24 * 60 * 60 * 1000);
                var day = datenew.getDate();
                var month = datenew.getMonth() + 1;
                var year = datenew.getFullYear();
                var fulldate = ("0" + day).slice(-2) + " " + monthNames[month] + " " + year;
                _('rm' + index).value = fulldate;
            }
        } catch (o) {
        }
    });
}

function pluginChange(val, id, function_id = null) {
    if (val == true) {
        document.getElementById("pg" + id).style.display = 'block';
        $('#' + id).bootstrapSwitch('state', true);
        if (function_id != null) {
            setFunctionPlugin(function_id);
        }

        if (val == false && id == "isonlinepayments") {
            checkValue('is_enable_payments_', true);
            $('#isenablepayments').bootstrapSwitch('state', true);
            $("#enable_payments_div").hide();
        } else if (val == true && id == "isonlinepayments") {
            checkValue('is_enable_payments_', true);
            $('#isenablepayments').bootstrapSwitch('state', true);
            $("#enable_payments_div").show();
        }

    } else {
        //document.getElementById("pg" + id).style.display = 'none';
        $('#' + id).bootstrapSwitch('state', false);
        if (function_id != null) {
            window.livewire.emit('removePlugin', function_id);
        }
    }
}

function disableFunctionPlugin(val, id, function_id = null) {
    if (val == false) {
        if ($('#' + id).is(':checked')) {
            $('#' + id).bootstrapSwitch('state', val);
            if (function_id != null) {
                if (function_id == 20) {
                    document.getElementById("is_custmized_receipt_field").value = '';
                    $("#edit_receipt_fields_div").hide();
                }
                window.livewire.emit('removePlugin', function_id);
            }
        }
    } else {
        if ($('#' + id).is(':checked') == false) {
            $('#' + id).bootstrapSwitch('state', val);
            if (function_id != null) {
                setFunctionPlugin(function_id);
            }
            //$('#fmodel').click();
        }

    }

}

function drawBillingSections() {
    try {
        var mainDiv = document.getElementById('billing_details');
        mainDiv.innerHTML = " ";
        count = 0;

        if (drawDefaultBillingRows && drawDefaultBillingRows != '') {
            billingRows = JSON.parse(drawDefaultBillingRows);
            $.each(billingRows, function (index, value) {
                var newDiv = document.createElement('div');
                newDiv.innerHTML = '<div class="col-md-12 draggable" draggable="true" ondragstart="dragStart(event)" id="custom-billing-field-' + index + '" parentNode="' + value['parentNode'] + '" isDefault="' + value['isDefault'] + '" isMandatory="0"><div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div><div class="col-md-6" id="lbl-custom-billing-field-' + index + '">' + value['label'] + '</div><div class="col-md-4 text-gray-500" id="old-value-custom-billing-field-' + index + '" style="display:none">' + value['default_value'] + '</div></div>';
                mainDiv.appendChild(newDiv);
                count = index + 1;
            });
        }

        $('input[txtType="billing"]').map(function (i) {  //input[name="headercolumn[]"]
            if (!exist_available_fields.includes(this.value)) {
                index = count + i;
                var newDiv = document.createElement('div');
                //var element = document.getElementById('custom-billing-field-'+index);
                newDiv.innerHTML = '<div class="col-md-12 draggable" draggable="true" ondragstart="dragStart(event)" id="custom-billing-field-' + index + '" parentNode="billing" isDefault="0" isMandatory="0"><div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div><div class="col-md-6" id="lbl-custom-billing-field-' + index + '">' + this.value + '</div><div class="col-md-4 text-gray-500" id="old-value-custom-billing-field-' + index + '"></div></div>';
                mainDiv.appendChild(newDiv);
            }
        }).get();
    } catch (o) {
    }
}

function drawCustomerSections() {
    try {
        var mainDiv = document.getElementById('customer_details');
        mainDiv.innerHTML = " ";
        count = 0;
        if (drawDefaultCustomerRows != '') {
            customerRows = JSON.parse(drawDefaultCustomerRows);
            $.each(customerRows, function (index, value) {
                var newDiv = document.createElement('div');
                newDiv.innerHTML = '<div class="col-md-12 draggable" draggable="true" ondragstart="dragStart(event)" id="custom-customer-field-' + index + '" parentNode="' + value['parentNode'] + '" isDefault="' + value['isDefault'] + '" isMandatory="0"><div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div><div class="col-md-6" id="lbl-custom-customer-field-' + index + '">' + value['label'] + '</div><div class="col-md-4 text-gray-500" id="old-value-custom-customer-field-' + index + '" style="display:none">' + value['default_value'] + '</div></div>';
                mainDiv.appendChild(newDiv);
                count = index + 1;
            });
        }

        $('input[name="customer_column_name[]"]').map(function (i) {
            if (this.value == 'Customer code' || this.value == 'Member code') { }
            else if (this.value == 'Customer name' || this.value == 'Member name') { }
            else if (this.value == 'Email ID') { }
            else {
                if (!exist_available_fields.includes(this.value)) {
                    index = count + i;
                    var newDiv = document.createElement('div');
                    newDiv.innerHTML = '<div class="col-md-12 draggable" draggable="true" ondragstart="dragStart(event)" id="custom-customer-field-' + index + '" parentNode="customer" isDefault="0" isMandatory="0"><div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div><div class="col-md-6" id="lbl-custom-customer-field-' + index + '">' + this.value + '</div><div class="col-md-4 text-gray-500" id="old-value-custom-customer-field-' + index + '"></div></div>';
                    mainDiv.appendChild(newDiv);
                }
            }
        }).get();
    } catch (o) {
    }
}

function setFunctionPlugin(function_id) {
    document.getElementById('closebuttonp').click();
    if (function_id == 20) {
        //hide richtextbox to avoid drag drop text on panel
        document.getElementById("new_tnc").style.display = "none";
        //call function to draw all customer details and billing setcions
        drawCustomerSections();
        drawBillingSections();
        document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
        document.getElementById("panelWrapId").style.transform = "translateX(0%)";
        $('.page-sidebar-wrapper').css('pointer-events', 'none');
        $("#edit_receipt_fields_div").show();
    } else {
        document.getElementById('fmodel').click();
        window.livewire.emit('modalFunctionShow', 1, function_id);
    }
}

function disablePlugin(val, id) {
    if (val == false) {
        if ($('#' + id).is(':checked')) {
            $('#' + id).bootstrapSwitch('state', val);
        }
    }
    if (val == false && id == "plg14") {
        checkValue('is_enable_payments_', true);
        $('#isenablepayments').bootstrapSwitch('state', true);
        $("#enable_payments_div").hide();
    } else if (val == true && id == "plg14") {
        $("#enable_payments_div").show();
    }
    else if (val == false && id == "plg13") {
        $("#min_partial_payment_div").hide();
    } else if (val == true && id == "plg13") {
        document.getElementById("pma").min = 50;
        $("#min_partial_payment_div").show();
    }

}

function add_late_fee() {
    var amount, latefee;
    amount = document.getElementById('amount').value;
    latefee = document.getElementById('late_fee').value;
    var numbers = /^[-+]?[0-9]+$/;
    if (latefee.match(numbers)) {
    } else {
        latefee = 0;
    }
    result = (parseFloat(amount) + parseFloat(latefee));
    if (result > 0) {
        document.getElementById('amount_with_latefee').value = result;
    }
    calculatesimplegrandtotal();
}


function autocomplete(row) {
    /*the autocomplete function takes two arguments,
     the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    if (products == null) {
        return false;
    }
    inp = document.getElementById("particular" + row);
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        arr = products;
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    vall = this.getElementsByTagName("input")[0].value;
                    document.getElementById("particular" + row).value = vall;
                    product_rate(vall, row);
                    /*close the list of autocompleted values,
                     (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x)
            x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
             increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
             decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x)
                    x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x)
            return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length)
            currentFocus = 0;
        if (currentFocus < 0)
            currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
         except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}


function autocompleteTax(row) {
    /*the autocomplete function takes two arguments,
     the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    if (tax_master == null) {
        return false;
    }
    inp = document.getElementById("tax" + row);
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        arr = tax_master;
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/

                    vall = this.getElementsByTagName("input")[0].value;
                    document.getElementById("tax" + row).value = vall;
                    tax_rate(vall, row);
                    /*close the list of autocompleted values,
                     (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x)
            x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
             increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
             decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x)
                    x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x)
            return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length)
            currentFocus = 0;
        if (currentFocus < 0)
            currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
         except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}



function checkValue(id, check) {
    if (check == true) {
        val = 1;
        style = 'block';
    } else {
        val = 0;
        style = 'none';
    }
    _(id).value = val;
    try {
        _('partial_min_amt').style.display = style;
    } catch (o) {

    }
}

function handlepartialcheck(check) {
    if (check == true) {
        _('partial_min_amt').style.display = 'block';
        try {
            _('is_partial_').value = '1';
        } catch (o) {

        }
    } else {
        _('partial_min_amt').style.display = 'none';
        try {
            _('is_partial_').value = '0';
        } catch (o) {

        }
    }
}

/*An array containing all the country names in the world:*/

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/

function drawParticularTable() {
    var i;
    var head = '<thead><tr>';
    var footer = '<tbody><tr class="warning">';
    for (i = 1; i < 13; i++) {
        if (_('pc_' + i).checked == true) {
            head = head + '<th class="td-c">' + _('pc_name_' + i).value + '</th>';
            text_name = _('pc_name_' + i).name;
            if (text_name == 'pc_item') {
                footer = footer + '<td><input type="text" name="particular_total" value="Particular total" id="particular_totallabel" class="form-control input-sm" placeholder="Enter total label"></td>';
            } else if (text_name == 'pc_total_amount') {
                footer = footer + '<td><input type="text" class="form-control input-sm" readonly=""></td>';
            } else {
                footer = footer + '<td></td>';
            }
        }

    }
    head = head + '<th class="td-c">Action</th></tr></thead><tbody id="new_particular"></tbody>';
    footer = footer + '<td></td></tr></tbody>';
    _('particular_table').innerHTML = head + footer;
}

function drawTravelTable(type) {
    var i;
    var head = '<thead><tr>';
    var footer = '</tr><tbody><tr>';
    var body = '<tr>';
    var c = 0;
    count = $('input[name="' + type + '_col[]"]').length;

    for (i = 1; i <= count; i++) {
        if (_(type + '_' + i).checked == true) {
            head = head + '<th class="td-c">' + _(type + '_name_' + i).value + '</th>';
            text_name = _(type + '_name_' + i).name;
            if (text_name == type + '_sr_no') {
                body = body + '<td class="td-c">x</td>';
            } else {
                body = body + '<td class="td-c">xxxx</td>';
            }
            c++;
        }

    }
    varcol = c - 1;
    head = head + '</tr></thead><tbody ></tbody>';
    footer = footer + '<td colspan="' + varcol + '" ><b class="pull-right">Total Rs.</b></td><td class="td-c">xxxx</td>';
    footer = footer + '</tr></tbody>';
    _(type + '_table').innerHTML = head + body + footer;
}

function AddparticularRow(defaultval) {
    //var x = document.getElementById("particular_table").rows.length;
    var mainDiv = document.getElementById('new_particular');
    var newDiv = document.createElement('tr');
    var i;
    var row = '';
    for (i = 1; i < 13; i++) {
        if (_('pc_' + i).checked == true) {
            text_name = _('pc_name_' + i).name;
            if (text_name == 'pc_item') {
                row = row + '<td><input type="text" maxlength="100" name="particularname[]" value="' + defaultval + '" class="form-control input-sm" placeholder="Add label"></td>';
            }
            else if (text_name == 'pc_sr_no') {
                row = row + '<td style="max-width:10px;">#</td>';
            }
            else {
                row = row + '<td><input type="text" class="form-control input-sm" readonly=""></td>';
            }
        }

    }
    row = row + '<td><a href="javascript:;" onclick="$(this).closest(' + "'" + 'tr' + "'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
    newDiv.innerHTML = row;
    mainDiv.appendChild(newDiv);
}

function AddTax() {
    var mainDiv = document.getElementById('new_tax');
    var newDiv = document.createElement('tr');
    var tax_div = _('tax_dropdown').innerHTML;
    newDiv.innerHTML = '<td><div class="input-icon right">' + tax_div + '</div></td><td><div class="input-icon right"><input type="text" readonly name="tax_per[]" class="form-control input-sm" ></div></td><td><input type="text" readonly class="form-control input-sm" ></td><td><input type="text" class="form-control input-sm" readonly></td><td><input type="text" readonly class="form-control input-sm"></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i>  </a></td>';
    mainDiv.appendChild(newDiv);
}

function setTaxPercent(tax_id) {
    var num = $('select[name="tax_id[]"]').length;
    for (var i = 0; i < num; i++) {
        tax_id = Number($('select[name="tax_id[]"]')[i].value);
        if (tax_id > 0) {
            percent = tax_array[tax_id].percentage;
        }
        else {
            percent = 0;
        }
        $('input[name="tax_per[]"]')[i].value = percent;
    }
}


function setProfileDetails() {
    profile_id = 0;
    if (_('profile_id')) {
        profile_id = _('profile_id').value;
    }
    if (profile_id == "") {
        profile_id = 0;
    }

    $.ajax({
        type: 'GET',
        url: '/ajax/getprofileinfo/' + profile_id,
        data: '',
        success: function (data) {
            obj = JSON.parse(data);
            $.each(obj, function (index, value) {
                try {
                    if (index == 12) {
                        _('profile' + index).innerHTML = value;
                    } else {
                        _('profile' + index).value = value;
                    }
                } catch (o) {

                }
            });

            if (obj.seq > 0) {
                setInvoiceNoColumn(obj.seq);
            }


        }
    });
}

function setInvoiceNoColumn(seq) {
    exist = 0;
    $('input[name="function_id[]"]').each(function (index, value) {
        fid = this.value;
        if (fid == 9) {
            param = $('input[name="function_param[]"]')[index].value;
            if (param == 'system_generated') {
                $('input[name="function_val[]"]')[index].value = seq;
            }
            exist = 1;
        }
    });
    $('input[name="exist_function_id[]"]').each(function (index, value) {
        fid = this.value;
        if (fid == 9) {
            param = $('input[name="exist_function_param[]"]')[index].value;
            if (param == 'system_generated') {
                $('input[name="exist_function_val[]"]')[index].value = seq;
            }
            exist = 1;
        }
    });
    if (exist == 0) {
        _('datatype').value = 'text';
        datatypeChange('text');
        _('custom_column_name').value = 'Invoice No.';
        _('column_function').value = "9";
        getMapping(9);
        _('mapping_param').value = 'system_generated';
        handleparam(9, 'system_generated');
        _('mapping_value').value = seq;
        addHeader();
    }
}

function tableHead(name) {
    try {
        tbodyRowCount = $('#t_' + name).find('tr').length;
        if (tbodyRowCount > 1) {
            _('h_' + name).style.display = 'contents';
        } else {
            _('h_' + name).style.display = 'none';
        }
    } catch (o) {

    }
}

function validateColumn() {
    var status = 1;
    try {
        if (document.getElementById('coltype').hasAttribute('required') && document.getElementById('coltype').value == '') {
            status = 0;
        }
    }
    catch (o) {
    }
    try {
        if (document.getElementById('colmapvalue').hasAttribute('required') && document.getElementById('colmapvalue').value == '') {
            status = 0;
        }
    }
    catch (o) {
    }
    if (status == 1) {
        $('#custom').modal('hide');
        window.livewire.emit('saveColumn');
    } else {
        document.getElementById('colerror').style.display = 'block';
        document.getElementById('colerror').innerHTML = 'Please fill all required fields';
    }

}

function resetPreviewFields() {
    //$("#target_drop2").load(location.href + " #target_drop2" );
    $("#target_drop2").load(location.href + " #target_drop2>*", "");
    //$("#available_fields").load(location.href+" #available_fields>*","");
    drawCustomerSections();
    drawBillingSections();
    $("#resetFieldsClose").click();
}

function setCusomizedReceiptFieldsValue() {
    var data = $("#set_custom_payment_receipt_fields").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/invoiceformat/savePluginValue',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                document.getElementById("is_custmized_receipt_field").value = obj.plginValue;
                hideCustomizePanel();
            } else {
                document.getElementById("is_custmized_receipt_field").value = '';
            }
        },
        error: function (data) {
        }
    });
    return false;
}

function saveInvoicePreviewStatus(id) {
    try {
        if (id == 'savepreview') {
            document.getElementById("preview_invoice_status").value = 11;
        } else {
            document.getElementById("preview_invoice_status").value = 0;
        }
    } catch (o) {
    }
}

function showStateDiv(country_name) {
    if (country_name != '') {
        if (country_name == 'India') {
            $('#state_txt').hide();
            $('#state_drpdown').show();
            $('#s2id_state_drpdown').show();
            $("#country_code_txt").text('+91');
            $("#defaultmobile").attr('pattern', "([0-9]{10})");  //^(\+[\d]{1,5}|0)?[1-9]\d{9}$
            $("#defaultmobile").attr('maxlength', "10");
        } else {
            $('#state_drpdown').hide();
            $('#s2id_state_drpdown').hide();
            $('#state_txt').show();
            $("#defaultmobile").attr('pattern', "([0-9]{7,10})");
            $("#defaultmobile").attr('maxlength', "10");
            $.ajax({
                type: 'POST',
                url: '/ajax/getCountryCode',
                data: {
                    'country_name': country_name
                },
                success: function (data) {
                    obj = JSON.parse(data);
                    if (obj.status == 1) {
                        $("#country_code_txt").text('+' + obj.country_code);
                    } else {
                        $("#country_code_txt").text('');
                    }
                }
            });
        }
    }
}


function saveDocument() {
    document_name = document.getElementById('document_name').value;
    document_description = document.getElementById('document_description').value;
    if(document_name == '' || document_description == ''){
        var div = document.getElementById('mandatory_docs');
        div.innerHTML += 'Document name and description cannot be blank!';
        div.style.display = 'block';
        return false;
    }
    document_action = document.getElementById('document_action').value;
    var mainDiv = document.getElementById('new_documents');
    var newDiv = document.createElement('tr');
    hidden = '<input type="hidden" name="mandatory_document_name[]" value="' + document_name + '"></input><input type="hidden" name="mandatory_document_description[]" value="' + document_description + '"></input><input type="hidden" name="mandatory_document_action[]" value="' + document_action + '"></input>';

    newDiv.innerHTML = '<td class="td-c  default-font">' + document_name + ' ' + hidden + '</td><td class="td-c  default-font">' + document_description + '</td><td class="td-c"><a href="javascript:;" onclick="$(this).closest(' + "'tr'" + ').remove();" class="btn btn-xs red"> <i class="fa fa-times"> </i> </a></td>';
    mainDiv.appendChild(newDiv);
    document.getElementById('document_name').value = '';
    document.getElementById('document_description').value = '';
    var div = document.getElementById('mandatory_docs');
    div.innerHTML += '';
    div.style.display = 'none';
    document.getElementById('documentclose').click();

}


function setCheckbox(val, id) {
    _(id).checked = val;
    if (val == false) {
        _('document_attachment_div').style.display = 'none';
        _('document_attachment_button').style.display = 'none';
    } else {
        _('document_attachment_div').style.display = 'block';
        _('document_attachment_button').style.display = 'block';
    }
}