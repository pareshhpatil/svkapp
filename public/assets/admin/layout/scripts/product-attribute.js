function AddProductAttributes() {
    var mainDiv = document.getElementById('new_attribute');
    var newDiv = document.createElement('tr');

    var table = document.getElementById("new_attribute");
    var rows = table.getElementsByTagName("tr")
    if(rows.length < 10) {
        newDiv.innerHTML = '<td><div class="input-icon right"><input type="text" required maxlength="45" name="default_values[]" class="form-control " placeholder="Add variation value"></div></td><td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();showAddRowBtn();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
        mainDiv.appendChild(newDiv);
    } else { 
        $("#add_var_value_btn").hide();
    }
}

function showAddRowBtn() {
    var table = document.getElementById("new_attribute");
    var rows = table.getElementsByTagName("tr")
    if(rows.length < 10) {
        $("#add_var_value_btn").show();
    } else {
        $("#add_var_value_btn").hide();
    }
}