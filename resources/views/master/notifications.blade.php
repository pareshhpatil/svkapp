@extends('layouts.app')
@section('content')

<div id="appCapsule" class="full-height">

    <div class="section full">

        <ul class="listview image-listview flush">
            @foreach($list as $v)
            @php 
            if($v->type==1)
            {
                $class='bg-success';
            }elseif($v->type==2)
            {
                $class='bg-warning';
            }elseif($v->type==3)
            {
                $class='bg-warning';
            }elseif($v->type==4)
            {
                $class='bg-danger';
            }
            @endphp
            <li>
                <a href="{{$v->link}}" class="item">
                    <div class="icon-box {{$class}}">
                    <ion-icon name="notifications-outline" role="img" class="md hydrated" aria-label="notifications outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>
                            <div class="mb-05"><strong>{{$v->title}}</strong></div>
                            <div class="text-small mb-05">{{$v->message}}</div>
                            <div class="text-xsmall">{{$v->created_date}}</div>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>

    </div>



    @endsection



    @section('footer')
    @endsection