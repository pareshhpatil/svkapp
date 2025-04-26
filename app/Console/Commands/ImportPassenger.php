<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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

                // Load the spreadsheet using PhpSpreadsheet
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getSheet(0);
                $subject = $spreadsheet->getProperties()->getSubject();
                $highestRow = $worksheet->getHighestRow();

                $rows = [];

                for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                    $array = [
                        'project_id'        => $row->project_id,
                        'bulk_id'           => $row->id,
                        'employee_name'     => $worksheet->getCell([1, $rowno])->getFormattedValue(), // A
                        'mobile'            => $worksheet->getCell([2, $rowno])->getFormattedValue(), // B
                        'email'             => $worksheet->getCell([3, $rowno])->getFormattedValue(), // C
                        'gender'            => $worksheet->getCell([4, $rowno])->getFormattedValue(), // D
                        'address'           => $worksheet->getCell([5, $rowno])->getFormattedValue(), // E
                        'location'          => $worksheet->getCell([6, $rowno])->getFormattedValue(), // F
                        'employee_code'     => $worksheet->getCell([7, $rowno])->getFormattedValue(), // G
                        'cost_center_code'  => $worksheet->getCell([8, $rowno])->getFormattedValue(), // H
                    ];
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
