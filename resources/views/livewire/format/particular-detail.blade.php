<div>
    <!-- add particulars label -->
    <div class="form-group">
        <div class="portlet  col-md-12">
            <div class="portlet-body">
                <h4 class="form-section mt-0">Add particulars
                    @if($template_type!='construction')
                    <a data-cy="add-columns-in-particulars" href="#particular" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                    <a wire:click="addParticular" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
                    @endif
                </h4>
                <div class="table-scrollable">
                    @if($template_type=='construction')
                    <table class="table table-bordered table-hover" id="particular_table">
                        <thead>
                            <tr>
                                <th class="td-c">Bill Code</th>
                                <th class="td-c">Desc</th>
                                <th class="td-c">Bill Type (% Complete or Unit)</th>
                                <th class="td-c">Original Contract Amount</th>
                                <th class="td-c">Approved Change Order Amount</th>
                                <th class="td-c">Current Contract Amount</th>
                                <th class="td-c">Previously Billed Percent</th>
                                <th class="td-c">Previously Billed Amount</th>
                                <th class="td-c">Current Billed Percent</th>
                                <th class="td-c">Current Billed Amount</th>
                                <th class="td-c">Stored Materials</th>
                                <th class="td-c">Total Billed (including this draw)</th>
                                <th class="td-c">Retainage %</th>
                                <th class="td-c">Retainage Amount Previously Withheld</th>
                                <th class="td-c">Retainage amount for this draw</th>
                                <th class="td-c">Retainage Release Amount</th>
                                <th class="td-c">Total outstanding retainage</th>
                                <th class="td-c">Project</th>
                                <th class="td-c">Cost Code</th>
                                <th class="td-c">Cost Type</th>
                                <th class="td-c">Group</th>
                                <th class="td-c">Bill code detail</th>
                                <th class="td-c">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tbody>
                            <tr>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="text" readonly="" class="form-control input-sm">
                                </td>

                                <td></td>
                            </tr>
                            <tr class="warning">
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>
                                <th class="td-c">
                                </th>

                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <table class="table table-bordered table-hover" id="particular_table">
                        <thead>
                            <tr>
                                @if(!empty($columns))
                                @foreach($columns as $key=>$col)
                                <th class="td-c">{{$col}}</th>
                                @endforeach
                                @endif
                                <th class="td-c">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $int=1 @endphp
                            @if(!empty($particulars))
                            @foreach($particulars as $k=>$name)
                            <tr>
                                @foreach($columns as $key=>$col)
                                <td class="td-c">
                                    @if($key=='item')
                                    <select required style="width: 100%;" data-cy="particular_item{{$int}}" selectNum="{{$k}}" name="particularname[]" data-placeholder="Type or Select" class="form-control productselect">
                                        <option value="">Select Product</option>
                                        @if(!empty($product_list))
                                        @foreach($product_list as $pk=>$vk)
                                        @if($name == $pk)
                                        <option selected value="{{$pk}}">{{$pk}}</option>
                                        @else
                                        <option value="{{$pk}}">{{$pk}}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                    @elseif($key=='sr_no')
                                    {{$int++}}
                                    @else
                                    <input type="text" class="form-control input-sm" readonly="">
                                    @endif
                                </td>
                                @endforeach
                                <td>
                                    <a href="javascript:;" wire:click="remove({{$k}})" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>
                        <tbody>
                            <tr class="warning">
                                @if(!empty($columns))
                                @foreach($columns as $key=>$col)
                                <th class="td-c">
                                    @if($key=='item') <input type="text" class="form-control input-sm" value="Particular total" readonly=""> @elseif($key=='total_amount') <input type="text" class="form-control input-sm" readonly=""> @endif
                                </th>
                                @endforeach
                                @endif
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- add particulars label ends -->
    <div class="modal fade" aria-hidden="true" id="particular" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select display column</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-scrollable">
                                <table class="table table-bordered table-hover font14 smalltbl">
                                    <thead>
                                        <tr>
                                            <th class="td-c">
                                                Display
                                            </th>
                                            <th class="td-c">
                                                Field name
                                            </th>
                                            <th class="td-c">
                                            </th>
                                            <th class="td-c">
                                                Column name
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($particularColumns))
                                        @foreach($particularColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c" id="checkbox_box{{ $key }}">
                                                @if($v['is_mandatory']==1)
                                                <input onclick="checkboxClicked('{{ $key }}')" data-cy="is_display_column" id="{{$key}}" name="particular_col[]" type="checkbox" checked style="pointer-events: none;" value="{{$key}}" />
                                                @else
                                                <input onclick="checkboxClicked('{{ $key }}')" data-cy="is_display_column" id="{{$key}}" name="particular_col[]" @if($v['is_default']==1) checked @endif type="checkbox" value="{{$key}}" />
                                                @endif
                                                <script>
                                                    var key = '{{ $key }}';
                                                    var checkbox_box = document.getElementById("checkbox_box" + key);
                                                    if (document.getElementById(key).checked) {
                                                        checkbox_box.style.backgroundColor = '#E5FCFF';
                                                    } else {
                                                        checkbox_box.style.backgroundColor = '#FFF';
                                                    }
                                                </script>

                                            </td>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                                <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['help_text']}}" data-original-title="" title=""></i>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" id="pc_{{$v['system_col_name']}}" name="pc_{{$v['system_col_name']}}" class="form-control input-sm" value="{{$v['column_name']}}">
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
                    <a wire:click="submit(particularValue());" data-dismiss="modal" class="btn blue">Done</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>

