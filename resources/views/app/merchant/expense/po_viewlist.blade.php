@extends('app.master')

@section('content')
<div class="page-content">
<div class="page-bar">  
    <span class="page-title" style="float: left;">{{$title}}</span>
    {{ Breadcrumbs::render('list.expense','Purchase order') }}
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            @include('layouts.alerts')
            <div class="portlet">

                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required  value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  />
                        </div>
                        <div class="form-group">
                            <select class="form-control "  id="category" data-placeholder="Select category" required="" name="category_id" >
                                <option value="0">Select category</option>
                                @foreach($category as $v)
                                @if($category_id==$v->id)
                                <option selected="" value="{{$v->id}}">{{$v->name}}</option>
                                @else
                                <option value="{{$v->id}}">{{$v->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control " id="department" data-placeholder="Select department" required="" name="department_id" >
                                <option value="0">Select department</option>
                                @foreach($department as $v)
                                @if($department_id==$v->id)
                                <option selected="" value="{{$v->id}}">{{$v->name}}</option>
                                @else
                                <option value="{{$v->id}}">{{$v->name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <input type="submit" class="btn  blue" value="Search">
                    </form>

                </div>
            </div>

            <!-- END PORTLET-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PAYMENT TRANSACTION TABLE -->
            <div class="portlet">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    PO id
                                </th>
                                <th class="td-c">
                                    PO no
                                </th>
                                <th class="td-c">
                                    Vendor name
                                </th>
                                <th class="td-c">
                                    Category
                                </th>
                                <th class="td-c">
                                    Department
                                </th>
                                <th class="td-c">
                                    Date
                                </th>
                                <th class="td-c">
                                    Expected delivery
                                </th>
                                <th class="td-c">
                                    Amount
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
                                    {{$v->expense_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->expense_no}}
                                </td>
                                <td class="td-c">
                                    <a href="/merchant/expense/view/{{$v->encrypted_id}}" >
                                        {{$v->vendor_name}}
                                    </a>
                                </td>
                                <td class="td-c">
                                    {{$v->category}}
                                </td>
                                <td class="td-c">
                                    {{$v->department}}
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->bill_date" type="date" />
                                </td>
                                <td class="td-c">
                                    <x-localize :date="$v->due_date" type="date" />
                                </td>
                                <td class="td-c">
                                    {{ Helpers::moneyFormatIndia($v->total_amount) }}
                                </td>
                                <td class="td-c">
                                    <div class="btn-group dropup">
                                        <button class="btn btn-xs btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                                            &nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="/merchant/expense/view/{{$v->encrypted_id}}" ><i class="fa fa-table"></i> View</a>
                                            </li>
                                            <li>
                                                <a href="/merchant/expense/update/{{$v->encrypted_id}}" ><i class="fa fa-edit"></i> Update</a>
                                            </li>
                                            @if($v->status==0)
                                            <li>
                                                <a href="/merchant/expense/convert/{{$v->encrypted_id}}" ><i class="fa fa-creative-commons"></i> Convert expense</a>
                                            </li>
                                            <li>
                                                <a href="/merchant/expense/resend/{{$v->encrypted_id}}" ><i class="fa fa-mail-forward"></i> Resend mail</a>
                                            </li>
                                            @endif
                                            <li>
                                                <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/expense/po/delete/{{$v->encrypted_id}}'" data-toggle="modal"  ><i class="fa fa-times"></i> Delete</a>
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
                <h4 class="modal-title">Delete purchase order</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this purchase order in the future?
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
hide_first_col=true;
</script>
@endsection