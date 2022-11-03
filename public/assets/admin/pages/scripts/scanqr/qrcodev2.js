
var app = new Vue({
    el: '#app',
    data: {
        scanner: null,
        activeCameraId: null,
        cameras: [],
        scans: []
    },
    mounted: function () {
        var self = this;
        self.scanner = new Instascan.Scanner({video: document.getElementById('preview'), scanPeriod: 5});
        self.scanner.addListener('scan', function (content, image) {
            window.location.href = '/merchant/loyalty/detail/' + content;
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            var session_cam_id = window.sessionStorage.getItem('s_camera_id');
            self.cameras = cameras;
            if (cameras.length > 0) {
                var selectedcam = 0;
                cameras.forEach(function (value, key) {
                    if (session_cam_id == value.id)
                    {
                        selectedcam = key;
                    }
                });
                self.activeCameraId = cameras[selectedcam].id;
                self.scanner.start(cameras[selectedcam]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    },
    methods: {
        formatName: function (name) {
            return name || ' Camera ';
        },
        selectCamera: function (camera) {
            window.sessionStorage.setItem('s_camera_id', camera.id);
            this.activeCameraId = camera.id;
            this.scanner.start(camera);
        }
    }
});
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

