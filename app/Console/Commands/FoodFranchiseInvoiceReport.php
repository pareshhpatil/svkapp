<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\FranchiseSale;
use App\Model\ParentModel;
use Exception;
use Log;
use PHPExcel;
use PHPExcel_IOFactory;
use App\Libraries\MailWrapper;

class FoodFranchiseInvoiceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'franchise:invoicereport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send franchise monthly GST report to each franchise';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $list = FranchiseSale::getFoodFranchiseList();
            $model = new FranchiseSale();
            foreach ($list as $franchise) {
                $from_date = date("Y-m-02", strtotime("first day of previous month"));
                $to_date = date("Y-m-01");
                $data = $model->getInvoiceReport($franchise->customer_id, $franchise->merchant_id, $from_date, $to_date);
                if (!empty($data)) {
                    $this->saveReportExcel($data);
                }
            }
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }

    public function saveReportExcel($data)
    {
        $column_name[] = 'Invoice number';
        $column_name[] = 'Bill_period';
        $column_name[] = 'Bill date';
        $column_name[] = 'Gross fee percent';
        $column_name[] = 'Waiver Fee percent';
        $column_name[] = 'Net fees percent';
        $column_name[] = 'Gross sale';
        $column_name[] = 'Net sale';
        $column_name[] = 'Gross fee';
        $column_name[] = 'Waiver fee';
        $column_name[] = 'Net_fee';
        $column_name[] = 'Penalty';
        $column_name[] = 'Previous Outstaning';
        $column_name[] = 'CGST';
        $column_name[] = 'SGST';
        $column_name[] = 'Invoice Total';


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("swipez")
            ->setLastModifiedBy("swipez")
            ->setTitle("Invoices")
            ->setDescription("Invoice report");
        $month = date("M Y", strtotime("first day of previous month"));
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Invoice Summary Report for the month of ' . $month);
        $objPHPExcel->getActiveSheet()->setCellValue('A5', $data[0]->first_name . $data[0]->last_name);
        #create array of excel column
        $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column = array();
        foreach ($first as $s) {
            $column[] = $s . '7';
        }
        foreach ($first as $f) {
            foreach ($first as $s) {
                $column[] = $f . $s . '7';
            }
        }
        $int = 0;
        foreach ($column_name as $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $col);
            $int = $int + 1;
        }
        $rint = 8;
        $store_id = '';
        $email = '';
        $name = '';
        $merchant_id = '';
        $model = new FranchiseSale();
        foreach ($data as $row) {
            $row = json_decode(json_encode($row), 1);
            $store_id = $row['customer_code'];
            $email = $row['email'];
            $merchant_id = $row['merchant_id'];
            $name = $row['first_name'] . $row['last_name'];
            $credit_note_summary = $model->getInvoiceCreditNoteSummary($row['customer_id'], $row['merchant_id'], $row['invoice_number']);
            if ($credit_note_summary != false) {
                $row['commision_fee_percent'] = $credit_note_summary->new_gross_comm_percent;
                $row['commision_waiver_percent'] = $credit_note_summary->new_waiver_comm_percent;
                $row['commision_net_percent'] = $credit_note_summary->new_net_comm_percent;
                $row['gross_sale'] = $credit_note_summary->new_gross_bilable_sale;
                $row['net_sale'] = $credit_note_summary->new_net_bilable_sale;
                $row['gross_fee'] = $credit_note_summary->new_gross_comm_amt;
                $row['waiver_fee'] = $credit_note_summary->new_waiver_comm_amt;
                $row['net_fee'] = $credit_note_summary->new_net_comm_amt;
                $row['penalty'] = $credit_note_summary->new_penalty;
                $row['bill_period'] = $credit_note_summary->bill_period;
                $row['basic_amount'] = $row['net_fee'];
                $tax = $row['basic_amount'] * 0.18;
                $row['invoice_total'] = $row['basic_amount'] + $tax;
            }
            $column_id = $model->getCustomerColumnId($row['merchant_id'], 'Store Location');
            $gst = $model->getInvoiceTax($row['payment_request_id']);
            $location = '';
            if ($column_id != false) {
                $location = $model->getCustomerColumnValue($row['customer_id'], $column_id);
                if ($location != '') {
                    $location = ' - ' . $location;
                }
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rint, $row['invoice_number']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rint, $row['bill_period']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rint, $row['bill_date']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rint, $row['commision_fee_percent']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rint, $row['commision_waiver_percent']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rint, $row['commision_net_percent']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rint, $row['gross_sale']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rint, $row['net_sale']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rint, $row['gross_fee']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rint, $row['waiver_fee']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $rint, $row['net_fee']);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rint, $row['previous_due']);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $rint, $row['penalty']);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rint, $gst);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $rint, $gst);
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $rint, $row['invoice_total']);
            $rint++;
        }
        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle('Invoices');
        $int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
            $autosize++;
        }
        $file_name = storage_path('app/public') . '/' . $store_id . '_invoice_report' . time() . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($file_name);
        $month = date("M Y", strtotime("first day of previous month"));
        $pmodel = new ParentModel();
        $company_name = $pmodel->getColumnValue('merchant', 'merchant_id', $merchant_id, 'company_name');
        $mail = new MailWrapper();
        $mail->from_name = $company_name;
        $data = array('attachment' => $file_name, 'attachment_name' => $store_id . '_report.xlsx', 'attachment_type' => 'application/vnd.ms-excel');
        $data['data'] = array('' => 'Dear ' . $name . ',<br><br>Please find in attachment invoice summary for month of ' . $month . '. <br><br><br>Regards,<br>' . $company_name);
        $mail->sendmail($email, 'data', $data, 'Monthly Invoice Summary');
        unlink($file_name);
    }
}
