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
<div id="img-loader" style="display: none;">
    <h2 class="loading-icon text-primary">Uploading..</h2>
</div>
<div id="app">
    <div id="appCapsule" class="full-height" ref="scrollContainer">
        <div class="message-divider">
            {{$created_date}}
        </div>
        <div v-for="item in messages">
            <div v-if="item.user_id!=user_id" class="message-item">
                <img v-if="item.gender=='Male'" src="/assets/img/map-male.png" alt="avatar" class="avatar">
                <img v-if="item.gender=='Female'" src="/assets/img/map-female.png" alt="avatar" class="avatar">
                <div class="content">
                    <div class="title" v-html="item.name"></div>
                    <div v-if="item.type==1" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.type==2" class="bubble">
                        <a download="chat-file" :href="item.message" title="ImageName">
                            <img :src="item.message" alt="photo" class="imaged w160">
                        </a>
                    </div>
                    <div class="footer" v-html="item.time"></div>
                </div>
            </div>
            <div v-if="item.user_id==user_id" class="message-item user">
                <div class="content">
                    <div v-if="item.type==1" class="bubble" v-html="item.message">
                    </div>
                    <div v-if="item.type==2" class="bubble">
                        <a download="chat-file" :href="item.message" title="ImageName">
                            <img :src="item.message" alt="photo" class="imaged w160">
                        </a>
                    </div>
                    <div class="footer" v-html="item.time"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="chatFooter">
        <div class="text-info" id="loder" role="status"></div>


        <form id="frm" @submit="formSubmit" enctype="multipart/form-data">
            @csrf
            <input type="file" id="fileuploadInput" style="display: none;" name="file" v-on:change="onImageChange" accept=".png, .jpg, .jpeg">
            <a href="javascript:void(0);" class="btn btn-icon btn-text-secondary rounded">
                <ion-icon name="camera" role="img" class="md hydrated" onclick="document.getElementById('fileuploadInput').click();" aria-label="camera"></ion-icon>
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
</div>



@endsection



@section('footer')


<script>
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
                message_type: 1,
                image: ''
            }
        },

        mounted() {

            this.messages = JSON.parse('{!!json_encode($messages)!!}');
            this.count = this.messages.length;
            this.user_id = '{{$user_id}}';
            this.group_id = '{{$group_id}}';
            setInterval(this.fetchData, 3000);
            this.scrollToBottom();
        },
        methods: {
            onImageChange(e) {
                document.getElementById('img-loader').style.display = 'flex';
                this.image = e.target.files[0];
                this.message_type = 2;
                this.formSubmit();
                document.getElementById('img-loader').style.display = 'none';
            },
            async formSubmit(e) {
                try {
                    e.preventDefault();
                } catch (o) {}

                let currentObj = this;
                var msgs = [];
                if (this.message != '' || this.message_type == 2) {
                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }
                    let formData = new FormData();
                    formData.append('file', this.image);
                    formData.append('message', this.message);
                    formData.append('group_id', this.group_id);
                    let res = await axios.post('/ajax/chat/submit', formData, config);
                    this.messages = res.data;
                }

                this.message_type = 1;
                this.message = '';
                this.image = null;
                this.scrollToBottom();
            },

            async fetchData() {
                await axios.get('/ajax/chat/{{$link}}')
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
            }


        }
    })
</script>





@endsection