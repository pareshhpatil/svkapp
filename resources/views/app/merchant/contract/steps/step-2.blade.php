<div  x-data="handle_particulars()" x-init="initializeParticulars" >
    <style>
        .onhover-border:hover {
            border: 1px solid #ddd !important;
        }

        .table thead tr th {
            font-size: 12px;
            padding: 3px;
            font-weight: 400;
            color: #333;
        }

        .table > tbody > tr > td {
            font-size: 12px !important;
            padding: 3px;
            border: 1px solid #D9DEDE;
            border-right: 0px;
            border-left: 0px;
        }

        .error-corner {
            border: 1px solid grey;
            background-image: linear-gradient(225deg, red, red 6px, transparent 6px, transparent);
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

        .dropdown-menu li > a {
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
                    <a data-cy="add_particulars_btn" href="javascript:;"  @click="await addNewRow()"
                       class="btn green pull-right mb-1"> Add new row </a>
                </div>
            </div>
            <div class="table-scrollable">
                <table class="table table-bordered table-hover" id="particular_table">
                    @php $particular_column = \App\ContractParticular::$particular_column @endphp
                    @include('app.merchant.contract.steps.step-2-head')

                    <tbody>
                        <template x-for="(field, index) in fields" :key="index">
                            <tr>
                                @foreach($particular_column as $column => $details)
                                    <td :id="`cell_{{$column}}_${index}`" @if(isset($details['visible'])) x-on:click="field.show{{$column}} = true; " x-on:blur="field.show{{$column}} = false" @endif  class="td-c onhover-border @if($column=='bill_code') col-id-no @endif">
                                        @switch($column)
                                            @case('bill_code')
                                                <div :id="`{{$column}}${index}`" x-model="field.{{$column}}"></div>
                                                <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
                                                <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
                                                <input type="hidden" name="description[]"  x-value="field.description" :id="`description${index}`">
                                                <div class="text-center" style="display: none;">
                                                    <p :id="`description-hidden${index}`" x-text="field.description"></p>
                                                </div>
                                            @break

                                            @case('bill_type')
                                                <select required style="width: 100%; min-width: 15  0px;font-size: 12px;" :id="`{{$column}}${index}`" x-model="field.{{$column}}" name="{{$column}}[]" data-placeholder="Select.." class="form-control input-sm billTypeSelect">
                                                    <option value="">Select..</option>
                                                    <option value="% Complete">% Complete</option>
                                                    <option value="Unit">Unit</option>
                                                    <option value="Calculated">Calculated</option>
                                                </select>
                                            @break

                                            @case('group')
                                                <div :id="`{{$column}}${index}`" x-model="field.{{$column}}"></div>
                                            @break

                                            @case('bill_code_detail')
                                                <select required style="width: 100%; min-width: 200px;" :id="`{{$column}}${index}`" x-model="field.{{$column}}" name="{{$column}}[]" data-placeholder="Select.." class="form-control  input-sm billcodedetail">
                                                    <option value="">Select..</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            @break

                                            @case('original_contract_amount')
                                                <template x-if="field.bill_type!='Calculated'">
                                                    <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                                    <span x-show="field.show{{$column}}">
                                                        <input :id="`{{$column}}${index}`"{{-- @if(isset($details['visible'])) type="hidden" @else--}} type="text" x-on:blur="field.show{{$column}} = false;calc(field);saveParticulars();" {{--@endif--}} @keyup="removeValidationError(`{{$column}}`, `${index}`)" x-model="field.{{$column}}" value="" name="{{$column}}[]" style="width: 100%;" class="form-control input-sm ">
                                                    </span>
                                                </template>

                                                <template x-if="field.bill_type=='Calculated'">
                                                    <div>
                                                        <span :id="`lbl_original_contract_amount${index}`" x-text="field.{{$column}}"></span><br>
                                                        <a :id="`add-calc${index}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCaculated(field)">Add calculation</a>
                                                        <a :id="`remove-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
                                                        <span :id="`pipe-calc${index}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                                        <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Edit</a>
                                                    </div>
                                                    <span x-show="field.show{{$column}}">
                                                        <input :id="`{{$column}}${index}`" type="hidden" x-model="field.{{$column}}" value="" name="{{$column}}[]" style="width: 100%;" class="form-control input-sm ">
                                                    </span>
                                                </template>


                                                <input :id="`introw${index}`" type="hidden" :value="index" x-model="field.introw" name="pint[]">
                                            @break

                                            @case('retainage_amount')
                                                <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                            @break

                                            @default
                                                <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                                <span x-show="field.show{{$column}}">
                                                    <input :id="`{{$column}}${index}`" {{--@if($readonly==true) type="hidden" @else--}} type="text" x-on:blur="field.show{{$column}} = false;calc(field);" {{--@endif--}} x-model="field.{{$column}}" value="" name="{{$column}}[]" style="width: 100%;" class="form-control input-sm ">
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                @endforeach
                                <td class="td-c " style="vertical-align: middle;width: 60px;">
                                    <button type="button" class="btn btn-xs red" @click="removeRow(index)">&times;</button>
                                    <template x-if="count === index">
                                    <span>
                                        <a href="javascript:;" @click="await addNewRow();" class="btn btn-xs green">+</a>
                                    </span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>

                    @include('app.merchant.contract.steps.step-2-footer')

                </table>
            </div>
            @include('app.merchant.contract.add-group-modal')
            @include('app.merchant.contract.add-calculation-modal2')
            @include('app.merchant.contract.add-bill-code-modal-contract')
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a class="btn green">Back</a>
                        <button class="btn blue" type="submit" @click="return validateParticulars()">Preview contract</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function initializeParticulars(){
            this.initializeDropdowns();
        }


        var particularsArray = JSON.parse('{!! json_encode($particulars) !!}');
        var bill_codes = JSON.parse('{!! json_encode($bill_codes) !!}');
        var groups = JSON.parse('{!! json_encode($groups) !!}');
        var only_bill_codes = JSON.parse('{!! json_encode(array_column($bill_codes, 'value')) !!}');
        var row = JSON.parse('{!! json_encode($row) !!}')
        function addPopover(id, message){
            $('#'+id).attr({
                'data-placement' : 'right',
                'data-container' : "body",
                'data-trigger' : 'hover',
                'data-content' : message,
                // 'data-original-title'
            }).popover();
        }

        function handle_particulars(){
            return {
                fields : JSON.parse('{!! json_encode($particulars) !!}'),
                bill_code : null,
                bill_description : null,
                group_name : null,
                count : {!! count($particulars) !!},

                addNewBillCode(){
                    var data = $("#billcodeform").serialize();
                    console.log(data);return false
                    var actionUrl = '/merchant/billcode/create';
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: data,
                        success: function (data) {
                            console.log(data);
                        }
                    });

                    let new_bill_code = $('#new_bill_code').val();
                    let new_bill_description = $('#new_bill_description').val();

                    let label = new_bill_code + ' | ' + new_bill_description

                    bill_codes.push(
                        {label: label, value : new_bill_code, description : new_bill_description }
                    )

                    this.updateBillCodeDropdowns(bill_codes, new_bill_code);




                    // initializeBillCodes();
                    return false;
                },
                updateBillCodeDropdowns(optionArray, selectedValue){
                    let selectedId = $('#selectedBillCodeId').val();

                    for(let v=0; v < this.fields.length; v++){
                        let billCodeSelector = document.querySelector('#bill_code' + v);

                        if(selectedId === 'bill_code'+v ) {
                            billCodeSelector.setOptions(optionArray, selectedValue);
                            this.fields[v].bill_code = billCodeSelector.value;
                            particularsArray.bill_code = billCodeSelector.value;
                            closeSidePanelBillCode()
                        }
                        else billCodeSelector.setOptions(optionArray, $('#bill_code'+v).val());

                    }

                    $('#new_bill_code').val(null);
                    $('#new_bill_description').val(null);
                    $('#selectedBillCodeId').val(null);
                },
                initializeDropdowns(){
                    for(let v=0; v < this.fields.length; v++){
                        this.virtualSelect(v, 'bill_code', bill_codes, this.fields[v].bill_code)
                        this.virtualSelect(v, 'group', groups, this.fields[v].group)
                    }
                },
                virtualSelect(id, type, options, selectedValue){
                    VirtualSelect.init({
                        ele: '#'+type+id,
                        options: options,
                        dropboxWrapper: 'body',
                        allowNewOption: true,
                        multiple:false,
                        selectedValue : selectedValue
                    });


                    $('#'+type+id).change(function () {
                        if(type === 'bill_code') {
                            particularsArray[id].bill_code = this.value
                            if (this.value !== null && this.value !== '' && !only_bill_codes.includes(this.value)) {
                                $('#new_bill_code').val(this.value)
                                $('#selectedBillCodeId').val(type + id)
                                billIndex(0, 0, 0)
                            }
                        }
                        if(type === 'group'){
                            if(!groups.includes(this.value)) groups.push(this.value)
                            particularsArray[id].group = this.value
                        }
                    });

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
                removeValidationError(fieldName, id){
                    if($('#'+fieldName+id).val() !== null && $('#'+fieldName+id).val() !== '') {
                        $('#cell_'+ fieldName+'_'+id).removeClass(' error-corner').popover('destroy')
                    }
                },
                wait(x) {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve(x);
                        }, 100);
                    });
                },
                validateParticulars(){
                    let valid = true;
                    this.copyBillCodeGroups();
                    for(let p=0; p < this.fields.length;p++){

                        if(this.fields[p].bill_code === null || this.fields[p].bill_code === '') {
                            $('#cell_bill_code_' + p).addClass(' error-corner');
                            addPopover('cell_bill_code_' + p, "Please select Bill code");
                            valid = false
                        }else{
                            $('#cell_bill_code_' + p).removeClass(' error-corner').popover('destroy')
                            // this.fields[p].bill_code = this.fields[p].bill_code
                        }

                        if(this.fields[p].bill_type === null || this.fields[p].bill_type === '') {
                            $('#cell_bill_type_' + p).addClass(' error-corner');
                            addPopover('cell_bill_type_' + p, "Please select Bill type");
                            valid = false
                        }else{
                            $('#cell_bill_type_' + p).removeClass(' error-corner').popover('destroy')
                        }

                        if(this.fields[p].original_contract_amount === null || this.fields[p].original_contract_amount === '') {
                            $('#cell_original_contract_amount_' + p).addClass(' error-corner');
                            addPopover('cell_original_contract_amount_' + p, "Please enter original contract amount");
                            valid = false
                        }else {
                            $('#cell_original_contract_amount_' + p).removeClass(' error-corner').popover('destroy')
                        }
                        // this.fields[p].group = particularsArray[p].group
                    }
                    return valid;
                },
                saveParticulars(){

                    this.copyBillCodeGroups();
                    let data = JSON.stringify(this.fields);
                    var actionUrl = '/merchant/contract/updatesaveV6';
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: {
                            _token: '{{ csrf_token() }}',
                            form_data: JSON.stringify(data),
                            link: $('#contract_id').val(),
                            contract_amount : $('#particulartotal').val().replace(/,/g,'')
                        },
                        success: function(data) {
                            console.log(data)
                        }
                    })
                },
                async addNewRow() {
                    this.fields.push(row)
                    particularsArray.push(row)
                    let id = particularsArray.length - 1;
                    this.count = id;
                    const x = await this.wait(10);
                    await this.virtualSelect(id, 'bill_code', bill_codes)
                    this.virtualSelect(id, 'group', groups)
                },
                removeRow(id){
                    this.fields.splice(id, 1);
                    particularsArray.splice(id, 1);

                    total = 0;
                    this.fields.forEach(function(currentValue, index, arr) {
                        let amount = (currentValue.original_contract_amount) ? currentValue.original_contract_amount : 0
                        total = Number(total) + Number(getamt(amount));
                    });

                    document.getElementById('particulartotal').value = updateTextView1(total);
                    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

                    numrow = this.fields.length - 1;
                    this.count = numrow;
                },
                copyBillCodeGroups() {
                    for(let p=0; p < this.fields.length; p++){
                        this.fields[p].bill_code = particularsArray[p].bill_code;
                        this.fields[p].group = particularsArray[p].group;
                        this.fields[p].original_contract_amount = (this.fields[p].original_contract_amount !== null && this.fields[p].original_contract_amount !== '')? this.fields[p].original_contract_amount.replace(',','') : 0
                        this.fields[p].retainage_amount = (this.fields[p].retainage_amount !== null && this.fields[p].retainage_amount !== '')? this.fields[p].retainage_amount.replace(',','') : 0;
                    }
                }
            }
        }
    </script>
</div>

