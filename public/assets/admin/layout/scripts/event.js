var category_name = "";
var numherder = 1;
var removecount = 0;
var removetaxcount = 0;
var numbers = /^-?[0-9]\d*(\.\d+)?$/;
var disablecopytitle = 0;
var disablecopydescription = 0;
var structure_col_json = "";
var payee_mandatory = '';
function removedivexist(id) {
    var ab = "exist" + id;

    var elem = document.getElementById(ab);
    elem.parentNode.removeChild(elem);
}

function setcopy(type) {
    if (type == "title") {
        if (disablecopytitle == 0) {
            _("altitle").value = _("title").value;
        }
    } else {
        if (disablecopydescription == 0) {
            var plainText = $($("#summernote").summernote("code")).text();
            _("shortdesc").innerHTML = plainText.substr(0, 300);
        }
    }
}

function setsummercopy() {
    if (disablecopydescription == 0) {
        var plainText = $($("#summernote").code()).text();
        _("shortdesc").innerHTML = plainText;
    }
}

function disablecopy(type) {
    if (type == "title") {
        disablecopytitle = 1;
    } else {
        disablecopydescription = 1;
    }
}

function removepackage(int) {
    try {
        _("package" + int).remove();
    } catch (o) {
    }

    try {
        _("exist" + int).remove();
    } catch (o) {
    }

    _("clgpkg").click();
}

function setdeletepackage(int) {
    _("dpkg").click();
    $("#deletepackage").attr("onclick", "removepackage(" + int + ");");
}
function addevent() {
    numherder++;
    while (document.getElementById("exist" + numherder)) {
        numherder = numherder + 1;
    }

    var mainDiv = document.getElementById("newevent");
    var newDiv = document.createElement("div");
    var newSpan = document.createElement("div");
    newSpan.setAttribute("id", "exist" + numherder);
    newSpan.setAttribute("class", "form-group");
    newSpan.innerHTML =
        '<input type="hidden" name="position[]" value="-1" /><input type="hidden" name="is_mandatory[]" value="2" /> <input type="hidden" name="datatype[]" value="text" /><div class="col-md-1"></div><div class="col-md-3"><input name="column[]" class="form-control form-control-inline " type="text" required placeholder="column name" value=""/></div><div class="col-md-6"><div class="input-group"><input name="columnvalue[]" style="z-index:1;" required class="form-control form-control-inline" type="text" value=""/><span class="input-group-addon " id="' +
        numherder +
        '" onclick="removedivexist(this.id)"><i class="fa fa-minus-circle"></i></span></div> <span class="help-block"></span></div>';
    mainDiv.appendChild(newSpan);
}

