@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">

    <div id="app" class="section ">
        @if(session()->has('message'))
        <div class="alert alert-outline-info alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="mt-2 mb-2">
            <div class="card">
                <div class="card-body">
                    <form action="/master/save/vehicle" method="post">
                        @csrf
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="text4b">Vehicle number</label>
                                <input type="text" required name="number" maxlength="20" class="form-control" id="text4b" placeholder="Eg. MH 04 GD 8159">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="email4bs">Type</label>
                                <select required name="car_type" class="form-control" placeholder="Car type">
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4b">Brand</label>
                                <input type="text" name="brand" maxlength="45" class="form-control" placeholder="Eg. Ertiga">
                            </div>
                        </div>

                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary btn-block ">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>









    </div>



    @endsection



    @section('footer')








    @endsection