@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
        <a href="/merchant/autocollect/subscription/create" class="btn blue pull-right"> Create subscription </a>
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
                                    # ID
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Plan name
                                </th>
                                <th class="td-c">
                                    Customer name
                                </th>
                                <th class="td-c">
                                    Email ID
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Status
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
                                    {{$v->subscription_id}}
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->created_date" type="datetime" />
                                
                                </td>
                                <td class="td-c">
                                    {{$v->plan_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->customer_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->email_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->mobile}}
                                </td>
                                <td class="td-c">
                                    {{ Helpers::moneyFormatIndia($v->amount) }}
                                </td>
                                <td class="td-c">
                                    @if($v->status==1)
                                    Submitted
                                    @elseif($v->status==2)
                                    Active
                                    @endif
                                </td>

                                <td class="td-c">
                                    @if($v->status==1)
                                    <div style="font-size: 0px;"><sublink{{$v->subscription_id}}>{{$v->auth_link}}</sublink{{$v->subscription_id}}></div>
                                    <a class="btn btn-xs green bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="sublink{{$v->subscription_id}}"><i class="fa fa-clipboard"></i> Copy Link</a>
                                    @endif
                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/autocollect/subscription/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
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
                <h4 class="modal-title">Delete Subscription</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Subscription in the future?
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