function addeventpackage() {
    numherder++;
    while (document.getElementById("exist" + numherder)) {
        numherder = numherder + 1;
    }

    var dateradio = getdateradio(numherder, "");
    bookunit = _("booking_unit").value;
    var category_list = _("firstcatdiv").innerHTML;
    var category_list = category_list.replace("cat1d", "cat" + numherder + "d");
    var category_list = category_list.replace(
        "ecategory_name[]",
        "category_name[]"
    );
    var mainDiv = document.getElementById("newpackage");
    var newDiv = document.createElement("div");
    var newSpan = document.createElement("div");
    newSpan.setAttribute("id", "exist" + numherder);
    newSpan.setAttribute("class", "form-group");
    var coupon = document.getElementById("coupon").innerHTML;
    var coupon = coupon.replace('1d"', numherder + 'd"');
    price_div = '';
    flexi_div = '';

    try {
        $("#currency :selected").map(function (i, el) {
            currency = $(el).val();
            price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price in (' + currency + ') <span class="required">* </span></label><div class="col-md-3"> <input name="unitcost[]" max="100000" required id="price' +
                numherder +
                'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="package_currency[]" /> <span class="help-block"></span></div></div></div>';
            flexi_div += '<div class="form-group" ><label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + numherder + 'd" name="min_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + numherder + 'd" name="max_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div></div>';

        }).get();
    } catch (o) {
        currency = 'INR';
        price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price in (' + currency + ') <span class="required">* </span></label><div class="col-md-3"> <input name="unitcost[]" max="100000" required id="price' +
            numherder +
            'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="package_currency[]" /> <span class="help-block"></span></div></div></div>';
        flexi_div += '<div class="form-group" ><label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + numherder + 'd" name="min_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + numherder + 'd" name="max_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div></div>';

    }



    //newSpan.innerHTML = '<div class="portlet box blue"><div class="portlet-title"><div class="caption">Package</div><div class="tools"><a href="javascript:;" class="collapse" data-original-title="" title=""></a><a href="javascript:;" class="remove" data-original-title="" title=""></a></div></div><div class="portlet-body"><div class="form-group"><label class="col-md-4 control-label">Package name <span class="required">* </span></label><div class="col-md-6"><input type="text" name="package_name[]" required class="form-control form-control-inline"><span class="help-block"></span></div></div><div class="form-group"><label class="col-md-4 control-label">Package description <span class="required">* </span></label><div class="col-md-6"><input type="text" name="package_description[]" required class="form-control form-control-inline"><span class="help-block"></span></div></div><div class="form-group"><label class="col-md-4 control-label">Available bookings <span class="required">* </span></label><div class="col-md-3"><input class="form-control form-control-inline" type="text" name="unitavailable[]" value="0"/><span class="help-block"></span></div>(Keep 0 for unlimited bookings)</div><div class="form-group"><label class="col-md-4 control-label">Min booking <span class="required">* </span></label><div class="col-md-2"><input type="number" onchange="validatemax(' + "'" + 'min_seat' + numherder + '' + "'" + ',' + "'" + 'max_seat' + numherder + '' + "'" + ');" name="min_seat[]" id="min_seat' + numherder + '" min="1" value="1" required class="form-control form-control-inline"><span class="help-block"></span></div><label class="col-md-2 control-label">Max booking <span class="required">* </span></label><div class="col-md-2"><input type="number" name="max_seat[]" id="max_seat' + numherder + '" value="1"  min="1" required class="form-control form-control-inline"><span class="help-block"></span></div></div><div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-8"><label class="control-label" ><input type="checkbox" id="is_flexible' + numherder + '" name="is_flexible[]" onchange="flexible(' + numherder + ');" value="1"  data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">Amount flexible</label><span class="help-block"></span></div></div><div class="form-group" id="flixible_div' + numherder + '" style="display: none;"><label class="col-md-4 control-label">Min amount <span class="required">* </span></label><div class="col-md-2"><input type="text" id="min_amount' + numherder + '" name="min_price[]" value="" class="form-control form-control-inline"><span class="help-block"></span></div><label class="col-md-2 control-label">Max amount <span class="required">* </span></label><div class="col-md-2"><input type="text" id="max_amount' + numherder + '" name="max_price[]" value="" class="form-control form-control-inline"><span class="help-block"></span></div></div><div id="nonflixible_div' + numherder + '"><div class="form-group"><label class="col-md-4 control-label">Price <span class="required">* </span></label><div class="col-md-3"> <input name="unitcost[]" id="unitcost' + numherder + '" required  class="form-control form-control-inline" type="text" value=""/><span class="help-block"></span></div><div class="col-md-1"><label class="control-label">Coupon</label></div><div class="col-md-2">' + coupon + '</div></div></div></div></div>';
    newSpan.innerHTML =
        '<div  id="package' +
        numherder +
        '"><h4 class="form-section"> Package&nbsp; <a class="btn btn-xs red pull-right" onclick="setdeletepackage(' +
        numherder +
        ');" title="Delete package"><i class="fa fa-remove"></i></a> <a hhref="javascript:;" onclick="clonepackage(' +
        numherder +
        ');"  class="btn btn-xs green pull-right">  <i class="fa fa-copy"></i> Clone </a> <a hhref="javascript:;" onclick="addeventpackage();"  class="btn btn-xs green pull-right">  <i class="fa fa-plus"></i> Add </a>  \n\
            </h4><div id="exist' +
        numherder +
        '"> <div class="form-group">  <label class="col-md-4 control-label">Category name <span class="required"> </span></label>  <div class="col-md-6">' +
        category_list +
        '  </div>  <div class="col-md-1 no-margin no-padding"><a title="Add new" href="#basic" data-toggle="modal" class="btn btn-xs green"><i class="fa fa-plus"></i> Add new </a>  </div> </div> <div class="form-group">  <label class="col-md-4 control-label">Package name <span class="required">* </span></label>  <div class="col-md-6"><input type="text" name="package_name[]"  maxlength="250" id="pkg' +
        numherder +
        'd" required class="form-control form-control-inline"><span class="help-block"></span>  </div> </div> <div class="form-group">  <label class="col-md-4 control-label">Package description <span class="required">* </span></label>  <div class="col-md-6"><textarea name="package_description[]" id="desc' +
        numherder +
        'd" required class="form-control form-control-inline"></textarea><span class="help-block"></span>  </div> </div> <div class="form-group">  <label class="col-md-4 control-label">Available <span class="bookunit">' +
        bookunit +
        '</span> <span class="required">* </span></label>  <div class="col-md-3"><input class="form-control form-control-inline" id="avqty' +
        numherder +
        'd" required min="0" type="number" name="unitavailable[]" maxlength="6"  value="0"/><span class="help-block"></span>  </div>  (Keep 0 for unlimited Bookings) </div> <div class="form-group">  <label class="col-md-4 control-label">Min <span class="bookunit">' +
        bookunit +
        '</span> <span class="required">* </span></label>  <div class="col-md-2"><input type="number" min="1" id="minqty' +
        numherder +
        'd" onchange="validatemax(' +
        "'" +
        "minqty" +
        numherder +
        "'d'" +
        ",'maxqty'" +
        numherder +
        "d" +
        ');" name="min_seat[]" maxlength="3" max="100" value="1" required class="form-control form-control-inline"><span class="help-block"></span>  </div>  <label class="col-md-2 control-label">Max <span class="bookunit">' +
        bookunit +
        '</span> <span class="required">* </span></label>  <div class="col-md-2"><input type="number" min="1" id="maxqty' +
        numherder +
        'd" name="max_seat[]" maxlength="3" max="100" value="1" required class="form-control form-control-inline"><span class="help-block"></span>  </div> </div> <div class="form-group">  <label class="col-md-4 control-label">Pricing Type <span class="popovers" data-container="body" data-trigger="hover" data-content="Change this to Flexible, if you would like your audience to decide how much they want to pay to attend the event. Mostly, applicable if you are creating an event for a NGO or an event to collect donations for a cause. If you change this to Flexible you will be provided two boxes to enter the Min amount & Max amount. Your users can pay any amount between the minimum and maximum amounts entered by you." data-original-title="" title=""><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>  <div class="col-md-2"><select class="form-control" onchange="flexible(' +
        numherder +
        ');" name="is_flexible[]" id="is_flexible' +
        numherder +
        '" data-placeholder="Pricing type"><option value="0">Standard</option><option value="1">Flexible</option><option value="2">Free</option></select><span class="help-block"></span></div> </div> <div class="form-group" id="flixible_div' +
        numherder +
        '" style="display: none;"> ' + flexi_div + '  </div> <div id="nonflixible_div' +
        numherder +
        '">  <div  id="price_div' + numherder + '">' + price_div + '<div class="form-group"><label class="col-md-4 control-label">Coupon</label><div class="col-md-2">' +
        coupon +
        '</div>  </div><div class="form-group"><label class="col-md-4 control-label">Tax (if applicable)</label><div class="col-md-3"><input name="tax_text[]" maxlength="45"  id="taxtext' +
        numherder +
        'd" placeholder="Tax label"  class="form-control form-control-inline"  type="text" value="GST"/><span class="help-block"></span></div><div class="col-md-3"><input name="tax[]" maxlength="6" max="100" id="tax' +
        numherder +
        'd"  step="0.01" min="0" max="100" class="form-control form-control-inline" type="number" value=""/><span class="help-block"></span></div>%</div></div><div class="form-group"><label class="col-md-4 control-label">Package type</label><div class="col-md-8"><label class="control-label"> <input type="radio" checked class="md-radiobtn" onchange="changePackageType(' +
        numherder +
        ', this.value);" name="package_type' +
        numherder +
        '[]" value="1">Normal Pass</label><br><label class="control-label"><input type="radio" class="md-radiobtn" onchange="changePackageType(' +
        numherder +
        ', this.value);" name="package_type' +
        numherder +
        '[]" value="2">Season Pass </label><span class="help-block"></span></div><input type="hidden" name="package_int[]" value="' +
        numherder +
        '"></div><div class="form-group" id="multidatediv' +
        numherder +
        'd"><label class="control-label col-md-4">Choose available date <span class="required"> </span></label><div class="col-md-8" id="multidate' +
        numherder +
        'd" style="height: 80px; overflow: auto; width: 300px;">' +
        dateradio +
        "</div></div></div>  </div></div>";
    mainDiv.appendChild(newSpan);
    var $target = $("html,body");
    var trget = $target.height() - 1300;
    $target.animate({ scrollTop: trget }, 500);



}

