@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">

  <!-- BEGIN PAGE HEADER-->
  <div class="page-bar">
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('home.gstr2a') }}
  </div>
  <!-- END PAGE HEADER-->
  <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="conn_error">
    Please validate your connection with the GST portal via OTP
  </div>
  <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="month_error">
    Month difference cannot be more than 12 months
  </div>
  <div class="alert alert-danger" style="display:none; margin-left: 0px !important; margin-right:0px !important" id="expense_error">
    No expense records found. Please upload expense data.
  </div>
  <!-- BEGIN SEARCH CONTENT-->
  <div class="portlet">
    <div class="portlet-body">
      <form class="form-inline" style="margin-bottom: 0px;" id="form-id" onsubmit="createJob()">
        @csrf
        <div class="form-group">
          <select required="" class="form-control" name="gstin" id="gstin_list">
            <option value="">Select GSTIN</option>
            @foreach($gst_list as $v)
            <option value="{{$v->gst_number}}"> {{$v->gst_number}} - {{$v->company_name}}</option>
            @endforeach
          </select>
          <br>
          <label for="gstin_field">Select supplier GSTIN</label>
          <br>
          <label>&nbsp</label>
        </div>
        <div class="form-group">
          <div style="margin: 10px;border-radius: 10px;padding: 10px;border: 1px solid #E5E5E5;">
            <div class="form-group">
              <input required style="width: 130px;" id='datepicker3' type='text' name="expense_from_month_year" class="form-control" />
              <br>
              <label style="margin-top: 5px;" for="gstin_field">From month</label>
            </div>
            <div class="form-group">
              <input required style="width: 130px;" id='datepicker4' type='text' name="expense_to_month_year" class="form-control" />
              <br>
              <label required style="margin-top: 5px;" for="gstin_field">To Month</label>
            </div>
          </div>
          <label style="margin-bottom: 0px; display:block; text-align: center;">MY DATA</label>
        </div>
        <div class="form-group">
          <div style="margin: 10px;border-radius: 10px;padding: 10px;border: 1px solid #E5E5E5;">
            <div class="form-group">
              <input required style="width: 130px;" id='datepicker1' type='text' name="gst_from_month_year" class="form-control" />
              <br>
              <label style="margin-top: 5px;" for="gstin_field">From month</label>
            </div>
            <div class="form-group">
              <input required style="width: 130px;" id='datepicker2' type='text' name="gst_to_month_year" class="form-control" />
              <br>
              <label style="margin-top: 5px;" for="gstin_field">To Month</label>
            </div>
          </div>
          <label style="margin-bottom: 0px; display:block; text-align: center;">GST PORTAL</label>
        </div>
        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <input type="submit" class="btn blue" value="Run" />
          <br>
          <label>&nbsp</label>
          <br>
          <label>&nbsp</label>
        </div>

      </form>

    </div>
  </div>



  @if($gst_list_count > 0)
  <div class="row">
    <div class="col-md-12">
      <div class="portlet ">
        <div class="portlet-body">
          <table class="table table-striped  table-hover" id="users-table">
            <thead>
              <tr>
                <th class="td-c">
                  GST number
                </th>
                <th class="td-c">
                  Month range
                </th>
                <th class="td-c">
                  Requested date
                </th>
                <th class="td-c">
                  Status
                </th>
                <th class="td-c">

                </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  @else
    <div class="alert alert-danger" style="margin-left: 0px !important; margin-right:0px !important">
      Please Link your GST
    </div>
  @endif
</div>

<script>
  $(function() {
    $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: '/merchant/gst/reconciliation/landingData/{{csrf_token()}}',
      columns: [{
          data: 'gstin'
        },
        {
          data: 'month_range'
        },
        {
          data: 'created_date_formatted'
        },
        {
          data: 'status'
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
      ]
    });

  });
</script>
@endsection

@section('footer')
<script type="text/javascript">
  $("#datepicker3").datepicker({
      format: "MM-yyyy",
      startView: "months",
      minViewMode: "months",
      startDate: '-2y',
      autoclose: true
    })
    .change(dateChanged)
    .on('changeDate', dateChanged);

  function dateChanged(ev) {
    if (ev.date != null || ev.date != undefined) {
      $("#datepicker4").datepicker('setDate', new Date(ev.date));
      $("#datepicker2").datepicker('setDate', new Date(ev.date));
      $("#datepicker1").datepicker('setDate', new Date(ev.date));
    }

  }
  $("#datepicker2").datepicker({
    format: "MM-yyyy",
    startView: "months",
    minViewMode: "months",
    startDate: '-2y',
    autoclose: true
  });
  $("#datepicker1").datepicker({
    format: "MM-yyyy",
    startView: "months",
    minViewMode: "months",
    startDate: '-2y',
    autoclose: true
  });
  $("#datepicker4").datepicker({
    format: "MM-yyyy",
    startView: "months",
    minViewMode: "months",
    startDate: '-2y',
    autoclose: true
  });
</script>
<style>
  tbody tr td {
    vertical-align: middle !important;
    text-align: center;
  }
</style>
<script src="/assets/admin/pages/scripts/gst2ra.js"></script>
@endsection