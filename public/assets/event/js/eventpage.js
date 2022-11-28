function occurenceSubmit(val, int) {
    try {
        document.getElementById('occurence_id').value = document.getElementById('occurence').value;
    } catch (o) { }
    try {
        document.getElementById('currency').value = document.getElementById('currency_id').value;
    } catch (o) { }
    document.getElementById('div_int').value = int;
    $('#occurence_form').submit();
}

function calculateTotal() {
    document.getElementById('booking_error').innerHTML = '';
    var total_amount = 0;
    var tax_display = '';
    var total_tax = 0;
    var total_qty = 0;
    var total_discount = 0;

    coupon_id = $('#coupon_id').val();
    if (coupon_id > 0) {

    } else {
        coupon_id = '';
    }

    $('input[name="package_id[]"]').each(function () {
        var pkg_id = $(this).val();
        var qty = $('#qty' + pkg_id).val();
        if (qty > 0) {
            var price = $('#price' + pkg_id).val();
            amount = Number(qty) * Number(price);
            total_amount = total_amount + amount;

            if (coupon_id != '') {
                if ($('#pkg_copun' + coupon_id)) {
                    coupoun_pkg_id = $('#pkg_copun' + coupon_id).val();
                    allcoupon_id = $('#all_coupon').val();
                    if (coupoun_pkg_id == pkg_id || allcoupon_id == coupon_id) {
                        c_type = $('#coupon_type').val();
                        c_fixed_amount = $('#coupon_fixed_amount').val();
                        c_percent = $('#coupon_percent').val();
                        if (c_type == 1) {
                            cost_coupon = Number(c_fixed_amount * qty);
                        } else {
                            cost_coupon = Number(amount * c_percent / 100);
                        }
                        amount = amount - cost_coupon;
                        total_discount = total_discount + cost_coupon;
                    }

                }
            }

            var tax = $('#tax' + pkg_id).val();
            currency_icon = document.getElementById('currency_icon').innerHTML;
            if (tax > 0) {
                var pkg_name = $('#pkg_name' + pkg_id).val();
                var tax_text = $('#tax_text' + pkg_id).val();
                tax_amount = amount * Number(tax) / 100;
                total_tax = Number(total_tax) + tax_amount;
                tax_display = tax_display + '<tr><td><small>' + pkg_name + ' (' + tax_text + ' @' + tax + '%)</small></td><td align="right" style="width: 60px;"><small>' + currency_icon + ' ' + tax_amount + '</small></td></tr>';
            }
            total_qty = total_qty + qty;
        }
    });

    if (tax_display != '') {
        document.getElementById('tax_breckup').innerHTML = '<table class="table-sm pull-right" ><tr><td class="text-muted">Tax breakup</td><td align="right"></td></tr>' + tax_display + '</table>';
    } else {
        document.getElementById('tax_breckup').innerHTML = '';
    }

    if (total_discount > 0) {
        document.getElementById('coupon_discount').innerHTML = formatedAmount(total_discount);
        document.getElementById('disctr').style.display = '';
        document.getElementById('coupon_status').innerHTML = $('#coupon_description').val();
    } else {
        document.getElementById('disctr').style.display = 'none';
        tqty = $('#total_qty').val();

        if (coupon_id != '' && tqty > 0) {
            try {
                document.getElementById('coupon_status').innerHTML = 'This coupon is not applicable for selected packages.';
            } catch (o) {

            }
        }
    }


    document.getElementById('total_qty').value = total_qty;
    document.getElementById('base_total').innerHTML = formatedAmount(total_amount);
    document.getElementById('grand_total').innerHTML = formatedAmount(total_amount + total_tax - total_discount);
    document.getElementById('submit_total').value = total_amount;
    document.getElementById('submit_grand_total').value = total_amount + total_tax - total_discount;
    document.getElementById('submit_discount').value = total_discount;
    document.getElementById('submit_tax').value = total_tax;

}

function formatedAmount(amount) {
    return amount.toLocaleString(undefined, { maximumFractionDigits: 2 });
}

function validateCoupon(merchant_id) {
    var coupon_code = document.getElementById('coupon_code').value;
    coupon_code = coupon_code.split(' ').join('SPACE');
    var coupon_id = '';
    try {
        tqty = $('#total_qty').val();
        if (tqty < 1) {
            $('#booking_error').innerHTML = '* Select Quantity';
            return;
        } else {
            $('#booking_error').innerHTML = '';
        }
    } catch (o) {

    }
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
                    document.getElementById('coupon_type').value = obj.type;
                    document.getElementById('coupon_percent').value = obj.percent;
                    document.getElementById('coupon_fixed_amount').value = obj.fixed_amount;
                    document.getElementById('coupon_description').value = 'Your coupon discount has been applied<br>' + obj.descreption;
                    try {
                        document.getElementById('coupon_status').innerHTML = 'Your coupon discount has been applied<br>' + obj.descreption;
                    } catch (o) {
                    }
                    calculateTotal();
                    return false;
                }
            }
        });
    } catch (o) {
    }
    calculateTotal();
    return false;
}

function validateBooking() {
    tqty = $('#total_qty').val();
    if (tqty > 0) {
        return true;
    } else {
        document.getElementById('booking_error').innerHTML = '* Select Quantity';
        return false;
    }
    return false;
}