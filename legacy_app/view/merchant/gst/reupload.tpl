
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Re-upload {$type} invoices</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            {if isset($validation_values)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <p >
                        Sheet have a typo in the state name, please select the correct state for these rows.
                    </p>
                </div>
                <form action="/merchant/gst/validated" enctype="multipart/form-data" method="post">
                    {CSRF::create('gst_invoice_upload')}
                    <div class="portlet ">
                        <div class="portlet-body">
                            <div class="panel-body">
                                <table class="table table-bordered" >
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Invalid state name
                                            </th>
                                            <th class="td-c">
                                                Rows
                                            </th>
                                            <th class="td-c" style="width: 300px;">
                                                Select state
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$validation_values key=k item=v}
                                            <tr>
                                                <td class="td-c">{$k}</td>
                                                <td class="td-c"><a title="Check excel row numbers {', '|implode:$v}">{count($v)} Rows</a></td>
                                                <td class="td-c">
                                                    <select class="form-control select2me text-left" name="state[]" data-placeholder="Select..." required="">
                                                        <option value=""></option>
                                                        {foreach from=$state_array key=sk item=s}
                                                            <option value="{if $s.config_key<10}0{$s.config_key}{else}{$s.config_key}{/if}">{if $s.config_key<10}0{$s.config_key}{else}{$s.config_key}{/if} - {$s.config_value}</option>
                                                        {/foreach}
                                                    </select>
                                                    <input type="hidden" name="state_name[]" value="{$k}">
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                                <div class="saveBtn form-group">        
                                    <div class="row">
                                        <div class="col-md-12  mt-2">
                                            <div class="col-md-6" ></div>
                                            <div class="col-md-6 no-padding" >
                                                <button type="submit" class="btn blue pull-right" >Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="bulk_id" value="{$bulk_id}">
                                <input type="hidden" name="type" value="{$type}">
                            </div>
                        </div>
                    </div>
                </form>
            {else}
                {if isset($haserrors)}
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <div class="media">
                            {foreach from=$haserrors item=v}
                                <p class="media-heading"><strong>Row no {$v.row}</strong></p>
                                {foreach from=$v item=k}
                                    {if $k.1!=''}<p class="media-heading">{$k.0} - {$k.1}</p>{/if}
                                {/foreach}
                            {/foreach}
                        </div>
                    </div>
                {/if}
                <!-- BEGIN UPLOAD EXCEL BOX -->
                <form action="/merchant/gst/upload" enctype="multipart/form-data" method="post">
                    {CSRF::create('gst_invoice_upload')}
                    <div class="portlet ">
                        <div class="portlet-body">
                            <div class="panel-body"><p>Upload filled excel sheet</p>
                                <p><div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">
                                            Select file </span>
                                        <span class="fileinput-exists">
                                            Change </span>
                                        <input type="file" accept=".csv,.xlsx" name="fileupload" required>
                                    </span>
                                    <span class="fileinput-filename">
                                    </span>
                                    &nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput">
                                    </a>
                                </div></p>
                                <p><input type="submit" class="btn blue" value="Upload"/></p>
                                <input type="hidden" name="bulk_id" value="{$bulk_id}">
                                <input type="hidden" name="type" value="{$type}">
                            </div>
                        </div>
                    </div>
                </form>
            {/if}
            <!-- END UPLOAD EXCEL BOX -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>