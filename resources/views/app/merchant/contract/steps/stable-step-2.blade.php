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
            background:#eee;
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

        .vs-option {
            z-index: 99;
        }
        .vs-option1 {
        }
        .vscomp-value {
            font-size: 12px !important;
        }

        .dropdown-menu li > a {
            font-size: 12px !important;
            line-height: 18px;
        }

        .bill_code_td{
            /*text-align: left;*/
            width: auto;
        }

        table thead,
        table tfoot {
            position: sticky;
        }
        table thead {
            inset-block-start: 0; /* "top" */
        }
        table tfoot {
            inset-block-end: 0; /* "bottom" */
        }

        .tableFixHead {
            overflow: auto;
        }

        .headFootZIndex {
            z-index: 3;
        }

        .biggerHead {
            text-align: left !important;
            padding-left: 15px !important;
            vertical-align: middle !important;
            font-weight: 500 !important;
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
            <div class="table-scrollable tableFixHead">
                <table class="table table-bordered table-hover" id="particular_table">
                    @php $particular_column = \App\ContractParticular::$particular_column @endphp
                    @include('app.merchant.contract.steps.step-2-head')

                    <tbody>
                        <template x-for="(field, index) in fields" :key="index">
                            <tr>
                                @php $readonly_array=array('retainage_amount','bill_code_detail','group','bill_type','bill_code');
                                         $number_array=array('original_contract_amount','retainage_percent');
//                                  @endphp
                                @foreach($particular_column as $column => $details)
                                    @php $readonly=false; @endphp
                                    @php $number='type="text"'; @endphp
                                    @if($column != 'description')
                                        @if(in_array($column, $readonly_array))
                                            @php $readonly=true; @endphp
                                        @endif
                                        @if(in_array($column, $number_array))
                                            @php $number='type=number step=0.00'; @endphp
                                        @endif
                                    <td style="max-width: 100px;vertical-align: middle; @if($column=='retainage_amount') background-color:#f5f5f5; @endif" :id="`cell_{{$column}}_${field.introw}`" @if(!$readonly) x-on:click="field.show{{$column}} = true; @if($column == 'original_contract_amount' ) checkBillType(field); @endif" x-on:blur="field.show{{$column}} = false" @endif  class="td-c onhover-border @if($column=='bill_code') col-id-no bill_code_td @endif">
                                        @switch($column)
                                            @case('bill_code')
                                                <div :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" ></div>
                                                <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${field.introw}`">
                                                <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${field.introw}`">
                                                <input type="hidden" name="description[]"  x-value="field.description" :id="`description${field.introw}`">
                                                <div class="text-center" style="display: none;">
                                                    <p :id="`description-hidden${field.introw}`" x-text="field.description"></p>
                                                </div>
                                            @break

                                            @case('bill_type')
{{--                                                <div :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" name="{{$column}}[]"></div>--}}
{{--                                            <input type="hidden" :id="`checkBillType${field.introw}`" x-model="field.checkBillType" x-init="$watch(field.checkBillType, (value, oldValue) => console.log(value, oldValue))"/>--}}
                                                <select required style="width: 100%; min-width: 150px;font-size: 12px;" :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" name="{{$column}}[]" data-placeholder="Select.." class="form-control input-sm billTypeSelect bill_type" x-on:change="changeBillType(field, index)">
                                                    <option value="">Select..</option>
                                                    <option value="% Complete">% Complete</option>
                                                    <option value="Unit">Unit</option>
                                                    <option value="Calculated">Calculated</option>
                                                    <option value="Cost">Cost</option>
                                                </select>
                                            @break

                                            @case('group')
                                                <div :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}"></div>
                                            @break

                                            @case('bill_code_detail')
                                                <div :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" name="{{$column}}[]"></div>
                                                {{--<select required style="width: 100%; min-width: 200px;" :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" name="{{$column}}[]" data-placeholder="Select.." class="form-control  input-sm billcodedetail">
                                                    <option value="">Select..</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>--}}
                                            @break

                                            @case('original_contract_amount')
                                                <span x-show="field.show{{$column}}">
                                                        <input :id="`{{$column}}${field.introw}`" @if($readonly) type="hidden" @else type="text" x-on:blur="field.show{{$column}} = false;calc(field);saveParticulars(); reflectOriginalContractAmountChange(field, index)" @endif
                                                        @keyup="removeValidationError(`{{$column}}`, `${field.introw}`)"
                                                               x-model="field.{{$column}}"
                                                               value="" name="{{$column}}[]"
                                                               style="width: 100%;" class="form-control input-sm " x-show="field.showoriginal_contract_amount">
                                                    </span>
                                                <template x-if="field.bill_type!='Calculated'">
                                                    <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                                    {{--<span x-show="field.show{{$column}}">
                                                        <input :id="`{{$column}}${field.introw}`" @if($readonly) type="hidden" @else type="text" x-on:blur="field.show{{$column}} = false;calc(field);saveParticulars();" @endif
                                                        @keyup="removeValidationError(`{{$column}}`, `${field.introw}`)"
                                                               x-model="field.{{$column}}"
                                                               value="" name="{{$column}}[]"
                                                               style="width: 100%;" class="form-control input-sm ">
                                                    </span>--}}
                                                </template>

                                                <template x-if="field.bill_type=='Calculated'">
                                                    <div>
                                                        <span :id="`lbl_original_contract_amount${field.introw}`" x-text="field.{{$column}}"></span><br>
                                                        {{--<a :id="`add-calc${field.introw}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCalculated(field)">Add calculation</a>
                                                        <a :id="`remove-calc${field.introw}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="removeCalculated(field)">Remove</a>
                                                        <span :id="`pipe-calc${field.introw}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                                        <a :id="`edit-calc${field.introw}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCalculated(field)">Edit</a>--}}
                                                        <a :id="`add-calc${field.introw}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCaculated(field, index)">Add calculation</a>
                                                        <a :id="`remove-calc${field.introw}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field, index)">Remove</a>
                                                        <span :id="`pipe-calc${field.introw}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
                                                        <a :id="`edit-calc${field.introw}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field, index)">Edit</a>
                                                    </div>
                                                    {{--<span x-show="field.show{{$column}}">
                                                        <input :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" value="" name="{{$column}}[]" @if($readonly) type="hidden" @else  type="text" x-on:blur="field.show{{$column}} = false;calc(field);saveParticulars();" @endif style="width: 100%;" class="form-control input-sm ">
                                                    </span>--}}
                                                </template>


                                                <input :id="`introw${field.introw}`" type="hidden" :value="field.introw" x-model="field.introw" name="pint[]">
                                                <input :id="`index${field.introw}`" type="hidden" :value="field.introw" x-model="field.introw" name="rowindex[]">
                                            @break

                                            @case('retainage_amount')
                                                <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                            @break

                                            @case('cost_type')
                                                <div :id="`{{$column}}${field.introw}`" x-model="field.{{$column}}" name="{{$column}}[]"></div>
                                            @break

                                            @default
                                                <span x-show="! field.show{{$column}}" x-text="field.{{$column}}"> </span>
                                                <span x-show="field.show{{$column}}">
                                                    <input :id="`{{$column}}${field.introw}`" @if($readonly) type="hidden" @else type="text" x-on:blur="field.show{{$column}} = false;calc(field);" @endif x-model="field.{{$column}}" value="" name="{{$column}}[]" style="width: 100%;" class="form-control input-sm ">
                                                </span>
                                            @break
                                        @endswitch
                                    </td>
                                    @endif
                                @endforeach

                                <td class="td-c " style="vertical-align: middle;width: 60px;">
                                    <button type="button" class="btn btn-xs red" @click="removeRow(field, `${index}`)">&times;</button>
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
            @include('app.merchant.contract.add-calculation-modal-contract')
            @include('app.merchant.contract.add-bill-code-modal-contract')
        </div>
    </div>

    <div class="portlet light bordered">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a class="btn green" @click="back()">Back</a>
                        <button class="btn blue" type="submit" @click="next()">Preview contract</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    <script src="{{ asset('assets/admin/layout/scripts/contract-virtual-select.js') }}"></script>--}}
    <script>
        function initializeParticulars(){
            this.initializeDropdowns();
            this.calculateTotalRetainage();
             $('.tableFixHead').css('max-height', screen.height/2);
          /*  $('.tableFixHead').on('scroll', function(){
                $('#headerRow').css('z-index',3);
                $('#footerRow').css('z-index',3);
            });*/
        }

        var particularsArray = JSON.parse('{!! json_encode($particulars) !!}');
        var bill_codes = JSON.parse('{!! json_encode($bill_codes) !!}');
        var groups = JSON.parse('{!! json_encode($groups) !!}');
        var bill_types = [{'label' : '% Complete', 'value' : '% Complete'}, { 'label' : 'Unit', 'value' : 'Unit'}, { 'label' : 'Calculated', 'value' : 'Calculated'}];
        var bill_code_details = [{'label' : 'Yes', 'value' : 'Yes'}, { 'label' : 'No', 'value' : 'No'}];
        var only_bill_codes = JSON.parse('{!! json_encode(array_column($bill_codes, 'value')) !!}');

        var cost_types = JSON.parse('{!! json_encode($cost_types) !!}');
        var row = JSON.parse('{!! json_encode($row) !!}')
        var needValidationOnStep2 = {!! json_encode($needValidationOnStep2) !!};

        function addPopover(id, message){
            $('#'+id).attr({
                'data-placement' : 'right',
                'data-container' : "body",
                'data-trigger' : 'hover',
                'data-content' : message,
                // 'data-original-title'
            }).popover();
        }

        function updateBillCodeDropdowns(optionArray, newBillCode){
            let selectedId = $('#selectedBillCodeId').val();

            for(let v=0; v < particularsArray.length; v++){
                let currentField = particularsArray[v];
                let billCodeSelector = document.querySelector('#bill_code' + currentField.introw);

                if(selectedId === 'bill_code'+ currentField.introw ) {

                    billCodeSelector.setOptions(optionArray);
                    billCodeSelector.setValue(newBillCode.id);

                    only_bill_codes.push(newBillCode.id)

                    particularsArray[v].bill_code = newBillCode.code;
                    particularsArray[v].description = newBillCode.description;
                    $('#description' + currentField.introw).val( newBillCode.description )

                }
                else billCodeSelector.setOptions(optionArray, particularsArray[v].bill_code);

            }
            closeSidePanelBillCode();

            $('#new_bill_code').val(null);
            $('#new_bill_description').val(null);
            $('#selectedBillCodeId').val(null);
        }

        function handle_particulars(){
            return {
                fields : JSON.parse('{!! json_encode($particulars) !!}'),
                bill_code : null,
                bill_description : null,
                group_name : null,
                count : {!! count($particulars) -1 !!},
                project_code : '{{ $project->project_id }}',
                totalretainage : null,

                initializeDropdowns(){
                    for(let v=0; v < this.fields.length; v++){
                        //console.log('Initializing dropdowns' + this.fields[v].introw);

                        this.virtualSelect(this.fields[v].introw, 'bill_code', bill_codes, this.fields[v].bill_code, v)
                        this.virtualSelect(this.fields[v].introw, 'group', groups, this.fields[v].group, v)
                        this.virtualSelect(this.fields[v].introw, 'cost_type', cost_types, this.fields[v].cost_type, v)
                        this.virtualSelect(this.fields[v].introw, 'bill_code_detail', bill_code_details, this.fields[v].bill_code_detail, v)
                    }
                },
                virtualSelect(id, type, options, selectedValue, index){
                    let allowNewOption= true;
                    let search= true;
                    if(type === 'bill_code_detail' || type === 'cost_type'){
                         allowNewOption= false;
                         search= false;
                    }

                    if(type === 'bill_code') {
                        vs_class = 'vs-option'
                    }else {
                        vs_class = 'vs-option1'
                    }

                    VirtualSelect.init({
                        ele: '#'+type+id,
                        options: options,
                        dropboxWrapper: 'body',
                        allowNewOption: allowNewOption,
                        multiple:false,
                        selectedValue : selectedValue,
                        additionalClasses : vs_class,
                        searchPlaceholderText : 'Search or Add new',
                        search:search,
                    });

                    $('.vscomp-toggle-button').not('.form-control, .input-sm').each(function () {
                        $(this).addClass('form-control input-sm');
                    })

                    $('#'+type+id).change(function () {
                        
                        if(index === undefined || index === null) {
                            index = $('#index'+id).val();
                        }

                        if(type === 'bill_code') {
                            particularsArray[index].bill_code = this.value
                            let displayValue = this.getDisplayValue().split('|');
                            if(displayValue[1] !== undefined) {
                                $('#description'+id).val(displayValue[1].trim())
                                particularsArray[index].description = displayValue[1].trim();
                            }

                            if (this.value !== null && this.value !== '' && !only_bill_codes.includes( parseInt(this.value) )) {
                                only_bill_codes.push(this.value)
                                $('#new_bill_code').val(this.value)
                                $('#selectedBillCodeId').val(type + id)
                                billIndex(0, 0, 0)
                            }
                        }
                        if(type === 'group'){
                            if(!groups.includes(this.value) && this.value !== '') {
                                groups.push(this.value)
                                for (let g = 0; g < particularsArray.length; g++) {
                                    let groupSelector = document.querySelector('#group' + particularsArray[g].introw);

                                    if('group'+id === 'group' + particularsArray[g].introw)
                                        groupSelector.setOptions(groups, this.value);
                                    else
                                        groupSelector.setOptions( groups, particularsArray[g].group);
                                }
                            }
                            particularsArray[index].group = this.value
                        }

                        if(type === 'bill_type'){
                            particularsArray[index].bill_type = this.value
                            if(this.value === 'Calculated')
                                fields[index].bill_type = this.value
                        }

                        if(type === 'bill_code_detail'){
                            particularsArray[index].bill_code_detail = this.value
                        }

                        if(type === 'cost_type'){
                            particularsArray[index].cost_type = this.value
                        }
                    });

                    $('#'+type+id).on('beforeOpen',function () {
                        let dropboxContainer = $('#'+type+id).find('.vscomp-ele-wrapper').attr('aria-controls');
                        $('#'+dropboxContainer).css('z-index',4)
                    });
                    $('.tableFixHead').scroll(function(){
                        try {
                            document.querySelector('#'+type+id).close();
                        }catch (o) {}
                    });
                    // $('#'+type+id).on('afterOpen',function () {console.log('afterOpen');
                    //     elementsOverlap( type+id, type);
                    //     elementsOverlap( type+id, type);
                    //     // elementsOverlap( type+id, 'footerRow');
                    //     $('#headerRow').removeClass('headFootZIndex');
                    //     $('#footerRow').removeClass('headFootZIndex');
                    // });
                    //
                    // $('#'+type+id).on('afterClose',function () {console.log('afterClose');
                    //     $('#headerRow').addClass('headFootZIndex');
                    //     $('#footerRow').addClass('headFootZIndex');
                    // });

                } ,
                addNewBillCode(token){

                    let new_bill_code = $('#new_bill_code').val();
                    let new_bill_description = $('#new_bill_description').val();
                    let goAhead = true;
                    if(new_bill_code === '') {
                        $('#new_bill_code_message').html('Please enter bill code');
                        goAhead = false
                    }else{
                        $('#new_bill_code_message').html('');
                    }
                    if( new_bill_description === ''){
                        $('#new_bill_description_message').html('Please enter bill description');
                        goAhead = false
                    }else {
                        $('#new_bill_description_message').html('');
                    }

                    if(goAhead) {

                        let actionUrl = '/merchant/billcode/new';
                        $.ajax({
                            type: "POST",
                            url: actionUrl,
                            data: {
                                _token: token,
                                bill_code: new_bill_code,
                                bill_description: new_bill_description,
                                project_id: $('#project_id').val()
                            },
                            success: function (data) {
                                let label = new_bill_code + ' | ' + new_bill_description

                                bill_codes.push(
                                    { value: data.billCode.id, label: label, description: new_bill_description }
                                )
                                updateBillCodeDropdowns(bill_codes, data.billCode)

                            }
                        });

                        return false;
                    }
                },
                closeBillCodePanel() {
                    let selectedId = $('#selectedBillCodeId').val();
                    //console.log(selectedId);
                    var selectedBillCode = document.querySelector('#'+selectedId);
                    selectedBillCode.reset();

                    document.getElementById("panelWrapIdBillCode").style.boxShadow = "none";
                    document.getElementById("panelWrapIdBillCode").style.transform = "translateX(100%)";
                    $("#billcodeform").trigger("reset");
                    $('.page-sidebar-wrapper').css('pointer-events', 'auto');
                    $('.page-content-wrapper').css('pointer-events', 'auto');
                    return false;
                },
               /* updateBillCodeDropdowns(optionArray, selectedValue, selectedDescription){
                    let selectedId = $('#selectedBillCodeId').val();

                    for(let v=0; v < this.fields.length; v++){
                        let currentField = this.fields[v];
                        let billCodeSelector = document.querySelector('#bill_code' + currentField.introw);

                        if(selectedId === 'bill_code'+ currentField.introw ) {

                            billCodeSelector.setOptions(optionArray);
                            billCodeSelector.setValue(selectedValue);
                            // billCodeSelector.setDisplayValue(selectedValue + '|'+selectedDescription)
                            only_bill_codes.push($('#new_bill_code').val())
                            this.fields[v].bill_code = $('#new_bill_code').val();
                            particularsArray[v].bill_code = $('#new_bill_code').val();
                            particularsArray[v].description = selectedDescription;
                            $('#description' + currentField.introw).val(selectedDescription)

                        }
                        else billCodeSelector.setOptions(optionArray, particularsArray[v].bill_code);

                    }
                    closeSidePanelBillCode();

                    $('#new_bill_code').val(null);
                    $('#new_bill_description').val(null);
                    $('#selectedBillCodeId').val(null);
                },*/
                updateBillType(){

                },
                checkBillType(field){
                  if(field.bill_type === 'Calculated') field.showoriginal_contract_amount = false 
                    else  field.showoriginal_contract_amount = true
                },
                changeBillType(field,index) {
                    if(field.bill_type === 'Calculated') {
                        this.RemoveCaculated(field,index);
                    }
                },
                calc(field) {
                    try {
                        try {
                            this.groups.indexOf(field.group) === -1 ? this.groups.push(field.group) : console.log("This item already exists");

                        } catch (o) {}
                        try {
                            field.retainage_amount = updateTextView1(getamt(field.original_contract_amount) *  getamt(field.retainage_percent) / 100) ;
                        } catch (o) {}
                        try {
                            field.original_contract_amount = updateTextView1(getamt(field.original_contract_amount));
                        } catch (o) {}
                        try {
                            field.retainage_percent = getamt(field.retainage_percent);

                        } catch (o) {}
                        var total = 0;
                        var totalretainage = 0;

                        this.fields.forEach(function(currentValue, index, arr) {
                            let oct = Number(getamt(currentValue.original_contract_amount));
                            let retainage_amount = Number(getamt(currentValue.retainage_amount));

                            // try {
                            //     oct = Number(getamt(currentValue.original_contract_amount));
                            // } catch (o) {
                            //     oct = 0;
                            // }
                            // if (oct > 0) {
                                total = Number(total) + oct;
                                totalretainage = Number(totalretainage) + retainage_amount;
                            // }
                        });
                        this.totalretainage = roundAmount(totalretainage);
                        document.getElementById('particulartotal').value = updateTextView1(total);
                        document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);
                        document.getElementById('retainagetotaldiv').innerHTML = updateTextView1(totalretainage);
                    } catch (o) {
                        // alert(o.message);
                    }

                },
                calculateTotalRetainage(){
                    this.totalretainage = 0;
                    for(let r=0; r < this.fields.length; r++) {
                       // console.log(this.fields[r].retainage_amount);
                        if(this.fields[r]!==undefined) {
                            this.totalretainage = Number(this.totalretainage) + Number(roundAmount(getamt(this.fields[r].retainage_amount)));
                        }
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
                    for(let p=0; p < this.fields.length; p++){
                        if(this.fields[p] !== undefined) {                        
                            let introw = this.fields[p].introw;
                            if(this.fields[p].bill_code === null || this.fields[p].bill_code === '') {
                                $('#cell_bill_code_' + introw).addClass(' error-corner');
                                addPopover('cell_bill_code_' + introw, "Please select Bill code");
                                valid = false
                            }else{
                                $('#cell_bill_code_' + introw).removeClass(' error-corner').popover('destroy')
                                // this.fields[p].bill_code = this.fields[p].bill_code
                            }

                            if(this.fields[p].bill_type === null || this.fields[p].bill_type === '') {
                                $('#cell_bill_type_' + introw).addClass(' error-corner');
                                addPopover('cell_bill_type_' + introw, "Please select Bill type");
                                valid = false
                            }else{
                                $('#cell_bill_type_' + introw).removeClass(' error-corner').popover('destroy')
                            }

                            if(this.fields[p].cost_type === null || this.fields[p].cost_type === '') {
                                $('#cell_cost_type_' + introw).addClass(' error-corner');
                                addPopover('cell_bill_type_' + introw, "Please select Cost type");
                                valid = false
                            }else{
                                $('#cell_cost_type_' + introw).removeClass(' error-corner').popover('destroy')
                            }

                            if(this.fields[p].original_contract_amount === null || this.fields[p].original_contract_amount === '' || this.fields[p].original_contract_amount === 0) {
                                $('#cell_original_contract_amount_' + introw).addClass(' error-corner');
                                addPopover('cell_original_contract_amount_' + introw, "Please enter original contract amount");
                                valid = false
                            }else
                                $('#cell_original_contract_amount_' + introw).removeClass(' error-corner').popover('destroy')
                            // else {
                            //     if( parseInt(this.fields[p].original_contract_amount) > 0 )
                            //         $('#cell_original_contract_amount_' + p).removeClass(' error-corner').popover('destroy')
                            //     else {
                            //         $('#cell_original_contract_amount_' + p).addClass(' error-corner');
                            //         addPopover('cell_original_contract_amount_' + p, "Original contract amount should be greater than zero");
                            //         valid = false
                            //     }
                            // }
                            // this.fields[p].group = particularsArray[p].group
                            }
                    }
                    return valid;
                },
                saveParticulars(back=0, next=0){
                    this.copyBillCodeGroups();
                    formfields=this.fields.filter(Boolean);
                    let data = JSON.stringify(formfields);
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
                            if(back === 1)
                                window.location = '{{ route(Route::getCurrentRoute()->getName(), ['step' => 1, 'contract_id' => $contract_id]) }}';
                            if(next === 1)
                                window.location = '{{ route(Route::getCurrentRoute()->getName(), ['step' => 3, 'contract_id' => $contract_id]) }}'
                        }
                    })
                },
                next(){
                    if(this.validateParticulars()) this.saveParticulars(0,1);
                },
                back(){
                    if(needValidationOnStep2) {
                        if (this.validateParticulars())
                            this.saveParticulars(1, 0);
                    }else this.saveParticulars(1, 0);
                },
                
                async addNewRow() {
                    // int = this.fields.length
                    document.getElementById('loader').style.display = 'block';
                    let lastRow = this.fields[this.fields.length-1]

                    let int = lastRow.introw + 1

                    this.fields.push({
                        'bill_code' : null,
                        'calculated_perc' : null,
                        'calculated_row' : null,
                        'description' : null,
                        'introw' : int,
                        'pint' : int,
                        'bill_type' : null,
                        'original_contract_amount' : null,
                        'retainage_percent' : null,
                        'retainage_amount' : null,
                        'project' : this.project_code,
                        'project_code' : this.project_code,
                        'cost_code' : null,
                        'cost_type' : null,
                        'group' : null,
                        'bill_code_detail' : 'Yes',
                        'show':false
                    });
                    particularsArray.push({
                        'bill_code' : null,
                        'calculated_perc' : null,
                        'calculated_row' : null,
                        'description' : null,
                        'introw' : int,
                        'pint' : int,
                        'bill_type' : null,
                        'original_contract_amount' : null,
                        'retainage_percent' : null,
                        'retainage_amount' : null,
                        'project' : this.project_code,
                        'project_code' : this.project_code,
                        'cost_code' : null,
                        'cost_type' : null,
                        'group' : null,
                        'bill_code_detail' : 'Yes',
                        'show':false
                    });

                    let id = particularsArray.length - 1;
                    this.count = id;
                    console.log(int);
                    const x = await this.wait(10);
                    this.virtualSelect(int, 'bill_code', bill_codes,null)
                    this.virtualSelect(int, 'group', groups, null)
                    this.virtualSelect(int, 'cost_type', cost_types, null)
                    this.virtualSelect(int, 'bill_code_detail', bill_code_details,'Yes', null)
                    
                    setTimeout(function () {
                        document.getElementById('loader').style.display = 'none';
                    }, 1000);
                },
                removeRow(field, index){
                    //alert(index);
                    let id = field.introw;
                    //this.fields.splice(index, 1);
                    delete this.fields[index];
                    delete particularsArray[index];
                    //particularsArray.splice(index, 1);

                    let total = 0;
                    for( let f =0; f < this.fields.length; f++){
                        let currentValue = this.fields[f];
                        if(currentValue !== undefined) {
                            let amount = (currentValue.original_contract_amount) ? currentValue.original_contract_amount : 0
                            total = Number(total) + Number(getamt(amount));
                            let calculatedRowValue = $('#calculated_row'+currentValue.introw).val()
                            if(calculatedRowValue !== '') {
                                var rowsIncludedInCalculation = JSON.parse(calculatedRowValue);

                                if(rowsIncludedInCalculation.includes(id)) {
                                    const index = rowsIncludedInCalculation.indexOf(id);
                                    if (index > -1) {
                                        rowsIncludedInCalculation.splice(index, 1);
                                        let rowsArray = JSON.stringify(rowsIncludedInCalculation);

                                        $('#calculated_row'+currentValue.introw).val( rowsArray );

                                        currentValue.calculated_row = rowsArray;
                                        this.reCalculateCalculatedRowValue(currentValue);
                                    }
                                }

                            }
                            this.calc(currentValue);
                            // this.fields[index].introw = index;
                        }
                        //this.virtualSelect(this.fields[f].introw, 'bill_code', bill_codes, this.fields[f].bill_code, f);
                    }

                    document.getElementById('particulartotal').value = updateTextView1(total);
                    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

                    numrow = this.fields.length - 1;
                    this.count = numrow;
                },
                reflectOriginalContractAmountChange(field, index){
                    let id = field.introw;

                    let total = 0;
                    for( let f =0; f < this.fields.length; f++){
                        let currentValue = this.fields[f];
                        if(currentValue !== undefined) {
                            let amount = (currentValue.original_contract_amount) ? currentValue.original_contract_amount : 0
                            total = Number(total) + Number(getamt(amount));
                            let calculatedRowValue = $('#calculated_row'+currentValue.introw).val()
                            if(calculatedRowValue !== '') {
                                let rowsIncludedInCalculation = JSON.parse(calculatedRowValue);

                                if(rowsIncludedInCalculation.includes(id)) {
                                    const index = rowsIncludedInCalculation.indexOf(id);
                                    if (index > -1) {
                                        this.reCalculateCalculatedRowValue(currentValue);
                                    }
                                }
                            }
                            this.calc(currentValue);
                        }
                        // this.fields[index].introw = index;
                    }
                    document.getElementById('particulartotal').value = updateTextView1(total);
                    document.getElementById('particulartotaldiv').innerHTML = updateTextView1(total);

                },
                reCalculateCalculatedRowValue(field){

                    let rowsIncludedInCalculation = JSON.parse($('#calculated_row'+field.introw).val());
                    let total = 0;
                    for(let r=0; r < rowsIncludedInCalculation.length; r++){
                        total += getamt( this.fields[r].original_contract_amount )
                    }
                    let calculatedAmt = (total * parseFloat($('#calculated_perc' + field.introw).val()) / 100);
                    console.log(calculatedAmt);

                    field.original_contract_amount = getamt(calculatedAmt);
                    $('#lbl_original_contract_amount' + field.introw).html(calculatedAmt);
                    $('#original_contract_amount'+field.introw).val(calculatedAmt)

                },
                copyBillCodeGroups() {
                    for(let p=0; p < this.fields.length; p++){
                        if(particularsArray[p] !== undefined) {
                            this.fields[p].bill_code = particularsArray[p].bill_code;
                            this.fields[p].description = particularsArray[p].description;
                            this.fields[p].group = particularsArray[p].group;
                            this.fields[p].cost_type = particularsArray[p].cost_type;
                            this.fields[p].bill_code_detail = particularsArray[p].bill_code_detail;

                            let oriContractAmt = this.fields[p].original_contract_amount;
                            this.fields[p].original_contract_amount =  (oriContractAmt !== null && oriContractAmt !== '')? getamt(oriContractAmt) : 0;//  (oriContractAmt !== null && oriContractAmt !== '')? ( (typeof oriContractAmt === 'number') ? oriContractAmt : oriContractAmt.replace(',','')) : 0

                            let retainAmt = this.fields[p].retainage_amount;
                            this.fields[p].retainage_amount = (retainAmt !== null && retainAmt !== '')? getamt(retainAmt) : 0;// (retainAmt !== null && retainAmt !== '')? ( (typeof retainAmt == 'number') ? retainAmt : retainAmt.replace(',','')) : 0;

                            this.fields[p].showoriginal_contract_amount = false;
                            this.fields[p].showretainage_percent = false;
                            this.fields[p].showretainage_amount = false;
                            this.fields[p].showcost_code = false;
                            this.fields[p].showcost_type = false;
                            this.fields[p].showproject = false;
                        }
                    }
                },

                setAOriginalContractAmount() {
                    let valid = true;
                    if ($('input[name^=calc-checkbox]:checked').length <= 0) {
                        $('#calc_checkbox_error').html('Please select atleast one particular');
                        valid = false;
                    }else
                        $('#calc_checkbox_error').html('');

                    if($('#calc_perc').val() === '' || $('#calc_perc').val() === null || $('#calc_perc').val() === '0' || $('#calc_perc').val() < 0 ) {
                        $('#calc_perc_error').html('Please enter percentage');
                        valid = false
                    }else
                        $('#calc_perc_error').html('');

                    if(valid) {
                        let selected_field_int =  $('#selected_field_int').val();
                        let selected_field_index = $('#selected_field_index').val();

                        let calc_amount = document.getElementById("calc_amount").value;

                        try {
                            this.fields[selected_field_index].original_contract_amount = calc_amount;
                            this.fields[selected_field_index].showoriginal_contract_amount = false;
                        } catch (o) {
                        }

                        this.setCalculatedOriginalContractAmount(selected_field_int, selected_field_index);
                        // this.calc(this.fields[selected_field_index]);
                        this.reflectOriginalContractAmountChange(this.fields[selected_field_index], selected_field_index);
                        this.calculateTotalRetainage();
                        this.fields[selected_field_index].calculated_perc = document.getElementById('calculated_perc' + selected_field_int).value;
                        this.fields[selected_field_index].calculated_row = document.getElementById('calculated_row' + selected_field_int).value;
                        this.saveParticulars();
                    }

                },
                setCalculatedOriginalContractAmount(introw, index) {

                    try {
                        document.getElementById("original_contract_amount" + introw).value = updateTextView1(getamt(document.getElementById("calc_amount").value));
                    } catch (o) {

                    }

                    document.getElementById("lbl_original_contract_amount" + introw).innerHTML = updateTextView1(getamt(document.getElementById("calc_amount").value));

                    try {
                        document.getElementById("project" + introw).readOnly = true;
                        document.getElementById("cost_code" + introw).readOnly = true;
                        document.getElementById("cost_type" + introw).readOnly = true;

                    } catch (o) {

                    }
                    document.getElementById("add-calc" + introw).style.display = 'none';
                    document.getElementById("remove-calc" + introw).style.display = 'inline-block';
                    document.getElementById("edit-calc" + introw).style.display = 'inline-block';
                    document.getElementById("edit-calc" + introw).innerHTML = 'Edit';
                    document.getElementById("pipe-calc" + introw).style.display = 'inline-block';
                    document.getElementById("edit-calc" + introw).innerHTML = 'Edit';
                    let calcRowArray = [];
                    $('input[name="calc-pint[]"]').each(function (indx, arr) {
                        let int = $(this).val();

                        var checkBox = document.getElementById("calc" + int);
                        if (checkBox.checked == true) {
                            calcRowArray.push(parseInt(int))
                        }
                    });
                    calcRowArray = JSON.stringify(calcRowArray);
                    console.log(calcRowArray);
                    // console.log(calcRowInt);
                    document.getElementById("calculated_row" + introw).value = calcRowArray;
                    document.getElementById("calculated_perc" + introw).value = parseFloat(document.getElementById("calc_perc").value).toFixed(2);
                    closeSidePanelcalc()
                    clearCalcTable();
                    calculateRetainage();
                    try {
                        document.getElementById("exicon" + introw).style.display = 'inline-block';
                    } catch (o) { }
                    try {
                        if (invoice_construction == true) {
                            calculateConstruction();
                        } else {
                            calculatedRowSummaryContract()
                        }
                    } catch (o) { }
                    return false;
                },
                OpenAddCaculated(field, index) {
                    calcRowInt=field.introw;
                    field.showoriginal_contract_amount = false;
                    //document.getElementById('original_contract_amount' + field.introw).type = 'hidden';
                    this.selected_field_int = field.introw;
                    document.getElementById('selected_field_int').value = field.introw;
                    document.getElementById('selected_field_index').value = index;
                    this.OpenAddCaculatedRow(field.introw, index);
                },
                RemoveCaculated(field, index) {
                    this.fields[index].original_contract_amount = 0;
                    document.getElementById('lbl_original_contract_amount' + field.introw).innerHTML = '';
                    this.fields[field.introw].showoriginal_contract_amount = false;
                    RemoveCaculatedRow(field.introw);
                    this.calc(field)
                },
                EditCaculated(field, index) {
                    document.getElementById('selected_field_int').value = field.introw;
                    document.getElementById('selected_field_index').value = index;
                    this.editCaculatedRow(field.introw, index);
                },
                addRowinCalcTable(ind, index) {
                    clearCalcTable();
                    calcRowInt = ind
                    $('#selected_field_index' ).val(index);
                    var mainDiv = document.getElementById('new_particular1');

                    for(let i = 0; i < particularsArray.length; i++) {
                        let newDiv = document.createElement('tr');
                        let row = '';
                        let particular = particularsArray[i];
                        if(particular !== undefined) {
                            if (ind !== particular.introw) {
                            let oca = document.getElementById('original_contract_amount' + particular.introw).value;
                            let amt = getamt(oca);
                            let displayValue = document.querySelector('#bill_code' + particular.introw ).getDisplayValue().split('|');
                            let bill_code = displayValue[0];
                            var discription = displayValue[1];

                            row = row + '<td class="td-c">' +
                                '<input type="hidden" name="calc-pint[]" value="' + particular.introw + '" id="calc-pint' + particular.introw + '">' +
                                '<input type="checkbox" name="calc-checkbox[]" value="' + particular.introw + '" id="calc' + particular.introw + '" onclick="inputCalcClicked(' + particular.introw + ',' + getamt(document.getElementById('original_contract_amount' + particular.introw).value) + ')"></td>' +
                                '<td class="td-c">' + bill_code + '</td><td class="td-c">' + discription + '</td>' +
                                '<td class="td-c">$' + document.getElementById('original_contract_amount' + particular.introw).value + '</td>'


                            }
                            newDiv.innerHTML = row;
                            mainDiv.appendChild(newDiv);
                        }
                        
                    }

                    /*$('input[name="pint[]"]').each(function (indx, arr) {
                        var newDiv = document.createElement('tr');
                        row = '';
                        int = ($(this).val() === null || $(this).val() == '') ? 0 : $(this).val();

                        bint = Number(int) + 2;
                        if (ind != int) {

                            oca = document.getElementById('original_contract_amount' + int).value;
                            amt = getamt(oca);
                            try {
                                var bill_code = particularsArray[indx].bill_code; //document.getElementById('select2-bill_code' + int + '-container').innerHTML;
                            } catch (o) {
                                var bill_code = particularsArray[indx].bill_code;//document.getElementById('select2-billcode' + int + '-container').innerHTML;
                            }
                            var discription = document.getElementById('description' + int).value;
                            //bill_code = document.getElementById('bill_code' + bint).value;
                            // if (amt > 0) {
                                row = row + '<td class="td-c">' +
                                    '<input type="hidden" name="calc-pint[]" value="' + int + '" id="calc-pint' + int + '">' +
                                    '<input type="checkbox" name="calc-checkbox[]" value="' + int + '" id="calc' + int + '" onclick="inputCalcClicked(' + int + ',' + getamt(document.getElementById('original_contract_amount' + int).value) + ')"></td>' +
                                    '<td class="td-c">' + bill_code + '</td><td class="td-c">' + discription + '</td>' +
                                    '<td class="td-c">$' + document.getElementById('original_contract_amount' + int).value + '</td>'
                            // }
                        }
                        newDiv.innerHTML = row;
                        mainDiv.appendChild(newDiv);

                    });*/
                },
                OpenAddCaculatedRow(row, index) {
                    this.proindexContract(row, row, index)

                },
                proindexContract(ind, select_id, index) {
                    product_index = ind;
                    currect_select_dropdwn_id = select_id;
                    document.getElementById("panelWrapIdcalc").style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    document.getElementById("panelWrapIdcalc").style.transform = "translateX(0%)";
                    $('.page-sidebar-wrapper').css('pointer-events', 'none');
                    $('.page-content-wrapper').css('pointer-events', 'none');
                    $('#panelWrapIdcalc').css('pointer-events', 'auto');
                    this.addRowinCalcTable(ind, index)

                },
                editCaculatedRow(row, index) {
                    this.OpenAddCaculatedRow(row, index)
                    document.getElementById("calc_perc").value = document.getElementById("calculated_perc" + row).value
                    let calc_json = document.getElementById("calculated_row" + row).value;
                    calc_json = JSON.parse(calc_json)

                    for (const element of calc_json) {
                        amount_value = getamt(document.getElementById("original_contract_amount" + element).value)
                        document.getElementById("calc" + element).checked = true
                        inputCalcClicked(element, amount_value)
                    }

                }
            }
        }
    </script>
</div>

