@extends('app.master')
@section('content')
<link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('merchant.import.contract') }}
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        @include('layouts.alerts')
        <div class="col-md-12">
            @if($order_id>0)
            <form action="/merchant/import/change-order/upload" enctype="multipart/form-data" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="">

                    <div class="portlet-body">
                        <div class="panel-group accordion" id="accordion1">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1">
                                            <b>Step #1 - Download & Fill excel sheet</b> </a>
                                    </h4>
                                </div>
                                <div id="collapse_1" class="panel-collapse in">
                                    <div class="panel-body">
                                        <p>Download the excel format for upload contract <a href="/merchant/import/format/changeOrder/{{$change_order_id}}" class="btn btn-xs blue">Download
                                                format</a>
                                        </p>
                                        <p>In the downloaded excel sheet enter contract details. Use column names
                                            mentioned in the excel sheet as reference.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2">
                                            <b>Step #2 - Upload filled sheet</b> </a>
                                    </h4>
                                </div>
                                <div id="collapse_2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <!--<p>Upload filled excel sheet</p>
                                        <p>-->
                                        <div class="form-body">
                                            <div class="form-group mb-0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <div class="col-md-3 pl-1" style="width: auto;">
                                                    <label class="control-label">Upload filled excel sheet</label>
                                                    
                                                    </div>
                                                        <input type="hidden" name="order_id" value="{{$order_id}}" />
                                                        <div class="col-md-3 pl-1" style="width: auto;">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new">
                                                                        Select file </span>
                                                                    <span class="fileinput-exists">
                                                                        Change </span>
                                                                    <input type="file" accept=".xlsx" name="fileupload" required>
                                                                </span>
                                                                <span class="fileinput-filename">
                                                                </span>
                                                                &nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 pl-1" style="width: auto;">
                                                            <input type="submit" class="btn blue" value="Upload" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <a href="/merchant/order/create" data-toggle="modal" class="btn green pull-right"> Create Contract </a>
            @endif
            <!-- END UPLOAD EXCEL BOX -->
            <!-- BEGIN CREATED TEMPLATES LISTING BOX -->
            
            <h3 class="form-section">Uploaded sheets</h3>
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table  table-striped table-hover" id="table-no-export">
                        <thead>
                            <tr>

                                <th class="td-c">
                                    Uploaded on
                                </th>
                               
                                <th class="td-c">
                                    Project name
                                </th>
                                <th class="td-c">
                                    Excel file name
                                </th>
                                <th class="td-c">
                                    Uploaded on
                                </th>
                                <th class="td-c">
                                    Total rows
                                </th>

                                <th class="td-c">
                                    Status
                                </th>
                                <th class="td-c" style="width: 50px;">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Delete Excel sheet</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you would not like to use this Excel in the future?
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
                </div>
            </div>
            <!-- END CREATED TEMPLATES LISTING BOX -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->

    <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>



</div>

@endsection