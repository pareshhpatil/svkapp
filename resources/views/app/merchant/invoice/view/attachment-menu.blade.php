<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse" style="max-width: 151px;">
  
        <ul  class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            @isset($docs)
            @foreach ($docs as $row)
         
            <li class="@if(in_array($row['title'], $selectedDoc)) active open @endif">
                <a href="@if ($row['link']!=''){{$row['link']}} @else javascript:; @endif">
                   
                   
                    <span class="title">{{$row['title']}}</span>
                    @if($row['link']=='') <span class="arrow "></span> @endif
                </a>
                @if(!empty($row['menu']))
                <ul class="sub-menu">
                    @foreach ($row['menu'] as $row2)
                 
                    <li class="@if(in_array($row2['title'], $selectedDoc)) active open @endif">
                        <a href="@if ($row2['link']!='') #tab_{{$row2['link']}}  @else javascript:; @endif" data-toggle="tab">
                          
                            <span class="title">{{$row2['title']}}</span>
                            @if($row2['link']=='') <span class="arrow "></span> @endif
                        </a>
                      
                        @if(!empty($row2['menu']))
                        <ul class="sub-menu">
                            @foreach ($row2['menu'] as $row3)
                            <li class="@if(in_array($row3['title'], $selectedDoc)) active open @endif">
                                <a href="@if ($row3['link']!='') #tab_{{$row3['link']}} @else javascript:; @endif" data-toggle="tab">
                                 
                                    <span class="title">{{$row3['title']}}</span>
                                    @if($row3['link']=='') <span class="arrow "></span> @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
            @endisset
            {{-- <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                    <a href="javascript:;">
                        <i id="side-bar-toggle" 
                        style="position:fixed; bottom:50px; font-size: 25px; margin-left: 32px; width:32px" 
                        onMouseOver="this.style.color='#0f9dae'" 
                        onMouseOut="this.style.color='#275770'"
                         class="sidebartoggle fa fa-chevron-circle-left"></i>
                    </a>
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li> --}}
        </ul>
    </div>
</div>