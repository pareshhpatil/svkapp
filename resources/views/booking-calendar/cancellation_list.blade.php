@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">

  <!-- BEGIN PAGE HEADER-->
  <div class="page-bar">
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('home.bookingcancellations') }}
  </div>
  <!-- END PAGE HEADER-->

  <!-- BEGIN SEARCH CONTENT-->

  <div class="portlet">
    <div class="portlet-body">
      <form class="form-inline" id="form-id" method="post">
        @csrf
        <div class="form-group">
          <div class="form-group">
            <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" />
          </div>
        </div>
        <div class="form-group">
          <select required="" class="form-control" name="cancel_status">
            <option value="0">Status</option>
            <option @if($cancel_status=='1' ) selected @endif value="1">New</option>
            <option @if($cancel_status=='2' ) selected @endif value="2">Refund Initiated</option>
            <option @if($cancel_status=='3' ) selected @endif value="3">Refunded</option>
            <option @if($cancel_status=='4' ) selected @endif value="4">Refund Denied</option>
          </select>
        </div>
        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <input type="submit" class="btn blue" value="Submit" />
        </div>

      </form>

    </div>
  </div>



  @if($cancellation_list_count > 0)
  <div class="row">
    <div class="col-md-12">
      <div class="portlet ">
        <div class="portlet-body">
          <table class="table table-striped  table-hover" id="users-table">
            <thead>
              <tr>
                <th class="td-c">
                  Booking Date
                </th>
                <th class="td-c">
                  Cancel Quantity
                </th>
                <th class="td-c">
                  Calendar
                </th>
                <th class="td-c">
                  Slot
                </th>
                <th class="td-c">
                  Transaction ID
                </th>
                <th class="td-c">
                  Slot Name
                </th>
                <th class="td-c">
                  Cancel Amount
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
    No cancellations available
  </div>
  @endif
</div>

<script>
  var from = "{{$from_date}}"
  var to = "{{$to_date}}";
  var cancel_status = "{{$cancel_status}}"
  $(function() {
    $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: '/merchant/transaction/booking/cancellations/list/' + from + '/' + to + '/' + cancel_status,
      columns: [{
          data: 'calendar_date'
        },
        {
          data: 'cancel_qty'
        },
        {
          data: 'calendar_title'
        },
        {
          data: 'slot'
        },
        {
          data: 'transaction_id'
        },
        {
          data: 'slot_name'
        },
        {
          data: 'cancel_amount'
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
<!-- <script type="text/javascript">
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
</script> -->
<style>
  tbody tr td {
    vertical-align: middle !important;
    text-align: center;
  }
</style>
<script src="/assets/admin/pages/scripts/gst2ra.js"></script>
@endsection