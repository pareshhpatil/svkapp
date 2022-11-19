@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/employee/advance">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Advance</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-social-usd"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-social-usd"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/logsheet">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Log sheet</h3>
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

    

    
    




</div>
<!-- /.row -->
</div>
@endsection
