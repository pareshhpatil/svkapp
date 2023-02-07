<html>
<script src="https://sdk.cashfree.com/js/ui/2.0.0/cashfree.sandbox.js"></script>
<script>
function render() {
  let paymentSessionId = "{{$payment_session_id}}";
  if (paymentSessionId == "") {
    alert("No session_id specified");
    return
  };
const cf = new Cashfree(paymentSessionId);
  cf.redirect();
  };
</script>


<span class="order-token"></span>  

</div>
<hr>
<script>
render();
</script>
</html>


