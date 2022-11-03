<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;
use App\Http\Controllers\AppController;

class InvoiceDetail extends Component
{

    public $invoiceColumns = [];
    public $modal = 0;
    public $datatype = 'text';
    public $functions = [];
    public $paramMapping = [];
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
    public $plugin_function = 0;
    public $template_type = '';
    public $columnTitle = 'Field property';
    public $viewLayout = null;
    public $listeners = ['modalFunctionShow', 'removePlugin', 'saveColumn'];

    public function mount($columns,$viewTemplate=null,$details = null)
    {
        $app = new AppController();
        $this->merchant_id = $app->merchant_id;
        $this->user_id = $app->user_id;

        $this->setConfig($columns);
        $this->setDatatypeFunction();
        $this->template_type = $details->template_type;
        $this->viewLayout = $viewTemplate;
    }

    public function setConfig($columns)
    {
        $this->fonts = array('money' => 'fa-inr', 'text' => 'fa-font', 'pan' => 'fa-font', 'number' => 'fa-sort-numeric-asc', 'mobile' => 'fa-sort-numeric-asc', 'primary' => 'fa-anchor', 'textarea' => 'fa-file-text-o', 'date' => 'fa-calendar', 'email' => 'fa-envelope', 'link' => 'fa-link');
        foreach ($columns as $key => $row) {
            $config = [];
            $config['position'] = 'R';
            $config['column_type'] = 'H';
            $config['headertablesave'] = $row->save_table_name;
            $config['headermandatory'] = $row->is_mandatory;
            $config['headercolumnposition'] = $row->column_position;
            $config['function_id'] = $row->function_id;
            $config['function_param'] = (isset($row->param)) ? $row->param : '';
            $config['function_val'] = (isset($row->param_value)) ? $row->param_value : '';
            $config['headerisdelete'] = $row->is_delete_allow;
            $config['headerdatatype'] = $row->column_datatype;
            if ($row->save_table_name == 'request') {
                $columns[$key]->readonly = 'readonly';
            } else {
                $columns[$key]->readonly = '';
            }
            $columns[$key]->column_id = (isset($row->column_id)) ? $row->column_id : 0;
            $columns[$key]->icon = (isset($this->fonts[$row->column_datatype])) ? $this->fonts[$row->column_datatype] : 'fa-font';
            $columns[$key]->config = json_encode($config);
        }
        $this->invoiceColumns = json_decode(json_encode($columns), 1);
    }

    public function updatedfunctionId()
    {
        $this->paramMapping = [];
        if ($this->functionId > 0) {
            if (isset($this->functions[$this->functionId])) {
                $mapping = $this->functions[$this->functionId]['mapping'];
                if ($mapping != '') {
                    foreach (explode(',', $mapping) as $m) {
                        $this->paramMapping[] = array('id' => $m, 'value' => ucfirst(str_replace('_', ' ', $m)));
                    }
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
        if ($this->seqNo == '') {
            $this->seqNo = 0;
        }
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
        $plugin_function = array(1, 4, 9, 13);
        $functions = $model->getTableList('column_function', 'datatype', $this->datatype);
        $functionsArray = json_decode(json_encode($functions), 1);
        
        foreach ($functionsArray as $row) {
            if ($this->functionId != $row['function_id'] && in_array($row['function_id'], $plugin_function)) {
            } else {
                $this->functions[$row['function_id']] = $row;
            }
        }
    }

    public function saveColumn()
    {
        $column = [];
        $column['column_name'] = $this->columnName;
        $column['column_id'] = 0;
        $column['column_datatype'] = $this->datatype;
        $column['icon'] = (isset($this->fonts[$this->datatype])) ? $this->fonts[$this->datatype] : 'fa-font';
        $column['function_id'] = $this->functionId;
        if ($this->columnId > 0) {
            $col = $this->invoiceColumns[$this->columnId];
            $carray = json_decode($col['config'], 1);
            $column['is_delete_allow'] = $col['is_delete_allow'];
            $column['readonly'] = $col['readonly'];
            $config['position'] = $carray['position'];
            $config['column_type'] = $carray['column_type'];
            $config['headertablesave'] = $carray['headertablesave'];
            $config['headermandatory'] = $carray['headermandatory'];
            $config['headercolumnposition'] = $carray['headercolumnposition'];
            $config['headerisdelete'] = $carray['headerisdelete'];
        } else {
            $column['is_delete_allow'] = 1;
            $column['readonly'] = "";
            $config = [];
            $config['position'] = 'R';
            $config['column_type'] = 'H';
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
        $this->updatedmappingParam();
        $this->modal = 1;
        if (in_array($this->functionId, array(1, 4, 9))) {
            $this->plugin_function = 1;
            $this->columnTitle = $col['column_name'];
        }
    }

    public function resetCol()
    {
        $this->columnId = 0;
        $this->columnName = '';
        $this->datatype = 'text';
        $this->systemCol = 0;
        $this->paramMapping = [];
        $this->functionId = null;
        $this->columnTitle = 'Field property';
        $this->setDatatypeFunction();
    }

    public function modalShow($show)
    {
        $this->plugin_function = 0;
        $this->resetCol();
        $this->modal = $show;
    }

    public function modalFunctionShow($show, $functionId)
    {
        $this->resetCol();
        $model = new InvoiceFormat();
        $function = $model->getTableRow('column_function', 'function_id', $functionId);
        $this->datatype = $function->datatype;
        $this->columnName = $function->function_name;
        $this->functionId = $functionId;
        $this->setDatatypeFunction();
        $this->plugin_function = 1;
        $this->updatedfunctionId();
        $this->modal = $show;
        if (in_array($functionId, array(1, 4, 9))) {
            $this->columnTitle = $function->function_name;
        } else {
            $this->columnTitle = 'Field property';
        }
    }

    public function remove($id)
    {
        unset($this->invoiceColumns[$id]);
    }

    public function removePlugin($function_id)
    {
        foreach ($this->invoiceColumns as $k => $row) {
            $array = json_decode($row['config'], 1);
            if ($array['function_id'] == $function_id) {
                unset($this->invoiceColumns[$k]);
            }
        }
    }

    public function render()
    {
        return view('livewire.format.invoice-detail');
    }
}
