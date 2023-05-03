@php $mode=(isset($subscription->mode))? $subscription->mode : 0; @endphp
@php $repeat_every=(isset($subscription->repeat_every))? $subscription->repeat_every : 0; @endphp
@php $repeat_on=(isset($subscription->repeat_on))? $subscription->repeat_on : 0; @endphp
@php $end_mode=(isset($subscription->end_mode))? $subscription->end_mode : 0; @endphp
<h3 class="form-section">Subscription section</h3>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">Mode</label>
            <div class="col-md-8">
                <select onchange="repeatChange(this.value);" data-cy="subscription-mode" name="mode" id="mode" class="form-control">
                    <option selected value="3">Monthly</option>
                    <option @if($mode==1) selected @endif value="1">Daily</option>
                    <option @if($mode==2) selected @endif value="2">Weekly</option>
                    <option @if($mode==4) selected @endif value="4">Yearly</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Repeat every</label>
            <div class="col-md-6">
                <input type="number" min="1" max="365" @if($repeat_every>1) value="{{$repeat_every??1}}" @else value="1" @endif class="form-control" data-cy="subscription-repeat-every" name="repeat_every" onblur="diplayText();" id="repeat_every">
                    
            </div>
            <label class="control-label col-md-2" id="repeat_text"></label>
        </div>

        <div id="repeat_on" style="display: none;" class="form-group">
            <label class="control-label col-md-4">Repeat on</label>
            <div class="col-md-8">
                <select class="form-control" id="repeat_on_" data-cy="subscription-repeat-on" name="repeat_on" onchange="diplayText();">
                    <option @if($repeat_on==1) selected @endif value="1">Sunday</option>
                    <option @if($repeat_on==2) selected @endif value="2">Monday</option>
                    <option @if($repeat_on==3) selected @endif value="3">Tuesday</option>
                    <option @if($repeat_on==4) selected @endif value="4">Wednesday</option>
                    <option @if($repeat_on==5) selected @endif value="5">Thrusday</option>
                    <option @if($repeat_on==6) selected @endif value="6">Friday</option>
                    <option @if($repeat_on==7) selected @endif value="7">Saturday</option>

                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-md-4">Start on <span class="required">* </span></label>
            <div class="col-md-8">
                @if(isset($subscription->start_date))
                <input type="text" required @if($subscription->last_sent_date!='2014-01-01 00:00:00')readonly class="form-control" @else class="form-control  date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" data-date-start-date="startDate" @endif name="bill_date" id="start_date" value='<x-localize :date="$subscription->start_date" type="date" />' onchange="duedateSummery();
                                                    diplayText();" placeholder="Select start date">
                <input type="hidden" name="exist_start_date" value="{{$subscription->start_date}}">
                @else
                <input type="text" data-cy="subscription-start-date" name="bill_date" id="start_date" required onchange="duedateSummery();
                                                diplayText();" class="form-control  date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" data-date-start-date="startDate" value="" placeholder="Select start date">
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Due date <span class="required">* </span></label>
            <div class="col-md-8">
                <input type="text" data-cy="subscription-due-date" name="due_date" required onchange="duedateSummery();"  @if(isset($subscription->start_date)) value='<x-localize :date="$subscription->due_date" type="date" />' @endif id="due_datetime" class="form-control  date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" data-date-start-date="startDate" placeholder="Select due date">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-4">Summary</label>
            <div class="col-md-8">
                <label class="control-label" id="due_datetime_text">Due same day</label>
                <input type="hidden" name="due_datetime_diff" value="{{$subscription->due_diff??0}}" id="due_datetime_diff">
                @if($is_carry!=1)
                <input type="hidden" name="carry_due" value="0">
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-3">End</label>
            <div class="radio-list col-md-9">
                <div class="form-group">
                    <div class="col-md-5">
                        <label>
                            <input type="radio" data-cy="subscription-end-mode1" checked name="end_mode" @if($end_mode == 1)checked @endif  onchange="endModeChange(this.value);" id="optionsRadios2" value="1"> Never</label>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">&nbsp;</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-5">
                        <label>
                            <input type="radio" data-cy="subscription-end-mode2" name="end_mode" id="optionsRadios2" @if($end_mode == 2)checked @endif onchange="endModeChange(this.value);" value="2"> Occurences</label>
                    </div>
                    <div class="col-md-7">
                        <div class=""><input type="number" data-cy="subscription-occurence" min="1" name="occurrence" id="occurence_text" @if($end_mode == 2) value="{{$subscription->occurrences}}" @else disabled @endif onchange="diplayText();"  class="form-control input-xsmall  "></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <label>
                            <input type="radio" name="end_mode" data-cy="subscription-end-mode3" id="optionsRadios3" @if($end_mode == 3)checked @endif onchange="endModeChange(this.value);" value="3"> End date</label>
                    </div>
                    <div class="col-md-7">
                        <div class=""><input type="text" onchange="diplayText();" data-cy="subscription-end-date" name="end_date" @if($end_mode == 3) value='<x-localize :date="$subscription->end_date" type="date" />' @endif disabled id="end_date_text" class="form-control input-small  date-picker" autocomplete="off" {!!$validate->date!!} data-date-format="{{ Session::get('default_date_format')}}" data-date-start-date="startDate" placeholder="Select end date"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Summary</label>
            <div class="col-md-7">
                <label class="control-label" id="display_text">{{$subscription->display_text??"Monthly"}}</label>
                <input type="hidden" id="_display_text" value="{{$subscription->display_text??'Monthly'}}" name="display_text">
                @if(isset($subscription->subscription_id))
                <input type="hidden"  value="{{$subscription->subscription_id}}" name="subscription_id" >
                @endif
            </div>
        </div>
    </div>
</div>