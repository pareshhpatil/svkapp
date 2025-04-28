@extends('layouts.guest',['title'=>'Install Mobile App'])
@section('content')
<div id="appCapsule">

  <div class="action-sheet-content text-center mt-2">
  <div class="row mb-1">
    <h4>
      Install App to track your cab driver, ride history and book a new ride.
</h4>
  </div>
    <div class="row mb-1">
      <div class="col">
        <a href="https://apps.apple.com/in/app/ride-track-app/id6449589190"><img src="/assets/img/ios2.png" alt="image" class="imaged w100"></a>
        <br>
        <br>
        <h2>IOS</h2>
      </div>

    </div>
    <div class="row mb-1">

      <div class="col mb-1">
        <a href="https://play.google.com/store/apps/details?id=com.sidhivinayak.travel.house"> <img src="/assets/img/android2.png" alt="image" class="imaged  w100"></a>
        <br>
        <br>
        <h2>Android</h2>
      </div>
    </div>

    <div class="row mb-1">
      <br>
      <br>
      <b>Thanks,</b>
      <div class="">
         <img src="https://admin.svktrv.in/dist/img/1681581649.png" alt="image" class="imaged  w200">
      </div>
    </div>
  </div>







</div>
@endsection