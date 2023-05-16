@extends('app.master')
@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
            <a href="{{ url('/merchant/sub-contracts/create/1') }}" class="btn blue pull-right">Create Subcontract</a>
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">

                    <div class="portlet-body">
                        <table class="table table-striped table-hover" id="table-no-export">
                            <thead>
                                <tr>
{{--                                    <th class="td-c">--}}
{{--                                        Date--}}
{{--                                    </th>--}}
                                    <th class="td-c">
                                        Vendor Name
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
                                        Start date
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
{{--                                            <td class="td-c">--}}
{{--                                                {{ $v->created_date }}--}}
{{--                                            </td>--}}
                                            <td class="td-c">
                                                <a style="font-size: 1.2rem;" href="/merchant/sub-contracts/edit/1/{{$v->encrypted_id}}">{{$v->vendor_name ? $v->vendor_name : $v->title}}</a>
                                                <br>
                                                <span class="text-gray-400 text-font-12">Vendor Code : <span class="text-gray-900"> {{$v->vendor_code}}</span></span>
                                            </td>
                                            <td class="td-c">
                                                CONTRACT
                                                <br>
                                                <span class="text-gray-400 text-font-12">SUBCONTRACT NO : <span class="text-gray-900"> {{$v->sub_contract_code}}</span></span>
                                            </td>
                                            <td class="td-c">
                                                {{$v->project_name}}
                                                <br>
                                                <span class="text-gray-400 text-font-12">PROJECT NO : <span class="text-gray-900"> {{$v->project_code}}</span></span>
                                            </td>
                                            <td class="td-c">
                                                ${{number_format($v->sub_contract_amount,2)}}
                                            </td>
                                            <td class="td-c">
                                                <x-localize :date="$v->start_date" type="date" />
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
                                                        <li><a href="/merchant/sub-contracts/edit/1/{{$v->encrypted_id}}"><i class="fa fa-edit"></i> Update</a>
                                                        </li>
                                                        <li>
                                                            <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/sub-contracts/delete/{{$v->encrypted_id}}'" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
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
    <!-- END CONTENT -->

    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Subcontract</h4>
                </div>
                <div class="modal-body">
                    Are you sure you would not like to use this sub-contract in the future?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <a href="" id="deleteanchor" class="btn delete">Confirm</a>
                </div>
            </div>
            <!-- modal-content -->
        </div>
        <!-- modal-dialog -->
    </div>

@endsection