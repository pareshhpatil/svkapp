<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;

class PassengerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $model = null;

    public function __construct()
    {
        $this->model = new MasterModel();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $data['selectedMenu'] = [2, 3];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);

        return view('web.passenger.create', $data);
    }

    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [2, 4];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        return view('web.passenger.list', $data);
    }

    public function changeStatus($type, $id)
    {
        if ($type == 'approve') {
            $this->model->updateTable('import', 'id', $id, 'status', 5);
            $this->model->querylist('INSERT INTO `passenger` (`project_id`, `employee_name`, `gender`, `address`, `location`, `mobile`, `email`, `bulk_id`,  `created_by`, `created_date`, `last_update_by`) select  `project_id`, `employee_name`, `gender`, `address`, `location`, `mobile`, `email`, `bulk_id`,  `created_by`, `created_date`, `last_update_by` from staging_passenger where is_active=1 and bulk_id= ' . $id);
            $message = 'Passengers added successfully';
        } elseif ($type == 'delete') {
            $this->model->updateTable('import', 'id', $id, 'is_active', 0);
            $message = 'Sheet deleted successfully';
        }
        return redirect()->back()->withSuccess($message);
    }

    public function import(Request $request)
    {
        $data['selectedMenu'] = [2, 5];
        $data['menus'] = Session::get('menus');
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1);
        $data['import_list'] = $this->model->getImport();

        return view('web.passenger.import', $data);
    }
    public function importsave(Request $request)
    {
        $file_name = time() . '.xlsx';

        $request->file('file')->storeAs('upload', $file_name);
        $user_id = Session::get('user_id');
        $array['project_id'] = $request->project_id;
        $array['file_name'] = $file_name;
        $array['user_file_name'] = $request->file('file')->getClientOriginalName();
        $this->model->saveTable('import', $array, $user_id);
        return redirect()->back()->withSuccess('File added successfully');
    }
    public function format()
    {

        $column_name[] = 'Name';
        $column_name[] = 'Mobile';
        $column_name[] = 'Email';
        $column_name[] = 'Gender(Male/Female)';
        $column_name[] = 'Address';
        $column_name[] = 'Area';
        $title = 'Passenger';


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("SVK")
            ->setLastModifiedBy("SVK")
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription("Import " . $title);
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
        foreach ($column_name as $col) {
            $objPHPExcel->getActiveSheet()->setCellValue($column[$int] . '1', $col);
            $int = $int + 1;
        }

        $objPHPExcel->getDefaultStyle()->getFont()->setName('verdana')
            ->setSize(10);
        $objPHPExcel->getActiveSheet()->setTitle($title);
        //$int++;
        $autosize = 0;
        while ($autosize < $int) {
            $objPHPExcel->getActiveSheet()->getColumnDimension(substr($column[$autosize] . '1', 0, -1))->setAutoSize(true);
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

    public function ajaxPassenger($project_id = 0,  $bulk_id = 0, $type = 0)
    {
        if ($bulk_id > 0) {
            if ($type > 0) {
                $data['data'] = $this->model->getTableList('staging_passenger', 'bulk_id', $bulk_id);
            } else {
                $data['data'] = $this->model->getTableList('passenger', 'bulk_id', $bulk_id);
            }
        } else {
            if ($project_id > 0) {
                $data['data'] = $this->model->getTableList('passenger', 'project_id', $project_id);
            } else {
                $data['data'] = $this->model->getTableList('passenger', 'is_active', 1);
            }
        }
        return json_encode($data);
    }

    public function save(Request $request)
    {
        $this->user_id = Session::get('user_id');
        foreach ($_POST['group-a'] as $row) {
            $row['project_id'] = $request->project_id;
            $exist = $this->model->getColumnValue('passenger', 'mobile', $row['mobile'], 'id');
            if ($exist == false) {
                $this->model->saveTable('passenger', $row, $this->user_id);
            }
        }

        return redirect()->back()->withSuccess('Passengers added successfully');
    }
}
