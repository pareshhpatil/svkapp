@extends('layouts.app')
@section('content')
<script src="https://unpkg.com/vue-croppie/dist/vue-croppie.js"></script>
<link rel="stylesheet" href="https://unpkg.com/croppie/croppie.css">
<div id="appCapsule" class="full-height">

    <div id="app">
        <div class="card">
            <div class="card-body">
                <form id="frm" @submit="formSubmit" enctype="multipart/form-data">
                    @csrf
                    <div class="section mt-3 text-center">
                        <div class="avatar-section">
                            <a href="#">
                                <img :src="data.icon" id="imgsrc" alt="avatar" class="imaged w100 rounded">
                                <input type="file" id="fileuploadInput" style="display: none;" name="file" v-on:change="croppie" accept=".png, .jpg, .jpeg">
                                <input type="hidden" name="image" id="img">
                                <span class="button" class="custom-file-upload" onclick="document.getElementById('fileuploadInput').click();">
                                    <ion-icon name="camera-outline"></ion-icon>
                                </span>
                                <div class="text-info" id="loder" role="status"></div>
                            </a>

                        </div>
                    </div>

                    <!-- the result -->
                    <div v-if="showcropper==true" class="mt-2">
                        <vue-croppie ref="croppieRef" :enableOrientation="true" :boundary="{ width: 300, height: 300}" :viewport="{ width:300, height:300, 'type':'square' }">
                        </vue-croppie>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8">
                                <a href="#" v-on:click="crop" class="btn btn-lg btn-primary btn-block">Upload</a>
                            </div>
                        </div>
                    </div>
                </form>
                <form ref="form" action="/profile/save" method="post">
                    @csrf
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Name</label>
                            <input type="text" class="form-control" :onclick="updated=true" name="name" v-model="data.name" placeholder="Enter your name">
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Gender</label>
                            <input type="text" class="form-control" readonly name="mobile" v-model="data.gender">
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Mobile</label>
                            <input type="text" class="form-control" readonly name="mobile" v-model="data.mobile" placeholder="Enter your name">
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Email</label>
                            <input type="text" class="form-control" :onclick="updated=true" name="email" v-model="data.email" placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Emergency contact</label>
                            <input type="text" class="form-control" :onclick="updated=true" name="emergency_contact" v-model="data.emergency_contact" placeholder="Enter emergency contact">
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Location</label>
                            <input type="text" name="location" readonly class="form-control" :onclick="updated=true" v-model="data.location" placeholder="Enter your location eg. Kharghar">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="textarea4b">Address</label>
                            <textarea id="textarea4b" name="address" readonly :onclick="updated=true" rows="2" v-model="data.address" class="form-control" placeholder="Enter your full address"></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div v-show="updated" class="mt-2">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                image: '',
                updated: false,
                croppieImage: '',
                cropped: null,
                showcropper: false
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            if (this.data.icon == '') {
                this.data.icon = '/assets/img/sample/avatar/avatar1.jpg';
            }

            this.updated = false;
        },
        methods: {
            croppie(e) {
                this.showcropper = true;
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;

                var reader = new FileReader();
                reader.onload = e => {
                    this.$refs.croppieRef.bind({
                        url: e.target.result
                    });
                };

                reader.readAsDataURL(files[0]);
            },
            crop() {
                // Options can be updated.
                // Current option will return a base64 version of the uploaded image with a size of 600px X 450px.
                let options = {
                    type: 'base64',
                    size: {
                        width: 300,
                        height: 300
                    },
                    format: 'jpeg'
                };

                lo(true);
                let currentObj = this;

                this.$refs.croppieRef.result(options, output => {
                    image = this.croppieImage = output;

                    let formData = new FormData();
                    // image = document.getElementById('img').value;
                    formData.append('image', image);

                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }

                    axios.post('/upload/file/image', formData, config)
                        .then(function(response) {
                            currentObj.data.icon = response.data.image;
                            lo(false);
                        })
                        .catch(function(error) {
                            currentObj.output = error;
                            lo(false);
                        });
                });
                // alert(this.image);
                this.showcropper = false;
            },
            updateValue(col, val) {
                axios.get('/setting/update/' + col + '/' + val);
            },
            onImageChange(e) {
                this.image = e.target.files[0];
                this.formSubmit();
            },
            formSubmit(e) {
                let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let formData = new FormData();
                formData.append('file', this.image);

                axios.post('/upload/file/image', formData, config)
                    .then(function(response) {
                        currentObj.data.icon = response.data.image;
                    })
                    .catch(function(error) {
                        currentObj.output = error;
                    });
            }
        }


    })
</script>
@endsection