function occurenceSubmit() {
    try {
        document.getElementById('occurence_id').value = document.getElementById('occurence').value;
    } catch (o) { }
    try {
        document.getElementById('currency').value = document.getElementById('currency_id').value;
    } catch (o) { }
    $('#occurence_form').submit();
}

function currencyChange() {

    num = 1;
    $('input[name="eunitcost[]"]').each(function () {
        price_div = '';
        flexi_div = '';
        try {
            $("#currency :selected").map(function (i, el) {
                currency = $(el).val();
                price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price in (' + currency + ') <span class="required">* </span></label><div class="col-md-3"> <input name="eunitcost[]" max="100000" required id="price' +
                    num +
                    'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="epackage_currency[]" /> <span class="help-block"></span></div></div></div>';

                flexi_div += '<label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + num + 'd" name="emin_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + num + 'd" name="emax_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div>';

            }).get();
        } catch (o) {
        }

        if (price_div == '') {
            currency = 'INR';
            price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price in (' + currency + ') <span class="required">* </span></label><div class="col-md-3"> <input name="eunitcost[]" max="100000" required id="price' +
                num +
                'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="epackage_currency[]" /> <span class="help-block"></span></div></div></div>';
            flexi_div += '<label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + num + 'd" name="emin_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + num + 'd" name="emax_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div>';

        }

        document.getElementById('price_div' + num).innerHTML = price_div;
        num = num + 1;
    });
    $('input[name="unitcost[]"]').each(function () {
        price_div = '';
        flexi_div = '';
        try {
            $("#currency :selected").map(function (i, el) {
                currency = $(el).val();
                price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price in (' + currency + ') <span class="required">* </span></label><div class="col-md-3"> <input name="unitcost[]" max="100000" required id="price' +
                    num +
                    'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="package_currency[]" /> <span class="help-block"></span></div></div></div>';

                flexi_div += '<label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + num + 'd" name="min_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + num + 'd" name="max_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div>';

            }).get();
        } catch (o) {
        }
        if (price_div == '') {
            currency = 'INR';
            price_div += '<div class="form-group" ><label class="col-md-4 control-label">Price <span class="required">* </span></label><div class="col-md-3"> <input name="unitcost[]" max="100000" required id="price' +
                num +
                'd" class="form-control form-control-inline" step="0.01" type="number" /><input type="hidden" value="' + currency + '" name="package_currency[]" /> <span class="help-block"></span></div></div></div>';
            flexi_div += '<label class="col-md-4 control-label">Min amount (' + currency + ') <span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="minpr' + num + 'd" name="min_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<label class="col-md-2 control-label">Max amount (' + currency + ')<span class="required">* </span></label>	<div class="col-md-2">		<input step="0.01" type="number" id="maxpr' + num + 'd" name="max_price[]" maxlength="12" class="form-control form-control-inline" required="">		<span class="help-block">		</span>	</div>	<div class="col-md-2">	</div>';

        }

        document.getElementById('price_div' + num).innerHTML = price_div;
        document.getElementById('flixible_div' + num).innerHTML = flexi_div;
        num = num + 1;
    });
}

