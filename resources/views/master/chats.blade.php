@extends('layouts.app')
@section('content')
<div id="appCapsule" class="full-height">

    <div id="app" class="section full">

        <!-- waiting tab -->
        <ul class="listview image-listview flush">
            <li v-for="item in data" class="">
                <a :href="item.link" class="item ">
                    <div class="icon-box bg-success">
                        <ion-icon name="chatbubble-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong v-html="item.name"></strong></div>
                            <div class="text-xsmall" v-html="item.datetime"></div>
                        </div>
                        <span class="badge badge-success "></span>
                    </div>
                </a>
            </li>
        </ul>
        <div class="text-center" >
            <br>
            <p class="text-center">Chat with us </p>
            <a href="/chat/create/5/0/1/{{Session::get('parent_id')}}/0" type="button" class="btn btn-icon btn-success me-1">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
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
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($chats)!!}');
        },
        methods: {

        }
    })
</script>
@endsection
