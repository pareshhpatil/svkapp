

<!-- add particulars label -->
<h3 class="form-section">Add particulars
    <a href="javascript:;" onclick="AddInvoiceParticularRow();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>
<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="particular_table">

    </table>
</div>
<!-- add particulars label ends -->

<script>
    particular_values='{$info.particular_values}';
    drawInvoiceParticularFormat('{$info.particular_column}','{$info.particular_total}');
    {foreach from=$default_particular item=v}
        AddInvoiceParticularRow('{$v}');
    {/foreach}
        
</script>