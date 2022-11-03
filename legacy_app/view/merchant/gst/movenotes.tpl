
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->

    <div class="row">

        {if isset($haserrors)}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Error!</strong>  {$haserrors}
            </div> 
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
            </div> 
        {/if}

        <!-- BEGIN PORTLET-->



        <!-- END PORTLET-->
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="row no-margin">
                <div class="col-md-12  no-padding">
                    <!-- BEGIN PORTLET-->

                    <div class="portlet">

                        <div class="portlet-body" >
                            <div class="row mb-3" >
                                <div class="col-md-12" >
                                    <form class="form-inline" role="form" action="" method="post">
                                        <div class="form-group  mr-2">
                                            <label>Month</label>
                                            <input class="form-control date-picker" style="width: 100%;" tabindex="-1" aria-hidden="true"  type="text" required  value="{$from_date}" name="from_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="From date"/>
                                        </div>


                                        <div class="form-group  mr-2">
                                            <label>&nbsp;</label>
                                            <input type="submit" style="width: 100%;" tabindex="-1" class="btn blue" value="Search">                                                        
                                        </div>


                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- END PORTLET-->
                </div>
            </div>

            <div class="portlet ">
                <div class="portlet-body">
                    <form action="/merchant/gst/savegstnotes" method="post">
                        <div class="table-responsive ">
                            <table class="table table-striped  table-hover" id="table-no-export">
                                <thead>
                                    <tr>
                                        <th>
                                            <label><input type="checkbox" onchange="checkAll(this.checked);" /><b>All</b></label>
                                        </th>
                                        <th class="td-c">
                                            Credit note no
                                        </th>
                                        <th class="td-c">
                                            Date
                                        </th>
                                        <th class="td-c">
                                            Invoice No
                                        </th>
                                        <th class="td-c">
                                            Bill date
                                        </th>
                                        <th>
                                            Customer code
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            State
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$notes item=v}
                                        <tr>
                                            <td><input name="customer_check[]" type="checkbox" value="{$v.id}"></td>
                                            <td class="td-c">
                                                {$v.credit_debit_no}
                                            </td>
                                            <td class="td-c">
                                                {$v.date|date_format:"%d-%m-%Y"}
                                            </td>
                                            <td class="td-c">
                                                {$v.invoice_no}
                                            </td>
                                            <td class="td-c">
                                                {$v.bill_date|date_format:"%d-%m-%Y"}
                                            </td>
                                            <td class="td-c">
                                                {$v.customer_code}
                                            </td>
                                            <td class="td-c">
                                                {$v.first_name} {$v.last_name}
                                            </td>
                                            <td class="td-c">
                                                {$v.state}
                                            </td>
                                            <td class="td-c">
                                                {$v.total_amount}
                                            </td>


                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="saveBtn form-group">        
                            <div class="row">
                                <div class="col-md-12  mt-2">


                                    <div class="col-md-4" ></div>

                                    <div class="col-md-8 no-padding" >
                                        <button type="submit" class="btn blue pull-right" >Move Invoices to GST</button>
                                        <div class="form-group pull-right mr-2">
                                            <select name="gst_number" required="" class="form-control full-width-div pull-right">    
                                                <option value="">Select GST number</option>
                                                {foreach from=$gst_list item=v}
                                                    <option value="{$v.gstin}" >{$v.company_name} - {$v.gstin}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
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
