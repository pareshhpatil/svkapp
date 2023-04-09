@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <div class="row"  >
                    <form action="/admin/logsheet/getlogsheet" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <input type="text" name="date" readonly="" required="" value="{{$month}}" autocomplete="off" class="form-control form-control-inline month-picker" data-date-format="M yyyy" >
                                <div class="help-block"></div>
                            </div>
                            <div class="col-md-3">
                                <select name="vehicle_id" required class="form-control select2" data-placeholder="Select...">
                                    <option value="">Select vehicle</option>
                                    @foreach ($vehicle_list as $item)
                                    @if($vehicle_id==$item->vehicle_id)
                                    <option selected value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                    @else
                                    <option value="{{$item->vehicle_id}}">{{$item->name}}</option>
                                    @endif

                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>


                            <div class="col-md-3">
                                <select name="company_id" required class="form-control select2" data-placeholder="Select...">
                                    <option value="">Select comapny</option>
                                    @foreach ($company_list as $item)
                                    @if($company_id==$item->company_id)
                                    <option selected="" value="{{$item->company_id}}">{{$item->name}}</option>
                                    @else
                                    <option value="{{$item->company_id}}">{{$item->name}}</option>
                                    @endif

                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-3">
                                <button  type="submit" class="btn btn-primary">Generate </button>
                                <a href="/admin/logsheet" class="btn btn-default">Back </a>
                            </div>
                            <br>
                            <br>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        @yield('middle_content')
        
        <!-- END PAYMENT TRANSACTION TABLE -->

    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
</div>
@endsection
