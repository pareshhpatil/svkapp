var product_index = null;
var currect_select_dropdwn_id = null;
window.onload = function () {
    var product_type = $('input[name="type"]:checked').val();
    var has_stock_keeping = $("#has_stock_keeping").val();
    var goods_type = $("#goods_type").val();

    setInputFields(product_type, goods_type);

    //check has inventory is set and type is product then validate available_stock and as of date is mendatory
    if (typeof has_stock_keeping != "undefined") {
        if (has_stock_keeping == '1' && product_type == 'Goods') {
            document.getElementById("available_stock").required = true;
        } else {
            document.getElementById("available_stock").required = false;
        }
    }
}

/* This function is used for hide or show input fileds according to product type while adding the product */
function setInputFields(product_type, goods_type = 'simple') {
    try {
        $("#goods_type").val(goods_type);
        if (product_type) {
            if (product_type.value == 'Goods' || product_type == 'Goods') {
                $("#product_type_div").show();
                document.getElementById("sac_code_lbl").innerHTML = 'HSN';
                document.getElementById("product_name_lbl").innerHTML = "Product";
                document.getElementById("product_name_lbl2").innerHTML = "Product";
                document.getElementById("modal_sac_code_lbl").innerHTML = 'HSN';
                livewire.emit('setProductType', 'Goods');
                if (goods_type == 'simple') {
                    //$("#product_img_div").show();
                    //livewire.emit('remove');
                    setView('simple');
                } else if (goods_type = 'variable') {
                    setView('variable');
                    // $("#manage_inventory_row").hide();
                    // $("#sku_div").hide();
                    // $("#sale_po_info").hide();
                    // $("#variable_product_div").show();
                    // $("#product_img_div").hide();
                }
            } else if (product_type.value == 'Service' || product_type == 'Service') {
                setFieldsForServiceType();
            }
        } else {
            setFieldsForServiceType();
        }
    }
    catch (o) { }
}

function setFieldsForServiceType() {
    try {
        $("#variable_product_div").hide();
        livewire.emit('remove');
        document.getElementById("product_name_lbl").innerHTML = "Service";
        document.getElementById("product_name_lbl2").innerHTML = "Service";
        document.getElementById("sac_code_lbl").innerHTML = 'SAC';
        document.getElementById("modal_sac_code_lbl").innerHTML = 'SAC';
        livewire.emit('setProductType', 'Service');
        $("#product_img_div").hide();
        $('.stock_keep_div_simple').hide();
        $("#product_type_div").hide();
        $("#sale_po_info").show();
        $("#sku_div").show();
        document.getElementsByClassName('stock_details_simple')[0].style.display = 'none';
        document.getElementById("available_stock").required = false;
        document.getElementById("has_stock_keeping").value = "0";
        document.getElementById("has_inventory").value = "0";
        $('#has_inventory').bootstrapSwitch('state');
        $('#has_inventory').bootstrapSwitch('state', false);
    } catch (o) { }
}

/* This function is used to hide or show indeventory div while creating product */
function stockDivEnable(type, clsName, idName) {
    var product_type = $('input[name="type"]:checked').val();
    if (type == true) {
        document.getElementsByClassName(clsName)[0].style.display = 'block';
        document.getElementById(idName).value = "1";
        if (product_type == 'Goods') {
            document.getElementById("available_stock").required = true;
        }
    } else {
        document.getElementById(idName).value = "0";
        document.getElementById("has_inventory").value = "0";
        document.getElementsByClassName(clsName)[0].style.display = 'none';
        document.getElementById("available_stock").required = false;
    }
}

function enableInventoryCTA(type) {
    if (type == true) {
        document.getElementById('enable_service').style.display = 'block';
    } else {
        document.getElementById('enable_service').style.display = 'none';
    }
}

function setValueForTax(type, inputID) {
    if (type == true) {
        document.getElementById(inputID).value = "1";
    } else {
        document.getElementById(inputID).value = "0";
    }
}

function saveProductCategory() {
    var cat_type = document.getElementById("category_master_type").value;
    var data = $("#category_frm_master").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/product-category/store',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                var cat = document.getElementById(cat_type);
                var option = document.createElement("option");
                option.text = obj.name;
                option.value = obj.id;
                option.selected = 1;
                cat.add(option);
                $("#" + cat_type).select2();
                $("#category_frm_master").trigger("reset");
                $("#categoryCloseModal").click();
                $("#category-error-msg").css('display', 'none');
            } else {
                printErrorMsg('category-error-msg', obj.error.original.error);
            }
        },
        error: function (data) {
            obj = JSON.parse(data);
            printErrorMsg('category-error-msg', obj.error.original.error);
        }
    });
    return false;
}

function saveUnitType() {
    //var formValid = document.forms["frm_unit_master"].checkValidity();
    //if(formValid) {
    var master_type = document.getElementById("unit_master_type").value;
    var data = $("#frm_unit_master").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/unit-type/store',
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
                $("#frm_unit_master").trigger("reset");
                $("#unitModalClose").click();
                $("#unit-error-msg").css('display', 'none');
            } else {
                printErrorMsg('unit-error-msg', obj.error.original.error);
            }
        },
        error: function (data) {
            obj = JSON.parse(data);
            printErrorMsg('unit-error-msg', obj.error.original.error);
        }
    });
    return false;
}

