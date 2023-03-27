
<style>
    .customize-output-panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .customize-output-panel {
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

    @media screen and (min-width: 0px) and (max-width: 700px) {
        .mobile {
            display: block !important;
        }

        .desk {
            display: none !important;

        }
    }

    @media screen and (min-width: 701px) {
        .mobile {
            display: none !important;
        }

        .desk {
            display: block !important;

        }

    }

    @media (max-width: 767px) {
        .customize-output-panel-wrap {
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    .control-label{
        color: #394242 !important;
    }
</style>
<script src="/assets/admin/layout/scripts/coveringnote.js" type="text/javascript"></script>

<div>
    <div class="  col-md-12">
        

    <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">Properties</a></li>
                <li role="presentation"><a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">Notifications</a></li>
            </ul>

        <div class="portlet light bordered">
           
            <div class="portlet-body">
            <form action="/merchant/contract/store" method="post">
                <input type="hidden" name="template_id" value="{{$template_id}}" >
                <div class="tab-content" style="">
                    <div class="tab-pane active" id="tab1">

                        <div id="pgisupload" >
                            <div class="mb-2">
                                <span class="form-section base-font">Invoice level attachments</span>
                            </div>
                            <div class="">
                                <label class="control-label  w-auto">Invoice attachments
                                    <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Support non mandatory document attachments at an invoice level" type="button">
                                        <i class="fa fa-info-circle"></i>
                                    </span>

                                </label>
                                <div class="pull-right">
                                    <input value="View document" required="" type="hidden" maxlength="20" class="form-control" name="upload_file_label">
                                    <input type="checkbox" @isset($plugins['has_upload']) checked @endif id="isupload" onchange="disablePlugin(this.checked, 'plg15');" name="has_upload" value="1" data-size="small" class="make-switch pull-right" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                            <br>
                            <!--<div class="input-group">
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">File label</label>
                        <div class="col-md-8">
                            <input value="View document" required="" type="text" maxlength="20" class="form-control" name="upload_file_label">
                        </div>
                    </div>
                </div>-->
                                <label class="control-label w-auto">Setup required documents
                                    <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Use this facility if you require specific documents during submission or approval" type="button">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                </label>
                                <div class="pull-right">
                                    <input type="checkbox" @isset($plugins['has_mandatory_upload']) checked @endif id="isMandatoryUpload" onchange="setCheckbox(this.checked, 'isMandatoryUpload');" name="has_mandatory_upload" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                                <a id="document_attachment_button" @if(!isset($plugins['has_mandatory_upload'])) style="display: none;" @endif data-toggle="modal" href="#new_document" class="btn btn-sm mb-1 green pull-right mr-1"><i class="fa fa-plus"> </i> Add document </a>


                                <div id="document_attachment_div" @if(!isset($plugins['has_mandatory_upload'])) style="display: none;" @endif>
                                    <br>
                                    <table id="t_new_cc" class="table table-bordered table-hover">
                                        <thead id="h_new_cc" style="display: contents;">
                                            <tr>
                                                <th class="td-c  default-font">
                                                    Document name
                                                </th>
                                                <th class="td-c  default-font">
                                                    Document description
                                                </th>
                                                <th class="td-c  default-font">
                                                    Required action
                                                </th>

                                                <th class="td-c" style="width: 80px;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="new_documents">
                                            @if(!empty($plugins['mandatory_data']))
                                            @foreach($plugins['mandatory_data'] as $v)
                                            <tr>
                                                <td class="td-c  default-font">{{$v['name']}}
                                                    <input type="hidden" name="mandatory_document_name[]" value="{{$v['name']}}">
                                                    <input type="hidden" name="mandatory_document_description[]" value="{{$v['description']}}">
                                                    <input type="hidden" name="mandatory_document_action[]" value="{{$v['required']}}">
                                                </td>
                                                <td class="td-c  default-font">{{$v['description']}}</td>
                                                <td class="td-c  default-font">{{$v['required']}}</td>
                                                <td class="td-c"><a href="javascript:;" onclick="$(this).closest('tr').remove();" class="btn btn-xs red"> <i class="fa fa-times"> </i> </a></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                        </div>

                        

                        <div id="pgiswatermark" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font">Watermark</span>
                                <div class="pull-right">
                                    <input type="checkbox" @isset($plugins['has_watermark']) checked @endif id="iswatermark" name="has_watermark" onchange="disablePlugin(this.checked, 'plg28');
                        " value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                            <div id="watermark_div" class="row mb-2" @if(isset($plugins['has_watermark']) && $plugins['has_watermark']=='1' ) style="display: block;" @else style="display:none;" @endif>
                                <label class="control-label col-md-3 w-auto">Watermark text</label>
                                <div class="col-md-3">
                                    <input type="text" maxlength="25" @isset($plugins['watermark_text']) value="{{$plugins['watermark_text']}}" @else value="DRAFT" @endif class="form-control" id="watermark_text" name="watermark_text">
                                </div>
                            </div>
                        </div>



                        <div id="pgisrevision" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font">Revision history&nbsp;</span>
                                <div class="pull-right">
                                    <input type="checkbox" @isset($plugins['save_revision_history']) checked @endif id="isrevision" onchange="disablePlugin(this.checked, 'plg22');" name="is_revision" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                        </div>

                        <div id="pginvoiceoutput" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font"> License available&nbsp; </span>
                                <div class="pull-right ml-1">
                                <input type="checkbox" @isset($plugins['has_aia_license']) checked @endif name="has_aia_license" id="plglicenseavailable" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                        </div>
                        <div id="pginvoiceoutput" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font"> Include stored materials from previous invoices </span>
                                <div class="pull-right ml-1">
                                <input type="checkbox" @isset($plugins['include_store_materials']) checked @endif name="include_store_materials" id="plgincludestorematerials" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                        </div>

                        <div id="pgissignature" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font"> Digital signature&nbsp; </span>
                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_signature']) checked @endif id="issignature" onchange="disablePlugin(this.checked, 'plg16');" name="has_signature" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                                <a href="/merchant/profile/digitalsignature/iframe" class="iframe btn btn-sm green pull-right"> Digital signature </a>
                            </div>
                        </div>

                        <div id="pgispartial" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font">Partial payment</span>
                                <div class="pull-right">
                                    <input type="checkbox" @isset($plugins['has_partial']) checked @endif id="ispartial" name="is_partial" onchange="disablePlugin(this.checked, 'plg13');
                        showDebit('partial');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                            <div id="min_partial_payment_div" class="row mb-2" @if(isset($plugins['has_partial']) && $plugins['has_partial']=='1' ) style="display: block;" @else style="display:none;" @endif>
                                <label class="control-label col-md-3 w-auto">Minimum partial amount</label>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" min="50" @isset($plugins['partial_min_amount']) value="{{$plugins['partial_min_amount']}}" @else value="50" @endif class="form-control" id="pma" name="partial_min_amount">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="tab2">
                        <div id="pgiscovering" >
                            <div class="mb-2 desk">
                                <span class="form-section base-font">Covering note </span>

                                <div class=" pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_covering_note']) checked @endif id="iscovering" name="is_covering" onchange="disablePlugin(this.checked, 'plg10');
                        showDebit('covering');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>

                                <a href="/merchant/coveringnote/dynamicvariable" class="iframe btn btn-sm green pull-right ml-1">Dynamic variables </a>
                                <a data-toggle="modal" href="#new_covering" class="btn btn-sm mb-1 green pull-right ">Add new note </a>

                            </div>
                            <div class="mb-2 mobile">
                                <span class="form-section base-font">Covering note </span>

                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_covering_note']) checked @endif id="iscovering" name="is_covering" onchange="disablePlugin(this.checked, 'plg10');
                        showDebit('covering');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                                <div class="mt-1">
                                    <a data-toggle="modal" href="#new_covering" class="btn btn-sm mb-1 green  ">Add new note </a>

                                    <a href="/merchant/coveringnote/dynamicvariable" class=" btn btn-sm mb-1 green">Dynamic variables </a>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="control-label col-md-3 w-auto">Select covering note</label>
                                <div class="col-md-4">
                                    <select class="form-control" id="covering_select" name="default_covering">
                                        <option value="0">Select Template</option>
                                        @if(!empty($coveringNotes))
                                        @foreach($coveringNotes as $v)
                                        <option @isset($plugins['default_covering_note']) @if($plugins['default_covering_note']==$v['covering_id']) selected @endif @endif value="{{$v['covering_id']}}">{{$v['template_name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div id="pgiscc" class="" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font">CC Emails</span>
                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_cc']) checked @endif id="iscc" name="is_cc" onchange="disablePlugin(this.checked, 'plg4'); showDebit('cc');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                </div>
                                <a onclick="AddCC(); tableHead('new_cc');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>

                            </div>
                            <div style="max-width: 500px;">
                                <table id="t_new_cc" class="table table-bordered table-hover">
                                    <thead id="h_new_cc" style="display: none;">
                                        <tr>
                                            <th class="td-c  default-font">
                                                Email
                                            </th>

                                            <th class="td-c">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="new_cc">

                                        @if(!empty($plugins['cc_email']))
                                        @foreach($plugins['cc_email'] as $v)
                                        <tr>
                                            <td>
                                                <div class="input-icon right"><input type="email" value="{{$v}}" name="cc[]" class="form-control input-sm" placeholder="Add email"></div>
                                            </td>
                                            <td><a href="javascript:;" onclick="$(this).closest('tr').remove();tableHead('new_cc');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>
                                        </tr>
                                        @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </form>
            



                <!-- add supplier start -->


                <!-- grand total label -->




            </div>

        </div>

        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a class="btn green" href="{{ route('contract.create.new', ['step' => 2, 'contract_id' => $contract_id]) }}">Back</a>
                            <button class="btn blue" type="submit" @click="next()" fdprocessedid="tinkj">Preview contract</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal  fade" id="new_covering" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add new covering note</h4>
                    </div>
                    <form action="/merchant/coveringnote/save" method="post" id="covering_frm" class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                    <div id="covering_error" class="alert alert-danger" style="display: none;">

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Template name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required name="template_name" {$validate.name} class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Mail body <span class="required">*
                                            </span></label>
                                        <div class="col-md-7">
                                            <textarea required name="body" id="summernote" class="form-control tncrich">
                                                <div style="text-align: center;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear %CUSTOMER_NAME%,<br><br>Please find attached our invoice for the services provided to your company.<br><br>It has been a pleasure serving you. We look forward to working with you again.<br><br>If you have any questions about your invoice, please contact us by replying to this email.<br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mail Subject <span class="required">*
                                            </span></label>
                                        <div class="col-md-6">
                                            <input type="text" required maxlength="100" name="subject" class="form-control" value="Payment request from %COMPANY_NAME%">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Invoice label <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required maxlength="20" name="invoice_label" class="form-control" value="View Invoice">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Attach PDF <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- End profile details -->



                    </form>
                    <div class="modal-footer">
                        <button type="button" id="cclosebutton" class="btn default" data-dismiss="modal">Close</button>
                        <input type="button" onclick="return saveCovering('');" value="Save" class="btn blue">
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="new_document" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add new document</h4>
                    </div>
                    <form action="/merchant/coveringnote/save" method="post"  class="form-horizontal form-row-sepe">
                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <br>
                                    <div id="covering_error" class="alert alert-danger" style="display: none;">
                                    </div>

                                    <div id="mandatory_docs" class="alert alert-danger" style="display: none;">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Document name <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required id="document_name" maxlength="100" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Document description <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <input type="text" required id="document_description" maxlength="250" class="form-control" value="">
                                            <!-- <input type="hidden" id="document_action" value="Non-mandatory"> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Required for action <span class="required">*
                                            </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="document_action">
                                                <option value="Non-mandatory">Non-mandatory</option>
                                                <option value="Mandatory on creation">Mandatory on creation</option>
                                                <option value="Mandatory on submission">Mandatory on submission</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- End profile details -->



                    </form>
                    <div class="modal-footer">
                        <button type="button" id="documentclose" class="btn default" data-dismiss="modal">Close</button>
                        <input type="button" onclick="return saveDocument('');" value="Save" class="btn blue">
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Customize invoice output drawer -->
        <div class="customize-output-panel-wrap" id="panelWrapInvoiceOutput">
            <div class="customize-output-panel">
                <div>
                    <h3 class="modal-title">
                        Customize invoice views
                        <a class="close " data-toggle="modal" onclick="closeCustomizeInvoiceOutputDrawer()">
                            <button type="button" class="close" aria-hidden="true"></button>
                        </a>
                    </h3>

                </div>

                <div style="position: relative;margin-top: 30px;">
                    <h4>AIA format </h4>
                    <hr />
                    <div class="mb-2" style="display: flex;justify-content: space-between;">
                        <span class="form-section base-font">License available&nbsp;</span>
                        <div>
                            <div class="plugin-button">
                                <input type="checkbox" @isset($plugins['has_aia_license']) checked @endif name="has_aia_license" id="plglicenseavailable" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.Customize invoice output drawer -->

        <script>
            function closeCustomizeInvoiceOutputDrawer() {
                document.getElementById("panelWrapInvoiceOutput").style.boxShadow = "none";
                document.getElementById("panelWrapInvoiceOutput").style.transform = "translateX(100%)";
            }

            $(document).ready(function() {
                $(document).on("click", ".customize-output-btn", function() {
                    let panelWrap = document.getElementById("panelWrapInvoiceOutput");
                    panelWrap.style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    panelWrap.style.transform = "translateX(0%)";
                })

            })
        </script>
    </div>