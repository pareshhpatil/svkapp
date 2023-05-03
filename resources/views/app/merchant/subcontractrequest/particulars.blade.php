@extends('app.master')

@section('header')
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">
<style>
    .uppy-Dashboard-AddFiles-title {
        font-size: 15px !important;
        font-weight: 400 !important;
    }

    .uppy-Dashboard-inner {

        border: 0px solid #eaeaea !important;
        border-radius: 5px !important;
    }

    [data-uppy-drag-drop-supported=true] .uppy-Dashboard-AddFiles {
        margin: 0px !important;
        padding-bottom: 9px;
        height: calc(100%) !important;
        border-radius: 3px;
        border: 1px dashed #dfdfdf;
    }
</style>
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('create.invoice','Invoice') }}
    </div>


    <!-- END PAGE HEADER-->

    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            
        </div>
    </div>
    @if($template_id=='')
    <div class="row" id="preview_div" style="display: none;">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body form">
                    <h4 class="form-section">Preview format</h4>
                    <div id="preview">

                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Show create invoice form -->
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="alert alert-danger" style="display: none;" id="invoiceerrorshow">
                <p id="invoiceerror_display">Please correct the below errors to complete registration.</p>
            </div>
            <form action="/merchant/invoice/invoicesave" onsubmit="return checkCurrentContractAmount('');" id="invoice" method="post" class="form-horizontal" enctype="multipart/form-data">
                {!!Helpers::csrfToken('invoice')!!}

                <input type="hidden" id="product_taxation_type" name="product_taxation_type" value="{{$product_taxation_type}}">

                <div>
                @include('app.merchant.invoice.construction_particular')
                    <div class="row">
                        
                    @include('app.merchant.invoice.construction_particular')

                    </div>
                    @include('app.merchant.invoice.footer')
                    @endif
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                        <a class="btn blue">Preview</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>







                <!-- BEGIN SEARCH CONTENT-->
                @endsection

                <!-- add particulars label ends -->

                @section('footer')
                @if($template_id!='')
                <script>
                    mode = '{{$mode}}';
                    exist_paricular_cnt = '0';
                    @if(isset($customer_id))
                    selectCustomer({
                        {
                            $customer_id
                        }
                    });
                    @endif
                    @if(isset($product_list))
                    products = {
                        !!$product_json!!
                    };
                    @endif

                    @if(isset($csi_code))
                    csi_codes = {
                        !!$csi_code_json!!
                    };
                    @endif

                    @if(isset($tax_array))
                    tax_master = '{!!$tax_array!!}';
                    tax_array = JSON.parse(tax_master);
                    taxes_rate = '{!!$tax_rate_array!!}';
                    @endif

                    particular_values = '{{$template_info->particular_values}}';

                    particular_col_array = JSON.parse('{!!$template_info->particular_column!!}');


                    @if(isset($contract_detail))
                    document.getElementById('_project_id').value = '{!!$contract_detail->id!!}';
                    @endif


                    /*@php $default_tax = json_decode($template_info->default_tax, 1);
                    @endphp
                    @if(!empty($default_tax))
                    @foreach($default_tax as $v)
                    AddInvoiceTax('{{$v}}');
                    @endforeach
                    @endif*/

                    @if(!empty($plugin['supplier']))
                    @foreach($plugin['supplier'] as $v)
                    AddsupplierRow({
                        {
                            $v
                        }
                    });
                    @endforeach
                    @endif


                    var datetime = '{!!$setting['
                    has_datetime ']??"0"!!}';


                    @if(isset($properties['travel_section']))
                    AddSecRow(tb_col, 'tb', datetime);
                    AddSecRow(tc_col, 'tc', datetime);
                    @endif

                    @if(isset($properties['hotel_section']))
                    AddSecRow(hb_col, 'hb', datetime);
                    @endif

                    @if(isset($properties['facility_section']))
                    AddSecRow(fs_col, 'fs', datetime);
                    @endif

                    calculateConstruction();
                </script>
                @endif
                <script>
                    $('#billing_profile_id').select2({

                    }).on('select2:open', function(e) {
                        pind = $(this).index();
                        if (document.getElementById('profilelist' + pind)) {} else {
                            $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/gstprofile" id="profilelist' + pind + '" target="_BLANK" class="clicker" >Add new profile</a> </div>');
                        }
                    });
                    $('#currency').select2({

                    }).on('select2:open', function(e) {
                        pind = $(this).index();
                        if (document.getElementById('currencylist' + pind)) {} else {
                            $('.select2-results').append('<div class="wrapper" > <a href="/merchant/profile/currency" id="currencylist' + pind + '"  target="_BLANK" class="clicker" >Add new currency</a> </div>');
                        }
                    });

                    $('#template_id').select2({

                    }).on('select2:open', function(e) {
                        pind = $(this).index();
                        if (document.getElementById('templatelists' + pind)) {} else {
                            $('.select2-results').append('<div class="wrapper" > <a href="/merchant/template/newtemplate" id="templatelists' + pind + '"   class="clicker" >Add new format</a> </div>');
                        }
                    });
                    invoice_construction = true;


                    $('.productselect').select2({
                        tags: true,
                        insertTag: function(data, tag) {
                            var $found = false;
                            $.each(data, function(index, value) {
                                if ($.trim(tag.text).toUpperCase() == $.trim(value.text).toUpperCase()) {
                                    $found = true;
                                }
                            });
                            if (!$found) data.unshift(tag);
                        }
                    }).on('select2:open', function(e) {
                        pind = $(this).index();
                        var index = $(".productselect").index(this);
                        index += 1;
                        if (document.getElementById('prolist' + pind)) {} else {
                            $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + index + ',' + index + ',0);">Addh new bill code</a> </div>');
                        }
                    });
                </script>


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
                @endsection



                @include('app.merchant.contract.add-calculation-modal')
                @include('app.merchant.contract.add-bill-code-modal')