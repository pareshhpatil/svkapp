@extends('layouts.app')
@section('content')

<div id="appCapsule" class="extra-header-active full-height">

    <div id="app" class="section tab-content mb-1">

        <div class="card">
            <div class="card-body">
                <form action="/ridesave" method="post">
                    @csrf
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="text4b">Pickup/Drop</label>
                            <select name="type" required class="form-select form-select-lg currency">
                                <option value="Pickup" selected="">Pickup</option>
                                <option value="Drop">Drop</option>
                            </select>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Date</label>
                            <input type="date" required name="date" class="form-control" id="email4b" placeholder="Select Date">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="email4b">Time</label>
                            <input type="time" required name="time" class="form-control" id="email5b" placeholder="Select Time">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>



                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label" for="textarea4b">Note</label>
                            <textarea id="textarea4b" name="note" rows="2" class="form-control" placeholder="Enter note"></textarea>
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                            </i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">Confirm</button>
                            </div>
                            <div class="col-6">
                                <a href="/dashboard" class="btn btn-lg btn-outline-secondary btn-block">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>




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
            }
        },
        mounted() {
            this.data = JSON.parse('{!!json_encode($data)!!}');
        },
        methods: {

        }
    })
</script>
@endsection