function clonepackage(id) {
    numherder++;
    while (document.getElementById("exist" + numherder)) {
        numherder = numherder + 1;
    }
    var mainDiv = document.getElementById("newpackage");
    var newDiv = document.createElement("div");
    var newSpan = document.createElement("div");
    newSpan.setAttribute("id", "exist" + numherder);
    newSpan.setAttribute("class", "form-group");
    var coupon = document.getElementById("coupon").innerHTML;
    var clonediv =
        '<div  id="package' +
        numherder +
        '">' +
        document.getElementById("package" + id).innerHTML +
        "</div>";

    for (i = 0; i < 20; i++) {
        var clonediv = clonediv.replace(id + 'd"', numherder + 'd"');
    }

    var clonediv = clonediv.replace(
        'id="is_flexible' + id + '"',
        'id="is_flexible' + numherder + '"'
    );
    var clonediv = clonediv.replace(
        "clonepackage(" + "'" + id + "'" + ");",
        "clonepackage(" + "'" + numherder + "'" + ");"
    );
    var clonediv = clonediv.replace(
        '<h4 class="form-section">Package',
        '<h4 class="form-section">Package<a class="btn btn-xs red pull-right" onclick="setdeletepackage('+numherder+');" title="Delete package"><i class="fa fa-remove"></i></a>'
    );

    var clonediv = clonediv.replace(
        "flexible(" + id + ");",
        "flexible(" + numherder + ");"
    );
    var clonediv = clonediv.replace(
        'id="flixible_div' + id + '"',
        'id="flixible_div' + numherder + '"'
    );
    var clonediv = clonediv.replace(
        'id="nonflixible_div' + id + '"',
        'id="nonflixible_div' + numherder + '"'
    );
    var clonediv = clonediv.replace(
        'name="package_int[]" value="' + id + '"',
        'name="package_int[]" value="' + numherder + '"'
    );
    var clonediv = clonediv.replace(
        "changePackageType(" + id + ", this.value);",
        "changePackageType(" + numherder + ", this.value);"
    );
    var clonediv = clonediv.replace(
        "changePackageType(" + id + ", this.value);",
        "changePackageType(" + numherder + ", this.value);"
    );
    var clonediv = clonediv.replace(
        "package_type" + id + "[]",
        "package_type" + numherder + "[]"
    );
    var clonediv = clonediv.replace(
        "price_div" + id,
        "price_div" + numherder
    );
    var clonediv = clonediv.replace(
        "package_type" + id + "[]",
        "package_type" + numherder + "[]"
    );
    var clonediv = clonediv.replace('id="coupon"', "");
    if (
        clonediv.indexOf(
            '<a href="javascript:;" class="remove" data-original-title="" title=""></a>'
        ) > -1
    ) {
    } else {
        var clonediv = clonediv.replace(
            '<a href="javascript:;" class="collapse" data-original-title="" title=""></a>',
            '<a href="javascript:;" class="collapse" data-original-title="" title=""></a><a href="javascript:;" class="remove" data-original-title="" title=""></a>'
        );
    }
    var clonediv = clonediv.replace(
        "validatemax(" +
        "'" +
        "minqty" +
        id +
        "d" +
        "'" +
        "," +
        "'" +
        "maxqty" +
        id +
        "d" +
        "'" +
        ");",
        "validatemax(" +
        "'" +
        "minqty" +
        numherder +
        "d" +
        "'" +
        "," +
        "'" +
        "maxqty" +
        numherder +
        "d" +
        "'" +
        ");"
    );

    newSpan.innerHTML = clonediv;
    mainDiv.appendChild(newSpan);
    var $target = $("html,body");
    var trget = $target.height() - 1300;

    flexi = $("#is_flexible" + id).val();
    $("#is_flexible" + numherder).val(flexi);

    _("cat" + numherder + "d").value = _("cat" + id + "d").value;
    _("pkg" + numherder + "d").value = _("pkg" + id + "d").value;
    _("desc" + numherder + "d").value = _("desc" + id + "d").value;
    _("avqty" + numherder + "d").value = _("avqty" + id + "d").value;
    _("minqty" + numherder + "d").value = _("minqty" + id + "d").value;
    _("maxqty" + numherder + "d").value = _("maxqty" + id + "d").value;
    _("taxtext" + numherder + "d").value = _("taxtext" + id + "d").value;
    _("tax" + numherder + "d").value = _("tax" + id + "d").value;
    if (_("is_flexible" + numherder).checked == 1) {
        _("minpr" + numherder + "d").value = _("minpr" + id + "d").value;
        _("maxpr" + numherder + "d").value = _("maxpr" + id + "d").value;
    } else {
        _("price" + numherder + "d").value = _("price" + id + "d").value;
        try {
            _("coup" + numherder + "d").value = _("coup" + id + "d").value;
        } catch (o) {
        }
    }
    _("is_flexible" + numherder).checked = _("is_flexible" + id).checked;

    _("multidate" + numherder + "d").innerHTML = "";
    multi_div = "";

    $('input[name="multi_occurence' + id + '[]"]').each(function () {
        valuee = $(this).val();
        if (this.checked) {
            multi_div =
                multi_div +
                '<label class="control-label" style="margin-right:10px;">\n\
<input type="checkbox" checked="" name="multi_occurence' +
                numherder +
                '[]" value="' +
                valuee +
                '">' +
                valuee +
                "</label>";
        } else {
            multi_div =
                multi_div +
                '<label class="control-label" style="margin-right:10px;">\n\
<input type="checkbox" name="multi_occurence' +
                numherder +
                '[]" value="' +
                valuee +
                '">' +
                valuee +
                "</label>";
        }
    });
    _("multidate" + numherder + "d").innerHTML = multi_div;

    if ($('input[name="package_type' + id + '[]"]:checked').val() == 1) {
        _("multidatediv" + numherder + "d").style.display = "block";
        $('input[name="package_type' + numherder + '[]"][value="1"]').prop(
            "checked",
            true
        );
    } else {
        _("multidatediv" + numherder + "d").style.display = "none";
        $('input[name="package_type' + numherder + '[]"][value="2"]').prop(
            "checked",
            true
        );
    }

    $target.animate({ scrollTop: trget }, 500);
}

