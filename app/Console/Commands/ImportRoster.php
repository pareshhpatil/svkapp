<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;

class ImportRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:roster';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $model = new MasterModel();
        $data = $model->getTableList('import', 'status', 1);
        foreach ($data as $row) {
            if ($row->bulk_type == 2) {
                $file = Storage::disk('upload')->path($row->file_name);
                $inputFileType = PHPExcel_IOFactory::identify($file);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file);
                $worksheet = $objPHPExcel->getSheet(0);
                $subject = $objPHPExcel->getProperties()->getSubject();
                if ($subject == 'Roster') {
                    $highestRow = $worksheet->getHighestRow();
                    $rows = [];

                    for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                        $array = [];
                        $emp['project_id'] = $row->project_id;
                        $emp['employee_name'] = $worksheet->getCellByColumnAndRow(0, $rowno)->getValue();
                        $emp['mobile'] = $worksheet->getCellByColumnAndRow(1, $rowno)->getValue();
                        $emp['email'] = $worksheet->getCellByColumnAndRow(2, $rowno)->getValue();
                        $emp['gender'] = $worksheet->getCellByColumnAndRow(3, $rowno)->getValue();
                        $emp['address'] = $worksheet->getCellByColumnAndRow(4, $rowno)->getValue();
                        $emp['location'] = $worksheet->getCellByColumnAndRow(5, $rowno)->getValue();
                        $emp['employee_code'] = $worksheet->getCellByColumnAndRow(6, $rowno)->getValue();
                        $emp['cost_center_code'] = $worksheet->getCellByColumnAndRow(7, $rowno)->getValue();
                        $passenger_id = $model->getColumnValue('passenger', 'mobile', $emp['mobile'], 'id');
                        if ($passenger_id == false) {
                            $emp['employee_name'] = str_replace(array("\r", "\n", "'"), "", $emp['employee_name']);
                            $emp['address'] = str_replace(array("\r", "\n", "'"), "", $emp['address']);
                            $emp['location'] = str_replace(array("\r", "\n", "'"), "", $emp['location']);
                            $emp['employee_code'] = str_replace(array("\r", "\n", "'"), "", $emp['employee_code']);
                            $emp['cost_center_code'] = str_replace(array("\r", "\n", "'"), "", $emp['cost_center_code']);
                            $passenger_id = $model->saveTable('passenger', $emp, $row->created_by);
                        }
                        $array['project_id'] = $row->project_id;
                        $array['bulk_id'] = $row->id;
                        $array['passenger_id'] = $passenger_id;
                        $array['type'] = $worksheet->getCellByColumnAndRow(8, $rowno)->getValue();
                        $array['date'] = $worksheet->getCellByColumnAndRow(9, $rowno)->getFormattedValue();
                        $array['shift'] = $worksheet->getCellByColumnAndRow(10, $rowno)->getValue();
                        $array['start_time'] = $worksheet->getCellByColumnAndRow(11, $rowno)->getValue();
                        $array['end_time'] = $worksheet->getCellByColumnAndRow(12, $rowno)->getValue();
                        $array['date'] = $this->sqlDate($array['date']);
                        if ($array['start_time'] == '0.0') {
                            $array['start_time'] = '0:00';
                        }
                        if ($array['end_time'] == '0.0') {
                            $array['end_time'] = '0:00';
                        }

                        if ($array['start_time'] != '') {
                            $array['start_time'] = $worksheet->getCellByColumnAndRow(11, $rowno)->getValue();
                            $UNIX_DATE = ($array['start_time'] - 25569) * 86400;
                            $array['start_time'] = $array['date'] . ' ' . gmdate("H:i:s", $UNIX_DATE);
                        } else {
                            $array['start_time'] = null;
                        }
                        if ($array['end_time'] != '') {
                            $array['end_time'] = $worksheet->getCellByColumnAndRow(12, $rowno)->getValue();
                            $UNIX_DATE = ($array['end_time'] - 25569) * 86400;
                            $array['end_time'] = $array['date'] . ' ' . gmdate("H:i:s", $UNIX_DATE);
                        } else {
                            $array['end_time'] = null;
                        }

                        $rows[] = $array;
                    }
                    foreach ($rows as $drow) {
                        $model->saveTable('staging_roster', $drow, $row->created_by);
                    }
                    $model->updateTable('import', 'id', $row->id, 'status', 3);
                }
            }
        }
    }

    public function sqlDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }
    public function sqlTime($time)
    {
        return date('H:i:s', strtotime($time));
    }
}
