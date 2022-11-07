<table class="table table-bordered table-hover" id="particular_table" wire:ignore>
    @if(!empty($particular_column))
        <thead>
        <tr>
            @foreach($particular_column as $k=>$v)
                @if($k!='description')
                    <th class="td-c @if($k=='bill_code') col-id-no @endif"
                        @if($k=='description' || $k=='bill_code' ) style="min-width: 100px;" @endif>
                                        <span class="popovers" data-placement="top" data-container="body"
                                              data-trigger="hover" data-content="{{$v}}"
                                              data-original-title=""> {{Helpers::stringShort($v)}}</span>
                    </th>
                @endif
            @endforeach
            <th class="td-c" style="width: 60px;">
                ?
            </th>
        </tr>
        </thead>
        <tbody>
        <template x-for="(field, index) in fields" :key="index">
            <tr>
                @php
                    $readonly_array=array('retainage_amount','bill_code_detail','group','bill_type','bill_code');
                    $number_array=array('original_contract_amount','retainage_percent');
                @endphp

                @foreach($particular_column as $key =>$column)

                    @php $readonly=false;  $number='type="text"'; @endphp
                    @if($key!='description')
                        @php
                            if(in_array($k, $readonly_array))
                                $readonly=true;

                            if(in_array($k, $number_array))
                            $number='type=number step=0.00';

                        @endphp
                        <td style="max-width: 100px;vertical-align: middle; @if($k=='retainage_amount') background-color:#f5f5f5; @endif" @if($readonly==false) x-on:click="field.txt{{$key}} = true; " x-on:blur="field.txt{{$key}} = false" @endif class="td-c onhover-border @if($k=='bill_code') col-id-no @endif">
                            @switch($key)
                                @case('bill_code')
                                    @include('livewire.contract.particulars-fields.bill_code')
                                    @break
                                @case('bill_type')
                                    @include('livewire.contract.particulars-fields.bill_type')
                                    @break
                                @case('group')
                                    @include('livewire.contract.particulars-fields.group')
                                    @break
                                @case('bill_code_detail')
                                    @include('livewire.contract.particulars-fields.bill_code_detail')
                                    @break
                                @case('original_contract_amount')

                            @endswitch

                            @include('livewire.contract.particulars-fields.'.$key)
                        </td>
                    @endif
                @endforeach

            </tr>
        </template>
        </tbody>
    @endif

</table>