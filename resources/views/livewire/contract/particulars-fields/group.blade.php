<select required style="width: 100%; min-width: 200px;" x-ref="`group${index}`" :id="`group${index}`" x-model="field.{{$key}}" name="{{$key}}[]" data-placeholder="Select group" class="form-control input-sm select2me groupSelect">
    <option value="">Select group</option>
    <template x-for="(group, groupindex) in groups" :key="groupindex">
        <option x-value="group" x-text="group"></option>
    </template>
    @if(!empty($pgroups))
        @foreach($pgroups as $g)
            <option value="{{$g}}">{{$g}}</option>
        @endforeach
    @endif

</select>