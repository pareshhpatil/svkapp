
<div class="vendorsigninmain" style=" margin: inherit;">
  <div class="gap"></div>
  
    <div class="personal_details sadow" style="padding: 0 0 10px; width:800px;">
  
    <div class="suc"  > <img src="/images/payment-success.gif" class="sucimg"  />
       <p class="" style="width:700px;">
         Your offline payment has been successfully saved. </p>
          <div class="template" style="float: right; margin-right: 10px;" ><?php if($this->isGuest!=TRUE){?> <a href="/patron/dashboard" class="back1a">< Back to dashboard</a><?php } ?></div>

    </div>
  </div>
   <div class="gap"></div>
  <div class="receipt " style="margin-top: 0px; height: 570px;" >
  
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
<div class="left"><p class="tbletext">Transaction Ref Number</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['transaction_id']!='')? $this->paymentdetail['transaction_id'] : '&nbsp;'; ?></p>
</div>

<?php if($this->paymentdetail['type']==1)
{?>
<div class="left"><p class="tbletext">Bank Ref Number</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['transaction_no']!='')? $this->paymentdetail['transaction_no'] : '&nbsp;'; ?></p>
</div>

<div class="left"><p class="tbletext">Bank Name</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['bank_name']!='')? $this->paymentdetail['bank_name'] : '&nbsp;'; ?></p>
</div>
<?php }else if($this->paymentdetail['type']==2){ ?>
<div class="left"><p class="tbletext">Cheque Number</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['cheque_no']!='')? $this->paymentdetail['cheque_no'] : '&nbsp;'; ?></p>
</div>

<div class="left"><p class="tbletext">Bank Name</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['bank_name']!='')? $this->paymentdetail['bank_name'] : '&nbsp;'; ?></p>
</div>
<?php }else if($this->paymentdetail['type']==3){ ?>
<div class="left"><p class="tbletext">Cash Paid To</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['cash_paid_to']!='')? $this->paymentdetail['cash_paid_to'] : '&nbsp;'; ?></p>
</div> <?php } ?>

<div class="left"><p class="tbletext">Payment Date &amp; Time</p>
</div><div class="right"><p class="tbletext"><?php if($this->paymentdetail['create_date']!=''){
    $date = new DateTime($this->paymentdetail['create_date']); echo $date->format('d-M-Y h:i:s.'); } else{ echo '&nbsp;';} ?></p>
</div>
<div class="left"><p class="tbletext">Payment Amount (&#x20B9;)</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['amount']!='')? $this->paymentdetail['amount'] : '&nbsp;'; ?></p>
</div>
<div class="left"><p class="tbletext">Mode of Payment</p>
</div><div class="right"><p class="tbletext"><?php echo ($this->paymentdetail['payment_mode']!='')? $this->paymentdetail['payment_mode'] : '&nbsp;'; ?></p>
</div>



</div>
      <div class="row3 "><p>Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span> </p>
 </div>
  </div>
  
  <!--mid-->
</div>
 <!--main-->