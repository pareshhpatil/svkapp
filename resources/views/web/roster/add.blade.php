<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Roster</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm" >
            @csrf
            <div class="mb-3">
                <label class="form-label" for="add-user-fullname">Project</label>
                <select name="project_id" id="project_id" v-on:change="fetchPassenger" class="select2 form-select input-sm" data-allow-clear="true">
                    <option value="0">Select..</option>
                    @if(!empty($project_list))
                    @foreach($project_list as $v)
                    <option @if($project_id==$v->project_id) selected @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="mb-3">
                <select id="passenger_id" name="passenger_id"  onchange="vue.passengerDetail(this.value);" class="select2 form-select input-sm" placeholder="Select.." aria-placeholder="Select..">
                    <option value="0">New employee</option>
                    <option v-for="item in passengers" :value="item.id" v-html="item.employee_name + ' ' + item.location"></option>
                </select>
            </div>
            <div class="mb-3">
                <input type="text" id="emp_name"  v-model="emp_name" class="form-control" placeholder="Employee name" aria-label="jdoe1" name="emp_name" />
            </div>
            <div class="mb-3">
                <input type="text" id="emp_name" v-model="emp_mobile" maxlength="10" minlength="10" class="form-control phone-mask" placeholder="10 Digit mobile number" aria-label="john.doe@example.com" name="emp_mobile" />
            </div>

            <div class="mb-3">
                <select id="gender" v-model="emp_gender" name="emp_gender" class="select2 form-select">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="mb-3">
                <input type="text" id="emp_location" v-model="emp_location" maxlength="100" class="form-control phone-mask" placeholder="eg. Bandra" name="emp_location" />
            </div>
            <div class="mb-3">
                <textarea id="emp_address" v-model="emp_address" rows="2" class="form-control" placeholder="Address" name="emp_address">
                </textarea>
            </div>
            <div class="mb-3">
                <select id="gender" v-model="roster_type" class="select2 form-select">
                    <option value="Pickup">Pickup</option>
                    <option value="Drop">Drop</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="text" id="roster_shift" v-model="roster_shift" maxlength="100" class="form-control phone-mask" placeholder="Shift eg. 12 Out" name="roster_shift" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="add-user-fullname">Date</label>
                <input type="date" id="roster_date" v-model="roster_date" class="form-control phone-mask" name="roster_date" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="add-user-fullname">Pickup Time</label>
                <input type="time" id="roster_time" v-model="roster_time" class="form-control phone-mask" name="roster_time" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="add-user-fullname">In Time</label>
                <input type="time" id="roster_in_time" v-model="roster_in_time" class="form-control phone-mask" name="roster_in_time" />
            </div>

            <button type="button" v-on:click="saveRoster" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
    </div>
</div>