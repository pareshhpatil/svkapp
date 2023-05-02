<div class="modal  fade" id="new_covering" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new covering note</h4>
            </div>
            <form action="/merchant/coveringnote/save" method="post" id="covering_frm" class="form-horizontal form-row-sepe">
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="covering_error" class="alert alert-danger" style="display: none;">

                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Template name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required name="template_name" {$validate.name} class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mail body <span class="required">*
                                    </span></label>
                                <div class="col-md-7">
                                    <textarea required name="body" id="summernote" class="form-control tncrich">
                                                <div style="text-align: left;"><span style="font-family:open sans,helvetica neue,helvetica,arial,sans-serif">Dear Team,<br><br>
							  Please find attached invoice dated : %BILL_DATE% for %PROJECT_NAME%.<br><br>
                              This invoice for amount $%PAYABLE_AMOUNT% is due by %DUE_DATE% - %INVOICE_LINK%<br><br>
                              In case of queries related to this invoice, please reply back to this email.
                                <br><br>Thanking You<br><br>With best regards,<br>%COMPANY_NAME%</span></div>
                                    </textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Mail Subject <span class="required">*
                                    </span></label>
                                <div class="col-md-6">
                                    <input type="text" required maxlength="100" name="subject" class="form-control" value="%PROJECT_NAME% invoice">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Invoice label <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required maxlength="20" name="invoice_label" class="form-control" value="View Invoice">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Attach PDF <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="checkbox" checked="" name="pdf_enable" value="1" class="make-switch" data-on-text="&nbsp;Enabled&nbsp;&nbsp;" data-off-text="&nbsp;Disabled&nbsp;">
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="cclosebutton" class="btn default" data-dismiss="modal">Close</button>
                <input type="button" onclick="return saveCovering('');" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="new_document" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add new document</h4>
            </div>
            <form action="/merchant/coveringnote/save" method="post" class="form-horizontal form-row-sepe">
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="covering_error" class="alert alert-danger" style="display: none;">
                            </div>

                            <div id="mandatory_docs" class="alert alert-danger" style="display: none;">
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Document name <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required id="document_name" maxlength="100" class="form-control" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Document description <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <input type="text" required id="document_description" maxlength="250" class="form-control" value="">
                                    <!-- <input type="hidden" id="document_action" value="Non-mandatory"> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Required for action <span class="required">*
                                    </span></label>
                                <div class="col-md-4">
                                    <select class="form-control" id="document_action">
                                        <option value="Non-mandatory">Non-mandatory</option>
                                        <option value="Mandatory on creation">Mandatory on creation</option>
                                        <option value="Mandatory on submission">Mandatory on submission</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- End profile details -->



            </form>
            <div class="modal-footer">
                <button type="button" id="documentclose" class="btn default" data-dismiss="modal">Close</button>
                <input type="button" onclick="return saveDocument('');" value="Save" class="btn blue">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--custom-reminder date modal for add reminder plugin -->
