<script>
    dashboard = new Vue({
        el: '#app',
        data() {
            return {
                data: [],
                current_rating: 0
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {
            rating(rating, id) {
                this.current_rating = rating;
                axios.get('/passenger/ride/rating/' + id + '/' + rating);
                toastbox('toast-11');
            },
            call(mobile) {
                axios.get('/call/' + mobile);
                toastbox('toast-15');
            },
            async loadData() {
                // var date = '';
                let res = await axios.get('/dashboard-data');
                this.data = res.data;
            }


        }
    })
</script>
