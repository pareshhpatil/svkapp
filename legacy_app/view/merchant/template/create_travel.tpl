<!-- add particulars label -->
<div class="form-group">
    <div class="portlet  col-md-12">
        <div class="portlet-body">
        <h4 class="form-section mt-0"> 
        Vehicle booking section
        <div class="pull-right">
        <input type="checkbox" id="issec1" name="sec_vehicle" checked data-size="small" onchange="
                            showDebit('sec1');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
            </div>
        </h4>
        
            <div id="sec1div">
            <div class="form-section mt-0 col-md-4 no-margin no-padding">
            <input type="text" class="form-control" name="sec_vehicle_name" value="VEHICLE BOOKING DETAILS">
            </div>
            <h4 class="form-section mt-0">
            <a  href="#particular"  data-toggle="modal"    class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                <a  onclick="AddparticularRow('');"   class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
            </h4> 
            <div class="table-scrollable">
                <table class="table table-bordered table-hover" id="particular_table">

                </table>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- add particulars label ends -->
<div class="modal fade " id="particular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select display column</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover font14 smalltbl">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Field name
                                        </th>
                                        <th class="td-c">
                                            Display 
                                        </th>
                                        <th class="td-c">
                                            Column name
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$int=1}
                                    {foreach from=$particular_columns item=v}
                                        <tr>
                                            <td class="td-c">
                                                <div>{$v.column_name}</div>
                                            </td>
                                            <td class="td-c">
                                                {if $v.is_mandatory}
                                                    <input name="particular_col[]" type="checkbox" style="pointer-events: none;" checked  id="pc_{$int}" value="{$v.system_col_name}" />
                                                {else}
                                                    <input name="particular_col[]" type="checkbox" {if isset($particular_col.{$v.system_col_name})}checked{/if}  id="pc_{$int}" value="{$v.system_col_name}" />
                                                {/if}
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" name="pc_{$v.system_col_name}" class="form-control input-sm" id="pc_name_{$int}" value="{if isset($particular_col.{$v.system_col_name})}{$particular_col.{$v.system_col_name}}{else}{$v.column_name}{/if}">
                                            </td>
                                        </tr>
                                        {$int=$int+1}
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="drawParticularTable();" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    drawParticularTable('Particular Total');
</script>


<div class="portlet  col-md-12">
        <div class="portlet-body">
        <h4 class="form-section mt-0"> 
        Travel booking section
        <div class="pull-right">
        <input type="checkbox" id="issec2" name="sec_travel" checked data-size="small" onchange="
                            showDebit('sec2');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
            </div>
        </h4>
        <div id="sec2div">
            <div class="form-section mt-0 col-md-4 no-margin no-padding">
            <input type="text" class="form-control" name="sec_travel_name" value="TRAVEL BOOKING DETAILS"/>
            </div>
            <a  href="#sectravel"  data-toggle="modal"    class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover form-group form-md-line-input" id="tb_table">
                    <thead><tr><th class="td-c">#</th><th class="td-c">Booking date</th><th class="td-c">Journey date</th><th class="td-c">Name</th><th class="td-c">Type</th><th class="td-c">From</th><th class="td-c">To</th><th class="td-c">Amt</th><th class="td-c">Ser. ch.</th><th class="td-c">Total</th></tr></thead>
                    <tbody >
                        <tr>
                            <td class="td-c">
                                X
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                               xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            
                        </tr>
                        <tr >
                            <td colspan="9" >
                                <b class="pull-right">Total Rs.</b>
                        </td>
                        <td class="td-c"> xxxx</td>
                    </tr>
                    </tbody>
                    
                </table>
            </div>
            
    
            <div class="form-section mt-0 col-md-4 no-margin no-padding">
            <input type="text" class="form-control" name="sec_travel_cancel_name"  value="TRAVEL BOOKING CANCELLATION">
            </div>
            <a  href="#sectravelcancel"  data-toggle="modal"    class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover form-group form-md-line-input" id="tc_table">
                    <thead><tr><th class="td-c">#</th><th class="td-c">Booking date</th><th class="td-c">Journey date</th><th class="td-c">Name</th><th class="td-c">Type</th><th class="td-c">From</th><th class="td-c">To</th><th class="td-c">Amt</th><th class="td-c">Ser. ch.</th><th class="td-c">Total</th></tr></thead>
                    <tbody >
                        <tr>
                            <td class="td-c">
                                X
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                               xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            
                        </tr>
                        <tr >
                            <td colspan="9" >
                                <b class="pull-right">Total Rs.</b>
                        </td>
                        <td class="td-c"> xxxx</td>
                    </tr>
                    </tbody>
                    
                </table>
            </div>
            </div>
    </div>
    </div>
    <!-- add particulars label ends -->


