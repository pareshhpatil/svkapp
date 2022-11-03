</div>
<!-- add taxes label ends -->
<!-- grand total label -->

</div>
<h3 class="form-section">Final summary</h3>

<div class="row">
    <div class="col-md-3 w-auto">
        <div class="form-group">
            <p>Fee value with taxes</p>
            <input type="text" id="totalamount" onblur="calculatesimplegrandtotal();" name="amount"  value="{$info.invoice_total}" readonly class="form-control">

        </div>

    </div>
    <div class="col-md-2">
        <div class="form-group">
            <p>Grand total</p>
            <input type="text" id="grandtotal" name="grand_total" value="{$info.grand_total}" readonly class="form-control">
        </div>
    </div>
   
    <div class="col-md-4">

        <p>&nbsp;</p>
        <input type="hidden" id="template_type" name="template_type" value="{$info.template_type}"/>
        <input type="hidden" name="payment_request_id" value="{$payment_request_id}"/> 
        <input type="submit" class="btn blue" value="Save">

    </div>

    <!--/span-->

    <!-- grand total label ends -->
</div>
</form>
</div>
</div>	
<!-- END PAGE CONTENT-->
</div>
    </div>
</div>
<!-- END CONTENT -->
</div>
<!-- /.modal -->

<script>
    function add_late_fee() {
        var amount, latefee;
        amount = document.getElementById('amount').value;
        latefee = document.getElementById('late_fee').value;
        result = (parseFloat(amount) + parseFloat(latefee));
        if (result > 0) {
            document.getElementById('amount_with_latefee').value = result;
        }
        document.getElementById('totalamount').onblur();
    }
</script>