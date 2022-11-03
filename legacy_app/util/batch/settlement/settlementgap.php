<?php

include('../config.php');

class SettlementGap {

    public $logger = NULL;
    public $db = NULL;

    function __construct() {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->process();
    }

    public function process() {
        $from_date = date('Y-m-d', strtotime("-60 day"));
        $to_date = date('Y-m-d', strtotime("-10 day"));
        //$from_date = '2017-03-01';
        //$to_date = '2017-07-01';
        $rows = $this->getsettlementGap($from_date, $to_date);
        if (!empty($rows)) {
            foreach ($rows as $val) {
                foreach ($val as $key => $val2) {
                    if (!in_array($key, $hide)) {
                        if ($cnt == 0) {
                            $column_name[] = ucfirst(str_replace('_', ' ', $key));
                        }
                        $values[$cnt][] = $val2;
                    }
                }
                $cnt++;
            }

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("swipez")
                    ->setLastModifiedBy("swipez")
                    ->setTitle("swipez_Settlement_Gap")
                    ->setDescription("Swipez settlement gap");
            #create array of excel column
            $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $column = array();
            foreach ($first as $s) {
                $column[] = $s;
            }
            foreach ($first as $f) {
                foreach ($first as $s) {
                    $column[] = $f . $s;
                }
            }
            $int = 0;
            foreach ($column_name as $val) {
                $objPHPExcel->getActiveSheet()->setCellValue($column[$int] . '1', $val);
                $int = $int + 1;
            }
            $rint = 2;
            foreach ($values as $val) {
                $vint = 0;
                foreach ($val as $vall) {
                    $objPHPExcel->getActiveSheet()->setCellValue($column[$vint] . $rint, $vall);
                    $vint = $vint + 1;
                }
                $rint++;
            }


            $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
                    ->setSize(10);
            $objPHPExcel->getActiveSheet()->setTitle('Swipez');
            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $column[$int - 1] . '1')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'AAAADD')
            )));
            $autosize = 0;
            while ($autosize < $int) {
                $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize] . '1', 0, -1))->setAutoSize(true);
                $autosize++;
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $file_name = TMP_FOLDER . 'settlements_missing_' . date('Ymd', strtotime($from_date)) . '-' . date('Ymd', strtotime($to_date)) . '.xlsx';
            $this->logger->info(__CLASS__, $file_name);
            $objWriter->save($file_name);
            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);
            $emailWrapper = new EmailWrapper();
            $emailWrapper->attachment = $file_name;
            $subject = "Settlements Missing";
            $toEmail_ = 'support@swipez.in';
            $toEmail2_ = 'paresh.patil@opusnet.in';
            $body_message = 'PFA';
            $emailWrapper->sendMail($toEmail_, "", $subject, $body_message, $body_message);
            $emailWrapper->sendMail($toEmail2_, "", $subject, $body_message, $body_message);
            if (isset($file_name)) {
                unlink($file_name);
            }
        }
    }

    /**
     * Process input param given to the script
     *
     */
    function getsettlementGap($from_date, $to_date) {
        try {
            $sql = "call admin_settelement_gap(:from_date,:to_date)";
            $params = array(':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E1]Error while get settlement Gap Error: ' . $e->getMessage());
        }
    }

}

new SettlementGap();
