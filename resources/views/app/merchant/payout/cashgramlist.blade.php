@extends('app.master')

@section('content')
<div class="page-content">
    <div class="page-bar">
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
        <a href="/merchant/cashgram/create" class="btn blue pull-right"> Create Cashgram </a>
    </div>
    <!-- BEGIN SEARCH CONTENT-->
    <div class="row">
        <div class="col-md-12">
            @include('layouts.alerts')
            <!-- BEGIN PORTLET-->

            <div class="portlet">
               
                <div class="portlet-body" >

                    <form class="form-inline" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <input class="form-control form-control-inline rpt-date" type="text" required value='<x-localize :date="$from_date" type="date" /> - <x-localize :date="$to_date" type="date" />' id="daterange" name="date_range"  autocomplete="off" data-date-format="{{ Session::get('default_date_format')}}"  />
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
            <div class="portlet ">
               
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Cashgram ID
                                </th>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Email
                                </th>
                                <th class="td-c">
                                    Mobile
                                </th>
                                <th class="td-c">
                                    Amount
                                </th>
                                <th class="td-c">
                                    Expiry date
                                </th>
                                <th class="td-c">
                                    Status
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
                                    {{$v->cashgram_id}}
                                </td>

                                <td class="td-c">
                                    {{$v->name}}
                                </td>
                                <td class="td-c">
                                    {{$v->email_id}}
                                </td>
                                <td class="td-c">
                                    {{$v->mobile}}
                                </td>
                                <td class="td-c">
                                    {{$v->amount}}
                                </td>
                                <td class="td-c">
                                  
                                    <x-localize :date="$v->expiry_date" type="date" />
                                </td>
                                <td class="td-c">
                                    @if($v->status==1)
                                    Active
                                    @elseif($v->status==2)
                                    Verified
                                    @elseif($v->status==3)
                                    Pending
                                    @elseif($v->status==4)
                                    Expired
                                    @elseif($v->status==5)
                                    Redeemed
                                    @elseif($v->status==6)
                                    Reversed
                                    @endif
                                </td>

                                <td class="td-c">
                                    @if($v->status==1)
                                    <div style="font-size: 0px;"><sublink{{$v->cashgram_id}}>{{$v->cashgram_link}}</sublink{{$v->cashgram_id}}></div>
                                    <a class="btn btn-xs green bs_growl_show" data-clipboard-action="copy"  data-clipboard-target="sublink{{$v->cashgram_id}}"><i class="fa fa-clipboard"></i> Copy Link</a>
                                    @endif
                                    <a href="#basic" onclick="document.getElementById('deleteanchor').href = '/merchant/cashgram/delete/{{$v->encrypted_id}}'" data-toggle="modal" class="btn btn-xs red"><i class="fa fa-times"></i> Delete </a>
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
                <h4 class="modal-title">Delete Cashgram</h4>
            </div>
            <div class="modal-body">
                Are you sure you would not like to use this Cashgram in the future?
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