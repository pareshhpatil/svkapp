@extends('app.master')

@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{ $title }}</span>
            {{ Breadcrumbs::render() }}
            {{-- <a href="#create"  data-toggle="modal" class="btn blue pull-right"> Region settings</a> --}}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            <div class="col-md-12">
                @include('layouts.alerts')
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">

                    <div class="">
                        <div class="portlet light">
                            <form action="/merchant/setting/region/save" method="post" class="form-horizontal form-row-sepe">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           
                            <div class="portlet-body form">

                                {{-- <h4 class="form-section">Currency & Religion Settings</h4> --}}
                                <!-- Start Bulk upload details -->
                               


                                <div id="plan_invoice_create">
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-9">

                                            <div class="form-group">
                                                <label class="control-label col-md-3" >Timezone </label>
                                                <div class="col-md-5">
                                  
                                                    <select name="selecttimezone" required=""
                                                        class="form-control select2me select2-offscreen"
                                                        data-placeholder="Select..." tabindex="-1" title="">
                                                        <option value=""></option>
                                                        
                                                        @foreach (timezone_identifiers_list() as $timezone)
                                                        @php
                                                            $currentTimeInZone = new DateTime("now", new DateTimeZone($timezone));
                                                            $currentTimeDiff = $currentTimeInZone->format('P');
                                                           $def_time= $default[0]['timezone']?$default[0]['timezone']:'America/Cancun';
                                                        @endphp
                                                        <option @if($def_time==$timezone) selected @endif value="{{ $timezone }}" >(GMT{{ $currentTimeDiff}}) {{ $timezone }}</option>
                                                        @endforeach


                                                    </select>
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="row">
                                        <div class="col-md-9">
@php
   $def_curr= $default[0]['currency']?$default[0]['currency']:'USD';
@endphp
                                            <div class="form-group">
                                                <label class="control-label col-md-3" >Currency </label>
                                                <div class="col-md-5">
                                                   
                                                    <select name="selectcurrency" required=""
                                                        class="form-control select2me select2-offscreen"
                                                        data-placeholder="Select..." tabindex="-1" title="">
                                                        <option value=""></option>
                                                     
                                                        @foreach($currency_list as $key=>$item)
                                                       
                                                        <option @if($def_curr==$item['code']) selected @endif  value="{{$item['code']}}">{{$item['name']}} - {{$item['code']}}</option>
                                                        @endforeach


                                                    </select>
                                                    <br>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-9">

                                            <div class="form-group">
                                                <label class="control-label col-md-3" style="padding-top:0px !important">Time format </label>
                                                <div class="col-md-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" @if($default[0]['time_format']?$default[0]['time_format']:'24'=='24') checked="true" @endif   type="radio" name="timeformat" id="inlineRadio1" value="24">
                                                        <label class="form-check-label" for="inlineRadio1">24 hour</label>
                                                      </div>
                                                </div>
                                                      <div class="col-md-3">
                                                        
                                                      <div class="form-check form-check-inline">
                                                        <input class="form-check-input" @if($default[0]['time_format']=='12') checked="true" @endif  type="radio" name="timeformat" id="inlineRadio2" value="12">
                                                        <label class="form-check-label" for="inlineRadio2">12 hour</label>
                                                      </div>
                                                        
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-9">

                                            <div class="form-group">
                                                <label class="control-label col-md-3" style="padding-top:0px !important">Date format </label>
                                              
                                                <div class="col-md-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input"  type="radio" name="dateformat" id="inlineRadio1" @if($default[0]['date_format']?$default[0]['date_format']:'M d yyyy'=='M d yyyy') checked="true" @endif   value="M d yyyy">
                                                        <label class="form-check-label" for="inlineRadio1">Month DD YYYY</label>
                                                      </div>
                                                </div>
                                                      <div class="col-md-3">
                                                        
                                                      <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="dateformat" id="inlineRadio2" @if($default[0]['date_format']=='d M yyyy') checked="true" @endif  value="d M yyyy">
                                                        <label class="form-check-label" for="inlineRadio2">DD Month YYYY</label>
                                                      </div>
                                                        
                                                   
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <br/>

                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <a href="/merchant/profile/settings" class="btn btn-default">Cancel</a>
                                                <input type="submit" class="btn blue" value="Save changes">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    </div>
    <!-- END CONTENT -->


    


   

  
@endsection
