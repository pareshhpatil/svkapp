<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN UPLOAD EXCEL BOX -->
            {if isset($haserrors)}
                <div class="row">
                    <div class="alert alert-danger">
                        <div class="">
                            {foreach from=$haserrors item=v}
                                <p class="media-heading">{$v.0} - {$v.1}.</p>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/if}


            <div class="">

                <div class="portlet-body">
                    <div class="panel-group accordion" id="accordion1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse"
                                        data-parent="#accordion1" href="#collapse_1">
                                        <b>Step #1 - Download excel sheet</b> </a>
                                </h4>
                            </div>
                            <div id="collapse_1" class="panel-collapse in">
                                <div class="panel-body">
                                    <p>To send out bulk invoices pick an invoice template. You can also create a new
                                        invoice template using "Add new format"</p>
                                    <form action="/merchant/bulkupload/excelExport/invoice" method="post"
                                        class="form-horizontal form-row-sepe">
                                        <div class="form-body">
                                            <div class="form-group mb-0">
                                                <div class="col-md-12">
                                                    <div class="col-md-3 pl-1" style="padding-right: 0px;">
                                                        <select name="template_id" id="template_ids" required
                                                            class="form-control select2me" data-placeholder="Select...">
                                                            <option value=""></option>
                                                            {foreach from=$templatelist item=v}
                                                                {if $v.template_type!='travel_ticket_booking' && $v.template_type!='scan' && $v.template_type!='event'}
                                                                    {if {{$template_selected}=={$v.template_id}}}
                                                                        <option selected value="{$v.template_id}" selected>
                                                                            {$v.template_name}</option>
                                                                    {else}
                                                                        <option value="{$v.template_id}">
                                                                            {$v.template_name}</option>
                                                                    {/if}
                                                                {/if}
                                                            {/foreach}
                                                        </select>
                                                        <small class="form-text text-muted">Invoice format</small>
                                                        <div class="help-block"></div>
                                                    </div>
                                                    <div class="col-md-2 pl-1" style="padding-right: 0px;">
                                                        <select name="type" required class="form-control"
                                                            data-placeholder="Select...">
                                                            <option value="">Select type</option>
                                                            <option selected value="1">Invoice</option>
                                                            <option value="2">Estimate</option>
                                                            <option value="3">Subscription invoice</option>
                                                            <option value="4">Subscription estimate</option>
                                                        </select>
                                                        <small class="form-text text-muted">Type</small>
                                                        <div class="help-block"></div>
                                                    </div>
                                                    <div class="col-md-3 pl-1" style="padding-right: 0px;">
                                                        <select name="sheet_type" required class="form-control"
                                                            data-placeholder="Select...">
                                                            <option value="">Sheet type</option>
                                                            <option selected value="1">Existing customers (Without
                                                                customer data)</option>
                                                            <option value="2">New customers (Without existing customer
                                                                data)</option>
                                                            <option value="3">Existing customers (With all customer
                                                                data)</option>
                                                            <option value="4">New customers (With all existing customer
                                                                data)</option>
                                                        </select>
                                                        <small class="form-text text-muted">Download format</small>
                                                        <div class="help-block"></div>
                                                    </div>
                                                    <div class="col-md-5 pl-1" style="width: auto;">
                                                        <button type="submit" class="btn blue"> Download
                                                        </button>
                                                        <input type="hidden" name="request_type"
                                                            value="{$request_type}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse"
                                        data-parent="#accordion1" href="#collapse_2">
                                        <b>Step #2 - Fill invoice excel sheet</b> </a>
                                </h4>
                            </div>
                            <div id="collapse_2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <p> In the downloaded excel sheet enter your data. Use column names mentioned in the
                                        excel sheet as reference.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse"
                                        data-parent="#accordion1" href="#collapse_3">
                                        <b>Step #3 - Upload filled sheet</b> </a>
                                </h4>
                            </div>
                            <div id="collapse_3" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form action="/merchant/bulkupload/upload" enctype="multipart/form-data"
                                        method="post">
                                        {CSRF::create('invoice_upload')}
                                        <p>Upload filled excel sheet</p>
                                        <p>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new">
                                                    Select file </span>
                                                <span class="fileinput-exists">
                                                    Change </span>
                                                <input type="file" accept=".xlsx" name="fileupload" required>
                                            </span>
                                            <span class="fileinput-filename">
                                            </span>
                                            &nbsp; <a href="javascript:;" class="close fileinput-exists"
                                                data-dismiss="fileinput">
                                            </a>
                                        </div>
                                        </p>
                                        <p><input type="submit" class="btn blue" value="Upload" /></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END UPLOAD EXCEL BOX -->
            <!-- BEGIN CREATED TEMPLATES LISTING BOX -->


            <!-- END CREATED TEMPLATES LISTING BOX -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>
<script>
    pages = 5
</script>