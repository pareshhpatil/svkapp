<div>
    <div class="col-md-6">
        <div class="form-group ">
            <div class="col-md-8 no-padding">
                <label class="control-label">Template name <span class="required">* </span> </label>
                <div class="input-icon right">
                    <input type="text" name="template_name" required maxlength="45" wire:model.lazy="templateName" class="form-control" placeholder="Enter a name for your template" />
                    @if($error!=null)<span id="tax_name-error" style="color: #a94442;" class="help-block help-block-error has-error">{{$error}}</span>@endif
                </div>
            </div>
        </div>
    </div>
    @if(!empty($profileList))
    @if(count($profileList)>1)
    <div class="col-md-6">
        <div class="form-group ">
            <div class="col-md-8  no-padding">
                <label class="control-label">Billing profile<span class="required"> </span> </label>
                <div class="input-icon right">
                    <select wire:change="$emit('changeProfile',$event.target.value)" class="form-control" id="profile_id" name="billingProfile_id">
                        <option value="">Select..</option>
                        @foreach($profileList as $v)
                        <option @if($profileId==$v['id']) selected @endif value="{{$v['id']}}">{{$v['profile_name']}} {{$v['gst_number']}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>
    @else
    <input type="hidden" name="billing_profile_id" value="{{$profileList[0]['id']}}">
    @endif
    @endif


    <script>
        
        @if($error != null)
        document.getElementById('btnsubmit').disabled = true;
        @else
        document.getElementById('btnsubmit').disabled = false;
        @endif
    </script>

</div>