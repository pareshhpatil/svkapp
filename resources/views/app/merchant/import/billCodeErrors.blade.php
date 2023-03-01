@extends('app.master')
@section('content')
<link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
    </div>
    <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- END UPLOAD EXCEL BOX -->
            <!-- BEGIN CREATED TEMPLATES LISTING BOX -->

            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table  table-striped table-hover" id="table-no-export">
                        <thead>
                            <tr>

                                <th class="td-c" style="max-width: 80px;">
                                    Row number
                                </th>
                                <th class="td-c" style="max-width: 200px;">
                                    Row number
                                </th>
                               
                                <th class="td-c">
                                    Error
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($errors))
                            @foreach($errors as  $k=>$v)
                            <tr>
                                <td class="td-c">
                                    {{$k}}
                                </td>
                                <td class="td-c">
                                    {{$k}}
                                </td>
                               
                                <td class="td-c">
                                    {{$v}}
                                </td>
                            </tr>
                            @endforeach
                            @endif
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
                    <div class="modal fade" id="sendinvoice" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                    <h4 class="modal-title">Save Excel Bill codes</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to save these bill codes?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                    <a href="" id="sendanchor" class="btn blue">Confirm</a>
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