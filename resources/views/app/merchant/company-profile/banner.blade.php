                                <div class="row">
                                    <div class="col-xs-12 fileinput fileinput-new banner-main"  data-provides="fileinput">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div data-tour="banner-image" class="fileinput-preview thumbnail banner-container image-fluid" style="min-width: 300px;" data-trigger="fileinput">
                                                @if ($details->banner!="")
                                                <img class="image-fluid" src="@if(strpos($details->banner, '/'))/{{$details->banner}}@else/uploads/images/landing/{{$details->banner}}@endif" style="object-fit:fill"></div>
                                                @endif                                                
                                                <!-- <img src="/uploads/images/landing/FTmsxD9JHZrxQ0nOM0TxJn_Ohm_7_tEyllYK.jpg"></div> -->
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new">
                                                        Upload banner </span>
                                                    <span class="fileinput-exists">
                                                        Change </span>
                                                    <input class="to_be_disabled" @if($details->publishable==0) disabled @endif type="file" name="banner" accept="image/*">
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                    Remove </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div data-tour="logo" class="col-md-6 fileinput fileinput-new logo-main" style="position: absolute;" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            @if($details->logo!="")
                                            <img class="img-responsive" style="max-width: 300px;max-height:150px;" src="@if(strpos($details->logo, '/'))/landingpage/default-logo.png @else /uploads/images/landing/{{$details->logo}} @endif" alt=""/>
                                            @endif
                                            <!-- <img class="img-responsive" style="max-width: 300px;max-height:150px;" src="/uploads/images/landing/landingpage/default-logo.png" alt=""/> -->
                                        </div>

                                        <div  class="fileinput-preview fileinput-exists thumbnail logo-select">
                                        </div>
                                        <div>
                                            <span class="btn btn-sm default btn-file">
                                                <span class="fileinput-new">
                                                    Select logo </span>
                                                <span class="fileinput-exists">
                                                    Change </span>
                                                <input class="to_be_disabled" @if($details->publishable==0) disabled @endif type="file" name="logo" accept="image/*">
                                            </span>
                                            <a href="javascript:;" class="btn-sm btn default fileinput-exists" data-dismiss="fileinput">
                                                Remove </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row " style="margin-top: 350px;">
                                    {{-- <ul data-tour="company-page-menus" class="mix-filter pull-right">
                                        <a href="{{url('site/company-profile/home')}}"> <li class="filter {{$type=='home'?'active':''}}">
                                                Home
                                            </li></a>
                                        <a href="{{url('site/company-profile/policies')}}"><li class="filter {{$type=='policies'?'active':''}}" >
                                                Policies
                                            </li></a>
                                        <a href="{{url('site/company-profile/aboutus')}}"><li class="filter {{$type=='aboutus'?'active':''}}" >
                                                About us
                                            </li></a>
                                        <a href="{{url('site/company-profile/contactus')}}"><li class="filter {{$type=='contactus'?'active':''}}">
                                                Contact us
                                            </li></a>
                                    </ul> --}}
                                    <div class="extra-breaks" style="display:none">
                                        <br><br><br>
                                    </div>
                                    <ul data-tour="company-page-menus" class="nav company-profile-nav-menu nav-pills pull-right">
                                        <li class="active"><a data-toggle="pill" href="#home-panel">Home</a></li>
                                        <li><a data-toggle="pill" href="#policy-panel">Policies</a></li>
                                        <li><a data-toggle="pill" href="#aboutus-panel">About us</a></li>
                                        <li><a data-toggle="pill" href="#contactus-panel">Contact us</a></li>
                                    </ul>
                                </div>
