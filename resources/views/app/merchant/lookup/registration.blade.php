@extends('app.master')

@section('content')
<div class="page-content">
    <h3 class="page-title">Registration lookups</h3>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        Search criteria
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title="">
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">

                    <form class="form-inline" role="form" action="" method="post">
                        <div class="form-group">
                            <input name="search_criteria" type="text" class="form-control input-xlarge"
                                placeholder="Email id or mobile" value="{{ $search_criteria ?? '' }}" required>
                            <!-- Upto 10 comma separated list supported -->

                        </div>
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-md blue" value="Search">
                        </div>

                    </form>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>
    </div>
    @isset($rows)
    <div class="row">
        <div class="col-md-12">
            <!-- Lookup registration table -->
            @if ($rows > 0)
            <div class="portlet ">
               
                <div class="portlet-body">

                    <table class="table table-striped table-bordered table-hover" id="table-no-export">
                        <thead>
                            <tr>
                                <th class="td-c">
                                    Name
                                </th>
                                <th class="td-c">
                                    Email Id
                                </th>
                                <th class="td-c">
                                    Mobile no
                                </th>
                                <th class="td-c">
                                    Registered date
                                </th>
                                <th class="td-c">
                                    Registration status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $first_name }} {{ $last_name}}</td>
                                <td>{{ $email_id }}</td>
                                <td>{{ $mobile_no }}</td>
                                <td>
                                    <x-localize :date="$registered_date" type="date" />
                                </td>
                                @if ($user_status <= 14) <td><span class="label label-sm label-warning">Registered,
                                        documentation pending</span></td>
                                    @else
                                    <td><span class="label label-sm label-success">Registered merchant</span></td>
                                    @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="alert alert-info">
                User has not registered with Swipez
            </div>
            @endif
        </div>
    </div>
    @endisset
</div>
@endsection
