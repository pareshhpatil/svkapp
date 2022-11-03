<!-- add particulars label -->
<div class="form-group">
    <div class="portlet col-md-12 ">
        <div class="portlet-body pt-0">
            <h4 class="form-section">Add particulars
            <a  href="#particular"  data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
            <a  onclick="AddparticularRow('');"  class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
            </h4>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover" id="particular_table">

                </table>
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
                                                <input type="text" name="pc_{$v.system_col_name}" class="form-control input-sm" id="pc_name_{$int}" value="{if isset($particular_col.{$v.system_col_name})}{$particular_col.{$v.system_col_name}}{else}{$v.column_name}{/if}">
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
                <button type="button" onclick="drawParticularTable('{$info.particular_total}');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    drawParticularTable('{$info.particular_total}');
    {foreach from=$default_particular item=v}
    AddparticularRow('{$v}');
    {/foreach}
</script>