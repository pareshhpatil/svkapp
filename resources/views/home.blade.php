
@extends('layouts.guest',['title'=>'Home'])
    @section('content')
    <div class="">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="index.html" class="app-brand-link gap-2">
                <img src="/assets/img/svk/logo2.png" style="max-width: 100%;">           
                  
                </a>
              </div>
              <!-- /Logo -->
              <p class="mb-4">Please sign-in to your account and start the adventure</p>

              <form id="formAuthentication" class="mb-3" action="/login/verify" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Mobile or Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="user_name"
                    placeholder="Enter your mobile or username"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>
                <br>
   <br>
    <br>
 <a href="#" onclick="logout()">Logout</a>
  <a href="/app/trips" >Trips</a>
   <a href="https://app.svktrv.in/app/notification/216ac4ad077b42336974d69a00c1f9dc" >Notification</a>


<script>
function logout() {
  // Call the "logout" method in Flutter code
  window.flutter_inappwebview.callHandler('logout');
}
</script>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
@endsection
   