@extends('logsheet.generate_bill')
@section('middle_content')
@isset($company)
<div class="panel panel-primary">
    <div class="panel-body" style="overflow: auto;">
        <div class="row"  >
            <table class="table table-bordered" style="font-size: 12px !important;color: black !important;text-align: center;">
                <tbody>
                    <tr>
                        <td colspan="11" class="td-c" style="font-size: 20px;font-weight: bold;">{{$company_name}}</td>
                    </tr>
                    <tr>
                        <td colspan="11" class="td-c" style="font-size: 15px;font-weight: bold;">
                            {{$company->name}} {{$company->address}}
                            <br>
                            Logsheet Entry for the month of {{$month}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="11" class="td-c" style="font-size: 15px;font-weight: bold;">
                            Summery of Car No: ({{$vehicle->car_type}}){{$vehicle->number}}
                        </td>
                    </tr>
                    <tr>
                        <th class="td-c">DATE</th>
                        <th class="td-c">START KM</th>
                        <th class="td-c">END KM</th>
                        <th class="td-c">TOTAL KM</th>
                        <th class="td-c">From Loc.</th>
                        <th class="td-c">To Loc.</th>
                        <th class="td-c">Toll/ Parking</th>
                        <th class="td-c">Remark</th>
                        <th class="td-c">Action</th>
                    </tr>
                    @php($total_km=0)
                    @php($extra_hr=0)
                    @php($extra_min=0)
                    @php($extra_hour=0)
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
                            {{$item->from}}
                        </td>
                        <td class="td-c">
                            {{$item->to}}
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
                            <a onclick="return confirm('Are you sure?');" href="/admin/logsheet/deletebill/{{$item->link}}">Delete</a>
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
@endisset
@endsection
