@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @isset($success_message)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success! </strong> {{$success_message}}
        </div>
        @endisset
        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <div class="row">
                    <form action="" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <input type="text" name="from_date" readonly="" required="" value="{{$from_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy">
                                <div class="help-block"></div>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_date" readonly="" required="" value="{{$to_date}}" autocomplete="off" class="form-control form-control-inline date-picker" data-date-format="d M yyyy">
                                <div class="help-block"></div>
                            </div>
                            <div class="col-md-3">
                                <select name="company_id" required class="form-control" data-placeholder="Select...">
                                    <option value="0">Select company</option>
                                    @foreach ($company_list as $item)
                                    @if($item->type>1)
                                    <option @if($company_id==$item->company_id) selected @endif value="{{$item->company_id}}">{{$item->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <div class="help-block"></div>
                            </div>

                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Submit </button>
                            </div>
                            <div class="col-md-1">
                                <button name="export" type="submit" class="btn btn-success">Export </button>
                            </div>
                            <br>
                            <br>
                        </div>

                    </form>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <div class="panel panel-primary">
            <div class="panel-body" style="overflow: auto;">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
							<th>Logsheet no </th>
                            <th>Company name </th>
							<th>Car type </th>
                            <th>Car no</th>
                            <th>Pickup time </th>
                            <th>Drop time </th>
                            <th>Pickup  </th>
                            <th>Drop  </th>
                            
							<th>Toll</th>
							<th>Employee</th>
							<th>Remark</th>
                            <th style="width: 40px;">Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $item)
                        <tr class="odd gradeX">
                            <td>{{$item->date}}</td>
							<td>{{$item->logsheet_no}}</td>
                            <td>{{$item->company_name}}</td>
							<td>{{$item->car_type}}</td>
                            <td>{{$item->car_no}}</td>
                            <td>{{$item->pickup_time}}</td>
                            <td>{{$item->drop_time}}</td>
                            <td>{{$item->pickup_location}}</td>
                            <td>{{$item->drop_location}}</td>
                            
							<td>{{$item->toll}}</td>
							<td>{{$item->employee_name}}</td>
							<td>{{$item->remark}}</td>
                            <td>
                            <a href="/admin/mis/updatecompanymis/{{$item->link}}" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>

                                <a href="#"  onclick="$(this).closest('tr').remove();deletelink = '/admin/mis/deletecompanymis/{{$item->link}}'" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modal-danger"><i class="fa fa-remove"></i></a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2768566574593657"
     crossorigin="anonymous"></script>
<!-- Admin -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2768566574593657"
     data-ad-slot="5658933431"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="modal modal-danger fade" id="modal-danger">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Delete MIS</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you would not like to use this MIS in the future?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
                <a id="deleteanchor" href="#" onclick="deleteMIS();" data-dismiss="modal" class="btn btn-outline">Delete</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
var deletelink='';
function deleteMIS() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    
  };
  xhttp.open("GET", deletelink, true);
  xhttp.send();
}
</script>

@endsection