<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4">Select vendor <span class="required">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control select2Contract" data-placeholder="Select vendor" required name="vendor_id" id="vendor_id">
                            <option value="">Select vendor</option>
                            @foreach($vendor_list as $vendor)
                                @if(($sub_contract && $sub_contract->vendor_id == $vendor->vendor_id) || (old('vendor_id') == $vendor->vendor_id))
                                    <option value="{{$vendor->vendor_id}}" selected>{{$vendor->vendor_name}} | {{$vendor->vendor_id}}</option>
                                @else
                                    <option value="{{$vendor->vendor_id}}">{{$vendor->vendor_name}} | {{$vendor->vendor_id}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4">Select project <span class="required">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control select2Contract" data-placeholder="Select project" required name="project_id" id="project_id">
                            <option value="">Select project</option>
                            @foreach($project_list as $project)
                                @if(($sub_contract && $sub_contract->project_id == $project->id) || (old('project_id') == $project->id))
                                    <option value="{{$project->id}}" selected>{{$project->project_name}} | {{$project->project_id}}</option>
                                @else
                                    <option value="{{$project->id}}">{{$project->project_name}} | {{$project->project_id}}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Start date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $start_date = $sub_contract->start_date ?? old('start_date')  @endphp
                        <input id="start_date" class="form-control form-control-inline date-picker"
                               type="text" required data-cy="start_date" name="start_date" data-date-format="{{ Session::get('default_date_format')}}"
                               autocomplete="off" placeholder="Start date" @if($start_date != null) value='<x-localize :date="$start_date" type="date" />' @endif
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                        <div class="text-danger" id="start_date_error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">End date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $end_date = $sub_contract->end_date ?? old('end_date')  @endphp
                        <input id="end_date" class="form-control form-control-inline date-picker"
                               type="text" required data-cy="end_date" name="end_date" data-date-format="{{ Session::get('default_date_format')}}"
                               autocomplete="off" placeholder="End date" @if($end_date != null) value='<x-localize :date="$end_date" type="date" />' @endif
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Sub Contract Code<span class="required">*</span></label>
                    <div class="col-md-8">
                        <input type="text" name="sub_contract_code" maxlength="45" required id="subcontract_code" class="form-control" value="{{ $sub_contract->sub_contract_code ?? old('sub_contract_code') }}"  >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Default Retainage</label>
                    <div class="col-md-8">
                        <input type="number" name="default_retainage" id="default_retainage" class="form-control" value="{{ $sub_contract->default_retainage ?? old('default_retainage') }}"  >
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4">Title</label>
                    <div class="col-md-8">
                        <input type="text" name="title" maxlength="45" id="title" class="form-control" value="{{ $sub_contract->title ?? old('title') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Sign</label>
                    <div class="col-md-8">
                        <input type="text" name="sign" maxlength="100" id="sign" class="form-control" value="{{ $sub_contract->sign ?? old('sign') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Description</label>
                    <div class="col-md-8">
                        <textarea class="form-control" maxlength="100" name="description" id="description" rows="3" placeholder="Description" >{{ $sub_contract->description ?? old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Attachments</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            @if(!empty($sub_contract->attachments))
                                @php
                                    $attachments = explode(',', $sub_contract->attachments);
                                @endphp
                                <span class="help-block">
                                    <a onclick="document.getElementById('newImageDiv').style.display = 'block';" class="UppyModalOpenerBtn btn default">Add attachments</a>
                                    <div id="docviewbox" class="mt-1">
                                        @foreach ($attachments as $key => $item)
                                            <span class=" btn btn-sm green" style="margin-bottom: 5px;margin-left: 0px !important">
                                                <a class="btn-sm " target="_BLANK" href="{{$item}}" title="Click to view full size">{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}..</a>
                                                <a href="#delete_doc" onclick="setdata('{{substr(substr(substr(basename($item), 0, strrpos(basename($item), '.')),0,-4),0,10)}}','{{$item}}');" data-toggle="modal"> <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover" data-content="Remove doc"></i> </a>
                                            </span>
                                        @endforeach
                                    </div>
                                </span>
                                <span id="newImageDiv" style="display: none;">
                                    <input type="hidden" name="file_upload" id="file_upload" value="{{$sub_contract->attachments}}">
                                    <div id="drag-drop-area2"></div>
                                </span>
                            @else
                                <input type="hidden" name="file_upload" id="file_upload" value="">
                                <a class="UppyModalOpenerBtn btn default">Add attachments</a>
                                <div id="docviewbox" class="mt-1">
                                </div>
                                <div id="drag-drop-area2"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="/merchant/sub-contracts" class="btn green">Cancel</a>
                    <button type="submit" class="btn blue" onclick="return validateDates()">Add Particulars</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://releases.transloadit.com/uppy/v3.3.0/uppy.min.js"></script>
<script>
    $(function() {
        $('.select2Contract').select2();

        function validateDates() {
            if(Date.parse($('#end_date').val()) > Date.parse($('#start_date').val())) {
                $('#start_date_error').html('Start date should be greater than or equal to End date')
                return false
            }

            return true;
        }
    })
</script>
<script>
    var envlimit = 5;

    var newdocfileslist = [];
    const { Compressor } = Uppy;
    @if(isset($sub_contract->attachments) && !empty($sub_contract->attachments[0]))
    @php
        $attachments = explode(',', $sub_contract->attachments);
    @endphp
    @foreach ($attachments as $key=>$item)
    newdocfileslist.push('{{$item}}');
    @endforeach
    @endif
    //uppy file upload code
    var uppy = new Uppy.Uppy({
        autoProceed: true,
        restrictions: {
            maxFileSize: 3000000,
            maxNumberOfFiles: envlimit,
            minNumberOfFiles: 1,
            allowedFileTypes: ['.jpg', '.png', '.jpeg', '.pdf']
        },
        onBeforeFileAdded: (currentFile, files) => {
            var remainleng = 0;
            if(document.getElementById("file_upload").value!='')
                remainleng = document.getElementById("file_upload").value.split(",").length;

            var counts = envlimit-remainleng;
            if(remainleng==envlimit) {
                // uppy.info('you can upload only '+envlimit+ ' files', 'error', 10000)
                // document.getElementById("up-error").innerHTML = "*Maximum "+envlimit+" files allowed";
                return Promise.reject('too few files')
            } else if (Object.keys(files).length > counts-1) {
                return Promise.reject('too few files')
            } else {
                return true;
            }
        }
    });
    uppy.use( Compressor, {
        quality: 0.6,
        limit: envlimit,
    });
    uppy.use(Uppy.Dashboard, {
        target: 'body',
        trigger: '.UppyModalOpenerBtn',
        inline: false,


        hideAfterFinish: true,
        showProgressDetails: false,
        hideUploadButton: false,
        hideRetryButton: false,
        hidePauseResumeButton: false,
        hideCancelButton: false,
    });
    window.uppy.getPlugin('Dashboard').setOptions({
        note: 'Max upload limit '+envlimit+' files(image & pdf) only',
    });
    uppy.use(Uppy.XHRUpload, {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        endpoint: '/merchant/uppyfileupload/uploadImage/sub-contract',
        method: 'post',
        formData: true,
        fieldName: 'image'
    });
    uppy.on('file-added', (file) => {
        document.getElementById("error").innerHTML = '';
        console.log('file-added');
    });
    uppy.on('upload', (data) => {
        console.log('Starting upload');
    });
    uppy.on('upload-success', (file, response) => {
        if (response.body.fileUploadPath != undefined) {
            path = response.body.fileUploadPath;
            extvalue = document.getElementById("file_upload").value;
            newdocfileslist.push(path);
            deletedocfile('');
            if (extvalue != '') {
                document.getElementById("file_upload").value = extvalue + ',' + path;
            } else {
                document.getElementById("file_upload").value = path;
            }
        }
        if (response.body.status == 300) {
            try {
                document.getElementById("error").innerHTML = response.body.errors;
            } catch (o) {}
            uppy.removeFile(file.id);
        } else {
            try {
                document.getElementById("error").innerHTML = '';
            } catch (o) {}
        }
    });
    uppy.on('complete', (result) => {
        //console.log('successful files:', result.successful)
        //console.log('failed files:', result.failed)
    });
    uppy.on('error', (error) => {
        //console.error(error.stack);
    });
</script>
<div class="modal fade" id="delete_doc" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 id="poptitle" class="modal-title">Delete attachment</h4>
                <input type="hidden" id="docfullurl">
            </div>
            <div class="modal-body">
                Do you want to permanently delete this attachment from this subcontract?
            </div>
            <div class="modal-footer">
                <button type="button" id="closeconformdoc" class="btn default" data-dismiss="modal">Cancel </button>
                <button type="button" onclick="deletedocfile('delete')" id="deleteanchor" class="btn delete">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="delete_doc2" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 id="poptitle" class="modal-title">Delete attachment</h4>
                <input type="hidden" id="file_url">
                <input type="hidden" id="array_name">
                <input type="hidden" id="array_key">
            </div>
            <div class="modal-body">
                Do you want to permanently delete this attachment from this subcontract?
            </div>
            <div class="modal-footer">
                <button type="button" id="closeconformdoc2" class="btn default" data-dismiss="modal">Cancel </button>
                <button type="button" onclick="deletedocfileMandatory('delete', document.getElementById('array_key').value, document.getElementById('array_name').value)" id="deleteanchor" class="btn delete">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function setdata(name, fullurl) {
        document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
        document.getElementById('docfullurl').value = fullurl;
    }
    function setdataMandatory(name, fullurl, array_name, ArrayKey) {
        document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
        document.getElementById('file_url').value = fullurl;
        document.getElementById('array_name').value = array_name;
        document.getElementById('array_key').value = ArrayKey;
    }
    function deletedocfile(x) {
        var html = '';
        if (x == 'delete') {
            var fullurl = document.getElementById('docfullurl').value;
            var index = newdocfileslist.indexOf(fullurl);
            if (index !== -1) {
                newdocfileslist.splice(index, 1);
            }
        }
        for (var i = 0; i < newdocfileslist.length; i++) {
            var filenm = newdocfileslist[i].substring(newdocfileslist[i].lastIndexOf('/') + 1);
            filenm = filenm.split('.').slice(0, -1).join('.')
            filenm = filenm.substring(0, filenm.length - 4);
            html = html + '<span class=" btn btn-sm green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
                '<a class="btn btn-sm " target="_BLANK" href="' + newdocfileslist[i] + '" title="Click to view full size">' + filenm.substring(0, 10) + '..</a>' +
                '<a href="#delete_doc" onclick="setdata(\'' + filenm.substring(0, 10) + '\',\'' + newdocfileslist[i] + '\');"   data-toggle="modal"> ' +
                ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';
        }
        clearnewuploads('no');
        document.getElementById('docviewbox').innerHTML = html;
        document.getElementById('closeconformdoc').click();
    }
    function deletedocfileMandatory(x, ArrayKey, arrayName) {
        var html = '';
        if (x == 'delete') {
            var array_key = document.getElementById('array_key').value;
            var file_url = document.getElementById('file_url').value;
            var full_url = document.getElementById('array_name').value;
            // arrayName = document.getElementById('array_name').value;
            // document.getElementById('file_upload_mandatory'+array_key).value=full_url;
            var arrayName = full_url.split(",");
            console.log('hi'+arrayName);

            var index = arrayName.indexOf(file_url);
            if (index !== -1) {
                delete arrayName[index];
            }
            document.getElementById('file_upload_mandatory'+array_key).value = arrayName.join(',');
            // const element = document.getElementById("docviewbox"+array_key);
            //element.remove();
        }
        arrayName= arrayName.filter(Boolean);
        console.log(arrayName);
        for (var i = 0; i < arrayName.length; i++) {
            var filenm = arrayName[i].substring(arrayName[i].lastIndexOf('/') + 1);
            filenm = filenm.split('.').slice(0, -1).join('.')
            filenm = filenm.substring(0, filenm.length - 4);
            html = html + '<br><span class="btn btn-sm green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
                '<a class=" btn btn-sm" target="_BLANK" href="' + arrayName[i] + '" title="Click to view full size">' + filenm.substring(0, 40) + '..</a>' +
                '<a href="#delete_doc2" onclick="setdataMandatory(\'' + filenm.substring(0, 40) + '\',\'' + arrayName[i] + '\',\'' + arrayName + '\',\'' + ArrayKey + '\');"   data-toggle="modal"> ' +
                ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';
        }
        clearnewuploads_mandatory('no',ArrayKey,  arrayName);
        document.getElementById('docviewbox'+ArrayKey).innerHTML = html;
        document.getElementById('closeconformdoc2').click();
    }
    function deletedocfileMandatoryold(x, ArrayKey, arrayName) {
        var html = '';
        if (x == 'delete') {
            var array_key = document.getElementById('array_key').value;
            var file_url = document.getElementById('file_upload_mandatory'+array_key).value;
            var arrayName = file_url.split(",");
            var index = arrayName.indexOf(file_url);
            if (index !== -1) {
                delete arrayName[index];
            }
            document.getElementById('file_upload_mandatory'+array_key).value = arrayName.join();
            const element = document.getElementById("docviewbox"+array_key);
            element.remove();
        }
        clearnewuploads_mandatory('no',ArrayKey,  arrayName);
        document.getElementById('docviewbox'+ArrayKey).innerHTML = html;
        document.getElementById('closeconformdoc').click();
    }
    function clearnewuploads(x) {
        document.getElementById("file_upload").value = '';
        var filesnm = '';
        for (var i = 0; i < newdocfileslist.length; i++) {
            if (filesnm != '')
                filesnm = filesnm + ',' + newdocfileslist[i];
            else
                filesnm = filesnm + newdocfileslist[i];
        }
        document.getElementById("file_upload").value = filesnm;
    }
    function clearnewuploads_mandatory(x,ArrayKey,  arrayName) {
        document.getElementById("file_upload_mandatory"+ArrayKey).value = '';
        var filesnm = '';
        for (var i = 0; i < arrayName.length; i++) {
            if (filesnm != '')
                filesnm = filesnm + ',' + arrayName[i];
            else
                filesnm = filesnm + arrayName[i];
        }
        document.getElementById("file_upload_mandatory"+ArrayKey).value = filesnm;
    }
</script>