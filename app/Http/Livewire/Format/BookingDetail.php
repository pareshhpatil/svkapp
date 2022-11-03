<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;

class BookingDetail extends Component
{

    public $invoiceColumns = [];
    public $modal = 0;
    public $datatype = 'text';
    public $functions = [];
    public $paramMapping = [];
    public $position;
    public $functionId;
    public $columnName;
    public $invoiceSeq = [];
    public $mappingParam;
    public $newSeq = 0;
    public $prefix;
    public $seqNo;
    public $merchant_id;
    public $user_id;
    public $mappingValue;
    public $fonts;
    public $systemCol = 0;
    public $columnId = 0;

    public function mount($columns)
    {
        $app = new AppController();
        $this->merchant_id = $app->merchant_id;
        $this->user_id = $app->user_id;

        $this->setConfig($columns);
        $this->setDatatypeFunction();
    }

    public function setConfig($columns)
    {
        $this->fonts = array('money' => 'fa-inr', 'text' => 'fa-font', 'pan' => 'fa-font', 'number' => 'fa-sort-numeric-asc', 'primary' => 'fa-anchor', 'textarea' => 'fa-file-text-o', 'date' => 'fa-calendar', 'email' => 'fa-envelope', 'link' => 'fa-link', 'time' => 'fa-clock-o');
        foreach ($columns as $key => $row) {
            $config = [];
            $config['position'] = $row->position;
            $config['column_type'] = 'BDS';
            $config['headertablesave'] = $row->save_table_name;
            $config['headermandatory'] = $row->is_mandatory;
            $config['headercolumnposition'] = $row->column_position;
            $config['function_id'] = $row->function_id;
            $config['function_param'] = '';
            $config['function_val'] = '';
            $config['headerisdelete'] = $row->is_delete_allow;
            $config['headerdatatype'] = $row->column_datatype;
            $columns[$key]->readonly = '';
            $columns[$key]->icon = (isset($this->fonts[$row->column_datatype])) ? $this->fonts[$row->column_datatype] : 'fa-font';
            $columns[$key]->config = json_encode($config);
        }
        $this->invoiceColumns = json_decode(json_encode($columns), 1);
    }

    public function updatedfunctionId()
    {
        $this->paramMapping = [];
        if ($this->functionId > 0) {
            $mapping = $this->functions[$this->functionId]['mapping'];
            if ($mapping != '') {
                foreach (explode(',', $mapping) as $m) {
                    $this->paramMapping[] = array('id' => $m, 'value' => ucfirst(str_replace('_', ' ', $m)));
                }
            }
        }
    }

    public function newSequence($seq)
    {
        $this->newSeq = $seq;
    }

    public function saveSequence()
    {
        $model = new InvoiceFormat();
        $id = $model->saveSequence($this->merchant_id, $this->prefix, $this->seqNo, $this->user_id);
        $this->invoiceSeq[] = array('auto_invoice_id' => $id, 'prefix' => $this->prefix, 'val' => $this->seqNo);
        $this->prefix = '';
        $this->seqNo = '';
        $this->newSeq = 0;
        $this->mappingValue = $id;
    }

    public function updatedmappingParam()
    {
        if ($this->mappingParam == 'system_generated') {
            $model = new InvoiceFormat();
            #get invoice number sequence list
            $invoiceSeq = $model->getInvoiceSequence($this->merchant_id);
            $this->invoiceSeq = json_decode(json_encode($invoiceSeq), 1);
        }
    }

    public function updated()
    {
        $this->setDatatypeFunction();
    }

    public function setDatatypeFunction()
    {
        $model = new InvoiceFormat();
        $this->functions = [];
        $functions = $model->getTableList('column_function', 'datatype', $this->datatype);
        $functionsArray = json_decode(json_encode($functions), 1);
        foreach ($functionsArray as $row) {
            $this->functions[$row['function_id']] = $row;
        }
    }

    public function saveColumn($data)
    {
        $array = json_decode($data, 1);
        $column['column_name'] = $array['column'];
        $column['column_datatype'] = $array['datatype'];
        $this->datatype = $array['datatype'];
        $this->position = $array['position'];
        $this->columnId = $array['key'];
        $column['icon'] = (isset($this->fonts[$this->datatype])) ? $this->fonts[$this->datatype] : 'fa-font';
        if ($this->columnId != "") {
            $col = $this->invoiceColumns[$this->columnId];
            $carray = json_decode($col['config'], 1);
            $column['is_delete_allow'] = $col['is_delete_allow'];
            $column['readonly'] = $col['readonly'];
            $column['position'] = $carray['position'];
            $config['position'] = $carray['position'];
            $config['column_type'] = $carray['column_type'];
            $config['headertablesave'] = $carray['headertablesave'];
            $config['headermandatory'] = $carray['headermandatory'];
            $config['headercolumnposition'] = $carray['headercolumnposition'];
            $config['headerisdelete'] = $carray['headerisdelete'];
        } else {
            $column['is_delete_allow'] = 1;
            $column['readonly'] = "";
            $column['position'] = $this->position;
            $config = [];
            $config['position'] = $this->position;
            $config['column_type'] = 'BDS';
            $config['headertablesave'] = 'metadata';
            $config['headermandatory'] = 0;
            $config['headercolumnposition'] = '0';
            $config['headerisdelete'] = 1;
        }
        $config['function_id'] = $this->functionId;
        $config['function_param'] = $this->mappingParam;
        $config['function_val'] = $this->mappingValue;
        $config['headerdatatype'] = $this->datatype;
        $column['config'] = json_encode($config);
        if ($this->columnId > 0) {
            $this->invoiceColumns[$this->columnId] = $column;
        } else {
            $this->invoiceColumns[] = $column;
        }
        $this->modal = 0;
        $this->resetCol();
    }

    public function updateColumn($id)
    {
        $this->columnId = $id;
        $col = $this->invoiceColumns[$id];
        $config = json_decode($col['config'], 1);
        $this->functionId = $config['function_id'];
        $this->columnName = $col['column_name'];
        $this->datatype = $col['column_datatype'];
        $this->mappingParam = $config['function_param'];
        $this->mappingValue = $config['function_val'];
        $this->systemCol = ($config['headertablesave'] == 'request') ? 1 : 0;
        $this->setDatatypeFunction();
        $this->updatedfunctionId();
        $this->modal = 1;
    }

    public function resetCol()
    {
        $this->columnId = 0;
        $this->columnName = '';
        $this->datatype = 'text';
        $this->systemCol = 0;
        $this->paramMapping = [];
        $this->functionId = null;
    }

    public function modalShow($show, $position)
    {
        $this->resetCol();
        $this->position = $position;
        $this->modal = $show;
    }

    public function remove($id)
    {
        unset($this->invoiceColumns[$id]);
    }

    public function render()
    {
        return view('livewire.format.booking-detail');
    }
}
