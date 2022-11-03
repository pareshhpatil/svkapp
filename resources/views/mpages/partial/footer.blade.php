<footer class="text-gray-600 body-font">
    <div class="container lg:px-40 md:px-20 py-6 mx-auto flex items-center sm:flex-row flex-col">
        @if($isLogin==1)
            <a href="{{env('APP_URL')}}/site/company-profile/home" data-tour="edit-company-website" class="bg-aqua chat-btn bg-indigo-500 text-white active:bg-indigo-600 font-bold uppercase text-xs px-4 py-3 rounded-full shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                <i class="fas fa-pen"> </i>&nbsp;&nbsp;Edit page
            </a>
        @endif
        <a href="/m/{{$merchant->display_url}}/" class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900">
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
        @isset($page_setting['hidemenu'])
        @else
        <nav id="navbar-list" data-tour="nav-bar" class="sm:text-center navbar-list hidden md:block absolute md:relative top-16 left-6 md:top-0 z-20 md:flex flex-col md:flex-row md:space-x-6 font-semibold w-full md:w-auto bg-white shadow-md md:rounded-none md:shadow-none md:bg-transparent p-6 pt-0 md:p-0"
        :class="{ 'flex' : mobileMenuOpen , 'hidden' : !mobileMenuOpen}">
            <a href="{{env('APP_URL')}}/billing-software" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700">Billing software</a>
            <a href="{{env('APP_URL')}}/payment-collections" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700">Payment gateway</a>
            <a href="{{env('APP_URL')}}/e-invoicing" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700">E-invoicing</a>
            <a href="{{env('APP_URL')}}/gst-reconciliation-software" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700">GST reconciliation</a>
            <a href="{{env('APP_URL')}}/invoice-template" class="text-center block py-2 md:py-1 text-color-2 hover:text-gray-700">Free invoice templates</a>
        </nav>
        @endisset
        <div class="md:ml-auto sm:ml-auto text-center">
            <p class="text-sm text-gray-500 sm:ml-6 sm:mt-0 mt-4">© {{ucwords($merchant->company_name)}}. All Rights Reserved.</p>
        </div>
    </div>
</footer>