@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

@endsection
@section('content')
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Roster /</span> List</h4>
                </div>


                @if($bulk_id==0)
                <div class="col-md-6 col-12 mb-4 pull-right">
                    <input type="text" onchange="reload()" id="bs-rangepicker-time" class="form-control" />
                </div>
                <div class="col-lg-4 pull-right">
                    <form action="" id="frm" method="post">
                        @csrf
                        <select name="project_id" id="select2Basic" onchange="reload()" class="select2 form-select input-sm" data-allow-clear="true">
                            <option value="0">All</option>
                            @if(!empty($project_list))
                            @foreach($project_list as $v)
                            <option @if($project_id==$v->project_id) selected @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                            @endforeach
                            @endif

                        </select>
                    </form>
                </div>
                <div class="col-lg-2 pull-right">
                    <button class="btn btn-secondary btn-sm  add-new btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Roster</span></span></button>
                </div>
                @else
                <input type="hidden" id="bs-rangepicker-time" value="" />
                <input type="hidden" id="select2Basic" value="0" />
                @endif
            </div>
            <div class="card invoice-preview-card">

                <div class="card-body">

                    <div class="card-datatable table-responsive pt-0">

                        <table id="datatable" class="datatables-basic  table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Mobile</th>
                                    <th>Location</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

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
                roster: [],
                passengers: [],
                shifts: [],
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
        },
        methods: {

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
                    console.log(res.data);
                    this.shifts = res.data;
                }

            },

            setRosterTime(val)
            {
                this.roster_shift=val;
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
                        reload();
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
    var bsRangePickerTime = $('#bs-rangepicker-time');
    bsRangePickerTime.daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'DD MMM YYYY h:mm A'
        },
        opens: isRtl ? 'left' : 'right'
    });

    var dt_basic;
    var project_id = 0;
    var date = 'na';

    function reload() {
        // try {

        project_id = document.getElementById('select2Basic').value;
        date = document.getElementById('bs-rangepicker-time').value;
        try {
            if (dt_basic) {
                dt_basic.destroy();
            }
        } catch (o) {}
        datatable();


        //  } catch (o) {}

    }
    // datatable (jquery)
    $(function() {
        @if($bulk_id > 0)
        datatable();
        @endif

    });

    function deletePassenger(id) {
        response = confirm('Are you sure you want to delete this item?');
        if (response == true) {
            $.get("/roster/delete/" + id + "/{{$bulk_id}}", function(data, status) {
                dt_basic.destroy();
                datatable();
            });
        }
    }


    function datatable() {
        var dt_basic_table = $('#datatable');
        // dt_basic;

        // DataTable with buttons
        // --------------------------------------------------------------------
        if (dt_basic_table.length) {
            dt_basic = dt_basic_table.DataTable({
                ajax: '/ajax/roster/' + date + '/' + project_id + '/{{$bulk_id}}',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'employee_name'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'location'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'shift'
                    },
                    {
                        data: 'start_time'
                    },
                    {
                        data: ''
                    }
                ],
                columnDefs: [{
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end m-0">' +
                            '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
                            '<li><a href="/roster/update/' + full.id + '" class="dropdown-item">Edit</a></li>' +
                            '<li><a href="javascript:;" onclick="' + "deletePassenger(" + full.id + ");" + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                            '</ul>' +
                            '</div>'
                        );
                    }
                }],
                order: [
                    [0, 'desc']
                ],
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
            });
        }
    }
</script>






@endsection