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
                    <form action="/master/save/driver" method="post">
                        @csrf
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="text4b">Name</label>
                                <input type="text" required name="name" maxlength="100" class="form-control" id="text4b" placeholder="Enter name">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="email4bs">Mobile</label>
                                <input type="text" required name="mobile" class="form-control" id="email4bs" inputmode="numeric" pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Mobile number">

                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4b">Location</label>
                                <input type="text" name="location" maxlength="45" class="form-control" id="select4b" placeholder="Enter location">
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="email4ba">Email</label>
                                <input type="email" name="email" maxlength="45" class="form-control" id="email4ba" placeholder="Enter email">
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="textarea4b">Address</label>
                                <textarea id="textarea4b" name="address" rows="2" class="form-control" placeholder="Enter address"></textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
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