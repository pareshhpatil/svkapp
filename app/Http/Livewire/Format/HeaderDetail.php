<?php

namespace App\Http\Livewire\Format;

use Livewire\Component;
use App\Model\InvoiceFormat;

class HeaderDetail extends Component
{
    public $headerColumn;
    public $columns;
    public $fonts;
    public $profileId;
    public $profileDetail;
    public $modal;
    public $checkboxes = [];
    public $listeners = ['changeProfile'];
    public function mount($columns, $profileId = 0, $metaColumns = null)
    {
        $existColumn = [];
        if (!empty($metaColumns)) {
            foreach ($metaColumns as $c) {
                $existColumn[$c->column_position] = $c->column_id;
            }
        }
        $this->modal = 0;
        $this->fonts = array('money' => 'fa-inr', 'text' => 'fa-font', 'pan' => 'fa-font', 'number' => 'fa-sort-numeric-asc', 'primary' => 'fa-anchor', 'textarea' => 'fa-file-text-o', 'date' => 'fa-calendar', 'email' => 'fa-envelope', 'link' => 'fa-link');
        $this->headerColumn = json_decode(json_encode($columns), 1);
        foreach ($this->headerColumn as $k => $v) {
            if (isset($existColumn[$v['id']])) {
                $this->headerColumn[$k]['column_id'] = $existColumn[$v['id']];
                $this->headerColumn[$k]['is_default'] = 1;
            } else {
                $this->headerColumn[$k]['column_id'] = 0;
                if (!empty($metaColumns)) {
                    $this->headerColumn[$k]['is_default'] = 0;
                }
            }
        }
        $this->changeProfile($profileId);
    }

    public function render()
    {
        return view('livewire.format.header-detail');
    }

    public function remove($id)
    {
        $this->headerColumn[$id]['is_default'] = 0;
    }

    public function modalShow($show)
    {
        $this->modal = $show;
    }

    public function changeProfile($profileId)
    {
        $this->profileId = $profileId;
        $model = new InvoiceFormat();
        if ($profileId > 0) {
            $profileDetail = $model->getTableRow('merchant_billing_profile', 'id', $profileId);
            $this->profileDetail = json_decode(json_encode($profileDetail), 1);
        }
        $this->headerColumn = $this->getColumnValues($this->headerColumn);
    }

    public function updated()
    {
        foreach ($this->checkboxes as $k) {
            $this->headerColumn[$k]['is_default'] = 1;
        }
    }

    public function submit($data)
    {
        foreach ($this->headerColumn as $k => $v) {
            if (in_array($k, $data)) {
                $this->headerColumn[$k]['is_default'] = 1;
            } else {
                $this->headerColumn[$k]['is_default'] = 0;
            }
        }
        
    }


    function getColumnValues($columns)
    {
        foreach ($columns as $key => $row) {
            $val = '';
            switch ($row['column_name']) {
                case 'Company name':
                    $val = $this->profileDetail['company_name'];
                    break;
                case 'Merchant contact':
                    $val = $this->profileDetail['business_contact'];
                    break;
                case 'Merchant email':
                    $val = $this->profileDetail['business_email'];
                    break;
                case 'Merchant address':
                    $val = $this->profileDetail['address'];
                    break;
                case 13:
                    $val = '';
                    break;
                case 'GSTIN Number':
                    $val = $this->profileDetail['gst_number'];
                    break;
                case 'CIN Number':
                    $val = $this->profileDetail['cin_no'];
                    break;
                case 'Company pan':
                    $val = $this->profileDetail['pan'];
                    break;
                case 'Company TAN':
                    $val = $this->profileDetail['tan'];
                    break;
            }
            if ($row['is_default'] == 1) {
                $this->checkboxes[] = $key;
            }
            $columns[$key]['value'] = $val;
            $columns[$key]['icon'] = (isset($this->fonts[$row['datatype']])) ? $this->fonts[$row['datatype']] : 'fa-font';
        }
        return $columns;
    }
}
