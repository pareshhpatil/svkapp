
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


        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        {if isset($error)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$success}
            </div> 
        {/if}
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" method="post" action="" role="form">
                        <div class="form-group">
                            <select required="" class="form-control" name="gstin">
                                <option value="">Select GSTIN</option>
                                {foreach from=$data key=k item=v}
                                    <option {if $post.gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-inline  rpt-date" id="daterange"  autocomplete="off" data-date-format="dd M yyyy"  name="date_range" type="text" value="{if isset($post.from_date)}{$post.from_date} - {$post.to_date}{/if}" placeholder="Date"/>
                        </div>

                        <div class="form-group">
                            <input type="submit" name="submit" class="btn blue" value="Search" />
                            <input type="submit" name="export" class="btn green" value="Excel export" />
                        </div>
                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="">
                        <thead>
                            <tr>
                               <th class="td-c">
                                    HSN
                                </th>
                                <th class="td-c">
                                    Description
                                </th>
                                <th class="td-c">
                                    UQC
                                </th>
                                <th class="td-c">
                                    Total quantity
                                </th>
                                <th class="td-c">
                                    Total value
                                </th>
                                <th class="td-c">
                                    Taxable value
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
                               
                               

                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            {foreach from=$list key=kk item=v}
                                <tr>
                                    
                                    <td class="td-c">
                                        {$v.hsn}
                                    </td>
                                    <td class="td-c">
                                        {$v.description}
                                    </td>
                                    <td class="td-c">
                                        {$v.uqc}
                                    </td>
                                    <td class="td-c">
                                        {$v.total_quantity}
                                    </td>
                                    <td class="td-c">
                                        {$v.total_value}
                                    </td>
                                    <td class="td-c">
                                        {$v.taxable_value}
                                    </td>
                                    <td class="td-c">
                                        {$v.IGST}
                                    </td>
                                    <td class="td-c">
                                        {$v.CGST}
                                    </td>
                                    <td class="td-c">
                                        {$v.SGST}
                                    </td>
                                    
                                </tr>

                            {/foreach}
                        </form>
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



<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete GSTR1 records</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to delete this record?
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
<div class="modal fade" id="sendinvoice" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Approve Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to approve these invoices?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="sendanchor" class="btn blue">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>