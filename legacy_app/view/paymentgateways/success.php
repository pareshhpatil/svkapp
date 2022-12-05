<div class="vendorsigninmain">
    <div class="gap"></div>
    <div class="personal_details sadow" style="padding: 0 0 10px; width:800px;">

        <div class="suc"  > <img src="/images/payment-success.gif" class="sucimg"  />
            <p class="" style="width:700px;">
                Your payment has been successfully processed by our banking partner. An email receipt has been sent by Swipez with these details on your registered email ID. </p>
            <div class="template" style="float: right; margin-right: 10px;" > <a href="/merchant/dashboard" class="back1a">< Back to dashboard</a></div>
        </div>




        <!--End -->
        <!--End -->
    </div>

    <div class="receipt ">

        <div class="row1 "><img src="/images/logo.png" /><div class="heading ">Transaction Receipt</div></div>
        <div class="row1 noborder ">
            <p><span>Thank You</span><br />
                Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.</p>
        </div>
        <div class="row2 ">

            <div class="left"><p class="tbletext">Merchant Name</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['BillingName']; ?></p>
            </div>
            <div class="left"><p class="tbletext">Merchant Email ID</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['BillingEmail']; ?></p>
            </div>

            <div class="left"><p class="tbletext">Transaction Ref Number</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['TransactionID']; ?></p>
            </div>
            <div class="left"><p class="tbletext">Payment Ref Number</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['MerchantRefNo']; ?></p>
            </div>
            <div class="left"><p class="tbletext">Payment Date &amp; Time</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['DateCreated']; ?></p>
            </div>
            <div class="left"><p class="tbletext">Payment Amount (&#x20B9;)</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['Amount']; ?></p>
            </div>
            <div class="left"><p class="tbletext">Mode of Payment</p>
            </div><div class="right"><p class="tbletext"><?php echo $this->paymentdetail['payment_mode']; ?></p>
            </div>
            <br />
            <br />
            <br />
            <br />


        </div>
        <div class="row3 "><p>Powered by Swipez &copy; <?php echo $this->current_year; ?> OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
        </div>
    </div>

    <!--mid-->
</div>
<!--main-->