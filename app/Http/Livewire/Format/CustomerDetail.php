<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Session;

class CustomerDetail extends Component
{
    public $customerColumns;
    public $fonts;
    public $modal = 0;
    public $checkboxes = [];

    public function mount($columns = null)
    {
        $model = new InvoiceFormat();
        $app = new AppController();
        $merchant_id = $app->merchant_id;
        #get billing profile list
        $customerColumns = $model->getTableList('customer_mandatory_column', 'is_active', 1);
        $customerCustomColumns = $model->getTableList('customer_column_metadata', 'merchant_id', $merchant_id);
        $this->fonts = array('money' => 'fa-inr', 'text' => 'fa-font', 'pan' => 'fa-font', 'number' => 'fa-sort-numeric-asc', 'mobile' => 'fa-sort-numeric-asc', 'primary' => 'fa-anchor', 'textarea' => 'fa-file-text-o', 'date' => 'fa-calendar', 'email' => 'fa-envelope', 'time' => 'fa-clock-o');
        $customerColumns = $this->setCustomerDefaultColumn($customerColumns);
        $exist_columns = [];
        if (!empty($columns)) {
            foreach ($columns as $c) {
                $exist_columns[$c->customer_column_id] = $c->column_id;
            }
        }
        $int = 0;
        foreach ($customerColumns as $key => $row) {
            $column_id = null;
            if ($columns != null) {
                if (isset($exist_columns[$row->id])) {
                    $this->checkboxes[] = $int;
                    $column_id = $exist_columns[$row->id];
                    $row->is_default = 1;
                }
            } else {
                if ($row->is_default == 1) {
                    $this->checkboxes[] = $int;
                }
            }

            $this->customerColumns[] = array('type' => 'customer', 'column_id' => $column_id, 'id' => $row->id, 'column_name' => $row->column_name, 'datatype' => $row->datatype, 'is_default' => $row->is_default, 'is_mandatory' => $row->is_mandatory);
            $int++;
        }
        foreach ($customerCustomColumns as $row) {
            $column_id = null;
            $is_default = 0;
            if ($columns != null) {
                if (isset($exist_columns[$row->column_id])) {
                    $this->checkboxes[] = $int;
                    $column_id = $exist_columns[$row->column_id];
                    $is_default = 1;
                }
            }
            $this->customerColumns[] = array('type' => 'custom', 'column_id' => $column_id, 'id' => $row->column_id, 'column_name' => $row->column_name, 'datatype' => $row->column_datatype, 'is_default' => $is_default, 'is_mandatory' => 0);
            $int++;
        }
    }

    private function setCustomerDefaultColumn($customerColumns)
    {
        if (Session::has('customer_default_column')) {
            $default_column = Session::get('customer_default_column');
            if (isset($default_column['customer_code'])) {
                $customerColumns[0]->column_name = $default_column['customer_code'];
            }
            if (isset($default_column['customer_name'])) {
                $customerColumns[1]->column_name = $default_column['customer_name'];
            }
            if (isset($default_column['email'])) {
                $customerColumns[2]->column_name = $default_column['email'];
            }
            if (isset($default_column['mobile'])) {
                $customerColumns[3]->column_name = $default_column['mobile'];
            }
            if (isset($default_column['address'])) {
                $customerColumns[4]->column_name = $default_column['address'];
            }
            if (isset($default_column['city'])) {
                $customerColumns[5]->column_name = $default_column['city'];
            }
            if (isset($default_column['state'])) {
                $customerColumns[6]->column_name = $default_column['state'];
            }
            if (isset($default_column['zipcode'])) {
                $customerColumns[7]->column_name = $default_column['zipcode'];
            }
        }
        return $customerColumns;
    }

    public function submit($data)
    {
        foreach ($this->customerColumns as $k => $v) {
            if (in_array($k, $data)) {
                $this->customerColumns[$k]['is_default'] = 1;
            } else {
                $this->customerColumns[$k]['is_default'] = 0;
            }
        }
    }

    public function remove($id)
    {
        $this->customerColumns[$id]['is_default'] = 0;
    }

    public function modalShow($show)
    {
        $this->modal = $show;
    }

    public function render()
    {
        return view('livewire.format.customer-detail');
    }
}
