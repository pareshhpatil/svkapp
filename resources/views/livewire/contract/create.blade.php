<div class="col-md-12">
{{--    @include('layouts.alerts')--}}

    <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
        <p>Error! Select a project before trying to add a new row
        </p>
    </div>
    <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
        <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
        </p>
    </div>
    <form  x-data="handler_create()" x-init="initSelect2" action="/merchant/contract/saveV5" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
        @csrf
        <div x-show="step==1">
            @include('livewire.contract.step-1')
{{--            <livewire:contract.information :contract_id="$contract_id" />--}}
        </div>
        <div x-show="step==2">
            @include('livewire.contract.step-2')
{{--            <livewire:contract.particulars :particular="$particulars" :project_id="$project_id" />--}}
        </div>
        <div x-show="step==3">
            @include('livewire.contract.step-3')
{{--            <livewire:contract.preview />--}}
        </div>

        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="/merchant/contract/list" class="btn green">Cancel</a>
                            <a class="btn green" x-show="step!=1" @click="step=step-1">Back</a>
                            <a class="btn blue" @click="goToParticulars()" x-show="step==1">Add particulars</a>
                            <a class="btn blue" x-show="step==2" @click="goToPreview()">Preview contract</a>
                            <a class="btn blue" x-show="step==3" @click="saveContract()">Save contract</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
       /* $(document).ready(function () {
            particularsDropdowns(0, this.fields);

        });*/
       csi_codes = [];
       var particularray = JSON.parse('{!! json_encode($particulars) !!}');
       var previewArray = [];

        window.addEventListener('update-csi-codes', event => {
            this.bill_codes = event.detail.csi_codes
            csi_codes = event.detail.csi_codes
            // console.log(this.bill_codes);
            $('#project_id').val(event.detail.project_id)
            $('#_project_id').val(event.detail.project_id)
            this.project = event.detail.project_name;
            // document.getElementById('_project_id').value = event.detail.project_id
            //console.log(csi_codes);

        })

        function initSelect2(){

            if(particularray.length > 1) {
                for (let p =0; p < particularray.length; p++)
                this.particularsDropdowns(p);
            }else {
                this.particularsDropdowns(0);
            }
        }

       /*$('#cell_bill_code_' + p).addClass(' error-corner');
       this.goAhead = false;
       }else $('#cell_bill_code_' + p).removeClass(' error-corner');

       if(particularray[p].bill_type === null || particularray[p].bill_type === '') {
           $('#cell_bill_type_' + p).addClass(' error-corner');
           this.goAhead = false;
       }else $('#cell_bill_type_' + p).removeClass(' error-corner');
       if(particularray[p].original_contract_amount === null || particularray[p].original_contract_amount === '') {
           $('#cell_original_contract_amount_' + p).addClass(' error-corner');*/

        function addPopover(id, message){
            $('#'+id).attr({
                'data-placement' : 'right',
                'data-container' : "body",
                'data-trigger' : 'hover',
                'data-content' : message,
                // 'data-original-title'
            }).popover();
        }

        function handler_create() {

            {{--console.log('{{ json_encode($particulars) }}');--}}
            return {
                step: {{ $step }},
                contract_code: '{{$contract_code}}',
                project_id : '{{$project_id}}',
                bill_date: '{{$bill_date}}',
                contract_date: '{{$contract_date}}',
                goAhead: true,
                fields : JSON.parse('{!! json_encode($particulars) !!}'),
                bill_codes : JSON.parse('{!! json_encode($csi_codes) !!}'),
                groups : JSON.parse('{!! json_encode($groups) !!}'),
                test : null,
                count: particularray.length - 1,
                group_name: '',
                group_show: false,
                bill_code_name: '',
                bill_code_description: '',
                selected_group: [],
                panel: true,
                billcodepanel: false,
                new_codes: [],
                calculations: [],
                bcd: ['Yes', 'No'],
                bill_types: ['% Complete', 'Unit', 'Calculated'],
                grand_total: 0,
                project:null,
                goToParticulars() {
                    this.resetFlags();
                    /*if (this.project_id === null || this.project_id === '') {
                        this.goAhead = false
                    }*/
                    if (this.contract_code === null || this.contract_code === '') {
                        this.goAhead = false
                    }
                    if (document.getElementById('project_id').value === '' || document.getElementById('project_id').value === null) {
                        this.goAhead = false
                    }
                    if (this.contract_date === null || this.contract_date === '') {
                        this.goAhead = false
                    }

                    if (this.bill_date === null || this.bill_date === '') {
                        this.goAhead = false
                    }
                    this.goToNextStep();
                    @this.storeContract();
                    $('input[name="bill_code[]"]').each(function(int, arr) {
                        // select2Dropdowns(int);
                        this.particularsDropdowns(int);
                    });
                },
                goToPreview() {

                    this.resetFlags();
                    this.validateParticulars();
                    if(this.goAhead) {
                        this.saveParticulars();
                    }

                    this.goToNextStep();
                    previewv5(previewArray);
                },
                saveParticulars(){

                    var data = this.getParticularsInfo();
                    /*Hack - need to change in future */
                    /*for (let d =0 ; d< data.length; d++){
                        data[d].original_contract_amount = data[d].original_contract_amount.replace(',','')
                        data[d].retainage_percent = data[d].retainage_percent.replace(',','')
                        data[d].retainage_amount = data[d].retainage_amount.replace(',','')
                    }*/
                    /*Hack - need to change in future */

                    if (data.length == 0) {
                        $('#paticulars_error').show()
                        return false;
                    }

                    var actionUrl = '/merchant/contract/updatesaveV5';
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: {
                            _token: '{{ csrf_token() }}',
                            form_data: JSON.stringify(data),
                            link: $('#contract_id').val(),
                            totalcost: $('#particulartotal').val(),
                            project_id: $('#project_id').val(),
                            contract_code: $('#contract_number').val(),
                            billing_frequency: $('#billing_frequency').val(),
                            contract_date: $('#contract_date').val(),
                            bill_date: $('#billing_date').val()
                        },
                        success: function(data) {
                            console.log(111)
                        }
                    })
                },
                saveContract() {
                     $('#frm_expense').submit();
                },
                resetFlags() {
                    this.goAhead = true;
                    $('#paticulars_error').hide()
                },
                goToNextStep() {
                    console.log(this.goAhead, this.step);
                    if (this.goAhead) this.step++;
                },
                particularsDropdowns(id = null) {

                     try {
                         $('#billcode' + id).select2({
                             insertTag: function (data, tag) {

                             }
                         }).on('select2:open', function (e) {
                             pind = $(this).index();
                             if (document.getElementById('prolist' + pind)) { } else {
                                 $('.select2-results').append('<div class="wrapper" id="prolist' + pind + '" > <a class="clicker" onclick="billIndex(' + id + ',' + id + ',0);">Add new bill code</a> </div>');
                             }
                         }).on('select2:select', function (e){
                             let data = $('#billcode' + id).select2('val');
                             let dataArray = data.split('|')
                             particularray[id]['bill_code'] = dataArray[0].trim();
                             particularray[id]['bill_code_text'] = data;

                             if(data.length > 0) {
                                 $('#cell_bill_code_' + id).removeClass(' error-corner').popover('destroy');

                             }
                         });
                     } catch (o) { }


                     try {
                         $('#group' + id).select2({
                             insertTag: function (data, tag) {

                             }
                         }).on('select2:open', function (e) {
                             pind = $(this).index();
                             if (document.getElementById('grouplist' + pind)) { } else {
                                 $('.select2-results').append('<div class="wrapper" id="grouplist' + pind + '" > <a class="clicker" onclick="setgroup(' + id + ');">Add new group</a> </div>');
                             }
                         }).on('select2:select', function (e){
                             var data = $('#group' + id).select2('val');
                             particularray[id]['group'] = data;
                         }).val(particularray[id].group).trigger('change');
                     } catch (o) { }
                 },
                getParticularsInfo(){
                    previewArray = particularray;
                     for(let i=0;i<particularray.length;i++) {
                         console.log(this.fields[i]);
                         particularray[i].bill_type = this.fields[i].bill_type;
                         particularray[i].original_contract_amount = (this.fields[i].original_contract_amount === '' || this.fields[i].original_contract_amount === null)?0 : this.fields[i].original_contract_amount;
                         particularray[i].retainage_percent = (this.fields[i].retainage_percent === '' || this.fields[i].retainage_percent === null || this.fields[i].retainage_percent === undefined)?0 : this.fields[i].retainage_percent;
                         particularray[i].retainage_amount = (this.fields[i].retainage_amount === '' || this.fields[i].retainage_amount === null || this.fields[i].retainage_amount === undefined)?0 : this.fields[i].retainage_amount;
                         particularray[i].project = this.fields[i].project;
                         particularray[i].project_code = this.fields[i].project;
                         particularray[i].cost_code = (this.fields[i].cost_code === undefined) ? '' : this.fields[i].cost_code ;
                         particularray[i].cost_type = (this.fields[i].cost_type === undefined) ? '' : this.fields[i].cost_type ;
                         particularray[i].calculated_perc = this.fields[i].calculated_perc;
                         particularray[i].calculated_row = this.fields[i].calculated_row;
                         particularray[i].bill_code_detail = (this.fields[i].bill_code_detail === '' || this.fields[i].bill_code_detail === null)? 'NO' : this.fields[i].bill_code_detail ;
                     }
                     return particularray
                 },
                removeField(id) {
                    this.fields.splice(id, 1);
                    particularray.splice(id, 1);

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
                validateParticulars(){

                    for(let p=0; p < particularray.length;p++){
                        console.log(particularray);
                        if(particularray[p].bill_code === null || particularray[p].bill_code === '') {
                            $('#cell_bill_code_' + p).addClass(' error-corner');
                            addPopover('cell_bill_code_' + p, "Please select Bill code");
                            this.goAhead = false;
                        }else{
                            $('#cell_bill_code_' + p).removeClass(' error-corner').popover('destroy')
                        }

                        if(this.fields[p].bill_type === null || this.fields[p].bill_type === '') {
                            $('#cell_bill_type_' + p).addClass(' error-corner');
                            addPopover('cell_bill_type_' + p, "Please select Bill type");
                            this.goAhead = false;
                        }else{
                            $('#cell_bill_type_' + p).removeClass(' error-corner').popover('destroy')
                        }

                        if(particularray[p].original_contract_amount === null || particularray[p].original_contract_amount === '') {
                            $('#cell_original_contract_amount_' + p).addClass(' error-corner');
                            addPopover('cell_original_contract_amount_' + p, "Please enter original contract amount");
                            this.goAhead = false;
                        }else {
                            $('#cell_original_contract_amount_' + p).removeClass(' error-corner').popover('destroy')
                        }
                    }
                },
                removeValidationError(fieldName, id){
                    if($('#'+fieldName+id).val() !== null && $('#'+fieldName+id).val() !== '') {
                        $('#cell_'+ fieldName+'_'+id).removeClass(' error-corner').popover('destroy')
                    }
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
                        particularray[group_index].group = this.group_name
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
                    console.log(field.introw);
                    this.selected_field_int = field.introw;console.log(document.getElementById('selected_field_int'));
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
                            bill_code_detail: '',
                            project: project_code2
                        });
                        particularray.push({
                            introw: int,
                            bill_code: '',
                            bill_type: '',
                            group: '',
                            bill_code_detail: '',
                            project: project_code2
                        })
                        const x = await this.wait(10);
                        numrow = this.fields.length - 1;

                        this.count = numrow;

                        this.particularsDropdowns(numrow)
                        // this.select2Dropdown(numrow);
                        // @this.set('particulars', this.fields);


                    } else {
                        document.getElementById('perror').style.display = 'block';
                    }
                }

            }
        }
    </script>
</div>
