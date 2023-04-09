@extends('layouts.admin')
@section('content')
<form action="" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="panel panel-primary">
        <div class="panel-body" style="overflow: auto;">
            <div class="row"  >
                <table class="table table-bordered" style="font-size: 12px !important;color: black !important;text-align: center;">
                    <tbody>
                        <tr>
                            <th class="td-c">DATE</th>
                            <th class="td-c">Employee</th>
                            <th class="td-c">Company</th>
                            <th class="td-c">Vehicle</th>
                            <th class="td-c">START KM</th>
                            <th class="td-c">END KM</th>
                            <th class="td-c">TOTAL KM</th>
                            <th class="td-c">START TIME</th>
                            <th class="td-c">CLOSE TIME</th>
                            <th class="td-c">Toll/ Parking</th>
                            <th class="td-c">Remark</th>
                            <th class="td-c">Action</th>
                        </tr>
                        @php($toll=0)
                        @foreach($list as $item)
                        <tr>
                            <td class="td-c">
                                {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                            </td>
                            <td class="td-c">
                                {{$item->user_name}}
                            </td>
                            <td class="td-c">
                                {{$item->company_name}}
                            </td>
                            <td class="td-c">
                                {{$item->vehicle_name}}
                            </td>
                            <td class="td-c">
                                {{$item->start_km}}
                            </td>
                            <td class="td-c">
                                {{$item->end_km}}
                            </td>
                            <td class="td-c">
                                {{$item->total_km}}
                            </td>
                            <td class="td-c">
                                {{ Carbon\Carbon::parse($item->start_time)->format('h:i A')}}
                            </td>
                            <td class="td-c">
                                {{ Carbon\Carbon::parse($item->close_time)->format('h:i A')}}
                            </td>
                            <td class="td-c">
                                @if($item->toll>0)
                                @php($toll=$toll+$item->toll)
                                {{$item->toll}}
                                @endif
                            </td>
                            <td class="td-c">
                                {{$item->remark}}
                            </td>
                            <td class="td-c">
                                <input type="checkbox" value="{{$item->logsheet_id}}" name="item[]">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <!-- /.panel-body -->
    </div>
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-danger pull-right" onclick="return confirm('Are you sure to delete?');" name="delete">Delete</button>
            <button type="submit" style="margin-right: 20px;" onclick="return confirm('Are you sure to approve?');" class="btn btn-success pull-right" name="approve">Approve</button>
            
        </div>
    </div>
</form>
@endsection
