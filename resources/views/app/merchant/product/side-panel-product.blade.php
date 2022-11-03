<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left:25%;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 11;
    }
    .panel-wrap .panel {
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
    .remove {
        float:right;
        display:inline-block;
        padding:2px 5px;
        /* background:#ccc; */
        cursor:pointer;
    }
    @media (max-width: 767px) {
        .panel-wrap {
            /* width: 23em; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position:fixed;
        }
    }
    @media (min-width: 768px) and (max-width: 991px) {
        .panel-wrap {
            /* width: 47em; */
            position:fixed;
            right:0;
        }
    }
    @media (min-width: 992px) and (max-width: 1199px) {
        .panel-wrap {
            /* width: 47em; */
            position:fixed;
            right:0;
        }
    }
</style>
<div class="panel-wrap" id="panelWrapId">
    <div class="panel">
        <header class="cd-panel__header">
            <h3 class="page-title">Create Product/Service 
                {{-- <a href="javascript:;" class="btn btn-sm red" id="close"> <i class="fa fa-times"> </i></a> --}}
                <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return closeProductPanel();">
                    <i class="fa fa-times"> </i>
                </a>
            </h3>
            <hr>
        </header>
        <div id="product_error" class="alert alert-danger" style="display: none;">
        </div>
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <form action="/merchant/product/store" method="post" class="form-horizontal form-row-sepe" enctype="multipart/form-data" id="product_create">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @include('app.merchant.product.add-product-layout')
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <input type="hidden" name="redirect" @if(isset($redirect) && !empty($redirect)) value="{{$redirect}}" @endif id="ajax"/>
                                    <a href="" class="btn default" onclick="return closeProductPanel();">Close</a>
                                    <input type="submit" value="Save" class="btn blue" onclick="return saveProduct();"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- product-category-modal for adding new product category -->
@include('app.merchant.product-category.create-product-category-modal')

<!-- unit-type-modal for adding new unit type -->
@include('app.merchant.unit-type.create-unit-modal')

<!-- confirm modal for inventory service enable -->
<div class="modal fade" id="confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to enable this service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <button id="enableServiceOk" class="btn blue">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Service Activation message -->
<div class="modal fade" id="serviceActivated" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                <p id="serviceActivatedMsg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" data-cy="close_service_activated_modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="search_hsn_sac_code" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="find_hsn_sac_code">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Find <span id="modal_sac_code_lbl">HSN</span> code</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @livewire('lookup.hsn-sac-code-search')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_hsn_sac_lookup_modal" class="btn default" data-dismiss="modal" data-cy="close_hsn_sac_lookup_modal">Cancel</button>
                    {{-- <button id="saveCode" class="btn blue" data-cy="save_sac_modal" onclick="return saveCode()">Save</button> --}}
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
                <p>Are you sure want to delete variation row?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                {{-- <button type="button" wire:click.prevent="remove()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button> --}}
                <button type="button" onclick="deleteVariationRow()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://releases.transloadit.com/uppy/v1.28.1/uppy.min.js"></script>
<script>
//uppy file upload code
var uppy = Uppy.Core({ 
    autoProceed: true,
    restrictions: {
        maxFileSize: 1000000,
        maxNumberOfFiles: 1,
        minNumberOfFiles: 1,
        allowedFileTypes: ['.jpg','.png','.jpeg']
    }
});

uppy.use(Uppy.Dashboard, {
    target: '#drag-drop-area', 
    inline: true,
    height: 200,
    maxHeight: 200,
    width: 280,
    maxWidth: 280,
    hideAfterFinish: true,
    showProgressDetails: false,
    hideUploadButton: false,
    hideRetryButton: false,
    hidePauseResumeButton: false,
    hideCancelButton: false,
    doneButtonHandler: () => {
        document.getElementById("uppy_file").value = '';
        this.uppy.reset()
        this.requestCloseModal()
    },
    locale: {
        strings: {
            done: 'Cancel'
    }}
});
    
uppy.use(Uppy.XHRUpload, { 
    endpoint: '/merchant/uppyfileupload/uploadImage',
    method:'post',
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
    document.getElementById("uppy_file").value = response.body.fileUploadPath;
    if(response.body.status == 300) {
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