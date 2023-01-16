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
                                                    @if($role->usersRoles->count() == 0 &&$role->name != 'Admin')
                                                        <li>
                                                            <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/roles/delete/{!! $role->id !!}'" data-toggle="modal"><i class="fa fa-remove"></i> Delete</a>
                                                        </li>
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
                    <h4 class="modal-title">Delete Role</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this role ?</p>
                    <p>Users assign to this role will not able to perform assigned permission action after delete!</p>
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