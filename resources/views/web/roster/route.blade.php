@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css" />

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<style>
    .mr-1 {
        margin-right: 10px;
    }

    .list-group-item {
        padding-right: 0;
        padding-left: 0;
    }

    .list-group {
        min-height: 100px;
        font-size: 14px;
    }
</style>
@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Roster /</span> Routing</h4>
                </div>
                <div class="col-lg-4 pull-right">
                    <button v-if="project_id>0" v-on:click="newCab" class="btn btn-primary btn-sm waves-effect waves-light" type="button" data-bs-toggle="modal" data-bs-target="#addnewcab">
                        <i class="ti ti-plus ti-xs me-1"></i>Add Cab
                    </button>
                    <button class="btn btn-secondary btn-sm  add-new btn-success" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Roster</span></span></button>

                </div>
            </div>
            <div class="card invoice-preview-card">
                <div class="row">
                    <div class="col-md-8" style="max-height: 700px;overflow: auto;">
                        <div class="row">
                            <div class="col-md-6" v-for="(item, index) in routes" v-if="item.is_active==1">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-label-primary" v-if="item.type=='Drop'" v-html="item.type"></span>
                                            <span class="badge bg-label-success" v-if="item.type=='Pickup'" v-html="item.type"></span>
                                            <span v-html="item.time"></span> &nbsp;&nbsp;
                                            <span v-html="item.slab_text"></span>
                                            <div class="card-action-element">
                                                <button type="button" v-on:click="editcab(index)" data-bs-toggle="modal" data-bs-target="#addnewcab" class="btn btn-icon btn-sm btn-outline-vimeo waves-effect">
                                                    <i class="tf-icons ti ti-pencil"></i>
                                                </button>
                                                <button type="button" v-on:click="removecab(index)" class="btn btn-icon btn-sm btn-outline-dribbble waves-effect">
                                                    <i class="tf-icons ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush" style="min-height: 30px;">
                                            <li v-if="item.escort>0" class="list-group-item cursor-move d-flex  align-items-center">
                                                <img class="rounded-circle mr-1" src="/assets/img/escort.png" alt="avatar" height="32" width="32" />
                                                <span v-html="'Escort added - '+item.escort_name"></span>&nbsp;&nbsp;
                                                <span class="pull-right">
                                                    <input type="time" id="modalAddCardExpiryDate" style="width: auto;" class="form-control expiry-date-mask" placeholder="Pickup Time">
                                                </span>
                                            </li>
                                        </ul>
                                        <ul class="list-group list-group-flush" :id="item.id">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-12 pull-right">
                                    <input type="text" onchange="setdate();" id="bs-rangepicker-time" class="form-control" />
                                </div>
                                <div class="col-lg-6 pull-right  mt-2">
                                    <select id="select2Basic" v-on:change="fetchData" class="select2 form-select input-sm" data-allow-clear="true">
                                        <option value="0">Select Project</option>
                                        @if(!empty($project_list))
                                        @foreach($project_list as $v)
                                        <option @if($project_id==$v->project_id) selected @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6 pull-right mt-2">
                                    <select id="type" v-on:change="filterRows" class="select2 form-select input-sm" data-allow-clear="true">
                                        <option value="">Select type</option>
                                        <option value="Drop">Drop</option>
                                        <option value="Pickup">Pickup</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 pull-right mt-2">
                                    <select id="shift" v-on:change="filterRows" class="select2 form-select input-sm" data-allow-clear="true">
                                        <option value="">Select shift</option>
                                        <option v-for="item in data.shift" :value="item" v-html="item"></option>
                                    </select>
                                </div>
                                <div class="col-lg-6 pull-right mt-2">
                                    <input type="text" id="search" onkeydown="vue.filterRows()" class="form-control" placeholder="Search..">
                                </div>

                                <div class="card-datatable table-responsive pt-0" style="max-height: 500px;">

                                    <div class="col-md-12 col-12 mb-md-0 mb-4">
                                        <p>&nbsp;</p>
                                        <ul class="list-group list-group-flush" id="roster_list">
                                            <li v-for="item in roster" :id="item.id" class="list-group-item drag-item cursor-move d-flex  align-items-center">
                                                <img class="rounded-circle mr-1" :src="item.photo" alt="avatar" height="32" width="32" />
                                                <span v-html="item.title"> </span>
                                                <input type="time" :value="item.start_time" v-if="item.type=='Pickup'" :id="'time'+item.id" style="width: auto;min-width: 130px;" class="form-control" placeholder="Pickup Time">
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <div class="col-lg-12 pull-right">
                <br>
                    <button v-if="project_id>0" v-on:click="saveRoute" class="btn btn-primary waves-effect waves-light" type="button">
                        <i class="ti ti-plus ti-xs me-1"></i>Submit
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addnewcab" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <form id="addNewCCForm" class="row g-3" onsubmit="return false">

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddCardName">Pickup/Drop</label>
                            <select v-model="new_route_type" class="select2 form-select input-sm">
                                <option value="">Select..</option>
                                <option value="Pickup">Pickup</option>
                                <option value="Drop">Drop</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddCardName">Date</label>
                            <input type="date" v-model="new_route_date" style="width: auto;" class="form-control">
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" v-if="new_route_type=='Pickup'" for="modalAddCardName">In Time</label>
                            <label class="form-label" v-if="new_route_type!='Pickup'" for="modalAddCardName">Drop Time</label>
                            <input type="time" v-model="new_route_time" style="width: auto;" class="form-control">
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddCardName">Car Type</label>
                            <select v-model="new_route_car_type" v-on:change="updatezone" class="select2 form-select input-sm">
                                <option value="">Select..</option>
                                <option value="Sedan">Sedan</option>
                                <option value="Suv">Suv</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddCardName">Slab package</label>
                            <select id="slab" class="select2 form-select input-sm" placeholder="Select.." aria-placeholder="Select..">
                                <option v-for="item in data.zone_list" v-if="new_route_car_type==item.car_type" :value="item.zone_id" v-html="item.zone"></option>
                            </select>
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalAddCardName">Escort</label>
                            <select v-model="new_route_escort" id="escort" class="select2 form-select input-sm">
                                <option value="">Select..</option>
                                <option value="1">Yes</option>

                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <input type="hidden" v-model="edit_cab" :value="edit_cab" id="edit_cab" />
                            <a v-on:click="addCab" href="#" class="btn btn-primary me-sm-3 me-1">Submit</a>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('web.roster.add')
