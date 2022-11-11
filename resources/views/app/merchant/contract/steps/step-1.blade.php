<div class="portlet light bordered">
{{--        {{dd($errors)}}--}}
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-6">
                <h3 class="form-section">
                    Contract information
                </h3>
                <div class="form-group">
                    <label class="control-label col-md-4">Select project <span class="required">*
                                            </span></label>
                    <div class="col-md-8">
                        <select class="form-control select2Contract" data-placeholder="Select project" required name="project_id" id="project_id">
                            <option value="">Select project</option>
                            @foreach($project_list as $v)
                                @if(($contract && $contract->project_id == $v->id) || (old('project_id') == $v->id))
                                    <option value="{{$v->id}}" selected>{{$v->project_name}} | {{$v->project_id}}</option>
                                @else
                                    <option value="{{$v->id}}">{{$v->project_name}} | {{$v->project_id}}</option>
                                @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="contract_amount" value="{{ $contract->contract_amount??0 }}"/>
                        @error('project_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Contract number <span class="required">*</span></label>
                    <div class="col-md-8">
                        <input type="text" maxlength="45" name="contract_code" id="contract_code" required class="form-control" value="{{ $contract->contract_code??old('contract_code') }}"  >
                        @error('contract_code')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Billing frequency<span class="required">
                                            </span></label>
                    <div class="col-md-8">
                        <select class="form-control" id="billing_frequency" name="billing_frequency">
                            <option @if(($contract && $contract->billing_frequency == 1) || (old('billing_frequency') == 1)) selected @endif value="1">Weekly</option>
                            <option @if(($contract && $contract->billing_frequency == 2) || (old('billing_frequency') == 2)) selected @endif value="2">Monthly</option>
                            <option @if(($contract && $contract->billing_frequency == 3) || (old('billing_frequency') == 3)) selected @endif value="3">Quarterly</option>
                        </select>
                        @error('billing_frequency')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Contract date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $contract_date = $contract->contract_date??old('contract_date')  @endphp
                        <input id="contract_date" class="form-control form-control-inline date-picker"
                               type="text" required data-cy="contract_date" name="contract_date" data-date-format="{{ Session::get('default_date_format')}}"
                               autocomplete="off" placeholder="Contract date" @if($contract_date != null) value='<x-localize :date="$contract_date" type="date" />' @endif
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                        @error('contract_date')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">First billing date<span class="required">*</span></label>
                    <div class="col-md-8">
                        @php $bill_date = $contract->bill_date??old('bill_date')  @endphp
                        <input id="billing_date" class="form-control form-control-inline date-picker"
                               data-cy="bill_date" type="text" required name="bill_date"
                               autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" placeholder="Bill date"
                               data-provide="datepicker" data-date-autoclose="true"
                               data-date-today-highlight="true" @if($bill_date != null)  value='<x-localize :date="$bill_date" type="date" />' @endif
                               onchange="this.dispatchEvent(new InputEvent('input'))"
                        />
                        @error('bill_date')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <h3 class="form-section">
                    Project information
                </h3>
                <div class="form-group">
                    <label class="control-label col-md-4">Project name</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            <input type="text" id="project_name" name="project_name" readonly class="form-control" value="{{ $project->project_name??null }}">
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ $project->customer_id??null }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Company name</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            <input type="text" id="customer_code" name="customer_code" readonly class="form-control" value="{{ (!is_null($project) && is_null($project->company_name))? $project->customer_id : $project->customer_company_code??null }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Customer name</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            <input type="text" id="customer_name" name="customer_name" readonly class="form-control" value="{{ $project->name??null }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Email</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            <input type="text" id="customer_email" name="customer_email" readonly class="form-control" value="{{ $project->email??null }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">Mobile number</label>
                    <div class="col-md-8">
                        <div class="input-icon right">
                            <input type="text" id="customer_number" name="customer_number" readonly class="form-control" value="{{ $project->mobile??null }}">
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {


            // function initialize() {
            $('.select2Contract').select2();
            $('.select2Contract').on('change', function (e) {

                var data = $('.select2Contract').select2('val');
                var actionUrl = '{{ route('contract.fetchProject') }}'
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        project_id: data,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#project_name').val(data.project.project_name)
                        $('#customer_id').val(data.project.customer_id)
                        let company_name = (data.project.company_name === null) ? data.project.customer_id : data.project.customer_company_code
                        $('#customer_code').val(company_name);
                        $('#customer_name').val(data.project.name);
                        $('#customer_email').val(data.project.email);
                        $('#customer_number').val(data.project.mobile);

                    }
                });
            });
            // }
        });
    </script>
</div>
<div class="portlet light bordered">
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-right">
                    <a href="/merchant/contract/list" class="btn green">Cancel</a>
                    <button type="submit" class="btn blue">Add particulars</button>
                </div>
            </div>
        </div>
    </div>
</div>