@extends('app.master')
@section('header')
    <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0
        }

        .ui-icon-arrowthick-2-n-s {
            background-position: -128px -48px
        }

        .ui-icon {
            display: inline-block;
            vertical-align: middle;
            margin-top: -.25em;
            position: relative;
            text-indent: -99999px;
            overflow: hidden;
            background-repeat: no-repeat
        }

        .ui-widget-icon-block {
            left: 50%;
            margin-left: -8px;
            display: block
        }

        .select2-container--bootstrap .select2-selection {
            border-radius: 1px
        }

        .select2-container .select2-selection--single {
            display: contents
        }
    </style>
    <link href="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{ $title }}</span>
            @if ($title == 'Create format')
                {{ Breadcrumbs::render('create.invoiceformat') }}
            @else
                {{ Breadcrumbs::render('update.invoiceformat') }}
            @endif
            <a href="/merchant/template/viewlist" class="btn green pull-right"> Invoice formats </a>
        </div>

        <!-- END PAGE HEADER-->
        <div class="row">
            <div class="col-md-12">

                @include('layouts.alerts')
                <div class="">


                    <div class="portlet-body">
                        <form action="/merchant/invoiceformat/save" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="design_name" value="@isset($designname)
                            {{$designname}} 
                            @endisset">
                            <input type="hidden" name="design_color" value="@isset($colorname){{ $colorname }}  @endisset">
                            <div class="form-body">
                                <!-- image upload -->

                                <div class="portlet  col-md-12">
                                    <div class="portlet-body">
                                        <div class="row">

                                            @livewire('format.billing-profile', ['profileId' => $defaultProfileId, 'templateName' => $detail->template_name])
                                           
                                                <div class="col-md-4 ">
                                                    @if (!empty($designname))
                                                    <div class="col-md-6">

                                                        <label class="control-label">Template Type </label>

                                                        <h5 style="color: #1f2020; text-transform: capitalize;">
                                                            {{ $typename }}<a
                                                                href="{{ url('/merchant/invoiceformat/choose-design/' . $from . '/' . $link) }}"
                                                                style="margin-left: 5px;"><i class="fa fa-edit"> </i></a>
                                                        </h5>
                                                    </div>
                                                    @endif
                                                    @if (!empty($colorname))
                                                    <div class="col-md-6 ">
                                                        <label class="control-label">Template Color </label>
                                                        <h5>
                                                            <i class="fa fa-circle"
                                                                style="height: 20px;
                                                width: 20px;
                                                background: {{ $colorname }};
                                                color: {{ $colorname }};
                                                border-radius: 20px;">
                                                            </i>
                                                           @php
                                                               $dsnm='travel';
                                                               if(!empty($designname))
                                                               {
                                                                $dsnm=$designname;
                                                               }
                                                           @endphp

                                                            <a
                                                                href="{{ url('/merchant/invoiceformat/choose-color/' . $from . '/' . $dsnm . '/' . str_replace("#", "",$colorname) . '/' . $link) }}"><i
                                                                    class="fa fa-edit"> </i></a>
                                                        </h5>
                                                    </div>
                                                    @endif



                                                </div>
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet  col-md-12">
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-6 fileinput fileinput-new" data-provides="fileinput">
                                                <h4 class="form-section mt-0">Upload company logo</h4>
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 130px;">
                                                    @if (isset($detail->image_path) && $detail->image_path != '')
                                                        <img src="/uploads/images/logos/{{ $detail->image_path }}"
                                                            class="img-responsive templatelogo" alt="" />
                                                        <input type="hidden" name="logo"
                                                            value="{{ $detail->image_path }}">
                                                    @else
                                                        <img src="/assets/admin/layout/img/nologo.gif"
                                                            class="img-responsive templatelogo" alt="" />
                                                    @endif
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                    style="max-width: 200px; max-height: 150px;">
                                                </div>
                                                <div>
                                                    <span class="btn btn-sm default btn-file">
                                                        <span class="fileinput-new">
                                                            Select logo </span>
                                                        <span class="fileinput-exists">
                                                            Change </span>
                                                        <input onchange="return validatefilesize(500000, 'imgupload');"
                                                            id="imgupload" type="file" accept="image/*"
                                                            name="uploaded_file">
                                                    </span>
                                                    <a href="javascript:;" id="imgdismiss"
                                                        class="btn-sm btn default fileinput-exists"
                                                        data-dismiss="fileinput">
                                                        Remove </a>
                                                </div>
                                            </div>
{{--                                            @livewire('format.header-detail', ['columns' => $formatColumns['M'], 'profileId' => $defaultProfileId, 'metaColumns' => $metadata['M']])--}}
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet  col-md-12">

                                    <div class="portlet-body">
                                        <div class="row">
                                            @isset($metadata['C'])
                                                @livewire('format.customer-detail', ['columns' => $metadata['C']])
                                            @else
                                                @livewire('format.customer-detail')
                                                @endif
                                                <div class="col-md-1"></div>
                                                @livewire('format.invoice-detail', ['columns' => $metadata['H'], 'details' => $detail])
                                            </div>
                                            @if ($detail->template_type == 'travel')
                                        </div>
                                    </div>
                                    <div class="portlet  col-md-12">
                                        <div class="portlet-body">
                                            <h4 class="form-section mt-0">
                                                Vehicle detail section
                                                <div class="pull-right">
                                                    <input type="checkbox" id="issec0" name="sec_vehicle_det"
                                                        @if ($vd_sec == true) checked @endif data-size="small"
                                                        onchange="showDebit('sec0');" value="1" class="make-switch "
                                                        data-on-text="&nbsp;ON&nbsp;&nbsp;" data-off-text="&nbsp;OFF&nbsp;">
                                                </div>
                                            </h4>
                                            <div id="sec0div" @if ($vd_sec == false) style="display: none;" @endif>
                                                <div class="row">
                                                    <div class="form-section mt-0 col-md-4">
                                                        <input type="text" class="form-control" name="sec_vehicle_det_name"
                                                            value="{{ $vd_title }}">
                                                    </div>
                                                </div>
                                                @livewire('format.booking-detail', ['columns' => $metadata['BDS'] ?? []])
                                            </div>
                                        </div>
                                    </div>
                                    @livewire('format.travel-detail', ['columns' => $detail->particular_column, 'particularColumns' => $formatColumns, 'defaultParticular' => $detail->default_particular, 'properties' => $detail->properties ?? ''])
                                @else
                                    @if ($detail->template_type != 'scan')
                                        @livewire('format.particular-detail', ['columns' => $detail->particular_column, 'particularColumns' => $formatColumns['P'], 'defaultParticular' => $detail->default_particular,'template_type'=>$detail->template_type])
                                    @else
                                </div>
                                @endif
                                @endif
                                @if ($detail->template_type != 'scan')
                                    @livewire('format.tax-detail', ['defaultTax' => $detail->default_tax])
                                @endif
                                <div class="portlet  col-md-12">
                                    <div class="portlet-body">
                                        <h4 class="form-section mt-0">Terms & conditions
                                        </h4>
                                        <table class="table mb-0">
                                            <tbody id="new_tnc">
                                                <tr>
                                                    <td>
                                                        <div class="input-icon right">
                                                            <textarea type="text" maxlength="5000" name="tnc" class="form-control input-sm tncrich"
                                                                placeholder="Add label">
