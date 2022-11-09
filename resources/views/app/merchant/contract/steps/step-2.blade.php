<div>
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
        <div class="portlet-body form"  x-data="handle_particulars()" x-init="initializeParticulars" >
            <div class="row">
                <div class="col-md-6">
                    <h3 class="form-section">Add Particulars</h3>
                </div>
                <div class="col-md-6">
                    <a data-cy="add_particulars_btn" href="javascript:;"
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
                                    <td @if(isset($details['visible'])) x-on:click="field.show = true; " x-on:blur="field.show = false" @endif>
                                        @switch($column)
                                            @case('bill_code')
                                                <div :id="`{{$column}}${index}`" x-model="field.bill_code"></div>
                                                <input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
                                                <input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
                                                <input type="hidden" name="description[]"  x-value="field.description" :id="`description${index}`">
                                                <div class="text-center" style="display: none;">
                                                    <p :id="`description-hidden${index}`" x-text="field.description"></p>
                                                </div>
                                            @break

                                            @case('bill_type')
                                        @endswitch
                                    </td>
                                @endforeach
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

    <script>
        function initializeParticulars(){
            initializeBillCodes();
        }

        function  initializeBillCodes(){
            for(let v=0; v < particularsArray.length; v++){
                virtualSelect('bill_code' + v)
            }
        }
        function virtualSelect(id){
            VirtualSelect.init({
                ele: '#'+id,
                options: bill_codes,
                dropboxWrapper: 'body',
                allowNewOption: true,
            });

            $('#'+id).change(function (){
                if (!only_bill_codes.includes(this.value))
                    billIndex(0,0,0)
                console.log(this)
            });

        }
        var particularsArray = JSON.parse('{!! json_encode($particulars) !!}');
        var bill_codes = JSON.parse('{!! json_encode($bill_codes) !!}');
        var only_bill_codes = JSON.parse('{!! json_encode(array_column($bill_codes, 'value')) !!}');
        function handle_particulars(){
            return {
                fields : JSON.parse('{!! json_encode($particulars) !!}'),
                bill_code : null,
                bill_description : null,
                group_name : null,

                addNewBillCode(){
                    let label = this.bill_code + ' | ' + this.bill_description
                    bill_codes.push(
                        {label: label, value : this.bill_code, description : this.bill_description }
                    )
                    console.log(bill_codes)
                    initializeBillCodes();
                    return false;
                }
            }
        }
    </script>
</div>