</div>


@endsection

@section('footer')

<script src="/assets/vendor/libs/moment/moment.js"></script>
<script src="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="/assets/vendor/libs/sortablejs/sortable.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                project_id: '{{$project_id}}',
                date: '',
                current_rating: 0,
                routes: [],
                roster: [],
                shifts: [],
                passengers: [],
                new_route_car_type: '',
                new_route_type: '',
                new_route_escort: '0',
                new_route_slab: '',
                new_route_time: '{{date("H:i")}}',
                new_route_date: '{{date("Y-m-d")}}',
                new_route_ids: [],
                new_route: JSON.parse('{"car_type":"","type":"","escort":"0"}'),
                car_type: '',
                count: 0,
                edit_cab: '',
                emp_mobile: '',
                emp_gender: 'Male',
                emp_project_id: 0,
                emp_location: '',
                emp_address: '',
                emp_name: '',
                emp_id: 0,
                roster_type: 'Drop',
                roster_shift: '',
                roster_in_time: '{{date("H:i")}}',
                roster_time: '',
                roster_date: '{{date("Y-m-d")}}',
            }
        },
        mounted() {
            window.vue = this;
            const
                pendingTasks = document.getElementById('roster_list');
            // Multiple
            // --------------------------------------------------------------------
            if (pendingTasks) {
                var sortable = Sortable.create(pendingTasks, {
                    animation: 150,
                    group: 'taskList',
                    onEnd: function(event) {
                        vue.moveData(event.item.id, event.to.id, event.from.id);
                    }
                });
            }
        },
        methods: {
            fetchData() {
                this.routes = [];
                this.date = document.getElementById('bs-rangepicker-time').value;
                this.project_id = document.getElementById('select2Basic').value;
                this.getData();
            },
            updatezone() {
                $('#slab').select2({
                    dropdownParent: $("#addnewcab")
                });
            },
            removeArray(arr, val) {
                for (var i = arr.length; i--;) {
                    if (arr[i] === val) {
                        arr.splice(i, 1);
                    }
                }
                return arr;
            },
            moveData(id, to, from) {


                if (from == 'roster_list') {
                    to_id = Number(to.substring(3)) - 1;
                    if (Array.isArray(this.routes[to_id].ids)) {
                        array = this.routes[to_id].ids;
                        array.push(id);
                        this.routes[to_id].ids = array;
                    } else {
                        array = [id];
                        this.routes[to_id].ids = array;
                    }

                } else if (to == 'roster_list') {
                    from_id = Number(from.substring(3)) - 1;
                    this.routes[from_id].ids = this.removeArray(this.routes[from_id].ids, id);
                } else {
                    from_id = Number(from.substring(3)) - 1;
                    to_id = Number(to.substring(3)) - 1;
                    if (Array.isArray(this.routes[to_id].ids)) {
                        array = this.routes[to_id].ids;
                        array.push(id);
                        this.routes[to_id].ids = array;
                    } else {
                        array = [id];
                        this.routes[to_id].ids = array;
                    }
                    this.routes[from_id].ids = this.removeArray(this.routes[from_id].ids, id);
                }
                console.log(this.routes);


            },
            addCab() {
                this.new_route_slab = $("#slab").val();
                if (this.new_route_car_type != '' && this.new_route_car_type != '' && this.new_route_slab > 0) {
                    slab_text = $("#slab").select2('data')[0].text;
                    escort_name = '';
                    edit_cab = $("#edit_cab").val();
                    array = JSON.parse('{"id":"","ids":"' + this.new_route_ids + '","time":"' + this.new_route_time + '","date":"' + this.new_route_date + '","car_type":"' + this.new_route_car_type + '","type":"' + this.new_route_type + '","escort_name":"' + escort_name + '","escort":"' + this.new_route_escort + '","slab":"' + this.new_route_slab + '","slab_text":"' + slab_text + '","is_active":"1"}');
                    if (edit_cab != '') {
                        this.routes[edit_cab] = array;
                    } else {
                        this.count = this.count + 1;
                        array.id = "cab" + this.count;
                        cab_id = "cab" + this.count;
                        this.routes.push(array);
                        setTimeout(function() {
                            var sortable = Sortable.create(document.getElementById(cab_id), {
                                animation: 150,
                                group: 'taskList',
                                onEnd: function(event) {
                                    vue.moveData(event.item.id, event.to.id, event.from.id);
                                }
                            });
                        }, 1000);
                    }
                    array = [];
                    this.new_route_escort = '0';
                    this.new_route_slab = '';
                    this.new_route_ids = [];
                    this.edit_cab = '';
                    edit_cab = '';
                    $('#addnewcab').modal('hide');
                }
            },
            filterRows() {
                shift = $("#shift").val();
                type = $("#type").val();
                search = $("#search").val();
                var input, filter, table, tr, td, i, txtValue;
                table = document.getElementById("roster_list");
                tr = table.getElementsByTagName("li");
                search = search.toUpperCase();
                for (i = 0; i <= tr.length - 1; i++) {
                    display = 'flex';
                    if (type != '') {
                        if (this.data.roster[tr[i].id].type != type) {
                            display = 'none';
                        }
                    }
                    if (shift != '') {
                        if (this.data.roster[tr[i].id].shift != shift) {
                            display = 'none';
                        }
                    }
                    if (search != '') {
                        var text = this.data.roster[tr[i].id].title;
                        text = text.toUpperCase();
                        //  console.log(search);
                        // console.log(text);
                        if (text.includes(search)) {

                        } else {
                            display = 'none';
                        }
                    }
                    document.getElementById(tr[i].id).setAttribute('style', 'display:' + display + ' !important');
                }

            },
            newCab() {
                this.edit_cab = '';
            },
            editcab(index) {
                this.edit_cab = index;
                this.new_route_slab = this.routes[index].slab;
                this.new_route_car_type = this.routes[index].car_type;
                this.new_route_type = this.routes[index].type;
                this.new_route_escort = this.routes[index].escort;
                //this.new_route_ids = this.routes[index].ids;
                this.new_route_date = this.routes[index].date;
                this.new_route_time = this.routes[index].time;
                document.getElementById('slab').value = this.new_route_slab;
                $("#slab").val(this.new_route_slab).trigger('change');
            },
            removecab(index) {
                this.routes[index].is_active = 0;
            },
            async getData() {
                // var date = '';
                if (this.project_id > 0) {
                    let res = await axios.get('/ajax/roster/route/' + this.date + '/' + this.project_id);
                    this.data = res.data;
                    this.roster = this.data.roster;
                }

            },
            passengerDetail(id) {
                this.emp_id = id;
                if (this.emp_id > 0) {
                    this.emp_name = this.passengers[this.emp_id].employee_name;
                    this.emp_mobile = this.passengers[this.emp_id].mobile;
                    this.emp_address = this.passengers[this.emp_id].address;
                    this.emp_location = this.passengers[this.emp_id].location;
                    this.emp_gender = this.passengers[this.emp_id].gender;
                } else {
                    this.emp_name = '';
                    this.emp_mobile = '';
                    this.emp_address = '';
                    this.emp_location = '';
                }
            },
            fetchPassenger() {
                this.passengers = [];
                this.getPassenger();
                this.getShift();
                $('#passenger_id').select2({
                    dropdownParent: $("#offcanvasAddUser")
                });
                //  $('#passenger_id').select2();
            },
            async getPassenger() {
                // var date = '';
                project_id = document.getElementById('project_id').value;
                this.emp_project_id = project_id;
                if (project_id > 0) {
                    let res = await axios.get('/ajax/passenger/' + project_id + '/0/3');
                    this.passengers = res.data;
                }

            },
            async getShift() {
                // var date = '';
                project_id = document.getElementById('project_id').value;
                roster_type = document.getElementById('roster_type').value;
                this.emp_project_id = project_id;
                if (project_id > 0) {
                    let res = await axios.get('/ajax/shift/' + project_id + '/'+roster_type);
                    this.shifts = res.data;
                }

            },
            setRosterTime(val)
            {
                this.roster_shift=val;
            },
            saveRoute() {
                times = [];
                this.routes.forEach((value, index) => {
                    if (value.type == 'Pickup') {
                        value.ids.forEach((rid, idx) => {
                            try {
                                times.push({
                                    key: rid,
                                    value: document.getElementById('time' + rid).value
                                });
                            } catch (o) {}
                        });
                    }
                });


                const formData = new FormData();
                formData.append('project_id', this.project_id);
                formData.append('data', JSON.stringify(this.routes));
                formData.append('times', JSON.stringify(times));
                axios.post('/roster/route/save', formData)
                    .then((res) => {
                        alert('Rides save successfully');
                        this.fetchData();
                    })
                    .catch((error) => {
                        // error.response.status Check status code
                    }).finally(() => {
                        //Perform action in always
                    });

            },
            saveRoster() {
                this.roster_shift=document.getElementById('roster_time').options[document.getElementById('roster_time').selectedIndex].text;
                const formData = new FormData()
                formData.append('emp_project_id', this.emp_project_id)
                formData.append('emp_name', this.emp_name)
                formData.append('emp_mobile', this.emp_mobile)
                formData.append('emp_address', this.emp_address)
                formData.append('emp_location', this.emp_location)
                formData.append('emp_gender', this.emp_gender)
                formData.append('emp_id', this.emp_id)

                formData.append('roster_date', this.roster_date)
                formData.append('roster_in_time', this.roster_in_time)
                formData.append('roster_shift', this.roster_shift)
                formData.append('roster_type', this.roster_type)
                formData.append('roster_time', this.roster_time)
                axios.post('/roster/save', formData)
                    .then((res) => {
                        $("#passenger_id").val(0).trigger('change');
                        this.passengerDetail(0);
                        alert('Roster added successfully');
                    })
                    .catch((error) => {
                        // error.response.status Check status code
                    }).finally(() => {
                        //Perform action in always
                    });



            }


        }
    })
</script>

<script>
    function setdate() {
        vue.fetchData();
    }

    var bsRangePickerTime = $('#bs-rangepicker-time');
    bsRangePickerTime.daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'DD MMM YYYY h:mm A'
        },
        opens: isRtl ? 'left' : 'right'
    });
    /**
     * Drag & Drop
     */
    'use strict';
</script>


@endsection