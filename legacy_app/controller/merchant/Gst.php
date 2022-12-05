<?php

/**
 * Franchise controller class to handle Merchants franchise
 */

use App\Jobs\TallyExport;

class Gst extends Controller
{

    private $iris_data = null;

    function __construct()
    {
        parent::__construct();
        $this->validateSession('merchant');
        $array = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        $this->smarty->assign("datearray", $array);
        $this->iris_data = $this->common->getListValue('config', 'config_type', 'IRIS_GST_DATA');
    }

    /**
     * Display merchant franchises list
     */
    function viewlist($type = null, $link = null)
    {
        try {
            $this->hasRole(1, 23);
            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            if ($type == 'gstr1') {
                $upload_id = $this->encrypt->decode($link);
                $detail = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $upload_id);
                $json['fp'] = $detail['fp'];
                $json['ft'] = 'GSTR1';
                $json['gstin'] = $detail['gstin'];
                $invoice_list = $irisapi->getInvoiceList(json_encode($json));
                SwipezLogger::info(__CLASS__, 'GSTR1 Response: ' . json_encode($invoice_list));
                if ($invoice_list['status'] == 1) {
                    $array = array();
                    foreach ($invoice_list['response'] as $inv) {
                        $array = array_merge($array, $inv);
                    }
                }
                $this->view->selectedMenu = array(12, 52, 90);
                $this->view->title = "Verify uploaded invoices";
                $this->smarty->assign("title", "Verify uploaded invoices");
            } else {
                $post_month = $this->session->get('post_month');
                if (isset($post_month)) {
                    $_POST['month'] = $this->session->get('post_month');
                    $this->session->remove('post_month');
                }
                $this->smarty->assign("month", '-1');
                if (!empty($_POST)) {
                    $response = $irisapi->getGSTData($_POST['gstin'], $_POST['month'] . $_POST['year'], 'B2B', 'GSTR2A');
                    //dd($response);
                    //$response = '{"status":1,"response":{"b2b":[{"inv":[{"itms":[{"num":1,"itm_det":{"samt":69.099999999999994,"rt":18,"txval":767.73000000000002,"camt":69.099999999999994}}],"val":905.91999999999996,"inv_typ":"R","pos":"29","idt":"10-01-2019","rchrg":"N","inum":"KA-1819-604674","chksum":"40890e86da58b55b5854035da365fa62d3b7260fba6dc59a69e052c55be7d9b6"},{"itms":[{"num":1,"itm_det":{"samt":104.31,"rt":18,"txval":1159,"camt":104.31}}],"val":1367,"inv_typ":"R","pos":"29","idt":"15-01-2019","rchrg":"N","inum":"KA-1819-673589","chksum":"03bc915a61b4bb7b9f7157eb48cea234df59787883fd7b631cccee5c400284ce"},{"itms":[{"num":1,"itm_det":{"samt":192.97,"rt":18,"txval":2144.0599999999999,"camt":192.97}}],"val":2529.9899999999998,"inv_typ":"R","pos":"29","idt":"05-01-2019","rchrg":"N","inum":"KA-1819-722523","chksum":"cf8896770ce096e934585f75000ea2c0d923700ac6b8a1f248bf06826ef03bd2"}],"cfs":"Y","ctin":"29AAICA3918J1ZE"}]}}';
                    //$response = json_decode($response, 1);
                    if ($response['response'] == null) {
                        $this->smarty->assign("error", $response['message']);
                    }
                    $array = $response['response']['b2b'];
                    $gstr2 = array();
                    foreach ($array as $key => $r) {
                        foreach ($r['inv'] as $kk => $inv) {
                            $iamount = 0;
                            $camount = 0;
                            $txvalue = 0;
                            foreach ($inv['itms'] as $item) {

                                if ($item['itm_det']['camt'] > 0) {
                                    $camount = $camount + $item['itm_det']['camt'];
                                }
                                $txvalue = $txvalue + $item['itm_det']['txval'];

                                if ($item['itm_det']['iamt'] > 0) {
                                    $iamount = $iamount + $item['itm_det']['iamt'];
                                }
                            }
                            $array[$key]['inv'][$kk]['camount'] = $camount;
                            $array[$key]['inv'][$kk]['iamount'] = $iamount;
                            $array[$key]['inv'][$kk]['txamount'] = $txvalue;
                        }
                    }
                    SwipezLogger::info(__CLASS__, 'GSTR2A Response: ' . json_encode($response));
                    $this->smarty->assign("post", $_POST);
                }
                $this->view->selectedMenu = array(12, 53);
                $this->view->title = "GSTR 2 invoice list";
                $this->smarty->assign("title", "GSTR 2 invoice list");
                $data = $this->model->getGSTDetails($this->merchant_id);
                $this->setdatemonth();
            }

            $this->smarty->assign("link", $link);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("type", $type);
            $this->smarty->assign("list", $array);

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            if ($type == 'gstr1') {
                $this->smarty->display(VIEW . 'merchant/gst/list.tpl');
            } else {
                $this->smarty->display(VIEW . 'merchant/gst/gstr2list.tpl');
            }

            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function gst3b($success = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            if ($success == 'success') {
                $this->smarty->assign("success", 'GST3B Invoices have been submited to GST Portal.');
            } else {
                $gst_list = $this->model->getGSTDetails($this->merchant_id);
                $this->smarty->assign("gst_list", $gst_list);

                if (!empty($_POST)) {
                    $json['fp'] = $_POST['month'] . $_POST['year'];
                    $json['gstin'] = $_POST['gstin'];
                    $salesSummary = $this->model->get3BSummary($this->merchant_id, $json['gstin'], $json['fp'], 'RI');
                    $crSummary = $this->model->get3BSummary($this->merchant_id, $json['gstin'], $json['fp'], 'C');
                    $dbSummary = $this->model->get3BSummary($this->merchant_id, $json['gstin'], $json['fp'], 'D');

                    $summary['taxable'] = $salesSummary['taxable'] - $crSummary['taxable'] + $dbSummary['taxable'];
                    $summary['igst'] = $salesSummary['igst'] - $crSummary['igst'] + $dbSummary['igst'];
                    $summary['cgst'] = $salesSummary['cgst'] - $crSummary['cgst'] + $dbSummary['cgst'];
                    $summary['sgst'] = $salesSummary['sgst'] - $crSummary['sgst'] + $dbSummary['sgst'];
                    $salesgrouping = $this->model->get3BDetail($this->merchant_id, $json['gstin'], $json['fp'], 'RI');
                    $crgrouping = $this->model->get3BDetail($this->merchant_id, $json['gstin'], $json['fp'], 'C');
                    $dbgrouping = $this->model->get3BDetail($this->merchant_id, $json['gstin'], $json['fp'], 'D');

                    foreach ($crgrouping as $cr) {
                        if (!isset($salesgrouping[$cr['pos']])) {
                            $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                            $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                        }
                        $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] - $cr['taxable'];
                        $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] - $cr['igst'];
                        $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] - $cr['cgst'];
                        $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] - $cr['sgst'];
                    }
                    foreach ($dbgrouping as $cr) {
                        if (!isset($salesgrouping[$cr['pos']])) {
                            $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                            $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                        }
                        $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] + $cr['taxable'];
                        $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] + $cr['igst'];
                        $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] + $cr['cgst'];
                        $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] + $cr['sgst'];
                    }
                    //dd($salesgrouping);
                    $PurchaseSummary = $this->model->get3BPurchaseSummary($this->merchant_id, $json['fp'], $json['gstin']);
                    $this->smarty->assign("fp", $json['fp']);
                    $this->smarty->assign("summary", $summary);
                    $this->smarty->assign("grouping", $salesgrouping);
                    $this->smarty->assign("purchaseSummary", $PurchaseSummary);
                    if (isset($_POST['download'])) {
                        $this->make3Bcsv($json['fp'], $json['gstin'], $summary, $salesgrouping, $PurchaseSummary);
                    }
                }
            }
            $this->setdatemonth();
            $this->view->selectedMenu = array(12, 51, 88);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("type", $type);
            $this->smarty->assign("list", $array);
            $this->smarty->assign("title", "Prepare summary");
            $this->view->title = "Prepare summary";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => 'GSTR 3B', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/3blist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function setdatemonth()
    {
        $months = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
        $year = array(date('Y') - 2, date('Y') - 1, date('Y'));
        if (isset($_POST['month'])) {
            $this->smarty->assign("month", $_POST['month']);
        }
        if (isset($_POST['year'])) {
            $this->smarty->assign("year", $_POST['year']);
        }
        if (isset($_POST['gstin'])) {
            $this->smarty->assign("gstin", $_POST['gstin']);
        }
        $this->smarty->assign("months", $months);
        $this->smarty->assign("years", $year);
    }

    function gstr1upload()
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            if (isset($_POST['submit'])) {
                $this->hasRole(2, 23);
                $fromdate = new DateTime($_POST['from_date']);
                $todate = new DateTime($_POST['to_date']);
                if ($_POST['type'] == 'QTR') {
                    $invlist1 = $this->model->getInvoices($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'B2CS');
                    $noteList1 = $this->model->getNotes($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'B2CS');

                    $invlist2 = $this->model->getInvoices($this->merchant_id, $_POST['gstin'], $todate->format('Y-m-01'), $todate->format('Y-m-d'), 'B2B');
                    $noteList2 = $this->model->getNotes($this->merchant_id, $_POST['gstin'], $todate->format('Y-m-01'), $todate->format('Y-m-d'), 'B2B');
                    if (empty($invlist1)) {
                        $invlist = $invlist2;
                    } else {
                        if (empty($invlist2)) {
                            $invlist = $invlist1;
                        } else {
                            $invlist = array_merge($invlist1, $invlist2);
                        }
                    }

                    if (empty($noteList1)) {
                        $noteList = $noteList2;
                    } else {
                        if (empty($noteList2)) {
                            $noteList = $noteList1;
                        } else {
                            $noteList = array_merge($noteList1, $noteList2);
                        }
                    }
                    $docinvlist = $this->model->getInvoices($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'ALL');
                    $docnoteList = $this->model->getNotes($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'ALL');
                } else {
                    $invlist = $this->model->getInvoices($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $_POST['type']);
                    $noteList = $this->model->getNotes($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $_POST['type']);
                    $docinvlist = $invlist;
                    $docnoteList = $noteList;
                }
                $count = count($docinvlist);
                $count2 = count($docnoteList);
                if ($count > 0 || $count2 > 0) {
                    foreach ($invlist as $inv) {
                        $array[] = $inv['id'];
                    }
                    foreach ($noteList as $inv) {
                        $array[] = $inv['id'];
                    }

                    foreach ($docinvlist as $inv) {
                        $docarray[] = $inv['id'];
                    }
                    foreach ($docnoteList as $inv) {
                        $docarray[] = $inv['id'];
                    }

                    $id = $this->model->saveR1Upload(json_encode($array), $count, $this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year'], $_POST['type']);
                    $invoicelist = $this->model->getDocumentInvoiceDetails($this->merchant_id, $_POST['gstin'], '', 'RI', json_encode($docarray));
                    $invoice_seq = $this->getSeqArray($invoicelist, 1);
                    $invoicelist = $this->model->getDocumentInvoiceDetails($this->merchant_id, $_POST['gstin'], '', 'C', json_encode($docarray));
                    $credit_seq = $this->getSeqArray($invoicelist, 5);
                    $this->model->deleteInvoiceDocument($this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year']);
                    $this->model->saveInvoiceDocument($invoice_seq, $this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year']);
                    $this->model->saveInvoiceDocument($credit_seq, $this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year']);
                    if ($id > 0) {
                        $this->session->set("successMessage", 'GSTR 1 Invoices have been prepared for your review.');
                        header('Location: /merchant/gst/gstr1upload');
                        die();
                    } else {
                        $this->smarty->assign("error", 'File upload failed');
                    }
                } else {
                    $this->smarty->assign("error", 'Invoices not found');
                }
                $this->smarty->assign("post", $_POST);
            }
            $this->setdatemonth();
            $data = $this->model->getGSTDetails($this->merchant_id);
            $post_month = $this->session->get('post_month');
            if (isset($post_month)) {
                $_POST['month'] = $this->session->get('post_month');
                $this->session->remove('post_month');
            }
            $bulk_list = $this->common->getListValue('iris_r1_upload', 'merchant_id', $this->merchant_id, 0, ' and status<>3');
            foreach ($bulk_list as $k => $v) {
                $bulk_list[$k]['link'] = $this->encrypt->encode($v['upload_id']);
                $bulk_list[$k]['created_at'] = $this->generic->formatTimeString($v['created_date']);
                $bulk_list[$k]['total_invoices'] = count(json_decode($v['invoice_ids']));
            }
            $this->view->hide_first_col = true;
            $this->view->selectedMenu = array(12, 52, 90);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("bulk_list", $bulk_list);
            $this->smarty->assign("title", "Prepare your GSTR 1");
            $this->view->title = "Prepare your GSTR 1";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->js = ['transaction'];
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/r1upload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function invoicedocument($link = null)
    {
        try {
            $this->hasRole(1, 23);
            $id = $this->encrypt->decode($link);
            $row = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $id);
            if ($_POST['submit']) {
                $list = array();
                $this->model->deleteInvoiceDocument($this->merchant_id, $row['gstin'], $row['fp']);
                foreach ($_POST['docNum'] as $key => $val) {
                    $array['docNum'] = $val;
                    if ($val == 1) {
                        $array['docTyp'] = 'Invoices for outward supply';
                    } else if ($val == 5) {
                        $array['docTyp'] = 'Credit Note';
                    } else if ($val == 4) {
                        $array['docTyp'] = 'Debit Note';
                    }
                    $array['from_serial'] = $_POST['from'][$key];
                    $array['to_serial'] = $_POST['to'][$key];
                    $array['total'] = $_POST['total'][$key];
                    $array['canceled'] = $_POST['cancel'][$key];
                    $list[] = $array;
                }
                $this->model->saveInvoiceDocument($list, $this->merchant_id, $row['gstin'], $row['fp']);
                $this->smarty->assign("success", 'Invoice documents have been saved successfully.');
            }

            $invoice_seq = $this->model->getInvoiceDocuments($this->merchant_id, $row['fp'], $row['gstin']);
            $this->smarty->assign("invoice_seq", $invoice_seq);
            $this->view->hide_first_col = true;
            $this->view->selectedMenu = array(12, 52, 90);
            $this->smarty->assign("title", "Invoice document");
            $this->view->title = "Invoice document";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->js = ['transaction'];
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/invoicedocument.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function reportinvoicedocument($link = null)
    {
        try {
            $this->hasRole(1, 23);
            if (isset($_POST['submit'])) {
                $this->hasRole(2, 23);
                $fromdate = new DateTime($_POST['from_date']);
                $todate = new DateTime($_POST['to_date']);
                $this->smarty->assign("post", $_POST);
            }
            $this->setdatemonth();
            $data = $this->model->getGSTDetails($this->merchant_id);
            $post_month = $this->session->get('post_month');
            if (isset($post_month)) {
                $_POST['month'] = $this->session->get('post_month');
                $this->session->remove('post_month');
            }

            $invoice_list = $this->model->getDocumentInvoiceDetails($this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year'], 'RI');
            $invoice_seq = $this->getSeqArray($invoice_list, 1);
            $invoice_list = $this->model->getDocumentInvoiceDetails($this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year'], 'C');
            $credit_seq = $this->getSeqArray($invoice_list, 5);
            $invoice_list = $this->model->getDocumentInvoiceDetails($this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year'], 'D');
            $debit_seq = $this->getSeqArray($invoice_list, 4);
            $this->smarty->assign("invoice_seq", $invoice_seq);
            $this->smarty->assign("credit_seq", $credit_seq);
            $this->smarty->assign("debit_seq", $debit_seq);
            $this->view->hide_first_col = true;
            $this->view->selectedMenu = array(12, 52, 90);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("title", "Invoice document");
            $this->view->title = "Invoice document";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/rpt_invoicedocument.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function getSeqArray($invoice_list, $docNum)
    {
        $seq_array = array();
        foreach ($invoice_list as $row) {
            $string = $row['inv_number'];
            $int = 0;
            $numeric = true;
            $number = 0;
            while ($numeric == true) {
                $int--;
                $num = substr($string, $int);
                if (is_numeric($num) && substr($num, 0, 1) != '-') {
                    $number = $num;
                } else {
                    $numeric = false;
                    $cnt = strlen($string) + $int;
                    $key = substr($string, 0, $int + 1);
                    $seq_array[$key][] = $number;
                }
            }
        }
        foreach ($seq_array as $key => $seq) {
            sort($seq);
            $total = count($seq);
            $start = $seq[0];
            $last = $seq[$total - 1];
            if ($docNum == 1) {
                $array['docTyp'] = 'Invoices for outward supply';
            } else if ($docNum == 5) {
                $array['docTyp'] = 'Credit Note';
            } else if ($docNum == 4) {
                $array['docTyp'] = 'Debit Note';
            }
            $array['docNum'] = $docNum;
            $array['from_serial'] = $key . $start;
            $array['to_serial'] = $key . $last;
            $array['total'] = $last - $start + 1;
            $array['canceled'] = $array['total'] - $total;
            $seqarray[] = $array;
        }
        return $seqarray;
    }

    function invoicelist($link, $type)
    {
        try {
            $this->hasRole(1, 23);
            $bulk_id = $this->encrypt->decode($link);
            $data = array();
            if ($type == 1) {
                $data = $this->common->getListValue('iris_invoice', 'bulk_id', $bulk_id, 1, " and dty='RI'");
            } else if ($type == 'creditnote') {
                $data = $this->common->getListValue('iris_invoice', 'bulk_id', $bulk_id, 1, " and dty='C'");
            } else {
                $ids = $this->common->getRowValue('invoice_ids', 'iris_r1_upload', 'upload_id', $bulk_id);
                if (!empty($ids) && $ids != null && $ids != "null") {
                    $ids = str_replace('"', "", $ids);
                    $ids = str_replace('[', "(", $ids);
                    $ids = str_replace(']', ")", $ids);
                    $data = $this->common->getListValue('iris_invoice', 'merchant_id', $this->merchant_id, 0, ' and id in ' . $ids);
                }
            }
            $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            foreach ($gst_state_code as $gsc) {
                if (strlen($gsc['config_key']) == 1) {
                    $gsc['config_key'] = '0' . $gsc['config_key'];
                }
                $state_array[$gsc['config_key']] = $gsc['config_value'];
            }
            foreach ($data as $key => $row) {
                $data[$key]['state'] = $state_array[$row['pos']];
            }
            $data = $this->generic->getEncryptedList($data, 'link', 'id');
            $this->smarty->assign("data", $data);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("type", $type);
            if ($type == 'creditnote') {
                $this->smarty->assign("title", 'Credit note list');
                $this->view->title = "Credit note list";
            } else {
                $this->smarty->assign("title", 'GST invoice list');
                $this->view->title = "GST invoice list";
            }

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/invoicelist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function moveinvoices()
    {
        try {
            $this->validatePackage();
            $user_id = $this->session->get('userid');
            #SwipezLogger::info(__CLASS__, "Invoice details invoked by $user_id");
            $aging_by_selected = 'bill_date';
            $cycle_selected = '';
            $customer_selected = '';
            $invoice_type = 1;
            $current_date = date("d M Y");
            $last_date = $this->getLast_date();
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $aging_by = $_POST['aging_by'];
                $this->smarty->assign("checkedlist", $_POST['status']);
                $status = implode(',', $_POST['status']);
                $column_select = $_POST['column_name'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
                $customer_name = '';
                $aging_by = 'bill_date';
                $status = '';
                $column_select = array();
            }

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);

            $is_settle = 0;
            $this->smarty->assign("from_date", $this->generic->formatDateString($from_date));
            $this->smarty->assign("to_date", $this->generic->formatDateString($to_date));
            $this->smarty->assign("session_date_format", $this->session->get('default_date_format'));
            $_SESSION["session_date_format"] = $this->session->get('default_date_format');
            $group = '';
            $franchise_id = 0;
            $vendor_id = 0;
            $where = '';

            $billing_profile_id = ($_POST['billing_profile_id'] > 0) ? $_POST['billing_profile_id'] : 0;
            $billing_profile_list = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $this->merchant_id, 1);
            $this->smarty->assign("billing_profile_list", $billing_profile_list);
            $this->smarty->assign("billing_profile_id", $billing_profile_id);

            $_SESSION['_from_date'] = $fromdate->format('Y-m-d');
            $_SESSION['_to_date'] = $todate->format('Y-m-d');
            $_SESSION['_cycle_name'] = $cycle_selected;
            $_SESSION['_customer_id'] = $customer_selected;
            $_SESSION['_status'] = $status;
            $_SESSION['_aging_by'] = $aging_by;
            $_SESSION['_is_settle'] = $is_settle;
            $_SESSION['_franchise_id'] = $franchise_id;
            $_SESSION['_vendor_id'] = $vendor_id;
            $_SESSION['_group'] = $group;
            $_SESSION['_invoice_type'] = $invoice_type;
            $_SESSION['_billing_profile_id'] = $billing_profile_id;
            $data = $this->model->getGSTDetails($this->merchant_id);
            $this->smarty->assign("gst_list", $data);
            $this->setAjaxDatatableSession();
            $this->smarty->assign("title", "Move Invoices to GST");
            $this->view->title = "Move Invoices to GST";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => 'GST invoice list', 'url' => '/merchant/gst/listinvoices'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->selectedMenu = array(12, 50, 124);
            $this->view->sortdisable = 1;
            $this->view->ajaxpage = 'moveinvoice.php';
            $this->view->js = ['transaction'];
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/moveinvoice.tpl');
            $this->view->render('footer/request_list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function movenotes()
    {
        try {
            $user_id = $this->session->get('userid');
            $current_date = date("d M Y");
            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
            } else {
                $from_date = $current_date;
            }

            $fromdate = new DateTime($from_date);

            $this->smarty->assign("from_date", $from_date);

            $notes = $this->model->getNotesDetail($this->merchant_id, $fromdate->format('Y-m'));
            $data = $this->model->getGSTDetails($this->merchant_id);
            $this->smarty->assign("gst_list", $data);
            $this->smarty->assign("notes", $notes);
            $this->smarty->assign("title", "Move notes to GST");
            $this->view->title = "Move notes to GST";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => 'GST invoice list', 'url' => '/merchant/gst/listinvoices'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->selectedMenu = array(12, 50, 167);
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/movenotes.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function savegstinvoice()
    {
        try {
            $success_count = 0;
            $errors = array();
            $merchant_state = $this->common->getMerchantProfile($this->merchant_id, 0, 'state');
            $merchant_det = $this->common->getSingleValue('merchant', 'merchant_id', $this->merchant_id);
            foreach ($_POST['customer_check'] as $payment_request_id) {
                $data = array();
                $inv_det = $this->common->getSingleValue('payment_request', 'payment_request_id', $payment_request_id);
                $cust_det = $this->common->getSingleValue('customer', 'customer_id', $inv_det['customer_id']);
                $particular_det = $this->common->getListValue('invoice_particular', 'payment_request_id', $payment_request_id, 1);
                $tax_det = $this->common->getinvoiceTax($payment_request_id);
                if ($merchant_det['industry_type'] == 1) {
                    $data['ty'] = 'G';
                } else {
                    $data['ty'] = 'S';
                }
                $data['dty'] = 'RI';
                $data['note_number'] = null;
                $data['note_date'] = null;
                $data['invoice_date'] = $inv_det['bill_date'];
                $data['invoice_number'] = $inv_det['invoice_number'];
                $data['invoice_amount'] = $inv_det['invoice_total'];
                $data['seller_gstin'] = $inv_det['gst_number'];
                if ($cust_det['gst_number'] == '') {
                    $data['invTyp'] = 'B2CS';
                    $data['ctpy'] = 'U';
                    $data['ctin'] = NULL;
                    $data['cname'] = NULL;
                } else {
                    $data['invTyp'] = 'B2B';
                    $data['ctpy'] = 'R';
                    $data['ctin'] = $cust_det['gst_number'];
                    $data['cname'] = $cust_det['first_name'] . ' ' . $cust_det['last_name'];
                }

                $data['bill_from'] = $merchant_state;
                $data['ship_to'] = $cust_det['state'];
                $supply_type = 'intra';
                if ($data['bill_from'] != $data['ship_to']) {
                    $supply_type = 'inter';
                }
                $particulars = array();
                foreach ($particular_det as $det) {
                    $particular = array();
                    $particular['description'] .= $det['item'];
                    if ($det['qty'] > 0) {
                        $particular['qty'] = $det['qty'];
                    } else {
                        $particular['qty'] = 1;
                    }

                    if ($det['rate'] > 0) {
                        $particular['exclusive_tax'] = $det['rate'];
                    } else {
                        $particular['exclusive_tax'] = $det['total_amount'];
                    }
                    $particular['igst_rate'] = 0;
                    $particular['igst_tax'] = 0;
                    $particular['cgst_rate'] = 0;
                    $particular['cgst_tax'] = 0;
                    $particular['sgst_rate'] = 0;
                    $particular['sgst_tax'] = 0;
                    if ($det['gst'] > 0) {
                        if ($supply_type == 'inter') {
                            $particular['igst_rate'] = $det['gst'];
                            $particular['igst_tax'] = round($particular['exclusive_tax'] * $det['gst'] / 100);
                        } else {
                            $gst = $det['gst'] / 2;
                            $particular['cgst_rate'] = $gst;
                            $particular['cgst_tax'] = round($particular['exclusive_tax'] * $gst / 100);
                            $particular['sgst_rate'] = $gst;
                            $particular['sgst_tax'] = round($particular['exclusive_tax'] * $gst / 100);
                        }
                    }
                    $particular['invoice_amount'] = $particular['exclusive_tax'] + $particular['cgst_tax'] + $particular['sgst_tax'] + $particular['igst_tax'];
                    if ($det['sac_code'] != '') {
                        $particular['sac'] = $det['sac_code'];
                    }
                    $particulars[] = $particular;
                }

                $error = $this->validateGstInvoice($data);
                if ($error == false) {
                    $inv_id = $this->common->getRowValue('id', 'iris_invoice', 'payment_request_id', $payment_request_id, 1, " and merchant_id='" . $this->merchant_id . "'");
                    if ($inv_id > 0) {
                        $this->model->updateGSTInvoice($inv_id, $this->merchant_id, $data);
                        $this->common->genericupdate('iris_invoice_detail', 'is_active', 0, 'invoice_id', $inv_id);
                    } else {
                        $inv_id = $this->model->createGSTInvoice($this->merchant_id, $payment_request_id, $data);
                    }
                    foreach ($particulars as $row) {
                        $this->model->createGSTInvoiceDetail($inv_id, $row, $this->merchant_id);
                    }
                    $success_count++;
                } else {
                    $errors[$data['invoice_number']] = $error;
                }
            }

            if ($success_count > 0) {
                $this->smarty->assign('success', $success_count . ' Invoices moved successfully');
            }
            if (!empty($error)) {
                $this->smarty->assign('haserrors', $errors);
            }
            //$this->savegstirisinvoice();
            $this->listinvoices();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function invoicesave()
    {
        $inv_id = $this->encrypt->decode($_POST['invoice_id']);
        $data['invTyp'] = $_POST['invTyp'];
        $data['cname'] = $_POST['cname'];
        $data['ctin'] = $_POST['ctin'];
        $data['pos'] = $_POST['pos'];
        $data['invoice_date'] = $this->generic->sqlDate($_POST['pdt']);
        $data['seller_gstin'] = $_POST['gstin'];
        $data['invoice_number'] = $_POST['inum'];
        $data['invoice_amount'] = $_POST['total_amount'];
        $data['ntDt'] = null;
        if (isset($_POST['ntDt'])) {
            $data['ntDt'] = $this->generic->sqlDate($_POST['ntDt']);
        }
        $data['splyTy'] = strtoupper($_POST['splyTy']);
        $this->model->updateGSTInvoice($inv_id, $this->merchant_id, $data);
        $this->common->genericupdate('iris_invoice_detail', 'is_active', 0, 'invoice_id', $inv_id);
        $num = 0;

        $gst_array = array('3' => 5, '4' => 12, '5' => 18, '6' => 28, '0' => 0, '1' => 0, '2' => 0);

        foreach ($_POST['particular'] as $key => $val) {
            $num++;
            $data = array();
            $data['description'] = $val;
            $data['sac'] = $_POST['sac'][$key];
            $data['qty'] = $_POST['unit'][$key];
            $data['exclusive_tax'] = round($_POST['rate'][$key] * $data['qty'], 2);
            $gst = $gst_array[$_POST['tax'][$key]];
            $particular_id = $_POST['particular_id'][$key];
            $tax_amt = 0;
            if ($_POST['splyTy'] == 'inter') {
                $data['igst_rate'] = $gst;
                $data['igst_tax'] = round($data['exclusive_tax'] * $gst / 100, 2);
                $data['cgst_rate'] = 0;
                $data['cgst_tax'] = 0;
                $data['sgst_rate'] = 0;
                $data['sgst_tax'] = 0;
                $tax_amt = $data['igst_tax'];
            } else {
                $gst = $gst / 2;
                $data['igst_rate'] = 0;
                $data['igst_tax'] = 0;
                $data['cgst_rate'] = $gst;
                $data['cgst_tax'] = round($data['exclusive_tax'] * $gst / 100, 2);
                $data['sgst_rate'] = $gst;
                $data['sgst_tax'] = round($data['exclusive_tax'] * $gst / 100, 2);
                $tax_amt = $data['sgst_tax'] + $data['cgst_tax'];
            }
            $data['invoice_amount'] = $data['exclusive_tax'] + $tax_amt;

            if (is_numeric($particular_id)) {
                $this->model->updateInvoiceDetail($particular_id, $data, $this->merchant_id);
            } else {
                $data['ty'] = 'G';
                $data['product_code'] = time() . rand(0, 99);
                $this->model->createGSTInvoiceDetail($inv_id, $data, $this->merchant_id, $num);
            }
        }
        if ($_POST['ref'] != '') {
            $this->updatesaved($_POST['ref'], $inv_id);
        }

        $this->session->set('successMessage', 'Detail updated successfully');
        header('Location: /merchant/gst/viewinvoice/' . $_POST['invoice_id']);
    }

    function savegstnotes()
    {
        try {
            $success_count = 0;
            $errors = array();
            $merchant_state = $this->common->getMerchantProfile($this->merchant_id, 0, 'state');
            $merchant_det = $this->common->getSingleValue('merchant', 'merchant_id', $this->merchant_id);
            foreach ($_POST['customer_check'] as $note_id) {
                $data = array();
                $inv_det = $this->common->getSingleValue('credit_debit_note', 'id', $note_id);
                $cust_det = $this->common->getSingleValue('customer', 'customer_id', $inv_det['customer_id']);
                $particular_det = $this->common->getListValue('credit_debit_detail', 'credit_debit_id', $note_id, 1);
                if ($merchant_det['industry_type'] == 1) {
                    $data['ty'] = 'G';
                } else {
                    $data['ty'] = 'S';
                }
                $data['dty'] = 'C';
                $data['note_number'] = $inv_det['credit_debit_no'];
                $data['note_date'] = $inv_det['date'];
                $data['invoice_date'] = $inv_det['bill_date'];
                $data['invoice_number'] = $inv_det['invoice_no'];
                $data['invoice_amount'] = $inv_det['total_amount'];
                $data['seller_gstin'] = $_POST['gst_number'];
                if ($cust_det['gst_number'] == '') {
                    $data['invTyp'] = 'B2CS';
                    $data['ctpy'] = 'U';
                    $data['ctin'] = NULL;
                    $data['cname'] = NULL;
                } else {
                    $data['invTyp'] = 'B2B';
                    $data['ctpy'] = 'R';
                    $data['ctin'] = $cust_det['gst_number'];
                    $data['cname'] = $cust_det['first_name'] . ' ' . $cust_det['last_name'];
                }

                $data['bill_from'] = $merchant_state;
                $data['ship_to'] = $cust_det['state'];

                $error = $this->validateGstInvoice($data);
                if ($error == false) {
                    $inv_id = $this->common->getRowValue('id', 'iris_invoice', 'payment_request_id', $note_id, 1, " and merchant_id='" . $this->merchant_id . "'");
                    if ($inv_id > 0) {
                        $this->model->updateGSTInvoice($inv_id, $this->merchant_id, $data);
                    } else {
                        $invoice_id = $this->model->createGSTInvoice($this->merchant_id, $note_id, $data);
                    }
                    $num = 1;
                    foreach ($particular_det as $pdet) {
                        $data['invoice_amount'] = $pdet['total_value'];
                        $data['hsnSc'] = $pdet['sac_code'];
                        $data['hsnSc'] = $pdet['sac_code'];
                        $data['description'] = $pdet['particular_name'];
                        $data['qty'] = 1;
                        $data['product_code'] = 'NoteDet' . $pdet['id'];
                        $data['exclusive_tax'] = $pdet['amount'];
                        if ($pdet['igst_amount'] > 0) {
                            $data['igst_rate'] = $pdet['gst_percent'];
                            $data['cgst_rate'] = 0;
                            $data['sgst_rate'] = 0;
                        } else {
                            $data['igst_rate'] = 0;
                            $data['cgst_rate'] = $pdet['gst_percent'] / 2;
                            $data['sgst_rate'] = $pdet['gst_percent'] / 2;
                        }
                        $data['igst_tax'] = $pdet['igst_amount'];
                        $data['cgst_tax'] = $pdet['cgst_amount'];
                        $data['sgst_tax'] = $pdet['sgst_amount'];
                        $this->model->createGSTInvoiceDetail($invoice_id, $data, $this->merchant_id, $num);
                        $num++;
                    }
                    $success_count++;
                } else {
                    $errors[$data['invoice_number']] = $error;
                }

                $qty = 1;
                foreach ($particular_det as $det) {
                    $data['description'] .= $det['item'];
                    if ($det['qty'] > 0) {
                        $qty = $qty + $det['qty'];
                    }
                    if ($det['sac_code'] != '') {
                        $data['sac'] = $det['sac_code'];
                    }
                }
                $data['qty'] = $qty;
                $applicable = 0;
                $igst_percent = 0;
                $igst_amt = 0;
                $cgst_percent = 0;
                $cgst_amt = 0;
                $sgst_percent = 0;
                $sgst_amt = 0;
                foreach ($tax_det as $det) {
                    $applicable = $applicable + $det['applicable'];
                    if ($det['tax_type'] == 1) {
                        $cgst_percent = $det['tax_percent'];
                        $cgst_amt = $cgst_amt + $det['tax_amount'];
                    }
                    if ($det['tax_type'] == 2) {
                        $sgst_percent = $det['tax_percent'];
                        $sgst_amt = $sgst_amt + $det['tax_amount'];
                    }
                    if ($det['tax_type'] == 3) {
                        $igst_percent = $det['tax_percent'];
                        $igst_amt = $igst_amt + $det['tax_amount'];
                    }
                }
                $data['exclusive_tax'] = $inv_det['basic_amount'];
                $data['igst_rate'] = $igst_percent;
                $data['igst_tax'] = $igst_amt;
                $data['cgst_rate'] = $cgst_percent;
                $data['cgst_tax'] = $cgst_amt;
                $data['sgst_rate'] = $sgst_percent;
                $data['sgst_tax'] = $sgst_amt;
            }

            if ($success_count > 0) {
                $this->smarty->assign('success', $success_count . ' Invoices moved successfully');
            }
            if (!empty($error)) {
                $this->smarty->assign('haserrors', $errors);
            }
            //$this->savegstirisinvoice();
            $this->listinvoices();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E001-R]Error while merchant balance report Error: for merchant [' . $user_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validateGstInvoice($data)
    {
        if ($data['ship_to'] == '') {
            $error = 'Customer state is empty';
        }
        return $error;
    }

    function listinvoices()
    {
        try {
            $this->hasRole(1, 23);
            $current_date = date("M Y");
            if (isset($_POST['month'])) {
                $this->smarty->assign("gst_number", $_POST['gst_number']);
                $from_date = '01-' . $_POST['month'] . '-' . $_POST['year'];
                $fromdate = new DateTime($from_date);
                $data = $this->model->getGstInvoiceList($this->merchant_id, $_POST['gst_number'], $fromdate->format('Y-m'));
                $expense_data = $this->model->getExpenseDetail($this->merchant_id, $fromdate->format('Y-m'), $_POST['gst_number']);
                $expense_data = $this->generic->getEncryptedList($expense_data, 'expense_id', 'link');
                $expense_data = $this->generic->getMoneyFormatList($expense_data, 'total_amount', 'total_amount');
            } else {
                $from_date = $current_date;
            }
            $this->smarty->assign("from_date", $from_date);
            $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            foreach ($gst_state_code as $gsc) {
                if (strlen($gsc['config_key']) == 1) {
                    $gsc['config_key'] = '0' . $gsc['config_key'];
                }
                $state_array[$gsc['config_key']] = $gsc['config_value'];
            }
            foreach ($data as $key => $row) {
                $data[$key]['link'] = $this->encrypt->encode($row['id']);
                $data[$key]['state'] = $state_array[$row['pos']];
                $data[$key]['val'] = $this->generic->moneyFormatIndia($row['val']);
            }
            $this->setdatemonth();
            $gst_list = $this->model->getGSTDetails($this->merchant_id);
            $this->smarty->assign("gst_list", $gst_list);
            $this->view->selectedMenu = array(12, 50, 126);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("expense_data", $expense_data);
            $this->smarty->assign("title", 'GST invoice list');
            $this->view->title = "GST invoice list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatable = true;
            $this->view->datatablejs = 'table-no-export-class';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/gstinvoicelist.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function gst3bupload()
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            if (isset($_POST['submit'])) {
                $this->hasRole(2, 23);
                $id = $this->model->uploadExcel($_FILES['file'], $this->merchant_id, $_POST['gstin'], $_POST['month'] . $_POST['year']);
                if ($id > 0) {
                    $this->session->set("successMessage", ' GST3B file has been uploaded.');
                    header('Location: /merchant/gst/gst3bupload');
                    die();
                } else {
                    $this->smarty->assign("Error", 'File upload failed');
                }
            }

            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            $this->setdatemonth();
            $data = $this->model->getGSTDetails($this->merchant_id);
            $post_month = $this->session->get('post_month');
            if (isset($post_month)) {
                $_POST['month'] = $this->session->get('post_month');
                $this->session->remove('post_month');
            }
            $bulk_list = $this->common->getListValue('iris_3b_upload', 'merchant_id', $this->merchant_id, 0, ' and status<>3');
            foreach ($bulk_list as $k => $v) {
                $bulk_list[$k]['link'] = $this->encrypt->encode($v['bulk_upload_id']);
                $bulk_list[$k]['created_at'] = $this->generic->formatTimeString($v['created_date']);
            }
            $this->view->hide_first_col = true;
            $this->view->selectedMenu = array(12, 51, 89);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("merchant_id", $this->merchant_id);
            $this->smarty->assign("bulk_list", $bulk_list);
            $this->smarty->assign("title", "Save Summary");
            $this->view->title = "Save Summary";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => 'GSTR 3B', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/3bupload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function delete3b($bulk_id)
    {
        try {
            $this->hasRole(3, 23);
            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($bulk_id);
            $detail = $this->common->getSingleValue('iris_3b_upload', 'bulk_upload_id', $bulk_id);
            if ($detail['merchant_id'] == $merchant_id) {
                $this->common->genericupdate('iris_3b_upload', 'status', 3, 'bulk_upload_id', $bulk_id);
                $this->session->set("successMessage", ' Excel deleted successfully.');
                header('Location: /merchant/gst/gst3bupload');
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E63]Error while delete bulk upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deletestaginginvoice($invoice_id, $link)
    {
        try {
            $this->hasRole(3, 23);
            $dty = $this->common->getRowValue('dty', 'iris_invoice', 'id', $invoice_id);
            $this->common->genericupdate('iris_invoice', 'is_active', 0, 'id', $invoice_id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
            $this->session->set("successMessage", ' Invoice deleted successfully.');
            if ($dty == 'C') {
                header('Location: /merchant/gst/invoicelist/' . $link . '/creditnote');
            } else {
                header('Location: /merchant/gst/invoicelist/' . $link . '/1');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E63]Error while delete gst staging invoice Error: for merchant [' . $this->merchant_id . '] and invoice id [' . $invoice_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function approveinvoice($link, $type)
    {
        try {
            $this->hasRole(3, 23);
            $this->session->set("successMessage", ' Invoice approved successfully.');
            $bulk_id = $this->encrypt->decode($link);
            if ($type == 1) {
                $this->common->genericupdate('bulk_upload', 'status', 5, 'bulk_upload_id', $bulk_id);
                $this->common->genericupdate('iris_invoice', 'status', 1, 'id', $bulk_id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                header('Location: /merchant/gst/bulkupload');
            } else if ($type == 'stock') {
                $this->common->genericupdate('bulk_upload', 'status', 5, 'bulk_upload_id', $bulk_id);
                $this->common->genericupdate('iris_invoice', 'status', 1, 'id', $bulk_id, $this->user_id, " and merchant_id='" . $this->merchant_id . "'");
                header('Location: /merchant/gst/bulkupload/stock');
            } else {
                $this->common->genericupdate('iris_r1_upload', 'status', 4, 'upload_id', $bulk_id);
                header('Location: /merchant/gst/gstr1upload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E63]Error while delete bulk upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function deleter1($bulk_id)
    {
        try {
            $this->hasRole(3, 23);
            $merchant_id = $this->session->get('merchant_id');
            $bulk_id = $this->encrypt->decode($bulk_id);
            $detail = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $bulk_id);
            if ($detail['merchant_id'] == $merchant_id) {
                $this->common->genericupdate('iris_r1_upload', 'status', 3, 'upload_id', $bulk_id);
                $this->session->set("successMessage", ' Entry deleted successfully.');
                header('Location: /merchant/gst/gstr1upload');
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E63]Error while delete bulk upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function make3Bcsv($fp, $gstin, $summary, $grouping, $PurchaseSummary)
    {
        $csv .= "Return period," . $fp . ",,,,,,,\n";
        $csv .= "Return Type,GSTR3B,,,,,,,\n";
        $csv .= "GSTIN of Taxpayer,Item Type,Item Details,Place of Supply,Total Taxable value,IGST,CGST,SGST,CESS\n";
        $csv .= $gstin . ",3.1a,(a) Outward taxable supplies (other than zero rated; nil rated and exempted),," . $summary['taxable'] . "," . $summary['igst'] . "," . $summary['cgst'] . "," . $summary['sgst'] . ",0\n";
        foreach ($grouping as $grp) {
            $csv .= $gstin . ",3.2a,Supplies made to Unregistered Persons (Inter-State)," . $grp['pos'] . "," . $grp['taxable'] . "," . $grp['igst'] . ",,,\n";
        }
        if ($PurchaseSummary['igst'] != null || $PurchaseSummary['cgst'] != null) {
            $csv .= $gstin . ",4.5a,All other ITC,,," . $PurchaseSummary['igst'] . "," . $PurchaseSummary['cgst'] . "," . $PurchaseSummary['sgst'] . ",0\n";
        }
        $file_name = TMP_FOLDER . time() . 'GSTR3B_Summary.csv';
        $csv_handler = fopen($file_name, 'w');
        fwrite($csv_handler, $csv);
        fclose($csv_handler);
        header('Location: /' . $file_name);
    }

    function gstconnection($gstin = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            $data = $this->model->getGSTDetails($this->merchant_id);
            if ($gstin != null) {
                $this->smarty->assign("gstin", $gstin);
                $this->smarty->assign("gst_connection_failed", 1);
            }
            if (!empty($_POST)) {
                require_once UTIL . 'IRISAPI.php';
                $irisapi = new IRISAPI($this->iris_data);
                $gstin = $_POST['gstin'];
                $this->smarty->assign("gstin", $gstin);
                $response = $irisapi->validateConnection($gstin);
                SwipezLogger::info(__CLASS__, 'Validate GSTIN Connection Response: ' . json_encode($response));
                if ($response['response'] == 1) {
                    $this->smarty->assign("success", 'Your session with GSTN is valid!');
                } else {
                    $this->smarty->assign("gst_connection_failed", 1);
                    if (isset($_POST['otp'])) {
                        $company_id = $this->common->getRowValue('gsp_id', 'merchant_billing_profile', 'gst_number', $_POST['gstin']);
                        $response = $irisapi->generateOTP($company_id);
                        SwipezLogger::info(__CLASS__, 'Request OTP Response: ' . json_encode($response));
                        $this->smarty->assign("showotp", 1);
                    }
                    if (isset($_POST['submit_otp'])) {
                        $company_id = $this->common->getRowValue('gsp_id', 'merchant_billing_profile', 'gst_number', $_POST['gstin']);
                        $response = $irisapi->submitOTP($company_id, $_POST['otp_text']);
                        SwipezLogger::info(__CLASS__, 'Submit OTP Response: ' . json_encode($response));
                        if ($response['response'] == true) {
                            $this->smarty->assign("gst_connection_failed", 0);
                            $this->smarty->assign("success", 'Connection obtained with the GST portal.');
                        } else {
                            $this->smarty->assign("error", $response['message']);
                        }
                    }
                }
            }
            $list = array();
            $this->view->selectedMenu = array(12, 49);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("title", "GST connection");
            $this->view->title = "GST connection";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/gstconnection.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function gstdraft($link = null, $type = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            $data = $this->model->getGSTDetails($this->merchant_id);
            $upload_id = 0;
            if ($link != null) {
                $upload_id = $this->encrypt->decode($link);
                $row = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $upload_id);
                $_POST['month'] = substr($row['fp'], 0, 2);
                $_POST['year'] = substr($row['fp'], 2);
                $_POST['gstin'] = $row['gstin'];
                if ($type == 'create') {
                    $_POST['create'] = true;
                } else if ($type == 'delete') {
                    $_POST['delete'] = true;
                }
            }
            if (!empty($_POST)) {
                require_once UTIL . 'IRISAPI.php';
                $irisapi = new IRISAPI($this->iris_data);
                $period = $_POST['month'] . $_POST['year'];
                $gstin = $_POST['gstin'];
                if (isset($_POST['create'])) {
                    $response = $irisapi->createGSTR($period, $gstin, 'GSTR1');
                    SwipezLogger::info(__CLASS__, 'Create draft Response: ' . json_encode($response));
                    if ($upload_id > 0) {
                        $this->common->genericupdate('iris_r1_upload', 'status', 5, 'upload_id', $upload_id);
                        $this->session->set("successMessage", $response['message']);
                        header('Location: /merchant/gst/gstr1upload');
                        die();
                    }
                    $this->smarty->assign("success", $response['message']);
                }
                if (isset($_POST['otp'])) {
                    $company_id = $this->common->getRowValue('gsp_id', 'merchant_billing_profile', 'gst_number', $_POST['gstin']);
                    $response = $irisapi->generateOTP($company_id);
                    SwipezLogger::info(__CLASS__, 'Request OTP Response: ' . json_encode($response));
                    $this->smarty->assign("gst_connection_failed", 1);
                    $this->smarty->assign("showotp", 1);
                }
                if (isset($_POST['delete'])) {
                    $response = $irisapi->deleteGSTR($period, $gstin);
                    SwipezLogger::info(__CLASS__, 'Delete draft Response: ' . json_encode($response));
                    if ($upload_id > 0) {
                        $this->common->genericupdate('iris_r1_upload', 'status', 1, 'upload_id', $upload_id);
                        $this->session->set("successMessage", $response['message']);
                        header('Location: /merchant/gst/gstr1upload');
                        die();
                    }
                    $this->smarty->assign("success", $response['message']);
                }
                $response = $irisapi->getGSTRSummary($period, $gstin);
                SwipezLogger::info(__CLASS__, 'Get GSTR Summary Response: ' . json_encode($response));
                $this->smarty->assign("summary", $response['response']);
            }
            $this->setdatemonth();
            $list = array();
            $this->view->selectedMenu = array(12, 52, 91);
            $this->smarty->assign("gst_list", $data);
            if ($link != null) {
                $this->smarty->assign("link", $link);
            } else {
                $this->smarty->assign("link", '');
            }
            $this->smarty->assign("title", "Save GSTR1");
            $this->view->title = "Save GSTR1";
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/draft2.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function draft3b($link, $type = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            $id = $this->encrypt->decode($link);
            $det = $this->common->getSingleValue('iris_3b_upload', 'bulk_upload_id', $id);
            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            if ($type == 'create') {
                $response = $irisapi->createGSTR($det['fp'], $det['gstin'], 'GSTR3B');
                if (isset($response['response']['id'])) {
                    $this->common->genericupdate('iris_3b_upload', 'draft_id', $response['response']['id'], 'bulk_upload_id', $id);
                    $this->common->genericupdate('iris_3b_upload', 'status', 4, 'bulk_upload_id', $id);
                }
                SwipezLogger::info(__CLASS__, 'Create 3B draft Response: ' . json_encode($response));
                $this->session->set("successMessage", $response['message']);
                header('Location: /merchant/gst/draft3b/' . $link);
                die();
            } else if ($type == 'delete') {
                $response = $irisapi->deleteGSTR($det['fp'], $det['gstin'], 'GSTR3B');
                $this->common->genericupdate('iris_3b_upload', 'draft_id', '', 'bulk_upload_id', $id);
                $this->common->genericupdate('iris_3b_upload', 'status', 1, 'bulk_upload_id', $id);
                SwipezLogger::info(__CLASS__, 'Delete 3B draft Response: ' . json_encode($response));
                $this->session->set("successMessage", $response['message']);
                header('Location: /merchant/gst/gst3bupload');
                die();
            } else {
                if ($det['draft_id'] == '') {
                    $this->smarty->assign("summary", array());
                } else {
                    $response = $irisapi->get3BInvoice($det['fp'], $det['gstin']);
                    SwipezLogger::info(__CLASS__, '3b Invoices Response: ' . json_encode($response));

                    $total_unreg_count = 0;
                    $tax_unreg_sum = 0;
                    $igst_unreg_sum = 0;
                    foreach ($response['response'][0]['interSup']['unreg_details'] as $unreg) {
                        $tax_unreg_sum = $tax_unreg_sum + $unreg['txval'];
                        $igst_unreg_sum = $igst_unreg_sum + $unreg['iamt'];
                        $total_unreg_count++;
                    }
                    $response['response'][0]['c_unreg']['t1'] = $total_unreg_count;
                    $response['response'][0]['c_unreg']['t2'] = $tax_unreg_sum;
                    $response['response'][0]['c_unreg']['t3'] = $igst_unreg_sum;
                    $this->smarty->assign("summary", $response['response'][0]);
                }
            }
            $list = array();
            $this->view->selectedMenu = array(12, 51, 89);
            $this->smarty->assign("title", "Save GSTR 3B to GST portal");
            $this->smarty->assign("link", $link);
            $this->smarty->assign("det", $det);
            $this->view->title = "Save GSTR 3B to GST portal";
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/draft3b.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function gstsubmitsave()
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 23);
            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            $response = $irisapi->validateConnection($_POST['gstin']);
            SwipezLogger::info(__CLASS__, 'Validate GSTIN Connection Response: ' . json_encode($response));
            if ($response['response'] == 1) {
                $response = $irisapi->submitGST($_POST['gstin'], $_POST['fp'], 'SAVE', $_POST['frm_type']);
                SwipezLogger::info(__CLASS__, $_POST['frm_type'] . ' Save draft Response: ' . json_encode($response));
                if ($_POST['frm_type'] == 'GSTR3B') {
                    if ($response['status'] == 1) {
                        $this->common->genericupdate('iris_3b_upload', 'status', 5, 'bulk_upload_id', $_POST['bulk_upload_id']);
                    } else {
                        $this->session->set("errorMessage", $response['response']['MESSAGE']);
                    }
                    header('Location: /merchant/gst/gst3bupload');
                    die();
                } else {
                    if ($response['status'] == 1) {
                        if ($_POST['upload_id'] != '') {
                            $upload_id = $this->encrypt->decode($_POST['upload_id']);
                            $this->common->genericupdate('iris_r1_upload', 'status', 6, 'upload_id', $upload_id);
                        }
                        $this->smarty->assign("success", $response['message']);
                    } else {
                        $this->session->set("errorMessage", $response['response']['MESSAGE']);
                    }

                    header('Location: /merchant/gst/gstr1upload');
                    die();
                }
                $this->smarty->assign("success", $response['message']);
                header('Location: /merchant/gst/gstsubmit');
                die();
            } else {
                header('Location: /merchant/gst/gstconnection/' . $_POST['gstin']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function gstsubmit($link = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            if ($link != null) {
                $upload_id = $this->encrypt->decode($link);
                $row = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $upload_id);
                $_POST['month'] = $row['fp'] . $row['gstin'];
            } else {
                $data = $this->model->getGSTInvoiceDetails($this->merchant_id);
            }
            if (!empty($_POST)) {
                $this->hasRole(2, 23);
                require_once UTIL . 'IRISAPI.php';
                $irisapi = new IRISAPI($this->iris_data);
                $period = substr($_POST['month'], 0, 6);
                $gstin = substr($_POST['month'], 6);
                $this->smarty->assign("month", $period);
                $this->smarty->assign("gstin", $gstin);
                $response = $irisapi->validateConnection($gstin);
                SwipezLogger::info(__CLASS__, 'Validate GSTIN Connection Response: ' . json_encode($response));
                if ($response['response'] == 1) {
                    if (isset($_POST['submit_gst'])) {
                        $response = $irisapi->submitGST($_POST['gstn'], $_POST['fp'], 'SUBMIT');
                        SwipezLogger::info(__CLASS__, 'GSTN Submit Response: ' . json_encode($response));
                        $gstin = $_POST['gstn'];
                        $period = $_POST['fp'];
                        if ($_POST['upload_id'] != '') {
                            $link = $_POST['upload_id'];
                            $upload_id = $this->encrypt->decode($_POST['upload_id']);
                            if ($_POST['form_type'] == 'GSTR1') {
                                $this->common->genericupdate('iris_r1_upload', 'status', 7, 'upload_id', $upload_id);
                            } else {
                                $this->common->genericupdate('iris_3b_upload', 'status', 7, 'bulk_upload_id', $upload_id);
                            }
                        }
                    }
                    $response = $irisapi->getGSTSubmitSTatus($gstin, $period);
                    SwipezLogger::info(__CLASS__, 'Submit GSTN status Response: ' . json_encode($response));
                    if ($response['response']['subStatus'] == 1) {
                        $array['filingPeriod'] = $period;
                        $array['returnType'] = 'GSTR1';
                        $array['gstin'] = $gstin;
                        $response = $irisapi->getGSTData($gstin, $period, 'RETSUM', 'GSTR1');
                        if (isset($response['response']['sec_sum'])) {
                            $this->smarty->assign("summary", $response['response']['sec_sum']);
                        }
                        SwipezLogger::info(__CLASS__, 'Submit GSTN Data Response: ' . json_encode($response));
                        $this->smarty->assign("success", $response['message']);
                    } else {
                        $response = $irisapi->submitGSTChecklist($period, $gstin);
                        if ($response['status'] == 1) {
                            $this->smarty->assign("submit_enable", 1);
                            $this->smarty->assign("message", $response['message']);
                        }
                        $this->smarty->assign("checklist", $response['response']);
                        SwipezLogger::info(__CLASS__, 'GSTN checklist Response: ' . json_encode($response));
                    }
                } else {
                    header('Location: /merchant/gst/gstconnection/' . $gstin);
                    die();
                }
            }

            $list = array();
            $this->view->selectedMenu = array(12, 52, 92);
            $this->smarty->assign("link", $link);
            $this->smarty->assign("data", $data);
            $this->smarty->assign("title", "Submit GSTR1");
            $this->view->title = "Submit GSTR1";
            $this->view->datatablejs = 'table-no-export';
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/gstsubmit.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function filingstatus()
    {
        $this->validatePackage();
        if ($_POST['gst_number']) {
            require_once UTIL . 'IRISAPI.php';
            $iris = new IRISAPI($this->iris_data);
            $GSTInfo = $iris->getGSTInfo($_POST['gst_number']);
            $info = $GSTInfo['response'];
            if ($info['status_code'] == 0) {
                $this->smarty->assign("error", $info['error']['message']);
            } else {
                $returnstatus = $iris->getGSTReturnStatus($_POST['gst_number']);
                $return_list = $returnstatus['response']['data']['EFiledlist'];
                foreach ($return_list as $row) {
                    if ($row['rtntype'] == 'GSTR3B') {
                        $lastfileperiod = $row['ret_prd'];
                        break;
                    }
                }

                $day = date('d');
                if ($day > 23) {
                    $fperiod = date("mY", strtotime("-1 months"));
                } else {
                    $fperiod = date("mY", strtotime("-2 months"));
                }
                if (strtotime($lastfileperiod) < strtotime($fperiod)) {
                    if ($lastfileperiod != '') {
                        $lastfileperiod = substr($lastfileperiod, 2) . '-' . substr($lastfileperiod, 0, 2) . '-01';
                        $fperiod = substr($fperiod, 2) . '-' . substr($fperiod, 0, 2) . '-01';
                        $datetime1 = new DateTime($lastfileperiod);
                        $datetime2 = new DateTime($fperiod);
                        $interval = $datetime1->diff($datetime2);
                        $month = $interval->format('%m');
                        if ($month > 1) {
                            $mtext = ' Months';
                        } else {
                            $mtext = ' Month';
                        }
                        $filereturn_error = 'Tax return not filed for ' . $month . $mtext;
                        $this->smarty->assign("filereturn_error", $filereturn_error);
                    } else {
                        $filereturn_error = 'Tax return never filed';
                        $this->smarty->assign("filereturn_error", $filereturn_error);
                    }
                } else {
                    $filereturn_success = 'Tax Return filing upto date';
                    $this->smarty->assign("filereturn_success", $filereturn_success);
                }
                $info['nature'] = implode(',', $info['nature']);
                $this->smarty->assign("det", $info);
                $this->smarty->assign("return_list", $return_list);
            }
            $this->smarty->assign("gst_number", $_POST['gst_number']);
        }
        $this->view->datatablejs = 'table-no-export-class';
        $this->view->title = 'Filing status';
        $this->smarty->assign('title', $this->view->title);

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => 'Filling status', 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->hide_first_col = true;
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/filing_status.tpl');
        $this->view->render('footer/list');
    }

    function updatesaved($ref, $id)
    {
        try {
            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            $json = array();
            $invoices = array();
            $count = 0;
            $row = 1;
            $rowdata = $this->common->getSingleValue('iris_invoice', 'id', $id, 1);
            $invdetail = $this->common->getListValue('iris_invoice_detail', 'invoice_id', $id, 1);
            $det = $irisapi->getInvoiceDetail($ref, $rowdata['gstin']);
            $fp = $det['response']['fp'];
            $row = 1;
            $invoices['invTyp'] = $rowdata['invTyp'];
            $invoices['splyTy'] = $rowdata['splyTy'];
            $invoices['dst'] = $rowdata['dst'];
            $invoices['refnum'] = $rowdata['refnum'];
            $date = new DateTime($rowdata['pdt']);
            $invoices['pdt'] = $date->format('d-m-Y');
            $invoices['ctpy'] = $rowdata['ctpy'];
            if ($rowdata['invTyp'] == 'B2CS') {
                $invoices['ctin'] = null;
                $invoices['cname'] = null;
            } else {
                $invoices['ctin'] = $rowdata['ctin'];
                $invoices['cname'] = $rowdata['cname'];
            }

            if ($rowdata['dty'] == 'RI') {
                $rowdata['ntNum'] = null;
                $rowdata['ntDt'] = null;
            } else {
                $date = new DateTime($rowdata['ntDt']);
                $rowdata['ntDt'] = $date->format('d-m-Y');
            }
            $invoices['ntNum'] = $rowdata['ntNum'];
            $invoices['ntDt'] = $rowdata['ntDt'];
            if ($rowdata['inum'] == '') {
                $invoices['inum'] = null;
                $invoices['idt'] = null;
            } else {
                $invoices['inum'] = trim($rowdata['inum']);
                $date = new DateTime($rowdata['idt']);
                $invoices['idt'] = $date->format('d-m-Y');
            }

            $invoices['val'] = $rowdata['val'];
            $invoices['pos'] = $rowdata['pos'];
            $invoices['rchrg'] = $rowdata['rchrg'];
            $invoices['fy'] = $rowdata['fy'];
            $invoices['dty'] = $rowdata['dty'];
            $invoices['rsn'] = $rowdata['rsn'];
            $invoices['pgst'] = $rowdata['pgst'];
            $invoices['prs'] = $rowdata['prs'];
            $invoices['odnum'] = $rowdata['odnum'];
            $invoices['gen2'] = $rowdata['gen2'];
            $invoices['gen7'] = $rowdata['gen7'];
            $invoices['gen8'] = $rowdata['gen8'];
            $invoices['gen10'] = $rowdata['gen10'];
            $invoices['gen11'] = $rowdata['gen11'];
            $invoices['gen12'] = $rowdata['gen12'];
            $invoices['gen13'] = $rowdata['gen13'];
            $is_cgst = 0;
            foreach ($invdetail as $invdet) {
                if ($invdet['txval'] > 0) {
                    $itemDetails['num'] = $row;
                    $itemDetails['sval'] = $invdet['sval'];
                    $itemDetails['ty'] = $invdet['ty'];
                    $itemDetails['hsnSc'] = $invdet['hsnSc'];
                    if (strlen($invdet['desc']) > 30) {
                        $invdet['desc'] = substr($invdet['desc'], 0, 28) . '..';
                    }
                    $itemDetails['desc'] = $invdet['desc'];
                    $itemDetails['uqc'] = $invdet['uqc'];
                    $itemDetails['qty'] = $invdet['qty'];
                    $itemDetails['txval'] = $invdet['txval'];
                    $itemDetails['irt'] = ($invdet['irt'] > 0) ? $invdet['irt'] : null;
                    $itemDetails['iamt'] = ($invdet['iamt'] > 0) ? $invdet['iamt'] : null;
                    $itemDetails['crt'] = ($invdet['crt'] > 0) ? $invdet['crt'] : null;
                    $itemDetails['camt'] = ($invdet['camt'] > 0) ? $invdet['camt'] : null;
                    $itemDetails['srt'] = ($invdet['srt'] > 0) ? $invdet['srt'] : null;
                    $itemDetails['samt'] = ($invdet['samt'] > 0) ? $invdet['samt'] : null;
                    $itemDetails['csrt'] = ($invdet['csrt'] > 0) ? $invdet['csrt'] : null;
                    $itemDetails['csamt'] = ($invdet['csamt'] > 0) ? $invdet['csamt'] : null;
                    $itemDetails['txp'] = $invdet['txp'];
                    $itemDetails['disc'] = $invdet['disc'];
                    $itemDetails['adval'] = $invdet['adval'];
                    $itemDetails['rt'] = $invdet['rt'];
                    if ($itemDetails['camt'] > 0) {
                        $is_cgst = 1;
                    }
                    $invoices['itemDetails'][] = $itemDetails;
                    $row++;
                }
            }
            if ($is_cgst == 1) {
                $invoices['splyTy'] = 'INTRA';
            }
            $invoices['gstin'] = $rowdata['gstin'];
            $invoices['fp'] = $fp;
            $invoices['ft'] = 'GSTR1';
            $json['invoices'][] = $invoices;
            SwipezLogger::info(__CLASS__, 'Request ' . json_encode($json));
            $res = $irisapi->saveInvoice($rowdata['gstin'], json_encode($json));
            SwipezLogger::info(__CLASS__, 'Response ' . json_encode($res));
            if ($res['response']['status'] == 'SUCCESS') {
                $this->session->set("successMessage", 'Invoice saved successfully.');
                header('Location: /merchant/gst/gstr1upload');
            } else {
                $this->session->set("errorMessage", $res['response']['message']);
                header('Location: /merchant/gst/gstr1upload');
            }

            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function deleteinvoice($link, $id)
    {
        try {
            $this->hasRole(3, 23);
            require_once UTIL . 'IRISAPI.php';
            $irisapi = new IRISAPI($this->iris_data);
            $fp = substr($id, 0, 6);
            $gst = substr($id, 6, 15);
            $invoice_id = substr($id, 21);
            $det = $irisapi->getInvoiceDetail($invoice_id, $gst);
            $this->common->genericupdate('iris_invoice', 'is_active', 0, 'refnum', $det['response']['refnum']);
            $fp = $det['response']['fp'];
            $array['fp'] = $fp;
            $array['ft'] = 'GSTR1';
            $array['gstin'] = $gst;
            $array['idList'][] = $invoice_id;
            $array['sectionType'] = 'Regular Transactions';
            $this->session->set('post_month', $post_month);
            $invoice = $irisapi->deleteInvoice(json_encode($array));
            SwipezLogger::info(__CLASS__, 'Delete Invoice Response: ' . json_encode($invoice));
            $this->session->set("successMessage", 'Invoice deleted successfully.');
            header('Location: /merchant/gst/viewlist/gstr1/' . $link);
            die();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E999]Error while listing vendor Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function updateinvoice($link, $ref = '')
    {
        $id = $this->encrypt->decode($link);
        $detail = $this->common->getSingleValue('iris_invoice', 'id', $id);
        $particular = $this->common->getListValue('iris_invoice_detail', 'invoice_id', $id, 1);
        $this->smarty->assign("det", $detail);
        $this->smarty->assign("particular", $particular);
        $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
        foreach ($gst_state_code as $gsc) {
            if (strlen($gsc['config_key']) == 1) {
                $gsc['config_key'] = '0' . $gsc['config_key'];
            }
            $state_array[$gsc['config_key']] = $gsc['config_value'];
        }
        $this->smarty->assign("state", $state_array);
        $this->smarty->assign("link", $link);
        $this->smarty->assign("ref", $ref);
        $this->view->title = 'Invoice update';
        $this->view->js = ['expense'];
        $this->view->footer_code = '<script>calculateexpensecost();</script>';
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/update_invoice.tpl');
        $this->view->render('footer/list');
    }

    function viewinvoice($link, $ref = '')
    {

        $id = $this->encrypt->decode($link);
        $detail = $this->common->getSingleValue('iris_invoice', 'id', $id);
        if (empty($detail)) {
            $this->setInvalidLinkError();
        }
        $particular = $this->common->getListValue('iris_invoice_detail', 'invoice_id', $id, 1);
        $this->smarty->assign("det", $detail);
        $this->smarty->assign("particular", $particular);
        $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
        foreach ($gst_state_code as $gsc) {
            if (strlen($gsc['config_key']) == 1) {
                $gsc['config_key'] = '0' . $gsc['config_key'];
            }
            $state_array[$gsc['config_key']] = $gsc['config_value'];
        }
        $this->smarty->assign("state", $state_array);
        $this->smarty->assign("ref", $ref);
        $this->smarty->assign("link", $link);
        $this->view->title = 'Invoice update';
        $this->view->js = ['expense'];
        $this->view->footer_code = '<script>calculateexpensecost();</script>';
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/invoice.tpl');
        $this->view->render('footer/list');
    }

    function showinvoice($type, $id)
    {
        $this->hasRole(1, 23);
        require_once UTIL . 'IRISAPI.php';
        $irisapi = new IRISAPI($this->iris_data);
        $gst = substr($id, 0, 15);
        $invoice_id = substr($id, 15);
        $invoice = $irisapi->getInvoiceDetail($invoice_id, $gst);
        $inv_id = $this->common->getRowValue('id', 'iris_invoice', 'merchant_id', $this->merchant_id, 0, " and refnum='" . $invoice['response']['refnum'] . "'");
        $link = $this->encrypt->encode($inv_id);
        if ($type == 'view') {
            $this->viewinvoice($link, $invoice_id);
        } else {
            if (isset($invoice['response']['errorDataModel'][$type])) {
                $errors[] = $invoice['response']['errorDataModel'][$type];
            }
            foreach ($invoice['response']['itemDetails'] as $item) {
                if (isset($item['errorDataModel'][$type])) {
                    $errors[] = $item['errorDataModel'][$type];
                }
            }
            $this->smarty->assign("errors", $errors);
            $file = 'allerrors';
            $this->smarty->assign("id", $id);
            $this->smarty->assign("type", $type);

            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/gst/' . $file . '.tpl');
            $this->view->render('footer/nonfooter');
        }
    }

    /**
     * Bulk upload gst invoice
     */ function bulkupload($subtype = null)
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 23);
            $list = $this->model->getBulkuploadList($this->merchant_id, 7);
            foreach ($list as $int => $item) {
                $list[$int]['bulk_id'] = $this->encrypt->encode($item['bulk_upload_id']);
                $list[$int]['created_at'] = $this->generic->formatTimeString($item['created_date']);
                if ($subtype == 'stock' && $item['sub_type'] != 'Stock') {
                    unset($list[$int]);
                }
                if ($subtype == null && $item['sub_type'] == 'Stock') {
                    unset($list[$int]);
                }
            }

            $this->view->hide_first_col = true;
            $this->smarty->assign("sub_type", $subtype);
            $this->smarty->assign("type_", '7');
            $this->smarty->assign("bulklist", $list);
            $this->view->datatablejs = 'table-small-no-export';
            $this->view->selectedMenu = array(12, 50, 125);
            $this->view->title = 'Upload your invoices';
            $this->smarty->assign('title', 'Bulk upload your ' . $subtype . ' invoices to Swipez cloud');
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'GST', 'url' => ''),
                array('title' => 'GST invoice list', 'url' => '/merchant/gst/listinvoices'),
                array('title' => 'Bulk upload', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['bulkupload'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/gst/gstupload.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EC001]Error while vendor bulkupload Error: for user id [' . $this->user_id . '] ' . $e->getMessage());
        }
    }

    public function upload($type = null)
    {
        try {
            if ($type == null) {
                $type = $_POST['type'];
            }
            $this->hasRole(2, 23);
            $merchant_id = $this->session->get('merchant_id');
            if (isset($_FILES["fileupload"])) {
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';
                $validator = new Uploadvalidator($this->model);
                if ($_POST['type'] == 'Amazon' || $_POST['type'] == 'Stock') {
                    $validator->validateCsvUpload();
                } else {
                    $validator->validateExcelUpload();
                }
                $hasErrors = $validator->fetchErrors();
                if ($hasErrors == false) {
                    if (isset($_POST['bulk_id'])) {
                        require_once(MODEL . 'merchant/BulkuploadModel.php');
                        $bulkupload = new BulkuploadModel();
                        $merchant_id = $this->session->get('merchant_id');
                        $bulk_id = $this->encrypt->decode($_POST['bulk_id']);
                        $detail = $bulkupload->getBulkuploaddetail($merchant_id, $bulk_id);
                        $bulkupload->updateBulkUploadStatus($bulk_id, 7);
                        $folder = ($detail['status'] == 2 || $detail['status'] == 3) ? 'staging' : 'error';
                        $bulkupload->moveExcelFile($merchant_id, $folder, $detail['system_filename']);
                    }
                    if ($type == 'Amazon') {
                        $this->amazonGST($_FILES["fileupload"]);
                    } elseif ($type == 'Flipkart') {
                        $this->flipkartGST($_FILES["fileupload"]);
                    } elseif ($type == 'Stock') {
                        $this->stocktransferGST($_FILES["fileupload"]);
                    }
                } else {
                    $this->smarty->assign("hasErrors", $hasErrors);
                    $this->bulkupload($_POST['type']);
                }
            } else {
                header('Location: /merchant/vendor/bulkupload/' . $_POST['type']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E270]Error while bulk upload submit Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bulkerror($bulk_upload_id, $type = null)
    {
        try {
            $this->hasRole(1, 23);
            $bulk_id = $this->encrypt->decode($bulk_upload_id);
            $merchant_id = $this->session->get('merchant_id');
            if ($type == 2) {
                $detail = $this->common->getSingleValue('iris_r1_upload', 'upload_id', $bulk_id);
                $errorfile = 'gst/gstr1error';
            } else {
                require_once(MODEL . 'merchant/BulkuploadModel.php');
                $bulkupload = new BulkuploadModel();
                $detail = $bulkupload->getBulkuploaddetail($merchant_id, $bulk_id);
                $errorfile = 'bulkupload/allerrors';
            }
            if ($detail['error_json'] != '') {
                $errors = json_decode($detail['error_json'], true);
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('header/guest');
                $this->smarty->display(VIEW . 'merchant/' . $errorfile . '.tpl');
                $this->view->render('footer/nonfooter');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E272]Error while bulk upload error Error:for merchant [' . $merchant_id . '] and  bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function amazonGST($inputFile, $bulk_upload_id = NULL)
    {
        try {
            $this->hasRole(2, 23);
            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }
            $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            foreach ($gst_state_code as $gsc) {
                if (strlen($gsc['config_key']) == 1) {
                    $gsc['config_key'] = '0' . $gsc['config_key'];
                }
                $state_array[strtolower($gsc['config_value'])] = $gsc['config_key'];
            }
            $state_validation = array();
            $state_errors = array();
            $errors = array();
            $is_upload = TRUE;
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            $merchant_id = $this->session->get('merchant_id');
            $getcolumnvalue = array();
            if (empty($errors)) {
                $row = 1;
                $csvfile = fopen($File, "r");
                while (!feof($csvfile)) {
                    $mem_ = fgetcsv($csvfile);
                    if ($row > 1) {
                        if ($mem_[0] != '') {
                            $post_row = array();
                            $int = 0;
                            $post_row['seller_gstin'] = (string) $mem_[$int];
                            $int++;
                            $post_row['invoice_number'] = (string) $mem_[$int];
                            $int++;
                            $post_row['invoice_date'] = $this->getexceldate($mem_[$int]);
                            $int = $int + 7;
                            $post_row['qty'] = (string) $mem_[$int];
                            $int++;
                            $post_row['description'] = (string) $mem_[$int];
                            $int++;
                            $int++;
                            $post_row['sac'] = (string) $mem_[$int];
                            $int = $int + 4;
                            $post_row['bill_from'] = (string) $mem_[$int];
                            if ($post_row['bill_from'] == '') {
                                $post_row['bill_from'] = (string) $mem_[$int + 4];
                            }
                            $int = $int + 8;
                            $post_row['ship_to'] = (string) $mem_[$int];
                            $valid = $this->model->validateGSTstate($post_row['ship_to'], $state_array);
                            if ($valid == 0) {
                                $state_validation[$post_row['ship_to']][] = $row + 1;
                                $state_errors[] = array('ship_to' => array('Ship to', 'Invalid state name ' . $post_row['ship_to']), 'row' => $row + 1);
                            }
                            $int = $int + 3;
                            $post_row['invoice_amount'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['exclusive_tax'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['total_tax_amount'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['cgst_rate'] = (string) $mem_[$int] * 100;
                            $int++;
                            $post_row['sgst_rate'] = (string) $mem_[$int] * 100;
                            $int++;
                            $post_row['utgst_rate'] = (string) $mem_[$int] * 100;
                            $int++;
                            $post_row['igst_rate'] = (string) $mem_[$int] * 100;
                            $int = $int + 4;
                            $post_row['cgst_tax'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['sgst_tax'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['igst_tax'] = $this->roundAmount((string) $mem_[$int], 2);
                            $int++;
                            $post_row['utgst_tax'] = $this->roundAmount((string) $mem_[$int], 2);
                            if ($post_row['invoice_number'] != '') {
                                $_POSTarray[] = $post_row;
                            }
                        }
                    }
                    $row++;
                }
                fclose($csvfile);
            }
            $rows_count = count($_POSTarray);
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any invoices.';
                }
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';

                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $validator = new Uploadvalidator($this->model);
                        $validator->validateAmazonGST();
                        $hasErrors = $validator->fetchErrors();
                        if (!empty($hasErrors)) {
                            $hasErrors['row'] = $errorrow;
                            $errors[] = $hasErrors;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }

            if (empty($errors) && $bulk_upload_id == NULL) {
                if (empty($state_errors)) {
                    $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 7, 'Amazon');
                    $this->session->set('successMessage', '  Excel sheet uploaded.');
                    header('Location: /merchant/gst/bulkupload');
                    die();
                } else {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, $rows_count, 7, 'Amazon');
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($state_errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                    $this->smarty->assign("validation_values", $state_validation);
                    $this->smarty->assign("state_array", $gst_state_code);
                }
            }
            if ($is_upload == TRUE && $bulk_upload_id == NULL && $bulk_id == null) {
                $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 7, 'Amazon');
                $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                $bulk_id = $this->encrypt->encode($bulk_id);
            }

            if ($bulk_upload_id != NULL) {
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('nonlogoheader');
                $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                $this->view->render('nonfooter');
            } else {
                $this->smarty->assign("haserrors", $errors);
                if ($bulk_id != '') {
                    $this->reupload('Amazon', $bulk_id);
                } else {
                    $this->bulkupload();
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function stocktransferGST($inputFile, $bulk_upload_id = NULL)
    {
        try {
            $this->hasRole(2, 23);
            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }
            $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            foreach ($gst_state_code as $gsc) {
                if (strlen($gsc['config_key']) == 1) {
                    $gsc['config_key'] = '0' . $gsc['config_key'];
                }
                $state_array[strtolower($gsc['config_value'])] = $gsc['config_key'];
            }
            $state_validation = array();
            $state_errors = array();
            $errors = array();
            $is_upload = TRUE;
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            $merchant_id = $this->session->get('merchant_id');
            $getcolumnvalue = array();
            if (empty($errors)) {
                $row = 1;
                $csvfile = fopen($File, "r");
                while (!feof($csvfile)) {
                    $mem_ = fgetcsv($csvfile);
                    if ($row > 1) {
                        if ($mem_[0] != '') {
                            $post_row = array();
                            $post_row['seller_gstin'] = (string) $mem_[32];
                            $post_row['invoice_number'] = (string) $mem_[14];
                            $post_row['invoice_date'] = $this->getexceldate($mem_[15]);
                            $post_row['qty'] = (string) $mem_[19];
                            $post_row['description'] = (string) $mem_[17];
                            $post_row['sac'] = (string) $mem_[20];
                            $post_row['bill_from'] = (string) $mem_[6];
                            $post_row['ship_to'] = (string) $mem_[11];
                            $valid = $this->model->validateGSTstate($post_row['ship_to'], $state_array);
                            if ($valid == 0) {
                                $state_validation[$post_row['ship_to']][] = $row + 1;
                                $state_errors[] = array('ship_to' => array('Ship to', 'Invalid state name ' . $post_row['ship_to']), 'row' => $row + 1);
                            }
                            $post_row['invoice_amount'] = $this->roundAmount((string) $mem_[16], 2);
                            $post_row['exclusive_tax'] = $this->roundAmount((string) $mem_[21], 2);
                            $post_row['cgst_rate'] = (string) $mem_[24] * 100;
                            $post_row['sgst_rate'] = (string) $mem_[24] * 100;
                            $post_row['utgst_rate'] = (string) $mem_[26] * 100;
                            $post_row['igst_rate'] = (string) $mem_[22] * 100;
                            $post_row['cgst_tax'] = $this->roundAmount((string) $mem_[25], 2);
                            $post_row['sgst_tax'] = $this->roundAmount((string) $mem_[25], 2);
                            $post_row['igst_tax'] = $this->roundAmount((string) $mem_[23], 2);
                            $post_row['utgst_tax'] = $this->roundAmount((string) $mem_[27], 2);
                            $post_row['total_tax_amount'] = $this->roundAmount($post_row['cgst_tax'] + $post_row['cgst_tax'] + $post_row['igst_tax'], 2);
                            if ($post_row['invoice_number'] != '') {
                                $_POSTarray[] = $post_row;
                            }
                        }
                    }
                    $row++;
                }
                fclose($csvfile);
            }
            $rows_count = count($_POSTarray);
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any invoices.';
                }
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';

                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $validator = new Uploadvalidator($this->model);
                        $validator->validateAmazonGST();
                        $hasErrors = $validator->fetchErrors();
                        if (!empty($hasErrors)) {
                            $hasErrors['row'] = $errorrow;
                            $errors[] = $hasErrors;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }
            if (empty($errors) && $bulk_upload_id == NULL) {
                if (empty($state_errors)) {
                    $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 7, 'Stock');
                    $this->session->set('successMessage', '  Excel sheet uploaded.');
                    header('Location: /merchant/gst/bulkupload/stock');
                    die();
                } else {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, $rows_count, 7, 'Stock');
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($state_errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                    $this->smarty->assign("validation_values", $state_validation);
                    $this->smarty->assign("state_array", $gst_state_code);
                }
            }
            if ($is_upload == TRUE && $bulk_upload_id == NULL && $bulk_id == null) {
                $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 7, 'Stock');
                $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                $bulk_id = $this->encrypt->encode($bulk_id);
            }

            if ($bulk_upload_id != NULL) {
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('nonlogoheader');
                $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                $this->view->render('nonfooter');
            } else {
                $this->smarty->assign("haserrors", $errors);
                if ($bulk_id != '') {
                    $this->reupload('Stock', $bulk_id);
                } else {
                    $this->bulkupload('stock');
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function flipkartGST($inputFile, $bulk_upload_id = NULL)
    {
        try {
            $this->hasRole(2, 23);
            if ($bulk_upload_id == NULL) {
                $File = $inputFile['tmp_name'];
            } else {
                $File = $inputFile;
            }

            $gst_state_code = $this->common->getListValue('config', 'config_type', 'gst_state_code');
            foreach ($gst_state_code as $gsc) {
                if (strlen($gsc['config_key']) == 1) {
                    $gsc['config_key'] = '0' . $gsc['config_key'];
                }
                $state_array[strtolower($gsc['config_value'])] = $gsc['config_key'];
            }
            $state_validation = array();
            $state_errors = array();
            $errors = array();
            $is_upload = TRUE;
            require_once(MODEL . 'merchant/BulkuploadModel.php');
            $bulkupload = new BulkuploadModel();
            $merchant_id = $this->session->get('merchant_id');


            $inputFileType = PHPExcel_IOFactory::identify($File);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($File);
            $subject = $objPHPExcel->getProperties()->getSubject();

            $worksheet = $objPHPExcel->getSheetByName('Sales Report');
            if ($worksheet == null) {
                $errors[0][0] = 'Invalid excel';
                $errors[0][1] = 'Uploaded excel does not contain flipkart sales.';
                $this->smarty->assign("haserrors", $errors);
                $this->bulkupload('Flipkart');
                die();
            }
            $highestColumn = 55; //$worksheet->getHighestColumn(); // e.g 'F'
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumnIndex = 55; //PHPExcel_Cell::columnIndexFromString($highestColumn);
            for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                $val = $cell->getValue();
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                    $val = $cell->getValue();
                    $getcolumnvalue[$rowno][] = $val;
                }
            }

            if (empty($errors)) {
                foreach ($getcolumnvalue as $rowno => $row) {
                    if ($row[50] == '') {
                        $inc = 1;
                    } else {
                        $inc = 0;
                    }
                    if ($row[0] != '') {
                        $post_row = array();
                        switch ($row[8]) {
                            case 'Cancellation':
                                $type = 'C';
                                break;
                            case 'Return':
                                $type = 'R';
                                break;
                            case 'Return Cancellation':
                                $type = 'RC';
                                break;
                            case 'Sale':
                                $type = 'S';
                                break;
                            default:
                                $type = 'S';
                                break;
                        }
                        $post_row['seller_gstin'] = (string) $row[0];
                        $post_row['order_id'] = (string) $row[1];
                        $post_row['description'] = str_replace('"', '', (string) $row[3]);
                        $post_row['sac'] = (string) $row[6];

                        $post_row['qty'] = $row[13 - $inc];

                        $post_row['invoice_number'] = (string) $row[43 - $inc];
                        $post_row['invoice_date'] = $this->getexceldate($row[44 - $inc]);


                        $post_row['bill_from'] = substr($row[0], 0, 2);
                        $post_row['ship_to'] = (string) $row[49 - $inc];
                        $valid = $this->model->validateGSTstate($post_row['ship_to'], $state_array);
                        if ($valid == 0) {
                            $state_validation[$post_row['ship_to']][] = $rowno;
                            $state_errors[] = array('ship_to' => array('Ship to', 'Invalid state name ' . $post_row['ship_to']), 'row' => $rowno);
                        }
                        $post_row['invoice_amount'] = $this->roundAmount((string) $row[45 - $inc], 2);
                        $post_row['exclusive_tax'] = $this->roundAmount((string) $row[23 - $inc], 2);
                        $post_row['cgst_rate'] = (string) $row[32 - $inc];
                        $post_row['sgst_rate'] = (string) $row[32 - $inc];
                        $post_row['utgst_rate'] = 0;
                        $post_row['igst_rate'] = (string) $row[30 - $inc];
                        $post_row['cgst_tax'] = $this->roundAmount((string) $row[33 - $inc], 2);
                        $post_row['sgst_tax'] = $this->roundAmount((string) $row[33 - $inc], 2);
                        $post_row['igst_tax'] = $this->roundAmount((string) $row[31 - $inc], 2);
                        $post_row['utgst_tax'] = 0;
                        $post_row['total_tax_amount'] = $post_row['cgst_tax'] + $post_row['sgst_tax'] + $post_row['igst_tax'];
                        $_POSTarray[] = $post_row;
                    }
                }
            }
            $rows_count = count($_POSTarray);
            $errorrow = 2;
            try {
                if (empty($_POSTarray) && empty($errors)) {
                    $is_upload = FALSE;
                    $errors[0][0] = 'Empty excel';
                    $errors[0][1] = 'Uploaded excel does not contain any invoices.';
                }
                require_once CONTROLLER . 'merchant/Uploadvalidator.php';

                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $validator = new Uploadvalidator($this->model);
                        $validator->validateAmazonGST();
                        $hasErrors = $validator->fetchErrors();
                        if (!empty($hasErrors)) {
                            $hasErrors['row'] = $errorrow;
                            $errors[] = $hasErrors;
                        } else {
                        }
                        $errorrow++;
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                SwipezLogger::error(__CLASS__, '[E275]Error while validating excel Error: for merchant [' . $merchant_id . '] and ' . $e->getMessage());
            }
            if (empty($errors) && $bulk_upload_id == NULL) {

                if (empty($state_errors)) {
                    $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), FALSE, $rows_count, 7, 'Flipkart');
                    $this->session->set('successMessage', '  Excel sheet uploaded.');
                    header('Location: /merchant/gst/bulkupload');
                    die();
                } else {
                    $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, $rows_count, 7, 'Flipkart');
                    $this->common->genericupdate('bulk_upload', 'error_json', json_encode($state_errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                    $bulk_id = $this->encrypt->encode($bulk_id);
                    $this->smarty->assign("validation_values", $state_validation);
                    $this->smarty->assign("state_array", $gst_state_code);
                }
            }

            if ($is_upload == TRUE && $bulk_upload_id == NULL && $bulk_id == null) {
                $bulk_id = $bulkupload->uploadExcel($inputFile, $this->session->get('merchant_id'), TRUE, 0, 7, 'Flipkart');
                $this->common->genericupdate('bulk_upload', 'error_json', json_encode($errors), 'bulk_upload_id', $bulk_id, $this->user_id);
                $bulk_id = $this->encrypt->encode($bulk_id);
            }


            if ($bulk_upload_id != NULL) {
                $this->smarty->assign("bulk_id", $bulk_upload_id);
                $this->smarty->assign("errors", $errors);
                $this->view->render('nonlogoheader');
                $this->smarty->display(VIEW . 'merchant/bulkupload/allerrors.tpl');
                $this->view->render('nonfooter');
            } else {
                $this->smarty->assign("haserrors", $errors);
                if ($bulk_id != '') {
                    $this->reupload('Flipkart', $bulk_id);
                } else {
                    $this->bulkupload();
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E271]Error while uploading excel Error: for merchant [' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function validated()
    {
        $array = array();
        foreach ($_POST['state'] as $k => $val) {
            $array[trim($_POST['state_name'][$k])] = $val;
        }
        $bulk_id = $this->encrypt->decode($_POST['bulk_id']);
        $system_filename = $this->common->getRowValue('system_filename', 'bulk_upload', 'bulk_upload_id', $bulk_id);
        $this->common->genericupdate('bulk_upload', 'status', 2, 'bulk_upload_id', $bulk_id, $this->user_id);
        $this->common->genericupdate('bulk_upload', 'validate_value', json_encode($array), 'bulk_upload_id', $bulk_id, $this->user_id);

        $oldpath = 'uploads/Excel/' . $this->merchant_id . '/error/' . $system_filename;
        $newpath = 'uploads/Excel/' . $this->merchant_id . '/staging/' . $system_filename;

        if ((copy($oldpath, $newpath))) {
        } else {
            $this->logger->error(__CLASS__, '[E274-2]Error while move excel Error: file 1' . $oldpath . '/ file 2' . $newpath);
        }

        $this->session->set('successMessage', '  Excel sheet uploaded.');
        header('Location: /merchant/gst/bulkupload');
        die();
    }

    public function reupload($type, $bulk_id = '')
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 23);
            $merchant_id = $this->session->get('merchant_id');
            if ($bulk_id != '') {
                $this->smarty->assign("type", $type);
                $this->smarty->assign("bulk_id", $bulk_id);
                $this->view->title = 'Re-upload vendor';
                $this->view->selectedMenu = 'bulk_vendor';
                $this->view->canonical = 'merchant/bulkupload/error/';
                $this->view->header_file = ['bulkupload'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/gst/reupload.tpl');
                $this->view->render('footer/bulkupload');
            } else {
                header('Location: /merchant/gst/bulkupload');
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E269]Error while re-upload Error: for merchant [' . $merchant_id . '] and bulk id [' . $bulk_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function reportb2b()
    {
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);
            $invlist = $this->model->getreportb2b($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            $this->smarty->assign("post", $_POST);
            $rows = array();
            foreach ($invlist as $row) {
                if ($row['rate'] == 0) {
                    $row['rate'] = $row['cgstrt'] * 2;
                }
                $row['rate'] = round($row['rate'], 2);
                if ($row['invoice_type'] == 'RI') {
                    $row['invoice_type'] = 'Regular';
                } elseif ($row['invoice_type'] == 'C') {
                    $row['invoice_type'] = 'Credit note';
                    $row['invoice_value'] = '-' . $row['invoice_value'];
                    $row['invoice_value'] = '-' . $row['invoice_value'];
                    $row['taxable_value'] = '-' . $row['taxable_value'];
                    $row['IGST'] = '-' . $row['IGST'];
                    $row['CGST'] = '-' . $row['CGST'];
                    $row['SGST'] = '-' . $row['SGST'];
                } elseif ($row['invoice_type'] == 'D') {
                    $row['invoice_type'] = 'Debit note';
                }
                $row['cess_amount'] = 0;
                $row['total'] = $row['invoice_value'];
                unset($row['cgstrt']);
                $rows[] = $row;
            }
            if (isset($_POST['export'])) {
                $this->common->excelexport('B2B', $rows);
            }
        }

        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }

        $this->view->selectedMenu = array(13);
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $rows);
        $this->smarty->assign("title", "GST B2B");
        $this->view->title = "GST B2B";
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end
        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/rpt_b2b.tpl');
        $this->view->render('footer/list');
    }

    function reportb2clarge()
    {
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);
            $gstlargelimit = $this->common->getRowValue('config_value', 'config', 'config_type', 'gst_large_invoice_amount');
            $invlist = $this->model->getreportb2clarge($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $gstlargelimit);
            $this->smarty->assign("post", $_POST);

            $rows = array();
            foreach ($invlist as $row) {
                if ($row['rate'] == 0) {
                    $row['rate'] = $row['cgstrt'] * 2;
                }
                $row['rate'] = round($row['rate'], 2);
                if ($row['invoice_type'] == 'RI') {
                    $row['invoice_type'] = 'Regular';
                } elseif ($row['invoice_type'] == 'C') {
                    $row['invoice_type'] = 'Credit note';
                } elseif ($row['invoice_type'] == 'D') {
                    $row['invoice_type'] = 'Debit note';
                }
                unset($row['cgstrt']);
                $rows[] = $row;
            }
            if (isset($_POST['export'])) {
                $this->common->excelexport('B2C large', $rows);
            }
        }

        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }

        $this->view->selectedMenu = array(13);
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $rows);
        $this->smarty->assign("title", "GST B2C large");
        $this->view->title = "GST B2C large";
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end
        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/rpt_b2clarge.tpl');
        $this->view->render('footer/list');
    }

    function reportb2csmall()
    {
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);

            $gstlargelimit = $this->common->getRowValue('config_value', 'config', 'config_type', 'gst_large_invoice_amount');


            $salesgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $gstlargelimit, 'RI');
            $crgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $gstlargelimit, 'C');
            $dbgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $gstlargelimit, 'D');
            foreach ($crgrouping as $cr) {
                if (!isset($salesgrouping[$cr['pos']])) {
                    $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                    $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                    $salesgrouping[$cr['pos']]['place_of_supply'] = $cr['place_of_supply'];
                    $salesgrouping[$cr['pos']]['total'] = $cr['total'];
                }
                $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] - $cr['taxable'];
                $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] - $cr['igst'];
                $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] - $cr['cgst'];
                $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] - $cr['sgst'];
                $salesgrouping[$cr['pos']]['total'] = $salesgrouping[$cr['pos']]['total'] - $cr['total'];
            }
            foreach ($dbgrouping as $cr) {
                if (!isset($salesgrouping[$cr['pos']])) {
                    $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                    $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                    $salesgrouping[$cr['pos']]['place_of_supply'] = $cr['place_of_supply'];
                    $salesgrouping[$cr['pos']]['total'] = $cr['total'];
                }
                $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] + $cr['taxable'];
                $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] + $cr['igst'];
                $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] + $cr['cgst'];
                $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] + $cr['sgst'];
                $salesgrouping[$cr['pos']]['total'] = $salesgrouping[$cr['pos']]['total'] + $cr['total'];
            }
            $this->smarty->assign("post", $_POST);
            $rows = array();
            ksort($salesgrouping);
            foreach ($salesgrouping as $row) {
                unset($row['pos']);
                $rows[] = $row;
            }
            if (isset($_POST['export'])) {
                $this->common->excelexport('B2C small', $rows);
            }
        }

        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }

        $this->view->selectedMenu = array(13);
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $rows);
        $this->smarty->assign("title", "GST B2C small");
        $this->view->title = "GST B2C small";

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => 'GST invoice list', 'url' => '/merchant/gst/listinvoices'),
            array('title' => 'Bulk upload', 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/rpt_b2csmall.tpl');
        $this->view->render('footer/list');
    }

    function reportstatesummary()
    {
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);

            $salesgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 0, 'RI');
            $crgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 0, 'C');
            $dbgrouping = $this->model->getreportb2csmall($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 0, 'D');
            foreach ($crgrouping as $cr) {
                if (!isset($salesgrouping[$cr['pos']])) {
                    $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                    $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                    $salesgrouping[$cr['pos']]['place_of_supply'] = $cr['place_of_supply'];
                    $salesgrouping[$cr['pos']]['total'] = $cr['total'];
                }
                $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] - $cr['taxable'];
                $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] - $cr['igst'];
                $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] - $cr['cgst'];
                $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] - $cr['sgst'];
            }
            foreach ($dbgrouping as $cr) {
                if (!isset($salesgrouping[$cr['pos']])) {
                    $salesgrouping[$cr['pos']]['gstin'] = $cr['gstin'];
                    $salesgrouping[$cr['pos']]['pos'] = $cr['pos'];
                    $salesgrouping[$cr['pos']]['place_of_supply'] = $cr['place_of_supply'];
                    $salesgrouping[$cr['pos']]['total'] = $cr['total'];
                }
                $salesgrouping[$cr['pos']]['taxable'] = $salesgrouping[$cr['pos']]['taxable'] + $cr['taxable'];
                $salesgrouping[$cr['pos']]['igst'] = $salesgrouping[$cr['pos']]['igst'] + $cr['igst'];
                $salesgrouping[$cr['pos']]['cgst'] = $salesgrouping[$cr['pos']]['cgst'] + $cr['cgst'];
                $salesgrouping[$cr['pos']]['sgst'] = $salesgrouping[$cr['pos']]['sgst'] + $cr['sgst'];
            }
            $this->smarty->assign("post", $_POST);
            $rows = array();
            ksort($salesgrouping);
            foreach ($salesgrouping as $row) {
                unset($row['pos']);
                $rows[] = $row;
            }
            if (isset($_POST['export'])) {
                $this->common->excelexport('State summary', $rows);
            }
        }
        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }

        $this->view->selectedMenu = array(13);
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $rows);
        $this->smarty->assign("title", "GST Total summary");
        $this->view->title = "GST Total summary";

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);

        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/rpt_b2csmall.tpl');
        $this->view->render('footer/list');
    }

    function reporthsnsummary()
    {
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);
            $salesgrouping = $this->model->getreporthsnsummary($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'RI');
            $crgrouping = $this->model->getreporthsnsummary($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'C');
            $dbgrouping = $this->model->getreporthsnsummary($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), 'D');

            foreach ($crgrouping as $key => $cr) {
                if (!isset($salesgrouping[$key])) {
                    $salesgrouping[$key]['hsn'] = $key;
                    $salesgrouping[$key]['description'] = $cr['description'];
                    $salesgrouping[$key]['uqc'] = $cr['uqc'];
                }
                $salesgrouping[$key]['total_quantity'] = $salesgrouping[$key]['total_quantity'] - $cr['total_quantity'];
                $salesgrouping[$key]['total_value'] = $salesgrouping[$key]['total_value'] - $cr['total_value'];
                $salesgrouping[$key]['taxable_value'] = $salesgrouping[$key]['taxable_value'] - $cr['taxable_value'];
                $salesgrouping[$key]['IGST'] = $salesgrouping[$key]['IGST'] - $cr['IGST'];
                $salesgrouping[$key]['CGST'] = $salesgrouping[$key]['CGST'] - $cr['CGST'];
                $salesgrouping[$key]['SGST'] = $salesgrouping[$key]['SGST'] - $cr['SGST'];
            }

            foreach ($dbgrouping as $key => $cr) {
                if (!isset($salesgrouping[$key])) {
                    $salesgrouping[$key]['hsn'] = $key;
                    $salesgrouping[$key]['description'] = $cr['description'];
                    $salesgrouping[$key]['uqc'] = $cr['uqc'];
                }
                $salesgrouping[$key]['total_quantity'] = $salesgrouping[$key]['total_quantity'] + $cr['total_quantity'];
                $salesgrouping[$key]['total_value'] = $salesgrouping[$key]['total_value'] + $cr['total_value'];
                $salesgrouping[$key]['taxable_value'] = $salesgrouping[$key]['taxable_value'] + $cr['taxable_value'];
                $salesgrouping[$key]['IGST'] = $salesgrouping[$key]['IGST'] + $cr['IGST'];
                $salesgrouping[$key]['CGST'] = $salesgrouping[$key]['CGST'] + $cr['CGST'];
                $salesgrouping[$key]['SGST'] = $salesgrouping[$key]['SGST'] + $cr['SGST'];
            }

            $this->smarty->assign("post", $_POST);
            if (isset($_POST['export'])) {
                $this->common->excelexport('HSN summary', $salesgrouping);
            }
        }

        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }

        $this->view->selectedMenu = array(13);
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $salesgrouping);
        $this->smarty->assign("title", "GST HSN summary");
        $this->view->title = "GST HSN summary";
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'GST', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/rpt_hsnsummary.tpl');
        $this->view->render('footer/list');
    }

    function exporttally()
    {
        $this->validatePackage();
        $this->hasRole(1, 23);
        if (isset($_POST['from_date'])) {
            $fromdate = new DateTime($_POST['from_date']);
            $todate = new DateTime($_POST['to_date']);
            $this->smarty->assign("post", $_POST);
            $request_id = $this->model->saveTallyExportRequest($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $_POST['type']);
            TallyExport::dispatch($this->merchant_id, $_POST['gstin'], $fromdate->format('Y-m-d'), $todate->format('Y-m-d'), $_POST['type'], $request_id)->onQueue(ENV('SQS_DOWNLOAD_REPORT'));
            $this->session->set("successMessage", 'Tally report export request has been submited.');
            header('Location: /merchant/gst/exporttally');
            die();
        }

        $this->setdatemonth();
        $data = $this->model->getGSTDetails($this->merchant_id);
        $post_month = $this->session->get('post_month');
        if (isset($post_month)) {
            $_POST['month'] = $this->session->get('post_month');
            $this->session->remove('post_month');
        }
        $request = $this->common->getListValue('tally_export_request', 'merchant_id', $this->merchant_id);
        $request = $this->generic->getEncryptedList($request, 'link', 'id');
        $this->view->selectedMenu = array(13);
        $this->view->hide_first_col = true;
        $this->smarty->assign("data", $data);
        $this->smarty->assign("list", $request);
        $this->smarty->assign("title", "Export tally report");
        $this->view->title = "Export tally report";
        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Settings', 'url' => '/merchant/profile/settings'),
            array('title' => 'Data configuration', 'url' => ''),
            array('title' => $this->view->title, 'url' => '')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end
        $this->view->datatablejs = 'table-no-export';
        $this->view->header_file = ['list'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/gst/export_tally.tpl');
        $this->view->render('footer/list');
    }

    function getexceldate($val)
    {
        try {
            $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($val, 'Y-m-d');
            $excel_date = str_replace('/', '-', $excel_date);
        } catch (Exception $e) {
            $excel_date = (string) $val;
        }

        try {
            $excel_date = str_replace('-', '/', $excel_date);
            $date = new DateTime($excel_date);
        } catch (Exception $e) {
            $excel_date = str_replace('/', '-', $excel_date);
            try {
                $date = new DateTime($excel_date);
            } catch (Exception $e) {
                $value = (string) $val;
            }
        }
        try {
            if (isset($date)) {
                $value = $date->format('Y-m-d');
            }
        } catch (Exception $e) {
            $value = (string) $val;
        }
        return $value;
    }
}
