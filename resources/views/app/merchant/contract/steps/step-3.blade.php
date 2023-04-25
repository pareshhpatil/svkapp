
<style>
    .customize-output-panel-wrap {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        left: 50% !important;
        transform: translateX(100%);
        transition: .3s ease-out;
        z-index: 100;
    }

    .customize-output-panel {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        color: #394242;
        overflow-y: scroll;
        overflow-x: hidden;
        padding: 1em;
        box-shadow: 0 5px 15px rgb(0 0 0 / 50%);
        margin-bottom: 0;
    }
    .custom_chk {
        justify-content: center;
        box-sizing: border-box;
        width: 24px;
        height: 24px;
        font-size: 10px;
        font-weight: 500;
        border-radius: 50%;
        background-color: rgb(241,243,244);
        color: rgb(128,134,139);
        margin-right: 8px;
        cursor: pointer;
        align-items: center;
        -webkit-box-pack: center;
        -webkit-box-align: center;
        display: -webkit-inline-box;
    }
    .active-day {
        color: white !important;
        background-color: rgb(26,115,232);
    }
    @media screen and (min-width: 0px) and (max-width: 700px) {
        .mobile {
            display: block !important;
        }

        .desk {
            display: none !important;

        }
    }

    @media screen and (min-width: 701px) {
        .mobile {
            display: none !important;
        }

        .desk {
            display: block !important;

        }

    }

    @media (max-width: 767px) {
        .customize-output-panel-wrap {
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            position: fixed;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        .customize-output-panel-wrap {
            position: fixed;
            right: 0;
        }
    }

    .control-label {
        color: #394242 !important;
    }

    .popovers {
        color: #859494;
        vertical-align: text-bottom;
    }
</style>
<link href="/assets/global/plugins/summernote/summernote.min.css" rel="stylesheet">

<script src="/assets/admin/layout/scripts/coveringnote.js" type="text/javascript"></script>

<div>
    <div class="col-md-12">
        <div class="portlet light bordered">
            <h3 class="form-section">Settings</h3>
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#tab1" data-toggle="tab" class="step" aria-expanded="true">Properties</a></li>
                <li role="presentation"><a href="#tab2" data-toggle="tab" class="step" aria-expanded="true">Notifications</a></li>
                <li role="presentation"><a href="#tab3" data-toggle="tab" class="step" aria-expanded="true">Invoice format</a></li>
            </ul>
            <div class="portlet-body">
                @include('app.merchant.contract.steps.plugin-form')
            </div>

        </div>

        <div class="portlet light bordered">
            <div class="portlet-body form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a class="btn green" href="{{ route('contract.create.new', ['step' => 2, 'contract_id' => $contract_id]) }}">Back</a>
                            <button class="btn blue" type="submit" fdprocessedid="tinkj">Preview contract</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('app.merchant.contract.steps.plugin-modals');

        <script>
            
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
                                    '<td><div class="input-icon right"><input type="text" name="internal_reminder_subject[]"  maxlength="250" class="form-control input-sm" placeholder="Invoice creation reminder"></div></td>'+
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
    </div>
    @section('footer')
    <script src="/assets/global/plugins/summernote/summernote.min.js"></script>

    <script>
        $('.tncrich').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['undo', 'redo', 'codeview']]
            ],
            callbacks: {
                onKeydown: function(e) {
                    var t = e.currentTarget.innerText;
                    if (t.trim().length >= 5000) {
                        //delete keys, arrow keys, copy, cut
                        if (e.keyCode != 8 && !(e.keyCode >= 37 && e.keyCode <= 40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey))
                            e.preventDefault();
                    }
                },
                onKeyup: function(e) {
                    var t = e.currentTarget.innerText;
                    $('#maxContentPost').text(5000 - t.trim().length);
                },
                onPaste: function(e) {
                    var t = e.currentTarget.innerText;
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    var maxPaste = bufferText.length;
                    if (t.length + bufferText.length > 5000) {
                        maxPaste = 5000 - t.length;
                    }
                    if (maxPaste > 0) {
                        document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
                    }
                    $('#maxContentPost').text(5000 - t.length);
                }
            }
        });
    </script>
    @endsection