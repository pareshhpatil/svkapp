@extends('layouts.app')
@section('content')
<div id="appCapsule">
    <div id="app">
        <form id="frm" @submit="formSubmit" enctype="multipart/form-data">
            @csrf
            <div class="section mt-3 text-center">
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

        <div class="listview-title mt-1">Theme</div>
        <ul class="listview image-listview text inset no-line">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Dark Mode
                        </div>
                        <div class="form-check form-switch  ms-2">
                            <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                            <label class="form-check-label" for="darkmodeSwitch"></label>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="listview-title mt-1">Notifications</div>
        <ul class="listview image-listview text inset">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            App notifications
                            <div class="text-muted">
                                Receive in app notification
                            </div>
                        </div>
                        <div class="form-check form-switch  ms-2">
                            <input class="form-check-input" v-model="data.app_notification" :checked="data.app_notification" value="1" v-on:change="updateValue('app_notification',data.app_notification)" type="checkbox" id="app_notification">
                            <label class="form-check-label" for="app_notification"></label>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            SMS notifications
                            <div class="text-muted">
                                Receive SMS notification
                            </div>
                        </div>
                        <div class="form-check form-switch  ms-2">
                            <input class="form-check-input" v-model="data.sms_notification" :checked="data.sms_notification" type="checkbox" value="1" v-on:change="updateValue('sms_notification',data.sms_notification)" id="sms_notification">
                            <label class="form-check-label" for="sms_notification"></label>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
        <div class="listview-title mt-1">Profile Settings</div>
        <ul class="listview image-listview text inset mb-2">
            <li>
                <a href="/profile" class="item">
                    <div class="in">
                        <div>Update Profile</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" onclick="document.getElementById('logout').submit();" class="item">
                    <div class="in">
                        <div>Log out</div>
                    </div>
                </a>
                <form id="logout" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </li>
        </ul>




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