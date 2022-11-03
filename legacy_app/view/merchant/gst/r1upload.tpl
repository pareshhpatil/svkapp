
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
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong></strong>{$error}
            </div> 
        {/if}
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->

            <div class="portlet">
                <div class="portlet-body">

                    <form class="form-inline" method="post" action="" role="form">
                        <div class="form-group" >
                            <select required="" style="max-width: 200px;" class="form-control" name="gstin">
                                <option>Select GSTIN</option>
                                {foreach from=$data key=k item=v}
                                    <option {if $post.gstin==$v.gstin} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                {/foreach}
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-inline date-picker" style="max-width: 150px;" type="text" required  value="{$post.from_date}" name="from_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Bill date from"/>
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group">
                            <input class="form-control form-control-inline date-picker" style="max-width: 150px;" onchange="setFilePeriod(this.value);" id="to_date" type="text" required value="{$post.to_date}" name="to_date"  autocomplete="off" data-date-format="dd M yyyy"  placeholder="Bill date to"/>
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group">
                            <select required="" id="fpmonth" name="month" class="form-control" >
                                <option>File Period Month</option>
                                {foreach from=$months key=k item=v}

                                    <option {if $post.month==$k} selected {/if} value="{$k}">{$v}</option>
                                {/foreach}
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <select required="" id="fpyear" name="year" class="form-control" >
                                <option>File Period Year</option>
                                {foreach from=$years item=v}
                                    <option {if $v==$post.year} selected {/if} value="{$v}">{$v}</option>
                                {/foreach}
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <select required="" id="" name="type" class="form-control" >
                                <option value="ALL">ALL</option>
                                <option value="B2B">B2B</option>
                                <option value="B2CS">B2CS</option>
                                <option value="QTR">QTR</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn blue" value="Prepare for review" />
                            <span class="help-block"></span>
                        </div>

                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    {if !empty($bulk_list)}
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->

                <div class="portlet ">
                    <div class="portlet-body">
                        <table class="table table-striped  table-hover" id="table-no-export">
                            <thead>
                                <tr>
                                    <th class="td-c">
                                        Date
                                    </th>

                                    <th class="td-c">
                                        GST
                                    </th>
                                    <th class="td-c">
                                        File period
                                    </th>
                                    <th class="td-c">
                                        Total Invoices
                                    </th>
                                    <th class="td-c">
                                        Date
                                    </th>
                                    <th class="td-c">
                                        Type
                                    </th>
                                    <th class="td-c">
                                        Status
                                    </th>
                                    <th class="td-c">

                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                            <form action="" method="">
                                {foreach from=$bulk_list key=kk item=v}
                                    <tr>
                                        <td class="td-c">
                                            {$v.created_date}
                                        </td>
                                        <td class="td-c">

                                            {$link=''}
                                            {$type=''}
                                            {if $v.status=='0'}
                                                {$link='/merchant/gst/invoicelist/'}
                                                {$type='/2'}
                                                {else if {$v.status}=='1'}
                                                    {$link='/merchant/gst/viewlist/gstr1/'}
                                                    {else if {$v.status}=='2'}
                                                        {$link='/merchant/gst/bulkerror/'}
                                                        {$type='/2'}
                                                        {else if {$v.status}=='5'}
                                                            {$link='/merchant/gst/gstdraft/'}
                                                        {/if}

                                                        {if $link!=''}
                                                            <a  href="{$link}{$v.link}{$type}">{$v.gstin}</a>
                                                        {else}
                                                            {$v.gstin}
                                                        {/if}
                                                    </td>
                                                    <td class="td-c">
                                                        {$months.{$v.fp|substr:0:2}}-{$v.fp|substr:2}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.total_invoices}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.created_at}
                                                    </td>
                                                    <td class="td-c">
                                                        {$v.type}
                                                    </td>
                                                    <td class="td-c">
                                                        {if $v.status==0}
                                                            <span class="label label-sm label-warning">
                                                                Review
                                                            </span>
                                                        {else if $v.status==1}
                                                            <span class="label label-sm label-success">
                                                                Validated
                                                            </span>
                                                        {else if $v.status==2}
                                                            <span class="label label-sm label-danger">
                                                                Failed
                                                            </span>
                                                        {else if $v.status==5}
                                                            <span class="label label-sm label-warning">
                                                                Draft created
                                                            </span>
                                                        {else if $v.status==6}
                                                            <span class="label label-sm label-success">
                                                                Ready for submission
                                                            </span>
                                                        {else if $v.status==7}
                                                            <span class="label label-sm label-success">
                                                                Submitted to GSTIN
                                                            </span>
                                                        {else if $v.status==4}
                                                            <span class="label label-sm label-default">
                                                                Processing
                                                            </span>
                                                        {/if}
                                                    </td>
                                                    <td>
                                                        <div class="hidden-xs btn-group dropup" style="position: absolute;">
                                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                            </button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                                {if {$v.status}=='0'}
                                                                    <li>
                                                                        <a href="/merchant/gst/invoicelist/{$v.link}/2" target="_BLANK" ><i class="fa fa-table"></i> View invoices</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="/merchant/gst/invoicedocument/{$v.link}" target="_BLANK" ><i class="fa fa-file"></i> Prepare documents</a>
                                                                    </li>
                                                                    <li> <a href="#sendinvoice" onclick="document.getElementById('sendanchor').href = '/merchant/gst/approveinvoice/{$v.link}/2'" data-toggle="modal" ><i class="fa fa-check"></i> Approve data</a>
                                                                    </li>
                                                                    {else if {$v.status}=='1'}
                                                                        <li>
                                                                            <a href="/merchant/gst/viewlist/gstr1/{$v.link}" target="_BLANK" ><i class="fa fa-table"></i> View invoices</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="/merchant/gst/invoicedocument/{$v.link}" target="_BLANK" ><i class="fa fa-file"></i> Invoice documents</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="/merchant/gst/gstdraft/{$v.link}/create" ><i class="fa fa-table"></i> Create draft</a>
                                                                        </li>
                                                                        {else if {$v.status}=='2'}
                                                                            <li>
                                                                                <a href="/merchant/gst/bulkerror/{$v.link}/2" class="iframe" ><i class="fa fa-table"></i> View errors</a>
                                                                            </li>
                                                                            {else if {$v.status}=='5'}
                                                                                <li>
                                                                                    <a href="/merchant/gst/gstdraft/{$v.link}" ><i class="fa fa-table"></i> View draft</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="/merchant/gst/gstdraft/{$v.link}" ><i class="fa fa-file"></i> Save draft</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="#draft" onclick="document.getElementById('deleteanchor').href = '/merchant/gst/gstdraft/{$v.link}/delete';" data-toggle="modal"><i class="fa fa-remove"></i> Delete draft</a>
                                                                                </li>
                                                                                {else if {$v.status}=='6'}
                                                                                    <li>
                                                                                        <a href="/merchant/gst/gstsubmit/{$v.link}" ><i class="fa fa-save"></i> Submit draft</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="/merchant/gst/gstdraft/{$v.link}" ><i class="fa fa-table"></i> View draft</a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href="#draft" onclick="document.getElementById('deleteanchor').href = '/merchant/gst/gstdraft/{$v.link}/delete';" data-toggle="modal"><i class="fa fa-remove"></i> Delete draft</a>
                                                                                    </li>
                                                                                    {else if {$v.status}=='7'}
                                                                                        <li>
                                                                                            <a href="/merchant/gst/gstsubmit/{$v.link}" ><i class="fa fa-table"></i> View summary</a>
                                                                                        </li>

                                                                                    {/if}
                                                                                    <li>
                                                                                        <a href="#basic" onclick="document.getElementById('deletedanchor').href = '/merchant/gst/deleter1/{$v.link}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>

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
                                    {/if}

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
                                            <a href="" id="deletedanchor" class="btn delete">Confirm</a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div class="modal fade" id="draft" tabindex="-1" role="basic" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h4 class="modal-title">Delete GSTR1 draft</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you would like to delete this draft?
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