<form action="{{$post_url}}" method="post" id="plugin-form">
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