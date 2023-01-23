@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.contractlist') }}
        @if(in_array('create-contract', Session::get('permissions')) || Session::get('user_role') == 'Admin')
            <a href="{{ route('contract.create.new') }}" class="btn blue pull-right"> Create Contract </a>
        @endif

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
                            <label class="help-block" id="rptby">Contract date</label>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" />
                        </div>
                        <div class="form-group">
                            <select class="form-control " name="project_id" data-placeholder="Project">
                                <option value="">Select Project</option>
                                @foreach($project_list as $v)
                                <option @if($project_id==$v->id) selected @endif value="{{$v->id}}">{{$v->project_id}} | {{$v->project_name}}</option>
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
                                    Amount
                                </th>
                                <th class="td-c">
                                    Contract date
                                </th>
                                <th class="td-c">
                                    Created on
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
                                        {{ $v->created_date }}
                                    </td>
                                    <td class="td-c">
                                        <a style="font-size: 1.2rem;" href="{{ route('contract.update.new', ['step' =>1, 'contract_id' =>$v->encrypted_id]) }}">{{$v->company_name?$v->company_name:$v->name}}</a>
                                        <br>
                                        <span class="text-gray-400 text-font-12">{{$customer_code}} : <span class="text-gray-900"> {{$v->customer_code}}</span></span>
                                    </td>
                                    <td class="td-c">
                                        CONTRACT
                                        <br>
                                        <span class="text-gray-400 text-font-12">CONTRACT NO : <span class="text-gray-900"> {{$v->contract_code}}</span></span>
                                    </td>
                                    <td class="td-c">
                                        {{$v->project_name}}
                                        <br>
                                        <span class="text-gray-400 text-font-12">PROJECT NO : <span class="text-gray-900"> {{$v->project_code}}</span></span>
                                    </td>
                                    <td class="td-c">
                                    $@if($v->contract_amount < 0)({{str_replace('-','',number_format($v->contract_amount,2))}})@else{{number_format($v->contract_amount,2)}}@endif
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->contract_date" type="date" />
                                        <br>
                                        <span class="text-gray-400 text-font-12">FIRST BILLING DATE : <span class="text-gray-900">
                                                <x-localize :date="$v->bill_date" type="date" />
                                            </span></span>
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->created_date" type="datetime" />
                                    </td>
                                    <td class="td-c">
                                        <div class="btn-group dropup">
                                            <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    @if(in_array('update-contract', Session::get('permissions')) || Session::get('user_role') == 'Admin')
                                                        <a href="{{ route('contract.update.new', ['step' =>1, 'contract_id' =>$v->encrypted_id]) }}"><i class="fa fa-edit"></i> Update</a>
                                                    @endif
                                                </li>
                                                <li>
                                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/contract/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
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

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Contract</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Contract & its values in the future?
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