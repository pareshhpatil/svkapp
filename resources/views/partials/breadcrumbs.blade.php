@if (count($breadcrumbs))
<i class="fa fa-bar"></i>
    <ul class="page-breadcrumb">    
    @foreach ($breadcrumbs as $breadcrumb)
    @if ($breadcrumb->url)
    <li class="ms-hover">
    @if($loop->first)
    <a href="/merchant/dashboard"><i class="fa fa-home"></i></a>
    @endif
    <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
    @if(!$loop->last)
    <i class="fa fa-angle-right"></i>
    @endif
    </li>
    @else
    <li class="ms-hover">{{ $breadcrumb->title }}
    @if(!$loop->last)
    <i class="fa fa-angle-right"></i>
    @endif
    </li>
    @endif
    @endforeach
    </ul>

@endif