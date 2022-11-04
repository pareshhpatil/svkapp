<style>
    .onhover-border:hover {
        border: 1px solid #5C6B6B  !important;
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
    .select2-results__option{
        font-size: 12px !important;
    }
    .dropdown-menu li > a {
        font-size: 12px !important;
        line-height: 18px;
    }
</style>
<div x-data="handler()">
    <div class="row">
        <div class="col-md-6">
            <h3 class="form-section">Add particulars </h3>
        </div>
        <div class="col-md-6">
            <a data-cy="add_particulars_btn" href="javascript:;" @click="addNewField();" class="btn green pull-right mb-1"> Add new row </a>
        </div>
    </div>
    <div class="table-scrollable">
        <table class="table table-bordered table-hover" id="particular_table">
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
                    <th class="td-c">
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
                        <td style="max-width: 100px;vertical-align: middle;" @if($readonly==false) x-on:click="field.txt{{$k}} = true; @if($k=='bill_code') setAdvanceDropdownContract(index); @endif @if($k=='group') setAdvanceDropdownGroup(index+2); @endif" x-on:blur="field.txt{{$k}} = false" @else style="background-color: #f5f5f5;" @endif class="td-c onhover-border @if($k=='bill_code') col-id-no @endif" @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                            @if($k=='group')
                            <li class="dropdown">
                                <a href="javascript:;" x-text="field.{{$k}}" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                                </a>
                                <ul class="dropdown-menu">
                                    <template x-for="(grp, gindex) in groups" :key="gindex">
                                        <li>
                                            <a href="#" @click="setgroup(grp,field)" x-text="grp">

                                            </a>
                                        </li>
                                    </template>
                                    <li class="">
                                        <a class="" @click="addGroup(field);" href="#" style="color: #707ee0;"> Add new</a>
                                    </li>

                                </ul>
                            </li>
                            @elseif($k=='bill_code')

                            <select required style="width: 100%; min-width: 200px;" onchange="billCode2()" :id="`billcode${index+2}`" x-model="field.{{$k}}" value="fee" name="{{$k}}[]" data-placeholder="Select Bill Code" class="">
                                <option value="">Select Code</option>
                                <template x-for="(bill_code, billcodeindex) in bill_codes" :key="billcodeindex">
                                    <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                                </template>
                                <option value="aa">bb</option>
                            </select>
                            <div class="text-center" style="display: none;">
                                <p :id="`description${index}`" class="lable-heading"></p>
                                <p :id="`description-hidden${index}`"></p>
                            </div>
                            @elseif($k=='bill_code_detail')
                            <li class="dropdown">
                                <a href="javascript:;" x-text="field.{{$k}}" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                                </a>
                                <ul class="dropdown-menu">
                                    <template x-for="(b, bindex) in bcd" :key="bindex">
                                        <li>
                                            <a href="#" @click="setBCD(b,field)" x-text="b">

                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            @elseif($k=='bill_type')
                            <li class="dropdown">
                                <a href="javascript:;" x-text="field.{{$k}}" class="dropdown-toggle dropdown-l" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                                </a>
                                <ul class="dropdown-menu">
                                    <template x-for="(bt, btindex) in bill_types" :key="btindex">
                                        <li>
                                            <a href="#" @click="setBillType(bt,field)" x-text="bt">

                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </li>
                            @else
                            <span x-show="! field.txt{{$k}}" x-text="field.{{$k}}"> </span>
                            @endif

                            @if($k=='original_contract_amount')
                            <input :id="`{{$k}}${index}`" type="hidden" :value="index" name="pint[]">
                            <a :id="`add-calc${index}`" style="display: none; padding-top: 5px;" href="javascript:;" @click="OpenAddCaculated(field)">Add calculation</a>
                            <a :id="`remove-calc${index}`" style="display:none;padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                            <span :id="`pipe-calc${index}`" style="display:none;margin-left: 4px; color:#859494;"> | </span>
                            <a :id="`edit-calc${index}`" style="display:none;padding-top:5px;padding-left:5px;" href="javascript:;" @click="editCaculated(field)">Edit</a>
                            @endif
                            <span x-show="field.txt{{$k}}">
                                <input :id="`{{$k}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$k}} = false;calc(field);" @endif x-model="field.{{$k}}" value="" name="{{$k}}[]" style="width: 100%;" class="form-control input-sm">
                            </span>
                        </td>
                        @endif
                        @endforeach
                        <td class="td-c ">
                            <button type="button" class="btn btn-xs red" @click="removeField(index)">&times;</button>
                        </td>
                    </tr>
                </template>
            </tbody>
            <tfoot>
                <tr class="warning">
                    <th class="col-id-no"></th>
                    <th class="td-c">Grand total</th>
                    <th class="td-c">
                        <span id="particulartotaldiv"></span>
                        <input type="hidden" id="particulartotal" data-cy="particular-total1" name="totalcost" value="" class="form-control " readonly="">
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

    @include('app.merchant.contract.add-group-modal')

    

</div>


<script>
    csi_codes = [];
    total = 0;

    function handler() {
        return {
            fields: [],
            group_name: '',
            bill_code_name: '',
            bill_code_description: '',
            selected_group: [],
            panel: false,
            billcodepanel: false,
            bill_codes: [],
            groups: [],
            bcd: ['Yes', 'No'],
            bill_types: ['% Complete', 'Unit', 'Calculated'],
            grand_total: 0,
            removeField(index) {
                this.fields.splice(index, 1);

                total = 0;
                this.fields.forEach(function(currentValue, index, arr) {
                    total = Number(total) + Number(getamt(currentValue.original_contract_amount));
                });

                document.getElementById('particulartotal').value = updateTextView1(total);
                document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);
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
                    if (this.group_name != '-') {
                        this.groups.push(this.group_name);
                        this.selected_group.group = this.group_name;
                        this.panel = false;
                        this.group_name = '';
                    }
                }

            },
            setgroup(val, field) {
                field.group = val;
            },
            setBCD(val, field) {
                field.bill_code_detail = val;
            },
            setBillCode(val, field) {
                field.bill_code = val;
            },
            setBillType(val, field) {
                //  addCaculatedRow(val, field.introw);
                field.bill_type = val;
            },

            setBillCodes() {
                var bill_code_name = document.getElementById("bill_code_name").value;
                var bill_code_description = document.getElementById("bill_code_description").value;
                this.bill_codes.push({
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
            OpenAddCaculated(field) {
                OpenAddCaculatedRow(field.introw);
            },
            OpenRemoveCaculated(field) {
                OpenRemoveCaculatedRow(field.introw);
            },
            OpenEditCaculated(field) {
                OpenEditCaculatedRow(field.introw);
            },

            async addNewField() {
                document.getElementById('perror').style.display = 'none';
                _project_id = document.getElementById('_project_id').value;
                _project_id=1;
                if (_project_id > 0) {
                    project_code = $("#project_id option:selected").text();
                    int = project_code.indexOf(" | ");
                    project_code2 = project_code.substring(0, int);
                    this.bill_codes = csi_codes;
                    int = this.fields.length;
                    this.fields.push({
                        introw: int,
                        bill_code: '-',
                        bill_type: '-',
                        group: '-',
                        bill_code_detail: '-',
                        project_code: project_code2
                    });
                    const x = await this.wait(10);
                    numrow = this.fields.length + 1;
                    VirtualSelect.init({
                            ele: 'select',
                            zIndex: 2000,
                            dropboxWrapper: 'body'
                        });
                } else {
                    document.getElementById('perror').style.display = 'block';
                }
            }

        }

    }
</script>