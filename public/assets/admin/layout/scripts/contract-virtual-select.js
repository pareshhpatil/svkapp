const contractVirtualSelect = {

    initializeDropdowns(){
        for(let v=0; v < this.fields.length; v++){
            this.virtualSelect(v, 'bill_code', bill_codes, this.fields[v].bill_code)
            this.virtualSelect(v, 'group', groups, this.fields[v].group)
            // this.virtualSelect(v, 'bill_type', bill_types, this.fields[v].bill_type)
            this.virtualSelect(v, 'bill_code_detail', bill_code_details, this.fields[v].bill_code_detail)
        }
    },
    virtualSelect(id, type, options, selectedValue){
        VirtualSelect.init({
            ele: '#'+type+id,
            options: options,
            dropboxWrapper: 'body',
            allowNewOption: true,
            multiple:false,
            selectedValue : selectedValue,
            additionalClasses : 'vs-option'
        });

        $('.vscomp-toggle-button').not('.form-control, .input-sm').each(function () {
            $(this).addClass('form-control input-sm');
        })

        $('#'+type+id).change(function () {
            if(type === 'bill_code') {
                particularsArray[id].bill_code = this.value
                let displayValue = this.getDisplayValue().split('|');
                if(displayValue[1] !== undefined) {
                    $('#description'+id).val(displayValue[1].trim())
                    particularsArray[id].description = displayValue[1].trim();
                }

                if (this.value !== null && this.value !== '' && !only_bill_codes.includes(this.value)) {
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
                        let groupSelector = document.querySelector('#group' + g);
                        console.log('group'+id, 'group'+g)
                        if('group'+id === 'group'+g)
                            groupSelector.setOptions(groups, this.value);
                        else
                            groupSelector.setOptions( groups, particularsArray[g].group);
                    }
                }
                particularsArray[id].group = this.value
            }

            if(type === 'bill_type'){
                console.log(fields);
                particularsArray[id].bill_type = this.value
                if(this.value === 'Calculated')
                    fields[id].bill_type = this.value
            }

            if(type === 'bill_code_detail'){
                particularsArray[id].bill_code_detail = this.value
            }
        });

    },
}