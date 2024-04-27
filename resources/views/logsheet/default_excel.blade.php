@extends('layouts.admin')
@section('content')
<div class="panel ">
    <div class="panel-body" style="overflow: auto;">
        <div class="row"  >
            <table class="table table-bordered" style="font-size: 12px !important;color: black !important;text-align: center;line-height: 1;">
                <tbody>
                    <tr>
                        <td colspan="11" class="td-c " style="font-size: 20px;font-weight: bold;">{{$company_name}}</td>
                    </tr>
                    <tr>
                        <td colspan="11" class="td-c " style="font-size: 15px;font-weight: bold;">
                            {{$company->name}} 
                            <br>
                            Logsheet Entry for the month of {{$month}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="11" class="td-c" style="font-size: 15px;font-weight: bold;">
                            Summery of Car No: ({{$vehicle->car_type}}){{$vehicle->number}}
							@if($invoice->narrative!='')
							<br>
							{!!$invoice->narrative!!}
							@endif
                        </td>
                    </tr>
                    <tr>
                        <th class="td-c tds">DATE</th>
                        <th class="td-c tds">START KM</th>
                        <th class="td-c tds">END KM</th>
                        <th class="td-c tds">TOTAL KM</th>
                        <th class="td-c tds">START TIME</th>
                        <th class="td-c tds">CLOSE TIME</th>
                        <th class="td-c tds">TOTAL TIME</th>
                        <th class="td-c tds">EXTRA HRS</th>
                        <th class="td-c tds">Toll/ Parking</th>
                        <th class="td-c tds">Remark</th>
                    </tr>
                    @php($total_km=0)
                    @php($extra_hr=0)
                    @php($extra_min=0)
                    @php($toll=0)
                    @foreach($list as $item)
                    <tr>
                        @if ($item->start_km==0)
                        <td class="td-c tds">
                            {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                        </td>
                        <td class="td-c tds" colspan="5" style="font-weight: bold;"> {{$item->remark}}</td>
                        <td class="td-c tds"></td>
                        <td class="td-c tds"></td>
                        <td class="td-c tds"></td>
                        <td class="td-c tds"></td>
                        @else
                        <td class="td-c tds">
                            {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                        </td>
                        <td class="td-c tds">
                            {{$item->start_km}}
                        </td>
                        <td class="td-c tds">
                            {{$item->end_km}}
                        </td>
                        <td class="td-c tds">
                            @if ($item->total_km>0)
                            @php($total_km=$total_km+$item->total_km)
                            @endif
                            {{$item->total_km}}
                        </td>
                        <td class="td-c tds">
                            {{ Carbon\Carbon::parse($item->start_time)->format('h:i A')}}
                        </td>
                        <td class="td-c tds">
                            {{ Carbon\Carbon::parse($item->close_time)->format('h:i A')}}
                        </td>
                        <td class="td-c tds">
                            {{ Carbon\Carbon::parse($item->total_time)->format('H:i')}}
                        </td>
                        <td class="td-c tds">
                            @if($item->extra_time>'00:00:00')
                            @php($extra_hr=$extra_hr+Carbon\Carbon::parse($item->extra_time)->format('H'))
                            @php($extra_min=$extra_min+Carbon\Carbon::parse($item->extra_time)->format('i'))
                            {{ Carbon\Carbon::parse($item->extra_time)->format('H:i')}}
                            @endif
                        </td>
                        <td class="td-c tds">
                            @if($item->toll>0)
                            @php($toll=$toll+$item->toll)
                            {{$item->toll}}
                            @endif
                        </td>
                        <td class="td-c tds">
                            {{$item->remark}}
                        </td>
                        @endif

                    </tr>
                    @endforeach
                <tfoot>
                <th class="td-c tds"></th>
                <th class="td-c tds"></th>
                <th class="td-c tds">TOTAL KM</th>
                <th class="td-c tds">{{$total_km}}</th>
                <th class="td-c tds"></th>
                <th class="td-c tds"></th>
                <th class="td-c tds">EXTRA HRS</th>
                @php($extra_hour=($extra_min+($extra_hr*60))/60)
                <th class="td-c tds">{{$extra_hour}} </th>
                <th class="td-c tds">{{number_format($toll,2)}}</th>
                <th class="td-c tds"></th>
                </tfoot>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-6" style="max-width: 50%;">
                <table class="table table-bordered" style="font-size: 12px !important;color: black !important;">
                    <tbody>

                        <tr>
                            <th class="td-c tds">Particulars</th>
                            <th class="td-c tds">Unit</th>
                            <th class="td-c tds">Qty</th>
                            <th class="td-c tds">Rate(R.s)</th>
                            <th class="td-c tds">Amount</th>
                        </tr>
                        @foreach($logsheet_detail as $det)
                        <tr>
                            <td class="td-c tds">{{$det['particular_name']}}</td>
                            <td class="td-c tds">{{$det['unit']}}</td>
                            <td class="td-c tds">{{$det['qty']}}</td>
                            <td class="td-c tds">{{$det['rate']}}</td>
                            <td class="td-c tds">
                                @if($det['is_deduct']==1)-@endif{{$det['amount']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <th class="td-c tds"></th>
                    <th class="td-c tds"></th>
                    <th class="td-c tds"></th>
                    <th class="td-c tds">TOTAL</th>
                    <th class="td-c  tds" id="total_amount">{{$invoice->base_total}}</th>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
    <!-- /.panel-body -->
</div>
@endsection