function proindex(ind, select_id) {
    $('.productselect').select2("close");
    product_index = ind;
    currect_select_dropdwn_id = select_id;
    document.getElementById("panelWrapId").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
    document.getElementById("panelWrapId").style.transform = "translateX(0%)";
    $('.page-sidebar-wrapper').css('pointer-events', 'none');
}

function saveProduct() {
    //if($("#product_create").validate()) {
    document.getElementById('loader').style.display = 'block';
    var data = $("#product_create").serialize();
    $.ajax({
        type: 'POST',
        url: '/merchant/product/store',
        data: data,
        success: function (data) {
            obj = JSON.parse(data);
            if (obj.status == 1) {
                products = JSON.parse(obj.product_list);
                product = JSON.parse(obj.product_array);
                product_type = obj.product_type;

                $('#product' + currect_select_dropdwn_id).map(function () {
                    //this.append(new Option(obj.product_array.product_id, obj.name));
                    var newOption = new Option(obj.name, product.product_id, true, true);
                    // Append it to the select
                    $(this).append(newOption).trigger('change');
                }).get();

                $('#product' + currect_select_dropdwn_id).each(function (indx, arr) {
                    if (indx == product_index) {
                        $(this).val(product.product_id).trigger('change.select2');
                    }
                });

                try {
                    if (product_type == 'variable') {
                        $('select[name="item[]"]').each(function (indx, arr) {
                            $(this).empty();
                            product_drop = this;
                            $.each(products, function (index, value) {
                                product_drop.append(new Option(value.name, value.name));
                            });

                        });
                    }
                    $('select[name="item[]"]').map(function () {
                        this.append(new Option(obj.name, obj.name));
                    }).get();

                    $('select[name="item[]"]').each(function (indx, arr) {
                        if (indx == product_index) {
                            $(this).val(obj.name);
                            $(this).change();
                        }
                    });
                }
                catch (o) {
                }
                try {
                    $('select[name="sec_item[]"]').map(function () {
                        this.append(new Option(obj.name, obj.name));
                        // $(this).val()
                    }).get();

                    $('select[name="sec_item[]"]').each(function (indx, arr) {
                        if (indx == product_index) {
                            $(this).val(obj.name);
                            $(this).change();
                        }
                    });
                }
                catch (o) { }

                document.getElementById("panelWrapId").style.boxShadow = "none";
                document.getElementById("panelWrapId").style.transform = "translateX(100%)";

                $("#print-error-msg").css('display', 'none');
                $("#product_create").trigger("reset");
            } else {
                printErrorMsg('print-error-msg', obj.error.original.error);
            }
            document.getElementById('loader').style.display = 'none';
        },
        error: function (data) {
            obj = JSON.parse(data);
            printErrorMsg('print-error-msg', obj.error.original.error);
        }
    });
    return false;
}

function printErrorMsg(id, msg) {
    $("#" + id).find("ul").html('');
    $("#" + id).css('display', 'block');
    $.each(msg, function (key, value) {
        $("#" + id).find("ul").append('<li>' + value + '</li>');
    });
}

function closeProductPanel() {
    document.getElementById("panelWrapId").style.boxShadow = "none";
    document.getElementById("panelWrapId").style.transform = "translateX(100%)";
    $("#product_create").trigger("reset");
    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
    return false;
}

function enableService(service_id) {
    $('#confirm').modal("show");
    $('#enableServiceOk').on('click', function (e) {
        $('#confirm').modal("hide");
        document.getElementById('loader').style.display = 'block';
        $.ajax({
            type: 'POST',
            url: '/merchant/profile/activate/' + service_id + '/response',
            success: function (data) {
                obj = JSON.parse(data);
                if (obj.status == 1) {
                    document.getElementById("serviceActivatedMsg").innerHTML = "Service activation request has been sent. Our support team will get in touch with you shortly.";
                    $('#serviceActivated').modal("show");
                    $("#manage_inventory_row").load(location.href + " #manage_inventory_row");
                } else {
                    document.getElementById("serviceActivatedMsg").innerHTML = "Something went wrong ! Please try again";
                    $('#serviceActivated').modal("show");
                }
                document.getElementById('loader').style.display = 'none';
            },
            error: function (data) {
                document.getElementById("serviceActivatedMsg").innerHTML = "Something went wrong ! Please try again";
                $('#serviceActivated').modal("show");
            }
        });
    });
    return false;
}

function setView(product_type) {
    try {
        if (product_type == 'variable') {
            $("#expiry_date_div").hide();
            var old_value = $("#product_name").val();
            if (mode != 'update' && old_value == '') {
                livewire.emit('addRow');
            }
            $("#product_img_div").hide();
            $('.stock_keep_div_simple').hide();
            $('.stock_details_simple').hide();
            $("#manage_inventory_row").hide();
            $("#sku_div").hide();
            $("#sale_po_info").hide();
            $("#variable_product_div").show();
            document.getElementById("sale_price").required = false;
            document.getElementById("available_stock").required = false;
        } else if (product_type == 'simple') {
            $("#expiry_date_div").show();
            livewire.emit('remove');
            $("#product_img_div").show();
            $('.stock_keep_div_simple').show();
            $("#sku_div").show();
            $("#sale_po_info").show();
            $("#variable_product_div").hide();
            $("#manage_inventory_row").show();
            document.getElementById("sale_price").required = true;
            document.getElementById("available_stock").required = true;
        }
    } catch (o) {
        console.log(o)
    }
}

function deleteVariationRow(rowID) {
    if (rowID != '') {
        livewire.emit('remove', rowID);
    } else {
        livewire.emit('remove');
    }
}