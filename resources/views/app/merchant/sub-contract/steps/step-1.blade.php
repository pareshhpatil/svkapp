<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4">Select vendor <span class="required">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control select2Contract" data-placeholder="Select vendor" required name="vendor_id" id="vendor_id">
                            <option value="">Select vendor</option>
                            @foreach($vendor_list as $vendor)
                                @if(($sub_contract && $sub_contract->vendor_id == $vendor->vendor_id) || (old('vendor_id') == $vendor->vendor_id))
                                    <option value="{{$vendor->vendor_id}}" selected>{{$vendor->vendor_name}} | {{$vendor->vendor_id}}</option>
                                @else
                                    <option value="{{$vendor->vendor_id}}">{{$vendor->vendor_name}} | {{$vendor->vendor_id}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4">Select project <span class="required">*</span></label>
                    <div class="col-md-8">
                        <select class="form-control select2Contract" data-placeholder="Select project" required name="project_id" id="project_id">
                            <option value="">Select project</option>
                            @foreach($project_list as $project)
                                @if(($sub_contract && $sub_contract->project_id == $project->id) || (old('project_id') == $project->id))
                                    <option value="{{$project->id}}" selected>{{$project->project_name}} | {{$project->project_id}}</option>
                                @else
                                    <option value="{{$project->id}}">{{$project->project_name}} | {{$project->project_id}}</option>
                                @endif
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Start date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $start_date = $sub_contract->start_date ?? old('start_date')  @endphp
                        <input id="start_date" class="form-control form-control-inline date-picker"
                               type="text" required data-cy="start_date" name="start_date" data-date-format="{{ Session::get('default_date_format')}}"
                               autocomplete="off" placeholder="Start date" @if($start_date != null) value='<x-localize :date="$start_date" type="date" />' @endif
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                        <div class="text-danger" id="start_date_error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">End date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $end_date = $sub_contract->end_date ?? old('end_date')  @endphp
                        <input id="end_date" class="form-control form-control-inline date-picker"
                               type="text" required data-cy="end_date" name="end_date" data-date-format="{{ Session::get('default_date_format')}}"
                               autocomplete="off" placeholder="End date" @if($end_date != null) value='<x-localize :date="$end_date" type="date" />' @endif
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Default Retainage</label>
                    <div class="col-md-8">
                        <input type="text" name="default_retainage" id="default_retainage" class="form-control" value="{{ $sub_contract->default_retainage ?? old('default_retainage') }}"  >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Sub Contract Code</label>
                    <div class="col-md-8">
                        <input type="text" name="sub_contract_code" id="subcontract_code" class="form-control" value="{{ $sub_contract->sub_contract_code ?? old('sub_contract_code') }}"  >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4">Title<span class="required">*</span></label>
                    <div class="col-md-8">
                        <input type="text" name="title" id="title" required class="form-control" value="{{ $sub_contract->title ?? old('title') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Sign</label>
                    <div class="col-md-8">
                        <input type="text" name="sign" id="sign" class="form-control" value="{{ $sub_contract->sign ?? old('sign') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Description</label>
                    <div class="col-md-8">
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" >{{ $sub_contract->description ?? old('description') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Attachments<span class="required">*</span></label>
                    <div class="col-md-8">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="/merchant/sub-contracts" class="btn green">Cancel</a>
                    <button type="submit" class="btn blue" onclick="return validateDates()">Add Particulars</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.select2Contract').select2();

        function validateDates() {
            if(Date.parse($('#end_date').val()) > Date.parse($('#start_date').val())) {
                $('#start_date_error').html('Start date should be greater than or equal to End date')
                return false
            }

            return true;
        }
    })
</script>