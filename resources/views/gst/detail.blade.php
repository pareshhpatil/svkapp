@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  <div class="page-bar">
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('home.gstr2aDetails', $job_id, $supplier, $status)}}
  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="portlet ">
        <div class="portlet-body">
          <form class="form-inline" id="form-id" method="POST" action="/merchant/gst/reconciliation/detail/{{$job_id}}/{{$supplier}}/{{$status}}">
            @csrf
            <div class="form-group">
              <select id="recon_status_filter" required="" class="form-control" name="status">
                <option selected value="all">All</option>
                <option value="Matched">Matched</option>
                <option value="Reconciled">Reconciled</option>
                <option value="Pending">Pending</option>
                <option value="Missing in my data">Missing in my data</option>
                <option value="Missing in vendor GST filing">Missing in vendor GST filing</option>
                <option value="Mismatch in values">Mismatch in values</option>
              </select>
            </div>
            <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn blue" value="Filter" />
            <!-- <div class="pull-right">
              <a class="btn green" href="/merchant/gst/reconciliation/exportDetailData/{{$job_id}}/{{$supplier}}/{{$status}}">Export to excel</a>
            </div> -->
          </form>

        </div>
      </div>
      @if (count($summary_data) > 0)
      <div class="portlet ">
        <div class="portlet-body">
          <div class="subscription-info">
            @foreach($summary_data as $v)
            <div class="row">
              <div class="col-md-2">
                <h3> {{$v->vendor_gstin}}
                </h3>
                <p class="text-center">GSTIN</p>
              </div>
              <div class="col-md-2">
                @if($v->supplier =='')
                <h3> &nbsp
                </h3>
                @else
                <h3> {{$v->supplier}}
                </h3>
                @endif
                <p class="text-center">SUPPLIER NAME</p>
              </div>
              <div class="col-md-2">
                <h3>{{$v->no_of_doc}}
                </h3>
                <p class="text-center">NO: OF DOCS</p>
              </div>
              <div class="col-md-2">
                <h3>{{$v->taxable_value}}</h3>
                <p class="text-center">TAXABLE VALUE</p>
              </div>
              <div class="col-md-2">
                <h3>{{$v->tax_value}}
                </h3>
                <p class="text-center">TAX VALUE</p>
              </div>
              <div class="col-md-2">
                <h3>{{$v->tax_difference}}
                </h3>
                <p class="text-center">TAX DIFFERENCE</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      <div class="portlet ">
        <div class="portlet-body">
          <table class="table table-striped table-bordered table-hover dataTable no-footer" id="users-table1">
            <thead>
              <tr>
                <th class="td-c" colspan="2">

                </th>
                <th class="td-c" colspan="5">
                  My data
                </th>
                <th class="td-c">

                </th>
                <th class="td-c" colspan="5">
                  GST data
                </th>
                <th class="td-c">

                </th>
              </tr>
              <tr>
                <th class="td-c">

                </th>
                <th class="td-c">
                  Supplier
                </th>
                <th class="td-c">
                  Inv num
                </th>
                <th class="td-c">
                  Inv date
                </th>
                <th class="td-c">
                  Taxable val
                </th>
                <th class="td-c">
                  Tax val
                </th>
                <th class="td-c">
                  Total val
                </th>
                <th class="td-c bg-othercolumn">
                  Tax Diff
                  <button style="color: #6F8181;padding: 0px;" class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Difference of taxable value from my data and the data received from GST portal i.e. My data - GST portal data = Tax difference" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Inv num
                </th>
                <th class="td-c">
                  Inv date
                </th>
                <th class="td-c">
                  Taxable val
                </th>
                <th class="td-c">
                  Tax val
                </th>
                <th class="td-c">
                  Total val
                </th>
                <th class="td-c">
                  Recon status
                </th>
              </tr>
            </thead>
          </table>

        </div>
      </div>
    </div>
  </div>

