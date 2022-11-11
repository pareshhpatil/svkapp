@extends('app.master')
@section('content')

<style>
    .onhover-border:hover {
        border: 1px solid #ddd !important;
    }

    .table thead tr th {
        font-size: 12px;
        padding: 3px;
        font-weight: 400;
        color: #333;
    }

    .table>tbody>tr>td {
        font-size: 12px !important;
        padding: 3px;
        border: 1px solid #D9DEDE;
        border-right: 0px;
        border-left: 0px;
    }

    .error-corner {
        border: 1px solid grey;
        background-image: linear-gradient(225deg, red, red 6px, transparent 6px, transparent);
    }

    ul {
        list-style-type: none !important;
    }

    li {
        list-style-type: none !important;
    }

    .select2-results__option {
        font-size: 12px !important;
    }

    .dropdown-menu li>a {
        font-size: 12px !important;
        line-height: 18px;
    }

    .lable-heading {
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 24px;
        color: #767676;
        margin-bottom: 0px;
        margin-top: 5px;
    }

    .col-id-no {
        position: sticky !important;
        left: 0;
        z-index: 2;
        border-right: 2px solid #D9DEDE !important;
        background-color: #fff;
    }

    .steps {
        background-color: transparent !important;
        width: auto !important;
    }

    .chelptext {
        color: #767676;
        font-size: 13px;
    }

    .subscription-info h3 {
        font-size: 15px !important;
    }
</style>


<div>


    <div class="page-content">
        <div x-init="initSelect2">
            <!-- BEGIN PAGE HEADER-->
            <div class="page-bar">
                <span class="page-title" style="float: left;">{{$title}}</span>
                {{ Breadcrumbs::render('create.invoice','invoice') }}
                <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step 3 of 3</span>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">

                    <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Select a project before trying to add a new row
                        </p>
                    </div>
                    <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
                        <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
                        </p>
                    </div>


                    <div class="portlet light bordered">

                        <div class="portlet-body form">
                            <div class="subscription-info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 id="pr_project_name">
                                            {{$project->project_name}}
                                        </h3>
                                        <p class="text-center chelptext">PROJECT NAME

                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 id="pr_company_name">
                                            {{$customer->company_name}}
                                        </h3>
                                        <p class="text-center chelptext">COMPANY NAME</p>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 id="pr_contract_date">
                                            <x-localize :date="$invoice->bill_date" type="date" />
                                        </h3>
                                        <p class="text-center chelptext">Bill DATE</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h3 id="pr_contract_number">
                                            {{$contract_code}}
                                        </h3>
                                        <p class="text-center chelptext">CONTRACT NUMBER</p>
                                    </div>
                                    <div class="col-md-3">
                                        <h3 id="pr_billing_frequency">
                                            <x-localize :date="$invoice->due_date" type="date" />
                                        </h3>
                                        <p class="text-center chelptext">DUE DATE</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet light bordered">
                        <h3 class="page-title">Particulars</h3>
                        <div class="portlet-body">
                            <div id="table-subscription_wrapper" class="dataTables_wrapper no-footer">


                                <div class="table-scrollable">
                                    <table class="table table-striped  table-hover dataTable no-footer" id="table-subscription" role="grid" aria-describedby="table-subscription_info">
                                        @if(!empty($particular_column))
                                        <thead>
                                            <tr>
                                                @foreach($particular_column as $k=>$v)
                                                @if($k!='description')
                                                <th class="td-c " @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                                    <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v}}" data-original-title=""> {{Helpers::stringShort($v)}}</span>
                                                </th>
                                                @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        @endif
                                        <tbody id="preview_data">
                                            @foreach($particulars as $p=>$pv)
                                            <tr>
                                                @foreach($particular_column as $k=>$v)
                                                @if($k!='description')
                                                <td class="td-c " @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                                    {{$pv[$k]}}
                                                </td>
                                                @endif
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <p>Grand Total</p>
                                    <input type="text" id="totalamount" data-cy="invoice_total" name="grand_total" value="{{number_format($invoice->grand_total)}}" readonly="" class="form-control">
                                </div>
                            </div>
                            <div class="pull-right">
                                <input type="hidden" id="request_id" name="link" value="{{$link}}"></th>

                                <form class="form-horizontal" action="/merchant/invoice/saveInvoicePreview/{{$payment_request_id}}" method="post" onsubmit="document.getElementById('loader').style.display = 'block';">
                                    <div class="col-md-4 pull-left btn-pl-0">
                                        <div class="input-icon">
                                            <label class="control-label pr-1">Notify customer </label>
                                            <input type="checkbox" data-cy="notify" id="notify_" onchange="notifyPatron('notify_');" value="1" @if($notify_patron==1) checked @endif class="make-switch" data-size="small">
                                            <input type="hidden" id="is_notify_" name="notify_patron" value="{{($notify_patron==1) ? 1 : 0}}" />
                                        </div>
                                    </div>
                                    <div class="col-md-8 pull-left btn-pl-0">
                                    </div>

                                    <input type="hidden" name="payment_request_id" value="{{$payment_request_id}}" />
                                    <input type="hidden" name="payment_request_type" value="1" />
                                    <div class="view-footer-btn-rht-align">
                                        <br>
                                        <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                        <a class="btn green" href="/merchant/invoice/particular/{{$link}}">Back</a>

                                        <input type="submit" value="Save & Send" id="subbtn" class="btn blue margin-bottom-5 view-footer-btn-rht-align" />

                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>



