<?php
$swipez_fee_type = $this->PG['swipez_fee_type'];
$swipez_fee_val = $this->PG['swipez_fee_val'];
$pg_fee_type = $this->PG['pg_fee_type'];
$pg_fee_val = $this->PG['pg_fee_val'];
$pg_tax_type = $this->PG['pg_tax_type'];
$pg_tax_val = $this->PG['pg_tax_val'];
?>
<div class="gap"></div>

<div class="personal_details sadow">
    <div class="add_particulars">
        <div class="topbg"><div class="row_one"><strong>Add Taxes</strong></div><a  href = "javascript:void(0)" onclick = "InvoiceTax(<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);"><img src="/images/plus.gif"  class="plus"   /></a></div>
        <div class=" middlebg">


            <?php
            $int = $this->particularCount;
            $int = (isset($int)) ? $int : 1;
            $taxint = 1;
            $totaltax = 0;
            while (isset($this->invoicevalues['tax'][$int]['column_name'][0]) && $this->invoicevalues['tax'][$int]['column_type'][0] == 'TF') {
                $postInt++;
                $totaltax = $totaltax + $this->invoicevalues['tax'][$int]['value'][3];
                ?>
                <div id="pinnerDiv<?php echo $int; ?>">
                    <div class="row_three" ><input type="hidden" name="countrowtax"/>
                        <div class=" box1">
                            <div class="txt">Tax label</div>
                            <div class="field_bg"><input name="existvalues[]"  type="text" class="field" id="textfield7" value="<?php echo $this->invoicevalues['tax'][$int]['value'][0]; ?>" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.particular"); ?> />
                                <input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax'][$int]['invoice_id'][0]; ?>"  /></div>
                        </div>

                        <div class=" box2">
                            <div class="txt">Tax in %</div>
                            <div class="field_bg"><input name="existvalues[]" autocomplete="off" type="number" step="0.01" max="100" value="<?php echo $this->invoicevalues['tax'][$int]['value'][1]; ?>"  class="field" id="taxin<?php echo $taxint; ?>" onblur="calculatetax(<?php echo $taxint; ?>,<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);"  value="<?php echo $this->invoicevalues['tax'][$int]['value'][1]; ?>" /><input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax'][$int]['invoice_id'][1]; ?>" /></div>
                        </div>

                        <div class=" box2">
                            <div class="txt">Applicable on (&#x20B9;) <font style="color: black;"></div>
                            <div class="field_bg"><input name="existvalues[]" autocomplete="off" type="text" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.amount"); ?>  class="field" id="applicableamount<?php echo $taxint; ?>" onblur="calculatetax(<?php echo $taxint; ?>,<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);"  value="<?php echo $this->invoicevalues['tax'][$int]['value'][2]; ?>"  /><input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax'][$int]['invoice_id'][2]; ?>" /></div>
                        </div>
                        <div class=" box2">
                            <div class="txt">Absolute cost (&#x20B9;) <font style="color: black;"> </div>
                            <div class="field_bg"><input name="existvalues[]" onkeydown="return false;" autocomplete="off" type="text" style="background-color: #ccc;"  <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.amount"); ?> class="field" id="totaltax<?php echo $taxint; ?>" value="<?php echo $this->invoicevalues['tax'][$int]['value'][3]; ?>" tabindex="-1" /><input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax'][$int]['invoice_id'][3]; ?>" /></div>
                        </div>

                        <div class=" box2">
                            <div class="txt">Narrative</div>
                            <div class="field_bg"><input name="existvalues[]" autocomplete="off" type="text" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.narrative"); ?> class="field" id="textfield7" value="<?php echo $this->invoicevalues['tax'][$int]['value'][4]; ?>"  /><input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax'][$int]['invoice_id'][4]; ?>" /></div>
                        </div>
                        <img src="/images/minus1.gif" id="3" onclick="removenewdiv(<?php echo $int; ?>);
                    calculatetax(<?php echo $int; ?>,<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);" class="minus1" style="cursor:pointer;">
                    </div>
                </div><!--End -->
                <?php
                $int++;
                $taxint++;
            }
            ?>


            <div id="newtax">
            </div>

            <div class="row_total row_three" style=" width:895px !important;">
                <div class=" box1">
                    <div class="field_bg">
                        <input name="existvalues[]" type="text" class="field" id="textfield" value="<?php
                        if (isset($_POST['tax_total'])) {
                            echo $_POST['tax_total'];
                        } else {
                            echo $this->invoicevalues['tax_total'];
                        }
                        ?>">
                        <input type="hidden" name="existids[]" value="<?php echo $this->invoicevalues['tax_total_id']; ?>">
                    </div>
                </div>
                <div class=" box2">
                    <div class="field_bg" style="border:none;"> </div>
                </div>
                <div class="box2" id="totalapplicable">&nbsp;</div>
                <div class="box1" id="totaltaxcost"><?php echo $totaltax; ?></div>
                <input type="hidden" value="<?php echo $totaltax; ?>" name="totaltax" id="totaltaxamt"/>
            </div>






        </div>
    </div>
</div>





<?php
if (isset($this->invoicevalues['supplier_id'])) {
    $existsupplier = explode(",", $this->invoicevalues['supplier']);
    ?>
    <div class="gap"></div>
    <a name="supplier"></a>
    <div id="supplierpanel"  class="personal_details sadow" >
        <div class="add_particulars">
            <div class="topbg"><div class="row_one"><strong>Add Supplier</strong></div><a href = "#supplier" onclick = "document.getElementById('guest_pop').style.display = 'block';"><img src="/images/plus.gif"  class="plus"   /></a>
            </div>
            <div class=" middlebg">
                <input type="hidden" name="supplier_id" value="<?php echo $this->invoicevalues['supplier_id']; ?>">
                <?php
                foreach ($this->supplierlist as $supplier) {
                    if (in_array($supplier['supplier_id'], $existsupplier)) {
                        ?>
                        <script>
            document.getElementById("spid" +<?php echo $supplier['supplier_id']; ?>).checked = true;
                        </script>
                        <div id="pinnerDiv<?php echo $supplier['supplier_id']; ?>4544">
                            <span>
                                <div id="add_supplier1">
                                    <input type="hidden" name="supplier[]" value="<?php echo $supplier['supplier_id']; ?>">
                                    <div class="row_three" style="margin-bottom:-11px;">
                                        <div class=" box2"><div class="txt">Company name</div></div>
                                        <div class=" box2"><div class="txt">Contact person</div></div>
                                        <div class=" box2"><div class="txt">Contact email</div></div>
                                        <div class=" box2"><div class="txt">Contact mobile</div></div>
                                        <div class=" box2"><div class="txt">Industry type</div></div>
                                    </div>
                                    <div class="row_three">
                                        <div class="row_three" style="margin:0px;">
                                            <div class=" box2"><div class="field_bg2"><?php echo $supplier['supplier_company_name']; ?></div></div>
                                            <div class=" box2"><div class="field_bg2"><?php echo $supplier['contact_person_name']; ?></div></div>
                                            <div class=" box2"><div class="field_bg2"><?php echo $supplier['email_id1']; ?></div></div>
                                            <div class=" box2"><div class="field_bg2"><?php echo $supplier['mobile1']; ?></div></div>
                                            <div class=" box2"><div class="field_bg2"><?php echo $supplier['config_value']; ?></div></div>
                                            <img src="/images/minus1.gif" id="<?php echo $supplier['supplier_id']; ?>" onclick="removesupplier(this.id)" class="minus1" style="cursor:pointer;">
                                        </div> 
                                    </div>
                                </div>
                            </span>
                        </div>
                        <?php
                    }
                }
                ?>
                <div id="newsupplier">
                </div>
            </div>
        </div>
    </div>
<?php } ?>






<div class="gap"></div>

<div class="personal_details sadow">
    <div class="add_particulars">
        <div class="topbg">
            <div class="row_one"><strong>Final Summary</strong></div>
        </div>
        <div class=" middlebg">
            <div class="row_three">

                <div class=" box2">
                    <div class="txt"><strong>Bill value with taxes</strong></div>

                    <div class="field_bg" ><input autocomplete="off" type="text" tabindex="-1" style="background-color: #ccc;" onkeydown="return false;"  class="field" name="amount"  id="totalamount" onblur="calculategrandtotal(<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);" value="<?php echo $this->info['invoice_total']; ?>"  <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.bill_value"); ?> /></div>

                </div>

                <div class=" box2">
                    <div class="txt"><strong>Grand total</strong></div>

                    <div class="field_bg"><input autocomplete="off" type="text"  class="field" style="background-color: #ccc;" tabindex="-1" onkeydown="return false;" name="grand_total" id="grandtotal" value="<?php echo $this->info['grand_total']; ?>"  <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.grand_total"); ?>  >
                        <a title="<?php echo (empty($this->PG)) ? 'You will receive Grand Total minus applicable online payment charges on payment.' : "Includes online payment charges. On payment you will receive amount mentioned in 'Bill value with Taxes'."; ?>" class="masterTooltip"><img src="/images/question_mark.gif" width="20" height="13" class="masterTooltip question_mark2" border="0" /></a>
                    </div>

                </div>

                <div class=" box2">
                    <div class="txt"><strong>Narrative</strong></div>

                    <div class="field_bg"> <input class="field" name="narrative" type="text" value="<?php echo $this->info['narrative']; ?>" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.narrative"); ?> /></div>

                </div>



            </div>
        </div>
    </div>
</div>

<div class="gap"></div>
<div class="gn_temp">
    <input type="hidden" id="template_type" name="template_type" value="<?php echo $this->info['template_type']; ?>"/>
    <input type="hidden" name="payment_request_id" value="<?php echo $this->link; ?>"/> 
    <input type="submit" id="Btnsubmit" class="complete1"  value="Save >" name="entitysubmit" />

</div>

</div>
<!--main-->
<?php include VIEW . 'common/selectsupplier.php'; ?>

</form>
</div> </div> 

<div style="margin:auto; width:980px; float: right;"> <a href="#top"><img src="/images/TOP.gif" width="55" height="31" align="right" border="0" /></a> </div>
<script type="text/javascript" src="/inc/js/subscription.js"></script>

<script>
            var date1 = document.getElementById('due_datetime').value;
            document.getElementById('due_datetime').value = date1;
            showDatePicker('due_datetime', date1);

            var date2 = document.getElementById('start_date').value;
            showDatePicker('start_date', date2);

            var end_date = document.getElementById('end_date').value;
            document.getElementById('end_date').value = end_date;
            showDatePicker('end_date', end_date);

            duedateSummery();
</script>


