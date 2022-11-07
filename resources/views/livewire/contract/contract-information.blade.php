<div class="form-body">
    <!-- Start profile details -->
    <div class="row">
        <div class="col-md-6">
            <h3 class="form-section">
                Contract information
            </h3>
            <div class="form-group">
                <label class="control-label col-md-4">Select project <span class="required">*
                    </span></label>
                <div class="col-md-8">
                    <select data-search="true" placeholder="Select.." data-silent-initial-value-set="true" onchange="alert(this.value);" data-placeholder="Select project" required name="project_id" id="project_id">
                        @foreach($project_list as $v)
                        <option value="{{$v->id}}">{{$v->project_name}} | {{$v->project_id}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Contract number <span class="required">
                    </span></label>
                <div class="col-md-8">
                    <input type="text" maxlength="45" data-cy="contract_no" name="contract_no" class="form-control" data-cy="invoice_no" value="{{ old('invoice_no') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Billing frequency<span class="required">
                    </span></label>
                <div class="col-md-8">
                    <select onchange="alert(this.value);" name="billing_frequency">
                        <option value="1">Weekly</option>
                        <option selected value="2">Monthly</option>
                        <option value="3">Quaterly</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Contract date<span class="required">*
                    </span></label>
                <div class="col-md-8">
                    <input class="form-control form-control-inline date-picker" type="text" required data-cy="contract_date" name="contract_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Contract date" value="{{ old('due_date') }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">First billing date<span class="required">*
                    </span></label>
                <div class="col-md-8">
                    <input class="form-control form-control-inline date-picker" data-cy="bill_date" type="text" required name="bill_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Bill date" value="{{ old('bill_date') }}" />
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
                        <input type="text" id="project_name" name="project_name" readonly class="form-control cust_det">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Company name</label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input type="text" id="customer_code" name="customer_code" readonly class="form-control cust_det">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Customer name</label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input type="text" id="customer_name" name="customer_name" readonly class="form-control cust_det">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Email</label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input type="text" id="customer_email" name="customer_email" readonly class="form-control cust_det">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Mobile number</label>
                <div class="col-md-8">
                    <div class="input-icon right">
                        <input type="text" id="customer_number" name="customer_number" readonly class="form-control cust_det">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    VirtualSelect.init({
        ele: 'select'
    });

    $('#project_id').change(function() {
        projectSelected(this.value);
        document.getElementById('perror').style.display = 'none';
    });
</script>