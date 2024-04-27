@extends('layouts.admin')

@section('content')
<div class="row">
@if(Session::get('admin_id')==1)
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
        <a href="/admin/employee/list">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>Employee</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-ios-speedometer"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-speedometer"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/employee/absent">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Absent</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-android-person"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-android-person"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/logsheet/approve">
            <div class="small-box bg-fuchsia">
                <div class="inner">
                    <h3>Approve</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-ios-checkmark"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-android-checkmark-circle"></i>
                </div>
            </div>
        </a>
    </div>
    @endif
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

    


    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/vehicle/list">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>Vehicle</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-model-s"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-model-s"></i>
                </div>
            </div>
        </a>
    </div>



    


    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="/admin/company/list">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Company</h3>
                    <div class="hidden-xs">
                        <p>&nbsp;</p>
                        <br>
                    </div>
                    <div class="visible-xs">
                        <p style="font-size: 30px;"><i class="ion ion-ios-home"></i></p>
                    </div>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-home"></i>
                </div>
            </div>
        </a>
    </div>
    
    
    


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
<!-- /.row -->
</div>
@endsection
