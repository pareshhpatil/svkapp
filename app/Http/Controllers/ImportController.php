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
        //dd($data['list']);
        foreach ($data['list'] as $k => $v) {
            $data['list']{
                $k}->bulk_id = Encrypt::encode($v->bulk_upload_id);
        }
        $data['project_id'] = ($project_id != null) ? Encrypt::decode($project_id) : 0;
        $data['datatablejs'] = 'table-no-export';
        $data['hide_first_col'] = 1;
        return view('app/merchant/import/billCode', $data);
    }

    /**
     * @author Paresh
     *
     * Renders form to upload contract particulars
     *
     * @param $contract_id - Encrypted contract id
     *
     * @return void
     */
    public function contract($contract_id = null)
    {

        $data = Helpers::setBladeProperties('Import Contract', [], [14]);
        $data['list'] = $this->importModel->getContractUploadList($this->merchant_id);
        //dd($data['list']);
        foreach ($data['list'] as $k => $v) {
            $data['list']{
                $k}->bulk_id = Encrypt::encode($v->bulk_upload_id);
            $data['list']{
                $k}->contract_id = Encrypt::encode($v->parent_id);
        }
        $data['contract_id'] = ($contract_id != null) ? Encrypt::decode($contract_id) : 0;
        $data['datatablejs'] = 'table-no-export';
        $data['hide_first_col'] = 1;
        return view('app/merchant/import/contract', $data);
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
        $response = $this->validateSheet($file->getPathName(), 'BillCodes');
        if (!is_numeric($response)) {
            return redirect()->back()->withErrors([$response]);
        }
        $merchant_filename = $file->getClientOriginalName();
        $id = $this->importModel->saveBulkuploadRecord($this->merchant_id, 11, $request->project_id, $merchant_filename, $merchant_filename, 0, $response - 1, $this->user_id);
        $encryptedFileName = $id * env('IMPORT_ENC_NUMBER');
        $fileExtension = $file->getClientOriginalExtension();
        $encryptedFileNameExt = $encryptedFileName . '.' . $fileExtension;

        $this->importModel->updateBulkuploadStatus($id, $encryptedFileNameExt, 2);
        Storage::disk('s3_bulkupload')->put($encryptedFileNameExt, file_get_contents($file));

        return redirect()->back()->with('success', "File uploaded. You will be notified via email once the upload is completed.");
    }

    /**
     * @author Paresh
     *
     * Validate and upload contract sheet
     *
     * @param 
     *
     * @return void
     */
    public function uploadContract(Request $request)
    {
        $file = $request->file('fileupload');
        $request->validate([
            'contract_id' => 'required',
            'fileupload' => ['required', new ExcelRule($file)],
        ]);
        //Validate uploaded excel file data
        $response = $this->validateSheet($file->getPathName(), 'Contract');
        if (!is_numeric($response)) {
            return redirect()->back()->withErrors([$response]);
        }
        $merchant_filename = $file->getClientOriginalName();
        $id = $this->importModel->saveBulkuploadRecord($this->merchant_id, 12, $request->contract_id, $merchant_filename, $merchant_filename, 0, $response - 1, $this->user_id);
        $encryptedFileName = $id * env('IMPORT_ENC_NUMBER');
        $fileExtension = $file->getClientOriginalExtension();
        $encryptedFileNameExt = $encryptedFileName . '.' . $fileExtension;

        $this->importModel->updateBulkuploadStatus($id, $encryptedFileNameExt, 2);
        Storage::disk('s3_bulkupload')->put($encryptedFileNameExt, file_get_contents($file));

        return redirect()->back()->with('success', "File uploaded. You will be notified via email once the upload is completed.");
    }

    private function validateSheet($file, $type)
    {
        $inputFileType = PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->getSheet(0);
        $subject = $objPHPExcel->getProperties()->getSubject();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        if ($subject != $type) {
            return 'Invalid sheet please download format sheet and upload again';
        }
        if ($highestRow < 2) {
            return 'Sheet is empty';
        }
        return $highestRow;
    }

    public function approveBillCodes($link)
    {
        $bulk_id = Encrypt::decode($link);
        if (is_numeric($bulk_id)) {
            $valid = $this->importModel->getColumnValue('bulk_upload', 'bulk_upload_id', $bulk_id, 'bulk_upload_id', ['status' => '3', 'type' => '11', 'merchant_id' => $this->merchant_id]);
            if ($valid != false) {
                $this->importModel->approveBillCodes($bulk_id);
                $this->importModel->updateTable('bulk_upload', 'bulk_upload_id', $bulk_id, 'status', '5');
                return redirect()->back()->with('success', "Bill codes have been imported successfully.");
            }
        }
        return redirect()->back()->withErrors(['Invalid request']);
    }

    public function errorImports($link)
    {
        $bulk_id = Encrypt::decode($link);
        if (is_numeric($bulk_id)) {
            $error_json = $this->importModel->getColumnValue('bulk_upload', 'bulk_upload_id', $bulk_id, 'error_json', ['merchant_id' => $this->merchant_id]);
            if ($error_json != false) {

                $data = Helpers::setBladeProperties('Errors in sheet', [], [14]);
                $errors = json_decode($error_json, 1);
                foreach ($errors as $row) {
                    foreach ($row as $key => $val) {
                        $data['errors'][$key] = implode(',', $val);
                    }
                }
                $data['datatablejs'] = 'table-no-export';
                $data['hide_first_col'] = 1;
                return view('app/merchant/import/billCodeErrors', $data);
            }
        }
        return redirect()->back()->withErrors(['Invalid request']);
    }

    public function viewBillCodes($link)
    {
        $bulk_id = Encrypt::decode($link);
        if (is_numeric($bulk_id)) {
            $status = $this->importModel->getColumnValue('bulk_upload', 'bulk_upload_id', $bulk_id, 'status', ['type' => '11', 'merchant_id' => $this->merchant_id]);
            if ($status != false) {
                $title =  'Bill code list';
                $data = Helpers::setBladeProperties($title,  [],  []);
                if ($status == 5 || $status == 9) {
                    $list = $this->importModel->getTableList('csi_code', 'bulk_id', $bulk_id);
                    $data['type'] = 1;
                } else {
                    $list = $this->importModel->getTableList('staging_csi_code', 'bulk_id', $bulk_id);
                    $data['type'] = 2;
                }
                foreach ($list as $ck => $row) {
                    $list[$ck]->encrypted_id = Encrypt::encode($row->id);
                }
                $data['list'] = $list;
                $data['datatablejs'] = 'table-no-export';
                return view('app/merchant/import/listBillCodes', $data);
            }
        }
        return redirect()->back()->withErrors(['Invalid request']);
    }

    public function deleteBillCode($type, $link)
    {
        if ($link) {
            $id = Encrypt::decode($link);
            if ($type == 1) {
                $this->importModel->deleteTableRow('csi_code', 'id', $id, $this->merchant_id, $this->user_id);
            } else {
                $this->importModel->deleteTableRow('staging_csi_code', 'id', $id, $this->merchant_id, $this->user_id);
            }
            return redirect()->back()->with('success', "Record has been deleted");
        } else {
            return redirect()->back()->with('error', "Invalid request");
        }
    }
    public function deleteSheet($link)
    {
        if ($link) {
            $id = Encrypt::decode($link);

            $this->importModel->updateTable('bulk_upload', 'bulk_upload_id', $id, 'status', 6);
            return redirect()->back()->with('success', "Record has been deleted");
        } else {
            return redirect()->back()->with('error', "Invalid request");
        }
    }

    public function downloadImportFile($link)
    {
        $bulk_id = Encrypt::decode($link);
        if (is_numeric($bulk_id)) {
            $system_filename = $this->importModel->getColumnValue('bulk_upload', 'bulk_upload_id', $bulk_id, 'system_filename', ['type' => '11', 'merchant_id' => $this->merchant_id]);
            if ($system_filename != false) {
                if (Storage::disk('s3_bulkupload')->exists($system_filename)) {
                    $file_name = $system_filename;
                } else {
                    $file_name = 'processed/' . $system_filename;
                }
                return redirect(Storage::disk('s3_bulkupload')->temporaryUrl(
                    $file_name,
                    now()->addHour(),
                    ['ResponseContentDisposition' => 'attachment']
                ));
            }
        }
        return redirect()->back()->withErrors(['Invalid request']);
    }


    public function formatBillCode($type)
    {
        if ($type == 'billCode') {
            $column_name[] = 'Bill Code';
            $column_name[] = 'Description';
            $title = 'BillCodes';
        } elseif ($type == 'contract') {
            $column_name[] = 'Bill Code';
            $column_name[] = 'Cost Type';
            $column_name[] = 'Description';
            $column_name[] = 'Scheduled Value';
            $column_name[] = 'Work Retainage %';
            $column_name[] = 'Project Id';
            $column_name[] = 'Cost Code';
            $column_name[] = 'Sub Total Group';
            $column_name[] = 'Bill Code Detail (Yes/No)';
            $title = 'Contract';
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Briq")
            ->setLastModifiedBy("Briq")
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription("Import " . $title);
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
        $objPHPExcel->getActiveSheet()->setTitle($title);
        $int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize], 0, -1))->setAutoSize(true);
            $autosize++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
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
