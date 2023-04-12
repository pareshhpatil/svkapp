@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
        <a href="#create"  data-toggle="modal" class="btn blue pull-right"> Create department </a>
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet">
                
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    # ID
                                </th>
                                <th class="td-c">
                                    Department
                                </th>

                                <th class="td-c">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            @foreach($department as $v)
                            <tr>
                                <td class="td-c">
                                    {{$v->id}}
                                </td>

                                <td class="td-c">
                                    {{$v->name}}
                                </td>

                                <td class="td-c">
                                    <!-- <a href="#update" onclick="setUpdateMaster('{{$v->encrypted_id}}','{{$v->name}}')" data-toggle="modal"  class="btn btn-xs green"><i class="fa fa-edit"></i> Update </a>
                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/expense/department/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a> -->
                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#update" onclick="setUpdateMaster('{{$v->encrypted_id}}','{{$v->name}}')" data-toggle="modal" ><i class="fa fa-edit"></i> Update</a>
                                            </li>
                                            <li>
                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/expense/department/delete/{{$v->encrypted_id}}'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a>  
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
                <h4 class="modal-title">Delete department</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Department in the future?
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

<div class="modal fade" id="create" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/expense/departmentsave" onsubmit="loader();" method="post" class="form-horizontal form-row-sepe">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Create department</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Department <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" required=""  maxlength="45" name="department" class="form-control" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>					


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="submit" value="Save" class="btn blue"/>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="update" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/merchant/expense/departmentupdatesave" onsubmit="loader();" method="post" class="form-horizontal form-row-sepe">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Update department</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <!--<h3 class="form-section">Profile details</h3>-->

                        <div class="form-body">
                            <!-- Start profile details -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger display-none">
                                        <button class="close" data-dismiss="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-4">Department <span class="required">*
                                            </span></label>
                                        <div class="col-md-8">
                                            <input type="text" id="name_" required=""  maxlength="45" name="department" class="form-control" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>					


                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <input type="submit" value="Save" class="btn blue"/>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection