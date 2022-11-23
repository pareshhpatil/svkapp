
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        {include file="../../common/breadcumbs.tpl" title={$title} links=$links}
        <a href="/merchant/coveringnote/dynamicvariable" class="iframe btn btn-link pull-right ">Dynamic Variables</a>
    </div>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
            {if isset($haserrors)}
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <div class="media">
                        {foreach from=$haserrors item=v}
                            <p class="media-heading">{$v.0} - {$v.1}.</p>
                        {/foreach}
                    </div>

                </div>
            {/if}
            <div id="emailsent" {if isset($email_sent)} {else}style="display: none;"{/if} class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert"></button>
                <strong> </strong>Email has been sent successfully.
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!--<h3 class="form-section">Profile details</h3>-->
                    <form action="/merchant/coveringnote/save" method="post" id="submit_form" class="form-horizontal form-row-sepe">
                        {CSRF::create('coveringnote_save')}
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Template name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="template_name" {$validate.name} class="form-control" value="{$post.template_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Mail body <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <textarea required name="body" id="summernote" class="form-control description">
                                            {if isset($post.body)}{$post.body}{else}
                                                <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif"><img data-file-id="890997" src="{$logo}"  style="max-height: 200px; margin: 0px;"></span></div><div><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                                    {/if}
                                        </textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Mail Subject <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <input type="text" required maxlength="100" name="subject" class="form-control" value="{if isset($post.subject)}{$post.subject}{else} Payment request from %COMPANY_NAME%{/if}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Invoice label <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <input type="text" required maxlength="20"  name="invoice_label" class="form-control" value="{if isset($post.invoice_label)}{$post.invoice_label}{else}View Invoice{/if}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Attach PDF <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" 
                                               data-off-text="&nbsp;Disabled&nbsp;">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>					
                    <!-- End profile details -->

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <a href="/merchant/coveringnote/viewlist" class="btn btn-default">Cancel</a>
                                    <input type="submit" value="Save" class="btn blue"/>
                                    <input type="hidden" id="emailidd" name="email_id" value="{$merchant_email}" />
                                    
                                    <!--<a data-toggle="modal" title="Send test mail" href="#custom" class="btn green"><i class="fa fa-send"></i> Test Mail</a>-->
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>	
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>

<div class="modal fade bs-modal-lg in" id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">
        <form action="/merchant/coveringnote/sendtestmail" method="post" id="categoryForm" class="form-horizontal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Send Test Email</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="portlet-body form">
                            <div class="form-body">
                                <!-- Start profile details -->
                                <div class="alert alert-danger display-none" id="errors">

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Email ID<span class="required">* </span></label>
                                            <div class="col-md-4">
                                                <input type="email" id="merchant_email"  value="{$merchant_email}" maxlength="250" value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Captcha<span class="required">* </span></label>
                                            <div class="col-md-4">
                                                <form id="comment_form" action="form.php" method="post">
                                                    <div class="g-recaptcha" data-sitekey="{$capcha_key}"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End profile details -->
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" onclick="return sendtestemail();" class="btn blue">Send</button>
                    <button type="button" class="btn default" id="closebutton" data-dismiss="modal">Close</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </form></div>
</div>