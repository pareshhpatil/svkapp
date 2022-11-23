@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet ">
                
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Beneficiary ID
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Beneficiary code
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Account name
                                </th>
                                <th class="td-c">
                                    Bank account
                                </th>
                                <th class="td-c">
                                    IFSC
                                </th>
                                <th class="td-c">
                                    UPI
                                </th>
                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            @foreach($list as $v)
                            <tr>
                                <td class="td-c">
                                    {{$v->beneficiary_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->type}}
                                </td>
                                <td class="td-c">
                                    {{$v->beneficiary_code}}
                                </td>
                                <td class="td-c">
                                    {{$v->name}}
                                </td>
                                <td class="td-c">
                                    {{$v->email_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->mobile}}
                                </td>
                                <td class="td-c">
                                    {{$v->account_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->bank_account_no}}
                                </td>
                                <td class="td-c">
                                    {{$v->ifsc_code}}
                                </td>
                                <td class="td-c">
                                    {{$v->upi}}
                                </td>
                                <td class="td-c">
                                    <a href="#basic" title="Delete" onclick="document.getElementById('deleteanchor').href = '/merchant/beneficiary/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> </a>
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


<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Beneficiary</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this beneficiary in the future?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn delete">Confirm</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection