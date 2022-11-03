
<div class="page-content">
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
    </div>
    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <!-- BEGIN SEARCH CONTENT-->

    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">


            <!-- BEGIN PORTLET-->

            <div class="portlet">
                
                <div class="portlet-body">

                    <form class="form-inline" action="/merchant/gst/gstconnection" method="post" role="form">
                        {CSRF::create('gst_connection')}
                        <div class="form-group">
                            <select required="" class="form-control" name="gstin">
                                <option value="">Select GSTIN</option>
                                {foreach from=$data item=v}
                                    <option {if $gstin=={$v.gstin}} selected{/if} value="{$v.gstin}">{$v.company_name} - {$v.gstin}</option>
                                {/foreach}
                            </select>
                        </div>
                        <input type="submit" class="btn  blue" value="Get connection" />
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>
    {if isset($success)}
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-success ml-0">
                    <strong></strong>{$success}
                </div> 
            </div> 
        </div> 
    {/if}

    {if $gst_connection_failed==1}
        {if $showotp==1}
            <div class="row">
                <div class="col-md-8">
                    <div class="alert alert-success ml-0">
                        <strong>Success!</strong> OTP has been sent
                    </div>
                </div>
            </div>
        {else}
            <div class="row">
                <div class="col-md-8">
                    <div class="alert alert-info">
                        {if isset($error)}
                            <strong>Error!</strong> {$error}
                        {else}
                            Please validate your connection with the GST portal via OTP
                        {/if}
                    </div>
                </div>
            </div>
        {/if}
        <div class="row">
            <div class="col-md-8">
                <form action="/merchant/gst/gstconnection" method="post">
                    {CSRF::create('gst_connection')}
                    <input type="hidden" name="gstin" value="{$gstin}">
                    {if $showotp==1}
                        <div class="form-group">
                            <label class="control-label col-md-1">OTP <span class="required">*
                                </span></label>
                            <div class="col-md-4">
                                <input type="text" name="otp_text" class="form-control">
                            </div>
                        </div>

                        <input type="submit" name="submit_otp" value="Validate OTP" class="btn green"/>
                    {/if}
                    <input type="submit" name="otp" value="Request OTP" class="btn blue"/>
                </form>

            </div>
        </div>
    {/if}
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->



