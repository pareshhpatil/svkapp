{if isset($properties.vehicle_section)}
    <!-- add particulars label -->
    <h3 class="form-section">{$properties.vehicle_section.title}
        <a href="javascript:;" onclick="AddInvoiceParticularRow();" class="btn btn-sm green pull-right"> <i
                class="fa fa-plus"> </i> Add new row </a>
    </h3>

    <div class="table-scrollable">

        <table class="table table-bordered table-hover" id="particular_table">
            <thead>
                <tr>
                    {foreach from=$particular_column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
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
                {$total_amount=0}
                {foreach from=$particular item=dp}
                    <tr>
                        {foreach from=$particular_column key=k item=v}
                            {if $k!='sr_no'}
                                <td>
                                    {if $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                        <input type="number" step="0.01" onblur="calculateamt();" name="{$k}[]" class="form-control "
                                            value="{$dp.{$k}}">
                                        {if $k=='total_amount'}
                                            {$total_amount=$total_amount+{$dp.{$k}}}
                                        {/if}
                                    {elseif $k=='gst'}
                                        <select name="gst[]" onchange="calculateamt();" style="min-width:80px;" class="form-control ">
                                            <option value="">Select</option>
                                            <option value="0">0%</option>
                                            <option {if $dp.{$k}==5} selected="" {/if} value="5">5%</option>
                                            <option {if $dp.{$k}==12} selected="" {/if} value="12">12%</option>
                                            <option {if $dp.{$k}==18} selected="" {/if} value="18">18%</option>
                                            <option {if $dp.{$k}==28} selected="" {/if} value="28">28%</option </select>
                                        {else}
                                            {if $k=='item'}
                                                {$prod_ex=0}
                                                <select required style="width: 100%; " onchange="product_rate(this.value, this);" name="item[]"
                                                    data-placeholder="Type or Select" class="form-control  productselect">
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
                        <td class="td-c"><input type="hidden" name="particular_id[]" value="{$dp.id}"> <a href="javascript:;"
                                onclick="$(this).closest('tr').remove();
                                                                                                                                                                                                                                            calculateamt();"
                                class="btn btn-sm red"> <i class="fa fa-times">
                                </i> </a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr class="warning">
                    {foreach from=$particular_column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
                                {if $k=='item'}
                                    <input type="text" value="{$info.particular_total}" class="form-control " readonly="">
                                {else if $k=='total_amount'}
                                    <input type="text" id="particulartotal" value="{$total_amount}" class="form-control " readonly="">
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
{/if}

{$sec_total=0}
{if isset($properties.travel_section)}
    <!-- add particulars label -->
    <script>
        var tb_col='{$properties.travel_section.column|@json_encode}';
    </script>
    <h3 class="form-section">{$properties.travel_section.title}
        <a href="javascript:;" onclick="AddSecRow(tb_col,'tb');" class="btn btn-sm green pull-right"> <i class="fa fa-plus">
            </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    {$int=0}
                    {foreach from=$properties.travel_section.column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
                                {$v}
                            </th>
                            {$int=$int+1}
                        {/if}
                    {/foreach}
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_tb">
                {$total_amount=0}
                {$rate=0}
                {foreach from=$ticket_detail item=v}
                    {if $v.type==1}
                        {$int=1}
                        <tr>
                            {foreach from=$properties.travel_section.column key=k item=tb}
                                {$readonly=''}
                                {if $k!='sr_no'}
                                    <td class="td-c">
                                        {if $k == 'charge' || $k == 'amount' || $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                            {if $k == 'amount' || $k == 'rate'}
                                                {$rate=1}
                                            {/if}
                                            {if $rate==1 && $k == 'total_amount'}
                                                {$readonly='readonly'}
                                            {/if}
                                            {if $k == 'total_amount'}
                                                <input onblur="calculateSecamt('tb');" type="text" value="{$v.total}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                                {$total_amount=$total_amount+$v.total}
                                            {else}
                                                <input onblur="calculateSecamt('tb');" type="text" value="{$v.{$k}}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                            {/if}
                                        {elseif $k=='gst'}
                                            <select name="sec_gst[]" onchange="calculateamt('sec_');" style="min-width:80px;"
                                                class="form-control ">
                                                <option value="">Select</option>
                                                <option value="0">0%</option>
                                                <option {if $v.{$k}==5} selected="" {/if} value="5">5%</option>
                                                <option {if $v.{$k}==12} selected="" {/if} value="12">12%</option>
                                                <option {if $v.{$k}==18} selected="" {/if} value="18">18%</option>
                                                <option {if $v.{$k}==28} selected="" {/if} value="28">28%</option </select>
                                            {elseif $k=='booking_date' || $k=='journey_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.{$k}|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='from'}
                                                <input type="text" value="{$v.from_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {elseif $k=='to'}
                                                <input type="text" value="{$v.to_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {else}
                                                <input type="text" value="{$v.{$k}}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {/if}
                                    </td>
                                    {$int=$int+1}
                                {/if}
                            {/foreach}
                            <td>
                                {foreach from=$travel_col item=tc}
                                    {if isset($properties.travel_section.column.{$tc})}
                                    {else}
                                        <input type="hidden" name="sec_{$tc}[]" value="">
                                    {/if}

                                {/foreach}
                                <input type="hidden" name="sec_exist_id[]" value="{$v.id}">
                                <input type="hidden" name="sec_type_value[]" value="tb">
                                <a href="javascript:;"
                                    onclick="$(this).closest('tr').remove();calculateSecamt('tb');calculateamt('sec_');"
                                    class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{$int-2}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                        {$sec_total=$sec_total+$total_amount}
                        <input type="text" id="tb_sectotalamt" value="{$total_amount|string_format:"%.2f"}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>


    </div>

    <script>
        var tc_col='{$properties.travel_cancel_section.column|@json_encode}';
    </script>
    <h3 class="form-section">{$properties.travel_cancel_section.title}
        <a href="javascript:;" onclick="AddSecRow(tc_col,'tc');" class="btn btn-sm green pull-right"> <i class="fa fa-plus">
            </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    {$int=0}
                    {foreach from=$properties.travel_cancel_section.column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
                                {$v}
                            </th>
                            {$int=$int+1}
                        {/if}
                    {/foreach}
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_tc">

                {$total_amount=0}
                {$rate=0}

                {foreach from=$ticket_detail item=v}
                    {if $v.type==2}
                        {$int=1}
                        <tr>
                            {foreach from=$properties.travel_cancel_section.column key=k item=tb}
                                {$readonly=''}
                                {if $k!='sr_no'}
                                    <td class="td-c">
                                        {if $k == 'charge' || $k == 'amount' || $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                            {if $k == 'amount' || $k == 'rate'}
                                                {$rate=1}
                                            {/if}
                                            {if $rate==1 && $k == 'total_amount'}
                                                {$readonly='readonly'}
                                            {/if}
                                            {if $k == 'total_amount'}
                                                <input onblur="calculateSecamt('tc');" type="text" value="{$v.total}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                                {$total_amount=$total_amount+$v.total}
                                            {else}
                                                <input onblur="calculateSecamt('tc');" type="text" value="{$v.{$k}}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                            {/if}
                                        {elseif $k=='gst'}
                                            <select name="sec_gst[]" onchange="calculateamt('sec_');" style="min-width:80px;"
                                                class="form-control ">
                                                <option value="">Select</option>
                                                <option value="0">0%</option>
                                                <option {if $v.{$k}==5} selected="" {/if} value="5">5%</option>
                                                <option {if $v.{$k}==12} selected="" {/if} value="12">12%</option>
                                                <option {if $v.{$k}==18} selected="" {/if} value="18">18%</option>
                                                <option {if $v.{$k}==28} selected="" {/if} value="28">28%</option </select>
                                            {elseif $k=='booking_date' || $k=='journey_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.{$k}|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='from'}
                                                <input type="text" value="{$v.from_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {elseif $k=='to'}
                                                <input type="text" value="{$v.to_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {else}
                                                <input type="text" value="{$v.{$k}}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {/if}
                                    </td>
                                    {$int=$int+1}
                                {/if}
                            {/foreach}
                            <td>
                                {foreach from=$travel_col item=tc}
                                    {if isset($properties.travel_cancel_section.column.{$tc})}
                                    {else}
                                        <input type="hidden" name="sec_{$tc}[]" value="">
                                    {/if}

                                {/foreach}
                                <input type="hidden" name="sec_exist_id[]" value="{$v.id}">
                                <input type="hidden" name="sec_type_value[]" value="tc">
                                <a href="javascript:;"
                                    onclick="$(this).closest('tr').remove();calculateSecamt('tc');calculateamt('sec_');"
                                    class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                            </td>
                        </tr>

                    {/if}
                {/foreach}
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{$int-2}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                    {$sec_total=$sec_total-$total_amount}
                        <input type="text" id="tc_sectotalamt" value="{$total_amount|string_format:"%.2f"}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
    <!-- add particulars label ends -->
{/if}

{if isset($properties.hotel_section)}
    <!-- add particulars label -->
    <script>
        var hb_col='{$properties.hotel_section.column|@json_encode}';
    </script>
    <h3 class="form-section">{$properties.hotel_section.title}
        <a href="javascript:;" onclick="AddSecRow(hb_col,'hb');" class="btn btn-sm green pull-right"> <i class="fa fa-plus">
            </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    {$int=0}
                    {foreach from=$properties.hotel_section.column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
                                {$v}
                            </th>
                            {$int=$int+1}
                        {/if}
                    {/foreach}
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_hb">

                {$total_amount=0}
                {$rate=0}

                {foreach from=$ticket_detail item=v}
                    {$v.qty=$v.units}
                    {if $v.type==3}
                        {$int=1}
                        <tr>
                            {foreach from=$properties.hotel_section.column key=k item=tb}
                                {$readonly=''}
                                {if $k!='sr_no'}
                                    <td class="td-c">
                                        {if $k == 'charge' || $k == 'amount' || $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                            {if $k == 'amount' || $k == 'rate'}
                                                {$rate=1}
                                            {/if}
                                            {if $rate==1 && $k == 'total_amount'}
                                                {$readonly='readonly'}
                                            {/if}
                                            {if $k == 'total_amount'}
                                                <input onblur="calculateSecamt('hb');" type="text" value="{$v.total}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                                {$total_amount=$total_amount+$v.total}
                                            {else}
                                                <input onblur="calculateSecamt('hb');" type="text" value="{$v.{$k}}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                            {/if}
                                        {elseif $k=='item'}
                                            {$prod_ex=0}
                                            <select required style="width: 100%; " onchange="product_rate(this.value, this,'sec_');"
                                                name="sec_item[]" data-placeholder="Type or Select" class="form-control  productselect">
                                                <option value="">Select Product</option>
                                                {foreach from=$product_list key=$pk item=$vk}
                                                    {if $v.{$k}==$vk}
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
                                        {elseif $k=='gst'}
                                            <select name="sec_gst[]" onchange="calculateamt('sec_');" style="min-width:80px;"
                                                class="form-control ">
                                                <option value="">Select</option>
                                                <option value="0">0%</option>
                                                <option {if $v.{$k}==5} selected="" {/if} value="5">5%</option>
                                                <option {if $v.{$k}==12} selected="" {/if} value="12">12%</option>
                                                <option {if $v.{$k}==18} selected="" {/if} value="18">18%</option>
                                                <option {if $v.{$k}==28} selected="" {/if} value="28">28%</option </select>
                                            {elseif $k=='from_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.booking_date|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='to_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.journey_date|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='from'}
                                                <input type="text" value="{$v.from_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {elseif $k=='to'}
                                                <input type="text" value="{$v.to_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {else}
                                                <input type="text" value="{$v.{$k}}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {/if}
                                    </td>
                                    {$int=$int+1}
                                {/if}
                            {/foreach}
                            <td>
                                {foreach from=$travel_col item=tc}
                                    {if isset($properties.hotel_section.column.{$tc})}
                                    {else}
                                        <input type="hidden" name="sec_{$tc}[]" value="">
                                    {/if}

                                {/foreach}
                                <input type="hidden" name="sec_exist_id[]" value="{$v.id}">
                                <input type="hidden" name="sec_type_value[]" value="hb">
                                <a href="javascript:;"
                                    onclick="$(this).closest('tr').remove();calculateSecamt('hb');calculateamt('sec_');"
                                    class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                            </td>
                        </tr>

                    {/if}
                {/foreach}
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{$int-2}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                    {$sec_total=$sec_total+$total_amount}
                        <input type="text" id="hb_sectotalamt" value="{$total_amount|string_format:"%.2f"}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

{/if}

{if isset($properties.facility_section)}
    <!-- add particulars label -->
    <script>
        var fs_col='{$properties.facility_section.column|@json_encode}';
    </script>
    <h3 class="form-section">{$properties.facility_section.title}
        <a href="javascript:;" onclick="AddSecRow(fs_col,'fs');" class="btn btn-sm green pull-right"> <i class="fa fa-plus">
            </i> Add new row </a>
    </h3>

    <div class="table-scrollable">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    {$int=0}
                    {foreach from=$properties.facility_section.column key=k item=v}
                        {if $k!='sr_no'}
                            <th class="td-c">
                                {$v}
                            </th>
                            {$int=$int+1}
                        {/if}
                    {/foreach}
                    <th class="td-c">

                    </th>

                </tr>
            </thead>
            <tbody id="new_sec_fs">

                {$total_amount=0}
                {$rate=0}

                {foreach from=$ticket_detail item=v}
                    {$v.qty=$v.units}
                    {if $v.type==4}
                        {$int=1}
                        <tr>
                            {foreach from=$properties.facility_section.column key=k item=tb}
                                {$readonly=''}
                                {if $k!='sr_no'}
                                    <td class="td-c">
                                        {if $k == 'charge' || $k == 'amount' || $k == 'rate' || $k == 'qty' || $k == 'tax_amount' || $k == 'discount' || $k == 'total_amount'}
                                            {if $k == 'amount' || $k == 'rate'}
                                                {$rate=1}
                                            {/if}
                                            {if $rate==1 && $k == 'total_amount'}
                                                {$readonly='readonly'}
                                            {/if}
                                            {if $k == 'total_amount'}
                                                <input onblur="calculateSecamt('fs');" type="text" value="{$v.total}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                                {$total_amount=$total_amount+$v.total}
                                            {else}
                                                <input onblur="calculateSecamt('fs');" type="text" value="{$v.{$k}}" {$readonly} name="sec_{$k}[]"
                                                    class="form-control  pc-input">
                                            {/if}
                                        {elseif $k=='item'}
                                            {$prod_ex=0}
                                            <select required style="width: 100%; " onchange="product_rate(this.value, this,'sec_');"
                                                name="sec_item[]" data-placeholder="Type or Select" class="form-control  productselect">
                                                <option value="">Select Product</option>
                                                {foreach from=$product_list key=$pk item=$vk}
                                                    {if $v.{$k}==$vk}
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
                                        {elseif $k=='gst'}
                                            <select name="sec_gst[]" onchange="calculateamt('sec_');" style="min-width:80px;"
                                                class="form-control ">
                                                <option value="">Select</option>
                                                <option value="0">0%</option>
                                                <option {if $v.{$k}==5} selected="" {/if} value="5">5%</option>
                                                <option {if $v.{$k}==12} selected="" {/if} value="12">12%</option>
                                                <option {if $v.{$k}==18} selected="" {/if} value="18">18%</option>
                                                <option {if $v.{$k}==28} selected="" {/if} value="28">28%</option </select>
                                            {elseif $k=='from_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.booking_date|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='to_date'}
                                                <input type="text" name="sec_{$k}[]" value="{$v.journey_date|date_format:" %d %b %Y"}"
                                                    class="form-control date-picker" style="min-width:110px !important;" autocomplete="off"
                                                    data-date-format="dd M yyyy">
                                            {elseif $k=='from'}
                                                <input type="text" value="{$v.from_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {elseif $k=='to'}
                                                <input type="text" value="{$v.to_station}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {else}
                                                <input type="text" value="{$v.{$k}}" name="sec_{$k}[]" class="form-control  pc-input">
                                            {/if}
                                    </td>
                                    {$int=$int+1}
                                {/if}
                            {/foreach}
                            <td>
                                {foreach from=$travel_col item=tc}
                                    {if isset($properties.facility_section.column.{$tc})}
                                    {else}
                                        <input type="hidden" name="sec_{$tc}[]" value="">
                                    {/if}

                                {/foreach}
                                <input type="hidden" name="sec_exist_id[]" value="{$v.id}">
                                <input type="hidden" name="sec_type_value[]" value="fs">
                                <a href="javascript:;"
                                    onclick="$(this).closest('tr').remove();calculateSecamt('fs');calculateamt('sec_');"
                                    class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                            </td>
                        </tr>

                    {/if}
                {/foreach}
            </tbody>
            <tbody>
                <tr>
                    <td colspan="{$int-2}">
                        <b class="pull-right">Total Rs.</b>
                    </td>
                    <td class="td-c">
                    {$sec_total=$sec_total+$total_amount}
                        <input type="text" id="fs_sectotalamt" value="{$total_amount|string_format:"%.2f"}"
                            name="sec_calculate[]" readonly class="form-control">
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

{/if}


<input type="hidden" id="sec_total" value="{$sec_total}">