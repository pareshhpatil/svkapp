@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">

    <div id="app" class="section ">
        @if(session()->has('message'))
        <div class="alert alert-outline-info alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="submitForm" action="/save/ride" method="post">
                        @csrf

                        <!-- Pickup/Drop Selection -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="project_id">Project</label>
                                <select v-model="form.project_id" name="project_id" id="project_id" class="form-control custom-select select2" required>
                                    <option value="7">Neuiq</option>
                                    <option value="6">Nielson</option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="pickup_drop">Pickup/Drop</label>
                                <select v-model="form.pickup_drop" name="pickup_drop" id="pickup_drop" class="form-control custom-select select2" required>
                                    <option value="">Select..</option>
                                    <option value="Pickup">Pickup</option>
                                    <option value="Drop">Drop</option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="date">Date</label>
                                <input v-model="form.date" type="date" name="date" id="date" class="form-control" required>
                            </div>
                        </div>

                        <!-- Drop Time -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="drop_time">Drop Time</label>
                                <select v-model="form.drop_time" name="drop_time" id="drop_time" class="form-control custom-select select2" required>
                                    <option value="" disabled>Select shift time..</option>
                                    <option v-if="loadingShifts" value="" disabled>Loading shifts...</option>
                                    <option v-if="errorShifts" value="" disabled>Error loading shifts</option>
                                    <option v-for="shift in shifts" :key="shift.shift_time" :value="shift.shift_time">
                                        @{{ shift.name }}
                                    </option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <!-- Car Type -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="car_type">Car Type</label>
                                <select v-model="form.car_type" name="car_type" id="car_type" class="form-control custom-select select2" required>
                                    <option value="">Select..</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <!-- Slab Package -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="slab_package">Slab Package</label>
                                <select v-model="form.slab_package"  required name="slab_package" id="slab_package" class="form-control custom-select select2">
                                    <option value="" disabled>Select packages..</option>
                                    <option v-if="loading" value="" disabled>Loading packages...</option>
                                    <option v-if="error" value="" disabled>Error loading packages</option>
                                    <option v-for="zone in slabPackages" :key="zone.zone_id" :value="zone.zone_id">
                                        @{{ zone.zone }}
                                    </option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="title">Title</label>
                                <input v-model="form.title" type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
                            </div>
                        </div>

                        <!-- Escort -->
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="escort">Escort</label>
                                <select v-model="form.escort" name="escort" id="escort" class="form-control custom-select select2" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                <span v-if="loading">Loading...</span>
                                <span v-else>Submit</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <script>
            new Vue({
                el: '#app',
                data: {
                     form: {
                         project_id: '7', // Default to Neuiq
                         pickup_drop: '',
                         date: '',
                         drop_time: '',
                         car_type: '',
                         slab_package: '0',
                         title: '',
                         escort: '0'
                     },
                     slabPackages: [],
                     shifts: [],
                     loading: false,
                     loadingShifts: false,
                     error: null,
                     errorShifts: null
                },
                watch: {
                    'form.project_id': function() {
                        this.loadSlabPackages();
                        this.loadShifts();
                    },
                    'form.pickup_drop': function() {
                        this.loadShifts();
                    },
                    'form.car_type': function() {
                        this.loadSlabPackages();
                    }
                },
                methods: {
                    loadSlabPackages() {
                        if (this.form.project_id && this.form.car_type) {
                            this.loading = true;
                            this.error = null;
                            this.slabPackages = [];

                            axios.get(`/zone/list/${this.form.project_id}/${this.form.car_type}`)
                                .then(response => {
                                    this.slabPackages = response.data;
                                    this.loading = false;
                                })
                                .catch(error => {
                                    console.error('Error loading slab packages:', error);
                                    this.error = 'Error loading packages';
                                    this.loading = false;
                                });
                        } else {
                            this.slabPackages = [];
                        }
                    },
                    loadShifts() {
                        if (this.form.project_id && this.form.pickup_drop) {
                            this.loadingShifts = true;
                            this.errorShifts = null;
                            this.shifts = [];

                            axios.get(`/shift/list/${this.form.project_id}/${this.form.pickup_drop}`)
                                .then(response => {
                                    this.shifts = response.data;
                                    this.loadingShifts = false;
                                })
                                .catch(error => {
                                    console.error('Error loading shifts:', error);
                                    this.errorShifts = 'Error loading shifts';
                                    this.loadingShifts = false;
                                });
                        } else {
                            this.shifts = [];
                        }
                    },
                    calculateEndTime(date, time, hoursToAdd) {
                         // Create a Date object from the date and time
                         const dateTime = new Date(date + 'T' + time);
                         
                         // Add the specified hours
                         dateTime.setHours(dateTime.getHours() + hoursToAdd);
                         
                         // Format the result as YYYY-MM-DD HH:MM:SS
                         const year = dateTime.getFullYear();
                         const month = String(dateTime.getMonth() + 1).padStart(2, '0');
                         const day = String(dateTime.getDate()).padStart(2, '0');
                         const hours = String(dateTime.getHours()).padStart(2, '0');
                         const minutes = String(dateTime.getMinutes()).padStart(2, '0');
                         const seconds = String(dateTime.getSeconds()).padStart(2, '0');
                         
                         return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                     },
                     submitForm() {
                         // Handle form submission
                         this.loading = true;
                         console.log('Form data:', this.form);

                         // Calculate end time by adding 2 hours to drop time
                         const startTime = this.form.date + ' ' + this.form.drop_time;
                         const endTime = this.calculateEndTime(this.form.date, this.form.drop_time, 2);

                         // Create FormData for submission
                         const formData = new FormData();
                         formData.append('_token', document.querySelector('input[name="_token"]').value);
                         formData.append('project_id', this.form.project_id);
                         formData.append('type', this.form.pickup_drop);
                         formData.append('date', this.form.date);
                         formData.append('start_time', startTime);
                         formData.append('end_time', endTime);
                         formData.append('start_location', this.form.pickup_drop == 'Pickup' ? this.form.title : 'Marol');
                         formData.append('end_location', this.form.pickup_drop == 'Pickup' ? 'Marol' : this.form.title);
                         formData.append('title', this.form.title);
                         formData.append('escort', this.form.escort);
                         formData.append('slab_id', this.form.slab_package);


                        // Submit form via axios
                        axios.post('/save/ride', formData)
                            .then(response => {
                                this.loading = false;
                                console.log('Success:', response.data);
                                // Handle success (redirect, show message, etc.)
                                if (response.data.success) {
                                    window.location.href = response.data.redirect || '/my-rides';
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                console.error('Error:', error);
                                // Handle error
                                alert('Error submitting form. Please try again.');
                            });
                    }
                },
                mounted() {
                    // Load initial data if both project and car type are selected
                    if (this.form.project_id && this.form.car_type) {
                        this.loadSlabPackages();
                    }
                    // Load initial shifts if both project and pickup_drop are selected
                    if (this.form.project_id && this.form.pickup_drop) {
                        this.loadShifts();
                    }
                }
            });
        </script>









    </div>



    @endsection



    @section('footer')








    @endsection