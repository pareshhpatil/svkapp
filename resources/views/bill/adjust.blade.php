@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form action="/admin/expense/pending" method="post" id="myForm" class="form-horizontal">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @isset($success_message)
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Success! </strong> {{$success_message}}
            </div>
            @endisset
            <div class="panel panel-primary">
                <div class="panel-body" style="overflow: auto;">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-4">Company<span class="required">* </span></label>
                            <div class="col-md-8">
                                <select name="company_id" id="company_id" onchange="document.getElementById('myForm').submit();" required class="form-control select2" data-placeholder="Select...">
                                    <option value="">Select company</option>
                                    @foreach ($company_list as $item)
                                    <option @if($company_id==$item->company_id) selected @endif value="{{$item->company_id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label col-md-3">Invoice<span class="required">* </span></label>
                            <div class="col-md-9">
                                <select name="invoice_id" id="invoice_id" class="form-control select2" data-placeholder="Select...">
                                    <option value="">Select Invoice</option>
                                    @foreach ($invoice_list as $item)
                                    <option value="{{$item->invoice_id}}">{{$item->invoice_number}} - {{ Carbon\Carbon::parse($item->date)->format('M-Y')}} - {{$item->grand_total}} - {{$item->vehicle_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                                <input type="hidden" id="expense_amount" name="expense_amount">
                                <button type="submit" name="SaveButton" class="btn btn-primary pull-left" style="margin-right: 10px;">Save</button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-body" style="overflow: auto;">
                    <table id="example1" class="table table-bordered table-striped" style="text-align: center;">
                        <thead>
                            <tr>
                                <th class="td-c">DATE</th>
                                <th class="td-c">Category</th>
                                <th class="td-c">Name</th>
                                <th class="td-c">Company Name</th>
                                <th class="td-c">Note</th>
                                <th class="td-c">Amount</th>
                                <th class="td-c">Adjust Amt</th>
                                <th class="td-c">Adjust?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expense_list as $item)
                            <tr>
                                <td class="td-c">
                                    {{ Carbon\Carbon::parse($item->date)->format('d/m/Y')}}
                                </td>
                                <td class="td-c">
                                    {{$item->category}}
                                </td>
                                <td class="td-c">
                                    {{$item->employee_name}}
                                </td>
                                <td class="td-c">
                                    {{$item->company_name}}
                                </td>
                                <td class="td-c">
                                    {{$item->note}}
                                </td>
                                <td class="td-c">
                                    {{$item->pending_amount}}
                                </td>
                                <td class="td-c">
                                    <input type="number" step="0.01" name="req_{{$item->request_id}}" max="{{$item->pending_amount}}" onchange="invexpense();" id="req_{{$item->request_id}}" value="{{$item->pending_amount}}" class="form-control input-sm">
                                </td>
                                <td class="td-c">
                                    <input type="checkbox" name="rcheck[]" onchange="invexpense();" value="{{$item->request_id}}">
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
                    <!-- /.table-responsive -->
                    <p id="loaded_n_total"></p>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->

        </form>
    </div>
    <!-- /.col-lg-12 -->
</div>


@endsection