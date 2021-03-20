@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/trip/add">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Add Trip</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-android-clipboard"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-android-clipboard"></i>
                </div>
            </div>
        </a>
    </div>



    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/trip/list/upcoming">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Upcoming Trips</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-calendar"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-calendar"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/trip/list/past">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>Past Trips</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-calendar"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-calendar"></i>
                </div>
            </div>
        </a>
    </div>
   

</div>
<!-- /.row -->
</div>
@endsection
