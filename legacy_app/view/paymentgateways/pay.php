 <div class="gap_boxd"></div>
        <div class="gap_boxd"></div>
        <form action="payment" id="pay_form" name="pay_form" method="post">

       <div class="personal_details sadow">
        <div class="icone6"></div> 
       	<div class="pymntcatbg"  style="margin-left:-10px;">
       	  <div class="sctptfld arialspg"><?php echo $this->packageDetail['package_name']; ?></div>
          <div class="nvoptxtfld">
          	<div class="nvoptxtunder arialemail">
          <?php echo $this->packageDetail['package_description'];; ?></div>
          <div class="rsfldnew arialredbig">&#x20B9; <?php echo round($this->packageDetail['package_cost'],2); ?></div>
          
          </div>
        </div></div>
        
          <div class="gap_boxd"></div>
         
        
        
<div class="personal_details sadow">
        	 
            <div class="transferfundfld arialemail" style="margin-top:8px;"><br />
              Please note: We do not store any of your card/ account information during your order placement. During order placement we might redirect you to a <br />
         secure banking/payment gateway website to provide your card/account credentials. </div>
<div class="pymnteternconfld">
                    <div class="vendtermw"><input type="checkbox" name="checkbox" id="checkbox"  <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.checkbox"); ?> /> </div>
                    <div class="ventnctxtfld arialemail">I accept the 
                    <a href="/terms-popup" class="example5" style="border-right: none; color:#4e4e4e; text-decoration:underline;" >Terms and conditions</a> & 
                  <a href="/privacy-popup" class="example5" style="border-right: none; color:#4e4e4e; text-decoration:underline;" >Privacy policy</a></div>
                    </div>
                     <div class="transferfundfld arialemail">
                         <input name="package_id" type="hidden" size="60" value="<?php echo $this->packageDetail['package_id']; ?>" />
                    	<div class="clickherebtn"> <input type="submit" id="Btnsubmit" value="Click here to place the order" class="complete1" /></div>
         
                     </div>  </div> 
            
      <div class="gap_boxd"></div>
        </form>
     <!--main-->
    