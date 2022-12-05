<div>
    <div class="col-md-6" id="newHeaderleft">
        <h4 class="form-section mt-0">Customer Details <a data-toggle="modal" href="#customer" style="margin-right: 15px;" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Manage field </a></h4>
        <ul class="sortable">
            @if(!empty($customerColumns))
            @foreach($customerColumns as $key=>$v)
            @if($v['is_default']==1)
            @if($v['datatype']=="textarea")
            @php $text ='<textarea class="form-control input-sm" readonly=""></textarea>' @endphp
            @else
            @php $text ='<input type="text" class="form-control input-sm" readonly>' @endphp
            @endif

            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <div class="form-group" style="display: initial;">
                    @isset($v['column_id'])
                    <input name="cust_column_id[]" type="hidden" value="{{$v['column_id']}}">
                    @else
                    <input name="cust_column_id[]" type="hidden" value="0">
                    @endif
                    <input name="customer_column_id[]" type="hidden" value="{{$v['id']}}" />
                    <input name="customer_column_type[]" type="hidden" value="{{$v['type']}}" />
                    <input name="customer_column_name[]" type="hidden" value="{{$v['column_name']}}" />
                    <input name="customer_datatype[]" type="hidden" value="{{$v['datatype']}}" />
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa fa-arrows-v"></i></div>
                            </span>
                            <input type="text" readonly value="{{$v['column_name']}}" readonly class="form-control input-sm" maxlength="40">

                            <span class="input-group-btn">
                                <div class="btn default btn-sm"> <i class="fa {{$fonts[$v['datatype']] ?? 'fa-font'}}"></i></div>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        @if($v['is_mandatory']=='0')
                        <div class="input-group">
                            {!!$text!!}
                            <span class="input-group-addon " wire:click="remove({{$key}})">
                                <i class="fa fa-minus-circle"></i>
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
    <div class="modal fade" id="customer" aria-hidden="true" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
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
                                                Display?
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($customerColumns))
                                        @foreach($customerColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input type="checkbox" checked style="pointer-events: none;" name="ccheck[]" value="{{$key}}" />
                                                @else
                                                <input @if($v['is_default']==1) checked @endif type="checkbox" name="ccheck[]" value="{{$key}}" />
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
                    <a wire:click="submit(customerValue());" data-dismiss="modal" class="btn blue">Done</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<script>
    function customerValue() {
        checkboxes = [];
        $('input[name="ccheck[]"]:checked').each(function(x, v) {
            checkboxes.push(this.value);
        });
        return checkboxes;
    }
</script>