@extends('layouts.web')
@section('header')
<link rel="stylesheet" href="/assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />

<script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}"></script>
<style>
    #map {
      height: 550px;
      width: 100%;
    }
  </style>
@endsection
@section('content')
<div class="row">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="fw-bold py-2"><span class="text-muted fw-light">Ride /</span>
                Details
            </h4>
        </div>
    </div>
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">

        <!-- User Card -->
        <div class="card mb-4">
            <div class="card-body">
                @isset($driver->name)
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center flex-column">
                        <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{$driver->photo}}" style="max-width: 200px;" alt="User avatar">
                        <div class="user-info text-center">
                            <h4 class="mb-2">{{$driver->name}}</h4>
                            <span class="badge bg-label-secondary mt-1">Driver</span>
                        </div>
                    </div>
                </div>
                @endisset
                <div class="d-flex justify-content-around flex-wrap  pb-4 border-bottom">

                </div>
                <p class="mt-4 small text-uppercase text-muted">Details</p>
                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="fw-semibold me-1">Cab Number:</span>
                            <span>{{$vehicle->number}}</span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Mobile:</span>
                            <span>{{$driver->mobile}}</span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Status:</span>
                            @if($det->status==0)
                            <span class="badge bg-label-secondary">Pending</span>
                            @elseif($det->status==1)
                            <span class="badge bg-label-warning">Cab Assigned</span>
                            @elseif($det->status==2)
                            <span class="badge bg-label-success">Started</span>
                            @elseif($det->status==3)
                            <span class="badge bg-label-danger">Cancelled</span>
                            @elseif($det->status==4)
                            <span class="badge bg-label-danger">Rejected</span>
                            @elseif($det->status==5)
                            <span class="badge bg-label-success">Completed</span>
                            @endif


                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Date Time:</span>
                            <span>{{$det->date}} {{$det->start_time}}</span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Start Location:</span>
                            <span>{{$det->start_location}}</span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">End Location:</span>
                            <span>{{$det->end_location}}</span>
                        </li>

                    </ul>
                    <div class="d-flex justify-content-center">
                        <a href="javascript:;" class="btn btn-success me-3 waves-effect waves-light" data-bs-target="#editUser" data-bs-toggle="modal">Approve</a>
                        <a href="javascript:;" class="btn btn-primary suspend-user waves-effect">Call Driver</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /User Card -->
        <!-- Plan Card -->

        <!-- /Plan Card -->
    </div>
    <!--/ User Sidebar -->






    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <!-- User Pills -->
        <ul class="nav nav-pills flex-column flex-md-row mb-4">
            <li class="nav-item">
                <a class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true" href="javascript:void(0);"><i class="ti ti-user-check ti-xs me-1"></i>Route</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true" href="javascript:void(0);">
                    <i class="ti ti-star ti-xs me-1"></i>Ratings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" role="tab" data-bs-toggle="tab"  data-bs-target="#navs-top-toll" aria-controls="navs-top-toll" aria-selected="true" href="javascript:void(0);">
                    <i class="ti ti-file ti-xs me-1"></i>Toll & Parking</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" role="tab" data-bs-toggle="tab" onclick="initMap();" data-bs-target="#navs-top-map" aria-controls="navs-top-map" aria-selected="true" href="javascript:void(0);">
                    <i class="ti ti-location ti-xs me-1"></i>Location Map</a>
            </li>
            
        </ul>
        <!--/ User Pills -->

        <!-- Project table -->

        <!-- /Project table -->

        <!-- Activity Timeline -->


        <div class="tab-content p-0">
            <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                <div class="card mb-4">
                    <h5 class="card-header">Ride Timeline</h5>
                    <div class="card-body pb-0">
                        <ul class="timeline mb-0">
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Ride Start</h6>
                                        <small class="text-muted">{{$det->start_time}}</small>
                                    </div>
                                    <p class="mb-2">Ride start from {{$det->start_location}}</p>

                                </div>
                            </li>
                            @foreach($ride_passengers as $v)
                            @if($det->type=='Pickup')
                            @if($v->cab_time!='' && $v->cab_time!=$v->in_time)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Cab reached at {{$v->name}} pickup point</h6>
                                        <small class="text-muted">{{$v->cab_time}}</small>
                                    </div>
                                    <p class="mb-2">Cab reached at {{$v->pickup_location}}</p>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="{{$v->icon}}" alt="{{$v->name}}" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">
                                            @if(isset($v->in_time))
                                            {{$v->name}} got in the cab
                                            @elseif($v->status==3)
                                            {{$v->name}} Canceled ride
                                            @elseif($v->status==4)
                                            {{$v->name}} No show
                                            @else
                                            {{$v->name}} Pickup
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            @if(isset($v->in_time))
                                            {{$v->in_time}}
                                            @elseif($v->status!=3 && $v->status!=4)
                                            {{$v->pickup_time}}
                                            @endif
                                        </small>
                                    </div>
                                    <p class="mb-2">
                                        @if(isset($v->in_time))
                                        Cab moved from
                                        @else Pickup location
                                        @endif
                                        {{$v->pickup_location}}
                                    </p>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="{{$v->icon}}" alt="{{$v->name}}" width="15" class="me-2">
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @else
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-info"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">

                                            @if($v->status==2)
                                            {{$v->name}} dropped
                                            @elseif($v->status==3)
                                            {{$v->name}} Canceled ride
                                            @elseif($v->status==4)
                                            {{$v->name}} No show
                                            @else
                                            {{$v->name}} Drop
                                            @endif

                                        </h6>
                                        <small class="text-muted">
                                            @if($v->status==2)
                                            {{$v->drop_time}}
                                            @elseif($v->status!=3 && $v->status!=4)
                                            {{$v->drop_time}}
                                            @endif
                                        </small>
                                    </div>
                                    <p class="mb-2">Drop location {{$v->drop_location}}</p>
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-3">
                                            <img src="{{$v->icon}}" alt="{{$v->name}}" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @endforeach
                            @if($det->status==5)
                            <li class="timeline-item timeline-item-transparent border-0">
                                <span class="timeline-point timeline-point-danger"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Ride ended</h6>
                                        <small class="text-muted">{{$det->end_time}}</small>
                                    </div>
                                    <p class="mb-0">Ride ended at {{$det->end_location}}</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                <div class="card mb-4">
                    <h5 class="card-header">Ratings</h5>
                    <div class="" style="margin-left: 20px;">
                        @foreach($ride_passengers as $v)
                        @if($v->rating>0)
                        <div class="col-md-6 col-sm-6 col-12 mb-4">
                            <div class="card">
                                <h5 class="card-header">{{$v->name}}</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="javascript:void(0)" class="me-3">
                                                <img src="{{$v->icon}}" alt="{{$v->name}}" class="rounded-circle">
                                            </a>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="read-only-ratings jq-ry-container" data-rateyo-read-only="true" readonly="readonly" style="width: 192px;">
                                                <div class="jq-ry-group-wrapper">
                                                    <div class="jq-ry-normal-group jq-ry-group"><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="32px" height="32px" @if($v->rating>0) fill="#f39c12" @else fill="gray" @endif >
                                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon>
                                                        </svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="32px" height="32px" @if($v->rating>1) fill="#f39c12" @else fill="gray" @endif style="margin-left: 8px;">
                                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon>
                                                        </svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="32px" height="32px" @if($v->rating>2) fill="#f39c12" @else fill="gray" @endif style="margin-left: 8px;">
                                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon>
                                                        </svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="32px" height="32px" @if($v->rating>3) fill="#f39c12" @else fill="gray" @endif style="margin-left: 8px;">
                                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon>
                                                        </svg><!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 12.705 512 486.59" x="0px" y="0px" xml:space="preserve" width="32px" height="32px" @if($v->rating>4) fill="#f39c12" @else fill="gray" @endif style="margin-left: 8px;">
                                                            <polygon points="256.814,12.705 317.205,198.566 512.631,198.566 354.529,313.435 414.918,499.295 256.814,384.427 98.713,499.295 159.102,313.435 1,198.566 196.426,198.566 "></polygon>
                                                        </svg></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="navs-top-toll" role="tabpanel">
                <div class="card mb-4">
                    <h5 class="card-header">Toll & Parking Details</h5>
                    <div class="" style="margin-left: 30px;">
                        <p>No Records Found</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-top-map" role="tabpanel">
                <div class="card mb-4">
                    <h5 class="card-header">Locations</h5>
                    <div class="" >
                        
                        <div id="map"></div>

                    </div>
                </div>
            </div>

        </div>


        <!-- /Activity Timeline -->

        <!-- Invoice table -->

        <!-- /Invoice table -->
    </div>
    <!--/ User Content -->
