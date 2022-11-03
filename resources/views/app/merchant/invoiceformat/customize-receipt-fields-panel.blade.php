<style>
    .panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left:15%;
        /* width: 80em; */
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 11;
    }
    .panelCustomize {
        background-color: #F7F8F8 !important;
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
    .draggable {
        border-top: 1px solid #eee;
        /* Indicate the element draggable */
        padding: 7px 7px 7px;
    }
    .drag_div_wid {
        cursor: move;
        user-select: none;
        padding-top:3px;
        padding-left:0px;
    }
    .draggable .drag-list-icon{
        display:none;
    }
    .draggable:hover .drag-list-icon{
        display: block;
    }
    .draggable:hover {
        background-color: #F7F8F8;
    }
    .remove-btn {
        float: right;
        color: #999;
        cursor: pointer;
        font-size: 1.5em !important;
    }
    .over {
        background-clip: content-box;
        border: 2px dotted #859494;
    }
</style>
<script>
    function hideCustomizePanel() {
        document.getElementById("new_tnc").style.display = "contents";
        document.getElementById("panelWrapId").style.boxShadow = "none";
        document.getElementById("panelWrapId").style.transform = "translateX(100%)";
        $('.page-sidebar-wrapper').css('pointer-events','auto');
    }
    
    function allowDrop(ev) { 
        ev.preventDefault(); // cancel the ev event 
    }
    
    // function called when the drag operation starts 
    function dragStart(ev) { 
        // sets the data type and the value of the dragged data 
        // This data will be returned by getData(). Here the ID of current dragged element 
        //document.getElementById(ev.target.id).style.opacity = '0.4';
        ev.dataTransfer.setData('Text', ev.target.id); 
    } 
    
    // function called when the dragged element is dropped 
    function drop(ev) { 
        ev.preventDefault(); // the dragover event needs to be canceled to allow firing the drop event 
        var evtarget = document.getElementById(ev.target.id);
        if(evtarget != null) {
            evtarget.classList.remove("over");
        }
        // gets data set by setData() for current drag-and-drop operation (the ID of current dragged element), 
        // using for parameter a string with the same format set in setData 
        var drag_data = ev.dataTransfer.getData('Text'); 
        // adds /appends the dropped element in the specified target 
        //ev.target.appendChild(document.getElementById(drag_data)); 
        //document.getElementById("target_drop2").appendChild(document.getElementById(drag_data));
        ev.target.id = ev.target.id.replace('lbl-','');
        ev.target.id = ev.target.id.replace('old-value-','');
        document.getElementById(ev.target.id).after(document.getElementById(drag_data));
        
        //check which item is dragged        
        var parentNode = document.getElementById(drag_data).getAttribute("parentNode");
        var isdefault = document.getElementById(drag_data).getAttribute("isDefault");
        var ismandatory = document.getElementById(drag_data).getAttribute("ismandatory");

        if(parentNode != null && typeof(parentNode) != 'undefined') {
            //create remove button for available fields only
            var element = null;
            if(ismandatory==0) {
                var btn = document.createElement("i");
                btn.id = 'btn-'+drag_data;
                btn.addEventListener("click", function(){ revertBack(drag_data,btn,parentNode); });
                btn.className = 'remove-btn fa fa-minus-circle';
                var element = document.getElementById(btn.id);
            }

            var input_id = document.getElementById('txt'+drag_data);
            var input_val = document.getElementById('lbl-'+drag_data).innerText;

            if(typeof(input_id) != 'undefined' && input_id != null){
            } else {
                document.getElementById(drag_data).innerHTML +='<input id="txt'+drag_data+'" type="hidden" name="label[]" value="'+input_val+'">';
            }

            //If it isn't "undefined" and it isn't "null", then it exists.
            if(typeof(element) != 'undefined' && element != null){
                if(isdefault==0 || isdefault==1) {
                    document.getElementById('btn-'+drag_data).style.display = 'block';
                }
            } else{
                if(ismandatory==0) {
                    document.getElementById(drag_data).appendChild(btn);
                }
                if(isdefault==0) {
                    document.getElementById('old-value-'+drag_data).innerHTML = 'XXXXXX';
                } else if(isdefault==1) {
                    document.getElementById('btn-'+drag_data).style.display = 'block';
                }
            }
            document.getElementById('old-value-'+drag_data).style.display = 'block';
        }
    }

    function revertBack(revert_div_id,btn,parentNode) {
        document.getElementById(revert_div_id).removeChild(btn);
        var input_elem = document.getElementById("txt" + revert_div_id);
        document.getElementById(revert_div_id).removeChild(input_elem);
        //document.getElementById('old-value-'+revert_div_id).innerHTML = '';
        document.getElementById('old-value-'+revert_div_id).style.display = 'none';
        document.getElementById(parentNode+"_details").appendChild(document.getElementById(revert_div_id));
    }

    function removeDefaultReceiptFields(id,parentNode) {
        document.getElementById('btn-'+id).style.display = 'none';
        document.getElementById('old-value-'+id).style.display = 'none';
        var input_elem = document.getElementById("txt" + id);
        document.getElementById(id).removeChild(input_elem);
        document.getElementById(parentNode+"_details").appendChild(document.getElementById(id));
        //document.getElementById('target_drop2').removeChild(document.getElementById(id));
    }

    function dragEnter(ev) {
        ev.preventDefault();
        drop_row_id = ev.target.id.replace('lbl-','');
        var element = document.getElementById(drop_row_id);
        if(element != null) {
            element.classList.add('over');
        }
        //ev.target.classList.add('hovered');
    }

    function dragLeave(ev) {
        var element = document.getElementById(ev.target.id);
        if(element != null) {
            element.classList.remove("over");
        }
    }
</script>

<div class="panel-wrap" id="panelWrapId">
    <div class="panel panelCustomize">
        <header class="cd-panel__header">
            <h3 class="page-title">Configure payment receipt fields 
                {{-- <a href="javascript:;" class="btn btn-sm red" id="close"> <i class="fa fa-times"> </i></a> --}}
                <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return hideCustomizePanel();">
                    <i class="fa fa-times"> </i>
                </a>
            </h3>
            <hr>
        </header>
        <div id="plugin_value_error" class="alert alert-danger" style="display: none;">
        </div>
        <div class="portlet light bordered">
        <div class="portlet-body form">
            <form method="post" class="form-horizontal form-row-sepe" enctype="multipart/form-data" id="set_custom_payment_receipt_fields">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <div class="panel-header">
                                    <h4 class="form-section mt-0">Receipt preview 
                                        <a href="#resetDeaultfieldsModal" data-toggle="modal" class="btn btn-xs green pull-right">Reset to default fields</a>  
                                    </h4>
                                </div>
                                <div class="portlet light bordered">
                                    <div class="portlet-body form">
                                        <div>
                                            <div class="row invoice-logo">
                                                <div class="col-xs-6 invoice-logo-space">
                                                    <img src="@if($logo!=''){{$logo}}@else/assets/admin/layout/img/logo.png @endif" class="img-responsive templatelogo" style="max-height: 80px !important;" alt=""/>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p class="font-blue-madison td-r">
                                                        {{-- @if($response.main_company_name!='')
                                                            <span class="muted" style="font-size: 12px;"> (An official franchisee of {$response.main_company_name})</span>
                                                        @endif --}}
                                                    <h5 class="pull-right no-margin">Transaction Receipt</h3> 
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                
                                        <h3 class="font-blue-madison">Thank you</h3>
                                        <p>
                                            Your Payment is successful. Please quote your receipt number for any queries relating to this transaction in future. Please note that this receipt is valid subject to the realisation of your payment.
                                        </p>
                                        <div class="portlet ">
                                            <div class="portlet-body">
                                                <div class="row droptarget" id='target_drop2' ondrop='drop(event)' ondragenter="dragEnter(event)" ondragover='allowDrop(event)' ondragleave="dragLeave(event)">
                                                    <div class="col-md-12">
                                                        @if($mode=='update' && !empty($detail->custmized_receipt_fields))
                                                            @foreach($detail->custmized_receipt_fields as $k=>$field)
                                                                <div class='col-md-12 draggable' draggable='true' ondragstart='dragStart(event)' id="exist-receipt-field-{{$k}}" parentNode="{{$field['parentNode']}}" isDefault="{{$field['isDefault']}}" isMandatory="{{$field['is_mandatory']}}">
                                                                    {{-- <hr class="m-5"> --}}
                                                                    <div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div>
                                                                    <div class="col-md-6" id="lbl-exist-receipt-field-{{$k}}">{{$field['label']}}</div>
                                                                    <div class="col-md-4 text-gray-500" id="old-value-exist-receipt-field-{{$k}}">{!!$field['default_value']!!}</div>
                                                                    <input id="txtexist-receipt-field-{{$k}}" type="hidden" name="label[]" value="{{$field['label']}}" />
                                                                    @if($field['is_mandatory']==0)
                                                                        <i id="btn-exist-receipt-field-{{$k}}" class="remove-btn fa fa-minus-circle" onclick="removeDefaultReceiptFields('exist-receipt-field-{{$k}}','{{$field['parentNode']}}');"></i>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            @if(!empty($defaultReceiptFields))
                                                                @php $i=1; @endphp
                                                                @foreach($defaultReceiptFields as $k=>$field)
                                                                    <div class='col-md-12 draggable' draggable='true' ondragstart='dragStart(event)' id="exist-receipt-field-{{$i}}" parentNode="{{$field['parentNode']}}" isDefault="1" isMandatory="{{$field['is_mandatory']}}">
                                                                        {{-- <hr class="m-5"> --}}
                                                                        <div class="col-md-1 drag_div_wid"><div class="drag-list-icon"></div></div>
                                                                        <div class="col-md-6" id="lbl-exist-receipt-field-{{$i}}">{{$field['label']}}</div>
                                                                        <div class="col-md-4 text-gray-500" id="old-value-exist-receipt-field-{{$i}}">{!!$field['default_value']!!}</div>
                                                                        <input id="txtexist-receipt-field-{{$i}}" type="hidden" name="label[]" value="{{$field['label']}}" />
                                                                        @if($field['is_mandatory']==0)
                                                                            <i id="btn-exist-receipt-field-{{$i}}" class="remove-btn fa fa-minus-circle" onclick="removeDefaultReceiptFields('exist-receipt-field-{{$i}}','{{$field['parentNode']}}');"></i>
                                                                        @endif
                                                                    </div>
                                                                    @php $i++; @endphp
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <p>&copy; {{ date('Y') }} OPUS Net Pvt. Handmade in Pune. <img src="/assets/admin/layout/img/logo.png" class="img-responsive pull-right powerbyimg" alt=""/><span class="powerbytxt">powered by</span></p>
                                        <hr/>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4" id="available_fields">
                                <div class="panel-header">
                                    <h4 class="form-section mt-0">Available fields</h4>
                                </div>
                                <div class="portlet light bordered">
                                    <div class="portlet-body">
                                        <h5 class="form-section-h5">Customer Details</h4>
                                        <div class="row">
                                            <div id="customer_details" class="col-md-12">
                                            </div>
                                        </div>
                                        
                                        <h5 class="form-section-h5">Billing Details</h4>
                                        <div class="row">
                                            <div class="col-md-12" id="billing_details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="javascript:;" class="btn default" onclick="return hideCustomizePanel();">Close</a>
                                <input type="submit" value="Save" class="btn blue" onclick="return setCusomizedReceiptFieldsValue();"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

<!-- confirm modal for reset default fields -->
<div class="modal fade" id="resetDeaultfieldsModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Reset to default fields</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to reset to default fields?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal" id="resetFieldsClose">Close</button>
                <a class="btn blue" onclick="resetPreviewFields()">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>