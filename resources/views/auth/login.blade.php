@extends('layouts.guest',['title'=>'Login'])
@section('content')
<div class="">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login -->
      <div class="card">


        <div class="card-body">
          <!-- Logo -->
          <img src="https://ridetrack.in/images/logo.png?v=3" style="max-width: 20%;position: absolute;">
          <div class="app-brand justify-content-center mb-4 mt-2">
            <a href="index.html" class="app-brand-link gap-2">
              <img src="assets/img/banner.png" style="max-width: 100%;">

            </a>
          </div>
          <!-- /Logo -->
          <p class="mb-4">Please sign-in to your account and start the adventure</p>

          <form id="formAuthentication" class="mb-3" action="/login/verify" method="POST">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Mobile or Username</label>
              <input type="text" class="form-control" id="email" name="user_name" placeholder="Enter your mobile or username" autofocus />
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
            <div class="mb-3">

            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
@endsection