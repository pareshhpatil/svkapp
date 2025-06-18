@extends('layouts.app',['title'=>'Users'])
@section('content')
<div id="appCapsule" style="background-color: #ffffff;">
<div id="app">
  <form class="search-form">
    <div class="form-group searchbox">
      <input type="text" v-model="search" class="form-control" placeholder="Search...">
      <i class="input-icon icon ion-ios-search"></i>
    </div>
  </form>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Mobile</th>
          <th scope="col">Type</th>
          <th scope="col" class="text-end">Button</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="emp in filteredAndSorted">
          <th scope="row" v-html="emp.id"></th>
          <th scope="row" v-html="emp.name"></th>
          <th scope="row" v-html="emp.mobile"></th>
          <th scope="row" > <span v-html="emp.user_type"></span>/<span v-html="emp.project_id"></span></th>
           <td class="text-end">
            <a :href="`/user/switch/${emp.link}`" class="btn btn-success text-center">Login</a>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
  </div>




  


</div>
@endsection
@section('footer')
<script src="https://admin.ridetrack.in/assets/vendor/libs/jquery/jquery.js"></script>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                rcmsg: '',
                search: '',
                notloded: true,
                passenger_id: 0,
                emp_title: '',
                start_time: '',
                remove_id: 0,
                driver: {
                    name: '',
                    mobile: ''
                },
                escort: {
                    name: '',
                    mobile: ''
                },
                vehicle: {
                    number: '',
                    car_type: 'Sedan',
                    brand: ''
                },
                filter_passengers: [],
                passengers: []
            }
        },

        mounted() {
            this.passengers = JSON.parse('{!!json_encode($data)!!}');
        },
        computed: {
            filteredAndSorted() {
                // function to compare names
                function compare(a, b) {
                    if (a.name < b.name) return -1;
                    if (a.name > b.name) return 1;
                    return 0;
                }

                return this.passengers.filter(emp => {
                    return emp.name.toLowerCase().includes(this.search.toLowerCase())
                }).sort(compare)
            }
        }
        
    })



</script>
@endsection