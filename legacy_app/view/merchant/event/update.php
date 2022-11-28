<?php
$swipez_fee_type = $this->PG['swipez_fee_type'];
$swipez_fee_val = $this->PG['swipez_fee_val'];
$pg_fee_type = $this->PG['pg_fee_type'];
$pg_fee_val = $this->PG['pg_fee_val'];
$pg_tax_type = $this->PG['pg_tax_type'];
$pg_tax_val = $this->PG['pg_tax_val'];
?>
<script>
    var numherder = 1;
</script>
<script>
    $(function() {
        $("#banner").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    $(function() {
        $("#logo").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = imageIsLoaded2;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    function imageIsLoaded(e) {
        var image = $('#bannerdiv');
        image.css('background-image', 'url(' + e.target.result + ')');
    }
    ;

    function imageIsLoaded2(e) {
        $('#logoimg').attr('src', e.target.result);
    }
    ;


</script>

<div class="gap"> </div> 
<div class="patron_details">

</div>
<form enctype="multipart/form-data" id="templateform"  action="/merchant/event/updatesaved" method="post">
    <input type='file' id="banner" name="banner" style="visibility: hidden;" />
    <input type='file' id="logo" name="logo" style="visibility: hidden;" />  

    <div class="" style="width: 784px;float: left;margin-top: 2px; margin-left:260px;">
        <div id="bannerdiv" class="banner" style="background-image: url(/uploads/images/logos/<?php echo $this->info['banner_path']; ?>);">  
            <img src="/images/cam.png" class="masterTooltip" title="Upload banner image" onclick="document.getElementById('banner').click();"  style="  float: left;width: 40px;height: 35px;cursor: pointer; margin-left: 20px; margin-top: 20px;"></img>
            <div style="height: 119px;width: 193px;margin-top: 205px;margin-left: 4px;-webkit-box-shadow: 2px 1px 1px grey;
                 -webkit-border-radius: 5px;position: absolute;">  <img id="logoimg" src="<?php if ($this->info['image_path'] != '') {
    echo '/uploads/images/logos/' . $this->info['image_path'];
} else {
    echo '/images/logo.gif';
} ?>" style="height: 119px;width: 193px;position: absolute; " ></img><div style="height: 25px;  padding-top: 5px; width: 100%; background-color: black;opacity: 0.4;text-align: center;position: absolute; margin-top: 90px; color: white;cursor: pointer;" onclick="document.getElementById('logo').click();">Upload</div></div>
        </div>


        <div class="mandatory personal_details personal_details2 sadow" style="width:784px;margin-top: 20px;">
            <div class="mandatory add_particulars" style="width:784px;">
                <div class="mandatory topbg" style="width:784px;">
                    <div class="mandatory row_one" style="width:694px;"><strong>Update an event</strong></div><a  href = "javascript:void(0)" onclick = "addevent()"><img src="/images/plus.gif"  class="plus"   /></a></div>
            </div>
            <div class="gap" style="width:784px;"></div>
            <div class="profile-row"><h1>Event name </h1><input name="event_name" type="text" class="field2" value="<?php echo $this->info['template_name']; ?>" required="" aria-required="true" maxlength="250"></div>

            <?php
            $int = 0;
            foreach ($this->eventvalues as $event) {
                $column_name = $this->eventvalues['column_name'][$int];
                $value = $this->eventvalues['value'][$int];
                $column_name = $this->eventvalues['column_name'][$int];
                $column_id = $this->eventvalues['column_id'][$int];
                $invoice_id = $this->eventvalues['inv_id'][$int];
                $datatype = $this->eventvalues['column_datatype'][$int];
                $position = $this->eventvalues['column_position'][$int];
                $mandatory = $this->eventvalues['is_mandatory'][$int];
                $mandatory = ($mandatory == 1) ? 1 : 2;
                $required = ($mandatory == 1) ? 'required' : '';
                $validator = $this->HTMLValidatorPrinter->fetch("htmlval." . $datatype . "") . $required;
                ?>
                    <?php if ($datatype == 'text' || $datatype == 'money' || $datatype == 'number') { ?>
                    <div id="exist<?php echo $column_id; ?>" class="profile-row">
                        <input type="hidden" name="invoice_id[]" value="<?php echo $invoice_id; ?>" /><h1><?php echo $column_name; ?> </h1>
                        <input name="existvalue[]" type="text" class="field2" value="<?php echo $value; ?>" <?php echo $validator; ?> aria-required="true" title="" maxlength="50">
        <?php if ($mandatory == 2) { ?> &nbsp;&nbsp;<img src="/images/minus1.gif" id="<?php echo $column_id; ?>" onclick="removedivexist(this.id)" style="cursor:pointer;"><?php } ?>
                    </div>
                    <?php } else if ($datatype == 'textarea') { ?>
                    <div id="exist<?php echo $column_id; ?>" class="profile-row">
                        <input type="hidden" name="invoice_id[]" value="<?php echo $invoice_id; ?>" />
                        <h1><?php echo $column_name; ?> </h1>
                        <textarea name="existvalue[]" class="field2" required aria-required="true" <?php echo $validator; ?> style="height: 100px;width: 350px;" title="" maxlength="65000"><?php echo $value; ?></textarea>
        <?php if ($mandatory == 2) { ?> &nbsp;&nbsp;<img src="/images/minus1.gif" id="<?php echo $column_id; ?>" onclick="removedivexist(this.id)" style="cursor:pointer;"><?php } ?>
                    </div>
    <?php } else if ($position == 2) { ?>
                    <div id="exist<?php echo $column_id; ?>" class="profile-row">
                        <input type="hidden" name="invoice_id[]" value="<?php echo $invoice_id; ?>" />
                        <input type="hidden" name="existvalue[]" id="daterange" value="<?php echo $value; ?>" />
                        <h1><?php echo $column_name; ?> &nbsp; <span style="font-weight: bold;color: #5f5e5e;cursor: pointer;" id="addrange" onclick="adddaterange();">Add range</span> </h1> <input name="fromdate" id="fromdate" readonly="" type="text" onchange="setdaterange();" value="<?php echo $this->info['bill_date']; ?>" class="field2" style="width: 166px;margin-right: 20px;">
                        <input name="todate" id="todate" readonly="" onchange="setdaterange();" type="text" value="<?php echo $this->info['due_date']; ?>" class="field2" style="width: 166px; <?php if ($this->info['bill_date'] == $this->info['due_date']) {
                echo 'display: none;';
            } ?>">

                    </div>
    <?php } ?>

    <?php
    $int++;
}
?>

            <div id="newevent">
            </div>

            <div  class="profile-row">
                <h1>Units available (seats) &nbsp;<b><i title="keep 0 for unlimited units" class="masterTooltip">?</i></b></h1> <input name="unitavailable" value="<?php echo $this->info['unit_available']; ?>" required maxlength="7"  type="text"  class="field2" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.number"); ?> >
            </div>
            <div  class="profile-row">
                <h1>Unit cost</h1> <input name="unitcost" id="unitcost" required value="<?php echo $this->info['invoice_total']; ?>" onblur="calculateEventgrandtotal(<?php echo "'" . $swipez_fee_type . "','" . $swipez_fee_val . "','" . $pg_fee_type . "','" . $pg_fee_val . "','" . $pg_tax_val . "'"; ?>);" type="text" <?php $this->HTMLValidatorPrinter->fetch("htmlval.grand_total"); ?>  class="field2" >
            </div>

            <div  class="profile-row">
                <h1>Grand total</h1> <input name="grandtotal" id="grandtotal" value="<?php echo $this->info['grand_total']; ?>" style="background-color: #ccc;" tabindex="-1" onkeydown="return false;" readonly="" type="text" <?php echo $this->HTMLValidatorPrinter->fetch("htmlval.grand_total"); ?> class="field2">
            </div>

            <!--End -->

        </div><!--main-->
        <input type="hidden" value="<?php echo $this->link; ?>" name="payment_request_id">

    </div><input type="submit" value="Save >" class="submit1" id="Btnsubmit" style="margin: 20px 0 0 640px;"/> </div></form>



<script>
    var int = 2;
    var date1 = document.getElementById('fromdate').value;
    var date2 = document.getElementById('todate').value;
    if (date1 == date2)
    {
        var int = 1;
    }
    else
    {
        document.getElementById('addrange').innerHTML = 'Remove range';
    }
    var date1 = document.getElementById('fromdate').value;
    showDatePicker('fromdate', date1);
    var date2 = document.getElementById('todate').value;
    showDatePicker('todate', date2);

    function setdaterange()
    {
        var date1 = document.getElementById('fromdate').value;
        var date2 = document.getElementById('todate').value;
        if (int == 1)
        {
            date2 = date1;
            document.getElementById('todate').value=date1;
        }
        document.getElementById('daterange').value = date1 + ' to ' + date2;
    }

    function adddaterange()
    {
        if (int == 1)
        {
            document.getElementById('todate').style.display = 'block';
            document.getElementById('addrange').innerHTML = 'Remove range';
            document.getElementById('todate').disabled = false;
            int = 2;
        } else
        {
            document.getElementById('todate').style.display = 'none';
            document.getElementById('addrange').innerHTML = 'Add range';
            document.getElementById('todate').disabled = false;
            int = 1;
            setdaterange();
        }
    }

</script>