function _(el) {
    return document.getElementById(el);
}

function saveCategory(type) {
    var cat_name = document.getElementById("category_name").value;
    $('select[name="category_name[]"]').each(function () {
        var ex = $(this)
            .find('option[value="' + cat_name + '"]')
            .index();
        if (ex == -1) {
            $(this).append(
                '<option value="' + cat_name + '">' + cat_name + "</option>"
            );
        }
    });
    if (type == 1) {
        $('select[name="ecategory_name[]"]').each(function () {
            var ex = $(this)
                .find('option[value="' + cat_name + '"]')
                .index();
            if (ex == -1) {
                $(this).append(
                    '<option value="' + cat_name + '">' + cat_name + "</option>"
                );
            }
        });
    }
    document.getElementById("category_name").value = "";
    $("#close").click();
}

function validatemax(min_id, max_id) {
    minval = document.getElementById(min_id).value;
    $("#" + max_id).attr("min", minval);
}

function unitChange(value) {
    $(".bookunit").html(value);
}

function flexible(id) {
    if ($("#is_flexible" + id).val() == 1) {
        $("#flixible_div" + id)
            .slideDown(500)
            .fadeIn();
        $("#nonflixible_div" + id)
            .slideDown(500)
            .fadeOut();
        $("#price" + id + "d").prop("required", false);
        document.getElementById("price" + id + "d").value = "";
        $('input[name="unitcost[]"]').each(function () {
            $(this).val('0.00');
        });
    } else if ($("#is_flexible" + id).val() == 2) {
        $("#flixible_div" + id)
            .slideUp(500)
            .fadeOut();
        $("#nonflixible_div" + id)
            .slideDown(500)
            .fadeOut();
        $('input[name="unitcost[]"]').each(function () {
            $(this).val('0.00');
        });
        $('input[name="min_price[]"]').each(function () {
            $(this).val('0.00');
        });
        $('input[name="max_price[]"]').each(function () {
            $(this).val('0.00');
        });
    } else {
        $("#nonflixible_div" + id)
            .slideUp(500)
            .fadeIn();
        $("#flixible_div" + id)
            .slideUp(500)
            .fadeOut();

        $('input[name="min_price[]"]').each(function () {
            $(this).val('0.00');
        });
        $('input[name="max_price[]"]').each(function () {
            $(this).val('0.00');
        });
        $("#price" + id + "d").prop("required", true);
    }




}

