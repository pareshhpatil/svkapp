@extends('logsheet.generate_bill')
@section('middle_content')
@isset($company)
@php($extra_hour=0)
@php($toll=0)
@if(count($list)>0)
<div class="panel panel-primary">
    <div class="panel-body" style="overflow: auto;">
        <div class="row">
            <table class="table table-bordered" style="font-size: 12px !important;color: black !important;text-align: center;">
                <tbody>
                    <tr>
                        <th class="td-c">DATE</th>
                        <th class="td-c">START KM</th>
                        <th class="td-c">END KM</th>
                        <th class="td-c">TOTAL KM</th>
                        <th class="td-c">START TIME</th>
                        <th class="td-c">CLOSE TIME</th>
                        <th class="td-c">TOTAL TIME</th>
                        <th class="td-c">EXTRA HRS</th>
                        <th class="td-c">Toll/ Parking</th>
                        <th class="td-c">Remark</th>
                        <th class="td-c">Action</th>
                    </tr>
                    @php($total_km=0)
                    @php($extra_hr=0)
                    @php($extra_min=0)
                    @php($toll=0)
                    @foreach($list as $item)
                    <tr>
                        <td class="td-c">
                            {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                        </td>
                        <td class="td-c">
                            {{$item->start_km}}
                        </td>
                        <td class="td-c">
                            {{$item->end_km}}
                        </td>
                        <td class="td-c">
                            @if ($item->total_km>0)
                            @php($total_km=$total_km+$item->total_km)
                            @endif
                            {{$item->total_km}}
                        </td>
                        <td class="td-c">
                            {{ Carbon\Carbon::parse($item->start_time)->format('h:i A')}}
                        </td>
                        <td class="td-c">
                            {{ Carbon\Carbon::parse($item->close_time)->format('h:i A')}}
                        </td>
                        <td class="td-c">
                            @if($item->total_time>'00:00:00')
                            {{ Carbon\Carbon::parse($item->total_time)->format('H:i')}}
                            @endif
                        </td>
                        <td class="td-c">
                            @if($item->extra_time>'00:00:00')
                            @php($extra_hr=$extra_hr+Carbon\Carbon::parse($item->extra_time)->format('H'))
                            @php($extra_min=$extra_min+Carbon\Carbon::parse($item->extra_time)->format('i'))
                            {{ Carbon\Carbon::parse($item->extra_time)->format('H:i')}}
                            @endif
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
                            <a onclick="return confirm('Are you sure?')?deleteEntry('{{$item->link}}',this):'';" href="#">Delete</a>
                        </td>

                    </tr>
                    @endforeach
                <tfoot>
                    <th class="td-c"></th>
                    <th class="td-c"></th>
                    <th class="td-c">TOTAL KM</th>
                    <th class="td-c">{{$total_km}}</th>
                    <th class="td-c"></th>
                    <th class="td-c"></th>
                    <th class="td-c">EXTRA HRS</th>
                    @php($extra_hour=($extra_min+($extra_hr*60))/60)
                    <th class="td-c">{{$extra_hour}} </th>
                    <th class="td-c">{{number_format($toll,2)}}</th>
                    <th class="td-c"></th>
                    <th class="td-c"></th>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.panel-body -->
</div>
@endif


@endisset
@endsection