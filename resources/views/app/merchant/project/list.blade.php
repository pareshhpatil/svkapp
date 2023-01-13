@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('home.projectlist') }}
        <a href="/merchant/project/create" class="btn blue pull-right"> Create project </a>
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
                                    ID
                                </th>
                                <th class="td-c">
                                    Project ID
                                </th>
                                <th class="td-c">
                                    Project Name
                                </th>
                                <th class="td-c">
                                    Company Name
                                </th>
                                <th class="td-c">
                                    Start Date
                                </th>
                                <th class="td-c">
                                    End Date
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
                                    {{$v->id}}
                                </td>
                                <td class="td-c">
                                    {{$v->project_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->project_name}}
                                </td>
                                <td class="td-c">
                                    {{$v->company_name}}
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->start_date" type="date" />
                                  
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->end_date" type="date" />
                                  
                                </td>
                                <td class="td-c">
                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="/merchant/project/edit/{{$v->encrypted_id}}"><i class="fa fa-edit"></i> Update</a>
                                            </li>
                                            <li><a href="/merchant/code/list/{{$v->encrypted_id}}"><i class="fa fa-list"></i> View bill code</a>
                                            </li>
                                            <li><a href="/merchant/billedtransaction/list/{{$v->encrypted_id}}"><i class="fa fa-list"></i> View billed transaction</a>
                                            </li>
                                            <li>
                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/project/delete/{{$v->encrypted_id}}'"  data-toggle="modal" ><i class="fa fa-times"></i> Delete</a>  
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
                <h4 class="modal-title">Delete Project variation</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Project & its values in the future?
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

<script>
    list_name = '{{$list_name}}';
 </script>
@endsection
