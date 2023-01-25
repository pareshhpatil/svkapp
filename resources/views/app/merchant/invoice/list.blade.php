@extends('app.master')

@section('header')
@endsection

@section('content')
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render('invoicelist') }}
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN SEARCH CONTENT-->
    <div class="portlet">
        <div class="portlet-body">
            <form class="form-inline" style="margin-bottom: 0px;" id="form-id" onsubmit="createJob()">
                @csrf
                <div class="form-group">
                    <label class="help-block" id="rptby">Sent date</label>
                </div>
                <div class="form-group">
                <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range" autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}" />
                         </div>

                <div class="form-group">
                    <select class="form-control " name="invoice_type" data-placeholder="Request type">
                        <option value="">Request type</option>
                        <option {if $invoice_type==1} selected="" {/if} value="1">Invoice</option>
                        <option {if $invoice_type==2} selected="" {/if} value="2">Estimate</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control " name="invoice_status" data-placeholder="Invoice status">
                        <option selected="" value="0">Unpaid</option>
                        <option {if $invoice_status==1} selected="" {/if} value="1">Paid</option>
                        <option {if $invoice_status=='' } selected="" {/if} value="">All</option>
                    </select>
                </div>
                <input type="submit" class="btn blue" value="Search">

            </form>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="portlet ">
                <div class="portlet-body">
                    <table class="table table-striped  table-hover" id="users-table">
                        <thead>
                            <tr>
                                <th>
                                    Request #
                                </th>
                                <th>
                                    Customer name
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Project name
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Net billing
                                </th>
                                <th>
                                    Due date
                                </th>
                                <th>
                                    Sent date
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>

                                </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="10" style=""></th>
                            </tr>
                        </tfoot>
                        <tbody class="text-center request-list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Delete Invoice</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Invoice in the future?
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
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: '/merchant/invoice/list/data/{{csrf_token()}}',
            columns: [{
                    data: 'gstin'
                },
                {
                    data: 'month_range'
                },
                {
                    data: 'created_date_formatted'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

    });
</script>
@endsection

@section('footer')
<div class="panel-wrap" id="panelWrapId">

    <div class="panel">
        <h3 class="page-title">Revision history
            <a class="close " data-toggle="modal" onclick="return closeRevisionSidePanel();">
                <button type="button" class="close" aria-hidden="true"></button></a>
        </h3>
        <hr>
        <div id="subscription_view_ajax">
        </div>
    </div>
</div>
<script src=" /assets/global/plugins/jquery.min.js" type="text/javascript">
</script>
<script>
    var show_summary = '{$link}';
    if (show_summary != '') {
        callSidePanel('{$link}');
    }
</script>
@endsection