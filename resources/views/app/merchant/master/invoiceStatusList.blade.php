@extends('app.master')

@section('content')
<script src="/assets/admin/layout/scripts/invoiceformat.js?version=1665065805" type="text/javascript"></script>
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.configure-invoice-status') }}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet">
                
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                {{-- <th class="td-c">
                                    ID
                                </th> --}}
                                <th class="td-c">
                                   Status
                                </th>
                                <th class="td-c">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-bill-code">
                        <form action="" method="">
                          
                            @foreach($invoice_statues as $k=>$v)
                            <tr>
                                {{-- <td class="td-c">
                                    {{$v->config_key}}
                                </td> --}}
                                <td class="td-c">
                                    <a onclick="showUpdateStatus('{{$k}}','{{$v->config_key}}','{{$v->config_value}}')">{{$v->config_value}}</a>
                                </td>
                                <td class="td-c">
                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a onclick="showUpdateStatus('{{$k}}','{{$v->config_key}}','{{$v->config_value}}')"><i class="fa fa-edit"></i> Update</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </form>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END PAYMENT TRANSACTION TABLE -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
@include('app.merchant.master.invoice-status-update-modal')
@endsection
