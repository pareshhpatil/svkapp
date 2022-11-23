<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;

class ParticularDetail extends Component
{
    public $columns = [];
    public $particulars = [];
    public $particularColumns = [];
    public $columnCheckbox = [];
    public $columnName = [];
    public $modal = 0;

    public $product_list = [];
    protected $listeners = [
        'setParticularItem'
    ];

    public $template_type = '';

    public function mount($columns, $particularColumns, $defaultParticular = '', $template_type = '')

    {
        $this->columns = json_decode($columns, 1);
        $particularColumns = json_decode(json_encode($particularColumns), 1);


        $particularArray = [];
        foreach ($particularColumns as $key => $col) {
            if (isset($this->columns[$col['system_col_name']])) {
                $particularColumns[$key]['is_default'] = 1;
                $this->columnCheckbox[$col['system_col_name']] = true;
                $particularColumns[$key]['column_name'] = $this->columns[$col['system_col_name']];
            }
            $this->columnName[$col['system_col_name']] = $col['column_name'];
            $particularArray[$col['system_col_name']] = $particularColumns[$key];
        }

        $this->particularColumns = $particularArray;
        $this->template_type = $template_type;
        if ($defaultParticular != '') {
            $this->particulars = json_decode($defaultParticular, 1);
        }
    }

    public function modalShow($show)
    {
        $this->modal = $show;
    }

    public function submit($data)
    {
        $columns = [];
        foreach ($data as $row) {
            $col = json_decode($row, 1);
            $keys = array_keys($col);
            $columns[$keys[0]] = $col[$keys[0]];
        }

        $this->columns = [];
        foreach ($this->particularColumns as $k => $v) {
            if (isset($columns[$k])) {
                $this->particularColumns[$k]['is_default'] = 1;
                $this->columns[$k] = $this->columnName[$k];
            } else {
                $this->particularColumns[$k]['is_default'] = 0;
            }
        }
    }


    public function setParticularItem($val, $key)
    {
        $this->particulars[$key]=$val;
    }
    public function updated()
    {
    }

    public function remove($key)
    {
        unset($this->particulars[$key]);
    }
    public function setColumns()
    {
        $this->columns = [];
        foreach ($this->particularColumns as $k => $v) {
            if (isset($this->columnCheckbox[$k])) {
                if ($this->columnCheckbox[$k] == true) {
                    $this->columns[$k] = $this->columnName[$k];
                }
            }
        }
        $this->modal = 0;
    }

    public function addParticular()
    {
        $this->particulars[] = '';
    }
    public function render()
    {
        return view('livewire.format.particular-detail');
    }
}
