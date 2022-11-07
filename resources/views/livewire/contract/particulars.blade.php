<div x-data="handler()" {{--@if(!$stopPolling && !is_null($contract_id)) wire:poll.10s="updateParticulars()" @endif --}}{{--x-init="particularsPoll()"--}}>
    <style>
        .onhover-border:hover {
            border: 1px solid grey !important;
        }

        .table-hover>tbody>tr:hover {
    background-color: transparent !important;
}

        .table thead tr th {
            font-size: 12px;
            padding: 3px;
            font-weight: 400;
            color: #333;
        }

        .table>tbody>tr>td {
            font-size: 12px !important;
            padding: 3px;
            border: 1px solid #D9DEDE;
            border-right: 0px;
            border-left: 0px;
        }

        ul {
            list-style-type: none !important;
        }

        li {
            list-style-type: none !important;
        }

        .select2-results__option {
            font-size: 12px !important;
        }

        .dropdown-menu li>a {
            font-size: 12px !important;
            line-height: 18px;
        }
    </style>
    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="form-section">Add Particulars</h3>
                </div>
                <div class="col-md-6">
                    <a data-cy="add_particulars_btn" href="javascript:;" @click="await addNewField();" class="btn green pull-right mb-1"> Add new row </a>
                </div>
            </div>
            <div class="table-scrollable">

                <table class="table table-bordered table-hover" id="particular_table" wire:ignore>
                    @if(!empty($particular_column))
                    <thead>
                        <tr>
                            @foreach($particular_column as $k=>$v)
                            @if($k!='description')
                            <th class="td-c @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                <span class="popovers" data-placement="top" data-container="body" data-trigger="hover" data-content="{{$v}}" data-original-title=""> {{Helpers::stringShort($v)}}</span>
                            </th>
                            @endif
                            @endforeach
                            <th class="td-c" style="width: 60px;">
                                ?
                            </th>
                        </tr>
                    </thead>
                    @endif

                    @php $readonly_array=array('retainage_amount','bill_code_detail','group','bill_type','bill_code');
                    $number_array=array('original_contract_amount','retainage_percent');
                    @endphp

                    <tbody>
                        <template x-for="(field, index) in fields" :key="index">
                            <tr>
                                @foreach($particular_column as $k=>$v)
                                @php $readonly=false; @endphp
                                @php $number='type="text"'; @endphp
                                @if($k!='description')
                                @if(in_array($k, $readonly_array))
                                @php $readonly=true; @endphp
                                @endif
                                @if(in_array($k, $number_array))
                                @php $number='type=number step=0.00'; @endphp
                                @endif
                                <td style="max-width: 100px;vertical-align: middle; @if($k=='retainage_amount') background-color:#f5f5f5; @endif" @if($readonly==false) x-on:click="field.txt{{$k}} = true; " x-on:blur="field.txt{{$k}} = false" @endif class="td-c onhover-border @if($k=='bill_code') col-id-no @endif">
                                    @if($k=='bill_code')
                                    <select required style="width: 100%; min-width: 200px;" onchange="billCode2()" :id="`billcode${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select Bill Code" class="form-control input-sm select2me productselect">
                                        <option value="">Select Code</option>
                                        
                                        @if(!empty($csi_codes))
                                        @foreach($csi_codes as $code)
                                        <option value="{{$code['code']}}">{{$code['code']}} | {{$code['title']}}</option>
                                        @endforeach
                                        <template x-for="(bill_code, newbillcodeindex) in new_codes" :key="newbillcodeindex">
                                            <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                                        </template>
                                        @else
                                        <template x-for="(bill_code, billcodeindex) in bill_codes" :key="billcodeindex">
                                            <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                                        </template>
                                        @endif
                                    </select>
                                    <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
                                    <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
                                    <input type="hidden" name="description[]"  x-value="field.description" :id="`description${index}`">
                                    <div class="text-center" style="display: none;">
                                        <p :id="`description-hidden${index}`" x-text="field.description"></p>
                                    </div>
                                    @elseif($k=='group')
                                    <select required style="width: 100%; min-width: 200px;" x-ref="`group${index}`" :id="`group${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select group" class="form-control input-sm select2me groupSelect">
                                        <option value="">Select group</option>
                                        <template x-for="(group, groupindex) in groups" :key="groupindex">
                                            <option x-value="group" x-text="group"></option>
                                        </template>
                                        @if(!empty($pgroups))
                                        @foreach($pgroups as $g)
                                        <option value="{{$g}}">{{$g}}</option>
                                        @endforeach
                                        @endif

                                    </select>
                                    @elseif($k=='bill_type')
                                        <select required style="width: 100%; min-width: 15  0px;font-size: 12px;" :id="`billtype${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select.." class="form-control select2me billTypeSelect">
                                            <option value="">Select..</option>
                                            <option value="% Complete">% Complete</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Calculated">Calculated</option>
                                        </select>
                                    @elseif($k=='bill_code_detail')
                                    <select required style="width: 100%; min-width: 200px;" :id="`billcodedetail${index}`" x-model="field.{{$k}}" name="{{$k}}[]" data-placeholder="Select.." class="form-control select2me billcodedetail">
                                        <option value="">Select..</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    @else
                                        @if($k=='original_contract_amount')
                                            <template x-if="field.bill_type!='Calculated'">
                                            <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}"> </span>
                                            </template>
                                        @else
                                            <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}"> </span>
                                        @endif
                                    @endif

                                    @if($k=='original_contract_amount')

                                    <template x-if="field.bill_type=='Calculated'">
                                        <div>
                                            <span :id="`lbl_original_contract_amount${index}`" x-text="field.{{$k}}"></span><br>
                                            <a :id="`add-calc${index}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCaculated(field)">Add calculation</a>
                                            <a :id="`remove-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                                            <span :id="`pipe-calc${index}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                            <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Edit</a>
                                        </div>
                                        <span x-show="field.txt{{$k}}">
                                            <input :id="`{{$k}}${index}`" type="hidden" x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                        </span>
                                    </template>
                                    <template x-if="field.bill_type!='Calculated'">
                                        <span x-show="field.txt{{$k}}">
                                            <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                        </span>
                                    </template>

                                    <input :id="`introw${index}`" type="hidden" :value="index" x-model="field.introw" name="pint[]">


                                    @else
                                    <span x-show="field.txt{{$k}}">
                                        <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm ">
                                    </span>
                                    @endif
                                </td>
                                @endif
                                @endforeach
                                <td class="td-c " style="vertical-align: middle;width: 60px;">
                                    <button type="button" class="btn btn-xs red" @click="removeField(index)">&times;</button>
                                    <template x-if="count==index">
                                        <span>
                                            <a href="javascript:;" @click="await addNewField();" class="btn btn-xs green">+</a>
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="warning">
                            <th class="col-id-no"></th>
                            <th class="td-c">Grand total</th>
                            <th class="td-c">
                                <span id="particulartotaldiv">{{$total}}</span>
                                <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="{{$total}}" class="form-control " readonly="">
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>

                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @include('app.merchant.contract.add-group-modal')
    @include('app.merchant.contract.add-calculation-modal2')

    <script>
        csi_codes = [];
        total = 0;
        particular_array = JSON.parse('{!!$particular_json!!}');

        @if($csi_code_json != '')
            csi_codes = JSON.parse('{!!$csi_code_json!!}');
        @endif

        window.addEventListener('update-csi-codes', event => {
            this.bill_codes = event.detail.csi_codes
            csi_codes = event.detail.csi_codes
            // console.log(this.bill_codes);
            document.getElementById('_project_id').value = event.detail.project_id
            //console.log(csi_codes);
        })

        /*function particularsPoll(){
            setInterval(() => {
                @this.call('updateParticulars', this.fields);
            }, 10000)
        }*/
        function handler() {

            return {
                // project_id : @entangle('project_id'),
                fields: particular_array,
                group_name: '',
                group_show: false,
                bill_code_name: '',
                bill_code_description: '',
                selected_group: [],
                panel: true,
                billcodepanel: false,
                bill_codes: csi_codes,
                new_codes: [],
                calculations: [],
                groups: [],
                count: particular_array.length - 1,
                bcd: ['Yes', 'No'],
                bill_types: ['% Complete', 'Unit', 'Calculated'],
                grand_total: 0,


                removeField(id) {
                    this.fields.splice(id, 1);

                    total = 0;
                    this.fields.forEach(function(currentValue, index, arr) {
                        total = Number(total) + Number(getamt(currentValue.original_contract_amount));
                    });

                    document.getElementById('particulartotal').value = updateTextView1(total);
                    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

                    numrow = this.fields.length - 1;
                    this.count = numrow;

                    // @this.set('particulars', this.fields);
                },
                addGroup(field) {

                    this.selected_group = field;
                    this.panel = true;
                    document.getElementById("panelWrapIdgroup").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    document.getElementById("panelWrapIdgroup").style.transform = "translateX(0%)";
                },
                addBillCode(field) {
                    this.selected_group = field;
                    this.billcodepanel = true;
                    document.getElementById("_project_id").value = document.getElementById("project_id").value;
                    document.getElementById("panelWrapIdBillCodePanel").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    document.getElementById("panelWrapIdBillCodePanel").style.transform = "translateX(0%)";
                },

                saveGroup() {
                    if (this.group_name != '') {
                        this.groups.push(this.group_name);
                        group_index = document.getElementById("group_index").value;

                        document.getElementById("group" + group_index).value = this.group_name;
                        document.getElementById("panelWrapIdgroup").style.boxShadow = "";
                        document.getElementById("panelWrapIdgroup").style.transform = "";
                        var newState = new Option(this.group_name, this.group_name, true, true);
                        $("#group" + group_index).append(newState).trigger('change');
                        this.group_name='';
                    }

                },

                setBCD(val, field) {
                    field.bill_code_detail = val;
                },
                setBillCode(val, field) {
                    field.bill_code = val;
                },

                setBillCodes() {
                    var bill_code_name = document.getElementById("bill_code_name").value;
                    var bill_code_description = document.getElementById("bill_code_description").value;
                    this.bill_codes.push({
                        code: bill_code_name,
                        title: bill_code_description,

                    });
                    this.new_codes.push({
                        code: bill_code_name,
                        title: bill_code_description,

                    });
                    //this.bill_codes = csi_codes;
                    this.billcodepanel = false;
                    this.selected_group.bill_code = bill_code_name;
                },

                calc(field) {
                    try {
                        try {
                            this.groups.indexOf(field.group) === -1 ? this.groups.push(field.group) : console.log("This item already exists");

                        } catch (o) {}
                        try {
                            field.retainage_amount = updateTextView1(getamt(field.original_contract_amount) * getamt(field.retainage_percent) / 100);
                        } catch (o) {}
                        try {
                            field.original_contract_amount = updateTextView1(getamt(field.original_contract_amount));
                        } catch (o) {}
                        try {
                            field.retainage_percent = updateTextView1(getamt(field.retainage_percent));
                        } catch (o) {}
                        total = 0;
                        this.fields.forEach(function(currentValue, index, arr) {
                            try {
                                oct = Number(getamt(currentValue.original_contract_amount));
                            } catch (o) {
                                oct = 0;
                            }
                            if (oct > 0) {
                                total = Number(total) + oct;

                            }
                        });

                        document.getElementById('particulartotal').value = updateTextView1(total);
                        document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);
                    } catch (o) {
                        // alert(o.message);
                    }

                },

                wait(x) {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve(x);
                        }, 100);
                    });
                },

                setAOriginalContractAmount() {
                    selected_field_int = document.getElementById('selected_field_int').value;
                    calc_amount = document.getElementById("calc_amount").value;
                    try{
                        this.fields[selected_field_int].original_contract_amount = calc_amount;
                    }catch(o){}
                    
                    setOriginalContractAmount();
                    this.fields[selected_field_int].calculated_perc = document.getElementById('calculated_perc' + selected_field_int).value;
                    this.fields[selected_field_int].calculated_row = document.getElementById('calculated_row' + selected_field_int).value;

                },
                OpenAddCaculated(field) {
                    this.selected_field_int = field.introw;
                    document.getElementById('selected_field_int').value = field.introw;
                    OpenAddCaculatedRow(field.introw);
                },
                RemoveCaculated(field) {
                    this.fields[field.introw].original_contract_amount = 0;
                    document.getElementById('lbl_original_contract_amount' + field.introw).innerHTML = '';
                    RemoveCaculatedRow(field.introw);
                },
                EditCaculated(field) {
                    document.getElementById('selected_field_int').value = field.introw;
                    editCaculatedRow(field.introw);
                },

                select2Dropdown(id) {
                    try {
                        $('#billcode' + id).select2({
                            insertTag: function(data, tag) {

                            }
                        }).on('select2:open', function(e) {
                            pind = $(this).index();
                            if (document.getElementById('prolist' + pind)) {} else {
                                $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + id + ',' + id + ',0);">Add new bill code</a> </div>');
                            }
                        });
                        /*.on('change', function () {
                                                    let valueArr = $(this).val().split(' | ');
                                                    // this.fields[id].bill_code = valueArr[0];
                                                    console.log(this.fields.length);
                                                });*/
                    } catch (o) {}

                    try {
                        // $('#billtype' + id).select2({
                        //     minimumResultsForSearch: -1
                        // });
                    } catch (o) {}
                    try {
                        $('#billcodedetail' + id).select2({
                            minimumResultsForSearch: -1
                        });
                    } catch (o) {}

                    try {
                        $('#group' + id).select2({
                            insertTag: function(data, tag) {

                            }
                        }).on('select2:open', function(e) {
                            pind = $(this).index();
                            if (document.getElementById('grouplist' + pind)) {} else {
                                $('.select2-results').append('<div class="wrapper" id="grouplist' + pind + '" > <a class="clicker" onclick="setgroup(' + id + ');">Add new group</a> </div>');
                            }
                        });
                    } catch (o) {}
                },

                init() {
                    console.log('I will get evaluated when initializing each "dropdown" component.')
                },



                async addNewField() {
                    document.getElementById('perror').style.display = 'none';
                    _project_id = document.getElementById('project_id').value;
                    if (_project_id > 0) {
                        project_code = $("#project_id option:selected").text();
                        int = project_code.indexOf(" | ");
                        project_code2 = project_code.substring(0, int);
                        this.bill_codes = csi_codes;
                        int = this.fields.length;
                        this.fields.push({
                            introw: int,
                            bill_code: '',
                            bill_type: '',
                            group: '',
                            bill_code_detail: 'Yes',
                            project: project_code2
                        });
                        const x = await this.wait(10);
                        numrow = this.fields.length - 1;

                        this.count = numrow;

                        this.select2Dropdown(numrow);
                        this.saveParticulars();
                        // @this.set('particulars', this.fields);


                    } else {
                        document.getElementById('perror').style.display = 'block';
                    }
                }

            }

        }
    </script>
</div>