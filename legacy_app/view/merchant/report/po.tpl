
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <!-- END SEARCH CONTENT-->
        <div class="col-md-12">

            <!-- BEGIN PORTLET-->
            <div class="portlet">

                <div class="portlet-body ">
                    <form class="form-inline" method="post" role="form">
                        <div class="form-group">
                            <label class="help-block">PO date</label>
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}" name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                        </div>

                        <div class="form-group">
                            <label class="help-block">Category</label>
                            <select class="form-control "  id="category" data-placeholder="Select category" required="" name="category_id" >
                                <option value="0">Select..</option>
                                {foreach from=$category item=v}
                                    {if $category_id==$v.id}
                                        <option selected="" value="{$v.id}">{$v.name}</option>
                                    {else}
                                        <option value="{$v.id}">{$v.name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="help-block">Department</label>
                            <select class="form-control " id="department" data-placeholder="Select department" required="" name="department_id" >
                                <option value="0">Select..</option>
                                {foreach from=$department item=v}
                                    {if $department_id==$v.id}
                                        <option selected="" value="{$v.id}">{$v.name}</option>
                                    {else}
                                        <option value="{$v.id}">{$v.name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                        <input type="hidden" name="payment_status">
                        <div class="form-group">
                            <label class="help-block">Column name</label>
                            <select multiple id="column_name" data-placeholder="Column name" name="column_name[]">
                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select)} 
                                            <option selected value="{$v.column_name}" >{$v.display_name}</option>
                                        {else}
                                            <option value="{$v.column_name}">{$v.display_name}</option>
                                        {/if}
                                    {/if}
                                {/foreach}
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="help-block">&nbsp;</label>
                            <button type="submit" class="btn  blue">Search</button>
                            <button type="submit" name="export" class="btn  green" title="Download report in excel format">Export</button>


                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div> 

        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-small" >
                        <thead>
                            <tr>

                                <th class="td-c">
                                    PO ID
                                </th>
                                <th class="td-c">
                                    Date
                                </th>

                                <th class="td-c">
                                    PO No
                                </th>
                                <th class="td-c">
                                    Vendor name
                                </th>
                                <th class="td-c">
                                    Category
                                </th>
                                <th class="td-c">
                                    Department
                                </th>
                                <th class="td-c">
                                    PO date
                                </th>
                                <th class="td-c">
                                    Ref no
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>

                                {foreach from=$column_list item=v}
                                    {if $v.column_name!=''}
                                        {if in_array($v.column_name, $column_select)} 
                                            {if $v.display_name=='GST'}
                                                <th class="td-c">
                                                    SGST
                                                </th>
                                                <th class="td-c">
                                                    CGST
                                                </th>
                                                <th class="td-c">
                                                    IGST
                                                </th>
                                            {else}
                                                <th class="td-c">
                                                    {$v.display_name}
                                                </th>
                                            {/if}
                                        {/if}
                                    {/if}
                                {/foreach}
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$reportlist item=v}
                                <tr>
                                    <td class="td-c">
                                        {$v.expense_id}
                                    </td>
                                    <td class="td-c">
                                        {$v.created_date}
                                    </td>
                                    <td class="td-c">
                                        {$v.expense_no}
                                    </td>
                                    <td class="td-c">
                                        {$v.vendor_name}
                                    </td>
                                    <td class="td-c">
                                        {$v.category}
                                    </td>
                                    <td class="td-c">
                                        {$v.department}
                                    </td>
                                    <td class="td-c">
                                        {$v.bill_date|date_format:"%d/%b/%y"}
                                    </td>
                                    <td class="td-c">
                                        {$v.invoice_no}
                                    </td>
                                    <td class="td-c">
                                        {$v.total_amount}
                                    </td>
                                    {foreach from=$column_select item=cv}
                                        {if $cv=='gst'}
                                            <td class="td-c">
                                                {$v.sgst_amount}
                                            </td>
                                            <td class="td-c">
                                                {$v.cgst_amount}
                                            </td>
                                            <td class="td-c">
                                                {$v.igst_amount}
                                            </td>
                                        {else}
                                            <td class="td-c">
                                                {$v.{$cv}}
                                            </td>
                                        {/if}

                                    {/foreach}
                                </tr>
                            {/foreach}
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

