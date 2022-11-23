<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\Invoice;
use Spatie\ArrayToXml\ArrayToXml;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TallyExportReady;
use Notification;

class TallyExport implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $merchant_id;
    protected $gst_number;
    protected $from_date;
    protected $to_date;
    protected $type;
    protected $request_id;
    protected $invoice_model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $gst_number, $from_date, $to_date, $type, $request_id) {
        $this->merchant_id = $merchant_id;
        $this->gst_number = $gst_number;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->type = $type;
        $this->request_id = $request_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $this->invoice_model = new Invoice();
        $states = $this->invoice_model->getGstState();
        foreach ($states as $s) {
            $key = ($s->config_key > 9) ? $s->config_key : '0' . $s->config_key;
            $statearray[$key] = $s->config_value;
        }
        $data = array();
        $all = array();
        $rows = $this->invoice_model->getIrisInvoiceDetail($this->merchant_id, $this->gst_number, $this->from_date, $this->to_date, $this->type);
        if ($this->gst_number == '') {
            $swipez_rows = $this->invoice_model->getSwipezInvoice($this->merchant_id, $this->from_date, $this->to_date);
        }
        if ($rows->isNotEmpty()) {
            $gstArray = $this->getGstArray($rows, $this->type, $statearray);
            foreach ($gstArray as $kkk => $aa) {
                $ar = array();
                $ar['_attributes'] = ['xmlns:UDF' => 'TallyUDF'];
                $ar['VOUCHER'] = $aa;
                $all[] = $ar;
            }
        }
        if ($this->gst_number == '') {
            if ($swipez_rows->isNotEmpty()) {
                $gstArray = $this->getSwipezArray($swipez_rows);
                foreach ($gstArray as $kkk => $aa) {
                    $ar = array();
                    $ar['_attributes'] = ['xmlns:UDF' => 'TallyUDF'];
                    $ar['VOUCHER'] = $aa;
                    $all[] = $ar;
                }
            }
        }

        if (!empty($all)) {
            $data['ENVELOPE']['HEADER']['TALLYREQUEST'] = 'Import Data';
            $data['ENVELOPE']['BODY']['IMPORTDATA']['REQUESTDESC']['REPORTNAME'] = 'Vouchers';
            $data['ENVELOPE']['BODY']['IMPORTDATA']['REQUESTDATA']['TALLYMESSAGE'] = $all;

            $arrayToXml = new ArrayToXml($data);
            $result = $arrayToXml->prettify()->toXml();
            $uuid = Encrypt::encode($this->request_id);
            if ($this->type == 'C') {
                $file_name = $uuid . '_creditnotevoucher.xml';
            } elseif ($this->type == 'RI') {
                $file_name = $uuid . '_salesvoucher.xml';
            } else {
                $file_name = $uuid . '_debitnotevoucher.xml';
            }
            $this->invoice_model->updateTallyExportRequest($this->request_id, 1, $file_name);
            Storage::put('/download/tallyexport/' . $file_name, $result);
            $url = '/merchant/tallyexport/download/' . $uuid;
            $email = $this->invoice_model->getColumnValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 'business_email');
            Notification::route('mail', $email)->notify(new TallyExportReady($url));
        } else {
            $this->invoice_model->updateTallyExportRequest($this->request_id, 2, '');
        }
    }

    public function failed(\Exception $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }
    }

    function getSwipezArray($rows) {
        $last_invoice_id = 0;
        $array = array();
        $key = 0;
        $output_gst = array();
        $merchant_state = $this->invoice_model->getColumnValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 'state');
        foreach ($rows as $key => $row) {
            $data = array();
            if ($merchant_state == $row->state || $row->state == '') {
                $state_type = 'INTRA';
            } else {
                $state_type = 'INTER';
            }

            $date = date('Ymd', strtotime($row->bill_date));
            $vnum = $row->payment_request_id;
            $buyer_name = $row->first_name . ' ' . $row->last_name;
            $export_type = "OnlineSales";
            $data['_attributes'] = ['VCHTYPE' => $export_type, 'ACTION' => 'Create'];
            $data['DATE'] = $date;
            $data['PARTYNAME'] = 'Swipez';
            $data['VOUCHERTYPENAME'] = $export_type;
            $data['VOUCHERNUMBER'] = $vnum;
            $data['REFERENCE'] = $vnum;
            $data['PARTYLEDGERNAME'] = $buyer_name;
            $data['BASICBASEPARTYNAME'] = $buyer_name;
            if ($row->payment_request_status == 1 || $row->payment_request_status == 2) {
                $data['BASICDUEDATEOFPYMT'] = 'Prepaid';
            } else {
                $data['BASICDUEDATEOFPYMT'] = 'Postpaid';
            }
            $data['STATENAME'] = $row->state;
            $data['PLACEOFSUPPLY'] = $row->state;
            $data['BASICSHIPDOCUMENTNO'] = $row->invoice_number;
            $data['ISINVOICE'] = "Yes";
            if ($row->gst_number == '') {
                $data['GSTREGISTRATIONTYPE'] = 'Consumer';
                $buyer_name = 'Retail Buyer';
            } else {
                $data['GSTREGISTRATIONTYPE'] = 'Regular';
                $data['PARTYGSTIN'] = $row->gst_number;
            }
            $data['INVOICEORDERLIST.LIST']['BASICORDERDATE'] = $date;
            $data['INVOICEORDERLIST.LIST']['BASICPURCHASEORDERNO'] = $row->invoice_number;

            $detail['LEDGERNAME'] = $buyer_name;
            $detail['ISDEEMEDPOSITIVE'] = "Yes";
            $detail['ISPARTYLEDGER'] = "Yes";
            $detail['AMOUNT'] = '-' . $row->invoice_total;
            $detail['VATEXPAMOUNT'] = '-' . $row->invoice_total;
            $detail['BILLALLOCATIONS.LIST']['NAME'] = $data['VOUCHERNUMBER'];
            $detail['BILLALLOCATIONS.LIST']['BILLTYPE'] = "New Ref";
            $detail['BILLALLOCATIONS.LIST']['AMOUNT'] = '-' . $row->invoice_total;

            $data['LEDGERENTRIES.LIST'][] = $detail;
            $data['NARRATION'] = "Invoice to " . $buyer_name . " for order " . $row->invoice_number;
            $data['BASICBUYERADDRESS.LIST']['_attributes'] = ['TYPE' => 'String'];
            $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = $row->address;
            $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = "India";
            $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = "Ph:" . $row->mobile;


            $particulars = $this->invoice_model->getInvoiceParticular($row->payment_request_id);
            foreach ($particulars as $pr) {
                $gst_text = ($pr->gst > 0) ? "  @ " . $pr->gst . "%" : '';
                $particular['LEDGERNAME'] = $pr->item . $gst_text;
                $particular['ISDEEMEDPOSITIVE'] = "No";
                $particular['AMOUNT'] = $pr->total_amount;
                $particular['VATEXPAMOUNT'] = $pr->total_amount;

                if ($pr->gst > 0) {
                    $particular['GSTOVRDNASSESSABLEVALUE'] = $pr->total_amount;
                    $particular['GSTOVRDNNATURE'] = "Sales";
                    $particular['GSTRATEVALUATIONTYPE'] = "Based on Value";
                    if ($state_type == 'INTER') {
                        $particular['RATEDETAILS.LIST']['GSTRATEDUTYHEAD'] = "Integrated Tax";
                        $particular['RATEDETAILS.LIST']['GSTRATEVALUATIONTYPE'] = "Based on Value";
                        $particular['RATEDETAILS.LIST']['GSTRATE'] = $pr->gst;
                    } else {
                        $intragst['GSTRATEDUTYHEAD'] = "Central Tax";
                        $intragst['GSTRATEVALUATIONTYPE'] = "Based on Value";
                        $intragst['GSTRATE'] = $pr->gst / 2;
                        $particular['RATEDETAILS.LIST'][] = $intragst;
                        $intragst['GSTRATEDUTYHEAD'] = "State Tax";
                        $particular['RATEDETAILS.LIST'][] = $intragst;
                    }
                }
                $data['LEDGERENTRIES.LIST'][] = $particular;
            }

            $array[$key] = $data;


            $taxes = $this->invoice_model->getInvoiceTax($row->payment_request_id);
            foreach ($taxes as $tx) {
                $outputgst = array();
                $outputgst['LEDGERNAME'] = $tx->tax_name;
                $outputgst['ISDEEMEDPOSITIVE'] = "No";
                $outputgst['AMOUNT'] = $tx->tax_amount;
                $outputgst['VATEXPAMOUNT'] = $tx->tax_amount;
                $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
            }
        }

        return $array;
    }

    function getGstArray($rows, $type, $statearray) {
        $last_invoice_id = 0;
        $array = array();
        $particular_prod = array();
        $key = 0;
        $output_gst = array();
        if ($type == 'RI') {
            $export_type = "OnlineSales";
            $minus = "-";
            $plus = "";
        } else if ($type == 'C') {
            $export_type = "OnlineCreditNote";
            $minus = "";
            $plus = "-";
        } else if ($type == 'D') {
            $export_type = "OnlineDebitNote";
            $minus = "-";
            $plus = "";
        }
        $count = count($rows) - 1;
        foreach ($rows as $kk => $row) {
            if ($row->source == 'Amazon') {
                $row->source = 'Amazon.in';
            }
            if ($last_invoice_id == 0 || $last_invoice_id != $row->invoice_id) {
                if ($last_invoice_id != 0) {
                    $array[$key]['LEDGERENTRIES.LIST'][] = $particular_prod;
                    if ($output_gst['type'] == 'IGST') {
                        $outputgst['LEDGERNAME'] = "Output IGST @ " . round($output_gst['rate'], 2) . "%";
                        $outputgst['ISDEEMEDPOSITIVE'] = "No";
                        $outputgst['AMOUNT'] = $plus . $output_gst['amount'];
                        $outputgst['VATEXPAMOUNT'] = $plus . $output_gst['amount'];
                        $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
                    } else {
                        $outputgst['LEDGERNAME'] = "Output CGST @ " . round($output_gst['rate'], 2) . "%";
                        $outputgst['ISDEEMEDPOSITIVE'] = "No";
                        $outputgst['AMOUNT'] = $plus . round($output_gst['amount'] / 2, 2);
                        $outputgst['VATEXPAMOUNT'] = $plus . round($output_gst['amount'] / 2, 2);
                        $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
                        $outputgst['LEDGERNAME'] = "Output SGST @ " . round($output_gst['rate'], 2) . "%";
                        $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
                    }
                    $output_gst = array();
                    $key++;
                    $data = array();
                    $particular = array();
                    $particular_prod = array();
                }
                $order_date = date('Ymd', strtotime($row->order_date));
                if ($type == 'RI') {
                    $date = date('Ymd', strtotime($row->idt));
                    $vnum = $row->inum;
                } else {
                    $date = date('Ymd', strtotime($row->ntDt));
                    $vnum = $row->ntNum;
                    $inv_date = date('Ymd', strtotime($row->idt));
                }
                $last_invoice_id = $row->invoice_id;
                $data['_attributes'] = ['VCHTYPE' => $export_type, 'ACTION' => 'Create'];
                $data['DATE'] = $date;
                $data['PARTYNAME'] = $row->source;
                $data['VOUCHERTYPENAME'] = $export_type;
                $data['VOUCHERNUMBER'] = $vnum;

                if ($type == 'RI') {
                    $data['REFERENCE'] = $row->order_id;
                } else {
                    $data['REFERENCE'] = $row->inum;
                    $data['REFERENCEDATE'] = $inv_date;
                    if ($type == 'C') {
                        $data['GSTNATUREOFRETURN'] = "01-Sales Return";
                        if ($row->val < 250001) {
                            $data['URDORIGINALSALEVALUE'] = "Lesser than or equal to 2.5 lakhs";
                        } else {
                            $data['URDORIGINALSALEVALUE'] = "Greater than 2.5 lakhs";
                        }
                    }
                    $data['INVOICEDELNOTES.LIST']['BASICSHIPPINGDATE'] = $order_date;
                }

                $data['PARTYLEDGERNAME'] = $row->source;
                $data['BASICBASEPARTYNAME'] = $row->source;
                $data['BASICSHIPPEDBY'] = '';
                $data['BASICBUYERNAME'] = '';
                if ($row->payment_mode == 'Postpaid' || $row->payment_mode == 'COD') {
                    $data['BASICDUEDATEOFPYMT'] = 'Postpaid';
                } else {
                    $data['BASICDUEDATEOFPYMT'] = 'Prepaid';
                }
                $data['STATENAME'] = $statearray[$row->pos];
                $data['PLACEOFSUPPLY'] = $statearray[$row->pos];
                $data['BASICSHIPDOCUMENTNO'] = $row->order_id;
                $data['ISINVOICE'] = "Yes";
                if ($row->invTyp == 'B2CS') {
                    $data['GSTREGISTRATIONTYPE'] = 'Consumer';
                    $buyer_name = 'Retail Buyer';
                } else {
                    $data['GSTREGISTRATIONTYPE'] = 'Regular';
                    $data['PARTYGSTIN'] = $row->ctin;
                    $buyer_name = $row->cname;
                }
                $data['INVOICEORDERLIST.LIST']['BASICORDERDATE'] = $order_date;
                $data['INVOICEORDERLIST.LIST']['BASICPURCHASEORDERNO'] = $row->order_id;

                $detail['LEDGERNAME'] = $row->source;
                $detail['ISDEEMEDPOSITIVE'] = "Yes";
                $detail['ISPARTYLEDGER'] = "Yes";
                $detail['AMOUNT'] = $minus . $row->val;
                $detail['VATEXPAMOUNT'] = $minus . $row->val;
                $detail['BILLALLOCATIONS.LIST']['NAME'] = $data['VOUCHERNUMBER'];
                $detail['BILLALLOCATIONS.LIST']['BILLTYPE'] = "New Ref";
                $detail['BILLALLOCATIONS.LIST']['AMOUNT'] = $minus . $row->val;

                $data['LEDGERENTRIES.LIST'][] = $detail;
                $data['ALLINVENTORYENTRIES.LIST'] = array();
                $data['NARRATION'] = "Goods sold to " . $buyer_name . " for order " . $row->order_id . ' Memo: Item: ' . $row->desc . " Qty: " . $row->qty;
                $data['BASICBUYERADDRESS.LIST']['_attributes'] = ['TYPE' => 'String'];
                $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = '';
                $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = '';
                $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = $row->address;
                $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = "India";
                $data['BASICBUYERADDRESS.LIST']['BASICBUYERADDRESS'][] = "Ph:";
                $supType = ($row->splyTy == 'INTER') ? 'Interstate' : 'Local';
                $gst = $row->irt;
                $output_gst['type'] = 'IGST';
                $gst_amount = $row->iamt;
                if ($gst == 0) {
                    $gst = $row->crt + $row->srt;
                    $output_gst['type'] = 'CGST';
                    $gst_amount = round($row->camt * 2, 2);
                }
                $output_gst['rate'] = $gst;
                if (isset($output_gst['amount'])) {
                    $output_gst['amount'] = $output_gst['amount'] + $gst_amount;
                } else {
                    $output_gst['amount'] = $gst_amount;
                }
                $particular_prod['LEDGERNAME'] = $supType . " Sales @ " . round($gst, 2) . "%";
                $particular_prod['ISDEEMEDPOSITIVE'] = "No";
                $particular_prod['AMOUNT'] = $plus . $row->txval;
                $particular_prod['VATEXPAMOUNT'] = $row->txval;
                $particular_prod['GSTOVRDNASSESSABLEVALUE'] = $plus . $row->txval;
                if ($supType == 'Interstate') {
                    $particular_prod['GSTOVRDNNATURE'] = "Interstate Sales Taxable";
                } else {
                    $particular_prod['GSTOVRDNNATURE'] = "Sales Taxable";
                }
                $particular_prod['GSTRATEVALUATIONTYPE'] = "Based on Value";
                if ($gst == $row->irt) {
                    $particular_prod['RATEDETAILS.LIST']['GSTRATEDUTYHEAD'] = "Integrated Tax";
                    $particular_prod['RATEDETAILS.LIST']['GSTRATEVALUATIONTYPE'] = "Based on Value";
                    $particular_prod['RATEDETAILS.LIST']['GSTRATE'] = $gst;
                } else {
                    $intragst['GSTRATEDUTYHEAD'] = "Central Tax";
                    $intragst['GSTRATEVALUATIONTYPE'] = "Based on Value";
                    $intragst['GSTRATE'] = $row->crt;
                    $particular_prod['RATEDETAILS.LIST'][] = $intragst;
                    $intragst['GSTRATEDUTYHEAD'] = "State Tax";
                    $particular_prod['RATEDETAILS.LIST'][] = $intragst;
                }
                //$data['LEDGERENTRIES.LIST'][] = $particular;
                $array[$key] = $data;
            } else {
                $particular = array();
                $gst = $row->irt;
                $output_gst['type'] = 'IGST';
                $gst_amount = $row->iamt;
                if ($gst == 0) {
                    $gst = $row->crt + $row->srt;
                    $output_gst['type'] = 'CGST';
                    $gst_amount = round($row->camt * 2, 2);
                }
                $output_gst['rate'] = $gst;
                if ($output_gst['amount'] > 0) {
                    $output_gst['amount'] = $output_gst['amount'] + $gst_amount;
                } else {
                    $output_gst['amount'] = $gst_amount;
                }
                $supType = ($row->splyTy == 'INTER') ? 'Interstate' : 'Local';
                if ($row->desc == 'Shipping charges') {
                    $particular['LEDGERNAME'] = $supType . " Shipping Charges @ " . round($gst, 2) . "%";
                } else {
                    $particular['LEDGERNAME'] = $supType . " Sales @ " . round($gst, 2) . "%";
                }

                $particular['ISDEEMEDPOSITIVE'] = "No";
                $particular['AMOUNT'] = $plus . $row->txval;
                $particular['GSTOVRDNASSESSABLEVALUE'] = $plus . $row->txval;

                if ($supType == 'Interstate') {
                    $particular['GSTOVRDNNATURE'] = "Interstate Sales Taxable";
                } else {
                    $particular['GSTOVRDNNATURE'] = "Sales Taxable";
                }

                $particular['GSTRATEVALUATIONTYPE'] = "Based on Value";
                $cgst = round($gst / 2, 2);
                $intragst['GSTRATEDUTYHEAD'] = "Central Tax";
                $intragst['GSTRATEVALUATIONTYPE'] = "Based on Value";
                $intragst['GSTRATE'] = (string) $cgst;
                $particular['RATEDETAILS.LIST'][] = $intragst;
                $intragst['GSTRATEDUTYHEAD'] = "State Tax";
                $particular['RATEDETAILS.LIST'][] = $intragst;

                $intergst['GSTRATEDUTYHEAD'] = "Integrated Tax";
                $intergst['GSTRATEVALUATIONTYPE'] = "Based on Value";
                $intergst['GSTRATE'] = $gst;
                $particular['RATEDETAILS.LIST'][] = $intergst;

                $array[$key]['LEDGERENTRIES.LIST'][] = $particular;
            }
        }

        $array[$key]['LEDGERENTRIES.LIST'][] = $particular_prod;
        if ($output_gst['type'] == 'IGST') {
            $outputgst['LEDGERNAME'] = "Output IGST @ " . round($output_gst['rate'], 2) . "%";
            $outputgst['ISDEEMEDPOSITIVE'] = "No";
            $outputgst['AMOUNT'] = $plus . $output_gst['amount'];
            $outputgst['VATEXPAMOUNT'] = $plus . $output_gst['amount'];
            $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
        } else {
            $outputgst['LEDGERNAME'] = "Output CGST @ " . round($output_gst['rate'], 2) . "%";
            $outputgst['ISDEEMEDPOSITIVE'] = "No";
            $outputgst['AMOUNT'] = $plus . round($output_gst['amount'] / 2, 2);
            $outputgst['VATEXPAMOUNT'] = $plus . round($output_gst['amount'] / 2, 2);
            $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
            $outputgst['LEDGERNAME'] = "Output SGST @ " . round($output_gst['rate'], 2) . "%";
            $array[$key]['LEDGERENTRIES.LIST'][] = $outputgst;
        }

        foreach ($array as $key => $row) {
            $amount = 0;
            $ledgeramount = 0;
            $lk = 0;
            foreach ($row['LEDGERENTRIES.LIST'] as $lkey => $ledger) {
                $lk = $lkey;
                $amt = $this->cnum($ledger['AMOUNT']);
                if ($amount == 0) {
                    $amount = $amt;
                } else {
                    $ledgeramount = $ledgeramount + $amt;
                }
            }
            if ($amount != $ledgeramount) {
                $dif = round($amount - $ledgeramount, 2);
                $tax_amt = $array[$key]['LEDGERENTRIES.LIST'][$lk]['AMOUNT'];
                if ($type == 'RI') {
                    $tax_amt = $tax_amt + $dif;
                } else {
                    $tax_amt = $tax_amt - $dif;
                }
                $array[$key]['LEDGERENTRIES.LIST'][$lk]['AMOUNT'] = $tax_amt;
                $array[$key]['LEDGERENTRIES.LIST'][$lk]['VATEXPAMOUNT'] = $tax_amt;
            }
        }
        
        return $array;
    }

    function cnum($num) {
        return str_replace('-', '', $num);
    }

}