</div>
<style>
  .btn-status {
    width: 100%;
    padding-bottom: 4px;
    padding-top: 4px;
    background: #FFFFFF;
    box-sizing: border-box;
    border-radius: 14px !important;
    text-align: center;
    font-family: Roboto;
    font-style: normal;
    font-weight: normal;
    display: flex;
    align-items: flex-end;
    display: block;
  }

  .btn-match {
    color: #A0ACAC;
    border: 1px solid #A0ACAC;
  }

  .btn-recon {
    color: #6F8181;
    border: 1px solid #6F8181;
  }

  .btn-pending {
    color: #275770;
    border: 1px solid #275770;
  }

  .btn-missing-my-data {
    color: #F99B36;
    border: 1px solid #F99B36;
  }

  .btn-missing-vendor {
    color: #B82020;
    border: 1px solid #B82020;
  }

  .btn-mismatch {
    color: #18AEBF;
    border: 1px solid #18AEBF;
  }

  .page-sidebar-wrapper {
    display: none;
  }

  .page-content-wrapper .page-content {
    margin-left: 0px;
  }

  .subscription-info i {
    font-size: 22px !important;
  }

  .cust-head {
    text-align: left !important;
  }

  .subscription-info h3 {
    text-align: center;
    color: #000;
    margin-bottom: 2px !important;
  }

  .td-c {
    vertical-align: middle !important;
  }

  .bg-lightyellow {
    background-color: lightgoldenrodyellow;
  }

  .bg-othercolumn {
    background-color: #D9DEDE;
  }

  .bg-amount-mismatch {
    background-color: #ff726f;
  }

  .button-on-hover:hover,
  .svg-icon:hover {
    background-color: #F7F8F8;
    color: #18AEBF;
  }

  .svg-icon {
    height: 25px;
    width: 25px;
    color: #495555;
  }

  .panel-wrap {
    position: fixed;
    top: 0;
    bottom: 0;
    right: 0;
    left: 25%;
    /* width: 80em; */
    transform: translateX(100%);
    transition: .3s ease-out;
    z-index: 11;
  }

  .panel-wrap .panel {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    color: #394242;
    overflow-y: scroll;
    overflow-x: hidden;
    padding: 1em;
    box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
    margin-bottom: 0;
  }

  .remove {
    padding: 4px 3px;
    cursor: pointer;
    float: left;
    position: relative;
    top: 0px;
    color: #000;
    right: 25px;
    z-index: 99999;
  }

  .remove:hover {
    color: #000;
  }

  .remove i {
    font-size: 25px !important;
  }

  .subscription-info i {
    font-size: 22px !important;
  }

  .cust-head {
    text-align: left !important;
  }

  .subscription-info h3 {
    text-align: center;
    color: #000;
    margin-bottom: 2px !important;
  }

  .subscription-info h2 {
    font-weight: 600;
    margin-bottom: 0 !important;
    margin-top: 5px !important;
    text-align: center;
  }

  .td-head {
    font-size: 19px;
  }

  @media (max-width: 767px) {
    .cust-head {
      text-align: center !important;
    }

    .panel-wrap {
      /* width: 23em; */
      top: 0;
      bottom: 0;
      right: 0;
      left: 0;
      position: fixed;
    }
  }

  @media (min-width: 768px) and (max-width: 991px) {
    .panel-wrap {
      /* width: 47em; */
      position: fixed;
      right: 0;
    }
  }

  @media (min-width: 992px) and (max-width: 1199px) {
    .panel-wrap {
      /* width: 47em; */
      position: fixed;
      right: 0;
    }
  }
</style>

<div class="panel-wrap" id="panelWrapId">
  <div id="close_tab" hidden>
    <a href="javascript:;" class="remove" data-original-title="Close" title="" onclick="return closeSidePanel();">
      <i class="fa fa-times"> </i>
    </a>
  </div>
  <div class="panel">
    <div id="integration_view_ajax">
    </div>
  </div>
</div>

<script>
  $(function() {
    $('#users-table1').DataTable({
      language: {
        searchPlaceholder: "Supplier name and Invoice number"
      },
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: '/merchant/gst/reconciliation/detailData/{{$job_id}}/{{$supplier}}/{{$status}}',
      columns: [{
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        },
        {
          data: 'supplier',
          searchable: true
        },
        {
          data: 'purch_invoice_number',
          searchable: true
        },
        {
          data: 'purch_request_date',
          searchable: false
        },
        {
          data: 'purch_taxable_amount',
          searchable: false
        },
        {
          data: 'purch_tax_value',
          searchable: false
        },
        {
          data: 'purch_total_amount',
          searchable: false
        },
        {
          data: 'diff',
          searchable: false
        },
        {
          data: 'gst_invoice_number',
          searchable: true
        },
        {
          data: 'gst_request_date',
          searchable: false
        },
        {
          data: 'gst_taxable_amount',
          searchable: false
        },
        {
          data: 'gst_tax_value',
          searchable: false
        },
        {
          data: 'gst_total_amount',
          searchable: false
        },
        {
          data: 'status',
          searchable: false
        }
      ],
      createdRow: function(row, data, index) {
        if (data['diff'] < 0 || data['diff'] > 0) {
          $('td', row).eq(7).addClass('bg-lightyellow');
        } else {
          $('td', row).eq(7).addClass('bg-othercolumn');
        }
        if (data['purch_total_amount'] > 0 && data['gst_total_amount'] > 0) {
          if (data['purch_total_amount'] < data['gst_total_amount'] || data['purch_total_amount'] > data['gst_total_amount']) {
            $('td', row).eq(6).addClass('bg-amount-mismatch');
            $('td', row).eq(12).addClass('bg-amount-mismatch');
          }

        }
        if (data['purch_tax_value'] > 0 && data['gst_tax_value'] > 0) {
          if ((data['purch_tax_value'] < data['gst_tax_value']) || (data['purch_tax_value'] > data['gst_tax_value'])) {
            $('td', row).eq(5).addClass('bg-amount-mismatch');
            $('td', row).eq(11).addClass('bg-amount-mismatch');
          }

        }
        if (data['purch_taxable_amount'] > 0 && data['gst_taxable_amount'] > 0) {
          if (data['purch_taxable_amount'] < data['gst_taxable_amount'] || data['purch_taxable_amount'] > data['gst_taxable_amount']) {
            $('td', row).eq(4).addClass('bg-amount-mismatch');
            $('td', row).eq(10).addClass('bg-amount-mismatch');
          }
        }
      }
    });

  });
</script>

@endsection

@section('footer')
<script src="/assets/admin/pages/scripts/gst2ra.js"></script>
@endsection