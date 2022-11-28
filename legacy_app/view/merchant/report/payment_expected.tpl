
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                
                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">to Date</label>
                            <input class="form-control form-control-inline  date-picker rpt-date"  autocomplete="off" data-date-format="{$session_date_format}" name="to_date" type="text" value="{$to_date}" placeholder="To date"/>
                            <input  autocomplete="off" data-date-format="dd M yyyy"  name="from_date" type="hidden" value="{$from_date}"/>

                        </div>
                        <div class="form-group">
                            <label class="help-block">Report by</label>
                            <select class="form-control " data-placeholder="Aging interval" name="aging_by">
                                <option value="last_update_date">Bill date</option>
                                <option value="due_date" {if {$aging_by_selected == 'due_date'}} selected {/if}>Due date</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Intervals</label>
                            <select class="form-control " data-placeholder="Aging interval" name="aging">
                                {$int = 4}
                                {$col_span= 4}
                                {while $int < 11}
                                    {if {$aging_selected} == {$int}}
                                        {$col_span=$aging_selected}
                                        <option value="{$int}" selected>{$int}</option>
                                    {else}
                                        <option value="{$int}" >{$int}</option>
                                    {/if}
                                    {$int++}
                                {/while}
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="help-block">Intervals of</label>
                            <select class="form-control " data-placeholder="Aging interval" name="interval">
                                {$int = 15}
                                {while $int < 120}
                                    {if {$interval} == {$int}}
                                        <option value="{$int}" selected>{$int}</option>
                                    {else}
                                        <option value="{$int}" >{$int}</option>
                                    {/if}
                                    {$int = $int +15}
                                {/while}
                            </select> <label>&nbsp;Days &nbsp;&nbsp;</label>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>

                        </div>
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>

        <!-- END SEARCH CONTENT-->

        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-sum-ellipsis-large">
                        <thead>
                            <tr>
                                {foreach from=$display_columns item=v}
                                    <th>{$v}</th>
                                    {/foreach}

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="{$col_span+5}" style=""></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    {foreach from=$column item=v2}
                                        <td>{$v.{$v2}}</td>
                                        {/foreach}
                                </tr>

                            {/foreach}

                        </tbody>

                        </thead>
                        <tbody>



                        </tbody>
                    </table>
                </div>
            </div>

            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>

    </div>

    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->

