@extends('logsheet.generate_bill')
@section('middle_content')
@isset($company)
@php($extra_hour=0)
@php($toll=0)
@if(count($list)>0)
<style>
    .amtbox {
        border: none;
    }

    .input-sm {
        font-size: 13px;
    }
</style>
<div class="panel panel-primary">
    <form action="/admin/logsheet/monthsave" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                        </tr>
                        @php($total_km=0)
                        @php($extra_hr=0)
                        @php($extra_min=0)
                        @php($toll=0)
                        @php($int=1)

                        @foreach($list as $item)
                        <tr>
                            <td class="td-c" style="min-width: 100px;;">
                                {{ Carbon\Carbon::parse($item->date)->format('d-m-Y')}}
                                <input type="hidden" name="date[]" value="{{$item->date}}">
                            </td>
                            <td class="td-c">
                                <input value="{{$item->start_km}}" @if($item->holiday==1) readonly @endif type="text" onblur="calculate({{$int}});" name="start_km[]" class="form-control input-sm amtbox" id="start_km{{$int}}">
                            </td>
                            <td class="td-c">
                                <input value="{{$item->end_km}}" onkeydown="handleEnter(event.key,'{{$int}}')" @if($item->holiday==1) readonly @endif type="text" onblur="calculate({{$int}});" name="end_km[]" class="form-control input-sm amtbox" id="end_km{{$int}}">
                            </td>
                            <td class="td-c">

                                @if ($item->total_km>0)
                                @php($total_km=$total_km+$item->total_km)
                                @endif
                                <input value="{{$item->total_km}}" readonly type="text" onblur="calculate({{$int}});" name="total_km[]" class="form-control input-sm amtbox" id="total_km{{$int}}">
                            </td>
                            <td class="td-c">

                                <input value="{{$item->start_time}}" @if($item->holiday==1) readonly @endif type="time" onblur="calculate({{$int}});" name="start_time[]" class="form-control input-sm amtbox" id="start_time{{$int}}">
                            </td>
                            <td class="td-c">
                                <input value="{{$item->close_time}}" @if($item->holiday==1) readonly @endif type="time" onblur="calculate({{$int}});" name="close_time[]" class="form-control input-sm amtbox" id="close_time{{$int}}">
                            </td>
                            <td class="td-c">
                                <input value="{{$item->total_time}}" readonly type="text" onblur="calculate({{$int}});" name="total_time[]" class="form-control input-sm amtbox" id="total_time{{$int}}">
                            </td>
                            <td class="td-c">
                                @if($item->extra_time>'00:00:00')
                                @php($extra_hr=$extra_hr+Carbon\Carbon::parse($item->extra_time)->format('H'))
                                @php($extra_min=$extra_min+Carbon\Carbon::parse($item->extra_time)->format('i'))
                                {{ Carbon\Carbon::parse($item->extra_time)->format('H:i')}}
                                @endif
                                <input value="{{$item->extra_time}}" type="text" name="extra_time[]" class="form-control input-sm amtbox" id="extra_time{{$int}}">
                            </td>
                            <td class="td-c">
                                @if($item->toll>0)
                                @php($toll=$toll+$item->toll)

                                @endif
                                <input value="{{$item->toll}}" @if($item->holiday==1) readonly @endif type="text" onblur="calculate({{$int}});" name="toll[]" class="form-control input-sm amtbox" id="toll{{$int}}">
                            </td>
                            <td class="td-c">
                                <input value="{{$item->remark}}" type="text" name="remark[]" class="form-control input-sm amtbox" id="remark{{$int}}">
                                <input value="{{$item->holiday}}" type="hidden" name="holiday[]" id="holiday{{$int}}">
                            </td>

                            @php($int++)
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div class="col-md-9">
                </div>


                <div class="col-md-3">
                    <input type="hidden" name="vehicle_id" value="{{$vehicle_id}}">
                    <input type="hidden" name="company_id" value="{{$company_id}}">
                    <input type="hidden" name="admin_id" value="{{$admin_id}}">
                    <input type="hidden" name="user_id" value="{{$user_id}}">
                    <button type="submit" class="btn btn-primary pull-right " fdprocessedid="1tt3fg">Submit </button>
                    <a href="/admin/logsheet" class="btn btn-default pull-right  " style="margin-right: 20px;">Back </a>
                </div>
                <br>
                <br>
            </div>
        </div>
    </form>
    <!-- /.panel-body -->
</div>
@endif

<script>
    function calculate(id) {
        _('total_km' + id).value = _a('end_km' + id) - _a('start_km' + id);
        try {
            next_id = Number(id) + 1;
            while (_('holiday' + next_id).value == 1) {
                next_id = Number(next_id) + 1;
            }
            if (_('start_km' + next_id).value == '') {
                _('start_km' + next_id).value = _('end_km' + id).value;
            }



        } catch (o) {}

        getTime(id)
    }

    function _(id) {
        return document.getElementById(id);
    }

    function _a(id) {
        val = _(id).value;
        try {
            Str = val.toString();
            return Number(Str.replaceAll(',', ''));
        } catch (o) {
            return 0;
        }

    }

    function getTime(id) {

        let t = _('start_time' + id).value;
        let start = Number(t.split(':')[0]) * 60 * 60 * 1000 + Number(t.split(':')[1]) * 60 * 1000;


        // task starts
        for (var i = 0; i < 100000000; i++);
        // task ends

        let e = _('close_time' + id).value;
        let end = Number(e.split(':')[0]) * 60 * 60 * 1000 + Number(e.split(':')[1]) * 60 * 1000;

        timeInMiliseconds = end - start;



        let h, m;
        h = Math.floor(timeInMiliseconds / 1000 / 60 / 60);
        m = Math.floor((timeInMiliseconds / 1000 / 60 / 60 - h) * 60);
		
		if(e<t)
		{
			
			h=24+h;
		}

        _('total_time' + id).value = h + ':' + m;

        // if (timeInMiliseconds > 43200000) {
        //     extramilisecond = timeInMiliseconds - 43200000;

        //     let eh, em;
        //     eh = Math.floor(extramilisecond / 1000 / 60 / 60);
        //     em = Math.floor((extramilisecond / 1000 / 60 / 60 - eh) * 60);

        // _('extra_time' + id).value = eh + ':' + em;
        //  }
    }

    function handleEnter(key, id) {
        console.log(key);
        if (key == 'ArrowDown') {
            next_id = Number(id) + 1;
            while (_('holiday' + next_id).value == 1) {
                next_id = Number(next_id) + 1;
            }
            _('end_km' + next_id).focus();
        }
        if (id > 1) {
            if (key == 'ArrowUp') {
                next_id = Number(id) - 1;
                while (_('holiday' + next_id).value == 1) {
                    next_id = Number(next_id) - 1;
                }
                _('end_km' + next_id).focus();
            }
        }
    }
</script>


@endisset
@endsection