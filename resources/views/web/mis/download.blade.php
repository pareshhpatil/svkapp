@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="/roster/MISDownload" id="frm" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <h4 class="fw-bold py-2"><span class="text-muted fw-light">Download /</span> MIS</h4>
                </div>
                <div class="col-lg-4 pull-right">
                    <input type="text"  name="date_range" id="bs-rangepicker-time" value="{{$current_date_range}}" class="form-control" />
                </div>
                <div class="col-lg-2 pull-right">
                    <select name="project_id" id="select2Basic" class="select2 form-select input-sm" data-allow-clear="true">
                        @if(!empty($project_list))
                        @foreach($project_list as $v)
                        <option @if($project_id==$v->project_id) selected @endif @if(count($project_list)==1) selected @endif value="{{$v->project_id}}">{{$v->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-lg-2 pull-right">
                    <button type="submit" name="downloadRoster" class="btn btn-primary waves-effect waves-light pull-right">Download MIS</button>
                </div>

            </div>
        </form>
        
    </div>
</div>


@endsection

@section('footer')


<script src="/assets/vendor/libs/moment/moment.js"></script>
<script src="/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="/assets/vendor/libs/sortablejs/sortable.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>

<script>
    var dt_basic;
    var project_id = 0;
    var date = '{{$current_date_range}}';

    function reload(id) {
        project_id = id;
        dt_basic.destroy();
        datatable();
    }

    function deleteride(id) {
        response = confirm('Are you sure you want to delete this item?');
        if (response == true) {
            $.get("/ride/delete/" + id, function(data, status) {
                dt_basic.destroy();
                datatable();
            });
        }
    }

    function reloadDate(id) {
        try {
            date = id;
            dt_basic.destroy();
            datatable();
        } catch (o) {}

    }
    // datatable (jquery)
    


    
</script>
<script>
    var bsRangePickerTime = $('#bs-rangepicker-time');
    bsRangePickerTime.daterangepicker({
        timePicker: false,
        locale: {
            format: 'DD MMM YYYY'
        },
        opens: isRtl ? 'left' : 'right'
    });
</script>

@endsection
