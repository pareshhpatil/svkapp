<div>
    <div class="col-md-6">
        <h4 class="form-section mt-0">Header Details <a data-toggle="modal" href="#main_header" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Manage field </a></h4>
        <ul class="sortable">
            @if(!empty($headerColumn))
            @foreach($headerColumn as $key=>$v)
            @if($v['is_default']==1)
            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <div class="form-group" style="display: initial;">
                    <input name="main_header_id[]" type="hidden" value="{{$v['id']}}" />
                    <input type="hidden" name="main_header_datatype[]" value="{{$v['datatype']}}">
                    <input type="hidden" name="main_header_column_id[]" value="{{$v['column_id']}}">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                            </span>
                            <input type="text" name="main_header_name[]" readonly value="{{$v['column_name']}}" class="form-control input-sm" maxlength="40" placeholder="   ">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa {{$v['icon']}}"></i></div>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($v['is_mandatory']==0)
                        <div class="input-group">
                            @if($v['datatype']=="textarea")
                            <textarea class="form-control input-sm" readonly="">{{$v['value']}}</textarea>
                            @else
                            <input type="text" class="form-control input-sm" value="{{$v['value']}}" readonly>
                            @endif
                            <span class="input-group-addon " wire:click="remove({{$key}})"><i class="fa fa-minus-circle"></i>
                            </span>
                        </div>
                        @else
                        @if($v['datatype']=="textarea")
                        <textarea class="form-control input-sm" readonly="">{{$v['value']}}</textarea>
                        @else
                        <input type="text" class="form-control input-sm" value="{{$v['value']}}" readonly>
                        @endif
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
    <div class="modal fade " aria-hidden="true" id="main_header" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div x-data="" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Select display column</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="table-scrollable">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Column name
                                            </th>
                                            <th class="td-c">
                                                Display
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($headerColumn))
                                        @foreach($headerColumn as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input type="checkbox" checked style="pointer-events: none;" name="hcheck[]" value="{{$key}}" />
                                                @else
                                                <input @if($v['is_default']==1) checked @endif type="checkbox" name="hcheck[]" value="{{$key}}" />
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a wire:click="submit(headerValues());" data-dismiss="modal" class="btn blue">Done</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>

<script>
    function headerValues() {
        checkboxes = [];
        $('input[name="hcheck[]"]:checked').each(function(x, v) {
            checkboxes.push(this.value);
        });
        return checkboxes;
    }
</script>