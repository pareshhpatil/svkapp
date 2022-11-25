<select required style="width: 100%; min-width: 15  0px;font-size: 12px;" :id="`billtype${index}`" x-model="field.{{$key}}" name="{{$key}}[]" data-placeholder="Select.."
        class="form-control select2me billTypeSelect">
    <option value="">Select..</option>
    <option value="% Complete">% Complete</option>
    <option value="Unit">Unit</option>
    <option value="Calculated">Calculated</option>
</select>