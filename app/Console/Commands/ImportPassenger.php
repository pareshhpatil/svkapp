<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;

class ImportPassenger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:passenger';

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
            if ($row->bulk_type == 1) {
                $file = Storage::disk('upload')->path($row->file_name);
                $inputFileType = PHPExcel_IOFactory::identify($file);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file);
                $worksheet = $objPHPExcel->getSheet(0);
                $subject = $objPHPExcel->getProperties()->getSubject();
                $highestRow = $worksheet->getHighestRow();
                $rows = [];

                for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                    $val = $worksheet->getCellByColumnAndRow(0, $rowno)->getFormattedValue();
                    $array['project_id'] = $row->project_id;
                    $array['bulk_id'] = $row->id;
                    $array['employee_name'] = $worksheet->getCellByColumnAndRow(0, $rowno)->getFormattedValue();
                    $array['mobile'] = $worksheet->getCellByColumnAndRow(1, $rowno)->getFormattedValue();
                    $array['email'] = $worksheet->getCellByColumnAndRow(2, $rowno)->getFormattedValue();
                    $array['gender'] = $worksheet->getCellByColumnAndRow(3, $rowno)->getFormattedValue();
                    $array['address'] = $worksheet->getCellByColumnAndRow(4, $rowno)->getFormattedValue();
                    $array['location'] = $worksheet->getCellByColumnAndRow(5, $rowno)->getFormattedValue();
                    $array['employee_code'] = $worksheet->getCellByColumnAndRow(6, $rowno)->getFormattedValue();
                    $array['cost_center_code'] = $worksheet->getCellByColumnAndRow(7, $rowno)->getFormattedValue();
                    $rows[] = $array;
                }
                foreach ($rows as $drow) {
                    $model->saveTable('staging_passenger', $drow, $row->created_by);
                }
                $model->updateTable('import', 'id', $row->id, 'status', 3);
            }
        }
    }
}
