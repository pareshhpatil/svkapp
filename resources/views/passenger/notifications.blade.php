@extends('layouts.app')
@section('content')
<div id="appCapsule" class="full-height">

    <div id="app" class="section full">

        <!-- waiting tab -->
        <ul class="listview image-listview flush">
            <li v-for="item in data.notification" class="">
                <a :href="item.link" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="notifications-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong v-html="item.title"></strong></div>
                            <div class="text-small mb-05" v-html="item.message"></div>
                            <div class="text-xsmall" v-html="item.created_date"></div>
                        </div>
                        <span class="badge badge-primary "></span>
                    </div>
                </a>
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
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {

        }
    })
</script>
@endsection