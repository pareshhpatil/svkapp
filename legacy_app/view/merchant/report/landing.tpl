<div class="page-content  ">
    <!-- BEGIN PAGE HEADER-->

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="portlet light" style="min-height: 500px;">
        <div class="row ">
            <div class="col-md-1"></div>

            <div class="col-md-10">
                <div class="col-md-4">
                    <h4 class="dc-h"><b>Collections </b></h4>
                    <p>
                        <a href="/merchant/report/collections" class="dc-link"> Collection details</a>
                    </p>
                    <p>
                        <a href="/merchant/report/paymentsreceived" class="dc-link">
                            {if $service_id==4}
                                Event
                            {elseif $service_id==5}
                                Booking
                            {else}
                                Invoice
                            {/if}
                            payments</a>
                    </p>

                    <p>
                        <a href="/merchant/report/websitepaymentsreceived" class="dc-link"> Website payments</a>
                    </p>
                    <p>
                        <a href="/merchant/report/websitepaymentsreceived/plan" class="dc-link"> Plan payments</a>
                    </p>
                    <p>
                        <a href="/merchant/report/websitepaymentsreceived/directpay" class="dc-link"> Payment link
                            transactions</a>
                    </p>
                    <p>
                        <a href="/merchant/report/websitepaymentsreceived/form" class="dc-link"> Form builder
                            transactions</a>
                    </p>
                    <p>
                        <a href="/merchant/report/ledger" class="dc-link"> Ledger report</a>
                    </p>
                    <p>
                        <a href="/merchant/report/payment_tdr" class="dc-link"> TDR charges</a>
                    </p>

                </div>
                {if $service_id==2 || $service_id==6}
                    <div class="col-md-4">
                        <h4 class="dc-h"><b>Invoicing</b></h4>
                        <p>
                            <a href="/merchant/report/agingdetails" class="dc-link"> Unpaid invoices</a>
                        </p>
                        <p>
                            <a href="/merchant/report/invoicedetails" class="dc-link"> Invoice details</a>
                        </p>
                        <p>
                            <a href="/merchant/report/invoicedetails/estimate" class="dc-link"> Estimate details</a>
                        </p>
                        <p>
                            <a href="/merchant/report/agingsummary" class="dc-link"> Aging summary</a>
                        </p>
                        <p>
                            <a href="/merchant/report/vendorcommission" class="dc-link"> Vendor commission</a>
                        </p>
                        <p>
                            <a href="/merchant/report/paymentexpected" class="dc-link"> Payments expected</a>
                        </p>
                        <p>
                            <a href="/merchant/report/taxsummary" class="dc-link"> Tax summary</a>
                        </p>
                        <p>
                            <a href="/merchant/report/taxdetails" class="dc-link"> Tax details</a>
                        </p>
                        <p>
                            <a href="" class="dc-link"> </a>
                        </p>

                    </div>
                {/if}
                {if $service_id==12}
                    <div class="col-md-4">
                        <h4 class="dc-h"><b>Expense</b></h4>
                        <p>
                            <a href="/merchant/report/expense" class="dc-link"> Expense details</a>
                        </p>
                        <p>
                            <a href="/merchant/report/expense/po" class="dc-link"> PO details</a>
                        </p>
                    </div>
                {/if}

                <div class="col-md-4">
                    <h4 class="dc-h"><b>Settlements </b></h4>
                    <p>
                        <a href="/merchant/report/payment_settlement_summary" class="dc-link"> Settlement summary</a>
                    </p>
                    <p>
                        <a href="/merchant/report/payment_settlement_details" class="dc-link"> Settlement details</a>
                    </p>
                    <p>
                        <a href="/merchant/report/refunddetails" class="dc-link"> Refund details</a>
                    </p>
                    <p>
                        <a href="/merchant/report/disputedetails" class="dc-link"> Dispute details</a>
                    </p>

                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-1"></div>

            <div class="col-md-10">
                {if $service_id==6}
                    <div class="col-md-4">
                        <h4 class="dc-h"><b>GST</b></h4>
                        <p>
                            <a href="/merchant/gst/reportb2b" class="dc-link"> B2B</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/reportb2clarge" class="dc-link"> B2C large</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/reportb2csmall" class="dc-link"> B2C small</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/reporthsnsummary" class="dc-link"> HSN summary</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/reportstatesummary" class="dc-link"> Total summary</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/reportinvoicedocument" class="dc-link"> Invoice document</a>
                        </p>
                        <p>
                            <a href="/merchant/gst/filingstatus" class="dc-link"> Filing status</a>
                        </p>
                    </div>
                {/if}

                <div class="col-md-4">
                    <h4 class="dc-h"><b>Form builder</b></h4>
                    <p>
                        <a href="/merchant/report/formbuilder" class="dc-link"> Form builder data</a>
                    </p>
                </div>

                <div class="col-md-4">
                    <h4 class="dc-h"><b>Inventory</b></h4>
                    <p>
                        <a href="/merchant/report/productsalesreport" class="dc-link"> Product wise sales</a>
                    </p>
                </div>

            </div>
        </div>


    </div>
</div>
</div>
<!-- END PAGE CONTENT-->
</div>
</div>
<!-- END CONTENT -->
</div>