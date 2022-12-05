
{if isset($properties.vehicle_section)}
<!-- add particulars label -->
<h3 class="form-section">{$properties.vehicle_section.title}
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
{/if}

{if isset($properties.travel_section)}
<!-- add particulars label -->
<script>
var tb_col='{$properties.travel_section.column|@json_encode}';
</script>
<h3 class="form-section">{$properties.travel_section.title}
    <a href="javascript:;" onclick="AddSecRow(tb_col,'tb');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" >
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
            
        </tbody>
        <tbody>
            <tr >
                <td colspan="{$int-1}" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c">
                 <input type="text" id="tb_sectotalamt" name="sec_calculate[]" readonly  class="form-control" ></td>
            </tr>
        </tbody>

    </table>
    <script>
        AddSecRow('{$properties.travel_section.column|@json_encode}','tb');
    </script>

</div>

<script>
var tc_col='{$properties.travel_cancel_section.column|@json_encode}';
</script>
<h3 class="form-section">{$properties.travel_cancel_section.title}
    <a href="javascript:;" onclick="AddSecRow(tc_col,'tc');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" >
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
            
        </tbody>
        <tbody>
            <tr >
                <td colspan="{$int-1}" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c"> <input type="text" id="tc_sectotalamt" name="sec_calculate[]" readonly  class="form-control input-sm" ></td>
            </tr>
        </tbody>

    </table>
</div>
<script>
        AddSecRow('{$properties.travel_cancel_section.column|@json_encode}','tc');
</script>
<!-- add particulars label ends -->
{/if}


{if isset($properties.hotel_section)}
<!-- add particulars label -->
<script>
var hb_col='{$properties.hotel_section.column|@json_encode}';
</script>
<h3 class="form-section">{$properties.hotel_section.title}
    <a href="javascript:;" onclick="AddSecRow(hb_col,'hb');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" >
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
            
        </tbody>
        <tbody>
            <tr >
                <td colspan="{$int-1}" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c">
                 <input type="text" id="hb_sectotalamt" name="sec_calculate[]" readonly  class="form-control" ></td>
            </tr>
        </tbody>

    </table>
    <script>
        AddSecRow('{$properties.hotel_section.column|@json_encode}','hb');
    </script>

</div>

{/if}


{if isset($properties.facility_section)}
<!-- add particulars label -->
<script>
var fs_col='{$properties.facility_section.column|@json_encode}';
</script>
<h3 class="form-section">{$properties.facility_section.title}
    <a href="javascript:;" onclick="AddSecRow(fs_col,'fs');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a></h3>

<div class="table-scrollable">
    <table class="table table-bordered table-hover" >
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
            
        </tbody>
        <tbody>
            <tr >
                <td colspan="{$int-1}" >
                    <b class="pull-right">Total Rs.</b>
                </td>
                <td class="td-c">
                 <input type="text" id="fs_sectotalamt" name="sec_calculate[]" readonly  class="form-control" ></td>
            </tr>
        </tbody>

    </table>
    <script>
        AddSecRow('{$properties.facility_section.column|@json_encode}','fs');
    </script>

</div>

{/if}
<input type="hidden" id="sec_total" value="0">