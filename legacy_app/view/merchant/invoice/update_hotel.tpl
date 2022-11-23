

<!-- add particulars label -->
<h3 class="form-section">Add particulars
    <a href="javascript:;" onclick="AddHotelInvoiceParticular();" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" id="particular_table">
        <thead>
            <tr>
                <th class="td-c">
                    Product {if isset($product_array)}<a  data-toggle="modal"  href="#new_product" class="btn btn-xs green pull-right"> <i class="fa fa-plus"> </i></a>{/if}
                </th>
                <th class="td-c">
                    Quantity
                </th>
                <th class="td-c">
                    Unit cost
                </th>
                <th class="td-c"> 
                    Absolute cost
                </th>
                <th class="td-c">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody id="new_particular">
            {$int =1}
            {foreach from=$particular item=v}
                <tr>
            <input type="hidden" name="countrow"/>
            <td>
                <div class="input-icon right">
                    
                    {if isset($product_array)}
                        <select style="max-width:200px;    padding-right: 0px; padding-left: 10px;" onchange="product_rate(this.value,{$int});" id="particular{$int}"  name="existvalues[]" data-placeholder="Select..." class="form-control  select2 select2_sample2 input-sm" >
                            <option value="">Select Product</option>
                            {foreach from=$product_list item=pp}
                                <option {if $v.0.value==$pp} selected {/if} value="{$pp}">{$pp}</option>
                            {/foreach}
                        </select>
                    {else}
                        <input  type="text"  id="particular{$int}"  {$validate.narrative} value="{$v.0.value}"  name="existvalues[]" class="form-control input-sm" placeholder="Add product name">
                    {/if}
                    
                    <input name="existids[]" type="hidden" class="field"  value="{$v.0.invoice_id}"  />
                </div>
            </td>
            <td>
                <input type="text" name="existvalues[]"  {$validate.quantity}  value="{$v.1.value}" onblur="calculatecost({$int});" id="unitnumber{$int}"  class="form-control input-sm">
                <input name="existids[]" type="hidden" class="field"  value="{$v.1.invoice_id}"  />
            </td>
            <td>
                <input type="text" name="existvalues[]"  {$validate.money}  value="{$v.2.value}" onblur="calculatecost({$int});" id="unitprice{$int}" class="form-control input-sm">
                <input name="existids[]" type="hidden" class="field"  value="{$v.2.invoice_id}"  />
            </td>
            <td>
                <input type="text" name="existvalues[]" value="{$v.3.value}" id="cost{$int}" readonly  class="form-control input-sm">
                <input name="existids[]" type="hidden" class="field"  value="{$v.3.invoice_id}"  />
            </td>
            <td>
                <a  id="{$int}" onclick="removeParticular(this.id);
            $(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> Delete row </a>
            </td>
            </tr>
            {$int = $int + 1}
        {/foreach}
        </tbody>
        <tr class="warning">
            <td><div class="input-icon right">
                    
                    <input type="text" value="{$particularTotal.value}" name="existvalues[]" class="form-control input-sm" placeholder="Enter total label">
                    <input type="hidden" value="{$particularTotal.invoice_id}" name="existids[]" >
                </div>
            </td>
            <td></td>
            <td></td>
            <td>
                <input type="text" id="particulartotal" value="{$info.basic_amount}" name="totalcost" class="form-control input-sm" readonly>
            </td>
            <td></td>

        </tr>
    </table>
</div>
<!-- add particulars label ends -->