function multidate() {
    $(".demo .code")
        .each(function () {
            eval(
                $(this)
                    .attr("title", "NEW: edit this code and test it!")
                    .text()
            );
            this.contentEditable = true;
        })
        .focus(function () { });
}

function showdate(id) {
    var text = "";
    if (id > 0) {
        _("occurence_div").style.display = "block";
    } else {
        _("occurence_div").style.display = "none";
    }
    for (i = 0; i < id; i++) {
        text +=
            '<tr><td><input class="form-control form-control-inline input-sm date-picker"  onchange="setOccurenceDate(this);" type="text" required  value="" name="from_date[]" autocomplete="off" data-date-format="dd M yyyy" placeholder="Start date"/></td><td style="padding-left: 5px;">\n\
<input type="text" name="from_time[]"  onchange="setOccurenceDate(this);" required  class="form-control form-control-inline input-sm timepicker timepicker-no-seconds"></td>\n\
<td style="padding-left: 5px;"><input class="form-control form-control-inline input-sm date-picker" onchange="setOccurenceDate(this);"  type="text" required  value="" name="to_date[]" autocomplete="off" data-date-format="dd M yyyy" placeholder="End date"/></td>\n\
<td style="padding-left: 5px;"><input  type="text" onchange="setOccurenceDate(this);"  name="to_time[]" required  class="form-control form-control-inline input-sm timepicker timepicker-no-seconds"></td></tr>';
    }
    _("occurence_date").innerHTML = text;
    setdatepicker();
    settimepicker();
}

function setdatepicker() {
    $(".date-picker").datepicker({
        rtl: Swipez.isRTL(),
        orientation: "left",
        autoclose: true,
        todayHighlight: true
    });
}

function settimepicker() {
    $(".timepicker").timepicker();
}

function setOccurenceDate(key) {
    try {
        if (key.name == 'from_date[]') {
            ind = $('input[name="from_date[]"]').index(key);
            to_date = $('input[name="to_date[]"]')[ind].value;
            if (to_date == '') {
                $('input[name="to_date[]"]')[ind].value = $('input[name="from_date[]"]')[ind].value;
            }
        }

        if (key.name == 'from_time[]') {
            occ = Number($('input[name="from_time[]"]').length);
            ind = $('input[name="from_time[]"]').index(key);
            val = $('input[name="from_time[]"]')[ind].value;
            for (i = ind + 1; i <= occ; ++i) {
                from_date = $('input[name="from_date[]"]')[i].value;
                if (from_date == '') {
                    $('input[name="from_time[]"]')[i].value = val;
                }
            }
        }

        if (key.name == 'to_time[]') {
            occ = Number($('input[name="to_time[]"]').length);
            ind = $('input[name="to_time[]"]').index(key);
            val = $('input[name="to_time[]"]')[ind].value;
            for (i = ind + 1; i <= occ; ++i) {
                to_date = $('input[name="to_date[]"]')[i].value;
                if (to_date == '') {
                    $('input[name="to_time[]"]')[i].value = val;
                }
            }
        }
    } catch (o) { }
    var total_package = Number(numherder) + 1;

    for (i = 1; i < total_package; ++i) {
        if (document.getElementById("emultidate" + i + "d")) {
            dateradio = getdateradio(i, "e");
            _("emultidate" + i + "d").innerHTML = dateradio;
        }
        // do something with `substr[i]`
    }

    for (i = 1; i < total_package; ++i) {
        if (document.getElementById("multidate" + i + "d")) {
            dateradio = getdateradio(i, "");
            _("multidate" + i + "d").innerHTML = dateradio;
        }
        // do something with `substr[i]`
    }
}