<div class="portlet  col-md-12">
        <div class="portlet-body">
        <h4 class="form-section mt-0"> 
        Hotel booking section
        <div class="pull-right">
        <input type="checkbox" id="issec3" name="sec_hotel" checked data-size="small" onchange="
                            showDebit('sec3');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
            </div>
        </h4>
        <a  href="#sechotel"  data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
        <div id="sec3div">
            <div class="form-section mt-0 col-md-4 no-margin no-padding">
            <input type="text" class="form-control" name="sec_hotel_name" value="HOTEL BOOKING DETAILS">
            </div>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover form-group form-md-line-input" id="hb_table">
                    <thead>
                        <tr>
                            <th class="td-c">
                            <input value="#" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c">
                            <input value="Description" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c">
                            <input value="From Date" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c">
                            <input value="To Date" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c">
                            <input value="Units" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c"> 
                            <input value="Rate" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c"> 
                            <input value="GST" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            <th class="td-c"> 
                            <input value="Absolute cost" class="form-control  input-sm text-center bb-0"   name="time_period_label" placeholder="Add column name"> 
                            </th>
                            
                            
                        </tr>
                    </thead>
                    <tbody >
                        <tr>
                            <td class="td-c">
                                X
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            <td class="td-c">
                                xxxxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                           
                            
                        </tr>
                        <tr >
                            <td colspan="7" >
                                <b class="pull-right">Total Rs.</b>
                        </td>
                        <td class="td-c"> xxxx</td>
                    </tr>
                    </tbody>
                    
                </table>
            </div>
            </div>
    </div>
    </div>

    <div class="portlet  col-md-12">
        <div class="portlet-body">
        <h4 class="form-section mt-0"> 
        Facilities section
        <div class="pull-right">
        <input type="checkbox" id="issec4"  name="sec_facility" checked data-size="small" onchange="showDebit('sec4');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
            </div>
        </h4>
        <a  href="#secfacility"  data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
        <div id="sec4div">
            <div class="form-section mt-0 col-md-4 no-margin no-padding">
            <input type="text" name="sec_facility_name"  class="form-control" value="FACILITIES DETAILS">
            </div>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover form-group form-md-line-input" id="fs_table">
                    <thead><tr><th class="td-c">#</th><th class="td-c">Description</th><th class="td-c">Date</th><th class="td-c">Units</th><th class="td-c">Rate</th><th class="td-c">GST</th><th class="td-c">Absolute cost</th></tr></thead>
                    <tbody id="">
                        <tr>
                            <td class="td-c">
                                X
                            </td>
                            <td class="td-c">
                                xxxx
                            </td>
                            
                            <td class="td-c">
                                xxxxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                            <td class="td-c"> 
                                xxxx
                            </td>
                           
                            
                        </tr>
                        <tr >
                            <td colspan="6" >
                                <b class="pull-right">Total Rs.</b>
                        </td>
                        <td class="td-c"> xxxx</td>
                    </tr>
                    </tbody>
                    
                </table>
            </div>
            </div>
    </div>
    </div>

