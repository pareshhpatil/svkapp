@extends('app.master')
<style>
    .light {
        color: #818182;
        background-color: #fefefe;
        border-color: #fdfdfe;
    }

    .light .notification-link {
        color: #818182;
    }

    .dark {
        color: #1b1e21;
        background-color: #d6d8d9;
        border-color: #c6c8ca;
    }

    .dark .notification-link {
        color: #1b1e21;
    }

    .notification-list {
        padding: 20px 0;
    }

</style>
@section('content')
    <div class="page-content">
        <div class="page-bar">
            <span class="page-title" style="float: left;">{{$title}}</span>
            {{ Breadcrumbs::render() }}
        </div>
        <!-- BEGIN SEARCH CONTENT-->
        <div class="row">
            @include('layouts.alerts')
            <div class="col-md-12">
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div class="portlet">
                    <div class="notification-list">
                        @foreach($notifications as $notification)
                            @if(empty($notification->read_at))
                                <div class="alert dark" role="alert">
                                    <a href="{!! url('/merchant/invoice/viewg703/' . $notification->data['payment_request_id']).'?notification_id=' . $notification->id !!}" class="notification-link">
                                        <strong>{!! $notification->data['invoice_number'] !!}</strong> Pending for approval
                                    </a>
                                </div>
                            @else
                                <div class="alert dark" role="alert">
                                    <a href="{!! url('/merchant/invoice/viewg703/' . $notification->data['payment_request_id']).'?notification_id=' . $notification->id !!}" class="notification-link">
                                        <strong>{!! $notification->data['invoice_number'] !!}</strong> Pending for approval
                                    </a>
                                </div>
                            @endif

                        @endforeach
                    </div>

                </div>
                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->
@endsection