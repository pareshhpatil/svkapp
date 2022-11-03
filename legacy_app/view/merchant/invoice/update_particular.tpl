

<!-- add particulars label -->
<h3 class="form-section">Add particulars
    <a href="javascript:;" onclick="AddInvoiceParticularRow();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">

    <table class="table table-bordered table-hover" id="particular_table">
        <thead>
            <tr>
                {foreach from=$particular_column key=k item=v}
                    {if $k!='sr_no'}
                        <th class="td-c" >
                            {$v}
                            
                        </th>
                    {/if}
                {/foreach}
                <th class="td-c">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody id="new_particular">
            {foreach from=$particular item=dp}
                <tr> 
                    {foreach from=$particular_column key=k item=v}
                        {if $k!='sr_no'}
                            <td>
                                {if $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                    <input type="number" step="0.01" onblur="calculateamt();" name="{$k}[]" class="form-control " value="{$dp.{$k}}">
                                {elseif $k=='gst'}
                                    <select name="gst[]" onchange="calculateamt();" style="min-width:80px;" class="form-control ">
                                        <option value="">Select</option>
                                        <option value="0">0%</option>
                                        <option {if $dp.{$k}==5} selected="" {/if} value="5">5%</option>
                                        <option {if $dp.{$k}==12} selected="" {/if} value="12">12%</option>
                                        <option {if $dp.{$k}==18} selected="" {/if} value="18">18%</option>
                                        <option {if $dp.{$k}==28} selected="" {/if} value="28">28%</option
                                    </select>
                                {else}
                                    {if $k=='item'}
                                        {$prod_ex=0}
                                        <select required style="width: 100%; " onchange="product_rate(this.value, this);" name="item[]" data-placeholder="Type or Select" class="form-control  productselect">
                                            <option value="">Select Product</option>
                                            {foreach from=$product_list key=$pk item=$vk}
                                                {if $dp.{$k}==$pk}
                                                    {$prod_ex=1}
                                                    <option selected="" value="{$pk}">{$pk}</option>
                                                {else}
                                                    <option value="{$pk}">{$pk}</option>
                                                {/if}
                                            {/foreach}
                                            {if $prod_ex==0}
                                                <option selected="" value="{$dp.{$k}}">{$dp.{$k}}</option>
                                            {/if}
                                        </select>
                                    {else}
                                        <input type="text" name="{$k}[]" class="form-control " value="{$dp.{$k}}">
                                    {/if}
                                {/if}
                            </td>
                        {/if}
                    {/foreach}
                    <td class="td-c"><input type="hidden" name="particular_id[]" value="{$dp.id}"> <a href="javascript:;" onclick="$(this).closest('tr').remove();
                            calculateamt();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>
                </tr>
            {/foreach}
        </tbody>
        <tfoot>
            <tr class="warning">
                {foreach from=$particular_column key=k item=v}
                    {if $k!='sr_no'}
                        <th class="td-c" >
                            {if $k=='item'}
                                <input type="text" value="{$info.particular_total}" class="form-control " readonly="">
                            {else if $k=='total_amount'}
                                <input type="text" id="particulartotal" name="totalcost" value="{$info.basic_amount}" class="form-control " readonly="">
                            {/if}
                        </th>
                    {/if}
                {/foreach}
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- add particulars label ends -->

