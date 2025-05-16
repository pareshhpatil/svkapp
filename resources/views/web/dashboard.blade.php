@extends('layouts.web')
@section('content')
<div class="row">

    <!-- Earning Reports -->

    <!--/ Earning Reports -->

    <!-- Support Tracker -->

    

    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <small class="d-block mb-1 text-muted">Employees</small>
                </div>
                <h4 class="card-title mb-1">{{$total_employee}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-label-info p-1 rounded"><i class="ti ti-man ti-xs"></i></span>
                            <p class="mb-0">Male</p>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap">{{$male}}</h5>
                        <small class="text-muted">{{$malePercent}}%</small>
                    </div>
                    <div class="col-4">
                        <div class="divider divider-vertical">
                            <div class="divider-text">
                                <span class="badge-divider-bg bg-label-secondary">VS</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                            <p class="mb-0">Female</p>
                            <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-woman ti-xs"></i></span>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">{{$female}}</h5>
                        <small class="text-muted">{{$femalePercent}}%</small>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-4">
                    <div class="progress w-100" style="height: 8px">
                        <div class="progress-bar bg-info" style="width: {{$malePercent}}%" role="progressbar" aria-valuenow="{{$malePercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{$femalePercent}}%" aria-valuenow="{{$femalePercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">{{$month}}</h5>
                </div>
                
                <!-- </div> -->
            </div>
            <div class="card-body mt-5">
                
                <div class="row">

                    <div class="col-12 col-md-12">
                        <div id="slabwiseReports"></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-lg-12 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">{{$month}}</h5>
                    <small class="text-muted">Monthly Employees Ride Report</small>
                </div>
                <!-- <div class="dropdown">
                    <button class="btn p-0" type="button" id="earningReportsId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                        <a class="dropdown-item" href="javascript:void(0);">Previous Month</a>
                        <a class="dropdown-item" href="javascript:void(0);">Next Month</a>
                    </div>
                </div> -->
                <!-- </div> -->
            </div>
            <div class="card-body mt-5">
                <div class="row">
                    <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                        <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                            <h1 class="mb-0">{{$all}}</h1>
                            <div class="badge rounded bg-label-success"></div>
                        </div>
                        <small class="text-muted"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div id="weeklyEarningReports"></div>
                    </div>
                </div>
                <div class="border rounded p-3 mt-2">
                    <div class="row gap-4 gap-sm-0">
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-success p-1">
                                    <i class="ti ti-checkbox ti-sm"></i>
                                </div>
                                <h6 class="mb-0">Completed</h6>
                            </div>
                            <h4 class="my-2 pt-1">{{$completed}}</h4>
                            <div class="progress w-75 " style="height: 4px">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{$completedPercent}}%" aria-valuenow="{{$completedPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-circle-x ti-sm"></i></div>
                                <h6 class="mb-0">Cancelled</h6>
                            </div>
                            <h4 class="my-2 pt-1">{{$cancelled}}</h4>
                            <div class="progress w-75" style="height: 4px">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{$cancelledPercent}}%" aria-valuenow="{{$cancelledPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-danger p-1">
                                    <i class="ti ti-road-off ti-sm"></i>
                                </div>
                                <h6 class="mb-0">No Show</h6>
                            </div>
                            <h4 class="my-2 pt-1">{{$noshow}}</h4>
                            <div class="progress w-75" style="height: 4px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$noshowPercent}}%" aria-valuenow="{{$noshowPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>
@endsection

@section('footer')
<script>
    daysArray = {{$dayValues}};
    daysLabel =  {{$days}};

   var parsedLabels = @json($slabs);
   var  parsedValues=  @json($slabsValues);

   var slabLabel = JSON.parse(parsedLabels);
   var slabArray = JSON.parse(parsedValues);

</script>
<script src="/assets/js/dashboards-analytics.js?v=1"></script>

@endsection