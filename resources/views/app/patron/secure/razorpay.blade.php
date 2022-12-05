@if(isset($global_tag))
{{$global_tag}}
@endif

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="{{$data['return_url']}}" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    <input type="hidden" name="order_id" value="{{$data['order_id']}}">
    <input type="hidden" name="transaction_id" value="{{$data['transaction_id']}}">
    <input type="hidden" name="name" value="{{$data['name']}}">
    <input type="hidden" name="email" value="{{$data['email']}}">
    <input type="hidden" name="mobile" value="{{$data['mobile']}}">
</form>
<script>
    var options = {!!$json_data!!};
    options.handler = function(response) {
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.razorpayform.submit();
    };
    options.theme.image_padding = true;
    options.modal = {
        ondismiss: function() {
            window.location = "{{$data['repath']}}";
        },
        escape: true,
        backdropclose: false
    };
    var rzp = new Razorpay(options);
    rzp.open();
</script>