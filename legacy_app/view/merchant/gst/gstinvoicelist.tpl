<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <div class="btn-group pull-right">
            <button id="btnGroupVerticalDrop7" type="button" class="btn btn green dropdown-toggle mb-1"
                data-toggle="dropdown" aria-expanded="true">
                <i class="fa fa-file-code-o"></i> Upload data <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop7">
                <li>
                    <a href="/merchant/gst/bulkupload">
                        Upload invoices</a>
                </li>
                <li>
                    <a href="/merchant/gst/bulkupload/stock">
                        Upload stock transfer</a>
                </li>
                <li>
                    <a href="/merchant/gst/moveinvoices">
                        Move Swipez invoices</a>
                </li>
                <li>
                    <a href="/merchant/gst/movenotes">
                        Move Swipez Credit/Debit notes</a>
                </li>

            </ul>
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->

    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$k} - {$v}.</p>
                    {/foreach}
                </div>
            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong> {$success}
            </div>
        {/if}
    </div>


    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Search Invoices</h5>
                            <form class="form-inline" role="form" action="" method="post">
                                <div class="form-group  mr-2">
                                    <select name="gst_number" required="" class="form-control">
                                        <option value="">Select GST number</option>
                                        {foreach from=$gst_list item=v}
                                            <option {if $gst_number==$v.gstin} selected {/if} value="{$v.gstin}">
                                                {$v.company_name} - {$v.gstin}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group  mr-2">
                                    <select required="" name="month" class="form-control">
                                        <option value="">Select Month</option>
                                        {foreach from=$months key=k item=v}
                                            <option {if $k==$month} selected {$monthsel=$v}{/if} value="{$k}">{$v}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group  mr-2">
                                    <select required="" name="year" class="form-control">
                                        <option value="">Select Year</option>
                                        {foreach from=$years item=v}
                                            <option {if $v==$year} selected {/if} value="{$v}">{$v}</option>
                                        {/foreach}
                                    </select>
                                </div>

                                <div class="form-group  mr-2">
                                    <input type="submit" style="width: 100%;" tabindex="-1" class="btn blue"
                                        value="Search">
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END PORTLET-->

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">



            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1_1" data-toggle="tab">
                            Sales invoice list </a>
                    </li>
                    <li>
                        <a href="#tab_1_2" data-toggle="tab">
                            Expense invoice list </a>
                    </li>
                    <li>
                        <a href="#tab_1_3" data-toggle="tab">
                            Note list </a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab_1_1">
                        <div class="portlet ">
                            <div class="portlet-body">
                                <table class="table table-striped  table-hover table-no-export_class">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Invoice Number
                                            </th>
                                            <th class="td-c">
                                                Invoice date
                                            </th>
                                            <th class="td-c">
                                                Type
                                            </th>
                                            <th class="td-c">
                                                Supply Type
                                            </th>
                                            <th class="td-c">
                                                Supply State
                                            </th>
                                            <th class="td-c">
                                                Amount
                                            </th>
                                            <th class="td-c">
                                                Customer GST
                                            </th>
                                            <th class="td-c">

                                            </th>


                                        </tr>

                                    </thead>
                                    <tbody>
                                        {foreach from=$data item=v}
                                            {if $v.dty=='RI'}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.inum}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.pdt|date_format:"%d-%m-%Y"}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.invTyp}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.splyTy}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.state}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.val}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.ctin}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="/merchant/gst/viewinvoice/{$v.link}" target="_BLANK"
                                                            class="btn btn-xs blue"> View </a>
                                                        <a href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deletestaginginvoice/{$v.id}/listinvoice'"
                                                            data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                    </td>

                                                </tr>
                                            {/if}
                                        {/foreach}
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="tab_1_2">
                        <div class="portlet ">
                            <div class="portlet-body">
                                <table class="table table-striped  table-hover table-no-export_class">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Expense number
                                            </th>

                                            <th class="td-c">
                                                Invoice number
                                            </th>
                                            <th class="td-c">
                                                Invoice date
                                            </th>
                                            <th class="td-c">
                                                Vendor name
                                            </th>
                                            <th class="td-c">
                                                GST Number
                                            </th>
                                            <th class="td-c">
                                                IGST
                                            </th>
                                            <th class="td-c">
                                                CGST
                                            </th>
                                            <th class="td-c">
                                                SGST
                                            </th>
                                            <th class="td-c">
                                                Total amount
                                            </th>
                                            <th class="td-c">

                                            </th>


                                        </tr>

                                    </thead>
                                    <tbody>
                                        {foreach from=$expense_data item=v}
                                            <tr>
                                                <td class="td-c">
                                                    {$v.expense_no}
                                                </td>

                                                <td class="td-c">
                                                    {$v.invoice_no}
                                                </td>
                                                <td class="td-c">
                                                    {$v.bill_date|date_format:"%d-%m-%Y"}
                                                </td>
                                                <td class="td-c">
                                                    {$v.vendor_name}
                                                </td>
                                                <td class="td-c">
                                                    {$v.gst_number}
                                                </td>
                                                <td class="td-c">
                                                    {$v.igst_amount}
                                                </td>
                                                <td class="td-c">
                                                    {$v.cgst_amount}
                                                </td>
                                                <td class="td-c">
                                                    {$v.sgst_amount}
                                                </td>
                                                <td class="td-c">
                                                    {$v.total_amount}
                                                </td>

                                                <td class="td-c">
                                                    <a href="#basic"
                                                        onclick="document.getElementById('deleteanchor').href = '/merchant/expense/expense/delete/{$v.link}'"
                                                        data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                </td>

                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab_1_3">
                        <div class="portlet ">

                            <div class="portlet-body">
                                <table class="table table-striped  table-hover table-no-export_class">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Note number
                                            </th>
                                            <th class="td-c">
                                                Note type
                                            </th>
                                            <th class="td-c">
                                                Note date
                                            </th>
                                            <th class="td-c">
                                                Invoice number
                                            </th>
                                            <th class="td-c">
                                                Invoice date
                                            </th>
                                            <th class="td-c">
                                                Type
                                            </th>
                                            <th class="td-c">
                                                Supply type
                                            </th>
                                            <th class="td-c">
                                                Supply state
                                            </th>
                                            <th class="td-c">
                                                Amount
                                            </th>
                                            <th class="td-c">
                                                Customer GST
                                            </th>
                                            <th class="td-c">

                                            </th>


                                        </tr>

                                    </thead>
                                    <tbody>
                                        {foreach from=$data item=v}
                                            {if $v.dty=='C' || $v.dty=='D'}
                                                <tr>
                                                    <td class="td-c">
                                                        {$v.ntNum}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.dty=='C'}
                                                            Credit
                                                        {else}
                                                            Debit
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.ntDt|date_format:"%d-%m-%Y"}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.inum}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.pdt|date_format:"%d-%m-%Y"}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.invTyp}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.splyTy}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.state}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.val}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.ctin}
                                                    </td>
                                                    <td class="td-c">
                                                        <a href="/merchant/gst/viewinvoice/{$v.link}" target="_BLANK"
                                                            class="btn btn-xs blue"> View </a>

                                                        <a href="#basic"
                                                            onclick="document.getElementById('deleteanchor').href = '/merchant/gst/deletestaginginvoice/{$v.id}/listinvoice'"
                                                            data-toggle="modal" class="btn btn-xs red"> Delete </a>
                                                    </td>

                                                </tr>
                                            {/if}
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>





            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this invoice in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>