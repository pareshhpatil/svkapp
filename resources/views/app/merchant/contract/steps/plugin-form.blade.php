
<form action="{{$post_url}}" method="post" id="plugin-form">
    <div class="portlet light bordered">
        <h3 class="form-section">Settings</h3>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">Properties</a></li>
            <li role="presentation"><a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">Notifications</a></li>
            <li role="presentation"><a href="#tab3" data-toggle="tab" class="step" aria-expanded="true">Invoice format</a></li>
        </ul>
        <div class="portlet-body">
                <input type="hidden" name="template_id" value="{{$template_id}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="tab-content">
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
            
                        @if(!isset($show_sequence))
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
                        @endif
                    </div>
                    <!-- Tab 2 -->
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
                        <!--add reminder-->
                        @if(Session::get('user_role') == 'Admin')
                        <div>
                            <script src='/assets/admin/layout/scripts/plugin/virtual-select.min.js'></script>
                            <script>
                                @php
                                $sub_users_json = str_replace("\\", '\\\\', $sub_users);
                                $sub_users_json = str_replace("'", "\'", $sub_users_json);
                                $sub_users_json = str_replace('"', '\\"', $sub_users_json);
                                @endphp
                                
                                var sub_users = JSON.parse('{!! $sub_users_json !!}');
                            </script>
                            <hr>
                            <div class="mb-2">
                                <span class="form-section base-font">Add reminder
                                    <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Remind invoice creators to start creating invoices for a project by email and by creating a task in their Briq dashboard" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                        </svg>
                                    </span>
                                </span>
                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['has_internal_reminder']) checked @endif id="is_internal_reminder" name="is_internal_reminder" onchange="disablePlugin(this.checked, 'plg15');
                                                    showDebit('_internal_reminder');" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                    {{-- <input id="is_internal_reminder_txt" name="is_internal_reminder_txt" value="0" /> --}}
                                </div>
                            </div>
                            <div id="pg_is_internal_reminder" @isset($plugins['has_internal_reminder']) @else style="display: none;" @endif>
                                <a onclick="AddInternalReminder();
                                                tableHead('new_internal_reminder_before');" class="btn btn-sm green pull-left mb-1"> <i class="fa fa-plus"> </i> Add new row </a>
                                <a href="/merchant/coveringnote/dynamicvariable" style="text-decoration: underline; margin-top: 5px;" class="iframe pull-right ml-1">Dynamic variables </a>
            
                                <table id="t_new_internal_reminder_before" class="table table-bordered table-hover">
                                    <thead id="h_new_internal_reminder_before">
                                        <tr>
                                            <th class="td-c default-font" style="width: 200px;">
                                                Select user
                                            </th>
                                            <th class="td-c default-font">
                                                Subject
                                            </th>
                                            <th class="td-c default-font" style="width:23%">
                                                Reminder date
                                            </th>
                                            <th class="td-c default-font" style="width:20%">
                                                End after
                                            </th>
                                            <th class="td-c" style="width: 5%;">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="new_internal_reminder_before">
                                        @if(!empty($plugins['internal_reminders']))
                                        @php $i=0 @endphp
                                        @foreach($plugins['internal_reminders'] as $day=>$r)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="internal_reminder_id[]" value="@isset($r['id']) {{$r['id']}} @endisset" />
                                                <div class="input-icon right">
                                                    <div id="sub_user_drpdwn{{$i}}"></div>
                                                    <script>
                                                        VirtualSelect.init({
                                                            ele: '#sub_user_drpdwn'+{{$i}},
                                                            options: sub_users,
                                                            dropboxWrapper: 'body',
                                                            multiple: true,
                                                            name: 'reminder_user[]',
                                                            selectedValue: [{{$r['user_id']}}],
                                                        });
                                                        //virtualSelectInit({{$i}});
                                                    </script>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-icon right">
                                                    <input type="text" name="internal_reminder_subject[]" value="{{$r['subject']}}" maxlength="250" class="form-control input-sm" placeholder="Invoice creation reminder" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-icon right">
                                                    <select class="form-control input-sm" id="reminder_date_type" onchange="showReminderDateInput(this.value,{{$i}})" name="reminder_date_type[]">
                                                        <option @if($r['reminder_date_type']=='1' ) selected @endif value="1">Last day of every month</option>
                                                        <option @if($r['reminder_date_type']=='2' ) selected @endif value="2">1st of every month</option>
                                                        <option @if($r['reminder_date_type']=='3' ) selected @endif value="3">Last weekday of every month</option>
                                                        <option @if($r['reminder_date_type']=='4' ) selected @endif value="4">30 days from last invoice bill date</option>
                                                        <option @if($r['reminder_date_type']=='5' ) selected @endif value="5">Custom</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="input-icon right">
                                                    <div id="last_day_evry_month_div" style="display:none" class="reminder_date_div{{$i}}">
                                                        <input type="text" id="last_day_evry_month_dt" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value='<x-localize :date="$current_month_last_date" type="date" />' data-date-today-highlight="true" autocomplete="off" @if($r['reminder_date_type']!='1' ) disabled @endif />
                                                    </div>
                                                    <div id="1st_day_evry_month_div{{$i}}" style={{($r['reminder_date_type']=='2') ? "display:block" : "display:none"}} class="reminder_date_div{{$i}}">
                                                        <input type="text" id="1st_day_evry_month_dt{{$i}}" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value='<x-localize :date="$current_month_1st_date" type="date" />' data-date-today-highlight="true" autocomplete="off" @if($r['reminder_date_type']!='2' ) disabled @endif />
                                                    </div>
                                                    <div id="week_day_div{{$i}}" style={{($r['reminder_date_type']=='3') ? "display:block" : "display:none"}} class="reminder_date_div{{$i}}">
                                                        <select class="form-control input-sm" name="reminder_date[]" @if($r['reminder_date_type']!='3' ) disabled @endif>
                                                            <option value="Monday">Monday</option>
                                                            <option value="Tuesday">Tuesday</option>
                                                            <option value="Wednesday">Wednesday</option>
                                                            <option value="Thursday">Thursday</option>
                                                            <option value="Friday">Friday</option>
                                                            <option value="Saturday">Saturday</option>
                                                            <option value="Sunday">Sunday</option>
                                                        </select>
                                                    </div>
                                                    <div id="30days_div{{$i}}" style={{($r['reminder_date_type']=='4') ? "display:block" : "display:none"}} class="reminder_date_div{{$i}}">
                                                        <input type="number" name="reminder_date[]" min="1" max="31" class="form-control input-sm" value="30" @if($r['reminder_date_type']!='4' ) disabled @endif />
                                                    </div>
                                                    <div id="custom_div{{$i}}" class="reminder_date_div{{$i}}" style={{($r['reminder_date_type']=='5') ? "display:block" : "display:none"}}>
                                                        @if($r['reminder_date_type']=='5')
                                                        <span id="custom_summary{{$i}}">
                                                            Repeat every {{$r['repeat_every']}} {{$r['repeat_type']}}
                                                            @if($r['repeat_type']=='month')
                                                            <br />
                                                            {{str_replace('_',' ',$r['repeat_on'])}}
                                                            @elseif($r['repeat_type']=='week')
                                                            <br />
                                                            @php $week=json_decode($r['repeat_on'],1) @endphp
                                                            {{implode(', ',$week)}}
                                                            @endif
                                                        </span>
                                                        @endif
                                                        <input name="reminder_date[]" value="" @if($r['reminder_date_type']!='5' ) disabled @endif hidden />
                                                    </div>
                                                    <input name="repeat_every[]" id="repeat_every_txt{{$i}}" value="{{$r['repeat_every']}}" hidden />
                                                    <input name="repeat_type[]" id="repeat_type{{$i}}" value="{{$r['repeat_type']}}" hidden />
                                                    <input name="repeat_on[]" id="repeat_on{{$i}}" value="{{$r['repeat_on']}}" hidden />
                                                    <div>
                                            </td>
                                            <td>
                                                <div class="input-icon right">
                                                    <select class="form-control input-sm" id="end_date_type" onchange="showEndDateInput(this.value,{{$i}})" name="end_date_type[]">
                                                        <option @if($r['end_date_type']=='1' ) selected @endif value="1">Number of occurrences</option>
                                                        <option @if($r['end_date_type']=='2' ) selected @endif value="2">End date</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="input-icon right">
                                                    <div id="occurences_div{{$i}}" style={{($r['end_date_type']=='1') ? "display:block" : "display:none"}}>
                                                        <input type="number" name="end_date[]" min="1" max="100" class="form-control input-sm" placeholder="Enter occurrence" value="{{$r['end_date']}}" @if($r['end_date_type']!='1') disabled @endif />
                                                    </div>
                                                    <div id="end_date_div{{$i}}" style={{($r['end_date_type']=='2') ? "display:block" : "display:none"}}>
                                                        <input type="text" name="end_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" @if($r['end_date_type']=='2') value='<x-localize :date="$r['end_date']" type="date" />' @endif autocomplete="off" @if($r['end_date_type']!='2') disabled @endif />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                                                tableHead('new_internal_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                            </td>
                                        </tr>
                                        @php $i++ @endphp
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>
                                                <div class="input-icon right">
                                                    <div id="sub_user_drpdwn0"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-icon right">
                                                    <input type="text" name="internal_reminder_subject[]" maxlength="250" class="form-control input-sm" placeholder="Invoice creation reminder">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-icon right">
                                                    <select class="form-control input-sm" id="reminder_date_type" onchange="showReminderDateInput(this.value,0)" name="reminder_date_type[]">
                                                        <option value="1">Last day of every month</option>
                                                        <option value="2">1st of every month</option>
                                                        <option value="3">Last weekday of every month</option>
                                                        <option value="4">30 days from last invoice bill date</option>
                                                        <option value="5">Custom</option>
                                                    </select>
                                                </div>
                                                <br>
            
                                                <div class="input-icon right">
                                                    <div id="last_day_evry_month_div" style="display:none" class="reminder_date_div0">
                                                        <input type="text" id="last_day_evry_month_dt0" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value='<x-localize :date="$current_month_last_date" type="date" />' data-date-today-highlight="true" autocomplete="off" />
                                                    </div>
                                                    <div id="1st_day_evry_month_div0" style="display:none" class="reminder_date_div0">
                                                        <input type="text" id="1st_day_evry_month_dt0" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value='<x-localize :date="$current_month_1st_date" type="date" />' data-date-today-highlight="true" autocomplete="off" disabled />
                                                    </div>
                                                    <div id="week_day_div0" style="display:none" class="reminder_date_div0">
                                                        <select class="form-control input-sm" name="reminder_date[]" disabled>
                                                            <option value="Monday">Monday</option>
                                                            <option value="Tuesday">Tuesday</option>
                                                            <option value="Wednesday">Wednesday</option>
                                                            <option value="Thursday">Thursday</option>
                                                            <option value="Friday">Friday</option>
                                                            <option value="Saturday">Saturday</option>
                                                            <option value="Sunday">Sunday</option>
                                                        </select>
                                                    </div>
                                                    <div id="30days_div0" style="display:none" class="reminder_date_div0">
                                                        <input type="number" name="reminder_date[]" min="1" max="31" class="form-control input-sm" value="30" disabled />
                                                    </div>
                                                    <div id="custom_div0" class="reminder_date_div0" style="display:none">
                                                        <span id="custom_summary0"></span><input name="reminder_date[]" value="" hidden />
                                                    </div>
                                                    <input name="repeat_every[]" id="repeat_every_txt0" value="" hidden />
                                                    <input name="repeat_type[]" id="repeat_type0" value="" hidden />
                                                    <input name="repeat_on[]" id="repeat_on0" value="" hidden />
                                                    <div>
                                            </td>
                                            <td>
            
                                                <div class="input-icon right">
                                                    <select class="form-control input-sm" id="end_date_type" onchange="showEndDateInput(this.value,0)" name="end_date_type[]">
                                                        <option value="1">Number of occurrences</option>
                                                        <option value="2">End date</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="input-icon right">
                                                    <div id="occurences_div0" style="display:block;">
                                                        <input type="number" name="end_date[]" min="1" max="100" class="form-control input-sm" placeholder="Enter occurrence" />
                                                    </div>
                                                    <div id="end_date_div0" style="display:none;">
                                                        <input type="text" name="end_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" autocomplete="off" disabled />
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="javascript:;" onclick="$(this).closest('tr').remove();
                                                            tableHead('new_internal_reminder_before');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <!-- end of add reminder -->
                    </div>
            
                    <!-- Tab 3 -->
                    <div class="tab-pane" id="tab3">
                        <div>
                            <div class="mb-2">
                                <span class="form-section base-font">List all change orders
                                    <span class="popovers" data-container="body" data-placement="top" data-trigger="hover" data-content="Add a page which lists all incorporated COs. This will be a supplemental sheet after 703 listing." type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 18px;" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 ">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                        </svg>
                                    </span>
                                </span>
            
                                <div class="pull-right ml-1">
                                    <input type="checkbox" @isset($plugins['list_all_change_orders']) checked @endif id="islistallchangeorder" name="list_all_change_orders" value="1" data-size="small" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
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
                                    <input type="checkbox" onchange="disablePlugin(this.checked, 'plgincludestorematerials')" @isset($plugins['has_schedule_value']) checked @endif id="plgscheduleavailable" name="has_schedule_value" value="1" class="make-switch" data-size="small" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                </div>
                            </div>
                        </div>
                    </div>
            
                </div>
                
            </form>
        </div>
    </div>
    @if($plugin_settings=='contract')
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a class="btn green" href="{{ route('contract.create.new', ['step' => 2, 'contract_id' => $contract_id]) }}">Back</a>
                            <button class="btn blue" type="submit" fdprocessedid="tinkj">Preview contract</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($plugin_settings=='settings')
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a class="btn green" href="/merchant/profile/settings">Cancel</a>
                            <input class="btn blue" type="submit" value="Save">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</form>

