@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="fw-bold py-2"><span class="text-muted fw-light">Invoice /</span> List</h4>
            </div>

        </div>
        <div class="card invoice-preview-card">

            <div class="card-body">

                <div class="card-datatable table-responsive pt-0">

                    <table id="datatable" class="datatables-basic  table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Month</th>
                                <th>Bill date</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{$invoice->invoice_number}}</td>
                                <td>{{$invoice->title}}</td>
                                <td>{{$invoice->company_name}}</td>
                                <td>{{$invoice->bill_month}}</td>
                                <td>{{$invoice->bill_date}}</td>
                                <td>{{$invoice->grand_total}}</td>
                                <td>
                                    <div class="d-inline-block">
                                        <a href="javascript:;" class="dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">
                                            @foreach($invoice->documents as $document)
                                            <li><a href="{{$document->url}}" target="_BLANK" class="dropdown-item ">Download {{$document->document_name}}</a></li>
                                            @endforeach
                                            @if(session('user_type')==1)
                                            <li><a  href="{{ route('invoice.delete', $invoice->invoice_id) }}"
                                            onclick="return confirm('Are you sure you want to delete this?')" class="dropdown-item text-danger">Delete Invoice</a></li>

                                            @endif
                                        </ul>
                                    </div>
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


@endsection