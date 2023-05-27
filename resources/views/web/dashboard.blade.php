@extends('layouts.web')
@section('content')
<div class="row">

    <!-- Earning Reports -->

    <!--/ Earning Reports -->

    <!-- Support Tracker -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Welcome To Siddhivinayak Travel House</h5>
               </div>
                
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                        <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                            <h1 class="mb-0">164</h1>
                            <p class="mb-0">Total Passengers</p>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Total Rides</h6>
                                    <small class="text-muted">142</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                <div class="badge rounded bg-label-info p-1">
                                    <i class="ti ti-circle-check ti-sm"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Completed Rides</h6>
                                    <small class="text-muted">28</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center pb-1">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Cancelled Rides</h6>
                                    <small class="text-muted">20</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                    <img src="assets/img/banner.png" style="max-width: 100%;">

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('footer')

@endsection