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
                        <span v-if="item.pending_message>0" class="badge badge-success" style="margin-right: 10px; margin-left: -10px;" v-html="item.pending_message"></span>

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
            setInterval(this.fetchData, 5000);
        },
        methods: {
            async fetchData() {
                await axios.get('/whatsapp/getdata/1')
                    .then(response => {
                        // Update your data with the received response
                        this.data = response.data;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    })
</script>
@endsection
