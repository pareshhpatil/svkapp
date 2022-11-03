
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        {if isset($haserrors)}
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <strong>Error!</strong>
                <div class="media">
                    {foreach from=$haserrors key=k item=v}
                        <p class="media-heading">{$v.0} - {$v.1}</p>
                    {/foreach}
                </div>

            </div>
        {/if}
        {if isset($success)}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong>Success!</strong>{$success}
            </div>
        {/if}
        <form action="" method="post" id="submit_form"  class="form-horizontal form-row-sepe">
            <div class="col-md-12">
                <div class="alert alert-danger display-none">
                    <button class="close" data-dismiss="alert"></button>
                    You have some form errors. Please check below.
                </div>
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        {if $enable_qr==1}
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="col-md-8 no-padding">
                                        <div style="font-size: 0px;"><abc16>{$qrlink}</abc16></div>
                                        <a href="{$qrlink}" target="_BLANK" class="btn btn-sm blue"><i class="fa fa-link"></i> Open QR scanner</a>
                                        <a href="javascript:;" class="btn btn-sm green bs_growl_show" data-clipboard-action="copy" data-clipboard-target="abc16"><i class="fa fa-clipboard"></i> Copy to clipboard</a>
                                    </div>
                                    <!-- Start Bulk upload details -->
                                </div>
                            </div>	
                        {/if}

                    </div>						
                </div>						
                <!-- End Bulk upload details -->



            </div>
        </form>	

    </div>
</div>	
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>