@isset($detail->tnc) {!! $detail->tnc !!}  @endif
</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @livewire('format.plugin-detail', ['plugin' => $detail->plugin, 'columns' => $metadata['H'], 'selectedTemplateType' => $detail->template_type])

                                @if (!empty($designname))
                                    <div class="portlet  col-md-12">
                                        <div class="portlet-body">
                                            <h4 class="form-section mt-0">Footer note
                                            </h4>
                                            <textarea class="form-control" name="template_fooer_msg" rows="3" maxlength="250" id="template_fooer_msg"
                                                placeholder="Type your footer note content here" spellcheck="true">{{ $footernote }}</textarea>

                                        </div>
                                    </div>
                                @endif
                                <div class="portlet  col-md-12">
                                    <div class="portlet-body">
                                        <h3 class="form-section">Final summary
                                        </h3>
                                        <div class="row">
                                            <div class="col-md-3 w-auto">
                                                <div class="form-group">
                                                    <p>Fee value with taxes</p>
                                                    <input type="text" readonly class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <p>Grand total</p>
                                                    <input type="text" readonly class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <p>Narrative</p>
                                                    <input type="text" readonly class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" id="template_type" name="template_type"
                                                    value="{{ $detail->template_type }}" />
                                                <input type="hidden" name="template_id" value="{{ $template_id }}" />
                                                <input type="hidden" name="custmized_receipt_fields"
                                                    value="@if ($detail->custmized_receipt_fields != '') {{ json_encode($detail->custmized_receipt_fields, 1) }} @endif"
                                                    id="is_custmized_receipt_field" />
                                                <p>&nbsp;</p>
                                                <a href="/merchant/template/newtemplate" class="btn btn-link">Cancel</a>
                                                <input type="submit" id="btnsubmit" value="Save" class="btn blue">

                                            </div>
                                            <!--/span-->
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        </div>
        </div>
        </div>


        @livewire('format.modal-detail', ['columns' => $formatColumns['M']])

        @yield('modal_footer')
        @include('app.merchant.invoiceformat.customize-receipt-fields-panel')
    @endsection

    @section('footer')
        <script src="/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script>
            $(function() {
                $(".sortable").sortable();
                $(".sortable").disableSelection();
            });
            @if (isset($available_fields))
                exist_available_fields = '{!! $available_fields !!}';
            @endif
            @if (isset($drawCustomerRows))
                drawDefaultCustomerRows = '{!! $drawCustomerRows !!}';
            @endif
            @if (isset($drawBillingRows))
                drawDefaultBillingRows = '{!! $drawBillingRows !!}';
            @endif
        </script>
    @endsection
