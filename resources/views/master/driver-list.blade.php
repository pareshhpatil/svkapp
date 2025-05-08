@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">

    <div id="app">
        <div class="extraHeader">
            <form class="search-form">
                <div class="form-group searchbox">
                    <input type="text" v-model="search" class="form-control">
                    <i class="input-icon">
                        <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
                    </i>
                </div>
            </form>
        </div>
        <div class="section mt-1 mb-2">
            <div class="section-title">Found 43 results for "<span class="text-primary">Deposit</span>"</div>
            <div class="card">
                <ul class="listview image-listview media transparent flush">
                    <li v-for="driver in filteredDrivers" :key="driver.id">
                        <a :href="driver.link" class="item">
                            <div class="imageWrapper">
                                <img :src="driver.photo" v-if="driver.photo!='' && driver.photo!=null" alt="image" class="imaged w64">
                                <img src="https://app.svktrv.in/assets/img/map-male.png" v-if="driver.photo==null" alt="image" class="imaged w64">
                            </div>
                            <div class="in">
                                <div>
                                    <div v-text="driver.name"></div>
                                    <div class="text-muted" v-text="driver.mobile"></div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- <div class="section mb-2">
    <a href="#" class="btn btn-block btn-secondary">Load More</a>
</div> -->

    @endsection



    @section('footer')
    <script>
        new Vue({
            el: '#app',
            data: {
                search: '',
                drivers: @json($drivers) // Laravel injects driver list here
            },
            computed: {
                filteredDrivers() {
                    return this.drivers.filter(driver =>
                        driver.name.toLowerCase().includes(this.search.toLowerCase())
                    )
                }
            }
        })
    </script>
    @endsection