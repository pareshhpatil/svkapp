<div class="modal fade dialogbox" id="cancelride" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Ride</h5>
            </div>
            <form onsubmit="lod(true);" action="/passenger/ride/cancel" method="post">
                @csrf
                <div class="modal-body text-start mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="text1">Enter Reason</label>
                            <input type="text" name="message" class="form-control" placeholder="Enter cancel reason" maxlength="100">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                        <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-text-primary">CONFIRM</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade dialogbox" id="helpmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat</h5>
            </div>
            <form onsubmit="lod(true);" action="/passenger/help" method="post">
                @csrf
                <div class="modal-body text-start mb-2">
                    <div class="">
                        <div class="">

                            <a href="/chat/create/5/{{$data['ride_passenger']['ride_id']}}/1/{{$data['ride_passenger']['passenger_id']}}/0">
                                <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                    <div class="icon-wrap">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                    </div>
                                    <div>
                                        <strong>Supervisor</strong>
                                    </div>
                                </div>
                            </a>
                            <a href="/chat/create/5/{{$data['ride_passenger']['ride_id']}}/2/{{$data['ride_passenger']['passenger_id']}}/0">
                                <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                    <div class="icon-wrap">
                                        <ion-icon name="car-outline"></ion-icon>
                                    </div>
                                    <div>
                                        <strong>Driver</strong>
                                    </div>
                                </div>
                            </a>
                            <a href="/chat/create/5/{{$data['ride_passenger']['ride_id']}}/3/{{$data['ride_passenger']['passenger_id']}}/0">
                                <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                    <div class="icon-wrap">
                                        <ion-icon name="people-outline"></ion-icon>
                                    </div>
                                    <div>
                                        <strong>Co Passengers Group</strong>
                                    </div>
                                </div>
                            </a>

                            <a :href="'/chat/create/5/{{$data['ride']['id']}}/4/{{$data['ride_passenger']['passenger_id']}}/'+item.passenger_id" v-if="item.id!=data.ride_passenger.id" v-for="item in data.ride_passengers">
                                <div class="alert alert-imaged alert-outline-primary alert-dismissible fade show mb-2" role="alert">
                                    <div class="icon-wrap">
                                        <ion-icon name="person-outline"></ion-icon>
                                    </div>

                                    <div>
                                        <strong v-html="item.name"></strong>
                                    </div>

                                </div>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                        <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modalbox  dialogbox" id="sosmodel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emergency SOS</h5>
            </div>
            <form onsubmit="lod(true);" action="/passenger/sos" method="post">
                @csrf
                <div class="modal-body text-start mb-2 mt-1">
                    <div class="">
                        <div class="row">
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Disaster" class="custom-control-input form-check-input" id="em1">
                                    <label class="custom-control-label" for="em1">
                                        <img src="/assets/img/sos/1.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Accident" class="custom-control-input form-check-input" id="em2">
                                    <label class="custom-control-label" for="em2">
                                        <img src="/assets/img/sos/2.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Fire" class="custom-control-input form-check-input" id="em3">
                                    <label class="custom-control-label" for="em3">
                                        <img src="/assets/img/sos/3.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Alert" class="custom-control-input form-check-input" id="em4">
                                    <label class="custom-control-label" for="em4">
                                        <img src="/assets/img/sos/4.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Crime" class="custom-control-input form-check-input" id="em5">
                                    <label class="custom-control-label" for="em5">
                                        <img src="/assets/img/sos/5.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox image-checkbox">
                                    <input type="checkbox" name="emergency[]" value="Medical" class="custom-control-input form-check-input" id="em6">
                                    <label class="custom-control-label" for="em6">
                                        <img src="/assets/img/sos/6.png" alt="#" class="img-fluid">
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="text1">Message</label>
                            <input type="text" class="form-control" name="message" placeholder="Enter emergency details" maxlength="100">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="row">

                        <div class="listview-title mt-1">Notification</div>
                        <ul class="listview image-listview text">
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div>
                                            Notify Supervisor
                                        </div>
                                        <div class="form-check form-switch ms-2">
                                            <input name="notify_supervisor" value="1" readonly class="form-check-input" checked type="checkbox" id="SwitchCheckDefault5">
                                            <label class="form-check-label" for="SwitchCheckDefault5"></label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="in">
                                        <div>
                                            Notify your emergency contact
                                        </div>
                                        <div class="form-check form-switch ms-2">
                                            <input name="notify_emergency_contact" value="1" class="form-check-input" type="checkbox" id="SwitchCheckDefault2">
                                            <label class="form-check-label" for="SwitchCheckDefault2"></label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <input type="hidden" :value="data.ride_passenger.id" name="ride_passenger_id">
                        <input type="hidden" :value="data.ride_passenger.ride_id" name="ride_id">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toast-11" class="toast-box toast-center">
    <div class="in">
        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
        <div class="text">
            Thank you for Review
        </div>
    </div>
    <button type="button" onclick="closeT();" class="btn btn-sm  btn-text-light bg-red">CLOSE</button>
</div>

<div id="toast-15" class="toast-box toast-center">
    <div class="in">
        <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
        <div class="text">
            You will receive call shortly
        </div>
    </div>
    <button type="button" onclick="closeT(15);" class="btn btn-sm  btn-text-light bg-red">CLOSE</button>
</div>