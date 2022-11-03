
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
                                        <div class="form-group">
                                            <label class="help-block">Bill date</label>
                                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="{$session_date_format}"  name="date_range" type="text" value="{$from_date} - {$to_date}" placeholder="From date"/>
                                        </div>

                                        {if !empty($billing_profile_list)}
                                            {if count($billing_profile_list)>1}
                                                <div class="form-group">
                                                    <label class="help-block">Billing profile</label>
                                                    <select class="form-control rpt-date" data-placeholder="Billing profile" name="billing_profile_id">
                                                        <option value="0">Select..</option>
                                                        {foreach from=$billing_profile_list item=v}
                                                            {if {$billing_profile_id==$v.id}}
                                                                <option selected value="{$v.id}" selected>{$v.state} - {$v.gst_number}</option>
                                                            {else}
                                                                <option value="{$v.id}">{$v.state} - {$v.gst_number}</option>
                                                            {/if}
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            {/if}
                                        {/if}
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
                    <form action="/merchant/gst/savegstinvoice" method="post">
                        <div class="table-responsive ">
                            <table class="table table-striped  table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>
                                            <label><input type="checkbox" onchange="checkAll(this.checked);" /><b>All</b></label>
                                        </th>
                                        <th class="td-c">
                                            Invoice No
                                        </th>
                                        <th>
                                            Customer code
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Cycle name
                                        </th>

                                        <th>
                                            Sent Date
                                        </th>
                                        <th>
                                            Billing Date
                                        </th>
                                        <th>
                                            Due Date
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Amount
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="saveBtn form-group">        
                            <div class="row">
                                <div class="col-md-12  mt-2">


                                    <div class="col-md-6" ></div>

                                    <div class="col-md-6 no-padding" >
                                        <button type="submit" class="btn blue pull-right" >Move Invoices to GST</button>
                                        
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
