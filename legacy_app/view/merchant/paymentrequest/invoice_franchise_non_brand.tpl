{if isset($signature.font_file)}
    <link href="{$signature.font_file}" rel="stylesheet">
{/if}
<div class="page-content">
    <div style="text-align: -webkit-center;text-align:-moz-center;">
        <h3 class="page-title">
            &nbsp;
        </h3>
        {if $invoice_success}
            <div class="alert alert-block alert-success fade in" style="max-width: 900px;text-align: left;">
                {if $notify_patron==1}
                    {if $invoice_type==1}
                        <h4 class="alert-heading">Payment request sent!</h4>
                        <p>
                            Your invoice has been sent to your customer. You will receive a notification as soon as your customer
                            makes the payment.
                        </p>
                    {else}
                        <h4 class="alert-heading">Estimate sent!</h4>
                        <p>
                            Your estimate has been sent to your customer. You will receive a notification as soon as your customer
                            makes the payment, along with the final invoice copy.
                        </p>
                    {/if}
                {else}
                    {if $invoice_type==1}
                        <h4 class="alert-heading">Invoice saved!</h4>
                        <p>
                            Your invoice has been saved and will appear in the Requests and Reports tabs.
                        </p>
                    {else}
                        <h4 class="alert-heading">Estimate saved!</h4>
                        <p>
                            Your estimate has been saved and will appear in the Requests and Reports tabs.
                        </p>
                    {/if}

                {/if}
                <p>
                    <a class="btn blue input-sm" href="/merchant/invoice/update/{$link}">
                        Update {if $invoice_type==1}invoice{else}estimate{/if} </a>
                    <a class="btn green input-sm" target="_BLANk" href="{$whatsapp_share}">
                        <i class="fa fa-whatsapp"></i> Share on Whatsapp</a>
                    <a class="bs_growl_show btn green input-sm" title="Copy Link" data-clipboard-action="copy"
                        data-clipboard-target="linkcopy"> <i class="fa fa-clipboard"></i> Copy invoice link</a>

                </p>
                <div style="font-size: 0px;">
                    <linkcopy>{$patron_url}</linkcopy>
                </div>
            </div>
        {/if}
        {if isset($error)}
            <div class="alert alert-danger" style="max-width: 900px;text-align: left;">
                <div class="media">
                    <p class="media-heading">{$error}</p>
                </div>

            </div>
        {/if}

        <!-- END PAGE HEADER-->
        <div class="invoice">
            <div class="row invoice-logo  no-margin" style="margin-bottom: 0px;">
                <div class="col-md-3" style="min-width: 150px;line">
                    <img src="https://h7sak8am43.swipez.in/uploads/images/logos/ulc-dUBDTDyOiY5SwasSfEnRtK6zPeMq1aU.jpg"
                        class="img-responsive templatelogo" alt="">
                </div>
                <div class="col-md-8">
                    <p style="text-align: left;">
                        <a href="http://swipez.prod/m/asa" style="font-size: 27px;" target="_BLANK">Chetty's Corner
                        </a> <br>
                        <span class="muted" style="width: 418px;line-height: 20px;font-size: 13px;">
                            The Metropole, 25 NIBM Road, 5th Floor Office No.03, Kondhwa | PUNE-411048 (MS) <br>
                        </span>
                        <span class="muted" style="line-height: 20px;font-size: 13px;">
                            Contact: 020-30030323<br>
                        </span>
                        <span class="muted" style="line-height: 20px;font-size: 13px;">
                            Email: pareshhpatil@gmail.com<br>
                        </span>
                    </p>
                </div>
            </div>
            <div class="row no-margin bg-grey">
                <div class="col-md-12" style="text-align: center;">
                    <h4><b>Tax Invoice</b></h4>
                </div>
            </div>
            <div class="row no-margin">
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                    <div class="" style="">
                        <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">

                            <tbody>
                                <tr>
                                    <td style="min-width: 120px;"><b>Customer code</b></td>
                                    <td>C000004</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 120px;"><b>Customer name</b></td>
                                    <td>Paresh Patil</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 120px;"><b>Email ID</b></td>
                                    <td>paresh.patil@opusnet.in</td>
                                </tr>
                                <tr>
                                    <td style="min-width: 120px;"><b>Mobile no</b></td>
                                    <td>9730946150</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-md-6 invoice-payment" style="padding-left: 0px;padding-right: 0px;">
                    <div class="">
                        <table class="table table-bordered table-condensed" style="margin: 0px 0 0px 0 !important;">
                            <tbody>
                                <tr>
                                    <td style="min-width: 120px;">
                                        <b>Bill date</b>
                                    </td>
                                    <td style="min-width: 120px;">
                                        14 Jan 2022 </td>

                                </tr>
                                <tr>
                                    <td style="min-width: 120px;">
                                        <b>Due date</b>
                                    </td>
                                    <td style="min-width: 120px;">
                                        14 Jan 2022 </td>

                                </tr>
                                <tr>
                                    <td style="min-width: 120px;">
                                        <b>Invoice No</b>
                                    </td>
                                    <td style="min-width: 120px;">
                                        INV1225 </td>

                                </tr>

                                <tr>
                                    <td style="min-width: 120px;">
                                        <b>Bill Period</b>
                                    </td>
                                    <td style="min-width: 120px;"> 07 Dec 2021 To 13 Jan 2022
                                    </td>
                                </tr>



                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 no-padding">
                    <div class="col-md-5">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td-c">
                                        <b>Daily Gross Sales</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Date
                                    </td>
                                    <td class="td-c">
                                        Chetty's
                                    </td>
                                    <td class="td-c">
                                        Non Chetty's
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        07-12-2021
                                    </td>
                                    <td class="td-c">
                                        15600.00
                                    </td>
                                    <td class="td-c">
                                        1800.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        08-01-2022
                                    </td>
                                    <td class="td-c">
                                        19000.00
                                    </td>
                                    <td class="td-c">
                                        1500.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        09-01-2022
                                    </td>
                                    <td class="td-c">
                                        18000.00
                                    </td>
                                    <td class="td-c">
                                        1000.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        10-01-2022
                                    </td>
                                    <td class="td-c">
                                        19800.00
                                    </td>
                                    <td class="td-c">
                                        2000.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        11-01-2022
                                    </td>
                                    <td class="td-c">
                                        20000.00
                                    </td>
                                    <td class="td-c">
                                        500.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        12-01-2022
                                    </td>
                                    <td class="td-c">
                                        22000.00
                                    </td>
                                    <td class="td-c">
                                        1000.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        13-01-2022
                                    </td>
                                    <td class="td-c">
                                        25000.00
                                    </td>
                                    <td class="td-c">
                                        1200.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="td-c">
                                        <b>Summary</b>
                                    </td>
                                    <td colspan="4" class="td-c">
                                        <b>Franchise Fees</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">

                                    </td>
                                    <td colspan="2" class="td-c">
                                        Chetty's

                                    </td>
                                    <td colspan="2" class="td-r">
                                        Non chetty's
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Billable Sales
                                    </td>
                                    <td colspan="2" class="td-r">
                                        139400.00
                                    </td>
                                    <td colspan="2" class="td-r">
                                        9000.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                    Less : CGST and SGST 5.00 %
                                    </td>
                                    <td colspan="2" class="td-r">
                                        6638.00
                                    </td>
                                    <td colspan="2" class="td-r">
                                        429.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Net Billable Sales
                                    </td>
                                    <td colspan="2" class="td-r">
                                        132762.00
                                    </td>
                                    <td colspan="2" class="td-r">
                                        8571.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Gross Franchisee Fee on Net Billable
                                    </td>
                                    <td class="td-r">
                                        10.00%
                                    </td>
                                    <td class="td-r">
                                        13276.19
                                    </td>
                                    <td class="td-r">
                                        1.00%
                                    </td>
                                    <td class="td-r">
                                        85.71
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Less: Waiver
                                    </td>
                                    <td class="td-r">
                                        4.00%
                                    </td>
                                    <td class="td-r">
                                        5310.48
                                    </td>
                                    <td class="td-r">
                                        0.00%
                                    </td>
                                    <td class="td-r">
                                        0.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Net Franchise Fee receivable
                                    </td>
                                    <td class="td-r">
                                        6.00%
                                    </td>
                                    <td class="td-r">
                                        7965.71
                                    </td>
                                    <td class="td-r">
                                        1.00%
                                    </td>
                                    <td class="td-r">
                                        85.71
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Penalty on outstanding amt
                                    </td>
                                    <td colspan="4" class="td-r">
                                        0.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Franchisee fees Payable
                                    </td>
                                    <td colspan="4" class="td-r">
                                        8051.42
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        CGST@9%
                                    </td>
                                    <td colspan="4" class="td-r">
                                        724.62
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        SGST@9%
                                    </td>
                                    <td colspan="4" class="td-r">
                                        724.62
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Total Amount (FEE)
                                    </td>
                                    <td colspan="4" class="td-r">
                                        9500.66
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Previous outstanding
                                    </td>
                                    <td colspan="4" class="td-r">
                                        0.00
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Total FF to be Paid with Previous outstanding
                                    </td>
                                    <td colspan="4" class="td-r">
                                        9500.66
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-c">
                                        Rounded off to
                                    </td>
                                    <td colspan="4" class="td-r">
                                        9501.00
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        Amount (in words) : Nine Thousand Five Hundred One Rupees
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





            <div class="row">
                <div class="col-md-12">
                    <div class="table-scrollable" style="border: 0;">
                        <table class="table ">
                            <tbody>
                                <tr>
                                    <td style="border-bottom: 0;border-top: 0;">
                                        <table cellpadding="2" class="table table-bordered"
                                            style="border: 1px solid #cbcbcb;color: #5b4d4b;font-size: 12px;width:100%;">
                                            <tbody>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        1</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;" colspan="2">Your
                                                        account will be directly debited on due date with abovesaid
                                                        amount <br>excluding TDS, if any</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        2</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;" colspan="2">Delayed
                                                        Payment will attract penalty @18% per annum</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        3</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;" colspan="2">Previous
                                                        Balance, if any, need to paid immediately to the following bank
                                                        <br>account:
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        <br>
                                                    </td>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        Bank Name</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;">HDFC Bank</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        <br>
                                                    </td>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        Branch</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;">Malad (East) Branch,
                                                        Mumbai</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        <br>
                                                    </td>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        Account No.</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;">8787320000365</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        <br>
                                                    </td>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        IFSC Code</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;">HDFC0000398</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border-bottom: 1px solid #cbcbcb;border-right: 1px solid #cbcbcb;">
                                                        4</td>
                                                    <td style="border-bottom: 1px solid #cbcbcb;" colspan="2">All
                                                        Amounts above are in "Rs".<br></td>
                                                </tr>
                                                <tr>
                                                    <td style="border-right: 1px solid #cbcbcb;width: 20px;">5</td>
                                                    <td colspan="2">All Disputes are subject to Mumbai Jurisdiction
                                                        only.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="border-bottom: 0;border-top: 0;">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        CIN No
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        BMKPP5491H
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-md-6 bl-0">
                                                        Income Tax PAN
                                                    </td>
                                                    <td class="col-md-6 br-0">
                                                        BMKPP5491H
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-md-6 bl-0"
                                                        style="border-bottom-width: 1 !important;">
                                                        GST No
                                                    </td>
                                                    <td class="col-md-6 br-0"
                                                        style="border-bottom-width: 1 !important;">
                                                        27BRSPP2039Q1ZU
                                                    </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>

                                </tr>

                            </tbody>
                        </table>






                        Note: This is a system generated invoice. No signature required.





                    </div>

                </div>
            </div>
        </div>




<!-- END PAGE CONTENT-->