@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">

  <!-- BEGIN PAGE HEADER-->
  <div class="page-bar">
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('home.gstr2aSummary', $job_id ) }}
  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="portlet ">
        <div class="portlet-body">
          <form class="form-inline" style="margin-top: 7px;margin-bottom: 7px;" id="form-id" method="POST" action="/merchant/gst/reconciliation/summary/{{$job_id}}">
            @csrf
            <div class="form-group">
              <select class="form-control" name="gstin" id="gstin">
                <option value="">Select Supplier</option>
                @foreach($vendor_list as $v)
                <option value="{{$v->vendor_gstin}}"> {{$v->supplier}}</option>
                @endforeach

              </select>
              <br>
              <label style="margin-top: 5px;" for="gstin">Supplier GSTIN</label>
            </div>
            <div class="form-group">
              <select id="gstin_field" class="form-control" name="gstin_field">
                <option value="">Select</option>
                <option value="tax_difference">Tax difference</option>
                <option value="doc_difference">Invoice difference</option>
                <option value="taxable_difference">Taxable difference</option>
              </select>
              <br>
              <label style="margin-top: 5px;" for="gstin_field">Column</label>
            </div>
            <div class="form-group">
              <select id="between-dropdown" class="form-control" name="gstin_condition" onchange="checkbetweenStatus()">
                <option value=">">is greater than</option>
                <option value="<">is less than</option>
                <option value="=">is equal to</option>
                <option value="between">between</option>
              </select>
              <br>
              <label style="margin-top: 5px;">Criteria</label>
            </div>
            <div class="form-group" id="not_between">
              <input type="number" id="gstin_condition_value1" max="9999999999" style="width:100px" class="form-control" name="gstin_condition_value1" />
              <br>
              <label style="margin-top: 5px;">&nbsp</label>
            </div>
            <div style="display:none" id="between">
              <div class="form-group">
                <input type="number" class="form-control" max="9999999999" style="width:100px" name="gstin_condition_value2" />
                <br>
                <label style="margin-top: 5px;">&nbsp</label>
              </div>
              <div class="form-group">
                <label style="margin-bottom: 30px;">&</label>
              </div>
              <div class="form-group">
                <input type="number" class="form-control" max="9999999999" style="width:100px" name="gstin_condition_value3" />
                <br>
                <label style="margin-top: 5px;">&nbsp</label>
              </div>
            </div>
            <input type="hidden" name="job_id" value="{{$job_id}}" />
            <input type="hidden" name="page_limit" value="{{$page_limit}}" />
            <input type="hidden" name="taxable_difference_sort" value="" />
            <div class="form-group" id="not_between">
              <input type="submit" class="btn blue" value="Filter" />
              <br>
              <label style="margin-top: 5px;">&nbsp</label>
            </div>
          </form>
        </div>
      </div>

      <div class="portlet ">
        <div class="portlet-body">
          <div class=" subscription-info">
            <div class="col-md-2">
              <h3> {{$table_title_gst}}
              </h3>
              <p class="text-center">GSTIN</p>
            </div>
            <div class="col-md-2">
              <h3> {{$table_title_month_year}}
              </h3>
              <p class="text-center">Month & Year</p>
            </div>
          </div>
          <div class="pull-right" style="margin-top: 20px;">
            <div class="form-inline">
              <button class="btn blue" @if ($page_limit==1 ) disabled @endif onclick="prevpage('{{$page_limit}}')">Prev</button>
              <button class="btn blue" @if ($record_count < 10 ) disabled @endif onclick="nextpage('{{$page_limit}}')">Next</button>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 0px;">
            <form id="sort-form" action="/merchant/gst/reconciliation/summary/{{$job_id}}" method="POST">
              @csrf
              <input type="hidden" id='taxable_difference_sort' name="taxable_difference_sort" value="asc" />
              <input type="hidden" name="job_id" value="{{$job_id}}" />
              <input type="hidden" id='page_limit' name="page_limit" value="{{$page_limit}}" />
              <input type="hidden" name="gstin" value="" />
              <input type="hidden" name="gstin_field" value="" />
              <input type="hidden" name="gstin_condition" value="" />
              <input type="hidden" name="gstin_condition_value1" value="" />
              <input type="hidden" name="gstin_condition_value2" value="" />
              <input type="hidden" name="gstin_condition_value3" value="" />
            </form>
          </div>
          <table class="table table-bordered table-hover dataTable no-footer" id="table-gst-summary">
            <thead>
              <tr>
                <th class="td-c">
                  Supplier Details
                </th>
                <th class="td-c">

                </th>
                <th class="td-c">
                  No of invoices
                  <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Total number of invoices from GST portal and my data" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Mismatched Invoices
                  <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Mismatch in values are checked on the following parameters - Invoice Number, Total Value, Tax Value" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Tax value
                  <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Sum of applicable GST" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Tax difference
                  <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Total tax difference (Sum of applicable GST from GST portal - Sum of applicable GST from my data)" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Taxable value
                  <button class="popovers btn btn-link dropdown-toggle button-on-hover" data-container="body" data-placement="top" data-trigger="hover" data-content="Sum of all your invoices without GST" type="button">
                    <i class="fa fa-info-circle"></i>
                  </button>
                </th>
                <th class="td-c">
                  Taxable difference
                  <a href="#" onclick="sortTaxableDiff('asc')"><i class="fa fa-angle-up"></i></a>
                  <a href="#" onclick="sortTaxableDiff('desc')"><i class="fa fa-angle-down"></i></a>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach($list as $v)
              <tr>
                <td class="td-c" rowspan="2">
                  <a target="_blank" href="/merchant/gst/reconciliation/detail/{{$job_id}}/{{$v->vendor_gstin}}/all"> {{$v->supplier}}</a>
                </td>
                <td class="td-c">
                  MY DATA
                </td>
                <td class="td-c">
                  {{$v->no_of_doc_purch}}
                </td>
                <td class="td-c" rowspan="2">
                  {{$v->diff_of_doc}}
                </td>
                <td class="td-c">
                  {{$v->purch_request_total_amount}}
                </td>
                <td class="td-c" rowspan="2">
                  {{$v->diff_total_amount}}
                </td>
                <td class="td-c">
                  {{$v->tax_value_purch}}
                </td>
                <td class="td-c" rowspan="2">
                  {{$v->tax_value}}
                </td>

              </tr>
              <tr style="background-color: #E5FCFF;">
                <td class="td-c">
                  FROM GST
                </td>
                <td class="td-c">
                  {{$v->no_of_doc_gst}}
                </td>
                <td class="td-c">
                  {{$v->gst_request_total_amount}}
                </td>
                <td class="td-c">
                  {{$v->tax_value_gst}}
                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

</div>

@endsection

@section('footer')
<style>
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

  .btn-link {
    color: #6F8181;
    padding: 0px;
  }
</style>
<script src="/assets/admin/pages/scripts/gst2ra.js"></script>

@endsection