@extends('layouts.app')
@section('content')
<style>
    #img-loader {
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
    }
</style>
<script src="https://unpkg.com/webtonative@1.0.56/webtonative.min.js"></script>

<div id="app">
    <div id="appCapsule" class="full-height" ref="scrollContainer">
        <!-- <div class="message-divider">
            {{$created_date}}
        </div> -->
        <div v-for="item in messages">
            <div v-if="item.type!='Sent'" class="message-item">
                <img v-if="item.gender=='Male'" src="/assets/img/map-male.png" alt="avatar" class="avatar">
                <img v-if="item.gender=='Female'" src="/assets/img/map-female.png" alt="avatar" class="avatar">
                <div class="content">
                    <div v-if="item.message_type=='text'" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.message_type=='contacts'" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.message_type=='image'" class="bubble">
                        <a download="chat-file" :href="item.message" title="ImageName">
                            <img :src="item.message" alt="photo" class="imaged w160">
                        </a>
                    </div>
                    <div v-if="item.message_type=='location'" v-on:click="window.location.assign(item.message, '_system');">
                        <img src="/assets/img/navigation.png" alt="photo" class="imaged w76">
                    </div>
                    <div v-if="item.message_type=='document'" v-on:click="window.location.assign(item.message, '_system');">
                        <img src="/assets/img/document.png" alt="photo" class="imaged w76">
                    </div>
                    <div v-if="item.message_type=='audio'">
                        <audio preload="auto" controls>
                            <source :src="item.message" type="audio/mpeg">
                            <source :src="item.message" type="audio/ogg">
                            Your browser does not support the audio tag.
                        </audio>
                        <a :href="item.message">Audio</a>

                    </div>
                    <div v-if="item.message_type=='video'">
                        <video width="250" controls>
                            <source :src="item.message" type="video/mp4">
                        </video>

                    </div>
                    <div class="footer" style="opacity: 1; font-size: 15px;" v-if="item.reaction" v-html="item.reaction"></div>
                    <div class="footer" v-html="item.time"></div>

                </div>
            </div>
            <div v-if="item.type=='Sent'" class="message-item user">
                <div class="content" style="text-align: right;">
                    <div v-if="item.message_type=='text'" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.message_type=='contacts'" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.message_type=='image'" class="bubble">
                        <a
                            :download="getFileName(item.message)"
                            :href="getDownloadUrl(item.message)"
                            title="ImageName">
                            <img :src="item.message" alt="photo" class="imaged w160">
                        </a>
                    </div>
                    <div v-if="item.message_type=='location'" v-on:click="window.location.assign(item.message, '_system');">
                        <img src="/assets/img/navigation.png" alt="photo" class="imaged w76">
                    </div>
                    <div v-if="item.message_type=='document'" v-on:click="window.location.assign(item.message, '_system');">
                        <img src="/assets/img/document.png" alt="photo" class="imaged w76">
                    </div>
                    <div v-if="item.message_type=='audio'">
                        <audio preload="auto" controls>
                            <source :src="item.message" type="audio/mpeg">

                            <source :src="item.message" type="audio/ogg">
                            Your browser does not support the audio tag.
                        </audio>
                        <a :href="item.message">Audio</a>

                    </div>
                    <div class="footer" style="opacity: 1; font-size: 15px;" v-if="item.reaction" v-html="item.reaction"></div>
                    <div class="footer" v-html="item.time"></div>
                    <div class="footer" v-html="item.status"></div>

                </div>
            </div>
        </div>
        <div v-if="loader==true" class="message-item user">
            <div class="content">
                <div class="bubble">
                    <img src="/assets/img/loading.gif" alt="photo" class="imaged w160">
                </div>
            </div>
        </div>

    </div>
    <div class="chatFooter">
        <div class="text-info" id="loder" role="status"></div>


        <form id="frm" @submit="formSubmit" enctype="multipart/form-data">
            @csrf
            <input type="file" id="fileuploadInput" style="display: none;" name="file" v-on:change="onImageChange" accept=".png, .jpg, .jpeg">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#DialogIconedButton" class="btn btn-icon btn-text-secondary rounded">
                <ion-icon name="add-outline"></ion-icon>
            </a>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <input type="text" v-model="message" name="message" class="form-control" placeholder="Type a message...">
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                    </i>
                </div>
            </div>
            <button type="button" v-on:click="formSubmit" class="btn btn-icon btn-primary rounded">
                <ion-icon name="arrow-forward-outline" role="img" class="md hydrated" aria-label="arrow forward outline"></ion-icon>
            </button>
        </form>
    </div>


    <div class="modal fade dialogbox" id="DialogIconedButton" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-footer">
                    <div class="btn-list">
                        <a href="javascript:void(0);" onclick="document.getElementById('fileuploadInput').click();" class="btn btn-text-primary btn-block" data-bs-dismiss="modal">
                            <ion-icon name="image-outline"></ion-icon>
                            Photo
                        </a>
                        <a href="javascript:void(0);" v-on:click="sendLocation" class="btn btn-text-primary btn-block" data-bs-dismiss="modal">
                            <ion-icon name="navigate-outline"></ion-icon>
                            Location
                        </a>
                        <a href="javascript:void(0);" class="btn btn-text-danger btn-block" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                            CANCEL
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






