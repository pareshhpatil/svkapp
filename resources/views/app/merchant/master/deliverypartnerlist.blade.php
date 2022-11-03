@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">{{$title}}&nbsp;
        <a href="/merchant/master/delivery-partner/create" data-toggle="modal" class="btn blue pull-right"> Create new </a>
    </h3>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row mt-2">
        @include('layouts.alerts')
        <div class="col-md-12">
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
                                    Name
                                </th>
                                <th class="td-c">
                                    Start date
                                </th>
                                <th class="td-c">
                                    End date
                                </th>
                                <th class="td-c">
                                    Commission
                                </th>
                                <th class="td-c">
                                    GST
                                </th>
                                <th></th>
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
                                        {{$v->name}}
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->start_date" type="date" />
                                     
                                    </td>
                                    <td class="td-c">
                                        <x-localize :date="$v->end_date" type="date" />
                                    
                                    </td>
                                    <td class="td-c">
                                        {{$v->commission}}%
                                    </td>
                                    <td class="td-c">
                                        {{$v->gst}}%
                                    </td>
                                    <td class="td-c">
                                        <a href="/merchant/master/delivery-partner/update/{{$v->encrypted_id}}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                        <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/master/delivery-partner/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
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
                <h4 class="modal-title">Delete Delivery partner</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Delivery partner?
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