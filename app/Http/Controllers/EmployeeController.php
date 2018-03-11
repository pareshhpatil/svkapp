<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Employee;
use App\Model\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class EmployeeController extends Controller {

    private $employee_model;
    private $master_model;

    function __construct() {
        parent::__construct();
        $this->validateSession(1);
        $this->employee_model = new Employee();
        $this->master_model = new Master();
    }

    public function absent() {
        $absent_list = $this->employee_model->getAbsentList($this->admin_id);
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $data['title'] = 'Employee Absent';
        $data['list'] = $absent_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['employee_list'] = $employee_list;
        $data['addnew_button'] = 1;
        return view('employee.absent', $data);
    }

    public function overtime() {
        $overtime_list = $this->employee_model->getOverTimeList($this->admin_id);
        $vehicle_list = $this->master_model->getMaster('vehicle', $this->admin_id);
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $data['title'] = 'Employee Overtime';
        $data['list'] = $overtime_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['employee_list'] = $employee_list;
        $data['addnew_button'] = 1;
        return view('employee.overtime', $data);
    }

    public function advance() {
        $advance_list = $this->employee_model->getAdvanceList($this->admin_id);
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);
        $data['title'] = 'Employee Advance';
        $data['list'] = $advance_list;
        $data['employee_list'] = $employee_list;
        $data['addnew_button'] = 1;
        return view('employee.advance', $data);
    }

    public function salarydetail($link) {
        $id = $this->encrypt->decode($link);
        $detail = $this->master_model->getMasterDetail('salary', 'salary_id', $id);
        $employee_detail = $this->master_model->getMasterDetail('employee', 'employee_id', $detail->employee_id);

        if ($detail->advance_id != '')
            $data['advance_list'] = $this->master_model->getWherein('advance', 'advance_id', explode(',', $detail->advance_id));

        if ($detail->absent_id != '')
            $data['absent_list'] = $this->master_model->getWherein('absent', 'absent_id', explode(',', $detail->absent_id));

        if ($detail->overtime_id != '')
           $data['overtime_list'] = $this->master_model->getWherein('overtime', 'ot_id', explode(',', $detail->overtime_id));

        $data['title'] = 'Salary Detail';
        $data['emp_detail'] = $employee_detail;
        $data['det'] = $detail;
        return view('employee.salary_view', $data);
    }

    public function salary() {
        if (isset($_POST['salary_month'])) {
            foreach ($_POST['absent_id'] as $value) {
                $key = array_search($value, $_POST['absent_idint']);
                $this->employee_model->updateAbsentAmount($_POST['absent_amount'][$key], $value, $this->user_id);
            }
            foreach ($_POST['advance_id'] as $value) {
                $this->master_model->updateTableColumn('advance', 'is_adjust', 1, 'advance_id', $value, $this->user_id);
            }
            foreach ($_POST['overtime_id'] as $value) {
                $this->master_model->updateTableColumn('overtime', 'is_used', 1, 'ot_id', $value, $this->user_id);
            }
            $absent_id = (isset($_POST['absent_id'])) ? implode(',', $_POST['absent_id']) : '';
            $overtime_id = (isset($_POST['overtime_id'])) ? implode(',', $_POST['overtime_id']) : '';
            $advance_id = (isset($_POST['advance_id'])) ? implode(',', $_POST['advance_id']) : '';

            $advance_amount = ($_POST['advance_amount'] > 0) ? $_POST['advance_amount'] : 0;
            $overtime_amount = ($_POST['overtime_amount'] > 0) ? $_POST['overtime_amount'] : 0;
            $absent_amount = ($_POST['absent_total_amount'] > 0) ? $_POST['absent_total_amount'] : 0;

            $salary_month = date('Y-m-d', strtotime($_POST['salary_month']));
            $salary_date = date('Y-m-d', strtotime($_POST['date']));
            $this->employee_model->saveSalary($_POST['employee_id'], $salary_month, $salary_date, $_POST['amount'], $absent_amount, $advance_amount, $overtime_amount, $_POST['paid_amount'], $absent_id, $advance_id, $overtime_id, $_POST['remark'], $this->user_id, $this->admin_id);
            $this->setSuccess('Salary has been save successfully');
            header('Location: /admin/employee/salary');
        }


        $salary_list = $this->employee_model->getSalaryList($this->admin_id);
        $int = 0;
        foreach ($salary_list as $item) {
            $link = $this->encrypt->encode($item->{'salary_id'});
            $salary_list[$int]->link = $link;
            $int++;
        }
        $employee_list = $this->master_model->getMaster('employee', $this->admin_id);

        if (isset($_POST['date'])) {
            $data['month'] = $_POST['date'];
            $data['insert'] = 1;
            $absent_list = $this->master_model->getMaster('absent', $_POST['employee_id'], 'employee_id');
            $advance_list = $this->master_model->getMaster('advance', $_POST['employee_id'], 'employee_id');
            $overtime_list = $this->master_model->getMaster('overtime', $_POST['employee_id'], 'employee_id');
            $detail = $this->master_model->getMasterDetail('employee', 'employee_id', $_POST['employee_id']);
            $data['employee_id'] = $_POST['employee_id'];
            $data['absent_list'] = $absent_list;
            $data['advance_list'] = $advance_list;
            $data['overtime_list'] = $overtime_list;
            $data['det'] = $detail;
        } else {
            $data['month'] = '';
            $data['insert'] = 0;
            $data['employee_id'] = 0;
        }
        $data['title'] = 'Employee Salary';
        $data['list'] = $salary_list;
        $data['employee_list'] = $employee_list;
        $data['addnew_button'] = 1;
        return view('employee.salary', $data);
    }

    public function saveabsent(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        $deduct = (isset($request->is_deduct)) ? $request->is_deduct : 0;
        $result = $this->employee_model->saveAbsent($request->vehicle_id, $request->absent_employee_id, $request->replace_employee_id, $date, $request->remark, $deduct, $this->user_id, $this->admin_id);
        $this->setSuccess('Absent entry has been save successfully');
        header('Location: /admin/employee/absent');
        exit;
    }

    public function saveovertime(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        $amount = ($request->amount > 0) ? $request->amount : 0;
        $result = $this->employee_model->saveOverTime($request->vehicle_id, $request->over_employee_id, $request->replace_employee_id, $date, $amount, $request->remark, $this->user_id, $this->admin_id);
        $this->setSuccess('Overtime entry has been save successfully');
        header('Location: /admin/employee/overtime');
        exit;
    }

    public function saveadvance(Request $request) {
        $date = date('Y-m-d', strtotime($request->date));
        $result = $this->employee_model->saveAdvance($request->employee_id, $request->amount, $date, $request->remark, $this->user_id, $this->admin_id);
        $this->setSuccess('Advance entry has been save successfully');
        header('Location: /admin/employee/advance');
        exit;
    }

}
