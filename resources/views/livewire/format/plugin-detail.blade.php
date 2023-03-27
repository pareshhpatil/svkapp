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
</style>

<div>
    <div class="portlet  col-md-12">

        <div class="portlet-body">
            <h4 class="form-section mt-0">Plugins
                <a data-toggle="modal" href="#plugins" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Choose Plugins </a>
            </h4>

            <div id="pgisdebit" @isset($plugins['has_deductible']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font"> Deductible&nbsp;</span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_deductible']) checked @endif id="isdebit" name="is_debit" data-size="small" onchange="disablePlugin(this.checked, 'plg1');
                        showDebit('debit');" value="1" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a onclick="AddDebit();
                    tableHead('new_debit');" class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
                </div>

                <table id="t_new_debit" class="table table-bordered table-hover">
                    <thead id="h_new_debit" @isset($plugins['has_deductible']) @else style="display: none;" @endif>
                        <tr>
                            <th class="td-c  default-font">
                                Deduct label
                            </th>
                            <th class="td-c  default-font">
                                Deduct in %
                            </th>
                            <th class="td-c  default-font">
                                Applicable on
                            </th>
                            <th class="td-c  default-font">
                                Absolute cost
                            </th>
                            <th class="td-c">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_debit">
                        @if(!empty($plugins['deductible']))
                        @foreach($plugins['deductible'] as $v)
                        <tr>
                            <td>
                                <div class="input-icon right">
                                    <input type="text" name="debit[]" class="form-control input-sm" value="{{$v['tax_name']}}" placeholder="Add label">
                                </div>
                            </td>
                            <td>
                                <div class="input-icon right"><input type="number" step="0.01" value="{{$v['percent']}}" max="100" name="debitdefaultValue[]" class="form-control input-sm" placeholder="Add %"></div>
                            </td>
                            <td><input type="text" readonly="" class="form-control input-sm"></td>
                            <td><input type="text" class="form-control input-sm" readonly=""></td>
                            <td><a href="javascript:;" onclick="$(this).closest('tr').remove();tableHead('new_debit');" class="btn btn-sm red"> <i class="fa fa-times"> </i></a></td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- add supplier start -->
            <div id="pgissupplier" @isset($plugins['has_supplier']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Supplier</span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_supplier']) checked @endif id="issupplier" name="is_supplier" onchange="disablePlugin(this.checked, 'plg2');
                        showDebit('supplier');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a data-toggle="modal" href="#respond" class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
                </div>
                <table id="t_new_supplier" class="table table-bordered table-hover">
                    <thead id="h_new_supplier">
                        <tr>
                            <th class="td-c  default-font">
                                Supplier company name
                            </th>
                            <th class="td-c  default-font">
                                Contact person name
                            </th>
                            <th class="td-c  default-font">
                                Mobile
                            </th>
                            <th class="td-c  default-font">
                                Industry type
                            </th>

                            <th class="td-c">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_supplier">
                        @if(!empty($supplier))

                        @foreach($supplier as $v)
                        @if(!empty($plugins['supplier']))
                        @if(in_array($v['supplier_id'],$plugins['supplier']))
                        <tr id="row{{$v['supplier_id']}}">
                            <td class="td-c"><input type="hidden" name="supplier[]" value="{{$v['supplier_id']}}">
                                {{$v['supplier_company_name']}}
                            </td>
                            <td class="td-c">{{$v['contact_person_name']}}</td>
                            <td class="td-c">{{$v['mobile1']}}</td>
                            <td class="td-c">{{$v['email_id1']}}</td>
                            <td class="td-c">
                                <a href="javascript:;" id="{{$v['supplier_id']}}" onclick="removesupplier(this.id);$(this).closest('tr').remove();tableHead('new_supplier');" class="btn btn-sm red"> <i class="fa fa-times"> </i></a>
                            </td>
                        </tr>
                        @endif
                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- grand total label -->
            <div id="pgiscoupon" @isset($plugins['has_coupon']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Coupon&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_coupon']) checked @endif id="iscoupon" onchange="disablePlugin(this.checked, 'plg3');" name="is_coupon" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <div id="pgisinvoicenumber" @isset($plugins['has_invoice_number']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Invoice number&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_invoice_number']) checked @endif id="isinvoicenumber" onchange="disableFunctionPlugin(this.checked, 'plgf9');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <div id="pgiscustomizedpaymentreceipt" @isset($plugins['has_customized_payment_receipt']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Customize payment receipt&nbsp;</span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_customized_payment_receipt']) checked @endif id="iscustomizedpaymentreceipt" onchange="disableFunctionPlugin(this.checked, 'plg20','20');showDebit('customized_payment_receipt');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <div class="pull-right" id="edit_receipt_fields_div" @if(isset($plugins['has_customized_payment_receipt']) && $plugins['has_customized_payment_receipt']=='1' ) style="display: block;" @else style="display:none;" @endif>
                        <a onclick="setFunctionPlugin(20)" class="btn btn-sm green pull-right"> <i class="fa fa-pencil"> </i> Configure receipt fields </a>
                    </div>
                </div>
            </div>
            <div id="pgisexpirydate" @isset($plugins['has_expiry_date']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Expiry date&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_expiry_date']) checked @endif id="isexpirydate" onchange="disableFunctionPlugin(this.checked, 'plgf1');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <div id="pgispreviousdue" @isset($plugins['has_previous_due']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Previous dues&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_previous_due']) checked @endif id="ispreviousdue" onchange="disableFunctionPlugin(this.checked, 'plgf4');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>

            <div id="pgiscc" class="" @isset($plugins['has_cc']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">CC Emails</span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_cc']) checked @endif id="iscc" name="is_cc" onchange="disablePlugin(this.checked, 'plg4');
                        showDebit('cc');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                    </div>
                    <a onclick="AddCC();
                    tableHead('new_cc');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>

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


            <div id="pgisroundoff" @isset($plugins['roundoff']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Round off&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['roundoff']) checked @endif id="isroundoff" onchange="disablePlugin(this.checked, 'plg5');" name="is_roundoff" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <div id="pgisacknowledgement" @isset($plugins['has_acknowledgement']) @else style="display: none;" @endif>
                <hr>
                <div>
                    <span class="form-section base-font">Acknowledgement section&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_acknowledgement']) checked @endif id="isacknowledgement" onchange="disablePlugin(this.checked, 'plg6');" name="has_acknowledgement" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>

            <div id="pgisfranchise" class="" @isset($plugins['has_franchise']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Franchise&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_franchise']) checked @endif id="isfranchise" name="is_franchise" onchange="disablePlugin(this.checked, 'plg7');
                            showDebit('franchise');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
                <div>
                    <label class="control-label  mb-2">Notify Franchise on payment</label>
                    &nbsp;<label> <input type="checkbox" @isset($plugins['has_franchise']) @if($plugins['franchise_notify_email']==1) checked @endif @endif value="1" name="franchise_notify_email"> Email </label>
                    &nbsp;<label> <input type="checkbox" @isset($plugins['has_franchise']) @if($plugins['franchise_notify_sms']==1) checked @endif @endif value="1" name="franchise_notify_sms"> SMS </label>
                    <br>
                    <label class="control-label">Display franchise name on Invoice <input type="checkbox" @isset($plugins['has_franchise']) @if($plugins['franchise_name_invoice']==1) checked @endif @endif value="1" name="franchise_name_invoice"> Yes </label>
                    </h4>
                </div>
            </div>


            <div id="pgisvendor" @isset($plugins['has_vendor']) @else style="display: none;" @endif>
                <hr>
                <div>
                    <span class="form-section base-font">Vendor&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_vendor']) checked @endif id="isvendor" name="is_vendor" onchange="disablePlugin(this.checked, 'plg71');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <div id="pgisprepaid" @isset($plugins['is_prepaid']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Pre-paid invoices&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['is_prepaid']) checked @endif id="isprepaid" onchange="disablePlugin(this.checked, 'plg8');" name="is_prepaid" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
            <!--
            <div id="pgiswebhook" style="display: none;">
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Webhook&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" id="iswebhook" onchange="disablePlugin(this.checked, 'plg9');" name="has_webhook" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>
           -->

            <div id="pgisautocollect" @isset($plugins['has_autocollect']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Auto collect&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" id="isautocollect" @isset($plugins['has_autocollect']) checked @endif onchange="disablePlugin(this.checked, 'plg14');" name="has_autocollect" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>


            <div id="pgisupload" @isset($plugins['has_upload']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Invoice level attachments</span>
                </div>
                <div class="mb-2">
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">Invoice attachments
                            <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Support non mandatory document attachments at an invoice level" type="button">
                                <i class="fa fa-info-circle"></i>
                            </span>

                        </label>
                        <div class="pull-right">
                            <input value="View document" required="" type="hidden" maxlength="20" class="form-control" name="upload_file_label">
                            <input type="checkbox" @isset($plugins['has_upload']) checked @endif id="isupload" onchange="disablePlugin(this.checked, 'plg15');" name="has_upload" value="1" data-size="small" class="make-switch pull-right" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <!--<div class="input-group">
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">File label</label>
                        <div class="col-md-8">
                            <input value="View document" required="" type="text" maxlength="20" class="form-control" name="upload_file_label">
                        </div>
                    </div>
                </div>-->
                <div class="mb-2">
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">Setup required documents
                            <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Use this facility if you require specific documents during submission or approval" type="button">
                                <i class="fa fa-info-circle"></i>
                            </span>
                        </label>
                        <div class="pull-right">
                            <input type="checkbox" @isset($plugins['has_mandatory_upload']) checked @endif id="isMandatoryUpload" onchange="setCheckbox(this.checked, 'isMandatoryUpload');" name="has_mandatory_upload" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                        </div>
                        <a id="document_attachment_button" @if(!isset($plugins['has_mandatory_upload'])) style="display: none;" @endif data-toggle="modal" href="#new_document" class="btn btn-sm mb-1 green pull-right mr-1"><i class="fa fa-plus"> </i> Add document </a>

                    </div>

                    <div id="document_attachment_div" @if(!isset($plugins['has_mandatory_upload'])) style="display: none;" @endif>
                        <br>
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
                <br>
                <br>
                <div class="input-group" style="display:none;">
                    <a href="/merchant/profile/digitalsignature/iframe" class="iframe btn btn-sm green pull-right"> Digital signature </a>
                </div>
            </div>

            <div id="pgissignature" @isset($plugins['has_signature']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font"> Digital signature&nbsp; </span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_signature']) checked @endif id="issignature" onchange="disablePlugin(this.checked, 'plg16');" name="has_signature" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a href="/merchant/profile/digitalsignature/iframe" class="iframe btn btn-sm green pull-right"> Digital signature </a>
                </div>
            </div>

            <div id="pgispartial" @isset($plugins['has_partial']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Partial payment</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_partial']) checked @endif id="ispartial" name="is_partial" onchange="disablePlugin(this.checked, 'plg13');
                        showDebit('partial');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
                <div id="min_partial_payment_div" class="row mb-2" @if(isset($plugins['has_partial']) && $plugins['has_partial']=='1' ) style="display: block;" @else style="display:none;" @endif>
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">Minimum partial amount</label>
                        <div class="col-md-3">
                            <input type="number" step="0.01" min="50" @isset($plugins['partial_min_amount']) value="{{$plugins['partial_min_amount']}}" @else value="50" @endif class="form-control" id="pma" name="partial_min_amount">
                        </div>
                    </div>
                </div>
            </div>

            <div id="pgiswatermark" @isset($plugins['has_watermark']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Watermark</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_watermark']) checked @endif id="iswatermark" name="has_watermark" onchange="disablePlugin(this.checked, 'plg28');
                        " value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
                <div id="watermark_div" class="row mb-2">
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">Watermark text</label>
                        <div class="col-md-3">
                            <input type="text" maxlength="25" @isset($plugins['watermark_text']) value="{{$plugins['watermark_text']}}" @else value="DRAFT" @endif class="form-control" id="watermark_text" name="watermark_text">
                        </div>
                    </div>
                </div>
            </div>

            <div id="pgiscovering" @isset($plugins['has_covering_note']) @else style="display: none;" @endif>
                <hr>
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
                    <div class="form-group form-horizontal">
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
            </div>

            <div id="pgiscustnotification" @isset($plugins['has_custom_notification']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Customize notification text </span>

                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_custom_notification']) checked @endif id="iscustnotification" name="is_custom_notification" onchange="disablePlugin(this.checked, 'plg11');
                        showDebit('custnotification');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a href="/merchant/coveringnote/dynamicvariable" class="iframe btn btn-sm green pull-right"> Dynamic variables </a>

                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group form-horizontal">
                            <label class="control-label col-md-3 w-auto" style="min-width: 128px;">Email Subject</label>
                            <div class="col-md-6">
                                <input class="form-control" @isset($plugins['custom_email_subject']) value="{{$plugins['custom_email_subject']}}" @else value="Payment request from %COMPANY_NAME%" @endif type="text" maxlength="200" name="custom_subject" placeholder="Email subject">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group form-horizontal">
                            <label class="control-label col-md-3 w-auto" style="min-width: 128px;">SMS</label>
                            <div class="col-md-6">
                                <textarea class="form-control" type="text" maxlength="200" name="custom_sms" placeholder="Payment request SMS">@isset($plugins['custom_sms']) {{$plugins['custom_sms']}} @else You have received a payment request from %COMPANY_NAME% for amount %TOTAL_AMOUNT%. To make an online payment, access your bill via %SHORT_URL% @endif</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="pgissupplier" style="display: none;">
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Supplier</span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" id="issupplier" name="is_supplier" onchange="disablePlugin(this.checked, 'plg2');
                            showDebit('supplier');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a data-toggle="modal" href="#respond" class="btn btn-sm green pull-right "> <i class="fa fa-plus"> </i> Add new row </a>
                </div>
                <table id="t_new_supplier" class="table table-bordered table-hover">
                    <thead id="h_new_supplier" style="display: none;">
                        <tr>
                            <th class="td-c  default-font">
                                Supplier company name
                            </th>
                            <th class="td-c  default-font">
                                Contact person name
                            </th>
                            <th class="td-c  default-font">
                                Mobile
                            </th>
                            <th class="td-c  default-font">
                                Industry type
                            </th>

                            <th class="td-c">
                            </th>
                        </tr>
                    </thead>
                    <tbody id="new_supplier">

                    </tbody>
                </table>
            </div>


            <div id="pgiscustreminder" @isset($plugins['has_custom_reminder']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Customize reminder schedule </span>

                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['has_custom_reminder']) checked @endif id="iscustreminder" name="is_custom_reminder" onchange="disablePlugin(this.checked, 'plg12');
                        showDebit('custreminder');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a onclick="AddReminder();
                    tableHead('new_reminder');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>

                </div>
                <div style="">
                    <div class="" style="">
                        <table id="t_new_reminder" class="table table-bordered table-hover">
                            <thead id="h_new_reminder">
                                <tr>
                                    <th class="td-c  default-font" style="width: 200px;">
                                        Days before due date
                                    </th>
                                    <th class="td-c  default-font">
                                        Reminder email subject
                                    </th>
                                    <th class="td-c  default-font">
                                        Reminder SMS
                                    </th>

                                    <th class="td-c" style="width: 50px;">
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="new_reminder">
                                @if(!empty($plugins['reminders']))
                                @foreach($plugins['reminders'] as $day=>$r)
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="number" name="reminder[]" value="{{$day}}" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_subject[]" value="{{$r['email_subject']}}" maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_sms[]" value="{{$r['sms']}}" maxlength="200" class="form-control input-sm" placeholder="Reminder SMS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="number" name="reminder[]" value="3" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_sms[]" maxlength="200" class="form-control input-sm" placeholder="Reminder SMS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="number" name="reminder[]" value="1" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_sms[]" maxlength="200" class="form-control input-sm" placeholder="Reminder SMS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="number" name="reminder[]" value="0" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder mail subject">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-icon right">
                                            <input type="text" name="reminder_sms[]" maxlength="200" class="form-control input-sm" placeholder="Reminder SMS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="pgisonlinepayments" @isset($plugins['has_online_payments']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Enable/Disable payments</span>
                    <div class="pull-right">
                        <input type="checkbox" @if(isset($plugins['has_online_payments']) && $plugins['has_online_payments']=='1' ) checked="" value="1" @else value="0" @endif id="isonlinepayments" onchange="disablePlugin(this.checked, 'plg14');checkValue('is_online_payments_', this.checked);
                        showDebit('online_payments');" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                        <input type="hidden" id="is_online_payments_" @if(isset($plugins['has_online_payments']) && $plugins['has_online_payments']=='1' ) value="1" @else value="0" @endif name="has_online_payments" />
                    </div>
                </div>
                <div class="row mb-2" id="enable_payments_div" @if(isset($plugins['has_online_payments']) && $plugins['has_online_payments']=='1' ) style="display: block;" @else style="display:none;" @endif>
                    <div class="form-group form-horizontal">
                        <label class="control-label col-md-3 w-auto">Enable online payments</label>
                        <div class="col-md-3">
                            <input type="hidden" id="is_enable_payments_" name="enable_payments" @if(isset($plugins['enable_payments']) && $plugins['enable_payments']=='1' ) value="1" @else value="0" @endif />
                            <input type="checkbox" id="isenablepayments" @if(isset($plugins['enable_payments']) && $plugins['enable_payments']=='1' ) checked="" value="1" @else value="0" @endif onchange="checkValue('is_enable_payments_', this.checked);" data-size="small" class="make-switch" data-on-text="&nbsp;YES&nbsp;&nbsp;" data-off-text="&nbsp;NO&nbsp;">
                        </div>
                    </div>
                </div>
            </div>

            <div id="pgiseinvoice" @isset($plugins['has_e_invoice']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">E Invoice&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['has_e_invoice']) checked @endif id="iseinvoice" onchange="disablePlugin(this.checked, 'plg21');" name="is_einvoice" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>

            <div id="pgisrevision" @isset($plugins['save_revision_history']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font">Revision history&nbsp;</span>
                    <div class="pull-right">
                        <input type="checkbox" @isset($plugins['save_revision_history']) checked @endif id="isrevision" onchange="disablePlugin(this.checked, 'plg22');" name="is_revision" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </div>
            </div>

            <div id="pginvoiceoutput" @isset($plugins['invoice_output']) @else style="display: none;" @endif>
                <hr>
                <div class="mb-2">
                    <span class="form-section base-font"> Invoice Output&nbsp; </span>
                    <div class="pull-right ml-1">
                        <input type="checkbox" @isset($plugins['invoice_output']) checked @endif onchange="disablePlugin(this.checked, 'plg23');" id="invoiceoutput" name="invoice_output" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                    <a href="#" class="btn btn-sm green pull-right ml-1 customize-output-btn">Customize output </a>
                </div>
            </div>



        </div>
    </div>


    <!-- Supplier list start -->
    <div class="modal fade bs-modal-lg" id="respond" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Select supplier
                    <button type="button" id="closeguest" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">

                    @if(!empty($supplier))
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Supplier company name
                                </th>
                                <th class="td-c">
                                    Contact person name
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>

                                <th class="td-c">
                                    Select
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier as $v)
                            <tr>
                                <td class="td-c">
                                    <div id="spname{{$v['supplier_id']}}">{{$v['supplier_company_name']}}</div>
                                </td>
                                <td class="td-c">
                                    <div id="spcontact{{$v['supplier_id']}}">{{$v['contact_person_name']}}</div>
                                </td>
                                <td class="td-c">
                                    <div id="spmobile{{$v['supplier_id']}}">{{$v['mobile1']}}</div>
                                </td>

                                <td class="td-c">
                                    <div id="spemail{{$v['supplier_id']}}" style="display: none;">{{$v['email_id1']}}</div>
                                    <input type="checkbox" @if(!empty($plugins['supplier'])) @if(in_array($v['supplier_id'],$plugins['supplier'])) checked="" @endif @endif class="icheck" value="{{$v['supplier_id']}}" id="spid{{$v['supplier_id']}}" onchange="AddsupplierRow(this.value);" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row no-margin">
                        <button type="button" class="btn blue pull-right" data-dismiss="modal" aria-hidden="true">Done</button>
                    </div>

                    @else
                    <br>
                    <div align="center">
                        <h5>No records found</h5>
                    </div>
                    @endif
                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>


    <div class="modal fade in" id="plugins" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closebutton1" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Choose plugins</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="flex-container">
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Invoice number</p>
                                            <p class="mb-4 default-font"> Add sequential invoice numbers to your invoices to ease billing. Enable system generated invoice numbers or add them manually to each invoice as per your needs.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plgf9" @isset($plugins['has_invoice_number']) checked @endif onchange="pluginChange(this.checked, 'isinvoicenumber',9);" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Covering note</p>
                                            <p class="mb-4 default-font"> Attach a personalized covering note with your invoices. Your customers will receive their invoices with covering notes as a PDF attachment with a payment option within.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg10" @isset($plugins['has_covering_note']) checked @endif onchange="pluginChange(this.checked, 'iscovering');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> File upload</p>
                                            <p class="mb-4 default-font">Upload the document or image you want to attach to the invoices your customers receive via email & SMS.</p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg15" @isset($plugins['has_upload']) checked @endif onchange="pluginChange(this.checked, 'isupload');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Revision history</p>
                                            <p class="mb-4 default-font"> Store invoice revision history
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg22" @isset($plugins['save_revision_history']) checked @endif onchange="pluginChange(this.checked, 'isrevision');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Invoice Output</p>
                                            <p class="mb-4 default-font"> Customize the appearance of your invoice i.e. configure how your G702 / G703 formats appear to your customers and the generated PDF.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg23" @isset($plugins['invoice_output']) checked @endif onchange="pluginChange(this.checked, 'invoiceoutput');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0">Watermark</p>
                                            <p class="mb-4 default-font">Add a custom text as a watermark to your PDF documents and web links.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg28" @isset($plugins['has_watermark']) checked @endif onchange="pluginChange(this.checked, 'iswatermark');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Customize reminder schedule</p>
                                            <p class="mb-4 default-font"> Customize the schedule of the payment reminders sent to your customers via SMS & email. Personalize the frequency of the reminders sent before the invoice due date.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg12" @isset($plugins['has_custom_reminder']) checked @endif onchange="pluginChange(this.checked, 'iscustreminder');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Customize notification text</p>
                                            <p class="mb-4 default-font"> Customize the email subject & SMS text sent to your customer once an invoice is created and sent. Personalize the notifications sent via SMS & email as per your needs.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg11" @isset($plugins['has_custom_notification']) checked @endif onchange="pluginChange(this.checked, 'iscustnotification');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Partial payment</p>
                                            <p class="mb-4 default-font">Enable your customers to pay their invoice amounts in parts. Personalize and set the minimum amount for partial payments as per your requirements.</p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg13" @isset($plugins['has_partial']) checked @endif onchange="pluginChange(this.checked, 'ispartial');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Auto collect</p>
                                            <p class="mb-4 default-font"> Automate recurring payment collections from your customers. Enable your customers to pay for your items of sale/services on a recurring schedule.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg14" @isset($plugins['has_autocollect']) checked @endif onchange="pluginChange(this.checked, 'isautocollect');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Expiry date</p>
                                            <p class="mb-4 default-font"> Affix an expiry date to your invoices. Your invoice will no longer be valid post the date of expiration specified.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plgf1" @isset($plugins['has_expiry_date']) checked @endif onchange="pluginChange(this.checked, 'isexpirydate',1);" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 flex-item">
                                    <div class="panel box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Customize payment receipt</p>
                                            <p class="mb-4 default-font"> Auto-generated receipt is shown to your customer after payment of invoice or estimate. Add or change the values displayed to your customer on the receipt.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg20" @isset($plugins['has_customized_payment_receipt']) checked @endif onchange="pluginChange(this.checked, 'iscustomizedpaymentreceipt',20);" name="has_customized_payment_receipt" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Enable/Disable payments</p>
                                            <p class="mb-4 default-font">Get the ability to switch on or off online payment options for your invoices or estimates.</p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg14" @isset($plugins['has_online_payments']) checked @endif onchange="pluginChange(this.checked, 'isonlinepayments');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> CC Emails</p>
                                            <p class="mb-4 default-font"> Automate email notifications for your team and/or suppliers when invoices are created & sent. Enable the CC Emails plugin to send a copy of your invoices to your team along with your customers.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg4" @isset($plugins['cc_email']) checked @endif onchange="pluginChange(this.checked, 'iscc');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Round off</p>
                                            <p class="mb-4 default-font">Enable the removal of decimal points from the final invoice amount by rounding off to the nearest value. Round off the total invoice amount with the applicable taxes calculated and added. </p>
                                            <div class="pull-right plugin-button">
                                                <input type="checkbox" id="plg5" @isset($plugins['roundoff']) checked @endif onchange="pluginChange(this.checked, 'isroundoff');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Digital signature</p>
                                            <p class="mb-4 default-font">Create and/or personalize your digital signature and add them to the invoices created & sent to your customers.</p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg16" @isset($plugins['has_signature']) checked @endif onchange="pluginChange(this.checked, 'issignature');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($selectedTemplateType != 'construction')
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0">Deductibles</p>
                                            <p class="mb-4 default-font">Let your customers subtract tax deductions at source (TDS deductions) from their invoice/estimate amounts before payments. The TDS amount will be automatically deducted before your customers make a payment.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg1" @isset($plugins['has_deductible']) checked @endif onchange="pluginChange(this.checked, 'isdebit');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Supplier</p>
                                            <p class="mb-4 default-font">Notify your suppliers via email & SMS once an invoice/estimate is paid by the customer. Automate payment notifications for your suppliers and vendors.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg2" @isset($plugins['has_custom_reminder']) checked @endif onchange="pluginChange(this.checked, 'issupplier');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Coupon</p>
                                            <p class="mb-4 default-font"> Attach discount coupons to your invoices/estimates. Your customers can apply discounts on their invoice amounts via coupon codes before making a payment.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg3" @isset($plugins['has_coupon']) checked @endif onchange="pluginChange(this.checked, 'iscoupon');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Acknowledgement section</p>
                                            <p class="mb-4 default-font">Add an acknowledgement section within your invoices. Enable the plugin to include an acknowledgement towards the bottom of your invoices.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg6" @isset($plugins['has_acknowledgement']) checked @endif onchange="pluginChange(this.checked, 'isacknowledgement');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($selectedTemplateType != 'construction')
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Franchise</p>
                                            <p class="mb-4 default-font"> Attach a franchise to your invoice, so that the invoice may be raised in the name of the franchise and the customer's payment split with the same. Enable automated SMS & email notifications for your franchise.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg7" @isset($plugins['has_franchise']) checked @endif onchange="pluginChange(this.checked, 'isfranchise');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Vendor</p>
                                            <p class="mb-4 default-font"> Attach vendor(s) to your invoice to split invoice payments. Automate payments directly into your vendor’s account.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg71" @isset($plugins['has_vendor']) checked @endif onchange="pluginChange(this.checked, 'isvendor');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif


                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Pre-paid invoices</p>
                                            <p class="mb-4 default-font"> Create & send invoices for payments which have already been received. Invoices which have been paid in full will not present payment options/links to your customer.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg8" @isset($plugins['is_prepaid']) checked @endif onchange="pluginChange(this.checked, 'isprepaid');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Webhook</p>
                                            <p class="mb-4 default-font"> Web link which will be invoked once an online payment is made against your invoice.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg9" onchange="pluginChange(this.checked, 'iswebhook');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->

                                @if($selectedTemplateType != 'construction')
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> Previous dues</p>
                                            <p class="mb-4 default-font"> Enable the inclusion of previous dues from the customer before an invoice is created & sent. The outstanding dues from the customer will be auto-calculated and added to the invoice amount when creating a new invoice.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plgf4" @isset($plugins['has_previous_due']) checked @endif onchange="pluginChange(this.checked, 'ispreviousdue',4);" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif


                                @if($selectedTemplateType != 'construction')
                                <div class="col-xs-12 col-sm-6 col-md-4  flex-item">
                                    <div class="panel  box-plugin">
                                        <div class="panel-body">
                                            <p class="form-section mt-0"> E Invoice</p>
                                            <p class="mb-4 default-font"> Create GST-compliant e-invoices and upload them to the Invoice Registration Portal (IRP). The invoices will be validated with a unique Invoice Reference Number (IRN), digital signature, and QR code.
                                            </p>
                                            <div class="plugin-button">
                                                <input type="checkbox" id="plg21" @isset($plugins['has_e_invoice']) checked @endif onchange="pluginChange(this.checked, 'iseinvoice');" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" id="closebuttonp" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                <form action="/merchant/coveringnote/save" method="post" id="covering_frm" class="form-horizontal form-row-sepe">
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
        function showsupplier() {
            if ($('#issupplier').is(':checked')) {
                $("#supplierdiv").slideDown(500).fadeIn();
            } else {
                $("#supplierdiv").slideUp(500).fadeOut();
            }
        }

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