<script>
    function checkboxClicked(key) {
        var checkbox_box = document.getElementById("checkbox_box" + key);
        if (document.getElementById(key).checked) {
            checkbox_box.style.backgroundColor = '#E5FCFF';
        } else {
            checkbox_box.style.backgroundColor = '#FFF';
        }

        if (key == 'discount_perc' && document.getElementById("discount_perc").checked == true ||
            key == 'discount' && document.getElementById("discount").checked == true) {
            document.getElementById("rate").checked = true;
        } else if (document.getElementById("discount_perc").checked == true && document.getElementById("discount").checked == false) {
            document.getElementById("rate").checked = true;
        } else if (document.getElementById("discount_perc").checked == false && document.getElementById("discount").checked == true) {
            document.getElementById("rate").checked = true;
        } else if (document.getElementById("discount_perc").checked == true && document.getElementById("discount").checked == true) {
            document.getElementById("rate").checked = true;
        } else if (key == 'rate' && document.getElementById("rate").checked == true) {
            document.getElementById("rate").checked = true;
        } else {
            document.getElementById("rate").checked = false;
        }
    }

    function particularValue() {
        var rateExists = 0;
        particular = [];
        $('input[name="particular_col[]"]:checked').each(function(x, v) {
            var parray = '{"' + this.value + '":"' + $('#pc_' + this.value).val() + '"}';
            particular.push(parray);
            if (this.value == 'rate') {
                rateExists = 1;
            }
            if (this.value == 'discount_perc' || this.value == 'discount') {
                if (!rateExists) {
                    var parray = '{"rate":"' + $('#pc_rate').val() + '"}';
                    particular.push(parray);
                }
            }
        });
        return particular;
    }

    document.addEventListener("livewire:load", function(event) {
        Livewire.hook('message.processed', (message, component) => {
            $('.productselect').select2({
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
                select_id = $(this).attr('selectNum');
                var index = $(".productselect").index(this);
                if (document.getElementById('prolist' + pind)) {} else {
                    $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="proindex(' + index + ',' + select_id + ');">Add new product</a> </div>');
                }
            });
            $('.productselect').on('change', function(e) {
                let elementName = $(this).attr('wire:model');
                let key = $(this).attr('selectNum');
                var data = $(this).select2('val');
                livewire.emit('setParticularItem', e.target.value, key);
            });
        })
    });
</script>