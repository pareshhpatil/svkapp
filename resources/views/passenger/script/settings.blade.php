<script>
    settings = new Vue({
        el: '#settings',
        data() {
            return {
                data: [],
                image: '',
                croppieImage: '',
                cropped: null,
                showcropper: false
            }
        },
        mounted() {

        },
        methods: {
            async loadData() {
                // var date = '';
                let res = await axios.get('/settings-data');
                this.data = res.data;
                if (this.data.icon == '') {
                    this.data.icon = '/assets/img/sample/avatar/avatar1.jpg';
                }
            },
            croppie(e) {
                this.showcropper = true;
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;

                var reader = new FileReader();
                reader.onload = e => {
                    this.$refs.croppieRef.bind({
                        url: e.target.result
                    });
                };

                reader.readAsDataURL(files[0]);
            },
            crop() {
                // Options can be updated.
                // Current option will return a base64 version of the uploaded image with a size of 600px X 450px.
                let options = {
                    type: 'base64',
                    size: {
                        width: 300,
                        height: 300
                    },
                    format: 'jpeg'
                };

                lo(true);
                let currentObj = this;

                this.$refs.croppieRef.result(options, output => {
                    image = this.croppieImage = output;

                    let formData = new FormData();
                    // image = document.getElementById('img').value;
                    formData.append('image', image);

                    const config = {
                        headers: {
                            'content-type': 'multipart/form-data'
                        }
                    }

                    axios.post('/upload/file/image', formData, config)
                        .then(function(response) {
                            currentObj.data.icon = response.data.image;
                            lo(false);
                        })
                        .catch(function(error) {
                            currentObj.output = error;
                            lo(false);
                        });
                });
                // alert(this.image);
                this.showcropper = false;
            },
            updateValue(col) {
                val = document.getElementById(col).checked;
                axios.get('/setting/update/' + col + '/' + val);
                if (col == 'dark_mode') {
                    var pageBody = document.querySelector("body");
                    if (val) {
                        pageBody.classList.add("dark-mode");
                    } else {
                        pageBody.classList.remove("dark-mode");
                    }
                }
            },
            onImageChange(e) {
                this.image = e.target.files[0];
                this.formSubmit();
            },
            formSubmit(e) {
                lo(true);
                let currentObj = this;

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                let formData = new FormData();
                image = document.getElementById('img').value;
                // formData.append('image', image);

                axios.post('/upload/file/image', formData, config)
                    .then(function(response) {
                        currentObj.data.icon = response.data.image;
                        lo(false);
                    })
                    .catch(function(error) {
                        currentObj.output = error;
                        lo(false);
                    });
            }
        }


    })
