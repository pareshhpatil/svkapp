<script>
    rides = new Vue({
        el: '#app2',
        data() {
            return {
                data: [],
                cancel_booking_id: 0,
                approve_type: 'Approve',
                current_date: '{{$current_date}}'
            }
        },
        mounted() {
            // this.data = JSON.parse('{!!json_encode($rides)!!}');
        },
        methods: {
            async fetchDate(type) {
                // var date = '';
                let res = await axios.get('/date/fetch/' + this.current_date + '/' + type);
                this.current_date = res.data;

            },
            approve(item, type) {
                this.approve_type = type;
                document.getElementById('approve_type_title').innerHTML = type;
                document.getElementById('approve_booking_id').value = item.id;
                document.getElementById('approve_type').value = type;
            },
            cancel(item) {
                if (item.hours > 6 || item.status == 0) {
                    document.getElementById('no_show').value = '0';
                    document.getElementById('cancel_message').innerHTML = 'You want to cancel?';
                } else {
                    document.getElementById('no_show').value = '1';
                    document.getElementById('cancel_message').innerHTML = 'Your request will be sent for admin approval as this is Ad hoc request. Whether approved or rejected, you will receive a notification regarding the status of your request.';

                }
                document.getElementById('cancel_booking_id').value = item.id;
            },
            async loadData() {
                // var date = '';
                let res = await axios.get('/my-rides-data');
                this.data = res.data;
            }
        }
    })
    document.getElementById('tab-{{$type}}').click();
</script>
