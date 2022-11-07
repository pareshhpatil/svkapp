<template x-if="field.bill_type=='Calculated'">
    <div>test
        <span :id="`lbl_original_contract_amount${index}`" x-text="field.{{$key}}"></span><br>
        <a :id="`add-calc${index}`" style=" padding-top: 5px;" x-show="!field.original_contract_amount" href="javascript:;" @click="OpenAddCaculated(field)">Add calculation</a>
        <a :id="`remove-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;" href="javascript:;" @click="RemoveCaculated(field)">Remove</a>
        <span :id="`pipe-calc${index}`" x-show="field.original_contract_amount" style="margin-left: 4px; color:#859494;"> | </span>
        <a :id="`edit-calc${index}`" x-show="field.original_contract_amount" style="padding-top:5px;padding-left:5px;" href="javascript:;" @click="EditCaculated(field)">Edit</a>
    </div>
    <span x-show="field.txt{{$key}}">
       <input :id="`{{$key}}${index}`" type="hidden" x-model="field.{{$key}}" value="" name="{{$key}}[]" style="width: 100%;" class="form-control input-sm ">
    </span>
</template>
<template x-if="field.bill_type!='Calculated'">
        <span x-show="field.txt{{$key}}">
           <input :id="`{{$key}}${index}`" @if($readonly==true) type="hidden" @else type="text" x-on:blur="field.txt{{$key}} = false;calc(field);" @endif x-model="field.{{$key}}" value="" name="{{$key}}[]" style="width: 100%;" class="form-control input-sm ">
        </span>
</template>

<input :id="`introw${index}`" type="hidden" :value="index" x-model="field.introw" name="pint[]">