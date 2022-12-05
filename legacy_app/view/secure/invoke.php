<html>
<body oncontextmenu="return false;">
<form  method="post" action="<?php echo $_POST['req_url'] ?>" name="frmTransaction" id="frmTransaction" >
<input name="account_id" type="hidden" value="<?php echo $_POST['account_id'] ?>">
     
 <input name="return_url" type="hidden" size="60" value="<?php echo $_POST['return_url'] ?>" />
 <input name="mode" type="hidden" size="60" value="<?php echo $_POST['mode']?>" />
  <input name="reference_no" type="hidden" value="<?php echo  $_POST['reference_no'] ?>" />
  <input name="amount" type="hidden" value="<?php echo $_POST['amount']?>"/>
  <input name="description" type="hidden" value="<?php echo $_POST['description'] ?>" /> 
 <input name="name" type="hidden" maxlength="255" value="<?php echo $_POST['name'] ?>" />
<input name="address" type="hidden" maxlength="255" value="<?php echo $_POST['address'] ?>" />
<input name="city" type="hidden" maxlength="255" value="<?php echo $_POST['city'] ?>" />
<input name="state" type="hidden" maxlength="255" value="<?php echo $_POST['state'] ?>" />
<input name="postal_code" type="hidden" maxlength="255" value="<?php echo $_POST['postal_code'] ?>" />
<input name="country" type="hidden" maxlength="255" value="<?php echo $_POST['country'] ?>" />
 <input name="phone" type="hidden" maxlength="255" value="<?php echo $_POST['phone'] ?>" />
   <input name="email" type="hidden" size="60" value="<?php echo $_POST['email']?>" />
<input name="ship_name" type="hidden" maxlength="255" value="<?php echo $_POST['ship_name'] ?>" />
<input name="ship_address" type="hidden" maxlength="255" value="<?php echo $_POST['ship_address'] ?>" />
<input name="ship_city" type="hidden" maxlength="255" value="<?php echo $_POST['ship_city'] ?>" />
<input name="ship_state" type="hidden" maxlength="255" value="<?php echo $_POST['ship_state'] ?>" />
<input name="ship_postal_code" type="hidden" maxlength="255" value="<?php echo $_POST['ship_postal_code'] ?>" />
<input name="ship_country" type="hidden" maxlength="255" value="<?php echo $_POST['ship_country'] ?>" />
<input name="ship_phone" type="hidden" maxlength="255" value="<?php echo $_POST['ship_phone'] ?>" />
<input name="secure_hash" type="hidden" size="60" value="<?php echo $_POST['hash']?>" />
 
 
</form>
</body>
<script type="text/javascript">

document.getElementById("frmTransaction").submit();

</script>
</html>