<div class="modal fade " id="sectravel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Select display column</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover font14 smalltbl">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Field name
                                    </th>
                                    <th class="td-c">
                                        Display 
                                    </th>
                                    <th class="td-c">
                                        Column name
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {$int=1}
                                {foreach from=$travel_particular_columns item=v}
                                    <tr>
                                        <td class="td-c">
                                            <div>{$v.column_name}</div>
                                        </td>
                                        <td class="td-c">
                                            {if $v.is_mandatory}
                                                <input name="tb_col[]" type="checkbox" style="pointer-events: none;" checked  id="tb_{$int}" value="{$v.system_col_name}" />
                                            {else}
                                                <input name="tb_col[]" type="checkbox" {if $v.is_default==1} checked {/if} id="tb_{$int}" value="{$v.system_col_name}" />
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <input type="text" maxlength="45" name="tb_{$v.system_col_name}" class="form-control input-sm" id="tb_name_{$int}" value="{$v.column_name}">
                                        </td>
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="drawTravelTable('tb');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
        </div>
    </div>
</div>
</div>


<div class="modal fade " id="sectravelcancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Select display column</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover font14 smalltbl">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Field name
                                    </th>
                                    <th class="td-c">
                                        Display 
                                    </th>
                                    <th class="td-c">
                                        Column name
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {$int=1}
                                {foreach from=$travel_particular_columns item=v}
                                    <tr>
                                        <td class="td-c">
                                            <div>{$v.column_name}</div>
                                        </td>
                                        <td class="td-c">
                                            {if $v.is_mandatory}
                                                <input name="tc_col[]" type="checkbox" style="pointer-events: none;" checked  id="tc_{$int}" value="{$v.system_col_name}" />
                                            {else}
                                                <input name="tc_col[]" type="checkbox" {if $v.is_default==1} checked {/if} id="tc_{$int}" value="{$v.system_col_name}" />
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <input type="text" maxlength="45" name="tc_{$v.system_col_name}" class="form-control input-sm" id="tc_name_{$int}" value="{$v.column_name}">
                                        </td>
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="drawTravelTable('tc');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>


<div class="modal fade " id="sechotel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Select display column</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover font14 smalltbl">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Field name
                                    </th>
                                    <th class="td-c">
                                        Display 
                                    </th>
                                    <th class="td-c">
                                        Column name
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {$int=1}
                                {foreach from=$hotel_particular_columns item=v}
                                    <tr>
                                        <td class="td-c">
                                            <div>{$v.column_name}</div>
                                        </td>
                                        <td class="td-c">
                                            {if $v.is_mandatory}
                                                <input name="hb_col[]" type="checkbox" style="pointer-events: none;" checked  id="hb_{$int}" value="{$v.system_col_name}" />
                                            {else}
                                                <input name="hb_col[]" type="checkbox" {if $v.is_default==1} checked {/if} id="hb_{$int}" value="{$v.system_col_name}" />
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <input type="text" maxlength="45" name="hb_{$v.system_col_name}" class="form-control input-sm" id="hb_name_{$int}" value="{$v.column_name}">
                                        </td>
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="drawTravelTable('hb');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<div class="modal fade " id="secfacility" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Select display column</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover font14 smalltbl">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Field name
                                    </th>
                                    <th class="td-c">
                                        Display 
                                    </th>
                                    <th class="td-c">
                                        Column name
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {$int=1}
                                {foreach from=$facility_particular_columns item=v}
                                    <tr>
                                        <td class="td-c">
                                            <div>{$v.column_name}</div>
                                        </td>
                                        <td class="td-c">
                                            {if $v.is_mandatory}
                                                <input name="fs_col[]" type="checkbox" style="pointer-events: none;" checked  id="fs_{$int}" value="{$v.system_col_name}" />
                                            {else}
                                                <input name="fs_col[]" type="checkbox" {if $v.is_default==1} checked {/if} id="fs_{$int}" value="{$v.system_col_name}" />
                                            {/if}
                                        </td>
                                        <td class="td-c">
                                            <input type="text" maxlength="45" name="fs_{$v.system_col_name}" class="form-control input-sm" id="fs_name_{$int}" value="{$v.column_name}">
                                        </td>
                                    </tr>
                                    {$int=$int+1}
                                {/foreach}
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="drawTravelTable('fs');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>