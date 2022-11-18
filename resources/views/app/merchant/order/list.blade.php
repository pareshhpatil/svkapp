@extends('app.master')
<style>
    .margin-control-lable {
        margin-right: -50px;
        margin-top: 5px !important;
    }
</style>
@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.orderlist') }}
        <a href="/merchant/order/create" class="btn blue pull-right"> Create Change Order </a>
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-body">
                    <form class="form-inline" role="form" id="contract-form" method="post">
                        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="help-block" id="rptby">Created date</label>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" />
                        </div>
                        <div class="form-group">
                            <select class="form-control " name="contract_id" data-placeholder="Project">
                                <option value="">Select Contract</option>
                                @foreach($contract as $v)
                                <option @if($contract_id==$v->contract_id) selected @endif value="{{$v->contract_id}}">{{$v->project_name}} | {{$v->contract_code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" class="btn blue" value="Search">
                    </form>
                </div>
            </div>

            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet">

                <div class="portlet-body">
                    <table class="table table-striped table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    {{$customer_name}}
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    Project name
                                </th>
                                <th class="td-c">
                                    Contract
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Change order date
                                </th>
                                <th class="td-c">
                                    Created on
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
                                <tr role="row" class="odd">
                                    <td class="td-c">
                                        <x-localize :date="$v->created_date" type="date" />
                                    </td>
                                    <td class="td-c">
                                        @if($v->status == 0)
                                        <a style="font-size: 1.2rem;" href="/merchant/order/update/{{$v->encrypted_id}}">{{$v->company_name?$v->company_name:$v->name}}</a>
                                        @else
                                        <span style="font-size: 1.2rem;">
                                            <a style="font-size: 1.2rem;" href="/merchant/order/approved/{{$v->encrypted_id}}">{{$v->company_name?$v->company_name:$v->name}}</a>
                                        </span>
                                        @endif
                                        <br>
                                        <span class="text-gray-400 text-font-12">{{$customer_code}} : <span class="text-gray-900"> {{$v->customer_code}}</span></span>
                                    </td>
                                    <td class="td-c">
                                        ORDER
                                        <br>
                                        <span class="text-gray-400 text-font-12">ORDER NO : <span class="text-gray-900"> {{$v->order_no}}</span></span>
                                    </td>
                                    <td class="td-c">
                                        {{$v->project_name}}
                                        <br>
                                        <span class="text-gray-400 text-font-12">PROJECT NO : <span class="text-gray-900"> {{$v->project_code}}</span></span>
                                    </td>
                                    <td class="td-c">
                                        {{$v->contract_code}}
                                    </td>
                                    <td class="td-c">
                                        ${{number_format($v->total_change_order_amount,2)}}
                                    </td>
                                    <td class="td-c">

                                        <x-localize :date="$v->order_date" type="date" />
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->created_date" type="datetime" />

                                    </td>
                                    <td class="td-c">
                                        @if($v->status == 0)
                                        <span class="badge badge-pill status draft">PENDING</span>
                                        @else
                                        <span class="badge badge-pill status paid_online">APPROVED</span>
                                        @endif
                                    </td>
                                    <td class="td-c">
                                        @if($v->status == 0)
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="#basic2" onclick="document.getElementById('encrypt_id').value = '{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-check"></i> Approve</a>
                                                </li>
                                                <li>
                                                    <a href="/merchant/order/update/{{$v->encrypted_id}}"><i class="fa fa-edit"></i> Update</a>
                                                </li>
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/order/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                                </li>

                                            </ul>
                                        </div>
                                        @elseif($v->status == 1 && $v->invoice_status==0)
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a href="#unapprove" onclick="document.getElementById('un_encrypt_id').value = '{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-check"></i> Unapprove</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
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
                <h4 class="modal-title">Delete Order</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Order & its values in the future?
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


<div class="modal fade" id="basic2" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/order/approve/">
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Approve change order</h4>
                </div>
                <div class="modal-body col-md-12">

                    <div class="col-md-12">
                        Once a change order is approved it cannot be edited. The changes made in this change order will automatically reflect in the next invoice created for this project.
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="form-group">
                        <label class="control-label col-md-4 margin-control-lable">Approval date
                            <i class="popovers fa fa-info-circle support blue" data-placement="top" data-container="body" data-trigger="hover" data-content="Approval date" data-original-title="" title=""></i></label>
                        <div class="col-md-4">
                            <input class="form-control form-control-inline date-picker" type="text" required data-cy="approved_date" name="approved_date" autocomplete="off" data-date-format="dd M yyyy" placeholder="Approval date" value="{{$to_date}}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="encrypt_id" name="link" value="">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="submit" value="Confirm" class="btn blue">
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="unapprove" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/order/unapprove/">
                <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Unapprove change order</h4>
                </div>
                <div class="modal-body col-md-12">

                    <br>
                    <div class="form-group">
                        <label class="col-md-4 control-label margin-control-lable">Message</label>
                        <div class="col-md-8">
                            <textarea class="form-control  form-control-inline" rows="3" required name="unapprove_message" placeholder="Please enter a remark"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="un_encrypt_id" name="link" value="">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="submit" value="Confirm" class="btn blue">
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@endsection