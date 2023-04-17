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

    .control-label {
        color: #394242 !important;
    }

    .popovers {
        color: #859494;
        vertical-align: text-bottom;
    }
</style>
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">

<script src="/assets/admin/layout/scripts/coveringnote.js" type="text/javascript"></script>

<div>
    <div class="col-md-12">
        <div class="portlet light bordered">
            <h3 class="form-section">Settings</h3>
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">Properties</a></li>
                <li role="presentation"><a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">Notifications</a></li>
            </ul>
            <div class="portlet-body">

                <form action="/merchant/contract/store" method="post">
                    <input type="hidden" name="template_id" value="{{$template_id}}">
                    <div class="tab-content" style="">
                        <div class="tab-pane active" id="tab1">

                            <div id="pgisupload">
                                <div class="mb-2">
                                    <span class="form-section base-font">Invoice level attachments</span>
                                </div>
                                <label class="control-label w-auto">Setup required documents
                                    <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Setup mandatory or non-mandatory documents that need to be uploaded while creating the invoice" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                        </svg>
                                    </span>
                                </label>
                                <div class="pull-right">
                                    <input value="View document" type="hidden" name="upload_file_label">
                                    <input value="1" type="hidden" name="has_upload">
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



                            <div id="pgiswatermark">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">Watermark

                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Add a custom text as a watermark to your PDF documents and web links." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
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



                            <div id="pgisrevision">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">Revision history&nbsp;
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Maintain a revision history of all changes made to your invoice." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                    <div class="pull-right">
                                        <input type="checkbox" @isset($plugins['save_revision_history']) checked @endif id="isrevision" onchange="disablePlugin(this.checked, 'plg22');" name="is_revision" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>
                                </div>
                            </div>

                            <div id="pginvoiceoutput">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font"> AIA license available
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Switch this toggle ON if you have a valid AIA license. This will create invoices with the AIA logo and format." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                    <div class="pull-right ml-1">
                                        <input type="checkbox" @isset($plugins['has_aia_license']) checked @endif id="plglicenseavailable" name="has_aia_license" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>
                                </div>
                            </div>
                            <div id="pgschedulevalue">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font"> Scheduled value split
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content='Show the impact of change orders on scheduled value i.e. against all bill codes display "Change from previous applications", "Change this period" and "Current CO" columns' type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                    <div class="pull-right ml-1">
                                        <input type="checkbox" onchange="disablePlugin(this.checked, 'plgincludestorematerials')"
                                         @isset($plugins['has_schedule_value']) checked @endif id="plgscheduleavailable" name="has_schedule_value" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>
                                </div>
                            </div>
                            <div id="pginvoiceoutput">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font"> Stored materials inclusion
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="To include stored materials in ''from previous application'' switch this toggle to On." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                    <div class="pull-right ml-1">
                                        <input type="checkbox" @isset($plugins['include_store_materials']) checked @endif name="include_store_materials" id="plgincludestorematerials" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>
                                </div>
                            </div>
                            <!--
                        <div id="pgissignature" >
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font"> Digital signature&nbsp; </span>
                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_signature']) checked @endif id="issignature" onchange="disablePlugin(this.checked, 'plg16');" name="has_signature" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                                <a href="/merchant/profile/digitalsignature/iframe" class="iframe btn btn-sm green pull-right"> Digital signature </a>
                            </div>
                        </div>-->

                            <div id="pgispartial">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">Partial payment
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Enable logging part payments. Personalize and set the minimum amount for partial payments as per your requirements." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
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

                            <div id="invoiceSequence">
                                <!--add sequence modal-->
                                @include('app.merchant.project.add-sequence-modal')
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">Invoice sequence number
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Select a sequence number that you would like to associate with the invoices created with this contract" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 w-auto">Select a sequence
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Pick an existing sequence number or create a new sequence number. To create a new sequence number, enter the start of your sequence i.e. If you want to start your sequence at 100 enter 100" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </label>
                                    <div class="col-md-3">
                                        <select required class="form-control select2me" data-placeholder="Select" name="sequence_number" id="seq_no_drpdwn">
                                            <option value="">Select sequence</option>
                                            @if(!empty($invoiceSeq))
                                            @foreach($invoiceSeq as $f)
                                            <option value="{{$f['auto_invoice_id']}}" @if($project_data->sequence_number == $f['auto_invoice_id']) selected @endif>{{$f['prefix']}}{{$f['seprator']}}{{$f['val']+1}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-1 ml-minus-1">
                                        <a title="New invoice number" onclick="showNewSequencePanel()" class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                    </div>
                                </div>
                                <div class="row" id="newSequencePanel" hidden>
                                    <div class="portlet light bordered col-md-6">
                                        <div class="portlet-body">
                                        <div class="form-group " style="margin-left: 140px;">
                                            <div class="col-md-6">
                                                <input class="form-control" type="text" id="project_prefix" name="prefix" placeholder="Prefix" maxlength="20" onkeyup="changeSeparatorVal(this.value)" value="{{ old('prefix') }}" />
                                            </div>
                                            <div class="col-md-2 ml-minus-1">
                                                <input class="form-control" type="text" name="seprator" placeholder="Separator" maxlength="5" value="{{ old('seprator')!='' ? old('seprator') : '-' }}" id="separator" />
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control" onkeyup=imposeMinMax(this) type="number" min="0" max="99999999" name="sequence" placeholder="Seq. no" value="{{ old('sequence') }}" id="seq_no" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-3">
                                                <button type="button" onclick="saveSequence()" class="btn btn-sm blue">Save sequence</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" onclick="showNewSequencePanel()" class="btn default btn-sm">Cancel</button>
                                                <p id="seq_error" style="color: red;"></p>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <div>
                                <div class="mb-2 desk">
                                    <span class="form-section base-font">Covering note

                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Send emails with a covering note. Invoices will be sent as a PDF attachment." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>

                                    <div class=" pull-right ml-1">
                                        <input type="checkbox" @isset($plugins['has_covering_note']) checked @endif id="iscovering" name="is_covering" onchange="disablePlugin(this.checked, 'plg10');
                        showDebit('covering');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>
                                </div>

                                <div id="pgiscovering" @isset($plugins['has_covering_note']) @else style="display: none;" @endif class="row mb-2">
                                    <label class="control-label col-md-3 w-auto">Select covering note</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="covering_select" name="default_covering">
                                            <option value="0">Select Template</option>
                                            @if(!empty($coveringNotes))
                                            @foreach($coveringNotes as $v)
                                            <option @isset($plugins['default_covering_note']) @if($plugins['default_covering_note']==$v->covering_id) selected @endif @endif value="{{$v->covering_id}}">{{$v->template_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <a data-toggle="modal" href="#new_covering" class="btn btn-sm mb-1 green pull-right" style="margin-right: 15px;">Add new note </a>
                                    <a href="/merchant/coveringnote/dynamicvariable" style="text-decoration: underline; margin-top: 5px;" class="iframe pull-right mr-1">Dynamic variables </a>

                                </div>
                            </div>


                            <div class="">
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">CC Emails
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Automate email notifications to internal or external parties when invoices are created & sent. Enable the CC Emails plugin to send a copy of your invoices." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>
                                    <div class="pull-right ml-1">
                                        <input type="checkbox" @isset($plugins['has_cc']) checked @endif id="iscc" name="is_cc" onchange="disablePlugin(this.checked, 'plg4'); showDebit('cc');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">

                                    </div>

                                </div>
                                <div id="pgiscc" @isset($plugins['has_cc']) @else style="display: none;" @endif>
                                    <div style="max-width: 500px;">
                                        <a onclick="AddCC(); tableHead('new_cc');" class="btn btn-sm green pull-left mb-1"> <i class="fa fa-plus"> </i> Add new row </a>

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

                            <div>
                                <hr>
                                <div class="mb-2">
                                    <span class="form-section base-font">Customize reminder schedule
                                        <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Customize the schedule of invoice due reminders sent to your customers via email." type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </span>
                                    </span>

                                    <div class="pull-right ml-1">
                                        <input type="checkbox" @isset($plugins['has_custom_reminder']) checked @endif id="iscustreminder" name="is_custom_reminder" onchange="disablePlugin(this.checked, 'plg12');
                        showDebit('custreminder');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    </div>


                                </div>
                                <div id="pgiscustreminder" @isset($plugins['has_custom_reminder']) @else style="display: none;" @endif>
                                    <a onclick="AddReminder2('before');
                    tableHead('new_reminder_before');" class="btn btn-sm green pull-left mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
                                    <a href="/merchant/coveringnote/dynamicvariable" style="text-decoration: underline; margin-top: 5px;" class="iframe pull-right ml-1">Dynamic variables </a>

                                    <table id="t_new_reminder_before" class="table table-bordered table-hover">
                                        <thead id="h_new_reminder_before">
                                            <tr>
                                                <th class="td-c  default-font" style="width: 200px;">
                                                    Days before due date
                                                </th>
                                                <th class="td-c  default-font">
                                                    Reminder email subject
                                                </th>
                                                <th class="td-c" style="width: 50px;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="new_reminder_before">
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
                                                        <input type="text" name="reminder_subject[]" value="{{$r['email_subject']}}" maxlength="250" class="form-control input-sm" placeholder="Reminder email subject">
                                                        <input type="hidden" name="reminder_type[]" value="before">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
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
                                                        <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder email subject">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
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
                                                        <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder email subject">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
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
                                                        <input type="text" name="reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Reminder email subject">
                                                        <input type="hidden" name="reminder_type[]" value="before">
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>




                                    <a onclick="AddReminder2('after');
                    tableHead('new_reminder_after');" class="btn btn-sm green pull-left mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
                                    <table id="t_new_reminder_after" class="table table-bordered table-hover">
                                        <thead id="h_new_reminder_after">
                                            <tr>
                                                <th class="td-c  default-font" style="width: 200px;">
                                                    Days after due date
                                                </th>
                                                <th class="td-c  default-font">
                                                    Reminder email subject
                                                </th>
                                                <th class="td-c" style="width: 50px;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="new_reminder_after">
                                            @if(!empty($plugins['reminders_after']))
                                            @foreach($plugins['reminders_after'] as $day=>$r)
                                            <tr>
                                                <td>
                                                    <div class="input-icon right">
                                                        <input type="number" name="reminder[]" value="{{$day}}" step="1" max="100" class="form-control input-sm" placeholder="Add day">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-icon right">
                                                        <input type="text" name="reminder_subject[]" value="{{$r['email_subject']}}" maxlength="250" class="form-control input-sm" placeholder="Reminder email subject">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="reminder_type[]" value="after">
                                                    <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                            tableHead('new_reminder_after');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                                </td>
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
                                                <div style="text-align: left;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear Team,<br><br>
							  Please find attached invoice dated : %BILL_DATE% for %PROJECT_NAME%.<br><br>
                              This invoice for amount $%PAYABLE_AMOUNT% is due by %DUE_DATE% - %INVOICE_LINK%<br><br>
                              In case of queries related to this invoice, please reply back to this email.
                                <br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Mail Subject <span class="required">*
                                            </span></label>
                                        <div class="col-md-6">
                                            <input type="text" required maxlength="100" name="subject" class="form-control" value="%PROJECT_NAME% invoice">
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
                    <form action="/merchant/coveringnote/save" method="post" class="form-horizontal form-row-sepe">
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
    @section('footer')
    <script src="/assets/global/plugins/summernote/summernote.min.js"></script>

    <script>
        $('.tncrich').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['undo', 'redo', 'codeview']]
            ],
            callbacks: {
                onKeydown: function(e) {
                    var t = e.currentTarget.innerText;
                    if (t.trim().length >= 5000) {
                        //delete keys, arrow keys, copy, cut
                        if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                            e.preventDefault();
                    }
                },
                onKeyup: function(e) {
                    var t = e.currentTarget.innerText;
                    $('#maxContentPost').text(5000 - t.trim().length);
                },
                onPaste: function(e) {
                    var t = e.currentTarget.innerText;
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    var maxPaste = bufferText.length;
                    if (t.length + bufferText.length > 5000) {
                        maxPaste = 5000 - t.length;
                    }
                    if (maxPaste > 0) {
                        document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                    }
                    $('#maxContentPost').text(5000 - t.length);
                }
            }
        });
    </script>
    @endsection