@extends('layouts.app1')
@section('content')



@include('passenger.new.dashboard')
@include('passenger.new.my-rides')
@include('passenger.new.book-ride')
@include('passenger.new.calendar')
@include('passenger.new.settings')



@endsection

@section('footer')

@include('passenger.script.dashboard')
@include('passenger.script.my-rides')
@include('passenger.script.book-ride')
@include('passenger.script.calendar')
@include('passenger.script.settings')

@endsection
