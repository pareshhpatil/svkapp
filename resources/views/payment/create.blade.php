@extends('layouts.admin')

@section('content')
<div class="row" id="insert">
    @isset($success_message)
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong>Success! </strong> {{$success_message}}
    </div>
    @endisset
    <form action="/admin/income/create" method="post" id="myForm" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="col-md-8">
            <div class="form-group">
                <label class="control-label col-md-4">Company<span class="required">* </span></label>
                <div class="col-md-7">
                    <select name="company_id" id="company_id" onchange="document.getElementById('myForm').submit();" required class="form-control select2" data-placeholder="Select...">
                        <option value="">Select company</option>
                        @foreach ($company_list as $item)
                        <option @if($company_id==$item->company_id) selected @endif value="{{$item->company_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Payment Date<span class="required"> </span></label>
                <div class="col-md-7">
                    <input type="text" name="date" readonly="" value="{{$current_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="dd M yyyy">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Amount<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="number" pattern="[0-9]*" step="1" required="" value="" name="amount" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-4">Remark<span class="required">* </span></label>
                <div class="col-md-7">
                    <input type="text" required id="remark" name="remark" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Payment mode<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="payment_mode" class="form-control" data-placeholder="Select...">
                        <option value="">Select mode</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="NEFT">NEFT</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4">Paid In<span class="required"> </span></label>
                <div class="col-md-7">
                    <select name="source_id" class="form-control" data-placeholder="Select...">
                        <option value="">Select source</option>
                        @foreach ($paymentsource_list as $item)
                        <option value="{{$item->paymentsource_id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(!empty($invoice_list))
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h4>Invoice List</h4>
                    <table id="" class="table table-bordered table-striped" style="text-align: center;">
                        <thead>
                            <tr>
                                <th class="td-c">DATE</th>
                                <th class="td-c">Invoice #</th>
                                <th class="td-c">Vehicle</th>
                                <th class="td-c">Invoice Amount</th>
                                <th class="td-c">Paid Amt</th>
                                <th class="td-c">TDS Amt</th>
                                <th class="td-c">Adjust?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice_list as $item)
                            <tr>
                                <td class="td-c">
                                    {{ Carbon\Carbon::parse($item->bill_date)->format('d/M/Y')}}
                                </td>
                                <td class="td-c">
                                    {{$item->invoice_number}}
                                </td>
                                <td class="td-c">
                                    {{$item->vehicle_name}}
                                </td>
                                <td class="td-c">
                                    <span id="bill_amt_{{$item->invoice_id}}">{{$item->grand_total}}</span>
                                </td>
                                <td class="td-c">
                                    <input type="number" step="0.01" name="req_{{$item->invoice_id}}" max="{{$item->grand_total}}" onchange="invexpense();calTDS({{$item->invoice_id}})" id="req_{{$item->invoice_id}}" value="{{$item->grand_total}}" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="number" step="0.01" readonly name="tds_{{$item->invoice_id}}" max="{{$item->grand_total}}" id="tds_{{$item->invoice_id}}" value="0" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="checkbox" name="rcheck[]" onchange="invexpense();" value="{{$item->invoice_id}}">
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"></th>

                                <th class="td-c" id="total_expense">0.00</th>
                                <th class="td-c"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.panel-body -->
            </div>
            @endif

            <div class="form-group">
                <div class="col-md-11 modal-footer">
                    <p id="loaded_n_total"></p>
                    <a href="/admin/bill" class="btn btn-default pull-right">Close</a>
                    <input type="hidden" id="expense_amount">
                    <button type="submit" name="SaveButton" class="btn btn-primary pull-right" style="margin-right: 10px;">Save</button>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    function calTDS(id) {
        document.getElementById('tds_' + id).value = Number(document.getElementById('bill_amt_' + id).innerHTML) - Number(document.getElementById('req_' + id).value);
    }
</script>

@endsection