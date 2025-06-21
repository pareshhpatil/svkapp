@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">

    <div id="app" class="section full">

        <ul class="listview image-listview flush">
            <li v-for="(v, index) in list" :key="index">
                <a :href="v.link" class="item">
                    <div class="icon-box" :class="getClass(v.type)">
                        <ion-icon name="notifications-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong>@{{ v.title }}</strong></div>
                            <div class="text-small mb-05">@{{ v.message }}</div>
                            <div class="text-xsmall">@{{ v.created_date }}</div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
        <div v-if="loadmore" class="section mt-2 mb-2">
            <a href="#" @click="fetchData" class="btn btn-primary btn-block btn-lg">Load More</a>
        </div>
    </div>



    @endsection



    @section('footer')

    <script>
        new Vue({
            el: '#app',
            data: {
                list: JSON.parse('{!!$list!!}'),
                offset: {{$limit}},
                loadmore: true,
            },
            methods: {
                getClass(type) {
                    if (type === 1) return 'bg-success';
                    if (type === 2 || type === 3) return 'bg-warning';
                    if (type === 4) return 'bg-danger';
                    return '';
                },
                async fetchData() {
                    await axios.get('/notifications/0/' + this.offset)
                        .then(response => {
                            // Update your data with the received response
                            if (response.data.length > 0) {
                                this.list = [...this.list, ...response.data];
                                this.offset += {{$limit}};
                                this.scrollToBottom();
                            }
                            else{
                                this.loadmore = false;
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
        });
    </script>
    @endsection