function getdateradio(int, type) {
    var f_date = document.getElementsByName("from_date[]");
    var f_time = document.getElementsByName("from_time[]");
    var t_date = document.getElementsByName("to_date[]");
    var t_time = document.getElementsByName("to_time[]");
    var dateradio = "";
    for (var o = 0; o < f_date.length; o++) {
        var fd = f_date[o];
        var ft = f_time[o];
        var td = t_date[o];
        var tt = t_time[o];
        if (fd.value != "") {
            start_datetime = fd.value + " " + ft.value;
            if (fd.value == td.value) {
                item = fd.value + " " + ft.value;
            } else {
                item = fd.value + " " + ft.value;
            }
            dateradio =
                dateradio +
                '<label class="control-label" style="margin-right:10px;">\n\
     <input type="checkbox" checked name="' +
                type +
                "multi_occurence" +
                int +
                '[]" value="' +
                start_datetime +
                '">' +
                item +
                "</label>";
        }
    }
    return dateradio;
}

function changePackageType(id, value) {
    if (value == 1) {
        _("multidatediv" + id + "d").style.display = "block";
    } else {
        _("multidatediv" + id + "d").style.display = "none";
    }
}
function is_details_capture(val, id) {
    if (val == 1) {
        document.getElementById(id + "_name").value = 1;
        try {
            document.getElementById(id).style.display = "block";
        } catch (o) {
        }
    } else {
        document.getElementById(id + "_name").value = 0;
        try {
            document.getElementById(id).style.display = "none";
        } catch (o) {
        }
    }
}

function setStructure(type, position) {
    var struct_table = "";
    obj = JSON.parse(structure_col_json);
    $.each(obj, function (index, value) {
        var col_name = value.name;
        var datatype = value.datatype;
        var name = value.column_name;
        if (document.getElementById(type + "_" + col_name)) {
        } else {
            struct_table =
                struct_table +
                '<tr><td class="td-c">' +
                value.column_name + '</td><td class="td-c"><input id="' + type + 'selcheck_' + "'" + col_name + "'" + '" type="checkbox" onchange="AddStructureField(' + "'" + type + "','" + value.name + "','" + value.column_name + "','" + value.datatype + "','" + position + "'" + ', this.checked);"/><span style="display:none;" id="json_' + type + position + col_name + '">' + JSON.stringify(value) + '</span></td><td class="td-c"><input id="' + type + 'mandcheck_' + "'" + col_name + "'" + '" type="checkbox"  onchange="AddMandatoryField(' + "'" +
                type + "','" + position + "','" + col_name + "'" + ', this.checked);"/></td></tr>';
        }
    });
    _("structure_col").innerHTML = struct_table;
}

function AddMandatoryField(type, position, name, check) {
    if (document.getElementById(type + position + 'm_' + name)) {
        jsons = _(type + position + 'f_' + name).innerHTML;
        if (check == true) {
            _(type + 'req_' + name).innerHTML = '*';
            _(type + position + 'm_' + name).value = '1';
            jsons = jsons.replace('"mandatory":0', '"mandatory":1');
        } else {
            _(type + 'req_' + name).innerHTML = '';
            _(type + position + 'm_' + name).value = '0';
            jsons = jsons.replace('"mandatory":1', '"mandatory":0');
        }
        _(type + position + 'f_' + name).innerHTML = jsons;
    }
}

