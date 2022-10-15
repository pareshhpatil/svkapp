@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    <form action="/admin/location/save" method="post" id="customerForm" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4">Company name<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="company_id" required class="form-control select2" data-placeholder="Select...">
                        <option value="">Select Company</option>
                        @foreach ($company_list as $item)
                        <option value="{{$item->company_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Location<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" name="name" required="" value="" class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-4"></div>
                <div class="col-md-7">
                    <h4 id="status"></h4>
                    <p id="loaded_n_total"></p>

                    <a href="/admin/location/list" class="btn btn-default pull-right">Close</a>
                    <button id="savebutton" type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection