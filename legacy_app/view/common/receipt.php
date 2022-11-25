
<div class="vendorsigninmain" style="width: 600px; margin: inherit;">
  <div class="gap"></div>
  
  <div class="receipt " style="margin-top: 0px; height: 540px;" >
  
      <div class="row1 "><img src="/images/logo.png" /><div class="heading " style="line-height: 49px;">Transaction Receipt</div></div>
    <div class="row1 noborder ">
  <p><span>Thank You</span><br /><br />
Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.</p>
</div>
<div class="row2 ">

<div class="left"><p class="tbletext">Patron Name</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['patron_name']!='')? $this->paymentdetail['patron_name'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Patron Email ID</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['patron_email']!='')? $this->paymentdetail['patron_email'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Payment Towards</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['company_name']!='')? $this->paymentdetail['company_name'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Payment Ref Number</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['ref_no']!='')? $this->paymentdetail['ref_no'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Transaction Ref Number</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['transaction_id']!='')? $this->paymentdetail['transaction_id'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Payment Date &amp; Time</p>
</div><div class="right"><p class="tbletext"><?php if($this->paymentdetail['date']!=''){
    $date = new DateTime($this->paymentdetail['date']); echo $date->format('d-M-Y h:i:s'); } else{ echo '&nbsp;';} ?></p>
</div>
<div class="left"><p class="tbletext">Payment Amount (&#x20B9;)</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['amount']!='')? $this->paymentdetail['amount'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Mode of Payment</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['payment_mode']!='')? $this->paymentdetail['payment_mode'] : '&nbsp;'; ?></p>
</div>



</div>
<div class="row3 "><p>Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
 </div>
  </div>
  
  <!--mid-->
</div>
 <!--main-->