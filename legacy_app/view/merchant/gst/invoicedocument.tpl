
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
        <div class="col-md-12">
            {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>  {$success}
                <p>
                    <a href="/merchant/gst/gstr1upload"  class="btn btn-sm blue mt-1"> Proceed to prepare GSTR1 </a>
                </p>
            </div> 
        {/if}
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet ">
                <div class="portlet-body">
                    <form action="" method="post">
                        <h3 class="form-section mt-2">
                            <a href="javascript:;" onclick="AddInvoiceDocument();" class="btn btn-sm green pull-right mb-1"> <i class="fa fa-plus"> </i> Add new row </a></h3>
                        <div class="table-scrollable">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="td-c">
                                            Type
                                        </th>
                                        <th class="td-c">
                                            From serial number
                                        </th>
                                        <th class="td-c">
                                            To serial number
                                        </th>
                                        <th class="td-c">
                                            Total number
                                        </th>
                                        <th class="td-c">
                                            Cancelled
                                        </th>
                                        <th class="td-c">
                                            Issued
                                        </th>
                                        <th class="td-c">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="new_doc">
                                    {foreach from=$invoice_seq key=kk item=v}
                                        <tr>
                                            <td class="td-c">
                                                <select class="form-control" name="docNum[]">
                                                    <option {if $v.docNum==1} selected="" {/if} value="1">Invoices for outward supply</option>
                                                    <option {if $v.docNum==5} selected="" {/if} value="5">Credit Note</option>
                                                    <option {if $v.docNum==4} selected="" {/if} value="4">Debit Note</option>
                                                </select>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" class="form-control" value="{$v.from}" name="from[]" />
                                            </td>
                                            <td class="td-c">
                                                <input type="text" class="form-control" value="{$v.to}" name="to[]" />
                                            </td>
                                            <td class="td-c">
                                                <input type="text" class="form-control" value="{$v.total_no}" name="total[]" />
                                            </td>
                                            <td class="td-c">
                                                <input type="text" class="form-control" value="{$v.cancel}" name="cancel[]" />
                                            </td>
                                            <td class="td-c">
                                                <input type="text" class="form-control" value="{$v.netIssue}" name="netissued[]" />
                                            </td>
                                            <td class="td-c">
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="saveBtn form-group">        
                            <div class="row">
                                <div class="col-md-12  mt-2">


                                    <div class="col-md-6" ></div>

                                    <div class="col-md-6 no-padding" >
                                        <input type="submit" name="submit" class="btn blue pull-right" value="Save document"  >
                                        
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