<div class="modal fade" id="custom_date_reminder" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="width:fit-content;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Custom recurrence</h4>
            </div>
            <form action="/merchant/contract/custom_internal_reminder" method="post" id="custom_date_reminder_frm" class="form-horizontal form-row-sepe">
                <div class="form-body">
                    <!-- Start profile details -->
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <div id="custom_date_error" class="alert alert-danger" style="display: none;">

                            </div>
                            <div class="form-group">
                                <input name="rowId" id="custom_date_reminder_row" hidden />
                                <label class="control-label col-md-4">Repeat every <span class="required">*
                                    </span></label>
                                <div class="col-md-3 pt-7">
                                    <input type="number" required name="repeat_occurences" min="1" class="form-control" value="">
                                </div>
                                <div class="col-md-2 pt-7">
                                    <select class="form-control" name="repeat_type" onchange="checkRepeatType(this.value)">
                                        <option value="day">day</option>
                                        <option value="week" selected>week</option>
                                        <option value="month">month</option>
                                        <option value="year">year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="repeat_on_div">
                                <label class="control-label col-md-4">Repeat on <span class="required">*
                                    </span>
                                </label>
                                <div class="col-md-8 pt-7" id="repeat_by_week">
                                    <div class="" style="display:flex">
                                        <span>
                                            <div id="day1" class="custom_chk @if($current_day=='Sunday')active-day @endif" title="Sunday" val="S">S <input type="text" id="chk1" name="week_days_selected[]" value="" hidden /> </div>
                                        </span>
                                        <span>
                                            <div id="day2" class="custom_chk @if($current_day=='Monday')active-day @endif" title="Monday" val="M">M <input type="text" id="chk2" name="week_days_selected[]" value="" hidden /></div>
                                        </span>
                                        <span>
                                            <div id="day3" class="custom_chk @if($current_day=='Tuesday')active-day @endif" title="Tuesday" val="T">T <input type="text" id="chk3" name="week_days_selected[]" value="" hidden /></div>
                                        </span>
                                        <span>
                                            <div id="day4" class="custom_chk @if($current_day=='Wednesday')active-day @endif" title="Wednesday" val="W">W <input type="text" id="chk4" name="week_days_selected[]" value="" hidden /> </div>
                                        </span>
                                        <span>
                                            <div id="day5" class="custom_chk @if($current_day=='Thursday')active-day @endif" title="Thursday" val="T">T <input type="text" id="chk5" name="week_days_selected[]" value="" hidden /></div>
                                        </span>
                                        <span>
                                            <div id="day6" class="custom_chk @if($current_day=='Friday')active-day @endif" title="Friday" val="F">F <input type="text" id="chk6" name="week_days_selected[]" value="" hidden /> </div>
                                        </span>
                                        <span>
                                            <div id="day7" class="custom_chk @if($current_day=='Saturday')active-day @endif" title="Saturday" val="S">S <input type="text" id="chk7" name="week_days_selected[]" value="" hidden /></div>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-7" id="repeat_by_month" style="display:none;">
                                    <select class="form-control" name="repeat_on">
                                        <option value="monthly_on_day_{{$current_date}}">Montly on day {{$current_date}}</option>
                                        <option value="monthly_on_third_{{$current_day}}">Monthly on the third {{$current_day}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" id="closeInternalReminder" class="btn default" data-dismiss="modal">Cancel</button>
                <input type="button" onclick="return saveCustomInternalReminder();" value="Done" class="btn blue">
            </div>
        </div>
    </div>
</div>
<!--end of custom-reminder date modal for add reminder plugin -->

<script>
            function btnClick() {
                document.getElementById('frm_expense').onsubmit = function() {
                    document.getElementById('loader').style.display = 'none';
                };
            }
            function closeCustomizeInvoiceOutputDrawer() {
                document.getElementById("panelWrapInvoiceOutput").style.boxShadow = "none";
                document.getElementById("panelWrapInvoiceOutput").style.transform = "translateX(100%)";
            }

            $(document).ready(function() {
                $(document).on("click", ".customize-output-btn", function() {
                    let panelWrap = document.getElementById("panelWrapInvoiceOutput");
                    panelWrap.style.boxShadow = "0 0 0 9999px rgba(0,0,0,0.5)";
                    panelWrap.style.transform = "translateX(0%)";
                });
                $(document).on("click", ".custom_chk", function() {
                    const d = new Date();
                    let day = d.getDay();

                    var id=$(this).attr('id');
                    var valueOfId = $(this).attr('title');
                    var classExist = $('#'+id).hasClass('active-day');
                   

                    if(classExist==false) {
                        $('#'+id).addClass('active-day');
                    } else if(classExist==true) {
                        $('#'+id).removeClass('active-day');
                    }
                    var cnt = 0;
                    var lineItem = Array.from (document.querySelectorAll('.custom_chk'))
                   

                    lineItem.forEach((arrayElement, index) => {
                        //lineItem[index].className  += " otherclass";
                        chkId= index+1;
                        var classExist = $('#'+lineItem[index].id).hasClass('active-day');
                        if(classExist==true) {
                            //console.log(lineItem[index].title);
                            $("#chk"+chkId).val(lineItem[index].title);
                            cnt=cnt+1;
                        } else {
                            $("#chk"+chkId).val("");
                        }
                    });
                    
                    if(cnt==0) {
                        day=day+1;
                        $('#day'+day).addClass('active-day');
                    }
                });
                virtualSelectInit('0');
            });
            function virtualSelectInit(id='') {
                id=(id!='') ? id : 0;
                VirtualSelect.init({
                    ele: '#sub_user_drpdwn'+id,
                    options: sub_users,
                    dropboxWrapper: 'body',
                    multiple:true,
                    name: 'reminder_user[]',
                    additionalClasses : 'custom_valid',
                    required: true
                });

                $('.vscomp-toggle-button').not('.form-control, .input-sm').each(function() {
                    $(this).addClass('form-control input-sm mw-150');
                });
            }
            function AddInternalReminder(type='before') {
                var cntInput = document.getElementsByName("internal_reminder_subject[]");
                cntInputs=cntInput.length;
                
                try {
                    while (document.getElementById('sub_user_drpdwn' + cntInputs)) {
                        cntInputs = cntInputs + 1;
                    }
                } catch(o){}

                var mainDiv = document.getElementById('new_internal_reminder_'+type);
                var newDiv = document.createElement('tr');
                newDiv.innerHTML = '<td><div class="input-icon right"><div id="sub_user_drpdwn'+cntInputs+'"></div></div></td>'+
                                    '<td><div class="input-icon right"><input type="text" name="internal_reminder_subject[]"  maxlength="250" class="form-control input-sm" placeholder="Invoice creation reminder" required></div></td>'+
                                    '<td><div class="input-icon right"><select class="form-control input-sm" id="reminder_date_type" onchange="showReminderDateInput(this.value,'+cntInputs+')" name="reminder_date_type[]">'+
                                                                        '<option value="1">Last day of every month</option>'+
                                                                        '<option value="2">1st of every month</option>'+
                                                                        '<option value="3">Last weekday of every month</option>'+
                                                                        '<option value="4">30 days from last invoice bill date</option>'+
                                                                        '<option value="5">Custom</option></select></div><br>'+
                                        '<div class="input-icon right">'+
                                            '<div id="last_day_evry_month_div" style="display:none" class="reminder_date_div'+cntInputs+'">'+
                                                '<input type="text" id="last_day_evry_month_dt" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value="<x-localize :date="$current_month_last_date" type="date" />" data-date-today-highlight="true" autocomplete="off"/></div>'+
                                            '<div id="1st_day_evry_month_div'+cntInputs+'" style="display:none" class="reminder_date_div'+cntInputs+'">'+
                                                '<input type="text" id="1st_day_evry_month_dt'+cntInputs+'" name="reminder_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" value="<x-localize :date="$current_month_1st_date" type="date" />" data-date-today-highlight="true" autocomplete="off" disabled/></div>'+
                                            '<div id="week_day_div'+cntInputs+'" style="display:none" class="reminder_date_div'+cntInputs+'">'+
                                                '<select class="form-control input-sm" name="reminder_date[]" disabled>'+
                                                    '<option value="Monday">Monday</option>'+
                                                    '<option value="Tuesday">Tuesday</option>'+
                                                    '<option value="Wednesday">Wednesday</option>'+
                                                    '<option value="Thursday">Thursday</option>'+
                                                    '<option value="Friday">Friday</option>'+
                                                    '<option value="Saturday">Saturday</option>'+
                                                    '<option value="Sunday">Sunday</option></select></div>'+
                                            '<div id="30days_div'+cntInputs+'" style="display:none" class="reminder_date_div'+cntInputs+'">'+
                                                '<input type="number" name="reminder_date[]" min="1" max="31" class="form-control input-sm" value="30" disabled/></div>'+
                                            '<div id="custom_div'+cntInputs+'" class="reminder_date_div'+cntInputs+'" style="display:none"><span id="custom_summary'+cntInputs+'"></span><input name="reminder_date[]" value="" hidden/></div>'+
                                                '<input name="repeat_every[]" id="repeat_every_txt'+cntInputs+'" value="" hidden/>'+
                                                '<input name="repeat_type[]" id="repeat_type'+cntInputs+'" value="" hidden/>'+
                                                '<input name="repeat_on[]" id="repeat_on'+cntInputs+'" value="" hidden/>'+    
                                        '</div>'+
                                    '</td>'+
                                    '<td><div class="input-icon right"><select class="form-control input-sm" id="end_date_type" onchange="showEndDateInput(this.value,'+cntInputs+')" name="end_date_type[]"><option value="1">Number of occurrences</option><option value="2">End date</option></select><br>'+
                                        '<div class="input-icon right"><div id="occurences_div'+cntInputs+'" style="display:block;"><input type="number" name="end_date[]" min="1" max="100" class="form-control input-sm" placeholder="Enter occurrence"/></div>'+
                                        '<div id="end_date_div'+cntInputs+'" style="display:none;"><input type="text" name="end_date[]" class="form-control date-picker input-sm" data-date-format="{{ Session::get('default_date_format')}}" autocomplete="off" disabled/></div></div></div></td>'+
                                    '<td><a href="javascript:;" onClick="$(this).closest(' + "'tr'" + ').remove();tableHead(' + "'new_reminder_"+type+"'" + ');" class="btn btn-sm red"> <i class="fa fa-times"> </i> </a></td>';
                mainDiv.appendChild(newDiv);
                virtualSelectInit(cntInputs);
                $('.date-picker').datepicker();
            }      
        </script>