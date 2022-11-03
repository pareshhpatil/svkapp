<template :key="zeroth">
    <tr>
        <td>
            <select required style="width: 100%; min-width: 200px;" @update-csi-codes.window="bill_codes = this.bill_codes" onchange="billCode2()" :id="`billcode${index+2}`" x-model="field.{{$k}}" value="fee" name="{{$k}}[]" data-placeholder="Select Bill Code" class="form-control input-sm select2Bill productselect">
                <option value="">Select Code</option>
                @foreach($csi_codes as $csi_code)
                    <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
                @endforeach
            </select>
        </td>
        <td><select required style="width: 100%; min-width: 200px;" @update-csi-codes.window="bill_codes = this.bill_codes" onchange="billCode2()" :id="`billcode${index+2}`" x-model="field.{{$k}}" value="fee" name="{{$k}}[]" data-placeholder="Select Bill Code" class="form-control input-sm select2Bill productselect">
                <option value="">Select Code</option>
                <option></option>
            </select></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</template>