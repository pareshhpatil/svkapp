<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MasterModel;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    public function create($id = null)
    {
        $data['selectedMenu'] = [2, 3];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['det'] = [];
        $data['type'] = 1;
        if ($id > 0) {
            $data['det'] = $this->model->getTableRow('passenger', 'id', $id);
            $data['type'] = $data['det']->passenger_type;
            if ($data['type'] == 2) {
                $data['selectedMenu'] = [16, 18];
            }
        }

        return view('web.passenger.create', $data);
    }

    public function list(Request $request, $bulk_id = 0, $type = 0)
    {
        $data['selectedMenu'] = [2, 4];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = $bulk_id;
        $data['type'] = $type;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.passenger.list', $data);
    }

    public function escortCreate($id = null)
    {
        $data['selectedMenu'] = [16, 17];
        $data['menus'] = Session::get('menus');
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        $data['det'] = [];
        $data['type'] = 2;
        if ($id > 0) {
            $data['det'] = $this->model->getTableRow('passenger', 'id', $id);
        }

        return view('web.passenger.create', $data);
    }

    public function escortList(Request $request)
    {
        $data['selectedMenu'] = [16, 18];
        $data['menus'] = Session::get('menus');
        $data['bulk_id'] = 0;
        $data['type'] = 2;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        return view('web.passenger.list', $data);
    }

    public function changeStatus($type, $status, $id)
    {
        if ($status == 'approve') {
            $this->model->updateTable('import', 'id', $id, 'status', 5);
            if ($type == 'passenger') {
                $this->model->querylist('INSERT INTO `passenger` (`project_id`, `employee_name`, `gender`, `address`, `location`, `mobile`, `email`, `bulk_id`,  `created_by`, `created_date`, `last_update_by`) select  `project_id`, `employee_name`, `gender`, `address`, `location`, `mobile`, `email`, `bulk_id`,  `created_by`, `created_date`, `last_update_by` from staging_passenger where is_active=1 and bulk_id= ' . $id);
            } else {
                $this->model->querylist('INSERT INTO `roster` (`project_id`,`passenger_id`,`date`,`type`,`start_time`,`end_time`,`shift`,`note`,`status`,`is_active`,`bulk_id`,`created_by`,`created_date`,`last_update_by`) select  `project_id`,`passenger_id`,`date`,`type`,`start_time`,`end_time`,`shift`,`note`,`status`,`is_active`,`bulk_id`,`created_by`,`created_date`,`last_update_by` from staging_roster where is_active=1 and bulk_id= ' . $id);
            }
            $message = ucfirst($type) . 's added successfully';
        } elseif ($status == 'delete') {
            $this->model->updateTable('import', 'id', $id, 'is_active', 0);
            $message = 'Sheet deleted successfully';
        }
        return redirect()->back()->withSuccess($message);
    }

    public function import(Request $request, $type = 'passenger')
    {
        $data['selectedMenu'] = [2, 5];
        $data['menus'] = Session::get('menus');
        $data['type'] = $type;
        $data['project_id'] = (isset($request->project_id) ? $request->project_id : 0);
        $data['project_list'] = $this->model->getTableList('project', 'is_active', 1, 0, Session::get('project_access'));
        if ($type == 'roster') {
            $data['selectedMenu'] = [7, 19];
        }
        $bulk_type = ($type == 'roster') ? 2 : 1;
        $data['import_list'] = $this->model->getImport(Session::get('project_access'), $bulk_type);
        return view('web.passenger.import', $data);
    }
    public function importsave(Request $request, $type = 'passenger')
    {
        $file_name = time() . '.xlsx';
        $request->file('file')->storeAs('upload', $file_name);
        $user_id = Session::get('user_id');
        $array['project_id'] = $request->project_id;
        $array['file_name'] = $file_name;
        $array['bulk_type'] = ($request->bulk_type == 'roster') ? 2 : 1;
        $array['user_file_name'] = $request->file('file')->getClientOriginalName();
        $this->model->saveTable('import', $array, $user_id);
        return redirect()->back()->withSuccess('File added successfully');
    }
    public function format($type = 'passenger')
    {
        $column_name = [
            'Name',
            'Mobile',
            'Email',
            'Gender(Male/Female)',
            'Address',
            'Area',
            'Employee code',
            'Cost center code',
        ];

        if ($type == 'roster') {
            $column_name[] = 'Type (Pickup/Drop)';
            $column_name[] = 'Date';
            $column_name[] = 'Shift';
            $column_name[] = 'Pickup Time';
            $column_name[] = 'In Time';
        }

        $title = ucfirst($type);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator("SVK")
            ->setLastModifiedBy("SVK")
            ->setTitle($title)
            ->setSubject($title)
            ->setDescription("Import " . $title);

        // Create Excel column letters (A-Z, AA-ZZ)
        $column = [];
        $letters = range('A', 'Z');
        foreach ($letters as $l) {
            $column[] = $l;
        }
        foreach ($letters as $f) {
            foreach ($letters as $s) {
                $column[] = $f . $s;
            }
        }

        // Set column headers
        foreach ($column_name as $index => $name) {
            $sheet->setCellValue($column[$index] . '1', $name);
            $sheet->getColumnDimension($column[$index])->setAutoSize(true);
        }

        // Font styling
        $spreadsheet->getDefaultStyle()->getFont()->setName('Verdana')->setSize(10);
        $sheet->setTitle($title);

        // Output to browser
        $filename = $title . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Clean output buffer to prevent corrupt download
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
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
            $passenger_type = ($type == 2) ? 2 : 1;
            if ($project_id > 0) {
                $data['data'] = $this->model->getTableList('passenger', 'project_id', $project_id, 0, [], ['passenger_type' => $passenger_type]);

                if ($type == 3) {
                    $array = [];
                    if (!empty($data['data'])) {
                        foreach ($data['data'] as $row) {
                            $array[$row->id] = $row;
                        }
                    }
                    return json_encode($array);
                }
            } else {
                $data['data'] = $this->model->getTableList('passenger', 'is_active', 1, 0, Session::get('project_access'), ['passenger_type' => $passenger_type]);
            }
        }
        return json_encode($data);
    }

    public function delete($id)
    {
        $this->model->updateTable('passenger', 'id', $id, 'is_active', 0);
    }

    public function save(Request $request)
    {
        $this->user_id = Session::get('user_id');
        foreach ($_POST['group-a'] as $row) {
            $row['project_id'] = $request->project_id;
            $exist = false;
            $row['address'] = str_replace(array("\r", "\n", "'"), '', $row['address']);
            if ($request->passenger_id) {
                $this->model->updateArray('passenger', 'id', $request->passenger_id, $row);
            } else {
                if ($row['mobile'] != '') {
                    $exist = $this->model->getColumnValue('passenger', 'mobile', $row['mobile'], 'id');
                }
                if ($exist == false) {
                    $row['passenger_type'] = $request->type;
                    $this->model->saveTable('passenger', $row, $this->user_id);
                }
            }
        }

        if ($request->passenger_id > 0) {
            return redirect('/passenger/list')->withSuccess('Passenger updated successfully');
        } else {
            return redirect()->back()->withSuccess('Passengers added successfully');
        }
    }
}
