@extends('layouts.app')
@section('content')
<div id="appCapsule" class="full-height">

    <div id="app">
        <div class="card">
            <div class="card-body">
                <form id="frm" @submit="formSubmit" enctype="multipart/form-data">
                    @csrf
                    <div class="section text-center">
                        <div class="avatar-section">
                            <a href="#">
                                <img :src="data.icon" id="imgsrc" alt="avatar" class="imaged w100 rounded">
                                <input type="file" id="fileuploadInput" style="display: none;" name="file" v-on:change="onImageChange" accept=".png, .jpg, .jpeg">
                                <span class="button" class="custom-file-upload" onclick="document.getElementById('fileuploadInput').click();">
                                    <ion-icon name="camera-outline"></ion-icon>
                                </span>
                            </a>

                        </div>
                    </div>
                </form>
                <form ref="form" action="/profile/save" method="post">
                    @csrf
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Name</label>
                            <input type="text" class="form-control" name="name" v-model="data.name" placeholder="Enter your name">
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
                            <input type="text" class="form-control" name="email" v-model="data.email" placeholder="Enter your email">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Emergency contact</label>
                            <input type="text" class="form-control" name="emergency_contact" v-model="data.emergency_contact" placeholder="Enter emergency contact">
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Location</label>
                            <input type="text" name="location" class="form-control" v-model="data.location" placeholder="Enter your location eg. Kharghar">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="textarea4b">Address</label>
                            <textarea id="textarea4b" name="address" rows="2" v-model="data.address" class="form-control" placeholder="Enter your full address"></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Update</button>
                            </div>
                            <div class="col-6">
                                <a href="/dashboard" class="btn btn-lg btn-outline-secondary btn-block">Cancel</a>
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
                image: ''
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
            if (this.data.icon == '') {
                this.data.icon = '/assets/img/sample/avatar/avatar1.jpg';
            }
        },
        methods: {
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