
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">Re-upload {$type}</h3>
    <!-- END PAGE HEADER-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
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
            <form action="/merchant/franchise/upload" enctype="multipart/form-data" method="post">
                {CSRF::create('franchise_upload')}
                <div class="portlet ">
                    <div class="portlet-body">
                        <div class="panel-body"><p>Upload filled excel sheet</p>
                            <p><div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new">
                                        Select file </span>
                                    <span class="fileinput-exists">
                                        Change </span>
                                    <input type="file" accept=".xlsx" name="fileupload" required>
                                </span>
                                <span class="fileinput-filename">
                                </span>
                                &nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput">
                                </a>
                            </div></p>
                            <p><input type="submit" class="btn blue" value="Upload"/></p>
                            <input type="hidden" name="bulk_id" value="{$bulk_id}">
                        </div>
                    </div>
                </div>
            </form>
            <!-- END UPLOAD EXCEL BOX -->

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>