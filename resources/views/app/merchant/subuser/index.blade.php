@extends('app.master')

@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
            <a href="{{ url('/merchant/subusers/create') }}" class="btn blue pull-right"> Create Sub Merchant</a>
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
                                <th class="td-c">
                                    Merchant name
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Role
                                </th>
                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c">

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subUsers as $subUser)
                                <tr>
                                    <td class="td-c">
                                        {{$subUser->first_name}}
                                    </td>
                                    <td class="td-c">
                                        {{$subUser->email_id}}
                                    </td>
                                    <td class="td-c">
{{--                                        {{$subUser.role}}--}}
                                    </td>
                                    <td class="td-c">
                                        @if($subUser->user_status == '20')
                                            <span class="label label-sm label-success">
                                                {{$subUser->user_status_label}}
                                            </span>
                                        @elseif($subUser->user_status == '19')
                                            <span class="label label-sm label-warning">
                                                {{$subUser->user_status_label}}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="td-c">
{{--                                        <a href="#basic" class="btn btn-xs red"><i class="fa fa-remove"></i> Delete </a>--}}
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/subusers/delete/{!! $subUser->user_id !!}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-remove"></i> Delete </a>
                                    </td>
                                </tr>
                            @endforeach
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
                    <h4 class="modal-title">Delete Sub-Merchant</h4>
                </div>
                <div class="modal-body">
                    Are you sure you would not like to use this Merchant in the future?
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