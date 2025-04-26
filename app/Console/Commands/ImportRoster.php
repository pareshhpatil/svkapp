<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
                $this->importRoster($model, $row);
            } else if ($row->bulk_type == 1) {
                $this->importPassenger($model, $row);
            }
        }
    }


    protected function importPassenger($model, $row)
    {
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


    protected function importRoster($model, $row)
    {
        try {
            $file = Storage::disk('upload')->path($row->file_name);
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $subject = $spreadsheet->getProperties()->getSubject();

            if (strtolower($subject) !== 'roster') {
                return false;
            }

            $headerMap = $this->mapHeaders($worksheet);
            $highestRow = $worksheet->getHighestRow();
            $rows = [];
            Log::error($headerMap);

            for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                $emp = [
                    'project_id' => $row->project_id,
                    'employee_name' => $this->cleanText($this->getCellValue($worksheet, $headerMap['name'], $rowno)),
                    'mobile' => $this->getCellValue($worksheet, $headerMap['mobile'], $rowno),
                    'email' => $this->getCellValue($worksheet, $headerMap['email'], $rowno),
                    'gender' => $this->getCellValue($worksheet, $headerMap['gender'], $rowno),
                    'address' => $this->cleanText($this->getCellValue($worksheet, $headerMap['address'], $rowno)),
                    'location' => $this->cleanText($this->getCellValue($worksheet, $headerMap['area'], $rowno)),
                    'employee_code' => $this->cleanText($this->getCellValue($worksheet, $headerMap['employee code'], $rowno)),
                    'cost_center_code' => $this->cleanText($this->getCellValue($worksheet, $headerMap['cost center code'], $rowno)),
                ];

                $passenger_id = $model->getColumnValue('passenger', 'mobile', $emp['mobile'], 'id');
                if (!$passenger_id) {
                    $passenger_id = $model->saveTable('passenger', $emp, $row->created_by);
                }

                $date = $this->parseDate($this->getCellValue($worksheet, $headerMap['date'], $rowno));
                $startTime = $this->parseExcelTime($this->getCellValue($worksheet, $headerMap['pickup time'], $rowno), $date);
                $endTime = $this->parseExcelTime($this->getCellValue($worksheet, $headerMap['in time'], $rowno), $date);

                $rows[] = [
                    'project_id' => $row->project_id,
                    'bulk_id' => $row->id,
                    'passenger_id' => $passenger_id,
                    'type' => $this->getCellValue($worksheet, $headerMap['type (pickup/drop)'], $rowno),
                    'date' => $date,
                    'shift' => $this->getCellValue($worksheet, $headerMap['shift'], $rowno),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }

            foreach ($rows as $drow) {
                $model->saveTable('staging_roster', $drow, $row->created_by);
            }

            $model->updateTable('import', 'id', $row->id, 'status', 3);
        } catch (\Exception $e) {
            Log::error('Error importing roster file: ' . $row->file_name, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    protected function getCellValue($worksheet, $colIndex, $rowIndex)
    {
        $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
        return $worksheet->getCell("{$colLetter}{$rowIndex}")->getValue();
    }

    protected function mapHeaders($worksheet): array
    {
        $headerMap = [];
        $highestColumn = Coordinate::columnIndexFromString($worksheet->getHighestColumn());

        for ($col = 1; $col <= $highestColumn; $col++) {
            $colLetter = Coordinate::stringFromColumnIndex($col);
            $header = strtolower(trim($worksheet->getCell("{$colLetter}1")->getValue()));
            $headerMap[$header] = $col - 1; // 0-based index
        }

        return $headerMap;
    }

    protected function parseDate($value): ?string
    {
        if (is_numeric($value)) {
            return gmdate('Y-m-d', ($value - 25569) * 86400);
        }
        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    protected function parseExcelTime($value, $date): ?string
    {
        if (empty($value) || $value === '0.0') return null;
        if (is_numeric($value)) {
            return $date . ' ' . gmdate('H:i:s', ($value - 25569) * 86400);
        }
        return $date . ' ' . date('H:i:s', strtotime($value));
    }

    protected function cleanText($text): string
    {
        return trim(str_replace(["\r", "\n", "'"], '', $text));
    }
}
