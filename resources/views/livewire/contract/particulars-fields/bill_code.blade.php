<select required style="width: 100%; min-width: 200px;" :id="`billcode${index}`"
        x-model="field.{{$key}}" name="{{$key}}[]" @update-csi-codes.window="bill_codes = this.bill_codes"
        data-placeholder="Select Bill Code" class="form-control input-sm select2me bill_code">
    <option value="">Select Code</option>
    <template x-for="(bill_code, billcodeindex) in bill_codes" :key="billcodeindex">
        <option x-value="bill_code.code" x-text="`${bill_code.code} | ${bill_code.title}`"></option>
    </template>
</select>
<input type="hidden" name="calculated_perc[]" x-model="field.calculated_perc" :id="`calculated_perc${index}`">
<input type="hidden" name="calculated_row[]" x-model="field.calculated_row" :id="`calculated_row${index}`">
<input type="hidden" name="description[]"  x-value="field.description" :id="`description${index}`">
<div class="text-center" style="display: none;">
    <p :id="`description-hidden${index}`" x-text="field.description"></p>
</div>

