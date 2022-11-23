<select required style="width: 100%; min-width: 200px;" :id="`billcodedetail${index}`" x-model="field.{{$key}}" name="{{$key}}[]" data-placeholder="Select.." class="form-control select2me billcodedetail">
    <option value="">Select..</option>
    <option value="Yes">Yes</option>
    <option value="No">No</option>
</select>