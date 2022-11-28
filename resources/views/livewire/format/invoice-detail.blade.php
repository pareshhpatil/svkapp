<div>
    <div class="col-md-6" id="">
        <h4 class="form-section mt-0">Billing Details
            <a wire:click="modalShow(1)" data-toggle="modal" href="#custom" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a>
            <a data-toggle="modal" href="#custom" id="fmodel"></a>
        </h4>
        <ul class="sortable">
            @if(!empty($invoiceColumns))
            @foreach($invoiceColumns as $key=>$v)
            @if($v['column_datatype']=="textarea")
            @php $text='<textarea class="form-control input-sm" readonly=""></textarea>' @endphp
            @else
            @php $text='<input type="text" class="form-control input-sm" value="" readonly>' @endphp
            @endif
            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <div class="form-group" style="display: initial;">
                    <input name="column_config[]" type="hidden" value="{{$v['config']}}">
                    <input name="column_id[]" type="hidden" value="{{$v['column_id']}}">
                    @if($v['is_delete_allow']==1 || $v['column_datatype']=='date')
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                            </span>
                            <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" class="form-control input-sm" maxlength="40" placeholder="Enter label name" txtType="billing">

                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa {{$v['icon']}}"></i></div>
                                <a class="btn default btn-sm" wire:click="updateColumn({{$key}})" data-toggle="modal" href="#custom">
                                    <i class="fa fa-edit"></i></a>
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                            </span>
                            <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" readonly class="form-control input-sm" maxlength="40" placeholder="Enter label name" txtType="billing">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa {{$v['icon']}}"></i></div>
                            </span>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        @if($v['is_delete_allow']==1)
                        <div class="input-group">
                            <div>
                                {!!$text!!}
                            </div>
                            <span class="input-group-addon ">
                                <i class="fa fa-minus-circle" @if($v['function_id']>0) onclick="disableFunctionPlugin(false,'plgf{{$v['function_id']}}')" @endif wire:click="remove({{$key}})"></i>
                            </span>
                        </div>
                        @else
                        {!!$text!!}
                        @endif
                        <span class="help-block">
                        </span>
                    </div>
                </div>
            </li>
            @endforeach
            @endif
        </ul>
    </div>


    <div class="modal fade @if($modal==1) in @endif" @if($modal==1) style="display: block; padding-right: 18px;" @else aria-hidden="true" @endif id="custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">{{$columnTitle}}</h4>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-body">
                                <div id="colerror" class="alert alert-danger display-hide">

                                </div>

                                @if($plugin_function==1)
                                <input type="hidden" wire:model="datatype" value="{{$datatype}}">
                                @else
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Datatype</label>
                                    <div class="col-md-6">
                                        <select class="form-control input-sm" @if($systemCol==1) readonly @endif wire:model="datatype" data-placeholder="Select type">
                                            @if($systemCol==1)
                                            <option value="{{$datatype}}">{{ ucfirst($datatype) }}</option>
                                            @else
                                            <option value="text">Text</option>
                                            <option value="textarea">Text area</option>
                                            <option value="number">Number</option>
                                            <option value="money">Money</option>
                                            <option value="percent">Percentage</option>
                                            <option value="date">Date</option>
                                            <option value="time">Time</option>
                                            @endif
                                        </select>

                                        <span class="help-block">
                                        </span>
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group" id="bank_transaction_no">
                                    <label class="col-md-5 control-label">Column name<span class="required" aria-required="true">* </span></label>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-inline input-sm" @if($systemCol==1) readonly @endif wire:model.lazy="columnName" type="text" value="" placeholder="Column name" />
                                        <input id="readonly" type="hidden" value="" />
                                        <span class="help-block">
                                        </span>
                                    </div>

                                </div>
                                @if($plugin_function==1)
                                <input type="hidden" wire:model="functionId" value="{{$functionId}}">
                                @else
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Functions</label>
                                    <div class="col-md-6">
                                        <select class="form-control input-sm" wire:model="functionId" data-placeholder="Select function">
                                            <option selected value="">Select function</option>
                                            @if(!empty($functions))
                                            @foreach($functions as $f)
                                            <option value="{{$f['function_id']}}">{{$f['function_name']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <span class="help-block">
                                        </span>
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($paramMapping))
                                <div>
                                    <div class="form-group">
                                        <label id="colname" class="col-md-5 control-label">Type <span class="required" aria-required="true">* </span></label>
                                        <div class="col-md-6">
                                            <select id="coltype" required class="form-control input-sm" wire:model="mappingParam" data-placeholder="Select">
                                                @if($template_type == 'construction')
                                                <option selected value="system_generated">System generated</option>
                                                @else
                                                <option selected value="">Select..</option>
                                                @foreach($paramMapping as $f)
                                                <option value="{{$f['id']}}">{{$f['value']}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block">
                                            </span>
                                            <span class="help-block">
                                            </span>
                                        </div>
                                        @if($template_type == 'construction')
                                        <span class="help-block">
                                            Invoice number for G702 / 703 is set as an auto generated sequence via the Project screen
                                        </span>
                                        @endif
                                    </div>

                                    @if($functionId==7)
                                    <div class="form-group">
                                        <label class="col-md-5 control-label">Days</label>
                                        <div class="col-md-6">
                                            <input type="text" name="mapping_value" wire:model.lazy="mappingValue" class="form-control">
                                            <span class="help-block">
                                            </span>
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>

                                    @elseif($mappingParam=='system_generated')

                                    @if($template_type != 'construction')
                                    <div class="form-group">
                                        <label class="col-md-5 control-label">Sequence <span class="required" aria-required="true">* </span></label>
                                        <div class="col-md-6">
                                            <select id="colmapvalue" required class="form-control input-sm" wire:model="mappingValue" data-placeholder="Select">
                                                <option value="">Select sequence</option>
                                                @if(!empty($invoiceSeq))
                                                @foreach($invoiceSeq as $f)
                                                <option value="{{$f['auto_invoice_id']}}">{{$f['prefix']}}{{$f['val']}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block"></span>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-1 no-margin no-padding" id="new_seq_number_btn">
                                            <a title="New invoice number" wire:click="newSequence(1)" class="btn btn-sm green"><i class="fa fa-plus"></i> New sequence</a>
                                        </div>
                                    </div>
                                    @if($newSeq==1)
                                    <div class="form-group">
                                        <label class="col-md-5 control-label">Add new prefix</label>
                                        <div class="col-md-3">
                                            <input type="text" placeholder="Add prefix" wire:model.lazy="prefix" maxlength="20" class="form-control input-sm">
                                            <span class="help-block">
                                            </span>
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="col-md-2 no-padding">
                                            <input type="number" value="0" placeholder="Last no." wire:model.lazy="seqNo" max="9999999" class="form-control input-sm">
                                            <span class="help-block">
                                            </span>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label">&nbsp;</label>
                                        <div class="col-md-6">
                                            <button type="button" wire:click="saveSequence" class="btn btn-sm blue">Save sequence</button>
                                            <button type="button" wire:click="newSequence(0)" class="btn default btn-sm">Cancel</button>
                                            <span class="help-block">
                                            </span>
                                            <p id="seq_error" style="color: red;"></p>
                                        </div>
                                    </div>
                                    @endif
                                    @endif
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="modalShow(0)" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" onclick="return validateColumn();" class="btn blue">Save</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>