@endsection



@section('footer')


<script>
    var mylatitude = '';
    var mylongitude = '';

    function success(pos) {
        const crd = pos.coords;
        mylatitude = crd.latitude;
        mylongitude = crd.longitude;
    }


    function fileDownload(file_name, path) {
        window.WTN.downloadBlobFile({
            fileName: file_name,
            downloadUrl: path
        })
    }

    function error(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    function startLocation() {
        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };
        navigator.geolocation.getCurrentPosition(success, error, options);

    }


    var height = 900;
    new Vue({
        el: '#app',
        data() {
            return {
                messages: [],
                count: 0,
                user_id: '',
                message: '',
                group_id: '',
                loader: false,
                message_type: 1,
                image: ''
            }
        },

        mounted() {

            this.messages = JSON.parse('{!!$messages!!}');
            this.count = this.messages.length;
            this.user_id = '{{$user_id}}';
            this.group_id = '{{$group_id}}';
            setInterval(this.fetchData, 3000);
            setTimeout(() => this.scrollToBottom(), 1000);
        },
        methods: {
            onImageChange(e) {
                this.scrollToBottom();
                this.loader = true;
                this.image = e.target.files[0];
                this.message_type = 2;
                this.formSubmit();

            },
            sendLocation() {
                this.scrollToBottom();
                this.loader = true;
                this.message_type = 3;
                startLocation();
                setTimeout(() => this.formSubmit(), 5000);
            },
            async formSubmit(e) {
                try {
                    e.preventDefault();
                } catch (o) {}

                let currentObj = this;
                var msgs = [];


                if (this.message_type == 3 && mylatitude != '') {
                    this.message = 'https://www.google.com/maps/search/?api=1&query=' + mylatitude + ',' + mylongitude;
                }
                var text_message = this.message;
                this.message = '';

                if (text_message != '' || this.message_type == 2) {
                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }
                    let formData = new FormData();
                    formData.append('message_type', this.message_type);
                    formData.append('file', this.image);
                    formData.append('message', text_message);
                    formData.append('group_id', this.group_id);
                    formData.append('mylatitude', mylatitude);
                    formData.append('mylongitude', mylongitude);
                    let res = await axios.post('/ajax/whatsapp/submit', formData, config);
                    this.messages = res.data;
                }

                this.message_type = 1;

                this.image = null;
                this.scrollToBottom();
                this.loader = false;
            },

            async fetchData() {
                await axios.get('/ajax/whatsapp/{{$link}}')
                    .then(response => {
                        // Update your data with the received response
                        if (response.data.length > this.count) {
                            this.count = response.data.length;
                            this.messages = response.data;
                            this.scrollToBottom();
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            },
            scrollToBottom() {
                window.scrollTo(0, document.body.scrollHeight);
            },

            getFileName(url) {
                try {
                    const urlObj = new URL(url);
                    const pathname = urlObj.pathname;
                    const filename = pathname.substring(pathname.lastIndexOf('/') + 1) || 'chat-file';
                    return filename;
                } catch (e) {
                    return 'chat-file';
                }
            },
            getDownloadUrl(originalUrl) {
                try {
                    const filename = this.getFileName(originalUrl);
                    const hasQuery = originalUrl.includes('?');
                    return `${originalUrl}${hasQuery ? '&' : '?'}filename=${filename}`;
                } catch (e) {
                    return originalUrl;
                }
            }
        }
    })
</script>





@endsection