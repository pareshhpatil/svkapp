@extends('layouts.guest',['title'=>'Users'])
@section('content')
<div id="appCapsule" style="background-color: #ffffff;">

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Mobile</th>
          <th scope="col">Type</th>
          <th scope="col" class="text-end">Button</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $v)
        <tr>
          <th scope="row">{{$v['id']}}</th>
          <td>{{$v['name']}}</td>
          <td>{{$v['mobile']}}</td>
          <td>{{$v['user_type']}}</td>
          <td class="text-end">
            <a href="/user/switch/{{$v['link']}}" class="btn btn-success text-center">Login</a>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>


</div>
@endsection