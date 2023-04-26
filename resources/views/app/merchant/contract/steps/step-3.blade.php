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
                <li role="presentation"><a href="#tab3" data-toggle="tab" class="step" aria-expanded="true">Invoice format</a></li>
            </ul>
            <div class="portlet-body">
                @include('app.merchant.contract.steps.plugin-form')
            </div>

        </div>

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

        @include('app.merchant.contract.steps.plugin-modals')

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