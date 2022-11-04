<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse" style="margin-top: -66px;">
        <ul style="padding-top:10px;" class="page-sidebar-menu {{$language}}" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <a itemprop="url" id="logo-wrapper-default" title="Swipez" href="{{$server_name}}"  style="margin-left:20px;display: block;">
                <img style="max-height: 60px;" id="logo-default"class="logo-default hidden-xs" src="/assets/admin/layout/img/logo.png?v=6" alt="Briq" title="Briq">
            </a>
            @isset($menus[0])
            @foreach ($menus[0] as $row)
            <li class="menu-items @if(in_array($row['id'], $selectedMenu)) active open @endif">
                <a href="@if ($row['link']!=''){{$row['link']}} @else javascript:; @endif">
                    <i class="{{$row['icon']}}"></i>
                    @if($row['id']==170)<span class="badge badge-success">New</span>@endif
                    <span class="title">{{$row['title']}}</span>
                    @if($row['link']=='') <span class="arrow "></span> @endif
                </a>
                @isset($menus[$row['id']])
                <ul class="sub-menu">
                    @foreach ($menus[$row['id']] as $row2)
                    <li class="menu-items @if(in_array($row2['id'], $selectedMenu)) active open @endif">
                        <a href="@if ($row2['link']!=''){{$row2['link']}} @else javascript:; @endif">
                            @if ($row2['icon']!='')<i class="{{$row2['icon']}}"></i> @endif
                            <span class="title">{{$row2['title']}}</span>
                            @if($row2['link']=='') <span class="arrow "></span> @endif
                        </a>
                        @isset($menus[$row2['id']])
                        <ul class="sub-menu">
                            @foreach ($menus[$row2['id']] as $row3)
                            <li class="@if(in_array($row3['id'], $selectedMenu)) active open @endif">
                                <a href="@if ($row3['link']!=''){{$row3['link']}} @else javascript:; @endif">
                                    @if ($row3['icon']!='')<i class="{{$row3['icon']}}"></i> @endif
                                    <span class="title">{{$row3['title']}}</span>
                                    @if($row3['link']=='') <span class="arrow "></span> @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endisset
                    </li>
                    @endforeach
                </ul>
                @endisset
            </li>
            @endforeach
            @endisset
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                    <a href="javascript:;">
                    <img  id="side-bar-toggle" style="position:fixed; bottom:50px; font-size: 25px; margin-left: 32px; width:32px;color: rgb(99, 102, 158);" 
                    src="<?php echo '/assets/admin/layout/img/circled-chevron-left.png'; ?>" alt="open"
                    class="sidebartoggle">
                    </a>
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
        </ul>
    </div>
</div>