<script>
    bookride = new Vue({
        el: '#bookride',
        data() {
            return {
                data: [],
                type: 'Pickup',
                pickup: 'Home',
                drop: 'Office',
                shifts: [],
                selected: '',
                allshifts: []
            }
        },
        mounted() {

        },
        methods: {
            changeMode() {
                this.selected = '';
                if (this.type == 'Pickup') {
                    this.type = 'Drop';
                    this.pickup = 'Office';
                    this.drop = 'Home';
                } else {
                    this.type = 'Pickup';
                    this.pickup = 'Home';
                    this.drop = 'Office';
                }
                this.shifts = this.allshifts[this.type];
            },
            async loadData() {
                // var date = '';
                let res = await axios.get('/book-ride-data');
                this.data = res.data;
                this.allshifts = res.data.array;
                this.shifts = this.allshifts[this.type];
                console.log(this.shifts);
            }
        }
    });

    function validateDate() {
        var currentDate = new Date();
        currentDate.setHours(currentDate.getHours() + 6);
        var updatedDate = new Date(document.getElementById('date').value + ' ' + document.getElementById('shift_time').value);
        if (currentDate > updatedDate) {
            document.getElementById("dialogclick").click();
            document.getElementById("status").value = '0';
            return false;
        } else {
            document.getElementById("status").value = '1';
        }
    }

    var today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);
</script>
