<div>
    <div>

        <div class="row" id="">
            <div class="col-md-6">
                <div class="row no-margin mb-1" id="">
                    <a onclick="addBookingDetail('L');" data-toggle="modal" href="#bds" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a>
                </div>
                <ul class="sortable">
                    @if(!empty($invoiceColumns))
                    @foreach($invoiceColumns as $key=>$v)
                    @if($v['position']=="L")
                    @if($v['column_datatype']=="textarea")
                    @php $text='<textarea class="form-control input-sm" readonly=""></textarea>' @endphp
                    @else
                    @php $text='<input type="text" class="form-control input-sm" value="" readonly>' @endphp
                    @endif
                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                        <div class="form-group" style="display: initial;">
                            <input name="column_config[]" type="hidden" value="{{$v['config']}}">
                            <input name="column_id[]" type="hidden" value="{{$v['column_id'] ?? '0'}}">
                            @if($v['is_delete_allow']==1 || $v['column_datatype']=='date')
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                                    </span>
                                    <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">

                                    <span class="input-group-btn">
                                        <div class="btn default btn-sm"> <i class="fa {{$v['icon']}}"></i></div>
                                        <a class="btn default btn-sm" onclick="setColumn('{{$v["column_datatype"]}}','{{$v["column_name"]}}',{{$key}})" data-toggle="modal" href="#bds">
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
                                    <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" readonly class="form-control input-sm" maxlength="40" placeholder="Enter label name">
                                    <span class="input-group-btn">
                                        <div class="btn default btn-sm"> <i class="fa {{$v['icon'] ?? 'fa-font'}}"></i></div>
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
                                        <i class="fa fa-minus-circle" wire:click="remove({{$key}})"></i>
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
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-6" id="">
                <div class="row no-margin mb-1" id="">
                    <a onclick="addBookingDetail('R');" data-toggle="modal" href="#bds" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add custom field </a>
                </div>
                <ul class="sortable">
                    @if(!empty($invoiceColumns))
                    @foreach($invoiceColumns as $key=>$v)
                    @if($v['position']=="R")
                    @if($v['column_datatype']=="textarea")
                    @php $text='<textarea class="form-control input-sm" readonly=""></textarea>' @endphp
                    @else
                    @php $text='<input type="text" class="form-control input-sm" value="" readonly>' @endphp
                    @endif
                    <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                        <div class="form-group" style="display: initial;">
                            <input name="column_config[]" type="hidden" value="{{$v['config']}}">
                            <input name="column_id[]" type="hidden" value="{{$v['column_id'] ?? '0'}}">
                            @if($v['is_delete_allow']==1 || $v['column_datatype']=='date')
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                                    </span>
                                    <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" class="form-control input-sm" maxlength="40" placeholder="Enter label name">

                                    <span class="input-group-btn">
                                        <div class="btn default btn-sm"> <i class="fa {{$v['icon']}}"></i></div>
                                        <a class="btn default btn-sm" onclick="setColumn('{{$v["column_datatype"]}}','{{$v["column_name"]}}',{{$key}})" data-toggle="modal" href="#bds">
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
                                    <input type="text" name="headercolumn[]" {{$v['readonly']}} required value="{{$v['column_name']}}" readonly class="form-control input-sm" maxlength="40" placeholder="Enter label name">
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
                                        <i class="fa fa-minus-circle" wire:click="remove({{$key}})"></i>
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
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>



        <div class="modal fade " aria-hidden="true" id="bds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Field property</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-5 control-label">Datatype</label>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm" id="bdatatype" @if($systemCol==1) readonly @endif data-placeholder="Select type">
                                                @if($systemCol==1)
                                                <option value="{{$datatype}}">{{ ucfirst($datatype) }}</option>
                                                @else
                                                <option @if(@datatype=='text' ) selected @endif value="text">Text</option>
                                                <option @if(@datatype=='textarea' ) selected @endif value="textarea">Text area</option>
                                                <option @if(@datatype=='number' ) selected @endif value="number">Number</option>
                                                <option @if(@datatype=='money' ) selected @endif value="money">Money</option>
                                                <option @if(@datatype=='percent' ) selected @endif value="percent">Percentage</option>
                                                <option @if(@datatype=='date' ) selected @endif value="date">Date</option>
                                                <option @if(@datatype=='time' ) selected @endif value="time">Time</option>
                                                @endif
                                            </select>
                                            <span class="help-block">
                                            </span>
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group" id="bank_transaction_no">
                                        <label class="col-md-5 control-label">Column name</label>
                                        <div class="col-md-6">
                                            <input class="form-control form-control-inline input-sm" id="bcolumnname" @if($systemCol==1) readonly @endif type="text" value="" placeholder="Column name" />
                                            <input id="readonly" type="hidden" value="" />
                                            <input id="bkey" type="hidden" value="" />
                                            <input id="bposition" type="hidden" value="" />
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="saveColumn(bookingValues());" data-dismiss="modal" class="btn blue">Save</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>
<script>
    function bookingValues() {
        datatype = $('#bdatatype').val();
        column = $('#bcolumnname').val();
        key = $('#bkey').val();
        bposition = $('#bposition').val();
        return '{"datatype":"' + datatype + '","column":"' + column + '","key":"' + key + '","position":"' + bposition + '"}';
    }

    function setColumn(datatype, name, key) {
        $('#bdatatype').val(datatype);
        $('#bcolumnname').val(name);
        $('#bkey').val(key);
    }

    function addBookingDetail(position) {
        $('#bkey').val('');
        $('#bdatatype').val('text');
        $('#bcolumnname').val('');
        $('#bposition').val(position);
    }
</script>