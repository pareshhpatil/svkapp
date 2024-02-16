<script src="https://unpkg.com/vue-croppie/dist/vue-croppie.js"></script>
<link rel="stylesheet" href="https://unpkg.com/croppie/croppie.css">

<div id="settings" style="display: none;">
    <div id="appCapsule" class="extra-header-active full-height">
        <div>
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
                                <input class="form-check-input " @if($settings->dark_mode==1) checked @endif value="1" v-on:change="updateValue('dark_mode')" type="checkbox" id="dark_mode">
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
                                <input class="form-check-input" @if($settings->app_notification==1) checked @endif value="1" v-on:change="updateValue('app_notification')" type="checkbox" id="app_notification">
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

                                <input class="form-check-input" @if($settings->sms_notification==1) checked @endif type="checkbox" value="1" v-on:change="updateValue('sms_notification')" id="sms_notification">
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
</div>