</div>
<script>
    mode = '{{$mode}}';
</script>
</div>

<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
<script>
    var newdocfileslist = [];
    //uppy file upload code
    var uppy = Uppy.Core({
        autoProceed: true,
        restrictions: {
            maxFileSize: 3000000,
            maxNumberOfFiles: 10,
            minNumberOfFiles: 1,
            allowedFileTypes: ['.jpg', '.png', '.jpeg', '.pdf']
        }
    });

    uppy.use(Uppy.Dashboard, {
        target: 'body',
        trigger: '.UppyModalOpenerBtn',
        inline: false,
        height: 40,
        maxHeight: 200,

        hideAfterFinish: true,
        showProgressDetails: false,
        hideUploadButton: false,
        hideRetryButton: false,
        hidePauseResumeButton: false,
        hideCancelButton: false,
        // doneButtonHandler: () => {
        //     document.getElementById("file_upload").value = '';
        //     this.uppy.reset()
        //     this.requestCloseModal()
        // },
        // locale: {
        //     strings: {
        //         done: 'Cancel'
        // }}
    });

    uppy.use(Uppy.XHRUpload, {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        endpoint: '/merchant/uppyfileupload/uploadImage/invoice',
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
        path = response.body.fileUploadPath;
        extvalue = document.getElementById("file_upload").value;
        newdocfileslist.push(path);
        deletedocfile('');
        if (extvalue != '') {
            document.getElementById("file_upload").value = extvalue + ',' + path;
        } else {
            document.getElementById("file_upload").value = path;
        }
        if (response.body.status == 300) {
            document.getElementById("error").innerHTML = response.body.errors;
            uppy.removeFile(file.id);
        } else {
            document.getElementById("error").innerHTML = '';
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
                Do you want to permanently delete this attachment from this invoice?
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
<script>
    function setdata(name, fullurl) {

        document.getElementById('poptitle').innerHTML = "Delete attachment - " + name;
        document.getElementById('docfullurl').value = fullurl;
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
            html = html + '<span class=" btn btn-xs green" style="margin-bottom: 5px;margin-left: 0px !important;margin-right: 5px !important">' +
                '<a class=" btn btn-xs " target="_BLANK" href="' + newdocfileslist[i] + '" title="Click to view full size">' + filenm.substring(0, 10) + '..</a>' +
                '<a href="#delete_doc" onclick="setdata(\'' + filenm.substring(0, 10) + '\',\'' + newdocfileslist[i] + '\');"   data-toggle="modal"> ' +
                ' <i class=" popovers fa fa-close" style="color: #A0ACAC;" data-placement="top" data-container="body" data-trigger="hover"  data-content="Remove doc"></i>   </a> </span>';


        }
        clearnewuploads('no');
        document.getElementById('docviewbox').innerHTML = html;
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
</script>

@include('app.merchant.contract.add-bill-code-modal')
@include('app.merchant.invoice.add-attachment-billcode-modal')

@endsection