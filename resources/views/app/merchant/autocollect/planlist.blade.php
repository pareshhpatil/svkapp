@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
        <a href="/merchant/autocollect/plan/create" @if($collectkey==false) disabled @endif class="btn blue pull-right"> Create plan </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Auto debit payments from customers</strong>
                <div class="media">
                    Automatically debit funds from customers debit card or bank account at a set frequency. Your customer will need to authorize this account debit.
                    Would you like to enable this feature in your account please reach out to support@swipez.in
                </div>
            </div>
            @include('layouts.alerts')
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Plan ID
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Plan name
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Occurrence
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
                                        {{$v->plan_id}}
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->created_date" type="datetime" />
                                      
                                    </td>
                                    <td class="td-c">
                                        {{$v->plan_name}}
                                    </td>
                                    <td class="td-c">
                                        {{ Helpers::moneyFormatIndia($v->amount) }}
                                    </td>
                                    <td class="td-c">
                                        {{$v->occurrence}} Months
                                    </td>
                                    <td class="td-c">
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/autocollect/plan/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
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
                <h4 class="modal-title">Delete Plan</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this plan in the future?
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

<div class="modal fade" id="confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Activate Service</h4>
            </div>
            <div class="modal-body">
                Are you sure you would like to activate this service?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <a href="" id="deleteanchor" class="btn blue">Confirm</a>
            </div>
        </div>

    </div>

</div>
@endsection