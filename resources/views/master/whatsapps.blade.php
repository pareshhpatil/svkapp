@extends('layouts.app')
@section('content')
<div id="appCapsule" class="full-height">

    <div id="app" class="section full">

        <!-- waiting tab -->
        <ul class="listview image-listview flush">
            <li v-for="item in data" class="">
                <a :href="item.link" class="item ">
                    <div class="icon-box bg-success">
                        <ion-icon name="logo-whatsapp"></ion-icon>
                    </div>
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong v-html="item.name"></strong></div>
                            <div class="text-xsmall" v-html="item.last_update_date"></div>
                        </div>
                        <span class="badge badge-success "></span>
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
            this.data = JSON.parse('{!!json_encode($whatsapps)!!}');
        },
        methods: {

        }
    })
</script>
@endsection
