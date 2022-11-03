<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50%;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }

    .panel-wrap .panel {
        position: inherit !important;
    }

    .remove {
        padding: 4px 3px;
        cursor: pointer;
        float: left;
        position: relative;
        top: 0px;
        color: #fff;
        right: 25px;
        z-index: 99999;
    }

    .remove:hover {
        color: #FFF;
    }

    .remove i {
        font-size: 19px !important;
    }

    .subscription-info i {
        font-size: 22px !important;
    }

    .cust-head {
        text-align: left !important;
    }

    .subscription-info h3 {
        text-align: center;
        color: #000;
        margin-bottom: 2px !important;
    }

    .subscription-info h2 {
        font-weight: 600;
        margin-bottom: 0 !important;
        margin-top: 5px !important;
        text-align: center;
    }

    .td-head {
        font-size: 19px;
    }

    @media (max-width: 767px) {
        .cust-head {
            text-align: center !important;
        }

        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position: fixed;
            right: 0;
        }
    }

    .right-value {
        text-align: right;
    }
</style>
<div class="panel-wrap " id="panelWrapIdaddcovernote">
    {{-- <div id="close_tab">
        <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return closeSidePanelcalc();">
            <i class="fa fa-times" aria-hidden="true"> </i>
        </a>
    </div> --}}
    <div class="panel ">
        <div class="">
            <div class="portlet">
                <div class="modal-header">
                    <a class="close "   data-toggle="modal" href="#confirm_box">
          
                        <button type="button"   class="close"   aria-hidden="true"></button></a> 
                    <h4 class="modal-title">Add new covering note</h4>
                </div>
                <form action="/merchant/coveringnote/save" method="post" id="covering_frm" class="form-horizontal form-row-sepe">
                    {!!Helpers::csrfToken('coveringnote_save')!!}
                    <div class="form-body ">
                        <!-- Start profile details -->
                        <div class="row">
                            <div class="col-md-12">
                                <br>
                                <div id="covering_error" class="alert alert-danger" style="display: none;">
    
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Template name <span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input type="text" required data-cy="covering-name" name="template_name" {$validate.name} class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Mail body <span class="required">*
                                        </span></label>
                                    <div class="col-md-9">
                                        <textarea required name="body" data-cy="covering-body" id="summernote" class="form-control description tncrich">
                                                    <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">
                                                    @if(isset($logo))<img data-file-id="890997" src="{{$logo}}"  style="max-height: 200px;max-width:300px; margin: 0px;">@endif</span></div><div><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You,<br>With best regards,<br>%COMPANY_NAME%</span></div>
                                        </textarea>
                                        <span class="help-block"></span>
                                        <a href="/merchant/coveringnote/dynamicvariable" class="iframe  pull-right">Dynamic variables </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Mail Subject <span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input type="text" required maxlength="100" data-cy="covering-subject" name="subject" class="form-control" value="Payment request from %COMPANY_NAME%">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Invoice label <span class="required">*
                                        </span></label>
                                    <div class="col-md-6">
                                        <input type="text" required maxlength="20" data-cy="covering-label" name="invoice_label" class="form-control" value="View Invoice">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Attach PDF <span class="required">*
                                        </span></label>
                                    <div class="col-md-4">
                                        <input type="checkbox" checked="" name="pdf_enable" data-cy="covering-pdf" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                    </div>
                                </div>
                            </div>
    
    
                        </div>
                    </div>
                    <!-- End profile details -->
    
    
    
                </form>
                <div class="modal-footer">
                    <a   data-toggle="modal" href="#confirm_box">
          
                        <button type="button"   class="btn default">Close</button>
                        </a>
                    <button type="button" id="cclosebutton" data-cy="covering-btn-close" class="btn default hidden" data-dismiss="modal">Close</button>
                    <input type="submit" data-cy="covering-btn-save" onclick="return saveCovering('');" value="Save" class="btn blue">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>