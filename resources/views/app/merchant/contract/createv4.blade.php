@extends('app.master')
<style>
    .lable-heading {
        font-style: normal;
        font-weight: 400;
        font-size: 14px;
        line-height: 24px;
        color: #767676;
        margin-bottom: 0px;
        margin-top: 5px;
    }

    .col-id-no {
        position: sticky !important;
        left: 0;
        z-index: 2;
        border-right: 2px solid #D9DEDE !important;
        background-color: #fff;
    }

    .steps {
        background-color: transparent !important;
        border: 2px #18AEBF solid !important;
        color: #18AEBF !important;
        width: auto !important;
    }
</style>
@section('content')
<div x-data="handler_create()">

    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render('home.contractcreate') }}
            <span class=" pull-right badge badge-pill status steps" style="padding: 6px 16px 6px 16px !important;">Step <span x-text="step"></span> of 3</span>
        </div>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                @include('layouts.alerts')

                <div id="perror" style="display: none;" class="alert alert-block alert-danger fade in">
                    <p>Error! Select a project before trying to add a new row
                    </p>
                </div>
                <div id="paticulars_error" style="display: none;" class="alert alert-block alert-danger fade in">
                    <p>Error! Before proceeding, please verify the details.. <br> 'Bill code', 'Bill Type', 'Original Contract Amount' are mandatory fields !
                    </p>
                </div>
                <form action="/merchant/contract/saveV4" id="frm_expense" onsubmit="loader();" method="post" enctype="multipart/form-data" class="form-horizontal form-row-sepe">
                    @csrf
                    <div x-show="step==1">
                        <livewire:contract.information :contract_id="$contract_id" />
                    </div>
                    <div x-show="step==2">
                        <livewire:contract.particulars :particular="$particulars" :project_id="$project_id" />
                    </div>
                    <div x-show="step==3">
                        <livewire:contract.preview />
                    </div>

                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <a href="/merchant/contract/list" class="btn green">Cancel</a>
                                        <a class="btn green" x-show="step!=1" @click="step=step-1">Back</a>
                                        <a class="btn blue" x-show="step==1" @click="goToParticulars()">Add particulars</a>
                                        <a class="btn blue" x-show="step==2" @click="goToPreview()">Preview contract</a>
                                        <a class="btn blue" x-show="step==3" @click="saveContract()">Save contract</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
    <script>
        mode = '{{$mode}}';

        function handler_create() {
            return {
                step: 1,
                contract_code: '{{$contract_code}}',
                // project_id : null,
                bill_date: '{{$bill_date}}',
                contract_date: '{{$contract_date}}',
                forms: {
                    no_contract_code: false,
                    no_contract_code_msg: null,
                    no_project_id: false,
                    no_project_id_msg: null,
                    no_contract_date: false,
                    no_contract_date_msg: null,
                    no_bill_date: false,
                    no_bill_date_msg: null,
                },
                goAhead: true,
                goToParticulars() {
                    this.resetFlags();
                    if (this.contract_code === null || this.contract_code === '') {
                        this.forms.no_contract_code = true;
                        this.forms.no_contract_code_msg = 'Please enter contract number';
                        this.goAhead = false
                    }
                    if (document.getElementById('project_id').value === '' || document.getElementById('project_id').value === null) {
                        this.forms.no_project_id = true;
                        this.forms.no_project_id_msg =  "Please select project";
                        this.goAhead = false
                    }
                    if (this.contract_date === null || this.contract_date === '') {
                        this.forms.no_contract_date = true;
                        this.forms.no_contract_date_msg = 'Please select contract date';
                        this.goAhead = false
                    }

                    if (this.bill_date === null || this.bill_date === '') {
                        this.forms.no_bill_date = true;
                        this.forms.no_bill_date_msg = 'Please select bill date';
                        this.goAhead = false
                    }
                    this.goToNextStep();
                    $('input[name="bill_code[]"]').each(function(int, arr) {
                        select2Dropdowns(int);
                    });
                },
                goToPreview() {
                    this.resetFlags();
                    // let contract_id = $('#contract_id').val();
                    var data = getContractParticularsData();

                    if (data.length == 0) {
                        $('#paticulars_error').show()
                        return false;
                    }

                    console.log(JSON.stringify(data));
                    /*$('.productselect').each(function (){
                         console.log($(this).select2('val'));
                     })*/
                    var actionUrl = '/merchant/contract/updatesaveV4';
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: {
                            _token: '{{ csrf_token() }}',
                            form_data: JSON.stringify(data),
                            link: $('#contract_id').val(),
                            totalcost: $('#particulartotal').val(),
                            project_id: $('#project_id').val(),
                            contract_code: $('#contract_number').val(),
                            billing_frequency: $('#billing_frequency').val(),
                            contract_date: $('#contract_date').val(),
                            bill_date: $('#billing_date').val()
                        },
                        success: function(data) {
                            console.log(111)
                        }
                    })
                    // if()
                    /*let go = true;
                    $('.productselect').each(function (){
                        if($(this).val() === '') {  $(this).css('border-color', 'red'); go = false; }
                    });
                    this.goAhead = go;*/
                    this.goToNextStep();
                    preview();
                },
                saveContract() {
                    $('#frm_expense').submit();
                },
                resetFlags() {
                    this.goAhead = true;
                    this.forms.no_contract_code = false;
                    this.forms.no_project_id = false;
                    this.forms.no_contract_date = false;
                    this.forms.no_bill_date = false;
                    $('#paticulars_error').hide()
                },
                goToNextStep() {
                    if (this.goAhead) this.step++;
                }

            }
        }
    </script>
</div>
</div>
@include('app.merchant.contract.add-bill-code-modal')
@endsection