</div>


@endsection
  <script>
    function initMap() {
      // Map initialization
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13
      });

      // Directions service
      var directionsService = new google.maps.DirectionsService();
      var directionsDisplay = new google.maps.DirectionsRenderer();
      directionsDisplay.setMap(map);

      // Define the waypoints (stops) with titles
      var waypoints = [
	  @if($det->type=='Drop')
		{
          location: '{{$company_address}}',
          stopover: true,
          title: 'Office'
        },
      @endif
      @foreach($ride_passengers as $v)
      @if($v->status!=3 && $v->status!=4)
        {
          location: '{{$v->address}}',
          stopover: true,
          title: '{{$v->name}}'
        },
      @endif
      @endforeach
      @if($det->type=='Pickup')
		{
          location: '{{$company_address}}',
          stopover: true,
          title: 'Office'
        }
     @endif
        
      ];
	  
	 var origin = waypoints[0].location;
     var destination = waypoints[waypoints.length - 1].location;

      // Calculate the directions
      var request = {
        origin: origin,
        destination: destination,
        waypoints: waypoints.map(function(waypoint) {
          return {
            location: waypoint.location,
            stopover: waypoint.stopover
          };
        }),
        optimizeWaypoints: true,
        travelMode: 'DRIVING'
      };

      directionsService.route(request, function(result, status) {
        if (status == 'OK') {
          directionsDisplay.setDirections(result);

          // Add markers with titles for each waypoint
          var route = result.routes[0];
          var waypointsOrder = result.routes[0].waypoint_order;
          for (var i = 0; i < waypointsOrder.length; i++) {
            var waypointIndex = waypointsOrder[i];
            var waypoint = waypoints[waypointIndex];
            var marker = new google.maps.Marker({
              position: route.legs[i].end_location,
              map: map,
              title: waypoint.title
            });
          }
        }
      });
    }
</script>


@section('footer')





<script src="/assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="/assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

<script src="/assets/js/app-invoice-add.js"></script>
<script src="/assets/vendor/libs/select2/select2.js"></script>

<script src="/assets/js/forms-selects.js"></script>
<script src="/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script>
    var defaultTime = '09:00';
    $('#bs-datepicker-autoclose').datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd MM yyyy',
        orientation: isRtl ? 'auto right' : 'auto left'
    });
</script>


@endsection