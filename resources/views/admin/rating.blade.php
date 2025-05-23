@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">
    <div id="app" class="section tab-content mt-2 mb-1">
        <!-- waiting tab -->
        <div class="transactions">
            <!-- item -->
            <a v-if="data.length" v-for="item in data" :href="item.link" class="item">
                <div class="detail">
                    <img v-if="!item.photo" src="/assets/img/driver.png" alt="img" class="image-block imaged w48">
                    <img v-if="item.photo" :src="item.photo" alt="img" class="image-block imaged w48">
                    <div>
                        <strong v-html="item.name"></strong>
                        <p><span v-html="item.employee_name"></span>-<span v-html="item.datetime"></span></p>
                        <div class="full-star-ratings jq-ry-container" data-rateyo-full-star="true">
                            <div class="jq-ry-group-wrapper">
                                <div class="jq-ry-rated-group jq-ry-group" style="width: 100%;">
                                    <span v-for="count in 5">
                                        <svg v-if="count<=item.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#e8481e">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg>
                                        <svg v-if="count>item.rating" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="15px" height="15px" fill="#dbdade">
                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 ">
                                            </polygon>
                                        </svg>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right">
                    <ion-icon name="chevron-forward-outline" role="img" class="md hydrated"></ion-icon>
                </div>
            </a>

            <div v-if="!data.length">
                <img src="/assets/img/no-record.png" alt="img" style="max-width: 100%;" class="">
                <h3 class="text-center">No past rides</h3>
            </div>

            <!-- * item -->
        </div>
        <!-- * waiting tab -->




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
                cancel_booking_id: 0,
                approve_type: 'Approve'
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($ratings)!!}');
        },

    })
</script>

@endsection