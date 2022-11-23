@extends('app.master')




@section('content')
   <div class="page-content" >
    <div class="page-bar">  
        <span class="page-title" style="float: left;">{{$title}}</span>
        {{ Breadcrumbs::render() }}
    </div>
    
    <div class="row apps-shadow mb-2 mr-1" style="background-color: #ffffff;    margin-left: 2px;
    padding-right: 5px;max-width:1000px;" >
    
        <div class="col-lg-6" >
            <div class="apps-box ml-2 mt-2">
            <div class="row no-margin">
            <div class="col-xs-12">
          
            <h2 style="font-style: normal; font-weight: 600; font-size: 32px; line-height: 38px;color: #5C6B6B;"><strong>Pay all business bills right here!</strong></h2>
            <p style="font-weight: 400;font-size: 20px;line-height: 24px;color: #394242;">Now never miss a bill payment start paying all your utility bills right here from your Swipez dashboard.</p>
            <a class="btn blue mt-1 mb-1" style="box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.1), 0px 10px 10px rgba(0, 0, 0, 0.04); border-radius: 4px !important; "
            href="https://bills.pe/cTCPh5p" target="_blank">Get Started</a>   
        </div>
            
            </div>
          
            
            </div>
            </div>
            <div class="col-lg-6" >
                <div class="apps-box mt-1 mb-1">
                <div class="row">
                    <img alt="Pay your bills" class="img-fluid img-responsive"
                    src="{!! asset('images/pay_your_bills.png') !!}"  style="max-height: 300px;" />
                
                </div>
                
                </div>
                </div>
        </div>
    
        {{-- <div class="row ml-1">
        <h4 style="float: left;font-weight: 600; font-size: 18px;line-height: 21px;color: #859494;">LEARN MORE</h4>
        </div> --}}
       {{-- <div class="row  mb-2 mr-1" style="margin-left: 2px;
        padding-right: 5px;" >
            <div class="col-md-4 " style="padding-left: 0px !important;">
              
               
               <div class="list-group apps-shadow" style="text-align: center;background-color: #ffffff; ">
                  
                     <a href="#" class="list-group-item list-group-item-action rounded" style="font-weight: 400;
                     font-size: 20px;
                     line-height: 24px;
                     display: flex;
  justify-content: center;
  align-items: center;
                     color: #767676;height: 14.5%;border-top-right-radius:5px !important;border-top-left-radius:5px !important">
                      <span>How to create an invoice?</span>
                    
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" style="font-weight: 400;
                    font-size: 20px;
                    line-height: 24px;
                    display: flex;
  justify-content: center;
  align-items: center;
                    color: #767676;height: 14.5%;">How to create invoices in bulk using excel upload?</a>
                    <a href="#" class="list-group-item list-group-item-action" style="font-weight: 400;
                    font-size: 20px;
                    line-height: 24px;
                    display: flex;
  justify-content: center;
  align-items: center;
                    color: #767676;height: 14.5%;border-bottom-right-radius:5px !important;border-bottom-left-radius:5px !important">Learn to create invoices where payments have been received in advance.</a>
                   
                </div>
          
                
              
              
                
              
                </div>
                <div class="col-md-8 apps-shadow" style="background-color: #ffffff;">
                    <div style="cursor:pointer;height:286px">
                        <div id="video-promo-container" style="display: flex;
                        justify-content: center;
                        align-items: center;">
                            <div id="video-play-button"
                                style="position:absolute; top:0px; left:0px; right:0px; bottom:0px; z-index:400">
                                <span style="position:absolute; margin-top:-35px; margin-left:-20px; top:50%; left:48%">
                                    <svg aria-hidden="true"  focusable="false" data-prefix="fab" data-icon="youtube"
                                        class="h-16 pb-2" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 576 512" style="color: #ff0000;width: 44px;">
                                        <path fill="currentColor"
                                            d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z">
                                        </path>
                                    </svg>
                                </span>
                                
                            </div>
                           
                         
                        </div>
                        
                    </div>
                    </div>
            </div> --}}
        
       
   
    
    
      
    
 
        
    
          </div> 

@endsection