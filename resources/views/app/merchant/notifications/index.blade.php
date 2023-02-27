@extends('app.master')
<style>
    .notification-item {
        background: #FFFFFF;
        border: 1px solid #E5E5E5;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
        border-radius: 4px;
        padding: 18px 20px;
        margin-bottom: 25px;
    }

    .notification-item-link {
        display: flex;
        justify-content: space-around;
        align-items: center;
        text-align: center;
    }

    .notification-item-label {
        font-weight: 400;
        font-size: 14px;
        color: #767676;
        text-transform: uppercase;
    }

    .notification-item-value {
        font-weight: 400;
        font-size: 18px;
        color: #495555;
    }

    .notification-item-link-icon {
        font-size: 27px !important;
    }

    .notification-pagination {
        display: block;
       text-align: center;
    }

    .load-more-btn {
        display: none;
        background-color: #ffffff;
        border: 1px solid #D9DEDE;
        color: #3E4AA3;
        padding: 10px 15px;
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
                <div class="loading" id="loader" style="display: none;">Loading&#8230;</div>
                <!-- BEGIN PAYMENT TRANSACTION TABLE -->
                <div id="notifications">
                    
                </div>
                <div class="notification-pagination">
                    <button type="button" class="load-more-btn">Load More</button>
                </div>

                <!-- END PAYMENT TRANSACTION TABLE -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
    <!-- END CONTENT -->
    <script>
        $(function() {
            let notificationWrapper = $("#notifications");
            let loadMoreBtn = $('.load-more-btn');
            let currentPage = 1;

            //Load Notifications
            loadNotifications();

            function loadNotifications() {
                document.getElementById('loader').style.display = 'block';
                $.ajax({
                    url: `/merchant/notifications/all?page=${currentPage}`,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        if(response.success) {
                            let data = response.data.notifications;

                            if(response.data.lastPage > currentPage) {
                                loadMoreBtn.show();
                                currentPage = response.data.currentPage + 1;
                            } else {
                                loadMoreBtn.hide();
                            }

                            data.forEach(notification => {
                                console.log(notification);
                                let html = '';
                                if(notification.type == "change-order") {
                                    html = changeOrderHTML(notification); 
                                }

                                if(notification.type == "invoice") {
                                    html = invoiceHTML(notification); 
                                }

                                notificationWrapper.append(html);
                            });
                            document.getElementById('loader').style.display = 'none';
                        }

                    }
                });
            }

            function invoiceHTML(notification) {
                let html = `<div class="notification-item"><a href=/merchant/invoice/viewg703/${notification.data['payment_request_id']}?notification_id=${notification.id} class="notification-item-link">
                            <div>
                                <p class="notification-item-value">${notification.invoice_number}</p>
                                <p class="notification-item-label">Invoice  Number</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.customer_name}</p>
                                <p class="notification-item-label">Client Name</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.currency_icon} ${notification.amount}</p>
                                <p class="notification-item-label">Amount</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.updated_date}</p>
                                <p class="notification-item-label">Updated Date</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.updated_by}</p>
                                <p class="notification-item-label">Updated by</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.type_status}</p>
                                <p class="notification-item-label">Action</p>
                            </div>
                            <div>
                                <i class="fa fa-angle-right notification-item-link-icon"></i>
                            </div>
                        </a></div>`;

                        return html;
            }

            function changeOrderHTML(notification) {
                let html = `<div class="notification-item"><a href=/merchant/invoice/viewg703/${notification.data['order_id']}?notification_id=${notification.id} class="notification-item-link">
                            <div>
                                <p class="notification-item-value">${notification.order_number}</p>
                                <p class="notification-item-label">Order  Number</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.customer_name}</p>
                                <p class="notification-item-label">Client Name</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.total_change_order_amount}</p>
                                <p class="notification-item-label">Change Order Amount</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.order_date}</p>
                                <p class="notification-item-label">Order Date</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.updated_by}</p>
                                <p class="notification-item-label">Updated by</p>
                            </div>
                            <div>
                                <p class="notification-item-value">${notification.type_status ? notification.type_status: ''}</p>
                                <p class="notification-item-label">Action</p>
                            </div>
                            <div>
                                <i class="fa fa-angle-right notification-item-link-icon"></i>
                            </div>
                        </a></div>`;

                        return html;
            }

            loadMoreBtn.on('click', function() {
                loadNotifications();
            })
        })
     </script>
@endsection