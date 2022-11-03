<header class="text-gray-600 body-font">
    <div class="container lg:px-20 md:px-4 px-4 py-6 mx-auto flex items-center flex-row justify-between">
        <a href="{{url('m/'.$merchant->display_url.'/')}}" class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900">
            @if($logo == null)
            @if(strpos($merchant_detail->logo,'/'))
                <img class="h-12 md:h-10 " src="{{asset($merchant_detail->logo)}}" alt="{{$merchant->company_name}}" title="{{$merchant->company_name}}">
            @else
                <img class="h-12 md:h-10 " src="{{asset('uploads/images/landing/'.$merchant_detail->logo)}}" alt="{{$merchant->company_name}}" title="{{$merchant->company_name}}">
            @endif
            @else
                <img class="h-14 md:h-10" src="{{asset($logo)}}" alt="{{$merchant->company_name}}" title="{{$merchant->company_name}}">
            @endif
        </a>
        <button class="inline-block md:hidden w-12 h-12 text-color-1 p-1" id="hamburger" data-tour="nav-bar">
            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
        </button>
        @isset($page_setting['hidemenu'])
        @else
        <nav id="navbar-list" data-tour="nav-bar" class="sm:text-center navbar-list hidden md:block absolute md:relative top-16 left-0 md:top-0 z-20 md:flex flex-col md:flex-row md:space-x-6 font-semibold w-full md:w-auto bg-white shadow-md md:rounded-none md:shadow-none md:bg-transparent p-6 pt-0 md:p-0"
        :class="{ 'flex' : mobileMenuOpen , 'hidden' : !mobileMenuOpen}">
            <a href="@if($merchant_website!=false){{$merchant_website}}@else{{url('/m/'.$merchant->display_url.'/')}}@endif" class="text-center block py-2 md:py-1 hover:text-gray-700 {{ url('/m/'.$merchant->display_url.'/') == url()->current() ? 'text-color-1':'text-color-2' }}">Home</a>
            <a href="{{url('/m/'.$merchant->display_url.'/paymybill')}}" class="text-center block py-2 md:py-1 {{ url('/m/'.$merchant->display_url.'/paymybill') == url()->current() ? 'text-color-1':'text-color-2' }} hover:text-gray-700">Pay my bill</a>
            <a href="{{url('/m/'.$merchant->display_url.'/payment-link')}}" class="text-center block py-2 md:py-1 {{ url('/m/'.$merchant->display_url.'/payment-link') == url()->current() ? 'text-color-1':'text-color-2' }} hover:text-gray-700">Payment link</a>
            <a href="@if($merchant_website!=false){{$merchant_website}}@else{{url('/m/'.$merchant->display_url.'/policies')}}@endif" class="text-center block py-2 md:py-1 {{ url('/m/'.$merchant->display_url.'/policies') == url()->current() ? 'text-color-1':'text-color-2' }} hover:text-gray-700">Policies</a>
            <a href="@if($merchant_website!=false){{$merchant_website}}@else{{url('/m/'.$merchant->display_url.'/aboutus')}}@endif" class="text-center block py-2 md:py-1 {{ url('/m/'.$merchant->display_url.'/aboutus') == url()->current() ? 'text-color-1':'text-color-2' }} hover:text-gray-700">About us</a>
            <a href="@if($merchant_website!=false){{$merchant_website}}@else{{url('/m/'.$merchant->display_url.'/contactus')}}@endif" class="text-center block py-2 md:py-1 {{ url('/m/'.$merchant->display_url.'/contactus') == url()->current() ? 'text-color-1':'text-color-2' }} hover:text-gray-700">Contact us</a>
        </nav>
        @endisset
    </div>
</header>
<!-- end section header -->