function AddStructureField(type, name, col_name, datatype, position, check) {
    if (check == true) {
        var mainDiv = document.getElementById('div_' + type + position);
        var newSpan = document.createElement('div');
        if (datatype == 'textarea') {
            textbox = '<textarea readonly id="' + type + '_' + name + '" class="form-control input-sm form-control-inline"></textarea>';
        } else {
            textbox = '<input type="text" id="' + type + '_' + name + '" readonly class="form-control form-control-inline">';
        }
        jsons = _('json_' + type + position + name).innerHTML;
        mandatory = '';
        is_mandatory = 0;
        if ($('#' + type + 'mandcheck_' + name).is(':checked')) {
            mandatory = '*';
            is_mandatory = 1;
            jsons = jsons.replace('"mandatory":0', '"mandatory":1');
        } else {
            jsons = jsons.replace('"mandatory":1', '"mandatory":0');
        }

        newSpan.setAttribute('id', 'exist' + type + name);
        newSpan.setAttribute('class', 'form-group');
        newSpan.innerHTML = '<label class="col-md-4 control-label">' + col_name + ' <span id="' + type + 'req_' + name + '" class="required">' + mandatory + '</span></label>	<div class="col-md-6">	<div class="input-group">' + textbox + '<textarea style="display:none;" id="' + type + position + 'f_' + name + '" name="' + type + position + '[]" >' + jsons + '</textarea>		<input type="hidden" id="' + type + position + 'm_' + name + '" value="' + is_mandatory + '">		<span class="input-group-addon " onclick="editMandatory(' + "'" + type + "','" + name + "','" + position + "'" + ')"><a data-toggle="modal"  href="#mandatory">   <i class="fa fa-edit"></i></a>		</span>		<span class="input-group-addon " onclick="removedivexist(' + "'" + type + name + "'" + ')"><i class="fa fa-minus-circle"></i>		</span>		</div></div>';
        mainDiv.appendChild(newSpan);
    } else {
        removedivexist(type + name);
    }
}

function addOccurence() {
    var mainDiv = document.getElementById('occurence_date');
    var newDiv = document.createElement('tr');
    newDiv.innerHTML = '<td><input class="form-control form-control-inline input-sm date-picker" autocomplete="off" onchange="setOccurenceDate(this);" type="text" required  value="" name="from_date[]" autocomplete="off" data-date-format="dd M yyyy" placeholder="Start date"/></td><td style="padding-left: 5px;">\n\
    <input type="text" name="from_time[]"  onchange="setOccurenceDate(this);" required  class="form-control form-control-inline input-sm timepicker timepicker-no-seconds"></td>\n\
    <td style="padding-left: 5px;"><input class="form-control form-control-inline input-sm date-picker" onchange="setOccurenceDate(this);"  type="text" required  value="" autocomplete="off" name="to_date[]"  data-date-format="dd M yyyy" placeholder="End date"/></td>\n\
    <td style="padding-left: 5px;"><input  type="text" onchange="setOccurenceDate(this);"  name="to_time[]" required  class="form-control form-control-inline input-sm timepicker timepicker-no-seconds"></td><td style="padding-left: 5px;"><a href="javascript:;"  onclick="$(this).closest(' + "'tr'" + ').remove();setOccurenceDate();setOccurenceCount();" class="btn btn-xs red"><i class="fa fa-times"></i></a></td>';
    mainDiv.appendChild(newDiv);
    setdatepicker();
    settimepicker();
    setOccurenceCount();
}


function editMandatory(type, name, position) {
    selected = _(type + position + 'm_' + name).value;
    $('#selectmand').val(selected);
    _('hid_type').value = type;
    _('hid_name').value = name;
    _('hid_position').value = position;
}

function validatefield(mandatory, name, validation, display_name) {
    var is_error = 0;
    var reg = '';
    value = _('payeeid_' + name).value;
    if (mandatory == 1 && value == '') {
        is_error = 1;
    }
    if (validation == 'email') {
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    } else if (validation == 'mobile') {
        var reg = /^(\+[\d]{1,5}|0)?[1-9]\d{9}$/;
    }
    if (reg != '') {
        if (reg.test(value) == false) {
            is_error = 1;
        }
    }

    if (is_error == 1) {
        _('error_' + name).innerHTML = 'Enter your valid ' + display_name;
        return false;
    } else {
        _('error_' + name).innerHTML = '';
        return true;
    }



}

function validatePayee() {
    var is_valid = true;
    obj = JSON.parse(payee_mandatory);
    $.each(obj, function (index, value) {
        result = validatefield(value.mandatory, value.name, value.datatype, value.column_name);
        if (result == false) {
            is_valid = false;
        }
    });
    if (is_valid == true) {
        _('payee_div').className = 'form-body hidden';
        _('attendee_div').className = 'row';
        _('pay_div').className = '';
    }

}

function showPayee() {
    _('payee_div').className = 'form-body';
    _('attendee_div').className = 'row hidden';
    _('pay_div').className = 'hidden';
}

function chooseMandatory(val) {
    type = _('hid_type').value;
    name = _('hid_name').value;
    position = _('hid_position').value;
    AddMandatoryField(type, position, name, val);
}

function sameaspayee(check) {
    obj = JSON.parse(payee_mandatory);
    $.each(obj, function (index, value) {
        try {
            if (_('attendeeid_' + value.name)) {
                if (check == true) {
                    _('attendeeid_' + value.name).value = _('payeeid_' + value.name).value;
                } else {
                    _('attendeeid_' + value.name).value = '';
                }
            }
        } catch (o) {
        }
    });

}
