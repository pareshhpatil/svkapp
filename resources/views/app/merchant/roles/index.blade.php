@extends('app.master')

@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
            <a href="{{ url('/merchant/roles/create') }}"  data-toggle="modal" class="btn blue pull-right"> Create Role </a>
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
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Description
                                </th>

                                <th class="td-c">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="td-c">
                                            {{$role->name}}
                                        </td>

                                        <td class="td-c">
                                            {{$role->description}}
                                        </td>

                                        <td class="td-c">
                                            <div class="btn-group dropup">
                                                <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                                    &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <a href="{{ url('merchant/roles/'. $role->id .'/edit') }}" onclick="setUpdateMaster('{{$role->encrypted_id}}','{{$role->name}}')" data-toggle="modal"><i class="fa fa-edit"></i> Update</a>
                                                    </li>
                                                </ul>
                                            </div>
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
@endsection