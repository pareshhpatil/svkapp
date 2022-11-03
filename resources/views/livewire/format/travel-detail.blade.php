<div>
    <!-- add particulars label -->
    <div class="form-group">
        <div class="portlet  col-md-12">
            <div class="portlet-body">
                <h4 class="form-section mt-0">
                    Vehicle booking section
                    <div class="pull-right">
                        <input type="checkbox" id="issec1" name="sec_vehicle" @if(in_array('VS',$activeSection)) checked @endif data-size="small" onchange="showDebit('sec1');" value="1" class="make-switch" data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                    </div>
                </h4>

                <div id="sec1div" @if(!in_array('VS',$activeSection)) style="display: none;" @endif>
                    <div class="form-section mt-0 col-md-4 no-margin no-padding">
                        <input type="text" class="form-control" name="sec_vehicle_name" value="{{$sectionTitle['VS']}}">
                    </div>
                    <h4 class="form-section mt-0">
                        <a href="#particular" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                        <a onclick="AddparticularRow('');" class="btn btn-sm green pull-right"> <i class="fa fa-plus"> </i> Add new row </a>
                    </h4>
                    <div class="table-scrollable">
                        <table class="table table-bordered table-hover" id="particular_table">
                            <tr>
                                @if(!empty($columns))
                                @foreach($columns as $key=>$col)
                                <th class="td-c">{{$col}}</th>
                                @endforeach
                                @endif
                                <th class="td-c">Action</th>
                            </tr>
                            <tbody id="new_particular">
                                @php $int=1 @endphp
                                @if(!empty($particulars))
                                @foreach($particulars as $k=>$name)
                                <tr>
                                    @if(!empty($columns))
                                    @foreach($columns as $key=>$col)
                                    <td class="td-c">
                                        @if($key=='item')
                                        <input type="text" maxlength="100" wire:model.lazy="particulars.{{$k}}" name="particularname[]" class="form-control input-sm" placeholder="Add label">
                                        @elseif($key=='sr_no')
                                        {{$int++}}
                                        @else
                                        <input type="text" class="form-control input-sm" readonly="">
                                        @endif
                                    </td>
                                    @endforeach
                                    @endif
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        drawParticularTable('Particular Total');
    </script>
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
                                        @php $int=1 @endphp
                                        @if(!empty($particularColumns))
                                        @foreach($particularColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c" id="checkbox_box{{ $key }}">
                                                @if($v['is_mandatory']==1)
                                                <input onclick="checkboxClicked('{{ $key }}')" id="{{$key}}" name="particular_col[]" type="checkbox" id="pc_{{$int}}" checked style="pointer-events: none;" value="{{$key}}" />
                                                @else
                                                <input onclick="checkboxClicked('{{ $key }}')" id="{{$key}}" name="particular_col[]" @if($v['is_default']==1) checked @endif id="pc_{{$int}}" type="checkbox" value="{{$key}}" />
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
                                                <input type="text" maxlength="45" id="pc_name_{{$int}}" name="pc_{{$v['system_col_name']}}" class="form-control input-sm" value="{{$v['column_name']}}">
                                            </td>
                                        </tr>
                                        @php $int++ @endphp
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="drawParticularTable();" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script>
        function checkboxClicked(key, type = '') {
            var checkbox_box = document.getElementById("checkbox_box" + key);
            if (document.getElementById(key).checked) {
                checkbox_box.style.backgroundColor = '#E5FCFF';
            } else {
                checkbox_box.style.backgroundColor = '#FFF';
            }

            if (key == 'discount_perc' && document.getElementById(type + "discount_perc").checked == true ||
                key == 'discount' && document.getElementById(type + "discount").checked == true) {
                document.getElementById(type + "rate").checked = true;
            } else if (document.getElementById(type + "discount_perc").checked == true && document.getElementById(type + "discount").checked == false) {
                document.getElementById(type + "rate").checked = true;
            } else if (document.getElementById(type + "discount_perc").checked == false && document.getElementById(type + "discount").checked == true) {
                document.getElementById(type + "rate").checked = true;
            } else if (document.getElementById(type + "discount_perc").checked == true && document.getElementById(type + "discount").checked == true) {
                document.getElementById(type + "rate").checked = true;
            } else if (key == 'rate' && document.getElementById(type + "rate").checked == true) {
                document.getElementById(type + "rate").checked = true;
            } else {
                document.getElementById(type + "rate").checked = false;
            }
        }

        function particularValue() {
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
    </script>

    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">
                Travel booking section
                <div class="pull-right">
                    <input type="checkbox" id="issec2" name="sec_travel" @if(in_array('TB',$activeSection)) checked @endif data-size="small" onchange="
                            showDebit('sec2');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </h4>
            <div id="sec2div" @if(!in_array('TB',$activeSection)) style="display: none;" @endif>
                <div class="form-section mt-0 col-md-4 no-margin no-padding">
                    <input type="text" class="form-control" name="sec_travel_name" value="{{$sectionTitle['TB']}}" />
                </div>
                <a href="#sectravel" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover form-group form-md-line-input" id="tb_table">
                        <thead>
                            <tr>
                                @if(!empty($travelParticularColumns))
                                @foreach($travelParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">{{$col['column_name']}}</th>
                                @endif
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php $int=0 @endphp
                                @if(!empty($travelParticularColumns))
                                @foreach($travelParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">xxxx</th>
                                @php $int++ @endphp
                                @endif
                                @endforeach
                                @endif

                            </tr>
                            <tr>
                                <td colspan="{{$int-1}}">
                                    <b class="pull-right">Total Rs.</b>
                                </td>
                                <td class="td-c"> xxxx</td>
                            </tr>
                        </tbody>

                    </table>
                </div>


                <div class="form-section mt-0 col-md-4 no-margin no-padding">
                    <input type="text" class="form-control" name="sec_travel_cancel_name" value="{{$sectionTitle['TC']}}">
                </div>
                <a href="#sectravelcancel" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover form-group form-md-line-input" id="tc_table">
                        <thead>
                            <tr>
                                @if(!empty($travelParticularCancelColumns))
                                @foreach($travelParticularCancelColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">{{$col['column_name']}}</th>
                                @endif
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php $int=0 @endphp
                                @if(!empty($travelParticularCancelColumns))
                                @foreach($travelParticularCancelColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">xxxx</th>
                                @php $int++ @endphp
                                @endif
                                @endforeach
                                @endif
                            </tr>
                            <tr>
                                <td colspan="{{$int-1}}">
                                    <b class="pull-right">Total Rs.</b>
                                </td>
                                <td class="td-c"> xxxx</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- add particulars label ends -->


    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">
                Hotel booking section
                <div class="pull-right">
                    <input type="checkbox" id="issec3" name="sec_hotel" @if(in_array('HB',$activeSection)) checked @endif data-size="small" onchange="
                            showDebit('sec3');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </h4>
            <div id="sec3div" @if(!in_array('HB',$activeSection)) style="display: none;" @endif>
                <a href="#sechotel" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                <div class="form-section mt-0 col-md-4 no-margin no-padding">
                    <input type="text" class="form-control" name="sec_hotel_name" value="{{$sectionTitle['HB']}}">
                </div>
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover form-group form-md-line-input" id="hb_table">
                        <thead>
                            <tr>
                                @if(!empty($hotelParticularColumns))
                                @foreach($hotelParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">{{$col['column_name']}}</th>
                                @endif
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php $int=0 @endphp
                                @if(!empty($hotelParticularColumns))
                                @foreach($hotelParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">xxxx</th>
                                @php $int++ @endphp
                                @endif
                                @endforeach
                                @endif

                            </tr>
                            <tr>
                                <td colspan="{{$int-1}}">
                                    <b class="pull-right">Total Rs.</b>
                                </td>
                                <td class="td-c"> xxxx</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="portlet  col-md-12">
        <div class="portlet-body">
            <h4 class="form-section mt-0">
                Facilities section
                <div class="pull-right">
                    <input type="checkbox" id="issec4" name="sec_facility" @if(in_array('FS',$activeSection)) checked @endif data-size="small" onchange="showDebit('sec4');" value="1" class="make-switch " data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                </div>
            </h4>
            <div id="sec4div" @if(!in_array('FS',$activeSection)) style="display: none;" @endif>
                <a href="#secfacility" data-toggle="modal" class="btn btn-sm green pull-right"><i class="fa fa-cog"> </i></a>
                <div class="form-section mt-0 col-md-4 no-margin no-padding">
                    <input type="text" name="sec_facility_name" class="form-control" value="{{$sectionTitle['FS']}}">
                </div>
                <div class="table-scrollable">
                    <table class="table table-bordered table-hover form-group form-md-line-input" id="fs_table">
                        <thead>
                            <tr>
                                @if(!empty($facilityParticularColumns))
                                @foreach($facilityParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">{{$col['column_name']}}</th>
                                @endif
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php $int=0 @endphp
                                @if(!empty($facilityParticularColumns))
                                @foreach($facilityParticularColumns as $key=>$col)
                                @if($col['is_default']==1)
                                <th class="td-c">xxxx</th>
                                @php $int++ @endphp
                                @endif
                                @endforeach
                                @endif

                            </tr>
                            <tr>
                                <td colspan="{{$int-1}}">
                                    <b class="pull-right">Total Rs.</b>
                                </td>
                                <td class="td-c"> xxxx</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="sectravel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

                                        @if(!empty($travelParticularColumns))
                                        @foreach($travelParticularColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input name="tb_col[]" type="checkbox" checked style="pointer-events: none;" id="tb_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @else
                                                <input name="tb_col[]" type="checkbox" @if($v['is_default']==1) checked @endif id="tb_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                            <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['help_text']}}" data-original-title="" title=""></i>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" value="{{$v['column_name']}}" name="tb_{{$v['system_col_name']}}" id="tb_name_{{$key+1}}" class="form-control input-sm" value="">
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
                    <button type="button" onclick="drawTravelTable('tb');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="sectravelcancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        @if(!empty($travelParticularCancelColumns))
                                        @foreach($travelParticularCancelColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input name="tc_col[]" type="checkbox" checked style="pointer-events: none;" id="tc_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @else
                                                <input name="tc_col[]" type="checkbox" @if($v['is_default']==1) checked @endif id="tc_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                            <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['help_text']}}" data-original-title="" title=""></i>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" value="{{$v['column_name']}}" name="tc_{{$v['system_col_name']}}" id="tc_name_{{$key+1}}" class="form-control input-sm" value="">
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
                    <button type="button" onclick="drawTravelTable('tc');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div class="modal fade " id="sechotel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        @if(!empty($hotelParticularColumns))
                                        @foreach($hotelParticularColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input onclick="checkboxClicked('{{$v['system_col_name']}}', 'hb_')" name="hb_col[]" type="checkbox" checked style="pointer-events: none;" id="hb_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @else
                                                <input onclick="checkboxClicked('{{$v['system_col_name']}}' , 'hb_')" name="hb_col[]" type="checkbox" @if($v['is_default']==1) checked @endif id="hb_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                            <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['help_text']}}" data-original-title="" title=""></i>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" value="{{$v['column_name']}}" name="hb_{{$v['system_col_name']}}" id="hb_name_{{$key+1}}" class="form-control input-sm" value="">
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
                    <button type="button" onclick="drawTravelTable('hb');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade " id="secfacility" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        @if(!empty($facilityParticularColumns))
                                        @foreach($facilityParticularColumns as $key=>$v)
                                        <tr>
                                            <td class="td-c">
                                                @if($v['is_mandatory']==1)
                                                <input onclick="checkboxClicked('{{$v['system_col_name']}}', 'fs_')" name="fs_col[]" type="checkbox" checked style="pointer-events: none;" id="fs_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @else
                                                <input onclick="checkboxClicked('{{$v['system_col_name']}}', 'fs_')" name="fs_col[]" type="checkbox" @if($v['is_default']==1) checked @endif id="fs_{{$key+1}}" value="{{$v['system_col_name']}}" />
                                                @endif
                                            </td>
                                            <td class="td-c">
                                                <div>{{$v['column_name']}}</div>
                                            </td>
                                            <td class="td-c">
                                            <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v['help_text']}}" data-original-title="" title=""></i>
                                            </td>
                                            <td class="td-c">
                                                <input type="text" maxlength="45" value="{{$v['column_name']}}" name="fs_{{$v['system_col_name']}}" id="fs_name_{{$key+1}}" class="form-control input-sm" value="">
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
                    <button type="button" onclick="drawTravelTable('fs');" class="btn blue" id="closebutton" data-dismiss="modal">Done</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    </script>
</div>