<div class="portlet light bordered">
                    <div class="portlet-body form">
<div class="row" @if($contract_id == null) wire:poll.10s="store" @endif>
    <input type="hidden" wire:model.defer="contract_id">
    <div class="col-md-6" wire:ignore>
        <h3 class="form-section">
            Contract information
        </h3>
        <div class="form-group">
            <label class="control-label col-md-4">Select project <span class="required">*
                                            </span></label>
            <div class="col-md-8">
                <select class="form-control select2Contract" data-placeholder="Select project" required wire:model="project_id" x-ref="project_id" id="project_id">
                    <option value="">Select project</option>
                    @foreach($project_list as $v)
                        <option value="{{$v->id}}">{{$v->project_name}} | {{$v->project_id}}</option>
                    @endforeach
                </select>
                <input type="hidden" wire:model.defer="contract_id"/>
                <div class="text-danger" x-show="forms.no_project_id">
                    Please select project ID.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Contract number <span class="required">
                                            </span></label>
            <div class="col-md-8">
                <input type="text" maxlength="45" id="contract_number" wire:model.defer="contract_code" x-model="contract_code" class="form-control" >
                <div class="text-danger" x-show="forms.no_contract_code">
                    Please enter contract code.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Billing frequency<span class="required">
                                            </span></label>
            <div class="col-md-8">
                <select class="form-control" id="billing_frequency" wire:model.defer="billing_frequency">
                    <option value="1">Weekly</option>
                    <option value="2">Monthly</option>
                    <option value="3">Quarterly</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Contract date<span class="required">*
                                            </span></label>
            <div class="col-md-8">
                <input id="contract_date" class="form-control form-control-inline date-picker"
                       type="text" required data-cy="contract_date" wire:model.defer="contract_date" x-model="contract_date"
                       autocomplete="off" data-date-format="dd M yyyy" placeholder="Contract date"
                       data-provide="datepicker" data-date-autoclose="true"
                       data-date-today-highlight="true"
                       onchange="this.dispatchEvent(new InputEvent('input'))"/>
                <div class="text-danger" x-show="forms.no_contract_date">
                    Please select contract date.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">First billing date<span class="required">*
                                            </span></label>
            <div class="col-md-8">
                <input id="billing_date" class="form-control form-control-inline date-picker"
                       data-cy="bill_date" type="text" required wire:model.defer="bill_date" x-model="bill_date"
                       autocomplete="off" data-date-format="dd M yyyy" placeholder="Bill date"
                       data-provide="datepicker" data-date-autoclose="true"
                       data-date-today-highlight="true"
                       onchange="this.dispatchEvent(new InputEvent('input'))"
                />
                <div class="text-danger" x-show="forms.no_bill_date">
                    Please select billing date.
                </div>
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
    <script>
        $(document).ready(function () {


                $('.select2Contract').select2();
                $('.select2Contract').on('change', function (e) {

                    var data = $('.select2Contract').select2('val');

                    @this.set('project_id', data);
                });
            // }
        });
    </script>
</div>
</div>
</div>
