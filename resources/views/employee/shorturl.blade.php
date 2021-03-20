@extends('layouts.employee')

@section('content')
<div class="row" id="insert">
    <form action="/employee/saveshorturl" method="post" id="customerForm" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-6">
            @isset($short_url)
            <div class="form-group">
                <label class="control-label col-md-4">Short URL<span class="required"> </span></label>
                <div class="col-md-7">
                    <a>{{$short_url}}</a>
                </div>
            </div>
            @endisset
            
            <div class="form-group">
                <label class="control-label col-md-4">Long URL<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="long_url" required="" value="" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>
                    <button id="savebutton" type="submit" class="btn btn-primary">Save</button>
                    <a href="#" class="btn btn-default">Close</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
