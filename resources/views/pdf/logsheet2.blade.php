@extends('layouts.pdf')
@section('content')

<div class="panel " style="margin-left: -50px;margin-right: -50px;margin-top: -50px;margin-bottom: -50px;">
    <div class="panel-body">
        <div class="row"  >
            <div class="col-md-12">
                <table class="table table-bordered" style="font-size: 10px !important;color: black !important;">
                <tbody>
                    <tr>
                        <td colspan="10" class="td-c " style="font-size: 17px;font-weight: bold; padding: 10px;">{{$company_name}}</td>
                    </tr>
                    <tr>
                        <td colspan="10" class="td-c " style="font-size: 11px;font-weight: bold;padding: 5px;">
                            {{$company->name}} 
                            <br>
                            Logsheet Entry for the month of {{$month}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="td-c" style="font-size: 11px;font-weight: bold;padding: 5px;">
                            Summary of Car No: ({{$vehicle->car_type}}){{$vehicle->number}}
							@if($invoice->narrative!='')
							<br>
							{{$invoice->narrative}}
							@endif
                        </td>
                    </tr>
                    <tr>
                        <th class="td-c tds" style="font-size: 10px;">DATE</th>
                        <th class="td-c tds" style="font-size: 10px;">START KM</th>
                        <th class="td-c tds" style="font-size: 10px;">END KM</th>
                        <th class="td-c tds" style="font-size: 10px;">TOTAL KM</th>
                        <th class="td-c tds" style="font-size: 10px;">START TIME</th>
                        <th class="td-c tds" style="font-size: 10px;">CLOSE TIME</th>
                        <th class="td-c tds" style="font-size: 10px;">TOTAL TIME</th>
                        <th class="td-c tds" style="font-size: 10px;">EXTRA HRS</th>
                        <th class="td-c tds" style="font-size: 10px;">Toll/ Parking</th>
                        <th class="td-c tds" style="font-size: 10px;">Remark</th>
                    </tr>
                    @php($total_km=0)
                    @php($extra_hr=0)
                    @php($extra_min=0)
                    @php($toll=0)
                    @foreach($list as $item)
                    <tr>
                        @if ($item->start_km==0)
                        <td class="td-c tds" style="font-size: 10px;">
                            {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                        </td>
                        <td class="td-c tds" colspan="5" style="font-weight: bold;font-size: 10px;"> {{$item->remark}}</td>
                        <td class="td-c tds" style="font-size: 10px;"></td>
                        <td class="td-c tds" style="font-size: 10px;"></td>
                        <td class="td-c tds" style="font-size: 10px;"></td>
                        <td class="td-c tds" style="font-size: 10px;"></td>
                        @else
                        <td class="td-c tds" style="font-size: 10px;">
                            {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{$item->start_km}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{$item->end_km}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            @if ($item->total_km>0)
                            @php($total_km=$total_km+$item->total_km)
                            @endif
                            {{$item->total_km}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{ Carbon\Carbon::parse($item->start_time)->format('h:i A')}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{ Carbon\Carbon::parse($item->close_time)->format('h:i A')}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{ Carbon\Carbon::parse($item->total_time)->format('H:i')}}
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            @if($item->extra_time>'00:00:00')
                            @php($extra_hr=$extra_hr+Carbon\Carbon::parse($item->extra_time)->format('H'))
                            @php($extra_min=$extra_min+Carbon\Carbon::parse($item->extra_time)->format('i'))
                            {{ Carbon\Carbon::parse($item->extra_time)->format('H:i')}}
                            @endif
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            @if($item->toll>0)
                            @php($toll=$toll+$item->toll)
                            {{$item->toll}}
                            @endif
                        </td>
                        <td class="td-c tds" style="font-size: 10px;">
                            {{$item->remark}}
                        </td>
                        @endif

                    </tr>
                    @endforeach
                <tfoot>
                    <tr>
                <th class="td-c tds"></th>
                <th class="td-c tds"></th>
                <th class="td-c tds" style="font-size: 10px;">TOTAL KM</th>
                <th class="td-c tds" style="font-size: 10px;">{{$total_km}}</th>
                <th class="td-c tds"></th>
                <th class="td-c tds"></th>
                <th class="td-c tds" style="font-size: 10px;">EXTRA HRS</th>
                @php($extra_hour=($extra_min+($extra_hr*60))/60)
                <th class="td-c tds" style="font-size: 10px;">{{$extra_hour}} </th>
                <th class="td-c tds" style="font-size: 10px;">{{number_format($toll,2)}}</th>
                <th class="td-c tds"></th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
        </div>

        <div class="row">
            <div class="col-md-6" style="max-width: 50%;">
                <table class="table table-bordered" style="font-size: 11px !important;color: black !important;">
                    <tbody>

                        <tr>
                            <th class="td-c tds" style="font-size: 10px;">Particulars</th>
                            <th class="td-c tds" style="font-size: 10px;">Unit</th>
                            <th class="td-c tds" style="font-size: 10px;">Qty</th>
                            <th class="td-c tds" style="font-size: 10px;">Rate(R.s)</th>
                            <th class="td-c tds" style="font-size: 10px;">Amount</th>
                        </tr>
                        @foreach($logsheet_detail as $det)
                        <tr>
                            <td class="td-c tds" style="font-size: 10px;">{{$det['particular_name']}}</td>
                            <td class="td-c tds" style="font-size: 10px;">{{$det['unit']}}</td>
                            <td class="td-c tds" style="font-size: 10px;">{{$det['qty']}}</td>
                            <td class="td-c tds" style="font-size: 10px;">{{$det['rate']}}</td>
                            <td class="td-c tds" style="font-size: 10px;">
                                @if($det['is_deduct']==1)-@endif{{$det['amount']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                    <th class="td-c tds"></th>
                    <th class="td-c tds"></th>
                    <th class="td-c tds"></th>
                    <th class="td-c tds" style="font-size: 10px;">TOTAL</th>
                    <th class="td-c  tds" style="font-size: 10px;" id="total_amount">{{$invoice->base_total}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
    <!-- /.panel-body -->
</div>

@endsection
