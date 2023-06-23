@extends('layouts.app')
@section('content')
<script src="https://unpkg.com/vue-croppie/dist/vue-croppie.js"></script>
<link rel="stylesheet" href="https://unpkg.com/croppie/croppie.css">

<div id="appCapsule" class="extra-header-active full-height">
    <div id="app">
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



        <div class="listview-title mt-1">Theme</div>
        <ul class="listview image-listview text inset no-line">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            Dark Mode
                        </div>
                        <div class="form-check form-switch  ms-2">
                            <input class="form-check-input " @if($data->dark_mode==1) checked @endif value="1" v-on:change="updateValue('dark_mode')" type="checkbox" id="dark_mode">
                            <label class="form-check-label" for="dark_mode"></label>
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
                            <input class="form-check-input" @if($data->app_notification==1) checked @endif value="1" v-on:change="updateValue('app_notification')" type="checkbox" id="app_notification">
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

                            <input class="form-check-input" @if($data->sms_notification==1) checked @endif type="checkbox" value="1" v-on:change="updateValue('sms_notification')" id="sms_notification">
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
<script src="https://unpkg.com/webtonative@1.0.43/webtonative.min.js"></script>




<script>
    function start() {
        window.WTN.backgroundLocation.start({
            callback: false,
            apiUrl: "https://app.svktrv.in/app/ping",
            timeout: 10,
            data: "userid1",
            backgroundIndicator: true,
            pauseAutomatically: true,
            distanceFilter: 0.0,
            desiredAccuracy: "best",
            activityType: "other",
        });
    }

    function stop() {
        window.WTN.backgroundLocation.stop();
    }

    const {
        Messaging: FirebaseMessaging
    } = window.WTN.Firebase

    FirebaseMessaging.getFCMToken({
        callback: function(data) {
            document.getElementById('token').innerHTML = data.token;
            //store it in your backend to send notification
        }
    })

    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                image: '',
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
            updateValue(col) {
                val = document.getElementById(col).checked;
                axios.get('/setting/update/' + col + '/' + val);
                if (col == 'dark_mode') {
                    var pageBody = document.querySelector("body");
                    if (val) {
                        pageBody.classList.add("dark-mode");
                    } else {
                        pageBody.classList.remove("dark-mode");
                    }
                }
            },
            onImageChange(e) {
                this.image = e.target.files[0];
                this.formSubmit();
            },
            formSubmit(e) {
                lo(true);
                let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let formData = new FormData();
                image = document.getElementById('img').value;
                alert(image);
                // formData.append('image', image);

                axios.post('/upload/file/image', formData, config)
                    .then(function(response) {
                        currentObj.data.icon = response.data.image;
                        lo(false);
                    })
                    .catch(function(error) {
                        currentObj.output = error;
                        lo(false);
                    });
            }
        }


    })
</script>
@endsection