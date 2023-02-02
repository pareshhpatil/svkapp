<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Model\Import;
use App\Libraries\Encrypt;
use Validator;
use Illuminate\Support\Facades\Session;
use Log;
use PHPExcel;
use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\Rules\ExcelRule;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{

    private $importModel = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->importModel = new Import();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * @author Paresh
     *
     * Renders form to upload bill codes
     *
     * @param $project_id - Encrypted project id if merchant come from project list
     *
     * @return void
     */
    public function billCodes($project_id = null)
    {

        $data = Helpers::setBladeProperties('Import Bill codes', [], [14]);
        $data['projectLists'] = $this->importModel->getTableList('project', 'merchant_id', $this->merchant_id);
        $data['list'] = $this->importModel->getBillCodesUploadList($this->merchant_id);
        $data['project_id'] = ($project_id != null) ? Encrypt::decode($project_id) : 0;
        $data['datatablejs'] = 'table-no-export';
        $data['hide_first_col'] = 1;
        return view('app/merchant/import/billCode', $data);
    }

    /**
     * @author Paresh
     *
     * Validate and upload bill code sheet
     *
     * @param 
     *
     * @return void
     */
    public function uploadBillCode(Request $request)
    {
        $file = $request->file('fileupload');
        $request->validate([
            'project_id' => 'required',
            'fileupload' => ['required', new ExcelRule($file)],
        ]);
        //Validate uploaded excel file data
        $response = $this->validateSheet($file->getPathName());
        if (!is_numeric($response)) {
            return redirect()->back()->withErrors([$response]);
        }
        $merchant_filename = $file->getClientOriginalName();
        $id = $this->importModel->saveBulkuploadRecord($this->merchant_id, 11, $request->project_id, $merchant_filename, $merchant_filename, 0, $response-1, $this->user_id);
        $encryptedFileName = $id * env('IMPORT_ENC_NUMBER');
        $fileExtension = $file->getClientOriginalExtension();
        $encryptedFileNameExt = $encryptedFileName . '.' . $fileExtension;

        $this->importModel->updateBulkuploadStatus($id, $encryptedFileNameExt, 2);
        Storage::disk('s3_private')->put($encryptedFileNameExt, file_get_contents($file));

        return redirect()->back()->with('success', "File uploaded. You will be notified via email once the upload is completed.");
    }

    private function validateSheet($file)
    {
        $inputFileType = PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->getSheet(0);
        $subject = $objPHPExcel->getProperties()->getSubject();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        if ($subject != 'billCodes') {
            return 'Invalid sheet please download format sheet and upload again';
        }
        if ($highestRow < 2) {
            return 'Sheet is empty';
        }
        return $highestRow;
    }


    public function formatBillCode()
    {

        $column_name[] = 'Bill Code';
        $column_name[] = 'Description';
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Briq")
            ->setLastModifiedBy("Briq")
            ->setTitle("BillCodes")
            ->setSubject('billCodes')
            ->setDescription("Import bill codes");
        #create array of excel column
        $first = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $column = array();
        foreach ($first as $s) {
            $column[] = $s . '1';
        }
        foreach ($first as $f) {
            foreach ($first as $s) {
                $column[] = $f . $s . '1';
            }
        }
        $int = 0;
        foreach ($column_name as $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int], $col);
            $int = $int + 1;
        }

        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle('BillCodes');
        $int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
            $autosize++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="BillCodes.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        exit;
    }
}
