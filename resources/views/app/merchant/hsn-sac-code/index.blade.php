@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">HSN/SAC code list&nbsp;
        <a href="/merchant/hsn-sac-code/create" data-toggle="modal" class="btn blue pull-right"> Create HSN/SAC Code </a>
    </h3>
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
                                    # ID
                                </th>
                                <th class="td-c">
                                    Code
                                </th>
                                <th class="td-c">
                                    Type
                                </th>
                                <th class="td-c">
                                    GST
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <form action="" method="">
                            @foreach($hsn_sac_codes as $code)
                            <tr>
                                <td class="td-c">
                                    {{$code->id}}
                                </td>
                                <td class="td-c">
                                    {{$code->code}}
                                </td>
                                <td class="td-c">
                                    {{$code->type}}
                                </td>
                                <td class="td-c">
                                    {{$code->gst}}
                                </td>
                                <td class="td-c">
                                    <a href="/merchant/hsn-sac-code/edit/{{$code->encrypted_id}}" class="btn btn-xs green"><i class="fa fa-edit"></i> Edit </a>
                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/hsn-sac-code/delete/{{$code->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
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
                <h4 class="modal-title">Delete HSN/SAC Code</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this HSN/SAC code?
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