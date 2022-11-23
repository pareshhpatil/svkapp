<script src="https://www.swipez.in/js/jquery-1.7.1.min.js"></script>
<script>
    var package_id = 0;

    function setpackageID(id) {
        package_id = id
    }

    function checkPackageExists() {
        redirect_flag = false;
        for (const [key, value] of Object.entries(localStorage)) {
            if (key.includes('slot_title')) {
                redirect_flag = true;
            }
        }

        if (redirect_flag == true) {
            app_url = "{{env('APP_URL')}}";
            payment_request_id = '{{$payment_request_id}}';
            redirect_url = app_url + '/patron/booking/cancellation/' + payment_request_id + '/confirm';
            window.location.href = redirect_url;
        } else {
            alert("please select a package to cancel")
        }

    }

    function fullCancelledclicked(key, seats, slot_name) {
        localStorage.setItem('slot_title' + key, 0);
        document.getElementById('cancelButtons' + key).style.display = 'none';
        document.getElementById('cancelText' + key).style.display = 'block';
    }

    function PartialCancelledclicked(type, seats) {
        document.getElementById('cancelButtons' + type).style.display = 'none';
        document.getElementById('packagebutton' + type).style.display = 'none';
        document.getElementById('quantity_button' + type).style.display = 'flex';

    }

    function backClicked(key) {
        document.getElementById('cancelButtons' + key).style.display = 'none';
        document.getElementById('backButton' + key).style.display = 'none';
        document.getElementById('quantity_button' + key).style.display = 'none';
        document.getElementById('cancelText' + key).style.display = 'none';
        document.getElementById('packagebutton' + key).style.display = 'block';

    }

    function cancelPackage(key, seats) {
        document.getElementById('cancelButtons' + key).style.display = 'block';
        document.getElementById('backButton' + key).style.display = 'block';
        document.getElementById('packagebutton' + key).style.display = 'none';

    }

    $(document).ready(function() {
        $('.quantity_button .num-in span').click(function() {
            var $input = $(this).parents('.num-block').find('input.in-num');
            var max_count = document.getElementById('max_qty' + package_id).value;
            var count = document.getElementById('couter_value' + package_id).value;
            var slot_title = document.getElementById('slot_title' + package_id).value;
            if ($(this).hasClass('minus')) {
                count = parseFloat(count) - 1;
                count = count < 1 ? 0 : count;
                if (count < 2) {
                    $(this).addClass('dis');
                } else {
                    $(this).removeClass('dis');
                }
                document.getElementById('couter_value' + package_id).value = count
            } else {
                count = parseFloat(count) + 1;
                count = count > max_count ? max_count : count;
                if (count > 1) {
                    $(this).parents('.num-block').find(('.minus')).removeClass('dis');
                }
                document.getElementById('couter_value' + package_id).value = count
            }

            localStorage.setItem('slot_title' + package_id, count);
            $input.change();
            return false;
        });
    });
</script>
</body>