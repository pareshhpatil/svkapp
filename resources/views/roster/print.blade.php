@extends('layouts.admin')

@section('content')
<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid black !important;
}    
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding-bottom: 2px !important;
    padding-top: 2px !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <div class="panel ">
            <a class="btn btn-success pull-right hidden-print" onclick="window.print();">Print</a>
            <div class="panel-body" style="overflow: auto;">
                <br>
                <br>
            <table id="example1" class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="border-bottom: 0px !important;">Vehicle No: <b>@isset($vdet->number){{$vdet->number}}@endisset</b>  </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 0px !important;">Driver Name: <b>@isset($ddet->name){{$ddet->name}}@endisset</b>  </td>
                        </tr>
                        <tr>
                            <td>Driver Mobile: <b>@isset($ddet->mobile){{$ddet->mobile}}@endisset</b></td>
                        </tr>
                    </tbody>
            </table>
            <div style="text-align: center;"><b>TRANSPORT - EMPLOYEE @if($det->pickupdrop=='Pickup') PICKUP @else DROP @endif REGISTRATION FORM</b>
                @if($det->pickupdrop=='Pickup') <span class="pull-right">Office Reporting Time : 06:45 AM</span>@else <span class="pull-right"> Drop Time {{ \Carbon\Carbon::parse($first_time)->format('h:i A')}}</span> @endif
            </div>
                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{$route_no}}</th>
                            <th>Date </th>
                            <th>{{ \Carbon\Carbon::parse($det->date)->format('d M Y')}}</th>
                            <th> </th>
                            @if($det->pickupdrop=='Pickup')
                            <th colspan="3"></th>
                            @else
                            <th colspan="2">EMP. Sign</th>
                            @endif
                        </tr>
                        <tr>
                            <th style="width: 150px;">Name:</th>
                            <th>Gender </th>
                            <th>Address</th>
                            <th>Location</th>
                            @if($det->pickupdrop=='Pickup')
                            <th>PickTime</th>
                            <th>Mobile No</th>
                            <th style="width: 120px;">Emp Sign.</th>
                            @else
                            <th style="width: 100px;">Drop off</th>
                            <th style="width: 120px;">Time</th>
                            @endif
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roster_emp as $key=>$item)
                        <tr >
                            <td>{{$item->employee_name}}</td>
                            <td>{{$item->gender}}</td>
                            <td>{{$item->address}}</td>
                            <td>{{$item->location}}</td>
                            @if($det->pickupdrop=='Pickup')
                            <td>{{ \Carbon\Carbon::parse($item->pickup_time)->format('h:i A')}}</td>
                            <td>{{$item->mobile}}</td>
                            <td></td>
                            @else
                            <td></td>
                            <td></td>
                            @endif
                        </tr>
                        @endforeach
                        <tr >
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            <td></td>
                            <td></td>
                            @else
                            <td></td>
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            <td></td>
                            <td></td>
                            @else
                            <td></td>
                            <td></td>
                            @endif
                            
                        </tr>
                        <tr>
                            <td colspan="5">Employee Count</td>
                            
                            @if($det->pickupdrop=='Pickup')
                            <td colspan="2">Driver Signature</td>
                            @else
                            <td>Driver Signature</td>
                            @endif
                        </tr>
                        <tr >
                            @if($det->pickupdrop=='Pickup')
                            <td colspan="7">Security Signature</td>
                            @else
                            <td colspan="6">Security Signature</td>
                            @endif
                        </tr>
                        <tr >
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr >
                            <td>Rating of Driver</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td></td>
                            <td>Excellent</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td></td>
                            <td>Very Good</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td></td>
                            <td>Good</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td></td>
                            <td>Average</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                        <tr >
                            <td>Remarks if any</td>
                            <td>Unsatisfactory</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if($det->pickupdrop=='Pickup')
                            <td></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script>
window.print();
</script>
@endsection
